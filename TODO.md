# TODO: Implementasi Drag & Drop Upload + Preserve Form Data on Refresh

## Step 1: Edit `arsip_masuk/create.blade.php`

- [x] Tambahkan event listener drag & drop (dragenter, dragover, dragleave, drop) pada area upload
- [x] Tambahkan visual highlight saat drag over
- [x] Tambahkan sessionStorage untuk menyimpan data form (termasuk nama file) saat refresh
- [x] Tambahkan script restore data pada DOMContentLoaded

## Step 2: Edit `arsip_keluar/create.blade.php`

- [x] Tambahkan event listener drag & drop (dragenter, dragover, dragleave, drop) pada area upload
- [x] Tambahkan visual highlight saat drag over
- [x] Tambahkan sessionStorage untuk menyimpan data form (termasuk nama file) saat refresh
- [x] Tambahkan script restore data pada DOMContentLoaded

## Testing

- [x] Verifikasi drag & drop berfungsi
- [x] Verifikasi preserve data setelah refresh
