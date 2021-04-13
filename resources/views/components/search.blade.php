@props(['perPage', 'search'])

@php
    $perPage  = $perPage ?? true;
@endphp

<div class="mb-4 sm:flex sm:justify-between">
    <div class="items-baseline w-full space-y-4 sm:space-x-4 sm:flex sm:space-y-0">
        <div class="relative rounded-md shadow-sm ">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"></path>
                </svg>
            </div>

            <input wire:model.debounce.500ms="search" class="mt-1 block w-full px-10 form-input sm:text-sm sm:leading-5"
                   placeholder="Search"/>

            @if($search)
                <div class="absolute inset-y-0 right-0 flex items-center">
                    <button class="h-full px-2 py-0 text-gray-500 bg-transparent border-transparent outline-none sm:text-sm sm:leading-5"
                            wire:click="clearSearch">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        {{ $slot }}
    </div>
    @if($perPage)
        <div class="relative mt-4 rounded-md shadow-sm sm:mt-1">
            <input disabled id="perPage" class="block w-full pl-4 pr-5 form-input sm:text-sm sm:leading-5"
                placeholder="Per page"/>
            <div class="absolute inset-y-0 right-0 flex items-center w-full">
                <select wire:model="perPage" aria-label="Per Page"
                        class="right-dropdown w-full h-full py-0 text-right text-gray-500 bg-transparent border-transparent form-select sm:pl-20 pr-7 sm:text-sm sm:leading-5">
                    <option class="right-option">5</option>
                    <option class="right-option">15</option>
                    <option class="right-option">25</option>
                    <option class="right-option">35</option>
                    <option class="right-option">50</option>
                    <option class="right-option">100</option>
                </select>
            </div>
        </div>
    @endif
</div>
