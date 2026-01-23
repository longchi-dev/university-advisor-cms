@props([
    'items' => [],
])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-1 p-0 bg-transparent border-0"
        style="font-size: 0.75rem;">
        @foreach ($items as $item)
            <li class="breadcrumb-item {{ $loop->last ? 'active text-muted' : '' }}">
                @if(!$loop->last && isset($item['href']))
                    <a href="{{ $item['href'] }}"
                       class="text-muted text-decoration-none breadcrumb-link">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-muted">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
