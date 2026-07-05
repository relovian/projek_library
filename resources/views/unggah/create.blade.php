@extends('layouts.app')
@section('title', isset($arsip) ? 'Edit Draft Arsip' : 'Unggah Arsip')
@section('breadcrumb', isset($arsip) ? 'Unggah / Edit Draft' : 'Unggah')

@section('content')
<div class="page-header">
    <h1>{{ isset($arsip) ? 'Edit Draft Arsip' : 'Unggah Arsip Baru' }}</h1>
    <p>{{ isset($arsip) ? 'Lanjutkan dan kirim draft arsip Anda' : 'Tambahkan dokumen baru ke sistem arsip Bawaslu' }}</p>
</div>

<div class="upload-layout">

    {{-- Form Utama --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:22px;">Informasi Dokumen</div>

        <form id="formUtama" method="POST"
            action="{{ isset($arsip) ? route('unggah.draft.update', $arsip) : route('unggah.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($arsip)) @method('PUT') @endif

            {{-- Judul --}}
            <div class="form-group">
                <label class="form-label">Judul Dokumen <span class="required">*</span></label>
                <input class="form-input {{ $errors->has('judul') ? 'is-error' : '' }}"
                    type="text" name="judul"
                    value="{{ old('judul', $arsip->judul ?? '') }}"
                    placeholder="Masukkan judul dokumen…">
                @error('judul')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kategori & Divisi --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select class="form-select {{ $errors->has('kategori_id') ? 'is-error' : '' }}" name="kategori_id">
                        <option value="">Pilih kategori…</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}"
                            {{ old('kategori_id', $arsip->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Divisi / Bidang <span class="required">*</span></label>
                    <select class="form-select {{ $errors->has('divisi_id') ? 'is-error' : '' }}" name="divisi_id">
                        <option value="">Pilih divisi…</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}"
                            {{ old('divisi_id', $arsip->divisi_id ?? '') == $div->id ? 'selected' : '' }}>
                            {{ $div->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Tanggal & Nomor Surat --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Dokumen <span class="required">*</span></label>
                    <input class="form-input {{ $errors->has('tanggal_dokumen') ? 'is-error' : '' }}"
                        type="date" name="tanggal_dokumen"
                        value="{{ old('tanggal_dokumen', isset($arsip) ? $arsip->tanggal_dokumen?->format('Y-m-d') : date('Y-m-d')) }}">
                    @error('tanggal_dokumen')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Surat / Referensi <span class="required">*</span></label>
                    <input class="form-input {{ $errors->has('nomor_surat') ? 'is-error' : '' }}"
                        type="text" name="nomor_surat"
                        value="{{ old('nomor_surat', $arsip->nomor_surat ?? '') }}"
                        placeholder="Cth: No. 045/SK/2026">
                    @error('nomor_surat')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Periode Pemilu --}}
            <div class="form-group">
                <label class="form-label">Periode Pemilu <span class="required">*</span></label>
                <select class="form-select {{ $errors->has('periode_pemilu') ? 'is-error' : '' }}" name="periode_pemilu">
                    <option value="">Pilih periode…</option>
                    @foreach(['Pemilu 2024','Pilkada 2024','Pemilu 2029','Non-Pemilu'] as $periode)
                    <option value="{{ $periode }}"
                        {{ old('periode_pemilu', $arsip->periode_pemilu ?? '') == $periode ? 'selected' : '' }}>
                        {{ $periode }}
                    </option>
                    @endforeach
                </select>
                @error('periode_pemilu')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tags --}}
            <div class="form-group">
                <label class="form-label">Tag / Label <span class="required">*</span></label>
                <input class="form-input {{ $errors->has('tags') ? 'is-error' : '' }}"
                    type="text" name="tags"
                    value="{{ old('tags', $arsip->tags ?? '') }}"
                    placeholder="Pisahkan tag dengan koma, cth: laporan, temuan, jatim">
                @error('tags')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea {{ $errors->has('deskripsi') ? 'is-error' : '' }}"
                    name="deskripsi"
                    placeholder="Ringkasan singkat isi dokumen…">{{ old('deskripsi', $arsip->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tingkat Akses --}}
            <div class="form-group">
                <label class="form-label">Tingkat Akses <span class="required">*</span></label>
                <select class="form-select {{ $errors->has('tingkat_akses') ? 'is-error' : '' }}" name="tingkat_akses">
                    @foreach([
                        'publik_internal' => 'Publik Internal',
                        'divisi'          => 'Divisi Terkait',
                        'pimpinan'        => 'Pimpinan',
                        'rahasia'         => 'Rahasia',
                    ] as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('tingkat_akses', $arsip->tingkat_akses ?? 'publik_internal') == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('tingkat_akses')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label class="form-label">
                    File Dokumen
                    @if(!isset($arsip)) <span class="required">*</span> @endif
                </label>

                {{-- Info file lama kalau edit draft --}}
                @if(isset($arsip) && $arsip->file_pertama)
                <div style="
                    display:flex; align-items:center; gap:10px;
                    background:#f0fdf4; border:1px solid #bbf7d0;
                    border-radius:8px; padding:10px 14px;
                    font-size:13px; color:#15803d; margin-bottom:10px;
                ">
                    <img src="{{ $arsip->file_pertama->ikon ?? asset('img/berkas.png') }}" width="20" alt="">
                    <span>File saat ini: <strong>{{ $arsip->file_pertama->nama_asli }}</strong></span>
                    <span style="color:#6b7280; font-size:12px;">({{ $arsip->file_pertama->ukuran_format }})</span>
                </div>
                <p style="font-size:12px; color:#6b7280; margin-bottom:8px;">
                    Biarkan kosong jika tidak ingin mengganti file.
                </p>
                @endif

                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-zone-icon">
                        <img src="{{ asset('img/folder_kosong.png') }}" alt="">
                    </div>
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
                @error('file')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" name="aksi" value="kirim" class="btn-primary" style="flex:1; justify-content:center;">
                    ⬆ {{ isset($arsip) ? 'Kirim untuk Ditinjau' : 'Kirim untuk Ditinjau' }}
                </button>
                <button type="submit" name="aksi" value="draft" class="btn-sm btn-view" style="padding:9px 18px; font-size:13px;">
                    {{ isset($arsip) ? 'Simpan Draft' : 'Simpan Draft' }}
                </button>
                @if(isset($arsip))
                <a href="{{ route('arsip.index') }}" class="btn-sm btn-view" style="padding:9px 18px; font-size:13px;">
                    Batal
                </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Sidebar kanan --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Revisi --}}
        @if(!isset($arsip))
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Unggah Revisi</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:14px; line-height:1.6;">
                Jika ini adalah versi terbaru dari dokumen yang sudah ada, masukkan ID arsip aslinya:
            </p>
            <input class="form-input" type="text" name="arsip_induk_id" form="formUtama"
                placeholder="ID arsip yang akan direvisi…">
            <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">
                Versi baru akan terhubung dengan riwayat dokumen asli.
            </div>
        </div>
        @endif

        {{-- Daftar Draft --}}
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
                    {{-- Arahkan ke route edit draft yang benar --}}
                    <a href="{{ route('unggah.draft.edit', $draft) }}"
                       class="btn-sm btn-view"
                       style="font-size:11px; {{ isset($arsip) && $arsip->id === $draft->id ? 'background:#FDE68A;' : '' }}">
                        {{ isset($arsip) && $arsip->id === $draft->id ? 'Sedang diedit' : 'Lanjutkan' }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Panduan --}}
        <div class="card" style="background:var(--surface2);">
            <div style="font-size:13px; font-weight:700; margin-bottom:10px;">Panduan Unggah</div>
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