<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPDB 2025/2026 – SMA Muhammadiyah 1 Purwokerto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #0a1628;
            --navy2:   #122040;
            --gold:    #c9972a;
            --gold2:   #e8b84b;
            --cream:   #f9f5ee;
            --white:   #ffffff;
            --gray:    #6b7280;
            --light:   #f3f4f6;
            --green:   #16a34a;
            --red:     #dc2626;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--navy);
            overflow-x: hidden;
        }

        /* ── NAVBAR ─────────────────────────── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5vw;
            height: 72px;
            background: rgba(10,22,40,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(201,151,42,.25);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; color: var(--gold2); letter-spacing: .03em;
        }
        .nav-brand .emblem {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: grid; place-items: center;
            font-size: 1.1rem; font-weight: 900; color: var(--navy);
        }
        .nav-links { display: flex; gap: 32px; list-style: none; }
        .nav-links a {
            color: rgba(255,255,255,.75); text-decoration: none;
            font-size: .875rem; font-weight: 500; letter-spacing: .04em;
            text-transform: uppercase; transition: color .2s;
        }
        .nav-links a:hover { color: var(--gold2); }

        /* NAV AUTH BUTTONS */
        .nav-auth {
            display: flex; gap: 10px; align-items: center;
        }
        .btn-nav-login {
            padding: 9px 22px; border-radius: 4px;
            border: 1px solid rgba(201,151,42,.5);
            color: var(--gold2); font-weight: 600; font-size: .85rem;
            background: transparent; cursor: pointer;
            transition: all .2s; font-family: 'DM Sans', sans-serif;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-nav-login:hover { background: rgba(201,151,42,.1); border-color: var(--gold2); }
        .btn-nav-register {
            padding: 9px 22px; border-radius: 4px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); font-weight: 700; font-size: .85rem;
            cursor: pointer; transition: opacity .2s; font-family: 'DM Sans', sans-serif;
            border: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-nav-register:hover { opacity: .88; }

        /* DASHBOARD BUTTON (hidden by default) */
        .btn-dashboard {
            display: none;
            padding: 9px 22px; border-radius: 4px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); font-weight: 700; font-size: .85rem;
            cursor: pointer; transition: all .2s; font-family: 'DM Sans', sans-serif;
            border: none; align-items: center; gap: 8px;
        }
        .btn-dashboard:hover { opacity: .88; transform: translateY(-1px); }
        .user-nav-info {
            display: none;
            align-items: center; gap: 12px;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: var(--navy); font-size: .85rem;
        }
        .user-name { color: rgba(255,255,255,.85); font-size: .875rem; font-weight: 500; }
        .btn-logout {
            padding: 6px 14px; border-radius: 4px;
            border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.6); font-size: .78rem;
            background: transparent; cursor: pointer; font-family: 'DM Sans', sans-serif;
            transition: all .2s;
        }
        .btn-logout:hover { border-color: var(--red); color: #fca5a5; }

        .nav-cta {
            padding: 10px 24px; border-radius: 4px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); font-weight: 600; font-size: .875rem;
            text-decoration: none; transition: opacity .2s;
        }
        .nav-cta:hover { opacity: .88; }
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
        .hamburger span { display: block; width: 24px; height: 2px; background: var(--gold2); transition: .3s; }

        /* ── MODAL OVERLAY ──────────────────── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 2000;
            background: rgba(5, 12, 25, 0.82);
            backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .3s;
        }
        .modal-overlay.active {
            opacity: 1; pointer-events: all;
        }
        .modal-box {
            background: var(--navy2);
            border: 1px solid rgba(201,151,42,.25);
            border-radius: 16px;
            width: 100%; max-width: 460px;
            margin: 0 16px;
            padding: 40px;
            position: relative;
            transform: translateY(24px) scale(.97);
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
            box-shadow: 0 32px 80px rgba(0,0,0,.5);
        }
        .modal-overlay.active .modal-box {
            transform: translateY(0) scale(1);
        }
        .modal-close {
            position: absolute; top: 18px; right: 20px;
            background: rgba(255,255,255,.08); border: none;
            width: 32px; height: 32px; border-radius: 50%;
            cursor: pointer; color: rgba(255,255,255,.6);
            font-size: 1rem; display: grid; place-items: center;
            transition: background .2s;
        }
        .modal-close:hover { background: rgba(255,255,255,.15); }
        .modal-logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 28px;
        }
        .modal-logo .emblem {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: grid; place-items: center;
            font-weight: 900; color: var(--navy); font-size: .95rem;
        }
        .modal-logo span {
            font-family: 'Playfair Display', serif;
            color: var(--gold2); font-size: .95rem;
        }
        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem; font-weight: 900; color: var(--white);
            margin-bottom: 6px;
        }
        .modal-subtitle { font-size: .875rem; color: rgba(255,255,255,.5); margin-bottom: 32px; }
        .modal-subtitle a { color: var(--gold2); cursor: pointer; text-decoration: none; }
        .modal-subtitle a:hover { text-decoration: underline; }

        /* FORM ELEMENTS */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: .8rem; font-weight: 600;
            color: rgba(255,255,255,.65); margin-bottom: 8px;
            letter-spacing: .06em; text-transform: uppercase;
        }
        .form-control {
            width: 100%; padding: 12px 16px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 8px; color: var(--white);
            font-size: .95rem; font-family: 'DM Sans', sans-serif;
            transition: border-color .2s, background .2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--gold2);
            background: rgba(255,255,255,.09);
        }
        .form-control::placeholder { color: rgba(255,255,255,.3); }
        .form-control.error { border-color: var(--red); }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .btn-form-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); font-weight: 700; font-size: 1rem;
            border: none; border-radius: 8px; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: transform .2s, box-shadow .2s;
            margin-top: 8px;
            box-shadow: 0 8px 24px rgba(201,151,42,.3);
        }
        .btn-form-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(201,151,42,.4); }
        .btn-form-submit:active { transform: translateY(0); }

        .form-divider {
            display: flex; align-items: center; gap: 14px;
            margin: 22px 0;
        }
        .form-divider::before, .form-divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,.1);
        }
        .form-divider span { font-size: .78rem; color: rgba(255,255,255,.35); }

        .form-error {
            font-size: .8rem; color: #fca5a5;
            margin-top: 6px; display: none;
        }
        .form-error.show { display: block; }

        .alert-success {
            background: rgba(22,163,74,.15);
            border: 1px solid rgba(22,163,74,.3);
            border-radius: 8px; padding: 12px 16px;
            font-size: .875rem; color: #86efac;
            margin-bottom: 18px; display: none;
        }
        .alert-success.show { display: block; }
        .alert-error {
            background: rgba(220,38,38,.12);
            border: 1px solid rgba(220,38,38,.25);
            border-radius: 8px; padding: 12px 16px;
            font-size: .875rem; color: #fca5a5;
            margin-bottom: 18px; display: none;
        }
        .alert-error.show { display: block; }

        /* ── DASHBOARD PANEL ────────────────── */
        .dashboard-overlay {
            position: fixed; inset: 0; z-index: 1500;
            background: rgba(5,12,25,.88);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .3s;
        }
        .dashboard-overlay.active {
            opacity: 1; pointer-events: all;
        }
        .dashboard-panel {
            background: var(--navy);
            border: 1px solid rgba(201,151,42,.2);
            border-radius: 20px;
            width: 100%; max-width: 860px;
            margin: 0 16px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(32px);
            transition: transform .4s cubic-bezier(.34,1.56,.64,1);
            box-shadow: 0 40px 100px rgba(0,0,0,.6);
        }
        .dashboard-overlay.active .dashboard-panel {
            transform: translateY(0);
        }
        .dash-header {
            padding: 32px 40px;
            background: linear-gradient(135deg, var(--navy2), rgba(201,151,42,.08));
            border-bottom: 1px solid rgba(201,151,42,.15);
            display: flex; align-items: center; justify-content: space-between;
        }
        .dash-user {
            display: flex; align-items: center; gap: 16px;
        }
        .dash-avatar {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; color: var(--navy); font-size: 1.3rem;
            box-shadow: 0 0 0 4px rgba(201,151,42,.2);
        }
        .dash-user-info .dash-greeting { font-size: .8rem; color: rgba(255,255,255,.5); }
        .dash-user-info .dash-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem; color: var(--white); font-weight: 700;
        }
        .btn-close-dash {
            background: rgba(255,255,255,.08); border: none;
            width: 40px; height: 40px; border-radius: 50%;
            cursor: pointer; color: rgba(255,255,255,.6);
            font-size: 1.1rem; display: grid; place-items: center;
            transition: background .2s;
        }
        .btn-close-dash:hover { background: rgba(255,255,255,.15); }

        .dash-body { padding: 32px 40px 40px; }

        .dash-status-card {
            background: rgba(201,151,42,.08);
            border: 1px solid rgba(201,151,42,.2);
            border-radius: 12px; padding: 20px 24px;
            margin-bottom: 28px;
            display: flex; align-items: center; gap: 16px;
        }
        .status-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: grid; place-items: center; font-size: 1.4rem; flex-shrink: 0;
        }
        .status-info .status-label { font-size: .75rem; color: rgba(255,255,255,.5); letter-spacing: .08em; text-transform: uppercase; }
        .status-info .status-value { font-size: 1rem; font-weight: 600; color: var(--white); margin-top: 2px; }
        .status-badge {
            margin-left: auto;
            padding: 6px 14px; border-radius: 100px;
            background: rgba(22,163,74,.15); border: 1px solid rgba(22,163,74,.3);
            color: #86efac; font-size: .75rem; font-weight: 600;
            letter-spacing: .06em; text-transform: uppercase;
        }
        .status-badge.pending {
            background: rgba(234,179,8,.15); border-color: rgba(234,179,8,.3);
            color: #fde047;
        }

        .dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
        .dash-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px; padding: 20px;
        }
        .dash-card-title {
            font-size: .75rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--gold); margin-bottom: 12px;
        }
        .dash-card-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem; font-weight: 700; color: var(--white);
        }
        .dash-card-sub { font-size: .82rem; color: rgba(255,255,255,.45); margin-top: 4px; }

        .dash-timeline {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px; padding: 24px;
            margin-bottom: 28px;
        }
        .dash-timeline-title {
            font-size: .8rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--gold); margin-bottom: 20px;
        }
        .dash-tl { display: flex; flex-direction: column; gap: 14px; }
        .dash-tl-item {
            display: flex; gap: 14px; align-items: center;
        }
        .dash-tl-dot {
            width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
        }
        .dash-tl-dot.done { background: var(--gold2); }
        .dash-tl-dot.active {
            background: var(--gold2);
            box-shadow: 0 0 0 3px rgba(232,184,75,.25);
        }
        .dash-tl-dot.upcoming { background: rgba(255,255,255,.2); }
        .dash-tl-text { font-size: .875rem; color: rgba(255,255,255,.7); flex: 1; }
        .dash-tl-date { font-size: .78rem; color: rgba(255,255,255,.35); }
        .dash-tl-text.active-step { color: var(--white); font-weight: 600; }

        .dash-actions { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-dash-action {
            padding: 11px 24px; border-radius: 8px;
            font-size: .875rem; font-weight: 600;
            cursor: pointer; font-family: 'DM Sans', sans-serif;
            transition: all .2s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-dash-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); border: none;
        }
        .btn-dash-primary:hover { opacity: .88; transform: translateY(-1px); }
        .btn-dash-outline {
            background: transparent;
            border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.7);
        }
        .btn-dash-outline:hover { border-color: var(--gold2); color: var(--gold2); }

        /* ── HERO ───────────────────────────── */
        #hero {
            position: relative; min-height: 100vh;
            display: flex; align-items: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                linear-gradient(to bottom, rgba(10,22,40,.78) 0%, rgba(10,22,40,.55) 50%, rgba(10,22,40,.85) 100%),
                url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1600&q=80') center/cover no-repeat;
        }
        .hero-pattern {
            position: absolute; inset: 0; z-index: 1; opacity: .06;
            background-image: repeating-linear-gradient(45deg, var(--gold) 0, var(--gold) 1px, transparent 0, transparent 50%);
            background-size: 20px 20px;
        }
        .hero-content {
            position: relative; z-index: 2;
            max-width: 780px; padding: 0 5vw; padding-top: 80px;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(201,151,42,.18); border: 1px solid rgba(201,151,42,.4);
            padding: 6px 16px; border-radius: 100px;
            font-size: .78rem; font-weight: 600; color: var(--gold2);
            letter-spacing: .1em; text-transform: uppercase;
            margin-bottom: 28px;
            animation: fadeUp .7s both;
        }
        .hero-badge::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--gold2); animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
        h1.hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 6vw, 5.2rem);
            line-height: 1.08; color: var(--white); font-weight: 900;
            margin-bottom: 24px;
            animation: fadeUp .7s .1s both;
        }
        h1.hero-title em {
            font-style: italic; color: var(--gold2);
            display: block;
        }
        .hero-sub {
            font-size: 1.1rem; color: rgba(255,255,255,.78);
            line-height: 1.7; max-width: 540px; margin-bottom: 44px;
            font-weight: 300;
            animation: fadeUp .7s .2s both;
        }
        .hero-actions {
            display: flex; gap: 16px; flex-wrap: wrap;
            animation: fadeUp .7s .3s both;
        }
        .btn-primary {
            padding: 14px 36px; border-radius: 4px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: var(--navy); font-weight: 700; font-size: .95rem;
            text-decoration: none; transition: transform .2s, box-shadow .2s;
            box-shadow: 0 8px 24px rgba(201,151,42,.4);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(201,151,42,.5); }
        .btn-outline {
            padding: 14px 36px; border-radius: 4px;
            border: 1px solid rgba(255,255,255,.45);
            color: var(--white); font-weight: 500; font-size: .95rem;
            text-decoration: none; transition: border-color .2s, background .2s;
        }
        .btn-outline:hover { border-color: var(--gold2); background: rgba(201,151,42,.1); }

        .hero-stats {
            position: absolute; bottom: 48px; right: 5vw; z-index: 2;
            display: flex; gap: 32px;
            animation: fadeUp .7s .4s both;
        }
        .stat-card {
            text-align: center;
            padding: 20px 28px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 8px;
            backdrop-filter: blur(8px);
        }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 900; color: var(--gold2);
            display: block;
        }
        .stat-label { font-size: .75rem; color: rgba(255,255,255,.6); letter-spacing: .08em; text-transform: uppercase; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── COUNTDOWN ──────────────────────── */
        #countdown {
            background: linear-gradient(135deg, var(--navy), var(--navy2));
            padding: 48px 5vw;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 24px;
        }
        .countdown-label {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem; color: var(--white);
        }
        .countdown-label span { color: var(--gold2); }
        .countdown-timer { display: flex; gap: 16px; }
        .time-box {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(201,151,42,.25);
            border-radius: 8px; padding: 16px 20px; text-align: center; min-width: 72px;
        }
        .time-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem; font-weight: 700; color: var(--gold2); display: block;
        }
        .time-unit { font-size: .68rem; color: rgba(255,255,255,.5); letter-spacing: .1em; text-transform: uppercase; }
        .countdown-cta .btn-primary { font-size: .85rem; padding: 12px 28px; }

        /* ── SECTION BASE ───────────────────── */
        section { padding: 100px 5vw; }
        .section-tag {
            display: inline-block;
            font-size: .72rem; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gold);
            margin-bottom: 14px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.9rem, 4vw, 3rem);
            line-height: 1.15; color: var(--navy); font-weight: 900;
            margin-bottom: 16px;
        }
        .section-title.light { color: var(--white); }
        .section-sub {
            font-size: 1rem; color: var(--gray); line-height: 1.75;
            max-width: 580px;
        }
        .section-sub.light { color: rgba(255,255,255,.65); }
        .divider {
            width: 56px; height: 3px;
            background: linear-gradient(to right, var(--gold), var(--gold2));
            margin: 20px 0 36px;
        }

        /* ── VISI MISI ──────────────────────── */
        #visi-misi {
            background: var(--white);
            display: grid; grid-template-columns: 1fr 1fr; gap: 0;
            padding: 0;
        }
        .vm-block {
            padding: 80px 6vw;
            position: relative; overflow: hidden;
        }
        .vm-block.visi { background: var(--navy); }
        .vm-block.misi { background: var(--cream); }
        .vm-block.visi::before {
            content: '"'; position: absolute; top: -20px; right: 24px;
            font-family: 'Playfair Display', serif;
            font-size: 18rem; line-height: 1; color: rgba(201,151,42,.06);
            pointer-events: none;
        }
        .vm-icon {
            width: 52px; height: 52px; border-radius: 12px; margin-bottom: 28px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: grid; place-items: center; font-size: 1.5rem;
        }
        .vm-quote {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem; font-style: italic; line-height: 1.6;
            color: var(--white); margin-bottom: 28px;
        }
        .vm-block.misi .vm-quote { color: var(--navy); }
        .misi-list { list-style: none; display: flex; flex-direction: column; gap: 16px; }
        .misi-list li {
            display: flex; gap: 14px; align-items: flex-start;
            font-size: .93rem; line-height: 1.65; color: var(--navy);
        }
        .misi-list li::before {
            content: '';
            min-width: 20px; height: 20px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            margin-top: 2px;
            display: grid; place-items: center;
            font-size: .6rem; color: var(--navy); font-weight: 700;
        }

        /* ── FASILITAS ──────────────────────── */
        #fasilitas { background: var(--cream); }
        .fasilitas-header { max-width: 600px; margin-bottom: 60px; }
        .fasilitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }
        .fasilitas-card {
            background: var(--white);
            border-radius: 12px; padding: 32px 28px;
            border: 1px solid rgba(10,22,40,.06);
            transition: transform .3s, box-shadow .3s;
            position: relative; overflow: hidden;
        }
        .fasilitas-card::after {
            content: ''; position: absolute;
            bottom: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(to right, var(--gold), var(--gold2));
            transform: scaleX(0); transform-origin: left;
            transition: transform .3s;
        }
        .fasilitas-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(10,22,40,.1); }
        .fasilitas-card:hover::after { transform: scaleX(1); }
        .fas-icon { font-size: 2rem; margin-bottom: 18px; display: block; }
        .fas-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; font-weight: 700; color: var(--navy); margin-bottom: 10px;
        }
        .fas-desc { font-size: .875rem; color: var(--gray); line-height: 1.65; }

        /* ── PPDB INFO ──────────────────────── */
        #ppdb {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
            position: relative; overflow: hidden;
        }
        #ppdb::before {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(circle at 70% 50%, rgba(201,151,42,.12) 0%, transparent 60%);
        }
        .ppdb-inner { position: relative; z-index: 1; }
        .ppdb-header { margin-bottom: 60px; }
        .ppdb-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; align-items: start; }

        .ppdb-timeline { display: flex; flex-direction: column; gap: 0; }
        .tl-item {
            display: flex; gap: 24px; padding-bottom: 36px;
            position: relative;
        }
        .tl-item:not(:last-child)::before {
            content: ''; position: absolute;
            left: 19px; top: 40px; bottom: 0;
            width: 2px; background: rgba(201,151,42,.2);
        }
        .tl-dot {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: grid; place-items: center;
            font-size: .8rem; font-weight: 700; color: var(--navy);
        }
        .tl-body {}
        .tl-date { font-size: .75rem; color: var(--gold); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 4px; }
        .tl-title { font-size: 1rem; font-weight: 600; color: var(--white); margin-bottom: 4px; }
        .tl-desc { font-size: .85rem; color: rgba(255,255,255,.55); line-height: 1.6; }

        .ppdb-info-cards { display: flex; flex-direction: column; gap: 20px; }
        .info-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(201,151,42,.2);
            border-radius: 10px; padding: 24px;
        }
        .info-card-title {
            font-size: .75rem; letter-spacing: .1em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 12px; font-weight: 600;
        }
        .info-card-body { font-size: .9rem; color: rgba(255,255,255,.75); line-height: 1.65; }
        .req-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .req-list li {
            display: flex; gap: 10px; align-items: flex-start;
            font-size: .875rem; color: rgba(255,255,255,.7);
        }
        .req-list li::before { content: '✓'; color: var(--gold2); font-weight: 700; flex-shrink: 0; }
        .biaya-table { width: 100%; border-collapse: collapse; }
        .biaya-table td {
            padding: 8px 0; font-size: .875rem; color: rgba(255,255,255,.7);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .biaya-table td:last-child { text-align: right; color: var(--gold2); font-weight: 600; }

        .ppdb-actions { margin-top: 48px; display: flex; gap: 16px; flex-wrap: wrap; }

        /* ── GALLERY ────────────────────────── */
        #galeri { background: var(--white); }
        .gallery-header { margin-bottom: 48px; }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 200px 200px;
            gap: 12px;
        }
        .gallery-item {
            border-radius: 8px; overflow: hidden;
            position: relative; cursor: pointer;
        }
        .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 2; }
        .gallery-item:nth-child(4) { grid-column: span 2; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
        .gallery-item:hover img { transform: scale(1.07); }
        .gallery-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(10,22,40,.7) 0%, transparent 60%);
            opacity: 0; transition: opacity .3s;
            display: flex; align-items: flex-end; padding: 20px;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-caption { font-size: .85rem; color: var(--white); font-weight: 500; }

        /* ── MAP ────────────────────────────── */
        #lokasi { background: var(--cream); padding-bottom: 60px; }
        .map-container { display: grid; grid-template-columns: 1fr 1.6fr; gap: 48px; align-items: start; }
        .map-info-cards { display: flex; flex-direction: column; gap: 20px; }
        .loc-card {
            background: var(--white);
            border-radius: 10px; padding: 24px 24px 24px 20px;
            border-left: 4px solid var(--gold);
            box-shadow: 0 2px 12px rgba(10,22,40,.06);
        }
        .loc-card-label { font-size: .72rem; letter-spacing: .1em; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; font-weight: 600; }
        .loc-card-value { font-size: .95rem; color: var(--navy); line-height: 1.6; font-weight: 500; }
        .map-embed { border-radius: 12px; overflow: hidden; box-shadow: 0 16px 40px rgba(10,22,40,.12); height: 400px; }
        .map-embed iframe { width: 100%; height: 100%; border: 0; display: block; }

        /* ── FOOTER ─────────────────────────── */
        footer { background: var(--navy); padding: 64px 5vw 32px; }
        .footer-top {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 40px; margin-bottom: 48px;
        }
        .footer-brand .nav-brand { margin-bottom: 16px; }
        .footer-tagline { font-size: .875rem; color: rgba(255,255,255,.5); line-height: 1.7; }
        .footer-col h4 {
            font-size: .8rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--gold2); margin-bottom: 20px;
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .footer-col ul li a {
            font-size: .875rem; color: rgba(255,255,255,.55);
            text-decoration: none; transition: color .2s;
        }
        .footer-col ul li a:hover { color: var(--gold2); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            padding-top: 24px; display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: 8px;
        }
        .footer-copy { font-size: .8rem; color: rgba(255,255,255,.35); }

        /* ── RESPONSIVE ─────────────────────── */
        @media (max-width: 900px) {
            #visi-misi { grid-template-columns: 1fr; }
            .ppdb-grid { grid-template-columns: 1fr; }
            .map-container { grid-template-columns: 1fr; }
            .gallery-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
            .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 1; height: 220px; }
            .gallery-item:nth-child(4) { grid-column: span 1; }
            .footer-top { grid-template-columns: 1fr 1fr; }
            .hero-stats { position: static; flex-wrap: wrap; margin-top: 40px; }
            .dash-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .countdown-timer { gap: 8px; }
            .time-box { padding: 12px 14px; min-width: 58px; }
            .gallery-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .gallery-item:nth-child(1),
            .gallery-item:nth-child(4) { grid-column: span 1; }
            .gallery-item { height: 200px; }
            .footer-top { grid-template-columns: 1fr; }
            .dash-header { padding: 24px; }
            .dash-body { padding: 24px; }
            .form-row { grid-template-columns: 1fr; }
        }

        /* ── SCROLL REVEAL ──────────────────── */
        .reveal {
            opacity: 0; transform: translateY(32px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* Scrollbar for dashboard */
        .dashboard-panel::-webkit-scrollbar { width: 6px; }
        .dashboard-panel::-webkit-scrollbar-track { background: transparent; }
        .dashboard-panel::-webkit-scrollbar-thumb { background: rgba(201,151,42,.3); border-radius: 3px; }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════ -->
<!-- NAVBAR -->
<!-- ══════════════════════════════════════════ -->
<nav>
    <div class="nav-brand">
        <div class="emblem">N</div>
        <span>SMA Muhammadiyah 1 Purwokerto</span>
    </div>
    <ul class="nav-links">
        <li><a href="#visi-misi">Visi & Misi</a></li>
        <li><a href="#fasilitas">Fasilitas</a></li>
        <li><a href="#ppdb">PPDB</a></li>
        <li><a href="#galeri">Galeri</a></li>
        <li><a href="#lokasi">Lokasi</a></li>
    </ul>

    <!-- Auth Buttons (shown when NOT logged in) -->
    <div class="nav-auth" id="nav-auth-buttons">
        <a href="/login" class="btn-nav-login">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Masuk
        </a>
        <a href="/register" class="btn-nav-register">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
            Daftar
        </a>
    </div>

    <!-- User info + Dashboard (shown when logged in) -->
    <div class="user-nav-info" id="nav-user-info">
        <div class="user-avatar" id="nav-avatar">–</div>
        <span class="user-name" id="nav-username">–</span>
        <button class="btn-dashboard" id="btn-dashboard-nav" onclick="openDashboard()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </button>
        <button class="btn-logout" onclick="doLogout()">Keluar</button>
    </div>

    <div class="hamburger">
        <span></span><span></span><span></span>
    </div>
</nav>


<!-- ══════════════════════════════════════════ -->
<!-- LOGIN MODAL -->
<!-- ══════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-login">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('login')">✕</button>
        <div class="modal-logo">
            <div class="emblem">N</div>
            <span>SMA Muhammadiyah 1 Purwokerto</span>
        </div>
        <h2 class="modal-title">Selamat Datang</h2>
        <p class="modal-subtitle">Belum punya akun? <a onclick="switchModal('login','register')">Daftar sekarang →</a></p>

        <div class="alert-success" id="login-success">Login berhasil! Mengalihkan…</div>
        <div class="alert-error" id="login-error">Email atau password salah. Coba lagi.</div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="login-email" placeholder="nama@email.com">
            <div class="form-error" id="err-login-email">Email tidak valid.</div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" id="login-password" placeholder="••••••••">
            <div class="form-error" id="err-login-pass">Password minimal 6 karakter.</div>
        </div>

        <button class="btn-form-submit" onclick="doLogin()">Masuk ke Akun</button>

        <div class="form-divider"><span>atau masuk sebagai demo</span></div>
        <button class="btn-form-submit" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.15);box-shadow:none;" onclick="demoLogin()">
            🎓 Demo Login (Calon Siswa)
        </button>
    </div>
</div>


<!-- ══════════════════════════════════════════ -->
<!-- REGISTER MODAL -->
<!-- ══════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-register">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('register')">✕</button>
        <div class="modal-logo">
            <div class="emblem">N</div>
            <span>SMA Muhammadiyah 1 Purwokerto</span>
        </div>
        <h2 class="modal-title">Buat Akun</h2>
        <p class="modal-subtitle">Sudah punya akun? <a onclick="switchModal('register','login')">Masuk di sini →</a></p>

        <div class="alert-success" id="register-success">Akun berhasil dibuat! Silakan masuk.</div>
        <div class="alert-error" id="register-error">Terjadi kesalahan. Periksa data Anda.</div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Depan</label>
                <input type="text" class="form-control" id="reg-firstname" placeholder="Budi">
                <div class="form-error" id="err-reg-fname">Wajib diisi.</div>
            </div>
            <div class="form-group">
                <label>Nama Belakang</label>
                <input type="text" class="form-control" id="reg-lastname" placeholder="Santoso">
                <div class="form-error" id="err-reg-lname">Wajib diisi.</div>
            </div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="reg-email" placeholder="nama@email.com">
            <div class="form-error" id="err-reg-email">Email tidak valid.</div>
        </div>
        <div class="form-group">
            <label>Nomor HP / WhatsApp</label>
            <input type="tel" class="form-control" id="reg-phone" placeholder="08xxxxxxxxxx">
            <div class="form-error" id="err-reg-phone">Nomor tidak valid.</div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" id="reg-password" placeholder="Minimal 6 karakter">
            <div class="form-error" id="err-reg-pass">Password minimal 6 karakter.</div>
        </div>
        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" class="form-control" id="reg-confirm" placeholder="Ulangi password">
            <div class="form-error" id="err-reg-confirm">Password tidak cocok.</div>
        </div>

        <button class="btn-form-submit" onclick="doRegister()">Buat Akun Sekarang</button>
    </div>
</div>


<!-- ══════════════════════════════════════════ -->
<!-- DASHBOARD PANEL -->
<!-- ══════════════════════════════════════════ -->
<div class="dashboard-overlay" id="dashboard-overlay">
    <div class="dashboard-panel">
        <div class="dash-header">
            <div class="dash-user">
                <div class="dash-avatar" id="dash-avatar">B</div>
                <div class="dash-user-info">
                    <div class="dash-greeting">Selamat datang kembali 👋</div>
                    <div class="dash-name" id="dash-fullname">Budi Santoso</div>
                </div>
            </div>
            <button class="btn-close-dash" onclick="closeDashboard()">✕</button>
        </div>

        <div class="dash-body">
            <div class="dash-status-card">
                <div class="status-icon">📋</div>
                <div class="status-info">
                    <div class="status-label">Status Pendaftaran</div>
                    <div class="status-value">PPDB SMA Muhammadiyah 1 Purwokerto – T.A. 2025/2026</div>
                </div>
                <span class="status-badge pending">Belum Lengkap</span>
            </div>

            <div class="dash-grid">
                <div class="dash-card">
                    <div class="dash-card-title">📅 Waktu Tersisa</div>
                    <div class="dash-card-value" id="dash-countdown">–</div>
                    <div class="dash-card-sub">Hingga penutupan pendaftaran</div>
                </div>
                <div class="dash-card">
                    <div class="dash-card-title">📁 Berkas Terunggah</div>
                    <div class="dash-card-value">0 / 6</div>
                    <div class="dash-card-sub">Lengkapi segera untuk seleksi</div>
                </div>
            </div>

            <div class="dash-timeline">
                <div class="dash-timeline-title">📌 Progress Tahapan PPDB</div>
                <div class="dash-tl">
                    <div class="dash-tl-item">
                        <div class="dash-tl-dot done"></div>
                        <div class="dash-tl-text">Pembuatan akun pendaftaran</div>
                        <div class="dash-tl-date">✔ Selesai</div>
                    </div>
                    <div class="dash-tl-item">
                        <div class="dash-tl-dot active"></div>
                        <div class="dash-tl-text active-step">Pengisian formulir & upload berkas</div>
                        <div class="dash-tl-date">Aktif sekarang</div>
                    </div>
                    <div class="dash-tl-item">
                        <div class="dash-tl-dot upcoming"></div>
                        <div class="dash-tl-text">Tes seleksi & wawancara</div>
                        <div class="dash-tl-date">1–10 Jul 2025</div>
                    </div>
                    <div class="dash-tl-item">
                        <div class="dash-tl-dot upcoming"></div>
                        <div class="dash-tl-text">Pengumuman hasil seleksi</div>
                        <div class="dash-tl-date">20 Jul 2025</div>
                    </div>
                    <div class="dash-tl-item">
                        <div class="dash-tl-dot upcoming"></div>
                        <div class="dash-tl-text">Daftar ulang & orientasi siswa baru</div>
                        <div class="dash-tl-date">21–30 Jul 2025</div>
                    </div>
                </div>
            </div>

            <div class="dash-actions">
                <button class="btn-dash-action btn-dash-primary" onclick="closeDashboard()">
                    📝 Lengkapi Formulir
                </button>
                <button class="btn-dash-action btn-dash-outline" onclick="closeDashboard()">
                    📂 Upload Berkas
                </button>
                <button class="btn-dash-action btn-dash-outline" onclick="closeDashboard()">
                    📞 Hubungi Panitia
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════ -->
<!-- HERO -->
<!-- ══════════════════════════════════════════ -->
<section id="hero">
    <div class="hero-bg"></div>
    <div class="hero-pattern"></div>
    <div class="hero-content">
        <div class="hero-badge">PPDB Tahun Ajaran 2025/2026</div>
        <h1 class="hero-title">
            Wujudkan Mimpi <em>Bersama Kami</em>
        </h1>
        <p class="hero-sub">
            SMA Muhammadiyah 1 Purwokerto membuka penerimaan murid baru untuk tahun ajaran 2025/2026.
            Bergabunglah dengan ribuan alumni berprestasi yang telah mengukir jejak di tingkat nasional dan internasional.
        </p>
        <div class="hero-actions">
            <a href="#ppdb" class="btn-primary">Info Pendaftaran</a>
            <a href="#visi-misi" class="btn-outline">Kenali Sekolah Kami</a>
        </div>
    </div>
    <div class="hero-stats">
        <div class="stat-card">
            <span class="stat-num">3.200+</span>
            <span class="stat-label">Alumni Berprestasi</span>
        </div>
        <div class="stat-card">
            <span class="stat-num">98%</span>
            <span class="stat-label">Lulus PTN</span>
        </div>
        <div class="stat-card">
            <span class="stat-num">40+</span>
            <span class="stat-label">Ekstrakurikuler</span>
        </div>
    </div>
</section>

{{-- <!-- COUNTDOWN -->
<div id="countdown">
    <div class="countdown-label">Pendaftaran ditutup pada <span>30 Juli 2025</span></div>
    <div class="countdown-timer">
        <div class="time-box"><span class="time-num" id="cd-days">47</span><span class="time-unit">Hari</span></div>
        <div class="time-box"><span class="time-num" id="cd-hours">12</span><span class="time-unit">Jam</span></div>
        <div class="time-box"><span class="time-num" id="cd-mins">35</span><span class="time-unit">Menit</span></div>
        <div class="time-box"><span class="time-num" id="cd-secs">08</span><span class="time-unit">Detik</span></div>
    </div>
    <div class="countdown-cta">
        
    </div>
</div> --}}

<!-- VISI MISI -->
<section id="visi-misi">
    <div class="vm-block visi reveal">
        
        <div class="section-tag" style="color:var(--gold2)">Visi Sekolah</div>
        <div class="divider"></div>
        <p class="vm-quote">
            " Terbentuknya Pribadi Islami yang Unggul Dalam IMTAQ, Berkemajuan dan Memiliki Life Skill"
        </p>
    </div>
    <div class="vm-block misi reveal">
        
        <div class="section-tag">Misi Sekolah</div>
        <div class="divider"></div>
        <ul class="misi-list">
            <li>Meningkatkan Iman dan Takwa kepada siswa SMA Muhammadiyah 1 Purwokerto.</li>
            <li>Meningkatan kepedulian terhadap misi da’wah persyarikatan dengan mendorong, memberdayakan warga sekolah untuk berperan aktif dalam kegiatan persyarikatan baik secara personal maupun lembagaMembeikan pembekalan kepada siswa untuk hafal Al Qur’an Minimal juz 30.</li>
            <li>Melaksanakan kegiatan belajar mengajar yang kindusif, efektif dan efisien untuk meningkatkan perolehan Nilai UN dan US.</li>
            <li>Meningkatkan daya saing siswauntuk dapat masuk PTN dan PTS Favorit serta berprestasi di bidang Akademi maupun Non Akademi.</li>
            <li>Memberikan pembekalan kepada siswa dalam bidang Life Skill.</li>
            <li>Mempersiapkan siswa menjadi kader umat Islam, Bangsa dan Persyarikatan.</li>
        </ul>
    </div>
</section>

<!-- FASILITAS -->
<section id="fasilitas">
    <div class="fasilitas-header reveal">
        <div class="section-tag">Fasilitas Kami</div>
        <h2 class="section-title">Lingkungan Belajar<br>Berkelas Dunia</h2>
        <div class="divider"></div>
        <p class="section-sub">Kami menyediakan fasilitas modern dan lengkap untuk mendukung proses belajar-mengajar yang optimal serta pengembangan bakat dan minat siswa.</p>
    </div>
    <div class="fasilitas-grid">
        <div class="fasilitas-card reveal"><span class="fas-icon">🔬</span><div class="fas-name">Lab Sains Terintegrasi</div><p class="fas-desc">Laboratorium Fisika, Kimia, dan Biologi berteknologi mutakhir dengan peralatan sesuai standar internasional.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">💻</span><div class="fas-name">Lab Komputer & AI</div><p class="fas-desc">200+ unit komputer terbaru dengan koneksi internet fiber optik 1 Gbps dan perangkat lunak berlisensi penuh.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">📚</span><div class="fas-name">Perpustakaan Digital</div><p class="fas-desc">Koleksi lebih dari 15.000 judul buku fisik dan akses ke ribuan jurnal internasional secara daring.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">🏟️</span><div class="fas-name">Lapangan & Stadion</div><p class="fas-desc">Lapangan futsal, basket, voli, dan lintasan atletik berstandar nasional di lingkungan sekolah.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">🎭</span><div class="fas-name">Aula & Teater</div><p class="fas-desc">Ruang pertunjukan berkapasitas 500 orang dengan sistem tata suara dan pencahayaan profesional.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">🍽️</span><div class="fas-name">Kantin Sehat</div><p class="fas-desc">Kantin higienis dengan menu bergizi dan terjangkau, dikelola secara profesional oleh mitra bersertifikat.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">🏥</span><div class="fas-name">Klinik Sekolah</div><p class="fas-desc">Unit kesehatan sekolah dengan tenaga medis berpengalaman, siap melayani kebutuhan kesehatan siswa setiap hari.</p></div>
        <div class="fasilitas-card reveal"><span class="fas-icon">🚌</span><div class="fas-name">Layanan Antar-Jemput</div><p class="fas-desc">Armada bus sekolah ber-AC yang melayani berbagai rute di dalam kota dengan sistem GPS real-time.</p></div>
    </div>
</section>

<!-- PPDB INFO -->
<section id="ppdb">
    <div class="ppdb-inner">
        <div class="ppdb-header reveal">
            <div class="section-tag" style="color:var(--gold2)">Penerimaan Murid Baru</div>
            <h2 class="section-title light">Informasi PPDB<br>2025/2026</h2>
            <div class="divider"></div>
            <p class="section-sub light">Ikuti alur pendaftaran berikut untuk bergabung bersama keluarga besar SMA Muhammadiyah 1 Purwokerto. Proses seleksi transparan dan berkeadilan.</p>
        </div>
        <div class="ppdb-grid">
            <div class="reveal">
                <h3 style="font-family:'Playfair Display',serif;color:var(--white);font-size:1.25rem;margin-bottom:32px;">Jadwal Pelaksanaan</h3>
                <div class="ppdb-timeline">
                    <div class="tl-item"><div class="tl-dot">1</div><div class="tl-body"><div class="tl-date">1 – 15 Juni 2025</div><div class="tl-title">Pembukaan Pendaftaran Online</div><div class="tl-desc">Registrasi akun dan pengisian formulir pendaftaran melalui portal resmi PPDB.</div></div></div>
                    <div class="tl-item"><div class="tl-dot">2</div><div class="tl-body"><div class="tl-date">16 – 25 Juni 2025</div><div class="tl-title">Pengumpulan Berkas</div><div class="tl-desc">Unggah dokumen persyaratan yang diperlukan melalui sistem pendaftaran online.</div></div></div>
                    <div class="tl-item"><div class="tl-dot">3</div><div class="tl-body"><div class="tl-date">1 – 10 Juli 2025</div><div class="tl-title">Tes Seleksi & Wawancara</div><div class="tl-desc">Tes tertulis (Matematika, IPA, IPS, B.Indonesia) dan sesi wawancara dengan panitia.</div></div></div>
                    <div class="tl-item"><div class="tl-dot">4</div><div class="tl-body"><div class="tl-date">20 Juli 2025</div><div class="tl-title">Pengumuman Hasil Seleksi</div><div class="tl-desc">Hasil seleksi diumumkan melalui portal resmi dan akan dikirim via email terdaftar.</div></div></div>
                    <div class="tl-item"><div class="tl-dot">5</div><div class="tl-body"><div class="tl-date">21 – 30 Juli 2025</div><div class="tl-title">Daftar Ulang & Orientasi</div><div class="tl-desc">Konfirmasi kehadiran, pembayaran, dan persiapan masa orientasi siswa baru.</div></div></div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:20px;" class="reveal">
                <div class="info-card">
                    <div class="info-card-title">📋 Persyaratan Dokumen</div>
                    <ul class="req-list">
                        <li>Fotokopi Ijazah / Surat Keterangan Lulus SMP</li>
                        <li>Fotokopi SKHUN (Surat Keterangan Hasil Ujian Nasional)</li>
                        <li>Fotokopi Kartu Keluarga (KK)</li>
                        <li>Fotokopi Akta Kelahiran</li>
                        <li>Pas foto berwarna 3×4 (4 lembar)</li>
                        <li>Sertifikat prestasi akademik/non-akademik (jika ada)</li>
                    </ul>
                </div>
                <div class="info-card">
                    <div class="info-card-title">💰 Biaya Pendidikan</div>
                    <table class="biaya-table">
                        <tr><td>Biaya Pendaftaran</td><td>Rp 150.000</td></tr>
                        <tr><td>Uang Pangkal</td><td>Rp 5.000.000</td></tr>
                        <tr><td>SPP per Bulan</td><td>Rp 750.000</td></tr>
                        <tr><td>Seragam & Atribut</td><td>Rp 800.000</td></tr>
                        <tr><td>Kegiatan MPLS</td><td>Rp 300.000</td></tr>
                    </table>
                    <p style="font-size:.78rem;color:rgba(255,255,255,.4);margin-top:12px;">*Tersedia beasiswa bagi siswa berprestasi dan kurang mampu</p>
                </div>
                <div class="info-card">
                    <div class="info-card-title">📞 Kontak Panitia PPDB</div>
                    <div class="info-card-body">
                        📱  (0281) 633373<br>
                        🕐 Senin–Sabtu, 08.00–12.30 WIB
                    </div>
                </div>
            </div>
        </div>
        <div class="ppdb-actions reveal">
            <a href="/register" class="btn-primary">Daftar Online Sekarang</a>
        </div>
    </div>
</section>

<!-- GALLERY -->
<section id="galeri">
    <div class="gallery-header reveal">
        <div class="section-tag">Galeri Sekolah</div>
        <h2 class="section-title">Momen Berharga di<br>Nusantara Jaya</h2>
        <div class="divider"></div>
    </div>
    <div class="gallery-grid reveal">
        <div class="gallery-item"><img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80" alt="Upacara"><div class="gallery-overlay"><span class="gallery-caption">Upacara Hari Kemerdekaan</span></div></div>
        <div class="gallery-item"><img src="https://images.unsplash.com/photo-1564981797816-1043664bf78d?w=600&q=80" alt="Laboratorium"><div class="gallery-overlay"><span class="gallery-caption">Praktikum Sains</span></div></div>
        <div class="gallery-item"><img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=600&q=80" alt="Perpustakaan"><div class="gallery-overlay"><span class="gallery-caption">Ruang Perpustakaan</span></div></div>
        <div class="gallery-item"><img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=800&q=80" alt="Olahraga"><div class="gallery-overlay"><span class="gallery-caption">Kejuaraan Basket Pelajar</span></div></div>
        <div class="gallery-item"><img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80" alt="Kegiatan"><div class="gallery-overlay"><span class="gallery-caption">Kegiatan OSIS</span></div></div>
    </div>
</section>

<!-- MAP / LOKASI -->
<section id="lokasi">
    <div class="reveal" style="margin-bottom:48px;">
        <div class="section-tag">Lokasi Kami</div>
        <h2 class="section-title">Temukan Kami di<br>Pusat Kota</h2>
        <div class="divider"></div>
    </div>
    <div class="map-container">
        <div class="map-info-cards reveal">
            <div class="loc-card"><div class="loc-card-label">📍 Alamat</div><div class="loc-card-value">Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115</div></div>
            <div class="loc-card"><div class="loc-card-label">🕐 Jam Operasional</div><div class="loc-card-value">Senin – Jumat: 07.30 – 16.00 WIB<br>Sabtu: 07.30 – 12.30 WIB</div></div>
            <div class="loc-card"><div class="loc-card-label">📞 Telepon</div><div class="loc-card-value">(0281) 633373</div></div>
            <a href="https://maps.google.com" target="_blank" class="btn-primary" style="text-align:center;margin-top:8px;">Buka di Google Maps</a>
        </div>
        <div class="map-embed reveal">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.443536014362!2d109.2288820747998!3d-7.416064673040172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e572a33a7bf%3A0x60396314bb780a46!2sSMA%20Muhammadiyah%201%20Purwokerto%20(SMAMSA%20Purwokerto)!5e0!3m2!1sid!2sid!4v1772252049348!5m2!1sid!2sid" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <div class="nav-brand"><div class="emblem">N</div><span>SMA Muhammadiyah 1 Purwokerto</span></div>
            <p class="footer-tagline">Mendidik generasi penerus bangsa yang cerdas, berkarakter, dan siap menghadapi tantangan global dengan pondasi nilai Pancasila yang kuat.</p>
        </div>
        <div class="footer-col"><h4>Navigasi</h4><ul><li><a href="#visi-misi">Visi & Misi</a></li><li><a href="#fasilitas">Fasilitas</a></li><li><a href="#ppdb">Info PPDB</a></li><li><a href="#galeri">Galeri</a></li><li><a href="#lokasi">Lokasi</a></li></ul></div>
        <div class="footer-col"><h4>PPDB</h4><ul><li><a href="/register">Pendaftaran Online</a></li><li><a href="#">Persyaratan</a></li><li><a href="#">Jadwal Seleksi</a></li><li><a href="#">Biaya Pendidikan</a></li></ul></div>
        <div class="footer-col"><h4>Sosial Media</h4><ul><li><a href="#">Instagram</a></li><li><a href="#">Facebook</a></li><li><a href="#">YouTube</a></li><li><a href="#">Twitter/X</a></li><li><a href="#">TikTok</a></li></ul></div>
    </div>
    <div class="footer-bottom">
        <p class="footer-copy">© 2025 SMA Muhammadiyah 1 Purwokerto. Hak cipta dilindungi undang-undang.</p>
        <p class="footer-copy">Dirancang dengan ❤ untuk generasi Indonesia</p>
    </div>
</footer>


<script>
/* ══════════════════════════════════════════════════
   STATE
══════════════════════════════════════════════════ */
let currentUser = null; // { name, email, initials }

// Simple mock "database" stored in memory
const users = [
    { email: 'demo@ppdb.id', password: 'demo123', name: 'Demo Siswa' }
];

/* ══════════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════════ */
function openModal(type) {
    clearForms();
    document.getElementById('modal-' + type).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(type) {
    document.getElementById('modal-' + type).classList.remove('active');
    document.body.style.overflow = '';
}
function switchModal(from, to) {
    closeModal(from);
    setTimeout(() => openModal(to), 180);
}

// Close on backdrop click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

/* ══════════════════════════════════════════════════
   LOGIN
══════════════════════════════════════════════════ */
function doLogin() {
    const email = document.getElementById('login-email').value.trim();
    const pass  = document.getElementById('login-password').value;

    let valid = true;

    // Validate
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('err-login-email', true); valid = false;
    } else { showErr('err-login-email', false); }

    if (!pass || pass.length < 6) {
        showErr('err-login-pass', true); valid = false;
    } else { showErr('err-login-pass', false); }

    if (!valid) return;

    // Check credentials
    const user = users.find(u => u.email === email && u.password === pass);
    if (!user) {
        document.getElementById('login-error').classList.add('show');
        return;
    }

    document.getElementById('login-error').classList.remove('show');
    document.getElementById('login-success').classList.add('show');

    setTimeout(() => {
        closeModal('login');
        setLoggedIn({ name: user.name, email: user.email });
    }, 900);
}

function demoLogin() {
    setLoggedIn({ name: 'Demo Siswa', email: 'demo@ppdb.id' });
    closeModal('login');
}

/* ══════════════════════════════════════════════════
   REGISTER
══════════════════════════════════════════════════ */
function doRegister() {
    const fname   = document.getElementById('reg-firstname').value.trim();
    const lname   = document.getElementById('reg-lastname').value.trim();
    const email   = document.getElementById('reg-email').value.trim();
    const phone   = document.getElementById('reg-phone').value.trim();
    const pass    = document.getElementById('reg-password').value;
    const confirm = document.getElementById('reg-confirm').value;

    let valid = true;

    if (!fname) { showErr('err-reg-fname', true); valid = false; } else showErr('err-reg-fname', false);
    if (!lname) { showErr('err-reg-lname', true); valid = false; } else showErr('err-reg-lname', false);
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showErr('err-reg-email', true); valid = false; } else showErr('err-reg-email', false);
    if (!phone || phone.length < 10) { showErr('err-reg-phone', true); valid = false; } else showErr('err-reg-phone', false);
    if (!pass || pass.length < 6) { showErr('err-reg-pass', true); valid = false; } else showErr('err-reg-pass', false);
    if (!confirm || confirm !== pass) { showErr('err-reg-confirm', true); valid = false; } else showErr('err-reg-confirm', false);

    if (!valid) return;

    // Check if email already exists
    if (users.find(u => u.email === email)) {
        document.getElementById('register-error').textContent = 'Email sudah terdaftar.';
        document.getElementById('register-error').classList.add('show');
        return;
    }

    // Register
    const fullName = fname + ' ' + lname;
    users.push({ email, password: pass, name: fullName });

    document.getElementById('register-error').classList.remove('show');
    document.getElementById('register-success').classList.add('show');

    setTimeout(() => {
        switchModal('register', 'login');
        // Pre-fill email in login
        document.getElementById('login-email').value = email;
    }, 1200);
}

/* ══════════════════════════════════════════════════
   AUTH STATE
══════════════════════════════════════════════════ */
function setLoggedIn(user) {
    currentUser = user;
    const initials = user.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();

    // Navbar
    document.getElementById('nav-auth-buttons').style.display = 'none';
    const userInfo = document.getElementById('nav-user-info');
    userInfo.style.display = 'flex';
    document.getElementById('btn-dashboard-nav').style.display = 'flex';
    document.getElementById('nav-avatar').textContent = initials;
    document.getElementById('nav-username').textContent = user.name.split(' ')[0];

    // Dashboard data
    document.getElementById('dash-avatar').textContent = initials;
    document.getElementById('dash-fullname').textContent = user.name;
}

function doLogout() {
    currentUser = null;
    document.getElementById('nav-auth-buttons').style.display = 'flex';
    const userInfo = document.getElementById('nav-user-info');
    userInfo.style.display = 'none';
    document.getElementById('btn-dashboard-nav').style.display = 'none';
}

/* ══════════════════════════════════════════════════
   DASHBOARD
══════════════════════════════════════════════════ */
function openDashboard() {
    if (!currentUser) { openModal('login'); return; }
    document.getElementById('dashboard-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    updateDashCountdown();
}
function closeDashboard() {
    document.getElementById('dashboard-overlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('dashboard-overlay').addEventListener('click', e => {
    if (e.target === document.getElementById('dashboard-overlay')) closeDashboard();
});

function updateDashCountdown() {
    const deadline = new Date('2025-07-30T23:59:59');
    const diff = deadline - new Date();
    if (diff <= 0) { document.getElementById('dash-countdown').textContent = 'Ditutup'; return; }
    const d = Math.floor(diff / 86400000);
    const h = Math.floor((diff % 86400000) / 3600000);
    document.getElementById('dash-countdown').textContent = d + 'h ' + h + 'j lagi';
}

/* ══════════════════════════════════════════════════
   HELPERS
══════════════════════════════════════════════════ */
function showErr(id, show) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('show', show);
    const input = el.previousElementSibling;
    if (input && input.classList.contains('form-control')) {
        input.classList.toggle('error', show);
    }
}
function clearForms() {
    document.querySelectorAll('.form-control').forEach(el => { el.value = ''; el.classList.remove('error'); });
    document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
    document.querySelectorAll('.alert-success, .alert-error').forEach(el => el.classList.remove('show'));
}

/* ══════════════════════════════════════════════════
   COUNTDOWN TIMER
══════════════════════════════════════════════════ */
const deadline = new Date('2025-07-30T23:59:59');
function updateCountdown() {
    const diff = deadline - new Date();
    if (diff <= 0) return;
    const d = Math.floor(diff / 86400000);
    const h = Math.floor((diff % 86400000) / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    document.getElementById('cd-days').textContent  = String(d).padStart(2,'0');
    document.getElementById('cd-hours').textContent = String(h).padStart(2,'0');
    document.getElementById('cd-mins').textContent  = String(m).padStart(2,'0');
    document.getElementById('cd-secs').textContent  = String(s).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

/* ══════════════════════════════════════════════════
   SCROLL REVEAL
══════════════════════════════════════════════════ */
const revealEls = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
    });
}, { threshold: 0.12 });
revealEls.forEach(el => observer.observe(el));

/* ══════════════════════════════════════════════════
   ESC KEY
══════════════════════════════════════════════════ */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        closeDashboard();
        document.body.style.overflow = '';
    }
});
</script>
</body>
</html>