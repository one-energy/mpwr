@props(['loop'])

<tr {{ $attributes->merge(['class' => '']) }}>
    {{ $slot }}
</tr>
