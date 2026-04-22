@extends('layouts.app')
@section('title','Add Consultant')
@section('page-title','Add Consultant')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-badge-fill text-primary me-2"></i>Add New Consultant</div>
      <div class="card-body">
        <form method="POST" action="{{ route('consultants.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Specialty *</label><input type="text" name="specialty" class="form-control" value="{{ old('specialty') }}" required placeholder="e.g. Reproductive Endocrinology"></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
            <div class="col-md-6"><label class="form-label">Consultation Fee (৳) *</label><input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee',0) }}" required min="0" step="0.01" placeholder="e.g. 800"></div>
            <div class="col-12"><label class="form-label">Qualifications</label><input type="text" name="qualifications" class="form-control" value="{{ old('qualifications') }}" placeholder="e.g. MBBS, FCPS (Gynae)"></div>
            <div class="col-12"><label class="form-label">Bio / About</label><textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
            <div class="col-md-6"><label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Add Consultant</button>
            <a href="{{ route('consultants.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
