-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1:3307
-- Généré le :  Jeu 24 Avril 2025 à 18:36
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `easydoc`
--

-- --------------------------------------------------------

--
-- Structure de la table `bankaccounts`
--

CREATE TABLE IF NOT EXISTS `bankaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` int(11) NOT NULL,
  `rib` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `codecl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iff` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateadd` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rs` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `capital` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `patente` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iff` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cnss` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facture` int(11) NOT NULL,
  `devis` int(11) NOT NULL,
  `avoir` int(11) NOT NULL,
  `br` int(11) NOT NULL,
  `factureproforma` int(11) NOT NULL,
  `bl` int(11) NOT NULL,
  `bs` int(11) NOT NULL,
  `bc` int(11) NOT NULL,
  `bre` int(11) NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `detailsdocuments`
--

CREATE TABLE IF NOT EXISTS `detailsdocuments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `uprice` double NOT NULL,
  `tva` int(11) NOT NULL,
  `discounttype` varchar(255) NOT NULL,
  `discount` double NOT NULL,
  `tprice` double NOT NULL,
  `client` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `typedoc` varchar(255) NOT NULL,
  `dateadd` varchar(255) NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `state` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `client` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `note` text NOT NULL,
  `modepayment` text NOT NULL,
  `conditions` text NOT NULL,
  `abovetable` text NOT NULL,
  `correctdoc` varchar(255) NOT NULL,
  `attachments` text NOT NULL,
  `user` int(11) NOT NULL,
  `dateadd` varchar(255) NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `infodocs`
--

CREATE TABLE IF NOT EXISTS `infodocs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` int(11) NOT NULL,
  `document` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modepayment` text COLLATE utf8_unicode_ci NOT NULL,
  `conditions` text COLLATE utf8_unicode_ci NOT NULL,
  `abovetable` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `infodocs`
--

INSERT INTO `infodocs` (`id`, `company`, `document`, `modepayment`, `conditions`, `abovetable`) VALUES
(1, 1, 'facture', '<p>ok 2</p>', '<p>ok 3</p>', '<p>ok 1</p>'),
(2, 1, 'devis', '<p>ok 2</p>', '<p>ok 3</p>', '<p>ok 1</p>');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facture` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `avoir` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `caissein` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `caisseout` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `unpaid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `inwaiting` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `outwaiting` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rcaissein` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rcaisseout` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `remis` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rremis` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `incach` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `outcach` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `notifications`
--

INSERT INTO `notifications` (`id`, `facture`, `avoir`, `caissein`, `caisseout`, `unpaid`, `inwaiting`, `outwaiting`, `rcaissein`, `rcaisseout`, `remis`, `rremis`, `incach`, `outcach`) VALUES
(1, 'on-1', 'on-1', 'on-1', 'on-1', 'on-0', 'off-0', 'off-0', 'on-1', 'on-1', 'on-undefin', 'off-0', 'off-0', 'off-0');

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `nbrows` int(11) NOT NULL,
  `rowcolor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `parametres`
--

INSERT INTO `parametres` (`id`, `user`, `nbrows`, `rowcolor`) VALUES
(3, 4, 100, 1);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `doc` int(11) NOT NULL,
  `worker` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `nature` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `modepayment` varchar(255) NOT NULL,
  `imputation` varchar(255) NOT NULL,
  `rib` int(11) NOT NULL,
  `invoiced` varchar(255) NOT NULL DEFAULT 'Oui',
  `paid` int(11) NOT NULL,
  `tva` double NOT NULL,
  `remis` int(11) NOT NULL,
  `dateremis` varchar(255) NOT NULL,
  `nremise` varchar(255) NOT NULL,
  `company` int(11) NOT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `typedoc` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `dateadd` varchar(255) NOT NULL,
  `datedue` varchar(255) NOT NULL,
  `datepaid` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `attachments` text NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `store` varchar(255) NOT NULL,
  `oneprice` int(11) NOT NULL,
  `onlyproduct` int(11) NOT NULL,
  `onlyservice` int(11) NOT NULL,
  `useproject` int(11) NOT NULL,
  `dategap` int(11) NOT NULL,
  `inventaire` int(11) NOT NULL,
  `defaultstate` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `cover`, `store`, `oneprice`, `onlyproduct`, `onlyservice`, `useproject`, `dategap`, `inventaire`, `defaultstate`, `currency`) VALUES
(1, 'logo.png', 'bg.jpg', 'EasyBM', 2, 1, 1, 0, 0, 0, 'Nouvelle', 'DH');

-- --------------------------------------------------------

--
-- Structure de la table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `codefo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `respname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `respphone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `respemail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `respfax` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateadd` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tvas`
--

CREATE TABLE IF NOT EXISTS `tvas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tva` double NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `tvas`
--

INSERT INTO `tvas` (`id`, `tva`, `trash`) VALUES
(2, 20, 1),
(3, 7, 1),
(4, 10, 1),
(5, 12, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci NOT NULL,
  `superadmin` int(11) NOT NULL,
  `defaultstate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `depots` text COLLATE utf8_unicode_ci NOT NULL,
  `companies` text COLLATE utf8_unicode_ci NOT NULL,
  `projects` text COLLATE utf8_unicode_ci NOT NULL,
  `datesignup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `fullname`, `picture`, `email`, `password`, `phone`, `type`, `roles`, `superadmin`, `defaultstate`, `depots`, `companies`, `projects`, `datesignup`, `trash`) VALUES
(4, 'SuperAdmin', 'avatar.png', 'superadmin', 'superadmin', '0709999589', 'moderator', 'Consulter Tableau de bord,Consulter Trésorerie,Ajouter Trésorerie,Modifier Trésorerie,Supprimer Trésorerie,Exporter Trésorerie,Consulter Factures,Ajouter Factures,Modifier Factures,Supprimer Factures,Exporter Factures,Consulter Devis,Ajouter Devis,Modifier Devis,Supprimer Devis,Exporter Devis,Consulter Factures proforma,Ajouter Factures proforma,Modifier Factures proforma,Supprimer Factures proforma,Exporter Factures proforma,Consulter Bons de livraison,Ajouter Bons de livraison,Modifier Bons de livraison,Supprimer Bons de livraison,Exporter Bons de livraison,Consulter Bons de sortie,Ajouter Bons de sortie,Modifier Bons de sortie,Supprimer Bons de sortie,Exporter Bons de sortie,Consulter Bons de retour,Ajouter Bons de retour,Modifier Bons de retour,Supprimer Bons de retour,Exporter Bons de retour,Consulter Factures avoir,Ajouter Factures avoir,Modifier Factures avoir,Supprimer Factures avoir,Exporter Factures avoir,Consulter Clients,Ajouter Clients,Modifier Clients,Supprimer Clients,Exporter Clients,CA Clients,Consulter Bons de commande,Ajouter Bons de commande,Modifier Bons de commande,Supprimer Bons de commande,Exporter Bons de commande,Consulter Bons de réception,Ajouter Bons de réception,Modifier Bons de réception,Supprimer Bons de réception,Exporter Bons de réception,Consulter Fournisseurs,Ajouter Fournisseurs,Modifier Fournisseurs,Supprimer Fournisseurs,Exporter Fournisseurs,CA Fournisseurs,Consulter Sociétés,Ajouter Sociétés,Modifier Sociétés,Supprimer Sociétés,Exporter Sociétés,CA Sociétés,Consulter Utilisateurs,Ajouter Utilisateurs,Modifier Utilisateurs,Supprimer Utilisateurs,Consulter TVA,Ajouter TVA,Modifier TVA,Supprimer TVA,Consulter Formation,Consultation des notifications,Réglage des notifications,Modification date opération,Transformation / Dupplication documents,Modification statut de paiement,Suppression historique de paiement,Télécharger Backup', 1, 'Livrée', '0', '0,1', '0', '1678533547', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
