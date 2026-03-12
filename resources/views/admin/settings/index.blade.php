<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres Système</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
        }
        .header-buttons {
            display: flex;
            gap: 1rem;
        }
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
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            width: auto;
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
        .info-text {
            background-color: #e7f3ff;
            padding: 1rem;
            border-radius: 4px;
            color: #0056b3;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Paramètres Système</h1>
            <div class="header-buttons">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    ← Retour au tableau de bord
                </a>
                <form method="POST" 
                      action="{{ route('admin.settings.reset') }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        🔄 Réinitialiser
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="info-text">
            ℹ️ Ces paramètres contrôlent le comportement général de la plateforme. Modifiez-les avec précaution.
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <!-- General Settings -->
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

            <!-- Academic Settings -->
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

            <!-- Submission Settings -->
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
    </div>
</body>
</html>