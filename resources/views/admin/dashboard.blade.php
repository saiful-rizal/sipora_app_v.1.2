@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page_label', 'Dashboard Admin')

@section('content')
    <div class="admin-head">
        <div>
            <span class="admin-badge">Panel Admin</span>
            <h1 class="admin-title">Dashboard Admin</h1>
            <p class="admin-subtitle">Tampilan ringkas untuk akses cepat ke semua modul pengelolaan.</p>
        </div>
    </div>

    @if(!$isSuperAdmin)
        <div class="alert alert-warning admin-alert" role="alert">
            <i class="bi bi-shield-lock"></i>
            Anda login sebagai Admin Biasa. Aksi sensitif tetap dibatasi untuk Super Admin.
        </div>
    @endif

    <section class="admin-grid-cards">
        <article class="admin-stat-card adminx-stat-one">
            <div class="icon"><i class="bi bi-diagram-3"></i></div>
            <div>
                <h6>Jumlah Jurusan</h6>
                <strong>{{ $counts['jurusan'] }}</strong>
            </div>
        </article>
        <article class="admin-stat-card adminx-stat-two">
            <div class="icon"><i class="bi bi-mortarboard"></i></div>
            <div>
                <h6>Jumlah Prodi</h6>
                <strong>{{ $counts['prodi'] }}</strong>
            </div>
        </article>
        <article class="admin-stat-card adminx-stat-three">
            <div class="icon"><i class="bi bi-bookmarks"></i></div>
            <div>
                <h6>Jumlah Tema</h6>
                <strong>{{ $counts['tema'] }}</strong>
            </div>
        </article>
        <article class="admin-stat-card adminx-stat-four">
            <div class="icon"><i class="bi bi-people"></i></div>
            <div>
                <h6>Jumlah User</h6>
                <strong>{{ $counts['users'] }}</strong>
            </div>
        </article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head d-flex justify-content-between align-items-center">
            <div>
                <h5>Akses Cepat</h5>
                <small>Pilih menu sesuai pekerjaan harian Anda.</small>
            </div>
            <span class="adminx-badge-inline"><i class="bi bi-lightning-charge"></i> Navigasi cepat</span>
        </div>
        <div class="p-3 d-grid gap-2" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-outline-primary text-start"><i class="bi bi-diagram-3 me-1"></i> Kelola Jurusan</a>
            <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-primary text-start"><i class="bi bi-mortarboard me-1"></i> Kelola Prodi</a>
            <a href="{{ route('admin.tema.index') }}" class="btn btn-outline-primary text-start"><i class="bi bi-bookmarks me-1"></i> Kelola Tema</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary text-start"><i class="bi bi-people me-1"></i> Kelola User</a>
        </div>
    </section>
@endsection
