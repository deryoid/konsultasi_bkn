<?php
include '../../config/config.php';
include '../../config/koneksi.php';


$bln = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
);

// Optional filter by role via GET
$allowed_roles = ['Admin','Konselor','User'];
$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$role = in_array($role, $allowed_roles) ? $role : '';
$where_sql = $role !== '' ? " WHERE role='".$koneksi->real_escape_string($role)."'" : '';
?>

<script type="text/javascript">
    window.print();
</script>

<!DOCTYPE html>
<html>

<head>
    <title>LAPORAN DATA USER<?= $role ? ' - '.$role : '' ?></title>
</head>

<body>
    <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" align="left" width="90" height="90">
    <p align="center"><b>
            <font size="6"><b> HASNUR RIUNG SINERGI</b></font> <br>
            <font size="4">Jln. Sudirman, Bungur, Kec.Bungur, Kab.Tapin, Kalimantan Selatan 71114</font><br>
        </b></p><br>
        <hr size="2px" color="black">

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table border="1" cellspacing="0" width="100%">
                    <thead>
                        <tr align="center">
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Hak Akses</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    $data = $koneksi->query("SELECT * FROM user".$where_sql);
                    while ($row = $data->fetch_array()) {
                    ?>
                        <tbody>
                            <tr>
                                <td align="center"><?= $no++ ?></td>
                                <td><?= $row['nama_user'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['role'] ?></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <br>

    </div>


</body>

</html>

<script>
    <?php
    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }

    ?>
</script>
