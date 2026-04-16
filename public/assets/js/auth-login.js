(function () {
    function normalizeLocalDevHost() {
        if (window.location.hostname !== '127.0.0.1') return;

        const port = window.location.port ? ':' + window.location.port : '';
        const targetUrl =
            window.location.protocol +
            '//localhost' +
            port +
            window.location.pathname +
            window.location.search +
            window.location.hash;

        window.location.replace(targetUrl);
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function handleGoogleSignIn(idToken, config) {
        fetch(config.googleAuthEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken,
            },
            body: JSON.stringify({ token: idToken, id_token: idToken }),
        })
            .then((res) => res.json().then((data) => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (ok && data.success) {
                    window.location.href = data.redirect_to || config.dashboardUrl;
                    return;
                }

                const message = data && data.message ? data.message : 'Login Google gagal.';
                throw new Error(message);
            })
            .catch((err) => {
                alert(err && err.message ? err.message : 'Terjadi kesalahan saat login dengan Google');
            });
    }

    function injectGoogleButtonStyle() {
        if (document.getElementById('firebase-google-btn-style')) return;

        const style = document.createElement('style');
        style.id = 'firebase-google-btn-style';
        style.textContent =
            '#googleSignInButton {' +
            '  width: 100%;' +
            '}' +
            '.firebase-google-btn {' +
            '  width: 100%;' +
            '  max-width: none;' +
            '  margin: 0;' +
            '  border: 1px solid #dadce0;' +
            '  border-radius: 6px;' +
            '  background: #ffffff;' +
            '  color: #3c4043;' +
            '  height: 44px;' +
            '  display: inline-flex;' +
            '  align-items: center;' +
            '  justify-content: center;' +
            '  gap: 10px;' +
            '  font-size: 14px;' +
            '  font-weight: 500;' +
            '  font-family: Roboto, Arial, sans-serif;' +
            '  cursor: pointer;' +
            '  box-shadow: none;' +
            '  transition: border-color .15s ease, background-color .15s ease;' +
            '}' +
            '.firebase-google-btn:hover {' +
            '  border-color: #c7cacf;' +
            '  background: #fcfcfc;' +
            '}' +
            '.firebase-google-btn:disabled {' +
            '  opacity: .7;' +
            '  cursor: not-allowed;' +
            '  transform: none;' +
            '}' +
            '.firebase-google-btn .google-logo {' +
            '  width: 18px;' +
            '  height: 18px;' +
            '  display: block;' +
            '}' +
            '.firebase-google-btn span {' +
            '  letter-spacing: .1px;' +
            '}';

        document.head.appendChild(style);
    }

    function buildGoogleButton() {
        const signInContainer = document.getElementById('googleSignInButton');
        if (!signInContainer) return null;

        injectGoogleButtonStyle();
        signInContainer.innerHTML = '';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'firebase-google-btn';
        btn.innerHTML = '<img class="google-logo" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"><span>Sign in with Google</span>';

        signInContainer.appendChild(btn);
        return btn;
    }

    /* ==============================================
       FIX: SPLASH SCREEN - TUNGGU 100% (NO AUTO-DISMISS)
       ============================================== */

    // Global flag untuk tracking loading status
    window.splashLoadingComplete = false;

    function initSplash() {
        const splashScreen = document.getElementById('splash-screen');
        const loginContainer = document.querySelector('.login-container');

        // Keamanan: Jika splash screen tidak ada, langsung tampilkan login
        if (!splashScreen || !loginContainer) {
            if (loginContainer) {
                loginContainer.style.opacity = '1';
                loginContainer.classList.add('visible');
            }
            return;
        }

        console.log('[auth-login.js] Splash screen initialized');
        console.log('[auth-login.js] Waiting for loading to reach 100%...');

        // ⭐ PERUBAHAN: Tidak ada lagi auto-dismiss berdasarkan waktu!
        // Sebagai gantinya, kita setup listener untuk menunggu flag dari inline script

        function checkLoadingComplete() {
            // Cek apakah inline script sudah set flag ke true
            if (window.splashLoadingComplete === true) {
                console.log('[auth-login.js] ✅ Loading complete! Dismiss splash...');
                dismissSplash();
                return true;
            }
            return false;
        }

        function dismissSplash() {
            // Double check
            if (!splashScreen || splashScreen.dataset.dismissed === 'true') return;

            console.log('[auth-login.js] 🎬 Executing dismiss...');

            // Mark as dismissed
            splashScreen.dataset.dismissed = 'true';

            // Trigger fade out
            splashScreen.classList.add('splash-hide');

            // Show login form
            if (loginContainer) {
                loginContainer.style.opacity = '1';
                loginContainer.classList.add('visible');
            }

            // Remove from DOM after animation
            setTimeout(function () {
                if (splashScreen && splashScreen.parentNode) {
                    splashScreen.parentNode.removeChild(splashScreen);
                    console.log('[auth-login.js] ✨ Splash removed from DOM, login form visible!');
                }
            }, 900);
        }

        // Polling setiap 100ms untuk cek status loading (fallback)
        // Ini hanya sebagai backup, utamanya inline script yang trigger
        var pollInterval = setInterval(function () {
            if (checkLoadingComplete()) {
                clearInterval(pollInterval);
            }
        }, 100);

        // Safety net: Maximum wait 15 detik (untuk mencegah infinite loop jika error)
        setTimeout(function () {
            clearInterval(pollInterval);
            if (!splashScreen.dataset.dismissed && window.splashLoadingComplete !== true) {
                console.warn('[auth-login.js] ⚠️ Safety timeout reached, checking current percent...');
                // Cek persentase terakhir
                var percentEl = document.querySelector('.loader-percentage');
                var currentPercent = percentEl ? percentEl.textContent : 'unknown';
                console.log('[auth-login.js] Current percentage:', currentPercent);

                // Jika masih belum complete tapi sudah lama, force complete
                if (window.splashLoadingComplete !== true) {
                    console.log('[auth-login.js] Forcing completion due to timeout...');
                    window.splashLoadingComplete = true;
                    dismissSplash();
                }
            }
        }, 15000); // 15 detik max wait
    }

    function initGoogle(config) {
        const hasFirebase = typeof firebase !== 'undefined' && firebase.auth;
        const firebaseConfig = config.firebaseConfig || {};

        if (hasFirebase && firebaseConfig.apiKey && firebaseConfig.authDomain && firebaseConfig.projectId) {
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }

            const auth = firebase.auth();
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.setCustomParameters({ prompt: 'select_account' });

            auth.getRedirectResult()
                .then(function (result) {
                    if (result && result.user) {
                        return result.user.getIdToken(true).then(function (idToken) {
                            handleGoogleSignIn(idToken, config);
                        });
                    }
                    return null;
                })
                .catch(function () {
                    // Redirect result failure is non-blocking; normal click flow still works.
                });

            const googleButton = buildGoogleButton();
            if (!googleButton) return;

            googleButton.addEventListener('click', async function () {
                const buttonText = googleButton.querySelector('span');

                try {
                    googleButton.disabled = true;
                    if (buttonText) buttonText.textContent = 'Signing in...';

                    const result = await auth.signInWithPopup(provider);
                    const idToken = await result.user.getIdToken(true);
                    handleGoogleSignIn(idToken, config);
                } catch (error) {
                    if (error && error.code === 'auth/unauthorized-domain') {
                        const activeHost = window.location.hostname || 'domain-aktif';
                        alert(
                            'Domain ini belum diizinkan di Firebase: ' + activeHost + '\n\n' +
                            'Buka Firebase Console -> Authentication -> Settings -> Authorized domains, lalu tambahkan domain tersebut.\n' +
                            'Setelah disimpan, refresh halaman dan coba login lagi.'
                        );
                        return;
                    }

                    if (error && (error.code === 'auth/popup-blocked' || error.code === 'auth/cancelled-popup-request')) {
                        try {
                            if (buttonText) buttonText.textContent = 'Redirecting...';
                            await auth.signInWithRedirect(provider);
                            return;
                        } catch (redirectError) {
                            const redirectMessage = redirectError && redirectError.message
                                ? redirectError.message
                                : 'Login redirect Google gagal dijalankan.';
                            alert(redirectMessage);
                            return;
                        }
                    }

                    const message = error && error.message
                        ? error.message
                        : 'Popup login Google gagal dibuka.';
                    alert(message);
                } finally {
                    googleButton.disabled = false;
                    if (buttonText) buttonText.textContent = 'Sign in with Google';
                    auth.signOut().catch(function () {});
                }
            });

            return;
        }

        if (typeof google === 'undefined') return;

        const clientId = 'MASUKKAN_CLIENT_ID_ANDA_DISINI';
        google.accounts.id.initialize({
            client_id: clientId,
            callback: (response) => handleGoogleSignIn(response.credential, config),
            auto_select: false,
            cancel_on_tap_outside: false,
        });

        const signInContainer = document.getElementById('googleSignInButton');
        if (!signInContainer) return;

        google.accounts.id.renderButton(signInContainer, {
            theme: 'outline',
            size: 'large',
            text: 'signin_with',
            width: 250,
            logo_alignment: 'center',
        });

        setTimeout(() => google.accounts.id.prompt(), 1000);
    }

    window.togglePassword = togglePassword;

    window.addEventListener('load', function () {
        normalizeLocalDevHost();
        if (window.location.hostname === '127.0.0.1') {
            return;
        }

        const body = document.body;
        const config = {
            googleAuthEndpoint: body.dataset.googleAuthEndpoint || '',
            csrfToken: body.dataset.csrfToken || '',
            dashboardUrl: body.dataset.dashboardUrl || '/dashboard',
            firebaseConfig: window.firebaseWebConfig || {},
        };

        initSplash(); // Initialize splash (tapi tidak auto-dismiss!)
        initGoogle(config);
    });
})();
