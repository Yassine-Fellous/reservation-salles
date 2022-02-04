-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 30 jan. 2022 à 00:53
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reservationsalles`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES
(1, 'Karting 50cc', 'course pour les og', '2022-01-13 15:00:15', '2022-01-14 16:00:15', 2),
(3, 'Intervention DDOS le parisien', 'Besoin de l\'office pour lancer le DDOS avec mes Botnet. Gregory Chelli', '2022-01-17 08:00:00', '2022-01-17 09:00:00', 3),
(4, 'réservation', 'réservation pour le bail', '2022-01-25 09:00:00', '2022-01-25 10:00:00', 1),
(20, 'testZAD', 'La ZAD', '2022-01-26 09:00:00', '2022-01-26 10:00:00', 4),
(33, 'kkk', 'kkk', '2022-01-27 13:00:00', '2022-01-27 15:00:00', 4),
(29, 'lavraie', 'ouais ouais', '2022-01-26 16:00:00', '2022-01-26 17:00:00', 4),
(30, 'kkk', 'kkk', '2022-01-27 08:00:00', '2022-01-27 09:00:00', 4),
(31, 'kkk', 'kkk', '2022-01-27 08:00:00', '2022-01-27 09:00:00', 4),
(32, 'kkk2', 'kkk', '2022-01-27 08:00:00', '2022-01-27 09:00:00', 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `password`) VALUES
(1, 'TEST2', '098f6bcd4621d373cade4e832627b4f6'),
(2, 'test3', 'test3'),
(3, 'Greg', '8da255ba9c9791d7d0ec10305d30670e'),
(4, 'marcus', '49c167d7cd66dc64a474c261860ba50f');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
