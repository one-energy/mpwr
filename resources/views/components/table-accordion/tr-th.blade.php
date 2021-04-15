@props(['cols' => 4])

@php
    //the limit to cols is 12
    $class="grid grid-cols-" . $cols
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
