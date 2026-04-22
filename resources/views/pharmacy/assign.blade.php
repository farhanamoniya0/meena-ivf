@extends('layouts.app')
@section('title','Assign Medicine')
@section('page-title','Assign Medicine to Patient')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    {{-- Requisition Form --}}
    <div class="card mb-3">
      <div class="card-header"><i class="bi bi-send-fill text-info me-2"></i>Submit Requisition</div>
      <div class="card-body">
        <form method="POST" action="{{ route('pharmacy.requisitions.store') }}" class="row g-3 align-items-end">
          @csrf
          <div class="col-md-5">
            <label class="form-label">Medicine *</label>
            <select name="medicine_id" class="form-select" required>
              <option value="">— Select —</option>
              @foreach($medicines as $med)
              <option value="{{ $med->id }}">{{ $med->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" placeholder="Optional">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-info text-white"><i class="bi bi-send me-1"></i>Submit Requisition</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Assign Medicine Card --}}
    <div class="card">
      <div class="card-header"><i class="bi bi-person-check-fill text-success me-2"></i>Assign Medicine to Patient</div>
      <div class="card-body">
        <form method="POST" action="{{ route('pharmacy.assign.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Patient *</label>
            <input type="text" id="patientSearch" class="form-control" placeholder="Type name, phone or code..." autocomplete="off">
            <input type="hidden" name="patient_id" id="patientId" required>
            <div id="searchResults" class="list-group mt-1 shadow" style="position:absolute;z-index:999;max-width:420px;display:none;"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Medicine *</label>
            <select name="medicine_id" id="medicineSelect" class="form-select" required>
              <option value="">— Select Medicine —</option>
              @foreach($medicines as $med)
              <option value="{{ $med->id }}">{{ $med->name }} (Stock: {{ $med->total_stock }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Batch *</label>
            <select name="medicine_batch_id" id="batchSelect" class="form-select" required>
              <option value="">— Select Medicine First —</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <input type="text" name="notes" class="form-control" placeholder="Optional">
          </div>
          <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-2"></i>Assign Medicine</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
// Patient search
const si=document.getElementById('patientSearch'),sr=document.getElementById('searchResults'),pi=document.getElementById('patientId');
let t;
si.addEventListener('input',function(){
  clearTimeout(t);const q=this.value.trim();if(q.length<2){sr.style.display='none';return;}
  t=setTimeout(()=>{
    fetch('{{ route("patients.search") }}?q='+encodeURIComponent(q)).then(r=>r.json()).then(data=>{
      sr.innerHTML='';if(!data.length){sr.style.display='none';return;}
      data.forEach(p=>{const a=document.createElement('a');a.href='#';a.className='list-group-item list-group-item-action';
        a.innerHTML=`<strong>${p.name}</strong> <span class="text-muted">${p.patient_code}</span> — ${p.phone}`;
        a.addEventListener('click',e=>{e.preventDefault();si.value=p.name+' ('+p.patient_code+')';pi.value=p.id;sr.style.display='none';});
        sr.appendChild(a);});sr.style.display='block';});},300);});
document.addEventListener('click',e=>{if(!sr.contains(e.target)&&e.target!==si)sr.style.display='none';});

// Load batches when medicine changes
document.getElementById('medicineSelect').addEventListener('change',function(){
  const id=this.value;const bs=document.getElementById('batchSelect');
  bs.innerHTML='<option>Loading...</option>';
  if(!id){bs.innerHTML='<option value="">— Select Medicine First —</option>';return;}
  fetch(`/pharmacy/medicines/${id}/batch-list`).then(r=>r.json()).then(data=>{
    bs.innerHTML='<option value="">— Select Batch —</option>';
    data.forEach(b=>{const o=document.createElement('option');o.value=b.id;
      o.textContent=`${b.batch_number} | Exp: ${b.expiry_date} | Stock: ${b.quantity}`;bs.appendChild(o);});
  });
});
</script>
@endpush
