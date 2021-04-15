@props(['header', 'body', 'pagination'])

@php
    $pagination = $pagination ?? null;
    $class = 'flex flex-col';
    if($pagination) {
        $class .= ' ';
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="min-w-full">
        <div id="head">
            {{ $header }}
        </thead>
        <div id="body">
            {{ $body }}
        </div>
    </div>
    {!! $pagination !!}
</div>
