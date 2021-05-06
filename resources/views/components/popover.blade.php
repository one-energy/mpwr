@props([
    'ref' => null,
    'position' => 'left'
])

@php
    $style = match ($position) {
        'left' => 'top: 10px; left: 0; transform: translateX(-90%); max-width: 200px; width: auto;',
        'bottom' => 'top: 50%; left: 50%; transform: translate(-50%, 18%); max-width: 200px; width: auto;',
        'default' => null
    };
@endphp

<div
    {{ $attributes }}
    x-data="{ open: false, ref: '{{ $ref }}' }"
    x-on:open-popover.window="$event.detail.ref === ref ? open = true : false"
    x-on:close-popover.window="$event.detail.ref === ref ? open = !open : false"
    class="bg-gray-200 rounded shadow-xl z-20 h-auto p-4 space-y-2 absolute"
    @if ($style !== null)
        style="{{ $style }}"
    @endif
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    x-show="open"
>
    {{ $slot}}
</div>
