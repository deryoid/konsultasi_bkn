<?php
require 'config/config.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Favicon -->
  <title>Login </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/assets/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page" style="background-color:  #E98B33;">
  <div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
      <div class="login-logo">
          <img src="<?= base_url() ?>/assets/dist/img/LOGO_BKN.png" style="margin-top: 20px; margin-bottom: 20px;" width="134px;" height="180px;">
          <!-- <h4 style="color: #E98B33; font-family:Impact, Luminari, Chalkduster">LOGIN APLIKASI</h4> -->
      </div>
      <div class="card-body login-card-body">
        <!-- <p class="login-box-msg">Sign in to start your session</p> -->
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
          <div class="alert alert-danger success-alert" role="alert">
            <small><i class="fa fa-check"> <?= $_SESSION['pesan']; ?></i></small>
          </div>
        <?php $_SESSION['pesan'] = '';
        } ?>

        <form action="" method="POST">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required="">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user-circle"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required="">
            <div class="input-group-append">
              <div class="input-group-text" id="lihatpass">
                <span class="fas fa-eye" title="Lihat Password" onclick="change();"></span>
              </div>
            </div>
          </div>

          <button type="submit" name="submit" class="btn btn-block btn-xm" style="background-color:  #E98B33; color:aliceblue;"><i class="fa fa-sign-in-alt mr-1"></i>Masuk</button>
          <a type="submit" href="index.php" class="btn btn-block btn-xm" style="background-color:  #E98B33; color:aliceblue;"><i class="fa fa-backspace mr-1"></i>Kembali</a>
          <br>
        </form>

      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/assets/dist/js/adminlte.min.js"></script>

  <script>
    $(function() {
      setTimeout(function() {
        $(".success-alert").slideUp();
      }, 1500);
    });

    function change() {
      var x = document.getElementById('pass').type;

      if (x == 'password') {
        document.getElementById('pass').type = 'text';
        document.getElementById('lihatpass').innerHTML = '<span class="fas fa-eye" title="Lihat Password" style="color: blue;" id="lihatpass" onclick="change();"></span>';
      } else {
        document.getElementById('pass').type = 'password';
        document.getElementById('lihatpass').innerHTML = '<span class="fas fa-eye" title="Lihat Password" id="lihatpass" onclick="change();"></span>';
      }
    }
  </script>

</body>

</html>

<?php
include 'config/koneksi.php';
if (isset($_POST['submit'])) {
  $user = mysqli_real_escape_string($koneksi, $_POST['username']);
  $pass = mysqli_real_escape_string($koneksi, $_POST['password']);
  $pass = md5($pass);

  $query = mysqli_query($koneksi, "SELECT * FROM user
  WHERE username = '$user' AND password = '$pass'");
  $row = mysqli_fetch_array($query);


  if ($row) {
    $nama_user  = $row['nama_user'];
    $username  = $row['username'];
    $password  = $row['password'];
    $role      = $row['role'];
    $id_user   = $row['id_user'];

    if ($user == $username && $pass == $password) {
      if ($role == "User") {
        // Ambil data pegawai berdasarkan username (yang isinya NIP)
        $query_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE nip = '".$row['username']."'");
        $data_pegawai = mysqli_fetch_assoc($query_pegawai);

        $_SESSION['nama_user']    = $nama_user;
        $_SESSION['id_user']      = $id_user;
        $_SESSION['role']         = $role;
        $_SESSION['nip']          = $row['username']; // NIP dari username
        $_SESSION['nik']          = $data_pegawai['nik'];
        $_SESSION['nama_lengkap'] = $data_pegawai['nama_lengkap'];
        $_SESSION['email']        = $data_pegawai['email'];
        $_SESSION['jabatan']      = $data_pegawai['jabatan'];
        echo "<script>window.location.replace('user/');</script>";
      } elseif ($role == "Admin") {
        $_SESSION['id_user']   = $id_user;
        $_SESSION['role'] = $role;
        echo "<script>window.location.replace('admin/');</script>";
      } elseif ($role == "Konselor") {
        $_SESSION['nama_user'] = $nama_user;
        $_SESSION['id_user'] = $id_user;
        $_SESSION['role'] = $role;
        // Optional: map id_konselor jika tabel konselor_user tersedia
        if ($koneksi) {
          $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
          if ($check && $check->num_rows > 0) {
            $id_user_safe = $koneksi->real_escape_string($id_user);
            $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$id_user_safe' LIMIT 1");
            if ($map && $map->num_rows > 0) {
              $mk = $map->fetch_assoc();
              $_SESSION['id_konselor'] = $mk['id_konselor'];
            }
          }
        }
        echo "<script>window.location.replace('admin/respon_konsultasi/');</script>";
      } 
    }
  } else {
    $_SESSION['pesan'] = 'Username atau Password Tidak Ditemukan';
    echo "<script>window.location.replace('login.php');</script>";
  }
}


?>
