@extends('layouts.app')

@section('title', 'Suivi des Étudiants')
@section('page-title', 'Suivi de la Progression des Étudiants')

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
<<<<<<< HEAD
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn-info {
        background-color: #4f46e5;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .btn:hover {
        opacity: 0.95;
    }

    /* ── Filter bar ── */
=======
    .btn:hover { opacity: 0.9; }
    .btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }
    .btn-info  { background: var(--tp-accent); color: white; }
    .btn-small { padding: 0.4rem 0.8rem; font-size: 0.85rem; }

>>>>>>> 29f2233 (fifth update)
    .course-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .course-filter label {
        font-size: 0.9rem;
<<<<<<< HEAD
        color: #94a3b8;
=======
        color: var(--tp-text-muted);
>>>>>>> 29f2233 (fifth update)
        white-space: nowrap;
        font-weight: bold;
    }
    .course-filter select {
<<<<<<< HEAD
        background: #1e293b;
        border: 1px solid #334155;
        color: #e2e8f0;
=======
        background: var(--tp-input-bg);
        border: 1px solid var(--tp-border);
        color: var(--tp-text-primary);
>>>>>>> 29f2233 (fifth update)
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        cursor: pointer;
        outline: none;
    }
<<<<<<< HEAD
    .course-filter select:focus {
        border-color: #6366f1;
    }
=======
    .course-filter select:focus { border-color: #6366f1; }
    .course-filter select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }
>>>>>>> 29f2233 (fifth update)

    .class-section {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid var(--tp-border);
    }
    .class-section h2 {
        color: var(--tp-accent-text);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--tp-border);
    }
    .students-table { width: 100%; border-collapse: collapse; }
    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--tp-border);
        color: var(--tp-text-secondary);
    }
    .students-table th {
        background: var(--tp-table-header);
        font-weight: bold;
        color: var(--tp-text-primary);
    }
    .students-table tr:hover { background: var(--tp-table-row-hover); }

    #no-results {
        display: none;
        text-align: center;
        padding: 3rem;
        color: var(--tp-text-faint);
        background: var(--tp-bg-raised);
        border: 1px solid var(--tp-border);
        border-radius: 1rem;
    }

    #no-results {
        display: none;
        text-align: center;
        padding: 3rem;
        color: #64748b;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 1rem;
    }
</style>
@endsection

@section('content')

<<<<<<< HEAD
    {{-- Filter bar --}}
=======
>>>>>>> 29f2233 (fifth update)
    <div class="course-filter">
        <label for="class-filter">Filtrer par classe :</label>
        <select id="class-filter" onchange="filterClass(this.value)">
            <option value="">— Toutes les classes —</option>
            @foreach($classes as $class)
                <option value="class-{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Class sections --}}
    @forelse($classes as $class)
        <div class="class-section" id="class-{{ $class->id }}">
            <h2>{{ $class->name }}</h2>

            @if($class->students->count() > 0)
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Nom de l'étudiant</th>
                            <th>Email</th>
                            <th>Nombre d'étudiants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $class->students->count() }}</td>
                                <td>
                                    <a href="{{ route('teacher.progress.show', $student->id) }}"
                                       class="btn btn-info btn-small">
                                        👁️ Voir détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: var(--tp-text-faint); text-align: center; padding: 2rem;">
                    Aucun étudiant dans cette classe
                </p>
            @endif
        </div>
    @empty
        <div class="class-section" style="text-align: center;">
            <p style="color: var(--tp-text-faint);">Vous n'avez aucune classe assignée</p>
        </div>
    @endforelse

    <div id="no-results">Aucune classe trouvée.</div>

@endsection

@section('extra-scripts')
<script>
    function filterClass(value) {
        const sections = document.querySelectorAll('.class-section');
        let anyVisible = false;

        sections.forEach(section => {
            if (!value || section.id === value) {
                section.style.display = 'block';
                anyVisible = true;
            } else {
                section.style.display = 'none';
            }
        });

        document.getElementById('no-results').style.display = anyVisible ? 'none' : 'block';
    }
</script>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 29f2233 (fifth update)
