@extends('layouts.app')
@section('title', 'Arsip')
@section('breadcrumb', 'Arsip')

@section('content')
<div class="page-header">
    <h1>Kelola Arsip</h1>
    <p>Temukan dan kelola seluruh dokumen yang tersimpan dalam sistem</p>
</div>

{{-- Tab --}}
<div class="tab-row">
    @foreach([
        ''      => 'Semua Arsip',
        'saya'  => 'Arsip Saya',
    ] as $tabVal => $tabLabel)
    <a href="{{ route('arsip.index', array_merge(request()->except('tab','page'), $tabVal ? ['tab'=>$tabVal] : [])) }}"
       class="tab-btn {{ request('tab', '') === $tabVal ? 'active' : '' }}">
        {{ $tabLabel }}
    </a>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('arsip.index') }}" class="filter-row">
    @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif

    <input class="filter-input" type="text" name="q" value="{{ request('q') }}" placeholder="🔍  Cari judul dokumen…">

    <select class="filter-select" name="kategori_id">
        <option value="">Semua Kategori</option>
        @foreach($kategoris as $kat)
        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
            {{ $kat->nama }}
        </option>
        @endforeach
    </select>

    <select class="filter-select" name="divisi_id">
        <option value="">Semua Divisi</option>
        @foreach($divisis as $div)
        <option value="{{ $div->id }}" {{ request('divisi_id') == $div->id ? 'selected' : '' }}>
            {{ $div->nama }}
        </option>
        @endforeach
    </select>

    <select class="filter-select" name="tahun">
        <option value="">Semua Tahun</option>
        @foreach($tahunList as $tahun)
        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
        @endforeach
    </select>

    <select class="filter-select" name="status">
        <option value="">Semua Status</option>
        @foreach(['draft'=>'Draft','menunggu'=>'Menunggu','ditinjau'=>'Ditinjau','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $val => $label)
        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn-primary">Terapkan Filter</button>
    @if(request()->hasAny(['q','kategori_id','divisi_id','tahun','status']))
        <a href="{{ route('arsip.index') }}" class="btn-sm btn-view">Reset</a>
    @endif
</form>

{{-- Tabel --}}
<div class="card" style="padding:0; overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="padding-left:20px">Dokumen</th>
                    <th>Kategori</th>
                    <th>Divisi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Ukuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsips as $arsip)
                <tr>
                    <td style="padding-left:20px">
                        <div class="doc-thumb">
                            <div class="doc-thumb-icon {{ $arsip->file_pertama?->ekstensi ?? 'pdf' }}">
                                {{ $arsip->file_pertama?->ikon ?? '📄' }}
                            </div>
                            <div>
                                <div class="doc-thumb-name">{{ Str::limit($arsip->judul, 45) }}</div>
                                <div class="doc-thumb-id">{{ $arsip->kode_arsip }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="category-tag">{{ $arsip->kategori->nama }}</span></td>
                    <td>{{ $arsip->divisi->nama }}</td>
                    <td>{{ $arsip->tanggal_dokumen->format('d/m/Y') }}</td>
                    <td>
                        <span class="doc-status status-{{ $arsip->status_color }}">
                            {{ $arsip->status_label }}
                        </span>
                    </td>
                    <td>{{ $arsip->file_pertama?->ukuran_format ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('arsip.show', $arsip) }}" class="tbl-btn" title="Lihat">👁</a>
                            <a href="{{ route('arsip.download', $arsip) }}" class="tbl-btn" title="Unduh">⬇️</a>
                            @can('update', $arsip)
                            <a href="{{ route('arsip.edit', $arsip) }}" class="tbl-btn" title="Edit">✏️</a>
                            @endcan
                            @can('delete', $arsip)
                            <form method="POST" action="{{ route('arsip.destroy', $arsip) }}"
                                  onsubmit="return confirm('Hapus arsip ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn" title="Hapus">🗑️</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon">🗂️</div>
                            <p>Tidak ada arsip yang sesuai filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 20px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <span style="font-size:12.5px; color:var(--text-muted)">
            Menampilkan {{ $arsips->firstItem() }}–{{ $arsips->lastItem() }} dari {{ number_format($arsips->total()) }} dokumen
        </span>
        {{ $arsips->withQueryString()->links() }}
    </div>
</div>
@endsection