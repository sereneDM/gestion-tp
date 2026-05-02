@extends('layouts.app')
@section('title', 'Logs Système')

@section('content')

{{-- System info --}}
<div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6 mb-6">
    <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-4 pb-2 border-b border-slate-200 dark:border-slate-600">
        ℹ️ Informations Système
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach([
            ['PHP', $systemInfo['php_version']],
            ['Laravel', $systemInfo['laravel_version']],
            ['Base de données', ucfirst($systemInfo['database'])],
            ['Environnement', ucfirst($systemInfo['environment'])],
            ['Mode Debug', $systemInfo['debug_mode']],
            ['Fuseau horaire', $systemInfo['timezone']],
        ] as [$label, $value])
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-600 px-4 py-3">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">{{ $label }}</div>
                <div class="text-slate-800 dark:text-slate-100 font-medium">{{ $value }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- Activity log --}}
<div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6">
    <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-4 pb-2 border-b border-slate-200 dark:border-slate-600">
        🕐 Activité Récente
    </h2>
    @forelse($activities as $activity)
        <div class="bg-white dark:bg-slate-800 border-l-4 border-violet-500 dark:border-violet-400
                    border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 mb-3">
            <div class="flex justify-between items-start mb-1">
                <span class="font-semibold text-slate-800 dark:text-slate-100 text-sm">{{ $activity->description }}</span>
                <span class="text-xs text-slate-400 dark:text-slate-500 ml-3 shrink-0">{{ $activity->created_at->diffForHumans() }}</span>
            </div>
            <div class="text-xs text-violet-600 dark:text-violet-400">
                👤 {{ $activity->causer?->email ?? 'Système' }}
            </div>
            @if($activity->subject_type)
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                </div>
            @endif
        </div>
    @empty
        <div class="text-center text-slate-400 dark:text-slate-500 py-10 text-sm">
            Aucune activité enregistrée pour le moment.
        </div>
    @endforelse
</div>

@endsection