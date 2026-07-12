@extends('layouts.marketing')

@php
    $categoryName = html_entity_decode(
        $category->name,
        ENT_QUOTES | ENT_HTML5,
        'UTF-8',
    );

    $canonicalUrl = $products->currentPage() === 1
        ? route('catalog.categories.show', [
            'locale' => app()->getLocale(),
            'categorySlug' => $category->slug,
        ])
        : $products->url($products->currentPage());
@endphp

@section(
    'title',
    $categoryName . ' vom Kiosk liefern lassen | Kioskheld'
)

@section(
    'meta_description',
    \Illuminate\Support\Str::limit(
        'Entdecke '
            . $categoryName
            . ' bei Kioskheld und prüfe mit deiner PLZ, welche Produkte Kioske in deiner Nähe liefern.',
        155,
    )
)

@section(
    'canonical_url',
    $canonicalUrl
)


@section('content')
    <main class="catalog-category-show">
        <section class="catalog-category-show__hero">
            <div class="container">
                <nav
                    class="catalog-category-show__breadcrumb"
                    aria-label="Breadcrumb"
                >
                    <a
                        href="{{ route('catalog.categories.index', [
                            'locale' => app()->getLocale(),
                        ]) }}"
                    >
                        Kategorien
                    </a>

                    <span aria-hidden="true">›</span>

                    <span>{{ $category->name }}</span>
                </nav>

                <div class="catalog-category-show__hero-grid">
                    <div class="catalog-category-show__copy">
                        <p class="catalog-category-show__eyebrow">
                            Kategorie
                        </p>

                        <h1 class="catalog-category-show__title">
                            {{ $category->name }}
                        </h1>

                        @if ($category->description)
                            <p class="catalog-category-show__description">
                                {{ $category->description }}
                            </p>
                        @else
                            <p class="catalog-category-show__description">
                                Entdecke Produkte aus der Kategorie
                                {{ $category->name }} bei teilnehmenden Kiosken.
                            </p>
                        @endif

                        <div class="catalog-category-show__meta">
                            <span>
                                {{ $products->total() }}

                                {{ $products->total() === 1
                                    ? 'Produkt'
                                    : 'Produkte' }}
                            </span>

                            <span>
                                Preise und Verfügbarkeit standortabhängig
                            </span>
                        </div>

                        <div class="catalog-category-show__actions">
                            <a
                                href="{{ route('home') }}#find"
                                class="catalog-category-show__button catalog-category-show__button--primary"
                            >
                                Verfügbarkeit per PLZ prüfen
                            </a>

                            <a
                                href="{{ route('catalog.categories.index', [
                                    'locale' => app()->getLocale(),
                                ]) }}"
                                class="catalog-category-show__button catalog-category-show__button--secondary"
                            >
                                Alle Kategorien
                            </a>
                        </div>
                    </div>

                    <div class="catalog-category-show__visual">
                        @if (filled($category->image_url))
                            <img
                                class="catalog-category-show__image"
                                src="{{ $category->image_url }}"
                                alt=""
                                loading="eager"
                                decoding="async"
                                onerror="
                                    this.hidden = true;
                                    this.nextElementSibling.hidden = false;
                                "
                            >

                            <div
                                class="catalog-category-show__placeholder"
                                hidden
                                aria-hidden="true"
                            >
                                {{ mb_strtoupper(
                                    mb_substr($category->name, 0, 1)
                                ) }}
                            </div>
                        @else
                            <div
                                class="catalog-category-show__placeholder"
                                aria-hidden="true"
                            >
                                {{ mb_strtoupper(
                                    mb_substr($category->name, 0, 1)
                                ) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="catalog-category-show__products">
            <div class="container">
                <div class="catalog-category-show__section-heading">
                    <div>
                        <p class="catalog-category-show__section-kicker">
                            Sortiment
                        </p>

                        <h2>
                            Produkte aus {{ $category->name }}
                        </h2>
                    </div>

                    <p>
                        Der angezeigte Preis ist ein unverbindlicher
                        Mindestpreis. Das konkrete Angebot hängt vom
                        ausgewählten Kiosk ab.
                    </p>
                </div>

                @if ($products->isNotEmpty())
                    <div class="catalog-category-show__product-grid">
                        @foreach ($products as $product)
                            @include('catalog.partials.product-card', [
                                'product' => $product,
                            ])
                        @endforeach
                    </div>

                    <div class="catalog-category-show__pagination">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="catalog-category-show__empty">
                        <h2>Aktuell keine Produkte vorhanden</h2>

                        <p>
                            In dieser Kategorie sind derzeit keine aktiven
                            Produkte verfügbar.
                        </p>
                    </div>
                @endif

                <section class="catalog-category-show__cta">
                    <div class="catalog-category-show__cta-inner">
                        <div>
                            <p class="catalog-category-show__cta-kicker">
                                Jetzt konkret prüfen
                            </p>

                            <h2>
                                Welcher Kiosk liefert {{ $category->name }}
                                an deine Adresse?
                            </h2>

                            <p>
                                Gib deine Postleitzahl ein und wir zeigen dir
                                verfügbare Kioske, konkrete Preise und das
                                tatsächlich lieferbare Sortiment.
                            </p>
                        </div>

                        <a
                            href="{{ route('home') }}#find"
                            class="catalog-category-show__cta-button"
                        >
                            Kiosk finden
                        </a>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
