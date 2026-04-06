@extends('layouts.student')

@section('title', $course->name)
@section('page-title', $course->name)

@section('extra-styles')
<style>
    .course-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .course-header h2 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
    }
    .course-teacher {
        opacity: 0.9;
        font-size: 1.1rem;
    }
    .course-description {
        margin-top: 1rem;
        opacity: 0.95;
    }
    .section-title {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .tp-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        border-left: 4px solid #007bff;
    }
    .tp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .tp-card.submitted {
        border-left-color: #28a745;
        background: #f8fff9;
    }
    .tp-card.graded {
        border-left-color: #17a2b8;
        background: #f0f9ff;
    }
    .tp-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .tp-description {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tp-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #666;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-submitted {
        background: #d4edda;
        color: #155724;
    }
    .status-graded {
        background: #d1ecf1;
        color: #0c5460;
    }
    .grade-display {
        background: #007bff;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 1.1rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1rem;
    }
    .btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-success {
        background: #28a745;
        color: white;
    }
    .btn-success:hover {
        background: #218838;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        display: inline-block;
        width: auto;
        padding: 0.6rem 1.2rem;
    }
    .btn-secondary:hover {
        background: #545b62;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
        color: #999;
    }
    .back-button {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
    <div class="back-button">
        <a href="{{ route('student.my-courses') }}" class="btn btn-secondary">
            ← Retour à mes cours
        </a>
    </div>

    <div class="course-header">
        <h2>{{ $course->name }}</h2>
        <div class="course-teacher">👨‍🏫 Enseignant: {{ $course->teacher->name }}</div>
        @if($course->description)
            <div class="course-description">{{ $course->description }}</div>
        @endif
    </div>

    <h3 class="section-title">📝 Travaux Pratiques ({{ $course->tps->count() }})</h3>

    @if($course->tps->count() > 0)
        <div class="tps-grid">
            @foreach($course->tps as $tp)
                @php
                    $submission = $submissions->get($tp->id);
                    $hasSubmitted = $submission !== null;
                    $isGraded = $hasSubmitted && $submission->grade !== null;
                @endphp

                <div class="tp-card {{ $isGraded ? 'graded' : ($hasSubmitted ? 'submitted' : '') }}">
                    <div class="tp-title">{{ $tp->title }}</div>
                    
                    @if($isGraded)
                        <span class="status-badge status-graded">✓ Noté</span>
                        <div class="grade-display">
                            Note: {{ $submission->grade }}/20
                        </div>
                    @elseif($hasSubmitted)
                        <span class="status-badge status-submitted">✓ Soumis</span>
                    @else
                        <span class="status-badge status-pending">À faire</span>
                    @endif

                    <div class="tp-description">{{ $tp->description }}</div>

                    <div class="tp-meta">
                        @if($tp->due_date)
                            <div>📅 Échéance: {{ $tp->due_date->format('d/m/Y à H:i') }}</div>
                        @else
                            <div>📅 Pas d'échéance</div>
                        @endif
                        
                        @if($hasSubmitted)
                            <div>📤 Soumis le: {{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
                        @endif
                    </div>

                    @if($isGraded)
                        <a href="{{ route('student.tps.show', $tp->id) }}" class="btn btn-success">
                            👁️ Voir ma note & commentaires
                        </a>
                    @elseif($hasSubmitted)
                        <a href="{{ route('student.tps.show', $tp->id) }}" class="btn btn-success">
                            👁️ Voir ma soumission
                        </a>
                    @else
                        <a href="{{ route('student.tps.show', $tp->id) }}" class="btn btn-primary">
                            📝 Voir et soumettre
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
            <h2>Aucun TP disponible</h2>
            <p>Votre enseignant n'a pas encore publié de travaux pratiques pour ce cours.</p>
        </div>
    @endif
@endsection