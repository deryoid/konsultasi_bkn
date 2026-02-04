<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$id_konsultasi = $_GET['id'];

// Ambil data konsultasi
$query = $koneksi->query("SELECT k.*, kat.nama_kategori, p.nama_lengkap
    FROM konsultasi k
    LEFT JOIN kategori kat ON k.id_kategori = kat.id_kategori
    LEFT JOIN pegawai p ON k.nip = p.nip
    WHERE k.id_konsultasi = '$id_konsultasi' AND k.nip = '".$_SESSION['nip']."'");

$data = $query->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!');window.location.href='../index.php';</script>";
    exit();
}

// Ambil respon konsultasi
$query_respon = $koneksi->query("SELECT r.*, kn.nama_konselor, kn.jabatan_konselor
    FROM respon_konsultasi r
    LEFT JOIN konselor kn ON r.id_konselor = kn.id_konselor
    WHERE r.id_konsultasi = '$id_konsultasi'
    ORDER BY r.tanggal_respon DESC");

$title = "Detail Konsultasi";
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
                            <h1 class="m-0 text-dark">Detail Konsultasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Info Konsultasi -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        Informasi Konsultasi
                                    </h3>
                                    <div class="card-tools">
                                        <?php
                                        $status_class = '';
                                        switch($data['status']) {
                                            case 'Baru': $status_class = 'badge-warning'; break;
                                            case 'Diterima': $status_class = 'badge-success'; break;
                                            case 'Selesai': $status_class = 'badge-secondary'; break;
                                            case 'Proses': $status_class = 'badge-info'; break;
                                            default: $status_class = 'badge-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $status_class ?>"><?= $data['status'] ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="20%">ID Konsultasi</th>
                                            <td><?= $data['id_konsultasi'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kategori</th>
                                            <td><?= $data['nama_kategori'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Judul</th>
                                            <td><?= $data['judul'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            <td><?= $data['nama_lengkap'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>NIP</th>
                                            <td><?= $data['nip'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td><?= date('d/m/Y', strtotime($data['tanggal_pengajuan'])) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <td><?= nl2br($data['deskripsi']) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <a href="../index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <?php if ($data['status'] == 'Selesai' || $query_respon->num_rows > 0): ?>
                                        <button onclick="window.print()" class="btn btn-primary">
                                            <i class="fas fa-print"></i> Cetak
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Respon Konselor -->
                    <?php if ($query_respon->num_rows > 0): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-comments mr-1"></i>
                                        Respon Konselor
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php while ($respon = $query_respon->fetch_assoc()): ?>
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm" src="<?= base_url() ?>/assets/dist/img/user1-128x128.jpg" alt="user image">
                                            <span class="username">
                                                <a href="#"><?= $respon['nama_konselor'] ?></a>
                                            </span>
                                            <span class="description"><?= $respon['jabatan_konselor'] ?></span>
                                        </div>
                                        <p>
                                            <?= nl2br($respon['isi_respon']) ?>
                                        </p>
                                        <p>
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($respon['tanggal_respon'])) ?>
                                            </small>
                                        </p>
                                        <?php if ($respon['lampiran_respon']): ?>
                                        <p>
                                            <a href="<?= base_url('uploads/' . $respon['lampiran_respon']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-paperclip"></i> Lampiran
                                            </a>
                                        </p>
                                        <?php endif; ?>
                                        <hr>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i>
                                Belum ada respon dari konselor. Silakan tunggu informasi lebih lanjut.
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <?php include_once "../../templates/footer.php"; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <?php include_once "../../templates/script.php"; ?>
</body>
</html>
