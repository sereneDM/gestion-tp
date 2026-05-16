@extends('layouts.admin')

@section('title', 'Statistiques Globales')

@section('breadcrumb')
    <span class="tb-bc-current">Statistiques</span>
@endsection

@section('extra-styles')
<style>
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink-4);
        margin: 2.5rem 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-width: 992px) {
        .data-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Statistiques Globales</h1>
<p class="page-subtitle">Vue d'ensemble des performances et de l'activité du système</p>

<div class="section-title"><i class="ti ti-users"></i> Communauté</div>
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Utilisateurs</div>
        <div class="stat-tile-value">{{ $totalUsers }}</div>
        <div class="stat-tile-sub">inscrits</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Étudiants</div>
        <div class="stat-tile-value">{{ $totalStudents }}</div>
        <div class="stat-tile-sub">actifs</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Enseignants</div>
        <div class="stat-tile-value">{{ $totalTeachers }}</div>
        <div class="stat-tile-sub">sur la plateforme</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Admins</div>
        <div class="stat-tile-value">{{ $totalAdmins }}</div>
        <div class="stat-tile-sub">système</div>
    </div>
</div>

<div class="section-title"><i class="ti ti-book"></i> Académique</div>
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">Classes</div>
        <div class="stat-tile-value">{{ $totalClasses }}</div>
        <div class="stat-tile-sub">total des classes</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">TP créés</div>
        <div class="stat-tile-value">{{ $totalTPs }}</div>
        <div class="stat-tile-sub">par les enseignants</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Soumissions</div>
        <div class="stat-tile-value">{{ $totalSubmissions }}</div>
        <div class="stat-tile-sub">reçues au total</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Moyenne</div>
        <div class="stat-tile-value">{{ $averageGrade ? number_format($averageGrade, 1) : '—' }}</div>
        <div class="stat-tile-sub">générale /20</div>
    </div>
</div>

<div class="data-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="ti ti-medal"></i> Meilleurs étudiants</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Moyenne</th>
                        <th>TP Soumis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topStudents as $item)
                        <tr>
                            <td style="font-weight:700; color:var(--ink);">{{ $item->student->name }}</td>
                            <td><span style="color:var(--success); font-weight:800;">{{ number_format($item->avg_grade, 2) }}</span></td>
                            <td style="color:var(--ink-3);">{{ $item->total_submissions }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; padding:3rem; color:var(--ink-4);">Aucune donnée disponible</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title"><i class="ti ti-chalkboard"></i> Enseignants actifs</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Enseignant</th>
                        <th>TP Créés</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeTeachers as $item)
                        <tr>
                            <td style="font-weight:700; color:var(--ink);">{{ $item->teacher->name }}</td>
                            <td><span style="color:var(--accent); font-weight:800;">{{ $item->tps_count }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="text-align:center; padding:3rem; color:var(--ink-4);">Aucune donnée disponible</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 20px; overflow: hidden;">
    <div class="card-header">
        <div class="card-header-title"><i class="ti ti-history"></i> Soumissions récentes</div>
    </div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Travail Pratique</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSubmissions as $submission)
                    <tr>
                        <td style="font-weight:700; color:var(--ink);">{{ $submission->student->name }}</td>
                        <td>{{ $submission->tp->title }}</td>
                        <td style="color:var(--ink-4); font-size:12px;">{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($submission->status === 'graded')
                                <span class="badge badge-graded">Noté</span>
                            @else
                                <span class="badge badge-pending">En attente</span>
                            @endif
                        </td>
                        <td style="font-weight:800; color:var(--ink);">
                            {{ $submission->grade ? number_format($submission->grade, 1) . '/20' : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; padding:3rem; color:var(--ink-4);">Aucune soumission récente</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection