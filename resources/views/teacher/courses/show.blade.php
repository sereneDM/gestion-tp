@extends('layouts.app')

@section('title', $course->name)
@section('page-title', $course->name)

@section('extra-styles')
<style>
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #334155;
    }
    .tab {
        padding: 1rem 2rem;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        color: #94a3b8;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .tab:hover { color: #a5b4fc; }
    .tab.active {
        color: #c7d2fe;
        border-bottom-color: #8b5cf6;
        font-weight: bold;
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .join-code-box {
        background: #0f172a;
        color: #e2e8f0;
        padding: 2rem;
        border-radius: 1rem;
        text-align: center;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .join-code {
        font-size: 2.5rem;
        font-weight: bold;
        font-family: monospace;
        letter-spacing: 0.1em;
        margin: 1rem 0;
        color: #a5b4fc;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .info-card {
        background: #0f172a;
        padding: 1.5rem;
        border-radius: 1rem;
        text-align: center;
        border: 1px solid #334155;
    }
    .info-number {
        font-size: 2rem;
        font-weight: bold;
        color: #818cf8;
    }
    .info-label {
        color: #94a3b8;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }

    /* ── TP cards ── */
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .tp-card {
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 1rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s;
        display: flex;
        flex-direction: column;
        min-height: 220px;
        position: relative;
    }
    .tp-card:hover {
        transform: translateY(-5px);
        border-color: #475569;
        box-shadow: 0 12px 24px rgba(15,23,42,0.25);
    }
    .tp-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        gap: 0.75rem;
    }
    .tp-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #c7d2fe;
        flex: 1;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: bold;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .status-published { background: rgba(34,197,94,0.15);  color: #86efac; }
    .status-draft     { background: rgba(251,191,36,0.15); color: #facc15; }
    .status-closed    { background: rgba(239,68,68,0.15);  color: #fca5a5; }

    .tp-description {
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 2.88rem;
        max-height: 2.88rem;
    }
    .tp-meta {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.4rem;
    }
    .tp-spacer { flex: 1; }
    .tp-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
        position: relative;
    }

    /* ── TP dropdown menu ── */
    .tp-menu-btn {
        background: #1e293b;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 0.4rem 0.65rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.1rem;
        line-height: 1;
        z-index: 1;
    }
    .tp-menu-dropdown {
        display: none;
        position: absolute;
        bottom: 2.5rem;
        right: 0;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        min-width: 160px;
        z-index: 200;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .tp-menu-dropdown a,
    .tp-menu-dropdown button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        text-decoration: none;
        box-sizing: border-box;
    }
    .tp-menu-dropdown a         { color: #e2e8f0; border-radius: 0.75rem 0.75rem 0 0; }
    .tp-menu-dropdown button    { color: #fca5a5; border-radius: 0 0 0.75rem 0.75rem; }
    .tp-menu-dropdown a:hover,
    .tp-menu-dropdown button:hover { background: #334155; }

    /* ── buttons ── */
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        text-align: center;
        color: #e2e8f0;
    }
    .btn-secondary { background: #475569; color: white; }
    .btn-primary   { background: #4f46e5; color: white; }
    .btn-warning   { background: #f59e0b; color: #1f2937; }
    .btn-danger    { background: #dc3545; color: white; }
    .btn-success   { background: #10b981; color: white; }
    .btn-small     { padding: 0.4rem 0.8rem; font-size: 0.85rem; }

    /* ── students table ── */
    .students-table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
    }
    .students-table thead {
        background: #334155;
        color: #e2e8f0;
    }
    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #475569;
        color: #cbd5e1;
    }
    .students-table tbody tr:hover { background: #1e293b; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    /* ── course-level menu ── */
    #course-menu a:hover,
    #course-menu button:hover {
        background: #334155;
    }
</style>
@endsection

@section('content')

    <!-- Course Actions -->
    <div style="display:flex; justify-content:flex-end; margin-bottom:2rem; position:relative;">
        <button onclick="toggleMenu('course-menu')" style="background:#1e293b; border:1px solid #334155; color:#e2e8f0; padding:0.5rem 0.75rem; border-radius:0.5rem; cursor:pointer; font-size:1.2rem;">⋮</button>
        <div id="course-menu" style="display:none; position:absolute; top:2.5rem; right:0; background:#1e293b; border:1px solid #334155; border-radius:0.75rem; min-width:180px; z-index:100; box-shadow:0 8px 24px rgba(0,0,0,0.4);">
            <a href="{{ route('teacher.courses.edit', $course->id) }}"
               style="display:block; padding:0.75rem 1rem; color:#e2e8f0; text-decoration:none; border-radius:0.75rem 0.75rem 0 0;">
                ✏️ Modifier le cours
            </a>
            <form method="POST" action="{{ route('teacher.courses.regenerate-code', $course->id) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Générer un nouveau code? L\'ancien ne fonctionnera plus.')"
                        style="width:100%; text-align:left; padding:0.75rem 1rem; background:none; border:none; color:#e2e8f0; cursor:pointer;">
                    🔄 Nouveau code
                </button>
            </form>
            <form method="POST" action="{{ route('teacher.courses.destroy', $course->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Supprimer ce cours? Action irréversible.')"
                        style="width:100%; text-align:left; padding:0.75rem 1rem; background:none; border:none; color:#fca5a5; cursor:pointer; border-radius:0 0 0.75rem 0.75rem;">
                    🗑️ Supprimer le cours
                </button>
            </form>
        </div>
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
            <button class="btn btn-secondary" style="margin-top: 1rem;" onclick="copyJoinCode()">
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
            <div style="background:#0f172a; border:1px solid #334155; border-radius:1rem; padding:1.5rem; margin-top:1.5rem;">
                <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; margin-bottom:0.5rem;">Description</div>
                <p style="margin:0; color:#cbd5e1; font-size:0.95rem; line-height:1.6;">{{ $course->description }}</p>
            </div>
        @endif
    </div>

    <!-- Tab: TPs -->
    <div class="tab-content" id="tab-tps">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0; font-size:1.5rem; color:#f1f5f9;">📝 Travaux Pratiques ({{ $course->tps->count() }})</h3>
            <a href="{{ route('teacher.courses.tps.create', $course->id) }}" class="btn btn-success" style="display:inline-block; width:auto;">
                ➕ Créer un TP
            </a>
        </div>

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps->sortBy('created_at') as $tp)
                    <div class="tp-card"
                         onclick="window.location.href='{{ route('teacher.tps.show', $tp->id) }}'">

                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            <span class="status-badge status-{{ $tp->status }}">
                                {{ ucfirst($tp->status) }}
                            </span>
                        </div>

                        <div class="tp-description">
                            @if(filled($tp->description))
                                {{ $tp->description }}
                            @else
                                <span style="font-style:italic;">Aucune description</span>
                            @endif
                        </div>

                        <div class="tp-meta">📅 {{ $tp->due_date ? 'Échéance: ' . $tp->due_date->format('d/m/Y à H:i') : 'Pas d\'échéance' }}</div>
                        <div class="tp-meta">📊 {{ $tp->submissions->count() }} soumission(s)</div>

                        <div class="tp-spacer"></div>

                        <div class="tp-footer">
                            <button class="tp-menu-btn"
                                    onclick="event.stopPropagation(); toggleTpMenu('tp-menu-{{ $tp->id }}')">⋮</button>
                            <div id="tp-menu-{{ $tp->id }}" class="tp-menu-dropdown">
                                <a href="{{ route('teacher.tps.edit', $tp->id) }}"
                                   onclick="event.stopPropagation();">
                                    ✏️ Modifier
                                </a>
                                <form method="POST" action="{{ route('teacher.tps.destroy', $tp->id) }}"
                                      onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="event.stopPropagation(); return confirm('Supprimer ce TP?')">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                <h3>Aucun TP créé</h3>
                <p>Créez votre premier TP pour ce cours</p>
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
    /* ── Course-level menu ── */
    function toggleMenu(id) {
        event.stopPropagation();
        const menu = document.getElementById(id);
        const isOpen = menu.style.display === 'block';
        closeAllMenus();
        menu.style.display = isOpen ? 'none' : 'block';
    }

    /* ── TP-level menus (only closes other tp menus) ── */
    function toggleTpMenu(id) {
        const menu = document.getElementById(id);
        const isOpen = menu.style.display === 'block';
        // close all tp menus
        document.querySelectorAll('[id^="tp-menu-"]').forEach(m => m.style.display = 'none');
        menu.style.display = isOpen ? 'none' : 'block';
    }

    function closeAllMenus() {
        document.querySelectorAll('[id$="-menu"], [id^="tp-menu-"]').forEach(m => m.style.display = 'none');
    }

    /* Close everything when clicking outside */
    document.addEventListener('click', closeAllMenus);

    function switchTab(tabName, event) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
        history.replaceState(null, null, '#' + tabName);
    }

    function copyJoinCode() {
        const code = document.getElementById('joinCode').textContent.trim();
        navigator.clipboard.writeText(code).then(() => {
            alert('Code copié: ' + code);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fragment = window.location.hash.replace('#', '');
        const validTabs = ['info', 'tps', 'students'];
        if (fragment && validTabs.includes(fragment)) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById('tab-' + fragment).classList.add('active');
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.getAttribute('onclick') && tab.getAttribute('onclick').includes("'" + fragment + "'")) {
                    tab.classList.add('active');
                }
            });
        }
    });
</script>
@endsection