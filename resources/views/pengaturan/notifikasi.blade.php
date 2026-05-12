@extends('layouts.app')
@section('title', 'Notifikasi')
@section('breadcrumb', 'Pengaturan / Notifikasi')

@section('content')
<div class="page-header">
    <h1>Pengaturan Notifikasi</h1>
    <p>Atur preferensi notifikasi sistem untuk akun Anda</p>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">

    {{-- Notifikasi Sistem --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:20px;">🔔 Notifikasi Sistem</div>

        <div style="display:flex; flex-direction:column; gap:0;">

            @foreach([
                ['icon'=>'📄','label'=>'Arsip baru diunggah','sub'=>'Notifikasi saat ada arsip baru di divisi Anda','key'=>'arsip_baru'],
                ['icon'=>'✅','label'=>'Arsip disetujui','sub'=>'Notifikasi saat arsip yang Anda unggah disetujui','key'=>'arsip_disetujui'],
                ['icon'=>'❌','label'=>'Arsip ditolak','sub'=>'Notifikasi saat arsip yang Anda unggah ditolak','key'=>'arsip_ditolak'],
                ['icon'=>'⏳','label'=>'Menunggu persetujuan','sub'=>'Notifikasi saat ada dokumen menunggu persetujuan Anda','key'=>'menunggu_persetujuan'],
                ['icon'=>'🔄','label'=>'Revisi dokumen','sub'=>'Notifikasi saat ada revisi dokumen baru','key'=>'revisi_dokumen'],
            ] as $notif)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 0; border-bottom:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:var(--surface2); display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
                        {{ $notif['icon'] }}
                    </div>
                    <div>
                        <div style="font-size:13.5px; font-weight:600;">{{ $notif['label'] }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">{{ $notif['sub'] }}</div>
                    </div>
                </div>
                <label style="position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0;">
                    <input type="checkbox" checked style="opacity:0; width:0; height:0;">
                    <span style="position:absolute; cursor:pointer; inset:0; background:var(--bawaslu-red); border-radius:24px; transition:.3s;">
                        <span style="position:absolute; content:''; height:18px; width:18px; left:3px; bottom:3px; background:white; border-radius:50%; transition:.3s; transform:translateX(20px);"></span>
                    </span>
                </label>
            </div>
            @endforeach

        </div>

        <div style="margin-top:20px;">
            <button class="btn-primary">💾 Simpan Preferensi</button>
        </div>
    </div>

    {{-- Notifikasi Email --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">📧 Notifikasi Email</div>

            <div style="display:flex; flex-direction:column; gap:0;">
                @foreach([
                    ['label'=>'Ringkasan harian','sub'=>'Email ringkasan aktivitas setiap hari'],
                    ['label'=>'Ringkasan mingguan','sub'=>'Email ringkasan arsip setiap minggu'],
                    ['label'=>'Persetujuan mendesak','sub'=>'Email langsung saat ada dokumen penting'],
                ] as $item)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid var(--border);">
                    <div>
                        <div style="font-size:13.5px; font-weight:600;">{{ $item['label'] }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">{{ $item['sub'] }}</div>
                    </div>
                    <input type="checkbox" style="width:16px; height:16px; accent-color:var(--bawaslu-red); cursor:pointer;">
                </div>
                @endforeach
            </div>

            <div style="margin-top:16px; padding:12px; background:var(--surface2); border-radius:8px; font-size:12.5px; color:var(--text-muted);">
                📬 Email akan dikirim ke: <strong>{{ auth()->user()->email }}</strong>
            </div>
        </div>

        <div class="card" style="background:#FFFBEB; border-color:#FDE68A;">
            <div style="font-size:13px; font-weight:700; color:#92400E; margin-bottom:8px;">⚠️ Fitur dalam Pengembangan</div>
            <p style="font-size:13px; color:#78350F; line-height:1.6;">
                Pengiriman notifikasi email sedang dalam proses pengembangan dan akan segera tersedia.
            </p>
        </div>
    </div>

</div>
@endsection