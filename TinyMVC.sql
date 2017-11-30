-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 24 Août 2015 à 10:10
-- Version du serveur: 5.5.44-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `2iMVC`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clé primaire, identifiant numérique auto incrémenté',
  `pseudo` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'pseudo',
  `passe` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'mot de passe',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indique si l''utilisateur est un administrateur',
  `connecte` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indique si l''utilisateur est connecte',
  `couleur` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'black' COMMENT 'indique la couleur préférée de l''utilisateur, en anglais',
  `blacklist` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `passe`, `admin`, `connecte`, `couleur`, `blacklist`) VALUES
(3, 'tom', 'ig2i', 0, 0, 'orange', 0),
(6, 'admin', 'admin', 1, 0, 'black', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
