@extends('layouts.app')
@section('title', isset($arsip) ? 'Edit Draft Arsip' : 'Unggah Arsip')
@section('breadcrumb', isset($arsip) ? 'Unggah / Edit Draft' : 'Unggah')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">{{ isset($arsip) ? 'Edit Draft Arsip' : 'Unggah Arsip Baru' }}</h1>
    <p class="text-[14px] text-abu">{{ isset($arsip) ? 'Lanjutkan dan kirim draft arsip Anda' : 'Tambahkan dokumen baru ke sistem arsip Bawaslu' }}</p>
</div>

<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">

    {{-- Form Utama --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Informasi Dokumen</div>

        <form id="formUtama" method="POST"
            action="{{ isset($arsip) ? route('unggah.draft.update', $arsip) : route('unggah.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($arsip)) @method('PUT') @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Judul Dokumen <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('judul') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="judul"
                    value="{{ old('judul', $arsip->judul ?? '') }}"
                    placeholder="Masukkan judul dokumen…">
                @error('judul')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Kategori <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('kategori_id') ? 'border-[#dc2626]' : '' }}" name="kategori_id">
                        <option value="">Pilih kategori…</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}"
                            {{ old('kategori_id', $arsip->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Divisi / Bidang <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('divisi_id') ? 'border-[#dc2626]' : '' }}" name="divisi_id">
                        <option value="">Pilih divisi…</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}"
                            {{ old('divisi_id', $arsip->divisi_id ?? '') == $div->id ? 'selected' : '' }}>
                            {{ $div->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Tanggal & Nomor Surat --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Dokumen <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_dokumen') ? 'border-[#dc2626]' : '' }}"
                        type="date" name="tanggal_dokumen"
                        value="{{ old('tanggal_dokumen', isset($arsip) ? $arsip->tanggal_dokumen?->format('Y-m-d') : date('Y-m-d')) }}">
                    @error('tanggal_dokumen')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nomor Surat / Referensi <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('nomor_surat') ? 'border-[#dc2626]' : '' }}"
                        type="text" name="nomor_surat"
                        value="{{ old('nomor_surat', $arsip->nomor_surat ?? '') }}"
                        placeholder="Cth: No. 045/SK/2026">
                    @error('nomor_surat')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Periode Pemilu --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Periode Pemilu <span class="text-bawaslu-red">*</span></label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('periode_pemilu') ? 'border-[#dc2626]' : '' }}" name="periode_pemilu">
                    <option value="">Pilih periode…</option>
                    @foreach(['Pemilu 2024','Pilkada 2024','Pemilu 2029','Non-Pemilu'] as $periode)
                    <option value="{{ $periode }}"
                        {{ old('periode_pemilu', $arsip->periode_pemilu ?? '') == $periode ? 'selected' : '' }}>
                        {{ $periode }}
                    </option>
                    @endforeach
                </select>
                @error('periode_pemilu')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tags --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tag / Label <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tags') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="tags"
                    value="{{ old('tags', $arsip->tags ?? '') }}"
                    placeholder="Pisahkan tag dengan koma, cth: laporan, temuan, jatim">
                @error('tags')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[90px] {{ $errors->has('deskripsi') ? 'border-[#dc2626]' : '' }}"
                    name="deskripsi"
                    placeholder="Ringkasan singkat isi dokumen…">{{ old('deskripsi', $arsip->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tingkat Akses --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tingkat Akses <span class="text-bawaslu-red">*</span></label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tingkat_akses') ? 'border-[#dc2626]' : '' }}" name="tingkat_akses">
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
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- File Upload --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    File Dokumen
                    @if(!isset($arsip)) <span class="text-bawaslu-red">*</span> @endif
                </label>

                {{-- Info file lama kalau edit draft --}}
                @if(isset($arsip) && $arsip->file_pertama)
                <div class="flex items-center gap-[10px] bg-[#f0fdf4] border border-solid border-[#bbf7d0] rounded-lg px-2 py-3 text-xs text-[#15803d] mt-2">
                    <img src="{{ $arsip->file_pertama->ikon ?? asset('img/berkas.png') }}" width="20" alt="">
                    <span>File saat ini: <strong>{{ $arsip->file_pertama->nama_asli }}</strong></span>
                    <span class="text-abu text-sm ">({{ $arsip->file_pertama->ukuran_format }})</span>
                </div>
                <p class="text-xs text-abu mt-2">
                    Biarkan kosong jika tidak ingin mengganti file.
                </p>
                @endif

                <div class="border-2 border-dashed border-border rounded-[14px] py-12 text-center cursor-pointer transition-colors duration-200 hover:border-bawaslu-red hover:bg-[#FEF2F2] bg-surface2" onclick="document.getElementById('fileInput').click()">
                    <div class="text-5xl mb-4">
                        <img src="{{ asset('img/folder_kosong.png') }}" class="mx-auto" alt="">
                    </div>
                    <h3 class="text-base font-bold mb-1.5">Seret & Jatuhkan File</h3>
                    <p class="text-[13px] text-abu">atau klik untuk pilih file</p>
                    <div class="flex gap-2 justify-center mt-4 flex-wrap">
                        <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">PDF</span>
                        <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">DOCX</span>
                        <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">XLSX</span>
                        <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">JPG</span>
                        <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">PNG</span>
                    </div>
                    <p class="mt-2 text-xs text-[#6B6560]" id="fileName">Maks. 50 MB per file</p>
                </div>
                <input type="file" id="fileInput" name="file" class="hidden"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                    onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? 'Maks. 50 MB per file'">
                @error('file')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-[10px] mt-2">
                <button type="submit" name="aksi" value="kirim" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    {{ isset($arsip) ? 'Kirim untuk Ditinjau' : 'Kirim untuk Ditinjau' }}
                </button>
                <button type="submit" name="aksi" value="draft" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    {{ isset($arsip) ? 'Simpan Draft' : 'Simpan Draft' }}
                </button>
                @if(isset($arsip))
                <a href="{{ route('arsip.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    Batal
                </a>
                @endif
            </div>

        </form>
    </div>

    <div class="flex flex-col gap-4">

        @if(!isset($arsip))
        <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-[15px] font-bold mb-4">Unggah Revisi</div>
            <p class="text-xs text-abu mt-3 leading-[1.6]">
                Jika ini adalah versi terbaru dari dokumen yang sudah ada, masukkan ID arsip aslinya:
            </p>
            <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]" type="text" name="arsip_induk_id" form="formUtama"
                placeholder="ID arsip yang akan direvisi…">
            <div class="text-xs text-abu mt-2">
                Versi baru akan terhubung dengan riwayat dokumen asli.
            </div>
        </div>
        @endif

        {{-- Daftar Draft --}}
        @if($drafts->count() > 0)
        <div class="bg-surface border border-[#FDE68A] rounded-[14px] p-6 mb-[15px] bg-[#FFFBEB]">
            <div class="text-xs font-bold mt-2 text-[#92400E]">
                 Draft yang Belum Selesai
            </div>
            <div class="text-xs text-[#78350F] mt-3">
                Anda memiliki {{ $drafts->count() }} draft tersimpan.
            </div>
            <ul class="list-none flex flex-col gap-[8px]">
                @foreach($drafts as $draft)
                <li class="text-xs flex justify-between items-center">
                    <span> {{ Str::limit($draft->judul, 30) }}</span>
                    <a href="{{ route('unggah.draft.edit', $draft) }}"
                       class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border text-xs"
                        {{ isset($arsip) && $arsip->id === $draft->id ? 'background:#FDE68A;' : '' }}">
                        {{ isset($arsip) && $arsip->id === $draft->id ? 'Sedang diedit' : 'Lanjutkan' }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Panduan --}}
        <div class="bg-surface2 border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-xs font-bold mt-2">Panduan Unggah</div>
            <ul class="text-xs text-abu leading-[1.8] pl-4">
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