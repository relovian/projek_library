# Progress Tasks

## [x] 1. Chart Dashboard - Tujuan → Arsip Masuk
- [x] Tambah relasi `arsipMasuk()` many-to-many di model Tujuan
- [x] Ubah query `$tujuanStats` di DashboardController dari `arsipKeluar` ke `arsipMasuk`
- [x] Hapus relasi usang `arsipKeluar()` dari model Tujuan (sudah tidak ada kolom tujuan_id di arsip_keluar)
- [x] Hapus `'tujuan'` dari eager load di `ArsipKeluarController@trash()` (menyebabkan error)

## [x] 2. Fitur Backup - Struktur Folder per Tanggal
- [x] Buat helper `getDailyBackupDir()` → `backups/Y/m/d/`
- [x] Buat helper `formatSize()` untuk format ukuran file
- [x] Buat helper `scanBackups()` untuk scan rekursif folder backup
- [x] Update `backupDatabase()` → simpan ke `backups/Y/m/d/backup_db_*.sql`
- [x] Update `backupFiles()` → simpan ke `backups/Y/m/d/backup_files_*.zip`, include `public/img/`
- [x] Update `downloadBackup($path)` → dukung path bersarang dengan `where('path', '.*')`
- [x] Update `destroyBackup($path)` → dukung path bersarang
- [x] Update route: `download/{path}` dan `destroy/{path}` dengan `where('path', '.*')`
- [x] Update view backup → tampilkan header folder per tanggal, gunakan `$backup['path']` untuk route
