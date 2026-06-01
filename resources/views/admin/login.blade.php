<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Shallow Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Hind Siliguri', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #1e1b4b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.5);
        }
        .login-header {
            background: linear-gradient(135deg, #0f172a, #1e1b4b);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }
        .login-header .shield-icon {
            width: 64px; height: 64px;
            background: rgba(99,102,241,.2);
            border: 2px solid rgba(99,102,241,.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: #a5b4fc;
        }
        .login-header h5 {
            color: #fff;
            font-weight: 700;
            margin: 0 0 .25rem;
        }
        .login-header p {
            color: rgba(255,255,255,.45);
            font-size: .78rem;
            margin: 0;
        }
        .admin-badge {
            display: inline-block;
            background: rgba(99,102,241,.15);
            border: 1px solid rgba(99,102,241,.3);
            color: #a5b4fc;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: .2rem .7rem;
            border-radius: 20px;
            margin-bottom: .75rem;
        }
        .login-body { padding: 1.75rem 2rem 2rem; }
        .form-label { font-weight: 500; font-size: .88rem; color: #374151; }
        .form-control {
            border-radius: 8px;
            border-color: #e5e7eb;
            padding: .6rem .9rem;
            font-size: .9rem;
            font-family: 'Hind Siliguri', sans-serif;
        }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .btn-admin {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .7rem;
            font-size: .95rem;
            font-weight: 600;
            font-family: 'Hind Siliguri', sans-serif;
            width: 100%;
            transition: opacity .2s;
        }
        .btn-admin:hover { opacity: .9; color: #fff; }
        .divider {
            text-align: center;
            margin: 1.25rem 0 1rem;
            position: relative;
            color: #9ca3af;
            font-size: .78rem;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e5e7eb;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .owner-link {
            display: block;
            text-align: center;
            font-size: .82rem;
            color: #6b7280;
            text-decoration: none;
        }
        .owner-link:hover { color: #4f46e5; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="shield-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="admin-badge"><i class="bi bi-stars me-1"></i>Super Admin</div>
        <h5>অ্যাডমিন প্যানেল</h5>
        <p>Shallow Manager Administration</p>
    </div>

    <div class="login-body">
        @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:8px;">
            <i class="bi bi-shield-x me-1"></i>{{ $errors->first() }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:8px;">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Admin ইমেইল</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-shield-check text-indigo" style="color:#6366f1;"></i>
                    </span>
                    <input type="email" name="email"
                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">পাসওয়ার্ড</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="passInput"
                           class="form-control border-start-0 border-end-0 ps-0"
                           placeholder="••••••••" required>
                    <button type="button" class="input-group-text bg-white"
                            onclick="const i=document.getElementById('passInput');i.type=i.type==='password'?'text':'password';this.querySelector('i').className=i.type==='password'?'bi bi-eye':'bi bi-eye-slash';">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:.83rem;">মনে রাখুন</label>
                </div>
            </div>

            <button type="submit" class="btn-admin">
                <i class="bi bi-shield-lock me-2"></i>Admin লগইন
            </button>
        </form>

        <div class="divider">অথবা</div>

        <a href="{{ route('login') }}" class="owner-link">
            <i class="bi bi-person-fill me-1"></i>শ্যালো মালিকদের লগইন পেজ
        </a>
    </div>
</div>

</body>
</html>
