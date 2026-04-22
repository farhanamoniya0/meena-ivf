<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\PatientPackage;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('activePackage')->has('packages');
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('phone','like',"%$s%")->orWhere('patient_code','like',"%$s%"));
        }
        $patients = $query->latest()->paginate(20)->withQueryString();
        return view('billing.index', compact('patients'));
    }

    public function patient(Patient $patient)
    {
        $patient->load(['packages.ivfPackage','packages.payments','consultant']);
        return view('billing.patient', compact('patient'));
    }

    public function payForm(PatientPackage $package)
    {
        $package->load(['patient','ivfPackage','payments']);
        return view('billing.pay', compact('package'));
    }

    public function payStore(Request $request, PatientPackage $package)
    {
        $data = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank,card,bkash,nagad,rocket',
            'transaction_id' => 'nullable|string|max:100',
            'bank_name'      => 'nullable|string|max:100',
            'reference'      => 'nullable|string|max:100',
            'remarks'        => 'nullable|string',
        ]);

        $data['patient_package_id'] = $package->id;
        $data['patient_id']         = $package->patient_id;
        $data['received_by']        = Auth::id();
        $data['status']             = 'approved';
        $data['approved_by']        = Auth::id();
        $data['approved_at']        = now();

        $payment = Payment::create($data);

        // Credit patient advance balance so it can be used to adjust future bills
        $package->patient->increment('advance_balance', $data['amount']);

        if ($package->remaining <= 0) {
            $package->update(['status' => 'completed']);
        }

        return redirect()->route('billing.receipt', $payment)->with('success', 'Payment recorded.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['patient','patientPackage.ivfPackage','receivedBy']);
        return view('billing.receipt', compact('payment'));
    }

    public function todayPayments()
    {
        // IVF Package Payments (payments table)
        $pkgPayments = Payment::with(['patient','receivedBy'])
            ->whereDate('created_at', today())
            ->where('status', 'approved')
            ->latest()->get();

        // OP Bill Payments (bills table)
        $opBills = Bill::with(['patient','createdBy'])
            ->whereDate('payment_date', today())
            ->where('paid_amount', '>', 0)
            ->latest()->get();

        // Unify into a single collection
        $transactions = collect();

        foreach ($pkgPayments as $p) {
            $transactions->push([
                'type'        => 'package',
                'ref_no'      => $p->receipt_no,
                'ref_url'     => route('billing.receipt', $p),
                'patient'     => $p->patient,
                'amount'      => (float) $p->amount,
                'method'      => $p->payment_method ?? 'cash',
                'txn_id'      => $p->transaction_id,
                'received_by' => $p->receivedBy?->name ?? 'System',
                'time'        => $p->created_at,
            ]);
        }

        foreach ($opBills as $b) {
            $transactions->push([
                'type'        => 'bill',
                'ref_no'      => $b->bill_no,
                'ref_url'     => route('bills.show', $b),
                'patient'     => $b->patient,
                'amount'      => (float) $b->paid_amount,
                'method'      => $b->payment_method ?? 'cash',
                'txn_id'      => $b->transaction_id,
                'received_by' => $b->createdBy?->name ?? 'System',
                'time'        => $b->updated_at,
            ]);
        }

        $transactions = $transactions->sortByDesc('time')->values();

        $summary = [
            'cash'    => $transactions->where('method', 'cash')->sum('amount'),
            'bank'    => $transactions->where('method', 'bank')->sum('amount'),
            'card'    => $transactions->where('method', 'card')->sum('amount'),
            'bkash'   => $transactions->where('method', 'bkash')->sum('amount'),
            'nagad'   => $transactions->where('method', 'nagad')->sum('amount'),
            'rocket'  => $transactions->where('method', 'rocket')->sum('amount'),
            'advance' => $transactions->where('method', 'advance')->sum('amount'),
            'total'   => $transactions->sum('amount'),
        ];

        return view('billing.today', compact('transactions', 'summary'));
    }
}
