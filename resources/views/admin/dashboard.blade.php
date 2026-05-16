@extends('layouts.app')

@section('title', 'Administration')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
    :root {
        --ink:        #0d1117;
        --ink-2:      #3d4550;
        --ink-3:      #6b7585;
        --ink-4:      #9aa3af;
        --line:       #e8ebef;
        --line-2:     #d1d6dd;
        --surface:    #ffffff;
        --surface-2:  #f5f6f8;
        --surface-3:  #eef0f3;
        --accent:     #3d5afe;
        --accent-2:   #5271ff;
        --accent-bg:  #eef1ff;
        --danger:     #e53935;
        --warning:    #f59e0b;
        --success:    #10b981;
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  16px;
        --radius-xl:  22px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .admin-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #3d5afe 0%, #7c3aed 100%);
        border-radius: var(--radius-xl);
        padding: 3rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(61, 90, 254, 0.25);
    }

    .welcome-banner::before {
        content: "";
        position: absolute;
        top: -50px; right: -50px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-banner h1 {
        font-family: var(--font-serif);
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }

    .welcome-banner p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        line-height: 1.6;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: var(--shadow-sm);
        text-decoration: none;
        color: inherit;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .icon-blue { background: var(--accent-bg); color: var(--accent); }
    .icon-purple { background: #f3f0ff; color: #7c3aed; }
    .icon-green { background: #ecfdf5; color: #10b981; }

    .stat-info div:first-child {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-4);
        margin-bottom: 0.25rem;
    }

    .stat-info div:last-child {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--ink);
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .card-title {
        font-family: var(--font-serif);
        font-size: 1.25rem;
        margin-bottom: 1.25rem;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: var(--accent);
        font-size: 1.2rem;
    }

    .action-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--ink-2);
        transition: all 0.2s;
    }

    .action-item:hover {
        background: var(--surface-2);
        border-color: var(--accent);
        color: var(--accent);
    }

    .action-item i {
        font-size: 1.25rem;
    }

    .action-text {
        flex: 1;
    }

    .action-text div:first-child {
        font-weight: 700;
        font-size: 0.9rem;
    }

    .action-text div:last-child {
        font-size: 0.75rem;
        color: var(--ink-4);
    }

    @media (max-width: 768px) {
        .admin-grid { grid-template-columns: 1fr; }
        .welcome-banner { padding: 2rem; }
        .welcome-banner h1 { font-size: 1.75rem; }
    }
</style>
@endsection

@section('content')
<div class="admin-wrapper">
    <div class="welcome-banner">
        <h1>Bonjour, {{ Auth::user()->name }}</h1>
        <p>Bienvenue dans votre centre de contrôle. Gérez les utilisateurs, surveillez les activités et analysez les performances globales de la plateforme.</p>
    </div>

    <div class="quick-stats">
        <a href="{{ route('admin.users.index') }}" class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="ti ti-users"></i>
            </div>
            <div class="stat-info">
                <div>Gestion</div>
                <div>Utilisateurs</div>
            </div>
        </a>
        <a href="{{ route('admin.statistics') }}" class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="ti ti-chart-bar"></i>
            </div>
            <div class="stat-info">
                <div>Analyse</div>
                <div>Statistiques</div>
            </div>
        </a>
        <a href="{{ route('admin.system-logs') }}" class="stat-card">
            <div class="stat-icon icon-green">
                <i class="ti ti-history"></i>
            </div>
            <div class="stat-info">
                <div>Système</div>
                <div>Logs d'activité</div>
            </div>
        </a>
    </div>

    <div class="admin-grid">
        <div class="card">
            <h2 class="card-title"><i class="ti ti-settings"></i> Actions rapides</h2>
            <div class="action-list">
                <a href="{{ route('admin.users.create') }}" class="action-item">
                    <i class="ti ti-user-plus"></i>
                    <div class="action-text">
                        <div>Créer un compte</div>
                        <div>Ajouter un nouvel étudiant ou enseignant</div>
                    </div>
                    <i class="ti ti-chevron-right"></i>
                </a>
                <a href="{{ route('admin.users.index') }}" class="action-item">
                    <i class="ti ti-shield-lock"></i>
                    <div class="action-text">
                        <div>Gérer les droits</div>
                        <div>Modifier les rôles et permissions</div>
                    </div>
                    <i class="ti ti-chevron-right"></i>
                </a>
                <a href="{{ route('admin.statistics') }}" class="action-item">
                    <i class="ti ti-download"></i>
                    <div class="action-text">
                        <div>Rapports</div>
                        <div>Générer des rapports de performance</div>
                    </div>
                    <i class="ti ti-chevron-right"></i>
                </a>
                <a href="{{ route('admin.classes.index') }}" class="action-item">
                    <i class="ti ti-books"></i>
                    <div class="action-text">
                        <div>Superviser les classes</div>
                        <div>Gérer et surveiller les cours actifs</div>
                    </div>
                    <i class="ti ti-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title"><i class="ti ti-info-circle"></i> État du système</h2>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                    <span style="color:var(--ink-3);">Version PHP</span>
                    <span style="font-weight:600;">{{ PHP_VERSION }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                    <span style="color:var(--ink-3);">Version Laravel</span>
                    <span style="font-weight:600;">{{ app()->version() }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                    <span style="color:var(--ink-3);">Environnement</span>
                    <span style="color:var(--success); font-weight:600;">{{ config('app.env') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection