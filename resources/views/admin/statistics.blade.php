@extends('layouts.app')

@section('title', 'Statistiques Globales')
@section('page-title', 'Statistiques Globales du Système')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        background: #1e293b;
        color: #e2e8f0;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn:hover {
        background: #334155;
        border-color: #475569;
    }
    .btn-secondary {
        background-color: #1e293b;
        color: #e2e8f0;
    }
    .btn-secondary:hover {
        background: #334155;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #0f172a;
        padding: 1.5rem;
        border-radius: 1rem;
        border: 1px solid #334155;
        text-align: center;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #818cf8;
    }
    .stat-label {
        color: #94a3b8;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .section {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .section h2 {
        color: #c7d2fe;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .section-heading {
        color: #cbd5e1;
        margin-bottom: 1rem;
        margin-top: 2rem;
    }
    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
    }
    th {
        background-color: #1e293b;
        font-weight: bold;
        color: #cbd5e1;
    }
    td {
        color: #e2e8f0;
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
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
</style>
@endsection

@section('content')
    


    <h3 class="section-heading">👥 Utilisateurs</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #28a745;">{{ $totalStudents }}</div>
            <div class="stat-label">Étudiants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #ffc107;">{{ $totalTeachers }}</div>
            <div class="stat-label">Enseignants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #dc3545;">{{ $totalAdmins }}</div>
            <div class="stat-label">Administrateurs</div>
        </div>
    </div>

    <h3 class="section-heading">📚 Classes</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalClasses }}</div>
            <div class="stat-label">Total classes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $classesWithStudents }}</div>
            <div class="stat-label">Classes avec étudiants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $classesWithTeachers }}</div>
            <div class="stat-label">Classes avec enseignants</div>
        </div>
    </div>

    <h3 class="section-heading">📝 Travaux Pratiques</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalTPs }}</div>
            <div class="stat-label">Total TP</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #28a745;">{{ $publishedTPs }}</div>
            <div class="stat-label">TP publiés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #ffc107;">{{ $draftTPs }}</div>
            <div class="stat-label">Brouillons</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalSubmissions }}</div>
            <div class="stat-label">Total soumissions</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #28a745;">{{ $gradedSubmissions }}</div>
            <div class="stat-label">Notées</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #ffc107;">{{ $pendingSubmissions }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $averageGrade ? number_format($averageGrade, 2) : 'N/A' }}</div>
            <div class="stat-label">Moyenne générale</div>
        </div>
    </div>

    <h3 class="section-heading">✓ Présences</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalAttendances }}</div>
            <div class="stat-label">Total enregistrements</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #28a745;">{{ $presentCount }}</div>
            <div class="stat-label">Présents</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #dc3545;">{{ $absentCount }}</div>
            <div class="stat-label">Absents</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #ffc107;">{{ $lateCount }}</div>
            <div class="stat-label">Retards</div>
        </div>
    </div>

    <div class="grid-2col">
        <div class="section">
            <h2>🏆 Meilleurs étudiants</h2>
            @if($topStudents->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Moyenne</th>
                            <th>TP soumis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topStudents as $item)
                            <tr>
                                <td>{{ $item->student->name }}</td>
                                <td><strong>{{ number_format($item->avg_grade, 2) }}/20</strong></td>
                                <td>{{ $item->total_submissions }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #999; padding: 2rem;">Aucune donnée</p>
            @endif
        </div>

        <div class="section">
            <h2>👨‍🏫 Enseignants les plus actifs</h2>
            @if($activeTeachers->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Enseignant</th>
                            <th>TP créés</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeTeachers as $item)
                            <tr>
                                <td>{{ $item->teacher->name }}</td>
                                <td><strong>{{ $item->tps_count }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #999; padding: 2rem;">Aucune donnée</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h2>📋 Soumissions récentes</h2>
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
                            <td>{{ $submission->grade ? $submission->grade . '/20' : 'En attente' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 2rem;">Aucune soumission</p>
        @endif
    </div>
@endsection