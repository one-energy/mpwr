@props(['label', 'name', 'value'])

@if($label ?? false)
    <div {{ $attributes }}>
        <label for="{{ $name }}" class="block text-sm font-medium leading-5 text-gray-700">{{ $label }}</label>

        <div class="relative mt-1 rounded-md shadow-sm">
            <select {{  $attributes->except('class') }}
                name="{{ $name }}" id="{{ $name }}"class="block w-full transition duration-150 ease-in-out form-select sm:text-sm sm:leading-5">
                {{ $slot }}
            </select>

            @error($name)
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <x-svg.alert class="w-5 h-5 text-red-500"></x-svg.alert>
                </div>
            @enderror
        </div>

        @error($name)
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>
@else
    <div {{  $attributes->except('wire:model')->merge(['class' => 'rounded-md shadow-sm' ]) }} >
        <select {{ $attributes->except('class') }}
            name="{{ $name }}" id="{{ $name }}" class="block w-full transition duration-150 ease-in-out form-select sm:text-sm sm:leading-5" >
          {{ $slot }}
        </select>
    </div>
@endif
