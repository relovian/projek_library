@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Pengaturan / Kategori')

@section('content')
<div class="page-header">
    <h1>Kelola Kategori & Tag</h1>
    <p>Tambah, ubah, atau hapus kategori dokumen arsip</p>
</div>

<div style="margin-bottom: 20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration: none;">
        Kembali ke Pengaturan
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 360px; gap:24px; align-items:start;">

    {{-- Daftar Kategori --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <div class="card-title">Daftar Kategori</div>
            <span style="font-size:12.5px; color:var(--text-muted);">{{ $kategoris->count() }} kategori</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="padding-left:20px;">Nama Kategori</th>
                    <th>Kode</th>
                    <th>Jumlah Arsip</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kat)
                <tr>
                    <td style="padding-left:20px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:12px; height:12px; border-radius:50%; background:{{ $kat->warna }};"></div>
                            <span style="font-weight:600;">{{ $kat->nama }}</span>
                        </div>
                    </td>
                    <td><span class="category-tag">{{ $kat->kode }}</span></td>
                    <td>{{ $kat->arsips_count }} arsip</td>
                    <td>
                        @if($kat->is_aktif)
                            <span class="doc-status status-green">Aktif</span>
                        @else
                            <span class="doc-status status-gray">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            {{-- TOMBOL EDIT - Menggunakan LINK dengan parameter ?edit= --}}
                            <a href="{{ route('pengaturan.kategoris', ['edit' => $kat->id]) }}" 
                               class="tbl-btn" 
                               title="Edit"
                               style="cursor:pointer; text-decoration:none; display:flex; align-items: center;">✏️</a>
                            
                            @if($kat->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.kategoris.destroy', $kat) }}"
                                onsubmit="return confirm('Hapus kategori ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn" title="Hapus">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <img src="{{ asset('img/lock.png') }}   " alt="">
                                <!-- <img src="{{ asset('img/category.png') }}" alt=""> -->
                            </div>
                            <p>Belum ada kategori. Tambahkan kategori pertama.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah / Edit --}}
    <div class="card" id="formCard">
        <div class="card-title" style="margin-bottom:20px;" id="formTitle">
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

            <div class="form-group">
                <label class="form-label">Nama Kategori <span class="required">*</span></label>
                <input class="form-input @error('nama') is-invalid @enderror" 
                       type="text" 
                       name="nama" 
                       id="inputNama"
                       value="{{ old('nama', request()->has('edit') && isset($editKategori) ? $editKategori->nama : '') }}" 
                       placeholder="cth: Pengawasan">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kode <span class="required">*</span></label>
                <input class="form-input @error('kode') is-invalid @enderror" 
                       type="text" 
                       name="kode" 
                       id="inputKode"
                       value="{{ old('kode', request()->has('edit') && isset($editKategori) ? $editKategori->kode : '') }}" 
                       placeholder="cth: PENGAWASAN" 
                       style="text-transform:uppercase;">
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">Huruf kapital, tanpa spasi</div>
                @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Warna Badge</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="color" name="warna" id="inputWarna"
                        value="{{ old('warna', request()->has('edit') && isset($editKategori) ? $editKategori->warna : '#6B7280') }}"
                        style="width:40px; height:36px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px;">
                    <span style="font-size:13px; color:var(--text-muted);">Warna untuk badge kategori</span>
                </div>
                @error('warna')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea @error('deskripsi') is-invalid @enderror" 
                          name="deskripsi" 
                          id="inputDeskripsi"
                          placeholder="Deskripsi singkat kategori…" 
                          style="min-height:70px;">{{ old('deskripsi', request()->has('edit') && isset($editKategori) ? $editKategori->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select @error('is_aktif') is-invalid @enderror" name="is_aktif" id="inputStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editKategori) ? $editKategori->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editKategori) ? $editKategori->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.kategoris') }}" class="btn-sm btn-view" style="padding:8px 16px; text-decoration:none; display:inline-block; text-align:center;">Batal</a>
                @endif
                <button type="button" class="btn-sm btn-view" onclick="resetForm()" style="padding:8px 16px;">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetForm() {
    // Redirect ke halaman kategoris tanpa parameter edit
    window.location.href = '{{ route('pengaturan.kategoris') }}';
}

// Auto uppercase kode
document.getElementById('inputKode').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/\s/g, '_');
});
</script>

<style>
.is-invalid {
    border-color: #DC2626 !important;
    border-width: 1.5px !important;
}
.invalid-feedback {
    color: #DC2626;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}
</style>
@endpush