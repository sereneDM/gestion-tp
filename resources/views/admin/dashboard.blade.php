@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="tb-bc-current">Dashboard</span>
@endsection

@section('extra-styles')
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
    }

    .action-list { display: flex; flex-direction: column; gap: 6px; }

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

    @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }
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
            <div style="padding: 16px;" class="action-list">
                <a href="{{ route('admin.users.create') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-user-plus"></i></div>
                    <div class="action-row-text">
                        <strong>Créer un compte</strong>
                        <span>Ajouter un étudiant, enseignant ou admin</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.users.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-users"></i></div>
                    <div class="action-row-text">
                        <strong>Gérer les utilisateurs</strong>
                        <span>Modifier les rôles et accès</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.classes.create') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-plus"></i></div>
                    <div class="action-row-text">
                        <strong>Créer une classe</strong>
                        <span>Nouvelle classe et assignation</span>
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
                        <span>Performances et activité</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.system-logs') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-history"></i></div>
                    <div class="action-row-text">
                        <strong>Journal d'activité</strong>
                        <span>Logs des actions système</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="action-row">
                    <div class="action-row-icon"><i class="ti ti-settings"></i></div>
                    <div class="action-row-text">
                        <strong>Paramètres système</strong>
                        <span>Configuration de la plateforme</span>
                    </div>
                    <i class="ti ti-chevron-right" style="color:var(--ink-4); font-size:16px;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Right: Info --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card" style="padding:20px;">
            <div class="card-header-title" style="margin-bottom:14px; font-size:12px;">
                <i class="ti ti-info-circle" style="color:var(--ink-4);"></i> Infos système
            </div>
            @foreach([
                ['PHP', PHP_VERSION],
                ['Laravel', app()->version()],
                ['Base de données', ucfirst(config('database.default'))],
                ['Environnement', ucfirst(config('app.env'))],
                ['Mode debug', config('app.debug') ? 'Activé ⚠️' : 'Désactivé ✓'],
            ] as [$k, $v])
            <div style="display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid var(--line); font-size:12.5px;">
                <span style="color:var(--ink-3);">{{ $k }}</span>
                <span style="font-weight:600; color:var(--ink);">{{ $v }}</span>
            </div>
            @endforeach
        </div>

        <div class="card" style="padding:20px; background:var(--accent-bg); border-color:rgba(61,90,254,.15);">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <i class="ti ti-school" style="font-size:20px; color:var(--accent);"></i>
                <span style="font-size:13px; font-weight:700; color:var(--accent);">Nom de la plateforme</span>
            </div>
            <div style="font-size:16px; font-weight:800; color:var(--ink); margin-bottom:6px;">
                {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}
            </div>
            <div style="font-size:11.5px; color:var(--ink-3); line-height:1.5;">
                {{ \App\Models\Setting::get('site_description', '') ?: 'Aucune description définie.' }}
            </div>
            <a href="{{ route('admin.settings.index') }}" style="display:inline-flex; align-items:center; gap:4px; margin-top:12px; font-size:12px; color:var(--accent); text-decoration:none; font-weight:600;">
                <i class="ti ti-edit" style="font-size:13px;"></i> Modifier
            </a>
        </div>
    </div>
</div>
@endsection