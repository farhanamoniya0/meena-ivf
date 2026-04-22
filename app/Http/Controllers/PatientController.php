<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Consultant;
use App\Models\Couple;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('consultant')->latest();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('phone','like',"%$s%")->orWhere('patient_code','like',"%$s%"));
        }
        if ($request->type)         $query->where('registration_type', $request->type);
        if ($request->created_today) $query->whereDate('created_at', today());
        $patients = $query->paginate(20)->withQueryString();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $consultants = Consultant::where('status','active')->orderBy('name')->get();
        $services    = Service::active()->orderBy('category')->orderBy('name')->get();
        return view('patients.create', compact('consultants','services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'nullable|string|max:100',
            'phone'         => 'required|string|max:20',
            'dob'           => 'nullable|date',
            'gender'        => 'required|in:female,male,other',
            'consultant_id' => 'nullable|exists:consultants,id',
        ]);

        $firstName = trim($request->first_name);
        $lastName  = trim($request->last_name ?? '');
        $fullName  = $lastName ? "$firstName $lastName" : $firstName;

        $patient = Patient::create([
            'first_name'        => $firstName,
            'last_name'         => $lastName ?: null,
            'name'              => $fullName,
            'dob'               => $request->dob,
            'age'               => $request->age,
            'gender'            => $request->gender,
            'marital_status'    => $request->marital_status,
            'phone'             => $request->phone,
            'phone_alt'         => $request->phone_alt,
            'occupation'        => $request->occupation,
            'source_type'       => $request->source_type,
            'address'           => $request->address,
            'post_code'         => $request->post_code,
            'thana'             => $request->thana,
            'district'          => $request->district,
            'division'          => $request->division,
            'blood_group'       => $request->blood_group,
            'height_cm'         => $request->height_cm,
            'weight_kg'         => $request->weight_kg,
            'nid_number'        => $request->nid_number,
            'referred_by'       => $request->referred_by,
            'consultant_id'     => $request->consultant_id,
            'notes'             => $request->notes,
            'registration_type' => 'full',
            'photo'             => $this->saveCameraPhoto($request->photo_data, 'patients/photos'),
        ]);

        // Partner details
        $partnerFirst = trim($request->partner_first_name ?? '');
        if ($partnerFirst) {
            $partnerLast  = trim($request->partner_last_name ?? '');
            $partnerPhoto = $this->saveCameraPhoto($request->partner_photo_data, 'patients/photos');
            Couple::create([
                'patient_id'            => $patient->id,
                'husband_name'          => $partnerFirst . ($partnerLast ? " $partnerLast" : ''),
                'husband_dob'           => $request->partner_dob,
                'husband_age'           => $request->partner_age,
                'husband_phone'         => $request->partner_phone,
                'husband_occupation'    => $request->partner_occupation,
                'husband_blood_group'   => $request->partner_blood_group,
                'husband_photo'         => $partnerPhoto,
                'partner_first_name'    => $partnerFirst,
                'partner_last_name'     => $partnerLast ?: null,
                'partner_gender'        => $request->partner_gender,
                'partner_marital_status'=> $request->partner_marital_status,
                'partner_phone'         => $request->partner_phone,
                'partner_occupation'    => $request->partner_occupation,
                'partner_blood_group'   => $request->partner_blood_group,
                'partner_height_cm'     => $request->partner_height_cm,
                'partner_weight_kg'     => $request->partner_weight_kg,
                'partner_address'       => $request->partner_address,
                'partner_post_code'     => $request->partner_post_code,
                'partner_thana'         => $request->partner_thana,
                'partner_district'      => $request->partner_district,
                'partner_division'      => $request->partner_division,
            ]);
        }

        // Create visit (appointment) if date provided
        if ($request->filled('visit_date')) {
            \App\Models\Appointment::create([
                'patient_id'       => $patient->id,
                'consultant_id'    => $request->consultant_id,
                'appointment_date' => $request->visit_date,
                'appointment_time' => $request->visit_time,
                'type'             => $request->visit_reason ?? 'new_patient',
                'notes'            => $request->referral_type ? 'Referral: ' . $request->referral_type . ($request->referred_by ? ' — ' . $request->referred_by : '') : null,
                'status'           => 'scheduled',
                'created_by'       => Auth::id(),
            ]);
        }

        $bill = $this->createRegistrationBill($patient, $request);

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'patient_code' => $patient->patient_code,
                'patient_name' => $patient->name,
                'bill_url'     => route('bills.show', $bill) . '?print=1',
            ]);
        }

        return redirect()->route('bills.show', $bill)->with('success', 'Patient registered! Code: ' . $patient->patient_code);
    }

    public function show(Patient $patient)
    {
        $patient->load(['consultant','couple','packages.ivfPackage','payments','appointments.consultant']);
        $bills = Bill::with('items')->where('patient_id', $patient->id)->latest()->get();
        return view('patients.show', compact('patient','bills'));
    }

    public function edit(Patient $patient)
    {
        $consultants = Consultant::where('status','active')->orderBy('name')->get();
        $patient->load('couple');
        return view('patients.edit', compact('patient','consultants'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'phone'         => 'required|string|max:20',
            'age'           => 'nullable|integer',
            'dob'           => 'nullable|date',
            'gender'        => 'required|in:female,male,other',
            'address'       => 'nullable|string',
            'nid_number'    => 'nullable|string',
            'blood_group'   => 'nullable|string',
            'religion'      => 'nullable|string',
            'occupation'    => 'nullable|string',
            'referred_by'   => 'nullable|string',
            'consultant_id' => 'nullable|exists:consultants,id',
            'notes'         => 'nullable|string',
            'status'        => 'required|in:active,inactive,completed',
        ]);

        $newPhoto = $this->saveCameraPhoto($request->photo_data, 'patients/photos');
        if ($newPhoto) $data['photo'] = $newPhoto;

        $patient->update($data);

        if ($request->filled('husband_name')) {
            $couple = $patient->couple ?? new Couple(['patient_id' => $patient->id]);
            $couple->fill([
                'husband_name'        => $request->husband_name,
                'husband_dob'         => $request->husband_dob,
                'husband_age'         => $request->husband_age,
                'husband_phone'       => $request->husband_phone,
                'husband_nid'         => $request->husband_nid,
                'husband_occupation'  => $request->husband_occupation,
                'husband_blood_group' => $request->husband_blood_group,
                'marriage_date'       => $request->marriage_date,
                'medical_history'     => $request->medical_history,
            ])->save();
        }

        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated.');
    }

    public function quickCreate()
    {
        $consultants = Consultant::where('status','active')->orderBy('name')->get();
        $services    = Service::active()->orderBy('category')->orderBy('name')->get();
        return view('patients.quick', compact('consultants','services'));
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'phone'         => 'required|string|max:20',
            'age'           => 'nullable|integer',
            'gender'        => 'required|in:female,male,other',
            'consultant_id' => 'nullable|exists:consultants,id',
        ]);

        $data['photo']             = $this->saveCameraPhoto($request->photo_data, 'patients/photos');
        $data['registration_type'] = 'quick';
        $patient = Patient::create($data);

        $bill = $this->createRegistrationBill($patient, $request, isNew: true);
        return redirect()->route('bills.show', $bill)->with('success', 'Quick registered! Code: ' . $patient->patient_code);
    }

    public function search(Request $request)
    {
        $term     = $request->get('q','');
        $patients = Patient::where('name','like',"%$term%")
            ->orWhere('phone','like',"%$term%")
            ->orWhere('patient_code','like',"%$term%")
            ->select('id','patient_code','name','phone','gender','advance_balance')
            ->take(10)->get();
        return response()->json($patients);
    }

    private function saveCameraPhoto(?string $base64, string $folder): ?string
    {
        if (! $base64 || ! str_starts_with($base64, 'data:image')) return null;
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = str_replace(' ', '+', $imageData);
        $decoded   = base64_decode($imageData);
        if (! $decoded) return null;
        $filename  = $folder . '/' . uniqid('cam_', true) . '.jpg';
        Storage::disk('public')->put($filename, $decoded);
        return $filename;
    }

    private function createRegistrationBill(Patient $patient, Request $request): Bill
    {
        $bill = Bill::create([
            'patient_id'    => $patient->id,
            'consultant_id' => $patient->consultant_id,
            'bill_date'     => $request->bill_date ?? today(),
            'discount'      => $request->bill_discount ?? 0,
            'subtotal'      => 0,
            'net_total'     => 0,
            'paid_amount'   => 0,
            'created_by'    => Auth::id(),
            'notes'         => $request->notes,
            'status'        => 'draft',
        ]);

        // Services selected from dropdown
        foreach ($request->service_ids ?? [] as $sid) {
            $service = Service::find($sid);
            if ($service) {
                BillItem::create([
                    'bill_id'     => $bill->id,
                    'service_id'  => $service->id,
                    'description' => $service->name,
                    'quantity'    => 1,
                    'unit_rate'   => $service->charge,
                    'discount'    => 0,
                    'amount'      => $service->charge,
                ]);
            }
        }

        // Custom bill items
        $descs = $request->custom_desc ?? [];
        $qtys  = $request->custom_qty  ?? [];
        $rates = $request->custom_rate ?? [];
        $discs = $request->custom_disc ?? [];
        foreach ($descs as $i => $desc) {
            if (!trim($desc)) continue;
            $qty  = max(1, (int)($qtys[$i] ?? 1));
            $rate = (float)($rates[$i] ?? 0);
            $disc = (float)($discs[$i] ?? 0);
            BillItem::create([
                'bill_id'     => $bill->id,
                'description' => $desc,
                'quantity'    => $qty,
                'unit_rate'   => $rate,
                'discount'    => $disc,
                'amount'      => max(0, $qty * $rate - $disc),
            ]);
        }

        $bill->recalculate();

        // Record payment if paid
        if ($request->filled('paid_amount') && (float)$request->paid_amount > 0) {
            $bill->update([
                'paid_amount'    => $request->paid_amount,
                'payment_method' => $request->payment_method ?? 'cash',
                'payment_date'   => today(),
                'transaction_id' => $request->transaction_id,
                'payment_meta'   => $this->buildPaymentMeta($request),
            ]);
            $bill->recalculate();
        }

        return $bill;
    }

    private function buildPaymentMeta(Request $request): ?array
    {
        $method = $request->payment_method;
        if ($method === 'bank') return ['bank_name' => $request->meta_bank_name, 'account_holder' => $request->meta_account_holder, 'account_number' => $request->meta_account_number];
        if ($method === 'card') return ['card_type' => $request->meta_card_type, 'card_number' => $request->meta_card_number, 'card_holder' => $request->meta_card_holder];
        if (in_array($method, ['bkash','nagad','rocket'])) return ['mobile_number' => $request->meta_mobile_number];
        return null;
    }
}
