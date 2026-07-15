document.addEventListener('DOMContentLoaded', () => {
    /* ═══════════════════════════════════════════
       NAV SCROLL BEHAVIOUR
       ═══════════════════════════════════════════ */
    const nav = document.querySelector('.nav');
    if (nav) {
        const onScroll = () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    const form = document.querySelector('#postcodeForm');
    const input = document.querySelector('#postcode');
    const toast = document.querySelector('#toast');

    if (!form || !input || !toast) {
        return;
    }

    const postcodeCheckUrl = form.dataset.postcodeCheckUrl;
    const shopSelectionUrl = form.dataset.shopSelectionUrl;
    const shopLegacyUrl = form.dataset.shopLegacyUrl;

    if (!postcodeCheckUrl || !shopSelectionUrl || !shopLegacyUrl) {
        console.error('Kioskheld postcode route configuration missing', {
            postcodeCheckUrl,
            shopSelectionUrl,
            shopLegacyUrl,
        });

        return;
    }

    let currentPostcode = null;

    const showToast = (message) => {
        toast.textContent = message;
        toast.classList.add('show');

        window.setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    };

    const normalizePostcode = (value) => value.replace(/\D/g, '').slice(0, 5);

    const removeDistrictBox = () => {
        const existingBox = document.querySelector('#districtSuggestions');

        if (existingBox) {
            existingBox.remove();
        }
    };

    const showDistrictSuggestions = (postcode, suggestions = []) => {
        removeDistrictBox();

        if (!Array.isArray(suggestions) || suggestions.length === 0) {
            showToast('Bitte gib deinen Ortsteil genauer an.');
            return;
        }

        const box = document.createElement('div');
        box.id = 'districtSuggestions';
        box.className = 'district-suggestions';
        box.setAttribute('aria-live', 'polite');

        const title = document.createElement('div');
        title.className = 'district-suggestions-title';
        title.textContent = 'Bitte wähle deinen Ortsteil:';

        const list = document.createElement('div');
        list.className = 'district-suggestions-list';

        suggestions.forEach((suggestion) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'district-suggestion-button';

            const label = suggestion.label
                || [suggestion.district, suggestion.city].filter(Boolean).join(', ')
                || suggestion.district
                || suggestion.city
                || 'Ortsteil auswählen';

            button.textContent = label;

            button.addEventListener('click', async () => {
                showToast(`${label} wird geprüft ...`);

                await checkAvailability({
                    postcode,
                    city: suggestion.city || null,
                    district: suggestion.district || null,
                });
            });

            list.appendChild(button);
        });

        box.appendChild(title);
        box.appendChild(list);

        form.insertAdjacentElement('afterend', box);
    };

    const buildAvailabilityUrl = ({ postcode, city = null, district = null }) => {
        const url = new URL(postcodeCheckUrl, window.location.origin);

        url.searchParams.set('postcode', postcode);

        if (city) {
            url.searchParams.set('city', city);
        }

        if (district) {
            url.searchParams.set('district', district);
        }

        return url;
    };

    /* ═══════════════════════════════════════════
       NEARBY KIOSKS RENDERING (neu)
       ═══════════════════════════════════════════ */
    const nearbyGrid = document.querySelector('#nearbyKiosksGrid');
    const nearbyEmpty = document.querySelector('#nearbyKiosksEmpty');

    const renderNearbyKiosks = (shops) => {
        if (!nearbyGrid || !Array.isArray(shops) || shops.length === 0) {
            return;
        }

        /* Remove empty state */
        if (nearbyEmpty) {
            nearbyEmpty.remove();
        }

        /* Remove previously rendered cards */
        nearbyGrid.querySelectorAll('.nearby-kiosk-card').forEach((card) => card.remove());

        shops.forEach((shop) => {
            const shopName = shop.name || 'Kiosk';
            const shopCity = shop.city || '';
            const shopAddress = shop.address || '';
            const shopSlug = shop.slug || '';
            const deliveryTime = shop.delivery_time || shop.delivery_minutes || '';
            const minimumOrder = shop.minimum_order || '';

            const card = document.createElement('article');
            card.className = 'nearby-kiosk-card';

            const shopImgUrl = shop.image_url || shop.image || '';

            card.innerHTML = `
                <a class="nearby-kiosk-card__link" href="${shopSlug ? shopLegacyUrl.replace('__SHOP_SLUG__', encodeURIComponent(shopSlug)) : '#'}">
                    <div class="nearby-kiosk-card__media">
                        ${shopImgUrl
                            ? `<img class="nearby-kiosk-card__image" src="${shopImgUrl}" alt="" loading="lazy">`
                            : `<span class="nearby-kiosk-card__placeholder">${shopName.charAt(0).toUpperCase()}</span>`
                        }
                    </div>
                    <div class="nearby-kiosk-card__body">
                        <h3 class="nearby-kiosk-card__name">${shopName}</h3>
                        <p class="nearby-kiosk-card__address">${[shopAddress, shopCity].filter(Boolean).join(', ')}</p>
                        <div class="nearby-kiosk-card__meta">
                            ${deliveryTime ? `<span class="nearby-kiosk-card__meta-item"><strong>${deliveryTime}</strong> Min.</span>` : ''}
                            ${minimumOrder ? `<span class="nearby-kiosk-card__meta-item">ab <strong>${minimumOrder}</strong></span>` : ''}
                        </div>
                        <span class="nearby-kiosk-card__cta">Jetzt bestellen →</span>
                    </div>
                </a>
            `;

            nearbyGrid.appendChild(card);
        });

        /* Scroll to section */
        const section = document.querySelector('#nearby-kiosks');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    /* ═══════════════════════════════════════════
       AVAILABILITY RESPONSE HANDLER
       (bestehende Logik + Nearby-Kiosks-Erweiterung)
       ═══════════════════════════════════════════ */
    const handleAvailabilityResponse = (data) => {
        if (!data || typeof data !== 'object') {
            showToast('Die Antwort der PLZ-Prüfung war ungültig.');
            return;
        }

        if (data.requires_district === true) {
            currentPostcode = data.postcode || currentPostcode;
            showToast(data.message || 'Bitte wähle deinen Ortsteil aus.');
            showDistrictSuggestions(currentPostcode, data.suggestions || []);
            return;
        }

        removeDistrictBox();

        if (!data.available) {
            showToast(data.message || 'Für diese Postleitzahl ist Kioskheld aktuell noch nicht verfügbar.');
            return;
        }

        const shops = Array.isArray(data.shops) ? data.shops : [];

        /* ── Nearby Kiosks: Karten rendern ── */
        renderNearbyKiosks(shops);

        if (data.mode === 'single' && shops.length === 1) {
            const shop = shops[0];

            if (!shop || !shop.slug) {
                showToast('Der gefundene Kiosk konnte nicht korrekt geladen werden.');
                return;
            }

            showToast(`${shop.name || 'Kiosk'} gefunden. Du wirst weitergeleitet ...`);

            window.setTimeout(() => {
                window.location.href = shopLegacyUrl.replace(
                    '__SHOP_SLUG__',
                    encodeURIComponent(shop.slug)
                );
            }, 700);

            return;
        }

        if (data.mode === 'multiple' && shops.length > 1) {
            /* Karten werden angezeigt statt redirect — User wählt direkt */
            showToast(`${shops.length} Kioske gefunden. Wähle deinen Shop:`);
            return;
        }

        showToast('Es wurde kein eindeutiger Kiosk gefunden.');
    };

    const checkAvailability = async ({ postcode, city = null, district = null }) => {
        const cleanPostcode = normalizePostcode(postcode);

        if (!/^\d{5}$/.test(cleanPostcode)) {
            showToast('Bitte eine gültige 5-stellige Postleitzahl eingeben.');
            input.focus();
            return;
        }

        currentPostcode = cleanPostcode;

        try {
            const response = await fetch(buildAvailabilityUrl({
                postcode: cleanPostcode,
                city,
                district,
            }), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            const result = await response.json();

            if (!response.ok || !result.ok) {
                showToast(result.message || 'Die PLZ konnte nicht geprüft werden.');
                input.focus();
                return;
            }

            handleAvailabilityResponse(result.data);
        } catch (error) {
            showToast('Die Prüfung ist gerade nicht erreichbar. Bitte versuche es erneut.');
        }
    };

    input.addEventListener('input', () => {
        input.value = normalizePostcode(input.value);
        removeDistrictBox();
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const postcode = normalizePostcode(input.value);

        showToast('Wir prüfen deine Umgebung ...');

        await checkAvailability({
            postcode,
        });
    });

});