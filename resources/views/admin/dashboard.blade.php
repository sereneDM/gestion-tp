@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="tb-bc-current">Dashboard</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="tb-btn tb-btn-secondary">
        <i class="ti ti-user-plus"></i> Nouvel utilisateur
    </a>
    <a href="{{ route('admin.classes.create') }}" class="tb-btn tb-btn-primary">
        <i class="ti ti-plus"></i> Nouvelle classe
    </a>
@endsection

@section('extra-styles')
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
    }

    .quick-actions { display: flex; flex-direction: column; gap: 8px; }

    .action-row {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 18px;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--ink-2);
        background: var(--surface);
        transition: all .2s;
    }
    .action-row:hover { border-color: var(--accent); background: var(--accent-bg); color: var(--accent); }
    .action-row-icon {
        width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
        background: var(--surface-2);
        color: var(--ink-3);
        transition: all .2s;
    }
    .action-row:hover .action-row-icon { background: rgba(61,90,254,.12); color: var(--accent); }
    .action-row-text { flex: 1; }
    .action-row-text strong { display: block; font-size: 13px; font-weight: 700; }
    .action-row-text span { font-size: 11.5px; color: var(--ink-4); }
    .action-row:hover .action-row-text span { color: inherit; opacity: .7; }

    .sys-card { padding: 0; }
    .sys-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid var(--line);
        font-size: 12.5px;
    }
    .sys-row:last-child { border-bottom: none; }
    .sys-key { color: var(--ink-3); }
    .sys-val { font-weight: 600; color: var(--ink); }
    .sys-ok { color: var(--success); }

    .side-stack { display: flex; flex-direction: column; gap: 18px; }

    @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

{{-- ── Stat strip ──────────────────────────────────────── --}}
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Utilisateurs</div>
        <div class="stat-tile-value">{{ $totalUsers ?? '—' }}</div>
        <div class="stat-tile-sub">inscrits sur la plateforme</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Étudiants</div>
        <div class="stat-tile-value">{{ $totalStudents ?? '—' }}</div>
        <div class="stat-tile-sub">actifs</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Enseignants</div>
        <div class="stat-tile-value">{{ $totalTeachers ?? '—' }}</div>
        <div class="stat-tile-sub">sur la plateforme</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Classes</div>
        <div class="stat-tile-value">{{ $totalClasses ?? '—' }}</div>
        <div class="stat-tile-sub">actives</div>
    </div>
</div>

{{-- ── Main grid ───────────────────────────────────────── --}}
<div class="dash-grid">

    {{-- Left: Quick actions --}}
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-title"><i class="ti ti-bolt"></i> Actions rapides</div>
            </div>
            <div style="padding: 16px; display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('admin.users.create') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-user-plus"></i></div>
                    <div class="action-row-text">
                        <strong>Créer un compte</strong>
                        <span>Ajouter un étudiant, enseignant ou admin</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.users.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-shield-lock"></i></div>
                    <div class="action-row-text">
                        <strong>Gérer les droits</strong>
                        <span>Modifier les rôles et permissions</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.classes.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-books"></i></div>
                    <div class="action-row-text">
                        <strong>Superviser les classes</strong>
                        <span>Gérer et surveiller les cours actifs</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.statistics') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-chart-bar"></i></div>
                    <div class="action-row-text">
                        <strong>Statistiques globales</strong>
                        <span>Performances et rapports</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.system-logs') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-history"></i></div>
                    <div class="action-row-text">
                        <strong>Logs d'activité</strong>
                        <span>Journal des actions système</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Right: System state --}}
    <div class="side-stack">
        <div class="card sys-card">
            <div class="card-header">
                <div class="card-header-title"><i class="ti ti-server"></i> État du système</div>
            </div>
            <div class="sys-row">
                <span class="sys-key">PHP</span>
                <span class="sys-val">{{ PHP_VERSION }}</span>
            </div>
            <div class="sys-row">
                <span class="sys-key">Laravel</span>
                <span class="sys-val">{{ app()->version() }}</span>
            </div>
            <div class="sys-row">
                <span class="sys-key">Environnement</span>
                <span class="sys-val sys-ok">{{ ucfirst(config('app.env')) }}</span>
            </div>
            <div class="sys-row">
                <span class="sys-key">Debug</span>
                <span class="sys-val" style="color: {{ config('app.debug') ? 'var(--warning)' : 'var(--success)' }}">
                    {{ config('app.debug') ? 'Activé' : 'Désactivé' }}
                </span>
            </div>
        </div>

        
    </div>
 
</div>
@endsection