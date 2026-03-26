@extends('admin.layout')

@section('title', 'Data Tema')
@section('page_label', 'Data Tema')

@section('content')
    <div class="admin-head">
        <div>
            <span class="admin-badge">Master Data</span>
            <h1 class="admin-title">Kelola Tema</h1>
            <p class="admin-subtitle">Halaman khusus untuk pengelolaan tema.</p>
        </div>
    </div>

    <section class="admin-panel" id="panel-tema">
        <div class="admin-panel-head">
            <h5>Data Tema</h5>
            <small>Update kode/nama tema atau hapus tema yang tidak digunakan.</small>
        </div>
        <div class="px-3 pt-3 adminx-section-tools">
            <input type="text" class="adminx-search-input" placeholder="Cari kode, tema, atau rumpun..." data-table-search="#table-tema">
            <span class="adminx-badge-inline"><i class="bi bi-list-ul"></i> {{ $tema->count() }} data</span>
            <span class="adminx-help">Cari terlebih dahulu sebelum melakukan update.</span>
        </div>
        <div class="table-responsive">
            <table class="table admin-table align-middle" id="table-tema">
                <thead>
                    <tr>
                        <th style="width: 8%">ID</th>
                        <th style="width: 16%">Kode</th>
                        <th style="width: 30%">Nama Tema</th>
                        <th style="width: 24%">Rumpun</th>
                        <th style="width: 22%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tema as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->id_tema }}</td>
                            <td>
                                <form action="{{ route('admin.tema.update', $item->id_tema) }}" method="POST" class="admin-form-row">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="kode_tema" class="form-control form-control-sm" value="{{ $item->kode_tema }}" maxlength="50" placeholder="Kode tema">
                            </td>
                            <td>
                                    <input type="text" name="nama_tema" class="form-control form-control-sm" value="{{ $item->nama_tema }}" required maxlength="100">
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
                                    <form action="{{ route('admin.tema.delete', $item->id_tema) }}" method="POST" onsubmit="return confirm('Hapus tema ini?')">
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
                        <tr><td colspan="5" class="text-center py-4">Data tema belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
