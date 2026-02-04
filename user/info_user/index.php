<?php
$title = "Profil Pegawai";
require '../../config/config.php';
include '../../config/koneksi.php';

include '../../templates/head.php';
include '../../templates/navbar.php';
include '../../templates/sidebar.php';

// Ambil data pegawai dari session
$data_pegawai = [
    'nip' => $_SESSION['nip'] ?? '',
    'nik' => $_SESSION['nik'] ?? '',
    'nama_lengkap' => $_SESSION['nama_lengkap'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'jabatan' => $_SESSION['jabatan'] ?? ''
];
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profil Pegawai</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fa fa-check"> <?= $_SESSION['pesan']; ?></i>
                        </div>
                    <?php }
                    $_SESSION['pesan'] = ''; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-address-card"></i> Informasi Pegawai
                            </h3>
                            <div class="card-tools">
                                <a href="../ubahpw.php" class="btn btn-info btn-sm">
                                    <i class="fa fa-key mr-1"></i>Ubah Password
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="20%">NIP</th>
                                            <td><?= htmlspecialchars($data_pegawai['nip']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>NIK</th>
                                            <td><?= htmlspecialchars($data_pegawai['nik']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td><?= htmlspecialchars($data_pegawai['nama_lengkap']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?= htmlspecialchars($data_pegawai['email']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td><?= htmlspecialchars($data_pegawai['jabatan']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Username</th>
                                            <td><?= htmlspecialchars($data_pegawai['nip']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td><span class="badge badge-info"><?= $_SESSION['role'] ?></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>

<?php
include '../../templates/footer.php';
?>
