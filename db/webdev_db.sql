-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2021 at 09:43 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webdev_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerID` int(11) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerID`, `email`, `password`, `firstName`, `lastName`) VALUES
(4, 'test@test.com', 'test', 'Test', 'Tester'),
(1, 'ctz12345@uga.edu', 'storm', 'Clara', 'Zhang'),
(2, 'claraworks@gmail.com', 'boop', 'Claire', 'Zing'),
(3, 'hotdog@gmail.com', 'relish', 'Ketchup', 'Mustard'),
(5, 'hello@hotmail.com', '123456', 'Hiya', 'Hoya');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` int(64) NOT NULL,
  `brandID` int(10) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `productPrice` decimal(5,2) NOT NULL,
  `productImage` varchar(100) NOT NULL,
  `productImageAlt` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `brandID`, `productName`, `productPrice`, `productImage`, `productImageAlt`) VALUES
(1, 1, 'Animal Crossing: New Horizons', '59.99', '../images/ac.png', 'Animal Crossing cover art'),
(2, 1, 'The Legend of Zelda: Breath of the Wild', '59.99', '../images/botw.png', 'Breath of the Wild cover art'),
(3, 1, 'Super Smash Bros. Ultimate', '59.99', '../images/smash.png', 'Super Smash Bros. Ultimate cover art'),
(4, 1, 'Pokemon Brilliant Diamond', '59.99', '../images/pokemon.png', 'Pokemon Brilliant Diamond cover art'),
(5, 2, 'God of War', '14.99', '../images/gow.png', 'God of War cover art'),
(6, 2, 'Farming Simulator 22', '59.99', '../images/farmsim.png', 'Farming Simulator 22 cover art'),
(7, 3, 'Halo Infinite', '59.99', '../images/halo.png', 'Halo Infinite cover art'),
(8, 3, 'NBA 2K22', '54.99', '../images/nba.png', 'NBA 2K22 cover art'),
(9, 1, 'Metroid Dread', '59.99', '../images/dread.png', 'Metroid Dread cover art'),
(10, 2, 'Call of Duty Vanguard', '59.99', '../images/cod.png', 'Call of Duty Vanguard cover art'),
(11, 3, 'Ori and the Will of the Wisps', '19.99', '../images/ori.png', 'Ori and the Will of the Wisps cover art'),
(12, 2, 'Marvel\'s Spider-Man: Miles Morales', '44.99', '../images/spiderman.png', 'Marvel\'s Spider-Man: Miles Morales cover art'),
(13, 3, 'It Takes Two', '37.99', '../images/takes2.png', 'It Takes Two cover art'),
(14, 3, 'Assasin\'s Creed Valhalla', '54.99', '../images/valhalla.png', 'Assasin\'s Creed Valhalla cover art'),
(15, 2, 'Resident Evil Village', '46.99', '../images/village.png', 'Resident Evil Village cover art'),
(16, 1, 'Luigi\'s Mansion 3', '54.99', '../images/luigi.png', 'Luigi\'s Mansion 3 cover art'),
(17, 3, 'Red Dead Redemption 2', '24.99', '../images/reddead.png', 'Red Dead Redemption 2 cover art'),
(18, 2, 'Ghost of Tsushima', '51.99', '../images/ghost.png', 'Ghost of Tsushima cover art');

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brandID` int(10) NOT NULL,
  `brandName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brandID`, `brandName`) VALUES
(1, 'Nintendo Switch'),
(2, 'PlayStation'),
(3, 'Xbox');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `brandID` (`brandID`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brandID`);


--
-- AUTO_INCREMENT for dumped tables
--


--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE `cart` ( /* Also need to look into UNIQUE keys! */
  `customerID` int(11) NOT NULL,  
  `productID` int(11) NOT NULL, 
  `subTotal` decimal(9,2) DEFAULT 0.00,
  `quantity` int(2) DEFAULT 0,
  `shipping` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cart` (`customerID`, `productID`, `subTotal`, `quantity`) VALUES
(1, 2, 59.99, 1),
(1, 3, 59.99, 1),
(4, 2, 59.99, 2);

CREATE TABLE `orderPayment` ( /* Billing info. Could modify to act as a receipt but eh... */
  /* `orderPaymentID` int(11) NOT NULL, ...???AUTO_INCREMENT */
  `customerID` int(11) NOT NULL,
  `cardNum` int(11) NOT NULL,
  `ccvNum` int(11) NOT NULL,
  `cardholderName` text DEFAULT NULL,
  `expDate` text DEFAULT NULL, /* hmm... calendar input? */
  `address` text DEFAULT NULL,
  `state` text DEFAULT NULL, /* 'Georgia' or 'GA'? */
  `city` text DEFAULT NULL,
  `zipCode` int(11) DEFAULT NULL,
  `total` decimal(9,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orderPayment` (`customerID`, `cardNum`, `ccvNum`, `cardholderName`, `expDate`, `address`, `state`, `city`, `zipCode`, `total`) VALUES
(1, 1234123412341234, 129, 'Clara Zhang', '05/23', '1000 Example Road', 'Georgia', 'Suwanee', '30025', 12.99),
(4, 4321432143214321, 420, 'Test Tester', '12/25', '9999 Something Street', 'California', 'Los Angeles', '19201', 4.46),
(1, 1234123412341234, 129, 'Clara Zhang', '05/23', '1000 Example Road', 'Georgia', 'Suwanee', '30025', 8.64);
