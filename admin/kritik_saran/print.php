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

// Ambil parameter filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Build filter query
$where_clause = 'WHERE 1=1';
$filter_info = '';
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $tanggal_awal_safe = $koneksi->real_escape_string($tanggal_awal);
    $tanggal_akhir_safe = $koneksi->real_escape_string($tanggal_akhir);
    $where_clause .= " AND DATE(tanggal) BETWEEN '$tanggal_awal_safe' AND '$tanggal_akhir_safe'";

    $tgl_awal_fmt = date('d/m/Y', strtotime($tanggal_awal));
    $tgl_akhir_fmt = date('d/m/Y', strtotime($tanggal_akhir));
    $filter_info = "Periode: $tgl_awal_fmt s/d $tgl_akhir_fmt";
}
?>

<script type="text/javascript">
    window.print();
</script>

<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN KRITIK & SARAN</title>
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

    <h2 style="text-align:center;">LAPORAN KRITIK & SARAN</h2>
    <?php if (!empty($filter_info)): ?>
    <p style="text-align:center; font-weight:bold;"><?= $filter_info ?></p>
    <?php endif; ?>
    <table>
        <thead>
            <tr align="center">
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Instansi</th>
                <th>Jabatan</th>
                <th>Kritik</th>
                <th>Saran</th>
                <th>Penilaian</th>
                <th>Kontak</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $data = $koneksi->query("SELECT * FROM kritik_saran $where_clause ORDER BY tanggal DESC, id_kritik_saran DESC");
        while ($row = $data->fetch_array()) {
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['instansi']) ?></td>
                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                <td><?= htmlspecialchars($row['kritik']) ?></td>
                <td><?= htmlspecialchars($row['saran']) ?></td>
                <td><?= htmlspecialchars($row['penilaian']) ?></td>
                <td><?= htmlspecialchars($row['kontak']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
</body>
</html>
