@extends('layouts.app')
@section('title', 'Profil Saya')
@section('breadcrumb', 'Pengaturan / Profil')

@section('content')
<div class="page-header">
    <h1>Profil & Password</h1>
    <p>Perbarui informasi akun Anda</p>
</div>

<div style="margin-bottom: 20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration: none;">
        Kembali ke Pengaturan
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">

    {{-- Form Profil --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:24px;"> Informasi Profil</div>

        <div style="display:flex; align-items:center; gap:20px; margin-bottom:28px; padding-bottom:24px; border-bottom:1px solid var(--border);">
            <div style="width:72px; height:72px; border-radius:50%; background:var(--bawaslu-red); display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; color:#fff; flex-shrink:0;">
                {{ $user->inisial }}
            </div>
            <div>
                <div style="font-weight:700; font-size:16px;">{{ $user->nama_lengkap }}</div>
                <div style="color:var(--text-muted); font-size:13px; margin-top:4px;">
                    {{ $user->role_label }} · {{ $user->divisi?->nama ?? 'Tanpa Divisi' }}
                </div>
                <div style="color:var(--text-muted); font-size:12px; margin-top:2px;">
                    NIP: {{ $user->nip ?? '-' }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pengaturan.profil.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input class="form-input" type="text" name="nama_lengkap"
                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input class="form-input" type="email" name="email"
                    value="{{ old('email', $user->email) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Telepon</label>
                <input class="form-input" type="text" name="telepon"
                    value="{{ old('telepon', $user->telepon) }}"
                    placeholder="+62 812-xxxx-xxxx">
            </div>

            <div class="form-group">
                <label class="form-label">NIP</label>
                <input class="form-input" type="text" value="{{ $user->nip ?? '-' }}"
                    readonly style="background:var(--surface2); cursor:not-allowed;">
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">
                    NIP tidak dapat diubah sendiri. Hubungi Admin.
                </div>
            </div>

            <button type="submit" class="btn-primary"> Simpan Perubahan</button>
        </form>
    </div>

    {{-- Form Password --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:24px;"> Ubah Password</div>

        <form method="POST" action="{{ route('pengaturan.password.update') }}">
            @csrf
            @method('PUT')

            @if(session('password_success'))
            <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#059669;">
                ✅ {{ session('password_success') }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Password Lama <span class="required">*</span></label>
                <input type="password" name="password_lama"
                    placeholder="••••••••"  class="form-input {{ $errors->has('password_lama') ? 'is-error' : '' }} ">
            
                @error('password_lama')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span class="required">*</span></label>
                <input type="password" name="password_baru"
                    placeholder="Min. 8 karakter" class="form-input {{ $errors->has('password_lama') ? 'is-error' : '' }} " >

                @error('password_baru')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                <input type="password" name="password_baru_confirmation"
                    placeholder="Ulangi password baru" class="form-input {{ $errors->has('password_lama') ? 'is-error' : '' }} ">

                @error('password_baru_confirmation')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="background:var(--surface2); border-radius:8px; padding:12px 14px; margin-bottom:18px; font-size:12.5px; color:var(--text-muted); line-height:1.7;">
                <strong>Syarat password:</strong><br>
                • Minimal 8 karakter<br>
                • Kombinasi huruf dan angka disarankan
            </div>

            <button type="submit" class="btn-primary"> Ubah Password</button>
        </form>
    </div>

</div>

{{-- Info Akun --}}
<div class="card" style="margin-top:24px;">
    <div class="card-title" style="margin-bottom:16px;">Statistik Akun</div>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
        <div style="text-align:center; padding:16px; background:var(--surface2); border-radius:10px;">
            <div style="font-size:24px; font-weight:800; color:var(--bawaslu-red);">
                {{ auth()->user()->arsips()->count() }}
            </div>
            <div style="font-size:12.5px; color:var(--text-muted); margin-top:4px;">Total Arsip Diunggah</div>
        </div>
        <div style="text-align:center; padding:16px; background:var(--surface2); border-radius:10px;">
            <div style="font-size:24px; font-weight:800; color:#059669;">
                {{ auth()->user()->arsips()->where('status','disetujui')->count() }}
            </div>
            <div style="font-size:12.5px; color:var(--text-muted); margin-top:4px;">Arsip Disetujui</div>
        </div>
        <div style="text-align:center; padding:16px; background:var(--surface2); border-radius:10px;">
            <div style="font-size:24px; font-weight:800; color:#D97706;">
                {{ auth()->user()->arsips()->where('status','menunggu')->count() }}
            </div>
            <div style="font-size:12.5px; color:var(--text-muted); margin-top:4px;">Menunggu Persetujuan</div>
        </div>
    </div>
</div>
@endsection