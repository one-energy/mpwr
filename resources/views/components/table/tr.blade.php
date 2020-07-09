@props(['loop'])

<tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
    {{ $slot }}
</tr>
