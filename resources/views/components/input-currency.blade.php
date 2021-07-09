@props(['label', 'name', 'value', 'tooltip', 'observation', 'disabledToUser', 'maxSize' => 100000, 'disabled' => false, 'atEnd' => 'USD', 'wire' => null])

@php
    $class = 'form-input block w-full pl-7 pr-12 sm:text-sm sm:leading-5';
    if( $errors->has($name) ) {
        $class .= 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red';
    }

    $tooltip        = $tooltip ?? null;
    $observation    = $observation ?? null;
    $disabledToUser = $disabledToUser ?? null;
    $name = $name ?? 'currency-input';
    $wireModel = $attributes->wire('model');
    $model = $wireModel->value();
@endphp

<div {{ $attributes->except('wire:model', 'value') }} x-data="{
        model:  @if ($model) @entangle($model) @else '' @endif,
        inputValue: null,
        inputName: {{json_encode($name)}},
        editingInput: null,
        oldValue: 0,
        validateInput($event, $maxSize) {
            this.inputValue = this.inputValue.replace(/(?!\.)\D/g, '').replace(/(?<=\..*)\./g, '').replace(/(?<=\.\d\d).*/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            if(parseFloat($event.target.value) > $maxSize){

                $event.target.value = this.oldValue;
            } else {
                this.oldValue = $event.target.value;
            }
        },
        formatValue(event) {
            event.target.value = this.currencyFormat(event.target.value);
        },
        currencyFormat(value) {
            return value.toString().replace(/(?!\.)\D/g, '').replace(/(?<=\..*)\./g, '').replace(/(?<=\.\d\d).*/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
        startInput() {
            inputElement = document.getElementById(this.inputName)
            this.inputValue = this.model ?? inputElement.value;
            formatter = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2
            })
            if (this.inputValue) {
                this.inputValue = formatter.format(this.inputValue);
            }
        },
        async updateModel(event) {
            this.editingInput = this.inputName;
            await this.validateInput(event, '{{$maxSize}}');
            value = this.inputValue.replaceAll(',', '')
            this.model = parseFloat(value);
        }
    }" x-init="() => {
        startInput();
        $watch('model', (newValue) => {
            formatter = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2
            });
            if (newValue && (!editingInput || editingInput != inputName)) {
                inputValue = currencyFormat(newValue);
            }
        })
    }">
    <div class="flex" key="{{$name}}">
        <label for="{{ $name }}" class="block text-sm font-medium leading-5 text-gray-700">{{ $label }}</label>
        @if($tooltip)
            <x-svg.question-mark class="h-4 w-4 text-gray-600 cursor-pointer ml-1" x-on:mouseenter="tooltipShow = true" x-on:mouseleave="tooltipShow = false" x-on:click="tooltipShow = true"></x-svg.question-mark>
            <div class="relative">
                <div x-show="tooltipShow" class="absolute left-1 bottom-1 px-2 py-1 bg-gray-700 text-xs text-white bg-white shadow border-0 block z-50 text-center break-words rounded-md">{{ $tooltip }}</div>
            </div>
        @endif
        @if($observation)
            <label class="text-sm leading-5 text-gray-500 ml-1">- {{ $observation }}</label>
        @endif
    </div>

    <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm sm:leading-5">
                $
            </span>
        </div>
        <input {{ $attributes->except(['class', 'wire:model', 'value', 'type'])->merge(['class' => $class]) }}
               name="{{ $name }}" id="{{ $name }}"
               type="text"
               x-on:blur="formatValue($event)"
               x-on:input="updateModel($event)"
               x-model="inputValue"
               value="{{ old($name, $value ?? null) }}"
               @if(($disabledToUser && user()->role == $disabledToUser) || $disabled) disabled @endif/>

        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm sm:leading-5">
                {{$atEnd}}
            </span>
        </div>

        @error($name)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <x-svg.alert class="h-5 w-5 text-red-500"></x-svg.alert>
        </div>
        @enderror
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
