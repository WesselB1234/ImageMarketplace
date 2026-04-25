-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 25 apr 2026 om 16:28
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
  `id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `is_moderated` tinyint(1) NOT NULL,
  `is_onsale` tinyint(1) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT current_timestamp(),
  `alt_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Images`
--

INSERT INTO `Images` (`id`, `owner_id`, `creator_id`, `name`, `description`, `price`, `is_moderated`, `is_onsale`, `time_created`, `alt_text`) VALUES
(1, 54, 54, 'TestImage', 'TestImage', NULL, 0, 0, '2026-04-25 16:10:11', 'TestImage alt text');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image_tokens` int(11) NOT NULL,
  `role` enum('User','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `image_tokens`, `role`) VALUES
(54, 'admin', '$2y$12$D.Ju/TZfyqx2mq.0pu.pcebxBWxN0nQx6zuxlfpqF48VvuXnOpiyG', 100000, 'Admin'),
(55, 'user', '$2y$12$exTQCqOyLIxAg4IE0ZCoA.7QSLXlMTdg5oiKbXRNRijqo6eXy5p1.', 1000, 'User');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Images_ibfk_1` (`owner_id`),
  ADD KEY `Images_ibfk_2` (`creator_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT voor een tabel `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

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
