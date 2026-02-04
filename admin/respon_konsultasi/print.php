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
    <title>LAPORAN RESPON KONSULTASI</title>
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

    <h2 style="text-align:center;">LAPORAN RESPON KONSULTASI</h2>
    <table>
        <thead>
            <tr align="center">
                <th>No</th>
                <th>ID Konsultasi</th>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Nama Konselor</th>
                <th>Isi Respon</th>
                <th>Tanggal Respon</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $where = '';
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
            $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
            if ($check && $check->num_rows > 0) {
                $uid = $koneksi->real_escape_string($_SESSION['id_user']);
                $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$uid' LIMIT 1");
                if ($map && $map->num_rows > 0) {
                    $mk = $map->fetch_assoc();
                    $idk = $koneksi->real_escape_string($mk['id_konselor']);
                    $where = "WHERE r.id_konselor = '".$idk."'";
                } else {
                    $where = "WHERE 1=0";
                }
            } else {
                $where = "WHERE 1=0";
            }
        }
        $data = $koneksi->query("
             SELECT r.*, k.nip, p.nama_lengkap, kr.nama_konselor
            FROM respon_konsultasi r
            LEFT JOIN konsultasi k ON r.id_konsultasi = k.id_konsultasi
            LEFT JOIN konselor kr ON kr.id_konselor = r.id_konselor
            LEFT JOIN pegawai p ON k.nip = p.nip
            $where
            ORDER BY r.tanggal_respon DESC
        ");
        while ($row = $data->fetch_array()) {
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['id_konsultasi'] ?></td>
                <td><?= $row['nip'] ?></td>
                <td><?= $row['nama_lengkap'] ?></td>
                <td><?= $row['nama_konselor'] ?></td>
                <td><?= $row['isi_respon'] ?></td>
                <td><?= $row['tanggal_respon'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
</body>
</html>
