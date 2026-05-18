@extends('layouts.app')

@section('title', 'Mes Cours')
@section('page-title', 'Mes Cours')

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
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --warning:    #f59e0b;
    --warning-bg: #fffbeb;
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

.page-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0.5rem 0 3rem;
}

/* ── Top bar ── */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.page-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
    
}

.btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: var(--accent);
    color: white;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 600;
    font-family: var(--font-body);
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    position: relative;
    overflow: hidden;
}
.btn-new::after {
    content: "";
    position: absolute;
    top: 0; left: -60%;
    width: 40%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transform: skewX(-20deg);
    animation: shimmer 3s infinite;
}
@keyframes shimmer {
    0%, 60% { left: -60%; }
    80%, 100% { left: 120%; }
}
.btn-new:hover { background: var(--accent-2); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(61,90,254,0.35); }
.btn-new i { font-size: 16px; }

/* ── Grid ── */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}

/* ── Card ── */
.course-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.course-card::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--accent);
    border-radius: 3px 0 0 3px;
}

.course-card.archived::before { background: var(--ink-4); }

.course-card:hover {
    border-color: var(--line-2);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

/* card header */
.course-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
}

.course-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.course-code {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--accent-bg);
    color: var(--accent);
    border: 1px solid rgba(61,90,254,0.2);
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-family: monospace;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background 0.15s;
}
.course-code:hover { background: rgba(61,90,254,0.15); }

/* description */
.course-description {
    font-size: 0.85rem;
    color: var(--ink-3);
    line-height: 1.55;
    min-height: 2.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* footer row */
.course-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.75rem;
    border-top: 1px solid var(--line);
    margin-top: auto;
}

.course-meta-pills {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-wrap: wrap;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 500;
    color: var(--ink-3);
    background: var(--surface-2);
    border: 1px solid var(--line);
}
.pill i { font-size: 12px; }

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 700;
}
.status-active   { background: var(--success-bg); color: var(--success); }
.status-archived { background: var(--surface-3);  color: var(--ink-4);   }

/* ── Empty state ── */
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
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 28px;
    color: var(--ink-4);
}
.empty-state h3 { color: var(--ink-2); font-size: 1rem; font-weight: 600; margin-bottom: 0.4rem; }
.empty-state p  { font-size: 0.875rem; max-width: 280px; margin: 0 auto 1.5rem; }
</style>
@endsection

@section('content')

<div class="page-wrapper">

    <div class="topbar">
        <h1 class="page-heading">Mes cours</h1>
        <a href="{{ route('teacher.courses.create') }}" class="btn-new">
            <i class="ti ti-plus"></i> Créer un cours
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
                <div class="course-card {{ $course->status === 'archived' ? 'archived' : '' }}"
                     onclick="window.location.href='{{ route('teacher.courses.show', $course->id) }}'">

                    <div class="course-header">
                        <div class="course-name">{{ $course->name }}</div>
                        <div class="course-code"
                             title="Cliquer pour copier"
                             onclick="event.stopPropagation(); copyCode(this, '{{ $course->join_code }}')">
                            <i class="ti ti-copy" style="font-size:11px;"></i>
                            {{ $course->join_code }}
                        </div>
                    </div>

                    <div class="course-description">
                        @if($course->description)
                            {{ $course->description }}
                        @else
                            <span style="font-style:italic;color:var(--ink-4);">Aucune description</span>
                        @endif
                    </div>

                    <div class="course-footer">
                        <div class="course-meta-pills">
                            <span class="pill">
                                <i class="ti ti-users"></i>
                                {{ $course->students_count }} étudiant(s)
                            </span>
                        </div>
                        <span class="status-badge status-{{ $course->status }}">
                            @if($course->status === 'active')
                                <i class="ti ti-circle-filled" style="font-size:8px;"></i> Actif
                            @else
                                <i class="ti ti-archive" style="font-size:11px;"></i> Archivé
                            @endif
                        </span>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="ti ti-book"></i>
            </div>
            <h3>Aucun cours créé</h3>
            <p>Créez votre premier cours pour commencer à enseigner.</p>
            
        </div>
    @endif

</div>

@endsection

@section('extra-scripts')
<script>
function copyCode(el, code) {
    navigator.clipboard.writeText(code).then(() => {
        showToast('✓ Code copié : ' + code);
    });
}
</script>
@endsection