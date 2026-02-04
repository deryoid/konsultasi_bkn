-- Tambahkan kolom nip di tabel user untuk relasi ke tabel pegawai
-- Jalankan query ini di phpMyAdmin atau terminal mysql

ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;

-- Update username yang sudah ada untuk menggunakan NIP dari pegawai (jika ada)
-- Ini opsional, hanya untuk data yang sudah ada

-- Set username sebagai NIP untuk konsistensi
