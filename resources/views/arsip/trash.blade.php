@extends('layouts.app')

@section('title', 'Trash Arsip')
@section('breadcrumb') Trash @endsection

@section('content')
<div class="trash-wrapper">

    {{-- Header --}}
    <div class="trash-header">
        <div>
            <h1 class="trash-header-title">Trash Arsip</h1>
            <p class="trash-header-sub">
                Arsip yang dihapus akan tersimpan di sini. Bisa dipulihkan atau dihapus permanen.
            </p>
        </div>
        <div class="trash-header-actions">
            <a href="{{ route('arsip.index') }}" class="btn-back"> Kembali ke Arsip</a>

            @if($arsip->count() > 0)
            <form method="POST" action="{{ route('arsip.empty-trash') }}"
                onsubmit="return confirm('Yakin ingin menghapus SEMUA arsip di trash secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-empty-trash">🗑 Kosongkan Trash</button>
            </form>
            @endif
        </div>
    </div>

    {{-- Warning banner --}}
    <div class="trash-warning">
        <span class="trash-warning-icon">⚠️</span>
        <span>Arsip di trash akan <strong>dihapus permanen otomatis setelah 7 hari</strong>. Pulihkan arsip jika masih dibutuhkan.</span>
    </div>

    {{-- Empty state --}}
    @if($arsip->isEmpty())
    <div class="trash-empty">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="#d1d5db"
            stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto;">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        <p class="trash-empty-title">Trash kosong</p>
        <p class="trash-empty-sub">Tidak ada arsip yang dihapus saat ini.</p>
    </div>

    @else
    <div class="trash-card">
        <table class="trash-table">
            <thead>
                <tr>
                    <th>Judul Arsip</th>
                    <th>Kategori</th>
                    <th>Dihapus oleh</th>
                    <th>Tanggal dihapus</th>
                    <th>Dihapus permanen</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arsip as $item)
                @php
                    $hariTersisa  = now()->diffInDays($item->deleted_at->addDays(7), false);
                    $sudahLewat   = $hariTersisa <= 0;
                    $countdownClass = $hariTersisa <= 2
                        ? 'countdown countdown-red'
                        : ($hariTersisa <= 5 ? 'countdown countdown-yellow' : 'countdown countdown-gray');
                @endphp
                <tr>
                    <td>
                        <div class="trash-doc-name">{{ Str::limit($item->judul, 40) }}</div>
                        <div class="trash-doc-meta">{{ $item->kode_arsip }}</div>
                    </td>
                    <td>
                        <span class="category-tag">{{ $item->kategori->nama ?? '-' }}</span>
                    </td>
                    <td>{{ $item->uploader->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->deleted_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($sudahLewat)
                            <span class="countdown countdown-red">⚠️ Segera dihapus</span>
                        @else
                            <span class="{{ $countdownClass }}">
                                {{ $hariTersisa == 0 ? 'Hari ini' : "dalam {$hariTersisa} hari" }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="trash-actions">

                            {{-- Pulihkan --}}
                            <form method="POST" action="{{ route('arsip.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-restore">♻️ Pulihkan</button>
                            </form>

                            {{-- Hapus permanen --}}
                            <form method="POST" action="{{ route('arsip.force-delete', $item->id) }}"
                                onsubmit="return confirm('Hapus permanen arsip ini? File akan ikut terhapus dan tidak bisa dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-force-delete">🗑 Hapus Permanen</button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($arsip->hasPages())
        <div class="trash-pagination">
            {{ $arsip->links() }}
        </div>
        @endif
    </div>
    @endif

</div>
@endsection