@extends('layouts.app')
@section('title', 'Kelola User')
@section('breadcrumb', 'Pengaturan / User')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola User & Hak Akses</h1>
    <p class="text-[14px] text-abu">Manajemen akun pengguna dan permission sistem</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_320px] gap-6 items-start">

    <div class="bg-surface border border-border rounded-[14px] overflow-hidden pr-2 pb-2">
        <div class="px-5 py-6 border-b border-solid border-border flex justify-between items-center">
            <div class="text-[15px] font-bold">Daftar Pengguna</div>
            <span class="text-xs text-abu">{{ $users->total() }} pengguna</span>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Role</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Divisi</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="flex items-center gap-[10px]">
                            <div class="w-8 h-8 rounded-full bg-bawaslu-red flex justify-center text-xs font-bold shrink-0 text-white items-center">
                                {{ $u->inisial }}
                            </div>
                            <div>
                                <div class="font-semibold text-xs">{{ $u->nama_lengkap }}</div>
                                <div class="text-xs text-abu">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($u->role === 'admin') bg-[#FEF2F2] text-[#DC2626]
                            @elseif($u->role === 'pimpinan') bg-[#EFF6FF] text-[#2563EB]
                            @elseif($u->role === 'komisioner') bg-[#FFF7ED] text-[#EA580C]
                            @elseif($u->role === 'kepala_sekretariat') bg-[#F0FDF4] text-[#16A34A]
                            @elseif($u->role === 'kepala_sub_bagian') bg-[#F5F3FF] text-[#7C3AED]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $u->role_label }}
                        </span>
                    </td>
                    <td class="px-[14px] py-3 text-xs">{{ $u->divisi?->nama ?? '-' }}</td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($u->is_aktif) bg-[#ECFDF5] text-[#059669]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $u->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-[14px] py-3">
                        <div class="flex gap-1.5">
                            {{-- TOMBOL EDIT --}}
                            <a href="{{ route('pengaturan.users', ['edit' => $u->id]) }}" 
                               class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" 
                               title="Edit">
                                <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt=""> 
                            </a>
                            
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('pengaturan.users.destroy', $u) }}"
                                onsubmit="return confirm('Hapus user ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2" title="Hapus">
                                    <img src="{{ asset('img/hapus.png') }}" class="w-[15px] h-[15px]" alt=""> 
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada pengguna.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-6 flex items-center justify-end gap-2 flex-wrap px-4 py-3">
            {{-- Tombol Previous --}}
            @if ($users->onFirstPage())
                <span class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB] opacity-45 pointer-events-none">‹</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB]">‹</a>
            @endif

            {{-- Nomor Halaman --}}
            @for ($i = 1; $i <= $users->lastPage(); $i++)
                <a href="{{ $users->url($i) }}"
                class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-red-600 hover:border-[#D1D5DB] hover:text-white {{ $users->currentPage() == $i ? 'bg-red-500 text-white border-bawaslu-red' : '' }}">
                    {{ $i }}
                </a>
            @endfor

            {{-- Tombol Next --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB]">›</a>
            @else
                <span class="min-w-[38px] h-[38px] px-[14px] flex items-center justify-center rounded-[10px] border border-border bg-white text-hitam no-underline text-sm font-semibold transition-all duration-200 ease hover:bg-surface2 hover:border-[#D1D5DB] opacity-45 pointer-events-none">›</span>
            @endif
        </div>
    </div>

    {{-- Form Tambah/Edit User --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]" id="formUserCard">
        <div class="text-[15px] font-bold mb-5" id="formUserTitle">
            @if(request()->has('edit') && $editUser = \App\Models\User::find(request()->get('edit')))
                Edit User: {{ $editUser->nama_lengkap }}
            @elseif(old('_method') == 'PUT' || session('edit_id'))
                Edit User
            @else
                Tambah User
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

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Lengkap <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('nama_lengkap') border-[#DC2626] border-[1.5px] @enderror" 
                       type="text" 
                       name="nama_lengkap" 
                       id="uNama" 
                       value="{{ old('nama_lengkap', request()->has('edit') && isset($editUser) ? $editUser->nama_lengkap : '') }}">
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
                       value="{{ old('nama_panggilan', request()->has('edit') && isset($editUser) ? $editUser->nama_panggilan : '') }}">
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
                       value="{{ old('nip', request()->has('edit') && isset($editUser) ? $editUser->nip : '') }}" 
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
                       value="{{ old('email', request()->has('edit') && isset($editUser) ? $editUser->email : '') }}">
                @error('email')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]" id="passwordField" @if(request()->has('edit') || old('_method') == 'PUT') style="display:none" @endif>
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Password <span class="text-bawaslu-red">@if(!request()->has('edit') && !old('_method'))*@endif</span></label>
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

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Role <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('role') border-[#DC2626] border-[1.5px] @enderror" name="role" id="uRole">
                        <option value="staff" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="pimpinan" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="komisioner" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'komisioner' ? 'selected' : '' }}>Komisioner</option>
                        <option value="kepala_sekretariat" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'kepala_sekretariat' ? 'selected' : '' }}>Kepala Sekretariat</option>
                        <option value="kepala_sub_bagian" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'kepala_sub_bagian' ? 'selected' : '' }}>Kepala Sub Bagian</option>
                        <option value="admin" {{ old('role', request()->has('edit') && isset($editUser) ? $editUser->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Divisi</label>
                    <select class="w-full pr-[25px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('divisi_id') border-[#DC2626] border-[1.5px] @enderror" name="divisi_id" id="uDivisi">
                        <option value="">Tanpa Divisi</option>
                        @foreach($divisis as $div)
                        <option value="{{ $div->id }}" {{ old('divisi_id', request()->has('edit') && isset($editUser) ? $editUser->divisi_id : '') == $div->id ? 'selected' : '' }}>{{ $div->nama }}</option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Status</label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] @error('is_aktif') border-[#DC2626] border-[1.5px] @enderror" name="is_aktif" id="uStatus">
                    <option value="1" {{ old('is_aktif', request()->has('edit') && isset($editUser) ? $editUser->is_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', request()->has('edit') && isset($editUser) ? $editUser->is_aktif : '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_aktif')
                    <div class="text-[12px] text-[#DC2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-[10px]">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1"> Simpan </button>
                @if(request()->has('edit') || old('_method') == 'PUT')
                <a href="{{ route('pengaturan.users') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-center">Batal</a>
                @endif
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4" onclick="resetUserForm()">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetUserForm() {
    window.location.href = '{{ route('pengaturan.users') }}';
}

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
