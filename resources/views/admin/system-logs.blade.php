@extends('layouts.app')

@section('title', 'Logs Système')
@section('page-title', 'Logs et Supervision Système')

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
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
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
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .info-item {
        padding: 1rem;
        background: #1e293b;
        border-radius: 0.75rem;
        border: 1px solid #334155;
    }
    .info-label {
        font-weight: bold;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
    }
    .info-value {
        color: #e2e8f0;
        font-size: 1.1rem;
    }
    .activity-item {
        padding: 1rem;
        border-left: 4px solid #6366f1;
        background: #1e293b;
        margin-bottom: 1rem;
        border-radius: 0.75rem;
        border: 1px solid #334155;
    }
    .activity-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .activity-action {
        font-weight: bold;
        color: #e2e8f0;
    }
    .activity-time {
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .activity-user {
        color: #c7d2fe;
        font-size: 0.9rem;
    }
    .activity-details {
        color: #666;
        font-size: 0.9rem;
    }
    .empty-state {
        text-align: center;
        color: #999;
        padding: 2rem;
    }
</style>
@endsection

@section('content')

    <div class="section">
        <h2>ℹ️ Informations Système</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Version PHP</div>
                <div class="info-value">{{ $systemInfo['php_version'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Version Laravel</div>
                <div class="info-value">{{ $systemInfo['laravel_version'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Base de données</div>
                <div class="info-value">{{ ucfirst($systemInfo['database']) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Environnement</div>
                <div class="info-value">{{ ucfirst($systemInfo['environment']) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Mode Debug</div>
                <div class="info-value">{{ $systemInfo['debug_mode'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fuseau horaire</div>
                <div class="info-value">{{ $systemInfo['timezone'] }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>🕐 Activité Récente</h2>
        @forelse($activities as $activity)
            <div class="activity-item">
                <div class="activity-header">
                    <div class="activity-action">{{ $activity->description }}</div>
                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
                <div class="activity-user">
                    👤 {{ $activity->causer?->email ?? 'Système' }}
                </div>
                @if($activity->subject_type)
                    <div class="activity-details">
                        {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                Aucune activité enregistrée pour le moment.
            </div>
        @endforelse
    </div>

@endsection