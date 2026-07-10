@extends('layouts.marketing')

@section('title', 'Über uns | Kioskheld')

@section('content')
    <main class="about-page">
        <section class="about-hero">
            <div class="container about-hero-grid">
                <div class="about-hero-copy">
                    <p class="about-kicker">Über Kioskheld</p>

                    <h1>
                        Der Kiosk um die Ecke.
                        <span>Jetzt auch online.</span>
                    </h1>

                    <p>
                        Kioskheld bringt lokale Kioske, Spätis und Getränkeläden dahin, wo Kunden heute bestellen:
                        direkt aufs Handy. Ohne Konzern-Blabla. Ohne komplizierten Shop. Schnell, lokal und klar.
                    </p>

                    <div class="about-hero-actions">
                        <a href="{{ route('home') }}#find" class="about-btn about-btn-primary">
                            Kiosk finden
                        </a>

                        <a href="{{ route('partner.index') }}" class="about-btn about-btn-outline">
                            Partner werden
                        </a>
                    </div>
                </div>

                <div class="about-hero-card">
                    <div class="about-hero-badge">Powered by Foodzwerge</div>

                    <h2>Lokale Sofortbestellungen brauchen keine komplizierte Plattform.</h2>

                    <p>
                        Kioskheld ist die einfache Oberfläche für Kunden. Foodzwerge liefert die Technik im Hintergrund:
                        Produkte, Warenkorb, Bestellungen, Händlerlogik und Schnittstellen.
                    </p>

                    <div class="about-hero-steps">
                        <div>
                            <strong>01</strong>
                            <span>PLZ eingeben</span>
                        </div>

                        <div>
                            <strong>02</strong>
                            <span>Kiosk wählen</span>
                        </div>

                        <div>
                            <strong>03</strong>
                            <span>Bestellen</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section about-pullup-section">
            <div class="container">
                <div class="about-statement">
                    <p class="about-kicker about-kicker-dark">Warum Kioskheld?</p>

                    <h2>
                        Weil kleine Händler digital sichtbar sein müssen,
                        ohne sich von großen Plattformen abhängig zu machen.
                    </h2>

                    <p>
                        Viele Kioske haben gute Lage, Stammkunden und Produkte, die Menschen sofort brauchen:
                        Getränke, Snacks, Süßes, Eis, Tabakwaren, Haushaltsartikel oder kleine Alltagsretter.
                        Aber online sind sie oft kaum auffindbar. Genau diese Lücke schließt Kioskheld.
                    </p>
                </div>

                <div class="about-proof-grid">
                    <article class="about-proof-card">
                        <span>Für Kunden</span>
                        <strong>Schneller bestellen</strong>
                        <p>
                            Postleitzahl eingeben, verfügbaren Kiosk finden und typische Kioskprodukte direkt online bestellen.
                        </p>
                    </article>

                    <article class="about-proof-card">
                        <span>Für Händler</span>
                        <strong>Mehr Sichtbarkeit</strong>
                        <p>
                            Ein digitaler Verkaufskanal, ohne selbst eine große Plattform entwickeln oder betreiben zu müssen.
                        </p>
                    </article>

                    <article class="about-proof-card">
                        <span>Für die Region</span>
                        <strong>Lokal bleibt lokal</strong>
                        <p>
                            Bestellungen landen bei echten Anbietern aus der Umgebung statt in anonymen Konzernstrukturen.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-section about-dark-section">
            <div class="container about-split">
                <div class="about-split-copy">
                    <p class="about-kicker">Unsere Haltung</p>

                    <h2>
                        Wir bauen keinen Marktplatz für alles.
                        Wir bauen eine klare Lösung für Kioske.
                    </h2>

                    <p>
                        Kioskprodukte sind Impulskäufe. Niemand will sich durch eine komplizierte Plattform klicken,
                        wenn er schnell Getränke, Chips, Süßes oder Eis braucht. Deshalb ist Kioskheld bewusst fokussiert:
                        finden, auswählen, bestellen.
                    </p>
                </div>

                <div class="about-dark-card">
                    <div class="about-dark-card-line">
                        <span>Keine App-Pflicht</span>
                        <strong>Direkt im Browser nutzbar.</strong>
                    </div>

                    <div class="about-dark-card-line">
                        <span>Kein Technik-Chaos</span>
                        <strong>Foodzwerge arbeitet im Hintergrund.</strong>
                    </div>

                    <div class="about-dark-card-line">
                        <span>Kein unnötiger Ballast</span>
                        <strong>Fokus auf Bestellungen, Sortiment und Region.</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="about-section-head">
                    <p class="about-kicker about-kicker-dark">So denken wir</p>

                    <h2>Einfach genug für Kunden. Stark genug für Händler.</h2>
                </div>

                <div class="about-feature-grid">
                    <article class="about-feature-card">
                        <div class="about-feature-icon">⌖</div>
                        <h3>Lokal zuerst</h3>
                        <p>
                            Kioskheld stellt Anbieter aus der Umgebung in den Vordergrund. Nicht irgendeinen Händler,
                            sondern den Kiosk, der wirklich erreichbar ist.
                        </p>
                    </article>

                    <article class="about-feature-card">
                        <div class="about-feature-icon">↯</div>
                        <h3>Schnell statt kompliziert</h3>
                        <p>
                            Der Weg zur Bestellung muss kurz bleiben. Gerade bei Snacks, Getränken und Sofortbedarf zählt Tempo.
                        </p>
                    </article>

                    <article class="about-feature-card">
                        <div class="about-feature-icon">▣</div>
                        <h3>Technisch sauber</h3>
                        <p>
                            Kioskheld nutzt Foodzwerge als technische Basis. Dadurch bleibt die Plattform erweiterbar,
                            ohne die Oberfläche unnötig aufzublähen.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-section about-engine-section">
            <div class="container about-engine">
                <div>
                    <p class="about-kicker">Foodzwerge Verbindung</p>

                    <h2>Kioskheld ist die Bühne. Foodzwerge ist der Motor.</h2>
                </div>

                <div class="about-engine-card">
                    <p>
                        Im Vordergrund sieht der Kunde Kioskheld: eine einfache Plattform für lokale Kioskbestellungen.
                        Im Hintergrund arbeitet Foodzwerge: Shop-Technik, Produkte, Bestellungen, Lieferlogik,
                        Händlerverwaltung und Schnittstellen.
                    </p>

                    <p>
                        Das Ergebnis: Kunden bekommen eine einfache Oberfläche. Händler bekommen ein handhabbares System.
                        Und die technische Basis bleibt stabil erweiterbar.
                    </p>
                </div>
            </div>
        </section>

        <section class="about-section about-final-section">
            <div class="container">
                <div class="about-final-cta">
                    <div>
                        <p class="about-kicker about-kicker-dark">Bereit?</p>

                        <h2>
                            Finde deinen Kioskheld.
                            Oder werde selbst einer.
                        </h2>

                        <p>
                            Kioskheld startet regional und wächst mit den Händlern, die verstanden haben:
                            Online bestellen ist längst nicht mehr nur etwas für Restaurants.
                        </p>
                    </div>

                    <div class="about-final-actions">
                        <a href="{{ route('home') }}#find" class="about-btn about-btn-primary">
                            Jetzt Kiosk suchen
                        </a>

                        <a href="{{ route('partner.register') }}" class="about-btn about-btn-dark">
                            Als Partner starten
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
