@extends('layouts.app')
@section('title', 'Kelola Divisi')
@section('breadcrumb', 'Pengaturan / Divisi')

@section('content')
<div class="page-header">
    <h1>Kelola Divisi / Bidang</h1>
    <p>Konfigurasi struktur organisasi divisi Bawaslu</p>
</div>

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
                            <button class="tbl-btn"
                                onclick="editDivisi({{ $div->id }}, '{{ addslashes($div->nama) }}', '{{ $div->kode }}', '{{ addslashes($div->deskripsi) }}', {{ $div->is_aktif ? 1 : 0 }})">✏️</button>
                            @if($div->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.divisis.destroy', $div) }}"
                                onsubmit="return confirm('Hapus divisi ini?')">
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

    {{-- Form --}}
    <div class="card" id="formDivisiCard">
        <div class="card-title" style="margin-bottom:20px;" id="formDivisiTitle">➕ Tambah Divisi</div>

        <form method="POST" id="formDivisi" action="{{ route('pengaturan.divisis.store') }}">
            @csrf
            <div id="divisiMethodField"></div>

            @if(session('success'))
            <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#059669;">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Nama Divisi <span class="required">*</span></label>
                <input class="form-input" type="text" name="nama" id="dNama" value="{{ old('nama') }}" placeholder="cth: Divisi Pengawasan">
            </div>
            <div class="form-group">
                <label class="form-label">Kode <span class="required">*</span></label>
                <input class="form-input" type="text" name="kode" id="dKode" value="{{ old('kode') }}" placeholder="cth: DIV-PENGAWASAN">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea" name="deskripsi" id="dDeskripsi" style="min-height:70px;" placeholder="Deskripsi singkat divisi…">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select" name="is_aktif" id="dStatus">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">💾 Simpan</button>
                <button type="button" class="btn-sm btn-view" onclick="resetDivisiForm()" style="padding:8px 16px;">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editDivisi(id, nama, kode, deskripsi, isAktif) {
    document.getElementById('formDivisiTitle').textContent = '✏️ Edit Divisi';
    document.getElementById('formDivisi').action = '/pengaturan/divisis/' + id;
    document.getElementById('divisiMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('dNama').value = nama;
    document.getElementById('dKode').value = kode;
    document.getElementById('dDeskripsi').value = deskripsi;
    document.getElementById('dStatus').value = isAktif;
    document.getElementById('formDivisiCard').scrollIntoView({ behavior: 'smooth' });
}
function resetDivisiForm() {
    document.getElementById('formDivisiTitle').textContent = '➕ Tambah Divisi';
    document.getElementById('formDivisi').action = '{{ route('pengaturan.divisis.store') }}';
    document.getElementById('divisiMethodField').innerHTML = '';
    document.getElementById('formDivisi').reset();
}
</script>
@endpush