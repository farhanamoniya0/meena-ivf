@extends('layouts.app')
@section('title','Patient: '.$patient->name)
@section('page-title','Patient Profile')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div class="d-flex align-items-center gap-3">
    @if($patient->photo)
    <img src="{{ asset('storage/'.$patient->photo) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;">
    @else
    <div style="width:56px;height:56px;border-radius:50%;background:#17a589;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;">{{ strtoupper(substr($patient->name,0,1)) }}</div>
    @endif
    <div>
      <h5 class="fw-700 mb-0">{{ $patient->name }}</h5>
      <span class="badge bg-primary">{{ $patient->patient_code }}</span>
      <span class="badge bg-{{ ['active'=>'success','inactive'=>'secondary','completed'=>'info'][$patient->status] }} ms-1">{{ ucfirst($patient->status) }}</span>
      @if($patient->advance_balance > 0)
      <span class="badge ms-1" style="background:#e0f7fa;color:#00695c;border:1px solid #b2dfdb;"><i class="bi bi-piggy-bank me-1"></i>Advance: ৳{{ number_format($patient->advance_balance, 2) }}</span>
      @endif
    </div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
    <a href="{{ route('billing.patient', $patient) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-receipt me-1"></i>Billing</a>
    <a href="{{ route('packages.assign') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-box me-1"></i>Assign Package</a>
    <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-outline-info"><i class="bi bi-calendar-plus me-1"></i>Appointment</a>
    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#creditAdvModal"><i class="bi bi-piggy-bank me-1"></i>Add Advance</button>
  </div>
</div>

<div class="row g-3">
  {{-- Info Card --}}
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header"><i class="bi bi-info-circle me-2"></i>Patient Details</div>
      <div class="card-body">
        <table class="table table-sm mb-0" style="font-size:.83rem;">
          <tr><td class="text-muted fw-500">Phone</td><td>{{ $patient->phone }}</td></tr>
          <tr><td class="text-muted fw-500">Age</td><td>{{ $patient->age ?? ($patient->dob ? $patient->dob->age.' yrs' : '—') }}</td></tr>
          <tr><td class="text-muted fw-500">Gender</td><td>{{ ucfirst($patient->gender) }}</td></tr>
          <tr><td class="text-muted fw-500">Blood Group</td><td>{{ $patient->blood_group ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Religion</td><td>{{ $patient->religion ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Occupation</td><td>{{ $patient->occupation ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">NID</td><td>{{ $patient->nid_number ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Address</td><td>{{ $patient->address ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Consultant</td><td>{{ $patient->consultant?->name ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Referred By</td><td>{{ $patient->referred_by ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Registered</td><td>{{ $patient->created_at->format('d M Y') }}</td></tr>
        </table>
      </div>
    </div>

    @if($patient->couple)
    <div class="card">
      <div class="card-header"><i class="bi bi-people me-2 text-success"></i>Husband Details</div>
      <div class="card-body">
        <table class="table table-sm mb-0" style="font-size:.83rem;">
          <tr><td class="text-muted fw-500">Name</td><td>{{ $patient->couple->husband_name }}</td></tr>
          <tr><td class="text-muted fw-500">Phone</td><td>{{ $patient->couple->husband_phone ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Age</td><td>{{ $patient->couple->husband_age ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Blood</td><td>{{ $patient->couple->husband_blood_group ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">NID</td><td>{{ $patient->couple->husband_nid ?? '—' }}</td></tr>
          <tr><td class="text-muted fw-500">Marriage Date</td><td>{{ $patient->couple->marriage_date?->format('d M Y') ?? '—' }}</td></tr>
        </table>
      </div>
    </div>
    @endif
  </div>

  <div class="col-md-8">
    {{-- Packages & Billing --}}
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-fill text-primary me-2"></i>IVF Packages & Balance</span>
        <a href="{{ route('packages.assign') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Assign</a>
      </div>
      <div class="card-body p-0">
        @forelse($patient->packages as $pkg)
        <div class="p-3 border-bottom">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong>{{ $pkg->ivfPackage->name }}</strong>
              <span class="badge ms-1 {{ $pkg->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($pkg->status) }}</span>
            </div>
            @if($pkg->status=='active')
            <a href="{{ route('billing.pay', $pkg) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Pay</a>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-4 text-center">
              <div style="font-size:.7rem;color:#6b7280;">Package Amount</div>
              <div class="fw-700 text-dark">৳{{ number_format($pkg->total_amount) }}</div>
              @if($pkg->discount > 0)<div style="font-size:.7rem;color:#e65100;">Discount: ৳{{ number_format($pkg->discount) }}</div>@endif
            </div>
            <div class="col-4 text-center">
              <div style="font-size:.7rem;color:#6b7280;">Paid Amount</div>
              <div class="fw-700 text-success">৳{{ number_format($pkg->paid_amount) }}</div>
            </div>
            <div class="col-4 text-center">
              <div style="font-size:.7rem;color:#6b7280;">Remaining</div>
              <div class="fw-700 {{ $pkg->remaining > 0 ? 'text-danger' : 'text-success' }}">
                {{ $pkg->remaining > 0 ? '৳'.number_format($pkg->remaining) : '✓ Paid' }}
              </div>
            </div>
          </div>
          @if($pkg->payments->count())
          <div class="mt-2">
            <div class="progress" style="height:6px;border-radius:4px;">
              @php $pct = $pkg->net_amount > 0 ? min(100, ($pkg->paid_amount / $pkg->net_amount) * 100) : 0 @endphp
              <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
            </div>
            <small class="text-muted">{{ number_format($pct,1) }}% paid</small>
          </div>
          @endif
        </div>
        @empty
        <div class="text-center py-3 text-muted"><i class="bi bi-box me-1"></i>No package assigned yet.</div>
        @endforelse
      </div>
    </div>

    {{-- Appointments --}}
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar2 text-info me-2"></i>Appointments</span>
        <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-outline-info">Book</a>
      </div>
      <div class="card-body p-0">
        @forelse($patient->appointments->take(5) as $appt)
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
          <div style="min-width:42px;height:42px;background:#e3f2fd;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#1565c0;">
            <div>{{ $appt->appointment_date->format('d') }}</div>
            <div>{{ $appt->appointment_date->format('M') }}</div>
          </div>
          <div class="flex-grow-1">
            <div class="fw-500" style="font-size:.85rem;">{{ ucfirst($appt->type) }} — {{ $appt->consultant?->name ?? 'General' }}</div>
            <div class="text-muted" style="font-size:.72rem;">{{ $appt->appointment_time ? date('h:i A', strtotime($appt->appointment_time)) : '' }}</div>
          </div>
          @php $sc2=['scheduled'=>'secondary','confirmed'=>'primary','completed'=>'success','cancelled'=>'danger','no-show'=>'warning'] @endphp
          <span class="badge bg-{{ $sc2[$appt->status] }}">{{ ucfirst($appt->status) }}</span>
        </div>
        @empty
        <div class="text-center py-3 text-muted">No appointments yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
{{-- Credit Advance Modal --}}
<div class="modal fade" id="creditAdvModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header" style="background:#e0f7fa;">
        <h6 class="modal-title fw-700"><i class="bi bi-piggy-bank text-success me-2"></i>Add Advance Credit</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('patients.credit-advance', $patient) }}">
        @csrf
        <div class="modal-body">
          <div class="mb-1 text-muted" style="font-size:.8rem;">Current balance: <strong>৳{{ number_format($patient->advance_balance, 2) }}</strong></div>
          <label class="form-label fw-600">Amount (৳) *</label>
          <input type="number" name="amount" class="form-control" min="1" step="0.01" placeholder="Enter amount" required>
          <div class="form-text">This amount will be credited to the patient's advance balance and can be used to pay future bills.</div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success fw-600"><i class="bi bi-plus-circle me-1"></i>Credit</button>
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
