-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 21 Sie 2022, 16:49
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `klimo`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ventilators`
--

CREATE TABLE `ventilators` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT 'wyłączony/włączony',
  `temperature` int(255) NOT NULL COMMENT 'Temperatura urządzenia ',
  `fan_power` int(255) NOT NULL COMMENT 'Moc wentylatora',
  `winding_direction` varchar(30) NOT NULL COMMENT 'Są 5 kierunków: Front, right, left, down, top',
  `mode_settings` varchar(30) NOT NULL COMMENT 'Są 4 tryby: cool, auto, heat, dry'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `ventilators`
--

INSERT INTO `ventilators` (`id`, `status`, `temperature`, `fan_power`, `winding_direction`, `mode_settings`) VALUES
(1, 0, 100, 30, 'right', 'auto'),
(2, 0, 20, 100, 'down', 'heat'),
(3, 1, 20, 100, 'down', 'heat'),
(4, 1, 20, 100, 'down', 'heat'),
(5, 1, 20, 101, 'down', 'heat');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `ventilators`
--
ALTER TABLE `ventilators`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `ventilators`
--
ALTER TABLE `ventilators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
