@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Selamat Datang, {{ auth()->user()->nama_lengkap }} </h1>
    <p class="text-[14px] text-abu">Ringkasan sistem pengelolaan arsip Bawaslu — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-bawaslu-red"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#FEF2F2]">
            <img src="{{ asset('img/unggah.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ number_format($stats['total_arsip_masuk']) }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Total Arsip Masuk</div>
    </div>

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-blue-500"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#EFF6FF]">
            <img src="{{ asset('img/unduh.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ number_format($stats['total_arsip_keluar']) }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Total Arsip Keluar</div>
    </div>

    <div class="group relative overflow-hidden rounded-[14px] border border-border bg-surface p-[22px] transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.07)]">
        <div class="absolute inset-x-0 top-0 h-[3px] bg-emerald-500"></div>
        <div class="mb-[14px] flex size-[42px] items-center justify-center rounded-[10px] bg-[#ECFDF5]">
            <img src="{{ asset('img/arsip.png') }}" class="size-5" alt="">
        </div>
        <div class="text-[30px] font-extrabold leading-none text-text">{{ number_format($stats['total_arsip_semua']) }}</div>
        <div class="mt-[6px] text-[12.5px] font-medium text-text-abu">Total Semua Arsip</div>
    </div>
</div>

{{-- Statistik Arsip (Chart Bulat) --}}
<div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="text-[15px] font-bold">Statistik Arsip</div>
            <div class="text-[12px] text-abu mt-[2px]">Sub Bagian, Klasifikasi, Sifat Surat, Verifikator, dan Tujuan</div>
        </div>
        <button id="btnTampilkanStat" type="button"
            class="px-3 py-[7px] rounded-lg bg-bawaslu-red text-white text-[12px] font-semibold [font-family:inherit] transition-opacity duration-200 hover:opacity-[0.9]">
            Tampilkan Statistik
        </button>
    </div>

    <div id="statCharts" class="hidden">
        {{-- Baris 1: 3 kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Sub Bagian</div>
                <div class="flex items-center justify-center">
                    <canvas id="chartSubBagian" class="w-full" height="220"></canvas>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Klasifikasi</div>
                <div class="flex items-center justify-center">
                    <canvas id="chartKlasifikasi" class="w-full" height="220"></canvas>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Sifat Surat</div>
                <div class="flex items-center justify-center">
                    <canvas id="chartSifat" class="w-full" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Baris 2: 2 kolom (Verifikator & Tujuan sejajar) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Verifikator</div>
                <div class="flex items-center justify-center">
                    <canvas id="chartVerifikator" class="w-full" height="220"></canvas>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Tujuan</div>
                <div class="flex items-center justify-center">
                    <canvas id="chartTujuan" class="w-full" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #statCharts canvas { max-width: 420px; }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);
            const chartInstances = {};

            function makePieConfig(labels, values) {
                const colors = [
                    '#C0272D', '#2563EB', '#059669', '#D97706', '#7C3AED',
                    '#DC2626', '#10B981', '#6B7280', '#111827', '#3B82F6'
                ];
                const bg = values.map(function(_, i) { return colors[i % colors.length]; });

                return {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: bg,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed; } } }
                        }
                    }
                };
            }

            function renderCharts() {
                var ids = [
                    ['chartSubBagian', 'sub_bagian'],
                    ['chartKlasifikasi', 'klasifikasi'],
                    ['chartSifat', 'sifat'],
                    ['chartVerifikator', 'verifikator'],
                    ['chartTujuan', 'tujuan'],
                ];

                ids.forEach(function(item) {
                    var canvasId = item[0];
                    var key = item[1];
                    var canvas = document.getElementById(canvasId);
                    if (!canvas) return;

                    if (chartInstances[canvasId]) {
                        chartInstances[canvasId].destroy();
                    }

                    var labels = (chartData[key] && chartData[key].labels) ? chartData[key].labels : [];
                    var values = (chartData[key] && chartData[key].values) ? chartData[key].values : [];

                    chartInstances[canvasId] = new Chart(canvas.getContext('2d'), makePieConfig(labels, values));
                });
            }

            var btn = document.getElementById('btnTampilkanStat');
            var wrap = document.getElementById('statCharts');

            if (btn && wrap) {
                btn.addEventListener('click', function() {
                    wrap.classList.remove('hidden');
                    renderCharts();
                });
            }
        });
    </script>
@endpush

{{-- Grid Konten --}}
<div class="grid grid-cols-[1fr_340px] gap-5">
    {{-- Arsip Terbaru --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Arsip Terbaru Diunggah</div>
                <div class="text-[12px] text-abu mt-[2px]">7 hari terakhir</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('arsip.index') }}">Lihat semua →</a>
        </div>
        <ul class="list-none flex-col">
            @forelse($arsipTerbaru as $arsip)
            <li class="flex items-center gap-[14px] py-3 border-b border-border cursor-pointer last:border-b-0" onclick="window.location='{{ route('arsip.show', $arsip) }}'">
                <div class="w-[38px] h-[38px] rounded-[9px] flex items-center justify-center text-lg shrink-0">
                    <img src="{{ $arsip->file_pertama?->ikon ?? asset('img/berkas.png') }}"
                        alt="icon" class="w-[25px] h-[25px] object-contain">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-semibold truncate">{{ $arsip->judul }}</div>
                    <div class="text-[11.5px] text-abu mt-[2px]">
                        {{ $arsip->divisi->nama }} ·
                        {{ $arsip->tanggal_dokumen->format('d M Y') }} ·
                        {{ $arsip->file_pertama?->ukuran_format ?? '-' }}
                    </div>
                </div>
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
            </li>
            @empty
            <li class="flex flex-col items-center justify-center py-10 px-5 text-abu">
                <div class="text-[40px] mb-[10px]"><img src="{{ asset('img/arsip.png') }}" alt=""></div>
                <p class="text-[14px]">Belum ada arsip yang diunggah.</p>
            </li>
            @endforelse
        </ul>
    </div>

    {{-- Perlu Ditinjau (Admin/Pimpinan) atau Aktivitas Saya (Staff) --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Perlu Ditinjau</div>
                <div class="text-[12px] text-abu mt-[2px]">{{ $stats['menunggu'] }} dokumen menunggu</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('persetujuan.index') }}">Semua →</a>
        </div>
        <ul class="list-none">
            @forelse($menungguPersetujuan as $arsip)
            <li class="py-3 border-b border-border last:border-b-0">
                <div class="flex justify-between items-start mb-1">
                    <div class="text-[13px] font-semibold">{{ Str::limit($arsip->judul, 40) }}</div>
                    <div class="text-[11px] text-abu">{{ $arsip->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-[12px] text-abu">
                    <img src="{{ asset('img/unggah.png') }}" class="w-3 h-3 mr-[5px]" alt="">
                    {{ $arsip->uploader->nama_lengkap }} · {{ $arsip->divisi->nama }}
                </div>
                <div class="flex gap-1.5 mt-[15px] flex-wrap flex justify-start items-center">
                    <form method="POST" action="{{ route('persetujuan.setujui', $arsip) }}" class="flex justify-start items-center">
                        @csrf
                        <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border-none [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-[#059669] text-white h-6">Setujui</button>
                    </form>
                    <button type="button" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-abu border-border h-6"
                        onclick="openTolakModal({{ $arsip->id }}, '{{ addslashes($arsip->judul) }}')">
                        Tolak
                    </button>
                    <a href="{{ route('arsip.show', $arsip) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border h-6">Lihat</a>
                </div>
            </li>
            @empty
            <li class="flex flex-col justify-center items-center py-10 px-5 text-abu">
                <div class="text-[40px] mb-[10px]"><img src="{{ asset('img/persetujuan.png') }}" alt=""></div>
                <p class="text-[14px]">Tidak ada dokumen yang menunggu.</p>
            </li>
            @endforelse
        </ul>
    </div>
    @else
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div class="text-[15px] font-bold">Aktivitas Terakhir Saya</div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('aktivitas.index') }}">Semua →</a>
        </div>
        <ul class="list-none">
            @forelse($aktivitasSaya as $log)
            <li class="flex gap-[14px] py-4 border-b border-border items-start last:border-b-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0 mt-[2px]
                    @switch($log->aksi_warna_dot)
                        @case('upload') bg-[#ECFDF5] @break
                        @case('download') bg-[#EFF6FF] @break
                        @case('approve') bg-[#F0FDF4] @break
                        @case('edit') bg-[#FFFBEB] @break
                        @case('reject') bg-[#FEF2F2] @break
                        @default bg-[#EFF6FF]
                    @endswitch">
                    <img src="{{ $log->aksi_ikon }}" width="15" height="15" alt="">
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold">{{ $log->aksi_label }}</div>
                    @if($log->arsip)
                    <div class="text-[12.5px] text-abu mt-[2px]">{{ $log->arsip->judul }}</div>
                    @endif
                    <div class="text-[11.5px] text-abu mt-1">{{ $log->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </li>
            @empty
            <li class="text-center py-10 px-5 text-abu"><p class="mt-10 text-[14px]">Belum ada aktivitas.</p></li>
            @endforelse
        </ul>
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div class="hidden fixed inset-0 bg-black/45 z-[200] items-center justify-center" id="modalTolak">
    <div class="bg-surface rounded-[16px] p-8 max-w-[520px] w-[90%]">
        <div class="flex justify-between items-start mb-6">
            <div class="text-lg font-bold">Tolak Arsip</div>
            <button class="text-xl cursor-pointer text-abu bg-none border-none" onclick="closeTolakModal()">✕</button>
        </div>
        <p class="text-xs text-abu mt-[16px]">
            Berikan alasan penolakan untuk dokumen: <strong id="modalDocName"></strong>
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
        document.getElementById('modalTolak').classList.add('flex');
        document.getElementById('modalTolak').classList.remove('hidden');
    }
    function closeTolakModal() {
        document.getElementById('modalTolak').classList.remove('flex');
        document.getElementById('modalTolak').classList.add('hidden');
    }
    </script>
@endpush