@props([
    'placeholder' => 'Select a value',
    'searchable'  => true,
    'multiselect' => false,
    'options'     => null, // only name of livewire property
    'optionValue' => null,
    'optionLabel' => null,
    'noneOption'  => false,
    'showAlert'   => false,
    'label'       => null,
    'name'        => null,
    'id'          => null,
])

@php
    $wireModel = $attributes->wire('model');
    $model     = $wireModel->value();
    $class     = 'bg-white w-full border rounded-md shadow-sm pl-3 pr-10 py-2 text-left text-black cursor-pointer focus:outline-none focus:ring-1 sm:text-sm disabled:opacity-60';
    $name      = $name ?? $model ?? $id ?? null;
    $id        = $id   ?? $name  ?? null;
    $hasError = $errors->has($name);
    $hasError
        ? $class .= 'border-red-400 text-red-600 focus:ring-red-500 focus:border-red-500'
        : $class .= 'border-gray-300 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500';
@endphp

<div x-data="{
    searchable:  @json(filter_var($searchable, FILTER_VALIDATE_BOOLEAN)),
    multiselect: @json(filter_var($multiselect, FILTER_VALIDATE_BOOLEAN)),
    noneOption:  @json(filter_var($multiselect, FILTER_VALIDATE_BOOLEAN)),
    popover: false,
    search: '',
    name: '{{ $name }}',
    placeholder: '{{ $placeholder }}',
    optionValue: '{{ $optionValue }}',
    optionLabel: '{{ $optionLabel }}',
    model: @entangle($wireModel),
    rawOptions: @entangle($options),
    options: [],
    refreshOptions() {
        this.options = this.rawOptions.map(option => {
            if (!this.optionValue) {
                return { label: option, value: option }
            }
            return {
                label: this.optionLabel == 'firstAndLastName' ? option['first_name'] + ' ' + option['last_name']: option[this.optionLabel],
                value: option[this.optionValue]
            }
        })

    },
    getFilteredOptions() {
        if (!this.searchable) return this.options
        return this.options.filter(option => {
            return option.label.toLowerCase().includes(this.search.toLowerCase())
        })
    },
    togglePopover() { this.popover = !this.popover },
    closePopover() {
        this.popover = false
        this.$refs.select.dispatchEvent(new Event('popup-close'))
    },
    select(option) {
        if (this.multiselect) {
            const model = Object.assign([], this.model)
            const index = model.findIndex(value => value == option.value)
            index > -1 ? model.splice(index) : model.push(option.value)
            this.model = model
        } else {
            this.model       = option.value
            this.placeholder = option.label
            this.closePopover()
        }
    },
    isSelected(value) {
        if (this.multiselect) {
            return !!this.model?.find(option => option == value)
        }
        return value == this.model
    },
    clearModel() {
        if (this.multiselect) {
            return this.model = []
        }
        this.model = null
    },
    isEmptyModel() {
        if (this.multiselect) {
            return this.model?.length == 0
        }
        return this.model == null
    },
    getLabel() {
        if (this.multiselect) {
            if (!this.model?.length) return this.placeholder
            const selecteds = this.model?.map(value => {
                return this.options.find(option => option.value === value)?.label
            }).join(', ')
            return `${this.model.length}: ${selecteds}`
        }
        return this.placeholder
    },
    focusables() { return [...$el.querySelectorAll('li, input')] },
    firstFocusable() { return this.focusables()[0] },
    lastFocusable() { return this.focusables().slice(-1)[0] },
    nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
    prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
    nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
    prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
}"
x-init="() => {
    refreshOptions()
    if (multiselect && !model) { model = [] }
    $watch('rawOptions', () => refreshOptions())
    $watch('model', value => {
        if (isEmptyModel()) {
            placeholder = '{{ $placeholder }}'
        }
    })
    $watch('popover', value => {
        if (value) {
            $nextTick(() => $refs.search.focus())
        }
    })
}">
    @if ($label)
        <label x-on:click="togglePopover" id="{{ $id }}" class="block text-sm font-medium {{ $hasError ? 'text-red-700' : 'text-gray-700' }}">
            {{ $label }}
        </label>
    @endif
    <div class="mt-1 relative">
        <button {{ $attributes->merge(['class' => $class]) }}
            x-on:click="togglePopover"
            type="button">
            <div class="flex justify-between">
                <span class="block truncate text-black @if($showAlert) italic text-gray-400 @endif" x-text="getLabel()"></span>
                @if ($showAlert)
                    <x-svg.alert class="h-5"/>
                @endif
            </div>
        </button>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer">
            <x-icon class="text-gray-400  hover:text-red-500"
                x-on:click.prevent="clearModel"
                name="close"
                x-show="!isEmptyModel()" />
            <x-icon name="selector" class="text-gray-400" />
        </span>

        <div class="absolute z-50 border-t mt-1 w-full rounded-lg bg-white soft-shadow"
            x-show="popover"
            x-on:click.away="closePopover"
            x-on:keydown.escape="closePopover">
            <div x-show="searchable" class="p-2">
                <x-input-search
                    x-ref="search"
                    filled
                    x-model.debounce.750ms="search"
                    search="search"
                    x-on:keydown.arrow-down.prevent="$event.shiftKey || nextFocusable().focus()"
                    placeholder="Search here"
                />
            </div>
            <ul class="max-h-60 overflow-auto soft-scrollbar text-base leading-6 focus:outline-none sm:text-sm sm:leading-5"
                x-ref="list"
                tabindex="-1"
                x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
                x-on:keydown.arrow-down.prevent="$event.shiftKey || nextFocusable().focus()"
                x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
                x-on:keydown.arrow-up.prevent="prevFocusable().focus()">

                @if($noneOption)
                    <li class="focus:outline-none focus:bg-indigo-100 focus:text-indigo-800 text-gray-900
                                cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm
                                transition-colors ease-in-out hover:text-white
                                duration-100 group"
                        :class="{
                            'hover:bg-red-500'   :  isSelected(null),
                            'hover:bg-green-500': !isSelected(null),
                        }"
                        tabindex="0"
                        x-on:click="select(new Object({ label: 'Self Gen', value: null }))"
                        x-on:keydown.enter="select([value: 0, placeholder:'Self Gen'])">
                        <span class="block truncate" :class="{
                                'font-semibold':  isSelected(null),
                                'font-normal'  : !isSelected(null),
                            }">
                            Self Gen
                        </span>

                        <span class="absolute group-focus:text-white group-hover:text-white
                                        inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                            x-show="isSelected(null)">
                            <x-icon name="check" />
                        </span>
                    </li>
                @endif
                <template x-for='(option, index) in getFilteredOptions()' :key="`${name}-item-${index}`">
                    <li class="focus:outline-none focus:bg-indigo-100 focus:text-indigo-800 text-gray-900
                               cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm
                               transition-colors ease-in-out hover:text-white
                               duration-100 group"
                        :class="{
                            'hover:bg-red-500'   :  isSelected(option.value),
                            'hover:bg-green-500': !isSelected(option.value),
                        }"
                        tabindex="0"
                        x-on:click="select(option)"
                        x-on:keydown.enter="select(option)">
                        <span class="block truncate" :class="{
                                'font-semibold':  isSelected(option.value),
                                'font-normal'  : !isSelected(option.value),
                            }"
                            x-text="option.label">
                        </span>

                        <span class="absolute group-focus:text-white group-hover:text-white
                                     inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                            x-show="isSelected(option.value)">
                            <x-icon name="check" />
                        </span>
                    </li>
                </template>
                <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 rounded-sm"
                    x-show="!getFilteredOptions().length"
                    x-on:click="closePopover">
                    <span class="block truncate font-normal">
                        No options
                    </span>
                </li>
            </ul>
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
