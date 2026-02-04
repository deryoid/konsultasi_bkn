<?php
/**
 * Konfigurasi SMTP untuk Email
 *
 * PANDUAN SETUP:
 * 1. Buat App Password Gmail: https://myaccount.google.com/apppasswords
 * 2. Pastikan 2-Factor Authentication aktif
 * 3. Pilih: Mail > Windows Computer > Generate
 * 4. Copy password 16 digit dan paste di bawah
 */

return [
    // Host SMTP
    'host' => 'smtp.gmail.com',

    // Port SMTP (587 untuk TLS, 465 untuk SSL)
    'port' => 587,

    // Email Gmail untuk pengirim
    'username' => 'bkn.kalsel8@gmail.com',  // GANTI DENGAN EMAIL ANDA

    // App Password Gmail (16 digit, bukan password utama)
    'password' => 'zezx hfzd kban wibm',      // GANTI DENGAN APP PASSWORD ANDA

    // Email pengirim yang tampil di inbox penerima
    'from_email' => 'noreply@bkn.go.id',

    // Nama pengirim yang tampil di inbox penerima
    'from_name' => 'Sistem Konsultasi BKN',

    // Enkripsi: 'tls' atau 'ssl'
    'encryption' => 'tls'
];
