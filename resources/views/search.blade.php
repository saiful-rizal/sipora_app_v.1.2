<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Pencarian Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body data-search-detail-endpoint="{{ route('search.get-detail') }}">
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    @include('components.navbar')
    @include('components.header_search')

    <div class="search-container">
        <div class="search-form-section">
            <div class="search-form-card">
                <form id="searchForm" method="GET" action="{{ route('search.index') }}" class="search-form">
                    <div class="search-input-group">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" id="searchInput" class="search-input" placeholder="Cari dokumen, penulis, kata kunci..." value="{{ $search_query }}" autocomplete="off">
                        <button class="search-button" type="submit"><i class="bi bi-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>

        @if(empty($search_query))
            <div class="popular-keywords-section">
                <div class="keywords-header">
                    <i class="bi bi-fire keywords-icon"></i>
                    <h4>Kata Kunci Populer</h4>
                </div>
                <div class="keywords-cloud">
                    @foreach($popular_keywords as $keyword)
                        <a href="{{ route('search.index', ['q' => $keyword]) }}" class="keyword-tag">
                            <i class="bi bi-tag-fill"></i> <span>{{ $keyword }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($search_query) && count($results) === 0)
            <div class="empty-state">
                <div class="empty-state-card">
                    <div class="empty-state-icon"><i class="bi bi-emoji-frown"></i></div>
                    <h3 class="empty-state-title">Tidak Ada Hasil</h3>
                    <p class="empty-state-description">Tidak ada dokumen untuk kata kunci "{{ $search_query }}".</p>
                    <div class="action-buttons">
                        <a href="{{ route('search.index') }}" class="empty-state-action"><i class="bi bi-arrow-clockwise"></i> Cari Lagi</a>
                        <a href="{{ route('browser.index') }}" class="empty-state-action secondary"><i class="bi bi-folder2-open"></i> Jelajahi Semua</a>
                    </div>
                </div>
            </div>
        @endif

        @if(count($results) > 0)
            <div class="results-header">
                <div class="results-info">
                    <h4><i class="bi bi-search"></i> Hasil Pencarian</h4>
                    <p class="results-count">Ditemukan <strong>{{ count($results) }}</strong> dokumen untuk "<strong>{{ $search_query }}</strong>"</p>
                </div>
                <div class="view-mode-toggle">
                    <button class="view-btn active" data-view="grid" title="Grid View" onclick="setSearchViewMode('grid')"><i class="bi bi-grid-3x3-gap"></i></button>
                    <button class="view-btn" data-view="list" title="List View" onclick="setSearchViewMode('list')"><i class="bi bi-list-ul"></i></button>
                </div>
            </div>
            <div class="document-grid" id="documentGrid">
                @foreach($results as $doc)
                    <div class="document-card" data-id="{{ $doc['dokumen_id'] }}" onclick="openDocumentModal({{ $doc['dokumen_id'] }})">
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
                                    <div class="document-uploader"><i class="bi bi-person-circle"></i><span>{{ $doc['uploader_name'] ?? '-' }}</span></div>
                                    <div class="document-date">{{ \Carbon\Carbon::parse($doc['tgl_unggah'])->format('d M y') }}</div>
                                </div>
                                <div class="document-actions">
                                    <button class="btn-action btn-view" type="button" onclick="event.stopPropagation(); openDocumentModal({{ $doc['dokumen_id'] }})"><i class="bi bi-info-circle"></i></button>
                                    <a href="{{ $doc['download_url'] }}" download class="btn-action btn-download" onclick="event.stopPropagation()"><i class="bi bi-download"></i></a>
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
    <script src="{{ asset('assets/js/search-page.js') }}"></script>
</body>
</html>
