@extends('layouts.app')
@section('title', 'Mes Soumissions')
@section('content')
@if($submissions->count() > 0)
    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-violet-600 text-white">
                    <th class="px-4 py-3 text-left font-semibold">Cours</th>
                    <th class="px-4 py-3 text-left font-semibold">TP</th>
                    <th class="px-4 py-3 text-left font-semibold">Date de soumission</th>
                    <th class="px-4 py-3 text-left font-semibold">Statut</th>
                    <th class="px-4 py-3 text-left font-semibold">Note</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800">
                @foreach($submissions as $submission)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-4 py-3">
                            <strong class="text-slate-800 dark:text-slate-200 block">{{ $submission->tp->class->name }}</strong>
                            <small class="text-slate-500 dark:text-slate-400">{{ $submission->tp->teacher->name }}</small>
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200">
                            {{ $submission->tp->title }}
                        </td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                            {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($submission->grade)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✓ Noté</span>
                            @elseif($submission->status === 'late')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">⏰ En retard</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳ En attente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($submission->grade)
                                <span class="px-3 py-1 rounded font-bold
                                    @if($submission->grade >= 14) bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                    @elseif($submission->grade >= 10) bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                    @else bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 @endif">
                                    {{ $submission->grade }}/20
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('student.tps.show', $submission->tp->id) }}"
                               class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs transition-colors">
                                👁️ Voir détails
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📄</div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Aucune soumission</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-4">Vous n'avez pas encore soumis de travaux.</p>
        <a href="{{ route('student.my-courses') }}"
           class="text-violet-600 dark:text-violet-400 hover:underline transition-colors">
            📚 Voir mes cours
        </a>
    </div>
@endif
@endsection