<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Detail Dokumen</title>
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

        .docs-page {
            max-width: 800px;
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            position: relative;
            z-index: 1;
        }

        .detail-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .04), 0 2px 6px rgba(0, 0, 0, .02);
            padding: 32px;
            margin-bottom: 24px;
        }

        .detail-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .detail-title {
            font-family: var(--font-d);
            font-size: 24px;
            color: var(--t1);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 14px;
            color: var(--t2);
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .section-title {
            font-family: var(--font-d);
            font-size: 18px;
            color: var(--t1);
            margin-bottom: 12px;
        }

        .section-content {
            color: var(--t2);
            line-height: 1.6;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .badge-success {
            background: rgba(34, 197, 94, .1);
            color: #16a34a;
        }

        .badge-danger {
            background: rgba(239, 68, 68, .1);
            color: #dc2626;
        }

        .badge-warning {
            background: rgba(245, 158, 11, .1);
            color: #d97706;
        }

        .badge-info {
            background: rgba(59, 130, 246, .1);
            color: #2563eb;
        }

        .badge-secondary {
            background: rgba(107, 114, 128, .1);
            color: #6b7280;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(130deg, var(--green), var(--teal));
            color: #fff;
            padding: 12px 24px;
            border-radius: var(--r-lg);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(29, 78, 216, .2);
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(29, 78, 216, .3);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--t2);
            padding: 12px 24px;
            border: 1.5px solid var(--border);
            border-radius: var(--r-lg);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
        }

        .btn-back:hover {
            background: var(--page);
            color: var(--t1);
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="docs-page">
        <!-- Header -->
        <div style="margin-bottom:24px;">
            <a href="{{ route('documents.my') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya
            </a>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-header">
                <h1 class="detail-title">{{ $document->judul }}</h1>
                <div class="detail-meta">
                    <span><i class="bi bi-person"></i> {{ $document->uploader_name }}</span>
                    <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($document->tgl_unggah)->format('d M Y H:i') }}</span>
                    <span class="badge-status {{ $status_badge }}">{{ $document->status_name }}</span>
                </div>
            </div>

            @if($document->abstrak)
            <div class="detail-section">
                <h2 class="section-title">Abstrak</h2>
                <div class="section-content">{{ $document->abstrak }}</div>
            </div>
            @endif

            <div class="detail-section">
                <h2 class="section-title">Informasi Dokumen</h2>
                <div class="section-content">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        @if($document->nama_jurusan)
                        <div><strong>Jurusan:</strong> {{ $document->nama_jurusan }}</div>
                        @endif
                        @if($document->nama_prodi)
                        <div><strong>Program Studi:</strong> {{ $document->nama_prodi }}</div>
                        @endif
                        @if($document->nama_tema)
                        <div><strong>Tema:</strong> {{ $document->nama_tema }}</div>
                        @endif
                        @if($document->tahun)
                        <div><strong>Tahun:</strong> {{ $document->tahun }}</div>
                        @endif
                        @if($document->turnitin !== null)
                        <div><strong>Turnitin:</strong> {{ $document->turnitin }}%</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ $download_url }}" class="btn-download" download>
                    <i class="bi bi-download"></i> Download Dokumen
                </a>
            </div>
        </div>
    </div>
</body>

</html>
