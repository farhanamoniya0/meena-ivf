@extends('layouts.app')
@section('title',"Today's Collections")
@section('page-title',"Today's Collections")
@section('content')
<div class="d-flex justify-content-between mb-3 flex-wrap gap-2 align-items-center">
  <div>
    <h5 class="fw-600 mb-0">Daily Collections</h5>
    <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
  </div>
  @if(auth()->user()->hasRole(['admin','accountant']))
  <form method="POST" action="{{ route('accounts.close') }}">
    @csrf
    <button class="btn btn-success btn-sm" onclick="return confirm('Close today? This action records end-of-day.')"><i class="bi bi-lock-fill me-1"></i>Close Day</button>
  </form>
  @endif
</div>

@php
  $methodCards = [
    'cash'    => ['Cash',    'success',   'bi-cash'],
    'bank'    => ['Bank',    'primary',   'bi-bank2'],
    'card'    => ['Card',    'info',      'bi-credit-card'],
    'bkash'   => ['bKash',   'danger',    'bi-phone'],
    'nagad'   => ['Nagad',   'warning',   'bi-phone-fill'],
    'rocket'  => ['Rocket',  'secondary', 'bi-rocket'],
    'advance' => ['Advance', 'dark',      'bi-piggy-bank'],
  ];
  $mc = ['cash'=>'success','bank'=>'primary','card'=>'info','bkash'=>'danger','nagad'=>'warning','rocket'=>'secondary','advance'=>'dark'];
@endphp

<div class="row g-3 mb-4">
  @foreach($methodCards as $key => [$label, $color, $icon])
  @if($summary[$key] > 0 || in_array($key, ['cash','bank','card','bkash','nagad','rocket']))
  <div class="col-6 col-md-2">
    <div class="stat-card bg-white text-center">
      <div class="icon mx-auto mb-2" style="border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:var(--bs-{{ $color }}-bg,#f8f9fa);color:var(--bs-{{ $color }},#333);">
        <i class="bi {{ $icon }}"></i>
      </div>
      <div class="fw-700">৳{{ number_format($summary[$key]) }}</div>
      <small class="text-muted">{{ $label }}</small>
    </div>
  </div>
  @endif
  @endforeach
</div>

<div class="card mb-3" style="border-left:4px solid #0b6e4f;">
  <div class="card-body d-flex justify-content-between align-items-center">
    <div>
      <div class="text-muted" style="font-size:.82rem;">Total Collection Today</div>
      <h3 class="fw-700 mb-0 text-success">৳{{ number_format($summary['total']) }}</h3>
    </div>
    <div class="text-end">
      <div class="text-muted" style="font-size:.82rem;">Transactions</div>
      <h3 class="fw-700 mb-0">{{ $transactions->count() }}</h3>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><i class="bi bi-list-ul me-2"></i>Transactions</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Receipt / Bill No.</th>
            <th>Type</th>
            <th>Patient</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Txn ID</th>
            <th>Received By</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $txn)
          <tr>
            <td>
              <a href="{{ $txn['ref_url'] }}" target="_blank" style="font-size:.78rem;">{{ $txn['ref_no'] }}</a>
            </td>
            <td>
              @if($txn['type'] === 'package')
                <span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">IVF Pkg</span>
              @elseif($txn['method'] === 'advance')
                <span class="badge bg-dark-subtle text-dark" style="font-size:.7rem;">Advance Adj.</span>
              @else
                <span class="badge bg-success-subtle text-success" style="font-size:.7rem;">OP Bill</span>
              @endif
            </td>
            <td>
              <div class="fw-500" style="font-size:.83rem;">{{ $txn['patient']->name }}</div>
              <div class="text-muted" style="font-size:.72rem;">{{ $txn['patient']->patient_code }}</div>
            </td>
            <td class="fw-600 text-success">৳{{ number_format($txn['amount'], 2) }}</td>
            <td>
              <span class="badge bg-{{ $mc[$txn['method']] ?? 'secondary' }}">{{ ucfirst($txn['method']) }}</span>
            </td>
            <td style="font-size:.78rem;">{{ $txn['txn_id'] ?? '—' }}</td>
            <td style="font-size:.83rem;">{{ $txn['received_by'] }}</td>
            <td style="font-size:.78rem;">{{ $txn['time'] instanceof \Carbon\Carbon ? $txn['time']->format('h:i A') : \Carbon\Carbon::parse($txn['time'])->format('h:i A') }}</td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No collections today.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
