@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Selamat Datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
    <p>Ringkasan sistem pengelolaan arsip Bawaslu — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="stat-grid">
    <div class="stat-card c1">
        <div class="stat-icon">🗂️</div>
        <div class="stat-number">{{ number_format($stats['total_arsip']) }}</div>
        <div class="stat-label">Total Arsip Tersimpan</div>
    </div>
    <!-- <div class="stat-card c2">
        <div class="stat-icon">📄</div>
        <div class="stat-number">{{ $stats['unggah_bulan'] }}</div>
        <div class="stat-label">Diunggah Bulan Ini</div>
    </div> -->
    <div class="stat-card c3">
        <div class="stat-icon">⏳</div>
        <div class="stat-number">{{ $stats['menunggu'] }}</div>
        <div class="stat-label">Menunggu Persetujuan</div>
        @if($stats['menunggu'] > 0)
            <div class="stat-change pend">Perlu ditinjau</div>
        @endif
    </div>
    <div class="stat-card c4">
        <div class="stat-icon">👤</div>
        <div class="stat-number">{{ $stats['user_aktif'] }}</div>
        <div class="stat-label">Pengguna Aktif</div>
    </div>
</div>

{{-- ── GRID KONTEN ── --}}
<div class="dash-grid">
    {{-- Arsip Terbaru --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Arsip Terbaru Diunggah</div>
                <div class="card-sub">7 hari terakhir</div>
            </div>
            <a class="view-all" href="{{ route('arsip.index') }}">Lihat semua →</a>
        </div>
        <ul class="doc-list">
            @forelse($arsipTerbaru as $arsip)
            <li class="doc-item" onclick="window.location='{{ route('arsip.show', $arsip) }}'">
                <div class="doc-icon {{ $arsip->file_pertama?->ekstensi ?? 'pdf' }}">
                    {{ $arsip->file_pertama?->ikon ?? '📄' }}
                </div>
                <div class="doc-info">
                    <div class="doc-name">{{ $arsip->judul }}</div>
                    <div class="doc-meta">
                        {{ $arsip->divisi->nama }} ·
                        {{ $arsip->tanggal_dokumen->format('d M Y') }} ·
                        {{ $arsip->file_pertama?->ukuran_format ?? '-' }}
                    </div>
                </div>
                <span class="doc-status status-{{ $arsip->status_color }}">
                    {{ $arsip->status_label }}
                </span>
            </li>
            @empty
            <li class="empty-state">
                <div class="empty-icon">🗂️</div>
                <p>Belum ada arsip yang diunggah.</p>
            </li>
            @endforelse
        </ul>
    </div>

    {{-- Menunggu Persetujuan --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Perlu Ditinjau</div>
                <div class="card-sub">{{ $stats['menunggu'] }} dokumen menunggu</div>
            </div>
            <a class="view-all" href="{{ route('persetujuan.index') }}">Semua →</a>
        </div>
        <ul class="pending-list">
            @forelse($menungguPersetujuan as $arsip)
            <li class="pending-item">
                <div class="pending-item-header">
                    <div class="pending-doc-name">{{ Str::limit($arsip->judul, 40) }}</div>
                    <div class="pending-time">{{ $arsip->created_at->diffForHumans() }}</div>
                </div>
                <div class="pending-submitter">
                    📤 {{ $arsip->uploader->nama_lengkap }} · {{ $arsip->divisi->nama }}
                </div>
                <div class="pending-actions">
                    <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-sm btn-approve">✓ Setujui</button>
                    </form>
                    <button type="button" class="btn-sm btn-reject"
                        onclick="openTolakModal({{ $arsip->id }}, '{{ addslashes($arsip->judul) }}')">
                        ✗ Tolak
                    </button>
                    <a href="{{ route('arsip.show', $arsip) }}" class="btn-sm btn-view">👁 Lihat</a>
                </div>
            </li>
            @empty
            <li class="empty-state" style="padding:30px 0;">
                <div class="empty-icon">✅</div>
                <p>Tidak ada dokumen yang menunggu.</p>
            </li>
            @endforelse
        </ul>
    </div>
    @else
    {{-- Aktivitas Saya untuk Staff --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Aktivitas Terakhir Saya</div>
            <a class="view-all" href="{{ route('aktivitas.index') }}">Semua →</a>
        </div>
        <ul class="timeline">
            @forelse($aktivitasSaya as $log)
            <li class="tl-item">
                <div class="tl-dot {{ $log->aksi_warna_dot }}">{{ $log->aksi_ikon }}</div>
                <div class="tl-content">
                    <div class="tl-action">{{ $log->aksi_label }}</div>
                    @if($log->arsip)
                    <div class="tl-doc">{{ $log->arsip->judul }}</div>
                    @endif
                    <div class="tl-time">{{ $log->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </li>
            @empty
            <li class="empty-state"><p>Belum ada aktivitas.</p></li>
            @endforelse
        </ul>
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalTolak">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tolak Arsip</div>
            <button class="modal-close" onclick="closeTolakModal()">✕</button>
        </div>
        <p style="font-size:13.5px; color:var(--text-muted); margin-bottom:16px;">
            Berikan alasan penolakan untuk dokumen: <strong id="modalDocName"></strong>
        </p>
        <form id="formTolak" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Catatan Penolakan <span class="required">*</span></label>
                <textarea class="form-textarea" name="catatan_penolakan" required
                    placeholder="Tuliskan alasan penolakan…"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn-sm btn-view" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn-sm btn-approve" style="background:var(--bawaslu-red)">Konfirmasi Tolak</button>
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
    document.getElementById('modalTolak').classList.add('open');
}
function closeTolakModal() {
    document.getElementById('modalTolak').classList.remove('open');
}
</script>
@endpush