@extends('layouts.app')
@section('title','Edit Package')
@section('page-title','Edit IVF Package')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit: {{ $package->name }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('packages.update', $package) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Package Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$package->name) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Total Cost (৳) *</label>
            <input type="number" name="total_cost" class="form-control" value="{{ old('total_cost',$package->total_cost) }}" required min="0" step="0.01">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description',$package->description) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Included Services</label>
            <textarea name="included_services" class="form-control" rows="5">{{ old('included_services',$package->included_services) }}</textarea>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Duration (Days)</label>
              <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days',$package->duration_days) }}" min="1">
            </div>
            <div class="col-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active" {{ old('status',$package->status)=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status',$package->status)=='inactive'?'selected':'' }}>Inactive</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Save Changes</button>
            <a href="{{ route('packages.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
