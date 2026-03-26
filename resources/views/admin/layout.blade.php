<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | @yield('title', 'Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-ux.css') }}" rel="stylesheet">
</head>
<body class="adminx-body">
    @php
        $activeMenu = $activeMenu ?? 'dashboard';
        $displayRole = $isSuperAdmin ? 'Super Admin' : 'Admin';
    @endphp

    <div class="adminx-layout">
        <aside class="adminx-sidebar" id="adminxSidebar">
            <div class="adminx-brand">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo SIPORA">
                <div>
                    <strong>SIPORA</strong>
                    <small>Admin Panel</small>
                </div>
            </div>

            <nav class="adminx-menu">
                <a href="{{ route('admin.dashboard') }}" class="{{ $activeMenu === 'dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard Admin</span>
                </a>
                <a href="{{ route('admin.jurusan.index') }}" class="{{ $activeMenu === 'jurusan' ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Data Jurusan</span>
                </a>
                <a href="{{ route('admin.prodi.index') }}" class="{{ $activeMenu === 'prodi' ? 'active' : '' }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>Data Prodi</span>
                </a>
                <a href="{{ route('admin.tema.index') }}" class="{{ $activeMenu === 'tema' ? 'active' : '' }}">
                    <i class="bi bi-bookmarks"></i>
                    <span>Data Tema</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ $activeMenu === 'users' ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Pengelolaan User</span>
                </a>
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house"></i>
                    <span>Dashboard Biasa</span>
                </a>
            </nav>

            <div class="adminx-sidebar-footer">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="adminx-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="adminx-main">
            <header class="adminx-topbar">
                <button class="adminx-menu-toggle" type="button" onclick="toggleAdminSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <div class="adminx-topbar-search">
                    <i class="bi bi-search"></i>
                    <input type="text" value="@yield('page_label', 'Admin Panel')" readonly>
                </div>

                <div class="adminx-user-chip">
                    <div class="avatar">{{ strtoupper(mb_substr($displayName, 0, 1)) }}</div>
                    <div>
                        <strong>{{ $displayName }}</strong>
                        <small>{{ $displayRole }}</small>
                    </div>
                </div>
            </header>

            <section class="adminx-content">
                <div class="adminx-quicknav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ $activeMenu === 'dashboard' ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.jurusan.index') }}" class="{{ $activeMenu === 'jurusan' ? 'active' : '' }}">Jurusan</a>
                    <a href="{{ route('admin.prodi.index') }}" class="{{ $activeMenu === 'prodi' ? 'active' : '' }}">Prodi</a>
                    <a href="{{ route('admin.tema.index') }}" class="{{ $activeMenu === 'tema' ? 'active' : '' }}">Tema</a>
                    <a href="{{ route('admin.users.index') }}" class="{{ $activeMenu === 'users' ? 'active' : '' }}">User</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success admin-alert" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger admin-alert" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger admin-alert" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminxSidebar');
            sidebar.classList.toggle('open');
        }

        function applyTableSearch(inputElement) {
            const tableSelector = inputElement.getAttribute('data-table-search');
            const table = document.querySelector(tableSelector);

            if (!table) {
                return;
            }

            const keyword = inputElement.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(function (row) {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(keyword) ? '' : 'none';
            });
        }

        document.querySelectorAll('[data-table-search]').forEach(function (inputElement) {
            inputElement.addEventListener('input', function () {
                applyTableSearch(inputElement);
            });
        });
    </script>
</body>
</html>
