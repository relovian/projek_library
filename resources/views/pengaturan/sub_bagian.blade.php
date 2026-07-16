@extends('layouts.app')
@section('title', 'Kelola Sub Bagian')
@section('breadcrumb', 'Pengaturan / Sub Bagian')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Sub Bagian</h1>
    <p class="text-[14px] text-abu">Konfigurasi struktur organisasi sub bagian Bawaslu</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_360px] gap-6 items-start">

    {{-- Daftar Sub Bagian --}}
    <div class="bg-surface border border-border rounded-[14px] overflow-hidden">
        <div class="px-5 py-6 border-b border-solid border-border">
            <div class="text-[15px] font-bold">Daftar Sub Bagian</div>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Sub Bagian</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subBagian as $sub)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">{{ $sub->nama }}</div>
                        @if($sub->deskripsi)
                        <div class="text-xs text-abu">{{ $sub->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($sub->is_aktif) bg-[#ECFDF5] text-[#059669]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $sub->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-[14px] py-3">
                        <div class="flex gap-1.5">
                            {{-- TOMBOL EDIT --}}
                            <a href="{{ route('pengaturan.sub_bagian', ['edit' => $sub->id]) }}" 
                               class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" 
                               title="Edit">
                                <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                            </a>
                            
                            @if($sub->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.sub_bagian.destroy', $sub) }}"
                                onsubmit="return confirm('Hapus sub bagian ini?')" class="inline">
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
                <tr><td colspan="6"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada sub bagian.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah/Edit Sub Bagian --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]" id="formSubBagianCard">
        <div class="text-[15px] font-bold mb-5" id="formSubBagianTitle">
            @if(request()->has('edit') && $editSubBagian = \App\Models\SubBagian::find(request()->get('edit')))
                Edit Sub Bagian: {{ $editSubBagian->nama }}
            @elseif(old('_method') == 'PUT')
                Edit Sub Bagian
            @else
                Tambah Sub Bagian
            @endif
        </div>

        <form method="POST" id="formSubBagian" 
              action="@if(request()->has('edit') && $editSubBagian = \App\Models\SubBagian::find(request()->get('edit'))) 
                        {{ route('pengaturan.sub_bagian.update', $editSubBagian) }} 
                      @elseif(old('_method') == 'PUT' && old('sub_bagian_id')) 
                        {{ route('pengaturan.sub_bagian.update', old('sub_bagian_id')) }} 
                      @else 
                        {{ route('pengaturan.sub_bagian.store') }} 
                      @endif">
            @csrf
            @if(request()->has('edit') && isset($editSubBagian))
                @method('PUT')
                <input type="hidden" name="sub_bagian_id" value="{{ $editSubBagian->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="sub_bagian_id" value="{{ old('sub_bagian_id') }}">
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Sub Bagian <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama" 
                       id="sbNama" 
                       value="{{ old('nama', request()->has('edit') && isset($editSubBagian) ? $editSubBagian->nama : '') }}" 
                       placeholder="cth: Sub Bagian A">
                @error('nama')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[70px] @error('deskripsi') border-[#DC2626] border-[1.5px] @enderror" 
                          name="deskripsi" 
                          id="sbDeskripsi" 
                          placeholder="Deskripsi singkat sub bagian…">{{ old('deskripsi', request()->has('edit') && isset($editSubBagian) ? $editSubBagian->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="sbStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editSubBagian) ? $editSubBagian->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editSubBagian) ? $editSubBagian->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.sub_bagian') }}" class="rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-center">Batal</a>
                @endif
                <button type="button" class="rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4" onclick="resetSubBagianForm()">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetSubBagianForm() {
    window.location.href = '{{ route('pengaturan.sub_bagian') }}';
};
</script>
@endpush