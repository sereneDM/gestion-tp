@extends('layouts.app')

@section('title', 'Ma Progression')
@section('page-title', 'Ma Progression Académique')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #0f172a;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        border: 1px solid #334155;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #6366f1;
    }
    .stat-label {
        color: #cbd5e1;
        margin-top: 0.5rem;
    }
    .section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #334155;
    }
    .section h2 {
        color: #6366f1;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .chart-container {
        background: #1e293b;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1rem;
        border: 1px solid #334155;
    }
    .grade-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }
    .grade-item:last-child {
        border-bottom: none;
    }
    .grade-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #6366f1;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }
    th {
        background-color: #1e293b;
        font-weight: bold;
        color: #cbd5e1;
    }
    tr:hover {
        background-color: #334155;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-present {
        background-color: #86efac;
        color: #065f46;
    }
    .status-absent {
        background-color: #fca5a5;
        color: #7f1d1d;
    }
    .status-late {
        background-color: #fbbf24;
        color: #78350f;
    }
    .status-excused {
        background-color: #a5b4fc;
        color: #312e81;
    }
</style>
@endsection

@section('content')
    {{ Breadcrumbs::render('student.progress') }}


    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalSubmissions }}</div>
            <div class="stat-label">TP soumis</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $gradedSubmissions }}</div>
            <div class="stat-label">TP notés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pendingSubmissions }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}</div>
            <div class="stat-label">Moyenne</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $attendanceStats['present'] }}</div>
            <div class="stat-label">Présences</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $attendanceStats['absent'] }}</div>
            <div class="stat-label">Absences</div>
        </div>
    </div>

    <div class="section">
        <h2>Mes Notes par TP</h2>
        @if($gradesByTP->count() > 0)
            <div class="chart-container">
                @foreach($gradesByTP as $item)
                    <div class="grade-item">
                        <div>
                            <strong>{{ $item['tp']->title }}</strong>
                            <div style="color: #cbd5e1; font-size: 0.9rem; margin-top: 0.25rem;">
                                Enseignant: {{ $item['tp']->teacher->name }}
                            </div>
                            <div style="color: #999; font-size: 0.85rem; margin-top: 0.25rem;">
                                Soumis le: {{ $item['submitted_at']->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="grade-value">{{ number_format($item['grade'], 2) }}/20</div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">
                Aucune note disponible pour le moment
            </p>
        @endif
    </div>

    <div class="section">
        <h2>Historique de Présence</h2>
        @if($attendances->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Cours</th>
                        <th>Statut</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances->take(20) as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>{{ $attendance->class->name }}</td>
                            <td>
                                <span class="status-badge status-{{ $attendance->status }}">
                                    @if($attendance->status === 'present')
                                        ✓ Présent
                                    @elseif($attendance->status === 'absent')
                                        ✗ Absent
                                    @elseif($attendance->status === 'late')
                                        ⏰ Retard
                                    @else
                                        📝 Excusé
                                    @endif
                                </span>
                            </td>
                            <td>{{ $attendance->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">
                Aucun enregistrement de présence
            </p>
        @endif
    </div>
@endsection