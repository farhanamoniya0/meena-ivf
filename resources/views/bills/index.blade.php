@extends('layouts.app')
@section('title','All Bills')
@section('page-title','Bills')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Bills</h5></div>
  <a href="{{ route('bills.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Bill</a>
</div>

<div class="row g-3 mb-3">
  <div class="col-6 col-md-3"><div class="stat-card bg-white text-center">
    <h4 class="fw-700 text-success mb-0">৳{{ number_format($todayTotal) }}</h4><small class="text-muted">Today's Collections</small>
  </div></div>
  <div class="col-6 col-md-3"><div class="stat-card bg-white text-center">
    <h4 class="fw-700 text-warning mb-0">৳{{ number_format($pendingAmt) }}</h4><small class="text-muted">Pending Balance</small>
  </div></div>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Bill no, patient name, phone..." value="{{ request('search') }}" style="max-width:220px;">
      <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="max-width:145px;">
      <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="max-width:145px;">
      <select name="status" class="form-select form-select-sm" style="max-width:130px;">
        <option value="">All Status</option>
        @foreach(['draft','partial','paid','cancelled'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
      <a href="{{ route('bills.index') }}" class="btn btn-light btn-sm">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Bill No</th><th>Patient</th><th>Date</th><th>Consultant</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($bills as $bill)
          @php $sc=['draft'=>'secondary','partial'=>'warning text-dark','paid'=>'success','cancelled'=>'danger'] @endphp
          <tr>
            <td><a href="{{ route('bills.show',$bill) }}" class="fw-500 text-decoration-none text-primary">{{ $bill->bill_no }}</a></td>
            <td>
              <div class="fw-500" style="font-size:.85rem;">{{ $bill->patient->name }}</div>
              <div class="text-muted" style="font-size:.72rem;">{{ $bill->patient->patient_code }}</div>
            </td>
            <td style="font-size:.83rem;">{{ $bill->bill_date->format('d M Y') }}</td>
            <td style="font-size:.83rem;">{{ $bill->consultant?->name ?? '—' }}</td>
            <td class="fw-700">৳{{ number_format($bill->net_total) }}</td>
            <td class="text-success fw-600">৳{{ number_format($bill->paid_amount) }}</td>
            <td class="{{ $bill->balance > 0 ? 'text-danger fw-600' : 'text-muted' }}">৳{{ number_format($bill->balance) }}</td>
            <td><span class="badge bg-{{ $sc[$bill->status] }}">{{ ucfirst($bill->status) }}</span></td>
            <td>
              <a href="{{ route('bills.show',$bill) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="View"><i class="bi bi-eye"></i></a>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" class="text-center py-4 text-muted">No bills found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $bills->links() }}</div>
  </div>
</div>
@endsection
