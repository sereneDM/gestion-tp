@extends('layouts.app')
@section('title', 'Statistiques Globales')

@section('content')

{{-- Stat card macro as a reusable pattern --}}
@php
function statCard($number, $label, $color = 'violet') {
    $colors = ['violet'=>'text-violet-600 dark:text-violet-400','green'=>'text-green-600 dark:text-green-400','amber'=>'text-amber-500 dark:text-amber-400','red'=>'text-red-600 dark:text-red-400'];
    $c = $colors[$color] ?? $colors['violet'];
    return "<div class=\"bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600\"><div class=\"text-3xl font-bold {$c} mb-1\">{$number}</div><div class=\"text-xs text-slate-500 dark:text-slate-400\">{$label}</div></div>";
}
@endphp

{{-- Users --}}
<h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 mt-2">👥 Utilisateurs</h3>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $totalUsers }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Total</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">{{ $totalStudents }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Étudiants</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-amber-500 dark:text-amber-400 mb-1">{{ $totalTeachers }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Enseignants</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-red-600 dark:text-red-400 mb-1">{{ $totalAdmins }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Administrateurs</div>
    </div>
</div>

{{-- Classes --}}
<h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">📚 Classes</h3>
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $totalClasses }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Total classes</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $classesWithStudents }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Avec étudiants</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $classesWithTeachers }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Avec enseignants</div>
    </div>
</div>

{{-- TPs --}}
<h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">📝 Travaux Pratiques</h3>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $totalTPs }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Total TP</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">{{ $publishedTPs }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Publiés</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-amber-500 dark:text-amber-400 mb-1">{{ $draftTPs }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Brouillons</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $totalSubmissions }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Soumissions</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">{{ $gradedSubmissions }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Notées</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-amber-500 dark:text-amber-400 mb-1">{{ $pendingSubmissions }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">En attente</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $averageGrade ? number_format($averageGrade,2) : 'N/A' }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Moyenne /20</div>
    </div>
</div>

{{-- Attendance --}}
<h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">✓ Présences</h3>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-violet-600 dark:text-violet-400 mb-1">{{ $totalAttendances }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Total</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">{{ $presentCount }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Présents</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-red-600 dark:text-red-400 mb-1">{{ $absentCount }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Absents</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-600">
        <div class="text-3xl font-bold text-amber-500 dark:text-amber-400 mb-1">{{ $lateCount }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">Retards</div>
    </div>
</div>

{{-- Tables --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    {{-- Top students --}}
    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-5">
        <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-4 pb-2 border-b border-slate-200 dark:border-slate-600">🏆 Meilleurs étudiants</h2>
        @if($topStudents->count() > 0)
            <table class="w-full text-sm">
                <thead><tr class="text-left">
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Étudiant</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Moyenne</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">TP</th>
                </tr></thead>
                <tbody>
                    @foreach($topStudents as $item)
                        <tr class="border-t border-slate-200 dark:border-slate-600">
                            <td class="py-2 text-slate-700 dark:text-slate-200">{{ $item->student->name }}</td>
                            <td class="py-2 font-bold text-green-600 dark:text-green-400">{{ number_format($item->avg_grade,2) }}/20</td>
                            <td class="py-2 text-slate-500 dark:text-slate-400">{{ $item->total_submissions }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-slate-400 py-6 text-sm">Aucune donnée</p>
        @endif
    </div>

    {{-- Active teachers --}}
    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-5">
        <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-4 pb-2 border-b border-slate-200 dark:border-slate-600">👨‍🏫 Enseignants actifs</h2>
        @if($activeTeachers->count() > 0)
            <table class="w-full text-sm">
                <thead><tr class="text-left">
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Enseignant</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">TP créés</th>
                </tr></thead>
                <tbody>
                    @foreach($activeTeachers as $item)
                        <tr class="border-t border-slate-200 dark:border-slate-600">
                            <td class="py-2 text-slate-700 dark:text-slate-200">{{ $item->teacher->name }}</td>
                            <td class="py-2 font-bold text-violet-600 dark:text-violet-400">{{ $item->tps_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-slate-400 py-6 text-sm">Aucune donnée</p>
        @endif
    </div>
</div>

{{-- Recent submissions --}}
<div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-5">
    <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-4 pb-2 border-b border-slate-200 dark:border-slate-600">📋 Soumissions récentes</h2>
    @if($recentSubmissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left">
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Étudiant</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">TP</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Date</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Statut</th>
                    <th class="pb-2 text-slate-500 dark:text-slate-400 font-medium">Note</th>
                </tr></thead>
                <tbody>
                    @foreach($recentSubmissions as $submission)
                        <tr class="border-t border-slate-200 dark:border-slate-600">
                            <td class="py-2 text-slate-700 dark:text-slate-200">{{ $submission->student->name }}</td>
                            <td class="py-2 text-slate-600 dark:text-slate-300">{{ $submission->tp->title }}</td>
                            <td class="py-2 text-slate-500 dark:text-slate-400">{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                    @if($submission->grade) bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                    @else bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 @endif">
                                    {{ $submission->grade ? 'Noté' : 'En attente' }}
                                </span>
                            </td>
                            <td class="py-2 font-medium text-slate-700 dark:text-slate-200">
                                {{ $submission->grade ? $submission->grade.'/20' : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-slate-400 py-6 text-sm">Aucune soumission</p>
    @endif
</div>

@endsection