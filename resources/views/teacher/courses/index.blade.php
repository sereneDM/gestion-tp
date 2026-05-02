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
        transition: opacity 0.15s;
    }
    .btn-primary  { background: var(--tp-accent); color: white; }
    .btn-primary:hover  { background: var(--tp-accent-hover); opacity: 1; }
    .btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }
    .btn-info  { background: #2563eb; color: white; }
    .btn-small { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
    .btn:hover { opacity: 0.9; }

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
        background: var(--tp-bg-raised);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: transform 0.2s, border-color 0.2s;
        border-left: 4px solid #6366f1;
        border: 1px solid var(--tp-border);
        cursor: pointer;
    }
    .course-card.archived {
        opacity: 0.8;
        border-left-color: var(--tp-border-hover);
    }
    .course-card:hover {
        transform: translateY(-4px);
        border-color: var(--tp-border-hover);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
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
        color: var(--tp-text-primary);
    }
    .course-code {
        background: #4338ca;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 0.75rem;
        font-family: monospace;
        font-size: 0.9rem;
        font-weight: bold;
        cursor: pointer;
    }
    .course-description {
        color: var(--tp-text-secondary);
        font-size: 0.9rem;
        min-height: 2.5rem;
        margin-bottom: 1rem;
    }
    .course-description .no-desc {
        color: var(--tp-text-faint);
        font-style: italic;
    }
    .course-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: var(--tp-text-muted);
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-active {
        background: rgba(34,197,94,0.15);
        color: #16a34a;
    }
    [data-theme="dark"] .status-active { color: #86efac; }
    .status-archived {
        background: rgba(248,113,113,0.15);
        color: #dc2626;
    }
    [data-theme="dark"] .status-archived { color: #fca5a5; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: var(--tp-bg-raised);
        border-radius: 1rem;
        border: 1px solid var(--tp-border);
        color: var(--tp-text-secondary);
    }
    .empty-state p { color: var(--tp-text-muted); margin: 1rem 0; }
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
                     onclick="window.location.href='{{ route('teacher.courses.show', $course->id) }}'">
                    <div class="course-header">
                        <div class="course-name">{{ $course->name }}</div>
                        <div class="course-code"
<<<<<<< HEAD
     title="Cliquer pour copier"
     onclick="event.stopPropagation(); copyCode(this, '{{ $course->join_code }}')">
    {{ $course->join_code }}
</div>
=======
                             title="Cliquer pour copier"
                             onclick="event.stopPropagation(); copyCode(this, '{{ $course->join_code }}')">
                            {{ $course->join_code }}
                        </div>
>>>>>>> 29f2233 (fifth update)
                    </div>

                    <div class="course-description">
                        @if($course->description)
                            {{ $course->description }}
                        @else
                            <span class="no-desc">Aucune description</span>
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
<<<<<<< HEAD
            <p style="color: #666; margin: 1rem 0;">Créez votre premier cours pour commencer!</p>
           
        </div>
    @endif
@endsection
=======
            <p>Créez votre premier cours pour commencer!</p>
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">➕ Créer un cours</a>
        </div>
    @endif
@endsection

>>>>>>> 29f2233 (fifth update)
@section('extra-scripts')
<script>
    function copyCode(el, code) {
        navigator.clipboard.writeText(code).then(() => {
            showToast('✓ Code copié : ' + code);
        });
    }
</script>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 29f2233 (fifth update)
