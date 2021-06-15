@props([
    'options',
    'labeledBy',
    'trackBy',
    'placeholder' => 'Select an option',
    'label' => '',
    'name' => '',
    'searchable' => 'true'
])

<select id="select" class="hidden" {{ $attributes }} name="{{ $name }}" multiple>
    @foreach($options as $option)
        <option value="{{ is_object($option) ? $option->{$trackBy} : $option[$trackBy] }}">
            {{ is_object($option) ? $option->{$labeledBy} : $option[$labeledBy] }}
        </option>
    @endforeach
</select>

<div x-data="dropdown()" x-init="() => {
    baseOptions = {{ json_encode($options) }}
    loadOptions();
    @if ($attributes->has('wire:model'))
        $watch('selected', (value) => {
            if (Array.isArray(value)) {
                $wire.set('{{ $attributes->wire('model')->value() }}', selectedValues);
            }
        });
    @endif
}" class="sm:flex-1">
    <div class="rounded-md relative w-full">
        <div class="flex flex-col items-center relative" x-cloak>
            <div x-on:click="open" class="w-full" x-cloak>
                <label for="select" class="mt-2 block text-sm font-medium leading-5 text-gray-700">{{ $label }}</label>
                <div class="mb-2 mt-1 p-1 pt-0 flex border border-gray-300 bg-white rounded-md">
                    <div class="flex flex-auto flex-wrap">
                        <template x-for="option in selected" :key="option.key">
                            <div class="space-x-1 flex justify-center items-center m-1 font-medium py-1.5 px-2 text-white rounded bg-green-400 border">
                                <span
                                    class="text-xs font-normal leading-none max-w-full flex-initial"
                                    x-text="option.text">
                                </span>
                                <div class="flex flex-auto flex-row-reverse">
                                    <div class="cursor-pointer" x-on:click.stop="remove(option)">
                                        <x-icon icon="x" class="h-3 w-3 text-gray-700" />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="selected.length == 0" class="flex-1">
                            @if (!$searchable)
                                <input
                                    placeholder="{{ $placeholder }}"
                                    class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                    disabled
                                >
                            @else
                                <input
                                    placeholder="{{ $placeholder }}"
                                    class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                    x-model="search"
                                >
                            @endif
                        </div>
                    </div>
                    <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                        <button
                            type="button"
                            x-show="isOpen"
                            x-on:click="open"
                            class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                            <x-icon icon="chevron-down" class="h-4 w-4 text-gray-500" />
                        </button>
                        <button
                            type="button"
                            x-show="!isOpen"
                            @click="close"
                            class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                            <x-icon icon="chevron-up" class="h-4 w-4 text-gray-500" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div x-show.transition.origin.top="isOpen"
                     class="absolute shadow top-100 bg-white z-40 w-full left-0 max-h-select"
                     x-on:click.away="close">
                    <div
                        class="flex flex-col w-full overflow-y-auto"
                        :class="{
                            'h-40': options.length > 0,
                            'h-10': options.length < 1,
                        }"
                    >
                        <template x-for="(option, index) in options" :key="option.key" class="overflow-auto">
                            <div
                                class="cursor-pointer w-full hover:bg-gray-100"
                                :class="{'bg-green-400 text-white hover:bg-red-400': option.selected}"
                                @click="select(option, index)"
                            >
                                <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                    <div class="w-full items-center flex justify-between">
                                        <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                        <div x-show="option.selected">
                                            {{-- TODO: Can add some check icon --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
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
        function dropdown() {
            return {
                model: @if ($attributes->has('wire:model')) @entangle($attributes->wire('model')) @else [] @endif,
                baseOptions: @json($options),
                searchable: '{{ $searchable }}' === 'true',
                search: '',
                options: [],
                selected: [],
                show: false,
                open() {
                    this.show = true;
                    this.search = '';
                },
                close() {
                    this.show = false;
                    this.search = '';
                },
                select(option, index) {
                    this.search = '';

                    if (this.options[index].selected) {
                        this.optionsEl[index].selected = false;
                        this.options[index].selected = false;
                        this.selected = this.selected.filter(({ key }) => key !== option.key);

                        return;
                    }

                    this.options[index].selected = true;
                    this.optionsEl[index].selected = true;
                    this.selected = [...this.selected, { ...option }];
                },
                remove(option) {
                    const index = this.options.findIndex(({ key }) => key === option.key);
                    const foundOption = this.options[index];

                    this.options[index] = {...foundOption, selected: false}
                    this.selected = this.selected.filter(({ key }) => key !== option.key);
                },
                isNotEmpty(value) {
                    return Array.isArray(value) && value.length > 0;
                },
                loadOptions() {
                    this.options = this.baseOptions.map((option, index) => {
                        return {
                            index,
                            value: option['{{ $trackBy }}'],
                            text: option['{{ $labeledBy }}'],
                            key: Math.random().toString(36).substr(2, 5),
                            selected: this.isNotEmpty(this.model) ? this.model.includes(option['{{ $trackBy }}']) : false
                        }
                    });

                    if (this.isNotEmpty(this.model)) {
                        this.selected = this.options.filter(option => this.model.includes(option.value));
                    }
                },
                get filteredOptions() {
                    if (!this.search) return this.options;

                    return this.options.filter(
                        option => option.text.toLocaleLowerCase().includes(this.search.toLocaleLowerCase())
                    );
                },
                get optionsEl() {
                    return document.getElementById('select').options;
                },
                get selectedValues() {
                    return this.selected.map(({ value }) => value)
                },
                get isOpen() {
                    return this.show === true;
                }
            };
        }
    </script>
@endpush
