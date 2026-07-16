document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.language-switcher').forEach((switcher) => {
        const trigger = switcher.querySelector('.language-switcher-trigger');

        if (!trigger) {
            return;
        }

        const close = () => {
            switcher.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        };

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();

            const willOpen = !switcher.classList.contains('is-open');

            document.querySelectorAll('.language-switcher.is-open').forEach((item) => {
                item.classList.remove('is-open');

                item.querySelector('.language-switcher-trigger')
                    ?.setAttribute('aria-expanded', 'false');
            });

            if (willOpen) {
                switcher.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        switcher.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                close();
                trigger.focus();
            }
        });

        document.addEventListener('click', (event) => {
            if (!switcher.contains(event.target)) {
                close();
            }
        });
    });
});
