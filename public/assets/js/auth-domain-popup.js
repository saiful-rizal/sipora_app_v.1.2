(function () {
    const defaultTitle = 'Gunakan Email Politeknik Negeri Jember';

    function closePopup(popup) {
        if (!popup) return;
        if (popup.dataset.autocloseTimer) {
            clearTimeout(Number(popup.dataset.autocloseTimer));
            delete popup.dataset.autocloseTimer;
        }
        popup.classList.remove('show');
        setTimeout(() => {
            if (popup && popup.parentNode) {
                popup.parentNode.removeChild(popup);
            }
        }, 180);
    }

    function ensurePopup() {
        let popup = document.getElementById('authDomainPopup');
        if (popup) {
            return popup;
        }

        popup = document.createElement('div');
        popup.id = 'authDomainPopup';
        popup.className = 'auth-domain-popup';
        popup.innerHTML = `
            <div class="auth-domain-popup__card" role="dialog" aria-modal="true" aria-labelledby="authDomainPopupTitle">
                <div class="auth-domain-popup__icon">!</div>
                <h3 id="authDomainPopupTitle">${defaultTitle}</h3>
                <p class="auth-domain-popup__message" id="authDomainPopupMessage">Gunakan email Politeknik Negeri Jember.</p>
                <button type="button" class="auth-domain-popup__button" id="authDomainPopupButton">Tutup</button>
            </div>
        `;

        document.body.appendChild(popup);

        const button = popup.querySelector('#authDomainPopupButton');
        if (button) {
            button.addEventListener('click', () => closePopup(popup));
        }

        popup.addEventListener('click', (event) => {
            if (event.target === popup) {
                closePopup(popup);
            }
        });

        return popup;
    }

    window.showAuthStatusPopup = function (options) {
        if (typeof document === 'undefined' || !document.body) return;

        const config = typeof options === 'string' ? { message: options } : (options || {});
        const type = config.type === 'success' ? 'success' : 'error';
        const title = config.title || (type === 'success' ? 'Berhasil' : defaultTitle);
        const message = config.message || (type === 'success' ? 'Berhasil.' : 'Gunakan email Politeknik Negeri Jember.');
        const autoCloseMs = typeof config.autoCloseMs === 'number' ? config.autoCloseMs : 5000;

        const popup = ensurePopup();
        const messageEl = popup.querySelector('#authDomainPopupMessage');
        const titleEl = popup.querySelector('#authDomainPopupTitle');
        const iconEl = popup.querySelector('.auth-domain-popup__icon');

        popup.classList.toggle('auth-domain-popup--success', type === 'success');
        popup.classList.toggle('auth-domain-popup--error', type !== 'success');

        if (titleEl) {
            titleEl.textContent = title;
        }

        if (messageEl) {
            messageEl.textContent = message;
        }

        if (iconEl) {
            iconEl.textContent = type === 'success' ? '✓' : '!';
        }

        popup.classList.add('show');

        if (popup.dataset.autocloseTimer) {
            clearTimeout(Number(popup.dataset.autocloseTimer));
        }

        popup.dataset.autocloseTimer = String(setTimeout(() => closePopup(popup), autoCloseMs));
    };

    window.showPolijeEmailPopup = function (message) {
        window.showAuthStatusPopup({
            type: 'error',
            title: defaultTitle,
            message: message || 'Gunakan email Politeknik Negeri Jember.',
            autoCloseMs: 5000,
        });
    };

    window.showAuthSuccessPopup = function (message, autoCloseMs) {
        window.showAuthStatusPopup({
            type: 'success',
            title: 'Berhasil',
            message: message || 'Berhasil.',
            autoCloseMs: typeof autoCloseMs === 'number' ? autoCloseMs : 1200,
        });
    };
})();
