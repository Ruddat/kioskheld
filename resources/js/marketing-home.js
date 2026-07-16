document.addEventListener('DOMContentLoaded', () => {
    /* ═══════════════════════════════════════════
       CONFIGURATION
       ═══════════════════════════════════════════ */

    const DEFAULT_SHOP_IMAGE_BASE = '/images/kioskheld/shops';
    const DEFAULT_SHOP_IMAGE_COUNT = 10;

    /* ═══════════════════════════════════════════
       NAV SCROLL BEHAVIOUR
       ═══════════════════════════════════════════ */

    const nav = document.querySelector('.nav');

    if (nav) {
        const onScroll = () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        };

        window.addEventListener('scroll', onScroll, {
            passive: true,
        });

        onScroll();
    }

    /* ═══════════════════════════════════════════
       POSTCODE FORM
       ═══════════════════════════════════════════ */

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

    /* ═══════════════════════════════════════════
       GENERAL HELPERS
       ═══════════════════════════════════════════ */

    const showToast = (message) => {
        toast.textContent = message;
        toast.classList.add('show');

        window.setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    };

    const normalizePostcode = (value) => {
        return String(value ?? '')
            .replace(/\D/g, '')
            .slice(0, 5);
    };

    const normalizeText = (value, fallback = '') => {
        if (value === null || value === undefined) {
            return fallback;
        }

        const normalized = String(value).trim();

        return normalized || fallback;
    };

    const escapeHtml = (value) => {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    };

    const escapeAttribute = (value) => {
        return escapeHtml(value);
    };

    const buildShopUrl = (shopSlug) => {
        if (!shopSlug) {
            return '#';
        }

        return shopLegacyUrl.replace(
            '__SHOP_SLUG__',
            encodeURIComponent(shopSlug)
        );
    };

    /* ═══════════════════════════════════════════
       DEFAULT SHOP IMAGES
       ═══════════════════════════════════════════ */

    const createStableHash = (value) => {
        const text = String(value ?? '');

        let hash = 0;

        for (let index = 0; index < text.length; index += 1) {
            hash = ((hash << 5) - hash) + text.charCodeAt(index);
            hash |= 0;
        }

        return Math.abs(hash);
    };

    const getDefaultShopImageNumber = (shop) => {
        const numericId = Number.parseInt(shop?.id, 10);

        if (Number.isInteger(numericId) && numericId > 0) {
            return ((numericId - 1) % DEFAULT_SHOP_IMAGE_COUNT) + 1;
        }

        const stableValue = [
            shop?.slug,
            shop?.name,
            shop?.address,
            shop?.city,
        ]
            .filter(Boolean)
            .join('|');

        const hash = createStableHash(stableValue || 'kioskheld');

        return (hash % DEFAULT_SHOP_IMAGE_COUNT) + 1;
    };

    const getDefaultShopImageUrl = (shop) => {
        const imageNumber = getDefaultShopImageNumber(shop);

        return `${DEFAULT_SHOP_IMAGE_BASE}/shop-${imageNumber}.png`;
    };

    const isPlaceholderOrLogoImage = (imageUrl) => {
        const normalizedUrl = normalizeText(imageUrl).toLowerCase();

        if (!normalizedUrl) {
            return false;
        }

        return [
            'default-shop-logo',
            'default_shop_logo',
            'shop-logo',
            'shop_logo',
            'no-image',
            'no_image',
            'placeholder',
        ].some((fragment) => normalizedUrl.includes(fragment));
    };

    const getShopMedia = (shop) => {
        const defaultImageUrl = getDefaultShopImageUrl(shop);

        const providedImageUrl = normalizeText(
            shop?.image_url
            || shop?.image
            || shop?.cover_image_url
        );

        const explicitLogoUrl = normalizeText(
            shop?.logo_url
            || shop?.shop_logo_url
            || shop?.logo
        );

        /*
         * Liefert die API in image_url nur ein Logo oder einen Platzhalter,
         * verwenden wir als Kartenbild ein Kioskheld-Defaultbild.
         * Das gelieferte Bild wird gleichzeitig als Logo eingeblendet.
         */
        if (providedImageUrl && isPlaceholderOrLogoImage(providedImageUrl)) {
            return {
                coverImageUrl: defaultImageUrl,
                fallbackImageUrl: defaultImageUrl,
                logoImageUrl: explicitLogoUrl || providedImageUrl,
            };
        }

        return {
            coverImageUrl: providedImageUrl || defaultImageUrl,
            fallbackImageUrl: defaultImageUrl,
            logoImageUrl: explicitLogoUrl,
        };
    };

    /* ═══════════════════════════════════════════
       FORMAT SHOP VALUES
       ═══════════════════════════════════════════ */

    const formatDeliveryTime = (shop) => {
        const directValue = normalizeText(
            shop?.delivery_time
            || shop?.delivery_minutes
            || shop?.estimated_delivery_time
        );

        if (directValue) {
            if (/min/i.test(directValue)) {
                return directValue;
            }

            return `${directValue} Min.`;
        }

        const minimum = shop?.delivery_time_min
            ?? shop?.delivery_minutes_min
            ?? shop?.estimated_delivery_min;

        const maximum = shop?.delivery_time_max
            ?? shop?.delivery_minutes_max
            ?? shop?.estimated_delivery_max;

        if (minimum && maximum) {
            return `${minimum}–${maximum} Min.`;
        }

        if (minimum) {
            return `ab ${minimum} Min.`;
        }

        return '';
    };

    const formatMinimumOrder = (shop) => {
        const value = shop?.minimum_order
            ?? shop?.minimum_order_value
            ?? shop?.minimum_order_amount
            ?? shop?.min_order_value;

        if (value === null || value === undefined || value === '') {
            return '';
        }

        if (typeof value === 'string') {
            const normalized = value.trim();

            if (!normalized) {
                return '';
            }

            if (normalized.includes('€')) {
                return normalized;
            }

            const numericValue = Number.parseFloat(
                normalized.replace(',', '.')
            );

            if (Number.isFinite(numericValue)) {
                return new Intl.NumberFormat('de-DE', {
                    style: 'currency',
                    currency: 'EUR',
                }).format(numericValue);
            }

            return normalized;
        }

        const numericValue = Number(value);

        if (!Number.isFinite(numericValue)) {
            return '';
        }

        return new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR',
        }).format(numericValue);
    };

    const getOpeningStatus = (shop) => {
        const isOpen = shop?.is_open
            ?? shop?.open
            ?? shop?.currently_open;

        if (isOpen === true) {
            return {
                className: 'is-open',
                label: 'Geöffnet',
            };
        }

        if (isOpen === false) {
            return {
                className: 'is-closed',
                label: 'Geschlossen',
            };
        }

        const status = normalizeText(
            shop?.opening_status
            || shop?.status
        ).toLowerCase();

        if (['open', 'opened', 'geöffnet', 'available'].includes(status)) {
            return {
                className: 'is-open',
                label: 'Geöffnet',
            };
        }

        if (['closed', 'geschlossen', 'unavailable'].includes(status)) {
            return {
                className: 'is-closed',
                label: 'Geschlossen',
            };
        }

        return null;
    };

    const buildShopAddress = (shop) => {
        const address = normalizeText(
            shop?.address
            || shop?.street
            || shop?.street_address
        );

        const postcode = normalizeText(
            shop?.postcode
            || shop?.postal_code
        );

        const city = normalizeText(shop?.city);

        const location = [postcode, city]
            .filter(Boolean)
            .join(' ');

        return [address, location]
            .filter(Boolean)
            .join(', ');
    };

    /* ═══════════════════════════════════════════
       DISTRICT SUGGESTIONS
       ═══════════════════════════════════════════ */

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
                || [suggestion.district, suggestion.city]
                    .filter(Boolean)
                    .join(', ')
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

    const buildAvailabilityUrl = ({
        postcode,
        city = null,
        district = null,
    }) => {
        const url = new URL(
            postcodeCheckUrl,
            window.location.origin
        );

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
       NEARBY KIOSKS
       ═══════════════════════════════════════════ */

    const nearbySection = document.querySelector('#nearby-kiosks');
    const nearbyGrid = document.querySelector('#nearbyKiosksGrid');

    const showNearbySection = () => {
        if (!nearbySection) {
            return;
        }

        nearbySection.classList.remove('nearby-kiosks--hidden');
        nearbySection.classList.add('is-visible');
        nearbySection.setAttribute('aria-hidden', 'false');
    };

    const hideNearbySection = () => {
        if (!nearbySection) {
            return;
        }

        nearbySection.classList.add('nearby-kiosks--hidden');
        nearbySection.classList.remove('is-visible');
        nearbySection.setAttribute('aria-hidden', 'true');
    };


    const clearNearbyKiosks = () => {
        if (!nearbyGrid) {
            return;
        }

        nearbyGrid
            .querySelectorAll('.nearby-kiosk-card')
            .forEach((card) => card.remove());
    };

    const removeNearbyEmptyState = () => {
        const emptyState = document.querySelector('#nearbyKiosksEmpty');

        if (emptyState) {
            emptyState.remove();
        }
    };

    const createShopCard = (shop) => {
        const shopName = normalizeText(shop?.name, 'Kiosk');
        const shopSlug = normalizeText(shop?.slug);
        const shopAddress = buildShopAddress(shop);
        const deliveryTime = formatDeliveryTime(shop);
        const minimumOrder = formatMinimumOrder(shop);
        const openingStatus = getOpeningStatus(shop);

        const shopUrl = buildShopUrl(shopSlug);
        const {
            coverImageUrl,
            fallbackImageUrl,
            logoImageUrl,
        } = getShopMedia(shop);

        const card = document.createElement('article');
        card.className = 'nearby-kiosk-card';

        const deliveryMeta = deliveryTime
            ? `
                <span class="nearby-kiosk-card__meta-item">
                    <span aria-hidden="true">◷</span>
                    <strong>${escapeHtml(deliveryTime)}</strong>
                </span>
            `
            : '';

        const minimumOrderMeta = minimumOrder
            ? `
                <span class="nearby-kiosk-card__meta-item">
                    <span aria-hidden="true">🛒</span>
                    ab <strong>${escapeHtml(minimumOrder)}</strong>
                </span>
            `
            : '';

        const openingStatusHtml = openingStatus
            ? `
                <span class="nearby-kiosk-card__status ${escapeAttribute(openingStatus.className)}">
                    <span aria-hidden="true"></span>
                    ${escapeHtml(openingStatus.label)}
                </span>
            `
            : '';

        card.innerHTML = `
            <a
                class="nearby-kiosk-card__link"
                href="${escapeAttribute(shopUrl)}"
            >
<div class="nearby-kiosk-card__media">
    <img
        class="nearby-kiosk-card__image"
        src="${escapeAttribute(coverImageUrl)}"
        data-default-image="${escapeAttribute(fallbackImageUrl)}"
        alt="${escapeAttribute(shopName)}"
        loading="lazy"
        decoding="async"
    >

    ${logoImageUrl
                ? `
            <span class="nearby-kiosk-card__logo">
                <img
                    src="${escapeAttribute(logoImageUrl)}"
                    alt=""
                    loading="lazy"
                    decoding="async"
                >
            </span>
        `
                : ''
            }

    ${openingStatusHtml}
</div>

                <div class="nearby-kiosk-card__body">
                    <h3 class="nearby-kiosk-card__name">
                        ${escapeHtml(shopName)}
                    </h3>

                    ${shopAddress
                ? `
                            <p class="nearby-kiosk-card__address">
                                ${escapeHtml(shopAddress)}
                            </p>
                        `
                : ''
            }

                    ${(deliveryMeta || minimumOrderMeta)
                ? `
                            <div class="nearby-kiosk-card__meta">
                                ${deliveryMeta}
                                ${minimumOrderMeta}
                            </div>
                        `
                : ''
            }

                    <span class="nearby-kiosk-card__cta">
                        Sortiment ansehen
                        <span aria-hidden="true">→</span>
                    </span>
                </div>
            </a>
        `;

        const image = card.querySelector('.nearby-kiosk-card__image');

        if (image) {
            image.addEventListener('error', () => {
                const fallbackUrl = image.dataset.defaultImage;

                if (!fallbackUrl || image.src.endsWith(fallbackUrl)) {
                    image.hidden = true;
                    return;
                }

                image.src = fallbackUrl;
            });
        }

        return card;
    };

    const renderNearbyKiosks = (shops) => {
        if (!nearbyGrid || !Array.isArray(shops) || shops.length === 0) {
            hideNearbySection();
            return;
        }

        clearNearbyKiosks();

        const fragment = document.createDocumentFragment();

        shops.forEach((shop) => {
            fragment.appendChild(createShopCard(shop));
        });

        nearbyGrid.appendChild(fragment);

        showNearbySection();

        window.requestAnimationFrame(() => {
            nearbySection?.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        });
    };

    /* ═══════════════════════════════════════════
       AVAILABILITY RESPONSE
       ═══════════════════════════════════════════ */

    const handleAvailabilityResponse = (data) => {
        if (!data || typeof data !== 'object') {
            showToast('Die Antwort der PLZ-Prüfung war ungültig.');
            return;
        }

        if (data.requires_district === true) {
            currentPostcode = data.postcode || currentPostcode;

            showToast(
                data.message
                || 'Bitte wähle deinen Ortsteil aus.'
            );

            showDistrictSuggestions(
                currentPostcode,
                data.suggestions || []
            );

            return;
        }

        removeDistrictBox();

        if (!data.available) {
            clearNearbyKiosks();
            hideNearbySection();

            showToast(
                data.message
                || 'Für diese Postleitzahl ist Kioskheld aktuell noch nicht verfügbar.'
            );

            return;
        }

        const shops = Array.isArray(data.shops)
            ? data.shops
            : [];

        renderNearbyKiosks(shops);

        if (data.mode === 'single' && shops.length === 1) {
            const shop = shops[0];

            if (!shop || !shop.slug) {
                showToast(
                    'Der gefundene Kiosk konnte nicht korrekt geladen werden.'
                );

                return;
            }

            showToast(
                `${shop.name || 'Kiosk'} gefunden. Du wirst weitergeleitet ...`
            );

            window.setTimeout(() => {
                window.location.href = buildShopUrl(shop.slug);
            }, 700);

            return;
        }

        if (data.mode === 'multiple' && shops.length > 1) {
            showToast(
                `${shops.length} Kioske gefunden. Wähle deinen Shop:`
            );

            return;
        }

        if (shops.length === 1) {
            const shop = shops[0];

            showToast(
                `${shop.name || 'Kiosk'} wurde gefunden.`
            );

            return;
        }

        if (shops.length > 1) {
            showToast(
                `${shops.length} Kioske gefunden. Wähle deinen Shop:`
            );

            return;
        }

        showToast('Es wurde kein passender Kiosk gefunden.');
    };

    /* ═══════════════════════════════════════════
       AVAILABILITY REQUEST
       ═══════════════════════════════════════════ */

    const checkAvailability = async ({
        postcode,
        city = null,
        district = null,
    }) => {
        const cleanPostcode = normalizePostcode(postcode);

        if (!/^\d{5}$/.test(cleanPostcode)) {
            showToast(
                'Bitte eine gültige 5-stellige Postleitzahl eingeben.'
            );

            input.focus();

            return;
        }

        currentPostcode = cleanPostcode;

        try {
            const response = await fetch(
                buildAvailabilityUrl({
                    postcode: cleanPostcode,
                    city,
                    district,
                }),
                {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            let result;

            try {
                result = await response.json();
            } catch (error) {
                throw new Error('Invalid JSON response');
            }

            if (!response.ok || !result.ok) {
                showToast(
                    result?.message
                    || 'Die PLZ konnte nicht geprüft werden.'
                );

                input.focus();

                return;
            }

            handleAvailabilityResponse(result.data);
        } catch (error) {
            console.error('Kioskheld availability request failed', error);

            showToast(
                'Die Prüfung ist gerade nicht erreichbar. Bitte versuche es erneut.'
            );
        }
    };

    /* ═══════════════════════════════════════════
       EVENTS
       ═══════════════════════════════════════════ */

    input.addEventListener('input', () => {
        input.value = normalizePostcode(input.value);

        removeDistrictBox();
        clearNearbyKiosks();
        hideNearbySection();
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
