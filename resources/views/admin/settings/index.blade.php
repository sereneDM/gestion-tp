@extends('layouts.admin')

@section('title', 'Paramètres Système')

@section('breadcrumb')
    <span class="tb-bc-current">Paramètres</span>
@endsection

@section('extra-styles')
<style>
    .settings-wrapper {
        max-width: 800px;
    }

    .card {
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .card-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink-4);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 1px solid var(--line);
        padding-bottom: 1rem;
    }

    .toggle-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--line-2);
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--success);
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    .footer-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 3rem;
        padding: 2rem;
        background: var(--surface-2);
        border-radius: var(--radius-xl);
        border: 1px solid var(--line);
    }

    .btn-save {
        background: var(--accent);
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-save:hover {
        background: var(--accent-2);
        box-shadow: 0 4px 12px rgba(61, 90, 254, 0.2);
    }

    .btn-reset {
        background: transparent;
        color: var(--danger);
        border: 1px solid rgba(229, 57, 53, 0.2);
        padding: 0.875rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: #fef2f2;
        border-color: var(--danger);
    }
</style>
@endsection

@section('content')
<div class="settings-wrapper">
    <h1 class="page-title">Paramètres Système</h1>
    <p class="page-subtitle">Configurez le comportement global de la plateforme.</p>

    <div class="card" style="display:flex; align-items:flex-start; gap:10px; background:var(--accent-bg); border:1px solid rgba(61,90,254,.15); border-radius:var(--radius-md); padding:12px 14px; margin-bottom:24px; font-size:12.5px; color:var(--accent); line-height:1.5;">
        <i class="ti ti-info-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
        Ces réglages affectent le fonctionnement global de la plateforme. Toute modification est instantanée.
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-title"><i class="ti ti-settings"></i> Paramètres généraux</div>
            @foreach($settings['general'] as $key => $setting)
                <div class="form-group">
                    <label class="label" for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}" class="input">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <label class="toggle-group" for="{{ $key }}">
                            <div class="toggle-switch">
                                <input type="hidden" name="{{ $key }}" value="0">
                                <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1" {{ $setting['value'] == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <span style="font-size: 0.875rem; font-weight: 500; color: var(--ink-2);">Activer cette option</span>
                        </label>
                    @else
                        <input type="{{ $setting['type'] }}" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}" class="input">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-title"><i class="ti ti-school"></i> Paramètres académiques</div>
            @foreach($settings['academic'] as $key => $setting)
                <div class="form-group">
                    <label class="label" for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}" class="input">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <label class="toggle-group" for="{{ $key }}">
                            <div class="toggle-switch">
                                <input type="hidden" name="{{ $key }}" value="0">
                                <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1" {{ $setting['value'] == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <span style="font-size: 0.875rem; font-weight: 500; color: var(--ink-2);">Activer cette option</span>
                        </label>
                    @else
                        <input type="{{ $setting['type'] }}" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}" class="input">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-title"><i class="ti ti-file-upload"></i> Paramètres de soumission</div>
            @foreach($settings['submissions'] as $key => $setting)
                <div class="form-group">
                    <label class="label" for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}" class="input">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <label class="toggle-group" for="{{ $key }}">
                            <div class="toggle-switch">
                                <input type="hidden" name="{{ $key }}" value="0">
                                <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1" {{ $setting['value'] == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <span style="font-size: 0.875rem; font-weight: 500; color: var(--ink-2);">Activer cette option</span>
                        </label>
                    @else
                        <input type="{{ $setting['type'] }}" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}" class="input">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="footer-actions">
            <button type="submit" class="btn-save">
                <i class="ti ti-device-floppy"></i> Enregistrer les paramètres
            </button>
    </form>
    
    <form method="POST" action="{{ route('admin.settings.reset') }}" onsubmit="return confirm('Réinitialiser tous les paramètres aux valeurs par défaut ?')">
        @csrf
        <button type="submit" class="btn-reset">
            <i class="ti ti-refresh"></i> Réinitialiser
        </button>
    </form>
        </div>
</div>
@endsection