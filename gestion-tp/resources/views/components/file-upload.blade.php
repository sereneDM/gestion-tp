@props([
    'id' => 'file-input',
    'name' => 'file',
    'accept' => '.pdf',
    'hint' => 'PDF uniquement · max 10 Mo',
    'required' => false,
])

<label for="{{ $id }}" class="file-upload">
    📎 Cliquez pour sélectionner un fichier{{ $required ? ' *' : '' }}
    <div class="file-hint">{{ $hint }}</div>
</label>

<input type="file"
       id="{{ $id }}"
       name="{{ $name }}"
       accept="{{ $accept }}"
       {{ $required ? 'required' : '' }}
       onchange="showFileName(this, '{{ $id }}')">

<div id="{{ $id }}-preview" class="selected-file" style="display:none;"></div>