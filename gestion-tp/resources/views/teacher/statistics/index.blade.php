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

    /* ── Chart bars ── */
    .chart-bar {
        display: flex;
        flex-direction: column;
        margin-bottom: 1.25rem;
    }
    .chart-bar:last-child { margin-bottom: 0; }
    .chart-bar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.4rem;
    }
    .chart-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #cbd5e1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 70%;
    }
    .chart-count {
        font-size: 0.85rem;
        font-weight: bold;
        color: #818cf8;
        white-space: nowrap;
    }
    .chart-track {
        width: 100%;
        background: #1e293b;
        border-radius: 9999px;
        height: 10px;
        overflow: hidden;
    }
    .chart-bar-fill {
        height: 10px;
        background: linear-gradient(90deg, #4f46e5, #818cf8);
        border-radius: 9999px;
        transition: width 0.6s ease;
        min-width: 4px;
    }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #334155; color: #cbd5e1; }
    th { background-color: #334155; font-weight: bold; color: #e2e8f0; }
    tr:hover { background-color: #1e293b; }
    .status-badge { padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.85rem; font-weight: bold; display: inline-block; }
    .status-graded { background-color: rgba(34,197,94,0.15); color: #86efac; }
    .status-submitted { background-color: rgba(251,191,36,0.15); color: #facc15; }

    /* ── Filter bar ── */
    .course-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .course-filter label {
        font-size: 0.9rem;
        color: #94a3b8;
        white-space: nowrap;
        font-weight: bold;
    }
    .course-filter select,
    .course-filter input[type="text"] {
        background: #1e293b;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        outline: none;
    }
    .course-filter select { cursor: pointer; }
    .course-filter input[type="text"] { flex: 1; min-width: 180px; }
    .course-filter select:focus,
    .course-filter input[type="text"]:focus { border-color: #6366f1; }
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

        <!-- Étudiants par cours -->
        <div class="section">
            <h2>Étudiants par cours</h2>
            <div class="chart-container">
                @forelse($classes as $class)
                    @php
                        $maxStudents = $classes->max('students_count') ?: 1;
                        $percentage = ($class->students_count / $maxStudents) * 100;
                    @endphp
                    <div class="chart-bar">
                        <div class="chart-bar-header">
                            <span class="chart-label" title="{{ $class->name }}">{{ $class->name }}</span>
                            <span class="chart-count">{{ $class->students_count }} étudiant(s)</span>
                        </div>
                        <div class="chart-track">
                            <div class="chart-bar-fill" style="width: {{ max($percentage, 1) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color: #64748b; text-align: center;">Aucun cours</p>
                @endforelse
            </div>
        </div>

        <!-- Distribution des notes -->
        <div class="section">
            <h2>Distribution des notes</h2>

            <div class="course-filter">
                <label for="grade-search">🔍</label>
                <input type="text" id="grade-search" placeholder="Rechercher un cours..."
                       oninput="filterGradeCourse()">
                <label for="grade-select">ou</label>
                <select id="grade-select" onchange="selectGradeCourse(this.value)">
                    <option value="">— Tous les cours —</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="chart-container" id="grade-chart">
                {{-- Populated by JS --}}
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
            <p style="text-align: center; color: #64748b; padding: 2rem;">Aucune soumission récente</p>
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
                <div style="font-size: 3rem; font-weight: bold; color: {{ $passRate >= 50 ? '#86efac' : '#fca5a5' }};">
                    {{ number_format($passRate, 1) }}%
                </div>
                <p style="color: #64748b; margin-top: 1rem;">Notes ≥ 10/20</p>
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
                <div style="font-size: 3rem; font-weight: bold; color: {{ $correctionRate >= 75 ? '#86efac' : '#fde68a' }};">
                    {{ number_format($correctionRate, 1) }}%
                </div>
                <p style="color: #64748b; margin-top: 1rem;">Soumissions notées</p>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
<script>
    const allDistribution = @json($gradeDistribution);
    const classesList = @json($classes->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));
    const perClassDistribution = @json($gradeDistributionPerClass ?? []);
    const ranges = ['0-10', '10-12', '12-14', '14-16', '16-20'];

    function renderChart(distribution) {
        const chart = document.getElementById('grade-chart');
        const max = Math.max(...ranges.map(r => distribution[r] ?? 0), 1);
        chart.innerHTML = ranges.map(range => {
            const count = distribution[range] ?? 0;
            const pct = max > 0 ? Math.max((count / max) * 100, count > 0 ? 1 : 0) : 0;
            return `
                <div class="chart-bar">
                    <div class="chart-bar-header">
                        <span class="chart-label">${range}/20</span>
                        <span class="chart-count">${count} soumission(s)</span>
                    </div>
                    <div class="chart-track">
                        <div class="chart-bar-fill" style="width:${pct}%"></div>
                    </div>
                </div>`;
        }).join('');
    }

    renderChart(allDistribution);

    function filterGradeCourse() {
        const query = document.getElementById('grade-search').value.toLowerCase().trim();
        const select = document.getElementById('grade-select');

        Array.from(select.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = !query || opt.text.toLowerCase().includes(query) ? '' : 'none';
        });

        const visible = Array.from(select.options).filter(o => o.value && o.style.display !== 'none');
        if (visible.length === 1) {
            select.value = visible[0].value;
            selectGradeCourse(visible[0].value);
        } else {
            select.value = '';
            renderChart(allDistribution);
        }
    }

    function selectGradeCourse(classId) {
        if (!classId) {
            renderChart(allDistribution);
            return;
        }
        const dist = perClassDistribution[classId] ?? {};
        renderChart(dist);
        document.getElementById('grade-search').value =
            classesList.find(c => c.id == classId)?.name ?? '';
    }
</script>
@endsection