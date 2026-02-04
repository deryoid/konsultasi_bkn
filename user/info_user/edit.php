<?php
require '../../config/config.php';
require '../../config/koneksi.php';
$title = "Edit Profil";
?>
<!DOCTYPE html>
<html>
<?php
include '../../templates/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include '../../templates/navbar.php'; ?>
        <?php include '../../templates/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Edit Profil</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Profil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Informasi Profil</h3>
                                </div>

                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Data pegawai diambil dari database master BKN. Untuk mengubah data pegawai, silakan hubungi admin.
                                    </div>

                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="25%">NIP</th>
                                            <td><?= htmlspecialchars($_SESSION['nip'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>NIK</th>
                                            <td><?= htmlspecialchars($_SESSION['nik'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?= htmlspecialchars($_SESSION['email'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td><?= htmlspecialchars($_SESSION['jabatan'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Username</th>
                                            <td><?= htmlspecialchars($_SESSION['nip'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td><span class="badge badge-info"><?= $_SESSION['role'] ?? '' ?></span></td>
                                        </tr>
                                    </table>

                                    <hr>

                                    <h4>Ubah Password</h4>
                                    <p class="text-muted">Silakan buat password yang kuat dengan minimal 6 karakter.</p>

                                    <form method="POST" action="ubahpw.php">
                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Masukkan password baru (minimal 6 karakter)" required>
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" name="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Password
                                            </button>
                                            <a href="index.php" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Kembali
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include_once "../../templates/footer.php"; ?>
    </div>

    <?php include_once "../../templates/script.php"; ?>
</body>
</html>