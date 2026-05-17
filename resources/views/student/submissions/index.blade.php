@extends('layouts.app')

@section('title', 'Mes Soumissions')
@section('page-title', 'Mes Soumissions')

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
    margin-bottom: 1.25rem;
}

/* ── Filter bar ── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 0.85rem 1rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
}

.filter-input-wrap {
    position: relative;
    flex: 1;
    min-width: 180px;
}
.filter-input-wrap i {
    position: absolute;
    left: 10px; top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: var(--ink-4);
    pointer-events: none;
}
.filter-input {
    width: 100%;
    padding: 0.58rem 0.9rem 0.58rem 2.2rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.filter-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
    background: var(--surface);
}
.filter-input::placeholder { color: var(--ink-4); }

.filter-divider { font-size: 0.78rem; color: var(--ink-4); white-space: nowrap; }

.filter-select {
    padding: 0.58rem 2rem 0.58rem 0.85rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    transition: border-color 0.2s;
}
.filter-select:focus { outline: none; border-color: var(--accent); }

/* ── Status filter pills ── */
.status-pills {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    margin-left: auto;
}
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.3rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid var(--line-2);
    background: var(--surface-2);
    color: var(--ink-3);
    cursor: pointer;
    transition: all 0.15s;
}
.status-pill:hover { border-color: var(--line-2); background: var(--surface-3); }
.status-pill.active-all     { background: var(--ink); color: white; border-color: var(--ink); }
.status-pill.active-graded  { background: var(--success-bg); color: var(--success); border-color: rgba(16,185,129,0.3); }
.status-pill.active-pending { background: var(--warning-bg); color: var(--warning); border-color: rgba(245,158,11,0.3); }
.status-pill i { font-size: 12px; }

/* ── Empty / no-results states ── */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border: 1px dashed var(--line-2);
    border-radius: var(--radius-xl);
    color: var(--ink-3);
}
.empty-icon {
    width: 64px; height: 64px;
    border-radius: 18px;
    background: var(--surface-2);
    border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 28px;
    color: var(--ink-4);
}
.empty-state h3 { color: var(--ink-2); font-size: 1rem; font-weight: 600; margin-bottom: 0.4rem; }
.empty-state p  { font-size: 0.875rem; max-width: 300px; margin: 0 auto; }

.no-results {
    display: none;
    text-align: center;
    padding: 3rem 2rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    color: var(--ink-3);
    font-size: 0.875rem;
}

/* ── Course section card ── */
.course-section {
    display: none;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1rem;
}

.course-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    background: var(--surface-2);
}

.course-section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--ink);
}
.course-section-title i { color: var(--accent); font-size: 17px; }

.course-section-meta {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 600;
}
.badge-total   { background: var(--accent-bg);  color: var(--accent); }
.badge-graded  { background: var(--success-bg); color: var(--success); }
.badge-pending { background: var(--warning-bg); color: var(--warning); }

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
    padding: 0.95rem 1.25rem;
    font-size: 0.875rem;
    color: var(--ink-2);
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr { transition: background 0.15s; }
.data-table tbody tr:hover { background: var(--surface-2); }

.tp-title {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.875rem;
}

/* ── Badges ── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.22rem 0.65rem; border-radius: 100px;
    font-size: 0.72rem; font-weight: 700;
}
.badge-graded-status  { background: var(--success-bg); color: var(--success); }
.badge-pending-status { background: var(--warning-bg); color: var(--warning); }

.grade-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.75rem;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: 0.875rem;
}
.grade-good    { background: var(--success-bg); color: var(--success); }
.grade-average { background: var(--warning-bg); color: var(--warning); }
.grade-poor    { background: var(--danger-bg);  color: var(--danger); }

.btn-view {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 0.35rem 0.85rem; border-radius: var(--radius-sm);
    background: var(--accent-bg); color: var(--accent);
    border: 1px solid rgba(61,90,254,0.2);
    font-size: 0.78rem; font-weight: 600;
    text-decoration: none; transition: background 0.15s, transform 0.1s;
}
.btn-view:hover { background: var(--accent); color: white; transform: translateY(-1px); }
.btn-view i { font-size: 13px; }

.no-rows {
    padding: 2rem; text-align: center;
    color: var(--ink-4); font-size: 0.875rem;
}

/* ── Global empty (no submissions at all) ── */
.btn-new {
    display: inline-flex; align-items: center; gap: 0.45rem;
    background: var(--accent); color: white;
    padding: 0.6rem 1.2rem; border: none; border-radius: var(--radius-md);
    font-size: 0.85rem; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
}
.btn-new:hover { background: #5271ff; transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <h1 class="page-heading">Mes soumissions</h1>

    @if($submissions->count() > 0)

        {{-- Group submissions by course --}}
        @php
            $byCourse = $submissions->groupBy(fn($s) => $s->tp->class->name);
        @endphp

        {{-- Filter bar --}}
        <div class="filter-bar">
            <div class="filter-input-wrap">
                <i class="ti ti-search"></i>
                <input type="text" class="filter-input" id="course-search"
                       placeholder="Rechercher un cours..." oninput="filterCourses()">
            </div>

            <span class="filter-divider">ou</span>

            <select class="filter-select" id="course-select" onchange="jumpToCourse(this.value)">
                <option value="">Sélectionner un cours</option>
                @foreach($byCourse as $courseName => $courseSubmissions)
                    <option value="course-{{ Str::slug($courseName) }}">{{ $courseName }}</option>
                @endforeach
            </select>

            <div class="status-pills">
                <button class="status-pill active-all" data-status="all" onclick="setStatus('all', this)">
                    <i class="ti ti-list"></i> Tous
                </button>
                <button class="status-pill" data-status="graded" onclick="setStatus('graded', this)">
                    <i class="ti ti-circle-check"></i> Notés
                </button>
                <button class="status-pill" data-status="pending" onclick="setStatus('pending', this)">
                    <i class="ti ti-clock"></i> En attente
                </button>
            </div>
        </div>

        {{-- Default empty prompt --}}
        <div id="empty-state" class="empty-state">
            <div class="empty-icon"><i class="ti ti-books"></i></div>
            <h3>Sélectionnez un cours</h3>
            <p>Recherchez ou choisissez un cours pour afficher vos soumissions.</p>
        </div>

        <div id="no-results" class="no-results">
            <i class="ti ti-mood-empty" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
            Aucun cours trouvé.
        </div>

        {{-- One card per course --}}
        @foreach($byCourse as $courseName => $courseSubmissions)
            @php
                $slug    = Str::slug($courseName);
                $graded  = $courseSubmissions->filter(fn($s) => $s->grade)->count();
                $pending = $courseSubmissions->filter(fn($s) => !$s->grade)->count();
                $teacher = $courseSubmissions->first()->tp->teacher->name;
            @endphp
            <div class="course-section"
                 id="course-{{ $slug }}"
                 data-course-name="{{ strtolower($courseName) }}">

                <div class="course-section-header">
                    <div class="course-section-title">
                        <i class="ti ti-book"></i>
                        {{ $courseName }}
                        <span style="font-weight:400; color:var(--ink-4); font-size:0.82rem;">
                            · {{ $teacher }}
                        </span>
                    </div>
                    <div class="course-section-meta">
                        <span class="header-badge badge-total">
                            <i class="ti ti-files"></i>
                            {{ $courseSubmissions->count() }} soumission(s)
                        </span>
                        @if($graded > 0)
                            <span class="header-badge badge-graded">
                                <i class="ti ti-circle-check"></i> {{ $graded }} notée(s)
                            </span>
                        @endif
                        @if($pending > 0)
                            <span class="header-badge badge-pending">
                                <i class="ti ti-clock"></i> {{ $pending }} en attente
                            </span>
                        @endif
                    </div>
                </div>

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
                    <tbody data-course-body="{{ $slug }}">
                        @foreach($courseSubmissions as $submission)
                            @php $isGraded = (bool) $submission->grade; @endphp
                            <tr data-status="{{ $isGraded ? 'graded' : 'pending' }}">
                                <td class="tp-title">{{ $submission->tp->title }}</td>
                                <td style="color:var(--ink-3);">
                                    {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                                </td>
                                <td>
                                    @if($isGraded)
                                        <span class="badge badge-graded-status">
                                            <i class="ti ti-circle-check"></i> Noté
                                        </span>
                                    @else
                                        <span class="badge badge-pending-status">
                                            <i class="ti ti-clock"></i> En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($isGraded)
                                        <span class="grade-chip
                                            @if($submission->grade >= 14) grade-good
                                            @elseif($submission->grade >= 10) grade-average
                                            @else grade-poor
                                            @endif">
                                            {{ $submission->grade }} / 20
                                        </span>
                                    @else
                                        <span style="color:var(--ink-4);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.tps.show', $submission->tp->id) }}" class="btn-view">
                                        <i class="ti ti-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="no-rows" id="no-rows-{{ $slug }}" style="display:none;">
                    <i class="ti ti-filter-off" style="font-size:1.3rem; display:block; margin-bottom:0.35rem;"></i>
                    Aucune soumission ne correspond au filtre.
                </div>

            </div>
        @endforeach

    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="ti ti-file-off"></i></div>
            <h3>Aucune soumission</h3>
            <p>Vous n'avez pas encore soumis de travaux pratiques.</p>
            <br>
            <a href="{{ route('student.my-courses') }}" class="btn-new">
                <i class="ti ti-books"></i> Voir mes cours
            </a>
        </div>
    @endif

</div>
@endsection

@section('extra-scripts')
<script>
let activeStatus = 'all';

// ── Search by typing ──
function filterCourses() {
    const query    = document.getElementById('course-search').value.toLowerCase().trim();
    const sections = document.querySelectorAll('.course-section');
    let anyVisible = false;

    if (!query) {
        sections.forEach(s => s.style.display = 'none');
        document.getElementById('no-results').style.display = 'none';
        setEmptyState(true);
        document.getElementById('course-select').value = '';
        return;
    }

    sections.forEach(section => {
        const match = section.dataset.courseName.includes(query);
        section.style.display = match ? 'block' : 'none';
        if (match) { applyStatusFilter(section); anyVisible = true; }
    });

    setEmptyState(false);
    document.getElementById('no-results').style.display = anyVisible ? 'none' : 'block';
    document.getElementById('course-select').value = '';
}

// ── Jump via dropdown ──
function jumpToCourse(id) {
    const sections = document.querySelectorAll('.course-section');
    if (!id) {
        sections.forEach(s => s.style.display = 'none');
        document.getElementById('no-results').style.display = 'none';
        setEmptyState(true);
        document.getElementById('course-search').value = '';
        return;
    }
    sections.forEach(s => s.style.display = 'none');
    document.getElementById('no-results').style.display = 'none';
    setEmptyState(false);
    document.getElementById('course-search').value = '';

    const target = document.getElementById(id);
    if (target) {
        target.style.display = 'block';
        applyStatusFilter(target);
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ── Status pill filter ──
function setStatus(status, btn) {
    activeStatus = status;

    document.querySelectorAll('.status-pill').forEach(p => {
        p.classList.remove('active-all', 'active-graded', 'active-pending');
    });

    if (status === 'all')     btn.classList.add('active-all');
    if (status === 'graded')  btn.classList.add('active-graded');
    if (status === 'pending') btn.classList.add('active-pending');

    document.querySelectorAll('.course-section').forEach(section => {
        if (section.style.display !== 'none') applyStatusFilter(section);
    });
}

function applyStatusFilter(section) {
    const slug    = section.id.replace('course-', '');
    const rows    = section.querySelectorAll('tbody tr');
    const noRows  = document.getElementById('no-rows-' + slug);
    let visible   = 0;

    rows.forEach(row => {
        const show = activeStatus === 'all' || row.dataset.status === activeStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    if (noRows) noRows.style.display = visible === 0 ? 'block' : 'none';
}

function setEmptyState(show) {
    document.getElementById('empty-state').style.display = show ? 'block' : 'none';
}
</script>
@endsection