<nav class="main-header navbar navbar-expand navbar-blue navbar-dark" style="background-color:  #E98B33;">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>

  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      
      <a class="nav-link alert-logout" href="<?= base_url('logout') ?>">
            <?php
            if ($_SESSION['role'] == "Admin") {
              echo $_SESSION['role'];
            } elseif ($_SESSION['role'] == "User") {
              echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : $_SESSION['nama_user'];
            } elseif ($_SESSION['role'] == "Konselor") {
              echo isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Konselor';
            }
           ?>
         <i class="fas fa-sign-out-alt"></i>
      </a>
    </li>

  </ul>
</nav>
