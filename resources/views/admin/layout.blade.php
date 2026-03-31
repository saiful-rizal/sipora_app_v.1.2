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
      
    </div>

    <a href="{{ route('admin.dashboard') }}" class="{{ $activeMenu=='dashboard'?'active':'' }}">
        <i class="bi bi-grid"></i> Beranda
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

   <!-- DOKUMEN -->
<div class="menu-group">

    <button class="menu-toggle {{ str_contains($activeMenu,'documents') ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#menuDokumen">

        <i class="bi bi-file-earmark-text"></i> Dokumen
         <i class="bi bi-chevron-down ms-auto"></i>
    </button>

    <div id="menuDokumen" class="collapse {{ str_contains($activeMenu,'documents') ? 'show' : '' }}">

        <a href="{{ route('admin.documents.index') }}"
           class="submenu {{ $activeMenu=='documents'?'active':'' }}">
            Manajemen Data
        </a>

        <a href="{{ route('admin.documents.report') }}"
           class="submenu {{ $activeMenu=='documents_report'?'active':'' }}">
            Laporan 
        </a>

    </div>
</div>

<!-- USER -->
<div class="menu-group">

    <button class="menu-toggle {{ str_contains($activeMenu,'users') ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#menuUser">

        <i class="bi bi-people"></i> Pengguna
       <i class="bi bi-chevron-down ms-auto"></i>
    </button>

    <div id="menuUser" class="collapse {{ str_contains($activeMenu,'users') ? 'show' : '' }}">

        <a href="{{ route('admin.users.index') }}"
           class="submenu {{ $activeMenu=='users'?'active':'' }}">
            Manajemen Data
        </a>

        <a href="{{ route('admin.users.report') }}"
           class="submenu {{ $activeMenu=='users_report'?'active':'' }}">
            Laporan
        </a>

    </div>
</div>

    <hr>

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
                <input type="text" id="globalSearch" placeholder="Masukkan kata kunci dan tekan spasi untuk mencari...">
            </div>

            <div class="dropdown">
                <div class="avatar dropdown-toggle"
                     data-bs-toggle="dropdown"
                     style="cursor:pointer;">
                    {{ strtoupper(substr($displayName ?? 'A',0,1)) }}
                </div>

                <div class="dropdown-menu dropdown-menu-end p-3 shadow border-0"
                     style="width:240px; border-radius:16px;">

                    <div class="mb-3">
                        <strong>{{ $displayName }}</strong><br>
                        <small class="text-muted">{{ $displayRole ?? 'Admin' }}</small>
                    </div>

                    <hr class="my-2">

                    <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i> Profile
                    </a>

                    <a href="#" class="dropdown-item d-flex align-items-center gap-2">
                        <i class="bi bi-gear"></i> Settings
                    </a>

                </div>
            </div>

        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

<!-- MODAL LOGOUT -->
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
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("globalSearch");

    searchInput.addEventListener("keyup", function () {

        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {

            let text = row.innerText.toLowerCase();

            row.querySelectorAll("input").forEach(input => {
                text += " " + input.value.toLowerCase();
            });

            row.querySelectorAll("select").forEach(select => {
                text += " " + select.options[select.selectedIndex].text.toLowerCase();
            });

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }

        });

    });

});
</script>
@stack('scripts')
</body>
</html>