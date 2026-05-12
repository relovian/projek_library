@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Pengaturan / Kategori')

@section('content')
<div class="page-header">
    <h1>Kelola Kategori & Tag</h1>
    <p>Tambah, ubah, atau hapus kategori dokumen arsip</p>
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
                            <button class="tbl-btn" title="Edit"
                                onclick="editKategori({{ $kat->id }}, '{{ $kat->nama }}', '{{ $kat->kode }}', '{{ $kat->warna }}', '{{ $kat->deskripsi }}', {{ $kat->is_aktif ? 1 : 0 }})">
                                ✏️
                            </button>
                            @if($kat->arsips_count == 0)
                            <form method="POST" action="{{ route('pengaturan.kategoris.destroy', $kat) }}"
                                onsubmit="return confirm('Hapus kategori ini?')">
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
                            <div class="empty-icon">🏷️</div>
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
        <div class="card-title" style="margin-bottom:20px;" id="formTitle">➕ Tambah Kategori</div>

        <form method="POST" id="formKategori" action="{{ route('pengaturan.kategoris.store') }}">
            @csrf
            <div id="methodField"></div>

            @if(session('success'))
            <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#059669;">
                ✅ {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#DC2626;">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Nama Kategori <span class="required">*</span></label>
                <input class="form-input" type="text" name="nama" id="inputNama"
                    value="{{ old('nama') }}" placeholder="cth: Pengawasan">
            </div>

            <div class="form-group">
                <label class="form-label">Kode <span class="required">*</span></label>
                <input class="form-input" type="text" name="kode" id="inputKode"
                    value="{{ old('kode') }}" placeholder="cth: PENGAWASAN" style="text-transform:uppercase;">
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">Huruf kapital, tanpa spasi</div>
            </div>

            <div class="form-group">
                <label class="form-label">Warna Badge</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="color" name="warna" id="inputWarna"
                        value="{{ old('warna', '#6B7280') }}"
                        style="width:40px; height:36px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px;">
                    <span style="font-size:13px; color:var(--text-muted);">Warna untuk badge kategori</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea" name="deskripsi" id="inputDeskripsi"
                    placeholder="Deskripsi singkat kategori…" style="min-height:70px;">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select" name="is_aktif" id="inputStatus">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                    💾 Simpan
                </button>
                <button type="button" class="btn-sm btn-view" onclick="resetForm()" style="padding:8px 16px;">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editKategori(id, nama, kode, warna, deskripsi, isAktif) {
    document.getElementById('formTitle').textContent = '✏️ Edit Kategori';
    document.getElementById('formKategori').action = '/pengaturan/kategoris/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('inputNama').value = nama;
    document.getElementById('inputKode').value = kode;
    document.getElementById('inputWarna').value = warna;
    document.getElementById('inputDeskripsi').value = deskripsi;
    document.getElementById('inputStatus').value = isAktif;
    document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('formTitle').textContent = '➕ Tambah Kategori';
    document.getElementById('formKategori').action = '{{ route('pengaturan.kategoris.store') }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('formKategori').reset();
}

// Auto uppercase kode
document.getElementById('inputKode').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/\s/g, '_');
});
</script>
@endpush