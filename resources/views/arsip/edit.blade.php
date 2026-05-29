@extends('layouts.app')
@section('title', 'Edit Arsip')
@section('breadcrumb', 'Edit Arsip')

@section('content')
<div class="page-header">
    <h1>Edit Arsip</h1>
    <p>Perbarui informasi dan metadata dokumen arsip</p>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="{{ route('arsip.update', $arsip) }}">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="form-group">
            <label class="form-label">Judul Dokumen <span style="color:#dc2626">*</span></label>
            <input type="text" name="judul" class="form-input {{ $errors->has('judul') ? 'is-error' : '' }}"
                value="{{ old('judul', $arsip->judul) }}" placeholder="Masukkan judul dokumen...">
            @error('judul')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nomor Surat --}}
        <div class="form-group">
            <label class="form-label">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-input"
                value="{{ old('nomor_surat', $arsip->nomor_surat) }}" placeholder="Contoh: 001/BAWASLU/V/2024">
        </div>

        {{-- Kategori & Divisi --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                <select name="kategori_id" class="form-input {{ $errors->has('kategori_id') ? 'is-error' : '' }}">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_id', $arsip->kategori_id) == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Divisi <span style="color:#dc2626">*</span></label>
                <select name="divisi_id" class="form-input {{ $errors->has('divisi_id') ? 'is-error' : '' }}">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisis as $div)
                    <option value="{{ $div->id }}" {{ old('divisi_id', $arsip->divisi_id) == $div->id ? 'selected' : '' }}>
                        {{ $div->nama }}
                    </option>
                    @endforeach
                </select>
                @error('divisi_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Tanggal & Periode --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">Tanggal Dokumen <span style="color:#dc2626">*</span></label>
                <input type="date" name="tanggal_dokumen" class="form-input {{ $errors->has('tanggal_dokumen') ? 'is-error' : '' }}"
                    value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen?->format('Y-m-d')) }}">
                @error('tanggal_dokumen')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Periode Pemilu</label>
                <input type="text" name="periode_pemilu" class="form-input"
                    value="{{ old('periode_pemilu', $arsip->periode_pemilu) }}" placeholder="Contoh: 2024">
            </div>
        </div>

        {{-- Tingkat Akses --}}
        <div class="form-group">
            <label class="form-label">Tingkat Akses <span style="color:#dc2626">*</span></label>
            <select name="tingkat_akses" class="form-input {{ $errors->has('tingkat_akses') ? 'is-error' : '' }}">
                @foreach([
                    'publik_internal' => 'Publik Internal (semua staff)',
                    'divisi'          => 'Divisi (hanya divisi terkait)',
                    'pimpinan'        => 'Pimpinan',
                    'rahasia'         => 'Rahasia',
                ] as $val => $label)
                <option value="{{ $val }}" {{ old('tingkat_akses', $arsip->tingkat_akses) === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            @error('tingkat_akses')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tags --}}
        <div class="form-group">
            <label class="form-label">Tags</label>
            <input type="text" name="tags" class="form-input"
                value="{{ old('tags', $arsip->tags) }}" placeholder="Pisahkan dengan koma, contoh: pengawasan,pemilu,2024">
            <span style="font-size:11px; color:#9ca3af; margin-top:4px; display:block;">
                Pisahkan setiap tag dengan tanda koma
            </span>
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-input" rows="4"
                placeholder="Tuliskan deskripsi singkat tentang dokumen ini...">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
        </div>

        {{-- Info file (readonly, tidak bisa diubah di edit) --}}
        @if($arsip->file_pertama)
        <div class="form-group">
            <label class="form-label">File Terlampir</label>
            <div style="
                display:flex; align-items:center; gap:10px;
                background:#f9fafb; border:1px solid #e5e7eb;
                border-radius:8px; padding:10px 14px;
                font-size:13px; color:#6b7280;
            ">
                <img src="{{ $arsip->file_pertama->ikon ?? asset('img/berkas.png') }}" width="24" alt="">
                <span>{{ $arsip->file_pertama->nama_asli }}</span>
                <span style="margin-left:auto;">{{ $arsip->file_pertama->ukuran_format }}</span>
            </div>
            <span style="font-size:11px; color:#9ca3af; margin-top:4px; display:block;">
                File tidak dapat diubah melalui halaman ini.
            </span>
        </div>
        @endif

        {{-- Tombol aksi --}}
        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('arsip.show', $arsip) }}" class="btn-sm btn-view"
                style="padding:8px 16px; border-radius:8px; text-decoration:none; font-size:13px;">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection