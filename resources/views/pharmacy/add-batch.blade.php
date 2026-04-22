@extends('layouts.app')
@section('title','Add Stock Batch')
@section('page-title','Add Stock Batch')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <div class="fw-600"><i class="bi bi-plus-circle-fill text-success me-2"></i>Add Batch: {{ $medicine->name }}</div>
        <small class="text-muted">Current Stock: {{ $medicine->total_stock }} {{ $medicine->unit }}</small>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('pharmacy.batches.store', $medicine) }}">
          @csrf
          <div class="mb-3"><label class="form-label">Batch Number *</label><input type="text" name="batch_number" class="form-control" value="{{ old('batch_number') }}" required placeholder="e.g. BTH-2024-001"></div>
          <div class="mb-3"><label class="form-label">Expiry Date *</label><input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required></div>
          <div class="mb-3"><label class="form-label">Quantity *</label><input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required min="1"></div>
          <div class="row g-3 mb-3">
            <div class="col-6"><label class="form-label">Purchase Price (৳)</label><input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" step="0.01" min="0"></div>
            <div class="col-6"><label class="form-label">Sale Price (৳)</label><input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}" step="0.01" min="0"></div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1"><i class="bi bi-check-lg me-2"></i>Add to Stock</button>
            <a href="{{ route('pharmacy.batches', $medicine) }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
