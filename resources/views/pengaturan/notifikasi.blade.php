@extends('layouts.app')
@section('title', 'Notifikasi')
@section('breadcrumb', 'Pengaturan / Notifikasi')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Pengaturan Notifikasi</h1>
    <p class="text-[14px] text-abu">Atur preferensi notifikasi sistem untuk akun Anda</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<form action="{{ route('pengaturan.notifikasi.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-[1fr_1fr] gap-6 items-start">

        {{-- Notifikasi Sistem --}}
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-[15px] font-bold mb-5">Notifikasi Sistem</div>

            {{-- Arsip baru diunggah --}}
            <div class="flex items-center justify-between py-[14px] border-b border-border last:border-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-surface2 flex items-center justify-center shrink-0">
                        <img src="{{ asset('img/arsip.png') }}" class="w-5 h-5" alt="">
                    </div>
                    <div>
                        <div class="text-[13.5px] font-medium text-hitam">Arsip baru diunggah</div>
                        <div class="text-[12px] text-abu mt-0.5">Notifikasi saat ada arsip baru di divisi Anda</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_baru" value="1"
                        {{ $user->notif_arsip_baru ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Arsip disetujui --}}
            <div class="flex items-center justify-between py-[14px] border-b border-border last:border-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-surface2 flex items-center justify-center shrink-0">
                        <img src="{{ asset('img/persetujuan.png') }}" class="w-5 h-5" alt="">
                    </div>
                    <div>
                        <div class="text-[13.5px] font-medium text-hitam">Arsip disetujui</div>
                        <div class="text-[12px] text-abu mt-0.5">Notifikasi saat arsip Anda disetujui atau dihapus permanen oleh admin</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_disetujui" value="1"
                        {{ $user->notif_arsip_disetujui ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Arsip ditolak --}}
            <div class="flex items-center justify-between py-[14px] border-b border-border last:border-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-surface2 flex items-center justify-center shrink-0">
                        <img src="{{ asset('img/tolak.png') }}" class="w-5 h-5" alt="">
                    </div>
                    <div>
                        <div class="text-[13.5px] font-medium text-hitam">Arsip ditolak</div>
                        <div class="text-[12px] text-abu mt-0.5">Notifikasi saat arsip Anda ditolak atau dipulihkan oleh admin</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_arsip_ditolak" value="1"
                        {{ $user->notif_arsip_ditolak ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- Menunggu persetujuan --}}
            <div class="flex items-center justify-between py-[14px] border-b border-border last:border-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-surface2 flex items-center justify-center shrink-0">
                        <img src="{{ asset('img/deadline.png') }}" class="w-5 h-5" alt="">
                    </div>
                    <div>
                        <div class="text-[13.5px] font-medium text-hitam">Menunggu persetujuan</div>
                        <div class="text-[12px] text-abu mt-0.5">Notifikasi saat ada arsip menunggu dihapus permanen oleh admin</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="notif_menunggu_persetujuan" value="1"
                        {{ $user->notif_menunggu_persetujuan ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="mt-5">
                <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]">Simpan Preferensi</button>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-[15px] font-bold mb-4">Tentang Notifikasi</div>
            <div class="text-xs text-abu leading-[1.7]">
                <p>Notifikasi akan muncul di ikon lonceng pada navbar sistem.</p>
                <br>
                <p>Matikan notifikasi yang tidak Anda butuhkan untuk mengurangi gangguan.</p>
                <br>
                <p class="text-abu text-xs">
                    Catatan: Notifikasi hanya tersedia di dalam sistem, bukan melalui email atau SMS.
                </p>
            </div>
        </div>

    </div>
</form>
@endsection