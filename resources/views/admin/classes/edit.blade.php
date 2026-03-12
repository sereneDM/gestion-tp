<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Classe</title>
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
            min-height: 100px;
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
        .students-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1rem;
        }
        .student-checkbox {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
        }
        .student-checkbox:hover {
            background-color: #f8f9fa;
        }
        .student-checkbox input {
            width: auto;
            margin-right: 0.75rem;
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
        .btn-primary:hover {
            background-color: #e0a800;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Modifier une Classe</h1>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nom de la classe *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $class->name) }}" 
                           required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" 
                              name="description">{{ old('description', $class->description) }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="teacher_id">Enseignant assigné</label>
                    <select id="teacher_id" name="teacher_id">
                        <option value="">-- Aucun enseignant --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                    {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Étudiants (sélectionnez plusieurs)</label>
                    <div class="students-list">
                        @php
                            $selectedStudents = old('students', $class->students->pluck('id')->toArray());
                        @endphp
                        @forelse($students as $student)
                            <div class="student-checkbox">
                                <input type="checkbox" 
                                       name="students[]" 
                                       value="{{ $student->id }}"
                                       id="student_{{ $student->id }}"
                                       {{ in_array($student->id, $selectedStudents) ? 'checked' : '' }}>
                                <label for="student_{{ $student->id }}" style="margin: 0; font-weight: normal;">
                                    {{ $student->name }} ({{ $student->email }})
                                </label>
                            </div>
                        @empty
                            <p style="color: #999;">Aucun étudiant disponible</p>
                        @endforelse
                    </div>
                    @error('students')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        ✓ Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                        ✗ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>