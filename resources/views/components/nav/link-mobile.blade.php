@props(['active'])

@php
    $active = $active ?? false;
    $class = 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out';

    if($active) {
        $class = 'text-green-base border-green-base block pl-3 pr-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out';
    }
@endphp

<a {{ $attributes->merge(compact('class')) }}>
    {{ $slot }}
</a>
