<?php
// Script untuk menambahkan kolom nip ke tabel user
require 'config/koneksi.php';

echo "<h2>Instalasi Database - Menambahkan Kolom NIP</h2>";
echo "<hr>";

// Cek apakah kolom nip sudah ada
$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM user LIKE 'nip'");

if (mysqli_num_rows($check_column) > 0) {
    echo "<div style='color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ Berhasil!</strong> Kolom 'nip' sudah ada di tabel user.<br>";
    echo "</div>";
} else {
    // Tambahkan kolom nip
    echo "<div style='color: #856404; padding: 10px; background: #fff3cd; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>⚠️ Proses...</strong> Menambahkan kolom 'nip' ke tabel user...<br>";
    echo "</div>";

    $alter = mysqli_query($koneksi, "ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username");

    if ($alter) {
        echo "<div style='color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>✅ Berhasil!</strong> Kolom 'nip' berhasil ditambahkan!<br>";
        echo "</div>";

        // Update data user yang sudah ada dengan NIP dari username (jika username adalah NIP)
        $update = mysqli_query($koneksi, "UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL");
        if ($update) {
            $affected = mysqli_affected_rows($koneksi);
            if ($affected > 0) {
                echo "<div style='color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0;'>";
                echo "<strong>✅ Berhasil!</strong> $affected data user telah diupdate dengan NIP.<br>";
                echo "</div>";
            }
        }

        echo "<script>setTimeout(function(){ window.location.href = 'register.php'; }, 2000);</script>";
        echo "<p>🔄 Redirecting to register page...</p>";
    } else {
        echo "<div style='color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>❌ Gagal!</strong> " . mysqli_error($koneksi) . "<br>";
        echo "</div>";
    }
}

// Tampilkan informasi database
echo "<hr>";
echo "<h3>Informasi Database:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Kolom</th><th>Tipe</th><th>Status</th></tr>";

$result = mysqli_query($koneksi, "DESCRIBE user");
while ($row = mysqli_fetch_array($result)) {
    $status = '';
    if ($row['Field'] == 'nip') {
        $status = '✅ (Baru)';
    }
    echo "<tr>";
    echo "<td><strong>" . $row['Field'] . "</strong></td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "</table>";

// Tampilkan link untuk kembali
echo "<hr>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Halaman Utama</a></p>";
echo "<p><a href='register.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Daftar Akun Baru</a></p>";
?>
