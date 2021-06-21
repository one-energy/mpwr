@props(['value', 'label'])

@php

    $hasError = $errors->has($name);
    $hasError
        ? $class .= 'border-red-400 text-red-600 focus:ring-red-500 focus:border-red-500'
        : $class .= 'border-gray-300 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500';
@endphp

<div>
    <li class="focus:outline-none focus:bg-indigo-100 focus:text-indigo-800 text-gray-900
                cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm
                transition-colors ease-in-out hover:text-white
                duration-100 group"
        :class="{
            'hover:bg-red-500'   :  isOptionSelected($value),
            'hover:bg-green-500': !isOptionSelected($value),
        }"
        tabindex="0"
        x-on:click="select(option)"
        x-on:keydown.enter="select(option)">
        <span class="block truncate" :class="{
                'font-semibold':  isOptionSelected($value),
                'font-normal'  : !isOptionSelected($value),
            }"> {{$label}}
        </span>

        <span class="absolute group-focus:text-white group-hover:text-white
                        inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
            x-show="isOptionSelected($value)">
            <x-icon name="check" />
        </span>
    </li>
</div>
