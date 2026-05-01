@extends('layouts.app')

@section('title', 'Mes Cours')
@section('page-title', 'Mes Cours')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        color: #e2e8f0;
    }
    .btn-primary { background-color: #4f46e5; color: white; }
    .btn-primary:hover { background: #4338ca; }
    .header-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.5rem;
    }
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .course-card {
        background: #0f172a;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 12px 24px rgba(15,23,42,0.25);
        transition: transform 0.2s, border-color 0.2s;
        border: 1px solid #334155;
    }
    .course-card:hover {
        transform: translateY(-4px);
        border-color: #475569;
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
        color: #f8fafc;
    }
    .course-teacher {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }
    .course-description {
        font-size: 0.9rem;
        min-height: 2.5rem;
        margin-bottom: 1rem;
    }
    .course-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #94a3b8;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #0f172a;
        border-radius: 1rem;
        border: 1px solid #334155;
        color: #cbd5e1;
    }
</style>
@endsection

@section('content')
    <div class="header-actions">
        <a href="{{ route('student.join-course.form') }}" class="btn btn-primary">
            ➕ Rejoindre un cours
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
                <div class="course-card"
                     onclick="window.location.href='{{ route('student.courses.show', $course->id) }}'"
                     style="cursor: pointer;">
                    <div class="course-header">
                        <div>
                            <div class="course-name">{{ $course->name }}</div>
                            <div class="course-teacher">👨‍🏫 {{ $course->teacher->name }}</div>
                        </div>
                    </div>

                    <div class="course-description">
                        @if($course->description)
                            <span style="color:#cbd5e1;">{{ Str::limit($course->description, 100) }}</span>
                        @else
                            <span style="color:#475569; font-style:italic;">Aucune description</span>
                        @endif
                    </div>

                    <div class="course-meta">
                        <span>📝 {{ $course->tps_count }} TP(s)</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div style="font-size: 5rem; margin-bottom: 1rem;">📚</div>
            <h2>Aucun cours</h2>
            <p style="margin-top: 1rem;">Vous n'êtes inscrit à aucun cours pour le moment.</p>
        </div>
    @endif
@endsection