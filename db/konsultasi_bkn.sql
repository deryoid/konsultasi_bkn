-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2025 at 12:48 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konsultasi_bkn`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(80) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Jenjang Karir', '-'),
(2, 'Permasalahan Disiplin', '-'),
(3, 'Permohonan Mutasi', '-'),
(4, 'Kesejahteraan Pegawai', '-'),
(5, 'Masalah Pribadi (psikososial)', '-');

-- --------------------------------------------------------

--
-- Table structure for table `konselor`
--

CREATE TABLE `konselor` (
  `id_konselor` int NOT NULL,
  `nama_konselor` varchar(255) NOT NULL,
  `jabatan_konselor` varchar(255) NOT NULL,
  `keahlian` varchar(255) NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konselor`
--

INSERT INTO `konselor` (`id_konselor`, `nama_konselor`, `jabatan_konselor`, `keahlian`, `status`) VALUES
(1, 'Dr. Aminah Virgia Van Dijk, S.Psi., M.Psi', 'Konselor Ahli', 'Masalah Pribadi (psikososial)', 'Aktif'),
(2, 'Ahmad Zain, M.AP', 'Konselor Karir ASN	', 'Pengembangan Karir, Mutasi', 'Aktif'),
(3, 'Siti Rahma, S.Sos', 'Analis Kepegawaian Ahli Muda', 'Kenaikan Pangkat, Pensiun', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` varchar(25) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `id_kategori` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_respon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id_konsultasi`, `nip`, `id_kategori`, `judul`, `tanggal_pengajuan`, `status`, `deskripsi`, `tanggal_respon`) VALUES
('KNS00001', '199210132025061003', 5, 'Konsultasi Masalah Pribadi', '2025-06-17', 'Diterima', 'Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18'),
('KNS00002', '199210132025061003', 3, 'Konsultasi Permohonan Perpindahan Penempatan Dinas di HSS', '2025-06-17', 'Baru', ' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18'),
('KNS00003', '199510132025061004', 4, 'Konsultasi Kesejahteraan Pegawai', '2025-06-17', 'Baru', ' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18');

-- --------------------------------------------------------

--
-- Table structure for table `kritik_saran`
--

CREATE TABLE `kritik_saran` (
  `id_kritik_saran` int NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `instansi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `kritik` varchar(255) NOT NULL,
  `saran` varchar(255) NOT NULL,
  `penilaian` varchar(50) NOT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kritik_saran`
--

INSERT INTO `kritik_saran` (`id_kritik_saran`, `nama_lengkap`, `instansi`, `jabatan`, `kritik`, `saran`, `penilaian`, `kontak`) VALUES
(1, 'Hajji Sirajuddin', 'Inspektorat Provinsi KALSEL', 'Auditor', 'Sdh Bagus', 'HArus Lebih bagus', 'Sangat Baik', '0812391293129'),
(2, 'Zaenuddin', 'BKN', 'Pegawai', 'Lama Proses Konsultasi', 'Harus Lebih Cepat', 'Cukup', '0812555577723');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `nip` varchar(25) NOT NULL,
  `nik` varchar(25) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_satker` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`nip`, `nik`, `nama_lengkap`, `email`, `id_satker`, `jabatan`) VALUES
('199210132025061003', '6371021310920003', 'Hajji Sirajuddin', 'sirajjudin@bkn.go.id', 'BKN01', 'Staff Bidang Perencanaan Kepegawaian'),
('199510132025061004', '6371021310950004', 'Riza Maulana', 'riza@gmail.com', 'BKN01', 'Kepala Pengadaan Barang dan Jasa');

-- --------------------------------------------------------

--
-- Table structure for table `respon_konsultasi`
--

CREATE TABLE `respon_konsultasi` (
  `id_respon_konsultasi` int NOT NULL,
  `id_konsultasi` varchar(25) NOT NULL,
  `id_konselor` int NOT NULL,
  `isi_respon` text NOT NULL,
  `tanggal_respon` date NOT NULL,
  `lampiran_respon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `respon_konsultasi`
--

INSERT INTO `respon_konsultasi` (`id_respon_konsultasi`, `id_konsultasi`, `id_konselor`, `isi_respon`, `tanggal_respon`, `lampiran_respon`) VALUES
(2, 'KNS00001', 3, 'Konsultasi Kenaikan Pangkat', '2025-06-17', 'lampiran_68514e5200197.pdf'),
(3, 'KNS00002', 3, 'Anda harus mendapat ijin dari kepala dinas', '2025-06-17', 'lampiran_6851632178d3d.pdf'),
(4, 'KNS00003', 2, 'Sebaiknya anda menuruti perintah atasan agar kesejahteraan anda terjamin', '2025-06-17', 'lampiran_68516351be135.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `satker`
--

CREATE TABLE `satker` (
  `id_satker` varchar(100) NOT NULL,
  `satker` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `satker`
--

INSERT INTO `satker` (`id_satker`, `satker`, `lokasi`) VALUES
('BKN01', 'Badan Kepegawaian Daerah Kantor Reg VIII', 'Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `role`) VALUES
(1, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `konselor`
--
ALTER TABLE `konselor`
  ADD PRIMARY KEY (`id_konselor`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD PRIMARY KEY (`id_kritik_saran`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `id_satker` (`id_satker`);

--
-- Indexes for table `respon_konsultasi`
--
ALTER TABLE `respon_konsultasi`
  ADD PRIMARY KEY (`id_respon_konsultasi`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `id_konselor` (`id_konselor`);

--
-- Indexes for table `satker`
--
ALTER TABLE `satker`
  ADD PRIMARY KEY (`id_satker`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `konselor`
--
ALTER TABLE `konselor`
  MODIFY `id_konselor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kritik_saran`
--
ALTER TABLE `kritik_saran`
  MODIFY `id_kritik_saran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `respon_konsultasi`
--
ALTER TABLE `respon_konsultasi`
  MODIFY `id_respon_konsultasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
