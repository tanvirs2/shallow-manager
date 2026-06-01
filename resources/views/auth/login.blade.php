<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন — শ্যালো ম্যানেজার</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2540 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }
        .login-header {
            background: #1a3a5c;
            color: #fff;
            padding: 2rem;
            text-align: center;
        }
        .login-header .icon {
            font-size: 3rem;
            color: #60c5fa;
        }
        .login-header h4 {
            margin: .5rem 0 .25rem;
            font-weight: 700;
        }
        .login-header p {
            margin: 0;
            color: rgba(255,255,255,.65);
            font-size: .9rem;
        }
        .login-body { padding: 2rem; }
        .form-control:focus { border-color: #1a3a5c; box-shadow: 0 0 0 .2rem rgba(26,58,92,.2); }
        .btn-login {
            background: #1a3a5c;
            border: none;
            color: #fff;
            width: 100%;
            padding: .75rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-login:hover { background: #0f2540; color: #fff; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="icon"><i class="bi bi-droplet-fill"></i></div>
        <h4>শ্যালো ম্যানেজার</h4>
        <p>সেচ ব্যবস্থাপনা সিস্টেম</p>
    </div>
    <div class="login-body">
        @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">ইমেইল</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="আপনার ইমেইল"
                           required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">পাসওয়ার্ড</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control"
                           placeholder="পাসওয়ার্ড" required>
                </div>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">লগইন মনে রাখুন</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>লগইন করুন
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
