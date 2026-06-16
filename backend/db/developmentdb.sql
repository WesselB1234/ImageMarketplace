-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 16 jun 2026 om 13:34
-- Serverversie: 12.0.2-MariaDB-ubu2404
-- PHP-versie: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Images`
--

CREATE TABLE `Images` (
  `image_id` int(11) UNSIGNED NOT NULL,
  `owner_id` int(11) UNSIGNED DEFAULT NULL,
  `creator_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `is_moderated` tinyint(1) DEFAULT NULL,
  `is_onsale` tinyint(1) DEFAULT NULL,
  `time_created` datetime DEFAULT current_timestamp(),
  `alt_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Images`
--

INSERT INTO `Images` (`image_id`, `owner_id`, `creator_id`, `name`, `description`, `price`, `is_moderated`, `is_onsale`, `time_created`, `alt_text`) VALUES
(1, 1, 1, 'TestImage', 'This image does not have a .png file yet. You can make a new image containing a .png by uploading a new one.', NULL, 0, 0, '2026-04-25 16:10:11', 'This image does not have a .png file yet. You can make a new image containing a .png by uploading a new one.');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20260614193010, 'Migration', '2026-06-16 13:24:12', '2026-06-16 13:24:12', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image_tokens` int(11) DEFAULT NULL,
  `role` enum('User','Admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `image_tokens`, `role`) VALUES
(1, 'admin', '$2y$12$D.Ju/TZfyqx2mq.0pu.pcebxBWxN0nQx6zuxlfpqF48VvuXnOpiyG', 100000, 'Admin'),
(2, 'user', '$2y$12$exTQCqOyLIxAg4IE0ZCoA.7QSLXlMTdg5oiKbXRNRijqo6eXy5p1.', 1000, 'User');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexen voor tabel `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Indexen voor tabel `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Images`
--
ALTER TABLE `Images`
  MODIFY `image_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `Images`
--
ALTER TABLE `Images`
  ADD CONSTRAINT `Images_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `Users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `Images_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `Users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
