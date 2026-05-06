@extends('layouts.app')
@section('title', 'Paramètres de Notification')
@section('page-title', 'Paramètres de Notification')
@section('extra-styles')
<style>
    .settings-card {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--tp-border);
    }
    .settings-card h2 {
        margin-top: 0;
        color: var(--tp-text-primary);
        border-bottom: 1px solid var(--tp-border);
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }
    .info-box {
        background: rgba(99,102,241,0.1);
        border-left: 4px solid var(--tp-accent);
        padding: 0.9rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        color: var(--tp-text-secondary);
    }
    .setting-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--tp-border);
    }
    .setting-group:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .setting-group h3 {
        color: var(--tp-text-primary);
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 0.9rem 1rem;
        background: var(--tp-bg-surface);
        border: 1px solid var(--tp-border);
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .checkbox-item:hover {
        background: var(--tp-hover-bg);
        border-color: var(--tp-border-hover);
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 1rem;
        cursor: pointer;
        accent-color: var(--tp-accent);
        flex-shrink: 0;
    }
    .checkbox-label {
        flex: 1;
        cursor: pointer;
        color: var(--tp-text-primary);
    }
    .checkbox-description {
        font-size: 0.83rem;
        color: var(--tp-text-muted);
        margin-top: 0.2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.15s;
    }
    .btn-primary { background: var(--tp-accent); color: white; }
    .btn-primary:hover { background: var(--tp-accent-hover); }
</style>
@endsection
@section('content')
    <form method="POST" action="{{ route('notification-settings.update') }}">
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
            @forelse($courses as $course)
                <div class="setting-group">
                    <h3>{{ $course->name }}</h3>
                    @if(Auth::user()->isStudent())
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_new_tp"
                                   name="courses[{{ $course->id }}][new_tp]"
                                   {{ $settings[$course->id]->new_tp_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_new_tp" class="checkbox-label">
                                <div>📝 Nouveaux TP</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_graded"
                                   name="courses[{{ $course->id }}][submission_graded]"
                                   {{ $settings[$course->id]->submission_graded_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_graded" class="checkbox-label">
                                <div>⭐ TP notés</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_post"
                                   name="courses[{{ $course->id }}][post]"
                                   {{ $settings[$course->id]->post_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_post" class="checkbox-label">
                                <div>📢 Publications</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_comment"
                                   name="courses[{{ $course->id }}][comment]"
                                   {{ $settings[$course->id]->comment_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_comment" class="checkbox-label">
                                <div>💬 Commentaires</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_like"
                                   name="courses[{{ $course->id }}][like]"
                                   {{ $settings[$course->id]->like_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_like" class="checkbox-label">
                                <div>❤️ Likes</div>
                            </label>
                        </div>
                    @else
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_submission"
                                   name="courses[{{ $course->id }}][new_submission]"
                                   {{ $settings[$course->id]->new_submission_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_submission" class="checkbox-label">
                                <div>📤 Nouvelles soumissions</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_student_joined"
                                   name="courses[{{ $course->id }}][student_joined]"
                                   {{ $settings[$course->id]->student_joined_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_student_joined" class="checkbox-label">
                                <div>👤 Nouveaux étudiants</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_comment"
                                   name="courses[{{ $course->id }}][comment]"
                                   {{ $settings[$course->id]->comment_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_comment" class="checkbox-label">
                                <div>💬 Commentaires</div>
                            </label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="course_{{ $course->id }}_like"
                                   name="courses[{{ $course->id }}][like]"
                                   {{ $settings[$course->id]->like_notifications ? 'checked' : '' }}>
                            <label for="course_{{ $course->id }}_like" class="checkbox-label">
                                <div>❤️ Likes</div>
                            </label>
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align:center; padding:2rem; color: var(--tp-text-faint);">
                    @if(Auth::user()->isStudent())
                        Vous n'êtes inscrit à aucun cours
                    @else
                        Vous n'avez créé aucun cours
                    @endif
                </div>
            @endforelse
        </div>
        <div>
            <button type="submit" class="btn btn-primary">✓ Enregistrer les paramètres</button>
        </div>
    </form>
@endsection
