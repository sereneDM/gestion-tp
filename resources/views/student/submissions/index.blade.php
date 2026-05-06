@extends('layouts.app')

@section('title', 'Mes Soumissions')
@section('page-title', 'Mes Soumissions')

@section('extra-styles')
<style>
    /* ── Filter bar ── */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .filter-bar label {
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: bold;
        white-space: nowrap;
    }
    .filter-bar select {
        background: #1e293b;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        cursor: pointer;
        outline: none;
    }
    .filter-bar select:focus {
        border-color: #6366f1;
    }
    .filter-reset {
        background: none;
        border: 1px solid #475569;
        color: #94a3b8;
        padding: 0.5rem 0.9rem;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        cursor: pointer;
        display: none;
    }
    .filter-reset:hover {
        background: #1e293b;
        color: #e2e8f0;
    }

    /* ── Table ── */
    .submissions-table {
        width: 100%;
        border-collapse: collapse;
        background: #1e293b;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #334155;
    }
    thead {
        background: #4f46e5;
        color: white;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }
    tbody tr:hover {
        background: #334155;
    }

    /* ── TP title truncation ── */
    .tp-title {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        font-weight: bold;
        color: #e2e8f0;
        title: attr(title);
    }

    /* ── Badges ── */
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-submitted {
        background: rgba(251,191,36,0.15);
        color: #fcd34d;
    }
    .status-graded {
        background: rgba(34,197,94,0.15);
        color: #86efac;
    }

    .grade-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: bold;
        font-size: 1rem;
        display: inline-block;
    }
    .grade-good    { background: rgba(34,197,94,0.15);  color: #86efac; }
    .grade-average { background: rgba(251,191,36,0.15); color: #fcd34d; }
    .grade-poor    { background: rgba(239,68,68,0.15);  color: #fca5a5; }

    .btn-view {
        background: #6366f1;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    .btn-view:hover { background: #4f46e5; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #0f172a;
        border-radius: 8px;
        color: #cbd5e1;
        border: 1px solid #334155;
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
    @if($submissions->count() > 0)

        {{-- Filter bar --}}
        <div class="filter-bar">
            <label>Filtrer par cours :</label>
            <select id="filter-course" onchange="applyFilters()">
                <option value="">— Tous les cours —</option>
                @foreach($submissions->pluck('tp.class.name')->unique()->sort() as $courseName)
                    <option value="{{ $courseName }}">{{ $courseName }}</option>
                @endforeach
            </select>

            <label>Statut :</label>
            <select id="filter-status" onchange="applyFilters()">
                <option value="">— Tous —</option>
                <option value="graded">✓ Noté</option>
                <option value="submitted">⏳ En attente</option>
            </select>

            <button class="filter-reset" id="reset-btn" onclick="resetFilters()">✕ Réinitialiser</button>
        </div>

        <table class="submissions-table">
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>TP</th>
                    <th>Date de soumission</th>
                    <th>Statut</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="submissions-body">
                @foreach($submissions as $submission)
                    @php
                        $statusKey = $submission->grade ? 'graded' : 'submitted';
                    @endphp
                    <tr data-course="{{ $submission->tp->class->name }}"
                        data-status="{{ $statusKey }}">
                        <td>
                            <strong>{{ $submission->tp->class->name }}</strong>
                            <br>
                            <small style="color: #94a3b8;">{{ $submission->tp->teacher->name }}</small>
                        </td>
                        <td>
                            <span class="tp-title" title="{{ $submission->tp->title }}">
                                {{ $submission->tp->title }}
                            </span>
                        </td>
                        <td>{{ $submission->submitted_at->format('d/m/Y à H:i') }}</td>
                        <td>
                            @if($submission->grade)
                                <span class="status-badge status-graded">✓ Noté</span>
                          
                            @else
                                <span class="status-badge status-submitted">⏳ En attente</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->grade)
                                <span class="grade-badge
                                    @if($submission->grade >= 14) grade-good
                                    @elseif($submission->grade >= 10) grade-average
                                    @else grade-poor
                                    @endif">
                                    {{ $submission->grade }}/20
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.tps.show', $submission->tp->id) }}" class="btn-view">
                                👁️ Voir détails
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="no-results">Aucune soumission ne correspond aux filtres sélectionnés.</div>

    @else
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📄</div>
            <h2>Aucune soumission</h2>
            <p>Vous n'avez pas encore soumis de travaux.</p>
            <a href="{{ route('student.my-courses') }}" style="color: #6366f1; margin-top: 1rem; display: inline-block;">
                📚 Voir mes cours
            </a>
        </div>
    @endif
@endsection

@section('extra-scripts')
<script>
    function applyFilters() {
        const course = document.getElementById('filter-course').value;
        const status = document.getElementById('filter-status').value;
        const rows   = document.querySelectorAll('#submissions-body tr');
        let visible  = 0;

        rows.forEach(row => {
            const matchCourse = !course || row.dataset.course === course;
            const matchStatus = !status || row.dataset.status === status;
            const show = matchCourse && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';

        // Show reset button only when a filter is active
        const anyActive = course || status;
        document.getElementById('reset-btn').style.display = anyActive ? 'inline-block' : 'none';
    }

    function resetFilters() {
        document.getElementById('filter-course').value = '';
        document.getElementById('filter-status').value = '';
        applyFilters();
    }
</script>
@endsection