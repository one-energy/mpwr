@props(['header', 'body', 'pagination'])

@php
    $pagination = $pagination ?? null;
    $class = 'flex flex-col';
    if($pagination) {
        $class .= ' bg-white sm:overflow-hidden shadow sm:rounded-lg';
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div
            class="align-middle inline-block overflow-hidden sm:rounded-lg min-w-full  @unless($pagination)shadow  @endunless">
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
