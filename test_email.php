<?php
/**
 * Test Email
 * File ini untuk testing pengiriman email
 *
 * CARA MENGGUNAKAN:
 * 1. Pastikan sudah konfigurasi config/smtp_config.php
 * 2. Buka browser: http://localhost/konsultasi_bkn/test_email.php
 * 3. atau jalankan via terminal: php test_email.php
 */

require 'config/config.php';
require 'config/koneksi.php';
require 'config/email.php';

// Buat instance EmailLibrary
$emailLib = new EmailLibrary();

echo "====================================\n";
echo "   TEST EMAIL NOTIFIKASI\n";
echo "====================================\n\n";

// Test 1: Kirim notifikasi status
echo "Test 1: Kirim Notifikasi Status\n";
echo "--------------------------------\n";

$test1 = $emailLib->kirimNotifikasiStatus(
    'test@example.com',  // Ganti dengan email tujuan
    'Hajji Sirajuddin',
    'KNS00001',
    'Menunggu',
    'Diproses',
    'Pertanyaan tentang Kenaikan Pangkat'
);

if ($test1) {
    echo "✅ Notifikasi status BERHASIL dikirim!\n";
} else {
    echo "❌ Gagal mengirim notifikasi status.\n";
    echo "   Cek error log untuk detail.\n";
}

echo "\n";

// Test 2: Kirim notifikasi respon
echo "Test 2: Kirim Notifikasi Respon\n";
echo "--------------------------------\n";

$test2 = $emailLib->kirimNotifikasiRespon(
    'test@example.com',  // Ganti dengan email tujuan
    'Hajji Sirajuddin',
    'KNS00001',
    'Dr. Budi Santoso',
    'Terima kasih atas pertanyaan Anda. Mengenai kenaikan pangkat, silakan melampirkan SK terakhir.'
);

if ($test2) {
    echo "✅ Notifikasi respon BERHASIL dikirim!\n";
} else {
    echo "❌ Gagal mengirim notifikasi respon.\n";
    echo "   Cek error log untuk detail.\n";
}

echo "\n";
echo "====================================\n";
echo "   TEST SELESAI\n";
echo "====================================\n\n";

echo "CATATAN:\n";
echo "- Jika gagal, pastikan konfigurasi SMTP sudah benar\n";
echo "- Cek file: config/smtp_config.php\n";
echo "- Error log tersimpan di: error_log()\n\n";

echo "KONFIGURASI SMTP:\n";
echo "Host: smtp.gmail.com\n";
echo "Port: 587 (TLS)\n";
echo "Username: email@gmail.com\n";
echo "Password: App Password (16 digit)\n\n";

// Tampilkan konfigurasi saat ini
if (file_exists('config/smtp_config.php')) {
    $config = require 'config/smtp_config.php';
    echo "KONFIGURASI SAAT INI:\n";
    echo "Email: " . $config['username'] . "\n";
    echo "From: " . $config['from_email'] . "\n";
    echo "Encryption: " . $config['encryption'] . "\n";
}
?>
