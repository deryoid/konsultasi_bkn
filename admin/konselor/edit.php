<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Konselor";

// Ambil id konselor dari parameter GET
$id = isset($_GET['id']) ? $koneksi->real_escape_string($_GET['id']) : '';

// Ambil data konselor berdasarkan id
$data = $koneksi->query("SELECT * FROM konselor WHERE id_konselor = '$id'");
$row = $data->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<?php include '../../templates/head.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../../templates/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include '../../templates/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Konselor</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Konselor</li>
                                <li class="breadcrumb-item active">Edit Data</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Konselor</h3>
                                    </div>
                                    <div class="card-body" style="background-color: white;">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Konselor</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nama_konselor" value="<?= htmlspecialchars($row['nama_konselor']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jabatan Konselor</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="jabatan_konselor" value="<?= htmlspecialchars($row['jabatan_konselor']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Keahlian</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="keahlian" value="<?= htmlspecialchars($row['keahlian']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="status" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                                    <option value="Tidak Aktif" <?= $row['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: white;">
                                        <a href="<?= base_url('admin/konselor/') ?>" class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left"> Batal</i></a>
                                        <button type="submit" name="submit" class="btn bg-gradient-primary float-right mr-2"><i class="fa fa-save"> Simpan</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <?php include_once "../../templates/footer.php"; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <?php include_once "../../templates/script.php"; ?>

    <?php
    if (isset($_POST['submit'])) {
        $nama_konselor    = $koneksi->real_escape_string($_POST['nama_konselor']);
        $jabatan_konselor = $koneksi->real_escape_string($_POST['jabatan_konselor']);
        $keahlian         = $koneksi->real_escape_string($_POST['keahlian']);
        $status           = $koneksi->real_escape_string($_POST['status']);

        $submit = $koneksi->query("UPDATE konselor SET nama_konselor='$nama_konselor', jabatan_konselor='$jabatan_konselor', keahlian='$keahlian', status='$status' WHERE id_konselor='$id'");

        if ($submit) {
            $_SESSION['pesan'] = "Data Berhasil Diubah";
            echo "<script>window.location.replace('../konselor/');</script>";
        }
    }
    ?>
</body>
</html>
