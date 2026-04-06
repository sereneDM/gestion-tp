@extends('layouts.teacher')

@section('title', 'Suivi des Étudiants')
@section('page-title', 'Suivi de la Progression des Étudiants')

@section('extra-styles')
<style>
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
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .class-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .class-section h2 {
        color: #007bff;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .students-table {
        width: 100%;
        border-collapse: collapse;
    }
    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .students-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555;
    }
    .students-table tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
    <div class="header-actions">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">← Retour</a>
    </div>

    @forelse($classes as $class)
        <div class="class-section">
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
                <p style="color: #999; text-align: center; padding: 2rem;">
                    Aucun étudiant dans cette classe
                </p>
            @endif
        </div>
    @empty
        <div class="class-section" style="text-align: center;">
            <p style="color: #999;">Vous n'avez aucune classe assignée</p>
        </div>
    @endforelse
@endsection