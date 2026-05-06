@unless ($breadcrumbs->isEmpty())
    <nav class="container mx-auto">
        <ol class="p-4 rounded flex flex-wrap bg-slate-800 text-sm text-slate-200">
            @foreach ($breadcrumbs as $breadcrumb)

                @if ($breadcrumb->url && !$loop->last)
                    <li>
                        <a href="{{ $breadcrumb->url }}" class="text-slate-200 hover:text-white hover:underline focus:text-white focus:underline">
                            {{ $breadcrumb->title }}
                        </a>
                    </li>
                @else
                    <li class="text-slate-200">
                        {{ $breadcrumb->title }}
                    </li>
                @endif

                @unless($loop->last)
                    <li class="text-slate-400 px-2">
                        /
                    </li>
                @endif

            @endforeach
        </ol>
    </nav>
@endunless
