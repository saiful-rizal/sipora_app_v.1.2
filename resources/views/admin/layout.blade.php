<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | @yield('title','Admin')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CUSTOM -->
    <link href="{{ asset('assets/css/admin-ux.css') }}" rel="stylesheet">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand d-flex gap-2 mb-4 align-items-center">

        <img src="{{ asset('assets/logo.png') }}"
             style="height:40px; width:auto; object-fit:contain;">

        <div>
            <strong>SIPORA</strong><br>
            <small>Admin Panel</small>
        </div>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="{{ $activeMenu=='dashboard'?'active':'' }}">
        <i class="bi bi-grid"></i> Dashboard
    </a>

    <a href="{{ route('admin.jurusan.index') }}" class="{{ $activeMenu=='jurusan'?'active':'' }}">
        <i class="bi bi-diagram-3"></i> Jurusan
    </a>

    <a href="{{ route('admin.prodi.index') }}" class="{{ $activeMenu=='prodi'?'active':'' }}">
        <i class="bi bi-mortarboard"></i> Prodi
    </a>

    <a href="{{ route('admin.tema.index') }}" class="{{ $activeMenu=='tema'?'active':'' }}">
        <i class="bi bi-bookmarks"></i> Tema
    </a>

    <a href="{{ route('admin.documents.index') }}" class="{{ $activeMenu=='documents'?'active':'' }}">
    <i class="bi bi-file-earmark-text"></i> Dokumen
</a>

    <a href="{{ route('admin.users.index') }}" class="{{ $activeMenu=='users'?'active':'' }}">
        <i class="bi bi-people"></i> User
    </a>

    <hr>

    <!-- LOGOUT BUTTON (trigger modal) -->
    <button class="btn btn-outline-danger w-100 mt-2"
            data-bs-toggle="modal"
            data-bs-target="#logoutModal">
        <i class="bi bi-box-arrow-right"></i> Logout
    </button>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">

        <h5>@yield('page_label')</h5>

        <div class="d-flex gap-3 align-items-center">

            <!-- SEARCH -->
            <div class="search-topbar">
                <i class="bi bi-search"></i>
                <input type="text" id="globalSearch" placeholder="Cari data..."
                       data-table-target="@yield('search_target')">
            </div>

          <div class="dropdown">

    <div class="avatar dropdown-toggle"
         data-bs-toggle="dropdown"
         style="cursor:pointer;">
        {{ strtoupper(substr($displayName ?? 'A',0,1)) }}
    </div>

    <div class="dropdown-menu dropdown-menu-end p-3 shadow border-0"
         style="width:240px; border-radius:16px;">

        <!-- USER INFO -->
        <div class="mb-3">
            <strong>{{ $displayName }}</strong><br>
            <small class="text-muted">{{ $displayRole ?? 'Admin' }}</small>
        </div>

        <hr class="my-2">

        <!-- MENU -->
        <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-person"></i> Profile
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-gear"></i> Settings
        </a>

        <hr class="my-2">

    

    </div>

</div>

        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

<!-- OVERLAY -->
<div id="overlay" class="overlay" onclick="closeSlide()"></div>


<!-- ================= MODAL LOGOUT ================= -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Konfirmasi Logout</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Anda akan keluar dari sistem. Lanjutkan?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>

                <!-- FORM LOGOUT REAL -->
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="btn btn-danger">
                        Ya, Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===== GLOBAL SEARCH ===== */
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('globalSearch');

    if (input) {
        input.addEventListener('keyup', function () {
            const keyword = input.value.toLowerCase();
            const target = input.dataset.tableTarget;

            if (!target) return;

            document.querySelectorAll(`${target} tbody tr`).forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
            });
        });
    }

    /* TOAST */
    const toastEl = document.getElementById('liveToast');
    if (toastEl) {
        new bootstrap.Toast(toastEl).show();
    }
});

/* SLIDE */
function openSlide(){
    document.getElementById('slidePanel')?.classList.add('open');
}
function closeSlide(){
    document.getElementById('slidePanel')?.classList.remove('open');
}
</script>

@stack('scripts')

</body>
</html>