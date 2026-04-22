@extends('layouts.app')
@section('title','Reports Ready for Delivery')
@section('page-title','Lab Reports')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-600 mb-0">Reports Ready for Delivery</h5>
    <small class="text-muted">Print and hand to patient, then mark as delivered.</small>
  </div>
  <a href="{{ route('lab.reports.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-list-ul me-1"></i>All Reports
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success py-2">{{ session('success') }}</div>
@endif

@if($reports->isEmpty())
<div class="text-center py-5 text-muted">
  <i class="bi bi-check2-all" style="font-size:3rem;color:#16a34a;"></i>
  <p class="mt-2">No pending reports to deliver.</p>
</div>
@else
<div class="row g-3">
  @foreach($reports as $r)
  <div class="col-md-6 col-xl-4">
    <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #7c3aed!important;">
      <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <div class="fw-700 text-primary" style="font-size:.85rem;">{{ $r->sample_code }}</div>
            <div class="fw-600">{{ $r->patient->name }}</div>
            <div style="font-size:.75rem;color:#9ca3af;">{{ $r->patient->patient_code }} &middot; {{ $r->patient->phone }}</div>
          </div>
          <span class="badge" style="background:#ede9fe;color:#7c3aed;font-size:.7rem;">Ready</span>
        </div>
        <div style="font-size:.78rem;color:#6b7280;" class="mb-3">
          <i class="bi bi-clock me-1"></i>Ready since {{ $r->reported_at ? $r->reported_at->diffForHumans() : '—' }}
          @if($r->reporter)
          &nbsp;·&nbsp; By {{ $r->reporter->name }}
          @endif
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('lab.reports.print',$r) }}" target="_blank" class="btn btn-success btn-sm flex-fill">
            <i class="bi bi-printer me-1"></i>Print
          </a>
          <form method="POST" action="{{ route('lab.reports.advance',$r) }}" class="flex-fill">
            @csrf
            <button type="submit" class="btn btn-outline-success btn-sm w-100">
              <i class="bi bi-check2 me-1"></i>Mark Delivered
            </button>
          </form>
          <a href="{{ route('lab.reports.show',$r) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-eye"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection
