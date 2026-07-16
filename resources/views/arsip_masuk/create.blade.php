@extends('layouts.app')
@section('title', 'Tambah Surat Masuk')
@section('breadcrumb', 'Surat Masuk / Tambah')

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
    <h1 class="font-serif text-[28px] text-hitam mb-1">Tambah Arsip Masuk</h1>
    <p class="text-[14px] text-abu">Tambahkan surat masuk baru ke dalam sistem</p>
</div>

<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">
    {{-- Form Utama --}}
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Form Arsip Masuk</div>

        <form id="formSuratMasuk" method="POST" action="{{ route('arsip-masuk.store') }}" enctype="multipart/form-data">
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

            {{-- Tujuan (single select wajib) --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Tujuan <span class="text-bawaslu-red">*</span>
                </label>

                <select name="tujuan_id"
                    class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)] {{ $errors->has('tujuan_id') ? 'border-[#dc2626]' : '' }}">
                    <option value="" disabled {{ old('tujuan_id') ? '' : 'selected' }}>Pilih tujuan…</option>
                    @foreach($tujuans as $tujuan)
                        <option value="{{ $tujuan->id }}" {{ (string) old('tujuan_id') === (string) $tujuan->id ? 'selected' : '' }}>
                            {{ $tujuan->nama }}
                        </option>
                    @endforeach
                </select>

                @error('tujuan_id')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tujuan / Disposisi (Select Multiple Users) --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Disposisi <span class="text-bawaslu-red">*</span>
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
                                <td class="px-2 py-2 text-hitam font-medium">{{ $user->nama_panggilan ?? $user->nama_lengkap }}</td>
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

                {{-- Upload File --}}
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
                <a href="{{ route('dashboard') }}" class="rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    Batal
                </a>
            </div>

        </form>

        {{-- JS Disposisi: pilih semua / hapus semua / check all header --}}
        <script>
            (function () {
                const selectAllBtn = document.getElementById('selectAllBtn');
                const deselectAllBtn = document.getElementById('deselectAllBtn');
                const checkAllHeader = document.getElementById('checkAllHeader');
                const checkboxes = document.querySelectorAll('.user-checkbox');

                function setAll(checked) {
                    checkboxes.forEach(cb => {
                        cb.checked = checked;
                    });

                    // Update header checkbox
                    if (checkAllHeader) {
                        checkAllHeader.checked = checked;
                        checkAllHeader.indeterminate = false;
                    }
                }

                function updateHeader() {
                    if (!checkAllHeader) return;
                    const total = checkboxes.length;
                    const selected = Array.from(checkboxes).filter(cb => cb.checked).length;

                    checkAllHeader.checked = total > 0 && selected === total;
                    checkAllHeader.indeterminate = selected > 0 && selected < total;
                }

                if (selectAllBtn) {
                    selectAllBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        setAll(true);
                    });
                }

                if (deselectAllBtn) {
                    deselectAllBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        setAll(false);
                    });
                }

                if (checkAllHeader) {
                    checkAllHeader.addEventListener('change', function (e) {
                        setAll(e.target.checked);
                    });
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', updateHeader);
                });

                // initial
                updateHeader();
            })();
        </script>
    </div>

    {{-- Sidebar --}}
    <div class="flex flex-col gap-4">

        <div class="bg-surface2 border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-xs font-bold mt-2">Panduan</div>
            <ul class="text-xs text-abu leading-[1.8] pl-4 mt-2">
                <li>Isi semua field yang bertanda <span class="text-bawaslu-red">*</span></li>
                <li>Pilih minimal satu disposisi surat</li>
                <li>FIle akan otomatis masuk ke google drive</li>
                <li>Format file: PDF, DOCX, XLSX, JPG, PNG, MP4</li>
            </ul>
        </div>
    </div>
</div>
@endsection

