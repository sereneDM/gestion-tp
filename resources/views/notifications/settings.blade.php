@extends('layouts.app')

@section('title', 'Paramètres de Notification')
@section('page-title', 'Paramètres de Notification')

@section('sidebar-menu')
    @if(Auth::user()->isStudent())
        @include('layouts.partials.student-menu')
    @elseif(Auth::user()->isTeacher())
        @include('layouts.partials.teacher-menu')
    @else
        @include('layouts.partials.admin-menu')
    @endif
@endsection

@section('extra-styles')
<style>
    .settings-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .settings-card h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .setting-group {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .setting-group:last-child {
        border-bottom: none;
    }
    .setting-group h3 {
        color: #555;
        margin-bottom: 1rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
    }
    .checkbox-item:hover {
        background: #e9ecef;
    }
    .checkbox-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 1rem;
        cursor: pointer;
    }
    .checkbox-label {
        flex: 1;
        cursor: pointer;
        color: #333;
    }
    .checkbox-description {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.25rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        text-decoration: none;
        display: inline-block;
        margin-left: 1rem;
    }
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
                        <input type="checkbox" 
                               id="global_new_tp" 
                               name="global_new_tp"
                               {{ $globalSettings->new_tp_notifications ? 'checked' : '' }}>
                        <label for="global_new_tp" class="checkbox-label">
                            <div>📝 Nouveaux TP</div>
                            <div class="checkbox-description">Être notifié quand un enseignant publie un nouveau TP</div>
                        </label>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" 
                               id="global_submission_graded" 
                               name="global_submission_graded"
                               {{ $globalSettings->submission_graded_notifications ? 'checked' : '' }}>
                        <label for="global_submission_graded" class="checkbox-label">
                            <div>⭐ TP notés</div>
                            <div class="checkbox-description">Être notifié quand un TP est noté</div>
                        </label>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" 
                               id="global_post" 
                               name="global_post"
                               {{ $globalSettings->post_notifications ? 'checked' : '' }}>
                        <label for="global_post" class="checkbox-label">
                            <div>📢 Publications</div>
                            <div class="checkbox-description">Être notifié des nouvelles annonces</div>
                        </label>
                    </div>
                @else
                    <div class="checkbox-item">
                        <input type="checkbox" 
                               id="global_new_submission" 
                               name="global_new_submission"
                               {{ $globalSettings->new_submission_notifications ? 'checked' : '' }}>
                        <label for="global_new_submission" class="checkbox-label">
                            <div>📤 Nouvelles soumissions</div>
                            <div class="checkbox-description">Être notifié quand un étudiant soumet un TP</div>
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
                    @endif
                </div>
            @empty
                <p style="color: #999; text-align: center; padding: 2rem;">
                    @if(Auth::user()->isStudent())
                        Vous n'êtes inscrit à aucun cours
                    @else
                        Vous n'avez créé aucun cours
                    @endif
                </p>
            @endforelse
        </div>

        <div>
            <button type="submit" class="btn btn-primary">
                ✓ Enregistrer les paramètres
            </button>
            <a href="{{ Auth::user()->isStudent() ? route('student.dashboard') : route('teacher.dashboard') }}" 
               class="btn btn-secondary">
                ← Retour
            </a>
        </div>
    </form>
@endsection