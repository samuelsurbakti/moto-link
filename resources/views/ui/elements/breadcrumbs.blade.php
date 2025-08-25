@props([
    'items' => [],
])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($items as $item)
            @if (!$loop->last && empty($item['active']))
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] ?? 'javascript:void(0);' }}">
                        {{ $item['label'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
