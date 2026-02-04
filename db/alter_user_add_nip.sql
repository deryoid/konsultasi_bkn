-- =====================================================
-- INSTALASI DATABASE KONSULTASI BKN
-- Jalankan script ini di phpMyAdmin atau Database Client
-- =====================================================

-- 1. Cek dan tambahkan kolom nip ke tabel user
-- -----------------------------------------------------

-- Hapus kolom nip jika ada (untuk menghindari error duplicate)
ALTER TABLE user DROP COLUMN IF EXISTS nip;

-- Tambahkan kolom nip
ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;

-- 2. Update data user yang sudah ada
-- -----------------------------------------------------
-- Update user dengan role 'User' untuk mengambil NIP dari username
UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL;

-- 3. Verifikasi struktur tabel
-- -----------------------------------------------------
-- Jalankan query ini untuk melihat hasil:
-- DESCRIBE user;
-- SELECT * FROM user;

-- =====================================================
-- SELESAI - Kolom nip sudah ditambahkan
-- =====================================================
