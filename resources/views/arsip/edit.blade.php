@extends('layouts.app')
@section('title', 'Edit Arsip')
@section('breadcrumb', 'Edit Arsip')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Edit Arsip</h1>
    <p class="text-[14px] text-abu">Perbarui informasi dan metadata dokumen arsip</p>
</div>

<div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px] max-w-[800px]">
    <form method="POST" action="{{ route('arsip.update', $arsip) }}">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Judul Dokumen <span class="text-[#dc2626]">*</span></label>
            <input type="text" name="judul" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('judul') ? 'border-[#dc2626]' : '' }}"
                value="{{ old('judul', $arsip->judul) }}" placeholder="Masukkan judul dokumen...">
            @error('judul')
                <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nomor Surat --}}
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                value="{{ old('nomor_surat', $arsip->nomor_surat) }}" placeholder="Contoh: 001/BAWASLU/V/2024">
        </div>

        {{-- Kategori & Divisi --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Kategori <span class="text-[#dc2626]">*</span></label>
                <select name="kategori_id" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('kategori_id') ? 'border-[#dc2626]' : '' }}">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_id', $arsip->kategori_id) == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Divisi <span class="text-[#dc2626]">*</span></label>
                <select name="divisi_id" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('divisi_id') ? 'border-[#dc2626]' : '' }}">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisis as $div)
                    <option value="{{ $div->id }}" {{ old('divisi_id', $arsip->divisi_id) == $div->id ? 'selected' : '' }}>
                        {{ $div->nama }}
                    </option>
                    @endforeach
                </select>
                @error('divisi_id')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Tanggal & Periode --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Dokumen <span class="text-[#dc2626]">*</span></label>
                <input type="date" name="tanggal_dokumen" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_dokumen') ? 'border-[#dc2626]' : '' }}"
                    value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen?->format('Y-m-d')) }}">
                @error('tanggal_dokumen')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Periode Pemilu</label>
                <input type="text" name="periode_pemilu" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                    value="{{ old('periode_pemilu', $arsip->periode_pemilu) }}" placeholder="Contoh: 2024">
            </div>
        </div>

        {{-- Tingkat Akses --}}
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tingkat Akses <span class="text-[#dc2626]">*</span></label>
            <select name="tingkat_akses" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tingkat_akses') ? 'border-[#dc2626]' : '' }}">
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
                <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tags --}}
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tags</label>
            <input type="text" name="tags" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                value="{{ old('tags', $arsip->tags) }}" placeholder="Pisahkan dengan koma, contoh: pengawasan,pemilu,2024">
            <span class="text-xs text-[#9ca3af] mt-1 block">
                Pisahkan setiap tag dengan tanda koma
            </span>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
            <textarea name="deskripsi" class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[90px]" rows="4"
                placeholder="Tuliskan deskripsi singkat tentang dokumen ini...">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
        </div>

        {{-- Info file (readonly, tidak bisa diubah di edit) --}}
        @if($arsip->file_pertama)
        <div class="mb-[18px]">
            <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">File Terlampir</label>
            <div class="flex items-center gap-[10px] bg-[#f9fafb] border border-[#e5e7eb] rounded-lg px-2 py-3 text-xs text-[#6b7280]">
                <img src="{{ $arsip->file_pertama->ikon ?? asset('img/berkas.png') }}" width="24" alt="">
                <span>{{ $arsip->file_pertama->nama_asli }}</span>
                <span class="ml-auto">{{ $arsip->file_pertama->ukuran_format }}</span>
            </div>
            <span class="text-xs text-[#9ca3af] mt-1 block">
                File tidak dapat diubah melalui halaman ini.
            </span>
        </div>
        @endif

        {{-- Tombol aksi --}}
        <div class="flex gap-[10px] mt-2">
            <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]">Simpan Perubahan</button>
            <a href="{{ route('arsip.show', $arsip) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 rounded-lg">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection