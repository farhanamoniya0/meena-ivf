@extends('layouts.app')
@section('title','Edit Patient')
@section('page-title','Edit Patient')
@section('content')
<form method="POST" action="{{ route('patients.update', $patient) }}" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-header"><i class="bi bi-person-fill text-primary me-2"></i>Patient Information — {{ $patient->patient_code }}</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$patient->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$patient->phone) }}" required></div>
            <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" value="{{ old('dob',$patient->dob?->format('Y-m-d')) }}"></div>
            <div class="col-md-2"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="{{ old('age',$patient->age) }}"></div>
            <div class="col-md-3"><label class="form-label">Gender *</label>
              <select name="gender" class="form-select" required>
                <option value="female" {{ old('gender',$patient->gender)=='female'?'selected':'' }}>Female</option>
                <option value="male"   {{ old('gender',$patient->gender)=='male'?'selected':'' }}>Male</option>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Blood Group</label>
              <select name="blood_group" class="form-select">
                <option value="">—</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_group',$patient->blood_group)==$bg?'selected':'' }}>{{ $bg }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6"><label class="form-label">NID Number</label><input type="text" name="nid_number" class="form-control" value="{{ old('nid_number',$patient->nid_number) }}"></div>
            <div class="col-md-6"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" value="{{ old('occupation',$patient->occupation) }}"></div>
            <div class="col-md-6"><label class="form-label">Referred By</label><input type="text" name="referred_by" class="form-control" value="{{ old('referred_by',$patient->referred_by) }}"></div>
            <div class="col-md-6"><label class="form-label">Consultant</label>
              <select name="consultant_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($consultants as $c)
                <option value="{{ $c->id }}" {{ old('consultant_id',$patient->consultant_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6"><label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active"    {{ old('status',$patient->status)=='active'?'selected':'' }}>Active</option>
                <option value="inactive"  {{ old('status',$patient->status)=='inactive'?'selected':'' }}>Inactive</option>
                <option value="completed" {{ old('status',$patient->status)=='completed'?'selected':'' }}>Completed</option>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$patient->address) }}</textarea></div>
          </div>
        </div>
      </div>

      @if($patient->couple)
      <div class="card">
        <div class="card-header"><i class="bi bi-people text-success me-2"></i>Husband Information</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Husband Name</label><input type="text" name="husband_name" class="form-control" value="{{ old('husband_name',$patient->couple->husband_name) }}"></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="husband_phone" class="form-control" value="{{ old('husband_phone',$patient->couple->husband_phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="husband_dob" class="form-control" value="{{ old('husband_dob',$patient->couple->husband_dob?->format('Y-m-d')) }}"></div>
            <div class="col-md-2"><label class="form-label">Age</label><input type="number" name="husband_age" class="form-control" value="{{ old('husband_age',$patient->couple->husband_age) }}"></div>
            <div class="col-md-3"><label class="form-label">Blood Group</label>
              <select name="husband_blood_group" class="form-select">
                <option value="">—</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                <option value="{{ $bg }}" {{ old('husband_blood_group',$patient->couple->husband_blood_group)==$bg?'selected':'' }}>{{ $bg }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">NID</label><input type="text" name="husband_nid" class="form-control" value="{{ old('husband_nid',$patient->couple->husband_nid) }}"></div>
            <div class="col-md-4"><label class="form-label">Marriage Date</label><input type="date" name="marriage_date" class="form-control" value="{{ old('marriage_date',$patient->couple->marriage_date?->format('Y-m-d')) }}"></div>
            <div class="col-12"><label class="form-label">Medical History</label><textarea name="medical_history" class="form-control" rows="2">{{ old('medical_history',$patient->couple->medical_history) }}</textarea></div>
          </div>
        </div>
      </div>
      @else
      <input type="hidden" name="husband_name" value="">
      @endif
    </div>

    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-header"><i class="bi bi-camera me-2"></i>Update Photos</div>
        <div class="card-body">
          @if($patient->photo)
          <div class="mb-2 text-center"><img src="{{ asset('storage/'.$patient->photo) }}" style="height:80px;border-radius:8px;object-fit:cover;"></div>
          @endif
          <div class="mb-3"><label class="form-label">Patient Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
          <div><label class="form-label">NID / Birth Certificate</label><input type="file" name="nid_photo" class="form-control" accept="image/*,.pdf"></div>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Save Changes</button>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-light">Cancel</a>
      </div>
    </div>
  </div>
</form>
@endsection
