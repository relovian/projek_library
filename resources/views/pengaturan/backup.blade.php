@extends('layouts.app')
@section('title', 'Backup & Pemeliharaan')
@section('breadcrumb', 'Pengaturan / Backup')

@section('content')
<div class="page-header">
    <h1>Backup & Pemeliharaan</h1>
    <p>Kelola backup data dan pemeliharaan rutin sistem SIARSIP</p>
</div>

<div style="margin-bottom: 20px;">
    <a href="{{ route('pengaturan.index') }}" class="btn-sm btn-view" style="text-decoration: none;">
        Kembali ke Pengaturan
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">

    {{-- Backup Database --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:20px;">💾 Backup Database</div>

        <div style="background:var(--surface2); border-radius:10px; padding:16px; margin-bottom:20px;">
            <div style="font-size:12.5px; color:var(--text-muted); margin-bottom:4px;">Backup Terakhir</div>
            <div style="font-size:15px; font-weight:700;">{{ now()->format('d M Y, H:i') }} WIB</div>
            <div style="font-size:12px; color:#059669; margin-top:4px;">✅ Sistem berjalan normal</div>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
            <form method="POST" action="{{ route('pengaturan.backup.database') }}">
                @csrf
                <button type="submit" class="btn-primary" style="width:100%; justify-content:center;"
                    onclick="return confirm('Mulai backup database sekarang?')">
                    💾 Backup Database Sekarang
                </button>
            </form>
            <form method="POST" action="{{ route('pengaturan.backup.files') }}">
                @csrf
                <button type="submit" class="btn-sm btn-view" style="width:100%; justify-content:center; padding:9px;"
                    onclick="return confirm('Backup semua file arsip? Proses ini mungkin memakan waktu.')">
                    📁 Backup File Arsip
                </button>
            </form>
        </div>

        <div style="font-size:12.5px; color:var(--text-muted); line-height:1.7; padding:12px; background:#FFFBEB; border-radius:8px; border:1px solid #FDE68A;">
            ⚠️ <strong>Penting:</strong> Lakukan backup secara rutin minimal 1x seminggu untuk mencegah kehilangan data.
        </div>
    </div>

    {{-- Riwayat Backup --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:20px;">📋 Riwayat Backup</div>

        @if(session('success'))
        <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#059669;">
            ✅ {{ session('success') }}
        </div>
        @endif

        @php
        $backups = [
            ['nama'=>'backup_db_'.date('Ymd').'.sql', 'ukuran'=>'12.4 MB', 'waktu'=>now()->subHours(2), 'tipe'=>'database'],
            ['nama'=>'backup_db_'.date('Ymd', strtotime('-1 day')).'.sql', 'ukuran'=>'12.1 MB', 'waktu'=>now()->subDay(), 'tipe'=>'database'],
            ['nama'=>'backup_files_'.date('Ymd', strtotime('-3 day')).'.zip', 'ukuran'=>'1.2 GB', 'waktu'=>now()->subDays(3), 'tipe'=>'files'],
            ['nama'=>'backup_db_'.date('Ymd', strtotime('-7 day')).'.sql', 'ukuran'=>'11.8 MB', 'waktu'=>now()->subWeek(), 'tipe'=>'database'],
        ];
        @endphp

        <ul style="list-style:none;">
            @foreach($backups as $backup)
            <li style="display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid var(--border);">
                <div style="width:36px; height:36px; border-radius:8px; background:{{ $backup['tipe']==='database' ? '#EFF6FF' : '#ECFDF5' }}; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
                    {{ $backup['tipe']==='database' ? '🗄️' : '📦' }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $backup['nama'] }}
                    </div>
                    <div style="font-size:11.5px; color:var(--text-muted); margin-top:2px;">
                        {{ $backup['ukuran'] }} · {{ $backup['waktu']->diffForHumans() }}
                    </div>
                </div>
                <button class="tbl-btn" title="Unduh">⬇️</button>
                <button class="tbl-btn" title="Hapus" onclick="return confirm('Hapus backup ini?')">🗑️</button>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Pemeliharaan Sistem --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:20px;">🔧 Pemeliharaan Sistem</div>

        <div style="display:flex; flex-direction:column; gap:12px;">

            <div style="padding:16px; background:var(--surface2); border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:13.5px; font-weight:600;">🗑️ Bersihkan Cache</div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">Hapus cache aplikasi dan config</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.cache') }}">
                    @csrf
                    <button type="submit" class="btn-sm btn-view" style="padding:7px 14px;">Jalankan</button>
                </form>
            </div>

            <div style="padding:16px; background:var(--surface2); border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:13.5px; font-weight:600;">📝 Bersihkan Log Lama</div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">Hapus log aktivitas lebih dari 90 hari</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.log') }}">
                    @csrf
                    <button type="submit" class="btn-sm btn-view" style="padding:7px 14px;"
                        onclick="return confirm('Hapus log lebih dari 90 hari?')">Jalankan</button>
                </form>
            </div>

            <div style="padding:16px; background:var(--surface2); border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:13.5px; font-weight:600;">🗂️ Bersihkan Draft Kadaluarsa</div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">Hapus draft yang tidak diubah lebih dari 30 hari</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.draft') }}">
                    @csrf
                    <button type="submit" class="btn-sm btn-view" style="padding:7px 14px;"
                        onclick="return confirm('Hapus draft kadaluarsa?')">Jalankan</button>
                </form>
            </div>

        </div>
    </div>

    {{-- Info Sistem --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:20px;">📊 Informasi Sistem</div>

        <table style="width:100%; font-size:13px;">
            @foreach([
                ['label'=>'Versi Laravel', 'value'=>app()->version()],
                ['label'=>'Versi PHP', 'value'=>PHP_VERSION],
                ['label'=>'Database', 'value'=>'MySQL'],
                ['label'=>'Total Arsip', 'value'=>\App\Models\Arsip::count().' dokumen'],
                ['label'=>'Total User', 'value'=>\App\Models\User::count().' pengguna'],
                ['label'=>'Ukuran Storage', 'value'=>'Calculating...'],
                ['label'=>'Uptime Server', 'value'=>'Normal'],
            ] as $info)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:10px 0; color:var(--text-muted); width:45%;">{{ $info['label'] }}</td>
                <td style="padding:10px 0; font-weight:600;">{{ $info['value'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>

</div>
@endsection