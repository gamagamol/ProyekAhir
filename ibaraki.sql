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
INSERT INTO `detail_penerimaan_barang` VALUES (163,1,10),(164,1,10),(165,2,10),(166,1,10),(167,1,10);
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
INSERT INTO `detail_transaksi_pembelian` VALUES (226,1,10,100000,6600,0.06,6000,600),(227,1,10,100000,6600,0.06,6000,600),(228,2,10,100000,6875000,62.5,6250000,625000),(229,1,10,100000,6875000,62.5,6250000,625000),(230,1,10,100000,6875000,62.5,6250000,625000);
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
INSERT INTO `detail_transaksi_penawaran` VALUES (235,1),(236,1),(238,1),(239,1),(237,2);
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
  `jumlah_detail_pengiriman` int NOT NULL,
  `sisa_detail_pengiriman` int NOT NULL,
  `berat_detail_pengiriman` float NOT NULL,
  `ppn_detail_pengiriman` int NOT NULL,
  `subtotal_detail_pengiriman` int NOT NULL,
  `total_detail_pengiriman` int NOT NULL,
  KEY `fk_pengiriman_produk` (`id_produk`),
  KEY `fk_pengiriman_penjualan` (`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi_pengiriman`
--

LOCK TABLES `detail_transaksi_pengiriman` WRITE;
/*!40000 ALTER TABLE `detail_transaksi_pengiriman` DISABLE KEYS */;
INSERT INTO `detail_transaksi_pengiriman` VALUES (302,2,194,5,5,31.25,312500,3125000,3437500),(303,1,195,5,5,31.25,312500,3125000,3437500),(304,2,194,5,0,31.25,625000,3125000,3437500),(305,1,195,5,0,31.25,625000,3125000,3437500),(306,1,192,5,5,0.03,300,3000,3300),(307,1,193,5,5,0.03,300,3000,3300),(308,1,192,5,0,0.03,600,3000,3300),(309,1,193,5,0,0.03,600,3000,3300),(310,1,196,10,0,62.5,625000,6250000,6875000);
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
INSERT INTO `detail_transaksi_penjualan` VALUES (192,1,10),(193,1,10),(194,2,10),(195,1,10),(196,1,10);
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
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal`
--

LOCK TABLES `jurnal` WRITE;
/*!40000 ALTER TABLE `jurnal` DISABLE KEYS */;
INSERT INTO `jurnal` VALUES (151,258,112,'2022-04-07',13770000,'debit'),(152,258,211,'2022-04-07',1250000,'kredit'),(153,258,212,'2022-04-07',10000,'kredit'),(154,258,411,'2022-04-07',12500000,'kredit');
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
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembelian`
--

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;
INSERT INTO `pembelian` VALUES (226,192,256,'PO/1/2022/04/07','2022-04-07'),(227,193,257,'PO/1/2022/04/07','2022-04-07'),(228,194,258,'PO/2/2022/04/07','2022-04-07'),(229,195,259,'PO/2/2022/04/07','2022-04-07'),(230,196,260,'PO/1/2022/04/08','2022-04-08');
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
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penawaran`
--

LOCK TABLES `penawaran` WRITE;
/*!40000 ALTER TABLE `penawaran` DISABLE KEYS */;
INSERT INTO `penawaran` VALUES (235,256,'QTH/1/2022/04/07','2022-04-07',10,0,10),(236,257,'QTH/1/2022/04/07','2022-04-07',10,0,10),(237,258,'QTH/2/2022/04/07','2022-04-07',100,100,100),(238,259,'QTH/2/2022/04/07','2022-04-07',100,0,100),(239,260,'QTH/1/2022/04/08','2022-04-08',100,0,100);
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
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penerimaan_barang`
--

LOCK TABLES `penerimaan_barang` WRITE;
/*!40000 ALTER TABLE `penerimaan_barang` DISABLE KEYS */;
INSERT INTO `penerimaan_barang` VALUES (163,226,256,'GR/1/2022/04/07','2022-04-07'),(164,227,257,'GR/1/2022/04/07','2022-04-07'),(165,228,258,'GR/2/2022/04/07','2022-04-07'),(166,229,259,'GR/2/2022/04/07','2022-04-07'),(167,230,260,'GR/1/2022/04/08','2022-04-08');
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
  `id_penerimaan_barang` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `no_pengiriman` varchar(100) NOT NULL,
  `tgl_pengiriman` date NOT NULL,
  PRIMARY KEY (`id_pengiriman`)
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengiriman`
--

LOCK TABLES `pengiriman` WRITE;
/*!40000 ALTER TABLE `pengiriman` DISABLE KEYS */;
INSERT INTO `pengiriman` VALUES (302,165,258,'DO/1/2022/04/07','2022-04-07'),(303,166,259,'DO/1/2022/04/07','2022-04-07'),(304,165,258,'DO/1/2022/04/07','2022-04-07'),(305,166,259,'DO/1/2022/04/07','2022-04-07'),(306,163,256,'DO/2/2022/04/07','2022-04-07'),(307,164,257,'DO/2/2022/04/07','2022-04-07'),(308,163,256,'DO/2/2022/04/07','2022-04-07'),(309,164,257,'DO/2/2022/04/07','2022-04-07'),(310,167,260,'DO/1/2022/04/08','2022-04-08');
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
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan`
--

LOCK TABLES `penjualan` WRITE;
/*!40000 ALTER TABLE `penjualan` DISABLE KEYS */;
INSERT INTO `penjualan` VALUES (192,256,'SO/1/2022/04/07','2022-04-07'),(193,257,'SO/1/2022/04/07','2022-04-07'),(194,258,'SO/2/2022/04/07','2022-04-07'),(195,259,'SO/2/2022/04/07','2022-04-07'),(196,260,'SO/1/2022/04/08','2022-04-08');
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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagihan`
--

LOCK TABLES `tagihan` WRITE;
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
INSERT INTO `tagihan` VALUES (105,258,302,'INV/1/2022/04/07','2022-04-07'),(106,259,303,'INV/1/2022/04/07','2022-04-07');
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
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (256,0,'PJ1','CUTTING',10,0,10,0.06,100000,10,6000,16600,'delivery','P001','PE-01',600,10000,2),(257,0,'PJ1','CUTTING',10,0,10,0.06,100000,10,6000,16600,'delivery','P001','PE-01',600,10000,2),(258,0,'PJ2','CUTTING',100,100,100,62.5,100000,10,6250000,6885000,'bill','P001','PE-02',625000,10000,2),(259,0,'PJ2','CUTTING',100,0,100,62.5,100000,10,6250000,6885000,'bill','P001','PE-02',625000,10000,2),(260,0,'PJ3','CUTTING',100,0,100,62.5,100000,10,6250000,6885000,'delivery','P001','PE-01',625000,10000,2);
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

-- Dump completed on 2022-04-10 22:06:10
