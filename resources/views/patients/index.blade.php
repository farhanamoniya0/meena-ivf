@extends('layouts.app')
@section('title','Patients')
@section('page-title','All Patients')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
  <div>
    <h5 class="fw-600 mb-0">Patient Registry</h5>
    <small class="text-muted">{{ $patients->total() }} total patients</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('patients.quick') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-lightning-fill me-1"></i>Quick Register</a>
    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Full Register</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, phone, code..." value="{{ request('search') }}" style="max-width:280px;">
      <select name="type" class="form-select form-select-sm" style="max-width:140px;">
        <option value="">All Types</option>
        <option value="full" {{ request('type')=='full'?'selected':'' }}>Full</option>
        <option value="quick" {{ request('type')=='quick'?'selected':'' }}>Quick</option>
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Search</button>
      @if(request()->hasAny(['search','type']))
      <a href="{{ route('patients.index') }}" class="btn btn-light btn-sm">Clear</a>
      @endif
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr><th>Patient Code</th><th>Name</th><th>Phone</th><th>Age/Gender</th><th>Consultant</th><th>Type</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          @forelse($patients as $p)
          <tr>
            <td><span class="badge bg-primary-subtle text-primary fw-600">{{ $p->patient_code }}</span></td>
            <td>
              <a href="{{ route('patients.show', $p) }}" class="text-decoration-none fw-500">{{ $p->name }}</a>
              <div class="text-muted" style="font-size:.72rem;">Reg: {{ $p->created_at->format('d M Y') }}</div>
            </td>
            <td>{{ $p->phone }}</td>
            <td>{{ $p->age ?? '—' }} / {{ ucfirst($p->gender) }}</td>
            <td>{{ $p->consultant?->name ?? '—' }}</td>
            <td><span class="badge {{ $p->registration_type=='full'?'bg-success-subtle text-success':'bg-info-subtle text-info' }}">{{ ucfirst($p->registration_type) }}</span></td>
            <td>
              @php $sc=['active'=>'success','inactive'=>'secondary','completed'=>'primary'] @endphp
              <span class="badge bg-{{ $sc[$p->status] }}-subtle text-{{ $sc[$p->status] }}">{{ ucfirst($p->status) }}</span>
            </td>
            <td>
              <a href="{{ route('patients.show', $p) }}" class="btn btn-sm btn-outline-info py-0 px-2" title="View"><i class="bi bi-eye"></i></a>
              <a href="{{ route('patients.edit', $p) }}" class="btn btn-sm btn-outline-warning py-0 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
              <a href="{{ route('billing.patient', $p) }}" class="btn btn-sm btn-outline-success py-0 px-2" title="Billing"><i class="bi bi-receipt"></i></a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No patients found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $patients->links() }}</div>
  </div>
</div>
@endsection
