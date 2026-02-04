<?php
require '../../config/config.php';
require '../../config/koneksi.php';
?>
<!DOCTYPE html>
<html>
<?php
include '../../templates/head.php';
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
                            <h1 class="m-0 text-dark">Review</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Review</li>
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
                                            <label for="" class="col-sm-2 col-form-label">Rumah Makan </label>
                                            <div class="col-sm-10">
                                                <select class="custom-select select2" name="id_rumah_makan" data-placeholder="Pilih" required>
                                                    <option value="">Pilih</option>
                                                    <?php
                                                    $id_user = $_SESSION['id_user'];
                                                    $rumah_makan = $koneksi->query("SELECT * FROM rumah_makan WHERE id_rumah_makan NOT IN (SELECT id_rumah_makan FROM review WHERE id_user = '$id_user')");
                                                    foreach ($rumah_makan as $tmp) {
                                                    ?>
                                                        <option value="<?= $tmp['id_rumah_makan'] ?>"><?= $tmp['nama_rumah_makan'] ?> " Kategori : <?= $tmp['kategori'] ?> "</option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                     <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Pengguna</label>
                                            <div class="col-sm-10">
                                            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                                            <input type="text" class="form-control" value="<?= $_SESSION['nama_user'] ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Ulasan </label>
                                            <div class="col-sm-10">
                                                <textarea type="textarea" class="form-control" id="" name="ulasan"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Kecepatan Pelayanan </label>
                                            <div class="col-sm-10">
                                                <select class="custom-select" name="c1" required>
                                                    <option value="20">&#9733; </option>
                                                    <option value="40">&#9733;&#9733; </option>
                                                    <option value="60">&#9733;&#9733;&#9733; </option>
                                                    <option value="80">&#9733;&#9733;&#9733;&#9733; </option>
                                                    <option value="100">&#9733;&#9733;&#9733;&#9733;&#9733; </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Keramahan Pelayanan </label>
                                            <div class="col-sm-10">
                                                <select class="custom-select" name="c2" required>
                                                    <option value="20">&#9733; </option>
                                                    <option value="40">&#9733;&#9733; </option>
                                                    <option value="60">&#9733;&#9733;&#9733; </option>
                                                    <option value="80">&#9733;&#9733;&#9733;&#9733; </option>
                                                    <option value="100">&#9733;&#9733;&#9733;&#9733;&#9733; </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Cita Rasa Makanan </label>
                                            <div class="col-sm-10">
                                                <select class="custom-select" name="c3" required>
                                                    <option value="20">&#9733; </option>
                                                    <option value="40">&#9733;&#9733; </option>
                                                    <option value="60">&#9733;&#9733;&#9733; </option>
                                                    <option value="80">&#9733;&#9733;&#9733;&#9733; </option>
                                                    <option value="100">&#9733;&#9733;&#9733;&#9733;&#9733; </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Kebersihan </label>
                                            <div class="col-sm-10">
                                                <select class="custom-select" name="c4" required>
                                                    <option value="20">&#9733; </option>
                                                    <option value="40">&#9733;&#9733; </option>
                                                    <option value="60">&#9733;&#9733;&#9733; </option>
                                                    <option value="80">&#9733;&#9733;&#9733;&#9733; </option>
                                                    <option value="100">&#9733;&#9733;&#9733;&#9733;&#9733; </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Harga</label>
                                            <div class="col-sm-10">
                                                <select class="custom-select" name="c5" required>
                                                    <option value="20">&#9733; </option>
                                                    <option value="40">&#9733;&#9733; </option>
                                                    <option value="60">&#9733;&#9733;&#9733; </option>
                                                    <option value="80">&#9733;&#9733;&#9733;&#9733; </option>
                                                    <option value="100">&#9733;&#9733;&#9733;&#9733;&#9733; </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Dibuat Pada </label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" id="" name="dibuat_pada" value="<?= date('Y-m-d') ?>" readonly>
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
        $id_rumah_makan = $_POST['id_rumah_makan'];
        $id_user = $_POST['id_user'];
        $ulasan = str_replace("'", "\\'", $_POST['ulasan']);
        $c1 = $_POST['c1'];
        $c2 = $_POST['c2'];
        $c3 = $_POST['c3'];
        $c4 = $_POST['c4'];
        $c5 = $_POST['c5'];
        $dibuat_pada = $_POST['dibuat_pada'];

        $submit = $koneksi->query("INSERT INTO review VALUES (
            NULL,
            '$id_rumah_makan',
            '$id_user',
            '$ulasan',
            '$c1',
            '$c2',
            '$c3',
            '$c4',
            '$c5',
            '$dibuat_pada'
        )");

        if ($submit) {
            $_SESSION['pesan'] = "Data Review Ditambahkan";
            echo "<script>window.location.replace('../review/');</script>";
        }
    }
        
    ?>


</body>

</html>
