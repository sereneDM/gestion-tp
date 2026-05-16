@extends('layouts.app')

@section('title', 'Statistiques Globales')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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

    .stats-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2rem;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--ink-4);
        font-size: 1rem;
    }

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .stat-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ink);
        line-height: 1;
    }

    .stat-label {
        font-size: 0.82rem;
        color: var(--ink-3);
        font-weight: 500;
    }

    .stat-card.accent { border-left: 4px solid var(--accent); }
    .stat-card.success { border-left: 4px solid var(--success); }
    .stat-card.warning { border-left: 4px solid var(--warning); }
    .stat-card.danger { border-left: 4px solid var(--danger); }

    .data-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
        background: var(--surface-2);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: var(--ink);
    }

    .card-header i { color: var(--accent); }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 0.75rem 1.5rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-4);
        border-bottom: 1px solid var(--line);
    }

    td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--line);
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background: var(--surface-2); }

    .badge {
        padding: 0.25rem 0.65rem;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-success { background: #ecfdf5; color: #10b981; }
    .badge-warning { background: #fffbeb; color: #f59e0b; }

    @media (max-width: 992px) {
        .data-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="stats-wrapper">
    <div class="page-header">
        <h1 class="page-title">Statistiques Globales</h1>
        <p class="page-subtitle">Vue d'ensemble des performances et de l'activité du système</p>
    </div>

    <div class="section-title"><i class="ti ti-users"></i> Communauté</div>
    <div class="stats-grid">
        <div class="stat-card accent">
            <div class="stat-val">{{ $totalUsers }}</div>
            <div class="stat-label">Utilisateurs inscrits</div>
        </div>
        <div class="stat-card success">
            <div class="stat-val">{{ $totalStudents }}</div>
            <div class="stat-label">Étudiants actifs</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-val">{{ $totalTeachers }}</div>
            <div class="stat-label">Enseignants</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-val">{{ $totalAdmins }}</div>
            <div class="stat-label">Administrateurs</div>
        </div>
    </div>

    <div class="section-title"><i class="ti ti-book"></i> Académique</div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-val">{{ $totalClasses }}</div>
            <div class="stat-label">Total des classes</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $totalTPs }}</div>
            <div class="stat-label">TP créés</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $totalSubmissions }}</div>
            <div class="stat-label">Soumissions reçues</div>
        </div>
        <div class="stat-card accent">
            <div class="stat-val">{{ $averageGrade ? number_format($averageGrade, 1) : '—' }}</div>
            <div class="stat-label">Moyenne générale /20</div>
        </div>
    </div>

    <div class="data-grid">
        <div class="card">
            <div class="card-header"><i class="ti ti-medal"></i> Meilleurs étudiants</div>
            <div class="table-container">
                <table>
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
                                <td style="font-weight:600;">{{ $item->student->name }}</td>
                                <td><span style="color:var(--success); font-weight:700;">{{ number_format($item->avg_grade, 2) }}</span></td>
                                <td>{{ $item->total_submissions }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; padding:3rem; color:var(--ink-4);">Aucune donnée disponible</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="ti ti-chalkboard"></i> Enseignants actifs</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Enseignant</th>
                            <th>TP Créés</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeTeachers as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->teacher->name }}</td>
                                <td><span style="color:var(--accent); font-weight:700;">{{ $item->tps_count }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align:center; padding:3rem; color:var(--ink-4);">Aucune donnée disponible</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 2rem;">
        <div class="card-header"><i class="ti ti-history"></i> Soumissions récentes</div>
        <div class="table-container">
            <table>
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
                            <td style="font-weight:600;">{{ $submission->student->name }}</td>
                            <td>{{ $submission->tp->title }}</td>
                            <td style="color:var(--ink-3);">{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($submission->status === 'graded')
                                    <span class="badge badge-success">Noté</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                            <td style="font-weight:700;">
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
</div>
@endsection