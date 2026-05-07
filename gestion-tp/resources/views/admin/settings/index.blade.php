@extends('layouts.app')

@section('title', 'Paramètres Système')
@section('page-title', 'Paramètres Système')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #545b62;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        font-size: 1rem;
        padding: 0.75rem 2rem;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .header-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        justify-content: flex-end;
    }
    .info-text {
        background-color: #e7f3ff;
        padding: 1rem;
        border-radius: 4px;
        color: #0056b3;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .settings-section {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-title {
        font-size: 1.3rem;
        color: #007bff;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: Arial, sans-serif;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
    }
    input:focus, textarea:focus {
        outline: none;
        border-color: #007bff;
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
    }
    .checkbox-group label {
        margin: 0;
        font-weight: normal;
        cursor: pointer;
    }
    .save-button-container {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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