@extends('layouts.app')
@section('title', 'Tambah Surat Masuk')
@section('breadcrumb', 'Surat Masuk / Tambah')

@section('content')
{{-- Notifikasi Sukses Upload + Link Google Drive --}}
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
    <h1 class="font-serif text-[28px] text-hitam mb-1">Tambah Surat Masuk</h1>
    <p class="text-[14px] text-abu">Tambahkan surat masuk baru ke dalam sistem</p>
</div>

<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">
    {{-- Form Utama --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Form Surat Masuk</div>

        <form id="formSuratMasuk" method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Nama File --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama File <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('nama_file') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="nama_file"
                    value="{{ old('nama_file') }}"
                    placeholder="Masukkan nama file surat…">
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
                    placeholder="Masukkan perihal surat…">
                @error('perihal')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Asal Instansi --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Asal Instansi <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('asal_instansi') ? 'border-[#dc2626]' : '' }}"
                    type="text" name="asal_instansi"
                    value="{{ old('asal_instansi') }}"
                    placeholder="Masukkan asal instansi pengirim…">
                @error('asal_instansi')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tujuan / Disposisi (Select Multiple Users) --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Tujuan / Disposisi <span class="text-bawaslu-red">*</span>
                </label>

                <div class="flex gap-2 mb-3">
                    <button type="button" id="selectAllBtn" class="px-3 py-[5px] rounded-[6px] text-[11px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
                        Pilih Semua
                    </button>
                    <button type="button" id="deselectAllBtn" class="px-3 py-[5px] rounded-[6px] text-[11px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
                        Hapus Semua
                    </button>
                </div>

                <div class="border border-border rounded-lg overflow-hidden max-h-[300px] overflow-y-auto">
                    <table class="w-full text-[12.5px]">
                        <thead class="bg-surface2 sticky top-0">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold text-hitam w-10">
                                    <input type="checkbox" id="checkAllHeader" class="cursor-pointer">
                                </th>
                                <th class="text-left px-2 py-2 font-semibold text-hitam">Nama</th>
                                <th class="text-left px-2 py-2 font-semibold text-hitam">Role</th>
                                <th class="text-left px-2 py-2 font-semibold text-hitam">Divisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-t border-border hover:bg-[#FFF5F5] transition-colors">
                                <td class="px-3 py-2">
                                    <input type="checkbox" name="users_disposisi[]" value="{{ $user->id }}"
                                        class="user-checkbox cursor-pointer"
                                        {{ in_array($user->id, old('users_disposisi', [])) ? 'checked' : '' }}>
                                </td>
                                <td class="px-2 py-2 text-hitam font-medium">{{ $user->nama_lengkap }}</td>
                                <td class="px-2 py-2 text-abu">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                        @if($user->role === 'admin') bg-blue-100 text-blue-700
                                        @elseif($user->role === 'pimpinan') bg-purple-100 text-purple-700
                                        @elseif($user->role === 'kepala_sekretariat') bg-green-100 text-green-700
                                        @elseif($user->role === 'kepala_sub_bagian') bg-yellow-100 text-yellow-700
                                        @elseif($user->role === 'komisioner') bg-indigo-100 text-indigo-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $user->role_label }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-abu">{{ $user->divisi->nama ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-abu text-[13px]">
                                    Tidak ada user tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-[11px] text-abu mt-1">Pilih satu atau lebih user sebagai tujuan disposisi surat.</p>
                @error('users_disposisi')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
                @error('users_disposisi.*')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Surat --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Surat <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_surat') ? 'border-[#dc2626]' : '' }}"
                    type="date" name="tanggal_surat"
                    value="{{ old('tanggal_surat') }}">
                @error('tanggal_surat')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Diterima --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Diterima <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_diterima') ? 'border-[#dc2626]' : '' }}"
                    type="date" name="tanggal_diterima"
                    value="{{ old('tanggal_diterima') }}">
                @error('tanggal_diterima')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Unggah --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Unggah <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tanggal_unggah') ? 'border-[#dc2626]' : '' }}"
                    type="date" name="tanggal_unggah"
                    value="{{ old('tanggal_unggah') }}">
                @error('tanggal_unggah')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Opsi Upload: File atau Link Google Drive --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Upload File <span class="text-bawaslu-red">*</span>
                </label>

                {{-- Pilihan metode --}}
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

                {{-- Upload File --}}
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
                                <p class="text-[11px] text-abu mt-1">File akan disimpan di server lokal.</p>
                    @error('file')
                        <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Link Google Drive --}}
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
            
            @isset($surat)
                <a href="{{ $surat->link_file }}" target="_blank" class="btn btn-primary">
                    Lihat Dokumen
                </a>
            @endisset

            {{-- Tombol Aksi --}}
            <div class="flex gap-[10px] mt-2">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    Simpan Surat Masuk
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
                <li>Pilih minimal satu tujuan/disposisi surat</li>
                <li>File akan disimpan di server lokal terlebih dahulu</li>
                <li>Google Drive akan dicoba secara otomatis (opsional)</li>
                <li>Format file: PDF, DOCX, XLSX, JPG, PNG </li>
            </ul>
        </div>
    </div>
</div>
@endsection

