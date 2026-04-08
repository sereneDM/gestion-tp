@unless ($breadcrumbs->isEmpty())
    <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.6rem; font-weight: bold; color: #333;">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->first)
                <span style="color: #999; font-weight: 300;">\</span>
            @endif

            @if ($breadcrumb->url && !$loop->last)
                <a href="{{ $breadcrumb->url }}" style="color: #667eea; text-decoration: none;">
                    {{ $breadcrumb->title }}
                </a>
            @else
                <span style="color: #333;">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
@endunless