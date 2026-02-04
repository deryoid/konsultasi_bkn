<?php

require '../../config/config.php';
require '../../config/koneksi.php';

$id    = $_GET['id'];
$hapus = $koneksi->query("DELETE FROM konsultasi WHERE id_konsultasi = '$id'");


if ($hapus) {
   $_SESSION['pesan'] = "konsultasi Berhasil dihapus";
   echo "<script>window.location.replace('../konsultasi/');</script>";
}
