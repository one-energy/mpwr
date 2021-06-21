@props(['value', 'label'])

@php

    $hasError = $errors->has($name);
    $hasError
        ? $class .= 'border-red-400 text-red-600 focus:ring-red-500 focus:border-red-500'
        : $class .= 'border-gray-300 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500';
@endphp

<div>
    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm"
        x-show="!getFilteredOptions().length"
        x-on:click="closePopover">
        <span class="block truncate font-normal">
            No options
        </span>
    </li>
</div>
