@props(['label', 'name', 'value', 'disabledToUser', 'disabled', 'wire' => null])

@php
    $class = 'form-input block w-full pr-10 sm:text-sm sm:leading-5';
    if( $errors->has($name) ) {
        $class .= 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red';
    }
    $disabledToUser = $disabledToUser ?? null;
    $disabled = $disabled ?? false;
    $wire = $wire && is_bool($wire) ? $name : $wire;
@endphp

<div {{ $attributes }}>
    <div class="mt-1 relative rounded-md shadow-sm">
        <input {{ $attributes->except('class')->merge(['class' => $class]) }}
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $label }}"
            @if($attributes->get('type') != 'password') value="{{ old($name, $value ?? null) }}" @endif
            @if ($wire) wire:model="{{ $wire }}" @endif
            @if(($disabledToUser && user()->role == $disabledToUser) || $disabled) disabled @endif/>

        <div class="absolute inset-y-0 right-0 flex items-center ">
            <button class="justify-center py-2 px-2 text-sm font-medium cursor-pointer focus:outline-none hover:bg-gray-50 bg-gray-200 text-white transition duration-150 ease-in-out" 
            type="button"  x-on:click="$dispatch('cancel-edit-input', {from: $event.target})">
                <x-svg.cancel class="h-5 w-4"/>
                
            </button>
            <button class="justify-center py-2 px-2 text-sm font-medium cursor-pointer focus:outline-none rounded-r-md border-r hover:bg-gray-50 bg-gray-200 text-white transition duration-150 ease-in-out" 
                    type="button">
                <x-svg.confirm class="h-5 w-4"/>
            </button>
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
