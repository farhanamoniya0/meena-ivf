@extends('layouts.app')
@section('title','Tasks')
@section('page-title','My Tasks')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h5 class="fw-600 mb-0">Tasks</h5><small class="text-muted">Assigned to you</small></div>
  @if(auth()->user()->hasRole(['admin','doctor','consultant']))
  <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Assign Task</a>
  @endif
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Task</th><th>Patient</th><th>Priority</th><th>Due Date</th><th>Assigned By</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($myTasks as $task)
          <tr>
            <td>
              <div class="fw-500">{{ $task->title }}</div>
              @if($task->description)<div class="text-muted" style="font-size:.72rem;">{{ Str::limit($task->description,60) }}</div>@endif
            </td>
            <td style="font-size:.83rem;">{{ $task->patient?->name ?? '—' }}</td>
            <td>
              @php $pc=['urgent'=>'danger','high'=>'warning','medium'=>'info','low'=>'secondary'] @endphp
              <span class="badge bg-{{ $pc[$task->priority] }}">{{ ucfirst($task->priority) }}</span>
            </td>
            <td>
              @if($task->due_date)
              <span class="{{ $task->due_date->isPast() && $task->status!='completed' ? 'text-danger fw-600' : 'text-muted' }}" style="font-size:.83rem;">
                {{ $task->due_date->format('d M Y') }}
              </span>
              @else —
              @endif
            </td>
            <td style="font-size:.83rem;">{{ $task->assignedBy->name }}</td>
            <td>
              @php $sc=['pending'=>'secondary','in-progress'=>'primary','completed'=>'success','cancelled'=>'danger'] @endphp
              <span class="badge bg-{{ $sc[$task->status] }}">{{ ucfirst($task->status) }}</span>
            </td>
            <td>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0" data-bs-toggle="dropdown">Update</button>
                <ul class="dropdown-menu border-0 shadow" style="border-radius:10px;font-size:.83rem;">
                  @foreach(['in-progress','completed','cancelled'] as $ns)
                  <li>
                    <form method="POST" action="{{ route('tasks.status',$task) }}">
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
          <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-check-all fs-3 d-block mb-2"></i>All caught up! No pending tasks.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $myTasks->links() }}</div>
  </div>
</div>
@endsection
