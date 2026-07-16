@extends('layouts.marketing')

@section('title', 'Datenschutz | Kioskheld')

@section('content')
    <main class="subpage legal-page privacy-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="subpage-hero subpage-hero-compact">
            <div class="container subpage-hero-copy subpage-hero-copy-narrow">
                <p class="subpage-kicker">Datenschutz</p>

                <h1>Datenschutzerklärung</h1>

                <p>
                    Informationen darüber, welche personenbezogenen Daten beim Besuch und bei der Nutzung von Kioskheld
                    verarbeitet werden können.
                </p>
            </div>
        </section>

        <section class="subpage-section">
            <div class="container legal-container">
                <div class="legal-card">
                    <div class="legal-note legal-note-warning">
                        <strong>Wichtiger Hinweis:</strong>
                        Diese Datenschutzerklärung ist eine Grundstruktur mit Platzhaltern. Sie ersetzt keine rechtliche
                        Prüfung. Vor Veröffentlichung müssen Anbieter, Zahlungsarten, Cookies, Tracking und tatsächliche
                        technische Abläufe final geprüft und ergänzt werden.
                    </div>

                    <div class="privacy-toc">
                        <a href="#verantwortlicher">Verantwortlicher</a>
                        <a href="#hosting">Hosting</a>
                        <a href="#logs">Server-Logs</a>
                        <a href="#kontakt">Kontakt</a>
                        <a href="#bestellungen">Bestellungen</a>
                        <a href="#zahlungen">Zahlungsanbieter</a>
                        <a href="#cookies">Cookies</a>
                        <a href="#rechte">Deine Rechte</a>
                    </div>

                    <div class="legal-block" id="verantwortlicher">
                        <h2>1. Verantwortlicher</h2>

                        <p>
                            Verantwortlich für die Datenverarbeitung auf dieser Website ist:
                        </p>

                        <p>
                            <strong>[Firmenname / Betreiber]</strong><br>
                            [Straße und Hausnummer]<br>
                            [PLZ und Ort]<br>
                            [Land]
                        </p>

                        <p>
                            Telefon: [Telefonnummer]<br>
                            E-Mail: <a href="mailto:[E-Mail-Adresse]">[E-Mail-Adresse]</a>
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>2. Allgemeine Hinweise zur Datenverarbeitung</h2>

                        <p>
                            Wir verarbeiten personenbezogene Daten nur, soweit dies zur Bereitstellung dieser Website,
                            zur Bearbeitung von Anfragen, zur Durchführung von Bestellungen oder aufgrund gesetzlicher
                            Pflichten erforderlich ist.
                        </p>

                        <p>
                            Personenbezogene Daten sind alle Informationen, mit denen eine natürliche Person identifiziert
                            werden kann oder identifizierbar ist. Dazu gehören zum Beispiel Name, Anschrift, E-Mail-Adresse,
                            Telefonnummer, IP-Adresse oder Bestelldaten.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>3. Rechtsgrundlagen der Verarbeitung</h2>

                        <p>
                            Die Verarbeitung personenbezogener Daten erfolgt je nach Vorgang auf Grundlage der Datenschutz-
                            Grundverordnung, insbesondere:
                        </p>

                        <ul>
                            <li>Art. 6 Abs. 1 lit. a DSGVO bei Einwilligung,</li>
                            <li>Art. 6 Abs. 1 lit. b DSGVO zur Erfüllung eines Vertrags oder vorvertraglicher Maßnahmen,</li>
                            <li>Art. 6 Abs. 1 lit. c DSGVO zur Erfüllung rechtlicher Pflichten,</li>
                            <li>Art. 6 Abs. 1 lit. f DSGVO auf Grundlage berechtigter Interessen.</li>
                        </ul>
                    </div>

                    <div class="legal-block" id="hosting">
                        <h2>4. Hosting</h2>

                        <p>
                            Diese Website wird bei folgendem Anbieter gehostet:
                        </p>

                        <p>
                            <strong>[Hosting-Anbieter]</strong><br>
                            [Anschrift oder Link zur Datenschutzerklärung des Hosting-Anbieters]
                        </p>

                        <p>
                            Beim Aufruf der Website verarbeitet der Hosting-Anbieter technische Daten, die erforderlich sind,
                            um die Website sicher und zuverlässig auszuliefern. Dazu können insbesondere IP-Adresse, Datum
                            und Uhrzeit des Zugriffs, aufgerufene Seiten, übertragene Datenmenge, Browsertyp, Betriebssystem
                            und Referrer-URL gehören.
                        </p>

                        <p>
                            Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Unser berechtigtes
                            Interesse liegt in der sicheren, stabilen und effizienten Bereitstellung dieser Website.
                        </p>
                    </div>

                    <div class="legal-block" id="logs">
                        <h2>5. Server-Logfiles</h2>

                        <p>
                            Beim Besuch dieser Website können automatisch Informationen in sogenannten Server-Logfiles
                            gespeichert werden. Dazu gehören insbesondere:
                        </p>

                        <ul>
                            <li>IP-Adresse,</li>
                            <li>Datum und Uhrzeit der Anfrage,</li>
                            <li>aufgerufene URL,</li>
                            <li>HTTP-Statuscode,</li>
                            <li>Browsertyp und Browserversion,</li>
                            <li>verwendetes Betriebssystem,</li>
                            <li>Referrer-URL.</li>
                        </ul>

                        <p>
                            Diese Daten dienen der technischen Bereitstellung, Fehleranalyse, Sicherheit und
                            Missbrauchsabwehr.
                        </p>
                    </div>

                    <div class="legal-block" id="kontakt">
                        <h2>6. Kontaktaufnahme</h2>

                        <p>
                            Wenn du uns per E-Mail, Kontaktformular oder auf anderem Weg kontaktierst, verarbeiten wir die
                            von dir übermittelten Angaben zur Bearbeitung deiner Anfrage. Dazu können Name, E-Mail-Adresse,
                            Telefonnummer, Nachrichtentext und weitere freiwillig gemachte Angaben gehören.
                        </p>

                        <p>
                            Die Verarbeitung erfolgt je nach Inhalt der Anfrage auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO
                            oder Art. 6 Abs. 1 lit. f DSGVO.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>7. Postleitzahl-Suche und Shop-Anfragen</h2>

                        <p>
                            Wenn du auf Kioskheld eine Postleitzahl eingibst, kann diese verarbeitet werden, um verfügbare
                            Händler oder Liefergebiete in deiner Nähe zu prüfen. Je nach technischem Ausbau können dabei
                            auch weitere Informationen verarbeitet werden, die für die Anzeige passender Shops oder
                            Lieferoptionen erforderlich sind.
                        </p>

                        <p>
                            Die Verarbeitung erfolgt zur Durchführung vorvertraglicher Maßnahmen oder auf Grundlage unseres
                            berechtigten Interesses, eine passende regionale Shop-Auswahl bereitzustellen.
                        </p>
                    </div>

                    <div class="legal-block" id="bestellungen">
                        <h2>8. Bestellungen</h2>

                        <p>
                            Wenn über Kioskheld oder angebundene Foodzwerge-Shops Bestellungen aufgegeben werden, können die
                            für die Durchführung der Bestellung notwendigen Daten verarbeitet werden. Dazu können insbesondere
                            gehören:
                        </p>

                        <ul>
                            <li>Name,</li>
                            <li>Lieferanschrift,</li>
                            <li>Telefonnummer,</li>
                            <li>E-Mail-Adresse,</li>
                            <li>Bestellinhalte,</li>
                            <li>Liefer- und Zahlungsinformationen,</li>
                            <li>interne Status- und Prozessdaten zur Bestellung.</li>
                        </ul>

                        <p>
                            Die Verarbeitung erfolgt zur Vertragserfüllung gemäß Art. 6 Abs. 1 lit. b DSGVO. Bestelldaten
                            können außerdem aufgrund gesetzlicher Aufbewahrungspflichten gespeichert werden.
                        </p>
                    </div>

                    <div class="legal-block" id="zahlungen">
                        <h2>9. Zahlungsanbieter</h2>

                        <p>
                            Für Zahlungen können externe Zahlungsanbieter eingesetzt werden. Die konkreten Anbieter werden
                            hier ergänzt, sobald die Zahlungsarten final feststehen.
                        </p>

                        <p>
                            <strong>Platzhalter:</strong><br>
                            [PayPal / Stripe / Mollie / Klarna / Barzahlung / EC-Zahlung / sonstige Anbieter ergänzen]
                        </p>

                        <p>
                            Bei Nutzung eines Zahlungsdienstes werden die für die Zahlungsabwicklung erforderlichen Daten an
                            den jeweiligen Anbieter übermittelt. Es gelten zusätzlich die Datenschutzbestimmungen des
                            jeweiligen Zahlungsanbieters.
                        </p>
                    </div>

                    <div class="legal-block" id="cookies">
                        <h2>10. Cookies</h2>

                        <p>
                            Diese Website kann Cookies verwenden. Cookies sind kleine Textdateien, die auf deinem Endgerät
                            gespeichert werden. Einige Cookies sind technisch erforderlich, damit die Website funktioniert.
                            Andere Cookies dürfen nur eingesetzt werden, wenn hierfür eine wirksame Einwilligung vorliegt.
                        </p>

                        <p>
                            <strong>Technisch erforderliche Cookies</strong> können zum Beispiel für Warenkorb, Sitzung,
                            Sicherheit oder Spracheinstellungen notwendig sein.
                        </p>

                        <p>
                            <strong>Optionale Cookies</strong> für Analyse, Marketing oder externe Dienste werden nur
                            verwendet, wenn sie tatsächlich eingebunden sind und die erforderliche Einwilligung eingeholt
                            wurde.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>11. Analyse- und Trackingdienste</h2>

                        <p>
                            Derzeit bitte prüfen und ergänzen:
                        </p>

                        <ul>
                            <li>[Kein Tracking im Einsatz]</li>
                            <li>[Google Analytics / Matomo / Plausible / sonstiger Dienst]</li>
                            <li>[Facebook Pixel / TikTok Pixel / sonstige Marketingdienste]</li>
                        </ul>

                        <p>
                            Falls Analyse- oder Marketingdienste eingesetzt werden, müssen Anbieter, Zweck, Rechtsgrundlage,
                            Speicherdauer, Einwilligungsmechanismus und Widerspruchsmöglichkeiten konkret ergänzt werden.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>12. Einbindung externer Inhalte</h2>

                        <p>
                            Auf dieser Website können externe Inhalte eingebunden werden, zum Beispiel Karten, Schriftarten,
                            Videos, Zahlungsdienste oder andere technische Dienste. Solche Einbindungen können dazu führen,
                            dass personenbezogene Daten an Drittanbieter übertragen werden.
                        </p>

                        <p>
                            <strong>Platzhalter:</strong><br>
                            [Google Maps / OpenStreetMap / YouTube / Google Fonts lokal oder extern / sonstige Dienste ergänzen]
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>13. Speicherdauer</h2>

                        <p>
                            Personenbezogene Daten werden nur so lange gespeichert, wie dies für den jeweiligen Zweck
                            erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen. Nach Wegfall des Zwecks oder
                            Ablauf gesetzlicher Fristen werden die Daten gelöscht oder gesperrt, sofern keine weitere
                            Rechtsgrundlage für die Verarbeitung besteht.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>14. Empfänger von Daten</h2>

                        <p>
                            Personenbezogene Daten können an technische Dienstleister, Hosting-Anbieter, Zahlungsanbieter,
                            angebundene Händler oder sonstige Stellen übermittelt werden, soweit dies für den Betrieb der
                            Website, die Bearbeitung von Anfragen oder die Durchführung von Bestellungen erforderlich ist.
                        </p>

                        <p>
                            Mit Dienstleistern, die personenbezogene Daten in unserem Auftrag verarbeiten, werden soweit
                            erforderlich Vereinbarungen zur Auftragsverarbeitung abgeschlossen.
                        </p>
                    </div>

                    <div class="legal-block" id="rechte">
                        <h2>15. Deine Rechte</h2>

                        <p>
                            Du hast im Rahmen der gesetzlichen Voraussetzungen folgende Rechte:
                        </p>

                        <ul>
                            <li>Recht auf Auskunft,</li>
                            <li>Recht auf Berichtigung,</li>
                            <li>Recht auf Löschung,</li>
                            <li>Recht auf Einschränkung der Verarbeitung,</li>
                            <li>Recht auf Datenübertragbarkeit,</li>
                            <li>Recht auf Widerspruch gegen bestimmte Verarbeitungen,</li>
                            <li>Recht auf Widerruf einer erteilten Einwilligung.</li>
                        </ul>
                    </div>

                    <div class="legal-block">
                        <h2>16. Beschwerderecht bei einer Aufsichtsbehörde</h2>

                        <p>
                            Du hast das Recht, dich bei einer Datenschutzaufsichtsbehörde zu beschweren, wenn du der Ansicht
                            bist, dass die Verarbeitung deiner personenbezogenen Daten gegen Datenschutzrecht verstößt.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>17. Sicherheit</h2>

                        <p>
                            Wir treffen technische und organisatorische Maßnahmen, um personenbezogene Daten gegen Verlust,
                            Missbrauch, unbefugten Zugriff und Veränderung zu schützen. Die Maßnahmen werden entsprechend der
                            technischen Entwicklung und der tatsächlichen Risiken angepasst.
                        </p>
                    </div>

                    <div class="legal-block">
                        <h2>18. Änderung dieser Datenschutzerklärung</h2>

                        <p>
                            Diese Datenschutzerklärung kann angepasst werden, wenn sich technische Abläufe, gesetzliche
                            Vorgaben oder eingesetzte Dienste ändern. Es gilt die jeweils auf dieser Website veröffentlichte
                            Fassung.
                        </p>

                        <p class="legal-updated">
                            Stand: [Monat Jahr]
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

        <x-marketing.footer />

@endsection
