<?php
require 'config/config.php';
require 'config/koneksi.php';
require 'config/day.php';

// Ambil parameter pencarian
$nip = isset($_GET['nip']) ? trim($_GET['nip']) : '';
$id_konsultasi = isset($_GET['id_konsultasi']) ? trim($_GET['id_konsultasi']) : '';
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Validasi input
if ($nip === '' && $id_konsultasi === '') {
    die('Masukkan NIP atau ID Konsultasi.');
}

// Build filter tanggal
$where_tanggal = 'WHERE 1=1';
$filter_info = '';
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $tanggal_awal_safe = $koneksi->real_escape_string($tanggal_awal);
    $tanggal_akhir_safe = $koneksi->real_escape_string($tanggal_akhir);
    $where_tanggal .= " AND r.tanggal_respon BETWEEN '$tanggal_awal_safe' AND '$tanggal_akhir_safe'";

    $tgl_awal_fmt = date('d/m/Y', strtotime($tanggal_awal));
    $tgl_akhir_fmt = date('d/m/Y', strtotime($tanggal_akhir));
    $filter_info = "<br><small>Periode Respon: $tgl_awal_fmt s/d $tgl_akhir_fmt</small>";
}

// Bangun query pencarian
$where = [];
if ($nip !== '') {
    $where[] = "k.nip = '" . $koneksi->real_escape_string($nip) . "'";
}
if ($id_konsultasi !== '') {
    $where[] = "k.id_konsultasi = '" . $koneksi->real_escape_string($id_konsultasi) . "'";
}
$where_sql = implode(' AND ', $where);

$query = $koneksi->query("
    SELECT k.*, p.nama_lengkap, p.nip, c.nama_kategori 
    FROM konsultasi k
    LEFT JOIN pegawai p ON k.nip = p.nip
    LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
    WHERE $where_sql
    LIMIT 1
");

$data = $query->fetch_assoc();
if (!$data) {
    die('Data konsultasi tidak ditemukan.');
}
// Ambil data respon konsultasi
$id_konsultasi_val = $koneksi->real_escape_string($data['id_konsultasi']);
$where_id = " AND r.id_konsultasi = '$id_konsultasi_val'";
$final_where = $where_tanggal . $where_id;

$respon = $koneksi->query("
    SELECT r.*, k.nama_konselor
    FROM respon_konsultasi r
    LEFT JOIN konselor k ON r.id_konselor = k.id_konselor
    $final_where
    ORDER BY r.tanggal_respon ASC
");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tiket Konsultasi</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 0;
        }
        .ticket-container {
            max-width: 540px;
            margin: 40px auto;
            border: 1.5px solid #1976d2;
            padding: 32px 36px 28px 36px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(25, 118, 210, 0.08);
        }
        .header {
            text-align: center;
            margin-bottom: 18px;
        }
        .header img {
            margin-bottom: 8px;
        }
        .header .instansi {
            font-size: 17px;
            font-weight: 600;
            color: #1976d2;
        }
        .header .alamat {
            font-size: 13px;
            color: #555;
        }
        hr {
            border: none;
            border-top: 1.5px solid #e3e3e3;
            margin: 18px 0 22px 0;
        }
        .ticket-title {
            font-size: 23px;
            font-weight: bold;
            color: #1976d2;
            text-align: center;
            margin-bottom: 22px;
            letter-spacing: 1px;
        }
        .ticket-info {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .label {
            font-weight: 600;
            width: 140px;
            display: inline-block;
            color: #333;
        }
        .footer {
            text-align: right;
            margin-top: 32px;
            font-size: 13px;
            color: #888;
        }
        .respon-section {
            margin-top: 36px;
        }
        .respon-title {
            font-size: 17px;
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 18px;
        }
        .respon-box {
            margin-bottom: 22px;
            padding: 16px 20px 14px 20px;
            border-radius: 8px;
            background: #f7fbff;
            border: 1px solid #dbe6ee;
            box-shadow: 0 1px 3px rgba(25,118,210,0.04);
        }
        .respon-header {
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 8px;
            font-size: 15px;
        }
        .respon-row {
            margin-bottom: 6px;
            font-size: 14px;
        }
        .respon-label {
            font-weight: 500;
            width: 120px;
            display: inline-block;
            color: #444;
            vertical-align: top;
        }
        .respon-content {
            display: inline-block;
            vertical-align: top;
        }
        .respon-isi {
            margin-left: 0;
            margin-top: 3px;
            background: #fff;
            border-radius: 5px;
            padding: 10px 12px;
            border: 1px solid #e3e3e3;
            font-size: 14px;
            color: #222;
            line-height: 1.5;
        }
        .respon-lampiran a {
            color: #1976d2;
            text-decoration: underline;
        }
        .no-respon {
            text-align: center;
            color: #888;
            margin-top: 20px;
            font-size: 14px;
        }
        @media print {
            body { background: #fff; }
            .ticket-container { box-shadow: none; border: 1.5px solid #888; }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" width="70" height="70" alt="Logo BKN">
            <div class="instansi">Kantor Regional VIII BKN</div>
            <div class="alamat">Jl. Bhayangkara No.1, Banjarbaru, Kalimantan Selatan</div>
        </div>
        <hr>
        <div class="ticket-title">TIKET KONSULTASI<?= $filter_info ?></div>
        <div class="ticket-info"><span class="label">ID Konsultasi</span>: <?= $data['id_konsultasi'] ?></div>
        <div class="ticket-info"><span class="label">NIP</span>: <?= $data['nip'] ?></div>
        <div class="ticket-info"><span class="label">Nama Lengkap</span>: <?= $data['nama_lengkap'] ?></div>
        <div class="ticket-info"><span class="label">Kategori</span>: <?= $data['nama_kategori'] ?></div>
        <div class="ticket-info"><span class="label">Judul</span>: <?= $data['judul'] ?></div>
        <div class="ticket-info"><span class="label">Tanggal Pengajuan</span>: <?= $data['tanggal_pengajuan'] ?></div>
        <div class="ticket-info"><span class="label">Status</span>: <b><?= $data['status'] ?></b></div>
        <div class="ticket-info"><span class="label">Deskripsi</span>: <?= $data['deskripsi'] ?></div>

        <div class="respon-section">
            <div class="respon-title">Respon Konsultasi</div>
            <?php
            $no = 1;
            if ($respon->num_rows > 0) {
                while ($row = $respon->fetch_assoc()) {
            ?>
            <div class="respon-box">
                <div class="respon-header">Respon #<?= $no++ ?></div>
                <div class="respon-row">
                    <span class="respon-label">Nama Konselor : </span>
                    <span class="respon-content"> <?= htmlspecialchars($row['nama_konselor']) ?></span>
                </div>
                <div class="respon-row">
                    <span class="respon-label">Tanggal Respon : </span>
                    <span class="respon-content"> <?= date('d/m/Y', strtotime($row['tanggal_respon'])) ?></span>
                </div>
                <div class="respon-row">
                    <span class="respon-label">Isi Respon : </span>
                    <span class="respon-content">
                        <div class="respon-isi"><?= nl2br(htmlspecialchars($row['isi_respon'])) ?></div>
                    </span>
                </div>
                <div class="respon-row respon-lampiran">
                    <span class="respon-label">Lampiran : </span>
                    <span class="respon-content">
                    <?php if (!empty($row['lampiran_respon'])): ?>
                        <a href="<?= base_url('uploads/' . $row['lampiran_respon']) ?>" target="_blank">Lihat Lampiran</a>
                    <?php else: ?>
                        <span style="color:#888;">-</span>
                    <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="no-respon">Belum ada respon.</div>';
            }
            ?>
        </div>
        <div class="footer">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
    </div>
</body>
</html>
