@extends('layouts.app')
@section('title','Assign Task')
@section('page-title','Assign Task')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-check2-square text-success me-2"></i>Assign New Task</div>
      <div class="card-body">
        <form method="POST" action="{{ route('tasks.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Task Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Follow up with patient after procedure">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Assign To <span class="text-danger">*</span></label>
              <select name="assigned_to" class="form-select" required>
                <option value="">— Select Staff —</option>
                @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('assigned_to')==$u->id?'selected':'' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Priority <span class="text-danger">*</span></label>
              <select name="priority" class="form-select" required>
                @foreach(['low','medium','high','urgent'] as $p)
                <option value="{{ $p }}" {{ old('priority','medium')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Due Date</label>
              <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
            </div>
            <div class="col-6">
              <label class="form-label">Department</label>
              <select name="department_id" class="form-select">
                <option value="">— None —</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ old('department_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Assign Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
