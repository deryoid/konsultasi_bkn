-- MySQL dump 10.13  Distrib 9.4.0, for macos26.0 (arm64)
--
-- Host: localhost    Database: konsultasi_bkn
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Jenjang Karir','-'),(2,'Permasalahan Disiplin','-'),(3,'Permohonan Mutasi','-'),(4,'Kesejahteraan Pegawai','-'),(5,'Masalah Pribadi (psikososial)','-'),(7,'Kenaikan pangkat','-');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konselor`
--

DROP TABLE IF EXISTS `konselor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `konselor` (
  `id_konselor` int NOT NULL AUTO_INCREMENT,
  `nama_konselor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan_konselor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `keahlian` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_konselor`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konselor`
--

LOCK TABLES `konselor` WRITE;
/*!40000 ALTER TABLE `konselor` DISABLE KEYS */;
INSERT INTO `konselor` VALUES (9,'Eka Wahyu Sholeha','Konselor Ahli','Jenjang Karir','eka.wahyu@bkn.go.id','Aktif',5),(10,'Wahidin','Konselor Ahli','Kenaikan Pangkat','dery037yj@gmail.com','Aktif',9);
/*!40000 ALTER TABLE `konselor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konsultasi`
--

DROP TABLE IF EXISTS `konsultasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `konsultasi` (
  `id_konsultasi` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kategori` int NOT NULL,
  `id_konselor` int DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_respon` date NOT NULL,
  PRIMARY KEY (`id_konsultasi`),
  KEY `id_kategori` (`id_kategori`),
  KEY `nip` (`nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konsultasi`
--

LOCK TABLES `konsultasi` WRITE;
/*!40000 ALTER TABLE `konsultasi` DISABLE KEYS */;
INSERT INTO `konsultasi` VALUES ('KNS00001','199210132025061003',5,NULL,'Konsultasi Masalah Pribadi','2025-06-17','Menunggu','Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00','2025-06-18'),('KNS00002','199210132025061003',3,NULL,'Konsultasi Permohonan Perpindahan Penempatan Dinas di HSS','2025-06-17','Menunggu',' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00','2025-06-18'),('KNS00003','199510132025061004',4,NULL,'Konsultasi Kesejahteraan Pegawai','2025-06-17','Menunggu',' Anda sudah bisa melakukan konsultasi pada Tanggal : dd/mm/yyyy, di lokasi .............. pada pukul : 00:00','2025-06-18'),('KNS00004','197710252007011009',3,NULL,'Permohonan mutasi ke  batulicin','2025-06-18','Menunggu','-','2025-06-18'),('KNS00005','1983110520174',7,NULL,'Prosedur permohonan kenaikan pangkat','2025-06-24','Menunggu','-','2025-06-24'),('KNS00006','197710252007011009',2,NULL,'Pengajuan pengaktifan pegawai setelah berstatus mantan narapidana','2025-06-27','Menunggu','-','2025-06-27'),('KNS00007','197710252007011009',7,NULL,'112312312','2026-02-04','Menunggu','12312312312312','2026-02-04'),('KNS00008','199210132025061003',7,NULL,'123','2026-02-04','Diproses','123','2026-02-04'),('KNS00009','199210132025061009',1,NULL,'Konsultasi Jenjang Karir','2026-02-04','Diproses','Saya ingin konsultasi','2026-02-04'),('KNS00010','199510132025061004',7,NULL,'Konsul','2026-04-15','Diproses','konsul','2026-04-15'),('KNS00011','199510132025061004',1,10,'Konsul','2026-07-31','Menunggu','test','2026-07-31');
/*!40000 ALTER TABLE `konsultasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kritik_saran`
--

DROP TABLE IF EXISTS `kritik_saran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kritik_saran` (
  `id_kritik_saran` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `instansi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kritik` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `saran` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `penilaian` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `kontak` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id_kritik_saran`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kritik_saran`
--

LOCK TABLES `kritik_saran` WRITE;
/*!40000 ALTER TABLE `kritik_saran` DISABLE KEYS */;
INSERT INTO `kritik_saran` VALUES (1,'Hajji Sirajuddin','Inspektorat Provinsi KALSEL','Auditor',NULL,'Sdh Bagus','HArus Lebih bagus','Sangat Baik','0812391293129','2026-02-10'),(2,'Zaenuddin','BKN','Pegawai',NULL,'Lama Proses Konsultasi','Harus Lebih Cepat','Cukup','0812555577723','2026-02-10'),(3,'fansnto ikrar','dinas kehutanan banjarbaru','staff khusus',NULL,'pegawai sering tidak ada ditempat','beri jadwal pegawai yang wfh','Cukup','fansto 423@gmail.com','2026-02-10'),(4,'indra Sumargo','dinas pendidikan','staff ahli',NULL,'bagian administrasi terlalu sedikit','tambah lagi bagian administrasi','Cukup','indra@gmail.com','2026-02-10'),(7,'Hajji Sirajuddin','Badan Kepegawaian Negara','Staff Bidang Perencanaan Kepegawaian','199210132025061003','Sudah Bagus ','Mohon ditingkatkan','Sangat Baik','infinikanus@gmail.com','2026-02-10'),(8,'Test User','Test Instansi','Test Jabatan',NULL,'Test Kritik','Test Saran','Baik','08123456789',NULL),(9,'Hajji Sirajuddin','Badan Kepegawaian Negara','Staff Bidang Perencanaan Kepegawaian',NULL,'sudah cukup bagus','tingkatkan pelayanan','Cukup','haji@gmail.com',NULL),(10,'Hajji Sirajuddin','Badan Kepegawaian Negara','Staff Bidang Perencanaan Kepegawaian',NULL,'bagus','sudah bagus','Sangat Baik','haji@gmail.com','2026-02-12');
/*!40000 ALTER TABLE `kritik_saran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pegawai`
--

DROP TABLE IF EXISTS `pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pegawai` (
  `nip` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_satker` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `file_pendukung` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_pendukung_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`nip`),
  KEY `id_satker` (`id_satker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pegawai`
--

LOCK TABLES `pegawai` WRITE;
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
INSERT INTO `pegawai` VALUES ('197710252007011009','637242567203001','Aulia Rizhan','rizhan23e4@gmail.com','DIKERMUM1','Pengendalian dan Evaluasi Tata Ruang',NULL,NULL),('1980123020201','32750180012001','Budi Santoso','budi.santoso@email.com','SMABANBARU1','Kepala Sekolah',NULL,NULL),('1983110520174','32100183110504','Rina Marlina','rina.marlina@email.com','DIKEPES1','Staf Ahli',NULL,NULL),('1985071120183','32750285071103','Ahmad Hidayat','ahmad.hidayat@email.com','DIKERMUM1','Kepala Bidang',NULL,NULL),('1989091520216','32750389091506','Lilis Kurniawati','lilis.kurnia@email.com','DIKERMUM1','Penyusun Program',NULL,NULL),('199210132025061003','6371021310920003','Hajji Sirajuddin','infinikanus@gmail.com','BKN01','Staff Bidang Perencanaan Kepegawaian','SK_199210132025061003_1770188574.png','ChatGPT Image Dec 23, 2025, 07_38_27 PM.png'),('199210132025061009','637201232399928093','Akhmad Dian','adifajar640@gmail.com','DIKEPES1','Staff IT','SK_199210132025061009_1770205925.png','ChatGPT Image Dec 23, 2025, 07_38_27 PM.png'),('199510132025061004','6371021310950004','Riza Maulana','riza@gmail.com','BKN01','Kepala Pengadaan Barang dan Jasa',NULL,NULL);
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respon_konsultasi`
--

DROP TABLE IF EXISTS `respon_konsultasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `respon_konsultasi` (
  `id_respon_konsultasi` int NOT NULL AUTO_INCREMENT,
  `id_konsultasi` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `id_konselor` int NOT NULL,
  `isi_respon` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_respon` date NOT NULL,
  `lampiran_respon` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_respon_konsultasi`),
  KEY `id_konsultasi` (`id_konsultasi`),
  KEY `id_konselor` (`id_konselor`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respon_konsultasi`
--

LOCK TABLES `respon_konsultasi` WRITE;
/*!40000 ALTER TABLE `respon_konsultasi` DISABLE KEYS */;
INSERT INTO `respon_konsultasi` VALUES (2,'KNS00001',3,'Konsultasi Kenaikan Pangkat','2025-06-17','lampiran_68514e5200197.pdf'),(3,'KNS00002',3,'-','2025-06-17','lampiran_6851632178d3d.pdf'),(4,'KNS00003',2,'-','2025-06-17','lampiran_68516351be135.pdf'),(5,'KNS00004',2,'-','2025-06-18',''),(6,'KNS00006',6,'12312312321','2025-06-29','lampiran_6982e4895c84b.png'),(7,'KNS00008',2,'123123','2026-02-04',''),(8,'KNS00009',3,'Kamu harus meningkat kompetensi pegawai yang kamu miliki sebagai ASN','2026-02-04','lampiran_698333a5b44dc.png'),(9,'KNS00009',3,'Kamu harus meningkat kompetensi pegawai yang kamu miliki sebagai ASN','2026-02-04','lampiran_698333ce6c00e.png'),(10,'KNS00010',10,'oke','2026-04-15','');
/*!40000 ALTER TABLE `respon_konsultasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satker`
--

DROP TABLE IF EXISTS `satker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satker` (
  `id_satker` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `satker` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_satker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satker`
--

LOCK TABLES `satker` WRITE;
/*!40000 ALTER TABLE `satker` DISABLE KEYS */;
INSERT INTO `satker` VALUES ('BKN01','Badan Kepegawaian Daerah Kantor Reg VIII','Jl. Bhayangkara No.1, Sungai Besar, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714'),('DIHUT1','Dinas Kehutanan Provinsi Kalimantan Selatan','Jl. A. Yani, Loktabat Sel., Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714'),('DIKEPES1','Kantor Dinas Kependudukan dan Pencatatan Sipil',' Jl. Sultan Adam No.18, Surgi Mufti, Kec. Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan 70116'),('DIKERMUM1','Dinas Pekerjaan Umum Dan Penataan Ruang Kota Banjarbaru','GRJG+WC3, Guntungmanggis, Kec. Landasan Ulin, Kota Banjar Baru, Kalimantan Selatan 70731\r\n'),('SMABANBARU1','SMA Negeri 1 Banjarbaru',' Jl. Keruing No.3, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan 70714');
/*!40000 ALTER TABLE `satker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Admin','admin','21232f297a57a5a743894a0e4a801fc3','Admin'),(5,'Eka Wahyu Sholeha','eka','79ee82b17dfb837b1be94a6827fa395a','Konselor'),(9,'wahidin','wahidin','660a97fb084ce39e7ef7777c278fca3d','Konselor'),(10,'Riza Maulana','199510132025061004','e10adc3949ba59abbe56e057f20f883e','User');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-31 14:37:18
