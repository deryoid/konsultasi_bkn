# Konfigurasi Email Gateway

## Pendahuluan
Sistem ini memiliki fitur notifikasi email otomatis yang akan mengirim email kepada pegawai ketika:
1. Admin mengubah status konsultasi
2. Konselor memberikan respon pada konsultasi

## Metode Pengiriman Email

### 1. Menggunakan PHP mail() (Default)
Secara default, sistem menggunakan fungsi `mail()` dari PHP.

**Konfigurasi php.ini:**
```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = noreply@bkn.go.id
sendmail_path = /usr/sbin/sendmail -t -i
```

### 2. Menggunakan SMTP Gmail (Disarankan untuk Production)

**Langkah 1 - Install PHPMailer:**
```bash
composer require phpmailer/phpmailer
```

**Langkah 2 - Buat file config/smtp.php:**
```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function kirimEmailSMTP($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'email@gmail.com';  // Email Gmail
        $mail->Password   = 'app_password';      // App Password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@bkn.go.id', 'Sistem Konsultasi BKN');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
```

**Langkah 3 - Update config/email.php:**
Ubah baris:
```php
return mail($to, $subject, $message, $headers);
```

Menjadi:
```php
// Jika menggunakan SMTP
require_once 'smtp.php';
return kirimEmailSMTP($to, $subject, $message);
```

### 3. Menggunakan SMTP Server Lain

#### Menggunakan SendGrid:
```bash
composer require sendgrid/sendgrid
```

#### Menggunakan Mailgun:
```bash
composer require mailgun/mailgun-php
```

## Membuat App Password Gmail (Untuk SMTP)

1. Login ke Gmail
2. Masuk ke **Google Account** > **Security**
3. Scroll ke **2-Step Verification** dan aktifkan
4. Setelah aktif, cari **App passwords**
5. Pilih:
   - App: Mail
   - Device: Windows Computer
6. Klik **Generate**
7. Copy password 16 digit yang diberikan
8. Gunakan password ini di konfigurasi SMTP

## Testing Email

Buat file test_email.php:
```php
<?php
require 'config/koneksi.php';
require 'config/email.php';

$emailLib = new EmailLibrary($koneksi);

$test = $emailLib->kirimNotifikasiStatus(
    'test@email.com',
    'Test User',
    'KNS00001',
    'Menunggu',
    'Diproses',
    'Test Konsultasi'
);

if ($test) {
    echo "Email berhasil dikirim!";
} else {
    echo "Gagal mengirim email.";
}
?>
```

Jalankan: `php test_email.php`

## Troubleshooting

### Email tidak terkirim:
1. Cek error log PHP: `/var/log/php/error.log`
2. Pastikan server memiliki akses internet
3. Cek konfigurasi firewall (port 587/465)

### Email masuk ke Spam:
1. Gunakan domain email resmi (bukan Gmail/Yahoo)
2. Setup SPF, DKIM, dan DM records di DNS
3. Gunakan SMTP relay service profesional

## Notifikasi yang Dikirim

| Trigger | Penerima | Konten |
|---------|----------|--------|
| Status berubah | Pegawai | Update status konsultasi |
| Respon baru | Pegawai | Respon dari konselor |

## Konfigurasi Email Sender

Untuk mengubah email sender, edit file `config/email.php`:

```php
$headers .= "From: Nama Aplikasi <email@domain.com>\r\n";
$headers .= "Reply-To: email@domain.com\r\n";
```

## Keamanan

⚠️ **PENTING:**
- Jangan commit password email ke git/repository
- Gunakan environment variables untuk credential
- Gunakan App Password Gmail, bukan password utama
- Aktifkan 2-Factor Authentication di email
