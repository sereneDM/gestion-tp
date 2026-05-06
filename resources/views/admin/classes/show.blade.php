@extends('layouts.app')

@section('title', 'Détails de la Classe')
@section('page-title', $class->name)

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        background: #1e293b;
        color: #e2e8f0;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn:hover {
        background: #334155;
        border-color: #475569;
    }
    .btn-secondary {
        background-color: #1e293b;
        color: #e2e8f0;
    }
    .btn-secondary:hover {
        background: #334155;
    }
    .section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .section h2 {
        color: #c7d2fe;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        padding: 0.5rem;
    }
    .info-label {
        font-weight: bold;
        min-width: 200px;
        color: #cbd5e1;
    }
    .info-value {
        color: #e2e8f0;
    }
    .join-code {
        font-family: monospace;
        background: #1e293b;
        color: #818cf8;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 1.2rem;
        font-weight: bold;
        border: 1px solid #334155;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    thead {
        background-color: #1e293b;
        border-bottom: 2px solid #334155;
    }
    th {
        padding: 1rem;
        text-align: left;
        color: #cbd5e1;
        font-weight: bold;
    }
    td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }
    tbody tr:hover {
        background-color: #1e293b;
    }
</style>
@endsection

@section('content')
    


    <div class="section">
        <h2>Informations de la Classe</h2>

        <div class="info-row">
            <div class="info-label">Nom:</div>
            <div class="info-value">{{ $class->name }}</div>
        </div>

        @if($class->description)
            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value">{{ $class->description }}</div>
            </div>
        @endif

        <div class="info-row">
            <div class="info-label">Enseignant:</div>
            <div class="info-value">{{ $class->teacher ? $class->teacher->name : 'Non assigné' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Code d'accès:</div>
            <div class="info-value"><span class="join-code">{{ $class->join_code }}</span></div>
        </div>

        <div class="info-row">
            <div class="info-label">Statut:</div>
            <div class="info-value">
                <span style="padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; background: {{ $class->status === 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $class->status === 'active' ? '#155724' : '#721c24' }};">
                    {{ $class->status === 'active' ? 'Actif' : 'Archivé' }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Nombre d'étudiants:</div>
            <div class="info-value">{{ $class->students->count() }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Date de création:</div>
            <div class="info-value">{{ $class->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    <div class="section">
        <h2>👥 Étudiants Inscrits ({{ $class->students->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @forelse($class->students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->pivot->created_at->format('d/m/Y à H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 2rem; color: #999;">
                            Aucun étudiant inscrit
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection