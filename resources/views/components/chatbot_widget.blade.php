@once
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --sbot-navy: #0b1b4d;
            --sbot-blue: #1a56d6;
            --sbot-blue-mid: #2563eb;
            --sbot-blue-light: #3b82f6;
            --sbot-blue-pale: #eff6ff;
            --sbot-indigo: #6366f1;
            --sbot-green: #22c55e;
            --sbot-rose: #f43f5e;

            --sbot-bg-window: #ffffff;
            --sbot-bg-header: #f8fafc;
            --sbot-bg-input: #ffffff;
            --sbot-bg-bubble-ai: #f1f5f9;
            --sbot-bg-bubble-usr: #1a56d6;
            --sbot-bg-code: #0f172a;

            --sbot-border: #e2e8f0;
            --sbot-border-focus: #3b82f6;

            --sbot-t1: #0f172a;
            --sbot-t2: #475569;
            --sbot-t3: #94a3b8;
            --sbot-t-user: #ffffff;
            --sbot-t-accent: #1a56d6;

            --sbot-shadow-fab: 0 8px 28px rgba(26, 86, 214, .38), 0 2px 8px rgba(15, 23, 42, .12);
            --sbot-shadow-win: 0 24px 64px rgba(15, 23, 42, .15), 0 0 0 1px rgba(15, 23, 42, .06);
            --sbot-shadow-bubble: 0 1px 3px rgba(15, 23, 42, .06);

            --sbot-r-sm: 8px;
            --sbot-r-md: 12px;
            --sbot-r-lg: 16px;
            --sbot-r-xl: 20px;
            --sbot-ease: cubic-bezier(.22, .68, 0, 1.2);
            --sbot-tr: all .25s var(--sbot-ease);

            --sbot-font-d: 'Sora', sans-serif;
            --sbot-font-b: 'Inter', sans-serif;
        }

        /* ── FAB ──────────────────────────────────── */
        #sbotFab {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 58px;
            height: 58px;
            border-radius: 16px;
            /* square-ish, not circle */
            border: none;
            cursor: pointer;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--sbot-navy) 0%, var(--sbot-blue) 100%);
            box-shadow: var(--sbot-shadow-fab);
            transition: var(--sbot-tr);
            outline: none;
        }

        #sbotFab:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 14px 36px rgba(26, 86, 214, .42);
        }

        #sbotFab:active {
            transform: scale(.95);
        }

        .sbot-fab-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform .35s var(--sbot-ease);
            color: #fff;
        }

        #sbotFab.sbot-open .sbot-fab-icon {
            transform: rotate(90deg);
        }

        /* Ripple ring */
        #sbotFab::before {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 20px;
            border: 2px solid rgba(26, 86, 214, .4);
            opacity: 0;
            animation: sbot-pulse 2.8s ease-out infinite;
        }

        #sbotFab.sbot-open::before {
            animation: none;
            opacity: 0;
        }

        @keyframes sbot-pulse {
            0% {
                transform: scale(1);
                opacity: .55;
            }

            100% {
                transform: scale(1.55);
                opacity: 0;
            }
        }

        .sbot-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--sbot-rose);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2.5px solid #fff;
            font-family: var(--sbot-font-d);
            transition: var(--sbot-tr);
        }

        #sbotFab.sbot-open .sbot-badge {
            transform: scale(0);
            opacity: 0;
        }

        /* ── WINDOW ───────────────────────────────── */
        #sbotWindow {
            position: fixed;
            bottom: 104px;
            right: 28px;
            width: 400px;
            height: 600px;
            max-height: calc(100vh - 140px);
            max-width: calc(100vw - 24px);
            background: var(--sbot-bg-window);
            border: 1px solid var(--sbot-border);
            border-radius: var(--sbot-r-xl);
            z-index: 999998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--sbot-shadow-win);
            transform: scale(.4) translateY(30px);
            opacity: 0;
            pointer-events: none;
            transform-origin: bottom right;
            transition: transform .38s var(--sbot-ease), opacity .22s ease;
        }

        #sbotWindow.sbot-open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: all;
        }

        /* ── HEADER ───────────────────────────────── */
        .sbot-header {
            padding: 14px 16px;
            background: linear-gradient(130deg, var(--sbot-navy) 0%, var(--sbot-blue) 100%);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        /* dot pattern */
        .sbot-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .10) 1px, transparent 1px);
            background-size: 18px 18px;
            pointer-events: none;
        }

        .sbot-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--sbot-r-md);
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }

        .sbot-online {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 11px;
            height: 11px;
            background: var(--sbot-green);
            border: 2px solid var(--sbot-navy);
            border-radius: 50%;
        }

        .sbot-header-info {
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .sbot-header-info h3 {
            font-family: var(--sbot-font-d);
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sbot-model-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            color: rgba(255, 255, 255, .9);
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .sbot-header-info p {
            font-size: 11.5px;
            color: rgba(255, 255, 255, .70);
            margin: 3px 0 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sbot-status-dot {
            width: 6px;
            height: 6px;
            background: var(--sbot-green);
            border-radius: 50%;
            display: inline-block;
        }

        .sbot-header-actions {
            margin-left: auto;
            display: flex;
            gap: 2px;
            position: relative;
            z-index: 1;
        }

        .sbot-hdr-btn {
            width: 32px;
            height: 32px;
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(255, 255, 255, .10);
            color: rgba(255, 255, 255, .8);
            border-radius: var(--sbot-r-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: var(--sbot-tr);
        }

        .sbot-hdr-btn:hover {
            background: rgba(255, 255, 255, .22);
            color: #fff;
            border-color: rgba(255, 255, 255, .3);
        }

        /* ── MESSAGES ─────────────────────────────── */
        .sbot-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fafbfd;
        }

        .sbot-messages::-webkit-scrollbar {
            width: 4px;
        }

        .sbot-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .sbot-messages::-webkit-scrollbar-thumb {
            background: var(--sbot-border);
            border-radius: 4px;
        }

        /* Welcome */
        .sbot-welcome {
            text-align: center;
            padding: 12px 6px 6px;
            animation: sbot-fadeIn .45s ease;
        }

        @keyframes sbot-fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .sbot-welcome-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            box-shadow: 0 8px 24px rgba(26, 86, 214, .25);
        }

        .sbot-welcome h4 {
            font-family: var(--sbot-font-d);
            font-size: 15.5px;
            font-weight: 700;
            color: var(--sbot-t1);
            margin: 0 0 6px;
        }

        .sbot-welcome p {
            font-size: 12.5px;
            color: var(--sbot-t2);
            margin: 0 auto;
            max-width: 280px;
            line-height: 1.6;
        }

        /* Chips */
        .sbot-chips {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-top: 14px;
        }

        .sbot-chip {
            padding: 10px 13px;
            background: #fff;
            border: 1px solid var(--sbot-border);
            border-radius: var(--sbot-r-md);
            cursor: pointer;
            text-align: left;
            font-size: 12.5px;
            color: var(--sbot-t2);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--sbot-tr);
            font-family: var(--sbot-font-d);
        }

        .sbot-chip:hover {
            border-color: var(--sbot-blue);
            color: var(--sbot-blue);
            background: var(--sbot-blue-pale);
            transform: translateX(3px);
            box-shadow: 0 2px 10px rgba(26, 86, 214, .10);
        }

        .sbot-chip-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--sbot-r-sm);
            background: var(--sbot-blue-pale);
            color: var(--sbot-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .sbot-chip:nth-child(1) {
            animation: sbot-slideUp .38s .08s ease both;
        }

        .sbot-chip:nth-child(2) {
            animation: sbot-slideUp .38s .16s ease both;
        }

        .sbot-chip:nth-child(3) {
            animation: sbot-slideUp .38s .24s ease both;
        }

        .sbot-chip:nth-child(4) {
            animation: sbot-slideUp .38s .32s ease both;
        }

        @keyframes sbot-slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bubbles */
        .sbot-msg {
            display: flex;
            gap: 9px;
            animation: sbot-msgIn .3s var(--sbot-ease) both;
            max-width: 100%;
        }

        @keyframes sbot-msgIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sbot-msg.sbot-user {
            flex-direction: row-reverse;
        }

        .sbot-msg-av {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            margin-top: 3px;
        }

        .sbot-msg.sbot-ai .sbot-msg-av {
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            color: #fff;
        }

        .sbot-msg.sbot-user .sbot-msg-av {
            background: linear-gradient(135deg, var(--sbot-blue), var(--sbot-indigo));
            color: #fff;
        }

        .sbot-bubble {
            max-width: 84%;
            padding: 11px 14px;
            font-size: 13px;
            line-height: 1.65;
            word-wrap: break-word;
            font-family: var(--sbot-font-b);
        }

        .sbot-msg.sbot-ai .sbot-bubble {
            background: #fff;
            border: 1px solid var(--sbot-border);
            border-radius: 4px var(--sbot-r-lg) var(--sbot-r-lg) var(--sbot-r-lg);
            color: var(--sbot-t1);
            box-shadow: var(--sbot-shadow-bubble);
        }

        .sbot-msg.sbot-user .sbot-bubble {
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            border-radius: var(--sbot-r-lg) 4px var(--sbot-r-lg) var(--sbot-r-lg);
            color: #fff;
            box-shadow: 0 4px 14px rgba(26, 86, 214, .22);
        }

        .sbot-bubble strong {
            font-weight: 600;
            color: var(--sbot-blue);
        }

        .sbot-msg.sbot-user .sbot-bubble strong {
            color: #bfdbfe;
        }

        .sbot-bubble ul,
        .sbot-bubble ol {
            padding-left: 18px;
            margin: 5px 0;
        }

        .sbot-bubble li {
            margin-bottom: 3px;
        }

        .sbot-bubble p {
            margin-bottom: 7px;
        }

        .sbot-bubble p:last-child {
            margin-bottom: 0;
        }

        .sbot-bubble pre {
            background: var(--sbot-bg-code);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: var(--sbot-r-sm);
            padding: 12px;
            margin: 8px 0;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            line-height: 1.6;
            color: #e2e8f0;
        }

        .sbot-bubble p code {
            background: var(--sbot-bg-code);
            color: #93c5fd;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }

        /* Message actions */
        .sbot-msg-acts {
            display: flex;
            gap: 1px;
            margin-top: 5px;
            opacity: 0;
            transition: opacity .18s;
        }

        .sbot-msg:hover .sbot-msg-acts {
            opacity: 1;
        }

        .sbot-msg.sbot-user .sbot-msg-acts {
            justify-content: flex-end;
        }

        .sbot-msg-act {
            padding: 3px 7px;
            border: none;
            background: none;
            color: var(--sbot-t3);
            cursor: pointer;
            border-radius: 5px;
            font-size: 11px;
            transition: var(--sbot-tr);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sbot-msg-act:hover {
            background: var(--sbot-blue-pale);
            color: var(--sbot-blue);
        }

        /* Typing dots */
        .sbot-typing {
            display: flex;
            gap: 9px;
            animation: sbot-msgIn .25s ease both;
        }

        .sbot-typing-dots {
            padding: 12px 16px;
            background: #fff;
            border: 1px solid var(--sbot-border);
            border-radius: 4px var(--sbot-r-lg) var(--sbot-r-lg) var(--sbot-r-lg);
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: var(--sbot-shadow-bubble);
        }

        .sbot-typing-dots span {
            width: 6px;
            height: 6px;
            background: var(--sbot-blue-light);
            border-radius: 50%;
            animation: sbot-bounce 1.3s infinite;
        }

        .sbot-typing-dots span:nth-child(2) {
            animation-delay: .14s;
        }

        .sbot-typing-dots span:nth-child(3) {
            animation-delay: .28s;
        }

        @keyframes sbot-bounce {

            0%,
            60%,
            100% {
                transform: translateY(0);
                opacity: .4;
            }

            30% {
                transform: translateY(-5px);
                opacity: 1;
            }
        }

        .sbot-cursor::after {
            content: '▍';
            color: var(--sbot-blue);
            animation: sbot-blink .65s infinite;
        }

        @keyframes sbot-blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0
            }
        }

        /* ── INPUT AREA ───────────────────────────── */
        .sbot-input-area {
            padding: 12px 14px 14px;
            border-top: 1px solid var(--sbot-border);
            background: var(--sbot-bg-header);
            flex-shrink: 0;
        }

        .sbot-input-box {
            display: flex;
            align-items: flex-end;
            background: #fff;
            border: 1.5px solid var(--sbot-border);
            border-radius: var(--sbot-r-lg);
            padding: 6px 6px 6px 14px;
            transition: var(--sbot-tr);
            box-shadow: 0 1px 4px rgba(15, 23, 42, .05);
        }

        .sbot-input-box:focus-within {
            border-color: var(--sbot-blue);
            box-shadow: 0 0 0 3px rgba(26, 86, 214, .10), 0 1px 4px rgba(15, 23, 42, .05);
        }

        .sbot-input-box textarea {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--sbot-t1);
            font-family: var(--sbot-font-b);
            font-size: 13px;
            resize: none;
            max-height: 96px;
            padding: 7px 0;
            line-height: 1.5;
        }

        .sbot-input-box textarea::placeholder {
            color: var(--sbot-t3);
        }

        .sbot-send-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--sbot-r-md);
            border: none;
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            transition: var(--sbot-tr);
            box-shadow: 0 3px 10px rgba(26, 86, 214, .25);
        }

        .sbot-send-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 18px rgba(26, 86, 214, .35);
        }

        .sbot-send-btn:disabled {
            opacity: .35;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .sbot-hint {
            text-align: center;
            font-size: 10px;
            color: var(--sbot-t3);
            margin-top: 7px;
            font-family: var(--sbot-font-b);
        }

        .sbot-hint kbd {
            background: #f1f5f9;
            border: 1px solid var(--sbot-border);
            border-radius: 3px;
            padding: 0 5px;
            font-family: inherit;
            font-size: 9px;
        }

        /* ── TOASTS ───────────────────────────────── */
        #sbotToasts {
            position: fixed;
            bottom: 104px;
            right: 28px;
            z-index: 1000000;
            display: flex;
            flex-direction: column;
            gap: 6px;
            pointer-events: none;
        }

        .sbot-toast {
            padding: 9px 14px;
            background: #fff;
            border: 1px solid var(--sbot-border);
            border-radius: var(--sbot-r-md);
            color: var(--sbot-t1);
            font-size: 12px;
            font-family: var(--sbot-font-d);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
            animation: sbot-toastIn .28s ease, sbot-toastOut .28s ease 2.5s forwards;
            pointer-events: auto;
        }

        .sbot-toast.sbot-ok {
            border-left: 3px solid var(--sbot-blue);
        }

        .sbot-toast.sbot-err {
            border-left: 3px solid var(--sbot-rose);
        }

        @keyframes sbot-toastIn {
            from {
                opacity: 0;
                transform: translateX(24px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes sbot-toastOut {
            to {
                opacity: 0;
                transform: translateX(24px)
            }
        }

        /* ── RESPONSIVE ───────────────────────────── */
        @media(max-width:480px) {
            #sbotWindow {
                bottom: 0;
                right: 0;
                width: 100vw;
                height: 100vh;
                height: 100dvh;
                max-height: 100vh;
                max-height: 100dvh;
                border-radius: 0;
                transform-origin: bottom center;
            }

            #sbotFab {
                bottom: 20px;
                right: 20px;
                width: 52px;
                height: 52px;
                border-radius: 14px;
            }

            .sbot-hint {
                display: none;
            }
        }

        @media(min-width:481px) and (max-width:768px) {
            #sbotWindow {
                width: calc(100vw - 32px);
                right: 16px;
                bottom: 92px;
            }

            #sbotFab {
                right: 16px;
                bottom: 20px;
            }
        }
    </style>
@endonce

{{-- FAB Button --}}
<button id="sbotFab" onclick="sbotToggle()" aria-label="Buka SIPORA AI">
    <span class="sbot-fab-icon">
        {{-- Sparkle / AI icon --}}
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
            <path d="M5 5l1.5 1.5M17.5 5L19 6.5M5 19l1.5-1.5M17.5 19l1.5-1.5" opacity=".5" />
        </svg>
    </span>
    <span class="sbot-badge" id="sbotBadge">1</span>
</button>

{{-- Chat Window --}}
<div id="sbotWindow">

    {{-- Header --}}
    <div class="sbot-header">
        <div class="sbot-header-avatar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
            </svg>
            <span class="sbot-online"></span>
        </div>
        <div class="sbot-header-info">
            <h3>SIPORA AI <span class="sbot-model-badge">Pro</span></h3>
            <p><span class="sbot-status-dot"></span> Online — siap membantu</p>
        </div>
        <div class="sbot-header-actions">
            {{-- New chat --}}
            <button class="sbot-hdr-btn" onclick="sbotClear()" title="Chat baru">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
            </button>
            {{-- Close --}}
            <button class="sbot-hdr-btn" onclick="sbotToggle()" title="Tutup">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Messages --}}
    <div class="sbot-messages" id="sbotMessages">
        <div class="sbot-welcome" id="sbotWelcome">
            <div class="sbot-welcome-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
                </svg>
            </div>
            <h4>SIPORA AI Assistant</h4>
            <p>Asisten cerdas untuk repository akademik Anda. Tanya tentang dokumen, panduan, atau analisis data.</p>
            <div class="sbot-chips">
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </div>
                    <span>Cari dokumen tentang machine learning</span>
                </button>
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                    </div>
                    <span>Cara upload tugas akhir di SIPORA?</span>
                </button>
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20V10" />
                            <path d="M18 20V4" />
                            <path d="M6 20v-4" />
                        </svg>
                    </div>
                    <span>Rangkum statistik dokumen saya</span>
                </button>
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <span>Info standar Turnitin SIPORA</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Input --}}
    <div class="sbot-input-area">
        <div class="sbot-input-box">
            <textarea id="sbotInput" rows="1" placeholder="Tanya tentang SIPORA..." onkeydown="sbotKey(event)"
                oninput="sbotResize(this);sbotUpdateBtn()"></textarea>
            <button class="sbot-send-btn" id="sbotSendBtn" onclick="sbotSend()" disabled>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13" />
                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                </svg>
            </button>
        </div>
        <div class="sbot-hint"><kbd>Enter</kbd> kirim &middot; <kbd>Shift+Enter</kbd> baris baru</div>
    </div>
</div>

<div id="sbotToasts"></div>

@once
    <script>
        (function() {
            var isOpen = false,
                isTyping = false,
                messages = [];

            var kb = {
                cari: '<strong>Hasil Pencarian Dokumen</strong>\n\nSaya menemukan dokumen terkait:\n\n1. <strong>Implementasi Machine Learning untuk Klasifikasi Gambar</strong>\n   Prodi: Teknik Informatika · Tahun: 2024 · Turnitin: 18% · Status: Disetujui\n\n2. <strong>Analisis Sentimen Media Sosial Menggunakan NLP</strong>\n   Prodi: Sistem Informasi · Tahun: 2024 · Turnitin: 15% · Status: Review\n\n3. <strong>Deep Learning untuk Deteksi Penyakit Daun</strong>\n   Prodi: Teknik Informatika · Tahun: 2023 · Turnitin: 21% · Status: Revisi\n\nGunakan filter di dashboard untuk mempersempit hasil.',
                upload: '<strong>Cara Upload Dokumen di SIPORA</strong>\n\n1. Klik menu <strong>"Unggah Dokumen"</strong> di navbar\n2. Isi form: judul, abstrak (min. 100 karakter), jurusan, prodi, tema, tahun\n3. Upload file PDF (maks. 25 MB)\n4. Klik <strong>"Simpan"</strong>\n\n<strong>Catatan:</strong>\n- Format wajib <strong>PDF</strong>, tidak boleh terproteksi\n- Turnitin berjalan otomatis setelah upload',
                rangkum: '<strong>Statistik Dokumen Anda</strong>\n\nTotal dokumen: <strong>3</strong>\nRata-rata Turnitin: <strong>18%</strong>\nUpload terbaru: 3 hari lalu\n\n<strong>Status</strong>\n- Disetujui: 1 · Review: 1 · Revisi: 1\n\n<strong>Rekomendasi:</strong>\n1 dokumen perlu revisi (Turnitin &gt; 20%)',
                turnitin: '<strong>Standar Turnitin SIPORA</strong>\n\n🟢 <strong>&lt; 20%</strong> — Aman, diproses langsung\n🟡 <strong>20%–30%</strong> — Perlu review dosen\n🔴 <strong>&gt; 30%</strong> — Wajib revisi\n\nHasil keluar dalam <strong>5–15 menit</strong> setelah upload.',
                def: 'Sebagai <strong>SIPORA AI</strong>, saya bisa membantu:\n\n- Pencarian dokumen berdasarkan topik\n- Statistik & rangkuman dokumen Anda\n- Panduan upload dokumen\n- Info standar Turnitin\n- Cara kerja fitur SIPORA\n\nBisa jelaskan lebih spesifik kebutuhan Anda?'
            };

            function getReply(m) {
                var l = m.toLowerCase();
                if (/cari|dokumen|temukan|machine learning/.test(l)) return kb.cari;
                if (/upload|unggah|cara/.test(l)) return kb.upload;
                if (/rangkum|statistik|data/.test(l)) return kb.rangkum;
                if (/turnitin|plagiasi|similar|standar/.test(l)) return kb.turnitin;
                return kb.def;
            }

            window.sbotToggle = function() {
                isOpen = !isOpen;
                document.getElementById('sbotWindow').classList.toggle('sbot-open', isOpen);
                document.getElementById('sbotFab').classList.toggle('sbot-open', isOpen);
                var icon = document.querySelector('#sbotFab .sbot-fab-icon');
                if (isOpen) {
                    icon.innerHTML =
                        '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
                    var b = document.getElementById('sbotBadge');
                    if (b) b.style.display = 'none';
                    setTimeout(function() {
                        document.getElementById('sbotInput').focus();
                    }, 400);
                } else {
                    icon.innerHTML =
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z"/><path d="M5 5l1.5 1.5M17.5 5L19 6.5M5 19l1.5-1.5M17.5 19l1.5-1.5" opacity=".5"/></svg>';
                }
            };

            window.sbotSend = function() {
                var input = document.getElementById('sbotInput');
                var text = input.value.trim();
                if (!text || isTyping) return;
                var w = document.getElementById('sbotWelcome');
                if (w) w.style.display = 'none';
                addBubble('user', text);
                input.value = '';
                input.style.height = 'auto';
                sbotUpdateBtn();
                isTyping = true;
                var c = document.getElementById('sbotMessages');
                var td = document.createElement('div');
                td.className = 'sbot-typing';
                td.id = 'sbotTypingEl';
                td.innerHTML = avHTML('ai') +
                    '<div class="sbot-typing-dots"><span></span><span></span><span></span></div>';
                c.appendChild(td);
                scrollDown();
                setTimeout(function() {
                    var te = document.getElementById('sbotTypingEl');
                    if (te) te.remove();
                    isTyping = false;
                    addBubble('ai', getReply(text), true);
                }, 700 + Math.random() * 1100);
            };

            function avHTML(role) {
                var sparkle =
                    '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z"/></svg>';
                var user =
                    '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
                return '<div class="sbot-msg-av">' + (role === 'ai' ? sparkle : user) + '</div>';
            }

            function addBubble(role, text, shouldType) {
                var c = document.getElementById('sbotMessages');
                messages.push({
                    role: role,
                    text: text
                });
                var idx = messages.length - 1;
                var div = document.createElement('div');
                div.className = 'sbot-msg sbot-' + role;
                var copyB = '<div class="sbot-msg-acts"><button class="sbot-msg-act" onclick="sbotCopyMsg(' + idx +
                    ')" title="Salin"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Salin</button>';
                var regenB = '<button class="sbot-msg-act" onclick="sbotRegen(' + idx +
                    ')" title="Coba lagi"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg> Ulang</button></div>';
                if (role === 'user') {
                    div.innerHTML = avHTML('user') + '<div><div class="sbot-bubble">' + escHtml(text) + '</div>' +
                        copyB + '</div>';
                    c.appendChild(div);
                    scrollDown();
                } else {
                    div.innerHTML = avHTML('ai') + '<div><div class="sbot-bubble" id="sbotBubble' + idx + '"></div>' +
                        copyB + regenB + '</div>';
                    c.appendChild(div);
                    scrollDown();
                    if (shouldType) typeText(text, 'sbotBubble' + idx);
                    else document.getElementById('sbotBubble' + idx).innerHTML = text;
                }
            }

            function typeText(html, elId) {
                var el = document.getElementById(elId);
                if (!el) return;
                isTyping = true;
                el.classList.add('sbot-cursor');
                var tmp = document.createElement('div');
                tmp.innerHTML = html;
                var full = tmp.innerHTML;
                var i = 0;

                function tick() {
                    if (i < full.length) {
                        i += 5;
                        el.innerHTML = full.substring(0, i);
                        scrollDown();
                        setTimeout(tick, 6);
                    } else {
                        el.innerHTML = full;
                        el.classList.remove('sbot-cursor');
                        isTyping = false;
                    }
                }
                tick();
            }

            function escHtml(t) {
                var d = document.createElement('div');
                d.textContent = t;
                return d.innerHTML.replace(/\n/g, '<br>');
            }

            function scrollDown() {
                var el = document.getElementById('sbotMessages');
                if (el) setTimeout(function() {
                    el.scrollTop = el.scrollHeight;
                }, 10);
            }

            window.sbotCopyMsg = function(idx) {
                var msg = messages[idx];
                if (!msg) return;
                var tmp = document.createElement('div');
                tmp.innerHTML = msg.text;
                navigator.clipboard.writeText(tmp.textContent).then(function() {
                    sbotToast('Disalin!', 'ok');
                });
            };

            window.sbotRegen = function(idx) {
                if (isTyping) return;
                var msg = messages[idx];
                if (!msg || msg.role !== 'ai') return;
                var c = document.getElementById('sbotMessages');
                var aiMsgs = c.querySelectorAll('.sbot-msg.sbot-ai');
                if (aiMsgs.length) aiMsgs[aiMsgs.length - 1].remove();
                messages.splice(idx, 1);
                var lastUser = null;
                for (var i = messages.length - 1; i >= 0; i--) {
                    if (messages[i].role === 'user') {
                        lastUser = messages[i];
                        break;
                    }
                }
                if (lastUser) {
                    isTyping = true;
                    var td = document.createElement('div');
                    td.className = 'sbot-typing';
                    td.id = 'sbotTypingEl';
                    td.innerHTML = avHTML('ai') +
                        '<div class="sbot-typing-dots"><span></span><span></span><span></span></div>';
                    c.appendChild(td);
                    scrollDown();
                    setTimeout(function() {
                        var te = document.getElementById('sbotTypingEl');
                        if (te) te.remove();
                        isTyping = false;
                        addBubble('ai', getReply(lastUser.text), true);
                    }, 800 + Math.random() * 700);
                }
            };

            window.sbotClear = function() {
                messages = [];
                var c = document.getElementById('sbotMessages');
                c.innerHTML = document.querySelector('#sbotWelcome') ? '' : '';
                var wHtml =
                    '<div class="sbot-welcome" id="sbotWelcome"><div class="sbot-welcome-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z"/></svg></div><h4>SIPORA AI Assistant</h4><p>Asisten cerdas untuk repository akademik Anda.</p><div class="sbot-chips"><button class="sbot-chip" onclick="sbotUseChip(this)"><div class="sbot-chip-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div><span>Cari dokumen tentang machine learning</span></button><button class="sbot-chip" onclick="sbotUseChip(this)"><div class="sbot-chip-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><span>Cara upload tugas akhir di SIPORA?</span></button><button class="sbot-chip" onclick="sbotUseChip(this)"><div class="sbot-chip-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></div><span>Rangkum statistik dokumen saya</span></button><button class="sbot-chip" onclick="sbotUseChip(this)"><div class="sbot-chip-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div><span>Info standar Turnitin SIPORA</span></button></div></div>';
                c.innerHTML = wHtml;
                sbotToast('Chat baru dimulai', 'ok');
            };

            window.sbotUseChip = function(el) {
                var text = el.querySelector('span').textContent;
                document.getElementById('sbotInput').value = text;
                sbotUpdateBtn();
                sbotSend();
            };
            window.sbotKey = function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sbotSend();
                }
            };
            window.sbotResize = function(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 96) + 'px';
            };
            window.sbotUpdateBtn = function() {
                document.getElementById('sbotSendBtn').disabled = !document.getElementById('sbotInput').value
            .trim();
            };
            window.sbotToast = function(msg, type) {
                var c = document.getElementById('sbotToasts');
                var t = document.createElement('div');
                t.className = 'sbot-toast sbot-' + type;
                var color = type === 'ok' ? '#1a56d6' : '#f43f5e';
                var icon = type === 'ok' ?
                    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="' + color +
                    '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' :
                    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="' + color +
                    '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
                t.innerHTML = icon + ' ' + msg;
                c.appendChild(t);
                setTimeout(function() {
                    if (t.parentNode) t.remove();
                }, 3000);
            };
        })
        ();
    </script>
@endonce
