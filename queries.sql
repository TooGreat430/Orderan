-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2023 at 02:51 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unieat`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'Makanan'),
(2, 'Minuman'),
(3, 'Snacks'),
(6, 'Seblak');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `ItemID` int(11) NOT NULL,
  `ShopID` int(11) NOT NULL,
  `ItemName` varchar(50) NOT NULL,
  `ItemDescription` varchar(255) NOT NULL,
  `ItemPrice` int(11) NOT NULL,
  `ItemAvailability` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`ItemID`, `ShopID`, `ItemName`, `ItemDescription`, `ItemPrice`, `ItemAvailability`) VALUES
(1, 1, 'Bakmi Ayam Enak', 'Bakmi dengan daging ayam yang enak', 15000, 1),
(2, 1, 'Bakmi Ayam Pangsit Enak', 'Sama kayak bakmi ayam enak tapi ditambahin pangsit goreng', 17000, 1),
(3, 1, 'Es Teh Tawar', '', 3000, 1),
(4, 2, 'Bakso Sapi', '', 14000, 1),
(5, 1, 'Es Teh Manis', 'Es Teh teh Manis', 5000, 1),
(6, 1, 'Pangsit Goreng', 'Pangsit yang digoreng dengan enak', 2000, 1),
(7, 2, 'Baso Ikan', 'Baso Ikan pak jali 5 pcs lengkap dengan kuah yang gurih, bakso nya bikin hepi', 16000, 1),
(8, 2, 'Bakso Aci', 'Bakso kecil-kecil dari aci yang bikin hepi', 13000, 1),
(9, 2, 'Jus Jeruk', 'Bukan jus sachetan kok', 10000, 1),
(10, 2, 'Kerupuk Kaleng', '', 2000, 1),
(11, 3, 'Basreng', 'Baso digoreng bumbu pedes', 5000, 1),
(12, 3, 'Kerupuk Jablay', '100% ga keras', 5000, 1),
(13, 3, 'Makaroni Goreng', '', 5000, 1),
(14, 3, 'Pop Ice', 'Pop Ice full topping keju, dll.', 5000, 1),
(15, 3, 'JasJus', 'JasJus doang', 3000, 1),
(16, 4, 'Batagor Biasa', '', 2000, 1),
(17, 4, 'Batagor Telur', '', 3000, 1),
(18, 4, 'Batagor Keju', '', 6000, 1),
(19, 4, 'Batagor Pangsit', '', 3000, 1),
(20, 4, 'Batagor Tahu', '', 2000, 1),
(21, 5, 'Seblak Biasa', 'Kamu nanyea isi seblak ini? yaudah biar aku kasih tau yea seblak ini tuh isi kerupuk, baso, sosis. Inget yea, kerupuk, baso, sosis.', 10000, 1),
(22, 5, 'Seblak Setengah Lengkap', 'Kamu nanyea isi seblak ini? yaudah biar aku kasih tau yea seblak ini tuh isi kerupuk, baso, sosis, ceker, cikuwa. Inget yea, kerupuk, baso, sosis, ceker, cikuwa.', 14000, 1),
(23, 5, 'Seblak Lengkap', 'Kamu nanyea isi seblak ini? yaudah biar aku kasih tau yea seblak ini tuh isi kerupuk, baso, sosis, ceker, cikuwa, dumpling, otak-otak. Inget yea, kerupuk, baso, sosis, ceker, cikuwa, dumpling, otak-otak.', 18000, 1),
(24, 5, 'Kerupuk Kulit', 'Jangan lupa beli ini juga yea, kan udah aku kasih tau isi seblaknyea kan yea. Ente kadang-kadang ente yea, rawrrrrr', 5000, 1),
(25, 5, 'Jus Sirsak', '', 6000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `OrderID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`OrderID`, `ItemID`, `Quantity`, `Note`) VALUES
(1, 1, 5, ''),
(1, 2, 7, ''),
(2, 1, 2, ''),
(2, 3, 2, ''),
(3, 11, 3, ''),
(3, 13, 2, ''),
(3, 14, 2, ''),
(3, 15, 1, ''),
(4, 18, 2, ''),
(4, 19, 1, ''),
(4, 20, 4, ''),
(5, 4, 1, ''),
(5, 9, 1, ''),
(5, 10, 1, ''),
(6, 23, 2, ''),
(6, 24, 2, ''),
(6, 25, 3, ''),
(7, 16, 5, ''),
(7, 17, 4, ''),
(7, 18, 2, ''),
(7, 19, 7, ''),
(7, 20, 7, ''),
(8, 1, 2, ''),
(8, 5, 2, ''),
(8, 6, 2, ''),
(9, 22, 1, ''),
(9, 24, 1, ''),
(9, 25, 1, ''),
(10, 23, 2, ''),
(10, 24, 2, ''),
(10, 25, 5, ''),
(11, 11, 5, ''),
(11, 13, 5, ''),
(11, 14, 5, ''),
(11, 15, 5, ''),
(12, 1, 1, ''),
(12, 2, 1, ''),
(12, 5, 2, ''),
(12, 6, 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `orderheader`
--

CREATE TABLE `orderheader` (
  `OrderID` int(11) NOT NULL,
  `ShopID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `PickupperID` int(10) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `OrderDate` datetime NOT NULL,
  `OrderStatus` int(11) NOT NULL,
  `RatingScore` double NOT NULL,
  `RatingComment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderheader`
--

INSERT INTO `orderheader` (`OrderID`, `ShopID`, `CustomerID`, `PickupperID`, `Alamat`, `OrderDate`, `OrderStatus`, `RatingScore`, `RatingComment`) VALUES
(1, 1, 5, 17, 'ruang c1102', '2022-11-14 10:25:34', 3, 4.5, 'Bakminya Wuenak'),
(2, 1, 5, 17, 'Ruang A1803', '2022-11-15 06:45:26', 2, 4.2, 'Ga bosen bosen'),
(3, 3, 10, 17, 'Ruang A0203', '2022-11-16 14:16:26', 2, 3.7, 'Biasa aja'),
(4, 4, 10, 17, 'Ruang B0502', '2022-11-16 14:17:02', 2, 4, ''),
(5, 2, 11, 17, 'Perpus', '2022-11-16 14:17:32', 3, 4.7, 'MANTAPPPPPPPPPPPPPP'),
(6, 5, 12, 17, 'Antar ke GOR', '2022-11-16 14:18:38', 2, 4.9, 'Bikin nagih'),
(7, 4, 12, 17, 'GOR', '2022-11-16 14:19:43', 2, 4.4, 'Enak banget'),
(8, 1, 14, 17, 'Ruang B0305', '2022-11-16 14:21:10', 2, 2.78, 'Ga begitu enak'),
(9, 5, 14, 17, 'C1101', '2022-11-16 14:21:41', 2, 5, 'Perfekkk no fek fek'),
(10, 5, 5, 17, 'B1002', '2022-11-16 14:32:06', 2, 4.5, 'Enak'),
(11, 3, 5, 17, 'Ruang C0502', '2022-11-16 14:32:38', 2, 3.9, 'Biasa aja'),
(12, 1, 5, 17, 'Ruang C0301', '2022-11-16 14:33:10', 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`RoleID`, `RoleName`) VALUES
(1, 'Guest'),
(2, 'Customer'),
(3, 'Tenant'),
(4, 'Admin'),
(5, 'Pickupper');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `ShopID` int(11) NOT NULL,
  `ShopName` varchar(50) NOT NULL,
  `OwnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`ShopID`, `ShopName`, `OwnerID`) VALUES
(1, 'Bakmi Enak', 2),
(2, 'Bakso Pak Jali', 3),
(3, 'Warung Jajan', 4),
(4, 'Batagor Mantap', 6),
(5, 'Seblak CEP Sal MEK', 7);

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

CREATE TABLE `shop_categories` (
  `ShopID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_categories`
--

INSERT INTO `shop_categories` (`ShopID`, `CategoryID`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 2),
(3, 3),
(4, 3),
(5, 2),
(5, 3),
(5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `UserEmail` varchar(50) NOT NULL,
  `UserUsername` varchar(50) NOT NULL,
  `UserPassword` varchar(255) NOT NULL,
  `UserPhone` varchar(25) NOT NULL,
  `RoleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserEmail`, `UserUsername`, `UserPassword`, `UserPhone`, `RoleID`) VALUES
(1, 'darmawanruslan12@gmail.com', 'Darma', '12345', '082123733400', 4),
(2, 'dummyseller@email.com', 'dummyseller', '12345', '088888888888', 3),
(3, 'dummyseller@email.com', 'dummyseller2', '12345', '088888888888', 3),
(4, 'dummyseller@email.com', 'dummyseller3', '12345', '088888888888', 3),
(5, 'darma@email.com', 'ekkho jamalika', '$2y$10$.qd5fdAswW76LBOcDN7PAeogDRrzFx0lIxpQmis0l2mkrjSXV2uMO', '08123456789', 2),
(6, 'jubaedah@gmail.com', 'Pak Jubaedah', '12345', '088888888888', 3),
(7, 'cepsalmek@yahoo.com', 'Cecep salmek', '12345', '08123456789', 3),
(8, 'Budi@binus.ac.id', 'Budi Budiman', '$2y$10$CP70EtV.71uNKvd/r7IoPOfPnnhjaB2KDnq1FFCXszyqTRy6Vn5Sy', '+628123456789', 2),
(9, 'Marfel@binus.com', 'Marfelino', '$2y$10$nh8raAf6dh82v.hFX9zVjuKuETHrNVfQM5QcZS07012BwASv3gAuG', '08123456789', 2),
(10, 'Kari@gmail.com', 'Kari Kari Kari', '$2y$10$jN5oi5NHjvdUvyES6wI/xuIcyuQzMPjkA.MMh1Ax6HdRdsARMbjRi', '08123456789', 2),
(11, 'jessy@youtube.com', 'jessy val', '$2y$10$PskeL8MuQqb9Vqs6u1Ous.aqymIJ24pfPMZEGxuaH.OVzNEG9G1k.', '08123456789', 2),
(12, 'Randy@facebook.com', 'Raphael Randy', '$2y$10$gxV.JAAjGRtC4sRKekk1z.ljrgdoxksFnatwP9gxUBxOR9xonolwO', '+18123456789', 2),
(13, 'Ahsan@twitter.com', 'Ahsan Istamar', '$2y$10$FcFP2hqtEGWyGtvDbOgghuMgkFmgF3Sj3hoPAVqY/4eCAVK4bnz02', '+3388228830291', 2),
(14, 'Julius@Enterkomputer.com', 'Julius Juli', '$2y$10$YN4NWwWwD5qAxE75B6G8JuR57p1ZtSJEpN53REJVFLmIN42ry4WQ.', '+3001231902478', 2),
(15, 'Feli@terserah.co.id', 'Feliiiiiiiiiii', '$2y$10$qdHIGNoLU53bX7hWWV9RXO1kK4xn/d7K6roYa94ybs5Oc6yGCxrpq', '+1234901745221', 2),
(16, 'SiapaAjaDah@IniItu.Sini.Situ', 'Customer Misterius', '$2y$10$zXnrClodr6vxDJpGSiueK.YOIgsfl/KaQ34pUZoHMMWfl8oD2j8E.', '+12591750215890', 2),
(17, 'pickup@test.com', 'pickupper', '12345', '082123123929', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ShopID` (`ShopID`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`OrderID`,`ItemID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `orderheader`
--
ALTER TABLE `orderheader`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `ShopID` (`ShopID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `PickupperID` (`PickupperID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`ShopID`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD PRIMARY KEY (`ShopID`,`CategoryID`),
  ADD KEY `shop_categories_ibfk_1` (`CategoryID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `RoleID` (`RoleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orderheader`
--
ALTER TABLE `orderheader`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `ShopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`ShopID`) REFERENCES `shop` (`ShopID`);

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orderheader` (`OrderID`),
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `orderheader`
--
ALTER TABLE `orderheader`
  ADD CONSTRAINT `orderheader_ibfk_1` FOREIGN KEY (`ShopID`) REFERENCES `shop` (`ShopID`),
  ADD CONSTRAINT `orderheader_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `orderheader_ibfk_3` FOREIGN KEY (`PickupperID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `shop`
--
ALTER TABLE `shop`
  ADD CONSTRAINT `shop_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD CONSTRAINT `shop_categories_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `shop_categories_ibfk_2` FOREIGN KEY (`ShopID`) REFERENCES `shop` (`ShopID`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
