<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi ID
if (empty($id)) {
    echo "<script>alert('ID tidak valid!');window.location.replace('index.php');</script>";
    exit();
}

// Hapus data
$hapus = $koneksi->query("DELETE FROM kritik_saran WHERE id_kritik_saran = '" . $koneksi->real_escape_string($id) . "'");

if ($hapus) {
    $_SESSION['pesan'] = "Data Kritik & Saran berhasil dihapus!";
    echo "<script>window.location.replace('index.php');</script>";
} else {
    echo "<script>alert('Gagal menghapus data!');window.location.replace('index.php');</script>";
}
?>
