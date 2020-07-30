@props(['href', 'type', 'color'])

@php
    $href  = $href ?? null;
    $type  = $type ?? null;
    $color  = $color ?? 'green';
@endphp

@if($type ?? false)
    <button type="{{ $type }}"
        {{ $attributes->merge(['class' => "font-medium @if($color == 'green') text-green-base hover:text-green-dark @else text-{$color}-600 hover:text-{$color}-500 @endif focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
        {!! $slot !!}
    </button>
@else
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => "font-medium @if($color == 'green') text-green-base hover:text-green-dark @else text-{$color}-600 hover:text-{$color}-500 @endif focus:outline-none focus:underline transition ease-in-out duration-150"]) }}>
       {!! $slot !!}
    </a>
@endif
