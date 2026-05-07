@extends('layouts.app')

@section('title', 'Progression de ' . $student->name)
@section('page-title', 'Progression de ' . $student->name)

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.progress.show', $student) }}
@endsection

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        color: #e2e8f0;
    }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn:hover {
        opacity: 0.95;
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
        border-radius: 1rem;
        box-shadow: 0 12px 24px rgba(15,23,42,0.25);
        text-align: center;
        border: 1px solid #334155;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #818cf8;
    }
    .stat-label {
        color: #94a3b8;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .section h2 {
        color: #c7d2fe;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #cbd5e1;
    }
    th {
        background-color: #334155;
        font-weight: bold;
        color: #e2e8f0;
    }
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.15s;
    }
    .clickable-row:hover {
        background-color: #1e293b;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-graded {
        background-color: rgba(34,197,94,0.15);
        color: #86efac;
    }
    .status-submitted {
        background-color: rgba(251,191,36,0.15);
        color: #facc15;
    }
    .status-present {
        background-color: rgba(34,197,94,0.15);
        color: #86efac;
    }
    .status-absent {
        background-color: rgba(239,68,68,0.15);
        color: #fca5a5;
    }
    .status-late {
        background-color: rgba(251,191,36,0.15);
        color: #facc15;
    }
    .row-action-hint {
        color: #475569;
        font-size: 0.8rem;
        text-align: right;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .section-header h2 {
        color: #c7d2fe;
        margin: 0;
        border: none;
        padding: 0;
    }
    .section-hint {
        font-size: 0.8rem;
        color: #475569;
        font-style: italic;
    }
</style>
@endsection


@section('content')

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

    {{-- Submissions --}}
    <div class="section">
        <div class="section-header">
            <h2>Soumissions des TP</h2>
            @if($submissions->count() > 0)
                <span class="section-hint">Cliquez sur une ligne pour noter</span>
            @endif
        </div>

        @if($submissions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>TP</th>
                        <th>Date de soumission</th>
                        <th>Statut</th>
                        <th>Note</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr class="clickable-row"
                           onclick="window.location='{{ route('teacher.submissions.show', [$submission->tp_id, $submission->id]) }}'">
                            <td>{{ $submission->tp->title }}</td>
                            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $submission->grade ? $submission->grade . '/20' : 'En attente' }}</strong>
                            </td>
                            <td class="row-action-hint">
                                {{ $submission->grade ? '✏️ Modifier' : '📝 Noter' }} →
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">
                Aucune soumission pour le moment
            </p>
        @endif
    </div>

    {{-- Attendance --}}
    <div class="section">
        <div class="section-header">
            <h2>Historique de présence</h2>
            @if($attendances->count() > 0)
                <span class="section-hint">Cliquez sur une date pour modifier la séance</span>
            @endif
        </div>

        @if($attendances->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                        <tr class="clickable-row"
                            onclick="window.location='{{ route('teacher.attendance.show', ['class_id' => $attendance->class_id, 'date' => $attendance->date->format('Y-m-d'), 'student_id' => $student->id]) }}'">
                            <td style="color: #818cf8;">
                                {{ $attendance->date->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $attendance->status }}">
                                    {{ ucfirst($attendance->status) }}
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