@extends('layouts.app')
@section('title', 'Paramètres Système')

@section('content')

<div class="flex justify-end gap-3 mb-5">
    <form method="POST" action="{{ route('admin.settings.reset') }}"
          onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres?')">
        @csrf
        <button type="submit"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                       hover:bg-red-200 dark:hover:bg-red-900/50">
            🔄 Réinitialiser
        </button>
    </form>
</div>

<div class="flex items-start gap-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500
            px-4 py-3 rounded-lg text-sm text-blue-700 dark:text-blue-300 mb-6">
    ℹ️ Ces paramètres contrôlent le comportement général de la plateforme. Modifiez-les avec précaution.
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    @foreach([
        ['general',     '📋 Paramètres généraux'],
        ['academic',    '🎓 Paramètres académiques'],
        ['submissions', '📤 Paramètres de soumission'],
    ] as [$group, $title])
        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6 mb-5">
            <h2 class="font-bold text-violet-600 dark:text-violet-400 text-base mb-5 pb-2 border-b border-slate-200 dark:border-slate-600">
                {{ $title }}
            </h2>
            @foreach($settings[$group] as $key => $setting)
                <div class="mb-5">
                    <label for="{{ $key }}"
                           class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                        {{ $setting['label'] }}
                    </label>
                    @if($setting['type'] === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}" rows="3"
                                  class="w-full px-3 py-2 rounded-lg text-sm border border-slate-300 dark:border-slate-600
                                         bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100
                                         focus:outline-none focus:border-violet-500 dark:focus:border-violet-400 resize-y transition-colors">{{ $setting['value'] }}</textarea>
                    @elseif($setting['type'] === 'checkbox')
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1"
                                   {{ $setting['value'] == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-violet-600 cursor-pointer">
                            <label for="{{ $key }}" class="text-sm text-slate-600 dark:text-slate-300 cursor-pointer font-normal">Activé</label>
                        </div>
                    @else
                        <input type="{{ $setting['type'] }}" id="{{ $key }}" name="{{ $key }}"
                               value="{{ $setting['value'] }}"
                               class="w-full px-3 py-2 rounded-lg text-sm border border-slate-300 dark:border-slate-600
                                      bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100
                                      focus:outline-none focus:border-violet-500 dark:focus:border-violet-400 transition-colors">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="flex justify-center pt-2">
        <button type="submit"
                class="px-8 py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-xl font-semibold text-base transition-colors shadow">
            ✓ Enregistrer les paramètres
        </button>
    </div>
</form>

@endsection