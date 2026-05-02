@extends('layouts.app')
@section('title', 'Supervision des Classes')

@section('content')

<div class="flex items-start gap-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500
            px-4 py-3 rounded-lg text-sm text-blue-700 dark:text-blue-300 mb-5">
    ℹ️ <span><strong>Note:</strong> Les classes sont créées par les enseignants. Vous pouvez les superviser et les supprimer si nécessaire.</span>
</div>

<div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-violet-600 text-white">
                <th class="px-4 py-3 text-left font-semibold">Nom de la Classe</th>
                <th class="px-4 py-3 text-left font-semibold">Enseignant</th>
                <th class="px-4 py-3 text-left font-semibold">Code d'accès</th>
                <th class="px-4 py-3 text-left font-semibold">Étudiants</th>
                <th class="px-4 py-3 text-left font-semibold">Statut</th>
                <th class="px-4 py-3 text-left font-semibold">Créé le</th>
                <th class="px-4 py-3 text-left font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800">
            @forelse($classes as $class)
                <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-100">{{ $class->name }}</td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                        {{ $class->teacher ? $class->teacher->name : 'Non assigné' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs font-bold px-2 py-1 rounded
                                     bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300">
                            {{ $class->join_code }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $class->students_count }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $class->status === 'active'
                                ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300'
                                : 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' }}">
                            {{ $class->status === 'active' ? '● Actif' : '● Archivé' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $class->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.classes.show', $class->id) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
                                      bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300
                                      hover:bg-indigo-200 dark:hover:bg-indigo-900/50">
                                👁️ Voir
                            </a>
                            <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}"
                                  onsubmit="return confirm('Supprimer cette classe? Cette action est irréversible!')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
                                               bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                               hover:bg-red-200 dark:hover:bg-red-900/50">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                        Aucune classe créée
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection