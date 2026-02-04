<?php
// Script untuk menambahkan kolom nip ke tabel user
require 'config/koneksi.php';

// Cek apakah kolom nip sudah ada
$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM user LIKE 'nip'");

if (mysqli_num_rows($check_column) > 0) {
    echo "✅ Kolom 'nip' sudah ada di tabel user!<br>";
} else {
    // Tambahkan kolom nip
    $alter = mysqli_query($koneksi, "ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username");

    if ($alter) {
        echo "✅ Berhasil menambahkan kolom 'nip' ke tabel user!<br>";

        // Update data user yang sudah ada dengan NIP dari username (jika username adalah NIP)
        $update = mysqli_query($koneksi, "UPDATE user SET nip = username WHERE role = 'User'");
        if ($update) {
            $affected = mysqli_affected_rows($koneksi);
            echo "✅ Berhasil mengupdate $affected data user dengan NIP!<br>";
        }
    } else {
        echo "❌ Gagal menambahkan kolom 'nip': " . mysqli_error($koneksi) . "<br>";
    }
}

// Tampilkan link untuk kembali
echo "<br><a href='index.php'>Kembali ke Halaman Utama</a>";
?>
