document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#postcodeForm');
    const input = document.querySelector('#postcode');
    const toast = document.querySelector('#toast');

    if (!form || !input || !toast) {
        return;
    }

    const demoShopPostcodes = ['31224', '38100', '38102'];

    const showToast = (message) => {
        toast.textContent = message;
        toast.classList.add('show');

        window.setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    };

    const normalizePostcode = (value) => value.replace(/\D/g, '').slice(0, 5);

    input.addEventListener('input', () => {
        input.value = normalizePostcode(input.value);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const value = normalizePostcode(input.value);

        if (!/^\d{5}$/.test(value)) {
            showToast('Bitte eine gültige 5-stellige Postleitzahl eingeben.');
            input.focus();
            return;
        }

        if (demoShopPostcodes.includes(value)) {
            showToast('Demo: Kioskheld gefunden. Später geht es direkt zum Shop.');
            return;
        }

        showToast('Demo: Noch kein Kioskheld gefunden. Später Leadformular anzeigen.');
    });

    document.querySelectorAll('.plus').forEach((button) => {
        button.addEventListener('click', () => {
            showToast('Demo: Bundle wurde vorgemerkt.');
        });
    });
});
