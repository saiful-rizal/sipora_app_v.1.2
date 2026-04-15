@php
    $authUser = Auth::user();
    $sessionUser = session('auth_user', []);

    if ($authUser) {
        $userId = (int) ($authUser->id_user ?? 0);
        $username = $authUser->username ?? ($authUser->name ?? 'Guest');
        $email = $authUser->email ?? '';
    } else {
        $userId = (int) ($sessionUser['id_user'] ?? 0);
        $username = $sessionUser['username'] ?? 'Guest';
        $email = $sessionUser['email'] ?? '';
    }

    $profilePhoto = null;
    if ($userId > 0) {
        $profilePhoto = \Illuminate\Support\Facades\DB::table('user_profile')
            ->where('id_user', $userId)
            ->value('foto_profil');
    }
    $avatarUrl = $profilePhoto ? asset('uploads/profile/' . $profilePhoto) : null;

    $parts = preg_split('/\s+/', trim($username)) ?: [];
    $initials = '';
    foreach ($parts as $part) {
        if ($part !== '') {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }
        if (mb_strlen($initials) >= 2) {
            break;
        }
    }
    $initials = $initials !== '' ? $initials : 'G';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>SIPORA - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --navy: #0b1b4d;
            --blue: #1a56d6;
            --sky: #38bdf8;
            --indigo: #4a7dff;
            --teal: #2563eb;
            --rose: #3b82f6;
            --amber: #f59e0b;
            --green: #10b981;
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

        body {
            font-family: var(--font-b);
            background: var(--page);
            color: var(--t1);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(ellipse 60% 40% at 100% 0%, rgba(26, 86, 214, .10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 35% at 0% 100%, rgba(30, 64, 175, .08) 0%, transparent 60%),
                var(--page);
        }

        /* ═══ NAVBAR ═══ */
        nav {
            background: #fff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(15, 23, 42, .05);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--navy), var(--blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            font-family: var(--font-d);
            box-shadow: 0 4px 12px rgba(26, 86, 214, .25);
        }

        .brand-text strong {
            display: block;
            font-family: var(--font-d);
            font-size: 15px;
            font-weight: 600;
            color: var(--t1);
        }

        .brand-text span {
            font-size: 10.5px;
            color: var(--t2);
            letter-spacing: .2px;
            font-family: var(--font-b);
        }

        .nav-links {
            display: flex;
            gap: 4px;
        }

        .nav-links a {
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            font-family: var(--font-b);
            color: var(--t2);
            transition: all .18s;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: var(--blue);
            background: rgba(26, 86, 214, .07);
        }

        .nav-links a.active {
            color: var(--blue);
            background: rgba(26, 86, 214, .10);
            font-weight: 500;
        }

        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 20px;
            transition: all .18s;
            flex-shrink: 0;
        }

        .mobile-nav-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 150;
            background: rgba(11, 27, 77, .5);
            backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s;
        }

        .mobile-nav-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: min(300px, 85vw);
            height: 100vh;
            background: #fff;
            z-index: 151;
            padding: 80px 24px 24px;
            overflow-y: auto;
            transition: right .35s cubic-bezier(.22, .68, 0, 1.1);
            box-shadow: -8px 0 32px rgba(11, 27, 77, .15);
        }

        .mobile-nav-overlay.open .mobile-nav-menu {
            right: 0;
        }

        .mobile-nav-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 18px;
            transition: all .18s;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .mobile-nav-links a {
            text-decoration: none;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 400;
            font-family: var(--font-b);
            color: var(--t2);
            transition: all .18s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-nav-links a:hover,
        .mobile-nav-links a.active {
            color: var(--blue);
            background: rgba(26, 86, 214, .08);
        }

        .mobile-nav-links a i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 17px;
            position: relative;
            transition: all .18s;
            flex-shrink: 0;
        }

        .notif-btn:hover {
            border-color: var(--blue);
            color: var(--blue);
        }

        .notif-dot {
            width: 8px;
            height: 8px;
            background: var(--rose);
            border-radius: 50%;
            position: absolute;
            top: 7px;
            right: 7px;
            border: 2px solid #fff;
        }

        .avatar-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 500;
            font-size: 14px;
            font-family: var(--font-d);
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(26, 86, 214, .3);
            border: 2.5px solid rgba(255, 255, 255, .7);
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* ═══ LAYOUT ═══ */
        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(16px, 4vw, 28px);
        }

        /* ═══ HERO ═══ */
        .hero {
            border-radius: var(--r-lg);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr auto;
            min-height: 130px;
            background: linear-gradient(135deg, #0b1b4d 0%, #1a3fa8 50%, #1a56d6 100%);
            box-shadow: 0 8px 28px rgba(26, 86, 214, .25), 0 2px 6px rgba(15, 23, 42, .08);
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 45px solid rgba(255, 255, 255, .06);
            top: -120px;
            right: -80px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 28px solid rgba(255, 255, 255, .06);
            bottom: -70px;
            left: 200px;
        }

        .hero-dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .10) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: .4;
        }

        .hero-left {
            padding: clamp(20px, 4vw, 32px);
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 18px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 500;
            font-family: var(--font-b);
            color: rgba(255, 255, 255, .9);
            letter-spacing: .6px;
            text-transform: uppercase;
            margin-bottom: 10px;
            width: fit-content;
        }

        .hero-eyebrow i {
            font-size: 10px;
        }

        .hero h2 {
            font-family: var(--font-d);
            font-size: clamp(18px, 4vw, 24px);
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
            position: relative;
            z-index: 2;
            line-height: 1.3;
        }

        .hero h2 span {
            background: linear-gradient(90deg, #93c5fd, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 400 !important;
        }

        .hero p {
            font-size: clamp(11px, 2.5vw, 13px);
            font-family: var(--font-b);
            color: rgba(255, 255, 255, .75);
            max-width: 340px;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .hero-chips {
            display: flex;
            gap: 6px;
            margin-top: 14px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .20);
            border-radius: 18px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 400;
            font-family: var(--font-b);
            color: rgba(255, 255, 255, .88);
            backdrop-filter: blur(4px);
        }

        .chip i {
            font-size: 11px;
        }

        .hero-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 32px 24px 0;
            position: relative;
            z-index: 2;
        }

        .hero-avatar-wrap {
            position: relative;
        }

        .hero-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .18);
            border: 2.5px solid rgba(255, 255, 255, .45);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-d);
            font-size: 24px;
            font-weight: 500;
            color: #fff;
            backdrop-filter: blur(8px);
            box-shadow: 0 6px 20px rgba(15, 23, 42, .2);
            overflow: hidden;
        }

        .hero-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-avatar-ring {
            position: absolute;
            inset: -7px;
            border-radius: 50%;
            border: 2px dashed rgba(255, 255, 255, .18);
            animation: spin 20s linear infinite;
        }

        .hero-avatar-dot {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--green);
            border: 2.5px solid rgba(15, 35, 90, 1);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ═══ STAT CARDS ═══ */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
            gap: clamp(12px, 2vw, 16px);
            margin-bottom: 20px;
        }

        .stat {
            position: relative;
            border-radius: var(--r-md);
            padding: clamp(14px, 3vw, 18px);
            overflow: visible;
            cursor: default;
            transition: all .25s ease;
            border: 1px solid var(--border);
            background: var(--card);
            min-height: unset;
            font-family: var(--font-b);
        }

        .stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(15, 23, 42, .10);
        }

        .stat-1,
        .stat-2,
        .stat-3 {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-left: 3px solid var(--blue);
        }

        .stat-1:hover,
        .stat-2:hover,
        .stat-3:hover {
            border-color: rgba(26, 86, 214, .30);
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        .stat-deco,
        .stat-dots {
            display: none !important;
        }

        .stat-inner {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .stat-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            position: relative;
            flex-shrink: 0;
            background: rgba(26, 86, 214, .10);
            color: var(--blue);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
            min-width: 0;
        }

        .stat-val {
            font-family: var(--font-d);
            font-size: clamp(20px, 4vw, 26px);
            font-weight: 400;
            letter-spacing: -.8px;
            line-height: 1.15;
            color: var(--navy);
        }

        .stat-label {
            font-family: var(--font-b);
            font-size: clamp(10.5px, 2.2vw, 12px);
            color: var(--t2);
            letter-spacing: .1px;
            font-weight: 400;
            line-height: 1.3;
            word-wrap: break-word;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            flex-wrap: wrap;
        }

        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
            font-family: var(--font-b);
            background: var(--card);
            border: 1px solid var(--border);
        }

        .trend-up {
            color: #15803d;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .trend-new {
            color: #6d28d9;
            background: #faf5ff;
            border-color: #ede9fe;
        }

        .trend-pct {
            color: #c2410c;
            background: #fffbeb;
            border-color: #fde68a;
        }

        .trend-sub {
            font-size: 10px;
            color: var(--t3);
            font-weight: 400;
            font-family: var(--font-b);
        }

        .stat-progress {
            width: 100%;
            max-width: 140px;
            height: 5px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            margin-top: 6px;
            position: relative;
            z-index: 1;
        }

        .stat-progress-fill {
            height: 100%;
            border-radius: 6px;
            transition: width .8s ease;
            background: var(--amber);
        }

        /* ════════════════════════════════════════
           SMART TRENDING SECTION - ELEGANT DESIGN
           ════════════════════════════════════════ */

        .trending-section {
            margin-bottom: 20px;
            position: relative;
        }

        .trending-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 4px 24px rgba(15, 23, 42, 0.06),
                0 1px 3px rgba(15, 23, 42, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .trending-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                var(--blue) 0%,
                var(--indigo) 25%,
                var(--sky) 50%,
                var(--teal) 75%,
                var(--blue) 100%);
            background-size: 200% 100%;
            animation: gradientShift 4s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Header Section */
        .trending-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .trending-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .trending-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(251, 191, 36, 0.15));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.3); }
            50% { box-shadow: 0 0 16px 4px rgba(245, 158, 11, 0.15); }
        }

        .trending-icon-wrapper i {
            font-size: 17px;
            color: var(--amber);
            font-weight: 600;
        }

        .trending-title-text h3 {
            font-family: var(--font-d);
            font-size: 15px;
            font-weight: 600;
            color: var(--t1);
            letter-spacing: -0.2px;
            line-height: 1.3;
        }

        .trending-subtitle {
            font-size: 11px;
            color: var(--t3);
            font-family: var(--font-b);
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .trending-subtitle::before {
            content: '●';
            font-size: 6px;
            color: var(--green);
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Reset Button */
        .trending-reset-btn {
            display: none;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 500;
            font-family: var(--font-b);
            background: linear-gradient(135deg, #fee2e2, #fef2f2);
            border: 1px solid #fecaca;
            color: #dc2626;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.08);
        }

        .trending-reset-btn.visible {
            display: inline-flex;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .trending-reset-btn:hover {
            background: linear-gradient(135deg, #fecaca, #fee2e2);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        .trending-reset-btn i {
            font-size: 12px;
        }

        /* Tags Container */
        .trending-tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        /* Individual Tag - ELEGANT DESIGN */
        .trending-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 22px;
            font-size: 12.5px;
            font-weight: 500;
            font-family: var(--font-b);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1.5px solid #e2e8f0;
            color: var(--t1);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            user-select: none;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.04),
                0 1px 2px rgba(15, 23, 42, 0.02);
        }

        .trending-tag::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(26, 86, 214, 0.05),
                rgba(74, 125, 255, 0.03));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .trending-tag:hover {
            transform: translateY(-2px) scale(1.02);
            border-color: rgba(26, 86, 214, 0.4);
            color: var(--blue);
            box-shadow:
                0 8px 20px rgba(26, 86, 214, 0.12),
                0 2px 6px rgba(26, 86, 214, 0.06);
        }

        .trending-tag:hover::before {
            opacity: 1;
        }

        .trending-tag.active-search {
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            border-color: transparent;
            color: #fff;
            box-shadow:
                0 6px 20px rgba(26, 86, 214, 0.3),
                0 2px 6px rgba(26, 86, 214, 0.2);
            transform: translateY(-1px);
        }

        .trending-tag.active-search::before {
            display: none;
        }

        /* Rank Badge - PREMIUM STYLE */
        .tag-rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            font-family: var(--font-d);
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: var(--t3);
            border: 1px solid #cbd5e1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .trending-tag:hover .tag-rank,
        .trending-tag.active-search .tag-rank {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.15));
            color: inherit;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Special styling for top 3 ranks */
        .trending-tag[data-rank="1"] .tag-rank {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
            border-color: #fbbf24;
            box-shadow: 0 2px 6px rgba(251, 191, 36, 0.2);
        }

        .trending-tag[data-rank="2"] .tag-rank {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            color: #6b7280;
            border-color: #9ca3af;
        }

        .trending-tag[data-rank="3"] .tag-rank {
            background: linear-gradient(135deg, #fed7aa, #fdba74);
            color: #c2410c;
            border-color: #fb923c;
        }

        .trending-tag[data-rank="1"]:hover,
        .trending-tag[data-rank="1"].active-search {
            border-color: rgba(217, 119, 6, 0.5);
        }

        .trending-tag[data-rank="1"].active-search .tag-rank {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Tag Text */
        .tag-text {
            position: relative;
            z-index: 1;
            letter-spacing: 0.1px;
        }

        /* Search Icon on Hover */
        .tag-icon {
            font-size: 13px;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .trending-tag:hover .tag-icon {
            opacity: 1;
            transform: translateX(0);
        }

        .trending-tag.active-search .tag-icon {
            opacity: 1;
            transform: translateX(0);
        }

        /* ═══ SECTION HEADER ═══ */
        .sec-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .sec-title {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .sec-title-bar {
            width: 3px;
            height: 18px;
            border-radius: 3px;
            background: linear-gradient(180deg, var(--blue), var(--indigo));
            flex-shrink: 0;
        }

        .sec-title h3 {
            font-family: var(--font-d);
            font-size: clamp(14px, 3vw, 16px);
            font-weight: 600;
            color: var(--t1);
            letter-spacing: -.2px;
        }

        .sec-badge {
            background: var(--blue);
            color: #fff;
            font-size: 10px;
            font-weight: 500;
            font-family: var(--font-b);
            padding: 2px 8px;
            border-radius: 12px;
        }

        .sec-tools {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            width: 100%;
        }

        .filters {
            display: flex;
            gap: 5px;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
            flex: 1;
            min-width: 0;
        }

        .filters::-webkit-scrollbar {
            height: 6px;
        }

        .filters::-webkit-scrollbar-track {
            background: transparent;
        }

        .filters::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        .filter-pill {
            padding: 5px 12px;
            border-radius: 18px;
            font-size: 11.5px;
            font-weight: 500;
            font-family: var(--font-b);
            border: 1px solid var(--border);
            background: #fff;
            color: var(--t2);
            cursor: pointer;
            transition: all .2s;
            user-select: none;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .filter-pill:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, .04);
        }

        .filter-pill.active {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .view-toggle {
            display: flex;
            gap: 3px;
            flex-shrink: 0;
        }

        .vbtn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 13px;
            transition: all .2s;
            font-family: var(--font-b);
        }

        .vbtn:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: rgba(26, 86, 214, .04);
        }

        .vbtn.active {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
            box-shadow: 0 2px 8px rgba(26, 86, 214, .25);
        }

        /* ═══ DOCUMENT GRID ═══ */
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(280px, 100%), 1fr));
            gap: clamp(12px, 2vw, 16px);
        }

        .doc-grid.list-mode {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .doc-grid.list-mode .doc-card {
            flex-direction: row;
            max-height: 130px;
        }

        .doc-grid.list-mode .thumb {
            width: 150px;
            min-width: 150px;
            height: auto;
            min-height: 130px;
            border-radius: var(--r-md) 0 0 var(--r-md);
        }

        .doc-grid.list-mode .doc-body {
            padding: 12px 16px;
        }

        .doc-grid.list-mode .doc-desc {
            -webkit-line-clamp: 1;
        }

        .doc-grid.list-mode .doc-meta {
            display: none;
        }

        .doc-card {
            background: var(--card);
            border-radius: var(--r-md);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(15, 23, 42, .06), 0 1px 2px rgba(15, 23, 42, .04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .25s cubic-bezier(.22, 68, 0, 1.15), box-shadow .25s, border-color .25s;
            cursor: pointer;
        }

        .doc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, .10), 0 2px 6px rgba(15, 23, 42, .04);
            border-color: rgba(26, 86, 214, .25);
        }

        .doc-card.hidden-card {
            display: none;
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

        .thumb {
            height: 125px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .08) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .thumb-icon {
            font-size: 38px;
            color: rgba(255, 255, 255, .88);
            z-index: 1;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .18));
            transition: transform .25s;
        }

        .doc-card:hover .thumb-icon {
            transform: scale(1.08) translateY(-2px);
        }

        .thumb-ext {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, .20);
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 16px;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 500;
            font-family: var(--font-b);
            color: #fff;
            letter-spacing: .7px;
            text-transform: uppercase;
            z-index: 2;
            backdrop-filter: blur(4px);
        }

        .doc-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            gap: 8px;
        }

        .doc-title {
            font-family: var(--font-d);
            font-size: clamp(12px, 2.5vw, 14px);
            font-weight: 400;
            color: var(--t1);
            line-height: 1.35;
            flex: 1;
            padding-right: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            letter-spacing: -.1px;
        }

        .doc-badges {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .badge {
            font-size: 8.5px;
            padding: 2px 7px;
            border-radius: 16px;
            font-weight: 500;
            font-family: var(--font-b);
            text-transform: uppercase;
            letter-spacing: .4px;
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

        .doc-desc {
            font-size: clamp(10px, 2.2vw, 12px);
            font-family: var(--font-b);
            color: var(--t2);
            line-height: 1.5;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .doc-meta {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .doc-meta span {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 10.5px;
            font-family: var(--font-b);
            color: var(--t2);
        }

        .doc-meta i {
            font-size: 10.5px;
            color: #93c5fd;
        }

        .doc-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 8px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 8px;
        }

        .doc-user {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
            flex: 1;
        }

        .doc-user-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 8.5px;
            font-weight: 600;
            overflow: hidden;
            flex-shrink: 0;
        }

        .doc-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .doc-user-name {
            font-size: 10px;
            font-weight: 400;
            font-family: var(--font-b);
            color: var(--t1);
            line-height: 1.1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .doc-user-date {
            font-size: 9.5px;
            font-family: var(--font-b);
            color: var(--t3);
            margin-top: 1px;
        }

        .doc-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .act-btn {
            width: 27px;
            height: 27px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            border: none;
            transition: all .2s;
            text-decoration: none;
            font-family: var(--font-b);
        }

        .act-view {
            background: var(--blue);
            color: #fff;
        }

        .act-view:hover {
            background: #1240b5;
            transform: scale(1.06);
            box-shadow: 0 3px 8px rgba(26, 86, 214, .28);
        }

        .act-dl {
            background: transparent;
            color: var(--blue);
            border: 1px solid var(--border);
        }

        .act-dl:hover {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        /* ═══ EMPTY STATE ═══ */
        .empty-card {
            grid-column: 1 / -1;
            background: var(--card);
            border: 1.5px dashed var(--border);
            border-radius: var(--r-lg);
            padding: clamp(32px, 6vw, 48px) clamp(20px, 4vw, 28px);
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin: 0 auto 16px;
            box-shadow: 0 6px 20px rgba(26, 86, 214, .22);
        }

        .empty-card h4 {
            font-family: var(--font-d);
            font-size: clamp(14px, 3vw, 18px);
            font-weight: 500;
            color: var(--t1);
            margin-bottom: 6px;
        }

        .empty-card p {
            font-size: clamp(11px, 2.5vw, 13px);
            font-family: var(--font-b);
            color: var(--t3);
            margin-bottom: 20px;
            line-height: 1.65;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 10px;
            background: linear-gradient(130deg, var(--blue), var(--indigo));
            color: #fff;
            font-weight: 500;
            font-size: 12.5px;
            font-family: var(--font-b);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(26, 86, 214, .25);
            transition: all .22s;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 86, 214, .35);
        }

        /* ═══ MODAL ═══ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(11, 27, 77, .45);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(12px, 3vw, 24px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-dialog {
            background: var(--card);
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 720px;
            max-height: calc(100vh - clamp(24px, 5vw, 48px));
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 64px rgba(11, 27, 77, .25), 0 0 0 1px rgba(255, 255, 255, .1);
            transform: translateY(20px) scale(.97);
            transition: transform .3s cubic-bezier(.22, .68, 0, 1.1);
            overflow: hidden;
        }

        .modal-overlay.open .modal-dialog {
            transform: translateY(0) scale(1);
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: clamp(16px, 3vw, 20px) clamp(16px, 3vw, 24px);
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .modal-head h5 {
            font-family: var(--font-d);
            font-size: clamp(14px, 3vw, 16px);
            font-weight: 400;
            color: var(--t1);
            flex: 1;
            padding-right: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            font-size: 14px;
            transition: all .18s;
            flex-shrink: 0;
        }

        .modal-close:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fca5a5;
        }

        .modal-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            padding: 0 clamp(16px, 3vw, 24px);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .modal-tab {
            padding: 12px 18px;
            font-size: 13px;
            font-weight: 400;
            font-family: var(--font-b);
            color: var(--t2);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .18s;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .modal-tab:hover {
            color: var(--blue);
        }

        .modal-tab.active {
            color: var(--blue);
            border-bottom-color: var(--blue);
            font-weight: 500;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: clamp(16px, 3vw, 24px);
            -webkit-overflow-scrolling: touch;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .preview-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
            color: var(--t3);
            text-align: center;
        }

        .preview-placeholder i {
            font-size: 48px;
            margin-bottom: 16px;
            color: var(--border);
        }

        .preview-placeholder h4 {
            font-family: var(--font-d);
            font-size: 16px;
            font-weight: 400;
            color: var(--t2);
            margin-bottom: 6px;
        }

        .preview-placeholder p {
            font-size: 13px;
            font-family: var(--font-b);
        }

        .preview-placeholder iframe {
            width: 100%;
            height: clamp(300px, 50vh, 500px);
            border: none;
            border-radius: var(--r-md);
        }

        .info-grid {
            display: grid;
            gap: 14px;
        }

        .info-row {
            display: flex;
            gap: 12px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            width: clamp(100px, 25vw, 130px);
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 400;
            font-family: var(--font-b);
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: .5px;
            padding-top: 2px;
        }

        .info-value {
            font-size: clamp(12px, 2.5vw, 13.5px);
            font-family: var(--font-b);
            color: var(--t1);
            line-height: 1.5;
            flex: 1;
            min-width: 0;
            word-break: break-word;
        }

        .info-badge-inline {
            display: inline-block;
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500;
            font-family: var(--font-b);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ═══ FOOTER ═══ */
        .footer {
            text-align: center;
            margin-top: 12px;
            padding: 14px 20px;
            background: #fff;
            border-radius: var(--r-md);
            border: 1px solid var(--border);
            font-size: 12.5px;
            font-family: var(--font-b);
            color: var(--t3);
        }

        /* ═══ CHATBOT ═══ */
        .chatbot {
            position: fixed;
            bottom: clamp(16px, 3vw, 28px);
            right: clamp(16px, 3vw, 28px);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(26, 86, 214, .35);
            transition: all .2s;
            z-index: 50;
        }

        .chatbot:hover {
            transform: scale(1.1);
        }

        .chatbot-panel {
            position: fixed;
            bottom: 90px;
            right: clamp(16px, 3vw, 28px);
            width: min(360px, calc(100vw - 32px));
            max-height: min(480px, calc(100vh - 160px));
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--s-lg);
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transform: translateY(12px) scale(.96);
            transition: all .25s cubic-bezier(.22, .68, 0, 1.1);
        }

        .chatbot-panel.open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .chatbot-head {
            padding: 16px 18px;
            background: linear-gradient(130deg, var(--navy), var(--blue));
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot-head-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .chatbot-head-text strong {
            display: block;
            font-family: var(--font-d);
            font-size: 13px;
            font-weight: 600;
        }

        .chatbot-head-text span {
            font-size: 10.5px;
            font-family: var(--font-b);
            color: rgba(255, 255, 255, .7);
        }

        .chatbot-body {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            min-height: 200px;
            -webkit-overflow-scrolling: touch;
        }

        .chatbot-msg {
            margin-bottom: 12px;
            display: flex;
            gap: 8px;
        }

        .chatbot-msg.bot {
            justify-content: flex-start;
        }

        .chatbot-msg.user {
            justify-content: flex-end;
        }

        .chatbot-bubble {
            max-width: 85%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13px;
            font-family: var(--font-b);
            line-height: 1.5;
            word-wrap: break-word;
        }

        .chatbot-msg.bot .chatbot-bubble {
            background: var(--page);
            color: var(--t1);
            border-bottom-left-radius: 4px;
        }

        .chatbot-msg.user .chatbot-bubble {
            background: var(--blue);
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .chatbot-input-row {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 8px;
        }

        .chatbot-input {
            flex: 1;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-size: 13px;
            font-family: var(--font-b);
            outline: none;
            transition: border-color .18s;
            min-width: 0;
        }

        .chatbot-input:focus {
            border-color: var(--blue);
        }

        .chatbot-send {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--blue);
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: background .18s;
            flex-shrink: 0;
        }

        .chatbot-send:hover {
            background: #1240b5;
        }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) and (min-width: 769px) {
            .nav-inner {
                padding: 0 20px;
            }

            .nav-links {
                gap: 2px;
            }

            .nav-links a {
                padding: 7px 10px;
                font-size: 13px;
            }

            .stats {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }

            .doc-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }

            /* Trending Responsive */
            .trending-card {
                padding: 18px 20px;
            }

            .trending-tag {
                padding: 8px 14px;
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .mobile-nav-overlay {
                display: block;
            }

            .hero {
                grid-template-columns: 1fr;
                min-height: auto;
                text-align: left;
            }

            .hero-right {
                display: none;
            }

            .hero-left {
                padding: 24px 20px;
            }

            .hero p {
                max-width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            /* Trending Mobile */
            .trending-section {
                margin-bottom: 16px;
            }

            .trending-card {
                padding: 16px 18px;
                border-radius: var(--r-lg);
            }

            .trending-header {
                margin-bottom: 14px;
            }

            .trending-title-group {
                gap: 8px;
            }

            .trending-icon-wrapper {
                width: 32px;
                height: 32px;
            }

            .trending-title-text h3 {
                font-size: 14px;
            }

            .trending-subtitle {
                font-size: 10px;
            }

            .trending-tags-container {
                gap: 8px;
            }

            .trending-tag {
                padding: 7px 13px;
                font-size: 11.5px;
                border-radius: 18px;
                gap: 6px;
            }

            .tag-rank {
                min-width: 20px;
                height: 20px;
                font-size: 9px;
            }

            .tag-icon {
                display: none;
            }

            .sec-header {
                flex-direction: column;
                align-items: stretch;
            }

            .sec-title {
                justify-content: space-between;
            }

            .sec-tools {
                flex-direction: column;
            }

            .filters {
                width: 100%;
            }

            .view-toggle {
                align-self: flex-end;
            }

            .doc-grid {
                grid-template-columns: 1fr;
            }

            .doc-grid.list-mode .doc-card {
                flex-direction: column;
                max-height: none;
            }

            .doc-grid.list-mode .thumb {
                width: 100%;
                min-width: unset;
                height: 120px;
                border-radius: 0;
            }

            .modal-dialog {
                max-height: 95vh;
                border-radius: var(--r-lg);
            }

            .modal-head {
                padding: 14px 16px;
            }

            .modal-body {
                padding: 16px;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: 100%;
                padding-top: 0;
                padding-bottom: 4px;
            }

            .preview-placeholder iframe {
                height: 350px;
            }
        }

        @media (max-width: 480px) {
            .nav-inner {
                padding: 0 16px;
                height: 58px;
            }

            .brand-logo {
                width: 34px;
                height: 34px;
                font-size: 14px;
            }

            .brand-text strong {
                font-size: 14px;
            }

            .brand-text span {
                font-size: 9.5px;
            }

            .notif-btn,
            .avatar-btn {
                width: 34px;
                height: 34px;
            }

            .page {
                padding: 12px;
            }

            .hero {
                border-radius: var(--r-md);
                margin-bottom: 16px;
            }

            .hero-left {
                padding: 20px 16px;
            }

            .hero h2 {
                font-size: 18px;
            }

            .hero-chips {
                display: none !important;
            }

            .stat {
                padding: 14px;
            }

            .stat-icon-wrap {
                width: 34px;
                height: 34px;
                font-size: 15px;
            }

            .stat-val {
                font-size: 20px;
            }

            /* Trending Small Mobile */
            .trending-card {
                padding: 14px 14px;
                border-radius: var(--r-md);
            }

            .trending-header {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 12px;
            }

            .trending-reset-btn {
                align-self: flex-end;
                margin-top: 4px;
                padding: 6px 12px;
                font-size: 11px;
            }

            .trending-tag {
                padding: 6px 11px;
                font-size: 11px;
                border-radius: 16px;
            }

            .sec-header {
                margin-bottom: 12px;
            }

            .sec-title h3 {
                font-size: 14px;
            }

            .filter-pill {
                padding: 4px 10px;
                font-size: 10.5px;
            }

            .doc-card {
                border-radius: 10px;
            }

            .doc-body {
                padding: 12px;
            }

            .doc-title {
                font-size: 12px;
            }

            .doc-desc {
                font-size: 11px;
                -webkit-line-clamp: 2;
            }

            .doc-footer {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .doc-user {
                justify-content: center;
            }

            .doc-actions {
                justify-content: center;
            }

            .empty-card {
                padding: 24px 16px;
                border-radius: var(--r-md);
            }

            .empty-icon {
                width: 52px;
                height: 52px;
                font-size: 24px;
            }

            .upload-btn {
                padding: 9px 16px;
                font-size: 12px;
                width: 100%;
                justify-content: center;
            }

            .footer {
                font-size: 11px;
                padding: 12px 16px;
            }

            .chatbot {
                bottom: 16px;
                right: 16px;
                width: 46px;
                height: 46px;
                font-size: 18px;
            }

            .chatbot-panel {
                bottom: 74px;
                right: 16px;
                left: 16px;
                width: auto;
                max-height: calc(100vh - 120px);
            }

            .chatbot-head {
                padding: 14px 16px;
            }

            .chatbot-body {
                padding: 12px;
            }

            .chatbot-input-row {
                padding: 10px 12px;
            }

            .modal-dialog {
                border-radius: var(--r-md);
            }

            .modal-head {
                padding: 12px 14px;
            }

            .modal-head h5 {
                font-size: 13px;
            }

            .modal-tab {
                padding: 10px 14px;
                font-size: 12px;
            }

            .modal-body {
                padding: 14px;
            }

            .preview-placeholder {
                min-height: 200px;
            }

            .preview-placeholder i {
                font-size: 40px;
            }

            .preview-placeholder iframe {
                height: 280px;
            }

            .info-label {
                font-size: 11px;
            }

            .info-value {
                font-size: 12px;
            }
        }

        @media (max-width: 360px) {
            .brand-text {
                display: none;
            }

            .hero h2 {
                font-size: 16px;
            }

            .hero p {
                font-size: 11px;
            }

            .stat-val {
                font-size: 18px;
            }

            .doc-title {
                font-size: 11px;
            }

            .doc-meta {
                font-size: 9.5px;
            }

            /* Trending Extra Small */
            .trending-title-text h3 {
                font-size: 13px;
            }

            .trending-tag {
                padding: 5px 10px;
                font-size: 10.5px;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .hero {
                min-height: 100px;
            }

            .hero-left {
                padding: 16px 20px;
            }

            .hero-right {
                display: none;
            }

            .hero-chips {
                margin-top: 8px;
            }

            .chatbot-panel {
                max-height: calc(100vh - 100px);
            }

            .modal-dialog {
                max-height: 98vh;
            }
        }

        @media print {

            nav,
            .chatbot,
            .chatbot-panel,
            .mobile-nav-overlay,
            .mobile-nav-menu {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .page {
                padding: 0;
                max-width: 100%;
            }

            .hero {
                background: #333;
                color: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .doc-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        @media (prefers-contrast: high) {

            .stat,
            .doc-card,
            .modal-dialog,
            .chatbot-panel,
            .trending-card {
                border-width: 2px;
            }

            .filter-pill,
            .vbtn,
            .trending-tag {
                border-width: 2px;
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

<body data-detail-url="{{ route('dashboard.get-detail') }}" data-share-base="{{ url('/dashboard') }}">

    @include('components.navbar')

    <!-- Mobile Navigation -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="closeMobileNav()">
        <div class="mobile-nav-menu" onclick="event.stopPropagation()">
            <button class="mobile-nav-close" onclick="closeMobileNav()" aria-label="Tutup Menu">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="mobile-nav-links">
                <a href="#" class="active"><i class="bi bi-house-door"></i> Dashboard</a>
                <a href="#"><i class="bi bi-file-earmark-text"></i> Dokumen</a>
                <a href="#"><i class="bi bi-cloud-upload"></i> Upload</a>
                <a href="#"><i class="bi bi-bar-chart"></i> Statistik</a>
                <a href="#"><i class="bi bi-gear"></i> Pengaturan</a>
            </div>
        </div>
    </div>

    <div class="page">

        <!-- HERO -->
        <div class="hero">
            <div class="hero-dots"></div>
            <div class="hero-left">
                <div class="hero-eyebrow"><i class="bi bi-stars"></i> Portal Repository Akademik</div>
                <h2>Selamat Datang, <span>{{ $username }}</span></h2>
                <p>Kelola dan temukan dokumen akademik Politeknik Negeri Jember dengan mudah dan cepat.</p>
                <div class="hero-chips">
                    <div class="chip"><i class="bi bi-shield-check"></i> Terverifikasi</div>
                    <div class="chip"><i class="bi bi-calendar3"></i> Semester Ganjil 2025</div>
                    <div class="chip"><i class="bi bi-patch-check"></i> Akun Aktif</div>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-avatar-wrap">
                    <div class="hero-avatar">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $username }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="hero-avatar-ring"></div>
                    <div class="hero-avatar-dot"></div>
                </div>
            </div>
        </div>

        <!-- SMART STAT CARDS -->
        <div class="stats">
            <div class="stat stat-1">
                <div class="stat-inner">
                    <div class="stat-icon-wrap"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="stat-info">
                        <div class="stat-val">{{ $totalDokumen }}</div>
                        <div class="stat-label">Total Dokumen</div>
                    </div>
                    <div class="stat-trend">
                        <div class="trend-badge trend-up"><i class="bi bi-arrow-up-short"></i> {{ $uploadBaru }}</div>
                        <div class="trend-sub">bulan ini</div>
                    </div>
                </div>
            </div>

            <div class="stat stat-2">
                <div class="stat-inner">
                    <div class="stat-icon-wrap"><i class="bi bi-cloud-upload"></i></div>
                    <div class="stat-info">
                        <div class="stat-val">{{ $uploadBaru }}</div>
                        <div class="stat-label">Upload Baru</div>
                    </div>
                    <div class="stat-trend">
                        <div class="trend-badge trend-new"><i class="bi bi-lightning"></i> Baru</div>
                        <div class="trend-sub">minggu ini</div>
                    </div>
                </div>
            </div>

            <!-- CARD 3 - PROGRESS -->
            <div class="stat stat-3">
                <div class="stat-inner">
                    <div class="stat-icon-wrap"><i class="bi bi-pie-chart"></i></div>
                    <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <div class="stat-val">{{ $persentasePenggunaan }}%</div>
                            <div class="stat-label">Penggunaan Bulan Ini</div>
                        </div>
                        <div class="stat-progress" style="margin-top: 0;">
                            <div class="stat-progress-fill" id="usageProgressFill" style="width: 0%;"></div>
                        </div>
                        <div class="stat-trend">
                            <div class="trend-badge trend-pct"><i class="bi bi-bar-chart"></i>
                                {{ $persentasePenggunaan }}%</div>
                            <div class="trend-sub">dari kuota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════
             TRENDING / PENCARIAN TERATAS - ELEGANT
             ════════════════════════════════════════ -->
        <div class="trending-section">
            <div class="trending-card">

                <!-- Header -->
                <div class="trending-header">
                    <div class="trending-title-group">
                        <div class="trending-icon-wrapper">
                            <i class="bi bi-fire"></i>
                        </div>
                        <div class="trending-title-text">
                            <h3>Pencarian Terpopuler</h3>
                            <div class="trending-subtitle">Update real-time · Berdasarkan aktivitas user</div>
                        </div>
                    </div>

                    <!-- Reset Button (hidden by default) -->
                    <button class="trending-reset-btn" id="trendingResetBtn" onclick="resetTrendingSearch()">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </button>
                </div>

                <!-- Tags Container -->
                <div class="trending-tags-container" id="trendingTagsContainer">

                    <!-- Rank 1 - Gold -->
                    <a class="trending-tag" data-rank="1" onclick="applyTrendingSearch('sistem informasi', this)">
                        <span class="tag-rank">1</span>
                        <span class="tag-text">Sistem Informasi</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <!-- Rank 2 - Silver -->
                    <a class="trending-tag" data-rank="2" onclick="applyTrendingSearch('kecerdasan buatan', this)">
                        <span class="tag-rank">2</span>
                        <span class="tag-text">Kecerdasan Buatan</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <!-- Rank 3 - Bronze -->
                    <a class="trending-tag" data-rank="3" onclick="applyTrendingSearch('machine learning', this)">
                        <span class="tag-rank">3</span>
                        <span class="tag-text">Machine Learning</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <!-- Rank 4+ -->
                    <a class="trending-tag" data-rank="4" onclick="applyTrendingSearch('jaringan komputer', this)">
                        <span class="tag-rank">4</span>
                        <span class="tag-text">Jaringan Komputer</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <a class="trending-tag" data-rank="5" onclick="applyTrendingSearch('manajemen', this)">
                        <span class="tag-rank">5</span>
                        <span class="tag-text">Manajemen</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <a class="trending-tag" data-rank="6" onclick="applyTrendingSearch('pertanian', this)">
                        <span class="tag-rank">6</span>
                        <span class="tag-text">Pertanian</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <a class="trending-tag" data-rank="7" onclick="applyTrendingSearch('akuntansi', this)">
                        <span class="tag-rank">7</span>
                        <span class="tag-text">Akuntansi</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                    <a class="trending-tag" data-rank="8" onclick="applyTrendingSearch('kesehatan', this)">
                        <span class="tag-rank">8</span>
                        <span class="tag-text">Kesehatan</span>
                        <i class="bi bi-search tag-icon"></i>
                    </a>

                </div>

            </div>
        </div>

        <!-- SECTION HEADER -->
        <div class="sec-header">
            <div class="sec-title">
                <div class="sec-title-bar"></div>
                <h3>Dokumen Saya</h3>
                <div class="sec-badge" id="docCountBadge">{{ $totalDokumen }}</div>
            </div>
            <div class="sec-tools">
                {{-- ═══ FILTER: Jenis Dokumen ═══ --}}
                <div class="filters">
                    <div class="filter-pill active" data-filter="all">Semua</div>
                    <div class="filter-pill" data-filter="tesis">Tesis</div>
                    <div class="filter-pill" data-filter="tugas_akhir">Tugas Akhir</div>
                    <div class="filter-pill" data-filter="skripsi">Skripsi</div>
                    <div class="filter-pill" data-filter="disertasi">Disertasi</div>
                </div>
                <div class="view-toggle">
                    <button type="button" class="vbtn active" id="gridViewBtn" title="Tampilan Grid"
                        aria-label="Tampilan Grid"><i class="bi bi-grid-3x3-gap"></i></button>
                    <button type="button" class="vbtn" id="listViewBtn" title="Tampilan List"
                        aria-label="Tampilan List"><i class="bi bi-list-ul"></i></button>
                </div>
            </div>
        </div>

        <!-- DOCUMENT GRID -->
        @if ($documents->isEmpty())
            <div class="doc-grid">
                <div class="empty-card">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>Tidak ada dokumen ditemukan</h4>
                    <p>Belum ada dokumen yang diunggah. Mulai unggah dokumen akademik pertama Anda.</p>
                    <a href="{{ route('upload.index') }}" class="upload-btn"><i class="bi bi-cloud-upload"></i>
                        Unggah Dokumen</a>
                </div>
            </div>
        @else
            <div class="doc-grid" id="documentGrid">
                @foreach ($documents as $index => $doc)
                    @php
                        $filePath = $doc['file_path'] ?? '';
                        $fileName = basename($filePath);
                        $fileURL = asset('uploads/documents/' . $fileName);
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $judul = $doc['judul'] ?? 'Tanpa Judul';
                        $abstrakRaw = $doc['abstrak'] ?? 'Tidak ada deskripsi';
                        $abstrak = \Illuminate\Support\Str::limit($abstrakRaw, 150);
                        $statusName = $doc['status_name'] ?? 'Unknown';
                        $statusBadge = $doc['status_badge'] ?? 'badge-secondary';
                        $statusId = $doc['status_id'] ?? 0;
                        $gradClass = 'thumb-grad-' . $index % 6;

                        // Deteksi jenis dokumen dari nama_tema atau field jenis_dokumen
                        $jenisDocRaw = strtolower($doc['jenis_dokumen'] ?? $doc['nama_tema'] ?? '');
                        if (str_contains($jenisDocRaw, 'tesis')) {
                            $jenisDoc = 'tesis';
                        } elseif (str_contains($jenisDocRaw, 'tugas akhir') || str_contains($jenisDocRaw, 'tugas_akhir')) {
                            $jenisDoc = 'tugas_akhir';
                        } elseif (str_contains($jenisDocRaw, 'skripsi')) {
                            $jenisDoc = 'skripsi';
                        } elseif (str_contains($jenisDocRaw, 'disertasi')) {
                            $jenisDoc = 'disertasi';
                        } else {
                            $jenisDoc = 'lainnya';
                        }

                        $badgeMap = [
                            'badge-success' => 'b-success',
                            'badge-info' => 'b-info',
                            'badge-warning' => 'b-warn',
                            'badge-danger' => 'b-danger',
                            'badge-secondary' => 'b-gray',
                        ];
                        $badgeClass = $badgeMap[$statusBadge] ?? 'b-gray';

                        $thumbIcon = 'bi-file-earmark-text';
                        if (in_array($fileExt, ['doc', 'docx'])) {
                            $thumbIcon = 'bi-file-earmark-word';
                        } elseif (in_array($fileExt, ['xls', 'xlsx'])) {
                            $thumbIcon = 'bi-file-earmark-spreadsheet';
                        } elseif (in_array($fileExt, ['ppt', 'pptx'])) {
                            $thumbIcon = 'bi-file-earmark-ppt';
                        } elseif ($fileExt === 'pdf') {
                            $thumbIcon = 'bi-file-earmark-pdf';
                        } elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $thumbIcon = 'bi-file-earmark-image';
                        } elseif (in_array($fileExt, ['zip', 'rar', '7z'])) {
                            $thumbIcon = 'bi-file-earmark-zip';
                        }
                    @endphp

                    <div class="doc-card"
                        data-id="{{ $doc['dokumen_id'] }}"
                        data-title="{{ strtolower($judul) }}"
                        data-description="{{ strtolower($abstrak) }}"
                        data-ext="{{ $fileExt }}"
                        data-jenis-doc="{{ $jenisDoc }}"
                        data-full-title="{{ $doc['judul'] ?? 'Tanpa Judul' }}"
                        data-full-description="{{ $doc['abstrak'] ?? 'Tidak ada deskripsi' }}"
                        data-uploader-name="{{ $doc['uploader_name'] ?? 'Admin' }}"
                        data-uploader-email="{{ $doc['uploader_email'] ?? '' }}"
                        data-nama-jurusan="{{ $doc['nama_jurusan'] ?? '' }}"
                        data-nama-prodi="{{ $doc['nama_prodi'] ?? '' }}"
                        data-nama-tema="{{ $doc['nama_tema'] ?? '' }}"
                        data-tahun="{{ $doc['tahun'] ?? '' }}"
                        data-status-id="{{ $statusId }}"
                        data-status-name="{{ $statusName }}"
                        data-status-badge="{{ $badgeClass }}"
                        data-turnitin="{{ $doc['turnitin'] ?? '' }}"
                        data-file-name="{{ $fileName }}"
                        data-file-size="{{ $doc['file_size'] ?? 0 }}"
                        data-tgl-unggah="{{ $doc['tgl_unggah'] ?? '' }}"
                        data-updated-at="{{ $doc['tgl_unggah'] ?? '' }}"
                        data-id-user="{{ $doc['id_user'] }}"
                        data-file-url="{{ $fileURL }}"
                        data-file-type="{{ $fileExt }}"
                        onclick="showDocumentPreview({{ (int) $doc['dokumen_id'] }}, @js($fileURL), @js($fileExt))">

                        <div class="thumb {{ $gradClass }}">
                            <i class="bi {{ $thumbIcon }} thumb-icon"></i>
                            <div class="thumb-ext">{{ $fileExt ?: 'FILE' }}</div>
                        </div>

                        <div class="doc-body">
                            <div class="doc-header">
                                <div class="doc-title">{{ $judul }}</div>
                                <div class="doc-badges">
                                    @if ($statusId > 0)
                                        <span class="badge {{ $badgeClass }}">{{ $statusName }}</span>
                                    @endif
                                    @if (!empty($doc['turnitin']) && is_numeric($doc['turnitin']) && $doc['turnitin'] > 0)
                                        <span class="badge b-info">T: {{ $doc['turnitin'] }}%</span>
                                    @endif
                                    @if ($jenisDoc !== 'lainnya')
                                        <span class="badge b-gray">{{ ucfirst(str_replace('_', ' ', $jenisDoc)) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="doc-desc">{{ $abstrak }}</div>

                            <div class="doc-meta">
                                @if (!empty($doc['nama_jurusan']))
                                    <span><i class="bi bi-briefcase"></i>{{ \Illuminate\Support\Str::limit($doc['nama_jurusan'], 18, '') }}</span>
                                @endif
                                @if (!empty($doc['tahun']))
                                    <span><i class="bi bi-calendar3"></i> {{ $doc['tahun'] }}</span>
                                @endif
                            </div>

                            <div class="doc-footer">
                                <div class="doc-user">
                                    <div class="doc-user-avatar">
                                        @if ($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="doc-user-name">
                                            {{ \Illuminate\Support\Str::limit($doc['uploader_name'] ?? 'Admin', 14, '') }}
                                        </div>
                                        <div class="doc-user-date">
                                            {{ \Carbon\Carbon::parse($doc['tgl_unggah'] ?? 'now')->format('d M y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="doc-actions">
                                    <button class="act-btn act-view" title="Lihat Detail"
                                        onclick="event.stopPropagation(); showDocumentDetail({{ $doc['dokumen_id'] }})">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <a href="{{ $fileURL }}" download class="act-btn act-dl" title="Unduh"
                                        onclick="event.stopPropagation()">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @include('components.footer_browser')

    </div>

    <!-- MODAL -->
    <div class="modal-overlay" id="documentModal">
        <div class="modal-dialog">
            <div class="modal-head">
                <h5 id="modalTitle">Memuat Detail...</h5>
                <button class="modal-close" onclick="closeDocumentModal()" aria-label="Tutup"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-tabs">
                <div class="modal-tab" data-tab="info" onclick="switchTab('info')"><i class="bi bi-info-circle"></i>
                    Informasi</div>
                <div class="modal-tab active" data-tab="preview" onclick="switchTab('preview')"><i
                        class="bi bi-eye"></i> Pratinjau</div>
            </div>
            <div class="modal-body">
                <div class="tab-pane" id="info-tab">
                    <div id="documentInfoContent">
                        <div style="text-align:center;padding:40px 0;">
                            <div
                                style="display:inline-block;width:36px;height:36px;border:3px solid var(--border);border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;">
                            </div>
                            <p style="margin-top:14px;font-size:13px;color:var(--t2);">Memuat informasi dokumen...</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane active" id="preview-tab">
                    <div id="documentViewerContainer" class="preview-placeholder">
                        <i class="bi bi-file-earmark-text"></i>
                        <h4>Pratinjau Dokumen</h4>
                        <p>Memuat pratinjau dokumen...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.chatbot_widget')

    <script>
        // ═══ Mobile Navigation ═══
        function openMobileNav() {
            document.getElementById('mobileNavOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileNav() {
            document.getElementById('mobileNavOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (menuBtn) {
                menuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    openMobileNav();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeMobileNav();
                    closeDocumentModal();
                }
            });
        });

        // ═══ Progress Bar Animation ═══
        (function () {
            var fill = document.getElementById('usageProgressFill');
            if (fill) {
                var pct = parseInt('{{ $persentasePenggunaan }}') || 0;
                setTimeout(function () {
                    fill.style.width = Math.min(pct, 100) + '%';
                }, 400);
            }
        })();

        // ═══ View Mode Toggle ═══
        function setViewMode(mode) {
            var grid = document.getElementById('documentGrid');
            var gridBtn = document.getElementById('gridViewBtn');
            var listBtn = document.getElementById('listViewBtn');
            if (!grid) return;
            if (mode === 'list') {
                grid.classList.add('list-mode');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            } else {
                grid.classList.remove('list-mode');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            }
        }

        document.getElementById('gridViewBtn').addEventListener('click', function () {
            setViewMode('grid');
        });

        document.getElementById('listViewBtn').addEventListener('click', function () {
            setViewMode('list');
        });

        // ═══ Update doc count badge ═══
        function updateDocCount() {
            var badge = document.getElementById('docCountBadge');
            if (!badge) return;
            var visible = document.querySelectorAll('.doc-card:not(.hidden-card)').length;
            badge.textContent = visible;
        }

        // ═══ Filter by Jenis Dokumen ═══
        document.querySelectorAll('.filter-pill').forEach(function (pill) {
            pill.addEventListener('click', function () {
                // Reset trending search state
                resetTrendingSearch(false);

                document.querySelectorAll('.filter-pill').forEach(function (p) {
                    p.classList.remove('active');
                });
                this.classList.add('active');

                var filter = this.dataset.filter;

                document.querySelectorAll('.doc-card').forEach(function (card) {
                    var jenis = (card.dataset.jenisDoc || '').toLowerCase();
                    var show = false;

                    if (filter === 'all') {
                        show = true;
                    } else if (filter === 'tesis') {
                        show = jenis === 'tesis';
                    } else if (filter === 'tugas_akhir') {
                        show = jenis === 'tugas_akhir';
                    } else if (filter === 'skripsi') {
                        show = jenis === 'skripsi';
                    } else if (filter === 'disertasi') {
                        show = jenis === 'disertasi';
                    }

                    card.classList.toggle('hidden-card', !show);
                });

                updateDocCount();
            });
        });

        // ═══ Trending Search - UPDATED WITH ELEGANT DESIGN ═══
        function applyTrendingSearch(keyword, tagEl) {
            // Reset filter pills — set "Semua" as visually neutral (no active)
            document.querySelectorAll('.filter-pill').forEach(function (p) {
                p.classList.remove('active');
            });

            // Highlight the clicked tag with elegant animation
            document.querySelectorAll('.trending-tag').forEach(function (t) {
                t.classList.remove('active-search');
            });
            if (tagEl) tagEl.classList.add('active-search');

            // Show reset button with fade-in animation
            var resetBtn = document.getElementById('trendingResetBtn');
            if (resetBtn) resetBtn.classList.add('visible');

            // Filter cards by keyword match in title or description
            var kw = keyword.toLowerCase();
            document.querySelectorAll('.doc-card').forEach(function (card) {
                var title = (card.dataset.title || '').toLowerCase();
                var desc = (card.dataset.description || '').toLowerCase();
                var jurusan = (card.dataset.namaJurusan || '').toLowerCase();
                var tema = (card.dataset.namaTema || '').toLowerCase();
                var show = title.includes(kw) || desc.includes(kw) || jurusan.includes(kw) || tema.includes(kw);
                card.classList.toggle('hidden-card', !show);
            });

            updateDocCount();
        }

        function resetTrendingSearch(resetPill) {
            // Default: also reset filter pills to "Semua"
            if (resetPill === undefined) resetPill = true;

            // Remove active state from all tags with smooth transition
            document.querySelectorAll('.trending-tag').forEach(function (t) {
                t.classList.remove('active-search');
            });

            // Hide reset button
            var resetBtn = document.getElementById('trendingResetBtn');
            if (resetBtn) resetBtn.classList.remove('visible');

            if (resetPill) {
                document.querySelectorAll('.filter-pill').forEach(function (p) {
                    p.classList.remove('active');
                });
                var allPill = document.querySelector('.filter-pill[data-filter="all"]');
                if (allPill) allPill.classList.add('active');

                document.querySelectorAll('.doc-card').forEach(function (card) {
                    card.classList.remove('hidden-card');
                });

                updateDocCount();
            }
        }

        // ═══ Modal Functions ═══
        function openDocumentModal(title) {
            var modal = document.getElementById('documentModal');
            document.getElementById('modalTitle').textContent = title || 'Detail Dokumen';
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            switchTab('preview');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('documentModal').addEventListener('click', function (e) {
            if (e.target === this) closeDocumentModal();
        });

        function switchTab(tabName) {
            document.querySelectorAll('.modal-tab').forEach(function (t) {
                t.classList.toggle('active', t.dataset.tab === tabName);
            });
            document.querySelectorAll('.tab-pane').forEach(function (p) {
                p.classList.toggle('active', p.id === tabName + '-tab');
            });
        }

        function showDocumentPreview(docId, fileUrl, fileType) {
            openDocumentModal('Pratinjau Dokumen');
            var container = document.getElementById('documentViewerContainer');
            if (!container) return;

            if (fileType === 'pdf' && fileUrl) {
                container.innerHTML = '<iframe src="' + fileUrl + '" title="Pratinjau PDF"></iframe>';
            } else {
                var iconName = 'text';
                if (fileType === 'docx' || fileType === 'doc') iconName = 'word';
                else if (fileType === 'xls' || fileType === 'xlsx') iconName = 'spreadsheet';
                else if (fileType === 'ppt' || fileType === 'pptx') iconName = 'ppt';

                container.innerHTML =
                    '<div class="preview-placeholder"><i class="bi bi-file-earmark-' + iconName + '"></i>' +
                    '<h4>' + (fileType ? fileType.toUpperCase() : 'FILE') + ' Tidak Dapat Dipratinjau</h4>' +
                    '<p>Format ini tidak didukung untuk pratinjau langsung. Silakan unduh file.</p>' +
                    (fileUrl
                        ? '<a href="' + fileUrl + '" download class="upload-btn" style="margin-top:16px;font-size:13px;padding:9px 20px;"><i class="bi bi-download"></i> Unduh File</a>'
                        : '') +
                    '</div>';
            }
        }

        function showDocumentDetail(docId) {
            openDocumentModal('Detail Dokumen');
            switchTab('info');
            var infoContainer = document.getElementById('documentInfoContent');
            if (!infoContainer) return;

            var card = document.querySelector('.doc-card[data-id="' + docId + '"]');
            if (card) {
                renderInfoFromCard(card, infoContainer);
                return;
            }

            var detailUrl = document.body.dataset.detailUrl;
            if (detailUrl) {
                infoContainer.innerHTML =
                    '<div style="text-align:center;padding:40px 0;">' +
                    '<div style="display:inline-block;width:36px;height:36px;border:3px solid var(--border);border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div>' +
                    '<p style="margin-top:14px;font-size:13px;color:var(--t2);">Memuat informasi dokumen...</p></div>';

                fetch(detailUrl + '?id=' + docId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.success) infoContainer.innerHTML = buildInfoHTML(data.data || data);
                        else infoContainer.innerHTML = '<p style="color:var(--rose);text-align:center;padding:20px;">Gagal memuat detail.</p>';
                    })
                    .catch(function () {
                        infoContainer.innerHTML = '<p style="color:var(--rose);text-align:center;padding:20px;">Terjadi kesalahan saat memuat data.</p>';
                    });
            }
        }

        function renderInfoFromCard(card, container) {
            container.innerHTML = buildInfoHTML({
                judul: card.dataset.fullTitle,
                abstrak: card.dataset.fullDescription,
                uploader_name: card.dataset.uploaderName,
                uploader_email: card.dataset.uploaderEmail,
                nama_jurusan: card.dataset.namaJurusan,
                nama_prodi: card.dataset.namaProdi,
                nama_tema: card.dataset.namaTema,
                tahun: card.dataset.tahun,
                status_name: card.dataset.statusName,
                status_badge: card.dataset.statusBadge,
                turnitin: card.dataset.turnitin,
                file_name: card.dataset.fileName,
                file_size: card.dataset.fileSize,
                tgl_unggah: card.dataset.tglUnggah,
                file_url: card.dataset.fileUrl,
                file_type: card.dataset.fileType
            });
        }

        function buildInfoHTML(d) {
            var rows = [
                { label: 'Judul', value: d.judul || d.full_title || '-' },
                { label: 'Abstrak', value: d.abstrak || d.full_description || '-' },
                { label: 'Pengunggah', value: (d.uploader_name || '-') + (d.uploader_email ? (' (' + d.uploader_email + ')') : '') }
            ];

            if (d.nama_jurusan) rows.push({ label: 'Jurusan', value: d.nama_jurusan });
            if (d.nama_prodi)   rows.push({ label: 'Prodi',   value: d.nama_prodi });
            if (d.nama_tema)    rows.push({ label: 'Tema',    value: d.nama_tema });
            if (d.tahun)        rows.push({ label: 'Tahun',   value: d.tahun });
            if (d.status_name)  rows.push({
                label: 'Status',
                value: '<span class="info-badge-inline badge ' + (d.status_badge || 'b-gray') + '">' + d.status_name + '</span>'
            });
            if (d.turnitin && Number(d.turnitin) > 0) rows.push({ label: 'Turnitin', value: d.turnitin + '%' });

            rows.push({ label: 'File',     value: (d.file_name || '-') + ' (' + formatFileSize(d.file_size) + ')' });
            rows.push({ label: 'Diunggah', value: formatDate(d.tgl_unggah || d.updated_at) });

            var html = '<div class="info-grid">';
            rows.forEach(function (r) {
                html += '<div class="info-row"><div class="info-label">' + r.label +
                    '</div><div class="info-value">' + r.value + '</div></div>';
            });
            html += '</div>';

            if (d.file_url) {
                html += '<div style="margin-top:20px;text-align:right;">' +
                    '<a href="' + d.file_url + '" download class="upload-btn" style="font-size:12.5px;padding:9px 18px;display:inline-flex;">' +
                    '<i class="bi bi-download"></i> Unduh File</a></div>';
            }

            return html;
        }

        function formatFileSize(bytes) {
            if (!bytes || bytes === 0) return '0 B';
            var k = 1024, sizes = ['B', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'short', year: 'numeric'
                });
            } catch (e) {
                return dateStr;
            }
        }
    </script>

</body>

</html>
