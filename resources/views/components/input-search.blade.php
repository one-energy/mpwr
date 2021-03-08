@props([
    'iconAlign'   => 'right',
    'placeholder' => 'Search here',
    'rounded'     => false,
    'filled'      => false,
    'borderless'  => false,
    'noSpinner'   => false,
])

@php
    $inputClasses = 'w-full h-10 px-5 text-sm focus:shadow-md trans border-gray-300 transition ease-in-out duration-300';
    switch ($iconAlign) {
        case 'left':
            $iconClasses   = 'left-3';
            $inputClasses .= ' pl-10';
            break;
        case 'right':
            $iconClasses   = 'right-3';
            $inputClasses .= ' pr-10';
            break;
    }
    if ($rounded) {
        $inputClasses .= ' rounded-full bg-white';
    }
    if ($filled) {
        $inputClasses .= ' rounded-md bg-gray-200 py-2';
    }
    if ($borderless) {
        $inputClasses .= ' border-none focus:ring-0';
    }
@endphp

<div class="relative text-gray-700 w-full">
    <input
        type="search"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => $inputClasses]) }}
    />

    <div class="absolute top-0 h-full flex items-center {{ $iconClasses }}">
        <x-svg.search wire:loading.remove class="h-5 w-5 text-gray-500" />
    </div>

    @if (!$noSpinner)
        <div wire:loading class="pr-2 text-gray-500 absolute top-0 pt-3 right-2 h-full flex items-center">
            <x-svg.spinner color="#777" class="w-5 h-5" />
        </div>
    @endif
</div>
