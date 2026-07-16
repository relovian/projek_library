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
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Nama File <span class="text-bawaslu-red">*</span></label>
                <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                    type="text" name="nama_file"
                    value="{{ old('nama_file', $arsipMasuk->nama_file) }}">
            </div>

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

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tujuan <span class="text-bawaslu-red">*</span></label>
                <select name="tujuan_id"
                    class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]">
                    <option value="">Pilih tujuan…</option>
                    @foreach($tujuans as $tujuan)
                        <option value="{{ $tujuan->id }}" {{ old('tujuan_id', $arsipMasuk->tujuan_id) == $tujuan->id ? 'selected' : '' }}>
                            {{ $tujuan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-[18px]">
                <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Disposisi <span class="text-bawaslu-red">*</span></label>
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
            </div>

            <div class="grid grid-cols-3 gap-4">
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

                <div class="mb-[18px]">
                    <label class="block text-[12.5px] font-bold mb-[7px] text-hitam">Tanggal Unggah <span class="text-bawaslu-red">*</span></label>
                    <input class="w-full px-[13px] py-[9px] border border-border rounded-lg text-[13.5px] [font-family:inherit] bg-surface text-hitam outline-none transition-colors duration-200 focus:border-bawaslu-red focus:shadow-[0_0_0_3px_rgba(192,39,45,.08)]"
                        type="date" name="tanggal_unggah"
                        value="{{ old('tanggal_unggah', $arsipMasuk->tanggal_unggah?->format('Y-m-d')) }}">
                </div>
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
@endsection

@push('scripts')
<script>
    document.getElementById('checkAllHeader')?.addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush