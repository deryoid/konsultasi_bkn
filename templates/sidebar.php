<aside class="main-sidebar sidebar-light-blue elevation-4">
  <!-- dark-primary  -->
  <!-- Brand Logo -->
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 d-flex justify-content-center">
      <div class="image">
        <img src="<?= base_url() ?>/assets/dist/img/LOGO_BKN.png" alt="Logo" style="display:block; margin:auto; width:50px; height:65px">
      </div>
    </div>

    <?php if ($_SESSION['role'] == "Admin") { ?>

      <!-- Sidebar Menu -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?= base_url('admin/index.php') ?>" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Home
              </p>
            </a>
          </li>

          <li class="nav nav-header">Data Master</li>
          <li class="nav-item">
            <a href="<?= base_url('admin/satker/') ?>" class="nav-link">
              <i class="fas fa-building nav-icon"></i>
              <p>Satker</p>
            </a>
          </li>   
          <li class="nav-item">
            <a href="<?= base_url('admin/pegawai/') ?>" class="nav-link">
              <i class="fas fa-users nav-icon"></i>
              <p>Pegawai</p>
            </a>
          </li>   
          <li class="nav-item">
            <a href="<?= base_url('admin/konselor/') ?>" class="nav-link">
              <i class="fas fa-user-friends nav-icon"></i>
              <p>Konselor</p>
            </a>
          </li>   
          <li class="nav-item">
            <a href="<?= base_url('admin/user/') ?>" class="nav-link">
              <i class="fas fa-user-cog nav-icon"></i>
              <p>User</p>
            </a>
          </li>
          <li class="nav nav-header">Layanan</li>
          <li class="nav-item">
            <a href="<?= base_url('admin/kategori/') ?>" class="nav-link">
              <i class="fas fa-sitemap nav-icon"></i>
              <p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/konsultasi/') ?>" class="nav-link">
              <i class="fas fa-list-ol nav-icon"></i>
              <p>Daftar Konsultasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/respon_konsultasi/') ?>" class="nav-link">
              <i class="fas fa-comment-alt nav-icon"></i>
              <p>Respon Konsultasi</p>
            </a>
          </li>

          <li class="nav nav-header">Feedback</li>
          <li class="nav-item">
            <a href="<?= base_url('admin/kritik_saran/') ?>" class="nav-link">
              <i class="fas fa-comment nav-icon"></i>
              <p>Kritik & Saran</p>
            </a>
          </li>
          <li class="nav nav-header">Informasi dan Riwayat</li>
          <li class="nav-item">
            <a href="<?= base_url('admin/riwayat_konsultasi/') ?>" class="nav-link">
              <i class="fas fa-comment-dots nav-icon"></i>
              <p>Riwayat Konsultasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/rekap_konselor/') ?>" class="nav-link">
              <i class="fas fa-digital-tachograph nav-icon"></i>
              <p>Data Rekapitulasi Konselor</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    
    <?php }elseif ($_SESSION['role'] == "User") { ?>

<!-- Sidebar Menu -->
<nav class="mt-0">
  <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <li class="nav-item">
      <a href="<?= base_url('user/index') ?>" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
          Home
        </p>
      </a>
    </li>

    <li class="nav nav-header">Konsultasi</li>
    <li class="nav-item">
      <a href="<?= base_url('user/konsultasi/tambah') ?>" class="nav-link">
        <i class="nav-icon fas fa-plus-circle"></i>
        <p>
          Buat Konsultasi
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('user/info_user/') ?>" class="nav-link">
        <i class="nav-icon fas fa-user-alt"></i>
        <p>
          Profil
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('user/kritik_saran/') ?>" class="nav-link">
        <i class="nav-icon fas fa-comment-dots"></i>
        <p>
          Kritik & Saran
        </p>
      </a>
    </li>

  </ul>
</nav>
<!-- /.sidebar-menu -->



<?php } ?>
    <?php if ($_SESSION['role'] == "Konselor") { ?>

<!-- Sidebar Menu for Konselor -->
<nav class="mt-3">
  <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
      <a href="<?= base_url('admin/respon_konsultasi/') ?>" class="nav-link">
        <i class="fas fa-comment-alt nav-icon"></i>
        <p>Respon Konsultasi</p>
      </a>
    </li>
  </ul>
</nav>
<!-- /.sidebar-menu -->


<?php } ?>
  </div>
  <!-- /.sidebar -->
</aside>
