@extends('layouts.app')
@section('title', 'Kelola User')
@section('breadcrumb', 'Pengaturan / User')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola User & Hak Akses</h1>
    <p class="text-[14px] text-abu">Manajemen akun pengguna dan permission sistem</p>
</div>

<div class="mb-5 flex items-center justify-between">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
    <a href="{{ route('pengaturan.users.create') }}" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]">
        Tambah User
    </a>
</div>

{{-- Tabel Full Width --}}
<div class="bg-surface border border-border rounded-[14px] overflow-hidden">
    <div class="px-5 py-6 border-b border-solid border-border flex justify-between items-center">
        <div class="text-[15px] font-bold">Daftar Pengguna</div>
        <span class="text-xs text-abu">{{ $users->total() }} pengguna</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Nama Panggilan</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Role</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Divisi</th>
                    <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Verifikator</th>
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
                    <td class="px-[14px] py-3 text-xs">{{ $u->nama_panggilan }}</td>
                    <td class="px-[14px] py-3">
                        <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                            @if($u->role === 'admin') bg-[#FEF2F2] text-[#DC2626]
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
                            @if($u->is_verifikator) bg-[#ECFDF5] text-[#059669]
                            @else bg-[#F5F5F5] text-[#6B7280] @endif">
                            {{ $u->is_verifikator ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
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
                            <a href="{{ route('pengaturan.users.edit', $u) }}" 
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
                <tr><td colspan="7"><div class="text-center py-10 px-5 text-abu"><p class="text-[14px]">Belum ada pengguna.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-5 py-4 border-t border-border flex items-center justify-between flex-wrap gap-2">
        <span class="text-xs text-abu">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ number_format($users->total()) }} pengguna
        </span>
      <div class="flex items-center gap-1.5">
        @if ($users->onFirstPage())
            <span class="min-w-[36px] h-[36px] px-3 flex items-center justify-center rounded-[8px] border border-border bg-surface2 text-gray-400 text-sm cursor-not-allowed">‹</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="min-w-[36px] h-[36px] px-3 flex items-center justify-center rounded-[8px] border border-border bg-white text-hitam text-sm hover:bg-bawaslu-red hover:text-white transition-all">‹</a>
        @endif

        @for ($i = 1; $i <= $users->lastPage(); $i++)
            <a href="{{ $users->url($i) }}"
            class="min-w-[36px] h-[36px] px-3 flex items-center justify-center rounded-[8px] border border-border text-sm font-semibold transition-all 
            {{ $users->currentPage() == $i ? 'bg-bawaslu-red text-white border-bawaslu-red' : 'bg-white text-hitam hover:bg-surface2' }}">
                {{ $i }}
            </a>
        @endfor

        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="min-w-[36px] h-[36px] px-3 flex items-center justify-center rounded-[8px] border border-border bg-white text-hitam text-sm hover:bg-bawaslu-red hover:text-white transition-all">›</a>
        @else
            <span class="min-w-[36px] h-[36px] px-3 flex items-center justify-center rounded-[8px] border border-border bg-surface2 text-gray-400 text-sm cursor-not-allowed">›</span>
        @endif
    </div>
    </div>
</div>
@endsection