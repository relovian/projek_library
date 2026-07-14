@extends('layouts.app')
@section('title', 'Arsip')
@section('breadcrumb', 'Arsip')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Kelola Arsip</h1>
    <p class="text-[14px] text-abu">Temukan dan kelola seluruh dokumen yang tersimpan dalam sistem</p>
</div>

{{-- Tab --}}
<div class="flex mb-5 border-b-2 border-border gap-0">

    @foreach([
        '' => 'Semua Arsip',
        'saya'  => 'Arsip Saya',
        'masuk' => 'Arsip Masuk',
        'keluar' => 'Arsip Keluar',
    ] as $tabVal => $tabLabel)
    <a href="{{ route('arsip.index', array_merge(request()->except('tab','page'), $tabVal ? ['tab'=>$tabVal] : [])) }}"
      class="-mb-[2px] inline-block border-b-2 border-transparent px-[18px] py-2.5 text-[13.5px] font-semibold text-text-abu transition-colors duration-200 hover:text-text [&.active]:border-b-bawaslu-red [&.active]:text-bawaslu-red {{ request('tab', '') === $tabVal ? 'active' : '' }}">
        {{ $tabLabel }}
    </a>
    @endforeach
    
</div>

{{-- Filter --}}
<form id="filterForm" method="GET" action="{{ route('arsip.index') }}" class="flex flex-col gap-2.5 mb-5 flex-wrap">
    @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif

    {{-- Baris 1: Search + Tujuan (untuk arsip masuk) atau kategori/divisi (untuk arsip utama) --}}
    <div class="flex items-center gap-2.5 flex-wrap">
        <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer flex-1 min-w-[300px]" type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode arsip keluar, nama file, perihal..." onkeyup="autoSearch()">

        @if(request('tab') === 'masuk')
            {{-- Dropdown Tujuan untuk arsip masuk --}}
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                <option value="">Semua Tujuan</option>
                @foreach($tujuans ?? [] as $t)
                <option value="{{ $t->id }}" {{ request('tujuan_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->nama }}
                </option>
                @endforeach
            </select>
        @elseif(request('tab') === 'keluar')
            {{-- Dropdown Tujuan untuk arsip keluar --}}
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                <option value="">Semua Tujuan</option>
                @foreach($tujuans ?? [] as $t)
                <option value="{{ $t->id }}" {{ request('tujuan_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->nama }}
                </option>
                @endforeach
            </select>
        @else

            {{-- Filter untuk arsip utama --}}
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="kategori_id" onchange="autoSearch()">
                <option value="">Semua Kategori</option>
                @foreach($kategoris ?? [] as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama }}
                </option>
                @endforeach
            </select>

            <select class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="divisi_id" onchange="autoSearch()">
                <option value="">Semua Divisi</option>
                @foreach($divisis ?? [] as $div)
                <option value="{{ $div->id }}" {{ request('divisi_id') == $div->id ? 'selected' : '' }}>
                    {{ $div->nama }}
                </option>
                @endforeach
            </select>
        @endif
    </div>

    {{-- Baris 2: Filter lainnya --}}
    <div class="flex items-center gap-2.5 flex-wrap">
        @if(request('tab') === 'masuk')
            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_diterima" value="{{ request('tanggal_diterima') }}" onchange="autoSearch()">

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_unggah" value="{{ request('tanggal_unggah') }}" onchange="autoSearch()">

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="disposisi_user_id[]" multiple size="1" onchange="autoSearch()" style="min-width: 180px;">
                <option value="">Semua Disposisi</option>
                @foreach($users ?? [] as $u)
                <option value="{{ $u->id }}" {{ in_array($u->id, request('disposisi_user_id', [])) ? 'selected' : '' }}>
                    {{ $u->nama_lengkap }}
                </option>
                @endforeach
            </select>

        @elseif(request('tab') === 'keluar')
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="klasifikasi_id" onchange="autoSearch()">
                <option value="">Semua Klasifikasi</option>
                @foreach($klasifikasis ?? [] as $klas)
                <option value="{{ $klas->id }}" {{ request('klasifikasi_id') == $klas->id ? 'selected' : '' }}>
                    {{ $klas->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="sifat_id" onchange="autoSearch()">
                <option value="">Semua Sifat</option>
                @foreach($sifats ?? [] as $s)
                <option value="{{ $s->id }}" {{ request('sifat_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="sub_bagian_id" onchange="autoSearch()">
                <option value="">Semua Sub Bagian</option>
                @foreach($subBagians ?? [] as $sb)
                <option value="{{ $sb->id }}" {{ request('sub_bagian_id') == $sb->id ? 'selected' : '' }}>
                    {{ $sb->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="verifikator_id" onchange="autoSearch()">
                <option value="">Semua Verifikator</option>
                @foreach($verifikators ?? [] as $v)
                <option value="{{ $v->id }}" {{ request('verifikator_id') == $v->id ? 'selected' : '' }}>
                    {{ $v->user->nama_lengkap ?? $v->user->name ?? 'Verifikator' }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="pembuat_id" onchange="autoSearch()">
                <option value="">Semua Pembuat</option>
                @foreach($users ?? [] as $u)
                <option value="{{ $u->id }}" {{ request('pembuat_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->nama_lengkap }}
                </option>
                @endforeach
            </select>

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_surat" value="{{ request('tanggal_surat') }}" onchange="autoSearch()">

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_unggah" value="{{ request('tanggal_unggah') }}" onchange="autoSearch()">

        @else
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tahun" onchange="autoSearch()">
                <option value="">Semua Tahun</option>
                @foreach($tahunList ?? [] as $tahun)
                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="status" onchange="autoSearch()">
                <option value="">Semua Status</option>
                @foreach(['draft'=>'Draft','menunggu'=>'Menunggu','ditinjau'=>'Ditinjau','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        @endif

        <a href="{{ route('arsip.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">Reset</a>
    </div>

</form>

{{-- Tabel --}}
<div class="bg-surface border border-border rounded-[14px] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-[13.5px]">
            <thead>
                <tr>
                    @if(request('tab') === 'masuk')
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Dokumen</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Asal Instansi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Surat</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Diterima</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Disposisi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                    @elseif(request('tab') === 'keluar')
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Dokumen</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Kode Arsip</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Nama File</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Perihal</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Klasifikasi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Sifat</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Sub Bagian</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Verifikator</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tujuan</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Pembuat</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Surat</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Unggah</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>

                    @else
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Dokumen</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Kategori</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Divisi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Ukuran</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(request('tab') === 'masuk')
                    @forelse($suratMasuks as $suratMasuk)
                    <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                        <td class="px-[14px] py-3 pl-5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-[7px] flex items-center justify-center text-[15px] shrink-0">
                                   <img 
                                        src="{{ asset('img/berkas.png') }}"
                                        alt="icon"
                                        class="w-[25px] h-[25px] object-contain"
                                    >
                                </div>
                                <div>
                                    <div class="text-[13px] font-semibold">{{ Str::limit($suratMasuk->perihal, 45) }}</div>
                                    <div class="text-[11px] text-abu mt-[1px]">{{ $suratMasuk->kode_arsip_masuk }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-[14px] py-3">{{ $suratMasuk->asal_instansi }}</td>
                        <td class="px-[14px] py-3">{{ $suratMasuk->tanggal_surat->format('d/m/Y') }}</td>
                        <td class="px-[14px] py-3">{{ $suratMasuk->tanggal_diterima->format('d/m/Y') }}</td>
                        <td class="px-[14px] py-3">
                            @if($suratMasuk->usersDisposisi->count() > 0)
                                @foreach($suratMasuk->usersDisposisi as $userDisposisi)
                                    <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border mr-1 mb-1">{{ $userDisposisi->nama_lengkap }}</span>
                                @endforeach
                            @else
                                <span class="text-abu">-</span>
                            @endif
                        </td>
                        <td class="px-[14px] py-3">
                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0 bg-[#F5F5F5] text-[#6B7280]">
                                Disetujui
                            </span>
                        </td>
                        <td class="px-[14px] py-3">
                            <div class="flex gap-1.5">
                                <a href="{{ $suratMasuk->link_file }}" target="_blank" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                    <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-10 px-5 text-abu">
                                <div class="flex justify-center align-center text-[40px] mb-[10px]">
                                    <img src="{{ asset('img/arsip.png') }}" alt="">
                                </div>
                                <p class="text-[14px]">Tidak ada arsip masuk yang tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                @elseif(request('tab') === 'keluar')
                    @forelse($arsipKeluars as $arsipKeluar)
                    <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">

                        <td class="px-[14px] py-3 pl-5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-[7px] flex items-center justify-center text-[15px] shrink-0">
                                   <img 
                                        src="{{ asset('img/berkas.png') }}"
                                        alt="icon"
                                        class="w-[25px] h-[25px] object-contain"
                                    >
                                </div>
                                <div>
                                    <div class="text-[13px] font-semibold">{{ Str::limit($arsipKeluar->perihal, 45) }}</div>
                                    <div class="text-[11px] text-abu mt-[1px]">{{ $arsipKeluar->kode_arsip_keluar }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->kode_arsip_keluar }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->nama_file }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->perihal }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->klasifikasi?->nama ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->sifatSurat?->nama ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->subBagian?->nama ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->verifikator?->user?->nama_lengkap ?? $arsipKeluar->verifikator?->user?->name ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->tujuan?->nama ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ $arsipKeluar->pembuat?->nama_lengkap ?? $arsipKeluar->pembuat?->name ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ optional($arsipKeluar->tanggal_surat)->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-[14px] py-3">{{ optional($arsipKeluar->tanggal_unggah)->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-[14px] py-3">
                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0 bg-[#F5F5F5] text-[#6B7280]">
                                Disetujui
                            </span>
                        </td>
                        <td class="px-[14px] py-3">
                            <div class="flex gap-1.5">
                                <a href="{{ $arsipKeluar->link_file }}" target="_blank" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                    <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                </a>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="14">

                            <div class="text-center py-10 px-5 text-abu">
                                <div class="flex justify-center align-center text-[40px] mb-[10px]">
                                    <img src="{{ asset('img/arsip.png') }}" alt="">
                                </div>
                                <p class="text-[14px]">Tidak ada arsip keluar yang tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                @else
                    @forelse($arsips as $arsip)
                    <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                        <td class="px-[14px] py-3 pl-5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-[7px] flex items-center justify-center text-[15px] shrink-0">
                                   <img 
                                        src="{{ $arsip->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                                        alt="icon"
                                        class="w-[25px] h-[25px] object-contain"
                                    >
                                </div>
                                <div>
                                    <div class="text-[13px] font-semibold">{{ Str::limit($arsip->judul, 45) }}</div>
                                    <div class="text-[11px] text-abu mt-[1px]">{{ $arsip->kode_arsip }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-[14px] py-3"><span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border">{{ $arsip->kategori->nama }}</span></td>
                        <td class="px-[14px] py-3">{{ $arsip->divisi->nama }}</td>
                        <td class="px-[14px] py-3">{{ $arsip->tanggal_dokumen->format('d/m/Y') }}</td>
                        <td class="px-[14px] py-3">
                            <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                                @switch($arsip->status_color)
                                    @case('green') bg-[#ECFDF5] text-[#059669] @break
                                    @case('yellow') bg-[#FFFBEB] text-[#D97706] @break
                                    @case('blue') bg-[#EFF6FF] text-[#2563EB] @break
                                    @case('gray') bg-[#F5F5F5] text-[#6B7280] @break
                                    @case('red') bg-[#FEF2F2] text-[#DC2626] @break
                                    @default bg-[#F5F5F5] text-[#6B7280]
                                @endswitch">
                                {{ $arsip->status_label }}
                            </span>
                        </td>
                        <td class="px-[14px] py-3">{{ $arsip->file_pertama?->ukuran_format ?? '-' }}</td>
                        <td class="px-[14px] py-3">
                            <div class="flex gap-1.5">
                                <a href="{{ route('arsip.show', $arsip) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                    <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                </a>
                                
                                {{-- Hanya admin atau uploader yang bisa edit/hapus (hanya untuk Arsip Saya) --}}
                                @if(request('tab') === 'saya' && (auth()->user()->role === 'admin' || auth()->user()->id === $arsip->uploader_id))
                                    <a href="{{ route('arsip.edit', $arsip) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Edit">
                                        <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>

                                    <form method="POST" action="{{ route('arsip.destroy', $arsip->id) }}"
                                        onsubmit="return confirm('Pindahkan arsip ini ke trash?')">
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
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-10 px-5 text-abu">
                                <div class="flex justify-center align-center text-[40px] mb-[10px]">
                                    <img src="{{ asset('img/arsip.png') }}" alt="">
                                </div>
                                <p class="text-[14px]">Tidak ada arsip yang tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                @endif
            
            </tbody>
        </table>
    </div>

    @if(request('tab') === 'masuk')
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $suratMasuks->firstItem() ?? 0 }}–{{ $suratMasuks->lastItem() ?? 0 }} dari {{ number_format($suratMasuks->total()) }} dokumen
            </span>
            {{ $suratMasuks->withQueryString()->links() }}
        </div>
    @elseif(request('tab') === 'keluar')
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $arsipKeluars->firstItem() ?? 0 }}–{{ $arsipKeluars->lastItem() ?? 0 }} dari {{ number_format($arsipKeluars->total()) }} dokumen
            </span>
            {{ $arsipKeluars->withQueryString()->links() }}
        </div>
    @else
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $arsips->firstItem() }}–{{ $arsips->lastItem() }} dari {{ number_format($arsips->total()) }} dokumen
            </span>
            {{ $arsips->withQueryString()->links() }}
        </div>
    @endif
</div>

<div class="mt-3 text-right">
    <a href="{{ route('arsip.trash') }}" class="inline-flex items-center justify-center w-[50px] h-[50px] fixed bottom-[30px] right-[30px] bg-[#db3f44] rounded-full transition-all duration-200 ease-in-out hover:bg-[#c0272d]">
        <img src="{{ asset('img/sampah.png') }}" class="w-[30px] h-[30px]" alt="">
    </a>
</div>

@endsection

@push('scripts')
<script>
    // Auto search function - submit form on change
    function autoSearch() {
        // Debounce to prevent too many requests
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500);
    }
</script>
@endpush