@extends('layouts.app')
@section('title', $course->name)
@section('content')
{{-- Header: teacher + leave menu --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <div class="text-xs uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Enseignant</div>
        <div class="text-slate-700 dark:text-slate-300 text-sm">👨‍🏫 {{ $course->teacher->name }}</div>
    </div>
    <div class="relative">
        <button onclick="toggleCourseMenu()"
                class="w-8 h-8 flex items-center justify-center rounded-lg border
                       border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400
                       hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-lg">⋮</button>
        <div id="course-menu"
             class="hidden absolute top-9 right-0 z-50 min-w-[150px] rounded-xl shadow-xl
                    bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-slate-700">
            <form method="POST" action="{{ route('student.leave-course', $course->id) }}" class="block w-full">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full text-left px-4 py-3 text-sm text-red-600 dark:text-red-400
                               hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                    🚪 Quitter le cours
                </button>
            </form>
        </div>
    </div>
</div>
{{-- Tabs --}}
<div class="flex gap-1 mb-6 border-b-2 border-slate-200 dark:border-slate-700">
    <button class="tab-btn active-tab px-6 py-3 text-sm font-medium border-b-[3px] transition-colors"
            onclick="switchTab('info', event)">📋 Informations</button>
    <button class="tab-btn px-6 py-3 text-sm font-medium border-b-[3px] border-transparent
                   text-slate-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors"
            onclick="switchTab('tps', event)">📝 Travaux Pratiques</button>
</div>
{{-- Tab: Info --}}
<div class="tab-content" id="tab-info">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
            <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $course->tps->count() }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Travaux pratiques</div>
        </div>
        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
            <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $submissions->count() }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Mes soumissions</div>
        </div>
        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
            <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $submissions->filter(fn($s) => $s->grade !== null)->count() }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP notés</div>
        </div>
    </div>
    @if($course->description)
        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 p-5">
            <div class="text-xs uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Description</div>
            <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed m-0">{{ $course->description }}</p>
        </div>
    @endif
</div>
{{-- Tab: TPs --}}
<div class="tab-content hidden" id="tab-tps">
    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-5">
        📝 Travaux Pratiques ({{ $course->tps->count() }})
    </h3>
    @if($course->tps->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach($course->tps as $tp)
                @php
                    $submission   = $submissions->get($tp->id);
                    $hasSubmitted = $submission !== null;
                    $isGraded     = $hasSubmitted && $submission->grade !== null;
                @endphp
                <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-slate-200 dark:border-slate-700
                            p-5 cursor-pointer flex flex-col min-h-[200px]
                            hover:-translate-y-1 hover:border-violet-400 dark:hover:border-violet-500 transition-all shadow-sm"
                     onclick="window.location.href='{{ route('student.tps.show', $tp->id) }}'">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="font-bold text-slate-900 dark:text-slate-100 truncate flex-1">{{ $tp->title }}</div>
                        @if($isGraded)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 shrink-0">✓ Noté</span>
                        @elseif($hasSubmitted)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 shrink-0">✓ Soumis</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 shrink-0">À faire</span>
                        @endif
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2 mb-3 min-h-[2.5rem]">
                        {{ filled($tp->description) ? $tp->description : 'Aucune description' }}
                    </p>
                    <div class="text-xs text-slate-400 dark:text-slate-500 mb-1">
                        📅 {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Pas d\'échéance' }}
                    </div>
                    @if($hasSubmitted)
                        <div class="text-xs text-slate-400 dark:text-slate-500 mb-1">
                            📤 Soumis le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                    @if($isGraded)
                        <div class="inline-block font-mono text-sm font-bold px-3 py-1 rounded-lg mb-3
                                    bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300">
                            🎯 {{ $submission->grade }}/20
                        </div>
                    @endif
                    <div class="mt-auto pt-3">
                        <a href="{{ route('student.tps.show', $tp->id) }}"
                           onclick="event.stopPropagation()"
                           class="block w-full text-center py-2.5 rounded-lg text-sm font-bold text-white transition-colors
                               {{ $isGraded ? 'bg-cyan-600 hover:bg-cyan-700' : ($hasSubmitted ? 'bg-green-600 hover:bg-green-700' : 'bg-violet-600 hover:bg-violet-700') }}">
                            @if($isGraded) Voir ma note &amp; commentaires
                            @elseif($hasSubmitted) Voir ma soumission
                            @else Voir et soumettre @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="text-6xl mb-4">📝</div>
            <h2 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Aucun TP disponible</h2>
            <p class="text-slate-500 dark:text-slate-400">Votre enseignant n'a pas encore publié de travaux pratiques.</p>
        </div>
    @endif
</div>
<style>
.active-tab {
    color: #7c3aed;
    border-bottom-color: #7c3aed;
    font-weight: 600;
}
[data-theme="dark"] .active-tab {
    color: #a78bfa;
    border-bottom-color: #a78bfa;
}
</style>
@endsection
@section('extra-scripts')
<script>
function toggleCourseMenu() {
    document.getElementById('course-menu').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        const menu = document.getElementById('course-menu');
        if (menu) menu.classList.add('hidden');
    }
});
function switchTab(tabName, event) {
    document.querySelectorAll('.tab-btn').forEach(t => {
        t.classList.remove('active-tab');
        t.classList.add('text-slate-500', 'border-transparent');
    });
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    event.target.classList.add('active-tab');
    event.target.classList.remove('text-slate-500', 'border-transparent');
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    history.replaceState(null, null, '#' + tabName);
}
document.addEventListener('DOMContentLoaded', function() {
    const fragment = window.location.hash.replace('#', '');
    if (['info','tps'].includes(fragment)) {
        document.querySelectorAll('.tab-btn')[fragment === 'tps' ? 1 : 0].click();
    }
});
</script>
@endsection
