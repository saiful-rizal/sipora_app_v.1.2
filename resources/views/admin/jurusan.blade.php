@extends('admin.layout')

@section('title', 'Data Jurusan')
@section('page_label', 'Data Jurusan')

@section('content')
    <div class="admin-head">
        <div>
            <span class="admin-badge">Master Data</span>
            <h1 class="admin-title">Kelola Jurusan</h1>
            <p class="admin-subtitle">Halaman khusus untuk pengelolaan jurusan.</p>
        </div>
    </div>

    <section class="admin-panel" id="panel-jurusan">
        <div class="admin-panel-head">
            <h5>Data Jurusan</h5>
            <small>Update nama jurusan atau hapus data jurusan.</small>
        </div>
        <div class="px-3 pt-3 adminx-section-tools">
            <input type="text" class="adminx-search-input" placeholder="Cari jurusan atau rumpun..." data-table-search="#table-jurusan">
            <span class="adminx-badge-inline"><i class="bi bi-list-ul"></i> {{ $jurusan->count() }} data</span>
            <span class="adminx-help">Gunakan pencarian untuk mempercepat edit data.</span>
        </div>
        <div class="table-responsive">
            <table class="table admin-table align-middle" id="table-jurusan">
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
@endsection
