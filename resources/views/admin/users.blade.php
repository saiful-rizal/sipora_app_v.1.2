@extends('admin.layout')

@section('title','Pengelolaan User')
@section('page_label','User')
@section('search_target','#table-users')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Modul Pengguna
    </span>
    <h4 class="fw-bold mb-1">Pengelolaan Pengguna</h4>
    <small class="text-muted">
        Kelola akun, role, dan status pengguna
    </small>
</div>

@if(!$isSuperAdmin)
<div class="alert alert-warning py-2">
    <i class="bi bi-shield-lock"></i>
    Akses terbatas: hanya bisa ubah user non-admin
</div>
@endif

<section class="admin-panel">

    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- INFO --}}
        <div class="d-flex gap-2 flex-wrap">

            <div class="info-chip">
                <i class="bi bi-people"></i>
                {{ $users->count() }}
            </div>

            <div class="info-chip success">
                <i class="bi bi-check-circle"></i>
                {{ $users->where('status','approved')->count() }}
            </div>

            <div class="info-chip warning">
                <i class="bi bi-hourglass-split"></i>
                {{ $users->where('status','pending')->count() }}
            </div>

            <div class="info-chip danger">
                <i class="bi bi-x-circle"></i>
                {{ $users->where('status','rejected')->count() }}
            </div>

        </div>

        <div class="d-flex gap-2 mb-3">

            <button class="btn btn-sm btn-outline-primary active"
                    onclick="filterRole('all', this)">
                Semua
            </button>

            <button class="btn btn-sm btn-outline-primary"
                    onclick="filterRole('admin', this)">
                Admin
            </button>

            <button class="btn btn-sm btn-outline-primary"
                    onclick="filterRole('mahasiswa', this)">
                Mahasiswa
            </button>

        </div>

        {{-- BUTTON --}}
        @if($isSuperAdmin)
        <button class="btn btn-primary d-flex align-items-center gap-2 px-3"
                onclick="openSlide()">
            <i class="bi bi-plus-circle"></i>
            Tambah Admin
        </button>
        @endif

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="table-users">

            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>NIM</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $item)
                    @php
                        $lockedByRole = !$isSuperAdmin && in_array((string)$item->role, ['admin','superadmin'], true);
                    @endphp


<tr data-role="{{ strtolower($item->role) }}">
    <td>{{ $item->id_user }}</td>
    <td>{{ $item->nama_lengkap }}</td>
    <td>{{ $item->username }}</td>
    <td>{{ $item->email }}</td>
    <td>{{ $item->nim ?: '-' }}</td>

    <td colspan="3"> <!-- role + status + tombol save -->
        <form action="{{ route('admin.users.update',$item->id_user) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            @method('PUT')

            <select name="role" class="form-select form-select-sm" style="width:120px" {{ $isSuperAdmin ? '' : 'disabled' }}>
                <option value="superadmin" {{ $item->role=='superadmin'?'selected':'' }}>Super Admin</option>
                <option value="admin" {{ $item->role=='admin'?'selected':'' }}>Admin</option>
                <option value="mahasiswa" {{ $item->role=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
            </select>

            <select name="status" class="form-select form-select-sm" style="width:120px" {{ $lockedByRole?'disabled':'' }}>
                <option value="pending" {{ $item->status=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ $item->status=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ $item->status=='rejected'?'selected':'' }}>Rejected</option>
            </select>

            <button type="button" class="btn btn-sm btn-outline-primary btn-save" {{ $lockedByRole?'disabled':'' }}>
                <i class="bi bi-save"></i>
            </button>
        </form>
    </td>
</tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data user
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

{{-- ===== SLIDE PANEL ===== --}}
@if($isSuperAdmin)
<div id="slidePanel" class="slide-panel">

    <div class="slide-header">
        <h6 class="fw-semibold mb-0">Tambah Admin</h6>
        <button type="button" onclick="closeSlide()">×</button>
    </div>

    <form method="POST" action="{{ route('admin.users.store-admin') }}">
        @csrf

        <input type="text" name="nama_lengkap" class="form-control mb-2" placeholder="Nama Lengkap" required>
        <input type="text" name="nim" class="form-control mb-2" placeholder="NIM / NIP" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Konfirmasi Password" required>

        <button class="btn btn-primary w-100">
            Simpan Admin
        </button>
    </form>

</div>

<div id="overlay" class="overlay" onclick="closeSlide()"></div>
@endif

{{-- ===== TOAST SUCCESS ===== --}}
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast text-bg-success border-0">
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

<!-- ===== MODAL KONFIRMASI SAVE ===== -->
<div class="modal fade" id="confirmSaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Konfirmasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Simpan perubahan user ini?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-primary" id="confirmSaveBtn">
                    Ya, Simpan
                </button>
            </div>

        </div>
    </div>
</div>
 
@endsection

@push('scripts')

<script>
function filterRole(role, btn){

    document.querySelectorAll('.btn-outline-primary').forEach(b => {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    document.querySelectorAll('#table-users tbody tr').forEach(row => {

        const rowRole = row.dataset.role;

        if(role === 'all'){
            row.style.display = '';
        }
        else if(role === 'admin'){
            if(rowRole === 'admin' || rowRole === 'superadmin'){
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
        else if(role === 'mahasiswa'){
            row.style.display = (rowRole === 'mahasiswa') ? '' : 'none';
        }

    });
}
</script>

<script>

document.addEventListener('DOMContentLoaded', function(){

    let selectedForm = null;

    const modal = new bootstrap.Modal(document.getElementById('confirmSaveModal'));

    // klik tombol save
    document.querySelectorAll('.btn-save').forEach(btn => {
        btn.addEventListener('click', function(){

            selectedForm = this.closest('form');

            modal.show();
        });
    });

    // klik "Ya, Simpan"
    document.getElementById('confirmSaveBtn').addEventListener('click', function(){
        if(selectedForm){
            selectedForm.submit();
        }
    });

});

/* SLIDE */
function openSlide(){
    document.getElementById('slidePanel')?.classList.add('open');
    document.getElementById('overlay')?.classList.add('show');
}
function closeSlide(){
    document.getElementById('slidePanel')?.classList.remove('open');
    document.getElementById('overlay')?.classList.remove('show');
}

</script>

@endpush