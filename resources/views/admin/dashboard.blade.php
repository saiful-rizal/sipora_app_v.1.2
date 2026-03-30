@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page_label', 'Dashboard Admin')

@section('content')

    <div class="admin-header mb-4">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
            Panel Admin
        </span>
        <h2 class="fw-bold mt-2">Dashboard Admin</h2>
        <p class="text-muted">Tampilan ringkas untuk akses cepat ke semua modul pengelolaan.</p>
    </div>

    @if (!$isSuperAdmin)
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock"></i>
            Anda login sebagai Admin Biasa. Aksi sensitif dibatasi.
        </div>
    @endif

    <!-- ===== CARD STAT ===== -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card stat-card blue">
                <i class="bi bi-diagram-3"></i>
                <h6>Jumlah Jurusan</h6>
                <h3>{{ $counts['jurusan'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card soft">
                <i class="bi bi-mortarboard"></i>
                <h6>Jumlah Prodi</h6>
                <h3>{{ $counts['prodi'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card blue">
                <i class="bi bi-bookmarks"></i>
                <h6>Jumlah Tema</h6>
                <h3>{{ $counts['tema'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card soft">
                <i class="bi bi-people"></i>
                <h6>Jumlah User</h6>
                <h3>{{ $counts['users'] }}</h3>
            </div>
        </div>

    </div>

    <!-- ===== QUICK ACCESS ===== -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Akses Cepat</h5>
                    <small class="text-muted">Navigasi cepat ke fitur utama</small>
                </div>
                <span class="badge bg-primary-subtle text-primary">
                    <i class="bi bi-lightning-charge"></i> Quick Menu
                </span>
            </div>

            <div class="row g-2">

                <div class="col-md-3">
                    <a href="{{ route('admin.jurusan.index') }}" class="quick-btn">
                        <i class="bi bi-diagram-3"></i> Jurusan
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('admin.prodi.index') }}" class="quick-btn">
                        <i class="bi bi-mortarboard"></i> Prodi
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('admin.tema.index') }}" class="quick-btn">
                        <i class="bi bi-bookmarks"></i> Tema
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('admin.users.index') }}" class="quick-btn">
                        <i class="bi bi-people"></i> User
                    </a>
                </div>

            </div>

        </div>
    </div>
    <section class="admin-panel mt-4">
        <div class="admin-panel-head">
            <h5>Statistik Dokumen</h5>
            <small>Grafik jumlah dokumen berdasarkan status</small>
        </div>

        <div class="row p-3">
            <div class="col-md-6">
                <canvas id="barChart"></canvas>
            </div>

            <div class="col-md-6">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = @json($labels ?? []);
        const dataJumlah = @json($dataJumlah ?? []);

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: dataJumlah,
                    backgroundColor: [
                        '#36A2EB',
                        '#FF6384',
                        '#4BC0C0',
                        '#FFCE56',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: dataJumlah,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            }
        });
    </script>

@endsection
