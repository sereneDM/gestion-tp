@extends('layouts.admin')

@section('title', 'Logs Système')

@section('breadcrumb')
    <span class="tb-bc-current">Logs Système</span>
@endsection

@section('extra-styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: "";
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--line);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-dot {
        position: absolute;
        left: -1.25rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent);
        border: 3px solid var(--surface);
        box-shadow: 0 0 0 2px var(--accent-bg);
        z-index: 1;
    }

    .activity-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s;
    }

    .activity-card:hover {
        transform: translateX(4px);
        border-color: var(--accent);
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .activity-desc {
        font-weight: 700;
        color: var(--ink);
    }

    .activity-time {
        font-size: 0.75rem;
        color: var(--ink-4);
    }

    .activity-meta {
        font-size: 0.82rem;
        color: var(--ink-3);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .activity-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink-4);
        margin: 2.5rem 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Supervision Système</h1>
<p class="page-subtitle">État du serveur et journal des activités</p>

<div class="section-title"><i class="ti ti-info-circle"></i> État du serveur</div>
<div class="stat-strip">
    <div class="stat-tile">
        <div class="stat-tile-label">PHP</div>
        <div class="stat-tile-value">{{ $systemInfo['php_version'] }}</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Laravel</div>
        <div class="stat-tile-value">{{ $systemInfo['laravel_version'] }}</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Base de données</div>
        <div class="stat-tile-value">{{ ucfirst($systemInfo['database']) }}</div>
    </div>
    <div class="stat-tile">
        <div class="stat-tile-label">Environnement</div>
        <div class="stat-tile-value" style="color:var(--success);">{{ ucfirst($systemInfo['environment']) }}</div>
    </div>
</div>

<div class="section-title"><i class="ti ti-history"></i> Journal d'activités</div>
<div class="timeline">
    @forelse($activities as $activity)
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-desc">{{ $activity->description }}</div>
                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
                <div class="activity-meta">
                    <span><i class="ti ti-user"></i> {{ $activity->causer?->name ?? 'Système' }}</span>
                    @if($activity->subject_type)
                        <span><i class="ti ti-box"></i> {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:4rem; color:var(--ink-4);">
            Aucune activité enregistrée
        </div>
    @endforelse
</div>
@endsection