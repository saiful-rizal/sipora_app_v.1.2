<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIPORA POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-register.css') }}">

    <style>
        html,
        body {
            overflow: hidden;
            height: 100%;
        }

        .register-card {
            border-radius: 24px !important;
            box-shadow: 0 12px 40px rgba(26, 86, 214, .25), 0 2px 8px rgba(15, 23, 42, .10) !important;
            overflow: hidden;
            max-height: 95vh;
        }

        .register-card-right {
            overflow-y: auto !important;
            scrollbar-width: thin;
            scrollbar-color: #e4e9f5 transparent;
        }

        .register-card-right::-webkit-scrollbar {
            width: 4px;
        }

        .register-card-right::-webkit-scrollbar-track {
            background: transparent;
        }

        .register-card-right::-webkit-scrollbar-thumb {
            background: #e4e9f5;
            border-radius: 10px;
        }

        .register-card-left {
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .btn-primary {
            background: linear-gradient(130deg, #1a56d6, #6366f1) !important;
            box-shadow: 0 4px 16px rgba(26, 86, 214, .30) !important;
            border-radius: 10px !important;
            border: none !important;
            font-family: 'Sora', sans-serif !important;
            letter-spacing: .3px !important;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 24px rgba(26, 86, 214, .35) !important;
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
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

        .form-input {
            border-radius: 10px !important;
            border: 1.5px solid #e4e9f5 !important;
            font-family: 'Inter', sans-serif !important;
            transition: all .22s !important;
        }

        .form-input:focus {
            border-color: #1a56d6 !important;
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .10), 0 1px 3px rgba(26, 86, 214, .06) !important;
        }

        .form-input.input-error {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, .10) !important;
        }

        .form-input.input-success {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, .10) !important;
        }

        /* Label: regular weight, bukan bold */
        .form-label {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #0f172a;
            font-size: 13px;
            letter-spacing: .2px;
        }

        .login-link a {
            color: #1a56d6 !important;
            font-weight: 500;
        }

        .login-link a:hover {
            color: #1240b5 !important;
        }

        .alert-error {
            background: #fef2f2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
            border-radius: 12px !important;
        }

        .email-warning {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: .78rem;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            animation: premAlertIn .35s ease forwards;
            font-weight: 400;
        }

        .email-warning.hidden {
            display: none !important;
        }

        .email-warning i {
            font-size: .82rem;
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

        .checkbox-container input[type="checkbox"] {
            appearance: none !important;
            -webkit-appearance: none !important;
            width: 18px !important;
            height: 18px !important;
            border: 1.5px solid #d1d5db !important;
            border-radius: 5px !important;
            cursor: pointer;
            position: relative;
            transition: all .2s ease;
            flex-shrink: 0;
        }

        .checkbox-container input[type="checkbox"]:checked {
            background: #1a56d6 !important;
            border-color: #1a56d6 !important;
        }

        .checkbox-container input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 5.5px;
            top: 2px;
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-container input[type="checkbox"]:hover:not(:checked) {
            border-color: #9ca3af !important;
        }

        .checkbox-container label {
            font-size: .82rem;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
        }

        .password-toggle {
            color: #94a3b8 !important;
        }

        .password-toggle:hover {
            color: #1a56d6 !important;
            background: rgba(26, 86, 214, .06) !important;
        }

        .progress-container {
            margin-bottom: 20px;
        }

        .progress-bar {
            height: 6px;
            background: #e4e9f5;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #1a56d6, #6366f1);
            border-radius: 10px;
            transition: width .5s cubic-bezier(.22, 1, .36, 1);
            box-shadow: 0 0 10px rgba(26, 86, 214, .3);
        }

        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-top: 6px;
            font-size: .7rem;
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
        }

        .register-success-overlay {
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(11, 27, 77, .45);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .register-success-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .register-success-card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 36px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 24px 64px rgba(11, 27, 77, .25), 0 0 0 1px rgba(255, 255, 255, .1);
            transform: translateY(20px) scale(.97);
            transition: transform .35s cubic-bezier(.22, .68, 0, 1.1);
        }

        .register-success-overlay.active .register-success-card {
            transform: translateY(0) scale(1);
        }

        .register-success-card h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .register-success-card p {
            font-size: .85rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
            font-weight: 400;
        }

        .register-success-actions .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 32px;
            font-size: .88rem;
        }

        .register-container {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .85s ease, transform .85s ease;
        }

        .register-container.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* BG */
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

        .prem-bg-orb:nth-child(4) {
            width: 240px;
            height: 180px;
            top: 15%;
            left: 55%;
            background: rgba(56, 189, 248, .05);
            animation: premBgOrbIn 2s 1.1s ease forwards, premBgOrb4 20s 3.5s ease-in-out infinite;
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

        @keyframes premBgOrb4 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(-30px, 22px) scale(1.05)
            }
        }

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
            width: 5px;
            height: 5px;
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

        .prem-bg-dot:nth-child(5) {
            width: 4px;
            height: 4px;
            bottom: 35%;
            right: 25%;
            background: rgba(99, 102, 241, .15);
            animation: premBgDotIn 1.5s 1.5s ease forwards, premBgDotFloat 13s 2s ease-in-out infinite;
        }

        .prem-bg-dot:nth-child(6) {
            width: 3px;
            height: 3px;
            top: 45%;
            left: 5%;
            background: rgba(26, 86, 214, .18);
            animation: premBgDotIn 1.5s 1.8s ease forwards, premBgDotFloat 15s 3.8s ease-in-out infinite;
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

        .prem-bg-geo {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            opacity: 0;
        }

        .prem-bg-geo-diamond {
            width: 10px;
            height: 10px;
            border: 1px solid rgba(99, 102, 241, .12);
            transform: rotate(45deg);
            animation: premGeoIn 2s .5s ease forwards, premGeoFloat 9s ease-in-out infinite;
        }

        .prem-bg-geo-diamond:nth-child(2) {
            width: 7px;
            height: 7px;
            border-color: rgba(20, 184, 166, .10);
            animation: premGeoIn 2s 1s ease forwards, premGeoFloat 11s 2s ease-in-out infinite;
        }

        .prem-bg-geo-cross {
            width: 8px;
            height: 8px;
            position: relative;
            animation: premGeoIn 2s .8s ease forwards, premGeoFloatCross 10s 3s ease-in-out infinite;
        }

        .prem-bg-geo-cross::before,
        .prem-bg-geo-cross::after {
            content: '';
            position: absolute;
            background: rgba(26, 86, 214, .10);
        }

        .prem-bg-geo-cross::before {
            width: 100%;
            height: 1px;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }

        .prem-bg-geo-cross::after {
            width: 1px;
            height: 100%;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
        }

        @keyframes premGeoIn {
            to {
                opacity: 1
            }
        }

        @keyframes premGeoFloat {

            0%,
            100% {
                transform: translateY(0) rotate(45deg)
            }

            50% {
                transform: translateY(-14px) rotate(45deg)
            }
        }

        @keyframes premGeoFloatCross {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        /* LOGO */
        .prem-logo-wrap {
            position: relative !important;
            display: inline-flex !important;
            width: 82px !important;
            height: 82px !important;
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
                transform: translateY(-7px)
            }
        }

        .prem-logo-ring {
            position: absolute !important;
            inset: -12px !important;
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
            inset: -22px !important;
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
            inset: -30px !important;
            border-radius: 50% !important;
            background: conic-gradient(from 0deg, rgba(147, 197, 253, .18), rgba(196, 181, 253, .10), rgba(56, 189, 248, .06), rgba(99, 102, 241, .12), rgba(147, 197, 253, .18)) !important;
            filter: blur(22px) !important;
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

        /* LEFT PANEL */
        .prem-left-dots {
            position: absolute !important;
            inset: 0 !important;
            background-image: radial-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px) !important;
            background-size: 22px 22px !important;
            opacity: .5 !important;
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
            width: 280px !important;
            height: 280px !important;
            border: 50px solid rgba(255, 255, 255, .05) !important;
            top: -120px !important;
            right: -80px !important;
            animation: premLeftCircFloat 20s ease-in-out infinite !important;
        }

        .prem-left-circ-2 {
            width: 160px !important;
            height: 160px !important;
            border: 30px solid rgba(255, 255, 255, .05) !important;
            bottom: -60px !important;
            left: 30% !important;
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

        .prem-left-eyebrow {
            position: absolute !important;
            top: 28px !important;
            left: 28px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            background: rgba(255, 255, 255, .12) !important;
            border: 1px solid rgba(255, 255, 255, .20) !important;
            border-radius: 20px !important;
            padding: 4px 12px !important;
            font-size: 10px !important;
            font-weight: 500 !important;
            color: rgba(255, 255, 255, .85) !important;
            letter-spacing: .5px !important;
            text-transform: uppercase !important;
            z-index: 2 !important;
            font-family: 'Inter', sans-serif !important;
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .prem-left-eyebrow.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-left-eyebrow i {
            font-size: 10px !important;
        }

        .prem-left-chips {
            position: absolute !important;
            bottom: 24px !important;
            left: 0 !important;
            right: 0 !important;
            display: flex !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 0 24px !important;
            z-index: 2 !important;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .6s .1s ease, transform .6s .1s ease;
        }

        .prem-left-chips.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-chip {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            background: rgba(255, 255, 255, .12) !important;
            border: 1px solid rgba(255, 255, 255, .18) !important;
            border-radius: 20px !important;
            padding: 5px 12px !important;
            font-size: 11px !important;
            font-weight: 400 !important;
            color: rgba(255, 255, 255, .85) !important;
            backdrop-filter: blur(4px) !important;
            font-family: 'Inter', sans-serif !important;
            transition: transform .2s, background .2s;
        }

        .prem-chip:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .18) !important;
        }

        .prem-chip i {
            font-size: 11px !important;
        }

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
            transform: scale(1.08) !important;
        }

        /* H1 tetap semibold, deskripsi regular */
        .register-card-left h1 {
            font-family: 'Sora', sans-serif !important;
            font-weight: 600 !important;
            letter-spacing: -.2px !important;
        }

        .register-card-left p {
            font-family: 'Inter', sans-serif !important;
            font-weight: 400 !important;
        }

        /* RIGHT PANEL */
        .prem-right-accent {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 3px !important;
            background: linear-gradient(90deg, #1a56d6, #6366f1, #38bdf8) !important;
            z-index: 2 !important;
        }

        .prem-card-glow {
            position: absolute !important;
            width: 400px !important;
            height: 400px !important;
            border-radius: 50% !important;
            background: radial-gradient(circle, rgba(26, 86, 214, .04) 0%, transparent 70%) !important;
            pointer-events: none !important;
            z-index: 0 !important;
            opacity: 0;
            transition: opacity .4s ease;
            transform: translate(-50%, -50%);
        }

        .prem-card-glow.on {
            opacity: 1;
        }

        .prem-trust-line {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            margin-top: 14px !important;
            font-size: 11.5px !important;
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
            font-size: 10px !important;
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

        .form-group {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity .45s ease, transform .45s ease;
        }

        .form-group.in {
            opacity: 1;
            transform: translateY(0);
        }

        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all .2s;
        }

        @media (max-width:768px) {
            .prem-bg-orb {
                filter: blur(45px);
            }

            .prem-left-eyebrow {
                top: 18px !important;
                left: 18px !important;
                font-size: 9px !important;
            }

            .prem-left-chips {
                bottom: 18px !important;
                gap: 6px !important;
                padding: 0 16px !important;
            }

            .prem-chip {
                padding: 4px 9px !important;
                font-size: 10px !important;
            }

            .prem-left-circ-1 {
                width: 200px !important;
                height: 200px !important;
                border-width: 35px !important;
            }

            .prem-left-circ-2 {
                width: 120px !important;
                height: 120px !important;
                border-width: 22px !important;
            }

            .register-card {
                max-height: 98vh;
            }

            .prem-logo-ring {
                inset: -10px !important;
            }

            .prem-logo-ring-2 {
                inset: -18px !important;
            }

            .prem-logo-glow {
                inset: -24px !important;
            }
        }

        @media (max-width:480px) {
            .prem-bg-orb {
                filter: blur(25px);
            }

            .prem-bg-particles,
            .prem-bg-geo {
                display: none;
            }

            .prem-left-chips {
                display: none;
            }

            .prem-left-eyebrow {
                display: none;
            }

            .prem-card-glow {
                display: none;
            }

            .prem-left-circ-1 {
                width: 150px !important;
                height: 150px !important;
                border-width: 25px !important;
                top: -80px !important;
                right: -50px !important;
            }

            .prem-left-circ-2 {
                width: 90px !important;
                height: 90px !important;
                border-width: 18px !important;
            }

            .register-card {
                max-height: none;
            }

            html,
            body {
                overflow: auto;
            }

            .prem-logo-ring,
            .prem-logo-ring-2 {
                display: none;
            }

            .prem-logo-glow {
                inset: -16px !important;
                filter: blur(15px) !important;
            }

            .prem-logo-wrap {
                width: 70px !important;
                height: 70px !important;
            }
        }

        @media (prefers-reduced-motion:reduce) {

            .prem-bg-orb,
            .prem-bg-dot,
            .prem-bg-geo,
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

            .prem-left-eyebrow,
            .prem-left-chips,
            .prem-trust-line,
            .prem-logo-ring,
            .prem-logo-ring-2,
            .prem-logo-glow {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body data-availability-endpoint="{{ route('auth.check-user') }}" data-csrf-token="{{ csrf_token() }}"
    data-login-page="{{ route('login') }}" data-register-success-message="{{ session('register_success', '') }}">

    <div class="prem-bg-orbs">
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
        <div class="prem-bg-orb"></div>
    </div>
    <div class="prem-bg-particles">
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
        <div class="prem-bg-dot"></div>
    </div>
    <div class="prem-bg-geo prem-bg-geo-diamond" style="top:14%;left:6%;"></div>
    <div class="prem-bg-geo prem-bg-geo-diamond" style="top:78%;right:10%;"></div>
    <div class="prem-bg-geo prem-bg-geo-cross" style="top:30%;right:6%;"></div>

    <div class="bg-pattern"></div>
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    <div class="register-container">
        <div class="register-card" style="position:relative;">
            <div class="prem-card-glow"></div>

            <div class="register-card-left">
                <div class="prem-left-dots"></div>
                <div class="prem-left-circ prem-left-circ-1"></div>
                <div class="prem-left-circ prem-left-circ-2"></div>
                <div class="prem-left-eyebrow"><i class="bi bi-stars"></i> Portal Repository Akademik</div>

                <div class="register-card-left-content">
                    <div class="logo-container">
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
                    <h1>Bergabung dengan SIPORA</h1>
                    <p>Sistem Informasi Politeknik Negeri Jember Repository Assets</p>
                </div>

                <div class="prem-left-chips">
                    <div class="prem-chip"><i class="bi bi-shield-check"></i> Terverifikasi</div>
                    <div class="prem-chip"><i class="bi bi-lock"></i> Terenkripsi</div>
                    <div class="prem-chip"><i class="bi bi-clock"></i> 24/7 Aktif</div>
                </div>
            </div>

            <div class="register-card-right" style="position:relative;">
                <div class="prem-right-accent"></div>

                @if (session('register_error'))
                    <div class="alert alert-error mb-4"><i
                            class="fas fa-exclamation-circle"></i>{{ session('register_error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error mb-4"><i class="fas fa-exclamation-circle"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form id="registerFormElement" method="POST" action="{{ route('register.submit') }}" novalidate>
                    @csrf
                    <input type="hidden" name="action" value="register">

                    <!-- Nama Lengkap -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input w-100 px-3 py-2"
                            placeholder="Masukkan nama lengkap" required value="{{ old('nama_lengkap') }}">
                        <div id="namaWarning" class="email-warning hidden mt-2"><i
                                class="fas fa-exclamation-triangle"></i><span>Nama sudah terdaftar</span></div>
                    </div>

                    <!-- Nomor Induk -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="nomor_induk">Nomor Induk</label>
                        <input type="text" id="nomor_induk" name="nomor_induk" class="form-input w-100 px-3 py-2"
                            placeholder="Masukkan NIM / NIP / Nomor Pegawai" required
                            value="{{ old('nomor_induk') }}">
                    </div>

                    <!-- Username -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="reg_username">Username</label>
                        <input type="text" id="reg_username" name="username" class="form-input w-100 px-3 py-2"
                            placeholder="Masukkan username" required value="{{ old('username') }}">
                        <div id="usernameWarning" class="email-warning hidden mt-2"><i
                                class="fas fa-exclamation-triangle"></i><span>Username tidak valid atau sudah
                                digunakan</span></div>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="email">Email SSO <span
                                style="color:#dc2626;">*</span></label>
                        <input type="email" id="email" name="email" class="form-input w-100 px-3 py-2"
                            placeholder="Masukkan email akademik (.ac.id)" required value="{{ old('email') }}">
                        <div id="emailWarning" class="email-warning hidden mt-2"><i
                                class="fas fa-exclamation-triangle"></i><span>Hanya email dengan domain .ac.id yang
                                diizinkan</span></div>
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="reg_password">Kata Sandi</label>
                        <div class="password-input-container">
                            <input type="password" id="reg_password" name="password"
                                class="form-input w-100 px-3 py-2 pr-11" placeholder="Minimal 8 karakter"
                                minlength="8" required>
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('reg_password')"><i class="bi bi-eye"
                                    id="reg_password-icon"></i></button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-4">
                        <label class="form-label d-block mb-2" for="confirm_password">Konfirmasi Kata Sandi</label>
                        <div class="password-input-container">
                            <input type="password" id="confirm_password" name="confirmPassword"
                                class="form-input w-100 px-3 py-2 pr-11" placeholder="Ulangi kata sandi"
                                minlength="8" required>
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('confirm_password')"><i class="bi bi-eye"
                                    id="confirm_password-icon"></i></button>
                        </div>
                        <div id="passwordMatchWarning" class="email-warning mt-2" style="display:none;">
                            <i id="passwordMatchIcon" class="fas fa-exclamation-triangle"
                                style="margin-right:6px;"></i>
                            <span id="passwordMatchText">Kata sandi tidak sama</span>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="form-options mb-4">
                        <div class="checkbox-container d-flex align-items-center gap-2">
                            <input type="checkbox" id="agreeTerms" required>
                            <label for="agreeTerms" class="mb-0">Saya setuju dengan <a href="#"
                                    style="color:#1a56d6;">syarat dan ketentuan</a></label>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-text"><span id="progressText">0% Selesai</span><span
                                id="progressStep">Langkah 1 dari 5</span></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="registerSubmitBtn"
                        class="btn btn-primary w-100 py-2 mt-3">Daftar</button>
                </form>

                <div class="prem-trust-line mt-4">
                    <i class="fas fa-lock"></i>
                    <span>Data Anda dilindungi enkripsi SSL 256-bit</span>
                </div>

                <p class="login-link mt-3 mb-0 text-center">Sudah punya akun? <a href="{{ route('login') }}">Masuk
                        sekarang</a></p>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="registerSuccessModal" class="register-success-overlay" style="display:none;">
        <div class="register-success-card" role="dialog" aria-modal="true" aria-labelledby="registerSuccessTitle">
            <div
                style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#22c55e,#16a34a);display:inline-flex;align-items:center;justify-content:center;font-size:24px;color:#fff;margin-bottom:16px;box-shadow:0 8px 24px rgba(34,197,94,.3);">
                <i class="fas fa-check"></i>
            </div>
            <h2 id="registerSuccessTitle">Pendaftaran Berhasil</h2>
            <p id="registerSuccessMessage">Akun Anda berhasil didaftarkan dan sedang menunggu persetujuan admin.</p>
            <div class="register-success-actions"><button id="goToLoginBtn" class="btn btn-primary">Masuk</button>
            </div>
        </div>
    </div>

    <!-- Password Mismatch Modal -->
    <div id="passwordMismatchModal" class="register-success-overlay" style="display:none;">
        <div class="register-success-card" role="dialog" aria-modal="true" aria-labelledby="passwordMismatchTitle">
            <div
                style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#f87171,#dc2626);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:#fff;margin-bottom:16px;box-shadow:0 8px 24px rgba(248,113,113,.3);">
                <i class="fas fa-exclamation"></i>
            </div>
            <h2 id="passwordMismatchTitle" style="color:#dc2626">Kata sandi tidak sama</h2>
            <p id="passwordMismatchMessage">Pastikan kata sandi dan konfirmasi kata sandi sama sebelum melanjutkan.</p>
            <div class="register-success-actions"><button id="pwMismatchOkBtn" class="btn btn-primary">Oke,
                    perbaiki</button></div>
        </div>
    </div>

    <script src="{{ asset('assets/js/auth-register.js') }}"></script>

    <script>
        (function() {
            'use strict';

            // ==================== ANIMASI INISIALISASI ====================
            var ctr = document.querySelector('.register-container');
            if (ctr) setTimeout(function() {
                ctr.classList.add('visible');
            }, 150);

            var groups = document.querySelectorAll('.form-group');
            if (groups.length) groups.forEach(function(el, i) {
                setTimeout(function() {
                    el.classList.add('in');
                }, 350 + i * 80);
            });

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

            setTimeout(function() {
                var e = document.querySelector('.prem-left-eyebrow');
                if (e) e.classList.add('show');
            }, 200);
            setTimeout(function() {
                var c = document.querySelector('.prem-left-chips');
                if (c) c.classList.add('show');
            }, 350);
            setTimeout(function() {
                var t = document.querySelector('.prem-trust-line');
                if (t) t.classList.add('show');
            }, 500);

            var card = document.querySelector('.register-card'),
                glow = document.querySelector('.prem-card-glow');
            if (card && glow) {
                card.addEventListener('mousemove', function(e) {
                    var r = card.getBoundingClientRect();
                    glow.style.left = (e.clientX - r.left) + 'px';
                    glow.style.top = (e.clientY - r.top) + 'px';
                    glow.classList.add('on');
                });
                card.addEventListener('mouseleave', function() {
                    glow.classList.remove('on');
                });
            }

            var btn = document.querySelector('#registerSubmitBtn');
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

            // ==================== MODAL FUNCTIONS ====================
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

            /* Tombol "Masuk" → langsung ke login TANPA splash */
            var goLoginBtn = document.getElementById('goToLoginBtn');
            if (goLoginBtn) {
                goLoginBtn.addEventListener('click', function() {
                    closeModal('registerSuccessModal');
                    var loginUrl = (document.body.dataset.loginPage || '/login');
                    var sep = loginUrl.indexOf('?') > -1 ? '&' : '?';
                    window.location.href = loginUrl + sep + 'skip_splash=1';
                });
            }

            var pwOkBtn = document.getElementById('pwMismatchOkBtn');
            if (pwOkBtn) pwOkBtn.addEventListener('click', function() {
                closeModal('passwordMismatchModal');
                var pw = document.getElementById('reg_password');
                if (pw) pw.focus();
            });

            document.querySelectorAll('.register-success-overlay').forEach(function(o) {
                o.addEventListener('click', function(e) {
                    if (e.target === o) closeModal(o.id);
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') document.querySelectorAll('.register-success-overlay.active').forEach(
                    function(m) {
                        closeModal(m.id);
                    });
            });

            // ==================== TOGGLE PASSWORD ====================
            window.togglePassword = function(inputId) {
                var input = document.getElementById(inputId);
                var icon = document.getElementById(inputId + '-icon');
                if (!input || !icon) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            };

            // ==================== VALIDATION STATE ====================
            var validationState = {
                nama: false,
                nomorInduk: false,
                username: false,
                email: false,
                password: false,
                confirmPassword: false,
                terms: false
            };

            // ==================== HELPER FUNCTIONS ====================
            function showWarning(elementId, show) {
                var el = document.getElementById(elementId);
                if (!el) return;
                if (show) {
                    el.classList.remove('hidden');
                    el.style.display = 'flex';
                } else {
                    el.classList.add('hidden');
                    el.style.display = 'none';
                }
            }

            function setInputState(inputId, state) {
                var input = document.getElementById(inputId);
                if (!input) return;

                input.classList.remove('input-error', 'input-success');
                if (state === 'error') {
                    input.classList.add('input-error');
                } else if (state === 'success') {
                    input.classList.add('input-success');
                }
            }

            function updateProgress() {
                var totalFields = 7; // nama, nomorInduk, username, email, password, confirmPassword, terms
                var filledFields = 0;

                if (validationState.nama) filledFields++;
                if (validationState.nomorInduk) filledFields++;
                if (validationState.username) filledFields++;
                if (validationState.email) filledFields++;
                if (validationState.password) filledFields++;
                if (validationState.confirmPassword) filledFields++;
                if (validationState.terms) filledFields++;

                var percentage = Math.round((filledFields / totalFields) * 100);
                var progressFill = document.getElementById('progressFill');
                var progressText = document.getElementById('progressText');
                var progressStep = document.getElementById('progressStep');

                if (progressFill) progressFill.style.width = percentage + '%';
                if (progressText) progressText.textContent = percentage + '% Selesai';

                var currentStep = filledFields > 0 ? filledFields : 1;
                if (progressStep) progressStep.textContent = 'Langkah ' + currentStep + ' dari ' + totalFields;
            }

            // ==================== VALIDATION FUNCTIONS ====================

            // Validasi Nama Lengkap
            function validateFullname() {
                var input = document.getElementById('nama_lengkap');
                var value = input ? input.value.trim() : '';

                if (value.length < 3) {
                    setInputState('nama_lengkap', 'error');
                    validationState.nama = false;
                    return false;
                }

                // Cek ketersediaan nama via AJAX (opsional)
                /*
                var endpoint = document.body.dataset.availabilityEndpoint;
                if (endpoint && value.length >= 3) {
                    fetch(endpoint + '?field=nama&value=' + encodeURIComponent(value))
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data.exists) {
                                showWarning('namaWarning', true);
                                setInputState('nama_lengkap', 'error');
                                validationState.nama = false;
                            } else {
                                showWarning('namaWarning', false);
                                setInputState('nama_lengkap', 'success');
                                validationState.nama = true;
                                updateProgress();
                            }
                        })
                        .catch(function() {
                            // Jika gagal, tetap lanjutkan
                            showWarning('namaWarning', false);
                            setInputState('nama_lengkap', 'success');
                            validationState.nama = true;
                            updateProgress();
                        });
                    return;
                }
                */

                showWarning('namaWarning', false);
                setInputState('nama_lengkap', 'success');
                validationState.nama = true;
                updateProgress();
                return true;
            }

            // Validasi Nomor Induk
            function validateNomorInduk() {
                var input = document.getElementById('nomor_induk');
                var value = input ? input.value.trim() : '';

                if (value.length < 5) {
                    setInputState('nomor_induk', 'error');
                    validationState.nomorInduk = false;
                    return false;
                }

                setInputState('nomor_induk', 'success');
                validationState.nomorInduk = true;
                updateProgress();
                return true;
            }

            // Validasi Username
            function validateUsername() {
                var input = document.getElementById('reg_username');
                var value = input ? input.value.trim() : '';

                // Username minimal 3 karakter, hanya alphanumeric dan underscore
                var usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

                if (!usernameRegex.test(value)) {
                    showWarning('usernameWarning', true);
                    setInputState('reg_username', 'error');
                    validationState.username = false;
                    updateProgress();
                    return false;
                }

                // Cek ketersediaan username via AJAX
                var endpoint = document.body.dataset.availabilityEndpoint;
                if (endpoint) {
                    fetch(endpoint + '?field=username&value=' + encodeURIComponent(value), {
                            headers: {
                                'X-CSRF-TOKEN': document.body.dataset.csrfToken || ''
                            }
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            if (data.exists) {
                                showWarning('usernameWarning', true);
                                document.querySelector('#usernameWarning span').textContent =
                                    'Username sudah digunakan';
                                setInputState('reg_username', 'error');
                                validationState.username = false;
                            } else {
                                showWarning('usernameWarning', false);
                                setInputState('reg_username', 'success');
                                validationState.username = true;
                            }
                            updateProgress();
                        })
                        .catch(function() {
                            // Jika gagal, tetap lanjutkan dengan asumsi tersedia
                            showWarning('usernameWarning', false);
                            setInputState('reg_username', 'success');
                            validationState.username = true;
                            updateProgress();
                        });

                    return true; // Lanjutkan sambil menunggu response
                }

                showWarning('usernameWarning', false);
                setInputState('reg_username', 'success');
                validationState.username = true;
                updateProgress();
                return true;
            }

            // Validasi Email
            function validateEmail() {
                var input = document.getElementById('email');
                var value = input ? input.value.trim() : '';

                // Validasi format email dasar
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    showWarning('emailWarning', true);
                    document.querySelector('#emailWarning span').textContent = 'Format email tidak valid';
                    setInputState('email', 'error');
                    validationState.email = false;
                    updateProgress();
                    return false;
                }

                // Validasi domain .ac.id
                if (!value.toLowerCase().endsWith('.ac.id')) {
                    showWarning('emailWarning', true);
                    document.querySelector('#emailWarning span').textContent =
                        'Hanya email dengan domain .ac.id yang diizinkan';
                    setInputState('email', 'error');
                    validationState.email = false;
                    updateProgress();
                    return false;
                }

                showWarning('emailWarning', false);
                setInputState('email', 'success');
                validationState.email = true;
                updateProgress();
                return true;
            }

            // Validasi Password
            function validatePassword() {
                var input = document.getElementById('reg_password');
                var value = input ? input.value : '';

                if (value.length < 8) {
                    setInputState('reg_password', 'error');
                    validationState.password = false;
                    updateProgress();
                    return false;
                }

                // Optional: Check password strength
                // Minimal 8 karakter, mengandung huruf dan angka
                var hasLetter = /[a-zA-Z]/.test(value);
                var hasNumber = /[0-9]/.test(value);

                if (!hasLetter || !hasNumber) {
                    setInputState('reg_password', 'error');
                    validationState.password = false;
                    updateProgress();
                    return false;
                }

                setInputState('reg_password', 'success');
                validationState.password = true;
                updateProgress();

                // Jika confirm password sudah diisi, validasi juga
                var confirmInput = document.getElementById('confirm_password');
                if (confirmInput && confirmInput.value) {
                    validateConfirmPassword();
                }

                return true;
            }

            // Validasi Konfirmasi Password
            function validateConfirmPassword() {
                var passwordInput = document.getElementById('reg_password');
                var confirmInput = document.getElementById('confirm_password');

                var password = passwordInput ? passwordInput.value : '';
                var confirmPassword = confirmInput ? confirmInput.value : '';

                if (confirmPassword === '') {
                    showWarning('passwordMatchWarning', false);
                    setInputState('confirm_password', '');
                    validationState.confirmPassword = false;
                    updateProgress();
                    return false;
                }

                if (password !== confirmPassword) {
                    showWarning('passwordMatchWarning', true);
                    document.getElementById('passwordMatchText').textContent = 'Kata sandi tidak sama';
                    document.getElementById('passwordMatchIcon').className = 'fas fa-exclamation-triangle';
                    setInputState('confirm_password', 'error');
                    validationState.confirmPassword = false;
                    updateProgress();
                    return false;
                }

                showWarning('passwordMatchWarning', false);
                setInputState('confirm_password', 'success');
                validationState.confirmPassword = true;
                updateProgress();
                return true;
            }

            // Validasi Terms
            function validateTerms() {
                var checkbox = document.getElementById('agreeTerms');
                validationState.terms = checkbox ? checkbox.checked : false;
                updateProgress();
                return validationState.terms;
            }

            // ==================== EVENT LISTENERS ====================

            // Attach event listeners untuk semua field
            var namaInput = document.getElementById('nama_lengkap');
            if (namaInput) {
                namaInput.addEventListener('blur', validateFullname);
                namaInput.addEventListener('input', function() {
                    if (this.value.trim().length >= 3) {
                        validateFullname();
                    } else {
                        validationState.nama = false;
                        setInputState('nama_lengkap', '');
                        updateProgress();
                    }
                });
            }

            var nomorIndukInput = document.getElementById('nomor_induk');
            if (nomorIndukInput) {
                nomorIndukInput.addEventListener('blur', validateNomorInduk);
                nomorIndukInput.addEventListener('input', function() {
                    if (this.value.trim().length >= 5) {
                        validateNomorInduk();
                    } else {
                        validationState.nomorInduk = false;
                        setInputState('nomor_induk', '');
                        updateProgress();
                    }
                });
            }

            var usernameInput = document.getElementById('reg_username');
            if (usernameInput) {
                usernameInput.addEventListener('blur', validateUsername);
                usernameInput.addEventListener('input', function() {
                    var value = this.value.trim();
                    var usernameRegex = /^[a-zA-Z0-9_]*$/;

                    // Hanya izinkan karakter valid
                    if (!usernameRegex.test(value)) {
                        this.value = value.replace(/[^a-zA-Z0-9_]/g, '');
                    }

                    if (this.value.length >= 3) {
                        // Debounce untuk mengurangi request AJAX
                        clearTimeout(this.validateTimeout);
                        this.validateTimeout = setTimeout(validateUsername, 500);
                    } else {
                        validationState.username = false;
                        setInputState('reg_username', '');
                        updateProgress();
                    }
                });
            }

            var emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.addEventListener('blur', validateEmail);
                emailInput.addEventListener('input', function() {
                    if (this.value.includes('@')) {
                        validateEmail();
                    } else {
                        validationState.email = false;
                        setInputState('email', '');
                        updateProgress();
                    }
                });
            }

            var passwordInput = document.getElementById('reg_password');
            if (passwordInput) {
                passwordInput.addEventListener('input', validatePassword);
                passwordInput.addEventListener('blur', validatePassword);
            }

            var confirmPasswordInput = document.getElementById('confirm_password');
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', validateConfirmPassword);
                confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
            }

            var termsCheckbox = document.getElementById('agreeTerms');
            if (termsCheckbox) {
                termsCheckbox.addEventListener('change', validateTerms);
            }

            // ==================== FORM SUBMISSION ====================
            var form = document.getElementById('registerFormElement');

            if (form && btn) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Reset semua validasi terlebih dahulu
                    validateFullname();
                    validateNomorInduk();
                    validateUsername();
                    validateEmail();
                    validatePassword();
                    validateConfirmPassword();
                    validateTerms();

                    // Cek apakah ada error
                    var errors = [];

                    if (!validationState.nama) errors.push('Nama lengkap tidak valid (minimal 3 karakter)');
                    if (!validationState.nomorInduk) errors.push(
                    'Nomor induk tidak valid (minimal 5 karakter)');
                    if (!validationState.username) errors.push(
                        'Username tidak valid (minimal 3 karakter, alphanumeric)');
                    if (!validationState.email) errors.push('Email harus menggunakan domain .ac.id');
                    if (!validationState.password) errors.push(
                        'Password minimal 8 karakter dengan huruf dan angka');
                    if (!validationState.confirmPassword) {
                        errors.push('Konfirmasi password tidak cocok');
                        openModal('passwordMismatchModal');
                        return;
                    }
                    if (!validationState.terms) errors.push('Anda harus menyetujui syarat dan ketentuan');

                    if (errors.length > 0) {
                        console.log('Validation errors:', errors);

                        // Focus ke field pertama yang error
                        if (!validationState.nama) {
                            document.getElementById('nama_lengkap').focus();
                        } else if (!validationState.nomorInduk) {
                            document.getElementById('nomor_induk').focus();
                        } else if (!validationState.username) {
                            document.getElementById('reg_username').focus();
                        } else if (!validationState.email) {
                            document.getElementById('email').focus();
                        } else if (!validationState.password) {
                            document.getElementById('reg_password').focus();
                        } else if (!validationState.confirmPassword) {
                            document.getElementById('confirm_password').focus();
                        } else if (!validationState.terms) {
                            document.getElementById('agreeTerms').focus();
                        }

                        return;
                    }

                    // Semua validasi lulus, submit form
                    btn.disabled = true;
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '.8';
                    var orig = btn.innerHTML;
                    btn.innerHTML =
                        '<i class="fas fa-circle-notch fa-spin" style="margin-right:8px"></i>Memproses...';

                    // Submit form secara native
                    form.submit();

                    // Fallback: restore button jika submit terlalu lama
                    setTimeout(function() {
                        if (document.visibilityState === 'visible' && btn.disabled) {
                            btn.disabled = false;
                            btn.style.pointerEvents = '';
                            btn.style.opacity = '';
                            btn.innerHTML = orig;
                            btn.appendChild(bs);
                        }
                    }, 10000);
                });
            }

            // ==================== INITIAL PROGRESS UPDATE ====================
            updateProgress();

            // Cek jika ada success message dari session
            var successMessage = document.body.dataset.registerSuccessMessage;
            if (successMessage && successMessage.trim() !== '') {
                setTimeout(function() {
                    openModal('registerSuccessModal');
                    var msgEl = document.getElementById('registerSuccessMessage');
                    if (msgEl) msgEl.textContent = successMessage;
                }, 500);
            }

        })();
    </script>
</body>

</html>
