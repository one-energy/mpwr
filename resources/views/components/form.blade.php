@props(['route', 'put', 'patch'])

<form {{ $attributes }} method="POST" action="{{ $route }}">
    @csrf

    @if($put ?? false )
        @method('PUT')
    @endif

    @if($patch ?? false )
        @method('PATCH')
    @endif

    {{ $slot }}
</form>
