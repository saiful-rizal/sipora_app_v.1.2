@extends('admin.layout')

@section('title','Laporan Dokumen')
@section('page_label','Laporan Dokumen')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Laporan Dokumen
    </span>
    <h4 class="fw-bold mb-1">Laporan Dokumen</h4>
    <small class="text-muted">Filter dan ekspor data dokumen berdasarkan status dan periode waktu</small>
</div>

{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px">
                    <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Total</div>
                    <div class="fw-bold fs-4">{{ $summary['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px">
                    <i class="bi bi-check-circle text-success fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Approved</div>
                    <div class="fw-bold fs-4 text-success">{{ $summary['approved'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px">
                    <i class="bi bi-hourglass-split text-warning fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Pending</div>
                    <div class="fw-bold fs-4 text-warning">{{ $summary['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px">
                    <i class="bi bi-x-circle text-danger fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Rejected</div>
                    <div class="fw-bold fs-4 text-danger">{{ $summary['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="admin-panel">

    {{-- FILTER FORM --}}
    <form method="GET" action="{{ route('admin.documents.report') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            <div class="col-12 col-md-2">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status_id" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @php
                        $statusOptions = collect($status_options ?? []);
                    @endphp
                    @foreach($statusOptions as $status)
                        <option value="{{ $status->status_id }}" {{ (string) ($filters['status_id'] ?? '') === (string) $status->status_id ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2">
                <label class="form-label small fw-semibold mb-1">Jurusan</label>
                <select name="id_jurusan" class="form-select form-select-sm">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id_jurusan }}"
                            {{ ($filters['id_jurusan'] ?? '') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2">
                <label class="form-label small fw-semibold mb-1">Tanggal Dari</label>
                <input type="date" name="tgl_dari" class="form-control form-control-sm"
                       value="{{ $filters['tgl_dari'] ?? '' }}">
            </div>

            <div class="col-12 col-md-2">
                <label class="form-label small fw-semibold mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" class="form-control form-control-sm"
                       value="{{ $filters['tgl_sampai'] ?? '' }}">
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.documents.report') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
                <a href="{{ route('admin.documents.report') }}?{{ http_build_query(array_merge($filters, ['export' => 'excel'])) }}"
                   class="btn btn-success btn-sm px-3 ms-auto">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </a>
            </div>

        </div>
    </form>

    {{-- INFO HASIL --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-muted">Menampilkan <strong>{{ $dokumens->count() }}</strong> dokumen</small>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="table-report">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th style="min-width:180px">Judul</th>
                    <th style="min-width:140px">Uploader</th>
                    <th style="min-width:120px">Jurusan</th>
                    <th style="min-width:130px">Prodi</th>
                    <th style="min-width:100px">Tema</th>
                    <th style="min-width:80px">Tahun</th>
                    <th style="min-width:90px">Turnitin</th>
                    <th style="min-width:110px">Tgl Unggah</th>
                    <th style="min-width:110px">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokumens as $i => $item)
                @php
                    $namaStatus = strtolower($item->status->nama_status ?? '');
                    $isApproved = in_array($namaStatus, ['diterbitkan','approved','disetujui']);
                    $isRejected = in_array($namaStatus, ['ditolak','rejected']);
                    $badgeClass = $isApproved
                        ? 'bg-success-subtle text-success'
                        : ($isRejected ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning');
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <span class="fw-semibold d-inline-block text-truncate" style="max-width:160px"
                              title="{{ $item->judul }}">{{ $item->judul }}</span>
                    </td>
                    <td>{{ $item->uploader->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
                    <td>{{ $item->tema->nama_tema ?? '-' }}</td>
                    <td>{{ $item->year->tahun ?? $item->year->nama_tahun ?? '-' }}</td>
                    <td>
                        @if($item->turnitin)
                            <span class="badge {{ $item->turnitin <= 20 ? 'bg-success-subtle text-success' : ($item->turnitin <= 40 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}">
                                {{ $item->turnitin }}%
                            </span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $item->tgl_unggah ? \Carbon\Carbon::parse($item->tgl_unggah)->format('d M Y') : '-' }}</small>
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }}">
                            {{ $item->status->nama_status ?? 'Unknown' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data dokumen
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

@endsection
