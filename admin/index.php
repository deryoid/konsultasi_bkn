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
                 
              </b></h5>
          </div>

          <div class="row">
        
            <div class="col-md-4">
              <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <div class="row">
                <div class="text-center">
                <!-- <img src="<?= base_url('fotorumahmakan/' . $data['foto']) ?>" alt="<?= $data['nama_rumah_makan'] ?>" class="img-fluid rounded mx-auto d-block" style="width: 100%; height: 200px; object-fit: cover;"><br> -->
                </div>
                <hr>
                <p class="card-text flex-grow-1" style="font-size: 14px; word-wrap: break-word;">
             
                </p>
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

</body>
</html>