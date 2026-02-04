<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Konsultasi";

// Ambil data pegawai untuk dropdown NIP
$pegawai = [];
$sql_pegawai = $koneksi->query("SELECT nip, nama_lengkap FROM pegawai ORDER BY nama_lengkap ASC");
while ($row = $sql_pegawai->fetch_assoc()) {
    $pegawai[] = $row;
}

// Ambil data kategori untuk dropdown
$kategori = [];
$sql_kategori = $koneksi->query("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
while ($row = $sql_kategori->fetch_assoc()) {
    $kategori[] = $row;
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
                            <h1 class="m-0 text-dark">Konsultasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Konsultasi</li>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Tambah Konsultasi</h3>
                                    </div>
                                    <div class="card-body" style="background-color: white;">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Konsultasi</label>
                                            <div class="col-sm-10">
                                                <?php
                                                // Ambil kode terakhir dari database
                                                $result = $koneksi->query("SELECT id_konsultasi FROM konsultasi ORDER BY id_konsultasi DESC LIMIT 1");
                                                $lastKode = $result->fetch_assoc();
                                                if ($lastKode && preg_match('/KNS00(\d+)/', $lastKode['id_konsultasi'], $matches)) {
                                                    $nextNumber = (int)$matches[1] + 1;
                                                } else {
                                                    $nextNumber = 1;
                                                }
                                                $kode_konsultasi = 'KNS00' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                                                ?>
                                                <input type="text" class="form-control" name="id_konsultasi" value="<?= htmlspecialchars($kode_konsultasi) ?>" readonly required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="nip" required>
                                                    <option value="">-- Pilih Pegawai --</option>
                                                    <?php foreach ($pegawai as $p): ?>
                                                        <option value="<?= htmlspecialchars($p['nip']) ?>">
                                                            <?= htmlspecialchars($p['nip']) ?> - <?= htmlspecialchars($p['nama_lengkap']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kategori</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="id_kategori" required>
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
                                            <label class="col-sm-2 col-form-label">Judul</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="judul" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tanggal Pengajuan</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tanggal_pengajuan" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="status">
                                                    <option value="Baru" selected>Baru</option>
                                                    <option value="Diterima">Diterima</option>
                                                    <option value="Ditolak">Ditolak</option>
                                                    <option value="Proses">Proses</option>
                                                    <option value="Selesai">Selesai</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Deskripsi</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                                                <small class="form-text text-muted">
                                                    <b>Notice:</b> Deskripsi ini diisi ketika status di <b>Proses</b>.<br>
                                                    Contoh: Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tanggal Respon</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tanggal_respon" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="background-color: white;">
                                        <a href="<?= base_url('admin/konsultasi/') ?>" class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left"> Batal</i></a>
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
        $id_konsultasi               = $koneksi->real_escape_string($_POST['id_konsultasi']);
        $nip               = $koneksi->real_escape_string($_POST['nip']);
        $id_kategori       = $koneksi->real_escape_string($_POST['id_kategori']);
        $judul             = $koneksi->real_escape_string($_POST['judul']);
        $tanggal_pengajuan = $koneksi->real_escape_string($_POST['tanggal_pengajuan']);
        $status            = 'Baru'; // Status default
        $deskripsi         = $koneksi->real_escape_string($_POST['deskripsi']);
        $tanggal_respon         = $koneksi->real_escape_string($_POST['tanggal_respon']);

        $submit = $koneksi->query("INSERT INTO konsultasi (id_konsultasi, nip, id_kategori, judul, tanggal_pengajuan, status, deskripsi, tanggal_respon) VALUES ('$id_konsultasi', '$nip', '$id_kategori', '$judul', '$tanggal_pengajuan', '$status', '$deskripsi','$tanggal_respon')");

        if ($submit) {
            $_SESSION['pesan'] = "Data Berhasil Ditambahkan";
            echo "<script>window.location.replace('../konsultasi/');</script>";
        }
    }
    ?>
</body>
</html>
