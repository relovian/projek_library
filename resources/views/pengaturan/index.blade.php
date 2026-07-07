@extends('layouts.app')
@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Pengaturan Sistem</h1>
    <p class="text-[14px] text-abu ">Konfigurasi dan administrasi SIARSIP Bawaslu</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    @if(auth()->user()->isAdmin())
    <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.kategoris') }}'">
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/category.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Kategori & Tag</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Tambah, ubah, atau hapus kategori dan tag untuk pengelompokan arsip.</p>
    </div>

    

    <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.users') }}'">
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/group.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola User & Hak Akses</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Manajemen akun pengguna, peran, dan permission akses sistem.</p>
    </div>

    <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.divisis') }}'">
        <div class="mb-3 text-[28px]">
            <img class="size-10" src="{{ asset('img/divisi.png') }}" alt="">
        </div>
        <h3 class="mb-1 text-[14px] font-bold">Kelola Divisi / Bidang</h3>
        <p class="text-[12.5px] leading-[1.5] text-text-abu">Konfigurasi struktur organisasi divisi dan bidang di Bawaslu.</p>
    </div>
    @endif

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

    @if(auth()->user()->isAdmin())
        <div class="cursor-pointer rounded-[14px] border border-border bg-surface p-[22px] pb-[25px] transition-all duration-200 hover:border-bawaslu-red hover:shadow-[0_4px_16px_rgba(192,39,45,0.08)]" onclick="window.location='{{ route('pengaturan.backup') }}'">
            <div class="mb-3 text-[28px]">
                <img class="size-10" src="{{ asset('img/backup.png') }}" alt="">
            </div>
            <h3 class="mb-1 text-[14px] font-bold">Backup & Pemeliharaan</h3>
            <p class="text-[12.5px] leading-[1.5] text-text-abu">Kelola backup data dan pemeliharaan rutin sistem.</p>

        </div>
    @endif
</div>
@endsection 