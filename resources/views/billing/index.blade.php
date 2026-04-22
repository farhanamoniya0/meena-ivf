@extends('layouts.app')
@section('title','Billing')
@section('page-title','Billing Desk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">Billing Desk</h5>
    <small class="text-muted">Patients with IVF packages</small>
  </div>
  <a href="{{ route('billing.today') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-cash-stack me-1"></i>Today's Collections</a>
</div>
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Search patient..." value="{{ request('search') }}" style="max-width:280px;">
      <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Search</button>
      @if(request('search'))<a href="{{ route('billing.index') }}" class="btn btn-light btn-sm">Clear</a>@endif
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Patient</th><th>Phone</th><th>Package</th><th>Net Payable</th><th>Paid</th><th>Remaining</th><th>Status</th><th></th></tr></thead>
        <tbody>
          @forelse($patients as $p)
          @php $pkg = $p->activePackage ?? $p->packages->last() @endphp
          <tr>
            <td>
              <a href="{{ route('billing.patient',$p) }}" class="text-decoration-none fw-500">{{ $p->name }}</a>
              <div class="text-muted" style="font-size:.72rem;">{{ $p->patient_code }}</div>
            </td>
            <td>{{ $p->phone }}</td>
            <td>{{ $pkg?->ivfPackage?->name ?? '—' }}</td>
            <td>৳{{ $pkg ? number_format($pkg->net_amount) : '—' }}</td>
            <td class="text-success fw-500">৳{{ $pkg ? number_format($pkg->paid_amount) : '—' }}</td>
            <td class="{{ ($pkg?->remaining ?? 0) > 0 ? 'text-danger fw-500' : 'text-success' }}">
              {{ $pkg ? ($pkg->remaining > 0 ? '৳'.number_format($pkg->remaining) : '✓ Paid') : '—' }}
            </td>
            <td>
              @if($pkg)
              @php $sc=['active'=>'success','completed'=>'primary','cancelled'=>'secondary'] @endphp
              <span class="badge bg-{{ $sc[$pkg->status] }}-subtle text-{{ $sc[$pkg->status] }}">{{ ucfirst($pkg->status) }}</span>
              @endif
            </td>
            <td>
              <a href="{{ route('billing.patient',$p) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
              @if($pkg && $pkg->status=='active')
              <a href="{{ route('billing.pay',$pkg) }}" class="btn btn-sm btn-primary py-0 px-2"><i class="bi bi-plus-lg"></i></a>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No billing records found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $patients->links() }}</div>
  </div>
</div>
@endsection
