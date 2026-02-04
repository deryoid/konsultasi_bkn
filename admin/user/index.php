<?php
require '../../config/config.php';
require '../../config/koneksi.php';
require '../../config/day.php';

$title = "User";
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
                            <h1 class="m-0 text-dark">User</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">User</li>
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
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex align-items-center">
                                    <a href="tambah.php" class="btn bg-blue mr-2"><i class="fa fa-plus-circle"> Tambah Data</i></a>
                                    <div class="btn-group">
                                      <button type="button" class="btn bg-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-print"></i> Cetak
                                      </button>
                                      <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="print.php" target="_blank">Semua</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="print.php?role=Admin" target="_blank">Admin</a>
                                        <a class="dropdown-item" href="print.php?role=Konselor" target="_blank">Konselor</a>
                                        <a class="dropdown-item" href="print.php?role=User" target="_blank">User</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="print_konselor.php" target="_blank">Konselor Terpetakan</a>
                                      </div>
                                    </div>
                                    <a href="#" id="print-btn" class="btn bg-secondary ml-2"><i class="fa fa-print"></i> Cetak (Terfilter)</a>
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
                                            <thead class="bg-blue">
                                                <tr align="center">
                                                    <th>No</th>
                                                    <th>Nama User</th>
                                                    <th>Username</th>
                                                    <th>Hak Akses</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody style="background-color: azure">
                                            <?php
                                            $no = 1;
                                            $data = $koneksi->query("SELECT * FROM user");
                                            while ($row = $data->fetch_array()) {
                                            ?>
                                                    <tr>
                                                        <td align="center"><?= $no++ ?></td>
                                                        <td><?= $row['nama_user'] ?></td>
                                                        <td><?= $row['username'] ?></td>
                                                        <td><?= $row['role'] ?></td>
                                                        <td align="center">
                                                            <a href="edit.php?id=<?= $row['id_user'] ?>" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                            <a href="hapus.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm alert-hapus" title="Hapus"><i class="fa fa-trash"></i></a>
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

    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    </script>

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
    html += '<p size="4" align="center"><u>User</u></p>';
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
