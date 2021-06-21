@props([
    'searchName' => '', //this is a string name of variable to search items
    'selected', 
    'placeholder', 
    'searchable' => true, 
    'label', 
    'name'
])

@php
    $class     = 'bg-white w-full border rounded-md shadow-sm pl-3 pr-10 py-2 text-left text-black cursor-pointer focus:outline-none focus:ring-1 sm:text-sm disabled:opacity-60';

    $name     = $name ?? '';
    $label    = $label ?? '';
    $selected = $selected ?? $placeholder;

    $hasError = $errors->has($name);
    $hasError
        ? $class .= 'border-red-400 text-red-600 focus:ring-red-500 focus:border-red-500'
        : $class .= 'border-gray-300 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500';
@endphp

<div x-data="initSearchSelect()">
    @if ($label)
        <label x-on:click="togglePopover" class="block text-sm font-medium {{ $hasError ? 'text-red-700' : 'text-gray-700' }}">
            {{ $label }}
        </label>
    @endif
    <div class="mt-1 relative">
        <button {{ $attributes->merge(['class' => $class]) }}
            x-on:click="togglePopover" type="button">
            {{$selected}}
        </button>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer">
            <x-icon name="selector" class="text-gray-400" />
        </span>

        <div class="absolute z-50 border m-1 w-full rounded-lg bg-white soft-shadow"
            x-show="popover"
            x-on:click.away="closePopover"
            x-on:keydown.escape="closePopover">
            @if($searchable)
                <div class="p-2">
                    <div class="items-baseline w-full space-y-4 sm:space-x-4 sm:flex sm:space-y-0">
                        <div class="relative rounded-md shadow-sm ">
                            <input wire:model.debounce.250ms="{{$searchName}}" placeholder="Search"/>
                        </div>
                    </div>
                    {{-- <x-search :search="$searchSelect" :perPage="false"/> --}}
                    {{-- <x-input-search
                        x-ref="search"
                        filled
                        x-model.debounce.750ms="search"
                        search="search"
                        x-on:keydown.arrow-down.prevent="$event.shiftKey || nextFocusable().focus()"
                        placeholder="Search here"
                    /> --}}
                </div>
            @endif
            <div class="max-h-60 overflow-auto soft-scrollbar text-base leading-6 focus:outline-none sm:text-sm sm:leading-5">
                {{$slot}}
            </div>
        </div>
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>

@push('scripts')
    <script>
        function initSearchSelect() {
            console.log('teste')
            return {
                popover: false,
                togglePopover() { this.popover = !this.popover },
                closePopover() {
                    this.popover = false
                    this.$refs.select.dispatchEvent(new Event('popup-close'))
                },
                select(option) {
                    this.model       = option.value
                    this.placeholder = option.label
                    this.closePopover()
                },
                focusables() { return [...$el.querySelectorAll('li, input')] },
                firstFocusable() { return this.focusables()[0] },
                lastFocusable() { return this.focusables().slice(-1)[0] },
                nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
                prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
                nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
                prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
            }
        }
    </script>
@endpush
