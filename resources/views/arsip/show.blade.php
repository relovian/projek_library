@extends('layouts.app')
@section('title', $arsip->judul)
@section('breadcrumb', 'Arsip / Detail')

@section('content')
<div style="display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start;">

    {{-- Konten Utama --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Header Dokumen --}}
        <div class="card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                <div style="display:flex; align-items:flex-start; gap:16px;">
                    <div style="width:56px; height:56px; border-radius:12px; background:#FEF2F2; display:flex; align-items:center; justify-content:center; font-size:28px; flex-shrink:0;">
                        {{ $arsip->file_pertama?->ikon ?? '📄' }}
                    </div>
                    <div>
                        <h2 style="font-size:20px; font-weight:700; margin-bottom:6px; line-height:1.3;">{{ $arsip->judul }}</h2>
                        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                            <span class="category-tag">{{ $arsip->kategori->nama }}</span>
                            <span class="doc-status status-{{ $arsip->status_color }}">{{ $arsip->status_label }}</span>
                            @if($arsip->nomor_surat)
                            <span style="font-size:12px; color:var(--text-muted);">No. {{ $arsip->nomor_surat }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="{{ route('arsip.download', $arsip) }}" class="btn-primary">
                        ⬇️ Unduh
                    </a>
                    @can('update', $arsip)
                    <a href="{{ route('arsip.edit', $arsip) }}" class="btn-sm btn-view" style="padding:8px 14px;">
                        ✏️ Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Deskripsi --}}
        @if($arsip->deskripsi)
        <div class="card">
            <div class="card-title" style="margin-bottom:12px;">📝 Deskripsi</div>
            <p style="font-size:14px; line-height:1.7; color:var(--text-muted);">{{ $arsip->deskripsi }}</p>
        </div>
        @endif

        {{-- Catatan Penolakan --}}
        @if($arsip->status === 'ditolak' && $arsip->catatan_penolakan)
        <div class="card" style="border-color:#FECACA; background:#FEF2F2;">
            <div style="font-size:14px; font-weight:700; color:#DC2626; margin-bottom:8px;">❌ Alasan Penolakan</div>
            <p style="font-size:13.5px; color:#991B1B; line-height:1.6;">{{ $arsip->catatan_penolakan }}</p>
            @if($arsip->penyetuju)
            <div style="font-size:12px; color:#B91C1C; margin-top:8px;">
                Ditolak oleh {{ $arsip->penyetuju->nama_lengkap }} · {{ $arsip->disetujui_at?->format('d M Y, H:i') }} WIB
            </div>
            @endif
        </div>
        @endif

        {{-- Riwayat Versi --}}
        @if($arsip->revisis->count() > 0 || $arsip->arsipInduk)
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">🔄 Riwayat Versi</div>
            @if($arsip->arsipInduk)
            <div style="font-size:13px; margin-bottom:10px; color:var(--text-muted);">
                Dokumen induk:
                <a href="{{ route('arsip.show', $arsip->arsipInduk) }}" style="color:var(--bawaslu-red);">
                    {{ $arsip->arsipInduk->judul }}
                </a>
            </div>
            @endif
            @foreach($arsip->revisis as $rev)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid var(--border);">
                <div>
                    <span style="font-size:13px; font-weight:600;">Versi {{ $rev->versi }}</span>
                    <span style="font-size:12px; color:var(--text-muted); margin-left:8px;">{{ $rev->created_at->format('d M Y') }}</span>
                </div>
                <a href="{{ route('arsip.show', $rev) }}" class="btn-sm btn-view" style="font-size:11px;">Lihat</a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Aktivitas Log --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">📋 Riwayat Aktivitas</div>
            @forelse($arsip->aktivitasLogs()->with('user')->latest()->take(10)->get() as $log)
            <div style="display:flex; gap:12px; padding:10px 0; border-bottom:1px solid var(--border);">
                <div class="tl-dot {{ $log->aksi_warna_dot }}" style="flex-shrink:0;">{{ $log->aksi_ikon }}</div>
                <div>
                    <div style="font-size:13px; font-weight:600;">{{ $log->aksi_label }}</div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                        {{ $log->user->nama_lengkap }} · {{ $log->created_at->format('d M Y, H:i') }} WIB
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:20px 0;">
                <p>Belum ada aktivitas.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Sidebar Kanan --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Info Dokumen --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">ℹ️ Informasi Dokumen</div>
            <table style="width:100%; font-size:13px;">
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted); width:45%;">Kode Arsip</td>
                    <td style="padding:8px 0; font-weight:600;">{{ $arsip->kode_arsip }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Tanggal</td>
                    <td style="padding:8px 0; font-weight:600;">{{ $arsip->tanggal_dokumen->format('d M Y') }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Divisi</td>
                    <td style="padding:8px 0; font-weight:600;">{{ $arsip->divisi->nama }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Pengunggah</td>
                    <td style="padding:8px 0; font-weight:600;">{{ $arsip->uploader->nama_lengkap }}</td>
                </tr>
                @if($arsip->periode_pemilu)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Periode</td>
                    <td style="padding:8px 0; font-weight:600;">{{ $arsip->periode_pemilu }}</td>
                </tr>
                @endif
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Akses</td>
                    <td style="padding:8px 0; font-weight:600;">{{ ucfirst(str_replace('_', ' ', $arsip->tingkat_akses)) }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:8px 0; color:var(--text-muted);">Versi</td>
                    <td style="padding:8px 0; font-weight:600;">v{{ $arsip->versi }}</td>
                </tr>
                @if($arsip->status === 'disetujui' && $arsip->penyetuju)
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Disetujui</td>
                    <td style="padding:8px 0; font-weight:600;">
                        {{ $arsip->penyetuju->nama_lengkap }}<br>
                        <span style="font-size:11px; color:var(--text-muted);">{{ $arsip->disetujui_at?->format('d M Y') }}</span>
                    </td>
                </tr>
                @endif
            </table>
        </div>

        {{-- File --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">📁 File Dokumen</div>
            @forelse($arsip->files as $file)
            <div style="display:flex; align-items:center; gap:12px; padding:10px; background:var(--surface2); border-radius:8px; margin-bottom:8px;">
                <div style="font-size:24px;">{{ $file->ikon }}</div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $file->nama_asli }}
                    </div>
                    <div style="font-size:11.5px; color:var(--text-muted); margin-top:2px;">
                        {{ strtoupper($file->ekstensi) }} · {{ $file->ukuran_format }}
                    </div>
                </div>
                <a href="{{ route('arsip.download', $arsip) }}" class="tbl-btn" title="Unduh">⬇️</a>
            </div>
            @empty
            <div class="empty-state" style="padding:16px 0;"><p>Tidak ada file.</p></div>
            @endforelse
        </div>

        {{-- Tags --}}
        @if($arsip->tags)
        <div class="card">
            <div class="card-title" style="margin-bottom:12px;">🏷️ Tag</div>
            <div style="display:flex; flex-wrap:wrap; gap:6px;">
                @foreach($arsip->tags_array as $tag)
                <span style="padding:4px 10px; background:var(--surface2); border:1px solid var(--border); border-radius:20px; font-size:12px; color:var(--text-muted);">
                    {{ trim($tag) }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Aksi Admin --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
        @if($arsip->status === 'menunggu')
        <div class="card" style="border-color:#FDE68A; background:#FFFBEB;">
            <div style="font-size:13px; font-weight:700; color:#92400E; margin-bottom:12px;">⏳ Tindakan Persetujuan</div>
            <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" style="margin-bottom:8px;">
                @csrf
                <button type="submit" class="btn-primary" style="width:100%; justify-content:center;"
                    onclick="return confirm('Setujui dokumen ini?')">
                    ✓ Setujui Dokumen
                </button>
            </form>
            <button type="button" class="btn-sm btn-reject" style="width:100%; justify-content:center; padding:8px;"
                onclick="document.getElementById('modalTolakShow').classList.add('open')">
                ✗ Tolak Dokumen
            </button>
        </div>
        @endif
        @endif

    </div>
</div>

{{-- Tombol Kembali --}}
<div style="margin-top:16px;">
    <a href="{{ route('arsip.index') }}" class="btn-sm btn-view" style="padding:8px 16px;">
        ← Kembali ke Daftar Arsip
    </a>
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalTolakShow">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tolak Dokumen</div>
            <button class="modal-close" onclick="document.getElementById('modalTolakShow').classList.remove('open')">✕</button>
        </div>
        <form method="POST" action="{{ route('persetujuan.tolak', $arsip) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Catatan Penolakan <span class="required">*</span></label>
                <textarea class="form-textarea" name="catatan_penolakan" required
                    placeholder="Tuliskan alasan penolakan…"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn-sm btn-view"
                    onclick="document.getElementById('modalTolakShow').classList.remove('open')">Batal</button>
                <button type="submit" class="btn-sm" style="background:var(--bawaslu-red); color:#fff; padding:5px 14px;">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection