-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 22 fév. 2025 à 21:49
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_commune`
--

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

DROP TABLE IF EXISTS `connexion`;
CREATE TABLE IF NOT EXISTS `connexion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`id`, `nom`, `email`, `password`, `role`) VALUES
(22, 'samb', 'i.s.papy97@gmail.com', '$2y$10$l5KiUyw58BFb.hHVan3zDO8bsmYlria81nxTAFEnoEolBOpQeB5ZO', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `type_demande` text NOT NULL,
  `numero_registre` varchar(20) DEFAULT NULL,
  `date_demande` date NOT NULL,
  `statut` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `nom`, `telephone`, `date_naissance`, `type_demande`, `numero_registre`, `date_demande`, `statut`) VALUES
(19, 'samb', '+221771664876', '2023-01-11', 'Extrait de naissance', '574/1997', '2025-02-11', 'En attente'),
(12, 'samb', '+221775849570', '2020-02-07', 'Carte d\'identitÃ©', NULL, '2025-02-09', 'ValidÃ©e'),
(13, 'samb', '+221775849570', '2022-07-09', 'Certificat de rÃ©sidence', NULL, '2025-02-09', 'RejetÃ©e'),
(14, 'samb', '+221775849570', '2024-05-10', 'Certificat de rÃ©sidence', NULL, '2025-02-12', 'En attente'),
(15, 'samb', '+221775849570', '2024-05-10', 'Certificat de rÃ©sidence', NULL, '2025-02-12', 'RejetÃ©e'),
(20, 'Banda Niang', '+221771664876', '2022-02-11', 'Extrait de naissance', '574/1997', '2025-02-11', 'En attente');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
