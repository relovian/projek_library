@extends('layouts.app')
@section('title', 'Kelola Verifikator')
@section('breadcrumb', 'Pengaturan / Verifikator')

@php
use App\Models\Verifikator;   
@endphp

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Verifikator</h1>
    <p class="text-[14px] text-abu">Konfigurasi struktur organisasi Verfikator Bawaslu</p>
</div>

<div class="mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-[1fr_360px] gap-6 items-start">

    {{-- Daftar Verifikator --}}
    <div class="bg-surface border border-border rounded-[14px] overflow-hidden">
        <div class="px-5 py-6 border-b border-solid border-border">
            <div class="text-[15px] font-bold">Daftar Verifikator</div>
        </div>
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Lengkap</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Panggilan</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Jumlah Arsip</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">{{ $u->nama_lengkap }}</div>
                    </td>

                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">{{ $u->nama_panggilan }}</div>
                    </td>

                    <td class="px-[14px] py-3 pl-5">
                        <div class="font-semibold">0 arsip</div>
                    </td>

                     <td class="px-[14px] py-3">
                         @if($u->dataVerifikator && $u->dataVerifikator->is_aktif)
                             <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0 bg-[#D1FAE5] text-[#059669]">
                                 Aktif
                             </span>
                         @else
                             <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0 bg-[#F3F4F6] text-[#6B7280]">
                                 Nonaktif
                             </span>
                         @endif
                     </td>
                     <td class="px-[14px] py-3">
                         <form method="POST" action="{{ route('pengaturan.users.update', $u) }}" class="inline">
                             @csrf @method('PUT')
                             <input type="hidden" name="nama_lengkap" value="{{ $u->nama_lengkap }}">
                             <input type="hidden" name="nama_panggilan" value="{{ $u->nama_panggilan }}">
                             <input type="hidden" name="email" value="{{ $u->email }}">
                             <input type="hidden" name="nip" value="{{ $u->nip }}">
                             <input type="hidden" name="role" value="{{ $u->role }}">
                             <input type="hidden" name="divisi_id" value="{{ $u->divisi_id }}">
                             <input type="hidden" name="is_aktif" value="{{ $u->is_aktif ? '1' : '0' }}">
                             <input type="hidden" name="is_verifikator" value="{{ $u->dataVerifikator && $u->dataVerifikator->is_aktif ? '0' : '1' }}">
                             <button type="submit" class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[6px] cursor-pointer border border-border bg-surface hover:bg-surface2" title="Ubah Status">
                                 @if($u->dataVerifikator && $u->dataVerifikator->is_aktif)
                                     Nonaktifkan
                                 @else
                                     Aktifkan
                                 @endif
                             </button>
                         </form>
                     </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada verifikator.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetVerifikatorForm() {
    window.location.href = '{{ route('pengaturan.verifikator') }}';
};
</script>
@endpush