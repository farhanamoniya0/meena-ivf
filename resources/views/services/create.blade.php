@extends('layouts.app')
@section('title','Add Service')
@section('page-title','Add Service')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Add New Service</div>
      <div class="card-body">
        <form method="POST" action="{{ route('services.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Service Code <small class="text-muted">(auto-generated if blank)</small></label>
            <input type="text" name="service_code" class="form-control" value="{{ old('service_code') }}" placeholder="e.g. SVC-019" style="text-transform:uppercase;">
          </div>
          <div class="mb-3">
            <label class="form-label">Service Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Semen Analysis">
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category') }}" list="catList" placeholder="e.g. Lab, OPD, Procedure">
            <datalist id="catList">
              @foreach(['Lab','OPD','IVF','Procedure','Imaging','Consultation','Pharmacy','Other'] as $cat)
              <option value="{{ $cat }}">
              @endforeach
            </datalist>
          </div>
          <div class="mb-3">
            <label class="form-label">Charge (৳) *</label>
            <input type="number" name="charge" class="form-control" value="{{ old('charge') }}" required min="0" step="0.01" placeholder="0.00">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional description...">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Active</option>
              <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Add Service</button>
            <a href="{{ route('services.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
