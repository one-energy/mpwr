@props(['type', 'color', 'href'])

@php
    $color = $color ?? 'green';
    $href = $href ?? null;
@endphp

<span class="rounded-md shadow-sm">
    @if($href)
        <a href="{{ $href }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4
            @if($color == 'red-outlined') border-2 border-red-500 text-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50 
            @elseif($color == 'green-border') border-2 border-green-base text-green-base hover:text-green-dark hover:border-green-dark focus:border-green-500 focus:shadow-outline-green active:bg-green-50 
            @else border border-transparent text-white bg-{$color}-600 hover:bg-{$color}-500 focus:border-{$color}-700 focus:shadow-outline-{$color} active:bg-{$color}-700 
            @endif text-sm font-medium rounded-md focus:outline-none transition duration-150 ease-in-out"]) }}
        >
            {{ $slot }}
        </a>
    @else
        <button type="{{ $type ?? 'button' }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4
            @if($color == 'red-outlined') border-2 border-red-500 text-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50 
            @elseif($color == 'green-border') border-2 border-green-base text-green-base hover:text-green-dark hover:border-green-dark focus:border-green-500 focus:shadow-outline-green active:bg-green-50 
            @else border border-transparent bg-{$color}-600 hover:bg-{$color}-500 focus:outline-none focus:border-{$color}-700 focus:shadow-outline-{$color} active:bg-{$color}-700 
            @endif text-sm font-medium rounded-md text-white transition duration-150 ease-in-out"]) }}
        >
            {{ $slot }}
        </button>
    @endif
</span>