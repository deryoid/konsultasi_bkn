<?php
/**
 * Script Test Email - Konsultasi BKN
 * Jalankan via browser: http://konsultasi_bkn.test/test_email.php
 */

require_once __DIR__ . '/config/email.php';

echo "<h2>🧪 Test Email - Sistem Konsultasi BKN</h2>";

$email_lib = new EmailLibrary();

// Test 1: Notifikasi Status
echo "<h3>Test 1: Notifikasi Perubahan Status</h3>";
$result1 = $email_lib->kirimNotifikasiStatus(
    'bkn.kalsel8@gmail.com',   // email tujuan
    'User Test',                // nama penerima
    'KSL-2024-001',            // ID konsultasi
    'Menunggu',                 // status lama
    'Diproses',                 // status baru
    'Test Konsultasi Kepegawaian' // judul
);
echo $result1 ? "✅ <strong>Berhasil dikirim!</strong>" : "❌ <strong>Gagal dikirim!</strong>";

echo "<br><br>";

// Test 2: Notifikasi Konselor
echo "<h3>Test 2: Notifikasi ke Konselor</h3>";
$result2 = $email_lib->kirimNotifikasiKonselor(
    'bkn.kalsel8@gmail.com',         // email konselor
    'Konselor Test',                  // nama konselor
    'KSL-2024-001',                  // ID konsultasi
    'Pegawai Test',                   // nama pegawai
    'Test Konsultasi Kepegawaian',   // judul
    'Ini adalah deskripsi test konsultasi untuk pengujian sistem email.', // deskripsi
    date('d-m-Y H:i:s')             // tanggal
);
echo $result2 ? "✅ <strong>Berhasil dikirim!</strong>" : "❌ <strong>Gagal dikirim!</strong>";

echo "<br><br>";

// Test 3: Notifikasi Respon
echo "<h3>Test 3: Notifikasi Respon Konselor</h3>";
$result3 = $email_lib->kirimNotifikasiRespon(
    'bkn.kalsel8@gmail.com',        // email tujuan
    'User Test',                     // nama penerima
    'KSL-2024-001',                 // ID konsultasi
    'Konselor Test',                 // nama konselor
    'Ini adalah test respon dari konselor untuk pengujian sistem email.' // isi respon
);
echo $result3 ? "✅ <strong>Berhasil dikirim!</strong>" : "❌ <strong>Gagal dikirim!</strong>";

echo "<br><br><hr>";
echo "<p><small>Config SMTP: smtp.gmail.com:587 | From: bkn.kalsel8@gmail.com</small></p>";
echo "<p><a href='javascript:history.back()'>← Kembali</a></p>";
?>
