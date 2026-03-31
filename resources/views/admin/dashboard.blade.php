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
    <div class="row mt-4">

        <!-- Bar Chart -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-semibold">Statistik Status Dokumen</h5>
                    <p class="text-muted small">Jumlah dokumen berdasarkan status</p>
                    <canvas id="statusBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="fw-semibold">Persentase Status Dokumen</h5>
                    <p class="text-muted small">Distribusi Dokumen</p>
                    <canvas id="statusPieChart" style="max-height:250px;"></canvas>
                </div>
            </div>
        </div>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = @json($labels);
        const dataJumlah = @json($dataJumlah);

        const colors = [
            "#6366F1",
            "#22C55E",
            "#F59E0B",
            "#EF4444",
            "#06B6D4"
        ];


        // BAR CHART
        new Chart(document.getElementById('statusBarChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: dataJumlah,
                    backgroundColor: colors,
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },

            }
        });


        // PIE / DOUGHNUT CHART
        new Chart(document.getElementById('statusPieChart'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataJumlah,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: "65%",
                plugins: {
                    legend: {
                        position: "bottom"
                    }
                }
            }
        });
    </script>

@endsection
