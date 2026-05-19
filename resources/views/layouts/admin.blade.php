<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:       #3d5afe;
            --accent-2:     #5271ff;
            --accent-bg:    #eef1ff;
            --accent-muted: rgba(61,90,254,.15);
            --ink:   #0d1117;
            --ink-2: #3d4550;
            --ink-3: #6b7585;
            --ink-4: #9aa3af;
            --surface:   #ffffff;
            --surface-2: #f5f6f8;
            --surface-3: #eef0f3;
            --line:   #e8ebef;
            --line-2: #d1d6dd;
            --success: #10b981;
            --warning: #f59e0b;
            --danger:  #e53935;
            --sb-bg:        #0d1117;
            --sb-border:    rgba(255,255,255,.07);
            --sb-text:      rgba(255,255,255,.45);
            --sb-text-hover:rgba(255,255,255,.85);
            --sb-active-bg: rgba(61,90,254,.22);
            --sb-active-tx: #c7d2fe;
            --sb-active-ic: #818cf8;
            --sb-width:     220px;
            --topbar-h:  52px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 22px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,.07);
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

        /* ── Sidebar ── */
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
            padding: 18px 16px 16px;
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
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sb-wordmark span { color: var(--sb-active-ic); }

        .sb-section {
            padding: 14px 16px 5px;
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
        .sb-item i { font-size: 17px; width: 20px; flex-shrink: 0; }
        .sb-item:hover { background: rgba(255,255,255,.06); color: var(--sb-text-hover); }
        .sb-item.active { background: var(--sb-active-bg); color: var(--sb-active-tx); font-weight: 600; }
        .sb-item.active i { color: var(--sb-active-ic); }
        .sb-item.active::before {
            content: '';
            position: absolute; left: 0; top: 6px; bottom: 6px;
            width: 3px; background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sb-divider { height: 1px; background: var(--sb-border); margin: 8px 16px; }

        .sb-bottom {
            margin-top: auto;
            padding: 12px 8px;
            border-top: 1px solid var(--sb-border);
        }

        .sb-user {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
        }

        .sb-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: linear-gradient(135deg, #3d5afe, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: white;
        }
        .sb-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .sb-user-meta { display: flex; flex-direction: column; min-width: 0; flex: 1; }
        .sb-user-name { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { font-size: 10px; color: rgba(255,255,255,.3); }

        .sb-bottom-actions { display: flex; gap: 4px; }
        .sb-icon-btn {
            width: 28px; height: 28px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,.1);
            background: rgba(255,255,255,.05);
            color: rgba(255,255,255,.4);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px;
            text-decoration: none;
            transition: background .15s, color .15s;
        }
        .sb-icon-btn:hover { background: rgba(255,255,255,.1); color: rgba(255,255,255,.8); }

        /* ── Main wrapper ── */
        .admin-main {
            margin-left: var(--sb-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 10px;
            position: sticky; top: 0; z-index: 50;
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

        .tb-actions { display: flex; align-items: center; gap: 8px; }

        /* ── Flash Messages ── */
        .flash {
            margin: 20px 28px 0;
            padding: 11px 16px;
            border-radius: var(--radius-md);
            font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Page content ── */
        .admin-content { padding: 28px; flex: 1; }

        /* ── Shared utilities ── */
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

        .badge {
            display: inline-flex; align-items: center;
            padding: .25rem .65rem;
            border-radius: 100px;
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .02em;
        }
        .badge-student  { background: #eef1ff; color: var(--accent); }
        .badge-teacher  { background: #fffbeb; color: #b45309; }
        .badge-admin    { background: #fef2f2; color: #dc2626; }
        .badge-active   { background: #ecfdf5; color: #065f46; }
        .badge-archived { background: #fef2f2; color: #991b1b; }
        .badge-graded   { background: #ecfdf5; color: #065f46; }
        .badge-pending  { background: #fffbeb; color: #92400e; }

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
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-2); box-shadow: 0 4px 12px rgba(61,90,254,.2); }
        .btn-secondary { background: var(--surface); color: var(--ink-3); border-color: var(--line); }
        .btn-secondary:hover { background: var(--surface-2); color: var(--ink); }

        /* topbar action buttons */
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
        .tb-btn-secondary { background: var(--surface); color: var(--ink-3); border-color: var(--line); }
        .tb-btn-secondary:hover { background: var(--surface-2); color: var(--ink); }
        .tb-btn-primary { background: var(--accent); color: white; }
        .tb-btn-primary:hover { background: var(--accent-2); }

        @yield('extra-styles')
    </style>

    @yield('head')
</head>
<body>

{{-- ── SIDEBAR ── --}}
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
            <i class="ti ti-history"></i> Journal d'activité
        </a>
        <a href="{{ route('admin.settings.index') }}" class="sb-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="ti ti-settings"></i> Paramètres
        </a>
    </nav>

    <div class="sb-divider"></div>

    <div class="sb-bottom">
        <div class="sb-user">
            <div class="sb-avatar">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <div class="sb-user-meta">
                <span class="sb-user-name">{{ Auth::user()->name }}</span>
                <span class="sb-user-role">Administrateur</span>
            </div>
        </div>
        <div style="display:flex; gap:4px; padding:4px 10px 0;">
            <a href="{{ route('admin.profile') }}" class="sb-icon-btn" title="Mon profil">
                <i class="ti ti-user"></i>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sb-icon-btn" title="Déconnexion">
                <i class="ti ti-logout"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="admin-main">

    <header class="topbar">
        <div class="tb-breadcrumb">
            <span class="tb-bc-root">Admin</span>
            @hasSection('breadcrumb')
                <span class="tb-bc-sep">/</span>
                @yield('breadcrumb')
            @endif
        </div>
        <div class="tb-spacer"></div>
        <div class="tb-actions">
            @yield('topbar-actions')
        </div>
    </header>

    @if(session('success'))
        <div class="flash flash-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    <main class="admin-content">
        @yield('content')
    </main>
</div>

@yield('scripts')
</body>
</html>