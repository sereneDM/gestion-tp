<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Progression</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
        }
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
        .status-present {
            background-color: #28a745;
            color: white;
        }
        .status-absent {
            background-color: #dc3545;
            color: white;
        }
        .status-late {
            background-color: #ffc107;
            color: #333;
        }
        .status-excused {
            background-color: #17a2b8;
            color: white;
        }
        .chart-container {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        .grade-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .grade-item:last-child {
            border-bottom: none;
        }
        .grade-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Ma Progression Académique</h1>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalSubmissions }}</div>
                <div class="stat-label">TP soumis</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $gradedSubmissions }}</div>
                <div class="stat-label">TP notés</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $pendingSubmissions }}</div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}</div>
                <div class="stat-label">Moyenne</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $attendanceStats['present'] }}</div>
                <div class="stat-label">Présences</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $attendanceStats['absent'] }}</div>
                <div class="stat-label">Absences</div>
            </div>
        </div>

        <!-- Grades by TP -->
        <div class="section">
            <h2>Mes Notes par TP</h2>
            @if($gradesByTP->count() > 0)
                <div class="chart-container">
                    @foreach($gradesByTP as $item)
                        <div class="grade-item">
                            <div>
                                <strong>{{ $item['tp']->title }}</strong>
                                <div style="color: #666; font-size: 0.9rem; margin-top: 0.25rem;">
                                    Enseignant: {{ $item['tp']->teacher->name }}
                                </div>
                                <div style="color: #999; font-size: 0.85rem; margin-top: 0.25rem;">
                                    Soumis le: {{ $item['submitted_at']->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="grade-value">{{ number_format($item['grade'], 2) }}/20</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: #999; padding: 2rem;">
                    Aucune note disponible pour le moment
                </p>
            @endif
        </div>

        <!-- Attendance History -->
        <div class="section">
            <h2>Historique de Présence</h2>
            @if($attendances->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Classe</th>
                            <th>Statut</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances->take(20) as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>{{ $attendance->class->name }}</td>
                                <td>
                                    <span class="status-badge status-{{ $attendance->status }}">
                                        @if($attendance->status === 'present')
                                            ✓ Présent
                                        @elseif($attendance->status === 'absent')
                                            ✗ Absent
                                        @elseif($attendance->status === 'late')
                                            ⏰ Retard
                                        @else
                                            📝 Excusé
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #999; padding: 2rem;">
                    Aucun enregistrement de présence
                </p>
            @endif
        </div>
    </div>
</body>
</html>