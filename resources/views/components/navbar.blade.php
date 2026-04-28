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
        $profilePhoto = DB::table('user_profile')->where('id_user', $userId)->value('foto_profil');
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
                $message = 'Dokumen "' . e($doc->judul) . '" berstatus: ' . e($statusName) . '';

                if ((int) $doc->status_id === 5) {
                    $iconType = 'success';
                    $iconClass = 'bi-check-circle-fill';
                    $title = 'Dokumen Diterbitkan';
                    $message = 'Dokumen "' . e($doc->judul) . '" telah diterbitkan dan tersedia untuk umum';
                } elseif ((int) $doc->status_id === 1) {
                    $iconType = 'info';
                    $iconClass = 'bi-clock-fill';
                    $title = 'Menunggu Persetujuan';
                    $message = 'Dokumen "' . e($doc->judul) . '" sedang menunggu persetujuan dari reviewer/admin';
                } elseif ((int) $doc->status_id === 4) {
                    $iconType = 'danger';
                    $iconClass = 'bi-x-circle-fill';
                    $title = 'Dokumen Ditolak';
                    $message = 'Dokumen "' . e($doc->judul) . '" ditolak. Silakan periksa kembali dokumen Anda';
                } elseif ((int) $doc->status_id === 2) {
                    $iconType = 'warning';
                    $iconClass = 'bi-hourglass-split';
                    $title = 'Sedang Direview';
                    $message = 'Dokumen "' . e($doc->judul) . '" sedang direview oleh tim';
                } elseif ((int) $doc->status_id === 3) {
                    $iconType = 'secondary';
                    $iconClass = 'bi-file-earmark-text-fill';
                    $title = 'Menunggu Publikasi';
                    $message = 'Dokumen "' . e($doc->judul) . '" masih dalam status Menunggu Publikasi';
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
                        'message' =>
                            e($doc->uploader_name ?? 'Unknown') . ' mengunggah dokumen: "' . e($doc->judul) . '"',
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
                        'message' =>
                            'Dokumen "' .
                            e($doc->judul) .
                            '" dari ' .
                            e($doc->uploader_name ?? 'Unknown') .
                            ' menunggu review',
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
                        'message' =>
                            e($download->downloader_name ?? 'Unknown') .
                            ' mengunduh dokumen: "' .
                            e($download->judul) .
                            '"',
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

<link
    href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
    rel="stylesheet">
<link href="{{ asset('assets/css/navbar.css') }}" rel="stylesheet">

<!-- ============================================ -->
<!-- NAVBAR - DENGAN MENU CENTER (GRID LAYOUT) -->
<!-- ============================================ -->
<nav>
    <div class="nav-container">
        <a href="{{ route('dashboard') }}" class="brand">
            <div class="brand-logo-wrap">
                <img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije" class="brand-logo-img">
            </div>
            <div class="brand-text">
                <strong>SIPORA</strong>
                <span>Sistem Informasi Polije Repository Asset</span>
            </div>
        </a>

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="bi bi-list"></i>
        </button>

        <div class="nav-links" id="navLinks">
            <a href="{{ route('dashboard') }}" class="nav-main-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>
            <a href="{{ url('/upload') }}" class="nav-main-link {{ request()->is('upload*') ? 'active' : '' }}">Unggah</a>
            <a href="{{ url('/browser') }}" class="nav-main-link {{ request()->is('browser*') ? 'active' : '' }}">Jelajahi</a>
            <a href="{{ url('/search') }}" class="nav-main-link {{ request()->is('search*') ? 'active' : '' }}">Pencarian</a>
            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-admin {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
            @endif
        </div>

        <!-- USER INFO SECTION - NOTIFIKASI & PROFIL BERDEMPET -->
        <div class="user-info">
            <div class="notification-icon" id="notificationIcon" style="position:relative;"
                onclick="toggleNotification()">
                <i class="bi bi-bell-fill"></i>
                @if ($notificationCount > 0)
                    <span class="notification-badge" id="notificationCount"></span>
                @endif

                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <span>Notifikasi</span>
                        @if ($notificationCount > 0)
                            <a href="#" onclick="markAllAsRead(); return false;">Tandai dibaca</a>
                        @endif
                    </div>

                    <div class="notification-list" id="notificationList">
                        @if (!empty($notifications))
                            @foreach ($notifications as $index => $notif)
                                <div class="notification-item unread" data-index="{{ $index }}"
                                    onclick="showNotificationDetail({{ $index }})">
                                    <div class="notification-content">
                                        <div class="notification-icon-wrapper {{ $notif['icon_type'] }}">
                                            <i class="bi {{ $notif['icon_class'] }}"></i>
                                        </div>
                                        <div>
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

            <div id="userAvatarContainer" class="user-avatar-container" onclick="toggleUserDropdown()">
                <i class="bi bi-person-fill user-profile-icon"></i>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="user-dropdown-header">
                    <div class="dropdown-avatar">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="User Avatar">
                        @else
                            <span
                                class="avatar-initial">{{ $getInitials($userData['username'] ?: $userData['nama_lengkap']) }}</span>
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

                @if ($userId > 0)
                    <a href="#" class="user-dropdown-item user-dropdown-logout"
                        onclick="submitLogout(); return false;">
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

<!-- Modal Profil -->
<div class="modal" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Profil Pengguna</h5>
            <button type="button" class="modal-close-btn" onclick="closeModal('profileModal')"
                style="background:#dc2626;border:none;cursor:pointer;padding:8px 12px;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i
                    class="bi bi-x-lg" style="font-size:18px;color:#ffffff;"></i></button>
        </div>
        <div class="modal-body">
            <div class="profile-header">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="User Avatar" class="profile-avatar">
                @else
                    <div class="profile-avatar dropdown-avatar"><span
                            class="avatar-initial">{{ $getInitials($userData['username']) }}</span></div>
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

<!-- Modal Bantuan -->
<div class="modal" id="helpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Bantuan</h5>
            <button type="button" class="modal-close-btn" onclick="closeModal('helpModal')"
                style="background:#dc2626;border:none;cursor:pointer;padding:8px 12px;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i
                    class="bi bi-x-lg" style="font-size:18px;color:#ffffff;"></i></button>
        </div>
        <div class="modal-body">
            <div class="accordion" id="helpAccordion">
                <div class="accordion-item">
                    <button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mengunggah
                        Dokumen</button>
                    <div class="accordion-collapse show">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu Unggah.</li>
                                <li>Isi data dokumen.</li>
                                <li>Pilih file dokumen.</li>
                                <li>Klik Unggah Dokumen.</li>
                                <li>Tunggu proses review admin.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mencari
                        Dokumen</button>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Buka menu Pencarian.</li>
                                <li>Masukkan kata kunci.</li>
                                <li>Klik cari / Enter.</li>
                                <li>Gunakan filter yang tersedia.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" onclick="toggleAccordion(this)">Cara Mengunduh
                        Dokumen</button>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <p>Klik ikon unduh pada kartu dokumen atau dari modal detail dokumen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Notifikasi -->
<div class="modal" id="notificationDetailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Detail Notifikasi</h5>
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
            <div class="notification-detail-actions">
                <button class="btn btn-secondary" onclick="closeModal('notificationDetailModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Semua Notifikasi -->
<div class="modal" id="allNotificationsModal" tabindex="-1">
    <div class="modal-dialog large">
        <div class="modal-header">
            <h5 class="modal-title">Semua Notifikasi</h5>
            <button type="button" class="modal-close-btn" onclick="closeModal('allNotificationsModal')"
                style="background:#dc2626;border:none;cursor:pointer;padding:8px 12px;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i
                    class="bi bi-x-lg" style="font-size:18px;color:#ffffff;"></i></button>
        </div>
        <div class="modal-body">
            <div class="notification-header" style="border-radius:12px;margin-bottom:14px;">
                <span>Terbaru</span>
                @if ($notificationCount > 0)
                    <button class="btn btn-primary" onclick="clearAllNotifications()"><i class="bi bi-trash3"></i>
                        Hapus Semua</button>
                @endif
            </div>
            <div class="all-notifications-list" id="allNotificationsList">
                @if (!empty($notifications))
                    @foreach ($notifications as $index => $notif)
                        <div class="notification-item unread" data-index="{{ $index }}"
                            onclick="showNotificationDetail({{ $index }})">
                            <div class="notification-content">
                                <div class="notification-icon-wrapper {{ $notif['icon_type'] }}"><i
                                        class="bi {{ $notif['icon_class'] }}"></i></div>
                                <div>
                                    <div class="notification-title">{{ $notif['title'] }}</div>
                                    <div class="notification-message">{!! $notif['message'] !!}</div>
                                    <div class="notification-time">{{ $notif['time'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="notification-empty"><i class="bi bi-bell-slash"></i>
                        <p>Tidak ada notifikasi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="notificationsData" data-items='@json($notifications)' style="display:none;"></div>

<!-- ============================================ -->
<!-- CSS - NAVBAR DENGAN MENU CENTER (GRID LAYOUT) -->
<!-- PERBAIKAN: NOTIFIKASI & PROFIL LEBIH BERDEMPET -->
<!-- ============================================ -->
<style>
    /* ===== RESET & BASE STYLES ===== */
    nav *,
    .modal *,
    .notification-dropdown *,
    .user-dropdown * {
        font-weight: 400 !important;
    }

    .brand-text strong,
    .brand-text>strong {
        font-weight: 800 !important;
    }

    /* ===== NAVBAR UTAMA ===== */
    nav {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1000 !important;
        width: 100% !important;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 0 20px !important;
        box-sizing: border-box !important;
    }

    /* ===== NAV CONTAINER - GRID LAYOUT UNTUK CENTERING SEMPURNA ===== */
    .nav-container {
        display: grid !important;
        grid-template-columns: 1fr auto 1fr !important;
        align-items: center !important;
        gap: 20px !important;
        padding: 12px 0 !important;
        margin: 0 auto !important;
        width: 100% !important;
        max-width: 1200px !important;
        box-sizing: border-box !important;
    }

    /* ===== BRAND / LOGO (RATA KIRI) ===== */
    .brand {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        justify-self: start !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0 !important;
    }

    .brand-logo-wrap {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    .brand-logo-img {
        height: 36px !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
    }

    .brand-text {
        display: flex !important;
        flex-direction: column !important;
        line-height: 1.2 !important;
    }

    .brand-text strong {
        font-size: 18px !important;
        letter-spacing: 0.5px !important;
    }

    .brand-text span {
        font-size: 11px !important;
        opacity: 0.85 !important;
    }

    /* ===== MENU NAVIGASI - TEPAT DI TENGAH ===== */
    .nav-links {
        justify-self: center !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .nav-links a {
        white-space: nowrap !important;
        padding: 8px 14px !important;
        text-decoration: none !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
    }

    .nav-links a.nav-main-link {
        min-width: 98px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        box-sizing: border-box !important;
    }

    .nav-links a:hover {
        background-color: #f3f4f6 !important;
        color: #1a56d6 !important;
    }

    .nav-links a.active {
        background-color: #1a56d6 !important;
        color: white !important;
    }

    /*
     * ============================================
     * USER INFO SECTION - PERBAIKAN SPACING
     * Notifikasi & Profil Sekarang Lebih Berdempet
     * ============================================
     */
    .user-info {
        justify-self: end !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        /* ✅ DIUBAH: Dari 12px → 6px (lebih rapat) */
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0 !important;
    }

    /* ✅ PERBAIKAN: Ikon Notifikasi dengan margin kecil */
    .notification-icon {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        cursor: pointer !important;
        margin-right: 2px !important;
        /* ✅ DITAMBAHKAN: Fine-tuning posisi */
    }

    /* ✅ PERBAIKAN: Avatar Container lebih compact */
    .user-avatar-container {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        cursor: pointer !important;
        background-color: #1a56d6 !important;
        width: 34px !important;
        /* ✅ DIUBAH: Dari 36px → 34px (sedikit lebih kecil) */
        height: 34px !important;
        /* ✅ DIUBAH: Dari 36px → 34px */
        border-radius: 50% !important;
        transition: opacity 0.2s ease !important;
        margin-left: 0px !important;
        /* ✅ Pastikan tidak ada margin berlebih */
    }

    .user-avatar-container:hover {
        opacity: 0.85 !important;
    }

    /* ===== ICON STYLING ===== */
    .user-profile-icon {
        color: #ffffff !important;
        font-size: 17px !important;
        /* ✅ Disesuaikan dengan ukuran avatar baru */
    }

    .notification-icon i.bi-bell-fill {
        font-size: 19px !important;
        /* ✅ Disesuaikan agar proporsional */
        color: #4b5563 !important;
        cursor: pointer !important;
    }

    .notification-icon i.bi-bell-fill:hover {
        color: #1a56d6 !important;
    }

    /* ===== NOTIFICATION BADGE ===== */
    .notification-badge {
        position: absolute !important;
        top: -5px !important;
        right: -8px !important;
        background-color: #ef4444 !important;
        color: white !important;
        font-size: 10px !important;
        font-weight: bold !important;
        min-width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 4px !important;
    }

    /* ===== DROPDOWNS ===== */
    .notification-dropdown,
    .user-dropdown {
        position: absolute !important;
        top: 55px !important;
        right: 20px !important;
        width: 320px !important;
        background: #ffffff !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #e5e7eb !important;
        z-index: 1001 !important;
        display: none !important;
    }

    .notification-dropdown.show,
    .user-dropdown.show {
        display: block !important;
    }

    .user-dropdown {
        width: 260px !important;
    }

    /* ===== AVATAR & INITIALS ===== */
    .avatar-initial {
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        /* ✅ Disesuaikan dengan ukuran avatar */
    }

    .dropdown-avatar {
        background: linear-gradient(135deg, #1a56d6, #4a7dff) !important;
        border-radius: 50% !important;
        width: 40px !important;
        height: 40px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .user-dropdown-header {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 16px !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .user-dropdown-header .name {
        font-weight: 600 !important;
        color: #1f2937 !important;
    }

    .user-dropdown-header .role {
        font-size: 12px !important;
        color: #6b7280 !important;
    }

    .user-dropdown-item {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 12px 16px !important;
        color: #374151 !important;
        text-decoration: none !important;
        transition: background 0.2s !important;
    }

    .user-dropdown-item:hover {
        background-color: #f9fafb !important;
    }

    .user-dropdown-divider {
        height: 1px !important;
        background-color: #e5e7eb !important;
        margin: 8px 0 !important;
    }

    .user-dropdown-logout {
        color: #dc2626 !important;
    }

    /* ===== MOBILE MENU BUTTON ===== */
    .mobile-menu-btn {
        display: none !important;
        background: none !important;
        border: none !important;
        font-size: 24px !important;
        cursor: pointer !important;
        color: #1f2937 !important;
    }

    /* ===== RESPONSIVE UNTUK TABLET & HP ===== */
    @media (max-width: 768px) {
        nav {
            padding: 0 16px !important;
        }

        .nav-container {
            gap: 10px !important;
        }

        .brand-logo-img {
            height: 30px !important;
        }

        .brand-text strong {
            font-size: 16px !important;
        }

        .brand-text span {
            font-size: 9px !important;
        }

        .nav-links {
            gap: 4px !important;
        }

        .nav-links a {
            padding: 6px 10px !important;
            font-size: 14px !important;
        }

        .nav-links a.nav-main-link {
            min-width: auto !important;
        }

        /* ✅ Mobile: Jarak tetap rapat */
        .user-info {
            gap: 4px !important;
        }

        .user-avatar-container {
            width: 32px !important;
            height: 32px !important;
        }

        .user-profile-icon {
            font-size: 15px !important;
        }

        .notification-icon i.bi-bell-fill {
            font-size: 17px !important;
        }

        /* ✅ Mobile: Hilangkan margin tambahan */
        .notification-icon {
            margin-right: 0px !important;
        }
    }

    /* ===== UNTUK HP KECIL (LAYOUT JADI STACK) ===== */
    @media (max-width: 640px) {
        .mobile-menu-btn {
            display: block !important;
        }

        .nav-container {
            grid-template-columns: auto auto !important;
            grid-template-rows: auto auto !important;
        }

        .brand {
            grid-column: 1 / 2 !important;
            grid-row: 1 / 2 !important;
        }

        .mobile-menu-btn {
            grid-column: 2 / 3 !important;
            grid-row: 1 / 2 !important;
            justify-self: end !important;
        }

        .user-info {
            grid-column: 3 / 4 !important;
            grid-row: 1 / 2 !important;
            gap: 4px !important;
            /* ✅ Mobile: Sangat rapat */
        }

        .nav-links {
            grid-column: 1 / 4 !important;
            grid-row: 2 / 3 !important;
            justify-self: center !important;
            flex-wrap: wrap !important;
            margin-top: 10px !important;
        }

        .notification-dropdown,
        .user-dropdown {
            width: 280px !important;
            right: 10px !important;
        }
    }

    /* ===== MODAL STYLING ===== */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex !important;
    }

    .modal-dialog {
        background: #ffffff !important;
        border-radius: 16px !important;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-dialog.large {
        max-width: 600px;
    }

    .modal-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 16px 20px !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .modal-title {
        font-size: 18px !important;
        font-weight: 600 !important;
        margin: 0 !important;
    }

    .modal-close-btn {
        background: #dc2626 !important;
        border: none !important;
        cursor: pointer !important;
        padding: 6px 10px !important;
        border-radius: 6px !important;
    }

    .modal-close-btn i {
        color: #ffffff !important;
        font-size: 14px !important;
    }

    .modal-body {
        padding: 20px !important;
    }

    /* ===== NOTIFICATION ITEMS ===== */
    .notification-item {
        padding: 12px !important;
        border-bottom: 1px solid #f3f4f6 !important;
        cursor: pointer !important;
        transition: background 0.2s !important;
    }

    .notification-item:hover {
        background-color: #f9fafb !important;
    }

    .notification-content {
        display: flex !important;
        gap: 12px !important;
    }

    .notification-icon-wrapper {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .notification-icon-wrapper.info {
        background-color: #dbeafe !important;
        color: #1a56d6 !important;
    }

    .notification-icon-wrapper.success {
        background-color: #d1fae5 !important;
        color: #10b981 !important;
    }

    .notification-icon-wrapper.warning {
        background-color: #fed7aa !important;
        color: #f97316 !important;
    }

    .notification-icon-wrapper.danger {
        background-color: #fee2e2 !important;
        color: #ef4444 !important;
    }

    .notification-title {
        font-weight: 600 !important;
        font-size: 14px !important;
        margin-bottom: 4px !important;
    }

    .notification-message {
        font-size: 12px !important;
        color: #6b7280 !important;
        margin-bottom: 4px !important;
    }

    .notification-time {
        font-size: 11px !important;
        color: #9ca3af !important;
    }

    .notification-empty {
        text-align: center !important;
        padding: 30px !important;
        color: #9ca3af !important;
    }
</style>

<!-- ============================================ -->
<!-- JAVASCRIPT - INTERAKTIVITAS NAVBAR -->
<!-- ============================================ -->
<script>
    function toggleNotification() {
        const dropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');

        if (userDropdown && userDropdown.classList.contains('show')) {
            userDropdown.classList.remove('show');
        }

        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        const notifDropdown = document.getElementById('notificationDropdown');

        if (notifDropdown && notifDropdown.classList.contains('show')) {
            notifDropdown.classList.remove('show');
        }

        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        const notifIcon = document.getElementById('notificationIcon');
        const userAvatar = document.getElementById('userAvatarContainer');
        const notifDropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');

        if (notifDropdown && notifIcon && !notifIcon.contains(event.target) && !notifDropdown.contains(event
                .target)) {
            notifDropdown.classList.remove('show');
        }

        if (userDropdown && userAvatar && !userAvatar.contains(event.target) && !userDropdown.contains(event
                .target)) {
            userDropdown.classList.remove('show');
        }
    });

    // Fungsi untuk modal
    function openProfileModal() {
        document.getElementById('profileModal').classList.add('show');
        closeDropdowns();
    }

    function openHelpModal() {
        document.getElementById('helpModal').classList.add('show');
        closeDropdowns();
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    function closeDropdowns() {
        document.getElementById('notificationDropdown')?.classList.remove('show');
        document.getElementById('userDropdown')?.classList.remove('show');
    }

    function submitLogout() {
        document.getElementById('logoutForm').submit();
    }

    function toggleAccordion(button) {
        const collapse = button.nextElementSibling;
        collapse.classList.toggle('show');
    }

    function showNotificationDetail(index) {
        const notifications = JSON.parse(document.getElementById('notificationsData').dataset.items || '[]');
        const notif = notifications[index];
        if (notif) {
            document.getElementById('notifDetailTitle').innerText = notif.title;
            document.getElementById('notifDetailMessage').innerHTML = notif.message;
            document.getElementById('notifDetailTime').innerText = notif.time;
            document.getElementById('notificationDetailModal').classList.add('show');
        }
        closeDropdowns();
    }

    function showAllNotifications() {
        document.getElementById('allNotificationsModal').classList.add('show');
        closeDropdowns();
    }

    function markAllAsRead() {
        // Implementasi mark all as read
        console.log('Mark all as read');
    }

    function clearAllNotifications() {
        // Implementasi clear all notifications
        console.log('Clear all notifications');
    }

    // Tutup modal saat klik di luar
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }

    // Mobile menu toggle
    document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
        const navLinks = document.getElementById('navLinks');
        navLinks.classList.toggle('show');
    });
</script>

<script src="{{ asset('assets/js/navbar.js') }}" defer></script>
