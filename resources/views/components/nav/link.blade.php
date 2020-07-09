@props(['active'])

@php
    $active = $active ?? false;
    $class = 'px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700';
    if($active) {
        $class = 'px-3 py-2 rounded-md text-sm font-medium text-white bg-gray-900 focus:outline-none focus:text-white focus:bg-gray-700';
    }
@endphp

<a {{ $attributes->merge(compact('class')) }}>
    {{ $slot }}
</a>
