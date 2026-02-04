<?php
require '../../config/config.php';
require '../../config/koneksi.php';
$title = "Edit Profil";

$nip = $_SESSION['nip'] ?? '';

// Ambil data pegawai
$data_pegawai = $koneksi->query("SELECT * FROM pegawai WHERE nip = '" . $koneksi->real_escape_string($nip) . "'")->fetch_assoc();

// Handle upload file
if (isset($_POST['upload_file'])) {
    if (isset($_FILES['file_pendukung']) && $_FILES['file_pendukung']['error'] == 0) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $filename = $_FILES['file_pendukung']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Buat nama file unik
            $new_filename = 'SK_' . $nip . '_' . time() . '.' . $ext;
            $upload_path = '../../uploads/pegawai/';

            // Buat folder jika belum ada
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            if (move_uploaded_file($_FILES['file_pendukung']['tmp_name'], $upload_path . $new_filename)) {
                // Update database
                $stmt = $koneksi->prepare("UPDATE pegawai SET file_pendukung = ?, file_pendukung_name = ? WHERE nip = ?");
                $stmt->bind_param("sss", $new_filename, $filename, $nip);

                if ($stmt->execute()) {
                    $_SESSION['pesan'] = "File berhasil diunggah!";
                    echo "<script>window.location.replace('edit.php');</script>";
                } else {
                    $error = "Gagal menyimpan ke database!";
                }
                $stmt->close();
            } else {
                $error = "Gagal mengunggah file!";
            }
        } else {
            $error = "Tipe file tidak diizinkan! Hanya PDF, DOC, DOCX, JPG, JPEG, PNG.";
        }
    } else {
        $error = "Pilih file terlebih dahulu!";
    }
}

// Handle hapus file
if (isset($_POST['delete_file'])) {
    $stmt = $koneksi->prepare("UPDATE pegawai SET file_pendukung = NULL, file_pendukung_name = NULL WHERE nip = ?");
    $stmt->bind_param("s", $nip);

    if ($stmt->execute()) {
        // Hapus file fisik jika ada
        if (!empty($data_pegawai['file_pendukung']) && file_exists('../../uploads/pegawai/' . $data_pegawai['file_pendukung'])) {
            unlink('../../uploads/pegawai/' . $data_pegawai['file_pendukung']);
        }
        $_SESSION['pesan'] = "File berhasil dihapus!";
        echo "<script>window.location.replace('edit.php');</script>";
    }
    $stmt->close();
}
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
                    <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fa fa-check"> <?= $_SESSION['pesan']; ?></i>
                        </div>
                    <?php $_SESSION['pesan'] = ''; } ?>

                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fa fa-exclamation-triangle"> <?= $error; ?></i>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <!-- Informasi Pegawai -->
                        <div class="col-md-6">
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
                                            <td><?= htmlspecialchars($data_pegawai['nip'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>NIK</th>
                                            <td><?= htmlspecialchars($data_pegawai['nik'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td><?= htmlspecialchars($data_pegawai['nama_lengkap'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?= htmlspecialchars($data_pegawai['email'] ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td><?= htmlspecialchars($data_pegawai['jabatan'] ?? '') ?></td>
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
                                </div>
                            </div>
                        </div>

                        <!-- Upload File SK & Dokumen Pendukung -->
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-upload"></i> Dokumen Pegawai
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Unggah SK Pegawai atau dokumen pendukung lainnya (PDF, DOC, DOCX, JPG, PNG). Maksimal 5MB.
                                    </div>

                                    <!-- File Saat Ini -->
                                    <?php if (!empty($data_pegawai['file_pendukung'])): ?>
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-check-circle"></i> File Saat Ini:</h6>
                                        <p class="mb-2">
                                            <i class="fas fa-file"></i>
                                            <strong><?= htmlspecialchars($data_pegawai['file_pendukung_name'] ?? $data_pegawai['file_pendukung']) ?></strong>
                                        </p>
                                        <div class="btn-group">
                                            <a href="<?= base_url('uploads/pegawai/' . $data_pegawai['file_pendukung']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="<?= base_url('uploads/pegawai/' . $data_pegawai['file_pendukung']) ?>" download class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                            <form method="POST" class="d-inline">
                                                <button type="submit" name="delete_file" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus file ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Belum ada file yang diunggah.
                                    </div>
                                    <?php endif; ?>

                                    <hr>

                                    <!-- Form Upload -->
                                    <h6>Upload File Baru</h6>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Pilih File</label>
                                            <input type="file" name="file_pendukung" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                            <small class="form-text text-muted">
                                                Format yang diizinkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 5MB.
                                            </small>
                                        </div>

                                        <button type="submit" name="upload_file" class="btn btn-success">
                                            <i class="fas fa-upload"></i> Upload File
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubah Password -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Ubah Password</h3>
                                </div>

                                <div class="card-body">
                                    <p class="text-muted">Silakan buat password yang kuat dengan minimal 6 karakter.</p>

                                    <form method="POST" action="ubahpw.php">
                                        <div class="form-group" style="max-width: 400px;">
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
