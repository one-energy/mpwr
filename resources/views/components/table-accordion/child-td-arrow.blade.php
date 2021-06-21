@props(['open' => false])
<div {{ $attributes->merge(['class' => 'table-cell pr-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 ']) }} >
    <div class="flex ml-14">
        @if($open)
            <x-svg.chevron-up name="up" class="w-4 h-auto mr-4"/>
        @else
            <x-svg.chevron-down name="down" class="w-4 h-auto mr-4"/>
        @endif
        {{ $slot }}
    </div>
</div>
