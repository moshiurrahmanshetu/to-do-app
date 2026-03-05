<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — To-Do</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 56px;
            --gradient-start: #667eea;
            --gradient-mid: #764ba2;
            --gradient-end: #f093fb;
            --sidebar-bg: rgba(255,255,255,0.12);
            --sidebar-border: rgba(255,255,255,0.15);
            --card-glass: rgba(255,255,255,0.25);
            --card-glass-border: rgba(255,255,255,0.35);
            --text-primary: #fff;
            --text-muted: rgba(255,255,255,0.9);
        }
        [data-theme="dark"] {
            --gradient-start: #1a1a2e;
            --gradient-mid: #16213e;
            --gradient-end: #0f3460;
            --sidebar-bg: rgba(255,255,255,0.06);
            --sidebar-border: rgba(255,255,255,0.08);
            --card-glass: rgba(255,255,255,0.08);
            --card-glass-border: rgba(255,255,255,0.12);
            --text-primary: #e9ecef;
            --text-muted: rgba(233,236,239,0.85);
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-mid) 50%, var(--gradient-end) 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: var(--text-primary);
        }
        .app-wrap {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            backdrop-filter: blur(12px);
            border-right: 1px solid var(--sidebar-border);
            padding: 1.25rem 0;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1030;
        }
        .sidebar-brand {
            padding: 0 1.25rem 1.25rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--sidebar-border);
            margin-bottom: 1rem;
        }
        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 0.6rem 1.25rem;
            border-radius: 0 1rem 1rem 0;
            margin-right: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: var(--text-primary);
        }
        .sidebar .nav-link i {
            margin-right: 0.6rem;
            opacity: 0.95;
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
        }
        .top-navbar {
            height: var(--navbar-height);
            background: var(--sidebar-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sidebar-border);
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }
        .top-navbar .navbar-brand,
        .top-navbar .nav-link {
            color: var(--text-primary);
        }
        .page-content {
            padding: 1.5rem;
        }
        .stat-card {
            background: var(--card-glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--card-glass-border);
            border-radius: 1rem;
            padding: 1.25rem;
            color: var(--text-primary);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .stat-card .stat-label {
            font-size: 0.875rem;
            opacity: 0.95;
            margin-top: 0.25rem;
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            opacity: 0.9;
        }
        .stat-card.total .stat-icon { background: rgba(255,255,255,0.3); }
        .stat-card.completed .stat-icon { background: rgba(40, 167, 69, 0.5); }
        .stat-card.pending .stat-icon { background: rgba(255, 193, 7, 0.5); }
        .stat-card.overdue .stat-icon { background: rgba(220, 53, 69, 0.5); }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .top-navbar { left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-wrap">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-check2-square me-2"></i>To-Do
            </div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                    <i class="bi bi-list-task"></i>Tasks
                </a>
                <a class="nav-link" href="{{ route('tasks.create') }}">
                    <i class="bi bi-plus-circle"></i>New Task
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <nav class="top-navbar">
                <span class="navbar-brand mb-0 h6 mb-0">@yield('navbar-title', 'Dashboard')</span>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-light theme-toggle" id="themeToggle" title="Toggle dark/light mode" aria-label="Toggle theme">
                        <i class="bi bi-sun-fill theme-icon-light"></i>
                        <i class="bi bi-moon-fill theme-icon-dark d-none"></i>
                    </button>
                    @auth
                        <span class="small" style="color: var(--text-primary)">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                    @endauth
                </div>
            </nav>

            <main class="page-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){
        var KEY = 'theme';
        var html = document.documentElement;
        var btn = document.getElementById('themeToggle');
        var iconLight = document.querySelector('.theme-icon-light');
        var iconDark = document.querySelector('.theme-icon-dark');
        function apply(theme) {
            html.setAttribute('data-theme', theme);
            if (iconLight && iconDark) {
                iconLight.classList.toggle('d-none', theme === 'dark');
                iconDark.classList.toggle('d-none', theme !== 'dark');
            }
        }
        function get() { return localStorage.getItem(KEY) || 'light'; }
        function set(theme) { localStorage.setItem(KEY, theme); apply(theme); }
        apply(get());
        if (btn) btn.addEventListener('click', function() { var n = get() === 'dark' ? 'light' : 'dark'; set(n); });
    })();
    </script>
    @stack('scripts')
</body>
</html>
