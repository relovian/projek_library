@extends('layouts.app')
@section('title', $arsip->judul)
@section('breadcrumb', 'Arsip / Detail')

@section('content')

<div class="container_arsip arsip-layout">
    <div class="wrapper arsip-main-wrapper">
        <div class="container arsip-header-container">
            <div class="card arsip-header-card">
                <div class="arsip-header-left">
                    <div class="arsip-icon-wrapper">
                        <img 
                            src="{{ $arsip->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                            alt="icon"
                            class="arsip-icon-image"
                        >
                    </div>

                    <div class="arsip-header-content">
                        <h2 class="arsip-title">
                            {{ $arsip->judul }}
                        </h2>

                        <div class="arsip-meta">
                            <span class="category-tag">
                                {{ $arsip->kategori->nama }}
                            </span>

                            <span class="doc-status status-{{ $arsip->status_color }}">
                                {{ $arsip->status_label }}
                            </span>

                            @if($arsip->nomor_surat)
                            <span class="arsip-nomor">
                                No. {{ $arsip->nomor_surat }}
                            </span>
                            @endif

                        </div>

                    </div>

                </div>

                <div class="arsip-header-action">

                    <a href="{{ route('arsip.download', $arsip) }}" class="btn-primary">
                        Unduh
                    </a>

                    @can('update', $arsip)
                    <a href="{{ route('arsip.edit', $arsip) }}"
                       class="btn-sm btn-view arsip-btn-edit">

                        <img src="{{ asset('img/edit.png') }}" alt=""> Edit

                    </a>
                    @endcan

                </div>

            </div>

        </div>

        {{-- Deskripsi --}}
        @if($arsip->deskripsi)

        <div class="card arsip-description-card">

            <div class="card-title arsip-card-title">
                Deskripsi
            </div>

            <p class="arsip-description-text">
                {{ $arsip->deskripsi }}
            </p>

        </div>

        @endif

        {{-- Catatan Penolakan --}}
        @if($arsip->status === 'ditolak' && $arsip->catatan_penolakan)

        <div class="card arsip-reject-card">

            <div class="arsip-reject-title">
                Alasan Penolakan
            </div>

            <p class="arsip-reject-text">
                {{ $arsip->catatan_penolakan }}
            </p>

            @if($arsip->penyetuju)

            <div class="arsip-reject-info">
                Ditolak oleh {{ $arsip->penyetuju->nama_lengkap }} ·
                {{ $arsip->disetujui_at?->format('d M Y, H:i') }} WIB
            </div>

            @endif

        </div>

        @endif

        {{-- Riwayat Versi --}}
        @if($arsip->revisis->count() > 0 || $arsip->arsipInduk)

        <div class="card arsip-version-card">

            <div class="card-title arsip-card-title">
                Riwayat Versi
            </div>

            @if($arsip->arsipInduk)

            <div class="arsip-parent-doc">

                Dokumen induk:

                <a href="{{ route('arsip.show', $arsip->arsipInduk) }}"
                   class="arsip-parent-link">

                    {{ $arsip->arsipInduk->judul }}

                </a>

            </div>

            @endif

            @foreach($arsip->revisis as $rev)

            <div class="arsip-version-item">

                <div class="arsip-version-info">

                    <span class="arsip-version-label">
                        Versi {{ $rev->versi }}
                    </span>

                    <span class="arsip-version-date">
                        {{ $rev->created_at->format('d M Y') }}
                    </span>

                </div>

                <a href="{{ route('arsip.show', $rev) }}"
                   class="btn-sm btn-view arsip-version-btn">

                    Lihat

                </a>

            </div>

            @endforeach

        </div>

        @endif

        {{-- Aktivitas Log --}}
        <div class="card arsip-activity-card">

            <div class="card-title arsip-card-title">
                Riwayat Aktivitas
            </div>

            @forelse($arsip->aktivitasLogs()->with('user')->latest()->take(10)->get() as $log)

            <div class="arsip-activity-item">

                <div class="tl-dot {{ $log->aksi_warna_dot }}">
                    <img src="{{ $log->aksi_ikon }}" alt="">
                </div>

                <div class="arsip-activity-content">

                    <div class="arsip-activity-title">
                        {{ $log->aksi_label }}
                    </div>

                    <div class="arsip-activity-date">
                        {{ $log->user->nama_lengkap }} ·
                        {{ $log->created_at->format('d M Y, H:i') }} WIB
                    </div>

                </div>

            </div>

            @empty

            <div class="empty-state arsip-empty-state">
                <p>Belum ada aktivitas.</p>
            </div>

            @endforelse

        </div>

    </div>

    {{-- Sidebar --}}
    <div class="arsip-sidebar">

        {{-- Informasi Dokumen --}}
        <div class="card arsip-info-card">

            <div class="card-title arsip-card-title">
                Informasi Dokumen
            </div>

            <table class="arsip-info-table">

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Kode Arsip</td>
                    <td class="arsip-info-value">{{ $arsip->kode_arsip }}</td>
                </tr>

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Tanggal</td>
                    <td class="arsip-info-value">
                        {{ $arsip->tanggal_dokumen->format('d M Y') }}
                    </td>
                </tr>

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Divisi</td>
                    <td class="arsip-info-value">
                        {{ $arsip->divisi->nama }}
                    </td>
                </tr>

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Pengunggah</td>
                    <td class="arsip-info-value">
                        {{ $arsip->uploader->nama_lengkap }}
                    </td>
                </tr>

                @if($arsip->periode_pemilu)

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Periode</td>
                    <td class="arsip-info-value">
                        {{ $arsip->periode_pemilu }}
                    </td>
                </tr>

                @endif

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Akses</td>
                    <td class="arsip-info-value">
                        {{ ucfirst(str_replace('_', ' ', $arsip->tingkat_akses)) }}
                    </td>
                </tr>

                <tr class="arsip-info-row">
                    <td class="arsip-info-label">Versi</td>
                    <td class="arsip-info-value">
                        v{{ $arsip->versi }}
                    </td>
                </tr>

                @if($arsip->status === 'disetujui' && $arsip->penyetuju)

                <tr class="arsip-info-row">

                    <td class="arsip-info-label">
                        Disetujui
                    </td>

                    <td class="arsip-info-value">

                        {{ $arsip->penyetuju->nama_lengkap }}

                        <br>

                        <span class="arsip-approved-date">
                            {{ $arsip->disetujui_at?->format('d M Y') }}
                        </span>

                    </td>

                </tr>

                @endif

            </table>

        </div>

        {{-- File --}}
        <div class="card arsip-file-card">

            <div class="card-title arsip-card-title">
                <span>File Dokumen</span>
            </div>

            @forelse($arsip->files as $file)

            <div class="arsip-file-item">

                <div class="arsip-file-icon">
                    <img src="{{ $file->ikon }}" alt="">
                </div>

                <div class="arsip-file-content">

                    <div class="arsip-file-name">
                        {{ $file->nama_asli }}
                    </div>

                    <div class="arsip-file-meta">
                        {{ strtoupper($file->ekstensi) }} · {{ $file->ukuran_format }}
                    </div>

                </div>

                <a href="{{ route('arsip.download', $arsip) }}"
                   class="tbl-btn arsip-file-download"
                   title="Unduh">

                    <img src="{{ asset('img/unggah.png') }}" alt="" class="icon_unggah">

                </a>

            </div>

            @empty

            <div class="empty-state arsip-empty-state-file">
                <p>Tidak ada file.</p>
            </div>

            @endforelse

        </div>

        {{-- Tags --}}
        @if($arsip->tags)

        <div class="card arsip-tag-card">

            <div class="card-title arsip-tag-title">
                Tag
            </div>

            <div class="arsip-tag-wrapper">

                @foreach($arsip->tags_array as $tag)

                <span class="arsip-tag-item">
                    {{ trim($tag) }}
                </span>

                @endforeach

            </div>

        </div>

        @endif

        {{-- Aksi Admin --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
        @if($arsip->status === 'menunggu')

        <div class="card arsip-approval-card">

            <div class="arsip-approval-title">
                Tindakan Persetujuan
            </div>

            <form method="POST"
                  action="{{ route('persetujuan.setujui', $arsip) }}"
                  class="arsip-approval-form">

                @csrf

                <button type="submit"
                        class="btn-primary arsip-btn-approve"
                        onclick="return confirm('Setujui dokumen ini?')">

                    Setujui Dokumen

                </button>

            </form>

            <button type="button"
                    class="btn-sm btn-reject arsip-btn-reject"
                    onclick="document.getElementById('modalTolakShow').classList.add('open')">

                Tolak Dokumen

            </button>

        </div>

        @endif
        @endif

    </div>

</div>

{{-- Tombol Kembali --}}
<div class="arsip-back-wrapper">

    <a href="{{ route('arsip.index') }}"
       class="btn-sm btn-view arsip-back-btn">

        ← Kembali ke Daftar Arsip

    </a>

</div>

{{-- Modal Tolak --}}
<div class="modal-overlay arsip-modal-overlay" id="modalTolakShow">

    <div class="modal arsip-modal">

        <div class="modal-header arsip-modal-header">

            <div class="modal-title arsip-modal-title">
                Tolak Dokumen
            </div>

            <button class="modal-close arsip-modal-close"
                onclick="document.getElementById('modalTolakShow').classList.remove('open')">

                ✕

            </button>

        </div>

        <form method="POST"
              action="{{ route('persetujuan.tolak', $arsip) }}"
              class="arsip-modal-form">

            @csrf

            <div class="form-group arsip-form-group">

                <label class="form-label arsip-form-label">
                    Catatan Penolakan
                    <span class="required">*</span>
                </label>

                <textarea class="form-textarea arsip-form-textarea"
                          name="catatan_penolakan"
                          required
                          placeholder="Tuliskan alasan penolakan…"></textarea>

            </div>

            <div class="arsip-modal-action">

                <button type="button"
                        class="btn-sm btn-view arsip-modal-cancel"
                        onclick="document.getElementById('modalTolakShow').classList.remove('open')">

                    Batal

                </button>

                <button type="submit"
                        class="btn-sm arsip-modal-submit">

                    Konfirmasi Tolak

                </button>

            </div>

        </form>

    </div>

</div>

@endsection