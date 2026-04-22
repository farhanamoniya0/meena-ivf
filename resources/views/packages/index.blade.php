@extends('layouts.app')
@section('title','IVF Packages')
@section('page-title','IVF Packages')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">IVF Packages</h5><small class="text-muted">Manage treatment packages</small></div>
  <div class="d-flex gap-2">
    <a href="{{ route('packages.assign') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-plus me-1"></i>Assign to Patient</a>
    <a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>New Package</a>
  </div>
</div>
<div class="row g-3">
  @forelse($packages as $pkg)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-700 mb-0">{{ $pkg->name }}</h6>
          <span class="badge {{ $pkg->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($pkg->status) }}</span>
        </div>
        <div class="mb-2">
          <span class="fw-700 fs-5 text-primary">৳{{ number_format($pkg->total_cost) }}</span>
        </div>
        @if($pkg->description)
        <p class="text-muted mb-2" style="font-size:.82rem;">{{ $pkg->description }}</p>
        @endif
        @if($pkg->included_services)
        <div class="mb-2">
          <small class="text-muted fw-500">Included Services:</small>
          <div style="font-size:.8rem;white-space:pre-line;color:#374151;">{{ $pkg->included_services }}</div>
        </div>
        @endif
        @if($pkg->duration_days)
        <div class="text-muted mb-3" style="font-size:.78rem;"><i class="bi bi-clock me-1"></i>{{ $pkg->duration_days }} days</div>
        @endif
        <div class="text-muted mb-3" style="font-size:.78rem;"><i class="bi bi-people me-1"></i>{{ $pkg->patient_packages_count }} patients enrolled</div>
        <div class="d-flex gap-2 mt-auto">
          <a href="{{ route('packages.assign') }}?package_id={{ $pkg->id }}" class="btn btn-sm btn-primary flex-grow-1">Assign</a>
          <a href="{{ route('packages.edit', $pkg) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card"><div class="card-body text-center py-5">
      <i class="bi bi-box fs-1 text-muted d-block mb-3"></i>
      <h5>No packages yet</h5>
      <a href="{{ route('packages.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus-lg me-1"></i>Create First Package</a>
    </div></div>
  </div>
  @endforelse
</div>
<div class="mt-3">{{ $packages->links() }}</div>
@endsection
