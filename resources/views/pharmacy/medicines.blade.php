@extends('layouts.app')
@section('title','Medicines')
@section('page-title','Medicine Stock')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Medicines</h5><small class="text-muted">{{ $medicines->total() }} medicines</small></div>
  <div class="d-flex gap-2">
    <a href="{{ route('pharmacy.assign') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-person-check me-1"></i>Assign to Patient</a>
    <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Medicine</a>
  </div>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Generic / Brand</th><th>Category</th><th>Unit</th><th>Stock</th><th>Reorder At</th><th>Batches</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($medicines as $med)
          @php $stock = $med->total_stock; $low = $stock <= $med->reorder_level; @endphp
          <tr class="{{ $low ? 'table-danger' : '' }}">
            <td>
              <div class="fw-500">{{ $med->name }}</div>
              <span class="badge {{ $med->status=='active'?'bg-success-subtle text-success':'bg-secondary-subtle text-secondary' }}">{{ ucfirst($med->status) }}</span>
            </td>
            <td style="font-size:.82rem;">{{ $med->generic_name ?? '—' }}<br><span class="text-muted">{{ $med->brand ?? '' }}</span></td>
            <td style="font-size:.82rem;">{{ $med->category ?? '—' }}</td>
            <td>{{ $med->unit }}</td>
            <td>
              <span class="fw-600 {{ $low ? 'text-danger' : 'text-success' }}">{{ $stock }}</span>
              @if($low)<span class="badge bg-danger ms-1">Low</span>@endif
            </td>
            <td>{{ $med->reorder_level }}</td>
            <td><span class="badge bg-primary-subtle text-primary">{{ $med->batches_count }}</span></td>
            <td>
              <a href="{{ route('pharmacy.batches', $med) }}" class="btn btn-sm btn-outline-info py-0 px-2" title="Batches"><i class="bi bi-layers"></i></a>
              <a href="{{ route('pharmacy.batches.add', $med) }}" class="btn btn-sm btn-outline-success py-0 px-2" title="Add Stock"><i class="bi bi-plus-lg"></i></a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No medicines added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $medicines->links() }}</div>
  </div>
</div>
@endsection
