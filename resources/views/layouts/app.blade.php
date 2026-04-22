<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — Meena IVF</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--sidebar-w:260px;--primary:#5b21b6;--accent:#7c3aed;--light-purple:#f3e8ff;--sidebar-bg:#16082e;--sidebar-text:rgba(255,255,255,.75);--sidebar-active:#7c3aed;}
*{font-family:'Poppins',sans-serif;}
body{background:#f5f3ff;min-height:100vh;}
/* SIDEBAR */
#sidebar{width:var(--sidebar-w);height:100vh;background:var(--sidebar-bg);position:fixed;top:0;left:0;z-index:1040;transition:width .3s;overflow-x:hidden;display:flex;flex-direction:column;}
#sidebar nav{flex:1;overflow-y:auto;overflow-x:hidden;padding-bottom:20px;}
#sidebar nav::-webkit-scrollbar{width:4px;}
#sidebar nav::-webkit-scrollbar-track{background:transparent;}
#sidebar nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.2);border-radius:4px;}
#sidebar nav::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.4);}
#sidebar .brand{padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.1);}
#sidebar .brand img{width:36px;height:36px;border-radius:50%;object-fit:cover;}
#sidebar .brand-text{font-weight:700;font-size:.95rem;color:#fff;line-height:1.2;}
#sidebar .brand-sub{font-size:.7rem;color:var(--accent);letter-spacing:.5px;}
#sidebar .nav-label{font-size:.65rem;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,.35);padding:16px 20px 4px;}
#sidebar .nav-link{color:var(--sidebar-text);padding:10px 20px;display:flex;align-items:center;gap:10px;font-size:.83rem;border-left:3px solid transparent;transition:all .2s;border-radius:0;}
#sidebar .nav-link:hover,#sidebar .nav-link.active{color:#fff;background:rgba(255,255,255,.08);border-left-color:var(--accent);}
#sidebar .nav-link i{font-size:1rem;width:20px;text-align:center;}
#sidebar .badge-pill{font-size:.6rem;padding:2px 6px;border-radius:20px;}
/* TOPBAR */
#topbar{margin-left:var(--sidebar-w);background:#fff;padding:0 24px;height:60px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 4px rgba(0,0,0,.08);position:sticky;top:0;z-index:1030;}
#topbar .page-title{font-weight:600;font-size:1rem;color:#1a2a3a;}
/* MAIN */
#main-content{margin-left:var(--sidebar-w);padding:24px;transition:margin .3s;}
/* CARDS */
.stat-card{border:none;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .2s;}
.stat-card:hover{transform:translateY(-2px);}
.stat-card .icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.card{border:none;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.06);}
.card-header{background:#fff;border-bottom:1px solid #f0f4f8;font-weight:600;font-size:.9rem;padding:14px 20px;border-radius:12px 12px 0 0 !important;}
/* TABLE */
.table thead th{background:#f8fafc;font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;font-weight:600;}
.table td{font-size:.85rem;vertical-align:middle;}
/* BADGES */
.badge-status{font-size:.72rem;padding:4px 10px;border-radius:20px;font-weight:500;}
/* BUTTONS */
.btn-primary{background:var(--primary);border-color:var(--primary);}
.btn-primary:hover{background:#4c1d95;border-color:#4c1d95;}
.btn-purple{background:var(--accent);border-color:var(--accent);color:#fff;}
.btn-purple:hover{background:#6d28d9;border-color:#6d28d9;color:#fff;}
/* ALERTS */
.alert{border-radius:10px;font-size:.85rem;}
/* FORM */
.form-control,.form-select{border-radius:8px;font-size:.875rem;border-color:#dee2e6;}
.form-control:focus,.form-select:focus{border-color:var(--accent);box-shadow:0 0 0 .2rem rgba(124,58,237,.15);}
.form-label{font-weight:500;font-size:.82rem;color:#374151;margin-bottom:4px;}
/* PAYMENT METHODS */
.method-card{border:2px solid #e5e7eb;border-radius:10px;padding:10px;cursor:pointer;text-align:center;transition:all .2s;}
.method-card:hover,.method-card.selected{border-color:var(--accent);background:#f3e8ff;}
/* Camera UI */
.camera-circle{width:110px;height:110px;border-radius:50%;background:#f3e8ff;border:3px dashed var(--accent);display:flex;align-items:center;justify-content:center;cursor:pointer;margin:0 auto;transition:all .2s;}
.camera-circle:hover{background:#ede9fe;border-style:solid;}
.camera-photo-preview{width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);margin:0 auto;display:block;}
/* Mobile */
@media(max-width:768px){
  #sidebar{width:0;}
  #topbar,#main-content{margin-left:0;}
  #sidebar.open{width:var(--sidebar-w);}
}
/* PRINT */
@media print{#sidebar,#topbar,.no-print{display:none!important;}#main-content{margin:0;padding:0;}}
</style>
@stack('styles')
</head>
<body>
<div id="sidebar">
  <div class="brand d-flex align-items-center gap-2">
    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;">M</div>
    <div>
      <div class="brand-text">Meena IVF</div>
      <div class="brand-sub">Management System</div>
    </div>
  </div>

  <nav>
    @php $role = auth()->user()->role; @endphp

    {{-- Dashboard — visible to all --}}
    <div class="nav-label">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    {{-- PATIENTS: admin, billing, reception --}}
    @if(in_array($role, ['admin','billing','reception']))
    <div class="nav-label">Patients</div>
    <a href="{{ route('patients.quick') }}" class="nav-link {{ request()->routeIs('patients.quick*') ? 'active' : '' }}">
      <i class="bi bi-person-plus-fill"></i> Quick Register
    </a>
    <a href="{{ route('patients.create') }}" class="nav-link {{ request()->routeIs('patients.create') ? 'active' : '' }}">
      <i class="bi bi-person-lines-fill"></i> Full Register
    </a>
    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.index') ? 'active' : '' }}">
      <i class="bi bi-people-fill"></i> All Patients
    </a>
    @endif

    {{-- PATIENTS (read-only): doctor, consultant, lab --}}
    @if(in_array($role, ['doctor','consultant','lab']))
    <div class="nav-label">Patients</div>
    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.index') ? 'active' : '' }}">
      <i class="bi bi-people-fill"></i> All Patients
    </a>
    @endif

    {{-- CLINICAL: admin, doctor, consultant, reception --}}
    @if(in_array($role, ['admin','doctor','consultant','reception']))
    <div class="nav-label">Clinical</div>
    <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments*') ? 'active' : '' }}">
      <i class="bi bi-calendar-check-fill"></i> Appointments
    </a>
    @if(in_array($role, ['admin','doctor']))
    <a href="{{ route('consultants.index') }}" class="nav-link {{ request()->routeIs('consultants*') ? 'active' : '' }}">
      <i class="bi bi-person-badge-fill"></i> Consultants
    </a>
    @endif
    @if(in_array($role, ['admin','doctor']))
    <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks*') ? 'active' : '' }}">
      <i class="bi bi-check2-square"></i> Tasks
    </a>
    @endif
    @endif

    {{-- BILLING: admin, billing, reception, accountant --}}
    @if(in_array($role, ['admin','billing','reception','accountant']))
    <div class="nav-label">Billing</div>
    @if(in_array($role, ['admin','billing','reception']))
    <a href="{{ route('bills.create') }}" class="nav-link {{ request()->routeIs('bills.create') ? 'active' : '' }}">
      <i class="bi bi-plus-circle-fill"></i> Create Bill
    </a>
    @endif
    <a href="{{ route('bills.index') }}" class="nav-link {{ request()->routeIs('bills.index') || request()->routeIs('bills.show') ? 'active' : '' }}">
      <i class="bi bi-receipt-cutoff"></i> All Bills
    </a>
    <a href="{{ route('billing.today') }}" class="nav-link {{ request()->routeIs('billing.today') ? 'active' : '' }}">
      <i class="bi bi-cash-stack"></i> Today's Collections
    </a>
    @if(in_array($role, ['admin','billing']))
    <a href="{{ route('packages.index') }}" class="nav-link {{ request()->routeIs('packages*') ? 'active' : '' }}">
      <i class="bi bi-box-fill"></i> IVF Packages
    </a>
    @endif
    @endif

    {{-- ACCOUNTS: admin, accountant --}}
    @if(in_array($role, ['admin','accountant']))
    <div class="nav-label">Accounts</div>
    <a href="{{ route('accounts.dashboard') }}" class="nav-link {{ request()->routeIs('accounts*') ? 'active' : '' }}">
      <i class="bi bi-bar-chart-line-fill"></i> Accounts
    </a>
    @endif

    {{-- PHARMACY: admin, pharmacy --}}
    @if(in_array($role, ['admin','pharmacy']))
    <div class="nav-label">Pharmacy</div>
    <a href="{{ route('pharmacy.dashboard') }}" class="nav-link {{ request()->routeIs('pharmacy*') ? 'active' : '' }}">
      <i class="bi bi-capsule-pill"></i> Pharmacy
    </a>
    @endif

    {{-- LAB REPORTS: lab, admin, doctor, consultant, billing, reception --}}
    @if(in_array($role, ['lab','admin','doctor','consultant','billing','reception']))
    <div class="nav-label">Laboratory</div>
    @if(in_array($role, ['lab','admin']))
    <a href="{{ route('lab.reports.create') }}" class="nav-link {{ request()->routeIs('lab.reports.create') ? 'active' : '' }}">
      <i class="bi bi-plus-circle-fill"></i> New Sample Entry
    </a>
    @endif
    <a href="{{ route('lab.reports.index') }}" class="nav-link {{ request()->routeIs('lab.reports.index') || request()->routeIs('lab.reports.show') || request()->routeIs('lab.reports.edit') ? 'active' : '' }}">
      <i class="bi bi-clipboard2-pulse-fill"></i> All Lab Reports
      @php $labReady = \App\Models\LabReport::where('status','ready')->count(); @endphp
      @if($labReady > 0)
      <span class="badge-pill ms-auto" style="background:#7c3aed;color:#fff;">{{ $labReady }}</span>
      @endif
    </a>
    @if(in_array($role, ['admin','billing','reception']))
    <a href="{{ route('lab.reports.ready') }}" class="nav-link {{ request()->routeIs('lab.reports.ready') ? 'active' : '' }}">
      <i class="bi bi-printer-fill"></i> Ready to Print
      @if($labReady > 0)
      <span class="badge-pill ms-auto" style="background:#16a34a;color:#fff;">{{ $labReady }}</span>
      @endif
    </a>
    @endif
    @endif

    {{-- MASTERS: admin, billing --}}
    @if(in_array($role, ['admin','billing']))
    <div class="nav-label">Masters</div>
    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services*') ? 'active' : '' }}">
      <i class="bi bi-list-check"></i> Service Master
    </a>
    <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff*') ? 'active' : '' }}">
      <i class="bi bi-person-badge-fill"></i> Staff Master
    </a>
    @endif

    {{-- ADMIN ONLY --}}
    @if($role === 'admin')
    <div class="nav-label">Admin</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-shield-fill-check"></i> Admin Panel
    </a>
    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <i class="bi bi-people-fill"></i> System Users
    </a>
    <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
      <i class="bi bi-building-fill"></i> Departments
    </a>
    @endif
  </nav>
</div>

<div id="topbar">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle"><i class="bi bi-list fs-5"></i></button>
    <span class="page-title">@yield('page-title','Dashboard')</span>
  </div>
  <div class="d-flex align-items-center gap-3">
    <small class="text-muted d-none d-md-block">{{ now()->format('D, d M Y') }}</small>
    <div class="dropdown">
      <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
        <div style="width:30px;height:30px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;">
          {{ strtoupper(substr(auth()->user()->name,0,2)) }}
        </div>
        <span class="d-none d-md-inline" style="font-size:.82rem;">{{ auth()->user()->name }}</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:10px;">
        <li><span class="dropdown-item-text text-muted" style="font-size:.78rem;">{{ ucfirst(auth()->user()->role) }}</span></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>

<div id="main-content">
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Please fix the errors:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click',()=>{
  document.getElementById('sidebar').classList.toggle('open');
});
</script>
@stack('scripts')
</body>
</html>
