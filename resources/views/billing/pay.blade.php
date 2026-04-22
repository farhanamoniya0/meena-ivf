@extends('layouts.app')
@section('title','Record Payment')
@section('page-title','Record Payment')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card mb-3" style="border-left:4px solid #17a589;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <h6 class="fw-700 mb-0">{{ $package->patient->name }}</h6>
            <span class="badge bg-primary">{{ $package->patient->patient_code }}</span>
          </div>
          <div class="text-end">
            <div style="font-size:.72rem;color:#6b7280;">Package</div>
            <div class="fw-600">{{ $package->ivfPackage->name }}</div>
          </div>
        </div>
        <div class="row g-2 mt-1">
          <div class="col-4 text-center p-2 rounded-3" style="background:#f8f9fa;">
            <div style="font-size:.7rem;color:#6b7280;">Net Payable</div>
            <div class="fw-700">৳{{ number_format($package->net_amount) }}</div>
          </div>
          <div class="col-4 text-center p-2 rounded-3" style="background:#e8f5e9;">
            <div style="font-size:.7rem;color:#2e7d32;">Paid</div>
            <div class="fw-700 text-success">৳{{ number_format($package->paid_amount) }}</div>
          </div>
          <div class="col-4 text-center p-2 rounded-3" style="background:{{ $package->remaining>0?'#ffebee':'#e8f5e9' }};">
            <div style="font-size:.7rem;color:{{ $package->remaining>0?'#c62828':'#2e7d32' }};">Remaining</div>
            <div class="fw-700 {{ $package->remaining>0?'text-danger':'text-success' }}">
              {{ $package->remaining>0?'৳'.number_format($package->remaining):'Fully Paid' }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><i class="bi bi-cash-stack me-2"></i>Record New Payment</div>
      <div class="card-body">
        <form method="POST" action="{{ route('billing.pay.store', $package) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Payment Amount (৳) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control form-control-lg fw-600"
              value="{{ old('amount', $package->remaining > 0 ? $package->remaining : '') }}"
              min="1" step="0.01" required placeholder="Enter amount">
            @if($package->remaining > 0)
            <small class="text-muted">Remaining: ৳{{ number_format($package->remaining) }}</small>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
            <div class="row g-2" id="paymentMethods">
              @foreach(['cash'=>'bi-cash','bank'=>'bi-bank2','card'=>'bi-credit-card','bkash'=>'bi-phone','nagad'=>'bi-phone-fill','rocket'=>'bi-rocket'] as $m=>$icon)
              <div class="col-4">
                <label class="method-card w-100 {{ old('payment_method')=='cash' && $m=='cash' ? 'selected':'' }}" id="mc-{{ $m }}">
                  <input type="radio" name="payment_method" value="{{ $m }}" class="d-none" {{ old('payment_method','cash')==$m?'checked':'' }} required>
                  <i class="bi {{ $icon }} d-block fs-4 mb-1"></i>
                  <div style="font-size:.8rem;font-weight:600;">{{ ucfirst($m) }}</div>
                </label>
              </div>
              @endforeach
            </div>
          </div>

          <div id="txnField" class="{{ old('payment_method','cash')=='cash'?'d-none':'' }}">
            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label">Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id') }}" placeholder="Txn/Reference number">
              </div>
              <div class="col-6" id="bankField" style="{{ old('payment_method')=='bank'?'':'display:none;' }}">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="Bank name">
              </div>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Remarks (optional)</label>
            <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}" placeholder="Any note">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1 py-2"><i class="bi bi-check-circle me-2"></i>Record Payment</button>
            <a href="{{ route('billing.patient', $package->patient) }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('[name="payment_method"]').forEach(r => {
  r.addEventListener('change', function() {
    document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
    this.closest('.method-card').classList.add('selected');
    const m = this.value;
    document.getElementById('txnField').classList.toggle('d-none', m==='cash');
    document.getElementById('bankField').style.display = m==='bank' ? '' : 'none';
  });
});
document.querySelectorAll('.method-card').forEach(c => {
  c.addEventListener('click', function() {
    this.querySelector('input[type=radio]').dispatchEvent(new Event('change'));
  });
});
// init
const checked = document.querySelector('[name="payment_method"]:checked');
if(checked && checked.value !== 'cash') {
  document.getElementById('txnField').classList.remove('d-none');
  if(checked.value === 'bank') document.getElementById('bankField').style.display = '';
}
document.querySelector('.method-card')?.classList.add('selected');
</script>
@endpush
