@extends('layouts.app')
@section('title','Lab Reports')
@section('page-title','Lab Reports')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">Lab Reports — Andrology</h5>
    <small class="text-muted">Semen Analysis Workflow Management</small>
  </div>
  <a href="{{ route('lab.reports.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-lg me-1"></i>New Sample Entry
  </a>
</div>

{{-- Status Summary Cards --}}
<div class="row g-2 mb-3">
  @php
    $tabs = [
      ['key'=>'','label'=>'All','icon'=>'bi-list-ul','color'=>'#374151','bg'=>'#f3f4f6'],
      ['key'=>'pending','label'=>'Pending','icon'=>'bi-hourglass-split','color'=>'#6b7280','bg'=>'#f3f4f6'],
      ['key'=>'collected','label'=>'Collected','icon'=>'bi-droplet-fill','color'=>'#0891b2','bg'=>'#e0f2fe'],
      ['key'=>'processing','label'=>'Processing','icon'=>'bi-gear-fill','color'=>'#d97706','bg'=>'#fef3c7'],
      ['key'=>'ready','label'=>'Ready','icon'=>'bi-check-circle-fill','color'=>'#7c3aed','bg'=>'#ede9fe'],
      ['key'=>'delivered','label'=>'Delivered','icon'=>'bi-bag-check-fill','color'=>'#16a34a','bg'=>'#dcfce7'],
    ];
    $allCount = array_sum($counts);
  @endphp
  @foreach($tabs as $tab)
  <div class="col-6 col-md-2">
    <a href="{{ route('lab.reports.index', array_merge(request()->query(), ['status'=>$tab['key']])) }}"
       class="card border-0 text-decoration-none h-100 {{ request('status')===$tab['key'] ? 'shadow' : '' }}"
       style="background:{{ request('status')===$tab['key'] ? $tab['bg'] : '#fff' }};border:2px solid {{ request('status')===$tab['key'] ? $tab['color'] : '#e5e7eb' }}!important;">
      <div class="card-body p-2 text-center">
        <i class="bi {{ $tab['icon'] }} mb-1" style="font-size:1.3rem;color:{{ $tab['color'] }};"></i>
        <div class="fw-700" style="font-size:1.1rem;color:{{ $tab['color'] }};">
          {{ $tab['key'] ? ($counts[$tab['key']] ?? 0) : $allCount }}
        </div>
        <div style="font-size:.7rem;color:#6b7280;">{{ $tab['label'] }}</div>
      </div>
    </a>
  </div>
  @endforeach
</div>

{{-- Search --}}
<form method="GET" class="mb-3">
  <input type="hidden" name="status" value="{{ request('status') }}">
  <div class="input-group input-group-sm" style="max-width:380px;">
    <input type="text" name="search" class="form-control" placeholder="Search sample code, patient name / phone…" value="{{ request('search') }}">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    @if(request('search'))
      <a href="{{ route('lab.reports.index',['status'=>request('status')]) }}" class="btn btn-outline-danger"><i class="bi bi-x"></i></a>
    @endif
  </div>
</form>

@if(session('success'))
  <div class="alert alert-success py-2">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0" style="font-size:.82rem;">
        <thead style="background:#f8fafc;">
          <tr>
            <th class="ps-3">Sample Code</th>
            <th>Patient</th>
            <th>Test Type</th>
            <th>Status</th>
            <th>Collected At</th>
            <th>Ready At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reports as $r)
          <tr>
            <td class="ps-3 fw-600">
              <a href="{{ route('lab.reports.show',$r) }}" class="text-decoration-none">{{ $r->sample_code }}</a>
            </td>
            <td>
              <div class="fw-600">{{ $r->patient->name }}</div>
              <div style="color:#9ca3af;font-size:.7rem;">{{ $r->patient->patient_code }}</div>
            </td>
            <td>{{ str_replace('_',' ', ucwords($r->test_type,'_')) }}</td>
            <td>
              <span class="badge rounded-pill px-2 py-1" style="background:{{ $r->status_bg }};color:{{ $r->status_color }};font-size:.72rem;">
                {{ $r->status_label }}
              </span>
            </td>
            <td>{{ $r->collected_at ? $r->collected_at->format('d M, h:i A') : '—' }}</td>
            <td>{{ $r->reported_at ? $r->reported_at->format('d M, h:i A') : '—' }}</td>
            <td>
              <a href="{{ route('lab.reports.show',$r) }}" class="btn btn-xs btn-outline-primary">
                <i class="bi bi-eye"></i>
              </a>
              @if($r->status === 'ready' || $r->status === 'delivered')
              <a href="{{ route('lab.reports.print',$r) }}" target="_blank" class="btn btn-xs btn-outline-success ms-1">
                <i class="bi bi-printer"></i>
              </a>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center py-4 text-muted">No reports found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">{{ $reports->links() }}</div>
@endsection
