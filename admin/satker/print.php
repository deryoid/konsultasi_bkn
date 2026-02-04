<?php
include '../../config/config.php';
include '../../config/koneksi.php';

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
    <title>LAPORAN DATA SATKER</title>
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

    <h2 style="text-align:center;">LAPORAN DATA SATKER</h2>
    <table>
        <thead>
            <tr align="center">
                <th>No</th>
                <th>ID Satker</th>
                <th>Satker</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $data = $koneksi->query("SELECT * FROM satker ORDER BY id_satker DESC");
        while ($row = $data->fetch_array()) {
        ?>
            <tr></tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['id_satker'] ?></td>
                <td><?= $row['satker'] ?></td>
                <td><?= $row['lokasi'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
</body>
</html>
