const notificationsEl = document.getElementById('notificationsData');
const notificationsData = (() => {
    if (!notificationsEl) return [];
    try {
        return JSON.parse(notificationsEl.dataset.items || '[]');
    } catch (e) {
        return [];
    }
})();

function injectNavPageTransitionStyle() {
    if (document.getElementById('nav-page-transition-style')) return;

    const style = document.createElement('style');
    style.id = 'nav-page-transition-style';
    style.textContent =
        'body {' +
        '  opacity: 0;' +
        '  transform: translateY(2px);' +
        '  transition: opacity .14s linear, transform .14s ease-out;' +
        '  will-change: opacity, transform;' +
        '}' +
        'body.page-ready {' +
        '  opacity: 1;' +
        '  transform: translateY(0);' +
        '}' +
        'body.page-leaving {' +
        '  opacity: 0;' +
        '  transform: translateY(2px);' +
        '  pointer-events: none;' +
        '}' +
        '@media (prefers-reduced-motion: reduce) {' +
        '  body, body.page-ready, body.page-leaving {' +
        '    opacity: 1;' +
        '    transform: none;' +
        '    transition: none;' +
        '  }' +
        '}';

    document.head.appendChild(style);
}

function shouldHandleNavTransitionClick(event, link) {
    if (!link || !link.href) return false;
    if (event.defaultPrevented || event.button !== 0) return false;
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
    if (link.target && link.target.toLowerCase() === '_blank') return false;

    const href = link.getAttribute('href') || '';
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return false;

    const targetUrl = new URL(link.href, window.location.href);
    if (targetUrl.origin !== window.location.origin) return false;

    const current = window.location.pathname + window.location.search + window.location.hash;
    const next = targetUrl.pathname + targetUrl.search + targetUrl.hash;
    if (current === next) return false;

    return true;
}

injectNavPageTransitionStyle();

document.addEventListener('DOMContentLoaded', function () {
    requestAnimationFrame(function () {
        document.body.classList.add('page-ready');
    });

    window.addEventListener('pageshow', function () {
        document.body.classList.remove('page-leaving');
        document.body.classList.add('page-ready');
    });

    document.querySelectorAll('.nav-links a').forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (!shouldHandleNavTransitionClick(event, link)) return;

            event.preventDefault();
            document.body.classList.remove('page-ready');
            document.body.classList.add('page-leaving');

            setTimeout(function () {
                window.location.assign(link.href);
            }, 95);
        });
    });

    let dismissedNotifications = [];
    try {
        dismissedNotifications = JSON.parse(localStorage.getItem('dismissedNotifications') || '[]');
    } catch (e) {
        dismissedNotifications = [];
    }

    /* ═══════════════════════════════════════
       MOUSE-TRACKING RADIAL GLOW pada nav links
       ═══════════════════════════════════════ */
    document.querySelectorAll('.nav-links a').forEach(function (link) {
        link.addEventListener('mousemove', function (e) {
            var rect = link.getBoundingClientRect();
            var x = ((e.clientX - rect.left) / rect.width * 100);
            var y = ((e.clientY - rect.top) / rect.height * 100);
            link.style.setProperty('--mx', x + '%');
            link.style.setProperty('--my', y + '%');
        });

        link.addEventListener('mouseleave', function () {
            link.style.setProperty('--mx', '50%');
            link.style.setProperty('--my', '50%');
        });
    });

    /* ═══════════════════════════════════════
       NOTIFICATION LOGIC
       ═══════════════════════════════════════ */
    function getNotifKey(notif) {
        var type = notif.type || 'unknown';
        var docId = notif.doc_id || notif.dokumen_id || '';
        var statusId = (typeof notif.status_id !== 'undefined') ? notif.status_id : '';
        var ts = notif.timestamp || notif.tanggal || notif.tgl_unggah || '';
        return type + '_' + docId + '_' + statusId + '_' + ts;
    }

    function updateNotificationUI() {
        var items = document.querySelectorAll('#notificationList .notification-item, #allNotificationsList .notification-item');
        items.forEach(function (item) {
            var idx = parseInt(item.getAttribute('data-index'));
            var notif = notificationsData[idx];
            if (!notif) return;
            var key = getNotifKey(notif);
            if (dismissedNotifications.includes(key)) {
                item.classList.remove('unread');
                item.classList.add('read');
            }
        });

        var unreadItems = document.querySelectorAll('#notificationList .notification-item.unread');
        var count = unreadItems.length;
        var badge = document.getElementById('notificationCount');
        if (badge) {
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    updateNotificationUI();

    /* ═══════════════════════════════════════
       DROPDOWN CONTROLS
       ═══════════════════════════════════════ */
    var mobileMenuBtn = document.getElementById('mobileMenuBtn');
    var navLinks = document.getElementById('navLinks');
    var notificationIcon = document.getElementById('notificationIcon');
    var notificationDropdown = document.getElementById('notificationDropdown');
    var userAvatarContainer = document.getElementById('userAvatarContainer');
    var userDropdown = document.getElementById('userDropdown');

    var isOpen = { notification: false, user: false, mobile: false };

    function closeAllDropdowns() {
        if (notificationDropdown) {
            notificationDropdown.classList.remove('show');
            isOpen.notification = false;
        }
        if (userDropdown) {
            userDropdown.classList.remove('show');
            isOpen.user = false;
        }
        if (navLinks) {
            navLinks.classList.remove('show');
            isOpen.mobile = false;
        }
    }

    function toggleDropdown(target, key) {
        var wasOpen = isOpen[key];
        closeAllDropdowns();
        if (!wasOpen && target) {
            target.classList.add('show');
            isOpen[key] = true;
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            toggleDropdown(navLinks, 'mobile');
        });
    }

    if (notificationIcon) {
        notificationIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown(notificationDropdown, 'notification');
        });
    }

    if (userAvatarContainer) {
        userAvatarContainer.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown(userDropdown, 'user');
        });
    }

    document.addEventListener('click', closeAllDropdowns);

    /* Keyboard: Escape tutup semua dropdown & modal */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
            document.querySelectorAll('.modal.show').forEach(function (m) {
                m.classList.remove('show');
            });
        }
    });

    /* ═══════════════════════════════════════
       MODAL CONTROLS
       ═══════════════════════════════════════ */
    window.openProfileModal = function () {
        closeModal('helpModal');
        closeModal('notificationDetailModal');
        var m = document.getElementById('profileModal');
        if (m) m.classList.add('show');
        closeAllDropdowns();
    };

    window.openHelpModal = function () {
        closeModal('profileModal');
        closeModal('notificationDetailModal');
        var m = document.getElementById('helpModal');
        if (m) m.classList.add('show');
        closeAllDropdowns();
    };

    window.closeModal = function (modalId) {
        var m = document.getElementById(modalId);
        if (m) m.classList.remove('show');
    };

    window.showAllNotifications = function () {
        closeModal('profileModal');
        closeModal('helpModal');
        closeModal('notificationDetailModal');
        var m = document.getElementById('allNotificationsModal');
        if (m) m.classList.add('show');
        closeAllDropdowns();
        updateNotificationUI();
    };

    function markNotificationAsRead(index) {
        var notif = notificationsData[index];
        if (!notif) return;
        var key = getNotifKey(notif);
        if (dismissedNotifications.indexOf(key) === -1) {
            dismissedNotifications.push(key);
            try {
                localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
            } catch (e) { /* ignore */ }
        }
        updateNotificationUI();
    }

    window.showNotificationDetail = function (index) {
        var notif = notificationsData[index];
        if (!notif) return;
        var icon = document.getElementById('notifDetailIcon');
        if (icon) {
            icon.className = 'notification-detail-icon notification-icon-wrapper ' + notif.icon_type;
            icon.innerHTML = '<i class="bi ' + notif.icon_class + '"></i>';
        }
        var titleEl = document.getElementById('notifDetailTitle');
        var timeEl = document.getElementById('notifDetailTime');
        var msgEl = document.getElementById('notifDetailMessage');
        if (titleEl) titleEl.textContent = notif.title;
        if (timeEl) timeEl.textContent = notif.time;
        if (msgEl) msgEl.innerHTML = notif.message;
        closeModal('allNotificationsModal');
        closeModal('helpModal');
        var m = document.getElementById('notificationDetailModal');
        if (m) m.classList.add('show');
        markNotificationAsRead(index);
    };

    function clearNotificationsUI() {
        document.querySelectorAll('.notification-item').forEach(function (item) { item.remove(); });
        var badge = document.getElementById('notificationCount');
        if (badge) badge.style.display = 'none';
        var emptyHTML = '<div class="notification-empty"><i class="bi bi-bell-slash"></i><p>Tidak ada notifikasi baru.</p></div>';
        var list = document.getElementById('notificationList');
        if (list) list.innerHTML = emptyHTML;
        var allList = document.getElementById('allNotificationsList');
        if (allList) allList.innerHTML = emptyHTML;
    }

    /* ═══════════════════════════════════════
       TOAST NOTIFICATION — premium style
       ═══════════════════════════════════════ */
    function showNotificationMessage(message, type) {
        type = type || 'success';

        var toast = document.createElement('div');
        toast.className = 'nav-toast nav-toast-' + type;

        var iconName = type === 'error' ? 'exclamation-circle-fill' : 'check-circle-fill';
        toast.innerHTML = '<i class="bi bi-' + iconName + '"></i><span>' + message + '</span>';

        /* Inject style jika belum ada */
        if (!document.getElementById('nav-toast-style')) {
            var style = document.createElement('style');
            style.id = 'nav-toast-style';
            style.textContent =
                '.nav-toast {' +
                '  position:fixed;top:24px;right:24px;' +
                '  background:linear-gradient(135deg,#22c55e,#14b8a6);' +
                '  color:#fff;padding:14px 22px;border-radius:14px;' +
                '  box-shadow:0 8px 32px rgba(0,0,0,.15),0 0 0 1px rgba(255,255,255,.1) inset;' +
                '  display:flex;align-items:center;gap:10px;z-index:9999;' +
                '  font-size:13px;font-weight:600;font-family:Inter,sans-serif;' +
                '  animation:toastIn .4s cubic-bezier(.34,1.56,.64,1) forwards;' +
                '  backdrop-filter:blur(8px);' +
                '}' +
                '.nav-toast-error { background:linear-gradient(135deg,#f43f5e,#e11d48); }' +
                '.nav-toast.removing { animation:toastOut .3s ease forwards; }' +
                '@keyframes toastIn {' +
                '  0% { opacity:0; transform:translateY(-20px) scale(.9); }' +
                '  100% { opacity:1; transform:translateY(0) scale(1); }' +
                '}' +
                '@keyframes toastOut {' +
                '  0% { opacity:1; transform:translateY(0) scale(1); }' +
                '  100% { opacity:0; transform:translateY(-16px) scale(.95); }' +
                '}';
            document.head.appendChild(style);
        }

        document.body.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('removing');
            setTimeout(function () {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 2500);
    }

    window.clearAllNotifications = function () {
        dismissedNotifications = notificationsData.map(getNotifKey);
        try {
            localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
        } catch (e) { /* ignore */ }
        clearNotificationsUI();
        showNotificationMessage('Daftar notifikasi telah dibersihkan.');
    };

    window.markAllAsRead = function () {
        var visible = document.querySelectorAll('#notificationList .notification-item');
        visible.forEach(function (item) {
            var idx = parseInt(item.getAttribute('data-index'));
            var notif = notificationsData[idx];
            if (!notif) return;
            var key = getNotifKey(notif);
            if (dismissedNotifications.indexOf(key) === -1) {
                dismissedNotifications.push(key);
            }
        });
        try {
            localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
        } catch (e) { /* ignore */ }
        updateNotificationUI();
        showNotificationMessage('Semua notifikasi telah ditandai sebagai dibaca');
    };

    /* ═══════════════════════════════════════
       ACCORDION — smooth toggle
       ═══════════════════════════════════════ */
    window.toggleAccordion = function (button) {
        var item = button.closest('.accordion-item');
        var collapse = item.querySelector('.accordion-collapse');
        var expanded = collapse.classList.contains('show');

        /* Tutup semua dulu */
        document.querySelectorAll('.accordion-collapse.show').forEach(function (el) {
            el.classList.remove('show');
        });

        /* Buka yang diklik jika sebelumnya tertutup */
        if (!expanded) {
            collapse.classList.add('show');
        }
    };

    /* ═══════════════════════════════════════
       LOGOUT
       ═══════════════════════════════════════ */
    window.submitLogout = function () {
        var form = document.getElementById('logoutForm');
        if (form) form.submit();
    };

    /* ═══════════════════════════════════════
       CLICK OUTSIDE MODAL to close
       ═══════════════════════════════════════ */
    document.querySelectorAll('.modal').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    });

    /* ═══════════════════════════════════════
       STAGGERED ENTRY — notif items muncul
       satu per satu saat dropdown buka
       ═══════════════════════════════════════ */
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            if (m.target.id === 'notificationDropdown' && m.target.classList.contains('show')) {
                var items = m.target.querySelectorAll('.notification-item');
                items.forEach(function (item, i) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(8px)';
                    setTimeout(function () {
                        item.style.transition = 'opacity .3s ease, transform .3s cubic-bezier(.34,1.56,.64,1)';
                        item.style.opacity = item.classList.contains('read') ? '.45' : '1';
                        item.style.transform = 'translateY(0)';
                    }, i * 40);
                });
            }
        });
    });

    var notifDrop = document.getElementById('notificationDropdown');
    if (notifDrop) {
        observer.observe(notifDrop, { attributes: true, attributeFilter: ['class'] });
    }
});
