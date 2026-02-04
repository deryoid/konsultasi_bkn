<?php
require '../../config/config.php';
require '../../config/koneksi.php';
?>
<!DOCTYPE html>
<html>
<?php
include '../../templates/head.php';
$id   = $_GET['id'];
$data = $koneksi->query("SELECT * FROM materi WHERE id_materi = '$id'");
$row  = $data->fetch_array();

?>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../../templates/navbar.php';
        ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        include '../../templates/sidebar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Materi</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Materi</li>
                                <li class="breadcrumb-item active">Tambah Data</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- left column -->
                    <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Horizontal Form -->
                                <div class="card card-blue">
                                    <div class="card-header">
                                        <h3 class="card-title">Review</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <div class="card-body" style="background-color: white;">

                                    <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Judul </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="judul_materi" value="<?= $row['judul_materi'] ?>">
                                            </div>
                                        </div>
                                     <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Materi</label>
                                            <div class="col-sm-10">
                                                <textarea type="text" class="form-control" id="" name="materi"><?= $row['materi'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Tanggal </label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control"  value="<?= $row['tgl_materi'] ?>" name="tgl_materi">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Hari </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="<?= $row['hari_materi'] ?>" name="hari_materi">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Jam </label>
                                            <div class="col-sm-10">
                                                <input type="time" class="form-control" value="<?= $row['jam_materi'] ?>" name="jam_materi">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Pemateri</label>
                                            <div class="col-sm-10">
                                                <select class="custom-select select2" name="id_pemateri" data-placeholder="Pilih" required>
                                                    <option value=""></option>
                                                    <?php
                                                    $pemateri = $koneksi->query("SELECT * FROM pemateri");
                                                    foreach ($pemateri as $tmp) {
                                                    ?>
                                                        <option value="<?= $tmp['id_pemateri'] ?>" <?php if ($tmp['id_pemateri'] == $row['id_pemateri']) {
                                                                                                echo 'selected';
                                                                                            } ?>><?= $tmp['nama_pemateri'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer" style="background-color: white;">
                                        <a href="<?= base_url('user/review/') ?>" class="btn bg-gradient-secondary float-right"><i class="fa fa-arrow-left"> Batal</i></a>
                                        <button type="submit" name="submit" class="btn bg-gradient-primary float-right mr-2"><i class="fa fa-save"> Simpan</i></button>
                                    </div>
                                    <!-- /.card-footer -->

                                </div>

                            </div>
                            <!--/.col (left) -->
                        </div>

                    </form>

                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once "../../templates/footer.php"; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <?php include_once "../../templates/script.php"; ?>


    <?php

    if (isset($_POST['submit'])) {
        $judul_materi           = str_replace("'", "\\'",$_POST['judul_materi']);
        $materi                  = str_replace("'", "\\'",$_POST['materi']);
        $tgl_materi             = $_POST['tgl_materi'];
        $hari_materi            = str_replace("'", "\\'",$_POST['hari_materi']);
        $jam_materi              = $_POST['jam_materi'];
        $id_pemateri           = $_POST['id_pemateri'];




        $submit = $koneksi->query("UPDATE materi SET 
        judul_materi = '$judul_materi',
        materi = '$materi',
        tgl_materi = '$tgl_materi',
        hari_materi = '$hari_materi',
        jam_materi = '$jam_materi',
        id_pemateri = '$id_pemateri'
        WHERE 
        id_materi = '$id'");
        // var_dump($submit, $koneksi->error);
        // die;
        if ($submit) {

            $_SESSION['pesan'] = "Data Review Diubah";
            echo "<script>window.location.replace('../review/');</script>";
        }
    }
    ?>


</body>

</html>
