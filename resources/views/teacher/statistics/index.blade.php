@extends('layouts.teacher')

@section('title', 'Statistiques Pédagogiques')
@section('page-title', 'Statistiques Pédagogiques')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #007bff;
    }
    .stat-label {
        color: #666;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section h2 {
        color: #007bff;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    .chart-container {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
    }
    .chart-bar {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .chart-label {
        min-width: 100px;
        font-weight: bold;
        color: #555;
    }
    .chart-bar-fill {
        flex: 1;
        height: 30px;
        background: #007bff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        color: white;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555;
    }
    tr:hover {
        background-color: #f8f9fa;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-graded {
        background-color: #28a745;
        color: white;
    }
    .status-submitted {
        background-color: #ffc107;
        color: #333;
    }
</style>
@endsection

@section('content')
    <div class="header-actions">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">← Retour</a>
    </div>

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
        <div class="section">
            <h2>Distribution des notes</h2>
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
                        <div class="chart-bar-fill" style="width: {{ $percentage }}%;">
                            {{ $count }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section">
            <h2>Étudiants par classe</h2>
            <div class="chart-container">
                @forelse($classes as $class)
                    @php
                        $maxStudents = $classes->max('students_count') ?: 1;
                        $percentage = ($class->students_count / $maxStudents) * 100;
                    @endphp
                    <div class="chart-bar">
                        <div class="chart-label">{{ $class->name }}</div>
                        <div class="chart-bar-fill" style="width: {{ $percentage }}%;">
                            {{ $class->students_count }}
                        </div>
                    </div>
                @empty
                    <p style="color: #999; text-align: center;">Aucune classe</p>
                @endforelse
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
                            <td>
                                <strong>{{ $submission->grade ? $submission->grade . '/20' : 'En attente' }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">
                Aucune soumission récente
            </p>
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
@endsection