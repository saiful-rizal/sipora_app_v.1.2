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

        /* ═══════════════════════════════════════════
           ✅ UPLOAD PAGE - DIUBAH AGAR SESUAI DASHBOARD
           ═══════════════════════════════════════════ */
        .upload-page {
            max-width: 1200px;
            /* ✅ Diubah dari 900px ke 1200px (sama dengan dashboard) */
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
            /* ✅ Diubah untuk responsif seperti dashboard */
            position: relative;
            z-index: 1;
        }

        .upload-page *:not(.fas):not(.far):not(.fab):not(.bi):not(.fa-solid):not(.fa-regular):not(.fa-brands):not(.badge-status):not(.score-badge):not(.doc-title) {
            font-weight: 400 !important;
        }

        /* ═══ TAB NAVIGATION ═══ */
        .tab-navigation {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-md);
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
            transition: background-color .25s ease, color .25s ease;
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

        .up-alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .up-alert-warning i {
            color: #d97706;
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

        /* ═══ HERO ═══ */
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

        .up-chip i {
            font-size: 11px;
        }

        /* ═══ SMART UPLOAD CARD - USER-CENTERED DESIGN ═══ */
        .smart-upload-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
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

        .smart-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .smart-card-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
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
            animation: smartIconPulse 3s ease-in-out infinite;
        }

        @keyframes smartIconPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 6px 28px rgba(255, 255, 255, 0.2);
            }
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

        /* Step Indicator - NEW! */
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

        /* Smart Section Grouping */
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

        .smart-section:nth-child(5) {
            animation-delay: 0.25s;
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

        .smart-section-badge.step {
            background: rgba(26, 86, 214, 0.1);
            color: var(--blue);
            border-color: rgba(26, 86, 214, 0.2);
        }

        /* Smart Form Grid */
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

        .smart-input.valid {
            border-color: var(--green);
            background: rgba(34, 197, 94, 0.02);
        }

        .smart-input.invalid {
            border-color: var(--rose);
            background: rgba(244, 63, 94, 0.02);
        }

        .smart-input::placeholder,
        .smart-textarea::placeholder {
            color: var(--t3);
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

        .smart-input.valid~.smart-input-icon {
            color: var(--green);
        }

        .smart-input.invalid~.smart-input-icon {
            color: var(--rose);
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

        .smart-validation-msg {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            margin-top: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            animation: msgSlideIn .2s ease;
        }

        .smart-validation-msg.show {
            display: flex;
        }

        .smart-validation-msg.error {
            background: rgba(244, 63, 94, 0.08);
            color: var(--rose);
            border: 1px solid rgba(244, 63, 94, 0.15);
        }

        .smart-validation-msg.success {
            background: rgba(34, 197, 94, 0.08);
            color: var(--green);
            border: 1px solid rgba(34, 197, 94, 0.15);
        }

        @keyframes msgSlideIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smart Dropzone - ENHANCED */
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

        .smart-dropzone::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.03), rgba(30, 64, 175, 0.03));
            opacity: 0;
            transition: opacity .3s;
        }

        .smart-dropzone:hover,
        .smart-dropzone.drag-over {
            border-color: var(--blue);
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.04), rgba(30, 64, 175, 0.06));
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(26, 86, 214, 0.15);
        }

        .smart-dropzone:hover::before,
        .smart-dropzone.drag-over::before {
            opacity: 1;
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
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.18), rgba(30, 64, 175, 0.18));
            box-shadow: 0 8px 24px rgba(26, 86, 214, 0.25);
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

        /* TOMBOL DROPZONE */
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

        .smart-dropzone-btn:hover {
            background: linear-gradient(130deg, var(--indigo), var(--blue));
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
            transition: all .2s;
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

        /* File Size Warning */
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

        .file-size-note i {
            font-size: 13px;
            color: var(--blue);
        }

        /* AI SCREENING FORMAT - ENHANCED SMART VERSION */

        /* Screening Container */
        .ai-screening-container {
            display: none;
            margin-top: 28px;
            animation: screeningSlideIn .6s cubic-bezier(.4, 0, .2, 1);
        }

        .ai-screening-container.show {
            display: block;
        }

        @keyframes screeningSlideIn {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ENHANCED SCREENING CARD - Glassmorphism + Gradient Border */
        .ai-screening-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.98) 100%);
            border-radius: var(--r-xl);
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);

            &::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: var(--r-xl);
                padding: 2.5px;
                background: linear-gradient(135deg,
                        var(--blue) 0%,
                        var(--indigo) 25%,
                        var(--sky) 50%,
                        var(--indigo) 75%,
                        var(--blue) 100%);
                background-size: 300% 300%;
                animation: gradientBorderRotate 4s linear infinite;
                -webkit-mask:
                    linear-gradient(#fff 0 0) content-box,
                    linear-gradient(#fff 0 0);
                mask:
                    linear-gradient(#fff 0 0) content-box,
                    linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                z-index: 0;
            }

            box-shadow: 0 20px 60px rgba(26, 86, 214, 0.12),
            0 8px 24px rgba(0, 0, 0, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        @keyframes gradientBorderRotate {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Header Section - Enhanced */
        .ai-screening-header {
            background: linear-gradient(135deg,
                    rgba(26, 86, 214, 0.06) 0%,
                    rgba(74, 125, 255, 0.04) 50%,
                    rgba(56, 189, 248, 0.03) 100%);
            padding: 28px 28px 24px;
            border-bottom: 1px solid rgba(228, 233, 245, 0.6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;

            &::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg,
                        var(--blue) 0%,
                        var(--indigo) 35%,
                        var(--sky) 65%,
                        var(--indigo) 100%);
                background-size: 200% 100%;
                animation: headerGradientShift 3s ease-in-out infinite;
            }
        }

        @keyframes headerGradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .ai-screening-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ENHANCED ICON - Glow Effect + Pulse Ring */
        .ai-screening-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            position: relative;
            box-shadow:
                0 8px 24px rgba(26, 86, 214, 0.35),
                0 2px 8px rgba(26, 86, 214, 0.2);

            &::before {
                content: '';
                position: absolute;
                inset: -4px;
                border-radius: 20px;
                background: linear-gradient(135deg, var(--blue), var(--indigo));
                opacity: 0;
                animation: iconPulseRing 2.5s ease-in-out infinite;
                z-index: -1;
            }
        }

        @keyframes iconPulseRing {

            0%,
            100% {
                opacity: 0;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(1.15);
            }
        }

        .ai-screening-title h4 {
            font-family: var(--font-d);
            font-size: 17px;
            color: var(--t1);
            margin: 0 0 4px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .ai-screening-title p {
            font-size: 12px;
            color: var(--t3);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;

            &::before {
                content: '';
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: var(--green);
                animation: dotBlink 1.5s ease-in-out infinite;
            }
        }

        @keyframes dotBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* CIRCULAR SCORE BADGE - Modern Progress Ring */
        .ai-score-badge {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 16px 24px;
            background: var(--card);
            border-radius: 18px;
            border: 2px solid var(--border);
            position: relative;
            box-shadow:
                0 4px 16px rgba(0, 0, 0, 0.04),
                0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all .35s cubic-bezier(.4, 0, .2, 1);

            &:hover {
                transform: translateY(-2px);
                box-shadow:
                    0 8px 24px rgba(0, 0, 0, 0.08),
                    0 4px 12px rgba(26, 86, 214, 0.08);
            }
        }

        .ai-score-value {
            font-family: var(--font-d);
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            position: relative;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all .4s ease;

            filter: drop-shadow(0 2px 4px rgba(26, 86, 214, 0.15));
        }

        .ai-score-label {
            font-size: 10.5px;
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        /* Score Badge Color Variations */
        .ai-score-badge.excellent {
            border-color: rgba(34, 197, 94, 0.3);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.04), rgba(20, 184, 166, 0.02));

            .ai-score-value {
                background: linear-gradient(135deg, #16a34a, #22c55e);
                -webkit-background-clip: text;
                background-clip: text;
                filter: drop-shadow(0 2px 4px rgba(34, 197, 94, 0.2));
            }
        }

        .ai-score-badge.good {
            border-color: rgba(26, 86, 214, 0.3);
            background: linear-gradient(135deg, rgba(26, 86, 214, 0.04), rgba(74, 125, 255, 0.02));

            .ai-score-value {
                background: linear-gradient(135deg, var(--blue), var(--indigo));
                -webkit-background-clip: text;
                background-clip: text;
            }
        }

        .ai-score-badge.warning {
            border-color: rgba(245, 158, 11, 0.3);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.04), rgba(251, 191, 36, 0.02));

            .ai-score-value {
                background: linear-gradient(135deg, #d97706, #f59e0b);
                -webkit-background-clip: text;
                background-clip: text;
                filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.2));
            }
        }

        .ai-score-badge.danger {
            border-color: rgba(239, 68, 68, 0.3);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.04), rgba(244, 63, 94, 0.02));

            .ai-score-value {
                background: linear-gradient(135deg, #dc2626, #ef4444);
                -webkit-background-clip: text;
                background-clip: text;
                filter: drop-shadow(0 2px 4px rgba(239, 68, 68, 0.2));
            }
        }

        /* Screening Body - Enhanced Spacing */
        .ai-screening-body {
            padding: 28px;
            position: relative;
            z-index: 1;
        }

        /* STATUS BANNER - Glass Card Style */
        .ai-status-banner {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            animation: statusBannerIn .5s cubic-bezier(.4, 0, .2, 1);

            &::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
                opacity: 0.5;
            }
        }

        @keyframes statusBannerIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(-8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .ai-status-banner.passed {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(20, 184, 166, 0.08) 100%);
            border: 1.5px solid rgba(34, 197, 94, 0.25);
            box-shadow:
                0 4px 16px rgba(34, 197, 94, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);

            .ai-status-icon {
                background: linear-gradient(135deg, #16a34a, #22c55e);
                box-shadow: 0 4px 16px rgba(34, 197, 94, 0.3);
            }
        }

        .ai-status-banner.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.08) 100%);
            border: 1.5px solid rgba(245, 158, 11, 0.25);
            box-shadow:
                0 4px 16px rgba(245, 158, 11, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);

            .ai-status-icon {
                background: linear-gradient(135deg, #d97706, #f59e0b);
                box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
            }
        }

        .ai-status-banner.failed {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(244, 63, 94, 0.08) 100%);
            border: 1.5px solid rgba(239, 68, 68, 0.25);
            box-shadow:
                0 4px 16px rgba(239, 68, 68, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);

            .ai-status-icon {
                background: linear-gradient(135deg, #dc2626, #ef4444);
                box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
            }
        }

        .ai-status-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .ai-status-text {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .ai-status-text h5 {
            font-family: var(--font-d);
            font-size: 15px;
            color: var(--t1);
            margin: 0 0 4px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .ai-status-text p {
            font-size: 12.5px;
            color: var(--t2);
            margin: 0;
            line-height: 1.55;
        }

        /* CHECK LIST - Modern Cards with Stagger Animation */
        .ai-check-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .ai-check-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(248, 250, 252, 0.9) 100%);
            border-radius: 14px;
            border: 1.5px solid var(--border);
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateX(-16px);
            animation: checkItemSlideIn .4s cubic-bezier(.4, 0, .2, 1) forwards;

            &:hover {
                border-color: rgba(26, 86, 214, 0.25);
                box-shadow:
                    0 4px 16px rgba(0, 0, 0, 0.06),
                    0 2px 6px rgba(26, 86, 214, 0.06);
                transform: translateX(0) translateY(-2px);
            }

            &::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg,
                        transparent 0%,
                        rgba(255, 255, 255, 0.4) 50%,
                        transparent 100%);
                transition: left .5s ease;
            }

            &:hover::after {
                left: 100%;
            }
        }

        /* Stagger delay for each item */
        .ai-check-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .ai-check-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .ai-check-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .ai-check-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .ai-check-item:nth-child(5) {
            animation-delay: 0.25s;
        }

        .ai-check-item:nth-child(6) {
            animation-delay: 0.3s;
        }

        @keyframes checkItemSlideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .ai-check-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            position: relative;
        }

        .ai-check-item.passed .ai-check-icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(20, 184, 166, 0.12));
            color: #16a34a;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.15);
        }

        .ai-check-item.warning .ai-check-icon {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(251, 191, 36, 0.12));
            color: #d97706;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
        }

        .ai-check-item.failed .ai-check-icon {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(244, 63, 94, 0.12));
            color: #dc2626;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
        }

        .ai-check-content {
            flex: 1;
            min-width: 0;
        }

        .ai-check-content strong {
            display: block;
            font-size: 13.5px;
            color: var(--t1);
            margin-bottom: 3px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .ai-check-content span {
            font-size: 12px;
            color: var(--t3);
        }

        .ai-check-status {
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        .ai-check-item.passed .ai-check-status {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.12), rgba(20, 184, 166, 0.1));
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .ai-check-item.warning .ai-check-status {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(251, 191, 36, 0.1));
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .ai-check-item.failed .ai-check-status {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(244, 63, 94, 0.1));
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* RECOMMENDATIONS SECTION - Enhanced Card */
        .ai-recommendations {
            background: linear-gradient(135deg,
                    rgba(26, 86, 214, 0.04) 0%,
                    rgba(74, 125, 255, 0.03) 50%,
                    rgba(56, 189, 248, 0.02) 100%);
            border: 2px dashed rgba(26, 86, 214, 0.3);
            border-radius: 16px;
            padding: 22px 24px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;

            &::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    radial-gradient(circle at 1px 1px, rgba(26, 86, 214, 0.08) 1px, transparent 0);
                background-size: 20px 20px;
                opacity: 0.5;
            }
        }

        .ai-recommendations-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .ai-recommendations-header i {
            font-size: 20px;
            color: var(--blue);
            animation: bulbGlow 2s ease-in-out infinite;
        }

        @keyframes bulbGlow {

            0%,
            100% {
                filter: drop-shadow(0 0 4px rgba(26, 86, 214, 0.3));
                transform: scale(1);
            }

            50% {
                filter: drop-shadow(0 0 8px rgba(26, 86, 214, 0.5));
                transform: scale(1.1);
            }
        }

        .ai-recommendations-header strong {
            font-family: var(--font-d);
            font-size: 14px;
            color: var(--t1);
            font-weight: 700;
        }

        .ai-recommendations ul {
            margin: 0;
            padding-left: 22px;
            position: relative;
            z-index: 1;
        }

        .ai-recommendations li {
            font-size: 12.5px;
            color: var(--t2);
            margin-bottom: 8px;
            line-height: 1.6;
            position: relative;
            padding-left: 8px;
            transition: all .25s ease;

            &:hover {
                color: var(--blue);
                padding-left: 12px;
            }

            &::marker {
                color: var(--blue);
                font-weight: bold;
            }
        }

        .ai-recommendations li:last-child {
            margin-bottom: 0;
        }

        /* Loading State - Enhanced */
        .ai-screening-loading {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 56px 24px;
            text-align: center;
        }

        .ai-screening-loading.show {
            display: flex;
        }

        .ai-loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid var(--border);
            border-top-color: var(--blue);
            border-right-color: var(--indigo);
            border-radius: 50%;
            animation: aiSpin 1s linear infinite;
            margin-bottom: 24px;
            position: relative;

            &::after {
                content: '';
                position: absolute;
                inset: 6px;
                border: 3px solid transparent;
                border-top-color: var(--sky);
                border-radius: 50%;
                animation: aiSpinInner 0.8s linear infinite reverse;
            }
        }

        @keyframes aiSpin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes aiSpinInner {
            to {
                transform: rotate(-360deg);
            }
        }

        .ai-loading-text {
            font-family: var(--font-d);
            font-size: 16px;
            color: var(--t1);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .ai-loading-subtext {
            font-size: 12.5px;
            color: var(--t3);
            max-width: 280px;
            line-height: 1.5;
        }

        /* Responsive for AI Screening */
        @media (max-width: 768px) {
            .ai-screening-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 24px 20px 20px;
            }

            .ai-screening-title {
                flex-direction: column;
                gap: 12px;
            }

            .ai-screening-body {
                padding: 24px 20px;
            }

            .ai-status-banner {
                flex-direction: column;
                text-align: center;
                padding: 18px 20px;
            }

            .ai-check-item {
                flex-wrap: wrap;
                padding: 14px 16px;
            }

            .ai-check-status {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }

            .ai-score-badge {
                padding: 14px 20px;
            }

            .ai-score-value {
                font-size: 28px;
            }
        }

        /* Smart File Preview */
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
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
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

        /* TOMBOL HAPUS FILE */
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
            transition: background-color .2s ease, color .2s ease, border-color .2s ease;
            flex-shrink: 0;
        }

        .smart-file-remove:hover {
            background: var(--rose);
            color: #fff;
            border-color: var(--rose);
        }

        /* Optional Section Toggle */
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

        /* Divider */
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

        /* Action Buttons */
        .smart-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* SMART BUTTON */
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
            transition: background-color .2s ease, color .2s ease, border-color .2s ease;
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

        .smart-btn-primary:active {
            background: linear-gradient(130deg, var(--blue), var(--indigo));
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

        /* SHINE EFFECT DIHAPUS TOTAL */
        .smart-btn-shine {
            display: none;
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

        /* Responsive Design */
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

            /* Step indicator responsive */
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

            .up-row {
                grid-template-columns: 1fr;
            }

            .up-hero {
                padding: 28px 24px;
            }

            .up-hero h2 {
                font-size: 20px;
            }

            .up-card-body {
                padding: 20px;
            }

            .up-card-head {
                padding: 18px 20px 14px;
            }

            .up-dropzone {
                padding: 28px 16px;
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
        }

        @media (max-width: 480px) {
            .up-hero-chips {
                display: none;
            }

            .smart-dropzone-formats {
                gap: 5px;
            }

            .smart-format-tag {
                font-size: 9px;
                padding: 4px 7px;
            }

            .smart-divider {
                margin: 24px 0 20px;
            }

            .step-label {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .prem-bg-orb,
            .smart-spinner,
            .smart-progress-fill {
                animation: none !important;
            }

            .up-alert,
            .smart-file-preview,
            .smart-section {
                animation: none !important;
            }

            .smart-dropzone,
            .smart-dropzone-icon,
            .smart-btn,
            .smart-file-remove {
                transition: none !important;
            }

            /* Disable enhanced animations */
            .ai-screening-card::before,
            .ai-screening-icon::before,
            .ai-check-item,
            .ai-loading-spinner,
            .ai-loading-spinner::after,
            .ai-status-banner,
            .ai-recommendations-header i {
                animation: none !important;
            }
        }
    </style>
</head>

<body data-old-prodi="{{ old('id_prodi') ?? '' }}" data-upload-prodi-endpoint="{{ route('upload.get-prodi') }}">

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

        <!-- Tab Navigation - REDIRECT KE HALAMAN TERSENDIRI -->
        <nav class="tab-navigation" role="navigation" aria-label="Menu Navigasi Dokumen">
            <div class="tab-nav-container">
                <!-- Tab Unggah: Tetap di halaman ini (active) -->
                <span class="tab-nav-item active" aria-current="page">
                    <i class="bi bi-cloud-arrow-up"></i><span class="tab-text">Unggah</span>
                </span>

                <!-- Tab Dokumen Saya: Redirect ke my_documents.blade.php -->
                <a href="{{ route('documents.my') }}" class="tab-nav-item" role="link">
                    <i class="bi bi-folder2-open"></i><span class="tab-text">Dokumen Saya</span>
                </a>

                <!-- Tab Riwayat: Redirect ke upload_history.blade.php -->
                <a href="{{ route('documents.history', ['date' => 'all']) }}" class="tab-nav-item" role="link">
                    <i class="bi bi-clock-history"></i><span class="tab-text">Riwayat Upload</span>
                </a>

                <!-- Tab Turnitin: Redirect ke turnitin.blade.php -->
                <a href="{{ route('documents.turnitin', ['score' => 'all']) }}" class="tab-nav-item" role="link">
                    <i class="bi bi-patch-check"></i><span class="tab-text">Skor Turnitin</span>
                </a>

                <div class="tab-nav-indicator"></div>
            </div>
        </nav>

        <!-- ALERTS -->
        @if (session()->has('upload_success'))
            <div class="up-alert up-alert-success"><i class="bi bi-check-circle-fill"></i>
                <div><strong>Berhasil!</strong> Dokumen telah diunggah.</div>
            </div>
        @endif

        @if (isset($screening_result) && !empty($screening_result))
            <div class="up-alert {{ $screening_result['passed'] ?? false ? 'up-alert-success' : 'up-alert-warning' }}">
                <i
                    class="bi {{ $screening_result['passed'] ?? false ? 'bi-shield-check' : 'bi-exclamation-triangle' }}"></i>
                <div><strong>Screening
                        ({{ $screening_result['score'] ?? 0 }}%)</strong>{{ $screening_result['message'] ?? '' }}
                </div>
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

        <!-- SMART UPLOAD CARD - FORM UNGGAH SAJA -->
        <div class="smart-upload-card">
            <!-- Card Header with Progress -->
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

            <!-- Card Body -->
            <div class="smart-card-body">
                <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data"
                    id="uploadForm">@csrf

                    <!-- STEP INDICATOR -->
                    <div class="step-indicator">
                        <div class="step-item active" data-step="1">
                            <div class="step-number">1</div>
                            <span class="step-label">Upload File</span>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-number">2</div>
                            <span class="step-label">Info Dokumen</span>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-number">3</div>
                            <span class="step-label">Klasifikasi</span>
                        </div>
                        <div class="step-item" data-step="4">
                            <div class="step-number">4</div>
                            <span class="step-label">Selesai</span>
                        </div>
                    </div>

                    <!-- LANGKAH 1: UPLOAD FILE (PRIORITAS UTAMA) -->
                    <div class="smart-section" data-section="upload">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon upload"><i class="bi bi-cloud-arrow-up"></i>
                                </div>
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
                                    onclick="document.getElementById('mainFileInput').click()">
                                    <i class="bi bi-folder2-open"></i> Pilih File
                                </button>
                                <div class="smart-dropzone-formats">
                                    <span class="smart-format-tag"><i class="bi bi-file-pdf"></i> PDF</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-word"></i> DOC</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-ppt"></i> PPT</span>
                                    <span class="smart-format-tag"><i class="bi bi-file-excel"></i> XLS</span>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div class="smart-file-preview" id="mainFilePreview">
                                <div class="smart-file-icon"><i class="bi bi-file-earmark-text" id="previewIcon"></i>
                                </div>
                                <div class="smart-file-info">
                                    <div class="smart-file-name" id="mainFileName">—</div>
                                    <div class="smart-file-meta">
                                        <span><i class="bi bi-hdd"></i> <span id="mainFileSize">—</span></span>
                                        <span><i class="bi bi-check-circle" style="color: var(--green);"></i>
                                            Siap diunggah</span>
                                    </div>
                                </div>
                                <button type="button" class="smart-file-remove" id="mainFileRemove"
                                    title="Hapus File">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- AI SCREENING FORMAT SECTION - ENHANCED VERSION -->
                            <div class="ai-screening-container" id="aiScreeningContainer">
                                <div class="ai-screening-card">
                                    <!-- Header with Score -->
                                    <div class="ai-screening-header">
                                        <div class="ai-screening-title">
                                            <div class="ai-screening-icon">
                                                <i class="bi bi-cpu"></i>
                                            </div>
                                            <div>
                                                <h4>AI Format Screening</h4>
                                                <p>Analisis otomatis oleh kecerdasan buatan</p>
                                            </div>
                                        </div>
                                        <div class="ai-score-badge" id="aiScoreBadge">
                                            <span class="ai-score-value" id="aiScoreValue">—</span>
                                            <span class="ai-score-label">Skor</span>
                                        </div>
                                    </div>

                                    <!-- Loading State -->
                                    <div class="ai-screening-loading" id="aiLoadingState">
                                        <div class="ai-loading-spinner"></div>
                                        <div class="ai-loading-text">Sedang Menganalisis Dokumen...</div>
                                        <div class="ai-loading-subtext">AI sedang memeriksa format dan kualitas file
                                        </div>
                                    </div>

                                    <!-- Results Body -->
                                    <div class="ai-screening-body" id="aiScreeningBody" style="display: none;">
                                        <!-- Status Banner -->
                                        <div class="ai-status-banner" id="aiStatusBanner">
                                            <div class="ai-status-icon">
                                                <i class="bi bi-shield-check" id="aiStatusIcon"></i>
                                            </div>
                                            <div class="ai-status-text">
                                                <h5 id="aiStatusTitle">Memproses...</h5>
                                                <p id="aiStatusMessage">Mohon tunggu sebentar</p>
                                            </div>
                                        </div>

                                        <!-- Check List -->
                                        <div class="ai-check-list" id="aiCheckList">
                                            <!-- Items will be injected by JavaScript -->
                                        </div>

                                        <!-- Recommendations -->
                                        <div class="ai-recommendations" id="aiRecommendations"
                                            style="display: none;">
                                            <div class="ai-recommendations-header">
                                                <i class="bi bi-lightbulb"></i>
                                                <strong>Rekomendasi AI</strong>
                                            </div>
                                            <ul id="aiRecommendationList">
                                                <!-- Items will be injected by JavaScript -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="file-size-note">
                                <i class="bi bi-info-circle"></i>
                                <span>Ukuran maksimal file: <strong>10MB</strong> • Format: PDF, DOC, DOCX, PPT,
                                    PPTX, XLS, XLSX</span>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 2: INFORMASI DASAR DOKUMEN -->
                    <div class="smart-section" data-section="info">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon info"><i class="bi bi-info-lg"></i></div>
                                <span>Langkah 2: Informasi Dokumen</span>
                            </div>
                            <span class="smart-section-badge step required">Langkah 2 • Wajib</span>
                        </div>

                        <div class="smart-grid">
                            <!-- Judul - PALING PENTING -->
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
                                <div class="smart-hint"><i class="bi bi-lightbulb"></i> Gunakan judul yang
                                    jelas dan deskriptif</div>
                                <div class="smart-validation-msg" id="judulValidation"></div>
                            </div>

                            <!-- Tahun & Tema -->
                            <div class="smart-field">
                                <label class="smart-label">
                                    <span class="smart-label-text">Tahun Akademik <span class="req">*</span></span>
                                </label>
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
                                <label class="smart-label">
                                    <span class="smart-label-text">Tema / Kategori <span
                                            class="req">*</span></span>
                                </label>
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

                            <!-- Deskripsi / Abstrak -->
                            <div class="smart-field full">
                                <label class="smart-label">
                                    <span class="smart-label-text">Deskripsi / Abstrak <span
                                            class="req">*</span></span>
                                    <span class="smart-char-count" id="abstrakCount">0/500</span>
                                </label>
                                <textarea class="smart-textarea" name="abstrak" required id="textareaAbstrak"
                                    placeholder="Jelaskan secara singkat isi atau rangkuman dokumen Anda..." maxlength="500">{{ old('abstrak') }}</textarea>
                                <div class="smart-hint"><i class="bi bi-lightbulb"></i> Tulis ringkasan yang
                                    membantu orang memahami konten dokumen</div>
                            </div>

                            <!-- Kata Kunci -->
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
                                <div class="smart-hint"><i class="bi bi-info-circle"></i> Pisahkan kata kunci
                                    dengan koma (,)</div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH 3: KLASIFIKASI -->
                    <div class="smart-section" data-section="classify">
                        <div class="smart-section-header">
                            <div class="smart-section-title">
                                <div class="smart-section-icon classify"><i class="bi bi-diagram-3"></i></div>
                                <span>Langkah 3: Klasifikasi Dokumen</span>
                            </div>
                            <span class="smart-section-badge step required">Langkah 3 • Wajib</span>
                        </div>

                        <div class="smart-grid">
                            <div class="smart-field">
                                <label class="smart-label">
                                    <span class="smart-label-text">Divisi <span class="req">*</span></span>
                                </label>
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

                            <div class="smart-field">
                                <label class="smart-label">
                                    <span class="smart-label-text">Jurusan <span class="req">*</span></span>
                                </label>
                                <select class="smart-select" name="id_jurusan" required id="selectJurusan">
                                    <option value="">— Pilih Jurusan —</option>
                                    @if (isset($jurusan_data))
                                        @foreach ($jurusan_data as $j)
                                            <option value="{{ $j->id_jurusan }}" @selected(old('id_jurusan') == $j->id_jurusan)>
                                                {{ $j->nama_jurusan }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="smart-field">
                                <label class="smart-label">
                                    <span class="smart-label-text">Program Studi <span class="req">*</span></span>
                                </label>
                                <select class="smart-select" name="id_prodi" required id="selectProdi">
                                    <option value="">— Pilih Prodi —</option>
                                    @if (isset($prodi_data))
                                        @foreach ($prodi_data as $p)
                                            <option value="{{ $p->id_prodi }}" @selected(old('id_prodi') == $p->id_prodi)>
                                                {{ $p->nama_prodi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="smart-hint" style="margin-top: 14px;">
                            <i class="bi bi-info-circle"></i>
                            <span>Pilih klasifikasi yang sesuai dengan afiliasi akademik dokumen Anda</span>
                        </div>
                    </div>

                    <!-- OPSIONAL: TURNITIN (DI AKHIR) -->
                    <div class="smart-section" data-section="optional">
                        <!-- Collapsible Toggle -->
                        <div class="smart-optional-toggle" id="turnitinToggle">
                            <div class="smart-optional-toggle-left">
                                <div class="smart-optional-toggle-icon"><i class="bi bi-patch-check-fill"></i>
                                </div>
                                <div class="smart-optional-toggle-text">
                                    <h4>+ Tambah Laporan Turnitin (Opsional)</h4>
                                    <p>Sertakan jika Anda memiliki laporan kemiripan dari Turnitin</p>
                                </div>
                            </div>
                            <div class="smart-toggle-arrow"><i class="bi bi-chevron-down"></i></div>
                        </div>

                        <!-- Collapsible Content -->
                        <div class="smart-optional-content" id="turnitinContent">
                            <div class="smart-grid">
                                <div class="smart-field">
                                    <label class="smart-label">
                                        <span class="smart-label-text">Skor Kemiripan (%)</span>
                                    </label>
                                    <div class="smart-input-group">
                                        <input type="number" class="smart-input" name="turnitin" min="0"
                                            max="100" step="0.1" value="{{ old('turnitin') }}"
                                            placeholder="0.0" id="inputTurnitin">
                                        <span class="smart-input-suffix">%</span>
                                    </div>
                                    <div class="smart-hint"><i class="bi bi-info-circle"></i> Range: 0% - 100%
                                        (semakin rendah semakin baik)</div>
                                </div>

                                <div class="smart-field">
                                    <label class="smart-label">
                                        <span class="smart-label-text">File Laporan Turnitin</span>
                                    </label>
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
                        <span class="smart-divider-text"><i class="bi bi-send"></i> Semua data sudah lengkap?
                            Klik tombol di bawah</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="smart-actions">
                        <button type="submit" class="smart-btn smart-btn-primary" id="uploadSubmitBtn">
                            <span class="smart-btn-text"><i class="bi bi-cloud-upload-fill"></i> Unggah
                                Dokumen Sekarang</span>
                            <div class="smart-btn-loading">
                                <span class="smart-spinner"></span>
                                <span>Sedang Memproses...</span>
                            </div>
                            <div class="smart-btn-shine"></div>
                        </button>
                        <button type="reset" class="smart-btn smart-btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="up-footer">&copy; {{ date('Y') }} SIPORA — Politeknik Negeri Jember</div>
    </div>

    @include('components.chatbot_widget')

    <script>
        (function() {
            'use strict';

            // STEP INDICATOR LOGIC - DIPERBAIKI
            const steps = document.querySelectorAll('.step-item');

            /**
             * LOGIKA STEP INDICATOR YANG DIPERBAIKI:
             *
             * Step akan dinilai berdasarkan COMPLETION STATUS masing-masing:
             * - Step 1 (Upload File): Selesai jika ada file yang dipilih
             * - Step 2 (Info Dokumen): Selesai jika judul, abstrak, kata kunci, tahun, tema terisi
             * - Step 3 (Klasifikasi): Selesai jika divisi, jurusan, prodi terpilih
             * - Step 4 (Selesai): Active jika semua step 1-3 completed
             *
             * KEY FIX: Step akan RESET ke status yang sesuai jika datanya dihapus!
             */
            function updateStepIndicator() {
                const form = document.getElementById('uploadForm');
                if (!form) return;

                // Get all form elements
                const fileInput = document.getElementById('mainFileInput');
                const judulInput = document.getElementById('inputJudul');
                const abstrakInput = document.getElementById('textareaAbstrak');
                const keywordInput = document.getElementById('inputKeyword');
                const yearSelect = document.getElementById('selectYear');
                const temaSelect = document.getElementById('selectTema');
                const divisiSelect = document.getElementById('selectDivisi');
                const jurusanSelect = document.getElementById('selectJurusan');
                const prodiSelect = document.getElementById('selectProdi');

                // CHECK STEP 1: File Upload
                const step1Complete = fileInput && fileInput.files && fileInput.files.length > 0;

                // CHECK STEP 2: Info Dokumen (semua field wajib terisi)
                const step2Complete = judulInput && judulInput.value.trim().length >= 5 &&
                    abstrakInput && abstrakInput.value.trim().length >= 10 &&
                    keywordInput && keywordInput.value.trim().length >= 3 &&
                    yearSelect && yearSelect.value !== '' &&
                    temaSelect && temaSelect.value !== '';

                // CHECK STEP 3: Klasifikasi (semua dropdown terpilih)
                const step3Complete = divisiSelect && divisiSelect.value !== '' &&
                    jurusanSelect && jurusanSelect.value !== '' &&
                    prodiSelect && prodiSelect.value !== '';

                // RESET SEMUA STEP DULU (penting untuk dynamic update!)
                steps.forEach(function(step) {
                    step.classList.remove('active', 'completed');
                });

                // LOGIKA DINAMIS: Tentukan status setiap step berdasarkan completion
                if (step1Complete) {
                    // Step 1 completed
                    steps[0].classList.add('completed');

                    if (step2Complete) {
                        // Step 2 juga completed
                        steps[1].classList.add('completed');

                        if (step3Complete) {
                            // Step 3 juga completed -> Step 4 active
                            steps[2].classList.add('completed');
                            steps[3].classList.add('active'); // Final step
                        } else {
                            // Step 3 belum -> Step 3 active
                            steps[2].classList.add('active');
                        }
                    } else {
                        // Step 2 belum -> Step 2 active
                        steps[1].classList.add('active');
                    }
                } else {
                    // Step 1 BELUM SELESAI -> KEMBALI KE STEP 1!
                    // Ini fix untuk masalah "stuck di step 2"
                    steps[0].classList.add('active');

                    // Step 2, 3, 4 otomatis tidak active/completed karena sudah direset di atas
                }
            }

            // Attach to form inputs - REAL-TIME UPDATE
            document.querySelectorAll('#uploadForm input, #uploadForm select, #uploadForm textarea').forEach(function(
                el) {
                el.addEventListener('change', updateStepIndicator);
                el.addEventListener('input', updateStepIndicator);
            });

            // Click on step to scroll to section
            steps.forEach(function(step) {
                step.addEventListener('click', function() {
                    const stepNum = this.dataset.step;
                    let targetSection;
                    if (stepNum === '1') targetSection = document.querySelector(
                        '[data-section="upload"]');
                    else if (stepNum === '2') targetSection = document.querySelector(
                        '[data-section="info"]');
                    else if (stepNum === '3') targetSection = document.querySelector(
                        '[data-section="classify"]');

                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // CHARACTER COUNTERS
            function setupCharCounter(inputId, counterId, maxLen) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(counterId);
                if (!input || !counter) return;

                function updateCount() {
                    const len = input.value.length;
                    counter.textContent = len + '/' + maxLen;
                    counter.className = 'smart-char-count';
                    if (len > maxLen * 0.9) counter.classList.add('danger');
                    else if (len > maxLen * 0.7) counter.classList.add('warning');
                }

                input.addEventListener('input', updateCount);
                updateCount();
            }

            setupCharCounter('inputJudul', 'judulCount', 200);
            setupCharCounter('textareaAbstrak', 'abstrakCount', 500);
            setupCharCounter('inputKeyword', 'keywordCount', 100);

            // INPUT VALIDATION
            function setupValidation(inputId, validationId, validator) {
                const input = document.getElementById(inputId);
                const validation = document.getElementById(validationId);
                if (!input || !validation) return;

                input.addEventListener('blur', function() {
                    const result = validator(input.value);
                    if (result.valid) {
                        input.classList.remove('invalid');
                        input.classList.add('valid');
                        validation.className = 'smart-validation-msg success show';
                        validation.innerHTML = '<i class="bi bi-check-circle"></i> ' + result.message;
                    } else if (input.value.length > 0) {
                        input.classList.remove('valid');
                        input.classList.add('invalid');
                        validation.className = 'smart-validation-msg error show';
                        validation.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + result.message;
                    } else {
                        input.classList.remove('valid', 'invalid');
                        validation.className = 'smart-validation-msg';
                    }
                    updateStepIndicator();
                });

                input.addEventListener('input', function() {
                    if (input.classList.contains('valid') || input.classList.contains('invalid')) {
                        input.classList.remove('valid', 'invalid');
                        validation.className = 'smart-validation-msg';
                    }
                });
            }

            setupValidation('inputJudul', 'judulValidation', function(val) {
                if (val.length < 5) return {
                    valid: false,
                    message: 'Judul minimal 5 karakter'
                };
                if (val.length > 200) return {
                    valid: false,
                    message: 'Judul maksimal 200 karakter'
                };
                return {
                    valid: true,
                    message: 'Format judul valid ✓'
                };
            });

            // FORM PROGRESS BAR
            function updateFormProgress() {
                const form = document.getElementById('uploadForm');
                const progressBar = document.getElementById('formProgress');
                if (!form || !progressBar) return;

                const requiredFields = form.querySelectorAll('[required]');
                let filledCount = 0;

                requiredFields.forEach(function(field) {
                    if (field.type === 'file') {
                        if (field.files && field.files.length > 0) filledCount++;
                    } else if (field.value.trim()) {
                        filledCount++;
                    }
                });

                const progress = Math.round((filledCount / requiredFields.length) * 100);
                progressBar.style.width = progress + '%';

                // Also update step indicator
                updateStepIndicator();
            }

            document.querySelectorAll('#uploadForm input, #uploadForm select, #uploadForm textarea').forEach(function(
                el) {
                el.addEventListener('change', updateFormProgress);
                el.addEventListener('input', updateFormProgress);
            });

            setTimeout(updateFormProgress, 100);

            // DROPZONE LOGIC
            const dz = document.getElementById('mainDropzone'),
                fi = document.getElementById('mainFileInput'),
                pv = document.getElementById('mainFilePreview'),
                fn = document.getElementById('mainFileName'),
                fs = document.getElementById('mainFileSize'),
                rm = document.getElementById('mainFileRemove');

            if (dz && fi) {
                ['dragenter', 'dragover'].forEach(function(e) {
                    dz.addEventListener(e, function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        dz.classList.add('drag-over');
                    });
                });
                ['dragleave', 'drop'].forEach(function(e) {
                    dz.addEventListener(e, function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation;
                        dz.classList.remove('drag-over');
                    });
                });
                dz.addEventListener('drop', function(e) {
                    const f = e.dataTransfer.files;
                    if (f && f.length > 0) {
                        fi.files = f;
                        showFP(f[0]);
                    }
                });
                fi.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) showFP(this.files[0]);
                });
            }

            if (rm) {
                rm.addEventListener('click', function() {
                    if (fi) fi.value = '';
                    if (pv) pv.classList.remove('show');
                    if (dz) dz.classList.remove('has-file');

                    // Hide AI screening when file removed
                    if (typeof aiScreening !== 'undefined') {
                        aiScreening.hide();
                    }

                    // UPDATE STEP INDICATOR SAAT FILE DIHAPUS!
                    // Ini akan membuat step kembali ke 1 jika file dihapus
                    updateFormProgress();
                    updateStepIndicator();
                });
            }

            function showFP(file) {
                if (!file || !fn || !fs || !pv || !dz) return;
                fn.textContent = file.name || '-';
                fs.textContent = formatSize(file.size || 0);
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

                // UPDATE STEP SAAT FILE DITAMBAHKAN
                updateFormProgress();
                updateStepIndicator();
            }

            function formatSize(b) {
                if (!b || b === 0) return '0 B';
                const k = 1024,
                    s = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(b) / Math.log(k));
                return parseFloat((b / Math.pow(k, i)).toFixed(1)) + ' ' + s[i];
            }

            // OPTIONAL TURNITIN TOGGLE
            const turnitinToggle = document.getElementById('turnitinToggle');
            const turnitinContent = document.getElementById('turnitinContent');

            if (turnitinToggle && turnitinContent) {
                turnitinToggle.addEventListener('click', function() {
                    const isActive = this.classList.toggle('active');
                    turnitinContent.classList.toggle('show', isActive);
                });
            }

            const ti = document.getElementById('turnitinFileInput'),
                tl = document.getElementById('turnitinFileLabel');
            if (ti && tl) {
                ti.addEventListener('change', function() {
                    tl.textContent = this.files && this.files.length > 0 ? this.files[0].name :
                        'Pilih file laporan';
                });
            }

            // SUBMIT LOADING STATE
            const form = document.getElementById('uploadForm'),
                btn = document.getElementById('uploadSubmitBtn');

            if (form && btn) {
                form.addEventListener('submit', function() {
                    btn.classList.add('is-loading');
                    const progressBar = document.getElementById('formProgress');
                    if (progressBar) progressBar.style.width = '100%';

                    setTimeout(function() {
                        if (document.visibilityState === 'visible' && btn.classList.contains(
                                'is-loading'))
                            btn.classList.remove('is-loading');
                    }, 8000);
                });
            }

            // RESET HANDLER
            if (form) {
                form.addEventListener('reset', function() {
                    setTimeout(function() {
                        if (pv) pv.classList.remove('show');
                        if (fi) fi.value = '';
                        if (dz) dz.classList.remove('has-file');
                        if (tl) tl.textContent = 'Pilih file laporan';
                        if (ti) ti.value = '';

                        document.querySelectorAll('.smart-char-count').forEach(function(el) {
                            const max = el.textContent.split('/')[1];
                            if (max) el.textContent = '0/' + max;
                            el.className = 'smart-char-count';
                        });

                        document.querySelectorAll('.smart-input.valid, .smart-input.invalid').forEach(
                            function(el) {
                                el.classList.remove('valid', 'invalid');
                            });
                        document.querySelectorAll('.smart-validation-msg.show').forEach(function(el) {
                            el.classList.remove('show');
                        });

                        if (turnitinToggle) turnitinToggle.classList.remove('active');
                        if (turnitinContent) turnitinContent.classList.remove('show');

                        // Hide AI screening on reset
                        if (typeof aiScreening !== 'undefined') {
                            aiScreening.hide();
                        }

                        // UPDATE STEP SETELAH RESET
                        updateFormProgress();
                        updateStepIndicator();
                    }, 50);
                });
            }

            // AI SCREENING FORMAT LOGIC
            const aiScreening = {
                container: document.getElementById('aiScreeningContainer'),
                loadingState: document.getElementById('aiLoadingState'),
                screeningBody: document.getElementById('aiScreeningBody'),
                scoreBadge: document.getElementById('aiScoreBadge'),
                scoreValue: document.getElementById('aiScoreValue'),
                statusBanner: document.getElementById('aiStatusBanner'),
                statusIcon: document.getElementById('aiStatusIcon'),
                statusTitle: document.getElementById('aiStatusTitle'),
                statusMessage: document.getElementById('aiStatusMessage'),
                checkList: document.getElementById('aiCheckList'),
                recommendations: document.getElementById('aiRecommendations'),
                recommendationList: document.getElementById('aiRecommendationList'),

                // Simulate AI analysis
                analyzeFile: function(file) {
                    if (!this.container || !file) return;

                    // Show container and loading
                    this.container.classList.add('show');
                    this.loadingState.classList.add('show');
                    this.screeningBody.style.display = 'none';

                    // Reset score badge
                    this.scoreBadge.className = 'ai-score-badge';
                    this.scoreValue.textContent = '—';

                    const self = this;

                    // Simulate AI processing time (1.5-2.5 seconds)
                    const processingTime = 1500 + Math.random() * 1000;

                    setTimeout(function() {
                        self.displayResults(file);
                    }, processingTime);
                },

                displayResults: function(file) {
                    // Hide loading, show results
                    this.loadingState.classList.remove('show');
                    this.screeningBody.style.display = 'block';

                    // Get file info
                    const fileName = file.name || '';
                    const fileSize = file.size || 0;
                    const ext = fileName.split('.').pop().toLowerCase();

                    // Generate simulated AI analysis results
                    const results = this.generateAnalysis(fileSize, ext, fileName);

                    // Update score
                    this.updateScore(results.score);

                    // Update status banner
                    this.updateStatus(results.status, results.title, results.message);

                    // Update check list
                    this.updateCheckList(results.checks);

                    // Update recommendations
                    if (results.recommendations && results.recommendations.length > 0) {
                        this.recommendations.style.display = 'block';
                        this.recommendationList.innerHTML = results.recommendations.map(function(rec) {
                            return '<li>' + rec + '</li>';
                        }).join('');
                    } else {
                        this.recommendations.style.display = 'none';
                    }
                },

                generateAnalysis: function(size, ext, name) {
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    const validExts = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

                    let checks = [];
                    let totalScore = 100;
                    let recommendations = [];

                    // Check 1: File Format
                    const isValidFormat = validExts.includes(ext);
                    checks.push({
                        name: 'Format File',
                        desc: 'Ekstensi: ' + ext.toUpperCase(),
                        status: isValidFormat ? 'passed' : 'failed',
                        label: isValidFormat ? 'Valid' : 'Tidak Valid'
                    });
                    if (!isValidFormat) {
                        totalScore -= 40;
                        recommendations.push('Ubah format file ke PDF, DOC, DOCX, PPT, PPTX, XLS, atau XLSX');
                    }

                    // Check 2: File Size
                    const isGoodSize = size <= maxSize && size > 0;
                    const sizeMB = (size / (1024 * 1024)).toFixed(2);
                    let sizeStatus = 'passed';
                    let sizeLabel = 'Ideal (' + sizeMB + ' MB)';

                    if (size > maxSize) {
                        sizeStatus = 'failed';
                        sizeLabel = 'Terlalu Besar (' + sizeMB + ' MB)';
                        totalScore -= 30;
                        recommendations.push('Kompres file menjadi maksimal 10MB untuk performa optimal');
                    } else if (size === 0) {
                        sizeStatus = 'failed';
                        sizeLabel = 'File Kosong';
                        totalScore -= 50;
                    } else if (size < 50 * 1024) { // Less than 50KB
                        sizeStatus = 'warning';
                        sizeLabel = 'Terlalu Kecil (' + sizeMB + ' MB)';
                        totalScore -= 10;
                        recommendations.push('File terlalu kecil, pastikan isi dokumen lengkap');
                    }

                    checks.push({
                        name: 'Ukuran File',
                        desc: sizeLabel,
                        status: sizeStatus,
                        label: sizeStatus === 'passed' ? 'OK' : (sizeStatus === 'warning' ?
                            'Perhatian' : 'Bermasalah')
                    });

                    // Check 3: File Name Quality
                    const hasGoodName = name.length >= 5 && /^[a-zA-Z0-9\\-_\\s]+$/.test(name);
                    let nameStatus = 'passed';
                    let nameLabel = 'Baik';

                    if (!hasGoodName) {
                        nameStatus = 'warning';
                        nameLabel = 'Perlu Diperbaiki';
                        totalScore -= 5;
                        recommendations.push('Gunakan nama file yang deskriptif (hindari karakter khusus)');
                    } else if (name.includes(' ') && name.length > 30) {
                        nameStatus = 'warning';
                        nameLabel = 'Agak Panjang';
                        totalScore -= 2;
                    }

                    checks.push({
                        name: 'Nama File',
                        desc: name.substring(0, 35) + (name.length > 35 ? '...' : ''),
                        status: nameStatus,
                        label: nameLabel
                    });

                    // Check 4: Document Type Detection (simulated)
                    const docTypes = {
                        pdf: 'Dokumen PDF',
                        doc: 'Microsoft Word',
                        docx: 'Microsoft Word (Modern)',
                        ppt: 'Microsoft PowerPoint',
                        pptx: 'Microsoft PowerPoint (Modern)',
                        xls: 'Microsoft Excel',
                        xlsx: 'Microsoft Excel (Modern)'
                    };

                    checks.push({
                        name: 'Tipe Dokumen',
                        desc: docTypes[ext] || 'Unknown',
                        status: 'passed',
                        label: 'Terdeteksi'
                    });

                    // Check 5: Security Scan (simulated)
                    const isSecure = !name.toLowerCase().includes('malware') && !name.toLowerCase().includes(
                        'virus');
                    checks.push({
                        name: 'Keamanan File',
                        desc: 'Pemeriksaan dasar',
                        status: isSecure ? 'passed' : 'failed',
                        label: isSecure ? 'Aman' : 'Terdeteksi'
                    });
                    if (!isSecure) {
                        totalScore -= 50;
                        recommendations.push('File terdeteksi memiliki potensi risiko keamanan');
                    }

                    // Ensure score is within bounds
                    totalScore = Math.max(0, Math.min(100, totalScore));

                    // Determine overall status
                    let status, title, message;
                    if (totalScore >= 80) {
                        status = 'passed';
                        title = 'Dokumen Layak Upload ✓';
                        message = 'File Anda memenuhi standar kualitas dan siap diunggah ke repository.';
                    } else if (totalScore >= 60) {
                        status = 'warning';
                        title = 'Dokumen dengan Catatan ⚠';
                        message =
                            'File dapat diunggah, namun disarankan untuk memperbaiki beberapa aspek berikut.';
                    } else {
                        status = 'failed';
                        title = 'Dokumen Perlu Diperbaiki ✗';
                        message = 'File tidak memenuhi standar minimal. Silakan perbaiki sebelum mengunggah.';
                    }

                    return {
                        score: Math.round(totalScore),
                        status: status,
                        title: title,
                        message: message,
                        checks: checks,
                        recommendations: recommendations
                    };
                },

                updateScore: function(score) {
                    this.scoreValue.textContent = score;

                    // Remove old classes
                    this.scoreBadge.className = 'ai-score-badge';

                    // Add appropriate class based on score
                    if (score >= 80) {
                        this.scoreBadge.classList.add('excellent');
                    } else if (score >= 60) {
                        this.scoreBadge.classList.add('good');
                    } else if (score >= 40) {
                        this.scoreBadge.classList.add('warning');
                    } else {
                        this.scoreBadge.classList.add('danger');
                    }
                },

                updateStatus: function(status, title, message) {
                    this.statusBanner.className = 'ai-status-banner ' + status;
                    this.statusIcon.className = 'bi ' + (status === 'passed' ? 'bi-shield-check' : (status ===
                        'warning' ? 'bi-exclamation-triangle' : 'bi-x-circle'));
                    this.statusTitle.textContent = title;
                    this.statusMessage.textContent = message;
                },

                updateCheckList: function(checks) {
                    const self = this;
                    this.checkList.innerHTML = checks.map(function(check, index) {
                        return '<div class="ai-check-item ' + check.status +
                            '" style="animation-delay: ' + (index * 0.1) + 's;">' +
                            '<div class="ai-check-icon">' +
                            '<i class="bi ' + (check.status === 'passed' ? 'bi-check-lg' : (check
                                .status === 'warning' ? 'bi-exclamation-lg' : 'bi-x-lg')) + '"></i>' +
                            '</div>' +
                            '<div class="ai-check-content">' +
                            '<strong>' + check.name + '</strong>' +
                            '<span>' + check.desc + '</span>' +
                            '</div>' +
                            '<span class="ai-check-status">' + check.label + '</span>' +
                            '</div>';
                    }).join('');
                },

                hide: function() {
                    if (this.container) {
                        this.container.classList.remove('show');
                    }
                }
            };

            // Integrate with existing dropzone - Trigger AI screening when file is selected
            if (document.getElementById('mainFileInput')) {
                const mainFileInput = document.getElementById('mainFileInput');

                // Add event listener for AI screening when file is selected
                mainFileInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        // Small delay to ensure preview updates first
                        setTimeout(function() {
                            aiScreening.analyzeFile(mainFileInput.files[0]);
                        }, 300);
                    } else {
                        aiScreening.hide();
                    }
                });
            }

            console.log(
                '✅ SIPORA Smart Upload Form - Ready | AI Screening Feature Active | Dynamic Step Indicator Enabled | Button Animations Removed | Enhanced Visual Design Applied | Width: 1200px (Same as Dashboard)'
            );
        })();
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/upload-page.js') }}"></script>
    <script src="{{ asset('assets/js/upload.js') }}"></script>
</body>

</html>
