-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pustakaKita
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `ukuran_buku` varchar(50) NOT NULL,
  `halaman` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `cover` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES (13,' 978-623-10-3692-6','Biografi Pahlawan Nasional: Dewi Sartika','Sejarah','Hanugrah R.M.','Silda Impika',2024,'13×19',64,12,'','1776483982_9b5ab68a9a24442fa586.jpeg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(15,'978-623-02-1394-6','Pengantar Dasar Matematika','Matematika','Sri Suryanti, S.Pd., M.Si ','Dr. Irwani Zawawi, M.Kes.',2020,'14×20 cm',165,12,'','1776485414_29eb7781834e4ea9da3b.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(16,'978–602–03–1258–3','Cantik Itu Luka','Sastra','Eka Kurniawan','Gramedia Pustaka Utama',2015,'14×20 cm',150,12,'','1776485509_d0432481a7d2abbcd094.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(17,'978-602-475-022-0','Aljabar Linear Elementer','Matematika','Gandung Sugita','Anggraini',2018,'15.5×23 cm',285,18,'','1776485626_b1221efed3bf225ee4ef.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(18,'978-623-7022-30-5','Geometri Elektrik','Matematika','Mahsup ','Abdillah books',2018,'14×20 cm',62,19,'','1776485695_64048b8435f8da8f2ce3.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(19,'978-602-51195-0-7','History Of The World War, Sejarah Perang Dunia','Sejarah','Saut Pasaribu','Alexander Books',2020,' 14×21 cm',146,15,'','1776486540_73b1b6482e99f3cbaff7.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(22,' 978-623-10-3963-7','Biografi Pahlawan Nasional: H.O.S. Tjokroaminoto','Sejarah','Hanugrah R.M.','Silda Impika',2024,'13×19',64,15,'','1776529800_193979a5ef3116b80b65.png','0000-00-00 00:00:00','0000-00-00 00:00:00'),(25,'978-623-89917-1-6','Ensiklopedia Mini Bilingual','Sains','Dionisius Hargen','Silda Impika',2025,'14X20',104,15,'','1776665525_fde4f143fd51bf53328b.png','0000-00-00 00:00:00','0000-00-00 00:00:00'),(26,'978-623-8587-55-1','Muhammad The Messenger: Periode Mekah','Islam',' Samih `Athif Zain','Elex Media Komputindo',2024,'14x20',500,7,'','1776927012_643f0c06212476b35e49.avif','0000-00-00 00:00:00','0000-00-00 00:00:00'),(27,'979-3062-79-7','Pengantar Bahasa Pemrograman dan Aplikasinya dengan Pascal','Teknologi',' Mohammad Ghani, Ph.D','Bintang Semesta media',2026,'14x20',112,1,'','1776927244_0a5988d9a3d38dee2fae.avif','0000-00-00 00:00:00','0000-00-00 00:00:00'),(29,'978-0135455647','biology – campbel','Biologi','Lisa A. Urry','Pearson',2025,'14x20',250,15,'','1776928292_f20ca8230d36d561df6f.webp','0000-00-00 00:00:00','0000-00-00 00:00:00'),(30,'978-0135236321','Chemistry: The Central Science','Matematika','Brown','Pearson',2023,'14x20',1248,10,'','1776928470_774bb3b78b887e6051ae.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00'),(31,'978-979-781-039-9','Kimia Dasar: Konsep-Konsep Inti','Sastra','Raymond Chang','Erlangga',2017,'14x20',500,10,'','1776928783_c30c235a92d20aa2e254.jpg','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite`
--

DROP TABLE IF EXISTS `favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorite` (
  `id_favorite` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_favorite`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite`
--

LOCK TABLES `favorite` WRITE;
/*!40000 ALTER TABLE `favorite` DISABLE KEYS */;
INSERT INTO `favorite` VALUES (1,4,13,'2026-04-22 00:27:43');
/*!40000 ALTER TABLE `favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) NOT NULL,
  `durasi` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL,
  `denda` int(11) NOT NULL,
  `status_bayar` enum('n/a','belum','lunas') DEFAULT 'n/a',
  `status` enum('dipinjam','dikembalikan','ditolak','pending') NOT NULL,
  `tanggal_pinjam` datetime NOT NULL,
  `tanggal_kembali` datetime NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pinjam`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (75,15,'1',4,20000,'n/a','','2026-04-25 13:35:42','0000-00-00 00:00:00',NULL,'Transfer'),(76,13,'',4,0,'n/a','','2026-04-28 09:34:38','0000-00-00 00:00:00',NULL,NULL),(77,13,'',4,0,'n/a','','2026-04-28 09:45:32','0000-00-00 00:00:00',NULL,NULL),(78,15,'',4,0,'n/a','','2026-04-28 09:46:03','0000-00-00 00:00:00',NULL,NULL),(79,15,'',4,0,'n/a','','2026-04-28 09:49:56','0000-00-00 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulasan_buku`
--

DROP TABLE IF EXISTS `ulasan_buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ulasan_buku` (
  `id_ulasan` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) NOT NULL,
  `id_user` int(10) NOT NULL,
  `rating` tinyint(1) DEFAULT 0,
  `ulasan` text DEFAULT NULL,
  `tanggal_ulasan` datetime DEFAULT current_timestamp(),
  `is_read_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_ulasan`),
  KEY `fk_buku_ulasan` (`id_buku`),
  KEY `fk_users_ulasan` (`id_user`),
  CONSTRAINT `fk_buku_ulasan` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_ulasan` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulasan_buku`
--

LOCK TABLES `ulasan_buku` WRITE;
/*!40000 ALTER TABLE `ulasan_buku` DISABLE KEYS */;
INSERT INTO `ulasan_buku` VALUES (1,16,4,5,'kerennn bangettt nihhh bukuuuu','2026-04-21 21:41:43',0);
/*!40000 ALTER TABLE `ulasan_buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `role` enum('admin','user','petugas') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL,
  `password` varchar(100) NOT NULL,
  `foto` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Rezza Asrafal Huda','','','082312632693','zaasfaldaa_','admin','aktif','$2y$10$Il06BNABST4/up03AvbMLut5qfSEkGWes/Z9T.sem/lYDKo8CwHGu','1762910583_d5dcb61730bf118bfa90.png','2026-04-20 16:53:09'),(4,'kojoyyy','','','','ezott','user','aktif','$2y$10$GyMpQ0BsAHRlsecHpdoJLO1hMOzjbYCpx8utNRlo/ncy5LTDB0vuq','1762910614_53bc634a68d8353955bf.jpg','2026-04-11 16:35:57'),(6,'ojoyyy','','','','kojoyyy','petugas','aktif','$2y$10$8.PYqjvDMRtV0jgf8Bcztuz71xO7l0H2V8Yi2vjw5UYhpy6KavmZ2','1776703893_99e20da84bd70665ea10.png','2026-04-20 16:52:44'),(8,'Fariq Irsyad','','','','fariquser','','aktif','$2y$10$JO711VpeB9hAPR.0mMGdReuEcQQmTIJphXAjKZb9fg2LkMZtU.7ta','1777356860_603d705b7fbbd70aac40.png','2026-04-28 06:14:21');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-28 16:57:39
