-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 27 mars 2026 à 20:39
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mairie`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande_extrait`
--

DROP TABLE IF EXISTS `demande_extrait`;
CREATE TABLE IF NOT EXISTS `demande_extrait` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` text NOT NULL,
  `email` text NOT NULL,
  `telephone` int NOT NULL,
  `statut` text NOT NULL,
  `nom_pere` text NOT NULL,
  `nom_mere` text NOT NULL,
  `sexe` text NOT NULL,
  `date_demande` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demande_extrait`
--

INSERT INTO `demande_extrait` (`id`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `email`, `telephone`, `statut`, `nom_pere`, `nom_mere`, `sexe`, `date_demande`) VALUES
(1, '', '', '0000-00-00', '', '', 0, 'en attente', '', '', '', '0000-00-00 00:00:00'),
(2, '', '', '0000-00-00', '', '', 0, 'en attente', '', '', '', '0000-00-00 00:00:00'),
(3, 'Niang', 'Zeynabou', '2004-11-05', 'sacre_coeur', 'niangzeynabou71@gmail.com', 2147483647, 'validé', 'modou', 'fatou', 'feminin', '2026-03-26 21:05:42'),
(4, 'ngom', 'modou', '1999-05-12', 'sacre_coeur', 'ngom@gmail.com', 771000512, 'validé', 'Aliou', 'Coumba', 'masculin', '2026-03-26 21:05:42'),
(5, 'Niang', 'Zeynabou', '2004-11-05', 'sacre_coeur', 'niangzeynabou71@gmail.com', 2147483647, 'validé', 'mohamed', 'fatima', 'feminin', '2026-03-26 21:05:42'),
(6, 'thiam', 'lamine', '2003-02-14', 'sacre_coeur', 'thiamlamine@gmail.com', 771550023, 'En attente', 'Alassane', 'Fanta', 'masculin', '0000-00-00 00:00:00'),
(7, 'thiam', 'lamine', '2003-02-14', 'sacre_coeur', 'thiamlamine@gmail.com', 771550023, 'En attente', 'Alassane', 'Fanta', 'masculin', '0000-00-00 00:00:00'),
(8, 'Niang', 'Zeynabou', '2004-11-05', 'sacre_coeur', 'niangzeynabou71@gmail.com', 2147483647, 'validé', 'ibrahim', 'Assia', 'feminin', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `naissance`
--

DROP TABLE IF EXISTS `naissance`;
CREATE TABLE IF NOT EXISTS `naissance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_bebe` text NOT NULL,
  `date_naissance` date NOT NULL,
  `nom_pere` text NOT NULL,
  `nom_mere` text NOT NULL,
  `adresse` text NOT NULL,
  `sexe` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `naissance`
--

INSERT INTO `naissance` (`id`, `nom_bebe`, `date_naissance`, `nom_pere`, `nom_mere`, `adresse`, `sexe`) VALUES
(1, 'zeynabou', '2004-11-05', 'moussa', 'fatou', 'sacre coeur', '');

-- --------------------------------------------------------

--
-- Structure de la table `suivie`
--

DROP TABLE IF EXISTS `suivie`;
CREATE TABLE IF NOT EXISTS `suivie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_demande` int NOT NULL,
  `statut` text NOT NULL,
  `date_suivi` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_villa` int NOT NULL,
  `nom` text NOT NULL,
  `password` text NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `numero_villa`, `nom`, `password`, `role`) VALUES
(1, 8552, 'zeynabou niang', '$2y$10$KNoHvgTFz1UYrbwgX6xmSuvB/REL/lq/e0RNptPv90fxo41sHblQS', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
