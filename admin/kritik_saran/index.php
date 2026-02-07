<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Kritik & Saran";
?>
<!DOCTYPE html>
<html>
<?php include '../../templates/head.php'; ?>

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
                                    <h3 class="card-title">Daftar Kritik & Saran</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn bg-dark" data-toggle="modal" data-target="#modalFilterPrintKritik"><i class="fa fa-print"> Cetak</i></button>
                                        <button type="button" class="btn bg-success" data-toggle="modal" data-target="#modalFilterLaporanKepuasan"><i class="fa fa-chart-bar"> Cetak Rata Rata Laporan Kepuasan ASN</i></button>
                                    </div>
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
                                                $data = $koneksi->query("
                                                    SELECT * FROM kritik_saran ORDER BY id_kritik_saran DESC
                                                ");
                                                while ($row = $data->fetch_array()) {
                                                ?>
                                                    <tr>
                                                        <td align="center"><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                                        <td><?= htmlspecialchars($row['instansi']) ?></td>
                                                        <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                                        <td><?= htmlspecialchars($row['kritik']) ?></td>
                                                        <td><?= htmlspecialchars($row['saran']) ?></td>
                                                        <td>
                                                            <?php
                                                            $stars = '';
                                                            switch($row['penilaian']) {
                                                                case 'Sangat Baik': $stars = '⭐⭐⭐⭐⭐'; break;
                                                                case 'Baik': $stars = '⭐⭐⭐⭐'; break;
                                                                case 'Cukup': $stars = '⭐⭐⭐'; break;
                                                                case 'Kurang': $stars = '⭐⭐'; break;
                                                                case 'Sangat Kurang': $stars = '⭐'; break;
                                                            }
                                                            ?>
                                                            <span class="badge badge-info"><?= $stars ?></span><br>
                                                            <small><?= htmlspecialchars($row['penilaian']) ?></small>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['kontak']) ?></td>
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

    <!-- Modal Filter Print Kritik & Saran -->
    <div class="modal fade" id="modalFilterPrintKritik" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">Filter Tanggal Cetak Kritik & Saran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="print.php" method="get" target="_blank">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark"><i class="fa fa-print"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Filter Laporan Kepuasan ASN -->
    <div class="modal fade" id="modalFilterLaporanKepuasan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">Filter Tanggal Laporan Kepuasan ASN</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="cetak.php" method="get" target="_blank">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-chart-bar"></i> Cetak Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
