<?php
require '../config/config.php';
require '../config/koneksi.php';

$title = "Dashboard";

// Ambil data statistik
$total_pegawai = $koneksi->query("SELECT COUNT(*) FROM pegawai")->fetch_row()[0];
$total_konsultasi = $koneksi->query("SELECT COUNT(*) FROM konsultasi")->fetch_row()[0];
$total_menunggu = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Menunggu'")->fetch_row()[0];
$total_diproses = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Diproses'")->fetch_row()[0];
$total_selesai = $koneksi->query("SELECT COUNT(*) FROM konsultasi WHERE status = 'Selesai'")->fetch_row()[0];
$total_kategori = $koneksi->query("SELECT COUNT(*) FROM kategori")->fetch_row()[0];

// Data untuk grafik kategori
$stats_kategori = $koneksi->query("
    SELECT
        c.nama_kategori,
        COUNT(k.id_konsultasi) as jumlah
    FROM kategori c
    LEFT JOIN konsultasi k ON c.id_kategori = k.id_kategori
    GROUP BY c.id_kategori
    ORDER BY jumlah DESC
    LIMIT 5
");

$kategori_labels = [];
$kategori_data = [];
$kategori_colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

while ($row = $stats_kategori->fetch_assoc()) {
    $kategori_labels[] = $row['nama_kategori'];
    $kategori_data[] = $row['jumlah'];
}

// Konsultasi terbaru
$konsultasi_terbaru = $koneksi->query("
    SELECT k.*, p.nama_lengkap, c.nama_kategori
    FROM konsultasi k
    LEFT JOIN pegawai p ON k.nip = p.nip
    LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
    ORDER BY k.tanggal_pengajuan DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<?php
include '../templates/head.php';
?>



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
              <h1 class="m-0 text-dark">Dashboard Admin</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
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
                 SELAMAT DATANG ADMIN SISTEM KONSULTASI PEGAWAI BKN
              </b></h5>
          </div>

          <!-- Statistik Boxes -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $total_pegawai ?></h3>
                  <p>Total Pegawai</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $total_konsultasi ?></h3>
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
                  <h3><?= $total_menunggu ?></h3>
                  <p>Menunggu</p>
                </div>
                <div class="icon">
                  <i class="fas fa-clock"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= $total_selesai ?></h3>
                  <p>Selesai</p>
                </div>
                <div class="icon">
                  <i class="fas fa-flag-checkered"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Grafik dan Tabel -->
          <div class="row">
            <!-- Grafik Kategori -->
            <div class="col-md-6">
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Jenis Masalah ASN (Top 5)
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="kategoriChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>

            <!-- Distribusi Status -->
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i> Distribusi Status Konsultasi
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="statusChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Konsultasi Terbaru -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-list"></i> Konsultasi Terbaru
                  </h3>
                  <div class="card-tools">
                    <a href="laporan/waktu_respon.php" class="btn btn-info btn-sm">
                      <i class="fas fa-clock"></i> Waktu Respon
                    </a>
                    <a href="laporan/cetak_statistik.php" target="_blank" class="btn btn-dark btn-sm">
                      <i class="fas fa-print"></i> Cetak Statistik
                    </a>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Pegawai</th>
                          <th>Kategori</th>
                          <th>Judul</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($konsultasi_terbaru->num_rows > 0): ?>
                          <?php while ($row = $konsultasi_terbaru->fetch_assoc()): ?>
                            <tr>
                              <td><?= $row['id_konsultasi'] ?></td>
                              <td>
                                <strong><?= $row['nama_lengkap'] ?></strong><br>
                                <small class="text-muted"><?= $row['nip'] ?></small>
                              </td>
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
                            </tr>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">Belum ada konsultasi</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

  <script>
    // Data Kategori
    const kategoriLabels = <?= json_encode($kategori_labels) ?>;
    const kategoriData = <?= json_encode($kategori_data) ?>;
    const kategoriColors = <?= json_encode($kategori_colors) ?>;

    // Pie Chart Kategori
    const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
    const kategoriChart = new Chart(ctxKategori, {
      type: 'doughnut',
      data: {
        labels: kategoriLabels,
        datasets: [{
          data: kategoriData,
          backgroundColor: kategoriColors,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right'
          },
          title: {
            display: true,
            text: 'Top 5 Kategori Konsultasi',
            font: {
              size: 14,
              weight: 'bold'
            }
          }
        }
      }
    });

    // Bar Chart Status
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctxStatus, {
      type: 'bar',
      data: {
        labels: ['Menunggu', 'Diproses', 'Selesai'],
        datasets: [{
          label: 'Jumlah',
          data: [<?= $total_menunggu ?>, <?= $total_diproses ?>, <?= $total_selesai ?>],
          backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
          borderColor: ['#ffc107', '#17a2b8', '#28a745'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>

</body>
</html>
