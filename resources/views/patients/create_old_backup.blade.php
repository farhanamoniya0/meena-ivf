@extends('layouts.app')
@section('title','Register Patient')
@section('page-title','New Patient Registration')
@section('content')
@include('partials.camera-modal')
<form method="POST" action="{{ route('patients.store') }}">
@csrf
<div class="row g-3">
  {{-- LEFT: Patient info --}}
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header"><i class="bi bi-person-fill text-primary me-2"></i>Patient (Wife) Information</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 text-center mb-2">
            <div id="noPhoto" class="camera-circle" onclick="openCamera()"><i class="bi bi-camera-fill fs-2 text-purple" style="color:#7c3aed;"></i></div>
            <img id="photoPreview" src="" class="camera-photo-preview" style="display:none;">
            <input type="hidden" name="photo_data" id="photoData">
            <div class="mt-2"><button type="button" class="btn btn-sm btn-outline-primary" onclick="openCamera()"><i class="bi bi-camera me-1"></i>Take Photo</button></div>
          </div>
          <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
          <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
          <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" value="{{ old('dob') }}"></div>
          <div class="col-md-2"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="{{ old('age') }}" min="1" max="120"></div>
          <div class="col-md-3"><label class="form-label">Gender *</label>
            <select name="gender" class="form-select" required>
              <option value="female" {{ old('gender','female')=='female'?'selected':'' }}>Female</option>
              <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
            </select>
          </div>
          <div class="col-md-3"><label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select">
              <option value="">—</option>
              @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
              <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6"><label class="form-label">NID Number</label><input type="text" name="nid_number" class="form-control" value="{{ old('nid_number') }}"></div>
          <div class="col-md-6"><label class="form-label">Religion</label>
            <select name="religion" class="form-select">
              <option value="">—</option>
              @foreach(['Islam','Hinduism','Christianity','Buddhism','Other'] as $r)
              <option value="{{ $r }}" {{ old('religion')==$r?'selected':'' }}>{{ $r }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}"></div>
          <div class="col-md-6"><label class="form-label">Referred By</label><input type="text" name="referred_by" class="form-control" value="{{ old('referred_by') }}"></div>
          <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
          <div class="col-md-6"><label class="form-label">Consultant</label>
            <select name="consultant_id" id="consultantSelect" class="form-select">
              <option value="">— Select Consultant —</option>
              @foreach($consultants as $c)
              <option value="{{ $c->id }}" data-fee="{{ $c->consultation_fee }}" {{ old('consultant_id')==$c->id?'selected':'' }}>
                {{ $c->name }} — ৳{{ number_format($c->consultation_fee) }}</option>
              @endforeach
            </select>
            <small id="consultFeeNote" class="text-muted" style="font-size:.75rem;display:none;">Consultation fee will be added to bill</small>
          </div>
          <div class="col-md-6"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control" value="{{ old('notes') }}"></div>
        </div>
      </div>
    </div>

    {{-- Husband --}}
    <div class="card mb-3">
      <div class="card-header"><i class="bi bi-person-fill text-success me-2"></i>Husband Information <small class="text-muted fw-400">(Optional)</small></div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Husband Name</label><input type="text" name="husband_name" class="form-control" value="{{ old('husband_name') }}"></div>
          <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="husband_phone" class="form-control" value="{{ old('husband_phone') }}"></div>
          <div class="col-md-3"><label class="form-label">Age</label><input type="number" name="husband_age" class="form-control" value="{{ old('husband_age') }}"></div>
          <div class="col-md-3"><label class="form-label">Blood Group</label>
            <select name="husband_blood_group" class="form-select">
              <option value="">—</option>
              @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
              <option value="{{ $bg }}" {{ old('husband_blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3"><label class="form-label">NID</label><input type="text" name="husband_nid" class="form-control" value="{{ old('husband_nid') }}"></div>
          <div class="col-md-3"><label class="form-label">Marriage Date</label><input type="date" name="marriage_date" class="form-control" value="{{ old('marriage_date') }}"></div>
          <div class="col-12"><label class="form-label">Medical History</label><textarea name="medical_history" class="form-control" rows="2">{{ old('medical_history') }}</textarea></div>
        </div>
      </div>
    </div>
  </div>

  {{-- RIGHT: Bill info --}}
  <div class="col-md-4">
    {{-- Reg fee notice --}}
    <div class="card mb-3" style="border:2px solid #7c3aed;">
      <div class="card-header" style="background:#f3e8ff;"><i class="bi bi-receipt text-primary me-2"></i>Registration Bill (Auto)</div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
          <span style="font-size:.85rem;"><i class="bi bi-check-circle-fill text-success me-2"></i>Registration Fee</span>
          <span class="fw-700 text-primary">৳200</span>
        </div>
        <div id="consultFeeRow" class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded" style="display:none!important;">
          <span style="font-size:.85rem;"><i class="bi bi-person-badge me-2 text-info"></i>Consultation Fee</span>
          <span class="fw-700 text-info" id="consultFeeAmt">৳0</span>
        </div>
        <hr>
        @if($services->count())
        <label class="form-label fw-600 mb-1">Add Services to Bill</label>
        <div class="d-flex gap-2 mb-2">
          <select id="svcDropdown" class="form-select form-select-sm">
            <option value="">— Select Service —</option>
            @foreach($services->groupBy('category') as $cat => $svcs)
              @if($cat)<optgroup label="{{ $cat }}">@endif
              @foreach($svcs as $svc)
              <option value="{{ $svc->id }}"
                data-charge="{{ $svc->charge }}"
                data-name="{{ $svc->name }}"
                data-code="{{ $svc->service_code }}">
                [{{ $svc->service_code }}] {{ $svc->name }} — ৳{{ number_format($svc->charge) }}
              </option>
              @endforeach
              @if($cat)</optgroup>@endif
            @endforeach
          </select>
          <button type="button" class="btn btn-sm btn-primary px-3" onclick="addService()"><i class="bi bi-plus-lg"></i></button>
        </div>
        <div id="selectedServices" class="d-flex flex-column gap-1 mb-1"></div>
        @endif
        <hr>
        <div class="d-flex justify-content-between fw-700 fs-6">
          <span>Estimated Total</span>
          <span id="estTotal" class="text-primary">৳200</span>
        </div>
        <small class="text-muted" style="font-size:.72rem;">You can collect payment on the bill page.</small>
      </div>
    </div>

    <div class="card">
      <div class="card-body text-center py-3">
        <p class="text-muted mb-3" style="font-size:.78rem;">Code: <strong>MIV-YYYY-XXXX</strong> (auto-generated)</p>
        <button type="submit" class="btn btn-primary w-100 mb-2"><i class="bi bi-check-lg me-2"></i>Register & Create Bill</button>
        <a href="{{ route('patients.index') }}" class="btn btn-light w-100">Cancel</a>
      </div>
    </div>
  </div>
</div>
</form>
@endsection
@push('scripts')
<script>
let consultFeeBase = 200;
const addedServices = {};

function updateTotal(){
  let t = consultFeeBase;
  Object.values(addedServices).forEach(s => t += s.charge);
  document.getElementById('estTotal').textContent = '৳' + t.toLocaleString('en-BD');
}

function addService(){
  const dd = document.getElementById('svcDropdown');
  const opt = dd.options[dd.selectedIndex];
  if(!opt.value) return;
  const id = opt.value;
  if(addedServices[id]) { dd.value=''; return; }

  const charge = parseFloat(opt.dataset.charge)||0;
  const name   = opt.dataset.name;
  const code   = opt.dataset.code;
  addedServices[id] = {charge, name, code};

  const wrap = document.getElementById('selectedServices');
  const row  = document.createElement('div');
  row.className = 'd-flex align-items-center justify-content-between p-2 border rounded-2 bg-white';
  row.id = 'svc-row-'+id;
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
  dd.value = '';
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
  const row  = document.getElementById('consultFeeRow');
  const note = document.getElementById('consultFeeNote');
  const amt  = document.getElementById('consultFeeAmt');
  if(fee > 0){
    row.style.removeProperty('display');
    note.style.display='block';
    amt.textContent='৳'+fee.toLocaleString('en-BD');
    consultFeeBase = 200 + fee;
  } else {
    row.style.display='none';
    note.style.display='none';
    consultFeeBase = 200;
  }
  updateTotal();
});
</script>
@endpush
