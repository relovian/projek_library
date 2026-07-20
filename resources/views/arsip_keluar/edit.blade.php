@extends('layouts.app')
@section('title', 'Edit Arsip Keluar')
@section('breadcrumb', 'Arsip Keluar / Edit')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Edit Arsip Keluar</h1>
    <p class="text-[14px] text-abu">Perbarui informasi arsip keluar</p>
</div>

<div class="mt-4 mb-5">
    <a href="{{ route('arsip.index', ['tab' => 'keluar']) }}"
        class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border border-border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam">
        Kembali ke Arsip Keluar
    </a>
</div>

<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Form Edit Arsip Keluar</div>

        <form method="POST" action="{{ route('arsip-keluar.update', $arsipKeluar) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama File <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="text" name="nama_file"
                        value="{{ old('nama_file', $arsipKeluar->nama_file) }}">
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Perihal <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="text" name="perihal"
                        value="{{ old('perihal', $arsipKeluar->perihal) }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Klasifikasi <span class="text-bawaslu-red">*</span></label>
                    <select name="klasifikasi_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($klasifikasi as $klas)
                        <option value="{{ $klas->id }}" {{ old('klasifikasi_id', $arsipKeluar->klasifikasi_id) == $klas->id ? 'selected' : '' }}>{{ $klas->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Sifat <span class="text-bawaslu-red">*</span></label>
                    <select name="sifat_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($sifat as $s)
                        <option value="{{ $s->id }}" {{ old('sifat_id', $arsipKeluar->sifat_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Sub Bagian <span class="text-bawaslu-red">*</span></label>
                    <select name="sub_bagian_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($subBagian as $sb)
                        <option value="{{ $sb->id }}" {{ old('sub_bagian_id', $arsipKeluar->sub_bagian_id) == $sb->id ? 'selected' : '' }}>{{ $sb->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Verifikator <span class="text-bawaslu-red">*</span></label>
                    <select name="verifikator_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($verifikator as $v)
                        <option value="{{ $v->id }}" {{ old('verifikator_id', $arsipKeluar->verifikator_id) == $v->id ? 'selected' : '' }}>
                            {{ $v->user->nama_lengkap ?? $v->user->name ?? 'Verifikator' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tujuan <span class="text-bawaslu-red">*</span></label>
                    <select name="tujuan_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($tujuan as $t)
                        <option value="{{ $t->id }}" {{ old('tujuan_id', $arsipKeluar->tujuan_id) == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Pembuat <span class="text-bawaslu-red">*</span></label>
                    <select name="pembuat_id"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                        <option value="">-- Pilih --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('pembuat_id', $arsipKeluar->pembuat_id) == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Surat <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="date" name="tanggal_surat"
                        value="{{ old('tanggal_surat', $arsipKeluar->tanggal_surat?->format('Y-m-d')) }}">
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Unggah <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="date" name="tanggal_unggah"
                        value="{{ old('tanggal_unggah', $arsipKeluar->tanggal_unggah?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="flex gap-[10px] mt-2">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    Simpan Perubahan
                </button>
                <a href="{{ route('arsip.index', ['tab' => 'keluar']) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="flex flex-col gap-4">
        <div class="bg-surface2 border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-xs font-bold mb-3">Info Arsip</div>
            <div class="text-[12px] text-abu space-y-2">
                <div><span class="font-semibold text-hitam">Kode Arsip:</span> {{ $arsipKeluar->kode_arsip_keluar }}</div>
                <div><span class="font-semibold text-hitam">Link File:</span> 
                    @if($arsipKeluar->link_file)
                    <a href="{{ $arsipKeluar->link_file }}" target="_blank" class="text-bawaslu-red underline">Buka</a>
                    @else
                    -
                    @endif
                </div>
                <div><span class="font-semibold text-hitam">Diupload:</span> {{ $arsipKeluar->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection