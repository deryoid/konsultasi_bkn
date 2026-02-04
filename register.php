<?php
require 'config/config.php';
require 'config/koneksi.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Daftar Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition register-page" style="background-color: #E98B33;">
  <div class="register-box">
    <div class="card">
      <div class="register-logo">
        <img src="<?= base_url() ?>/assets/dist/img/LOGO_BKN.png" style="margin-top: 20px; margin-bottom: 20px;" width="134px;" height="180px;">
      </div>

      <div class="card-body register-card-body">
        <p class="login-box-msg">Daftar sebagai Pegawai</p>

        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
          <div class="alert alert-<?= isset($_SESSION['pesan_type']) ? $_SESSION['pesan_type'] : 'success' ?> success-alert" role="alert">
            <small><i class="fa fa-check"> <?= $_SESSION['pesan']; ?></i></small>
          </div>
        <?php
          $_SESSION['pesan'] = '';
          unset($_SESSION['pesan_type']);
        } ?>

        <?php
        if (isset($_GET['nip'])) {
          $nip_input = $koneksi->real_escape_string($_GET['nip']);

          // Cek apakah NIP ada di tabel pegawai
          $query_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE nip = '$nip_input'");
          $data_pegawai = mysqli_fetch_assoc($query_pegawai);

          // Cek apakah NIP sudah terdaftar di tabel user
          $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$nip_input'");

          if ($data_pegawai && mysqli_num_rows($query_user) == 0) {
        ?>
            <div class="alert alert-info">
              <strong>Data Pegawai Ditemukan!</strong>
              <br>Silakan buat password untuk akun Anda.
              <br><small class="text-muted">
                <i class="fas fa-info-circle"></i> <strong>Username Anda adalah NIP:</strong> <?= htmlspecialchars($data_pegawai['nip']) ?>
              </small>
            </div>

            <form action="" method="POST">
              <div class="form-group">
                <label>NIP (Username)</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data_pegawai['nip']) ?>" readonly>
                <small class="form-text text-muted">
                  <i class="fas fa-lock"></i> NIP Anda akan menjadi Username untuk login
                </small>
              </div>

              <div class="form-group">
                <label>NIK</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data_pegawai['nik']) ?>" readonly>
              </div>

              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data_pegawai['nama_lengkap']) ?>" readonly>
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($data_pegawai['email']) ?>" readonly>
              </div>

              <div class="form-group">
                <label>Jabatan</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data_pegawai['jabatan']) ?>" readonly>
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" required minlength="6">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-eye" onclick="togglePassword('password', this)" style="cursor: pointer;"></span>
                    </div>
                  </div>
                </div>
                <small class="form-text text-muted">Minimal 6 karakter</small>
              </div>

              <div class="form-group">
                <label for="konfirmasi_password">Konfirmasi Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" placeholder="Ulangi Password" required minlength="6">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-eye" onclick="togglePassword('konfirmasi_password', this)" style="cursor: pointer;"></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="alert alert-success">
                <strong>ℹ️ Informasi Akun:</strong><br>
                <table class="table table-sm mb-0">
                  <tr>
                    <td width="30%">Username</td>
                    <td><strong><?= htmlspecialchars($data_pegawai['nip']) ?></strong> (NIP Anda)</td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td><?= htmlspecialchars($data_pegawai['nama_lengkap']) ?></td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td>Isi di bawah ini</td>
                  </tr>
                </table>
              </div>

              <input type="hidden" name="nama_user" value="<?= htmlspecialchars($data_pegawai['nama_lengkap']) ?>">
              <input type="hidden" name="username" value="<?= htmlspecialchars($data_pegawai['nip']) ?>">

              <button type="submit" name="submit" class="btn btn-block btn-xm" style="background-color: #E98B33; color: aliceblue;">
                <i class="fa fa-user-plus mr-1"></i>Buat Akun
              </button>
            </form>
        <?php
          } elseif (!$data_pegawai) {
        ?>
            <div class="alert alert-danger">
              <i class="fa fa-exclamation-triangle"></i> NIP tidak ditemukan dalam database pegawai!
            </div>
            <a href="register.php" class="btn btn-block btn-secondary">
              <i class="fa fa-arrow-left mr-1"></i>Kembali
            </a>
        <?php
          } else {
        ?>
            <div class="alert alert-warning">
              <i class="fa fa-exclamation-circle"></i> NIP Anda sudah terdaftar! Silakan login.
            </div>
            <a href="login.php" class="btn btn-block btn-primary">
              <i class="fa fa-sign-in-alt mr-1"></i>Login
            </a>
        <?php
          }
        } else {
        ?>
          <p class="text-center">Masukkan <strong>NIP</strong> untuk memverifikasi data pegawai</p>

          <form action="" method="GET">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP (Contoh: 199210132025061003)" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-id-card"></span>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-block btn-xm" style="background-color: #E98B33; color: aliceblue;">
              <i class="fa fa-search mr-1"></i>Cek Data
            </button>
          </form>
        <?php
        }
        ?>

        <br>
        <a href="login.php" class="text-center">
          <i class="fa fa-arrow-left"></i> Kembali ke Login
        </a>
      </div>
    </div>
  </div>

  <script src="<?= base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>/assets/dist/js/adminlte.min.js"></script>

  <script>
    $(function() {
      setTimeout(function() {
        $(".success-alert").slideUp();
      }, 3000);
    });

    function togglePassword(inputId, icon) {
      var input = document.getElementById(inputId);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>

</body>

</html>

<?php
if (isset($_POST['submit'])) {
  $nama_user = mysqli_real_escape_string($koneksi, $_POST['nama_user']);
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);
  $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

  // Validasi password
  if ($password !== $konfirmasi_password) {
    $_SESSION['pesan'] = 'Password dan Konfirmasi Password tidak cocok!';
    $_SESSION['pesan_type'] = 'danger';
    echo "<script>window.location.replace('register.php');</script>";
    exit();
  }

  // Cek apakah username sudah ada
  $cek_username = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
  if (mysqli_num_rows($cek_username) > 0) {
    $_SESSION['pesan'] = 'Username (NIP) sudah terdaftar!';
    $_SESSION['pesan_type'] = 'warning';
    echo "<script>window.location.replace('register.php');</script>";
    exit();
  }

  // Hash password dengan MD5 (sesuai sistem login yang sudah ada)
  $password_hash = md5($password);

  // Insert data ke tabel user
  // Username dan NIP diisi dengan nilai yang sama (NIP pegawai)
  $query = mysqli_query($koneksi, "INSERT INTO user (nama_user, username, password, role) VALUES ('$nama_user', '$username', '$password_hash', 'User')");

  if ($query) {
    $_SESSION['pesan'] = "✅ Pendaftaran berhasil!<br>Username: <strong>$username</strong><br>Password: (yang Anda buat)";
    $_SESSION['pesan_type'] = 'success';
    echo "<script>window.location.replace('login.php');</script>";
  } else {
    $_SESSION['pesan'] = '❌ Pendaftaran gagal! Error: ' . mysqli_error($koneksi);
    $_SESSION['pesan_type'] = 'danger';
    echo "<script>window.location.replace('register.php');</script>";
  }
}
?>
