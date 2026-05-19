@extends('layouts.app')

@section('title', 'Paramètres de Notification')
@section('page-title', 'Paramètres de Notification')

@section('breadcrumbs')
    {{ Breadcrumbs::render('notification-settings') }}
@endsection

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
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
    --warning:    #f59e0b;
    --success:    #10b981;
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

.page-wrapper { max-width: 720px; margin: 0 auto; padding: 0.5rem 0 3rem; display: flex; flex-direction: column; gap: 1.25rem; }

/* ── Card ── */
.card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); overflow: hidden; }

.card-header { display: flex; align-items: center; gap: 0.65rem; padding: 1.25rem 1.75rem 1.1rem; border-bottom: 1px solid var(--line); }
.card-header-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); background: var(--accent-bg); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 16px; }
.card-header-title { font-size: 0.9rem; font-weight: 700; color: var(--ink); }

.card-body { padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 0; }

.info-box { display: flex; align-items: flex-start; gap: 0.65rem; background: var(--accent-bg); border: 1px solid rgba(61,90,254,0.15); border-radius: var(--radius-md); padding: 0.85rem 1rem; font-size: 0.82rem; color: var(--accent); line-height: 1.5; margin-bottom: 1.25rem; }
.info-box i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

/* ── Checkbox items ── */
.checkbox-item { display: flex; align-items: center; padding: 0.9rem 0; border-bottom: 1px solid var(--line); gap: 0.85rem; cursor: pointer; }
.checkbox-item:last-child { border-bottom: none; }
.checkbox-item input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); flex-shrink: 0; cursor: pointer; }
.checkbox-label { flex: 1; cursor: pointer; }
.checkbox-title { font-size: 0.875rem; font-weight: 600; color: var(--ink); margin-bottom: 2px; }
.checkbox-desc  { font-size: 0.77rem; color: var(--ink-4); line-height: 1.4; }

/* ── Course filter ── */
.course-filter { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.course-filter-label { font-size: 0.75rem; font-weight: 600; color: var(--ink-4); white-space: nowrap; }

.filter-input { padding: 0.55rem 0.9rem; border: 1px solid var(--line-2); border-radius: var(--radius-md); font-size: 0.85rem; font-family: var(--font-body); background: var(--surface); color: var(--ink); transition: border-color 0.2s; }
.filter-input:focus { outline: none; border-color: var(--accent); }
.filter-input[type="text"] { flex: 1; min-width: 160px; }
select.filter-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 2rem; cursor: pointer; }

.course-placeholder { text-align: center; padding: 1.75rem; color: var(--ink-4); font-size: 0.85rem; border: 1px dashed var(--line-2); border-radius: var(--radius-md); }
.course-placeholder i { font-size: 20px; display: block; margin-bottom: 0.4rem; color: var(--line-2); }

#no-course-results { display: none; text-align: center; padding: 1.75rem; color: var(--ink-4); font-size: 0.85rem; background: var(--surface-2); border: 1px solid var(--line); border-radius: var(--radius-md); }

.course-group { border-top: 1px solid var(--line); padding-top: 1rem; margin-top: 1rem; }
.course-group-title { font-size: 0.82rem; font-weight: 700; color: var(--ink-2); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem; }
.course-group-title i { font-size: 14px; color: var(--ink-4); }

/* ── Save button ── */
.save-section { display: flex; }
.btn-save { display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.7rem 1.5rem; border-radius: var(--radius-md); border: none; background: var(--accent); color: white; font-size: 0.875rem; font-weight: 700; font-family: var(--font-body); cursor: pointer; box-shadow: 0 2px 8px rgba(61,90,254,0.3); transition: background 0.2s, transform 0.15s, opacity 0.2s; }
.btn-save i { font-size: 15px; }
.btn-save:hover:not(:disabled) { background: var(--accent-2); transform: translateY(-1px); }
.btn-save:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }
/* Match feed heading style */
main h1 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.65rem !important;
    font-weight: 400 !important;   /* Serif Display looks better at normal weight */
    letter-spacing: -0.01em;
    color: #0d1117;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <form method="POST" action="{{ route('notification-settings.update') }}" id="settings-form">
        @csrf

        {{-- ── Global settings ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="ti ti-world"></i></div>
                <div class="card-header-title">Paramètres globaux</div>
            </div>
            <div class="card-body">

                <div class="info-box">
                    <i class="ti ti-info-circle"></i>
                    Ces paramètres s'appliquent à tous vos cours. Vous pouvez personnaliser par cours ci-dessous.
                </div>

                @if(Auth::user()->isStudent())
                    {{-- Student global settings: 6 entries, post like and comment like are separate --}}
                    @foreach([
                        ['global_new_tp',           $globalSettings->new_tp_notifications,            'ti-file-text',     'Nouveaux TP',          'Être notifié quand un enseignant publie un nouveau TP'],
                        ['global_submission_graded', $globalSettings->submission_graded_notifications, 'ti-star',          'TP notés',             'Être notifié quand un TP est noté'],
                        ['global_post',              $globalSettings->post_notifications,              'ti-speakerphone',  'Publications',         'Être notifié des nouvelles annonces'],
                        ['global_comment',           $globalSettings->comment_notifications,           'ti-message-circle','Commentaires',         'Être notifié quand quelqu\'un répond à votre commentaire'],
                        ['global_like',              $globalSettings->like_notifications,              'ti-heart',         'Likes (publications)', 'Être notifié quand quelqu\'un aime votre publication'],
                        ['global_comment_like',      $globalSettings->comment_like_notifications,      'ti-heart',         'Likes (commentaires)', 'Être notifié quand quelqu\'un aime votre commentaire'],
                    ] as [$name, $checked, $icon, $title, $desc])
                        <label class="checkbox-item">
                            <input type="checkbox" id="{{ $name }}" name="{{ $name }}" {{ $checked ? 'checked' : '' }}>
                            <label for="{{ $name }}" class="checkbox-label">
                                <div class="checkbox-title"><i class="ti {{ $icon }}" style="font-size:14px;margin-right:4px;color:var(--ink-4);"></i>{{ $title }}</div>
                                <div class="checkbox-desc">{{ $desc }}</div>
                            </label>
                        </label>
                    @endforeach
                @else
                    {{-- Teacher global settings: 5 entries, post like and comment like are separate --}}
                    @foreach([
                        ['global_new_submission', $globalSettings->new_submission_notifications,  'ti-upload',        'Nouvelles soumissions', 'Être notifié quand un étudiant soumet un TP'],
                        ['global_student_joined', $globalSettings->student_joined_notifications,  'ti-user-plus',     'Nouveaux étudiants',    'Être notifié quand un étudiant rejoint un de vos cours'],
                        ['global_comment',        $globalSettings->comment_notifications,         'ti-message-circle','Commentaires',          'Être notifié quand quelqu\'un commente vos publications'],
                        ['global_like',           $globalSettings->like_notifications,            'ti-heart',         'Likes (publications)',  'Être notifié quand quelqu\'un aime votre publication'],
                        ['global_comment_like',   $globalSettings->comment_like_notifications,    'ti-heart',         'Likes (commentaires)',  'Être notifié quand quelqu\'un aime votre commentaire'],
                    ] as [$name, $checked, $icon, $title, $desc])
                        <label class="checkbox-item">
                            <input type="checkbox" id="{{ $name }}" name="{{ $name }}" {{ $checked ? 'checked' : '' }}>
                            <label for="{{ $name }}" class="checkbox-label">
                                <div class="checkbox-title"><i class="ti {{ $icon }}" style="font-size:14px;margin-right:4px;color:var(--ink-4);"></i>{{ $title }}</div>
                                <div class="checkbox-desc">{{ $desc }}</div>
                            </label>
                        </label>
                    @endforeach
                @endif

            </div>
        </div>

        {{-- ── Per-course settings ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="ti ti-books"></i></div>
                <div class="card-header-title">Paramètres par cours</div>
            </div>
            <div class="card-body">

                @if($courses->count() > 0)

                    <div class="course-filter">
                        <span class="course-filter-label"><i class="ti ti-search" style="font-size:13px;"></i></span>
                        <input type="text" class="filter-input" id="course-search"
                               placeholder="Rechercher un cours..." oninput="filterCourses()">
                        <select class="filter-input" id="course-select" onchange="jumpToCourse(this.value)">
                            <option value="">— Aller à un cours —</option>
                            @foreach($courses as $course)
                                <option value="course-{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="course-placeholder" class="course-placeholder">
                        <i class="ti ti-search"></i>
                        Recherchez un cours ou utilisez le menu déroulant
                    </div>

                    <div id="no-course-results">Aucun cours trouvé.</div>

                    @foreach($courses as $course)
                        <div class="course-group"
                             id="course-{{ $course->id }}"
                             data-course-name="{{ strtolower($course->name) }}"
                             style="display:none;">
                            <div class="course-group-title">
                                <i class="ti ti-book"></i> {{ $course->name }}
                            </div>

                            @if(Auth::user()->isStudent())
                                {{-- Student per-course: uses $settings[$course->id] and courses[id][key] names --}}
                                @foreach([
                                    ["courses[{$course->id}][new_tp]",           $settings[$course->id]->new_tp_notifications,           'Nouveaux TP'],
                                    ["courses[{$course->id}][submission_graded]", $settings[$course->id]->submission_graded_notifications, 'TP notés'],
                                    ["courses[{$course->id}][post]",             $settings[$course->id]->post_notifications,             'Publications'],
                                    ["courses[{$course->id}][comment]",          $settings[$course->id]->comment_notifications,          'Commentaires'],
                                    ["courses[{$course->id}][like]",             $settings[$course->id]->like_notifications,             'Likes (publications)'],
                                    ["courses[{$course->id}][comment_like]",     $settings[$course->id]->comment_like_notifications,     'Likes (commentaires)'],
                                ] as [$name, $checked, $title])
                                    <label class="checkbox-item" style="padding:0.65rem 0;">
                                        <input type="checkbox" name="{{ $name }}" {{ $checked ? 'checked' : '' }}>
                                        <label class="checkbox-label">
                                            <div class="checkbox-title">{{ $title }}</div>
                                        </label>
                                    </label>
                                @endforeach
                            @else
                                {{-- Teacher per-course: uses $settings[$course->id] and courses[id][key] names --}}
                                @foreach([
                                    ["courses[{$course->id}][new_submission]", $settings[$course->id]->new_submission_notifications, 'Nouvelles soumissions'],
                                    ["courses[{$course->id}][student_joined]", $settings[$course->id]->student_joined_notifications, 'Nouveaux étudiants'],
                                    ["courses[{$course->id}][comment]",        $settings[$course->id]->comment_notifications,        'Commentaires'],
                                    ["courses[{$course->id}][like]",           $settings[$course->id]->like_notifications,           'Likes (publications)'],
                                    ["courses[{$course->id}][comment_like]",   $settings[$course->id]->comment_like_notifications,   'Likes (commentaires)'],
                                ] as [$name, $checked, $title])
                                    <label class="checkbox-item" style="padding:0.65rem 0;">
                                        <input type="checkbox" name="{{ $name }}" {{ $checked ? 'checked' : '' }}>
                                        <label class="checkbox-label">
                                            <div class="checkbox-title">{{ $title }}</div>
                                        </label>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @endforeach

                @else
                    <div style="text-align:center;padding:2rem;color:var(--ink-4);font-size:0.875rem;">
                        @if(Auth::user()->isStudent()) Vous n'êtes inscrit à aucun cours
                        @else Vous n'avez créé aucun cours
                        @endif
                    </div>
                @endif

            </div>
        </div>

        <div class="save-section">
            <button type="submit" id="save-btn" class="btn-save" disabled>
                <i class="ti ti-device-floppy"></i> Enregistrer les paramètres
            </button>
        </div>

    </form>
</div>
@endsection

@section('extra-scripts')
<script>
const form    = document.getElementById('settings-form');
const saveBtn = document.getElementById('save-btn');

function getState() {
    return Array.from(form.querySelectorAll('input[type="checkbox"]'))
        .map(cb => cb.name + ':' + cb.checked).join('|');
}
const originalState = getState();
form.addEventListener('change', () => { saveBtn.disabled = getState() === originalState; });

function filterCourses() {
    const query       = document.getElementById('course-search').value.toLowerCase().trim();
    const groups      = document.querySelectorAll('.course-group[data-course-name]');
    const placeholder = document.getElementById('course-placeholder');
    const noResults   = document.getElementById('no-course-results');
    let anyVisible    = false;

    groups.forEach(group => {
        if (!query) { group.style.display = 'none'; return; }
        const match = group.dataset.courseName.includes(query);
        group.style.display = match ? 'block' : 'none';
        if (match) anyVisible = true;
    });

    placeholder.style.display = query ? 'none' : 'block';
    noResults.style.display   = query && !anyVisible ? 'block' : 'none';
    document.getElementById('course-select').value = '';
}

function jumpToCourse(id) {
    if (!id) return;
    document.querySelectorAll('.course-group[data-course-name]').forEach(g => g.style.display = 'none');
    document.getElementById('course-search').value = '';
    document.getElementById('course-placeholder').style.display = 'none';
    document.getElementById('no-course-results').style.display  = 'none';
    const target = document.getElementById(id);
    if (target) {
        target.style.display = 'block';
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
@endsection