@extends('layouts.marketing')

@php
    $productName = html_entity_decode(
        $product->name,
        ENT_QUOTES | ENT_HTML5,
        'UTF-8',
    );

    $productBrand = $product->brand
        ? html_entity_decode(
            $product->brand,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8',
        )
        : null;

    $canonicalUrl = route('catalog.products.show', [
        'locale' => app()->getLocale(),
        'productSlug' => $product->slug,
    ]);

    $metaDescription = \Illuminate\Support\Str::limit(
        $productName
            . ' bei teilnehmenden Kiosken bestellen. '
            . 'Prüfe mit deiner PLZ Preis, Verfügbarkeit und Lieferung in deiner Nähe.',
        155,
    );

    $schema = array_filter(
        [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $productName,
            'image' => filled($product->image_url)
                ? [$product->image_url]
                : null,
            'description' => $product->short_description
                ?: $product->description,
            'brand' => $productBrand
                ? [
                    '@type' => 'Brand',
                    'name' => $productBrand,
                ]
                : null,
            'sku' => filled($product->external_id)
                ? 'JD-' . $product->external_id
                : null,
            'gtin' => $product->gtin,
        ],
        fn ($value) => $value !== null && $value !== '',
    );
@endphp

@section(
    'title',
    $productName . ' liefern lassen | Kioskheld'
)

@section(
    'meta_description',
    $metaDescription
)

@section(
    'canonical_url',
    $canonicalUrl
)

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        ) !!}
    </script>
@endsection

@section('content')
    <main class="catalog-page">
        <section class="catalog-product-detail-hero">
            <div class="container">
                <nav
                    class="catalog-breadcrumb catalog-breadcrumb-dark"
                    aria-label="Breadcrumb"
                >
                    <a
                        href="{{ route('catalog.products.index', [
                            'locale' => app()->getLocale(),
                        ]) }}"
                    >
                        Produkte
                    </a>

                    @if ($product->category)
                        <span aria-hidden="true">›</span>

                        <a
                            href="{{ route('catalog.categories.show', [
                                'locale' => app()->getLocale(),
                                'categorySlug' => $product->category->slug,
                            ]) }}"
                        >
                            {{ $product->category->name }}
                        </a>
                    @endif

                    <span aria-hidden="true">›</span>

                    <span>{{ $product->name }}</span>
                </nav>

                <div class="catalog-product-detail-grid">
<div class="catalog-product-detail-media">
    @if (filled($product->image_url))
        <img
            class="catalog-product-detail-image"
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            loading="eager"
            decoding="async"
        >
    @else
        <div
            class="catalog-product-detail-placeholder"
            aria-hidden="true"
        >
            {{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}
        </div>
    @endif
</div>

                    <div class="catalog-product-detail-copy">
                        @if ($product->brand)
                            <p class="catalog-product-detail-brand">
                                {{ $product->brand }}
                            </p>
                        @endif

                        <h1>{{ $product->name }}</h1>

                        @if ($product->short_description)
                            <p class="catalog-product-detail-lead">
                                {{ \Illuminate\Support\Str::limit(
                                    $product->short_description,
                                    220
                                ) }}
                            </p>
                        @elseif ($product->description)
                            <p class="catalog-product-detail-lead">
                                {{ \Illuminate\Support\Str::limit(
                                    strip_tags($product->description),
                                    220
                                ) }}
                            </p>
                        @endif

                        @if ($product->lowest_price !== null)
                            <div class="catalog-product-detail-price">
                                <span>Ab</span>

                                <strong>
                                    {{ number_format(
                                        (float) $product->lowest_price,
                                        2,
                                        ',',
                                        '.'
                                    ) }}
                                    €
                                </strong>
                            </div>
                        @endif

                        <div class="catalog-product-detail-notice">
                            <strong>Bei dir verfügbar?</strong>

                            <p>
                                Gib deine Postleitzahl ein. Danach zeigen wir dir,
                                welcher Kiosk dieses Produkt anbietet und welcher
                                Preis dort tatsächlich gilt.
                            </p>
                        </div>

                        <div class="catalog-product-detail-actions">
                            <a
                                href="{{ route('home') }}#find"
                                class="catalog-detail-primary"
                            >
                                Jetzt per PLZ prüfen
                            </a>

                            @if ($product->category)
                                <a
                                    href="{{ route('catalog.categories.show', [
                                        'locale' => app()->getLocale(),
                                        'categorySlug' => $product->category->slug,
                                    ]) }}"
                                    class="catalog-product-detail-category-link"
                                >
                                    Weitere {{ $product->category->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="catalog-product-detail-content">
            <div class="container">
                <div class="catalog-product-info-grid">
                    <article class="catalog-product-info-card">
                        <p class="catalog-section-kicker">
                            Produktinformationen
                        </p>

                        <h2>Mehr über {{ $product->name }}</h2>

                        <div class="catalog-product-description-copy">
                            @if (
                                filled($product->description)
                                && $product->description !== $product->short_description
                            )
                                {!! nl2br(e($product->description)) !!}
                            @elseif (filled($product->short_description))
                                {{ $product->short_description }}
                            @else
                                Zu diesem Produkt liegen aktuell noch keine weiteren
                                Informationen vor.
                            @endif
                        </div>
                    </article>

                    <aside class="catalog-product-facts">
                        <p class="catalog-section-kicker">
                            Auf einen Blick
                        </p>

                        <h2>Produktdetails</h2>

                        <dl>
                            @if ($product->brand)
                                <div>
                                    <dt>Marke</dt>
                                    <dd>{{ $product->brand }}</dd>
                                </div>
                            @endif

                            @if ($product->category)
                                <div>
                                    <dt>Kategorie</dt>

                                    <dd>
                                        <a
                                            href="{{ route('catalog.categories.show', [
                                                'locale' => app()->getLocale(),
                                                'categorySlug' => $product->category->slug,
                                            ]) }}"
                                        >
                                            {{ $product->category->name }}
                                        </a>
                                    </dd>
                                </div>
                            @endif

                            @if ($product->gtin)
                                <div>
                                    <dt>EAN</dt>
                                    <dd>{{ $product->gtin }}</dd>
                                </div>
                            @endif

                            @if ($product->lowest_price !== null)
                                <div>
                                    <dt>Preis</dt>

                                    <dd>
                                        ab
                                        {{ number_format(
                                            (float) $product->lowest_price,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                        €
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </aside>
                </div>

                @php
                    $displayVariants = $product->variants
                        ->filter(function ($variant) {
                            $name = mb_strtolower(trim($variant->name));

                            return ! in_array($name, [
                                'standard',
                                'standart',
                                'default',
                            ], true);
                        });
                @endphp

                @if ($displayVariants->isNotEmpty())
                    <section class="catalog-product-variants">
                        <div class="catalog-section-heading">
                            <div>
                                <p class="catalog-section-kicker">
                                    Varianten
                                </p>

                                <h2>Verfügbare Ausführungen</h2>
                            </div>

                            <p>
                                Die konkrete Variante und Verfügbarkeit hängt
                                vom ausgewählten Kiosk ab.
                            </p>
                        </div>

                        <div class="catalog-variant-grid">
                            @foreach ($displayVariants as $variant)
                                <article class="catalog-variant-card">
                                    <h3>{{ $variant->name }}</h3>

                                    @if ($variant->description)
                                        <p>{{ $variant->description }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($relatedProducts->isNotEmpty())
                    <section class="catalog-related-products">
                        <div class="catalog-section-heading">
                            <div>
                                <p class="catalog-section-kicker">
                                    Weitere Produkte
                                </p>

                                <h2>
                                    Das könnte dich auch interessieren
                                </h2>
                            </div>

                            @if ($product->category)
                                <a
                                    href="{{ route('catalog.categories.show', [
                                        'locale' => app()->getLocale(),
                                        'categorySlug' => $product->category->slug,
                                    ]) }}"
                                    class="catalog-related-link"
                                >
                                    Alle Produkte der Kategorie
                                </a>
                            @endif
                        </div>

                        <div class="catalog-product-grid">
                            @foreach ($relatedProducts as $relatedProduct)
                                @include('catalog.partials.product-card', [
                                    'product' => $relatedProduct,
                                ])
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="catalog-cta">
                    <div class="catalog-cta-inner">
                        <div>
                            <p class="catalog-cta-kicker">
                                Jetzt prüfen
                            </p>

                            <h2>
                                Ist {{ $product->name }} bei dir lieferbar?
                            </h2>

                            <p>
                                Gib deine Postleitzahl ein und finde den Kiosk,
                                der dieses oder ähnliche Produkte an deine
                                Adresse liefert.
                            </p>
                        </div>

                        <a
                            href="{{ route('home') }}#find"
                            class="catalog-cta-button"
                        >
                            Verfügbarkeit prüfen
                        </a>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
