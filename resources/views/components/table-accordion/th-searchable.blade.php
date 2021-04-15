@props(['by', 'direction', 'sortedBy'])

<div {{ $attributes }}>
    <button
        class="text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex justify-between focus:outline-none"
        wire:click="sort('{{ $by }}', '{{ $sortedBy == $by ? ($direction == 'asc' ? 'desc' : 'asc') :'asc' }}')">
        {{ $slot }}

        @if($sortedBy == $by && $direction == 'asc')
            <x-svg.sort-ascending class="ml-2 w-4 h-4 text-gray-400"/>
        @elseif($sortedBy == $by && $direction == 'desc')
            <x-svg.sort-descending class="ml-2 w-4 h-4 text-gray-400"/>
        @endif
    </button>
</div>
