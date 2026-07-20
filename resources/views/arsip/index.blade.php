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

    @php
        $tabs = [
            'saya'   => 'Arsip Saya',
            'masuk'  => 'Arsip Masuk',
            'keluar' => 'Arsip Keluar',
        ];

        // Jika role komisioner, hide tab Arsip Masuk/Keluar
        if (auth()->check() && auth()->user()->role === 'komisioner') {
            unset($tabs['saya']);
        }
    @endphp

    @foreach($tabs as $tabVal => $tabLabel)
    <a href="{{ route('arsip.index', array_merge(request()->except('tab','page'), ['tab'=>$tabVal])) }}"
      class="-mb-[2px] inline-block border-b-2 border-transparent px-[18px] py-2.5 text-[13.5px] font-semibold text-text-abu transition-colors duration-200 hover:text-text [&.active]:border-b-bawaslu-red [&.active]:text-bawaslu-red {{ request('tab', '') === $tabVal ? 'active' : '' }}">
        {{ $tabLabel }}
    </a>
    @endforeach
    
</div>

{{-- Filter --}}
<form id="filterForm" method="GET" action="{{ route('arsip.index') }}" class="flex flex-col gap-2.5 mb-5 flex-wrap">
    @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif

    @php
        $isSayaMasuk = request('tab') === 'saya' && request('arsip_id') === 'arsip_masuk';
        $isSayaKeluar = request('tab') === 'saya' && request('arsip_id') === 'arsip_keluar';
    @endphp

    {{-- Baris 1: Search + dropdown --}}
    <div class="flex items-center gap-2.5 flex-wrap">
        <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer flex-1 min-w-[300px]" type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode arsip, nama file, perihal..." onkeyup="autoSearch()">

        @if(request('tab') === 'masuk')
            {{-- Dropdown Tujuan untuk arsip masuk --}}
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                <option value="">Semua Tujuan</option>
                @foreach($tujuan ?? [] as $tn)
                <option value="{{ $tn->id }}" {{ request('tujuan_id') == $tn->id ? 'selected' : '' }}>
                    {{ $tn->nama }}
                </option>
                @endforeach
            </select>
        @elseif(request('tab') === 'keluar')
            {{-- Dropdown Tujuan untuk arsip keluar --}}
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                <option value="">Semua Tujuan</option>
                @foreach($tujuan ?? [] as $tn)
                <option value="{{ $tn->id }}" {{ request('tujuan_id') == $tn->id ? 'selected' : '' }}>
                    {{ $tn->nama }}
                </option>
                @endforeach
            </select>
        @elseif (request('tab') === 'saya' || $isSayaMasuk || $isSayaKeluar )
            {{-- Dropdown Pilih Arsip untuk tab Arsip Saya --}}

                <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="arsip_id" onchange="autoSearch()">
                    <option value="">Pilih Arsip</option>
                
                    <option value="arsip_masuk" {{ request('arsip_id') == 'arsip_masuk' ? 'selected' : '' }}>
                        Arsip Masuk
                    </option>

                    <option value="arsip_keluar" {{ request('arsip_id') == 'arsip_keluar' ? 'selected' : '' }}>
                        Arsip Keluar
                    </option>
                
                </select>
    
            @if($isSayaMasuk)
                {{-- Dropdown Tujuan untuk arsip masuk (dalam tab Arsip Saya) --}}
                <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                    <option value="">Semua Tujuan</option>
                    @foreach($tujuan ?? [] as $tn)
                    <option value="{{ $tn->id }}" {{ request('tujuan_id') == $tn->id ? 'selected' : '' }}>
                        {{ $tn->nama }}
                    </option>
                    @endforeach
                </select>
            @elseif($isSayaKeluar)
                {{-- Dropdown Tujuan untuk arsip keluar (dalam tab Arsip Saya) --}}
                <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="tujuan_id" onchange="autoSearch()">
                    <option value="">Semua Tujuan</option>
                    @foreach($tujuan ?? [] as $tn)
                    <option value="{{ $tn->id }}" {{ request('tujuan_id') == $tn->id ? 'selected' : '' }}>
                        {{ $tn->nama }}
                    </option>
                    @endforeach
                </select>
            @endif
        @endif
    </div>

    {{-- Baris 2: Filter lainnya --}}
    <div class="flex items-center gap-2.5 flex-wrap">
        @if(request('tab') === 'masuk' || $isSayaMasuk)
            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_surat" value="{{ request('tanggal_surat') }}" title="Filter berdasarkan tanggal surat" onchange="autoSearch()">

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_diterima" value="{{ request('tanggal_diterima') }}" title="Filter berdasarkan tanggal diterima" onchange="autoSearch()">

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_unggah" value="{{ request('tanggal_unggah') }}" title="Filter berdasarkan tanggal unggah" onchange="autoSearch()">

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="disposisi_user_id[]" multiple size="1" onchange="autoSearch()" style="min-width: 180px;">
                <option value="">Semua Disposisi</option>
                @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, request('disposisi_user_id', [])) ? 'selected' : '' }}>
                    {{ $user->nama_lengkap }}
                </option>
                @endforeach
            </select>

        @elseif(request('tab') === 'keluar' || $isSayaKeluar)
            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="klasifikasi_id" onchange="autoSearch()">
                <option value="">Semua Klasifikasi</option>
                @foreach($klasifikasi ?? [] as $ks)
                <option value="{{ $ks->id }}" {{ request('klasifikasi_id') == $ks->id ? 'selected' : '' }}>
                    {{ $ks->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="sifat_id" onchange="autoSearch()">
                <option value="">Semua Sifat</option>
                @foreach($sifat ?? [] as $sf)
                <option value="{{ $sf->id }}" {{ request('sifat_id') == $sf->id ? 'selected' : '' }}>
                    {{ $sf->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="sub_bagian_id" onchange="autoSearch()">
                <option value="">Semua Sub Bagian</option>
                @foreach($subBagian ?? [] as $sb)
                <option value="{{ $sb->id }}" {{ request('sub_bagian_id') == $sb->id ? 'selected' : '' }}>
                    {{ $sb->nama }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="verifikator_id" onchange="autoSearch()">
                <option value="">Semua Verifikator</option>
                @foreach($verifikator ?? [] as $vk)
                <option value="{{ $vk->id }}" {{ request('verifikator_id') == $vk->id ? 'selected' : '' }}>
                    {{ $vk->user->nama_lengkap ?? $v->user->name ?? 'Verifikator' }}
                </option>
                @endforeach
            </select>

            <select class="pr-8 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" name="pembuat_id" onchange="autoSearch()">
                <option value="">Semua Pembuat</option>
                @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ request('pembuat_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->nama_lengkap }}
                </option>
                @endforeach
            </select>

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_surat" value="{{ request('tanggal_surat') }}" title="Filter berdasarkan tanggal surat" onchange="autoSearch()">

            <input class="px-3 py-2 border border-border rounded-lg text-[13px] [font-family:inherit] bg-surface text-hitam outline-none cursor-pointer" type="date" name="tanggal_unggah" value="{{ request('tanggal_unggah') }}" title="Filter berdasarkan tanggal unggah" onchange="autoSearch()">
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
                    @if(request('tab') === 'masuk' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_masuk'))
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Dokumen</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Asal Instansi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tujuan</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Surat</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Diterima</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal Unggah</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Disposisi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                    @elseif(request('tab') === 'keluar' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_keluar'))
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
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>

                    @elseif(request('tab') === 'saya' && !request('arsip_id'))
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border pl-5">Dokumen</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Divisi</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Tanggal</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Status</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Ukuran</th>
                        <th class="text-left px-[14px] py-[11px] text-[11px] font-bold uppercase tracking-[.6px] text-abu bg-surface2 border-b border-border">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(request('tab') === 'masuk' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_masuk'))
                    @forelse($ArsipMasuk as $am)
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
                                        <div class="text-[13px] font-semibold">{{ Str::limit($am->perihal, 45) }}</div>
                                        <div class="text-[11px] text-abu mt-[1px]">{{ $am->kode_arsip_masuk }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-[14px] py-3">{{ $am->asal_instansi }}</td>
                            <td class="px-[14px] py-3">{{ $am->tujuan?->nama ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $am->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="px-[14px] py-3">{{ $am->tanggal_diterima->format('d/m/Y') }}</td>
                            <td class="px-[14px] py-3">{{ $am->tanggal_unggah ? $am->tanggal_unggah->format('d/m/Y') : '-' }}</td>
                            <td class="px-[14px] py-3">
                                @if($am->usersDisposisi->count() > 0)
                                    @foreach($am->usersDisposisi as $userDisposisi)
                                        <span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border mr-1 mb-1">{{ $userDisposisi->nama_lengkap }}</span>
                                    @endforeach
                                @else
                                    <span class="text-abu">-</span>
                                @endif
                            </td>
                            <td class="px-[14px] py-3">
                                <div class="flex gap-1.5">
                                    <a href="{{ $am->link_file }}" target="_blank" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                        <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>

                                    {{-- Edit: Admin bisa semua, selain itu hanya arsip milik sendiri --}}
                                    @if(auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $am->uploader_id))
                                    <a href="{{ route('arsip-masuk.edit', $am) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Edit">
                                        <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>
                                    @endif

                                    {{-- Hapus: Selain komisioner, hanya arsip milik sendiri --}}
                                    @if(auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $am->uploader_id))
                                    <form method="POST" action="{{ route('arsip-masuk.destroy', $am->id) }}"
                                        onsubmit="return confirm('Pindahkan arsip masuk ini ke trash?')">
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
                            <td colspan="9">
                                <div class="text-center py-10 px-5 text-abu">
                                    <div class="flex justify-center align-center text-[40px] mb-[10px]">
                                        <img src="{{ asset('img/arsip.png') }}" alt="">
                                    </div>
                                    <p class="text-[14px]">Tidak ada arsip masuk yang tersedia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                @elseif(request('tab') === 'keluar' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_keluar'))
                    @forelse($arsipKeluar as $ak)
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
                                        <div class="text-[13px] font-semibold">{{ Str::limit($ak->perihal, 45) }}</div>
                                        <div class="text-[11px] text-abu mt-[1px]">{{ $ak->kode_arsip_keluar }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-[14px] py-3">{{ $ak->kode_arsip_keluar }}</td>
                            <td class="px-[14px] py-3">{{ $ak->nama_file }}</td>
                            <td class="px-[14px] py-3">{{ $ak->perihal }}</td>
                            <td class="px-[14px] py-3">{{ $ak->klasifikasi?->nama ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $ak->sifatSurat?->nama ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $ak->subBagian?->nama ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $ak->verifikator?->user?->nama_lengkap ?? $ak->verifikator?->user?->name ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $ak->tujuan?->nama ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ $ak->pembuat?->nama_lengkap ?? $ak->pembuat?->name ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ optional($ak->tanggal_surat)->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-[14px] py-3">{{ optional($ak->tanggal_unggah)->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-[14px] py-3">
                                <div class="flex gap-1.5">
                                    <a href="{{ $ak->link_file }}" target="_blank" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                        <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>

                                    {{-- Edit: Admin bisa semua, selain itu hanya arsip milik sendiri --}}
                                    @if(auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $ak->uploader_id))
                                    <a href="{{ route('arsip-keluar.edit', $ak) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Edit">
                                        <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>
                                    @endif

                                    {{-- Hapus: Selain komisioner, hanya arsip milik sendiri --}}
                                    @if(auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $ak->uploader_id))
                                    <form method="POST" action="{{ route('arsip-keluar.destroy', $ak->id) }}"
                                        onsubmit="return confirm('Pindahkan arsip keluar ini ke trash?')">
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
                @elseif(request('tab') === 'saya' && !request('arsip_id'))
                    @forelse($arsip as $ap)
                        <tr class="border-b border-border transition-colors duration-[.15s] cursor-pointer hover:bg-surface2 last:border-b-0">
                            <td class="px-[14px] py-3 pl-5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-[7px] flex items-center justify-center text-[15px] shrink-0">
                                    <img 
                                            src="{{ $ap->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                                            alt="icon"
                                            class="w-[25px] h-[25px] object-contain"
                                        >
                                    </div>
                                    <div>
                                        <div class="text-[13px] font-semibold">{{ Str::limit($ap->judul, 45) }}</div>
                                        <div class="text-[11px] text-abu mt-[1px]">{{ $ap->kode_arsip }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-[14px] py-3"><span class="inline-flex items-center gap-1 text-[11.5px] font-semibold px-[9px] py-[3px] rounded-[20px] bg-surface2 text-abu border border-border">{{ $arsip->kategori->nama }}</span></td>
                            <td class="px-[14px] py-3">{{ $ap->divisi->nama }}</td>
                            <td class="px-[14px] py-3">{{ $ap->tanggal_dokumen->format('d/m/Y') }}</td>
                            {{-- <td class="px-[14px] py-3">
                                <span class="text-[10.5px] font-bold px-[9px] py-[3px] rounded-[20px] shrink-0
                                    @switch($ap->status_color)
                                        @case('green') bg-[#ECFDF5] text-[#059669] @break
                                        @case('yellow') bg-[#FFFBEB] text-[#D97706] @break
                                        @case('blue') bg-[#EFF6FF] text-[#2563EB] @break
                                        @case('gray') bg-[#F5F5F5] text-[#6B7280] @break
                                        @case('red') bg-[#FEF2F2] text-[#DC2626] @break
                                        @default bg-[#F5F5F5] text-[#6B7280]
                                    @endswitch">
                                    {{ $arsip->status_label }}
                                </span>
                            </td> --}}
                            <td class="px-[14px] py-3">{{ $arsip->file_pertama?->ukuran_format ?? '-' }}</td>
                            <td class="px-[14px] py-3">
                                <div class="flex gap-1.5">
                                    <a href="{{ route('arsip.show', $ap) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Lihat">
                                        <img src="{{ asset('img/pratinjau.png') }}" class="w-[15px] h-[15px]" alt="">
                                    </a>
                                    
                                    {{-- Edit: Admin bisa semua, selain itu hanya arsip milik sendiri --}}
                                    @if(auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $ap->uploader_id))
                                        <a href="{{ route('arsip.edit', $ap) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline" title="Edit">
                                            <img src="{{ asset('img/edit.png') }}" class="w-[15px] h-[15px]" alt="">
                                        </a>
                                    @endif

                                    {{-- Hapus: Selain komisioner, hanya arsip milik sendiri --}}
                                    @if(request('tab') === 'saya' && auth()->user()->role !== 'komisioner' && (auth()->user()->role === 'admin' || auth()->user()->id === $ap->uploader_id))
                                        <form method="POST" action="{{ route('arsip.destroy', $ap->id) }}"
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
                        <td colspan="8">
                            <div class="text-center py-10 px-5 text-abu">
                                <div class="flex justify-center align-center text-[40px] mb-[10px]">
                                    <img src="{{ asset('img/arsip.png') }}" alt="">
                                </div>
                                <p class="text-[14px]">Pilih Arsip Terlebih Dahulu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                @endif
            
            </tbody>
        </table>
    </div>

    @if(request('tab') === 'masuk' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_masuk'))
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $ArsipMasuk->firstItem() ?? 0 }}–{{ $ArsipMasuk->lastItem() ?? 0 }} dari {{ number_format($ArsipMasuk->total()) }} dokumen
            </span>
            {{ $ArsipMasuk->withQueryString()->links() }}
        </div>
    @elseif(request('tab') === 'keluar' || (request('tab') === 'saya' && request('arsip_id') === 'arsip_keluar'))
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $arsipKeluar->firstItem() ?? 0 }}–{{ $arsipKeluar->lastItem() ?? 0 }} dari {{ number_format($arsipKeluar->total()) }} dokumen
            </span>
            {{ $arsipKeluar->withQueryString()->links() }}
        </div>
    @elseif(request('tab') === 'saya' && !request('arsip_id'))
        <div class="px-4 py-5 border-t border-solid border-border flex justify-between items-center flex-wrap gap-[10px]">
            <span class="text-xs text-abu">
                Menampilkan {{ $arsip->firstItem() }}–{{ $arsip->lastItem() }} dari {{ number_format($arsip->total()) }} dokumen
            </span>
            {{ $arsip->withQueryString()->links() }}
        </div>
    @endif
</div>

<div class="mt-3 text-right">
    @php
        $trashRoute = '#';
        if (request('tab') === 'masuk') {
            $trashRoute = route('arsip-masuk.trash', ['from' => 'masuk']);
        } elseif (request('tab') === 'keluar') {
            $trashRoute = route('arsip-keluar.trash', ['from' => 'keluar']);
        } elseif (request('tab') === 'saya') {
            // Default ke trash arsip masuk milik user, bisa ganti sesuai dropdown
            if (request('arsip_id') === 'arsip_keluar') {
                $trashRoute = route('arsip-keluar.trash', ['from' => 'saya_keluar']);
            } else {
                $trashRoute = route('arsip-masuk.trash', ['from' => 'saya_masuk']);
            }
        } else {
            $trashRoute = route('arsip-masuk.trash', ['from' => 'masuk']);
        }
    @endphp
    <a href="{{ $trashRoute }}" class="inline-flex items-center justify-center w-[50px] h-[50px] fixed bottom-[30px] right-[30px] bg-[#db3f44] rounded-full transition-all duration-200 ease-in-out hover:bg-[#c0272d]">
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