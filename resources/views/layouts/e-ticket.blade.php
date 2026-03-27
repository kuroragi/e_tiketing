<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="/assets/ticket.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Ticketing Layanan Kominfo Kota Bukittinggi')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* ══════════════════════════════════════════
           DESIGN TOKENS — LIGHT MODE
        ══════════════════════════════════════════ */
        :root,
        [data-theme="light"] {
            --primary:        #2563eb;
            --primary-dark:   #1d4ed8;
            --primary-light:  #eff6ff;
            --success:        #16a34a;
            --warning:        #d97706;
            --danger:         #dc2626;
            --info:           #0891b2;

            --bg-body:        #f1f5f9;
            --bg-card:        #ffffff;
            --bg-card-hover:  #f8fafc;
            --bg-input:       #ffffff;
            --border:         #e2e8f0;

            --text-primary:   #0f172a;
            --text-secondary: #64748b;
            --text-muted:     #94a3b8;
            --text-inverse:   #ffffff;

            --sidebar-bg:     #0f172a;
            --sidebar-hover:  rgba(255,255,255,0.08);
            --sidebar-active: rgba(37,99,235,0.85);
            --sidebar-text:   #94a3b8;
            --sidebar-active-text: #ffffff;
            --sidebar-label:  #475569;

            --navbar-bg:      #ffffff;
            --navbar-border:  #e2e8f0;

            --card-shadow:    0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
            --card-shadow-lg: 0 8px 25px rgba(0,0,0,.12);

            --toast-bg:       #1e293b;
            --toast-text:     #f8fafc;
        }

        /* ══════════════════════════════════════════
           DARK MODE TOKENS
        ══════════════════════════════════════════ */
        [data-theme="dark"] {
            --primary:        #3b82f6;
            --primary-dark:   #2563eb;
            --primary-light:  rgba(59,130,246,0.15);
            --success:        #22c55e;
            --warning:        #f59e0b;
            --danger:         #ef4444;
            --info:           #06b6d4;

            --bg-body:        #0f172a;
            --bg-card:        #1e293b;
            --bg-card-hover:  #263348;
            --bg-input:       #1e293b;
            --border:         #334155;

            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted:     #64748b;
            --text-inverse:   #0f172a;

            --sidebar-bg:     #020617;
            --sidebar-hover:  rgba(255,255,255,0.06);
            --sidebar-active: rgba(59,130,246,0.85);
            --sidebar-text:   #64748b;
            --sidebar-active-text: #ffffff;
            --sidebar-label:  #334155;

            --navbar-bg:      #1e293b;
            --navbar-border:  #334155;

            --card-shadow:    0 1px 3px rgba(0,0,0,.3), 0 1px 2px rgba(0,0,0,.2);
            --card-shadow-lg: 0 8px 25px rgba(0,0,0,.4);

            --toast-bg:       #0f172a;
            --toast-text:     #f1f5f9;
        }

        /* ══════════════════════════════════════════
           BASE
        ══════════════════════════════════════════ */
        *, *::before, *::after { transition: background-color .25s ease, border-color .25s ease, color .15s ease; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
        }

        /* ── NAVBAR ───────────────────────────────────────── */
        .navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 1px 0 var(--navbar-border), 0 2px 8px rgba(0,0,0,.05);
            padding: .6rem 1rem;
            min-height: 64px;
        }
        .navbar-brand {
            font-weight: 700; font-size: 1rem;
            color: var(--text-primary) !important; text-decoration: none;
        }
        .navbar-brand .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(37,99,235,.35);
        }
        .navbar-brand .brand-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .navbar-brand .brand-sub   { font-size: .68rem; color: var(--text-secondary); font-weight: 400; }

        /* Dark-mode toggle button */
        .theme-toggle {
            width: 36px; height: 36px;
            border-radius: 50%; border: 1px solid var(--border);
            background: var(--bg-body);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1rem;
            transition: all .2s;
        }
        .theme-toggle:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }

        /* User button */
        .nav-user-btn {
            display: flex; align-items: center; gap: .6rem;
            background: var(--bg-body); border: 1px solid var(--border);
            border-radius: 50px; padding: .35rem .9rem .35rem .35rem;
            color: var(--text-primary); text-decoration: none;
            transition: all .2s; font-size: .875rem; font-weight: 500;
        }
        .nav-user-btn:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }
        .nav-user-btn .user-role-badge {
            font-size: .65rem; background: var(--primary); color: #fff;
            border-radius: 4px; padding: 1px 6px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .03em;
        }

        /* Dropdown dark */
        .dropdown-menu {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }
        .dropdown-item { color: var(--text-primary) !important; }
        .dropdown-item:hover { background: var(--primary-light) !important; color: var(--primary) !important; }
        .dropdown-divider { border-color: var(--border) !important; }

        /* ── SIDEBAR ──────────────────────────────────────── */
        .sidebar {
            background: var(--sidebar-bg); width: 100%;
            display: flex; flex-direction: column;
        }
        .sidebar-inner {
            padding: 1rem .75rem; flex: 1; overflow-y: auto;
        }
        .sidebar-inner::-webkit-scrollbar { width: 4px; }
        .sidebar-inner::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

        .sidebar-section-label {
            font-size: .65rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--sidebar-label);
            padding: .5rem .75rem .4rem; margin-top: .5rem;
        }
        .sidebar .nav-link {
            color: var(--sidebar-text); padding: .55rem .85rem;
            border-radius: .5rem; margin: .1rem 0;
            transition: all .18s ease;
            display: flex; align-items: center; gap: .6rem;
            font-size: .875rem; font-weight: 500;
        }
        .sidebar .nav-link i { font-size: 1rem; width: 1.2rem; text-align: center; flex-shrink: 0; opacity: .75; }
        .sidebar .nav-link:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .sidebar .nav-link:hover i { opacity: 1; }
        .sidebar .nav-link.active {
            background: var(--sidebar-active); color: var(--sidebar-active-text);
            box-shadow: 0 2px 8px rgba(37,99,235,.3);
        }
        .sidebar .nav-link.active i { opacity: 1; }
        .sidebar .nav-link .link-label { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .sidebar-user-card {
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
            border-radius: .75rem; padding: .85rem; margin-bottom: 1rem;
            display: flex; align-items: center; gap: .75rem;
        }
        .sidebar-user-card .avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: .9rem; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-user-card .user-name {
            font-size: .85rem; font-weight: 600; color: #e2e8f0;
            line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user-card .user-role {
            font-size: .68rem; font-weight: 600; color: #7dd3fc;
            text-transform: uppercase; letter-spacing: .05em;
        }

        /* ── MAIN CONTENT ─────────────────────────────────── */
        .main-content {
            background-color: var(--bg-body);
            min-height: calc(100vh - 64px); padding: 1.5rem;
        }

        /* ── CARDS ────────────────────────────────────────── */
        .card {
            border: 1px solid var(--border); border-radius: .75rem;
            box-shadow: var(--card-shadow);
            transition: box-shadow .2s ease, transform .2s ease;
            background: var(--bg-card);
        }
        .card:hover { box-shadow: var(--card-shadow-lg); transform: translateY(-1px); }
        .card-header {
            background: var(--bg-card); border-bottom: 1px solid var(--border);
            border-radius: .75rem .75rem 0 0 !important; padding: 1rem 1.25rem;
        }
        .card-body { color: var(--text-primary); }
        .card.card-primary   { border-top: 3px solid var(--primary); }
        .card.card-success   { border-top: 3px solid var(--success); }
        .card.card-warning   { border-top: 3px solid var(--warning); }
        .card.card-danger    { border-top: 3px solid var(--danger); }
        .card.card-info      { border-top: 3px solid var(--info); }

        /* ── BUTTONS ──────────────────────────────────────── */
        .btn-primary { background-color: var(--primary); border-color: var(--primary); font-weight: 500; }
        .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); }

        /* ── TABLE ────────────────────────────────────────── */
        .table { color: var(--text-primary); }
        .table th { color: var(--text-secondary); border-color: var(--border); }
        .table td { border-color: var(--border); }
        .table-hover tbody tr:hover { background-color: var(--bg-card-hover); }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.02); }
        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(255,255,255,.03); }

        /* ── FORMS ────────────────────────────────────────── */
        .form-control, .form-select {
            background-color: var(--bg-input); border-color: var(--border);
            color: var(--text-primary);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--bg-input); color: var(--text-primary);
            border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(37,99,235,.15);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { color: var(--text-primary); font-weight: 500; }
        .input-group-text { background: var(--bg-body); border-color: var(--border); color: var(--text-secondary); }

        /* ── MODALS ───────────────────────────────────────── */
        .modal-content { background: var(--bg-card); border-color: var(--border); color: var(--text-primary); }
        .modal-header { border-bottom-color: var(--border); }
        .modal-footer { border-top-color: var(--border); }

        /* ── BADGES / STATUS ──────────────────────────────── */
        .status-badge {
            font-size: .72rem; padding: .3rem .75rem; border-radius: 50px;
            font-weight: 600; letter-spacing: .02em; display: inline-block;
        }
        .status-baru               { background: #fef9c3; color: #854d0e; }
        .status-diproses           { background: #dbeafe; color: #1e40af; }
        .status-selesai            { background: #dcfce7; color: #15803d; }
        .status-ditolak            { background: #fee2e2; color: #b91c1c; }
        .status-dibatalkan         { background: #f3f4f6; color: #374151; }
        .status-menunggu_verifikasi{ background: #fff7ed; color: #c2410c; }
        [data-theme="dark"] .status-baru               { background: rgba(234,179,8,.2);   color: #fef08a; }
        [data-theme="dark"] .status-diproses           { background: rgba(59,130,246,.2);  color: #93c5fd; }
        [data-theme="dark"] .status-selesai            { background: rgba(34,197,94,.2);   color: #86efac; }
        [data-theme="dark"] .status-ditolak            { background: rgba(239,68,68,.2);   color: #fca5a5; }
        [data-theme="dark"] .status-dibatalkan         { background: rgba(100,116,139,.2); color: #cbd5e1; }
        [data-theme="dark"] .status-menunggu_verifikasi{ background: rgba(249,115,22,.2);  color: #fdba74; }

        /* SLA Overdue badge */
        .badge-overdue {
            font-size: .68rem; padding: .25rem .6rem; border-radius: 50px;
            font-weight: 700; background: rgba(220,38,38,.1); color: #dc2626;
            border: 1px solid rgba(220,38,38,.3); animation: pulse-red 2s infinite;
        }
        [data-theme="dark"] .badge-overdue { background: rgba(239,68,68,.2); color: #fca5a5; border-color: rgba(239,68,68,.3); }
        @keyframes pulse-red {
            0%,100% { box-shadow: 0 0 0 0 rgba(220,38,38,.3); }
            50%      { box-shadow: 0 0 0 4px rgba(220,38,38,0); }
        }

        /* SLA progress bar */
        .sla-progress { height: 6px; border-radius: 50px; overflow: hidden; }
        .sla-progress .bar-ok       { background: linear-gradient(90deg, #22c55e, #16a34a); }
        .sla-progress .bar-warning  { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .sla-progress .bar-danger   { background: linear-gradient(90deg, #ef4444, #dc2626); }

        /* ── PAGE HEADER ──────────────────────────────────── */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            color: #fff; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
            border-radius: .75rem; box-shadow: 0 4px 15px rgba(37,99,235,.25);
        }

        /* ── STATS CARD ───────────────────────────────────── */
        .stats-card { text-align: center; padding: 1.5rem; }
        .stats-card .stats-number {
            font-size: 2rem; font-weight: 700; margin-bottom: .25rem;
            background: linear-gradient(135deg, currentColor, currentColor);
        }
        .stats-card .stats-label { font-size: .8rem; color: var(--text-secondary); margin-bottom: 0; font-weight: 500; }

        /* Animated counter */
        .stats-number[data-count] { transition: none; }

        /* ── TICKET ROWS ──────────────────────────────────── */
        .ticket-card { transition: all .2s ease; cursor: pointer; }
        .ticket-card:hover { background-color: var(--primary-light); }
        .ticket-row-hover:hover { background-color: var(--bg-card-hover) !important; }

        /* ── USER AVATAR ──────────────────────────────────── */
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; flex-shrink: 0;
        }

        /* ── BREADCRUMB ───────────────────────────────────── */
        .breadcrumb { background: transparent; margin-bottom: 0; padding: 0; font-size: .8rem; }
        .breadcrumb-item a { color: var(--text-secondary); text-decoration: none; }
        .breadcrumb-item a:hover { color: var(--primary); }
        .breadcrumb-item.active { color: var(--text-muted); }
        .breadcrumb-item+.breadcrumb-item::before { color: var(--text-muted); }

        /* ── FOOTER ───────────────────────────────────────── */
        .footer {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8; padding: 2rem 0 1rem; margin-top: auto;
        }
        .footer h6 { color: #e2e8f0; font-weight: 600; }
        .footer a.text-light:hover { color: #7dd3fc !important; }

        /* ── TOAST NOTIFICATION SYSTEM ────────────────────── */
        #toast-container {
            position: fixed; bottom: 1.5rem; right: 1.5rem;
            z-index: 9999; display: flex; flex-direction: column; gap: .65rem;
            max-width: 380px; width: calc(100vw - 2rem);
        }
        .toast-item {
            background: var(--toast-bg); color: var(--toast-text);
            border-radius: .75rem; padding: .9rem 1rem;
            display: flex; align-items: flex-start; gap: .75rem;
            box-shadow: 0 8px 30px rgba(0,0,0,.25);
            animation: slideUp .35s cubic-bezier(.22,.61,.36,1) forwards;
            overflow: hidden; position: relative;
        }
        .toast-item.hiding {
            animation: slideDown .3s ease forwards;
        }
        .toast-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: .95rem;
        }
        .toast-success .toast-icon { background: rgba(34,197,94,.2);  color: #22c55e; }
        .toast-error   .toast-icon { background: rgba(239,68,68,.2);  color: #ef4444; }
        .toast-warning .toast-icon { background: rgba(245,158,11,.2); color: #f59e0b; }
        .toast-info    .toast-icon { background: rgba(59,130,246,.2); color: #3b82f6; }
        .toast-body { flex: 1; font-size: .875rem; font-weight: 500; padding-top: .18rem; }
        .toast-close {
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; font-size: 1.1rem; padding: 0; line-height: 1;
            flex-shrink: 0;
        }
        .toast-close:hover { color: var(--toast-text); }
        .toast-progress {
            position: absolute; bottom: 0; left: 0; height: 3px; border-radius: 0 0 .75rem .75rem;
            animation: shrink 5s linear forwards;
        }
        .toast-success .toast-progress { background: #22c55e; }
        .toast-error   .toast-progress { background: #ef4444; }
        .toast-warning .toast-progress { background: #f59e0b; }
        .toast-info    .toast-progress { background: #3b82f6; }

        @keyframes slideUp   { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideDown { from { opacity:1; transform:translateY(0); } to { opacity:0; transform:translateY(20px); } }
        @keyframes shrink    { from { width: 100%; } to { width: 0%; } }

        /* ── MISC ─────────────────────────────────────────── */
        .alert { border: none; border-radius: .65rem; font-size: .875rem; }
        [data-theme="dark"] .alert-success { background: rgba(34,197,94,.15); color: #86efac; }
        [data-theme="dark"] .alert-danger  { background: rgba(239,68,68,.15); color: #fca5a5; }
        [data-theme="dark"] .alert-warning { background: rgba(245,158,11,.15); color: #fde68a; }
        [data-theme="dark"] .alert-info    { background: rgba(6,182,212,.15);  color: #67e8f9; }

        .text-muted { color: var(--text-muted) !important; }
        .border { border-color: var(--border) !important; }
        .border-bottom { border-bottom-color: var(--border) !important; }
        hr { border-color: var(--border); }

        /* ── CHART CANVAS DARK ────────────────────────────── */
        [data-theme="dark"] canvas { filter: brightness(.95); }

        /* ── PRIORITY COLORS ──────────────────────────────── */
        .priority-tinggi { color: var(--danger); }
        .priority-sedang { color: var(--warning); }
        .priority-rendah { color: var(--success); }
    </style>

    @stack('styles')
</head>

<body class="d-flex flex-column">
    <!-- ═══════════════ NAVBAR ═══════════════ -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <div class="brand-icon"><i class="bi bi-ticket-perforated"></i></div>
                <div class="brand-text">
                    <div class="brand-title">e-Tiket Kominfo</div>
                    <div class="brand-sub">Kota Bukittinggi</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <button class="theme-toggle" id="themeToggle" title="Ganti tema" data-bs-toggle="tooltip">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a class="nav-user-btn dropdown-toggle" href="#" id="navbarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar" style="width:30px;height:30px;font-size:.7rem;">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'User' }}</span>
                            <span class="nav-user-btn user-role-badge d-none d-md-inline">{{ auth()->user()->role ?? 'user' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-1" style="min-width:220px;">
                            <li class="px-3 py-2">
                                <div class="fw-semibold" style="font-size:.875rem;">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">{{ auth()->user()->email }}</div>
                                @if(auth()->user()->department)
                                    <div class="mt-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.65rem;">
                                            {{ auth()->user()->department->name }}
                                        </span>
                                    </div>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mx-1">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-2 text-danger" style="width:auto;">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ═══════════════ LAYOUT ═══════════════ -->
    <div class="container-fluid flex-grow-1 px-0">
        <div class="row g-0 h-100">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 d-flex flex-column">
                <div class="sidebar flex-grow-1">
                    <div class="sidebar-inner">

                        <!-- User Card -->
                        <div class="sidebar-user-card">
                            <div class="avatar">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                            <div style="min-width:0;">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-role">{{ auth()->user()->role ?? 'user' }}</div>
                            </div>
                        </div>

                        <div class="sidebar-section-label">Menu Utama</div>
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i><span class="link-label">Dashboard</span>
                            </a>

                            @if(auth()->user()->isSkpd())
                                <a class="nav-link {{ request()->routeIs('tiket.saya') ? 'active' : '' }}"
                                    href="{{ route('tiket.saya') }}">
                                    <i class="bi bi-ticket"></i><span class="link-label">Tiket Saya</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('tiket.create') ? 'active' : '' }}"
                                    href="{{ route('tiket.create') }}">
                                    <i class="bi bi-plus-circle"></i><span class="link-label">Buat Tiket</span>
                                </a>
                            @endif

                            @if(!auth()->user()->isSkpd())
                                <a class="nav-link {{ request()->routeIs('tiket.index') ? 'active' : '' }}"
                                    href="{{ route('tiket.index') }}">
                                    <i class="bi bi-list-check"></i><span class="link-label">Daftar Tiket</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}"
                                    href="{{ route('laporan.index') }}">
                                    <i class="bi bi-bar-chart-line"></i><span class="link-label">Laporan</span>
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                                <a class="nav-link {{ request()->routeIs('ticket.management.*') ? 'active' : '' }}"
                                    href="{{ route('ticket.management.index') }}">
                                    <i class="bi bi-shuffle"></i><span class="link-label">Manajemen Tiket</span>
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <div class="sidebar-section-label mt-2">Administrasi</div>
                                <a class="nav-link {{ request()->routeIs('admin.pengguna') ? 'active' : '' }}"
                                    href="{{ route('admin.pengguna') }}">
                                    <i class="bi bi-people"></i><span class="link-label">Pengguna</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.skpd') ? 'active' : '' }}"
                                    href="{{ route('admin.skpd') }}">
                                    <i class="bi bi-building"></i><span class="link-label">Data SKPD</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.jenis-pekerjaan') ? 'active' : '' }}"
                                    href="{{ route('admin.jenis-pekerjaan') }}">
                                    <i class="bi bi-tags"></i><span class="link-label">Jenis Pekerjaan</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}"
                                    href="{{ route('admin.pengaturan') }}">
                                    <i class="bi bi-gear"></i><span class="link-label">Pengaturan</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.log-aktivitas') ? 'active' : '' }}"
                                    href="{{ route('admin.log-aktivitas') }}">
                                    <i class="bi bi-clock-history"></i><span class="link-label">Log Aktivitas</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"
                                    href="{{ route('admin.laporan') }}">
                                    <i class="bi bi-bar-chart"></i><span class="link-label">Laporan Admin</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                                    href="{{ route('admin.roles.index') }}">
                                    <i class="bi bi-shield-lock"></i><span class="link-label">Manajemen Role</span>
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                                    href="{{ route('admin.permissions.index') }}">
                                    <i class="bi bi-key"></i><span class="link-label">Permission</span>
                                </a>
                            @endif

                            <div class="sidebar-section-label mt-2">Informasi</div>
                            <a class="nav-link {{ request()->routeIs('panduan') ? 'active' : '' }}" href="{{ route('panduan') }}">
                                <i class="bi bi-book"></i><span class="link-label">Panduan</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">
                                <i class="bi bi-info-circle"></i><span class="link-label">Tentang Sistem</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('hubungi') ? 'active' : '' }}" href="{{ route('hubungi') }}">
                                <i class="bi bi-headset"></i><span class="link-label">Hubungi Kami</span>
                            </a>
                        </nav>

                        <!-- Logout shortcut -->
                        <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.07);">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent" style="color:#f87171;">
                                    <i class="bi bi-box-arrow-left"></i><span class="link-label">Keluar</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Breadcrumb -->
                    @if(!request()->routeIs('dashboard'))
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif

                    <!-- Page Header -->
                    @hasSection('page-header')
                        <div class="page-header">
                            <div class="container-fluid">@yield('page-header')</div>
                        </div>
                    @endif

                    <!-- Main Content Area -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════ FOOTER ═══════════════ -->
    <footer class="footer">
        <div class="container-fluid px-4">
            <div class="row g-4 mb-3">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:32px;height:32px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-ticket-perforated text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.85rem;font-weight:700;color:#e2e8f0;">e-Tiket Kominfo</div>
                            <div style="font-size:.7rem;color:#64748b;">Kota Bukittinggi</div>
                        </div>
                    </div>
                    <p class="small mb-0" style="color:#64748b;line-height:1.6;">Sistem Ticketing Layanan Dinas Komunikasi dan Informatika Kota Bukittinggi.</p>
                </div>
                <div class="col-md-2">
                    <h6 class="mb-3" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;">Navigasi</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="{{ route('dashboard') }}" class="text-light text-decoration-none small"><i class="bi bi-house me-1 opacity-50"></i>Dashboard</a></li>
                        <li class="mb-1"><a href="{{ route('panduan') }}" class="text-light text-decoration-none small"><i class="bi bi-book me-1 opacity-50"></i>Panduan</a></li>
                        <li class="mb-1"><a href="{{ route('tentang') }}" class="text-light text-decoration-none small"><i class="bi bi-info-circle me-1 opacity-50"></i>Tentang</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="mb-3" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;">Bantuan</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="{{ route('hubungi') }}" class="text-light text-decoration-none small"><i class="bi bi-headset me-1 opacity-50"></i>Hubungi Kami</a></li>
                        <li class="mb-1"><a href="{{ route('kebijakan') }}" class="text-light text-decoration-none small"><i class="bi bi-shield-check me-1 opacity-50"></i>Kebijakan</a></li>
                        <li class="mb-1"><a href="{{ route('syarat-ketentuan') }}" class="text-light text-decoration-none small"><i class="bi bi-file-text me-1 opacity-50"></i>Syarat &amp; Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;">Kontak</h6>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:28px;height:28px;background:rgba(255,255,255,.08);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-telephone text-white" style="font-size:.75rem;"></i>
                        </span>
                        <span class="small" style="color:#94a3b8;">(0752) 123-4567</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:28px;height:28px;background:rgba(255,255,255,.08);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-envelope text-white" style="font-size:.75rem;"></i>
                        </span>
                        <span class="small" style="color:#94a3b8;">kominfo@bukittinggi.go.id</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:28px;height:28px;background:rgba(255,255,255,.08);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock text-white" style="font-size:.75rem;"></i>
                        </span>
                        <span class="small" style="color:#94a3b8;">Senin &ndash; Jumat, 08.00 &ndash; 17.00</span>
                    </div>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,.08);margin:1rem 0 .75rem;">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <p class="mb-0 small" style="color:#475569;">&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Bukittinggi.</p>
                <span class="small" style="color:#334155;">Sistem Ticketing Layanan v2.0</span>
            </div>
        </div>
    </footer>

    <!-- ═══════════════ TOAST CONTAINER ═══════════════ -->
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <!-- Modal: Akses Ditolak -->
    @if(session('forbidden'))
        <div class="modal fade" id="forbiddenModal" tabindex="-1" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-shield-exclamation me-2"></i>Akses Ditolak</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3"><i class="bi bi-lock-fill text-danger" style="font-size:3rem;"></i></div>
                        <p class="mb-0">{{ session('forbidden') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                            <i class="bi bi-check2 me-1"></i>Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    /* ═══════════════════════════════════
       DARK MODE SYSTEM
    ═══════════════════════════════════ */
    (function () {
        const html    = document.documentElement;
        const toggle  = document.getElementById('themeToggle');
        const icon    = document.getElementById('themeIcon');
        const KEY     = 'etiket_theme';

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            }
            localStorage.setItem(KEY, theme);
        }

        // Restore saved theme
        const saved = localStorage.getItem(KEY) || 'light';
        applyTheme(saved);

        if (toggle) {
            toggle.addEventListener('click', function () {
                const current = html.getAttribute('data-theme');
                applyTheme(current === 'dark' ? 'light' : 'dark');
            });
        }
    })();

    /* ═══════════════════════════════════
       TOAST NOTIFICATION SYSTEM
    ═══════════════════════════════════ */
    window.showToast = function(message, type = 'info', duration = 5000) {
        const container = document.getElementById('toast-container');
        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-exclamation-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info:    'bi-info-circle-fill',
        };

        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon"><i class="bi ${icons[type] || icons.info}"></i></div>
            <div class="toast-body">${message}</div>
            <button class="toast-close" aria-label="Tutup">×</button>
            <div class="toast-progress" style="animation-duration:${duration}ms"></div>
        `;

        container.appendChild(toast);

        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));

        // Auto dismiss
        const timer = setTimeout(() => dismissToast(toast), duration);

        function dismissToast(el) {
            clearTimeout(timer);
            el.classList.add('hiding');
            el.addEventListener('animationend', () => el.remove(), { once: true });
        }
    };

    /* ─── Flash Messages → Toast ─────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif
        @if(session('status'))
            showToast(@json(session('status')), 'info');
        @endif
        @if($errors->any())
            const errs = @json($errors->all());
            errs.forEach(msg => showToast(msg, 'error'));
        @endif

        // Tooltip init
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

        // Forbidden modal
        @if(session('forbidden'))
            new bootstrap.Modal(document.getElementById('forbiddenModal')).show();
        @endif

        // Animated counters
        document.querySelectorAll('.stats-number[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count, 10);
            const duration = 1200;
            const step = Math.ceil(target / (duration / 16));
            let current = 0;
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current.toLocaleString('id-ID');
                if (current >= target) clearInterval(timer);
            }, 16);
        });
    });

    // Auto refresh dashboard every 5 minutes
    setInterval(() => {
        if (window.location.pathname === '/dashboard') location.reload();
    }, 300000);
    </script>

    @stack('scripts')
</body>
</html>
