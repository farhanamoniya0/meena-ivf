@extends('layouts.app')
@section('title','Quick Registration')
@section('page-title','Quick Registration')
@section('content')
@include('partials.camera-modal')
<div class="row justify-content-center">
  <div class="col-md-10">
    <form method="POST" action="{{ route('patients.quick.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Registration</div>
          <div class="card-body">
            {{-- Camera --}}
            <div class="text-center mb-3">
              <div id="noPhoto" class="camera-circle" onclick="openCamera()"><i class="bi bi-camera-fill fs-2" style="color:#7c3aed;"></i></div>
              <img id="photoPreview" src="" class="camera-photo-preview" style="display:none;">
              <input type="hidden" name="photo_data" id="photoData">
              <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="openCamera()"><i class="bi bi-camera me-1"></i>Take Photo</button>
            </div>
            <div class="mb-3"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="Patient's full name"></div>
            <div class="mb-3"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX"></div>
            <div class="row g-3 mb-3">
              <div class="col-6"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="{{ old('age') }}" min="1"></div>
              <div class="col-6"><label class="form-label">Gender *</label>
                <select name="gender" class="form-select" required>
                  <option value="female" {{ old('gender','female')=='female'?'selected':'' }}>Female</option>
                  <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                </select>
              </div>
            </div>
            <div class="mb-3"><label class="form-label">Consultant</label>
              <select name="consultant_id" id="consultantSelect" class="form-select">
                <option value="">— None —</option>
                @foreach($consultants as $c)
                <option value="{{ $c->id }}" data-fee="{{ $c->consultation_fee }}" {{ old('consultant_id')==$c->id?'selected':'' }}>
                  {{ $c->name }} — ৳{{ number_format($c->consultation_fee) }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-600"><i class="bi bi-check-lg me-2"></i>Register & Create Bill</button>
            <div class="text-center mt-2"><a href="{{ route('patients.create') }}" class="text-muted" style="font-size:.8rem;">Need full registration? →</a></div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card" style="border:2px solid #7c3aed;">
          <div class="card-header" style="background:#f3e8ff;"><i class="bi bi-receipt text-primary me-2"></i>Auto Bill Preview</div>
          <div class="card-body">
            <div class="d-flex justify-content-between p-2 mb-1 bg-light rounded"><span style="font-size:.85rem;"><i class="bi bi-check-circle-fill text-success me-1"></i>Registration Fee</span><span class="fw-700 text-primary">৳200</span></div>
            <div id="consultFeeRow" style="display:none;" class="d-flex justify-content-between p-2 mb-1 bg-light rounded"><span style="font-size:.85rem;"><i class="bi bi-person-badge me-1 text-info"></i>Consultation Fee</span><span class="fw-700 text-info" id="consultFeeAmt">৳0</span></div>
            <hr>
            @if($services->count())
            <label class="form-label fw-600 mb-1">Add Services</label>
            <div class="d-flex gap-2 mb-2">
              <select id="svcDropdown" class="form-select form-select-sm">
                <option value="">— Select Service —</option>
                @foreach($services->groupBy('category') as $cat => $svcs)
                  @if($cat)<optgroup label="{{ $cat }}">@endif
                  @foreach($svcs as $svc)
                  <option value="{{ $svc->id }}" data-charge="{{ $svc->charge }}" data-name="{{ $svc->name }}" data-code="{{ $svc->service_code }}">
                    [{{ $svc->service_code }}] {{ $svc->name }} — ৳{{ number_format($svc->charge) }}
                  </option>
                  @endforeach
                  @if($cat)</optgroup>@endif
                @endforeach
              </select>
              <button type="button" class="btn btn-sm btn-primary px-3" onclick="addService()"><i class="bi bi-plus-lg"></i></button>
            </div>
            <div id="selectedServices" class="d-flex flex-column gap-1 mb-1"></div>
            <hr>
            @endif
            <div class="d-flex justify-content-between fw-700 fs-6 mt-2">
              <span>Estimated Total</span>
              <span id="estTotal" class="text-primary">৳200</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
let base = 200;
const addedServices = {};

function updateTotal(){
  let t = base;
  Object.values(addedServices).forEach(s => t += s.charge);
  document.getElementById('estTotal').textContent = '৳' + t.toLocaleString('en-BD');
}

function addService(){
  const dd = document.getElementById('svcDropdown');
  const opt = dd.options[dd.selectedIndex];
  if(!opt.value) return;
  const id = opt.value;
  if(addedServices[id]){ dd.value=''; return; }

  const charge = parseFloat(opt.dataset.charge)||0;
  const name   = opt.dataset.name;
  const code   = opt.dataset.code;
  addedServices[id] = {charge, name, code};

  const wrap = document.getElementById('selectedServices');
  const row  = document.createElement('div');
  row.id = 'svc-row-'+id;
  row.className = 'd-flex align-items-center justify-content-between p-2 border rounded-2 bg-white';
  row.style.fontSize='.82rem';
  row.innerHTML = `
    <div>
      <span class="badge bg-secondary me-1" style="font-size:.7rem;">${code}</span>
      <span>${name}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="fw-700 text-primary">৳${charge.toLocaleString('en-BD')}</span>
      <input type="hidden" name="service_ids[]" value="${id}">
      <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="removeService('${id}')">
        <i class="bi bi-x-lg" style="font-size:.65rem;"></i>
      </button>
    </div>`;
  wrap.appendChild(row);
  dd.value='';
  updateTotal();
}

function removeService(id){
  delete addedServices[id];
  const el = document.getElementById('svc-row-'+id);
  if(el) el.remove();
  updateTotal();
}

document.getElementById('consultantSelect').addEventListener('change', function(){
  const fee = parseFloat(this.options[this.selectedIndex]?.dataset.fee)||0;
  const row = document.getElementById('consultFeeRow');
  if(fee > 0){
    row.style.display='flex';
    document.getElementById('consultFeeAmt').textContent='৳'+fee.toLocaleString('en-BD');
    base = 200 + fee;
  } else {
    row.style.display='none';
    base = 200;
  }
  updateTotal();
});
</script>
@endpush
