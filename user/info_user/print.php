<?php
include '../../config/config.php';
include '../../config/koneksi.php';

$no = 1;

$bln = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
);
$data = $koneksi->query("SELECT * FROM pelamar AS p LEFT JOIN bidang AS j ON p.id_bidang = j.id_bidang WHERE p.id_pelamar = '$_SESSION[id_pelamar]'")->fetch_array();
?>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<script type="text/javascript">
    window.print();
</script>

<!DOCTYPE html>
<html>

<head>
    <title>LAPORAN DATA</title>
</head>

<body>
<p align="center"><b>
            <img src="<?= base_url('assets/dist/img/LOGO_BKN.png') ?>" align="left" width="80" height="80">
            <font size="6">HASNUR RIUNG SINERGI SITE EBL</font>
            <br>
            <font size="3">Alamat: Kalumpang, Kec. Bungur, Kabupaten Tapin, Kalimantan Selatan</font>
            <br>
            <br>
            <hr size="1px" color="black">
            <p size="4" align="center"><u>Loker</u></p>
        </b></p>
    <hr size="2px" color="black">

    <p style="text-align: center; margin-top: 2%;">
        <label>
            <b style="font-size: 28;"><u>Formulir Pendaftaran Kerja</u></b> <br>
            <br>
            <br>
        </label>
        <table border="0" width="60%"  cellpadding=" 1">
        <p style="text-align: justify; font-size: 15; ">Dengan ini menyatakan data yang dibawah ini dibuat sebenar benarnya untuk pendaftaran calon karyawan baru di HRS pada tahun <?php echo date('Y')?> :</p>
        </table>
        <div align="center">
        <table border="0" width="60%" style="text-align: left; font-size: 15; " cellpadding=" 1">
            <tr style="vertical-align: top;">
                <th width="30%">NIK</th>
                <td>:</td>
                <td><b><?= strtoupper($data['nik_pelamar'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th width="30%">Nama </th>
                <td>:</td>
                <td><b><?= strtoupper($data['nama'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th width="30%">Pendidikan </th>
                <td>:</td>
                <td><b><?= strtoupper($data['pendidikan'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th width="30%">Bidang </th>
                <td>:</td>
                <td><b><?= strtoupper($data['nm_bidang'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th width="30%">JK </th>
                <td>:</td>
                <td><b><?= strtoupper($data['jk'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th>Agama</th>
                <td>:</td>
                <td><b><?= strtoupper($data['agama'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th>Alamat</th>
                <td>:</td>
                <td><b><?= strtoupper($data['alamat'])?></b></td>
            </tr>
            <tr style="vertical-align: top;">
                <th width="40%">Telp/No. Whatsapp</th>
                <td>:</td>
                <td><b><?= strtoupper($data['telp'])?></b></td>
            </tr>
        </table>
        <br>
        </div>
        <br>
        <table border="0" width="60%"  cellpadding=" 1">
        <p style="text-align: justify; font-size: 15; ">Dengan Ini menyatakan bahwa data yang dibuat dengan benar. </p>
        </table>
       

<br>
<div style="text-align: center; display: inline-block; float: right;">
  <h5>
    Rantau , <?php echo tgl_indo(date('Y-m-d')); ?><br>
    
    <br><br><br><br>
    <u><?= $data['nama']?></u> <br>
    <?= $data['nik_pelamar'] ?>
  </h5>

</div> 
</body>

</html>

<script>
    <?php
    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }

    ?>
</script>
