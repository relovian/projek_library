# TODO Status

## ✅ Completed

1. **Fixed `PengaturanController@updateUser`** - Mengganti `delete()` pada data verifikator menjadi `update(['is_aktif' => 0])` untuk menghindari error foreign key constraint dari tabel `arsip_keluar`.

2. **Added route `lihat` for ArsipMasuk** - `GET /arsip-masuk/{arsipMasuk}/lihat` → `ArsipMasukController@lihat`

3. **Added route `lihat` for ArsipKeluar** - `GET /arsip-keluar/{arsipKeluar}/lihat` → `ArsipKeluarController@lihat`

## 🚀 Yang akan datang

- Implementasi method `lihat()` di ArsipMasukController dan ArsipKeluarController
- Update views untuk menambahkan tombol "Lihat" yang mengarah ke route `lihat`
