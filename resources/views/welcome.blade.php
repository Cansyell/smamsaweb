<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPDB 2025/2026 – SMA Muhammadiyah 1 Purwokerto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* — Brand from logo — */
            --blue:      #1a3a6b;   /* deep navy blue from logo */
            --blue-mid:  #234f96;   /* medium blue */
            --blue-light:#e8eef8;   /* pale blue tint */
            --gold:      #c8942a;   /* gold from logo trophy */
            --gold-lt:   #f0c95a;   /* lighter gold */
            --gold-pale: #fdf6e3;   /* very pale gold wash */

            /* — Neutrals — */
            --ink:       #111827;
            --ink-2:     #374151;
            --ink-3:     #6b7280;
            --ink-4:     #9ca3af;
            --canvas:    #ffffff;
            --bg:        #f8f9fc;   /* very light blue-white */
            --surface:   #f1f4fb;   /* slightly bluer surface */
            --rule:      #e2e8f0;

            /* — Semantic — */
            --green:     #166534;
            --red:       #991b1b;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        /* ── UTILITIES ───────────────────── */
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: .7rem; font-weight: 700;
            letter-spacing: .16em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 1rem;
        }
        .eyebrow::before {
            content: '';
            display: block; width: 20px; height: 2px;
            background: var(--gold);
            border-radius: 2px;
        }
        .eyebrow.light { color: var(--gold-lt); }
        .eyebrow.light::before { background: var(--gold-lt); }

        h2.section-title {
            font-family: 'Lora', serif;
            font-size: clamp(1.9rem, 3.5vw, 2.8rem);
            font-weight: 700; line-height: 1.12;
            letter-spacing: -.02em; color: var(--ink);
        }
        h2.section-title.light { color: #fff; }
        p.section-lead {
            font-size: 1rem; line-height: 1.8;
            color: var(--ink-3); max-width: 520px;
            margin-top: .75rem;
        }
        p.section-lead.light { color: rgba(255,255,255,.65); }

        /* ── NAVBAR ──────────────────────── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 900;
            height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 max(4vw, 20px);
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--rule);
            box-shadow: 0 1px 8px rgba(26,58,107,.05);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        /* Logo: no circle/border, just the image */
        .nav-logo {
            width: 40px; height: 40px;
            object-fit: contain;
            display: block;
        }
        .nav-brand-text {
            display: flex; flex-direction: column;
            gap: 0;
        }
        .nav-brand-name {
            font-family: 'Lora', serif;
            font-size: .88rem; font-weight: 700;
            color: var(--blue); line-height: 1.2;
        }
        .nav-brand-sub {
            font-size: .65rem; font-weight: 500;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--ink-4); line-height: 1;
        }

        .nav-links {
            display: flex; gap: 6px; list-style: none;
            margin: 0 auto;
            padding: 0 24px;
        }
        .nav-links a {
            display: block;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: .8rem; font-weight: 600;
            letter-spacing: .04em; text-transform: uppercase;
            color: var(--ink-3); text-decoration: none;
            transition: color .15s, background .15s;
        }
        .nav-links a:hover { color: var(--blue); background: var(--blue-light); }

        .nav-auth { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
        .btn-login {
            padding: 8px 20px;
            border: 1.5px solid var(--rule);
            border-radius: 8px;
            font-size: .82rem; font-weight: 600;
            color: var(--ink-2); background: transparent;
            cursor: pointer; font-family: inherit;
            text-decoration: none;
            transition: border-color .15s, background .15s;
            display: inline-flex; align-items: center;
        }
        .btn-login:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
        .btn-daftar {
            padding: 8px 22px;
            border: 1.5px solid var(--blue);
            border-radius: 8px;
            font-size: .82rem; font-weight: 700;
            color: #fff; background: var(--blue);
            cursor: pointer; font-family: inherit;
            text-decoration: none;
            transition: background .15s, opacity .15s;
            display: inline-flex; align-items: center;
        }
        .btn-daftar:hover { background: var(--blue-mid); border-color: var(--blue-mid); }

        /* user state */
        .nav-user { display: none; align-items: center; gap: 10px; }
        .nav-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--blue-light); border: 2px solid var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem; font-weight: 700; color: var(--blue);
        }
        .nav-uname { font-size: .82rem; font-weight: 600; color: var(--ink-2); }
        .btn-dash {
            padding: 7px 16px; border-radius: 8px;
            background: var(--blue); color: #fff;
            font-size: .78rem; font-weight: 700;
            border: none; cursor: pointer; font-family: inherit;
            display: none; align-items: center; gap: 5px;
            transition: opacity .15s;
        }
        .btn-dash:hover { opacity: .85; }
        .btn-logout {
            padding: 6px 12px; border-radius: 6px;
            border: 1px solid var(--rule);
            font-size: .75rem; font-weight: 500;
            color: var(--ink-4); background: transparent;
            cursor: pointer; font-family: inherit;
            transition: all .15s;
        }
        .btn-logout:hover { border-color: var(--red); color: var(--red); }

        .hamburger {
            display: none;
            width: 42px; height: 42px;
            border: 1px solid var(--rule);
            border-radius: 10px;
            background: var(--canvas);
            align-items: center; justify-content: center;
            flex-direction: column; gap: 5px;
            cursor: pointer;
            flex-shrink: 0;
            transition: border-color .15s, background .15s;
        }
        .hamburger:hover { border-color: var(--blue); background: var(--blue-light); }
        .hamburger span {
            display: block;
            width: 20px; height: 2px;
            border-radius: 999px;
            background: var(--ink);
            transform-origin: center;
            transition: transform .25s, opacity .2s;
        }
        .hamburger.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu { display: none; }


        /* ── MODALS ──────────────────────── */
        .moverlay {
            position: fixed; inset: 0; z-index: 1200;
            background: rgba(17,24,39,.5);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .moverlay.open { opacity: 1; pointer-events: all; }
        .mbox {
            background: var(--canvas);
            border: 1px solid var(--rule);
            border-radius: 16px;
            width: 100%; max-width: 420px;
            margin: 16px;
            padding: 36px;
            position: relative;
            box-shadow: 0 20px 60px rgba(26,58,107,.15);
            transform: translateY(16px);
            transition: transform .3s cubic-bezier(.34,1.56,.64,1);
        }
        .moverlay.open .mbox { transform: translateY(0); }
        .mclose {
            position: absolute; top: 14px; right: 14px;
            width: 30px; height: 30px; border-radius: 8px;
            border: 1px solid var(--rule); background: transparent;
            cursor: pointer; color: var(--ink-3);
            font-size: .85rem; display: grid; place-items: center;
            transition: background .15s;
        }
        .mclose:hover { background: var(--surface); }
        .mbox h2 {
            font-family: 'Lora', serif;
            font-size: 1.75rem; font-weight: 700; color: var(--ink);
            margin-bottom: 4px;
        }
        .msub { font-size: .82rem; color: var(--ink-4); margin-bottom: 22px; }
        .msub a { color: var(--blue); cursor: pointer; text-decoration: none; font-weight: 600; }
        .msub a:hover { text-decoration: underline; }

        .fg { margin-bottom: 14px; }
        .fg label {
            display: block; font-size: .72rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--ink-3); margin-bottom: 6px;
        }
        .fc {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--rule);
            border-radius: 8px;
            font-size: .9rem; font-family: inherit;
            color: var(--ink); background: var(--canvas);
            transition: border-color .15s;
            outline: none;
        }
        .fc:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,58,107,.08); }
        .fc.err { border-color: #dc2626; }
        .fc::placeholder { color: var(--ink-4); }
        .fgrow { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .ferr { font-size: .72rem; color: #dc2626; margin-top: 4px; display: none; }
        .ferr.show { display: block; }

        .malert {
            padding: 10px 14px; border-radius: 8px;
            font-size: .82rem; margin-bottom: 14px; display: none;
        }
        .malert.show { display: block; }
        .malert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .malert-no { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        .btn-submit {
            width: 100%; padding: 12px;
            background: var(--blue); color: #fff;
            font-size: .9rem; font-weight: 700; font-family: inherit;
            border: none; border-radius: 8px; cursor: pointer;
            margin-top: 4px;
            transition: opacity .15s, background .15s;
        }
        .btn-submit:hover { background: var(--blue-mid); }
        .btn-demo {
            width: 100%; padding: 11px;
            background: var(--bg); color: var(--ink-2);
            font-size: .85rem; font-weight: 500; font-family: inherit;
            border: 1.5px solid var(--rule); border-radius: 8px;
            cursor: pointer; margin-top: 8px;
            transition: background .15s;
        }
        .btn-demo:hover { background: var(--surface); }
        .msep {
            display: flex; align-items: center; gap: 10px;
            margin: 14px 0;
        }
        .msep::before, .msep::after {
            content: ''; flex: 1; height: 1px; background: var(--rule);
        }
        .msep span { font-size: .72rem; color: var(--ink-4); }

        /* ── DASHBOARD ───────────────────── */
        .doverlay {
            position: fixed; inset: 0; z-index: 1100;
            background: rgba(17,24,39,.45);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .doverlay.open { opacity: 1; pointer-events: all; }
        .dpanel {
            background: var(--canvas);
            border: 1px solid var(--rule);
            border-radius: 16px;
            width: 100%; max-width: 700px;
            margin: 16px;
            max-height: 88vh; overflow-y: auto;
            box-shadow: 0 24px 60px rgba(26,58,107,.15);
            transform: translateY(20px);
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
        }
        .doverlay.open .dpanel { transform: translateY(0); }
        .dpanel::-webkit-scrollbar { width: 4px; }
        .dpanel::-webkit-scrollbar-thumb { background: var(--rule); border-radius: 2px; }

        .dhead {
            padding: 28px 32px 24px;
            border-bottom: 1px solid var(--rule);
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-mid) 100%);
            border-radius: 16px 16px 0 0;
        }
        .duser { display: flex; align-items: center; gap: 14px; }
        .dav {
            width: 48px; height: 48px; border-radius: 50%;
            background: rgba(255,255,255,.2);
            border: 2px solid rgba(255,255,255,.4);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; color: #fff;
        }
        .dgreeting { font-size: .72rem; color: rgba(255,255,255,.6); margin-bottom: 2px; }
        .dname { font-family: 'Lora', serif; font-size: 1.1rem; font-weight: 700; color: #fff; }
        .dcloser {
            width: 32px; height: 32px; border-radius: 8px;
            border: 1px solid rgba(255,255,255,.25);
            background: rgba(255,255,255,.1);
            cursor: pointer; color: rgba(255,255,255,.7);
            font-size: .85rem; display: grid; place-items: center;
            transition: background .15s;
        }
        .dcloser:hover { background: rgba(255,255,255,.2); }

        .dbody { padding: 28px 32px 32px; }
        .dstatus {
            background: var(--gold-pale);
            border: 1px solid #e8c97a;
            border-radius: 10px; padding: 14px 18px;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px;
        }
        .dstatus-icon { font-size: 1.4rem; }
        .dstatus-lbl { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--gold); }
        .dstatus-val { font-size: .875rem; font-weight: 500; color: var(--ink-2); margin-top: 2px; }
        .badge-pending {
            margin-left: auto;
            padding: 4px 10px; border-radius: 6px;
            background: #fef9c3; border: 1px solid #fde047;
            font-size: .68rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase; color: #854d0e;
        }

        .dgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
        .dcard {
            background: var(--bg);
            border: 1px solid var(--rule);
            border-radius: 10px; padding: 16px;
        }
        .dcard-lbl { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ink-4); margin-bottom: 6px; }
        .dcard-val { font-family: 'Lora', serif; font-size: 1.8rem; font-weight: 700; color: var(--blue); line-height: 1; }
        .dcard-sub { font-size: .72rem; color: var(--ink-4); margin-top: 4px; }

        .dtl { margin-bottom: 20px; }
        .dtl-head { font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--ink-4); margin-bottom: 12px; }
        .trow { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--rule); }
        .trow:last-child { border-bottom: none; }
        .tdot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .tdot.done { background: var(--gold); }
        .tdot.now { background: var(--blue); box-shadow: 0 0 0 3px rgba(26,58,107,.15); }
        .tdot.later { background: var(--rule); }
        .ttext { font-size: .85rem; color: var(--ink-3); flex: 1; }
        .ttext.now { color: var(--ink); font-weight: 600; }
        .tdate { font-size: .72rem; color: var(--ink-4); white-space: nowrap; }

        .dacts { display: flex; gap: 8px; flex-wrap: wrap; }
        .dbtn {
            padding: 8px 18px; border-radius: 8px;
            font-size: .8rem; font-weight: 700; font-family: inherit;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all .15s;
        }
        .dbtn-p { background: var(--blue); color: #fff; border: 1.5px solid var(--blue); }
        .dbtn-p:hover { background: var(--blue-mid); }
        .dbtn-g { background: transparent; color: var(--ink-2); border: 1.5px solid var(--rule); }
        .dbtn-g:hover { background: var(--surface); border-color: var(--ink-3); }

        /* ── HERO ────────────────────────── */
        #hero {
            min-height: 100vh;
            padding-top: 68px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .hero-left {
            display: flex; flex-direction: column; justify-content: center;
            padding: 80px max(4vw, 32px) 80px max(6vw, 48px);
            background: var(--canvas);
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--blue-light);
            border: 1px solid rgba(152, 174, 207, 0.15);
            border-radius: 6px;
            padding: 5px 12px;
            font-size: .7rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--blue); margin-bottom: 24px;
            width: fit-content;
        }
        .hero-badge::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
            background: var(--blue); flex-shrink: 0;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }

        h1.hero-title {
            font-family: 'Lora', serif;
            font-size: clamp(2.6rem, 4.5vw, 4rem);
            font-weight: 700;
            line-height: 1.07; letter-spacing: -.025em;
            color: var(--ink);
            margin-bottom: 18px;
        }
        h1.hero-title strong { color: var(--blue); font-weight: 700; }
        h1.hero-title em { font-style: italic; color: var(--gold); }

        .hero-desc {
            font-size: .98rem; font-weight: 400;
            line-height: 1.8; color: var(--ink-3);
            max-width: 440px; margin-bottom: 36px;
        }

        .hero-ctas { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 48px; }
        .btn-cta-primary {
            padding: 13px 28px;
            background: var(--blue); color: #fff;
            font-size: .9rem; font-weight: 700;
            border-radius: 8px; text-decoration: none;
            border: 2px solid var(--blue);
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s;
        }
        .btn-cta-primary:hover { background: var(--blue-mid); border-color: var(--blue-mid); }
        .btn-cta-secondary {
            padding: 13px 28px;
            background: transparent; color: var(--ink-2);
            font-size: .9rem; font-weight: 600;
            border-radius: 8px; text-decoration: none;
            border: 2px solid var(--rule);
            transition: border-color .15s, background .15s;
        }
        .btn-cta-secondary:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }

        .hero-stats { display: flex; gap: 0; }
        .hs {
            padding-right: 32px; margin-right: 32px;
            border-right: 1px solid var(--rule);
        }
        .hs:last-child { border-right: none; padding-right: 0; margin-right: 0; }
        .hs-num {
            font-family: 'Lora', serif;
            font-size: 2.2rem; font-weight: 700;
            color: var(--blue); line-height: 1;
            display: block;
        }
        .hs-lbl { font-size: .72rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--ink-4); margin-top: 3px; }

        .hero-right {
            position: relative; overflow: hidden;
            background: #e3e9ef; 
        }

        .hero-img-bg {
            position: absolute; inset: 0;
            background: url('{{ asset('images/Logo-smamsa2.jpeg') }}') center/cover no-repeat;
            opacity: .80;
        }
        .hero-right-content {
            position: relative; z-index: 1;
            height: 100%; display: flex; flex-direction: column; justify-content: flex-end;
            padding: 48px 40px;
        }
        .hero-accent-card {
            background: rgba(15, 35, 71, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px; padding: 24px 28px;
            margin-bottom: 20px;
        }
        .hac-label { font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--gold-lt); margin-bottom: 8px; }
        .hac-text {
            font-family: 'Lora', serif;
            font-size: 1.05rem; font-style: italic;
            color: rgba(255,255,255,.9); line-height: 1.55;
        }
        .hero-year-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--gold);
            border-radius: 8px;
            padding: 10px 20px;
            font-size: .88rem; font-weight: 700;
            color: var(--blue); width: fit-content;
        }
        .hero-dots {
            position: absolute; top: 60px; right: 40px;
            display: grid; grid-template-columns: repeat(6,1fr); gap: 10px;
            opacity: .15;
        }
        .hero-dots span {
            width: 4px; height: 4px; border-radius: 50%;
            background: #fff; display: block;
        }

        /* ── VISI MISI ───────────────────── */
        #visi-misi {
            display: grid; grid-template-columns: 1fr 1fr; padding: 0;
        }
        .vm {
            padding: 80px max(5vw, 40px);
        }
        .vm.visi { background: var(--blue); }
        .vm.misi { background: var(--canvas); border-top: 1px solid var(--rule); }
        .vm.visi .eyebrow { color: var(--gold-lt); }
        .vm.visi .eyebrow::before { background: var(--gold-lt); }
        .vm.visi h2.section-title { color: #fff; }
        .vm-visi-text {
            font-family: 'Lora', serif;
            font-size: 1.25rem; font-style: italic; font-weight: 400;
            color: rgba(255,255,255,.85); line-height: 1.65;
            margin-top: 1rem;
            padding: 20px 0 0 20px;
            border-left: 3px solid var(--gold);
        }
        .vm.misi h2.section-title { color: var(--ink); margin-top: .5rem; }
        .misi-ul { list-style: none; margin-top: 1.5rem; display: flex; flex-direction: column; gap: 14px; }
        .misi-ul li {
            display: flex; gap: 12px; align-items: flex-start;
            font-size: .88rem; line-height: 1.7; color: var(--ink-2);
        }
        .mcheck {
            width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0;
            background: var(--blue-light); border: 1.5px solid rgba(26,58,107,.2);
            display: flex; align-items: center; justify-content: center;
            margin-top: 2px;
        }
        .mcheck svg { width: 10px; height: 10px; color: var(--blue); }

        /* ── FASILITAS ───────────────────── */
        #fasilitas { background: var(--bg); padding: 88px max(4vw, 24px); }
        .fas-head { max-width: 560px; margin-bottom: 52px; }

        .fas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        .fas-card {
            background: var(--canvas);
            border: 1px solid var(--rule);
            border-radius: 14px;
            padding: 28px 24px 24px;
            /* clean shadow — not too heavy */
            box-shadow: 0 2px 8px rgba(26,58,107,.04), 0 8px 24px rgba(26,58,107,.06);
            transition: transform .22s ease, box-shadow .22s ease;
            position: relative; overflow: hidden;
        }
        .fas-card::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--gold));
            opacity: 0;
            transition: opacity .22s;
        }
        .fas-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(26,58,107,.08), 0 20px 48px rgba(26,58,107,.1);
        }
        .fas-card:hover::after { opacity: 1; }

        .fas-icon-bg {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: var(--blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 18px;
            transition: background .22s;
        }
        .fas-card:hover .fas-icon-bg { background: var(--blue); }
        .fas-card:hover .fas-icon-bg .fas-emoji { filter: brightness(100) saturate(0); }

        .fas-title {
            font-size: .95rem; font-weight: 700;
            color: var(--ink); margin-bottom: 8px;
            letter-spacing: -.01em;
        }
        .fas-desc { font-size: .82rem; line-height: 1.65; color: var(--ink-4); }

        /* ── PPDB ────────────────────────── */
        #ppdb {
            background: var(--blue);
            padding: 88px max(4vw, 24px);
            position: relative; overflow: hidden;
        }
        #ppdb::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,.03);
            pointer-events: none;
        }
        #ppdb::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(200,148,42,.06);
            pointer-events: none;
        }
        .ppdb-inner { position: relative; z-index: 1; }
        .ppdb-grid {
            margin-top: 52px;
            display: grid; grid-template-columns: 1.1fr 1fr;
            gap: 56px; align-items: start;
        }

        .jadwal-list { display: flex; flex-direction: column; gap: 0; }
        .jadwal-item {
            display: flex; gap: 18px;
            padding-bottom: 24px;
            position: relative;
        }
        .jadwal-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px; top: 36px; bottom: 0;
            width: 1px; background: rgba(255,255,255,.1);
        }
        .jadwal-num {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(200,148,42,.4);
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 700; color: var(--gold-lt);
        }
        .jadwal-date { font-size: .65rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--gold-lt); opacity: .8; margin-bottom: 3px; }
        .jadwal-title { font-size: .92rem; font-weight: 600; color: rgba(255,255,255,.9); margin-bottom: 3px; }
        .jadwal-desc { font-size: .78rem; color: rgba(255,255,255,.45); line-height: 1.6; }

        .ppdb-side { display: flex; flex-direction: column; gap: 14px; }
        .pcard {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px; padding: 20px 22px;
            transition: background .15s;
        }
        .pcard:hover { background: rgba(255,255,255,.09); }
        .pcard-title {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--gold-lt); margin-bottom: 12px;
            display: flex; align-items: center; gap: 6px;
        }
        .req-list { list-style: none; display: flex; flex-direction: column; gap: 7px; }
        .req-list li { display: flex; gap: 7px; font-size: .83rem; color: rgba(255,255,255,.7); line-height: 1.5; }
        .req-list li::before { content: '✓'; color: var(--gold-lt); font-weight: 700; flex-shrink: 0; font-size: .8rem; }
        .biaya-tbl { width: 100%; border-collapse: collapse; }
        .biaya-tbl td { padding: 6px 0; font-size: .83rem; color: rgba(255,255,255,.7); border-bottom: 1px solid rgba(255,255,255,.05); }
        .biaya-tbl td:last-child { text-align: right; color: var(--gold-lt); font-weight: 700; }
        .pinfo { font-size: .85rem; color: rgba(255,255,255,.65); line-height: 1.7; }
        .pnote { font-size: .7rem; color: rgba(255,255,255,.3); margin-top: 10px; }

        .ppdb-cta { margin-top: 40px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .btn-ppdb {
            padding: 13px 32px;
            background: var(--gold); color: var(--blue);
            font-size: .9rem; font-weight: 800;
            border-radius: 8px; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s;
        }
        .btn-ppdb:hover { background: var(--gold-lt); }
        .btn-ppdb-ghost {
            padding: 13px 28px;
            background: transparent; color: rgba(255,255,255,.7);
            font-size: .88rem; font-weight: 600;
            border-radius: 8px; text-decoration: none;
            border: 1.5px solid rgba(255,255,255,.2);
            transition: border-color .15s, color .15s;
        }
        .btn-ppdb-ghost:hover { border-color: rgba(255,255,255,.5); color: #fff; }

        /* ── GALERI ──────────────────────── */
        #galeri { background: var(--canvas); padding: 88px max(4vw, 24px); }
        .galeri-head { margin-bottom: 44px; }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 200px 200px;
            gap: 10px;
        }
        .gi {
            border-radius: 10px; overflow: hidden;
            position: relative; background: var(--surface);
        }
        .gi:nth-child(1) { grid-column: span 2; grid-row: span 2; }
        .gi:nth-child(4) { grid-column: span 2; }
        .gi img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .45s; }
        .gi:hover img { transform: scale(1.05); }
        .gi iframe { width: 100%; height: 100%; display: block; border: 0; }
        .gi-cap {
            position: absolute; bottom: 0; left: 0; right: 0; padding: 12px 14px;
            background: linear-gradient(to top, rgba(17,24,39,.7) 0%, transparent 100%);
            font-size: .76rem; font-weight: 500; color: #fff;
            opacity: 0; transition: opacity .25s;
        }
        .gi:hover .gi-cap { opacity: 1; }

        /* ── LOKASI ──────────────────────── */
        #lokasi { background: var(--bg); padding: 88px max(4vw, 24px); }
        .lokasi-grid {
            margin-top: 44px;
            display: grid; grid-template-columns: 1fr 1.7fr;
            gap: 44px; align-items: start;
        }
        .lcard {
            background: var(--canvas);
            border: 1px solid var(--rule);
            border-radius: 10px; padding: 16px 20px;
            margin-bottom: 10px;
        }
        .lcard:last-child { margin-bottom: 0; }
        .lcard-lbl {
            font-size: .65rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--blue); margin-bottom: 5px;
        }
        .lcard-val { font-size: .875rem; color: var(--ink-2); line-height: 1.65; }
        .btn-maps {
            display: block; text-align: center;
            padding: 12px; margin-top: 14px;
            background: var(--blue); color: #fff;
            font-size: .85rem; font-weight: 700;
            border-radius: 8px; text-decoration: none;
            transition: background .15s;
        }
        .btn-maps:hover { background: var(--blue-mid); }
        .map-frame {
            border-radius: 14px; overflow: hidden;
            height: 390px;
            border: 1px solid var(--rule);
            box-shadow: 0 4px 20px rgba(26,58,107,.08);
        }
        .map-frame iframe { width: 100%; height: 100%; border: 0; display: block; }

        /* ── FOOTER ──────────────────────── */
        footer {
            background: var(--blue);
            padding: 60px max(4vw, 24px) 32px;
        }
        .footer-top {
            display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 44px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 28px;
        }
        .footer-logo-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .footer-logo { width: 36px; height: 36px; object-fit: contain; }
        .footer-school-name { font-family: 'Lora', serif; font-size: .95rem; font-weight: 700; color: rgba(255,255,255,.85); }
        .footer-tagline { font-size: .8rem; color: rgba(255,255,255,.4); line-height: 1.75; }
        .footer-col h4 {
            font-size: .68rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--gold-lt);
            margin-bottom: 16px; opacity: .8;
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-col ul li a {
            font-size: .82rem; color: rgba(255,255,255,.45);
            text-decoration: none; transition: color .15s;
        }
        .footer-col ul li a:hover { color: rgba(255,255,255,.85); }
        .footer-bottom {
            display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: 8px;
        }
        .footer-copy { font-size: .72rem; color: rgba(255,255,255,.25); }

        /* ── SCROLL REVEAL ───────────────── */
        .reveal { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: none; }

        @media (max-width: 1024px) {
    #hero { grid-template-columns: 1fr; min-height: auto; }
    .hero-right { min-height: 340px; }
    .hero-left { padding: 60px max(5vw, 24px) 48px; }
    #visi-misi { grid-template-columns: 1fr; }
    .ppdb-grid { grid-template-columns: 1fr; gap: 40px; }
    .lokasi-grid { grid-template-columns: 1fr; }
    .footer-top { grid-template-columns: 1fr 1fr; }
    .gallery-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
    .gi:nth-child(1) { grid-column: span 2; grid-row: span 1; height: 240px; }
    .gi:nth-child(4) { grid-column: span 1; }
    .dgrid { grid-template-columns: 1fr; }
    .hero-logo-card { width: 55%; }
}

        @media (max-width: 640px) {
            nav {
                height: 64px;
                padding: 0 16px;
                gap: 12px;
            }
            .nav-brand {
                min-width: 0;
                gap: 8px;
            }
            .nav-logo {
                width: 36px;
                height: 36px;
            }
            .nav-brand-text {
                min-width: 0;
            }
            .nav-brand-name {
                max-width: 210px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-size: .82rem;
            }
            .nav-brand-sub {
                font-size: .58rem;
            }
            .nav-links { display: none; }
            .nav-auth { display: none; }
            .nav-user { display: none !important; }
            .hamburger { display: flex; }

            /* hero */
            #hero { grid-template-columns: 1fr; }
            .hero-right { min-height: 260px; }
            .hero-title { font-size: 2rem; }
            .hero-desc { font-size: .88rem; }
            .hero-ctas { flex-direction: column; }
            .btn-cta-primary, .btn-cta-secondary { width: 100%; justify-content: center; }
            .hero-stats { flex-wrap: wrap; gap: 16px; }
            .hs { padding-right: 16px; margin-right: 16px; }
            .hero-accent-card { display: none; }
            .hero-logo-card { width: 60%; padding: 18px 22px; }
            .hero-logo-card img { height: 80px; }
            .hero-year-badge { font-size: .75rem; padding: 7px 14px; }

            /* galeri */
            .gallery-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .gi { height: 200px; grid-column: auto !important; grid-row: auto !important; }

            /* footer */
            .footer-top { grid-template-columns: 1fr; }

            /* lainnya */
            section, #fasilitas, #ppdb, #galeri, #lokasi { padding: 64px max(5vw, 20px); }
            .fgrow { grid-template-columns: 1fr; }
            .dhead, .dbody { padding: 20px; }
            .dpanel { margin: 8px; }

            /* ── MOBILE MENU ─────────────────── */
            .mobile-menu {
                display: block;
                position: fixed;
                top: 64px; left: 0; right: 0;
                z-index: 850;
                background: rgba(255,255,255,.98);
                backdrop-filter: blur(16px);
                border-bottom: 1px solid var(--rule);
                box-shadow: 0 8px 24px rgba(26,58,107,.1);
                padding: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height .35s cubic-bezier(.4,0,.2,1), padding .35s;
            }
            .mobile-menu.open {
                max-height: calc(100vh - 64px);
                padding: 12px 0 18px;
                overflow-y: auto;
            }
            .mobile-menu-links {
                list-style: none;
                display: flex; flex-direction: column;
                padding: 0 16px;
                gap: 4px;
            }
            .mobile-menu-links a {
                display: block;
                padding: 12px 14px;
                border-radius: 8px;
                font-size: .9rem; font-weight: 600;
                color: var(--ink-2); text-decoration: none;
                transition: background .15s, color .15s;
            }
            .mobile-menu-links a:hover,
            .mobile-menu-links a:active {
                background: var(--blue-light);
                color: var(--blue);
            }
            .mobile-menu-divider {
                height: 1px;
                background: var(--rule);
                margin: 10px 16px;
            }
            .mobile-menu-auth {
                display: flex; gap: 10px;
                padding: 0 16px;
            }
            .mobile-menu-auth a {
                flex: 1; text-align: center;
                padding: 11px 10px;
                border-radius: 8px;
                font-size: .88rem; font-weight: 700;
                text-decoration: none;
                transition: background .15s;
            }
            .mobile-menu-auth .m-login {
                border: 1.5px solid var(--rule);
                color: var(--ink-2);
                background: transparent;
            }
            .mobile-menu-auth .m-login:hover { background: var(--surface); }
            .mobile-menu-auth .m-daftar {
                background: var(--blue); color: #fff;
                border: 1.5px solid var(--blue);
            }
            .mobile-menu-auth .m-daftar:hover { background: var(--blue-mid); }

            /* user state di mobile menu */
            .mobile-menu-user {
                display: none;
                flex-direction: column; gap: 8px;
                padding: 0 16px;
            }
            .mobile-menu-user.show { display: flex; }
            .mobile-user-info {
                display: flex; align-items: center; gap: 10px;
                padding: 10px 14px;
                background: var(--blue-light);
                border-radius: 8px;
            }
            .mobile-user-avatar {
                width: 34px; height: 34px; border-radius: 50%;
                background: var(--blue); color: #fff;
                display: flex; align-items: center; justify-content: center;
                font-size: .72rem; font-weight: 700;
                flex-shrink: 0;
            }
            .mobile-user-name {
                font-size: .88rem; font-weight: 600;
                color: var(--blue);
            }
            .mobile-menu-user-btns {
                display: flex; gap: 8px;
            }
            .mobile-menu-user-btns button {
                flex: 1; padding: 10px;
                border-radius: 8px;
                font-size: .84rem; font-weight: 700;
                font-family: inherit; cursor: pointer;
                transition: background .15s;
            }
            .mbtn-dash {
                background: var(--blue); color: #fff;
                border: 1.5px solid var(--blue);
            }
            .mbtn-dash:hover { background: var(--blue-mid); }
            .mbtn-logout {
                background: transparent; color: var(--ink-3);
                border: 1.5px solid var(--rule);
            }
            .mbtn-logout:hover { border-color: var(--red); color: var(--red); }

            }
    </style>
</head>
<body>

{{-- NAVBAR                             --}}
<nav>
    <a class="nav-brand" href="/">
        {{-- Logo only — no wrapper circle/border --}}
        <img class="nav-logo" src="{{ asset('images/logo-smamsa1.jpeg') }}" alt="Logo SMAMSA">
        <div class="nav-brand-text">
            <span class="nav-brand-name">SMA Muhammadiyah 1 Purwokerto</span>
            <span class="nav-brand-sub">SMAMSA — Est. 1956</span>
        </div>
    </a>

    <ul class="nav-links">
        <li><a href="#visi-misi">Visi &amp; Misi</a></li>
        <li><a href="#fasilitas">Fasilitas</a></li>
        <li><a href="#ppdb">SPMB</a></li>
        <li><a href="#galeri">Galeri</a></li>
        <li><a href="#lokasi">Lokasi</a></li>
    </ul>

    <div class="nav-auth" id="nav-auth-buttons">
        <a href="/login" class="btn-login">Masuk</a>
        <a href="/register" class="btn-daftar">Daftar</a>
    </div>

    <div class="nav-user" id="nav-user-info">
        <div class="nav-avatar" id="nav-avatar">–</div>
        <span class="nav-uname" id="nav-username">–</span>
        <button class="btn-dash" id="btn-dashboard-nav" onclick="openDashboard()">Dashboard</button>
        <button class="btn-logout" onclick="doLogout()">Keluar</button>
    </div>

    <button class="hamburger" type="button" aria-label="Buka menu navigasi" aria-controls="mobile-menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- MOBILE MENU                        --}}
<div class="mobile-menu" id="mobile-menu">
    <ul class="mobile-menu-links">
        <li><a href="#visi-misi" onclick="closeMobileMenu()">Visi &amp; Misi</a></li>
        <li><a href="#fasilitas" onclick="closeMobileMenu()">Fasilitas</a></li>
        <li><a href="#ppdb" onclick="closeMobileMenu()">SPMB</a></li>
        <li><a href="#galeri" onclick="closeMobileMenu()">Galeri</a></li>
        <li><a href="#lokasi" onclick="closeMobileMenu()">Lokasi</a></li>
    </ul>
    <div class="mobile-menu-divider"></div>

    {{-- state: belum login --}}
    <div class="mobile-menu-auth" id="mobile-auth-buttons">
        <a href="/login" class="m-login">Masuk</a>
        <a href="/register" class="m-daftar">Daftar</a>
    </div>

    {{-- state: sudah login --}}
    <div class="mobile-menu-user" id="mobile-user-info">
        <div class="mobile-user-info">
            <div class="mobile-user-avatar" id="mobile-avatar">–</div>
            <span class="mobile-user-name" id="mobile-username">–</span>
        </div>
        <div class="mobile-menu-user-btns">
            <button class="mbtn-dash" onclick="closeMobileMenu(); openDashboard()">Dashboard</button>
            <button class="mbtn-logout" onclick="closeMobileMenu(); doLogout()">Keluar</button>
        </div>
    </div>
</div>

{{-- LOGIN MODAL                        --}}
<div class="moverlay" id="modal-login">
    <div class="mbox">
        <button class="mclose" onclick="closeModal('login')">✕</button>
        <h2>Masuk</h2>
        <p class="msub">Belum punya akun? <a onclick="switchModal('login','register')">Daftar sekarang →</a></p>
        <div class="malert malert-ok" id="login-success">Login berhasil! Mengalihkan…</div>
        <div class="malert malert-no" id="login-error">Email atau password salah.</div>
        <div class="fg">
            <label>Email</label>
            <input type="email" class="fc" id="login-email" placeholder="nama@email.com">
            <div class="ferr" id="err-login-email">Email tidak valid.</div>
        </div>
        <div class="fg">
            <label>Password</label>
            <input type="password" class="fc" id="login-password" placeholder="••••••••">
            <div class="ferr" id="err-login-pass">Password minimal 6 karakter.</div>
        </div>
        <button class="btn-submit" onclick="doLogin()">Masuk</button>
        <div class="msep"><span>atau</span></div>
        <button class="btn-demo" onclick="demoLogin()">🎓 Demo Login (Calon Siswa)</button>
    </div>
</div>

{{-- REGISTER MODAL                     --}}
<div class="moverlay" id="modal-register">
    <div class="mbox">
        <button class="mclose" onclick="closeModal('register')">✕</button>
        <h2>Buat Akun</h2>
        <p class="msub">Sudah punya akun? <a onclick="switchModal('register','login')">Masuk di sini →</a></p>
        <div class="malert malert-ok" id="register-success">Akun berhasil dibuat! Silakan masuk.</div>
        <div class="malert malert-no" id="register-error">Terjadi kesalahan. Periksa data Anda.</div>
        <div class="fgrow">
            <div class="fg">
                <label>Nama Depan</label>
                <input type="text" class="fc" id="reg-firstname" placeholder="Budi">
                <div class="ferr" id="err-reg-fname">Wajib diisi.</div>
            </div>
            <div class="fg">
                <label>Nama Belakang</label>
                <input type="text" class="fc" id="reg-lastname" placeholder="Santoso">
                <div class="ferr" id="err-reg-lname">Wajib diisi.</div>
            </div>
        </div>
        <div class="fg">
            <label>Email</label>
            <input type="email" class="fc" id="reg-email" placeholder="nama@email.com">
            <div class="ferr" id="err-reg-email">Email tidak valid.</div>
        </div>
        <div class="fg">
            <label>Nomor HP / WhatsApp</label>
            <input type="tel" class="fc" id="reg-phone" placeholder="08xxxxxxxxxx">
            <div class="ferr" id="err-reg-phone">Nomor tidak valid.</div>
        </div>
        <div class="fg">
            <label>Password</label>
            <input type="password" class="fc" id="reg-password" placeholder="Minimal 6 karakter">
            <div class="ferr" id="err-reg-pass">Password minimal 6 karakter.</div>
        </div>
        <div class="fg">
            <label>Konfirmasi Password</label>
            <input type="password" class="fc" id="reg-confirm" placeholder="Ulangi password">
            <div class="ferr" id="err-reg-confirm">Password tidak cocok.</div>
        </div>
        <button class="btn-submit" onclick="doRegister()">Buat Akun</button>
    </div>
</div>

{{-- DASHBOARD                          --}}
<div class="doverlay" id="dashboard-overlay">
    <div class="dpanel">
        <div class="dhead">
            <div class="duser">
                <div class="dav" id="dash-avatar">B</div>
                <div>
                    <div class="dgreeting">Selamat datang kembali 👋</div>
                    <div class="dname" id="dash-fullname">Budi Santoso</div>
                </div>
            </div>
            <button class="dcloser" onclick="closeDashboard()">✕</button>
        </div>
        <div class="dbody">
            <div class="dstatus">
                <div class="dstatus-icon">📋</div>
                <div>
                    <div class="dstatus-lbl">Status Pendaftaran</div>
                    <div class="dstatus-val">SPMB SMA Muhammadiyah 1 Purwokerto – T.A. 2025/2026</div>
                </div>
                <span class="badge-pending">Belum Lengkap</span>
            </div>
            <div class="dgrid">
                <div class="dcard">
                    <div class="dcard-lbl">Waktu Tersisa</div>
                    <div class="dcard-val" id="dash-countdown">–</div>
                    <div class="dcard-sub">Hingga penutupan pendaftaran</div>
                </div>
                <div class="dcard">
                    <div class="dcard-lbl">Berkas Terunggah</div>
                    <div class="dcard-val">0/6</div>
                    <div class="dcard-sub">Lengkapi segera untuk seleksi</div>
                </div>
            </div>
            <div class="dtl">
                <div class="dtl-head">Progress Tahapan SPMB</div>
                <div class="trow"><div class="tdot done"></div><div class="ttext">Pembuatan akun</div><div class="tdate">✔ Selesai</div></div>
                <div class="trow"><div class="tdot now"></div><div class="ttext now">Pengisian formulir & upload berkas</div><div class="tdate">Aktif</div></div>
                <div class="trow"><div class="tdot later"></div><div class="ttext">Tes seleksi & wawancara</div><div class="tdate">1–10 Jul</div></div>
                <div class="trow"><div class="tdot later"></div><div class="ttext">Pengumuman hasil seleksi</div><div class="tdate">20 Jul</div></div>
                <div class="trow"><div class="tdot later"></div><div class="ttext">Daftar ulang & orientasi</div><div class="tdate">21–30 Jul</div></div>
            </div>
            <div class="dacts">
                <button class="dbtn dbtn-p" onclick="closeDashboard()">Lengkapi Formulir</button>
                <button class="dbtn dbtn-g" onclick="closeDashboard()">Upload Berkas</button>
                <button class="dbtn dbtn-g" onclick="closeDashboard()">Hubungi Panitia</button>
            </div>
        </div>
    </div>
</div>

{{-- HERO                               --}}
@if ($hero)
<section id="hero">
    <div class="hero-left">
        <div class="hero-badge">SPMB {{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}</div>
        <h1 class="hero-title">
            <strong>{{ $hero->title_main }}</strong><br>
            <em>{{ $hero->title_italic }}</em>
        </h1>
        <p class="hero-desc">{{ $hero->subtitle }}</p>
        <div class="hero-ctas">
            <a href="{{ $hero->btn_primary_url }}" class="btn-cta-primary">
                {{ $hero->btn_primary_label }}
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ $hero->btn_outline_url }}" class="btn-cta-secondary">{{ $hero->btn_outline_label }}</a>
        </div>
        @if ($hero->stats->isNotEmpty())
        <div class="hero-stats">
            @foreach ($hero->stats as $i => $stat)
                <div class="hs">
                    <span class="hs-num">{{ $stat->number }}</span>
                    <span class="hs-lbl">{{ $stat->label }}</span>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="hero-right">
        <div class="hero-img-bg" style="background-image: url('{{ $hero->background_image ? Storage::url($hero->background_image) : asset('images/Logo-smamsa2.jpeg') }}');"></div>
        <div class="hero-dots">
            @for($i=0;$i<36;$i++)<span></span>@endfor
        </div>
        <div class="hero-right-content">
            <div class="hero-accent-card">
                <div class="hac-label">Visi Sekolah</div>
                <div class="hac-text">"Terbentuknya Pribadi Islami yang Unggul Dalam IMTAQ, Berkemajuan dan Memiliki Life Skill"</div>
            </div>
            <div class="hero-year-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                Tahun Ajaran {{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}
            </div>
        </div>
    </div>
</section>
@endif

{{-- VISI MISI                          --}}
<section id="visi-misi">
    <div class="vm visi reveal">
        <div class="eyebrow light">Visi Sekolah</div>
        <h2 class="section-title light">Tujuan &amp; Cita-cita</h2>
        <div class="vm-visi-text">
            "Terbentuknya Pribadi Islami yang Unggul Dalam IMTAQ, Berkemajuan dan Memiliki Life Skill"
        </div>
    </div>
    <div class="vm misi reveal">
        <div class="eyebrow">Misi Sekolah</div>
        <h2 class="section-title">Langkah Kami</h2>
        <ul class="misi-ul">
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Meningkatkan Iman dan Takwa kepada seluruh siswa.
            </li>
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Memberdayakan warga sekolah untuk aktif dalam kegiatan da'wah persyarikatan.
            </li>
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Melaksanakan KBM yang kondusif, efektif dan efisien demi nilai UN/US optimal.
            </li>
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Meningkatkan daya saing untuk masuk PTN/PTS favorit dan berprestasi akademi & non-akademi.
            </li>
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Memberikan pembekalan Life Skill kepada setiap siswa.
            </li>
            <li>
                <div class="mcheck">
                    <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                </div>
                Mempersiapkan kader umat Islam, Bangsa, dan Persyarikatan.
            </li>
        </ul>
    </div>
</section>

{{-- FASILITAS                          --}}
<section id="fasilitas">
    <div class="fas-head reveal">
        <div class="eyebrow">Fasilitas Kami</div>
        <h2 class="section-title">Lingkungan Belajar<br>yang Mendukung</h2>
        <p class="section-lead">Kami menyediakan fasilitas modern dan lengkap untuk mendukung proses belajar-mengajar yang optimal serta pengembangan bakat dan minat siswa.</p>
    </div>
    <div class="fas-grid">
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">🔬</span></div>
            <div class="fas-title">Lab Sains</div>
            <p class="fas-desc">Laboratorium Fisika, Kimia, dan Biologi dengan peralatan sesuai standar.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">💻</span></div>
            <div class="fas-title">Lab Komputer</div>
            <p class="fas-desc">Puluhan unit komputer untuk mendukung belajar siswa.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">📚</span></div>
            <div class="fas-title">Perpustakaan</div>
            <p class="fas-desc">Koleksi lebih dari 1.000 judul buku.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">🏟️</span></div>
            <div class="fas-title">Lapangan</div>
            <p class="fas-desc">Lapangan olahraga di lingkungan sekolah.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">🎭</span></div>
            <div class="fas-title">Aula</div>
            <p class="fas-desc">Ruang pertunjukan berkapasitas 500 orang.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">🍽️</span></div>
            <div class="fas-title">Kantin Sehat</div>
            <p class="fas-desc">Kantin higienis dengan menu bergizi dan terjangkau.</p>
        </div>
        <div class="fas-card reveal">
            <div class="fas-icon-bg"><span class="fas-emoji">🏥</span></div>
            <div class="fas-title">Klinik Sekolah</div>
            <p class="fas-desc">Unit kesehatan sekolah untuk kebutuhan siswa setiap hari.</p>
        </div>
    </div>
</section>

{{-- PPDB                               --}}
<section id="ppdb">
    <div class="ppdb-inner">
        <div class="reveal">
            <div class="eyebrow light">Penerimaan Murid Baru</div>
            <h2 class="section-title light">Informasi SPMB<br>{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}</h2>
            <p class="section-lead light">Ikuti alur pendaftaran berikut untuk bergabung bersama keluarga besar SMA Muhammadiyah 1 Purwokerto. Proses seleksi transparan dan berkeadilan.</p>
        </div>

        <div class="ppdb-grid">
            <div class="reveal">
                <p style="font-size:.68rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.3);margin-bottom:24px;">Jadwal Pelaksanaan</p>
                <div class="jadwal-list">
                    @foreach ($jadwals as $jadwal)
                    <div class="jadwal-item">
                        <div class="jadwal-num">{{ $jadwal->nomor_urut }}</div>
                        <div>
                            <div class="jadwal-date">{{ $jadwal->tanggal_label }}</div>
                            <div class="jadwal-title">{{ $jadwal->judul }}</div>
                            @if ($jadwal->deskripsi)
                            <div class="jadwal-desc">{{ $jadwal->deskripsi }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="ppdb-side reveal">
                @if ($persyaratan->isNotEmpty())
                <div class="pcard">
                    <div class="pcard-title">📋 Persyaratan Dokumen</div>
                    <ul class="req-list">
                        @foreach ($persyaratan as $p)
                        <li>{{ $p->dokumen }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if ($biaya->isNotEmpty())
                <div class="pcard">
                    <div class="pcard-title">💰 Biaya Pendidikan</div>
                    <table class="biaya-tbl">
                        @foreach ($biaya as $b)
                        <tr><td>{{ $b->nama_biaya }}</td><td>{{ $b->nominal_rupiah }}</td></tr>
                        @endforeach
                    </table>
                    @if ($ppdbSetting?->catatan_beasiswa)
                    <p class="pnote">{{ $ppdbSetting->catatan_beasiswa }}</p>
                    @endif
                </div>
                @endif

                @if ($ppdbSetting)
                <div class="pcard">
                    <div class="pcard-title">📞 Kontak Panitia</div>
                    <div class="pinfo">
                        @if ($ppdbSetting->telepon)📱 {{ $ppdbSetting->telepon }}<br>@endif
                        @if ($ppdbSetting->jam_operasional)🕐 {{ $ppdbSetting->jam_operasional }}@endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="ppdb-cta reveal">
            <a href="{{ $ppdbSetting?->link_pendaftaran ?? '/register' }}" class="btn-ppdb">
                Daftar Online Sekarang
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#lokasi" class="btn-ppdb-ghost">Lihat Lokasi Sekolah</a>
        </div>
    </div>
</section>

{{-- GALERI                             --}}
<section id="galeri">
    <div class="galeri-head reveal">
        <div class="eyebrow">Galeri Sekolah</div>
        <h2 class="section-title">Momen di SMAMSA<br>Purwokerto</h2>
    </div>
    @if ($galeri->isNotEmpty())
    <div class="gallery-grid reveal">
        @foreach ($galeri as $item)
        <div class="gi {{ $item->tipe === 'video' ? 'video-gi' : '' }}">
            @if ($item->tipe === 'video')
                <iframe src="{{ $item->embed_url }}" title="{{ $item->judul }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @else
                <img src="{{ $item->gambar_url ?? asset($item->gambar_path) }}" alt="{{ $item->alt_text ?? $item->judul }}">
            @endif
            <div class="gi-cap">{{ $item->caption ?? $item->judul }}</div>
        </div>
        @endforeach
    </div>
    @endif
</section>

{{-- LOKASI                             --}}
<section id="lokasi">
    <div class="reveal">
        <div class="eyebrow">Lokasi</div>
        <h2 class="section-title">Temukan Kami</h2>
    </div>
    <div class="lokasi-grid">
        <div class="reveal">
            <div class="lcard">
                <div class="lcard-lbl">Alamat</div>
                <div class="lcard-val">Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115</div>
            </div>
            <div class="lcard">
                <div class="lcard-lbl">Jam Operasional</div>
                <div class="lcard-val">Senin – Jumat: 07.30 – 16.00 WIB<br>Sabtu: 07.30 – 12.30 WIB</div>
            </div>
            <div class="lcard">
                <div class="lcard-lbl">Telepon</div>
                <div class="lcard-val">(0281) 633373</div>
            </div>
            <a href="https://www.google.com/maps?q=SMA+Muhammadiyah+1+Purwokerto"target="_blank"class="btn-maps">Buka di Google Maps →</a>
        </div>
        <div class="map-frame reveal">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.443536014362!2d109.2288820747998!3d-7.416064673040172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e572a33a7bf%3A0x60396314bb780a46!2sSMA%20Muhammadiyah%201%20Purwokerto%20(SMAMSA%20Purwokerto)!5e0!3m2!1sid!2sid!4v1772252049348!5m2!1sid!2sid" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- footer --}}
<footer>
    <div class="footer-top">
        <div>
            <div class="footer-logo-wrap">
                <img class="footer-logo" src="{{ asset('images/logo-smamsa1.jpeg') }}" alt="Logo SMAMSA">
                <span class="footer-school-name">SMA Muhammadiyah 1 Purwokerto</span>
            </div>
            <p class="footer-tagline">Mendidik generasi penerus bangsa yang cerdas, berkarakter, dan siap menghadapi tantangan global dengan pondasi nilai Islam yang kuat.</p>
        </div>
        <div class="footer-col">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#visi-misi">Visi &amp; Misi</a></li>
                <li><a href="#fasilitas">Fasilitas</a></li>
                <li><a href="#ppdb">Info SPMB</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="#lokasi">Lokasi</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>SPMB</h4>
            <ul>
                <li><a href="/register">Pendaftaran Online</a></li>
                <li><a href="#">Persyaratan</a></li>
                <li><a href="#">Jadwal Seleksi</a></li>
                <li><a href="#">Biaya Pendidikan</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Sosial Media</h4>
            <ul>
                <li><a href="https://www.instagram.com/smamsapurwokerto/" target="_blank">Instagram</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p class="footer-copy">© 2025 SMA Muhammadiyah 1 Purwokerto. Hak cipta dilindungi undang-undang.</p>
        <p class="footer-copy">Dirancang untuk generasi Indonesia 🇮🇩</p>
    </div>
</footer>


<script>
// STATE 
let currentUser = null;
const users = [{ email:'demo@ppdb.id', password:'demo123', name:'Demo Siswa' }];
const hamburger = document.querySelector('.hamburger');
const mobileMenu = document.getElementById('mobile-menu');

// MOBILE NAV 
function setBodyLock() {
    const hasOverlay = document.querySelector('.moverlay.open,.doverlay.open');
    const hasMobileMenu = mobileMenu && mobileMenu.classList.contains('open');
    document.body.style.overflow = hasOverlay || hasMobileMenu ? 'hidden' : '';
}

function closeMobileMenu() {
    if (!mobileMenu || !hamburger) return;
    mobileMenu.classList.remove('open');
    hamburger.classList.remove('active');
    hamburger.setAttribute('aria-expanded', 'false');
    hamburger.setAttribute('aria-label', 'Buka menu navigasi');
    setBodyLock();
}

function toggleMobileMenu() {
    if (!mobileMenu || !hamburger) return;
    const isOpen = mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('active', isOpen);
    hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    hamburger.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    setBodyLock();
}

if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', e => {
        e.stopPropagation();
        toggleMobileMenu();
    });

    document.addEventListener('click', e => {
        if (
            mobileMenu.classList.contains('open') &&
            !mobileMenu.contains(e.target) &&
            !hamburger.contains(e.target)
        ) {
            closeMobileMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 640) closeMobileMenu();
    });
}

// MODAL 
function openModal(t){ clearForms(); closeMobileMenu(); document.getElementById('modal-'+t).classList.add('open'); setBodyLock(); }
function closeModal(t){ document.getElementById('modal-'+t).classList.remove('open'); setBodyLock(); }
function switchModal(a,b){ closeModal(a); setTimeout(()=>openModal(b),200); }
document.querySelectorAll('.moverlay').forEach(o=>{
    o.addEventListener('click',e=>{ if(e.target===o){ o.classList.remove('open'); setBodyLock(); }});
});

// LOGIN 
function doLogin(){
    const email=document.getElementById('login-email').value.trim();
    const pass=document.getElementById('login-password').value;
    let ok=true;
    if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ showErr('err-login-email',true); ok=false; }else showErr('err-login-email',false);
    if(!pass||pass.length<6){ showErr('err-login-pass',true); ok=false; }else showErr('err-login-pass',false);
    if(!ok)return;
    const user=users.find(u=>u.email===email&&u.password===pass);
    if(!user){ document.getElementById('login-error').classList.add('show'); return; }
    document.getElementById('login-error').classList.remove('show');
    document.getElementById('login-success').classList.add('show');
    setTimeout(()=>{ closeModal('login'); setLoggedIn({name:user.name,email:user.email}); },900);
}
function demoLogin(){ setLoggedIn({name:'Demo Siswa',email:'demo@ppdb.id'}); closeModal('login'); }

// REGISTER 
function doRegister(){
    const fname=document.getElementById('reg-firstname').value.trim();
    const lname=document.getElementById('reg-lastname').value.trim();
    const email=document.getElementById('reg-email').value.trim();
    const phone=document.getElementById('reg-phone').value.trim();
    const pass=document.getElementById('reg-password').value;
    const confirm=document.getElementById('reg-confirm').value;
    let ok=true;
    if(!fname){ showErr('err-reg-fname',true); ok=false; }else showErr('err-reg-fname',false);
    if(!lname){ showErr('err-reg-lname',true); ok=false; }else showErr('err-reg-lname',false);
    if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ showErr('err-reg-email',true); ok=false; }else showErr('err-reg-email',false);
    if(!phone||phone.length<10){ showErr('err-reg-phone',true); ok=false; }else showErr('err-reg-phone',false);
    if(!pass||pass.length<6){ showErr('err-reg-pass',true); ok=false; }else showErr('err-reg-pass',false);
    if(!confirm||confirm!==pass){ showErr('err-reg-confirm',true); ok=false; }else showErr('err-reg-confirm',false);
    if(!ok)return;
    if(users.find(u=>u.email===email)){ const el=document.getElementById('register-error'); el.textContent='Email sudah terdaftar.'; el.classList.add('show'); return; }
    users.push({email,password:pass,name:fname+' '+lname});
    document.getElementById('register-error').classList.remove('show');
    document.getElementById('register-success').classList.add('show');
    setTimeout(()=>{ switchModal('register','login'); document.getElementById('login-email').value=email; },1200);
}

// AUTH 
function setLoggedIn(user) {
    currentUser = user;
    const ini = user.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();

    // navbar desktop
    document.getElementById('nav-auth-buttons').style.display = 'none';
    const ui = document.getElementById('nav-user-info'); ui.style.display = 'flex';
    const bd = document.getElementById('btn-dashboard-nav'); bd.style.display = 'flex';
    document.getElementById('nav-avatar').textContent = ini;
    document.getElementById('nav-username').textContent = user.name.split(' ')[0];

    // dashboard
    document.getElementById('dash-avatar').textContent = ini;
    document.getElementById('dash-fullname').textContent = user.name;

    // mobile menu
    document.getElementById('mobile-auth-buttons').style.display = 'none';
    const mu = document.getElementById('mobile-user-info'); mu.classList.add('show');
    document.getElementById('mobile-avatar').textContent = ini;
    document.getElementById('mobile-username').textContent = user.name.split(' ')[0];
}

function doLogout() {
    currentUser = null;
    // desktop
    document.getElementById('nav-auth-buttons').style.display = 'flex';
    document.getElementById('nav-user-info').style.display = 'none';
    document.getElementById('btn-dashboard-nav').style.display = 'none';
    // mobile
    document.getElementById('mobile-auth-buttons').style.display = 'flex';
    document.getElementById('mobile-user-info').classList.remove('show');
    closeMobileMenu();
}

// DASHBOARD 
function openDashboard(){
    if(!currentUser){ openModal('login'); return; }
    closeMobileMenu();
    document.getElementById('dashboard-overlay').classList.add('open');
    setBodyLock();
    const d=Math.floor((new Date('2025-07-30')-new Date())/86400000);
    document.getElementById('dash-countdown').textContent=d>0?d+' hari':'Ditutup';
}
function closeDashboard(){
    document.getElementById('dashboard-overlay').classList.remove('open');
    setBodyLock();
}
document.getElementById('dashboard-overlay').addEventListener('click',e=>{ if(e.target===document.getElementById('dashboard-overlay'))closeDashboard(); });

// HELPERS  
function showErr(id,show){
    const el=document.getElementById(id); if(!el)return;
    el.classList.toggle('show',show);
    const inp=el.previousElementSibling;
    if(inp&&inp.classList.contains('fc')) inp.classList.toggle('err',show);
}
function clearForms(){
    document.querySelectorAll('.fc').forEach(e=>{e.value='';e.classList.remove('err');});
    document.querySelectorAll('.ferr').forEach(e=>e.classList.remove('show'));
    document.querySelectorAll('.malert').forEach(e=>e.classList.remove('show'));
}

//  SCROLL REVEAL 
const io=new IntersectionObserver(entries=>{
    entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }});
},{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// ESC  
document.addEventListener('keydown',e=>{
    if(e.key==='Escape'){
        document.querySelectorAll('.moverlay.open,.doverlay.open').forEach(m=>m.classList.remove('open'));
        closeMobileMenu();
        setBodyLock();
    }
});
</script>
</body>
</html>
