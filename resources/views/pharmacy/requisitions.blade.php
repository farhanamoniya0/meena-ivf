@extends('layouts.app')
@section('title','Requisitions')
@section('page-title','Medicine Requisitions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-600 mb-0">Requisitions</h5>
  <a href="{{ route('pharmacy.assign') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>New Requisition</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Medicine</th><th>Qty</th><th>Requested By</th><th>Reason</th><th>Status</th><th>Approved By</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($requisitions as $req)
          <tr>
            <td class="fw-500">{{ $req->medicine->name }}</td>
            <td>{{ $req->quantity }}</td>
            <td style="font-size:.83rem;">{{ $req->requestedBy->name }}</td>
            <td style="font-size:.82rem;">{{ $req->reason ?? '—' }}</td>
            <td>
              @php $sc=['pending'=>'warning text-dark','approved'=>'success','rejected'=>'danger'] @endphp
              <span class="badge bg-{{ $sc[$req->status] }}">{{ ucfirst($req->status) }}</span>
            </td>
            <td style="font-size:.83rem;">{{ $req->approvedBy?->name ?? '—' }}</td>
            <td style="font-size:.78rem;">{{ $req->created_at->format('d M Y') }}</td>
            <td>
              @if($req->status === 'pending')
              <form method="POST" action="{{ route('pharmacy.requisitions.approve',$req) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success py-0 px-2 me-1"><i class="bi bi-check-lg"></i></button></form>
              <form method="POST" action="{{ route('pharmacy.requisitions.reject',$req) }}" class="d-inline">@csrf<button class="btn btn-sm btn-danger py-0 px-2"><i class="bi bi-x-lg"></i></button></form>
              @else — @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No requisitions found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $requisitions->links() }}</div>
  </div>
</div>
@endsection
