<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$bln = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
);
?>

<script type="text/javascript">
    window.print();
</script>

<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN DATA KONSULTASI</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .header img { float: left; }
        .header h1, .header p { margin: 0; }
        hr { border: 1px solid #000; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px 8px; }
        th { background: #f2f2f2; }
        .no-print { display: none; }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" width="90" height="90">
        <h1>Kantor Regional VIII Badan Kepegawaian Negara</h1>
        <p>Alamat: Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714</p>
    </div>
    <br>
    <br>
    <hr>

    <h2 style="text-align:center;">LAPORAN DATA KONSULTASI</h2>
    <table>
        <thead>
            <tr align="center">
                <th>No</th>
                <th>ID KONSULTASI</th>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Tanggal Respon</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $data = $koneksi->query("
            SELECT k.*, p.nama_lengkap, p.nip, c.nama_kategori 
            FROM konsultasi k
            LEFT JOIN pegawai p ON k.nip = p.nip
            LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
            ORDER BY k.tanggal_pengajuan DESC
        ");
        while ($row = $data->fetch_array()) {
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['id_konsultasi'] ?></td>
                <td><?= $row['nip'] ?></td>
                <td><?= $row['nama_lengkap'] ?></td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['tanggal_pengajuan'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['deskripsi'] ?></td>
                <td><?= $row['tanggal_respon'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
</body>
</html>
