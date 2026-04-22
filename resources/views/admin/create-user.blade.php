@extends('layouts.app')
@section('title','Add User')
@section('page-title','Add System User')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-plus-fill text-primary me-2"></i>Create New User</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf
          <div class="mb-3"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
          <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
          <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
          <div class="mb-3">
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
              @foreach(['admin','doctor','consultant','billing','accountant','pharmacy','lab','reception'] as $r)
              <option value="{{ $r }}" {{ old('role')==$r?'selected':'' }}>{{ ucfirst($r) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required minlength="6"></div>
          <div class="mb-3"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="is_active" id="isActive" value="1" checked>
            <label class="form-check-label" for="isActive">Active (can login)</label>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Create User</button>
            <a href="{{ route('admin.users') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
