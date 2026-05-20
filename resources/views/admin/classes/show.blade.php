@extends('layouts.admin')

@section('title', 'Détails de la Classe')

@section('breadcrumb')
    <a href="{{ route('admin.classes.index') }}" class="tb-bc-page" style="text-decoration:none;">Cours</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">{{ $class->name }}</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.classes.edit', $class->id) }}" class="tb-btn tb-btn-secondary">
        <i class="ti ti-edit"></i> Modifier
    </a>
@endsection

@section('extra-styles')
<style>
    /* ── Tabs ── */
    .stabs {
        display: flex;
        gap: 0.2rem;
        margin-bottom: 1.75rem;
        border-bottom: 1px solid var(--line);
    }

    .stab {
        padding: 0.75rem 1.2rem;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--ink-3);
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.15s;
    }

    .stab i { font-size: 15px; }
    .stab:hover { color: var(--ink-2); }
    .stab.active { color: var(--accent); border-bottom-color: var(--accent); font-weight: 600; }

    .stab-content { display: none; }
    .stab-content.active { display: block; }

    /* ── Teacher card ── */
    .teacher-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.4rem 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        gap: 1.1rem;
    }

    .teacher-avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-bg), #dde4ff);
        border: 3px solid var(--surface-2);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--accent);
    }

    .teacher-info { flex: 1; min-width: 0; }

    .teacher-micro-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--ink-4);
        margin-bottom: 0.25rem;
    }

    .teacher-name { font-size: 1rem; font-weight: 700; color: var(--ink); }

    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 0.35rem;
        padding: 0.18rem 0.6rem;
        background: var(--accent-bg);
        color: var(--accent);
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .teacher-email {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 0.35rem;
        margin-left: 0.5rem;
        font-size: 0.78rem;
        color: var(--ink-4);
        text-decoration: none;
        transition: color .15s;
    }
    .teacher-email:hover { color: var(--accent); }

    /* ── Stat strip ── */
    .sstat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .sstat-tile {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.1rem 1.25rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .sstat-val {
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--ink);
        line-height: 1;
    }

    .sstat-lbl { font-size: 0.78rem; color: var(--ink-3); margin-top: 0.35rem; }

    /* ── Description card ── */
    .desc-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.2rem 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.1rem;
    }

    .micro-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink-4);
        margin-bottom: 0.5rem;
    }

    .desc-text { font-size: 0.9rem; color: var(--ink-2); line-height: 1.7; }

    /* ── Join code strip ── */
    .join-code-strip {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.2rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        box-shadow: var(--shadow-sm);
        flex-wrap: wrap;
    }

    .jc-left { display: flex; flex-direction: column; gap: 0.2rem; }

    .jc-code {
        font-size: 1.6rem;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: 0.12em;
        color: var(--accent);
    }

    .btn-copy-code {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface-2);
        color: var(--ink-2);
        font-size: 0.82rem;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
    }
    .btn-copy-code:hover { background: var(--surface-3); }

    /* ── Danger zone ── */
    .danger-zone {
        margin-top: 1.5rem;
        padding: 1.1rem 1.25rem;
        background: #fff5f5;
        border: 1px solid rgba(229,57,53,0.2);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .danger-zone-text { font-size: 0.875rem; color: var(--ink-2); }
    .danger-zone-text strong { color: var(--ink); font-weight: 700; }
    .danger-zone-hint { font-size: 0.78rem; color: var(--ink-4); margin-top: 2px; }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.1rem;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(229,57,53,0.3);
        background: #fff0f0;
        color: var(--danger);
        font-size: 0.82rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-danger:hover { background: #ffe0e0; }

    /* ── Student table card ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--line);
        background: var(--surface-2);
    }

    .table-card-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--ink-4);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .scount-badge {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--ink-4);
        background: var(--surface-3);
        border: 1px solid var(--line);
        border-radius: 100px;
        padding: 0.1rem 0.5rem;
    }

    /* ── Student search ── */
    .student-search-wrap {
        padding: 10px 12px;
        border-bottom: 1px solid var(--line);
        position: relative;
    }

    .student-search-wrap i {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: var(--ink-4);
        pointer-events: none;
    }

    .student-search {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        font-family: inherit;
        background: var(--surface-2);
        color: var(--ink);
        outline: none;
        transition: border-color .2s;
    }

    .student-search:focus { border-color: var(--accent); }

    /* ── Table ── */
    .s-table { width: 100%; border-collapse: collapse; }
    .s-table thead { background: var(--surface-2); }
    .s-table th {
        padding: 0.75rem 1.25rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ink-4);
        border-bottom: 1px solid var(--line);
    }
    .s-table td {
        padding: 0.85rem 1.25rem;
        font-size: 0.875rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--line);
    }
    .s-table tbody tr:last-child td { border-bottom: none; }
    .s-table tbody tr:hover { background: var(--surface-2); }
    .s-table tbody tr.row-hidden { display: none; }

    .student-name-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-av {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-bg);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--ink-4);
    }
    .empty-state i { font-size: 2.2rem; display: block; margin-bottom: 0.85rem; opacity: 0.35; }
    .empty-state p { font-size: 0.875rem; }
</style>
@endsection

@section('content')
<div class="show-page">

    {{-- Page title + status --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 class="page-title" style="margin-bottom: 0.5rem;">{{ $class->name }}</h1>
        <div style="display:flex; align-items:center; gap:10px;">
            @if($class->status === 'active')
                <span class="badge badge-active">Active</span>
            @else
                <span class="badge badge-archived">Archivée</span>
            @endif
            <span style="color:var(--ink-4); font-size:12.5px;">
                Créée le {{ $class->created_at->format('d/m/Y à H:i') }}
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="stabs">
        <button class="stab active" onclick="switchTab('info', event)">
            <i class="ti ti-info-circle"></i> Informations
        </button>
        <button class="stab" onclick="switchTab('students', event)">
            <i class="ti ti-users"></i> Étudiants
            <span style="background:var(--surface-3); border:1px solid var(--line); border-radius:100px; padding:1px 7px; font-size:10.5px; font-weight:700; color:var(--ink-4);">{{ $class->students->count() }}</span>
        </button>
    </div>

    {{-- ── Tab: Info ── --}}
    <div class="stab-content active" id="tab-info">

        {{-- Teacher card --}}
        <div class="teacher-card">
            <div class="teacher-avatar-placeholder">
                {{ $class->teacher ? strtoupper(substr($class->teacher->name, 0, 1)) : '?' }}
            </div>
            <div class="teacher-info">
                <div class="teacher-micro-label">Enseignant responsable</div>
                @if($class->teacher)
                    <div class="teacher-name">{{ $class->teacher->name }}</div>
                    <span class="teacher-badge">
                        <i class="ti ti-school" style="font-size:11px;"></i> Enseignant
                    </span>
                    <a href="mailto:{{ $class->teacher->email }}" class="teacher-email">
                        <i class="ti ti-mail" style="font-size:12px;"></i>
                        {{ $class->teacher->email }}
                    </a>
                @else
                    <div class="teacher-name" style="color:var(--ink-4); font-style:italic;">Non assigné</div>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="sstat-grid">
            <div class="sstat-tile">
                <div class="sstat-val">{{ $class->students->count() }}</div>
                <div class="sstat-lbl">Étudiants inscrits</div>
            </div>
            <div class="sstat-tile">
                <div class="sstat-val" style="font-size:1rem; color:{{ $class->status === 'active' ? 'var(--success)' : 'var(--ink-4)' }};">
                    {{ $class->status === 'active' ? 'Active' : 'Archivée' }}
                </div>
                <div class="sstat-lbl">Statut du cours</div>
            </div>
        </div>

        {{-- Description --}}
        <div class="desc-card">
            <div class="micro-label">Description</div>
            <p class="desc-text">
                {{ $class->description ?: 'Aucune description fournie.' }}
            </p>
        </div>

        {{-- Join code strip (only once) --}}
        <div class="join-code-strip">
            <div class="jc-left">
                <div class="micro-label">Code d'accès</div>
                <div class="jc-code" id="join-code-val">{{ $class->join_code }}</div>
                <div style="font-size:11.5px; color:var(--ink-4); margin-top:4px;">
                    Partagez ce code avec les étudiants pour qu'ils rejoignent le cours.
                </div>
            </div>
            <button class="btn-copy-code" onclick="copyCode()">
                <i class="ti ti-copy"></i> Copier le code
            </button>
        </div>

        {{-- Danger zone --}}
        <div class="danger-zone">
            <div>
                <div class="danger-zone-text"><strong>Supprimer ce cours</strong></div>
                <div class="danger-zone-hint">Cette action est irréversible et supprimera toutes les données associées.</div>
            </div>
            <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}"
                  onsubmit="return confirm('Supprimer ce cours définitivement ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="ti ti-trash" style="font-size:14px;"></i> Supprimer le cours
                </button>
            </form>
        </div>

    </div>

    {{-- ── Tab: Students ── --}}
    <div class="stab-content" id="tab-students">

        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <i class="ti ti-users" style="font-size:13px;"></i>
                    Étudiants inscrits
                </div>
                <span class="scount-badge">{{ $class->students->count() }}</span>
            </div>

            @if($class->students->count() > 0)
                <div class="student-search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text"
                           class="student-search"
                           id="student-search"
                           placeholder="Rechercher un étudiant…"
                           autocomplete="off">
                </div>

                <table class="s-table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody id="student-tbody">
                        @foreach($class->students as $student)
                            <tr data-name="{{ strtolower($student->name) }}"
                                data-email="{{ strtolower($student->email) }}">
                                <td>
                                    <div class="student-name-cell">
                                        <div class="student-av">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <span style="font-weight:700; color:var(--ink);">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td style="color:var(--ink-4); font-size:12px;">
                                    {{ $student->pivot->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div id="no-results" style="display:none; padding:2.5rem; text-align:center; color:var(--ink-4); font-size:13px;">
                    <i class="ti ti-search" style="font-size:22px; display:block; margin-bottom:8px; opacity:.35;"></i>
                    Aucun étudiant trouvé
                </div>

            @else
                <div class="empty-state">
                    <i class="ti ti-users"></i>
                    <p>Aucun étudiant inscrit dans ce cours.</p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

@section('extra-scripts')
<script>
/* ── Tab switching — consistent with student/teacher views ── */
function switchTab(tabName, event) {
    document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.stab-content').forEach(c => c.classList.remove('active'));
    event.target.closest('.stab').classList.add('active');
    document.getElementById('tab-' + tabName).classList.add('active');
    history.replaceState(null, null, '?tab=' + tabName);
}

/* Restore tab from URL */
document.addEventListener('DOMContentLoaded', function () {
    const tabParam = new URLSearchParams(window.location.search).get('tab');
    const validTabs = ['info', 'students'];
    if (tabParam && validTabs.includes(tabParam)) {
        document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.stab-content').forEach(c => c.classList.remove('active'));
        document.getElementById('tab-' + tabParam).classList.add('active');
        document.querySelectorAll('.stab').forEach(t => {
            if (t.getAttribute('onclick') && t.getAttribute('onclick').includes("'" + tabParam + "'")) {
                t.classList.add('active');
            }
        });
    }
});

/* ── Copy join code — uses admin showToast ── */
function copyCode() {
    const code = document.getElementById('join-code-val').textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
        if (typeof showToast === 'function') {
            showToast('Code copié : ' + code);
        }
    });
}

/* ── Student search ── */
const searchInput = document.getElementById('student-search');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const rows = document.querySelectorAll('#student-tbody tr');
        let visible = 0;
        rows.forEach(function (row) {
            const name  = row.dataset.name  || '';
            const email = row.dataset.email || '';
            const show  = !q || name.includes(q) || email.includes(q);
            row.classList.toggle('row-hidden', !show);
            if (show) visible++;
        });
        document.getElementById('no-results').style.display = (visible === 0) ? 'block' : 'none';
    });
}
</script>
@endsection