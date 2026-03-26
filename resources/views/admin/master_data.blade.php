<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Admin Master Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body class="adminx-body">
    @php
        $sessionUser = session('auth_user', []);
        $displayName = $sessionUser['nama_lengkap'] ?? ($sessionUser['username'] ?? 'Admin');
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
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-pie-chart"></i>
                    <span>Widgets</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-columns-gap"></i>
                    <span>UI Elements</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-layers"></i>
                    <span>Advanced UI</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-ui-checks-grid"></i>
                    <span>Form Elements</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Charts</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-table"></i>
                    <span>Tables</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-compass"></i>
                    <span>Icons</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-map"></i>
                    <span>Maps</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-people"></i>
                    <span>User Pages</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-exclamation-octagon"></i>
                    <span>Error Pages</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-window"></i>
                    <span>General Pages</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-bag"></i>
                    <span>E-commerce</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-envelope"></i>
                    <span>E-mail</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-calendar3"></i>
                    <span>Calendar</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-list-check"></i>
                    <span>Todo List</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-images"></i>
                    <span>Gallery</span>
                </a>
                <a href="#" class="adminx-menu-muted">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Documentation</span>
                </a>
                <a href="#panel-jurusan">
                    <i class="bi bi-diagram-3"></i>
                    <span>Data Jurusan</span>
                </a>
                <a href="#panel-prodi">
                    <i class="bi bi-mortarboard"></i>
                    <span>Data Prodi</span>
                </a>
                <a href="#panel-tema">
                    <i class="bi bi-bookmarks"></i>
                    <span>Data Tema</span>
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
                    <input type="text" value="Master Data" readonly>
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
                <div class="admin-head">
                    <div>
                        <span class="admin-badge">Panel Admin</span>
                        <h1 class="admin-title">Manajemen Master Data</h1>
                        <p class="admin-subtitle">Kelola data jurusan, program studi, dan tema secara terpisah dari dashboard pengguna.</p>
                    </div>
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

                <section class="adminx-overview">
                    <div class="adminx-overview-hero">
                        <div class="adminx-overview-header">
                            <h5>Welcome {{ $displayName }}</h5>
                            <small>All systems are running smoothly.</small>
                        </div>
                        <div class="adminx-weather-card">
                            <div>
                                <h6>31° C</h6>
                                <p>Jember, Indonesia</p>
                            </div>
                            <i class="bi bi-brightness-high"></i>
                        </div>
                    </div>

                    <div class="adminx-metric-grid">
                        <article class="adminx-mini-card blue">
                            <span>Total Jurusan</span>
                            <strong>{{ $jurusan->count() }}</strong>
                        </article>
                        <article class="adminx-mini-card indigo">
                            <span>Total Prodi</span>
                            <strong>{{ $prodi->count() }}</strong>
                        </article>
                        <article class="adminx-mini-card violet">
                            <span>Total Tema</span>
                            <strong>{{ $tema->count() }}</strong>
                        </article>
                        <article class="adminx-mini-card coral">
                            <span>Status Login</span>
                            <strong>{{ $displayRole }}</strong>
                        </article>
                    </div>
                </section>

                <section class="adminx-report-grid">
                    <article class="adminx-report-card">
                        <div class="adminx-report-head">
                            <h6>Order Details</h6>
                            <small>Ringkasan data master saat ini</small>
                        </div>
                        <div class="adminx-report-stats">
                            <div>
                                <span>Jurusan</span>
                                <strong>{{ $jurusan->count() }}</strong>
                            </div>
                            <div>
                                <span>Prodi</span>
                                <strong>{{ $prodi->count() }}</strong>
                            </div>
                            <div>
                                <span>Tema</span>
                                <strong>{{ $tema->count() }}</strong>
                            </div>
                        </div>
                        <div class="adminx-fake-chart">
                            <span style="height: 38%"></span>
                            <span style="height: 56%"></span>
                            <span style="height: 45%"></span>
                            <span style="height: 72%"></span>
                            <span style="height: 60%"></span>
                            <span style="height: 82%"></span>
                            <span style="height: 51%"></span>
                        </div>
                    </article>

                    <article class="adminx-report-card">
                        <div class="adminx-report-head">
                            <h6>Sales Report</h6>
                            <small>Visual status modul admin</small>
                        </div>
                        <div class="adminx-line-legend">
                            <div><i></i> Data aktif</div>
                            <div><i></i> Data referensi</div>
                        </div>
                        <div class="adminx-bar-chart">
                            <div><span style="height: 70%"></span></div>
                            <div><span style="height: 45%"></span></div>
                            <div><span style="height: 88%"></span></div>
                            <div><span style="height: 52%"></span></div>
                            <div><span style="height: 76%"></span></div>
                        </div>
                    </article>
                </section>

                <section class="admin-grid-cards">
                    <article class="admin-stat-card adminx-stat-one">
                        <div class="icon"><i class="bi bi-diagram-3"></i></div>
                        <div>
                            <h6>Jumlah Jurusan</h6>
                            <strong>{{ $jurusan->count() }}</strong>
                        </div>
                    </article>
                    <article class="admin-stat-card adminx-stat-two">
                        <div class="icon"><i class="bi bi-mortarboard"></i></div>
                        <div>
                            <h6>Jumlah Prodi</h6>
                            <strong>{{ $prodi->count() }}</strong>
                        </div>
                    </article>
                    <article class="admin-stat-card adminx-stat-three">
                        <div class="icon"><i class="bi bi-bookmarks"></i></div>
                        <div>
                            <h6>Jumlah Tema</h6>
                            <strong>{{ $tema->count() }}</strong>
                        </div>
                    </article>
                </section>

                @if(!$isSuperAdmin)
                    <div class="alert alert-warning admin-alert" role="alert">
                        <i class="bi bi-shield-lock"></i>
                        Anda login sebagai Admin Biasa. Aksi hapus hanya tersedia untuk Super Admin.
                    </div>
                @endif

                <section class="admin-panel" id="panel-jurusan">
                    <div class="admin-panel-head">
                        <h5>Kelola Jurusan</h5>
                        <small>Update nama jurusan atau hapus data jurusan.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 8%">ID</th>
                                    <th style="width: 34%">Nama Jurusan</th>
                                    <th style="width: 30%">Rumpun</th>
                                    <th style="width: 28%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jurusan as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->id_jurusan }}</td>
                                        <td>
                                            <form action="{{ route('admin.jurusan.update', $item->id_jurusan) }}" method="POST" class="admin-form-row">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="nama_jurusan" class="form-control form-control-sm" value="{{ $item->nama_jurusan }}" required maxlength="100">
                                        </td>
                                        <td>
                                                <select name="id_rumpun" class="form-select form-select-sm">
                                                    <option value="">Tanpa Rumpun</option>
                                                    @foreach($rumpun as $rumpunItem)
                                                        <option value="{{ $rumpunItem->id_rumpun }}" {{ (string) $item->id_rumpun === (string) $rumpunItem->id_rumpun ? 'selected' : '' }}>
                                                            {{ $rumpunItem->nama_rumpun }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td>
                                                <div class="admin-actions">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-save"></i> Update
                                                    </button>
                                            </form>
                                            @if($isSuperAdmin)
                                                <form action="{{ route('admin.jurusan.delete', $item->id_jurusan) }}" method="POST" onsubmit="return confirm('Hapus jurusan ini? Prodi terkait juga akan dihapus.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                                </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Data jurusan belum tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="admin-panel" id="panel-prodi">
                    <div class="admin-panel-head">
                        <h5>Kelola Program Studi</h5>
                        <small>Update prodi dan relasi jurusan atau hapus data prodi.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 8%">ID</th>
                                    <th style="width: 32%">Nama Prodi</th>
                                    <th style="width: 32%">Jurusan</th>
                                    <th style="width: 28%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prodi as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->id_prodi }}</td>
                                        <td>
                                            <form action="{{ route('admin.prodi.update', $item->id_prodi) }}" method="POST" class="admin-form-row">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="nama_prodi" class="form-control form-control-sm" value="{{ $item->nama_prodi }}" required maxlength="100">
                                        </td>
                                        <td>
                                                <select name="id_jurusan" class="form-select form-select-sm" required>
                                                    @foreach($jurusan as $jurusanItem)
                                                        <option value="{{ $jurusanItem->id_jurusan }}" {{ (string) $item->id_jurusan === (string) $jurusanItem->id_jurusan ? 'selected' : '' }}>
                                                            {{ $jurusanItem->nama_jurusan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td>
                                                <div class="admin-actions">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-save"></i> Update
                                                    </button>
                                            </form>
                                            @if($isSuperAdmin)
                                                <form action="{{ route('admin.prodi.delete', $item->id_prodi) }}" method="POST" onsubmit="return confirm('Hapus prodi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                                </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Data prodi belum tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="admin-panel" id="panel-tema">
                    <div class="admin-panel-head">
                        <h5>Kelola Tema</h5>
                        <small>Update kode/nama tema atau hapus tema yang tidak digunakan.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 8%">ID</th>
                                    <th style="width: 16%">Kode</th>
                                    <th style="width: 30%">Nama Tema</th>
                                    <th style="width: 24%">Rumpun</th>
                                    <th style="width: 22%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tema as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->id_tema }}</td>
                                        <td>
                                            <form action="{{ route('admin.tema.update', $item->id_tema) }}" method="POST" class="admin-form-row">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="kode_tema" class="form-control form-control-sm" value="{{ $item->kode_tema }}" maxlength="50" placeholder="Kode tema">
                                        </td>
                                        <td>
                                                <input type="text" name="nama_tema" class="form-control form-control-sm" value="{{ $item->nama_tema }}" required maxlength="100">
                                        </td>
                                        <td>
                                                <select name="id_rumpun" class="form-select form-select-sm">
                                                    <option value="">Tanpa Rumpun</option>
                                                    @foreach($rumpun as $rumpunItem)
                                                        <option value="{{ $rumpunItem->id_rumpun }}" {{ (string) $item->id_rumpun === (string) $rumpunItem->id_rumpun ? 'selected' : '' }}>
                                                            {{ $rumpunItem->nama_rumpun }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td>
                                                <div class="admin-actions">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-save"></i> Update
                                                    </button>
                                            </form>
                                            @if($isSuperAdmin)
                                                <form action="{{ route('admin.tema.delete', $item->id_tema) }}" method="POST" onsubmit="return confirm('Hapus tema ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                                </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Data tema belum tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminxSidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
