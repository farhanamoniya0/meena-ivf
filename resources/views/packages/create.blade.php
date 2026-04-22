@extends('layouts.app')
@section('title','Create Package')
@section('page-title','Create IVF Package')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-box-fill text-primary me-2"></i>New IVF Package</div>
      <div class="card-body">
        <form method="POST" action="{{ route('packages.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Package Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Standard IVF Package">
          </div>
          <div class="mb-3">
            <label class="form-label">Total Cost (৳) <span class="text-danger">*</span></label>
            <input type="number" name="total_cost" class="form-control" value="{{ old('total_cost') }}" required min="0" step="0.01" placeholder="e.g. 150000">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Included Services</label>
            <textarea name="included_services" class="form-control" rows="5" placeholder="List services one per line:&#10;- Consultation&#10;- Egg retrieval&#10;- Embryo transfer&#10;- Lab work">{{ old('included_services') }}</textarea>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Duration (Days)</label>
              <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days') }}" min="1" placeholder="e.g. 30">
            </div>
            <div class="col-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Create Package</button>
            <a href="{{ route('packages.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
