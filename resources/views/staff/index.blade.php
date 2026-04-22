@extends('layouts.app')
@section('title','Staff Master')
@section('page-title','Staff Master')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Staff Master</h5><small class="text-muted">All employees</small></div>
  <a href="{{ route('staff.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Staff</a>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, ID, designation..." value="{{ request('search') }}" style="max-width:220px;">
      <select name="department" class="form-select form-select-sm" style="max-width:160px;">
        <option value="">All Departments</option>
        @foreach($departments as $d)<option value="{{ $d }}" {{ request('department')==$d?'selected':'' }}>{{ $d }}</option>@endforeach
      </select>
      <select name="status" class="form-select form-select-sm" style="max-width:130px;">
        <option value="">All Status</option>
        <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
        <option value="terminated" {{ request('status')=='terminated'?'selected':'' }}>Terminated</option>
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
    </form>
  </div>
</div>

<div class="row g-3">
  @forelse($staff as $s)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          @if($s->photo)
          <img src="{{ asset('storage/'.$s->photo) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #7c3aed;">
          @else
          <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#5b21b6,#7c3aed);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;flex-shrink:0;">{{ strtoupper(substr($s->name,0,1)) }}</div>
          @endif
          <div>
            <div class="fw-600">{{ $s->name }}</div>
            <div class="text-muted" style="font-size:.75rem;">{{ $s->employee_id }}</div>
            <span class="badge {{ $s->status=='active'?'bg-success':($s->status=='terminated'?'bg-danger':'bg-secondary') }}">{{ ucfirst($s->status) }}</span>
          </div>
        </div>
        <div style="font-size:.82rem;color:#374151;">
          <div class="mb-1"><i class="bi bi-briefcase me-2 text-primary"></i>{{ $s->designation }}</div>
          @if($s->department)<div class="mb-1"><i class="bi bi-building me-2 text-muted"></i>{{ $s->department }}</div>@endif
          @if($s->phone)<div class="mb-1"><i class="bi bi-phone me-2 text-muted"></i>{{ $s->phone }}</div>@endif
          @if($s->join_date)<div class="mb-1"><i class="bi bi-calendar me-2 text-muted"></i>Joined: {{ $s->join_date->format('d M Y') }}</div>@endif
        </div>
        <a href="{{ route('staff.edit', $s) }}" class="btn btn-sm btn-outline-warning w-100 mt-2"><i class="bi bi-pencil me-1"></i>Edit</a>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12"><div class="card"><div class="card-body text-center py-5">
    <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
    <h6>No staff members added yet</h6>
    <a href="{{ route('staff.create') }}" class="btn btn-primary btn-sm mt-2">Add First Staff</a>
  </div></div></div>
  @endforelse
</div>
<div class="mt-3">{{ $staff->links() }}</div>
@endsection
