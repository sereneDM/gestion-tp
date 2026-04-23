@props([
    'id' => 'file-input',
    'name' => 'file',
    'accept' => '.pdf',
    'hint' => 'PDF uniquement · max 10 Mo'
])

<label for="{{ $id }}" class="file-upload">
    📎 Cliquez pour sélectionner un fichier
    <div class="file-hint">
        {{ $hint }}
    </div>
</label>

<input type="file"
       id="{{ $id }}"
       name="{{ $name }}"
       accept="{{ $accept }}"
       onchange="showFileName(this, '{{ $id }}')">

<div id="{{ $id }}-preview" class="selected-file" style="display:none;"></div>