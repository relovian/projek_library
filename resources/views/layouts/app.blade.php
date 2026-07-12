<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIARSIP') — Bawaslu RI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="overflow-x-hidden ">
    
    <aside class="w-64 fixed inset-y-0 left-0 z-50 flex flex-col bg-[#8B1A1F]">
        <div class="px-5 py-6 border-b border-white/10 flex items-center gap-3">
            <div class="w-[42px] h-[42px] bg-[var(--bawaslu-gold)] rounded-[10px] flex items-center justify-center text-[20px] font-extrabold text-white shrink-0">B</div>
            <div class="text-white">
                <div class="text-sm font-bold">SIARSIP Bawaslu</div>
                <div class="tracking-[0.5px] text-xs uppercase text-white">Sistem Informasi Arsip</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 border-b border-white/10 px-5 py-4">
            <div class="size-9 flex items-center justify-center rounded-full bg-white/20 font-bold text-white text-sm shrink-0">{{ auth()->user()->inisial }}</div>
            <div class="text-white text-[13px] font-semibold">
                <div class="text-white text-[13px] font-semibold">{{ auth()->user()->nama_lengkap }}</div>
                <div class="inline-block mt-[3px] rounded-lg bg-white/10 px-1 py-[1px] text-[10px] uppercase tracking-[0.4px] text-white/50">{{ auth()->user()->role_label }}</div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-[14px]">
            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Utama</div>

            <a href="{{ route('dashboard') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/dashboard.png') }}" alt=""> Dashboard
            </a>

            <a href="{{ route('arsip.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/arsip.png') }}" alt=""> Arsip
            </a>

            <a href="{{ route('surat-masuk.create') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/unduh.png') }}" alt="">Unggah Surat Masuk
            </a>

            <a href="{{ route('unggah.create') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('unggah.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/unggah.png') }}" alt="">Unggah Surat Keluar
            </a>

            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Manajemen</div>

            <a href="{{ route('aktivitas.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('aktivitas.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/aktivitas.png') }}" alt=""> Aktivitas
            </a>

            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Sistem</div>

            <a href="{{ route('pengaturan.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/pengaturan.png') }}" alt=""> Pengaturan
            </a>
        </nav>

        <div class="border-t border-white/10 px-5 py-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full cursor-pointer items-center gap-2 rounded-lg bg-transparent px-1 py-2 text-[13px] text-white/50 transition-colors duration-200 [font-family:inherit] border-0 hover:bg-white/[0.08] hover:text-white/80">
                    <img class="w-5 h-5" src="{{ asset('img/keluar.png') }}" alt=""> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-h-screen flex-1 flex-col ml-[260px]">
        <header class="sticky top-0 z-50 flex h-[60px] items-center justify-between border-b border-border bg-surface px-8">
            <div class="flex items-center gap-3" >
                <div class="text-hitam text-sm">
                    Beranda / <span class="text-abu">@yield('breadcrumb', 'Halaman')</span>
                </div>
            </div>

            <form action="{{ route('arsip.index') }}" method="GET" class="flex w-[280px] items-center gap-2 rounded-lg border border-border bg-surface2 px-[14px] py-[7px]">
                <span>
                    <img class="size-[15px] mt-[5px]" src="{{ asset('img/search.png') }}" alt="">
                </span>
                <input class="w-full border-none bg-transparent p-0 text-[13px] text-hitam outline-none focus:ring-0 [font-family:inherit] placeholder:text-abu" type="text" name="q" value="{{ request('q') }}" placeholder="Cari arsip, dokumen…">
            </form>

        <div class="relative" id="notifWrapper">

            <div class="relative flex size-9 cursor-pointer items-center justify-center rounded-lg border border-border bg-surface text-[16px]" id="notifBtn" onclick="toggleNotif()">
                <img class="size-[18px]" src="{{asset('img/notfikasi.png')}}" alt="">
                @php $jmlNotif = \App\Models\Arsip::menunggu()->count(); @endphp
                @if($jmlNotif > 0)
                    <span class="absolute right-[6px] top-[6px] size-[7px] rounded-full border-[1.5px] border-white bg-bawaslu-red"></span>
                @endif
            </div>

            <div id="notifDropdown" class="hidden absolute top-11 right-0 w-85 bg-[#FFFFFF] border border-[#E2DDD8] rounded-xl shadow-[0_8px_32px_rgba(0,0,0,0.12)] z-[999] overflow-hidden">
                    {{-- Header --}}
                    <div class="px-4 py-5 border-b border border-[#E2DDD8] flex justify-between items-center">
                        <div class="text-sm font-bold" >Notifikasi</div>
                        <a href="{{ route('aktivitas.index') }}" class="text-sm text-bawaslu-red font-medium">
                            Lihat semua 
                        </a>
                    </div>

                    {{-- Daftar Notifikasi --}}
                    <div class="max-h-42 overflow-y-auto">

                        {{-- Menunggu Persetujuan --}}
                        @php
                            $user = auth()->user();

                            // Hanya tampilkan arsip menunggu kalau preferensi aktif
                            $arsipMenunggu = collect();
                            if ($user->notif_menunggu_persetujuan) {
                                $arsipMenunggu = \App\Models\Arsip::with(['uploader','divisi'])
                                    ->menunggu()->latest()->take(4)->get();
                            }

                            // Filter aktivitas berdasarkan preferensi yang aktif
                            $aksiFilter = [];
                            if ($user->notif_arsip_baru)      $aksiFilter[] = 'unggah';
                            if ($user->notif_arsip_disetujui) $aksiFilter[] = 'setujui';
                            if ($user->notif_arsip_ditolak)   $aksiFilter[] = 'tolak';
                            if ($user->notif_revisi_dokumen)  $aksiFilter[] = 'revisi';

                            $aktivitasTerbaru = collect();
                            if (!empty($aksiFilter)) {
                                $aktivitasTerbaru = \App\Models\AktivitasLog::with(['user','arsip'])
                                    ->where('user_id', $user->id)
                                    ->whereIn('aksi', $aksiFilter)
                                    ->latest()->take(3)->get();
                            }

                        @endphp

                        @if($arsipMenunggu->count() > 0)

                            <div class="pt-2 pb-1 px-4 text-xs font-bold text-abu uppercase tracking-[.8px]">
                                Menunggu Persetujuan
                            </div>

                            @foreach($arsipMenunggu as $a)
                                <a href="{{ route('arsip.show', $a) }}" class="flex items-start gap-2 px-2 py-4 no-underline text-hitam transition-colors duration-150 " onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='transparent'">
                                    <div class="w-8 h-8 rounded-2xl bg-[#FFFBEB] flex items-center justify-center text-sm shrink-0 mt-[1px]" >⏳</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-semibold overflow-hidden overflow-ellipsis whitespace-nowrap">{{ $a->judul }}</div>
                                        <div class="text-xs text-abu mt-[2px] ">
                                            📤 {{ $a->uploader->nama_lengkap }} · {{ $a->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-bold px-[2px] py-[7px] rounded-[20px] bg-[#FFFBEB] text-[#D97706] flex-shrink-0 mt-1" >Menunggu</span>
                                </a>
                            @endforeach

                        @endif

                        {{-- Aktivitas Terbaru --}}
                        @if($aktivitasTerbaru->count() > 0)
                            <div class="pt-2 px-4 pb-1 text-[10px] font-bold text-abu uppercase tracking-[0.8px] border-t border-solid border-border">
                                Aktivitas Terbaru
                            </div>

                            @foreach($aktivitasTerbaru as $log)
                                <div class="flex items-start gap-2 px-2 py-4">
                                    <div class="w-8 h-8 rounded-full bg-surface2 flex items-center justify-center text-sm shrink-0">
                                        <img src="{{ $log->aksi_ikon }}" width="18" height="18" alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-semibold">{{ $log->aksi_label }}</div>
                                        @if($log->arsip)
                                        <div class="text-xs text-abu mt-[1px] overflow-hidden overflow-ellipsis whitespace-nowrap">
                                            {{ $log->arsip->judul }}
                                        </div>
                                        @endif
                                        <div class="text-xs text-abu mt-[2px]">
                                            {{ $log->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @endif

                        @if($arsipMenunggu->count() === 0 && $aktivitasTerbaru->count() === 0)
                        <div class="px-3 py-xl text-center text-abu text-xs" >
                            <div class="text-3xl mb-[8px]">🎉</div>
                            Tidak ada notifikasi baru
                        </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-3 py-5 border-t border-[#E2DDD8] flex gap-3">
                        <a href="{{ route('aktivitas.index') }}" class="inline-flex cursor-pointer items-center rounded-md border-none px-3 py-[5px] text-xs font-semibold [font-family:inherit] no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border border-border flex-1 text-center justify-center p-2">
                            Semua Aktivitas
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                        <a href="{{ route('persetujuan.index') }}" class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] text-xs justify-center flex-1">
                            Persetujuan ({{ $jmlNotif }})
                        </a>
                        @endif
                    </div>
            </div>
        </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="px-3 py-6 text-xs font-semibold flex items-center gap-2 bg-[#ECFDF5] text-[#059669] border-b border-solid border-[#A7F3D0]">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="px-3 py-6 text-xs font-semibold flex items-center gap-2 bg-[#FEF2F2] text-[#DC2626] border-b border-solid border-[#FECACA]">{{ session('error') }}</div>
        @endif

        <div class="p-8 flex-1">
            @yield('content')
        </div>

    </div>

    @stack('scripts')
        <script>
        function toggleNotif() {
            const dd = document.getElementById('notifDropdown');
            dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notifWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('notifDropdown').style.display = 'none';
            }
        });
        </script>

    @stack('scripts')

</body>
</html>