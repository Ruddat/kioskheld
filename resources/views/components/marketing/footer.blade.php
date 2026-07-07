<footer>
    <div class="container footer-grid">
        <x-marketing.logo :show-crown="false" />

        <nav class="footer-links" aria-label="Footer Navigation">
            <a href="{{ route('home') }}#find">Kioske finden</a>
            <a href="{{ route('home') }}#bundles">Sortiment</a>
            <a href="{{ route('home') }}#partner">Für Partner</a>

            <a href="{{ route('about') }}">Über uns</a>
            <a href="{{ route('home') }}#faq">FAQ</a>

            <a href="{{ route('legal.imprint') }}">Impressum</a>
            <a href="{{ route('legal.privacy') }}">Datenschutz</a>
            <a href="{{ route('home') }}#agb">AGB</a>
        </nav>
    </div>
</footer>
