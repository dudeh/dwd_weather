-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: 002.mysql.db.fge.5hosting.com
-- Erstellungszeit: 14. Jan 2018 um 22:20
-- Server Version: 5.5.51-cll-lve
-- PHP-Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `db388_wetter`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messgroessen`
--

CREATE TABLE IF NOT EXISTS `messgroessen` (
`mess_pk` int(11) NOT NULL,
  `beschreibung` varchar(1000) NOT NULL,
  `code` varchar(100) NOT NULL,
  `einheit` varchar(10) NOT NULL,
  `type` enum('FORECAST','REAL') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=341 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messwerte`
--

CREATE TABLE IF NOT EXISTS `messwerte` (
`pk` int(11) NOT NULL,
  `station_fk` int(11) NOT NULL,
  `messgroesse_fk` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zeit` time NOT NULL,
  `wert` float(11,6) NOT NULL,
  `type` enum('FORECAST','REAL') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1079170 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stationen`
--

CREATE TABLE IF NOT EXISTS `stationen` (
`stat_pk` int(11) NOT NULL,
  `stationsname` varchar(100) NOT NULL,
  `stations_id` int(11) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `elevation` int(11) NOT NULL,
  `land_kurz` varchar(10) NOT NULL,
  `land` varchar(100) NOT NULL,
  `kontinent` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22010 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messgroessen`
--
ALTER TABLE `messgroessen`
 ADD PRIMARY KEY (`mess_pk`), ADD UNIQUE KEY `Messgroesse_Kurz_UK` (`code`);

--
-- Indexes for table `messwerte`
--
ALTER TABLE `messwerte`
 ADD PRIMARY KEY (`pk`), ADD UNIQUE KEY `Station_Zeit_Datum_Messgroesse_Type_UK` (`station_fk`,`messgroesse_fk`,`datum`,`zeit`,`type`), ADD KEY `station_fk` (`station_fk`);

--
-- Indexes for table `stationen`
--
ALTER TABLE `stationen`
 ADD PRIMARY KEY (`stat_pk`), ADD UNIQUE KEY `Stations_ID_UK` (`stations_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messgroessen`
--
ALTER TABLE `messgroessen`
MODIFY `mess_pk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=341;
--
-- AUTO_INCREMENT for table `messwerte`
--
ALTER TABLE `messwerte`
MODIFY `pk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1079170;
--
-- AUTO_INCREMENT for table `stationen`
--
ALTER TABLE `stationen`
MODIFY `stat_pk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22010;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
