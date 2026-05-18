@extends('layouts.app')

@section('title', 'Suivi des Étudiants')
@section('page-title', 'Suivi des Étudiants')

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
    --surface-3:  #eef0f3;
    --accent:     #3d5afe;
    --accent-2:   #5271ff;
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

.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.page-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
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

.filter-input {
    flex: 1;
    min-width: 180px;
    padding: 0.55rem 0.9rem 0.55rem 2.2rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.filter-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(61,90,254,0.1); background: var(--surface); }
.filter-input::placeholder { color: var(--ink-4); }

.filter-input-wrap {
    position: relative;
    flex: 1;
    min-width: 180px;
}
.filter-input-wrap i {
    position: absolute;
    left: 9px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: var(--ink-4);
    pointer-events: none;
}

.filter-divider {
    font-size: 0.78rem;
    color: var(--ink-4);
    white-space: nowrap;
}

.filter-select {
    padding: 0.55rem 2rem 0.55rem 0.85rem;
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

/* ── Class section card ── */
.class-section {
    display: none;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1rem;
}

.class-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    background: var(--surface-2);
}

.class-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.class-section-title i { color: var(--accent); font-size: 17px; }

.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 600;
    background: var(--accent-bg);
    color: var(--accent);
}

/* ── Table ── */
.data-table {
    width: 100%;
    border-collapse: collapse;
}
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
.data-table tbody tr {
    transition: background 0.15s;
}
.data-table tbody tr:hover { background: var(--surface-2); }

.student-name { font-weight: 600; color: var(--ink); }
.student-email { font-size: 0.8rem; color: var(--ink-4); margin-top: 2px; }

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.35rem 0.85rem;
    border-radius: var(--radius-sm);
    font-size: 0.78rem;
    font-weight: 600;
    font-family: var(--font-body);
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: background 0.15s, transform 0.1s;
    background: var(--accent-bg);
    color: var(--accent);
    border: 1px solid rgba(61,90,254,0.2);
}
.btn-action:hover { background: var(--accent); color: white; transform: translateY(-1px); }
.btn-action i { font-size: 13px; }

.no-students {
    padding: 2rem;
    text-align: center;
    color: var(--ink-4);
    font-size: 0.875rem;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="topbar">
        <h1 class="page-heading">Suivi des étudiants</h1>
    </div>

    <div class="filter-bar">
        <div class="filter-input-wrap">
            <i class="ti ti-search"></i>
            <input type="text" class="filter-input" id="class-search"
                   placeholder="Rechercher un cours..." oninput="filterClasses()">
        </div>
        <span class="filter-divider">ou</span>
        <select class="filter-select" id="class-select" onchange="jumpToClass(this.value)">
            <option value="">Sélectionner un cours</option>
            @foreach($classes as $class)
                <option value="class-{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="empty-state" class="empty-state">
        <div class="empty-icon"><i class="ti ti-school"></i></div>
        <h3>Sélectionnez un cours</h3>
        <p>Recherchez ou choisissez un cours dans le menu déroulant pour afficher ses étudiants.</p>
    </div>

    <div id="no-results" class="no-results">
        <i class="ti ti-mood-empty" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
        Aucun cours trouvé.
    </div>

    @forelse($classes as $class)
        <div class="class-section" id="class-{{ $class->id }}"
             data-class-name="{{ strtolower($class->name) }}">

            <div class="class-section-header">
                <div class="class-section-title">
                    <i class="ti ti-building"></i>
                    {{ $class->name }}
                </div>
                <span class="class-badge">
                    <i class="ti ti-users"></i>
                    {{ $class->students->count() }} étudiant(s)
                </span>
            </div>

            @if($class->students->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Effectif cours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->students as $student)
                            <tr>
                                <td>
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </td>
                                <td>
                                    <span style="color:var(--ink-3); font-size:0.82rem;">
                                        {{ $class->students->count() }} étudiants
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('teacher.progress.show', $student->id) }}" class="btn-action">
                                        <i class="ti ti-eye"></i> Voir détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-students">
                    <i class="ti ti-user-off" style="font-size:1.5rem; display:block; margin-bottom:0.4rem; color:var(--ink-4);"></i>
                    Aucun étudiant dans ce cours
                </div>
            @endif
        </div>
    @empty
        
    @endforelse

</div>
@endsection

@section('extra-scripts')
<script>
function setEmptyState(show) {
    document.getElementById('empty-state').style.display = show ? 'block' : 'none';
}

function filterClasses() {
    const query    = document.getElementById('class-search').value.toLowerCase().trim();
    const sections = document.querySelectorAll('.class-section[data-class-name]');
    let anyVisible = false;

    if (!query) {
        sections.forEach(s => s.style.display = 'none');
        document.getElementById('no-results').style.display = 'none';
        setEmptyState(true);
        document.getElementById('class-select').value = '';
        return;
    }

    sections.forEach(section => {
        const match = section.dataset.className.includes(query);
        section.style.display = match ? 'block' : 'none';
        if (match) anyVisible = true;
    });

    setEmptyState(false);
    document.getElementById('no-results').style.display = anyVisible ? 'none' : 'block';
    document.getElementById('class-select').value = '';
}

function jumpToClass(id) {
    const sections = document.querySelectorAll('.class-section[data-class-name]');
    if (!id) {
        sections.forEach(s => s.style.display = 'none');
        document.getElementById('no-results').style.display = 'none';
        setEmptyState(true);
        document.getElementById('class-search').value = '';
        return;
    }
    sections.forEach(s => s.style.display = 'none');
    document.getElementById('no-results').style.display = 'none';
    setEmptyState(false);
    document.getElementById('class-search').value = '';
    const target = document.getElementById(id);
    if (target) { target.style.display = 'block'; target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
}
</script>
@endsection