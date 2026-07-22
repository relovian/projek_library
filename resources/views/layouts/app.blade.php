<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIARSIP') — Bawaslu Kota Surabaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="text-white text-[13px] font-bold italic">{{ auth()->user()->nama_lengkap }}</div>
            <div class="inline-block mt-[3px] rounded-lg bg-white/10 px-1 py-[1px] text-[10px] uppercase tracking-[0.4px] text-white/50">{{ auth()->user()->role_label }}</div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-[14px]">
        
        <nav class="flex-1 overflow-y-auto px-3 py-[14px]">

            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Utama</div>

            <a href="{{ route('dashboard') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/dashboard.png') }}" alt=""> Dashboard
            </a>

            <a href="{{ route('arsip.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/arsip.png') }}" alt=""> Arsip
            </a>

            @if(!auth()->user()->isKomisioner())
            <a href="{{ route('arsip-masuk.create') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('arsip-masuk.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/unduh.png') }}" alt="">Unggah Arsip Masuk
            </a>

            <a href="{{ route('arsip-keluar.create') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('arsip-keluar.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/unggah.png') }}" alt="">Unggah Arsip Keluar
            </a>
            @endif

            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Manajemen</div>

            @if(!auth()->user()->isKomisioner())
                <a href="{{ route('aktivitas.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('aktivitas.*') ? 'active' : '' }}">
                    <img class="w-4 h-4" src="{{ asset('img/aktivitas.png') }}" alt=""> Aktivitas
                </a>
            @endif


            <div class="mt-2 px-2 pb-[6px] pt-2 text-[9.5px] font-bold uppercase tracking-[1.2px] text-white/35">Sistem</div>

            <a href="{{ route('pengaturan.index') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/pengaturan.png') }}" alt=""> Pengaturan
            </a>

            <a href="{{ route('about') }}" class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2.5 text-[13.5px] font-medium text-white/70 no-underline transition duration-200 mb-0.5 hover:bg-white/10 hover:text-white [&.active]:bg-white/[0.18] [&.active]:text-white [&.active]:font-semibold {{ request()->routeIs('about') ? 'active' : '' }}">
                <img class="w-4 h-4" src="{{ asset('img/about.png') }}" alt="">
                <span>Tentang</span>
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

        <div class="text-center pb-4 text-white/30 text-[11px] font-medium">
            <p>SIArsip v1.5</p></div>
    </aside>

    <div class="flex min-h-screen flex-1 flex-col ml-[260px]">
        <header class="sticky top-0 z-50 flex h-[60px] items-center justify-between border-b border-border bg-surface px-8">
            <div class="flex items-center gap-3" >
                <div class="text-hitam text-sm">
                    Beranda / <span class="text-abu">@yield('breadcrumb', 'Halaman')</span>
                </div>
            </div>

        <div class="relative" id="notifWrapper">

            <div class="relative flex size-9 cursor-pointer items-center justify-center rounded-lg border border-border bg-surface text-[16px]" id="notifBtn" onclick="toggleNotif()">
                <img class="size-[18px]" src="{{asset('img/notfikasi.png')}}" alt="">
                @if($unreadCount > 0)
                    <span class="absolute right-[6px] top-[6px] size-[7px] rounded-full border-[1.5px] border-white bg-bawaslu-red notif-badge-red" id="mainNotifBadge"></span>
                @endif  
            </div>

            <div id="notifDropdown" class="hidden absolute top-11 right-0 w-[360px] bg-white border border-[#E2DDD8] rounded-xl shadow-[0_8px_32px_rgba(0,0,0,0.12)] z-[999] overflow-hidden">
                {{-- Header --}}
                <div class="px-5 py-[18px] border-b border-[#E2DDD8] flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <div class="size-[18px] relative">
                            <img class="size-full" src="{{asset('img/notfikasi.png')}}" alt="">
                            @if($unreadCount > 0)
                                <span class="absolute -top-[2px] -right-[2px] size-2 rounded-full bg-bawaslu-red border border-white notif-badge-red"></span>
                            @endif
                        </div>
                        <span class="text-sm font-bold text-hitam">Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="text-[10px] font-semibold bg-bawaslu-red text-white px-[7px] py-[2px] rounded-full leading-none notif-unread-count">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    @if(!auth()->user()->isKomisioner())
                        <a href="{{ route('aktivitas.index') }}" class="text-xs font-semibold text-bawaslu-red no-underline hover:underline">
                            Lihat semua
                        </a>
                    @endif
                </div>

                {{-- Daftar Notifikasi --}}
                <div class="max-h-[320px] overflow-y-auto divide-y divide-[#F3F2F0]">
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notif)
                            <a href="{{ $notif->link ?? route('aktivitas.index') }}"
                               class="flex items-start gap-3 px-5 py-[14px] no-underline transition-all duration-150
                                      {{ $notif->is_read ? 'bg-white hover:bg-[#FAFAF9]' : 'bg-[#FFF8F7] hover:bg-[#FFF0EE] notif-item-unread' }}">
                                {{-- Icon --}}
                                <div class="size-9 rounded-full flex items-center justify-center shrink-0
                                    {{ $notif->type === 'create' ? 'bg-emerald-50' : ($notif->type === 'update' ? 'bg-blue-50' : ($notif->type === 'restore' ? 'bg-amber-50' : 'bg-red-50')) }}">
                                    @if($notif->type === 'create')
                                        <img class="size-[18px]" src="{{ asset('img/unggah.png') }}" alt="upload">
                                    @elseif($notif->type === 'update')
                                        <img class="size-[18px]" src="{{ asset('img/edit.png') }}" alt="edit">
                                    @elseif($notif->type === 'restore')
                                        <img class="size-[18px]" src="{{ asset('img/pulihkan.png') }}" alt="restore">
                                    @elseif($notif->type === 'force_delete')
                                        <img class="size-[18px]" src="{{ asset('img/aprove_arsip.png') }}" alt="approved">
                                    @elseif($notif->type === 'soft_delete')
                                        <img class="size-[18px]" src="{{ asset('img/pending_arsip.png') }}" alt="pending">
                                    @else
                                        <span class="text-gray-400 text-base">●</span>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-[2px]">
                                        <span class="text-[12.5px] font-bold {{ $notif->is_read ? 'text-hitam' : 'text-bawaslu-red' }} {{ !$notif->is_read ? 'notif-title-unread' : '' }}">
                                            @php
                                                $labelMap = [
                                                    'ArsipMasuk' => 'Arsip Masuk',
                                                    'ArsipKeluar' => 'Arsip Keluar',
                                                    'Arsip' => 'Arsip',
                                                ];
                                                $entityLabel = $labelMap[$notif->entity_type] ?? 'Arsip';
                                            @endphp
                                            @php
                                                $typeLabel = match($notif->type) {
                                                    'create' => $entityLabel . ' Baru',
                                                    'update' => $entityLabel . ' Diperbarui',
                                                    'restore' => $entityLabel . ' Di Tolak Untuk Hapus Permanen',
                                                    'force_delete' => $entityLabel . ' Disetujui Hapus Permanen',
                                                    'soft_delete' => 'Menunggu Hapus Permanen',
                                                    default => $entityLabel . ' Dihapus',
                                                };
                                            @endphp
                                            {{ $typeLabel }}
                                        </span>
                                        @if(!$notif->is_read)
                                            <span class="size-[6px] rounded-full bg-bawaslu-red shrink-0 notif-unread-dot"></span>
                                        @endif
                                    </div>
                                    <div class="text-[12px] text-[#6B7280] leading-snug line-clamp-2">
                                        {{ $notif->message }}
                                    </div>
                                    <div class="text-[11px] text-[#9CA3AF] mt-[3px]">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center py-[40px] px-5">
                            <div class="size-[48px] rounded-full bg-green-50 flex items-center justify-center mb-3">
                                <span class="text-[22px]">✅</span>
                            </div>
                            <div class="text-[13px] font-semibold text-hitam mb-1">Tidak ada notifikasi baru</div>
                            <div class="text-[12px] text-[#6B7280] text-center">Anda akan mendapat notifikasi saat ada aktivitas arsip baru.</div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                @if(!auth()->user()->isKomisioner() && $notifications->count() > 0)
                    <div class="px-5 py-4 border-t border-[#E2DDD8] bg-[#FAFAF9]">
                        <a href="{{ route('aktivitas.index') }}"
                           class="flex items-center justify-center gap-2 w-full rounded-lg border border-[#E2DDD8] bg-white px-4 py-[10px] text-xs font-semibold text-hitam no-underline transition-all duration-150 hover:bg-[#F3F2F0] [font-family:inherit]">
                            <span>Lihat Semua Aktivitas</span>
                            <span class="text-base leading-none">→</span>
                        </a>
                    </div>
                @endif
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

    <script>
        function toggleNotif() {
            const dropdown = document.getElementById('notifDropdown');
            const isOpening = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden');

            // Saat dropdown dibuka, tandai semua notifikasi sebagai sudah dibaca
            if (isOpening) {
                markAllNotificationsRead();
            }
        }

        /**
         * Tandai semua notifikasi sebagai sudah dibaca.
         * Hilangkan badge merah & style unread langsung di frontend.
         */
        function markAllNotificationsRead() {
            // Hilangkan semua badge merah
            document.querySelectorAll('.notif-badge-red').forEach(el => el.remove());
            document.querySelectorAll('.notif-unread-dot').forEach(el => el.remove());
            document.querySelectorAll('.notif-unread-count').forEach(el => el.remove());

            // Hilangkan background merah muda -> putih (cari semua item notifikasi yang belum dibaca)
            document.querySelectorAll('.notif-item-unread').forEach(el => {
                el.classList.remove('bg-[#FFF8F7]');
                el.classList.remove('hover:bg-[#FFF0EE]');
                el.classList.add('bg-white');
                el.classList.add('hover:bg-[#FAFAF9]');
            });

            // Ubah warna teks judul yang merah jadi hitam
            document.querySelectorAll('.notif-title-unread').forEach(el => {
                el.classList.remove('text-bawaslu-red');
                el.classList.add('text-hitam');
            });

            // Panggil API untuk simpan ke database
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            }).catch(function(err) {
                console.error('Gagal mark all read:', err);
            });
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('notifWrapper');
            const dropdown = document.getElementById('notifDropdown');
            if (wrapper && dropdown && !wrapper.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>