@extends('layouts.app')
@section('title', 'Aktivitas')
@section('breadcrumb', 'Aktivitas')

@section('content')
<div class="page-header">
    <h1>Log Aktivitas</h1>
    <p>Riwayat seluruh aktivitas dalam sistem arsip</p>
</div>

<div class="tab-row">
    <a href="{{ route('aktivitas.index', ['tab' => 'semua']) }}"
       class="tab-btn {{ $tab === 'semua' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('aktivitas.index', ['tab' => 'unduh']) }}"
       class="tab-btn {{ $tab === 'unduh' ? 'active' : '' }}">Riwayat Unduhan Saya</a>
    <a href="{{ route('aktivitas.index', ['tab' => 'perubahan']) }}"
       class="tab-btn {{ $tab === 'perubahan' ? 'active' : '' }}">Riwayat Perubahan</a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('aktivitas.index', ['tab' => 'log']) }}"
       class="tab-btn {{ $tab === 'log' ? 'active' : '' }}">
        Log Semua User
    </a>
    @endif
</div>

<div class="card">
    @if($logs->count() > 0)
    <ul class="timeline">
        @foreach($logs as $log)
        <li class="tl-item">
            <div class="tl-dot {{ $log->aksi_warna_dot }}">
                <img src="{{ $log->aksi_ikon }}" alt="">
            </div>
            <div class="tl-content" style="flex:1;">
                <div class="tl-action">{{ $log->aksi_label }}</div>
                @if($log->arsip)
                <div class="tl-doc">
                    <a href="{{ route('arsip.show', $log->arsip) }}"
                          style="color:var(--bawaslu-red); text-decoration:none;">
                        {{ $log->arsip->judul }}
                    </a>
                </div>
                @endif
                @if($log->keterangan)
                <div class="tl-doc">{{ $log->keterangan }}</div>
                @endif
                <div class="tl-time">
                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                    @if(auth()->user()->isAdmin() && $tab === 'log')
                        · oleh <strong>{{ $log->user->nama_lengkap }}</strong>
                    @endif
                </div>
            </div>
            @if($log->arsip)
            <span class="doc-status status-{{ $log->arsip->status_color }}">
                {{ $log->arsip->status_label }}
            </span>
            @endif
        </li>
        @endforeach
    </ul>

    {{-- Pagination --}}

    <div class="custom-pagination">

        {{-- Tombol Previous --}}
        @if ($logs->onFirstPage())
            <span class="page-btn disabled">‹</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="page-btn">‹</a>
        @endif

        {{-- Nomor Halaman --}}
        @for ($i = 1; $i <= $logs->lastPage(); $i++)
            <a href="{{ $logs->url($i) }}"
            class="page-btn {{ $logs->currentPage() == $i ? 'active' : '' }}">
                {{ $i }}
            </a>
        @endfor

        {{-- Tombol Next --}}
        @if ($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="page-btn">›</a>
        @else
            <span class="page-btn disabled">›</span>
        @endif

    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon">📋</div>
        <p>Belum ada aktivitas yang tercatat.</p>
    </div>
    @endif
</div>
@endsection