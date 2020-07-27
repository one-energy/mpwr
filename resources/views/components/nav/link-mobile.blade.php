@props(['active'])

@php
    $active = $active ?? false;
    $class = 'text-gray-400 fill-current block pl-3 pr-4 py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out';

    if($active) {
        $class = 'text-gray-600 fill-current block pl-3 pr-4 py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out';
    }
@endphp

<a {{ $attributes->merge(compact('class')) }}>
    {{ $slot }}
</a>
