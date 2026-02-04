<?php
include '../../config/config.php';
include '../../config/koneksi.php';

// Auto print
?>
<script type="text/javascript">window.print();</script>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>LAPORAN KONSELOR TERPETAKAN</title>
  <style>
    body { font-family: Arial, sans-serif; }
    .header { text-align:center; }
    .header img { float:left; }
    .header h1, .header p { margin:0; }
    hr { border:1px solid #000; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 6px 8px; }
    th { background: #f2f2f2; }
    .note { margin-top: 10px; color: #555; font-size: 13px; }
  </style>
  <?php
  // Prepare dataset
  $has_table = false;
  $check = $koneksi->query("SHOW TABLES LIKE 'konselor_user'");
  if ($check && $check->num_rows > 0) { $has_table = true; }

  $rows = [];
  if ($has_table) {
    $sql = $koneksi->query(
      "SELECT u.id_user, u.nama_user AS akun_nama, u.username, k.id_konselor, k.nama_konselor, k.jabatan_konselor, k.keahlian, k.status
       FROM user u
       JOIN konselor_user ku ON ku.id_user = u.id_user
       JOIN konselor k ON k.id_konselor = ku.id_konselor
       WHERE u.role = 'Konselor'
       ORDER BY k.nama_konselor ASC"
    );
    while ($r = $sql->fetch_assoc()) { $rows[] = $r; }
  }
  ?>
</head>
<body>
  <div class="header">
    <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" width="90" height="90" alt="Logo BKN">
    <h1>Kantor Regional VIII Badan Kepegawaian Negara</h1>
    <p>Alamat: Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714</p>
  </div>
  <br><br>
  <hr>

  <h2 style="text-align:center;">LAPORAN KONSELOR TERPETAKAN</h2>

  <?php if (!$has_table): ?>
    <div class="note">Tabel <b>konselor_user</b> belum tersedia. Jalankan skrip: <code>db/upgrade_2025_10_konselor_user.sql</code>.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th style="width:40px;">No</th>
          <th>ID Konselor</th>
          <th>Nama Konselor</th>
          <th>Jabatan</th>
          <th>Keahlian</th>
          <th>Status</th>
          <th>ID User</th>
          <th>Nama Akun</th>
          <th>Username</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; foreach ($rows as $r): ?>
          <tr>
            <td style="text-align:center;"><?= $no++ ?></td>
            <td><?= htmlspecialchars($r['id_konselor']) ?></td>
            <td><?= htmlspecialchars($r['nama_konselor']) ?></td>
            <td><?= htmlspecialchars($r['jabatan_konselor']) ?></td>
            <td><?= htmlspecialchars($r['keahlian']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= htmlspecialchars($r['id_user']) ?></td>
            <td><?= htmlspecialchars($r['akun_nama']) ?></td>
            <td><?= htmlspecialchars($r['username']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr>
            <td colspan="9" style="text-align:center; color:#777;">Belum ada konselor yang dipetakan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>

