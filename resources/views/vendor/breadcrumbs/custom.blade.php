@unless ($breadcrumbs->isEmpty())

<style>
.bc-nav {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
}
.bc-sep {
    opacity: 0.3;
    display: flex;
    align-items: center;
}
.bc-sep svg {
    width: 12px;
    height: 12px;
    stroke: currentColor;
    stroke-width: 2.5;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.bc-link {
    color: #6366f1;
    text-decoration: none;
    padding: 2px 7px;
    border-radius: 6px;
    transition: background 0.15s, color 0.15s;
}
.bc-link:hover {
    background: rgba(99, 102, 241, 0.1);
    color: #4f46e5;
}
.bc-current {
    color: #94a3b8;
    font-weight: 500;
    padding: 2px 7px;
}
</style>

<nav class="bc-nav" aria-label="Fil d'Ariane">
    @foreach ($breadcrumbs as $breadcrumb)

        @if (!$loop->first)
            <span class="bc-sep" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
            </span>
        @endif

        @if ($breadcrumb->url && !$loop->last)
            <a href="{{ $breadcrumb->url }}" class="bc-link">{{ $breadcrumb->title }}</a>
        @else
            <span class="bc-current">{{ $breadcrumb->title }}</span>
        @endif

    @endforeach
</nav>

@endunless