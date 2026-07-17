<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kioskheld Admin') · Kioskheld</title>

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <span class="admin-brand-mark">K</span>
                <span>
                    <strong>Kioskheld</strong>
                    <small>Admin</small>
                </span>
            </a>

            <nav class="admin-nav" aria-label="Admin Navigation">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>
                    Dashboard
                </a>

    <a href="{{ route('admin.analytics.index') }}"
        @class(['active' => request()->routeIs('admin.analytics.*')])>
        Analytics
    </a>

                <a href="{{ route('admin.partner-leads.index') }}" @class(['active' => request()->routeIs('admin.partner-leads.*')])>
                    Partner-Anfragen
                </a>

                <a href="{{ route('home', ['locale' => config('app.locale', 'de')]) }}" target="_blank" rel="noopener">
                    Website ansehen
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <span class="admin-topbar-label">Interner Bereich</span>
                    <strong>@yield('title', 'Dashboard')</strong>
                </div>

                <div class="admin-user">
                    {{ auth()->user()?->name ?? 'Admin' }}
                </div>
            </header>

            @if (session('status'))
                <div class="admin-flash">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>

</html>
