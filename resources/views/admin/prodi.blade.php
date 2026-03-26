@extends('admin.layout')

@section('title', 'Data Prodi')
@section('page_label', 'Data Prodi')

@section('content')
    <div class="admin-head">
        <div>
            <span class="admin-badge">Master Data</span>
            <h1 class="admin-title">Kelola Program Studi</h1>
            <p class="admin-subtitle">Halaman khusus untuk pengelolaan program studi.</p>
        </div>
    </div>

    <section class="admin-panel" id="panel-prodi">
        <div class="admin-panel-head">
            <h5>Data Prodi</h5>
            <small>Update prodi dan relasi jurusan atau hapus data prodi.</small>
        </div>
        <div class="px-3 pt-3 adminx-section-tools">
            <input type="text" class="adminx-search-input" placeholder="Cari prodi atau jurusan..." data-table-search="#table-prodi">
            <span class="adminx-badge-inline"><i class="bi bi-list-ul"></i> {{ $prodi->count() }} data</span>
            <span class="adminx-help">Pencarian bekerja langsung tanpa reload halaman.</span>
        </div>
        <div class="table-responsive">
            <table class="table admin-table align-middle" id="table-prodi">
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
@endsection
