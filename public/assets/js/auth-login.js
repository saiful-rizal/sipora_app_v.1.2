



// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyBxnwFSK4HFjCpHu2ZtTT3dqExVhCiYStM",
  authDomain: "sipora-73236.firebaseapp.com",
  databaseURL: "https://sipora-73236-default-rtdb.firebaseio.com",
  projectId: "sipora-73236",
  storageBucket: "sipora-73236.firebasestorage.app",
  messagingSenderId: "301258608651",
  appId: "1:301258608651:web:7c44eda49b214673b0b70e",
  measurementId: "G-ER3FCLJRP5"
};

function loadFirebaseSdk(callback) {
    if (window.firebase && window.firebase.auth) {
        callback();
        return;
    }
    var script = document.createElement('script');
    script.src = 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js';
    script.onload = function () {
        var script2 = document.createElement('script');
        script2.src = 'https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js';
        script2.onload = callback;
        document.head.appendChild(script2);
    };
    document.head.appendChild(script);
}

function isPolijeEmail(email) {
    return /@(student\.)?polije\.ac\.id$/i.test(email);
}

function processGoogleUser(user) {
    if (!user) {
        return Promise.resolve();
    }

    if (!isPolijeEmail(user.email)) {
        alert('Hanya email polije.ac.id yang diizinkan.');
        return firebase.auth().signOut();
    }

    return user.getIdToken().then(function (token) {
        sendTokenToBackend(token);
    });
}

function handleRedirectResult() {
    return firebase.auth().getRedirectResult()
        .then(function (result) {
            if (!result || !result.user) {
                return;
            }

            return processGoogleUser(result.user);
        })
        .catch(function (error) {
            if (error && error.code === 'auth/popup-closed-by-user') {
                return;
            }

            alert('Login Google gagal: ' + ((error && error.message) || error));
        });
}

function showGoogleButton() {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'google-signin-btn';

    btn.innerHTML = `
        <div class="google-btn-content">
            <img class="google-icon"
                 src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                 alt="Google">
            <span class="google-btn-text">Masuk dengan Google</span>
        </div>
    `;

    btn.onclick = handleGoogleSignIn;

    var container = document.getElementById('googleSignInButton');
    if (container) {
        container.innerHTML = '';
        container.appendChild(btn);
    }

    // Inject style
    if (!document.getElementById('google-signin-btn-style')) {
        var style = document.createElement('style');
        style.id = 'google-signin-btn-style';

        style.innerHTML = `
        .google-signin-btn {
            width: 100%;
            max-width: 420px;
            min-width: 200px;
            height: 44px;
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            cursor: pointer;
            padding: 0 16px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .google-signin-btn:hover {
            background: #f8f9fa;
            border-color: #c6c6c6;
        }

        .google-btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: 100%;
        }

        .google-icon {
            width: 20px;
            height: 20px;
        }

        .google-btn-text {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #3c4043;
        }
        `;

        document.head.appendChild(style);
    }
}

function handleGoogleSignIn() {
    var provider = new firebase.auth.GoogleAuthProvider();
    provider.setCustomParameters({
        hd: 'polije.ac.id',
        prompt: 'select_account'
    });
    firebase.auth().signInWithPopup(provider)
        .then(function (result) {
            return processGoogleUser(result.user);
        })
        .catch(function (error) {
            // Fallback ke signInWithRedirect jika popup diblokir
            if (error && error.code === 'auth/popup-blocked') {
                firebase.auth().signInWithRedirect(provider);
            } else if (error && error.code === 'auth/popup-closed-by-user') {
                alert('Login dibatalkan. Silakan klik tombol Google dan selesaikan proses login.');
            } else {
                alert('Login Google gagal: ' + (error.message || error));
            }
        });
}

function sendTokenToBackend(token) {
    fetch(document.body.dataset.googleAuthEndpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.body.dataset.csrfToken
        },
        body: JSON.stringify({
            token: token
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/dashboard';
            } else {
                if (data.message && data.message.toLowerCase().includes('polije')) {
                    alert('Hanya akun polije.ac.id yang diizinkan.');
                } else if (data.message && data.message.toLowerCase().includes('authorization')) {
                    alert('Akses Google ditolak. Pastikan Anda memilih akun polije.ac.id.');
                } else {
                    alert(data.message || 'Login Google gagal.');
                }
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan saat login Google.');
        });
}

document.addEventListener('DOMContentLoaded', function () {
    loadFirebaseSdk(function () {
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        showGoogleButton();
        handleRedirectResult();
    });
});
