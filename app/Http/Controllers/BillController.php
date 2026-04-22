<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Consultant;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['patient','consultant','createdBy']);

        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('bill_no','like',"%$s%")
                ->orWhereHas('patient', fn($p) => $p->where('name','like',"%$s%")
                    ->orWhere('phone','like',"%$s%")
                    ->orWhere('patient_code','like',"%$s%")));
        }
        if ($request->status)   $query->where('status', $request->status);
        if ($request->date_from) $query->where('bill_date', '>=', $request->date_from);
        if ($request->date_to)   $query->where('bill_date', '<=', $request->date_to);

        $bills       = $query->latest()->paginate(25)->withQueryString();
        $todayTotal  = Bill::whereDate('created_at', today())->whereIn('status',['paid','partial'])->sum('paid_amount');
        $pendingAmt  = Bill::whereIn('status',['draft','partial'])->sum(DB::raw('net_total - paid_amount'));
        return view('bills.index', compact('bills','todayTotal','pendingAmt'));
    }

    public function create(Request $request)
    {
        $patient     = $request->patient_id ? Patient::find($request->patient_id) : null;
        $consultants = Consultant::where('status','active')->orderBy('name')->get();
        $services    = Service::active()->orderBy('category')->orderBy('name')->get();
        return view('bills.create', compact('patient','consultants','services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'bill_date'    => 'required|date',
            'discount'     => 'nullable|numeric|min:0',
            'paid_amount'  => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,bank,card,bkash,nagad,rocket',
        ]);

        DB::transaction(function () use ($request) {
            $discount = $request->discount ?? 0;

            $bill = Bill::create([
                'patient_id'    => $request->patient_id,
                'consultant_id' => $request->consultant_id ?: null,
                'bill_date'     => $request->bill_date,
                'discount'      => $discount,
                'subtotal'      => 0,
                'net_total'     => 0,
                'paid_amount'   => 0,
                'notes'         => $request->notes,
                'created_by'    => Auth::id(),
                'status'        => 'draft',
            ]);

            $this->buildItems($bill, $request);
            $bill->recalculate();

            if ($request->boolean('adjust_from_advance')) {
                $patient = \App\Models\Patient::find($request->patient_id);
                $amount  = min((float)$bill->net_total, (float)$patient->advance_balance);
                if ($amount > 0) {
                    $patient->decrement('advance_balance', $amount);
                    $bill->update([
                        'paid_amount'    => $amount,
                        'payment_method' => 'advance',
                        'payment_date'   => today(),
                        'payment_meta'   => ['note' => 'Adjusted from patient advance balance'],
                    ]);
                    $bill->recalculate();
                }
            } elseif ($request->paid_amount > 0) {
                $bill->update([
                    'paid_amount'    => $request->paid_amount,
                    'payment_method' => $request->payment_method,
                    'payment_date'   => today(),
                    'transaction_id' => $request->transaction_id,
                    'payment_meta'   => $this->extractPaymentMeta($request),
                ]);
                $bill->recalculate();
            }

            $this->redirectBill = $bill->id;
        });

        return redirect()->route('bills.show', $this->redirectBill)->with('success', 'Bill created successfully.');
    }

    private int $redirectBill = 0;

    private function buildItems(Bill $bill, Request $request): void
    {
        if ($request->boolean('add_reg_fee')) {
            BillItem::create([
                'bill_id'     => $bill->id,
                'description' => 'Registration Fee (New Patient)',
                'quantity'    => 1,
                'unit_rate'   => 200,
                'amount'      => 200,
            ]);
        }

        if ($request->consultant_id) {
            $consultant = Consultant::find($request->consultant_id);
            if ($consultant && $consultant->consultation_fee > 0) {
                BillItem::create([
                    'bill_id'     => $bill->id,
                    'description' => 'Consultation Fee — Dr. ' . $consultant->name,
                    'quantity'    => 1,
                    'unit_rate'   => $consultant->consultation_fee,
                    'amount'      => $consultant->consultation_fee,
                ]);
            }
        }

        foreach ($request->service_ids ?? [] as $sid) {
            $service = Service::find($sid);
            if ($service) {
                BillItem::create([
                    'bill_id'     => $bill->id,
                    'service_id'  => $service->id,
                    'description' => $service->name,
                    'quantity'    => 1,
                    'unit_rate'   => $service->charge,
                    'amount'      => $service->charge,
                ]);
            }
        }

        foreach ($request->custom_desc ?? [] as $i => $desc) {
            if (trim($desc) === '') continue;
            $qty  = (int) ($request->custom_qty[$i]  ?? 1);
            $rate = (float) ($request->custom_rate[$i] ?? 0);
            if ($qty > 0 && $rate > 0) {
                BillItem::create([
                    'bill_id'     => $bill->id,
                    'description' => $desc,
                    'quantity'    => $qty,
                    'unit_rate'   => $rate,
                    'amount'      => $qty * $rate,
                ]);
            }
        }
    }

    public function show(Bill $bill)
    {
        $bill->load(['patient.couple','consultant','items.service','createdBy']);
        return view('bills.show', compact('bill'));
    }

    public function pay(Request $request, Bill $bill)
    {
        // Advance balance adjustment
        if ($request->adjust_from_advance) {
            $patient = $bill->patient;
            $amount  = min((float)$bill->balance, (float)$patient->advance_balance);
            if ($amount <= 0) {
                return back()->with('error', 'No advance balance available for this patient.');
            }
            $patient->decrement('advance_balance', $amount);
            $bill->update([
                'paid_amount'    => $bill->paid_amount + $amount,
                'payment_method' => 'advance',
                'payment_date'   => today(),
                'payment_meta'   => ['note' => 'Adjusted from patient advance balance'],
            ]);
            $bill->recalculate();
            return redirect()->route('bills.show', $bill)->with('success', '৳' . number_format($amount, 2) . ' adjusted from advance balance.');
        }

        $request->validate([
            'paid_amount'    => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank,card,bkash,nagad,rocket',
        ]);

        $bill->update([
            'paid_amount'    => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_date'   => today(),
            'payment_meta'   => $this->extractPaymentMeta($request),
        ]);
        $bill->recalculate();

        return redirect()->route('bills.show', $bill)->with('success', 'Payment recorded.');
    }

    public function creditAdvance(Request $request, \App\Models\Patient $patient)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        $patient->increment('advance_balance', $request->amount);
        return back()->with('success', '৳' . number_format($request->amount, 2) . ' credited to advance balance.');
    }

    private function extractPaymentMeta(Request $request): ?array
    {
        $method = $request->payment_method;
        if ($method === 'bank') {
            return [
                'bank_name'       => $request->meta_bank_name,
                'account_holder'  => $request->meta_account_holder,
                'account_number'  => $request->meta_account_number,
            ];
        }
        if ($method === 'card') {
            return [
                'card_type'   => $request->meta_card_type,
                'card_number' => $request->meta_card_number,
                'card_holder' => $request->meta_card_holder,
            ];
        }
        if (in_array($method, ['bkash','nagad','rocket'])) {
            return [
                'mobile_number' => $request->meta_mobile_number,
            ];
        }
        return null;
    }

    public function consultantFee(Consultant $consultant)
    {
        return response()->json(['fee' => $consultant->consultation_fee]);
    }
}
