@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Schema;

    $authUser = Auth::user();
    $sessionUser = session('auth_user');

    if ($authUser) {
        $userData = [
            'id_user' => $authUser->id_user ?? null,
            'username' => $authUser->username ?? ($authUser->name ?? 'Guest'),
            'email' => $authUser->email ?? '',
            'role' => $authUser->role ?? 'mahasiswa',
            'nama_lengkap' => $authUser->nama_lengkap ?? ($authUser->name ?? ''),
        ];
    } elseif ($sessionUser) {
        $userData = [
            'id_user' => $sessionUser['id_user'] ?? null,
            'username' => $sessionUser['username'] ?? 'Guest',
            'email' => $sessionUser['email'] ?? '',
            'role' => $sessionUser['role'] ?? 'mahasiswa',
            'nama_lengkap' => $sessionUser['nama_lengkap'] ?? '',
        ];
    } else {
        $userData = [
            'id_user' => 0,
            'username' => 'Guest',
            'email' => '',
            'role' => 'guest',
            'nama_lengkap' => '',
        ];
    }

    $userId = (int) ($userData['id_user'] ?? 0);
    $userRole = $userData['role'] ?? 'guest';
    $isAdmin = in_array($userRole, ['superadmin', 'admin', 1, '1', 'SuperAdmin'], true);

    $roleName = match ((string) $userRole) {
        'superadmin', 'SuperAdmin' => 'Super Admin',
        'admin', '1' => 'Administrator',
        'mahasiswa', '2' => 'Mahasiswa',
        default => 'Guest',
    };

    $profilePhoto = null;
    if ($userId > 0) {
        $profilePhoto = DB::table('user_profile')
            ->where('id_user', $userId)
            ->value('foto_profil');
    }

    $avatarUrl = $profilePhoto ? asset('uploads/profile/' . $profilePhoto) : null;

    $getInitials = function (string $name): string {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = '';
        foreach ($parts as $part) {
            if ($part !== '') {
                $initials .= mb_strtoupper(mb_substr($part, 0, 1));
            }
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }
        return $initials !== '' ? $initials : 'G';
    };

    $timeAgo = function (?string $datetime): string {
        if (!$datetime) {
            return 'Waktu tidak diketahui';
        }
        $time = strtotime($datetime);
        if (!$time) {
            return 'Waktu tidak diketahui';
        }
        $diff = time() - $time;
        if ($diff < 60) {
            return 'Baru saja';
        }
        if ($diff < 3600) {
            return floor($diff / 60) . ' menit yang lalu';
        }
        if ($diff < 86400) {
            return floor($diff / 3600) . ' jam yang lalu';
        }
        if ($diff < 2629743) {
            return floor($diff / 86400) . ' hari yang lalu';
        }
        return date('d M Y', $time);
    };

    $notifications = [];
    if ($userId > 0) {
        try {
            $userDocuments = DB::table('dokumen as d')
                ->leftJoin('master_status_dokumen as msd', 'd.status_id', '=', 'msd.status_id')
                ->where('d.uploader_id', $userId)
                ->select('d.dokumen_id', 'd.judul', 'd.status_id', 'd.tgl_unggah', 'msd.nama_status')
                ->orderByDesc('d.tgl_unggah')
                ->limit(30)
                ->get();

            foreach ($userDocuments as $doc) {
                $statusName = $doc->nama_status ?: 'Unknown';
                $iconType = 'info';
                $iconClass = 'bi-info-circle-fill';
                $title = 'Status Diperbarui';
                $message = 'Dokumen "' . e($doc->judul) . '" berstatus: <strong>' . e($statusName) . '</strong>';

                if ((int) $doc->status_id === 5) {
                    $iconType = 'success';
                    $iconClass = 'bi-check-circle-fill';
                    $title = 'Dokumen Diterbitkan';
                    $message = 'Dokumen "' . e($doc->judul) . '" <strong>telah diterbitkan</strong> dan tersedia untuk umum';
                } elseif ((int) $doc->status_id === 1) {
                    $iconType = 'info';
                    $iconClass = 'bi-clock-fill';
                    $title = 'Menunggu Persetujuan';
                    $message = 'Dokumen "' . e($doc->judul) . '" sedang <strong>menunggu persetujuan</strong> dari reviewer/admin';
                } elseif ((int) $doc->status_id === 4) {
                    $iconType = 'danger';
                    $iconClass = 'bi-x-circle-fill';
                    $title = 'Dokumen Ditolak';
                    $message = 'Dokumen "' . e($doc->judul) . '" <strong>ditolak</strong>. Silakan periksa kembali dokumen Anda';
                } elseif ((int) $doc->status_id === 2) {
                    $iconType = 'warning';
                    $iconClass = 'bi-hourglass-split';
                    $title = 'Sedang Direview';
                    $message = 'Dokumen "' . e($doc->judul) . '" sedang <strong>direview</strong> oleh tim';
                } elseif ((int) $doc->status_id === 3) {
                    $iconType = 'secondary';
                    $iconClass = 'bi-file-earmark-text-fill';
                    $title = 'Menunggu Publikasi';
                    $message = 'Dokumen "' . e($doc->judul) . '" masih dalam status <strong>Menunggu Publikasi</strong>';
                }

                $notifications[] = [
                    'title' => $title,
                    'message' => $message,
                    'time' => $timeAgo($doc->tgl_unggah),
                    'icon_type' => $iconType,
                    'icon_class' => $iconClass,
                    'doc_id' => $doc->dokumen_id,
                    'judul' => $doc->judul,
                    'status_id' => $doc->status_id,
                    'nama_status' => $statusName,
                    'type' => 'document_status',
                    'timestamp' => strtotime($doc->tgl_unggah ?? 'now'),
                ];
            }

            if ($isAdmin) {
                $newDocs = DB::table('dokumen as d')
                    ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
                    ->where('d.uploader_id', '!=', $userId)
                    ->where('d.tgl_unggah', '>', now()->subDays(7))
                    ->select('d.dokumen_id', 'd.judul', 'd.tgl_unggah', 'u.username as uploader_name')
                    ->orderByDesc('d.tgl_unggah')
                    ->limit(10)
                    ->get();

                foreach ($newDocs as $doc) {
                    $notifications[] = [
                        'title' => 'Dokumen Baru',
                        'message' => '<strong>' . e($doc->uploader_name ?? 'Unknown') . '</strong> mengunggah dokumen: "' . e($doc->judul) . '"',
                        'time' => $timeAgo($doc->tgl_unggah),
                        'icon_type' => 'info',
                        'icon_class' => 'bi-file-earmark-plus',
                        'doc_id' => $doc->dokumen_id,
                        'judul' => $doc->judul,
                        'uploader_name' => $doc->uploader_name,
                        'type' => 'new_document',
                        'timestamp' => strtotime($doc->tgl_unggah ?? 'now'),
                    ];
                }

                $pendingDocs = DB::table('dokumen as d')
                    ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
                    ->where('d.status_id', 2)
                    ->select('d.dokumen_id', 'd.judul', 'd.tgl_unggah', 'u.username as uploader_name')
                    ->orderByDesc('d.tgl_unggah')
                    ->limit(5)
                    ->get();

                foreach ($pendingDocs as $doc) {
                    $notifications[] = [
                        'title' => 'Dokumen Menunggu Review',
                        'message' => 'Dokumen "' . e($doc->judul) . '" dari <strong>' . e($doc->uploader_name ?? 'Unknown') . '</strong> menunggu review',
                        'time' => $timeAgo($doc->tgl_unggah),
                        'icon_type' => 'warning',
                        'icon_class' => 'bi-eye',
                        'doc_id' => $doc->dokumen_id,
                        'judul' => $doc->judul,
                        'uploader_name' => $doc->uploader_name,
                        'type' => 'pending_review',
                        'timestamp' => strtotime($doc->tgl_unggah ?? 'now'),
                    ];
                }
            }

            if (Schema::hasTable('download_history')) {
                $downloads = DB::table('download_history as dh')
                    ->join('dokumen as d', 'dh.dokumen_id', '=', 'd.dokumen_id')
                    ->leftJoin('users as u', 'dh.user_id', '=', 'u.id_user')
                    ->where('d.uploader_id', $userId)
                    ->where('dh.tanggal', '>', now()->subDays(7))
                    ->select('dh.tanggal', 'd.dokumen_id', 'd.judul', 'u.username as downloader_name')
                    ->orderByDesc('dh.tanggal')
                    ->limit(10)
                    ->get();

                foreach ($downloads as $download) {
                    $notifications[] = [
                        'title' => 'Dokumen Diunduh',
                        'message' => '<strong>' . e($download->downloader_name ?? 'Unknown') . '</strong> mengunduh dokumen: "' . e($download->judul) . '"',
                        'time' => $timeAgo($download->tanggal),
                        'icon_type' => 'primary',
                        'icon_class' => 'bi-download',
                        'doc_id' => $download->dokumen_id,
                        'judul' => $download->judul,
                        'downloader_name' => $download->downloader_name,
                        'type' => 'download',
                        'timestamp' => strtotime($download->tanggal ?? 'now'),
                    ];
                }
            }

            usort($notifications, fn($a, $b) => ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0));
            $notifications = array_slice($notifications, 0, 20);
        } catch (\Throwable $e) {
            $notifications = [];
        }
    }

    $notificationCount = count($notifications);
@endphp

<link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">
<!-- INLINE CSS MOVED TO public/assets/css/navbar.css
:root {
    --primary-color: #0058e4;
    --primary-dark: #0047c2;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --white: #ffffff;
    --gray-50: #fafbfc;
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --gray-400: #ced4da;
    --gray-500: #adb5bd;
    --gray-600: #6c757d;
    --gray-700: #495057;
    --gray-800: #343a40;
    --gray-900: #212529;
    --border-radius: 8px;
    --box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    --transition: all 0.3s ease;
}

nav {
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    padding: 0 1.5rem;
    position: sticky;
    top: 0;
    z-index: 1030;
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95);
    animation: slideDown 0.5s ease;
}

@keyframes slideDown {
    from { transform: translateY(-100%); }
    to { transform: translateY(0); }
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
    transition: var(--transition);
}

.brand:hover {
    transform: translateY(-2px);
}

.brand img {
    height: 40px;
    width: auto;
    border-radius: 6px;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-links a {
    color: var(--gray-600);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    position: relative;
    transition: var(--transition);
    padding: 0.5rem 0;
}

.nav-links a:hover, .nav-links a.active {
    color: var(--primary-color);
}

.nav-links a.active::after {
    content: '';
    position: absolute;
    bottom: -21px;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: var(--primary-color);
    border-radius: 3px 3px 0 0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
}

.user-avatar-container,
.dropdown-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    font-weight: 600;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(0, 88, 228, 0.2);
}

.user-avatar-container:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 88, 228, 0.3);
}

.user-avatar-container img,
.dropdown-avatar img,
.profile-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initial {
    font-size: 14px;
    font-weight: 700;
}

.notification-icon {
    position: relative;
    cursor: pointer;
    color: var(--gray-600);
    font-size: 1.3rem;
    transition: var(--transition);
    padding: 0.5rem;
    border-radius: 50%;
}

.notification-icon:hover {
    color: var(--primary-color);
    background-color: rgba(0, 88, 228, 0.1);
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: linear-gradient(135deg, var(--danger-color), #c82333);
    color: var(--white);
    border-radius: 50%;
    width: 12px;
    height: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.notification-dropdown,
.user-dropdown {
    position: absolute;
    top: calc(100% + 15px);
    right: 0;
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: none;
    border: 1px solid var(--gray-200);
    animation: slideDown 0.3s ease;
}

.notification-dropdown {
    width: 400px;
    max-height: 480px;
    flex-direction: column;
    overflow: hidden;
}

.notification-dropdown.show,
.user-dropdown.show {
    display: flex;
}

.notification-header {
    padding: 1.2rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    background-color: var(--gray-50);
}

.notification-list {
    flex-grow: 1;
    overflow-y: auto;
}

.notification-item {
    padding: 1.2rem;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    gap: 1rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.notification-item:hover {
    background-color: var(--gray-50);
}

.notification-item.unread {
    background-color: rgba(0, 88, 228, 0.05);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--primary-color), var(--primary-dark));
}

.notification-item.read {
    opacity: 0.7;
}

.notification-content {
    display: flex;
    gap: 0.9rem;
    width: 100%;
}

.notification-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
}

.notification-icon-wrapper.success { background: linear-gradient(135deg, #d1f7c4, #b8e6b1); color: var(--success-color); }
.notification-icon-wrapper.danger { background: linear-gradient(135deg, #f8d7da, #f1b0b7); color: var(--danger-color); }
.notification-icon-wrapper.warning { background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: var(--warning-color); }
.notification-icon-wrapper.info { background: linear-gradient(135deg, #cfe2ff, #b8daff); color: var(--info-color); }
.notification-icon-wrapper.secondary { background: linear-gradient(135deg, #e9ecef, #dee2e6); color: var(--gray-600); }
.notification-icon-wrapper.primary { background: linear-gradient(135deg, #cfe2ff, #a6c8ff); color: var(--primary-color); }

.notification-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 0.4rem; color: var(--gray-800); }
.notification-message { font-size: 0.85rem; color: var(--gray-600); line-height: 1.5; }
.notification-time { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.4rem; }

.notification-empty {
    padding: 3rem 2rem;
    text-align: center;
    color: var(--gray-500);
}

.notification-footer {
    padding: 1rem 1.2rem;
    border-top: 1px solid var(--gray-200);
    text-align: center;
    background-color: var(--gray-50);
}

.user-dropdown {
    width: 260px;
    flex-direction: column;
    overflow: hidden;
}

.user-dropdown-header {
    padding: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.9rem;
    border-bottom: 1px solid var(--gray-200);
    background: linear-gradient(135deg, var(--gray-50), var(--white));
}

.dropdown-avatar { width: 48px; height: 48px; }

.user-dropdown-item {
    padding: 0.9rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.9rem;
    color: var(--gray-700);
    text-decoration: none;
    transition: var(--transition);
}

.user-dropdown-item:hover {
    background-color: var(--gray-50);
    color: var(--primary-color);
}

.user-dropdown-divider {
    height: 1px;
    background-color: var(--gray-200);
    margin: 0.3rem 0;
}

.user-dropdown-logout { color: var(--danger-color); }

.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 1.6rem;
    color: var(--primary-color);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1060;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-dialog.large { max-width: 800px; }

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, var(--gray-50), var(--white));
}

.modal-title { font-size: 1.3rem; font-weight: 600; margin: 0; }

.modal-close {
    background: none;
    border: none;
    font-size: 1.3rem;
    color: var(--gray-500);
    cursor: pointer;
}

.modal-body { padding: 1.5rem; overflow-y: auto; }

.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.profile-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid var(--gray-200);
}

.profile-detail-item {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.notification-detail-header { display: flex; align-items: center; gap: 1.2rem; margin-bottom: 1.5rem; }
.notification-detail-icon { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
.notification-detail-message { font-size: 1rem; line-height: 1.6; margin-bottom: 1.5rem; padding: 1rem; background-color: var(--gray-50); border-radius: var(--border-radius); border-left: 4px solid var(--primary-color); }
.notification-detail-actions { display: flex; gap: 0.8rem; }

.accordion-item { border: 1px solid var(--gray-200); margin-bottom: -1px; }
.accordion-button { width: 100%; padding: 1.2rem 1.5rem; background-color: var(--white); border: none; text-align: left; font-weight: 600; cursor: pointer; }
.accordion-collapse { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
.accordion-collapse.show { max-height: 500px; }
.accordion-body { padding: 0 1.5rem 1.2rem; }

.btn {
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    font-weight: 500;
    border: none;
    font-size: 0.9rem;
    cursor: pointer;
}

.btn-primary { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: var(--white); }
.btn-secondary { background-color: var(--gray-200); color: var(--gray-700); }

@media (max-width: 768px) {
    .nav-links {
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        height: calc(100vh - 70px);
        background-color: var(--white);
        flex-direction: column;
        padding: 2rem;
        gap: 1rem;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1020;
    }
    .nav-links.show { transform: translateX(0); }
    .mobile-menu-btn { display: block; }
    .notification-dropdown, .user-dropdown { width: calc(100vw - 2rem); right: -1rem; }
    .modal-dialog.large { width: 95%; max-width: none; }
}
-->

<nav>
  <div class="nav-container">
    <a href="{{ route('dashboard') }}" class="brand">
      <img src="{{ asset('assets/logo.png') }}" alt="Logo Polije">
    </a>

    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="bi bi-list"></i>
    </button>

    <div class="nav-links" id="navLinks">
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>
      <a href="{{ url('/upload') }}" class="{{ request()->is('upload*') ? 'active' : '' }}">Unggah</a>
      <a href="{{ url('/browser') }}" class="{{ request()->is('browser*') ? 'active' : '' }}">Jelajahi</a>
      <a href="{{ url('/search') }}" class="{{ request()->is('search*') ? 'active' : '' }}">Pencarian</a>
            <a href="{{ route('chatbot.index') }}" class="{{ request()->routeIs('chatbot.*') ? 'active' : '' }}">Sipora AI</a>
            @if($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
            @endif
    </div>

    <div class="user-info">
      <div class="notification-icon" id="notificationIcon">
        <i class="bi bi-bell-fill"></i>
        @if($notificationCount > 0)
          <span class="notification-badge" id="notificationCount"></span>
        @endif

        <div class="notification-dropdown" id="notificationDropdown">
          <div class="notification-header">
            <h5>Terbaru</h5>
            @if($notificationCount > 0)
              <a href="#" onclick="markAllAsRead(); return false;">Tandai semua dibaca</a>
            @endif
          </div>

          <div class="notification-list" id="notificationList">
            @if(!empty($notifications))
              @foreach($notifications as $index => $notif)
                <div class="notification-item unread" data-index="{{ $index }}" onclick="showNotificationDetail({{ $index }})">
                  <div class="notification-content">
                    <div class="notification-icon-wrapper {{ $notif['icon_type'] }}">
                      <i class="bi {{ $notif['icon_class'] }}"></i>
                    </div>
                    <div class="notification-text">
                      <div class="notification-title">{{ $notif['title'] }}</div>
                      <div class="notification-message">{!! $notif['message'] !!}</div>
                      <div class="notification-time">{{ $notif['time'] }}</div>
                    </div>
                  </div>
                </div>
              @endforeach
            @else
              <div class="notification-empty">
                <i class="bi bi-bell-slash"></i>
                <p>Tidak ada notifikasi baru.</p>
              </div>
            @endif
          </div>

          <div class="notification-footer">
            <a href="#" onclick="showAllNotifications(); return false;">
              <i class="bi bi-list-ul"></i>
              Lihat Semua Notifikasi
            </a>
          </div>
        </div>
      </div>

      <div id="userAvatarContainer" class="user-avatar-container">
        @if($avatarUrl)
          <img src="{{ $avatarUrl }}" alt="User Avatar" id="userAvatar">
        @else
          <span class="avatar-initial">{{ $getInitials($userData['username'] ?: $userData['nama_lengkap']) }}</span>
        @endif
      </div>

      <div class="user-dropdown" id="userDropdown">
        <div class="user-dropdown-header">
          <div class="dropdown-avatar">
            @if($avatarUrl)
              <img src="{{ $avatarUrl }}" alt="User Avatar">
            @else
              <span class="avatar-initial">{{ $getInitials($userData['username'] ?: $userData['nama_lengkap']) }}</span>
            @endif
          </div>
          <div>
            <div class="name">{{ $userData['username'] }}</div>
            <div class="role">{{ $roleName }}</div>
          </div>
        </div>

        <a href="#" class="user-dropdown-item" onclick="openProfileModal(); return false;">
          <i class="bi bi-person"></i>
          <span>Profil Saya</span>
        </a>

        <a href="#" class="user-dropdown-item" onclick="openHelpModal(); return false;">
          <i class="bi bi-question-circle"></i>
          <span>Bantuan</span>
        </a>

        <div class="user-dropdown-divider"></div>

        @if($userId > 0)
          <a href="#" class="user-dropdown-item user-dropdown-logout" onclick="submitLogout(); return false;">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="user-dropdown-item user-dropdown-logout">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk</span>
          </a>
        @endif
      </div>
    </div>
  </div>
</nav>

<form id="logoutForm" method="POST" action="{{ route('auth.logout') }}" style="display:none;">
    @csrf
</form>

<div class="modal" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Profil Pengguna</h5>
            <button type="button" class="modal-close" onclick="closeModal('profileModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="profile-header">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="User Avatar" class="profile-avatar">
                @else
                    <div class="profile-avatar dropdown-avatar"><span class="avatar-initial">{{ $getInitials($userData['username']) }}</span></div>
                @endif
                <div class="profile-info">
                    <h4>{{ $userData['username'] }}</h4>
                    <p>{{ $userData['email'] }}</p>
                    <p>{{ $roleName }}</p>
                </div>
            </div>
            <div class="profile-details">
                <h5>Informasi Pribadi</h5>
                <div class="profile-detail-item"><span>Username</span><span>{{ $userData['username'] }}</span></div>
                <div class="profile-detail-item"><span>Email</span><span>{{ $userData['email'] }}</span></div>
                <div class="profile-detail-item"><span>Role</span><span>{{ $roleName }}</span></div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="helpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Bantuan</h5>
            <button type="button" class="modal-close" onclick="closeModal('helpModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="accordion" id="helpAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mengunggah Dokumen</button></h2>
                    <div class="accordion-collapse show"><div class="accordion-body"><ol><li>Klik menu <strong>Unggah</strong>.</li><li>Isi data dokumen.</li><li>Pilih file dokumen.</li><li>Klik <strong>Unggah Dokumen</strong>.</li><li>Tunggu proses review admin.</li></ol></div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mencari Dokumen</button></h2>
                    <div class="accordion-collapse"><div class="accordion-body"><ol><li>Buka menu <strong>Pencarian</strong>.</li><li>Masukkan kata kunci.</li><li>Klik cari/Enter.</li><li>Gunakan filter yang tersedia.</li></ol></div></div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mengunduh Dokumen</button></h2>
                    <div class="accordion-collapse"><div class="accordion-body"><p>Klik ikon unduh pada kartu dokumen atau dari modal detail dokumen.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="notificationDetailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Detail Notifikasi</h5>
            <button type="button" class="modal-close" onclick="closeModal('notificationDetailModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="notification-detail-header">
                <div class="notification-detail-icon" id="notifDetailIcon"></div>
                <div>
                    <div class="notification-detail-title" id="notifDetailTitle"></div>
                    <div class="notification-detail-time" id="notifDetailTime"></div>
                </div>
            </div>
            <div class="notification-detail-message" id="notifDetailMessage"></div>
            <div class="notification-detail-actions"><button class="btn btn-secondary" onclick="closeModal('notificationDetailModal')">Tutup</button></div>
        </div>
    </div>
</div>

<div class="modal" id="allNotificationsModal" tabindex="-1">
    <div class="modal-dialog large">
        <div class="modal-header">
            <h5 class="modal-title">Semua Notifikasi</h5>
            <button type="button" class="modal-close" onclick="closeModal('allNotificationsModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="notification-header">
                <h5>Terbaru</h5>
                @if($notificationCount > 0)
                    <button class="btn btn-primary" onclick="clearAllNotifications()"><i class="bi bi-trash3"></i> Hapus Semua</button>
                @endif
            </div>
            <div class="all-notifications-list" id="allNotificationsList">
                @if(!empty($notifications))
                    @foreach($notifications as $index => $notif)
                        <div class="notification-item unread" data-index="{{ $index }}" onclick="showNotificationDetail({{ $index }})">
                            <div class="notification-content">
                                <div class="notification-icon-wrapper {{ $notif['icon_type'] }}"><i class="bi {{ $notif['icon_class'] }}"></i></div>
                                <div class="notification-text">
                                    <div class="notification-title">{{ $notif['title'] }}</div>
                                    <div class="notification-message">{!! $notif['message'] !!}</div>
                                    <div class="notification-time">{{ $notif['time'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="notification-empty"><i class="bi bi-bell-slash"></i><p>Tidak ada notifikasi.</p></div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="notificationsData" data-items='@json($notifications)' style="display:none;"></div>
<script src="{{ asset('assets/js/navbar.js') }}" defer></script>
<!-- INLINE JS MOVED TO public/assets/js/navbar.js
const notificationsData = @json($notifications);

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
-->
