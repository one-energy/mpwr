@props(['type', 'color', 'href'])

@php
    $color = $color ?? 'blue';
    $href = $href ?? null;
@endphp

<span class="rounded-md shadow-sm">
    @if($href)
        <a href="{{ $href }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white
            bg-{$color}-600 hover:bg-{$color}-500 focus:outline-none focus:border-{$color}-700 focus:shadow-outline-{$color} active:bg-{$color}-700
            transition duration-150 ease-in-out"]) }}
        >
            {{ $slot }}
        </a>
    @else
        <button type="{{ $type ?? 'button' }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white
            bg-{$color}-600 hover:bg-{$color}-500 focus:outline-none focus:border-{$color}-700 focus:shadow-outline-{$color} active:bg-{$color}-700
            transition duration-150 ease-in-out"]) }}
        >
            {{ $slot }}
        </button>
    @endif
</span>
