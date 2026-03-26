<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
</head>
<body data-detail-url="{{ route('dashboard.get-detail') }}" data-share-base="{{ url('/dashboard') }}">
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    @include('components.navbar')

    <div class="browser-container browser-container--top">
        @include('components.header_dashboard')
    </div>

    <div class="browser-container">
        <div class="stats">
            <div class="stat-card">
                <i class="bi bi-file-earmark-text"></i>
                <div>
                    <h4>{{ $totalDokumen }}</h4>
                    <p>Total Dokumen</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="bi bi-cloud-upload"></i>
                <div>
                    <h4>{{ $uploadBaru }}</h4>
                    <p>Upload Baru</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="bi bi-pie-chart"></i>
                <div>
                    <h4>{{ $persentasePenggunaan }}%</h4>
                    <p>Penggunaan Bulan Ini</p>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h5>Dokumen Saya</h5>
            <div class="view-toggle">
                <button class="view-btn active" id="gridViewBtn" onclick="setViewMode('grid')">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="view-btn" id="listViewBtn" onclick="setViewMode('list')">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>

        @if($documents->isEmpty())
            <div class="empty-state">
                <div class="empty-state-card">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="empty-state-title">Tidak ada dokumen ditemukan</h3>
                    <p class="empty-state-description">Belum ada dokumen yang diunggah.</p>
                    <a href="{{ route('upload.index') }}" class="empty-state-action">
                        <i class="bi bi-cloud-upload"></i> Unggah Dokumen
                    </a>
                </div>
            </div>
        @else
            <div class="document-grid" id="documentGrid">
                @foreach($documents as $doc)
                    @php
                        $filePath = $doc['file_path'] ?? '';
                        $fileName = basename($filePath);
                        $fileURL = asset('uploads/documents/' . $fileName);
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $judul = $doc['judul'] ?? 'Tanpa Judul';
                        $abstrakRaw = $doc['abstrak'] ?? 'Tidak ada deskripsi';
                        $abstrak = \Illuminate\Support\Str::limit($abstrakRaw, 150);
                        $statusName = $doc['status_name'] ?? 'Unknown';
                        $statusBadge = $doc['status_badge'] ?? 'badge-secondary';
                    @endphp

                    <div class="document-card"
                        data-title="{{ strtolower($judul) }}"
                        data-description="{{ strtolower($abstrak) }}"
                        data-full-title="{{ $doc['judul'] ?? 'Tanpa Judul' }}"
                        data-full-description="{{ $doc['abstrak'] ?? 'Tidak ada deskripsi' }}"
                        data-uploader-name="{{ $doc['uploader_name'] ?? 'Admin' }}"
                        data-uploader-email="{{ $doc['uploader_email'] ?? '' }}"
                        data-nama-jurusan="{{ $doc['nama_jurusan'] ?? '' }}"
                        data-nama-prodi="{{ $doc['nama_prodi'] ?? '' }}"
                        data-nama-tema="{{ $doc['nama_tema'] ?? '' }}"
                        data-tahun="{{ $doc['tahun'] ?? '' }}"
                        data-status-id="{{ $doc['status_id'] ?? '' }}"
                        data-status-name="{{ $statusName }}"
                        data-status-badge="{{ $statusBadge }}"
                        data-turnitin="{{ $doc['turnitin'] ?? '' }}"
                        data-file-name="{{ $fileName }}"
                        data-file-size="{{ $doc['file_size'] ?? 0 }}"
                        data-tgl-unggah="{{ $doc['tgl_unggah'] ?? '' }}"
                        data-updated-at="{{ $doc['tgl_unggah'] ?? '' }}"
                        data-id-user="{{ $doc['id_user'] ?? '' }}"
                        data-id="{{ $doc['dokumen_id'] }}"
                        data-file-url="{{ $fileURL }}"
                        data-file-type="{{ $fileExt }}"
                        onclick="showDocumentPreview({{ (int) $doc['dokumen_id'] }}, @js($fileURL), @js($fileExt))"
                    >
                        <div class="document-thumbnail">
                            <i class="bi bi-file-earmark-text document-thumbnail-icon"></i>
                            <div class="document-thumbnail-text">{{ strtoupper($fileExt ?: 'FILE') }}</div>
                        </div>

                        <div class="document-content">
                            <div class="document-header">
                                <h6 class="document-title">{{ $judul }}</h6>
                                <div class="document-badges">
                                    @if(!empty($doc['status_id']) && is_numeric($doc['status_id']) && $doc['status_id'] > 0)
                                        <span class="badge {{ $statusBadge }}">{{ $statusName }}</span>
                                    @endif
                                    @if(!empty($doc['turnitin']) && is_numeric($doc['turnitin']) && $doc['turnitin'] > 0)
                                        <span class="badge badge-info" style="background-color: #cfe2ff; color: #084298;">T: {{ $doc['turnitin'] }}%</span>
                                    @endif
                                </div>
                            </div>

                            <div class="document-description">{{ $abstrak }}</div>

                            <div class="document-meta">
                                @if(!empty($doc['nama_jurusan']))
                                    <div class="document-meta-item">
                                        <i class="bi bi-briefcase"></i>
                                        <span>{{ \Illuminate\Support\Str::limit($doc['nama_jurusan'], 15, '') }}</span>
                                    </div>
                                @endif
                                @if(!empty($doc['tahun']))
                                    <div class="document-meta-item">
                                        <i class="bi bi-calendar3"></i>
                                        <span>{{ $doc['tahun'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="document-footer">
                                <div class="document-info">
                                    <div class="document-uploader">
                                        <i class="bi bi-person-circle"></i>
                                        <span>{{ \Illuminate\Support\Str::limit($doc['uploader_name'] ?? 'Admin', 12, '') }}</span>
                                    </div>
                                    <div class="document-date">{{ \Carbon\Carbon::parse($doc['tgl_unggah'] ?? 'now')->format('d M y') }}</div>
                                </div>

                                <div class="document-actions">
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="event.stopPropagation(); showDocumentDetail({{ $doc['dokumen_id'] }})">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <a href="{{ $fileURL }}" download class="btn-action btn-download" title="Unduh" onclick="event.stopPropagation()">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="browser-container browser-container--bottom">
        @include('components.footer_browser')
    </div>

    <div id="documentModal" class="document-modal">
        <div class="document-modal-dialog">
            <div class="document-modal-content">
                <div class="document-modal-header">
                    <h5 class="document-modal-title" id="modalTitle">Memuat Detail...</h5>
                    <button class="document-modal-close" onclick="closeDocumentModal()" aria-label="Tutup">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="document-modal-body">
                    <div class="document-detail-tabs">
                        <div class="document-detail-tab" data-tab="info">
                            <i class="bi bi-info-circle"></i> Informasi
                        </div>
                        <div class="document-detail-tab active" data-tab="preview">
                            <i class="bi bi-eye"></i> Pratinjau
                        </div>
                    </div>

                    <div class="document-detail-content" id="info-tab">
                        <div id="documentInfoContent">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Memuat...</span>
                                </div>
                                <p class="mt-2">Memuat informasi dokumen...</p>
                            </div>
                        </div>
                    </div>

                    <div class="document-detail-content active" id="preview-tab">
                        <div id="documentViewerContainer" class="preview-placeholder">
                            <i class="bi bi-file-earmark-text"></i>
                            <h4>Pratinjau Dokumen</h4>
                            <p>Memuat pratinjau dokumen...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}" defer></script>
</body>
</html>
