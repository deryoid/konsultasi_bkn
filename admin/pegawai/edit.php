<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Pegawai";

$id = isset($_GET['id']) ? $koneksi->real_escape_string($_GET['id']) : '';
// Ambil data pegawai berdasarkan id
$data = $koneksi->query("SELECT * FROM pegawai WHERE nip = '$id'");
$row = $data->fetch_assoc();

// Ambil data satker untuk dropdown
$satker = [];
$sql_satker = $koneksi->query("SELECT id_satker, satker FROM satker ORDER BY satker ASC");
while ($s = $sql_satker->fetch_assoc()) {
    $satker[] = $s;
}
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
                            <h1 class="m-0 text-dark">Pegawai</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Pegawai</li>
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
                                        <h3 class="card-title">Pegawai</h3>
                                    </div>
                                    <div class="card-body" style="background-color: white;">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nip" value="<?= htmlspecialchars($row['nip']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIK</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nik" value="<?= htmlspecialchars($row['nik']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($row['nama_lengkap']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Satker</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="id_satker" required>
                                                    <option value="">-- Pilih Satker --</option>
                                                    <?php foreach ($satker as $s): ?>
                                                        <option value="<?= htmlspecialchars($s['id_satker']) ?>" <?= $row['id_satker'] == $s['id_satker'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($s['satker']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jabatan</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="jabatan" value="<?= htmlspecialchars($row['jabatan']) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: white;">
                                        <a href="<?= base_url('admin/pegawai/') ?>" class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left"> Batal</i></a>
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
        $nip          = $koneksi->real_escape_string($_POST['nip']);
        $nik          = $koneksi->real_escape_string($_POST['nik']);
        $nama_lengkap = $koneksi->real_escape_string($_POST['nama_lengkap']);
        $email        = $koneksi->real_escape_string($_POST['email']);
        $id_satker    = $koneksi->real_escape_string($_POST['id_satker']);
        $jabatan      = $koneksi->real_escape_string($_POST['jabatan']);

        $submit = $koneksi->query("UPDATE pegawai SET nip='$nip', nik='$nik', nama_lengkap='$nama_lengkap', email='$email', id_satker='$id_satker', jabatan='$jabatan' WHERE nip='$id'");

        if ($submit) {
            $_SESSION['pesan'] = "Data Berhasil Diubah";
            echo "<script>window.location.replace('../pegawai/');</script>";
        }
    }
    ?>
</body>
</html>
