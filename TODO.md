# TODO

- [ ] Update DashboardController stats:
    - total_arsip_masuk dari tabel `surat_masuk`
    - total_arsip_keluar dari tabel `arsip_keluar`
    - total_arsip_semua = total masuk + keluar
- [ ] Update `resources/views/dashboard/index.blade.php` cards:
    - Card 1 => Total Arsip Masuk
    - Card 2 => Total Arsip Keluar
    - Card 3 => Total Semua Arsip
- [ ] Pastikan ikon/teks tetap konsisten dengan style yang ada
- [ ] (Opsional) Pastikan query tidak bentrok dengan existing variabel `stats['total_arsip']/['menunggu']/'user_aktif'` (bila masih dipakai komponen lain)
