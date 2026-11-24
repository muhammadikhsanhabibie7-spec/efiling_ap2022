/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: efiling
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB

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
-- Table structure for table `arsip`
--

DROP TABLE IF EXISTS `arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip` (
  `id_arsip` int(11) NOT NULL AUTO_INCREMENT,
  `id_par_arsip` int(11) DEFAULT NULL,
  `id_arsip_jenis` int(11) NOT NULL,
  `op` int(11) NOT NULL,
  `arsip` varchar(150) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `lokasi` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_arsip`),
  KEY `id_arsip_jenis` (`id_arsip_jenis`),
  KEY `id_par_arsip` (`id_par_arsip`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip`
--

LOCK TABLES `arsip` WRITE;
/*!40000 ALTER TABLE `arsip` DISABLE KEYS */;
INSERT INTO `arsip` VALUES
(39,NULL,5,9,'355_20112026_215502_activity_diagram_-_recepsionis.pdf','2026-11-20 21:55:02',NULL),
(40,NULL,6,9,'852_20112026_220852_Analisis_Distribusi_Frekuensi_Salon_Chepak.xlsx','2026-11-20 22:08:52',NULL),
(41,NULL,2,9,'1000_20112026_221632_activity_diagram-pasienn.pdf','2026-11-20 22:16:32',NULL),
(43,NULL,6,91,'689_21112026_135528_Analisis_Distribusi_Frekuensi_Salon_Chepak_(1).xlsx','2026-11-21 13:55:28',NULL),
(44,NULL,5,93,'319_21112026_225031_UseCase_Diagram_RS_LP3I.png','2026-11-21 22:50:31',NULL);
/*!40000 ALTER TABLE `arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_akses`
--

DROP TABLE IF EXISTS `arsip_akses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_akses` (
  `id_akses` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_jenis` int(11) DEFAULT NULL,
  `id_unit` int(11) NOT NULL,
  `id_bidang` int(11) DEFAULT NULL,
  `id_par_bidang` int(11) NOT NULL,
  PRIMARY KEY (`id_akses`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_akses`
--

LOCK TABLES `arsip_akses` WRITE;
/*!40000 ALTER TABLE `arsip_akses` DISABLE KEYS */;
INSERT INTO `arsip_akses` VALUES
(2,4,27,1,0);
/*!40000 ALTER TABLE `arsip_akses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_audit_log`
--

DROP TABLE IF EXISTS `arsip_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_audit_log` (
  `id_audit` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` datetime NOT NULL DEFAULT current_timestamp(),
  `id_pegawai` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `nama_user` varchar(150) DEFAULT NULL,
  `nama_role` varchar(100) DEFAULT NULL,
  `modul` varchar(60) NOT NULL,
  `aksi` varchar(40) NOT NULL,
  `objek` varchar(200) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `ip_address` varchar(60) DEFAULT NULL,
  `device_cookie` varchar(80) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `url_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_audit`),
  KEY `idx_audit_waktu` (`waktu`),
  KEY `idx_audit_user` (`id_pegawai`),
  KEY `idx_audit_modul` (`modul`),
  KEY `idx_audit_aksi` (`aksi`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_audit_log`
--

LOCK TABLES `arsip_audit_log` WRITE;
/*!40000 ALTER TABLE `arsip_audit_log` DISABLE KEYS */;
INSERT INTO `arsip_audit_log` VALUES
(1,'2026-11-21 01:01:35',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(2,'2026-11-21 01:05:37',NULL,'nimda','nimda','-','Autentikasi','LOGIN_GAGAL','nimda','Kode Captcha tidak cocok','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(3,'2026-11-21 01:05:47',0,'nimda','Root Administrator','Root','Autentikasi','LOGIN','nimda','Login Root Administrator berhasil','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(4,'2026-11-21 01:14:09',0,'nimda','Root Administrator','Root','Autentikasi','LOGOUT','','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(5,'2026-11-21 01:14:34',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(6,'2026-11-21 03:25:57',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(7,'2026-11-21 03:26:06',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(8,'2026-11-21 03:55:02',9,'admin','admin','Super Admin','Pengarsipan','TAMBAH','4546156165 - Surat Penagihan Pembayaran','Kategori: Bukti Pembayaran | Jenis: Surat Masuk | Tgl: 2026-11-20 | Pengirim: ikhsan | Penerima: jidan | Status: Aktif | File diunggah: 355_20112026_215502_activity_diagram_-_recepsionis.pdf','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(9,'2026-11-21 04:08:52',9,'admin','admin','Super Admin','Pengarsipan','TAMBAH','78489449 - Surat Pembelian AC Daikin','Kategori: Bukti Pembelian | Jenis: Surat Masuk | Tgl: 2026-11-20 | Pengirim: PT Sejuk Abadi | Penerima: Polija | Status: Aktif | File diunggah: 852_20112026_220852_Analisis_Distribusi_Frekuensi_Salon_Chepak.xlsx','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(10,'2026-11-21 04:16:32',9,'admin','admin','Super Admin','Pengarsipan','TAMBAH','3412-MCS-2026 - Arsip Pegawai Jidan','Kategori: Data Riwayat Hidup Pegawai | Jenis: Surat Masuk | Tgl: 2026-11-20 | Pengirim: PT Solusi  | Penerima: Jaka | Status: Aktif | File diunggah: 1000_20112026_221632_activity_diagram-pasienn.pdf','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(11,'2026-11-21 04:16:32',9,'admin','admin','Super Admin','Pengarsipan','TAMBAH','3412-MCS-2026 - Arsip Pegawai Jidan','Kategori: Data Riwayat Hidup Pegawai | Jenis: Surat Masuk | Tgl: 2026-11-20 | Pengirim: PT Solusi  | Penerima: Jaka | Status: Aktif | File diunggah: 857_20112026_221632_activity_diagram-pasienn.pdf','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(12,'2026-11-21 04:26:29',9,'admin','admin','Super Admin','Peminjaman','TAMBAH','4546156165 - Surat Penagihan Pembayaran','Pengajuan peminjaman oleh Reza (Super Admin) | Durasi: 5 hari (2026-11-21 s/d 2026-11-26) | Keperluan: Untuk Audit','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/peminjaman/save'),
(13,'2026-11-21 04:28:40',9,'admin','admin','Super Admin','Disposisi','TAMBAH','78489449 - Surat Pembelian AC Daikin','Tujuan: Bagian Keuangan dan Perlengkapan / Staf Admin | Sifat: Segera | Instruksi: Buat surat balasan / tanggapan | Tembusan: Wadir 2','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/disposisi/save'),
(14,'2026-11-21 06:44:18',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(15,'2026-11-21 06:48:45',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/login_process'),
(16,'2026-11-21 06:51:41',93,'staf1','Staf Operator','Operator','Peminjaman','TAMBAH','3412-MCS-2026 - Arsip Pegawai Jidan','Pengajuan peminjaman oleh Staf Operator (Umum Kampus) | Durasi: 3 hari (2026-11-21 s/d 2026-11-24) | Keperluan: Keperluan rapat koordinasi (Demo Role Operator)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','filing/peminjaman/save'),
(17,'2026-11-21 06:52:31',93,'staf1','Staf Operator','Operator','Autentikasi','LOGOUT','staf1','Pengguna keluar dari sistem','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/process_logout'),
(18,'2026-11-21 06:54:16',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/login_process'),
(19,'2026-11-21 06:55:46',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/process_logout'),
(20,'2026-11-21 06:56:24',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/login_process'),
(21,'2026-11-21 06:59:29',93,'staf1','Staf Operator','Operator','Autentikasi','LOGOUT','staf1','Pengguna keluar dari sistem','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/process_logout'),
(22,'2026-11-21 07:06:35',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.33.1','login/login_process'),
(23,'2026-11-21 07:06:56',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(24,'2026-11-21 07:09:40',9,'admin','admin','Super Admin','Peminjaman','TERIMA','3412-MCS-2026 - Arsip Pegawai Jidan','Peminjaman #2 oleh Staf Operator -> Dipinjam | Catatan: Di terima','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/peminjaman/konfirmasi'),
(25,'2026-11-21 07:10:07',9,'admin','admin','Super Admin','Disposisi','TINDAK_LANJUT','78489449 - Surat Pembelian AC Daikin','Disposisi #1 | Status TL: Menunggu -> Diproses | Catatan TL: tunggu ya','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/disposisi/update_tl'),
(26,'2026-11-21 07:12:08',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(27,'2026-11-21 07:12:22',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(28,'2026-11-21 07:12:58',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(29,'2026-11-21 07:13:05',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(30,'2026-11-21 07:13:12',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(31,'2026-11-21 07:14:31',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(32,'2026-11-21 07:14:38',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(33,'2026-11-21 07:14:45',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(34,'2026-11-21 07:15:11',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(35,'2026-11-21 07:15:24',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(36,'2026-11-21 07:24:19',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(37,'2026-11-21 07:24:25',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(38,'2026-11-21 07:24:32',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(39,'2026-11-21 07:26:09',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(40,'2026-11-21 07:26:23',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(41,'2026-11-21 07:27:26',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(42,'2026-11-21 07:27:34',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(43,'2026-11-21 07:27:41',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(44,'2026-11-21 19:52:22',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(45,'2026-11-21 19:55:28',91,'admin_arsip','Admin Arsip','ADMIN','Pengarsipan','TAMBAH','B1912E-E1-2026 - Pembeli PC AXIOO','Kategori: Bukti Pembelian | Jenis: Surat Masuk | Tgl: 2026-11-21 | Pengirim: PT Axioo Technology | Penerima: Polija | Status: Aktif | File diunggah: 689_21112026_135528_Analisis_Distribusi_Frekuensi_Salon_Chepak_(1).xlsx','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','filing/pengarsipan/save_arsip'),
(46,'2026-11-22 04:06:39',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(47,'2026-11-22 04:07:32',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(48,'2026-11-22 04:07:55',92,'pimpinan1','Pimpinan Kampus','Pimpinan','Autentikasi','LOGIN','pimpinan1','Login berhasil sebagai Pimpinan Kampus (Pimpinan)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(49,'2026-11-22 04:08:34',92,'pimpinan1','Pimpinan Kampus','Pimpinan','Autentikasi','LOGOUT','pimpinan1','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(50,'2026-11-22 04:16:52',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(51,'2026-11-22 04:17:59',93,'staf1','Staf Operator','Operator','Peminjaman','TAMBAH','B1912E-E1-2026 - Pembeli PC AXIOO','Pengajuan peminjaman oleh Staf Operator (Umum Kampus) | Durasi: 15 hari (2026-11-22 s/d 2026-12-07) | Keperluan: Peninjauan','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','filing/peminjaman/save'),
(52,'2026-11-22 04:18:42',93,'staf1','Staf Operator','Operator','Autentikasi','LOGOUT','staf1','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(53,'2026-11-22 04:19:40',NULL,'admin_arsip','admin_arsip','-','Autentikasi','LOGIN_GAGAL','admin_arsip','Kode Captcha tidak cocok','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(54,'2026-11-22 04:19:46',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(55,'2026-11-22 04:23:27',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(56,'2026-11-22 04:23:36',NULL,'staf1','staf1','-','Autentikasi','LOGIN_GAGAL','staf1','Kode Captcha tidak cocok','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(57,'2026-11-22 04:23:47',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(58,'2026-11-22 04:26:03',93,'staf1','Staf Operator','Operator','Autentikasi','LOGOUT','staf1','Pengguna keluar dari sistem','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/process_logout'),
(59,'2026-11-22 04:26:30',NULL,'admin_arsip','admin_arsip','-','Autentikasi','LOGIN_GAGAL','admin_arsip','Kode Captcha tidak cocok','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(60,'2026-11-22 04:26:41',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-0D451FF95BC92033','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0','login/login_process'),
(61,'2026-11-22 04:30:33',NULL,'staf1','staf1','-','Autentikasi','LOGIN_GAGAL','staf1','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(62,'2026-11-22 04:30:40',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(63,'2026-11-22 04:37:10',93,'staf1','Staf Operator','Operator','Disposisi','TAMBAH','B1912E-E1-2026 - Pembeli PC AXIOO','Tujuan: Bagian Keuangan dan Perlengkapan / Staf Operator / Staf Admin | Sifat: Segera | Instruksi: Koordinasikan dengan unit terkait | Tembusan: Wadir 1','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/disposisi/save'),
(64,'2026-11-22 04:50:31',93,'staf1','Staf Operator','Operator','Pengarsipan','TAMBAH','A414-BM4B-2026 - Laporan Pengiriman Satu Ton Briket Kelapa','Kategori: Bukti Pembayaran | Jenis: Surat Keluar | Tgl: 2026-11-21 | Pengirim: PT Andika Sejahtera | Penerima: Jaka | Status: Aktif | File diunggah: 319_21112026_225031_UseCase_Diagram_RS_LP3I.png','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(65,'2026-11-22 04:51:31',93,'staf1','Staf Operator','Operator','Autentikasi','LOGOUT','staf1','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(66,'2026-11-22 04:51:44',NULL,'pimpinan1','pimpinan1','-','Autentikasi','LOGIN_GAGAL','pimpinan1','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(67,'2026-11-22 04:51:51',92,'pimpinan1','Pimpinan Kampus','Pimpinan','Autentikasi','LOGIN','pimpinan1','Login berhasil sebagai Pimpinan Kampus (Pimpinan)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(68,'2026-11-22 09:50:27',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGIN','admin_arsip','Login berhasil sebagai Admin Arsip (ADMIN)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.34.0','login/login_process'),
(69,'2026-11-22 09:52:45',91,'admin_arsip','Admin Arsip','ADMIN','Peminjaman','TERIMA','B1912E-E1-2026 - Pembeli PC AXIOO','Peminjaman #3 oleh Staf Operator -> Dipinjam | Catatan: Disetujui Admin Arsip (Demo Role ADMIN)','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.34.0','filing/peminjaman/konfirmasi'),
(70,'2026-11-22 09:56:09',91,'admin_arsip','Admin Arsip','ADMIN','Autentikasi','LOGOUT','admin_arsip','Pengguna keluar dari sistem','::1','DEV-8E7E5F06B2D3718B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.34.0','login/process_logout'),
(71,'2026-11-22 09:58:02',NULL,'staf1','staf1','-','Autentikasi','LOGIN_GAGAL','staf1','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(72,'2026-11-22 09:58:09',93,'staf1','Staf Operator','Operator','Autentikasi','LOGIN','staf1','Login berhasil sebagai Staf Operator (Operator)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(73,'2026-11-22 10:01:36',93,'staf1','Staf Operator','Operator','Pengarsipan','TAMBAH','0BD55-2026 - Laporan Pembelian Server','Kategori: Laporan Keuangan | Jenis: Surat Keluar | Tgl: 2026-11-22 | Pengirim: PT Server Indo SYB | Penerima: jidan | Status: Aktif','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/pengarsipan/save_arsip'),
(74,'2026-11-22 10:02:17',93,'staf1','Staf Operator','Operator','Disposisi','TINDAK_LANJUT','B1912E-E1-2026 - Pembeli PC AXIOO','Disposisi #2 | Status TL: Menunggu -> Selesai | Catatan TL: Di Setujui','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/disposisi/update_tl'),
(75,'2026-11-22 10:03:39',93,'staf1','Staf Operator','Operator','Peminjaman','TAMBAH','0BD55-2026 - Laporan Pembelian Server','Pengajuan peminjaman oleh Staf Manager (Umum Kampus) | Durasi: 7 hari (2026-11-22 s/d 2026-11-29) | Keperluan: Untuk Mengajukan Penambahan Jumlah Server','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','filing/peminjaman/save'),
(76,'2026-11-22 13:42:28',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(77,'2026-11-22 13:42:38',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(78,'2026-11-22 14:15:20',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(79,'2026-11-22 14:16:08',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(80,'2026-11-22 14:16:20',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(81,'2026-11-23 11:52:55',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(82,'2026-11-23 12:33:11',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/process_logout'),
(83,'2026-11-23 13:02:22',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(85,'2026-11-23 22:45:45',96,'muhammad_ikhsan_habibie','Muhammad Ikhsan Habibie','Operator','Autentikasi','REGISTER','muhammad_ikhsan_habibie','Registrasi akun baru sebagai Operator (Muhammad Ikhsan Habibie | Politeknik LP3I Jakarta - Bagian Keuangan dan Perlengkapan)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','register/process'),
(86,'2026-11-23 22:46:05',NULL,'Muhammad Ikhsan Habibie','Muhammad Ikhsan Habibie','-','Autentikasi','LOGIN_GAGAL','Muhammad Ikhsan Habibie','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(87,'2026-11-23 22:46:07',NULL,'Muhammad Ikhsan Habibie','Muhammad Ikhsan Habibie','-','Autentikasi','LOGIN_GAGAL','Muhammad Ikhsan Habibie','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(88,'2026-11-23 22:46:07',NULL,'Muhammad Ikhsan Habibie','Muhammad Ikhsan Habibie','-','Autentikasi','LOGIN_GAGAL','Muhammad Ikhsan Habibie','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(89,'2026-11-23 22:53:01',97,'muhammad_ikhsan_habibie','Muhammad Ikhsan Habibie','Operator','Autentikasi','REGISTER','muhammad_ikhsan_habibie','Registrasi akun baru sebagai Operator (Muhammad Ikhsan Habibie | Politeknik LP3I Jakarta - Bagian Keuangan dan Perlengkapan)','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','register/process'),
(90,'2026-11-23 22:53:16',NULL,'147258369','147258369','-','Autentikasi','LOGIN_GAGAL','147258369','Kode Captcha tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(91,'2026-11-23 22:53:26',NULL,'147258369','147258369','-','Autentikasi','LOGIN_GAGAL','147258369','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(92,'2026-11-23 22:53:28',NULL,'147258369','147258369','-','Autentikasi','LOGIN_GAGAL','147258369','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(93,'2026-11-23 22:53:28',NULL,'147258369','147258369','-','Autentikasi','LOGIN_GAGAL','147258369','Username atau Password tidak cocok','::1','DEV-5529E07CB73BE8C7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','login/login_process'),
(94,'2026-11-24 17:29:15',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/login_process'),
(95,'2026-11-24 17:33:20',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/process_logout'),
(96,'2026-11-24 17:34:49',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Kode Captcha tidak cocok','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/login_process'),
(97,'2026-11-24 17:34:59',NULL,'admin','admin','-','Autentikasi','LOGIN_GAGAL','admin','Password salah','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/login_process'),
(98,'2026-11-24 17:35:09',9,'admin','admin','Super Admin','Autentikasi','LOGIN','admin','Login berhasil sebagai admin (Super Admin)','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/login_process'),
(99,'2026-11-24 18:44:25',9,'admin','admin','Super Admin','Autentikasi','LOGOUT','admin','Pengguna keluar dari sistem','::1','DEV-1E7E5FCCA1A3099B','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36 Edg/158.0.0.0','login/process_logout');
/*!40000 ALTER TABLE `arsip_audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_disposisi`
--

DROP TABLE IF EXISTS `arsip_disposisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_disposisi` (
  `id_disposisi` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) unsigned NOT NULL,
  `no_agenda` varchar(50) DEFAULT NULL,
  `tgl_disposisi` date NOT NULL,
  `dari_pegawai` int(11) DEFAULT NULL,
  `tujuan_bidang` int(11) DEFAULT NULL COMMENT 'Diteruskan ke Unit Organisasi/Bidang',
  `tujuan_pegawai` int(11) DEFAULT NULL COMMENT 'Diteruskan ke Pegawai/PIC',
  `diteruskan_kepada` varchar(200) DEFAULT NULL,
  `sifat` enum('Biasa','Penting','Segera','Rahasia') NOT NULL DEFAULT 'Biasa',
  `instruksi` text NOT NULL COMMENT 'Instruksi Disposisi',
  `tembusan` varchar(255) DEFAULT NULL COMMENT 'Tembusan (Mengetahui)',
  `catatan` text DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL COMMENT 'Tindak Lanjut (TL)',
  `status_tl` enum('Menunggu','Diproses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `tgl_tindak_lanjut` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_disposisi`),
  KEY `idx_disp_konten` (`id_arsip_konten`),
  KEY `idx_disp_status` (`status_tl`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_disposisi`
--

LOCK TABLES `arsip_disposisi` WRITE;
/*!40000 ALTER TABLE `arsip_disposisi` DISABLE KEYS */;
INSERT INTO `arsip_disposisi` VALUES
(1,33,'','2026-11-21',9,1,NULL,'Staf Admin','Segera','Buat surat balasan / tanggapan','Wadir 2','','tunggu ya','Diproses','2026-11-21 01:10:07',9,'2026-11-20 21:28:40'),
(2,36,'B534G-2026','2026-11-22',93,1,93,'Staf Admin','Segera','Koordinasikan dengan unit terkait','Wadir 1','','Di Setujui','Selesai','2026-11-22 04:02:17',93,'2026-11-21 21:37:10');
/*!40000 ALTER TABLE `arsip_disposisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_kait`
--

DROP TABLE IF EXISTS `arsip_kait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_kait` (
  `id_arsip_kait` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip` int(11) NOT NULL,
  `id_arsip_jenis` int(11) NOT NULL,
  `id_kait` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_arsip_kait`),
  KEY `id_arsip` (`id_arsip`),
  KEY `id_arsip_jenis` (`id_arsip_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_kait`
--

LOCK TABLES `arsip_kait` WRITE;
/*!40000 ALTER TABLE `arsip_kait` DISABLE KEYS */;
/*!40000 ALTER TABLE `arsip_kait` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_kolom`
--

DROP TABLE IF EXISTS `arsip_kolom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_kolom` (
  `id_kolom` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) DEFAULT NULL,
  `id_arsip_kolom` int(11) DEFAULT NULL,
  `konten` text DEFAULT NULL,
  PRIMARY KEY (`id_kolom`),
  KEY `id_arsip_konten` (`id_arsip_konten`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_kolom`
--

LOCK TABLES `arsip_kolom` WRITE;
/*!40000 ALTER TABLE `arsip_kolom` DISABLE KEYS */;
INSERT INTO `arsip_kolom` VALUES
(7,1,4,'511810935121'),
(8,1,5,'PLN'),
(9,1,6,'Rp 69.196'),
(10,1,7,'enam puluh sembilan ribu seratus sembilan puluh enam'),
(11,1,8,'2018-07-25'),
(12,1,9,'Slamet Wibisono'),
(19,9,11,'19'),
(20,9,12,'Surat'),
(21,10,11,'19'),
(22,10,12,'Surat'),
(25,11,11,'18'),
(26,11,12,'Aplikasi'),
(29,12,11,'18'),
(30,12,12,'Video'),
(47,13,11,'-'),
(48,13,12,'Musik'),
(49,24,10,'2018-06-06'),
(50,25,10,'2018-06-06'),
(51,30,13,'100000000'),
(58,32,4,''),
(59,32,5,''),
(60,32,6,''),
(61,32,7,''),
(62,32,8,NULL),
(63,32,9,''),
(64,34,1,'Dito'),
(65,34,2,'1995-01-05'),
(66,34,3,'Utan Panjang'),
(70,37,4,'047-JKT-2026'),
(71,37,5,'Mr.Dito'),
(72,37,6,'5000.000.000.00'),
(73,37,7,''),
(74,37,8,'2026-11-20'),
(75,37,9,'Mr.Nasril');
/*!40000 ALTER TABLE `arsip_kolom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_konten`
--

DROP TABLE IF EXISTS `arsip_konten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_konten` (
  `id_arsip_konten` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_arsip_jenis` int(11) DEFAULT NULL,
  `jenis_surat` enum('M','K') NOT NULL DEFAULT 'M' COMMENT 'M=Surat Masuk, K=Surat Keluar',
  `tipe_arsip_konten` int(1) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `no_surat` varchar(50) DEFAULT NULL,
  `pengirim` varchar(150) DEFAULT NULL COMMENT 'Asal Instansi / Dari',
  `tanggal` date DEFAULT NULL,
  `tanggal_unggah` date DEFAULT NULL,
  `op` int(11) DEFAULT NULL,
  `op_bak` varchar(128) DEFAULT NULL,
  `penerima` varchar(100) DEFAULT NULL,
  `perihal` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `masa_aktif` int(11) DEFAULT 1 COMMENT 'Masa aktif arsip (tahun)',
  `tgl_inaktif` date DEFAULT NULL,
  `tgl_retensi` date DEFAULT NULL,
  `status_arsip` enum('Aktif','Inaktif','Retensi','Musnah','Permanen') NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id_arsip_konten`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_konten`
--

LOCK TABLES `arsip_konten` WRITE;
/*!40000 ALTER TABLE `arsip_konten` DISABLE KEYS */;
INSERT INTO `arsip_konten` VALUES
(32,5,'M',0,'Surat Penagihan Pembayaran','4546156165','ikhsan','2026-11-20','2026-11-20',9,NULL,'jidan','Surat Penagihan Pembayaran','',1,'2027-11-20','2029-11-20','Aktif'),
(33,6,'M',1,'Surat Pembelian AC Daikin','78489449','PT Sejuk Abadi','2026-11-20','2026-11-20',0,NULL,'Polija','Surat Pembelian AC Daikin','Kepada blablablabla',1,'2027-11-20','2029-11-20','Aktif'),
(34,2,'M',1,'Arsip Pegawai Jidan','3412-MCS-2026','PT Solusi ','2026-11-20','2026-11-20',0,NULL,'Jaka','Arsip Pegawai Jidan','blabla',1,'2027-11-20','2029-11-20','Aktif'),
(36,6,'M',1,'Pembeli PC AXIOO','B1912E-E1-2026','PT Axioo Technology','2026-11-21','2026-11-21',0,NULL,'Polija','Pembeli PC AXIOO','Mengirim Komputer Full Sett',1,'2027-11-21','2029-11-21','Aktif'),
(37,5,'K',0,'Laporan Pengiriman Satu Ton Briket Kelapa','A414-BM4B-2026','PT Andika Sejahtera','2026-11-21','2026-11-21',93,NULL,'Jaka','Laporan Pengiriman Satu Ton Briket Kelapa','Laporan Pengiriman Satu Ton Briket Kela',1,'2027-11-21','2029-11-21','Aktif'),
(38,4,'K',0,'Laporan Pembelian Server','0BD55-2026','PT Server Indo SYB','2026-11-22','2026-11-22',93,NULL,'jidan','Laporan Pembelian Server','',1,'2027-11-22','2029-11-22','Aktif');
/*!40000 ALTER TABLE `arsip_konten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_konten_role`
--

DROP TABLE IF EXISTS `arsip_konten_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_konten_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) DEFAULT NULL,
  `id_bidang` int(11) DEFAULT NULL,
  `id_operator` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_konten_role`
--

LOCK TABLES `arsip_konten_role` WRITE;
/*!40000 ALTER TABLE `arsip_konten_role` DISABLE KEYS */;
INSERT INTO `arsip_konten_role` VALUES
(1,15,3,9),
(2,16,3,9),
(3,18,3,9),
(4,23,3,9),
(5,26,3,9),
(6,27,3,9),
(7,28,3,9),
(8,29,3,9),
(9,30,3,9),
(10,32,3,9),
(11,37,5,93),
(12,38,5,93);
/*!40000 ALTER TABLE `arsip_konten_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_konten_set`
--

DROP TABLE IF EXISTS `arsip_konten_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_konten_set` (
  `id_arsip_set` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) DEFAULT NULL,
  `id_arsip` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_arsip_set`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_konten_set`
--

LOCK TABLES `arsip_konten_set` WRITE;
/*!40000 ALTER TABLE `arsip_konten_set` DISABLE KEYS */;
INSERT INTO `arsip_konten_set` VALUES
(1,1,1),
(2,2,2),
(5,2,5),
(6,3,6),
(7,3,7),
(8,4,8),
(9,5,9),
(11,5,11),
(12,5,12),
(13,5,13),
(14,5,14),
(15,9,15),
(16,9,16),
(17,10,17),
(18,10,18),
(19,11,19),
(20,12,20),
(21,12,21),
(23,14,23),
(24,15,24),
(25,16,25),
(26,17,26),
(27,18,27),
(28,19,28),
(29,20,29),
(30,21,30),
(31,22,31),
(32,23,32),
(33,24,33),
(34,25,34),
(35,27,35),
(37,28,37),
(38,29,38),
(39,32,39),
(40,33,40),
(41,34,41),
(43,36,43),
(44,37,44);
/*!40000 ALTER TABLE `arsip_konten_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_peminjaman`
--

DROP TABLE IF EXISTS `arsip_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) unsigned NOT NULL,
  `id_pegawai_peminjam` int(11) DEFAULT NULL,
  `nama_peminjam` varchar(150) NOT NULL,
  `unit_peminjam` varchar(150) DEFAULT NULL,
  `keperluan` text NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `lama_pinjam` int(11) NOT NULL DEFAULT 7,
  `tgl_kembali` date NOT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `status_pinjam` enum('Menunggu','Dipinjam','Ditolak','Dikembalikan','Telat') NOT NULL DEFAULT 'Menunggu',
  `diverifikasi_oleh` int(11) DEFAULT NULL,
  `tgl_verifikasi` datetime DEFAULT NULL,
  `catatan_verifikasi` text DEFAULT NULL,
  `divalidasi_superadmin` tinyint(1) NOT NULL DEFAULT 0,
  `tgl_validasi_superadmin` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_peminjaman`),
  KEY `idx_pinjam_konten` (`id_arsip_konten`),
  KEY `idx_pinjam_status` (`status_pinjam`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_peminjaman`
--

LOCK TABLES `arsip_peminjaman` WRITE;
/*!40000 ALTER TABLE `arsip_peminjaman` DISABLE KEYS */;
INSERT INTO `arsip_peminjaman` VALUES
(1,32,9,'Reza','Super Admin','Untuk Audit','2026-11-21',5,'2026-11-26',NULL,'Menunggu',NULL,NULL,NULL,0,NULL,'2026-11-20 21:26:29'),
(2,34,93,'Staf Operator','Umum Kampus','Keperluan rapat koordinasi (Demo Role Operator)','2026-11-21',3,'2026-11-24',NULL,'Dipinjam',9,'2026-11-21 01:09:40','Di terima',1,'2026-11-21 01:09:40','2026-11-20 23:51:41'),
(3,36,93,'Staf Operator','Umum Kampus','Peninjauan','2026-11-22',15,'2026-12-07',NULL,'Dipinjam',91,'2026-11-22 03:52:45','Disetujui Admin Arsip (Demo Role ADMIN)',0,NULL,'2026-11-21 21:17:59'),
(4,38,93,'Staf Manager','Umum Kampus','Untuk Mengajukan Penambahan Jumlah Server','2026-11-22',7,'2026-11-29',NULL,'Menunggu',NULL,NULL,NULL,0,NULL,'2026-11-22 03:03:39');
/*!40000 ALTER TABLE `arsip_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_retensi_log`
--

DROP TABLE IF EXISTS `arsip_retensi_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_retensi_log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` int(11) unsigned NOT NULL,
  `status_lama` varchar(30) DEFAULT NULL,
  `status_baru` varchar(30) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `tgl_perubahan` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `idx_ret_konten` (`id_arsip_konten`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_retensi_log`
--

LOCK TABLES `arsip_retensi_log` WRITE;
/*!40000 ALTER TABLE `arsip_retensi_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `arsip_retensi_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=MyISAM AUTO_INCREMENT=3137 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `captcha`
--

LOCK TABLES `captcha` WRITE;
/*!40000 ALTER TABLE `captcha` DISABLE KEYS */;
INSERT INTO `captcha` VALUES
(3136,1795520665,'::1','94670'),
(3135,1795516499,'::1','43488'),
(3134,1795516489,'::1','24320'),
(3133,1795516400,'::1','28800'),
(3130,1795515728,'::1','10261'),
(3131,1795516043,'::1','50864'),
(3132,1795516142,'::1','48324');
/*!40000 ALTER TABLE `captcha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_db`
--

DROP TABLE IF EXISTS `log_db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_db` (
  `id_log_db` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `moddate` datetime NOT NULL,
  `detail` text NOT NULL,
  PRIMARY KEY (`id_log_db`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_db`
--

LOCK TABLES `log_db` WRITE;
/*!40000 ALTER TABLE `log_db` DISABLE KEYS */;
INSERT INTO `log_db` VALUES
(1,1,'2016-08-10 19:45:53','Kolom <strong>id_par</strong> pada <strong>ref_arsip_jenis</strong> ditambahkan'),
(2,1,'2016-08-10 19:45:53','Kolom <strong>urut</strong> pada <strong>ref_arsip_jenis</strong> ditambahkan'),
(3,2,'2016-08-10 19:45:53','Modul <b>Statistik Arsip</b> ditambahkan'),
(4,2,'2016-08-13 16:21:06','Modul <b>Super Admin</b> ditambahkan'),
(5,2,'2016-08-13 16:21:06','Modul <b>Tambah Konten</b> ditambahkan'),
(6,2,'2016-08-13 16:21:06','Modul <b>Edit Konten</b> ditambahkan'),
(7,2,'2016-08-13 16:21:07','Modul <b>Hapus Konten</b> ditambahkan'),
(8,2,'2016-08-13 16:21:07','Modul <b>Hapus Arsip</b> ditambahkan'),
(9,2,'2016-08-13 16:21:07','Modul <b>Upload Arsip</b> ditambahkan'),
(10,2,'2016-08-13 18:56:53','Modul <b>Laporan Statistik Arsip</b> ditambahkan'),
(11,2,'2016-08-13 18:56:53','Modul <b>Laporan Arsip</b> ditambahkan'),
(12,1,'2017-04-11 03:46:45','Kolom <strong>penerima</strong> pada <strong>arsip_konten</strong> ditambahkan'),
(13,1,'2017-04-11 03:46:45','Kolom <strong>akses_manual</strong> pada <strong>ref_arsip_jenis</strong> ditambahkan'),
(14,1,'2017-04-11 03:46:45','Kolom <strong>id_operator</strong> pada <strong>ref_arsip_jenis</strong> ditambahkan'),
(15,1,'2017-04-11 03:46:45','Tabel <strong>ref_arsip_jenis_role</strong> ditambahkan'),
(16,1,'2017-04-11 03:46:46','Tabel <strong>arsip_konten_role</strong> ditambahkan'),
(17,2,'2017-04-11 03:46:46','Modul <b>Unit Kerja(Pusat/Cabang)</b> ditambahkan'),
(18,2,'2017-04-11 03:46:46','Modul <b>Bidang</b> ditambahkan'),
(19,2,'2018-08-03 11:49:24','Modul <b>Khusus</b> ditambahkan'),
(20,2,'2018-08-03 11:49:24','Modul <b>Aplikasi</b> ditambahkan'),
(21,2,'2018-08-03 11:49:24','Modul <b>Kewenangan</b> ditambahkan'),
(22,2,'2018-08-03 11:49:24','Modul <b>Operator</b> ditambahkan'),
(23,2,'2018-08-03 11:49:25','Modul <b>Umum</b> ditambahkan'),
(24,2,'2018-08-03 11:49:25','Modul <b>Personal</b> ditambahkan'),
(25,2,'2018-08-03 11:49:25','Modul <b>Jenis Kelamin</b> ditambahkan'),
(26,2,'2018-08-03 11:49:25','Modul <b>Golongan Darah</b> ditambahkan'),
(27,2,'2018-08-03 11:49:25','Modul <b>Status Keluarga</b> ditambahkan'),
(28,2,'2018-08-03 11:49:25','Modul <b>Status Anak</b> ditambahkan'),
(29,2,'2018-08-03 11:49:25','Modul <b>Pekerjaan</b> ditambahkan'),
(30,2,'2018-08-03 11:49:25','Modul <b>Keluarga</b> ditambahkan'),
(31,2,'2018-08-03 11:49:26','Modul <b>Status Kawin</b> ditambahkan'),
(32,2,'2018-08-03 11:49:26','Modul <b>Agama</b> ditambahkan'),
(33,2,'2018-08-03 11:49:26','Modul <b>Administratif</b> ditambahkan'),
(34,2,'2018-08-03 11:49:26','Modul <b>Penetap</b> ditambahkan'),
(35,2,'2018-08-03 11:49:26','Modul <b>Kementerian</b> ditambahkan'),
(36,2,'2018-08-03 11:49:26','Modul <b>Jenjang Pendidikan</b> ditambahkan'),
(37,2,'2018-08-03 11:49:26','Modul <b>Bentuk Pendidikan</b> ditambahkan'),
(38,2,'2018-08-03 11:49:26','Modul <b>Lembaga</b> ditambahkan'),
(39,2,'2018-08-03 11:49:27','Modul <b>Fakultas</b> ditambahkan'),
(40,2,'2018-08-03 11:49:27','Modul <b>Program Studi</b> ditambahkan'),
(41,2,'2018-08-03 11:49:27','Modul <b>Jurusan</b> ditambahkan'),
(42,2,'2018-08-03 11:49:27','Modul <b>Ujian Kompetensi</b> ditambahkan'),
(43,2,'2018-08-03 11:49:27','Modul <b>Propinsi</b> ditambahkan'),
(44,2,'2018-08-03 11:49:27','Modul <b>Kabupaten</b> ditambahkan'),
(45,2,'2018-08-03 11:49:27','Modul <b>Kecamatan</b> ditambahkan'),
(46,2,'2018-08-03 11:49:27','Modul <b>Kelurahan/Desa</b> ditambahkan');
/*!40000 ALTER TABLE `log_db` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_users`
--

DROP TABLE IF EXISTS `log_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'timestamp',
  `id_operator` int(11) DEFAULT NULL COMMENT 'operator',
  `id_modul` int(11) DEFAULT NULL COMMENT 'tabel',
  `act` int(11) DEFAULT NULL COMMENT 'aktifitas',
  `field` text DEFAULT NULL COMMENT 'isi fied',
  `field_alias` text DEFAULT NULL,
  `data` text DEFAULT NULL COMMENT 'isi data',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_users`
--

LOCK TABLES `log_users` WRITE;
/*!40000 ALTER TABLE `log_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nav`
--

DROP TABLE IF EXISTS `nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nav` (
  `id_nav` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_par_nav` int(11) DEFAULT NULL,
  `id_aplikasi` int(1) DEFAULT NULL,
  `ref` int(1) DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  `tipe` int(1) DEFAULT NULL,
  `judul` varchar(128) DEFAULT NULL,
  `link` varchar(128) DEFAULT NULL,
  `fa` varchar(50) DEFAULT NULL,
  `urut` int(3) DEFAULT NULL,
  `separator` int(1) DEFAULT NULL,
  `aktif` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_nav`)
) ENGINE=InnoDB AUTO_INCREMENT=1863 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav`
--

LOCK TABLES `nav` WRITE;
/*!40000 ALTER TABLE `nav` DISABLE KEYS */;
INSERT INTO `nav` VALUES
(218,NULL,16,1,'',2,'Transaksi','','',203,2,1),
(219,218,16,1,'',2,'Barang Masuk','inventori/barang_masuk','',204,2,1),
(220,218,16,1,'',2,'Permintaan Barang','inventori/permintaan','',205,2,1),
(221,218,16,1,'',2,'Barang Rusak','inventori/barang_rusak','',206,2,1),
(440,218,16,1,'',2,'Transaksi','','',NULL,0,1),
(1784,NULL,103,1,NULL,2,'Pengaturan',NULL,'cog',1,0,1),
(1785,1784,103,1,NULL,2,'Kewenangan','filing/kewenangan','shield',2,0,1),
(1786,1784,103,1,NULL,2,'Operator','filing/operator','user',3,0,1),
(1787,NULL,103,1,NULL,2,'Referensi',NULL,'cube',2,0,1),
(1788,1787,103,1,NULL,2,'Jenis Arsip','filing/jenis_arsip','cog',5,0,1),
(1789,1787,103,1,NULL,2,'Ekstensi Unggah','filing/referensi/ke/ekstensi','file-text-o',6,0,1),
(1790,1787,103,1,NULL,2,'Unit Kerja','filing/unit','university',7,0,1),
(1791,1787,103,1,NULL,2,'Unit Organisasi','filing/bidang','university',8,0,1),
(1792,1787,103,1,NULL,2,'Akses Arsip','filing/akses','lock',9,0,0),
(1793,NULL,103,1,NULL,2,'Pengarsipan','filing/pengarsipan','database',3,0,1),
(1794,NULL,103,1,NULL,2,'Laporan',NULL,'file-text-o',7,0,1),
(1795,1794,103,1,NULL,2,'Statistik Arsip','filing/statistik','file-text',12,0,1),
(1798,NULL,103,1,'OPRTT',1,'Pengguna Non Ubah Akun',NULL,NULL,5,0,1),
(1800,1786,103,1,'OPRSA',1,'Super Admin',NULL,NULL,14,0,1),
(1801,1793,103,1,'ARSAD',1,'Tambah Konten',NULL,NULL,15,0,1),
(1802,1793,103,1,'ARSED',1,'Edit Konten',NULL,NULL,16,0,1),
(1803,1793,103,1,'ARSDE',1,'Hapus Konten',NULL,NULL,17,0,1),
(1804,1793,103,1,'FILDE',1,'Hapus Arsip',NULL,NULL,18,0,1),
(1805,1793,103,1,'FILUP',1,'Upload Arsip',NULL,NULL,19,0,1),
(1806,1794,103,1,NULL,2,'Laporan Statistik Arsip','filing/laporan/statistik','file-text',20,0,1),
(1807,1794,103,1,'',2,'Laporan Arsip','filing/laporan','file-text',21,0,1),
(1808,1787,103,1,NULL,2,'Unit Kerja(Pusat/Cabang)','filing/unit','university',22,0,0),
(1809,1787,103,1,NULL,2,'Bidang','filing/bidang','university',23,0,0),
(1810,NULL,104,2,NULL,2,'Khusus',NULL,'cog',1,NULL,1),
(1811,1810,104,2,NULL,2,'Aplikasi','referensi/aplikasi','shield',2,NULL,1),
(1812,1810,104,2,NULL,2,'Kewenangan','referensi/kewenangan',NULL,3,NULL,1),
(1813,1810,104,2,NULL,2,'Operator','referensi/operator',NULL,4,NULL,1),
(1814,NULL,104,2,NULL,2,'Umum',NULL,'cube',5,NULL,1),
(1815,1814,104,2,NULL,2,'Personal',NULL,'user',6,NULL,1),
(1816,1815,104,2,NULL,2,'Jenis Kelamin','referensi/umum/ke/jenis_kelamin',NULL,7,NULL,1),
(1817,1815,104,2,NULL,2,'Golongan Darah','referensi/umum/ke/golongan_darah',NULL,8,NULL,1),
(1818,1815,104,2,NULL,2,'Status Keluarga','referensi/umum/ke/keluarga_status',NULL,9,NULL,1),
(1819,1815,104,2,NULL,2,'Status Anak','referensi/umum/ke/status_anak',NULL,10,NULL,1),
(1820,1815,104,2,NULL,2,'Pekerjaan','referensi/umum/ke/pekerjaan',NULL,11,NULL,1),
(1821,1815,104,2,NULL,2,'Keluarga','referensi/umum/ke/keluarga',NULL,12,NULL,1),
(1822,1815,104,2,NULL,2,'Status Kawin','referensi/umum/ke/kawin_status',NULL,13,NULL,1),
(1823,1815,104,2,NULL,2,'Agama','referensi/umum/ke/agama',NULL,14,NULL,1),
(1824,1814,104,2,NULL,2,'Administratif',NULL,'file-text-o',15,NULL,1),
(1825,1824,104,2,NULL,2,'Penetap','referensi/umum/ke/penetap',NULL,16,NULL,1),
(1826,1824,104,2,NULL,2,'Kementerian','referensi/umum/ke/kementerian',NULL,17,NULL,1),
(1827,1824,104,2,NULL,2,'Jenjang Pendidikan','referensi/umum/ke/studi_jenjang',NULL,18,NULL,1),
(1828,1824,104,2,NULL,2,'Bentuk Pendidikan','referensi/umum/ke/studi_bentuk',NULL,19,NULL,1),
(1829,1824,104,2,NULL,2,'Lembaga','referensi/umum/ke/studi_lembaga',NULL,20,NULL,1),
(1830,1824,104,2,NULL,2,'Fakultas','referensi/umum/ke/fakultas',NULL,21,NULL,1),
(1831,1824,104,2,NULL,2,'Program Studi','referensi/umum/ke/prodi',NULL,22,NULL,1),
(1832,1824,104,2,NULL,2,'Jurusan','referensi/umum/ke/jurusan',NULL,23,NULL,1),
(1833,1824,104,2,NULL,2,'Ujian Kompetensi','referensi/umum/ke/kompetensi',NULL,24,NULL,1),
(1834,1824,104,2,NULL,2,'Propinsi','referensi/umum/ke/propinsi',NULL,25,NULL,1),
(1835,1824,104,2,NULL,2,'Kabupaten','referensi/umum/ke/kabupaten',NULL,26,NULL,1),
(1836,1824,104,2,NULL,2,'Kecamatan','referensi/umum/ke/kecamatan',NULL,27,NULL,1),
(1837,1824,104,2,NULL,2,'Kelurahan/Desa','referensi/umum/ke/kelurahan',NULL,28,NULL,1),
(1850,NULL,103,1,NULL,2,'Disposisi','filing/disposisi','share-square-o',4,0,1),
(1851,1850,103,1,'DSPAD',1,'Tambah Disposisi',NULL,NULL,1,0,1),
(1852,1850,103,1,'DSPTL',1,'Tindak Lanjut Disposisi',NULL,NULL,2,0,1),
(1853,1850,103,1,'DSPDE',1,'Hapus Disposisi',NULL,NULL,3,0,1),
(1854,NULL,103,1,NULL,2,'Peminjaman Arsip','filing/peminjaman','book',5,0,1),
(1855,1854,103,1,'PJMAD',1,'Ajukan Peminjaman',NULL,NULL,1,0,1),
(1856,1854,103,1,'PJMVR',1,'Verifikasi Peminjaman',NULL,NULL,2,0,1),
(1857,1854,103,1,'PJMDE',1,'Hapus Peminjaman',NULL,NULL,3,0,1),
(1858,NULL,103,1,NULL,2,'Retensi Arsip','filing/retensi','archive',6,0,1),
(1859,1858,103,1,'RETED',1,'Kelola Retensi',NULL,NULL,1,0,1),
(1860,1794,103,1,NULL,2,'Buku Agenda (M/K)','filing/laporan/agenda','book',11,0,1),
(1861,1784,103,1,NULL,2,'Log Aktivitas (Audit)','filing/audit_log','history',5,0,1),
(1862,1784,103,1,NULL,2,'Persetujuan Akun','filing/persetujuan_akun','user-plus',4,0,1);
/*!40000 ALTER TABLE `nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pak_pegawai`
--

DROP TABLE IF EXISTS `pak_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pak_pegawai` (
  `id_pak_peg` int(11) NOT NULL AUTO_INCREMENT,
  `id_pak_periode` int(11) NOT NULL,
  `id_peg_jabatan` int(11) NOT NULL,
  `nilai` float DEFAULT NULL,
  `tanggal_usul` date DEFAULT NULL,
  `tanggal_verifikasi` date DEFAULT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id_pak_peg`),
  KEY `id_peg_jabatan` (`id_peg_jabatan`),
  KEY `id_pak_periode` (`id_pak_periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pak_pegawai`
--

LOCK TABLES `pak_pegawai` WRITE;
/*!40000 ALTER TABLE `pak_pegawai` DISABLE KEYS */;
/*!40000 ALTER TABLE `pak_pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parameter`
--

DROP TABLE IF EXISTS `parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parameter` (
  `param` varchar(32) NOT NULL,
  `val` text NOT NULL,
  PRIMARY KEY (`param`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter`
--

LOCK TABLES `parameter` WRITE;
/*!40000 ALTER TABLE `parameter` DISABLE KEYS */;
INSERT INTO `parameter` VALUES
('alamat',''),
('anak',''),
('aplikasi','Elektronik Filing'),
('aplikasi_code','e-Filing'),
('aplikasi_logo','logo.png'),
('aplikasi_logo_only',''),
('aplikasi_s','e-Filing'),
('app_active','103,104'),
('batas_duk',''),
('beasiswa',''),
('bpjs','0'),
('copyright','2018'),
('default_pass',''),
('default_role_view',''),
('demo','0'),
('diklatpim',''),
('dms_golru','Grade / Kategori'),
('dms_golru_yt','1'),
('dms_jab','Jabatan'),
('dms_jab_yt','1'),
('dms_namanip','Nama / Nomor'),
('dms_namanip_yt','1'),
('dms_nip','Nomor ID'),
('foto_latar_login',''),
('ibukota','Jakarta'),
('informal',''),
('inpassing',''),
('instansi','POLITEKNIK LP3I JAKARTA'),
('instansi_code','Politeknik <br>LP3I <br>Jakarta'),
('instansi_s','JKT'),
('jadin_sppd','SPPD-090/[nomor]/[kode_bidang]/[bulan_romawi]/[tahun_angka]'),
('jadin_sppd_bts','/'),
('jadin_sppd_uri','2'),
('jadin_spt','090/[nomor]/[kode_bidang]/[bulan_romawi]/[tahun_angka]'),
('jadin_spt_bts','/'),
('jadin_spt_uri','2'),
('karis','0'),
('karpeg','0'),
('karyatulis',''),
('kesejahteraan',''),
('login_captcha','0'),
('main_color','#0d055f'),
('multi_unit','0'),
('organisasi',''),
('pemerintah','Kampus Pengembangan'),
('pemerintah_logo','LOGO_PLJ.png'),
('pemerintah_logo_bw','logo.png'),
('pemerintah_s','PLJ'),
('pendidikan',''),
('penetap_duk',''),
('penyesuaian_ijazah',''),
('perkawinan',''),
('sertifikasi',''),
('skp','a:9:{s:13:\"tanggal_batas\";s:10:\"2015-12-01\";s:12:\"tanggal_usul\";s:1:\"1\";s:11:\"tanggal_cek\";s:1:\"1\";s:19:\"tanggal_cek_banding\";s:1:\"1\";s:17:\"tanggal_perubahan\";s:1:\"1\";s:18:\"tanggal_verifikasi\";s:1:\"1\";s:13:\"batas_banding\";s:2:\"14\";s:19:\"batas_banding_nilai\";s:2:\"14\";s:13:\"skp_perubahan\";s:1:\"2\";}'),
('sk_belajar',''),
('sk_cpns',''),
('sk_cuti',''),
('sk_hukdis',''),
('sk_jabatan','8'),
('sk_kenpa','6'),
('sk_kgb',''),
('sk_pak',''),
('sk_penghargaan',''),
('taspen','0'),
('tes',''),
('tpp','a:2:{s:5:\"modul\";s:3:\"TPP\";s:5:\"rumus\";s:1:\"2\";}'),
('tugas',''),
('tunjangan',''),
('tvinfo_durasi','0,03');
/*!40000 ALTER TABLE `parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peg_jabatan`
--

DROP TABLE IF EXISTS `peg_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `peg_jabatan` (
  `id_peg_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `id_bidang` int(11) NOT NULL,
  `id_atasan` int(11) DEFAULT NULL,
  `id_atasan_atasan` int(11) DEFAULT NULL,
  `id_penetap` int(1) DEFAULT NULL,
  `id_status_pegawai` int(1) NOT NULL,
  `id_box` int(11) DEFAULT NULL,
  `puncak` int(1) DEFAULT 0,
  `no_sk` varchar(50) NOT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `tmt_jabatan` date NOT NULL,
  `selesai_jabatan` date NOT NULL,
  `tanggal_cetak` datetime DEFAULT NULL,
  `perpanjangan` int(3) DEFAULT NULL,
  `jabatan_manual` varchar(200) DEFAULT NULL,
  `eselon_manual` int(1) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `id_golru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_peg_jabatan`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `id_jabatan` (`id_jabatan`),
  KEY `id_atasan` (`id_atasan`),
  KEY `id_bidang` (`id_bidang`),
  KEY `id_box` (`id_box`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peg_jabatan`
--

LOCK TABLES `peg_jabatan` WRITE;
/*!40000 ALTER TABLE `peg_jabatan` DISABLE KEYS */;
INSERT INTO `peg_jabatan` VALUES
(25,9,27,0,3,NULL,NULL,NULL,0,NULL,0,'',NULL,'0000-00-00','0000-00-00',NULL,NULL,NULL,NULL,1,NULL),
(37,91,27,0,2,NULL,NULL,NULL,0,NULL,0,'',NULL,'2026-11-21','2026-11-21',NULL,NULL,NULL,NULL,1,NULL),
(38,92,32,0,4,NULL,NULL,NULL,0,NULL,0,'',NULL,'2026-11-21','2026-11-21',NULL,NULL,NULL,NULL,1,NULL),
(39,93,32,0,5,NULL,NULL,NULL,0,NULL,0,'',NULL,'2026-11-21','2026-11-21',NULL,NULL,NULL,NULL,1,NULL),
(42,96,27,0,1,NULL,NULL,NULL,0,NULL,0,'',NULL,'2026-11-23','2026-11-23',NULL,NULL,NULL,NULL,1,NULL),
(43,97,27,0,1,NULL,NULL,NULL,0,NULL,0,'',NULL,'2026-11-23','2026-11-23',NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `peg_jabatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peg_pegawai`
--

DROP TABLE IF EXISTS `peg_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `peg_pegawai` (
  `id_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `id_agama` int(1) NOT NULL,
  `id_jeniskelamin` int(1) NOT NULL,
  `id_gol_darah` int(1) NOT NULL,
  `id_tempat_lahir` int(11) NOT NULL,
  `id_statuskawin` int(11) DEFAULT NULL,
  `id_suku` int(11) DEFAULT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `id_tipe_pegawai` int(1) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nip` char(18) NOT NULL,
  `nip_lama` varchar(18) DEFAULT NULL,
  `no_nik` varchar(30) DEFAULT NULL,
  `no_npwp` varchar(30) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `gelar_belakang` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `hobi` varchar(100) DEFAULT NULL,
  `tinggi` int(1) DEFAULT NULL,
  `berat` int(1) DEFAULT NULL,
  `rambut` varchar(100) DEFAULT NULL,
  `bentuk_muka` varchar(100) DEFAULT NULL,
  `warna_kulit` varchar(100) DEFAULT NULL,
  `ciri_khas` varchar(100) DEFAULT NULL,
  `cacat` varchar(100) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `kodepos` varchar(10) DEFAULT NULL,
  `telepon` varchar(12) DEFAULT NULL,
  `email` varchar(80) NOT NULL,
  `pin` varchar(20) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `cpns_tmt` date DEFAULT NULL,
  `cpns_no` varchar(100) DEFAULT NULL,
  `cpns_tanggal` date DEFAULT NULL,
  `cpns_berkas` varchar(50) DEFAULT NULL,
  `mkg_tahun` int(1) DEFAULT NULL,
  `mkg_bulan` int(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `mn_jabatan` varchar(255) DEFAULT NULL COMMENT 'Manual Jabatan',
  `mn_golru` varchar(255) DEFAULT NULL COMMENT 'Manual Golongan',
  `status` int(1) DEFAULT 1 COMMENT '1: first_login ; 0: sudah pernah diganti',
  `reg_role_usulan` int(11) DEFAULT NULL,
  `reg_created_at` datetime DEFAULT NULL,
  `reg_approved_by` int(11) DEFAULT NULL,
  `reg_approved_at` datetime DEFAULT NULL,
  `reg_catatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `nip` (`nip`),
  KEY `id_jeniskelamin` (`id_jeniskelamin`),
  KEY `id_agama` (`id_agama`),
  KEY `id_gol_darah` (`id_gol_darah`),
  KEY `id_kelurahan` (`id_kelurahan`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peg_pegawai`
--

LOCK TABLES `peg_pegawai` WRITE;
/*!40000 ALTER TABLE `peg_pegawai` DISABLE KEYS */;
INSERT INTO `peg_pegawai` VALUES
(9,0,0,0,0,NULL,NULL,0,NULL,'admin','d8578edf8458ce06fbc5bb76a58c5ca4','123456789',NULL,NULL,NULL,'admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'9.jpg',NULL,NULL,NULL,NULL,NULL,NULL,'2026-11-24 11:35:09',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),
(91,0,0,0,0,NULL,NULL,0,NULL,'admin_arsip','d8578edf8458ce06fbc5bb76a58c5ca4','20260001',NULL,NULL,NULL,'Admin Arsip',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'admin.arsip@lp3i.ac.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-11-22 03:50:27',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),
(92,0,0,0,0,NULL,NULL,0,NULL,'pimpinan1','a1475279de60efc1b418fa651f695384','20260002',NULL,NULL,NULL,'Pimpinan Kampus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pimpinan@lp3i.ac.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-11-21 22:51:51',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),
(93,0,0,0,0,NULL,NULL,0,NULL,'staf1','a416afc993c8961855d7e78bd5e301dc','20260003',NULL,NULL,NULL,'Staf Operator',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'staf@lp3i.ac.id',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-11-22 03:58:09',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),
(97,0,0,0,0,NULL,NULL,0,NULL,'Muhammad Ikhsan Habibie','25f9e794323b453885f5181f1b624d0b','147258369',NULL,NULL,NULL,'Muhammad Ikhsan Habibie',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'san@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `peg_pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pegawai_role`
--

DROP TABLE IF EXISTS `pegawai_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pegawai_role` (
  `id_peg_role` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `id_role` int(3) NOT NULL,
  PRIMARY KEY (`id_peg_role`),
  KEY `id_pegawai` (`id_pegawai`,`id_role`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=1034 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pegawai_role`
--

LOCK TABLES `pegawai_role` WRITE;
/*!40000 ALTER TABLE `pegawai_role` DISABLE KEYS */;
INSERT INTO `pegawai_role` VALUES
(1024,9,83),
(1027,91,61),
(1028,92,84),
(1029,93,82),
(1032,96,82),
(1033,97,82);
/*!40000 ALTER TABLE `pegawai_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_agama`
--

DROP TABLE IF EXISTS `ref_agama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_agama` (
  `id_agama` int(1) NOT NULL AUTO_INCREMENT,
  `agama` varchar(50) NOT NULL,
  PRIMARY KEY (`id_agama`),
  UNIQUE KEY `agama` (`agama`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_agama`
--

LOCK TABLES `ref_agama` WRITE;
/*!40000 ALTER TABLE `ref_agama` DISABLE KEYS */;
INSERT INTO `ref_agama` VALUES
(4,'Budha'),
(3,'Hindu'),
(1,'Islam'),
(6,'Katholik'),
(7,'Konghucu'),
(2,'Kristen');
/*!40000 ALTER TABLE `ref_agama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_aplikasi`
--

DROP TABLE IF EXISTS `ref_aplikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_aplikasi` (
  `id_aplikasi` int(1) NOT NULL AUTO_INCREMENT,
  `id_par_aplikasi` int(1) DEFAULT NULL,
  `urut` int(1) NOT NULL,
  `kode_aplikasi` varchar(15) NOT NULL,
  `nama_aplikasi` varchar(50) NOT NULL,
  `deskripsi` varchar(80) DEFAULT NULL,
  `folder` varchar(30) DEFAULT NULL,
  `warna` varchar(7) DEFAULT NULL,
  `aktif` int(11) NOT NULL,
  PRIMARY KEY (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_aplikasi`
--

LOCK TABLES `ref_aplikasi` WRITE;
/*!40000 ALTER TABLE `ref_aplikasi` DISABLE KEYS */;
INSERT INTO `ref_aplikasi` VALUES
(10,2,1,'PTK','SIMPATIK','Sistem Informasi Manajemen Pendidikan dan Tenaga Kependidikan','ptk','#FF0000',0),
(103,NULL,1,'FIL','e-Filing','Sistem Informasi Manajemen File Elektronik','filing','#C59FD4',1),
(104,NULL,2,'REF','Referensi','Sistem Informasi Manajemen Referensi','referensi','#5BBA3C',1);
/*!40000 ALTER TABLE `ref_aplikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_arsip_ekstensi`
--

DROP TABLE IF EXISTS `ref_arsip_ekstensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_arsip_ekstensi` (
  `id_ekstensi` int(3) NOT NULL AUTO_INCREMENT,
  `ekstensi` varchar(80) NOT NULL DEFAULT '',
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_ekstensi`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_arsip_ekstensi`
--

LOCK TABLES `ref_arsip_ekstensi` WRITE;
/*!40000 ALTER TABLE `ref_arsip_ekstensi` DISABLE KEYS */;
INSERT INTO `ref_arsip_ekstensi` VALUES
(9,'gif',''),
(10,'jpg',''),
(12,'png',''),
(13,'zip',''),
(14,'doc',''),
(15,'docx',''),
(16,'xls',''),
(17,'xlsx',''),
(18,'pdf',''),
(19,'jpeg',''),
(20,'mp4',''),
(21,'mp3',''),
(22,'rar',''),
(26,'mid','');
/*!40000 ALTER TABLE `ref_arsip_ekstensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_arsip_jenis`
--

DROP TABLE IF EXISTS `ref_arsip_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_arsip_jenis` (
  `id_arsip_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `id_aplikasi` int(1) NOT NULL,
  `kode_arsip_jenis` varchar(15) DEFAULT NULL,
  `arsip_jenis` varchar(50) NOT NULL,
  `direktori` varchar(50) NOT NULL,
  `id_par` int(11) DEFAULT NULL,
  `urut` int(1) DEFAULT NULL,
  `akses_manual` int(1) DEFAULT NULL,
  `id_operator` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_arsip_jenis`),
  KEY `id_aplikasi` (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_arsip_jenis`
--

LOCK TABLES `ref_arsip_jenis` WRITE;
/*!40000 ALTER TABLE `ref_arsip_jenis` DISABLE KEYS */;
INSERT INTO `ref_arsip_jenis` VALUES
(1,3,'AKEP-SPP','Surat Pengangkatan Pegawai','surat_pengangkatan',NULL,1,NULL,NULL),
(2,3,'AKEP-DRHP','Data Riwayat Hidup Pegawai','data_riwatat_hidup_pegawai',NULL,3,NULL,NULL),
(3,3,'AKEP-RP','Rekaman Presensi','rekaman_presensi',NULL,2,NULL,NULL),
(4,3,'AKEU-LK','Laporan Keuangan','laporan_keuangan',NULL,4,NULL,NULL),
(5,3,'AKEU-BP','Bukti Pembayaran','bukti_pembayaran',NULL,5,NULL,NULL),
(6,3,'AKEU-BP','Bukti Pembelian','bukti_pembelian',NULL,6,NULL,NULL);
/*!40000 ALTER TABLE `ref_arsip_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_arsip_jenis_role`
--

DROP TABLE IF EXISTS `ref_arsip_jenis_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_arsip_jenis_role` (
  `id_arsip_jenis_role` int(11) NOT NULL AUTO_INCREMENT,
  `id_bidang` int(11) DEFAULT NULL,
  `id_arsip_jenis` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_arsip_jenis_role`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_arsip_jenis_role`
--

LOCK TABLES `ref_arsip_jenis_role` WRITE;
/*!40000 ALTER TABLE `ref_arsip_jenis_role` DISABLE KEYS */;
INSERT INTO `ref_arsip_jenis_role` VALUES
(3,1,1),
(4,1,3),
(5,1,2),
(6,1,4),
(7,1,5),
(8,1,6),
(9,1,7),
(10,1,8),
(11,1,9),
(12,1,10),
(13,1,11),
(14,3,1),
(15,3,3),
(16,3,2),
(17,3,4),
(18,3,5),
(19,3,6),
(20,3,7),
(21,3,8),
(22,3,9),
(23,3,10),
(24,3,11),
(25,1,12),
(26,2,12),
(27,3,12),
(29,4,12),
(30,4,10),
(31,4,11),
(32,2,1),
(33,4,1),
(34,5,1),
(35,2,2),
(36,4,2),
(37,5,2),
(38,2,3),
(39,4,3),
(40,5,3),
(41,2,4),
(42,4,4),
(43,5,4),
(44,2,5),
(45,4,5),
(46,5,5),
(47,2,6),
(48,4,6),
(49,5,6);
/*!40000 ALTER TABLE `ref_arsip_jenis_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_arsip_kolom`
--

DROP TABLE IF EXISTS `ref_arsip_kolom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_arsip_kolom` (
  `id_arsip_kolom` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_arsip_jenis` int(11) DEFAULT NULL,
  `kolom` varchar(128) DEFAULT NULL,
  `tipe` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_arsip_kolom`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_arsip_kolom`
--

LOCK TABLES `ref_arsip_kolom` WRITE;
/*!40000 ALTER TABLE `ref_arsip_kolom` DISABLE KEYS */;
INSERT INTO `ref_arsip_kolom` VALUES
(1,2,'Nama',1),
(2,2,'Tgl Lahir',3),
(3,2,'Alamat',2),
(4,5,'No. Resi/Kwitansi',1),
(5,5,'Telah terima dari',1),
(6,5,'Biaya',1),
(7,5,'Terbilang',1),
(8,5,'Tanggal Pembayaran',3),
(9,5,'Penerima',1),
(10,1,'Tanggal Berlaku',3),
(11,10,'Wisuda ke',1),
(12,10,'Kelompok',1),
(13,12,'Jumlah',4);
/*!40000 ALTER TABLE `ref_arsip_kolom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_bidang`
--

DROP TABLE IF EXISTS `ref_bidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_bidang` (
  `id_bidang` int(11) NOT NULL AUTO_INCREMENT,
  `id_par_bidang` int(11) DEFAULT NULL,
  `id_unit` int(11) unsigned NOT NULL,
  `penetapan` date DEFAULT NULL,
  `kode_bidang` varchar(20) DEFAULT NULL,
  `nama_bidang` varchar(80) NOT NULL,
  `urut` int(1) NOT NULL,
  `level` int(1) DEFAULT NULL,
  `pos_x` int(3) DEFAULT NULL,
  `pos_y` int(3) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `aktif` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_bidang`),
  KEY `id_par_bidang` (`id_par_bidang`,`id_unit`),
  KEY `id_subunit` (`id_unit`),
  KEY `urut` (`urut`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_bidang`
--

LOCK TABLES `ref_bidang` WRITE;
/*!40000 ALTER TABLE `ref_bidang` DISABLE KEYS */;
INSERT INTO `ref_bidang` VALUES
(1,NULL,27,NULL,'01','Bagian Keuangan dan Perlengkapan',1,NULL,NULL,NULL,NULL,NULL),
(2,NULL,27,NULL,'02','Umum',2,NULL,NULL,NULL,NULL,NULL),
(3,NULL,27,NULL,'03','Super Admin',3,NULL,NULL,NULL,NULL,NULL),
(4,NULL,32,NULL,'KK','Kepala Kampus',1,NULL,NULL,NULL,NULL,NULL),
(5,NULL,32,NULL,'umu','Umum Kampus',2,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ref_bidang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_eselon`
--

DROP TABLE IF EXISTS `ref_eselon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_eselon` (
  `id_eselon` int(1) NOT NULL AUTO_INCREMENT,
  `eselon` varchar(20) NOT NULL,
  `urut` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_eselon`),
  UNIQUE KEY `eselon` (`eselon`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_eselon`
--

LOCK TABLES `ref_eselon` WRITE;
/*!40000 ALTER TABLE `ref_eselon` DISABLE KEYS */;
INSERT INTO `ref_eselon` VALUES
(1,'II.A',NULL),
(2,'II.B',NULL),
(3,'III.A',NULL),
(4,'III.B',NULL),
(5,'IV.A',NULL),
(6,'IV.B',NULL),
(7,'V.A',NULL),
(9,'Non Ess',NULL);
/*!40000 ALTER TABLE `ref_eselon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_gol_darah`
--

DROP TABLE IF EXISTS `ref_gol_darah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_gol_darah` (
  `id_gol_darah` int(1) NOT NULL AUTO_INCREMENT,
  `gol_darah` varchar(10) NOT NULL,
  PRIMARY KEY (`id_gol_darah`),
  UNIQUE KEY `gol_darah` (`gol_darah`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_gol_darah`
--

LOCK TABLES `ref_gol_darah` WRITE;
/*!40000 ALTER TABLE `ref_gol_darah` DISABLE KEYS */;
INSERT INTO `ref_gol_darah` VALUES
(1,'A'),
(3,'AB'),
(2,'B'),
(4,'O');
/*!40000 ALTER TABLE `ref_gol_darah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_golru`
--

DROP TABLE IF EXISTS `ref_golru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_golru` (
  `id_golru` int(1) NOT NULL AUTO_INCREMENT,
  `id_golru_jenis` int(1) NOT NULL,
  `golongan` varchar(20) NOT NULL,
  `pangkat` varchar(50) NOT NULL,
  `urut` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_golru`),
  KEY `id_golru_jenis` (`id_golru_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_golru`
--

LOCK TABLES `ref_golru` WRITE;
/*!40000 ALTER TABLE `ref_golru` DISABLE KEYS */;
INSERT INTO `ref_golru` VALUES
(1,1,'I/a','Juru Muda',NULL),
(2,1,'I/b','Juru Muda Tingkat I',NULL),
(3,1,'I/c','Juru',NULL),
(4,1,'I/d','Juru Tingkat I',NULL),
(5,1,'II/a','Pengatur Muda',NULL),
(6,1,'II/b','Pengatur Muda Tingkat I',NULL),
(7,1,'II/c','Pengatur',NULL),
(8,1,'II/d','Pengatur Tingkat I',NULL),
(9,1,'III/a','Penata Muda',NULL),
(10,1,'III/b','Penata Muda Tingkat I',NULL),
(11,1,'III/c','Penata',NULL),
(12,1,'III/d','Penata Tingkat I',NULL),
(13,1,'IV/a','Pembina',NULL),
(14,1,'IV/b','Pembina Tingkat I',NULL),
(15,1,'IV/c','Pembina Utama Muda',NULL),
(16,1,'IV/d','Pembina Utama Madya',NULL),
(17,1,'IV/e','Pembina Utama',NULL);
/*!40000 ALTER TABLE `ref_golru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_golru_jenis`
--

DROP TABLE IF EXISTS `ref_golru_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_golru_jenis` (
  `id_golru_jenis` int(1) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) DEFAULT NULL,
  `golru_jenis` varchar(50) NOT NULL,
  PRIMARY KEY (`id_golru_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_golru_jenis`
--

LOCK TABLES `ref_golru_jenis` WRITE;
/*!40000 ALTER TABLE `ref_golru_jenis` DISABLE KEYS */;
INSERT INTO `ref_golru_jenis` VALUES
(1,'001','Reguler'),
(2,'002','Pilihan'),
(3,'003','Istimewa'),
(4,'004','Pengabdian'),
(5,'005','Anumerta');
/*!40000 ALTER TABLE `ref_golru_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_golru_pendidikan`
--

DROP TABLE IF EXISTS `ref_golru_pendidikan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_golru_pendidikan` (
  `id_gol_pend` int(11) NOT NULL AUTO_INCREMENT,
  `id_bentuk_pendidikan` int(11) NOT NULL,
  `gol_puncak` int(1) DEFAULT NULL,
  `gol_dasar` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_gol_pend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_golru_pendidikan`
--

LOCK TABLES `ref_golru_pendidikan` WRITE;
/*!40000 ALTER TABLE `ref_golru_pendidikan` DISABLE KEYS */;
/*!40000 ALTER TABLE `ref_golru_pendidikan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_jabatan`
--

DROP TABLE IF EXISTS `ref_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_jabatan` (
  `id_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `id_jab_jenis` int(1) NOT NULL,
  `id_eselon` int(1) NOT NULL,
  `nama_jabatan` varchar(80) NOT NULL,
  `bup` int(1) NOT NULL,
  `stat_jab` int(1) NOT NULL,
  `id_bidang` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_jabatan`),
  KEY `id_jab_jenis` (`id_jab_jenis`,`id_eselon`),
  KEY `id_eselon` (`id_eselon`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_jabatan`
--

LOCK TABLES `ref_jabatan` WRITE;
/*!40000 ALTER TABLE `ref_jabatan` DISABLE KEYS */;
INSERT INTO `ref_jabatan` VALUES
(1,1,2,'KEPALA BADAN',0,1,NULL),
(2,1,4,'KEPALA BAGIAN PADA BIRO',0,1,NULL),
(3,1,4,'SEKRETARIS PADA BADAN',0,1,NULL),
(4,1,4,'KEPALA BIDANG PADA BADAN',0,1,NULL),
(5,1,6,'KEPALA SUB BAGIAN PADA BADAN',0,1,NULL),
(6,1,6,'KEPALA SUB BIDANG PADA BADAN',0,1,NULL),
(7,2,9,'Penatalaksana Sistem Informasi Kepegawaian',58,2,NULL),
(8,2,9,'Caraka',58,2,NULL),
(9,2,9,'Pemelihara Sarana Prasarana dan Kebersihan',58,2,NULL),
(10,2,9,'Pengelola Keuangan',58,2,NULL),
(11,2,9,'Penatalaksana Pemberian Tanda Kehormatan Satya Lancana Karya Satya PNS',58,2,NULL),
(12,2,9,'Pengadministrasi Umum',58,2,NULL),
(13,2,9,'Pemroses Surat Ijin Belajar, Tugas Belajar dan Ijin ke Luar Negeri',58,2,NULL),
(14,2,9,'Penyiap Fasilitasi Pengembangan Kinerja Pegawai',58,2,NULL),
(15,2,9,'Penatalaksana Kenaikan Pangkat dan Pensiun Pegawai ',58,2,NULL),
(16,2,9,'Penatalaksana Dokumentasi Kepegawaian',58,2,NULL),
(17,2,9,'Pramu Tamu dan Informasi',58,2,NULL),
(18,2,9,'',0,2,NULL),
(19,2,9,'Penatalaksana Pengadaan Pegawai',58,2,NULL),
(20,2,9,'Pengolah Data dan Informasi',58,2,NULL),
(21,2,9,'Pengelola Arsip dan Bahan Pustaka',58,2,NULL),
(22,2,9,'Penatalaksana Dokumentasi Kepegawaian Elektronik',58,2,NULL),
(23,2,9,'Penyusun Formasi Pegawai',58,2,NULL),
(24,2,9,'Pengelola Barang',58,2,NULL),
(25,2,9,'Pengadministrasi Kepegawaian',58,2,NULL),
(26,2,9,'Pemroses Tabungan Perumahan (Taperum) PNS',58,2,NULL),
(27,2,9,'Pengemudi',58,2,NULL),
(28,2,9,'Penatalaksana Pengembangan Manajemen SDM dan Karier Pegawai ',58,2,NULL),
(29,2,9,'Pengolah Data dan Informasi Kepegawaian',58,2,NULL),
(30,2,9,'Penatalaksana Pengangkatan, Pemindahan dan Pemberhentian Jabatan Fungsional Umum',58,2,NULL),
(31,2,9,'Penatalaksana Pengangkatan, Pemindahan dan Pemberhentian Jabatan Fungsional Tert',58,2,NULL),
(32,2,9,'Penatalaksana Kedudukan Hukum Pegawai',58,2,NULL),
(33,2,9,'Penatalaksana Sistem Informasi',58,2,NULL),
(34,2,9,'Penatalaksana Pengangkatan, Pemindahan dan Pemberhentian Jabatan Struktural',58,2,NULL),
(35,2,9,'',0,2,NULL),
(36,2,9,'Pengadministrasi Tata Naskah Kepegawaian',58,2,NULL),
(37,2,9,'Analis Kepegawaian Ahli Pertama',58,2,NULL),
(38,2,9,'Pengelola Laporan Harta Kekayaan Pejabat',58,2,NULL),
(39,2,9,'Penatalaksana Seleksi Diklat dan Ujian Pegawai',58,2,NULL),
(40,2,9,'Penyusun Bahan Kebijakan Kesejahteraan Pegawai ',58,2,NULL),
(41,2,9,'Penyiap Fasilitasi Pembinaan Kesegaran Jasmani Rohani dan Jaminan Kesehatan Pega',58,2,NULL),
(42,2,9,'Penganalisis Kebutuhan Diklat PNS',58,2,NULL),
(43,2,9,'Penyusun Program',58,2,NULL),
(44,2,9,'Calon Pegawai Negeri Sipil Daerah',58,2,NULL),
(45,2,9,'Pegawai Negeri Sipil',58,2,NULL),
(46,2,9,'Penyusun Laporan Hasil Moniitoring dan Evaluasi',58,2,NULL),
(47,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pengelolaan Administrasi Publik dan Keua',58,2,NULL),
(48,2,9,'Penyusun Rencana Pengembangan Kinerja Program',58,2,NULL),
(49,2,9,'Pengevaluasi Data Statistik',58,2,NULL),
(50,2,9,'Pelaksana Fasilitasi Pengembangan Kinerja Program',58,2,NULL),
(51,2,9,'Perencana Muda',58,2,NULL),
(52,2,9,'Penyusun Rencana Pembangunan Pertanian ',58,2,NULL),
(53,2,9,'Penyusun Rencana Pembangunan Administrasi Publik dan Keuangan',58,2,NULL),
(54,2,9,'',0,2,NULL),
(55,2,9,'',0,2,NULL),
(56,2,9,'Penyusun Rencana Pembangunan Perindustrian',58,2,NULL),
(57,2,9,'Penatalaksana Penyusunan Rencana RENSTRA, KUA, dan SP',58,2,NULL),
(58,2,9,'Pengolah Data Statistik',58,2,NULL),
(59,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pembangunan Aparatur, Hukum dan Politik',58,2,NULL),
(60,2,9,'',0,2,NULL),
(61,2,9,'Pengevaluasi Penyusunan Rencana RENSTRA, KUA, dan SP',58,2,NULL),
(62,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Perhubungan, Tata Ruang, Permukiman dan ',58,2,NULL),
(63,2,9,'Peneliti Madya',65,2,NULL),
(64,2,9,'',0,2,NULL),
(65,2,9,'Penyusun Laporan Kinerja Program',58,2,NULL),
(66,2,9,'',0,2,NULL),
(67,2,9,'Perencana Pertama',58,2,NULL),
(68,2,9,'',0,2,NULL),
(69,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pengelolaan Administrasi Publik dan Keua',58,2,NULL),
(70,2,9,'Perencana Madya',58,2,NULL),
(71,2,9,'Pengevaluasi dan Penyusun Laporan',58,2,NULL),
(72,2,9,'Penyusun Rencana Program Pembangunan Hukum dan Politik',58,2,NULL),
(73,2,9,'',0,2,NULL),
(74,2,9,'Penatalaksana Monitoring dan Evaluasi',58,2,NULL),
(75,2,9,'',0,2,NULL),
(76,2,9,'Penyusun Rencana Pembangunan Permukiman dan ESDM',58,2,NULL),
(77,2,9,'Penyusun Rencana Pembangunan Perhubungan',58,2,NULL),
(78,2,9,'Penyusun Rencana Pembangunan Tata Ruang',58,2,NULL),
(79,2,9,'Penata Usaha Pimpinan',58,2,NULL),
(80,2,9,'Penyusun Rencana Pengembangan Budaya dan Pariwisata',58,2,NULL),
(81,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pengembangan Kesejahteraan Masyarakat',58,2,NULL),
(82,2,9,'',0,2,NULL),
(83,2,9,'',0,2,NULL),
(84,2,9,'',0,2,NULL),
(85,1,3,'KEPALA BIRO',0,1,NULL),
(86,1,6,'KEPALA SUB BAGIAN PADA BIRO',0,1,NULL),
(87,2,9,'Penganalisis Kinerja Jabatan',58,2,NULL),
(88,2,9,'Penganalisis Sistim dan Prosedur',58,2,NULL),
(89,2,9,'Penganalisis Jabatan',58,2,NULL),
(90,2,9,'Penyiap Bahan Pembinaan dan Pengendalian Kelembagaan Kabupaten/Kota',58,2,NULL),
(91,2,9,'Penganalisis Sistim Pelayanan Umum',58,2,NULL),
(92,2,9,'Penyiap Bahan Pengembangan Kelembagaan',58,2,NULL),
(93,2,9,'Penyiap bahan Analisis Tatalaksana Pelayanan Umum',58,2,NULL),
(94,2,9,'Penyiap Bahan Penyusunan Sistim dan Prosedur',58,2,NULL),
(95,2,9,'Penganalisis Standarisasi ',58,2,NULL),
(96,2,9,'Penyusun Program dan Laporan',58,2,NULL),
(97,2,9,'Penyiap Bahan Analisis Jabatan',58,2,NULL),
(98,2,9,'Penganalisis Sistim AKIP',58,2,NULL),
(99,2,9,'Penganalisis Kelembagaan',58,2,NULL),
(100,1,2,'ASISTEN SEKRETARIS DAERAH',0,1,NULL),
(101,1,2,'STAF AHLI GUBERNUR',0,1,NULL),
(102,1,1,'SEKRETARIS DAERAH',0,2,61),
(103,1,6,'KEPALA SUB BAGIAN PADA SEKRETARIAT DPRD',0,1,NULL),
(104,1,4,'KEPALA BAGIAN PADA SEKRETARIAT DPRD',0,1,NULL),
(105,1,4,'KEPALA KANTOR PERWAKILAN DAERAH',0,1,NULL),
(106,1,2,'KEPALA DINAS',0,1,NULL),
(107,1,3,'DIREKTUR RUMAH SAKIT GRHASIA',0,1,NULL),
(108,1,4,'KEPALA BIDANG PADA DINAS',0,1,NULL),
(109,1,4,'SEKRETARIS PADA DINAS',0,1,NULL),
(110,1,4,'KEPALA BALAI PADA DINAS',0,1,NULL),
(111,1,5,'SEKRETARIS PADA RUMAH SAKIT GRHASIA',0,1,NULL),
(112,1,5,'KEPALA BIDANG PADA RUMAH SAKIT GRHASIA',0,1,NULL),
(113,1,6,'KEPALA SUB BAGIAN PADA DINAS',0,1,NULL),
(114,1,6,'KEPALA SEKSI PADA DINAS',0,1,NULL),
(115,1,6,'KEPALA SUB BAGIAN TATA USAHA PADA BALAI',0,1,NULL),
(116,1,6,'KEPALA SEKSI PADA BALAI',0,1,NULL),
(117,1,6,'KEPALA SUB BAGIAN TATA USAHA PADA KANTOR',0,1,NULL),
(118,1,6,'KEPALA SEKSI PADA KANTOR',0,1,NULL),
(119,1,6,'KEPALA SUB BAGIAN TU PADA MUSEUM',0,1,NULL),
(120,1,6,'KEPALA SUB BAGIAN PADA RUMAH SAKIT GRHASIA',0,1,NULL),
(121,1,6,'KEPALA SUB BIDANG PADA RUMAH SAKIT GRHASIA',0,1,NULL),
(122,2,9,'Penatalaksana Fasilitasi Perijinan Pemanfaatan Tanah',58,2,NULL),
(123,1,0,'STAF',0,1,NULL),
(124,2,9,'Penyiap Bahan Kebijakan Catatan Sipil',58,2,NULL),
(125,2,9,'Penatalaksana Pengendalian Pertanahan',58,2,NULL),
(126,2,9,'Pengkaji Fungsi Pertanahan',58,2,NULL),
(127,2,9,'Penganalisis dan Pengevaluasi Kebijakan Pengembangan Wilayah ',58,2,NULL),
(128,2,9,'Pengkaji Produk Hukum',58,2,NULL),
(129,2,9,'Penyusun Rumusan Kajian / Telaahan Produk Hukum Kabupaten/Kota',58,2,NULL),
(130,2,9,'Penganalisis Penyelesaian Sengketa Hukum',58,2,NULL),
(131,2,9,'Pengadministrasi Pengesahan Produk Hukum',58,2,NULL),
(132,2,9,'Penyiap Kebijakan Fasilitasi Kehidupan Beragama',58,2,NULL),
(133,2,9,'Penganalisis dan Pengevaluasi Kebijakan KB dan Keluarga Sejahtera',58,2,NULL),
(134,2,9,'Penyiap Kebijakan Sosial dan Tenaga Kerja',58,2,NULL),
(135,2,9,'Penganalisis dan Pengevaluasi Kebijakan Sosial dan Tenaga Kerja',58,2,NULL),
(136,2,9,'Penyiap Bahan Kebijakan Pekerjaan Umum dan ESDM',58,2,NULL),
(137,2,9,'Petugas Layanan Ijin Penelitian',58,2,NULL),
(138,2,9,'Penyiap Bahan Kebijakan Dekonsentrasi',58,2,NULL),
(139,2,9,'Pengarah Naskah Dinas',58,2,NULL),
(140,2,9,'Ajudan Gubernur dan Wakil Gubernur',58,2,NULL),
(141,2,9,'Pembawa Acara',58,2,NULL),
(142,2,9,'Penyiap Bahan Penerbitan Siaran Pemda',58,2,NULL),
(143,2,9,'Pemantau Penerbitan ',58,2,NULL),
(144,2,9,'Penyusun Konsep Pidato Gubernur /Wakil Gubernur dan Sekretaris Daerah',58,2,NULL),
(145,2,9,'Pendokumentasi Kebijakan Dan Kegiatan Pemerintah Daerah ',58,2,NULL),
(146,2,9,'Peliput Berita Kegiatan Pemda',58,2,NULL),
(147,2,9,'Penatalaksana Hubungan dan Kerjasama Antar Lembaga Informasi ',58,2,NULL),
(148,2,9,'Pramu Rumah Tangga',58,2,NULL),
(149,2,9,'Penyiap Akomodasi/Konsumsi',58,2,NULL),
(150,2,9,'Penatalaksana Keprotokolan',58,2,NULL),
(151,2,9,'Notulis',58,2,NULL),
(152,2,9,'Penyusun Pidato dan Makalah ',58,2,NULL),
(153,2,9,'Penyiap Layanan Penerimaan Tamu dan Aspirasi Masyarakat',58,2,NULL),
(154,2,9,'Penyusun Rencana Anggaran Sekretariat dan DPRD',58,2,NULL),
(155,2,9,'Pengarsip',58,2,NULL),
(156,2,9,'Penyusun Laporan Kegiatan DPRD dan Sekretariat DPRD',58,2,NULL),
(157,2,9,'Penyiap Bahan Penyusunan Program ',58,2,NULL),
(158,2,9,'Auditor Terampil Penyelia',58,2,NULL),
(159,2,9,'Auditor Ahli Madya',58,2,NULL),
(160,2,9,'Auditor Ahli Muda',58,2,NULL),
(161,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pengelolaan Administrasi Publik dan Keua',58,2,NULL),
(162,2,9,'Penyiap Data dan Informasi Pengkajian dan Pengembangan',58,2,NULL),
(163,2,9,'Pengkaji Penyusunan Diklat Teknis',58,2,NULL),
(164,2,9,'Pelaksana Fasilitasi Penyusunan Rencana Pengelolaan Administrasi Publik dan Keua',58,2,NULL),
(165,2,9,'Widyaiswara Muda',60,2,NULL),
(166,2,9,'Widyaiswara Pertama',58,2,NULL),
(167,2,9,'Arsiparis Terampil Penyelia',58,2,NULL),
(168,2,9,'Petugas Layanan  Perpustakaan ',58,2,NULL),
(169,2,9,'Pustakawan Ahli Pertama',58,2,NULL),
(170,2,9,'Penatalaksana Pengelolaan Arsip Dinamis',58,2,NULL),
(171,2,9,'Penatalaksana Deposit dan Pengolahan Bahan Pustaka',58,2,NULL),
(172,2,9,'Pustakawan Terampil Penyelia',60,2,NULL),
(173,2,9,'Pustakawan Ahli Madya',60,2,NULL),
(174,2,9,'Penyiap Bahan Pelestarian Bahan Pustaka dan Kerjasama Perpustakaan',58,2,NULL),
(175,2,9,'Penatalaksana Pelestarian Bahan Pustaka ',58,2,NULL),
(176,2,9,'Penatalaksana Pelayanan Perpustakaan',58,2,NULL),
(177,2,9,'Petugas Layanan Pendampingan Kelembagaan dan SDM Perpustakaan',58,2,NULL),
(178,2,9,'Petugas Pelestarian Bahan Pustaka',58,2,NULL),
(179,2,9,'Pustakawan Ahli Muda',60,2,NULL),
(180,2,9,'Petugas Pengolahan  Bahan Pustaka',58,2,NULL),
(181,2,9,'Penatalaksana Pembinaan Kelembagaan dan SDM Perpustakaan',58,2,NULL),
(182,2,9,'Petugas Deposit Bahan Pustaka',58,2,NULL),
(183,2,9,'Penatalaksana Pembinaan dan Pengawasan Penanggungjawab Usaha ',58,2,NULL),
(184,2,9,'Penatalaksana Pembinaan Penilaian Lingkungan',58,2,NULL),
(185,2,9,'Pengelola Data Perkembangan Nilai-nilai Kebangsaan dan Penanganan Konflik',58,2,NULL),
(186,2,9,'Penyiap Bahan Penyusunan Program dan Evaluasi',58,2,NULL),
(187,2,9,'Penganalisa Kapasitas Pengembangan Potensi dan Perekayasaan Sosial Perlindungan ',58,2,NULL),
(188,2,9,'Pengelola  Data Masalah Demokratisasi dan Perlindungan Hak Sipil',58,2,NULL),
(189,2,9,'Penatalaksana Pengembangan Budaya Politik Lokal dan Kewaspadaan',58,2,NULL),
(190,2,9,'Pengelola Data Partai Politik dan Organisasi Masyarakat',58,2,NULL),
(191,2,9,'Penyusun Rencana dan Prosedur Tetap Perlindungan Hak-hak Sipil',58,2,NULL),
(192,2,9,'Penatalaksana Pengembangan Kerukunan Umat Beragama',58,2,NULL),
(193,2,9,'Penatalaksana Pengembangan Demokrasi dan Perlindungan Hak-hak Sipil',58,2,NULL),
(194,2,9,'Penganalisa Kapasitas Pengembangan Potensi dan Perekayasaan Sosial Perlindungan ',58,2,NULL),
(195,2,9,'Pemantau dan Penganalisis Nilai-nilai Kebangsaan, Pembauran Bangsa dan Pengawasa',58,2,NULL),
(196,2,9,'Penyuluh Pertanian Ahli Madya',58,2,NULL),
(197,2,9,'Penjaga Kantor',58,2,NULL),
(198,2,9,'Penyiap Data Harga Pangan',58,2,NULL),
(199,2,9,'Pengkaji Peningkatan Akses Pangan',58,2,NULL),
(200,2,9,'Penganalisis Pengembangan Cadangan Pangan ',58,2,NULL),
(201,2,9,'Penatalaksana Pengembangan Kapasitas',58,2,NULL),
(202,2,9,'Penyiap Fasilitasi Peningkatan Akses Pangan ',58,2,NULL),
(203,2,9,'Pengawas Mutu Hasil Pertanian Ahli Madya',58,2,NULL),
(204,2,9,'Pengkaji Keamanan dan Kewaspadaan Pangan',58,2,NULL),
(205,2,9,'Pramu Kantor',58,2,NULL),
(206,2,9,'Pemantau  Keamanan dan Kewaspadaan Pangan',58,2,NULL),
(207,2,9,'Penyiap Data Program Penyuluhan',58,2,NULL),
(208,2,9,'Pengawas Mutu Hasil Pertanian Ahli Muda',58,2,NULL),
(209,2,9,'Penyiap Fasilitasi  Pengembangan Mutu dan Pelayanan Medik',58,2,NULL),
(210,2,9,'Penatalaksana Pengembangan Cadangan Pangan',58,2,NULL),
(211,2,9,'Pengawas Mutu Hasil Pertanian Terampil Pelaksana lanjutan',58,2,NULL),
(212,2,9,'Penganalisis Pengembangan Mutu Konsumsi Pangan',58,2,NULL),
(213,2,9,'Penyusun Rencana Pengembangan Potensi Penanaman Modal',58,2,NULL),
(214,2,9,'Pengelola Data Potensi dan Promosi Daerah',58,2,NULL),
(215,2,9,'Penatalaksana Fasilitasi  Kerjasama Pemerintah dan Lembaga Luar Negeri',58,2,NULL),
(216,2,9,'Penatalaksana Pengembangan Kelembagaan Organisasi Perempuan',58,2,NULL),
(217,2,9,'Gubernur Daerah Istimewa Yogyakarta',58,2,NULL),
(218,2,9,'Gubernur Daerah Istimewa Yogyakarta',58,2,NULL),
(219,2,9,'Wakil Gubernur Daerah Istimewa Yogyakarta',58,2,NULL);
/*!40000 ALTER TABLE `ref_jabatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_jabatan_jenis`
--

DROP TABLE IF EXISTS `ref_jabatan_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_jabatan_jenis` (
  `id_jab_jenis` int(1) NOT NULL AUTO_INCREMENT,
  `jenis_jabatan` varchar(50) NOT NULL,
  PRIMARY KEY (`id_jab_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_jabatan_jenis`
--

LOCK TABLES `ref_jabatan_jenis` WRITE;
/*!40000 ALTER TABLE `ref_jabatan_jenis` DISABLE KEYS */;
INSERT INTO `ref_jabatan_jenis` VALUES
(1,'Jabatan Struktural'),
(2,'Jabatan Fungsional Tertentu'),
(3,'Jabatan Fungsional Umum');
/*!40000 ALTER TABLE `ref_jabatan_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_jenis_kelamin`
--

DROP TABLE IF EXISTS `ref_jenis_kelamin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_jenis_kelamin` (
  `id_jeniskelamin` int(1) NOT NULL AUTO_INCREMENT,
  `jenis_kelamin` varchar(20) NOT NULL,
  PRIMARY KEY (`id_jeniskelamin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_jenis_kelamin`
--

LOCK TABLES `ref_jenis_kelamin` WRITE;
/*!40000 ALTER TABLE `ref_jenis_kelamin` DISABLE KEYS */;
INSERT INTO `ref_jenis_kelamin` VALUES
(1,'Laki-laki'),
(2,'Perempuan');
/*!40000 ALTER TABLE `ref_jenis_kelamin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_kabupaten`
--

DROP TABLE IF EXISTS `ref_kabupaten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_kabupaten` (
  `id_kabupaten` int(11) NOT NULL AUTO_INCREMENT,
  `id_propinsi` int(11) NOT NULL,
  `jenis` int(1) DEFAULT NULL,
  `kode_kabupaten` varchar(20) DEFAULT NULL,
  `kabupaten` varchar(150) NOT NULL,
  `luas` varchar(20) DEFAULT NULL,
  `dibuat` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kabupaten`),
  KEY `id_propinsi` (`id_propinsi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_kabupaten`
--

LOCK TABLES `ref_kabupaten` WRITE;
/*!40000 ALTER TABLE `ref_kabupaten` DISABLE KEYS */;
INSERT INTO `ref_kabupaten` VALUES
(1,23,1,'64.01','Paser','7730.88','2015-03-25 12:22:33'),
(2,23,1,'64.02','Kutai Kartanegara','23601.91','2015-03-25 12:22:33'),
(3,23,1,'64.03','Berau','21240.00','2015-03-25 12:22:33'),
(4,23,1,'64.07','Kutai Barat','20381.59','2015-03-25 12:22:33'),
(5,23,1,'64.08','Kutai Timur','35747.50','2015-03-25 12:22:33'),
(6,23,1,'64.09','Penajam Paser Utara','3333.06','2015-03-25 12:22:33'),
(7,23,1,'64.11','Mahakam Ulu','15315.00','2015-03-25 12:22:33'),
(8,23,2,'64.71','Balikpapan','527.00','2015-03-25 12:22:33'),
(9,23,2,'64.72','Samarinda','783.00','2015-03-25 12:22:33'),
(10,23,2,'64.74','Bontang','406.70','2015-03-25 12:22:33');
/*!40000 ALTER TABLE `ref_kabupaten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_kecamatan`
--

DROP TABLE IF EXISTS `ref_kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_kecamatan` (
  `id_kecamatan` int(11) NOT NULL AUTO_INCREMENT,
  `id_kabupaten` int(11) NOT NULL,
  `kode_kecamatan` varchar(20) NOT NULL,
  `kecamatan` varchar(150) NOT NULL,
  `dibuat` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kecamatan`),
  KEY `id_kabupaten` (`id_kabupaten`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_kecamatan`
--

LOCK TABLES `ref_kecamatan` WRITE;
/*!40000 ALTER TABLE `ref_kecamatan` DISABLE KEYS */;
INSERT INTO `ref_kecamatan` VALUES
(1,1,'64.01.01',' Batu Sopang','2015-03-25 13:25:38'),
(2,1,'64.01.02',' Tanjung Harapan','2015-03-25 13:25:38'),
(3,1,'64.01.03',' Pasir Balengkong','2015-03-25 13:25:38'),
(4,1,'64.01.04',' Tanah Grogot','2015-03-25 13:25:38'),
(5,1,'64.01.05',' Kuaro','2015-03-25 13:25:38'),
(6,1,'64.01.06',' Long Ikis','2015-03-25 13:25:38'),
(7,1,'64.01.07',' Muara Komam','2015-03-25 13:25:38'),
(8,1,'64.01.08',' Long Kali','2015-03-25 13:25:38'),
(9,1,'64.01.09',' Batu Engau','2015-03-25 13:25:38'),
(10,1,'64.01.10',' Muara Samu','2015-03-25 13:25:38'),
(11,2,'64.02.01',' Muara Muntai','2015-03-25 13:25:38'),
(12,2,'64.02.02',' Loa Kulu','2015-03-25 13:25:38'),
(13,2,'64.02.03',' Loa Janan','2015-03-25 13:25:38'),
(14,2,'64.02.04',' Anggana','2015-03-25 13:25:38'),
(15,2,'64.02.05',' Muara Badak','2015-03-25 13:25:38'),
(16,2,'64.02.06',' Tenggarong','2015-03-25 13:25:38'),
(17,2,'64.02.07',' Sebulu','2015-03-25 13:25:38'),
(18,2,'64.02.08',' Kota Bangun','2015-03-25 13:25:38'),
(19,2,'64.02.09',' Kenohan','2015-03-25 13:25:38'),
(20,2,'64.02.10',' Kembang Janggut','2015-03-25 13:25:38'),
(21,2,'64.02.11',' Muara Kaman','2015-03-25 13:25:38'),
(22,2,'64.02.12',' Tabang','2015-03-25 13:25:38'),
(23,2,'64.02.13',' Samboja','2015-03-25 13:25:38'),
(24,2,'64.02.14',' Muara Jawa','2015-03-25 13:25:38'),
(25,2,'64.02.15',' Sanga Sanga','2015-03-25 13:25:38'),
(26,2,'64.02.16',' Tenggarong Seberang','2015-03-25 13:25:38'),
(27,2,'64.02.17',' Marang Kayu','2015-03-25 13:25:38'),
(28,2,'64.02.18',' Muara Wis','2015-03-25 13:25:38'),
(29,3,'64.03.01',' Kelay','2015-03-25 13:25:38'),
(30,3,'64.03.02',' Talisayan','2015-03-25 13:25:38'),
(31,3,'64.03.03',' Sambaliung','2015-03-25 13:25:38'),
(32,3,'64.03.04',' Segah','2015-03-25 13:25:38'),
(33,3,'64.03.05',' Tanjung Redeb','2015-03-25 13:25:38'),
(34,3,'64.03.06',' Gunung Tabur','2015-03-25 13:25:38'),
(35,3,'64.03.07',' Pulau Derawan','2015-03-25 13:25:38'),
(36,3,'64.03.08',' Biduk-Biduk','2015-03-25 13:25:38'),
(37,3,'64.03.09',' Teluk Bayur','2015-03-25 13:25:38'),
(38,3,'64.03.10',' Tabalar','2015-03-25 13:25:38'),
(39,3,'64.03.11',' Maratua','2015-03-25 13:25:38'),
(40,3,'64.03.12',' Batu Putih','2015-03-25 13:25:38'),
(41,3,'64.03.13',' Biatan','2015-03-25 13:25:38'),
(42,4,'64.07.05',' Long Iram','2015-03-25 13:25:38'),
(43,4,'64.07.06',' Melak','2015-03-25 13:25:38'),
(44,4,'64.07.07',' Barong Tongkok','2015-03-25 13:25:38'),
(45,4,'64.07.08',' Damai','2015-03-25 13:25:38'),
(46,4,'64.07.09',' Muara Lawa','2015-03-25 13:25:38'),
(47,4,'64.07.10',' Muara Pahu','2015-03-25 13:25:38'),
(48,4,'64.07.11',' Jempang','2015-03-25 13:25:38'),
(49,4,'64.07.12',' Bongan','2015-03-25 13:25:38'),
(50,4,'64.07.13',' Penyinggahan','2015-03-25 13:25:38'),
(51,4,'64.07.14',' Bentian Besar','2015-03-25 13:25:38'),
(52,4,'64.07.15',' Linggang Bigung','2015-03-25 13:25:38'),
(53,4,'64.07.16',' Nyuatan','2015-03-25 13:25:38'),
(54,4,'64.07.17',' Siluq Ngurai','2015-03-25 13:25:38'),
(55,4,'64.07.18',' Mook Manaar Bulatn','2015-03-25 13:25:38'),
(56,4,'64.07.19',' Tering','2015-03-25 13:25:38'),
(57,4,'64.07.20',' Sekolaq Darat','2015-03-25 13:25:38'),
(58,5,'64.08.01',' Muara Ancalong','2015-03-25 13:25:38'),
(59,5,'64.08.02',' Muara Wahau','2015-03-25 13:25:38'),
(60,5,'64.08.03',' Muara Bengkal','2015-03-25 13:25:38'),
(61,5,'64.08.04',' Sangatta Utara','2015-03-25 13:25:38'),
(62,5,'64.08.05',' Sangkulirang','2015-03-25 13:25:38'),
(63,5,'64.08.06',' Busang','2015-03-25 13:25:38'),
(64,5,'64.08.07',' Telen','2015-03-25 13:25:38'),
(65,5,'64.08.08',' Kombeng','2015-03-25 13:25:38'),
(66,5,'64.08.09',' Bengalon','2015-03-25 13:25:38'),
(67,5,'64.08.10',' Kaliorang','2015-03-25 13:25:38'),
(68,5,'64.08.11',' Sandaran','2015-03-25 13:25:38'),
(69,5,'64.08.12',' Sangatta Selatan','2015-03-25 13:25:38'),
(70,5,'64.08.13',' Teluk Pandan','2015-03-25 13:25:38'),
(71,5,'64.08.14',' Rantau Pulung','2015-03-25 13:25:38'),
(72,5,'64.08.15',' Kaubun','2015-03-25 13:25:38'),
(73,5,'64.08.16',' Karangan','2015-03-25 13:25:38'),
(74,5,'64.08.17',' Batu Ampar','2015-03-25 13:25:38'),
(75,5,'64.08.18',' Long Mesangat','2015-03-25 13:25:38'),
(76,6,'64.09.01',' Penajam','2015-03-25 13:25:38'),
(77,6,'64.09.02','    Warn','2015-03-25 13:25:38'),
(78,6,'64.09.03',' Babulu','2015-03-25 13:25:38'),
(79,6,'64.09.04',' Sepaku','2015-03-25 13:25:38'),
(80,7,'64.11.01',' Long Bagun','2015-03-25 13:25:38'),
(81,7,'64.11.02',' Long Hubung','2015-03-25 13:25:38'),
(82,7,'64.11.03',' Laham','2015-03-25 13:25:38'),
(83,7,'64.11.04',' Long Apari','2015-03-25 13:25:38'),
(84,7,'64.11.05',' Long Pahangai','2015-03-25 13:25:38'),
(85,8,'64.71.01',' Balikpapan Timur','2015-03-25 13:25:38'),
(86,8,'64.71.02',' Balikpapan Barat','2015-03-25 13:25:38'),
(87,8,'64.71.03',' Balikpapan Utara','2015-03-25 13:25:38'),
(88,8,'64.71.04',' Balikpapan Tengah','2015-03-25 13:25:38'),
(89,8,'64.71.05',' Balikpapan Selatan','2015-03-25 13:25:38'),
(90,8,'64.71.06',' Balikpapan Kota','2015-03-25 13:25:38'),
(91,9,'64.72.01',' Palaran','2015-03-25 13:25:38'),
(92,9,'64.72.02',' Samarinda Seberang','2015-03-25 13:25:38'),
(93,9,'64.72.03',' Samarinda Ulu','2015-03-25 13:25:38'),
(94,9,'64.72.04',' Samarinda Ilir','2015-03-25 13:25:38'),
(95,9,'64.72.05',' Samarinda Utara','2015-03-25 13:25:38'),
(96,9,'64.72.06',' Sungai Kunjang','2015-03-25 13:25:38'),
(97,9,'64.72.07',' Sambutan','2015-03-25 13:25:38'),
(98,9,'64.72.08',' Sungai Pinang','2015-03-25 13:25:38'),
(99,9,'64.72.09',' Samarinda Kota','2015-03-25 13:25:38'),
(100,9,'64.72.10',' Loa Janan Ilir','2015-03-25 13:25:38'),
(101,10,'64.74.01',' Bontang Utara','2015-03-25 13:25:38'),
(102,10,'64.74.02',' Bontang Selatan','2015-03-25 13:25:38'),
(103,10,'64.74.03',' Bontang Barat','2015-03-25 13:25:38');
/*!40000 ALTER TABLE `ref_kecamatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_kelurahan`
--

DROP TABLE IF EXISTS `ref_kelurahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_kelurahan` (
  `id_kelurahan` int(11) NOT NULL AUTO_INCREMENT,
  `id_kecamatan` int(11) NOT NULL,
  `jenis` int(1) DEFAULT NULL COMMENT '1=kelurahan,2=desa',
  `kode_kelurahan` varchar(20) DEFAULT NULL,
  `kelurahan` varchar(150) NOT NULL,
  `kodepos` varchar(10) DEFAULT NULL,
  `dibuat` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kelurahan`),
  UNIQUE KEY `id_kecamatan` (`id_kecamatan`,`kelurahan`)
) ENGINE=InnoDB AUTO_INCREMENT=1030 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_kelurahan`
--

LOCK TABLES `ref_kelurahan` WRITE;
/*!40000 ALTER TABLE `ref_kelurahan` DISABLE KEYS */;
INSERT INTO `ref_kelurahan` VALUES
(1,1,2,'64.01.01.2009','Samuranggau','','0000-00-00 00:00:00'),
(2,1,2,'64.01.01.2010','Busui','','0000-00-00 00:00:00'),
(3,1,2,'64.01.01.2011','Batu Kajang','','0000-00-00 00:00:00'),
(4,1,2,'64.01.01.2012','Legai','','0000-00-00 00:00:00'),
(5,1,2,'64.01.01.2013','Sungaiterik','','0000-00-00 00:00:00'),
(6,1,2,'64.01.01.2014','Kasungai','','0000-00-00 00:00:00'),
(7,1,2,'64.01.01.2015','Rantau Buta','','0000-00-00 00:00:00'),
(8,1,2,'64.01.01.2016','Rantau Layung','','0000-00-00 00:00:00'),
(9,1,2,'64.01.01.2017','Songka','','0000-00-00 00:00:00'),
(10,2,2,'64.01.02.2004','Keladen','','0000-00-00 00:00:00'),
(11,2,2,'64.01.02.2006','Tanjungaru','','0000-00-00 00:00:00'),
(12,2,2,'64.01.02.2007','Labuangkallo','','0000-00-00 00:00:00'),
(13,2,2,'64.01.02.2012','Lori','','0000-00-00 00:00:00'),
(14,2,2,'64.01.02.2013','Selengot','','0000-00-00 00:00:00'),
(15,2,2,'64.01.02.2014','Random','','0000-00-00 00:00:00'),
(16,2,2,'64.01.02.2015','Senipah','','0000-00-00 00:00:00'),
(17,3,2,'64.01.03.2001','Lempesu','','0000-00-00 00:00:00'),
(18,3,2,'64.01.03.2002','Bekoso','','0000-00-00 00:00:00'),
(19,3,2,'64.01.03.2003','Damit','','0000-00-00 00:00:00'),
(20,3,2,'64.01.03.2004','Suatang','','0000-00-00 00:00:00'),
(21,3,2,'64.01.03.2005','Suliliran','','0000-00-00 00:00:00'),
(22,3,2,'64.01.03.2006','Pasir Belengkong','','0000-00-00 00:00:00'),
(23,3,2,'64.01.03.2007','Sangkuriman','','0000-00-00 00:00:00'),
(24,3,2,'64.01.03.2008','Laburan','','0000-00-00 00:00:00'),
(25,3,2,'64.01.03.2009','Suliliran Baru','','0000-00-00 00:00:00'),
(26,3,2,'64.01.03.2010','Keresik Bura','','0000-00-00 00:00:00'),
(27,3,2,'64.01.03.2011','Laburan Baru','','0000-00-00 00:00:00'),
(28,3,2,'64.01.03.2012','Seniung Jaya','','0000-00-00 00:00:00'),
(29,3,2,'64.01.03.2013','Sunge Batu','','0000-00-00 00:00:00'),
(30,3,2,'64.01.03.2014','Olong Pinang','','0000-00-00 00:00:00'),
(31,3,2,'64.01.03.2015','Suatang Keteban','','0000-00-00 00:00:00'),
(32,4,1,'64.01.04.1001','Tanah Grogot','','0000-00-00 00:00:00'),
(33,4,2,'64.01.04.2002','Janju','','0000-00-00 00:00:00'),
(34,4,2,'64.01.04.2003','Tepian Batang','','0000-00-00 00:00:00'),
(35,4,2,'64.01.04.2004','Tanah Periuk','','0000-00-00 00:00:00'),
(36,4,2,'64.01.04.2005','Pepara','','0000-00-00 00:00:00'),
(37,4,2,'64.01.04.2006','Sungaituak','','0000-00-00 00:00:00'),
(38,4,2,'64.01.04.2007','Sempulang','','0000-00-00 00:00:00'),
(39,4,2,'64.01.04.2008','Jone','','0000-00-00 00:00:00'),
(40,4,2,'64.01.04.2009','Rantau Panjang','','0000-00-00 00:00:00'),
(41,4,2,'64.01.04.2010','Muarapasir','','0000-00-00 00:00:00'),
(42,4,2,'64.01.04.2011','Padang Pengrapat','','0000-00-00 00:00:00'),
(43,4,2,'64.01.04.2012','Perepat','','0000-00-00 00:00:00'),
(44,4,2,'64.01.04.2013','Pulaurantau','','0000-00-00 00:00:00'),
(45,4,2,'64.01.04.2014','Sungailangir','','0000-00-00 00:00:00'),
(46,4,2,'64.01.04.2015','Tapis','','0000-00-00 00:00:00'),
(47,4,2,'64.01.04.2016','Senaken','','0000-00-00 00:00:00'),
(48,5,1,'64.01.05.1001','Kuaro','','0000-00-00 00:00:00'),
(49,5,2,'64.01.05.2002','Keluang Lolo','','0000-00-00 00:00:00'),
(50,5,2,'64.01.05.2003','Sandeley','','0000-00-00 00:00:00'),
(51,5,2,'64.01.05.2004','Harapan Baru','','0000-00-00 00:00:00'),
(52,5,2,'64.01.05.2005','Rangan','','0000-00-00 00:00:00'),
(53,5,2,'64.01.05.2006','Modang','','0000-00-00 00:00:00'),
(54,5,2,'64.01.05.2007','Pasir Mayang','','0000-00-00 00:00:00'),
(55,5,2,'64.01.05.2008','Kerta Bumi','','0000-00-00 00:00:00'),
(56,5,2,'64.01.05.2009','Pondang Baru','','0000-00-00 00:00:00'),
(57,5,2,'64.01.05.2010','Padang Jaya','','0000-00-00 00:00:00'),
(58,5,2,'64.01.05.2011','Kendarom','','0000-00-00 00:00:00'),
(59,5,2,'64.01.05.2012','Klempang Sari','','0000-00-00 00:00:00'),
(60,5,2,'64.01.05.2013','Keluang Paser Jaya','','0000-00-00 00:00:00'),
(61,6,1,'64.01.06.1001','Long Ikis','','0000-00-00 00:00:00'),
(62,6,2,'64.01.06.2002','Muara Adang','','0000-00-00 00:00:00'),
(63,6,2,'64.01.06.2003','Telukwaru','','0000-00-00 00:00:00'),
(64,6,2,'64.01.06.2004','Tajur','','0000-00-00 00:00:00'),
(65,6,2,'64.01.06.2005','Samuntai','','0000-00-00 00:00:00'),
(66,6,2,'64.01.06.2006','Lombok','','0000-00-00 00:00:00'),
(67,6,2,'64.01.06.2007','Pait','','0000-00-00 00:00:00'),
(68,6,2,'64.01.06.2008','Olung','','0000-00-00 00:00:00'),
(69,6,2,'64.01.06.2009','Kayungo','','0000-00-00 00:00:00'),
(70,6,2,'64.01.06.2010','Jemparing','','0000-00-00 00:00:00'),
(71,6,2,'64.01.06.2011','Tiwei','','0000-00-00 00:00:00'),
(72,6,2,'64.01.06.2012','Belimbing','','0000-00-00 00:00:00'),
(73,6,2,'64.01.06.2013','Long Gelang','','0000-00-00 00:00:00'),
(74,6,2,'64.01.06.2014','Krayan Jaya','','0000-00-00 00:00:00'),
(75,6,2,'64.01.06.2015','Bukitsaloka','','0000-00-00 00:00:00'),
(76,6,2,'64.01.06.2016','Krayan Sentosa','','0000-00-00 00:00:00'),
(77,6,2,'64.01.06.2017','Krayan Makmur','','0000-00-00 00:00:00'),
(78,6,2,'64.01.06.2018','Kayungo Sari','','0000-00-00 00:00:00'),
(79,6,2,'64.01.06.2019','Krayan Bahagia','','0000-00-00 00:00:00'),
(80,6,2,'64.01.06.2020','Sawit Jaya','','0000-00-00 00:00:00'),
(81,6,2,'64.01.06.2021','Sekurou Jaya','','0000-00-00 00:00:00'),
(82,6,2,'64.01.06.2022','Kerta Bhakti','','0000-00-00 00:00:00'),
(83,6,2,'64.01.06.2023','Adang Jaya','','0000-00-00 00:00:00'),
(84,6,2,'64.01.06.2024','Tajer Mulya','','0000-00-00 00:00:00'),
(85,6,2,'64.01.06.2025','Brewe','','0000-00-00 00:00:00'),
(86,6,2,'64.01.06.2026','Atang Pait','','0000-00-00 00:00:00'),
(87,7,1,'64.01.07.1001','Muarakomam','','0000-00-00 00:00:00'),
(88,7,2,'64.01.07.2002','Batu Butok','','0000-00-00 00:00:00'),
(89,7,2,'64.01.07.2003','Uko','','0000-00-00 00:00:00'),
(90,7,2,'64.01.07.2004','Muaralangon','','0000-00-00 00:00:00'),
(91,7,2,'64.01.07.2005','Binangon','','0000-00-00 00:00:00'),
(92,7,2,'64.01.07.2006','Muarakuaro','','0000-00-00 00:00:00'),
(93,7,2,'64.01.07.2007','Prayon','','0000-00-00 00:00:00'),
(94,7,2,'64.01.07.2008','Long Sayo','','0000-00-00 00:00:00'),
(95,7,2,'64.01.07.2009','Muarapayang','','0000-00-00 00:00:00'),
(96,7,2,'64.01.07.2010','Lusan','','0000-00-00 00:00:00'),
(97,7,2,'64.01.07.2011','Swan Slutung','','0000-00-00 00:00:00'),
(98,7,2,'64.01.07.2012','Sekuan Makmur','','0000-00-00 00:00:00'),
(99,7,2,'64.01.07.2013','Selerong','','0000-00-00 00:00:00'),
(100,8,1,'64.01.08.1001','Long Kali','','0000-00-00 00:00:00'),
(101,8,2,'64.01.08.2002','Muaratelake','','0000-00-00 00:00:00'),
(102,8,2,'64.01.08.2003','Sembakung','','0000-00-00 00:00:00'),
(103,8,2,'64.01.08.2004','Bente Tualan','','0000-00-00 00:00:00'),
(104,8,2,'64.01.08.2005','Mendik','','0000-00-00 00:00:00'),
(105,8,2,'64.01.08.2006','Munggu','','0000-00-00 00:00:00'),
(106,8,2,'64.01.08.2007','Muarapias','','0000-00-00 00:00:00'),
(107,8,2,'64.01.08.2008','Muaratoyu','','0000-00-00 00:00:00'),
(108,8,2,'64.01.08.2009','Perkuwen','','0000-00-00 00:00:00'),
(109,8,2,'64.01.08.2010','Pinang Jatus','','0000-00-00 00:00:00'),
(110,8,2,'64.01.08.2011','Muaralembakan','','0000-00-00 00:00:00'),
(111,8,2,'64.01.08.2012','Kepala Telake','','0000-00-00 00:00:00'),
(112,8,2,'64.01.08.2013','Mendik Makmur','','0000-00-00 00:00:00'),
(113,8,2,'64.01.08.2014','Mendik Karya','','0000-00-00 00:00:00'),
(114,8,2,'64.01.08.2015','Mendik Bhakti','','0000-00-00 00:00:00'),
(115,8,2,'64.01.08.2016','Sebakung Taka','','0000-00-00 00:00:00'),
(116,8,2,'64.01.08.2017','Maruat','','0000-00-00 00:00:00'),
(117,8,2,'64.01.08.2018','Sebakung Makmur','','0000-00-00 00:00:00'),
(118,8,2,'64.01.08.2019','Petiku','','0000-00-00 00:00:00'),
(119,8,2,'64.01.08.2020','Muaraadang II','','0000-00-00 00:00:00'),
(120,8,2,'64.01.08.2021','Makmur Jaya','','0000-00-00 00:00:00'),
(121,8,2,'64.01.08.2022','Gunungputar','','0000-00-00 00:00:00'),
(122,8,2,'64.01.08.2023','Putang','','0000-00-00 00:00:00'),
(123,9,2,'64.01.09.2001','Kerang','','0000-00-00 00:00:00'),
(124,9,2,'64.01.09.2002','Lomu','','0000-00-00 00:00:00'),
(125,9,2,'64.01.09.2003','Segendang','','0000-00-00 00:00:00'),
(126,9,2,'64.01.09.2004','Mengkudu','','0000-00-00 00:00:00'),
(127,9,2,'64.01.09.2005','Riwang','','0000-00-00 00:00:00'),
(128,9,2,'64.01.09.2006','Langgai','','0000-00-00 00:00:00'),
(129,9,2,'64.01.09.2007','Petangis','','0000-00-00 00:00:00'),
(130,9,2,'64.01.09.2008','Tempakan','','0000-00-00 00:00:00'),
(131,9,2,'64.01.09.2009','Kerang Dayo','','0000-00-00 00:00:00'),
(132,9,2,'64.01.09.2010','Saing Prupuk','','0000-00-00 00:00:00'),
(133,9,2,'64.01.09.2011','Bai Jaya','','0000-00-00 00:00:00'),
(134,9,2,'64.01.09.2012','Pengguren Jaya','','0000-00-00 00:00:00'),
(135,9,2,'64.01.09.2013','Tebru Paser Damai','','0000-00-00 00:00:00'),
(136,10,2,'64.01.10.2001','Tanjung Pinang','','0000-00-00 00:00:00'),
(137,10,2,'64.01.10.2002','Rantau Atas','','0000-00-00 00:00:00'),
(138,10,2,'64.01.10.2003','Libur Dinding','','0000-00-00 00:00:00'),
(139,10,2,'64.01.10.2004','Luan','','0000-00-00 00:00:00'),
(140,10,2,'64.01.10.2005','Suweto','','0000-00-00 00:00:00'),
(141,10,2,'64.01.10.2006','Muser','','0000-00-00 00:00:00'),
(142,10,2,'64.01.10.2007','Biu','','0000-00-00 00:00:00'),
(143,10,2,'64.01.10.2008','Rantau Bintungan','','0000-00-00 00:00:00'),
(144,10,2,'64.01.10.2009','Muara Andeh','','0000-00-00 00:00:00'),
(145,11,2,'64.02.01.2001','Perian','','0000-00-00 00:00:00'),
(146,11,2,'64.02.01.2002','Muara Leka','','0000-00-00 00:00:00'),
(147,11,2,'64.02.01.2003','Muara Aloh','','0000-00-00 00:00:00'),
(148,11,2,'64.02.01.2004','Jantur','','0000-00-00 00:00:00'),
(149,11,2,'64.02.01.2005','Batuq','','0000-00-00 00:00:00'),
(150,11,2,'64.02.01.2006','Rebaq Rinding','','0000-00-00 00:00:00'),
(151,11,2,'64.02.01.2007','Muara Muntai Ulu','','0000-00-00 00:00:00'),
(152,11,2,'64.02.01.2008','Muara Muntai Ilir','','0000-00-00 00:00:00'),
(153,11,2,'64.02.01.2009','Kayu Batu','','0000-00-00 00:00:00'),
(154,11,2,'64.02.01.2010','Jantur Selatan','','0000-00-00 00:00:00'),
(155,11,2,'64.02.01.2011','Tanjung Batuq Harapan','','0000-00-00 00:00:00'),
(156,11,2,'64.02.01.2012','Pulau Harapan','','0000-00-00 00:00:00'),
(157,11,2,'64.02.01.2013','Jantur Baru','','0000-00-00 00:00:00'),
(158,12,2,'64.02.02.2001','Jonggon','','0000-00-00 00:00:00'),
(159,12,2,'64.02.02.2002','Sungai Payang','','0000-00-00 00:00:00'),
(160,12,2,'64.02.02.2003','Jembayan','','0000-00-00 00:00:00'),
(161,12,2,'64.02.02.2004','Loa Kulu Kota','','0000-00-00 00:00:00'),
(162,12,2,'64.02.02.2005','Loh Sumber','','0000-00-00 00:00:00'),
(163,12,2,'64.02.02.2006','Ponoragan','','0000-00-00 00:00:00'),
(164,12,2,'64.02.02.2007','Rempanga','','0000-00-00 00:00:00'),
(165,12,2,'64.02.02.2008','Marga Rahayu','','0000-00-00 00:00:00'),
(166,12,2,'64.02.02.2009','Karya Utama','','0000-00-00 00:00:00'),
(167,12,2,'64.02.02.2010','Lung Anai','','0000-00-00 00:00:00'),
(168,12,2,'64.02.02.2011','Jembayan Tengah','','0000-00-00 00:00:00'),
(169,12,2,'64.02.02.2012','Jembayan Dalam','','0000-00-00 00:00:00'),
(170,12,2,'64.02.02.2013','Sepakat','','0000-00-00 00:00:00'),
(171,12,2,'64.02.02.2014','Sumber Sari','','0000-00-00 00:00:00'),
(172,12,2,'64.02.02.2015','Jongkang','','0000-00-00 00:00:00'),
(173,13,2,'64.02.03.2001','Bakungan','','0000-00-00 00:00:00'),
(174,13,2,'64.02.03.2002','Loa Duri Ulu','','0000-00-00 00:00:00'),
(175,13,2,'64.02.03.2003','Loa Janan Ulu','','0000-00-00 00:00:00'),
(176,13,2,'64.02.03.2004','Purwajaya','','0000-00-00 00:00:00'),
(177,13,2,'64.02.03.2005','Tani Bhakti','','0000-00-00 00:00:00'),
(178,13,2,'64.02.03.2006','Batuah','','0000-00-00 00:00:00'),
(179,13,2,'64.02.03.2007','Loa Duri Ilir','','0000-00-00 00:00:00'),
(180,13,2,'64.02.03.2008','Tani Harapan','','0000-00-00 00:00:00'),
(181,14,2,'64.02.04.2001','Sepatin','','0000-00-00 00:00:00'),
(182,14,2,'64.02.04.2002','Muara Pantuan','','0000-00-00 00:00:00'),
(183,14,2,'64.02.04.2003','Tani Baru','','0000-00-00 00:00:00'),
(184,14,2,'64.02.04.2004','Kutai Lama','','0000-00-00 00:00:00'),
(185,14,2,'64.02.04.2005','Anggana','','0000-00-00 00:00:00'),
(186,14,2,'64.02.04.2006','Sungai Mariam','','2015-04-24 15:47:57'),
(187,14,2,'64.02.04.2007','Sidomulyo','','0000-00-00 00:00:00'),
(188,14,2,'64.02.04.2008','Handil Terusan','','0000-00-00 00:00:00'),
(189,15,2,'64.02.05.2001','Saliki','','2015-04-24 15:47:31'),
(190,15,2,'64.02.05.2002','Salo Palai','','0000-00-00 00:00:00'),
(191,15,2,'64.02.05.2003','Muara Badak Ulu','','0000-00-00 00:00:00'),
(192,15,2,'64.02.05.2004','Muara Badak Ilir','','0000-00-00 00:00:00'),
(193,15,2,'64.02.05.2005','Tanjung Limau','','0000-00-00 00:00:00'),
(194,15,2,'64.02.05.2006','Tanah Datar','','0000-00-00 00:00:00'),
(195,15,2,'64.02.05.2007','Badak Baru','','0000-00-00 00:00:00'),
(196,15,2,'64.02.05.2008','Suka Damai','','0000-00-00 00:00:00'),
(197,15,2,'64.02.05.2009','Badak Mekar','','0000-00-00 00:00:00'),
(198,15,2,'64.02.05.2010','Gas Alam Badak I','','0000-00-00 00:00:00'),
(199,15,2,'64.02.05.2011','Batu-Batu','','0000-00-00 00:00:00'),
(200,15,2,'64.02.05.2012','Salo Cella','','0000-00-00 00:00:00'),
(201,15,2,'64.02.05.2013','Sungai Bawang','','0000-00-00 00:00:00'),
(202,16,1,'64.02.06.1001','Jahab','','0000-00-00 00:00:00'),
(203,16,1,'64.02.06.1002','Loa Ipuh','','0000-00-00 00:00:00'),
(204,16,1,'64.02.06.1003','Bukit Biru','','0000-00-00 00:00:00'),
(205,16,1,'64.02.06.1004','Timbau','','0000-00-00 00:00:00'),
(206,16,1,'64.02.06.1005','Melayu','','0000-00-00 00:00:00'),
(207,16,1,'64.02.06.1006','Panji','','0000-00-00 00:00:00'),
(208,16,1,'64.02.06.1007','Sukarame','','0000-00-00 00:00:00'),
(209,16,1,'64.02.06.1008','Kampung Baru','','0000-00-00 00:00:00'),
(210,16,1,'64.02.06.1009','Loa Tebu','','0000-00-00 00:00:00'),
(211,16,1,'64.02.06.1010','Mangkurawang','','0000-00-00 00:00:00'),
(212,16,1,'64.02.06.1011','Maluhu','','0000-00-00 00:00:00'),
(213,16,2,'64.02.06.2012','Rampak Lambur','','0000-00-00 00:00:00'),
(214,16,1,'64.02.06.1013','Loa Ipuh Darat','','0000-00-00 00:00:00'),
(215,16,2,'64.02.06.2014','Bendang Raya','','0000-00-00 00:00:00'),
(216,17,2,'64.02.07.2001','Selerong','','0000-00-00 00:00:00'),
(217,17,2,'64.02.07.2002','Tanjung Harapan','','0000-00-00 00:00:00'),
(218,17,2,'64.02.07.2003','Beloro','','0000-00-00 00:00:00'),
(219,17,2,'64.02.07.2004','Sebulu Ulu','','0000-00-00 00:00:00'),
(220,17,2,'64.02.07.2005','Sebulu Ilir','','0000-00-00 00:00:00'),
(221,17,2,'64.02.07.2006','Segihan','','0000-00-00 00:00:00'),
(222,17,2,'64.02.07.2007','Sumber Sari','','0000-00-00 00:00:00'),
(223,17,2,'64.02.07.2008','Manunggal Jaya','','0000-00-00 00:00:00'),
(224,17,2,'64.02.07.2009','Giri Agung','','0000-00-00 00:00:00'),
(225,17,2,'64.02.07.2010','Senoni','','0000-00-00 00:00:00'),
(226,17,2,'64.02.07.2011','Sebulu Moderen','','0000-00-00 00:00:00'),
(227,17,2,'64.02.07.2012','Sanggulan','','0000-00-00 00:00:00'),
(228,17,2,'64.02.07.2013','Lekaq Kidau','','0000-00-00 00:00:00'),
(229,17,2,'64.02.07.2014','Mekar Jaya','','0000-00-00 00:00:00'),
(230,18,2,'64.02.08.2001','Kedang Ipil','','0000-00-00 00:00:00'),
(231,18,2,'64.02.08.2002','Benua Baru','','0000-00-00 00:00:00'),
(232,18,2,'64.02.08.2003','Sedulang','','0000-00-00 00:00:00'),
(233,18,2,'64.02.08.2004','Loleng','','0000-00-00 00:00:00'),
(234,18,2,'64.02.08.2005','Kota Bangun Ulu','','0000-00-00 00:00:00'),
(235,18,2,'64.02.08.2006','Kota Bangun Ilir','','0000-00-00 00:00:00'),
(236,18,2,'64.02.08.2007','Liang','','0000-00-00 00:00:00'),
(237,18,2,'64.02.08.2008','Muhuran','','0000-00-00 00:00:00'),
(238,18,2,'64.02.08.2009','Pela','','0000-00-00 00:00:00'),
(239,18,2,'64.02.08.2010','Kota Bangun I','','0000-00-00 00:00:00'),
(240,18,2,'64.02.08.2011','Kota Bangun II','','0000-00-00 00:00:00'),
(241,18,2,'64.02.08.2012','Kota Bangun III','','0000-00-00 00:00:00'),
(242,18,2,'64.02.08.2013','Sumber Sari','','0000-00-00 00:00:00'),
(243,18,2,'64.02.08.2014','Sari Nadi','','0000-00-00 00:00:00'),
(244,18,2,'64.02.08.2015','Suka Bumi','','0000-00-00 00:00:00'),
(245,18,2,'64.02.08.2016','Wonosari','','0000-00-00 00:00:00'),
(246,18,2,'64.02.08.2017','Kedang Murung','','0000-00-00 00:00:00'),
(247,18,2,'64.02.08.2018','Kota Bangun Seberang','','0000-00-00 00:00:00'),
(248,18,2,'64.02.08.2019','Liang Ulu','','0000-00-00 00:00:00'),
(249,18,2,'64.02.08.2020','Sebelimbingan','','0000-00-00 00:00:00'),
(250,18,2,'64.02.08.2021','Sangkuliman','','0000-00-00 00:00:00'),
(251,19,2,'64.02.09.2001','Lamin Telihan','','0000-00-00 00:00:00'),
(252,19,2,'64.02.09.2002','Lamin Pulut','','0000-00-00 00:00:00'),
(253,19,2,'64.02.09.2003','Teluk Bingkai','','0000-00-00 00:00:00'),
(254,19,2,'64.02.09.2004','Kahala','','0000-00-00 00:00:00'),
(255,19,2,'64.02.09.2005','Tubuhan','','0000-00-00 00:00:00'),
(256,19,2,'64.02.09.2006','Semayang','','0000-00-00 00:00:00'),
(257,19,2,'64.02.09.2007','Teluk Muda','','0000-00-00 00:00:00'),
(258,19,2,'64.02.09.2008','Tuana Tuha','','0000-00-00 00:00:00'),
(259,19,2,'64.02.09.2009','Kahala Ilir','','0000-00-00 00:00:00'),
(260,20,2,'64.02.10.2001','Genting Tanah','','0000-00-00 00:00:00'),
(261,20,2,'64.02.10.2002','Loa Sakuh','','0000-00-00 00:00:00'),
(262,20,2,'64.02.10.2003','Hambau','','0000-00-00 00:00:00'),
(263,20,2,'64.02.10.2004','Kembang Janggut','','0000-00-00 00:00:00'),
(264,20,2,'64.02.10.2005','Kelekat','','0000-00-00 00:00:00'),
(265,20,2,'64.02.10.2006','Pulau Pinang','','0000-00-00 00:00:00'),
(266,20,2,'64.02.10.2007','Long Beleh Haloq','','0000-00-00 00:00:00'),
(267,20,2,'64.02.10.2008','Long Beleh Modang','','0000-00-00 00:00:00'),
(268,20,2,'64.02.10.2009','Muai','','0000-00-00 00:00:00'),
(269,20,2,'64.02.10.2010','Perdana','','0000-00-00 00:00:00'),
(270,20,2,'64.02.10.2011','Bukit Layang','','0000-00-00 00:00:00'),
(271,21,2,'64.02.11.2001','Muara Kaman Ilir','','0000-00-00 00:00:00'),
(272,21,2,'64.02.11.2002','Rantau Hempang','','0000-00-00 00:00:00'),
(273,21,2,'64.02.11.2003','Teratak','','0000-00-00 00:00:00'),
(274,21,2,'64.02.11.2004','Benua Puhun','','0000-00-00 00:00:00'),
(275,21,2,'64.02.11.2005','Muara Kaman Ulu','','0000-00-00 00:00:00'),
(276,21,2,'64.02.11.2006','Sabintulung','','0000-00-00 00:00:00'),
(277,21,2,'64.02.11.2007','Muara Siran','','0000-00-00 00:00:00'),
(278,21,2,'64.02.11.2008','Tunjungan','','0000-00-00 00:00:00'),
(279,21,2,'64.02.11.2009','Sedulang','','0000-00-00 00:00:00'),
(280,21,2,'64.02.11.2010','Menamang Kiri','','0000-00-00 00:00:00'),
(281,21,2,'64.02.11.2011','Menamang Kanan','','0000-00-00 00:00:00'),
(282,21,2,'64.02.11.2012','Sidomukti','','0000-00-00 00:00:00'),
(283,21,2,'64.02.11.2013','Panca Jaya','','0000-00-00 00:00:00'),
(284,21,2,'64.02.11.2014','Bunga Jadi','','0000-00-00 00:00:00'),
(285,21,2,'64.02.11.2015','Kupang Baru','','0000-00-00 00:00:00'),
(286,21,2,'64.02.11.2016','Lebahu Uloq','','0000-00-00 00:00:00'),
(287,21,2,'64.02.11.2017','Bukit Jering','','0000-00-00 00:00:00'),
(288,21,2,'64.02.11.2018','Liang Buaya','','0000-00-00 00:00:00'),
(289,21,2,'64.02.11.2019','Puan Cepak','','0000-00-00 00:00:00'),
(290,21,2,'64.02.11.2020','Cipari Makmur','','0000-00-00 00:00:00'),
(291,22,2,'64.02.12.2001','Gunung Sari','','0000-00-00 00:00:00'),
(292,22,2,'64.02.12.2002','Long Lalang','','0000-00-00 00:00:00'),
(293,22,2,'64.02.12.2003','Muara Ritan','','0000-00-00 00:00:00'),
(294,22,2,'64.02.12.2004','Buluk Sen','','0000-00-00 00:00:00'),
(295,22,2,'64.02.12.2005','Umaq Dian','','0000-00-00 00:00:00'),
(296,22,2,'64.02.12.2006','Muara Pedohon','','0000-00-00 00:00:00'),
(297,22,2,'64.02.12.2007','Bila Talang','','0000-00-00 00:00:00'),
(298,22,2,'64.02.12.2008','Kampung Baru','','0000-00-00 00:00:00'),
(299,22,2,'64.02.12.2009','Umaq Tukung','','0000-00-00 00:00:00'),
(300,22,2,'64.02.12.2010','Sidomulyo','','0000-00-00 00:00:00'),
(301,22,2,'64.02.12.2011','Umaq Bekuay','','0000-00-00 00:00:00'),
(302,22,2,'64.02.12.2012','Tabang Lama','','0000-00-00 00:00:00'),
(303,22,2,'64.02.12.2013','Muara Tiq','','0000-00-00 00:00:00'),
(304,22,2,'64.02.12.2014','Muara Salung','','0000-00-00 00:00:00'),
(305,22,2,'64.02.12.2015','Muara Kebaq','','0000-00-00 00:00:00'),
(306,22,2,'64.02.12.2016','Muara Belinau','','0000-00-00 00:00:00'),
(307,22,2,'64.02.12.2017','Muara Tubod','','0000-00-00 00:00:00'),
(308,22,2,'64.02.12.2018','Ritan Baru','','0000-00-00 00:00:00'),
(309,22,2,'64.02.12.2019','Tukung Ritan','','0000-00-00 00:00:00'),
(310,23,1,'64.02.13.1001','Salok Api Barat','','0000-00-00 00:00:00'),
(311,23,1,'64.02.13.1002','Salok Api Laut','','0000-00-00 00:00:00'),
(312,23,1,'64.02.13.1003','Ambarawang Laut','','0000-00-00 00:00:00'),
(313,23,1,'64.02.13.1004','Ambarawang Darat','','0000-00-00 00:00:00'),
(314,23,1,'64.02.13.1005','Margomulyo','','0000-00-00 00:00:00'),
(315,23,1,'64.02.13.1006','Sungai Merdeka','','0000-00-00 00:00:00'),
(316,23,1,'64.02.13.1007','Sungai Seluang','','0000-00-00 00:00:00'),
(317,23,1,'64.02.13.1008','Wonotirto','','0000-00-00 00:00:00'),
(318,23,1,'64.02.13.1009','Tanjung Harapan','','0000-00-00 00:00:00'),
(319,23,1,'64.02.13.1010','Samboja Kuala','','0000-00-00 00:00:00'),
(320,23,1,'64.02.13.1011','Sanipah','','0000-00-00 00:00:00'),
(321,23,1,'64.02.13.1012','Handil Baru','','0000-00-00 00:00:00'),
(322,23,1,'64.02.13.1013','Muara Sembilang','','0000-00-00 00:00:00'),
(323,23,2,'64.02.13.2014','Karya Jaya','','0000-00-00 00:00:00'),
(324,23,1,'64.02.13.1015','Bukit Merdeka','','0000-00-00 00:00:00'),
(325,23,2,'64.02.13.2016','Bukit Raya','','0000-00-00 00:00:00'),
(326,23,1,'64.02.13.1017','Argo Sari','','0000-00-00 00:00:00'),
(327,23,2,'64.02.13.2018','Tani Bhakti','','0000-00-00 00:00:00'),
(328,23,2,'64.02.13.2019','Beringin Agung','','0000-00-00 00:00:00'),
(329,23,1,'64.02.13.1020','Karya Merdeka','','0000-00-00 00:00:00'),
(330,23,1,'64.02.13.1021','Teluk Pemedas','','0000-00-00 00:00:00'),
(331,23,1,'64.02.13.1022','Kampung Lama','','0000-00-00 00:00:00'),
(332,23,1,'64.02.13.1023','Handil Baru Darat','','0000-00-00 00:00:00'),
(333,24,1,'64.02.14.1001','Muara Jawa Ilir','','0000-00-00 00:00:00'),
(334,24,1,'64.02.14.1002','Muara Jawa Tengah','','0000-00-00 00:00:00'),
(335,24,1,'64.02.14.1003','Muara Jawa Ulu','','0000-00-00 00:00:00'),
(336,24,1,'64.02.14.1004','Teluk Dalam','','0000-00-00 00:00:00'),
(337,24,1,'64.02.14.1005','Dondang','','0000-00-00 00:00:00'),
(338,24,1,'64.02.14.1006','Tama Pole','','0000-00-00 00:00:00'),
(339,24,1,'64.02.14.1007','Muara Kembang','','0000-00-00 00:00:00'),
(340,24,1,'64.02.14.1008','Muara Jawa','','0000-00-00 00:00:00'),
(341,25,1,'64.02.15.1001','Jawa','','0000-00-00 00:00:00'),
(342,25,1,'64.02.15.1002','Pendingin','','0000-00-00 00:00:00'),
(343,25,1,'64.02.15.1003','Sanga-Sanga Dalam','','0000-00-00 00:00:00'),
(344,25,1,'64.02.15.1004','Sari Jaya','','0000-00-00 00:00:00'),
(345,25,1,'64.02.15.1005','Sanga-Sanga Muara','','0000-00-00 00:00:00'),
(346,26,2,'64.02.16.2001','Manunggal Jaya','','0000-00-00 00:00:00'),
(347,26,2,'64.02.16.2002','Bukit Raya','','0000-00-00 00:00:00'),
(348,26,2,'64.02.16.2003','Embalut','','0000-00-00 00:00:00'),
(349,26,2,'64.02.16.2004','Bangun Rejo','','0000-00-00 00:00:00'),
(350,26,2,'64.02.16.2005','Kerta Buana','','0000-00-00 00:00:00'),
(351,26,2,'64.02.16.2006','Separi','','0000-00-00 00:00:00'),
(352,26,2,'64.02.16.2007','Bukit Pariaman','','0000-00-00 00:00:00'),
(353,26,2,'64.02.16.2008','Buana Jaya','','0000-00-00 00:00:00'),
(354,26,2,'64.02.16.2009','Mulawarman','','0000-00-00 00:00:00'),
(355,26,2,'64.02.16.2010','Loa Ulung','','0000-00-00 00:00:00'),
(356,26,2,'64.02.16.2011','Loa Raya','','0000-00-00 00:00:00'),
(357,26,2,'64.02.16.2012','Perjiwa','','0000-00-00 00:00:00'),
(358,26,2,'64.02.16.2013','Teluk Dalam','','0000-00-00 00:00:00'),
(359,26,2,'64.02.16.2014','Loa Lepu','','0000-00-00 00:00:00'),
(360,26,2,'64.02.16.2015','Suka Maju','','0000-00-00 00:00:00'),
(361,26,2,'64.02.16.2016','Loa Pari','','0000-00-00 00:00:00'),
(362,26,2,'64.02.16.2017','Karang Tunggal','','0000-00-00 00:00:00'),
(363,26,2,'64.02.16.2018','Tanjung Batu','','0000-00-00 00:00:00'),
(364,27,2,'64.02.17.2001','Sebuntal','','0000-00-00 00:00:00'),
(365,27,2,'64.02.17.2002','Santan Ulu','','0000-00-00 00:00:00'),
(366,27,2,'64.02.17.2003','Santan Tengah','','0000-00-00 00:00:00'),
(367,27,2,'64.02.17.2004','Santan Ilir','','0000-00-00 00:00:00'),
(368,27,2,'64.02.17.2005','Kersik','','0000-00-00 00:00:00'),
(369,27,2,'64.02.17.2006','Bunga Putih','','0000-00-00 00:00:00'),
(370,27,2,'64.02.17.2007','Makarti','','0000-00-00 00:00:00'),
(371,27,2,'64.02.17.2008','Perangat Selatan','','2015-04-24 15:46:32'),
(372,27,2,'64.02.17.2009','Perangat Baru','','2015-04-24 15:46:41'),
(373,27,2,'64.02.17.2010','Semangko','','0000-00-00 00:00:00'),
(374,27,2,'64.02.17.2011','Sambera Baru','','0000-00-00 00:00:00'),
(375,28,2,'64.02.18.2001','Muara Wis','','0000-00-00 00:00:00'),
(376,28,2,'64.02.18.2002','Sebemban','','0000-00-00 00:00:00'),
(377,28,2,'64.02.18.2003','Melintang','','0000-00-00 00:00:00'),
(378,28,2,'64.02.18.2004','Enggelam','','0000-00-00 00:00:00'),
(379,28,2,'64.02.18.2005','Lebaq Mantan','','0000-00-00 00:00:00'),
(380,28,2,'64.02.18.2006','Lebaq Cilong','','2015-04-24 15:49:32'),
(381,28,2,'64.02.18.2007','Muara Enggelam','','0000-00-00 00:00:00'),
(382,29,2,'64.03.01.2001','Merabu','','0000-00-00 00:00:00'),
(383,29,2,'64.03.01.2002','Panaan','','0000-00-00 00:00:00'),
(384,29,2,'64.03.01.2003','Merapun','','0000-00-00 00:00:00'),
(385,29,2,'64.03.01.2004','Muara Lesan','','0000-00-00 00:00:00'),
(386,29,2,'64.03.01.2005','Merasa','','0000-00-00 00:00:00'),
(387,29,2,'64.03.01.2006','Lesan Dayak','','0000-00-00 00:00:00'),
(388,29,2,'64.03.01.2007','Long Beliu','','0000-00-00 00:00:00'),
(389,29,2,'64.03.01.2008','Long Duhung','','0000-00-00 00:00:00'),
(390,29,2,'64.03.01.2009','Long Lamcin','','0000-00-00 00:00:00'),
(391,29,2,'64.03.01.2010','Long Keluh','','0000-00-00 00:00:00'),
(392,29,2,'64.03.01.2011','Long Pelay','','0000-00-00 00:00:00'),
(393,29,2,'64.03.01.2012','Mapulu','','0000-00-00 00:00:00'),
(394,29,2,'64.03.01.2013','Long Sului','','0000-00-00 00:00:00'),
(395,29,2,'64.03.01.2014','Sido Bangen','','0000-00-00 00:00:00'),
(396,30,2,'64.03.02.2002','Dumaring','','0000-00-00 00:00:00'),
(397,30,2,'64.03.02.2003','Talisayan','','0000-00-00 00:00:00'),
(398,30,2,'64.03.02.2014','Campur Sari','','0000-00-00 00:00:00'),
(399,30,2,'64.03.02.2015','Bumu Jaya','','0000-00-00 00:00:00'),
(400,30,2,'64.03.02.2016','Tunggal Bumi','','0000-00-00 00:00:00'),
(401,30,2,'64.03.02.2017','Sumber Mulya','','0000-00-00 00:00:00'),
(402,30,2,'64.03.02.2018','Suka Murya','','0000-00-00 00:00:00'),
(403,30,2,'64.03.02.2019','Purnasari Jaya','','0000-00-00 00:00:00'),
(404,30,2,'64.03.02.2021','Eka Sapta','','0000-00-00 00:00:00'),
(405,31,2,'64.03.03.2001','Long Lanuk','','0000-00-00 00:00:00'),
(406,31,2,'64.03.03.2002','Tumbit Dayak','','0000-00-00 00:00:00'),
(407,31,2,'64.03.03.2004','Inaran','','0000-00-00 00:00:00'),
(408,31,2,'64.03.03.2005','Pegat Bukur','','0000-00-00 00:00:00'),
(409,31,2,'64.03.03.2006','Rantau Panjang','','0000-00-00 00:00:00'),
(410,31,2,'64.03.03.2007','Suaran','','0000-00-00 00:00:00'),
(411,31,2,'64.03.03.2008','Pilanjau','','0000-00-00 00:00:00'),
(412,31,2,'64.03.03.2009','Pesayan/Mengkajang','','0000-00-00 00:00:00'),
(413,31,2,'64.03.03.2010','Sungai Bebanir','','0000-00-00 00:00:00'),
(414,31,2,'64.03.03.2011','Gurimbang','','0000-00-00 00:00:00'),
(415,31,2,'64.03.03.2012','Sukan Tengah','','0000-00-00 00:00:00'),
(416,31,1,'64.03.03.1013','Sambaliung','','0000-00-00 00:00:00'),
(417,31,2,'64.03.03.2014','Tanjung Perangat','','0000-00-00 00:00:00'),
(418,31,2,'64.03.03.2015','Bena Baru','','0000-00-00 00:00:00'),
(419,32,2,'64.03.04.2001','Laong La ai','','0000-00-00 00:00:00'),
(420,32,2,'64.03.04.2002','Punah Segah','','0000-00-00 00:00:00'),
(421,32,2,'64.03.04.2003','Long Ayap','','0000-00-00 00:00:00'),
(422,32,2,'64.03.04.2004','Long Ayan','','0000-00-00 00:00:00'),
(423,32,2,'64.03.04.2005','Punan Malinau','','0000-00-00 00:00:00'),
(424,32,2,'64.03.04.2006','Punan Mahakam','','0000-00-00 00:00:00'),
(425,32,2,'64.03.04.2007','Gunung Sari','','0000-00-00 00:00:00'),
(426,32,2,'64.03.04.2008','Pandan Sari','','0000-00-00 00:00:00'),
(427,32,2,'64.03.04.2009','Bukit Makmur','','0000-00-00 00:00:00'),
(428,32,2,'64.03.04.2010','Harapan Jaya','','0000-00-00 00:00:00'),
(429,32,2,'64.03.04.2011','Tepian Buah','','0000-00-00 00:00:00'),
(430,33,1,'64.03.05.1003','Sungai Bedungun','','0000-00-00 00:00:00'),
(431,33,1,'64.03.05.1004','Tanjung Redeb','','0000-00-00 00:00:00'),
(432,33,1,'64.03.05.1005','Bugis','','0000-00-00 00:00:00'),
(433,33,1,'64.03.05.1006','Gayam','','0000-00-00 00:00:00'),
(434,33,1,'64.03.05.1007','Karang Ambun','','0000-00-00 00:00:00'),
(435,33,1,'64.03.05.1008','Gunung Panjang','','0000-00-00 00:00:00'),
(436,34,2,'64.03.06.2001','Tasuk','','0000-00-00 00:00:00'),
(437,34,2,'64.03.06.2002','Birang','','0000-00-00 00:00:00'),
(438,34,1,'64.03.06.1003','Gunung Tabur','','0000-00-00 00:00:00'),
(439,34,2,'64.03.06.2004','Maluang','','0000-00-00 00:00:00'),
(440,34,2,'64.03.06.2005','Samburakat','','0000-00-00 00:00:00'),
(441,34,2,'64.03.06.2006','Sambakungan','','0000-00-00 00:00:00'),
(442,34,2,'64.03.06.2007','Merancang Ulu','','0000-00-00 00:00:00'),
(443,34,2,'64.03.06.2008','Merancang Ilir','','0000-00-00 00:00:00'),
(444,34,2,'64.03.06.2009','Pulau Besing','','0000-00-00 00:00:00'),
(445,34,2,'64.03.06.2010','Melati Jaya','','0000-00-00 00:00:00'),
(446,34,2,'64.03.06.2011','Batu-Batu','','0000-00-00 00:00:00'),
(447,35,2,'64.03.07.2001','Pegat Betumbuk','','0000-00-00 00:00:00'),
(448,35,2,'64.03.07.2002','Teluk Semanting','','0000-00-00 00:00:00'),
(449,35,2,'64.03.07.2003','Tanjung Batu','','0000-00-00 00:00:00'),
(450,35,2,'64.03.07.2004','Pulau Derawan','','0000-00-00 00:00:00'),
(451,35,2,'64.03.07.2009','Kasai','','0000-00-00 00:00:00'),
(452,36,2,'64.03.08.2001','Biduk-Biduk','','0000-00-00 00:00:00'),
(453,36,2,'64.03.08.2002','Pantai Harapan','','0000-00-00 00:00:00'),
(454,36,2,'64.03.08.2003','Tanjung Prepat','','0000-00-00 00:00:00'),
(455,36,2,'64.03.08.2004','Teluk Sumbang','','0000-00-00 00:00:00'),
(456,36,2,'64.03.08.2008','Teluk Sulaiman','','0000-00-00 00:00:00'),
(457,36,2,'64.03.08.2009','Giring-Giring','','0000-00-00 00:00:00'),
(458,37,2,'64.03.09.2001','Tumbit Melayu','','0000-00-00 00:00:00'),
(459,37,1,'64.03.09.1002','Teluk Bayur','','0000-00-00 00:00:00'),
(460,37,1,'64.03.09.1003','Rinding','','0000-00-00 00:00:00'),
(461,37,2,'64.03.09.2004','Labanan Jaya','','0000-00-00 00:00:00'),
(462,37,2,'64.03.09.2005','Labanan Makmur','','0000-00-00 00:00:00'),
(463,37,2,'64.03.09.2006','Labanan Makarti','','0000-00-00 00:00:00'),
(464,38,2,'64.03.10.2002','Tabalar Muara','','0000-00-00 00:00:00'),
(465,38,2,'64.03.10.2003','Bambangan/Tubaan','','0000-00-00 00:00:00'),
(466,38,2,'64.03.10.2004','Tabalar Ulu','','0000-00-00 00:00:00'),
(467,38,2,'64.03.10.2005','Semurut','','0000-00-00 00:00:00'),
(468,38,2,'64.03.10.2006','Buyung Buyung','','0000-00-00 00:00:00'),
(469,38,2,'64.03.10.2007','Harapan Maju','','0000-00-00 00:00:00'),
(470,39,2,'64.03.11.2001','Maratua Bohesillan','','0000-00-00 00:00:00'),
(471,39,2,'64.03.11.2002','Maratua Payung Payung','','0000-00-00 00:00:00'),
(472,39,2,'64.03.11.2003','Maratua Teluk Alulu','','0000-00-00 00:00:00'),
(473,39,2,'64.03.11.2004','Maratua Teluk Harapan','','0000-00-00 00:00:00'),
(474,40,2,'64.03.12.2001','Tembudan','','0000-00-00 00:00:00'),
(475,40,2,'64.03.12.2002','Kayu Indah','','0000-00-00 00:00:00'),
(476,40,2,'64.03.12.2003','Batu Putih','','0000-00-00 00:00:00'),
(477,40,2,'64.03.12.2004','Lobang Kelatak','','0000-00-00 00:00:00'),
(478,40,2,'64.03.12.2005','Ampen Medang','','0000-00-00 00:00:00'),
(479,40,2,'64.03.12.2006','Balikukup','','0000-00-00 00:00:00'),
(480,40,2,'64.03.12.2007','Sumber Agung','','0000-00-00 00:00:00'),
(481,41,2,'64.03.13.2001','Biatan Ulu','','0000-00-00 00:00:00'),
(482,41,2,'64.03.13.2002','Biatan Ilir','','0000-00-00 00:00:00'),
(483,41,2,'64.03.13.2003','Karangan','','0000-00-00 00:00:00'),
(484,41,2,'64.03.13.2004','Biatan Lempake','','0000-00-00 00:00:00'),
(485,41,2,'64.03.13.2005','Manunggal Jaya','','0000-00-00 00:00:00'),
(486,41,2,'64.03.13.2006','Biantan Bapinang','','0000-00-00 00:00:00'),
(487,41,2,'64.03.13.2007','Biatan Baru','','0000-00-00 00:00:00'),
(488,41,2,'64.03.13.2008','Bukit Makmur Jaya','','0000-00-00 00:00:00'),
(489,42,2,'64.07.05.2008','Muara Leban','','0000-00-00 00:00:00'),
(490,42,2,'64.07.05.2009','Long Iram Seberang','','0000-00-00 00:00:00'),
(491,42,2,'64.07.05.2010','Long Iram Ilir','','0000-00-00 00:00:00'),
(492,42,2,'64.07.05.2011','Anah','','0000-00-00 00:00:00'),
(493,42,2,'64.07.05.2015','Long Daliq','','0000-00-00 00:00:00'),
(494,42,2,'64.07.05.2016','Long Iram Kota','','0000-00-00 00:00:00'),
(495,42,2,'64.07.05.2017','Long Iram Bayan','','0000-00-00 00:00:00'),
(496,42,2,'64.07.05.2018','Keliwai','','0000-00-00 00:00:00'),
(497,42,2,'64.07.05.2019','Ujoh Halang','','0000-00-00 00:00:00'),
(498,42,2,'64.07.05.2021','Kelian Luar','','0000-00-00 00:00:00'),
(499,42,2,'64.07.05.2022','Sukomulyo','','0000-00-00 00:00:00'),
(500,43,2,'64.07.06.2001','Empas','','0000-00-00 00:00:00'),
(501,43,2,'64.07.06.2002','Empakuq','','0000-00-00 00:00:00'),
(502,43,2,'64.07.06.2003','Muara Bunyut','','0000-00-00 00:00:00'),
(503,43,1,'64.07.06.1006','Melak Ilir','','0000-00-00 00:00:00'),
(504,43,1,'64.07.06.1011','Melak Ulu','','0000-00-00 00:00:00'),
(505,43,2,'64.07.06.2012','Muara Benangaq','','0000-00-00 00:00:00'),
(506,44,1,'64.07.07.1001','Barong Tongkok','','0000-00-00 00:00:00'),
(507,44,2,'64.07.07.2002','Mencimai','','0000-00-00 00:00:00'),
(508,44,2,'64.07.07.2003','Engkuni Pasek','','0000-00-00 00:00:00'),
(509,44,2,'64.07.07.2004','Pepas Eheng','','0000-00-00 00:00:00'),
(510,44,2,'64.07.07.2006','Juhan Asa','','0000-00-00 00:00:00'),
(511,44,2,'64.07.07.2007','Asa','','0000-00-00 00:00:00'),
(512,44,2,'64.07.07.2008','Pepes Asa','','0000-00-00 00:00:00'),
(513,44,2,'64.07.07.2009','Muara Asa','','0000-00-00 00:00:00'),
(514,44,2,'64.07.07.2010','Ongko Asa','','0000-00-00 00:00:00'),
(515,44,2,'64.07.07.2011','Juaq Asa','','0000-00-00 00:00:00'),
(516,44,2,'64.07.07.2012','Ombau Asa','','0000-00-00 00:00:00'),
(517,44,2,'64.07.07.2013','Ngenyan Asa','','0000-00-00 00:00:00'),
(518,44,2,'64.07.07.2014','Gemuhan Asa','','0000-00-00 00:00:00'),
(519,44,2,'64.07.07.2015','Geleo Baru','','0000-00-00 00:00:00'),
(520,44,2,'64.07.07.2016','Geleo Asa','','0000-00-00 00:00:00'),
(521,44,2,'64.07.07.2017','Rejo Basuki','','0000-00-00 00:00:00'),
(522,44,2,'64.07.07.2018','Sumber Sari','','0000-00-00 00:00:00'),
(523,44,2,'64.07.07.2019','Sendawar','','0000-00-00 00:00:00'),
(524,44,2,'64.07.07.2020','Balok Asa','','0000-00-00 00:00:00'),
(525,44,1,'64.07.07.1021','Simpang Raya','','0000-00-00 00:00:00'),
(526,44,2,'64.07.07.2022','Belempung Ulaq','','0000-00-00 00:00:00'),
(527,45,2,'64.07.08.2001','Besiq','','0000-00-00 00:00:00'),
(528,45,2,'64.07.08.2002','Bermai','','0000-00-00 00:00:00'),
(529,45,2,'64.07.08.2003','Muara Niliq','','0000-00-00 00:00:00'),
(530,45,2,'64.07.08.2004','Mantar','','0000-00-00 00:00:00'),
(531,45,2,'64.07.08.2005','Muara Bomboy','','0000-00-00 00:00:00'),
(532,45,2,'64.07.08.2006','Damai Seberang','','0000-00-00 00:00:00'),
(533,45,2,'64.07.08.2007','Mendika','','0000-00-00 00:00:00'),
(534,45,2,'64.07.08.2008','Damai Kota','','0000-00-00 00:00:00'),
(535,45,2,'64.07.08.2009','Lumpat Dahuq','','0000-00-00 00:00:00'),
(536,45,2,'64.07.08.2010','Muara Tokong','','0000-00-00 00:00:00'),
(537,45,2,'64.07.08.2011','Jengan Danum','','0000-00-00 00:00:00'),
(538,45,2,'64.07.08.2012','Keay','','0000-00-00 00:00:00'),
(539,45,2,'64.07.08.2013','Tapulang','','0000-00-00 00:00:00'),
(540,45,2,'64.07.08.2014','Benung','','0000-00-00 00:00:00'),
(541,45,2,'64.07.08.2020','Muara Nyahing','','0000-00-00 00:00:00'),
(542,45,2,'64.07.08.2021','Sempatn','','0000-00-00 00:00:00'),
(543,45,2,'64.07.08.2022','Kelian','','0000-00-00 00:00:00'),
(544,46,2,'64.07.09.2001','Muara Begai','','0000-00-00 00:00:00'),
(545,46,2,'64.07.09.2002','Lotaq','','0000-00-00 00:00:00'),
(546,46,2,'64.07.09.2003','Payang','','0000-00-00 00:00:00'),
(547,46,2,'64.07.09.2004','Dingin','','0000-00-00 00:00:00'),
(548,46,2,'64.07.09.2005','Muara Lawa','','0000-00-00 00:00:00'),
(549,46,2,'64.07.09.2006','Lambing','','0000-00-00 00:00:00'),
(550,46,2,'64.07.09.2007','Benggeris','','0000-00-00 00:00:00'),
(551,46,2,'64.07.09.2008','Cempedes','','0000-00-00 00:00:00'),
(552,47,2,'64.07.10.2015','Jerang Dayak','','0000-00-00 00:00:00'),
(553,47,2,'64.07.10.2016','Mendung','','0000-00-00 00:00:00'),
(554,47,2,'64.07.10.2017','Jerang Melayu','','0000-00-00 00:00:00'),
(555,47,2,'64.07.10.2018','Dasaq','','0000-00-00 00:00:00'),
(556,47,2,'64.07.10.2020','Tepian Ulaq','','0000-00-00 00:00:00'),
(557,47,2,'64.07.10.2021','Sebelang','','0000-00-00 00:00:00'),
(558,47,2,'64.07.10.2022','Muara Baroh','','0000-00-00 00:00:00'),
(559,47,2,'64.07.10.2023','Teluk Tempudau','','0000-00-00 00:00:00'),
(560,47,2,'64.07.10.2024','Tanjung Laong','','0000-00-00 00:00:00'),
(561,47,2,'64.07.10.2025','Tanjung Pagar','','0000-00-00 00:00:00'),
(562,47,2,'64.07.10.2029','Gunung Bayan','','0000-00-00 00:00:00'),
(563,47,2,'64.07.10.2030','Muara Beloan','','0000-00-00 00:00:00'),
(564,48,2,'64.07.11.2001','Pentat','','0000-00-00 00:00:00'),
(565,48,2,'64.07.11.2002','Lembonah','','0000-00-00 00:00:00'),
(566,48,2,'64.07.11.2003','Muara Nayan','','0000-00-00 00:00:00'),
(567,48,2,'64.07.11.2004','Mancong','','0000-00-00 00:00:00'),
(568,48,2,'64.07.11.2005','Perigiq','','0000-00-00 00:00:00'),
(569,48,2,'64.07.11.2006','Muara Ohong','','0000-00-00 00:00:00'),
(570,48,2,'64.07.11.2007','Tanjung Jone','','0000-00-00 00:00:00'),
(571,48,2,'64.07.11.2008','Tanjung Isuy','','0000-00-00 00:00:00'),
(572,48,2,'64.07.11.2009','Tanjung Jan','','0000-00-00 00:00:00'),
(573,48,2,'64.07.11.2010','Pulau Lanting','','0000-00-00 00:00:00'),
(574,48,2,'64.07.11.2011','Muara Tae','','0000-00-00 00:00:00'),
(575,48,2,'64.07.11.2012','Bekokong Makmur','','0000-00-00 00:00:00'),
(576,49,2,'64.07.12.2001','Gerungung','','0000-00-00 00:00:00'),
(577,49,2,'64.07.12.2002','Pereng Taliq','','0000-00-00 00:00:00'),
(578,49,2,'64.07.12.2003','Tanjung Soke','','0000-00-00 00:00:00'),
(579,49,2,'64.07.12.2004','Deraya','','0000-00-00 00:00:00'),
(580,49,2,'64.07.12.2005','Lemper','','0000-00-00 00:00:00'),
(581,49,2,'64.07.12.2006','Muara Siram','','0000-00-00 00:00:00'),
(582,49,2,'64.07.12.2007','Resak','','0000-00-00 00:00:00'),
(583,49,2,'64.07.12.2008','Jambuk','','0000-00-00 00:00:00'),
(584,49,2,'64.07.12.2009','Muara Gusik','','0000-00-00 00:00:00'),
(585,49,2,'64.07.12.2010','Penawai','','0000-00-00 00:00:00'),
(586,49,2,'64.07.12.2011','Muara Kedang','','0000-00-00 00:00:00'),
(587,49,2,'64.07.12.2012','Jambuk Makmur','','0000-00-00 00:00:00'),
(588,49,2,'64.07.12.2014','Siram Makmur','','0000-00-00 00:00:00'),
(589,49,2,'64.07.12.2015','Siram Jaya','','0000-00-00 00:00:00'),
(590,49,2,'64.07.12.2016','Bukit Harapan','','0000-00-00 00:00:00'),
(591,49,2,'64.07.12.2019','Tanjung Sari','','0000-00-00 00:00:00'),
(592,50,2,'64.07.13.2001','Loa Deras','','0000-00-00 00:00:00'),
(593,50,2,'64.07.13.2002','Minta','','0000-00-00 00:00:00'),
(594,50,2,'64.07.13.2003','Tanjung Haur','','0000-00-00 00:00:00'),
(595,50,2,'64.07.13.2004','Penyinggahan Ilir','','0000-00-00 00:00:00'),
(596,50,2,'64.07.13.2005','Penyinggahan Ulu','','0000-00-00 00:00:00'),
(597,50,2,'64.07.13.2006','Bakung','','0000-00-00 00:00:00'),
(598,51,2,'64.07.14.2001','Randa Empas','','0000-00-00 00:00:00'),
(599,51,2,'64.07.14.2002','Tukuq','','0000-00-00 00:00:00'),
(600,51,2,'64.07.14.2003','Tende','','0000-00-00 00:00:00'),
(601,51,2,'64.07.14.2004','Sambung','','0000-00-00 00:00:00'),
(602,51,2,'64.07.14.2005','Anan Jaya','','0000-00-00 00:00:00'),
(603,51,2,'64.07.14.2006','Jelmu Sibak','','0000-00-00 00:00:00'),
(604,51,2,'64.07.14.2007','Suakong','','0000-00-00 00:00:00'),
(605,51,2,'64.07.14.2008','Penarung','','0000-00-00 00:00:00'),
(606,51,2,'64.07.14.2009','Dilang Puti','','0000-00-00 00:00:00'),
(607,52,2,'64.07.15.2001','Melapeh Baru','','0000-00-00 00:00:00'),
(608,52,2,'64.07.15.2002','Bigung Baru','','0000-00-00 00:00:00'),
(609,52,2,'64.07.15.2003','Tutung','','0000-00-00 00:00:00'),
(610,52,2,'64.07.15.2004','Linggang Melapeh','','0000-00-00 00:00:00'),
(611,52,2,'64.07.15.2005','Linggang Mapan','','0000-00-00 00:00:00'),
(612,52,2,'64.07.15.2006','Linggang Bigung','','0000-00-00 00:00:00'),
(613,52,2,'64.07.15.2007','Linggang Amer','','0000-00-00 00:00:00'),
(614,52,2,'64.07.15.2008','Bangun Sari','','0000-00-00 00:00:00'),
(615,52,2,'64.07.15.2009','Purwodadi','','0000-00-00 00:00:00'),
(616,52,2,'64.07.15.2010','Linggang Mencelew','','0000-00-00 00:00:00'),
(617,52,2,'64.07.15.2011','Linggang Kebut','','0000-00-00 00:00:00'),
(618,53,2,'64.07.16.2001','Dempar','','0000-00-00 00:00:00'),
(619,53,2,'64.07.16.2002','Temula','','0000-00-00 00:00:00'),
(620,53,2,'64.07.16.2003','Jontai','','0000-00-00 00:00:00'),
(621,53,2,'64.07.16.2004','Sembuan','','0000-00-00 00:00:00'),
(622,53,2,'64.07.16.2005','Intu Lingau','','0000-00-00 00:00:00'),
(623,53,2,'64.07.16.2006','Mu\'ut','','0000-00-00 00:00:00'),
(624,53,2,'64.07.16.2007','Terajuk','','0000-00-00 00:00:00'),
(625,53,2,'64.07.16.2008','Lakan Bilem','','0000-00-00 00:00:00'),
(626,53,2,'64.07.16.2009','Sentalar','','0000-00-00 00:00:00'),
(627,53,2,'64.07.16.2010','Awai','','0000-00-00 00:00:00'),
(628,54,2,'64.07.17.2001','Tebisaq','','0000-00-00 00:00:00'),
(629,54,2,'64.07.17.2002','Kaliq','','0000-00-00 00:00:00'),
(630,54,2,'64.07.17.2003','Tanah Mea','','0000-00-00 00:00:00'),
(631,54,2,'64.07.17.2004','Sang-Sang','','0000-00-00 00:00:00'),
(632,54,2,'64.07.17.2005','Muhur','','0000-00-00 00:00:00'),
(633,54,2,'64.07.17.2006','Muara Kelawit','','0000-00-00 00:00:00'),
(634,54,2,'64.07.17.2007','Bentas','','0000-00-00 00:00:00'),
(635,54,2,'64.07.17.2008','Betung','','0000-00-00 00:00:00'),
(636,54,2,'64.07.17.2009','Kiaq','','0000-00-00 00:00:00'),
(637,54,2,'64.07.17.2010','Tendiq','','0000-00-00 00:00:00'),
(638,54,2,'64.07.17.2011','Penawang','','0000-00-00 00:00:00'),
(639,54,2,'64.07.17.2012','Lendian Liang Nayuq','','0000-00-00 00:00:00'),
(640,54,2,'64.07.17.2013','Kenyanyan','','0000-00-00 00:00:00'),
(641,54,2,'64.07.17.2014','Rikong','','0000-00-00 00:00:00'),
(642,54,2,'64.07.17.2015','Kendisiq','','0000-00-00 00:00:00'),
(643,54,2,'64.07.17.2016','Muara Ponaq','','0000-00-00 00:00:00'),
(644,55,2,'64.07.18.2001','Sakaq Lotoq','','0000-00-00 00:00:00'),
(645,55,2,'64.07.18.2002','Sakaq Tada','','0000-00-00 00:00:00'),
(646,55,2,'64.07.18.2003','Gemuruh','','0000-00-00 00:00:00'),
(647,55,2,'64.07.18.2004','Karangan','','0000-00-00 00:00:00'),
(648,55,2,'64.07.18.2005','Merayaq','','0000-00-00 00:00:00'),
(649,55,2,'64.07.18.2006','Linggang Marimun','','0000-00-00 00:00:00'),
(650,55,2,'64.07.18.2007','Kelumpang','','0000-00-00 00:00:00'),
(651,55,2,'64.07.18.2008','Gadur','','0000-00-00 00:00:00'),
(652,55,2,'64.07.18.2009','Muara Batuq','','0000-00-00 00:00:00'),
(653,55,2,'64.07.18.2010','Muara Jawaq','','0000-00-00 00:00:00'),
(654,55,2,'64.07.18.2011','Abit','','0000-00-00 00:00:00'),
(655,55,2,'64.07.18.2012','Rembayan','','0000-00-00 00:00:00'),
(656,55,2,'64.07.18.2014','Gunung Rampah','','0000-00-00 00:00:00'),
(657,55,2,'64.07.18.2015','Jengan','','0000-00-00 00:00:00'),
(658,55,2,'64.07.18.2016','Muara Kalaq','','0000-00-00 00:00:00'),
(659,55,2,'64.07.18.2017','Tondoh','','0000-00-00 00:00:00'),
(660,56,2,'64.07.19.2001','Tering Baru','','0000-00-00 00:00:00'),
(661,56,2,'64.07.19.2002','Tering Lama','','0000-00-00 00:00:00'),
(662,56,2,'64.07.19.2003','Tering Seberang','','0000-00-00 00:00:00'),
(663,56,2,'64.07.19.2004','Tukul','','0000-00-00 00:00:00'),
(664,56,2,'64.07.19.2005','Kelubaq','','0000-00-00 00:00:00'),
(665,56,2,'64.07.19.2006','Purworejo','','0000-00-00 00:00:00'),
(666,56,2,'64.07.19.2007','Jelemuq','','0000-00-00 00:00:00'),
(667,56,2,'64.07.19.2008','Kelian Dalam','','0000-00-00 00:00:00'),
(668,56,2,'64.07.19.2009','Muara Mujan','','0000-00-00 00:00:00'),
(669,56,2,'64.07.19.2010','Muyub Ulu','','0000-00-00 00:00:00'),
(670,56,2,'64.07.19.2011','Muyub Aket','','0000-00-00 00:00:00'),
(671,56,2,'64.07.19.2013','Muyub Ilir','','0000-00-00 00:00:00'),
(672,56,2,'64.07.19.2014','Gabung Baru','','0000-00-00 00:00:00'),
(673,56,2,'64.07.19.2015','Banjarejo','','0000-00-00 00:00:00'),
(674,56,2,'64.07.19.2016','Tering Lama Ulu','','0000-00-00 00:00:00'),
(675,57,2,'64.07.20.2001','Sekolaq Joleq','','0000-00-00 00:00:00'),
(676,57,2,'64.07.20.2002','Sekolaq Muliaq','','0000-00-00 00:00:00'),
(677,57,2,'64.07.20.2003','Sekolaq Oday','','0000-00-00 00:00:00'),
(678,57,2,'64.07.20.2004','Srimulyo','','0000-00-00 00:00:00'),
(679,57,2,'64.07.20.2005','Sember Bangun','','0000-00-00 00:00:00'),
(680,57,2,'64.07.20.2006','Sumber Rejo','','0000-00-00 00:00:00'),
(681,57,2,'64.07.20.2007','Sekolaq Darat','','0000-00-00 00:00:00'),
(682,57,2,'64.07.20.2008','Leleng','','0000-00-00 00:00:00'),
(683,58,2,'64.08.01.2001','Senyiur','','0000-00-00 00:00:00'),
(684,58,2,'64.08.01.2002','Kelinjau Ilir','','0000-00-00 00:00:00'),
(685,58,2,'64.08.01.2003','Kelinjau Ulu','','0000-00-00 00:00:00'),
(686,58,2,'64.08.01.2004','Long Nah','','0000-00-00 00:00:00'),
(687,58,2,'64.08.01.2006','Long Tesak','','0000-00-00 00:00:00'),
(688,58,2,'64.08.01.2009','Gemar Baru','','0000-00-00 00:00:00'),
(689,58,2,'64.08.01.2012','Long Faq Baru','','0000-00-00 00:00:00'),
(690,58,2,'64.08.01.2013','Muara Dun','','0000-00-00 00:00:00'),
(691,59,2,'64.08.02.2001','Juk Luay','','0000-00-00 00:00:00'),
(692,59,2,'64.08.02.2002','Nehes Liah Bing','','0000-00-00 00:00:00'),
(693,59,2,'64.08.02.2003','Muara Wahau','','0000-00-00 00:00:00'),
(694,59,2,'64.08.02.2004','Dabeq','','0000-00-00 00:00:00'),
(695,59,2,'64.08.02.2005','Diaq Lay','','0000-00-00 00:00:00'),
(696,59,2,'64.08.02.2006','Benhes','','0000-00-00 00:00:00'),
(697,59,2,'64.08.02.2007','Wanasari','','0000-00-00 00:00:00'),
(698,59,2,'64.08.02.2008','Wahau Baru','','0000-00-00 00:00:00'),
(699,59,2,'64.08.02.2009','Karya Bhakti','','0000-00-00 00:00:00'),
(700,59,2,'64.08.02.2010','Long Wehea','','0000-00-00 00:00:00'),
(701,60,2,'64.08.03.2001','Senambah','','0000-00-00 00:00:00'),
(702,60,2,'64.08.03.2002','Ngayau','','0000-00-00 00:00:00'),
(703,60,2,'64.08.03.2003','Muara Bengkal Ilir','','0000-00-00 00:00:00'),
(704,60,2,'64.08.03.2004','Muara Bengkal Ulu','','0000-00-00 00:00:00'),
(705,60,2,'64.08.03.2005','Benua Baru','','0000-00-00 00:00:00'),
(706,60,2,'64.08.03.2013','Mulupan','','0000-00-00 00:00:00'),
(707,60,2,'64.08.03.2014','Batu Balai','','0000-00-00 00:00:00'),
(708,61,2,'64.08.04.2001','Sangatta Utara','','0000-00-00 00:00:00'),
(709,61,1,'64.08.04.1010','Teluk Lingga','','0000-00-00 00:00:00'),
(710,61,2,'64.08.04.2011','Singa Gembara','','0000-00-00 00:00:00'),
(711,61,2,'64.08.04.2012','Swarga Bara','','0000-00-00 00:00:00'),
(712,62,2,'64.08.05.2001','Kerayaan','','0000-00-00 00:00:00'),
(713,62,2,'64.08.05.2002','Tanjung Manis','','0000-00-00 00:00:00'),
(714,62,2,'64.08.05.2003','Peridan','','0000-00-00 00:00:00'),
(715,62,2,'64.08.05.2004','Saqa','','0000-00-00 00:00:00'),
(716,62,2,'64.08.05.2005','Mandu Dalam','','0000-00-00 00:00:00'),
(717,62,2,'64.08.05.2006','Banua Baru','','0000-00-00 00:00:00'),
(718,62,2,'64.08.05.2011','Sempayau','','0000-00-00 00:00:00'),
(719,62,2,'64.08.05.2012','Pelawan','','0000-00-00 00:00:00'),
(720,62,2,'64.08.05.2013','Tepian Tarap','','0000-00-00 00:00:00'),
(721,62,2,'64.08.05.2015','Maloy','','0000-00-00 00:00:00'),
(722,62,2,'64.08.05.2016','Benua Baru Ulu','','0000-00-00 00:00:00'),
(723,62,2,'64.08.05.2017','Kolek','','0000-00-00 00:00:00'),
(724,62,2,'64.08.05.2018','Pulau Miang','','0000-00-00 00:00:00'),
(725,62,2,'64.08.05.2019','Perupuk','','0000-00-00 00:00:00'),
(726,62,2,'64.08.05.2020','Mandau Pantai','','0000-00-00 00:00:00'),
(727,63,2,'64.08.06.2001','Long Betuq','','0000-00-00 00:00:00'),
(728,63,2,'64.08.06.2002','Long Pejeng','','0000-00-00 00:00:00'),
(729,63,2,'64.08.06.2003','Long Lees','','0000-00-00 00:00:00'),
(730,63,2,'64.08.06.2004','Mekar Baru','','0000-00-00 00:00:00'),
(731,63,2,'64.08.06.2005','Rantau Sentosa','','0000-00-00 00:00:00'),
(732,63,2,'64.08.06.2006','Long Nyelong','','0000-00-00 00:00:00'),
(733,64,2,'64.08.07.2001','Marah Haloq','','0000-00-00 00:00:00'),
(734,64,2,'64.08.07.2002','Long Melah','','0000-00-00 00:00:00'),
(735,64,2,'64.08.07.2003','Juk Ayaq','','0000-00-00 00:00:00'),
(736,64,2,'64.08.07.2004','Long Segar','','0000-00-00 00:00:00'),
(737,64,2,'64.08.07.2005','Long Noran','','0000-00-00 00:00:00'),
(738,64,2,'64.08.07.2006','Muara Pantun','','0000-00-00 00:00:00'),
(739,64,2,'64.08.07.2007','Rantau Panjang','','0000-00-00 00:00:00'),
(740,65,2,'64.08.08.2001','Makmur Jaya','','0000-00-00 00:00:00'),
(741,65,2,'64.08.08.2002','Marga Mulya','','0000-00-00 00:00:00'),
(742,65,2,'64.08.08.2003','Sukamaju','','0000-00-00 00:00:00'),
(743,65,2,'64.08.08.2004','Sidomulyo','','0000-00-00 00:00:00'),
(744,65,2,'64.08.08.2005','Sri Pantun','','0000-00-00 00:00:00'),
(745,65,2,'64.08.08.2006','Kombeng Indah','','0000-00-00 00:00:00'),
(746,65,2,'64.08.08.2007','Miau Baru','','0000-00-00 00:00:00'),
(747,66,2,'64.08.09.2001','Sepaso','','0000-00-00 00:00:00'),
(748,66,2,'64.08.09.2002','Sekerat','','0000-00-00 00:00:00'),
(749,66,2,'64.08.09.2003','Keraitan','','0000-00-00 00:00:00'),
(750,66,2,'64.08.09.2004','Tepian Langsat','','0000-00-00 00:00:00'),
(751,66,2,'64.08.09.2005','Tebagan Lembak','','0000-00-00 00:00:00'),
(752,66,2,'64.08.09.2006','Sepaso Timur','','0000-00-00 00:00:00'),
(753,66,2,'64.08.09.2007','Sepaso Selatan','','0000-00-00 00:00:00'),
(754,66,2,'64.08.09.2008','Muara Bengalon','','0000-00-00 00:00:00'),
(755,66,2,'64.08.09.2009','Tepian Baru','','0000-00-00 00:00:00'),
(756,66,2,'64.08.09.2010','Tepian Indah','','0000-00-00 00:00:00'),
(757,66,2,'64.08.09.2011','Sepaso Barat','','0000-00-00 00:00:00'),
(758,67,2,'64.08.10.2001','Kaliorang','','0000-00-00 00:00:00'),
(759,67,2,'64.08.10.2006','Bukit Makmur','','0000-00-00 00:00:00'),
(760,67,2,'64.08.10.2007','Bukit Harapan','','0000-00-00 00:00:00'),
(761,67,2,'64.08.10.2008','Citra Manunggal','','0000-00-00 00:00:00'),
(762,67,2,'64.08.10.2009','Bangun Jaya','','0000-00-00 00:00:00'),
(763,67,2,'64.08.10.2010','Bumi Sejahtera','','0000-00-00 00:00:00'),
(764,67,2,'64.08.10.2013','Selangkau','','0000-00-00 00:00:00'),
(765,68,2,'64.08.11.2001','Sandaran','','0000-00-00 00:00:00'),
(766,68,2,'64.08.11.2002','Manubar','','0000-00-00 00:00:00'),
(767,68,2,'64.08.11.2003','Tadoan','','0000-00-00 00:00:00'),
(768,68,2,'64.08.11.2004','Marukangan','','0000-00-00 00:00:00'),
(769,68,2,'64.08.11.2005','Susuk Luar','','0000-00-00 00:00:00'),
(770,68,2,'64.08.11.2006','Susuk Dalam','','0000-00-00 00:00:00'),
(771,68,2,'64.08.11.2007','Tanjung Mangkalihat','','0000-00-00 00:00:00'),
(772,69,2,'64.08.12.2001','Sangatta Selatan','','0000-00-00 00:00:00'),
(773,69,2,'64.08.12.2002','Singa Geweh','','0000-00-00 00:00:00'),
(774,69,2,'64.08.12.2003','Sangkima','','0000-00-00 00:00:00'),
(775,69,2,'64.08.12.2004','Teluk Singkama','','0000-00-00 00:00:00'),
(776,70,2,'64.08.13.2001','Teluk Pandan','','0000-00-00 00:00:00'),
(777,70,2,'64.08.13.2002','Suka Rahmat','','0000-00-00 00:00:00'),
(778,70,2,'64.08.13.2003','Suka Damai','','0000-00-00 00:00:00'),
(779,70,2,'64.08.13.2004','Kandolo','','0000-00-00 00:00:00'),
(780,70,2,'64.08.13.2005','Danau Redan','','0000-00-00 00:00:00'),
(781,70,2,'64.08.13.2006','Martadinata','','0000-00-00 00:00:00'),
(782,71,2,'64.08.14.2001','Mukti Jaya','','0000-00-00 00:00:00'),
(783,71,2,'64.08.14.2002','Pulung Sari','','0000-00-00 00:00:00'),
(784,71,2,'64.08.14.2003','Margo Mulyo','','0000-00-00 00:00:00'),
(785,71,2,'64.08.14.2004','Rantau Makmur','','0000-00-00 00:00:00'),
(786,71,2,'64.08.14.2005','Manunggal Jaya','','0000-00-00 00:00:00'),
(787,71,2,'64.08.14.2006','Tanjung Labu','','0000-00-00 00:00:00'),
(788,71,2,'64.08.14.2007','Kebon Agung','','0000-00-00 00:00:00'),
(789,71,2,'64.08.14.2008','Tepian Makmur','','0000-00-00 00:00:00'),
(790,72,2,'64.08.15.2001','Bumi Etam','','0000-00-00 00:00:00'),
(791,72,2,'64.08.15.2002','Bumi Rapak','','0000-00-00 00:00:00'),
(792,72,2,'64.08.15.2003','Bumi Jaya','','0000-00-00 00:00:00'),
(793,72,2,'64.08.15.2004','Cipta Graha','','0000-00-00 00:00:00'),
(794,72,2,'64.08.15.2005','Kadungan Jaya','','0000-00-00 00:00:00'),
(795,72,2,'64.08.15.2006','Pengadan Baru','','0000-00-00 00:00:00'),
(796,72,2,'64.08.15.2007','Mata Air','','0000-00-00 00:00:00'),
(797,72,2,'64.08.15.2008','Bukit Permata','','0000-00-00 00:00:00'),
(798,73,2,'64.08.16.2001','Karangan Dalam','','0000-00-00 00:00:00'),
(799,73,2,'64.08.16.2002','Batu Lepoq','','0000-00-00 00:00:00'),
(800,73,2,'64.08.16.2003','Pengadan','','0000-00-00 00:00:00'),
(801,73,2,'64.08.16.2004','Ba\'ay','','0000-00-00 00:00:00'),
(802,73,2,'64.08.16.2005','Mukti Lestari','','0000-00-00 00:00:00'),
(803,73,2,'64.08.16.2006','Karangan Seberang','','0000-00-00 00:00:00'),
(804,73,2,'64.08.16.2007','Karangan Hilir','','0000-00-00 00:00:00'),
(805,74,2,'64.08.17.2001','Batu Timbau','','0000-00-00 00:00:00'),
(806,74,2,'64.08.17.2002','Beno Harapan','','0000-00-00 00:00:00'),
(807,74,2,'64.08.17.2003','Mugi Rahayu','','0000-00-00 00:00:00'),
(808,74,2,'64.08.17.2004','Mawai Indah','','0000-00-00 00:00:00'),
(809,74,2,'64.08.17.2005','Himba Lestari','','0000-00-00 00:00:00'),
(810,74,2,'64.08.17.2006','Telaga','','0000-00-00 00:00:00'),
(811,75,2,'64.08.18.2001','Sika Makmur','','0000-00-00 00:00:00'),
(812,75,2,'64.08.18.2002','Segoi Makmur','','0000-00-00 00:00:00'),
(813,75,2,'64.08.18.2003','Mukti Utama','','0000-00-00 00:00:00'),
(814,75,2,'64.08.18.2004','Sumber Sari','','0000-00-00 00:00:00'),
(815,75,2,'64.08.18.2005','Melan','','0000-00-00 00:00:00'),
(816,75,2,'64.08.18.2006','Tanah Abang','','0000-00-00 00:00:00'),
(817,75,2,'64.08.18.2007','Sumber Agung','','0000-00-00 00:00:00'),
(818,76,1,'64.09.01.1001','Tanjung Tengah','','0000-00-00 00:00:00'),
(819,76,1,'64.09.01.1002','Salo Loang','','0000-00-00 00:00:00'),
(820,76,1,'64.09.01.1003','Petung','','0000-00-00 00:00:00'),
(821,76,1,'64.09.01.1004','Lawe-lawe','','0000-00-00 00:00:00'),
(822,76,1,'64.09.01.1005','Pejala','','0000-00-00 00:00:00'),
(823,76,1,'64.09.01.1006','Kampung Baru','','0000-00-00 00:00:00'),
(824,76,1,'64.09.01.1007','Sesumpu','','0000-00-00 00:00:00'),
(825,76,1,'64.09.01.1008','Sungai Parit','','0000-00-00 00:00:00'),
(826,76,1,'64.09.01.1009','Nipah-Nipah','','0000-00-00 00:00:00'),
(827,76,1,'64.09.01.1010','Nenang','','0000-00-00 00:00:00'),
(828,76,1,'64.09.01.1011','Penajam','','0000-00-00 00:00:00'),
(829,76,1,'64.09.01.1012','Gunung Seteleng','','0000-00-00 00:00:00'),
(830,76,1,'64.09.01.1013','Buluminung','','0000-00-00 00:00:00'),
(831,76,1,'64.09.01.1014','Sotek','','0000-00-00 00:00:00'),
(832,76,1,'64.09.01.1015','Sepan','','0000-00-00 00:00:00'),
(833,76,1,'64.09.01.1016','Riko','','0000-00-00 00:00:00'),
(834,76,1,'64.09.01.1017','Gersik','','0000-00-00 00:00:00'),
(835,76,1,'64.09.01.1018','Jenebora','','0000-00-00 00:00:00'),
(836,76,1,'64.09.01.1019','Pantai Lango','','0000-00-00 00:00:00'),
(837,76,2,'64.09.01.2020','Giri Mukti','','0000-00-00 00:00:00'),
(838,76,2,'64.09.01.2021','Bukit Subur','','0000-00-00 00:00:00'),
(839,76,2,'64.09.01.2022','Sidorejo','','0000-00-00 00:00:00'),
(840,76,2,'64.09.01.2023','Giripurwa','','0000-00-00 00:00:00'),
(841,77,2,'64.09.02.2001','Api-api','','0000-00-00 00:00:00'),
(842,77,2,'64.09.02.2002','Sesulu','','0000-00-00 00:00:00'),
(843,77,1,'64.09.02.1003','Warn','','0000-00-00 00:00:00'),
(844,77,2,'64.09.02.2004','Bangun Mulya','','0000-00-00 00:00:00'),
(845,78,2,'64.09.03.2001','Babulu Darat','','0000-00-00 00:00:00'),
(846,78,2,'64.09.03.2002','Labangka','','0000-00-00 00:00:00'),
(847,78,2,'64.09.03.2003','Babulu Laut','','0000-00-00 00:00:00'),
(848,78,2,'64.09.03.2004','Gunung Intan','','0000-00-00 00:00:00'),
(849,78,2,'64.09.03.2005','Gunung Makmur','','0000-00-00 00:00:00'),
(850,78,2,'64.09.03.2006','Sebakung Jaya','','0000-00-00 00:00:00'),
(851,78,2,'64.09.03.2007','Rawa Mulia','','0000-00-00 00:00:00'),
(852,78,2,'64.09.03.2008','Sri Raharja','','0000-00-00 00:00:00'),
(853,78,2,'64.09.03.2009','Sumber Sari','','0000-00-00 00:00:00'),
(854,78,2,'64.09.03.2010','Rintik','','0000-00-00 00:00:00'),
(855,78,2,'64.09.03.2011','Gunung Mulia','','0000-00-00 00:00:00'),
(856,78,2,'64.09.03.2012','Labangka Barat','','0000-00-00 00:00:00'),
(857,79,2,'64.09.04.2001','Tengin Baru','','0000-00-00 00:00:00'),
(858,79,2,'64.09.04.2002','Bukit Raya','','0000-00-00 00:00:00'),
(859,79,2,'64.09.04.2003','Suka Raja','','0000-00-00 00:00:00'),
(860,79,2,'64.09.04.2004','Bumi Harapan','','0000-00-00 00:00:00'),
(861,79,1,'64.09.04.1005','Sepaku','','0000-00-00 00:00:00'),
(862,79,1,'64.09.04.1006','Pemaluan','','0000-00-00 00:00:00'),
(863,79,1,'64.09.04.1007','Maridan','','0000-00-00 00:00:00'),
(864,79,1,'64.09.04.1008','Mentawir','','0000-00-00 00:00:00'),
(865,79,2,'64.09.04.2009','Argo Mulyo','','0000-00-00 00:00:00'),
(866,79,2,'64.09.04.2010','Semoi Dua','','0000-00-00 00:00:00'),
(867,79,2,'64.09.04.2011','Suko Mulyo','','0000-00-00 00:00:00'),
(868,79,2,'64.09.04.2012','Wono Sari','','0000-00-00 00:00:00'),
(869,79,2,'64.09.04.2013','Karang Jinawi','','0000-00-00 00:00:00'),
(870,79,2,'64.09.04.2014','Binuang','','0000-00-00 00:00:00'),
(871,79,2,'64.09.04.2015','Telemow','','0000-00-00 00:00:00'),
(872,80,2,'64.11.01.2001','Long Hurai','','0000-00-00 00:00:00'),
(873,80,2,'64.11.01.2002','Long Melaham','','0000-00-00 00:00:00'),
(874,80,2,'64.11.01.2003','Memahak Besar','','0000-00-00 00:00:00'),
(875,80,2,'64.11.01.2004','Memahak Ulu','','0000-00-00 00:00:00'),
(876,80,2,'64.11.01.2005','Batu Majang','','0000-00-00 00:00:00'),
(877,80,2,'64.11.01.2006','Ujoh Bilang','','0000-00-00 00:00:00'),
(878,80,2,'64.11.01.2007','Long Bagun Ilir','','0000-00-00 00:00:00'),
(879,80,2,'64.11.01.2008','Long Bagun Ulu','','0000-00-00 00:00:00'),
(880,80,2,'64.11.01.2009','Batoq Kelo','','0000-00-00 00:00:00'),
(881,80,2,'64.11.01.2010','Long Merah','','0000-00-00 00:00:00'),
(882,80,2,'64.11.01.2011','Rukun Damai','','0000-00-00 00:00:00'),
(883,81,2,'64.11.02.2001','Long Hubung','','0000-00-00 00:00:00'),
(884,81,2,'64.11.02.2002','Memahak Teboq','','0000-00-00 00:00:00'),
(885,81,2,'64.11.02.2003','Lutan','','0000-00-00 00:00:00'),
(886,81,2,'64.11.02.2004','Matalibaq','','0000-00-00 00:00:00'),
(887,81,2,'64.11.02.2005','Datah Bilang Ilir','','0000-00-00 00:00:00'),
(888,81,2,'64.11.02.2006','Datah Bilang Ulu','','0000-00-00 00:00:00'),
(889,81,2,'64.11.02.2007','Tri Pariq Makmur','','0000-00-00 00:00:00'),
(890,81,2,'64.11.02.2008','Wana Pariq','','0000-00-00 00:00:00'),
(891,81,2,'64.11.02.2009','Datah Bilang Baru','','0000-00-00 00:00:00'),
(892,81,2,'64.11.02.2010','Sirau','','0000-00-00 00:00:00'),
(893,81,2,'64.11.02.2011','Long Hubung Ulu','','0000-00-00 00:00:00'),
(894,82,2,'64.11.03.2001','Laham','','0000-00-00 00:00:00'),
(895,82,2,'64.11.03.2002','Long Gelawang','','0000-00-00 00:00:00'),
(896,82,2,'64.11.03.2003','Muara Ratah','','0000-00-00 00:00:00'),
(897,82,2,'64.11.03.2004','Danum Paroy','','0000-00-00 00:00:00'),
(898,82,2,'64.11.03.2005','Nyaribungan','','0000-00-00 00:00:00'),
(899,83,2,'64.11.04.2001','Long Penaneh I','','0000-00-00 00:00:00'),
(900,83,2,'64.11.04.2002','Long Kerioq','','0000-00-00 00:00:00'),
(901,83,2,'64.11.04.2003','Long Penaneh II','','0000-00-00 00:00:00'),
(902,83,2,'64.11.04.2004','Tiong Ohang','','0000-00-00 00:00:00'),
(903,83,2,'64.11.04.2005','Long Penaneh III','','0000-00-00 00:00:00'),
(904,83,2,'64.11.04.2006','Tiong Bu\'u','','0000-00-00 00:00:00'),
(905,83,2,'64.11.04.2007','Naha Buan','','0000-00-00 00:00:00'),
(906,83,2,'64.11.04.2008','Naha Tifab','','0000-00-00 00:00:00'),
(907,83,2,'64.11.04.2009','Naha Silat','','0000-00-00 00:00:00'),
(908,83,2,'64.11.04.2010','Long Apari','','0000-00-00 00:00:00'),
(909,84,2,'64.11.05.2001','Delang Kerohong','','0000-00-00 00:00:00'),
(910,84,2,'64.11.05.2002','Long Pakaq','','0000-00-00 00:00:00'),
(911,84,2,'64.11.05.2003','Long Lunuk','','0000-00-00 00:00:00'),
(912,84,2,'64.11.05.2004','Long Isun','','0000-00-00 00:00:00'),
(913,84,2,'64.11.05.2005','Naha Aru','','0000-00-00 00:00:00'),
(914,84,2,'64.11.05.2006','Datah Naha','','0000-00-00 00:00:00'),
(915,84,2,'64.11.05.2007','Lirung Ubing','','0000-00-00 00:00:00'),
(916,84,2,'64.11.05.2008','Long Pahangai I','','0000-00-00 00:00:00'),
(917,84,2,'64.11.05.2009','Long Pahangai II','','0000-00-00 00:00:00'),
(918,84,2,'64.11.05.2010','Long Tuyoq','','0000-00-00 00:00:00'),
(919,84,2,'64.11.05.2011','Liu Mulang','','0000-00-00 00:00:00'),
(920,84,2,'64.11.05.2012','Long Pakaq Baru','','0000-00-00 00:00:00'),
(921,84,2,'64.11.05.2013','Long Lunuk Baru','','0000-00-00 00:00:00'),
(922,85,1,'64.71.01.1001','Manggar','','0000-00-00 00:00:00'),
(923,85,1,'64.71.01.1002','Lamaru','','0000-00-00 00:00:00'),
(924,85,1,'64.71.01.1003','Teritip','','0000-00-00 00:00:00'),
(925,85,1,'64.71.01.1004','Manggar Baru','','0000-00-00 00:00:00'),
(926,86,1,'64.71.02.1001','Baru Ilir','','0000-00-00 00:00:00'),
(927,86,1,'64.71.02.1002','Baru Tengah','','0000-00-00 00:00:00'),
(928,86,1,'64.71.02.1003','Baru Ulu','','0000-00-00 00:00:00'),
(929,86,1,'64.71.02.1004','Kariangau','','0000-00-00 00:00:00'),
(930,86,1,'64.71.02.1005','Margo Mulyo','','0000-00-00 00:00:00'),
(931,86,1,'64.71.02.1006','Marga Sari','','0000-00-00 00:00:00'),
(932,87,1,'64.71.03.1001','Batu Ampar','','0000-00-00 00:00:00'),
(933,87,1,'64.71.03.1002','Gunungsamarinda','','0000-00-00 00:00:00'),
(934,87,1,'64.71.03.1003','Karang Joang','','0000-00-00 00:00:00'),
(935,87,1,'64.71.03.1004','Muararapak','','0000-00-00 00:00:00'),
(936,87,1,'64.71.03.1005','Gunungsamarinda Baru','','0000-00-00 00:00:00'),
(937,87,1,'64.71.03.1006','Graha Indah','','0000-00-00 00:00:00'),
(938,88,1,'64.71.04.1001','Gunungsari Ulu','','0000-00-00 00:00:00'),
(939,88,1,'64.71.04.1002','Gunungsari Ilir','','0000-00-00 00:00:00'),
(940,88,1,'64.71.04.1003','Karang Rejo','','0000-00-00 00:00:00'),
(941,88,1,'64.71.04.1004','Karang Jati','','0000-00-00 00:00:00'),
(942,88,1,'64.71.04.1005','Mekar Sari','','0000-00-00 00:00:00'),
(943,88,1,'64.71.04.1006','Sumber Rejo','','0000-00-00 00:00:00'),
(944,89,1,'64.71.05.1002','Sepinggan','','0000-00-00 00:00:00'),
(945,89,1,'64.71.05.1006','Gunungbahagia','','0000-00-00 00:00:00'),
(946,89,1,'64.71.05.1008','Sepinggan Baru','','0000-00-00 00:00:00'),
(947,89,1,'64.71.05.1009','Sepinggan Raya','','0000-00-00 00:00:00'),
(948,89,1,'64.71.05.1010','Sungainangka','','0000-00-00 00:00:00'),
(949,89,1,'64.71.05.1011','Damai Baru','','0000-00-00 00:00:00'),
(950,89,1,'64.71.05.1012','Damai Bahagia','','0000-00-00 00:00:00'),
(951,90,1,'64.71.06.1001','Prapatan','','0000-00-00 00:00:00'),
(952,90,1,'64.71.06.1002','Telaga Sari','','0000-00-00 00:00:00'),
(953,90,1,'64.71.06.1003','Klandasan Ulu','','0000-00-00 00:00:00'),
(954,90,1,'64.71.06.1004','Klandasan Ilir','','0000-00-00 00:00:00'),
(955,90,1,'64.71.06.1005','Damai','','0000-00-00 00:00:00'),
(956,91,1,'64.72.01.1001','Rawa Makmur','','0000-00-00 00:00:00'),
(957,91,1,'64.72.01.1002','Handil Bakti','','0000-00-00 00:00:00'),
(958,91,1,'64.72.01.1003','Bukuan','','0000-00-00 00:00:00'),
(959,91,1,'64.72.01.1004','Simpang Pasir','','0000-00-00 00:00:00'),
(960,91,1,'64.72.01.1005','Bantuas','','0000-00-00 00:00:00'),
(961,92,1,'64.72.02.1001','Sungai Keledang','','0000-00-00 00:00:00'),
(962,92,1,'64.72.02.1002','Baqa','','0000-00-00 00:00:00'),
(963,92,1,'64.72.02.1003','Mesjid','','0000-00-00 00:00:00'),
(964,92,1,'64.72.02.1009','Mangkupalas','','0000-00-00 00:00:00'),
(965,92,1,'64.72.02.1010','Tenun Samarinda','','0000-00-00 00:00:00'),
(966,92,1,'64.72.02.1011','Gunung Panjang','','0000-00-00 00:00:00'),
(967,93,1,'64.72.03.1001','Teluk Lerong Ilir','','0000-00-00 00:00:00'),
(968,93,1,'64.72.03.1002','Jawa','','0000-00-00 00:00:00'),
(969,93,1,'64.72.03.1004','Air Putih','','0000-00-00 00:00:00'),
(970,93,1,'64.72.03.1005','Sidodadi','','0000-00-00 00:00:00'),
(971,93,1,'64.72.03.1006','Air Hitam','','0000-00-00 00:00:00'),
(972,93,1,'64.72.03.1007','Dadi Mulya','','0000-00-00 00:00:00'),
(973,93,1,'64.72.03.1008','Gunung Kelua','','0000-00-00 00:00:00'),
(974,93,1,'64.72.03.1009','Bukit Pinang','','0000-00-00 00:00:00'),
(975,94,1,'64.72.04.1001','Selili','','0000-00-00 00:00:00'),
(976,94,1,'64.72.04.1002','Sungai Dama','','0000-00-00 00:00:00'),
(977,94,1,'64.72.04.1003','Sidomulyo','','0000-00-00 00:00:00'),
(978,94,1,'64.72.04.1013','Sidodamai','','0000-00-00 00:00:00'),
(979,94,1,'64.72.04.1014','Pelita','','0000-00-00 00:00:00'),
(980,95,1,'64.72.05.1002','Sempaja Selatan','','0000-00-00 00:00:00'),
(981,95,1,'64.72.05.1003','Lempake','','0000-00-00 00:00:00'),
(982,95,1,'64.72.05.1004','Sungai Siring','','0000-00-00 00:00:00'),
(983,95,1,'64.72.05.1010','Sempaja Utara','','0000-00-00 00:00:00'),
(984,95,1,'64.72.05.1011','Tanah Merah','','0000-00-00 00:00:00'),
(985,95,1,'64.72.05.1012','Sempaja Barat','','0000-00-00 00:00:00'),
(986,95,1,'64.72.05.1013','Sempaja Timur','','0000-00-00 00:00:00'),
(987,95,1,'64.72.05.1014','Budaya Pampang','','0000-00-00 00:00:00'),
(988,96,1,'64.72.06.1001','Loa Bakung','','0000-00-00 00:00:00'),
(989,96,1,'64.72.06.1002','Loa Buah','','0000-00-00 00:00:00'),
(990,96,1,'64.72.06.1003','Karang Asem Ulu','','0000-00-00 00:00:00'),
(991,96,1,'64.72.06.1004','Loa Bahu','','0000-00-00 00:00:00'),
(992,96,1,'64.72.06.1005','Teluk Lerong Ulu','','0000-00-00 00:00:00'),
(993,96,1,'64.72.06.1006','Karang Asam Ilir','','0000-00-00 00:00:00'),
(994,96,1,'64.72.06.1007','Karang Anyar','','0000-00-00 00:00:00'),
(995,97,1,'64.72.07.1001','Sungai Kapih','','0000-00-00 00:00:00'),
(996,97,1,'64.72.07.1002','Sambutan','','0000-00-00 00:00:00'),
(997,97,1,'64.72.07.1003','Makroman','','0000-00-00 00:00:00'),
(998,97,1,'64.72.07.1004','Sindang Sari','','0000-00-00 00:00:00'),
(999,97,1,'64.72.07.1005','Pulau Atas','','0000-00-00 00:00:00'),
(1000,98,1,'64.72.08.1001','Temindung Permai','','0000-00-00 00:00:00'),
(1001,98,1,'64.72.08.1002','Sungai Pinang Dalam','','0000-00-00 00:00:00'),
(1002,98,1,'64.72.08.1003','Gunung Lingai','','0000-00-00 00:00:00'),
(1003,98,1,'64.72.08.1004','Mugirejo','','0000-00-00 00:00:00'),
(1004,98,1,'64.72.08.1005','Bandara','','0000-00-00 00:00:00'),
(1005,99,1,'64.72.09.1001','Karang Mumus','','0000-00-00 00:00:00'),
(1006,99,1,'64.72.09.1002','Pelabuhan','','0000-00-00 00:00:00'),
(1007,99,1,'64.72.09.1003','Pasar Pagi','','0000-00-00 00:00:00'),
(1008,99,1,'64.72.09.1004','Bugis','','0000-00-00 00:00:00'),
(1009,99,1,'64.72.09.1005','Sungai Pinang Luar','','0000-00-00 00:00:00'),
(1010,100,1,'64.72.10.1001','Simpang Tiga','','0000-00-00 00:00:00'),
(1011,100,1,'64.72.10.1002','Tani Aman','','0000-00-00 00:00:00'),
(1012,100,1,'64.72.10.1003','Sengkotek','','0000-00-00 00:00:00'),
(1013,100,1,'64.72.10.1004','Harapan Baru','','0000-00-00 00:00:00'),
(1014,100,1,'64.72.10.1005','Rapak Dalam','','0000-00-00 00:00:00'),
(1015,101,1,'64.74.01.1001','Bontang Kuala','','0000-00-00 00:00:00'),
(1016,101,1,'64.74.01.1002','Bontang Baru','','0000-00-00 00:00:00'),
(1017,101,1,'64.74.01.1003','Lok Tuan','','0000-00-00 00:00:00'),
(1018,101,1,'64.74.01.1004','Guntung','','0000-00-00 00:00:00'),
(1019,101,1,'64.74.01.1005','Guntung Elai','','0000-00-00 00:00:00'),
(1020,101,1,'64.74.01.1006','Api-api','','0000-00-00 00:00:00'),
(1021,102,1,'64.74.02.1001','Tanjung Laut','','0000-00-00 00:00:00'),
(1022,102,1,'64.74.02.1002','Berbas Tangah','','0000-00-00 00:00:00'),
(1023,102,1,'64.74.02.1003','Berbas Pantai','','0000-00-00 00:00:00'),
(1024,102,1,'64.74.02.1004','Satimpo','','0000-00-00 00:00:00'),
(1025,102,1,'64.74.02.1005','Bontang Lestari','','0000-00-00 00:00:00'),
(1026,102,1,'64.74.02.1006','Tanjung Laut Indah','','0000-00-00 00:00:00'),
(1027,103,1,'64.74.03.1001','Belimbing','','0000-00-00 00:00:00'),
(1028,103,1,'64.74.03.1002','Gunung Telihan','','0000-00-00 00:00:00'),
(1029,103,1,'64.74.03.1003','Kanaan','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `ref_kelurahan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_kementerian`
--

DROP TABLE IF EXISTS `ref_kementerian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_kementerian` (
  `id_kementerian` int(3) NOT NULL AUTO_INCREMENT,
  `kode_kementerian` varchar(20) NOT NULL,
  `kementerian` varchar(80) NOT NULL,
  `singkatan` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_kementerian`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_kementerian`
--

LOCK TABLES `ref_kementerian` WRITE;
/*!40000 ALTER TABLE `ref_kementerian` DISABLE KEYS */;
INSERT INTO `ref_kementerian` VALUES
(1,'','Kementerian Pendidikan dan Kebudayaan','Kemendikbud'),
(2,'','Kementerian Agama','Kemenag');
/*!40000 ALTER TABLE `ref_kementerian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_lokasi`
--

DROP TABLE IF EXISTS `ref_lokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_lokasi` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(150) NOT NULL,
  PRIMARY KEY (`id_lokasi`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_lokasi`
--

LOCK TABLES `ref_lokasi` WRITE;
/*!40000 ALTER TABLE `ref_lokasi` DISABLE KEYS */;
INSERT INTO `ref_lokasi` VALUES
(1,'Mergangsan'),
(2,'Paser'),
(3,'SAMBOJA'),
(4,'Tenggarong'),
(5,'SAMARINDA'),
(6,'KAHALA'),
(7,'SAMARINDA'),
(8,'Tenggarong'),
(9,'SANGASANGA'),
(10,'Samarinda'),
(11,'Muara Wis'),
(12,'Kota Bangun'),
(13,'Muara Kaman'),
(14,'Sebulu'),
(15,'LOA JANAN'),
(16,'GENTING TANAH'),
(17,'Sumberjo'),
(18,'BANYUWANGI'),
(19,'umaq Dian'),
(20,'Muara Jawa'),
(21,'Handil Baru'),
(22,'CARAWALI'),
(23,'Surabaya'),
(24,'MOJOKERTO'),
(25,'Bulu Lampoko'),
(26,'Kutai'),
(27,'SABINTULUNG'),
(28,'Banyuwangi'),
(29,'Balikpapan'),
(30,'Madiun'),
(31,'Muara Muntai'),
(32,'Kedang Murung'),
(33,'Tabang'),
(34,'Kota Bangun'),
(35,'Samarinda'),
(36,'Embalut'),
(37,'LOA KULU'),
(38,'Sangasanga'),
(39,'Rambayan'),
(40,'MUARA JAWA'),
(41,'Loa Janan'),
(42,'LINGGANG BIGUN'),
(43,'MUARA JAWA'),
(44,'KUKAR'),
(45,'Handil'),
(46,'BONE'),
(47,'MUARA JAWA'),
(48,'Muara Kaman'),
(49,'Sei Barang'),
(50,'Jombang'),
(51,'SAMARINDA'),
(52,'tenggarong'),
(53,'SUMATRA UTARA'),
(54,'Linggang Amer'),
(55,'REA BARAT'),
(56,'Ma.Badak'),
(57,'SUKA BUMI'),
(58,'MUARA JAWA'),
(59,'Muara Kaman'),
(60,'LOK BATU'),
(61,'JUAQ ASA'),
(62,'MUARA JAWA'),
(63,'Muara Kaman'),
(64,'Semayang'),
(65,'SAMARINDA'),
(66,'Anggana'),
(67,'BANJARMASIN'),
(68,'Muara Kaman'),
(69,'LOA KULU'),
(70,'Handil C'),
(71,'SAMARINDA'),
(72,'Sangasanga'),
(73,'SAMARINDA'),
(74,'DONDANG'),
(75,'Handil B'),
(76,'SENGKANG'),
(77,'LOA TEBU'),
(78,'BALIKPAPAN'),
(79,'Lampihong'),
(80,'Tabang'),
(81,'Tambu'),
(82,'Kandeapi'),
(83,'Saliki'),
(84,'Blitar'),
(85,'Salo Palai'),
(86,'BLITAR'),
(87,'MUARA PAHU'),
(88,'Kutai Lama'),
(89,'Selerong'),
(90,'SAMBOJA'),
(91,'SAMARINDA'),
(92,'Long Beleh Modang'),
(93,'SABBANG'),
(94,'Palliwe'),
(95,'MUARA JAWA'),
(96,'KULON PROGO'),
(97,'Sanipah'),
(98,'Marangkayu'),
(99,'Pela'),
(100,'Kembang Janggut'),
(101,'B.T.Benawa'),
(102,'SAMARINDA'),
(103,'Samarinda'),
(104,'MURUNG PUDAK'),
(105,'BATU TIMBAU'),
(106,'Soppeng'),
(107,'Muara Jawa'),
(108,'6402136401690001'),
(109,'Kahala'),
(110,'SINJAI'),
(111,'Pasuruan'),
(112,'BANTUL'),
(113,'BARABAI'),
(114,'Muara Jawa'),
(115,'HANDIL BARU'),
(116,'Senambah'),
(117,'Kamp. Baru'),
(118,'Muara Badak'),
(119,'SAMBOJA'),
(120,'MUARA JAWA'),
(121,'EMBALUT'),
(122,'MELAK'),
(123,'MASAMBA'),
(124,'PATI'),
(125,'Sebulu'),
(126,'SAMARINDA'),
(127,'NGAWI'),
(128,'AJAKANG'),
(129,'TELUK DALAM'),
(130,'SOLO'),
(131,'Muara Badak'),
(132,'JEMBAYAN'),
(133,'MUARA JAWA'),
(134,'MUARA JAWA'),
(135,'SALAM REJO'),
(136,'BENGGERIS'),
(137,'Muara Jawa'),
(138,'BANJARMASIN'),
(139,'Lejja'),
(140,'BENGGERIS'),
(141,'ADOLANG'),
(142,'Barong Tongkok'),
(143,'SUAKONG'),
(144,'MUARA JAWA'),
(145,'Banyumas'),
(146,'Malaka'),
(147,'DONDANG'),
(148,'SUAKONG'),
(149,'MUARA JAWA'),
(150,'Anggana'),
(151,'EMBALUT'),
(152,'EMBALUT'),
(153,'DONDANG'),
(154,'SUKA BUMI'),
(155,'SAMPALAKAMBULA'),
(156,'Timor Leste'),
(157,'UJUNG PANDANG'),
(158,'Te'),
(159,'t'),
(160,'Yogyakarta'),
(161,'');
/*!40000 ALTER TABLE `ref_lokasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_penandatangan`
--

DROP TABLE IF EXISTS `ref_penandatangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penandatangan` (
  `id_penandatangan` int(11) NOT NULL AUTO_INCREMENT,
  `penandatangan` int(11) NOT NULL,
  `pengatasnama` int(11) NOT NULL,
  PRIMARY KEY (`id_penandatangan`),
  KEY `penetap` (`penandatangan`),
  KEY `wakil` (`pengatasnama`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_penandatangan`
--

LOCK TABLES `ref_penandatangan` WRITE;
/*!40000 ALTER TABLE `ref_penandatangan` DISABLE KEYS */;
INSERT INTO `ref_penandatangan` VALUES
(3,67,0),
(6,1,1);
/*!40000 ALTER TABLE `ref_penandatangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_penetap`
--

DROP TABLE IF EXISTS `ref_penetap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_penetap` (
  `id_penetap` int(11) NOT NULL AUTO_INCREMENT,
  `penetap` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_penetap`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_penetap`
--

LOCK TABLES `ref_penetap` WRITE;
/*!40000 ALTER TABLE `ref_penetap` DISABLE KEYS */;
INSERT INTO `ref_penetap` VALUES
(2,'Kepala BKD'),
(3,'Sekda'),
(4,'Presiden'),
(5,'SARKAWI');
/*!40000 ALTER TABLE `ref_penetap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_propinsi`
--

DROP TABLE IF EXISTS `ref_propinsi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_propinsi` (
  `id_propinsi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_propinsi` varchar(20) DEFAULT NULL,
  `propinsi` varchar(128) NOT NULL DEFAULT '',
  `ibukota` varchar(128) DEFAULT NULL,
  `dibuat` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_propinsi`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_propinsi`
--

LOCK TABLES `ref_propinsi` WRITE;
/*!40000 ALTER TABLE `ref_propinsi` DISABLE KEYS */;
INSERT INTO `ref_propinsi` VALUES
(1,'11','Aceh','Banda Aceh','2015-03-25 10:31:40'),
(2,'12','Sumatera Utara','Medan','2015-03-25 10:31:40'),
(3,'13','Sumatera Barat','Padang','2015-03-25 10:31:40'),
(4,'14','Riau','Pekanbaru','2015-03-25 10:31:40'),
(5,'15','Jambi','Jambi','2015-03-25 10:31:40'),
(6,'16','Sumatera Selatan','Palembang','2015-03-25 10:31:40'),
(7,'17','Bengkulu','Bengkulu','2015-03-25 10:31:40'),
(8,'18','Lampung','Bandar Lampung','2015-03-25 10:31:40'),
(9,'19','Kepulauan Bangka Belitung','Pangkal Pinang','2015-03-25 10:31:40'),
(10,'21','Kepulauan Riau','Tanjung Pinang','2015-03-25 10:31:40'),
(11,'31','DKI Jakarta','Jakarta','2015-03-25 10:31:40'),
(12,'32','Jawa Barat','Bandung','2015-03-25 10:31:40'),
(13,'33','Jawa Tengah','Semarang','2015-03-25 10:31:40'),
(14,'34','Daerah Istimewa Yogyakarta','Yogyakarta','2015-03-25 10:31:40'),
(15,'35','Jawa Timur','Surabaya','2015-03-25 10:31:40'),
(16,'36','Banten','Serang','2015-03-25 10:31:40'),
(17,'51','Bali','Denpasar','2015-03-25 10:31:40'),
(18,'52','Nusa Tenggara Barat','Mataram','2015-03-25 10:31:40'),
(19,'53','Nusa Tenggara Timur','Kupang','2015-03-25 10:31:40'),
(20,'61','Kalimantan Barat','Pontianak','2015-03-25 10:31:40'),
(21,'62','Kalimantan Tengah','Palangkaraya','2015-03-25 10:31:40'),
(22,'63','Kalimantan Selatan','Banjarmasin','2015-03-25 10:31:40'),
(23,'64','Kalimantan Timur','Samarinda','2015-03-25 10:31:40'),
(24,'65','Kalimantan Utara','Tanjung Selor','2015-03-25 10:31:40'),
(25,'71','Sulawesi Utara','Manado','2015-03-25 10:31:40'),
(26,'72','Sulawesi Tengah','Palu','2015-03-25 10:31:40'),
(27,'73','Sulawesi Selatan','Makassar','2015-03-25 10:31:40'),
(28,'74','Sulawesi Tenggara','Kendari','2015-03-25 10:31:40'),
(29,'75','Gorontalo','Gorontalo','2015-03-25 10:31:40'),
(30,'76','Sulawesi Barat','Mamuju','2015-03-25 10:31:40'),
(31,'81','Maluku','Ambon','2015-03-25 10:31:40'),
(32,'82','Maluku Utara','Ternate','2015-03-25 10:31:40'),
(33,'91','Papua','Jayapura','2015-03-25 10:31:40'),
(34,'92','Papua Barat','Manokwari','2015-03-25 10:31:40');
/*!40000 ALTER TABLE `ref_propinsi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_role`
--

DROP TABLE IF EXISTS `ref_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_role` (
  `id_role` int(3) NOT NULL AUTO_INCREMENT,
  `id_aplikasi` int(1) NOT NULL,
  `nama_role` varchar(80) NOT NULL,
  PRIMARY KEY (`id_role`),
  KEY `id_aplikasi` (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_role`
--

LOCK TABLES `ref_role` WRITE;
/*!40000 ALTER TABLE `ref_role` DISABLE KEYS */;
INSERT INTO `ref_role` VALUES
(26,0,'0'),
(57,0,'0'),
(61,103,'ADMIN'),
(82,103,'Operator'),
(83,103,'Super Admin'),
(84,103,'Pimpinan');
/*!40000 ALTER TABLE `ref_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_role_nav`
--

DROP TABLE IF EXISTS `ref_role_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_role_nav` (
  `id_role_nav` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) NOT NULL,
  `id_nav` int(11) NOT NULL,
  PRIMARY KEY (`id_role_nav`)
) ENGINE=InnoDB AUTO_INCREMENT=10871 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_role_nav`
--

LOCK TABLES `ref_role_nav` WRITE;
/*!40000 ALTER TABLE `ref_role_nav` DISABLE KEYS */;
INSERT INTO `ref_role_nav` VALUES
(10675,82,1787),
(10676,82,1788),
(10677,82,1789),
(10678,82,1790),
(10679,82,1791),
(10680,82,1792),
(10681,82,1793),
(10682,82,1801),
(10683,82,1802),
(10684,82,1805),
(10685,82,1794),
(10686,82,1795),
(10687,82,1806),
(10688,82,1807),
(10711,61,1784),
(10715,61,1787),
(10716,61,1788),
(10717,61,1789),
(10718,61,1790),
(10719,61,1791),
(10720,61,1792),
(10721,61,1793),
(10722,61,1801),
(10723,61,1802),
(10724,61,1803),
(10725,61,1804),
(10726,61,1805),
(10727,61,1794),
(10728,61,1795),
(10729,61,1806),
(10730,61,1807),
(10793,83,1784),
(10794,83,1785),
(10795,83,1786),
(10796,83,1800),
(10797,83,1787),
(10798,83,1788),
(10799,83,1789),
(10800,83,1790),
(10801,83,1791),
(10802,83,1792),
(10803,83,1793),
(10804,83,1801),
(10805,83,1802),
(10806,83,1803),
(10807,83,1804),
(10808,83,1805),
(10809,83,1794),
(10810,83,1795),
(10811,83,1806),
(10812,83,1807),
(10813,61,1850),
(10814,83,1850),
(10815,61,1851),
(10816,83,1851),
(10817,61,1852),
(10818,83,1852),
(10819,61,1853),
(10820,83,1853),
(10821,61,1854),
(10822,83,1854),
(10823,61,1855),
(10824,83,1855),
(10825,61,1856),
(10826,83,1856),
(10827,61,1857),
(10828,83,1857),
(10829,61,1858),
(10830,83,1858),
(10831,61,1859),
(10832,83,1859),
(10833,61,1860),
(10834,83,1860),
(10844,82,1850),
(10845,82,1851),
(10846,82,1852),
(10847,82,1854),
(10848,82,1855),
(10849,82,1858),
(10850,82,1860),
(10851,84,1793),
(10852,84,1850),
(10853,84,1851),
(10854,84,1852),
(10855,84,1854),
(10856,84,1858),
(10857,84,1794),
(10858,84,1795),
(10859,84,1806),
(10860,84,1807),
(10861,84,1860),
(10866,61,1861),
(10867,83,1861),
(10868,84,1861),
(10869,84,1784),
(10870,83,1862);
/*!40000 ALTER TABLE `ref_role_nav` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_status_kawin`
--

DROP TABLE IF EXISTS `ref_status_kawin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_status_kawin` (
  `id_statuskawin` int(1) NOT NULL AUTO_INCREMENT,
  `statuskawin` varchar(20) NOT NULL,
  PRIMARY KEY (`id_statuskawin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_status_kawin`
--

LOCK TABLES `ref_status_kawin` WRITE;
/*!40000 ALTER TABLE `ref_status_kawin` DISABLE KEYS */;
INSERT INTO `ref_status_kawin` VALUES
(1,'Kawin'),
(2,'Belum Kawin'),
(3,'Janda'),
(4,'Duda');
/*!40000 ALTER TABLE `ref_status_kawin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_status_pegawai`
--

DROP TABLE IF EXISTS `ref_status_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_status_pegawai` (
  `id_status_pegawai` int(1) NOT NULL AUTO_INCREMENT,
  `urut` int(1) NOT NULL,
  `tipe` int(1) NOT NULL,
  `status_usul` int(1) NOT NULL,
  `nama_status` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_status_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_status_pegawai`
--

LOCK TABLES `ref_status_pegawai` WRITE;
/*!40000 ALTER TABLE `ref_status_pegawai` DISABLE KEYS */;
INSERT INTO `ref_status_pegawai` VALUES
(1,3,1,0,'CPNS',NULL),
(2,4,1,0,'PNS',NULL),
(4,5,2,3,'Pensiun',''),
(5,1,1,1,'Mutasi Masuk',''),
(6,6,2,2,'Mutasi Keluar',NULL);
/*!40000 ALTER TABLE `ref_status_pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_tempat_lahir`
--

DROP TABLE IF EXISTS `ref_tempat_lahir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_tempat_lahir` (
  `id_tempat_lahir` int(11) NOT NULL AUTO_INCREMENT,
  `tempat_lahir` varchar(75) NOT NULL,
  PRIMARY KEY (`id_tempat_lahir`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_tempat_lahir`
--

LOCK TABLES `ref_tempat_lahir` WRITE;
/*!40000 ALTER TABLE `ref_tempat_lahir` DISABLE KEYS */;
/*!40000 ALTER TABLE `ref_tempat_lahir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_tipe_pegawai`
--

DROP TABLE IF EXISTS `ref_tipe_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_tipe_pegawai` (
  `id_tipe_pegawai` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `tipe_pegawai` varchar(75) NOT NULL,
  `jenis` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_tipe_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_tipe_pegawai`
--

LOCK TABLES `ref_tipe_pegawai` WRITE;
/*!40000 ALTER TABLE `ref_tipe_pegawai` DISABLE KEYS */;
INSERT INTO `ref_tipe_pegawai` VALUES
(1,'PNS/ASN','1'),
(2,'Honorer','2'),
(3,'K2','2');
/*!40000 ALTER TABLE `ref_tipe_pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_unit`
--

DROP TABLE IF EXISTS `ref_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_unit` (
  `id_unit` int(11) NOT NULL AUTO_INCREMENT,
  `id_par_unit` int(11) DEFAULT NULL,
  `kode_unit` varchar(20) DEFAULT NULL,
  `unit` varchar(80) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `id_kepala` int(11) DEFAULT NULL,
  `level_unit` int(1) DEFAULT NULL,
  `bso_image` varchar(50) DEFAULT NULL,
  `bso_uu` text DEFAULT NULL,
  `aktif` int(1) NOT NULL,
  `urut` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_unit`
--

LOCK TABLES `ref_unit` WRITE;
/*!40000 ALTER TABLE `ref_unit` DISABLE KEYS */;
INSERT INTO `ref_unit` VALUES
(27,NULL,'PLJ','Politeknik LP3I Jakarta','Jakarta',NULL,0,1,NULL,NULL,1,1),
(32,27,'KPG','Kampus Pengembangan','Jakarta',NULL,0,2,NULL,NULL,1,1);
/*!40000 ALTER TABLE `ref_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rs_ref_role`
--

DROP TABLE IF EXISTS `rs_ref_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rs_ref_role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `id_group` int(11) NOT NULL,
  `nama_role` varchar(150) NOT NULL,
  PRIMARY KEY (`id_role`),
  KEY `id_group` (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rs_ref_role`
--

LOCK TABLES `rs_ref_role` WRITE;
/*!40000 ALTER TABLE `rs_ref_role` DISABLE KEYS */;
INSERT INTO `rs_ref_role` VALUES
(1,3,'Root'),
(2,1,'Administrator RSUD');
/*!40000 ALTER TABLE `rs_ref_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_jabatan`
--

DROP TABLE IF EXISTS `view_jabatan`;
/*!50001 DROP VIEW IF EXISTS `view_jabatan`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_jabatan` AS SELECT
 1 AS `id_jabatan`,
  1 AS `id_pegawai`,
  1 AS `id_peg_jabatan`,
  1 AS `id_atasan`,
  1 AS `id_atasan_atasan`,
  1 AS `tmt_jabatan`,
  1 AS `selesai_jabatan`,
  1 AS `nama_jabatan`,
  1 AS `bup`,
  1 AS `id_eselon`,
  1 AS `eselon`,
  1 AS `id_bidang`,
  1 AS `kode_bidang`,
  1 AS `nama_bidang`,
  1 AS `id_unit`,
  1 AS `kode_unit`,
  1 AS `unit`,
  1 AS `id_status_pegawai`,
  1 AS `tipe_status`,
  1 AS `nama_status_pegawai`,
  1 AS `puncak`,
  1 AS `uraian_jabatan` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_pegawai`
--

DROP TABLE IF EXISTS `view_pegawai`;
/*!50001 DROP VIEW IF EXISTS `view_pegawai`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_pegawai` AS SELECT
 1 AS `nama_pegawai`,
  1 AS `agama`,
  1 AS `jenis_kelamin`,
  1 AS `id_pegawai`,
  1 AS `id_agama`,
  1 AS `id_jeniskelamin`,
  1 AS `id_gol_darah`,
  1 AS `id_tempat_lahir`,
  1 AS `id_suku`,
  1 AS `id_kelurahan`,
  1 AS `username`,
  1 AS `password`,
  1 AS `nip`,
  1 AS `nip_lama`,
  1 AS `no_nik`,
  1 AS `no_npwp`,
  1 AS `nama`,
  1 AS `gelar_depan`,
  1 AS `gelar_belakang`,
  1 AS `tanggal_lahir`,
  1 AS `hobi`,
  1 AS `tinggi`,
  1 AS `berat`,
  1 AS `rambut`,
  1 AS `bentuk_muka`,
  1 AS `warna_kulit`,
  1 AS `ciri_khas`,
  1 AS `cacat`,
  1 AS `alamat`,
  1 AS `kodepos`,
  1 AS `telepon`,
  1 AS `email`,
  1 AS `pin`,
  1 AS `photo`,
  1 AS `cpns_tmt`,
  1 AS `cpns_no`,
  1 AS `cpns_tanggal`,
  1 AS `cpns_berkas`,
  1 AS `mkg_tahun`,
  1 AS `mkg_bulan`,
  1 AS `last_login`,
  1 AS `id_tipe_pegawai`,
  1 AS `jenis_pegawai`,
  1 AS `tipe_pegawai`,
  1 AS `tempat_lahir`,
  1 AS `kelurahan`,
  1 AS `id_kecamatan`,
  1 AS `kecamatan`,
  1 AS `id_kabupaten`,
  1 AS `kabupaten`,
  1 AS `id_propinsi`,
  1 AS `propinsi`,
  1 AS `gol_darah`,
  1 AS `id_statuskawin`,
  1 AS `kawin` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'efiling'
--

--
-- Final view structure for view `view_jabatan`
--

/*!50001 DROP VIEW IF EXISTS `view_jabatan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_jabatan` AS select `p`.`id_jabatan` AS `id_jabatan`,`p`.`id_pegawai` AS `id_pegawai`,`p`.`id_peg_jabatan` AS `id_peg_jabatan`,`p`.`id_atasan` AS `id_atasan`,`p`.`id_atasan_atasan` AS `id_atasan_atasan`,`p`.`tmt_jabatan` AS `tmt_jabatan`,`p`.`selesai_jabatan` AS `selesai_jabatan`,`j`.`nama_jabatan` AS `nama_jabatan`,`j`.`bup` AS `bup`,`e`.`id_eselon` AS `id_eselon`,`e`.`eselon` AS `eselon`,`b`.`id_bidang` AS `id_bidang`,`b`.`kode_bidang` AS `kode_bidang`,`b`.`nama_bidang` AS `nama_bidang`,`u`.`id_unit` AS `id_unit`,`u`.`kode_unit` AS `kode_unit`,`u`.`unit` AS `unit`,`s`.`id_status_pegawai` AS `id_status_pegawai`,`s`.`tipe` AS `tipe_status`,`s`.`nama_status` AS `nama_status_pegawai`,`p`.`puncak` AS `puncak`,if(`j`.`stat_jab` = 1,concat(`j`.`nama_jabatan`,' ',`b`.`nama_bidang`,', ',`u`.`unit`),if(`j`.`stat_jab` = 2,concat(`j`.`nama_jabatan`,' ',`u`.`unit`),`j`.`nama_jabatan`)) AS `uraian_jabatan` from (((((`peg_jabatan` `p` left join `ref_jabatan` `j` on(`j`.`id_jabatan` = `p`.`id_jabatan`)) left join `ref_eselon` `e` on(`e`.`id_eselon` = `j`.`id_eselon`)) left join `ref_bidang` `b` on(`b`.`id_bidang` = `p`.`id_bidang`)) left join `ref_unit` `u` on(`u`.`id_unit` = `p`.`id_unit`)) left join `ref_status_pegawai` `s` on(`s`.`id_status_pegawai` = `p`.`id_status_pegawai`)) where `p`.`status` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_pegawai`
--

/*!50001 DROP VIEW IF EXISTS `view_pegawai`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_pegawai` AS select concat(ifnull(`p`.`gelar_depan`,''),' ',`p`.`nama`,if(`p`.`gelar_belakang` = '' or `p`.`gelar_belakang` is null,'',concat(', ',`p`.`gelar_belakang`))) AS `nama_pegawai`,`a`.`agama` AS `agama`,`k`.`jenis_kelamin` AS `jenis_kelamin`,`p`.`id_pegawai` AS `id_pegawai`,`p`.`id_agama` AS `id_agama`,`p`.`id_jeniskelamin` AS `id_jeniskelamin`,`p`.`id_gol_darah` AS `id_gol_darah`,`p`.`id_tempat_lahir` AS `id_tempat_lahir`,`p`.`id_suku` AS `id_suku`,`p`.`id_kelurahan` AS `id_kelurahan`,`p`.`username` AS `username`,`p`.`password` AS `password`,`p`.`nip` AS `nip`,`p`.`nip_lama` AS `nip_lama`,`p`.`no_nik` AS `no_nik`,`p`.`no_npwp` AS `no_npwp`,`p`.`nama` AS `nama`,`p`.`gelar_depan` AS `gelar_depan`,`p`.`gelar_belakang` AS `gelar_belakang`,`p`.`tanggal_lahir` AS `tanggal_lahir`,`p`.`hobi` AS `hobi`,`p`.`tinggi` AS `tinggi`,`p`.`berat` AS `berat`,`p`.`rambut` AS `rambut`,`p`.`bentuk_muka` AS `bentuk_muka`,`p`.`warna_kulit` AS `warna_kulit`,`p`.`ciri_khas` AS `ciri_khas`,`p`.`cacat` AS `cacat`,`p`.`alamat` AS `alamat`,`p`.`kodepos` AS `kodepos`,`p`.`telepon` AS `telepon`,`p`.`email` AS `email`,`p`.`pin` AS `pin`,`p`.`photo` AS `photo`,`p`.`cpns_tmt` AS `cpns_tmt`,`p`.`cpns_no` AS `cpns_no`,`p`.`cpns_tanggal` AS `cpns_tanggal`,`p`.`cpns_berkas` AS `cpns_berkas`,`p`.`mkg_tahun` AS `mkg_tahun`,`p`.`mkg_bulan` AS `mkg_bulan`,`p`.`last_login` AS `last_login`,`p`.`id_tipe_pegawai` AS `id_tipe_pegawai`,`tp`.`jenis` AS `jenis_pegawai`,`tp`.`tipe_pegawai` AS `tipe_pegawai`,`l`.`lokasi` AS `tempat_lahir`,`kel`.`kelurahan` AS `kelurahan`,`kec`.`id_kecamatan` AS `id_kecamatan`,`kec`.`kecamatan` AS `kecamatan`,`kab`.`id_kabupaten` AS `id_kabupaten`,`kab`.`kabupaten` AS `kabupaten`,`prop`.`id_propinsi` AS `id_propinsi`,`prop`.`propinsi` AS `propinsi`,`d`.`gol_darah` AS `gol_darah`,`ka`.`id_statuskawin` AS `id_statuskawin`,`ka`.`statuskawin` AS `kawin` from ((((((((((`peg_pegawai` `p` left join `ref_lokasi` `l` on(`l`.`id_lokasi` = `p`.`id_tempat_lahir`)) left join `ref_gol_darah` `d` on(`d`.`id_gol_darah` = `p`.`id_gol_darah`)) left join `ref_agama` `a` on(`a`.`id_agama` = `p`.`id_agama`)) left join `ref_jenis_kelamin` `k` on(`k`.`id_jeniskelamin` = `p`.`id_jeniskelamin`)) left join `ref_kelurahan` `kel` on(`kel`.`id_kelurahan` = `p`.`id_kelurahan`)) left join `ref_kecamatan` `kec` on(`kec`.`id_kecamatan` = `kel`.`id_kecamatan`)) left join `ref_kabupaten` `kab` on(`kab`.`id_kabupaten` = `kec`.`id_kabupaten`)) left join `ref_propinsi` `prop` on(`prop`.`id_propinsi` = `kab`.`id_propinsi`)) left join `ref_status_kawin` `ka` on(`ka`.`id_statuskawin` = `p`.`id_statuskawin`)) left join `ref_tipe_pegawai` `tp` on(`tp`.`id_tipe_pegawai` = `p`.`id_tipe_pegawai`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-24 18:47:57
