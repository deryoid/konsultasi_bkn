<?php

require '../../config/config.php';
require '../../config/koneksi.php';

$id    = $_GET['id'];
$hapus = $koneksi->query("DELETE FROM konselor WHERE id_konselor = '$id'");


if ($hapus) {
   $_SESSION['pesan'] = "Konselor Berhasil dihapus";
   echo "<script>window.location.replace('../konselor/');</script>";
}
