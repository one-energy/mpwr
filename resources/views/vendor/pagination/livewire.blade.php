@if ($paginator->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
         xmlns:wire="http://www.w3.org/1999/xhtml">
        <div class="flex-1 flex justify-between md:hidden">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="@lang('pagination.previous')"
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md
                      text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-green focus:border-green-300
                      active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 cursor-not-allowed">
                    @lang('pagination.previous')
                </span>
            @else
                <button wire:click="previousPage" rel="prev" aria-label="@lang('pagination.previous')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md
                        text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-green focus:border-green-300 active:bg-gray-100
                        active:text-gray-700 transition ease-in-out duration-150">
                    @lang('pagination.previous')
                </button>
            @endif

            <div class="self-center">
                <p class="text-xs sm:text-sm leading-5 text-gray-700">
                    <span>@lang('pagination.showing')</span>
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    <span>@lang('pagination.to')</span>
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>@lang('pagination.of')</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>@lang('pagination.results')</span>
                </p>
            </div>

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" rel="prev" aria-label="@lang('pagination.next')"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700
                        bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-green focus:border-green-300 active:bg-gray-100 active:text-gray-700
                        transition ease-in-out duration-150">
                    @lang('pagination.next')
                </button>
            @else
                <span aria-disabled="true" aria-label="@lang('pagination.next')"
                      class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700
                      bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-green focus:border-green-300 active:bg-gray-100 active:text-gray-700
                      transition ease-in-out duration-150">
                    @lang('pagination.next')
                </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 md:flex md:items-center md:justify-between">
            <div>
                <p class="text-sm leading-5 text-gray-700">
                    <span>@lang('pagination.showing')</span>
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    <span>@lang('pagination.to')</span>
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>@lang('pagination.of')</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>@lang('pagination.results')</span>
                </p>
            </div>
            <div>
                <span class="relative z-0 inline-flex shadow-sm">
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium
                        text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-100
                        active:text-gray-500 transition ease-in-out duration-150 cursor-not-allowed">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                  clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @else
                        <button wire:click="previousPage" type="button"
                                class="inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium
                                text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-100
                                active:text-gray-500 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                  clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif

                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span
                                class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700">
                              {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                          class="cursor-not-allowed -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-white
                                            hover:bg-green-dark bg-green-base focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-green-base active:text-gray-700
                                            transition ease-in-out duration-150">
                                      {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" type="button"
                                            class="-ml-px inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500
                                            focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                      {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" type="button"
                                class="-ml-px inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @else
                        <span
                            class="cursor-not-allowed -ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>

    </div>
@endif
