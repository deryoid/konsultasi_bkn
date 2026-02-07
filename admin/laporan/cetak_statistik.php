<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Cetak Laporan Statistik Kategori";

$id = isset($_GET['id']) ? $_GET['id'] : '';

// Ambil parameter filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Build filter query
$where_tanggal = 'WHERE 1=1';
$filter_info = '';
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $tanggal_awal_safe = $koneksi->real_escape_string($tanggal_awal);
    $tanggal_akhir_safe = $koneksi->real_escape_string($tanggal_akhir);
    $where_tanggal .= " AND k.tanggal_pengajuan BETWEEN '$tanggal_awal_safe' AND '$tanggal_akhir_safe'";

    $tgl_awal_fmt = date('d/m/Y', strtotime($tanggal_awal));
    $tgl_akhir_fmt = date('d/m/Y', strtotime($tanggal_akhir));
    $filter_info = "Periode Pengajuan: $tgl_awal_fmt s/d $tgl_akhir_fmt";
}

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
    $where_tanggal
    GROUP BY c.id_kategori
    ORDER BY jumlah DESC
");

$total_konsultasi = $koneksi->query("SELECT COUNT(*) FROM konsultasi $where_tanggal")->fetch_row()[0];
$total_menunggu = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Menunggu' $where_tanggal")->fetch_row()[0];
$total_diproses = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Diproses' $where_tanggal")->fetch_row()[0];
$total_selesai = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Selesai' $where_tanggal")->fetch_row()[0];

// Ambil data judul per kategori
$detail_kategori = [];
$stats_result = $koneksi->query("
    SELECT
        c.id_kategori,
        c.nama_kategori,
        COUNT(k.id_konsultasi) as jumlah
    FROM kategori c
    LEFT JOIN konsultasi k ON c.id_kategori = k.id_kategori
    $where_tanggal
    GROUP BY c.id_kategori
    ORDER BY jumlah DESC
");

while ($row = $stats_result->fetch_assoc()) {
    $id_kategori = $row['id_kategori'];

    // Ambil judul-judul di kategori ini
    $list_judul = $koneksi->query("
        SELECT judul, status, DATE_FORMAT(tanggal_pengajuan, '%d/%m/%Y') as tanggal
        FROM konsultasi
        WHERE id_kategori = '$id_kategori' $where_tanggal
        ORDER BY tanggal_pengajuan DESC
    ");

    $juduls = [];
    while ($j = $list_judul->fetch_assoc()) {
        $juduls[] = $j;
    }

    $row['detail'] = $juduls;
    $detail_kategori[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Statistik Kategori</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .summary-item {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
            border: 1px solid #ddd;
        }

        .summary-item h3 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .summary-item p {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        table th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #555;
        }

        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .total-row {
            background: #e8e8e8 !important;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-warning { background: #ffc107; color: #000; }
        .badge-info { background: #17a2b8; color: white; }
        .badge-success { background: #28a745; color: white; }
        .badge-primary { background: #007bff; color: white; }

        .kategori-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .kategori-title {
            background: #f0f0f0;
            padding: 10px;
            font-weight: bold;
            border-left: 4px solid #667eea;
            margin-bottom: 10px;
        }

        .judul-list {
            list-style: none;
            padding-left: 0;
        }

        .judul-list li {
            padding: 8px;
            border-bottom: 1px solid #eee;
            margin-bottom: 5px;
        }

        .judul-list li:last-child {
            border-bottom: none;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
            page-break-inside: avoid;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                size: landscape;
                margin: 1cm;
            }

            .summary-box {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo base_url('assets/dist/img/LOGO_BKN.png') ?>" alt="Logo BKN" style="width: 60px; height: 75px; margin-bottom: 10px;">
        <h1>LAPORAN STATISTIK KATEGORI KONSULTASI</h1>
        <p>Badan Kepegawaian Negara</p>
        <p>Cetak: <?php echo date('d/m/Y H:i') ?></p>
        <?php if (!empty($filter_info)): ?>
        <p style="color: #667eea; font-weight: bold;"><?= $filter_info ?></p>
        <?php endif; ?>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="summary-box">
        <div class="summary-item">
            <h3><?php echo $stats->num_rows ?></h3>
            <p>Total Kategori</p>
        </div>
        <div class="summary-item">
            <h3><?php echo $total_konsultasi ?></h3>
            <p>Total Konsultasi</p>
        </div>
        <div class="summary-item">
            <h3><?php echo $total_menunggu ?></h3>
            <p>Menunggu</p>
        </div>
        <div class="summary-item">
            <h3><?php echo $total_diproses ?></h3>
            <p>Diproses</p>
        </div>
        <div class="summary-item">
            <h3><?php echo $total_selesai ?></h3>
            <p>Selesai</p>
        </div>
    </div>

    <!-- Tabel Statistik -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Kategori</th>
                <th width="10%" class="text-center">Menunggu</th>
                <th width="10%" class="text-center">Diproses</th>
                <th width="10%" class="text-center">Selesai</th>
                <th width="10%" class="text-center">Total</th>
                <th width="30%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stats->data_seek(0);
            $no = 1;
            while ($row = $stats->fetch_assoc()):
                $persentase = $total_konsultasi > 0 ? round(($row['jumlah'] / $total_konsultasi) * 100, 1) : 0;
            ?>
            <tr>
                <td><?php echo $no++ ?></td>
                <td><strong><?php echo htmlspecialchars($row['nama_kategori']) ?></strong></td>
                <td class="text-center"><?php echo $row['menunggu'] ?></td>
                <td class="text-center"><?php echo $row['diproses'] ?></td>
                <td class="text-center"><?php echo $row['selesai'] ?></td>
                <td class="text-center"><strong><?php echo $row['jumlah'] ?></strong></td>
                <td>
                    <div style="background: #eee; border-radius: 3px; height: 20px; position: relative;">
                        <div style="background: #667eea; height: 100%; border-radius: 3px; width: <?php echo $persentase ?>%;"></div>
                        <span style="position: absolute; top: 2px; left: 5px; font-size: 10px;"><?php echo $persentase ?>%</span>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL:</td>
                <td class="text-center"><?php echo $total_menunggu ?></td>
                <td class="text-center"><?php echo $total_diproses ?></td>
                <td class="text-center"><?php echo $total_selesai ?></td>
                <td class="text-center"><?php echo $total_konsultasi ?></td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>

    <!-- Detail Judul per Kategori -->
    <h2 style="margin: 30px 0 20px 0; font-size: 16px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Rangkuman Judul Konsultasi per Kategori</h2>

    <?php foreach ($detail_kategori as $kategori): ?>
        <?php if (count($kategori['detail']) > 0): ?>
        <div class="kategori-section">
            <div class="kategori-title">
                <?php echo $kategori['nama_kategori'] ?> (<?php echo count($kategori['detail']) ?> konsultasi)
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="65%">Judul Konsultasi</th>
                        <th width="20%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no_judul = 1;
                    foreach ($kategori['detail'] as $judul):
                        $badge_class = 'badge-secondary';
                        switch($judul['status']) {
                            case 'Menunggu': $badge_class = 'badge-warning'; break;
                            case 'Diproses': $badge_class = 'badge-info'; break;
                            case 'Selesai': $badge_class = 'badge-success'; break;
                        }
                    ?>
                    <tr>
                        <td><?php echo $no_judul++ ?></td>
                        <td><?php echo $judul['tanggal'] ?></td>
                        <td><?php echo htmlspecialchars($judul['judul']) ?></td>
                        <td><span class="badge <?php echo $badge_class ?>"><?php echo $judul['status'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Grafik Sederhana -->
    <h2 style="margin: 30px 0 20px 0; font-size: 16px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Grafik Distribusi Kategori</h2>
    <table>
        <?php
        $stats->data_seek(0);
        $max_jumlah = 0;
        while ($row = $stats->fetch_assoc()) {
            if ($row['jumlah'] > $max_jumlah) {
                $max_jumlah = $row['jumlah'];
            }
        }

        $stats->data_seek(0);
        while ($row = $stats->fetch_assoc()):
            $bar_width = $max_jumlah > 0 ? ($row['jumlah'] / $max_jumlah) * 100 : 0;
        ?>
        <tr>
            <td width="30%"><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
            <td width="50%">
                <div style="background: #eee; border-radius: 3px; height: 25px; position: relative;">
                    <div style="background: #667eea; height: 100%; border-radius: 3px; width: <?php echo $bar_width; ?>%;"></div>
                </div>
            </td>
            <td width="10%" class="text-center"><strong><?php echo $row['jumlah']; ?></strong></td>
            <td width="10%" class="text-center">
                <?php echo $total_konsultasi > 0 ? round(($row['jumlah'] / $total_konsultasi) * 100, 1) : 0; ?>%
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak secara otomatis dari Sistem Konsultasi Pegawai BKN</p>
        <p>&copy; <?php echo date('Y') ?> Badan Kepegawaian Negara. All rights reserved.</p>
        <p>Halaman 1 dari 1</p>
    </div>

    <script>
        // Auto print saat halaman dimuat
        window.onload = function() {
            window.print();
        };

        // Tutup window setelah print
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
