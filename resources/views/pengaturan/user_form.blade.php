@extends('layouts.app')
@section('title', $mode === 'create' ? 'Tambah User' : 'Edit User')
@section('breadcrumb', 'Pengaturan / Users / ' . ($mode === 'create' ? 'Tambah' : 'Edit'))

@section('content')
<div class="max-w-[600px]">
    <div class="mb-7">
        <h1 class="font-serif text-[28px] text-hitam mb-1">{{ $mode === 'create' ? 'Tambah User' : 'Edit User' }}</h1>
        <p class="text-[14px] text-abu">{{ $mode === 'create' ? 'Buat akun pengguna baru' : 'Perbarui data pengguna' }}</p>
    </div>

    <div class="mb-5">
        <a href="{{ route('pengaturan.users') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
            Kembali ke Daftar User
        </a>
    </div>

    <div class="bg-surface border border-border rounded-[14px] p-6" id="formUserCard">
        <div class="text-[15px] font-bold mb-5">
            {{ $mode === 'create' ? 'Form Tambah User' : 'Form Edit User: ' . $user->nama_lengkap }}
        </div>

        <form method="POST" id="formUser" 
              action="{{ $mode === 'create' ? route('pengaturan.users.store') : route('pengaturan.users.update', $user) }}">
            @csrf
            @if($mode === 'edit')
                @method('PUT')
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Lengkap <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama_lengkap') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama_lengkap" 
                       id="uNama" 
                       value="{{ old('nama_lengkap', $user?->nama_lengkap ?? '') }}">
                @error('nama_lengkap')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Panggilan <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama_panggilan') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama_panggilan" 
                       id="uPanggilan" 
                       value="{{ old('nama_panggilan', $user?->nama_panggilan ?? '') }}">
                @error('nama_panggilan')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">NIP</label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nip') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nip" 
                       id="uNip" 
                       value="{{ old('nip', $user?->nip ?? '') }}" 
                       placeholder="18 digit NIP">
                @error('nip')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Email <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('email') border-[#DC2626] border-[1.5px] @enderror" 
                       type="email" 
                       name="email" 
                       id="uEmail" 
                       value="{{ old('email', $user?->email ?? '') }}">
                @error('email')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            @if($mode === 'create')
            <div class="mb-[18px]" id="passwordField">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Password <span class="text-bawaslu-red">*</span></label>
                <div style="position:relative;">
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('password') border-[#DC2626] border-[1.5px] @enderror" 
                       type="password" 
                       name="password"
                       id="uPassword"
                       style="padding-right:42px;">
                <button type="button" onclick="togglePassword('uPassword', 'eye-pass-admin')"
                    style="
                        position:absolute; right:10px; top:50%; transform:translateY(-50%);
                        background:none; border:none; cursor:pointer;
                        color:#9ca3af; padding:4px;
                    ">
                    <svg id="eye-pass-admin" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
                </div>
                @error('password')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
                    <div class="text-[12px] bg-[#fffbeb] text-abu p-2 mt-3 rounded-lg border border-yellow-500 block">
                        <p>Harus Minimal 8 Karakter, Mengandung Huruf Besar Dan Kecil, Mengandung Simbol</p>
                    </div>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Role <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('role') border-[#DC2626] border-[1.5px] @enderror" name="role" id="uRole">
                        <option value="admin" {{ old('role', $user?->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="komisioner" {{ old('role', $user?->role ?? '') == 'komisioner' ? 'selected' : '' }}>Komisioner</option>
                        <option value="kepala_sekretariat" {{ old('role', $user?->role ?? '') == 'kepala_sekretariat' ? 'selected' : '' }}>Kepala Sekretariat</option>
                        <option value="kepala_sub_bagian" {{ old('role', $user?->role ?? '') == 'kepala_sub_bagian' ? 'selected' : '' }}>Kepala Sub Bagian</option>
                        <option value="staff" {{ old('role', $user?->role ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Divisi</label>
                    <select class="w-full pr-[25px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('divisi_id') border-[#DC2626] border-[1.5px] @enderror" name="divisi_id" id="uDivisi">
                        <option value="">Tanpa Divisi</option>
                        @foreach($divisi as $div)
                        <option value="{{ $div->id }}" {{ old('divisi_id', $user?->divisi_id ?? '') == $div->id ? 'selected' : '' }}>{{ $div->nama }}</option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="uStatus">
                        <option value="1" {{ old('is_aktif', $user?->is_aktif ?? '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_aktif', $user?->is_aktif ?? '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_aktif')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Verifikator</label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_verifikator') border-[#DC2626] border-[1.5px] @enderror" name="is_verifikator" id="uVerifikator">
                        <option value="1" {{ old('is_verifikator', $user?->is_verifikator ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ old('is_verifikator', $user?->is_verifikator ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('is_verifikator')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    {{ $mode === 'create' ? 'Simpan User' : 'Perbarui User' }}
                </button>
                <a href="{{ route('pengaturan.users') }}" class="rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-center">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        `;
        icon.style.color = '#374151';
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
        icon.style.color = '#9ca3af';
    }
}
</script>
@endpush