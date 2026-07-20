@extends('layouts.app')
@section('title', 'Tentang')
@section('breadcrumb', 'Tentang')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Tentang SIARSIP Bawaslu</h1>
    <p class="text-[14px] text-abu">Informasi sistem dan tim pengembang</p>
</div>

<div class="max-w-5xl">
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-br from-bawaslu-red to-bawaslu-dark-red rounded-[20px] p-8 mb-6 overflow-hidden">
        <div class="relative flex items-center gap-6">
            <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                 <img src="{{ asset('img/arsip.png') }}" alt="" class="w-10 h-10">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white mb-1">SIARSIP Bawaslu</h2>
                <p class="text-white/80 text-[14px]">Sistem Informasi Arsip Digital - Badan Pengawas Pemilihan Umum RI</p>
            </div>
        </div>
    </div>

    {{-- Tujuan Section --}}
    <div class="bg-surface border border-border rounded-[14px] p-7 mb-6 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#FFFBEB] flex items-center justify-center shrink-0">
                <img src="{{ asset('img/info.png') }}" alt="" class="h-4 w-4">
            </div>
            <div class="flex-1">
                <h2 class="text-[18px] font-bold text-hitam mb-3">Tujuan Sistem</h2>
                <p class="text-[14px] text-abu leading-relaxed">
                    SIARSIP Bawaslu adalah Sistem Informasi Arsip yang dirancang untuk memudahkan pengelolaan, 
                    penyimpanan, dan pencarian dokumen arsip secara digital di lingkungan Badan Pengawas Pemilihan Umum Republik Indonesia (Bawaslu). 
                    Sistem ini bertujuan untuk meningkatkan efisiensi kerja, meminimalisir risiko kehilangan dokumen penting, 
                    serta memfasilitasi akses cepat terhadap arsip surat masuk dan keluar dengan tampilan yang user-friendly 
                    dan navigasi yang intuitif.
                </p>
            </div>
        </div>
    </div>

    {{-- Tim Pengembang Section --}}
    <div class="bg-surface border border-border rounded-[14px] p-7 mb-6 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-lg bg-[#EFF6FF] flex items-center justify-center">
                <img src="{{ asset('img/pengembang.png') }}" alt="" class="h-5 w-5">
            </div>
            <h2 class="text-[18px] font-bold text-hitam">Tim Pengembang</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Team Member 1 --}}
            <div class="group bg-surface2 border border-border rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 border-bawaslu-red/20">
                        <img src="{{ asset('img/Relo.jpeg') }}" alt="Tim IT Bawaslu RI" class="w-full h-full object-cover object-[center_30%]">
                    </div>
                    <h3 class="text-[18px] font-bold text-hitam mb-1">Relovian Rahmadan Santoso Putra</h3>
                    <h3 class="text-[17px] font-bold text-hitam mb-1">SMKN 10 Surabaya</h3>
                    <p class="text-[16px] text-abu">Rekaya Perangkat Lunak</p>
                </div>
            </div>

            {{-- Team Member 2 --}}
            <div class="group bg-surface2 border border-border rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 border-blue-500/20">
                        <img src="{{ asset('img/dimas.jpg') }}" alt="Divisi Pengelolaan Informasi" class="w-full h-full object-cover object-[center_30%]">
                    </div>
                   <h3 class="text-[18px] font-bold text-hitam mb-1">Dimas Satria Prayoga Arbai</h3>
                    <h3 class="text-[17px] font-bold text-hitam mb-1">SMKN 10 Surabaya</h3>
                    <p class="text-[16px] text-abu">Rekaya Perangkat Lunak</p>
                </div>
            </div>

            {{-- Team Member 3 --}}
            <div class="group bg-surface2 border border-border rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 border-emerald-500/20">
                        <img src="{{ asset('img/Vallentino.jpeg') }}" alt="Tim Support System" class="w-full h-full object-cover object-[center_10%]">
                    </div>
                     <h3 class="text-[18px] font-bold text-hitam mb-1">Vallentino Rizky Winoto</h3>
                    <h3 class="text-[17px] font-bold text-hitam mb-1">SMKN 10 Surabaya</h3>
                    <p class="text-[16px] text-abu">Rekaya Perangkat Lunak</p>
                </div>
            </div>

              {{-- Team Member 4 --}}
            <div class="group bg-surface2 border border-border rounded-xl p-5 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 border-emerald-500/20">
                        <img src="{{ asset('img/Alleluia.jpeg') }}" alt="Tim Support System" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=Tim+SS&background=10B981&color=fff&size=64'">
                    </div>
                      <h3 class="text-[18px] font-bold text-hitam mb-1">Alleluia Lefran Wayne</h3>
                    <h3 class="text-[17px] font-bold text-hitam mb-1">SMKN 10 Surabaya</h3>
                    <p class="text-[16px] text-abu">Rekaya Perangkat Lunak</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Asal Instansi Section --}}
    <div class="bg-surface border border-border rounded-[14px] p-7 mb-6 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-lg bg-[#ECFDF5] flex items-center justify-center">
               <img src="{{ asset('img/company1.png') }}" alt="" class="w-5 h-5">
            </div>
            <h2 class="text-[18px] font-bold text-hitam">Asal Instansi</h2>
        </div>
        
        <div class="bg-surface2 border border-border rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-white flex items-center justify-center shrink-0 border border-border">
                     <img src="{{ asset('img/company2.png') }}" alt="" class="w-8 h-8">
                </div>
                <div class="flex-1">
                    <h3 class="text-[15px] font-bold text-hitam mb-2">Badan Pengawas Pemilihan Umum Republik Indonesia (Bawaslu)</h3>
                    <div class="space-y-2 text-[13.5px] text-abu">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-abu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1110.607-10.607 8 8 0 010 10.607z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Jl. Raya Tenggilis Mejoyo No.1, Kali Rungkut, Kec. Rungkut, Surabaya, Jawa Timur 60299</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-abu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>(031) 99857450</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-abu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>set.surabaya@bawaslu.go.id.</span>
                        </div>
                    </div>
            
                </div>
            </div>
        </div>
    </div>

</div>
@endsection