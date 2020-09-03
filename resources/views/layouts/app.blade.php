<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>[x-cloak] { display: none; }</style>
    <livewire:styles/>
</head>

<body class="antialiased bg-white" x-data="{}">
@yield('app.content')

<livewire:scripts/>
</body>
</html>
