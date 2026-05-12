@extends('layouts.app')
@section('title', 'Persetujuan')
@section('breadcrumb', 'Persetujuan')

@section('content')
<div class="page-header">
    <h1>Antrian Persetujuan</h1>
    <p>Dokumen yang menunggu tinjauan dan persetujuan Anda</p>
</div>

{{-- Filter --}}
<div class="filter-row">
    <form method="GET" action="{{ route('persetujuan.index') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <select class="filter-select" name="divisi_id" onchange="this.form.submit()">
            <option value="">Semua Divisi</option>
            @foreach($divisis as $div)
            <option value="{{ $div->id }}" {{ request('divisi_id') == $div->id ? 'selected' : '' }}>
                {{ $div->nama }}
            </option>
            @endforeach
        </select>
    </form>

    {{-- Bulk Approve --}}
    <form method="POST" action="{{ route('persetujuan.bulk-setujui') }}" id="formBulk">
        @csrf
        <button type="submit" class="btn-primary"
            onclick="return confirm('Setujui semua dokumen terpilih?')">
            ✓ Setujui Semua Terpilih
        </button>
    </form>
</div>

<div class="card">
    @if($arsips->count() > 0)
    <ul class="pending-list">
        @foreach($arsips as $arsip)
        <li class="pending-item">
            <div class="pending-item-header">
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <input type="checkbox" name="ids[]" value="{{ $arsip->id }}"
                        form="formBulk"
                        style="width:16px; height:16px; accent-color:var(--bawaslu-red); margin-top:3px; flex-shrink:0;">
                    <div>
                        <div class="pending-doc-name">{{ $arsip->judul }}</div>
                        <div class="pending-submitter" style="margin-top:4px;">
                            📤 {{ $arsip->uploader->nama_lengkap }}
                            · {{ $arsip->divisi->nama }}
                            · {{ $arsip->file_pertama?->ekstensi ?? '-' }}
                            · {{ $arsip->file_pertama?->ukuran_format ?? '-' }}
                        </div>
                        <div class="pending-submitter" style="margin-top:2px;">
                            🏷️ {{ $arsip->kategori->nama }}
                            · 📅 {{ $arsip->tanggal_dokumen->format('d M Y') }}
                            @if($arsip->nomor_surat)
                                · No. {{ $arsip->nomor_surat }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="pending-time">{{ $arsip->created_at->diffForHumans() }}</div>
            </div>

            <div class="pending-actions">
                {{-- Setujui --}}
                <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-sm btn-approve"
                        onclick="return confirm('Setujui dokumen ini?')">
                        ✓ Setujui
                    </button>
                </form>

                {{-- Tolak --}}
                <button type="button" class="btn-sm btn-reject"
                    onclick="openTolakModal({{ $arsip->id }}, '{{ addslashes($arsip->judul) }}')">
                    ✗ Tolak
                </button>

                {{-- Lihat --}}
                <a href="{{ route('arsip.show', $arsip) }}" class="btn-sm btn-view">
                    👁 Pratinjau
                </a>
            </div>
        </li>
        @endforeach
    </ul>

    <div style="margin-top:20px; display:flex; justify-content:flex-end;">
        {{ $arsips->withQueryString()->links() }}
    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon">✅</div>
        <p>Tidak ada dokumen yang menunggu persetujuan.</p>
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div class="modal-overlay" id="modalTolak">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tolak Dokumen</div>
            <button class="modal-close" onclick="closeTolakModal()">✕</button>
        </div>
        <p style="font-size:13.5px; color:var(--text-muted); margin-bottom:16px;">
            Berikan alasan penolakan untuk: <strong id="modalDocName"></strong>
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
                <button type="submit" class="btn-sm btn-approve" style="background:var(--bawaslu-red)">
                    Konfirmasi Tolak
                </button>
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