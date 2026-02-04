<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Laporan Waktu Respon";

// Filter tanggal
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$tipe_filter = isset($_GET['tipe']) ? $_GET['tipe'] : 'hari_ini'; // hari_ini, semua, custom

// Set query berdasarkan filter
if ($tipe_filter == 'hari_ini') {
    $tanggal_filter = date('Y-m-d');
    $where_tanggal = "WHERE DATE(r.tanggal_respon) = '$tanggal_filter'";
    $label_tanggal = "Hari Ini (" . date('d/m/Y') . ")";
} elseif ($tipe_filter == 'custom' && !empty($tanggal_filter)) {
    $where_tanggal = "WHERE DATE(r.tanggal_respon) = '$tanggal_filter'";
    $label_tanggal = date('d/m/Y', strtotime($tanggal_filter));
} else {
    $where_tanggal = "";
    $label_tanggal = "Semua Waktu";
}

// Ambil data respon dengan waktu respon
$query = "
    SELECT
        k.id_konsultasi,
        k.nip,
        k.judul,
        k.tanggal_pengajuan,
        r.tanggal_respon,
        r.isi_respon,
        p.nama_lengkap,
        kn.nama_konselor,
        c.nama_kategori,
        k.status,
        TIMESTAMPDIFF(DAY, k.tanggal_pengajuan, r.tanggal_respon) as hari_respon,
        TIMESTAMPDIFF(HOUR, k.tanggal_pengajuan, r.tanggal_respon) as jam_respon,
        TIMESTAMPDIFF(MINUTE, k.tanggal_pengajuan, r.tanggal_respon) as menit_respon
    FROM respon_konsultasi r
    INNER JOIN konsultasi k ON r.id_konsultasi = k.id_konsultasi
    LEFT JOIN pegawai p ON k.nip = p.nip
    LEFT JOIN konselor kn ON r.id_konselor = kn.id_konselor
    LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
    $where_tanggal
    ORDER BY r.tanggal_respon DESC
";

$data_respon = $koneksi->query($query);

// Hitung statistik
$stats = [
    'total_respon' => 0,
    'avg_hari' => 0,
    'avg_jam' => 0,
    'max_hari' => 0,
    'min_hari' => 0,
    'kurang_dari_1_hari' => 0,
    '1_3_hari' => 0,
    'lebih_dari_3_hari' => 0
];

$total_hari = 0;
$total_jam = 0;
$max_hari = 0;
$min_hari = PHP_INT_MAX;

if ($data_respon->num_rows > 0) {
    $stats['total_respon'] = $data_respon->num_rows;

    while ($row = $data_respon->fetch_assoc()) {
        $hari = $row['hari_respon'];
        $jam = $row['jam_respon'];

        $total_hari += $hari;
        $total_jam += $jam;

        if ($hari > $max_hari) {
            $max_hari = $hari;
        }
        if ($hari < $min_hari) {
            $min_hari = $hari;
        }

        // Kategorisasi waktu respon
        if ($jam < 24) {
            $stats['kurang_dari_1_hari']++;
        } elseif ($hari <= 3) {
            $stats['1_3_hari']++;
        } else {
            $stats['lebih_dari_3_hari']++;
        }
    }

    $stats['avg_hari'] = round($total_hari / $stats['total_respon'], 1);
    $stats['avg_jam'] = round($total_jam / $stats['total_respon'], 1);
    $stats['max_hari'] = $max_hari;
    $stats['min_hari'] = $min_hari == PHP_INT_MAX ? 0 : $min_hari;

    // Reset pointer untuk ditampilkan di tabel
    $data_respon->data_seek(0);
}
?>
<!DOCTYPE html>
<html>
<?php include '../../templates/head.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include '../../templates/navbar.php'; ?>
        <?php include '../../templates/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Laporan Waktu Respon</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Laporan</li>
                                <li class="breadcrumb-item active">Waktu Respon</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Filter -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter Laporan
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <label>Tipe Filter:</label>
                                    <select name="tipe" class="form-control ml-2" onchange="this.form.submit()">
                                        <option value="hari_ini" <?= $tipe_filter == 'hari_ini' ? 'selected' : '' ?>>Hari Ini</option>
                                        <option value="semua" <?= $tipe_filter == 'semua' ? 'selected' : '' ?>>Semua Waktu</option>
                                        <option value="custom" <?= $tipe_filter == 'custom' ? 'selected' : '' ?>>Tanggal Custom</option>
                                    </select>
                                </div>

                                <?php if ($tipe_filter == 'custom'): ?>
                                <div class="form-group mr-2">
                                    <label>Tanggal:</label>
                                    <input type="date" name="tanggal" class="form-control ml-2" value="<?= $tanggal_filter ?>" onchange="this.form.submit()">
                                </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="waktu_respon.php" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>

                            <?php if ($tipe_filter != 'semua'): ?>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i>
                                Menampilkan respon pada tanggal: <strong><?= $label_tanggal ?></strong>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Statistik Box -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $stats['total_respon'] ?></h3>
                                    <p>Total Respon</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-reply"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $stats['avg_hari'] ?> Hari</h3>
                                    <p>Rata-rata Respon</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $stats['min_hari'] ?> Hari</h3>
                                    <p>Respon Tercepat</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= $stats['max_hari'] ?> Hari</h3>
                                    <p>Respon Terlama</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hourglass-end"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribusi Waktu Respon -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie"></i> Distribusi Waktu Respon
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-box bg-success">
                                                <span class="info-box-icon"><i class="fas fa-bolt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Kurang dari 1 Hari</span>
                                                    <span class="info-box-number"><?= $stats['kurang_dari_1_hari'] ?> Respon</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box bg-warning">
                                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">1-3 Hari</span>
                                                    <span class="info-box-number"><?= $stats['1_3_hari'] ?> Respon</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box bg-danger">
                                                <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Lebih dari 3 Hari</span>
                                                    <span class="info-box-number"><?= $stats['lebih_dari_3_hari'] ?> Respon</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Waktu Respon -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table"></i> Detail Waktu Respon
                                    </h3>
                                    <div class="card-tools">
                                        <a href="cetak_waktu_respon.php?tipe=<?= $tipe_filter ?>&tanggal=<?= $tanggal_filter ?>" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-print"></i> Cetak Laporan
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($data_respon->num_rows > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="tabelWaktuRespon">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="12%">ID Konsultasi</th>
                                                    <th width="15%">Pegawai</th>
                                                    <th width="5%">Kategori</th>
                                                    <th width="20%">Judul</th>
                                                    <th width="10%">Tgl Pengajuan</th>
                                                    <th width="10%">Tgl Respon</th>
                                                    <th width="8%">Waktu Respon</th>
                                                    <th width="10%">Konselor</th>
                                                    <th width="5%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                while ($row = $data_respon->fetch_assoc()):
                                                    $hari = $row['hari_respon'];
                                                    $jam = $row['jam_respon'];
                                                    $menit = $row['menit_respon'];

                                                    // Format waktu respon
                                                    if ($jam < 1) {
                                                        $waktu_respon = $menit . " menit";
                                                        $badge_class = "badge-success";
                                                    } elseif ($jam < 24) {
                                                        $waktu_respon = $jam . " jam";
                                                        $badge_class = "badge-info";
                                                    } elseif ($hari == 1) {
                                                        $waktu_respon = "1 hari";
                                                        $badge_class = "badge-warning";
                                                    } else {
                                                        $waktu_respon = $hari . " hari";
                                                        $badge_class = "badge-danger";
                                                    }

                                                    // Status badge
                                                    $status_class = '';
                                                    switch($row['status']) {
                                                        case 'Menunggu': $status_class = 'badge-warning'; break;
                                                        case 'Diproses': $status_class = 'badge-info'; break;
                                                        case 'Selesai': $status_class = 'badge-success'; break;
                                                        default: $status_class = 'badge-secondary';
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <strong><?= $row['id_konsultasi'] ?></strong>
                                                    </td>
                                                    <td>
                                                        <strong><?= $row['nama_lengkap'] ?></strong><br>
                                                        <small class="text-muted"><?= $row['nip'] ?></small>
                                                    </td>
                                                    <td>
                                                        <small><?= $row['nama_kategori'] ?></small>
                                                    </td>
                                                    <td><?= $row['judul'] ?></td>
                                                    <td>
                                                        <?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?><br>
                                                        <small class="text-muted"><?= date('H:i', strtotime($row['tanggal_pengajuan'])) ?></small>
                                                    </td>
                                                    <td>
                                                        <?= date('d/m/Y', strtotime($row['tanggal_respon'])) ?><br>
                                                        <small class="text-muted"><?= date('H:i', strtotime($row['tanggal_respon'])) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $badge_class ?>">
                                                            <?= $waktu_respon ?>
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php
                                                            if ($hari > 0) {
                                                                echo $hari . " hari " . ($jam % 24) . " jam";
                                                            } else {
                                                                echo $jam . " jam " . ($menit % 60) . " menit";
                                                            }
                                                            ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small><?= $row['nama_konselor'] ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $status_class ?>">
                                                            <?= $row['status'] ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Tidak ada data respon untuk filter yang dipilih.
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        <?php include_once "../../templates/footer.php"; ?>
    </div>

    <?php include_once "../../templates/script.php"; ?>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <script>
        $(document).ready(function() {
            $('#tabelWaktuRespon').DataTable({
                "order": [[6, "desc"]],
                "pageLength": 25,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });
        });
    </script>

    <style>
        @media print {
            .no-print { display: none !important; }
            .card-header button { display: none !important; }
            .breadcrumb { display: none !important; }
            .main-sidebar, .main-header, .main-footer { display: none !important; }
            .content-wrapper { margin-left: 0 !important; }
            .info-box { page-break-inside: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</body>
</html>
