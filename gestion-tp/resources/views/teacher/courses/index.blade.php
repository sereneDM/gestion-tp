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
    .btn-primary {
        background-color: #4f46e5;
        color: white;
    }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn-info {
        background-color: #2563eb;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .btn:hover {
        opacity: 0.95;
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
        background: #0f172a;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 12px 24px rgba(15,23,42,0.25);
        transition: transform 0.2s, border-color 0.2s;
        border-left: 4px solid #6366f1;
        border: 1px solid #334155;
    }
    .course-card.archived {
        opacity: 0.8;
        border-left-color: #475569;
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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
    .course-code {
        background: #4338ca;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 0.75rem;
        font-family: monospace;
        font-size: 0.9rem;
        font-weight: bold;
    }
    .course-description {
        color: #cbd5e1;
        font-size: 0.9rem;
        min-height: 2.5rem; /* keeps spacing even when content is short/absent */
        margin-bottom: 1rem;
    margin-bottom: 1rem;
    }
    .course-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #94a3b8;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-active {
        background-color: rgba(34,197,94,0.15);
        color: #86efac;
    }
    .status-archived {
        background-color: rgba(248,113,113,0.15);
        color: #fca5a5;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #0f172a;
        border-radius: 1rem;
        border: 1px solid #334155;
        color: #cbd5e1;
    }
    .empty-state a.btn-primary {
        background-color: #4f46e5;
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
                        <div class="course-code"
     title="Cliquer pour copier"
     onclick="event.stopPropagation(); copyCode(this, '{{ $course->join_code }}')">
    {{ $course->join_code }}
</div>
                    </div>

                    <div class="course-description">
                        @if($course->description)
                            {{ $course->description }}
                        @else
                            <span style="color: #475569; font-style: italic;">Aucune description</span>
                        @endif
                    </div>

                    <div class="course-meta">
                        <span>👥 {{ $course->students_count }} étudiant(s)</span>
                        <span>
                            <span class="status-badge status-{{ $course->status }}">
                                {{ $course->status === 'active' ? 'Actif' : 'Archivé' }}
                            </span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h2>Aucun cours créé</h2>
            <p style="color: #666; margin: 1rem 0;">Créez votre premier cours pour commencer!</p>
           
        </div>
    @endif
@endsection
@section('extra-scripts')
<script>
    function copyCode(el, code) {
        navigator.clipboard.writeText(code).then(() => {
            showToast('✓ Code copié : ' + code);
        });
    }
</script>
@endsection