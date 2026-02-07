<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Riwayat Konsulta";
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
                            <h1 class="m-0 text-dark">Riwayat Konsultasi</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Riwayat Konsultasi</li>
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
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <form method="get" class="form-inline mb-2 mb-md-0">
                                            <div class="form-group mr-2 mb-2 mb-md-0">
                                                <input type="text" name="filter" class="form-control" placeholder="Cari NIP atau Nama" value="<?= isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : '' ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">Cari</button>
                                            <a href="index.php" class="btn btn-secondary mr-2 mb-2 mb-md-0">Reset</a>
                                            <button type="button" class="btn btn-info mr-2 mb-2 mb-md-0" data-toggle="modal" data-target="#modalFilterPrintRiwayat"><i class="fa fa-print"> Print Semua</i></button>
                                        </form>
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
                                        <table  class="table table-bordered table-striped">
                                            <thead>
                                                <tr align="center">
                                                    <th>No</th>
                                                    <th>NIP</th>
                                                    <th>NIK</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Email</th>
                                                    <th>Satker</th>
                                                    <th>Jabatan</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $no = 1;
                                            $filter = '';
                                            if (isset($_GET['filter']) && $_GET['filter'] !== '') {
                                                $filter = $koneksi->real_escape_string($_GET['filter']);
                                                $query = "SELECT p.*, s.satker FROM pegawai p LEFT JOIN satker s ON p.id_satker = s.id_satker WHERE p.nip LIKE '%$filter%' OR p.nama_lengkap LIKE '%$filter%' ORDER BY p.nip DESC";
                                            } else {
                                                $query = "SELECT p.*, s.satker FROM pegawai p LEFT JOIN satker s ON p.id_satker = s.id_satker ORDER BY p.nip DESC";
                                            }
                                            $data = $koneksi->query($query);
                                            while ($row = $data->fetch_array()) {
                                            ?>
                                                <tr>
                                                    <td align="center"><?= $no++ ?></td>
                                                    <td><?= $row['nip'] ?></td>
                                                    <td><?= $row['nik'] ?></td>
                                                    <td><?= $row['nama_lengkap'] ?></td>
                                                    <td><?= $row['email'] ?></td>
                                                    <td><?= $row['satker'] ?></td>
                                                    <td><?= $row['jabatan'] ?></td>
                                                    <td align="center">
                                                        <a href="printriwayat.php?nip=<?= urlencode($row['nip']) ?>" target="blank" class="btn btn-info btn-sm" title="Print Riwayat">
                                                            <i class="fa fa-print"></i>
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

    <!-- Modal Filter Print Riwayat (Semua Pegawai) -->
    <div class="modal fade" id="modalFilterPrintRiwayat" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Filter Tanggal Cetak Riwayat (Semua)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="print.php" method="get" target="_blank">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Awal (Respon)</label>
                            <input type="date" name="tanggal_awal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir (Respon)</label>
                            <input type="date" name="tanggal_akhir" class="form-control" required>
                        </div>
                        <input type="hidden" name="filter" value="<?= isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : '' ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
