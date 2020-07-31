@props(['color'])
@php
    $color = $color ?? 'green';
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 @if($color == 'green') text-green-dark @else text-{{ $color }}-800 @endif">
    {{ $slot }}
</span>
