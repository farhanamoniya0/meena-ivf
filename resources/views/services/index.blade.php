@extends('layouts.app')
@section('title','Service Master')
@section('page-title','Service Master')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Service Master</h5><small class="text-muted">All billable services & charges</small></div>
  <a href="{{ route('services.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Service</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Code</th><th>Service Name</th><th>Category</th><th>Charge (৳)</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($services as $svc)
          <tr>
            <td><span class="badge bg-secondary fw-500" style="font-size:.75rem;letter-spacing:.5px;">{{ $svc->service_code }}</span></td>
            <td class="fw-500">{{ $svc->name }}</td>
            <td><span class="badge bg-primary-subtle text-primary">{{ $svc->category ?? '—' }}</span></td>
            <td class="fw-700 text-primary">৳{{ number_format($svc->charge) }}</td>
            <td style="font-size:.82rem;color:#6b7280;">{{ Str::limit($svc->description,60) ?? '—' }}</td>
            <td><span class="badge {{ $svc->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($svc->status) }}</span></td>
            <td>
              <a href="{{ route('services.edit', $svc) }}" class="btn btn-sm btn-outline-warning py-0 px-2 me-1"><i class="bi bi-pencil"></i></a>
              @if($svc->status=='active')
              <form method="POST" action="{{ route('services.deactivate', $svc) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-secondary py-0 px-2" title="Deactivate"><i class="bi bi-eye-slash"></i></button></form>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center py-5">
            <i class="bi bi-list-check fs-1 text-muted d-block mb-2"></i>
            <h6>No services added yet</h6>
            <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm mt-1">Add First Service</a>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $services->links() }}</div>
  </div>
</div>
@endsection
