@extends('admin.layout')

@section('title','Laporan User')
@section('page_label','Laporan User')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Laporan User
    </span>
    <h4 class="fw-bold mb-1">Laporan Pengguna</h4>
    <small class="text-muted">
        Filter dan ekspor data user berdasarkan role dan periode waktu
    </small>
</div>

<section class="admin-panel">

    {{-- TOP BAR (SAMA KAYAK USER MANAGEMENT) --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- INFO CHIP --}}
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

        {{-- EXPORT --}}
        <a href="{{ route('admin.users.report') }}?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}"
           class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>

    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.users.report') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="superadmin" {{ request('role')=='superadmin'?'selected':'' }}>Super Admin</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="mahasiswa" {{ request('role')=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Dari</label>
                <input type="date" name="tgl_dari" class="form-control form-control-sm"
                       value="{{ request('tgl_dari') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" class="form-control form-control-sm"
                       value="{{ request('tgl_sampai') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel"></i>
                </button>

                <a href="{{ route('admin.users.report') }}" class="btn btn-outline-secondary btn-sm">
                    Reset
                </a>
            </div>

        </div>
    </form>

    {{-- INFO HASIL --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-muted">
            Menampilkan <strong>{{ $users->count() }}</strong> user
        </small>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $u->nama_lengkap }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->nim ?? '-' }}</td>
                    <td>{{ $u->email }}</td>

                    {{-- ROLE --}}
                    <td>
                        <span class="badge 
                            {{ $u->role=='superadmin'?'bg-danger-subtle text-danger':
                               ($u->role=='admin'?'bg-primary-subtle text-primary':'bg-secondary-subtle text-secondary') }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge 
                            {{ $u->status=='approved'?'bg-success-subtle text-success':
                               ($u->status=='pending'?'bg-warning-subtle text-warning':'bg-danger-subtle text-danger') }}">
                            {{ ucfirst($u->status) }}
                        </span>
                    </td>

                    {{-- TANGGAL --}}
                    <td>
                        {{ $u->created_at 
                            ? \Carbon\Carbon::parse($u->created_at)->format('d M Y') 
                            : '-' }}
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data user
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection