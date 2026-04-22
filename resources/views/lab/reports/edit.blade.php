@extends('layouts.app')
@section('title','Edit Report — '.$report->sample_code)
@section('page-title','Lab Reports')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-600 mb-0">Edit Report Data — {{ $report->sample_code }}</h5>
  <a href="{{ route('lab.reports.show',$report) }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>
<div class="card border-0 shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('lab.reports.update',$report) }}">
      @csrf @method('PUT')
      @include('lab.reports._semen_form', ['data' => $report->report_data ?? []])
      <hr>
      <div class="mb-3">
        <label class="form-label fw-600">Notes</label>
        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ $report->notes }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg me-1"></i>Save Changes
      </button>
    </form>
  </div>
</div>
@endsection
