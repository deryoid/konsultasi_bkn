<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Konsultasi";
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
                            <h1 class="m-0 text-dark">Konsultasi</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Konsultasi</li>
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
                                    <a href="tambah.php" class="btn bg-blue"><i class="fa fa-plus-circle"> Tambah</i></a>
                                    <button type="button" class="btn bg-dark" data-toggle="modal" data-target="#modalFilterPrintKonsultasi"><i class="fa fa-print"> Cetak</i></button>
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
                                                    <th>ID KONSULTASI</th>
                                                    <th>Pegawai</th>
                                                    <th>Kategori</th>
                                                    <th>Judul</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Status</th>
                                                    <th>Deskripsi</th>
                                                    <th>Tanggal Respon</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $no = 1;
                                            $data = $koneksi->query("
                                                SELECT k.*, p.nama_lengkap, p.nip, p.nik, p.email, s.satker, p.jabatan, c.nama_kategori 
                                                FROM konsultasi k
                                                LEFT JOIN pegawai p ON k.nip = p.nip
                                                LEFT JOIN satker s ON p.id_satker = s.id_satker
                                                LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
                                                ORDER BY k.id_konsultasi DESC
                                            ");
                                            while ($row = $data->fetch_array()) {
                                            ?>
                                                <tr>
                                                    <td align="center"><?= $no++ ?></td>
                                                    <td><?= $row['id_konsultasi'] ?></td>
                                                    <td>
                                                        <div class="card" style="border:1px solid #007bff; border-radius:8px; padding:10px; background:#f8f9fa;">
                                                            <div style="font-weight:bold; color:#007bff; font-size:16px;"><?= $row['nama_lengkap'] ?></div>
                                                            <div style="font-size:13px;">
                                                                <span><strong>NIP:</strong> <?= $row['nip'] ?></span><br>
                                                                <span><strong>NIK:</strong> <?= $row['nik'] ?></span><br>
                                                                <span><strong>Email:</strong> <?= $row['email'] ?></span><br>
                                                                <span><strong>Satker:</strong> <?= $row['satker'] ?></span><br>
                                                                <span><strong>Jabatan:</strong> <?= $row['jabatan'] ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?= $row['nama_kategori'] ?></td>
                                                    <td><?= $row['judul'] ?></td>
                                                    <td><?= $row['tanggal_pengajuan'] ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = '';
                                                        switch($row['status']) {
                                                            case 'Menunggu': $status_class = 'badge-warning'; break;
                                                            case 'Diproses': $status_class = 'badge-info'; break;
                                                            case 'Selesai': $status_class = 'badge-success'; break;
                                                            default: $status_class = 'badge-secondary';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $status_class ?>"><?= $row['status'] ?></span>
                                                    </td>
                                                    <td><?= $row['deskripsi'] ?></td>
                                                    <td><?= $row['tanggal_respon'] ?></td>
                                                    <td align="center">
                                                        <a href="cetak_tiket.php?id=<?= $row['id_konsultasi'] ?>" class="btn btn-primary btn-sm" title="Cetak Tiket" target="_blank">
                                                            <i class="fa fa-ticket-alt"></i>
                                                        </a>
                                                        <a href="edit.php?id=<?= $row['id_konsultasi'] ?>" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a href="hapus.php?id=<?= $row['id_konsultasi'] ?>" class="btn btn-danger btn-sm alert-hapus" title="Hapus"><i class="fa fa-trash"></i></a>
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

    <!-- Modal Filter Print Konsultasi -->
    <div class="modal fade" id="modalFilterPrintKonsultasi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">Filter Tanggal Cetak Konsultasi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="print.php" method="get" target="_blank">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Awal (Pengajuan)</label>
                            <input type="date" name="tanggal_awal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir (Pengajuan)</label>
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

</body>

</html>
