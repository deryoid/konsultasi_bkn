<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Statistik Kategori Konsultasi";

// Ambil data statistik per kategori
$stats = $koneksi->query("
    SELECT
        c.id_kategori,
        c.nama_kategori,
        COUNT(k.id_konsultasi) as jumlah,
        SUM(CASE WHEN k.status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
        SUM(CASE WHEN k.status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
        SUM(CASE WHEN k.status = 'Selesai' THEN 1 ELSE 0 END) as selesai
    FROM kategori c
    LEFT JOIN konsultasi k ON c.id_kategori = k.id_kategori
    GROUP BY c.id_kategori
    ORDER BY jumlah DESC
");

$total_konsultasi = 0;
$data_kategori = [];
$data_jumlah = [];
$warna_chart = [];

// Warna untuk chart
$colors = [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
    '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
];

$index = 0;
while ($row = $stats->fetch_assoc()) {
    $total_konsultasi += $row['jumlah'];
    $data_kategori[] = $row['nama_kategori'];
    $data_jumlah[] = $row['jumlah'];
    $warna_chart[] = $colors[$index % count($colors)];
    $index++;
}

// Ambil data judul konsultasi per kategori untuk rangkuman
$judul_per_kategori = [];
while ($row = $stats->fetch_assoc()) {
    $id_kategori = $row['id_kategori'];
    $judul = $koneksi->query("
        SELECT judul, status, tanggal_pengajuan
        FROM konsultasi
        WHERE id_kategori = '$id_kategori'
        ORDER BY tanggal_pengajuan DESC
    ");

    $list_judul = [];
    while ($j = $judul->fetch_assoc()) {
        $list_judul[] = $j;
    }
    $row['list_judul'] = $list_judul;
    $judul_per_kategori[] = $row;
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
                            <h1 class="m-0 text-dark">Statistik Kategori Konsultasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Laporan</li>
                                <li class="breadcrumb-item active">Statistik Kategori</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fa fa-check"> <?= $_SESSION['pesan']; ?></i>
                        </div>
                    <?php $_SESSION['pesan'] = ''; } ?>

                    <!-- Card Statistik -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= count($data_kategori) ?></h3>
                                    <p>Total Kategori</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $total_konsultasi ?></h3>
                                    <p>Total Konsultasi</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>
                                        <?php
                                        $avg = $total_konsultasi > 0 ? round($total_konsultasi / count($data_kategori), 1) : 0;
                                        echo $avg;
                                        ?>
                                    </h3>
                                    <p>Rata-rata per Kategori</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>
                                        <?php
                                        $max = max($data_jumlah) ?? 0;
                                        echo $max;
                                        ?>
                                    </h3>
                                    <p>Tertinggi</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-trophy"></i>
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
                                        <i class="fas fa-chart-pie"></i> Distribusi Kategori (Pie Chart)
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar"></i> Jenis Masalah ASN (Bar Chart)
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Detail dan Cetak -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table"></i> Rincian Statistik per Kategori
                                    </h3>
                                    <div class="card-tools">
                                        <a href="cetak_statistik.php" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Cetak Laporan
                                        </a>
                                        <button onclick="window.print()" class="btn btn-dark btn-sm">
                                            <i class="fas fa-file-alt"></i> Print Halaman
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tabelStatistik">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="20%">Kategori</th>
                                                    <th width="10%" class="text-center">Menunggu</th>
                                                    <th width="10%" class="text-center">Diproses</th>
                                                    <th width="10%" class="text-center">Selesai</th>
                                                    <th width="10%" class="text-center">Total</th>
                                                    <th width="35%">Rangkuman Judul</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Reset result pointer
                                                $stats->data_seek(0);
                                                $no = 1;
                                                while ($row = $stats->fetch_assoc()):
                                                    $id_kategori = $row['id_kategori'];

                                                    // Ambil judul-judul di kategori ini
                                                    $list_judul = $koneksi->query("
                                                        SELECT judul, status
                                                        FROM konsultasi
                                                        WHERE id_kategori = '$id_kategori'
                                                        ORDER BY tanggal_pengajuan DESC
                                                        LIMIT 5
                                                    ");
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><strong><?= htmlspecialchars($row['nama_kategori']) ?></strong></td>
                                                    <td class="text-center">
                                                        <?php if ($row['menunggu'] > 0): ?>
                                                            <span class="badge badge-warning"><?= $row['menunggu'] ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($row['diproses'] > 0): ?>
                                                            <span class="badge badge-info"><?= $row['diproses'] ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($row['selesai'] > 0): ?>
                                                            <span class="badge badge-success"><?= $row['selesai'] ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="badge badge-primary"><?= $row['jumlah'] ?></strong>
                                                    </td>
                                                    <td>
                                                        <?php if ($list_judul->num_rows > 0): ?>
                                                            <ol style="margin: 0; padding-left: 20px;">
                                                                <?php while ($j = $list_judul->fetch_assoc()): ?>
                                                                    <li>
                                                                        <?= htmlspecialchars($j['judul']) ?>
                                                                        <small class="badge badge-<?= $j['status'] == 'Menunggu' ? 'warning' : ($j['status'] == 'Diproses' ? 'info' : 'success') ?>">
                                                                            <?= $j['status'] ?>
                                                                        </small>
                                                                    </li>
                                                                <?php endwhile; ?>
                                                            </ol>
                                                        <?php else: ?>
                                                            <em class="text-muted">Belum ada konsultasi</em>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                                <tr class="bg-light font-weight-bold">
                                                    <td colspan="2" class="text-right">TOTAL:</td>
                                                    <td class="text-center">
                                                        <?= $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Menunggu'")->fetch_row()[0] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Diproses'")->fetch_row()[0] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Selesai'")->fetch_row()[0] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= $total_konsultasi ?>
                                                    </td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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

    <script>
        // Data untuk chart
        const dataKategori = <?= json_encode($data_kategori) ?>;
        const dataJumlah = <?= json_encode($data_jumlah) ?>;
        const warnaChart = <?= json_encode($warna_chart) ?>;

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: dataKategori,
                datasets: [{
                    data: dataJumlah,
                    backgroundColor: warnaChart,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 10
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Konsultasi per Kategori',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: dataKategori,
                datasets: [{
                    label: 'Jumlah Konsultasi',
                    data: dataJumlah,
                    backgroundColor: warnaChart,
                    borderColor: warnaChart,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Jenis Masalah ASN per Kategori',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
    </script>

    <!-- Style khusus untuk print -->
    <style>
        @media print {
            .no-print { display: none !important; }
            .card-header button { display: none !important; }
            .breadcrumb { display: none !important; }
            .main-sidebar, .main-header, .main-footer { display: none !important; }
            .content-wrapper { margin-left: 0 !important; }
        }

        /* Print header */
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            body {
                background: white !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                page-break-inside: avoid;
            }
        }
    </style>
</body>
</html>
