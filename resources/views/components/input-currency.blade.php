@props(['label', 'name', 'value', 'tooltip', 'observation', 'disabledToUser', 'maxSize' => 100000, 'disabled' => false, 'atEnd' => 'USD', 'wire' => null])

@php
    $class = 'form-input block w-full pl-7 pr-12 sm:text-sm sm:leading-5';
    if( $errors->has($name) ) {
        $class .= 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red';
    }
    $tooltip        = $tooltip ?? null;
    $observation    = $observation ?? null;
    $disabledToUser = $disabledToUser ?? null;
    $wireModel = $attributes->wire('model');
    $model = $wireModel->value();
    $wire = $wire && is_bool($wire) ? $name : $attributes->wire('model')->value();

@endphp

<div {{ $attributes }} x-data="{
        model: @entangle($wire),
        inputValue: null,
        oldValue: 0,
        validateSize($event, $maxSize) {
            if($event.target.value > $maxSize){
                $event.target.value = this.oldValue
            } else {
                this.oldValue = $event.target.value
            }
        },
        startInput() {
            console.log(this.model)
            if (this.model) {
                const dec  = this.model.toString().split('.')[1]
                const len  = dec && dec.length > 2 ? dec.length : 2
                console.log(this.model)
                this.inputValue = Number(this.model).toFixed(len);
            }
        },
        updateInput() {
            this.model = this.inputValue
        }
    }" x-init="startInput()">
    <div class="flex">
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
        <input {{ $attributes->except('class')->merge(['class' => $class]) }}
               name="{{ $name }}" id="{{ $name }}"
               type="number"
               min="0"
               step="0.01"
               x-on:input="validateSize($event, {{$maxSize}})"
               x-model="inputValue"
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
