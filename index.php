<?php
require 'config/config.php';
require 'config/koneksi.php';
?>
<!DOCTYPE html>
<html>
<?php
include 'templates/head.php';


?>

<body>
<nav class=" navbar navbar-expand navbar-light navbar-light" style="background-color: #E98B33;">
  <a class="navbar-brand" href="#" style="color: white; font-weight: bold; font-size: 1.5em;">
    <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" alt="Logo" width="50" height="60" class="d-inline-block align-top" style="box-shadow: 0 4px 12px rgba(239,126,27,0.5); border-radius: 10px; background: #fff; padding: 4px;">
  </a>
  <ul class="navbar-nav">
    <a class="nav-link" href="index.php" style="color: white;"><i class="fas fa-home"></i> Home</a>
    <a class="nav-link" href="register.php" style="color: white;"><i class="fas fa-key"></i> Daftar Akun</a>
    <a class="nav-link" href="login.php" style="color: white;"><i class="fas fa-sign-in-alt"></i> Login</a>

    <!-- Button trigger modal -->
    <li class="nav-item">
      <a class="nav-link" href="#" style="color: white;" data-toggle="modal" data-target="#kritikSaranModal">
      <i class="fas fa-comment-dots"></i> Isi Kritik & Saran
      </a>
        </li>
        <?php
        // Proses insert kritik & saran jika form disubmit
        if (
      $_SERVER['REQUEST_METHOD'] === 'POST' &&
      isset(
        $_POST['nama_lengkap'],
        $_POST['instansi'],
        $_POST['jabatan'],
        $_POST['kritik'],
        $_POST['saran'],
        $_POST['penilaian']
      )
        ) {
      require_once 'config/koneksi.php';
      $nama_lengkap = $koneksi->real_escape_string($_POST['nama_lengkap']);
      $instansi     = $koneksi->real_escape_string($_POST['instansi']);
      $jabatan      = $koneksi->real_escape_string($_POST['jabatan']);
      $kritik       = $koneksi->real_escape_string($_POST['kritik']);
      $saran        = $koneksi->real_escape_string($_POST['saran']);
      $penilaian    = $koneksi->real_escape_string($_POST['penilaian']);
      $kontak       = isset($_POST['kontak']) ? $koneksi->real_escape_string($_POST['kontak']) : '';

      $sql = "INSERT INTO kritik_saran (nama_lengkap, instansi, jabatan, kritik, saran, penilaian, kontak)
          VALUES ('$nama_lengkap', '$instansi', '$jabatan', '$kritik', '$saran', '$penilaian', '$kontak')";
      if ($koneksi->query($sql)) {
        echo "<script>alert('Kritik & Saran berhasil dikirim!');window.location='index';</script>";
      } else {
        echo "<script>alert('Gagal mengirim kritik & saran.');</script>";
      }
        }
        ?>

        <!-- Modal Kritik & Saran -->
        <div class="modal fade" id="kritikSaranModal" tabindex="-1" role="dialog" aria-labelledby="kritikSaranModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document"><!-- modal-lg for wider modal -->
            <form action="" method="post">
              <div class="modal-content">
                <div class="modal-header" style="background-color: #E98B33;">
                  <h5 class="modal-title" id="kritikSaranModalLabel" style="color: white;">Form Kritik & Saran</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="nama_lengkap">Nama Lengkap</label>
                          <input type="text" class="form-control" name="nama_lengkap" required>
                        </div>
                        <div class="form-group">
                          <label for="instansi">Instansi</label>
                          <input type="text" class="form-control" name="instansi" required>
                        </div>
                        <div class="form-group">
                          <label for="jabatan">Jabatan</label>
                          <input type="text" class="form-control" name="jabatan" required>
                        </div>
                        <div class="form-group">
                          <label for="kontak">Kontak (Email/HP)</label>
                          <input type="text" class="form-control" name="kontak">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="kritik">Kritik</label>
                          <textarea class="form-control" name="kritik" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                          <label for="saran">Saran</label>
                          <textarea class="form-control" name="saran" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                          <label for="penilaian">Penilaian</label>
                          <select class="form-control" name="penilaian" required>
                            <option value="">-- Pilih Penilaian --</option>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Kurang">Kurang</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Kirim</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- End Modal Kritik & Saran -->
  </ul>
</nav>
<br>
<section>
  <div class="col-12">
    <div class="row">
      <div id="fotoSlider" class="carousel slide col-12" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#fotoSlider" data-slide-to="0" class="active"></li>
          <li data-target="#fotoSlider" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="<?= base_url('assets/dist/img/WEB_ZI_BARU.jpg') ?>" class="d-block w-100 h-100" alt="Slider 1" style="height:350px;object-fit:cover;">
          </div>
          <div class="carousel-item">
            <img src="<?= base_url('assets/dist/img/WEB1_Kantor.jpg') ?>" class="d-block w-100 h-100" alt="Slider 2" style="height:350px;object-fit:cover;">
          </div>
        </div>
        <a class="carousel-control-prev" href="#fotoSlider" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#fotoSlider" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div> <!-- Tutup .row (slider) -->
    <!-- Outline Card Pencarian -->
    <div class="row mt-4">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-body">
            <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
          <!-- Form pencarian -->
            <form action="" method="get">
            <div class="form-row align-items-end">
              <div class="form-group col-md-5">
                <label for="nip">NIP</label>
                <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP" value="<?= isset($_GET['nip']) ? htmlspecialchars($_GET['nip']) : '' ?>">
                </div>
                <div class="form-group col-md-5">
                <label for="id_konsultasi">ID Konsultasi</label>
                <input type="text" class="form-control" name="id_konsultasi" placeholder="Masukkan ID Konsultasi" value="<?= isset($_GET['id_konsultasi']) ? htmlspecialchars($_GET['id_konsultasi']) : '' ?>">
                </div>
                <div class="form-group col-md-2">
                <button type="submit" class="btn btn-success btn-xl" style="margin-top: 32px;">
                  <i class="fas fa-search"></i> 
                </button>
                <button type="button" class="btn btn-secondary btn-xl" style="margin-top: 32px; margin-left: 5px;" onclick="window.location.href='index';">
                  <i class="fas fa-sync-alt"></i>
                </button>
              </div>
            </div>
            </form>
            <?php
            require 'config/day.php';

            // Tampilkan hasil pencarian jika ada parameter
            $show_result = false;
            $nip = isset($_GET['nip']) ? trim($_GET['nip']) : '';
            $id_konsultasi = isset($_GET['id_konsultasi']) ? trim($_GET['id_konsultasi']) : '';

            if ($nip !== '' || $id_konsultasi !== '') {
              $where = [];
              if ($nip !== '') {
                $where[] = "k.nip = '" . $koneksi->real_escape_string($nip) . "'";
              }
              if ($id_konsultasi !== '') {
                $where[] = "k.id_konsultasi = '" . $koneksi->real_escape_string($id_konsultasi) . "'";
              }
              $where_sql = implode(' AND ', $where);

              $query = $koneksi->query("
                SELECT k.*, p.nama_lengkap, p.nip, c.nama_kategori 
                FROM konsultasi k
                LEFT JOIN pegawai p ON k.nip = p.nip
                LEFT JOIN kategori c ON k.id_kategori = c.id_kategori
                WHERE $where_sql
                LIMIT 1
              ");

              $data = $query->fetch_assoc();
              $show_result = true;
            }
            ?>

            <?php if ($show_result): ?>
              <?php if ($data): ?>
                <div class="alert alert-dark">
                  <strong>Hasil Pencarian:</strong>
                  <table class="table table-bordered mt-2">
                    <tr>
                      <th>ID Konsultasi</th>
                      <td><?= htmlspecialchars($data['id_konsultasi']) ?></td>
                    </tr>
                    <tr>
                      <th>NIP</th>
                      <td><?= htmlspecialchars($data['nip']) ?></td>
                    </tr>
                    <tr>
                      <th>Nama Lengkap</th>
                      <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                      <th>Kategori</th>
                      <td><?= htmlspecialchars($data['nama_kategori']) ?></td>
                    </tr>
                    <tr>
                      <th>Judul</th>
                      <td><?= htmlspecialchars($data['judul']) ?></td>
                    </tr>
                    <tr>
                      <th>Tanggal Pengajuan</th>
                      <td><?= htmlspecialchars($data['tanggal_pengajuan']) ?></td>
                    </tr>
                    <tr>
                      <th>Status</th>
                      <td><b><?= htmlspecialchars($data['status']) ?></b></td>
                    </tr>
                    <tr>
                      <th>Deskripsi</th>
                      <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                    </tr>
                    <tr>
                      <th>Tanggal Respon</th>
                      <td><?= htmlspecialchars($data['tanggal_respon']) ?></td>
                    </tr>
                  </table>
                  <a href="respon_konsultasi.php?nip=<?= urlencode($nip) ?>&id_konsultasi=<?= urlencode($id_konsultasi) ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak Respon Konsultasi
                  </a>
                </div>
              <?php else: ?>
                <div class="alert alert-danger">
                  Data konsultasi tidak ditemukan.
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <!-- End Form pencarian -->
          </div>

          <!-- <div class="card-body">
         nanti diisi
          </div> -->

        </div>
      </div>
      </div>
    </div>
    <!-- End Outline Card Pencarian -->
</section>

<?php include 'templates/script.php'; ?>
</body>
</html>
