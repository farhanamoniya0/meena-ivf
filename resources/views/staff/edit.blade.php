@extends('layouts.app')
@section('title','Edit Staff')
@section('page-title','Edit Staff Member')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit: {{ $staff->name }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('staff.update', $staff) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$staff->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Designation *</label><input type="text" name="designation" class="form-control" value="{{ old('designation',$staff->designation) }}" required></div>
            <div class="col-md-6"><label class="form-label">Department</label>
              <input type="text" name="department" class="form-control" value="{{ old('department',$staff->department) }}" list="deptList">
              <datalist id="deptList">@foreach(['OPD','IVF Lab','Pharmacy','Accounts','Admin','Nursing','Embryology'] as $d)<option value="{{ $d }}">@endforeach</datalist>
            </div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$staff->phone) }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$staff->email) }}"></div>
            <div class="col-md-6"><label class="form-label">NID</label><input type="text" name="nid" class="form-control" value="{{ old('nid',$staff->nid) }}"></div>
            <div class="col-md-4"><label class="form-label">Join Date</label><input type="date" name="join_date" class="form-control" value="{{ old('join_date',$staff->join_date?->format('Y-m-d')) }}"></div>
            <div class="col-md-4"><label class="form-label">Salary (৳)</label><input type="number" name="salary" class="form-control" value="{{ old('salary',$staff->salary) }}" min="0"></div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active" {{ old('status',$staff->status)=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status',$staff->status)=='inactive'?'selected':'' }}>Inactive</option>
                <option value="terminated" {{ old('status',$staff->status)=='terminated'?'selected':'' }}>Terminated</option>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$staff->address) }}</textarea></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$staff->notes) }}</textarea></div>
            @if($staff->photo)<div class="col-12"><img src="{{ asset('storage/'.$staff->photo) }}" style="height:60px;border-radius:50%;border:2px solid #7c3aed;"></div>@endif
            <div class="col-md-6"><label class="form-label">Update Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
            <a href="{{ route('staff.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
