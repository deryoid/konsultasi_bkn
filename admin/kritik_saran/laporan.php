<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Laporan Lengkap Kritik & Saran";

// Ambil semua data kritik & saran
$all_data = $koneksi->query("SELECT * FROM kritik_saran ORDER BY tanggal DESC, id_kritik_saran DESC");

// Hitung statistik penilaian
$stats_penilaian = [
    'sangat_baik' => 0,
    'baik' => 0,
    'cukup' => 0,
    'kurang' => 0,
    'sangat_kurang' => 0,
    'total' => 0,
    'avg_bintang' => 0
];

$total_bintang = 0;

while ($row = $all_data->fetch_assoc()) {
    $penilaian = $row['penilaian'];
    $stats_penilaian['total']++;

    switch($penilaian) {
        case 'Sangat Baik':
            $stats_penilaian['sangat_baik']++;
            $total_bintang += 5;
            break;
        case 'Baik':
            $stats_penilaian['baik']++;
            $total_bintang += 4;
            break;
        case 'Cukup':
            $stats_penilaian['cukup']++;
            $total_bintang += 3;
            break;
        case 'Kurang':
            $stats_penilaian['kurang']++;
            $total_bintang += 2;
            break;
        case 'Sangat Kurang':
            $stats_penilaian['sangat_kurang']++;
            $total_bintang += 1;
            break;
    }
}

if ($stats_penilaian['total'] > 0) {
    $stats_penilaian['avg_bintang'] = round($total_bintang / $stats_penilaian['total'], 2);
}

// Reset pointer untuk tabel
$all_data->data_seek(0);
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
                            <h1 class="m-0 text-dark">Laporan Kritik & Saran</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Feedback</li>
                                <li class="breadcrumb-item active">Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Statistik Header -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rata-rata Kepuasan</span>
                                    <span class="info-box-number"><?= $stats_penilaian['avg_bintang'] ?> ⭐</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-comments"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Respon</span>
                                    <span class="info-box-number"><?= $stats_penilaian['total'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-smile-beam"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sangat Baik</span>
                                    <span class="info-box-number"><?= $stats_penilaian['sangat_baik'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-smile"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Baik</span>
                                    <span class="info-box-number"><?= $stats_penilaian['baik'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie"></i> Distribusi Penilaian
                                    </h3>
                                    <div class="card-tools">
                                        <button onclick="window.print()" class="btn btn-dark btn-sm">
                                            <i class="fas fa-print"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="penilaianChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar"></i> Statistik Penilaian
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Penilaian</th>
                                                <th class="text-center">Jumlah</th>
                                                <th>Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $penilaian_list = [
                                                'Sangat Baik' => 'sangat_baik',
                                                'Baik' => 'baik',
                                                'Cukup' => 'cukup',
                                                'Kurang' => 'kurang',
                                                'Sangat Kurang' => 'sangat_kurang'
                                            ];

                                            $colors = ['success', 'primary', 'info', 'warning', 'danger'];
                                            $index = 0;

                                            foreach ($penilaian_list as $label => $key):
                                                $count = $stats_penilaian[$key];
                                                $pct = $stats_penilaian['total'] > 0 ? round(($count / $stats_penilaian['total']) * 100, 1) : 0;
                                                $bintang = '';
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($index < 5 - $key_index = array_search($key, array_keys($penilaian_list))) {
                                                        $bintang .= '⭐';
                                                    }
                                                }
                                                // Recalculate stars based on key
                                                if ($label == 'Sangat Baik') $bintang = '⭐⭐⭐⭐⭐';
                                                elseif ($label == 'Baik') $bintang = '⭐⭐⭐⭐';
                                                elseif ($label == 'Cukup') $bintang = '⭐⭐⭐';
                                                elseif ($label == 'Kurang') $bintang = '⭐⭐';
                                                else $bintang = '⭐';
                                            ?>
                                            <tr>
                                                <td><?= $bintang ?> <?= $label ?></td>
                                                <td class="text-center"><strong><?= $count ?></strong></td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-<?= $colors[$index] ?>" style="width: <?= $pct ?>%"><?= $pct ?>%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                                <?php $index++; endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Lengkap -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table"></i> Semua Kritik & Saran
                                    </h3>
                                    <div class="card-tools">
                                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Cetak Halaman
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($all_data->num_rows > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tabelKritikSaran">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="5%">Tanggal</th>
                                                    <th width="18%">Nama</th>
                                                    <th width="10%">Instansi</th>
                                                    <th width="12%">Jabatan</th>
                                                    <th width="10%">Penilaian</th>
                                                    <th width="20%">Kritik</th>
                                                    <th width="20%">Saran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                while ($row = $all_data->fetch_assoc()):
                                                    $badge_class = '';
                                                    $bintang = '';
                                                    switch($row['penilaian']) {
                                                        case 'Sangat Baik':
                                                            $badge_class = 'badge-success';
                                                            $bintang = '⭐⭐⭐⭐⭐';
                                                            break;
                                                        case 'Baik':
                                                            $badge_class = 'badge-primary';
                                                            $bintang = '⭐⭐⭐⭐';
                                                            break;
                                                        case 'Cukup':
                                                            $badge_class = 'badge-info';
                                                            $bintang = '⭐⭐⭐';
                                                            break;
                                                        case 'Kurang':
                                                            $badge_class = 'badge-warning';
                                                            $bintang = '⭐⭐';
                                                            break;
                                                        case 'Sangat Kurang':
                                                            $badge_class = 'badge-danger';
                                                            $bintang = '⭐';
                                                            break;
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                                                        <?php if (!empty($row['nip'])): ?>
                                                        <br><small class="text-info">NIP: <?= $row['nip'] ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?= $row['nama_lengkap'] ?></strong>
                                                    </td>
                                                    <td><?= $row['instansi'] ?></td>
                                                    <td><?= $row['jabatan'] ?></td>
                                                    <td>
                                                        <span class="badge <?= $badge_class ?>"><?= $bintang ?></span><br>
                                                        <small><?= $row['penilaian'] ?></small>
                                                    </td>
                                                    <td><?= $row['kritik'] ?></td>
                                                    <td><?= $row['saran'] ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-inbox"></i>
                                        Belum ada kritik & saran yang masuk.
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <script>
        const penilaianData = [<?= $stats_penilaian['sangat_baik'] ?>, <?= $stats_penilaian['baik'] ?>, <?= $stats_penilaian['cukup'] ?>, <?= $stats_penilaian['kurang'] ?>, <?= $stats_penilaian['sangat_kurang'] ?>];
        const penilaianColors = ['#28a745', '#007bff', '#17a2b8', '#ffc107', '#dc3545'];

        const ctxPenilaian = document.getElementById('penilaianChart').getContext('2d');
        const penilaianChart = new Chart(ctxPenilaian, {
            type: 'pie',
            data: {
                labels: ['⭐⭐⭐⭐⭐ Sangat Baik', '⭐⭐⭐⭐ Baik', '⭐⭐⭐ Cukup', '⭐⭐ Kurang', '⭐ Sangat Kurang'],
                datasets: [{
                    data: penilaianData,
                    backgroundColor: penilaianColors,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        $(document).ready(function() {
            $('#tabelKritikSaran').DataTable({
                "order": [[1, "desc"]],
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
        }
    </style>
</body>
</html>
