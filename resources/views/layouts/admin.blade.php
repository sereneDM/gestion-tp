<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') — {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:      #3d5afe;
            --accent-2:    #2a46f5;
            --accent-bg:   #eef1ff;
            --success:     #10b981;
            --success-bg:  #ecfdf5;
            --warning:     #f59e0b;
            --danger:      #e53935;
            --ink:         #0f172a;
            --ink-2:       #1e293b;
            --ink-3:       #475569;
            --ink-4:       #94a3b8;
            --surface:     #ffffff;
            --surface-2:   #f8fafc;
            --surface-3:   #f1f5f9;
            --line:        #e2e8f0;
            --line-2:      #cbd5e1;
            --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   14px;
            --sidebar-w:   228px;
            --topbar-h:    56px;
        }

        body { font-family: 'Inter', sans-serif; background: var(--surface-2); color: var(--ink); min-height: 100vh; display: flex; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh;
            background: var(--surface); border-right: 1px solid var(--line);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 100;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 0 16px; height: var(--topbar-h);
            border-bottom: 1px solid var(--line);
            text-decoration: none; flex-shrink: 0;
        }

        .sidebar-brand-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--accent); display: flex; align-items: center;
            justify-content: center; color: white; font-size: 15px; flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-size: 12.5px; font-weight: 800; color: var(--ink);
            letter-spacing: -0.02em; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }

        .sidebar-nav {
            flex: 1; overflow-y: auto; padding: 10px 8px;
            display: flex; flex-direction: column; gap: 1px;
        }

        .nav-section-label {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.12em; color: var(--ink-4); padding: 12px 8px 4px;
        }

        .nav-link {
            display: flex; align-items: center; gap: 9px;
            padding: 7.5px 9px; border-radius: var(--radius-sm);
            text-decoration: none; color: var(--ink-3);
            font-size: 12.5px; font-weight: 500;
            transition: background 0.15s, color 0.15s;
        }

        .nav-link i { font-size: 15px; flex-shrink: 0; width: 17px; text-align: center; }
        .nav-link:hover { background: var(--surface-3); color: var(--ink-2); }
        .nav-link.active { background: var(--accent-bg); color: var(--accent); font-weight: 700; }
        .nav-link.active i { color: var(--accent); }

        /* Sidebar footer */
        .sidebar-footer { padding: 10px 8px 12px; border-top: 1px solid var(--line); flex-shrink: 0; }

        .user-card {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 9px; border-radius: var(--radius-sm);
            text-decoration: none; transition: background 0.15s; cursor: pointer;
        }

        .user-card:hover { background: var(--surface-3); }

        .user-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--accent-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; color: var(--accent); flex-shrink: 0;
        }

        .user-name { font-size: 12px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 10px; color: var(--ink-4); }

        .logout-btn {
            display: flex; align-items: center; gap: 7px;
            width: 100%; margin-top: 4px; padding: 7px 9px;
            border: 1px solid var(--line); border-radius: var(--radius-sm);
            background: var(--surface); color: var(--ink-3);
            font-size: 12px; font-weight: 600; font-family: inherit; cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .logout-btn:hover { background: #fff0f0; color: var(--danger); border-color: rgba(229,57,53,.2); }

        /* MAIN */
        .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* TOPBAR */
        .topbar {
            height: var(--topbar-h); background: var(--surface); border-bottom: 1px solid var(--line);
            display: flex; align-items: center; padding: 0 24px;
            position: sticky; top: 0; z-index: 50;
        }

        .tb-breadcrumb { display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0; }
        .tb-bc-page    { font-size: 12.5px; color: var(--ink-4); font-weight: 500; }
        .tb-bc-sep     { font-size: 12px; color: var(--line-2); }
        .tb-bc-current { font-size: 12.5px; color: var(--ink-2); font-weight: 700; }

        .tb-actions {
            display: flex; align-items: center; gap: 8px;
            padding-left: 16px; border-left: 1px solid var(--line);
            margin-left: 16px; flex-shrink: 0;
        }

        .tb-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: var(--radius-sm);
            font-size: 12px; font-weight: 700; font-family: inherit;
            cursor: pointer; text-decoration: none; border: 1px solid transparent;
            transition: background 0.15s; white-space: nowrap;
        }

        .tb-btn-primary   { background: var(--accent); color: white; box-shadow: 0 2px 6px rgba(61,90,254,.2); }
        .tb-btn-primary:hover { background: var(--accent-2); }
        .tb-btn-secondary { background: var(--surface); color: var(--ink-2); border-color: var(--line); }
        .tb-btn-secondary:hover { background: var(--surface-3); }

        .page-content { padding: 28px 28px 48px; flex: 1; }
        .page-title    { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em; color: var(--ink); margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: var(--ink-4); margin-bottom: 24px; }

        /* Card */
        .card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 20px; border-bottom: 1px solid var(--line);
            background: var(--surface-2); border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .card-header-title { font-size: 12.5px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 7px; }
        .card-header-title i { color: var(--ink-4); font-size: 15px; }

        /* Forms */
        .form-group { margin-bottom: 1.25rem; }

        .label {
            display: block; font-size: 11.5px; font-weight: 700; color: var(--ink-3);
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;
        }

        .input {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--line-2); border-radius: var(--radius-sm);
            font-size: 13.5px; font-family: inherit; color: var(--ink); background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
        }

        .input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-bg); }
        textarea.input { min-height: 90px; resize: vertical; line-height: 1.6; }
        .error { font-size: 12px; color: var(--danger); margin-top: 4px; }

        /* Buttons */
        .btn-group { display: flex; align-items: center; gap: 8px; margin-top: 1.5rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 700; font-family: inherit;
            cursor: pointer; text-decoration: none; border: 1px solid transparent;
            transition: background .15s;
        }

        .btn-primary  { background: var(--accent); color: white; box-shadow: 0 2px 6px rgba(61,90,254,.2); }
        .btn-primary:hover { background: var(--accent-2); }
        .btn-secondary { background: var(--surface); color: var(--ink-2); border-color: var(--line); }
        .btn-secondary:hover { background: var(--surface-3); }

        /* Table */
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }

        .admin-table th {
            text-align: left; padding: 10px 16px;
            font-size: 10px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.09em; color: var(--ink-4);
            background: var(--surface-2); border-bottom: 1px solid var(--line); white-space: nowrap;
        }

        .admin-table td { padding: 12px 16px; border-bottom: 1px solid var(--line); color: var(--ink-2); vertical-align: middle; }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover td { background: var(--surface-2); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px; border-radius: 100px; font-size: 11px; font-weight: 700; }
        .badge-active   { background: var(--success-bg); color: #065f46; }
        .badge-archived { background: var(--surface-3); color: var(--ink-4); }
        .badge-student  { background: #eff6ff; color: #1d4ed8; }
        .badge-teacher  { background: #fff7ed; color: #c2410c; }
        .badge-admin    { background: var(--accent-bg); color: var(--accent); }

        /* Action icon buttons */
        .actions { display: flex; align-items: center; gap: 4px; }

        .btn-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: var(--radius-sm);
            border: 1px solid var(--line); background: var(--surface); color: var(--ink-3);
            font-size: 15px; cursor: pointer; text-decoration: none; transition: all .15s;
        }

        .btn-icon:hover        { background: var(--surface-3); color: var(--ink); }
        .btn-icon.danger:hover { background: #fff0f0; color: var(--danger); border-color: rgba(229,57,53,.25); }

        /* Stat strip */
        .stat-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; margin-bottom: 24px; }

        .stat-tile { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); }
        .stat-tile-label { font-size: 10.5px; font-weight: 700; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 6px; }
        .stat-tile-value { font-size: 2rem; font-weight: 800; color: var(--ink); letter-spacing: -0.04em; line-height: 1; margin-bottom: 4px; }
        .stat-tile-sub   { font-size: 11px; color: var(--ink-4); }

        /* Flash messages */
        .flash { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 20px; font-size: 13px; font-weight: 500; line-height: 1.5; }
        .flash-success { background: var(--success-bg); color: #065f46; border: 1px solid rgba(16,185,129,.2); }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid rgba(229,57,53,.2); }
    </style>
    @yield('extra-styles')
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="ti ti-code"></i></div>
            <span class="sidebar-brand-name">{{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</span>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Vue d'ensemble</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Tableau de bord
            </a>
            <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                <i class="ti ti-chart-bar"></i> Statistiques
            </a>

            <div class="nav-section-label">Utilisateurs</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="ti ti-users"></i> Gérer les utilisateurs
            </a>

            <div class="nav-section-label">Cours & Classes</div>
            <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                <i class="ti ti-books"></i> Tous les cours
            </a>

            <div class="nav-section-label">Système</div>
            <a href="{{ route('admin.system-logs') }}" class="nav-link {{ request()->routeIs('admin.system-logs') ? 'active' : '' }}">
                <i class="ti ti-history"></i> Journal d'activité
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="ti ti-settings"></i> Paramètres système
            </a>
        </nav>

        <div class="sidebar-footer">
            {{-- Clicking the user card → profile page --}}
            <a href="{{ route('admin.profile') }}" class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div style="min-width:0; flex:1;">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrateur</div>
                </div>
                <i class="ti ti-chevron-right" style="font-size:13px; color:var(--ink-4); flex-shrink:0;"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ti ti-logout" style="font-size:14px;"></i> Se déconnecter
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrap">
        <header class="topbar">
            <nav class="tb-breadcrumb">@yield('breadcrumb')</nav>
            @hasSection('topbar-actions')
                <div class="tb-actions">@yield('topbar-actions')</div>
            @endif
        </header>

        <div style="padding: 0 28px;">
            @if(session('success'))
                <div class="flash flash-success" style="margin-top:20px;">
                    <i class="ti ti-circle-check" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error" style="margin-top:20px;">
                    <i class="ti ti-alert-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <main class="page-content">
            @yield('content')
        </main>
    </div>

</body>
</html>