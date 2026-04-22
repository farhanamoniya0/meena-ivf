@extends('layouts.app')
@section('title','Billing: '.$patient->name)
@section('page-title','Patient Billing')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-700 mb-0">{{ $patient->name }}</h5>
    <span class="badge bg-primary">{{ $patient->patient_code }}</span>
    <span class="text-muted ms-2" style="font-size:.82rem;">{{ $patient->phone }}</span>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('patients.show',$patient) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-person me-1"></i>Profile</a>
    <a href="{{ route('packages.assign') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-box me-1"></i>Assign Package</a>
  </div>
</div>

@forelse($patient->packages as $pkg)
<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <i class="bi bi-box-fill text-primary me-2"></i>
      <strong>{{ $pkg->ivfPackage->name }}</strong>
      <span class="badge {{ $pkg->status=='active'?'bg-success':'bg-secondary' }} ms-2">{{ ucfirst($pkg->status) }}</span>
    </div>
    <div class="d-flex gap-2">
      @if($pkg->status=='active')
      <a href="{{ route('billing.pay',$pkg) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Record Payment</a>
      @endif
    </div>
  </div>
  <div class="card-body">
    {{-- Balance Summary --}}
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="p-3 rounded-3 text-center" style="background:#f8f9fa;">
          <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;">Package Total</div>
          <div class="fw-700 fs-5">৳{{ number_format($pkg->total_amount) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 rounded-3 text-center" style="background:#fff3e0;">
          <div style="font-size:.72rem;color:#e65100;text-transform:uppercase;letter-spacing:.5px;">Discount</div>
          <div class="fw-700 fs-5 text-warning">৳{{ number_format($pkg->discount) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 rounded-3 text-center" style="background:#e8f5e9;">
          <div style="font-size:.72rem;color:#2e7d32;text-transform:uppercase;letter-spacing:.5px;">Paid</div>
          <div class="fw-700 fs-5 text-success">৳{{ number_format($pkg->paid_amount) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 rounded-3 text-center" style="background:{{ $pkg->remaining>0?'#ffebee':'#e8f5e9' }};">
          <div style="font-size:.72rem;color:{{ $pkg->remaining>0?'#c62828':'#2e7d32' }};text-transform:uppercase;letter-spacing:.5px;">Remaining</div>
          <div class="fw-700 fs-5 {{ $pkg->remaining>0?'text-danger':'text-success' }}">
            {{ $pkg->remaining>0 ? '৳'.number_format($pkg->remaining) : '✓ Fully Paid' }}
          </div>
        </div>
      </div>
    </div>
    @php $pct = $pkg->net_amount>0 ? min(100,($pkg->paid_amount/$pkg->net_amount)*100) : 0; @endphp
    <div class="progress mb-4" style="height:8px;border-radius:10px;">
      <div class="progress-bar bg-success" style="width:{{ $pct }}%;border-radius:10px;"></div>
    </div>

    {{-- Payment History --}}
    @if($pkg->payments->count())
    <h6 class="fw-600 mb-2">Payment History</h6>
    <div class="table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Receipt</th><th>Date</th><th>Amount</th><th>Method</th><th>Ref/Txn</th><th>Status</th><th></th></tr></thead>
        <tbody>
          @foreach($pkg->payments->sortByDesc('created_at') as $pay)
          <tr>
            <td><span class="text-monospace" style="font-size:.78rem;">{{ $pay->receipt_no }}</span></td>
            <td style="font-size:.83rem;">{{ $pay->created_at->format('d M Y') }}</td>
            <td class="fw-600">৳{{ number_format($pay->amount) }}</td>
            <td>
              @php $mc=['cash'=>'success','bank'=>'primary','card'=>'info','bkash'=>'danger','nagad'=>'warning','rocket'=>'secondary'] @endphp
              <span class="badge bg-{{ $mc[$pay->payment_method] }}">{{ ucfirst($pay->payment_method) }}</span>
            </td>
            <td style="font-size:.78rem;">{{ $pay->transaction_id ?? $pay->bank_name ?? '—' }}</td>
            <td>
              @php $ps=['approved'=>'success','pending'=>'warning','rejected'=>'danger'] @endphp
              <span class="badge bg-{{ $ps[$pay->status] }}">{{ ucfirst($pay->status) }}</span>
            </td>
            <td><a href="{{ route('billing.receipt',$pay) }}" class="btn btn-xs btn-outline-secondary py-0 px-1" style="font-size:.72rem;" target="_blank"><i class="bi bi-printer me-1"></i>Receipt</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="text-center text-muted py-2"><i class="bi bi-receipt me-1"></i>No payments yet.</div>
    @endif
  </div>
</div>
@empty
<div class="card">
  <div class="card-body text-center py-5">
    <i class="bi bi-box fs-1 text-muted d-block mb-3"></i>
    <h5 class="fw-600">No Package Assigned</h5>
    <p class="text-muted">Assign an IVF package to this patient to start billing.</p>
    <a href="{{ route('packages.assign') }}?patient_id={{ $patient->id }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Assign IVF Package</a>
  </div>
</div>
@endforelse
@endsection
