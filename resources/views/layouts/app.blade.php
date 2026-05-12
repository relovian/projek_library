<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIARSIP') — Bawaslu RI</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* ── VARIABEL ── */
        :root {
            --bawaslu-red: #C0272D;
            --bawaslu-dark-red: #8B1A1F;
            --bawaslu-gold: #D4A843;
            --bg: #F7F5F2;
            --surface: #FFFFFF;
            --surface2: #F0EDE8;
            --text: #1A1714;
            --text-muted: #6B6560;
            --border: #E2DDD8;
            --sidebar-w: 260px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bawaslu-dark-red);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 42px; height: 42px;
            background: var(--bawaslu-gold);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .brand-text { color: #fff; }
        .brand-text .brand-name { font-size: 15px; font-weight: 700; }
        .brand-text .brand-sub { font-size: 10.5px; color: rgba(255,255,255,.55); margin-top: 1px; letter-spacing: .5px; text-transform: uppercase; }

        .sidebar-user {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-info .user-name { color: #fff; font-size: 13px; font-weight: 600; }
        .user-info .user-role {
            font-size: 10px;
            color: rgba(255,255,255,.5);
            background: rgba(255,255,255,.1);
            padding: 1px 7px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; }

        .nav-group-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
            padding: 8px 8px 6px;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background .2s;
            margin-bottom: 2px;
            color: rgba(255,255,255,.7);
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-item:hover { background: rgba(255,255,255,.1); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.18); color: #fff; font-weight: 600; }
        .nav-icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto;
            font-size: 9px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-admin { background: rgba(192,39,45,.35); color: #ff9a9a; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.5);
            font-size: 13px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background .2s, color .2s;
            background: none;
            border: none;
            width: 100%;
            font-family: inherit;
        }

        .logout-btn:hover { background: rgba(255,255,255,.08); color: rgba(255,255,255,.8); }

        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-breadcrumb { font-size: 12.5px; color: var(--text-muted); }
        .page-breadcrumb span { color: var(--text); font-weight: 600; }

        .topbar-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 7px 14px;
            width: 280px;
        }

        .topbar-search input { border: none; background: transparent; outline: none; font-size: 13px; font-family: inherit; color: var(--text); width: 100%; }
        .topbar-search input::placeholder { color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            position: relative;
        }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: var(--bawaslu-red);
            border-radius: 50%;
            border: 1.5px solid white;
        }

        /* ── CONTENT ── */
        .content { padding: 32px; flex: 1; }

        /* ── ALERT ── */
        .alert {
            padding: 12px 24px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background: #ECFDF5; color: #059669; border-bottom: 1px solid #A7F3D0; }
        .alert-error   { background: #FEF2F2; color: #DC2626; border-bottom: 1px solid #FECACA; }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--text); margin-bottom: 4px; }
        .page-header p  { color: var(--text-muted); font-size: 14px; }

        /* ── STAT CARDS ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.c1::before { background: var(--bawaslu-red); }
        .stat-card.c2::before { background: var(--bawaslu-gold); }
        .stat-card.c3::before { background: #3B82F6; }
        .stat-card.c4::before { background: #10B981; }

        .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 14px; }
        .stat-card.c1 .stat-icon { background: #FEF2F2; }
        .stat-card.c2 .stat-icon { background: #FFFBEB; }
        .stat-card.c3 .stat-icon { background: #EFF6FF; }
        .stat-card.c4 .stat-icon { background: #ECFDF5; }

        .stat-number { font-size: 30px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-label  { font-size: 12.5px; color: var(--text-muted); margin-top: 6px; font-weight: 500; }
        .stat-change { display: inline-flex; align-items: center; gap: 3px; font-size: 11px; font-weight: 600; margin-top: 8px; padding: 2px 8px; border-radius: 20px; }
        .stat-change.up   { color: #059669; background: #ECFDF5; }
        .stat-change.pend { color: #D97706; background: #FFFBEB; }

        /* ── CARDS ── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 24px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .card-title  { font-size: 15px; font-weight: 700; }
        .card-sub    { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .view-all    { font-size: 12px; color: var(--bawaslu-red); font-weight: 600; cursor: pointer; text-decoration: none; }

        /* ── DASH GRID ── */
        .dash-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }

        /* ── DOC LIST ── */
        .doc-list { list-style: none; }
        .doc-item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--border); cursor: pointer; }
        .doc-item:last-child { border-bottom: none; }
        .doc-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .doc-icon.pdf  { background: #FEF2F2; }
        .doc-icon.docx, .doc-icon.doc { background: #EFF6FF; }
        .doc-icon.xlsx, .doc-icon.xls { background: #ECFDF5; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doc-meta { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        /* ── STATUS BADGE ── */
        .doc-status { font-size: 10.5px; font-weight: 700; padding: 3px 9px; border-radius: 20px; flex-shrink: 0; }
        .status-green  { background: #ECFDF5; color: #059669; }
        .status-yellow { background: #FFFBEB; color: #D97706; }
        .status-blue   { background: #EFF6FF; color: #2563EB; }
        .status-gray   { background: #F5F5F5; color: #6B7280; }
        .status-red    { background: #FEF2F2; color: #DC2626; }

        /* ── PENDING LIST ── */
        .pending-list { list-style: none; }
        .pending-item { padding: 12px 0; border-bottom: 1px solid var(--border); }
        .pending-item:last-child { border-bottom: none; }
        .pending-item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
        .pending-doc-name { font-size: 13px; font-weight: 600; }
        .pending-time { font-size: 11px; color: var(--text-muted); }
        .pending-submitter { font-size: 12px; color: var(--text-muted); }
        .pending-actions { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; }

        /* ── BUTTONS ── */
        .btn-primary { padding: 8px 18px; background: var(--bawaslu-red); color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 6px; transition: background .2s; text-decoration: none; }
        .btn-primary:hover { background: var(--bawaslu-dark-red); }
        .btn-sm { padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; font-family: inherit; transition: opacity .2s; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-sm:hover { opacity: .85; }
        .btn-approve { background: #059669; color: #fff; }
        .btn-reject  { background: var(--surface2); color: var(--text-muted); border: 1px solid var(--border); }
        .btn-view    { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th { text-align: left; padding: 11px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--text-muted); background: var(--surface2); border-bottom: 1px solid var(--border); }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; cursor: pointer; }
        tbody tr:hover { background: var(--surface2); }
        tbody tr:last-child { border-bottom: none; }
        tbody td { padding: 12px 14px; }

        .doc-thumb { display: flex; align-items: center; gap: 10px; }
        .doc-thumb-icon { width: 32px; height: 32px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .doc-thumb-name { font-weight: 600; font-size: 13px; }
        .doc-thumb-id   { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

        .category-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: var(--surface2); color: var(--text-muted); border: 1px solid var(--border); }

        .action-btns { display: flex; gap: 5px; }
        .tbl-btn { width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--border); background: var(--surface); cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center; transition: background .15s; text-decoration: none; }
        .tbl-btn:hover { background: var(--surface2); }

        /* ── FORM ── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 7px; color: var(--text); }
        .form-label .required { color: var(--bawaslu-red); }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 9px 13px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; font-family: inherit; background: var(--surface); color: var(--text); outline: none; transition: border-color .2s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--bawaslu-red); box-shadow: 0 0 0 3px rgba(192,39,45,.08); }
        .form-textarea { resize: vertical; min-height: 90px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /* ── FILTER ── */
        .filter-row { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-select, .filter-input { padding: 8px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-family: inherit; background: var(--surface); color: var(--text); outline: none; cursor: pointer; }
        .filter-input { width: 220px; }

        /* ── TABS ── */
        .tab-row { display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid var(--border); }
        .tab-btn { padding: 10px 18px; font-size: 13.5px; font-weight: 600; cursor: pointer; color: var(--text-muted); border: none; background: transparent; font-family: inherit; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color .2s; text-decoration: none; display: inline-block; }
        .tab-btn.active { color: var(--bawaslu-red); border-bottom-color: var(--bawaslu-red); }
        .tab-btn:hover { color: var(--text); }

        /* ── TIMELINE ── */
        .timeline { list-style: none; }
        .tl-item { display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border); align-items: flex-start; }
        .tl-item:last-child { border-bottom: none; }
        .tl-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; margin-top: 2px; }
        .tl-dot.upload   { background: #ECFDF5; }
        .tl-dot.download { background: #EFF6FF; }
        .tl-dot.approve  { background: #F0FDF4; }
        .tl-dot.edit     { background: #FFFBEB; }
        .tl-dot.reject   { background: #FEF2F2; }
        .tl-action { font-size: 13.5px; font-weight: 600; }
        .tl-doc    { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }
        .tl-time   { font-size: 11.5px; color: var(--text-muted); margin-top: 4px; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 200; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border-radius: 16px; padding: 32px; max-width: 520px; width: 90%; }
        .modal-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .modal-title { font-size: 18px; font-weight: 700; }
        .modal-close { font-size: 20px; cursor: pointer; color: var(--text-muted); background: none; border: none; }

        /* ── PAGINATION ── */
        .pagination { display: flex; align-items: center; gap: 4px; margin-top: 20px; justify-content: flex-end; }
        .page-btn { width: 32px; height: 32px; border-radius: 7px; border: 1px solid var(--border); background: var(--surface); cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center; font-family: inherit; font-weight: 600; color: var(--text-muted); }
        .page-btn.active { background: var(--bawaslu-red); color: #fff; border-color: var(--bawaslu-red); }
        .page-btn:hover:not(.active) { background: var(--surface2); }

        /* ── UPLOAD ZONE ── */
        .upload-zone { border: 2px dashed var(--border); border-radius: 14px; padding: 48px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; background: var(--surface2); }
        .upload-zone:hover { border-color: var(--bawaslu-red); background: #FEF2F2; }
        .upload-zone-icon { font-size: 48px; margin-bottom: 16px; }
        .upload-zone h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
        .upload-zone p  { font-size: 13px; color: var(--text-muted); }
        .upload-formats { display: flex; gap: 8px; justify-content: center; margin-top: 16px; flex-wrap: wrap; }
        .format-tag { padding: 3px 10px; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--text-muted); }

        /* ── EMPTY STATE ── */
        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .empty-state .empty-icon { font-size: 40px; margin-bottom: 10px; }
        .empty-state p { font-size: 14px; }

        /* ── SETTINGS ── */
        .settings-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .settings-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 22px; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
        .settings-card:hover { border-color: var(--bawaslu-red); box-shadow: 0 4px 16px rgba(192,39,45,.08); }
        .settings-icon { font-size: 28px; margin-bottom: 12px; }
        .settings-card h3 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .settings-card p  { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; }
        .admin-lock { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; background: #FEF2F2; color: var(--bawaslu-red); margin-top: 8px; }

        /* ── UPLOAD LAYOUT ── */
        .upload-layout { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }

        @media (max-width: 1100px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-grid { grid-template-columns: 1fr; }
            .settings-grid { grid-template-columns: repeat(2, 1fr); }
            .upload-layout { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">B</div>
        <div class="brand-text">
            <div class="brand-name">SIARSIP Bawaslu</div>
            <div class="brand-sub">Sistem Informasi Arsip</div>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">{{ auth()->user()->inisial }}</div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->nama_lengkap }}</div>
            <div class="user-role">{{ auth()->user()->role_label }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Utama</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <a href="{{ route('arsip.index') }}" class="nav-item {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
            <span class="nav-icon">🗂️</span> Arsip
        </a>

        <a href="{{ route('unggah.create') }}" class="nav-item {{ request()->routeIs('unggah.*') ? 'active' : '' }}">
            <span class="nav-icon">⬆️</span> Unggah
        </a>

        <div class="nav-group-label">Manajemen</div>

        <a href="{{ route('aktivitas.index') }}" class="nav-item {{ request()->routeIs('aktivitas.*') ? 'active' : '' }}">
            <span class="nav-icon">📋</span> Aktivitas
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
        <a href="{{ route('persetujuan.index') }}" class="nav-item {{ request()->routeIs('persetujuan.*') ? 'active' : '' }}">
            <span class="nav-icon">✅</span> Persetujuan
            @php $jmlMenunggu = \App\Models\Arsip::menunggu()->count(); @endphp
            @if($jmlMenunggu > 0)
                <span class="nav-badge badge-admin">{{ $jmlMenunggu }}</span>
            @endif
        </a>
        @endif

        <div class="nav-group-label">Sistem</div>

        <a href="{{ route('pengaturan.index') }}" class="nav-item {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span> Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <span>🚪</span> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main">
    <header class="topbar">
        <div class="topbar-left" style="display:flex; align-items:center; gap:12px;">
            <div class="page-breadcrumb">
                Beranda / <span>@yield('breadcrumb', 'Halaman')</span>
            </div>
        </div>

        <form action="{{ route('arsip.index') }}" method="GET" class="topbar-search">
            <span>🔍</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari arsip, dokumen…">
        </form>

    <div style="position:relative;" id="notifWrapper">
        <div class="icon-btn" id="notifBtn" onclick="toggleNotif()" style="cursor:pointer;">
            🔔
            @php $jmlNotif = \App\Models\Arsip::menunggu()->count(); @endphp
            @if($jmlNotif > 0)
                <span class="notif-dot"></span>
            @endif
        </div>

        {{-- Dropdown Notifikasi --}}
        <div id="notifDropdown" style="
                display:none;
                position:absolute;
                top:44px; right:0;
                width:340px;
                background:var(--surface);
                border:1px solid var(--border);
                border-radius:14px;
                box-shadow:0 8px 32px rgba(0,0,0,.12);
                z-index:999;
                overflow:hidden;
            ">
                {{-- Header --}}
                <div style="padding:16px 18px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-size:14px; font-weight:700;">🔔 Notifikasi</div>
                    <a href="{{ route('aktivitas.index') }}" style="font-size:12px; color:var(--bawaslu-red); font-weight:600; text-decoration:none;">
                        Lihat semua →
                    </a>
                </div>

                {{-- Daftar Notifikasi --}}
                <div style="max-height:340px; overflow-y:auto;">

                    {{-- Menunggu Persetujuan --}}
                    @php
                        $arsipMenunggu = \App\Models\Arsip::with(['uploader','divisi'])
                            ->menunggu()->latest()->take(4)->get();
                        $aktivitasTerbaru = \App\Models\AktivitasLog::with(['user','arsip'])
                            ->latest()->take(3)->get();
                    @endphp

                    @if($arsipMenunggu->count() > 0)
                    <div style="padding:10px 18px 6px; font-size:10.5px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.8px;">
                        Menunggu Persetujuan
                    </div>
                    @foreach($arsipMenunggu as $a)
                    <a href="{{ route('arsip.show', $a) }}" style="display:flex; align-items:flex-start; gap:10px; padding:10px 18px; text-decoration:none; color:var(--text); transition:background .15s;" onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='transparent'">
                        <div style="width:34px; height:34px; border-radius:8px; background:#FFFBEB; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; margin-top:1px;">⏳</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:13px; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $a->judul }}</div>
                            <div style="font-size:11.5px; color:var(--text-muted); margin-top:2px;">
                                📤 {{ $a->uploader->nama_lengkap }} · {{ $a->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span style="font-size:9px; font-weight:700; padding:2px 7px; border-radius:20px; background:#FFFBEB; color:#D97706; flex-shrink:0; margin-top:4px;">Menunggu</span>
                    </a>
                    @endforeach
                    @endif

                    {{-- Aktivitas Terbaru --}}
                    @if($aktivitasTerbaru->count() > 0)
                    <div style="padding:10px 18px 6px; font-size:10.5px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.8px; border-top:1px solid var(--border);">
                        Aktivitas Terbaru
                    </div>
                    @foreach($aktivitasTerbaru as $log)
                    <div style="display:flex; align-items:flex-start; gap:10px; padding:10px 18px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:var(--surface2); display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0;">
                            {{ $log->aksi_ikon }}
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:13px; font-weight:600;">{{ $log->aksi_label }}</div>
                            @if($log->arsip)
                            <div style="font-size:11.5px; color:var(--text-muted); margin-top:1px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $log->arsip->judul }}
                            </div>
                            @endif
                            <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                                {{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if($arsipMenunggu->count() === 0 && $aktivitasTerbaru->count() === 0)
                    <div style="padding:32px 20px; text-align:center; color:var(--text-muted); font-size:13px;">
                        <div style="font-size:32px; margin-bottom:8px;">🎉</div>
                        Tidak ada notifikasi baru
                    </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div style="padding:12px 18px; border-top:1px solid var(--border); display:flex; gap:8px;">
                    <a href="{{ route('aktivitas.index') }}" class="btn-sm btn-view" style="flex:1; text-align:center; justify-content:center; padding:7px;">
                        📋 Semua Aktivitas
                    </a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                    <a href="{{ route('persetujuan.index') }}" class="btn-primary" style="flex:1; justify-content:center; padding:7px 12px; font-size:12px;">
                        ✅ Persetujuan ({{ $jmlNotif }})
                    </a>
                    @endif
            </div>
        </div>
    </div>

        {{-- <div class="topbar-right">
            <div class="icon-btn">
                🔔
                @if(\App\Models\Arsip::menunggu()->count() > 0)
                    <span class="notif-dot"></span>
                @endif
            </div>
        </div> --}}
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    <div class="content">
        @yield('content')
    </div>
</div>

@stack('scripts')
<script>
function toggleNotif() {
    const dd = document.getElementById('notifDropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notifWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown').style.display = 'none';
    }
});
</script>

    @stack('scripts')

</body>

</html>