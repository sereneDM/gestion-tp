@extends('layouts.app')
@section('title', 'Ma Progression')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="text-3xl mb-2">📝</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $totalTPs }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP Total</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="text-3xl mb-2">✅</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $submittedTPs }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP Soumis</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="text-3xl mb-2">⭐</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $gradedTPs }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP Notés</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="text-3xl mb-2">📊</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $averageGrade }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Moyenne /20</div>
    </div>
</div>

{{-- Completion bar --}}
<div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 mb-6 shadow-sm">
    <div class="flex justify-between items-center mb-2">
        <strong class="text-slate-800 dark:text-slate-200 text-sm">Taux de Complétion</strong>
        <span class="text-sm font-bold text-violet-600 dark:text-violet-400">{{ $completionRate }}%</span>
    </div>
    <div class="w-full h-5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
        <div class="h-full bg-violet-600 rounded-full transition-all"
             style="width: {{ $completionRate }}%">
        </div>
    </div>
</div>

{{-- Per course --}}
<h2 class="font-semibold text-slate-800 dark:text-slate-200 text-lg mb-4">Progression par Cours</h2>

@forelse($courses as $course)
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 mb-4 shadow-sm">
        <h3 class="font-bold text-slate-900 dark:text-white text-base pb-2 mb-3 border-b border-slate-200 dark:border-slate-700">
            {{ $course->name }}
        </h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">👨‍🏫 {{ $course->teacher->name }}</p>

        @if($course->tps->count() > 0)
            <div class="flex flex-col gap-2">
                @foreach($course->tps as $tp)
                    @php
                        $submission = App\Models\Submission::where('tp_id', $tp->id)
                            ->where('student_id', Auth::id())->first();
                    @endphp
                    <div class="grid grid-cols-[1fr_auto_auto] gap-3 items-center
                                px-3 py-2.5 rounded-lg border
                                bg-slate-50 dark:bg-slate-700/50
                                border-slate-200 dark:border-slate-600">
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm truncate">{{ $tp->title }}</span>
                        @if($submission)
                            @if($submission->grade)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 whitespace-nowrap">
                                    {{ $submission->grade }}/20
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 whitespace-nowrap">✓ Soumis</span>
                            @endif
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300 whitespace-nowrap">À faire</span>
                        @endif
                        <a href="{{ route('student.tps.show', $tp->id) }}"
                           class="text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-sm transition-colors whitespace-nowrap">
                            Voir →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-400 dark:text-slate-500 text-center py-4 text-sm">Aucun TP disponible pour ce cours</p>
        @endif
    </div>
@empty
    <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📊</div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Aucune donnée de progression</h2>
        <p class="text-slate-500 dark:text-slate-400">Inscrivez-vous à des cours pour voir votre progression</p>
    </div>
@endforelse

@endsection