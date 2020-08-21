@props(['name', 'label', 'checked', 'disabledToUser'])

@php
    $disabledToUser = $disabledToUser ?? '';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <input id="{{ $name }}" name="{{ $name }}" value="1" type="checkbox" {{ $checked ? 'checked' : '' }}
           class="form-checkbox h-4 w-4 text-green-base transition duration-150 ease-in-out"
           @if(user()->role == $disabledToUser) disabled @endif/>
    <label for="{{ $name }}" class="ml-2 block text-sm leading-5 text-gray-900">
        {{ $label }}
    </label>
</div>