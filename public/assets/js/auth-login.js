(function () {
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

    function handleGoogleSignIn(response, config) {
        fetch(config.googleAuthEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken,
            },
            body: JSON.stringify({ token: response.credential }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = config.dashboardUrl;
                } else {
                    alert('Login gagal: ' + data.message);
                }
            })
            .catch(() => alert('Terjadi kesalahan saat login dengan Google'));
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
        if (typeof google === 'undefined') return;

        const clientId = 'MASUKKAN_CLIENT_ID_ANDA_DISINI'; // Ganti dengan ID Anda
        google.accounts.id.initialize({
            client_id: clientId,
            callback: (response) => handleGoogleSignIn(response, config),
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
        const body = document.body;
        const config = {
            googleAuthEndpoint: body.dataset.googleAuthEndpoint || '',
            csrfToken: body.dataset.csrfToken || '',
            dashboardUrl: body.dataset.dashboardUrl || '/dashboard',
        };

        initSplash(); // Initialize splash (tapi tidak auto-dismiss!)
        initGoogle(config);
    });
})();
