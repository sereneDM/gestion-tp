@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-transform">
        <div class="text-3xl mb-2">📚</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $enrolledCoursesCount }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Cours suivis</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-transform">
        <div class="text-3xl mb-2">📝</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $availableTPs }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP disponibles</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-transform">
        <div class="text-3xl mb-2">✅</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $submittedCount }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP soumis</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 text-center border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-transform">
        <div class="text-3xl mb-2">⭐</div>
        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $gradedCount }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TP notés</div>
    </div>
</div>
{{-- Quick actions --}}
<div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 mb-6 shadow-sm">
    <h2 class="font-semibold text-slate-900 dark:text-white text-lg mb-4">🚀 Actions rapides</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <a href="{{ route('student.join-course.form') }}"
           class="py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-bold text-sm text-center transition-colors">
            ➕ Rejoindre un cours
        </a>
        <a href="{{ route('student.my-courses') }}"
           class="py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm text-center transition-colors">
            📚 Voir mes cours
        </a>
        <a href="{{ route('student.submissions.index') }}"
           class="py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-bold text-sm text-center transition-colors">
            📄 Mes soumissions
        </a>
        <a href="{{ route('student.progress') }}"
           class="py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm text-center transition-colors">
            📊 Ma progression
        </a>
    </div>
</div>
{{-- Feed --}}
<h2 class="font-semibold text-slate-800 dark:text-slate-100 text-lg mb-4">📰 Fil d'actualité</h2>
@forelse($posts as $post)
    <div class="bg-white dark:bg-[#0f172a] rounded-xl border border-slate-200 dark:border-slate-700 p-6 mb-4
                cursor-pointer hover:border-violet-400 dark:hover:border-violet-500 transition-colors"
         onclick="window.location='{{ route('posts.show', $post->id) }}'">
        <div class="flex justify-between items-start mb-3 pb-3 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <img src="{{ $post->user->profile_picture_url }}" alt="{{ $post->user->name }}"
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ $post->user->name }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $post->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold
                @if($post->type === 'announcement') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                @elseif($post->type === 'tp_posted') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                @elseif($post->type === 'reminder') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300
                @else bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 @endif">
                @if($post->type === 'announcement') 📢 Annonce
                @elseif($post->type === 'tp_posted') 📝 Nouveau TP
                @elseif($post->type === 'reminder') ⏰ Rappel
                @else 📌 Général @endif
            </span>
        </div>
        <div class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-2">{{ $post->title }}</div>
        <div class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $post->content }}</div>
        @if($post->tp && $post->tp->due_date)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700 text-sm text-slate-500 dark:text-slate-400">
                📅 Échéance: {{ $post->tp->due_date->format('d/m/Y à H:i') }}
            </div>
        @endif
        @if($post->class)
            <div class="mt-2 inline-block px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 rounded text-sm text-indigo-700 dark:text-indigo-300">
                📚 {{ $post->class->name }}
            </div>
        @endif
        @if($post->tp)
            <div class="mt-3">
                <a href="{{ route('student.tps.show', $post->tp->id) }}"
                   class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm transition-colors"
                   onclick="event.stopPropagation()">
                    👁️ Voir le TP
                </a>
            </div>
        @endif
        @if($post->attachment)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank"
                   class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm transition-colors"
                   onclick="event.stopPropagation()">
                    📎 Télécharger la pièce jointe
                </a>
            </div>
        @endif
    </div>
@empty
    <div class="text-center py-16 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="text-6xl mb-4">📭</div>
        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Aucune publication</h3>
        <p class="text-slate-500 dark:text-slate-400">Les annonces de vos enseignants apparaîtront ici</p>
    </div>
@endforelse
@if($posts->hasPages())
    <div class="mt-4">{{ $posts->links() }}</div>
@endif
@endsection
@section('extra-scripts')
<script>
document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id  = btn.dataset.id;
        const res = await fetch(`/posts/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        btn.querySelector('.like-icon').textContent = data.liked ? '❤️' : '🤍';
        btn.querySelector('.like-count').textContent = data.count;
        btn.classList.toggle('liked', data.liked);
    });
});
</script>
@endsection