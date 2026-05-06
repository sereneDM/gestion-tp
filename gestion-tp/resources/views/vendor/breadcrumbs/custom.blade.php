@unless ($breadcrumbs->isEmpty())
    <nav style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; margin-bottom: 0.5rem;">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->first)
                <span style="color: #475569;">›</span>
            @endif

            @if ($breadcrumb->url && !$loop->last)
                <a href="{{ $breadcrumb->url }}" style="color: #6366f1; text-decoration: none; transition: color 0.2s;"
                   onmouseover="this.style.color='#818cf8'" onmouseout="this.style.color='#6366f1'">
                    {{ $breadcrumb->title }}
                </a>
            @else
                <span style="color: #94a3b8;">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
@endunless