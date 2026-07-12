@extends('layouts.app')
@section('title', 'Kelola Sifat Surat')
@section('breadcrumb', 'Pengaturan / Sifat Surat')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Sifat Surat</h1>
    <p class="text-[14px] text-abu">Konfigurasi sifat surat untuk arsip</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_360px] gap-6 items-start">

    {{-- Daftar Sifat Surat --}}
    <div class="bg-surface border border-border rounded-[14px] overflow-hidden">
        <div class="px-5 py-6 border-b border-solid border-border">
            <div class="text-[15px] font-bold">Daftar Sifat Surat</div>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Sifat Surat</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Jumlah Arsip</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sifatSurats as $sifat)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">{{ $sifat->nama }}</div>
                        @if($sifat->deskripsi)
                        <div class="text-xs text-abu">{{ $sifat->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-[14px] py-3">{{ $sifat->arsips_count }} arsip</td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($sifat->is_aktif) bg-[#ECFDF5] text-[#059669]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $sifat->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-[14px] py-3">
                        <div class="flex gap-1.5">
                            {{-- TOMBOL EDIT --}}
                            <a href="{{ route('pengaturan.sifat_surats', ['edit' => $sifat->id]) }}" 
                               class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" 
                               title="Edit">
                                <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                            </a>
                            
                            @if($sifat->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.sifat_surats.destroy', $sifat) }}"
                                onsubmit="return confirm('Hapus sifat surat ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2">
                                    <img src="{{ asset('img/hapus.png') }}" class="w-[15px] h-[15px]" alt="">
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada sifat surat.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah/Edit Sifat Surat --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]" id="formSifatSuratCard">
        <div class="text-[15px] font-bold mb-5" id="formSifatSuratTitle">
            @if(request()->has('edit') && $editSifatSurat = \App\Models\SifatSurat::find(request()->get('edit')))
                Edit Sifat Surat: {{ $editSifatSurat->nama }}
            @elseif(old('_method') == 'PUT')
                Edit Sifat Surat
            @else
                Tambah Sifat Surat
            @endif
        </div>

        <form method="POST" id="formSifatSurat" 
              action="@if(request()->has('edit') && $editSifatSurat = \App\Models\SifatSurat::find(request()->get('edit'))) 
                        {{ route('pengaturan.sifat_surats.update', $editSifatSurat) }} 
                      @elseif(old('_method') == 'PUT' && old('sifat_surat_id')) 
                        {{ route('pengaturan.sifat_surats.update', old('sifat_surat_id')) }} 
                      @else 
                        {{ route('pengaturan.sifat_surats.store') }} 
                      @endif">
            @csrf
            @if(request()->has('edit') && isset($editSifatSurat))
                @method('PUT')
                <input type="hidden" name="sifat_surat_id" value="{{ $editSifatSurat->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="sifat_surat_id" value="{{ old('sifat_surat_id') }}">
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Sifat Surat <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama" 
                       id="ssNama" 
                       value="{{ old('nama', request()->has('edit') && isset($editSifatSurat) ? $editSifatSurat->nama : '') }}" 
                       placeholder="cth: Sifat Surat Rahasia">
                @error('nama')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[70px] @error('deskripsi') border-[#DC2626] border-[1.5px] @enderror" 
                          name="deskripsi" 
                          id="ssDeskripsi" 
                          placeholder="Deskripsi singkat sifat surat…">{{ old('deskripsi', request()->has('edit') && isset($editSifatSurat) ? $editSifatSurat->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="ssStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editSifatSurat) ? $editSifatSurat->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editSifatSurat) ? $editSifatSurat->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.sifat_surats') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-center">Batal</a>
                @endif
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4" onclick="resetSifatSuratForm()">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetSifatSuratForm() {
    window.location.href = '{{ route('pengaturan.sifat_surats') }}';
}
</script>
@endpush