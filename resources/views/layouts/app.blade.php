<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'শ্যালো ম্যানেজার') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-bg: #1a3a5c;
            --topbar-h: 56px;
            --bottomnav-h: 60px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: #f0f4f8;
            margin: 0;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease;
        }
        .sidebar .brand {
            background: #0f2540;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.1);
            min-height: var(--topbar-h);
            display: flex;
            align-items: center;
        }
        .sidebar nav { overflow-y: auto; flex: 1; padding-bottom: 1rem; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: .65rem 1rem;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: .93rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { background: rgba(255,255,255,.13); color: #fff; }
        .sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,.4);
            padding: .8rem 1rem .25rem;
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1rem;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .topbar .page-title {
            font-weight: 600;
            font-size: .95rem;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Page body ── */
        .page-body { padding: 1rem; flex: 1; }

        /* ── Stat cards ── */
        .stat-card { border: none; border-radius: 12px; }
        .stat-card .card-body { padding: 1rem; }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* ── Tables ── */
        .table th {
            font-weight: 600;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #64748b;
            white-space: nowrap;
        }
        .table td { font-size: .88rem; vertical-align: middle; }
        .btn-action { padding: .2rem .45rem; font-size: .78rem; }

        /* ── Bottom nav (mobile only) ── */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: var(--bottomnav-h);
            background: #fff;
            border-top: 1px solid #e2e8f0;
            z-index: 1030;
        }
        .bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: .65rem;
            gap: 2px;
            padding: .25rem 0;
            transition: color .15s;
        }
        .bottom-nav a i { font-size: 1.3rem; }
        .bottom-nav a.active,
        .bottom-nav a:hover { color: #1a3a5c; }
        .bottom-nav a.add-btn {
            background: #1a3a5c;
            color: #fff;
            border-radius: 50%;
            width: 48px; height: 48px;
            margin-top: -20px;
            flex: unset;
            box-shadow: 0 4px 12px rgba(26,58,92,.35);
        }
        .bottom-nav a.add-btn i { font-size: 1.5rem; }

        /* ── Responsive ── */
        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding-bottom: var(--bottomnav-h); }
            .bottom-nav { display: flex; align-items: center; justify-content: space-around; padding: 0 .5rem; }
            .topbar .btn-new { display: none; }
            .page-body { padding: .75rem; }
            .stat-card .card-body { padding: .75rem; }
            h4.fw-bold { font-size: 1.1rem; }
        }
        @media (min-width: 768px) {
            .sidebar-toggle { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- Sidebar --}}
<div class="sidebar" id="sidebar">
    <div class="brand">
        <i class="bi bi-droplet-fill text-info me-2"></i> শ্যালো ম্যানেজার
    </div>
    <nav>
        <div class="nav-section">মূল মেনু</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> ড্যাশবোর্ড
        </a>
        <a href="{{ route('pump-owner.edit') }}" class="nav-link {{ request()->routeIs('pump-owner.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> শ্যালো মালিকের প্রোফাইল
        </a>

        <div class="nav-section">ব্যবস্থাপনা</div>
        <a href="{{ route('farmers.index') }}" class="nav-link {{ request()->routeIs('farmers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> কৃষক তালিকা
        </a>
        <a href="{{ route('water-entries.index') }}" class="nav-link {{ request()->routeIs('water-entries.*') ? 'active' : '' }}">
            <i class="bi bi-droplet-half"></i> পানি সরবরাহ এন্ট্রি
        </a>
        <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> পেমেন্ট হিস্ট্রি
        </a>

        <div class="nav-section">রিপোর্ট</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> রিপোর্ট
        </a>
        <a href="{{ route('import.index') }}" class="nav-link {{ request()->routeIs('import.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-excel"></i> এক্সেল ইমপোর্ট
        </a>

        @if(auth()->user()->is_admin)
        <div class="nav-section">অ্যাডমিন</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
           style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.25);">
            <i class="bi bi-shield-lock-fill" style="color:#a5b4fc;"></i>
            <span style="color:#a5b4fc;">অ্যাডমিন প্যানেল</span>
        </a>
        @endif
    </nav>
    <div class="p-3 border-top border-white border-opacity-10">
        <div class="text-white-50 small mb-1">
            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
        </div>
        @if(!auth()->user()->is_admin)
            @php $days = auth()->user()->daysRemaining(); @endphp
            @if($days <= 7)
                <div class="mb-2">
                    <span class="badge bg-danger w-100 py-1">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ $days }} দিন বাকি!
                    </span>
                </div>
            @elseif($days <= 30)
                <div class="mb-2">
                    <span class="badge bg-warning text-dark w-100 py-1">
                        <i class="bi bi-clock me-1"></i>{{ $days }} দিন বাকি
                    </span>
                </div>
            @else
                <div class="text-white-50 mb-2" style="font-size:.7rem;">
                    <i class="bi bi-clock me-1"></i>মেয়াদ: {{ auth()->user()->expires_at->format('d/m/Y') }}
                </div>
            @endif
        @else
            <div class="text-white-50 mb-2" style="font-size:.7rem;">
                <i class="bi bi-shield-check me-1"></i>Admin
            </div>
        @endif
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i>লগআউট
            </button>
        </form>
    </div>
</div>

{{-- Main --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <button class="btn btn-light btn-sm sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="page-title">@yield('title', 'ড্যাশবোর্ড')</span>
        <a href="{{ route('water-entries.create') }}" class="btn btn-primary btn-sm btn-new ms-auto">
            <i class="bi bi-plus-lg"></i> নতুন এন্ট্রি
        </a>
    </div>

    {{-- Alerts --}}
    <div class="page-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- Bottom Nav (mobile) --}}
<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span>হোম</span>
    </a>
    <a href="{{ route('farmers.index') }}" class="{{ request()->routeIs('farmers.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i><span>কৃষক</span>
    </a>
    <a href="{{ route('water-entries.create') }}" class="add-btn">
        <i class="bi bi-plus-lg"></i>
    </a>
    <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i><span>পেমেন্ট</span>
    </a>
    <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i><span>রিপোর্ট</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle  = document.getElementById('sidebarToggle');

    function openSidebar()  { sidebar.classList.add('open');  overlay.classList.add('show'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); }

    toggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);

    // close sidebar on nav-link click (mobile)
    sidebar.querySelectorAll('.nav-link').forEach(l => l.addEventListener('click', () => {
        if (window.innerWidth < 768) closeSidebar();
    }));
</script>
@stack('scripts')
</body>
</html>
