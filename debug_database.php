<?php
// DEBUG & INSTALL DATABASE
// File ini akan mendiagnosa dan memperbaiki masalah database

require 'config/koneksi.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .btn-success { background: #28a745; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔧 Debug & Instalasi Database</h1>
    <hr>

    <?php
    // STEP 1: Cek koneksi database
    echo "<h2>Step 1: Cek Koneksi Database</h2>";
    if ($koneksi) {
        echo "<div class='success'>✅ Koneksi database BERHASIL!</div>";
        echo "<p><strong>Database:</strong> " . $koneksi->query("SELECT DATABASE()")->fetch_array()[0] . "</p>";
    } else {
        echo "<div class='error'>❌ Koneksi database GAGAL!</div>";
        exit();
    }

    // STEP 2: Cek struktur tabel user
    echo "<h2>Step 2: Cek Struktur Tabel User</h2>";
    $columns = $koneksi->query("DESCRIBE user");
    echo "<table>";
    echo "<tr><th>No</th><th>Kolom</th><th>Tipe</th><th>Null</th><th>Key</th></tr>";
    $has_nip = false;
    $no = 1;
    while ($col = $columns->fetch_array()) {
        echo "<tr>";
        echo "<td>$no</td>";
        echo "<td><strong>" . $col['Field'] . "</strong></td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "</tr>";
        if ($col['Field'] == 'nip') {
            $has_nip = true;
        }
        $no++;
    }
    echo "</table>";

    if ($has_nip) {
        echo "<div class='success'>✅ Kolom <strong>NIP</strong> SUDAH ADA!</div>";
    } else {
        echo "<div class='error'>❌ Kolom <strong>NIP</strong> BELUM ADA!</div>";
    }

    // STEP 3: Cek data pegawai
    echo "<h2>Step 3: Cek Data Pegawai</h2>";
    $pegawai = $koneksi->query("SELECT nip, nik, nama_lengkap FROM pegawai LIMIT 5");
    if ($pegawai && $pegawai->num_rows > 0) {
        echo "<div class='success'>✅ Data pegawai ditemukan: " . $pegawai->num_rows . " baris</div>";
        echo "<table>";
        echo "<tr><th>NIP</th><th>NIK</th><th>Nama Lengkap</th></tr>";
        while ($p = $pegawai->fetch_array()) {
            echo "<tr>";
            echo "<td>" . $p['nip'] . "</td>";
            echo "<td>" . $p['nik'] . "</td>";
            echo "<td>" . $p['nama_lengkap'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>❌ Data pegawai TIDAK DITEMUKAN!</div>";
    }

    // STEP 4: Cek data user
    echo "<h2>Step 4: Cek Data User</h2>";
    $users = $koneksi->query("SELECT id_user, nama_user, username, nip, role FROM user");
    if ($users && $users->num_rows > 0) {
        echo "<div class='info'>📊 Data user: " . $users->num_rows . " baris</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Nama</th><th>Username</th><th>NIP</th><th>Role</th></tr>";
        while ($u = $users->fetch_array()) {
            $nip_display = $u['nip'] ?? '<span style="color: red;">NULL</span>';
            echo "<tr>";
            echo "<td>" . $u['id_user'] . "</td>";
            echo "<td>" . $u['nama_user'] . "</td>";
            echo "<td>" . $u['username'] . "</td>";
            echo "<td>" . $nip_display . "</td>";
            echo "<td>" . $u['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>❌ Data user TIDAK DITEMUKAN!</div>";
    }

    // STEP 5: Tambahkan kolom nip jika belum ada
    echo "<h2>Step 5: Perbaiki Database</h2>";
    if (!$has_nip) {
        echo "<div class='error'>";
        echo "<strong>Masalah:</strong> Kolom NIP belum ada di tabel user!<br><br>";
        echo "<strong>Solusi:</strong> Jalankan SQL berikut di phpMyAdmin atau database client:<br><br>";
        echo "<pre>";
        echo "ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;";
        echo "UPDATE user SET nip = username WHERE role = 'User';";
        echo "</pre>";
        echo "</div>";

        // Coba tambahkan kolom otomatis
        echo "<div class='info'>";
        echo "<strong>Mencoba tambah kolom otomatis...</strong><br>";
        try {
            $koneksi->query("ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username");
            echo "✅ Kolom NIP berhasil ditambahkan!<br>";
            $koneksi->query("UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL");
            echo "✅ Data user berhasil diupdate!<br>";
            echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
            echo "🔄 Halaman akan di-refresh dalam 2 detik...";
        } catch (Exception $e) {
            echo "❌ Gagal: " . $e->getMessage();
        }
        echo "</div>";
    } else {
        echo "<div class='success'>✅ Database sudah BENAR! Kolom NIP sudah ada.</div>";
    }

    // STEP 6: Test query register
    echo "<h2>Step 6: Test Query Register</h2>";
    echo "<div class='info'>";
    echo "<strong>Menguji query yang akan digunakan saat register...</strong><br><br>";

    // Sample data dari pegawai
    $sample_pegawai = $koneksi->query("SELECT * FROM pegawai LIMIT 1")->fetch_array();
    if ($sample_pegawai) {
        echo "<pre>";
        echo "Sample Data Pegawai:\n";
        print_r($sample_pegawai);
        echo "\n";

        echo "Query yang akan dijalankan:\n";
        $nama_user = $sample_pegawai['nama_lengkap'];
        $username = $sample_pegawai['nip'];
        $nip = $sample_pegawai['nip'];
        $password_hash = md5('test123');
        $query = "INSERT INTO user (nama_user, username, nip, password, role)
                  VALUES ('$nama_user', '$username', '$nip', '$password_hash', 'User')";
        echo $query;
        echo "\n";
        echo "</pre>";

        echo "<p><strong>⚠️ Query di atas hanya TEST, tidak akan dijalankan.</strong></p>";
    }
    echo "</div>";
    ?>

    <hr>
    <h2>🔗 Navigasi</h2>
    <a href="index.php" class="btn">Kembali ke Beranda</a>
    <a href="register.php" class="btn btn-success">Daftar Akun Baru</a>
    <a href="login.php" class="btn">Login</a>

    <hr>
    <h2>📝 Panduan Manual</h2>
    <div class="info">
        <h3>Jika masih error, ikuti langkah ini:</h3>
        <ol>
            <li>Buka phpMyAdmin atau Database Client</li>
            <li>Pilih database <strong>konsultasi_bkn</strong></li>
            <li>Klik tab <strong>SQL</strong></li>
            <li>Copy dan paste SQL berikut:</li>
        </ol>
        <pre>
ALTER TABLE user ADD COLUMN nip VARCHAR(25) NULL AFTER username;
UPDATE user SET nip = username WHERE role = 'User' AND nip IS NULL;
        </pre>
        <ol start="5">
            <li>Klik <strong>Go</strong> atau <strong>Execute</strong></li>
            <li>Kembali ke halaman <a href="register.php">Register</a></li>
        </ol>
    </div>

</body>
</html>
