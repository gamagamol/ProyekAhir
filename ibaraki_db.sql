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
INSERT INTO `akun` VALUES (111,'Cash',11),(112,'Account Recivable',11),(200,'ACCOUNTS PAYABLE',2),(211,'VAT Debt',21),(212,'Carrying Load Debt',22),(411,'sales',4),(500,'PURCHASE',5);
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
  UNIQUE KEY `id_penerimaan_barang` (`id_penerimaan_barang`,`id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `detail_penerimaan_barang_ibfk_1` FOREIGN KEY (`id_penerimaan_barang`) REFERENCES `penerimaan_barang` (`id_penerimaan_barang`),
  CONSTRAINT `detail_penerimaan_barang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_penerimaan_barang`
--

LOCK TABLES `detail_penerimaan_barang` WRITE;
/*!40000 ALTER TABLE `detail_penerimaan_barang` DISABLE KEYS */;
INSERT INTO `detail_penerimaan_barang` VALUES (8,1),(7,2);
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
INSERT INTO `detail_transaksi_pembayaran` VALUES (52,1),(51,2);
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
  PRIMARY KEY (`id_pembelian`,`id_produk`),
  UNIQUE KEY `id_pembelian` (`id_pembelian`,`id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `detail_transaksi_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  CONSTRAINT `detail_transaksi_pembelian_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pembelian`
--

LOCK TABLES `detail_transaksi_pembelian` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pembelian` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pembelian` VALUES (33,1),(32,2);
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
INSERT INTO `detail_transaksi_penawaran` VALUES (115,1),(114,2);
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
  `id_penjualan` int NOT NULL,
  PRIMARY KEY (`id_pengiriman`,`id_produk`,`id_penjualan`),
  UNIQUE KEY `id_pengiriman` (`id_pengiriman`,`id_produk`,`id_penjualan`),
  KEY `fk_pengiriman_produk` (`id_produk`),
  KEY `fk_pengiriman_penjualan` (`id_penjualan`),
  CONSTRAINT `fk_pengiriman` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  CONSTRAINT `fk_pengiriman_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`),
  CONSTRAINT `fk_pengiriman_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pengiriman`
--

LOCK TABLES `detail_transaksi_pengiriman` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pengiriman` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pengiriman` VALUES (55,1,70),(54,2,69);
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
INSERT INTO `detail_transaksi_penjualan` VALUES (69,2),(70,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal`
--

LOCK TABLES `jurnal` WRITE;
/*!40000 ALTER TABLE `jurnal` DISABLE KEYS */;
INSERT INTO `jurnal` VALUES (231,135,500,'2022-02-07',1250000,'debit'),(232,135,200,'2022-02-07',1250000,'kredit'),(233,135,200,'2022-02-07',1250000,'debit'),(234,135,111,'2022-02-07',1250000,'kredit'),(235,135,112,'2022-02-07',1395000,'debit'),(236,135,211,'2022-02-07',125000,'kredit'),(237,135,212,'2022-02-07',10000,'kredit'),(238,135,411,'2022-02-07',1250000,'kredit'),(239,135,111,'2022-02-07',1395000,'debit'),(240,135,112,'2022-02-07',1395000,'kredit');
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
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggan`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES ('P001','Mol\'s tecnology','jalan jakarta 4 no.7,RT 03/RW02 ANTAPANI KULON BANDUNG','Gama Ariefsadya'),('P002','Mol\'s Academy','bandung','Gama Gamol');
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
INSERT INTO `pemasok` VALUES (1,'Suwandi','bandung','suwandi'),(2,'MOLI','-','MOL');
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
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES (51,135,'INV/1/2022/02/07','2022-02-07'),(52,136,'INV/1/2022/02/07','2022-02-07');
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
  PRIMARY KEY (`idpembayaranvendor`),
  UNIQUE KEY `id_transaksi_UNIQUE` (`id_transaksi`),
  UNIQUE KEY `id_pembelian_UNIQUE` (`id_pembelian`),
  CONSTRAINT `pv_pembelian` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  CONSTRAINT `pv_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaranvendor`
--

LOCK TABLES `pembayaranvendor` WRITE;
/*!40000 ALTER TABLE `pembayaranvendor` DISABLE KEYS */;
INSERT INTO `pembayaranvendor` VALUES (12,135,32,'PO/1/2022/02/07','2022-02-07'),(13,136,33,'PO/1/2022/02/07','2022-02-07');
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
  `id_transaksi` int NOT NULL,
  `no_pembelian` varchar(100) NOT NULL,
  `tgl_pembelian` date NOT NULL,
  PRIMARY KEY (`id_pembelian`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembelian`
--

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;
INSERT INTO `pembelian` VALUES (32,135,'PO/1/2022/02/07','2022-02-07'),(33,136,'PO/1/2022/02/07','2022-02-07');
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
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penawaran`
--

LOCK TABLES `penawaran` WRITE;
/*!40000 ALTER TABLE `penawaran` DISABLE KEYS */;
INSERT INTO `penawaran` VALUES (114,135,'QTH/1/2022/02/07','2022-02-07',100,100,100),(115,136,'QTH/1/2022/02/07','2022-02-07',100,0,100);
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
  `id_transaksi` int NOT NULL,
  `no_penerimaan` varchar(100) NOT NULL,
  `tgl_penerimaan` date NOT NULL,
  PRIMARY KEY (`id_penerimaan_barang`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `penerimaan_barang_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerimaan_barang`
--

LOCK TABLES `penerimaan_barang` WRITE;
/*!40000 ALTER TABLE `penerimaan_barang` DISABLE KEYS */;
INSERT INTO `penerimaan_barang` VALUES (7,135,'GR/1/2022/02/07','2022-02-07'),(8,136,'GR/1/2022/02/07','2022-02-07');
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
INSERT INTO `pengguna` VALUES (0,'superadmin','Gama Ariefsadya','SUPER_ADMIN','$2y$10$xCLFDY8J97QAzzqrqJgW2eTbrKzRFxm3umyiBiriYQvI9ryKzBeWK',0),(1,'salesadmin','Gama Sales','SALES_ADMIN','$2y$10$tcRg/UUJ9dNliDqtJIMwi.exCSOwlroUFDdE2WRo5DVTGdaP1dGrC',0),(2,'accountingadmin','Gama Accounting','ACCOUNTING_ADMIN','$2y$10$H8kbPoEnlnncaJC0CzgYfOeaF0GEKZpoY3x/vYkSyjR5070lkUEMK',0),(3,'owner','Gama Owner','owner','$2y$10$QQYwe0eNriUAPMMZwvebPOKxmxnZwpYrR/h9Cz.5ZbkT5tOEH5IyC',0);
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
  `id_transaksi` int NOT NULL,
  `no_pengiriman` varchar(100) NOT NULL,
  `tgl_pengiriman` date DEFAULT NULL,
  PRIMARY KEY (`id_pengiriman`),
  UNIQUE KEY `id_transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengiriman`
--

LOCK TABLES `pengiriman` WRITE;
/*!40000 ALTER TABLE `pengiriman` DISABLE KEYS */;
INSERT INTO `pengiriman` VALUES (54,135,'DO/1/2022/02/07','2022-02-07'),(55,136,'DO/1/2022/02/07','2022-02-07');
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
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan`
--

LOCK TABLES `penjualan` WRITE;
/*!40000 ALTER TABLE `penjualan` DISABLE KEYS */;
INSERT INTO `penjualan` VALUES (69,135,'SO/1/2022/02/07','2022-02-07'),(70,136,'SO/1/2022/02/07','2022-02-07');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (1,'SKS4','MACHINERY STEEL','CYLINDER'),(2,'SK45C','COLD WORK','FLAT');
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagihan`
--

LOCK TABLES `tagihan` WRITE;
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
INSERT INTO `tagihan` VALUES (56,135,54,'INV/1/2022/02/07','2022-02-07'),(57,136,55,'INV/1/2022/02/07','2022-02-07');
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
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (135,0,'PJ1','CUTTING',100,100,100,6.25,100000,1,625000,697500,'payment','P001','PE-01',62500,10000,1),(136,0,'PJ1','CUTTING',100,0,100,6.25,100000,1,625000,697500,'payment','P001','PE-01',62500,10000,1);
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

-- Dump completed on 2022-02-08 10:46:21
