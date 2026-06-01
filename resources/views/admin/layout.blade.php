<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'অ্যাডমিন প্যানেল') — Shallow Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-sidebar: 250px;
            --admin-dark: #0f172a;
            --admin-accent: #6366f1;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Hind Siliguri', sans-serif; background: #f1f5f9; margin: 0; }

        /* Sidebar */
        .admin-sidebar {
            width: var(--admin-sidebar);
            min-height: 100vh;
            background: var(--admin-dark);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        .admin-sidebar .brand {
            padding: 1.25rem 1rem;
            background: #020617;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .admin-sidebar .brand h6 {
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
            margin: 0;
        }
        .admin-sidebar .brand small {
            color: #6366f1;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .admin-sidebar nav { flex: 1; padding: .5rem 0; }
        .admin-sidebar .nav-label {
            font-size: .65rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
            padding: .75rem 1rem .25rem;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: .6rem 1rem;
            margin: 1px .5rem;
            border-radius: 8px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: all .15s;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: rgba(99,102,241,.2);
            color: #a5b4fc;
        }
        .admin-sidebar .nav-link.active { color: #fff; background: rgba(99,102,241,.35); }
        .admin-sidebar .user-section {
            padding: .75rem 1rem;
            border-top: 1px solid rgba(255,255,255,.07);
            background: rgba(0,0,0,.2);
        }

        /* Main */
        .admin-main {
            margin-left: var(--admin-sidebar);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .admin-topbar .breadcrumb { margin: 0; font-size: .82rem; }
        .admin-body { padding: 1.5rem; flex: 1; }

        /* Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* Table */
        .table th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
        }
        .table td { font-size: .875rem; vertical-align: middle; }

        /* Status badges */
        .badge-active   { background: #dcfce7; color: #166534; }
        .badge-expired  { background: #fee2e2; color: #991b1b; }
        .badge-expiring { background: #fef3c7; color: #92400e; }

        /* Page card */
        .page-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .page-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }
        .page-card-body { padding: 1.25rem; }

        @media (max-width: 767.98px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform .25s; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .admin-body { padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="admin-sidebar" id="adminSidebar">
    <div class="brand">
        <small><i class="bi bi-shield-lock-fill me-1"></i>Super Admin</small>
        <h6 class="mt-1"><i class="bi bi-droplet-fill text-primary me-1"></i>Shallow Manager</h6>
    </div>
    <nav>
        <div class="nav-label">প্যানেল</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> ড্যাশবোর্ড
        </a>

        <div class="nav-label">ব্যবস্থাপনা</div>
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> শ্যালো মালিক
        </a>
        <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus-fill"></i> নতুন মালিক যোগ
        </a>

        <div class="nav-label">সিস্টেম</div>
        <a href="{{ route('dashboard') }}" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> মূল অ্যাপ দেখুন
        </a>
    </nav>
    <div class="user-section">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="bg-indigo-900 rounded-circle d-flex align-items-center justify-content-center"
                 style="width:32px;height:32px;background:rgba(99,102,241,.3);">
                <i class="bi bi-shield-check text-white" style="font-size:.9rem;"></i>
            </div>
            <div>
                <div class="text-white fw-600" style="font-size:.8rem;">{{ auth()->user()->name }}</div>
                <div style="font-size:.68rem;color:rgba(255,255,255,.4);">Super Admin</div>
            </div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(255,255,255,.07);color:rgba(255,255,255,.7);font-size:.8rem;">
                <i class="bi bi-box-arrow-right me-1"></i>লগআউট
            </button>
        </form>
    </div>
</div>

<div class="admin-main">
    <div class="admin-topbar">
        <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">অ্যাডমিন</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge bg-indigo text-white" style="background:#6366f1;font-size:.72rem;padding:.35rem .6rem;">
                <i class="bi bi-shield-lock me-1"></i>Admin Mode
            </span>
        </div>
    </div>

    <div class="admin-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show py-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
