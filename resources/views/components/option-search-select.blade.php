@props(['value', 'label'])

<div class="focus:outline-none focus:bg-indigo-100 focus:text-indigo-800 text-gray-900
        cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm
        transition-colors ease-in-out hover:text-white
        duration-100 group"
    :class="{
        'hover:bg-red-500'   :  isOptionSelected({{$value}}),
    'hover:bg-green-500': !isOptionSelected({{$value}}),
    }"
    tabindex="0"
    x-on:click="select({{$value}}, '{{$label}}')"
    x-on:keydown.enter="select(option)">
    <span class="block truncate" :class="{
                'font-semibold':  isOptionSelected({{$value}}, '{{$label}}'),
                'font-normal'  : !isOptionSelected({{$value}}, '{{$label}}'),
            }"> {{$label}}
        </span>

        <span class="absolute group-focus:text-white group-hover:text-white
                        inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
            x-show="isOptionSelected({{$value}}, '{{$label}}')">
            <x-icon name="check" />
        </span>
</div>
