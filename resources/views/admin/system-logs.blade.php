@extends('layouts.admin')

@section('title', 'Journal d\'activité')

@section('breadcrumb')
    <span class="tb-bc-page">Système</span>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Journal d'activité</span>
@endsection

@section('extra-styles')
<style>
    /* ── Filter card ── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 22px;
        box-shadow: var(--shadow-sm);
    }

    .filter-card-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 8px;
        align-items: end;
    }

    @media (max-width: 780px) {
        .filter-grid { grid-template-columns: 1fr 1fr; }
        .filter-grid .filter-search { grid-column: span 2; }
        .filter-grid .filter-actions { grid-column: span 2; }
    }

    .filter-field { display: flex; flex-direction: column; gap: 5px; }

    .filter-label {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--ink-4);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .filter-input-wrap { position: relative; }
    .filter-input-wrap .fi-icon {
        position: absolute;
        left: 10px; top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: var(--ink-4);
        pointer-events: none;
    }

    .filter-input {
        width: 100%;
        padding: 7px 10px 7px 32px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        font-family: inherit;
        background: var(--surface-2);
        color: var(--ink);
        transition: border-color .2s, box-shadow .2s;
    }

    .filter-input.no-icon { padding-left: 10px; }

    .filter-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }

    .filter-input::placeholder { color: var(--ink-4); }

    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 6px;
    }

    .filter-submit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--accent);
        color: white;
        font-size: 12px; font-weight: 700;
        font-family: inherit; cursor: pointer;
        box-shadow: 0 2px 6px rgba(61,90,254,.25);
        transition: background .15s;
        white-space: nowrap;
    }
    .filter-submit:hover { background: var(--accent-2); }

    .filter-reset {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-3);
        font-size: 12px; font-weight: 600;
        font-family: inherit; cursor: pointer;
        text-decoration: none;
        transition: background .15s;
        white-space: nowrap;
    }
    .filter-reset:hover { background: var(--surface-3); }

    /* Active filter pills */
    .filter-pills {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--line);
    }

    .filter-pill-label { font-size: 11px; color: var(--ink-4); font-weight: 600; }

    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: var(--accent-bg);
        color: var(--accent);
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
    }

    /* ── Timeline ── */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: "";
        position: absolute;
        left: 0.6rem; top: 0; bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--accent-bg), var(--line) 30%, var(--line));
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.25rem;
    }

    .timeline-item:last-child { padding-bottom: 0; }

    .timeline-dot {
        position: absolute;
        left: -1.43rem;
        top: 0.8rem;
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--accent);
        border: 2px solid var(--surface);
        box-shadow: 0 0 0 2px var(--accent-bg);
        z-index: 1;
    }

    .activity-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        transition: border-color .15s, box-shadow .15s;
    }

    .activity-card:hover {
        border-color: rgba(61,90,254,.3);
        box-shadow: 0 2px 8px rgba(61,90,254,.07);
    }

    .activity-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
    }

    .activity-desc {
        font-weight: 700;
        color: var(--ink);
        font-size: 13px;
        line-height: 1.4;
    }

    .activity-time {
        font-size: 11px;
        color: var(--ink-4);
        white-space: nowrap;
        flex-shrink: 0;
        padding-top: 2px;
    }

    .activity-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        color: var(--ink-4);
    }

    .meta-chip i { font-size: 13px; }

    /* ── Result info bar ── */
    .results-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .results-count {
        font-size: 12px;
        color: var(--ink-4);
        font-weight: 500;
    }

    .results-count strong { color: var(--ink-2); font-weight: 800; }

    /* ── Pagination ── */
    .pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--line);
        font-size: 12.5px;
        color: var(--ink-4);
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-controls { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }

    .page-btn {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px;
        padding: 0 8px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-2);
        font-size: 12px; font-weight: 600;
        text-decoration: none;
        transition: background .15s, border-color .15s;
    }

    .page-btn:hover { background: var(--surface-3); }
    .page-btn.active { background: var(--accent); color: white; border-color: var(--accent); box-shadow: 0 2px 6px rgba(61,90,254,.25); }
    .page-btn.disabled { opacity: .35; pointer-events: none; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--ink-4);
    }

    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: .35; }
    .empty-state h3 { font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:4px;">
    <h1 class="page-title">Journal d'activité</h1>
</div>
<p class="page-subtitle">Historique complet des actions effectuées sur la plateforme.</p>

{{-- ── Filter Card ── --}}
<div class="filter-card">
    <div class="filter-card-title">
        <i class="ti ti-filter" style="font-size:13px;"></i> Filtrer les entrées
    </div>

    <form method="GET" action="{{ route('admin.system-logs') }}">
        <div class="filter-grid">

            {{-- Search --}}
            <div class="filter-field filter-search">
                <div class="filter-label">Recherche</div>
                <div class="filter-input-wrap">
                    <i class="ti ti-search fi-icon"></i>
                    <input type="text" name="search" class="filter-input"
                           placeholder="Utilisateur, action, ressource…"
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Date from --}}
            <div class="filter-field">
                <div class="filter-label">Du</div>
                <input type="date" name="date_from" class="filter-input no-icon" value="{{ request('date_from') }}">
            </div>

            {{-- Date to --}}
            <div class="filter-field">
                <div class="filter-label">Au</div>
                <input type="date" name="date_to" class="filter-input no-icon" value="{{ request('date_to') }}">
            </div>

            {{-- Actions --}}
            <div class="filter-actions">
                <button type="submit" class="filter-submit">
                    <i class="ti ti-search" style="font-size:13px;"></i> Filtrer
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <a href="{{ route('admin.system-logs') }}" class="filter-reset">
                        <i class="ti ti-x" style="font-size:13px;"></i> Effacer
                    </a>
                @endif
            </div>
        </div>

        {{-- Active filters pills --}}
        @if(request()->hasAny(['search', 'date_from', 'date_to']))
            <div class="filter-pills">
                <span class="filter-pill-label">Filtres actifs :</span>
                @if(request('search'))
                    <span class="filter-pill"><i class="ti ti-search" style="font-size:11px;"></i> "{{ request('search') }}"</span>
                @endif
                @if(request('date_from'))
                    <span class="filter-pill"><i class="ti ti-calendar" style="font-size:11px;"></i> Du {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="filter-pill"><i class="ti ti-calendar" style="font-size:11px;"></i> Au {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}</span>
                @endif
            </div>
        @endif
    </form>
</div>

{{-- Results bar --}}
<div class="results-bar">
    <div class="results-count">
        <strong>{{ $activities->total() }}</strong> entrée(s) trouvée(s)
        — Page <strong>{{ $activities->currentPage() }}</strong> sur <strong>{{ $activities->lastPage() }}</strong>
    </div>
</div>

{{-- Timeline card --}}
<div class="card" style="padding: 24px; overflow: hidden;">
    @if($activities->count() > 0)
        <div class="timeline">
            @foreach($activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="activity-card">
                        <div class="activity-top">
                            <div class="activity-desc">{{ $activity->description }}</div>
                            <div class="activity-time" title="{{ $activity->created_at->format('d/m/Y H:i:s') }}">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="activity-meta">
                            <span class="meta-chip">
                                <i class="ti ti-user"></i>
                                {{ $activity->causer?->name ?? 'Système' }}
                                @if($activity->causer)
                                    <span style="color:var(--line-2);">·</span>
                                    <span style="color:var(--ink-4);">{{ $activity->causer->email }}</span>
                                @endif
                            </span>
                            @if($activity->subject_type)
                                <span class="meta-chip">
                                    <i class="ti ti-box"></i>
                                    {{ class_basename($activity->subject_type) }}
                                    @if($activity->subject_id)
                                        <span style="color:var(--ink-4);">#{{ $activity->subject_id }}</span>
                                    @endif
                                </span>
                            @endif
                            <span class="meta-chip">
                                <i class="ti ti-clock"></i>
                                {{ $activity->created_at->format('d/m/Y à H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination-row">
            <span>Page {{ $activities->currentPage() }} / {{ $activities->lastPage() }}</span>
            <div class="pagination-controls">
                @if($activities->onFirstPage())
                    <span class="page-btn disabled"><i class="ti ti-arrow-left"></i></span>
                @else
                    <a href="{{ $activities->previousPageUrl() }}" class="page-btn"><i class="ti ti-arrow-left"></i></a>
                @endif

                @foreach(range(max(1, $activities->currentPage() - 2), min($activities->lastPage(), $activities->currentPage() + 2)) as $p)
                    <a href="{{ $activities->url($p) }}" class="page-btn {{ $p === $activities->currentPage() ? 'active' : '' }}">{{ $p }}</a>
                @endforeach

                @if($activities->hasMorePages())
                    <a href="{{ $activities->nextPageUrl() }}" class="page-btn"><i class="ti ti-arrow-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="ti ti-arrow-right"></i></span>
                @endif
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="ti ti-history"></i>
            <h3>Aucune activité trouvée</h3>
            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                <p>Essayez d'ajuster vos filtres ou <a href="{{ route('admin.system-logs') }}" style="color:var(--accent);">réinitialisez-les</a>.</p>
            @else
                <p>Aucun journal d'activité enregistré pour le moment.</p>
            @endif
        </div>
    @endif
</div>
@endsection