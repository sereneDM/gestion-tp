@extends('layouts.student')

@section('title', 'Mes Soumissions')
@section('page-title', 'Mes Soumissions')

@section('extra-styles')
<style>
    .submissions-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    thead {
        background: #007bff;
        color: white;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    tbody tr:hover {
        background: #f8f9fa;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-submitted {
        background: #fff3cd;
        color: #856404;
    }
    .status-graded {
        background: #d4edda;
        color: #155724;
    }
    .status-late {
        background: #f8d7da;
        color: #721c24;
    }
    .grade-badge {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: bold;
        font-size: 1rem;
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
    .btn-view {
        background: #17a2b8;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .btn-view:hover {
        background: #138496;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
        color: #999;
    }
</style>
@endsection

@section('content')
    @if($submissions->count() > 0)
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
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>
                            <strong>{{ $submission->tp->class->name }}</strong>
                            <br>
                            <small style="color: #666;">{{ $submission->tp->teacher->name }}</small>
                        </td>
                        <td><strong>{{ $submission->tp->title }}</strong></td>
                        <td>{{ $submission->submitted_at->format('d/m/Y à H:i') }}</td>
                        <td>
                            @if($submission->grade)
                                <span class="status-badge status-graded">✓ Noté</span>
                            @elseif($submission->status === 'late')
                                <span class="status-badge status-late">⏰ En retard</span>
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
    @else
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📄</div>
            <h2>Aucune soumission</h2>
            <p>Vous n'avez pas encore soumis de travaux.</p>
            <a href="{{ route('student.my-courses') }}" style="color: #007bff; margin-top: 1rem; display: inline-block;">
                📚 Voir mes cours
            </a>
        </div>
    @endif
@endsection