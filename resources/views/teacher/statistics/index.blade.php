@extends('layouts.app')

@section('title', 'Statistiques Pédagogiques')
@section('page-title', 'Statistiques Pédagogiques')

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
    --teal:       #0891b2;
    --teal-bg:    #ecfeff;
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
.stat-card.teal::before    { background: var(--teal); }

.stat-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.6rem; font-size: 17px;
    background: var(--accent-bg); color: var(--accent);
}
.stat-card.danger  .stat-icon { background: var(--danger-bg);  color: var(--danger); }
.stat-card.success .stat-icon { background: var(--success-bg); color: var(--success); }
.stat-card.warning .stat-icon { background: var(--warning-bg); color: var(--warning); }
.stat-card.purple  .stat-icon { background: var(--purple-bg);  color: var(--purple); }
.stat-card.teal    .stat-icon { background: var(--teal-bg);    color: var(--teal); }

.stat-val {
    font-family: var(--font-serif);
    font-size: 1.9rem; color: var(--ink);
    letter-spacing: -0.02em; line-height: 1; margin-bottom: 0.3rem;
}
.stat-lbl { font-size: 0.72rem; color: var(--ink-4); font-weight: 500; }

/* ── Layout ── */
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
@media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }

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
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.95rem; font-weight: 700; color: var(--ink);
}
.section-title i { color: var(--accent); font-size: 17px; }

/* ── Chart bars ── */
.chart-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; max-height: 300px; overflow-y: auto; }
.chart-bar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; gap: 0.5rem; }
.chart-label {
    font-size: 0.82rem; font-weight: 600; color: var(--ink-2);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60%; margin-right: 0.5rem;
}
.chart-count { font-size: 0.78rem; font-weight: 700; color: var(--accent); white-space: nowrap; }
.chart-track {
    width: 100%; background: var(--surface-2);
    border: 1px solid var(--line); border-radius: 100px; height: 8px; overflow: hidden;
}
.chart-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), #818cf8);
    border-radius: 100px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 4px;
}

/* ── Filter bar ── */
.filter-bar {
    display: flex; align-items: center; gap: 0.6rem;
    flex-wrap: wrap; padding: 0.85rem 1.5rem;
    border-bottom: 1px solid var(--line);
}
.filter-input-wrap { position: relative; flex: 1; min-width: 140px; }
.filter-input-wrap i {
    position: absolute; left: 9px; top: 50%;
    transform: translateY(-50%); font-size: 14px;
    color: var(--ink-4); pointer-events: none;
}
.filter-input {
    width: 100%;
    padding: 0.5rem 0.85rem 0.5rem 2rem;
    border: 1px solid var(--line-2); border-radius: var(--radius-md);
    font-size: 0.82rem; font-family: var(--font-body);
    background: var(--surface-2); color: var(--ink);
    transition: border-color 0.2s;
}
.filter-input:focus { outline: none; border-color: var(--accent); }
.filter-input::placeholder { color: var(--ink-4); }

.filter-select {
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    border: 1px solid var(--line-2); border-radius: var(--radius-md);
    font-size: 0.82rem; font-family: var(--font-body);
    background: var(--surface-2); color: var(--ink); cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center;
}
.filter-select:focus { outline: none; border-color: var(--accent); }
.filter-divider { font-size: 0.75rem; color: var(--ink-4); }

/* ── Rate cards ── */
.rate-display { padding: 2rem; text-align: center; }
.rate-val {
    font-family: var(--font-serif);
    font-size: 3rem; letter-spacing: -0.03em;
    line-height: 1; margin-bottom: 0.5rem;
}
.rate-lbl { font-size: 0.82rem; color: var(--ink-4); }

/* ── Attendance badges ── */
.attendance-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; padding: 1.25rem 1.5rem; }
.att-tile {
    background: var(--surface-2); border: 1px solid var(--line);
    border-radius: var(--radius-md); padding: 1rem; text-align: center;
}
.att-val { font-family: var(--font-serif); font-size: 1.6rem; color: var(--ink); letter-spacing: -0.02em; }
.att-lbl { font-size: 0.72rem; color: var(--ink-4); margin-top: 3px; }

/* ── Table ── */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    padding: 0.75rem 1.25rem; text-align: left;
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.06em; color: var(--ink-4);
    background: var(--surface-2); border-bottom: 1px solid var(--line);
}
.data-table td {
    padding: 0.9rem 1.25rem; font-size: 0.875rem;
    color: var(--ink-2); border-bottom: 1px solid var(--line);
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: var(--surface-2); }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.22rem 0.65rem; border-radius: 100px;
    font-size: 0.72rem; font-weight: 700;
}
.badge-graded    { background: var(--success-bg); color: var(--success); }
.badge-submitted { background: var(--warning-bg); color: var(--warning); }

.empty-row {
    padding: 2.5rem; text-align: center;
    color: var(--ink-4); font-size: 0.875rem;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <h1 class="page-heading">Statistiques pédagogiques</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-file-description"></i></div>
            <div class="stat-val">{{ $totalTPs }}</div>
            <div class="stat-lbl">Total TPs créés</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="ti ti-world-upload"></i></div>
            <div class="stat-val">{{ $publishedTPs }}</div>
            <div class="stat-lbl">TPs publiés</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon"><i class="ti ti-inbox"></i></div>
            <div class="stat-val">{{ $totalSubmissions }}</div>
            <div class="stat-lbl">Total soumissions</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="ti ti-clock"></i></div>
            <div class="stat-val">{{ $pendingSubmissions }}</div>
            <div class="stat-lbl">À corriger</div>
        </div>
        <div class="stat-card teal">
            <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
            <div class="stat-val">{{ $gradedSubmissions }}</div>
            <div class="stat-lbl">Notés</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon"><i class="ti ti-chart-bar"></i></div>
            <div class="stat-val">{{ $averageGrade ? number_format($averageGrade, 1) : '—' }}</div>
            <div class="stat-lbl">Moyenne générale</div>
        </div>
    </div>

    <div class="two-col">

        {{-- Students per course ── --}}
        <div class="section-card">
                        <div class="section-header">
                <div class="section-title"><i class="ti ti-users"></i> Étudiants par cours</div>
            </div>
            <div class="filter-bar">
                <div class="filter-input-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" class="filter-input" id="student-search"
                           placeholder="Rechercher un cours..." oninput="filterStudentCourse()">
                </div>
                <span class="filter-divider">ou</span>
                <select class="filter-select" id="student-select" onchange="selectStudentCourse(this.value)">
                    <option value="">Tous les cours</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="chart-body" id="students-container">
    @forelse($classes as $class)
        @php
            $maxStudents = $classes->max('students_count') ?: 1;
            $pct = ($class->students_count / $maxStudents) * 100;
        @endphp
        <div class="chart-bar-item" data-class-id="{{ $class->id }}">
            <div class="chart-bar-header">
                <span class="chart-label" title="{{ $class->name }}">{{ $class->name }}</span>
                <span class="chart-count">{{ $class->students_count }} étudiant(s)</span>
            </div>
            <div class="chart-track">
                <div class="chart-fill" style="width:{{ max($pct, 1) }}%"></div>
            </div>
        </div>
    @empty
        <div class="empty-row"><i class="ti ti-mood-empty" style="font-size:1.5rem; display:block; margin-bottom:0.4rem;"></i>Aucun cours</div>
    @endforelse
</div>
        </div>

        {{-- Grade distribution ── --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-title"><i class="ti ti-chart-histogram"></i> Distribution des notes</div>
            </div>
            <div class="filter-bar">
                <div class="filter-input-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" class="filter-input" id="grade-search"
                           placeholder="Rechercher un cours..." oninput="filterGradeCourse()">
                </div>
                <span class="filter-divider">ou</span>
                <select class="filter-select" id="grade-select" onchange="selectGradeCourse(this.value)">
                    <option value="">Tous les cours</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="chart-body" id="grade-chart"></div>
        </div>

    </div>

    {{-- Attendance ── --}}
    @if($attendanceStats->count() > 0)
        <div class="section-card" style="margin-bottom:1.25rem;">
            <div class="section-header">
                <div class="section-title"><i class="ti ti-calendar-stats"></i> Statistiques de présence</div>
            </div>
            <div class="attendance-stats">
                <div class="att-tile">
                    <div class="att-val" style="color:var(--success);">{{ $attendanceStats->get('present', 0) }}</div>
                    <div class="att-lbl">Présents</div>
                </div>
                <div class="att-tile">
                    <div class="att-val" style="color:var(--danger);">{{ $attendanceStats->get('absent', 0) }}</div>
                    <div class="att-lbl">Absents</div>
                </div>
                <div class="att-tile">
                    <div class="att-val" style="color:var(--warning);">{{ $attendanceStats->get('late', 0) }}</div>
                    <div class="att-lbl">Retards</div>
                </div>
                <div class="att-tile">
                    <div class="att-val" style="color:var(--teal);">{{ $attendanceStats->get('excused', 0) }}</div>
                    <div class="att-lbl">Excusés</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent submissions ── --}}
    <div class="section-card" style="margin-bottom:1.25rem;">
        <div class="section-header">
            <div class="section-title"><i class="ti ti-clock-bolt"></i> Soumissions récentes</div>
        </div>
        @if($recentSubmissions->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>TP</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubmissions as $submission)
                        <tr>
                            <td style="font-weight:600; color:var(--ink);">{{ $submission->student->name }}</td>
                            <td>{{ $submission->tp->title }}</td>
                            <td style="color:var(--ink-3);">{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $submission->status }}">
                                    @if($submission->status === 'graded')
                                        <i class="ti ti-circle-check"></i> Noté
                                    @else
                                        <i class="ti ti-clock"></i> En attente
                                    @endif
                                </span>
                            </td>
                            <td style="font-weight:700; color:var(--ink);">
                                {{ $submission->grade ? $submission->grade . ' / 20' : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-row">
                <i class="ti ti-inbox-off" style="font-size:1.5rem; display:block; margin-bottom:0.4rem;"></i>
                Aucune soumission récente
            </div>
        @endif
    </div>

    {{-- Rates ── --}}
    <div class="two-col">
        <div class="section-card">
            <div class="section-header">
                <div class="section-title"><i class="ti ti-trophy"></i> Taux de réussite</div>
            </div>
            @php
                $passRate = $gradedSubmissions > 0
                    ? (($gradeDistribution->get('10-12', 0) + $gradeDistribution->get('12-14', 0) + $gradeDistribution->get('14-16', 0) + $gradeDistribution->get('16-20', 0)) / $gradedSubmissions) * 100
                    : 0;
            @endphp
            <div class="rate-display">
                <div class="rate-val" style="color:{{ $passRate >= 50 ? 'var(--success)' : 'var(--danger)' }};">
                    {{ number_format($passRate, 1) }}%
                </div>
                <div class="rate-lbl">Notes ≥ 10 / 20</div>
            </div>
        </div>
        <div class="section-card">
            <div class="section-header">
                <div class="section-title"><i class="ti ti-writing"></i> Taux de correction</div>
            </div>
            @php
                $correctionRate = $totalSubmissions > 0
                    ? ($gradedSubmissions / $totalSubmissions) * 100
                    : 0;
            @endphp
            <div class="rate-display">
                <div class="rate-val" style="color:{{ $correctionRate >= 75 ? 'var(--success)' : 'var(--warning)' }};">
                    {{ number_format($correctionRate, 1) }}%
                </div>
                <div class="rate-lbl">Soumissions notées</div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-scripts')
<script>
const allDistribution      = @json($gradeDistribution);
const classesList          = @json($classes->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));
const perClassDistribution = @json($gradeDistributionPerClass ?? []);
const ranges               = ['0-10', '10-12', '12-14', '14-16', '16-20'];

function renderChart(distribution) {
    const chart = document.getElementById('grade-chart');
    const max   = Math.max(...ranges.map(r => distribution[r] ?? 0), 1);
    chart.innerHTML = ranges.map(range => {
        const count = distribution[range] ?? 0;
        const pct   = max > 0 ? Math.max((count / max) * 100, count > 0 ? 2 : 0) : 0;
        return `
            <div class="chart-bar-item">
                <div class="chart-bar-header">
                    <span class="chart-label">${range} / 20</span>
                    <span class="chart-count">${count} soumission(s)</span>
                </div>
                <div class="chart-track">
                    <div class="chart-fill" style="width:${pct}%"></div>
                </div>
            </div>`;
    }).join('');
}

renderChart(allDistribution);

function filterGradeCourse() {
    const query  = document.getElementById('grade-search').value.toLowerCase().trim();
    const select = document.getElementById('grade-select');
    Array.from(select.options).forEach(opt => {
        if (!opt.value) return;
        opt.style.display = !query || opt.text.toLowerCase().includes(query) ? '' : 'none';
    });
    const visible = Array.from(select.options).filter(o => o.value && o.style.display !== 'none');
    if (visible.length === 1) { select.value = visible[0].value; selectGradeCourse(visible[0].value); }
    else { select.value = ''; renderChart(allDistribution); }
}

function selectGradeCourse(classId) {
    if (!classId) { renderChart(allDistribution); return; }
    renderChart(perClassDistribution[classId] ?? {});
    document.getElementById('grade-search').value = classesList.find(c => c.id == classId)?.name ?? '';
}

// Student filter functions
function filterStudentCourse() {
    const query = document.getElementById('student-search').value.toLowerCase().trim();
    const select = document.getElementById('student-select');
    Array.from(select.options).forEach(opt => {
        if (!opt.value) return;
        opt.style.display = !query || opt.text.toLowerCase().includes(query) ? '' : 'none';
    });
    const visible = Array.from(select.options).filter(o => o.value && o.style.display !== 'none');
    if (visible.length === 1) {
        select.value = visible[0].value;
        selectStudentCourse(visible[0].value);
    } else {
        select.value = '';
        // Show all students
        document.querySelectorAll('.chart-bar-item[data-class-id]').forEach(el => {
            el.style.display = 'flex';
        });
        document.getElementById('student-search').value = '';
    }
}

function selectStudentCourse(classId) {
    if (!classId) {
        // Show all
        document.querySelectorAll('.chart-bar-item[data-class-id]').forEach(el => {
            el.style.display = 'flex';
        });
        document.getElementById('student-search').value = '';
        return;
    }
    document.querySelectorAll('.chart-bar-item[data-class-id]').forEach(el => {
        el.style.display = (el.dataset.classId == classId) ? 'flex' : 'none';
    });
    document.getElementById('student-search').value = classesList.find(c => c.id == classId)?.name ?? '';
}
</script>
@endsection