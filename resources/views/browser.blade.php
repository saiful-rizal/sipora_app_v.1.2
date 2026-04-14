<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Browser Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --navy: #0b1b4d;
            --blue: #1a56d6;
            --sky: #38bdf8;
            --indigo: #6366f1;
            --teal: #14b8a6;
            --rose: #f43f5e;
            --amber: #f59e0b;
            --green: #22c55e;
            --page: #f0f3fb;
            --card: #ffffff;
            --border: #e4e9f5;
            --t1: #0f172a;
            --t2: #64748b;
            --t3: #94a3b8;
            --r-md: 12px;
            --r-lg: 18px;
            --r-xl: 24px;
            --s-sm: 0 1px 4px rgba(15, 23, 42, .07), 0 1px 2px rgba(15, 23, 42, .04);
            --s-md: 0 4px 16px rgba(15, 23, 42, .09), 0 2px 4px rgba(15, 23, 42, .04);
            --s-lg: 0 16px 40px rgba(15, 23, 42, .12), 0 4px 8px rgba(15, 23, 42, .05);
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
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            background: radial-gradient(ellipse 60% 40% at 100% 0%, rgba(99, 102, 241, .10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 35% at 0% 100%, rgba(20, 184, 166, .08) 0%, transparent 60%), var(--page);
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
            background: rgba(99, 102, 241, .09);
            animation: premBgOrbIn 2s .2s ease forwards, premBgOrb1 25s 2s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(2) {
            width: 350px;
            height: 220px;
            bottom: -5%;
            left: -3%;
            background: rgba(20, 184, 166, .07);
            animation: premBgOrbIn 2s .5s ease forwards, premBgOrb2 28s 2.5s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 40%;
            left: 25%;
            background: rgba(26, 86, 214, .06);
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

        /* ════════════════════════════════════════════════════════════════════════
           ✅ CUSTOM SWEETALERT2 TOAST STYLING (COMPACT & SMALL)
           SAMA SEPERTI HALAMAN UPLOAD
           ════════════════════════════════════════════════════════════════════════ */

        /* Container utama toast */
        .swal2-popup.swal2-toast {
            width: auto !important;
            min-width: 280px !important;
            max-width: 360px !important;
            padding: 12px 16px !important;
            border-radius: 12px !important;
            font-family: var(--font-b) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
        }

        /* Icon container */
        .swal2-popup.swal2-toast .swal2-header {
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        .swal2-popup.swal2-toast .swal2-icon {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            margin: 0 !important;
            border-width: 2.5px !important;
        }

        /* Custom icon sizes */
        .swal2-popup.swal2-toast .swal2-icon.swal2-success {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            background-color: #fffbeb !important;
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-info {
            border-color: #3b82f6 !important;
            background-color: #eff6ff !important;
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #10b981 !important;
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
            background-color: #ef4444 !important;
        }

        /* Title styling */
        .swal2-popup.swal2-toast .swal2-title {
            margin: 0 0 0 12px !important;
            padding: 0 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: var(--t1) !important;
            line-height: 1.4 !important;
            flex: 1 !important;
        }

        /* Hide subtitle/text for more compact look */
        .swal2-popup.swal2-toast #swal2-content {
            display: none !important;
        }

        /* Close button */
        .swal2-popup.swal2-toast .swal2-close {
            width: 24px !important;
            height: 24px !important;
            font-size: 14px !important;
            color: var(--t3) !important;
            margin: 0 !important;
            padding: 4px !important;
            opacity: 0.6 !important;
            transition: opacity 0.2s !important;
        }

        .swal2-popup.swal2-toast .swal2-close:hover {
            opacity: 1 !important;
            color: var(--t2) !important;
        }

        /* Progress bar */
        .swal2-popup.swal2-toast .swal2-progresssteps {
            height: 3px !important;
            margin-top: 8px !important;
            background: rgba(0, 0, 0, 0.05) !important;
            border-radius: 2px !important;
            overflow: hidden !important;
        }

        .swal2-popup.swal2-toast .swal2-progresssteps .swal2-progresscircle {
            background: linear-gradient(90deg, var(--blue), var(--indigo)) !important;
            height: 100% !important;
        }

        /* Animation */
        .swal2-popup.swal2-toast.swal2-show {
            animation: slideInRight 0.3s cubic-bezier(0.21, 1.02, 0.73, 1) !important;
        }

        .swal2-popup.swal2-toast.swal2-hide {
            animation: slideOutRight 0.25s cubic-bezier(0.55, 0, 1, 0.45) !important;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        /* Responsive toast */
        @media (max-width: 480px) {
            .swal2-popup.swal2-toast {
                min-width: 260px !important;
                max-width: calc(100vw - 32px) !important;
                padding: 10px 14px !important;
            }

            .swal2-popup.swal2-toast .swal2-icon {
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
            }

            .swal2-popup.swal2-toast .swal2-title {
                font-size: 13px !important;
            }
        }

        /* ═══════════════════════════════════════════
           ✅ BROWSER PAGE
           ═══════════════════════════════════════════ */
        .br-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            position: relative;
            z-index: 1;
        }

        /* Hero */
        .br-hero {
            border-radius: var(--r-xl);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            padding: 36px 40px;
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%);
            box-shadow: 0 12px 40px rgba(26, 86, 214, .30), 0 2px 8px rgba(15, 23, 42, .12);
            z-index: 1;
        }

        .br-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: .5;
        }

        .br-hero::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            border: 50px solid rgba(255, 255, 255, .05);
            top: -120px;
            right: -80px;
        }

        .br-hero-eyebrow {
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

        .br-hero h2 {
            font-family: var(--font-d);
            font-size: 24px;
            color: #fff;
            margin-bottom: 6px;
            position: relative;
            z-index: 2;
            line-height: 1.3;
        }

        .br-hero p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, .68);
            max-width: 480px;
            line-height: 1.55;
            position: relative;
            z-index: 2;
        }

        .br-hero-chips {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .br-chip {
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

        /* Search Container */
        .br-search-container {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-md);
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        .br-search-wrapper {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .br-search-input {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            border: 2px solid var(--border);
            font-family: var(--font-b);
            font-size: 14px;
            color: var(--t1);
            outline: none;
            background: #fff;
            transition: all .25s cubic-bezier(.22, .68, 0, 1);
            position: relative;
            z-index: 5;
        }

        .br-search-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26, 86, 214, .10), 0 4px 16px rgba(26, 86, 214, .12);
        }

        .br-search-input::placeholder {
            color: var(--t3);
        }

        .br-search-btn {
            padding: 14px 28px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            font-family: var(--font-d);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 16px rgba(26, 86, 214, .30);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.3s ease;
            position: relative;
            z-index: 5;
            will-change: transform, box-shadow;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .br-search-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(26, 86, 214, .45), 0 4px 12px rgba(26, 86, 214, .25);
        }

        .br-search-btn:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 6px 20px rgba(26, 86, 214, .35);
            transition-duration: 0.1s;
        }

        .br-search-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .br-search-btn i {
            font-size: 16px !important;
        }

        /* ═══════════════════════════════════════════
           ✅ FILTER CARD - JURUSAN, PRODI, RESET
           ═══════════════════════════════════════════ */
        .br-filter-bar {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-md);
            padding: 24px 28px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        /* ✅ Perbaikan ukuran dropdown agar tidak terlalu panjang & tidak kepotong */
        .br-filter-row {
            display: grid !important;
            grid-template-columns: minmax(220px, 1fr) minmax(260px, 1.2fr) auto !important;
            gap: 16px !important;
            align-items: flex-end !important;
            width: 70%;
        }

        /* ✅ Biar teks dropdown tetap rapi (tidak keluar / kepotong aneh) */
        .br-select {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .br-filter-item {
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
            min-width: 0 !important;
            max-width: none !important;
            width: 100% !important;
            flex: none !important;
        }

        .br-filter-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--t2);
            font-weight: 600;
            white-space: nowrap;
        }

        .br-filter-label i {
            font-size: 15px !important;
            color: var(--blue);
        }

        /* ✅ DROPDOWN SELECT - Style umum */
        .br-select {
            padding: 9px 36px 9px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            font-family: var(--font-b);
            font-size: 13px;
            color: var(--t1);
            outline: none;
            background: #fff;
            appearance: none;
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            transition: all .2s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M3 5l3 3 3-3' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            height: 41px;
            overflow: visible;
        }

        .br-select option {
            padding: 8px 12px;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.4;
            min-height: 32px;
        }

        .br-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .10);
        }

        .br-select:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: #f8fafc;
        }

        /* ✅ Container untuk Prodi + Tombol Reset (kolom kedua) */
        .br-filter-item:nth-child(2)>div {
            display: flex !important;
            gap: 8px !important;
            align-items: center !important;
            width: 100% !important;
        }

        /* ✅ Dropdown Prodi - fleksibel mengisi sisa ruang */
        .br-filter-item:nth-child(2) .br-select {
            flex: 1 1 0% !important;
            min-width: 0 !important;
            width: 0 !important;
        }

        /* Tombol Reset di sebelah Prodi */
        .btn-reset-jurusan-prodi {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            font-size: 12.5px;
            color: var(--t2);
            text-decoration: none;
            transition: all .18s;
            font-family: var(--font-b);
            cursor: pointer;
            white-space: nowrap;
            height: 41px;
            flex-shrink: 0 !important;
        }

        .btn-reset-jurusan-prodi:hover {
            border-color: var(--rose);
            color: var(--rose);
            background: #fef2f2;
        }

        .btn-reset-jurusan-prodi i {
            font-size: 12px !important;
        }

        .br-filter-reset {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            font-size: 12.5px;
            color: var(--t2);
            text-decoration: none;
            transition: all .18s;
            font-family: var(--font-b);
            white-space: nowrap;
            height: 41px;
            align-self: flex-end;
        }

        .br-filter-reset:hover {
            border-color: var(--rose);
            color: var(--rose);
            background: #fef2f2;
        }

        .br-filter-reset i {
            font-size: 12px !important;
        }

        /* ═══════════════════════════════════════════
           ✅ EKSTERNAL CONTROLS - URUTKAN & VIEW TOGGLE
           ═══════════════════════════════════════════ */
        .br-filter-external-controls {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .br-sort-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .br-sort-label {
            font-size: 12px;
            color: var(--t3);
            font-weight: 500;
            white-space: nowrap;
        }

        .br-sort-select {
            padding: 8px 32px 8px 12px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            font-family: var(--font-b);
            font-size: 12.5px;
            color: var(--t1);
            outline: none;
            background: #fff;
            appearance: none;
            min-width: 160px;
            cursor: pointer;
            transition: all .2s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M3 5l3 3 3-3' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            height: 37px;
        }

        .br-sort-select option {
            padding: 6px 10px;
            white-space: normal;
            line-height: 1.3;
        }

        .br-sort-select:hover {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .08);
        }

        .br-sort-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .10);
        }

        /* VIEW TOGGLE */
        .br-view-toggle {
            display: flex;
            gap: 4px;
            background: var(--page);
            padding: 4px;
            border-radius: 10px;
            border: 1px solid var(--border);
            height: 41px;
        }

        .br-vbtn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 14px !important;
            transition: all .18s;
        }

        .br-vbtn:hover {
            background: var(--card);
            color: var(--blue);
        }

        .br-vbtn.active {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 3px 10px rgba(26, 86, 214, .3);
        }

        .br-vbtn i {
            font-size: 16px !important;
        }

        /* PRODI LOADING INDICATOR */
        .prodi-loading {
            display: none !important;
            align-items: center !important;
            gap: 6px !important;
            font-size: 12px !important;
            color: var(--blue) !important;
            padding: 8px 14px !important;
            background: rgba(26, 86, 214, 0.06) !important;
            border-radius: 10px !important;
            white-space: nowrap !important;
            font-weight: 500 !important;
            margin-top: 6px !important;
            margin-left: 0 !important;
            width: fit-content !important;
            align-self: flex-start !important;
        }

        .prodi-loading.active {
            display: inline-flex !important;
            animation: fadeInProdiLoading 0.4s ease !important;
        }

        .prodi-loading i {
            font-size: 13px !important;
            color: var(--blue) !important;
            animation: spin 1.5s linear infinite !important;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeInProdiLoading {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* RESULTS INFO */
        .br-results-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        /* Section Header */
        .br-sec-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .br-sec-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .br-sec-bar {
            width: 4px;
            height: 20px;
            border-radius: 4px;
            background: linear-gradient(180deg, var(--blue), var(--indigo));
        }

        .br-sec-title h3 {
            font-family: var(--font-d);
            font-size: 16px;
            color: var(--t1);
            letter-spacing: -.2px;
        }

        .br-sec-badge {
            background: var(--blue);
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .br-sec-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Active Filters */
        .br-active-filters {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .br-active-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            background: rgba(26, 86, 214, .08);
            color: var(--blue);
            border: 1px solid rgba(26, 86, 214, .18);
            animation: chipIn .25s ease;
        }

        @keyframes chipIn {
            from {
                opacity: 0;
                transform: scale(.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .br-active-chip a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(26, 86, 214, .15);
            color: var(--blue);
            text-decoration: none;
            font-size: 10px !important;
            transition: all .15s;
        }

        .br-active-chip a:hover {
            background: var(--blue);
            color: #fff;
        }

        .br-active-chip i {
            font-size: 10px !important;
        }

        /* Grid & Cards */
        .br-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(288px, 1fr));
            gap: 18px;
            position: relative;
            z-index: 1;
        }

        .br-grid.list-mode {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .br-grid.list-mode .br-card {
            flex-direction: row;
            max-height: 140px;
        }

        .br-grid.list-mode .br-thumb {
            width: 160px;
            min-width: 160px;
            height: auto;
            min-height: 140px;
            border-radius: var(--r-lg) 0 0 var(--r-lg);
        }

        .br-grid.list-mode .br-body {
            padding: 14px 18px;
        }

        .br-grid.list-mode .br-desc {
            -webkit-line-clamp: 1;
        }

        .br-grid.list-mode .br-meta {
            display: none;
        }

        .br-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            box-shadow: var(--s-sm);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .22s cubic-bezier(.22, .68, 0, 1.2), box-shadow .22s, border-color .22s;
            cursor: pointer;
            animation: cardFadeIn .35s ease backwards;
            position: relative;
            z-index: 2;
            height: 350px;
        }

        .br-card:nth-child(1) {
            animation-delay: .05s;
        }

        .br-card:nth-child(2) {
            animation-delay: .1s;
        }

        .br-card:nth-child(3) {
            animation-delay: .15s;
        }

        .br-card:nth-child(4) {
            animation-delay: .2s;
        }

        .br-card:nth-child(5) {
            animation-delay: .25s;
        }

        .br-card:nth-child(6) {
            animation-delay: .3s;
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .br-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--s-lg);
            border-color: rgba(26, 86, 214, .3);
            z-index: 3;
        }

        .thumb-grad-0 {
            background: linear-gradient(135deg, #1a3fa8 0%, #1a56d6 60%, #3b82f6 100%) !important;
        }

        .thumb-grad-1 {
            background: linear-gradient(135deg, #0f4c75 0%, #0ea5e9 100%) !important;
        }

        .thumb-grad-2 {
            background: linear-gradient(135deg, #134e3a 0%, #14b8a6 100%) !important;
        }

        .thumb-grad-3 {
            background: linear-gradient(135deg, #3b0764 0%, #6366f1 100%) !important;
        }

        .thumb-grad-4 {
            background: linear-gradient(135deg, #7c2d12 0%, #f59e0b 100%) !important;
        }

        .thumb-grad-5 {
            background: linear-gradient(135deg, #881337 0%, #f43f5e 100%) !important;
        }

        .br-thumb {
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .br-thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .09) 1px, transparent 1px);
            background-size: 18px 18px;
        }

        .br-thumb-icon {
            font-size: 42px !important;
            color: rgba(255, 255, 255, .88);
            z-index: 2;
            filter: drop-shadow(0 3px 8px rgba(0, 0, 0, .2));
            transition: transform .25s;
        }

        .br-card:hover .br-thumb-icon {
            transform: scale(1.1) translateY(-3px);
        }

        .br-thumb-ext {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 20px;
            padding: 3px 9px;
            font-size: 9.5px;
            color: #fff;
            letter-spacing: .8px;
            text-transform: uppercase;
            z-index: 3;
            backdrop-filter: blur(4px);
        }

        .br-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .br-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .br-title {
            font-family: var(--font-d);
            font-size: 13.5px;
            color: var(--t1);
            line-height: 1.35;
            flex: 1;
            padding-right: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            letter-spacing: -.1px;
        }

        .br-badges {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .br-badge {
            font-size: 9px;
            padding: 3px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .b-success {
            background: #dcfce7;
            color: #15803d;
        }

        .b-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .b-warn {
            background: #fef9c3;
            color: #a16207;
        }

        .b-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .b-gray {
            background: #f1f5f9;
            color: #64748b;
        }

        .br-desc {
            font-size: 11.5px;
            color: var(--t2);
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .br-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .br-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--t2);
        }

        .br-meta i {
            font-size: 11px !important;
            color: #93c5fd;
        }

        .br-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid var(--border);
        }

        .br-user {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .br-user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 9px;
            overflow: hidden;
        }

        .br-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .br-user-name {
            font-size: 11px;
            color: var(--t1);
            line-height: 1;
        }

        .br-user-date {
            font-size: 10px;
            color: var(--t3);
            margin-top: 1px;
        }

        .br-actions {
            display: flex;
            gap: 5px;
            position: relative;
            z-index: 10;
        }

        .br-act {
            width: 29px;
            height: 29px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12.5px !important;
            cursor: pointer;
            border: none;
            transition: all .18s;
            text-decoration: none;
            position: relative;
            z-index: 15;
        }

        .br-act-view {
            background: var(--blue);
            color: #fff;
        }

        .br-act-view:hover {
            background: #1240b5;
            transform: scale(1.08);
            box-shadow: 0 4px 10px rgba(26, 86, 214, .3);
        }

        .br-act-view i {
            font-size: 13px !important;
        }

        .br-act-dl {
            background: transparent;
            color: var(--blue);
            border: 1px solid var(--border);
        }

        .br-act-dl:hover {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .br-act-dl i {
            font-size: 13px !important;
        }

        /* Empty State */
        .br-empty {
            grid-column: 1/-1;
            background: var(--card);
            border: 1.5px dashed var(--border);
            border-radius: var(--r-xl);
            padding: 60px 32px;
            text-align: center;
        }

        .br-empty-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px !important;
            color: #fff;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(26, 86, 214, .25);
        }

        .br-empty-icon i {
            font-size: 30px !important;
        }

        .br-empty h4 {
            font-family: var(--font-d);
            font-size: 18px;
            color: var(--t1);
            margin-bottom: 8px;
        }

        .br-empty p {
            font-size: 13px;
            color: var(--t2);
            margin-bottom: 24px;
        }

        .br-empty-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: 10px;
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            font-size: 13.5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(26, 86, 214, .30);
            transition: all .2s;
        }

        .br-empty-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26, 86, 214, .35);
        }

        .br-empty-btn i {
            font-size: 14px !important;
        }

        /* Footer */
        .br-footer-page {
            text-align: center;
            margin-top: 32px;
            padding: 16px 20px;
            background: var(--card);
            border-radius: var(--r-md);
            border: 1px solid var(--border);
            font-size: 12.5px;
            color: var(--t3);
            position: relative;
            z-index: 1;
        }

        /* MODAL DETAIL */
        .br-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .br-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .br-modal-dialog {
            background: var(--card);
            border-radius: var(--r-xl);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
            max-width: 720px;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transform: scale(0.95) translateY(20px);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .br-modal-overlay.active .br-modal-dialog {
            transform: scale(1) translateY(0);
        }

        .br-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--navy), var(--blue));
            color: #fff;
        }

        .br-modal-head h5 {
            font-family: var(--font-d);
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .br-modal-head h5 i {
            font-size: 18px;
        }

        .br-modal-close {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .br-modal-close:hover {
            background: rgba(255, 255, 255, .25);
            border-color: rgba(255, 255, 255, .5);
            transform: scale(1.05);
        }

        .br-modal-body {
            padding: 24px;
            flex: 1;
        }

        .br-loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
        }

        .br-loading-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid var(--border);
            border-top-color: var(--blue);
            border-radius: 50%;
            animation: modalSpin 0.8s linear infinite;
            margin-bottom: 16px;
        }

        @keyframes modalSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .br-loading-text {
            font-size: 14px;
            color: var(--t2);
            font-weight: 500;
        }

        .detail-section {
            margin-bottom: 20px;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .detail-value {
            font-size: 14px;
            color: var(--t1);
            line-height: 1.6;
        }

        .detail-title-main {
            font-family: var(--font-d);
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .detail-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px;
            background: var(--page);
            border-radius: 10px;
        }

        .detail-meta-item i {
            font-size: 18px;
            color: var(--blue);
        }

        .detail-meta-text {
            font-size: 13px;
            color: var(--t1);
        }

        .detail-meta-label {
            font-size: 11px;
            color: var(--t3);
            display: block;
        }

        .detail-abstrak {
            background: var(--page);
            padding: 16px;
            border-radius: 10px;
            border-left: 3px solid var(--blue);
            font-size: 13.5px;
            line-height: 1.7;
            color: var(--t2);
        }

        .error-state {
            text-align: center;
            padding: 40px 20px;
        }

        .error-state i {
            font-size: 48px;
            color: var(--rose);
            margin-bottom: 16px;
        }

        .error-state h4 {
            font-family: var(--font-d);
            font-size: 18px;
            color: var(--t1);
            margin-bottom: 8px;
        }

        .error-state p {
            font-size: 13px;
            color: var(--t2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .br-hero {
                padding: 28px 24px;
            }

            .br-hero h2 {
                font-size: 20px;
            }

            .br-filter-bar {
                padding: 20px 18px;
                margin-bottom: 16px;
            }

            /* ✅ Mobile: Filter row jadi 1 kolom */
            .br-filter-row {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            .br-filter-item {
                min-width: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .br-select {
                min-width: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Mobile: Container Prodi stack vertikal */
            .br-filter-item:nth-child(2)>div {
                flex-direction: column !important;
                gap: 8px !important;
                width: 100% !important;
                align-items: stretch !important;
            }

            .br-filter-item:nth-child(2) .br-select {
                width: 100% !important;
                flex: none !important;
            }

            .btn-reset-jurusan-prodi {
                width: 100% !important;
                justify-content: center !important;
            }

            .prodi-loading {
                margin-left: 0;
            }

            /* External Controls Responsive */
            .br-filter-external-controls {
                justify-content: stretch !important;
                margin-bottom: 16px;
            }

            .br-sort-group {
                flex: 1;
                width: 100%;
            }

            .br-sort-select {
                flex: 1;
                min-width: 100%;
            }

            .br-grid {
                grid-template-columns: 1fr;
            }

            .br-grid.list-mode .br-card {
                flex-direction: column;
                max-height: none;
            }

            .br-card {
                height: auto;
                min-height: 320px;
            }

            .br-grid.list-mode .br-thumb {
                width: 100%;
                min-width: unset;
                height: 120px;
                border-radius: 0;
            }

            .br-search-wrapper {
                flex-direction: column !important;
            }

            .br-search-btn {
                width: 100% !important;
                justify-content: center !important;
            }

            .br-modal-dialog {
                max-width: 100%;
                border-radius: var(--r-lg);
            }

            .br-modal-head {
                padding: 16px 20px;
            }

            .br-modal-body {
                padding: 20px;
            }

            .detail-title-main {
                font-size: 18px;
            }

            .detail-meta-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .br-hero-chips {
                display: none !important;
            }

            .br-filter-bar {
                padding: 16px 14px;
            }

            .br-filter-item {
                margin-bottom: 4px;
            }

            .br-sec-header {
                flex-direction: column;
                align-items: stretch !important;
            }

            .br-sec-tools {
                justify-content: flex-end;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body data-browser-detail-endpoint="{{ route('browser.get-detail') }}"
    data-browser-prodi-endpoint="{{ route('browser.get-prodi') }}" data-csrf-token="{{ csrf_token() }}">

    <!-- Background Orbs -->
    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Page Content -->
    <div class="br-page">
        <!-- Hero -->
        <div class="br-hero">
            <div class="br-hero-eyebrow"><i class="bi bi-search"></i> Browser Dokumen</div>
            <h2>Jelajahi Repository Akademik</h2>
            <p>Temukan dan telusuri seluruh dokumen akademik yang tersedia di repositori SIPORA Politeknik Negeri
                Jember.</p>
            <div class="br-hero-chips">
                <div class="br-chip"><i class="bi bi-file-earmark-pdf"></i> PDF, DOC, PPT, XLS</div>
                <div class="br-chip"><i class="bi bi-people"></i> Semua Jurusan</div>
                <div class="br-chip"><i class="bi bi-shield-check"></i> Terverifikasi</div>
            </div>
        </div>

        <!-- Search Container -->
        <div class="br-search-container">
            <form method="GET" action="{{ route('browser.index') }}" id="searchForm"
                onsubmit="event.preventDefault(); handleSearchInput(document.getElementById('searchInput').value)">
                <div class="br-search-wrapper">
                    <input type="text" class="br-search-input" id="searchInput" name="q"
                        placeholder="Cari judul, penulis, kata kunci..." value="{{ request('q') }}" autocomplete="off"
                        oninput="handleSearchInput(this.value)">
                    <button type="button" class="br-search-btn"
                        onclick="handleSearchInput(document.getElementById('searchInput').value)">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- ✅ CARD FILTER - Jurusan, Prodi, Reset -->
        <form method="GET" action="{{ route('browser.index') }}" id="filterForm">
            <div class="br-filter-bar">
                <div class="br-filter-row">
                    <!-- Jurusan -->
                    <div class="br-filter-item">
                        <div class="br-filter-label">
                            <i class="bi bi-building"></i>
                            <span>Jurusan</span>
                        </div>
                        <select class="br-select" name="filter_jurusan" id="filterJurusan"
                            onchange="handleJurusanChange(this.value)">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusan_data as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}" @selected((string) $filter_jurusan === (string) $jurusan->id_jurusan)>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Program Studi + Reset Button -->
                    <div class="br-filter-item">
                        <div class="br-filter-label">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span>Program Studi</span>
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <select class="br-select" name="filter_prodi" id="filterProdi"
                                onchange="handleProdiChange(this.value)" @disabled(!$filter_jurusan && !$filter_prodi)>
                                <option value="">
                                    @if ($filter_jurusan || $filter_prodi)
                                        Semua Program Studi
                                    @else
                                        Pilih Jurusan dulu
                                    @endif
                                </option>
                                @if ($filter_jurusan || $filter_prodi)
                                    @foreach ($prodi_data as $prodi)
                                        <option value="{{ $prodi->id_prodi }}"
                                            data-jurusan="{{ $prodi->id_jurusan ?? '' }}" @selected((string) $filter_prodi === (string) $prodi->id_prodi)>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <!-- TOMBOL RESET DI SEBELAH PRODI -->
                            <button type="button" class="btn-reset-jurusan-prodi"
                                onclick="resetJurusanAndProdi(event)">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </div>

                    <!-- Reset Semua (jika ada filter aktif) -->
                    @if ($filter_jurusan || $filter_prodi || request('q'))
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <a href="{{ route('browser.index') }}" class="br-filter-reset"
                                onclick="resetAllFilters(event)">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Semua
                            </a>
                            <div id="prodiLoading" class="prodi-loading">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Memuat...</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        <!-- ✅ EKSTERNAL CONTROLS - Urutkan & View Toggle -->
        <div class="br-filter-external-controls">
            <!-- Urutkan -->
            <div class="br-sort-group">
                <span class="br-sort-label">Urutkan:</span>
                <select class="br-sort-select" id="sortSelect" onchange="applySort(this.value)">
                    <option value="?sort=newest">Terbaru</option>
                    <option value="?sort=oldest">Terlama</option>
                    <option value="?sort=title_az">Judul A-Z</option>
                    <option value="?sort=title_za">Judul Z-A</option>
                </select>
            </div>

            <!-- View Toggle (Grid/List) -->
            <div class="br-view-toggle">
                <button type="button" class="br-vbtn active" id="gridViewBtn" onclick="setViewMode('grid')"
                    title="Tampilan Grid">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="br-vbtn" id="listViewBtn" onclick="setViewMode('list')"
                    title="Tampilan List">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>

            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
        </div>

        <!-- Active Filters -->
        @if ($filter_jurusan || $filter_prodi || request('q'))
            <div class="br-active-filters">
                @if (request('q'))
                    <div class="br-active-chip">
                        <i class="bi bi-search" style="font-size:11px;"></i>
                        <span>"{{ request('q') }}"</span>
                        <a href="?{{ http_build_query(array_merge(request()->query(), ['q' => null])) }}">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                @endif
                @if ($filter_jurusan)
                    <div class="br-active-chip">
                        <i class="bi bi-building" style="font-size:11px;"></i>
                        <span>{{ \App\Models\MasterJurusan::where('id_jurusan', $filter_jurusan)->value('nama_jurusan') ?: 'Jurusan' }}</span>
                        <a href="#" onclick="event.preventDefault();resetJurusanAndProdi(event);">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                @endif
                @if ($filter_prodi)
                    @php
                        $prodi_name =
                            collect($prodi_data)->firstWhere('id_prodi', $filter_prodi)->nama_prodi ?? 'Program Studi';
                    @endphp
                    <div class="br-active-chip">
                        <i class="bi bi-mortarboard-fill" style="font-size:11px;"></i>
                        <span>{{ $prodi_name }}</span>
                        <a href="#" onclick="event.preventDefault();resetJurusanAndProdi(event);">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Results Info -->
        <div class="br-results-info">
        </div>

        <!-- Section Header with View Toggle -->
        <div class="br-sec-header">
            <div class="br-sec-title">
                <div class="br-sec-bar"></div>
                <h3>Dokumen Tersedia</h3>
                <div class="br-sec-badge">{{ count($documents) }}</div>
            </div>
        </div>

        <!-- Document Grid -->
        @if (count($documents) === 0)
            <div class="br-grid">
                <div class="br-empty">
                    <div class="br-empty-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h4>Tidak ada dokumen ditemukan</h4>
                    <p>Coba ubah filter atau perbesar cakupan pencarian Anda.</p>
                    <a href="{{ route('browser.index') }}" class="br-empty-btn">
                        <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        @else
            <div class="br-grid" id="documentGrid">
                @foreach ($documents as $index => $doc)
                    @php
                        $fileExt = strtolower($doc['file_type'] ?? '');
                        $gradClass = 'thumb-grad-' . $index % 6;
                        $badgeMap = [
                            'badge-success' => 'b-success',
                            'badge-info' => 'b-info',
                            'badge-warning' => 'b-warn',
                            'badge-danger' => 'b-danger',
                            'badge-secondary' => 'b-gray',
                        ];
                        $badgeClass = $badgeMap[$doc['status_badge'] ?? ''] ?? 'b-gray';
                        $thumbIcon = 'bi-file-earmark-text';
                        if (in_array($fileExt, ['doc', 'docx'])) {
                            $thumbIcon = 'bi-file-earmark-word-fill';
                        } elseif (in_array($fileExt, ['xls', 'xlsx'])) {
                            $thumbIcon = 'bi-file-earmark-spreadsheet';
                        } elseif (in_array($fileExt, ['ppt', 'pptx'])) {
                            $thumbIcon = 'bi-file-earmark-ppt';
                        } elseif ($fileExt === 'pdf') {
                            $thumbIcon = 'bi-file-earmark-pdf';
                        } elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $thumbIcon = 'bi-file-earmark-image';
                        }
                    @endphp
                    <div class="br-card" data-id="{{ $doc['dokumen_id'] }}"
                        data-jurusan="{{ $doc['id_jurusan'] ?? '' }}" data-prodi="{{ $doc['id_prodi'] ?? '' }}"
                        data-uploader="{{ $doc['uploader_name'] ?? '' }}"
                        data-keywords="{{ $doc['kata_kunci'] ?? '' }}"
                        data-uploaded="{{ $doc['tgl_unggah'] ?? '' }}"
                        onclick="showDetail({{ $doc['dokumen_id'] }})">
                        <div class="br-thumb {{ $gradClass }}">
                            <i class="bi {{ $thumbIcon }} br-thumb-icon"></i>
                            <div class="br-thumb-ext">{{ strtoupper($fileExt) ?: 'FILE' }}</div>
                        </div>
                        <div class="br-body">
                            <div class="br-header">
                                <div class="br-title">{{ $doc['judul'] }}</div>
                                <div class="br-badges">
                                    <span
                                        class="br-badge {{ $badgeClass }}">{{ $doc['status_name'] ?? '-' }}</span>
                                    @if (!empty($doc['turnitin']) && is_numeric($doc['turnitin']) && $doc['turnitin'] > 0)
                                        <span class="br-badge b-info">T:{{ $doc['turnitin'] }}%</span>
                                    @endif
                                </div>
                            </div>
                            <div class="br-desc">{{ \Illuminate\Support\Str::limit($doc['abstrak'] ?? '-', 150) }}
                            </div>
                            <div class="br-meta">
                                @if (!empty($doc['nama_jurusan']))
                                    <span><i
                                            class="bi bi-building"></i>{{ \Illuminate\Support\Str::limit($doc['nama_jurusan'], 18) }}</span>
                                @endif
                                @if (!empty($doc['nama_prodi']))
                                    <span><i
                                            class="bi bi-mortarboard-fill"></i>{{ \Illuminate\Support\Str::limit($doc['nama_prodi'], 18) }}</span>
                                @endif
                                @if (!empty($doc['tahun']))
                                    <span><i class="bi bi-calendar3"></i> {{ $doc['tahun'] }}</span>
                                @endif
                            </div>
                            <div class="br-footer">
                                <div class="br-user">
                                    <div class="br-user-avatar">
                                        {{ mb_strtoupper(mb_substr($doc['uploader_name'] ?? 'A', 0, 1)) }}</div>
                                    <div>
                                        <div class="br-user-name">
                                            {{ \Illuminate\Support\Str::limit($doc['uploader_name'] ?? 'Admin', 14) }}
                                        </div>
                                        <div class="br-user-date">
                                            {{ \Carbon\Carbon::parse($doc['tgl_unggah'] ?? 'now')->format('d M y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="br-actions">
                                    <button class="br-act br-act-view" title="Lihat Detail"
                                        onclick="event.stopPropagation();showDetail({{ $doc['dokumen_id'] }})">
                                        <i class="bi bi-info-circle-fill"></i>
                                    </button>
                                    <a href="{{ $doc['download_url'] ?? '#' }}" download class="br-act br-act-dl"
                                        title="Unduh"
                                        onclick="event.stopPropagation();showToastCompact('success','Mengunduh','File sedang diunduh...', 6000)">
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

    <!-- Footer -->
    <div class="br-footer-page">&copy; {{ date('Y') }} SIPORA — Repository Assets</div>

    <!-- Modal Detail -->
    <div id="detailModal" class="br-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
        <div class="br-modal-dialog">
            <div class="br-modal-head">
                <h5 id="detailTitle">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Loading...</span>
                </h5>
                <button class="br-modal-close" onclick="closeDetailModal()" aria-label="Tutup modal" type="button">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="br-modal-body" id="detailBody"></div>
        </div>
    </div>

    <!-- Chatbot -->
    @include('components.chatbot_widget')

    <!-- JavaScript -->
    <script>
        let _currentDocId = null;
        let isSubmitting = false;

        function showProdiLoading(show) {
            const el = document.getElementById('prodiLoading');
            if (el) {
                if (show) {
                    el.classList.add('active');
                    el.style.animation = 'none';
                    el.offsetHeight;
                    el.style.animation = null;
                } else {
                    el.classList.remove('active');
                }
            }
        }

        // FUNGSI RESET JURUSAN DAN PRODI
        function resetJurusanAndProdi(event) {
            if (event) event.preventDefault();

            console.log('[RESET] Resetting Jurusan and Prodi only...');

            const jurusanSelect = document.getElementById('filterJurusan');
            if (jurusanSelect) {
                jurusanSelect.value = '';
            }

            resetProdiSelect();

            const searchQuery = document.getElementById('searchInput')?.value || '';
            applyDocumentFilter('', '', searchQuery);
            updateFilterQueryString('', '');

            // ✅ MENGGUNAKAN COMPACT TOAST (SAMA SEPERTI UPLOAD PAGE)
            showToastCompact('success', 'Berhasil', 'Filter jurusan dan program studi telah direset', 3000);

            const prodiSelect = document.getElementById('filterProdi');
            if (prodiSelect) {
                prodiSelect.disabled = true;
                prodiSelect.innerHTML = '<option value="">Pilih Jurusan dulu</option>';
            }
        }

        function handleJurusanChange(jurusanId) {
            console.log('[JURUSAN] Changed to:', jurusanId);
            const searchQuery = document.getElementById('searchInput')?.value || '';

            if (!jurusanId || jurusanId === '' || jurusanId === null) {
                resetProdiSelect();
                applyDocumentFilter('', '', searchQuery);
                updateFilterQueryString('', '');
                return;
            }

            applyDocumentFilter(jurusanId, '', searchQuery);
            updateFilterQueryString(jurusanId, '');
            loadProdiByJurusan(jurusanId);
        }

        function loadProdiByJurusan(jurusanId) {
            const prodiSelect = document.getElementById('filterProdi');
            if (!prodiSelect) return;

            showProdiLoading(true);
            prodiSelect.disabled = true;
            prodiSelect.innerHTML = '<option value="">Memuat data...</option>';

            const endpoint = document.body.dataset.browserProdiEndpoint;
            if (!endpoint) {
                showErrorToast('Error', 'Endpoint API tidak ditemukan');
                resetProdiSelect();
                return;
            }

            let url = endpoint + (endpoint.includes('?') ? '&' : '?') + 'jurusan_id=' + encodeURIComponent(jurusanId) +
                '&_t=' + Date.now();
            const csrfToken = document.body.dataset.csrfToken || '';

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrfToken ? {
                            'X-CSRF-TOKEN': csrfToken
                        } : {})
                    },
                    credentials: 'same-origin',
                    signal: controller.signal
                })
                .then(response => {
                    clearTimeout(timeoutId);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    populateProdiSelect(data, jurusanId);
                    showProdiLoading(false);
                    prodiSelect.disabled = false;

                    // ✅ MENGGUNAKAN COMPACT TOAST (SAMA SEPERTI UPLOAD PAGE)
                    showToastCompact('success', 'Berhasil', 'Data Program Studi diperbarui', 3000);
                })
                .catch(error => {
                    clearTimeout(timeoutId);
                    prodiSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    prodiSelect.disabled = true;
                    showProdiLoading(false);

                    // ✅ MENGGUNAKAN COMPACT TOAST (SAMA SEPERTI UPLOAD PAGE)
                    showToastCompact('error', 'Error', error.message || 'Gagal memuat data prodi', 5000);
                });
        }

        function populateProdiSelect(data, jurusanId) {
            const prodiSelect = document.getElementById('filterProdi');
            if (!prodiSelect) return;

            prodiSelect.innerHTML = '<option value="">Semua Program Studi</option>';

            let prodiList = [];
            if (Array.isArray(data)) {
                prodiList = data;
            } else if (data && typeof data === 'object') {
                prodiList = data.data || data.prodi || (data.id_prodi ? [data] : []);
            }

            if (prodiList.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Tidak ada Program Studi';
                opt.disabled = true;
                prodiSelect.appendChild(opt);
                return;
            }

            prodiList.forEach((prodi, idx) => {
                if (!prodi || typeof prodi !== 'object') return;

                const opt = document.createElement('option');
                opt.value = String(prodi.id_prodi || prodi.id || '');
                opt.textContent = String(prodi.nama_prodi || prodi.nama || prodi.name || `Prodi ${idx+1}`).trim();
                opt.dataset.jurusan = String(jurusanId);

                const params = new URLSearchParams(window.location.search);
                if (params.get('filter_prodi') && String(opt.value) === String(params.get('filter_prodi'))) {
                    opt.selected = true;
                }

                prodiSelect.appendChild(opt);
            });
        }

        function resetProdiSelect() {
            const el = document.getElementById('filterProdi');
            if (el) {
                el.innerHTML = '<option value="">Pilih Jurusan dulu</option>';
                el.disabled = true;
                el.value = '';
            }
            showProdiLoading(false);
        }

        function handleProdiChange(prodiId) {
            const jurusanSelect = document.getElementById('filterJurusan');
            const jurusanId = jurusanSelect ? jurusanSelect.value : '';
            const searchQuery = document.getElementById('searchInput')?.value || '';
            applyDocumentFilter(jurusanId, prodiId, searchQuery);
            updateFilterQueryString(jurusanId, prodiId);
        }

        function handleSearchInput(query) {
            const jurusanId = document.getElementById('filterJurusan')?.value || '';
            const prodiId = document.getElementById('filterProdi')?.value || '';
            applyDocumentFilter(jurusanId, prodiId, query);
            updateSearchQueryString(query);
        }

        function applyDocumentFilter(jurusanId, prodiId, searchQuery = '') {
            const grid = document.getElementById('documentGrid');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.br-card'));
            const selectedJurusan = String(jurusanId || '').trim();
            const selectedProdi = String(prodiId || '').trim();
            const normalizedSearch = String(searchQuery || '').trim().toLowerCase();

            cards.forEach(card => {
                const cardJurusan = String(card.dataset.jurusan || '').trim();
                const cardProdi = String(card.dataset.prodi || '').trim();
                const cardTitle = card.querySelector('.br-title')?.textContent?.trim().toLowerCase() || '';
                const cardUploader = String(card.dataset.uploader || '').trim().toLowerCase();
                const cardKeywords = String(card.dataset.keywords || '').trim().toLowerCase();
                let visible = true;

                if (selectedJurusan && cardJurusan !== selectedJurusan) visible = false;
                if (selectedProdi && cardProdi !== selectedProdi) visible = false;
                if (normalizedSearch) {
                    const haystack = [cardTitle, cardUploader, cardKeywords].join(' ');
                    if (!haystack.includes(normalizedSearch)) visible = false;
                }

                card.style.display = visible ? '' : 'none';
            });
        }

        function updateSearchQueryString(query) {
            try {
                const url = new URL(window.location.href);
                if (query && String(query).trim()) {
                    url.searchParams.set('q', query.trim());
                } else {
                    url.searchParams.delete('q');
                }
                window.history.replaceState({}, '', url.toString());
            } catch (e) {}
        }

        function updateFilterQueryString(jurusanId, prodiId) {
            try {
                const url = new URL(window.location.href);
                if (jurusanId) {
                    url.searchParams.set('filter_jurusan', jurusanId);
                } else {
                    url.searchParams.delete('filter_jurusan');
                }
                if (prodiId) {
                    url.searchParams.set('filter_prodi', prodiId);
                } else {
                    url.searchParams.delete('filter_prodi');
                }
                window.history.replaceState({}, '', url.toString());
            } catch (e) {}
        }

        function resetAllFilters(e) {
            e.preventDefault();
            window.location.href = '{{ route('browser.index') }}';
        }

        function loadProdiByJurusanSilent(jurusanId) {
            const prodiSelect = document.getElementById('filterProdi');
            if (!prodiSelect || !jurusanId) return;

            const endpoint = document.body.dataset.browserProdiEndpoint;
            if (!endpoint) return;

            let url = endpoint + (endpoint.includes('?') ? '&' : '?') + 'jurusan_id=' + encodeURIComponent(jurusanId) +
                '&_t=' + Date.now();
            const csrf = document.body.dataset.csrfToken || '';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? {
                            'X-CSRF-TOKEN': csrf
                        } : {})
                    },
                    credentials: 'same-origin'
                })
                .then(r => {
                    if (!r.ok) throw new Error(r.status);
                    return r.json();
                })
                .then(d => {
                    populateProdiSelect(d, jurusanId);
                    prodiSelect.disabled = false;
                })
                .catch(err => {
                    prodiSelect.innerHTML = '<option value="">Semua Program Studi</option>';
                    prodiSelect.disabled = false;
                });
        }

        /**
         * ✅ COMPACT TOAST NOTIFICATION (Menggunakan SweetAlert2)
         *
         * SAMA SEPERTI HALAMAN UPLOAD - Versi compact dengan ukuran lebih kecil
         * - Lebih kecil dan ringkas
         * - Hanya menampilkan title (tanpa message text)
         * - Icon lebih kecil (32px)
         * - Animasi smooth dari kanan
         */
        function showToastCompact(type, title, message, duration = 4000) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type === 'success' ? 'success' : (type === 'error' ? 'error' : (type === 'warning' ? 'warning' : 'info')),
                    title: title,
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: duration,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            } else {
                console.log(`[${type.toUpperCase()}] ${title}: ${message}`);
            }
        }

        /**
         * ✅ LEGACY FUNCTION - Untuk backward compatibility
         * Tetap bisa dipanggil tapi akan redirect ke showToastCompact
         */
        function showToast(type, title, message, duration = 6000) {
            // Redirect ke compact version
            showToastCompact(type, title, message, duration);
        }

        /**
         * ✅ ERROR TOAST WRAPPER
         */
        function showErrorToast(t, m) {
            showToastCompact('error', t, m, 8000);
        }

        function escapeHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function initSearchInput() {
            const searchInput = document.getElementById('searchInput');
            if (!searchInput) return;
            searchInput.addEventListener('input', function() {
                handleSearchInput(this.value);
            });
            if (searchInput.value && String(searchInput.value).trim()) handleSearchInput(searchInput.value);
        }

        function applySort(sortValue) {
            if (!sortValue) return;
            const sortParam = sortValue.replace('?sort=', '');
            const grid = document.getElementById('documentGrid');
            if (!grid) {
                let url = new URL(window.location.href);
                url.searchParams.set('sort', sortParam);
                window.location.href = url.toString();
                return;
            }

            const cards = Array.from(grid.querySelectorAll('.br-card'));
            cards.sort((a, b) => {
                if (sortParam === 'newest' || sortParam === 'oldest') {
                    const dateA = new Date(a.dataset.uploaded || '');
                    const dateB = new Date(b.dataset.uploaded || '');
                    return sortParam === 'newest' ? dateB - dateA : dateA - dateB;
                }
                const titleA = a.querySelector('.br-title')?.textContent?.trim().toLowerCase() || '';
                const titleB = b.querySelector('.br-title')?.textContent?.trim().toLowerCase() || '';
                if (sortParam === 'title_az') return titleA.localeCompare(titleB);
                if (sortParam === 'title_za') return titleB.localeCompare(titleA);
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', sortParam);
                window.history.replaceState({}, '', url.toString());
            } catch (e) {}
        }

        function initializeSortSelect() {
            const sortSelect = document.getElementById('sortSelect');
            if (!sortSelect) return;
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort') || 'newest';
            sortSelect.value = '?sort=' + currentSort;
            sortSelect.addEventListener('change', function() {
                applySort(this.value);
            });
        }

        function showDetail(docId) {
            if (!docId && docId !== 0) {
                showToastCompact('error', 'Error', 'ID dokumen tidak valid', 5000);
                return;
            }
            _currentDocId = docId;
            const titleEl = document.querySelector('#detailTitle span');
            if (titleEl) titleEl.textContent = 'Memuat Data...';
            const bodyEl = document.getElementById('detailBody');
            if (bodyEl) bodyEl.innerHTML =
                '<div class="br-loading-state"><div class="br-loading-spinner"></div><div class="br-loading-text">Memuat detail dokumen...</div></div>';
            openDetailModal();

            let ep = document.body.dataset.browserDetailEndpoint;
            if (!ep) {
                renderErrorState('Error Konfigurasi', 'Endpoint API tidak ditemukan');
                return;
            }

            let url = ep + (ep.indexOf('?') === -1 ? '?' : '&') + 'id=' + encodeURIComponent(docId);
            const csrf = document.body.dataset.csrfToken || '';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? {
                            'X-CSRF-TOKEN': csrf
                        } : {})
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP Error ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (!data || typeof data !== 'object') throw new Error('Data tidak valid');
                    if (data.success === false) throw new Error(data.message || 'Server error');
                    const docData = data.data || data.document || (data.judul ? data : null) || (Array.isArray(data) ?
                        data[0] : data);
                    if (!docData) throw new Error('Data dokumen tidak ditemukan');
                    renderDetailData(docData);
                })
                .catch(error => {
                    renderErrorState('Gagal Memuat', error.message || 'Terjadi kesalahan');
                });
        }

        function renderDetailData(doc) {
            const bodyEl = document.getElementById('detailBody');
            const titleEl = document.querySelector('#detailTitle span');
            if (!bodyEl) return;
            if (titleEl) titleEl.textContent = doc.judul || 'Detail Dokumen';

            const judul = doc.judul || '-';
            const abstrak = doc.abstrak || doc.abstract || '-';
            const tahun = doc.tahun || '-';
            const namaJurusan = doc.nama_jurusan || doc.jurusan || '-';
            const namaProdi = doc.nama_prodi || doc.prodi || '-';
            const uploaderName = doc.uploader_name || doc.penulis || 'Unknown';
            const fileType = (doc.file_type || doc.type || 'pdf').toUpperCase();
            const fileSize = doc.file_size || doc.size || 0;
            const turnitin = doc.turnitin || null;
            const statusName = doc.status_name || doc.status || '-';

            bodyEl.innerHTML = `
                <h2 class="detail-title-main">${escapeHtml(judul)}</h2>
                <div class="detail-meta-grid">
                    <div class="detail-meta-item"><i class="bi bi-building"></i><div><span class="detail-meta-label">Jurusan</span><span class="detail-meta-text">${escapeHtml(namaJurusan)}</span></div></div>
                    <div class="detail-meta-item"><i class="bi bi-mortarboard-fill"></i><div><span class="detail-meta-label">Program Studi</span><span class="detail-meta-text">${escapeHtml(namaProdi)}</span></div></div>
                    <div class="detail-meta-item"><i class="bi bi-calendar3"></i><div><span class="detail-meta-label">Tahun</span><span class="detail-meta-text">${escapeHtml(tahun)}</span></div></div>
                    <div class="detail-meta-item"><i class="bi bi-person-fill"></i><div><span class="detail-meta-label">Diunggah oleh</span><span class="detail-meta-text">${escapeHtml(uploaderName)}</span></div></div>
                </div>
                <div class="detail-section"><div class="detail-label">Abstrak</div><div class="detail-abstrak">${escapeHtml(abstrak)}</div></div>
                <div class="detail-section"><div class="detail-label">Informasi File</div><div class="detail-value"><i class="bi bi-file-earmark"></i> Tipe: <strong>${escapeHtml(fileType)}</strong> | Ukuran: <strong>${formatSize(fileSize)}</strong> ${turnitin ? `| Turnitin: <strong>${turnitin}%</strong>` : ''}</div></div>
                <div class="detail-section"><div class="detail-label">Status</div><div class="detail-value"><span class="br-badge b-info">${escapeHtml(statusName)}</span></div></div>
            `;
        }

        function renderErrorState(title, message) {
            const bodyEl = document.getElementById('detailBody');
            const titleEl = document.querySelector('#detailTitle');
            if (titleEl) titleEl.textContent = 'Error';
            if (bodyEl) {
                bodyEl.innerHTML =
                    `<div class="error-state"><i class="bi bi-exclamation-triangle-fill"></i><h4>${escapeHtml(title)}</h4><p>${escapeHtml(message)}</p><button onclick="closeDetailModal()" class="br-empty-btn" style="margin-top:16px;"><i class="bi bi-x-lg"></i> Tutup</button></div>`;
            }
        }

        function formatSize(bytes) {
            if (!bytes || bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function openDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
                _currentDocId = null;
            }
        }

        window.setViewMode = function(mode) {
            const grid = document.getElementById('documentGrid');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');
            if (!grid) return;
            if (mode === 'list') {
                grid.classList.add('list-mode');
            } else {
                grid.classList.remove('list-mode');
            }
            if (gridViewBtn && listViewBtn) {
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.remove('active');
                if (mode === 'grid') gridViewBtn.classList.add('active');
                else if (mode === 'list') listViewBtn.classList.add('active');
            }
            try {
                localStorage.setItem('sipora_view_mode', mode);
            } catch (e) {}
        };

        function initViewMode() {
            const grid = document.getElementById('documentGrid');
            if (!grid) return;
            try {
                const savedMode = localStorage.getItem('sipora_view_mode');
                if (savedMode && (savedMode === 'grid' || savedMode === 'list')) setViewMode(savedMode);
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            const jSel = document.getElementById('filterJurusan');
            const pSel = document.getElementById('filterProdi');
            if (jSel && jSel.value) {
                setTimeout(() => loadProdiByJurusanSilent(jSel.value), 100);
            } else if (pSel) {
                pSel.disabled = true;
            }
            initializeSortSelect();
            initViewMode();
            initSearchInput();
            showProdiLoading(false);

            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeDetailModal();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('active')) closeDetailModal();
                });
            }
        });
    </script>

    <script src="{{ asset('assets/js/browser-page.js') }}"></script>
</body>

</html>
