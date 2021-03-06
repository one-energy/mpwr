@props(['by', 'direction', 'sortedBy'])

<div {{ $attributes->merge(['class' => 'pr-6 py-4 text-sm leading-5 text-gray-800 md:border-b md:border-gray-200']) }}>
    <button
        class="text-left text-xs h-full leading-4 font-medium text-gray-700 uppercase tracking-wider flex justify-between focus:outline-none whitespace-normal items-center"
        wire:click="sort('{{ $by }}', '{{ $sortedBy == $by ? ($direction == 'asc' ? 'desc' : 'asc') :'asc' }}')">
        {{ $slot }}

        @if($sortedBy == $by && $direction == 'asc')
            <x-svg.sort-ascending class="ml-2 w-4 h-4 text-gray-400"/>
        @elseif($sortedBy == $by && $direction == 'desc')
            <x-svg.sort-descending class="ml-2 w-4 h-4 text-gray-400"/>
        @endif
    </button>
</div>
