<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Respon Konsultasi";

// Ambil ID respon dari parameter GET
$id = isset($_GET['id']) ? $koneksi->real_escape_string($_GET['id']) : '';

// Ambil data respon_konsultasi yang akan diedit
$data = $koneksi->query("SELECT * FROM respon_konsultasi WHERE id_respon_konsultasi = '$id'");
$row = $data->fetch_assoc();

// Batasi akses Konselor hanya ke respon miliknya
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor' && $row) {
    $allowed = false;
    $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
    if ($check && $check->num_rows > 0) {
        $uid = $koneksi->real_escape_string($_SESSION['id_user']);
        $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$uid' LIMIT 1");
        if ($map && $map->num_rows > 0) {
            $mk = $map->fetch_assoc();
            if ($row['id_konselor'] == $mk['id_konselor']) {
                $allowed = true;
            }
        }
    }
    if (!$allowed) {
        die('Akses ditolak: Anda tidak berhak mengubah respon ini.');
    }
}

// Ambil data konsultasi JOIN pegawai untuk dropdown
$konsultasi = [];
$sql_konsultasi = $koneksi->query("
    SELECT k.id_konsultasi, k.judul, p.nip, p.nama_lengkap
    FROM konsultasi k
    JOIN pegawai p ON k.nip = p.nip
    ORDER BY k.id_konsultasi DESC
");
while ($r = $sql_konsultasi->fetch_assoc()) {
    $konsultasi[] = $r;
}

// Ambil data konselor dari tabel konselor
$nama_konselor = [];
$sql_konselor = $koneksi->query("SELECT id_konselor, nama_konselor FROM konselor ORDER BY nama_konselor ASC");
while ($r = $sql_konselor->fetch_assoc()) {
    $nama_konselor[] = [
        'id_konselor' => $r['id_konselor'],
        'nama_konselor' => $r['nama_konselor']
    ];
}
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
                        <h1 class="m-0 text-dark">Respon Konsultasi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Respon Konsultasi</li>
                            <li class="breadcrumb-item active">Edit Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">Edit Respon Konsultasi</h3>
                                </div>
                                <div class="card-body bg-white">

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Konsultasi (ID - NIP - Nama - Judul)</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="id_konsultasi" required>
                                                <option value="">-- Pilih Konsultasi --</option>
                                                <?php foreach ($konsultasi as $k): ?>
                                                    <option value="<?= htmlspecialchars($k['id_konsultasi']) ?>" <?= $row['id_konsultasi'] == $k['id_konsultasi'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($k['id_konsultasi']) ?> - <?= htmlspecialchars($k['nip']) ?> - <?= htmlspecialchars($k['nama_lengkap']) ?> - <?= htmlspecialchars($k['judul']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Nama Konselor</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $konselor_lock_id = null; $konselor_lock_nama = null;
                                            if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
                                                $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
                                                if ($check && $check->num_rows > 0) {
                                                    $uid = $koneksi->real_escape_string($_SESSION['id_user']);
                                                    $map = $koneksi->query("SELECT ku.id_konselor, k.nama_konselor FROM konselor_user ku JOIN konselor k ON k.id_konselor = ku.id_konselor WHERE ku.id_user = '$uid' LIMIT 1");
                                                    if ($map && $map->num_rows > 0) { $mk = $map->fetch_assoc(); $konselor_lock_id = $mk['id_konselor']; $konselor_lock_nama = $mk['nama_konselor']; }
                                                }
                                            }
                                            if ($konselor_lock_id) { ?>
                                                <input type="hidden" name="id_konselor" value="<?= htmlspecialchars($konselor_lock_id) ?>">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($konselor_lock_nama) ?>" readonly>
                                            <?php } else { ?>
                                                <select class="form-control" name="id_konselor" required>
                                                    <option value="">-- Pilih Konselor --</option>
                                                    <?php foreach ($nama_konselor as $nk): ?>
                                                        <option value="<?= htmlspecialchars($nk['id_konselor']) ?>" <?= $row['id_konselor'] == $nk['id_konselor'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($nk['nama_konselor']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Isi Respon</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="isi_respon" rows="4" required><?= htmlspecialchars($row['isi_respon']) ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Tanggal Respon</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="tanggal_respon" value="<?= htmlspecialchars($row['tanggal_respon']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Lampiran Respon</label>
                                        <div class="col-sm-8">
                                            <?php if ($row['lampiran_respon']): ?>
                                                <a href="../../uploads/<?= htmlspecialchars($row['lampiran_respon']) ?>" target="_blank">Lihat Lampiran</a><br>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="lampiran_respon" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="form-text text-muted">
                                                Upload file gambar (jpg, jpeg, png) atau PDF. Kosongkan jika tidak ingin mengubah lampiran.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="<?= base_url('admin/respon_konsultasi/') ?>" class="btn bg-gradient-secondary float-right ml-2">
                                        <i class="fa fa-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" name="submit" class="btn bg-gradient-primary float-right">
                                        <i class="fa fa-save"></i> Simpan
                                    </button>
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
    $id_konsultasi        = $koneksi->real_escape_string($_POST['id_konsultasi']);
    // Enforce konselor lock for Konselor role
    $id_konselor_post = isset($_POST['id_konselor']) ? $_POST['id_konselor'] : '';
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
        $lock_id = null;
        $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
        if ($check && $check->num_rows > 0) {
            $uid = $koneksi->real_escape_string($_SESSION['id_user']);
            $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$uid' LIMIT 1");
            if ($map && $map->num_rows > 0) { $mk = $map->fetch_assoc(); $lock_id = $mk['id_konselor']; }
        }
        if ($lock_id) { $id_konselor_post = $lock_id; }
    }
    $id_konselor          = $koneksi->real_escape_string($id_konselor_post);
    $isi_respon           = $koneksi->real_escape_string($_POST['isi_respon']);
    $tanggal_respon       = $koneksi->real_escape_string($_POST['tanggal_respon']);

    // Upload file jika ada
    $lampiran_respon = $row['lampiran_respon'];
    if ($_FILES['lampiran_respon']['name']) {
        $allowed = ['jpg','jpeg','png','pdf'];
        $ext = strtolower(pathinfo($_FILES['lampiran_respon']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            // Hapus file lama jika ada
            if ($lampiran_respon && file_exists('../../uploads/' . $lampiran_respon)) {
                unlink('../../uploads/' . $lampiran_respon);
            }
            $lampiran_respon = uniqid('lampiran_') . '.' . $ext;
            move_uploaded_file($_FILES['lampiran_respon']['tmp_name'], '../../uploads/' . $lampiran_respon);
        }
    }

    $submit = $koneksi->query("UPDATE respon_konsultasi SET 
        id_konsultasi='$id_konsultasi', 
        id_konselor='$id_konselor', 
        isi_respon='$isi_respon', 
        tanggal_respon='$tanggal_respon', 
        lampiran_respon='$lampiran_respon'
        WHERE id_respon_konsultasi='$id'
    ");

    if ($submit) {
        $_SESSION['pesan'] = "Data Berhasil Diubah";
        echo "<script>window.location.replace('../respon_konsultasi/');</script>";
    }
}
?>
</body>
</html>
