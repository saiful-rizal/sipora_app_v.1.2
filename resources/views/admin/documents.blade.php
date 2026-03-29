@extends('admin.layout')

@section('title','Dokumen')
@section('page_label','Dokumen')

@section('content')

<div class="admin-header mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Modul Dokumen
    </span>
    <h2 class="fw-bold mt-2">Manajemen Dokumen</h2>
    <p class="text-muted">
        Fitur ini masih dalam tahap pengembangan.
    </p>
</div>

<section class="admin-panel text-center py-5">

    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>

    <h5 class="mt-3">Halaman Dokumen</h5>

    <p class="text-muted">
        Navigasi sudah aktif.<br>
        Data dokumen akan ditampilkan di sini nanti.
    </p>

    <button class="btn btn-primary mt-3" disabled>
        Tambah Dokumen
    </button>

</section>

@endsection