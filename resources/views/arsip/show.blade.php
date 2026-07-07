@extends('layouts.app')
@section('title', $arsip->judul)
@section('breadcrumb', 'Arsip / Detail')

@section('content')

<div class="grid grid-cols-[1fr_320px] gap-6 items-start">
    <div class="flex flex-col gap-5">
        <div>
            <div class="bg-surface border border-border rounded-[14px] p-6 flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-[#FEF2F2] flex items-center justify-center shrink-0">
                        <img 
                            src="{{ $arsip->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                            alt="icon"
                            class="w-[25px] h-[25px] object-contain"
                        >
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-1.5 leading-tight">
                            {{ $arsip->judul }}
                        </h2>

                        <div class="flex gap-2 flex-wrap items-center">
                            <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border">
                                {{ $arsip->kategori->nama }}
                            </span>

                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                                @switch($arsip->status_color)
                                    @case('green') bg-[#ECFDF5] text-[#059669] @break
                                    @case('yellow') bg-[#FFFBEB] text-[#D97706] @break
                                    @case('blue') bg-[#EFF6FF] text-[#2563EB] @break
                                    @case('gray') bg-[#F5F5F5] text-[#6B7280] @break
                                    @case('red') bg-[#FEF2F2] text-[#DC2626] @break
                                    @default bg-[#F5F5F5] text-[#6B7280]
                                @endswitch">
                                {{ $arsip->status_label }}
                            </span>

                            @if($arsip->nomor_surat)
                            <span class="text-[12px] text-abu">
                                No. {{ $arsip->nomor_surat }}
                            </span>
                            @endif

                        </div>

                    </div>

                </div>

                <div class="flex gap-2 flex-wrap items-start">
                    <a href="{{ route('arsip.download', $arsip) }}" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]">
                        Unduh
                    </a>

                    @can('update', $arsip)
                    <a href="{{ route('arsip.edit', $arsip) }}"
                       class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border gap-1.5">

                        <img src="{{ asset('img/edit.png') }}" class="w-[13px] h-[13px]" alt=""> Edit

                    </a>
                    @endcan

                </div>

            </div>

        </div>

        {{-- Deskripsi --}}
        @if($arsip->deskripsi)

        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-4">
                Deskripsi
            </div>

            <p class="text-[14px] leading-[1.7] text-abu">
                {{ $arsip->deskripsi }}
            </p>

        </div>

        @endif

        {{-- Catatan Penolakan --}}
        @if($arsip->status === 'ditolak' && $arsip->catatan_penolakan)

        <div class="bg-surface border border-[#FECACA] rounded-[14px] p-6 mb-[15px] bg-[#FEF2F2]">

            <div class="text-[14px] font-bold text-[#DC2626] mb-2">
                Alasan Penolakan
            </div>

            <p class="text-[13.5px] text-[#991B1B] leading-[1.6]">
                {{ $arsip->catatan_penolakan }}
            </p>

            @if($arsip->penyetuju)

            <div class="text-[12px] text-[#B91C1C] mt-2">
                Ditolak oleh {{ $arsip->penyetuju->nama_lengkap }} ·
                {{ $arsip->disetujui_at?->format('d M Y, H:i') }} WIB
            </div>

            @endif

        </div>

        @endif

        {{-- Riwayat Versi --}}
        @if($arsip->revisis->count() > 0 || $arsip->arsipInduk)

        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-4">
                Riwayat Versi
            </div>

            @if($arsip->arsipInduk)

            <div class="text-[13px] mb-2.5 text-abu">

                Dokumen induk:

                <a href="{{ route('arsip.show', $arsip->arsipInduk) }}"
                   class="text-bawaslu-red">

                    {{ $arsip->arsipInduk->judul }}

                </a>

            </div>

            @endif

            @foreach($arsip->revisis as $rev)

            <div class="flex justify-between items-center py-2 border-b border-border">

                <div>

                    <span class="text-[13px] font-semibold">
                        Versi {{ $rev->versi }}
                    </span>

                    <span class="text-[12px] text-abu ml-2">
                        {{ $rev->created_at->format('d M Y') }}
                    </span>

                </div>

                <a href="{{ route('arsip.show', $rev) }}"
                   class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border text-[11px]">

                    Lihat

                </a>

            </div>

            @endforeach

        </div>

        @endif

        {{-- Aktivitas Log --}}
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-4">
                Riwayat Aktivitas
            </div>

            @forelse($arsip->aktivitasLogs()->with('user')->latest()->take(10)->get() as $log)

            <div class="flex gap-3 py-2.5 border-b border-border">

                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0
                    @switch($log->aksi_warna_dot)
                        @case('upload') bg-[#ECFDF5] @break
                        @case('download') bg-[#EFF6FF] @break
                        @case('approve') bg-[#F0FDF4] @break
                        @case('edit') bg-[#FFFBEB] @break
                        @case('reject') bg-[#FEF2F2] @break
                        @default bg-[#EFF6FF]
                    @endswitch">
                    <img src="{{ $log->aksi_ikon }}" alt="">
                </div>

                <div>

                    <div class="text-[13px] font-semibold">
                        {{ $log->aksi_label }}
                    </div>

                    <div class="text-[12px] text-abu mt-0.5">
                        {{ $log->user->nama_lengkap }} ·
                        {{ $log->created_at->format('d M Y, H:i') }} WIB
                    </div>

                </div>

            </div>

            @empty

            <div class="text-center py-5 px-5 text-abu">
                <p>Belum ada aktivitas.</p>
            </div>

            @endforelse

        </div>

    </div>

    {{-- Sidebar --}}
    <div class="flex flex-col gap-4">

        {{-- Informasi Dokumen --}}
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-4">
                Informasi Dokumen
            </div>

            <table class="w-full text-[13px]">

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Kode Arsip</td>
                    <td class="py-2 font-semibold">{{ $arsip->kode_arsip }}</td>
                </tr>

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Tanggal</td>
                    <td class="py-2 font-semibold">
                        {{ $arsip->tanggal_dokumen->format('d M Y') }}
                    </td>
                </tr>

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Divisi</td>
                    <td class="py-2 font-semibold">
                        {{ $arsip->divisi->nama }}
                    </td>
                </tr>

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Pengunggah</td>
                    <td class="py-2 font-semibold">
                        {{ $arsip->uploader->nama_lengkap }}
                    </td>
                </tr>

                @if($arsip->periode_pemilu)

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Periode</td>
                    <td class="py-2 font-semibold">
                        {{ $arsip->periode_pemilu }}
                    </td>
                </tr>

                @endif

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Akses</td>
                    <td class="py-2 font-semibold">
                        {{ ucfirst(str_replace('_', ' ', $arsip->tingkat_akses)) }}
                    </td>
                </tr>

                <tr class="border-b border-border">
                    <td class="py-2 text-abu w-[45%]">Versi</td>
                    <td class="py-2 font-semibold">
                        v{{ $arsip->versi }}
                    </td>
                </tr>

                @if($arsip->status === 'disetujui' && $arsip->penyetuju)

                <tr class="border-b border-border">

                    <td class="py-2 text-abu w-[45%]">
                        Disetujui
                    </td>

                    <td class="py-2 font-semibold">

                        {{ $arsip->penyetuju->nama_lengkap }}

                        <br>

                        <span class="text-[11px] text-abu">
                            {{ $arsip->disetujui_at?->format('d M Y') }}
                        </span>

                    </td>

                </tr>

                @endif

            </table>

        </div>

        {{-- File --}}
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-4">
                <span>File Dokumen</span>
            </div>

            @forelse($arsip->files as $file)

            <div class="flex items-center gap-3 p-2.5 bg-surface2 rounded-lg mb-2">

                <div class="text-2xl">
                    <img src="{{ $file->ikon }}" class="mt-[5px] w-8 h-8" alt="">
                </div>

                <div class="flex-1 min-w-0">

                    <div class="text-[13px] font-semibold truncate">
                        {{ $file->nama_asli }}
                    </div>

                    <div class="text-[11.5px] text-abu mt-0.5">
                        {{ strtoupper($file->ekstensi) }} · {{ $file->ukuran_format }}
                    </div>

                </div>

                <a href="{{ route('arsip.download', $arsip) }}"
                   class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline"
                   title="Unduh">

                    <img src="{{ asset('img/unggah.png') }}" class="w-[15px] h-[15px]" alt="">

                </a>

            </div>

            @empty

            <div class="text-center py-4 px-5 text-abu">
                <p>Tidak ada file.</p>
            </div>

            @endforelse

        </div>

        {{-- Tags --}}
        @if($arsip->tags)

        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">

            <div class="text-[15px] font-bold mb-3">
                Tag
            </div>

            <div class="flex flex-wrap gap-1.5">

                @foreach($arsip->tags_array as $tag)

                <span class="px-2.5 py-1 bg-surface2 border border-border rounded-[20px] text-[12px] text-abu">
                    {{ trim($tag) }}
                </span>

                @endforeach

            </div>

        </div>

        @endif

        {{-- Aksi Admin --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
        @if($arsip->status === 'menunggu')

        <div class="bg-surface border border-[#FDE68A] rounded-[14px] p-6 mb-[15px] bg-[#FFFBEB]">

            <div class="text-[13px] font-bold text-[#92400E] mb-3">
                Tindakan Persetujuan
            </div>

            <form method="POST"
                  action="{{ route('persetujuan.setujui', $arsip) }}"
                  class="mb-2">

                @csrf

                <button type="submit"
                        class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red w-full px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]"
                        onclick="return confirm('Setujui dokumen ini?')">

                    Setujui Dokumen

                </button>

            </form>

            <button type="button"
                    class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border border-border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-abu w-full justify-center py-2"
                    onclick="document.getElementById('modalTolakShow').classList.remove('hidden'); document.getElementById('modalTolakShow').classList.add('flex')">

                Tolak Dokumen

            </button>

        </div>

        @endif
        @endif

    </div>

</div>

{{-- Tombol Kembali --}}
<div class="mt-4">

    <a href="{{ route('arsip.index') }}"
       class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border border-border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam px-4 py-2">

        ← Kembali ke Daftar Arsip

    </a>

</div>

{{-- Modal Tolak --}}
<div class="hidden fixed inset-0 bg-black/45 z-[200] items-center justify-center" id="modalTolakShow">

    <div class="bg-surface rounded-[16px] p-8 max-w-[520px] w-[90%]">

        <div class="flex justify-between items-start mb-6">

            <div class="text-lg font-bold">
                Tolak Dokumen
            </div>

            <button class="text-xl cursor-pointer text-abu bg-none border-none"
                onclick="document.getElementById('modalTolakShow').classList.remove('flex'); document.getElementById('modalTolakShow').classList.add('hidden')">

                ✕

            </button>

        </div>

        <form method="POST"
              action="{{ route('persetujuan.tolak', $arsip) }}">

            @csrf

            <div class="mb-[18px]">

                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Catatan Penolakan
                    <span class="text-bawaslu-red">*</span>
                </label>

                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[90px]"
                          name="catatan_penolakan"
                          required
                          placeholder="Tuliskan alasan penolakan…"></textarea>

            </div>

            <div class="flex gap-2.5 justify-end">

                <button type="button"
                        class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border border-border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam"
                        onclick="document.getElementById('modalTolakShow').classList.remove('flex'); document.getElementById('modalTolakShow').classList.add('hidden')">

                    Batal

                </button>

                <button type="submit"
                        class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-none [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-bawaslu-red text-white px-[14px]">

                    Konfirmasi Tolak

                </button>

            </div>

        </form>

    </div>

</div>

@endsection