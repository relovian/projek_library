@extends('layouts.app')
@section('title', 'Kelola Tujuan')
@section('breadcrumb', 'Pengaturan / Tujuan')

@php
use App\Models\Tujuan;   
@endphp

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Tujuan</h1>
    <p class="text-[14px] text-abu">Konfigurasi struktur tujuan arsip</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_360px] gap-6 items-start">

    {{-- Daftar Tujuan --}}
    <div class="bg-surface border border-border rounded-[14px] overflow-hidden">
        <div class="px-5 py-6 border-b border-solid border-border">
            <div class="text-[15px] font-bold">Daftar Tujuan</div>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Tujuan</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tujuan as $tuj)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">{{ $tuj->nama }}</div>
                    </td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($tuj->is_aktif) bg-[#ECFDF5] text-[#059669]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $tuj->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-[14px] py-3">
                        <div class="flex gap-1.5">
                            {{-- TOMBOL EDIT --}}
                            <a href="{{ route('pengaturan.tujuan', ['edit' => $tuj->id]) }}" 
                               class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" 
                               title="Edit">
                                <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                            </a>
                            
                            @if($tuj->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.tujuan.destroy', $tuj) }}"
                                onsubmit="return confirm('Hapus Tujuan ini?')" class="inline">
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
                <tr><td colspan="6"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada Tujuan.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah/Edit Tujuan --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]" id="formTujuanCard">
        <div class="text-[15px] font-bold mb-5" id="formTujuanTitle">
            @if(request()->has('edit') && $editTujuan = Tujuan::find(request()->get('edit')))
                Edit Tujuan : {{ $editTujuan->nama }}
            @elseif(old('_method') == 'PUT')
                Edit Tujuan
            @else
                Tambah Tujuan
            @endif
        </div>

        <form method="POST" id="formTujuan" 
              action="@if(request()->has('edit') && $editTujuan = Tujuan::find(request()->get('edit'))) 
                        {{ route('pengaturan.tujuan.update', $editTujuan) }} 
                      @elseif(old('_method') == 'PUT' && old('tujuan_id')) 
                        {{ route('pengaturan.tujuan.update', old('tujuan_id')) }} 
                      @else 
                        {{ route('pengaturan.tujuan.store') }} 
                      @endif">
            @csrf
            @if(request()->has('edit') && isset($editTujuan))
                @method('PUT')
                <input type="hidden" name="tujuan_id" value="{{ $editTujuan->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="tujuan_id" value="{{ old('tujuan_id') }}">
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Tujuan <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama" 
                       id="tnNama" 
                       value="{{ old('nama', request()->has('edit') && isset($editTujuan) ? $editTujuan->nama : '') }}" 
                       placeholder="cth: Tujuan A">
                @error('nama')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[70px] @error('deskripsi') border-[#DC2626] border-[1.5px] @enderror" 
                          name="deskripsi" 
                          id="tnDeskripsi" 
                          placeholder="Deskripsi singkat Tujuan…">{{ old('deskripsi', request()->has('edit') && isset($editTujuan) ? $editTujuan->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="vkStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editTujuan) ? $editTujuan->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editTujuan) ? $editTujuan->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.sub_bagian') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border  text-center">Batal</a>
                @endif
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border" onclick="resetTujuanForm()">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetTujuanForm() {
    window.location.href = '{{ route('pengaturan.tujuan') }}';
};
</script>
@endpush