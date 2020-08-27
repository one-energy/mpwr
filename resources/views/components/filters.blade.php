@props(['keywords'])

<div class="border-gray-200 border-2 p-4 rounded-lg">
    <div class="flex justify-between">
        <span>
            Filters
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <symbol id="filter" viewBox="0 0 24 24">
                <path d="M19.479 2l-7.479 12.543v5.924l-1-.6v-5.324l-7.479-12.543h15.958zm3.521-2h-23l9 15.094v5.906l5 3v-8.906l9-15.094z" class="text-gray-700 fill-current"/>
            </symbol>
            <use xlink:href="#filter" width="15" height="15" y="4" x="4" />
        </svg>
    </div>

    <div class="pt-2 relative mx-auto text-gray-600">

        <input wire:model="keyword" wire:keydown.enter="addKeyword" label="Keyword" class="border-2 border-gray-300 bg-white h-10 w-full px-5 pr-16 rounded-lg text-sm focus:outline-none"
            type="search" name="keyword" placeholder="Search by Keyword" />

        <button type="button" wire:click="addKeyword"  class="absolute right-0 top-0 mt-5 mr-4">
            <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
            viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
            width="512px" height="512px">
            <path
                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
            </svg>
        </button>

    </div>
    
    <!-- Filter -->
    <section class="mt-6">
        <article>
            <div class="border-b border-gray-200">
                <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                    <span class="text-gray-70 font-thin text-sm">
                        Region
                    </span>
                    <div class="ml-4">
                        <x-svg.plus class="text-gray-300"></x-svg.plus>
                    </div>
                </header>
            </div>
        </article>
        <article>
            <div class="border-b bg-grey-lightest border-gray-200">
                <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                    <span class="text-gray-700 font-thin text-sm">
                        Member Region
                    </span>
                    <div class="flex">
                        <div class="rounded-full border border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                            1
                        </div>
                        <div class="ml-4">
                            <x-svg.plus class="text-gray-300"></x-svg.plus>
                        </div>
                    </div>
                </header>
                <div>
                    <div class="pl-2 pb-5 text-sm text-grey-darkest">
                        <ul class="pl-2">
                            <li class="pb-2">
                                Closer
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        <div class="flex justify-between">
            <div class="flex mt-12">
                <span class="text-sm">
                    Active Filters
                </span>
                <div class="ml-6 mt-1 rounded-full border border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                    {{ count($keywords) }}
                </div>
            </div>
            <div class="mt-12">
                <a href="#" wire:click="clearFilters" class="text-xs text-gray-600">
                    Clear Filters
                </a>
            </div>
        </div>
        <div class="mt-2 border-t border-gray-200">
            <div class="mt-2">
                @foreach($keywords as $word)
                <span class="rounded-full text-xs border border-gray-700 ml-1 px-2">
                    {{ $word }}
                </span>
                @endforeach
            </div>
        </div>
    </section>
    
    <div class="mt-6">
        <button type="button" wire:click="applyFilters" class="inline-flex w-full justify-center py-2 px-4 border-2 border-gray-700 text-sm leading-5 font-medium rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
            Apply Filters
        </button>
    </div> 
</div>