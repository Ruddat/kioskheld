@extends('layouts.marketing')

@section('title', 'AGB für Kioskheld-Partner')

@section('content')
    <main class="legal-terms-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="terms-hero">
            <div class="container terms-hero-grid">
                <div>
                    <p class="eyebrow">Kioskheld Partnerbedingungen</p>

                    <h1>
                        Allgemeine Geschäftsbedingungen
                        <span>für Kioskheld-Partner.</span>
                    </h1>

                    <p class="lead">
                        Diese Bedingungen regeln die Zusammenarbeit zwischen Kioskheld und teilnehmenden
                        Kiosken, Spätis, Getränkemärkten und vergleichbaren lokalen Anbietern.
                    </p>
                </div>

                <aside class="terms-summary-card">
                    <span>Partner-Modell</span>
                    <strong>Einfach starten. Fair abrechnen.</strong>

                    <ul>
                        <li>3 % Servicegebühr auf vermittelte Bestellungen</li>
                        <li>wöchentliche Abrechnung</li>
                        <li>wöchentliche Auszahlung vorhandener Guthaben</li>
                        <li>jederzeit kündbar</li>
                        <li>Sortiment und Mengen im Kundencenter pflegbar</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="terms-content-section">
            <div class="container terms-layout">
                <aside class="terms-toc" aria-label="Inhaltsverzeichnis">
                    <strong>Inhalt</strong>

                    <nav>
                        <a href="#scope">1. Geltungsbereich</a>
                        <a href="#platform">2. Plattformleistung</a>
                        <a href="#partner">3. Partnerkonto</a>
                        <a href="#products">4. Sortiment & Verfügbarkeit</a>
                        <a href="#orders">5. Bestellungen</a>
                        <a href="#delivery">6. Lieferung & Abholung</a>
                        <a href="#fees">7. Gebühren</a>
                        <a href="#settlement">8. Abrechnung</a>
                        <a href="#content">9. Inhalte</a>
                        <a href="#availability">10. Verfügbarkeit</a>
                        <a href="#term">11. Laufzeit & Kündigung</a>
                        <a href="#final">12. Schlussbestimmungen</a>
                    </nav>
                </aside>

                <article class="terms-card">
                    <div class="terms-meta">
                        <span>Stand: {{ now()->format('d.m.Y') }}</span>
                        <span>Kioskheld Partner-AGB</span>
                    </div>

                    <section id="scope">
                        <h2>1. Geltungsbereich</h2>

                        <p>
                            Diese Allgemeinen Geschäftsbedingungen gelten für die Nutzung von Kioskheld durch
                            gewerbliche Partner, insbesondere Kioske, Spätis, Getränkemärkte und vergleichbare
                            lokale Anbieter.
                        </p>

                        <p>
                            Kioskheld stellt eine digitale Plattform bereit, über die Partner ihr Angebot regional
                            sichtbar machen und Bestellungen von Kunden erhalten können. Abweichende Vereinbarungen
                            gelten nur, wenn sie ausdrücklich bestätigt wurden.
                        </p>
                    </section>

                    <section id="platform">
                        <h2>2. Leistungen von Kioskheld</h2>

                        <p>
                            Kioskheld unterstützt Partner bei der digitalen Darstellung ihres Betriebs, der
                            Präsentation von Artikeln, der Annahme vermittelter Bestellungen und der strukturierten
                            Weitergabe relevanter Bestelldaten.
                        </p>

                        <p>
                            Die konkrete technische Umsetzung kann über Kioskheld, Foodzwerge, JustDeliver oder
                            angebundene Systeme erfolgen. Kioskheld kann Funktionen erweitern, anpassen oder
                            verbessern, sofern der Kern der Partnerleistung erhalten bleibt.
                        </p>

                        <div class="terms-highlight">
                            <strong>Kernidee:</strong>
                            Der Partner bleibt Betreiber seines Angebots. Kioskheld stellt die digitale Brücke
                            zwischen regionaler Nachfrage und lokalem Kiosk-Angebot bereit.
                        </div>
                    </section>

                    <section id="partner">
                        <h2>3. Registrierung, Partnerkonto und Kundencenter</h2>

                        <p>
                            Für die Teilnahme kann ein Partnerkonto oder ein Zugang zum Kundencenter eingerichtet
                            werden. Der Partner ist verpflichtet, seine Angaben vollständig und korrekt zu machen
                            und Änderungen zeitnah zu pflegen.
                        </p>

                        <p>
                            Der Zugang darf nur von berechtigten Personen genutzt werden. Zugangsdaten, Magic Links
                            oder sonstige Login-Verfahren sind vertraulich zu behandeln.
                        </p>
                    </section>

                    <section id="products">
                        <h2>4. Artikel, Sortiment, Preise und Verfügbarkeit</h2>

                        <p>
                            Partner können grundsätzlich beliebige zulässige Artikel einstellen, sofern diese zum
                            jeweiligen Geschäftsmodell passen und rechtlich verkauft werden dürfen. Dazu können
                            insbesondere Getränke, Snacks, Süßwaren, Eis, Bundles, Aktionsartikel und sonstige
                            kiosktypische Waren gehören.
                        </p>

                        <p>
                            Der Partner ist für die Richtigkeit von Artikelangaben, Preisen, Bildern, Beschreibungen,
                            Allergenen, Zusatzstoffen, Mengen, Verfügbarkeiten und rechtlichen Verkaufsvoraussetzungen
                            verantwortlich.
                        </p>

                        <p>
                            Im Kundencenter können Produkte, Preise, Live-Mengen, Verfügbarkeiten und sonstige
                            Angebotsdaten gepflegt werden. Artikel können jederzeit aktiviert, deaktiviert oder
                            angepasst werden, sofern keine laufende Bestellung betroffen ist.
                        </p>
                    </section>

                    <section id="orders">
                        <h2>5. Bestellungen und Annahme</h2>

                        <p>
                            Über Kioskheld vermittelte Bestellungen werden an den Partner übermittelt. Der Partner
                            ist dafür verantwortlich, eingehende Bestellungen rechtzeitig zu prüfen, anzunehmen,
                            vorzubereiten und ordnungsgemäß auszuführen.
                        </p>

                        <p>
                            Kommt eine Bestellung aufgrund fehlender Ware, falscher Angaben, fehlender Erreichbarkeit
                            oder sonstiger Umstände nicht zustande, hat der Partner Kioskheld und den Kunden soweit
                            erforderlich unverzüglich zu informieren.
                        </p>
                    </section>

                    <section id="delivery">
                        <h2>6. Lieferung, Abholung, Liefergebiet und Mindestbestellwert</h2>

                        <p>
                            Der Partner kann festlegen, ob Lieferung, Abholung oder beide Optionen angeboten werden.
                            Liefergebiete können insbesondere über Postleitzahlen, Lieferzonen oder vergleichbare
                            regionale Regeln definiert werden.
                        </p>

                        <p>
                            Mindestbestellwerte, Lieferkosten, kostenlose Lieferung ab bestimmten Warenkörben und
                            sonstige Lieferbedingungen können im Kundencenter oder im Rahmen der Einrichtung
                            hinterlegt werden.
                        </p>

                        <p>
                            Der Partner ist verantwortlich für die tatsächliche Durchführung der Lieferung, soweit
                            keine ausdrücklich abweichende Vereinbarung getroffen wurde.
                        </p>
                    </section>

                    <section id="fees">
                        <h2>7. Servicegebühr und Zahlungsarten</h2>

                        <p>
                            Für über Kioskheld vermittelte Bestellungen fällt eine Servicegebühr von
                            <strong>3 % des vermittelten Bestellwerts</strong> an, sofern nichts Abweichendes
                            vereinbart wurde.
                        </p>

                        <p>
                            Unterstützte Zahlungsarten können je nach technischer Anbindung und Partnerkonfiguration
                            variieren. Der Partner kann angeben, ob Barzahlung, Kartenzahlung oder andere Zahlungsarten
                            angeboten werden sollen.
                        </p>

                        <p>
                            Gebühren, Zahlungsbedingungen oder besondere Schwellenwerte für Zahlungsarten werden
                            nur berücksichtigt, soweit sie technisch unterstützt, korrekt hinterlegt und rechtlich
                            zulässig sind.
                        </p>
                    </section>

                    <section id="settlement">
                        <h2>8. Wöchentliche Abrechnung und Auszahlung</h2>

                        <p>
                            Die Abrechnung erfolgt grundsätzlich wöchentlich. In der Abrechnung werden vermittelte
                            Bestellungen, Gebühren, Korrekturen, Stornos und sonstige abrechnungsrelevante Positionen
                            berücksichtigt.
                        </p>

                        <p>
                            Etwaige auszuzahlende Guthaben werden grundsätzlich wöchentlich ausgezahlt, sofern die
                            erforderlichen Zahlungsdaten vollständig vorliegen und keine offenen Prüfungen,
                            Rückfragen oder technischen Hindernisse bestehen.
                        </p>

                        <p>
                            Kioskheld kann Auszahlungen vorübergehend zurückhalten oder korrigieren, wenn Bestellungen
                            storniert wurden, offensichtliche Fehler vorliegen, Missbrauchsverdacht besteht oder
                            abrechnungsrelevante Informationen fehlen.
                        </p>
                    </section>

                    <section id="content">
                        <h2>9. Inhalte, Rechte und unzulässige Artikel</h2>

                        <p>
                            Der Partner stellt sicher, dass alle von ihm übermittelten Inhalte, Bilder, Texte,
                            Markenangaben, Produktinformationen und sonstigen Daten rechtmäßig verwendet werden dürfen.
                        </p>

                        <p>
                            Kioskheld kann Artikel oder Inhalte ablehnen, ausblenden oder entfernen, wenn diese
                            gegen gesetzliche Vorgaben, Rechte Dritter, Plattformregeln oder berechtigte Interessen
                            von Kioskheld verstoßen.
                        </p>

                        <p>
                            Altersbeschränkte oder regulierte Artikel dürfen nur angeboten werden, wenn die jeweiligen
                            gesetzlichen Voraussetzungen erfüllt und technisch sinnvoll abbildbar sind.
                        </p>
                    </section>

                    <section id="availability">
                        <h2>10. Technische Verfügbarkeit und Weiterentwicklung</h2>

                        <p>
                            Kioskheld bemüht sich um einen stabilen Betrieb der Plattform. Eine jederzeitige,
                            unterbrechungsfreie Verfügbarkeit kann jedoch nicht garantiert werden.
                        </p>

                        <p>
                            Wartungen, Updates, Sicherheitsmaßnahmen, technische Störungen oder Anpassungen an
                            angeschlossenen Systemen können vorübergehend zu Einschränkungen führen.
                        </p>

                        <p>
                            Kioskheld ist berechtigt, Funktionen weiterzuentwickeln, zu optimieren oder zu ersetzen,
                            wenn dies der Sicherheit, Stabilität, Nutzerfreundlichkeit oder wirtschaftlichen
                            Weiterentwicklung der Plattform dient.
                        </p>
                    </section>

                    <section id="term">
                        <h2>11. Laufzeit, Pausierung und Kündigung</h2>

                        <p>
                            Die Zusammenarbeit ist grundsätzlich jederzeit kündbar, sofern keine abweichende
                            Vereinbarung getroffen wurde. Eine Kündigung kann in Textform erfolgen.
                        </p>

                        <p>
                            Der Partner kann seinen Betrieb pausieren, einzelne Artikel deaktivieren oder seine
                            Verfügbarkeit anpassen. Bereits angenommene oder laufende Bestellungen bleiben davon
                            unberührt.
                        </p>

                        <p>
                            Kioskheld kann die Zusammenarbeit aus wichtigem Grund beenden oder den Partnerzugang
                            vorübergehend sperren, insbesondere bei Missbrauch, wiederholten Pflichtverletzungen,
                            falschen Angaben, Zahlungsproblemen oder rechtlichen Risiken.
                        </p>
                    </section>

                    <section id="final">
                        <h2>12. Schlussbestimmungen</h2>

                        <p>
                            Es gilt deutsches Recht. Vertragssprache ist Deutsch. Sollten einzelne Bestimmungen
                            unwirksam sein oder werden, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt.
                        </p>

                        <p>
                            Kioskheld kann diese Bedingungen anpassen, wenn dies aufgrund technischer,
                            wirtschaftlicher oder rechtlicher Entwicklungen erforderlich ist. Partner werden über
                            wesentliche Änderungen in geeigneter Weise informiert.
                        </p>

                        <div class="terms-final-note">
                            <strong>Hinweis:</strong>
                            Diese Seite bildet die Grundlage der Kioskheld-Partnerbedingungen. Individuelle
                            Vereinbarungen, konkrete Freischaltungen und technische Einstellungen können ergänzend
                            im Kundencenter oder im direkten Austausch festgelegt werden.
                        </div>
                    </section>
                </article>
            </div>
        </section>

        <x-marketing.footer />
    </main>
@endsection
