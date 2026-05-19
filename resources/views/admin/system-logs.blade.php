@extends('layouts.admin')

@section('title', 'Journal d\'activité')

@section('breadcrumb')
    <span class="tb-bc-current">Journal d'activité</span>
@endsection

@section('extra-styles')
<style>
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }

    .filter-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }
    .filter-wrap i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: var(--ink-4);
        pointer-events: none;
    }
    .filter-input {
        width: 100%;
        padding: 7px 10px 7px 32px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-family: inherit;
        background: var(--surface-2);
        color: var(--ink);
        transition: border-color .2s;
    }
    .filter-input:focus { outline: none; border-color: var(--accent); }
    .filter-input::placeholder { color: var(--ink-4); }

    .filter-date {
        padding: 7px 10px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-family: inherit;
        background: var(--surface-2);
        color: var(--ink);
        transition: border-color .2s;
    }
    .filter-date:focus { outline: none; border-color: var(--accent); }

    .filter-submit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 14px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--accent);
        color: white;
        font-size: 12.5px; font-weight: 700;
        font-family: inherit; cursor: pointer;
        transition: background .2s;
    }
    .filter-submit:hover { background: var(--accent-2); }

    .filter-reset {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-3);
        font-size: 12.5px; font-weight: 600;
        font-family: inherit; cursor: pointer;
        text-decoration: none;
        transition: background .15s;
    }
    .filter-reset:hover { background: var(--surface-2); }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 0.75rem; top: 0; bottom: 0;
        width: 2px;
        background: var(--line);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-dot {
        position: absolute;
        left: -1.25rem;
        top: 0.3rem;
        width: 12px; height: 12px;
        border-radius: 50%;
        background: var(--accent);
        border: 3px solid var(--surface-2);
        box-shadow: 0 0 0 2px var(--accent-bg);
        z-index: 1;
    }

    .activity-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow-sm);
        transition: border-color .2s, transform .15s;
    }
    .activity-card:hover { border-color: var(--accent); transform: translateX(3px); }

    .activity-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
        gap: 1rem;
    }

    .activity-desc {
        font-weight: 700;
        color: var(--ink);
        font-size: 13px;
    }

    .activity-time {
        font-size: 11px;
        color: var(--ink-4);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .activity-meta {
        font-size: 12px;
        color: var(--ink-3);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .activity-meta span { display: flex; align-items: center; gap: 4px; }

    /* Pagination */
    .pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--line);
        font-size: 12.5px;
        color: var(--ink-4);
    }
    .pagination-controls { display: flex; gap: 4px; align-items: center; }
    .page-btn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-2);
        font-size: 12px; font-weight: 600;
        text-decoration: none;
        transition: background .15s, border-color .15s;
    }
    .page-btn:hover { background: var(--surface-2); }
    .page-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
    .page-btn.disabled { opacity: .4; pointer-events: none; }

    .result-count {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px;
        background: var(--surface-2);
        border: 1px solid var(--line);
        border-radius: 100px;
        font-size: 11px; color: var(--ink-3);
    }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
    <h1 class="page-title">Journal d'activité</h1>
    <span class="result-count">
        <i class="ti ti-list" style="font-size:12px;"></i>
        {{ $activities->total() }} entrée(s)
    </span>
</div>
<p class="page-subtitle">Historique complet des actions effectuées sur la plateforme.</p>

{{-- Filter bar --}}
<form method="GET" action="{{ route('admin.system-logs') }}" class="filter-bar">
    <div class="filter-wrap">
        <i class="ti ti-search"></i>
        <input type="text" name="search" class="filter-input"
               placeholder="Rechercher par utilisateur ou action..."
               value="{{ request('search') }}">
    </div>

    <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:var(--ink-4);">
        <i class="ti ti-calendar" style="font-size:14px;"></i>
        Du
    </div>
    <input type="date" name="date_from" class="filter-date" value="{{ request('date_from') }}">

    <div style="font-size:12px; color:var(--ink-4);">au</div>
    <input type="date" name="date_to" class="filter-date" value="{{ request('date_to') }}">

    <button type="submit" class="filter-submit">
        <i class="ti ti-filter" style="font-size:13px;"></i> Filtrer
    </button>

    @if(request()->hasAny(['search', 'date_from', 'date_to']))
        <a href="{{ route('admin.system-logs') }}" class="filter-reset">
            <i class="ti ti-x" style="font-size:13px;"></i> Réinitialiser
        </a>
    @endif
</form>

{{-- Timeline --}}
<div class="card" style="padding: 24px; overflow: hidden;">
    @if($activities->count() > 0)
        <div class="timeline">
            @foreach($activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="activity-card">
                        <div class="activity-top">
                            <div class="activity-desc">{{ $activity->description }}</div>
                            <div class="activity-time">
                                <span title="{{ $activity->created_at->format('d/m/Y H:i:s') }}">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="activity-meta">
                            <span>
                                <i class="ti ti-user"></i>
                                {{ $activity->causer?->name ?? 'Système' }}
                                @if($activity->causer)
                                    <span style="color:var(--ink-4);">· {{ $activity->causer->email }}</span>
                                @endif
                            </span>
                            @if($activity->subject_type)
                                <span>
                                    <i class="ti ti-box"></i>
                                    {{ class_basename($activity->subject_type) }}
                                    @if($activity->subject_id) #{{ $activity->subject_id }} @endif
                                </span>
                            @endif
                            <span>
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
            <span>Page {{ $activities->currentPage() }} sur {{ $activities->lastPage() }}</span>

            <div class="pagination-controls">
                @if($activities->onFirstPage())
                    <span class="page-btn disabled"><i class="ti ti-arrow-left"></i></span>
                @else
                    <a href="{{ $activities->previousPageUrl() }}" class="page-btn">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                @endif

                @foreach(range(max(1, $activities->currentPage() - 2), min($activities->lastPage(), $activities->currentPage() + 2)) as $p)
                    <a href="{{ $activities->url($p) }}"
                       class="page-btn {{ $p === $activities->currentPage() ? 'active' : '' }}">
                        {{ $p }}
                    </a>
                @endforeach

                @if($activities->hasMorePages())
                    <a href="{{ $activities->nextPageUrl() }}" class="page-btn">
                        <i class="ti ti-arrow-right"></i>
                    </a>
                @else
                    <span class="page-btn disabled"><i class="ti ti-arrow-right"></i></span>
                @endif
            </div>
        </div>
    @else
        <div style="text-align:center; padding:4rem; color:var(--ink-4);">
            <i class="ti ti-history" style="font-size:2.5rem; display:block; margin-bottom:1rem; opacity:.4;"></i>
            <div style="font-weight:600; margin-bottom:6px;">Aucune activité trouvée</div>
            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                <div style="font-size:13px;">Essayez d'ajuster vos filtres.</div>
            @endif
        </div>
    @endif
</div>
@endsection