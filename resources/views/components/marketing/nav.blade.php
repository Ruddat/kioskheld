<nav class="container nav" aria-label="{{ __('navigation.main_navigation') }}">
    <x-marketing.logo />

    <div class="nav-links">
        <a href="{{ route('home') }}#how">
            {{ __('navigation.how_it_works') }}
        </a>

        <a href="{{ route('home') }}#find">
            {{ __('navigation.find_kiosk') }}
        </a>

        {{--
        <a href="{{ route('home') }}#bundles">
            {{ __('navigation.assortment') }}
        </a>
        --}}

        <a href="{{ route('partner.index') }}">
            {{ __('navigation.for_partners') }}
        </a>

        <a href="{{ route('about') }}">
            {{ __('navigation.about') }}
        </a>

        <x-marketing.language-switcher />

        <a class="partner-btn" href="{{ route('partner.register') }}">
            👤 {{ __('navigation.become_partner') }}
        </a>
    </div>

    <div class="mobile-menu" aria-hidden="true">☰</div>
</nav>
