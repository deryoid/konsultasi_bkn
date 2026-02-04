-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Jun 2025 pada 15.17
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

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
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(80) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Jenjang Karir', '-'),
(2, 'Permasalahan Disiplin', '-'),
(3, 'Permohonan Mutasi', '-'),
(4, 'Kesejahteraan Pegawai', '-'),
(5, 'Masalah Pribadi (psikososial)', '-'),
(7, 'Kenaikan pangkat', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konselor`
--

CREATE TABLE `konselor` (
  `id_konselor` int(11) NOT NULL,
  `nama_konselor` varchar(255) NOT NULL,
  `jabatan_konselor` varchar(255) NOT NULL,
  `keahlian` varchar(255) NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konselor`
--

INSERT INTO `konselor` (`id_konselor`, `nama_konselor`, `jabatan_konselor`, `keahlian`, `status`) VALUES
(1, 'Dr. Aminah Virgia Van Dijk, S.Psi., M.Psi', 'Konselor Ahli', 'Masalah Pribadi (psikososial)', 'Aktif'),
(2, 'Ahmad Zain, M.AP', 'Konselor Karir ASN	', 'Pengembangan Karir, Mutasi', 'Aktif'),
(3, 'Siti Rahma, S.Sos', 'Analis Kepegawaian Ahli Muda', 'Kenaikan Pangkat, Pensiun', 'Aktif'),
(4, 'Maria Ulfa', 'Konselur', 'Kenaikan pangkat', 'Aktif'),
(5, 'Andi Saputro', 'Konselor', 'Mutasi', 'Aktif'),
(6, 'Yudi Prasetyo', 'Konselor', 'Permasalahan Disiplin', 'Aktif'),
(7, 'Siti Aminah', 'Konselor junior ', 'Kesejahteran pegawai, mutasi', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` varchar(25) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_respon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konsultasi`
--

INSERT INTO `konsultasi` (`id_konsultasi`, `nip`, `id_kategori`, `judul`, `tanggal_pengajuan`, `status`, `deskripsi`, `tanggal_respon`) VALUES
('KNS00001', '199210132025061003', 5, 'Konsultasi Masalah Pribadi', '2025-06-17', 'Diterima', 'Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18'),
('KNS00002', '199210132025061003', 3, 'Konsultasi Permohonan Perpindahan Penempatan Dinas di HSS', '2025-06-17', 'Baru', ' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18'),
('KNS00003', '199510132025061004', 4, 'Konsultasi Kesejahteraan Pegawai', '2025-06-17', 'Baru', ' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00', '2025-06-18'),
('KNS00004', '197710252007011009', 3, 'Permohonan mutasi ke  batulicin', '2025-06-18', 'Baru', '-', '2025-06-18'),
('KNS00005', '1983110520174', 7, 'Prosedur permohonan kenaikan pangkat', '2025-06-24', 'Baru', '-', '2025-06-24'),
('KNS00006', '197710252007011009', 2, 'Pengajuan pengaktifan pegawai setelah berstatus mantan narapidana', '2025-06-27', 'Baru', '-', '2025-06-27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kritik_saran`
--

CREATE TABLE `kritik_saran` (
  `id_kritik_saran` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `instansi` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `kritik` varchar(255) NOT NULL,
  `saran` varchar(255) NOT NULL,
  `penilaian` varchar(50) NOT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kritik_saran`
--

INSERT INTO `kritik_saran` (`id_kritik_saran`, `nama_lengkap`, `instansi`, `jabatan`, `kritik`, `saran`, `penilaian`, `kontak`) VALUES
(1, 'Hajji Sirajuddin', 'Inspektorat Provinsi KALSEL', 'Auditor', 'Sdh Bagus', 'HArus Lebih bagus', 'Sangat Baik', '0812391293129'),
(2, 'Zaenuddin', 'BKN', 'Pegawai', 'Lama Proses Konsultasi', 'Harus Lebih Cepat', 'Cukup', '0812555577723'),
(3, 'fansnto ikrar', 'dinas kehutanan banjarbaru', 'staff khusus', 'pegawai sering tidak ada ditempat', 'beri jadwal pegawai yang wfh', 'Cukup', 'fansto 423@gmail.com'),
(4, 'indra Sumargo', 'dinas pendidikan', 'staff ahli', 'bagian administrasi terlalu sedikit', 'tambah lagi bagian administrasi', 'Cukup', 'indra@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
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
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`nip`, `nik`, `nama_lengkap`, `email`, `id_satker`, `jabatan`) VALUES
('197710252007011009', '637242567203001', 'Aulia Rizhan', 'rizhan23e4@gmail.com', 'DIKERMUM1', 'Pengendalian dan Evaluasi Tata Ruang'),
('1980123020201', '32750180012001', 'Budi Santoso', 'budi.santoso@email.com', 'SMABANBARU1', 'Kepala Sekolah'),
('1983110520174', '32100183110504', 'Rina Marlina', 'rina.marlina@email.com', 'DIKEPES1', 'Staf Ahli'),
('1985071120183', '32750285071103', 'Ahmad Hidayat', 'ahmad.hidayat@email.com', 'DIKERMUM1', 'Kepala Bidang'),
('1989091520216', '32750389091506', 'Lilis Kurniawati', 'lilis.kurnia@email.com', 'DIKERMUM1', 'Penyusun Program'),
('199210132025061003', '6371021310920003', 'Hajji Sirajuddin', 'sirajjudin@bkn.go.id', 'BKN01', 'Staff Bidang Perencanaan Kepegawaian'),
('199510132025061004', '6371021310950004', 'Riza Maulana', 'riza@gmail.com', 'BKN01', 'Kepala Pengadaan Barang dan Jasa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `respon_konsultasi`
--

CREATE TABLE `respon_konsultasi` (
  `id_respon_konsultasi` int(11) NOT NULL,
  `id_konsultasi` varchar(25) NOT NULL,
  `id_konselor` int(11) NOT NULL,
  `isi_respon` text NOT NULL,
  `tanggal_respon` date NOT NULL,
  `lampiran_respon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `respon_konsultasi`
--

INSERT INTO `respon_konsultasi` (`id_respon_konsultasi`, `id_konsultasi`, `id_konselor`, `isi_respon`, `tanggal_respon`, `lampiran_respon`) VALUES
(2, 'KNS00001', 3, 'Konsultasi Kenaikan Pangkat', '2025-06-17', 'lampiran_68514e5200197.pdf'),
(3, 'KNS00002', 3, '-', '2025-06-17', 'lampiran_6851632178d3d.pdf'),
(4, 'KNS00003', 2, '-', '2025-06-17', 'lampiran_68516351be135.pdf'),
(5, 'KNS00004', 2, '-', '2025-06-18', ''),
(6, 'KNS00006', 6, '-', '2025-06-29', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `satker`
--

CREATE TABLE `satker` (
  `id_satker` varchar(100) NOT NULL,
  `satker` varchar(250) NOT NULL,
  `lokasi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `satker`
--

INSERT INTO `satker` (`id_satker`, `satker`, `lokasi`) VALUES
('BKN01', 'Badan Kepegawaian Daerah Kantor Reg VIII', 'Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714'),
('DIHUT1', 'Dinas Kehutanan Provinsi Kalimantan Selatan', 'Jl. A. Yani, Loktabat Sel., Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714'),
('DIKEPES1', 'Kantor Dinas Kependudukan dan Pencatatan Sipil', ' Jl. Sultan Adam No.18, Surgi Mufti, Kec. Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan 70116'),
('DIKERMUM1', 'Dinas Pekerjaan Umum Dan Penataan Ruang Kota Banjarbaru', 'GRJG+WC3, Guntungmanggis, Kec. Landasan Ulin, Kota Banjar Baru, Kalimantan Selatan 70731\r\n'),
('SMABANBARU1', 'SMA Negeri 1 Banjarbaru', ' Jl. Keruing No.3, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan 70714');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `role`) VALUES
(1, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `konselor`
--
ALTER TABLE `konselor`
  ADD PRIMARY KEY (`id_konselor`);

--
-- Indeks untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `nip` (`nip`);

--
-- Indeks untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD PRIMARY KEY (`id_kritik_saran`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `id_satker` (`id_satker`);

--
-- Indeks untuk tabel `respon_konsultasi`
--
ALTER TABLE `respon_konsultasi`
  ADD PRIMARY KEY (`id_respon_konsultasi`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `id_konselor` (`id_konselor`);

--
-- Indeks untuk tabel `satker`
--
ALTER TABLE `satker`
  ADD PRIMARY KEY (`id_satker`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `konselor`
--
ALTER TABLE `konselor`
  MODIFY `id_konselor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  MODIFY `id_kritik_saran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `respon_konsultasi`
--
ALTER TABLE `respon_konsultasi`
  MODIFY `id_respon_konsultasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
