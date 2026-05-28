<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AAYMCA Voting Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600;700&family=Barlow+Condensed:wght@700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red: #C8102E;
            --red-dark: #9e0b23;
            --black: #0d0d0d;
            --grey: #6b6b6b;
            --grey-light: #e8e8e8;
            --white: #ffffff;
            --surface: #141414;
            --surface-2: #1e1e1e;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--black);
            color: var(--white);
            font-family: 'Barlow', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2.5rem;
            background: rgba(13,13,13,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .nav-logo img {
            height: 38px;
            width: auto;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 0.5rem 1.25rem;
            border-radius: 2px;
            transition: background 0.2s, color 0.2s;
        }

        .nav-links a.ghost {
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .nav-links a.ghost:hover {
            color: var(--white);
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.05);
        }

        .nav-links a.solid {
            color: var(--white);
            background: var(--red);
            border: 1px solid var(--red);
        }
        .nav-links a.solid:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 1.5rem 5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 50% 0%, rgba(200,16,46,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-eyebrow {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .hero-eyebrow::before,
        .hero-eyebrow::after {
            content: '';
            display: block;
            width: 32px;
            height: 1px;
            background: var(--red);
        }

        .hero h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            line-height: 0.95;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .hero h1 span {
            color: var(--red);
            display: block;
        }

        .hero-sub {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(255,255,255,0.55);
            max-width: 520px;
            margin: 0 auto 2.5rem;
            font-weight: 300;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.875rem 2rem;
            border-radius: 2px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--red);
            color: var(--white);
            border: 1px solid var(--red);
        }
        .btn-primary:hover {
            background: var(--red-dark);
            border-color: var(--red-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-outline:hover {
            color: var(--white);
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.04);
        }

        /* ── DIVIDER ── */
        .divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* ── FEATURES ── */
        .features {
            padding: 5rem 2.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            text-transform: uppercase;
            letter-spacing: 0.01em;
            margin-bottom: 3.5rem;
            color: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .feature-card {
            background: var(--surface);
            padding: 2rem 1.75rem;
            transition: background 0.2s;
        }
        .feature-card:hover {
            background: var(--surface-2);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            margin-bottom: 1.25rem;
            color: var(--red);
        }

        .feature-card h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
            color: var(--white);
        }

        .feature-card p {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
        }

        /* ── STATS STRIP ── */
        .stats-strip {
            background: var(--red);
            padding: 3.5rem 2.5rem;
        }

        .stats-inner {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: 2rem;
            flex-wrap: wrap;
            text-align: center;
        }

        .stat-num {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            line-height: 1;
            color: var(--white);
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-top: 0.3rem;
        }

        .stat-divider {
            width: 1px;
            height: 48px;
            background: rgba(255,255,255,0.25);
        }

        /* ── CTA ── */
        .cta-section {
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
        }

        .cta-section h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: clamp(2rem, 5vw, 3.5rem);
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .cta-section p {
            color: rgba(255,255,255,0.5);
            max-width: 440px;
            margin: 0 auto 2.5rem;
            font-size: 1rem;
            font-weight: 300;
        }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        footer img {
            height: 28px;
            width: auto;
            filter: brightness(0) invert(1);
            opacity: 0.4;
        }

        footer p {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.3);
            letter-spacing: 0.02em;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up {
            animation: fadeUp 0.7s ease both;
        }
        .fade-up:nth-child(1) { animation-delay: 0s; }
        .fade-up:nth-child(2) { animation-delay: 0.12s; }
        .fade-up:nth-child(3) { animation-delay: 0.22s; }
        .fade-up:nth-child(4) { animation-delay: 0.32s; }
        .fade-up:nth-child(5) { animation-delay: 0.42s; }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            nav { padding: 1rem 1.25rem; }
            .nav-logo img { height: 30px; }
            .features { padding: 3.5rem 1.25rem; }
            .stat-divider { display: none; }
            footer { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    {{-- ── NAVIGATION ── --}}
    <nav>
        <a href="/" class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="AAYMCA">
        </a>
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="solid">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="ghost">Sign In</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="solid">Register</a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- ── HERO ── --}}
    <section class="hero">
        <div class="hero-eyebrow fade-up">Africa Alliance of YMCAs</div>
        <h1 class="fade-up">
            Voting
            <span>Platform</span>
        </h1>
        <p class="hero-sub fade-up">
            Transparent, secure, and democratic elections for the YMCA Africa Alliance community.
        </p>
        <div class="hero-actions fade-up">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    Go to Dashboard
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Sign In to Vote
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline">Create Account</a>
                @endif
            @endauth
        </div>
    </section>

    <div class="divider"></div>

    {{-- ── FEATURES ── --}}
    <section class="features">
        <p class="section-label">Why This Platform</p>
        <h2 class="section-title">Built for Democratic Excellence</h2>
        <div class="features-grid">
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 4L4 10v9c0 7.18 5.94 13.9 14 15.96C26.06 32.9 32 26.18 32 19v-9L18 4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M13 18l3.5 3.5L23 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>Secure Voting</h3>
                <p>Every vote is authenticated and protected. One member, one vote — no duplicates, no manipulation.</p>
            </div>
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="18" cy="18" r="14" stroke="currentColor" stroke-width="2"/>
                    <path d="M18 10v8l5 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>Live Results</h3>
                <p>Results are tallied in real-time as votes are cast. Full transparency from open to close.</p>
            </div>
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="8" width="28" height="20" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M4 14h28M10 20h4M10 24h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h3>Full Audit Trail</h3>
                <p>Every action is logged. Admins have access to comprehensive audit records for accountability.</p>
            </div>
            <div class="feature-card">
                <svg class="feature-icon" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="14" cy="13" r="5" stroke="currentColor" stroke-width="2"/>
                    <circle cx="26" cy="13" r="5" stroke="currentColor" stroke-width="2"/>
                    <path d="M4 30c0-5.52 4.48-10 10-10h12c5.52 0 10 4.48 10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h3>Group Management</h3>
                <p>Organise voters into groups and assign elections by membership for structured governance.</p>
            </div>
        </div>
    </section>

    {{-- ── STATS STRIP ── --}}
    <div class="stats-strip">
        <div class="stats-inner">
            <div>
                <div class="stat-num">100%</div>
                <div class="stat-label">Transparent</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">Real-Time</div>
                <div class="stat-label">Result Counting</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">Secure</div>
                <div class="stat-label">Authenticated Votes</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">PDF</div>
                <div class="stat-label">Exportable Reports</div>
            </div>
        </div>
    </div>

    {{-- ── CTA ── --}}
    <section class="cta-section">
        <h2>Ready to Participate?</h2>
        <p>Join the AAYMCA community and make your voice heard in every election.</p>
        @auth
            <a href="{{ url('/voter') }}" class="btn btn-primary">View Active Elections</a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary">
                Get Started
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        @endif
    </section>

    {{-- ── FOOTER ── --}}
    <footer>
        <img src="{{ asset('images/logo.png') }}" alt="AAYMCA Africa Alliance">
        <p>&copy; {{ date('Y') }} YMCA Africa Alliance. All rights reserved.</p>
    </footer>

</body>
</html>