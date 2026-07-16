@extends('layouts.app')
@section('title', 'Unggah Arsip Keluar')
@section('breadcrumb', 'Arsip Keluar / Unggah')

@section('content')

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-[14px] p-4">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-[13px] text-red-700">{{ session('error') }}</p>
    </div>
</div>
@endif

<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Unggah Arsip Keluar</h1>
    <p class="text-[14px] text-abu">Tambahkan arsip surat keluar baru ke dalam sistem</p>
</div>

<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">
    {{-- Form Utama --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Form Arsip Keluar</div>

        <form id="formArsipKeluar" method="POST" action="{{ route('arsip-keluar.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Baris 1: Klasifikasi & No Arsip --}}
            <div class="grid grid-cols-1 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Klasifikasi Arsip <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('klasifikasi_id') ? 'border-[#dc2626]' : '' }}" name="klasifikasi_id" id="klasifikasiSelect" onchange="generateKodeArsip()">
                        <option value="">Pilih klasifikasi…</option>
                        @foreach($klasifikasi as $ks)
                        <option value="{{ $ks->id }}" data-singkatan="{{ strtoupper(substr($ks->nama, 0, 2)) }}"
                            {{ old('klasifikasi_id') == $ks->id ? 'selected' : '' }}>
                            {{ $ks->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Baris 2: Sifat & Sub Bagian --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Sifat <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('sifat_id') ? 'border-[#dc2626]' : '' }}" name="sifat_id">
                        <option value="">Pilih sifat…</option>
                        @foreach($sifat as $sf)
                        <option value="{{ $sf->id }}" {{ old('sifat_id') == $sf->id ? 'selected' : '' }}>{{ $sf->nama }}</option>
                        @endforeach
                    </select>
                    @error('sifat_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Sub Bagian <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('sub_bagian_id') ? 'border-[#dc2626]' : '' }}" name="sub_bagian_id">
                        <option value="">Pilih sub bagian…</option>
                        @foreach($subBagian as $sb)
                        <option value="{{ $sb->id }}" {{ old('sub_bagian_id') == $sb->id ? 'selected' : '' }}>{{ $sb->nama }}</option>
                        @endforeach
                    </select>
                    @error('sub_bagian_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Baris 3: Verifikator & Tujuan --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Verifikator <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('verifikator_id') ? 'border-[#dc2626]' : '' }}" name="verifikator_id">
                        <option value="">Pilih verifikator…</option>
                        @foreach($verifikator as $vk)
                        <option value="{{ $vk->id }}" {{ old('verifikator_id') == $vk->id ? 'selected' : '' }}>{{ $vk->user->nama_lengkap ?? $vk->user->name ?? 'Verifikator' }}</option>
                        @endforeach
                    </select>
                    @error('verifikator_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tujuan <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tujuan_id') ? 'border-[#dc2626]' : '' }}" name="tujuan_id">
                        <option value="">Pilih tujuan…</option>
                        @foreach($tujuan as $tn)
                        <option value="{{ $tn->id }}" {{ old('tujuan_id') == $tn->id ? 'selected' : '' }}>{{ $tn->nama }}</option>
                        @endforeach
                    </select>
                    @error('tujuan_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Nama File --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama File <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('nama_file') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="nama_file"
                    value="{{ old('nama_file') }}"
                    placeholder="Masukkan nama file arsip…">
                @error('nama_file')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Perihal --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Perihal <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('perihal') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="perihal"
                    value="{{ old('perihal') }}"
                    placeholder="Masukkan perihal arsip…">
                @error('perihal')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Pembuat --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Pembuat <span class="text-bawaslu-red">*</span></label>
                <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('pembuat_id') ? 'border-[#dc2626]' : '' }}" name="pembuat_id">
                    <option value="">Pilih pembuat…</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('pembuat_id') == $user->id ? 'selected' : '' }}>{{ $user->nama_lengkap }}</option>
                    @endforeach
                </select>
                @error('pembuat_id')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Surat & Tanggal Unggah --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Surat <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_surat') ? 'border-[#dc2626]' : '' }}"
                        type="date" name="tanggal_surat" id="tanggalSurat"
                        value="{{ old('tanggal_surat') }}"
                        onchange="generateKodeArsip()">
                    @error('tanggal_surat')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Unggah <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_unggah') ? 'border-[#dc2626]' : '' }}"
                        type="date" name="tanggal_unggah" id="tanggalUnggah"
                        value="{{ old('tanggal_unggah', date('Y-m-d')) }}"
                        onchange="generateKodeArsip()">
                    @error('tanggal_unggah')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Opsi Upload: File atau Link Google Drive --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Upload File <span class="text-bawaslu-red">*</span>
                </label>

                <div id="uploadFileSection">
                    <div class="border-2 border-dashed border-border rounded-[14px] py-12 text-center cursor-pointer transition-colors duration-200 hover:border-bawaslu-red hover:bg-[#FEF2F2] bg-surface2" onclick="document.getElementById('fileInput').click()">
                        <div class="text-5xl mb-4">
                            <img id="folderIcon" src="{{ asset('img/folder_kosong.png') }}" class="mx-auto" alt="">
                        </div>
                        <h3 class="text-base font-bold mb-1.5">Seret & Jatuhkan File</h3>
                        <p class="text-[13px] text-abu">atau klik untuk pilih file</p>
                        <div class="flex gap-2 justify-center mt-4 flex-wrap">
                            <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">PDF</span>
                            <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">DOCX</span>
                            <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">XLSX</span>
                            <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">JPG</span>
                            <span class="px-2.5 py-[3px] bg-surface border border-border rounded-[6px] text-[11px] font-semibold text-abu">PNG</span>
                        </div>
                        <p class="mt-2 text-xs text-[#6B6560]" id="fileName"></p>
                    </div>
                    <input type="file" id="fileInput" name="file" class="hidden"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                        onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? ''; document.getElementById('folderIcon').src = this.files[0] ? '{{ asset('img/folder_open.png') }}' : '{{ asset('img/folder_kosong.png') }}'">
                    @error('file')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-[10px] mt-2">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    Simpan Arsip Keluar
                </button>
                <a href="{{ route('dashboard') }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    Batal
                </a>
            </div>

        </form>
    </div>

    {{-- Sidebar --}}
    <div class="flex flex-col gap-4">
        <div class="bg-surface2 border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-xs font-bold mt-2">Panduan</div>
            <ul class="text-xs text-abu leading-[1.8] pl-4 mt-2">
                <li>Isi semua field yang bertanda <span class="text-bawaslu-red">*</span></li>
                <li>File akan otomatis masuk ke google drive</li>
                <li>Format file: PDF, DOCX, XLSX, JPG, PNG, MP4</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi generate kode arsip otomatis
    function generateKodeArsip() {
        const klasifikasiSelect = document.getElementById('klasifikasiSelect');
        const tanggalUnggah = document.getElementById('tanggalUnggah').value;
        const kodeInput = document.getElementById('kodeArsip');

        if (klasifikasiSelect.value && tanggalUnggah) {
            const selectedOption = klasifikasiSelect.options[klasifikasiSelect.selectedIndex];
            const singkatan = selectedOption.dataset.singkatan || '';
            const tgl = tanggalUnggah.replace(/-/g, '');
            kodeInput.value = singkatan + tgl + '-???';
        } else {
            kodeInput.value = '';
        }
    }

    // Fungsi toggle upload metode
    function toggleUploadMetode() {
        const fileRadio = document.querySelector('input[name="metode_upload"][value="file"]');
        const linkRadio = document.querySelector('input[name="metode_upload"][value="link"]');
        const fileSection = document.getElementById('uploadFileSection');
        const linkSection = document.getElementById('uploadLinkSection');

        if (fileRadio && fileRadio.checked) {
            fileSection.classList.remove('hidden');
            linkSection.classList.add('hidden');
            document.querySelector('input[name="link_file"]').removeAttribute('required');
        } else if (linkRadio && linkRadio.checked) {
            fileSection.classList.add('hidden');
            linkSection.classList.remove('hidden');
            document.querySelector('input[name="file"]').removeAttribute('required');
        }
    }

    // Fungsi copy link ke clipboard
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Tersalin!';
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-blue-600');
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-green-600');
                }, 2000);
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Link berhasil disalin!');
    }

    // Generate kode saat halaman dimuat jika ada old value
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('klasifikasiSelect').value) {
            generateKodeArsip();
        }
    });
</script>
@endpush