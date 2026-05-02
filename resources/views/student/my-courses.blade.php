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
                        cursor-pointer transition hover:-translate-y-1 shadow-sm"
                 onclick="window.location.href='{{ route('student.courses.show', $course->id) }}'">

                <div class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-3 truncate">
                    {{ $course->name }}
                </div>

                @if($course->description)
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">{{ $course->description }}</p>
                @endif

                <div class="flex flex-col gap-1 text-sm text-slate-500 dark:text-slate-400 mb-4">
                    <span>👨‍🏫 <strong class="text-slate-700 dark:text-slate-300">Enseignant:</strong> {{ $course->teacher->name }}</span>
                    <span>👥 <strong class="text-slate-700 dark:text-slate-300">Étudiants:</strong> {{ $course->students_count }}</span>
                    <span>📅 <strong class="text-slate-700 dark:text-slate-300">Inscrit le:</strong> {{ $course->pivot->created_at->format('d/m/Y') }}</span>
                </div>

                <div onclick="event.stopPropagation()">
                    <form method="POST" action="{{ route('student.leave-course', $course->id) }}"
                          onsubmit="return confirm('Voulez-vous vraiment quitter ce cours?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1.5 rounded-lg text-sm transition-colors
                                       bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                       hover:bg-red-200 dark:hover:bg-red-900/50">
                            ✗ Quitter le cours
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📚</div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Aucun cours rejoint</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-5">Demandez un code d'accès à votre enseignant pour rejoindre un cours!</p>
        <a href="{{ route('student.join-course.form') }}"
           class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-medium transition-colors">
            ➕ Rejoindre mon premier cours
        </a>
    </div>
@endif

@endsection