@extends('layouts.app')

@section('title', 'Paramètres Système')

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
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .settings-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2.25rem;
        color: var(--ink);
    }

    .info-box {
        background: var(--surface-3);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--ink-3);
        margin-bottom: 2rem;
        border: 1px solid var(--line);
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
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

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink-2);
        margin-bottom: 0.75rem;
        letter-spacing: 0.05em;
    }

    .input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: var(--surface-2);
        color: var(--ink);
        font-family: var(--font-body);
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .input:focus {
        outline: none;
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 4px var(--accent-bg);
    }

    textarea.input {
        min-height: 100px;
        resize: vertical;
        line-height: 1.6;
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

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background: var(--accent-2);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(61, 90, 254, 0.2);
    }

    .btn-danger-outline {
        background: transparent;
        color: var(--danger);
        border-color: rgba(229, 57, 53, 0.2);
    }

    .btn-danger-outline:hover {
        background: #fef2f2;
        border-color: var(--danger);
    }
</style>
@endsection

@section('content')
<div class="settings-wrapper">
    <div class="page-header">
        <h1 class="page-title">Paramètres Système</h1>
    </div>

    <div class="info-box">
        <i class="ti ti-info-circle"></i>
        <span>Ces réglages affectent le fonctionnement global de la plateforme. Toute modification est instantanée.</span>
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
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy"></i> Enregistrer les paramètres
            </button>
    </form>
    
    <form method="POST" action="{{ route('admin.settings.reset') }}" onsubmit="return confirm('Réinitialiser tous les paramètres aux valeurs par défaut ?')">
        @csrf
        <button type="submit" class="btn btn-danger-outline">
            <i class="ti ti-refresh"></i> Réinitialiser
        </button>
    </form>
        </div>
</div>
@endsection