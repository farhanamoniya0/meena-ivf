@extends('layouts.app')
@section('title','Accounts')
@section('page-title','Accounts Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">Accounts Dashboard</h5>
    <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('accounts.history') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history me-1"></i>Closing History</a>
    @if(!$todayClosed || $todayClosed->status !== 'closed')
    <form method="POST" action="{{ route('accounts.close') }}">
      @csrf
      <button class="btn btn-sm btn-success" onclick="return confirm('Close today and lock transactions?')">
        <i class="bi bi-lock-fill me-1"></i>Close Day
      </button>
    </form>
    @else
    <span class="badge bg-success fs-6 d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill"></i>Day Closed</span>
    @endif
  </div>
</div>

{{-- Today Summary --}}
<div class="row g-3 mb-4">
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['cash']) }}</div><small class="text-muted">Cash</small>
  </div></div>
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['bank']) }}</div><small class="text-muted">Bank</small>
  </div></div>
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['card']) }}</div><small class="text-muted">Card</small>
  </div></div>
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['bkash']) }}</div><small class="text-muted">bKash</small>
  </div></div>
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['nagad']) }}</div><small class="text-muted">Nagad</small>
  </div></div>
  <div class="col-md-2 col-6"><div class="stat-card bg-white text-center">
    <div class="fw-700 fs-5">৳{{ number_format($summary['rocket']) }}</div><small class="text-muted">Rocket</small>
  </div></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card" style="border-left:4px solid #0b6e4f;">
      <div class="card-body">
        <div class="text-muted" style="font-size:.78rem;">Today's Total</div>
        <h2 class="fw-800 text-success mb-0">৳{{ number_format($summary['total']) }}</h2>
        <small class="text-muted">{{ $summary['count'] }} transactions</small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" style="border-left:4px solid #1565c0;">
      <div class="card-body">
        <div class="text-muted" style="font-size:.78rem;">This Month</div>
        <h2 class="fw-800 text-primary mb-0">৳{{ number_format($monthTotal) }}</h2>
        <small class="text-muted">{{ now()->format('F Y') }}</small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" style="border-left:4px solid #e65100;">
      <div class="card-body">
        <div class="text-muted" style="font-size:.78rem;">Pending Approvals</div>
        <h2 class="fw-800 text-warning mb-0">{{ $pendingPayments->count() }}</h2>
        <small class="text-muted">payments pending</small>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  {{-- Pending approvals --}}
  @if($pendingPayments->count())
  <div class="col-12">
    <div class="card border-warning">
      <div class="card-header bg-warning-subtle d-flex align-items-center justify-content-between">
        <span><i class="bi bi-hourglass-split text-warning me-2"></i>Pending Payment Approvals</span>
        <span class="badge bg-warning text-dark">{{ $pendingPayments->count() }}</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Patient</th><th>Amount</th><th>Method</th><th>Received By</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($pendingPayments as $pay)
              <tr>
                <td><div class="fw-500">{{ $pay->patient->name }}</div><div class="text-muted" style="font-size:.72rem;">{{ $pay->patient->patient_code }}</div></td>
                <td class="fw-600">৳{{ number_format($pay->amount) }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($pay->payment_method) }}</span></td>
                <td style="font-size:.83rem;">{{ $pay->receivedBy->name }}</td>
                <td style="font-size:.83rem;">{{ $pay->created_at->format('d M h:i A') }}</td>
                <td>
                  <form method="POST" action="{{ route('accounts.approve', $pay) }}" class="d-inline">@csrf<button class="btn btn-xs btn-success py-0 px-2 me-1"><i class="bi bi-check-lg"></i> Approve</button></form>
                  <form method="POST" action="{{ route('accounts.reject', $pay) }}" class="d-inline">@csrf<button class="btn btn-xs btn-danger py-0 px-2"><i class="bi bi-x-lg"></i> Reject</button></form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- Recent transactions --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header"><i class="bi bi-list-ul me-2"></i>Recent Approved Transactions</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Receipt</th><th>Patient</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
            <tbody>
              @forelse($recentPayments as $pay)
              <tr>
                <td><a href="{{ route('billing.receipt',$pay) }}" style="font-size:.78rem;">{{ $pay->receipt_no }}</a></td>
                <td><div class="fw-500" style="font-size:.83rem;">{{ $pay->patient->name }}</div></td>
                <td class="fw-600 text-success">৳{{ number_format($pay->amount) }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($pay->payment_method) }}</span></td>
                <td style="font-size:.83rem;">{{ $pay->created_at->format('d M Y, h:i A') }}</td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center py-3 text-muted">No transactions yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
