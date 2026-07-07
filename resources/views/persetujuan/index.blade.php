@extends('layouts.app')
@section('title', 'Persetujuan')
@section('breadcrumb', 'Persetujuan')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Antrian Persetujuan</h1>
    <p class="text-[14px] text-text-abu">Dokumen yang menunggu tinjauan dan persetujuan Anda</p>
</div>

{{-- Filter --}}
<div class="flex items-center gap-2.5 mb-5 flex-wrap">
    <form method="GET" action="{{ route('persetujuan.index') }}" class="flex gap-[10px] flex-wrap">
        <select class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="divisi_id" onchange="this.form.submit()">
            <option value="">Semua Divisi</option>
            @foreach($divisis as $div)
            <option value="{{ $div->id }}" {{ request('divisi_id') == $div->id ? 'selected' : '' }}>
                {{ $div->nama }}
            </option>
            @endforeach
        </select>
    </form>

    {{-- Bulk Approve --}}
    <form method="POST" action="{{ route('persetujuan.bulk-setujui') }}" id="formBulk">
        @csrf
        <button type="submit" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit]"
            onclick="return confirm('Setujui semua dokumen terpilih?')">
            Setujui Semua Terpilih
        </button>
    </form>
</div>

<div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
    @if($arsips->count() > 0)
    <ul class="list-none">
        @foreach($arsips as $arsip)
        <li class="py-3 border-b border-border last:border-b-0">
            <div class="flex justify-between items-start mb-1">
                <div class="flex items-start gap-[10px]">
                    <input type="checkbox" name="ids[]" value="{{ $arsip->id }}"
                        form="formBulk"
                        class="w-4 h-4 accent-bawaslu-red mt-[3px] shrink-0">
                    <div>
                        <div class="text-[13px] font-semibold">{{ $arsip->judul }}</div>
                        <div class="text-[12px] text-abu mt-1">
                            <img src="{{ asset('img/unggah.png') }}" class="w-3 h-3 inline mr-[5px]" alt=""> {{ $arsip->uploader->nama_lengkap }}
                            · {{ $arsip->divisi->nama }}
                            · {{ $arsip->file_pertama?->ekstensi ?? '-' }}
                            · {{ $arsip->file_pertama?->ukuran_format ?? '-' }}
                        </div>

                        <div class="text-[12px] text-abu mt-[2px]">
                            <img src="{{ asset('img/category.png') }}" class="w-3 h-3 inline mr-[5px]" alt=""> {{ $arsip->kategori->nama }}
                            · <img src="{{ asset('img/kalender.png') }}" class="w-3 h-3 inline mr-[5px]" alt="">{{ $arsip->tanggal_dokumen->format('d M Y') }}
                            @if($arsip->nomor_surat)
                                · No. {{ $arsip->nomor_surat }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-[11px] text-abu">{{ $arsip->created_at->diffForHumans() }}</div>
            </div>

            <div class="flex gap-1.5 mt-[15px] flex-wrap justify-start items-center">
                {{-- Setujui --}}
                <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" class="flex justify-start items-center">
                    @csrf
                    <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-transparent [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-[#059669] text-white h-6"
                        onclick="return confirm('Setujui dokumen ini?')">
                        Setujui
                    </button>
                </form>

                {{-- Tolak --}}
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-abu border-border h-6"
                    onclick="openTolakModal({{ $arsip->id }}, '{{ addslashes($arsip->judul) }}')">
                    Tolak
                </button>

                {{-- Lihat --}}
                <a href="{{ route('arsip.show', $arsip) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border h-6">
                    Pratinjau
                </a>
            </div>
        </li>
        @endforeach
    </ul>

    <div class="mt-4 flex justify-end">
        {{ $arsips->withQueryString()->links() }}
    </div>

    @else
    <div class="text-center py-10 px-5 text-abu">
        <div class="text-[40px] mb-[10px]">
            <img src="{{ asset('img/persetujuan.png') }}" alt="">
        </div>
        <p class="text-[14px]">Tidak ada dokumen yang menunggu persetujuan.</p>
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div class="hidden fixed inset-0 bg-black/45 z-[200] items-center justify-center" id="modalTolak">
    <div class="bg-surface rounded-[16px] p-8 max-w-[520px] w-[90%]">
        <div class="flex justify-between items-start mb-6">
            <div class="text-lg font-bold">Tolak Dokumen</div>
            <button class="text-xl cursor-pointer text-abu bg-none border-none" onclick="closeTolakModal()">✕</button>
        </div>
        <p class="text-xs text-abu mt-4">
            Berikan alasan penolakan untuk: <strong id="modalDocName"></strong>
        </p>
        <form id="formTolak" method="POST">
            @csrf
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Catatan Penolakan <span class="text-bawaslu-red">*</span></label>
                <textarea class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] resize-vertical min-h-[90px]" name="catatan_penolakan" required
                    placeholder="Tuliskan alasan penolakan…"></textarea>
            </div>
            <div class="flex gap-[10px] justify-end">
                <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-none [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-bawaslu-red text-white">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openTolakModal(id, judul) {
    document.getElementById('modalDocName').textContent = judul;
    document.getElementById('formTolak').action = '/persetujuan/' + id + '/tolak';
    document.getElementById('modalTolak').classList.remove('hidden');
    document.getElementById('modalTolak').classList.add('flex');
}
function closeTolakModal() {
    document.getElementById('modalTolak').classList.remove('flex');
    document.getElementById('modalTolak').classList.add('hidden');
}
</script>
@endpush