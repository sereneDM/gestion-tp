@extends('layouts.app')

@section('title', $course->name)
@section('page-title', $course->name)

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
    --shadow-lg:  0 12px 40px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.05);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 1100px; margin: 0 auto; padding: 0.5rem 0 3rem; }

/* ── Top menu ── */
.top-actions { display: flex; justify-content: flex-end; margin-bottom: 1.5rem; position: relative; }

.menu-wrap { position: relative; }
.menu-btn {
    background: var(--surface);
    border: 1px solid var(--line);
    color: var(--ink-3);
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    transition: background 0.15s, color 0.15s;
}
.menu-btn:hover { background: var(--surface-2); color: var(--ink); }

.page-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 6px); right: 0;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    min-width: 190px;
    z-index: 100;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}
.page-dropdown a,
.page-dropdown button {
    display: flex; align-items: center; gap: 0.5rem;
    width: 100%; text-align: left;
    padding: 0.7rem 1rem;
    background: none; border: none;
    cursor: pointer;
    font-size: 0.85rem;
    font-family: var(--font-body);
    color: var(--ink-2);
    text-decoration: none;
    transition: background 0.15s;
}
.page-dropdown a i,
.page-dropdown button i { font-size: 15px; color: var(--ink-4); }
.page-dropdown a:hover,
.page-dropdown button:hover { background: var(--surface-2); }
.page-dropdown .danger-item { color: var(--danger) !important; }
.page-dropdown .danger-item i { color: var(--danger) !important; }
.page-dropdown .danger-item:hover { background: var(--danger-bg) !important; }
.page-dropdown-divider { height: 1px; background: var(--line); margin: 0.25rem 0; }

/* ── Tabs ── */
.tabs {
    display: flex; gap: 0.25rem;
    margin-bottom: 1.75rem;
    border-bottom: 1px solid var(--line);
}
.tab {
    padding: 0.75rem 1.25rem;
    background: none; border: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    cursor: pointer;
    font-size: 0.875rem; font-weight: 500;
    color: var(--ink-3);
    font-family: var(--font-body);
    display: flex; align-items: center; gap: 0.4rem;
    transition: color 0.15s;
}
.tab i { font-size: 15px; }
.tab:hover { color: var(--ink-2); }
.tab.active { color: var(--accent); border-bottom-color: var(--accent); font-weight: 600; }

.tab-content { display: none; }
.tab-content.active { display: block; }

/* ── Teacher card ── */
.teacher-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.teacher-avatar {
    width: 64px; height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--surface-2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    flex-shrink: 0;
}
.teacher-avatar-placeholder {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-bg), #dde4ff);
    border: 3px solid var(--surface-2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 700;
    color: var(--accent);
    font-family: var(--font-serif);
}
.teacher-info { flex: 1; min-width: 0; }
.teacher-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--ink-4); margin-bottom: 0.3rem;
}
.teacher-name {
    font-size: 1rem; font-weight: 700;
    color: var(--ink); letter-spacing: -0.01em;
}
.teacher-role-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    margin-top: 0.35rem;
    padding: 0.2rem 0.6rem;
    background: var(--accent-bg); color: var(--accent);
    border-radius: 100px;
    font-size: 0.72rem; font-weight: 600;
}
.teacher-role-badge i { font-size: 11px; }
.teacher-you-tag {
    display: inline-flex; align-items: center; gap: 0.3rem;
    margin-top: 0.35rem; margin-left: 0.4rem;
    padding: 0.2rem 0.6rem;
    background: var(--success-bg); color: var(--success);
    border-radius: 100px;
    font-size: 0.72rem; font-weight: 600;
}
.teacher-email-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin-top: 0.5rem;
    font-size: 0.82rem;
    color: var(--ink-3);
    text-decoration: none;
    transition: color 0.15s;
}
.teacher-email-link i { font-size: 13px; }
.teacher-email-link:hover { color: var(--accent); }

/* ── Stat tiles ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.stat-tile {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.stat-tile-val {
    font-family: var(--font-serif);
    font-size: 2rem; font-weight: 700;
    color: var(--ink); line-height: 1;
}
.stat-tile-lbl { font-size: 0.78rem; color: var(--ink-3); margin-top: 0.4rem; }

/* ── Description ── */
.description-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
}
.card-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--ink-4); margin-bottom: 0.6rem;
}
.description-card p { font-size: 0.9rem; color: var(--ink-2); line-height: 1.7; }

/* ── Course PDF ── */
.pdf-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
}
.pdf-card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem;
}
.pdf-card-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--ink-4);
    display: flex; align-items: center; gap: 0.4rem;
}
.pdf-card-label i { font-size: 14px; }
.pdf-download-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.4rem 0.9rem;
    border-radius: var(--radius-sm);
    background: var(--surface-2); border: 1px solid var(--line);
    color: var(--ink-2); font-size: 0.78rem; font-weight: 500;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.pdf-download-btn i { font-size: 13px; }
.pdf-download-btn:hover { background: var(--surface-3); border-color: var(--line-2); }

/* ── Join code — slim inline strip ── */
.join-code-box {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
}
.join-code-left { display: flex; flex-direction: column; gap: 0.25rem; }
.join-code-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--ink-4);
}
.join-code {
    font-size: 1.6rem; font-weight: 700;
    font-family: monospace; letter-spacing: 0.15em;
    color: var(--accent);
}
.join-code-actions { display: flex; gap: 0.6rem; align-items: center; flex-shrink: 0; }
.btn-copy {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--surface-2);
    color: var(--ink-2);
    font-size: 0.82rem; font-weight: 500;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.btn-copy:hover { background: var(--surface-3); border-color: var(--line-2); }
.btn-copy i { font-size: 14px; }
.btn-regen {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid rgba(245,158,11,0.25);
    background: var(--warning-bg);
    color: var(--warning);
    font-size: 0.82rem; font-weight: 600;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.btn-regen:hover { background: #fef3c7; }
.btn-regen i { font-size: 14px; }

/* ── TP grid ── */
.section-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem;
}
.section-heading {
    font-family: var(--font-serif);
    font-size: 1.4rem; color: var(--ink); letter-spacing: -0.01em;
    display: flex; align-items: center; gap: 0.5rem;
}
.count-badge {
    font-family: var(--font-body);
    font-size: 0.72rem; font-weight: 700;
    color: var(--ink-4);
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: 100px;
    padding: 0.1rem 0.5rem;
}
.btn-new {
    display: inline-flex; align-items: center; gap: 0.45rem;
    background: var(--success); color: white;
    padding: 0.55rem 1.1rem;
    border: none; border-radius: var(--radius-md);
    font-size: 0.85rem; font-weight: 600;
    font-family: var(--font-body); cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 2px 8px rgba(16,185,129,0.25);
}
.btn-new:hover { background: #0ea572; transform: translateY(-1px); }
.btn-new i { font-size: 15px; }

.tps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
}
.tp-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.4rem;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm);
    display: flex; flex-direction: column; gap: 0.6rem;
    position: relative; overflow: hidden;
}
.tp-card::before {
    content: "";
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; border-radius: 3px 0 0 3px;
}
.tp-card.status-published::before { background: var(--success); }
.tp-card.status-draft::before     { background: var(--warning); }
.tp-card.status-closed::before    { background: var(--danger);  }
.tp-card:hover { border-color: var(--line-2); box-shadow: var(--shadow-md); transform: translateY(-2px); }

.tp-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
.tp-title {
    font-size: 0.97rem; font-weight: 700;
    color: var(--ink); letter-spacing: -0.01em;
    flex: 1; min-width: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.18rem 0.6rem;
    border-radius: 100px;
    font-size: 0.7rem; font-weight: 700;
    white-space: nowrap; flex-shrink: 0;
}
.badge i { font-size: 11px; }
.badge-published { background: var(--success-bg); color: var(--success); }
.badge-draft     { background: var(--warning-bg); color: var(--warning); }
.badge-closed    { background: var(--danger-bg);  color: var(--danger);  }

.tp-description {
    font-size: 0.83rem; color: var(--ink-3); line-height: 1.55;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; min-height: 2.5rem;
}
.tp-meta {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: var(--ink-4);
}
.tp-meta i { font-size: 13px; }
.tp-footer { display: flex; justify-content: flex-end; margin-top: auto; position: relative; }
.tp-menu-btn {
    background: var(--surface-2);
    border: 1px solid var(--line);
    color: var(--ink-3);
    padding: 0.3rem 0.6rem;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 1rem; line-height: 1;
    display: flex; align-items: center;
    transition: background 0.15s, color 0.15s;
}
.tp-menu-btn:hover { background: var(--surface-3); color: var(--ink); }
.tp-dropdown {
    display: none;
    position: absolute;
    bottom: calc(100% + 4px); right: 0;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    min-width: 160px;
    z-index: 200;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}
.tp-dropdown a,
.tp-dropdown button {
    display: flex; align-items: center; gap: 0.5rem;
    width: 100%; text-align: left;
    padding: 0.7rem 1rem;
    background: none; border: none;
    cursor: pointer; font-size: 0.85rem;
    font-family: var(--font-body);
    color: var(--ink-2); text-decoration: none;
    transition: background 0.15s;
}
.tp-dropdown a i,
.tp-dropdown button i { font-size: 14px; color: var(--ink-4); }
.tp-dropdown a:hover,
.tp-dropdown button:hover { background: var(--surface-2); }
.tp-dropdown .danger-item { color: var(--danger) !important; }
.tp-dropdown .danger-item i { color: var(--danger) !important; }
.tp-dropdown .danger-item:hover { background: var(--danger-bg) !important; }

/* ── Students table ── */
.table-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.students-table { width: 100%; border-collapse: collapse; }
.students-table thead { background: var(--surface-2); }
.students-table th {
    padding: 0.85rem 1.25rem;
    text-align: left;
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-4);
    border-bottom: 1px solid var(--line);
}
.students-table td {
    padding: 0.9rem 1.25rem;
    font-size: 0.875rem; color: var(--ink-2);
    border-bottom: 1px solid var(--line);
}
.students-table tbody tr:last-child td { border-bottom: none; }
.students-table tbody tr:hover { background: var(--surface-2); }

.btn-remove {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--radius-sm);
    border: 1px solid rgba(229,57,53,0.25);
    background: var(--danger-bg);
    color: var(--danger);
    font-size: 0.78rem; font-weight: 600;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s;
}
.btn-remove:hover { background: #ffcdd2; }
.btn-remove i { font-size: 13px; }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    background: var(--surface);
    border: 1px dashed var(--line-2);
    border-radius: var(--radius-xl);
    color: var(--ink-3);
}
.empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface-2); border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem; font-size: 28px; color: var(--ink-4);
}
.empty-state h3 { color: var(--ink-2); font-size: 1rem; font-weight: 600; margin-bottom: 0.4rem; }
.empty-state p  { font-size: 0.875rem; max-width: 280px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="top-actions">
        <div class="menu-wrap">
            <button class="menu-btn" onclick="toggleMenu('course-menu')">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <div class="page-dropdown" id="course-menu">
                <a href="{{ route('teacher.courses.edit', $course->id) }}?from=info">
                    <i class="ti ti-edit"></i> Modifier le cours
                </a>
                <div class="page-dropdown-divider"></div>
                <form method="POST" action="{{ route('teacher.courses.destroy', $course->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="danger-item"
                            onclick="return confirm('Supprimer ce cours? Action irréversible.')">
                        <i class="ti ti-trash"></i> Supprimer le cours
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="tabs">
        <button class="tab active" onclick="switchTab('info', event)">
            <i class="ti ti-info-circle"></i> Informations
        </button>
        <button class="tab" onclick="switchTab('tps', event)">
            <i class="ti ti-file-text"></i> Travaux Pratiques
        </button>
        <button class="tab" onclick="switchTab('students', event)">
            <i class="ti ti-users"></i> Étudiants
        </button>
    </div>

    {{-- Tab: Info --}}
    <div class="tab-content active" id="tab-info">

        {{-- Teacher card (you) at top --}}
        <div class="teacher-card">
            @if(auth()->user()->profile_picture)
                <img
                    src="{{ auth()->user()->profile_picture_url }}"
                    alt="{{ auth()->user()->name }}"
                    class="teacher-avatar">
            @else
                <div class="teacher-avatar-placeholder">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="teacher-info">
                <div class="teacher-label">Enseignant responsable</div>
                <div class="teacher-name">{{ auth()->user()->name }}</div>
                <div class="teacher-role-badge">
                    <i class="ti ti-school"></i> Enseignant
                </div>
                <a href="mailto:{{ auth()->user()->email }}" class="teacher-email-link">
                    <i class="ti ti-mail"></i>
                    {{ auth()->user()->email }}
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stat-grid">
            <div class="stat-tile">
                <div class="stat-tile-val">{{ $course->students->count() }}</div>
                <div class="stat-tile-lbl">Étudiants inscrits</div>
            </div>
            <div class="stat-tile">
                <div class="stat-tile-val">{{ $course->tps->count() }}</div>
                <div class="stat-tile-lbl">Travaux pratiques</div>
            </div>
            <div class="stat-tile">
                <div class="stat-tile-val">{{ $course->tps->where('status', 'published')->count() }}</div>
                <div class="stat-tile-lbl">TP publiés</div>
            </div>
        </div>

        {{-- Description --}}
        @if($course->description)
            <div class="description-card">
                <div class="card-label">Description</div>
                <p>{{ $course->description }}</p>
            </div>
        @endif

        {{-- Course PDF --}}
@if($course->course_pdf)
    <div class="pdf-card">
        <div class="pdf-card-header">
            <div class="pdf-card-label">
                <i class="ti ti-file-type-pdf"></i> Fichier du cours
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <button type="button" class="pdf-download-btn" id="pdf-toggle-btn" onclick="togglePdf()">
                    <i class="ti ti-eye" id="pdf-toggle-icon"></i>
                    <span id="pdf-toggle-label">Afficher</span>
                </button>
                <a href="{{ asset('storage/' . $course->course_pdf) }}"
                   download
                   class="pdf-download-btn">
                    <i class="ti ti-download"></i> Télécharger
                </a>
            </div>
        </div>
        <div id="pdf-viewer" style="display:none; border-top:1px solid var(--line);">
            <iframe
                src="{{ asset('storage/' . $course->course_pdf) }}"
                style="width:100%; height:600px; border:none; display:block;"
                title="Aperçu PDF du cours">
            </iframe>
        </div>
    </div>
@endif

        {{-- Join code — slim inline strip at bottom --}}
        <div class="join-code-box">
            <div class="join-code-left">
                <div class="join-code-label">Code d'accès au cours</div>
                <div class="join-code" id="joinCode">{{ $course->join_code }}</div>
            </div>
            <div class="join-code-actions">
                <button class="btn-copy" onclick="copyJoinCode()">
                    <i class="ti ti-copy"></i> Copier
                </button>
                <form method="POST" action="{{ route('teacher.courses.regenerate-code', $course->id) }}">
                    @csrf
                    <button type="submit" class="btn-regen"
                            onclick="return confirm('Générer un nouveau code? L\'ancien ne fonctionnera plus.')">
                        <i class="ti ti-refresh"></i> Nouveau code
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Tab: TPs --}}
    <div class="tab-content" id="tab-tps">

        <div class="section-topbar">
            <h2 class="section-heading">
                Travaux Pratiques
                <span class="count-badge">{{ $course->tps->count() }}</span>
            </h2>
            <a href="{{ route('teacher.courses.tps.create', $course->id) }}" class="btn-new">
                <i class="ti ti-plus"></i> Créer un TP
            </a>
        </div>

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps->sortBy('created_at') as $tp)
                    <div class="tp-card status-{{ $tp->status }}"
                         onclick="window.location.href='{{ route('teacher.tps.show', $tp->id) }}'">

                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            <span class="badge badge-{{ $tp->status }}">
                                @if($tp->status === 'published') <i class="ti ti-circle-check"></i> Publié
                                @elseif($tp->status === 'draft')  <i class="ti ti-pencil"></i> Brouillon
                                @else                             <i class="ti ti-lock"></i> Fermé
                                @endif
                            </span>
                        </div>

                        <div class="tp-description">
                            @if(filled($tp->description)) {{ $tp->description }}
                            @else <span style="font-style:italic;color:var(--ink-4);">Aucune description</span>
                            @endif
                        </div>

                        <div class="tp-meta">
                            <i class="ti ti-calendar-due"></i>
                            {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Pas d\'échéance' }}
                        </div>
                        <div class="tp-meta">
                            <i class="ti ti-upload"></i>
                            {{ $tp->submissions->count() }} soumission(s)
                        </div>

                        <div class="tp-footer">
                            <button class="tp-menu-btn"
                                    onclick="event.stopPropagation(); toggleTpMenu('tp-menu-{{ $tp->id }}')">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div id="tp-menu-{{ $tp->id }}" class="tp-dropdown">
                                <a href="{{ route('teacher.tps.edit', $tp->id) }}" onclick="event.stopPropagation();">
                                    <i class="ti ti-edit"></i> Modifier
                                </a>
                                <form method="POST" action="{{ route('teacher.tps.destroy', $tp->id) }}"
                                      onclick="event.stopPropagation();">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="danger-item"
                                            onclick="event.stopPropagation(); return confirm('Supprimer ce TP?')">
                                        <i class="ti ti-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-file-off"></i></div>
                <h3>Aucun TP créé</h3>
                <p>Créez votre premier TP pour ce cours.</p>
            </div>
        @endif

    </div>

    {{-- Tab: Students --}}
    <div class="tab-content" id="tab-students">

        <div class="section-topbar">
            <h2 class="section-heading">
                Étudiants inscrits
                <span class="count-badge">{{ $course->students->count() }}</span>
            </h2>
        </div>

        @if($course->students->count() > 0)
            <div class="table-card">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Inscrit le</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($course->students as $student)
                            <tr>
                                <td style="font-weight:600;color:var(--ink);">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('teacher.courses.remove-student', [$course->id, $student->id]) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-remove"
                                                onclick="return confirm('Retirer cet étudiant?')">
                                            <i class="ti ti-user-minus"></i> Retirer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-users-off"></i></div>
                <h3>Aucun étudiant inscrit</h3>
                <p>Partagez le code d'accès avec vos étudiants.</p>
            </div>
        @endif

    </div>

</div>
@endsection

@section('extra-scripts')
<script>
function toggleMenu(id) {
    event.stopPropagation();
    const menu = document.getElementById(id);
    const isOpen = menu.style.display === 'block';
    closeAllMenus();
    menu.style.display = isOpen ? 'none' : 'block';
}

function toggleTpMenu(id) {
    event.stopPropagation();
    const menu = document.getElementById(id);
    const isOpen = menu.style.display === 'block';
    document.querySelectorAll('.tp-dropdown').forEach(m => m.style.display = 'none');
    menu.style.display = isOpen ? 'none' : 'block';
}

function closeAllMenus() {
    document.querySelectorAll('.page-dropdown, .tp-dropdown').forEach(m => m.style.display = 'none');
}

document.addEventListener('click', closeAllMenus);

function copyJoinCode() {
    const code = document.getElementById('joinCode').textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
        const btn = document.querySelector('.btn-copy');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check"></i> Copié !';
        setTimeout(() => btn.innerHTML = original, 2000);
    });
}

function switchTab(tabName, event) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.target.closest('.tab').classList.add('active');
    document.getElementById('tab-' + tabName).classList.add('active');
    history.replaceState(null, null, '?tab=' + tabName);
}

const tabParam = new URLSearchParams(window.location.search).get('tab');
const validTabs = ['info', 'tps', 'students'];
if (tabParam && validTabs.includes(tabParam)) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tabParam).classList.add('active');
    document.querySelectorAll('.tab').forEach(tab => {
        if (tab.getAttribute('onclick')?.includes("'" + tabParam + "'")) tab.classList.add('active');
    });
}
function togglePdf() {
    const viewer = document.getElementById('pdf-viewer');
    const icon   = document.getElementById('pdf-toggle-icon');
    const label  = document.getElementById('pdf-toggle-label');
    const open   = viewer.style.display === 'block';
    viewer.style.display = open ? 'none' : 'block';
    icon.className  = open ? 'ti ti-eye' : 'ti ti-eye-off';
    label.textContent = open ? 'Afficher' : 'Masquer';
}
</script>
@endsection