@extends('layouts.app')
@section('title','Add Staff')
@section('page-title','Add Staff Member')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-plus-fill text-primary me-2"></i>Add New Staff Member</div>
      <div class="card-body">
        <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Designation *</label><input type="text" name="designation" class="form-control" value="{{ old('designation') }}" required placeholder="e.g. Senior Nurse, Lab Technician"></div>
            <div class="col-md-6"><label class="form-label">Department</label>
              <input type="text" name="department" class="form-control" value="{{ old('department') }}" list="deptList" placeholder="e.g. IVF Lab">
              <datalist id="deptList">@foreach(['OPD','IVF Lab','Pharmacy','Accounts','Admin','Nursing','Embryology'] as $d)<option value="{{ $d }}">@endforeach</datalist>
            </div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
            <div class="col-md-6"><label class="form-label">NID Number</label><input type="text" name="nid" class="form-control" value="{{ old('nid') }}"></div>
            <div class="col-md-4"><label class="form-label">Join Date</label><input type="date" name="join_date" class="form-control" value="{{ old('join_date') }}"></div>
            <div class="col-md-4"><label class="form-label">Monthly Salary (৳)</label><input type="number" name="salary" class="form-control" value="{{ old('salary') }}" min="0"></div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="terminated">Terminated</option>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Add Staff Member</button>
            <a href="{{ route('staff.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
