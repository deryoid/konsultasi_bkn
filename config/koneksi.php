<?php 
// Allow overriding via environment variables without breaking local defaults
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$name = getenv('DB_NAME') ?: "konsultasi_bkn";

$koneksi = mysqli_connect($host, $user, $password, $name);

if (!$koneksi) {
   die("Gagal Terkoneksi: ".mysqli_connect_errno()." - ".mysqli_connect_error());
}
?>
