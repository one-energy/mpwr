@props(['header', 'body', 'pagination', 'overflowX' => false])

@php
    $pagination = $pagination ?? null;
    $class = 'flex flex-col';
    if($pagination) {
        $class .= ' ';
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="">
        <div class="@if($overflowX) overflow-x-auto @endif">
            <table class="min-w-full">
                <thead>
                {{ $header }}
                </thead>
                <tbody class="bg-white">
                {{ $body }}
                </tbody>
            </table>
        </div>
    </div>

    {!! $pagination !!}
</div>
