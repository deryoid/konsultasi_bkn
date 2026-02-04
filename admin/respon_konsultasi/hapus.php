<?php

require '../../config/config.php';
require '../../config/koneksi.php';

$id    = $koneksi->real_escape_string($_GET['id']);

// Batasi Konselor hanya bisa menghapus miliknya
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Konselor') {
   $allowed = false;
   $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
   if ($check && $check->num_rows > 0) {
       $uid = $koneksi->real_escape_string($_SESSION['id_user']);
       $map = $koneksi->query("SELECT id_konselor FROM konselor_user WHERE id_user = '$uid' LIMIT 1");
       if ($map && $map->num_rows > 0) {
           $mk = $map->fetch_assoc();
           $cek = $koneksi->query("SELECT 1 FROM respon_konsultasi WHERE id_respon_konsultasi = '$id' AND id_konselor = '".$koneksi->real_escape_string($mk['id_konselor'])."' LIMIT 1");
           if ($cek && $cek->num_rows > 0) { $allowed = true; }
       }
   }
   if (!$allowed) { die('Akses ditolak: Anda tidak berhak menghapus respon ini.'); }
}

$hapus = $koneksi->query("DELETE FROM respon_konsultasi WHERE id_respon_konsultasi = '$id'");

if ($hapus) {
   $_SESSION['pesan'] = "Respon Konsultasi Berhasil dihapus";
   echo "<script>window.location.replace('../respon_konsultasi/');</script>";
}
