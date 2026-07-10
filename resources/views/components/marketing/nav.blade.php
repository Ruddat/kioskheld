<nav class="container nav" aria-label="Hauptnavigation">
    <x-marketing.logo />

    <div class="nav-links">
        <a href="{{ route('home') }}#how">So funktioniert's</a>
        <a href="{{ route('home') }}#find">Kioske finden</a>
        <a href="{{ route('home') }}#bundles">Sortiment</a>

        <a href="{{ route('partner.index') }}">Für Partner</a>

        <a href="{{ route('about') }}">Über uns</a>

        <a class="partner-btn" href="{{ route('partner.register') }}">
            👤 Partner werden
        </a>
    </div>

    <div class="mobile-menu" aria-hidden="true">☰</div>
</nav>
