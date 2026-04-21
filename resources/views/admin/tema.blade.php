@extends('admin.layout')

@section('title', 'Data Tema & Rumpun')
@section('page_label', 'Tema & Rumpun')
@section('search_target', '#table-tema')
@section('content')

<div class="mb-4">
    <h4 class="fw-bold">Data Tema & Rumpun</h4>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <button class="nav-link {{ session('active_tab') !== 'rumpun' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tema">Tema</button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ session('active_tab') === 'rumpun' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#rumpun">Rumpun</button>
    </li>
</ul>

<div class="tab-content">

{{-- ================= TEMA ================= --}}
<div class="tab-pane fade {{ session('active_tab') !== 'rumpun' ? 'show active' : '' }}" id="tema">

    {{-- FORM TAMBAH TEMA --}}
    <form action="{{ route('admin.tema.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2">
            <input type="text"
                   name="nama_tema"
                   class="form-control"
                   placeholder="Nama tema"
                   required>

            <select name="id_rumpun" class="form-control">
                <option value="">Pilih Rumpun</option>
                @foreach($rumpun as $r)
                    <option value="{{ $r->id_rumpun }}">{{ $r->nama_rumpun }}</option>
                @endforeach
            </select>

            <button class="btn btn-warning">Tambah</button>
        </div>
    </form>

    <div class="table-responsive">
    <table id="table-tema" class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Tema</th>
                <th>Rumpun</th>
                <th class="text-center" width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tema as $item)
            <tr>
                <form action="{{ route('admin.tema.update', $item->id_tema) }}" method="POST">
                    @csrf
                    @method('PUT')

                <td>
                    <input type="text"
                           name="nama_tema"
                           value="{{ $item->nama_tema }}"
                           class="form-control form-control-sm"
                           id="nama-tema-{{ $item->id_tema }}"
                           disabled
                           required>
                </td>

                <td>
                    <select name="id_rumpun"
                            class="form-select form-select-sm"
                            id="rumpun-tema-{{ $item->id_tema }}"
                            disabled>
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
                    <button type="button"
                            class="btn btn-sm btn-outline-warning"
                            onclick="enableEditTema({{ $item->id_tema }})">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button type="submit"
                            class="btn btn-sm btn-outline-primary d-none"
                            id="save-tema-{{ $item->id_tema }}">
                        <i class="bi bi-save"></i>
                    </button>
                </form>

                @if($isSuperAdmin)
                <form action="{{ route('admin.tema.delete', $item->id_tema) }}"
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
                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ================= RUMPUN ================= --}}
<div class="tab-pane fade {{ session('active_tab') === 'rumpun' ? 'show active' : '' }}" id="rumpun">

    {{-- FORM TAMBAH RUMPUN --}}
    <form action="{{ route('admin.rumpun.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2">
            <input type="text"
                   name="nama_rumpun"
                   class="form-control"
                   placeholder="Nama rumpun"
                   required>
            <button class="btn btn-secondary">Tambah</button>
        </div>
    </form>

    <div class="table-responsive">
    <table id="table-rumpun" class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Rumpun</th>
                <th class="text-center" width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rumpun as $item)
            <tr>
                <form action="{{ route('admin.rumpun.update', $item->id_rumpun) }}" method="POST">
                    @csrf
                    @method('PUT')

                <td>
                    <input type="text"
                           name="nama_rumpun"
                           value="{{ $item->nama_rumpun }}"
                           class="form-control form-control-sm"
                           id="rumpun-{{ $item->id_rumpun }}"
                           disabled
                           required>
                </td>

                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-outline-warning"
                            onclick="enableEditRumpun({{ $item->id_rumpun }})">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button type="submit"
                            class="btn btn-sm btn-outline-primary d-none"
                            id="save-rumpun-{{ $item->id_rumpun }}">
                        <i class="bi bi-save"></i>
                    </button>
                </form>

                @if($isSuperAdmin)
                <form action="{{ route('admin.rumpun.delete', $item->id_rumpun) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Hapus rumpun ini? Relasi di jurusan dan tema akan dilepas.')">
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
                <td colspan="2" class="text-center text-muted">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

</div>

@endsection

@push('scripts')
<script>
function enableEditTema(id) {
    document.getElementById('nama-tema-' + id).disabled = false;
    document.getElementById('rumpun-tema-' + id).disabled = false;
    document.getElementById('save-tema-' + id).classList.remove('d-none');
}

function enableEditRumpun(id) {
    document.getElementById('rumpun-' + id).disabled = false;
    document.getElementById('save-rumpun-' + id).classList.remove('d-none');
}
</script>
@endpush
