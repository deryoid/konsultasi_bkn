<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$id_konsultasi = $_GET['id'];

// Ambil data konsultasi
$query = $koneksi->query("SELECT k.*, kat.nama_kategori, p.nama_lengkap, p.nik, p.jabatan, s.satker
    FROM konsultasi k
    LEFT JOIN kategori kat ON k.id_kategori = kat.id_kategori
    LEFT JOIN pegawai p ON k.nip = p.nip
    LEFT JOIN satker s ON p.id_satker = s.id_satker
    WHERE k.id_konsultasi = '$id_konsultasi' AND k.nip = '".$_SESSION['nip']."'");

$data = $query->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!');window.close();</script>";
    exit();
}

// Ambil respon konsultasi
$query_respon = $koneksi->query("SELECT r.*, kn.nama_konselor, kn.jabatan_konselor
    FROM respon_konsultasi r
    LEFT JOIN konselor kn ON r.id_konselor = kn.id_konselor
    WHERE r.id_konsultasi = '$id_konsultasi'
    ORDER BY r.tanggal_respon DESC
    LIMIT 1");

$respon = $query_respon->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tiket Konsultasi - <?= $data['id_konsultasi'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .ticket {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #E98B33;
            padding: 30px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #E98B33;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
            height: 130px;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #E98B33;
            margin: 10px 0;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
        }
        .info-table td {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
            color: #555;
        }
        .status-box {
            padding: 15px;
            background: #f0f0f0;
            border-left: 4px solid #E98B33;
            margin: 20px 0;
        }
        .respon-box {
            padding: 15px;
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #E98B33; color: white; border: none; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #666; color: white; border: none; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="ticket">
        <div class="header">
            <img src="<?= base_url() ?>/assets/dist/img/LOGO_BKN.png" class="logo">
            <h2>TIKET KONSULTASI</h2>
            <p>Badan Kepegawaian Negara</p>
        </div>

        <table class="info-table">
            <tr>
                <td>ID Konsultasi</td>
                <td>: <?= $data['id_konsultasi'] ?></td>
            </tr>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>: <?= date('d/m/Y', strtotime($data['tanggal_pengajuan'])) ?></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>: <?= $data['nama_kategori'] ?></td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: <?= $data['judul'] ?></td>
            </tr>
        </table>

        <div class="status-box">
            <strong>Status:</strong> <?= $data['status'] ?>
        </div>

        <table class="info-table">
            <tr>
                <td>Nama Pegawai</td>
                <td>: <?= $data['nama_lengkap'] ?></td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: <?= $data['nip'] ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= $data['nik'] ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: <?= $data['jabatan'] ?></td>
            </tr>
            <tr>
                <td>Satker</td>
                <td>: <?= $data['satker'] ?></td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td colspan="2" style="border: none; padding-top: 20px;"><strong>Deskripsi Konsultasi:</strong></td>
            </tr>
            <tr>
                <td colspan="2"><?= nl2br($data['deskripsi']) ?></td>
            </tr>
        </table>

        <?php if ($respon): ?>
        <div class="respon-box">
            <strong><i class="fas fa-reply"></i> Respon Konselor:</strong><br><br>
            <strong><?= $respon['nama_konselor'] ?></strong>
            (<?= $respon['jabatan_konselor'] ?>)<br><br>
            <?= nl2br($respon['isi_respon']) ?><br><br>
            <small><em>Tanggal Respon: <?= date('d/m/Y H:i', strtotime($respon['tanggal_respon'])) ?></em></small>
            <?php if ($respon['lampiran_respon']): ?>
                <br><br>
                <a href="<?= base_url('uploads/' . $respon['lampiran_respon']) ?>" target="_blank" style="color: #E98B33;">
                    <i class="fas fa-paperclip"></i> Lampiran
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>Tiket ini dibuat secara otomatis oleh Sistem Konsultasi BKN</p>
            <p><?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>

    <script>
        // Auto print when loaded
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        };
    </script>
</body>
</html>
