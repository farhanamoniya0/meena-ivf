@extends('layouts.app')
@section('title','My Dashboard')
@section('page-title','My Dashboard')
@push('styles')
<style>
.period-card { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
.period-header { padding: 12px 16px; display: flex; align-items: center; gap: 10px; }
.period-header .ph-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,.35); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.period-header .ph-label { font-weight: 700; font-size: .9rem; color: #1f2937; }
.period-header .ph-total { margin-left: auto; font-size: 1.6rem; font-weight: 800; }
.stat-row { display: flex; gap: 0; border-top: 1px solid rgba(0,0,0,.06); }
.stat-cell { flex: 1; padding: 10px 8px; text-align: center; border-right: 1px solid rgba(0,0,0,.06); background: #fff; }
.stat-cell:last-child { border-right: none; }
.stat-cell .sc-val { font-size: 1.4rem; font-weight: 800; line-height: 1; }
.stat-cell .sc-lbl { font-size: .68rem; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-top: 3px; }
.stat-cell.highlight .sc-val { color: #7c3aed; }
.welcome-bar { background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%); border-radius: 12px; padding: 20px 24px; color: #fff; margin-bottom: 1.5rem; }
.tomorrow-badge { background: rgba(255,255,255,.2); border-radius: 8px; padding: 4px 10px; font-size: .75rem; font-weight: 600; }
</style>
@endpush
@section('content')

{{-- Welcome bar --}}
<div class="welcome-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
  <div>
    <div style="font-size:.8rem;opacity:.8;">Welcome back,</div>
    <div style="font-size:1.2rem;font-weight:700;">{{ $consultant?->name ?? $user->name }}</div>
    @if($consultant?->specialty)
    <div style="font-size:.82rem;opacity:.75;">{{ $consultant->specialty }}</div>
    @endif
  </div>
  <div class="text-end">
    <div style="font-size:.8rem;opacity:.8;">Today</div>
    <div style="font-size:1rem;font-weight:700;">{{ now()->format('l, d F Y') }}</div>
    @if(!$consultant)
    <span class="tomorrow-badge mt-1 d-inline-block" style="background:rgba(255,100,100,.25);color:#ffd;">
      <i class="bi bi-exclamation-triangle me-1"></i>No consultant profile linked to your email
    </span>
    @endif
  </div>
</div>

@php
$metrics = [
  'new_patient' => ['New Patient',  'bi-person-plus-fill',    '#7c3aed'],
  'followup'    => ['Follow Up',    'bi-arrow-repeat',        '#0369a1'],
  'scan'        => ['Scan',         'bi-activity',            '#047857'],
  'iui'         => ['IUI',          'bi-droplet-half',        '#b45309'],
  'stimulation' => ['Stimulation',  'bi-capsule',             '#be185d'],
];
@endphp

@foreach($periods as $period)
<div class="period-card">
  <div class="period-header" style="background:{{ $period['bg'] }};">
    <div class="ph-icon" style="color:{{ $period['color'] }};"><i class="bi {{ $period['icon'] }}"></i></div>
    <span class="ph-label">{{ $period['label'] }}</span>
    <span class="ph-total" style="color:{{ $period['color'] }};">{{ $period['stats']['total'] }}</span>
    <span style="font-size:.72rem;color:#6b7280;margin-left:2px;margin-top:10px;">Total</span>
  </div>
  <div class="stat-row">
    @foreach($metrics as $key => [$label, $icon, $color])
    <div class="stat-cell">
      <div class="sc-val" style="color:{{ $color }};">{{ $period['stats'][$key] }}</div>
      <div class="sc-lbl"><i class="bi {{ $icon }} me-1" style="color:{{ $color }};opacity:.7;"></i>{{ $label }}</div>
    </div>
    @endforeach
  </div>
</div>
@endforeach

{{-- Tomorrow's appointment detail list --}}
@php
  $tomorrowDate = now()->addDay()->toDateString();
  $tomorrowAppts = \App\Models\Appointment::where('consultant_id', $consultant?->id)
    ->where('appointment_date', $tomorrowDate)
    ->with('patient')
    ->orderBy('appointment_time')
    ->get();
@endphp
@if($consultant && $tomorrowAppts->count())
<div class="card">
  <div class="card-header d-flex align-items-center justify-content-between" style="background:#f3e8ff;">
    <span><i class="bi bi-calendar-event text-primary me-2"></i><strong>Tomorrow's Appointments</strong> — {{ now()->addDay()->format('d F Y') }}</span>
    <span class="badge" style="background:#7c3aed;">{{ $tomorrowAppts->count() }}</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0" style="font-size:.83rem;">
      <thead><tr style="background:#faf5ff;">
        <th>#</th><th>Time</th><th>Patient</th><th>Code</th><th>Type</th><th>Notes</th>
      </tr></thead>
      <tbody>
        @foreach($tomorrowAppts as $i => $appt)
        @php
        $typeColors = [
          'new_patient' => 'bg-purple-subtle text-purple',
          'followup'    => 'bg-primary-subtle text-primary',
          'scan'        => 'bg-success-subtle text-success',
          'iui'         => 'bg-warning-subtle text-warning',
          'stimulation' => 'bg-danger-subtle text-danger',
          'opd'         => 'bg-info-subtle text-info',
          'ivf'         => 'bg-secondary-subtle text-secondary',
          'consultation'=> 'bg-secondary-subtle text-secondary',
          'procedure'   => 'bg-secondary-subtle text-secondary',
        ];
        $typeLabels = [
          'new_patient' => 'New Patient','followup'=>'Follow Up','scan'=>'Scan',
          'iui'=>'IUI','stimulation'=>'Stimulation','opd'=>'OPD',
          'ivf'=>'IVF','consultation'=>'Consultation','procedure'=>'Procedure',
        ];
        @endphp
        <tr>
          <td class="text-muted">{{ $i+1 }}</td>
          <td class="fw-600">{{ $appt->appointment_time ? date('h:i A', strtotime($appt->appointment_time)) : '—' }}</td>
          <td><a href="{{ route('patients.show', $appt->patient) }}" class="text-decoration-none fw-600">{{ $appt->patient->name }}</a></td>
          <td><span class="badge bg-secondary">{{ $appt->patient->patient_code }}</span></td>
          <td>
            <span class="badge {{ $typeColors[$appt->type] ?? 'bg-secondary' }}">
              {{ $typeLabels[$appt->type] ?? ucfirst($appt->type) }}
            </span>
          </td>
          <td class="text-muted" style="font-size:.75rem;">{{ $appt->notes ?? '—' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- Lab Reports Widget --}}
@php
  $labPending    = \App\Models\LabReport::where('status','pending')->count();
  $labProcessing = \App\Models\LabReport::whereIn('status',['collected','processing'])->count();
  $labReady      = \App\Models\LabReport::where('status','ready')->count();
  $labReadyList  = \App\Models\LabReport::where('status','ready')->with(['patient','reporter'])->latest('reported_at')->take(5)->get();
@endphp
<div class="card border-0 shadow-sm mt-3" style="border-left:4px solid #7c3aed!important;">
  <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2 px-3">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-clipboard2-pulse-fill" style="color:#7c3aed;"></i>
      <strong style="font-size:.88rem;">Lab Reports Status</strong>
      @if($labReady > 0)
        <span class="badge rounded-pill" style="background:#7c3aed;">{{ $labReady }} Ready</span>
      @endif
    </div>
    <a href="{{ route('lab.reports.index') }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:3px 10px;">View All</a>
  </div>
  <div class="card-body p-3">
    <div class="d-flex gap-3 flex-wrap mb-3">
      <span class="badge" style="background:#f3f4f6;color:#6b7280;font-size:.78rem;">Pending: {{ $labPending }}</span>
      <span class="badge" style="background:#fef3c7;color:#d97706;font-size:.78rem;">In Progress: {{ $labProcessing }}</span>
      <span class="badge" style="background:#ede9fe;color:#7c3aed;font-size:.78rem;">Ready: {{ $labReady }}</span>
      <span class="badge" style="background:#dcfce7;color:#16a34a;font-size:.78rem;">Delivered today: {{ \App\Models\LabReport::whereDate('delivered_at',today())->count() }}</span>
    </div>
    @if($labReadyList->isNotEmpty())
      <div style="font-size:.8rem;font-weight:600;color:#6b7280;margin-bottom:6px;">Ready for Delivery:</div>
      @foreach($labReadyList as $r)
      <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
        <div>
          <span class="fw-600 text-primary">{{ $r->sample_code }}</span>
          <span class="ms-2 text-muted">{{ $r->patient->name }}</span>
        </div>
        <a href="{{ route('lab.reports.show',$r) }}" class="btn btn-xs btn-outline-primary" style="font-size:.7rem;padding:2px 8px;">View</a>
      </div>
      @endforeach
    @else
      <div class="text-muted" style="font-size:.82rem;">No reports awaiting delivery.</div>
    @endif
  </div>
</div>

@endsection
