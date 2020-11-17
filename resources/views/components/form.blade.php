@props(['route', 'put', 'patch', 'delete', 'get'])

<form {{ $attributes }} method="POST" action="{{ $route }}">
    @csrf

    @if($get ?? false )
        @method('GET')
    @endif

    @if($put ?? false )
        @method('PUT')
    @endif

    @if($delete ?? false )
        @method('DELETE')
    @endif

    @if($patch ?? false )
        @method('PATCH')
    @endif

    {{ $slot }}
</form>
