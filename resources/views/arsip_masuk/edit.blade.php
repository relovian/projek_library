@extends('layouts.app')
@section('title', 'Edit Surat Masuk')
@section('breadcrumb', 'Surat Masuk / Edit')

@section('content')
<div class="mb-7">
    <h1 class="font-serif text-[28px] text-hitam mb-1">Edit Surat Masuk</h1>
    <p class="text-[14px] text-abu">Perbarui informasi surat masuk</p>
</div>

<div class="mt-4 mb-5">
    <a href="{{ route('arsip.index', ['tab' => 'masuk']) }}"
        class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border border-border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam">
        Kembali ke Arsip Masuk
    </a>
</div>


<div class="grid grid-cols-[2fr_1fr] gap-6 items-start">
    <div class="bg-surface border border-border rounded-[14px] p-6 mb-[15px]">
        <div class="text-[15px] font-bold mb-5">Form Edit Surat Masuk</div>

         <form method="POST" action="{{ route('arsip-masuk.update', $arsipMasuk) }}">
            @csrf
            @method('PUT')

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Perihal <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                    type="text" name="perihal"
                    value="{{ old('perihal', $arsipMasuk->perihal) }}">
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Asal Instansi <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                    type="text" name="asal_instansi"
                    value="{{ old('asal_instansi', $arsipMasuk->asal_instansi) }}">
            </div>

            {{-- Tujuan (multiple checkbox) --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Tujuan <span class="text-bawaslu-red">*</span>
                </label>

                <div class="flex gap-2 mb-3">
                    <button
                        type="button"
                        id="selectAllTujuanBtn"
                        class="px-3 py-[5px] rounded-[6px] text-[11px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
                        Pilih Semua
                    </button>

                    <button
                        type="button"
                        id="deselectAllTujuanBtn"
                        class="px-3 py-[5px] rounded-[6px] text-[11px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border">
                        Hapus Semua
                    </button>
                </div>

                <div class="border border-border rounded-lg overflow-hidden">
                    <table class="w-full text-[13px]">
                        <thead class="bg-surface2 border-b border-border">
                            <tr>
                                <th class="w-12 px-3 py-2 text-center"></th>
                                <th class="px-3 py-2 text-left">Nama Tujuan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $selectedTujuan = old('tujuan_id', $arsipMasuk->tujuans->pluck('id')->toArray());
                            @endphp
                            @foreach ($tujuans as $tujuan)
                                <tr class="border-b border-border last:border-b-0 hover:bg-surface2/50">
                                    <td class="px-3 py-2 text-center">
                                        <input
                                            type="checkbox"
                                            class="tujuan-checkbox"
                                            name="tujuan_id[]"
                                            value="{{ $tujuan->id }}"
                                            {{ in_array($tujuan->id, $selectedTujuan) ? 'checked' : '' }}>
                                    </td>

                                    <td class="px-3 py-2">
                                        {{ $tujuan->nama }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @error('tujuan_id')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Disposisi (Select Multiple Users) --}}
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
                            @php
                                $selectedDisposisi = old('users_disposisi', $arsipMasuk->usersDisposisi->pluck('id')->toArray());
                            @endphp
                            @forelse($users as $user)
                            <tr class="border-t border-border hover:bg-[#FFF5F5] transition-colors">
                                <td class="px-3 py-2">
                                    <input type="checkbox" name="users_disposisi[]" value="{{ $user->id }}"
                                        class="user-checkbox cursor-pointer"
                                        {{ in_array($user->id, $selectedDisposisi) ? 'checked' : '' }}>
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
                                <td colspan="4" class="px-3 py-4 text-center text-abu text-[13px]">Tidak ada user tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-[11px] text-abu mt-1">Pilih satu atau lebih user sebagai tujuan disposisi surat.</p>
                @error('users_disposisi')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

             <div class="grid grid-cols-2 gap-4">
                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Surat <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="date" name="tanggal_surat"
                        value="{{ old('tanggal_surat', $arsipMasuk->tanggal_surat?->format('Y-m-d')) }}">
                </div>

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Diterima <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="date" name="tanggal_diterima"
                        value="{{ old('tanggal_diterima', $arsipMasuk->tanggal_diterima?->format('Y-m-d')) }}">
                </div>
            </div>

            {{-- Ganti File (Opsional) --}}
            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">
                    Ganti File <span class="text-[11px] text-abu">(Opsional)</span>
                </label>

                <div id="dropZoneEdit"
                    class="border-2 border-dashed border-border rounded-[14px] py-8 text-center cursor-pointer transition-all duration-200 hover:border-bawaslu-red hover:bg-[#FEF2F2] bg-surface2"
                    onclick="document.getElementById('fileInputEdit').click()">
                    <div class="text-3xl mb-2">
                        <img id="folderIconEdit" src="{{ asset('img/folder_kosong.png') }}" class="mx-auto" alt="">
                    </div>
                    <h3 class="text-sm font-bold mb-1">Ganti File</h3>
                    <p class="text-[12px] text-abu">atau klik untuk pilih file baru</p>
                    <p class="mt-1 text-xs text-[#6B6560]" id="fileNameEdit">{{ $arsipMasuk->nama_file }}</p>
                </div>
                <input type="file" id="fileInputEdit" name="file" class="hidden"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                    onchange="handleFileSelectEdit(this)">
                @error('file')
                    <span class="text-[12px] text-[#dc2626] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-[10px] mt-2">
                <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-bawaslu-red px-[18px] py-2 text-[13px] font-semibold text-white no-underline transition-colors duration-200 hover:bg-bawaslu-dark-red [font-family:inherit] flex-1">
                    Simpan Perubahan
                </button>
                <a href="{{ route('arsip.index', ['tab' => 'masuk']) }}" class="px-3 py-[5px] rounded-[6px] text-[12px] font-semibold cursor-pointer border [font-family:inherit] inline-flex items-center no-underline transition-opacity duration-200 hover:opacity-[0.85] bg-surface2 text-hitam border-border px-2 py-4 text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="flex flex-col gap-4">
        <div class="bg-surface2 border border-border rounded-[14px] p-6 mb-[15px]">
            <div class="text-xs font-bold mb-3">Info Arsip</div>
            <div class="text-[12px] text-abu space-y-2">
                <div><span class="font-semibold text-hitam">Kode Arsip:</span> {{ $arsipMasuk->kode_arsip_masuk }}</div>
                <div><span class="font-semibold text-hitam">Link File:</span> 
                    @if($arsipMasuk->link_file)
                    <a href="{{ $arsipMasuk->link_file }}" target="_blank" class="text-bawaslu-red underline">Buka</a>
                    @else
                    -
                    @endif
                </div>
                <div><span class="font-semibold text-hitam">Diupload:</span> {{ $arsipMasuk->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle file select for edit form
    window.handleFileSelectEdit = function (input) {
        const file = input.files[0];
        if (file) {
            const fileNameEl = document.getElementById('fileNameEdit');
            const folderIcon = document.getElementById('folderIconEdit');
            fileNameEl.textContent = file.name;
            folderIcon.src = '{{ asset("img/folder_open.png") }}';
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        // Tujuan checkbox handlers
        const selectAllTujuanBtn = document.getElementById('selectAllTujuanBtn');
        const deselectAllTujuanBtn = document.getElementById('deselectAllTujuanBtn');

        if (selectAllTujuanBtn) {
            selectAllTujuanBtn.addEventListener('click', function () {
                document.querySelectorAll('.tujuan-checkbox').forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            });
        }

        if (deselectAllTujuanBtn) {
            deselectAllTujuanBtn.addEventListener('click', function () {
                document.querySelectorAll('.tujuan-checkbox').forEach(function (checkbox) {
                    checkbox.checked = false;
                });
            });
        }

        // Disposisi checkbox handlers
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        const checkAllHeader = document.getElementById('checkAllHeader');
        const checkboxes = document.querySelectorAll('.user-checkbox');

        function setAll(checked) {
            checkboxes.forEach(cb => {
                cb.checked = checked;
            });

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
    });
</script>
@endpush
@endsection
