@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="tb-bc-current">Dashboard</span>
@endsection

@section('extra-styles')
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
    }

    @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }

    /* Quick action rows */
    .action-list { display: flex; flex-direction: column; gap: 5px; }

    .action-row {
        display: flex; align-items: center; gap: 13px;
        padding: 12px 16px;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--ink-2);
        background: var(--surface);
        transition: all .2s;
    }

    .action-row:hover { border-color: var(--accent); background: var(--accent-bg); color: var(--accent); }

    .action-row-icon {
        width: 34px; height: 34px; border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
        background: var(--surface-2); color: var(--ink-3);
        transition: all .2s;
    }

    .action-row:hover .action-row-icon { background: rgba(61,90,254,.12); color: var(--accent); }

    .action-row-text { flex: 1; }
    .action-row-text strong { display: block; font-size: 12.5px; font-weight: 700; }
    .action-row-text span   { font-size: 11px; color: var(--ink-4); }

    /* System info table */
    .sysinfo-table { width: 100%; border-collapse: collapse; }

    .sysinfo-table tr { border-bottom: 1px solid var(--line); }
    .sysinfo-table tr:last-child { border-bottom: none; }

    .sysinfo-table td {
        padding: 10px 0;
        font-size: 12.5px; vertical-align: middle;
    }

    .sysinfo-table td:first-child { color: var(--ink-4); display: flex; align-items: center; gap: 7px; }
    .sysinfo-table td:last-child  { text-align: right; font-weight: 700; color: var(--ink); }

    .sysinfo-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 100px;
        font-size: 11px; font-weight: 700;
    }

    .sysinfo-badge.ok      { background: var(--success-bg); color: #065f46; }
    .sysinfo-badge.warn    { background: #fff3cd; color: #7c4a00; }
    .sysinfo-badge.neutral { background: var(--surface-3); color: var(--ink-3); }
    .sysinfo-badge.accent  { background: var(--accent-bg); color: var(--accent); }

    /* Platform card — same look as stat-tile */
    .platform-tile {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 20px;
        box-shadow: var(--shadow-sm);
    }

    .platform-tile-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px; padding-bottom: 14px;
        border-bottom: 1px solid var(--line);
    }

    .platform-tile-label {
        font-size: 10.5px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.07em; color: var(--ink-4);
        display: flex; align-items: center; gap: 6px;
    }

    .platform-tile-edit {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; color: var(--accent); text-decoration: none; font-weight: 700;
        padding: 3px 8px; border-radius: 4px;
        transition: background .15s;
    }

    .platform-tile-edit:hover { background: var(--accent-bg); }

    .platform-tile-name {
        font-size: 16px; font-weight: 800; color: var(--ink);
        letter-spacing: -0.02em; margin-bottom: 4px;
    }

    .platform-tile-desc {
        font-size: 12px; color: var(--ink-4); line-height: 1.5;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Tableau de bord</h1>
<p class="page-subtitle">Bienvenue, {{ Auth::user()->name }}. Vue d'ensemble de la plateforme.</p>

{{-- Stat strip --}}
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Utilisateurs</div>
        <div class="stat-tile-value">{{ $totalUsers }}</div>
        <div class="stat-tile-sub">inscrits au total</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Étudiants</div>
        <div class="stat-tile-value">{{ $totalStudents }}</div>
        <div class="stat-tile-sub">comptes actifs</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Enseignants</div>
        <div class="stat-tile-value">{{ $totalTeachers }}</div>
        <div class="stat-tile-sub">sur la plateforme</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Classes actives</div>
        <div class="stat-tile-value">{{ $totalClasses }}</div>
        <div class="stat-tile-sub">en cours</div>
    </div>
</div>

<div class="dash-grid">

    {{-- Left: Quick actions --}}
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-title"><i class="ti ti-bolt"></i> Actions rapides</div>
            </div>
            <div style="padding: 14px;" class="action-list">
                <a href="{{ route('admin.users.create') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-user-plus"></i></div>
                    <div class="action-row-text">
                        <strong>Créer un compte</strong>
                        <span>Ajouter un étudiant, enseignant ou admin</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.users.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-users"></i></div>
                    <div class="action-row-text">
                        <strong>Gérer les utilisateurs</strong>
                        <span>Modifier les rôles et accès</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.classes.create') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-plus"></i></div>
                    <div class="action-row-text">
                        <strong>Créer une classe</strong>
                        <span>Nouvelle classe et assignation</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.classes.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-books"></i></div>
                    <div class="action-row-text">
                        <strong>Superviser les classes</strong>
                        <span>Gérer et surveiller les cours actifs</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.statistics') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-chart-bar"></i></div>
                    <div class="action-row-text">
                        <strong>Statistiques globales</strong>
                        <span>Performances et activité</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.system-logs') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-history"></i></div>
                    <div class="action-row-text">
                        <strong>Journal d'activité</strong>
                        <span>Logs des actions système</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-settings"></i></div>
                    <div class="action-row-text">
                        <strong>Paramètres système</strong>
                        <span>Configuration de la plateforme</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:15px;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Right: Info panel --}}
    <div style="display:flex; flex-direction:column; gap:14px;">

        {{-- Infos système — improved --}}
        <div class="card" style="padding: 18px 20px;">
            <div class="card-header-title" style="margin-bottom: 14px; font-size:12px;">
                <i class="ti ti-server" style="color:var(--ink-4);"></i> Infos système
            </div>
            <table class="sysinfo-table">
                <tr>
                    <td><i class="ti ti-brand-php" style="font-size:15px;"></i> PHP</td>
                    <td><span class="sysinfo-badge accent">{{ PHP_VERSION }}</span></td>
                </tr>
                <tr>
                    <td><i class="ti ti-brand-laravel" style="font-size:15px;"></i> Laravel</td>
                    <td><span class="sysinfo-badge accent">{{ app()->version() }}</span></td>
                </tr>
                <tr>
                    <td><i class="ti ti-database" style="font-size:15px;"></i> Base de données</td>
                    <td><span class="sysinfo-badge neutral">{{ ucfirst(config('database.default')) }}</span></td>
                </tr>
                <tr>
                    <td><i class="ti ti-layers-intersect" style="font-size:15px;"></i> Environnement</td>
                    <td>
                        @if(config('app.env') === 'production')
                            <span class="sysinfo-badge ok">Production</span>
                        @else
                            <span class="sysinfo-badge neutral">{{ ucfirst(config('app.env')) }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><i class="ti ti-bug" style="font-size:15px;"></i> Mode debug</td>
                    <td>
                        @if(config('app.debug'))
                            <span class="sysinfo-badge warn"><i class="ti ti-alert-triangle" style="font-size:11px;"></i> Activé</span>
                        @else
                            <span class="sysinfo-badge ok"><i class="ti ti-check" style="font-size:11px;"></i> Désactivé</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- Plateforme tile — same card style as stat-tile --}}
        <div class="platform-tile">
            <div class="platform-tile-header">
                <div class="platform-tile-label">
                    <i class="ti ti-school" style="font-size:13px;"></i> Plateforme
                </div>
                <a href="{{ route('admin.settings.index') }}" class="platform-tile-edit">
                    <i class="ti ti-edit" style="font-size:12px;"></i> Modifier
                </a>
            </div>
            <div class="platform-tile-name">
                {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}
            </div>
            <div class="platform-tile-desc">
                {{ \App\Models\Setting::get('site_description', '') ?: 'Aucune description définie.' }}
            </div>
            @php $contact = \App\Models\Setting::get('contact_email', ''); @endphp
            @if($contact)
                <div style="margin-top: 12px; display:flex; align-items:center; gap:5px; font-size:11.5px; color:var(--ink-4);">
                    <i class="ti ti-mail" style="font-size:13px;"></i>
                    <a href="mailto:{{ $contact }}" style="color:var(--accent); text-decoration:none; font-weight:600;">{{ $contact }}</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection