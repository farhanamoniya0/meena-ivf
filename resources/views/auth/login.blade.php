<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Meena IVF</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'Poppins',sans-serif;}
body{background:linear-gradient(135deg,#16082e 0%,#5b21b6 100%);min-height:100vh;display:flex;align-items:center;}
.login-card{border:none;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.3);overflow:hidden;max-width:440px;width:100%;}
.login-header{background:linear-gradient(135deg,#5b21b6,#7c3aed);padding:36px 40px;text-align:center;}
.login-body{padding:36px 40px;background:#fff;}
.form-control{border-radius:10px;padding:12px 16px;font-size:.9rem;border-color:#dee2e6;}
.form-control:focus{border-color:#7c3aed;box-shadow:0 0 0 .2rem rgba(124,58,237,.15);}
.btn-login{background:linear-gradient(135deg,#5b21b6,#7c3aed);border:none;padding:12px;border-radius:10px;font-weight:600;font-size:.9rem;letter-spacing:.5px;}
.btn-login:hover{opacity:.9;}
.input-group-text{border-radius:10px 0 0 10px;background:#f8f9fa;border-color:#dee2e6;}
</style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="login-card">
        <div class="login-header">
          <div style="width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;">🏥</div>
          <h4 class="text-white fw-700 mb-1">Meena IVF Center</h4>
          <p class="text-white-50 mb-0" style="font-size:.85rem;">Management System</p>
        </div>
        <div class="login-body">
          <h5 class="fw-600 mb-1">Welcome Back</h5>
          <p class="text-muted mb-4" style="font-size:.82rem;">Sign in to your account to continue</p>

          @if($errors->any())
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2" style="font-size:.83rem;border-radius:10px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
          </div>
          @endif

          <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-500" style="font-size:.83rem;">Email Address</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus style="border-radius:0 10px 10px 0;">
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-500" style="font-size:.83rem;">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required style="border-radius:0 10px 10px 0;">
              </div>
            </div>
            <div class="mb-4 d-flex align-items-center justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.83rem;">Remember me</label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
              <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
          </form>
          <p class="text-center text-muted mt-4 mb-0" style="font-size:.75rem;">Meena IVF Center &copy; {{ date('Y') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
