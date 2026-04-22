@extends('layouts.app')
@section('title','Consultants')
@section('page-title','Consultants')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Consultants</h5><small class="text-muted">{{ $consultants->total() }} registered</small></div>
  <a href="{{ route('consultants.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Consultant</a>
</div>
<div class="row g-3">
  @forelse($consultants as $c)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          @if($c->photo)
          <img src="{{ asset('storage/'.$c->photo) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
          @else
          <div style="width:56px;height:56px;border-radius:50%;background:#17a589;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($c->name,0,1)) }}</div>
          @endif
          <div>
            <h6 class="fw-700 mb-0">{{ $c->name }}</h6>
            <div class="text-muted" style="font-size:.78rem;">{{ $c->specialty }}</div>
            <span class="badge {{ $c->status=='active'?'bg-success':'bg-secondary' }} mt-1">{{ ucfirst($c->status) }}</span>
          </div>
        </div>
        @if($c->qualifications)
        <div class="text-muted mb-1" style="font-size:.78rem;"><i class="bi bi-mortarboard me-1"></i>{{ $c->qualifications }}</div>
        @endif
        @if($c->phone)
        <div class="text-muted mb-1" style="font-size:.78rem;"><i class="bi bi-phone me-1"></i>{{ $c->phone }}</div>
        @endif
        @if($c->email)
        <div class="text-muted mb-2" style="font-size:.78rem;"><i class="bi bi-envelope me-1"></i>{{ $c->email }}</div>
        @endif
        <div class="text-muted mb-3" style="font-size:.78rem;"><i class="bi bi-people me-1"></i>{{ $c->patients_count }} patients</div>
        <a href="{{ route('consultants.edit',$c) }}" class="btn btn-sm btn-outline-warning w-100"><i class="bi bi-pencil me-1"></i>Edit</a>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12"><div class="card"><div class="card-body text-center py-5">
    <i class="bi bi-person-badge fs-1 text-muted d-block mb-3"></i>
    <h5>No consultants added yet</h5>
    <a href="{{ route('consultants.create') }}" class="btn btn-primary mt-2">Add First Consultant</a>
  </div></div></div>
  @endforelse
</div>
<div class="mt-3">{{ $consultants->links() }}</div>
@endsection
