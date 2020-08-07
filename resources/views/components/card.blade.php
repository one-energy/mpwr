@props(['color', 'footer'])

@php
    $color = $color ?? 'white';
@endphp

<div {{ $attributes->merge(['class' => "bg-{$color} sm:rounded-lg sm:overflow-hidden"]) }}>
    <div class="px-4 py-5 sm:px-6">
        {{ $slot }}
    </div>

    @if( $footer ?? false)
        <div class="px-4 py-3 text-right sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
