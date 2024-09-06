-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 06, 2024 at 01:45 PM
-- Server version: 11.1.5-MariaDB-deb12
-- PHP Version: 8.2.21
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `twitter`
--

-- --------------------------------------------------------
--
-- Table structure for table `errors`
--

CREATE TABLE `errors` (
  `id` int(11) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `foutmelding` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `errors`
--

INSERT INTO `errors` (`id`, `titel`, `foutmelding`)
VALUES (
    1,
    'Gebruiker niet gevonden',
    'De opgegeven gebruiker bestaat niet. Controleer de gegevens, en probeer het vervolgens opnieuw.'
  ),
  (
    2,
    'Wachtwoord onjuist',
    'Het opgegeven wachtwoord is incorrect. Vul je gegevens opnieuw in, en probeer het vervolgens opnieuw.'
  ),
  (
    3,
    'Verbinding mislukt',
    'Het is niet gelukt om verbinding te maken met de database. Probeer het later opnieuw.'
  ),
  (
    4,
    'Wachtwoorden komen niet overeen',
    'De opgegeven wachtwoorden komen niet overeen. Controleer de gegevens en probeer het opnieuw.'
  ),
  (
    5,
    'Gebruiker bestaat al',
    'De gebruikersnaam die je probeerde te gebruiken bestaat al. Kies een andere gebruikersnaam en probeer het opnieuw.'
  ),
  (
    6,
    'Post versturen mislukt',
    'Het is niet gelukt om jouw post te versturen. Probeer het later opnieuw.'
  ),
  (
    7,
    'Kon de post niet liken.',
    'Het is niet gelukt om de post te liken. Probeer het later opnieuw.'
  ),
  (
    8,
    'Versturen mislukt',
    'Het is niet gelukt om jouw post te versturen. Probeer het later opnieuw.'
  ),
  (
    9,
    'Kon status niet aanpassen',
    'Het is niet gelukt om jouw status aan te passen. Probeer het later opnieuw.'
  );
-- --------------------------------------------------------
--
-- Table structure for table `gebruikers`
--

CREATE TABLE `gebruikers` (
  `idGebruiker` int(11) NOT NULL,
  `naam` varchar(128) NOT NULL,
  `status` varchar(128) DEFAULT NULL,
  `wachtwoord` varchar(60) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `idPost` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `body` varchar(512) NOT NULL,
  `likes` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `repliesTo` int(11) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (
    `idPost`,
    `auteur`,
    `body`,
    `likes`,
    `timestamp`,
    `repliesTo`
  )
VALUES (
    46,
    19,
    'PhpMyAdmin is overrated.\nIt just deleted my database.',
    100,
    '2024-06-11 16:59:23',
    NULL
  ),
  (
    48,
    21,
    '@Izaak rip u',
    101,
    '2024-06-11 17:37:08',
    46
  ),
  (49, 19, '@itje ja', 0, '2024-06-11 18:59:30', 48),
  (
    50,
    21,
    '@Izaak yesyyeyysyeysyeysyeysyesyyeysyeysy',
    0,
    '2024-06-11 19:00:19',
    49
  ),
  (
    51,
    19,
    '@itje realllll',
    2,
    '2024-06-11 19:33:40',
    50
  ),
  (
    52,
    22,
    'realllllllllllllllllllllllllllllllllllllllllllllllllllll',
    0,
    '2024-06-13 07:12:31',
    NULL
  ),
  (
    53,
    19,
    'jasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerkljasdfjiodgjerklja',
    5,
    '2024-06-13 07:34:30',
    NULL
  ),
  (
    72,
    19,
    '@Izaak yes',
    0,
    '2024-06-13 13:00:47',
    46
  ),
  (
    73,
    20,
    '@Izaak wtf!',
    0,
    '2024-06-13 17:29:12',
    51
  ),
  (
    74,
    19,
    '@Robin ja echt',
    0,
    '2024-06-14 07:50:38',
    73
  ),
  (
    75,
    19,
    'Dit is zeer dood\n\nAls je dit leest, heb ik één ding te zeggen: &quot;Kerstboom.&quot;',
    0,
    '2024-08-30 21:39:24',
    NULL
  ),
  (101, 24, 'weed', 0, '2024-09-02 12:08:20', NULL),
  (
    102,
    19,
    'oh hell no',
    0,
    '2024-09-02 12:08:55',
    NULL
  );
-- --------------------------------------------------------
--
-- Table structure for table `toast`
--

CREATE TABLE `toast` (
  `id` int(11) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'check_circle',
  `type` varchar(50) NOT NULL DEFAULT 'succes'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `toast`
--

INSERT INTO `toast` (`id`, `caption`, `icon`, `type`)
VALUES (1, 'Account aangemaakt', 'person_add', 'succes'),
  (2, 'Je bent ingelogd', 'login', 'succes'),
  (3, 'Post geplaatst', 'check_circle', 'succes'),
  (4, 'Post geliked', 'thumb_up', 'melding'),
  (5, 'Reactie geplaatst', 'reply', 'succes'),
  (6, 'Je bent uitgelogd', 'logout', 'melding'),
  (
    7,
    'Post succesvol verwijderd',
    'delete',
    'succes'
  ),
  (8, 'Status geüpdatet', 'check_circle', 'melding'),
  (
    9,
    'Je moet ingelogd zijn',
    'warning',
    'waarschuwing'
  );
--
-- Indexes for dumped tables
--

--
-- Indexes for table `errors`
--
ALTER TABLE `errors`
ADD PRIMARY KEY (`id`);
--
-- Indexes for table `gebruikers`
--
ALTER TABLE `gebruikers`
ADD PRIMARY KEY (`idGebruiker`),
  ADD UNIQUE KEY `naam` (`naam`);
--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
ADD PRIMARY KEY (`idPost`);
--
-- Indexes for table `toast`
--
ALTER TABLE `toast`
ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `errors`
--
ALTER TABLE `errors`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 10;
--
-- AUTO_INCREMENT for table `gebruikers`
--
ALTER TABLE `gebruikers`
MODIFY `idGebruiker` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 26;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 169;
--
-- AUTO_INCREMENT for table `toast`
--
ALTER TABLE `toast`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 10;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;