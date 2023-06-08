-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2023 at 09:55 PM
-- Server version: 10.3.39-MariaDB
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `steinbergkarina_haaletus2`
--

-- --------------------------------------------------------

--
-- Table structure for table `HAALETUS`
--

CREATE TABLE `HAALETUS` (
  `Haaletaja_Eesnimi` varchar(100) NOT NULL,
  `Haaletaja_Perenimi` varchar(100) NOT NULL,
  `Haaletuse_aeg` datetime NOT NULL,
  `Otsus` enum('Poolt','Vastu') NOT NULL,
  `Haaletaja_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Triggers `HAALETUS`
--
DELIMITER $$
CREATE TRIGGER `insert_logi` AFTER INSERT ON `HAALETUS` FOR EACH ROW INSERT INTO LOGI (Haaletaja_id, Haaletaja_Eesnimi, Haaletaja_Perenimi, Otsus, H_alguse_aeg, H_lopu_aeg) VALUES (NEW.Haaletaja_id, NEW.Haaletaja_Eesnimi, NEW.Haaletaja_Perenimi, NEW.Otsus, NOW(), NOW() + INTERVAL 5 MINUTE)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_tulemused` AFTER INSERT ON `HAALETUS` FOR EACH ROW IF (
        SELECT COUNT(*)
        FROM TULEMUSED
    ) < 1 THEN
        INSERT INTO TULEMUSED (
            Tulemuse_id,
            Haaletanute_arv,
            H_alguse_aeg,
            H_lopu_aeg,
            Poolt,
            Vastu
        )
        VALUES (
             (1
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
            ),
            (
                SELECT MIN(H_alguse_aeg)
                FROM LOGI
            ),
            (
                SELECT MIN(H_lopu_aeg)
                FROM LOGI
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Poolt'
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Vastu'
            )
        );
    ELSE
        UPDATE TULEMUSED
        SET
            Haaletanute_arv = (
                SELECT COUNT(*)
                FROM HAALETUS
            ),
            H_alguse_aeg = (
                SELECT MIN(H_alguse_aeg)
                FROM LOGI
            ),
            H_lopu_aeg = (
                SELECT MIN(H_lopu_aeg)
                FROM LOGI
            ),
            Poolt = (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Poolt'
            ),
            Vastu = (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Vastu'
            )
        WHERE Tulemuse_id = 1;
    END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `piiraja` BEFORE INSERT ON `HAALETUS` FOR EACH ROW BEGIN
  SELECT COUNT(*) INTO @loendur FROM HAALETUS;
  IF @loendur >= 11 THEN
    CALL sth(); -- raise an error
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_logi` AFTER UPDATE ON `HAALETUS` FOR EACH ROW INSERT INTO LOGI (Haaletaja_id, Haaletaja_Eesnimi, Haaletaja_Perenimi, Otsus, H_alguse_aeg, H_lopu_aeg) VALUES (NEW.Haaletaja_id, NEW.Haaletaja_Eesnimi, NEW.Haaletaja_Perenimi, NEW.Otsus, NOW(), NOW() + INTERVAL 5 MINUTE)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tulemused` AFTER UPDATE ON `HAALETUS` FOR EACH ROW IF (
        SELECT COUNT(*)
        FROM TULEMUSED
    ) < 1 THEN
        INSERT INTO TULEMUSED (
            Tulemuse_id,
            Haaletanute_arv,
            H_alguse_aeg,
            H_lopu_aeg,
            Poolt,
            Vastu
        )
        VALUES (
             (1
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
            ),
            (
                SELECT MIN(H_alguse_aeg)
                FROM LOGI
            ),
            (
                SELECT MIN(H_lopu_aeg)
                FROM LOGI
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Poolt'
            ),
            (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Vastu'
            )
        );
    ELSE
        UPDATE TULEMUSED
        SET
            Haaletanute_arv = (
                SELECT COUNT(*)
                FROM HAALETUS
            ),
            H_alguse_aeg = (
                SELECT MIN(H_alguse_aeg)
                FROM LOGI
            ),
            H_lopu_aeg = (
                SELECT MIN(H_lopu_aeg)
                FROM LOGI
            ),
            Poolt = (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Poolt'
            ),
            Vastu = (
                SELECT COUNT(*)
                FROM HAALETUS
                WHERE Otsus = 'Vastu'
            )
        WHERE Tulemuse_id = 1;
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `LOGI`
--

CREATE TABLE `LOGI` (
  `Logi_id` int(3) NOT NULL,
  `Haaletaja_Eesnimi` varchar(100) NOT NULL,
  `Haaletaja_Perenimi` varchar(100) NOT NULL,
  `Haaletaja_id` int(3) NOT NULL,
  `H_alguse_aeg` datetime NOT NULL,
  `H_lopu_aeg` datetime NOT NULL,
  `Otsus` enum('Poolt','Vastu') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TULEMUSED`
--

CREATE TABLE `TULEMUSED` (
  `Tulemuse_id` int(2) NOT NULL,
  `Haaletanute_arv` int(2) NOT NULL,
  `H_alguse_aeg` datetime NOT NULL,
  `H_lopu_aeg` datetime NOT NULL,
  `Poolt` int(2) NOT NULL,
  `Vastu` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `HAALETUS`
--
ALTER TABLE `HAALETUS`
  ADD PRIMARY KEY (`Haaletaja_id`);

--
-- Indexes for table `LOGI`
--
ALTER TABLE `LOGI`
  ADD PRIMARY KEY (`Logi_id`);

--
-- Indexes for table `TULEMUSED`
--
ALTER TABLE `TULEMUSED`
  ADD PRIMARY KEY (`Tulemuse_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `HAALETUS`
--
ALTER TABLE `HAALETUS`
  MODIFY `Haaletaja_id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LOGI`
--
ALTER TABLE `LOGI`
  MODIFY `Logi_id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `TULEMUSED`
--
ALTER TABLE `TULEMUSED`
  MODIFY `Tulemuse_id` int(2) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
