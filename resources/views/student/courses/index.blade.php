@extends('layouts.student')

@section('title', 'Mes Cours')
@section('page-title', 'Mes Cours')

@section('extra-styles')
<style>
    .header-actions {
        text-align: right;
        margin-bottom: 1.5rem;
    }
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
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .course-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .course-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    .course-name {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .course-teacher {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .course-body {
        padding: 1.5rem;
    }
    .course-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
    }
    .stat-label {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.25rem;
    }
    .course-actions {
        display: flex;
        gap: 0.5rem;
    }
    .btn-view {
        flex: 1;
        background: #007bff;
        color: white;
        text-align: center;
        padding: 0.75rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
    }
    .btn-view:hover {
        background: #0056b3;
    }
    .btn-leave {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-leave:hover {
        background: #c82333;
    }
    .empty-state {
        text-align: center;
        padding: 4rem;
        background: white;
        border-radius: 8px;
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
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-name">{{ $course->name }}</div>
                        <div class="course-teacher">👨‍🏫 {{ $course->teacher->name }}</div>
                    </div>
                    
                    <div class="course-body">
                        @if($course->description)
                            <p style="color: #666; margin-bottom: 1rem;">{{ Str::limit($course->description, 100) }}</p>
                        @endif
                        
                        <div class="course-stats">
                            <div class="stat-item">
                                <div class="stat-number">{{ $course->tps_count }}</div>
                                <div class="stat-label">Travaux pratiques</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $course->students->count() }}</div>
                                <div class="stat-label">Étudiants</div>
                            </div>
                        </div>
                        
                        <div class="course-actions">
                            <a href="{{ route('student.courses.show', $course->id) }}" class="btn-view">
                                📚 Voir le cours
                            </a>
                            <form method="POST" action="{{ route('student.leave-course', $course->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-leave" onclick="return confirm('Quitter ce cours?')">
                                    ✗
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div style="font-size: 5rem; margin-bottom: 1rem;">📚</div>
            <h2>Aucun cours</h2>
            <p style="margin-top: 1rem; color: #666;">Vous n'êtes inscrit à aucun cours pour le moment.</p>
            <a href="{{ route('student.join-course.form') }}" class="btn btn-primary" style="margin-top: 1.5rem;">
                ➕ Rejoindre un cours
            </a>
        </div>
    @endif
@endsection