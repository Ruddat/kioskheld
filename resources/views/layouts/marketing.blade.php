<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Kioskheld – Dein Kiosk. Geliefert in Minuten.')</title>
    <meta name="description" content="@yield('meta_description', 'Kioskheld liefert Snacks, Getränke, Süßes und Bundles schnell zu dir. Einfach PLZ eingeben und Kioskheld in deiner Nähe finden.')">

    <meta name="theme-color" content="#050505">

    @vite([
        'resources/css/marketing.css',
        'resources/js/marketing-home.js',
    ])
</head>
<body class="marketing-body">
    @yield('content')
</body>
</html>
