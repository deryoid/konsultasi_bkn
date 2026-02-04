<?php
// Koneksi ke database
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

// Ambil parameter filter dari URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Validasi filter
if (empty($filter)) {
    die('Parameter filter tidak ditemukan.');
}

// Ambil data pegawai berdasarkan filter (misal: nip)
$stmt = $koneksi->prepare("SELECT * FROM pegawai WHERE nip = ?");
$stmt->bind_param("s", $filter);
$stmt->execute();
$result = $stmt->get_result();
$pegawai = $result->fetch_assoc();

if (!$pegawai) {
    die('Data pegawai tidak ditemukan.');
}

$nip = $pegawai['nip'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Konsultasi Pegawai</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .header img { float: left; }
        .header h1, .header p { margin: 0; }
        hr { border: 1px solid #000; }
        .pegawai-table, .respon-table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .pegawai-table th, .pegawai-table td, .respon-table th, .respon-table td { border: 1px solid #ccc; padding: 8px; }
        .pegawai-table th, .respon-table th { background: #f2f2f2; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" width="90" height="90">
        <h1>Kantor Regional VIII Badan Kepegawaian Negara</h1>
        <p>Alamat: Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714</p>
    </div>
    <br>
    <br>
    <hr>    

<h2>Riwayat Konsultasi Pegawai</h2>
<table class="pegawai-table">
    <tr>
        <th>NIP</th>
        <td><?= htmlspecialchars($pegawai['nip']) ?></td>
    </tr>
    <tr>
        <th>NIK</th>
        <td><?= htmlspecialchars($pegawai['nik']) ?></td>
    </tr>
    <tr>
        <th>Nama Lengkap</th>
        <td><?= htmlspecialchars($pegawai['nama_lengkap']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($pegawai['email']) ?></td>
    </tr>
    <tr>
        <th>ID Satker</th>
        <td><?= htmlspecialchars($pegawai['id_satker']) ?></td>
    </tr>
    <tr>
        <th>Jabatan</th>
        <td><?= htmlspecialchars($pegawai['jabatan']) ?></td>
    </tr>
</table>

<h2>Riwayat Respon Konsultasi</h2>
<table class="respon-table">
    <thead>
        <tr>
            <th>No</th>
            <th>ID Konsultasi</th>
            <th>Nama Konselor</th>
            <th>Isi Respon</th>
            <th>Tanggal Respon</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Ambil semua respon konsultasi milik pegawai beserta nama konselor
    $no = 1;
    $respon = $koneksi->query("
        SELECT r.*, ksl.nama_konselor AS nama_konselor
        FROM respon_konsultasi r
        INNER JOIN konsultasi k ON r.id_konsultasi = k.id_konsultasi
        LEFT JOIN konselor ksl ON r.id_konselor = ksl.id_konselor
        WHERE k.nip = '" . $koneksi->real_escape_string($nip) . "'
        ORDER BY r.tanggal_respon DESC
    ");
    if ($respon->num_rows > 0):
        while ($row = $respon->fetch_assoc()):
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['id_konsultasi']) ?></td>
            <td><?= htmlspecialchars($row['nama_konselor']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['isi_respon'])) ?></td>
            <td><?= htmlspecialchars($row['tanggal_respon']) ?></td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr>
            <td colspan="7" style="text-align:center;">Belum ada respon konsultasi.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
