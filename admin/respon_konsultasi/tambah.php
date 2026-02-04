<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Respon Konsultasi";

// Ambil data konsultasi JOIN pegawai
$konsultasi = [];
$sql_konsultasi = $koneksi->query("
    SELECT k.id_konsultasi, k.judul, p.nip, p.nama_lengkap
    FROM konsultasi k
    JOIN pegawai p ON k.nip = p.nip
    ORDER BY k.id_konsultasi DESC
");
while ($row = $sql_konsultasi->fetch_assoc()) {
    $konsultasi[] = $row;
}

// Ambil data konselor dari tabel konselor
$nama_konselor = [];
$sql_konselor = $koneksi->query("SELECT id_konselor, nama_konselor FROM konselor ORDER BY nama_konselor ASC");
while ($row = $sql_konselor->fetch_assoc()) {
    $nama_konselor[] = [
        'id_konselor' => $row['id_konselor'],
        'nama_konselor' => $row['nama_konselor']
    ];
}

// Jika role Konselor, kunci konselor sesuai mapping
$konselor_lock_id = null;
$konselor_lock_nama = null;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
    $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
    if ($check && $check->num_rows > 0) {
        $uid = $koneksi->real_escape_string($_SESSION['id_user']);
        $map = $koneksi->query("SELECT ku.id_konselor, k.nama_konselor FROM konselor_user ku JOIN konselor k ON k.id_konselor = ku.id_konselor WHERE ku.id_user = '$uid' LIMIT 1");
        if ($map && $map->num_rows > 0) {
            $mk = $map->fetch_assoc();
            $konselor_lock_id = $mk['id_konselor'];
            $konselor_lock_nama = $mk['nama_konselor'];
        }
    }
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
                            <li class="breadcrumb-item active">Tambah Data</li>
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
                                    <h3 class="card-title mb-0">Tambah Respon Konsultasi</h3>
                                </div>
                                <div class="card-body bg-white">

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Konsultasi (ID - NIP - Nama - Judul)</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="id_konsultasi" required>
                                                <option value="">-- Pilih Konsultasi --</option>
                                                <?php foreach ($konsultasi as $k): ?>
                                                    <option value="<?= htmlspecialchars($k['id_konsultasi']) ?>">
                                                        <?= htmlspecialchars($k['id_konsultasi']) ?> - <?= htmlspecialchars($k['nip']) ?> - <?= htmlspecialchars($k['nama_lengkap']) ?> - <?= htmlspecialchars($k['judul']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Nama Konselor</label>
                                        <div class="col-sm-8">
                                            <?php if ($konselor_lock_id): ?>
                                                <input type="hidden" name="id_konselor" value="<?= htmlspecialchars($konselor_lock_id) ?>">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($konselor_lock_nama) ?>" readonly>
                                            <?php else: ?>
                                            <select class="form-control" name="id_konselor" required>
                                                <option value="">-- Pilih Konselor --</option>
                                                <?php foreach ($nama_konselor as $nk): ?>
                                                    <option value="<?= htmlspecialchars($nk['id_konselor']) ?>"><?= htmlspecialchars($nk['nama_konselor']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Isi Respon</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="isi_respon" rows="4" required></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Tanggal Respon</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="tanggal_respon" required>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Lampiran Respon</label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control" name="lampiran_respon" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="form-text text-muted">
                                                Upload file gambar (jpg, jpeg, png) atau PDF.
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
    // Pastikan semua field yang dibutuhkan ada dan tidak kosong
    $id_konsultasi  = isset($_POST['id_konsultasi']) ? trim($_POST['id_konsultasi']) : '';
    $id_konselor    = isset($_POST['id_konselor']) ? trim($_POST['id_konselor']) : '';
    $isi_respon     = isset($_POST['isi_respon']) ? trim($_POST['isi_respon']) : '';
    $tanggal_respon = isset($_POST['tanggal_respon']) ? trim($_POST['tanggal_respon']) : '';

    // Validasi field wajib
    if ($id_konsultasi === '' || $id_konselor === '' || $isi_respon === '' || $tanggal_respon === '') {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        $id_konsultasi  = $koneksi->real_escape_string($id_konsultasi);
        // Enforce konselor lock for Konselor role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor' && $konselor_lock_id) {
            $id_konselor = $konselor_lock_id;
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor' && !$konselor_lock_id) {
            die('Akun Konselor belum dipetakan ke data konselor. Hubungi Admin.');
        }
        $id_konselor    = $koneksi->real_escape_string($id_konselor);
        $isi_respon     = $koneksi->real_escape_string($isi_respon);
        $tanggal_respon = $koneksi->real_escape_string($tanggal_respon);

        // Upload file
        $lampiran_respon = '';
        if (isset($_FILES['lampiran_respon']) && $_FILES['lampiran_respon']['name']) {
            $allowed = ['jpg','jpeg','png','pdf'];
            $ext = strtolower(pathinfo($_FILES['lampiran_respon']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $lampiran_respon = uniqid('lampiran_') . '.' . $ext;
                move_uploaded_file($_FILES['lampiran_respon']['tmp_name'], '../../uploads/' . $lampiran_respon);
            }
        }

        $submit = $koneksi->query("INSERT INTO respon_konsultasi (id_respon_konsultasi, id_konsultasi, id_konselor, isi_respon, tanggal_respon, lampiran_respon) VALUES (NULL, '$id_konsultasi', '$id_konselor', '$isi_respon', '$tanggal_respon', '$lampiran_respon')");

        // Update status konsultasi menjadi 'Diproses' setelah ada respon
        $koneksi->query("UPDATE konsultasi SET status = 'Diproses' WHERE id_konsultasi = '$id_konsultasi'");

        if ($submit) {
            // Load Email Library
            require_once '../../config/email.php';
            $emailLib = new EmailLibrary($koneksi);

            // Ambil data konsultasi untuk notifikasi email
            $data_konsultasi = $koneksi->query("SELECT k.*, p.nama_lengkap, p.email FROM konsultasi k LEFT JOIN pegawai p ON k.nip = p.nip WHERE k.id_konsultasi = '$id_konsultasi'")->fetch_assoc();
            $data_konselor = $koneksi->query("SELECT nama_konselor FROM konselor WHERE id_konselor = '$id_konselor'")->fetch_assoc();

            // Kirim email notifikasi respon
            if ($data_konsultasi && !empty($data_konsultasi['email']) && $data_konselor) {
                $kirim_email = $emailLib->kirimNotifikasiRespon(
                    $data_konsultasi['email'],
                    $data_konsultasi['nama_lengkap'],
                    $id_konsultasi,
                    $data_konselor['nama_konselor'],
                    $isi_respon
                );
            }

            $_SESSION['pesan'] = "Data Berhasil Ditambahkan";
            echo "<script>window.location.replace('../respon_konsultasi/');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menyimpan data!');</script>";
        }
    }
}
?>
</body>
</html>
