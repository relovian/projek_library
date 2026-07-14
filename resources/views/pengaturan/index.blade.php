@extends('layouts.app')
@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
@php
    $user = auth()->user();
    $canManage = $user->isAdmin();
@endphp

<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Pengaturan Sistem</h1>
    <p class="text-[14px] text-abu">Konfigurasi dan administrasi SIARSIP Bawaslu</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.kategoris') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/category.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Kategori & Tag</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus kategori dan tag untuk pengelompokan arsip.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.users') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/group.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola User & Hak Akses</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Manajemen akun pengguna, peran, dan permission akses sistem.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.divisis') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/divisi.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Divisi / Bidang</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Konfigurasi struktur organisasi divisi dan bidang di Bawaslu.</p>
    </div>

    <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.profil') }}'">
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/pengguna.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Profil & Password Saya</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Perbarui informasi profil dan ganti kata sandi akun Anda.</p>
    </div>

    <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.notifikasi') }}'">
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/notfikasi.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Notifikasi</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Atur preferensi notifikasi email dan sistem untuk akun Anda.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.backup') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/backup.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Backup & Pemiliharaan</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Kelola backup data dan pemeliharaan rutin sistem.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.sub_bagians') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/sub_bagian.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Sub Bagian</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus sub bagian untuk struktur organisasi.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.klasifikasis') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/klasifikasi.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Kode Klasifikasi</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus kode klasifikasi dokumen arsip.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.sifat_surats') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/sifat_surat.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Sifat Surat</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus sifat surat untuk arsip.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.verifikator') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/verifikator.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Verifikator</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus verifikator untuk arsip.</p>
    </div>

    <div class="group relative rounded-[14px] border border-border p-[22px] pb-[25px] {{ $canManage ? 'cursor-pointer bg-surface transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]' : 'bg-gray-100 opacity-70 cursor-not-allowed' }}"
        @if($canManage)
            onclick="window.location='{{ route('pengaturan.tujuan') }}'"
        @endif>
        @if(!$canManage)
            <img src="{{ asset('img/lock.png') }}" class="absolute top-4 right-4 w-6 h-6" alt="Locked">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                Fitur hanya bisa diakses Admin
            </div>
        @endif
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/tujuan.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Tujuan</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus tujuan untuk arsip.</p>
    </div>
</div>
@endsection