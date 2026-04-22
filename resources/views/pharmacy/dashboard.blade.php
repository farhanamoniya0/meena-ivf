@extends('layouts.app')
@section('title','Pharmacy')
@section('page-title','Pharmacy Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Pharmacy Dashboard</h5></div>
  <div class="d-flex gap-2">
    <a href="{{ route('pharmacy.medicines') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-list-ul me-1"></i>All Medicines</a>
    <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Medicine</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="stat-card bg-white text-center">
      <div class="icon mx-auto mb-2" style="background:#e8f5e9;color:#2e7d32;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-capsule-pill fs-5"></i></div>
      <h4 class="fw-700 mb-0">{{ $medicines->count() }}</h4>
      <small class="text-muted">Total Medicines</small>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="stat-card bg-white text-center">
      <div class="icon mx-auto mb-2" style="background:#ffebee;color:#c62828;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-arrow-down-circle fs-5"></i></div>
      <h4 class="fw-700 mb-0 text-danger">{{ $lowStock->count() }}</h4>
      <small class="text-muted">Low Stock</small>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="stat-card bg-white text-center">
      <div class="icon mx-auto mb-2" style="background:#fff3e0;color:#e65100;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-calendar-x fs-5"></i></div>
      <h4 class="fw-700 mb-0 text-warning">{{ $expiringBatches->count() }}</h4>
      <small class="text-muted">Expiring Soon</small>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="stat-card bg-white text-center">
      <div class="icon mx-auto mb-2" style="background:#e3f2fd;color:#1565c0;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-inbox fs-5"></i></div>
      <h4 class="fw-700 mb-0">{{ $pendingReqs->count() }}</h4>
      <small class="text-muted">Pending Reqs</small>
    </div>
  </div>
</div>

<div class="row g-3">
  @if($lowStock->count())
  <div class="col-md-6">
    <div class="card border-danger">
      <div class="card-header bg-danger-subtle d-flex justify-content-between">
        <span><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Low Stock Medicines</span>
        <span class="badge bg-danger">{{ $lowStock->count() }}</span>
      </div>
      <div class="card-body p-0">
        @foreach($lowStock as $med)
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
          <div>
            <div class="fw-500" style="font-size:.85rem;">{{ $med->name }}</div>
            <div class="text-muted" style="font-size:.72rem;">Reorder at: {{ $med->reorder_level }} {{ $med->unit }}</div>
          </div>
          <div class="text-end">
            <span class="badge bg-danger">{{ $med->total_stock }} {{ $med->unit }}</span>
            <div><a href="{{ route('pharmacy.batches.add', $med) }}" class="btn btn-xs btn-outline-danger py-0 px-1 mt-1" style="font-size:.72rem;">Restock</a></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  @if($expiringBatches->count())
  <div class="col-md-6">
    <div class="card border-warning">
      <div class="card-header bg-warning-subtle">
        <i class="bi bi-clock-history text-warning me-2"></i>Expiring Batches (≤30 days)
      </div>
      <div class="card-body p-0">
        @foreach($expiringBatches as $batch)
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
          <div>
            <div class="fw-500" style="font-size:.85rem;">{{ $batch->medicine->name }}</div>
            <div class="text-muted" style="font-size:.72rem;">Batch: {{ $batch->batch_number }}</div>
          </div>
          <div class="text-end">
            <span class="badge bg-warning text-dark">{{ $batch->expiry_date->format('d M Y') }}</span>
            <div class="text-muted" style="font-size:.72rem;">{{ $batch->quantity }} {{ $batch->medicine->unit }} left</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- Pending Requisitions --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-inbox text-primary me-2"></i>Pending Requisitions</span>
        <a href="{{ route('pharmacy.requisitions') }}" class="btn btn-sm btn-outline-primary">All Reqs</a>
      </div>
      <div class="card-body p-0">
        @forelse($pendingReqs as $req)
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
          <div>
            <div class="fw-500" style="font-size:.85rem;">{{ $req->medicine->name }}</div>
            <div class="text-muted" style="font-size:.72rem;">Qty: {{ $req->quantity }} — By: {{ $req->requestedBy->name }}</div>
          </div>
          <div class="d-flex gap-1">
            <form method="POST" action="{{ route('pharmacy.requisitions.approve', $req) }}">@csrf<button class="btn btn-sm btn-success py-0 px-2"><i class="bi bi-check-lg"></i></button></form>
            <form method="POST" action="{{ route('pharmacy.requisitions.reject', $req) }}">@csrf<button class="btn btn-sm btn-danger py-0 px-2"><i class="bi bi-x-lg"></i></button></form>
          </div>
        </div>
        @empty
        <div class="text-center py-3 text-muted">No pending requisitions.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
