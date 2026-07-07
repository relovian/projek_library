@extends('layouts.app')
@section('title', 'Backup & Pemeliharaan')
@section('breadcrumb', 'Pengaturan / Backup')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Backup & Pemeliharaan</h1>
    <p class="text-[14px] text-abu">Kelola backup data dan pemeliharaan rutin sistem SIARSIP</p>
</div>

<div class="mt-5 mb-5">
    <a href="{{ route('pengaturan.index') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
        Kembali ke Pengaturan
    </a>
</div>

<div class="grid grid-cols-2 gap-[20px] items-start">

    {{-- Backup Database --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5"> Backup Files </div>

        <div class="bg-surface2 rounded-lg p-4 mb-5">
            <div class="text-xs text-abu mb-1">Backup Terakhir</div>
            <div class="text-sm font-bold">{{ now()->format('d M Y, H:i') }} WIB</div>
            <div class="text-xs text-[#059669] mt-1">✅ Sistem berjalan normal</div>
        </div>

        <div class="flex flex-col gap-[10px] mb-5">
            
            <form method="POST" action="{{ route('pengaturan.backup.files') }}">
                @csrf
                <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border w-full justify-center p-2"
                    onclick="return confirm('Backup semua file arsip? Proses ini mungkin memakan waktu.')">
                    Backup File Arsip
                </button>
            </form>
        </div>

        <div class="text-xs text-abu leading-[1.7] p-3 bg-[#FFFBEB] rounded-lg border border-solid border-[#FDE68A]">
            ⚠️ <strong>Penting:</strong> Lakukan backup secara rutin minimal 1x seminggu untuk mencegah kehilangan data.
        </div>
    </div>

    {{-- Riwayat Backup --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Riwayat Backup</div>

        @if(session('success'))
        <div class="bg-[#ECFDF5] border border-solid border-[#A7F3D0] rounded-lg px-2 py-3 mb-4 text-xs text-[#059669]">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-[#FEF2F2] border border-solid border-[#FECACA] rounded-lg px-2 py-3 mb-4 text-xs text-[#DC2626]">
            ❌ {{ session('error') }}
        </div>
        @endif

        @if(count($backups) > 0)
        <ul class="list-none">
            @foreach($backups as $backup)
            <li class="flex items-center gap-[12px] px-3 py-3 border-b border-solid border-border last:border-b-0">
                <div class="w-9 h-9 rounded-lg {{ $backup['tipe'] === 'database' ? 'bg-[#EFF6FF]' : 'bg-[#ECFDF5]' }} flex items-center justify-center text-lg shrink-0">
                    {{ $backup['tipe']==='database' ? '🗄️' : '📦' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold truncate">
                        {{ $backup['nama'] }}
                    </div>
                    <div class="text-xs text-abu mt-[2px]">
                        {{ $backup['ukuran'] }} · {{ \Carbon\Carbon::parse($backup['waktu'])->diffForHumans() }}
                    </div>
                </div>
                <a href="{{ route('pengaturan.backup.download', $backup['nama']) }}" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2 no-underline text-hitam" title="Unduh">⬇️</a>
                <form method="POST" action="{{ route('pengaturan.backup.destroy', $backup['nama']) }}" class="inline" onsubmit="return confirm('Hapus backup {{ $backup['nama'] }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-[6px] border border-border bg-surface cursor-pointer text-[13px] flex items-center justify-center transition-colors duration-150 hover:bg-surface2" title="Hapus">🗑️</button>
                </form>
            </li>
            @endforeach
        </ul>
        @else
        <div class="text-center py-8">
            <div class="text-3xl mb-2">📭</div>
            <div class="text-xs text-abu">Belum ada backup tersimpan</div>
            <div class="text-xs text-abu mt-1">Klik tombol di samping untuk membuat backup baru</div>
        </div>
        @endif
    </div>

    {{-- Pemeliharaan Sistem --}}
    <div class="relative bottom-20 bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5"> Pemeliharaan Sistem</div>

        <div class="flex flex-col gap-3">

            <div class="p-4 bg-surface2 rounded-lg flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold">🗑️ Bersihkan Cache</div>
                    <div class="text-xs text-abu mt-[2px]">Hapus cache aplikasi dan config</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.cache') }}">
                    @csrf
                    <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">Jalankan</button>
                </form>
            </div>

            <div class="p-4 bg-surface2 rounded-lg flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold">📝 Bersihkan Log Lama</div>
                    <div class="text-xs text-abu mt-[2px]">Hapus log aktivitas lebih dari 30 hari</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.log') }}">
                    @csrf
                    <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border"
                        onclick="return confirm('Hapus log lebih dari 30 hari?')">Jalankan</button>
                </form>
            </div>

            <div class="p-4 bg-surface2 rounded-lg flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold">🗂️ Bersihkan Draft Kadaluarsa</div>
                    <div class="text-xs text-abu mt-[2px]">Hapus draft yang tidak diubah lebih dari 30 hari</div>
                </div>
                <form method="POST" action="{{ route('pengaturan.maintenance.draft') }}">
                    @csrf
                    <button type="submit" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border"
                        onclick="return confirm('Hapus draft kadaluarsa?')">Jalankan</button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection