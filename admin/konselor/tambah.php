<?php
require '../../config/config.php';
require '../../config/koneksi.php';

// Ambil daftar user dengan role Konselor yang belum ditautkan ke konselor manapun
$users_konselor = $koneksi->query("
    SELECT u.id_user, u.nama_user, u.username 
    FROM user u
    WHERE u.role = 'Konselor'
    AND u.id_user NOT IN (SELECT id_user FROM konselor WHERE id_user IS NOT NULL)
    ORDER BY u.nama_user
");

$title = "Konselor";
?>
<!DOCTYPE html>
<html>
<?php
include '../../templates/head.php';
?>

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
                                <li class="breadcrumb-item active">Tambah Data</li>
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
                                                <input type="text" class="form-control" name="nama_konselor" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jabatan Konselor</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="jabatan_konselor"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Keahlian</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="keahlian" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" placeholder="email@contoh.com">
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
                                                    <option value="Aktif">Aktif</option>
                                                    <option value="Tidak Aktif">Tidak Aktif</option>
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
                                                    <option value="<?= $u['id_user']?>">
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
    $id_user = !empty($_POST['id_user']) ? (int)$_POST['id_user'] : 'NULL';

    $id_user_val = ($id_user === 'NULL') ? 'NULL' : "'$id_user'";
    $submit = $koneksi->query("INSERT INTO konselor (id_konselor, nama_konselor, jabatan_konselor, keahlian, email, status, id_user) VALUES (NULL,'$nama_konselor', '$jabatan_konselor', '$keahlian', '$email', '$status', $id_user_val)");

    if ($submit) {
        $_SESSION['pesan'] = "Data Berhasil Ditambahkan";
        echo "<script>window.location.replace('../konselor/');</script>";
    }
}
?>
</body>

</html>