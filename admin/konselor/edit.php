<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Konselor";

// Ambil id konselor dari parameter GET
$id = isset($_GET['id']) ? $koneksi->real_escape_string($_GET['id']) : '';

// Ambil data konselor berdasarkan id
$data = $koneksi->query("SELECT * FROM konselor WHERE id_konselor = '$id'");
$row = $data->fetch_assoc();

// Ambil daftar user role Konselor (yang belum ditautkan ATAU yang sudah ditautkan ke konselor ini)
$users_konselor = $koneksi->query("
    SELECT u.id_user, u.nama_user, u.username 
    FROM user u
    WHERE u.role = 'Konselor'
    AND (
        u.id_user NOT IN (SELECT id_user FROM konselor WHERE id_user IS NOT NULL)
        OR u.id_user = '" . (int)($row['id_user'] ?? 0) . "'
    )
    ORDER BY u.nama_user
");
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
                                                <input type="text" class="form-control" name="nama_konselor"
                                                    value="<?= htmlspecialchars($row['nama_konselor'])?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jabatan Konselor</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="jabatan_konselor"
                                                    value="<?= htmlspecialchars($row['jabatan_konselor'])?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Keahlian</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="keahlian"
                                                    value="<?= htmlspecialchars($row['keahlian'])?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email"
                                                    value="<?= htmlspecialchars($row['email'] ?? '')?>" 
                                                    placeholder="email@contoh.com">
                                                <small class="form-text text-muted">
                                                    Email digunakan untuk mengirim notifikasi pengajuan konsultasi baru.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="status" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Aktif" <?=$row['status']=='Aktif' ? 'selected' : ''?>>Aktif</option>
                                                    <option value="Tidak Aktif" <?=$row['status']=='Tidak Aktif'
                                                        ? 'selected' : ''?>>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Akun User (Login) <small
                                                    class="text-muted">opsional</small></label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="id_user">
                                                    <option value="">-- Belum Ditautkan --</option>
                                                    <?php while ($u = $users_konselor->fetch_assoc()): ?>
                                                    <option value="<?= $u['id_user']?>"
                                                        <?=($row['id_user']==$u['id_user']) ? 'selected' : ''?>>
                                                        <?= htmlspecialchars($u['nama_user'])?> (
                                                        <?= htmlspecialchars($u['username'])?>)
                                                    </option>
                                                    <?php
endwhile; ?>
                                                </select>
                                                <small class="text-muted">Pilih akun user ber-role "Konselor" untuk
                                                    ditautkan. Konselor dapat login menggunakan akun ini.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: white;">
                                        <a href="<?= base_url('admin/konselor/')?>"
                                            class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left">
                                                Batal</i></a>
                                        <button type="submit" name="submit"
                                            class="btn bg-gradient-primary float-right mr-2"><i class="fa fa-save">
                                                Simpan</i></button>
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
    $nama_konselor = $koneksi->real_escape_string($_POST['nama_konselor']);
    $jabatan_konselor = $koneksi->real_escape_string($_POST['jabatan_konselor']);
    $keahlian = $koneksi->real_escape_string($_POST['keahlian']);
    $email = $koneksi->real_escape_string($_POST['email'] ?? '');
    $status = $koneksi->real_escape_string($_POST['status']);
    $id_user = !empty($_POST['id_user']) ? (int)$_POST['id_user'] : null;

    $id_user_val = is_null($id_user) ? 'NULL' : "'$id_user'";
    $submit = $koneksi->query("UPDATE konselor SET nama_konselor='$nama_konselor', jabatan_konselor='$jabatan_konselor', keahlian='$keahlian', email='$email', status='$status', id_user=$id_user_val WHERE id_konselor='$id'");

    if ($submit) {
        $_SESSION['pesan'] = "Data Berhasil Diubah";
        echo "<script>window.location.replace('../konselor/');</script>";
    }
}
?>
</body>

</html>