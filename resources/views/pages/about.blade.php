@extends('layouts.marketing')

@section('title', 'Über uns | Kioskheld')

@section('content')
    <main class="subpage">
        <section class="subpage-hero subpage-hero-about">
            <div class="container subpage-hero-grid">
                <div class="subpage-hero-copy">
                    <p class="subpage-kicker">Über Kioskheld</p>

                    <h1>
                        Dein lokaler Kiosk.
                        <span>Digital, schnell, direkt.</span>
                    </h1>

                    <p>
                        Kioskheld macht lokale Kioske, Spätis und Getränkedienste online erreichbar.
                        Kunden geben ihre Postleitzahl ein, finden verfügbare Händler in der Nähe und bestellen
                        genau das, was sonst schnell um die Ecke geholt wird.
                    </p>

                    <div class="subpage-hero-actions">
                        <a href="{{ route('home') }}#find" class="subpage-btn subpage-btn-primary">
                            Kiosk finden
                        </a>

                        <a href="{{ route('home') }}#partner" class="subpage-btn subpage-btn-outline">
                            Partner werden
                        </a>
                    </div>
                </div>

                <div class="subpage-hero-card">
                    <div class="hero-card-badge">Powered by Foodzwerge</div>

                    <h2>Eine Plattform für lokale Sofortbestellungen.</h2>

                    <p>
                        Kioskheld ist die klare Bestelloberfläche nach außen.
                        Foodzwerge liefert die technische Shop-Basis im Hintergrund.
                    </p>

                    <div class="hero-card-stats">
                        <div>
                            <strong>PLZ</strong>
                            <span>eingeben</span>
                        </div>

                        <div>
                            <strong>Kiosk</strong>
                            <span>auswählen</span>
                        </div>

                        <div>
                            <strong>Schnell</strong>
                            <span>liefern lassen</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="subpage-section">
            <div class="container">
                <div class="subpage-intro-card">
                    <p class="subpage-kicker dark">Was wir bauen</p>

                    <h2>Kein Konzern-Blabla. Eine einfache Lösung für echte Händler.</h2>

                    <p>
                        Viele kleine Händler haben gute Produkte, gute Lage und Stammkunden – aber keinen einfachen
                        digitalen Verkaufskanal. Genau hier setzt Kioskheld an. Die Plattform soll verständlich bleiben:
                        Postleitzahl eingeben, Händler finden, Produkte auswählen, bestellen.
                    </p>
                </div>

                <div class="subpage-feature-grid">
                    <article class="subpage-feature-card">
                        <div class="feature-icon">⌖</div>
                        <h3>Lokal gedacht</h3>
                        <p>
                            Kioskheld stellt Händler aus der Umgebung in den Vordergrund. Nicht irgendein Marktplatz,
                            sondern echte Anbieter aus der Region.
                        </p>
                    </article>

                    <article class="subpage-feature-card">
                        <div class="feature-icon">↯</div>
                        <h3>Schnell gemacht</h3>
                        <p>
                            Kiosk-Produkte sind Impulsprodukte. Deshalb muss der Weg kurz sein:
                            suchen, auswählen, bestellen, liefern lassen.
                        </p>
                    </article>

                    <article class="subpage-feature-card">
                        <div class="feature-icon">▣</div>
                        <h3>Einfach betreibbar</h3>
                        <p>
                            Händler sollen nicht erst ein komplexes Shopsystem lernen müssen.
                            Die Technik läuft über Foodzwerge im Hintergrund.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="subpage-section subpage-section-dark">
            <div class="container subpage-split">
                <div>
                    <p class="subpage-kicker">Foodzwerge Verbindung</p>

                    <h2>Kioskheld ist die Bühne. Foodzwerge ist der Motor.</h2>
                </div>

                <div class="subpage-dark-card">
                    <p>
                        Foodzwerge stellt die Shop-Technik bereit: Produkte, Bestellungen, Händlerverwaltung,
                        Lieferlogik und Schnittstellen. Kioskheld nutzt diese Grundlage und macht daraus eine
                        einfache, fokussierte Plattform für Kiosk- und Sofortlieferungen.
                    </p>

                    <p>
                        Für Kunden bleibt es simpel. Für Händler bleibt es handhabbar. Für die Plattform bleibt
                        die technische Basis sauber erweiterbar.
                    </p>
                </div>
            </div>
        </section>

        <section class="subpage-section">
            <div class="container">
                <div class="subpage-card-grid">
                    <article class="subpage-info-card">
                        <span>01</span>
                        <h3>Für Kunden</h3>
                        <p>
                            Keine langen Umwege. Kunden prüfen schnell, ob ein Kioskheld in der Nähe verfügbar ist
                            und bestellen typische Kioskprodukte direkt online.
                        </p>
                    </article>

                    <article class="subpage-info-card">
                        <span>02</span>
                        <h3>Für Händler</h3>
                        <p>
                            Händler bekommen einen zusätzlichen Verkaufskanal, ohne selbst eine große Plattform
                            entwickeln oder betreiben zu müssen.
                        </p>
                    </article>

                    <article class="subpage-info-card">
                        <span>03</span>
                        <h3>Für die Region</h3>
                        <p>
                            Lokale Bestellungen bleiben lokaler. Kleine Anbieter werden sichtbarer und können
                            digital gegen große Plattformen bestehen.
                        </p>
                    </article>
                </div>

                <div class="subpage-final-cta">
                    <div>
                        <p class="subpage-kicker dark">Bereit?</p>
                        <h2>Finde deinen Kioskheld oder werde selbst Partner.</h2>
                    </div>

                    <div class="subpage-hero-actions">
                        <a href="{{ route('home') }}#find" class="subpage-btn subpage-btn-primary">
                            Jetzt suchen
                        </a>

                        <a href="{{ route('home') }}#partner" class="subpage-btn subpage-btn-dark">
                            Partner werden
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />

@endsection
