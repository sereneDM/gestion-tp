@extends('layouts.teacher')

@section('title', 'Mes Cours')
@section('page-title', 'Mes Cours')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .header-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .course-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        border-left: 4px solid #007bff;
    }
    .course-card.archived {
        opacity: 0.6;
        border-left-color: #6c757d;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .course-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }
    .course-code {
        background: #007bff;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9rem;
        font-weight: bold;
    }
    .course-description {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    .course-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #999;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    .status-archived {
        background-color: #f8d7da;
        color: #721c24;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
    }
</style>
@endsection


@section('content')

    <div class="header-actions">
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
            ➕ Créer un cours
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
                <div class="course-card {{ $course->status === 'archived' ? 'archived' : '' }}"
                     onclick="window.location.href='{{ route('teacher.courses.show', $course->id) }}'"
                     style="cursor: pointer;">
                    <div class="course-header">
                        <div class="course-name">{{ $course->name }}</div>
                        <div class="course-code">{{ $course->join_code }}</div>
                    </div>

                    @if($course->description)
                        <div class="course-description">{{ $course->description }}</div>
                    @endif

                    <div class="course-meta">
                        <span>👥 {{ $course->students_count }} étudiant(s)</span>
                        <span>
                            <span class="status-badge status-{{ $course->status }}">
                                {{ $course->status === 'active' ? 'Actif' : 'Archivé' }}
                            </span>
                        </span>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('teacher.courses.show', $course->id) }}"
                           class="btn btn-info btn-small"
                           onclick="event.stopPropagation();">
                            👁️ Voir détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h2>Aucun cours créé</h2>
            <p style="color: #666; margin: 1rem 0;">Créez votre premier cours pour commencer!</p>
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                ➕ Créer mon premier cours
            </a>
        </div>
    @endif
@endsection