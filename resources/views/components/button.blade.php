@props(['type', 'color' => "gray", 'href'])

@php
    $href = $href ?? null;
    $class = " border border-green-base text-white bg-green-base hover:bg-green-dark hover:border-green-dark focus:border-green-500 focus:shadow-outline-green active:bg-green-50";
    if($color != "green")  {
        $class = "border border-transparent bg-{$color}-600 hover:bg-{$color}-500 focus:outline-none focus:border-{$color}-700 focus:shadow-outline-{$color} active:bg-{$color}-700";
    }
    
@endphp

<span class="rounded-md shadow-sm">
    @if($href)
        <a href="{{ $href }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4 text-sm font-medium rounded-md focus:outline-none transition duration-150 ease-in-out" . $class]) }}>
            {{ $slot }}
        </a>
    @else
        <button type="{{ $type ?? 'button' }}"
            {{ $attributes->merge(['class' => "justify-center py-2 px-4 text-sm font-medium rounded-md text-white transition duration-150 ease-in-out" . $class]) }}
        >
            {{ $slot }}
        </button>
    @endif
</span>