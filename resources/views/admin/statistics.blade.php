@extends('layouts.admin')

@section('title', 'Statistiques Globales')

@section('breadcrumb')
    <span class="tb-bc-current">Statistiques</span>
@endsection

@section('extra-styles')
<style>
    .section-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink-4);
        margin: 2rem 0 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-top: 0;
    }

    @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<h1 class="page-title">Statistiques globales</h1>
<p class="page-subtitle">Vue d'ensemble de l'activité de la plateforme</p>

{{-- Community --}}
<div class="section-label"><i class="ti ti-users"></i> Communauté</div>
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Utilisateurs totaux</div>
        <div class="stat-tile-value">{{ $totalUsers }}</div>
        <div class="stat-tile-sub">inscrits</div>
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
        <div class="stat-tile-label">Administrateurs</div>
        <div class="stat-tile-value">{{ $totalAdmins }}</div>
        <div class="stat-tile-sub">comptes admin</div>
    </div>
</div>

{{-- Academic --}}
<div class="section-label"><i class="ti ti-book"></i> Académique</div>
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Classes</div>
        <div class="stat-tile-value">{{ $totalClasses }}</div>
        <div class="stat-tile-sub">créées au total</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">TPs créés</div>
        <div class="stat-tile-value">{{ $totalTPs }}</div>
        <div class="stat-tile-sub">par les enseignants</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Soumissions</div>
        <div class="stat-tile-value">{{ $totalSubmissions }}</div>
        <div class="stat-tile-sub">reçues au total</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Moyenne générale</div>
        <div class="stat-tile-value">{{ $averageGrade ? number_format($averageGrade, 1) : '—' }}</div>
        <div class="stat-tile-sub">sur 20</div>
    </div>
</div>

{{-- Attendance --}}
<div class="section-label"><i class="ti ti-calendar-check"></i> Présences</div>
<div class="stat-strip" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
    <div class="stat-tile">
        <div class="stat-tile-label">Total</div>
        <div class="stat-tile-value">{{ $totalAttendances }}</div>
        <div class="stat-tile-sub">enregistrements</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Présents</div>
        <div class="stat-tile-value" style="color:var(--success);">{{ $presentCount }}</div>
        <div class="stat-tile-sub">séances</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Absents</div>
        <div class="stat-tile-value" style="color:var(--danger);">{{ $absentCount }}</div>
        <div class="stat-tile-sub">séances</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Retards</div>
        <div class="stat-tile-value" style="color:var(--warning);">{{ $lateCount }}</div>
        <div class="stat-tile-sub">séances</div>
    </div>
</div>

<div class="two-col" style="margin-top:18px;">
    {{-- Top students --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="ti ti-medal"></i> Meilleurs étudiants</div>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Moyenne</th>
                        <th>TPs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topStudents as $i => $item)
                        <tr>
                            <td style="color:var(--ink-4); font-size:12px; font-weight:700;">{{ $i + 1 }}</td>
                            <td style="font-weight:700; color:var(--ink);">{{ $item->student->name }}</td>
                            <td><span style="color:var(--success); font-weight:800;">{{ number_format($item->avg_grade, 1) }}/20</span></td>
                            <td style="color:var(--ink-3);">{{ $item->total_submissions }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:3rem; color:var(--ink-4);">
                                <i class="ti ti-mood-empty" style="display:block; font-size:24px; margin-bottom:8px; opacity:.5;"></i>
                                Aucune donnée disponible
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Active teachers --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="ti ti-chalkboard"></i> Enseignants actifs</div>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Enseignant</th>
                        <th>TPs créés</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeTeachers as $i => $item)
                        <tr>
                            <td style="color:var(--ink-4); font-size:12px; font-weight:700;">{{ $i + 1 }}</td>
                            <td style="font-weight:700; color:var(--ink);">{{ $item->teacher->name }}</td>
                            <td><span style="color:var(--accent); font-weight:800;">{{ $item->tps_count }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; padding:3rem; color:var(--ink-4);">
                                <i class="ti ti-mood-empty" style="display:block; font-size:24px; margin-bottom:8px; opacity:.5;"></i>
                                Aucune donnée disponible
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection