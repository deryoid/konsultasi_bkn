# Panduan Instalasi & Perbaikan Sistem Konsultasi Pegawai

## 📋 Daftar Perbaikan yang Dilakukan:

### 1. ✅ Perbaikan Error SQL di Dashboard User
**File:** `user/index.php` baris 185-187

**Masalah:**
```sql
-- ❌ ERROR: Alias 'k' digunakan untuk 2 tabel
SELECT k.*, k.nama_kategori
FROM konsultasi k
LEFT JOIN kategori k ON k.id_kategori = k.id_kategori
```

**Perbaikan:**
```sql
-- ✅ BENAR: Alias berbeda untuk setiap tabel
SELECT k.*, kat.nama_kategori
FROM konsultasi k
LEFT JOIN kategori kat ON k.id_kategori = kat.id_kategori
```

---

## 🚀 Cara Instalasi Database:

### Opsi 1: Menggunakan Script PHP (Rekomendasi)

1. Buka browser dan jalankan:
   ```
   http://localhost:8000/install_nip_column.php
   ```

2. Script akan otomatis:
   - Cek kolom `nip` di tabel user
   - Menambahkan kolom jika belum ada
   - Update data user yang sudah ada

### Opsi 2: Manual via phpMyAdmin

1. Buka phpMyAdmin
2. Pilih database `konsultasi_bkn`
3. Klik tab SQL
4. Jalankan query:

```sql
-- Tambahkan kolom nip
ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;

-- Update data yang sudah ada (opsional)
UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL;
```

---

## 🧪 Testing Sistem:

### 1. Test PHP Syntax (Semua File ✅)

```bash
# Cek semua file baru
php -l user/index.php
php -l user/konsultasi/tambah.php
php -l user/konsultasi/index.php
php -l user/konsultasi/detail.php
php -l user/konsultasi/cetak.php
php -l register.php
php -l login.php
```

**Hasil:** Tidak ada syntax error

### 2. Test Pendaftaran User

1. Buka: `http://localhost:8000/register.php`
2. Masukkan NIK: `6371021310920003`
3. Buat password
4. Login dengan NIP: `199210132025061003`

### 3. Test Konsultasi

1. Setelah login, buka dashboard
2. Klik "Buat Konsultasi"
3. Isi form dan kirim
4. Cek daftar konsultasi

---

## ⚠️ Error yang Mungkin Terjadi & Solusi:

### Error 1: "Unknown column 'nip' in 'field list'"

**Penyebab:** Kolom `nip` belum ditambahkan ke tabel user

**Solusi:** Jalankan `install_nip_column.php`

---

### Error 2: "Undefined index: nama_lengkap"

**Penyebab:** Session belum terisi dengan data pegawai

**Solusi:** Logout dan login kembali

---

### Error 3: "SQL Error: Duplicate entry"

**Penyebab:** NIP sudah terdaftar

**Solusi:** Gunakan NIP yang belum terdaftar

---

### Error 4: "Table doesn't exist: konsultasi"

**Penyebab:** Database belum di-import

**Solusi:** Import file `db/konsultasi_bkn.sql`

---

## 📁 Struktur File yang Diperbaiki:

```
✅ user/index.php - Perbaiki query SQL (alias conflict)
✅ user/konsultasi/ - Semua file sudah dicek syntax
✅ login.php - Update untuk session pegawai
✅ register.php - Update untuk menyimpan NIP
✅ install_nip_column.php - Script instalasi database (BARU)
```

---

## 🔧 Checklist Sebelum Menggunakan:

- [ ] Import database `konsultasi_bkn.sql`
- [ ] Jalankan `install_nip_column.php`
- [ ] Pastikan data pegawai ada di tabel `pegawai`
- [ ] Pastikan data kategori ada di tabel `kategori`
- [ ] Test register dengan NIK yang valid
- [ ] Test login dengan NIP dan password
- [ ] Test buat konsultasi baru

---

## 📞 Jika Masih Ada Error:

Jika masih menemui error:

1. Cek error message yang muncul
2. Buka browser console (F12) untuk lihat JavaScript error
3. Cek PHP error log
4. Pastikan semua file sudah di-upload dengan benar
5. Pastikan permission folder sudah benar (755 untuk folder, 644 untuk file)

---

## ✨ Fitur yang Sudah Berjalan:

- ✅ Pendaftaran user berdasarkan NIK
- ✅ Login dengan NIP dan password
- ✅ Dashboard dengan statistik konsultasi
- ✅ Form buat konsultasi
- ✅ Daftar konsultasi dengan DataTables
- ✅ Detail konsultasi dengan respon
- ✅ Cetak tiket konsultasi
- ✅ Menu sidebar yang terorganisir

---

## 🎯 Next Steps:

1. Jalankan `install_nip_column.php` untuk update database
2. Test pendaftaran user baru
3. Test pembuatan konsultasi
4. Verifikasi semua fitur berjalan normal
