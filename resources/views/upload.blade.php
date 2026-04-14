<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA - Repository Dokumen</title>
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

        /* ═══════════════════════════════════════════════════════════════
           ✅ CUSTOM SWEETALERT2 TOAST STYLING (COMPACT & SMALL)
           ═══════════════════════════════════════════════════════════════ */

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

        /* Upload Page */
        .upload-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            position: relative;
            z-index: 1;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(15, 23, 42, .09), 0 2px 4px rgba(15, 23, 42, .04);
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
            transition: all .25s ease;
            background: transparent;
            border: none;
            white-space: nowrap;
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

        /* Alerts */
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

        .up-alert ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .up-alert li {
            margin-bottom: 3px;
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

        /* Hero */
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
            max-width: 420px;
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

        /* Smart Upload Card */
        .smart-upload-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: box-shadow .3s ease;
        }

        .smart-upload-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .smart-card-header {
            background: linear-gradient(135deg, var(--blue) 0%, var(--indigo) 100%);
            padding: 24px 28px;
            position: relative;
            overflow: hidden;
        }

        .smart-header-content {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            z-index: 2;
        }

        .smart-header-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
        }

        .smart-header-text h3 {
            font-family: var(--font-d);
            font-size: 20px;
            color: #fff;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .smart-header-text p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .smart-progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 3;
        }

        .smart-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--sky), #fff, var(--sky));
            background-size: 200% 100%;
            border-radius: 2px;
            transition: width .5s cubic-bezier(.4, 0, .2, 1);
            animation: progressShimmer 2s linear infinite;
        }

        @keyframes progressShimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .smart-card-body {
            padding: 28px;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding: 0 8px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 40px;
            right: 40px;
            height: 2px;
            background: var(--border);
            z-index: 1;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            position: relative;
            z-index: 2;
            cursor: pointer;
            transition: all .3s;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--page);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-d);
            font-size: 14px;
            font-weight: 600;
            color: var(--t3);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .step-item.active .step-number {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 16px rgba(26, 86, 214, 0.3);
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .step-label {
            font-size: 11px;
            font-weight: 500;
            color: var(--t3);
            text-align: center;
            max-width: 70px;
        }

        .step-item.active .step-label {
            color: var(--blue);
            font-weight: 600;
        }

        .step-item.completed .step-label {
            color: var(--green);
        }

        /* Smart Section */
        .smart-section {
            margin-bottom: 32px;
            animation: sectionSlideIn .4s ease backwards;
        }

        .smart-section:nth-child(1) {
            animation-delay: 0.05s;
        }

        .smart-section:nth-child(2) {
            animation-delay: 0.1s;
        }

        .smart-section:nth-child(3) {
            animation-delay: 0.15s;
        }

        .smart-section:nth-child(4) {
            animation-delay: 0.2s;
        }

        @keyframes sectionSlideIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .smart-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .smart-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .smart-section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
        }

        .smart-section-icon.upload {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
        }

        .smart-section-icon.info {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
        }

        .smart-section-icon.classify {
            background: linear-gradient(135deg, var(--indigo), var(--sky));
        }

        .smart-section-icon.optional {
            background: linear-gradient(135deg, var(--amber), var(--green));
        }

        .smart-section-title span {
            font-family: var(--font-d);
            font-size: 15px;
            font-weight: 600;
            color: var(--t1);
        }

        .smart-section-badge {
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 20px;
            background: var(--page);
            color: var(--t3);
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-weight: 500;
            border: 1px solid var(--border);
        }

        .smart-section-badge.required {
            background: rgba(26, 86, 214, 0.08);
            color: var(--blue);
            border-color: rgba(26, 86, 214, 0.2);
        }

        /* Smart Grid & Form Elements */
        .smart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .smart-grid.full {
            grid-template-columns: 1fr;
        }

        .smart-field {
            position: relative;
        }

        .smart-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .smart-label-text {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--t2);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .smart-label-text .req {
            color: var(--rose);
            font-weight: 600;
        }

        .smart-char-count {
            font-size: 11px;
            color: var(--t3);
            background: var(--page);
            padding: 2px 8px;
            border-radius: 10px;
            transition: all .2s;
        }

        .smart-char-count.warning {
            color: var(--amber);
            background: rgba(245, 158, 11, 0.1);
        }

        .smart-char-count.danger {
            color: var(--rose);
            background: rgba(244, 63, 94, 0.1);
        }

        .smart-input,
        .smart-select,
        .smart-textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            font-family: var(--font-b);
            font-size: 13.5px;
            color: var(--t1);
            outline: none;
            background: #fff;
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
        }

        .smart-input:focus,
        .smart-select:focus,
        .smart-textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26, 86, 214, 0.08), 0 2px 8px rgba(26, 86, 214, 0.06);
            transform: translateY(-1px);
        }

        /* ✅ DISABLED STATE UNTUK PRODI SELECT */
        .smart-select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f8fafc;
        }

        .smart-select:disabled:hover {
            border-color: var(--border);
            box-shadow: none;
            transform: none;
        }

        .smart-input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: var(--t3);
            pointer-events: none;
            transition: all .2s;
        }

        .smart-input:focus~.smart-input-icon {
            color: var(--blue);
        }

        .smart-field.has-icon .smart-input {
            padding-right: 42px;
        }

        .smart-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M3 5l3 3 3-3' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }

        .smart-textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        .smart-hint {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: var(--t3);
            margin-top: 6px;
        }

        .smart-hint i {
            font-size: 12px;
        }

        /* Dropzone */
        .smart-dropzone-container {
            position: relative;
        }

        .smart-dropzone {
            border: 2.5px dashed var(--border);
            border-radius: 16px;
            padding: 48px 28px;
            text-align: center;
            cursor: pointer;
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }

        .smart-dropzone:hover,
        .smart-dropzone.drag-over {
            border-color: var(--blue);
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.04), rgba(30, 64, 175, 0.06));
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(26, 86, 214, 0.15);
        }

        .smart-dropzone.drag-over {
            border-style: solid;
            border-width: 3px;
        }

        .smart-dropzone.has-file {
            border-style: solid;
            border-color: var(--green);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.03), rgba(20, 184, 166, 0.03));
        }

        .smart-dropzone-icon {
            width: 80px;
            height: 80px;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.12), rgba(30, 64, 175, 0.12));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: var(--blue);
            margin: 0 auto 20px;
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            z-index: 2;
        }

        .smart-dropzone:hover .smart-dropzone-icon {
            transform: translateY(-4px) scale(1.05);
        }

        .smart-dropzone.has-file .smart-dropzone-icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.12), rgba(20, 184, 166, 0.12));
            color: var(--green);
        }

        .smart-dropzone h5 {
            font-family: var(--font-d);
            font-size: 16px;
            color: var(--t1);
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .smart-dropzone p {
            font-size: 13px;
            color: var(--t3);
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .smart-dropzone-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 12px;
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            font-size: 13.5px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(26, 86, 214, 0.3);
            transition: background-color .2s ease;
            position: relative;
            z-index: 2;
        }

        .smart-dropzone-formats {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .smart-format-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            padding: 5px 10px;
            border-radius: 8px;
            background: var(--card);
            color: var(--t3);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid var(--border);
            font-weight: 500;
        }

        .smart-format-tag:hover {
            border-color: var(--blue);
            color: var(--blue);
        }

        .smart-dropzone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        .file-size-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 12px;
            font-size: 11.5px;
            color: var(--t3);
            position: relative;
            z-index: 2;
        }

        /* File Preview */
        .smart-file-preview {
            display: none;
            align-items: center;
            gap: 16px;
            padding: 18px 22px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.04), rgba(20, 184, 166, 0.04));
            border: 1.5px solid rgba(34, 197, 94, 0.2);
            margin-top: 16px;
            animation: previewSlideIn .4s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
        }

        .smart-file-preview.show {
            display: flex;
        }

        .smart-file-preview::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--green), var(--teal));
        }

        @keyframes previewSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .smart-file-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--green), var(--teal));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            flex-shrink: 0;
        }

        .smart-file-info {
            flex: 1;
            min-width: 0;
        }

        .smart-file-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--t1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 3px;
        }

        .smart-file-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 11.5px;
            color: var(--t3);
        }

        .smart-file-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .smart-file-remove {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t3);
            font-size: 16px;
            transition: all .2s;
            flex-shrink: 0;
        }

        .smart-file-remove:hover {
            background: var(--rose);
            color: #fff;
            border-color: var(--rose);
        }

        /* Optional Toggle */
        .smart-optional-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.04), rgba(30, 64, 175, 0.04));
            border: 1.5px dashed rgba(26, 86, 214, 0.25);
            border-radius: 14px;
            cursor: pointer;
            transition: all .25s;
            margin-bottom: 18px;
        }

        .smart-optional-toggle:hover {
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.07), rgba(30, 64, 175, 0.07));
            border-color: rgba(26, 86, 214, 0.35);
        }

        .smart-optional-toggle-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .smart-optional-toggle-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            box-shadow: 0 4px 12px rgba(26, 86, 214, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
        }

        .smart-optional-toggle-text h4 {
            font-family: var(--font-d);
            font-size: 14px;
            color: var(--t1);
            margin-bottom: 2px;
        }

        .smart-optional-toggle-text p {
            font-size: 11.5px;
            color: var(--t3);
            margin: 0;
        }

        .smart-toggle-arrow {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--card);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--t3);
            font-size: 14px;
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .smart-optional-toggle.active .smart-toggle-arrow {
            transform: rotate(180deg);
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .smart-optional-content {
            display: none;
            padding: 20px;
            background: var(--page);
            border-radius: 14px;
            border: 1px solid var(--border);
            animation: optionalContentIn .3s ease;
        }

        .smart-optional-content.show {
            display: block;
        }

        @keyframes optionalContentIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .smart-input-group {
            display: flex;
            align-items: stretch;
        }

        .smart-input-group .smart-input {
            border-radius: 12px 0 0 12px;
        }

        .smart-input-suffix {
            display: flex;
            align-items: center;
            padding: 0 16px;
            background: var(--page);
            border: 1.5px solid var(--border);
            border-left: none;
            border-radius: 0 12px 12px 0;
            font-size: 13px;
            font-weight: 500;
            color: var(--t2);
            white-space: nowrap;
        }

        .smart-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 32px 0 24px;
        }

        .smart-divider::before,
        .smart-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .smart-divider-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--t3);
            white-space: nowrap;
            background: var(--card);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .smart-divider-text i {
            color: var(--blue);
        }

        .smart-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .smart-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-family: var(--font-b);
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .smart-btn-primary {
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            box-shadow: 0 6px 24px rgba(26, 86, 214, 0.3);
            min-width: 180px;
        }

        .smart-btn-primary:hover {
            background: linear-gradient(130deg, var(--indigo), var(--blue));
            box-shadow: 0 8px 32px rgba(26, 86, 214, 0.4);
        }

        .smart-btn-secondary {
            background: var(--card);
            color: var(--t2);
            border: 1.5px solid var(--border);
        }

        .smart-btn-secondary:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, 0.03);
        }

        .smart-btn-primary.is-loading {
            pointer-events: none;
            cursor: default;
        }

        .smart-btn-primary.is-loading .smart-btn-text {
            opacity: 0;
        }

        .smart-btn-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity .3s ease;
        }

        .smart-btn-primary.is-loading .smart-btn-loading {
            opacity: 1;
        }

        .smart-spinner {
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: smartSpin .7s linear infinite;
        }

        @keyframes smartSpin {
            to {
                transform: rotate(360deg);
            }
        }

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

        /* Prodi Loading Indicator */
        .prodi-loading-upload {
            display: none !important;
            align-items: center !important;
            gap: 6px !important;
            font-size: 11px !important;
            color: var(--blue) !important;
            padding: 6px 12px !important;
            background: rgba(26, 86, 214, 0.06) !important;
            border-radius: 8px !important;
            margin-top: 6px !important;
            width: fit-content !important;
        }

        .prodi-loading-upload.active {
            display: inline-flex !important;
        }

        .prodi-loading-upload i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .smart-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .smart-upload-card {
                border-radius: var(--r-lg);
            }

            .smart-card-header {
                padding: 20px 22px;
            }

            .smart-header-content {
                flex-direction: column;
                text-align: center;
            }

            .smart-header-icon {
                width: 48px;
                height: 48px;
                font-size: 22px;
            }

            .smart-header-text h3 {
                font-size: 17px;
            }

            .smart-card-body {
                padding: 20px;
            }

            .step-indicator {
                margin-bottom: 24px;
            }

            .step-number {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .step-label {
                font-size: 10px;
                max-width: 55px;
            }

            .smart-dropzone {
                padding: 36px 18px;
            }

            .smart-dropzone-icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }

            .smart-dropzone h5 {
                font-size: 14px;
            }

            .smart-dropzone-btn {
                padding: 10px 20px;
                font-size: 12.5px;
            }

            .smart-section {
                margin-bottom: 26px;
            }

            .smart-section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .smart-actions {
                flex-direction: column;
            }

            .smart-btn {
                width: 100%;
                justify-content: center;
            }

            .smart-optional-toggle {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .smart-optional-toggle-left {
                flex-direction: column;
            }

            .smart-file-preview {
                flex-wrap: wrap;
            }

            .smart-file-info {
                width: 100%;
            }

            .up-hero {
                padding: 28px 24px;
            }

            .up-hero h2 {
                font-size: 20px;
            }

            .tab-nav-container {
                gap: 2px;
                padding: 6px;
                overflow-x: auto;
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

<!--
✅ DATA ATTRIBUTES SUDAH SESUAI DENGAN ROUTE ANDA
Parameter: id_jurusan (sesuai dengan controller)
-->

<body data-old-prodi="{{ old('id_prodi') ?? '' }}" data-csrf-token="{{ csrf_token() }}">

    <!-- Background Orbs -->
    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>

    @include('components.navbar')

    <div class="upload-page">
        <!-- Hero -->
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
        <nav class="tab-navigation" role="navigation">
            <div class="tab-nav-container">
                <span class="tab-nav-item active"><i class="bi bi-cloud-arrow-up"></i><span
                        class="tab-text">Unggah</span></span>
                <a href="{{ route('documents.my') }}" class="tab-nav-item"><i class="bi bi-folder2-open"></i><span
                        class="tab-text">Dokumen Saya</span></a>
                <a href="{{ route('documents.history', ['date' => 'all']) }}" class="tab-nav-item"><i
                        class="bi bi-clock-history"></i><span class="tab-text">Riwayat Upload</span></a>
                <a href="{{ route('documents.turnitin', ['score' => 'all']) }}" class="tab-nav-item"><i
                        class="bi bi-patch-check"></i><span class="tab-text">Skor Turnitin</span></a>
                <div class="tab-nav-indicator"></div>
            </div>
        </nav>

        <!-- Alerts -->
        @if (session()->has('upload_success'))
            <div class="up-alert up-alert-success"><i class="bi bi-check-circle-fill"></i>
                <div><strong>Berhasil!</strong> Dokumen telah diunggah.</div>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="up-alert up-alert-danger"><i class="bi bi-exclamation-triangle-fill"></i>
                <div><strong>Error!</strong>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Smart Upload Card -->
        <div class="smart-upload-card">
            <!-- Header -->
            <div class="smart-card-header">
                <div class="smart-header-content">
                    <div class="smart-header-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                    <div class="smart-header-text">
                        <h3>Form Unggah Dokumen</h3>
                        <p>Lengkapi informasi dan unggah file Anda</p>
                    </div>
                </div>
                <div class="smart-progress-bar">
                    <div class="smart-progress-fill" id="formProgress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Body -->
            <div class="smart-card-body">
                <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step-item active" data-step="1">
                            <div class="step-number">1</div><span class="step-label">Upload File</span>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-number">2</div><span class="step-label">Info Dokumen</span>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-number">3</div><span class="step-label">Klasifikasi</span>
                        </div>
                        <div class="step-item" data-step="4">
                            <div class="step-number">4</div><span class="step-label">Selesai</span>
                        </div>
                    </div>

                    <!-- Step 1: Upload File -->
                    <div class="smart-section" data-section="upload">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon upload"><i class="bi bi-cloud-arrow-up"></i></div>
                                <span>Langkah 1: Upload File Dokumen</span>
                            </div>
                            <span class="smart-section-badge step required">Langkah 1 • Wajib</span>
                        </div>
                        <div class="smart-dropzone-container">
                            <div class="smart-dropzone" id="mainDropzone">
                                <input type="file" class="up-file-input" name="file_dokumen"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" required id="mainFileInput">
                                <div class="smart-dropzone-icon"><i class="bi bi-file-earmark-plus"></i></div>
                                <h5>Pilih atau Seret File ke Sini</h5>
                                <p>Klik area ini atau drag & drop file dokumen Anda</p>
                                <button type="button" class="smart-dropzone-btn"
                                    onclick="document.getElementById('mainFileInput').click()"><i
                                        class="bi bi-folder2-open"></i> Pilih File</button>
                                <div class="smart-dropzone-formats">
                                    <span class="smart-format-tag"><i class="bi bi-file-pdf"></i> PDF</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-word"></i> DOC</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-ppt"></i> PPT</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-excel"></i> XLS</span>
                                </div>
                            </div>
                            <div class="smart-file-preview" id="mainFilePreview">
                                <div class="smart-file-icon"><i class="bi bi-file-earmark-text" id="previewIcon"></i>
                                </div>
                                <div class="smart-file-info">
                                    <div class="smart-file-name" id="mainFileName">—</div>
                                    <div class="smart-file-meta">
                                        <span><i class="bi bi-hdd"></i> <span id="mainFileSize">—</span></span>
                                        <span><i class="bi bi-check-circle" style="color: var(--green);"></i> Siap
                                            diunggah</span>
                                    </div>
                                </div>
                                <button type="button" class="smart-file-remove" id="mainFileRemove"
                                    title="Hapus File"><i class="bi bi-x-lg"></i></button>
                            </div>
                            <div class="file-size-note"><i class="bi bi-info-circle"></i><span>Ukuran maksimal file:
                                    <strong>10MB</strong> • Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX</span></div>
                        </div>
                    </div>

                    <!-- Step 2: Info Dokumen -->
                    <div class="smart-section" data-section="info">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon info"><i class="bi bi-info-lg"></i></div>
                                <span>Langkah 2: Informasi Dokumen</span>
                            </div>
                            <span class="smart-section-badge step required">Langkah 2 • Wajib</span>
                        </div>
                        <div class="smart-grid">
                            <div class="smart-field full">
                                <label class="smart-label">
                                    <span class="smart-label-text">Judul Dokumen <span class="req">*</span></span>
                                    <span class="smart-char-count" id="judulCount">0/200</span>
                                </label>
                                <div class="smart-field has-icon">
                                    <input type="text" class="smart-input" name="judul" required
                                        id="inputJudul" value="{{ old('judul') }}"
                                        placeholder="Contoh: Implementasi Machine Learning..." maxlength="200">
                                    <i class="bi bi-type smart-input-icon"></i>
                                </div>
                                <div class="smart-hint"><i class="bi bi-lightbulb"></i> Gunakan judul yang jelas dan
                                    deskriptif</div>
                            </div>
                            <div class="smart-field">
                                <label class="smart-label"><span class="smart-label-text">Tahun Akademik <span
                                            class="req">*</span></span></label>
                                <select class="smart-select" name="year_id" required id="selectYear">
                                    <option value="">— Pilih Tahun —</option>
                                    @if (isset($tahun_data))
                                        @foreach ($tahun_data as $t)
                                            <option value="{{ $t->year_id }}" @selected(old('year_id') == $t->year_id)>
                                                {{ $t->tahun }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="smart-field">
                                <label class="smart-label"><span class="smart-label-text">Tema / Kategori <span
                                            class="req">*</span></span></label>
                                <select class="smart-select" name="id_tema" required id="selectTema">
                                    <option value="">— Pilih Tema —</option>
                                    @if (isset($tema_data))
                                        @foreach ($tema_data as $tm)
                                            <option value="{{ $tm->id_tema }}" @selected(old('id_tema') == $tm->id_tema)>
                                                {{ $tm->nama_tema }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="smart-field full">
                                <label class="smart-label">
                                    <span class="smart-label-text">Deskripsi / Abstrak <span
                                            class="req">*</span></span>
                                    <span class="smart-char-count" id="abstrakCount">0/500</span>
                                </label>
                                <textarea class="smart-textarea" name="abstrak" required id="textareaAbstrak"
                                    placeholder="Jelaskan secara singkat isi dokumen..." maxlength="500">{{ old('abstrak') }}</textarea>
                                <div class="smart-hint"><i class="bi bi-lightbulb"></i> Tulis ringkasan yang membantu
                                    orang memahami konten</div>
                            </div>
                            <div class="smart-field full">
                                <label class="smart-label">
                                    <span class="smart-label-text">Kata Kunci <span class="req">*</span></span>
                                    <span class="smart-char-count" id="keywordCount">0/100</span>
                                </label>
                                <div class="smart-field has-icon">
                                    <input type="text" class="smart-input" name="kata_kunci" required
                                        id="inputKeyword" value="{{ old('kata_kunci') }}"
                                        placeholder="Contoh: AI, data mining, python" maxlength="100">
                                    <i class="bi bi-tags smart-input-icon"></i>
                                </div>
                                <div class="smart-hint"><i class="bi bi-info-circle"></i> Pisahkan dengan koma (,)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                    ══════════════════════════════════════════════════
                    ✅ STEP 3: KLASIFIKASI (DYNAMIC JURUSAN-PRODI)
                    ══════════════════════════════════════════════════
                    -->
                    <div class="smart-section" data-section="classify">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon classify"><i class="bi bi-diagram-3"></i></div>
                                <span>Langkah 3: Klasifikasi Dokumen</span>
                            </div>
                            <span class="smart-section-badge step required">Langkah 3 • Wajib</span>
                        </div>
                        <div class="smart-grid">
                            <!-- Divisi -->
                            <div class="smart-field">
                                <label class="smart-label"><span class="smart-label-text">Divisi <span
                                            class="req">*</span></span></label>
                                <select class="smart-select" name="id_divisi" required id="selectDivisi">
                                    <option value="">— Pilih Divisi —</option>
                                    @if (isset($divisi_data))
                                        @foreach ($divisi_data as $d)
                                            <option value="{{ $d->id_divisi }}" @selected(old('id_divisi') == $d->id_divisi)>
                                                {{ $d->nama_divisi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- ✅ JURUSAN (TRIGGER) -->
                            <div class="smart-field">
                                <label class="smart-label"><span class="smart-label-text">Jurusan <span
                                            class="req">*</span></span></label>
                                <select class="smart-select" name="id_jurusan" required id="selectJurusan"
                                    onchange="handleJurusanChange(this.value)">
                                    <option value="">— Pilih Jurusan —</option>
                                    @if (isset($jurusan_data))
                                        @foreach ($jurusan_data as $j)
                                            <option value="{{ $j->id_jurusan }}" @selected(old('id_jurusan') == $j->id_jurusan)>
                                                {{ $j->nama_jurusan }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- ✅ PROGRAM STUDI (DYNAMIC) -->
                            <div class="smart-field">
                                <label class="smart-label"><span class="smart-label-text">Program Studi <span
                                            class="req">*</span></span></label>
                                <select class="smart-select" name="id_prodi" required id="selectProdi"
                                    @if (!old('id_jurusan')) disabled @endif>
                                    <option value="">
                                        @if (old('id_jurusan'))
                                            — Semua Program Studi —
                                        @else
                                            — Pilih Jurusan dulu —
                                        @endif
                                    </option>
                                    @if (old('id_jurusan') && isset($prodi_data))
                                        @foreach ($prodi_data as $p)
                                            <option value="{{ $p->id_prodi }}" @selected(old('id_prodi') == $p->id_prodi)>
                                                {{ $p->nama_prodi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <!-- Loading Indicator -->
                                <div id="prodiLoadingUpload" class="prodi-loading-upload">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Memuat...</span>
                                </div>
                            </div>
                        </div>
                        <div class="smart-hint" style="margin-top: 14px;">
                            <i class="bi bi-info-circle"></i>
                            <span>Pilih klasifikasi yang sesuai dengan afiliasi akademik dokumen Anda</span>
                        </div>
                    </div>

                    <!-- Optional: Turnitin -->
                    <div class="smart-section" data-section="optional">
                        <div class="smart-optional-toggle" id="turnitinToggle">
                            <div class="smart-optional-toggle-left">
                                <div class="smart-optional-toggle-icon"><i class="bi bi-patch-check-fill"></i></div>
                                <div class="smart-optional-toggle-text">
                                    <h4>+ Tambah Laporan Turnitin (Opsional)</h4>
                                    <p>Sertakan jika Anda memiliki laporan kemiripan dari Turnitin</p>
                                </div>
                            </div>
                            <div class="smart-toggle-arrow"><i class="bi bi-chevron-down"></i></div>
                        </div>
                        <div class="smart-optional-content" id="turnitinContent">
                            <div class="smart-grid">
                                <div class="smart-field">
                                    <label class="smart-label"><span class="smart-label-text">Skor Kemiripan
                                            (%)</span></label>
                                    <div class="smart-input-group">
                                        <input type="number" class="smart-input" name="turnitin" min="0"
                                            max="100" step="0.1" value="{{ old('turnitin') }}"
                                            placeholder="0.0" id="inputTurnitin">
                                        <span class="smart-input-suffix">%</span>
                                    </div>
                                    <div class="smart-hint"><i class="bi bi-info-circle"></i> Range: 0% - 100%</div>
                                </div>
                                <div class="smart-field">
                                    <label class="smart-label"><span class="smart-label-text">File Laporan
                                            Turnitin</span></label>
                                    <div class="smart-dropzone" style="padding: 16px;">
                                        <input type="file" name="turnitin_file" accept=".pdf,.doc,.docx"
                                            id="turnitinFileInput">
                                        <div
                                            style="display:flex;align-items:center;gap:8px;justify-content:center;color:var(--t3);">
                                            <i class="bi bi-paperclip"></i>
                                            <span id="turnitinFileLabel">Pilih file laporan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="smart-divider">
                        <span class="smart-divider-text"><i class="bi bi-send"></i> Semua data sudah lengkap? Klik
                            tombol di bawah</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="smart-actions">
                        <button type="submit" class="smart-btn smart-btn-primary" id="uploadSubmitBtn">
                            <span class="smart-btn-text"><i class="bi bi-cloud-upload-fill"></i> Unggah Dokumen
                                Sekarang</span>
                            <div class="smart-btn-loading"><span class="smart-spinner"></span><span>Sedang
                                    Memproses...</span></div>
                        </button>
                        <button type="reset" class="smart-btn smart-btn-secondary"><i
                                class="bi bi-arrow-counterclockwise"></i> Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="up-footer">&copy; {{ date('Y') }} SIPORA — Politeknik Negeri Jember</div>
    </div>

    @include('components.chatbot_widget')

    <!--
    ════════════════════════════════════════════════════════════════════════
    ✅ JAVASCRIPT UTAMA
    ════════════════════════════════════════════════════════════════════════
    -->
    <script>
        (function() {
            'use strict';

            // === STEP INDICATOR ===
            const steps = document.querySelectorAll('.step-item');

            function updateStepIndicator() {
                const form = document.getElementById('uploadForm');
                if (!form) return;

                const fi = document.getElementById('mainFileInput');
                const judul = document.getElementById('inputJudul');
                const abstrak = document.getElementById('textareaAbstrak');
                const keyword = document.getElementById('inputKeyword');
                const year = document.getElementById('selectYear');
                const tema = document.getElementById('selectTema');
                const divisi = document.getElementById('selectDivisi');
                const jurusan = document.getElementById('selectJurusan');
                const prodi = document.getElementById('selectProdi');

                const s1 = fi && fi.files && fi.files.length > 0;
                const s2 = judul && judul.value.trim().length >= 5 &&
                    abstrak && abstrak.value.trim().length >= 10 &&
                    keyword && keyword.value.trim().length >= 3 &&
                    year && year.value !== '' &&
                    tema && tema.value !== '';
                const s3 = divisi && divisi.value !== '' &&
                    jurusan && jurusan.value !== '' &&
                    prodi && prodi.value !== '';

                steps.forEach(s => s.classList.remove('active', 'completed'));

                if (s1) {
                    steps[0].classList.add('completed');
                    if (s2) {
                        steps[1].classList.add('completed');
                        if (s3) {
                            steps[2].classList.add('completed');
                            steps[3].classList.add('active');
                        } else {
                            steps[2].classList.add('active');
                        }
                    } else {
                        steps[1].classList.add('active');
                    }
                } else {
                    steps[0].classList.add('active');
                }
            }

            document.querySelectorAll('#uploadForm input, #uploadForm select, #uploadForm textarea').forEach(el => {
                el.addEventListener('change', updateStepIndicator);
                el.addEventListener('input', updateStepIndicator);
            });

            // Character Counters
            function setupCharCounter(inputId, counterId, maxLen) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(counterId);
                if (!input || !counter) return;

                function update() {
                    const len = input.value.length;
                    counter.textContent = len + '/' + maxLen;
                    counter.className = 'smart-char-count';
                    if (len > maxLen * 0.9) counter.classList.add('danger');
                    else if (len > maxLen * 0.7) counter.classList.add('warning');
                }
                input.addEventListener('input', update);
                update();
            }
            setupCharCounter('inputJudul', 'judulCount', 200);
            setupCharCounter('textareaAbstrak', 'abstrakCount', 500);
            setupCharCounter('inputKeyword', 'keywordCount', 100);

            // Form Progress
            function updateFormProgress() {
                const form = document.getElementById('uploadForm');
                const bar = document.getElementById('formProgress');
                if (!form || !bar) return;
                const fields = form.querySelectorAll('[required]');
                let filled = 0;
                fields.forEach(f => {
                    if (f.type === 'file' ? f.files?.length : f.value.trim()) filled++;
                });
                bar.style.width = Math.round((filled / fields.length) * 100) + '%';
                updateStepIndicator();
            }
            document.querySelectorAll('#uploadForm input, #uploadForm select, #uploadForm textarea').forEach(el => {
                el.addEventListener('change', updateFormProgress);
                el.addEventListener('input', updateFormProgress);
            });
            setTimeout(updateFormProgress, 100);

            // Dropzone Logic
            const dz = document.getElementById('mainDropzone'),
                fi = document.getElementById('mainFileInput'),
                pv = document.getElementById('mainFilePreview'),
                fn = document.getElementById('mainFileName'),
                fs = document.getElementById('mainFileSize'),
                rm = document.getElementById('mainFileRemove');

            if (dz && fi) {
                ['dragenter', 'dragover'].forEach(e => dz.addEventListener(e, ev => {
                    ev.preventDefault();
                    dz.classList.add('drag-over');
                }));
                ['dragleave', 'drop'].forEach(e => dz.addEventListener(e, ev => {
                    ev.preventDefault();
                    dz.classList.remove('drag-over');
                }));
                dz.addEventListener('drop', e => {
                    if (e.dataTransfer.files?.length) {
                        fi.files = e.dataTransfer.files;
                        showFP(e.dataTransfer.files[0]);
                    }
                });
                fi.addEventListener('change', () => {
                    if (fi.files?.length) showFP(fi.files[0]);
                });
            }
            if (rm) rm.addEventListener('click', () => {
                if (fi) fi.value = '';
                if (pv) pv.classList.remove('show');
                if (dz) dz.classList.remove('has-file');
                updateFormProgress();
            });

            function showFP(file) {
                if (!file || !fn || !fs || !pv || !dz) return;
                fn.textContent = file.name;
                fs.textContent = formatSize(file.size);
                pv.classList.add('show');
                dz.classList.add('has-file');
                const ic = document.getElementById('previewIcon');
                if (ic && file.name) {
                    const ext = file.name.split('.').pop().toLowerCase();
                    ic.className = 'bi ' + ({
                        pdf: 'bi-file-earmark-pdf',
                        doc: 'bi-file-earmark-word',
                        docx: 'bi-file-earmark-word',
                        ppt: 'bi-file-earmark-ppt',
                        pptx: 'bi-file-earmark-ppt',
                        xls: 'bi-file-earmark-spreadsheet',
                        xlsx: 'bi-file-earmark-spreadsheet'
                    } [ext] || 'bi-file-earmark-text');
                }
                updateFormProgress();
            }

            function formatSize(b) {
                if (!b) return '0 B';
                const k = 1024,
                    s = ['B', 'KB', 'MB', 'GB'],
                    i = Math.floor(Math.log(b) / Math.log(k));
                return parseFloat((b / Math.pow(k, i)).toFixed(1)) + ' ' + s[i];
            }

            // Turnitin Toggle
            const tt = document.getElementById('turnitinToggle'),
                tc = document.getElementById('turnitinContent');
            if (tt && tc) tt.addEventListener('click', () => {
                const a = tt.classList.toggle('active');
                tc.classList.toggle('show', a);
            });
            const ti = document.getElementById('turnitinFileInput'),
                tl = document.getElementById('turnitinFileLabel');
            if (ti && tl) ti.addEventListener('change', () => {
                tl.textContent = ti.files?.length ? ti.files[0].name : 'Pilih file laporan';
            });

            // Submit Loading
            const form = document.getElementById('uploadForm'),
                btn = document.getElementById('uploadSubmitBtn');
            if (form && btn) form.addEventListener('submit', () => {
                btn.classList.add('is-loading');
                document.getElementById('formProgress').style.width = '100%';
                setTimeout(() => {
                    if (btn.classList.contains('is-loading')) btn.classList.remove('is-loading');
                }, 8000);
            });

            // Reset Handler
            if (form) form.addEventListener('reset', () => setTimeout(() => {
                if (pv) pv.classList.remove('show');
                if (fi) fi.value = '';
                if (dz) dz.classList.remove('has-file');
                if (tl) tl.textContent = 'Pilih file laporan';
                if (ti) ti.value = '';
                document.querySelectorAll('.smart-char-count').forEach(el => {
                    const m = el.textContent.split('/')[1];
                    el.textContent = '0/' + m;
                    el.className = 'smart-char-count';
                });
                if (tt) tt.classList.remove('active');
                if (tc) tc.classList.remove('show');
                resetProdiSelect(); // ✅ Reset dropdown prodi juga
                updateFormProgress();
            }, 50));

            console.log('✅ SIPORA Smart Upload Ready');
        })();
    </script>

    <!--
    ════════════════════════════════════════════════════════════════════════
    ✅ JAVASCRIPT KHUSUS: DYNAMIC JURUSAN-PRODI DROPDOWN
    ════════════════════════════════════════════════════════════════════════

    ⚠️ PERHATIAN: Parameter query adalah "id_jurusan" (SESUAI CONTROLLER ANDA)
    -->
    <script>
        (function() {
            'use strict';

            // === ELEMEN DOM ===
            const selectJurusan = document.getElementById('selectJurusan');
            const selectProdi = document.getElementById('selectProdi');
            const loadingEl = document.getElementById('prodiLoadingUpload');

            if (!selectJurusan || !selectProdi) {
                console.warn('⚠️ Dropdown Jurusan/Prodi tidak ditemukan');
                return;
            }

            /**
             * ✅ FUNGSI UTAMA: Handle Perubahan Jurusan
             * Dipanggil saat user memilih jurusan dari dropdown
             */
            window.handleJurusanChange = function(jurusanId) {
                console.log(`🔄 [JURUSAN-PRODI] Jurusan dipilih: ${jurusanId}`);

                // Jika kosong, reset prodi
                if (!jurusanId || jurusanId === '') {
                    resetProdiSelect();
                    return;
                }

                // Load prodi via AJAX ke endpoint Laravel
                loadProdiByJurusan(jurusanId);

                // Update UI lainnya
                if (typeof updateFormProgress === 'function') updateFormProgress();
                if (typeof updateStepIndicator === 'function') updateStepIndicator();
            };

            /**
             * ✅ LOAD PRODI DARI SERVER VIA AJAX
             * Endpoint: /upload/prodi?id_jurusan={ID}
             * Controller: UploadController@getProdi
             */
            function loadProdiByJurusan(jurusanId) {
                // Tampilkan loading state
                showLoading(true);
                selectProdi.disabled = true;
                selectProdi.innerHTML = '<option value="">Memuat data...</option>';

                // ✅ Gunakan route() helper Laravel untuk URL yang benar
                // Parameter: id_jurusan (SESUAI DENGAN CONTROLLER ANDA)
                const url = "{{ route('upload.get-prodi') }}?id_jurusan=" + encodeURIComponent(jurusanId);
                const csrfToken = document.body.dataset.csrfToken || '';

                console.log(`📡 [JURUSAN-PRODI] Fetching: ${url}`);

                // Kirim request AJAX
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrfToken ? {
                                'X-CSRF-TOKEN': csrfToken
                            } : {})
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('✅ [JURUSAN-PRODI] Response:', data);

                        populateProdiSelect(data);
                        showLoading(false);
                        selectProdi.disabled = false;

                        // Toast sukses (COMPACT VERSION)
                        showToastCompact('success', 'Berhasil', 'Data Program Studi diperbarui', 2500);
                    })
                    .catch(error => {
                        console.error('❌ [JURUSAN-PRODI] Error:', error);
                        selectProdi.innerHTML = '<option value="">Gagal memuat</option>';
                        selectProdi.disabled = true;
                        showLoading(false);

                        // Toast error (COMPACT VERSION)
                        showToastCompact('error', 'Error', 'Gagal memuat data Program Studi', 4000);
                    });
            }

            /**
             * ✅ POPULATE DROPDOWN PRODI DARI RESPONSE JSON
             * Response format: [{id_prodi: 1, nama_prodi: "Teknik Informatika"}, ...]
             */
            function populateProdiSelect(data) {
                // Reset dengan option default
                selectProdi.innerHTML = '<option value="">— Pilih Prodi —</option>';

                // Pastikan data adalah array
                let prodiList = [];

                // Handle berbagai format response
                if (Array.isArray(data)) {
                    // Format langsung array
                    prodiList = data;
                } else if (data && typeof data === 'object') {
                    // Format object dengan property 'data'
                    prodiList = data.data || [data];
                }

                // Cek jika kosong
                if (!prodiList || prodiList.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'Tidak tersedia';
                    opt.disabled = true;
                    selectProdi.appendChild(opt);
                    console.log('⚠️ [JURUSAN-PRODI] Tidak ada data prodi');
                    return;
                }

                // Tambahkan option untuk setiap prodi
                prodiList.forEach((prodi, idx) => {
                    if (!prodi) return;

                    const option = document.createElement('option');
                    option.value = String(prodi.id_prodi || '');
                    option.textContent = String(prodi.nama_prodi || `Prodi ${idx+1}`).trim();

                    // Set selected jika ada old value dari validation error
                    const oldProdi = document.body.dataset.oldProdi || '';
                    if (oldProdi && String(option.value) === String(oldProdi)) {
                        option.selected = true;
                    }

                    selectProdi.appendChild(option);
                });

                console.log(`📋 [JURUSAN-PRODI] ${prodiList.length} Prodi ditampilkan`);
            }

            /**
             * ✅ RESET DROPDOWN PRODI KE STATE AWAL
             */
            window.resetProdiSelect = function() {
                if (selectProdi) {
                    selectProdi.innerHTML = '<option value="">— Pilih Jurusan dulu —</option>';
                    selectProdi.disabled = true;
                    selectProdi.value = '';
                }
                showLoading(false);
            };

            /**
             * ✅ TOGGLE LOADING INDICATOR
             */
            function showLoading(show) {
                if (loadingEl) {
                    loadingEl.style.display = show ? 'inline-flex' : 'none';
                    if (show) loadingEl.classList.add('active');
                    else loadingEl.classList.remove('active');
                }
            }

            /**
             * ✅ COMPACT TOAST NOTIFICATION (Menggunakan SweetAlert2)
             *
             * Versi compact dengan ukuran lebih kecil seperti pada gambar referensi
             * - Lebih kecil dan ringkas
             * - Hanya menampilkan title (tanpa message text)
             * - Icon lebih kecil
             * - Animasi smooth dari kanan
             */
            function showToastCompact(type, title, message, duration = 4000) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: type === 'success' ? 'success' : 'error',
                        title: title,
                        text: message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: duration,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'compact-toast-popup',
                            title: 'compact-toast-title',
                            icon: 'compact-toast-icon'
                        },
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
             * ✅ INISIALISASI SAAT HALAMAN DIMUAT
             * Cek jika ada nilai jurusan dari old input (setelah validation error)
             */
            function initializeDynamicDropdown() {
                if (selectJurusan && selectJurusan.value) {
                    console.log(`📌 [JURUSAN-PRODI] Inisialisasi dengan Jurusan ID: ${selectJurusan.value}`);
                    loadProdiByJurusan(selectJurusan.value);
                } else {
                    resetProdiSelect();
                }
            }

            // Jalankan inisialisasi saat DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeDynamicDropdown);
            } else {
                // DOM sudah ready, jalankan sekarang
                setTimeout(initializeDynamicDropdown, 100);
            }

            console.log('✅ [JURUSAN-PRODI] Dynamic Dropdown System Initialized');
            console.log('ℹ️ [JURUSAN-PRODI] Endpoint: {{ route('upload.get-prodi') }}?id_jurusan={ID}');

        })();
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/upload-page.js') }}"></script>
    <script src="{{ asset('assets/js/upload.js') }}"></script>
</body>

</html>
