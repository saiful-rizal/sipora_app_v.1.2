<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SIPORA AI - Smart Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            /* WARNA ASLI - TIDAK DIUBAH */
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

            /* Enhanced Shadows */
            --sbot-shadow-fab:
                0 10px 40px rgba(26, 86, 214, 0.4),
                0 4px 12px rgba(15, 23, 42, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            --sbot-shadow-win:
                0 32px 80px rgba(15, 23, 42, 0.18),
                0 8px 24px rgba(15, 23, 42, 0.08);
            --sbot-shadow-bubble:
                0 2px 12px rgba(15, 23, 42, 0.08),
                0 1px 4px rgba(15, 23, 42, 0.04);
            --sbot-shadow-input:
                0 4px 20px rgba(15, 23, 42, 0.08),
                0 1px 4px rgba(15, 23, 42, 0.04),
                inset 0 1px 3px rgba(255, 255, 255, 0.5);

            /* Border Radius */
            --sbot-r-sm: 10px;
            --sbot-r-md: 14px;
            --sbot-r-lg: 18px;
            --sbot-r-xl: 22px;
            --sbot-r-xxl: 26px;

            /* Transitions */
            --sbot-ease: cubic-bezier(0.22, 0.68, 0, 1.2);
            --sbot-ease-out: cubic-bezier(0.22, 1, 0.36, 1);
            --sbot-tr: all 0.3s var(--sbot-ease);
            --sbot-tr-smooth: all 0.4s var(--sbot-ease-out);

            /* Fonts */
            --sbot-font-d: 'Sora', sans-serif;
            --sbot-font-b: 'Inter', sans-serif;
        }

        /* ════════════════════════════════════════════
           FAB BUTTON - Hidden when Fullscreen
        ════════════════════════════════════════════ */
        #sbotFab {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 60px;
            height: 60px;
            border-radius: 18px;
            border: none;
            cursor: pointer;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--sbot-navy) 0%, var(--sbot-blue) 100%);
            box-shadow: var(--sbot-shadow-fab);
            transition:
                transform 0.35s var(--sbot-ease-out),
                opacity 0.3s ease,
                visibility 0.3s ease,
                scale 0.35s var(--sbot-ease-out);
            outline: none;
        }

        body.sbot-fullscreen-active #sbotFab {
            opacity: 0;
            visibility: hidden;
            transform: scale(0.5);
            pointer-events: none;
        }

        #sbotFab:hover {
            transform: translateY(-4px) scale(1.06);
            box-shadow:
                0 16px 48px rgba(26, 86, 214, 0.48),
                0 6px 16px rgba(15, 23, 42, 0.18);
        }

        #sbotFab:active {
            transform: scale(0.95);
        }

        .sbot-fab-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.4s var(--sbot-ease);
            color: #fff;
        }

        #sbotFab.sbot-open .sbot-fab-icon {
            transform: rotate(90deg);
        }

        #sbotFab::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 24px;
            border: 2px solid rgba(26, 86, 214, 0.45);
            opacity: 0;
            animation: sbot-pulse 3s ease-out infinite;
        }

        #sbotFab.sbot-open::before {
            animation: none;
            opacity: 0;
        }

        @keyframes sbot-pulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.65); opacity: 0; }
        }

        .sbot-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--sbot-rose);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fff;
            font-family: var(--sbot-font-d);
            transition: var(--sbot-tr-smooth);
            box-shadow: 0 2px 8px rgba(244, 63, 94, 0.35);
        }

        #sbotFab.sbot-open .sbot-badge {
            transform: scale(0) rotate(-45deg);
            opacity: 0;
        }

        /* ════════════════════════════════════════════
           CHAT WINDOW - TRUE FULLSCREEN NO GAPS
        ════════════════════════════════════════════ */
        #sbotWindow {
            position: fixed;
            bottom: 108px;
            right: 28px;
            width: 400px;
            height: 620px;
            max-height: calc(100vh - 140px);
            max-width: calc(100vw - 24px);
            background: var(--sbot-bg-window);
            border: 1px solid var(--sbot-border);
            border-radius: var(--sbot-r-xxl);
            z-index: 999998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--sbot-shadow-win);
            transform: scale(0.35) translateY(35px);
            opacity: 0;
            pointer-events: none;
            transform-origin: bottom right;
            transition:
                transform 0.45s var(--sbot-ease-out),
                opacity 0.3s ease,
                width 0.4s var(--sbot-ease-out),
                height 0.4s var(--sbot-ease-out),
                bottom 0.4s var(--sbot-ease-out),
                right 0.4s var(--sbot-ease-out),
                border-radius 0.4s var(--sbot-ease-out),
                border 0.4s ease;
        }

        #sbotWindow.sbot-open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: all;
        }

        #sbotWindow.sbot-fullscreen {
            bottom: 0 !important;
            right: 0 !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            max-width: none !important;
            max-height: none !important;
            border-radius: 0 !important;
            border: none !important;
            transform-origin: center center;
        }

        /* ════════════════════════════════════════════
           HEADER
        ════════════════════════════════════════════ */
        .sbot-header {
            padding: 16px 20px;
            background: linear-gradient(130deg, var(--sbot-navy) 0%, var(--sbot-blue) 100%);
            display: flex;
            align-items: center;
            gap: 13px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            min-height: 76px;
        }

        .sbot-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.10) 1px, transparent 1px);
            background-size: 18px 18px;
            pointer-events: none;
        }

        .sbot-header-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--sbot-r-md);
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 19px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(4px);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sbot-online {
            position: absolute;
            bottom: -3px;
            right: -3px;
            width: 12px;
            height: 12px;
            background: var(--sbot-green);
            border: 2.5px solid var(--sbot-navy);
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.45);
            animation: sbot-pulse-online 2s ease-in-out infinite;
        }

        @keyframes sbot-pulse-online {
            0%, 100% { box-shadow: 0 2px 6px rgba(34, 197, 94, 0.45); }
            50% { box-shadow: 0 2px 12px rgba(34, 197, 94, 0.65); }
        }

        .sbot-header-info {
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .sbot-header-info h3 {
            font-family: var(--sbot-font-d);
            font-size: 14.5px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .sbot-model-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 3px 8px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .sbot-header-info p {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.70);
            margin: 4px 0 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sbot-status-dot {
            width: 7px;
            height: 7px;
            background: var(--sbot-green);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 6px rgba(34, 197, 94, 0.55);
        }

        .sbot-header-actions {
            margin-left: auto;
            display: flex;
            gap: 4px;
            position: relative;
            z-index: 1;
        }

        .sbot-hdr-btn {
            width: 36px;
            height: 36px;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.8);
            border-radius: var(--sbot-r-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: var(--sbot-tr);
        }

        .sbot-hdr-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.30);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* ════════════════════════════════════════════
           MESSAGES AREA
        ════════════════════════════════════════════ */
        .sbot-messages {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 13px;
            background: #fafbfd;
            scroll-behavior: smooth;
        }

        .sbot-messages::-webkit-scrollbar {
            width: 5px;
        }

        .sbot-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .sbot-messages::-webkit-scrollbar-thumb {
            background: var(--sbot-border);
            border-radius: 10px;
        }

        .sbot-messages::-webkit-scrollbar-thumb:hover {
            background: var(--sbot-t3);
        }

        .sbot-welcome {
            text-align: center;
            padding: 14px 8px 8px;
            animation: sbot-fadeInUp 0.5s ease-out;
        }

        @keyframes sbot-fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sbot-welcome-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 16px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            box-shadow:
                0 10px 28px rgba(26, 86, 214, 0.28),
                0 4px 10px rgba(15, 23, 42, 0.1);
            position: relative;
        }

        .sbot-welcome-icon::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 60%);
            pointer-events: none;
        }

        .sbot-welcome h4 {
            font-family: var(--sbot-font-d);
            font-size: 16px;
            font-weight: 700;
            color: var(--sbot-t1);
            margin: 0 0 7px;
        }

        .sbot-welcome p {
            font-size: 12.5px;
            color: var(--sbot-t2);
            margin: 0 auto;
            max-width: 320px;
            line-height: 1.62;
        }

        .sbot-chips {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 16px;
        }

        .sbot-chip {
            padding: 13px 16px;
            background: #fff;
            border: 1.5px solid var(--sbot-border);
            border-radius: var(--sbot-r-md);
            cursor: pointer;
            text-align: left;
            font-size: 12.8px;
            color: var(--sbot-t2);
            display: flex;
            align-items: center;
            gap: 11px;
            transition: var(--sbot-tr-smooth);
            font-family: var(--sbot-font-d);
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }

        .sbot-chip::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--sbot-blue) 0%, var(--sbot-indigo) 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sbot-chip:hover {
            border-color: var(--sbot-blue);
            color: var(--sbot-blue);
            background: var(--sbot-blue-pale);
            transform: translateX(4px);
            box-shadow:
                0 4px 16px rgba(26, 86, 214, 0.12),
                0 2px 6px rgba(15, 23, 42, 0.04);
        }

        .sbot-chip:hover::before {
            transform: scaleY(1);
        }

        .sbot-chip-icon {
            width: 33px;
            height: 33px;
            border-radius: var(--sbot-r-sm);
            background: var(--sbot-blue-pale);
            color: var(--sbot-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: var(--sbot-tr);
        }

        .sbot-chip:hover .sbot-chip-icon {
            background: linear-gradient(135deg, var(--sbot-blue), var(--sbot-indigo));
            color: #fff;
            transform: scale(1.06);
        }

        .sbot-chip:nth-child(1) { animation: sbot-slideUp 0.4s 0.08s ease both; }
        .sbot-chip:nth-child(2) { animation: sbot-slideUp 0.4s 0.16s ease both; }
        .sbot-chip:nth-child(3) { animation: sbot-slideUp 0.4s 0.24s ease both; }
        .sbot-chip:nth-child(4) { animation: sbot-slideUp 0.4s 0.32s ease both; }

        @keyframes sbot-slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Message Bubbles */
        .sbot-msg {
            display: flex;
            gap: 10px;
            animation: sbot-msgIn 0.35s var(--sbot-ease) both;
            max-width: 100%;
        }

        @keyframes sbot-msgIn {
            from { opacity: 0; transform: translateY(9px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .sbot-msg.sbot-user {
            flex-direction: row-reverse;
        }

        .sbot-msg-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            margin-top: 3px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.1);
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
            max-width: 85%;
            padding: 13px 17px;
            font-size: 13px;
            line-height: 1.7;
            word-wrap: break-word;
            font-family: var(--sbot-font-b);
        }

        .sbot-msg.sbot-ai .sbot-bubble {
            background: #fff;
            border: 1.5px solid var(--sbot-border);
            border-radius: 6px var(--sbot-r-lg) var(--sbot-r-lg) var(--sbot-r-lg);
            color: var(--sbot-t1);
            box-shadow: var(--sbot-shadow-bubble);
        }

        .sbot-msg.sbot-user .sbot-bubble {
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            border-radius: var(--sbot-r-lg) 6px var(--sbot-r-lg) var(--sbot-r-lg);
            color: #fff;
            box-shadow:
                0 5px 18px rgba(26, 86, 214, 0.26),
                0 2px 6px rgba(15, 23, 42, 0.08);
        }

        /* FORMAT TEKS AI - Enhanced Typography */
        .sbot-bubble strong {
            font-weight: 600;
            color: var(--sbot-blue);
        }

        .sbot-msg.sbot-user .sbot-bubble strong {
            color: #bfdbfe;
        }

        /* Lists Styling - Bullet & Numbered */
        .sbot-bubble ul,
        .sbot-bubble ol {
            margin: 10px 0;
            padding-left: 0;
        }

        .sbot-bubble ul {
            list-style: none;
        }

        .sbot-bubble ul li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .sbot-bubble ul li::before {
            content: '●';
            position: absolute;
            left: 0;
            color: var(--sbot-blue-light);
            font-size: 10px;
            top: 5px;
        }

        .sbot-bubble ol {
            counter-reset: item;
            list-style: none;
        }

        .sbot-bubble ol li {
            counter-increment: item;
            position: relative;
            padding-left: 28px;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .sbot-bubble ol li::before {
            content: counter(item) '.';
            position: absolute;
            left: 0;
            font-weight: 600;
            color: var(--sbot-blue);
            font-size: 13px;
        }

        .sbot-bubble li:last-child {
            margin-bottom: 0;
        }

        .sbot-bubble p {
            margin: 0 0 10px;
            line-height: 1.7;
        }

        .sbot-bubble p:last-child {
            margin-bottom: 0;
        }

        .sbot-bubble pre {
            background: var(--sbot-bg-code);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: var(--sbot-r-sm);
            padding: 14px;
            margin: 12px 0;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            line-height: 1.6;
            color: #e2e8f0;
        }

        .sbot-bubble p code {
            background: var(--sbot-bg-code);
            color: #93c5fd;
            padding: 3px 7px;
            border-radius: 5px;
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
        }

        .sbot-msg-acts {
            display: flex;
            gap: 2px;
            margin-top: 6px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sbot-msg:hover .sbot-msg-acts {
            opacity: 1;
        }

        .sbot-msg.sbot-user .sbot-msg-acts {
            justify-content: flex-end;
        }

        .sbot-msg-act {
            padding: 4px 8px;
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

        .sbot-typing {
            display: flex;
            gap: 10px;
            animation: sbot-msgIn 0.28s ease both;
        }

        .sbot-typing-dots {
            padding: 13px 17px;
            background: #fff;
            border: 1.5px solid var(--sbot-border);
            border-radius: 6px var(--sbot-r-lg) var(--sbot-r-lg) var(--sbot-r-lg);
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: var(--sbot-shadow-bubble);
        }

        .sbot-typing-dots span {
            width: 7px;
            height: 7px;
            background: var(--sbot-blue-light);
            border-radius: 50%;
            animation: sbot-bounce 1.35s infinite;
        }

        .sbot-typing-dots span:nth-child(2) { animation-delay: 0.15s; }
        .sbot-typing-dots span:nth-child(3) { animation-delay: 0.3s; }

        @keyframes sbot-bounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.35; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        .sbot-cursor::after {
            content: '▍';
            color: var(--sbot-blue);
            animation: sbot-blink 0.68s infinite;
        }

        @keyframes sbot-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* ════════════════════════════════════════════
           INPUT AREA
        ════════════════════════════════════════════ */
        .sbot-input-area {
            padding: 14px 20px 16px;
            border-top: 1px solid var(--sbot-border);
            background: var(--sbot-bg-header);
            flex-shrink: 0;
        }

        .sbot-input-box {
            display: flex;
            align-items: flex-end;
            background: #fff;
            border: 2px solid var(--sbot-border);
            border-radius: var(--sbot-r-xl);
            padding: 8px 8px 8px 16px;
            transition: var(--sbot-tr-smooth);
            box-shadow: var(--sbot-shadow-input);
        }

        .sbot-input-box:focus-within {
            border-color: var(--sbot-blue-light);
            box-shadow:
                0 0 0 3px rgba(26, 86, 214, 0.10),
                0 4px 20px rgba(15, 23, 42, 0.06),
                var(--sbot-shadow-input);
        }

        .sbot-input-box textarea {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--sbot-t1);
            font-family: var(--sbot-font-b);
            font-size: 14px;
            resize: none;
            max-height: 120px;
            padding: 8px 0;
            line-height: 1.52;
        }

        .sbot-input-box textarea::placeholder {
            color: var(--sbot-t3);
        }

        .sbot-input-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 8px;
        }

        .sbot-voice-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid var(--sbot-border);
            background: linear-gradient(135deg, #fff 0%, #fafafa 100%);
            color: var(--sbot-t2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            transition: var(--sbot-tr-smooth);
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
        }

        .sbot-voice-btn:hover {
            border-color: var(--sbot-rose);
            color: var(--sbot-rose);
            background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%);
            transform: scale(1.07);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.15);
        }

        .sbot-voice-btn.sbot-recording {
            background: linear-gradient(135deg, var(--sbot-rose) 0%, #ec4899 100%);
            border-color: var(--sbot-rose);
            color: #fff;
            animation: sbot-recordingPulse 1.5s ease-in-out infinite;
        }

        @keyframes sbot-recordingPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.5); }
            50% { box-shadow: 0 0 0 10px rgba(244, 63, 94, 0); }
        }

        .sbot-send-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--sbot-r-md);
            border: none;
            background: linear-gradient(135deg, var(--sbot-navy), var(--sbot-blue));
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            transition: var(--sbot-tr-smooth);
            box-shadow:
                0 4px 14px rgba(26, 86, 214, 0.28),
                0 2px 6px rgba(15, 23, 42, 0.08);
            position: relative;
            overflow: hidden;
        }

        .sbot-send-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .sbot-send-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .sbot-send-btn:hover:not(:disabled) {
            transform: scale(1.08) translateY(-1px);
            box-shadow:
                0 7px 20px rgba(26, 86, 214, 0.36),
                0 3px 9px rgba(15, 23, 42, 0.1);
        }

        .sbot-send-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .sbot-hint {
            text-align: center;
            font-size: 10.5px;
            color: var(--sbot-t3);
            margin-top: 8px;
            font-family: var(--sbot-font-b);
        }

        .sbot-hint kbd {
            background: #f1f5f9;
            border: 1px solid var(--sbot-border);
            border-radius: 4px;
            padding: 2px 5px;
            font-family: inherit;
            font-size: 9.5px;
        }

        /* TOAST NOTIFICATIONS */
        #sbotToasts {
            position: fixed;
            bottom: 110px;
            right: 28px;
            z-index: 1000000;
            display: flex;
            flex-direction: column;
            gap: 7px;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        body.sbot-fullscreen-active #sbotToasts {
            bottom: 20px;
            right: 20px;
            z-index: 999999;
        }

        .sbot-toast {
            padding: 10px 16px;
            background: #fff;
            border: 1px solid var(--sbot-border);
            border-radius: var(--sbot-r-md);
            color: var(--sbot-t1);
            font-size: 12px;
            font-family: var(--sbot-font-d);
            display: flex;
            align-items: center;
            gap: 9px;
            box-shadow:
                0 10px 26px rgba(15, 23, 42, 0.12),
                0 2px 6px rgba(15, 23, 42, 0.06);
            animation: sbot-toastIn 0.32s ease, sbot-toastOut 0.32s ease 2.8s forwards;
            pointer-events: auto;
            font-weight: 500;
        }

        .sbot-toast.sbot-ok { border-left: 3.5px solid var(--sbot-green); }
        .sbot-toast.sbot-err { border-left: 3.5px solid var(--sbot-rose); }

        @keyframes sbot-toastIn {
            from { opacity: 0; transform: translateX(28px) scale(0.95); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }

        @keyframes sbot-toastOut {
            to { opacity: 0; transform: translateX(28px) scale(0.95); }
        }

        /* RESPONSIVE DESIGN */
        @media(max-width: 480px) {
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

            #sbotWindow.sbot-fullscreen { border-radius: 0 !important; }

            #sbotFab {
                bottom: 20px;
                right: 20px;
                width: 54px;
                height: 54px;
                border-radius: 16px;
            }

            #sbotWindow.sbot-open ~ #sbotFab,
            body.sbot-fullscreen-active #sbotFab {
                opacity: 0;
                visibility: hidden;
                transform: scale(0.5);
                pointer-events: none;
            }

            .sbot-hint { display: none; }
            #sbotToasts { bottom: 90px; right: 16px; }

            .sbot-input-area {
                padding: 12px 16px 14px;
                padding-bottom: calc(14px + env(safe-area-inset-bottom, 0px));
            }

            .sbot-header {
                padding: 14px 16px;
                padding-top: calc(14px + env(safe-area-inset-top, 0px));
            }
        }

        @media(min-width: 481px) and (max-width: 768px) {
            #sbotWindow {
                width: calc(100vw - 32px);
                right: 16px;
                bottom: 96px;
            }

            #sbotFab { right: 16px; bottom: 20px; }
            body.sbot-fullscreen-active #sbotFab {
                opacity: 0;
                visibility: hidden;
                transform: scale(0.5);
                pointer-events: none;
            }
        }

        @media(min-width: 769px) {
            body.sbot-fullscreen-active #sbotFab {
                opacity: 0;
                visibility: hidden;
                transform: scale(0.5);
                pointer-events: none;
            }
        }

        @media(max-height: 500px) and (orientation: landscape) {
            .sbot-welcome-icon { width: 46px; height: 46px; margin-bottom: 10px; }
            .sbot-welcome h4 { font-size: 14px; margin-bottom: 4px; }
            .sbot-welcome p { font-size: 11px; }
            .sbot-chips { margin-top: 10px; }
            .sbot-chip { padding: 10px 13px; }
            .sbot-header { padding: 10px 16px; }
            .sbot-input-area { padding: 10px 16px 12px; }
        }
    </style>
</head>
<body>

<!-- FAB Button -->
<button id="sbotFab" onclick="sbotToggle()" aria-label="Buka SIPORA AI">
    <span class="sbot-fab-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
            <path d="M5 5l1.5 1.5M17.5 5L19 6.5M5 19l1.5-1.5M17.5 19l1.5-1.5" opacity=".5" />
        </svg>
    </span>
    <span class="sbot-badge" id="sbotBadge">1</span>
</button>

<!-- Chat Window -->
<div id="sbotWindow">

    <!-- Header -->
    <div class="sbot-header">
        <div class="sbot-header-avatar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
            </svg>
            <span class="sbot-online"></span>
        </div>
        <div class="sbot-header-info">
            <h3>SIPORA AI <span class="sbot-model-badge">Pro</span></h3>
            <p><span class="sbot-status-dot"></span> Online — siap membantu</p>
        </div>
        <div class="sbot-header-actions">
            <button class="sbot-hdr-btn" onclick="sbotToggleFullscreen()" title="Full Screen">
                <svg id="sbotFullscreenIcon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 3 21 3 21 9"/>
                    <polyline points="9 21 3 21 3 15"/>
                    <line x1="21" y1="3" x2="14" y2="10"/>
                    <line x1="3" y1="21" x2="10" y2="14"/>
                </svg>
            </button>
            <button class="sbot-hdr-btn" onclick="sbotClear()" title="Chat baru">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10" />
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                </svg>
            </button>
            <button class="sbot-hdr-btn" onclick="sbotToggle()" title="Tutup">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="sbot-messages" id="sbotMessages">
        <div class="sbot-welcome" id="sbotWelcome">
            <div class="sbot-welcome-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
                </svg>
            </div>
            <h4>SIPORA AI Assistant</h4>
            <p>Asisten cerdas untuk repository akademik Anda. Tanya tentang dokumen, panduan, atau analisis data.</p>
            <div class="sbot-chips">
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </div>
                    <span>Cari dokumen tentang machine learning</span>
                </button>
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20V10" />
                            <path d="M18 20V4" />
                            <path d="M6 20v-4" />
                        </svg>
                    </div>
                    <span>Rangkum statistik dokumen saya</span>
                </button>
                <button class="sbot-chip" onclick="sbotUseChip(this)">
                    <div class="sbot-chip-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <span>Info standar Turnitin SIPORA</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Input Area with Voice Note Feature -->
    <div class="sbot-input-area">
        <div class="sbot-input-box">
            <textarea id="sbotInput" rows="1" placeholder="Tanya tentang SIPORA..." onkeydown="sbotKey(event)" oninput="sbotResize(this);sbotUpdateBtn()"></textarea>
            <div class="sbot-input-actions">
                <button class="sbot-voice-btn" id="sbotVoiceBtn" onclick="sbotToggleVoice()" title="Voice Note">
                    <svg id="sbotMicIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                        <line x1="12" y1="19" x2="12" y2="23"/>
                        <line x1="8" y1="23" x2="16" y2="23"/>
                    </svg>
                </button>
                <button class="sbot-send-btn" id="sbotSendBtn" onclick="sbotSend()" disabled>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="sbot-hint"><kbd>Enter</kbd> kirim · <kbd>Shift+Enter</kbd> baris baru · 🎤 Voice note tersedia</div>
    </div>
</div>

<!-- Toast Notifications Container -->
<div id="sbotToasts"></div>

<script>
(function() {
    // State Management
    let isOpen = false;
    let isTyping = false;
    let isFullscreen = false;
    let isRecording = false;
    let messages = [];
    let recognition = null;

    // Knowledge Base - DENGAN FORMAT STRUKTUR (Points + Paragraf)
    const kb = {
        cari: `
            <p><strong>📋 Hasil Pencarian Dokumen</strong></p>
            <p>Saya menemukan <strong>3 dokumen</strong> yang relevan dengan pencarian Anda:</p>
            <ol>
                <li><strong>Implementasi Machine Learning untuk Klasifikasi Gambar</strong><br><em>Prodi: Teknik Informatika · Tahun: 2024</em><br>Turnitin: <strong style="color: #22c55e">18%</strong> · Status: Disetujui ✓</li>
                <li><strong>Analisis Sentimen Media Sosial Menggunakan NLP</strong><br><em>Prodi: Sistem Informasi · Tahun: 2024</em><br>Turnitin: <strong style="color: #f59e0b">15%</strong> · Status: Review ⏳</li>
                <li><strong>Deep Learning untuk Deteksi Penyakit Daun</strong><br><em>Prodi: Teknik Informatika · Tahun: 2023</em><br>Turnitin: <strong style="color: #ef4444">21%</strong> · Status: Revisi ⚠️</li>
            </ol>
            <p>💡 Gunakan filter di dashboard untuk mempersempit hasil pencarian.</p>
        `,
        upload: `
            <p><strong>📤 Cara Upload Dokumen di SIPORA</strong></p>
            <p>Ikuti langkah-langkah berikut untuk mengunggah dokumen Anda:</p>
            <ol>
                <li>Klik menu <strong>"Unggah Dokumen"</strong> di navbar utama</li>
                <li>Isi form dengan data berikut:
                    <ul>
                        <li>Judul tugas akhir</li>
                        <li>Abstrak (min. <strong>100 karakter</strong>)</li>
                        <li>Jurusan & Program Studi</li>
                        <li>Tema penelitian</li>
                        <li>Tahun publikasi</li>
                    </ul>
                </li>
                <li>Upload file dalam format <strong>PDF</strong> (maks. <strong>25 MB</strong>)</li>
                <li>Klik tombol <strong>"Simpan"</strong> untuk menyelesaikan</li>
            </ol>
            <p><strong>⚠️ Catatan Penting:</strong></p>
            <ul>
                <li>Format wajib <strong>PDF</strong> — tidak boleh terproteksi password</li>
                <li>Cek Turnitin akan berjalan <strong>otomatis</strong> setelah upload</li>
                <li>Proses membutuhkan waktu <strong>5–15 menit</strong></li>
            </ul>
        `,
        rangkum: `
            <p><strong>📊 Statistik Dokumen Anda</strong></p>
            <p>Berikut ringkasan repository akademik Anda:</p>
            <ul>
                <li><strong>Total dokumen:</strong> 3 file</li>
                <li><strong>Rata-rata Turnitin:</strong> 18%</li>
                <li><strong>Upload terbaru:</strong> 3 hari lalu</li>
            </ul>
            <p><strong>Status Dokumen:</strong></p>
            <ol>
                <li>✅ <strong>Disetujui:</strong> 1 dokumen</li>
                <li>⏳ <strong>Dalam Review:</strong> 1 dokumen</li>
                <li>⚠️ <strong>Perlu Revisi:</strong> 1 dokumen</li>
            </ol>
            <p><strong>💡 Rekomendasi:</strong></p>
            <p>Terdapat <strong style="color: #ef4444">1 dokumen</strong> yang perlu direvisi karena persentase Turnitin melebihi <strong>20%</strong>. Segera perbarui dokumen tersebut untuk kelulusan tepat waktu.</p>
        `,
        turnitin: `
            <p><strong>✅ Standar Turnitin SIPORA</strong></p>
            <p>Berikut ketentuan similarity index yang berlaku di sistem SIPORA:</p>
            <ol>
                <li>🟢 <strong>&lt; 20%</strong> — Aman, diproses langsung tanpa review</li>
                <li>🟡 <strong>20% – 30%</strong> — Perlu review oleh dosen pembimbing</li>
                <li>🔴 <strong>&gt; 30%</strong> — Wajib revisi sebelum bisa diproses</li>
            </ol>
            <p><strong>⏱️ Estimasi Proses:</strong></p>
            <ul>
                <li>Hasil Turnitin keluar dalam <strong>5–15 menit</strong> setelah upload</li>
                <li>Notifikasi akan dikirim via email saat selesai</li>
                <li>Dokumen dengan similarity tinggi akan ditandai otomatis</li>
            </ul>
            <p>Pastikan dokumen Anda original dan sudah direferensi dengan benar untuk menghindari plagiasi tidak disengaja.</p>
        `,
        def: `
            <p><strong>👋 Halo! Saya SIPORA AI</strong></p>
            <p>Saya adalah asisten cerdas khusus untuk membantu Anda mengelola <strong>repository akademik</strong> di SIPORA.</p>
            <p><strong>Yang bisa saya bantu:</strong></p>
            <ul>
                <li>🔍 <strong>Pencarian dokumen</strong> berdasarkan topik, judul, atau kata kunci</li>
                <li>📈 <strong>Statistik & rangkuman</strong> dokumen yang Anda miliki</li>
                <li>📤 <strong>Panduan upload</strong> dan persyaratan dokumen</li>
                <li>ℹ️ <strong>Info standar Turnitin</strong> dan kebijakan plagiasi</li>
                <li>❓ <strong>Cara kerja fitur-fitur</strong> di platform SIPORA</li>
            </ul>
            <p>Silakan ajukan pertanyaan Anda dengan spesifik, misalnya:</p>
            <ul>
                <li>"Cari dokumen tentang deep learning"</li>
                <li>"Bagaimana cara upload tugas akhir?"</li>
                <li>"Berapa standar Turnitin yang diterima?"</li>
            </ul>
        `
    };

    function getReply(m) {
        const l = m.toLowerCase();
        if (/cari|dokumen|temukan|machine learning/.test(l)) return kb.cari;
        if (/upload|unggah|cara/.test(l)) return kb.upload;
        if (/rangkum|statistik|data/.test(l)) return kb.rangkum;
        if (/turnitin|plagiasi|similar|standar/.test(l)) return kb.turnitin;
        return kb.def;
    }

    window.sbotToggle = function() {
        isOpen = !isOpen;
        const win = document.getElementById('sbotWindow');
        const fab = document.getElementById('sbotFab');
        const icon = fab.querySelector('.sbot-fab-icon');
        const badge = document.getElementById('sbotBadge');

        win.classList.toggle('sbot-open', isOpen);
        fab.classList.toggle('sbot-open', isOpen);

        if (isOpen) {
            icon.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            if (badge) badge.style.display = 'none';
            setTimeout(() => document.getElementById('sbotInput').focus(), 420);
        } else {
            icon.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z"/><path d="M5 5l1.5 1.5M17.5 5L19 6.5M5 19l1.5-1.5M17.5 19l1.5-1.5" opacity=".5"/></svg>';
            if (isFullscreen) sbotToggleFullscreen();
        }
    };

    window.sbotToggleFullscreen = function() {
        const win = document.getElementById('sbotWindow');
        const fsIcon = document.getElementById('sbotFullscreenIcon');

        isFullscreen = !isFullscreen;
        win.classList.toggle('sbot-fullscreen', isFullscreen);
        document.body.classList.toggle('sbot-fullscreen-active', isFullscreen);

        if (isFullscreen) {
            fsIcon.innerHTML = `<polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/><line x1="14" y1="20" x2="21" y2="13"/><line x1="3" y1="3" x2="10" y2="10"/>`;
            sbotToast('Mode fullscreen aktif', 'ok');
        } else {
            fsIcon.innerHTML = `<polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>`;
            sbotToast('Mode normal', 'ok');
        }

        setTimeout(scrollDown, 400);
        window.dispatchEvent(new Event('resize'));
    };

    window.sbotToggleVoice = function() {
        const voiceBtn = document.getElementById('sbotVoiceBtn');
        const micIcon = document.getElementById('sbotMicIcon');

        if (!isRecording) {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = true;
                recognition.lang = 'id-ID';

                recognition.onstart = () => {
                    isRecording = true;
                    voiceBtn.classList.add('sbot-recording');
                    micIcon.innerHTML = `<rect x="6" y="6" width="12" height="12" rx="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/>`;
                    sbotToast('🎤 Mendengarkan...', 'ok');
                };

                recognition.onresult = (event) => {
                    const transcript = Array.from(event.results).map(result => result[0].transcript).join('');
                    document.getElementById('sbotInput').value = transcript;
                    sbotResize(document.getElementById('sbotInput'));
                    sbotUpdateBtn();
                };

                recognition.onerror = (event) => {
                    console.error('Speech recognition error:', event.error);
                    stopRecording();
                    sbotToast('❌ Error: ' + event.error, 'err');
                };

                recognition.onend = () => stopRecording();
                recognition.start();
            } else {
                sbotToast('❌ Browser tidak mendukung voice note', 'err');
            }
        } else {
            stopRecording();
            if (recognition) recognition.stop();
        }
    };

    function stopRecording() {
        isRecording = false;
        document.getElementById('sbotVoiceBtn').classList.remove('sbot-recording');
        document.getElementById('sbotMicIcon').innerHTML = `<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>`;
    }

    window.sbotSend = function() {
        const input = document.getElementById('sbotInput');
        const text = input.value.trim();
        if (!text || isTyping) return;

        const welcome = document.getElementById('sbotWelcome');
        if (welcome) welcome.style.display = 'none';

        addBubble('user', text);
        input.value = '';
        input.style.height = 'auto';
        sbotUpdateBtn();

        isTyping = true;
        const container = document.getElementById('sbotMessages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'sbot-typing';
        typingDiv.id = 'sbotTypingEl';
        typingDiv.innerHTML = avHTML('ai') + '<div class="sbot-typing-dots"><span></span><span></span><span></span></div>';
        container.appendChild(typingDiv);
        scrollDown();

        setTimeout(() => {
            const typingEl = document.getElementById('sbotTypingEl');
            if (typingEl) typingEl.remove();
            isTyping = false;
            addBubble('ai', getReply(text), true);
        }, 800 + Math.random() * 1100);
    };

    function avHTML(role) {
        const sparkle = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z"/></svg>';
        const user = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
        return '<div class="sbot-msg-av">' + (role === 'ai' ? sparkle : user) + '</div>';
    }

    function addBubble(role, text, shouldType) {
        const container = document.getElementById('sbotMessages');
        messages.push({ role, text });
        const idx = messages.length - 1;
        const div = document.createElement('div');
        div.className = `sbot-msg sbot-${role}`;

        const copyBtn = '<div class="sbot-msg-acts"><button class="sbot-msg-act" onclick="sbotCopyMsg(' + idx + ')" title="Salin"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Salin</button>';
        const regenBtn = '<button class="sbot-msg-act" onclick="sbotRegen(' + idx + ')" title="Coba lagi"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg> Ulang</button></div>';

        if (role === 'user') {
            div.innerHTML = avHTML('user') + '<div><div class="sbot-bubble">' + escHtml(text) + '</div>' + copyBtn + '</div>';
            container.appendChild(div);
            scrollDown();
        } else {
            div.innerHTML = avHTML('ai') + '<div><div class="sbot-bubble" id="sbotBubble' + idx + '"></div>' + copyBtn + regenBtn + '</div>';
            container.appendChild(div);
            scrollDown();
            if (shouldType) typeText(text, 'sbotBubble' + idx);
            else document.getElementById('sbotBubble' + idx).innerHTML = text;
        }
    }

    function typeText(html, elId) {
        const el = document.getElementById(elId);
        if (!el) return;
        isTyping = true;
        el.classList.add('sbot-cursor');
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const full = tmp.innerHTML;
        let i = 0;

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
        const div = document.createElement('div');
        div.textContent = t;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

    function scrollDown() {
        const el = document.getElementById('sbotMessages');
        if (el) setTimeout(() => { el.scrollTop = el.scrollHeight; }, 10);
    }

    window.sbotCopyMsg = function(idx) {
        const msg = messages[idx];
        if (!msg) return;
        const tmp = document.createElement('div');
        tmp.innerHTML = msg.text;
        navigator.clipboard.writeText(tmp.textContent).then(() => sbotToast('✅ Disalin!', 'ok'));
    };

    window.sbotRegen = function(idx) {
        if (isTyping) return;
        const msg = messages[idx];
        if (!msg || msg.role !== 'ai') return;
        const container = document.getElementById('sbotMessages');
        const aiMsgs = container.querySelectorAll('.sbot-msg.sbot-ai');
        if (aiMsgs.length) aiMsgs[aiMsgs.length - 1].remove();
        messages.splice(idx, 1);

        let lastUser = null;
        for (let i = messages.length - 1; i >= 0; i--) {
            if (messages[i].role === 'user') { lastUser = messages[i]; break; }
        }

        if (lastUser) {
            isTyping = true;
            const typingDiv = document.createElement('div');
            typingDiv.className = 'sbot-typing';
            typingDiv.id = 'sbotTypingEl';
            typingDiv.innerHTML = avHTML('ai') + '<div class="sbot-typing-dots"><span></span><span></span><span></span></div>';
            container.appendChild(typingDiv);
            scrollDown();
            setTimeout(() => {
                const typingEl = document.getElementById('sbotTypingEl');
                if (typingEl) typingEl.remove();
                isTyping = false;
                addBubble('ai', getReply(lastUser.text), true);
            }, 850 + Math.random() * 750);
        }
    };

    window.sbotClear = function() {
        messages = [];
        const container = document.getElementById('sbotMessages');
        container.innerHTML = '';
        container.innerHTML = `
            <div class="sbot-welcome" id="sbotWelcome">
                <div class="sbot-welcome-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5Z" />
                    </svg>
                </div>
                <h4>SIPORA AI Assistant</h4>
                <p>Asisten cerdas untuk repository akademik Anda.</p>
                <div class="sbot-chips">
                    <button class="sbot-chip" onclick="sbotUseChip(this)">
                        <div class="sbot-chip-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </div>
                        <span>Cari dokumen tentang machine learning</span>
                    </button>
                    <button class="sbot-chip" onclick="sbotUseChip(this)">
                        <div class="sbot-chip-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><line x1="16" y1="13" x2="8" y2="13" /><line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                        </div>
                        <span>Cara upload tugas akhir di SIPORA?</span>
                    </button>
                    <button class="sbot-chip" onclick="sbotUseChip(this)">
                        <div class="sbot-chip-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20V10" /><path d="M18 20V4" /><path d="M6 20v-4" />
                            </svg>
                        </div>
                        <span>Rangkum statistik dokumen saya</span>
                    </button>
                    <button class="sbot-chip" onclick="sbotUseChip(this)">
                        <div class="sbot-chip-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" /><line x1="12" y1="9" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                        </div>
                        <span>Info standar Turnitin SIPORA</span>
                    </button>
                </div>
            </div>`;
        sbotToast('🔄 Chat baru dimulai', 'ok');
    };

    window.sbotUseChip = function(el) {
        const text = el.querySelector('span').textContent;
        document.getElementById('sbotInput').value = text;
        sbotUpdateBtn();
        sbotSend();
    };

    window.sbotKey = function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sbotSend(); }
    };

    window.sbotResize = function(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    };

    window.sbotUpdateBtn = function() {
        document.getElementById('sbotSendBtn').disabled = !document.getElementById('sbotInput').value.trim();
    };

    window.sbotToast = function(msg, type) {
        const container = document.getElementById('sbotToasts');
        const toast = document.createElement('div');
        toast.className = `sbot-toast sbot-${type}`;
        const color = type === 'ok' ? '#22c55e' : '#f43f5e';
        const icon = type === 'ok'
            ? `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
            : `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
        toast.innerHTML = `${icon} ${msg}`;
        container.appendChild(toast);
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 3100);
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isFullscreen && isOpen) sbotToggleFullscreen();
    });

    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            scrollDown();
            if (isFullscreen) window.dispatchEvent(new Event('resize'));
        }, 200);
    });
})();
</script>

</body>
</html>
