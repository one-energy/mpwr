@props(['active'])

@php
    $active = $active ?? false;
    $class = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';
    if($active) {
        $class = 'border-green-base text-gray-900 focus:border-green-base inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';
    }
@endphp

<a {{ $attributes->merge(compact('class')) }}>
    {{ $slot }}
</a>
