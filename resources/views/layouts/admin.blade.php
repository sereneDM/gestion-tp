<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Plateforme</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        /* ─── Reset & Tokens ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Brand */
            --accent:       #3d5afe;
            --accent-2:     #5271ff;
            --accent-bg:    #eef1ff;
            --accent-muted: rgba(61,90,254,.15);

            /* Ink */
            --ink:   #0d1117;
            --ink-2: #3d4550;
            --ink-3: #6b7585;
            --ink-4: #9aa3af;

            /* Surfaces */
            --surface:   #ffffff;
            --surface-2: #f5f6f8;
            --surface-3: #eef0f3;

            /* Lines */
            --line:   #e8ebef;
            --line-2: #d1d6dd;

            /* Semantic */
            --success: #10b981;
            --warning: #f59e0b;
            --danger:  #e53935;

            /* Sidebar */
            --sb-bg:        #0d1117;
            --sb-border:    rgba(255,255,255,.07);
            --sb-text:      rgba(255,255,255,.45);
            --sb-text-hover:rgba(255,255,255,.85);
            --sb-active-bg: rgba(61,90,254,.22);
            --sb-active-tx: #c7d2fe;
            --sb-active-ic: #818cf8;
            --sb-width:     220px;

            /* Layout */
            --topbar-h:  52px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 22px;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,.07);

            /* Typography */
            --font: 'DM Sans', sans-serif;
            --font-serif: 'DM Serif Display', serif;
        }

        html, body { height: 100%; }

        body {
            font-family: var(--font);
            font-size: 14px;
            color: var(--ink-2);
            background: var(--surface-2);
            display: flex;
        }

        /* ─── Sidebar ────────────────────────────────────────────── */
        .sb {
            width: var(--sb-width);
            min-height: 100vh;
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sb-brand {
            padding: 20px 16px 18px;
            border-bottom: 1px solid var(--sb-border);
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }

        .sb-logo {
            width: 30px; height: 30px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sb-logo i { color: white; font-size: 16px; }

        .sb-wordmark {
            font-size: 13px; font-weight: 700;
            color: white; letter-spacing: .02em;
        }
        .sb-wordmark span { color: var(--sb-active-ic); }

        .sb-section {
            padding: 16px 16px 6px;
            font-size: 9px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.22);
        }

        .sb-nav { padding: 0 8px; display: flex; flex-direction: column; gap: 1px; }

        .sb-item {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--sb-text);
            font-size: 13px; font-weight: 500;
            transition: background .15s, color .15s;
            position: relative;
        }
        .sb-item i { font-size: 17px; width: 20px; flex-shrink: 0; transition: color .15s; }
        .sb-item:hover { background: rgba(255,255,255,.06); color: var(--sb-text-hover); }
        .sb-item:hover i { color: rgba(255,255,255,.75); }
        .sb-item.active { background: var(--sb-active-bg); color: var(--sb-active-tx); font-weight: 600; }
        .sb-item.active i { color: var(--sb-active-ic); }
        .sb-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sb-badge {
            margin-left: auto;
            background: rgba(61,90,254,.35);
            color: #c7d2fe;
            font-size: 9.5px; font-weight: 700;
            padding: 2px 7px;
            border-radius: 100px;
        }

        .sb-divider { height: 1px; background: var(--sb-border); margin: 10px 16px; }

        .sb-bottom {
            margin-top: auto;
            padding: 12px 8px;
            border-top: 1px solid var(--sb-border);
        }

        .sb-user {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            transition: background .15s;
        }
        .sb-user:hover { background: rgba(255,255,255,.06); }

        .sb-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3d5afe, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: white;
            flex-shrink: 0;
        }

        .sb-user-meta { display: flex; flex-direction: column; min-width: 0; }
        .sb-user-name { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { font-size: 10px; color: rgba(255,255,255,.3); }

        .sb-user-icon { margin-left: auto; color: rgba(255,255,255,.25); font-size: 14px; }

        /* ─── Main wrapper ───────────────────────────────────────── */
        .admin-main {
            margin-left: var(--sb-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── Topbar ─────────────────────────────────────────────── */
        .topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .tb-breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px;
        }
        .tb-bc-root { color: var(--ink-4); }
        .tb-bc-sep { color: var(--line-2); font-size: 16px; line-height: 1; }
        .tb-bc-page { color: var(--ink-3); }
        .tb-bc-current { color: var(--ink); font-weight: 600; }

        .tb-spacer { flex: 1; }

        .tb-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--surface-2);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 6px 12px;
            font-size: 12px; color: var(--ink-4);
            cursor: text;
            width: 200px;
            transition: border-color .2s, box-shadow .2s;
        }
        .tb-search:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-bg); }
        .tb-search input { border: none; background: none; outline: none; font-family: var(--font); font-size: 12px; color: var(--ink); width: 100%; }
        .tb-search input::placeholder { color: var(--ink-4); }
        .tb-search i { font-size: 14px; flex-shrink: 0; }

        .tb-divider { width: 1px; height: 22px; background: var(--line); }

        .tb-icon-btn {
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--ink-3);
            transition: all .2s;
            text-decoration: none;
            position: relative;
        }
        .tb-icon-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
        .tb-icon-btn i { font-size: 16px; }
        .tb-notif-dot {
            position: absolute; top: 5px; right: 5px;
            width: 6px; height: 6px;
            background: var(--accent); border-radius: 50%;
            border: 1.5px solid white;
        }

        .tb-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            border-radius: var(--radius-md);
            font-family: var(--font);
            font-size: 12px; font-weight: 700;
            cursor: pointer; text-decoration: none;
            border: 1px solid transparent;
            transition: all .2s;
        }
        .tb-btn i { font-size: 14px; }
        .tb-btn-secondary {
            background: var(--surface);
            color: var(--ink-3);
            border-color: var(--line);
        }
        .tb-btn-secondary:hover { background: var(--surface-2); color: var(--ink); }
        .tb-btn-primary {
            background: var(--accent);
            color: white;
        }
        .tb-btn-primary:hover { background: var(--accent-2); box-shadow: 0 4px 12px rgba(61,90,254,.25); }

        /* ─── Flash Messages ─────────────────────────────────────── */
        .flash {
            margin: 20px 28px 0;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ─── Page content wrapper ───────────────────────────────── */
        .admin-content {
            padding: 28px;
            flex: 1;
        }

        /* ─── Shared utility classes ─────────────────────────────── */
        .page-title {
            font-family: var(--font-serif);
            font-size: 1.75rem;
            color: var(--ink);
            margin-bottom: .25rem;
        }
        .page-subtitle { color: var(--ink-4); font-size: .875rem; margin-bottom: 1.75rem; }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--line);
            display: flex; align-items: center; gap: 8px;
        }
        .card-header-title {
            font-size: 13px; font-weight: 700; color: var(--ink);
            display: flex; align-items: center; gap: 6px; flex: 1;
        }
        .card-header-title i { color: var(--ink-4); font-size: 16px; }

        /* Table */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th {
            padding: 10px 20px;
            text-align: left;
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
            color: var(--ink-4);
            background: var(--surface-2);
            border-bottom: 1px solid var(--line);
        }
        .admin-table td {
            padding: 13px 20px;
            font-size: 13px; color: var(--ink-2);
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: var(--surface-2); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center;
            padding: .25rem .65rem;
            border-radius: 100px;
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .02em;
        }
        .badge-student { background: #eef1ff; color: var(--accent); }
        .badge-teacher { background: #fffbeb; color: #b45309; }
        .badge-admin   { background: #fef2f2; color: #dc2626; }
        .badge-active  { background: #ecfdf5; color: #065f46; }
        .badge-archived{ background: #fef2f2; color: #991b1b; }
        .badge-graded  { background: #ecfdf5; color: #065f46; }
        .badge-pending { background: #fffbeb; color: #92400e; }

        /* Icon action buttons */
        .btn-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            color: var(--ink-3); cursor: pointer;
            transition: all .2s; text-decoration: none;
        }
        .btn-icon i { font-size: 14px; }
        .btn-icon:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
        .btn-icon.danger:hover { border-color: var(--danger); color: var(--danger); background: #fef2f2; }

        .actions { display: flex; gap: 6px; align-items: center; }

        /* Stat strip */
        .stat-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }
        .stat-tile {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
        }
        .stat-tile-label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
            color: var(--ink-4); margin-bottom: 6px;
        }
        .stat-tile-value { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1; margin-bottom: 4px; }
        .stat-tile-sub { font-size: 11px; color: var(--ink-4); }
        .stat-up { color: var(--success); font-weight: 700; }

        /* Form elements */
        .form-group { margin-bottom: 1.4rem; }
        .label {
            display: block; font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
            color: var(--ink-4); margin-bottom: .45rem;
        }
        .input {
            width: 100%; padding: .7rem 1rem;
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            background: var(--surface-2);
            color: var(--ink); font-family: var(--font); font-size: .9rem;
            transition: all .2s;
        }
        .input:focus { outline: none; border-color: var(--accent); background: var(--surface); box-shadow: 0 0 0 3px var(--accent-bg); }
        textarea.input { min-height: 100px; resize: vertical; }
        .error { color: var(--danger); font-size: 11px; margin-top: .35rem; font-weight: 600; }

        .btn-group { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn {
            flex: 1; padding: .8rem;
            border-radius: var(--radius-md);
            font-family: var(--font); font-weight: 700; font-size: .875rem;
            cursor: pointer; text-align: center; text-decoration: none;
            border: 1px solid transparent; transition: all .2s;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-2); box-shadow: 0 4px 12px rgba(61,90,254,.2); }
        .btn-secondary { background: var(--surface); color: var(--ink-3); border-color: var(--line); }
        .btn-secondary:hover { background: var(--surface-2); color: var(--ink); }

        @yield('extra-styles')
    </style>

    @yield('head')
</head>
<body>

{{-- ── SIDEBAR ──────────────────────────────────────────────────── --}}
<aside class="sb">
    <a href="{{ route('admin.dashboard') }}" class="sb-brand">
        <div class="sb-logo"><i class="ti ti-command"></i></div>
        <span class="sb-wordmark">Admin<span>Panel</span></span>
    </a>

    <div class="sb-section">Aperçu</div>
    <nav class="sb-nav">
        <a href="{{ route('admin.dashboard') }}" class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ti ti-layout-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('admin.statistics') }}" class="sb-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
            <i class="ti ti-chart-bar"></i> Statistiques
        </a>
    </nav>

    <div class="sb-section">Gestion</div>
    <nav class="sb-nav">
        <a href="{{ route('admin.users.index') }}" class="sb-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="ti ti-users"></i> Utilisateurs
        </a>
        <a href="{{ route('admin.classes.index') }}" class="sb-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <i class="ti ti-books"></i> Classes
        </a>
    </nav>

    <div class="sb-section">Système</div>
    <nav class="sb-nav">
        <a href="{{ route('admin.system-logs') }}" class="sb-item {{ request()->routeIs('admin.system-logs') ? 'active' : '' }}">
            <i class="ti ti-history"></i> Logs d'activité
        </a>
        <a href="{{ route('admin.settings.index') }}" class="sb-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="ti ti-settings"></i> Paramètres
        </a>
    </nav>

    <div class="sb-bottom">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="sb-user-meta">
                <span class="sb-user-name">{{ Auth::user()->name }}</span>
                <span class="sb-user-role">Super administrateur</span>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="margin-left: auto; color: rgba(255,255,255,0.3); transition: color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'" title="Déconnexion">
                <i class="ti ti-logout" style="font-size: 18px;"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</aside>

{{-- ── MAIN ─────────────────────────────────────────────────────── --}}
<div class="admin-main">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="tb-breadcrumb">
            <span class="tb-bc-root">Admin</span>
            @hasSection('breadcrumb')
                <span class="tb-bc-sep">/</span>
                @yield('breadcrumb')
            @endif
        </div>

        <div class="tb-spacer"></div>

        <div class="tb-search">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Rechercher…">
        </div>

        <div class="tb-divider"></div>

        <a href="{{ route('admin.system-logs') }}" class="tb-icon-btn" title="Notifications">
            <i class="ti ti-bell"></i>
            <div class="tb-notif-dot"></div>
        </a>

        @yield('topbar-actions')
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash flash-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Page content --}}
    <main class="admin-content">
        @yield('content')
    </main>
</div>

@yield('scripts')
</body>
</html>
