@extends('layouts.app')
@section('title', 'Kelola User')
@section('breadcrumb', 'Pengaturan / User')

@section('content')
<div class="page-header">
    <h1>Kelola User & Hak Akses</h1>
    <p>Manajemen akun pengguna dan permission sistem</p>
</div>

<div style="display:grid; grid-template-columns:1fr 360px; gap:24px; align-items:start;">

    {{-- Daftar User --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <div class="card-title">Daftar Pengguna</div>
            <span style="font-size:12.5px; color:var(--text-muted);">{{ $users->total() }} pengguna</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="padding-left:20px;">Nama</th>
                    <th>Role</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td style="padding-left:20px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:var(--bawaslu-red); display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0;">
                                {{ $u->inisial }}
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px;">{{ $u->nama_lengkap }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted);">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="doc-status {{ $u->role === 'admin' ? 'status-red' : ($u->role === 'pimpinan' ? 'status-blue' : 'status-gray') }}">
                            {{ $u->role_label }}
                        </span>
                    </td>
                    <td style="font-size:13px;">{{ $u->divisi?->nama ?? '-' }}</td>
                    <td>
                        <span class="doc-status {{ $u->is_aktif ? 'status-green' : 'status-gray' }}">
                            {{ $u->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="tbl-btn" title="Edit"
                                onclick="editUser({{ $u->id }}, '{{ addslashes($u->nama_lengkap) }}', '{{ $u->email }}', '{{ $u->nip }}', '{{ $u->role }}', '{{ $u->divisi_id }}', {{ $u->is_aktif ? 1 : 0 }})">✏️</button>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('pengaturan.users.destroy', $u) }}"
                                onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn" title="Hapus">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>Belum ada pengguna.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:16px 20px; border-top:1px solid var(--border);">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Form Tambah/Edit User --}}
    <div class="card" id="formUserCard">
        <div class="card-title" style="margin-bottom:20px;" id="formUserTitle">➕ Tambah User</div>

        <form method="POST" id="formUser" action="{{ route('pengaturan.users.store') }}">
            @csrf
            <div id="userMethodField"></div>

            @if(session('success'))
            <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#059669;">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input class="form-input" type="text" name="nama_lengkap" id="uNama" value="{{ old('nama_lengkap') }}">
            </div>
            <div class="form-group">
                <label class="form-label">NIP</label>
                <input class="form-input" type="text" name="nip" id="uNip" value="{{ old('nip') }}" placeholder="18 digit NIP">
            </div>
            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input class="form-input" type="email" name="email" id="uEmail" value="{{ old('email') }}">
            </div>
            <div class="form-group" id="passwordField">
                <label class="form-label">Password <span class="required">*</span></label>
                <input class="form-input" type="password" name="password" placeholder="Min. 8 karakter">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select class="form-select" name="role" id="uRole">
                        <option value="staff">Staff</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Divisi</label>
                    <select class="form-select" name="divisi_id" id="uDivisi">
                        <option value="">Tanpa Divisi</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}">{{ $div->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select" name="is_aktif" id="uStatus">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">💾 Simpan</button>
                <button type="button" class="btn-sm btn-view" onclick="resetUserForm()" style="padding:8px 16px;">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editUser(id, nama, email, nip, role, divisiId, isAktif) {
    document.getElementById('formUserTitle').textContent = '✏️ Edit User';
    document.getElementById('formUser').action = '/pengaturan/users/' + id;
    document.getElementById('userMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('uNama').value = nama;
    document.getElementById('uEmail').value = email;
    document.getElementById('uNip').value = nip;
    document.getElementById('uRole').value = role;
    document.getElementById('uDivisi').value = divisiId;
    document.getElementById('uStatus').value = isAktif;
    document.getElementById('passwordField').style.display = 'none';
    document.getElementById('formUserCard').scrollIntoView({ behavior: 'smooth' });
}
function resetUserForm() {
    document.getElementById('formUserTitle').textContent = '➕ Tambah User';
    document.getElementById('formUser').action = '{{ route('pengaturan.users.store') }}';
    document.getElementById('userMethodField').innerHTML = '';
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('formUser').reset();
}
</script>
@endpush