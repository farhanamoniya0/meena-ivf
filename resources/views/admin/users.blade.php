@extends('layouts.app')
@section('title','Users')
@section('page-title','System Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-600 mb-0">System Users</h5>
  <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add User</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($users as $u)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div style="width:34px;height:34px;border-radius:50%;background:#17a589;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($u->name,0,2)) }}</div>
                <div>
                  <div class="fw-500" style="font-size:.85rem;">{{ $u->name }}</div>
                  @if($u->id === auth()->id())<small class="badge bg-primary-subtle text-primary">You</small>@endif
                </div>
              </div>
            </td>
            <td style="font-size:.83rem;">{{ $u->email }}</td>
            <td style="font-size:.83rem;">{{ $u->phone ?? '—' }}</td>
            <td>
              @php $rc=['admin'=>'danger','doctor'=>'primary','consultant'=>'success','billing'=>'warning','accountant'=>'info','pharmacy'=>'secondary','lab'=>'dark','reception'=>'light text-dark'] @endphp
              <span class="badge bg-{{ $rc[$u->role] }}">{{ ucfirst($u->role) }}</span>
            </td>
            <td><span class="badge {{ $u->is_active?'bg-success':'bg-secondary' }}">{{ $u->is_active?'Active':'Inactive' }}</span></td>
            <td style="font-size:.78rem;">{{ $u->created_at->format('d M Y') }}</td>
            <td>
              @if($u->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.toggle',$u) }}">
                @csrf
                <button class="btn btn-sm {{ $u->is_active?'btn-outline-danger':'btn-outline-success' }} py-0 px-2">
                  {{ $u->is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
  </div>
</div>
@endsection
