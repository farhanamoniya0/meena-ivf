@extends('layouts.app')
@section('title','Lab Report — '.$report->sample_code)
@section('page-title','Lab Reports')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">{{ $report->sample_code }}</h5>
    <small class="text-muted">{{ str_replace('_',' ',ucwords($report->test_type,'_')) }} — {{ $report->patient->name }} ({{ $report->patient->patient_code }})</small>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('lab.reports.index') }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    @if(in_array($report->status,['ready','delivered']))
    <a href="{{ route('lab.reports.print',$report) }}" target="_blank" class="btn btn-sm btn-success">
      <i class="bi bi-printer me-1"></i>Print Report
    </a>
    @endif
  </div>
</div>

{{-- Progress Timeline --}}
<div class="card border-0 shadow-sm mb-3">
  <div class="card-body py-3 px-4">
    @php
      $steps = [
        ['key'=>'pending',    'icon'=>'bi-clipboard-plus',      'label'=>'Registered',       'time'=>$report->created_at],
        ['key'=>'collected',  'icon'=>'bi-droplet-fill',        'label'=>'Sample Collected', 'time'=>$report->collected_at],
        ['key'=>'processing', 'icon'=>'bi-gear-fill',           'label'=>'Processing',       'time'=>$report->processed_at],
        ['key'=>'ready',      'icon'=>'bi-check-circle-fill',   'label'=>'Report Ready',     'time'=>$report->reported_at],
        ['key'=>'delivered',  'icon'=>'bi-bag-check-fill',      'label'=>'Delivered',        'time'=>$report->delivered_at],
      ];
      $statusOrder = ['pending','collected','processing','ready','delivered'];
      $currentIdx  = array_search($report->status, $statusOrder);
    @endphp
    <div class="d-flex align-items-center justify-content-between" style="position:relative;">
      <div style="position:absolute;top:22px;left:5%;right:5%;height:3px;background:#e5e7eb;z-index:0;"></div>
      @foreach($steps as $i => $step)
      @php
        $done   = $i <= $currentIdx;
        $active = $i === $currentIdx;
        $color  = $done ? ($active ? '#7c3aed' : '#16a34a') : '#d1d5db';
        $bg     = $done ? ($active ? '#ede9fe' : '#dcfce7') : '#f9fafb';
      @endphp
      <div class="text-center" style="flex:1;position:relative;z-index:1;">
        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle border-2"
             style="width:44px;height:44px;background:{{ $bg }};border:3px solid {{ $color }};margin-bottom:6px;">
          <i class="bi {{ $step['icon'] }}" style="font-size:1rem;color:{{ $color }};"></i>
        </div>
        <div style="font-size:.7rem;font-weight:{{ $active?'700':'500' }};color:{{ $done?$color:'#9ca3af' }};">
          {{ $step['label'] }}
        </div>
        @if($step['time'])
        <div style="font-size:.65rem;color:#9ca3af;">{{ \Carbon\Carbon::parse($step['time'])->format('d M, h:i A') }}</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="row g-3">
  {{-- Left: Info + Action --}}
  <div class="col-md-5">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="fw-700 mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Patient Details</div>
        <table class="table table-sm table-borderless mb-0" style="font-size:.83rem;">
          <tr><td class="text-muted" style="width:40%;">Name</td><td class="fw-600">{{ $report->patient->name }}</td></tr>
          <tr><td class="text-muted">UHID</td><td>{{ $report->patient->patient_code }}</td></tr>
          <tr><td class="text-muted">Phone</td><td>{{ $report->patient->phone }}</td></tr>
          <tr><td class="text-muted">Sample Code</td><td class="fw-600 text-primary">{{ $report->sample_code }}</td></tr>
          <tr><td class="text-muted">Test Type</td><td>{{ str_replace('_',' ',ucwords($report->test_type,'_')) }}</td></tr>
          <tr><td class="text-muted">Registered by</td><td>{{ $report->creator?->name ?? '—' }}</td></tr>
          @if($report->collected_by)
          <tr><td class="text-muted">Collected by</td><td>{{ $report->collector->name }}</td></tr>
          @endif
          @if($report->reported_by)
          <tr><td class="text-muted">Reported by</td><td>{{ $report->reporter->name }}</td></tr>
          @endif
          @if($report->delivered_by)
          <tr><td class="text-muted">Delivered by</td><td>{{ $report->deliverer->name }}</td></tr>
          @endif
        </table>
        @if($report->notes)
        <div class="mt-2 p-2 rounded" style="background:#f8fafc;font-size:.8rem;color:#6b7280;">
          <strong>Notes:</strong> {{ $report->notes }}
        </div>
        @endif
      </div>
    </div>

    {{-- Action Button --}}
    @if($report->next_status && $report->next_status !== 'ready')
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="fw-700 mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Next Action</div>
        <form method="POST" action="{{ route('lab.reports.advance',$report) }}">
          @csrf
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-arrow-right-circle me-2"></i>{{ $report->next_action_label }}
          </button>
        </form>
      </div>
    </div>
    @elseif($report->status === 'ready')
    <div class="card border-0 shadow-sm" style="border-left:4px solid #16a34a!important;">
      <div class="card-body">
        <div class="fw-700 mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Deliver Report</div>
        <p class="text-muted" style="font-size:.8rem;">Print the report and hand it to the patient, then mark as delivered.</p>
        <div class="d-flex gap-2">
          <a href="{{ route('lab.reports.print',$report) }}" target="_blank" class="btn btn-success flex-fill">
            <i class="bi bi-printer me-1"></i>Print Report
          </a>
          <form method="POST" action="{{ route('lab.reports.advance',$report) }}" class="flex-fill">
            @csrf
            <button type="submit" class="btn btn-outline-success w-100">
              <i class="bi bi-check2 me-1"></i>Mark Delivered
            </button>
          </form>
        </div>
      </div>
    </div>
    @elseif($report->status === 'delivered')
    <div class="alert alert-success mb-0">
      <i class="bi bi-bag-check-fill me-2"></i>Report delivered to patient on {{ $report->delivered_at->format('d M Y, h:i A') }}.
    </div>
    @endif
  </div>

  {{-- Right: Report Data Entry / Display --}}
  <div class="col-md-7">
    @if($report->status === 'processing')
    {{-- Fill Report Form --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom py-2 px-3">
        <strong style="font-size:.85rem;">Semen Analysis Report Data</strong>
        <small class="text-muted ms-2">Fill all parameters then Submit Report</small>
      </div>
      <div class="card-body p-3">
        @if(session('error'))
          <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('lab.reports.advance',$report) }}">
          @csrf
          @include('lab.reports._semen_form', ['data' => $report->report_data ?? []])
          <hr>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-1"></i>Submit Report &amp; Mark Ready
          </button>
        </form>
      </div>
    </div>
    @elseif($report->report_data)
    {{-- Show Filled Data --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom py-2 px-3 d-flex justify-content-between">
        <strong style="font-size:.85rem;">Semen Analysis Results</strong>
        @if(!in_array($report->status,['delivered']))
        <a href="{{ route('lab.reports.edit',$report) }}" class="btn btn-xs btn-outline-secondary">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
      </div>
      <div class="card-body p-3">
        @include('lab.reports._semen_display', ['data' => $report->report_data])
      </div>
    </div>
    @else
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-clipboard2-pulse" style="font-size:2.5rem;"></i>
        <p class="mt-2 mb-0">Report data will appear here once the lab starts processing.</p>
      </div>
    </div>
    @endif
  </div>
</div>

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div class="toast show bg-success text-white" role="alert">
    <div class="toast-body"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
  </div>
</div>
@endif
@endsection
