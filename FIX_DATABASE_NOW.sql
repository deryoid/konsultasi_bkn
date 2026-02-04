-- =====================================================
-- COPY SEMUA SQL DI BAWAH INI DAN JALANKAN DI PHPMYADMIN
-- atau database client Anda
-- =====================================================

-- 1. Gunakan database dulu
USE konsultasi_bkn;

-- 2. Cek apakah kolom nip sudah ada, jika ada HAPUS dulu
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = 'konsultasi_bkn'
     AND TABLE_NAME = 'user'
     AND COLUMN_NAME = 'nip') > 0,
    'ALTER TABLE user DROP COLUMN nip',
    'SELECT "Kolom nip belum ada, OK" AS status'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3. Tambahkan kolom nip
ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;

-- 4. Update data user yang sudah ada
UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL;

-- 5. Verifikasi - lihat struktur tabel user
DESCRIBE user;

-- 6. Verifikasi - lihat data user
SELECT id_user, nama_user, username, nip, role FROM user;

-- =====================================================
-- SELESAI!
-- Sekarang coba daftar lagi di register.php
-- =====================================================
