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
    <style>
        :root { --ux-ease: 260ms cubic-bezier(0.2, 0.7, 0.2, 1); }
        .fx-reveal { opacity: 0; transform: translateY(18px) scale(0.99); transition: opacity 520ms ease, transform 520ms ease; }
        .fx-reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }
        .stat-card, .document-card, .upload-form-card, .search-form-card, .filter-section, .popular-keywords-section, .results-header, .chat-shell, .chatbot-shell, .top-read-card {
            transition: transform var(--ux-ease), box-shadow var(--ux-ease), border-color var(--ux-ease);
            transform-style: preserve-3d;
            will-change: transform;
        }
        .stat-card:hover, .document-card:hover, .upload-form-card:hover, .search-form-card:hover, .filter-section:hover, .popular-keywords-section:hover, .results-header:hover, .chat-shell:hover, .chatbot-shell:hover, .top-read-card:hover {
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.14) !important;
            border-color: rgba(37, 99, 235, 0.22);
        }
        .view-btn, .search-button, .btn, .btn-action, .prompt-btn, .empty-state-action, .keyword-tag {
            position: relative;
            overflow: hidden;
            transition: transform var(--ux-ease), box-shadow var(--ux-ease), filter var(--ux-ease);
        }
        .view-btn:hover, .search-button:hover, .btn:hover, .btn-action:hover, .prompt-btn:hover, .empty-state-action:hover, .keyword-tag:hover {
            transform: translateY(-1px);
            filter: saturate(1.06);
        }
        .ux-ripple {
            position: absolute;
            border-radius: 999px;
            transform: translate(-50%, -50%);
            pointer-events: none;
            background: rgba(255, 255, 255, 0.42);
            animation: ux-ripple 620ms ease-out forwards;
        }
        @keyframes ux-ripple {
            from { width: 0; height: 0; opacity: 0.9; }
            to { width: 220px; height: 220px; opacity: 0; }
        }
        .bg-animation .bg-circle { transition: transform 600ms ease; }
        @media (max-width: 768px) {
            .fx-reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
    <style>
        body {
            background: linear-gradient(180deg, #f8fbff 0%, #edf4fc 100%);
        }

        .search-container {
            max-width: 1180px;
            margin-inline: auto;
            padding-inline: 16px;
        }

        .search-form-card,
        .popular-keywords-section,
        .results-header {
            border: 1px solid #dbe7f5;
            border-radius: 14px;
            background: #ffffff;
        }

        .search-input {
            min-height: 48px;
            border-radius: 12px;
            border-color: #cddcf0;
            font-size: 15px;
        }

        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.16);
        }

        .search-button {
            min-width: 44px;
            min-height: 44px;
            border-radius: 10px;
        }

        .keyword-tag {
            border-radius: 999px;
            border: 1px solid #dbe7f5;
            background: #f8fbff;
            padding: 8px 12px;
            color: #1e293b;
            font-weight: 600;
        }

        .document-card {
            border-radius: 14px;
            border: 1px solid #dbe7f5;
            background: #ffffff;
        }

        .document-description {
            color: #334155;
            line-height: 1.55;
        }

        .btn-action,
        .view-btn,
        .search-button,
        .empty-state-action,
        .keyword-tag {
            min-height: 40px;
        }

        .btn-action:focus-visible,
        .view-btn:focus-visible,
        .search-button:focus-visible,
        .empty-state-action:focus-visible,
        .keyword-tag:focus-visible {
            outline: 3px solid rgba(59, 130, 246, 0.35);
            outline-offset: 2px;
        }

        @media (max-width: 768px) {
            .search-container {
                padding-inline: 12px;
            }

            .action-buttons {
                display: grid;
                gap: 8px;
            }

            .action-buttons .empty-state-action {
                width: 100%;
            }
        }
    </style>
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
    <script>
        (function () {
            'use strict';
            const revealSelectors = ['.stat-card','.document-card','.upload-form-card','.search-form-card','.filter-section','.popular-keywords-section','.results-header','.chat-shell','.chatbot-shell','.top-read-card','.recommendation-card'];
            function initReveal() {
                const nodes = document.querySelectorAll(revealSelectors.join(','));
                if (!nodes.length) return;
                nodes.forEach((el, index) => { el.classList.add('fx-reveal'); el.style.transitionDelay = `${Math.min(index * 35, 240)}ms`; });
                if (!('IntersectionObserver' in window)) { nodes.forEach((el) => el.classList.add('is-visible')); return; }
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
                }, { threshold: 0.12 });
                nodes.forEach((el) => observer.observe(el));
            }
            function initTilt() {
                const cards = document.querySelectorAll('.stat-card, .document-card, .top-read-card, .chat-shell, .chatbot-shell, .upload-form-card, .search-form-card, .recommendation-card');
                cards.forEach((card) => {
                    card.addEventListener('mousemove', (event) => {
                        if (window.matchMedia('(max-width: 991px)').matches) return;
                        const rect = card.getBoundingClientRect();
                        const x = event.clientX - rect.left;
                        const y = event.clientY - rect.top;
                        const rotateX = ((y / rect.height) - 0.5) * -3;
                        const rotateY = ((x / rect.width) - 0.5) * 3;
                        card.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-2px)`;
                    });
                    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
                });
            }
            function initRipple() {
                const buttons = document.querySelectorAll('.btn, .view-btn, .search-button, .btn-action, .prompt-btn, .empty-state-action, .keyword-tag');
                buttons.forEach((button) => {
                    button.addEventListener('click', (event) => {
                        const rect = button.getBoundingClientRect();
                        const ripple = document.createElement('span');
                        ripple.className = 'ux-ripple';
                        ripple.style.left = `${event.clientX - rect.left}px`;
                        ripple.style.top = `${event.clientY - rect.top}px`;
                        button.appendChild(ripple);
                        setTimeout(() => ripple.remove(), 650);
                    });
                });
            }
            function initParallaxBackground() {
                const circles = document.querySelectorAll('.bg-animation .bg-circle');
                if (!circles.length) return;
                window.addEventListener('mousemove', (event) => {
                    const xRatio = (event.clientX / window.innerWidth) - 0.5;
                    const yRatio = (event.clientY / window.innerHeight) - 0.5;
                    circles.forEach((circle, index) => {
                        const depth = (index + 1) * 10;
                        circle.style.transform = `translate(${xRatio * depth}px, ${yRatio * depth}px)`;
                    });
                });
            }
            document.addEventListener('DOMContentLoaded', () => {
                initReveal();
                initTilt();
                initRipple();
                initParallaxBackground();
            });
        })();
    </script>
</body>
</html>
