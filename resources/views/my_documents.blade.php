<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Dokumen Saya</title>
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
           ✅ DOCS PAGE - DIUBAH AGAR SESUAI DASHBOARD
           ═══════════════════════════════════════════ */
        .docs-page {
            max-width: 1200px;
            /* ✅ Diubah dari 900px ke 1200px (sama dengan dashboard) */
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            /* ✅ Diubah untuk responsif seperti dashboard */
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

        /* ═══ ALERTS ═══ */
        .up-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--r-lg);
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.55;
            animation: upAlertIn .4s ease;
        }

        .up-alert i {
            font-size: 18px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        .up-alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .up-alert-success i {
            color: #16a34a;
        }

        .up-alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .up-alert-danger i {
            color: #dc2626;
        }

        @keyframes upAlertIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ═══ CARD ═══ */
        .up-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .04), 0 2px 6px rgba(0, 0, 0, .02);
            overflow: hidden;
            position: relative;
        }

        .up-card-accent {
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--indigo));
        }

        .up-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 28px 16px;
            position: relative;
        }

        .up-card-head-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(26, 86, 214, .25);
            flex-shrink: 0;
        }

        .up-card-head h4 {
            font-family: var(--font-d);
            font-size: 17px;
            color: var(--t1);
            margin-bottom: 2px;
        }

        .up-card-head p {
            font-size: 12.5px;
            color: var(--t3);
        }

        .up-card-head-with-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .up-badge-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            font-size: 12px;
            font-family: var(--font-b);
            white-space: nowrap;
        }

        .up-card-body {
            padding: 24px 28px 28px;
        }

        /* ═══ BATCH ACTION BAR ═══ */
        .batch-action-bar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: linear-gradient(135deg, rgba(26, 86, 214, .06), rgba(74, 125, 255, .06));
            border-bottom: 1px solid var(--border);
            animation: slideDown .25s ease;
        }

        .batch-action-bar.active {
            display: flex;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .batch-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--t1);
            font-family: var(--font-b);
        }

        .batch-info strong {
            color: var(--blue);
            font-weight: 600;
        }

        .batch-actions {
            display: flex;
            gap: 8px;
        }

        .btn-batch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--r-lg);
            font-size: 12.5px;
            font-family: var(--font-b);
            cursor: pointer;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            text-decoration: none;
            border: none;
            white-space: nowrap;
        }

        .btn-batch-download {
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            box-shadow: 0 2px 8px rgba(26, 86, 214, .2);
        }

        .btn-batch-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(26, 86, 214, .3);
        }

        .btn-batch-clear {
            background: transparent;
            color: var(--t2);
            border: 1.5px solid var(--border);
            padding: 8px 12px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--r-md);
            font-size: 14px;
            cursor: pointer;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            text-decoration: none;
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--t2);
        }

        .btn-action:hover {
            background: var(--page);
            border-color: var(--blue);
            color: var(--blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .btn-show:hover {
            border-color: var(--sky);
            color: var(--sky);
        }

        .btn-download:hover {
            border-color: var(--green);
            color: var(--green);
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

        .up-data-table tbody tr.selected {
            background: rgba(26, 86, 214, .06);
        }

        .doc-title {
            font-weight: 400 !important;
            color: var(--t1);
            margin-bottom: 3px;
        }

        .doc-subtitle {
            font-size: 11.5px;
            color: var(--t3);
            line-height: 1.4;
        }

        .time-info div {
            font-size: 13px;
            color: var(--t1);
        }

        .time-info small {
            font-size: 11.5px;
            color: var(--t3);
        }

        /* ═══ CUSTOM CHECKBOX ═══ */
        .custom-checkbox {
            position: relative;
            width: 20px;
            height: 20px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
            margin: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 6px;
            background: #fff;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
        }

        .custom-checkbox input:checked~.checkmark {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            border-color: transparent;
        }

        .checkmark:after {
            content: '';
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input:checked~.checkmark:after {
            display: block;
        }

        .custom-checkbox input:hover~.checkmark {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .1);
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
            margin: 0 auto 24px;
            line-height: 1.65;
        }

        .btn-empty-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: var(--r-lg);
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            font-size: 13.5px;
            font-family: var(--font-b);
            border: none;
            cursor: pointer;
            transition: all .2s cubic-bezier(.4, 0, .2, 1);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(26, 86, 214, .25);
        }

        .btn-empty-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26, 86, 214, .35);
        }

        /* Footer */
        .up-footer {
            text-align: center;
            margin-top: 32px;
            padding: 16px 20px;
            background: #fff;
            border-radius: var(--r-md);
            border: 1px solid var(--border);
            font-size: 12.5px;
            color: var(--t3);
            position: relative;
            z-index: 1;
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

            .up-card-body {
                padding: 20px;
            }

            .up-card-head {
                padding: 18px 20px 14px;
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

            .up-card-head-with-badge {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .batch-action-bar {
                flex-direction: column;
                gap: 12px;
                padding: 14px;
            }

            .batch-actions {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn-batch {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .docs-page {
                padding: clamp(12px, 3vw, 20px);
                /* ✅ Responsif untuk mobile */
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

            .up-alert {
                animation: none !important;
            }

            .batch-action-bar {
                animation: none !important;
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

    <div class="docs-page">

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
                <a href="{{ route('documents.my') }}" class="tab-nav-item active" role="tab" aria-selected="true">
                    <i class="bi bi-folder2-open"></i><span class="tab-text">Dokumen Saya</span>
                </a>
                <a href="{{ route('documents.history', ['date' => 'all']) }}" class="tab-nav-item" role="tab">
                    <i class="bi bi-clock-history"></i><span class="tab-text">Riwayat Upload</span>
                </a>
                <a href="{{ route('documents.turnitin', ['score' => 'all']) }}" class="tab-nav-item" role="tab">
                    <i class="bi bi-patch-check"></i><span class="tab-text">Skor Turnitin</span>
                </a>
                <div class="tab-nav-indicator"></div>
            </div>
        </nav>

        <!-- Alerts -->
        <!-- Main Card -->
        <div class="up-card">
            <div class="up-card-accent"></div>
            <div class="up-card-head">
                <div class="up-card-head-icon">
                    <i class="bi bi-folder-fill"></i>
                </div>
                <div class="up-card-head-with-badge">
                    <div>
                        <h4>Dokumen Saya</h4>
                        <p>Dokumen yang telah Anda unggah</p>
                    </div>
                    <span class="up-badge-count">
                        <i class="bi bi-files"></i> {{ count($my_documents) }} Dokumen
                    </span>
                </div>
            </div>

            <div class="up-card-body" style="padding:0;">
                @if (count($my_documents) === 0)
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h4>Belum Ada Dokumen</h4>
                        <p>Anda belum mengunggah dokumen apa pun. Mulai unggah dokumen pertama Anda sekarang.</p>
                        <a href="{{ route('upload.index') }}" class="btn-empty-action">
                            <i class="bi bi-cloud-upload"></i> Unggah Dokumen
                        </a>
                    </div>
                @else
                    <!-- Table -->
                    <div style="overflow-x:auto;">
                        <table class="up-data-table" id="documentsTable">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Turnitin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($my_documents as $doc)
                                    <tr data-id="{{ $doc->dokumen_id }}" data-url="{{ $doc->download_url }}">
                                        <td>
                                            <div>
                                                <div class="doc-title">{{ $doc->judul }}</div>
                                                <div class="doc-subtitle">
                                                    {{ \Illuminate\Support\Str::limit($doc->abstrak ?? '-', 80) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="time-info">
                                                <div>{{ \Carbon\Carbon::parse($doc->tgl_unggah)->format('d M Y') }}
                                                </div>
                                                <small>{{ \Carbon\Carbon::parse($doc->tgl_unggah)->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-status {{ $doc->status_badge ?? 'badge-default' }}">
                                                {{ $doc->status_name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $score = (int) ($doc->turnitin ?? 0);
                                                $scoreClass =
                                                    $score <= 20
                                                        ? 'score-low'
                                                        : ($score <= 30
                                                            ? 'score-medium'
                                                            : 'score-high');
                                            @endphp
                                            <span class="score-badge {{ $scoreClass }}">{{ $score }}%</span>
                                        </td>
                                        <td>
                                            <div style="display:flex;gap:8px;">
                                                <a href="{{ $doc->download_url }}" class="btn-action btn-show"
                                                    title="Lihat Dokumen" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('documents.download', $doc->dokumen_id) }}"
                                                    class="btn-action btn-download" title="Download" download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="up-footer">&copy; {{ date('Y') }} SIPORA — Politeknik Negeri Jember</div>

    </div>

    @include('components.footer_upload')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.chatbot_widget')


</body>

</html>
