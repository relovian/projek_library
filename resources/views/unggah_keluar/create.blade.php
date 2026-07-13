@extends('layouts.app')
@section('title', 'Unggah Arsip Keluar')
@section('breadcrumb', 'Arsip Keluar / Unggah')

@section('content')
{{-- Notifikasi Sukses Upload --}}
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-[14px] p-5">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="text-[14px] font-bold text-green-800 mb-1">Berhasil!</h3>
            <p class="text-[13px] text-green-700 mb-2">{{ session('success') }}</p>
            
            @if(session('kode_arsip'))
            <p class="text-[12px] text-green-600 mb-1">
                <span class="font-semibold">Kode Arsip:</span> {{ session('kode_arsip') }}
            </p>
            @endif
            
            @if(session('drive_link'))
            <div class="mt-2 flex items-center gap-2 bg-white rounded-lg p-3 border border-green-200">
                <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] text-green-600 font-semibold">Link Google Drive</p>
                    <a href="{{ session('drive_link') }}" target="_blank" 
                       class="text-[12px] text-blue-600 hover:text-blue-800 underline break-all">
                        {{ session('drive_link') }}
                    </a>
                </div>
                <button onclick="copyToClipboard('{{ session('drive_link') }}')" 
                        class="flex-shrink-0 px-3 py-1.5 rounded-lg bg-green-600 text-white text-[11px] font-semibold hover:bg-green-700 transition-colors [font-family:inherit] cursor-pointer">
                    Salin Link
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

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
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Klasifikasi Arsip <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('klasifikasi_id') ? 'border-[#dc2626]' : '' }}" name="klasifikasi_id" id="klasifikasiSelect" onchange="generateKodeArsip()">
                        <option value="">Pilih klasifikasi…</option>
                        @foreach($klasifikasis as $k)
                        <option value="{{ $k->id }}" data-singkatan="{{ strtoupper(substr($k->nama, 0, 2)) }}"
                            {{ old('klasifikasi_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">No. Arsip <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] bg-surface2 text-abu"
                        type="text" id="kodeArsip" name="kode_arsip" readonly
                        value="{{ old('kode_arsip') }}"
                        placeholder="Otomatis tergenerate">
                </div>
            </div>

            {{-- Baris 2: Sifat & Sub Bagian --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Sifat <span class="text-bawaslu-red">*</span></label>
                    <select class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('sifat_id') ? 'border-[#dc2626]' : '' }}" name="sifat_id">
                        <option value="">Pilih sifat…</option>
                        @foreach($sifats as $s)
                        <option value="{{ $s->id }}" {{ old('sifat_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
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
                        @foreach($subBagians as $sb)
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
                        @foreach($verifikators as $v)
                        <option value="{{ $v->id }}" {{ old('verifikator_id') == $v->id ? 'selected' : '' }}>{{ $v->user->nama_lengkap ?? $v->user->name ?? 'Verifikator' }}</option>
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
                        @foreach($tujuans as $t)
                        <option value="{{ $t->id }}" {{ old('tujuan_id') == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
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
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('pembuat_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
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

                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="metode_upload" value="file" 
                               {{ old('metode_upload', 'file') === 'file' ? 'checked' : '' }}
                               onchange="toggleUploadMetode()"
                               class="cursor-pointer">
                        <span class="text-[13px] font-medium text-hitam">Upload File</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="metode_upload" value="link"
                               {{ old('metode_upload') === 'link' ? 'checked' : '' }}
                               onchange="toggleUploadMetode()"
                               class="cursor-pointer">
                        <span class="text-[13px] font-medium text-hitam">Link Google Drive</span>
                    </label>
                </div>

                <div id="uploadFileSection" class="{{ old('metode_upload') === 'link' ? 'hidden' : '' }}">
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
                        <p class="mt-2 text-xs text-[#6B6560]" id="fileName">Maks. 50 MB per file</p>
                    </div>
                    <input type="file" id="fileInput" name="file" class="hidden"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                        onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? 'Maks. 50 MB per file'; document.getElementById('folderIcon').src = this.files[0] ? '{{ asset('img/folder_open.png') }}' : '{{ asset('img/folder_kosong.png') }}'">
                    <p class="text-[11px] text-abu mt-1">File akan otomatis diupload ke Google Drive.</p>
                    @error('file')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div id="uploadLinkSection" class="{{ old('metode_upload') === 'link' ? '' : 'hidden' }}">
                    <div class="border border-border rounded-[14px] p-5 bg-surface2">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                            <p class="text-[13px] font-semibold text-hitam">Masukkan Link Google Drive</p>
                        </div>
                        <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('link_file') ? 'border-[#dc2626]' : '' }}"
                            type="url" name="link_file"
                            value="{{ old('link_file') }}"
                            placeholder="https://drive.google.com/file/d/...">
                        <p class="text-[11px] text-abu mt-1">Tempel link Google Drive yang sudah dishare publik.</p>
                        @error('link_file')
                            <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
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
                <li>No. Arsip otomatis tergenerate dari klasifikasi + tanggal</li>
                <li>File akan otomatis diupload ke Google Drive</li>
                <li>Link akan otomatis tersimpan di database</li>
                <li>Format file: PDF, DOCX, XLSX, JPG, PNG (maks. 50 MB)</li>
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