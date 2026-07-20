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
                <div class="relative min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="chartSubBagian" class="w-full chart-canvas"></canvas>
                    <p id="emptySubBagian" class="absolute inset-0 flex items-center justify-center text-[13px] text-abu px-4 text-center">
                        Tidak ada statistik arsip per sub bagian.
                    </p>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Klasifikasi</div>
                <div class="relative min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="chartKlasifikasi" class="w-full chart-canvas"></canvas>
                    <p id="emptyKlasifikasi" class="absolute inset-0 flex items-center justify-center text-[13px] text-abu px-4 text-center">
                        Tidak ada statistik arsip per klasifikasi.
                    </p>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Sifat Surat</div>
                <div class="relative min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="chartSifat" class="w-full chart-canvas"></canvas>
                    <p id="emptySifat" class="absolute inset-0 flex items-center justify-center text-[13px] text-abu px-4 text-center">
                        Tidak ada statistik arsip per sifat surat.
                    </p>
                </div>
            </div>
        </div>

        {{-- Baris 2: 2 kolom (Verifikator & Tujuan sejajar) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Verifikator</div>
                <div class="relative min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="chartVerifikator" class="w-full chart-canvas"></canvas>
                    <p id="emptyVerifikator" class="absolute inset-0 flex items-center justify-center text-[13px] text-abu px-4 text-center">
                        Tidak ada statistik arsip per verifikator.
                    </p>
                </div>
            </div>

            <div class="bg-surface2 border border-border rounded-[14px] p-4">
                <div class="text-[13px] font-bold mb-2">Tujuan</div>
                <div class="relative min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="chartTujuan" class="w-full chart-canvas"></canvas>
                    <p id="emptyTujuan" class="absolute inset-0 flex items-center justify-center text-[13px] text-abu px-4 text-center">
                        Tidak ada statistik arsip per tujuan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #statCharts canvas.chart-canvas { max-width: 420px; }
        .chart-canvas { display: block; }
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
                var emptyMap = {
                    'chartSubBagian': 'emptySubBagian',
                    'chartKlasifikasi': 'emptyKlasifikasi',
                    'chartSifat': 'emptySifat',
                    'chartVerifikator': 'emptyVerifikator',
                    'chartTujuan': 'emptyTujuan',
                };

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
                    var emptyEl = document.getElementById(emptyMap[canvasId]);
                    if (!canvas) return;

                    if (chartInstances[canvasId]) {
                        chartInstances[canvasId].destroy();
                    }

                    var labels = (chartData[key] && chartData[key].labels) ? chartData[key].labels : [];
                    var values = (chartData[key] && chartData[key].values) ? chartData[key].values : [];

                    if (labels.length === 0 || values.every(function(v) { return v === 0; })) {
                        // Tidak ada data — tampilkan pesan kosong
                        canvas.classList.add('hidden');
                        if (emptyEl) emptyEl.classList.remove('hidden');
                    } else {
                        canvas.classList.remove('hidden');
                        if (emptyEl) emptyEl.classList.add('hidden');
                        chartInstances[canvasId] = new Chart(canvas.getContext('2d'), makePieConfig(labels, values));
                    }
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

@php
    function truncatePerihal($text, $max = 8) {
        if (!$text || mb_strlen($text) <= $max) return $text ?? '-';
        return mb_substr($text, 0, $max) . '...';
    }
@endphp

{{-- Grid Konten --}}
<div class="grid grid-cols-[1fr_1fr] gap-5">
    {{-- Arsip Masuk Terbaru --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Arsip Masuk Terbaru Diunggah</div>
                <div class="text-[12px] text-abu mt-[2px]">5 terbaru</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('arsip.index') }}">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB]">
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Kode Arsip</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Perihal</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Asal</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Tanggal Surat</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Pengunggah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsipMasukTerbaru as $arsip)
                    <tr class="border-b border-border last:border-b-0 hover:bg-[#F9FAFB]">
                        <td class="py-[10px] px-3 font-mono text-[12px]">{{ $arsip->kode_arsip_masuk ?? '-' }}</td>
                        <td class="py-[10px] px-3" title="{{ $arsip->perihal ?? '-' }}">{{ truncatePerihal($arsip->perihal) }}</td>
                        <td class="py-[10px] px-3">{{ $arsip->asal_instansi ?? '-' }}</td>
                        <td class="py-[10px] px-3 whitespace-nowrap">{{ $arsip->tanggal_surat ? $arsip->tanggal_surat->format('d/m/Y') : '-' }}</td>
                        <td class="py-[10px] px-3">{{ $arsip->uploader?->nama_lengkap ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-abu text-[14px]">Belum ada arsip masuk yang diunggah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Arsip Keluar Terbaru --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="text-[15px] font-bold">Arsip Keluar Terbaru Diunggah</div>
                <div class="text-[12px] text-abu mt-[2px]">5 terbaru</div>
            </div>
            <a class="text-[12px] text-bawaslu-red font-semibold cursor-pointer no-underline" href="{{ route('arsip.index') }}">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB]">
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Kode Arsip</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Perihal</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Tujuan</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Tanggal Surat</th>
                        <th class="py-[10px] px-3 text-left text-[12px] text-abu font-semibold border-b border-border">Pengunggah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsipKeluarTerbaru as $arsip)
                    <tr class="border-b border-border last:border-b-0 hover:bg-[#F9FAFB]">
                        <td class="py-[10px] px-3 font-mono text-[12px]">{{ $arsip->kode_arsip_keluar ?? '-' }}</td>
                        <td class="py-[10px] px-3" title="{{ $arsip->perihal ?? '-' }}">{{ truncatePerihal($arsip->perihal) }}</td>
                        <td class="py-[10px] px-3">{{ $arsip->tujuan?->nama ?? '-' }}</td>
                        <td class="py-[10px] px-3 whitespace-nowrap">{{ $arsip->tanggal_surat ? $arsip->tanggal_surat->format('d/m/Y') : '-' }}</td>
                        <td class="py-[10px] px-3">{{ $arsip->uploader?->nama_lengkap ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-abu text-[14px]">Belum ada arsip keluar yang diunggah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
