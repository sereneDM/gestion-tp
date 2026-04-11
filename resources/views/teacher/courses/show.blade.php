@extends('layouts.teacher')

@section('title', $course->name)
@section('page-title', $course->name)

@section('extra-styles')
<style>
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .tab {
        padding: 1rem 2rem;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        color: #666;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .tab:hover {
        color: #007bff;
    }
    .tab.active {
        color: #007bff;
        border-bottom-color: #007bff;
        font-weight: bold;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .join-code-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 2rem;
    }
    .join-code {
        font-size: 2.5rem;
        font-weight: bold;
        font-family: monospace;
        letter-spacing: 0.1em;
        margin: 1rem 0;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .info-card {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }
    .info-number {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
    }
    .info-label {
        color: #666;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .tp-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1.5rem;
        transition: transform 0.2s;
    }
    .tp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .tp-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .tp-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-published {
        background: #d4edda;
        color: #155724;
    }
    .status-draft {
        background: #fff3cd;
        color: #856404;
    }
    .status-closed {
        background: #f8d7da;
        color: #721c24;
    }
    .tp-meta {
        color: #666;
        font-size: 0.9rem;
        margin: 0.5rem 0;
    }
    .tp-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        text-align: center;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-warning {
        background: #ffc107;
        color: #333;
    }
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    .btn-success {
        background: #28a745;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .students-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    .students-table thead {
        background: #007bff;
        color: white;
    }
    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .students-table tbody tr:hover {
        background: #f8f9fa;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }
    .course-actions {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        justify-content: flex-end;
    }
    .danger-zone {
        background: #fff5f5;
        border: 1px solid #f8d7da;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    .danger-zone h3 {
        color: #dc3545;
        margin-top: 0;
        margin-bottom: 0.5rem;
    }
    .danger-zone p {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')

    

    <!-- Course Actions -->
    <div class="course-actions">
        <a href="{{ route('teacher.courses.edit', $course->id) }}" class="btn btn-warning">
            ✏️ Modifier le cours
        </a>
        <form method="POST" action="{{ route('teacher.courses.regenerate-code', $course->id) }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="return confirm('Générer un nouveau code? L\'ancien ne fonctionnera plus.')">
                🔄 Nouveau code
            </button>
        </form>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('info', event)">📋 Informations</button>
        <button class="tab" onclick="switchTab('tps', event)">📝 Travaux Pratiques</button>
        <button class="tab" onclick="switchTab('students', event)">👥 Étudiants</button>
    </div>

    <!-- Tab: Course Info -->
    <div class="tab-content active" id="tab-info">
        <div class="join-code-box">
            <div style="font-size: 0.9rem; opacity: 0.9;">Code d'accès au cours</div>
            <div class="join-code" id="joinCode">{{ $course->join_code }}</div>
            <button class="btn" style="background: white; color: #667eea; margin-top: 1rem;" onclick="copyJoinCode()">
                📋 Copier le code
            </button>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-number">{{ $course->students->count() }}</div>
                <div class="info-label">Étudiants inscrits</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $course->tps->count() }}</div>
                <div class="info-label">Travaux pratiques</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $course->tps->where('status', 'published')->count() }}</div>
                <div class="info-label">TP publiés</div>
            </div>
        </div>

        @if($course->description)
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-top: 1.5rem;">
                <h3 style="margin-top: 0;">Description du cours</h3>
                <p style="margin: 0;">{{ $course->description }}</p>
            </div>
        @endif

        <!-- Danger Zone -->
        <div class="danger-zone">
            <h3>🗑️ Zone de danger</h3>
            <p>La suppression du cours est irréversible. Tous les TPs et soumissions associés seront également supprimés.</p>
            <form method="POST" action="{{ route('teacher.courses.destroy', $course->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours? Cette action est irréversible.')">
                    🗑️ Supprimer le cours
                </button>
            </form>
        </div>
    </div>

    <!-- Tab: TPs -->
    <div class="tab-content" id="tab-tps">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0;">Travaux Pratiques</h2>
            <a href="{{ route('teacher.courses.tps.create', $course->id) }}" class="btn btn-success">
                ➕ Créer un TP
            </a>
        </div>

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps->sortBy('created_at') as $tp)
                    <div class="tp-card"
                         onclick="window.location.href='{{ route('teacher.tps.show', $tp->id) }}'"
                         style="cursor: pointer;">
                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            <span class="status-badge status-{{ $tp->status }}">
                                {{ ucfirst($tp->status) }}
                            </span>
                        </div>

                        <div class="tp-meta">
                            @if($tp->due_date)
                                📅 Échéance: {{ $tp->due_date->format('d/m/Y') }}
                            @else
                                📅 Pas d'échéance
                            @endif
                        </div>

                        <div class="tp-meta">
                            📊 {{ $tp->submissions->count() }} soumission(s)
                        </div>

                        <div class="tp-actions">
                            <a href="{{ route('teacher.tps.show', $tp->id) }}" class="btn btn-primary btn-small" onclick="event.stopPropagation();">
                                👁️ Voir
                            </a>
                            <a href="{{ route('teacher.tps.edit', $tp->id) }}" class="btn btn-warning btn-small" onclick="event.stopPropagation();">
                                ✏️ Modifier
                            </a>
                            <form method="POST" action="{{ route('teacher.tps.destroy', $tp->id) }}" style="display: inline;" onclick="event.stopPropagation();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small"
                                        onclick="return confirm('Supprimer ce TP?')">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                <h3>Aucun TP créé</h3>
                <p>Créez votre premier TP pour ce cours</p>
                <a href="{{ route('teacher.courses.tps.create', $course->id) }}" class="btn btn-success" style="margin-top: 1rem;">
                    ➕ Créer un TP
                </a>
            </div>
        @endif
    </div>

    <!-- Tab: Students -->
    <div class="tab-content" id="tab-students">
        <h2>👥 Étudiants Inscrits ({{ $course->students->count() }})</h2>

        @if($course->students->count() > 0)
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('teacher.courses.remove-student', [$course->id, $student->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small"
                                            onclick="return confirm('Retirer cet étudiant?')">
                                        ✗ Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">👥</div>
                <h3>Aucun étudiant inscrit</h3>
                <p>Partagez le code d'accès avec vos étudiants pour qu'ils rejoignent le cours</p>
            </div>
        @endif
    </div>
@endsection
@section('extra-scripts')
<script>
    function switchTab(tabName, event) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        event.target.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');

        // update URL fragment without reloading
        history.replaceState(null, null, '#' + tabName);
    }

    function copyJoinCode() {
        const code = document.getElementById('joinCode').textContent;
        navigator.clipboard.writeText(code).then(() => {
            alert('Code copié: ' + code);
        });
    }

    // on page load, activate tab from URL fragment
    document.addEventListener('DOMContentLoaded', function () {
        const fragment = window.location.hash.replace('#', '');
        const validTabs = ['info', 'tps', 'students'];
        if (fragment && validTabs.includes(fragment)) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            document.getElementById('tab-' + fragment).classList.add('active');
            // activate the matching tab button
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.getAttribute('onclick').includes("'" + fragment + "'")) {
                    tab.classList.add('active');
                }
            });
        }
    });
</script>
@endsection