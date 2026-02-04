<?php

require '../../config/config.php';
require '../../config/koneksi.php';

$id    = $_GET['id'];
$hapus = $koneksi->query("DELETE FROM satker WHERE id_satker = '$id'");


if ($hapus) {
   $_SESSION['pesan'] = "satker Berhasil dihapus";
   echo "<script>window.location.replace('../satker/');</script>";
}
