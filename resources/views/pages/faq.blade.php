@extends('layouts.marketing')

@section('title', 'FAQ | Kioskheld')

@section('content')
    <main class="faq-page">
        <section class="faq-hero">
            <div class="container faq-hero-grid">
                <div class="faq-hero-copy">
                    <p class="faq-kicker">Häufige Fragen</p>

                    <h1>
                        Fragen?
                        <span>Kioskheld antwortet.</span>
                    </h1>

                    <p>
                        Alles Wichtige rund um Bestellung, Lieferung, Zahlung und die Zusammenarbeit
                        mit Kioskheld – klar, direkt und ohne Fachchinesisch.
                    </p>

                    <div class="faq-hero-actions" aria-label="FAQ Schnellzugriff">
                        <a href="#kunden" class="faq-btn faq-btn-primary">Für Kunden</a>
                        <a href="#partner" class="faq-btn faq-btn-secondary">Für Partner</a>
                    </div>
                </div>

                <div class="faq-hero-panel" aria-label="Kioskheld FAQ Übersicht">
                    <div class="faq-panel-card faq-panel-card-main">
                        <span>01</span>
                        <strong>Bestellen</strong>
                        <p>PLZ eingeben, Kiosk wählen, Warenkorb prüfen.</p>
                    </div>

                    <div class="faq-panel-card">
                        <span>02</span>
                        <strong>Lieferung</strong>
                        <p>Verfügbare Liefer- und Abholoptionen werden im Checkout angezeigt.</p>
                    </div>

                    <div class="faq-panel-card">
                        <span>03</span>
                        <strong>Partner werden</strong>
                        <p>Kiosk online bringen und digitale Bestellungen starten.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="container">
                <div class="faq-intro">
                    <p class="faq-section-kicker">Schnell erklärt</p>

                    <h2>Die wichtigsten Antworten auf einen Blick.</h2>

                    <p>
                        Die FAQ ist bewusst einfach gehalten. Später können wir daraus problemlos
                        eine ausführlichere Hilfe-Seite mit Support-Themen, Zahlungsdetails und Partner-Onboarding machen.
                    </p>
                </div>

                <div class="faq-layout">
                    <aside class="faq-sidebar" aria-label="FAQ Bereiche">
                        <a href="#kunden">
                            <span>Kunden</span>
                            <strong>Bestellung, Lieferung, Zahlung</strong>
                        </a>

                        <a href="#partner">
                            <span>Partner</span>
                            <strong>Kiosk online bringen</strong>
                        </a>

                        <a href="{{ route('partner.register') }}">
                            <span>Mitmachen</span>
                            <strong>Partner-Anfrage starten</strong>
                        </a>
                    </aside>

                    <div class="faq-content">
                        <section class="faq-group" id="kunden">
                            <div class="faq-group-head">
                                <p>Für Kunden</p>
                                <h2>Bestellen bei Kioskheld</h2>
                            </div>

                            <div class="faq-list">
                                <details class="faq-item" open>
                                    <summary>Wie funktioniert Kioskheld?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Du gibst deine Postleitzahl ein, Kioskheld zeigt dir verfügbare Kioske
                                            in deiner Nähe und du bestellst Snacks, Getränke, Süßes oder typische
                                            Kioskprodukte direkt online.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Ist Kioskheld ein eigener Lieferdienst?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Kioskheld ist die Bestellplattform. Die Lieferung oder Abholung erfolgt
                                            über den jeweiligen Kiosk beziehungsweise über die angebundene Lieferstruktur.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Welche Produkte kann ich bestellen?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Je nach Kiosk findest du Getränke, Snacks, Süßwaren, Chips, Eis,
                                            Haushaltsartikel und weitere typische Kioskprodukte. Das Sortiment kann
                                            je nach Standort unterschiedlich sein.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Warum sehe ich keinen Kiosk in meiner Nähe?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Dann ist für deine Postleitzahl aktuell noch kein teilnehmender Kiosk aktiv.
                                            Kioskheld wird regional ausgebaut. Sobald ein Partner in deiner Umgebung
                                            verfügbar ist, kann er in der Suche erscheinen.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Wie bezahle ich meine Bestellung?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Die verfügbaren Zahlungsarten hängen vom jeweiligen Kiosk ab. Möglich sind
                                            je nach Anbieter Barzahlung, Kartenzahlung oder Online-Zahlung. Im Checkout
                                            werden nur die Zahlungsarten angezeigt, die für den jeweiligen Kiosk verfügbar sind.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Kann ich meine Bestellung abholen?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Wenn der Kiosk Abholung anbietet, wird dir diese Option im Bestellprozess
                                            angezeigt. Falls nur Lieferung möglich ist, wird entsprechend nur Lieferung angeboten.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Was passiert, wenn ein Produkt nicht verfügbar ist?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Der Warenkorb wird vor dem Checkout geprüft. Falls ein Produkt nicht verfügbar
                                            ist oder sich Preise geändert haben, bekommst du einen Hinweis und kannst deine
                                            Bestellung anpassen.
                                        </p>
                                    </div>
                                </details>
                            </div>
                        </section>

                        <section class="faq-group" id="partner">
                            <div class="faq-group-head">
                                <p>Für Partner</p>
                                <h2>Kiosk mit Kioskheld verbinden</h2>
                            </div>

                            <div class="faq-list">
                                <details class="faq-item" open>
                                    <summary>Was bringt Kioskheld meinem Kiosk?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Kioskheld macht deinen Kiosk online sichtbar und ermöglicht digitale Bestellungen,
                                            ohne dass du eine eigene Plattform entwickeln musst. Du kannst neue Kunden erreichen
                                            und dein Sortiment moderner anbieten.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Brauche ich technische Kenntnisse?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Nein. Die Grundstruktur wird vorbereitet. Du brauchst nur deine wichtigsten Daten,
                                            Öffnungszeiten, Lieferoptionen und dein Sortiment. Ziel ist ein möglichst einfacher Start.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Muss ich mein Sortiment selbst pflegen?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Das hängt vom gewählten Setup ab. Grundsätzlich soll die Pflege überschaubar bleiben.
                                            Wichtig ist, dass Preise, Verfügbarkeit und Produkte möglichst aktuell sind.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Kann ich Lieferung und Abholung anbieten?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Ja. Je nach Einrichtung kannst du Lieferung, Abholung oder beides anbieten.
                                            Die verfügbaren Optionen werden später im Bestellprozess angezeigt.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Wie kommen Bestellungen bei mir an?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Bestellungen werden über das angebundene System verarbeitet. Je nach Einrichtung
                                            können sie im Händlerbereich, per Drucker oder über andere angebundene Prozesse eingehen.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Was kostet Kioskheld?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Das Preismodell kann je nach Startphase und Vereinbarung variieren. Ziel ist ein
                                            faires Modell, bei dem Kioske ohne hohe Einstiegshürde online starten können.
                                        </p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>Wie schnell kann mein Kiosk online gehen?</summary>

                                    <div class="faq-answer">
                                        <p>
                                            Wenn Stammdaten, Öffnungszeiten, Lieferoptionen und Sortiment vorliegen, kann ein
                                            einfacher Kiosk relativ schnell vorbereitet werden. Je sauberer die Daten sind,
                                            desto schneller geht der Start.
                                        </p>
                                    </div>
                                </details>
                            </div>
                        </section>
                    </div>
                </div>

                <section class="faq-cta">
                    <div>
                        <p>Für Kioske und Händler</p>
                        <h2>Du willst mit deinem Kiosk online starten?</h2>
                        <span>
                            Schick uns deine Anfrage. Wir prüfen gemeinsam, wie dein Kiosk digital bestellbar wird.
                        </span>
                    </div>

                    <a href="{{ route('partner.register') }}">Partner werden</a>
                </section>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
