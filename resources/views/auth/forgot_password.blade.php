<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SIPORA POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">

    <style>
        html,
        body {
            overflow-x: hidden;
            min-height: 100%;
        }

        body {
            background: #f0f3fb !important;
            -webkit-font-smoothing: antialiased;
            font-family: 'Inter', sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(ellipse 60% 40% at 100% 0%, rgba(99, 102, 241, .10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 35% at 0% 100%, rgba(20, 184, 166, .08) 0%, transparent 60%),
                #f0f3fb;
        }

        /* Container - Compact Width */
        .reset-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reset-container.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Card - Compact Size */
        .reset-card {
            width: 100%;
            max-width: 460px;
            border-radius: 20px !important;
            box-shadow: 0 10px 36px rgba(26, 86, 214, .22), 0 2px 8px rgba(15, 23, 42, .08) !important;
            overflow: hidden;
            position: relative;
            background: #fff;
        }

        /* Left Panel - Compact */
        .reset-card-left {
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%) !important;
            padding: 32px 28px !important;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .reset-card-left h1 {
            font-family: 'Sora', sans-serif !important;
            font-weight: 600 !important;
            color: #fff !important;
            font-size: 1.35rem !important;
            margin-top: 18px !important;
            margin-bottom: 10px !important;
            letter-spacing: -.2px !important;
        }

        .reset-card-left p {
            font-family: 'Inter', sans-serif !important;
            font-weight: 400 !important;
            color: rgba(255, 255, 255, .82) !important;
            font-size: .85rem !important;
            line-height: 1.55 !important;
        }

        /* Right Panel - Compact */
        .reset-card-right {
            padding: 28px 28px 32px !important;
            position: relative;
            background: #fff;
        }

        /* BUTTON PRIMARY - WHITE TEXT & ICON */
        .btn-primary {
            background: linear-gradient(130deg, #1a56d6, #6366f1) !important;
            box-shadow: 0 4px 14px rgba(26, 86, 214, .28) !important;
            border-radius: 10px !important;
            border: none !important;
            font-family: 'Sora', sans-serif !important;
            letter-spacing: .25px !important;
            transition: all .25s ease !important;
            position: relative;
            overflow: hidden;
            color: #ffffff !important;
            /* ← TEKS PUTIH */
            font-weight: 600 !important;
        }

        .btn-primary i {
            color: #ffffff !important;
            /* ← ICON PUTIH */
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(26, 86, 214, .38) !important;
            transform: translateY(-1px);
            color: #ffffff !important;
            /* ← Tetap putih saat hover */
        }

        .btn-primary:hover i {
            color: #ffffff !important;
            /* ← Icon tetap putih saat hover */
        }

        .form-input {
            border-radius: 10px !important;
            border: 1.5px solid #e4e9f5 !important;
            font-family: 'Inter', sans-serif !important;
            transition: all .22s !important;
            width: 100%;
            padding: 11px 14px !important;
            font-size: .9rem !important;
        }

        .form-input:focus {
            border-color: #1a56d6 !important;
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .10), 0 1px 3px rgba(26, 86, 214, .06) !important;
            outline: none;
        }

        .form-input.input-error {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, .10) !important;
        }

        .form-label {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #0f172a;
            font-size: 13px;
            letter-spacing: .2px;
            display: block;
            margin-bottom: 6px;
        }

        .alert-error {
            background: #fef2f2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
            border-radius: 12px !important;
            padding: 11px 14px !important;
            font-size: .83rem !important;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px !important;
        }

        .alert-success {
            background: #f0fdf4 !important;
            color: #166534 !important;
            border: 1px solid #bbf7d0 !important;
            border-radius: 12px !important;
            padding: 11px 14px !important;
            font-size: .83rem !important;
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 16px !important;
        }

        .email-warning {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: .77rem;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 8px;
            animation: premAlertIn .35s ease forwards;
            font-weight: 400;
        }

        .email-warning.hidden {
            display: none !important;
        }

        .email-warning i {
            font-size: .8rem;
            flex-shrink: 0;
        }

        @keyframes premAlertIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Info Box - Compact */
        .info-box {
            background: linear-gradient(135deg, #eff6ff, #f0f9ff);
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            padding: 14px 15px;
            margin-bottom: 18px;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .4s ease, transform .4s ease;
        }

        .info-box.in {
            opacity: 1;
            transform: translateY(0);
        }

        .info-box-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: .84rem;
            color: #1e40af;
            margin-bottom: 9px;
        }

        .info-box-title i {
            font-size: .95rem;
        }

        .info-box-content p {
            font-size: .8rem;
            color: #475569;
            line-height: 1.55;
            margin-bottom: 5px;
            font-weight: 400;
        }

        .info-box-content p:last-child {
            margin-bottom: 0;
        }

        .info-box-content strong {
            color: #1e40af;
            font-weight: 600;
        }

        /* Reset Link Box */
        .reset-link-box {
            background: #fefce8;
            border: 1.5px solid #fde047;
            border-radius: 10px;
            padding: 12px 14px;
            margin-top: 12px;
            font-size: .8rem;
        }

        .reset-link-box strong {
            display: block;
            color: #854d0e;
            margin-bottom: 7px;
            font-family: 'Sora', sans-serif;
            font-size: .82rem;
        }

        .reset-link-box a {
            color: #ca8a04;
            word-break: break-all;
            text-decoration: underline;
        }

        .reset-link-box a:hover {
            color: #a16207;
        }

        /* Back Link */
        .back-to-login {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: .83rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 18px;
            transition: all .2s ease;
            opacity: 0;
            transform: translateX(-8px);
        }

        .back-to-login.show {
            opacity: 1;
            transform: translateX(0);
        }

        .back-to-login:hover {
            color: #1a56d6;
            gap: 9px;
        }

        .back-to-login i {
            font-size: .88rem;
            transition: transform .2s ease;
        }

        .back-to-login:hover i {
            transform: translateX(-3px);
        }

        /* Success Modal - Compact */
        .reset-success-overlay {
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(11, 27, 77, .45);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .reset-success-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .reset-success-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px 28px;
            text-align: center;
            max-width: 380px;
            width: 100%;
            box-shadow: 0 20px 56px rgba(11, 27, 77, .22), 0 0 0 1px rgba(255, 255, 255, .1);
            transform: translateY(18px) scale(.96);
            transition: transform .35s cubic-bezier(.22, .68, 0, 1.1);
        }

        .reset-success-overlay.active .reset-success-card {
            transform: translateY(0) scale(1);
        }

        .reset-success-card h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .reset-success-card p {
            font-size: .83rem;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .reset-success-actions .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 28px;
            font-size: .85rem;
        }

        /* BG Orbs - Subtle */
        .prem-bg-orbs {
            position: fixed;
            inset: 0;
            z-index: 0;
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
            width: 380px;
            height: 260px;
            top: -8%;
            right: -5%;
            background: rgba(99, 102, 241, .09);
            animation: premBgOrbIn 2s .2s ease forwards, premBgOrb1 25s 2s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(2) {
            width: 320px;
            height: 200px;
            bottom: -5%;
            left: -3%;
            background: rgba(20, 184, 166, .07);
            animation: premBgOrbIn 2s .5s ease forwards, premBgOrb2 28s 2.5s ease-in-out infinite;
        }

        .prem-bg-orb:nth-child(3) {
            width: 260px;
            height: 260px;
            top: 40%;
            left: 25%;
            background: rgba(26, 86, 214, .06);
            animation: premBgOrbIn 2s .8s ease forwards, premBgOrb3 22s 3s ease-in-out infinite;
        }

        @keyframes premBgOrbIn {
            to {
                opacity: 1
            }
        }

        @keyframes premBgOrb1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            33% {
                transform: translate(50px, 20px) scale(1.08)
            }

            66% {
                transform: translate(-25px, -12px) scale(.94)
            }
        }

        @keyframes premBgOrb2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            33% {
                transform: translate(-40px, -25px) scale(1.06)
            }

            66% {
                transform: translate(30px, 15px) scale(.93)
            }
        }

        @keyframes premBgOrb3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(25px, -20px) scale(1.1)
            }
        }

        /* Particles - Fewer */
        .prem-bg-particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .prem-bg-dot {
            position: absolute;
            border-radius: 50%;
            opacity: 0;
            animation: premBgDotIn 1.5s ease forwards;
        }

        .prem-bg-dot:nth-child(1) {
            width: 4px;
            height: 4px;
            top: 12%;
            left: 8%;
            background: rgba(99, 102, 241, .25);
            animation: premBgDotIn 1.5s .3s ease forwards, premBgDotFloat 12s 1.8s ease-in-out infinite;
        }

        .prem-bg-dot:nth-child(2) {
            width: 3px;
            height: 3px;
            top: 25%;
            right: 12%;
            background: rgba(26, 86, 214, .2);
            animation: premBgDotIn 1.5s .6s ease forwards, premBgDotFloat 14s 2.5s ease-in-out infinite;
        }

        .prem-bg-dot:nth-child(3) {
            width: 4px;
            height: 4px;
            bottom: 20%;
            left: 15%;
            background: rgba(20, 184, 166, .18);
            animation: premBgDotIn 1.5s .9s ease forwards, premBgDotFloat 10s 3.2s ease-in-out infinite;
        }

        .prem-bg-dot:nth-child(4) {
            width: 3px;
            height: 3px;
            top: 60%;
            right: 8%;
            background: rgba(56, 189, 248, .2);
            animation: premBgDotIn 1.5s 1.2s ease forwards, premBgDotFloat 16s 4s ease-in-out infinite;
        }

        @keyframes premBgDotIn {
            to {
                opacity: 1
            }
        }

        @keyframes premBgDotFloat {

            0%,
            100% {
                transform: translate(0, 0)
            }

            25% {
                transform: translate(12px, -18px)
            }

            50% {
                transform: translate(-8px, -28px)
            }

            75% {
                transform: translate(15px, -12px)
            }
        }

        /* LOGO - Smaller */
        .prem-logo-wrap {
            position: relative !important;
            display: inline-flex !important;
            width: 72px !important;
            height: 72px !important;
            opacity: 0;
            transform: scale(.6);
        }

        .prem-logo-wrap.enter {
            animation: premLogoEnter .8s cubic-bezier(.34, 1.56, .64, 1) forwards !important;
        }

        @keyframes premLogoEnter {
            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        .prem-logo-float {
            animation: premLogoFloat 5s ease-in-out infinite !important;
        }

        @keyframes premLogoFloat {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        .prem-logo-ring {
            position: absolute !important;
            inset: -10px !important;
            border-radius: 50% !important;
            border: 2px dashed rgba(255, 255, 255, .18) !important;
            animation: premLogoRingSpin 22s linear infinite !important;
            pointer-events: none !important;
            opacity: 0;
            z-index: 1;
        }

        .prem-logo-ring.go {
            opacity: 1;
            transition: opacity .8s .3s ease !important;
        }

        @keyframes premLogoRingSpin {
            to {
                transform: rotate(360deg)
            }
        }

        .prem-logo-ring-2 {
            position: absolute !important;
            inset: -18px !important;
            border-radius: 50% !important;
            border: 1px dashed rgba(255, 255, 255, .08) !important;
            animation: premLogoRingSpin2 30s linear infinite reverse !important;
            pointer-events: none !important;
            opacity: 0;
            z-index: 1;
        }

        .prem-logo-ring-2.go {
            opacity: 1;
            transition: opacity .8s .5s ease !important;
        }

        @keyframes premLogoRingSpin2 {
            to {
                transform: rotate(360deg)
            }
        }

        .prem-logo-glow {
            position: absolute !important;
            inset: -26px !important;
            border-radius: 50% !important;
            background: conic-gradient(from 0deg, rgba(147, 197, 253, .18), rgba(196, 181, 253, .10), rgba(56, 189, 248, .06), rgba(99, 102, 241, .12), rgba(147, 197, 253, .18)) !important;
            filter: blur(20px) !important;
            animation: premLogoGlowSpin 10s linear infinite, premLogoGlowPulse 4s ease-in-out infinite !important;
            pointer-events: none !important;
            opacity: 0;
            z-index: 0;
        }

        .prem-logo-glow.go {
            opacity: 1;
            transition: opacity 1s .4s ease !important;
        }

        @keyframes premLogoGlowSpin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes premLogoGlowPulse {

            0%,
            100% {
                scale: 1;
                opacity: var(--glow-o, 1)
            }

            50% {
                scale: 1.12;
                opacity: calc(var(--glow-o, 1)*.6)
            }
        }

        /* LEFT PANEL Decorations - Subtle */
        .prem-left-dots {
            position: absolute !important;
            inset: 0 !important;
            background-image: radial-gradient(rgba(255, 255, 255, .10) 1px, transparent 1px) !important;
            background-size: 20px 20px !important;
            opacity: .4 !important;
            pointer-events: none !important;
            z-index: 0 !important;
        }

        .prem-left-circ {
            position: absolute !important;
            border-radius: 50% !important;
            pointer-events: none !important;
            z-index: 0 !important;
        }

        .prem-left-circ-1 {
            width: 180px !important;
            height: 180px !important;
            border: 35px solid rgba(255, 255, 255, .04) !important;
            top: -80px !important;
            right: -50px !important;
            animation: premLeftCircFloat 20s ease-in-out infinite !important;
        }

        .prem-left-circ-2 {
            width: 110px !important;
            height: 110px !important;
            border: 22px solid rgba(255, 255, 255, .04) !important;
            bottom: -40px !important;
            left: 25% !important;
            animation: premLeftCircFloat 16s 3s ease-in-out infinite reverse !important;
        }

        @keyframes premLeftCircFloat {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg)
            }

            50% {
                transform: translate(8px, -8px) rotate(3deg)
            }
        }

        /* RIGHT PANEL Accent */
        .prem-right-accent {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 3px !important;
            background: linear-gradient(90deg, #1a56d6, #6366f1, #38bdf8) !important;
            z-index: 2 !important;
        }

        /* Trust Line */
        .prem-trust-line {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            margin-top: 14px !important;
            font-size: 11px !important;
            color: #94a3b8 !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 400 !important;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .5s ease, transform .5s ease;
        }

        .prem-trust-line.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-trust-line i {
            font-size: 9px !important;
            color: #64748b !important;
            animation: premLockPulse 3s ease-in-out infinite;
        }

        @keyframes premLockPulse {

            0%,
            100% {
                opacity: .4
            }

            50% {
                opacity: 1
            }
        }

        /* Button Shine Effect */
        .prem-btn-shine {
            position: absolute;
            top: 0;
            left: -110%;
            width: 55%;
            height: 100%;
            background: linear-gradient(105deg, transparent 30%, rgba(255, 255, 255, .15) 50%, transparent 70%);
            transform: skewX(-15deg);
            pointer-events: none;
            z-index: 1;
        }

        @keyframes premBtnShineGo {
            0% {
                left: -110%
            }

            100% {
                left: 160%
            }
        }

        /* Form Group Animation */
        .form-group {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity .4s ease, transform .4s ease;
        }

        .form-group.in {
            opacity: 1;
            transform: translateY(0);
        }

        /* Loading Spinner - PUTIH */
        .btn-primary .loading {
            display: none;
            width: 17px;
            height: 17px;
            border: 2px solid rgba(255, 255, 255, .3);
            /* ← Border putih transparan */
            border-top-color: #ffffff;
            /* ← Top border putih solid */
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        .btn-primary.loading .btn-text {
            opacity: 0;
        }

        .btn-primary.loading .loading {
            display: block;
            position: absolute;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Logo Circle */
        .logo-circle {
            background: transparent !important;
            border: none !important;
            backdrop-filter: none !important;
            box-shadow: none !important;
            border-radius: 50% !important;
            width: 100% !important;
            height: 100% !important;
            transition: transform .3s !important;
        }

        .logo-circle img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            mix-blend-mode: screen !important;
        }

        .logo-container:hover .logo-circle {
            transform: scale(1.06) !important;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .reset-container {
                padding: 16px 12px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .reset-card {
                border-radius: 16px !important;
            }

            .reset-card-left {
                padding: 26px 22px !important;
            }

            .reset-card-right {
                padding: 24px 20px 28px !important;
            }

            .reset-card-left h1 {
                font-size: 1.2rem !important;
            }

            .prem-logo-wrap {
                width: 64px !important;
                height: 64px !important;
            }

            .prem-logo-ring,
            .prem-logo-ring-2 {
                display: none;
            }

            .prem-logo-glow {
                filter: blur(14px) !important;
            }

            .prem-bg-orb {
                filter: blur(30px);
            }

            .prem-bg-particles {
                display: none;
            }

            .prem-left-circ-1 {
                width: 140px !important;
                height: 140px !important;
                border-width: 28px !important;
            }

            .prem-left-circ-2 {
                width: 80px !important;
                height: 80px !important;
                border-width: 18px !important;
            }

            html,
            body {
                overflow: auto;
            }
        }

        @media (max-width: 380px) {
            .reset-container {
                padding: 12px 10px;
            }

            .reset-card-left,
            .reset-card-right {
                padding-left: 18px !important;
                padding-right: 18px !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .prem-bg-orb,
            .prem-bg-dot,
            .prem-logo-float,
            .prem-logo-ring,
            .prem-logo-ring-2,
            .prem-logo-glow,
            .prem-left-circ,
            .prem-trust-line i {
                animation: none !important;
            }

            .prem-logo-wrap.enter {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }

            .prem-trust-line {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body data-login-page="{{ route('login') }}">

    <!-- Background Effects -->
    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>

    <div class="prem-bg-particles">
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
    </div>

    <!-- Main Container - COMPACT WIDTH -->
    <div class="reset-container">
        <div class="reset-card" style="position:relative;">

            <!-- Left Panel - Header Section -->
            <div class="reset-card-left">
                <div class="prem-left-dots"></div>
                <div class="prem-left-circ prem-left-circ-1"></div>
                <div class="prem-left-circ prem-left-circ-2"></div>

                <!-- Logo with Premium Effects -->
                <div class="logo-container" style="display:inline-block;">
                    <div class="prem-logo-wrap">
                        <div class="prem-logo-glow"></div>
                        <div class="prem-logo-ring"></div>
                        <div class="prem-logo-ring-2"></div>
                        <div class="prem-logo-float">
                            <div class="logo-circle">
                                <img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije">
                            </div>
                        </div>
                    </div>
                </div>

                <h1>Lupa Kata Sandi?</h1>
                <p>Masukkan email Anda dan kami akan mengirimkan link untuk mereset kata sandi.</p>
            </div>

            <!-- Right Panel - Form Section -->
            <div class="reset-card-right" style="position:relative;">
                <div class="prem-right-accent"></div>

                <!-- Back to Login -->
                <a href="{{ route('login') }}" class="back-to-login">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Halaman Login
                </a>

                <!-- Error Messages -->
                @if (session('forgot_error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('forgot_error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('forgot_success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="font-size:1.05rem;margin-top:2px;"></i>
                        <span>{{ session('forgot_success') }}</span>
                    </div>
                    @if (session('reset_link'))
                        <div class="reset-link-box">
                            <strong><i class="bi bi-link-45deg"></i> Link Reset (Dev/Testing):</strong>
                            <a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a>
                        </div>
                    @endif
                @endif

                <!-- Info Box -->
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Informasi Penting
                    </div>
                    <div class="info-box-content">
                        <p>Hanya email dengan domain <strong>.ac.id</strong> yang dapat menggunakan fitur ini.</p>
                        <p>Link reset berlaku selama <strong>60 menit</strong>.</p>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                    @csrf

                    <div class="form-group" style="margin-bottom:18px;">
                        <label class="form-label" for="email">Email Akun</label>

                        <!-- INPUT TANPA ICON -->
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="Masukkan Email Anda" required value="{{ old('email') }}">

                        <div class="email-warning hidden" id="emailWarning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Hanya email dengan domain .ac.id yang diizinkan</span>
                        </div>
                    </div>

                    <!-- BUTTON DENGAN TEKS & ICON PUTIH -->
                    <button type="submit" class="btn-primary" id="submitBtn"
                        style="width:100%;padding:12px;font-size:.88rem;position:relative;">
                        <span class="btn-text">
                            <i class="bi bi-send-fill" style="margin-right:7px;color:#ffffff;"></i>
                            <span style="color:#ffffff;">Kirim Link Reset</span>
                        </span>
                        <span class="loading"></span>
                    </button>
                </form>

                <!-- Trust Line -->
                <div class="prem-trust-line">
                    <i class="fas fa-lock"></i>
                    <span>Data dilindungi enkripsi SSL 256-bit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="resetSuccessModal" class="reset-success-overlay" style="display:none;">
        <div class="reset-success-card" role="dialog" aria-modal="true" aria-labelledby="resetSuccessTitle">
            <div
                style="width:58px;height:58px;border-radius:50%;background:linear-gradient(135deg,#22c55e,#16a34a);display:inline-flex;align-items:center;justify-content:center;font-size:26px;color:#fff;margin-bottom:16px;box-shadow:0 8px 24px rgba(34,197,94,.32);">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h2 id="resetSuccessTitle">Email Terkirim!</h2>
            <p id="resetSuccessMessage">Link reset kata sandi telah dikirim ke email Anda. Silakan periksa inbox atau
                folder spam.</p>
            <div class="reset-success-actions">
                <button id="goToLoginBtn" class="btn-primary">
                    <i class="bi bi-box-arrow-in-right" style="margin-right:7px;color:#ffffff;"></i>
                    <span style="color:#ffffff;">Kembali ke Login</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // Entrance Animation
            var ctr = document.querySelector('.reset-container');
            if (ctr) setTimeout(function() {
                ctr.classList.add('visible');
            }, 150);

            // Form Groups & Info Box Animation
            var groups = document.querySelectorAll('.form-group, .info-box');
            if (groups.length) groups.forEach(function(el, i) {
                setTimeout(function() {
                    el.classList.add('in');
                }, 350 + i * 90);
            });

            // Back Link Animation
            setTimeout(function() {
                var back = document.querySelector('.back-to-login');
                if (back) back.classList.add('show');
            }, 250);

            // Logo Animations
            setTimeout(function() {
                var w = document.querySelector('.prem-logo-wrap');
                if (w) w.classList.add('enter');
            }, 200);

            setTimeout(function() {
                var r = document.querySelector('.prem-logo-ring');
                if (r) r.classList.add('go');
            }, 400);

            setTimeout(function() {
                var r2 = document.querySelector('.prem-logo-ring-2');
                if (r2) r2.classList.add('go');
            }, 550);

            setTimeout(function() {
                var g = document.querySelector('.prem-logo-glow');
                if (g) g.classList.add('go');
            }, 350);

            // Trust Line
            setTimeout(function() {
                var t = document.querySelector('.prem-trust-line');
                if (t) t.classList.add('show');
            }, 650);

            // Button Shine Effect
            var btn = document.querySelector('#submitBtn');
            if (btn) {
                var bs = document.createElement('div');
                bs.className = 'prem-btn-shine';
                btn.appendChild(bs);

                btn.addEventListener('mouseenter', function() {
                    bs.style.animation = 'none';
                    void bs.offsetWidth;
                    bs.style.animation = 'premBtnShineGo .6s ease forwards';
                });

                bs.addEventListener('animationend', function() {
                    bs.style.animation = 'none';
                });
            }

            // Modal Functions
            function openModal(id) {
                var m = document.getElementById(id);
                if (!m) return;

                m.style.display = 'flex';
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        m.classList.add('active');
                    });
                });

                document.body.style.overflow = 'hidden';
            }

            function closeModal(id) {
                var m = document.getElementById(id);
                if (!m) return;

                m.classList.remove('active');
                setTimeout(function() {
                    m.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }

            // Go to Login Button
            var goLoginBtn = document.getElementById('goToLoginBtn');
            if (goLoginBtn) {
                goLoginBtn.addEventListener('click', function() {
                    closeModal('resetSuccessModal');
                    var loginUrl = (document.body.dataset.loginPage || '/login');
                    window.location.href = loginUrl;
                });
            }

            // Close modal on overlay click
            document.querySelectorAll('.reset-success-overlay').forEach(function(o) {
                o.addEventListener('click', function(e) {
                    if (e.target === o) closeModal(o.id);
                });
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.reset-success-overlay.active').forEach(function(m) {
                        closeModal(m.id);
                    });
                }
            });

            // Form Submit Handler with Loading State
            var form = document.getElementById('resetForm');
            if (form && btn) {
                form.addEventListener('submit', function() {
                    btn.classList.add('loading');
                    btn.style.pointerEvents = 'none';

                    setTimeout(function() {
                        btn.classList.remove('loading');
                        btn.style.pointerEvents = '';
                    }, 8000);
                });
            }

            // Email Validation Visual Feedback
            var emailInput = document.getElementById('email');
            var emailWarning = document.getElementById('emailWarning');

            if (emailInput && emailWarning) {
                emailInput.addEventListener('blur', function() {
                    var val = this.value.trim();
                    if (val && !val.endsWith('.ac.id')) {
                        emailWarning.classList.remove('hidden');
                        this.classList.add('input-error');
                    } else {
                        emailWarning.classList.add('hidden');
                        this.classList.remove('input-error');
                    }
                });

                emailInput.addEventListener('input', function() {
                    if (this.classList.contains('input-error')) {
                        this.classList.remove('input-error');
                        emailWarning.classList.add('hidden');
                    }
                });
            }
        })();
    </script>
</body>

</html>
