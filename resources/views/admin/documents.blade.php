@extends('admin.layout')

@section('title','Dokumen')
@section('page_label','Dokumen')
@section('search_target','#table-dokumen')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
        Modul Dokumen
    </span>
    <h4 class="fw-bold mb-1">Manajemen Dokumen</h4>
    <small class="text-muted">Kelola, verifikasi, dan moderasi dokumen yang diunggah mahasiswa</small>
</div>

<section class="admin-panel">

    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

        {{-- STATUS CHIPS --}}
        @php
            $totalApproved  = $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['diterbitkan','approved','disetujui']))->count();
            $totalPending   = $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['menunggu review','pending','draft']))->count();
            $totalRejected  = $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['ditolak','rejected']))->count();
            $totalPublished = $dokumens->filter(fn($d) => (bool) $d->is_published)->count();
        @endphp

        <div class="d-flex gap-2 flex-wrap">
            <div class="info-chip">
                <i class="bi bi-file-earmark-text"></i> {{ $dokumens->count() }}
            </div>
            <div class="info-chip success">
                <i class="bi bi-check-circle"></i> {{ $totalApproved }}
            </div>
            <div class="info-chip warning">
                <i class="bi bi-hourglass-split"></i> {{ $totalPending }}
            </div>
            <div class="info-chip danger">
                <i class="bi bi-x-circle"></i> {{ $totalRejected }}
            </div>
            <div class="info-chip" style="background:#e8f4fd;color:#0d6efd;border-color:#90c7f5">
                <i class="bi bi-broadcast"></i> {{ $totalPublished }} Publikasi
            </div>
        </div>

        {{-- FILTER --}}
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-primary active" onclick="filterStatus('all', this)">Semua</button>
            <button class="btn btn-sm btn-outline-primary" onclick="filterStatus('pending', this)">Pending</button>
            <button class="btn btn-sm btn-outline-primary" onclick="filterStatus('approved', this)">Approved</button>
            <button class="btn btn-sm btn-outline-primary" onclick="filterStatus('rejected', this)">Rejected</button>
            <button class="btn btn-sm btn-outline-primary" onclick="filterStatus('published', this)">Publikasi</button>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="table-dokumen">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th style="min-width:140px">Uploader</th>
                    <th style="min-width:180px">Judul</th>
                    <th style="min-width:190px">Abstrak</th>
                    <th style="min-width:120px">Tema</th>
                    <th style="min-width:130px">Jurusan</th>
                    <th style="min-width:140px">Prodi</th>
                    <th style="min-width:110px">Divisi</th>
                    <th style="min-width:90px">Tahun</th>
                    <th style="min-width:150px">Kata Kunci</th>
                    <th style="min-width:110px">Turnitin</th>
                    <th style="min-width:100px">File</th>
                    <th style="min-width:110px">Tgl Unggah</th>
                    <th style="min-width:110px">Status</th>
                    <th style="min-width:110px">Publikasi</th>
                    <th class="text-center" style="min-width:200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokumens as $item)
                @php
                    $namaStatus  = strtolower($item->status->nama_status ?? '');
                    $isApproved  = in_array($namaStatus, ['diterbitkan','approved','disetujui']);
                    $isRejected  = in_array($namaStatus, ['ditolak','rejected']);
                    $isPending   = !$isApproved && !$isRejected;

                    // Cast eksplisit agar nilai 0/1 dari DB konsisten
                    $isPublished = (bool) $item->is_published;

                    // Filter row: published override semua
                    $filterKey   = $isPublished ? 'published' : ($isApproved ? 'approved' : ($isRejected ? 'rejected' : 'pending'));

                    $badgeClass  = $isApproved ? 'bg-success-subtle text-success'
                                 : ($isRejected ? 'bg-danger-subtle text-danger'
                                 : 'bg-warning-subtle text-warning');
                @endphp
                <tr data-status="{{ $filterKey }}">

                    <td>{{ $item->dokumen_id }}</td>

                    {{-- UPLOADER --}}
                    <td>
                        <span class="d-inline-flex align-items-center gap-1">
                            <span class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold"
                                  style="width:26px;height:26px;font-size:.7rem;flex-shrink:0">
                                {{ strtoupper(substr($item->uploader->nama_lengkap ?? '?', 0, 1)) }}
                            </span>
                            <span class="d-inline-block text-truncate" style="max-width:100px"
                                  title="{{ $item->uploader->nama_lengkap ?? '-' }}">
                                {{ $item->uploader->nama_lengkap ?? '-' }}
                            </span>
                        </span>
                    </td>

                    {{-- JUDUL --}}
                    <td>
                        <span class="fw-semibold d-inline-block text-truncate" style="max-width:160px"
                              title="{{ $item->judul }}">{{ $item->judul }}</span>
                    </td>

                    {{-- ABSTRAK --}}
                    <td>
                        @if($item->abstrak)
                            <span class="text-muted d-inline-block text-truncate" style="max-width:160px">
                                {{ $item->abstrak }}
                            </span>
                            <a href="#" class="d-block small"
                               onclick="showAbstrak(event, `{{ addslashes($item->abstrak) }}`)">
                                Lihat selengkapnya
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- TEMA --}}
                    <td>{{ $item->tema->nama_tema ?? '-' }}</td>

                    {{-- JURUSAN --}}
                    <td>{{ $item->jurusan->nama_jurusan ?? '-' }}</td>

                    {{-- PRODI --}}
                    <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>

                    {{-- DIVISI --}}
                    <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>

                    {{-- TAHUN --}}
                    <td>{{ $item->year->tahun ?? $item->year->nama_tahun ?? $item->year->year ?? '-' }}</td>

                    {{-- KATA KUNCI --}}
                    <td>
                        @if($item->kata_kunci)
                            @foreach(explode(',', $item->kata_kunci) as $kw)
                                <span class="badge bg-light text-dark border me-1 mb-1">{{ trim($kw) }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- TURNITIN --}}
                    <td>
                        @if($item->turnitin_file)
                            <a href="{{ asset('storage/' . $item->turnitin_file) }}" target="_blank"
                               class="btn btn-sm btn-outline-info d-block mb-1">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Lihat
                            </a>
                        @endif
                        @if($item->turnitin)
                            <span class="badge {{ $item->turnitin <= 20 ? 'bg-success-subtle text-success' : ($item->turnitin <= 40 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}">
                                {{ $item->turnitin }}%
                            </span>
                        @endif
                        @if(!$item->turnitin_file && !$item->turnitin)
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    {{-- FILE --}}
                    <td>
                        @if($item->file_path)
                            <a href="{{ asset('uploads/documents/' . $item->file_path) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    {{-- TGL UNGGAH --}}
                    <td>
                        <small>{{ $item->tgl_unggah ? \Carbon\Carbon::parse($item->tgl_unggah)->format('d M Y') : '-' }}</small>
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge {{ $badgeClass }}">
                            {{ $item->status->nama_status ?? 'Unknown' }}
                        </span>
                    </td>

                    {{-- PUBLIKASI --}}
                    <td>
                        @if($isPublished)
                            <span class="badge bg-primary-subtle text-primary d-block mb-1">
                                <i class="bi bi-broadcast me-1"></i>Dipublikasi
                            </span>
                            @if($item->nomor_surat)
                                <small class="text-muted d-block" style="font-size:.7rem">{{ $item->nomor_surat }}</small>
                            @endif
                            @if($item->published_at)
                                <small class="text-muted d-block" style="font-size:.7rem">
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                                </small>
                            @endif
                        @elseif($isApproved)
                            <span class="badge bg-secondary-subtle text-secondary">Belum dipublikasi</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-wrap">

                            {{-- Tombol Detail (selalu tampil) --}}
                            <button class="btn btn-sm btn-outline-secondary" title="Detail"
                                    onclick="openDetail({{ $item->dokumen_id }})">
                                <i class="bi bi-eye"></i>
                            </button>

                            @if($isPending)
                                {{-- PENDING: Approve & Reject --}}
                                <button class="btn btn-sm btn-outline-success" title="Approve"
                                        onclick="openApproveModal({{ $item->dokumen_id }}, '{{ addslashes($item->judul) }}')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Reject"
                                        onclick="openRejectModal({{ $item->dokumen_id }})">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            @elseif($isApproved && !$isPublished)
                                {{-- APPROVED, BELUM PUBLISH: Publikasi + Revoke + Reject --}}
                                <button class="btn btn-sm btn-outline-primary" title="Publikasi"
                                        onclick="openPublishModal({{ $item->dokumen_id }}, '{{ addslashes($item->judul) }}')">
                                    <i class="bi bi-broadcast"></i>
                                </button>
                                <form action="{{ route('admin.dokumen.revoke', $item->dokumen_id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Cabut Approval"
                                            onclick="return confirm('Cabut status approved?')">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-danger" title="Reject"
                                        onclick="openRejectModal({{ $item->dokumen_id }})">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            @elseif($isApproved && $isPublished)
                                {{-- SUDAH DIPUBLIKASI: Cetak Surat --}}
                                <a href="{{ route('admin.dokumen.surat', $item->dokumen_id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary"
                                   title="Cetak Surat Publikasi (PDF)">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Cetak Surat
                                </a>

                            @elseif($isRejected)
                                {{-- REJECTED: Revoke + Delete --}}
                                <form action="{{ route('admin.dokumen.revoke', $item->dokumen_id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Kembalikan ke Pending"
                                            onclick="return confirm('Kembalikan ke pending?')">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.dokumen.destroy', $item->dokumen_id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permanen"
                                            onclick="return confirm('Hapus permanen? Tidak bisa dibatalkan!')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="16" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data dokumen
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

{{-- MODAL APPROVE --}}
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-2">
                <div class="mb-3">
                    <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center"
                         style="width:72px;height:72px">
                        <i class="bi bi-check-circle-fill text-success" style="font-size:2rem"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Approve Dokumen?</h5>
                <p class="text-muted mb-1">Dokumen berikut akan disetujui dan diterbitkan:</p>
                <p class="fw-semibold text-dark" id="approveTitleText" style="font-size:0.95rem"></p>
                <p class="text-muted small">Email notifikasi akan dikirim ke pengirim dokumen.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <form id="approveForm" method="POST" class="d-inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-lg me-1"></i>Ya, Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PUBLIKASI --}}
<div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-2">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center"
                         style="width:72px;height:72px">
                        <i class="bi bi-broadcast-pin text-primary" style="font-size:2rem"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Publikasi Dokumen?</h5>
                <p class="text-muted mb-1">Dokumen berikut akan dipublikasi secara resmi:</p>
                <p class="fw-semibold text-dark" id="publishTitleText" style="font-size:0.95rem"></p>
                <div class="alert alert-info text-start small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah dipublikasi, sistem akan otomatis:
                    <ul class="mb-0 mt-1 ps-3">
                        <li>Menerbitkan nomor surat publikasi unik</li>
                        <li>Mencatat tanggal publikasi resmi</li>
                        <li>Mengirimkan email notifikasi ke pengirim</li>
                        <li>Menghasilkan surat keterangan yang dapat dicetak</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-2">
                <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <form id="publishForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-broadcast me-1"></i>Ya, Publikasi Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-x-circle text-danger me-2"></i>Reject Dokumen</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- Form reject dengan opsi kirim file (fitur dari main) --}}
            <form id="rejectForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_reject" class="form-control" rows="3"
                                  placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Kirim File ke Pengirim</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opsi_file"
                                       id="opsiFileOriginal" value="original" checked
                                       onchange="toggleFileUpload(this.value)">
                                <label class="form-check-label" for="opsiFileOriginal">
                                    <i class="bi bi-file-earmark-arrow-up me-1 text-primary"></i>
                                    Kembalikan file dokumen asli milik pengirim
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opsi_file"
                                       id="opsiFileReviewed" value="reviewed"
                                       onchange="toggleFileUpload(this.value)">
                                <label class="form-check-label" for="opsiFileReviewed">
                                    <i class="bi bi-file-earmark-check me-1 text-success"></i>
                                    Kirim file yang sudah direview/dikoreksi oleh admin
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opsi_file"
                                       id="opsiFileTidak" value="tidak"
                                       onchange="toggleFileUpload(this.value)">
                                <label class="form-check-label" for="opsiFileTidak">
                                    <i class="bi bi-x me-1 text-danger"></i>
                                    Tidak perlu kirim file
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="uploadFileReviewed" style="display:none">
                        <label class="form-label fw-semibold small">
                            Upload File yang Sudah Direview <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file_reviewed" id="file_reviewed"
                               class="form-control form-control-sm"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                        <small class="text-muted">Format: PDF, DOC, DOCX, PPT, PPTX — Maks 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i>Ya, Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ABSTRAK --}}
<div class="modal fade" id="abstrakModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-card-text me-2"></i>Abstrak</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="abstrakContent" class="text-muted" style="white-space:pre-wrap"></p>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i>Detail Dokumen</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TOAST --}}
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast text-bg-success border-0 show">
        <div class="toast-body d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    </div>
</div>
@endif
@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="errorToast" class="toast text-bg-danger border-0 show">
        <div class="toast-body d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>

function filterStatus(status, btn) {
    document.querySelectorAll('.btn-outline-primary').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#table-dokumen tbody tr').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}

function openApproveModal(id, judul) {
    document.getElementById('approveForm').action = `/admin/documents/${id}/approve`;
    document.getElementById('approveTitleText').textContent = judul;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function openPublishModal(id, judul) {
    document.getElementById('publishForm').action = `/admin/documents/${id}/publish`;
    document.getElementById('publishTitleText').textContent = judul;
    new bootstrap.Modal(document.getElementById('publishModal')).show();
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/documents/${id}/reject`;
    document.getElementById('rejectForm').reset();
    document.getElementById('uploadFileReviewed').style.display = 'none';
    document.getElementById('file_reviewed').required = false;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function toggleFileUpload(val) {
    const uploadBox = document.getElementById('uploadFileReviewed');
    const fileInput = document.getElementById('file_reviewed');
    if (val === 'reviewed') {
        uploadBox.style.display = 'block';
        fileInput.required = true;
    } else {
        uploadBox.style.display = 'none';
        fileInput.required = false;
        fileInput.value = '';
    }
}

function showAbstrak(e, text) {
    e.preventDefault();
    document.getElementById('abstrakContent').textContent = text;
    new bootstrap.Modal(document.getElementById('abstrakModal')).show();
}

function openDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
    document.getElementById('detailModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Memuat data...</p>
        </div>`;

    fetch(`/admin/documents/${id}/detail`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        const namaStatus = d.status?.nama_status ?? '-';
        const isApproved = ['diterbitkan','approved','disetujui'].includes(namaStatus.toLowerCase());
        const isRejected = ['ditolak','rejected'].includes(namaStatus.toLowerCase());
        const color      = isApproved ? 'success' : (isRejected ? 'danger' : 'warning');
        const isPublished = d.is_published === true || d.is_published === 1;

        const kataKunci = d.kata_kunci
            ? d.kata_kunci.split(',').map(k => `<span class="badge bg-light text-dark border me-1">${k.trim()}</span>`).join('')
            : '-';

        const turnitin = d.turnitin_file
            ? `<a href="/storage/${d.turnitin_file}" target="_blank" class="btn btn-sm btn-outline-info me-1"><i class="bi bi-file-earmark-pdf me-1"></i>Lihat</a>`
            : '';
        const skor = d.turnitin
            ? `<span class="badge ${d.turnitin <= 20 ? 'bg-success-subtle text-success' : d.turnitin <= 40 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger'}">${d.turnitin}%</span>`
            : '';

        const fileDokumen = d.file_path
            ? `<a href="/uploads/documents/${d.file_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                   <i class="bi bi-file-earmark-arrow-down me-1"></i>Buka / Unduh Dokumen
               </a>`
            : '<span class="text-muted">-</span>';

        const publikasiBlock = isPublished
            ? `<div class="col-12">
                   <div class="alert alert-primary d-flex align-items-center gap-2 mb-0 py-2">
                       <i class="bi bi-broadcast-pin fs-5"></i>
                       <div>
                           <strong>Sudah Dipublikasi</strong>
                           <div class="small">Nomor Surat: <strong>${d.nomor_surat ?? '-'}</strong></div>
                           <div class="small">Tanggal: ${d.published_at ? new Date(d.published_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'}</div>
                       </div>
                       <a href="/admin/documents/${d.dokumen_id}/surat" target="_blank"
                          class="btn btn-sm btn-primary ms-auto">
                           <i class="bi bi-file-earmark-pdf me-1"></i>Cetak Surat PDF
                       </a>
                   </div>
               </div>`
            : '';

        document.getElementById('detailModalBody').innerHTML = `
        <div class="row g-3">
            <div class="col-md-9">
                <h5 class="fw-bold">${d.judul ?? '-'}</h5>
                <p class="text-muted">${d.abstrak ?? '<em>Tidak ada abstrak</em>'}</p>
            </div>
            <div class="col-md-3 text-md-end">
                <span class="badge bg-${color}-subtle text-${color} fs-6">${namaStatus}</span>
            </div>
            ${publikasiBlock}
            <div class="col-12"><hr class="my-1"></div>
            <div class="col-md-4">
                <small class="text-muted d-block">Uploader</small>
                <strong>${d.uploader?.nama_lengkap ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Tema</small>
                <strong>${d.tema?.nama_tema ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Jurusan</small>
                <strong>${d.jurusan?.nama_jurusan ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Program Studi</small>
                <strong>${d.prodi?.nama_prodi ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Divisi</small>
                <strong>${d.divisi?.nama_divisi ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Tahun</small>
                <strong>${d.year?.tahun ?? d.year?.nama_tahun ?? d.year?.year ?? '-'}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Tgl Unggah</small>
                <strong>${d.tgl_unggah ? new Date(d.tgl_unggah).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-'}</strong>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">Kata Kunci</small>
                <div class="mt-1">${kataKunci}</div>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">Turnitin</small>
                <div class="mt-1">${turnitin}${skor || (!turnitin ? '-' : '')}</div>
            </div>
            <div class="col-12">
                <small class="text-muted d-block mb-1">File Dokumen</small>
                ${fileDokumen}
            </div>
        </div>`;
    })
    .catch(() => {
        document.getElementById('detailModalBody').innerHTML =
            `<div class="alert alert-danger">Gagal memuat data dokumen.</div>`;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    ['liveToast','errorToast'].forEach(id => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => bootstrap.Toast.getOrCreateInstance(el).hide(), 3500);
    });
});
</script>
@endpush
