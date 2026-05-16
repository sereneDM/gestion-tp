@extends('layouts.app')

@section('title', 'Progression de ' . $student->name)
@section('page-title', 'Progression de ' . $student->name)

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.progress.show', $student) }}
@endsection

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display@0;1&display=swap" rel="stylesheet">
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
    --accent-bg:  #eef1ff;
    --danger:     #e53935;
    --danger-bg:  #fff0f0;
    --warning:    #f59e0b;
    --warning-bg: #fffbeb;
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --purple:     #7c3aed;
    --purple-bg:  #f3f0ff;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 1100px; margin: 0 auto; padding: 0.5rem 0 3rem; }

/* ── Stats grid ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
}
.stat-card.danger::before  { background: var(--danger); }
.stat-card.success::before { background: var(--success); }
.stat-card.warning::before { background: var(--warning); }
.stat-card.purple::before  { background: var(--purple); }

.stat-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.65rem;
    font-size: 18px;
    background: var(--accent-bg);
    color: var(--accent);
}
.stat-card.danger  .stat-icon { background: var(--danger-bg);  color: var(--danger); }
.stat-card.success .stat-icon { background: var(--success-bg); color: var(--success); }
.stat-card.warning .stat-icon { background: var(--warning-bg); color: var(--warning); }
.stat-card.purple  .stat-icon { background: var(--purple-bg);  color: var(--purple); }

.stat-val {
    font-family: var(--font-serif);
    font-size: 1.9rem;
    color: var(--ink);
    letter-spacing: -0.02em;
    line-height: 1;
    margin-bottom: 0.3rem;
}
.stat-lbl {
    font-size: 0.75rem;
    color: var(--ink-4);
    font-weight: 500;
}

/* ── Section card ── */
.section-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    background: var(--surface-2);
}

.section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.section-title i { color: var(--accent); font-size: 17px; }

.section-hint {
    font-size: 0.75rem;
    color: var(--ink-4);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ── Table ── */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    padding: 0.75rem 1.25rem;
    text-align: left;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-4);
    background: var(--surface-2);
    border-bottom: 1px solid var(--line);
}
.data-table td {
    padding: 0.9rem 1.25rem;
    font-size: 0.875rem;
    color: var(--ink-2);
    border-bottom: 1px solid var(--line);
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr.clickable { cursor: pointer; transition: background 0.15s; }
.data-table tbody tr.clickable:hover { background: var(--surface-2); }

/* ── Badges ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.22rem 0.65rem;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 700;
}
.badge-graded    { background: var(--success-bg); color: var(--success); }
.badge-submitted { background: var(--warning-bg); color: var(--warning); }
.badge-present   { background: var(--success-bg); color: var(--success); }
.badge-absent    { background: var(--danger-bg);  color: var(--danger);  }
.badge-late      { background: var(--warning-bg); color: var(--warning); }

.grade-strong { font-weight: 700; color: var(--ink); }
.date-col     { color: var(--accent); font-weight: 500; }

.row-hint {
    font-size: 0.75rem;
    color: var(--ink-4);
    display: flex;
    align-items: center;
    gap: 4px;
    justify-content: flex-end;
}

.empty-row {
    padding: 2.5rem;
    text-align: center;
    color: var(--ink-4);
    font-size: 0.875rem;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="stats-grid">
        <div class="stat-card accent">
            <div class="stat-icon"><i class="ti ti-file-text"></i></div>
            <div class="stat-val">{{ $totalSubmissions }}</div>
            <div class="stat-lbl">TP soumis</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
            <div class="stat-val">{{ $gradedSubmissions }}</div>
            <div class="stat-lbl">TP notés</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon"><i class="ti ti-chart-bar"></i></div>
            <div class="stat-val">{{ $averageGrade ? number_format($averageGrade, 1) : '—' }}</div>
            <div class="stat-lbl">Moyenne /20</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="ti ti-user-check"></i></div>
            <div class="stat-val">{{ $attendanceStats['present'] }}</div>
            <div class="stat-lbl">Présences</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon"><i class="ti ti-user-off"></i></div>
            <div class="stat-val">{{ $attendanceStats['absent'] }}</div>
            <div class="stat-lbl">Absences</div>
        </div>
    </div>

    {{-- Submissions --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-files"></i> Soumissions des TP
            </div>
            @if($submissions->count() > 0)
                <span class="section-hint"><i class="ti ti-click"></i> Cliquer pour noter</span>
            @endif
        </div>

        @if($submissions->count() > 0)
            <table class="data-table">
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
                        <tr class="clickable"
                            onclick="window.location='{{ route('teacher.submissions.show', [$submission->tp_id, $submission->id]) }}'">
                            <td style="font-weight:600; color:var(--ink);">{{ $submission->tp->title }}</td>
                            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $submission->status }}">
                                    @if($submission->status === 'graded')
                                        <i class="ti ti-circle-check"></i> Noté
                                    @else
                                        <i class="ti ti-clock"></i> En attente
                                    @endif
                                </span>
                            </td>
                            <td class="grade-strong">
                                {{ $submission->grade ? $submission->grade . ' / 20' : '—' }}
                            </td>
                            <td>
                                <div class="row-hint">
                                    {{ $submission->grade ? 'Modifier' : 'Noter' }}
                                    <i class="ti ti-arrow-right"></i>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-row">
                <i class="ti ti-file-off" style="font-size:1.5rem; display:block; margin-bottom:0.4rem;"></i>
                Aucune soumission pour le moment
            </div>
        @endif
    </div>

    {{-- Attendance --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-calendar-stats"></i> Historique de présence
            </div>
            @if($attendances->count() > 0)
                <span class="section-hint"><i class="ti ti-click"></i> Cliquer pour modifier</span>
            @endif
        </div>

        @if($attendances->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                        <tr class="clickable"
                            onclick="window.location='{{ route('teacher.attendance.show', ['class_id' => $attendance->class_id, 'date' => $attendance->date->format('Y-m-d'), 'student_id' => $student->id]) }}'">
                            <td class="date-col">{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $attendance->status }}">
                                    @if($attendance->status === 'present')
                                        <i class="ti ti-check"></i> Présent
                                    @elseif($attendance->status === 'absent')
                                        <i class="ti ti-x"></i> Absent
                                    @elseif($attendance->status === 'late')
                                        <i class="ti ti-clock"></i> Retard
                                    @else
                                        <i class="ti ti-notes"></i> Excusé
                                    @endif
                                </span>
                            </td>
                            <td style="color:var(--ink-3);">{{ $attendance->notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-row">
                <i class="ti ti-calendar-off" style="font-size:1.5rem; display:block; margin-bottom:0.4rem;"></i>
                Aucun enregistrement de présence
            </div>
        @endif
    </div>

</div>
@endsection