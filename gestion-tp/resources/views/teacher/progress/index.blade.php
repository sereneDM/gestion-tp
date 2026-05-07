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
        color: #e2e8f0;
    }
    .btn-secondary { background-color: #475569; color: white; }
    .btn-info { background-color: #4f46e5; color: white; }
    .btn-small { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
    .btn:hover { opacity: 0.95; }

    /* ── Filter bar ── */
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

    .class-section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
        display: none; /* hidden by default */
    }
    .class-section h2 {
        color: #c7d2fe;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .students-table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
    }
    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #cbd5e1;
    }
    .students-table th {
        background-color: #334155;
        font-weight: bold;
        color: #e2e8f0;
    }
    .students-table tr:hover { background-color: #1e293b; }

    #empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #475569;
        background: #0f172a;
        border: 1px dashed #334155;
        border-radius: 1rem;
    }
    #empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
    #empty-state p { font-size: 0.95rem; color: #64748b; }

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

    <div class="course-filter">
        <label for="class-search">🔍</label>
        <input type="text" id="class-search" placeholder="Rechercher une classe..."
               oninput="filterClasses()">
        <label for="class-select">ou</label>
        <select id="class-select" onchange="jumpToClass(this.value)">
            <option value="">— Sélectionner une classe —</option>
            @foreach($classes as $class)
                <option value="class-{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Default empty state -->
    <div id="empty-state">
        <div class="icon">🎓</div>
        <p>Recherchez ou sélectionnez une classe pour afficher ses étudiants.</p>
    </div>

    <div id="no-results">Aucune classe trouvée.</div>

    @forelse($classes as $class)
        <div class="class-section" id="class-{{ $class->id }}"
             data-class-name="{{ strtolower($class->name) }}">
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
                <p style="color: #64748b; text-align: center; padding: 2rem;">
                    Aucun étudiant dans cette classe
                </p>
            @endif
        </div>
    @empty
        <div class="class-section" style="display:block; text-align: center;">
            <p style="color: #64748b;">Vous n'avez aucune classe assignée</p>
        </div>
    @endforelse

@endsection

@section('extra-scripts')
<script>
    function setEmptyState(show) {
        document.getElementById('empty-state').style.display = show ? 'block' : 'none';
    }

    function filterClasses() {
        const query = document.getElementById('class-search').value.toLowerCase().trim();
        const sections = document.querySelectorAll('.class-section[data-class-name]');
        let anyVisible = false;

        if (!query) {
            // No query → back to empty state
            sections.forEach(s => s.style.display = 'none');
            document.getElementById('no-results').style.display = 'none';
            setEmptyState(true);
            document.getElementById('class-select').value = '';
            return;
        }

        sections.forEach(section => {
            const match = section.dataset.className.includes(query);
            section.style.display = match ? 'block' : 'none';
            if (match) anyVisible = true;
        });

        setEmptyState(false);
        document.getElementById('no-results').style.display = anyVisible ? 'none' : 'block';
        document.getElementById('class-select').value = '';
    }

    function jumpToClass(id) {
        if (!id) {
            // Reset to empty state
            document.querySelectorAll('.class-section[data-class-name]')
                .forEach(s => s.style.display = 'none');
            document.getElementById('no-results').style.display = 'none';
            setEmptyState(true);
            document.getElementById('class-search').value = '';
            return;
        }

        // Hide all, show only selected
        document.querySelectorAll('.class-section[data-class-name]')
            .forEach(s => s.style.display = 'none');
        document.getElementById('no-results').style.display = 'none';
        setEmptyState(false);
        document.getElementById('class-search').value = '';

        const target = document.getElementById(id);
        if (target) {
            target.style.display = 'block';
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>
@endsection