@extends('admin.layout')

@section('title', 'Pengelolaan User')
@section('page_label', 'Pengelolaan User')

@section('content')
    <div class="admin-head">
        <div>
            <span class="admin-badge">Manajemen User</span>
            <h1 class="admin-title">Pengelolaan User</h1>
            <p class="admin-subtitle">Kelola status akun dan role user sesuai hak akses admin.</p>
        </div>
    </div>

    @if(!$isSuperAdmin)
        <div class="alert alert-warning admin-alert" role="alert">
            <i class="bi bi-shield-lock"></i>
            Admin biasa hanya dapat mengubah status akun user non-admin.
        </div>
    @endif

    <section class="admin-panel" id="panel-users">
        <div class="admin-panel-head">
            <h5>Daftar User</h5>
            <small>Perbarui status dan role user berdasarkan kewenangan.</small>
        </div>
        <div class="px-3 pt-3 adminx-section-tools">
            <input type="text" class="adminx-search-input" placeholder="Cari nama, username, email, role, atau status..." data-table-search="#table-users">
            <span class="adminx-badge-inline"><i class="bi bi-list-ul"></i> {{ $users->count() }} user</span>
            <span class="adminx-badge-inline"><i class="bi bi-person-check"></i> {{ $users->where('status', 'approved')->count() }} approved</span>
            <span class="adminx-help">Untuk admin biasa, data admin lain otomatis terkunci.</span>
        </div>
        <div class="table-responsive">
            <table class="table admin-table align-middle" id="table-users">
                <thead>
                    <tr>
                        <th style="width: 6%">ID</th>
                        <th style="width: 20%">Nama</th>
                        <th style="width: 12%">Username</th>
                        <th style="width: 18%">Email</th>
                        <th style="width: 10%">NIM</th>
                        <th style="width: 10%">Role</th>
                        <th style="width: 12%">Status</th>
                        <th style="width: 12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $item)
                        @php
                            $lockedByRole = !$isSuperAdmin && in_array((string) $item->role, ['admin', 'superadmin'], true);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $item->id_user }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->nim ?: '-' }}</td>
                            <td>
                                <form action="{{ route('admin.users.update', $item->id_user) }}" method="POST" class="admin-form-row">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm" {{ $isSuperAdmin ? '' : 'disabled' }}>
                                        <option value="superadmin" {{ $item->role === 'superadmin' ? 'selected' : '' }}>superadmin</option>
                                        <option value="admin" {{ $item->role === 'admin' ? 'selected' : '' }}>admin</option>
                                        <option value="mahasiswa" {{ $item->role === 'mahasiswa' ? 'selected' : '' }}>mahasiswa</option>
                                    </select>
                            </td>
                            <td>
                                    <select name="status" class="form-select form-select-sm" {{ $lockedByRole ? 'disabled' : '' }}>
                                        <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>pending</option>
                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>approved</option>
                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>rejected</option>
                                    </select>
                            </td>
                            <td>
                                    <button type="submit" class="btn btn-sm btn-primary" {{ $lockedByRole ? 'disabled' : '' }}>
                                        <i class="bi bi-save"></i> Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
