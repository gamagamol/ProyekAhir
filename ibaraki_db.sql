-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: ibaraki_db
-- ------------------------------------------------------
-- Server version	8.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `akun`
--

DROP TABLE IF EXISTS `akun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `akun` (
  `kode_akun` int NOT NULL,
  `nama_akun` varchar(100) DEFAULT NULL,
  `header_akun` int DEFAULT NULL,
  PRIMARY KEY (`kode_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `akun`
--

LOCK TABLES `akun` WRITE;
/*!40000 ALTER TABLE `akun` DISABLE KEYS */;
INSERT INTO `akun` VALUES (111,'CASH',11),(112,'ACCOUNT RECIEVABLE',11),(200,'ACCOUNTS PAYABLE',2),(211,'VAT DEBT',21),(212,'CARRYINNG LOAD DEBT',22),(411,'SALES',4),(500,'PURCHASE',5);
/*!40000 ALTER TABLE `akun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_penerimaan_barang`
--

DROP TABLE IF EXISTS `detail_penerimaan_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_penerimaan_barang` (
  `id_penerimaan_barang` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_detail_penerimaan` int NOT NULL,
  KEY `id_produk` (`id_produk`),
  KEY `detail_penerimaan_barang_ibfk_3_idx` (`id_penerimaan_barang`),
  CONSTRAINT `detail_penerimaan_barang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `detail_penerimaan_barang_ibfk_3` FOREIGN KEY (`id_penerimaan_barang`) REFERENCES `penerimaan_barang` (`id_penerimaan_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_penerimaan_barang`
--

LOCK TABLES `detail_penerimaan_barang` WRITE;
/*!40000 ALTER TABLE `detail_penerimaan_barang` DISABLE KEYS */;
INSERT INTO `detail_penerimaan_barang` VALUES (193,2,10),(194,1,10),(195,2,10);
/*!40000 ALTER TABLE `detail_penerimaan_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi_pembayaran`
--

DROP TABLE IF EXISTS `detail_transaksi_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi_pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_produk` int NOT NULL,
  PRIMARY KEY (`id_pembayaran`,`id_produk`),
  UNIQUE KEY `id_pembayaran` (`id_pembayaran`,`id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `detail_transaksi_pembayaran_ibfk_1` FOREIGN KEY (`id_pembayaran`) REFERENCES `pembayaran` (`id_pembayaran`),
  CONSTRAINT `detail_transaksi_pembayaran_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pembayaran`
--

LOCK TABLES `detail_transaksi_pembayaran` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pembayaran` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pembayaran` VALUES (111,1),(110,2),(112,2);
/*!40000 ALTER TABLE `detail_transaksi_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi_pembelian`
--

DROP TABLE IF EXISTS `detail_transaksi_pembelian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi_pembelian` (
  `id_pembelian` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_detail_pembelian` int NOT NULL,
  `harga_detail_pembelian` int NOT NULL,
  `total_detail_pembelian` int NOT NULL,
  `berat_detail_pembelian` float NOT NULL,
  `subtotal_detail_pembelian` int NOT NULL,
  `ppn_detail_pembelian` int NOT NULL,
  KEY `id_produk` (`id_produk`),
  KEY `detail_transaksi_pembelian_ibfk_1_idx` (`id_pembelian`),
  CONSTRAINT `detail_transaksi_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  CONSTRAINT `detail_transaksi_pembelian_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pembelian`
--

LOCK TABLES `detail_transaksi_pembelian` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pembelian` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pembelian` VALUES (259,2,10,100000,6937500,62.5,6250000,687500),(260,1,10,100000,10279710,92.61,9261000,1018710),(261,2,10,100000,6937500,62.5,6250000,687500);
/*!40000 ALTER TABLE `detail_transaksi_pembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi_penawaran`
--

DROP TABLE IF EXISTS `detail_transaksi_penawaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi_penawaran` (
  `id_penawaran` int NOT NULL,
  `id_produk` int NOT NULL,
  PRIMARY KEY (`id_penawaran`,`id_produk`),
  UNIQUE KEY `id_penawaran` (`id_penawaran`,`id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `detail_transaksi_penawaran_ibfk_1` FOREIGN KEY (`id_penawaran`) REFERENCES `penawaran` (`id_penawaran`),
  CONSTRAINT `detail_transaksi_penawaran_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_penawaran`
--

LOCK TABLES `detail_transaksi_penawaran` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_penawaran` DISABLE KEYS */;
INSERT INTO `detail_transaksi_penawaran` VALUES (266,1),(265,2),(267,2);
/*!40000 ALTER TABLE `detail_transaksi_penawaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi_pengiriman`
--

DROP TABLE IF EXISTS `detail_transaksi_pengiriman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi_pengiriman` (
  `id_pengiriman` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_detail_pengiriman` int NOT NULL,
  `sisa_detail_pengiriman` int NOT NULL,
  `berat_detail_pengiriman` float NOT NULL,
  `ppn_detail_pengiriman` int NOT NULL,
  `subtotal_detail_pengiriman` int NOT NULL,
  `total_detail_pengiriman` int NOT NULL,
  KEY `fk_pengiriman_produk` (`id_produk`),
  KEY `fk_produk_pengiriman_idx` (`id_pengiriman`),
  CONSTRAINT `fk_pengiriman_detail_pengiriman` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  CONSTRAINT `fk_produk_detail_pengiriman` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pengiriman`
--

LOCK TABLES `detail_transaksi_pengiriman` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pengiriman` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pengiriman` VALUES (390,2,10,0,62.5,687500,6250000,6937500),(391,1,10,0,92.61,1018710,9261000,10279710),(392,2,10,0,62.5,687500,6250000,6937500);
/*!40000 ALTER TABLE `detail_transaksi_pengiriman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi_penjualan`
--

DROP TABLE IF EXISTS `detail_transaksi_penjualan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi_penjualan` (
  `id_penjualan` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_detail_penjualan` int NOT NULL,
  PRIMARY KEY (`id_penjualan`,`id_produk`),
  UNIQUE KEY `id_penjualan` (`id_penjualan`),
  KEY `fk_penjualan_produk` (`id_produk`),
  CONSTRAINT `fk_penjualan_detail` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`),
  CONSTRAINT `fk_produk_detail` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_penjualan`
--

LOCK TABLES `detail_transaksi_penjualan` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_penjualan` DISABLE KEYS */;
INSERT INTO `detail_transaksi_penjualan` VALUES (227,2,10),(228,1,10),(229,2,10);
/*!40000 ALTER TABLE `detail_transaksi_penjualan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jurnal`
--

DROP TABLE IF EXISTS `jurnal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal` (
  `id_jurnal` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int DEFAULT NULL,
  `kode_akun` int DEFAULT NULL,
  `tgl_jurnal` date DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `posisi_db_cr` char(10) DEFAULT NULL,
  PRIMARY KEY (`id_jurnal`),
  KEY `fk_jurnal_akun` (`kode_akun`),
  KEY `fk_jurnal_tranasksi` (`id_transaksi`),
  CONSTRAINT `fk_jurnal_akun` FOREIGN KEY (`kode_akun`) REFERENCES `akun` (`kode_akun`),
  CONSTRAINT `fk_jurnal_tranasksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=223 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal`
--

LOCK TABLES `jurnal` WRITE;
/*!40000 ALTER TABLE `jurnal` DISABLE KEYS */;
INSERT INTO `jurnal` VALUES (211,286,112,'2022-04-14',17217200,'debit'),(212,286,211,'2022-04-14',1706210,'kredit'),(213,286,212,'2022-04-14',10000,'kredit'),(214,286,411,'2022-04-14',15511000,'kredit'),(215,286,111,'2022-04-14',17217200,'debit'),(216,286,112,'2022-04-14',17217200,'kredit'),(217,288,112,'2022-04-14',6937500,'debit'),(218,288,211,'2022-04-14',687500,'kredit'),(219,288,212,'2022-04-14',10000,'kredit'),(220,288,411,'2022-04-14',6250000,'kredit'),(221,288,111,'2022-04-14',6937500,'debit'),(222,288,112,'2022-04-14',6937500,'kredit');
/*!40000 ALTER TABLE `jurnal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelanggan`
--

DROP TABLE IF EXISTS `pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  `perwakilan` varchar(100) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggan`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES ('P001','Mol\'s tecnology','jalan jakarta 4 no.7,RT 03/RW02 ANTAPANI KULON BANDUNG','Gama Ariefsadya','gamaariefsadya@gmail.com'),('P002','Mol\'s Academy','bandung','Gama Gamol','gamaariefsadya@gmail.com');
/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemasok`
--

DROP TABLE IF EXISTS `pemasok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemasok` (
  `id_pemasok` int NOT NULL AUTO_INCREMENT,
  `nama_pemasok` varchar(100) NOT NULL,
  `alamat_pemasok` varchar(100) NOT NULL,
  `perwakilan_pemasok` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pemasok`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemasok`
--

LOCK TABLES `pemasok` WRITE;
/*!40000 ALTER TABLE `pemasok` DISABLE KEYS */;
INSERT INTO `pemasok` VALUES (1,'Suwandi','bandung','suwandi'),(2,'GAMA ARIEFSADYA','BANDUNG','GAMA ARIEFSADYA');
/*!40000 ALTER TABLE `pemasok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembantu_penawaran`
--

DROP TABLE IF EXISTS `pembantu_penawaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembantu_penawaran` (
  `id_pembantu` int NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(100) NOT NULL,
  `tgl_pembantu` date NOT NULL,
  `nomor_pekerjaan` varchar(100) NOT NULL,
  `id_pelanggan` varchar(100) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `tebal_pembantu` int NOT NULL,
  `lebar_pembantu` int NOT NULL,
  `panjang_pembantu` int NOT NULL,
  `jumlah_pembantu` int NOT NULL,
  `layanan_pembantu` varchar(100) NOT NULL,
  `harga_pembantu` int NOT NULL,
  `ongkir_pembantu` int NOT NULL,
  `id_user` int NOT NULL,
  `tebal_penawaran` int NOT NULL,
  `lebar_penawaran` int NOT NULL,
  `panjang_penawaran` int NOT NULL,
  `berat_pembantu` float NOT NULL,
  `subtotal` int NOT NULL,
  `ppn` int NOT NULL,
  `total` int NOT NULL,
  PRIMARY KEY (`id_pembantu`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembantu_penawaran`
--

LOCK TABLES `pembantu_penawaran` WRITE;
/*!40000 ALTER TABLE `pembantu_penawaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembantu_penawaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `no_pembayaran` varchar(100) NOT NULL,
  `tgl_pembayaran` date DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `fk_pembayaran_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES (110,286,'PYT/1/2022/04/14','2022-04-14'),(111,287,'PYT/1/2022/04/14','2022-04-14'),(112,288,'PYT/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaranvendor`
--

DROP TABLE IF EXISTS `pembayaranvendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaranvendor` (
  `idpembayaranvendor` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_pembelian` int NOT NULL,
  `no_pembayaran_vendor` varchar(100) NOT NULL,
  `tgl_pembayaran_vendor` date NOT NULL,
  `cicilan` int NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`idpembayaranvendor`),
  UNIQUE KEY `id_transaksi_UNIQUE` (`id_transaksi`),
  UNIQUE KEY `id_pembelian_UNIQUE` (`id_pembelian`),
  CONSTRAINT `pv_pembelian` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  CONSTRAINT `pv_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaranvendor`
--

LOCK TABLES `pembayaranvendor` WRITE;
/*!40000 ALTER TABLE `pembayaranvendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembayaranvendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembelian`
--

DROP TABLE IF EXISTS `pembelian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembelian` (
  `id_pembelian` int NOT NULL AUTO_INCREMENT,
  `id_penjualan` int NOT NULL,
  `id_transaksi` int DEFAULT NULL,
  `no_pembelian` varchar(100) NOT NULL,
  `tgl_pembelian` date NOT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `pembelian_ibfk_1` (`id_transaksi`),
  CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembelian`
--

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;
INSERT INTO `pembelian` VALUES (259,227,286,'PO/1/2022/04/14','2022-04-14'),(260,228,287,'PO/1/2022/04/14','2022-04-14'),(261,229,288,'PO/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `pembelian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penawaran`
--

DROP TABLE IF EXISTS `penawaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penawaran` (
  `id_penawaran` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `no_penawaran` varchar(100) NOT NULL,
  `tgl_penawaran` date DEFAULT NULL,
  `tebal_penawaran` int NOT NULL,
  `lebar_penawaran` int NOT NULL,
  `panjang_penawaran` int NOT NULL,
  PRIMARY KEY (`id_penawaran`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `fk_penawaran_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penawaran`
--

LOCK TABLES `penawaran` WRITE;
/*!40000 ALTER TABLE `penawaran` DISABLE KEYS */;
INSERT INTO `penawaran` VALUES (265,286,'QTH/1/2022/04/14','2022-04-14',100,100,100),(266,287,'QTH/1/2022/04/14','2022-04-14',100,0,100),(267,288,'QTH/2/2022/04/14','2022-04-14',100,100,100);
/*!40000 ALTER TABLE `penawaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penerimaan_barang`
--

DROP TABLE IF EXISTS `penerimaan_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penerimaan_barang` (
  `id_penerimaan_barang` int NOT NULL AUTO_INCREMENT,
  `id_pembelian` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `no_penerimaan` varchar(100) NOT NULL,
  `tgl_penerimaan` date NOT NULL,
  PRIMARY KEY (`id_penerimaan_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerimaan_barang`
--

LOCK TABLES `penerimaan_barang` WRITE;
/*!40000 ALTER TABLE `penerimaan_barang` DISABLE KEYS */;
INSERT INTO `penerimaan_barang` VALUES (193,259,286,'GR/1/2022/04/14','2022-04-14'),(194,260,287,'GR/1/2022/04/14','2022-04-14'),(195,261,288,'GR/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `penerimaan_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna` (
  `id` int NOT NULL,
  `ussername` varchar(100) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `status_pengguna` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna`
--

LOCK TABLES `pengguna` WRITE;
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` VALUES (0,'superadmin','Gama Ariefsadya','SUPER_ADMIN','$2y$10$xCLFDY8J97QAzzqrqJgW2eTbrKzRFxm3umyiBiriYQvI9ryKzBeWK',0),(1,'salesadmin','Gama Sales','SALES_ADMIN','$2y$10$tcRg/UUJ9dNliDqtJIMwi.exCSOwlroUFDdE2WRo5DVTGdaP1dGrC',0),(2,'accountingadmin','Gama Accounting','ACCOUNTING_ADMIN','$2y$10$H8kbPoEnlnncaJC0CzgYfOeaF0GEKZpoY3x/vYkSyjR5070lkUEMK',0),(3,'owner','Gama Owner','OWNER','$2y$10$QQYwe0eNriUAPMMZwvebPOKxmxnZwpYrR/h9Cz.5ZbkT5tOEH5IyC',0);
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengiriman`
--

DROP TABLE IF EXISTS `pengiriman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengiriman` (
  `id_pengiriman` int NOT NULL AUTO_INCREMENT,
  `id_penerimaan_barang` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_penjualan` int NOT NULL,
  `no_pengiriman` varchar(100) NOT NULL,
  `tgl_pengiriman` date NOT NULL,
  PRIMARY KEY (`id_pengiriman`),
  KEY `fk_pengiriman_penjualan_idx` (`id_penjualan`),
  KEY `fk_pengiriman_transaksi_idx` (`id_transaksi`),
  CONSTRAINT `fk_pengiriman_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`),
  CONSTRAINT `fk_pengiriman_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=393 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengiriman`
--

LOCK TABLES `pengiriman` WRITE;
/*!40000 ALTER TABLE `pengiriman` DISABLE KEYS */;
INSERT INTO `pengiriman` VALUES (390,193,286,227,'DO/1/2022/04/14','2022-04-14'),(391,194,287,228,'DO/1/2022/04/14','2022-04-14'),(392,195,288,229,'DO/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `pengiriman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penjualan`
--

DROP TABLE IF EXISTS `penjualan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penjualan` (
  `id_penjualan` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `no_penjualan` varchar(100) NOT NULL,
  `tgl_penjualan` date DEFAULT NULL,
  PRIMARY KEY (`id_penjualan`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `fk_penjualan_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan`
--

LOCK TABLES `penjualan` WRITE;
/*!40000 ALTER TABLE `penjualan` DISABLE KEYS */;
INSERT INTO `penjualan` VALUES (227,286,'SO/1/2022/04/14','2022-04-14'),(228,287,'SO/1/2022/04/14','2022-04-14'),(229,288,'SO/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `penjualan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id_produk` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `jenis_produk` varchar(100) NOT NULL,
  `bentuk_produk` varchar(100) NOT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (1,'SKS4','MACHINERY STEEL','CYLINDER'),(2,'SK45C','COLD WORK','FLAT'),(10,'SKS46','MACHINERY STEEL','FLAT');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tagihan`
--

DROP TABLE IF EXISTS `tagihan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tagihan` (
  `id_tagihan` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_pengiriman` int NOT NULL,
  `no_tagihan` varchar(100) NOT NULL,
  `tgl_tagihan` date DEFAULT NULL,
  PRIMARY KEY (`id_tagihan`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  UNIQUE KEY `id_pengiriman` (`id_pengiriman`),
  CONSTRAINT `fk_tagihan_pengiriman` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  CONSTRAINT `fk_tagihan_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagihan`
--

LOCK TABLES `tagihan` WRITE;
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
INSERT INTO `tagihan` VALUES (129,286,390,'INV/1/2022/04/14','2022-04-14'),(130,287,391,'INV/1/2022/04/14','2022-04-14'),(131,288,392,'INV/2/2022/04/14','2022-04-14');
/*!40000 ALTER TABLE `tagihan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id` int DEFAULT NULL,
  `kode_transaksi` varchar(100) NOT NULL,
  `layanan` varchar(100) DEFAULT NULL,
  `panjang_transaksi` int DEFAULT NULL,
  `lebar_transaksi` int DEFAULT NULL,
  `tebal_transaksi` int DEFAULT NULL,
  `berat` float NOT NULL,
  `harga` float DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `subtotal` int NOT NULL,
  `total` float DEFAULT NULL,
  `status_transaksi` varchar(100) NOT NULL,
  `id_pelanggan` varchar(100) DEFAULT NULL,
  `nomor_pekerjaan` varchar(100) NOT NULL,
  `ppn` float NOT NULL,
  `ongkir` float NOT NULL,
  `id_pemasok` int NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `fk_pelanggan_tagihan` (`id_pelanggan`),
  KEY `fk_pengguna_tagihan` (`id`),
  CONSTRAINT `fk_tra_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  CONSTRAINT `fk_tra_pengguna` FOREIGN KEY (`id`) REFERENCES `pengguna` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (286,0,'PJ1','CUTTING',100,100,100,62.5,100000,10,6250000,6937500,'payment','P001','PE-01',687500,10000,2),(287,0,'PJ1','MILLING',100,0,100,92.61,100000,10,9261000,10279700,'payment','P001','PE-01',1018710,10000,2),(288,0,'PJ2','CUTTING',100,100,100,62.5,100000,10,6250000,6937500,'payment','P001','PE-02',687500,10000,2);
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-15  9:43:14
