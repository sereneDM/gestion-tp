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
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .class-section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
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
    .students-table tr:hover {
        background-color: #1e293b;
    }
</style>
@endsection

@section('content')
  


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