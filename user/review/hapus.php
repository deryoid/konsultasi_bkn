<?php

require '../../config/config.php';
require '../../config/koneksi.php';

$id    = $_GET['id'];
$hapus = $koneksi->query("DELETE FROM review WHERE id_review = '$id'");

if ($hapus) {
   $_SESSION['pesan'] = "Review berhasil dihapus";
   echo "<script>window.location.replace('../review/');</script>";
}
?>
