@extends('layouts.app')

@section('title', 'Logs Système')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
    :root {
        --ink:        #0d1117;
        --ink-2:      #3d4550;
        --ink-3:      #6b7585;
        --ink-4:      #9aa3af;
        --line:       #e8ebef;
        --line-2:     #d1d6dd;
        --surface:    #ffffff;
        --surface-2:  #f5f6f8;
        --surface-3:  #eef0f3;
        --accent:     #3d5afe;
        --accent-2:   #5271ff;
        --accent-bg:  #eef1ff;
        --danger:     #e53935;
        --warning:    #f59e0b;
        --success:    #10b981;
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  16px;
        --radius-xl:  22px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .logs-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2rem;
        color: var(--ink);
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

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ink-4);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .info-val {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink);
    }

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

    .badge-system {
        background: var(--surface-3);
        color: var(--ink-3);
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="logs-wrapper">
    <div class="page-header">
        <h1 class="page-title">Supervision Système</h1>
    </div>

    <div class="section-title"><i class="ti ti-info-circle"></i> État du serveur</div>
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">PHP</div>
            <div class="info-val">{{ $systemInfo['php_version'] }}</div>
        </div>
        <div class="info-card">
            <div class="info-card">
                <div class="info-label">Laravel</div>
                <div class="info-val">{{ $systemInfo['laravel_version'] }}</div>
            </div>
        </div>
        <div class="info-card">
            <div class="info-label">Base de données</div>
            <div class="info-val">{{ ucfirst($systemInfo['database']) }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Environnement</div>
            <div class="info-val"><span style="color:var(--success);">{{ ucfirst($systemInfo['environment']) }}</span></div>
        </div>
        <div class="info-card">
            <div class="info-label">Mode Debug</div>
            <div class="info-val">{{ $systemInfo['debug_mode'] }}</div>
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
</div>
@endsection