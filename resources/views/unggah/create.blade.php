@extends('layouts.app')
@section('title', 'Unggah Arsip')
@section('breadcrumb', 'Unggah')

@section('content')
<div class="page-header">
    <h1>Unggah Arsip Baru</h1>
    <p>Tambahkan dokumen baru ke sistem arsip Bawaslu</p>
</div>

<div class="upload-layout">
    {{-- Form Metadata --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:22px">📋 Informasi Dokumen</div>

        <form method="POST" action="{{ route('unggah.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#DC2626;">
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin-top:6px; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Judul Dokumen <span class="required">*</span></label>
                <input class="form-input" type="text" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul dokumen…">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select class="form-select" name="kategori_id">
                        <option value="">Pilih kategori…</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Divisi / Bidang <span class="required">*</span></label>
                    <select class="form-select" name="divisi_id">
                        <option value="">Pilih divisi…</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}" {{ old('divisi_id') == $div->id ? 'selected' : '' }}>
                            {{ $div->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Dokumen <span class="required">*</span></label>
                    <input class="form-input" type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Surat / Referensi</label>
                    <input class="form-input" type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Cth: No. 045/SK/2026">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Periode Pemilu</label>
                <select class="form-select" name="periode_pemilu">
                    <option value="">Pilih periode…</option>
                    @foreach(['Pemilu 2024','Pilkada 2024','Pemilu 2029','Non-Pemilu'] as $periode)
                    <option value="{{ $periode }}" {{ old('periode_pemilu') == $periode ? 'selected' : '' }}>{{ $periode }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tag / Label</label>
                <input class="form-input" type="text" name="tags" value="{{ old('tags') }}" placeholder="Pisahkan tag dengan koma, cth: laporan, temuan, jatim">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea" name="deskripsi" placeholder="Ringkasan singkat isi dokumen…">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Tingkat Akses <span class="required">*</span></label>
                <select class="form-select" name="tingkat_akses">
                    <option value="publik_internal" {{ old('tingkat_akses') == 'publik_internal' ? 'selected' : '' }}>Publik Internal</option>
                    <option value="divisi" {{ old('tingkat_akses') == 'divisi' ? 'selected' : '' }}>Divisi Terkait</option>
                    <option value="pimpinan" {{ old('tingkat_akses') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="rahasia" {{ old('tingkat_akses') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                </select>
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label class="form-label">File Dokumen <span class="required">*</span></label>
                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-zone-icon">📂</div>
                    <h3>Seret & Jatuhkan File</h3>
                    <p>atau klik untuk pilih file</p>
                    <div class="upload-formats">
                        <span class="format-tag">PDF</span>
                        <span class="format-tag">DOCX</span>
                        <span class="format-tag">XLSX</span>
                        <span class="format-tag">JPG</span>
                        <span class="format-tag">PNG</span>
                    </div>
                    <p style="margin-top:10px; font-size:12px;" id="fileName">Maks. 50 MB per file</p>
                </div>
                <input type="file" id="fileInput" name="file" style="display:none"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                    onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? 'Maks. 50 MB per file'">
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" name="aksi" value="kirim" class="btn-primary" style="flex:1; justify-content:center;">
                    ⬆️ Kirim untuk Ditinjau
                </button>
                <button type="submit" name="aksi" value="draft" class="btn-sm btn-view" style="padding:9px 18px; font-size:13px;">
                    💾 Simpan Draft
                </button>
            </div>
        </form>
    </div>

    {{-- Sidebar kanan --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Revisi --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">🔄 Unggah Revisi</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:14px; line-height:1.6;">
                Jika ini adalah versi terbaru dari dokumen yang sudah ada, masukkan ID arsip aslinya:
            </p>
            <input class="form-input" type="text" name="arsip_induk_id" form="formUtama"
                placeholder="🔍  ID arsip yang akan direvisi…">
            <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">
                Versi baru akan terhubung dengan riwayat dokumen asli.
            </div>
        </div>

        {{-- Draft --}}
        @if($drafts->count() > 0)
        <div class="card" style="background:#FFFBEB; border-color:#FDE68A;">
            <div style="font-size:13px; font-weight:700; margin-bottom:8px; color:#92400E;">
                ⚠️ Draft yang Belum Selesai
            </div>
            <div style="font-size:13px; color:#78350F; margin-bottom:12px;">
                Anda memiliki {{ $drafts->count() }} draft tersimpan.
            </div>
            <ul style="list-style:none; display:flex; flex-direction:column; gap:8px;">
                @foreach($drafts as $draft)
                <li style="font-size:12.5px; display:flex; justify-content:space-between; align-items:center;">
                    <span>📄 {{ Str::limit($draft->judul, 30) }}</span>
                    <a href="{{ route('arsip.edit', $draft) }}" class="btn-sm btn-view" style="font-size:11px;">Lanjutkan</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Panduan --}}
        <div class="card" style="background:var(--surface2);">
            <div style="font-size:13px; font-weight:700; margin-bottom:10px;">📌 Panduan Unggah</div>
            <ul style="font-size:12.5px; color:var(--text-muted); line-height:1.8; padding-left:16px;">
                <li>Pastikan nama file jelas dan deskriptif</li>
                <li>Ukuran file maksimal 50 MB</li>
                <li>Format yang diterima: PDF, DOCX, XLSX, JPG, PNG</li>
                <li>Dokumen akan ditinjau sebelum disetujui</li>
                <li>Draft bisa dilanjutkan kapan saja</li>
            </ul>
        </div>

    </div>
</div>
@endsection