@props(['color', 'footer'])

@php
    $color = $color ?? 'white';
@endphp

<div {{ $attributes->merge(['class' => "bg-{$color} shadow sm:rounded-lg sm:overflow-hidden"]) }}>
    <div class="py-8 px-4 sm:px-10">
        {{ $slot }}
    </div>

    @if( $footer ?? false)
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
