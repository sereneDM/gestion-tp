@extends('layouts.teacher')

@section('title', $tp->title)
@section('page-title', $tp->title)

@section('extra-styles')
<style>
    .header-actions {
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
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #545b62;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .info-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .info-card h2 {
        margin-top: 0;
        color: #007bff;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-label {
        font-weight: bold;
        color: #666;
    }
    .info-value {
        color: #333;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-published {
        background: #d4edda;
        color: #155724;
    }
    .status-draft {
        background: #fff3cd;
        color: #856404;
    }
    .status-closed {
        background: #f8d7da;
        color: #721c24;
    }
    .submissions-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    .submissions-table thead {
        background: #007bff;
        color: white;
    }
    .submissions-table th,
    .submissions-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .submissions-table tbody tr:hover {
        background: #f8f9fa;
    }
    .grade-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
        display: inline-block;
    }
    .grade-good {
        background: #d4edda;
        color: #155724;
    }
    .grade-average {
        background: #fff3cd;
        color: #856404;
    }
    .grade-poor {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }
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
            <div class="info-value">{{ $tp->title }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Description:</div>
            <div class="info-value">{{ $tp->description }}</div>
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
                    <a href="{{ asset('storage/' . $tp->attachments) }}" target="_blank" style="color: #007bff;">
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
                <p>Les étudiants n'ont pas encore soumis ce TP</p>
            </div>
        @endif
    </div>
@endsection