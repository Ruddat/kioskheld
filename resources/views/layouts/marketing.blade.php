<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        use App\Support\Seo\SeoRobots;
        use App\Support\Seo\SeoUrl;

        /*
         * Blade-Sections können bereits HTML-Entities enthalten.
         * Deshalb zunächst dekodieren und bei der Ausgabe anschließend
         * genau einmal über {{ }} sicher escapen.
         */
        $seoTitle = html_entity_decode(
            trim(
                $__env->yieldContent(
                    'title',
                    config('seo.defaults.title')
                )
            ),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8',
        );

        $seoDescription = html_entity_decode(
            trim(
                $__env->yieldContent(
                    'meta_description',
                    config('seo.defaults.description')
                )
            ),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8',
        );

        $seoRobots = trim(
            $__env->yieldContent(
                'robots',
                SeoRobots::content()
            )
        );

        /*
         * Die Unterseiten liefern unter canonical_url ausschließlich
         * die URL und keinen vollständigen <link>-Tag.
         */
        $seoCanonical = trim(
            $__env->yieldContent(
                'canonical_url',
                SeoUrl::canonical()
            )
        );

        $seoOgType = trim(
            $__env->yieldContent(
                'og_type',
                config('seo.defaults.og_type', 'website')
            )
        );

        $seoOgImage = trim(
            $__env->yieldContent('og_image')
        );

        $seoAlternates = SeoUrl::alternates();
        $seoXDefault = SeoUrl::xDefault();
        $seoOgLocale = SeoUrl::openGraphLocale();
    @endphp

    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ $seoTitle }}</title>

    <meta
        name="description"
        content="{{ $seoDescription }}"
    >

    <meta
        name="robots"
        content="{{ $seoRobots }}"
    >

    <link
        rel="canonical"
        href="{{ $seoCanonical }}"
    >

    @foreach ($seoAlternates as $locale => $alternateUrl)
        <link
            rel="alternate"
            hreflang="{{ $locale }}"
            href="{{ $alternateUrl }}"
        >
    @endforeach

    @if ($seoXDefault)
        <link
            rel="alternate"
            hreflang="x-default"
            href="{{ $seoXDefault }}"
        >
    @endif

    <meta
        property="og:site_name"
        content="{{ config('seo.site_name') }}"
    >

    <meta
        property="og:type"
        content="{{ $seoOgType }}"
    >

    <meta
        property="og:title"
        content="{{ $seoTitle }}"
    >

    <meta
        property="og:description"
        content="{{ $seoDescription }}"
    >

    <meta
        property="og:url"
        content="{{ $seoCanonical }}"
    >

    <meta
        property="og:locale"
        content="{{ $seoOgLocale }}"
    >

    @foreach ($seoAlternates as $locale => $alternateUrl)
        @continue($locale === app()->getLocale())

        <meta
            property="og:locale:alternate"
            content="{{ SeoUrl::openGraphLocale($locale) }}"
        >
    @endforeach

    @if ($seoOgImage !== '')
        <meta
            property="og:image"
            content="{{ $seoOgImage }}"
        >
    @endif

    <meta
        name="twitter:card"
        content="{{ config(
            'seo.defaults.twitter_card',
            'summary_large_image'
        ) }}"
    >

    <meta
        name="twitter:title"
        content="{{ $seoTitle }}"
    >

    <meta
        name="twitter:description"
        content="{{ $seoDescription }}"
    >

    @if ($seoOgImage !== '')
        <meta
            name="twitter:image"
            content="{{ $seoOgImage }}"
        >
    @endif

    <meta
        name="theme-color"
        content="#050505"
    >

    @stack('structured_data')
    @stack('head')

    @yield('structured_data')

    @vite([
        'resources/css/marketing.css',
        'resources/js/marketing-home.js',
    ])
</head>

<body class="marketing-body">
    @yield('content')
</body>

</html>
