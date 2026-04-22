@extends('layouts.app')
@section('title','Closing History')
@section('page-title','Daily Closing History')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-600 mb-0">Daily Closing History</h5>
  <a href="{{ route('accounts.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr><th>Date</th><th>Cash</th><th>Bank</th><th>Card</th><th>bKash</th><th>Nagad</th><th>Rocket</th><th>Total</th><th>Txns</th><th>Closed By</th><th>Status</th></tr>
        </thead>
        <tbody>
          @forelse($closings as $c)
          <tr>
            <td class="fw-600">{{ $c->closing_date->format('d M Y') }}</td>
            <td>{{ $c->total_cash > 0 ? '৳'.number_format($c->total_cash) : '—' }}</td>
            <td>{{ $c->total_bank > 0 ? '৳'.number_format($c->total_bank) : '—' }}</td>
            <td>{{ $c->total_card > 0 ? '৳'.number_format($c->total_card) : '—' }}</td>
            <td>{{ $c->total_bkash > 0 ? '৳'.number_format($c->total_bkash) : '—' }}</td>
            <td>{{ $c->total_nagad > 0 ? '৳'.number_format($c->total_nagad) : '—' }}</td>
            <td>{{ $c->total_rocket > 0 ? '৳'.number_format($c->total_rocket) : '—' }}</td>
            <td class="fw-700 text-success">৳{{ number_format($c->total_amount) }}</td>
            <td>{{ $c->total_transactions }}</td>
            <td style="font-size:.83rem;">{{ $c->closedBy?->name ?? '—' }}</td>
            <td><span class="badge {{ $c->status=='closed'?'bg-success':'bg-warning text-dark' }}">{{ ucfirst($c->status) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="11" class="text-center py-4 text-muted">No closing records yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $closings->links() }}</div>
  </div>
</div>
@endsection
