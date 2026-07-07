<nav class="container nav" aria-label="Hauptnavigation">
    <x-marketing.logo />

    <div class="nav-links">
        <a href="{{ route('home') }}#how">So funktioniert's</a>
        <a href="{{ route('home') }}#find">Kioske finden</a>
        <a href="{{ route('home') }}#bundles">Sortiment</a>
        <a href="{{ route('home') }}#partner">Für Partner</a>
        <a href="{{ route('about') }}">Über uns</a>
        <a class="partner-btn" href="{{ route('home') }}#partner">👤 Partner werden</a>
    </div>

    <div class="mobile-menu" aria-hidden="true">☰</div>
</nav>
