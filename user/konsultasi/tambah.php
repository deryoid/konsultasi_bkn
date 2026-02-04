<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Buat Konsultasi";

// Ambil data kategori untuk dropdown
$kategori = [];
$sql_kategori = $koneksi->query("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
while ($row = $sql_kategori->fetch_assoc()) {
    $kategori[] = $row;
}

// NIP dari session
$nip = $_SESSION['nip'];
?>
<!DOCTYPE html>
<html>
<?php include '../../templates/head.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include '../../templates/navbar.php'; ?>
        <?php include '../../templates/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Buat Konsultasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Konsultasi</li>
                                <li class="breadcrumb-item active">Buat</li>
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
                            <i class="icon fa fa-check"></i> <?= $_SESSION['pesan']; ?>
                        </div>
                    <?php $_SESSION['pesan'] = ''; } ?>

                    <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Form Pengajuan Konsultasi</h3>
                                    </div>
                                    <div class="card-body" style="background-color: white;">
                                        <!-- Info Pegawai -->
                                        <div class="alert alert-info">
                                            <strong>Informasi Pegawai:</strong><br>
                                            NIP: <?= $nip ?><br>
                                            Nama: <?= $_SESSION['nama_lengkap'] ?>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Konsultasi</label>
                                            <div class="col-sm-10">
                                                <?php
                                                // Ambil kode terakhir dari database
                                                $result = $koneksi->query("SELECT id_konsultasi FROM konsultasi ORDER BY id_konsultasi DESC LIMIT 1");
                                                $lastKode = $result->fetch_assoc();
                                                if ($lastKode && preg_match('/KNS(\d+)/', $lastKode['id_konsultasi'], $matches)) {
                                                    $nextNumber = (int)$matches[1] + 1;
                                                } else {
                                                    $nextNumber = 1;
                                                }
                                                $kode_konsultasi = 'KNS' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                                                ?>
                                                <input type="text" class="form-control" name="id_konsultasi" value="<?= htmlspecialchars($kode_konsultasi) ?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kategori</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="id_kategori" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <?php foreach ($kategori as $k): ?>
                                                        <option value="<?= htmlspecialchars($k['id_kategori']) ?>">
                                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Judul Konsultasi</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="judul" placeholder="Masukkan judul konsultasi" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tanggal Pengajuan</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Deskripsi</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="deskripsi" rows="5" placeholder="Jelaskan detail konsultasi Anda..." required></textarea>
                                                <small class="form-text text-muted">
                                                    Jelaskan secara detail masalah atau pertanyaan yang ingin Anda konsultasikan
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: white;">
                                        <a href="../index.php" class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left"> Batal</i></a>
                                        <button type="submit" name="submit" class="btn bg-gradient-primary float-right mr-2"><i class="fa fa-paper-plane"> Kirim</i></button>
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

    <!-- Select2 -->
    <script src="<?= base_url() ?>/assets/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>

    <?php
    if (isset($_POST['submit'])) {
        $id_konsultasi  = $koneksi->real_escape_string($_POST['id_konsultasi']);
        $id_kategori    = $koneksi->real_escape_string($_POST['id_kategori']);
        $judul          = $koneksi->real_escape_string($_POST['judul']);
        $tanggal_pengajuan = $koneksi->real_escape_string($_POST['tanggal_pengajuan']);
        $status         = 'Baru'; // Status default
        $deskripsi      = $koneksi->real_escape_string($_POST['deskripsi']);
        $tanggal_respon = date('Y-m-d'); // Default tanggal respon

        $submit = $koneksi->query("INSERT INTO konsultasi (id_konsultasi, nip, id_kategori, judul, tanggal_pengajuan, status, deskripsi, tanggal_respon) VALUES ('$id_konsultasi', '$nip', '$id_kategori', '$judul', '$tanggal_pengajuan', '$status', '$deskripsi', '$tanggal_respon')");

        if ($submit) {
            $_SESSION['pesan'] = "Konsultasi berhasil dikirim! Kode: $id_konsultasi";
            echo "<script>window.location.replace('../index.php');</script>";
        } else {
            echo "<script>alert('Gagal mengirim konsultasi!');</script>";
        }
    }
    ?>
</body>
</html>
