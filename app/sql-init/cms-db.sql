-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Värd: mysql
-- Tid vid skapande: 03 apr 2023 kl 11:41
-- Serverversion: 10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- PHP-version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `cms-db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `page`
--

CREATE TABLE `page` (
  `page_id` int(10) UNSIGNED NOT NULL,
  `page_name` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `menu_priority` int(11) DEFAULT NULL,
  `tiny_mce` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumpning av Data i tabell `page`
--

INSERT INTO `page` (`page_id`, `page_name`, `created_at`, `updated_at`, `published`, `content`, `user_id`, `menu_priority`, `tiny_mce`) VALUES
(2, 'Contact', '2023-03-20 14:06:59', '2023-03-31 10:14:45', 1, '<h1 style=\"text-align: center;\">Contact page</h1>', 18, 5, 1),
(4, 'Home', '2023-03-21 11:37:29', '2023-03-31 10:17:58', 1, '<h1 style=\"text-align: center;\">Welcome to this website!</h1>\r\n<p><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"../cms-content/uploads/eifeltower.jpeg\" alt=\"Alt Text\" /></p>\r\n<p>Here is some content to this homepage.</p>\r\n<ul>\r\n<li>We all love paris</li>\r\n<li>Paris is nice</li>\r\n<li>We all need to travel to paris</li>\r\n</ul>\r\n<h2 style=\"text-align: center;\">Copyright 🤓</h2>', 18, 1, 1),
(6, 'About', '2023-03-23 13:37:15', '2023-04-03 11:31:22', 1, '<h1 style=\"text-align: center;\">About us</h1>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 32.1463%; text-align: center;\">Adress 1<br />Postcode City<br />Country</td>\r\n<td style=\"width: 32.1463%; text-align: center;\">Some more info could be here</td>\r\n<td style=\"width: 32.1463%; text-align: center;\">And also here</td>\r\n</tr>\r\n</tbody>\r\n</table>', 18, 3, 1),
(14, 'Page', '2023-03-29 10:54:57', '2023-03-31 12:38:46', 1, '<h1 style=\"text-align: center;\"><strong>Hello!<br /><br />😀<br /></strong></h1>', 18, 4, 1),
(15, 'TinyMCE', '2023-03-29 11:47:33', '2023-03-29 12:41:19', 1, '<h1 style=\"text-align: center;\"><span style=\"color: #000000;\">This is a page heading</span></h1>\r\n<p> </p>\r\n<p> hello</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 54.3906px; background-color: #f8cac6; border-color: #e03e2d; border-style: double;\" border=\"1\" cellpadding=\"100px\">\r\n<tbody>\r\n<tr style=\"height: 54.3906px;\">\r\n<td style=\"width: 32.1978%; height: 54.3906px;\">\r\n<p style=\"text-align: center;\"><span style=\"color: #000000;\">Here comes some content to the page.</span></p>\r\n</td>\r\n<td style=\"width: 32.1978%; text-align: center; height: 54.3906px;\">🥳</td>\r\n<td style=\"width: 32.1978%; text-align: center; height: 54.3906px;\">Some more content here</td>\r\n</tr>\r\n</tbody>\r\n</table>', 18, 5, NULL),
(23, 'Draft', '2023-03-31 12:37:45', NULL, 0, '<h1 class=\"text-2xl\" style=\"text-align: center;\">This is a draft🤠</h1>', 18, NULL, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `position` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumpning av Data i tabell `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `firstname`, `lastname`, `position`) VALUES
(18, 'annalindqvist_@live.se', '$2y$10$PcTO5VhyeZ283HlM0YYXTuG0qefIsNkNi0QNaRweYgNESBBQzb91C', 'Anna', 'Lindqvist', 1),
(19, 'example9@mail.com', '$2y$10$BUjuQouDkMa8fPF7RdpjsOuEeoPLT64IshjpZlIWWF/XUq8EziUhS', 'Peter', 'Pan', 0),
(28, 'test@mejl.se', '$2y$10$i1crWknbcTKY6QOZx6p/jeIsN808F3XTR9ERMG5k6mQlG6.0SR4Xa', 'Lille', 'Skutt', 0),
(29, 'jacke@mejl.com', '$2y$10$fdwg1toVAHfjEJ9zQaVBC.YPbSY2pqhZy8MK/Ls3.Jg/I82yukjsy', 'Jacke', 'Mellqvist', 0),
(31, 'test2@test.se', '$2y$10$Io4H09UUd0PJokkSkEUak.a1a6fh2zzalPepzECPdBEeL5xdKOO.G', 'Banana', 'Orangesson', 1);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `text_UNIQUE` (`page_name`),
  ADD UNIQUE KEY `page_name` (`page_name`),
  ADD KEY `fk_page_user_idx` (`user_id`);

--
-- Index för tabell `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `page`
--
ALTER TABLE `page`
  MODIFY `page_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT för tabell `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `fk_page_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
