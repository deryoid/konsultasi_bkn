<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$title = "Daftar Konsultasi";

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
                            <h1 class="m-0 text-dark">Daftar Konsultasi Saya</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Konsultasi</li>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-list mr-1"></i>
                                        Semua Konsultasi
                                    </h3>
                                    <div class="card-tools">
                                        <a href="tambah" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Buat Baru
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th width="10%">ID</th>
                                                    <th width="15%">Kategori</th>
                                                    <th width="25%">Judul</th>
                                                    <th width="12%">Tanggal</th>
                                                    <th width="10%">Status</th>
                                                    <th width="10%">Respon</th>
                                                    <th width="18%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = $koneksi->query("SELECT k.*, kat.nama_kategori
                                                    FROM konsultasi k
                                                    LEFT JOIN kategori kat ON k.id_kategori = kat.id_kategori
                                                    WHERE k.nip = '$nip'
                                                    ORDER BY k.tanggal_pengajuan DESC");

                                                if ($query->num_rows > 0) {
                                                    while ($row = $query->fetch_array()) {
                                                        // Cek apakah ada respon
                                                        $cek_respon = $koneksi->query("SELECT COUNT(*) as total FROM respon_konsultasi WHERE id_konsultasi = '".$row['id_konsultasi']."'")->fetch_array();

                                                        // Status badge color
                                                        $status_class = '';
                                                        switch($row['status']) {
                                                            case 'Baru': $status_class = 'badge-warning'; break;
                                                            case 'Diterima': $status_class = 'badge-success'; break;
                                                            case 'Selesai': $status_class = 'badge-secondary'; break;
                                                            case 'Proses': $status_class = 'badge-info'; break;
                                                            default: $status_class = 'badge-secondary';
                                                        }
                                                ?>
                                                <tr>
                                                    <td><?= $row['id_konsultasi'] ?></td>
                                                    <td><?= $row['nama_kategori'] ?></td>
                                                    <td><?= htmlspecialchars($row['judul']) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                                                    <td>
                                                        <span class="badge <?= $status_class ?>"><?= $row['status'] ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($cek_respon['total'] > 0): ?>
                                                            <span class="badge badge-success"><?= $cek_respon['total'] ?> Respon</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Belum</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="detail.php?id=<?= $row['id_konsultasi'] ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if ($cek_respon['total'] > 0): ?>
                                                            <a href="cetak.php?id=<?= $row['id_konsultasi'] ?>" target="_blank" class="btn btn-primary btn-sm" title="Cetak">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <i class="fas fa-inbox fa-3x text-muted"></i>
                                                        <p class="text-muted">Belum ada konsultasi</p>
                                                        <a href="tambah" class="btn btn-primary">
                                                            <i class="fas fa-plus"></i> Buat Konsultasi
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include_once "../../templates/footer.php"; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php include_once "../../templates/script.php"; ?>

    <!-- DataTables -->
    <script src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
</body>
</html>
