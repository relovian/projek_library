@extends('layouts.app')

@section('title', 'Trash Arsip Masuk')
@section('breadcrumb') Trash / Surat Masuk @endsection

@section('content')
<div class="max-w-[1100px]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-semibold text-[#111827] m-0">Trash Surat Masuk</h1>
            <p class="text-[13px] text-[#6b7280] mt-1">
                Surat masuk yang dihapus akan tersimpan di sini. Hanya admin yang bisa memulihkan atau menghapus permanen.
            </p>
        </div>
        <div>
            <a href="{{ route('arsip.index', ['tab' => 'masuk']) }}" class="text-[13px] text-[#6b7280] no-underline px-3.5 py-[7px] border border-[#e5e7eb] rounded-lg bg-white"> Kembali</a>
        </div>
    </div>

    {{-- Empty state --}}
    @if($arsipMasuk->isEmpty())
    <div class="bg-white border border-[#e5e7eb] rounded-xl py-12 text-center text-[#9ca3af]">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="#d1d5db"
            stroke-width="1.5" viewBox="0 0 24 24" class="my-0 mx-auto">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        <p class="text-sm font-medium text-[#6b7280] mt-3">Trash kosong</p>
        <p class="text-xs mt-1">Tidak ada surat masuk yang dihapus.</p>
    </div>

    @else
    <div class="bg-white border border-[#e5e7eb] rounded-xl overflow-hidden">
        <table class="w-full border-collapse text-[13px]">
            <thead>
                <tr class="bg-[#f9fafb] border-b border-[#e5e7eb]">
                    <th class="px-4 py-3 text-left font-medium text-[#6b7280]">Nama File</th>
                    <th class="px-4 py-3 text-left font-medium text-[#6b7280]">Perihal</th>
                    <th class="px-4 py-3 text-left font-medium text-[#6b7280]">Dihapus oleh</th>
                    <th class="px-4 py-3 text-left font-medium text-[#6b7280]">Tanggal dihapus</th>
                    <th class="px-4 py-3 text-center font-medium text-[#6b7280]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arsipMasuk as $item)
                <tr class="border-b border-[#f3f4f6] last:border-b-0">
                    <td class="px-4 py-3 text-[#6b7280] align-middle">
                        <div class="text-[#111827] font-medium">{{ Str::limit($item->nama_file, 40) }}</div>
                        <div class="text-[11px] text-[#9ca3af] mt-0.5">{{ $item->kode_arsip_masuk }}</div>
                    </td>
                    <td class="px-4 py-3 text-[#6b7280] align-middle">{{ Str::limit($item->perihal, 30) }}</td>
                    <td class="px-4 py-3 text-[#6b7280] align-middle">{{ $item->uploader->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-3 text-[#6b7280] align-middle">{{ $item->deleted_at->format('d M Y, H:i') }}</td>
                    <td class="px-4 py-3 text-[#6b7280] align-middle">
                        <div class="flex gap-2 justify-center">

                            @if(auth()->user()->role === 'admin')
                            {{-- Pulihkan --}}
                            <form method="POST" action="{{ route('arsip-masuk.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-[12px] font-medium text-[#15803d] bg-[#f0fdf4] border border-[#bbf7d0] px-3 py-[5px] rounded-[6px] cursor-pointer hover:bg-[#dcfce7]">♻️ Pulihkan</button>
                            </form>

                            {{-- Hapus permanen --}}
                            <form method="POST" action="{{ route('arsip-masuk.force-delete', $item->id) }}"
                                onsubmit="return confirm('Hapus permanen arsip ini? Tidak bisa dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[12px] font-medium text-[#dc2626] bg-[#fef2f2] border border-[#fecaca] px-3 py-[5px] rounded-[6px] cursor-pointer hover:bg-[#fee2e2]">🗑 Hapus Permanen</button>
                            </form>
                            @else
                                <span class="text-[12px] text-abu">Menunggu admin</span>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($arsipMasuk->hasPages())
        <div class="px-4 py-3 border-t border-[#e5e7eb]">
            {{ $arsipMasuk->links() }}
        </div>
        @endif
    </div>
    @endif

</div>
@endsection