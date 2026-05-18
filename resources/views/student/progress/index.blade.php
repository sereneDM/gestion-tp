@extends('layouts.app')

@section('title', 'Ma Progression')
@section('page-title', 'Ma Progression Académique')

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
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 1100px; margin: 0 auto; padding: 0.5rem 0 3rem; }

.page-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
    margin-bottom: 1.5rem;
}

/* ── Stats ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
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
    content: ""; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: var(--accent);
}
.stat-card.danger::before  { background: var(--danger); }
.stat-card.success::before { background: var(--success); }
.stat-card.warning::before { background: var(--warning); }
.stat-card.purple::before  { background: var(--purple); }

.stat-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.6rem;
    font-size: 17px;
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
.stat-lbl { font-size: 0.72rem; color: var(--ink-4); font-weight: 500; }

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
    gap: 0.5rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    background: var(--surface-2);
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--ink);
}
.section-header i { color: var(--accent); font-size: 17px; }

/* ── Grades list ── */
.grade-list { display: flex; flex-direction: column; }
.grade-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    gap: 1rem;
}
.grade-item:last-child { border-bottom: none; }

.grade-info { flex: 1; min-width: 0; }
.grade-tp-name { font-weight: 600; color: var(--ink); font-size: 0.9rem; }
.grade-meta { font-size: 0.78rem; color: var(--ink-4); margin-top: 3px; }

.grade-pill {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ink);
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 0.3rem 0.85rem;
    white-space: nowrap;
    letter-spacing: -0.01em;
}
.grade-pill.good    { background: var(--success-bg); border-color: rgba(16,185,129,0.2); color: var(--success); }
.grade-pill.average { background: var(--warning-bg); border-color: rgba(245,158,11,0.2); color: var(--warning); }
.grade-pill.poor    { background: var(--danger-bg);  border-color: rgba(229,57,53,0.2);  color: var(--danger); }

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

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.22rem 0.65rem; border-radius: 100px;
    font-size: 0.72rem; font-weight: 700;
}
.badge-present { background: var(--success-bg); color: var(--success); }
.badge-absent  { background: var(--danger-bg);  color: var(--danger);  }
.badge-late    { background: var(--warning-bg); color: var(--warning); }
.badge-excused { background: var(--accent-bg);  color: var(--accent);  }

.empty-row {
    padding: 2.5rem; text-align: center;
    color: var(--ink-4); font-size: 0.875rem;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    {{ Breadcrumbs::render('student.progress') }}

    <h1 class="page-heading">Ma progression</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-file-text"></i></div>
            <div class="stat-val">{{ $totalSubmissions }}</div>
            <div class="stat-lbl">TP soumis</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
            <div class="stat-val">{{ $gradedSubmissions }}</div>
            <div class="stat-lbl">TP notés</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="ti ti-clock"></i></div>
            <div class="stat-val">{{ $pendingSubmissions }}</div>
            <div class="stat-lbl">En attente</div>
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

    {{-- Grades ── --}}
    <div class="section-card">
        <div class="section-header">
            <i class="ti ti-medal"></i> Mes notes par TP
        </div>
        @if($gradesByTP->count() > 0)
            <div class="grade-list">
                @foreach($gradesByTP as $item)
                    @php
                        $g = $item['grade'];
                        $cls = $g >= 14 ? 'good' : ($g >= 10 ? 'average' : 'poor');
                    @endphp
                    <div class="grade-item">
                        <div class="grade-info">
                            <div class="grade-tp-name">{{ $item['tp']->title }}</div>
                            <div class="grade-meta">
                                {{ $item['tp']->teacher->name }} · Soumis le {{ $item['submitted_at']->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="grade-pill {{ $cls }}">{{ number_format($g, 2) }} / 20</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-row">
                <i class="ti ti-trophy-off" style="font-size:1.5rem; display:block; margin-bottom:0.4rem;"></i>
                Aucune note disponible pour le moment
            </div>
        @endif
    </div>

    {{-- Attendance ── --}}
    <div class="section-card">
        <div class="section-header">
            <i class="ti ti-calendar-stats"></i> Historique de présence
        </div>
        @if($attendances->count() > 0)
            <table class="data-table">
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
                            <td style="font-weight:600; color:var(--accent);">{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>{{ $attendance->class->name }}</td>
                            <td>
                                <span class="badge badge-{{ $attendance->status }}">
                                    @if($attendance->status === 'present')   <i class="ti ti-check"></i> Présent
                                    @elseif($attendance->status === 'absent') <i class="ti ti-x"></i> Absent
                                    @elseif($attendance->status === 'late')   <i class="ti ti-clock"></i> Retard
                                    @else                                     <i class="ti ti-notes"></i> Excusé
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