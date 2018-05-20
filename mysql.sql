-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Erstellungszeit: 07. Mai 2018 um 10:10
-- Server-Version: 5.6.38
-- PHP-Version: 7.2.1

-- erstellen einer datenbank namens projekt im sql des Projekts dieses skript ausführen
# Rechte für `project`@`localhost`
GRANT ALL PRIVILEGES ON *.* TO 'project'@'localhost' IDENTIFIED BY PASSWORD '*2F43412C5E1CEE672E9E52E89BCD580DC49B8B94' WITH GRANT OPTION;

GRANT ALL PRIVILEGES ON `project`.* TO 'project'@'localhost';

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `project`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `selector` char(40) DEFAULT NULL,
  `hashedValidator` char(64) DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `auth_tokens`
--

INSERT INTO `auth_tokens` (`id`, `selector`, `hashedValidator`, `userid`, `expires`) VALUES
(23, '35ebc43ed32e2bd22aba48f3075ce1b8322a5a6c', '$2y$10$2q/DuF56q9.xc8C1acC9G.8BnJ3uy874lEZzd3f5ECCGEPwD4PkMi', 1, 1526052543),
(24, 'b744d92df9ef45a93bad27b90255b1c0358ef7b3', '$2y$10$1.nzpO.3ddBF8i2DMLSlnuE4qDRCrT/4t/isf1ZqjAxqkNQG.ZsxO', 1, 1526578295);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konten`
--

CREATE TABLE `konten` (
  `kontoID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `kontoName` varchar(20) DEFAULT NULL,
  `kontoType` tinyint(1) DEFAULT NULL,
  `stand` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `konten`
--

INSERT INTO `konten` (`kontoID`, `userID`, `kontoName`, `kontoType`, `stand`) VALUES
(1, 1, 'Hauptkonto', 0, 2000),
(2, 1, 'danie', 0, 100),
(3, 1, 'Alex', 0, 100);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `prename` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `master` int(11) DEFAULT NULL,
  `isVerified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `prename`, `lastname`, `email`, `password`, `master`, `isVerified`) VALUES
(1, 'Daniel', 'Pfeffer', 'daniel.pfeffer@breathless-pictures.at', '$2y$10$zI42Ew1/mlXMugGCey8L7u/iCp6IRhTgGToEDzPmkNBjBsf.zN/yq', NULL, 0),
(2, 'Hans Peter', 'Pfeffer', 'hanspeter.pfeffer@breathless-pictures.at', '$2y$10$Rty6WBcJ3br3henkFXpEU.WCpU.bnFh6zaadkZaDB5kUte0GPFBLG', NULL, 0),
(3, 'Daniel', 'Pfeffer', 'daniel.pfeffer@gmail.com', '$2y$10$hfIfjlo00iCo5qjn6itVbe1zPkQ5e5DT0mGLeeRTxpdFSSKhC6vPq', NULL, 0),
(4, 'Julian', 'Danninger', 'julian.danninger@gmail.com', '$2y$10$e1.Wgi3iH4JYqkn24HttLumqYM7XAeHo2gxLokSm0ctQC/TGrPC0S', NULL, 0),
(5, 'Basti', 'Schief', 'bast.schief@gmail.com', '$2y$10$EG5ucz.z/J6ASfeNdw/plu51.jzTQtmYIE5TQ0XXOE9MHtA/5/e3O', NULL, 0),
(6, 'Stefan', 'Scharinger', 'stefan.scharez@scharez.at', '$2y$10$3VlnKpr6rTo4Wtj3vcM.y.ke/8En6IGUDPdGhRn4uyTHlaNZ90lYy', NULL, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid_id_foreign` (`userid`);

--
-- Indizes für die Tabelle `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`kontoID`),
  ADD KEY `userIDKont_id_foreign` (`userID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `master_ID_Foreign` (`master`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT für Tabelle `konten`
--
ALTER TABLE `konten`
  MODIFY `kontoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `userid_id_foreign` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `konten`
--
ALTER TABLE `konten`
  ADD CONSTRAINT `userIDKont_id_foreign` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `master_ID_Foreign` FOREIGN KEY (`master`) REFERENCES `user` (`id`);
