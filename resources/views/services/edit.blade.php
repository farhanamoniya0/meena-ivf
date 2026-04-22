@extends('layouts.app')
@section('title','Edit Service')
@section('page-title','Edit Service')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit: {{ $service->name }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('services.update', $service) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Service Code</label>
            <input type="text" name="service_code" class="form-control" value="{{ old('service_code',$service->service_code) }}" style="text-transform:uppercase;">
          </div>
          <div class="mb-3"><label class="form-label">Service Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$service->name) }}" required></div>
          <div class="mb-3"><label class="form-label">Category</label><input type="text" name="category" class="form-control" value="{{ old('category',$service->category) }}" list="catList">
            <datalist id="catList">@foreach(['Lab','OPD','IVF','Procedure','Imaging','Consultation','Pharmacy','Other'] as $cat)<option value="{{ $cat }}">@endforeach</datalist>
          </div>
          <div class="mb-3"><label class="form-label">Charge (৳) *</label><input type="number" name="charge" class="form-control" value="{{ old('charge',$service->charge) }}" required min="0" step="0.01"></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description',$service->description) }}</textarea></div>
          <div class="mb-3"><label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" {{ old('status',$service->status)=='active'?'selected':'' }}>Active</option>
              <option value="inactive" {{ old('status',$service->status)=='inactive'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
            <a href="{{ route('services.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
