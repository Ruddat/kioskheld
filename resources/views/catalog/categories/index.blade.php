@extends('layouts.marketing')

@section('title', 'Kiosk-Kategorien entdecken | Kioskheld')

@section(
    'meta_description',
    'Entdecke Snacks, Getränke, Süßigkeiten und weitere Kiosk-Kategorien. Prüfe anschließend per PLZ, welche Produkte in deiner Nähe verfügbar sind.'
)

@section('canonical')
    <link
        rel="canonical"
        href="{{ route('catalog.categories.index', [
            'locale' => app()->getLocale(),
        ]) }}"
    >
@endsection

@section('content')
    <main class="catalog-categories-index">
        <section class="catalog-categories-index__hero">
            <div class="container">
                <div class="catalog-categories-index__hero-inner">
                    <p class="catalog-categories-index__eyebrow">
                        Kioskheld Sortiment
                    </p>

                    <h1 class="catalog-categories-index__title">
                        Kategorien
                        <span>entdecken.</span>
                    </h1>

                    <p class="catalog-categories-index__intro">
                        Von Snacks und Getränken bis zu Süßigkeiten und kleinen
                        Alltagsrettern. Entdecke das Sortiment teilnehmender
                        Kioske und prüfe anschließend, was an deinem Standort
                        tatsächlich verfügbar ist.
                    </p>
                </div>
            </div>
        </section>

        <section class="catalog-categories-index__content">
            <div class="container">
                <div class="catalog-categories-index__toolbar">
                    <div class="catalog-categories-index__toolbar-copy">
                        <strong>
                            {{ $categories->count() }}

                            {{ $categories->count() === 1
                                ? 'Kategorie'
                                : 'Kategorien' }}
                        </strong>

                        <span>
                            Das konkrete Sortiment hängt vom liefernden Kiosk ab.
                        </span>
                    </div>

                    <a
                        href="{{ route('home') }}#find"
                        class="catalog-categories-index__toolbar-button"
                    >
                        Kiosk per PLZ finden
                    </a>
                </div>

                @if ($categories->isNotEmpty())
                    <div class="catalog-categories-index__grid">
                        @foreach ($categories as $category)
                            <article class="catalog-categories-index__card">
                                <a
                                    class="catalog-categories-index__card-link"
                                    href="{{ route('catalog.categories.show', [
                                        'locale' => app()->getLocale(),
                                        'categorySlug' => $category->slug,
                                    ]) }}"
                                >
                                    <div class="catalog-categories-index__card-media">
                                        @if (filled($category->image_url))
                                            <img
                                                class="catalog-categories-index__card-image"
                                                src="{{ $category->image_url }}"
                                                alt=""
                                                loading="lazy"
                                                decoding="async"
                                                onerror="
                                                    this.hidden = true;
                                                    this.nextElementSibling.hidden = false;
                                                "
                                            >

                                            <span
                                                class="catalog-categories-index__card-placeholder"
                                                hidden
                                                aria-hidden="true"
                                            >
                                                {{ mb_strtoupper(
                                                    mb_substr($category->name, 0, 1)
                                                ) }}
                                            </span>
                                        @else
                                            <span
                                                class="catalog-categories-index__card-placeholder"
                                                aria-hidden="true"
                                            >
                                                {{ mb_strtoupper(
                                                    mb_substr($category->name, 0, 1)
                                                ) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="catalog-categories-index__card-body">
                                        <h2 class="catalog-categories-index__card-title">
                                            {{ $category->name }}
                                        </h2>

                                        @if ($category->description)
                                            <p class="catalog-categories-index__card-description">
                                                {{ \Illuminate\Support\Str::limit(
                                                    $category->description,
                                                    105
                                                ) }}
                                            </p>
                                        @endif

                                        <div class="catalog-categories-index__card-footer">
                                            <span class="catalog-categories-index__card-count">
                                                {{ $category->active_products_count }}

                                                {{ $category->active_products_count === 1
                                                    ? 'Produkt'
                                                    : 'Produkte' }}
                                            </span>

                                            <span
                                                class="catalog-categories-index__card-arrow"
                                                aria-hidden="true"
                                            >
                                                →
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="catalog-categories-index__empty">
                        <h2>Noch keine Kategorien verfügbar</h2>

                        <p>
                            Der Katalog wird gerade aufgebaut. Prüfe über deine
                            Postleitzahl, welche Kioske bereits liefern.
                        </p>
                    </div>
                @endif

                <section class="catalog-categories-index__cta">
                    <div class="catalog-categories-index__cta-inner">
                        <div>
                            <p class="catalog-categories-index__cta-kicker">
                                Sortiment in deiner Nähe
                            </p>

                            <h2>
                                Welche Produkte wirklich verfügbar sind,
                                entscheidet dein Standort.
                            </h2>

                            <p>
                                Gib deine Postleitzahl ein. Danach zeigen wir dir
                                verfügbare Kioske, konkrete Preise und das
                                tatsächlich lieferbare Sortiment.
                            </p>
                        </div>

                        <a
                            href="{{ route('home') }}#find"
                            class="catalog-categories-index__cta-button"
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
