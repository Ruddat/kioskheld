@extends('layouts.marketing')

@section('title', 'Produkte vom Kiosk liefern lassen | Kioskheld')

@section(
    'meta_description',
    'Entdecke Produkte bei Kioskheld und prüfe mit deiner PLZ, welche Kioske in deiner Nähe liefern.'
)

@section('canonical')
    <link
        rel="canonical"
        href="{{ $products->url($products->currentPage()) }}"
    >
@endsection



@section('content')
    <main class="catalog-page">
        <section class="catalog-hero">
            <div class="container">
                <div class="catalog-hero-inner">
                    <p class="catalog-eyebrow">
                        Kioskheld Sortiment
                    </p>

                    <h1>
                        Produkte
                        <span>entdecken.</span>
                    </h1>

                    <p class="catalog-hero-copy">
                        Snacks, Getränke, Süßigkeiten und weitere Produkte aus
                        teilnehmenden Kiosken. Die konkrete Verfügbarkeit und
                        der tatsächliche Preis hängen von deinem Standort ab.
                    </p>
                </div>
            </div>
        </section>

        <section class="catalog-content">
            <div class="container">
                <div class="catalog-toolbar">
                    <div class="catalog-toolbar-copy">
                        <strong>
                            {{ $products->total() }}
                            {{ $products->total() === 1 ? 'Produkt' : 'Produkte' }}
                        </strong>

                        <span>
                            Allgemeiner Katalog ohne verbindliche Lieferzusage.
                        </span>
                    </div>

                    <div class="catalog-toolbar-actions">
                        <a
                            href="{{ route('catalog.categories.index', [
                                'locale' => app()->getLocale(),
                            ]) }}"
                            class="catalog-toolbar-link catalog-toolbar-link-secondary"
                        >
                            Kategorien ansehen
                        </a>

                        <a
                            href="{{ route('home') }}#find"
                            class="catalog-toolbar-link"
                        >
                            Kiosk per PLZ finden
                        </a>
                    </div>
                </div>

                @if ($products->isNotEmpty())
                    <div class="catalog-product-grid">
                        @foreach ($products as $product)
                            @include('catalog.partials.product-card', [
                                'product' => $product,
                            ])
                        @endforeach
                    </div>

                    <div class="catalog-pagination">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="catalog-empty">
                        <h2>Noch keine Produkte verfügbar</h2>

                        <p>
                            Der Katalog wird gerade aufgebaut. Prüfe über deine
                            Postleitzahl, welche Kioske bereits liefern.
                        </p>
                    </div>
                @endif

                <section class="catalog-cta">
                    <div class="catalog-cta-inner">
                        <div>
                            <p class="catalog-cta-kicker">
                                Sortiment in deiner Nähe
                            </p>

                            <h2>
                                Finde heraus, welche Produkte dein Kiosk liefert.
                            </h2>

                            <p>
                                Gib deine Postleitzahl ein und wir zeigen dir
                                verfügbare Kioske, konkrete Preise und das
                                tatsächlich lieferbare Sortiment.
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
