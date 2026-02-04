-- Tambahkan kolom file_pendukung di tabel pegawai untuk menyimpan nama file SK dan dokumen pendukung lainnya
-- Jalankan query ini di phpMyAdmin atau terminal mysql

ALTER TABLE pegawai ADD COLUMN file_pendukung VARCHAR(255) NULL AFTER jabatan;

-- Opsional: Tambah kolom untuk nama asli file dan tipe file
ALTER TABLE pegawai ADD COLUMN file_pendukung_name VARCHAR(255) NULL AFTER file_pendukung;
