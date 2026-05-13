@extends('layouts.app')
@section('title', 'Kelola Divisi')
@section('breadcrumb', 'Pengaturan / Divisi')

@section('content')
<div class="page-header">
    <h1>Kelola Divisi / Bidang</h1>
    <p>Konfigurasi struktur organisasi divisi Bawaslu</p>
</div>

{{-- Session Success --}}
@if(session('success'))
<div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:12px 16px; margin-bottom:24px; font-size:13px; color:#059669;">
    ✅ {{ session('success') }}
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 360px; gap:24px; align-items:start;">

    {{-- Daftar Divisi --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border);">
            <div class="card-title">Daftar Divisi</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="padding-left:20px;">Nama Divisi</th>
                    <th>Kode</th>
                    <th>Jumlah Arsip</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($divisis as $div)
                <tr>
                    <td style="padding-left:20px;">
                        <div style="font-weight:600;">{{ $div->nama }}</div>
                        @if($div->deskripsi)
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $div->deskripsi }}</div>
                        @endif
                    </td>
                    <td><span class="category-tag">{{ $div->kode }}</span></td>
                    <td>{{ $div->arsips_count }} arsip</td>
                    <td>
                        <span class="doc-status {{ $div->is_aktif ? 'status-green' : 'status-gray' }}">
                            {{ $div->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            {{-- TOMBOL EDIT - Menggunakan LINK dengan parameter ?edit= --}}
                            <a href="{{ route('pengaturan.divisis', ['edit' => $div->id]) }}" 
                               class="tbl-btn" 
                               title="Edit"
                               style="cursor:pointer; text-decoration:none; display:inline-block;">✏️</a>
                            
                            @if($div->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.divisis.destroy', $div) }}"
                                onsubmit="return confirm('Hapus divisi ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>Belum ada divisi.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Tambah/Edit Divisi --}}
    <div class="card" id="formDivisiCard">
        <div class="card-title" style="margin-bottom:20px;" id="formDivisiTitle">
            @if(request()->has('edit') && $editDivisi = \App\Models\Divisi::find(request()->get('edit')))
                ✏️ Edit Divisi: {{ $editDivisi->nama }}
            @elseif(old('_method') == 'PUT')
                ✏️ Edit Divisi
            @else
                ➕ Tambah Divisi
            @endif
        </div>

        <form method="POST" id="formDivisi" 
              action="@if(request()->has('edit') && $editDivisi = \App\Models\Divisi::find(request()->get('edit'))) 
                        {{ route('pengaturan.divisis.update', $editDivisi) }} 
                      @elseif(old('_method') == 'PUT' && old('divisi_id')) 
                        {{ route('pengaturan.divisis.update', old('divisi_id')) }} 
                      @else 
                        {{ route('pengaturan.divisis.store') }} 
                      @endif">
            @csrf
            @if(request()->has('edit') && isset($editDivisi))
                @method('PUT')
                <input type="hidden" name="divisi_id" value="{{ $editDivisi->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="divisi_id" value="{{ old('divisi_id') }}">
            @endif

            <div class="form-group">
                <label class="form-label">Nama Divisi <span class="required">*</span></label>
                <input class="form-input @error('nama') is-invalid @enderror" 
                       type="text" 
                       name="nama" 
                       id="dNama" 
                       value="{{ old('nama', request()->has('edit') && isset($editDivisi) ? $editDivisi->nama : '') }}" 
                       placeholder="cth: Divisi Pengawasan">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kode <span class="required">*</span></label>
                <input class="form-input @error('kode') is-invalid @enderror" 
                       type="text" 
                       name="kode" 
                       id="dKode" 
                       value="{{ old('kode', request()->has('edit') && isset($editDivisi) ? $editDivisi->kode : '') }}" 
                       placeholder="cth: DIV-PENGAWASAN">
                @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea @error('deskripsi') is-invalid @enderror" 
                          name="deskripsi" 
                          id="dDeskripsi" 
                          style="min-height:70px;" 
                          placeholder="Deskripsi singkat divisi…">{{ old('deskripsi', request()->has('edit') && isset($editDivisi) ? $editDivisi->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select @error('is_aktif') is-invalid @enderror" name="is_aktif" id="dStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editDivisi) ? $editDivisi->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editDivisi) ? $editDivisi->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">💾 Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.divisis') }}" class="btn-sm btn-view" style="padding:8px 16px; text-decoration:none; display:inline-block; text-align:center;">❌ Batal</a>
                @endif
                <button type="button" class="btn-sm btn-view" onclick="resetDivisiForm()" style="padding:8px 16px;">🔄 Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetDivisiForm() {
    // Redirect ke halaman divisis tanpa parameter edit
    window.location.href = '{{ route('pengaturan.divisis') }}';
}

// Auto uppercase kode
document.getElementById('dKode').addEventListener('input', function() {
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