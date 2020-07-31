@props(['href', 'type', 'color'])

@php
    $href  = $href ?? null;
    $type  = $type ?? null;
    $color = $color ?? 'green';
@endphp

@if($type ?? false)
    <button type="{{ $type }}"
    @if($color == 'green')
        {{ $attributes->merge(['class' => "font-medium text-green-base hover:text-green-dark focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
    @else
        {{ $attributes->merge(['class' => "font-medium text-{$color}-600 hover:text-{$color}-500 focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
    @endif
        {!! $slot !!}
    </button>
@else
    <a href="{{ $href }}"
    @if($color == 'green')
        {{ $attributes->merge(['class' => "font-medium text-green-base hover:text-green-dark focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
    @else
        {{ $attributes->merge(['class' => "font-medium text-{$color}-600 hover:text-{$color}-500 focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
    @endif
        {!! $slot !!}
    </a>
@endif
