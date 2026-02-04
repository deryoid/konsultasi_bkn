<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "Jurusan";
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
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <a href="tambah.php" class="btn bg-blue"><i class="fa fa-plus-circle"> Review</i></a>
                                    <!-- <a href="#" id="print-btn" class="btn bg-dark"><i class="fa fa-print"> Print</i></a> -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
                                    ?>
                                        <div class="alert alert-info alertinfo" role="alert">
                                            <i class="fa fa-check-circle"> <?= $_SESSION['pesan']; ?></i>
                                        </div>
                                    <?php
                                        $_SESSION['pesan'] = '';
                                    }
                                    ?>

                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead class="bg-primary">
                                                <tr align="center">
                                                    <th>No</th>
                                                    <th>Nama Rumah Makan</th>
                                                    <th>Kategori</th>
                                                    <th>Nama Pengguna</th>
                                                    <th>Ulasan</th>
                                                    <th>Kecepatan Layanan</th>
                                                    <th>Keramahan Layanan</th>
                                                    <th>Cita Rasa</th>
                                                    <th>Kebersihan</th>
                                                    <th>Harga</th>
                                                    <th>Rata - Rata</th>
                                                    <th>Dibuat Pada</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $no = 1;
                                            $data = $koneksi->query("SELECT * FROM review AS r
                                            LEFT JOIN rumah_makan AS rm 
                                            ON r.id_rumah_makan = rm.id_rumah_makan
                                            LEFT JOIN user AS u
                                            ON r.id_user = u.id_user
                                            WHERE r.id_user = '$_SESSION[id_user]'
                                            ORDER BY id_review DESC
                                            ");
                                            while ($row = $data->fetch_array()) {
                                            ?>
                                                    <tr>
                                                        <td align="center"><?= $no++ ?></td>
                                                        <td><?= $row['nama_rumah_makan'] ?></td>
                                                        <td><?= $row['kategori'] ?></td>
                                                        <td><?= $row['username'] ?></td>
                                                        <td><?= $row['ulasan'] ?></td>
                                                        <td><?= str_repeat('★', $row['c1'] / 20) ?></td>
                                                        <td><?= str_repeat('★', $row['c2'] / 20) ?></td>
                                                        <td><?= str_repeat('★', $row['c3'] / 20) ?></td>
                                                        <td><?= str_repeat('★', $row['c4'] / 20) ?></td>
                                                        <td><?= str_repeat('★', $row['c5'] / 20) ?></td>
                                                        <td>
                                                            <?php
                                                            $average = ($row['c1'] + $row['c2'] + $row['c3'] + $row['c4'] + $row['c5']) / 5;
                                                            echo str_repeat('★', intval($average / 20));
                                                            ?>
                                                        </td>
                                                        <td><?= $row['dibuat_pada'] ?></td>
                                                        <td align="center">
                                                            <!-- <a href="edit?id=<?= $row['id_materi'] ?>" class="btn btn-success btn-sm" title="Hapus"><i class="fa fa-edit"></i> </a> -->
                                                            <a href="hapus.php?id=<?= $row['id_review'] ?>" class="btn btn-danger btn-sm alert-hapus" title="Hapus"><i class="fa fa-trash"></i> </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
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


</body>

</html>
<script>
$(document).ready(function() {
  $('#print-btn').on('click', function() {
    var table = $('#example1').DataTable();
    var data = table.rows({ filter: 'applied' }).data();
    var html = '';
    html += '<p align="center"><b>';
    html += '<img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" align="left" width="80" height="80">';
    html += '<font size="6">HASNUR RIUNG SINERGI SITE EBL</font>';
    html += '<br>';
    html += '<font size="3">Alamat: Kalumpang, Kec. Bungur, Kabupaten Tapin, Kalimantan Selatan</font>';
    html += '<br>';
    html += '<br>';
    html += '<hr size="1px" color="black">';
    html += '<p size="4" align="center"><u>Pelatihan</u></p>';
    html += '</b></p>';
    html += '<hr size="2px" color="black">';
    html += '<table width="100%">';
    $.each(data, function(index, row) {
      html += '<tr>';
      $.each(row, function(index, cell) {
        html += '<td>' + cell + '</td>';
      });
      html += '</tr>';
    });
    html += '</table>';
    html += '<br>';
    html += '<div style="text-align: center; display: inline-block; float :right;">';
    html += '<h5>';
    html += 'Ditetapkan di : Rantau <br>';
    html += 'Pada Tanggal : <?php echo tgl_indo(date('Y-m-d')); ?><br>';
    html += '<br>';
    html += '<br>';
    html += 'Hasnuriyadi Sulaiman.';
    html += '</h5>';
    html += '</div>';
    var printWindow = window.open('', '', 'height=500,width=700');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>table { border-collapse: collapse; } table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(html);
    printWindow.document.write('</body></html>');
    printWindow.print();
    printWindow.close();
  });
});

</script>
