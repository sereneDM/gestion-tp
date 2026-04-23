@extends('layouts.app')

@section('title', 'Statistiques Pédagogiques')
@section('page-title', 'Statistiques Pédagogiques')

@section('extra-styles')
<style>
    .btn { padding: 0.6rem 1.2rem; border: none; border-radius: 0.75rem; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; color: #e2e8f0; }
    .btn-primary { background-color: #4f46e5; color: white; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: #0f172a; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 12px 24px rgba(15,23,42,0.25); text-align: center; border: 1px solid #334155; }
    .stat-number { font-size: 2.5rem; font-weight: bold; color: #818cf8; }
    .stat-label { color: #94a3b8; margin-top: 0.5rem; font-size: 0.9rem; }
    .section { background: #0f172a; padding: 2rem; border-radius: 1rem; margin-bottom: 2rem; box-shadow: 0 12px 24px rgba(15,23,42,0.25); border: 1px solid #334155; }
    .section h2 { color: #c7d2fe; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #334155; }
    .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .chart-container { background: #0f172a; padding: 1.5rem; border-radius: 1rem; border: 1px solid #334155; }
    .chart-bar { display: flex; align-items: center; margin-bottom: 1rem; }
    .chart-label { min-width: 100px; font-weight: bold; color: #cbd5e1; }
    .chart-bar-fill { flex: 1; height: 30px; background: #4f46e5; border-radius: 9999px; display: flex; align-items: center; padding: 0 1rem; color: white; font-weight: bold; min-width: 2rem; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #334155; color: #cbd5e1; }
    th { background-color: #334155; font-weight: bold; color: #e2e8f0; }
    tr:hover { background-color: #1e293b; }
    .status-badge { padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.85rem; font-weight: bold; display: inline-block; }
    .status-graded { background-color: rgba(34,197,94,0.15); color: #86efac; }
    .status-submitted { background-color: rgba(251,191,36,0.15); color: #facc15; }
    .course-filter { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .course-filter select { padding: 0.5rem 0.75rem; border: 1px solid #334155; border-radius: 0.75rem; font-size: 0.9rem; color: #e2e8f0; background: #0f172a; }
    .course-filter label { color: #cbd5e1; }
</style>
@endsection

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalTPs }}</div>
            <div class="stat-label">Total TP créés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $publishedTPs }}</div>
            <div class="stat-label">TP publiés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalSubmissions }}</div>
            <div class="stat-label">Total soumissions</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pendingSubmissions }}</div>
            <div class="stat-label">À corriger</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $gradedSubmissions }}</div>
            <div class="stat-label">Notés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}</div>
            <div class="stat-label">Moyenne générale</div>
        </div>
    </div>

    <div class="grid-2col" style="margin-bottom: 2rem;">

        {{-- Étudiants par classe (now on the LEFT) --}}
        <div class="section">
            <h2>Étudiants par cours</h2>
            <div class="chart-container">
                @forelse($classes as $class)
                    @php
                        $maxStudents = $classes->max('students_count') ?: 1;
                        $percentage = ($class->students_count / $maxStudents) * 100;
                    @endphp
                    <div class="chart-bar">
                        <div class="chart-label">{{ $class->name }}</div>
                        <div class="chart-bar-fill" style="width: {{ max($percentage, 5) }}%;">
                            {{ $class->students_count }}
                        </div>
                    </div>
                @empty
                    <p style="color: #999; text-align: center;">Aucun cours</p>
                @endforelse
            </div>
        </div>

        {{-- Distribution des notes (now on the RIGHT) with course filter --}}
        <div class="section">
            <h2>Distribution des notes</h2>

            <form method="GET" action="{{ route('teacher.statistics') }}" class="course-filter">
                <label for="class_id" style="font-weight: bold; color: #555; white-space: nowrap;">
                    Filtrer par cours:
                </label>
                <select name="class_id" id="class_id" onchange="this.form.submit()">
                    <option value="">— Tous les cours —</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @if($selectedClassId)
                    <a href="{{ route('teacher.statistics') }}" 
                       class="btn btn-primary" 
                       style="font-size:0.8rem; padding: 0.4rem 0.8rem;"
                       onclick="sessionStorage.setItem('stats_scroll_pos', window.scrollY)">
                        ✕ Réinitialiser
                    </a>
                @endif
            </form>

            <div class="chart-container">
                @php
                    $ranges = ['0-10', '10-12', '12-14', '14-16', '16-20'];
                    $maxCount = $gradeDistribution->max() ?: 1;
                @endphp
                @foreach($ranges as $range)
                    @php
                        $count = $gradeDistribution->get($range, 0);
                        $percentage = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                    @endphp
                    <div class="chart-bar">
                        <div class="chart-label">{{ $range }}</div>
                        <div class="chart-bar-fill" style="width: {{ max($percentage, 5) }}%;">
                            {{ $count }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    @if($attendanceStats->count() > 0)
        <div class="section">
            <h2>Statistiques de présence</h2>
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                <div class="stat-card">
                    <div class="stat-number" style="color: #28a745;">{{ $attendanceStats->get('present', 0) }}</div>
                    <div class="stat-label">Présents</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #dc3545;">{{ $attendanceStats->get('absent', 0) }}</div>
                    <div class="stat-label">Absents</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #ffc107;">{{ $attendanceStats->get('late', 0) }}</div>
                    <div class="stat-label">Retards</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #17a2b8;">{{ $attendanceStats->get('excused', 0) }}</div>
                    <div class="stat-label">Excusés</div>
                </div>
            </div>
        </div>
    @endif

    <div class="section">
        <h2>Soumissions récentes</h2>
        @if($recentSubmissions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>TP</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubmissions as $submission)
                        <tr>
                            <td>{{ $submission->student->name }}</td>
                            <td>{{ $submission->tp->title }}</td>
                            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td><strong>{{ $submission->grade ? $submission->grade . '/20' : 'En attente' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">Aucune soumission récente</p>
        @endif
    </div>

    <div class="grid-2col">
        <div class="section">
            <h2>Taux de réussite</h2>
            @php
                $passRate = $gradedSubmissions > 0
                    ? (($gradeDistribution->get('10-12', 0) + $gradeDistribution->get('12-14', 0) + $gradeDistribution->get('14-16', 0) + $gradeDistribution->get('16-20', 0)) / $gradedSubmissions) * 100
                    : 0;
            @endphp
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; font-weight: bold; color: {{ $passRate >= 50 ? '#28a745' : '#dc3545' }};">
                    {{ number_format($passRate, 1) }}%
                </div>
                <p style="color: #666; margin-top: 1rem;">Notes ≥ 10/20</p>
            </div>
        </div>

        <div class="section">
            <h2>Taux de correction</h2>
            @php
                $correctionRate = $totalSubmissions > 0
                    ? ($gradedSubmissions / $totalSubmissions) * 100
                    : 0;
            @endphp
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; font-weight: bold; color: {{ $correctionRate >= 75 ? '#28a745' : '#ffc107' }};">
                    {{ number_format($correctionRate, 1) }}%
                </div>
                <p style="color: #666; margin-top: 1rem;">Soumissions notées</p>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Restore scroll position after filter change
    const scrollKey = 'stats_scroll_pos';
    const savedPos = sessionStorage.getItem(scrollKey);
    if (savedPos) {
        window.scrollTo(0, parseInt(savedPos));
        sessionStorage.removeItem(scrollKey);
    }

    // Save scroll position before form submits
    const filterSelect = document.querySelector('select[name="class_id"]');
    if (filterSelect) {
        filterSelect.addEventListener('change', function () {
            sessionStorage.setItem(scrollKey, window.scrollY);
            this.closest('form').submit();
        });
    }
});
</script>
@endsection