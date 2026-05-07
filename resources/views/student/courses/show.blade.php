@extends('layouts.app')

@section('title', $course->name)
@section('page-title', $course->name)

@section('extra-styles')
<style>
    .btn {
        display: inline-block;
        padding: 0.65rem 1.5rem;
        text-align: center;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s;
        border: 1px solid #334155;
        cursor: pointer;
        background: #1e293b;
        color: #e2e8f0;
    }
    .btn:hover {
        background: #334155;
        border-color: #475569;
    }
    .btn-primary        { background: #1e293b; color: #e2e8f0; }
    .btn-primary:hover  { background: #334155; }
    .btn-success        { background: #1e293b; color: #e2e8f0; }
    .btn-success:hover  { background: #334155; }
    .btn-info           { background: #1e293b; color: #e2e8f0; }
    .btn-info:hover     { background: #334155; }

    /* ── Form Centering ── */
    .form-centered {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background: #0f172a;
        border-radius: 1rem;
        border: 1px solid #334155;
    }
    .form-centered form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .form-centered .btn {
        width: 100%;
    }

    /* ── Tabs (matches teacher) ── */
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

    /* ── TP grid ── */
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .tp-card {
        background: #0f172a;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #334155;
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s;
        display: flex;
        flex-direction: column;
        position: relative;
        min-height: 220px;
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
    .status-pending   { background: rgba(251,191,36,0.15); color: #facc15; }
    .status-submitted { background: rgba(34,197,94,0.15);  color: #86efac; }
    .status-graded    { background: rgba(6,182,212,0.15);  color: #67e8f9; }

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
    .tp-grade {
        font-family: monospace;
        background: #164e63;
        color: #67e8f9;
        padding: 0.3rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 0.75rem;
        align-self: flex-start;
    }
    .tp-spacer { flex: 1; }

    /* ── 3-dots menu ── */
    .course-menu-btn {
        background: transparent;
        border: 1px solid #334155;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .course-menu-btn:hover {
        background: #1e293b;
        border-color: #475569;
        color: #e2e8f0;
    }
    .course-menu-dropdown {
        display: none;
        position: absolute;
        top: 2.2rem;
        right: 0;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        min-width: 150px;
        z-index: 100;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .course-menu-dropdown button {
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        color: #fca5a5;
        cursor: pointer;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        transition: background 0.15s;
    }
    .course-menu-dropdown button:hover { background: #334155; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #0f172a;
        border-radius: 1rem;
        color: #94a3b8;
        border: 1px solid #334155;
    }
</style>
@endsection

@section('content')

    {{-- Course header row (teacher name + leave menu) --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; margin-bottom:0.25rem;">Enseignant</div>
            <div style="color:#cbd5e1; font-size:0.95rem;">👨‍🏫 {{ $course->teacher->name }}</div>
        </div>
        <div style="position:relative;">
            <button class="course-menu-btn" onclick="toggleCourseMenu()">⋮</button>
            <div class="course-menu-dropdown" id="course-menu">
                <form method="POST" action="{{ route('student.leave-course', $course->id) }}"
                      style="display:block; width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">🚪 Quitter le cours</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab active" onclick="switchTab('info', event)">📋 Informations</button>
        <button class="tab" onclick="switchTab('tps', event)">📝 Travaux Pratiques</button>
    </div>

    {{-- Tab: Info --}}
    <div class="tab-content active" id="tab-info">

        <div class="info-grid">
            <div class="info-card">
                <div class="info-number">{{ $course->tps->count() }}</div>
                <div class="info-label">Travaux pratiques</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->count() }}</div>
                <div class="info-label">Mes soumissions</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->filter(fn($s) => $s->grade !== null)->count() }}</div>
                <div class="info-label">TP notés</div>
            </div>
        </div>

        @if($course->description)
            <div style="background:#0f172a; border:1px solid #334155; border-radius:1rem; padding:1.5rem;">
                <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; margin-bottom:0.5rem;">Description</div>
                <p style="margin:0; color:#cbd5e1; font-size:0.95rem; line-height:1.6;">{{ $course->description }}</p>
            </div>
        @endif

    </div>

    {{-- Tab: TPs --}}
    <div class="tab-content" id="tab-tps">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0; font-size:1.5rem; color:#f1f5f9;">📝 Travaux Pratiques ({{ $course->tps->count() }})</h3>
        </div>

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps as $tp)
                    @php
                        $submission   = $submissions->get($tp->id);
                        $hasSubmitted = $submission !== null;
                        $isGraded     = $hasSubmitted && $submission->grade !== null;
                    @endphp

                    <div class="tp-card"
                         onclick="window.location.href='{{ route('student.tps.show', $tp->id) }}'">

                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            @if($isGraded)
                                <span class="status-badge status-graded">✓ Noté</span>
                            @elseif($hasSubmitted)
                                <span class="status-badge status-submitted">✓ Soumis</span>
                            @else
                                <span class="status-badge status-pending">À faire</span>
                            @endif
                        </div>

                        <div class="tp-description">
                            @if(filled($tp->description))
                                {{ $tp->description }}
                            @else
                                <span style="font-style:italic;">Aucune description</span>
                            @endif
                        </div>

                        <div class="tp-meta">📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Non définie' }}</div>
                        <div class="tp-meta">👨‍🏫 {{ $course->teacher->name }}</div>
                        @if($hasSubmitted)
                            <div class="tp-meta">📤 Soumis le {{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
                        @endif

                        @if($isGraded)
                            <div class="tp-grade">🎯 {{ $submission->grade }}/20</div>
                        @endif

                        <div class="tp-spacer"></div>

                        <a href="{{ route('student.tps.show', $tp->id) }}"
                           onclick="event.stopPropagation();"
                           class="btn {{ $isGraded ? 'btn-info' : ($hasSubmitted ? 'btn-success' : 'btn-primary') }}">
                            @if($isGraded)
                                Voir ma note &amp; commentaires
                            @elseif($hasSubmitted)
                                Voir ma soumission
                            @else
                                Voir et soumettre
                            @endif
                        </a>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                <h2>Aucun TP disponible</h2>
                <p>Votre enseignant n'a pas encore publié de travaux pratiques.</p>
            </div>
        @endif

    </div>

@endsection

@section('extra-scripts')
<script>
    function toggleCourseMenu() {
        const menu = document.getElementById('course-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.course-menu-btn') && !e.target.closest('#course-menu')) {
            const menu = document.getElementById('course-menu');
            if (menu) menu.style.display = 'none';
        }
    });

    function switchTab(tabName, event) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
        history.replaceState(null, null, '#' + tabName);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fragment = window.location.hash.replace('#', '');
        const validTabs = ['info', 'tps'];
        if (fragment && validTabs.includes(fragment)) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + fragment).classList.add('active');
            document.querySelectorAll('.tab').forEach(t => {
                if (t.getAttribute('onclick') && t.getAttribute('onclick').includes("'" + fragment + "'")) {
                    t.classList.add('active');
                }
            });
        }
    });
</script>
@endsection
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

    /* ── TP grid ── */
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .tp-card {
        background: #0f172a;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #334155;
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s;
        display: flex;
        flex-direction: column;
        position: relative;
        min-height: 220px;
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
    .status-pending   { background: rgba(251,191,36,0.15); color: #facc15; }
    .status-submitted { background: rgba(34,197,94,0.15);  color: #86efac; }
    .status-graded    { background: rgba(6,182,212,0.15);  color: #67e8f9; }

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
    .tp-grade {
        font-family: monospace;
        background: #164e63;
        color: #67e8f9;
        padding: 0.3rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 0.75rem;
        align-self: flex-start;
    }
    .tp-spacer { flex: 1; }

    /* ── 3-dots menu ── */
    .course-menu-btn {
        background: transparent;
        border: 1px solid #334155;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .course-menu-btn:hover {
        background: #1e293b;
        border-color: #475569;
        color: #e2e8f0;
    }
    .course-menu-dropdown {
        display: none;
        position: absolute;
        top: 2.2rem;
        right: 0;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        min-width: 150px;
        z-index: 100;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .course-menu-dropdown button {
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        color: #fca5a5;
        cursor: pointer;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        transition: background 0.15s;
    }
    .course-menu-dropdown button:hover { background: #334155; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #0f172a;
        border-radius: 1rem;
        color: #94a3b8;
        border: 1px solid #334155;
    }
</style>
@endsection

@section('content')

    {{-- Course header row (teacher name + leave menu) --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; margin-bottom:0.25rem;">Enseignant</div>
            <div style="color:#cbd5e1; font-size:0.95rem;">👨‍🏫 {{ $course->teacher->name }}</div>
        </div>
        <div style="position:relative;">
            <button class="course-menu-btn" onclick="toggleCourseMenu()">⋮</button>
            <div class="course-menu-dropdown" id="course-menu">
                <form method="POST" action="{{ route('student.leave-course', $course->id) }}"
                      style="display:block; width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">🚪 Quitter le cours</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab active" onclick="switchTab('info', event)">📋 Informations</button>
        <button class="tab" onclick="switchTab('tps', event)">📝 Travaux Pratiques</button>
    </div>

    {{-- Tab: Info --}}
    <div class="tab-content active" id="tab-info">

        <div class="info-grid">
            <div class="info-card">
                <div class="info-number">{{ $course->tps->count() }}</div>
                <div class="info-label">Travaux pratiques</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->count() }}</div>
                <div class="info-label">Mes soumissions</div>
            </div>
            <div class="info-card">
                <div class="info-number">{{ $submissions->filter(fn($s) => $s->grade !== null)->count() }}</div>
                <div class="info-label">TP notés</div>
            </div>
        </div>

        @if($course->description)
            <div style="background:#0f172a; border:1px solid #334155; border-radius:1rem; padding:1.5rem;">
                <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#64748b; margin-bottom:0.5rem;">Description</div>
                <p style="margin:0; color:#cbd5e1; font-size:0.95rem; line-height:1.6;">{{ $course->description }}</p>
            </div>
        @endif

    </div>

    {{-- Tab: TPs --}}
    <div class="tab-content" id="tab-tps">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0; font-size:1.5rem; color:#f1f5f9;">📝 Travaux Pratiques ({{ $course->tps->count() }})</h3>
        </div>

        @if($course->tps->count() > 0)
            <div class="tps-grid">
                @foreach($course->tps as $tp)
                    @php
                        $submission   = $submissions->get($tp->id);
                        $hasSubmitted = $submission !== null;
                        $isGraded     = $hasSubmitted && $submission->grade !== null;
                    @endphp

                    <div class="tp-card"
                         onclick="window.location.href='{{ route('student.tps.show', $tp->id) }}'">

                        <div class="tp-header">
                            <div class="tp-title">{{ $tp->title }}</div>
                            @if($isGraded)
                                <span class="status-badge status-graded">✓ Noté</span>
                            @elseif($hasSubmitted)
                                <span class="status-badge status-submitted">✓ Soumis</span>
                            @else
                                <span class="status-badge status-pending">À faire</span>
                            @endif
                        </div>

                        <div class="tp-description">
                            @if(filled($tp->description))
                                {{ $tp->description }}
                            @else
                                <span style="font-style:italic;">Aucune description</span>
                            @endif
                        </div>

                        <div class="tp-meta">📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Non définie' }}</div>
                        <div class="tp-meta">👨‍🏫 {{ $course->teacher->name }}</div>
                        @if($hasSubmitted)
                            <div class="tp-meta">📤 Soumis le {{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
                        @endif

                        @if($isGraded)
                            <div class="tp-grade">🎯 {{ $submission->grade }}/20</div>
                        @endif

                        <div class="tp-spacer"></div>

                        <a href="{{ route('student.tps.show', $tp->id) }}"
                           onclick="event.stopPropagation();"
                           class="btn {{ $isGraded ? 'btn-info' : ($hasSubmitted ? 'btn-success' : 'btn-primary') }}">
                            @if($isGraded)
                                Voir ma note &amp; commentaires
                            @elseif($hasSubmitted)
                                Voir ma soumission
                            @else
                                Voir et soumettre
                            @endif
                        </a>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                <h2>Aucun TP disponible</h2>
                <p>Votre enseignant n'a pas encore publié de travaux pratiques.</p>
            </div>
        @endif

    </div>

@endsection

@section('extra-scripts')
<script>
    function toggleCourseMenu() {
        const menu = document.getElementById('course-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.course-menu-btn') && !e.target.closest('#course-menu')) {
            const menu = document.getElementById('course-menu');
            if (menu) menu.style.display = 'none';
        }
    });

    function switchTab(tabName, event) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
        history.replaceState(null, null, '#' + tabName);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fragment = window.location.hash.replace('#', '');
        const validTabs = ['info', 'tps'];
        if (fragment && validTabs.includes(fragment)) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + fragment).classList.add('active');
            document.querySelectorAll('.tab').forEach(t => {
                if (t.getAttribute('onclick') && t.getAttribute('onclick').includes("'" + fragment + "'")) {
                    t.classList.add('active');
                }
            });
        }
    });
</script>
@endsection