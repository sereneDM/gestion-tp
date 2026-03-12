<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le TP</title>
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
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            display: inline-block;
            flex: 1;
            text-align: center;
        }
        .btn-primary {
            background-color: #ffc107;
            color: #333;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Modifier le TP</h1>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('teacher.tps.update', $tp->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Titre du TP *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $tp->title) }}" 
                           required>
                    @error('title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description / Énoncé *</label>
                    <textarea id="description" 
                              name="description" 
                              required>{{ old('description', $tp->description) }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="class_id">Classe</label>
                    <select id="class_id" name="class_id">
                        <option value="">-- Toutes les classes --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                    {{ old('class_id', $tp->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="due_date">Date d'échéance</label>
                    <input type="date" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', $tp->due_date ? $tp->due_date->format('Y-m-d') : '') }}">
                    @error('due_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Statut *</label>
                    <select id="status" name="status" required>
                        <option value="draft" {{ old('status', $tp->status) === 'draft' ? 'selected' : '' }}>
                            Brouillon
                        </option>
                        <option value="published" {{ old('status', $tp->status) === 'published' ? 'selected' : '' }}>
                            Publié
                        </option>
                        <option value="closed" {{ old('status', $tp->status) === 'closed' ? 'selected' : '' }}>
                            Fermé
                        </option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        ✓ Enregistrer
                    </button>
                    <a href="{{ route('teacher.tps.index') }}" class="btn btn-secondary">
                        ✗ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>