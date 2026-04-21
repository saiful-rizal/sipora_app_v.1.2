@extends('admin.layout')

@section('title', 'Data Jurusan & Prodi')
@section('page_label', 'Jurusan & Prodi')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold">Data Jurusan & Prodi</h4>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#jurusan">Jurusan</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#prodi">Prodi</button>
    </li>
</ul>

<div class="tab-content">

{{-- ================= JURUSAN ================= --}}
<div class="tab-pane fade show active" id="jurusan">

    {{-- FORM TAMBAH --}}
    <form action="{{ route('admin.jurusan.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2">
            <input type="text" name="nama_jurusan" class="form-control" placeholder="Nama jurusan" required>
            <button class="btn btn-primary">Tambah</button>
        </div>
    </form>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Jurusan</th>
                <th>Jumlah Prodi</th>
                <th class="text-center" width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurusan as $item)
            <tr>

                <form action="{{ route('admin.jurusan.update',$item->id_jurusan) }}" method="POST">
                    @csrf
                    @method('PUT')

                <td>
                    <input type="text"
                           name="nama_jurusan"
                           value="{{ $item->nama_jurusan }}"
                           class="form-control form-control-sm"
                           id="jurusan-{{ $item->id_jurusan }}"
                           disabled
                           required>
                </td>

                <td>
                    {{ $item->total_prodi }}
                </td>

                <td class="text-center">

                    <button type="button"
                            class="btn btn-sm btn-outline-warning"
                            onclick="enableEditJurusan({{ $item->id_jurusan }})">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button type="submit"
                            class="btn btn-sm btn-outline-primary d-none"
                            id="save-jurusan-{{ $item->id_jurusan }}">
                        <i class="bi bi-save"></i>
                    </button>

                </form>

                @if($isSuperAdmin)
                <form action="{{ route('admin.jurusan.delete',$item->id_jurusan) }}"
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
                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ================= PRODI ================= --}}
<div class="tab-pane fade" id="prodi">

    <form action="{{ route('admin.prodi.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2">
            <input type="text" name="nama_prodi" class="form-control" placeholder="Nama prodi" required>

            <select name="id_jurusan" class="form-control" required>
                <option value="">Pilih Jurusan</option>
                @foreach($jurusan as $j)
                <option value="{{ $j->id_jurusan }}">{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>

            <button class="btn btn-success">Tambah</button>
        </div>
    </form>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Prodi</th>
                <th>Jurusan</th>
                <th class="text-center" width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prodi as $item)
            <tr>

                <td>
                    <form action="{{ route('admin.prodi.update',$item->id_prodi) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PUT')

                        <input type="text"
                               name="nama_prodi"
                               value="{{ $item->nama_prodi }}"
                               class="form-control form-control-sm"
                               required>
                </td>

                <td>
                        <select name="id_jurusan" class="form-control form-control-sm" required>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}"
                                    {{ $j->id_jurusan == $item->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                </td>

                <td class="text-center">
                        <button class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
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
                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
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
function enableEditJurusan(id) {
    document.getElementById('jurusan-' + id).disabled = false;
    document.getElementById('save-jurusan-' + id).classList.remove('d-none');
}
</script>
@endpush
