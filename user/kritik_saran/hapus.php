<?php
require '../../config/config.php';
require '../../config/koneksi.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$nip = $_SESSION['nip'] ?? '';

// Validasi ID
if (empty($id)) {
    echo "<script>alert('ID tidak valid!');window.location.replace('index.php');</script>";
    exit();
}

// Cek apakah kritik & saran milik user ini
$cek = $koneksi->query("SELECT * FROM kritik_saran WHERE id_kritik_saran = '" . $koneksi->real_escape_string($id) . "' AND nip = '" . $koneksi->real_escape_string($nip) . "'");

if ($cek->num_rows > 0) {
    // Hapus data
    $hapus = $koneksi->query("DELETE FROM kritik_saran WHERE id_kritik_saran = '" . $koneksi->real_escape_string($id) . "' AND nip = '" . $koneksi->real_escape_string($nip) . "'");

    if ($hapus) {
        $_SESSION['pesan'] = "Kritik & Saran berhasil dihapus!";
        echo "<script>window.location.replace('index.php');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!');window.location.replace('index.php');</script>";
    }
} else {
    echo "<script>alert('Data tidak ditemukan atau bukan milik Anda!');window.location.replace('index.php');</script>";
}
?>
