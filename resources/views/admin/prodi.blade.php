@extends('admin.layout')

@section('title', 'Data Prodi')
@section('page_label', 'Prodi')
@section('search_target','#table-prodi')
@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
        Modul Prodi
    </span>
    <h4 class="fw-bold mb-1">Data Program Studi</h4>
    <small class="text-muted">
        Kelola nama program studi dan relasi jurusan
    </small>
</div>

{{-- ============================================================ --}}
{{-- FORM TAMBAH PRODI --}}
{{-- ============================================================ --}}
<section class="admin-panel mb-4">
    <h6 class="fw-semibold mb-3">
        <i class="bi bi-plus-circle me-1 text-success"></i> Tambah Program Studi
    </h6>

    <form action="{{ route('admin.prodi.store') }}" method="POST">
        @csrf

        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label form-label-sm mb-1">Nama Prodi <span class="text-danger">*</span></label>
                <input type="text"
                       name="nama_prodi"
                       class="form-control form-control-sm @error('nama_prodi') is-invalid @enderror"
                       placeholder="Contoh: S1 Teknik Informatika"
                       value="{{ old('nama_prodi') }}"
                       required>
                @error('nama_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Jurusan <span class="text-danger">*</span></label>
                <select name="id_jurusan"
                        class="form-select form-select-sm @error('id_jurusan') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}"
                            {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
                @error('id_jurusan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-success btn-sm w-100">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Prodi
                </button>
            </div>
        </div>
    </form>
</section>

{{-- ============================================================ --}}
{{-- TABEL DATA PRODI --}}
{{-- ============================================================ --}}
<section class="admin-panel">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="info-chip">
            <i class="bi bi-list-ul"></i>
            {{ $prodi->count() }} Data
        </div>
    </div>

    <div class="table-responsive">
        <table id="table-prodi" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Prodi</th>
                    <th>Jurusan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($prodi as $item)
                <tr>
                    <td>{{ $item->id_prodi }}</td>

                    <td>
                        <form action="{{ route('admin.prodi.update', $item->id_prodi) }}"
                              method="POST"
                              class="d-flex gap-2 align-items-center">
                            @csrf
                            @method('PUT')

                            <input type="text"
                                   name="nama_prodi"
                                   class="form-control form-control-sm"
                                   value="{{ $item->nama_prodi }}"
                                   required>
                    </td>

                    <td>
                            <select name="id_jurusan" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}"
                                        {{ $item->id_jurusan == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                    </td>

                    <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Simpan perubahan">
                                <i class="bi bi-save"></i>
                            </button>
                        </form>

                        @if($isSuperAdmin)
                        <form action="{{ route('admin.prodi.delete', $item->id_prodi) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus prodi ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger" title="Hapus prodi">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Belum ada data prodi
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection
