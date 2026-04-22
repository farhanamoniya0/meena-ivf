@extends('layouts.app')
@section('title','Appointments')
@section('page-title','Appointments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Appointments</h5><small class="text-muted">{{ $appointments->total() }} found for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</small></div>
  <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-calendar-plus me-1"></i>Book Appointment</a>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
      <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" style="max-width:160px;">
      <select name="consultant_id" class="form-select form-select-sm" style="max-width:180px;">
        <option value="">All Consultants</option>
        @foreach($consultants as $c)
        <option value="{{ $c->id }}" {{ request('consultant_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
        @endforeach
      </select>
      <select name="status" class="form-select form-select-sm" style="max-width:140px;">
        <option value="">All Status</option>
        @foreach(['scheduled','confirmed','completed','cancelled','no-show'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Time</th><th>Patient</th><th>Type</th><th>Consultant</th><th>Department</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($appointments as $a)
          <tr>
            <td class="fw-600" style="font-size:.85rem;">{{ $a->appointment_time ? date('h:i A',strtotime($a->appointment_time)) : '—' }}</td>
            <td>
              <a href="{{ route('patients.show',$a->patient) }}" class="text-decoration-none fw-500">{{ $a->patient->name }}</a>
              <div class="text-muted" style="font-size:.72rem;">{{ $a->patient->patient_code }}</div>
            </td>
            <td><span class="badge bg-info-subtle text-info">{{ ucfirst($a->type) }}</span></td>
            <td style="font-size:.83rem;">{{ $a->consultant?->name ?? '—' }}</td>
            <td style="font-size:.83rem;">{{ $a->department?->name ?? '—' }}</td>
            <td>
              @php $sc=['scheduled'=>'secondary','confirmed'=>'primary','completed'=>'success','cancelled'=>'danger','no-show'=>'warning'] @endphp
              <span class="badge bg-{{ $sc[$a->status] }}">{{ ucfirst($a->status) }}</span>
            </td>
            <td>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0" data-bs-toggle="dropdown">Status</button>
                <ul class="dropdown-menu shadow border-0" style="border-radius:10px;font-size:.83rem;">
                  @foreach(['confirmed','completed','cancelled','no-show'] as $ns)
                  <li>
                    <form method="POST" action="{{ route('appointments.status',$a) }}">
                      @csrf <input type="hidden" name="status" value="{{ $ns }}">
                      <button class="dropdown-item">{{ ucfirst($ns) }}</button>
                    </form>
                  </li>
                  @endforeach
                </ul>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center py-4 text-muted">No appointments for this date.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $appointments->links() }}</div>
  </div>
</div>
@endsection
