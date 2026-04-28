<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIPORA POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-login.css') }}">

    <!-- SVG Definitions for Loader Gradient -->
    <svg style="position: absolute; width: 0; height: 0; overflow: hidden;" aria-hidden="true">
        <defs>
            <linearGradient id="loaderGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#93c5fd;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#c4b5fd;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#93c5fd;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    <style>
        /* =============================================
       PREMIUM — Dashboard Color System
       ============================================= */

        /* ── NO SCROLL ── */
        html,
        body {
            overflow: hidden;
            height: 100%;
        }

        /* ── Splash Background → Hero Gradient ── */
        #splash-screen {
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%) !important;
            overflow: hidden;
        }

        /* ── Card → Hero Radius & Shadow ── */
        .login-card {
            border-radius: 24px !important;
            box-shadow: 0 12px 40px rgba(26, 86, 214, .25), 0 2px 8px rgba(15, 23, 42, .10) !important;
            overflow: hidden;
            max-height: 92vh;
        }

        .login-card-right {
            overflow-y: auto !important;
            scrollbar-width: thin;
            scrollbar-color: #e4e9f5 transparent;
        }

        .login-card-right::-webkit-scrollbar {
            width: 4px;
        }

        .login-card-right::-webkit-scrollbar-track {
            background: transparent;
        }

        .login-card-right::-webkit-scrollbar-thumb {
            background: #e4e9f5;
            border-radius: 10px;
        }

        /* ── Left Panel → Hero Gradient ── */
        .login-card-left {
            background: linear-gradient(130deg, #0b1b4d 0%, #1a3fa8 45%, #1a56d6 75%, #2979ff 100%) !important;
        }

        /* ── Button → Upload-btn Style ── */
        .btn-primary {
            background: linear-gradient(130deg, #1a56d6, #6366f1) !important;
            box-shadow: 0 4px 16px rgba(26, 86, 214, .30) !important;
            border-radius: 10px !important;
            border: none !important;
            font-family: 'Sora', sans-serif !important;
            letter-spacing: .3px !important;
            font-weight: 400 !important;
            position: relative;
            overflow: hidden;
            transition: all .35s cubic-bezier(.4, 0, .2, 1) !important;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 24px rgba(26, 86, 214, .35) !important;
        }

        /* ── Body BG → Dashboard Page ── */
        body {
            background: #f0f3fb !important;
            -webkit-font-smoothing: antialiased;
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

        /* ── Inputs → Dashboard Style ── */
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

        .form-label {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: #0f172a;
            font-size: 12.5px;
            letter-spacing: .2px;
        }

        /* ── Links → Dashboard Blue ── */
        .forgot-password,
        .register-link a {
            color: #1a56d6 !important;
        }

        .forgot-password:hover,
        .register-link a:hover {
            color: #1240b5 !important;
        }

        /* ── Alert → Dashboard Colors ── */
        .alert-error {
            background: #fef2f2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
            border-radius: 12px !important;
        }

        .alert-success {
            background: #f0fdf4 !important;
            color: #166534 !important;
            border: 1px solid #bbf7d0 !important;
            border-radius: 12px !important;
        }

        /* ── Divider → Dashboard Border ── */
        .divider::before,
        .divider::after {
            background: #e4e9f5 !important;
        }

        .divider span {
            color: #94a3b8 !important;
            font-size: 12.5px !important;
        }

        /* ── Checkbox → Dashboard Blue ── */
        .checkbox-container input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #e4e9f5;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: all .22s ease;
            flex-shrink: 0;
        }

        .checkbox-container input[type="checkbox"]:hover {
            border-color: #1a56d6;
        }

        .checkbox-container input[type="checkbox"]:checked {
            background: #1a56d6 !important;
            border-color: #1a56d6 !important;
        }

        .checkbox-container input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* ── Override semua teks di login agar tidak bold ── */
        .login-card,
        .login-card *:not(.fas):not(.far):not(.fab):not(.bi):not(.fa-solid):not(.fa-regular):not(.fa-brands) {
            font-weight: 400 !important;
        }

        /* ============================================
       BUTTON LOADING STATE — Premium Animation
       ============================================ */
        .btn-primary.is-loading {
            pointer-events: none !important;
            cursor: default !important;
        }

        .btn-primary.is-loading .btn-text {
            opacity: 0;
            transform: translateY(-10px);
            transition: all .25s ease;
        }

        .btn-loading-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: all .3s ease;
            pointer-events: none;
        }

        .btn-primary.is-loading .btn-loading-wrap {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-loading-text {
            font-size: 13px;
            color: rgba(255, 255, 255, .85);
            letter-spacing: .3px;
            font-family: 'Inter', sans-serif;
        }

        .btn-loading-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: btnSpinIcon .7s linear infinite;
        }

        @keyframes btnSpinIcon {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-loading-shimmer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(105deg, transparent 35%, rgba(255, 255, 255, .08) 50%, transparent 65%);
            transform: skewX(-15deg);
            pointer-events: none;
            opacity: 0;
        }

        .btn-primary.is-loading .btn-loading-shimmer {
            opacity: 1;
            animation: btnLoadShimmerGo 1.6s ease-in-out infinite;
        }

        @keyframes btnLoadShimmerGo {
            0% {
                left: -150%;
            }

            100% {
                left: 150%;
            }
        }

        .btn-primary.is-loading::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 11px;
            background: linear-gradient(90deg, transparent, rgba(147, 197, 253, .3), rgba(196, 181, 253, .3), transparent);
            background-size: 300% 100%;
            animation: btnBorderGlow 2s linear infinite;
            z-index: -1;
        }

        @keyframes btnBorderGlow {
            0% {
                background-position: 0% 0;
            }

            100% {
                background-position: 300% 0;
            }
        }

        /* ============================================
       LOGIN CONTAINER: Fade-in
       ============================================ */
        .login-container {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .85s ease, transform .85s ease;
        }

        .login-container.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============================================
       LOGIN BG: Animated Gradient Orbs
       ============================================ */
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
            background: rgba(20, 184, 167, .07);
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
                transform: translate(40px, 20px) scale(.92);
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

        @keyframes premBgOrb4 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(-30px, 22px) scale(1.05);
            }
        }

        /* ============================================
       LOGIN BG: Floating Particles
       ============================================ */
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
            background: rgba(20, 184, 167, .18);
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
                opacity: 1;
            }
        }

        @keyframes premBgDotFloat {

            0%,
            100% {
                transform: translate(0, 0);
            }

            25% {
                transform: translate(12px, -18px);
            }

            50% {
                transform: translate(-8px, -28px);
            }

            75% {
                transform: translate(15px, -12px);
            }
        }

        /* ============================================
       LOGIN BG: Geometric Accents
       ============================================ */
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
            border-color: rgba(20, 184, 167, .10);
            animation: premGeoIn 2s 1s ease forwards, premGeoFloat 11s 2s ease-in-out infinite;
        }

        .prem-bg-geo-cross {
            width: 8px;
            height: 8px;
            position: relative;
            animation: premGeoIn 2s .8s ease forwards, premGeoFloat 10s 3s ease-in-out infinite;
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
                opacity: 1;
            }
        }

        @keyframes premGeoFloat {

            0%,
            100% {
                transform: translateY(0) rotate(45deg);
            }

            50% {
                transform: translateY(-14px) rotate(45deg);
            }
        }

        /* ============================================
       LEFT PANEL: Logo Animations
       ============================================ */
        .prem-logo-wrap {
            position: relative;
            display: inline-flex;
        }

        .prem-logo-float {
            animation: premLogoFloat 5s ease-in-out infinite;
        }

        @keyframes premLogoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-7px);
            }
        }

        .prem-logo-ring {
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            border: 2px dashed rgba(255, 255, 255, .22);
            animation: premLogoRingSpin 22s linear infinite;
            pointer-events: none;
            opacity: 0;
        }

        .prem-logo-ring.go {
            opacity: 1;
            transition: opacity .8s .3s ease;
        }

        @keyframes premLogoRingSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .prem-logo-ring-2 {
            position: absolute;
            inset: -22px;
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, .12);
            animation: premLogoRingSpin2 30s linear infinite reverse;
            pointer-events: none;
            opacity: 0;
        }

        .prem-logo-ring-2.go {
            opacity: 1;
            transition: opacity .8s .5s ease;
        }

        @keyframes premLogoRingSpin2 {
            to {
                transform: rotate(360deg);
            }
        }

        .prem-logo-glow {
            position: absolute;
            inset: -30px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(147, 197, 253, .18), rgba(196, 181, 253, .10), rgba(56, 189, 248, .06), rgba(99, 102, 241, .12), rgba(147, 197, 253, .18));
            filter: blur(22px);
            animation: premLogoGlowSpin 10s linear infinite, premLogoGlowPulse 4s ease-in-out infinite;
            pointer-events: none;
            opacity: 0;
        }

        .prem-logo-glow.go {
            opacity: 1;
            transition: opacity 1s .4s ease;
        }

        @keyframes premLogoGlowSpin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes premLogoGlowPulse {

            0%,
            100% {
                scale: 1;
                opacity: var(--glow-o, 1);
            }

            50% {
                scale: 1.12;
                opacity: calc(var(--glow-o, 1) * .6);
            }
        }

        .prem-logo-wrap {
            opacity: 0;
            transform: scale(.6);
        }

        .prem-logo-wrap.enter {
            animation: premLogoEnter .8s cubic-bezier(.34, 1.56, .64, 1) forwards;
        }

        @keyframes premLogoEnter {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  SPLASH SCREEN - FIX: LOGO KE KIRI                  ║
         * ╚═══════════════════════════════════════════════════════╝
         */

        #splash-screen {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #splash-screen.hide {
            opacity: 0;
            transform: scale(1.05);
            pointer-events: none;
        }

        /* Background Layer with Animated Orbs */
        .splash-bg-layer {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .splash-gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0;
            animation: splashOrbIn 2s ease forwards;
        }

        .orb-1 {
            width: 500px;
            height: 300px;
            top: -15%;
            right: -10%;
            background: rgba(99, 102, 241, .14);
            animation: splashOrbIn 2s .3s ease forwards, orbFloat1 20s 2s ease-in-out infinite;
        }

        .orb-2 {
            width: 400px;
            height: 250px;
            bottom: -10%;
            left: -5%;
            background: rgba(20, 184, 167, .10);
            animation: splashOrbIn 2s .6s ease forwards, orbFloat2 24s 2.5s ease-in-out infinite;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            top: 30%;
            left: 40%;
            background: rgba(26, 86, 214, .12);
            animation: splashOrbIn 2s .9s ease forwards, orbFloat3 18s 3s ease-in-out infinite;
        }

        .orb-4 {
            width: 280px;
            height: 200px;
            top: 10%;
            left: 10%;
            background: rgba(56, 189, 248, .08);
            animation: splashOrbIn 2s 1.2s ease forwards, orbFloat4 22s 3.5s ease-in-out infinite;
        }

        @keyframes splashOrbIn {
            to {
                opacity: 1;
            }
        }

        @keyframes orbFloat1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(60px, 25px) scale(1.1);
            }

            66% {
                transform: translate(-30px, -15px) scale(.95);
            }
        }

        @keyframes orbFloat2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(-50px, -30px) scale(1.08);
            }

            66% {
                transform: translate(40px, 20px) scale(.92);
            }
        }

        @keyframes orbFloat3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(30px, -25px) scale(1.12);
            }
        }

        @keyframes orbFloat4 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(-40px, 30px) scale(1.05);
            }
        }

        /* Geometric Pattern Overlay */
        .splash-geo-pattern {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            animation: geoPatternIn 1.5s 1s ease forwards;
        }

        @keyframes geoPatternIn {
            to {
                opacity: 1;
            }
        }

        .geo-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(147, 197, 253, .15), transparent);
            height: 1px;
        }

        .line-1 {
            width: 200px;
            top: 20%;
            left: 10%;
            transform: rotate(-15deg);
            animation: lineGlow 4s ease-in-out infinite;
        }

        .line-2 {
            width: 150px;
            bottom: 25%;
            right: 15%;
            transform: rotate(25deg);
            animation: lineGlow 4s 1s ease-in-out infinite;
        }

        .line-3 {
            width: 180px;
            top: 60%;
            left: 20%;
            transform: rotate(-8deg);
            animation: lineGlow 4s 2s ease-in-out infinite;
        }

        @keyframes lineGlow {

            0%,
            100% {
                opacity: .3;
            }

            50% {
                opacity: .7;
            }
        }

        .geo-circle {
            position: absolute;
            border: 1px solid rgba(147, 197, 253, .12);
            border-radius: 50%;
        }

        .geo-circ-1 {
            width: 120px;
            height: 120px;
            top: 15%;
            right: 20%;
            animation: geoCircPulse 6s ease-in-out infinite;
        }

        .geo-circ-2 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 15%;
            animation: geoCircPulse 6s 2s ease-in-out infinite;
        }

        @keyframes geoCircPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .4;
            }

            50% {
                transform: scale(1.1);
                opacity: .7;
            }
        }

        .geo-diamond {
            position: absolute;
            width: 12px;
            height: 12px;
            border: 1px solid rgba(196, 181, 253, .15);
            transform: rotate(45deg);
        }

        .diamond-1 {
            top: 35%;
            left: 8%;
            animation: diamondFloat 8s ease-in-out infinite;
        }

        .diamond-2 {
            bottom: 35%;
            right: 10%;
            animation: diamondFloat 8s 2s ease-in-out infinite reverse;
        }

        @keyframes diamondFloat {

            0%,
            100% {
                transform: rotate(45deg) translateY(0);
            }

            50% {
                transform: rotate(45deg) translateY(-15px);
            }
        }

        /* Particles Canvas */
        .splash-particles-canvas {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        /* Vignette */
        .splash-vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(11, 27, 77, .5) 100%);
            pointer-events: none;
            z-index: 3;
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  ✅ MAIN CONTENT CONTAINER                          ║
         * ╚═══════════════════════════════════════════════════════╝
         */
        .splash-content {
            position: relative;
            z-index: 4;

            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;

            width: 100% !important;
            max-width: 480px !important;
            margin: 0 auto !important;
            padding: 40px 20px !important;

            text-align: center !important;
            box-sizing: border-box !important;
        }

        /*
         * ╔══════════════════════════════════════════════════════════╗
         * ║  🔥🔥🔥 LOGO CONTAINER - GESER KE KIRI!               ║
         * ╚══════════════════════════════════════════════════════════╝
         */
        .splash-logo-container {
            position: relative;
            width: 180px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 32px !important;

            /* 🔥 FIX: Geser Logo ke Kiri */
            margin-left: -20px !important;
            /* ⬅️ NEGATIVE MARGIN = KE KIRI */
            margin-right: auto !important;

            opacity: 0;
            transform: scale(0.5);
            animation: logoContainerIn 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards;
        }

        @keyframes logoContainerIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-pulse-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid transparent;
            opacity: 0;
        }

        .ring-outer {
            inset: -30px;
            border-color: rgba(147, 197, 253, .3);
            animation: ringPulseOuter 3s ease-out 1s forwards, ringRotate 20s linear 1s infinite;
        }

        .ring-middle {
            inset: -18px;
            border-color: rgba(196, 181, 253, .25);
            border-style: dashed;
            animation: ringPulseMiddle 3s ease-out 1.2s forwards, ringRotateReverse 25s linear 1.2s infinite;
        }

        .ring-inner {
            inset: -8px;
            border-color: rgba(255, 255, 255, .2);
            animation: ringPulseInner 3s ease-out 1.4s forwards;
        }

        @keyframes ringPulseOuter {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ringPulseMiddle {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ringPulseInner {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ringRotate {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes ringRotateReverse {
            to {
                transform: rotate(-360deg);
            }
        }

        .logo-rotating-ring {
            position: absolute;
            inset: -42px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: rgba(147, 197, 253, .4);
            border-right-color: rgba(196, 181, 253, .3);
            opacity: 0;
            animation: rotatingRingIn 1s ease 1.6s forwards, rotatingRingSpin 8s linear 1.6s infinite;
        }

        @keyframes rotatingRingIn {
            to {
                opacity: 1;
            }
        }

        @keyframes rotatingRingSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .logo-glow-effect {
            position: absolute;
            inset: -48px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(147, 197, 253, .3), rgba(196, 181, 253, .2), rgba(56, 189, 248, .15), rgba(99, 102, 241, .25), rgba(147, 197, 253, .3));
            filter: blur(28px);
            opacity: 0;
            animation: glowEffectIn 1s ease 1.8s forwards, glowEffectSpin 10s linear 1.8s infinite, glowEffectPulse 4s ease-in-out 1.8s infinite;
        }

        @keyframes glowEffectIn {
            to {
                opacity: 1;
            }
        }

        @keyframes glowEffectSpin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes glowEffectPulse {

            0%,
            100% {
                transform: rotate(360deg) scale(1);
                opacity: 1;
            }

            50% {
                transform: rotate(360deg) scale(1.15);
                opacity: .6;
            }
        }

        .splash-logo-wrapper {
            position: relative;
            z-index: 2;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: logoFloat 5s ease-in-out infinite 2s;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .splash-logo-img {
            width: 110px;
            height: 110px;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(26, 86, 214, .4));
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  ✅ TITLE CONTAINER "SIPORA" - CENTERED              ║
         * ╚═══════════════════════════════════════════════════════╝
         */
        .splash-title-container {
            text-align: center !important;
            margin-bottom: 16px !important;
            overflow: hidden;

            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;

            width: 100% !important;
            max-width: 420px !important;
            min-width: 0 !important;

            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

            box-sizing: border-box !important;

            opacity: 0;
            transform: translateY(30px) !important;
            animation: titleContainerIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) 1.2s forwards;
        }

        @keyframes titleContainerIn {
            to {
                opacity: 1;
                transform: translateY(0) !important;
            }
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  ✅ MAIN TITLE TEXT "SIPORA" - CENTERED              ║
         * ╚═══════════════════════════════════════════════════════╝
         */
        .splash-main-title {
            font-family: 'Sora', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 12px;
            margin: 0 !important;

            display: flex !important;
            gap: 4px;
            justify-content: center !important;
            align-items: center !important;
            flex-wrap: nowrap !important;

            width: 100% !important;
            max-width: 100% !important;

            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

            transform: none !important;
            box-sizing: border-box !important;
        }

        .title-letter {
            display: inline-block;
            background: linear-gradient(135deg, #93c5fd 0%, #c4b5fd 50%, #93c5fd 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            opacity: 0;
            transform: translateY(40px) rotateX(-90deg);
            animation: letterReveal 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            animation-delay: calc(1.4s + var(--letter-index) * 0.1s);
        }

        .title-letter:nth-child(1) {
            --letter-index: 0;
        }

        .title-letter:nth-child(2) {
            --letter-index: 1;
        }

        .title-letter:nth-child(3) {
            --letter-index: 2;
        }

        .title-letter:nth-child(4) {
            --letter-index: 3;
        }

        .title-letter:nth-child(5) {
            --letter-index: 4;
        }

        .title-letter:nth-child(6) {
            --letter-index: 5;
        }

        @keyframes letterReveal {
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .title-underline {
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #93c5fd, #c4b5fd, transparent);
            margin: 12px auto 0 !important;
            animation: underlineExpand 0.8s ease 2.2s forwards;
        }

        @keyframes underlineExpand {
            to {
                width: 280px;
            }
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  ✅ SUBTITLE CONTAINER - CENTERED                    ║
         * ╚═══════════════════════════════════════════════════════╝
         */
        .splash-subtitle-container {
            text-align: center !important;
            margin-bottom: 40px !important;
            overflow: hidden;

            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;

            width: 100% !important;
            max-width: 420px !important;
            min-width: 0 !important;

            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

            box-sizing: border-box !important;

            opacity: 0;
            transform: translateY(20px) !important;
            animation: subtitleContainerIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) 1.8s forwards;
        }

        @keyframes subtitleContainerIn {
            to {
                opacity: 1;
                transform: translateY(0) !important;
            }
        }

        .splash-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, .75);
            margin: 0 0 8px !important;
            min-height: 24px;
            text-align: center !important;
            width: 100% !important;
        }

        .subtitle-text {
            opacity: 0;
            animation: typingText 2s steps(50) 2.2s forwards;
        }

        @keyframes typingText {
            0% {
                width: 0;
                opacity: 1;
            }

            100% {
                width: 100%;
                opacity: 1;
            }
        }

        .subtitle-text::after {
            content: '';
            display: inline-block;
            width: 0;
        }

        .subtitle-cursor {
            display: inline-block;
            color: #93c5fd;
            font-weight: 300;
            animation: cursorBlink 1s step-end 4s infinite;
            opacity: 0;
        }

        @keyframes cursorBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .splash-tagline {
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, .45);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 !important;
            text-align: center !important;
            width: 100% !important;
            opacity: 0;
            animation: taglineFade 0.6s ease 4s forwards;
        }

        @keyframes taglineFade {
            to {
                opacity: 1;
            }
        }

        /*
         * ╔═══════════════════════════════════════════════════════╗
         * ║  ✅ LOADER CONTAINER - CENTERED                      ║
         * ╚═══════════════════════════════════════════════════════╝
         */
        .splash-loader-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 20px;

            width: 100% !important;
            max-width: 200px !important;
            margin-left: auto !important;
            margin-right: auto !important;

            opacity: 0;
            transform: translateY(20px);
            animation: loaderContainerIn 0.7s ease 2.5s forwards;
        }

        @keyframes loaderContainerIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loader-circle {
            position: relative;
            width: 80px;
            height: 80px;
        }

        .circular-loader {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .loader-bg {
            fill: none;
            stroke: rgba(255, 255, 255, .1);
            stroke-width: 4;
        }

        .loader-progress {
            fill: none;
            stroke: url(#loaderGradient);
            stroke-width: 4;
            stroke-linecap: round;
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            animation: loaderProgressAnim 3.5s ease-in-out 2s forwards;
        }

        @keyframes loaderProgressAnim {
            0% {
                stroke-dashoffset: 283;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        .loader-percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #93c5fd;
            opacity: 0;
            animation: percentageFade 0.4s ease 2.2s forwards;
        }

        @keyframes percentageFade {
            to {
                opacity: 1;
            }
        }

        .loader-wave-container {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 24px;
        }

        .wave-bar {
            width: 3px;
            height: 8px;
            background: linear-gradient(to top, #93c5fd, #c4b5fd);
            border-radius: 2px;
            animation: waveBarBounce 1.2s ease-in-out infinite;
        }

        .wave-bar:nth-child(1) {
            animation-delay: 0s;
        }

        .wave-bar:nth-child(2) {
            animation-delay: 0.1s;
        }

        .wave-bar:nth-child(3) {
            animation-delay: 0.2s;
        }

        .wave-bar:nth-child(4) {
            animation-delay: 0.3s;
        }

        .wave-bar:nth-child(5) {
            animation-delay: 0.4s;
        }

        @keyframes waveBarBounce {

            0%,
            100% {
                height: 8px;
            }

            50% {
                height: 24px;
            }
        }

        /* ============================================
       LEFT PANEL: Hero Dot Grid
       ============================================ */
        .prem-left-dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: .5;
            pointer-events: none;
            z-index: 0;
        }

        /* ============================================
       LEFT PANEL: Hero Decorative Circles
       ============================================ */
        .prem-left-circ {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .prem-left-circ-1 {
            width: 280px;
            height: 280px;
            border: 50px solid rgba(255, 255, 255, .05);
            top: -120px;
            right: -80px;
            animation: premLeftCircFloat 20s ease-in-out infinite;
        }

        .prem-left-circ-2 {
            width: 160px;
            height: 160px;
            border: 30px solid rgba(255, 255, 255, .05);
            bottom: -60px;
            left: 30%;
            animation: premLeftCircFloat 16s 3s ease-in-out infinite reverse;
        }

        @keyframes premLeftCircFloat {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            50% {
                transform: translate(8px, -8px) rotate(3deg);
            }
        }

        /* ============================================
       LEFT PANEL: Eyebrow Badge
       ============================================ */
        .prem-left-eyebrow {
            position: absolute;
            top: 28px;
            left: 28px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .20);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 400;
            color: rgba(255, 255, 255, .85);
            letter-spacing: .5px;
            text-transform: uppercase;
            z-index: 2;
            font-family: 'Inter', sans-serif;
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .prem-left-eyebrow.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-left-eyebrow i {
            font-size: 10px;
        }

        /* ============================================
       LEFT PANEL: Chips
       ============================================ */
        .prem-left-chips {
            position: absolute;
            bottom: 24px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 0 24px;
            z-index: 2;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .6s .1s ease, transform .6s .1s ease;
        }

        .prem-left-chips.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: 400;
            color: rgba(255, 255, 255, .85);
            backdrop-filter: blur(4px);
            font-family: 'Inter', sans-serif;
            transition: transform .2s, background .2s;
        }

        .prem-chip:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .18);
        }

        .prem-chip i {
            font-size: 11px;
        }

        /* ============================================
       LEFT PANEL: Logo Circle
       ============================================ */
        .logo-circle {
            background: transparent !important;
            border: none !important;
            backdrop-filter: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }

        .logo-container:hover .logo-circle {
            transform: none !important;
            box-shadow: none !important;
        }

        .login-card-left h1 {
            font-family: 'Sora', sans-serif !important;
            font-weight: 400 !important;
            letter-spacing: -.3px !important;
        }

        .login-card-left p {
            font-family: 'Inter', sans-serif !important;
        }

        /* ============================================
       RIGHT PANEL: Top Accent Line
       ============================================ */
        .prem-right-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1a56d6, #6366f1, #38bdf8);
            z-index: 2;
        }

        /* ============================================
       RIGHT PANEL: Card Cursor Glow
       ============================================ */
        .prem-card-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 86, 214, .04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            opacity: 0;
            transition: opacity .4s ease;
            transform: translate(-50%, -50%);
        }

        .prem-card-glow.on {
            opacity: 1;
        }

        /* ============================================
       RIGHT PANEL: Trust Line
       ============================================ */
        .prem-trust-line {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
            font-size: 11.5px;
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .5s ease, transform .5s ease;
        }

        .prem-trust-line.show {
            opacity: 1;
            transform: translateY(0);
        }

        .prem-trust-line i {
            font-size: 10px;
            color: #64748b;
            animation: premLockPulse 3s ease-in-out infinite;
        }

        @keyframes premLockPulse {

            0%,
            100% {
                opacity: .4;
            }

            50% {
                opacity: 1;
            }
        }

        /* ============================================
       BUTTON: Shine Sweep (hover only)
       ============================================ */
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

        .btn-primary.is-loading .prem-btn-shine {
            opacity: 0 !important;
        }

        @keyframes premBtnShineGo {
            0% {
                left: -110%;
            }

            100% {
                left: 160%;
            }
        }

        /* ============================================
       RESPONSIVE
       ============================================ */
        @media (max-width: 768px) {
            .splash-gradient-orb {
                filter: blur(50px);
            }

            .prem-bg-orb {
                filter: blur(45px);
            }

            .prem-left-eyebrow {
                top: 18px;
                left: 18px;
                font-size: 9px;
            }

            .prem-left-chips {
                bottom: 18px;
                gap: 6px;
                padding: 0 16px;
            }

            .prem-chip {
                padding: 4px 9px;
                font-size: 10px;
            }

            .prem-left-circ-1 {
                width: 200px;
                height: 200px;
                border-width: 35px;
            }

            .prem-left-circ-2 {
                width: 120px;
                height: 120px;
                border-width: 22px;
            }

            .splash-main-title {
                font-size: 2.4rem;
                letter-spacing: 10px;
            }

            .splash-logo-container {
                width: 155px;
                height: 155px;
                margin-left: -15px !important;
            }

            .splash-logo-wrapper {
                width: 110px;
                height: 110px;
            }

            .splash-logo-img {
                width: 95px;
                height: 95px;
            }

            .login-card {
                max-height: 96vh;
            }

            .prem-logo-ring {
                inset: -10px;
            }

            .prem-logo-ring-2 {
                inset: -18px;
            }

            .prem-logo-glow {
                inset: -24px;
            }

            .loader-circle {
                width: 65px;
                height: 65px;
            }
        }

        @media (max-width: 480px) {
            .splash-gradient-orb {
                filter: blur(30px);
            }

            .prem-bg-orb {
                filter: blur(25px);
            }

            .prem-bg-particles,
            .prem-bg-geo,
            .splash-geo-pattern {
                display: none;
            }

            .prem-left-chips,
            .prem-left-eyebrow,
            .prem-card-glow {
                display: none;
            }

            .prem-left-circ-1 {
                width: 150px;
                height: 150px;
                border-width: 25px;
                top: -80px;
                right: -50px;
            }

            .prem-left-circ-2 {
                width: 90px;
                height: 90px;
                border-width: 18px;
            }

            .splash-main-title {
                font-size: 1.9rem;
                letter-spacing: 7px;
            }

            .splash-subtitle {
                font-size: 0.75rem;
            }

            .splash-tagline {
                font-size: 0.65rem;
            }

            .splash-logo-container {
                width: 135px;
                height: 135px;
                margin-bottom: 24px;
                margin-left: -10px !important;
            }

            .splash-logo-wrapper {
                width: 95px;
                height: 95px;
            }

            .splash-logo-img {
                width: 82px;
                height: 82px;
            }

            .login-card {
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
                inset: -16px;
                filter: blur(15px);
            }

            .loader-circle {
                width: 55px;
                height: 55px;
            }

            .splash-content {
                padding: 24px 20px !important;
            }
        }

        /* ============================================
       REDUCED MOTION
       ============================================ */
        @media (prefers-reduced-motion: reduce) {

            .splash-gradient-orb,
            .splash-geo-pattern,
            .logo-pulse-ring,
            .logo-rotating-ring,
            .logo-glow-effect,
            .splash-logo-wrapper,
            .title-letter,
            .title-underline,
            .subtitle-text,
            .subtitle-cursor,
            .splash-tagline,
            .loader-progress,
            .wave-bar,
            .prem-logo-halo,
            .prem-progress-shimmer,
            .prem-trust-line i,
            .prem-btn-shine,
            .prem-title-shimmer,
            .prem-bg-orb,
            .prem-bg-dot,
            .prem-bg-geo,
            .prem-logo-float,
            .prem-logo-ring,
            .prem-logo-ring-2,
            .prem-logo-glow,
            .prem-left-circ,
            .btn-loading-shimmer,
            .btn-loading-icon {
                animation: none !important;
            }

            .btn-primary.is-loading .btn-loading-icon {
                border-top-color: #fff !important;
                opacity: 1 !important;
            }

            .prem-logo-wrap.enter,
            .splash-logo-container,
            .splash-title-container,
            .splash-subtitle-container,
            .splash-loader-container {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }

            .title-letter {
                opacity: 1 !important;
                transform: none !important;
            }

            .subtitle-text {
                opacity: 1 !important;
            }

            .loader-progress {
                stroke-dashoffset: 0 !important;
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

<body data-google-auth-endpoint="{{ route('auth.google') }}" data-csrf-token="{{ csrf_token() }}"
    data-login-url="{{ route('login') }}">

    <!-- ====== LOGIN BACKGROUND ANIMATIONS ====== -->
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

    <!-- ====== SPLASH SCREEN (LOGO KE KIRI) ====== -->
    <div id="splash-screen">
        <!-- Animated Background Layers -->
        <div class="splash-bg-layer">
            <div class="splash-gradient-orb orb-1"></div>
            <div class="splash-gradient-orb orb-2"></div>
            <div class="splash-gradient-orb orb-3"></div>
            <div class="splash-gradient-orb orb-4"></div>
        </div>

        <!-- Geometric Pattern Overlay -->
        <div class="splash-geo-pattern">
            <div class="geo-line line-1"></div>
            <div class="geo-line line-2"></div>
            <div class="geo-line line-3"></div>
            <div class="geo-circle geo-circ-1"></div>
            <div class="geo-circle geo-circ-2"></div>
            <div class="geo-diamond diamond-1"></div>
            <div class="geo-diamond diamond-2"></div>
        </div>

        <!-- Floating Particles Canvas -->
        <canvas class="splash-particles-canvas"></canvas>

        <!-- Vignette Effect -->
        <div class="splash-vignette"></div>

        <!-- Main Content Container -->
        <div class="splash-content">
            <!--
              ╔════════════════════════════════════════╗
              ║  LOGO CONTAINER - DENGAN INLINE STYLE   ║
              ║  UNTUK GESER KE KIRI                 ║
              ╚════════════════════════════════════════╝
            -->
            <div class="splash-logo-container"
                style="
                     margin-left: 120px !important;
                     margin-right: auto !important;
                 ">
                <div class="logo-pulse-ring ring-outer"></div>
                <div class="logo-pulse-ring ring-middle"></div>
                <div class="logo-pulse-ring ring-inner"></div>
                <div class="logo-rotating-ring"></div>
                <div class="logo-glow-effect"></div>
                <div class="splash-logo-wrapper">
                    <img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije" class="splash-logo-img">
                </div>
            </div>

            <!-- Title with Animated Gradient - CENTERED -->
            <div class="splash-title-container">
                <h1 class="splash-main-title">
                    <span class="title-letter">S</span><span class="title-letter">I</span><span
                        class="title-letter">P</span><span class="title-letter">O</span><span
                        class="title-letter">R</span><span class="title-letter">A</span>
                </h1>
                <div class="title-underline"></div>
            </div>

            <!-- Subtitle with Typing Effect - CENTERED -->
            <div class="splash-subtitle-container">
                <p class="splash-subtitle">
                    <span class="subtitle-text">Sistem Informasi Politeknik Negeri Jember</span>
                    <span class="subtitle-cursor">|</span>
                </p>
                <p class="splash-tagline">Repository Assets System</p>
            </div>

            <!-- Creative Loading Indicator - CENTERED -->
            <div class="splash-loader-container">
                <div class="loader-circle">
                    <svg class="circular-loader" viewBox="0 0 100 100">
                        <circle class="loader-bg" cx="50" cy="50" r="45"></circle>
                        <circle class="loader-progress" cx="50" cy="50" r="45"></circle>
                    </svg>
                    <div class="loader-percentage">0%</div>
                </div>
                <div class="loader-wave-container">
                    <div class="wave-bar"></div>
                    <div class="wave-bar"></div>
                    <div class="wave-bar"></div>
                    <div class="wave-bar"></div>
                    <div class="wave-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== LOGIN CONTAINER ====== -->
    <div class="login-container">
        <div class="login-card" style="position:relative;">
            <div class="prem-card-glow"></div>

            <div class="login-card-left" style="position:relative;overflow:hidden;">
                <div class="prem-left-dots"></div>
                <div class="prem-left-circ prem-left-circ-1"></div>
                <div class="prem-left-circ prem-left-circ-2"></div>
                <div class="prem-left-eyebrow">
                    <i class="bi bi-stars"></i> Portal Repository Akademik
                </div>

                <div class="login-card-left-content">
                    <div class="logo-container">
                        <div class="prem-logo-wrap">
                            <div class="prem-logo-glow"></div>
                            <div class="prem-logo-ring"></div>
                            <div class="prem-logo-ring-2"></div>
                            <div class="prem-logo-float">
                                <div class="logo-circle"><img src="{{ asset('assets/logo_polije.png') }}"
                                        alt="Logo Polije"></div>
                            </div>
                        </div>
                    </div>
                    <h1>Masuk ke SIPORA</h1>
                    <p>Sistem Informasi Politeknik Negeri Jember Repository Assets</p>
                </div>

                <div class="prem-left-chips">
                    <div class="prem-chip"><i class="bi bi-shield-check"></i> Terverifikasi</div>
                    <div class="prem-chip"><i class="bi bi-lock"></i> Terenkripsi</div>
                    <div class="prem-chip"><i class="bi bi-clock"></i> 24/7 Aktif</div>
                </div>
            </div>

            <div class="login-card-right" style="position:relative;">
                <div class="prem-right-accent"></div>

                @if (session('login_error'))
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                        {{ session('login_error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                    </div>
                @endif
                @if (session('reset_success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i>
                        {{ session('reset_success') }}
                    </div>
                @endif

                <form id="loginFormElement" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-input"
                            placeholder="Masukkan username / email / NIM" required
                            value="{{ old('username', request()->cookie('username', '')) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Kata Sandi</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Masukkan kata sandi" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')"><i
                                    class="bi bi-eye" id="password-icon"></i></button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember"
                                {{ request()->cookie('username') ? 'checked' : '' }}>
                            <label for="remember">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.forgot') }}" class="forgot-password">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn-primary" id="btnLogin">
                        <span class="btn-text">Masuk</span>
                        <div class="btn-loading-wrap">
                            <span class="btn-loading-icon"></span>
                            <span class="btn-loading-text">Memproses</span>
                        </div>
                        <div class="btn-loading-shimmer"></div>
                    </button>
                </form>

                <div class="divider"><span>Atau masuk dengan</span></div>
                <div class="social-login">
                    <div id="googleSignInButton"></div>
                </div>

                <div class="prem-trust-line">
                    <i class="fas fa-lock"></i>
                    <span>Dilindungi enkripsi SSL 256-bit</span>
                </div>

                <p class="register-link" style="margin-top:16px;text-align:center;">Belum punya akun? <a
                        href="{{ route('register') }}">Daftar sekarang</a></p>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/auth-login.js') }}"></script>

    <script>
        (function() {
            'use strict';

            /* ==============================================
               1. PARTICLES CANVAS SYSTEM (Splash)
               ============================================== */
            var canvas = document.querySelector('.splash-particles-canvas');
            var ctx, particles = [],
                mouse = {
                    x: -999,
                    y: -999
                },
                animId = null;
            var P_COUNT = 60,
                MAX_D = 120;

            if (canvas) {
                ctx = canvas.getContext('2d');
                var splash = document.getElementById('splash-screen');

                function resize() {
                    canvas.width = splash.offsetWidth;
                    canvas.height = splash.offsetHeight;
                }
                resize();
                window.addEventListener('resize', resize);

                for (var i = 0; i < P_COUNT; i++) {
                    particles.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        vx: (Math.random() - .5) * .3,
                        vy: (Math.random() - .5) * .3,
                        r: Math.random() * 2 + 0.5,
                        c: Math.random() > .5 ? '147,197,253' : '196,181,253',
                        alpha: Math.random() * 0.5 + 0.3
                    });
                }

                splash.addEventListener('mousemove', function(e) {
                    var r = splash.getBoundingClientRect();
                    mouse.x = e.clientX - r.left;
                    mouse.y = e.clientY - r.top;
                });
                splash.addEventListener('mouseleave', function() {
                    mouse.x = -999;
                    mouse.y = -999;
                });

                function loop() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    for (var i = 0; i < particles.length; i++) {
                        var p = particles[i];
                        var dx = mouse.x - p.x,
                            dy = mouse.y - p.y;
                        var md = Math.sqrt(dx * dx + dy * dy);
                        if (md < 150 && md > 0) {
                            p.vx -= dx / md * .02;
                            p.vy -= dy / md * .02;
                        }
                        p.vx *= .99;
                        p.vy *= .99;
                        p.x += p.vx;
                        p.y += p.vy;
                        if (p.x < -10) p.x = canvas.width + 10;
                        if (p.x > canvas.width + 10) p.x = -10;
                        if (p.y < -10) p.y = canvas.height + 10;
                        if (p.y > canvas.height + 10) p.y = -10;

                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = 'rgba(' + p.c + ',' + p.alpha + ')';
                        ctx.fill();

                        for (var j = i + 1; j < particles.length; j++) {
                            var q = particles[j];
                            var ddx = p.x - q.x,
                                ddy = p.y - q.y;
                            var d = Math.sqrt(ddx * ddx + ddy * ddy);
                            if (d < MAX_D) {
                                ctx.beginPath();
                                ctx.moveTo(p.x, p.y);
                                ctx.lineTo(q.x, q.y);
                                ctx.strokeStyle = 'rgba(147,197,253,' + ((1 - d / MAX_D) * .15) + ')';
                                ctx.lineWidth = 0.5;
                                ctx.stroke();
                            }
                        }
                    }
                    animId = requestAnimationFrame(loop);
                }
                loop();
            }

            function stopParticles() {
                if (animId) {
                    cancelAnimationFrame(animId);
                    animId = null;
                }
            }

            /* ==============================================
               2. LOADING PERCENTAGE COUNTER
               ============================================== */
            var percentageEl = document.querySelector('.loader-percentage');
            var loadingComplete = false;

            if (percentageEl) {
                var currentPercent = 0;
                var targetPercent = 100;
                var duration = 3500;
                var startTime = null;

                function updatePercentage(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = (timestamp - startTime) / duration;

                    if (progress < 1) {
                        currentPercent = Math.floor(progress * targetPercent);
                        percentageEl.textContent = currentPercent + '%';
                        requestAnimationFrame(updatePercentage);
                    } else {
                        currentPercent = 100;
                        percentageEl.textContent = '100%';
                        loadingComplete = true;
                        console.log('✅ [INLINE] Loading Complete! 100% reached.');
                        window.splashLoadingComplete = true;
                        console.log('✅ [INLINE] Flag window.splashLoadingComplete = true');
                        setTimeout(function() {
                            dismissSplash();
                        }, 500);
                    }
                }

                setTimeout(function() {
                    console.log('🚀 [INLINE] Loading counter started...');
                    requestAnimationFrame(updatePercentage);
                }, 2000);
            }

            /* ==============================================
               3. SPLASH DISMISS SYSTEM
               ============================================== */
            var spl = document.getElementById('splash-screen');
            var ctr = document.querySelector('.login-container');

            function dismissSplash() {
                if (!spl) {
                    console.log('❌ Splash not found');
                    return;
                }
                if (spl.dataset.gone === 'true') {
                    console.log('❌ Already dismissed');
                    return;
                }
                if (!loadingComplete) {
                    console.log('⚠️ Cannot dismiss: Not 100% yet (' + (percentageEl ? percentageEl.textContent :
                        'N/A') + ')');
                    return;
                }

                console.log('🎬 [INLINE] Dismissing splash screen...');
                spl.dataset.gone = 'true';
                stopParticles();
                spl.classList.add('hide');

                setTimeout(function() {
                    spl.style.display = 'none';
                    if (ctr) ctr.classList.add('visible');
                    revealElements();
                    console.log('✨ [INLINE] Login form revealed!');
                }, 800);
            }

            /* ==============================================
               4. REVEAL ELEMENTS AFTER SPLASH
               ============================================== */
            function revealElements() {
                setTimeout(function() {
                    var wrap = document.querySelector('.prem-logo-wrap');
                    if (wrap) wrap.classList.add('enter');
                }, 200);
                setTimeout(function() {
                    var ring = document.querySelector('.prem-logo-ring');
                    if (ring) ring.classList.add('go');
                }, 400);
                setTimeout(function() {
                    var ring2 = document.querySelector('.prem-logo-ring-2');
                    if (ring2) ring2.classList.add('go');
                }, 550);
                setTimeout(function() {
                    var glow = document.querySelector('.prem-logo-glow');
                    if (glow) glow.classList.add('go');
                }, 350);

                setTimeout(function() {
                    var e = document.querySelector('.prem-left-eyebrow');
                    if (e) e.classList.add('show');
                }, 300);
                setTimeout(function() {
                    var c = document.querySelector('.prem-left-chips');
                    if (c) c.classList.add('show');
                }, 450);

                setTimeout(function() {
                    var t = document.querySelector('.prem-trust-line');
                    if (t) t.classList.add('show');
                }, 600);
            }

            /* ==============================================
               5. CARD CURSOR GLOW
               ============================================== */
            var card = document.querySelector('.login-card');
            var glow = document.querySelector('.prem-card-glow');
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

            /* ==============================================
               6. BUTTON SHINE ON HOVER
               ============================================== */
            var btn = document.getElementById('btnLogin');
            if (btn) {
                var bs = document.createElement('div');
                bs.className = 'prem-btn-shine';
                btn.appendChild(bs);
                btn.addEventListener('mouseenter', function() {
                    if (btn.classList.contains('is-loading')) return;
                    bs.style.animation = 'none';
                    void bs.offsetWidth;
                    bs.style.animation = 'premBtnShineGo .6s ease forwards';
                });
                bs.addEventListener('animationend', function() {
                    bs.style.animation = 'none';
                });
            }

            /* ==============================================
               7. FORM LOADING STATE
               ============================================== */
            var form = document.getElementById('loginFormElement');
            if (form && btn) {
                form.addEventListener('submit', function() {
                    btn.classList.add('is-loading');

                    setTimeout(function() {
                        if (document.visibilityState === 'visible' && btn.classList.contains(
                                'is-loading')) {
                            btn.classList.remove('is-loading');
                        }
                    }, 8000);
                });
            }

            /* DEBUG LOG */
            console.log('💡 [INLINE] Splash Screen Initialized (LOGO SHIFTED LEFT VERSION)');
        })();
    </script>
</body>

</html>
