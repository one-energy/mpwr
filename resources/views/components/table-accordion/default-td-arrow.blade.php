@props(['index'])

<div {{ $attributes->merge(['class' => 'table-cell px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 ']) }} >
    <div class="flex">
        <x-svg.chevron-down name="down" class="w-4 h-auto mr-4"/>
        <x-svg.chevron-up name="up" class="w-4 h-auto mr-4 hidden"/>
        {{ $slot }}
    </div>
</div>
