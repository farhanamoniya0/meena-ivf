@extends('layouts.app')
@section('title','Admin Panel')
@section('page-title','Admin Dashboard')
@section('content')
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat-card bg-white">
    <div class="icon mb-2" style="background:#e8f5e9;color:#2e7d32;width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-people-fill"></i></div>
    <h3 class="fw-700 mb-0">{{ $stats['today_patients'] }}</h3><p class="text-muted mb-0" style="font-size:.78rem;">Today's Patients</p>
  </div></div>
  <div class="col-6 col-md-3"><div class="stat-card bg-white">
    <div class="icon mb-2" style="background:#e3f2fd;color:#1565c0;width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-cash-coin"></i></div>
    <h3 class="fw-700 mb-0">৳{{ number_format($stats['today_revenue']) }}</h3><p class="text-muted mb-0" style="font-size:.78rem;">Today's Revenue</p>
  </div></div>
  <div class="col-6 col-md-3"><div class="stat-card bg-white">
    <div class="icon mb-2" style="background:#fff3e0;color:#e65100;width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-hourglass-split"></i></div>
    <h3 class="fw-700 mb-0">{{ $stats['pending_payments'] }}</h3><p class="text-muted mb-0" style="font-size:.78rem;">Pending Payments</p>
  </div></div>
  <div class="col-6 col-md-3"><div class="stat-card bg-white">
    <div class="icon mb-2" style="background:#fce4ec;color:#c62828;width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-list-task"></i></div>
    <h3 class="fw-700 mb-0">{{ $stats['pending_tasks'] }}</h3><p class="text-muted mb-0" style="font-size:.78rem;">Pending Tasks</p>
  </div></div>
</div>

<div class="row g-3">
  {{-- Departments --}}
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-building text-primary me-2"></i>Departments</span>
        <a href="{{ route('admin.departments') }}" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size:.75rem;">Manage</a>
      </div>
      <div class="card-body p-0">
        @foreach($departments as $dept)
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
          <div>
            <div class="fw-500" style="font-size:.85rem;">{{ $dept->name }}</div>
            <div class="text-muted" style="font-size:.72rem;">{{ $dept->head?->name ?? 'No head assigned' }}</div>
          </div>
          <span class="badge {{ $dept->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($dept->status) }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Pending Payment Approvals --}}
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-cash-stack text-warning me-2"></i>Pending Payment Approvals</span>
        <span class="badge bg-warning text-dark">{{ $pendingPayments->count() }}</span>
      </div>
      @if($pendingPayments->count())
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead><tr><th>Patient</th><th>Amount</th><th>Method</th><th>By</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($pendingPayments as $pay)
              <tr>
                <td style="font-size:.83rem;">{{ $pay->patient->name }}</td>
                <td class="fw-600">৳{{ number_format($pay->amount) }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($pay->payment_method) }}</span></td>
                <td style="font-size:.78rem;">{{ $pay->receivedBy->name }}</td>
                <td>
                  <form method="POST" action="{{ route('admin.approve.payment',$pay) }}" class="d-inline">@csrf<button class="btn btn-xs btn-success py-0 px-1 me-1"><i class="bi bi-check-lg"></i></button></form>
                  <form method="POST" action="{{ route('admin.reject.payment',$pay) }}" class="d-inline">@csrf<button class="btn btn-xs btn-danger py-0 px-1"><i class="bi bi-x-lg"></i></button></form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @else
      <div class="card-body text-center text-muted py-3"><i class="bi bi-check-circle me-1"></i>All payments approved.</div>
      @endif
    </div>

    {{-- Pending Requisitions --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-inbox text-info me-2"></i>Pending Requisitions</span>
        <span class="badge bg-info text-white">{{ $pendingReqs->count() }}</span>
      </div>
      @if($pendingReqs->count())
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead><tr><th>Medicine</th><th>Qty</th><th>By</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($pendingReqs as $req)
              <tr>
                <td style="font-size:.83rem;">{{ $req->medicine->name }}</td>
                <td>{{ $req->quantity }}</td>
                <td style="font-size:.78rem;">{{ $req->requestedBy->name }}</td>
                <td>
                  <form method="POST" action="{{ route('admin.approve.req',$req) }}" class="d-inline">@csrf<button class="btn btn-xs btn-success py-0 px-1 me-1"><i class="bi bi-check-lg"></i></button></form>
                  <form method="POST" action="{{ route('admin.reject.req',$req) }}" class="d-inline">@csrf<button class="btn btn-xs btn-danger py-0 px-1"><i class="bi bi-x-lg"></i></button></form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @else
      <div class="card-body text-center text-muted py-3">No pending requisitions.</div>
      @endif
    </div>
  </div>

  {{-- Low Medicines & Expiring --}}
  @if($lowMeds->count() || $expiringBatches->count())
  <div class="col-12">
    <div class="card border-warning">
      <div class="card-header bg-warning-subtle"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Pharmacy Alerts</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <h6 class="text-danger fw-600 mb-2">Low Stock</h6>
            @forelse($lowMeds as $m)
            <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:.83rem;">
              <span>{{ $m->name }}</span><span class="badge bg-danger">{{ $m->total_stock }} {{ $m->unit }}</span>
            </div>
            @empty <p class="text-muted mb-0">All stock levels OK.</p>
            @endforelse
          </div>
          <div class="col-md-6">
            <h6 class="text-warning fw-600 mb-2">Expiring Soon</h6>
            @forelse($expiringBatches as $b)
            <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:.83rem;">
              <span>{{ $b->medicine->name }}</span><span class="badge bg-warning text-dark">{{ $b->expiry_date->format('d M Y') }}</span>
            </div>
            @empty <p class="text-muted mb-0">No batches expiring soon.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection
