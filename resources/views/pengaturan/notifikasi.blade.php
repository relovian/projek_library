@extends('layouts.app')
@section('title', 'Notifikasi')
@section('breadcrumb', 'Pengaturan / Notifikasi')

@push('styles')
<style>
    .notif-grid        { display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start; }
    .notif-item        { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid var(--border); }
    .notif-item:last-child { border:none; }
    .notif-info        { display:flex; align-items:center; gap:12px; }
    .notif-icon        { width:36px; height:36px; border-radius:8px; background:var(--surface2); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .notif-icon img    { width:20px; height:20px; }
    .notif-label       { font-size:13.5px; font-weight:500; color:var(--text); }
    .notif-sub         { font-size:12px; color:var(--text-muted); margin-top:2px; }

    /* Toggle switch */
    .switch            { position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0; }
    .switch input      { opacity:0; width:0; height:0; }
    .slider            { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:24px; transition:.3s; }
    .slider::before    { content:''; position:absolute; width:18px; height:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
    input:checked + .slider            { background:var(--bawaslu-red, #9b1c1c); }
    input:checked + .slider::before   { transform:translateX(20px); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Pengaturan Notifikasi</h1>
    <p>Atur preferensi notifikasi sistem untuk akun Anda</p>
</div>

<div style="margin-bottom:20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration:none;">
        ← Kembali ke Pengaturan
    </a>
</div>

{{-- Flash message --}}
@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif

<form action="{{ route('pengaturan.notifikasi.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="notif-grid">

        {{-- Notifikasi Sistem --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">Notifikasi Sistem</div>

            {{-- Arsip baru diunggah --}}
            <div class="notif-item">
                <div class="notif-info">
                    <div class="notif-icon"><img src="{{ asset('img/arsip.png') }}" alt=""></div>
                    <div>
                        <div class="notif-label">Arsip baru diunggah</div>
                        <div class="notif-sub">Notifikasi saat ada arsip baru di divisi Anda</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_baru" value="1"
                        {{ $user->notif_arsip_baru ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Arsip disetujui --}}
            <div class="notif-item">
                <div class="notif-info">
                    <div class="notif-icon"><img src="{{ asset('img/persetujuan.png') }}" alt=""></div>
                    <div>
                        <div class="notif-label">Arsip disetujui</div>
                        <div class="notif-sub">Notifikasi saat arsip yang Anda unggah disetujui</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_disetujui" value="1"
                        {{ $user->notif_arsip_disetujui ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Arsip ditolak --}}
            <div class="notif-item">
                <div class="notif-info">
                    <div class="notif-icon"><img src="{{ asset('img/tolak.png') }}" alt=""></div>
                    <div>
                        <div class="notif-label">Arsip ditolak</div>
                        <div class="notif-sub">Notifikasi saat arsip yang Anda unggah ditolak</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_ditolak" value="1"
                        {{ $user->notif_arsip_ditolak ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Menunggu persetujuan --}}
            <div class="notif-item">
                <div class="notif-info">
                    <div class="notif-icon"><img src="{{ asset('img/deadline.png') }}" alt=""></div>
                    <div>
                        <div class="notif-label">Menunggu persetujuan</div>
                        <div class="notif-sub">Notifikasi saat ada dokumen menunggu persetujuan Anda</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_menunggu_persetujuan" value="1"
                        {{ $user->notif_menunggu_persetujuan ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Revisi dokumen --}}
            <div class="notif-item">
                <div class="notif-info">
                    <div class="notif-icon"><img src="{{ asset('img/revisi.png') }}" alt=""></div>
                    <div>
                        <div class="notif-label">Revisi dokumen</div>
                        <div class="notif-sub">Notifikasi saat ada revisi dokumen baru</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_revisi_dokumen" value="1"
                        {{ $user->notif_revisi_dokumen ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn-primary">Simpan Preferensi</button>
            </div>
        </div>

        {{-- Info --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Tentang Notifikasi</div>
            <div style="font-size:13px; color:var(--text-muted); line-height:1.7;">
                <p>Notifikasi akan muncul di ikon lonceng pada navbar sistem.</p>
                <br>
                <p>Matikan notifikasi yang tidak Anda butuhkan untuk mengurangi gangguan.</p>
                <br>
                <p style="color:var(--text-muted); font-size:12px;">
                    Catatan: Notifikasi hanya tersedia di dalam sistem, bukan melalui email atau SMS.
                </p>
            </div>
        </div>

    </div>
</form>
@endsection