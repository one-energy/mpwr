@props(['header', 'body', 'pagination'])

@php
$pagination = $pagination ?? null;
$class = 'table w-full';
if ($pagination) {
    $class .= ' ';
}
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <x-table-accordion.tr-th>
        {{ $header }}
    </x-table-accordion.tr-th>
    <div class="table-row-group">
        {{ $body }}
    </div>
    {!! $pagination !!}
</div>
