@extends('layouts.app')
@section('title', 'Kelola User')
@section('breadcrumb', 'Pengaturan / User')

@section('content')
<div class="page-header">
    <h1>Kelola User & Hak Akses</h1>
    <p>Manajemen akun pengguna dan permission sistem</p>
</div>

{{-- Hanya tampilkan session success jika ada --}}
@if(session('success'))
<div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:12px 16px; margin-bottom:24px; font-size:13px; color:#059669;">
    ✅ {{ session('success') }}
</div>
@endif

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
                            {{-- TOMBOL EDIT - Menggunakan link dengan parameter ?edit= --}}
                            <a href="{{ route('pengaturan.users', ['edit' => $u->id]) }}" 
                               class="tbl-btn" 
                               title="Edit"
                               style="cursor:pointer; text-decoration:none; display:inline-block;">✏️</a>
                            
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('pengaturan.users.destroy', $u) }}"
                                onsubmit="return confirm('Hapus user ini?')" style="display:inline;">
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
        <div class="card-title" style="margin-bottom:20px;" id="formUserTitle">
            @if(request()->has('edit') && $editUser = \App\Models\User::find(request()->get('edit')))
                ✏️ Edit User: {{ $editUser->nama_lengkap }}
            @elseif(old('_method') == 'PUT' || session('edit_id'))
                ✏️ Edit User
            @else
                ➕ Tambah User
            @endif
        </div>

        <form method="POST" id="formUser" 
              action="@if(request()->has('edit') && $editUser = \App\Models\User::find(request()->get('edit'))) {{ route('pengaturan.users.update', $editUser) }} @elseif(old('_method') == 'PUT' && old('user_id')) {{ route('pengaturan.users.update', old('user_id')) }} @else {{ route('pengaturan.users.store') }} @endif">
            @csrf
            @if(request()->has('edit') && $editUser = \App\Models\User::find(request()->get('edit')))
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ $editUser->id }}">
            @elseif(old('_method') == 'PUT')
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ old('user_id') }}">
            @endif

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input class="form-input @error('nama_lengkap') is-invalid @enderror" 
                       type="text" 
                       name="nama_lengkap" 
                       id="uNama" 
                       value="{{ old('nama_lengkap', request()->has('edit') && isset($editUser) ? $editUser->nama_lengkap : '') }}">
                @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">NIP</label>
                <input class="form-input @error('nip') is-invalid @enderror" 
                       type="text" 
                       name="nip" 
                       id="uNip" 
                       value="{{ old('nip', request()->has('edit') && isset($editUser) ? $editUser->nip : '') }}" 
                       placeholder="18 digit NIP">
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input class="form-input @error('email') is-invalid @enderror" 
                       type="email" 
                       name="email" 
                       id="uEmail" 
                       value="{{ old('email', request()->has('edit') && isset($editUser) ? $editUser->email : '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" id="passwordField" @if(request()->has('edit') || old('_method') == 'PUT') style="display:none;" @endif>
                <label class="form-label">Password <span class="required">@if(!request()->has('edit') && !old('_method'))*@endif</span></label>
                <input class="form-input @error('password') is-invalid @enderror" 
                       type="password" 
                       name="password" 
                       placeholder="Min. 8 karakter">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" name="role" id="uRole">
                        <option value="staff" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="pimpinan" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="admin" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Divisi</label>
                    <select class="form-select @error('divisi_id') is-invalid @enderror" name="divisi_id" id="uDivisi">
                        <option value="">Tanpa Divisi</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}" {{ old('divisi_id', request()->has('edit') && isset($editUser) ? $editUser->divisi_id : '') == $div->id ? 'selected' : '' }}>{{ $div->nama }}</option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select @error('is_aktif') is-invalid @enderror" name="is_aktif" id="uStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editUser) ? $editUser->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editUser) ? $editUser->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">💾 Simpan</button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.users') }}" class="btn-sm btn-view" style="padding:8px 16px; text-decoration:none; display:inline-block; text-align:center;">❌ Batal</a>
                @endif
                <button type="button" class="btn-sm btn-view" onclick="resetUserForm()" style="padding:8px 16px;">🔄 Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetUserForm() {
    // Redirect ke halaman users tanpa parameter edit
    window.location.href = '{{ route('pengaturan.users') }}';
}
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