-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 05:29 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokokomputer`
--

-- --------------------------------------------------------

--
-- Table structure for table `detailtransaksi`
--

CREATE TABLE `detailtransaksi` (
  `ID_Invoice` varchar(50) NOT NULL,
  `Kode_Produk` varchar(50) NOT NULL,
  `Jumlah_Produk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailtransaksi`
--

INSERT INTO `detailtransaksi` (`ID_Invoice`, `Kode_Produk`, `Jumlah_Produk`) VALUES
('TNK-4178764055', '471108593072', 1),
('TNK-4178764055', '4711085940803', 1),
('TNK-4178764055', '730143314442', 1),
('TNK-4178764055', '840006668206', 1),
('TNK-4178764055', '889523041895', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `ID_Pelanggan` int(11) NOT NULL,
  `Nama_Pelanggan` varchar(255) NOT NULL,
  `No_Telp_Pelanggan` varchar(20) NOT NULL,
  `Alamat_Pelanggan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`ID_Pelanggan`, `Nama_Pelanggan`, `No_Telp_Pelanggan`, `Alamat_Pelanggan`) VALUES
(1, 'Azra Fadhil Shadiq', '087774013991', 'Kelurahan Sukamaju Kecamatan Cibeunying Kidul Kota Bandung, Jawa Barat 40121');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `Kode_Produk` varchar(50) NOT NULL,
  `Nama_Produk` varchar(255) NOT NULL,
  `Harga_Produk` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`Kode_Produk`, `Nama_Produk`, `Harga_Produk`) VALUES
('471108593072', 'ADATA - XPG GAMMIX S70 BLADE PCIe Gen4x4 M.2 2280 SSD 2TB', 2049000.00),
('4711085940803', 'ADATA - AX5U6000C3016G-DCLARWH', 1929000.00),
('730143314442', 'AMD - Ryzen 5 7600X', 3589000.00),
('840006668206', 'Corsair - RM850x SHIFT 80 PLUS Gold Fully Modular - White', 2539000.00),
('889523041895', 'Gigabyte - B650E AORUS ELITE X AX ICE', 4485000.00);

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `ID_Toko` int(11) NOT NULL,
  `Nama_Toko` varchar(255) NOT NULL,
  `Alamat_Toko` text NOT NULL,
  `No_Telp_Toko` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`ID_Toko`, `Nama_Toko`, `Alamat_Toko`, `No_Telp_Toko`) VALUES
(1, 'Nano Komputer', 'Mangga Dua Mall Lantai 2 No.47 A-B Jl. Arteri Mangga Dua Raya, Jakarta 10730', '02162309578');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `ID_Invoice` varchar(50) NOT NULL,
  `Tgl_Transaksi` date NOT NULL,
  `Kurir` varchar(50) NOT NULL,
  `ID_Pelanggan` int(11) NOT NULL,
  `ID_Toko` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`ID_Invoice`, `Tgl_Transaksi`, `Kurir`, `ID_Pelanggan`, `ID_Toko`) VALUES
('TNK-4178764055', '2024-09-27', 'J&T', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD PRIMARY KEY (`ID_Invoice`,`Kode_Produk`),
  ADD KEY `Kode_Produk` (`Kode_Produk`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`ID_Pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`Kode_Produk`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`ID_Toko`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`ID_Invoice`),
  ADD KEY `ID_Pelanggan` (`ID_Pelanggan`),
  ADD KEY `ID_Toko` (`ID_Toko`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `ID_Pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `ID_Toko` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD CONSTRAINT `detailtransaksi_ibfk_1` FOREIGN KEY (`ID_Invoice`) REFERENCES `transaksi` (`ID_Invoice`),
  ADD CONSTRAINT `detailtransaksi_ibfk_2` FOREIGN KEY (`Kode_Produk`) REFERENCES `produk` (`Kode_Produk`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`ID_Pelanggan`) REFERENCES `pelanggan` (`ID_Pelanggan`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`ID_Toko`) REFERENCES `toko` (`ID_Toko`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
