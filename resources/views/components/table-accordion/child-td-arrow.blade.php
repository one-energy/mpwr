<div {{ $attributes->merge(['class' => 'table-cell pr-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 ']) }} >
    <div class="flex ml-10">
        <x-svg.chevron-down class="w-4 h-auto mr-4"/>
        <x-svg.chevron-up id="xpto" class="w-4 h-auto mr-4 hidden"/>
        {{ $slot }}
    </div>
</div>
