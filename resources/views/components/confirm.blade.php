@props(['title', 'description', 'confirmButtonLabel', 'color'])

@php
    $color  = $color ?? 'red';
@endphp

<div {{ $attributes }} x-data="{open : false, target: null}"
     x-on:confirm.window="open = true; target = $event.detail.from"
     x-show="open"
     class="fixed inset-x-0 bottom-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
>
    <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="px-4 pt-5 pb-4 overflow-hidden transition-all transform bg-white rounded-lg shadow-xl sm:max-w-lg sm:w-full sm:p-6"
        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <div class="sm:flex sm:items-start">
            <div
                class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto @if($color == 'red') bg-red-100 @else bg-green-100 @endif rounded-full sm:mx-0 sm:h-10 sm:w-10">
                @if($color == 'red')
                    <svg class="w-6 h-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                @else
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
                   {{ $title }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500">
                        {{ $description }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <button type="button"
                        x-on:click="target.dispatchEvent(new CustomEvent('confirmed')); open = false;"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2
                        @if($color == 'red') text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50
                        @else border-green-base text-green-base hover:text-green-dark hover:border-green-dark focus:border-green-500 focus:shadow-outline-green active:bg-green-50
                        @endif focus:outline-none transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md">
                    {{ $confirmButtonLabel ?? 'Confirm' }}
                </button>
            </div>
            <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button type="button"
                        x-on:click="open = false"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green sm:text-sm sm:leading-5">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
