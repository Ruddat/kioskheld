document.addEventListener('DOMContentLoaded', () => {
    const shopPage = document.querySelector('.shop-app-page');

    if (!shopPage) {
        return;
    }

    const cartDrawer = document.querySelector('#cartDrawer');
    const cartBackdrop = document.querySelector('#cartBackdrop');
    const cartFloatingButton = document.querySelector('#cartFloatingButton');
    const cartCloseButton = document.querySelector('#cartCloseButton');
    const cartItems = document.querySelector('#cartItems');
    const cartSubtotal = document.querySelector('#cartSubtotal');
    const cartFloatingTotal = document.querySelector('#cartFloatingTotal');
    const cartFloatingCount = document.querySelector('#cartFloatingCount');
    const cartFloatingText = document.querySelector('#cartFloatingText');
    const cartCheckoutButton = document.querySelector('#cartCheckoutButton');

    const cartValidationMessage = document.querySelector('#cartValidationMessage');
    const cartValidatedTotals = document.querySelector('#cartValidatedTotals');
    const cartValidatedItemsTotal = document.querySelector('#cartValidatedItemsTotal');
    const cartValidatedDeliveryFee = document.querySelector('#cartValidatedDeliveryFee');

    const cartValidatedDepositRow = document.querySelector('#cartValidatedDepositRow');
    const cartValidatedDepositTotal = document.querySelector('#cartValidatedDepositTotal');

    const cartValidatedGrandTotal = document.querySelector('#cartValidatedGrandTotal');
    const cartValidatedMinimum = document.querySelector('#cartValidatedMinimum');
    const cartValidatedMissing = document.querySelector('#cartValidatedMissing');
    const cartMinimumRow = document.querySelector('#cartMinimumRow');
    const cartMissingRow = document.querySelector('#cartMissingRow');
    const cartSummaryHint = document.querySelector('#cartSummaryHint');

    const menuChoiceDrawer = document.querySelector('#menuChoiceDrawer');
    const menuChoiceBackdrop = document.querySelector('#menuChoiceBackdrop');
    const menuChoiceCloseButton = document.querySelector('#menuChoiceCloseButton');
    const menuChoiceTitle = document.querySelector('#menuChoiceTitle');
    const menuChoiceDescription = document.querySelector('#menuChoiceDescription');
    const menuChoicePrice = document.querySelector('#menuChoicePrice');
    const menuChoiceGroups = document.querySelector('#menuChoiceGroups');
    const menuChoiceMessage = document.querySelector('#menuChoiceMessage');
    const menuChoiceAddButton = document.querySelector('#menuChoiceAddButton');

    const formatter = new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    });

    const cart = new Map();

    const config = window.KioskheldShop || {};

    const catalogProducts = config.catalog || [];
    const catalogMenus = config.menus || [];
    const productsByCategoryId = config.productsByCategoryId || {};

    const cartValidateUrl = config.cartValidateUrl;
    const csrfToken = config.csrfToken;
    const shopId = Number(config.shopId || 0);

    if (!cartValidateUrl || !csrfToken || !shopId) {
        console.error('Kioskheld shop cart config missing', {
            cartValidateUrl,
            csrfToken: Boolean(csrfToken),
            shopId,
        });

        return;
    }

    let activeMenu = null;
    let activeMenuSelections = new Map();
    let lastValidation = null;
    let isValidating = false;
    let checkoutUrl = null;
    let validatedCartFingerprint = null;

    const buildCartFingerprint = () => {
        return JSON.stringify(
            [...cart.values()]
                .map((item) => {
                    if (item.type === 'menu') {
                        return {
                            type: 'menu',
                            menu_id: Number(item.menuId),
                            quantity: Number(item.quantity),
                            choices: (item.choices || [])
                                .map((choice) => ({
                                    choice_group_id: Number(choice.choice_group_id),
                                    variant_id: Number(choice.variant_id),
                                    quantity: Number(choice.quantity),
                                }))
                                .sort((a, b) => {
                                    if (a.choice_group_id !== b.choice_group_id) {
                                        return a.choice_group_id - b.choice_group_id;
                                    }

                                    return a.variant_id - b.variant_id;
                                }),
                        };
                    }

                    return {
                        type: 'product',
                        variant_id: Number(item.variantId),
                        quantity: Number(item.quantity),
                    };
                })
                .sort((a, b) => {
                    const left = `${a.type}:${a.variant_id || a.menu_id}`;
                    const right = `${b.type}:${b.variant_id || b.menu_id}`;

                    return left.localeCompare(right);
                })
        );
    };

    const openCart = () => {
        cartDrawer.classList.add('is-open');
        cartDrawer.setAttribute('aria-hidden', 'false');
        cartBackdrop.classList.add('is-visible');
    };

    const closeCart = () => {
        cartDrawer.classList.remove('is-open');
        cartDrawer.setAttribute('aria-hidden', 'true');
        cartBackdrop.classList.remove('is-visible');
    };

    const getCartCount = () => {
        return [...cart.values()].reduce((sum, item) => sum + item.quantity, 0);
    };

    const getCartTotal = () => {
        return [...cart.values()].reduce((sum, item) => {
            const merchandiseTotal = Number(item.price || 0) * Number(item.quantity || 0);
            const depositTotal = Number(item.depositAmount || 0) * Number(item.quantity || 0);

            return sum + merchandiseTotal + depositTotal;
        }, 0);
    };

    const invalidateCartValidation = () => {
        lastValidation = null;
        checkoutUrl = null;
        validatedCartFingerprint = null;
    };

    const resetValidationUi = () => {
        invalidateCartValidation();

        if (cartCheckoutButton) {
            cartCheckoutButton.textContent = 'Weiter zur Kasse';
        }

        if (cartValidationMessage) {
            cartValidationMessage.hidden = true;
            cartValidationMessage.className = 'cart-validation-message';
            cartValidationMessage.textContent = '';
        }

        if (cartValidatedTotals) {
            cartValidatedTotals.hidden = true;
        }

        if (cartSummaryHint) {
            cartSummaryHint.textContent =
                'Preise, Lieferbarkeit und Mindestbestellwert werden vor dem Checkout noch einmal serverseitig geprüft.';
        }

        document.querySelectorAll('.cart-item.has-validation-error').forEach((item) => {
            item.classList.remove('has-validation-error');
        });
    };

    const setValidationMessage = (type, message) => {
        if (!cartValidationMessage) {
            return;
        }

        cartValidationMessage.hidden = false;
        cartValidationMessage.className = `cart-validation-message is-${type}`;
        cartValidationMessage.textContent = message;
    };

    const getMenuPrice = (menu) => {
        return Number(menu?.payable_price ?? menu?.effective_price ?? menu?.menu_price ?? 0);
    };

    const getMenuById = (menuId) => {
        return catalogMenus.find((menu) => Number(menu.id) === Number(menuId));
    };

    const getChoiceGroupProducts = (group) => {
        if (Array.isArray(group.options) && group.options.length > 0) {
            return group.options.map((option) => ({
                product: {
                    id: option.product_id,
                    name: option.product_name || option.name || 'Produkt',
                    image_url: option.image_url || '',
                    is_available: option.is_available !== false,
                },
                variant: {
                    id: option.id,
                    name: option.name || option.product_name || 'Auswahl',
                    price: Number(option.price ?? 0),
                    regular_price: Number(option.regular_price ?? option.price ?? 0),
                    is_available: option.is_available !== false,
                    available_quantity: option.available_quantity ?? null,
                },
                option,
            }));
        }

        const products = productsByCategoryId[group.category_id] || [];
        const allowedTitles = group.allowed_variant_titles || [];

        return products
            .map((product) => {
                const variants = product.variants || [];

                const matchingVariants = variants.filter((variant) => {
                    if (allowedTitles.length === 0) {
                        return true;
                    }

                    return allowedTitles.includes(variant.name);
                });

                return matchingVariants.map((variant) => ({
                    product,
                    variant,
                    option: null,
                }));
            })
            .flat()
            .filter((entry) => entry.product?.is_available !== false && entry.variant?.is_available !== false);
    };

    const getGroupSelectionQuantity = (groupId) => {
        const groupSelection = activeMenuSelections.get(String(groupId));

        if (!groupSelection) {
            return 0;
        }

        return [...groupSelection.values()].reduce((sum, item) => sum + item.quantity, 0);
    };

    const getVariantSelectionQuantity = (groupId, variantId) => {
        const groupSelection = activeMenuSelections.get(String(groupId));

        if (!groupSelection) {
            return 0;
        }

        return groupSelection.get(String(variantId))?.quantity || 0;
    };

    const getChoiceOption = (groupId, variantId) => {
        if (!activeMenu) {
            return null;
        }

        const group = (activeMenu.choice_groups || [])
            .find((choiceGroup) => String(choiceGroup.id) === String(groupId));

        if (!group) {
            return null;
        }

        return getChoiceGroupProducts(group)
            .find(({ variant }) => String(variant.id) === String(variantId)) || null;
    };

    const setMenuChoiceMessage = (message) => {
        if (!menuChoiceMessage) {
            return;
        }

        if (!message) {
            menuChoiceMessage.hidden = true;
            menuChoiceMessage.textContent = '';
            return;
        }

        menuChoiceMessage.hidden = false;
        menuChoiceMessage.textContent = message;
    };

    const openMenuChoiceDrawer = () => {
        menuChoiceDrawer.classList.add('is-open');
        menuChoiceDrawer.setAttribute('aria-hidden', 'false');
        menuChoiceBackdrop.classList.add('is-visible');
    };

    const closeMenuChoiceDrawer = () => {
        menuChoiceDrawer.classList.remove('is-open');
        menuChoiceDrawer.setAttribute('aria-hidden', 'true');
        menuChoiceBackdrop.classList.remove('is-visible');
        activeMenu = null;
        activeMenuSelections = new Map();
        setMenuChoiceMessage('');
    };

    const buildMenuChoiceSummary = (choices) => {
        if (!choices.length) {
            return '';
        }

        return choices
            .map((choice) => `${choice.quantity}× ${choice.product_name}`)
            .join(', ');
    };

    const buildMenuCartKey = (menuId, choices) => {
        const normalizedChoices = choices
            .map((choice) => ({
                choice_group_id: Number(choice.choice_group_id),
                variant_id: Number(choice.variant_id),
                quantity: Number(choice.quantity),
            }))
            .sort((a, b) => {
                if (a.choice_group_id !== b.choice_group_id) {
                    return a.choice_group_id - b.choice_group_id;
                }

                return a.variant_id - b.variant_id;
            });

        return `menu:${menuId}:${JSON.stringify(normalizedChoices)}`;
    };

    const renderMenuChoiceDrawer = () => {
        if (!activeMenu) {
            return;
        }

        const price = getMenuPrice(activeMenu);

        menuChoiceTitle.textContent = activeMenu.name || 'Menü';
        menuChoiceDescription.textContent = activeMenu.description || '';
        menuChoicePrice.textContent = formatter.format(price);

        const groups = activeMenu.choice_groups || [];

        menuChoiceGroups.innerHTML = groups.map((group) => {
            const groupId = String(group.id);
            const options = getChoiceGroupProducts(group);
            const selectedQuantity = getGroupSelectionQuantity(groupId);
            const minQuantity = Number(group.min_quantity || 0);
            const maxQuantity = Number(group.max_quantity || 0);

            const optionsHtml = options.map(({ product, variant }) => {
                const variantId = String(variant.id);
                const quantity = getVariantSelectionQuantity(groupId, variantId);
                const imageUrl = product.image_url || '';
                const hasPlaceholder = imageUrl.includes('no-image-placeholder');

                const isAvailable = variant.is_available !== false && product.is_available !== false;
                const availableQuantity = variant.available_quantity;
                const hasFrontendLimit = availableQuantity !== null &&
                    availableQuantity !== undefined && Number(availableQuantity) > 0;

                const image = imageUrl && !hasPlaceholder ?
                    `<img src="${imageUrl}" alt="">` :
                    `<span>${(product.name || 'P').charAt(0)}</span>`;

                const priceInfo = Number(variant.price || 0) > 0 ?
                    `+ ${formatter.format(Number(variant.price || 0))}` :
                    'im Paket enthalten';

                const availabilityInfo = !isAvailable ?
                    'Aktuell nicht verfügbar' :
                    hasFrontendLimit ?
                        `Noch ${Number(availableQuantity)} verfügbar` :
                        '';

                return `
                        <div class="menu-choice-option ${!isAvailable ? 'is-disabled' : ''}" data-choice-group-id="${groupId}" data-variant-id="${variantId}">
                            <div class="menu-choice-option-image">
                                ${image}
                            </div>

                            <div class="menu-choice-option-main">
                                <h3>${product.name || variant.name || 'Produkt'}</h3>
                                <p>${variant.name || ''} · ${priceInfo}</p>
                                ${availabilityInfo ? `<small>${availabilityInfo}</small>` : ''}
                            </div>

                            <div class="menu-choice-option-controls">
                                <button type="button" data-menu-choice-action="decrease" data-choice-group-id="${groupId}" data-variant-id="${variantId}" ${quantity <= 0 ? 'disabled' : ''}>−</button>
                                <span>${quantity}</span>
                                <button type="button" data-menu-choice-action="increase" data-choice-group-id="${groupId}" data-variant-id="${variantId}" ${!isAvailable ? 'disabled' : ''}>+</button>
                            </div>
                        </div>
                    `;
            }).join('');

            return `
                    <section class="menu-choice-group">
                        <div class="menu-choice-group-head">
                            <div>
                                <h3>${group.label || 'Auswahl'}</h3>
                                <p>${selectedQuantity} von ${maxQuantity || minQuantity} gewählt</p>
                            </div>

                            <strong>${minQuantity}${maxQuantity !== minQuantity ? `–${maxQuantity}` : ''}×</strong>
                        </div>

                        <div class="menu-choice-options">
                            ${optionsHtml || `<div class="menu-choice-empty">Für diese Auswahl sind aktuell keine passenden Produkte verfügbar.</div>`}
                        </div>
                    </section>
                `;
        }).join('');

        setMenuChoiceMessage('');
    };

    const updateMenuChoiceQuantity = (groupId, variantId, direction) => {
        if (!activeMenu) {
            return;
        }

        const group = (activeMenu.choice_groups || [])
            .find((choiceGroup) => String(choiceGroup.id) === String(groupId));

        if (!group) {
            return;
        }

        const maxQuantity = Number(group.max_quantity || 0);
        const maxPerVariant = Number(group.max_per_variant || maxQuantity || 99);

        const currentGroupQuantity = getGroupSelectionQuantity(groupId);
        const currentVariantQuantity = getVariantSelectionQuantity(groupId, variantId);

        const selectedOption = getChoiceOption(groupId, variantId);
        const isAvailable = selectedOption?.variant?.is_available !== false &&
            selectedOption?.product?.is_available !== false;

        const availableQuantity = selectedOption?.variant?.available_quantity ?? null;
        const hasFrontendLimit = availableQuantity !== null &&
            availableQuantity !== undefined &&
            Number(availableQuantity) > 0;

        if (!isAvailable) {
            setMenuChoiceMessage('Diese Auswahl ist aktuell nicht verfügbar.');
            return;
        }

        if (direction === 'increase') {
            if (maxQuantity > 0 && currentGroupQuantity >= maxQuantity) {
                setMenuChoiceMessage(`Für "${group.label}" sind maximal ${maxQuantity} Stück möglich.`);
                return;
            }

            if (currentVariantQuantity >= maxPerVariant) {
                setMenuChoiceMessage(`Von diesem Artikel sind maximal ${maxPerVariant} Stück möglich.`);
                return;
            }

            if (hasFrontendLimit && currentVariantQuantity >= Number(availableQuantity)) {
                setMenuChoiceMessage(`Von dieser Auswahl sind aktuell nur ${Number(availableQuantity)} verfügbar.`);
                return;
            }
        }

        let groupSelection = activeMenuSelections.get(String(groupId));

        if (!groupSelection) {
            groupSelection = new Map();
            activeMenuSelections.set(String(groupId), groupSelection);
        }

        const current = groupSelection.get(String(variantId)) || {
            variantId: Number(variantId),
            quantity: 0,
        };

        if (direction === 'increase') {
            current.quantity += 1;
            groupSelection.set(String(variantId), current);
        }

        if (direction === 'decrease') {
            current.quantity -= 1;

            if (current.quantity <= 0) {
                groupSelection.delete(String(variantId));
            } else {
                groupSelection.set(String(variantId), current);
            }
        }

        setMenuChoiceMessage('');
        renderMenuChoiceDrawer();
    };

    const getSelectedMenuChoices = () => {
        if (!activeMenu) {
            return [];
        }

        const choices = [];

        (activeMenu.choice_groups || []).forEach((group) => {
            const groupSelection = activeMenuSelections.get(String(group.id));

            if (!groupSelection) {
                return;
            }

            groupSelection.forEach((selection) => {
                const options = getChoiceGroupProducts(group);
                const selectedOption = options.find(({ variant }) => Number(variant.id) === Number(selection.variantId));

                choices.push({
                    choice_group_id: Number(group.id),
                    variant_id: Number(selection.variantId),
                    quantity: Number(selection.quantity),
                    product_name: selectedOption?.product?.name || 'Produkt',
                    variant_name: selectedOption?.variant?.name || '',
                });
            });
        });

        return choices;
    };

    const validateActiveMenuChoices = () => {
        if (!activeMenu) {
            return 'Kein Menü ausgewählt.';
        }

        const groups = activeMenu.choice_groups || [];

        for (const group of groups) {
            const selectedQuantity = getGroupSelectionQuantity(String(group.id));
            const minQuantity = Number(group.min_quantity || 0);
            const maxQuantity = Number(group.max_quantity || 0);

            if (group.required && selectedQuantity < minQuantity) {
                return `Bitte wähle bei "${group.label}" mindestens ${minQuantity} Stück.`;
            }

            if (maxQuantity > 0 && selectedQuantity > maxQuantity) {
                return `Bei "${group.label}" sind maximal ${maxQuantity} Stück möglich.`;
            }
        }

        return null;
    };

    const addActiveMenuToCart = () => {
        const validationMessage = validateActiveMenuChoices();

        if (validationMessage) {
            setMenuChoiceMessage(validationMessage);
            return;
        }

        const choices = getSelectedMenuChoices();
        const menuId = Number(activeMenu.id);
        const cartKey = buildMenuCartKey(menuId, choices);
        const existing = cart.get(cartKey);
        const price = getMenuPrice(activeMenu);

        resetValidationUi();

        if (existing) {
            existing.quantity += 1;
            cart.set(cartKey, existing);
        } else {
            cart.set(cartKey, {
                cartKey,
                type: 'menu',
                menuId,
                name: activeMenu.name || 'Menü',
                price,
                imageUrl: activeMenu.image_url || '',
                quantity: 1,
                choices,
                choiceSummary: buildMenuChoiceSummary(choices),
            });
        }

        renderCart();
        closeMenuChoiceDrawer();
        openCart();
    };

    const getErrorMessage = (code, fallback = null) => {
        const messages = {
            SHOP_NOT_AVAILABLE: 'Dieser Kiosk ist aktuell nicht verfügbar.',
            ADDRESS_NOT_DELIVERABLE: 'Dieser Kiosk liefert aktuell nicht an deine Adresse.',
            VARIANT_NOT_AVAILABLE: 'Ein Artikel ist aktuell nicht mehr verfügbar.',
            PRICE_NOT_AVAILABLE: 'Für einen Artikel konnte kein aktueller Preis ermittelt werden.',
            PRODUCT_OUT_OF_STOCK: 'Ein Artikel ist aktuell nicht ausreichend verfügbar.',
            EMPTY_CART: 'Dein Warenkorb ist leer.',
            MINIMUM_ORDER_NOT_REACHED: 'Der Mindestbestellwert ist noch nicht erreicht.',
            PAYMENT_METHOD_NOT_ALLOWED: 'Diese Zahlungsart ist für diesen Shop aktuell nicht erlaubt.',
            MENU_CHOICE_INVALID: 'Eine Auswahl im Sparpaket ist nicht mehr gültig.',
            MENU_CHOICE_OUT_OF_STOCK: 'Eine Auswahl im Sparpaket ist aktuell nicht ausreichend verfügbar.',
        };

        return messages[code] || fallback || 'Der Warenkorb konnte nicht validiert werden.';
    };

    const markInvalidItems = (data) => {
        const invalidItems = data?.invalid_items || data?.items?.filter((item) => item.valid === false) || [];

        invalidItems.forEach((item) => {
            const variantId = item.variant_id || item.variantId;
            const menuId = item.menu_id || item.menuId;

            if (variantId) {
                const element = cartItems.querySelector(`.cart-item[data-variant-id="${variantId}"]`);

                if (element) {
                    element.classList.add('has-validation-error');
                }
            }

            if (menuId) {
                const element = cartItems.querySelector(`.cart-item[data-menu-id="${menuId}"]`);

                if (element) {
                    element.classList.add('has-validation-error');
                }
            }
        });
    };

    const renderValidatedTotals = (totals = {}) => {
        if (!cartValidatedTotals) {
            return;
        }

        const merchandiseTotal = Number(
            totals.merchandise_total
            ?? totals.items_total
            ?? 0
        );

        const depositTotal = Number(
            totals.deposit_total
            ?? 0
        );

        const deliveryFee = Number(
            totals.delivery_fee
            ?? 0
        );

        const grandTotal = Number(
            totals.grand_total
            ?? merchandiseTotal + depositTotal + deliveryFee
        );

        const minimumOrderValue = totals.minimum_order_value;
        const missingMinimumOrderValue = totals.missing_minimum_order_value;

        cartValidatedItemsTotal.textContent = formatter.format(merchandiseTotal);
        cartValidatedDeliveryFee.textContent = formatter.format(deliveryFee);
        cartValidatedGrandTotal.textContent = formatter.format(grandTotal);

        if (cartValidatedDepositRow && cartValidatedDepositTotal) {
            if (depositTotal > 0) {
                cartValidatedDepositRow.hidden = false;
                cartValidatedDepositTotal.textContent = formatter.format(depositTotal);
            } else {
                cartValidatedDepositRow.hidden = true;
                cartValidatedDepositTotal.textContent = formatter.format(0);
            }
        }

        if (minimumOrderValue !== null && minimumOrderValue !== undefined) {
            cartMinimumRow.hidden = false;
            cartValidatedMinimum.textContent = formatter.format(Number(minimumOrderValue));
        } else {
            cartMinimumRow.hidden = true;
        }

        if (
            missingMinimumOrderValue !== null &&
            missingMinimumOrderValue !== undefined &&
            Number(missingMinimumOrderValue) > 0
        ) {
            cartMissingRow.hidden = false;
            cartValidatedMissing.textContent = formatter.format(
                Number(missingMinimumOrderValue)
            );
        } else {
            cartMissingRow.hidden = true;
        }

        cartValidatedTotals.hidden = false;
    };

    const renderCart = () => {
        const items = [...cart.values()];
        const count = getCartCount();
        const total = getCartTotal();

        cartSubtotal.textContent = formatter.format(total);
        cartFloatingTotal.textContent = formatter.format(total);
        cartFloatingCount.textContent = count;
        cartCheckoutButton.disabled = count === 0 || isValidating;

        if (cartFloatingText) {
            cartFloatingText.textContent = count === 0 ?
                'Noch keine Artikel' :
                `${count} Artikel im Warenkorb`;
        }

        if (count > 0) {
            cartFloatingButton.classList.add('is-visible');
        } else {
            cartFloatingButton.classList.remove('is-visible');
        }

        if (items.length === 0) {
            cartItems.innerHTML = `
                    <div class="cart-empty">
                        Dein Warenkorb ist noch leer.
                    </div>
                `;

            resetValidationUi();

            return;
        }

        cartItems.innerHTML = items.map((item) => {
            const cartKey = item.cartKey || String(item.variantId);
            const encodedCartKey = encodeURIComponent(cartKey);

            const image = item.imageUrl ?
                `<img src="${item.imageUrl}" alt="">` :
                `<span>${item.name.charAt(0)}</span>`;

            const merchandiseLineTotal =
                Number(item.price || 0) * Number(item.quantity || 0);

            const depositLineTotal =
                Number(item.depositAmount || 0) * Number(item.quantity || 0);

            let meta;

            if (item.type === 'menu' && item.choiceSummary) {
                meta = item.choiceSummary;
            } else {
                meta = `${formatter.format(item.price)} · ${formatter.format(merchandiseLineTotal)}`;

                if (depositLineTotal > 0) {
                    meta += `<br><small>zzgl. ${formatter.format(depositLineTotal)} ${item.depositLabel || 'Pfand'}</small>`;
                }
            }

            const badge = item.type === 'menu' ?
                `<em class="cart-item-badge">Sparpaket</em>` :
                '';

            return `
        <div class="cart-item" data-cart-key="${encodedCartKey}" data-variant-id="${item.variantId || ''}" data-menu-id="${item.menuId || ''}">
            <div class="cart-item-image">
                ${image}
            </div>

            <div class="cart-item-main">
                ${badge}
                <h3>${item.name}</h3>
                <p>${meta}</p>

                <div class="cart-item-controls">
                    <button type="button" data-action="decrease" data-cart-key="${encodedCartKey}">−</button>
                    <span>${item.quantity}</span>
                    <button type="button" data-action="increase" data-cart-key="${encodedCartKey}">+</button>
                    <button type="button" class="cart-remove" data-action="remove" data-cart-key="${encodedCartKey}">×</button>
                </div>
            </div>
        </div>
    `;
        }).join('');

        if (lastValidation) {
            markInvalidItems(lastValidation);
        }
    };

    const buildValidationPayloadItems = () => {
        return [...cart.values()].map((item) => {
            if (item.type === 'menu') {
                return {
                    type: 'menu',
                    menu_id: Number(item.menuId),
                    quantity: Number(item.quantity),
                    choices: (item.choices || []).map((choice) => ({
                        choice_group_id: Number(choice.choice_group_id),
                        variant_id: Number(choice.variant_id),
                        quantity: Number(choice.quantity),
                    })),
                };
            }

            return {
                type: 'product',
                variant_id: Number(item.variantId),
                quantity: Number(item.quantity),
            };
        });
    };

    const extractValidationErrorMessage = (result, data) => {
        const firstError = Array.isArray(data.errors) && data.errors.length > 0 ?
            data.errors[0] :
            null;

        const code =
            firstError?.code ||
            data.code ||
            data.error_code ||
            result.code ||
            null;

        return (
            firstError?.message ||
            data.message ||
            result.message ||
            getErrorMessage(code)
        );
    };

    const updateCheckoutButtonState = () => {
        const hasItems = getCartCount() > 0;
        const currentFingerprint = buildCartFingerprint();
        const isStillValidated = Boolean(checkoutUrl && validatedCartFingerprint === currentFingerprint);

        cartCheckoutButton.disabled = !hasItems || isValidating;
        cartCheckoutButton.textContent = isStillValidated ? 'Zur Kasse' : 'Weiter zur Kasse';
    };

    const validateCart = async () => {
        const items = buildValidationPayloadItems();

        if (items.length === 0 || isValidating) {
            return;
        }

        isValidating = true;
        checkoutUrl = null;
        validatedCartFingerprint = null;
        cartCheckoutButton.disabled = true;
        cartCheckoutButton.textContent = 'Warenkorb wird geprüft...';

        if (cartValidationMessage) {
            cartValidationMessage.hidden = true;
            cartValidationMessage.className = 'cart-validation-message';
            cartValidationMessage.textContent = '';
        }

        if (cartValidatedTotals) {
            cartValidatedTotals.hidden = true;
        }

        try {
            const response = await fetch(cartValidateUrl, {

                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    shop_id: shopId,
                    payment_method: 'cash',
                    items,
                }),
            });

            const result = await response.json().catch(() => ({
                ok: false,
                message: 'Die Antwort der Warenkorb-Prüfung konnte nicht gelesen werden.',
                data: null,
            }));

            const data = result.data || {};
            lastValidation = data;

            console.log('Kioskheld cart validation result', {
                result,
                data,
                valid: data.valid,
                errors: data.errors,
                totals: data.totals,
                reservation: data.reservation,
                items: data.items,
            });

            const isValid = data.valid === true || result.valid === true;

            if (data.totals) {
                renderValidatedTotals(data.totals);
            }

            if (isValid && result.ok === true) {
                setValidationMessage('success', 'Preise und Lieferkosten wurden geprüft.');

                if (cartSummaryHint) {
                    cartSummaryHint.textContent =
                        'Dein Warenkorb wurde geprüft. Du kannst jetzt zur Kasse gehen.';
                }

                if (result.checkout_url) {
                    checkoutUrl = result.checkout_url;
                    validatedCartFingerprint = buildCartFingerprint();
                }

                return;
            }

            checkoutUrl = null;
            validatedCartFingerprint = null;

            const message = extractValidationErrorMessage(result, data);

            if (data.totals?.missing_minimum_order_value > 0) {
                setValidationMessage(
                    'warning',
                    `Der Mindestbestellwert ist noch nicht erreicht. Es fehlen ${formatter.format(Number(data.totals.missing_minimum_order_value))} Warenwert. Pfand zählt nicht zum Mindestbestellwert.`
                );
            } else {
                setValidationMessage('error', message);
            }

            markInvalidItems(data);

            if (cartSummaryHint) {
                cartSummaryHint.textContent =
                    'Bitte passe deinen Warenkorb an. Danach wird erneut geprüft.';
            }
        } catch (error) {
            checkoutUrl = null;
            validatedCartFingerprint = null;

            setValidationMessage(
                'error',
                'Die Warenkorb-Prüfung ist gerade nicht erreichbar. Bitte versuche es erneut.'
            );

            if (cartSummaryHint) {
                cartSummaryHint.textContent =
                    'Die Prüfung konnte nicht abgeschlossen werden. Bitte versuche es erneut.';
            }
        } finally {
            isValidating = false;
            updateCheckoutButtonState();
        }
    };

    document.querySelectorAll('.add-to-cart').forEach((button) => {
        button.addEventListener('click', () => {
            const variantId = button.dataset.variantId;
            const isAvailable = button.dataset.isAvailable !== '0';
            const availableQuantityRaw = button.dataset.availableQuantity;
            const availableQuantity = availableQuantityRaw === '' ? null : Number(availableQuantityRaw);

            if (!variantId) {
                return;
            }

            if (!isAvailable) {
                openCart();
                setValidationMessage('error', 'Dieser Artikel ist aktuell nicht verfügbar.');
                return;
            }

            resetValidationUi();

            const cartKey = `product:${variantId}`;
            const existing = cart.get(cartKey);

            const currentQuantity = existing?.quantity || 0;
            const hasFrontendLimit = availableQuantity !== null &&
                availableQuantity !== undefined &&
                availableQuantity > 0;

            if (hasFrontendLimit && currentQuantity >= availableQuantity) {
                openCart();
                setValidationMessage(
                    'warning',
                    `Von diesem Artikel sind aktuell nur ${availableQuantity} verfügbar.`
                );
                return;
            }

            if (existing) {
                existing.quantity += 1;
                cart.set(cartKey, existing);
            } else {
                cart.set(cartKey, {
                    cartKey,
                    type: 'product',
                    variantId,
                    productId: button.dataset.productId,
                    name: button.dataset.productName || 'Produkt',
                    price: Number(button.dataset.price || 0),
                    depositAmount: Number(button.dataset.depositAmount || 0),
                    depositLabel: button.dataset.depositLabel || 'Pfand',
                    imageUrl: button.dataset.imageUrl || '',
                    availableQuantity,
                    quantity: 1,
                });
            }

            renderCart();
            openCart();
        });
    });

    document.querySelectorAll('.add-menu-to-cart').forEach((button) => {
        button.addEventListener('click', () => {
            const menu = getMenuById(button.dataset.menuId);

            if (!menu) {
                openCart();
                setValidationMessage('error', 'Dieses Sparpaket konnte nicht geladen werden.');
                return;
            }

            activeMenu = menu;
            activeMenuSelections = new Map();

            renderMenuChoiceDrawer();
            openMenuChoiceDrawer();
        });
    });

    cartItems.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-action]');

        if (!button) {
            return;
        }

        const cartKey = decodeURIComponent(button.dataset.cartKey || '');
        const action = button.dataset.action;
        const item = cart.get(cartKey);

        if (!item) {
            return;
        }

        resetValidationUi();

        if (action === 'increase') {
            item.quantity += 1;
            cart.set(cartKey, item);
        }

        if (action === 'decrease') {
            item.quantity -= 1;

            if (item.quantity <= 0) {
                cart.delete(cartKey);
            } else {
                cart.set(cartKey, item);
            }
        }

        if (action === 'remove') {
            cart.delete(cartKey);
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeCart();
                closeMenuChoiceDrawer();
            }
        });


        renderCart();
    });

    cartFloatingButton.addEventListener('click', openCart);
    cartCloseButton.addEventListener('click', closeCart);
    cartBackdrop.addEventListener('click', closeCart);

    cartCheckoutButton.addEventListener('click', async () => {
        const currentFingerprint = buildCartFingerprint();

        if (checkoutUrl && validatedCartFingerprint === currentFingerprint) {
            window.location.href = checkoutUrl;
            return;
        }

        await validateCart();
    });

    menuChoiceCloseButton.addEventListener('click', closeMenuChoiceDrawer);
    menuChoiceBackdrop.addEventListener('click', closeMenuChoiceDrawer);
    menuChoiceAddButton.addEventListener('click', addActiveMenuToCart);

    menuChoiceGroups.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-menu-choice-action]');

        if (!button) {
            return;
        }

        updateMenuChoiceQuantity(
            button.dataset.choiceGroupId,
            button.dataset.variantId,
            button.dataset.menuChoiceAction
        );
    });

    renderCart();
});
