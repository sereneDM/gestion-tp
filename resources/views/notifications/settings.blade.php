@extends('layouts.app')

@section('title', 'Paramètres de Notification')
@section('page-title', 'Paramètres de Notification')

@section('breadcrumbs')
    {{ Breadcrumbs::render('notification-settings') }}
@endsection

@section('extra-styles')
<style>
    .settings-card {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #334155;
    }
    .settings-card h2 {
        margin-top: 0;
        color: #f1f5f9;
        border-bottom: 1px solid #334155;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }
    .info-box {
        background: rgba(99,102,241,0.1);
        border-left: 4px solid #6366f1;
        padding: 0.9rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        color: #a5b4fc;
    }
    .setting-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #1e293b;
    }
    .setting-group:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .setting-group h3 {
        color: #cbd5e1;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 0.9rem 1rem;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .checkbox-item:hover {
        background: #263448;
        border-color: #475569;
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 1rem;
        cursor: pointer;
        accent-color: #6366f1;
        flex-shrink: 0;
    }
    .checkbox-label {
        flex: 1;
        cursor: pointer;
        color: #e2e8f0;
    }
    .checkbox-description {
        font-size: 0.83rem;
        color: #64748b;
        margin-top: 0.2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s, opacity 0.2s;
    }
    .btn-primary {
        background: #4f46e5;
        color: white;
        cursor: pointer;
    }
    .btn-primary:not(:disabled):hover { background: #4338ca; }
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ── Course filter bar ── */
    .course-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .course-filter label {
        font-size: 0.9rem;
        color: #94a3b8;
        white-space: nowrap;
        font-weight: bold;
    }
    .course-filter select,
    .course-filter input[type="text"] {
        background: #1e293b;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        outline: none;
    }
    .course-filter select { cursor: pointer; }
    .course-filter input[type="text"] { flex: 1; min-width: 180px; }
    .course-filter select:focus,
    .course-filter input[type="text"]:focus { border-color: #6366f1; }

    .course-placeholder {
        text-align: center;
        padding: 2rem;
        color: #475569;
        font-size: 0.95rem;
        border: 1px dashed #334155;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
    }

    #no-course-results {
        display: none;
        text-align: center;
        padding: 2rem;
        color: #64748b;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')

    <form method="POST" action="{{ route('notification-settings.update') }}" id="settings-form">
        @csrf

        <!-- Global Settings -->
        <div class="settings-card">
            <h2>🌍 Paramètres Globaux</h2>

            <div class="info-box">
                ℹ️ Ces paramètres s'appliquent à tous vos cours. Vous pouvez personnaliser par cours ci-dessous.
            </div>

            <div class="setting-group">
                @if(Auth::user()->isStudent())
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_new_tp" name="global_new_tp"
                               {{ $globalSettings->new_tp_notifications ? 'checked' : '' }}>
                        <label for="global_new_tp" class="checkbox-label">
                            <div>📝 Nouveaux TP</div>
                            <div class="checkbox-description">Être notifié quand un enseignant publie un nouveau TP</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_submission_graded" name="global_submission_graded"
                               {{ $globalSettings->submission_graded_notifications ? 'checked' : '' }}>
                        <label for="global_submission_graded" class="checkbox-label">
                            <div>⭐ TP notés</div>
                            <div class="checkbox-description">Être notifié quand un TP est noté</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_post" name="global_post"
                               {{ $globalSettings->post_notifications ? 'checked' : '' }}>
                        <label for="global_post" class="checkbox-label">
                            <div>📢 Publications</div>
                            <div class="checkbox-description">Être notifié des nouvelles annonces</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_comment" name="global_comment"
                               {{ $globalSettings->comment_notifications ? 'checked' : '' }}>
                        <label for="global_comment" class="checkbox-label">
                            <div>💬 Commentaires</div>
                            <div class="checkbox-description">Être notifié quand quelqu'un répond à votre commentaire</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_like" name="global_like"
                               {{ $globalSettings->like_notifications ? 'checked' : '' }}>
                        <label for="global_like" class="checkbox-label">
                            <div>❤️ Likes</div>
                            <div class="checkbox-description">Être notifié quand quelqu'un aime votre commentaire</div>
                        </label>
                    </div>
                @else
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_new_submission" name="global_new_submission"
                               {{ $globalSettings->new_submission_notifications ? 'checked' : '' }}>
                        <label for="global_new_submission" class="checkbox-label">
                            <div>📤 Nouvelles soumissions</div>
                            <div class="checkbox-description">Être notifié quand un étudiant soumet un TP</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_student_joined" name="global_student_joined"
                               {{ $globalSettings->student_joined_notifications ? 'checked' : '' }}>
                        <label for="global_student_joined" class="checkbox-label">
                            <div>👤 Nouveaux étudiants</div>
                            <div class="checkbox-description">Être notifié quand un étudiant rejoint un de vos cours</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_comment" name="global_comment"
                               {{ $globalSettings->comment_notifications ? 'checked' : '' }}>
                        <label for="global_comment" class="checkbox-label">
                            <div>💬 Commentaires</div>
                            <div class="checkbox-description">Être notifié quand quelqu'un commente vos publications</div>
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="global_like" name="global_like"
                               {{ $globalSettings->like_notifications ? 'checked' : '' }}>
                        <label for="global_like" class="checkbox-label">
                            <div>❤️ Likes</div>
                            <div class="checkbox-description">Être notifié quand quelqu'un aime votre publication ou commentaire</div>
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <!-- Per-Course Settings -->
        <div class="settings-card">
            <h2>📚 Paramètres par Cours</h2>

            @if($courses->count() > 0)
                <div class="course-filter">
                    <label for="course-search">🔍</label>
                    <input type="text" id="course-search" placeholder="Rechercher un cours..."
                           oninput="filterCourses()">
                    <label for="course-select">ou</label>
                    <select id="course-select" onchange="jumpToCourse(this.value)">
                        <option value="">— Aller à un cours —</option>
                        @foreach($courses as $course)
                            <option value="course-{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="course-placeholder" class="course-placeholder">
                    🔍 Recherchez un cours ou utilisez le menu déroulant pour afficher ses paramètres
                </div>

                <div id="no-course-results">Aucun cours trouvé.</div>

                @foreach($courses as $course)
                    <div class="setting-group"
                         id="course-{{ $course->id }}"
                         data-course-name="{{ strtolower($course->name) }}"
                         style="display: none;">
                        <h3>{{ $course->name }}</h3>

                        @if(Auth::user()->isStudent())
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_new_tp"
                                       name="courses[{{ $course->id }}][new_tp]"
                                       {{ $settings[$course->id]->new_tp_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_new_tp" class="checkbox-label">
                                    <div>📝 Nouveaux TP</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_graded"
                                       name="courses[{{ $course->id }}][submission_graded]"
                                       {{ $settings[$course->id]->submission_graded_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_graded" class="checkbox-label">
                                    <div>⭐ TP notés</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_post"
                                       name="courses[{{ $course->id }}][post]"
                                       {{ $settings[$course->id]->post_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_post" class="checkbox-label">
                                    <div>📢 Publications</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_comment"
                                       name="courses[{{ $course->id }}][comment]"
                                       {{ $settings[$course->id]->comment_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_comment" class="checkbox-label">
                                    <div>💬 Commentaires</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_like"
                                       name="courses[{{ $course->id }}][like]"
                                       {{ $settings[$course->id]->like_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_like" class="checkbox-label">
                                    <div>❤️ Likes</div>
                                </label>
                            </div>
                        @else
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_submission"
                                       name="courses[{{ $course->id }}][new_submission]"
                                       {{ $settings[$course->id]->new_submission_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_submission" class="checkbox-label">
                                    <div>📤 Nouvelles soumissions</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_student_joined"
                                       name="courses[{{ $course->id }}][student_joined]"
                                       {{ $settings[$course->id]->student_joined_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_student_joined" class="checkbox-label">
                                    <div>👤 Nouveaux étudiants</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_comment"
                                       name="courses[{{ $course->id }}][comment]"
                                       {{ $settings[$course->id]->comment_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_comment" class="checkbox-label">
                                    <div>💬 Commentaires</div>
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox"
                                       id="course_{{ $course->id }}_like"
                                       name="courses[{{ $course->id }}][like]"
                                       {{ $settings[$course->id]->like_notifications ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}_like" class="checkbox-label">
                                    <div>❤️ Likes</div>
                                </label>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div style="text-align:center; padding:2rem; color:#64748b;">
                    @if(Auth::user()->isStudent())
                        Vous n'êtes inscrit à aucun cours
                    @else
                        Vous n'avez créé aucun cours
                    @endif
                </div>
            @endif
        </div>

        <div>
            <button type="submit" id="save-btn" class="btn btn-primary" disabled>
                ✓ Enregistrer les paramètres
            </button>
        </div>
    </form>

@endsection

@section('extra-scripts')
<script>
    // ── Change detection ──────────────────────────────────
    const form    = document.getElementById('settings-form');
    const saveBtn = document.getElementById('save-btn');

    function getState() {
        return Array.from(form.querySelectorAll('input[type="checkbox"]'))
            .map(cb => cb.name + ':' + cb.checked)
            .join('|');
    }

    const originalState = getState();

    form.addEventListener('change', () => {
        saveBtn.disabled = getState() === originalState;
    });

    // ── Course search filter ──────────────────────────────
    function filterCourses() {
        const query       = document.getElementById('course-search').value.toLowerCase().trim();
        const groups      = document.querySelectorAll('.setting-group[data-course-name]');
        const placeholder = document.getElementById('course-placeholder');
        const noResults   = document.getElementById('no-course-results');
        let anyVisible    = false;

        groups.forEach(group => {
            if (!query) {
                group.style.display = 'none';
                return;
            }
            const match = group.dataset.courseName.includes(query);
            group.style.display = match ? 'block' : 'none';
            if (match) anyVisible = true;
        });

        placeholder.style.display = query ? 'none' : 'block';
        noResults.style.display   = query && !anyVisible ? 'block' : 'none';

        // Reset dropdown when typing
        document.getElementById('course-select').value = '';
    }

    // ── Dropdown jump-to ─────────────────────────────────
    function jumpToCourse(id) {
        if (!id) return;

        const groups      = document.querySelectorAll('.setting-group[data-course-name]');
        const placeholder = document.getElementById('course-placeholder');
        const noResults   = document.getElementById('no-course-results');

        // Hide all courses and clear search
        groups.forEach(g => g.style.display = 'none');
        document.getElementById('course-search').value = '';
        placeholder.style.display = 'none';
        noResults.style.display   = 'none';

        // Show only the selected course and scroll to it
        const target = document.getElementById(id);
        if (target) {
            target.style.display = 'block';
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>
@endsection