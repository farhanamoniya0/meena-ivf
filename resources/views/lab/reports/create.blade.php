@extends('layouts.app')
@section('title','New Sample Entry')
@section('page-title','Lab Reports')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-600 mb-0">Register New Sample</h5>
  <a href="{{ route('lab.reports.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>

<div class="card border-0 shadow-sm" style="max-width:600px;">
  <div class="card-body p-4">
    @if($errors->any())
      <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('lab.reports.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-600">Patient</label>
        <select name="patient_id" class="form-select" required>
          <option value="">— Select Patient —</option>
          @foreach($patients as $p)
          <option value="{{ $p->id }}" {{ (old('patient_id', $patient?->id) == $p->id) ? 'selected' : '' }}>
            {{ $p->patient_code }} — {{ $p->name }} ({{ $p->phone }})
          </option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600">Test Type</label>
        <select name="test_type" class="form-select" required>
          <option value="semen_analysis" {{ old('test_type')=='semen_analysis'?'selected':'' }}>Semen Analysis</option>
          <option value="sperm_dna_fragmentation" {{ old('test_type')=='sperm_dna_fragmentation'?'selected':'' }}>Sperm DNA Fragmentation</option>
          <option value="sperm_morphology" {{ old('test_type')=='sperm_morphology'?'selected':'' }}>Sperm Morphology (Strict Criteria)</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-600">Notes <small class="text-muted">(optional)</small></label>
        <textarea name="notes" class="form-control" rows="2" placeholder="Any remarks about the sample collection…">{{ old('notes') }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg me-1"></i>Register Sample
      </button>
    </form>
  </div>
</div>
@endsection
