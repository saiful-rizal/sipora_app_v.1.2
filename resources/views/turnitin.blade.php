<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Laporan Turnitin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --navy: #0b1b4d;
            --blue: #1a56d6;
            --sky: #38bdf8;
            --indigo: #4a7dff;
            --teal: #2563eb;
            --rose: #3b82f6;
            --amber: #1e40af;
            --green: #1d4ed8;
            --page: #f0f3fb;
            --card: #ffffff;
            --border: #e4e9f5;
            --t1: #0f172a;
            --t2: #64748b;
            --t3: #94a3b8;
            --r-md: 12px;
            --r-lg: 18px;
            --r-xl: 24px;
            --font-d: 'Sora', sans-serif;
            --font-b: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            overflow-x: hidden;
            min-height: 100vh;
        }

        body {
            font-family: var(--font-b);
            background: var(--page);
            color: var(--t1);
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(ellipse 60% 40% at 100% 0%, rgba(26, 86, 214, .10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 35% at 0% 100%, rgba(30, 64, 175, .08) 0%, transparent 60%),
                var(--page);
        }

        /* Background Orbs */
        .prem-bg-orbs {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .prem-bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0;
            animation: premBgOrbIn 2s ease forwards;
        }

        .prem-bg-orb:nth-child(1) {
            width: 420px;
            height: 280px;
            top: -8%;
            right: -5%;
            background: rgba(26, 86, 214, .09);
            animation: premBgOrbIn 2s .2s ease forwards, premBgOrb1 25s 2s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(2) {
            width: 350px;
            height: 220px;
            bottom: -5%;
            left: -3%;
            background: rgba(30, 64, 175, .07);
            animation: premBgOrbIn 2s .5s ease forwards, premBgOrb2 28s 2.5s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 40%;
            left: 25%;
            background: rgba(59, 130, 246, .06);
            animation: premBgOrbIn 2s .8s ease forwards, premBgOrb3 22s 3s ease-in-out infinite;
        }

        @keyframes premBgOrbIn {
            to {
                opacity: 1;
            }
        }

        @keyframes premBgOrb1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(50px, 20px) scale(1.08);
            }

            66% {
                transform: translate(-25px, -12px) scale(.94);
            }
        }

        @keyframes premBgOrb2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(-40px, -25px) scale(1.06);
            }

            66% {
                transform: translate(30px, 15px) scale(.93);
            }
        }

        @keyframes premBgOrb3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(25px, -20px) scale(1.1);
            }
        }

        /* ═══════════════════════════════════════════
           ✅ TURNITIN PAGE - DIUBAH AGAR SESUAI DASHBOARD
           ═══════════════════════════════════════════ */
        .turnitin-page {
            max-width: 1200px;  /* ✅ Diubah dari 900px ke 1200px (sama dengan dashboard) */
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);  /* ✅ Diubah untuk responsif seperti dashboard */
            position: relative;
            z-index: 1;
        }

        /* ═══ HERO SECTION ═══ */
        .up-hero {
            border-radius: var(--r-xl);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            padding: 36px 40px;
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%);
            box-shadow: 0 12px 40px rgba(26, 86, 214, .30), 0 2px 8px rgba(15, 23, 42, .12);
        }

        .up-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: .5;
        }

        .up-hero::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            border: 50px solid rgba(255, 255, 255, .05);
            top: -120px;
            right: -80px;
        }

        .up-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 10.5px;
            color: rgba(255, 255, 255, .85);
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 14px;
            position: relative;
            z-index: 2;
        }

        .up-hero-eyebrow i {
            font-size: 10px;
        }

        .up-hero h2 {
            font-family: var(--font-d);
            font-size: 24px;
            color: #fff;
            margin-bottom: 6px;
            position: relative;
            z-index: 2;
            line-height: 1.3;
        }

        .up-hero p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, .68);
            max-width: 480px;
            line-height: 1.55;
            position: relative;
            z-index: 2;
        }

        .up-hero-chips {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .up-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 11.5px;
            color: rgba(255, 255, 255, .85);
            backdrop-filter: blur(4px);
        }

        .up-chip i {
            font-size: 11px;
        }

        /* ═══ TAB NAVIGATION ═══ */
        .tab-navigation {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .04), 0 2px 6px rgba(0, 0, 0, .02);
            margin-bottom: 24px;
            overflow: hidden;
            position: relative;
            z-index: 10;
        }

        .tab-nav-container {
            display: flex;
            align-items: stretch;
            gap: 4px;
            padding: 8px;
            position: relative;
        }

        .tab-nav-item {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 18px;
            border-radius: var(--r-lg);
            font-family: var(--font-b);
            font-size: 13px;
            color: var(--t2);
            cursor: pointer;
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            background: transparent;
            border: none;
            white-space: nowrap;
            user-select: none;
            text-decoration: none;
        }

        .tab-nav-item:hover:not(.active) {
            background: rgba(26, 86, 214, .06);
            color: var(--blue);
        }

        .tab-nav-item.active {
            background: linear-gradient(135deg, rgba(26, 86, 214, .12), rgba(74, 125, 255, .12));
            color: var(--blue);
            box-shadow: 0 2px 10px rgba(26, 86, 214, .15);
            font-weight: 500 !important;
        }

        .tab-nav-item i {
            font-size: 18px;
            transition: transform .25s;
        }

        .tab-nav-item:hover i {
            transform: scale(1.1);
        }

        .tab-nav-item.active i {
            color: var(--blue);
        }

        .tab-nav-indicator {
            position: absolute;
            bottom: 8px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--blue), var(--indigo));
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            z-index: 5;
        }

        /* ═══ FILTER BAR ═══ */
        .up-filter-bar {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .04), 0 2px 6px rgba(0, 0, 0, .02);
            padding: 18px 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .up-filter-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-d);
            font-size: 13.5px;
            color: var(--t1);
            white-space: nowrap;
        }

        .up-filter-header i {
            font-size: 17px;
            color: var(--blue);
        }

        .up-filter-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            flex: 1;
        }

        .up-filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: var(--r-lg);
            font-size: 12.5px;
            font-family: var(--font-b);
            color: var(--t2);
            background: var(--page);
            border: 1.5px solid var(--border);
            text-decoration: none;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            cursor: pointer;
            user-select: none;
        }

        .up-filter-chip:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, .04);
            transform: translateY(-1px);
        }

        .up-filter-chip.active {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(26, 86, 214, .2);
            font-weight: 500 !important;
        }

        /* ═══ TABLE CONTAINER ═══ */
        .up-table-container {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .04), 0 2px 6px rgba(0, 0, 0, .02);
            overflow: hidden;
        }

        .up-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(to bottom, #fafbff, #fff);
        }

        .up-table-header h5 {
            font-family: var(--font-d);
            font-size: 15px;
            color: var(--t1);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .up-table-header h5 i {
            color: var(--blue);
            font-size: 17px;
        }

        .up-result-count {
            font-size: 12px;
            color: var(--t3);
            background: var(--page);
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid var(--border);
            font-family: var(--font-b);
        }

        /* ═══ TABLE ═══ */
        .up-data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .up-data-table thead th {
            padding: 14px 18px;
            text-align: left;
            font-size: 11.5px;
            font-family: var(--font-d);
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: .5px;
            background: var(--page);
            border-bottom: 1px solid var(--border);
            font-weight: 600 !important;
        }

        .up-data-table thead th:last-child {
            text-align: right;
        }

        .up-data-table tbody td {
            padding: 15px 18px;
            font-size: 13px;
            color: var(--t1);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .up-data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .up-data-table tbody tr {
            transition: background .15s;
        }

        .up-data-table tbody tr:hover {
            background: rgba(26, 86, 214, .03);
        }

        .doc-title {
            font-weight: 600 !important;
            color: var(--t1);
            margin-bottom: 3px;
        }

        .doc-subtitle {
            font-size: 11.5px;
            color: var(--t3);
            line-height: 1.4;
        }

        /* ═══ BADGES ═══ */
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 11.5px;
            font-family: var(--font-b);
            font-weight: 500 !important;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-default {
            background: var(--page);
            color: var(--t2);
            border: 1px solid var(--border);
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 52px;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12.5px;
            font-family: var(--font-d);
            font-weight: 600 !important;
        }

        .score-low {
            background: #dcfce7;
            color: #166534;
        }

        .score-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .score-high {
            background: #fee2e2;
            color: #991b1b;
        }

        .score-none {
            background: var(--page);
            color: var(--t3);
            border: 1px solid var(--border);
        }

        /* ═══ ACTION BUTTONS ═══ */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 15px;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .action-btn.btn-view:hover {
            border-color: var(--indigo);
            color: var(--indigo);
            background: rgba(74, 125, 255, .05);
        }

        .action-btn.btn-download:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, .05);
        }

        /* ═══ EMPTY STATE ═══ */
        .empty-state {
            text-align: center;
            padding: 70px 24px;
        }

        .empty-state-icon {
            width: 88px;
            height: 88px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(26, 86, 214, .08), rgba(74, 125, 255, .08));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: var(--blue);
            margin: 0 auto 24px;
        }

        .empty-state h4 {
            font-family: var(--font-d);
            font-size: 18px;
            color: var(--t1);
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 13.5px;
            color: var(--t3);
            max-width: 340px;
            margin: 0 auto;
            line-height: 1.65;
        }

        /* Footer */
        .up-footer {
            text-align: center;
            margin-top: 32px;
            padding: 16px 20px;
            background: #fff;
            border-radius: var(--r-md);
            border: none;
            font-size: 12.5px;
            color: var(--t3);
            position: relative;
            z-index: 1;
        }

        /* Override global footer border for this page */
        footer.footer {
            border-top: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .up-hero {
                padding: 28px 24px;
            }

            .up-hero h2 {
                font-size: 20px;
            }

            .up-hero-chips {
                display: none;
            }

            .tab-nav-container {
                gap: 2px;
                padding: 6px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .tab-nav-container::-webkit-scrollbar {
                display: none;
            }

            .tab-nav-item {
                padding: 10px 14px;
                font-size: 12px;
                gap: 6px;
                flex: 0 0 auto;
                min-width: fit-content;
            }

            .tab-nav-item span.tab-text {
                display: none;
            }

            .tab-nav-item i {
                font-size: 19px;
            }

            .tab-nav-indicator {
                display: none;
            }

            .up-filter-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                padding: 16px 18px;
            }

            .up-filter-header {
                justify-content: center;
            }

            .up-filter-options {
                justify-content: center;
            }

            .up-filter-chip {
                padding: 7px 14px;
                font-size: 12px;
                flex: 1;
                justify-content: center;
            }

            .up-table-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
                padding: 16px 18px;
            }

            .up-data-table {
                font-size: 12px;
            }

            .up-data-table thead th,
            .up-data-table tbody td {
                padding: 12px 14px;
            }

            .doc-subtitle {
                display: none;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .turnitin-page {
                padding: clamp(12px, 3vw, 20px);  /* ✅ Responsif untuk mobile */
            }

            .up-hero {
                padding: 24px 20px;
            }

            .up-hero h2 {
                font-size: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .prem-bg-orb {
                animation: none !important;
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <!-- Background Orbs -->
    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>

    @include('components.navbar')

    <div class="turnitin-page">

        <!-- Hero Section -->
        <div class="up-hero">
            <div class="up-hero-eyebrow"><i class="bi bi-cloud-upload"></i> Repository Dokumen</div>
            <h2>Sistem Informasi Politeknik Negeri Jember</h2>
            <p>Kelola dokumen akademik Anda melalui satu platform terintegrasi.</p>
            <div class="up-hero-chips">
                <div class="up-chip"><i class="bi bi-file-earmark-pdf"></i> PDF, DOC, PPT, XLS</div>
                <div class="up-chip"><i class="bi bi-hdd"></i> Maks 10MB</div>
                <div class="up-chip"><i class="bi bi-shield-check"></i> Screening Otomatis</div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <nav class="tab-navigation" role="tablist" aria-label="Menu Navigasi Dokumen">
            <div class="tab-nav-container">
                <a href="{{ route('upload.index') }}" class="tab-nav-item" role="tab">
                    <i class="bi bi-cloud-arrow-up"></i><span class="tab-text">Unggah</span>
                </a>
                <a href="{{ route('documents.my') }}" class="tab-nav-item" role="tab">
                    <i class="bi bi-folder2-open"></i><span class="tab-text">Dokumen Saya</span>
                </a>
                <a href="{{ route('documents.history', ['date' => 'all']) }}" class="tab-nav-item" role="tab">
                    <i class="bi bi-clock-history"></i><span class="tab-text">Riwayat Upload</span>
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'all']) }}" class="tab-nav-item active"
                    role="tab" aria-selected="true">
                    <i class="bi bi-patch-check"></i><span class="tab-text">Skor Turnitin</span>
                </a>
                <div class="tab-nav-indicator"></div>
            </div>
        </nav>

        <!-- Filter Bar -->
        <div class="up-filter-bar">
            <div class="up-filter-header">
                <i class="bi bi-funnel"></i>
                <span>Filter Skor</span>
            </div>
            <div class="up-filter-options">
                <a href="{{ route('documents.turnitin', ['score' => 'all']) }}"
                    class="up-filter-chip {{ $score_filter === 'all' ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'none']) }}"
                    class="up-filter-chip {{ $score_filter === 'none' ? 'active' : '' }}">
                    Tanpa Skor
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'low']) }}"
                    class="up-filter-chip {{ $score_filter === 'low' ? 'active' : '' }}">
                    0-20%
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'medium']) }}"
                    class="up-filter-chip {{ $score_filter === 'medium' ? 'active' : '' }}">
                    21-40%
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'high']) }}"
                    class="up-filter-chip {{ $score_filter === 'high' ? 'active' : '' }}">
                    &gt;40%
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="up-table-container">
            <div class="up-table-header">
                <h5><i class="bi bi-patch-check"></i> Daftar Turnitin</h5>
                <span class="up-result-count">
                    <i class="bi bi-files" style="margin-right:4px;"></i>{{ count($documents) }} dokumen
                </span>
            </div>

            @if (count($documents) === 0)
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4>Tidak Ada Data</h4>
                    <p>Belum ada dokumen sesuai filter yang dipilih.</p>
                </div>
            @else
                <!-- Table -->
                <div style="overflow-x:auto;">
                    <table class="up-data-table">
                        <thead>
                            <tr>
                                <th>Dokumen</th>
                                <th>Tema</th>
                                <th>Tahun</th>
                                <th>Skor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $doc)
                                @php
                                    $score = (int) ($doc->turnitin ?? 0);

                                    if ($score === 0) {
                                        $scoreClass = 'score-none';
                                    } elseif ($score <= 20) {
                                        $scoreClass = 'score-low';
                                    } elseif ($score <= 40) {
                                        $scoreClass = 'score-medium';
                                    } else {
                                        $scoreClass = 'score-high';
                                    }

                                    $statusBadge = 'badge-default';
                                    if (isset($doc->status_name)) {
                                        $statusLower = strtolower($doc->status_name);
                                        if (
                                            strpos($statusLower, 'lolos') !== false ||
                                            strpos($statusLower, 'approve') !== false
                                        ) {
                                            $statusBadge = 'badge-success';
                                        } elseif (
                                            strpos($statusLower, 'review') !== false ||
                                            strpos($statusLower, 'pending') !== false
                                        ) {
                                            $statusBadge = 'badge-warning';
                                        } elseif (
                                            strpos($statusLower, 'reject') !== false ||
                                            strpos($statusLower, 'tolak') !== false
                                        ) {
                                            $statusBadge = 'badge-danger';
                                        } else {
                                            $statusBadge = 'badge-info';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="doc-title">{{ $doc->judul }}</div>
                                        <div class="doc-subtitle">{{ $doc->uploader_name ?? '-' }}</div>
                                    </td>
                                    <td>{{ $doc->nama_tema ?? '-' }}</td>
                                    <td>{{ $doc->year_id ?? '-' }}</td>
                                    <td>
                                        <span class="score-badge {{ $scoreClass }}">{{ $score }}%</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge-status {{ $statusBadge }}">{{ $doc->status_name ?? 'Unknown' }}</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if (isset($doc->dokumen_id))
                                                <a href="{{ route('documents.detail', ['id' => $doc->dokumen_id]) }}"
                                                    class="action-btn btn-view" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                            @if (isset($doc->dokumen_id))
                                                <a href="{{ route('documents.download', ['id' => $doc->dokumen_id]) }}"
                                                    class="action-btn btn-download" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="up-footer">&copy; {{ date('Y') }} SIPORA — Politeknik Negeri Jember</div>

    </div>

    @include('components.footer_upload')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
