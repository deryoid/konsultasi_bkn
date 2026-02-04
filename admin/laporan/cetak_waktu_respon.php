<?php
require '../../config/config.php';
require '../../config/koneksi.php';

// Filter tanggal
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$tipe_filter = isset($_GET['tipe']) ? $_GET['tipe'] : 'hari_ini';

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
    'total_respon' => $data_respon->num_rows,
    'avg_hari' => 0,
    'avg_jam' => 0,
    'max_hari' => 0,
    'min_hari' => 0,
];

$total_hari = 0;
$total_jam = 0;
$max_hari = 0;
$min_hari = PHP_INT_MAX;

if ($data_respon->num_rows > 0) {
    $all_data = [];
    while ($row = $data_respon->fetch_assoc()) {
        $all_data[] = $row;
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
    }

    $stats['avg_hari'] = round($total_hari / count($all_data), 1);
    $stats['avg_jam'] = round($total_jam / count($all_data), 1);
    $stats['max_hari'] = $max_hari;
    $stats['min_hari'] = $min_hari == PHP_INT_MAX ? 0 : $min_hari;

    // Reset pointer
    $data_respon->data_seek(0);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Waktu Respon</title>
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

        .kop {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }

        .kop img {
            width: 80px;
            height: 100px;
            margin-bottom: 10px;
        }

        .kop h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }

        .kop h2 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #666;
        }

        .kop p {
            font-size: 12px;
            color: #999;
        }

        .info-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-item {
            text-align: center;
        }

        .info-item h3 {
            font-size: 20px;
            color: #667eea;
            margin-bottom: 2px;
        }

        .info-item p {
            font-size: 11px;
            color: #666;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #555;
            font-size: 11px;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success { background: #28a745; color: white; }
        .badge-info { background: #17a2b8; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-secondary { background: #6c757d; color: white; }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                size: landscape;
                margin: 1cm;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop">
        <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" alt="Logo BKN">
        <h1>LAPORAN WAKTU RESPON KONSULTASI</h1>
        <h2>BADAN KEPEGAWAIAN NEGARA</h2>
        <p>Periode: <?= $label_tanggal ?></p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="info-box">
        <div class="info-item">
            <h3><?= $stats['total_respon'] ?></h3>
            <p>Total Respon</p>
        </div>
        <div class="info-item">
            <h3><?= $stats['avg_hari'] ?> Hari</h3>
            <p>Rata-rata</p>
        </div>
        <div class="info-item">
            <h3><?= $stats['min_hari'] ?> Hari</h3>
            <p>Tercepat</p>
        </div>
        <div class="info-item">
            <h3><?= $stats['max_hari'] ?> Hari</h3>
            <p>Terlama</p>
        </div>
    </div>

    <!-- Tabel Data -->
    <?php if ($data_respon->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">ID Konsultasi</th>
                <th width="18%">Pegawai</th>
                <th width="8%">Kategori</th>
                <th width="22%">Judul</th>
                <th width="10%">Tgl Pengajuan</th>
                <th width="10%">Tgl Respon</th>
                <th width="10%">Waktu Respon</th>
                <th width="7%">Status</th>
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

                // Format detail waktu
                if ($hari > 0) {
                    $detail_waktu = $hari . " hari " . ($jam % 24) . " jam";
                } else {
                    $detail_waktu = $jam . " jam " . ($menit % 60) . " menit";
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
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['id_konsultasi'] ?></td>
                <td>
                    <strong><?= $row['nama_lengkap'] ?></strong><br>
                    <small style="color: #666;"><?= $row['nip'] ?></small>
                </td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['judul'] ?></td>
                <td>
                    <?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?><br>
                    <small style="color: #666;"><?= date('H:i', strtotime($row['tanggal_pengajuan'])) ?></small>
                </td>
                <td>
                    <?= date('d/m/Y', strtotime($row['tanggal_respon'])) ?><br>
                    <small style="color: #666;"><?= date('H:i', strtotime($row['tanggal_respon'])) ?></small>
                </td>
                <td>
                    <span class="badge <?= $badge_class ?>">
                        <?= $waktu_respon ?>
                    </span><br>
                    <small style="color: #666;"><?= $detail_waktu ?></small>
                </td>
                <td align="center">
                    <span class="badge <?= $status_class ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data respon untuk periode yang dipilih.</p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p><strong>LAPORAN WAKTU RESPON KONSULTASI PEGAWAI</strong></p>
        <p>Badan Kepegawaian Negara - Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi</p>
        <p>&copy; <?= date('Y') ?> All rights reserved.</p>
        <p>Dicetak: <?= date('d/m/Y H:i:s') ?></p>
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
