<?php
/**
 * Email Library dengan PHPMailer
 * Untuk mengirim email notifikasi via SMTP
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer dan Exception
require_once __DIR__ . '/../vendor/autoload.php';

// Load konfigurasi SMTP
$config_smtp = require_once __DIR__ . '/smtp_config.php';

class EmailLibrary {
    private $smtpConfig;

    public function __construct() {
        global $config_smtp;
        $this->smtpConfig = $config_smtp;
    }

    /**
     * Kirim email notifikasi perubahan status konsultasi
     *
     * @param string $to Email tujuan
     * @param string $nama Nama penerima
     * @param string $id_konsultasi ID Konsultasi
     * @param string $status_lama Status sebelumnya
     * @param string $status_baru Status setelah diubah
     * @param string $judul Judul konsultasi
     * @return bool
     */
    public function kirimNotifikasiStatus($to, $nama, $id_konsultasi, $status_lama, $status_baru, $judul) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpConfig['username'];
            $mail->Password = $this->smtpConfig['password'];
            $mail->SMTPSecure = $this->smtpConfig['encryption'] === 'ssl' ?
                PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtpConfig['port'];
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom($this->smtpConfig['from_email'], $this->smtpConfig['from_name']);
            $mail->addAddress($to);
            $mail->addReplyTo($this->smtpConfig['from_email'], 'No Reply');

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Update Status Konsultasi - $id_konsultasi";

            // Tentukan warna badge berdasarkan status
            $status_color = '';
            $status_icon = '';

            switch($status_baru) {
                case 'Menunggu':
                    $status_color = '#ffc107';
                    $status_icon = '⏱️';
                    break;
                case 'Diproses':
                    $status_color = '#17a2b8';
                    $status_icon = '⚙️';
                    break;
                case 'Selesai':
                    $status_color = '#28a745';
                    $status_icon = '✅';
                    break;
                default:
                    $status_color = '#6c757d';
                    $status_icon = '📋';
            }

            $baseUrl = $this->getBaseUrl();

            // Template email HTML
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                    .status-box { background: white; padding: 20px; margin: 20px 0; border-left: 5px solid $status_color; border-radius: 5px; }
                    .status-badge { display: inline-block; padding: 8px 15px; background: $status_color; color: white; border-radius: 20px; font-weight: bold; margin: 10px 0; }
                    .detail-row { display: flex; padding: 10px 0; border-bottom: 1px solid #eee; }
                    .detail-label { font-weight: bold; width: 150px; color: #555; }
                    .detail-value { flex: 1; }
                    .footer { background: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #777; border-radius: 0 0 10px 10px; }
                    .btn { display: inline-block; padding: 12px 25px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
                    .btn:hover { background: #5568d3; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>📧 Notifikasi Konsultasi BKN</h2>
                    </div>
                    <div class='content'>
                        <p>Yth. <strong>$nama</strong>,</p>
                        <p>Kami informasikan bahwa status konsultasi Anda telah diperbarui.</p>

                        <div class='status-box'>
                            <h3 style='margin-top: 0;'>$status_icon Status Konsultasi Diperbarui</h3>
                            <p>Status konsultasi Anda telah berubah dari <strong>$status_lama</strong> menjadi:</p>
                            <div style='text-align: center;'>
                                <span class='status-badge'>$status_icon $status_baru</span>
                            </div>
                        </div>

                        <h4>Detail Konsultasi:</h4>
                        <div class='detail-row'>
                            <div class='detail-label'>ID Konsultasi:</div>
                            <div class='detail-value'><strong>$id_konsultasi</strong></div>
                        </div>
                        <div class='detail-row'>
                            <div class='detail-label'>Judul:</div>
                            <div class='detail-value'>$judul</div>
                        </div>

                        <div style='text-align: center; margin: 30px 0;'>
                            <p>Silakan login untuk melihat detail lebih lanjut:</p>
                            <a href='$baseUrl/user/konsultasi/detail.php?id=$id_konsultasi' class='btn' target='_blank'>
                                📋 Lihat Detail Konsultasi
                            </a>
                        </div>

                        <p style='font-size: 12px; color: #777;'>
                            Jika Anda memiliki pertanyaan, silakan hubungi admin BKN.
                        </p>
                    </div>
                    <div class='footer'>
                        <p>© " . date('Y') . " Badan Kepegawaian Negara. All rights reserved.</p>
                        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->AltBody = "Status konsultasi Anda $id_konsultasi telah diperbarui dari $status_lama menjadi $status_baru.";

            $mail->send();
            return true;
        } catch (Exception) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Kirim email notifikasi respon dari konselor
     *
     * @param string $to Email tujuan
     * @param string $nama Nama penerima
     * @param string $id_konsultasi ID Konsultasi
     * @param string $nama_konselor Nama konselor
     * @param string $isi_respon Isi respon
     * @return bool
     */
    public function kirimNotifikasiRespon($to, $nama, $id_konsultasi, $nama_konselor, $isi_respon) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpConfig['username'];
            $mail->Password = $this->smtpConfig['password'];
            $mail->SMTPSecure = $this->smtpConfig['encryption'] === 'ssl' ?
                PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtpConfig['port'];
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom($this->smtpConfig['from_email'], $this->smtpConfig['from_name']);
            $mail->addAddress($to);
            $mail->addReplyTo($this->smtpConfig['from_email'], 'No Reply');

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Respon Konsultasi - $id_konsultasi";

            $baseUrl = $this->getBaseUrl();

            // Template email HTML
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                    .respon-box { background: white; padding: 20px; margin: 20px 0; border-left: 5px solid #28a745; border-radius: 5px; }
                    .footer { background: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #777; border-radius: 0 0 10px 10px; }
                    .btn { display: inline-block; padding: 12px 25px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>💬 Respon Konsultasi</h2>
                    </div>
                    <div class='content'>
                        <p>Yth. <strong>$nama</strong>,</p>
                        <p>Konsultasi Anda telah mendapatkan respon dari konselor.</p>

                        <div class='respon-box'>
                            <h4 style='margin-top: 0;'>Konselor: $nama_konselor</h4>
                            <p>" . nl2br(htmlspecialchars($isi_respon)) . "</p>
                        </div>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='$baseUrl/user/konsultasi/detail.php?id=$id_konsultasi' class='btn' target='_blank'>
                                📋 Lihat Detail Konsultasi
                            </a>
                        </div>
                    </div>
                    <div class='footer'>
                        <p>© " . date('Y') . " Badan Kepegawaian Negara. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->AltBody = "Konsultasi Anda mendapatkan respon dari konselor $nama_konselor.";

            $mail->send();
            return true;
        } catch (Exception) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Get base URL from server
     */
    private function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return "$protocol://$host";
    }
}
?>
