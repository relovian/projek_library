@extends('layouts.app')
@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
@php
    $user = auth()->user();

    // Admin dan Pimpinan boleh mengakses fitur manajemen
    $canManage = $user->isAdmin() || $user->isPimpinan();
@endphp
<div class="page-header">
    <h1>Pengaturan Sistem</h1>
    <p>Konfigurasi dan administrasi SIARSIP Bawaslu</p>
</div>

<div class="settings-grid">
  
   <div class="settings-card {{ !$canManage ? 'locked' : '' }}"
    @if($canManage)
        onclick="window.location='{{ route('pengaturan.kategoris') }}'"
    @endif
>

    <!-- @if(!$canManage)
        <div class="lock-badge">🔒</div>
    @endif -->

    <div class="settings-icon">
        <img src="{{ asset('img/category.png') }}" alt="">
    </div>
   @if(!$canManage)
<div class="tooltip-lock">
    Hanya Admin & Pimpinan yang dapat mengakses fitur ini
</div>
@endif
    <h3>Kelola Kategori & Tag</h3>

    <p>Tambah, ubah, atau hapus kategori dan tag untuk pengelompokan arsip.</p>

</div>

    

    <div class="settings-card" onclick="window.location='{{ route('pengaturan.users') }}'">
        <div class="settings-icon">
            <img src="{{ asset('img/group.png') }}" alt="">
        </div>
        <h3>Kelola User & Hak Akses</h3>
        <p>Manajemen akun pengguna, peran, dan permission akses sistem.</p>
    </div>

    <div class="settings-card" onclick="window.location='{{ route('pengaturan.divisis') }}'">
        <div class="settings-icon">
            <img src="{{ asset('img/divisi.png') }}" alt="">
        </div>
        <h3>Kelola Divisi / Bidang</h3>
        <p>Konfigurasi struktur organisasi divisi dan bidang di Bawaslu.</p>
    </div>
    

    <div class="settings-card" onclick="window.location='{{ route('pengaturan.profil') }}'">
        <div class="settings-icon">
            <img src="{{ asset('img/pengguna.png') }}" alt="">
        </div>
        <h3>Profil & Password Saya</h3>
        <p>Perbarui informasi profil dan ganti kata sandi akun Anda.</p>
    </div>

    <div class="settings-card" onclick="window.location='{{ route('pengaturan.notifikasi') }}'">
        <div class="settings-icon">
            <img src="{{ asset('img/notfikasi.png') }}" alt="">
        </div>
        <h3>Notifikasi</h3>
        <p>Atur preferensi notifikasi email dan sistem untuk akun Anda.</p>
    </div>

  
        <div class="settings-card" onclick="window.location='{{ route('pengaturan.backup') }}'">
            <div class="settings-icon">
                <img src="{{ asset('img/backup.png') }}" alt="">
            </div>
            <h3>Backup & Pemeliharaan</h3>
            <p>Kelola backup data dan pemeliharaan rutin sistem.</p>

        </div>
   
</div>
@endsection