<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Pencarian Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/floating-button.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
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

        /* Page Container - RESPONSIF SESUAI BROWSE */
        .br-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 1;
        }

        /* Hero Section - FONT SESUAI BROWSE */
        .br-hero {
            border-radius: var(--r-xl);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            padding: 36px 40px;
            /* ✅ Sesuai browse */
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%);
            /* ✅ Sesuai browse */
            box-shadow: 0 12px 40px rgba(26, 86, 214, .30), 0 2px 8px rgba(15, 23, 42, .12);
            z-index: 1;
        }

        .br-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px);
            background-size: 22px 22px;
            /* ✅ Sesuai browse */
            opacity: .5;
        }

        .br-hero::after {
            content: '';
            position: absolute;
            width: 280px;
            /* ✅ Sesuai browse */
            height: 280px;
            /* ✅ Sesuai browse */
            border-radius: 50%;
            border: 50px solid rgba(255, 255, 255, .05);
            /* ✅ Sesuai browse */
            top: -120px;
            right: -80px;
        }

        .br-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .12);
            /* ✅ Sesuai browse */
            border: 1px solid rgba(255, 255, 255, .2);
            /* ✅ Sesuai browse */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            padding: 4px 12px;
            /* ✅ Sesuai browse */
            font-size: 10.5px;
            /* ✅ Sesuai browse */
            color: rgba(255, 255, 255, .85);
            /* ✅ Sesuai browse */
            letter-spacing: .5px;
            /* ✅ Sesuai browse */
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 14px;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
        }

        .br-hero-eyebrow i {
            font-size: 10.5px !important;
            /* ✅ Sesuai browse */
        }

        .br-hero h2 {
            font-family: var(--font-d);
            font-size: 24px;
            /* ✅ Sesuai browse (fixed, tidak responsive) */
            color: #fff;
            margin-bottom: 6px;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
            line-height: 1.3;
        }

        .br-hero p {
            font-size: 13.5px;
            /* ✅ Sesuai browse (fixed) */
            color: rgba(255, 255, 255, .68);
            /* ✅ Sesuai browse */
            max-width: 480px;
            /* ✅ Sesuai browse */
            line-height: 1.55;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
            margin-bottom: 0;
        }

        .br-hero-chips {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .br-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .12);
            /* ✅ Sesuai browse */
            border: 1px solid rgba(255, 255, 255, .18);
            /* ✅ Sesuai browse */
            border-radius: 20px;
            padding: 5px 12px;
            /* ✅ Sesuai browse */
            font-size: 11.5px;
            /* ✅ Sesuai browse */
            color: rgba(255, 255, 255, .85);
            /* ✅ Sesuai browse */
            font-weight: 500;
        }

        .br-chip i {
            font-size: 11.5px !important;
            /* ✅ Sesuai browse */
        }

        /* ==================== SEARCH CONTAINER ==================== */
        .search-section {
            margin-bottom: 24px;
        }

        .search-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-md);
            /* ✅ Sesuai browse style */
            padding: 24px 28px;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 100;
            overflow: visible;
        }

        /* Search Input Group */
        .search-input-group {
            display: flex;
            gap: 10px;
            align-items: stretch;
            max-width: 850px;
            margin: 0 auto 20px;
            position: relative;
        }

        .search-input-wrapper {
            flex: 1;
            min-width: 0;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input-field {
            width: 100%;
            min-width: 0;
            padding: 14px 20px 14px 48px;
            border-radius: 14px;
            border: 2px solid var(--border);
            font-family: var(--font-b);
            font-size: 14px;
            color: var(--t1);
            outline: none;
            background: #fff;
            transition: all .25s cubic-bezier(.22, .68, 0, 1);
            position: relative;
            z-index: 2;
        }

        .search-input-field:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26, 86, 214, .10), 0 4px 16px rgba(26, 86, 214, .12);
            /* ✅ Sesuai browse */
        }

        .search-input-field::placeholder {
            color: var(--t3);
        }

        /* Search Icon Inside Input */
        .search-icon-inner {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: var(--t3);
            transition: all .3s ease;
            pointer-events: none;
        }

        .search-icon-inner i {
            font-size: 16px !important;
            /* ✅ Sesuai browse */
        }

        /* Clear Input Button */
        .clear-input-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: var(--border);
            color: var(--t2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
            font-size: 14px !important;
        }

        .clear-input-btn.visible {
            opacity: 1;
        }

        .clear-input-btn:hover {
            background: var(--rose);
            color: #fff;
            transform: translateY(-50%) scale(1.15);
        }

        /* Voice Search Button */
        .voice-search-btn {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            border: 2px solid var(--border);
            background: linear-gradient(135deg, #fff, #fafbfc);
            color: var(--t2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .voice-search-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            opacity: 0;
            transition: opacity .3s ease;
        }

        .voice-search-btn:hover {
            border-color: var(--blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 86, 214, .2);
        }

        .voice-search-btn:hover::before {
            opacity: 1;
        }

        .voice-search-btn:hover i,
        .voice-search-btn.active i {
            color: #fff;
            position: relative;
            z-index: 2;
        }

        .voice-search-btn i {
            font-size: 22px !important;
            transition: all .3s ease;
            position: relative;
            z-index: 2;
        }

        .voice-search-btn.active {
            animation: voicePulse 1.5s ease-in-out infinite;
            border-color: var(--rose);
        }

        @keyframes voicePulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(244, 63, 94, .4);
            }

            50% {
                box-shadow: 0 0 0 12px rgba(244, 63, 94, 0);
            }
        }

        /* Primary Search Button */
        .search-btn-primary {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            /* ✅ Sesuai browse */
            border: none;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            font-family: var(--font-d);
            font-size: 14px;
            /* ✅ Sesuai browse */
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            /* ✅ Sesuai browse */
            white-space: nowrap;
            box-shadow: 0 4px 16px rgba(26, 86, 214, .30);
            /* ✅ Sesuai browse */
            transition: all .3s cubic-bezier(0.34, 1.56, 0.64, 1);
            /* ✅ Sesuai browse */
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .search-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            /* ✅ Sesuai browse */
            box-shadow: 0 12px 32px rgba(26, 86, 214, .45), 0 4px 12px rgba(26, 86, 214, .25);
            /* ✅ Sesuai browse */
        }

        .search-btn-primary:active {
            transform: translateY(-1px) scale(0.98);
            /* ✅ Sesuai browse */
        }

        .search-btn-primary i {
            font-size: 16px !important;
            /* ✅ Sesuai browse */
        }

        /* Search Suggestions Panel */
        .search-suggestions-panel {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: var(--card);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(15, 23, 42, .20), 0 8px 20px rgba(15, 23, 42, .10);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(.98);
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
            max-height: 420px;
            overflow-y: auto;
            margin-top: 8px;
        }

        .search-suggestions-panel.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .suggestions-header {
            padding: 14px 18px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--t3);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border);
        }

        .suggestions-header i {
            font-size: 13px !important;
        }

        .suggestion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            cursor: pointer;
            transition: all .2s ease;
            border-bottom: 1px solid rgba(228, 233, 245, .5);
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: rgba(26, 86, 214, .04);
        }

        .suggestion-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(26, 86, 214, .08), rgba(99, 102, 241, .05));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue);
            font-size: 15px !important;
            flex-shrink: 0;
        }

        .suggestion-text {
            flex: 1;
            min-width: 0;
        }

        .suggestion-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
            margin-bottom: 2px;
        }

        .suggestion-meta {
            font-size: 11px;
            color: var(--t3);
        }

        .suggestion-arrow {
            color: var(--t3);
            font-size: 14px !important;
            opacity: 0;
            transform: translateX(-8px);
            transition: all .25s ease;
        }

        .suggestion-item:hover .suggestion-arrow {
            opacity: 1;
            transform: translateX(0);
            color: var(--blue);
        }

        /* Popular Keywords Section */
        .keywords-section {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-sm);
            /* ✅ Sesuai browse */
            padding: 20px 24px;
            /* ✅ Sesuai browse */
            margin-bottom: 22px;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 10;
        }

        .keywords-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .keywords-icon {
            font-size: 15px !important;
            /* ✅ Sesuai browse filter icon */
            color: var(--blue);
            /* ✅ Sesuai browse */
        }

        .keywords-header h3 {
            font-family: var(--font-d);
            font-size: 16px;
            /* ✅ Sesuai browse section title */
            color: var(--t1);
            font-weight: 600;
            letter-spacing: -.2px;
            /* ✅ Sesuai browse */
        }

        .keywords-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            /* ✅ Sesuai browse active filters gap */
        }

        .keyword-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            /* ✅ Sesuai browse active chip */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            background: rgba(26, 86, 214, .08);
            /* ✅ Sesuai browse active chip */
            color: var(--blue);
            border: 1px solid rgba(26, 86, 214, .18);
            /* ✅ Sesuai browse active chip */
            text-decoration: none;
            font-size: 12px;
            /* ✅ Sesuai browse active chip */
            transition: all .25s ease;
            /* ✅ Sesuai browse */
            font-family: var(--font-b);
            font-weight: 500;
            cursor: pointer;
        }

        .keyword-item:hover {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(26, 86, 214, .3);
        }

        .keyword-item i {
            font-size: 11px !important;
            /* ✅ Sesuai browse active chip icon */
        }

        /* Results Section Header */
        .results-header {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 18px;
            /* ✅ Sesuai browse */
            padding: 20px 24px;
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-sm);
            /* ✅ Sesuai browse */
        }

        @media (min-width: 768px) {
            .results-header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 20px 28px;
            }
        }

        .results-info h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-d);
            font-size: 16px;
            /* ✅ Sesuai browse section title */
            color: var(--t1);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .results-info h4 i {
            color: var(--blue);
            font-size: 16px !important;
            /* ✅ Sesuai browse */
        }

        .results-count {
            font-size: 13px;
            /* ✅ Tetap */
            color: var(--t2);
        }

        .results-count strong {
            color: var(--blue);
            font-family: var(--font-d);
            font-size: 16px;
            /* ✅ Sesuai browse badge */
            font-weight: 700;
        }

        .results-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            /* ✅ Sesuai browse tools gap */
            flex-wrap: wrap;
        }

        .sort-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-label {
            font-size: 12px;
            /* ✅ Sesuai browse sort label */
            color: var(--t3);
            font-weight: 500;
        }

        .sort-dropdown {
            padding: 8px 32px 8px 12px;
            /* ✅ Sesuai browse sort select */
            border-radius: 8px;
            /* ✅ Sesuai browse sort select */
            border: 1.5px solid var(--border);
            font-family: var(--font-b);
            font-size: 12.5px;
            /* ✅ Sesuai browse sort select */
            color: var(--t1);
            outline: none;
            background: #fff;
            appearance: none;
            min-width: 160px;
            /* ✅ Sesuai browse sort select */
            cursor: pointer;
            transition: all .2s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M3 5l3 3 3-3' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            /* ✅ Sesuai browse */
        }

        .sort-dropdown:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .08);
            /* ✅ Sesuai browse */
        }

        .view-toggle {
            display: flex;
            gap: 4px;
            /* ✅ Sesuai browse toggle gap */
            background: var(--page);
            /* ✅ Sesuai browse */
            padding: 4px;
            /* ✅ Sesuai browse */
            border-radius: 10px;
            /* ✅ Sesuai browse */
            border: 1px solid var(--border);
            /* ✅ Sesuai browse */
        }

        .view-btn {
            width: 34px;
            /* ✅ Sesuai browse vbtn */
            height: 34px;
            /* ✅ Sesuai browse vbtn */
            border-radius: 8px;
            /* ✅ Sesuai browse vbtn */
            border: none;
            /* ✅ Sesuai browse vbtn */
            background: transparent;
            /* ✅ Sesuai browse vbtn */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 14px !important;
            /* ✅ Sesuai browse vbtn */
            transition: all .18s;
            /* ✅ Sesuai browse vbtn */
        }

        .view-btn:hover {
            background: var(--card);
            /* ✅ Sesuai browse vbtn */
            color: var(--blue);
        }

        .view-btn.active {
            background: var(--blue);
            /* ✅ Sesuai browse vbtn */
            color: #fff;
            box-shadow: 0 3px 10px rgba(26, 86, 214, .3);
            /* ✅ Sesuai browse vbtn */
        }

        .view-btn i {
            font-size: 16px !important;
            /* ✅ Sesuai browse vbtn */
        }

        /* Active Filters Bar */
        .active-filters-bar {
            display: flex;
            gap: 8px;
            /* ✅ Sesuai browse */
            margin-bottom: 18px;
            /* ✅ Sesuai browse */
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-chip-active {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            /* ✅ Sesuai browse active chip */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            font-size: 12px;
            /* ✅ Sesuai browse active chip */
            background: rgba(26, 86, 214, .08);
            /* ✅ Sesuai browse */
            color: var(--blue);
            border: 1px solid rgba(26, 86, 214, .18);
            /* ✅ Sesuai browse */
            font-weight: 500;
            animation: chipIn .25s ease;
            /* ✅ Sesuai browse */
        }

        @keyframes chipIn {

            /* ✅ Sesuai browse */
            from {
                opacity: 0;
                transform: scale(.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .filter-chip-active a {
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
            /* ✅ Sesuai browse */
            transition: all .15s;
        }

        .filter-chip-active a:hover {
            background: var(--blue);
            color: #fff;
        }

        .clear-all-filters {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            /* ✅ Sesuai browse reset button */
            padding: 9px 16px;
            /* ✅ Sesuai browse reset button */
            border-radius: 10px;
            /* ✅ Sesuai browse reset button */
            font-size: 12.5px;
            /* ✅ Sesuai browse reset button */
            background: #fff;
            /* ✅ Sesuai browse reset button */
            color: var(--t2);
            /* ✅ Sesuai browse reset button */
            border: 1.5px solid var(--border);
            /* ✅ Sesuai browse reset button */
            text-decoration: none;
            font-weight: 500;
            /* ✅ Sesuai browse */
            transition: all .18s;
            /* ✅ Sesuai browse */
            cursor: pointer;
            font-family: var(--font-b);
            /* ✅ Sesuai browse */
        }

        .clear-all-filters:hover {
            border-color: var(--rose);
            /* ✅ Sesuai browse reset hover */
            color: var(--rose);
            /* ✅ Sesuai browse reset hover */
            background: #fef2f2;
            /* ✅ Sesuai browse reset hover */
        }

        .clear-all-filters i {
            font-size: 12px !important;
            /* ✅ Sesuai browse reset icon */
        }

        /* ════════════════════════════════════════════════════════
           ✅ DOCUMENT GRID & CARD - SESUAI BROWSE PAGE
           ════════════════════════════════════════════════════════ */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(288px, 1fr));
            /* ✅ Sama dengan browse */
            gap: 18px;
            /* ✅ Sama dengan browse */
            position: relative;
            z-index: 1;
        }

        .documents-grid.list-view {
            grid-template-columns: 1fr !important;
            gap: 12px;
            /* ✅ Sama dengan browse list-mode */
        }

        /* DOCUMENT CARD - STYLE SESUAI BROWSE */
        .doc-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            box-shadow: var(--s-sm);
            /* ✅ Sesuai browse */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .22s cubic-bezier(.22, .68, 0, 1.2), box-shadow .22s, border-color .22s;
            /* ✅ Sesuai browse */
            cursor: pointer;
            animation: cardFadeIn .35s ease backwards;
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
        }

        .doc-card:nth-child(1) {
            animation-delay: .05s;
        }

        /* ✅ Sesuai browse */
        .doc-card:nth-child(2) {
            animation-delay: .1s;
        }

        /* ✅ Sesuai browse */
        .doc-card:nth-child(3) {
            animation-delay: .15s;
        }

        /* ✅ Sesuai browse */
        .doc-card:nth-child(4) {
            animation-delay: .2s;
        }

        /* ✅ Sesuai browse */
        .doc-card:nth-child(5) {
            animation-delay: .25s;
        }

        /* ✅ Sesuai browse */
        .doc-card:nth-child(6) {
            animation-delay: .3s;
        }

        /* ✅ Sesuai browse */

        @keyframes cardFadeIn {

            /* ✅ Sesuai browse */
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .doc-card:hover {
            transform: translateY(-5px);
            /* ✅ Sesuai browse */
            box-shadow: var(--s-lg);
            /* ✅ Sesuai browse */
            border-color: rgba(26, 86, 214, .3);
            /* ✅ Sesuai browse */
            z-index: 3;
            /* ✅ Sesuai browse */
        }

        /* Card Thumbnail - SESUAI BROWSE */
        .card-thumbnail {
            height: 140px;
            /* ✅ Sesuai browse */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .thumbnail-icon {
            font-size: 42px !important;
            /* ✅ Sesuai browse */
            color: rgba(255, 255, 255, .88);
            /* ✅ Sesuai browse */
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 3px 8px rgba(0, 0, 0, .2));
            /* ✅ Sesuai browse */
            transition: transform .25s;
            /* ✅ Sesuai browse */
        }

        .doc-card:hover .thumbnail-icon {
            transform: scale(1.1) translateY(-3px);
            /* ✅ Sesuai browse */
        }

        .file-type-badge {
            position: absolute;
            top: 10px;
            /* ✅ Sesuai browse */
            right: 10px;
            /* ✅ Sesuai browse */
            background: rgba(255, 255, 255, .18);
            /* ✅ Sesuai browse */
            border: 1px solid rgba(255, 255, 255, .25);
            /* ✅ Sesuai browse */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            padding: 3px 9px;
            /* ✅ Sesuai browse */
            font-size: 9.5px;
            /* ✅ Sesuai browse */
            color: #fff;
            letter-spacing: .8px;
            /* ✅ Sesuai browse */
            text-transform: uppercase;
            z-index: 3;
            backdrop-filter: blur(4px);
            /* ✅ Sesuai browse */
        }

        /* Thumbnail Gradients - SESUAI BROWSE */
        .thumb-grad-0 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #1a3fa8 0%, #1a56d6 60%, #3b82f6 100%) !important;
        }

        .thumb-grad-1 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #0f4c75 0%, #0ea5e9 100%) !important;
        }

        .thumb-grad-2 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #134e3a 0%, #14b8a6 100%) !important;
        }

        .thumb-grad-3 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #3b0764 0%, #6366f1 100%) !important;
        }

        .thumb-grad-4 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #7c2d12 0%, #f59e0b 100%) !important;
        }

        .thumb-grad-5 {
            /* ✅ Rename sesuai browse */
            background: linear-gradient(135deg, #881337 0%, #f43f5e 100%) !important;
        }

        /* Fallback untuk compatibility */
        .thumb-blue {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }

        .thumb-cyan {
            background: linear-gradient(135deg, #0e7490 0%, #06b6d4 50%, #22d3ee 100%);
        }

        .thumb-teal {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);
        }

        .thumb-indigo {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 50%, #818cf8 100%);
        }

        .thumb-purple {
            background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 50%, #c4b5fd 100%);
        }

        .thumb-rose {
            background: linear-gradient(135deg, #be123c 0%, #f43f5e 50%, #fb7185 100%);
        }

        .thumb-orange {
            background: linear-gradient(135deg, #c2410c 0%, #f97316 50%, #fb923c 100%);
        }

        /* Card Body - SESUAI BROWSE */
        .card-body {
            padding: 16px;
            /* ✅ Sesuai browse */
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
            /* ✅ Sesuai browse */
        }

        .card-title {
            font-family: var(--font-d);
            font-size: 13.5px;
            /* ✅ Sesuai browse */
            font-weight: 600;
            color: var(--t1);
            line-height: 1.35;
            /* ✅ Sesuai browse */
            flex: 1;
            padding-right: 8px;
            /* ✅ Sesuai browse */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 0;
            letter-spacing: -.1px;
            /* ✅ Sesuai browse */
        }

        .card-badges {
            display: flex;
            gap: 4px;
            /* ✅ Sesuai browse */
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .status-badge {
            font-size: 9px;
            /* ✅ Sesuai browse */
            font-weight: 700;
            padding: 3px 8px;
            /* ✅ Sesuai browse */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            text-transform: uppercase;
            letter-spacing: .5px;
            /* ✅ Sesuai browse */
            white-space: nowrap;
        }

        /* Badge Colors - SESUAI BROWSE */
        .badge-success {
            background: #dcfce7;
            /* ✅ Sesuai browse */
            color: #15803d;
            /* ✅ Sesuai browse */
        }

        .badge-info {
            background: #e0f2fe;
            /* ✅ Sesuai browse */
            color: #0369a1;
            /* ✅ Sesuai browse */
        }

        .badge-warning {
            background: #fef9c3;
            /* ✅ Sesuai browse */
            color: #a16207;
            /* ✅ Sesuai browse */
        }

        .badge-danger {
            background: #fee2e2;
            /* ✅ Sesuai browse */
            color: #b91c1c;
            /* ✅ Sesuai browse */
        }

        .badge-default {
            background: #f1f5f9;
            /* ✅ Sesuai browse (gray) */
            color: #64748b;
            /* ✅ Sesuai browse */
        }

        .card-description {
            font-size: 11.5px;
            /* ✅ Sesuai browse */
            color: var(--t2);
            line-height: 1.5;
            /* ✅ Sesuai browse */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            margin: 0;
            margin-bottom: 10px;
            /* ✅ Sesuai browse */
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            /* ✅ Sesuai browse */
            border-top: 1px solid var(--border);
            gap: 10px;
        }

        .uploader-info {
            display: flex;
            align-items: center;
            gap: 7px;
            /* ✅ Sesuai browse */
            flex: 1;
            min-width: 0;
        }

        .avatar-circle {
            width: 24px;
            /* ✅ Sesuai browse */
            height: 24px;
            /* ✅ Sesuai browse */
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 9px;
            /* ✅ Sesuai browse */
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
            /* ✅ Sesuai browse */
        }

        .uploader-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
            /* ✅ Sesuai browse */
            min-width: 0;
        }

        .uploader-name {
            font-size: 11px;
            /* ✅ Sesuai browse */
            font-weight: 600;
            color: var(--t1);
            line-height: 1;
            /* ✅ Sesuai browse */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .upload-date {
            font-size: 10px;
            /* ✅ Sesuai browse */
            color: var(--t3);
            margin-top: 1px;
            /* ✅ Sesuai browse */
        }

        .card-actions {
            display: flex;
            gap: 5px;
            /* ✅ Sesuai browse */
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            /* ✅ Sesuai browse */
        }

        .action-btn {
            width: 29px;
            /* ✅ Sesuai browse act */
            height: 29px;
            /* ✅ Sesuai browse act */
            border-radius: 7px;
            /* ✅ Sesuai browse act */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12.5px !important;
            /* ✅ Sesuai browse act */
            cursor: pointer;
            border: none;
            transition: all .18s;
            /* ✅ Sesuai browse act */
            text-decoration: none;
            position: relative;
            z-index: 15;
            /* ✅ Sesuai browse */
        }

        .action-btn-primary {
            background: var(--blue);
            /* ✅ Sesuai browse act-view */
            color: #fff;
        }

        .action-btn-primary:hover {
            background: #1240b5;
            /* ✅ Sesuai browse act-view hover */
            transform: scale(1.08);
            /* ✅ Sesuai browse */
            box-shadow: 0 4px 10px rgba(26, 86, 214, .3);
            /* ✅ Sesuai browse */
        }

        .action-btn-primary i {
            font-size: 13px !important;
            /* ✅ Sesuai browse */
        }

        .action-btn-secondary {
            background: transparent;
            /* ✅ Sesuai browse act-dl */
            color: var(--blue);
            border: 1px solid var(--border);
            /* ✅ Sesuai browse act-dl */
        }

        .action-btn-secondary:hover {
            background: var(--blue);
            /* ✅ Sesuai browse act-dl hover */
            color: #fff;
            border-color: var(--blue);
        }

        .action-btn-secondary i {
            font-size: 13px !important;
            /* ✅ Sesuai browse */
        }

        /* Empty State - SESUAI BROWSE */
        .empty-state-container {
            grid-column: 1 / -1;
            background: var(--card);
            border: 1.5px dashed var(--border);
            /* ✅ Sesuai browse */
            border-radius: var(--r-xl);
            /* ✅ Sesuai browse */
            padding: 60px 32px;
            /* ✅ Sesuai browse */
            text-align: center;
        }

        .empty-icon-wrapper {
            width: 72px;
            /* ✅ Sesuai browse */
            height: 72px;
            /* ✅ Sesuai browse */
            border-radius: 20px;
            /* ✅ Sesuai browse */
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px !important;
            /* ✅ Sesuai browse */
            color: #fff;
            margin: 0 auto 20px;
            /* ✅ Sesuai browse */
            box-shadow: 0 8px 24px rgba(26, 86, 214, .25);
            /* ✅ Sesuai browse */
        }

        .empty-title {
            font-family: var(--font-d);
            font-size: 18px;
            /* ✅ Sesuai browse */
            color: var(--t1);
            font-weight: 600;
            margin-bottom: 8px;
            /* ✅ Sesuai browse */
        }

        .empty-description {
            font-size: 13px;
            /* ✅ Sesuai browse */
            color: var(--t2);
            margin-bottom: 24px;
            /* ✅ Sesuai browse */
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Pagination - SESUAI BROWSE FOOTER STYLE */
        .pagination-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            /* ✅ Sesuai browse footer */
            padding: 16px 20px;
            /* ✅ Sesuai browse footer */
            background: var(--card);
            border-radius: var(--r-md);
            /* ✅ Sesuai browse footer */
            border: 1px solid var(--border);
            font-size: 12.5px;
            /* ✅ Sesuai browse footer */
            color: var(--t3);
            flex-wrap: wrap;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            color: var(--t2);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            font-family: var(--font-b);
            text-decoration: none;
        }

        .page-btn:hover:not(:disabled):not(.active) {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, .04);
        }

        .page-btn.active {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 3px 12px rgba(26, 86, 214, .3);
            font-weight: 700;
        }

        .page-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .page-info-text {
            font-size: 12px;
            color: var(--t3);
            font-weight: 500;
        }

        /* Footer - SESUAI BROWSE */
        .site-footer {
            text-align: center;
            margin-top: 32px;
            /* ✅ Sesuai browse */
            padding: 16px 20px;
            /* ✅ Sesuai browse */
            background: var(--card);
            border-radius: var(--r-md);
            /* ✅ Sesuai browse */
            border: 1px solid var(--border);
            font-size: 12.5px;
            /* ✅ Sesuai browse */
            color: var(--t3);
            position: relative;
            z-index: 1;
        }

        /* Responsive - SESUAI BROWSE */
        @media (max-width: 768px) {
            .br-hero {
                padding: 28px 24px;
                /* ✅ Sesuai browse */
            }

            .br-hero h2 {
                font-size: 20px;
                /* ✅ Sesuai browse */
            }

            .search-card {
                padding: 16px 18px;
                /* ✅ Sesuai browse */
            }

            .documents-grid {
                grid-template-columns: 1fr;
                /* ✅ Sesuai browse */
            }

            .documents-grid.list-view .doc-card {
                flex-direction: row;
                max-height: 140px;
                /* ✅ Sesuai browse */
            }

            .documents-grid.list-view .card-thumbnail {
                width: 160px;
                /* ✅ Sesuai browse */
                min-width: 160px;
                /* ✅ Sesuai browse */
                height: auto;
                min-height: 140px;
                /* ✅ Sesuai browse */
                border-radius: var(--r-lg) 0 0 var(--r-lg);
                /* ✅ Sesuai browse */
            }

            .documents-grid.list-view .card-body {
                padding: 14px 18px;
                /* ✅ Sesuai browse */
            }

            .documents-grid.list-view .card-description {
                -webkit-line-clamp: 1;
                /* ✅ Sesuai browse */
            }

            .search-input-group {
                flex-direction: column !important;
                /* ✅ Sesuai browse */
            }

            .search-btn-primary {
                width: 100% !important;
                /* ✅ Sesuai browse */
                justify-content: center !important;
                /* ✅ Sesuai browse */
            }
        }

        @media (max-width: 480px) {
            .br-hero-chips {
                display: none !important;
                /* ✅ Sesuai browse */
            }

            .search-card {
                padding: 14px 16px;
                /* ✅ Sesuai browse */
            }

            .results-header {
                flex-direction: column;
                align-items: stretch !important;
                /* ✅ Sesuai browse */
            }

            .results-toolbar {
                justify-content: flex-end;
                /* ✅ Sesuai browse */
            }
        }

        /* ═══ NAVBAR LOGO ALIGNMENT FIX ═══ */
        .navbar-brand,
        [class*="navbar"] img,
        [class*="nav-brand"] img,
        .brand-logo,
        .logo-img {
            transform: translateY(-2px) !important;
        }

        .navbar-brand-wrapper,
        .brand-container {
            align-items: center !important;
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

<body data-search-detail-endpoint="{{ route('search.get-detail') }}">

    <!-- Background Orbs -->
    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="br-page">

        <!-- Hero Section -->
        <section class="br-hero">
            <div class="br-hero-eyebrow">
                <i class="bi bi-search"></i>
                <span>PENCARIAN DOKUMEN</span>
            </div>
            <h2>Temukan Dokumen Akademik</h2>
            <p>Cari dan temukan dokumen akademik yang Anda butuhkan di repositori SIPORA Politeknik Negeri Jember.</p>
            <div class="br-hero-chips">
                <span class="br-chip"><i class="bi bi-file-earmark-pdf"></i> PDF, DOC, PPT</span>
                <span class="br-chip"><i class="bi bi-lightning-fill"></i> Cepat & Tepat</span>
                <span class="br-chip"><i class="bi bi-shield-check"></i> Terverifikasi</span>
            </div>
        </section>

        <!-- Search Section -->
        <section class="search-section">
            <div class="search-card">
                <!-- Search Input - Fully Functional -->
                <form id="searchForm" method="GET" action="{{ route('search.index') }}" style="margin:0;">
                    <div class="search-input-group">
                        <div class="search-input-wrapper">
                            <div class="search-icon-inner">
                                <i class="bi bi-search"></i>
                            </div>
                            <input type="text" name="q" id="searchInput" class="search-input-field"
                                placeholder="Cari dokumen, penulis, kata kunci..." value="{{ $search_query }}"
                                autocomplete="off">
                            <button type="button" class="clear-input-btn" id="clearInputBtn"
                                onclick="clearSearchInput()">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            <!-- Search Suggestions Panel -->
                            <div class="search-suggestions-panel" id="searchSuggestionsPanel">
                                <div class="suggestions-header">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    <span>Saran Pencarian</span>
                                </div>
                                <div class="suggestion-item" onclick="applySuggestion('machine learning')">
                                    <div class="suggestion-icon">
                                        <i class="bi bi-cpu"></i>
                                    </div>
                                    <div class="suggestion-text">
                                        <div class="suggestion-title">Machine Learning</div>
                                        <div class="suggestion-meta">Teknologi AI & Data Science</div>
                                    </div>
                                    <i class="bi bi-arrow-right suggestion-arrow"></i>
                                </div>
                                <div class="suggestion-item" onclick="applySuggestion('sistem informasi')">
                                    <div class="suggestion-icon">
                                        <i class="bi bi-diagram-3"></i>
                                    </div>
                                    <div class="suggestion-text">
                                        <div class="suggestion-title">Sistem Informasi</div>
                                        <div class="suggestion-meta">Manajemen & Teknologi</div>
                                    </div>
                                    <i class="bi bi-arrow-right suggestion-arrow"></i>
                                </div>
                                <div class="suggestion-item" onclick="applySuggestion('pemrograman web')">
                                    <div class="suggestion-icon">
                                        <i class="bi bi-globe"></i>
                                    </div>
                                    <div class="suggestion-text">
                                        <div class="suggestion-title">Pemrograman Web</div>
                                        <div class="suggestion-meta">Development & Framework</div>
                                    </div>
                                    <i class="bi bi-arrow-right suggestion-arrow"></i>
                                </div>
                                <div class="suggestion-item" onclick="applySuggestion('jaringan komputer')">
                                    <div class="suggestion-icon">
                                        <i class="bi bi-hdd-network"></i>
                                    </div>
                                    <div class="suggestion-text">
                                        <div class="suggestion-title">Jaringan Komputer</div>
                                        <div class="suggestion-meta">Network & Security</div>
                                    </div>
                                    <i class="bi bi-arrow-right suggestion-arrow"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Voice Search Button -->
                        <button type="button" class="voice-search-btn" id="voiceSearchBtn"
                            onclick="toggleVoiceSearch()" title="Cari dengan Suara">
                            <i class="bi bi-mic"></i>
                        </button>

                        <!-- Primary Search Button -->
                        <button type="submit" class="search-btn-primary" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        @if (empty($search_query))
            <!-- Popular Keywords Section -->
            <section class="keywords-section">
                <div class="keywords-header">
                    <i class="bi bi-fire keywords-icon"></i>
                    <h3>Kata Kunci Populer</h3>
                </div>
                <div class="keywords-list">
                    @foreach ($popular_keywords as $keyword)
                        <a href="{{ route('search.index', ['q' => $keyword]) }}" class="keyword-item"
                            data-keyword="{{ $keyword }}"
                            onclick="handleKeywordClick('{{ $keyword }}', event)">
                            <i class="bi bi-tag-fill"></i>
                            <span>{{ $keyword }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if (!empty($search_query))
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-info">
                    <h4><i class="bi bi-search"></i> Hasil Pencarian</h4>
                    <p class="results-count">Ditemukan <strong>{{ count($results) }}</strong> dokumen</p>
                </div>
                <div class="results-toolbar">
                    <div class="sort-wrapper">
                        <span class="sort-label">Urutkan:</span>
                        <select class="sort-dropdown" onchange="handleSortChange(this)">
                            @php
                                $sortOptions = collect($sort_options ?? []);
                            @endphp
                            @foreach($sortOptions as $sort)
                                <option value="{{ $sort->sort_key }}" {{ (string) ($current_sort ?? request('sort', 'relevance')) === (string) $sort->sort_key ? 'selected' : '' }}>
                                    {{ $sort->sort_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="view-toggle">
                        <button type="button" class="view-btn active" onclick="setViewMode('grid')"
                            title="Tampilan Grid">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button type="button" class="view-btn" onclick="setViewMode('list')" title="Tampilan List">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Active Filters Bar --}}
            @if (request()->hasAny(['type', 'verified']))
                <div class="active-filters-bar">
                    @if (request('type'))
                        <span class="filter-chip-active">
                            Tipe: {{ strtoupper(request('type')) }}
                            <a href="#" onclick="removeFilter('type', event)">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                    @if (request('verified'))
                        <span class="filter-chip-active">
                            Verified
                            <a href="#" onclick="removeFilter('verified', event)">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                    <a href="#" class="clear-all-filters" onclick="clearAllFilters(event)">
                        <i class="bi bi-trash3"></i>
                        Hapus Semua
                    </a>
                </div>
            @endif

            @if (count($results) === 0)
                <!-- Empty State -->
                <div class="documents-grid">
                    <div class="empty-state-container">
                        <div class="empty-icon-wrapper">
                            <i class="bi bi-emoji-frown"></i>
                        </div>
                        <h3 class="empty-title">Tidak Ada Hasil</h3>
                        <p class="empty-description">Tidak ada dokumen ditemukan untuk
                            "<strong>{{ $search_query }}</strong>". Coba gunakan kata kunci lain.</p>
                    </div>
                </div>
            @else
                <!-- Documents Grid -->
                <div class="documents-grid" id="documentsGrid">
                    @php
                        $gradients = [
                            'thumb-grad-0',
                            'thumb-grad-1',
                            'thumb-grad-2',
                            'thumb-grad-3',
                            'thumb-grad-4',
                            'thumb-grad-5',
                        ];
                    @endphp

                    @foreach ($results as $index => $doc)
                        @php
                            $fileExt = strtolower($doc['file_type'] ?? '');
                            $gradientClass = $gradients[$index % count($gradients)];
                            $iconClass = 'bi-file-earmark-text';
                            if ($fileExt === 'pdf') {
                                $iconClass = 'bi-file-earmark-pdf';
                            } elseif (in_array($fileExt, ['doc', 'docx'])) {
                                $iconClass = 'bi-file-earmark-word';
                            } elseif (in_array($fileExt, ['ppt', 'pptx'])) {
                                $iconClass = 'bi-file-earmark-ppt';
                            }
                        @endphp

                        <article class="doc-card" data-id="{{ $doc['dokumen_id'] }}">
                            <div class="card-thumbnail {{ $gradientClass }}">
                                <i class="bi {{ $iconClass }} thumbnail-icon"></i>
                                <span class="file-type-badge">{{ strtoupper($fileExt) ?: 'FILE' }}</span>
                            </div>
                            <div class="card-body">
                                <div class="card-header-row">
                                    <h6 class="card-title">{{ $doc['judul'] }}</h6>
                                    <div class="card-badges">
                                        @if (!empty($doc['status_name']))
                                            <span class="status-badge badge-info">{{ $doc['status_name'] }}</span>
                                        @endif
                                        @if (!empty($doc['turnitin']) && is_numeric($doc['turnitin']) && $doc['turnitin'] > 0)
                                            <span class="status-badge badge-info">T:{{ $doc['turnitin'] }}%</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="card-description">
                                    {{ \Illuminate\Support\Str::limit($doc['abstrak'] ?? '-', 150) }}</p>
                                <div class="card-footer">
                                    <div class="uploader-info">
                                        <div class="avatar-circle">
                                            {{ mb_strtoupper(mb_substr($doc['uploader_name'] ?? 'A', 0, 1)) }}</div>
                                        <div class="uploader-details">
                                            <span class="uploader-name">{{ $doc['uploader_name'] ?? '-' }}</span>
                                            <span
                                                class="upload-date">{{ \Carbon\Carbon::parse($doc['tgl_unggah'] ?? 'now')->format('d M y') }}</span>
                                        </div>
                                    </div>
                                    <div class="card-actions">
                                        <button type="button" class="action-btn action-btn-primary">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if (isset($pagination) && $pagination->lastPage() > 1)
                    <nav class="pagination-nav">
                        @if ($pagination->currentPage() > 1)
                            <button class="page-btn" data-page="{{ $pagination->previousPageUrl() }}"
                                onclick="goToPage({{ $pagination->currentPage() - 1 }}, event)">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        @endif

                        @for ($i = 1; $i <= $pagination->lastPage(); $i++)
                            @if (
                                $i == $pagination->currentPage() ||
                                    abs($i - $pagination->currentPage()) <= 2 ||
                                    $i == 1 ||
                                    $i == $pagination->lastPage())
                                <button class="page-btn {{ $i == $pagination->currentPage() ? 'active' : '' }}"
                                    data-page="{{ $i }}" onclick="goToPage({{ $i }}, event)">
                                    {{ $i }}
                                </button>
                            @elseif(abs($i - $pagination->currentPage()) == 3)
                                <span class="page-info-text">...</span>
                            @endif
                        @endfor

                        @if ($pagination->currentPage() < $pagination->lastPage())
                            <button class="page-btn" data-page="{{ $pagination->nextPageUrl() }}"
                                onclick="goToPage({{ $pagination->currentPage() + 1 }}, event)">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        @endif

                        <span class="page-info-text">
                            Halaman {{ $pagination->currentPage() }} dari {{ $pagination->lastPage() }}
                        </span>
                    </nav>
                @endif
            @endif
        @endif
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        &copy; {{ date('Y') }} SIPORA — Sistem Informasi Politeknik Negeri Jember Repository Assets
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== GLOBAL STATE =====
        let currentDocId = null;
        let isVoiceActive = false;
        let searchTimeout = null;
        let recognition = null;
        let isListening = false;

        // ===== QUICK FILTER APPLICATION (FULLY FUNCTIONAL) =====
        function applyQuickFilter(filterType, value) {
            const form = document.getElementById('searchForm');
            if (!form) return;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = filterType;
            input.value = value;
            form.appendChild(input);

            form.submit();
        }

        // ===== VIEW MODE SWITCHER =====
        function setViewMode(mode) {
            const grid = document.getElementById('documentsGrid');
            const buttons = document.querySelectorAll('.view-btn');
            if (!grid) return;

            if (mode === 'list') {
                grid.classList.add('list-view');
                if (buttons[1]) buttons[1].classList.add('active');
                if (buttons[0]) buttons[0].classList.remove('active');
            } else {
                grid.classList.remove('list-view');
                if (buttons[0]) buttons[0].classList.add('active');
                if (buttons[1]) buttons[1].classList.remove('active');
            }

            localStorage.setItem('sipora_view_mode', mode);
        }

        // ===== CLEAR INPUT FUNCTION =====
        function clearSearchInput() {
            const input = document.getElementById('searchInput');
            if (input) {
                input.value = '';
                input.focus();
                updateClearButtonVisibility();
                hideSuggestions();

                window.location.href = '{{ route('search.index') }}';
            }
        }

        // ===== UPDATE CLEAR BUTTON VISIBILITY =====
        function updateClearButtonVisibility() {
            const input = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearInputBtn');
            if (input && clearBtn) {
                if (input.value.length > 0) {
                    clearBtn.classList.add('visible');
                } else {
                    clearBtn.classList.remove('visible');
                }
            }
        }

        // ===== VOICE SEARCH =====
        function toggleVoiceSearch() {
            const btn = document.getElementById('voiceSearchBtn');
            const input = document.getElementById('searchInput');
            if (!btn || !input) return;

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                alert('Maaf, browser Anda tidak mendukung fitur Voice Search. Silakan gunakan Chrome atau Edge.');
                return;
            }

            if (!isListening) {
                try {
                    recognition = new SpeechRecognition();
                    recognition.lang = 'id-ID';
                    recognition.continuous = false;
                    recognition.interimResults = true;
                    recognition.maxAlternatives = 1;

                    recognition.onstart = function() {
                        isListening = true;
                        isVoiceActive = true;
                        btn.classList.add('active');
                        btn.innerHTML = '<i class="bi bi-mic-fill"></i>';
                        btn.title = 'Mengambil suara... Klik untuk berhenti';
                        console.log('[🎤] Voice search started...');
                    };

                    recognition.onresult = function(event) {
                        let finalTranscript = '';

                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            const transcript = event.results[i][0].transcript;
                            if (event.results[i].isFinal) {
                                finalTranscript += transcript;
                            }
                        }

                        if (finalTranscript) {
                            input.value = finalTranscript;
                            updateClearButtonVisibility();
                            hideSuggestions();

                            setTimeout(() => {
                                submitSearchForm();
                            }, 500);
                        }
                    };

                    recognition.onerror = function(event) {
                        console.error('[❌] Speech recognition error:', event.error);
                        stopVoiceSearch();

                        let errorMsg = 'Terjadi kesalahan saat mengambil suara.';
                        if (event.error === 'no-speech') {
                            errorMsg = 'Tidak terdeteksi suara. Silakan coba lagi.';
                        } else if (event.error === 'audio-capture') {
                            errorMsg = 'Tidak ditemukan mikrofon. Pastikan mikrofon terhubung.';
                        } else if (event.error === 'not-allowed') {
                            errorMsg = 'Izin mikrofon ditolak. Silakan izinkan akses mikrofon.';
                        }

                        alert(errorMsg);
                    };

                    recognition.onend = function() {
                        stopVoiceSearch();
                    };

                    recognition.start();

                } catch (error) {
                    console.error('[❌] Error starting speech recognition:', error);
                    stopVoiceSearch();
                }
            } else {
                stopVoiceSearch();
            }
        }

        function stopVoiceSearch() {
            if (recognition) {
                try {
                    recognition.stop();
                } catch (e) {}
            }

            isListening = false;
            isVoiceActive = false;

            const btn = document.getElementById('voiceSearchBtn');
            if (btn) {
                btn.classList.remove('active');
                btn.innerHTML = '<i class="bi bi-mic"></i>';
                btn.title = 'Cari dengan Suara';
            }

            console.log('[🎤] Voice search stopped');
        }

        // ===== SHOW/HIDE SUGGESTIONS PANEL =====
        function showSuggestions() {
            const panel = document.getElementById('searchSuggestionsPanel');
            if (panel) {
                panel.classList.add('show');
            }
        }

        function hideSuggestions() {
            const panel = document.getElementById('searchSuggestionsPanel');
            if (panel) {
                panel.classList.remove('show');
            }
        }

        // ===== APPLY SUGGESTION =====
        function applySuggestion(text) {
            const input = document.getElementById('searchInput');
            if (input) {
                input.value = text;
                updateClearButtonVisibility();
                hideSuggestions();

                submitSearchForm();
            }
        }

        // ===== SUBMIT SEARCH FORM =====
        function submitSearchForm() {
            const form = document.getElementById('searchForm');
            const input = document.getElementById('searchInput');

            if (!form || !input) return;

            const query = input.value.trim();

            if (query === '') {
                window.location.href = '{{ route('search.index') }}';
                return;
            }

            form.submit();
        }

        // ===== KEYWORD CLICK HANDLER =====
        function handleKeywordClick(keyword, event) {
            event.preventDefault();

            const input = document.getElementById('searchInput');
            if (input) {
                input.value = keyword;
                updateClearButtonVisibility();

                submitSearchForm();
            }
        }

        // ===== SORT CHANGE HANDLER =====
        function handleSortChange(selectElement) {
            const form = document.getElementById('searchForm');
            if (!form) return;

            const sortInput = document.createElement('input');
            sortInput.type = 'hidden';
            sortInput.name = 'sort';
            sortInput.value = selectElement.value;
            form.appendChild(sortInput);

            form.submit();
        }

        // ===== PAGINATION HANDLER =====
        function goToPage(page, event) {
            if (event) {
                event.preventDefault();
            }

            const form = document.getElementById('searchForm');
            if (!form) return;

            const pageInput = document.createElement('input');
            pageInput.type = 'hidden';
            pageInput.name = 'page';
            pageInput.value = page;
            form.appendChild(pageInput);

            form.submit();
        }

        // ===== REMOVE ACTIVE FILTER =====
        function removeFilter(filterType, event) {
            event.preventDefault();

            const form = document.getElementById('searchForm');
            if (!form) return;

            const removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_' + filterType;
            removeInput.value = '1';
            form.appendChild(removeInput);

            form.submit();
        }

        // ===== CLEAR ALL FILTERS =====
        function clearAllFilters(event) {
            event.preventDefault();

            const input = document.getElementById('searchInput');
            const currentQuery = input ? input.value : '';

            if (currentQuery) {
                window.location.href = '{{ route('search.index') }}?q=' + encodeURIComponent(currentQuery);
            } else {
                window.location.href = '{{ route('search.index') }}';
            }
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[✓] SIPORA Search Page Fully Initialized');

            const searchInput = document.getElementById('searchInput');

            if (searchInput) {
                updateClearButtonVisibility();

                searchInput.addEventListener('input', function() {
                    updateClearButtonVisibility();

                    if (this.value.length > 0) {
                        showSuggestions();
                    } else {
                        hideSuggestions();
                    }
                });

                searchInput.addEventListener('focus', function() {
                    if (this.value.length > 0 || true) {
                        showSuggestions();
                    }
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        hideSuggestions();
                        submitSearchForm();
                    }
                });
            }

            document.addEventListener('click', function(e) {
                const searchWrapper = document.querySelector('.search-input-wrapper');
                const panel = document.getElementById('searchSuggestionsPanel');
                const keywordsSection = document.querySelector('.keywords-section');

                const clickedOutsideSearch = searchWrapper && !searchWrapper.contains(e.target);
                const clickedOutsideKeywords = keywordsSection && !keywordsSection.contains(e.target);

                if (panel && clickedOutsideSearch && clickedOutsideKeywords) {
                    hideSuggestions();
                }
            });

            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }

                if (e.key === 'Escape') {
                    hideSuggestions();
                    if (document.activeElement === searchInput) {
                        if (searchInput.value === '') {
                            searchInput.blur();
                        }
                    }
                }
            });

            const keywordItems = document.querySelectorAll('.keyword-item');
            keywordItems.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    const keyword = this.getAttribute('data-keyword') || this.textContent.trim();
                    handleKeywordClick(keyword, event);
                });
            });

            const sortDropdown = document.querySelector('.sort-dropdown');
            if (sortDropdown) {
                sortDropdown.addEventListener('change', function() {
                    handleSortChange(this);
                });
            }

            const pageButtons = document.querySelectorAll('.page-btn[data-page]');
            pageButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    goToPage(page, e);
                });
            });

            const clearAllBtn = document.querySelector('.clear-all-filters');
            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', clearAllFilters);
            }

            const savedViewMode = localStorage.getItem('sipora_view_mode');
            if (savedViewMode) {
                setViewMode(savedViewMode);
            }

            console.log('[✓] All search features are now FULLY FUNCTIONAL!');
        });
    </script>
    @include('components.chatbot_widget')
    <script src="{{ asset('assets/js/smooth-transitions.js') }}"></script>
</body>

</html>
