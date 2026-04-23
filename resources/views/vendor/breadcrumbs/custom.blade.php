@unless ($breadcrumbs->isEmpty())
    <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.6rem; font-weight: bold; color: #e2e8f0;">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->first)
                <span style="color: #64748b; font-weight: 300;">\</span>
            @endif

            @if ($breadcrumb->url && !$loop->last)
                <a href="{{ $breadcrumb->url }}" style="color: #cbd5e1; text-decoration: none;">
                    {{ $breadcrumb->title }}
                </a>
            @else
                <span style="color: #e2e8f0;">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
@endunless