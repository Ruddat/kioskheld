<footer>
    <div class="container footer-grid">
        <x-marketing.logo :show-crown="false" />

        <nav
            class="footer-links"
            aria-label="{{ __('navigation.footer.navigation') }}"
        >
            <a href="{{ route('home') }}#find">
                {{ __('navigation.footer.find_kiosk') }}
            </a>

            <a href="{{ route('home') }}#bundles">
                {{ __('navigation.footer.assortment') }}
            </a>

            <a href="{{ route('home') }}#partner">
                {{ __('navigation.footer.for_partners') }}
            </a>

            <a href="{{ route('about') }}">
                {{ __('navigation.footer.about') }}
            </a>

            <a href="{{ route('faq') }}">
                {{ __('navigation.footer.faq') }}
            </a>

            <a href="{{ route('legal.imprint') }}">
                {{ __('navigation.footer.imprint') }}
            </a>

            <a href="{{ route('legal.privacy') }}">
                {{ __('navigation.footer.privacy') }}
            </a>

            <a href="{{ route('legal.terms') }}">
                {{ __('navigation.footer.terms') }}
            </a>
        </nav>
    </div>
</footer>
