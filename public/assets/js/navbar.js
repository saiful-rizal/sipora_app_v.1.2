const notificationsEl = document.getElementById('notificationsData');
const notificationsData = (() => {
    if (!notificationsEl) return [];
    try {
        return JSON.parse(notificationsEl.dataset.items || '[]');
    } catch (e) {
        return [];
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    let dismissedNotifications = [];
    try {
        dismissedNotifications = JSON.parse(localStorage.getItem('dismissedNotifications') || '[]');
    } catch (e) {
        dismissedNotifications = [];
    }

    function getNotifKey(notif) {
        const type = notif.type || 'unknown';
        const docId = notif.doc_id || notif.dokumen_id || '';
        const statusId = (typeof notif.status_id !== 'undefined') ? notif.status_id : '';
        const ts = notif.timestamp || notif.tanggal || notif.tgl_unggah || '';
        return `${type}_${docId}_${statusId}_${ts}`;
    }

    function updateNotificationUI() {
        const notificationItems = document.querySelectorAll('#notificationList .notification-item, #allNotificationsList .notification-item');
        notificationItems.forEach(item => {
            const idx = parseInt(item.getAttribute('data-index'));
            const notif = notificationsData[idx];
            if (!notif) return;
            const key = getNotifKey(notif);
            if (dismissedNotifications.includes(key)) {
                item.classList.remove('unread');
                item.classList.add('read');
            }
        });

        const unreadItems = document.querySelectorAll('#notificationList .notification-item.unread');
        const count = unreadItems.length;
        const badge = document.getElementById('notificationCount');
        if (badge) {
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    updateNotificationUI();

    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userAvatarContainer = document.getElementById('userAvatarContainer');
    const userDropdown = document.getElementById('userDropdown');

    function closeAllDropdowns() {
        if (notificationDropdown) notificationDropdown.classList.remove('show');
        if (userDropdown) userDropdown.classList.remove('show');
        if (navLinks) navLinks.classList.remove('show');
    }

    mobileMenuBtn?.addEventListener('click', () => navLinks?.classList.toggle('show'));

    notificationIcon?.addEventListener('click', function(e) {
        e.stopPropagation();
        closeAllDropdowns();
        notificationDropdown?.classList.toggle('show');
    });

    userAvatarContainer?.addEventListener('click', function(e) {
        e.stopPropagation();
        closeAllDropdowns();
        userDropdown?.classList.toggle('show');
    });

    document.addEventListener('click', closeAllDropdowns);

    window.openProfileModal = function() {
        closeModal('helpModal');
        document.getElementById('profileModal')?.classList.add('show');
        closeAllDropdowns();
    };

    window.openHelpModal = function() {
        closeModal('profileModal');
        document.getElementById('helpModal')?.classList.add('show');
        closeAllDropdowns();
    };

    window.closeModal = function(modalId) {
        document.getElementById(modalId)?.classList.remove('show');
    };

    window.showAllNotifications = function() {
        closeModal('profileModal');
        closeModal('helpModal');
        closeModal('notificationDetailModal');
        document.getElementById('allNotificationsModal')?.classList.add('show');
        closeAllDropdowns();
        updateNotificationUI();
    };

    function markNotificationAsRead(index) {
        const notif = notificationsData[index];
        if (!notif) return;
        const key = getNotifKey(notif);
        if (!dismissedNotifications.includes(key)) {
            dismissedNotifications.push(key);
            localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
        }
        updateNotificationUI();
    }

    window.showNotificationDetail = function(index) {
        const notif = notificationsData[index];
        if (!notif) return;
        const icon = document.getElementById('notifDetailIcon');
        icon.className = 'notification-detail-icon notification-icon-wrapper ' + notif.icon_type;
        icon.innerHTML = '<i class="bi ' + notif.icon_class + '"></i>';
        document.getElementById('notifDetailTitle').textContent = notif.title;
        document.getElementById('notifDetailTime').textContent = notif.time;
        document.getElementById('notifDetailMessage').innerHTML = notif.message;
        closeModal('allNotificationsModal');
        closeModal('helpModal');
        document.getElementById('notificationDetailModal')?.classList.add('show');
        markNotificationAsRead(index);
    };

    function clearNotificationsUI() {
        document.querySelectorAll('.notification-item').forEach(item => item.remove());
        const badge = document.getElementById('notificationCount');
        if (badge) badge.style.display = 'none';
        const list = document.getElementById('notificationList');
        if (list) list.innerHTML = '<div class="notification-empty"><i class="bi bi-bell-slash"></i><p>Tidak ada notifikasi baru.</p></div>';
        const allList = document.getElementById('allNotificationsList');
        if (allList) allList.innerHTML = '<div class="notification-empty"><i class="bi bi-bell-slash"></i><p>Tidak ada notifikasi.</p></div>';
    }

    function showNotificationMessage(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'error' ? 'linear-gradient(135deg, #dc3545, #c82333)' : 'linear-gradient(135deg, #28a745, #20c997)';
        const icon = type === 'error' ? '<i class="bi bi-exclamation-circle-fill"></i>' : '<i class="bi bi-check-circle-fill"></i>';
        toast.innerHTML = `${icon}<span>${message}</span>`;
        toast.style.cssText = 'position:fixed;top:20px;right:20px;background:' + bgColor + ';color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.2);display:flex;align-items:center;gap:.8rem;z-index:9999;';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2800);
    }

    window.clearAllNotifications = function() {
        dismissedNotifications = notificationsData.map(getNotifKey);
        localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
        clearNotificationsUI();
        showNotificationMessage('Daftar notifikasi telah dibersihkan.');
    };

    window.markAllAsRead = function() {
        const visible = Array.from(document.querySelectorAll('#notificationList .notification-item'));
        visible.forEach(item => {
            const idx = parseInt(item.getAttribute('data-index'));
            const notif = notificationsData[idx];
            if (!notif) return;
            const key = getNotifKey(notif);
            if (!dismissedNotifications.includes(key)) {
                dismissedNotifications.push(key);
            }
        });
        localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
        updateNotificationUI();
        showNotificationMessage('Semua notifikasi telah ditandai sebagai dibaca');
    };

    window.toggleAccordion = function(button) {
        const collapse = button.closest('.accordion-item').querySelector('.accordion-collapse');
        const expanded = collapse.classList.contains('show');
        document.querySelectorAll('.accordion-collapse').forEach(item => item.classList.remove('show'));
        if (!expanded) collapse.classList.add('show');
    };

    window.submitLogout = function() {
        document.getElementById('logoutForm')?.submit();
    };

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    });
});