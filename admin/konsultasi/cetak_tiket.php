<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

if (!isset($_GET['id'])) {
    die('ID konsultasi tidak ditemukan.');
}

$id = $_GET['id'];

$query = $koneksi->query("
    SELECT k.*, p.nama_lengkap, p.nip, c.nama_kategori 
    FROM konsultasi k
    LEFT JOIN pegawai p ON k.nip = p.nip
    LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
    WHERE k.id_konsultasi = '$id'
    LIMIT 1
");

$data = $query->fetch_assoc();
if (!$data) {
    die('Data konsultasi tidak ditemukan.');
}
?>

<script type="text/javascript">
    window.print();
</script>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tiket Konsultasi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket-container {
            width: 500px;
            margin: 40px auto;
            border: 2px dashed #333;
            padding: 30px 40px;
            border-radius: 12px;
            background: #f9f9f9;
        }
        .header { text-align: center; }
        .header img { margin-bottom: 10px; }
        .ticket-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; }
        .ticket-info { margin-bottom: 10px; }
        .label { font-weight: bold; width: 140px; display: inline-block; }
        .footer { text-align: right; margin-top: 30px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" width="70" height="70">
            <div>Kantor Regional VIII BKN</div>
            <div style="font-size:13px;">Jl. Bhayangkara No.1, Banjarbaru, Kalimantan Selatan</div>
        </div>
        <hr>
        <div class="ticket-title">TIKET KONSULTASI</div>
        <div class="ticket-info"><span class="label">ID Konsultasi</span>: <?= $data['id_konsultasi'] ?></div>
        <div class="ticket-info"><span class="label">NIP</span>: <?= $data['nip'] ?></div>
        <div class="ticket-info"><span class="label">Nama Lengkap</span>: <?= $data['nama_lengkap'] ?></div>
        <div class="ticket-info"><span class="label">Kategori</span>: <?= $data['nama_kategori'] ?></div>
        <div class="ticket-info"><span class="label">Judul</span>: <?= $data['judul'] ?></div>
        <div class="ticket-info"><span class="label">Tanggal Pengajuan</span>: <?= $data['tanggal_pengajuan'] ?></div>
        <div class="ticket-info"><span class="label">Status</span>: <?= $data['status'] ?></div>
        <div class="ticket-info"><span class="label">Deskripsi</span>: <?= $data['deskripsi'] ?></div>
        <div class="ticket-info"><span class="label">Tanggal Respon</span>: <?= $data['tanggal_respon'] ?></div>
        <div class="footer">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
    </div>
</body>
</html>
