@extends('layouts.app')
@section('title','Assign Package')
@section('page-title','Assign IVF Package to Patient')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-box-fill text-primary me-2"></i>Assign IVF Package</div>
      <div class="card-body">
        <form method="POST" action="{{ route('packages.assign.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Search Patient <span class="text-danger">*</span></label>
            <input type="text" id="patientSearch" class="form-control" placeholder="Type name, phone, or patient code..." autocomplete="off"
              value="{{ $patient ? $patient->name.' ('.$patient->patient_code.')' : '' }}">
            <input type="hidden" name="patient_id" id="patientId" value="{{ $patient?->id }}" required>
            <div id="searchResults" class="list-group mt-1 shadow" style="position:absolute;z-index:999;max-width:400px;display:none;"></div>
            @if($patient)
            <div class="mt-2 p-2 bg-light rounded">
              <strong>{{ $patient->name }}</strong> — {{ $patient->patient_code }} — {{ $patient->phone }}
            </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">IVF Package <span class="text-danger">*</span></label>
            <select name="ivf_package_id" id="packageSelect" class="form-select" required>
              <option value="">— Select Package —</option>
              @foreach($packages as $pkg)
              <option value="{{ $pkg->id }}" data-cost="{{ $pkg->total_cost }}" {{ request('package_id')==$pkg->id?'selected':'' }}>
                {{ $pkg->name }} — ৳{{ number_format($pkg->total_cost) }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Total Amount (৳) <span class="text-danger">*</span></label>
              <input type="number" name="total_amount" id="totalAmount" class="form-control fw-600" value="{{ old('total_amount') }}" min="0" step="0.01" required>
              <small class="text-muted">Auto-filled from package, editable</small>
            </div>
            <div class="col-6">
              <label class="form-label">Discount (৳)</label>
              <input type="number" name="discount" class="form-control" value="{{ old('discount',0) }}" min="0" step="0.01" placeholder="0">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', today()->format('Y-m-d')) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Any special notes...">{{ old('notes') }}</textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Assign Package</button>
            <a href="{{ route('packages.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
const searchInput = document.getElementById('patientSearch');
const searchResults = document.getElementById('searchResults');
const patientId = document.getElementById('patientId');
let timer;
searchInput.addEventListener('input', function() {
  clearTimeout(timer);
  const q = this.value.trim();
  if(q.length < 2) { searchResults.style.display='none'; return; }
  timer = setTimeout(() => {
    fetch('{{ route("patients.search") }}?q=' + encodeURIComponent(q))
      .then(r => r.json())
      .then(data => {
        searchResults.innerHTML = '';
        if(!data.length) { searchResults.style.display='none'; return; }
        data.forEach(p => {
          const a = document.createElement('a');
          a.href = '#';
          a.className = 'list-group-item list-group-item-action';
          a.innerHTML = `<strong>${p.name}</strong> <span class="text-muted">${p.patient_code}</span> — ${p.phone}`;
          a.addEventListener('click', e => {
            e.preventDefault();
            searchInput.value = p.name + ' (' + p.patient_code + ')';
            patientId.value = p.id;
            searchResults.style.display = 'none';
          });
          searchResults.appendChild(a);
        });
        searchResults.style.display = 'block';
      });
  }, 300);
});
document.addEventListener('click', e => { if(!searchResults.contains(e.target) && e.target !== searchInput) searchResults.style.display='none'; });

document.getElementById('packageSelect').addEventListener('change', function() {
  const opt = this.options[this.selectedIndex];
  if(opt.dataset.cost) document.getElementById('totalAmount').value = opt.dataset.cost;
});
</script>
@endpush
