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
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --info:       #0ea5e9;
    --info-bg:    #f0f9ff;
    --warning:    #f59e0b;
    --warning-bg: #fffbeb;
    --danger:     #ef4444;
    --danger-bg:  #fef2f2;
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

/* ── Buttons ── */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    padding: 0.6rem 1.2rem;
    text-align: center;
    text-decoration: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-family: var(--font-body);
    font-size: 0.85rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}
.btn-primary { background: var(--accent); color: white; box-shadow: 0 2px 8px rgba(61,90,254,0.3); }
.btn-primary:hover { background: var(--accent-2); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(61,90,254,0.35); }
.btn-success { background: var(--success); color: white; box-shadow: 0 2px 8px rgba(16,185,129,0.3); }
.btn-success:hover { background: #059669; transform: translateY(-1px); }
.btn-info { background: var(--info); color: white; box-shadow: 0 2px 8px rgba(14,165,233,0.3); }
.btn-info:hover { background: #0284c7; transform: translateY(-1px); }
.btn-danger { background: var(--danger-bg); color: var(--danger); }
.btn-danger:hover { background: #fee2e2; }

/* ── Top bar ── */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.topbar-title {
    font-size: 1.55rem;
    font-family: var(--font-serif);
    font-weight: 400;
    color: var(--ink);
    letter-spacing: -0.01em;
    line-height: 1.2;
}

/* ── Tabs ── */
.tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--line);
}
.tab {
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--ink-3);
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    font-family: var(--font-body);
}
.tab:hover { color: var(--ink); }
.tab.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
    font-weight: 600;
}
.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

/* resume tab helpers */
.spin { animation: spin 1s linear infinite; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* ── Teacher card (now at top) ── */
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
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--surface-2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    flex-shrink: 0;
}

.teacher-avatar-placeholder {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-bg), #dde4ff);
    border: 3px solid var(--surface-2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--accent);
    font-family: var(--font-serif);
}

.teacher-info { flex: 1; min-width: 0; }

.teacher-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--ink-4);
    margin-bottom: 0.3rem;
}

.teacher-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.teacher-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.35rem;
    padding: 0.2rem 0.6rem;
    background: var(--accent-bg);
    color: var(--accent);
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 600;
}
.teacher-role-badge i { font-size: 11px; }

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

/* ── Stats ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.info-card {
    background: var(--surface);
    padding: 1.5rem;
    border-radius: var(--radius-xl);
    text-align: center;
    border: 1px solid var(--line);
    box-shadow: var(--shadow-sm);
}
.info-number {
    font-size: 2.25rem;
    font-family: var(--font-serif);
    color: var(--accent);
    line-height: 1.2;
}
.info-label {
    color: var(--ink-3);
    margin-top: 0.25rem;
    font-size: 0.85rem;
    font-weight: 500;
}

/* ── Description ── */
.course-desc-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
}
.course-desc-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink-4);
    margin-bottom: 0.75rem;
    font-weight: 600;
}
.course-desc-body {
    color: var(--ink-2);
    font-size: 0.95rem;
    line-height: 1.6;
}

/* ── Join code box (now at bottom) ── */
.join-code-box {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 1.75rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
}
.join-code-left { display: flex; flex-direction: column; gap: 0.3rem; }
.join-code-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink-4);
}
.join-code {
    font-size: 1.6rem;
    font-weight: 700;
    font-family: monospace;
    letter-spacing: 0.15em;
    color: var(--accent);
}
.btn-copy {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--surface-2);
    color: var(--ink-2);
    font-size: 0.82rem;
    font-weight: 500;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
    flex-shrink: 0;
}
.btn-copy:hover { background: var(--surface-3); border-color: var(--line-2); }
.btn-copy i { font-size: 14px; }

/* ── TP grid ── */
.tps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.25rem;
    margin-top: 1.5rem;
}
.tp-card {
    background: var(--surface);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    border: 1px solid var(--line);
    cursor: pointer;
    transition: transform 0.15s, border-color 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    min-height: 220px;
    box-shadow: var(--shadow-sm);
}
.tp-card:hover {
    transform: translateY(-2px);
    border-color: var(--line-2);
    box-shadow: var(--shadow-md);
}
.tp-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    gap: 0.75rem;
}
.tp-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ink);
    flex: 1;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.3rem 0.6rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}
.status-badge i { font-size: 14px; }
.status-pending   { background: var(--warning-bg); color: var(--warning); border: 1px solid rgba(245,158,11,0.2); }
.status-submitted { background: var(--success-bg); color: var(--success); border: 1px solid rgba(16,185,129,0.2); }
.status-graded    { background: var(--info-bg); color: var(--info); border: 1px solid rgba(14,165,233,0.2); }

.tp-description {
    color: var(--ink-3);
    font-size: 0.85rem;
    line-height: 1.55;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6rem;
}
.tp-meta {
    font-size: 0.8rem;
    color: var(--ink-3);
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.tp-meta i { font-size: 14px; color: var(--ink-4); }
.tp-grade {
    background: var(--info-bg);
    color: var(--info);
    padding: 0.3rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
    align-self: flex-start;
}
.tp-spacer { flex: 1; }

/* ── 3-dots menu ── */
.course-menu-btn {
    background: var(--surface);
    border: 1px solid var(--line);
    color: var(--ink-3);
    width: 36px;
    height: 36px;
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
    box-shadow: var(--shadow-sm);
}
.course-menu-btn:hover { background: var(--surface-2); color: var(--ink); }

.course-menu-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    min-width: 180px;
    z-index: 100;
    box-shadow: var(--shadow-md);
    padding: 0.5rem;
}
.course-menu-dropdown button {
    width: 100%;
    text-align: left;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    color: var(--danger);
    cursor: pointer;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    font-family: var(--font-body);
    transition: background 0.15s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.course-menu-dropdown button i { font-size: 16px; }
.course-menu-dropdown button:hover { background: var(--danger-bg); }

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border-radius: var(--radius-xl);
    color: var(--ink-3);
    border: 1px dashed var(--line-2);
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
.empty-state p  { font-size: 0.875rem; max-width: 320px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    {{-- Course header --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">{{ $course->name }}</h1>
        </div>
        <div style="position:relative;">
            <button class="course-menu-btn" onclick="toggleCourseMenu()">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <div class="course-menu-dropdown" id="course-menu">
                <form method="POST" action="{{ route('student.leave-course', $course->id) }}" style="display:block; width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <i class="ti ti-door-exit"></i> Quitter le cours
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab active" onclick="switchTab('info', event)">
            <i class="ti ti-info-circle" style="margin-right:0.4rem; vertical-align:-2px;"></i> Informations
        </button>
        <button class="tab" onclick="switchTab('tps', event)">
            <i class="ti ti-file-text" style="margin-right:0.4rem; vertical-align:-2px;"></i> Travaux Pratiques
        </button>
        <button class="tab" onclick="switchTab('resume', event)">
            <i class="ti ti-brain" style="margin-right:0.4rem; vertical-align:-2px;"></i> Résumé IA
        </button>
    </div>

    {{-- Tab: Info --}}
    <div class="tab-content active" id="tab-info">

        {{-- Teacher card — top --}}
        @php $teacher = $course->teacher; @endphp
        <div class="teacher-card">

            @if($teacher->profile_picture)
                <img
                    src="{{ $teacher->profile_picture_url }}"
                    alt="{{ $teacher->name }}"
                    class="teacher-avatar">
            @else
                <div class="teacher-avatar-placeholder">
                    {{ mb_strtoupper(mb_substr($teacher->name, 0, 1)) }}
                </div>
            @endif

            <div class="teacher-info">
                <div class="teacher-label">Enseignant responsable</div>
                <div class="teacher-name">{{ $teacher->name }}</div>
                <div class="teacher-role-badge">
                    <i class="ti ti-school"></i> Enseignant
                </div>
                @if($teacher->show_email_publicly)
                    <a href="mailto:{{ $teacher->email }}" class="teacher-email-link">
                        <i class="ti ti-mail"></i>
                        {{ $teacher->email }}
                    </a>
                @endif
            </div>

        </div>

        {{-- Stats --}}
        <div class="info-grid">
            <div class="info-card">
                <div class="info-number">{{ $course->tps->count() }}</div>
                <div class="info-label">Travaux pratiques</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->count() }}</div>
                <div class="info-label">Mes soumissions</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->filter(fn($s) => $s->grade !== null)->count() }}</div>
                <div class="info-label">TP notés</div>
            </div>
        </div>

        {{-- Description --}}
        @if($course->description)
            <div class="course-desc-card">
                <div class="course-desc-title">Description du cours</div>
                <div class="course-desc-body">{{ $course->description }}</div>
            </div>
        @endif

        {{-- Course PDF --}}
@if($course->course_pdf)
    <div class="course-desc-card" style="padding:0; overflow:hidden;">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--line); display:flex; align-items:center; justify-content:space-between; gap:1rem;">
            <div class="course-desc-title" style="margin:0; display:flex; align-items:center; gap:0.4rem;">
                <i class="ti ti-file-type-pdf"></i> Fichier du cours
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <button type="button" class="btn-copy" id="pdf-toggle-btn" onclick="togglePdf()">
                    <i class="ti ti-eye" id="pdf-toggle-icon"></i>
                    <span id="pdf-toggle-label">Afficher</span>
                </button>
                <a href="{{ asset('storage/' . $course->course_pdf) }}"
                   download
                   class="btn-copy">
                    <i class="ti ti-download"></i> Télécharger
                </a>
            </div>
        </div>
        <div id="pdf-viewer" style="display:none;">
            <iframe
                src="{{ asset('storage/' . $course->course_pdf) }}"
                style="width:100%; height:600px; border:none; display:block;"
                title="Aperçu PDF du cours">
            </iframe>
        </div>
    </div>
@endif

        {{-- Join code — bottom, inline layout --}}
        <div class="join-code-box">
            <div class="join-code-left">
                <div class="join-code-label">Code d'accès au cours</div>
                <div class="join-code" id="joinCode">{{ $course->join_code }}</div>
            </div>
            <button class="btn-copy" onclick="copyJoinCode()">
                <i class="ti ti-copy"></i> Copier le code
            </button>
        </div>

    </div>

    {{-- Tab: TPs --}}
    <div class="tab-content" id="tab-tps">

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps as $tp)
                    @php
                        $submission   = $submissions->get($tp->id);
                        $hasSubmitted = $submission !== null;
                        $isGraded     = $hasSubmitted && $submission->grade !== null;
                    @endphp

                    <div class="tp-card" onclick="window.location.href='{{ route('student.tps.show', $tp->id) }}'">

                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            @if($isGraded)
                                <span class="status-badge status-graded"><i class="ti ti-check"></i> Noté</span>
                            @elseif($hasSubmitted)
                                <span class="status-badge status-submitted"><i class="ti ti-upload"></i> Soumis</span>
                            @else
                                <span class="status-badge status-pending"><i class="ti ti-clock"></i> À faire</span>
                            @endif
                        </div>

                        <div class="tp-description">
                            @if(filled($tp->description))
                                {{ $tp->description }}
                            @else
                                <span style="font-style:italic; color:var(--ink-4);">Aucune description</span>
                            @endif
                        </div>

                        <div class="tp-meta">
                            <i class="ti ti-calendar"></i>
                            Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Non définie' }}
                        </div>

                        @if($hasSubmitted)
                            <div class="tp-meta">
                                <i class="ti ti-upload"></i>
                                Soumis le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                            </div>
                        @endif

                        @if($isGraded)
                            <div class="tp-grade">
                                <i class="ti ti-award"></i> {{ $submission->grade }}/20
                            </div>
                        @endif

                        <div class="tp-spacer"></div>

                        <button class="btn {{ $isGraded ? 'btn-info' : ($hasSubmitted ? 'btn-success' : 'btn-primary') }}" style="width:100%;">
                            @if($isGraded)
                                <i class="ti ti-eye"></i> Voir ma note
                            @elseif($hasSubmitted)
                                <i class="ti ti-eye"></i> Voir ma soumission
                            @else
                                <i class="ti ti-arrow-right"></i> Voir et soumettre
                            @endif
                        </button>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-file-text"></i></div>
                <h3>Aucun TP disponible</h3>
                <p>Votre enseignant n'a pas encore publié de travaux pratiques.</p>
            </div>
        @endif

    </div>

    {{-- Tab: Résumé IA --}}
    <div class="tab-content" id="tab-resume">

        <div class="course-desc-card">
            <div class="course-desc-title">Résumé IA</div>
            <div style="margin-top:10px; display:flex; flex-direction:column; gap:12px;">

                {{-- Hidden real file input --}}
                <input type="file" id="resume-pdf-input" name="pdf" accept="application/pdf" style="display:none;">

                {{-- Drop zone / file selector --}}
                <div id="resume-dropzone" onclick="document.getElementById('resume-pdf-input').click()" style="border:2px dashed var(--line-2); border-radius:var(--radius-lg); padding:2rem 1rem; text-align:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; background:var(--surface-2);">
                    <i class="ti ti-file-type-pdf" style="font-size:2.2rem; color:var(--accent); display:block; margin-bottom:0.5rem;"></i>
                    <div id="resume-file-name" style="font-weight:600; color:var(--ink-2); margin-bottom:0.25rem;">Cliquer pour choisir un PDF</div>
                    <div style="font-size:0.8rem; color:var(--ink-4);">Format accepté : PDF — max 20 Mo</div>
                </div>

                {{-- Optional query --}}
                <div>
                    <label style="font-size:0.82rem; font-weight:600; color:var(--ink-3); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:.04em;">Requête (optionnelle)</label>
                    <input type="text" id="resume-query" placeholder="Ex: expliquer les formules clés" style="width:100%; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--line-2); font-family:var(--font-body); font-size:0.9rem;">
                </div>

                {{-- Buttons --}}
                <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    <button type="button" class="btn btn-primary" id="resume-submit">
                        <i class="ti ti-brain" id="resume-submit-icon"></i> Analyser le PDF
                    </button>
                    @if(!empty($course->course_doc_id))
                        <button type="button" class="btn btn-info" id="resume-use-uploaded" data-docid="{{ $course->course_doc_id }}">
                            <i class="ti ti-cloud-upload"></i> Utiliser le PDF du cours
                        </button>
                    @endif
                </div>

                <div id="resume-error" style="display:none; color:var(--danger); font-weight:600; font-size:0.88rem;"></div>

                {{-- Result area — single unified card --}}
                <div id="resume-result" style="display:none; margin-top:12px;">
                    <div class="course-desc-card" style="padding:0; overflow:hidden;">

                        {{-- Header with title + overview --}}
                        <div style="padding:1.5rem 1.75rem 1.25rem; border-bottom:1px solid var(--line);">
                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.6rem;">
                                <i class="ti ti-sparkles" style="color:var(--accent); font-size:1.1rem;"></i>
                                <h3 id="resume-title" style="font-size:1.15rem; font-weight:700; color:var(--ink); margin:0;"></h3>
                            </div>
                            <p id="resume-overview" style="color:var(--ink-2); font-size:0.92rem; line-height:1.65; margin:0;"></p>
                        </div>

                        {{-- Chapters --}}
                        <div id="resume-chapters"></div>

                        {{-- Key terms --}}
                        <div id="resume-terms"></div>

                        {{-- Formulas --}}
                        <div id="resume-formulas"></div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection

@section('extra-scripts')
<script>
    function toggleCourseMenu() {
        const menu = document.getElementById('course-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.course-menu-btn') && !e.target.closest('#course-menu')) {
            const menu = document.getElementById('course-menu');
            if (menu) menu.style.display = 'none';
        }
    });

    // Holds the active AbortController for the AI summarization fetch so it
    // can be cancelled if the user navigates away before it completes.
    let resumeAbortController = null;

    function cancelResumeIfRunning() {
        if (resumeAbortController) {
            resumeAbortController.abort();
            resumeAbortController = null;
        }
    }

    function switchTab(tabName, event) {
        // Cancel any in-flight AI request when leaving the resume tab
        if (tabName !== 'resume') cancelResumeIfRunning();
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        event.target.closest('.tab').classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
        history.replaceState(null, null, '#' + tabName);
    }

    // Abort on full page navigation (back/forward, address bar, refresh, etc.)
    window.addEventListener('beforeunload', cancelResumeIfRunning);

    // Abort when the user clicks ANY link that navigates away
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        // Ignore anchors, javascript:, mailto:, and target=_blank tabs
        if (!href || href.startsWith('#') || href.startsWith('javascript:') ||
            href.startsWith('mailto:') || link.target === '_blank') return;
        cancelResumeIfRunning();
    }, true); // capture phase — fires before any child stopPropagation

    // Abort when any form is submitted (navigates away)
    document.addEventListener('submit', function (e) {
        // Don't abort for the summarize upload form itself
        const action = e.target.action || '';
        if (action.includes('/summarize/')) return;
        cancelResumeIfRunning();
    }, true);

    document.addEventListener('DOMContentLoaded', function () {
        const fragment = window.location.hash.replace('#', '');
        const validTabs = ['info', 'tps', 'resume'];
        if (fragment && validTabs.includes(fragment)) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + fragment).classList.add('active');
            document.querySelectorAll('.tab').forEach(t => {
                if (t.getAttribute('onclick') && t.getAttribute('onclick').includes("'" + fragment + "'")) {
                    t.classList.add('active');
                }
            });
        }

        // Small helper to escape HTML when rendering results
        function escapeHtml(str) {
            if (!str && str !== 0) return '';
            return String(str).replace(/[&<>"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[s];
            });
        }

        // ── Résumé IA ──────────────────────────────────────────────────────────
        const resumeEndpoint = '/student/summarize/upload';
        const csrfToken      = '{{ csrf_token() }}';
        const pdfInput       = document.getElementById('resume-pdf-input');
        const dropzone       = document.getElementById('resume-dropzone');
        const fileNameEl     = document.getElementById('resume-file-name');
        const submitBtn      = document.getElementById('resume-submit');
        const submitIcon     = document.getElementById('resume-submit-icon');
        const errorEl        = document.getElementById('resume-error');
        const resultEl       = document.getElementById('resume-result');
        const chaptersEl     = document.getElementById('resume-chapters');
        const termsEl        = document.getElementById('resume-terms');
        const formulasEl     = document.getElementById('resume-formulas');
        const titleEl        = document.getElementById('resume-title');
        const overviewEl     = document.getElementById('resume-overview');

        // Step 1 – button opens picker if no file chosen yet, or re-runs analysis if one is
        if (submitBtn) {
            submitBtn.addEventListener('click', () => {
                if (pdfInput?.files?.length) {
                    sendResume();
                } else {
                    pdfInput && pdfInput.click();
                }
            });
        }

        // Step 2 – when a file is chosen, update the UI and enable the button
        if (pdfInput) {
            pdfInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    const name = this.files[0].name;
                    if (fileNameEl) fileNameEl.textContent = name;
                    if (dropzone) {
                        dropzone.style.borderColor = 'var(--accent)';
                        dropzone.style.background  = 'var(--accent-bg)';
                    }
                    if (submitBtn) submitBtn.disabled = false;
                    // auto-start the summarization
                    sendResume();
                }
            });
        }

        // Shared helper to reset & render results
        function clearResults() {
            if (errorEl)   { errorEl.style.display = 'none'; errorEl.textContent = ''; }
            if (resultEl)  { resultEl.style.display = 'none'; }
            if (chaptersEl) chaptersEl.innerHTML = '';
            if (termsEl)    termsEl.innerHTML = '';
            if (formulasEl) formulasEl.innerHTML = '';
            if (titleEl)    titleEl.textContent = '';
            if (overviewEl) overviewEl.textContent = '';
        }

        function renderResults(data) {
            if (!resultEl) return;

            // Unwrap data if it's inside a 'result' property
            let finalData = data;
            if (data.result && typeof data.result === 'object') {
                finalData = data.result;
            } else if (typeof data.result === 'string') {
                try {
                    finalData = JSON.parse(data.result);
                } catch (e) {
                    console.error("Failed to parse nested result string", e);
                }
            }

            // ── Title & Overview ──
            if (titleEl) titleEl.textContent = finalData.title || 'Résumé du document';
            if (overviewEl) overviewEl.textContent = finalData.overview || '';

            // ── Chapters — numbered sections inside the same card ──
            if (Array.isArray(finalData.chapters) && finalData.chapters.length && chaptersEl) {
                let html = '<div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--line);">' +
                    '<div style="display:flex; align-items:center; gap:0.45rem; margin-bottom:1rem;">' +
                    '<i class="ti ti-list-details" style="color:var(--accent); font-size:1rem;"></i>' +
                    '<span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--ink-4);">Chapitres</span></div>';

                finalData.chapters.forEach((ch, idx) => {
                    const isLast = idx === finalData.chapters.length - 1;
                    html += '<div style="display:flex; gap:1rem; ' + (isLast ? '' : 'margin-bottom:1.25rem; padding-bottom:1.25rem; border-bottom:1px dashed var(--line);') + '">' +
                        '<div style="flex-shrink:0; width:28px; height:28px; border-radius:50%; background:var(--accent-bg); color:var(--accent); font-size:0.78rem; font-weight:700; display:flex; align-items:center; justify-content:center; margin-top:2px;">' + (idx + 1) + '</div>' +
                        '<div style="flex:1; min-width:0;">' +
                        '<div style="font-size:0.98rem; font-weight:700; color:var(--ink); margin-bottom:0.3rem;">' + escapeHtml(ch.title || '') + '</div>' +
                        '<p style="color:var(--ink-2); font-size:0.88rem; line-height:1.6; margin:0 0 0.5rem;">' + escapeHtml(ch.summary || '') + '</p>';
                    if (Array.isArray(ch.key_concepts) && ch.key_concepts.length) {
                        html += '<div style="display:flex; flex-wrap:wrap; gap:0.35rem; margin-top:0.4rem;">' +
                            ch.key_concepts.map(k => '<span style="display:inline-block; padding:0.2rem 0.6rem; background:var(--surface-2); border:1px solid var(--line); border-radius:100px; font-size:0.76rem; color:var(--ink-3); font-weight:500;">' + escapeHtml(k) + '</span>').join('') +
                            '</div>';
                    }
                    html += '</div></div>';
                });
                html += '</div>';
                chaptersEl.innerHTML = html;
            }

            // ── Key Terms — compact grid ──
            if (finalData.key_terms && Object.keys(finalData.key_terms).length && termsEl) {
                const entries = Object.entries(finalData.key_terms);
                let html = '<div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--line);">' +
                    '<div style="display:flex; align-items:center; gap:0.45rem; margin-bottom:1rem;">' +
                    '<i class="ti ti-vocabulary" style="color:var(--accent); font-size:1rem;"></i>' +
                    '<span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--ink-4);">Termes clés</span></div>' +
                    '<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:0.75rem;">';
                entries.forEach(([term, def]) => {
                    html += '<div style="padding:0.75rem 1rem; background:var(--surface-2); border-radius:var(--radius-md); border:1px solid var(--line);">' +
                        '<div style="font-size:0.85rem; font-weight:700; color:var(--accent); margin-bottom:0.2rem;">' + escapeHtml(term) + '</div>' +
                        '<div style="font-size:0.82rem; color:var(--ink-3); line-height:1.5;">' + escapeHtml(def) + '</div></div>';
                });
                html += '</div></div>';
                termsEl.innerHTML = html;
            }

            // ── Formulas ──
            if (Array.isArray(finalData.formulas) && finalData.formulas.length && formulasEl) {
                let html = '<div style="padding:1.25rem 1.75rem;">' +
                    '<div style="display:flex; align-items:center; gap:0.45rem; margin-bottom:1rem;">' +
                    '<i class="ti ti-math-function" style="color:var(--accent); font-size:1rem;"></i>' +
                    '<span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--ink-4);">Formules</span></div>' +
                    '<div style="display:flex; flex-direction:column; gap:0.5rem;">';
                finalData.formulas.forEach(f => {
                    html += '<pre style="margin:0; background:var(--surface-2); padding:0.75rem 1rem; border-radius:var(--radius-md); font-family:\'DM Mono\', monospace; font-size:0.84rem; color:var(--ink-2); border:1px solid var(--line); overflow-x:auto; white-space:pre-wrap; word-break:break-word;">' + escapeHtml(f) + '</pre>';
                });
                html += '</div></div>';
                formulasEl.innerHTML = html;
            }

            resultEl.style.display = 'block';
        }

        // Core fetch function used by both flows
        async function doFetch(body, isJson) {
            cancelResumeIfRunning();
            clearResults();
            if (submitBtn) submitBtn.disabled = true;
            if (submitIcon) submitIcon.classList.add('spin');

            resumeAbortController = new AbortController();
            const { signal } = resumeAbortController;

            const headers = { 'X-CSRF-TOKEN': csrfToken };
            if (isJson) {
                headers['Content-Type'] = 'application/json';
            }

            try {
                const res = await fetch(resumeEndpoint, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers,
                    body,
                    signal,
                });
                if (!res.ok) {
                    let msg = res.statusText;
                    try { const j = await res.json(); msg = j.error || msg; } catch (_) {}
                    throw new Error(msg);
                }
                const data = await res.json();
                console.log('AI Response Data:', data);
                renderResults(data);
            } catch (err) {
                if (err.name === 'AbortError') return;
                console.error(err);
                if (errorEl) {
                    errorEl.textContent = err.message || 'Erreur réseau. Vérifiez que le service IA est démarré.';
                    errorEl.style.display = 'block';
                }
            } finally {
                resumeAbortController = null;
                if (submitBtn) submitBtn.disabled = !pdfInput?.files?.length;
                if (submitIcon) submitIcon.classList.remove('spin');
            }
        }

        // Flow A – student uploads their own PDF
        function sendResume() {
            if (!pdfInput || !pdfInput.files.length) return;
            const fd = new FormData();
            fd.append('pdf', pdfInput.files[0]);
            const q = document.getElementById('resume-query');
            if (q && q.value.trim()) fd.append('query', q.value.trim());
            doFetch(fd, false);
        }

        // Flow B – use the teacher-uploaded course PDF
        const useBtn = document.getElementById('resume-use-uploaded');
        if (useBtn) {
            useBtn.addEventListener('click', function () {
                const docId = this.dataset.docid;
                const q = document.getElementById('resume-query');
                doFetch(JSON.stringify({ doc_id: docId, query: q ? q.value.trim() : '' }), true);
            });
        }
    });

    function copyJoinCode() {
        const code = document.getElementById('joinCode').textContent.trim();
        navigator.clipboard.writeText(code).then(() => {
            const btn = document.querySelector('.btn-copy');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="ti ti-check"></i> Copié !';
            setTimeout(() => btn.innerHTML = original, 2000);
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
