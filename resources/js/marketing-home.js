document.addEventListener('DOMContentLoaded', () => {
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

        if (data.mode === 'single' && Array.isArray(data.shops) && data.shops.length === 1) {
            const shop = data.shops[0];

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

        if (data.mode === 'multiple' && Array.isArray(data.shops) && data.shops.length > 1) {
            showToast(`${data.shops.length} Kioske gefunden. Bitte wähle deinen Shop.`);

            window.setTimeout(() => {
                window.location.href = shopSelectionUrl;
            }, 700);

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

    document.querySelectorAll('.plus').forEach((button) => {
        button.addEventListener('click', () => {
            showToast('Demo: Bundle wurde vorgemerkt.');
        });
    });
});
