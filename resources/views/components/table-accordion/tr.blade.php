@props(['cols' => 4])

@php
    //the limit to cols is 12
    $class="hover:bg-gray-50 grid grid-cols-" . $cols
@endphp

<div >
    <div {{ $attributes->merge(['class' => $class]) }}>
        {{ $raw }}
    </div>
    <div {{ $attributes->merge(['class' => $class]) }}>
        {{ $rawContent }}
    </div>
</div>
