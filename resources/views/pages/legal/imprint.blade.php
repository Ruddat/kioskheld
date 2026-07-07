@extends('layouts.marketing')

@section('title', 'Impressum | Kioskheld')

@section('content')
    <main class="subpage legal-page">
        <section class="subpage-hero subpage-hero-compact">
            <div class="container subpage-hero-copy subpage-hero-copy-narrow">
                <p class="subpage-kicker">Rechtliches</p>

                <h1>Impressum</h1>

                <p>
                    Angaben gemäß den gesetzlichen Informationspflichten.
                    Die Platzhalter bitte vor Veröffentlichung vollständig ersetzen.
                </p>
            </div>
        </section>

        <section class="subpage-section">
            <div class="container legal-container">
                <div class="legal-card">
                    <div class="legal-block">
                        <h2>Angaben gemäß § 5 TMG</h2>

                        <p>
                            <strong>[Firmenname / Betreiber]</strong><br>
                            [Straße und Hausnummer]<br>
                            [PLZ und Ort]<br>
                            [Land]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Vertreten durch</h2>

                        <p>
                            [Name des vertretungsberechtigten Geschäftsführers / Inhabers]
                        </p>
                    </div>

                    <div class="legal-grid">
                        <div class="legal-mini-card">
                            <span>Telefon</span>
                            <strong>[Telefonnummer]</strong>
                        </div>

                        <div class="legal-mini-card">
                            <span>E-Mail</span>
                            <strong>
                                <a href="mailto:[E-Mail-Adresse]">[E-Mail-Adresse]</a>
                            </strong>
                        </div>
                    </div>

                    <div class="legal-block">
                        <h2>Registereintrag</h2>

                        <p>
                            Registergericht: [Registergericht, falls vorhanden]<br>
                            Registernummer: [Registernummer, falls vorhanden]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Umsatzsteuer-ID</h2>

                        <p>
                            Umsatzsteuer-Identifikationsnummer gemäß § 27a Umsatzsteuergesetz:<br>
                            [USt-ID, falls vorhanden]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Wirtschafts-Identifikationsnummer</h2>

                        <p>
                            Wirtschafts-Identifikationsnummer gemäß § 139c Abgabenordnung:<br>
                            [Wirtschafts-ID, falls vorhanden]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV</h2>

                        <p>
                            [Name der verantwortlichen Person]<br>
                            [Straße und Hausnummer]<br>
                            [PLZ und Ort]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>EU-Streitschlichtung</h2>

                        <p>
                            Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung bereit:
                            <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer">
                                https://ec.europa.eu/consumers/odr/
                            </a>
                        </p>

                        <p>
                            Unsere E-Mail-Adresse findest du oben im Impressum.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Verbraucherstreitbeilegung / Universalschlichtungsstelle</h2>

                        <p>
                            Wir sind nicht verpflichtet und nicht bereit, an Streitbeilegungsverfahren vor einer
                            Verbraucherschlichtungsstelle teilzunehmen.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Haftung für Inhalte</h2>

                        <p>
                            Als Diensteanbieter sind wir gemäß den allgemeinen Gesetzen für eigene Inhalte auf diesen Seiten
                            verantwortlich. Eine Verpflichtung zur Überwachung übermittelter oder gespeicherter fremder
                            Informationen besteht jedoch nur im Rahmen der gesetzlichen Vorgaben.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Haftung für Links</h2>

                        <p>
                            Unser Angebot kann Links zu externen Websites Dritter enthalten, auf deren Inhalte wir keinen
                            Einfluss haben. Für diese fremden Inhalte übernehmen wir keine Gewähr. Für die Inhalte der
                            verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>Urheberrecht</h2>

                        <p>
                            Die durch den Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem
                            deutschen Urheberrecht. Beiträge Dritter werden als solche gekennzeichnet. Die Vervielfältigung,
                            Bearbeitung, Verbreitung oder sonstige Verwertung außerhalb der Grenzen des Urheberrechts bedarf
                            der jeweiligen schriftlichen Zustimmung.
                        </p>
                    </div>

                    <div class="legal-note">
                        <strong>Hinweis:</strong>
                        Diese Vorlage enthält Platzhalter und muss vor Veröffentlichung mit den tatsächlichen Betreiberangaben
                        ergänzt und rechtlich geprüft werden.
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <x-marketing.footer />

@endsection
