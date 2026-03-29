@extends('admin.layout')

@section('title','Profil Admin')
@section('page_label','Profile')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Profile Saya</h4>
    <small class="text-muted">
        Kelola informasi akun dan keamanan
    </small>
</div>

<section class="admin-panel">

    <div class="row g-4">

        {{-- ===== PROFIL ===== --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-person"></i> Informasi Akun
                </h6>

                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                               class="form-control"
                               value="{{ $user['nama_lengkap'] }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username"
                               class="form-control"
                               value="{{ $user['username'] }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control"
                               value="{{ $user['email'] }}">
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </form>

            </div>

        </div>


        {{-- ===== PASSWORD ===== --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-lock"></i> Ubah Password
                </h6>

                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="old_password"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation"
                               class="form-control" required>
                    </div>

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-shield-lock"></i> Update Password
                    </button>
                </form>

            </div>

        </div>

    </div>

</section>


{{-- TOAST --}}
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast text-bg-success border-0">
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast text-bg-danger border-0">
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif

@endsection