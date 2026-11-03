<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'FinanceFlow') | FinanceFlow</title>
    <meta name="theme-color" content="#16845b">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Restore saved theme --}}
    <script>
        (() => {
            try {
                const savedTheme = JSON.parse(
                    localStorage.getItem('financeflow-theme') || 'null'
                );

                if (!savedTheme || typeof savedTheme !== 'object') return;

                const root = document.documentElement;

                const isValidColor = value =>
                    typeof value === 'string' &&
                    /^#[0-9a-f]{6}$/i.test(value);

                const getColor = (key, fallback) =>
                    isValidColor(savedTheme[key])
                        ? savedTheme[key]
                        : fallback;

                const colorOne = getColor('colorOne', '#e8f6ef');
                const colorTwo = getColor('colorTwo', '#eef2ff');
                const accent = getColor('accent', '#16845b');
                const surface = getColor('surface', '#ffffff');
                const input = getColor('input', '#ffffff');
                const table = getColor('table', '#f7f9fc');
                const border = getColor('border', '#e8edf3');
                const heading = getColor('heading', '#172b4d');
                const text = getColor('text', '#172b4d');
                const muted = getColor('muted', '#7b879b');

                const rawAngle = Number(savedTheme.angle);
                const angle = Number.isFinite(rawAngle)
                    ? Math.min(360, Math.max(0, rawAngle))
                    : 135;

                const background = savedTheme.mode === 'solid'
                    ? colorOne
                    : `linear-gradient(${angle}deg, ${colorOne}, ${colorTwo})`;

                const greenLight =
                    `color-mix(in srgb, ${accent} 12%, white)`;

                root.style.setProperty('--ff-page-background', background);
                root.style.setProperty('--ff-accent', accent);
                root.style.setProperty('--ff-surface', surface);
                root.style.setProperty('--ff-card-bg', surface);
                root.style.setProperty('--ff-input-bg', input);
                root.style.setProperty('--ff-table-head', table);
                root.style.setProperty('--ff-border', border);
                root.style.setProperty('--ff-heading', heading);
                root.style.setProperty('--ff-text', text);
                root.style.setProperty('--ff-muted', muted);
                root.style.setProperty('--ff-row-hover', table);

                root.style.setProperty('--page', background);
                root.style.setProperty('--surface', surface);
                root.style.setProperty('--text', text);
                root.style.setProperty('--muted', muted);
                root.style.setProperty('--border', border);
                root.style.setProperty('--green', accent);
                root.style.setProperty('--green-light', greenLight);

            } catch (error) {
                console.warn('FinanceFlow theme could not be loaded.', error);
            }
        })();
    </script>

    <style>
        :root {
            --ff-page-background: linear-gradient(135deg, #e8f6ef, #eef2ff);
            --ff-accent: #16845b;
            --ff-text: #172b4d;
            --ff-heading: #172b4d;
            --ff-muted: #7b879b;
            --ff-border: #e8edf3;
            --ff-surface: #ffffff;
            --ff-card-bg: rgba(255, 255, 255, 0.88);
            --ff-input-bg: #ffffff;
            --ff-table-head: #f7f9fc;
            --ff-row-hover: #f8fafc;

            --sidebar-width: 250px;

            --page: var(--ff-page-background);
            --surface: var(--ff-surface);
            --text: var(--ff-text);
            --muted: var(--ff-muted);
            --border: var(--ff-border);
            --green: var(--ff-accent);
            --green-light: color-mix(in srgb, var(--ff-accent) 12%, white);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--ff-page-background);
            color: var(--ff-text);
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
            transition: background .25s ease, color .25s ease;
        }

        body.menu-open {
            overflow: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button {
            color: inherit;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid var(--ff-accent);
            outline-offset: 3px;
        }

        /* Application shell */

        .app-shell {
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar */

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            height: 100vh;
            padding: 27px 17px 18px;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
            background: var(--ff-surface);
            border-right: 1px solid var(--ff-border);
            z-index: 100;
            transition:
                background .25s ease,
                border-color .25s ease,
                transform .22s ease;
        }

        /* Brand */

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px;
            margin-bottom: 34px;
            color: var(--ff-heading);
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -.8px;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: var(--ff-accent);
            color: #fff;
            font-size: 20px;
            font-weight: 700;
        }

        /* Navigation */

        .nav-section {
            margin-bottom: 20px;
        }

        .nav-label {
            padding: 0 12px;
            margin: 0 0 11px;
            color: var(--ff-muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .nav-list {
            display: grid;
            gap: 5px;
        }

        .nav-link {
            position: relative;
            min-height: 45px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 10px;
            color: var(--ff-muted);
            font-weight: 500;
            transition: background .18s ease, color .18s ease;
        }

        .nav-link:hover {
            background: var(--ff-row-hover);
            color: var(--ff-text);
        }

        .nav-link.active {
            background: var(--green-light);
            color: var(--ff-accent);
            font-weight: 700;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 10px;
            bottom: 10px;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: var(--ff-accent);
        }

        .nav-icon {
            width: 22px;
            flex-shrink: 0;
            display: inline-grid;
            place-items: center;
            font-size: 18px;
            line-height: 1;
        }

        .nav-link-text {
            min-width: 0;
        }

        .nav-link-create {
            margin-bottom: 27px;
            background: var(--ff-accent);
            color: #fff;
            font-weight: 600;
        }

        .nav-link-create:hover,
        .nav-link-create.active {
            background: var(--ff-accent);
            color: #fff;
            filter: brightness(.94);
        }

        .nav-link-create::before {
            display: none;
        }

        /* Sidebar account area */

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid var(--ff-border);
        }

        .user-card {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 8px 14px;
        }

        .avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border: 1px solid var(--ff-border);
            border-radius: 50%;
            background: var(--green-light);
            color: var(--ff-accent);
            font-size: 15px;
            font-weight: 700;
        }

        .user-details {
            min-width: 0;
        }

        .user-name {
            overflow-wrap: anywhere;
            color: var(--ff-heading);
            font-size: 13px;
            font-weight: 700;
        }

        .user-role {
            margin-top: 3px;
            color: var(--ff-muted);
            font-size: 11px;
        }

        .logout-button {
            width: 100%;
            min-height: 43px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 13px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: var(--ff-muted);
            cursor: pointer;
            text-align: left;
        }

        .logout-button:hover {
            background: #fff0f0;
            color: #c03939;
        }

        /* Main area - FIXED */

        .main-area {
            width: calc(100% - var(--sidebar-width));
            min-width: 0;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 38px;
            border-bottom: 1px solid var(--ff-border);
            background: var(--ff-surface);
        }

        .topbar-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .topbar-title {
            overflow: hidden;
            color: var(--ff-heading);
            font-size: 15px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .topbar-date {
            flex-shrink: 0;
            color: var(--ff-muted);
            font-size: 12px;
        }

        .main-content {
            width: 100%;
            min-width: 0;
            max-width: 1500px;
            margin: 0 auto;
            padding: 36px 38px 50px;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--ff-heading);
            font-size: clamp(25px, 3vw, 31px);
            font-weight: 700;
            letter-spacing: -1.1px;
            line-height: 1.2;
        }

        .page-heading p {
            margin: 8px 0 0;
            color: var(--ff-muted);
            line-height: 1.6;
        }

        /* Buttons */

        .btn-primary {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 17px;
            border: 0;
            border-radius: 9px;
            background: var(--ff-accent);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary:hover {
            filter: brightness(.92);
            transform: translateY(-1px);
        }

        /* Cards */

        .card {
            padding: 23px;
            border: 1px solid var(--ff-border);
            border-radius: 14px;
            background: var(--ff-card-bg);
            box-shadow: 0 3px 12px rgb(24 39 75 / 3%);
        }

        .card h2 {
            margin: 0;
            color: var(--ff-heading);
            font-size: 15px;
        }

        .muted {
            color: var(--ff-muted);
        }

        /* Messages */

        .flash-success {
            margin-bottom: 22px;
            padding: 14px 17px;
            border: 1px solid #ccebd9;
            border-radius: 10px;
            background: #effaf3;
            color: #176b46;
        }

        .error-message {
            margin-top: 6px;
            color: #c03939;
            font-size: 12px;
        }

        /* Form controls */

        input:not([type="checkbox"]):not([type="radio"]):not([type="color"]),
        select,
        textarea {
            color: var(--ff-text);
            background-color: var(--ff-input-bg);
            border-color: var(--ff-border);
        }

        input[type="color"] {
            cursor: pointer;
        }

        /* Mobile menu */

        .sidebar-overlay {
            display: none;
        }

        .mobile-menu-button {
            min-height: 38px;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 11px;
            border: 1px solid var(--ff-border);
            border-radius: 9px;
            background: var(--ff-surface);
            color: var(--ff-text);
            cursor: pointer;
        }

        .menu-icon {
            font-size: 18px;
            line-height: 1;
        }

        /* Responsive */

        @media (max-width: 1100px) {
            .topbar {
                padding-right: 24px;
                padding-left: 24px;
            }

            .main-content {
                padding-right: 24px;
                padding-left: 24px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: min(285px, 86vw);
                transform: translateX(-105%);
                box-shadow: 12px 0 35px rgb(24 39 75 / 8%);
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .main-area {
                width: 100%;
                margin-left: 0;
            }

            .mobile-menu-button {
                display: inline-flex;
            }

            .sidebar-overlay.is-visible {
                position: fixed;
                inset: 0;
                z-index: 90;
                display: block;
                background: rgb(15 23 42 / 35%);
                backdrop-filter: blur(2px);
            }

            .main-content {
                padding: 28px 22px 40px;
            }
        }

        @media (max-width: 560px) {
            .topbar {
                min-height: 66px;
                padding: 0 15px;
            }

            .topbar-left {
                gap: 10px;
            }

            .topbar-title {
                font-size: 14px;
            }

            .topbar-date {
                display: none;
            }

            .mobile-menu-button {
                min-height: 36px;
                padding: 0 9px;
                font-size: 12px;
            }

            .main-content {
                padding: 24px 15px 32px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-heading .btn-primary {
                width: 100%;
            }

            .card {
                padding: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-shell">

        <aside
            class="sidebar"
            id="appSidebar"
            aria-label="Application sidebar"
        >
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark" aria-hidden="true">F</span>
                <span>FinanceFlow</span>
            </a>

            <a
                href="{{ route('transactions.create') }}"
                class="nav-link nav-link-create {{ request()->routeIs('transactions.create') ? 'active' : '' }}"
                @if(request()->routeIs('transactions.create')) aria-current="page" @endif
            >
                <span class="nav-icon" aria-hidden="true">＋</span>
                <span class="nav-link-text">Add transaction</span>
            </a>

            <nav aria-label="Main navigation">
                <p class="nav-label">Workspace</p>

                <div class="nav-list">
                    <a
                        href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        @if(request()->routeIs('dashboard')) aria-current="page" @endif
                    >
                        <span class="nav-icon" aria-hidden="true">⌂</span>
                        <span class="nav-link-text">Dashboard</span>
                    </a>

                    <a
                        href="{{ route('transactions.index') }}"
                        class="nav-link {{ request()->routeIs('transactions.index', 'transactions.edit') ? 'active' : '' }}"
                        @if(request()->routeIs('transactions.index', 'transactions.edit')) aria-current="page" @endif
                    >
                        <span class="nav-icon" aria-hidden="true">⇄</span>
                        <span class="nav-link-text">Transactions</span>
                    </a>

                    <a
                        href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        @if(request()->routeIs('reports.*')) aria-current="page" @endif
                    >
                        <span class="nav-icon" aria-hidden="true">▥</span>
                        <span class="nav-link-text">Reports &amp; analytics</span>
                    </a>

                    <a
                        href="{{ route('budgets.index') }}"
                        class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}"
                        @if(request()->routeIs('budgets.*')) aria-current="page" @endif
                    >
                        <span class="nav-icon" aria-hidden="true">◷</span>
                        <span class="nav-link-text">Monthly Budgets</span>
                    </a>

                    <a
                        href="{{ route('settings') }}"
                        class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}"
                        @if(request()->routeIs('settings')) aria-current="page" @endif
                    >
                        <span class="nav-icon" aria-hidden="true">⚙</span>
                        <span class="nav-link-text">Settings</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-bottom">
                <div class="user-card">
                    <div class="avatar" aria-hidden="true">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="user-details">
                        <div class="user-name">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>

                        <div class="user-role">Personal account</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="logout-button">
                        <span class="nav-icon" aria-hidden="true">↪</span>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
        </aside>

        <button
            type="button"
            class="sidebar-overlay"
            id="sidebarOverlay"
            aria-label="Close navigation menu"
            tabindex="-1"
        ></button>

        <div class="main-area">
            <header class="topbar">
                <div class="topbar-left">
                    <button
                        type="button"
                        class="mobile-menu-button"
                        id="mobileMenuButton"
                        aria-controls="appSidebar"
                        aria-expanded="false"
                        aria-label="Open navigation menu"
                    >
                        <span class="menu-icon" aria-hidden="true">☰</span>
                        <span>Menu</span>
                    </button>

                    <span class="topbar-title">
                        @yield('topbar-title', 'Overview')
                    </span>
                </div>

                <time class="topbar-date" datetime="{{ now()->toDateString() }}">
                    {{ now()->format('l, d M Y') }}
                </time>
            </header>

            <main class="main-content" id="mainContent">
                @if (session('success'))
                    <div class="flash-success" role="status" aria-live="polite">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Mobile sidebar behavior --}}
    <script>
        (() => {
            const sidebar = document.getElementById('appSidebar');
            const menuButton = document.getElementById('mobileMenuButton');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar || !menuButton || !overlay) return;

            const mobileBreakpoint = 900;
            const isMobile = () => window.innerWidth <= mobileBreakpoint;

            const openMenu = () => {
                sidebar.classList.add('is-open');
                overlay.classList.add('is-visible');
                menuButton.setAttribute('aria-expanded', 'true');
                menuButton.setAttribute('aria-label', 'Close navigation menu');
                document.body.classList.add('menu-open');
            };

            const closeMenu = (returnFocus = false) => {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                menuButton.setAttribute('aria-expanded', 'false');
                menuButton.setAttribute('aria-label', 'Open navigation menu');
                document.body.classList.remove('menu-open');

                if (returnFocus && isMobile()) {
                    menuButton.focus();
                }
            };

            menuButton.addEventListener('click', () => {
                sidebar.classList.contains('is-open')
                    ? closeMenu()
                    : openMenu();
            });

            overlay.addEventListener('click', () => closeMenu(true));

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    closeMenu(true);
                }
            });

            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (isMobile()) closeMenu();
                });
            });

            window.addEventListener('resize', () => {
                if (!isMobile()) closeMenu();
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
