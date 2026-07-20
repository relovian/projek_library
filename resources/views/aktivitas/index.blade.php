@extends('layouts.app')
@section('title', 'Aktivitas')
@section('breadcrumb', 'Aktivitas')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Log Aktivitas</h1>
    <p class="text-[14px] text-abu">Riwayat seluruh aktivitas dalam sistem arsip</p>
</div>

<div class="mb-5 flex border-b-2 border-border">
    <a href="{{ route('aktivitas.index', ['tab' => 'semua']) }}"
       class="relative -mb-[2px] inline-block border-b-2 px-[18px] py-2.5 text-[13.5px] font-semibold transition-colors duration-200 {{ $tab === 'semua' 
      ? 'border-b-bawaslu-red text-bawaslu-red' : 'border-transparent text-abu hover:text-hitam' }}">Semua</a>


    <a href="{{ route('aktivitas.index', ['tab' => 'perubahan']) }}"
       class="relative -mb-[2px] inline-block border-b-2 px-[18px] py-2.5 text-[13.5px] font-semibold transition-colors duration-200 {{ $tab === 'perubahan' ? 'border-b-bawaslu-red text-bawaslu-red' : 'border-transparent text-abu hover:text-hitam' }}">Riwayat Perubahan</a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('aktivitas.index', ['tab' => 'log']) }}"
       class="relative -mb-[2px] inline-block border-b-2 px-[18px] py-2.5 text-[13.5px] font-semibold transition-colors duration-200 {{ $tab === 'log' ? 'border-b-bawaslu-red text-bawaslu-red' : 'border-transparent text-abu hover:text-hitam' }}">
        Log Semua User
    </a>
    @endif
</div>

<div class="mb-[15px] rounded-[14px] border border-border bg-surface p-6">
    @if($logs->count() > 0)
    <ul class="list-none">
        @foreach($logs as $log)
        <li class="flex items-start gap-[14px] border-b border-border py-4 last:border-none">
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
            <div class="flex-1">
                <div class="text-[13.5px] font-semibold">{{ $log->aksi_label }}</div>
                @if($log->arsip)
                <div class="text-[12.5px] text-abu mt-[2px]">
                    <a href="{{ route('arsip.show', $log->arsip) }}"
                          class="text-bawaslu-red no-underline">
                        {{ $log->arsip->judul }}
                    </a>
                </div>
                @endif
                @if($log->keterangan)
                <div class="text-[12.5px] text-abu mt-[2px]">{{ $log->keterangan }}</div>
                @endif
            <div class="text-[11.5px] text-abu mt-1">
                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                    @if($log->aksi === 'hapus')
                        @if ($log->aksi === 'pulihkan')
                            <span class="ml-2 inline-flex items-center rounded-[20px] bg-[#ECFDF5] text-[#059669] px-[9px] py-[3px] text-[10.5px] font-bold shrink-0">
                                Sudah dipulihkan
                            </span>
                        @else 
                            <span class="ml-2 inline-flex items-center rounded-[20px] bg-[#FEF2F2] text-[#DC2626] px-[9px] py-[3px] text-[10.5px] font-bold shrink-0">
                                Menunggu admin menghapus permanen
                            </span>
                        @endif
                    @elseif($log->aksi === 'hapus_permanen')
                        <span class="ml-2 inline-flex items-center rounded-[20px] bg-[#DC2626] text-white px-[9px] py-[3px] text-[10.5px] font-bold shrink-0">
                            Sudah dihapus permanen oleh admin
                        </span>
                    @elseif($log->aksi === 'pulihkan')
                        <span class="ml-2 inline-flex items-center rounded-[20px] bg-[#ECFDF5] text-[#059669] px-[9px] py-[3px] text-[10.5px] font-bold shrink-0">
                            Sudah dipulihkan
                        </span>
                    @endif
                    @if(auth()->user()->isAdmin() && $tab === 'log')
                        · oleh <strong>{{ $log->user->nama_lengkap }}</strong>
                    @endif
                </div>
            </div>
            @if($log->arsip)
            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                @switch($log->arsip->status_color)
                    @case('green') bg-[#ECFDF5] text-[#059669] @break
                    @case('yellow') bg-[#FFFBEB] text-[#D97706] @break
                    @case('blue') bg-[#EFF6FF] text-[#2563EB] @break
                    @case('gray') bg-[#F5F5F5] text-[#6B7280] @break
                    @case('red') bg-[#FEF2F2] text-[#DC2626] @break
                    @default bg-[#F5F5F5] text-[#6B7280]
                @endswitch">
                {{ $log->arsip->status_label }}
            </span>
            @endif
        </li>
        @endforeach
    </ul>

    {{-- Pagination --}}
    <div class="mt-6 flex items-center justify-end gap-2 flex-wrap">
        {{-- Tombol Previous --}}
        @if ($logs->onFirstPage())
            <span class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB] opacity-45 pointer-events-none">‹</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB]">‹</a>
        @endif

        {{-- Nomor Halaman --}}
        @for ($i = 1; $i <= $logs->lastPage(); $i++)
            <a href="{{ $logs->url($i) }}"
            class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border  text-hitam no-underline text-sm font-semibold transition-all duration-200 ease bg-red-500 hover:border-[#D1D5DB] {{ $logs->currentPage() == $i ? 'bg-[#be2a31] text-white border-bawaslu-red  hover:bg-[#de2a33]' : ' hover:bg-surface2' }}">
                {{ $i }}
            </a>
        @endfor

        {{-- Tombol Next --}}
        @if ($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB]">›</a>
        @else
            <span class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB] opacity-45 pointer-events-none">›</span>
        @endif
    </div>

    @else
    <div class="text-center py-10 px-5 text-abu">
        <div class="text-[40px] mb-[10px]">📋</div>
        <p class="text-[14px]">Belum ada aktivitas yang tercatat.</p>
    </div>
    @endif
</div>
@endsection