@extends('layouts.app')
@section('title', 'Notifikasi')
@section('breadcrumb', 'Pengaturan / Notifikasi')

@section('content')
<div class="page-header">
    <h1>Pengaturan Notifikasi</h1>
    <p>Atur preferensi notifikasi sistem untuk akun Anda</p>
</div>

<div style="margin-bottom: 20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration: none;">
        Kembali ke Pengaturan
    </a>
</div>

<form action="{{ route('pengaturan.index') }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">

        {{-- Notifikasi Sistem --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">Notifikasi Sistem</div>

            <div style="display:flex; flex-direction:column; gap:0;">
                
                {{-- Arsip baru diunggah --}}
                <div class="notif-item">
                    <div class="notif-info">
                        <div class="notif-icon"><img src="{{ asset('img/arsip.png') }}" alt="Icon"></div>
                        <div>
                            <div class="notif-label">Arsip baru diunggah</div>
                            <div class="notif-sub">Notifikasi saat ada arsip baru di divisi Anda</div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="notif_arsip_baru" value="1">
                        <span class="slider"></span>
                    </label>
                </div>

                {{-- Arsip disetujui --}}
                <div class="notif-item">
                    <div class="notif-info">
                        <div class="notif-icon"><img src="{{ asset('img/persetujuan.png') }}" alt="Icon"></div>
                        <div>
                            <div class="notif-label">Arsip disetujui</div>
                            <div class="notif-sub">Notifikasi saat arsip yang Anda unggah disetujui</div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="notif_arsip_disetujui" value="1">
                        <span class="slider"></span>
                    </label>
                </div>

                {{-- Arsip ditolak --}}
                <div class="notif-item">
                    <div class="notif-info">
                        <div class="notif-icon"><img src="{{ asset('img/tolak.png') }}" alt="Icon"></div>
                        <div>
                            <div class="notif-label">Arsip ditolak</div>
                            <div class="notif-sub">Notifikasi saat arsip yang Anda unggah ditolak</div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="notif_arsip_ditolak" value="1">
                        <span class="slider"></span>
                    </label>
                </div>

                {{-- Menunggu persetujuan --}}
                <div class="notif-item">
                    <div class="notif-info">
                        <div class="notif-icon"><img src="{{ asset('img/deadline.png') }}" alt="Icon"></div>
                        <div>
                            <div class="notif-label">Menunggu persetujuan</div>
                            <div class="notif-sub">Notifikasi saat ada dokumen menunggu persetujuan Anda</div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="notif_menunggu_persetujuan" value="1" >
                        <span class="slider"></span>
                    </label>
                </div>

                {{-- Revisi dokumen --}}
                <div class="notif-item" style="border:none;">
                    <div class="notif-info">
                        <div class="notif-icon"><img src="{{ asset('img/revisi.png') }}" alt="Icon"></div>
                        <div>
                            <div class="notif-label">Revisi dokumen</div>
                            <div class="notif-sub">Notifikasi saat ada revisi dokumen baru</div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="notif_revisi_dokumen" value="1" >
                        <span class="slider"></span>
                    </label>
                </div>

            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn-primary">Simpan Preferensi</button>
            </div>
        </div>

    </div>
</form>
@endsection