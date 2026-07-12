@extends('layouts.app')
@section('title', 'Profil Saya')
@section('breadcrumb', 'Pengaturan / Profil')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Profil & Password</h1>
    <p class="text-[14px] text-abu">Perbarui informasi akun Anda</p>
</div>

<div class="mt-5 mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-2 gap-[24px] items-start">

    {{-- Form Profil --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-6"> Informasi Profil</div>

        <div class="flex items-center gap-[20px] mt-[28px] pb-[24px]">
            <div class="w-20 h-20 rounded-full bg-bawaslu-red flex items-center justify-center text-4xl font-bold text-white shrink-0 border border-solid border-border">
                {{ $user->inisial }}
            </div>
            <div>
                <div class="font-bold text-base">{{ $user->nama_lengkap }}</div>
                <div class="text-abu text-xs mt-1">
                    {{ $user->role_label }} · {{ $user->divisi?->nama ?? 'Tanpa Divisi' }}
                </div>
                <div class="text-xs text-abu mt-[2px]">
                    NIP: {{ $user->nip ?? '-' }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pengaturan.profil.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Lengkap <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]" type="text" name="nama_lengkap"
                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama Panggilan <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]" type="text" name="nama_panggilan"
                    value="{{ old('nama_panggilan', $user->nama_panggilan) }}">
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Email <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]" type="email" name="email"
                    value="{{ old('email', $user->email) }}">
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Telepon</label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]" type="text" name="telepon"
                    value="{{ old('telepon', $user->telepon) }}"
                    placeholder="+62 812-xxxx-xxxx">
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">NIP</label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface2 text-hitam outline-none cursor-not-allowed" type="text" value="{{ $user->nip ?? '-' }}"
                    readonly>
                <div class="text-xs text-abu mt-1">
                    NIP tidak dapat diubah sendiri. Hubungi Admin.
                </div>
            </div>

            <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]"> Simpan Perubahan</button>
        </form>
    </div>

    {{-- Form Password --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-6"> Ubah Password</div>

        <form method="POST" action="{{ route('pengaturan.password.update') }}">
            @csrf
            @method('PUT')

            @if(session('password_success'))
            <div class="bg-[#ECFDF5] border border-solid border-[#A7F3D0] rounded-lg px-2 py-3 mb-4 text-xs text-[#059669]">
                ✅ {{ session('password_success') }}
            </div>
            @endif

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Password Lama <span class="text-bawaslu-red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_lama" id="passwordLama"
                        placeholder="••••••••" style="padding-right:42px;"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('password_lama') ? 'border-[#dc2626]' : '' }}">
                    <button type="button" onclick="togglePassword('passwordLama', 'eye-lama')"
                        style="
                            position:absolute; right:10px; top:50%; transform:translateY(-50%);
                            background:none; border:none; cursor:pointer;
                            color:#9ca3af; padding:4px;
                        ">
                        <svg id="eye-lama" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password_lama')
                <div class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Password Baru <span class="text-bawaslu-red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_baru" id="passwordBaru"
                        placeholder="Min. 8 karakter" style="padding-right:42px;"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('password_lama') ? 'border-[#dc2626]' : '' }}">
                    <button type="button" onclick="togglePassword('passwordBaru', 'eye-baru')"
                        style="
                            position:absolute; right:10px; top:50%; transform:translateY(-50%);
                            background:none; border:none; cursor:pointer;
                            color:#9ca3af; padding:4px;
                        ">
                        <svg id="eye-baru" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password_baru')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Konfirmasi Password Baru <span class="text-bawaslu-red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_baru_confirmation" id="passwordKonfirm"
                        placeholder="Ulangi password baru" style="padding-right:42px;"
                        class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('password_lama') ? 'border-[#dc2626]' : '' }}">
                    <button type="button" onclick="togglePassword('passwordKonfirm', 'eye-konfirm')"
                        style="
                            position:absolute; right:10px; top:50%; transform:translateY(-50%);
                            background:none; border:none; cursor:pointer;
                            color:#9ca3af; padding:4px;
                        ">
                        <svg id="eye-konfirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password_baru_confirmation')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-surface2 rounded-lg px-3 py-[14px] mt-[18px] mb-4 text-xs leading-[1.7]">
                <strong>Syarat password:</strong><br>
                • Minimal 8 karakter<br>
                • Kombinasi huruf dan angka disarankan
            </div>

            <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]"> Ubah Password</button>
        </form>
    </div>

</div>

{{-- Info Akun --}}
<div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px] mt-6">
    <div class="text-[15px] font-bold mb-4">Statistik Akun</div>
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-surface2 rounded-lg">
            <div class="text-2xl font-extrabold text-bawaslu-red">
                {{ auth()->user()->arsips()->count() }}
            </div>
            <div class="text-xs text-abu mt-1">Total Arsip Diunggah</div>
        </div>
        <div class="p-4 bg-surface2 rounded-lg">
            <div class="text-2xl font-extrabold text-[#059669]">
                {{ auth()->user()->arsips()->where('status','disetujui')->count() }}
            </div>
            <div class="text-xs text-abu mt-1">Arsip Disetujui</div>
        </div>
        <div class="p-4 bg-surface2 rounded-lg">
            <div class="text-2xl font-extrabold text-[#D97706]">
                {{ auth()->user()->arsips()->where('status','menunggu')->count() }}
            </div>
            <div class="text-xs text-abu mt-1">Menunggu Persetujuan</div>
        </div>
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