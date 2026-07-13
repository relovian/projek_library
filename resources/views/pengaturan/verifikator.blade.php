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