<div {{ $attributes->merge(['class' => 'table-cell px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 ']) }} >
    <div class="flex">
        <x-svg.arrow-down/>
        {{ $slot }}
    </div>
</div>
