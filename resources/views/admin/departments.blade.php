@extends('layouts.app')
@section('title','Departments')
@section('page-title','Department Management')
@section('content')
<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-building-fill text-primary me-2"></i>Add Department</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.departments.store') }}">
          @csrf
          <div class="mb-3"><label class="form-label">Department Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. IVF Lab"></div>
          <div class="mb-3"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="e.g. IVF-LAB"></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
          <div class="mb-3">
            <label class="form-label">Department Head</label>
            <select name="head_id" class="form-select">
              <option value="">— None —</option>
              @foreach($users as $u)
              <option value="{{ $u->id }}" {{ old('head_id')==$u->id?'selected':'' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Add Department</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-list-ul me-2"></i>Departments</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Code</th><th>Head</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($departments as $d)
              <tr>
                <td><div class="fw-500">{{ $d->name }}</div><div class="text-muted" style="font-size:.72rem;">{{ $d->description }}</div></td>
                <td><span class="badge bg-primary-subtle text-primary">{{ $d->code }}</span></td>
                <td style="font-size:.83rem;">{{ $d->head?->name ?? '—' }}</td>
                <td><span class="badge {{ $d->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($d->status) }}</span></td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center py-4 text-muted">No departments yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
