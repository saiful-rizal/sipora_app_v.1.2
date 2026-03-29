@extends('admin.layout')

@section('title','Data Prodi')
@section('page_label','Prodi')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Modul Prodi
    </span>
    <h4 class="fw-bold mb-1">Pengelolaan Program Studi</h4>
    <small class="text-muted">
        Kelola data program studi dan relasi jurusan
    </small>
</div>

<section class="admin-panel">

    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- INFO (SIMPLE) --}}
        <div class="d-flex gap-2 flex-wrap">
            <div class="info-chip">
                <i class="bi bi-list-ul"></i>
                {{ $prodi->count() }}
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="table-prodi">

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

                    {{-- FORM --}}
                    <td>
                        <form action="{{ route('admin.prodi.update',$item->id_prodi) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="text" name="nama_prodi"
                                   class="form-control form-control-sm"
                                   value="{{ $item->nama_prodi }}"
                                   required>
                    </td>

                    <td>
                            <select name="id_jurusan" class="form-select form-select-sm" required>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}"
                                        {{ (string)$item->id_jurusan === (string)$j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                    </td>

                    <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary"
                                    onclick="return confirm('Simpan perubahan?')">
                                <i class="bi bi-save"></i>
                            </button>
                        </form>

                        @if($isSuperAdmin)
                        <form action="{{ route('admin.prodi.delete',$item->id_prodi) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus prodi ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data prodi
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection