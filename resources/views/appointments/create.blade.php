@extends('layouts.app')
@section('title','Book Appointment')
@section('page-title','Book Appointment')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-calendar-plus text-primary me-2"></i>Book New Appointment</div>
      <div class="card-body">
        <form method="POST" action="{{ route('appointments.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Patient <span class="text-danger">*</span></label>
            <input type="text" id="patientSearch" class="form-control" placeholder="Type name, phone, or patient code..." autocomplete="off">
            <input type="hidden" name="patient_id" id="patientId" value="{{ request('patient_id') }}" required>
            <div id="searchResults" class="list-group mt-1 shadow" style="position:absolute;z-index:999;max-width:420px;display:none;"></div>
            @if(request('patient_id'))
            @php $pp = App\Models\Patient::find(request('patient_id')) @endphp
            @if($pp)<div class="mt-1 small text-success"><i class="bi bi-check-circle me-1"></i>{{ $pp->name }} ({{ $pp->patient_code }})</div>@endif
            @endif
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Date <span class="text-danger">*</span></label>
              <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date',today()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-6">
              <label class="form-label">Time</label>
              <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time') }}">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Type <span class="text-danger">*</span></label>
              <select name="type" class="form-select" required>
                @php
              $types = [
                'new_patient'  => 'New Patient',
                'followup'     => 'Follow Up',
                'scan'         => 'Scan',
                'iui'          => 'IUI',
                'stimulation'  => 'Stimulation',
                'ivf'          => 'IVF',
                'opd'          => 'OPD',
                'consultation' => 'Consultation',
                'procedure'    => 'Procedure',
              ];
              @endphp
              @foreach($types as $val => $lbl)
              <option value="{{ $val }}" {{ old('type','new_patient')==$val?'selected':'' }}>{{ $lbl }}</option>
              @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Consultant</label>
              <select name="consultant_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($consultants as $c)
                <option value="{{ $c->id }}" {{ old('consultant_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
              <option value="">— Select —</option>
              @foreach($departments as $d)
              <option value="{{ $d->id }}" {{ old('department_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Any notes...">{{ old('notes') }}</textarea>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Book Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
const si = document.getElementById('patientSearch');
const sr = document.getElementById('searchResults');
const pi = document.getElementById('patientId');
let t;
si.addEventListener('input', function() {
  clearTimeout(t);
  const q = this.value.trim();
  if(q.length < 2) { sr.style.display='none'; return; }
  t = setTimeout(() => {
    fetch('{{ route("patients.search") }}?q=' + encodeURIComponent(q))
      .then(r => r.json())
      .then(data => {
        sr.innerHTML = '';
        if(!data.length) { sr.style.display='none'; return; }
        data.forEach(p => {
          const a = document.createElement('a');
          a.href='#'; a.className='list-group-item list-group-item-action';
          a.innerHTML=`<strong>${p.name}</strong> <span class="text-muted">${p.patient_code}</span> — ${p.phone}`;
          a.addEventListener('click', e => { e.preventDefault(); si.value=p.name+' ('+p.patient_code+')'; pi.value=p.id; sr.style.display='none'; });
          sr.appendChild(a);
        });
        sr.style.display='block';
      });
  }, 300);
});
document.addEventListener('click', e => { if(!sr.contains(e.target) && e.target!==si) sr.style.display='none'; });
</script>
@endpush
