@extends('admin.layout')

@section('title', 'Data Jurusan')
@section('page_label', 'Jurusan')
@section('search_target','#table-jurusan')
@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Modul Jurusan
    </span>
    <h4 class="fw-bold mb-1">Data Jurusan</h4>
    <small class="text-muted">
        Kelola nama jurusan dan relasi rumpun
    </small>
</div>

<section class="admin-panel">

    {{-- INFO --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="info-chip">
            <i class="bi bi-list-ul"></i>
            {{ $jurusan->count() }} Data
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
    <table id="table-jurusan" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Jurusan</th>
                    <th>Rumpun</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jurusan as $item)
                <tr>
                    <td>{{ $item->id_jurusan }}</td>

                    <td>
                        <form action="{{ route('admin.jurusan.update', $item->id_jurusan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="text"
                                   name="nama_jurusan"
                                   class="form-control form-control-sm"
                                   value="{{ $item->nama_jurusan }}"
                                   required>
                    </td>

                    <td>
                            <select name="id_rumpun" class="form-select form-select-sm">
                                <option value="">-</option>
                                @foreach($rumpun as $r)
                                    <option value="{{ $r->id_rumpun }}"
                                        {{ $item->id_rumpun == $r->id_rumpun ? 'selected' : '' }}>
                                        {{ $r->nama_rumpun }}
                                    </option>
                                @endforeach
                            </select>
                    </td>

                    <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-save"></i>
                            </button>
                        </form>

                        @if($isSuperAdmin)
                        <form action="{{ route('admin.jurusan.delete', $item->id_jurusan) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus jurusan ini?')">
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
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection