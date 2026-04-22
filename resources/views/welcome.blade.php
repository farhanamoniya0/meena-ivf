{{-- Meena IVF — Landing Page --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meena IVF &amp; Fertility Care — Hospital Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    :root{--teal:#0b6e4f;--teal-light:#1a9e74;--teal-pale:#e8f5f0;--purple:#5b21b6;--purple-light:#7c3aed;--dark:#0f172a;--mid:#374151;--light:#f8fafc;}
    html{scroll-behavior:smooth;}
    body{font-family:'Inter',sans-serif;color:var(--dark);background:#fff;overflow-x:hidden;}
    /* NAVBAR */
    .navbar{position:fixed;top:0;left:0;right:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;padding:14px 48px;background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border-bottom:1px solid rgba(11,110,79,.1);transition:box-shadow .3s;}
    .navbar.scrolled{box-shadow:0 2px 24px rgba(0,0,0,.08);}
    .nav-brand{display:flex;align-items:center;gap:12px;text-decoration:none;}
    .logo-circle{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--teal-light));display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;font-weight:800;box-shadow:0 4px 12px rgba(11,110,79,.35);}
    .brand-name{font-size:15px;font-weight:700;color:var(--teal);display:block;}
    .brand-sub{font-size:10.5px;color:#6b7280;letter-spacing:.3px;display:block;}
    .nav-links{display:flex;align-items:center;gap:28px;list-style:none;}
    .nav-links a{font-size:13.5px;font-weight:500;color:var(--mid);text-decoration:none;transition:color .2s;}
    .nav-links a:hover{color:var(--teal);}
    .btn-login{background:linear-gradient(135deg,var(--teal),var(--teal-light));color:#fff!important;padding:9px 24px;border-radius:50px;font-size:13.5px;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(11,110,79,.3);transition:transform .2s,box-shadow .2s;display:inline-flex;align-items:center;gap:6px;}
    .btn-login:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(11,110,79,.4);}
    /* HERO */
    .hero{min-height:100vh;display:flex;align-items:center;background:linear-gradient(135deg,#f0fdf6 0%,#e8f5f0 40%,#faf5ff 100%);padding-top:80px;position:relative;overflow:hidden;}
    .hero::before{content:'';position:absolute;top:-200px;right:-200px;width:700px;height:700px;border-radius:50%;background:radial-gradient(circle,rgba(11,110,79,.07) 0%,transparent 70%);}
    .hero::after{content:'';position:absolute;bottom:-150px;left:-150px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.05) 0%,transparent 70%);}
    .hero-inner{max-width:1200px;margin:0 auto;padding:80px 48px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;width:100%;}
    .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(11,110,79,.1);color:var(--teal);padding:6px 16px;border-radius:50px;font-size:12.5px;font-weight:600;margin-bottom:20px;border:1px solid rgba(11,110,79,.2);}
    .hero h1{font-family:'Playfair Display',serif;font-size:50px;line-height:1.15;color:var(--dark);margin-bottom:16px;}
    .hero h1 span{color:var(--teal);}
    .hero-sub{font-size:16px;color:#6b7280;line-height:1.75;margin-bottom:36px;max-width:480px;}
    .hero-cta{display:flex;gap:14px;flex-wrap:wrap;align-items:center;}
    .btn-hero-primary{background:linear-gradient(135deg,var(--teal),var(--teal-light));color:#fff;padding:14px 32px;border-radius:50px;font-size:15px;font-weight:600;text-decoration:none;box-shadow:0 6px 20px rgba(11,110,79,.35);display:inline-flex;align-items:center;gap:8px;transition:transform .2s,box-shadow .2s;}
    .btn-hero-primary:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(11,110,79,.4);color:#fff;}
    .btn-hero-secondary{background:#fff;color:var(--teal);padding:13px 28px;border-radius:50px;font-size:15px;font-weight:600;text-decoration:none;border:2px solid var(--teal);display:inline-flex;align-items:center;gap:8px;transition:all .2s;}
    .btn-hero-secondary:hover{background:var(--teal);color:#fff;}
    .hero-stats{display:flex;gap:32px;margin-top:44px;padding-top:36px;border-top:1px solid rgba(0,0,0,.07);}
    .hero-stat .number{font-size:26px;font-weight:800;color:var(--teal);}
    .hero-stat .label{font-size:12px;color:#9ca3af;margin-top:2px;}
    /* Hero visual card */
    .hero-visual{position:relative;}
    .hero-card-main{background:#fff;border-radius:20px;padding:28px;box-shadow:0 20px 60px rgba(0,0,0,.12);border:1px solid rgba(11,110,79,.08);}
    .card-header-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
    .hc-title{font-size:13px;font-weight:700;color:var(--dark);}
    .hc-date{font-size:11px;color:#9ca3af;}
    .stat-row{display:flex;gap:12px;margin-bottom:16px;}
    .mini-stat{flex:1;background:var(--teal-pale);border-radius:12px;padding:14px 16px;text-align:center;}
    .mini-stat.purple{background:#f3e8ff;}
    .mini-stat.gold{background:#fefce8;}
    .mini-stat .ms-val{font-size:22px;font-weight:800;color:var(--teal);}
    .mini-stat.purple .ms-val{color:var(--purple-light);}
    .mini-stat.gold .ms-val{color:#b45309;}
    .mini-stat .ms-lbl{font-size:10px;color:#6b7280;margin-top:2px;}
    .prog-item{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;}
    .prog-label{font-size:12px;color:var(--mid);}
    .prog-bar-wrap{flex:1;height:6px;background:#e5e7eb;border-radius:99px;margin:0 10px;}
    .prog-bar{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--teal),var(--teal-light));}
    .prog-val{font-size:11px;font-weight:600;color:var(--teal);}
    .float-badge{position:absolute;background:#fff;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,.12);padding:12px 16px;display:flex;align-items:center;gap:10px;border:1px solid rgba(0,0,0,.06);}
    .float-badge.top-right{top:-24px;right:-28px;}
    .float-badge.bottom-left{bottom:-24px;left:-28px;}
    .fb-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;}
    .fb-val{font-size:14px;font-weight:700;color:var(--dark);}
    .fb-sub{font-size:10.5px;color:#9ca3af;}
    /* FEATURES */
    .features{padding:96px 48px;background:#fff;}
    .section-wrap{max-width:1200px;margin:0 auto;}
    .section-label{display:inline-block;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--teal);margin-bottom:12px;}
    .section-title{font-family:'Playfair Display',serif;font-size:38px;color:var(--dark);margin-bottom:14px;line-height:1.2;}
    .section-sub{font-size:16px;color:#6b7280;max-width:520px;line-height:1.7;}
    .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;margin-top:56px;}
    .feat-card{background:var(--light);border-radius:18px;padding:32px 28px;border:1.5px solid #f0f0f0;transition:all .25s;cursor:default;}
    .feat-card:hover{transform:translateY(-6px);border-color:rgba(11,110,79,.2);box-shadow:0 16px 48px rgba(11,110,79,.1);}
    .feat-icon{width:52px;height:52px;border-radius:14px;margin-bottom:20px;display:flex;align-items:center;justify-content:center;font-size:22px;}
    .feat-card h3{font-size:16.5px;font-weight:700;color:var(--dark);margin-bottom:10px;}
    .feat-card p{font-size:13.5px;color:#6b7280;line-height:1.7;}
    .feat-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:16px;}
    .feat-tag{font-size:11px;font-weight:600;padding:3px 10px;border-radius:50px;background:rgba(11,110,79,.08);color:var(--teal);}
    /* WORKFLOW */
    .workflow{padding:96px 48px;background:linear-gradient(135deg,#f0fdf6,#faf5ff);}
    .steps{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:56px;position:relative;}
    .steps::before{content:'';position:absolute;top:32px;left:12.5%;right:12.5%;height:2px;background:linear-gradient(90deg,var(--teal),var(--purple-light));z-index:0;}
    .step{text-align:center;position:relative;z-index:1;padding:0 16px;}
    .step-num{width:64px;height:64px;border-radius:50%;margin:0 auto 18px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;background:linear-gradient(135deg,var(--teal),var(--teal-light));box-shadow:0 6px 20px rgba(11,110,79,.35);border:3px solid #fff;}
    .step:nth-child(2) .step-num{background:linear-gradient(135deg,#0891b2,#06b6d4);box-shadow:0 6px 20px rgba(6,182,212,.35);}
    .step:nth-child(3) .step-num{background:linear-gradient(135deg,#7c3aed,#9333ea);box-shadow:0 6px 20px rgba(124,58,237,.35);}
    .step:nth-child(4) .step-num{background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 6px 20px rgba(217,119,6,.35);}
    .step h4{font-size:14.5px;font-weight:700;color:var(--dark);margin-bottom:8px;}
    .step p{font-size:12.5px;color:#6b7280;line-height:1.65;}
    /* MODULES */
    .modules{padding:64px 48px;background:#fff;border-top:1px solid #f0f0f0;}
    .modules-label{text-align:center;font-size:13px;font-weight:600;color:#9ca3af;letter-spacing:2px;text-transform:uppercase;margin-bottom:36px;}
    .modules-row{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;}
    .mod-chip{display:flex;align-items:center;gap:8px;background:var(--light);border:1.5px solid #e5e7eb;border-radius:50px;padding:10px 20px;font-size:13px;font-weight:600;color:var(--mid);transition:all .2s;cursor:default;}
    .mod-chip:hover{border-color:var(--teal);color:var(--teal);background:var(--teal-pale);}
    .mod-chip i{font-size:15px;}
    /* HIGHLIGHT */
    .highlight{padding:96px 48px;background:linear-gradient(135deg,var(--teal) 0%,#0d8a60 50%,#1a9e74 100%);text-align:center;color:#fff;}
    .highlight h2{font-family:'Playfair Display',serif;font-size:40px;margin-bottom:16px;color:#fff;}
    .highlight p{font-size:16.5px;opacity:.88;max-width:580px;margin:0 auto 40px;line-height:1.75;}
    .btn-white{background:#fff;color:var(--teal);padding:14px 36px;border-radius:50px;font-size:15px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 6px 20px rgba(0,0,0,.15);transition:transform .2s;}
    .btn-white:hover{transform:translateY(-2px);color:var(--teal);}
    .highlight-stats{display:flex;justify-content:center;gap:64px;margin-top:56px;flex-wrap:wrap;border-top:1px solid rgba(255,255,255,.2);padding-top:48px;}
    .hl-stat .val{font-size:42px;font-weight:800;color:#fff;}
    .hl-stat .lbl{font-size:13px;opacity:.75;margin-top:4px;letter-spacing:.5px;}
    /* FOOTER */
    footer{background:var(--dark);color:#fff;padding:56px 48px 32px;}
    .footer-inner{max-width:1100px;margin:0 auto;}
    .footer-top{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:48px;margin-bottom:48px;}
    .footer-brand-name{font-size:17px;font-weight:700;color:#fff;margin-bottom:8px;}
    .footer-brand p{font-size:13px;color:#9ca3af;line-height:1.7;margin-top:4px;}
    .footer-col h5{font-size:13px;font-weight:700;color:#e5e7eb;margin-bottom:16px;letter-spacing:.5px;text-transform:uppercase;}
    .footer-col ul{list-style:none;}
    .footer-col li{margin-bottom:10px;}
    .footer-col a{font-size:13px;color:#9ca3af;text-decoration:none;transition:color .2s;}
    .footer-col a:hover{color:#fff;}
    .footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:24px;display:flex;justify-content:space-between;align-items:center;font-size:12.5px;color:#6b7280;}
    /* Responsive */
    @media(max-width:900px){
      .navbar{padding:12px 20px;}
      .hero-inner{grid-template-columns:1fr;padding:60px 24px 48px;gap:40px;}
      .hero-visual{display:none;}
      .hero h1{font-size:36px;}
      .features-grid{grid-template-columns:1fr 1fr;}
      .features,.workflow,.highlight,.modules{padding:64px 24px;}
      .steps{grid-template-columns:1fr 1fr;gap:32px;}
      .steps::before{display:none;}
      .footer-top{grid-template-columns:1fr 1fr;}
    }
    @media(max-width:600px){
      .features-grid{grid-template-columns:1fr;}
      .hero-stats{flex-direction:column;gap:16px;}
      .highlight-stats{gap:32px;}
      .footer-top{grid-template-columns:1fr;}
      .footer-bottom{flex-direction:column;gap:8px;text-align:center;}
      .nav-links{display:none;}
    }
  </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar" id="mainNav">
  <a href="{{ route('home') }}" class="nav-brand">
    <div class="logo-circle"><i class="bi bi-heart-pulse-fill"></i></div>
    <div>
      <span class="brand-name">Meena IVF Center</span>
      <span class="brand-sub">Fertility Care Management</span>
    </div>
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#workflow">How It Works</a></li>
    <li><a href="#modules">Modules</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>
  @auth
    <a href="{{ route('dashboard') }}" class="btn-login"><i class="bi bi-speedometer2"></i> Dashboard</a>
  @else
    <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Staff Login</a>
  @endauth
</nav>

<!-- ══ HERO ══ -->
<section class="hero">
  <div class="hero-inner">
    <!-- Left -->
    <div>
      <div class="hero-badge"><i class="bi bi-shield-check-fill"></i> Trusted Healthcare Management</div>
      <h1>Smart Care for<br><span>IVF &amp; Fertility</span><br>Excellence</h1>
      <p class="hero-sub">A complete hospital management system purpose-built for IVF &amp; fertility clinics — from patient registration to billing, packages, pharmacy, and beyond.</p>
      <div class="hero-cta">
        @auth
          <a href="{{ route('dashboard') }}" class="btn-hero-primary"><i class="bi bi-speedometer2"></i> Go to Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="btn-hero-primary"><i class="bi bi-box-arrow-in-right"></i> Login to System</a>
        @endauth
        <a href="#features" class="btn-hero-secondary"><i class="bi bi-play-circle"></i> Explore Features</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat"><div class="number">360°</div><div class="label">Patient Management</div></div>
        <div class="hero-stat"><div class="number">Real-time</div><div class="label">Billing & Collections</div></div>
        <div class="hero-stat"><div class="number">IVF</div><div class="label">Package Tracking</div></div>
      </div>
    </div>
    <!-- Right: Dashboard Preview Card -->
    <div class="hero-visual">
      <div class="hero-card-main">
        <div class="card-header-row">
          <span class="hc-title">📊 Today's Dashboard</span>
          <span class="hc-date">{{ now()->format('d M Y') }}</span>
        </div>
        <div class="stat-row">
          <div class="mini-stat">
            <div class="ms-val">12</div>
            <div class="ms-lbl">New Patients</div>
          </div>
          <div class="mini-stat purple">
            <div class="ms-val">28</div>
            <div class="ms-lbl">Appointments</div>
          </div>
          <div class="mini-stat gold">
            <div class="ms-val">৳84k</div>
            <div class="ms-lbl">Collection</div>
          </div>
        </div>
        <div>
          <div class="prog-item">
            <span class="prog-label">IVF Packages Active</span>
            <div class="prog-bar-wrap"><div class="prog-bar" style="width:72%"></div></div>
            <span class="prog-val">72%</span>
          </div>
          <div class="prog-item">
            <span class="prog-label">Bills Collected</span>
            <div class="prog-bar-wrap"><div class="prog-bar" style="width:89%"></div></div>
            <span class="prog-val">89%</span>
          </div>
          <div class="prog-item">
            <span class="prog-label">Pharmacy Stock OK</span>
            <div class="prog-bar-wrap"><div class="prog-bar" style="width:65%"></div></div>
            <span class="prog-val">65%</span>
          </div>
        </div>
      </div>
      <div class="float-badge top-right">
        <div class="fb-icon" style="background:#e8f5f0;color:var(--teal);"><i class="bi bi-calendar2-check-fill"></i></div>
        <div>
          <div class="fb-val">Appointment</div>
          <div class="fb-sub">Next: 10:30 AM</div>
        </div>
      </div>
      <div class="float-badge bottom-left">
        <div class="fb-icon" style="background:#f3e8ff;color:var(--purple-light);"><i class="bi bi-receipt-cutoff"></i></div>
        <div>
          <div class="fb-val">Bill #BL-1042</div>
          <div class="fb-sub">Just Paid ৳3,200</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ MODULES STRIP ══ -->
<section class="modules" id="modules">
  <div class="section-wrap">
    <div class="modules-label">All-in-One Platform — Everything You Need</div>
    <div class="modules-row">
      <div class="mod-chip"><i class="bi bi-people-fill" style="color:var(--teal)"></i> Patient Registry</div>
      <div class="mod-chip"><i class="bi bi-calendar3" style="color:#0891b2"></i> Appointments</div>
      <div class="mod-chip"><i class="bi bi-receipt-cutoff" style="color:var(--purple-light)"></i> OP Billing</div>
      <div class="mod-chip"><i class="bi bi-heart-pulse-fill" style="color:#e11d48"></i> IVF Packages</div>
      <div class="mod-chip"><i class="bi bi-capsule-pill" style="color:#16a34a"></i> Pharmacy</div>
      <div class="mod-chip"><i class="bi bi-piggy-bank-fill" style="color:#b45309"></i> Advance Payments</div>
      <div class="mod-chip"><i class="bi bi-person-badge" style="color:#7c3aed"></i> Consultants</div>
      <div class="mod-chip"><i class="bi bi-bar-chart-line-fill" style="color:#0891b2"></i> Reports</div>
      <div class="mod-chip"><i class="bi bi-check2-circle" style="color:var(--teal)"></i> Tasks</div>
      <div class="mod-chip"><i class="bi bi-shield-lock-fill" style="color:var(--mid)"></i> Role Management</div>
    </div>
  </div>
</section>

<!-- ══ FEATURES ══ -->
<section class="features" id="features">
  <div class="section-wrap">
    <div>
      <span class="section-label">Core Features</span>
      <h2 class="section-title">Everything a Fertility Clinic Needs</h2>
      <p class="section-sub">Purpose-built modules designed for the unique workflows of IVF &amp; fertility care centers.</p>
    </div>
    <div class="features-grid">
      <div class="feat-card">
        <div class="feat-icon" style="background:#e8f5f0;color:var(--teal);"><i class="bi bi-people-fill"></i></div>
        <h3>Patient Management</h3>
        <p>Complete patient profiles with medical history, UHID, photos, registration type, and consultant assignments. Searchable and filterable.</p>
        <div class="feat-tags"><span class="feat-tag">Registration</span><span class="feat-tag">Profiles</span><span class="feat-tag">History</span></div>
      </div>
      <div class="feat-card">
        <div class="feat-icon" style="background:#ede9fe;color:var(--purple-light);"><i class="bi bi-calendar2-check-fill"></i></div>
        <h3>Appointment Scheduling</h3>
        <p>Book, track, and manage patient appointments with consultant-wise filtering, status tracking, and daily views.</p>
        <div class="feat-tags"><span class="feat-tag" style="background:#ede9fe;color:var(--purple-light);">Scheduling</span><span class="feat-tag" style="background:#ede9fe;color:var(--purple-light);">Consultants</span></div>
      </div>
      <div class="feat-card">
        <div class="feat-icon" style="background:#fef9c3;color:#b45309;"><i class="bi bi-receipt-cutoff"></i></div>
        <h3>Smart Billing</h3>
        <p>Create itemized OP bills with services, custom charges, discounts, multiple payment methods, and printable receipts.</p>
        <div class="feat-tags"><span class="feat-tag" style="background:#fef9c3;color:#b45309;">Cash</span><span class="feat-tag" style="background:#fef9c3;color:#b45309;">bKash</span><span class="feat-tag" style="background:#fef9c3;color:#b45309;">Bank</span></div>
      </div>
      <div class="feat-card">
        <div class="feat-icon" style="background:#fee2e2;color:#e11d48;"><i class="bi bi-heart-pulse-fill"></i></div>
        <h3>IVF Package System</h3>
        <p>Assign IVF packages to patients, track instalment payments, advance credits, remaining balances, and completion status.</p>
        <div class="feat-tags"><span class="feat-tag" style="background:#fee2e2;color:#e11d48;">Packages</span><span class="feat-tag" style="background:#fee2e2;color:#e11d48;">Instalments</span></div>
      </div>
      <div class="feat-card">
        <div class="feat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-capsule-pill"></i></div>
        <h3>Pharmacy Management</h3>
        <p>Manage medicines, batches, stock levels, expiry tracking, and internal requisitions with low-stock alerts.</p>
        <div class="feat-tags"><span class="feat-tag" style="background:#dcfce7;color:#16a34a;">Inventory</span><span class="feat-tag" style="background:#dcfce7;color:#16a34a;">Expiry</span></div>
      </div>
      <div class="feat-card">
        <div class="feat-icon" style="background:#e0f2fe;color:#0891b2;"><i class="bi bi-piggy-bank-fill"></i></div>
        <h3>Advance Payment Credit</h3>
        <p>Patients can pay advance amounts which are credited to their balance. Future bills can be instantly adjusted against this credit.</p>
        <div class="feat-tags"><span class="feat-tag" style="background:#e0f2fe;color:#0891b2;">Advance</span><span class="feat-tag" style="background:#e0f2fe;color:#0891b2;">Auto-Adjust</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ══ WORKFLOW ══ -->
<section class="workflow" id="workflow">
  <div class="section-wrap">
    <div style="text-align:center;">
      <span class="section-label">Workflow</span>
      <h2 class="section-title">How the System Works</h2>
      <p class="section-sub" style="margin:0 auto;">From patient walk-in to complete billing — a seamless end-to-end journey.</p>
    </div>
    <div class="steps">
      <div class="step">
        <div class="step-num"><i class="bi bi-person-plus-fill"></i></div>
        <h4>Register Patient</h4>
        <p>Create a complete patient profile with photo, medical history, and assign a unique UHID instantly.</p>
      </div>
      <div class="step">
        <div class="step-num"><i class="bi bi-calendar-check-fill"></i></div>
        <h4>Book Appointment</h4>
        <p>Schedule consultations, assign doctors, and track appointment status in real time.</p>
      </div>
      <div class="step">
        <div class="step-num"><i class="bi bi-clipboard2-pulse-fill"></i></div>
        <h4>Assign Package / Services</h4>
        <p>Link IVF packages or individual diagnostic services. Manage advance payments and instalment tracking.</p>
      </div>
      <div class="step">
        <div class="step-num"><i class="bi bi-cash-coin"></i></div>
        <h4>Bill &amp; Collect</h4>
        <p>Generate itemized bills, accept payments via cash, bank, bKash, or advance credit, and print receipts.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ HIGHLIGHT / CTA ══ -->
<section class="highlight">
  <div style="max-width:700px;margin:0 auto;">
    <span style="display:inline-block;background:rgba(255,255,255,.15);color:#fff;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 16px;border-radius:50px;margin-bottom:20px;border:1px solid rgba(255,255,255,.25);">Ready to Get Started?</span>
    <h2>Manage Your Entire Clinic<br>From One Powerful System</h2>
    <p>Meena IVF &amp; Fertility Care Management System gives your staff everything they need — all in one secure, fast, and easy-to-use platform.</p>
    <a href="{{ route('login') }}" class="btn-white"><i class="bi bi-box-arrow-in-right"></i> Access the System</a>
    <div class="highlight-stats">
      <div class="hl-stat"><div class="val">360°</div><div class="lbl">Patient Coverage</div></div>
      <div class="hl-stat"><div class="val">10+</div><div class="lbl">Integrated Modules</div></div>
      <div class="hl-stat"><div class="val">Real-time</div><div class="lbl">Billing & Reports</div></div>
      <div class="hl-stat"><div class="val">Secure</div><div class="lbl">Role-based Access</div></div>
    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer id="contact">
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div class="logo-circle" style="width:36px;height:36px;font-size:14px;"><i class="bi bi-heart-pulse-fill"></i></div>
          <div class="footer-brand-name">Meena IVF Center</div>
        </div>
        <p>Meena IVF &amp; Fertility Care Limited<br>Block - K, Road - 22, House - 11, Banani<br>Dhaka, Bangladesh - 1213</p>
        <p style="margin-top:12px;">📞 9611678979 &nbsp;|&nbsp; 131825507<br>✉️ meenaivffertility@gmail.com</p>
      </div>
      <div class="footer-col">
        <h5>Patient Services</h5>
        <ul>
          <li><a href="#">Patient Registration</a></li>
          <li><a href="#">Appointments</a></li>
          <li><a href="#">IVF Packages</a></li>
          <li><a href="#">Billing</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>System Modules</h5>
        <ul>
          <li><a href="#">Dashboard</a></li>
          <li><a href="#">Pharmacy</a></li>
          <li><a href="#">Consultants</a></li>
          <li><a href="#">Reports</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Quick Access</h5>
        <ul>
          <li><a href="{{ route('login') }}">Staff Login</a></li>
          @auth
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          @endauth
          <li><a href="#">System Help</a></li>
          <li><a href="#">Admin Panel</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; {{ date('Y') }} Meena IVF &amp; Fertility Care Limited. All rights reserved.</span>
      <span>Hospital Management System &mdash; Built with &hearts; for better healthcare</span>
    </div>
  </div>
</footer>

<script>
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 20);
  });
</script>
</body>
</html>
