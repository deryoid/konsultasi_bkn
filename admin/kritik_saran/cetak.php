<?php
require '../../config/config.php';
require '../../config/koneksi.php';

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
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kritik & Saran</title>
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
            justify-content: space-around;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-item {
            text-align: center;
        }

        .info-item h3 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .info-item p {
            font-size: 11px;
            color: #666;
            margin: 0;
        }

        .penilaian-summary {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .penilaian-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .penilaian-summary th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
        }

        .penilaian-summary td {
            padding: 8px;
            border: 1px solid #ddd;
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
        .badge-primary { background: #007bff; color: white; }
        .badge-info { background: #17a2b8; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-danger { background: #dc3545; color: white; }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
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

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }

            .info-box {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop">
        <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" alt="Logo BKN">
        <h1>LAPORAN KRITIK & SARAN ASN</h1>
        <h2>BADAN KEPEGAWAIAN NEGARA</h2>
        <p>Tahun: <?= date('Y') ?></p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="info-box">
        <div class="info-item">
            <h3><?= $stats_penilaian['avg_bintang'] ?> ⭐</h3>
            <p>Rata-rata Kepuasan</p>
        </div>
        <div class="info-item">
            <h3><?= $stats_penilaian['total'] ?></h3>
            <p>Total Respon</p>
        </div>
        <div class="info-item">
            <h3>⭐⭐⭐⭐⭐</h3>
            <p>Sangat Baik: <?= $stats_penilaian['sangat_baik'] ?></p>
        </div>
        <div class="info-item">
            <h3>⭐⭐⭐⭐</h3>
            <p>Baik: <?= $stats_penilaian['baik'] ?></p>
        </div>
    </div>

    <!-- Ringkasan Penilaian -->
    <div class="penilaian-summary">
        <h3 style="margin-bottom: 10px;">Ringkasan Penilaian</h3>
        <table>
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
                    if ($label == 'Sangat Baik') $bintang = '⭐⭐⭐⭐⭐';
                    elseif ($label == 'Baik') $bintang = '⭐⭐⭐⭐';
                    elseif ($label == 'Cukup') $bintang = '⭐⭐⭐';
                    elseif ($label == 'Kurang') $bintang = '⭐⭐';
                    else $bintang = '⭐';
                ?>
                <tr>
                    <td><?= $bintang ?> <?= $label ?></td>
                    <td class="text-center"><?= $count ?></td>
                    <td>
                        <?= $pct ?>%
                    </td>
                </tr>
                    <?php $index++; endforeach; ?>
                <tr style="background: #e8e8e8; font-weight: bold;">
                    <td colspan="2" class="text-right">TOTAL</td>
                    <td class="text-center"><?= $stats_penilaian['total'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tabel Data -->
    <?php if ($all_data->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">Tanggal</th>
                <th width="20%">Nama</th>
                <th width="12%">Instansi</th>
                <th width="12%">Jabatan</th>
                <th width="10%">Penilaian</th>
                <th width="17%">Kritik</th>
                <th width="17%">Saran</th>
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
                <td align="center"><?= $no++ ?></td>
                <td>
                    <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                    <?php if (!empty($row['nip'])): ?>
                    <br><small>NIP: <?= $row['nip'] ?></small>
                    <?php endif; ?>
                </td>
                <td><strong><?= $row['nama_lengkap'] ?></strong></td>
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
    <?php else: ?>
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data kritik & saran.</p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p><strong>LAPORAN KRITIK & SARAN ASN</strong></p>
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
