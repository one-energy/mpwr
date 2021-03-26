@props(['emitFormatted' => false])

@php
    $wireModel = $attributes->wire('model');
    $model     = $wireModel->value();
    $name      = $name ?? $model;
@endphp

<div x-data="{
    model: @entangle($wireModel),
    input: null,
    emitFormatted: @json($emitFormatted),
    maskInput(value) {
        this.input = this.mask(value)
    },
    mask(phoneNumber) {
        phoneNumber = phoneNumber.toString().replace(/[^0-9.]/g, '')
        if (phoneNumber.length > 10) {
            const countryCode = phoneNumber.substr(0, phoneNumber.length - 10)
            const areaCode    = phoneNumber.substr(-10, 3)
            const nextThree   = phoneNumber.substr(-7, 3)
            const lastFour    = phoneNumber.substr(-4, 4)
            return `+${countryCode} (${areaCode}) ${nextThree}-${lastFour}`
        }
        if (phoneNumber.length === 10) {
            const areaCode  = phoneNumber.substr(0, 3)
            const nextThree = phoneNumber.substr(3, 3)
            const lastFour  = phoneNumber.substr(6, 4)
            return `(${areaCode}) ${nextThree}-${lastFour}`
        }
        if (phoneNumber.length === 7) {
            const nextThree =  phoneNumber.substr(0, 3)
            const lastFour  =  phoneNumber.substr(3, 4)
            return `${nextThree}-${lastFour}`
        }
        return phoneNumber
    },
    unMask(maskedValue) {
        return maskedValue.toString().replace(/[^0-9.]/g, '')
    },
    emitInput() {
        this.model = this.emitFormatted
            ? this.input
            : this.unMask(this.input)
    }
}"
x-init="requestAnimationFrame(() => {
    const value = $wire.get('{{ $model }}')
    if (value) {
        input = mask(value)
    }
})">
    <x-input {{ $attributes->except(['wire:model', 'name', 'type', 'regex']) }}
        :name="$name"
        x-on:keyup="maskInput($event.target.value)"
        x-on:input.debounce.500ms="emitInput"
        x-model="input" />
</div>
