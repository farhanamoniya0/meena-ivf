@extends('layouts.app')
@section('title','Batches')
@section('page-title','Stock Batches')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">{{ $medicine->name }}</h5>
    <small class="text-muted">{{ $medicine->generic_name }} | Total Stock: <strong>{{ $medicine->total_stock }} {{ $medicine->unit }}</strong></small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pharmacy.batches.add', $medicine) }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg me-1"></i>Add Batch</a>
    <a href="{{ route('pharmacy.medicines') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
  </div>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Batch #</th><th>Expiry Date</th><th>Quantity</th><th>Purchase Price</th><th>Sale Price</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($batches as $batch)
          @php
            $expired  = $batch->expiry_date->isPast();
            $expiring = !$expired && $batch->expiry_date->diffInDays(now()) <= 30;
          @endphp
          <tr class="{{ $expired ? 'table-danger' : ($expiring ? 'table-warning' : '') }}">
            <td class="fw-500">{{ $batch->batch_number }}</td>
            <td>
              <span class="{{ $expired ? 'text-danger fw-600' : ($expiring ? 'text-warning fw-600' : '') }}">{{ $batch->expiry_date->format('d M Y') }}</span>
              @if($expired)<span class="badge bg-danger ms-1">Expired</span>@elseif($expiring)<span class="badge bg-warning text-dark ms-1">Expiring Soon</span>@endif
            </td>
            <td class="fw-600">{{ $batch->quantity }} {{ $medicine->unit }}</td>
            <td>{{ $batch->purchase_price > 0 ? '৳'.number_format($batch->purchase_price,2) : '—' }}</td>
            <td>{{ $batch->sale_price > 0 ? '৳'.number_format($batch->sale_price,2) : '—' }}</td>
            <td><span class="badge {{ $batch->quantity > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $batch->quantity > 0 ? 'In Stock' : 'Empty' }}</span></td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center py-4 text-muted">No batches found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $batches->links() }}</div>
  </div>
</div>
@endsection
