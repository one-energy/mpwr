<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/funnel.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="manifest" href="{{asset('manifest.json')}}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/images/icon/ios/icon-60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/icon/ios/icon-72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/images/icon/ios/icon-76.png">
    <link rel="apple-touch-icon" sizes="167x167" href="assets/images/icon/ios/icon-167.png">
    <link rel="apple-touch-icon" sizes="1024x1024" href="assets/images/icon/ios/icon-1024.png">
    <!-- iPhone Xs Max (1242px x 2688px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" href="assets/images/screen/ios/Default@3x~iphone.png">
    <!-- iPhone Xr (828px x 1792px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@3x~iphone.png">
    <!-- iPhone X, Xs (1125px x 2436px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="assets/images/screen/ios/Default@3x~iphone.png">
    <!-- iPhone 8 Plus, 7 Plus, 6s Plus, 6 Plus (1242px x 2208px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" href="assets/images/screen/ios/Default@2x~iphone.png">
    <!-- iPhone 8, 7, 6s, 6 (750px x 1334px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@2x~iphone.png">
    <!-- iPad Pro 12.9" (2048px x 2732px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@2x~ipad.png">
    <!-- iPad Pro 11” (1668px x 2388px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@2x~ipad.png">
    <!-- iPad Pro 10.5" (1668px x 2224px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@2x~ipad.png">
    <!-- iPad Mini, Air (1536px x 2048px) -->
    <link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" href="assets/images/screen/ios/Default@2x~ipad.png">
    <style>
        [x-cloak] { display: none; }
    </style>
    <livewire:styles/>
</head>

<body class="antialiased bg-white" x-data="{}">
@yield('app.content')

<livewire:scripts/>
</body>
</html>
