<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Respon Konsultasi";
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
                            <h1 class="m-0 text-dark">Respon Konsultasi</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Respon Konsultasis</li>
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
                                    <a href="tambah.php" class="btn bg-blue"><i class="fa fa-plus-circle"> Respon Konsultasi</i></a>
                                    <a href="print.php" target="blank" class="btn bg-dark"><i class="fa fa-print"> Cetak Daftar Respon</i></a>
                                    <a href="../laporan/waktu_respon.php" class="btn bg-warning"><i class="fa fa-clock"> Laporan Waktu Respon</i></a>
                                    <a href="../laporan/cetak_waktu_respon.php" target="_blank" class="btn bg-success"><i class="fa fa-file-alt"> Cetak Laporan Waktu</i></a>
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
                                                    <th>ID Konsultasi</th>
                                                    <th>NIP</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Nama Konselor</th>
                                                    <th>Isi Respon</th>
                                                    <th>Tanggal Respon</th>
                                                    <th>Lampiran</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                           <?php
                                                $no = 1;
                                                $where = '';
                                                $warn = '';
                                                if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
                                                    $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
                                                    if ($check && $check->num_rows > 0) {
                                                        $uid = $koneksi->real_escape_string($_SESSION['id_user']);
                                                        $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$uid' LIMIT 1");
                                                        if ($map && $map->num_rows > 0) {
                                                            $mk = $map->fetch_assoc();
                                                            $idk = $koneksi->real_escape_string($mk['id_konselor']);
                                                            $where = "WHERE r.id_konselor = '".$idk."'";
                                                        } else {
                                                            $warn = 'Akun Konselor belum dipetakan ke data konselor (tabel konselor_user). Hubungi Admin.';
                                                            $where = "WHERE 1=0";
                                                        }
                                                    } else {
                                                        $warn = 'Tabel konselor_user belum tersedia. Admin perlu membuat pemetaan akun ke konselor.';
                                                        $where = "WHERE 1=0";
                                                    }
                                                }
                                                if ($warn) {
                                                    echo '<div class="alert alert-warning">'.htmlspecialchars($warn).'</div>';
                                                }
                                                $sql = "
                                                    SELECT r.*, k.nip, p.nama_lengkap, kr.nama_konselor
                                                    FROM respon_konsultasi r
                                                    LEFT JOIN konsultasi k ON r.id_konsultasi = k.id_konsultasi
                                                    LEFT JOIN konselor kr ON kr.id_konselor = r.id_konselor
                                                    LEFT JOIN pegawai p ON k.nip = p.nip
                                                    " . $where . "
                                                    ORDER BY r.tanggal_respon DESC
                                                ";
                                                $data = $koneksi->query($sql);
                                                while ($row = $data->fetch_array()) {
                                                ?>
                                                    <tr>
                                                        <td align="center"><?= $no++ ?></td>
                                                        <td><?= $row['id_konsultasi'] ?></td>
                                                        <td><?= $row['nip'] ?></td>
                                                        <td><?= $row['nama_lengkap'] ?></td>
                                                        <td><?= $row['nama_konselor'] ?></td>
                                                        <td><?= $row['isi_respon'] ?></td>
                                                        <td><?= $row['tanggal_respon'] ?></td>
                                                        <td>
                                                            <?php if ($row['lampiran_respon']) { ?>
                                                                <a href="../../uploads/<?= $row['lampiran_respon'] ?>" target="_blank">Lihat Lampiran</a>
                                                            <?php } else { echo '-'; } ?>
                                                        </td>
                                                        <td align="center">
                                                            <a href="edit.php?id=<?= $row['id_respon_konsultasi'] ?>" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                            <a href="hapus.php?id=<?= $row['id_respon_konsultasi'] ?>" class="btn btn-danger btn-sm alert-hapus" title="Hapus"><i class="fa fa-trash"></i></a>
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
