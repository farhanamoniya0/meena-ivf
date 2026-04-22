@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('content')

{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-3">
  <div class="col-6 col-md-3">
    <a href="{{ route('patients.index', ['created_today'=>1]) }}" class="text-decoration-none">
    <div class="stat-card bg-white h-100" style="cursor:pointer;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow=''">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-people-fill"></i></div>
        <span class="badge bg-success-subtle text-success" style="font-size:.7rem;">Today</span>
      </div>
      <h3 class="fw-700 mb-0">{{ $stats['today_patients'] }}</h3>
      <p class="text-muted mb-0" style="font-size:.78rem;">New Patients <i class="bi bi-arrow-right-short"></i></p>
    </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('appointments.index', ['date'=>today()->toDateString()]) }}" class="text-decoration-none">
    <div class="stat-card bg-white h-100" style="cursor:pointer;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow=''">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="icon" style="background:#e3f2fd;color:#1565c0;"><i class="bi bi-calendar-check-fill"></i></div>
        <span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">Today</span>
      </div>
      <h3 class="fw-700 mb-0">{{ $stats['today_appointments'] }}</h3>
      <p class="text-muted mb-0" style="font-size:.78rem;">Appointments <i class="bi bi-arrow-right-short"></i></p>
    </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('billing.today') }}" class="text-decoration-none">
    <div class="stat-card bg-white h-100" style="cursor:pointer;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow=''">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="icon" style="background:#fff3e0;color:#e65100;"><i class="bi bi-cash-coin"></i></div>
        <span class="badge bg-warning-subtle text-warning" style="font-size:.7rem;">Today</span>
      </div>
      <h3 class="fw-700 mb-0">৳{{ number_format($stats['today_revenue']) }}</h3>
      <p class="text-muted mb-0" style="font-size:.78rem;">Collection <i class="bi bi-arrow-right-short"></i></p>
    </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card bg-white h-100" style="background:linear-gradient(135deg,#5b21b6,#7c3aed) !important;">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="icon" style="background:rgba(255,255,255,.2);color:#fff;"><i class="bi bi-graph-up-arrow"></i></div>
        <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.7rem;">{{ now()->format('M Y') }}</span>
      </div>
      <h3 class="fw-700 mb-0 text-white">৳{{ number_format($stats['my_monthly_collection']) }}</h3>
      <p class="mb-0" style="font-size:.78rem;color:rgba(255,255,255,.8);">My Monthly Collection</p>
    </div>
  </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <a href="{{ route('patients.index') }}" class="text-decoration-none">
    <div class="stat-card bg-white" style="cursor:pointer;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow=''">
      <div class="icon mb-2" style="background:#f3e5f5;color:#6a1b9a;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-people"></i></div>
      <h4 class="fw-700 mb-0">{{ $stats['total_patients'] }}</h4>
      <p class="text-muted mb-0" style="font-size:.78rem;">Total Patients <i class="bi bi-arrow-right-short"></i></p>
    </div>
    </a>
  </div>
  <div class="col-md-3">
    <div class="stat-card bg-white">
      <div class="icon mb-2" style="background:#e8f5e9;color:#2e7d32;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-box-fill"></i></div>
      <h4 class="fw-700 mb-0">{{ $stats['active_packages'] }}</h4>
      <p class="text-muted mb-0" style="font-size:.78rem;">Active IVF Packages</p>
    </div>
  </div>
  <div class="col-md-3">
    <a href="{{ route('bills.index') }}" class="text-decoration-none">
    <div class="stat-card bg-white" style="cursor:pointer;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseout="this.style.boxShadow=''">
      <div class="icon mb-2" style="background:#e0f7fa;color:#00695c;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-receipt"></i></div>
      <h4 class="fw-700 mb-0">৳{{ number_format($stats['advance_total']) }}</h4>
      <p class="text-muted mb-0" style="font-size:.78rem;">Advance Adj. (This Month) <i class="bi bi-arrow-right-short"></i></p>
    </div>
    </a>
  </div>
  <div class="col-md-3">
    <div class="stat-card bg-white">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="icon" style="background:#fce4ec;color:#c62828;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-list-task"></i></div>
        @if($stats['pending_tasks'] > 0)<span class="badge bg-danger" style="font-size:.7rem;">{{ $stats['pending_tasks'] }}</span>@endif
      </div>
      <h4 class="fw-700 mb-0">{{ $stats['pending_tasks'] }}</h4>
      <p class="text-muted mb-0" style="font-size:.78rem;">My Pending Tasks</p>
    </div>
  </div>
</div>

{{-- Today's New Patients (if any) --}}
@if($stats['today_new_patients']->count())
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <span><i class="bi bi-person-plus-fill me-2 text-success"></i>Today's New Patients
      <span class="badge bg-success ms-1">{{ $stats['today_new_patients']->count() }}</span>
    </span>
    <a href="{{ route('patients.index', ['created_today'=>1]) }}" class="btn btn-sm btn-outline-success">View All</a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>#</th><th>UID</th><th>Patient Name</th><th>Phone</th><th>Consultant</th><th>Reg. Time</th><th></th></tr></thead>
        <tbody>
          @foreach($stats['today_new_patients'] as $i => $np)
          <tr>
            <td class="text-muted" style="font-size:.78rem;">{{ $i+1 }}</td>
            <td><span class="badge bg-primary-subtle text-primary" style="font-size:.75rem;">{{ $np->patient_code }}</span></td>
            <td><a href="{{ route('patients.show', $np) }}" class="fw-500 text-decoration-none">{{ $np->name }}</a></td>
            <td style="font-size:.82rem;">{{ $np->phone }}</td>
            <td style="font-size:.82rem;">{{ $np->consultant?->name ?? '—' }}</td>
            <td style="font-size:.78rem;color:#6b7280;">{{ $np->created_at->format('h:i A') }}</td>
            <td><a href="{{ route('patients.show', $np) }}" class="btn btn-sm btn-outline-primary py-0 px-2">View</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<div class="row g-3">
  {{-- Today's Appointments --}}
  <div class="col-md-7">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-calendar-check me-2 text-primary"></i>Today's Appointments</span>
        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body p-0">
        @if($stats['today_appts']->isEmpty())
        <div class="text-center py-4 text-muted"><i class="bi bi-calendar-x fs-3 d-block mb-2"></i>No appointments today</div>
        @else
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Patient</th><th>Time</th><th>Consultant</th><th>Type</th><th>Status</th></tr></thead>
            <tbody>
              @foreach($stats['today_appts'] as $appt)
              <tr>
                <td>
                  <a href="{{ route('patients.show', $appt->patient) }}" class="text-decoration-none fw-500">{{ $appt->patient->name }}</a>
                  <div class="text-muted" style="font-size:.72rem;">{{ $appt->patient->patient_code }}</div>
                </td>
                <td><span class="fw-500">{{ $appt->appointment_time ? date('h:i A', strtotime($appt->appointment_time)) : '—' }}</span></td>
                <td>{{ $appt->consultant?->name ?? '—' }}</td>
                <td><span class="badge bg-info-subtle text-info">{{ ucfirst($appt->type) }}</span></td>
                <td>
                  @php $sc=['scheduled'=>'secondary','confirmed'=>'primary','completed'=>'success','cancelled'=>'danger','no-show'=>'warning'] @endphp
                  <span class="badge bg-{{ $sc[$appt->status] ?? 'secondary' }}">{{ ucfirst($appt->status) }}</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- My Tasks --}}
  <div class="col-md-5">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-check2-square me-2 text-success"></i>My Tasks</span>
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-success">View All</a>
      </div>
      <div class="card-body p-0">
        @if($stats['my_tasks']->isEmpty())
        <div class="text-center py-4 text-muted"><i class="bi bi-check-all fs-3 d-block mb-2"></i>All caught up!</div>
        @else
        @foreach($stats['my_tasks'] as $task)
        <div class="d-flex align-items-start gap-2 p-3 border-bottom">
          @php $pc=['urgent'=>'danger','high'=>'warning','medium'=>'info','low'=>'secondary'] @endphp
          <span class="badge bg-{{ $pc[$task->priority] }}" style="margin-top:2px;">{{ ucfirst($task->priority) }}</span>
          <div class="flex-grow-1">
            <div class="fw-500" style="font-size:.85rem;">{{ $task->title }}</div>
            @if($task->patient)<div class="text-muted" style="font-size:.72rem;">{{ $task->patient->name }}</div>@endif
            @if($task->due_date)<div class="text-{{ $task->due_date->isPast() ? 'danger' : 'muted' }}" style="font-size:.72rem;"><i class="bi bi-clock me-1"></i>Due {{ $task->due_date->format('d M') }}</div>@endif
          </div>
          <form method="POST" action="{{ route('tasks.status', $task) }}">
            @csrf
            <input type="hidden" name="status" value="completed">
            <button class="btn btn-sm btn-outline-success py-0 px-1" title="Mark done"><i class="bi bi-check-lg"></i></button>
          </form>
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>

  {{-- Alerts --}}
  @if($low_stock_meds->count() || $expiring_batches->count())
  <div class="col-12">
    <div class="card border-warning">
      <div class="card-header bg-warning-subtle"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Pharmacy Alerts</div>
      <div class="card-body">
        <div class="row g-3">
          @if($low_stock_meds->count())
          <div class="col-md-6">
            <h6 class="text-danger fw-600 mb-2"><i class="bi bi-arrow-down-circle-fill me-1"></i>Low Stock</h6>
            @foreach($low_stock_meds as $med)
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
              <span style="font-size:.83rem;">{{ $med->name }}</span>
              <span class="badge bg-danger">{{ $med->total_stock }} {{ $med->unit }}</span>
            </div>
            @endforeach
          </div>
          @endif
          @if($expiring_batches->count())
          <div class="col-md-6">
            <h6 class="text-warning fw-600 mb-2"><i class="bi bi-calendar-x-fill me-1"></i>Expiring Soon</h6>
            @foreach($expiring_batches as $batch)
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
              <span style="font-size:.83rem;">{{ $batch->medicine->name }}</span>
              <span class="badge bg-warning text-dark">{{ $batch->expiry_date->format('d M Y') }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- ===== LAB REPORTS SECTION ===== --}}
  <div class="col-12 mt-2">
    <div class="card border-0 shadow-sm" style="border-left:4px solid #7c3aed!important;">
      <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2 px-3">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-clipboard2-pulse-fill" style="color:#7c3aed;font-size:1.1rem;"></i>
          <strong style="font-size:.88rem;">Lab Reports</strong>
          @if($stats['lab_ready'] > 0)
          <span class="badge rounded-pill" style="background:#7c3aed;font-size:.72rem;">
            {{ $stats['lab_ready'] }} Ready
          </span>
          @endif
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('lab.reports.ready') }}" class="btn btn-xs btn-outline-success" style="font-size:.75rem;padding:3px 10px;">
            <i class="bi bi-list-check me-1"></i>Ready List
          </a>
          <a href="{{ route('lab.reports.index') }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:3px 10px;">
            <i class="bi bi-grid-3x3-gap me-1"></i>All Reports
          </a>
          @if(in_array(auth()->user()->role, ['lab','admin']))
          <a href="{{ route('lab.reports.create') }}" class="btn btn-xs btn-primary" style="font-size:.75rem;padding:3px 10px;">
            <i class="bi bi-plus-lg me-1"></i>New Sample
          </a>
          @endif
        </div>
      </div>

      {{-- Status summary mini-row --}}
      <div class="px-3 py-2 border-bottom d-flex gap-3 flex-wrap" style="background:#fafafa;">
        <div class="d-flex align-items-center gap-1">
          <span class="badge" style="background:#f3f4f6;color:#6b7280;">Pending: {{ $stats['lab_pending'] }}</span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <span class="badge" style="background:#fef3c7;color:#d97706;">In Progress: {{ $stats['lab_processing'] }}</span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <span class="badge" style="background:#ede9fe;color:#7c3aed;">Ready: {{ $stats['lab_ready'] }}</span>
        </div>
        <a href="{{ route('lab.reports.index',['status'=>'delivered']) }}" class="text-decoration-none">
          <span class="badge" style="background:#dcfce7;color:#16a34a;">Delivered today: {{ \App\Models\LabReport::whereDate('delivered_at',today())->count() }}</span>
        </a>
      </div>

      <div class="card-body p-0">
        @if($stats['lab_ready_reports']->isEmpty())
        <div class="text-center py-4 text-muted" style="font-size:.83rem;">
          <i class="bi bi-check2-all" style="font-size:1.6rem;color:#16a34a;display:block;margin-bottom:6px;"></i>
          No reports awaiting delivery right now.
        </div>
        @else
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="font-size:.81rem;">
            <thead style="background:#f8fafc;">
              <tr>
                <th class="ps-3">Sample Code</th>
                <th>Patient</th>
                <th>Test</th>
                <th>Ready Since</th>
                <th>Reported by</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($stats['lab_ready_reports'] as $r)
              <tr>
                <td class="ps-3 fw-600">
                  <a href="{{ route('lab.reports.show',$r) }}" class="text-decoration-none text-primary">{{ $r->sample_code }}</a>
                </td>
                <td>
                  <div class="fw-600">{{ $r->patient->name }}</div>
                  <div style="color:#9ca3af;font-size:.7rem;">{{ $r->patient->patient_code }}</div>
                </td>
                <td>{{ str_replace('_',' ',ucwords($r->test_type,'_')) }}</td>
                <td>
                  <span title="{{ $r->reported_at?->format('d M Y, h:i A') }}">
                    {{ $r->reported_at ? $r->reported_at->diffForHumans() : '—' }}
                  </span>
                </td>
                <td>{{ $r->reporter?->name ?? '—' }}</td>
                <td>
                  <a href="{{ route('lab.reports.print',$r) }}" target="_blank"
                     class="btn btn-xs btn-success me-1" style="font-size:.73rem;padding:3px 10px;">
                    <i class="bi bi-printer me-1"></i>Print
                  </a>
                  <form method="POST" action="{{ route('lab.reports.advance',$r) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-xs btn-outline-success" style="font-size:.73rem;padding:3px 10px;"
                            onclick="return confirm('Mark this report as delivered to patient?')">
                      <i class="bi bi-check2 me-1"></i>Delivered
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection
