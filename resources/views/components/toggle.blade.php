@props([
    'disabled' => false,
    'label'    => null,
])

@php
    $class = 'flex flex-col leading-3 ';
    if( $disabled ) {
        $class .= 'opacity-80';
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="flex items-center">
        <button class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            x-data="{
                checked: @entangle($attributes->wire('model')),
                toggle() { this.checked = !this.checked }
            }"
            :class="{
                'bg-green-base': checked,
                'bg-gray-200': !checked
            }"
            @if (!$disabled) x-on:click="toggle" @endif
            type="button"
            :aria-pressed="checked">
            @if ($label) <span class="sr-only">{{ $label }}</span> @endif
            <span class="pointer-events-none translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                :aria-hidden="checked"
                :class="{
                    'translate-x-5': checked,
                    'translate-x-0': !checked
                }">
            </span>
        </button>

        <span class="ml-3" @if (!$disabled) x-on:click="toggle" @endif>
            @if ($label)
                <span class="text-sm text-gray-700">{{ $label }}</span>
            @else
                {{ $slot }}
            @endif
        </span>
    </div>
</div>
