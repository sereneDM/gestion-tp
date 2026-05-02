@extends('layouts.app')

@section('title', Str::limit($tp->title, 50))
@section('page-title', Str::limit($tp->title, 50))

@section('extra-styles')
<style>
    .header-actions { margin-bottom: 1.5rem; }
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
    .btn:hover { opacity: 0.9; }
    .btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }
    .btn-primary   { background: var(--tp-accent); color: white; }
    .btn-warning   { background: #f59e0b; color: #1f2937; }
    .btn-danger    { background: #dc2626; color: white; }
    .btn-small     { padding: 0.4rem 0.8rem; font-size: 0.85rem; }

    .info-card {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid var(--tp-border);
    }
    .info-card h2 {
        margin-top: 0;
        color: var(--tp-accent-text);
        border-bottom: 2px solid var(--tp-border);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 1rem 0;
        border-bottom: 1px solid var(--tp-border);
        min-width: 0;
    }
    .info-label { font-weight: bold; color: var(--tp-text-muted); }
    .info-value {
        color: var(--tp-text-primary);
        min-width: 0;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .info-value.title-value {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-value.desc-value {
        white-space: pre-wrap;
        max-height: 6rem;
        overflow-y: auto;
        line-height: 1.5;
        color: var(--tp-text-secondary);
    }
    .info-value a { color: #6366f1; text-decoration: none; }
    .info-value a:hover { text-decoration: underline; }

    .status-badge { padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.85rem; font-weight: bold; display: inline-block; }
    .status-published { background: rgba(34,197,94,0.15);  color: #16a34a; }
    .status-draft     { background: rgba(251,191,36,0.15); color: #d97706; }
    .status-closed    { background: rgba(239,68,68,0.15);  color: #dc2626; }
    [data-theme="dark"] .status-published { color: #86efac; }
    [data-theme="dark"] .status-draft     { color: #facc15; }
    [data-theme="dark"] .status-closed    { color: #fca5a5; }

    .submissions-table { width: 100%; border-collapse: collapse; }
    .submissions-table thead { background: var(--tp-table-header); color: var(--tp-text-primary); }
    .submissions-table th,
    .submissions-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--tp-border);
        color: var(--tp-text-secondary);
    }
    .submissions-table tbody tr:hover { background: var(--tp-table-row-hover); }

    .grade-badge { padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.9rem; font-weight: bold; display: inline-block; }
    .grade-good    { background: rgba(34,197,94,0.15);  color: #16a34a; }
    .grade-average { background: rgba(251,191,36,0.15); color: #d97706; }
    .grade-poor    { background: rgba(239,68,68,0.15);  color: #dc2626; }
    [data-theme="dark"] .grade-good    { color: #86efac; }
    [data-theme="dark"] .grade-average { color: #facc15; }
    [data-theme="dark"] .grade-poor    { color: #fca5a5; }

    .empty-state { text-align: center; padding: 3rem; color: var(--tp-text-muted); }
</style>
@endsection

@section('content')

    <!-- TP Information -->
    <div class="info-card">
        <h2>📝 Informations du TP</h2>

        <div class="info-row">
            <div class="info-label">Cours:</div>
            <div class="info-value">{{ $tp->class->name }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Titre:</div>
            <div class="info-value title-value" title="{{ $tp->title }}">
                {{ Str::limit($tp->title, 80) }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Description:</div>
            <div class="info-value desc-value">{{ $tp->description }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Date d'échéance:</div>
            <div class="info-value">
                @if($tp->due_date)
                    {{ $tp->due_date->format('d/m/Y à H:i') }}
                @else
                    Pas d'échéance définie
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Statut:</div>
            <div class="info-value">
                <span class="status-badge status-{{ $tp->status }}">
                    {{ ucfirst($tp->status) }}
                </span>
            </div>
        </div>

        @if($tp->attachments)
            <div class="info-row">
                <div class="info-label">Fichier attaché:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $tp->attachments) }}" target="_blank">
                        📎 Télécharger le PDF
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Submissions -->
    <div class="info-card">
        <h2>📊 Soumissions ({{ $tp->submissions->count() }})</h2>

        @if($tp->submissions->count() > 0)
            <table class="submissions-table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Date de soumission</th>
                        <th>Statut</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tp->submissions as $submission)
                        <tr>
                            <td>{{ $submission->student->name }}</td>
                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y à H:i') : 'N/A' }}</td>
                            <td>
                                @if($submission->grade)
                                    <span class="status-badge status-published">Noté</span>
                                @else
                                    <span class="status-badge status-draft">En attente</span>
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
                                <a href="{{ route('teacher.submissions.show', [$tp->id, $submission->id]) }}"
                                   class="btn btn-primary btn-small">
                                    👁️ Voir & Noter
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📄</div>
                <h3>Aucune soumission</h3>
                <p style="color: var(--tp-text-faint);">Les étudiants n'ont pas encore soumis ce TP</p>
            </div>
        @endif
    </div>
@endsection
