@extends('layouts.app')

@section('title', 'Paramètres Système')
@section('page-title', 'Paramètres Système')

@section('extra-styles')
<style>
    .btn {
        padding: 0.65rem 1.5rem;
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
        background-color: #334155;
        border-color: #475569;
    }
    .btn-danger {
        background-color: #1e293b;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .btn-danger:hover {
        background-color: #7f1d1d;
        border-color: #991b1b;
    }
    .btn-primary {
        background-color: #1e293b;
        color: #e2e8f0;
        font-size: 1rem;
        padding: 0.75rem 2rem;
    }
    .btn-primary:hover {
        background-color: #334155;
        border-color: #475569;
    }
    .header-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        justify-content: flex-end;
    }
    .info-text {
        background-color: rgba(99, 102, 241, 0.1);
        padding: 1rem;
        border-radius: 0.75rem;
        color: #c7d2fe;
        border-left: 4px solid #6366f1;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .settings-section {
        background: #0f172a;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #334155;
    }
    .section-title {
        font-size: 1.3rem;
        color: #c7d2fe;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
        font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #475569;
        border-radius: 0.75rem;
        font-size: 1rem;
        background: #1e293b;
        color: #e2e8f0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
    }
    input:focus, textarea:focus {
        outline: none;
        border-color: #6366f1;
        background: #334155;
    }
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .checkbox-group input[type="checkbox"] {
        height: 20px;
        width: 20px;
        cursor: pointer;
        accent-color: #6366f1;
    }
    .checkbox-group label {
        margin: 0;
        font-weight: normal;
        cursor: pointer;
        color: #cbd5e1;
    }
    .save-button-container {
        background: #0f172a;
        padding: 1.5rem;
        border-radius: 1rem;
        border: 1px solid #334155;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
</style>
@endsection

@section('content')
    

    <div class="header-buttons">
        <form method="POST"
              action="{{ route('admin.settings.reset') }}"
              onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres?')">
            @csrf
            <button type="submit" class="btn btn-danger">
                🔄 Réinitialiser
            </button>
        </form>
    </div>

    <div class="info-text">
        ℹ️ Ces paramètres contrôlent le comportement général de la plateforme. Modifiez-les avec précaution.
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="settings-section">
            <div class="section-title">📋 Paramètres généraux</div>
            @foreach($settings['general'] as $key => $setting)
                <div class="form-group">
                    <label for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <div class="checkbox-group">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input type="checkbox"
                                   id="{{ $key }}"
                                   name="{{ $key }}"
                                   value="1"
                                   {{ $setting['value'] == '1' ? 'checked' : '' }}>
                            <label for="{{ $key }}">Activé</label>
                        </div>
                    @else
                        <input type="{{ $setting['type'] }}"
                               id="{{ $key }}"
                               name="{{ $key }}"
                               value="{{ $setting['value'] }}">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="settings-section">
            <div class="section-title">🎓 Paramètres académiques</div>
            @foreach($settings['academic'] as $key => $setting)
                <div class="form-group">
                    <label for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <div class="checkbox-group">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input type="checkbox"
                                   id="{{ $key }}"
                                   name="{{ $key }}"
                                   value="1"
                                   {{ $setting['value'] == '1' ? 'checked' : '' }}>
                            <label for="{{ $key }}">Activé</label>
                        </div>
                    @else
                        <input type="{{ $setting['type'] }}"
                               id="{{ $key }}"
                               name="{{ $key }}"
                               value="{{ $setting['value'] }}">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="settings-section">
            <div class="section-title">📤 Paramètres de soumission</div>
            @foreach($settings['submissions'] as $key => $setting)
                <div class="form-group">
                    <label for="{{ $key }}">{{ $setting['label'] }}</label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <div class="checkbox-group">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input type="checkbox"
                                   id="{{ $key }}"
                                   name="{{ $key }}"
                                   value="1"
                                   {{ $setting['value'] == '1' ? 'checked' : '' }}>
                            <label for="{{ $key }}">Activé</label>
                        </div>
                    @else
                        <input type="{{ $setting['type'] }}"
                               id="{{ $key }}"
                               name="{{ $key }}"
                               value="{{ $setting['value'] }}">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="save-button-container">
            <button type="submit" class="btn btn-primary">
                ✓ Enregistrer les paramètres
            </button>
        </div>
    </form>
@endsection