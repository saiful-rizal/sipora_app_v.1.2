@extends('admin.layout')

@section('title','Data Tema')
@section('page_label','Tema')
@section('search_target','#table-tema')
@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">Pengelolaan Tema</h4>
    <small class="text-muted">
        Kelola data tema yang digunakan dalam sistem
    </small>
</div>

<section class="admin-panel">

    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- INFO (SIMPLE, GA BERLEBIHAN) --}}
        <div class="d-flex gap-2 flex-wrap">
            <div class="info-chip">
                <i class="bi bi-list-ul"></i>
                {{ $tema->count() }}
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
    <table id="table-tema" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Tema</th>
                    <th>Rumpun</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tema as $item)
                <tr>
                    <td>{{ $item->id_tema }}</td>

                    {{-- FORM --}}
                    <td>
                        <form action="{{ route('admin.tema.update',$item->id_tema) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="text" name="kode_tema"
                                   class="form-control form-control-sm"
                                   value="{{ $item->kode_tema }}">
                    </td>

                    <td>
                            <input type="text" name="nama_tema"
                                   class="form-control form-control-sm"
                                   value="{{ $item->nama_tema }}"
                                   required>
                    </td>

                    <td>
                            <select name="id_rumpun" class="form-select form-select-sm">
                                <option value="">Tanpa Rumpun</option>
                                @foreach($rumpun as $r)
                                    <option value="{{ $r->id_rumpun }}"
                                        {{ (string)$item->id_rumpun === (string)$r->id_rumpun ? 'selected' : '' }}>
                                        {{ $r->nama_rumpun }}
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
                        <form action="{{ route('admin.tema.delete',$item->id_tema) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus tema ini?')">
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
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data tema
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection