@props(['route', 'put', 'patch', 'delete'])

<form {{ $attributes }} method="POST" action="{{ $route }}">
    @csrf

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
