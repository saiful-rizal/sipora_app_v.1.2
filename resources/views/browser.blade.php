<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Browser Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body data-browser-detail-endpoint="{{ route('browser.get-detail') }}" data-browser-prodi-endpoint="{{ route('browser.get-prodi') }}" data-filter-prodi="{{ $filter_prodi }}">
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    @include('components.navbar')
    @include('components.header_browser')

    <div class="browser-container">
        <div class="filter-section">
            <form method="GET" action="{{ route('browser.index') }}" id="filterForm" class="filter-inline">
                <div class="filter-item">
                    <label for="filter_jurusan">Jurusan</label>
                    <select id="filter_jurusan" name="filter_jurusan" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        @foreach($jurusan_data as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" @selected((string)$filter_jurusan === (string)$jurusan->id_jurusan)>{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filter_prodi">Program Studi</label>
                    <select id="filter_prodi" name="filter_prodi" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        @foreach($prodi_data as $prodi)
                            <option value="{{ $prodi->id_prodi }}" @selected((string)$filter_prodi === (string)$prodi->id_prodi)>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                @if($filter_jurusan || $filter_prodi)
                    <a href="{{ route('browser.index') }}" class="filter-reset"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                @endif
            </form>
        </div>

        <div class="results-header">
            <div class="results-info">
                <h4><i class="bi bi-folder2-open"></i> Dokumen Terbaru</h4>
                <p class="results-count">Jelajahi semua dokumen akademik tersedia @if($filter_jurusan || $filter_prodi) <br><small><strong>Filter aktif:</strong> @if($filter_jurusan) Jurusan @endif @if($filter_prodi) Program Studi @endif</small>@endif</p>
            </div>
            <div class="view-mode-toggle">
                <button class="view-btn active" data-view="grid" title="Grid View" onclick="setBrowserViewMode('grid')"><i class="bi bi-grid-3x3-gap"></i></button>
                <button class="view-btn" data-view="list" title="List View" onclick="setBrowserViewMode('list')"><i class="bi bi-list-ul"></i></button>
            </div>
        </div>

        @if(count($documents) === 0)
            <div class="empty-state">
                <div class="empty-state-card">
                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                    <h3 class="empty-state-title">Tidak ada dokumen ditemukan</h3>
                </div>
            </div>
        @else
            <div class="document-grid" id="documentGrid">
                @foreach($documents as $doc)
                    <div class="document-card" data-id="{{ $doc['dokumen_id'] }}">
                        <div class="document-thumbnail">
                            <i class="bi bi-file-earmark-text document-thumbnail-icon"></i>
                            <div class="document-thumbnail-text">{{ strtoupper($doc['file_type'] ?: 'FILE') }}</div>
                        </div>
                        <div class="document-content">
                            <div class="document-header">
                                <h6 class="document-title">{{ $doc['judul'] }}</h6>
                                <div class="document-badges">
                                    <span class="badge {{ $doc['status_badge'] }}">{{ $doc['status_name'] }}</span>
                                </div>
                            </div>
                            <div class="document-description">{{ \Illuminate\Support\Str::limit($doc['abstrak'] ?? '-', 150) }}</div>
                            <div class="document-footer">
                                <div class="document-info">
                                    <div class="document-uploader"><i class="bi bi-person-circle"></i><span>{{ $doc['uploader_name'] ?? 'Admin' }}</span></div>
                                    <div class="document-date">{{ \Carbon\Carbon::parse($doc['tgl_unggah'])->format('d M y') }}</div>
                                </div>
                                <div class="document-actions">
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="showDetail({{ $doc['dokumen_id'] }})"><i class="bi bi-info-circle"></i></button>
                                    <a href="{{ $doc['download_url'] }}" download class="btn-action btn-download" title="Unduh"><i class="bi bi-download"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('components.footer_browser')

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailTitle">Detail Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailBody">Memuat...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/browser-page.js') }}"></script>
</body>
</html>
