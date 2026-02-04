<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Kritik & Saran";
?>
<!DOCTYPE html>
<html>
<?php
include '../../templates/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../../templates/navbar.php';
        ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        include '../../templates/sidebar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Kritik & Saran</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Kritik & Saran</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline">
                                <div class="card-header">
                                    <a href="print.php" target="blank" class="btn bg-dark"><i class="fa fa-print"> Cetak</i></a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
                                    ?>
                                        <div class="alert alert-info alertinfo" role="alert">
                                            <i class="fa fa-check-circle"> <?= $_SESSION['pesan']; ?></i>
                                        </div>
                                    <?php
                                        $_SESSION['pesan'] = '';
                                    }
                                    ?>

                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr align="center">
                                                    <th width="5%">No</th>
                                                    <th width="10%">NIP</th>
                                                    <th width="12%">Nama Lengkap</th>
                                                    <th width="12%">Instansi</th>
                                                    <th width="12%">Jabatan</th>
                                                    <th width="15%">Kritik</th>
                                                    <th width="15%">Saran</th>
                                                    <th width="8%">Penilaian</th>
                                                    <th width="8%">Kontak</th>
                                                    <th width="7%">Tanggal</th>
                                                    <th width="6%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                           <?php
                                                $no = 1;
                                                $data = $koneksi->query("
                                                    SELECT * FROM kritik_saran ORDER BY id_kritik_saran DESC
                                                ");
                                                while ($row = $data->fetch_array()) {
                                                    // Tentukan jumlah bintang berdasarkan penilaian
                                                    $stars = '';
                                                    $badge_class = '';
                                                    switch($row['penilaian']) {
                                                        case 'Sangat Baik':
                                                            $stars = '⭐⭐⭐⭐⭐';
                                                            $badge_class = 'badge-success';
                                                            break;
                                                        case 'Baik':
                                                            $stars = '⭐⭐⭐⭐';
                                                            $badge_class = 'badge-primary';
                                                            break;
                                                        case 'Cukup':
                                                            $stars = '⭐⭐⭐';
                                                            $badge_class = 'badge-info';
                                                            break;
                                                        case 'Kurang':
                                                            $stars = '⭐⭐';
                                                            $badge_class = 'badge-warning';
                                                            break;
                                                        case 'Sangat Kurang':
                                                            $stars = '⭐';
                                                            $badge_class = 'badge-danger';
                                                            break;
                                                        default:
                                                            $stars = '-';
                                                            $badge_class = 'badge-secondary';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td align="center"><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($row['nip'] ?? '-') ?></td>
                                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                                        <td><?= htmlspecialchars($row['instansi']) ?></td>
                                                        <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                                        <td><?= htmlspecialchars($row['kritik']) ?></td>
                                                        <td><?= htmlspecialchars($row['saran']) ?></td>
                                                        <td>
                                                            <span class="badge <?= $badge_class ?>"><?= $stars ?></span><br>
                                                            <small><?= htmlspecialchars($row['penilaian']) ?></small>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['kontak']) ?></td>
                                                        <td>
                                                            <?php
                                                            // Tampilkan tanggal jika ada created_at atau tanggal field
                                                            if (isset($row['tanggal'])) {
                                                                echo date('d/m/Y', strtotime($row['tanggal']));
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td align="center">
                                                            <a href="hapus.php?id=<?= $row['id_kritik_saran'] ?>"
                                                               class="btn btn-danger btn-sm btn-hapus"
                                                               title="Hapus"
                                                               onclick="return confirm('Yakin ingin menghapus kritik & saran dari <?= htmlspecialchars($row['nama_lengkap']) ?>?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once "../../templates/footer.php"; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <?php include_once "../../templates/script.php"; ?>


</body>

</html>
