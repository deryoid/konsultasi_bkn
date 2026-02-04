<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Kritik & Saran";

$nip = $_SESSION['nip'] ?? '';

// Handle submit kritik & saran
if (isset($_POST['submit'])) {
    $nama_lengkap = $koneksi->real_escape_string($_POST['nama_lengkap']);
    $instansi     = $koneksi->real_escape_string($_POST['instansi']);
    $jabatan      = $koneksi->real_escape_string($_POST['jabatan']);
    $nip_post     = $koneksi->real_escape_string($_POST['nip']);
    $kritik       = $koneksi->real_escape_string($_POST['kritik']);
    $saran        = $koneksi->real_escape_string($_POST['saran']);
    $penilaian    = $koneksi->real_escape_string($_POST['penilaian']);
    $kontak       = $koneksi->real_escape_string($_POST['kontak']);

    $sql = "INSERT INTO kritik_saran (nama_lengkap, instansi, jabatan, nip, kritik, saran, penilaian, kontak, tanggal)
            VALUES ('$nama_lengkap', '$instansi', '$jabatan', '$nip_post', '$kritik', '$saran', '$penilaian', '$kontak', CURDATE())";

    if ($koneksi->query($sql)) {
        $_SESSION['pesan'] = "Kritik & Saran berhasil dikirim!";
        echo "<script>window.location.replace('index.php');</script>";
    } else {
        $error = "Gagal mengirim kritik & saran.";
    }
}

// Ambil data pegawai
$data_pegawai = $koneksi->query("SELECT * FROM pegawai WHERE nip = '" . $koneksi->real_escape_string($nip) . "'")->fetch_assoc();

// Ambil daftar kritik & saran dari user ini
$kritik_list = $koneksi->query("SELECT * FROM kritik_saran WHERE nip = '" . $koneksi->real_escape_string($nip) . "' ORDER BY id_kritik_saran DESC");
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
                            <h1 class="m-0 text-dark">Kritik & Saran</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Kritik & Saran</li>
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
                        <!-- Form Kritik & Saran -->
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i> Form Kritik & Saran
                                    </h3>
                                </div>
                                <form method="POST">
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            Silakan berikan kritik dan saran Anda untuk meningkatkan kualitas pelayanan.
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_lengkap"
                                                   value="<?= htmlspecialchars($data_pegawai['nama_lengkap'] ?? '') ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Instansi</label>
                                            <input type="text" class="form-control" name="instansi"
                                                   value="Badan Kepegawaian Negara" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <input type="text" class="form-control" name="jabatan"
                                                   value="<?= htmlspecialchars($data_pegawai['jabatan'] ?? '') ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Kontak (Email)</label>
                                            <input type="email" class="form-control" name="kontak"
                                                   value="<?= htmlspecialchars($data_pegawai['email'] ?? '') ?>">
                                            <small class="form-text text-muted">Email untuk menghubungi jika diperlukan</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Kritik <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="kritik" rows="3" required
                                                      placeholder="Tuliskan kritik Anda..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Saran <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="saran" rows="3" required
                                                      placeholder="Tuliskan saran Anda..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Penilaian <span class="text-danger">*</span></label>
                                            <select class="form-control" name="penilaian" required>
                                                <option value="">-- Pilih Penilaian --</option>
                                                <option value="Sangat Baik">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                                <option value="Baik">⭐⭐⭐⭐ Baik</option>
                                                <option value="Cukup">⭐⭐⭐ Cukup</option>
                                                <option value="Kurang">⭐⭐ Kurang</option>
                                                <option value="Sangat Kurang">⭐ Sangat Kurang</option>
                                            </select>
                                        </div>

                                        <input type="hidden" name="nip" value="<?= htmlspecialchars($nip) ?>">
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Kirim Kritik & Saran
                                        </button>
                                        <a href="../index.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Daftar Kritik & Saran -->
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-list"></i> Riwayat Kritik & Saran Anda
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($kritik_list->num_rows > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th width="15%">Penilaian</th>
                                                        <th width="30%">Kritik</th>
                                                        <th width="30%">Saran</th>
                                                        <th width="12%">Tanggal</th>
                                                        <th width="8%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; while ($row = $kritik_list->fetch_array()): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td>
                                                                <?php
                                                                $stars = '';
                                                                $badge_class = '';
                                                                switch($row['penilaian']) {
                                                                    case 'Sangat Baik':
                                                                        $stars = '⭐⭐⭐⭐⭐';
                                                                        $badge_class = 'badge-success';
                                                                        break;
                                                                    case 'Baik':
                                                                        $stars = '⭐⭐⭐⭐';
                                                                        $badge_class = 'badge-primary';
                                                                        break;
                                                                    case 'Cukup':
                                                                        $stars = '⭐⭐⭐';
                                                                        $badge_class = 'badge-info';
                                                                        break;
                                                                    case 'Kurang':
                                                                        $stars = '⭐⭐';
                                                                        $badge_class = 'badge-warning';
                                                                        break;
                                                                    case 'Sangat Kurang':
                                                                        $stars = '⭐';
                                                                        $badge_class = 'badge-danger';
                                                                        break;
                                                                }
                                                                ?>
                                                                <span class="badge <?= $badge_class ?>"><?= $stars ?></span><br>
                                                                <small><?= htmlspecialchars($row['penilaian']) ?></small>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['kritik']) ?></td>
                                                            <td><?= htmlspecialchars($row['saran']) ?></td>
                                                            <td>
                                                                <?php
                                                                if (!empty($row['tanggal'])) {
                                                                    echo date('d/m/Y', strtotime($row['tanggal']));
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a href="hapus.php?id=<?= $row['id_kritik_saran'] ?>"
                                                                   class="btn btn-danger btn-sm btn-hapus"
                                                                   title="Hapus"
                                                                   onclick="return confirm('Yakin ingin menghapus kritik & saran ini?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-inbox"></i>
                                            Belum ada kritik & saran yang Anda kirim.
                                        </div>
                                    <?php endif; ?>
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
