@extends('layouts.app')
@section('title', 'Mes Cours')
@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('student.join-course.form') }}"
       class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-sm font-medium transition-colors">
        ➕ Rejoindre un cours
    </a>
</div>
@if($courses->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($courses as $course)
            <div class="bg-white dark:bg-slate-800 rounded-xl p-5
                        border border-slate-200 dark:border-slate-700 border-l-4 border-l-violet-500
                        cursor-pointer hover:-translate-y-1 transition-all shadow-sm"
                 onclick="window.location.href='{{ route('student.courses.show', $course->id) }}'">
                <div class="font-bold text-lg text-slate-900 dark:text-slate-100 truncate mb-1">{{ $course->name }}</div>
                <div class="text-sm text-slate-500 dark:text-slate-400 mb-3">👨‍🏫 {{ $course->teacher->name }}</div>
                <div class="text-sm mb-4 min-h-[2.5rem]">
                    @if($course->description)
                        <span class="text-slate-600 dark:text-slate-400">{{ Str::limit($course->description, 100) }}</span>
                    @else
                        <span class="text-slate-400 dark:text-slate-500 italic">Aucune description</span>
                    @endif
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    📝 {{ $course->tps_count }} TP(s)
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📚</div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Aucun cours</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-5">Vous n'êtes inscrit à aucun cours pour le moment.</p>
        <a href="{{ route('student.join-course.form') }}"
           class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-medium transition-colors">
            ➕ Rejoindre un cours
        </a>
    </div>
@endif
@endsection
