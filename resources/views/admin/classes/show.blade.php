@extends('layouts.app')

@section('title', 'Détails de la Classe')
@section('page-title', $class->name)

@section('extra-styles')
<style>
    .btn {
        @apply px-5 py-2.5 border-none rounded-2xl cursor-pointer no-underline text-sm inline-flex items-center justify-center font-medium transition-colors duration-200;
    }
    .btn-secondary {
        @apply bg-slate-400 dark:bg-slate-600 text-white hover:bg-slate-500 dark:hover:bg-slate-700;
    }
    .section {
        @apply bg-white dark:bg-slate-800 px-8 py-8 rounded-2xl mb-8 shadow-md dark:shadow-lg border border-slate-200 dark:border-slate-700;
    }
    .section h2 {
        @apply text-violet-600 dark:text-violet-400 mb-6 pb-2 border-b border-slate-200 dark:border-slate-700 font-bold text-xl;
    }
    .info-row {
        @apply flex mb-4 py-3;
    }
    .info-label {
        @apply font-bold min-w-[200px] text-slate-700 dark:text-slate-300;
    }
    .info-value {
        @apply text-slate-900 dark:text-slate-100;
    }
    .join-code {
        @apply font-mono bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 px-4 py-2 rounded-2xl text-lg font-bold inline-block;
    }
    table {
        @apply w-full border-collapse bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-slate-200;
    }
    thead {
        @apply bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-50 font-bold;
    }
    th, td {
        @apply px-4 py-3 text-left border-b border-slate-200 dark:border-slate-600;
    }
    tbody tr {
        @apply bg-transparent;
    }
    tbody tr:hover {
        @apply bg-slate-50 dark:bg-slate-700/50 transition-colors;
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