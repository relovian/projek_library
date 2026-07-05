@extends('layouts.app')
@section('title', 'Notifikasi')
@section('breadcrumb', 'Pengaturan / Notifikasi')

@section('content')
<div class="page-header">
    <h1>Pengaturan Notifikasi</h1>
    <p>Atur preferensi notifikasi sistem untuk akun Anda</p>
</div>

<div style="margin-bottom:20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration:none;">
        Kembali ke Pengaturan
    </a>
</div>

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