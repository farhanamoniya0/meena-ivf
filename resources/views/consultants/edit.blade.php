@extends('layouts.app')
@section('title','Edit Consultant')
@section('page-title','Edit Consultant')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit: {{ $consultant->name }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('consultants.update', $consultant) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$consultant->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Specialty *</label><input type="text" name="specialty" class="form-control" value="{{ old('specialty',$consultant->specialty) }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$consultant->phone) }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$consultant->email) }}"></div>
            <div class="col-md-6"><label class="form-label">Consultation Fee (৳) *</label><input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee',$consultant->consultation_fee) }}" required min="0" step="0.01"></div>
            <div class="col-12"><label class="form-label">Qualifications</label><input type="text" name="qualifications" class="form-control" value="{{ old('qualifications',$consultant->qualifications) }}"></div>
            <div class="col-12"><label class="form-label">Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio',$consultant->bio) }}</textarea></div>
            @if($consultant->photo)
            <div class="col-12"><img src="{{ asset('storage/'.$consultant->photo) }}" style="height:60px;border-radius:50%;"></div>
            @endif
            <div class="col-md-6"><label class="form-label">Update Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
            <div class="col-md-6"><label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active" {{ old('status',$consultant->status)=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status',$consultant->status)=='inactive'?'selected':'' }}>Inactive</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
            <a href="{{ route('consultants.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
