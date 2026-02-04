<?php
require '../config/config.php';
require '../config/koneksi.php';

$title = "Dashboard";
?>
<!DOCTYPE html>
<html>
<?php
include '../templates/head.php';
?>

<style>
  #mapid {
    height: 580px;
    width: 100%;
  }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php
    include '../templates/navbar.php';
    ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php
    include '../templates/sidebar.php';
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard </li>
              </ol>
            </div>

          </div>
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->

          <div class="alert alert-light" role="alert">
            <h5><b>
            <img src="<?= base_url() ?>/assets/dist/img/LOGO_BKN.png" style="width: 50px;" alt="#" class="brand-image" style="opacity: .8">
                 SELAMAT DATANG <span class="btn btn-dark rounded-pill" style="font-size: 14px;"><?= $_SESSION['nama_lengkap'] ?> </span> SISTEM KONSULTASI PEGAWAI BKN
              </b></h5>
          </div>

          <!-- Info Pegawai -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-user-tie mr-1"></i> Informasi Pegawai
                  </h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <strong>NIP</strong>
                      <p class="text-muted"><?= $_SESSION['nip'] ?></p>
                    </div>
                    <div class="col-md-3">
                      <strong>NIK</strong>
                      <p class="text-muted"><?= $_SESSION['nik'] ?></p>
                    </div>
                    <div class="col-md-3">
                      <strong>Email</strong>
                      <p class="text-muted"><?= $_SESSION['email'] ?></p>
                    </div>
                    <div class="col-md-3">
                      <strong>Jabatan</strong>
                      <p class="text-muted"><?= $_SESSION['jabatan'] ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Statistik Konsultasi -->
          <?php
          $nip = $_SESSION['nip'];
          $stats = $koneksi->query("SELECT
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai
            FROM konsultasi WHERE nip = '$nip'")->fetch_array();
          ?>
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $stats['total'] ?></h3>
                  <p>Total Konsultasi</p>
                </div>
                <div class="icon">
                  <i class="fas fa-envelope"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $stats['menunggu'] ?></h3>
                  <p>Menunggu</p>
                </div>
                <div class="icon">
                  <i class="fas fa-clock"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $stats['diproses'] ?></h3>
                  <p>Diproses</p>
                </div>
                <div class="icon">
                  <i class="fas fa-spinner"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3><?= $stats['selesai'] ?></h3>
                  <p>Selesai</p>
                </div>
                <div class="icon">
                  <i class="fas fa-flag-checkered"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Daftar Konsultasi Terakhir -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i> Konsultasi Saya
                  </h3>
                  <div class="card-tools">
                    <a href="konsultasi/tambah.php" class="btn btn-primary btn-sm">
                      <i class="fas fa-plus"></i> Buat Konsultasi
                    </a>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Kategori</th>
                          <th>Judul</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $query = $koneksi->query("SELECT k.*, kat.nama_kategori
                          FROM konsultasi k
                          LEFT JOIN kategori kat ON k.id_kategori = kat.id_kategori
                          WHERE k.nip = '$nip'
                          ORDER BY k.tanggal_pengajuan DESC
                          LIMIT 5");
                        if ($query->num_rows > 0) {
                          while ($row = $query->fetch_array()) {
                        ?>
                        <tr>
                          <td><?= $row['id_konsultasi'] ?></td>
                          <td><?= $row['nama_kategori'] ?></td>
                          <td><?= $row['judul'] ?></td>
                          <td><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                          <td>
                            <?php
                            $status_class = '';
                            switch($row['status']) {
                              case 'Menunggu': $status_class = 'badge-warning'; break;
                              case 'Diproses': $status_class = 'badge-info'; break;
                              case 'Selesai': $status_class = 'badge-success'; break;
                              default: $status_class = 'badge-secondary';
                            }
                            ?>
                            <span class="badge <?= $status_class ?>"><?= $row['status'] ?></span>
                          </td>
                          <td>
                            <a href="konsultasi/detail.php?id=<?= $row['id_konsultasi'] ?>" class="btn btn-info btn-sm">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                        </tr>
                        <?php
                          }
                        } else {
                        ?>
                        <tr>
                          <td colspan="6" class="text-center">Belum ada konsultasi</td>
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
          <!-- /.row -->

        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
    <?php
    include '../templates/footer.php';
    ?>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- Script -->
  <?php
  include '../templates/script.php';
  ?>
</body>

</html>
