@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Selamat Datang, {{ auth()->user()->nama_lengkap }} </h1>
    <p class="text-[14px] text-abu">Ringkasan sistem pengelolaan arsip Bawaslu — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-bawaslu-red"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#FEF2F2]">
            <img src="{{ asset('img/arsip.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ number_format($stats['total_arsip']) }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Total Arsip Tersimpan</div>
    </div>

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-blue-500"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#EFF6FF]">
            <img src="{{ asset('img/deadline.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ $stats['menunggu'] }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Menunggu Persetujuan</div>
        @if($stats['menunggu'] > 0)
            <div class="mt-2 inline-flex items-center rounded-full bg-[#FFFBEB] px-2 py-[2px] text-[11px] font-semibold text-[#D97706]">Perlu ditinjau</div>
        @endif
    </div>

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-emerald-500"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#ECFDF5]">
            <img src="{{ asset('img/group.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ $stats['user_aktif'] }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Pengguna Aktif</div>
    </div>
</div>

{{-- Arsip Per Kategori --}}
<div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="text-[15px] font-bold">Arsip Per Kategori</div>
            <div class="text-[12px] text-abu mt-[2px]">Jumlah dokumen berdasarkan kategori</div>
        </div>
        <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('arsip.index') }}">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] gap-3 pt-4 pb-1">
        @forelse($arsipPerKategori as $kat)
        <a href="{{ route('arsip.index', ['kategori_id' => $kat->id]) }}"
            class="flex flex-col gap-[6px] bg-[var(--bg-secondary,#f9fafb)] border border-[var(--border,#e5e7eb)] rounded-[10px] px-4 py-[14px] no-underline transition-colors duration-[0.12s]"
           onmouseover="this.style.background='#f0f4ff'"
           onmouseout="this.style.background='var(--bg-secondary, #f9fafb)'">
            <div class="w-2 h-2 rounded-full  " style="background-color: {{ $kat->warna ?? '#6b7280' }};"></div>
            <div class="text-xl font-bold text-[#111827]">
                {{ number_format($kat->arsips_count) }}
            </div>
            <div class="text-xs text-abu">
                {{ $kat->nama }}
            </div>
        </a>
        @empty
        <div class="text-xs text-abu px-[8px] py-0">
            Belum ada kategori tersedia.
        </div>
        @endforelse
    </div>
</div>

{{-- Grid Konten --}}
<div class="grid grid-cols-[1fr_340px] gap-5">
    {{-- Arsip Terbaru --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Arsip Terbaru Diunggah</div>
                <div class="text-[12px] text-abu mt-[2px]">7 hari terakhir</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('arsip.index') }}">Lihat semua →</a>
        </div>
        <ul class="list-none">
            @forelse($arsipTerbaru as $arsip)
            <li class="flex items-center gap-[14px] py-3 border-b border-border cursor-pointer last:border-b-0" onclick="window.location='{{ route('arsip.show', $arsip) }}'">
                <div class="w-[38px] h-[38px] rounded-[9px] flex items-center justify-center text-lg shrink-0">
                    <img src="{{ $arsip->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                        alt="icon" class="w-[25px] h-[25px] object-contain">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold truncate">{{ $arsip->judul }}</div>
                    <div class="text-[11.5px] text-abu mt-[2px]">
                        {{ $arsip->divisi->nama }} ·
                        {{ $arsip->tanggal_dokumen->format('d M Y') }} ·
                        {{ $arsip->file_pertama?->ukuran_format ?? '-' }}
                    </div>
                </div>
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
            </li>
            @empty
            <li class="text-center py-10 px-5 text-abu">
                <div class="text-[40px] mb-[10px]"><img src="{{ asset('img/arsip.png') }}" alt=""></div>
                <p class="text-[14px]">Belum ada arsip yang diunggah.</p>
            </li>
            @endforelse
        </ul>
    </div>

    {{-- Perlu Ditinjau (Admin/Pimpinan) atau Aktivitas Saya (Staff) --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Perlu Ditinjau</div>
                <div class="text-[12px] text-abu mt-[2px]">{{ $stats['menunggu'] }} dokumen menunggu</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('persetujuan.index') }}">Semua →</a>
        </div>
        <ul class="list-none">
            @forelse($menungguPersetujuan as $arsip)
            <li class="py-3 border-b border-border last:border-b-0">
                <div class="flex justify-between items-start mb-1">
                    <div class="text-[13px] font-semibold">{{ Str::limit($arsip->judul, 40) }}</div>
                    <div class="text-[11px] text-abu">{{ $arsip->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-[12px] text-abu">
                    <img src="{{ asset('img/unggah.png') }}" class="w-3 h-3 mr-[5px]" alt="">
                    {{ $arsip->uploader->nama_lengkap }} · {{ $arsip->divisi->nama }}
                </div>
                <div class="flex gap-1.5 mt-[15px] flex-wrap flex justify-start items-center">
                    <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" class="flex justify-start items-center">
                        @csrf
                        <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-none [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-[#059669] text-white h-6">Setujui</button>
                    </form>
                    <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-abu border-border h-6"
                        onclick="openTolakModal({{ $arsip->id }}, '{{ addslashes($arsip->judul) }}')">
                        Tolak
                    </button>
                    <a href="{{ route('arsip.show', $arsip) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border h-6">Lihat</a>
                </div>
            </li>

            @empty
            <li class="text-center py-10 px-5 text-abu">
                <div class="text-[40px] mb-[10px]"><img src="{{ asset('img/persetujuan.png') }}" alt=""></div>
                <p class="text-[14px]">Tidak ada dokumen yang menunggu.</p>
            </li>
            @endforelse
        </ul>
    </div>
    @else
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div class="text-[15px] font-bold">Aktivitas Terakhir Saya</div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('aktivitas.index') }}">Semua →</a>
        </div>
        <ul class="list-none">
            @forelse($aktivitasSaya as $log)
            <li class="flex gap-[14px] py-4 border-b border-border items-start last:border-b-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0 mt-[2px]
                    @switch($log->aksi_warna_dot)
                        @case('upload') bg-[#ECFDF5] @break
                        @case('download') bg-[#EFF6FF] @break
                        @case('approve') bg-[#F0FDF4] @break
                        @case('edit') bg-[#FFFBEB] @break
                        @case('reject') bg-[#FEF2F2] @break
                        @default bg-[#EFF6FF]
                    @endswitch">
                    <img src="{{ $log->aksi_ikon }}" width="15" height="15" alt="">
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold">{{ $log->aksi_label }}</div>
                    @if($log->arsip)
                    <div class="text-[12.5px] text-abu mt-[2px]">{{ $log->arsip->judul }}</div>
                    @endif
                    <div class="text-[11.5px] text-abu mt-1">{{ $log->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </li>
            @empty
            <li class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada aktivitas.</p></li>
            @endforelse
        </ul>
    </div>
    @endif
</div>

<div class="hidden fixed inset-0 bg-black/45 z-[200] items-center justify-center" id="modalTolak">
    <div class="bg-surface rounded-[16px] p-8 max-w-[520px] w-[90%]">
        <div class="flex justify-between items-start mb-6">
            <div class="text-lg font-bold">Tolak Arsip</div>
            <button class="text-xl cursor-pointer text-abu bg-none border-none" onclick="closeTolakModal()">✕</button>
        </div>
        <p class="text-xs text-abu mt-[16px]">
            Berikan alasan penolakan untuk dokumen: <strong id="modalDocName"></strong>
        </p>
        <form id="formTolak" method="POST">
            @csrf
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Catatan Penolakan <span class="text-bawaslu-red">*</span></label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[90px]" name="catatan_penolakan" required
                    placeholder="Tuliskan alasan penolakan…"></textarea>
            </div>
            <div class="flex gap-[10px] justify-end">
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-none [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-bawaslu-red text-white">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    function openTolakModal(id, judul) {
        document.getElementById('modalDocName').textContent = judul;
        document.getElementById('formTolak').action = '/persetujuan/' + id + '/tolak';
        document.getElementById('modalTolak').classList.add('flex');
        document.getElementById('modalTolak').classList.remove('hidden');
    }
    function closeTolakModal() {
        document.getElementById('modalTolak').classList.remove('flex');
        document.getElementById('modalTolak').classList.add('hidden');
    }
    </script>
@endpush