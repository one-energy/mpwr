@props(['label', 'name', 'value', 'addOn', 'disabledToUser'])

@php
    $class = 'form-input block w-full pr-12 sm:text-sm sm:leading-5';
    if( $errors->has($name) ) {
        $class .= 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red';
    }
    $disabledToUser = $disabledToUser ?? null;
@endphp

<div {{ $attributes }}>
    <label for="{{ $name }}" class="block text-sm font-medium leading-5 text-gray-700">{{ $label }}</label>

    <div class="mt-1 relative rounded-md shadow-sm">
        <input {{ $attributes->except('class')->merge(['class' => $class]) }}
               name="{{ $name }}" id="{{ $name }}"
               type="number"
               min="0"
               step="0.01" 
               value="{{ old($name, $value ?? null) }}"
               @if($disabledToUser && user()->role == $disabledToUser) disabled @endif/>
        
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm sm:leading-5">
                {{ $addOn }}
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