@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Pengaturan / Kategori')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Kategori & Tag</h1>
    <p class="text-[14px] text-abu">Tambah, ubah, atau hapus kategori dokumen arsip</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_360px] gap-6 items-start">

    {{-- Daftar Kategori --}}
    <div class="bg-surface border border-border rounded-[14px] overflow-hidden">
        <div class="px-5 py-6 border-b border-solid border-border flex justify-between items-center">
            <div class="text-[15px] font-bold">Daftar Kategori</div>
            <span class="text-xs text-abu">{{ $kategoris->count() }} kategori</span>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Kategori</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Kode</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Jumlah Arsip</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kat)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:{{ $kat->warna }}"></div>
                            <span class="font-semibold">{{ $kat->nama }}</span>
                        </div>
                    </td>
                    <td class="px-[14px] py-3"><span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border">{{ $kat->kode }}</span></td>
                    <td class="px-[14px] py-3">{{ $kat->arsips_count }} arsip</td>
                    <td class="px-[14px] py-3">
                        @if($kat->is_aktif)
                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] bg-[#ECFDF5] text-[#059669]">Aktif</span>
                        @else
                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] bg-[#F5F5F5] text-[#6B7280]">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-[14px] py-3">
                        <div class="flex gap-1.5">
                            {{-- TOMBOL EDIT - Menggunakan LINK dengan parameter ?edit= --}}
                            <a href="{{ route('pengaturan.kategoris', ['edit' => $kat->id]) }}" 
                               class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" 
                               title="Edit">✏️</a>
                            
                            @if($kat->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.kategoris.destroy', $kat) }}"
                                onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2" title="Hapus">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="text-center py-10 px-5 text-abu">
                            <div class="text-[40px] mb-[10px]">
                                <img src="{{ asset('img/category.png') }}" alt="">
                            </div>
                            <p class="text-[14px]">Belum ada kategori. Tambahkan kategori pertama.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah / Edit --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]" id="formCard">
        <div class="text-[15px] font-bold mb-5" id="formTitle">
            @if(request()->has('edit') && $editKategori = \App\Models\Kategori::find(request()->get('edit')))
                Edit Kategori: {{ $editKategori->nama }}
            @elseif(old('_method') == 'PUT')
                Edit Kategori
            @else
                Tambah Kategori
            @endif
        </div>

        <form method="POST" id="formKategori" 
              action="@if(request()->has('edit') && $editKategori = \App\Models\Kategori::find(request()->get('edit'))) 
                        {{ route('pengaturan.kategoris.update', $editKategori) }} 
                      @elseif(old('_method') == 'PUT' && old('kategori_id')) 
                        {{ route('pengaturan.kategoris.update', old('kategori_id')) }} 
                      @else 
                        {{ route('pengaturan.kategoris.store') }} 
                      @endif">
            @csrf
            @if(request()->has('edit') && $editKategori = \App\Models\Kategori::find(request()->get('edit')))
                @method('PUT')
                <input type="hidden" name="kategori_id" value="{{ $editKategori->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="kategori_id" value="{{ old('kategori_id') }}">
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Kategori <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama" 
                       id="inputNama"
                       value="{{ old('nama', request()->has('edit') && isset($editKategori) ? $editKategori->nama : '') }}" 
                       placeholder="cth: Pengawasan">
                @error('nama')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Kode <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] uppercase @error('kode') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="kode" 
                       id="inputKode"
                       value="{{ old('kode', request()->has('edit') && isset($editKategori) ? $editKategori->kode : '') }}" 
                       placeholder="cth: PENGAWASAN">
                <div class="text-xs text-abu mt-1">Huruf kapital, tanpa spasi</div>
                @error('kode')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Warna Badge</label>
                <div class="flex items-center gap-[10px]">
                    <input type="color" name="warna" id="inputWarna"
                        value="{{ old('warna', request()->has('edit') && isset($editKategori) ? $editKategori->warna : '#6B7280') }}"
                        class="w-10 h-9 border border-solid border-border rounded-lg cursor-pointer p-[2px]">
                    <span class="text-xs text-abu">Warna untuk badge kategori</span>
                </div>
                @error('warna')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Deskripsi</label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[70px] @error('deskripsi') border-[#DC2626] border-[1.5px] @enderror" 
                          name="deskripsi" 
                          id="inputDeskripsi"
                          placeholder="Deskripsi singkat kategori…" >{{ old('deskripsi', request()->has('edit') && isset($editKategori) ? $editKategori->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="inputStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editKategori) ? $editKategori->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editKategori) ? $editKategori->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.kategoris') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-center">Batal</a>
                @endif
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4" onclick="resetForm()">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetForm() {
    window.location.href = '{{ route('pengaturan.kategoris') }}';
}

document.getElementById('inputKode').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/\s/g, '_');
});
</script>
@endpush