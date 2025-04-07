--Ligne pour créer un utilisateur TOUT PUISSANT!
--CREATE USER 'pokemin'@'localhost' IDENTIFIED BY 'adminpokemin';
--GRANT ALL PRIVILEGES ON `pokemin`.* TO 'adminpokemin'@'localhost' WITH GRANT OPTION;


SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `attaque`;
CREATE TABLE `attaque` (
  `id_attaque` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `degat` int(11) NOT NULL,
  `style` varchar(50) NOT NULL,
  `id_type` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_attaque`),
  UNIQUE KEY `nom` (`nom`),
  KEY `id_type` (`id_type`),
  CONSTRAINT `attaque_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `attaque_pokemin`;
CREATE TABLE `attaque_pokemin` (
  `id_attaque` bigint(20) unsigned NOT NULL,
  `id_instance` bigint(20) unsigned NOT NULL,
  `mana` smallint(6) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `niveau_obtention` smallint(6) NOT NULL,
  PRIMARY KEY (`id_attaque`,`id_instance`),
  KEY `id_instance` (`id_instance`),
  CONSTRAINT `attaque_pokemin_ibfk_1` FOREIGN KEY (`id_attaque`) REFERENCES `attaque` (`id_attaque`),
  CONSTRAINT `attaque_pokemin_ibfk_2` FOREIGN KEY (`id_instance`) REFERENCES `instance_pokemin` (`id_instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `compte`;
CREATE TABLE `compte` (
  `id_compte` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `pseudo` varchar(128) NOT NULL,
  `password` varchar(258) NOT NULL,
  `date_creation` date DEFAULT NULL,
  `id_role` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_compte`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`),
  KEY `FK_role` (`id_role`),
  CONSTRAINT `FK_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `compte` (`id_compte`, `email`, `pseudo`, `password`, `date_creation`, `id_role`) VALUES
(1,	'pokemin@hotmail.fr',	'Admin',	'$2y$10$XlJckGRO/1DttTtYv5EdgO1VA.e70IDGdfJWqc.Hay7WtnNAqqmmi',	'2024-11-21',	3);

DROP TABLE IF EXISTS `ct`;
CREATE TABLE `ct` (
  `id_objet` bigint(20) unsigned NOT NULL,
  `id_attaque` bigint(20) unsigned NOT NULL,
  `nom` varchar(128) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id_objet`,`id_attaque`),
  KEY `id_attaque` (`id_attaque`),
  CONSTRAINT `ct_ibfk_1` FOREIGN KEY (`id_objet`) REFERENCES `objet` (`id_objet`),
  CONSTRAINT `ct_ibfk_2` FOREIGN KEY (`id_attaque`) REFERENCES `attaque` (`id_attaque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `don`;
CREATE TABLE `don` (
  `id_don` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `effet` varchar(1024) DEFAULT NULL,
  `degat` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_don`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `dresseur`;
CREATE TABLE `dresseur` (
  `id_dresseur` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `pokegrolard` int(11) NOT NULL,
  PRIMARY KEY (`id_dresseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `instance_pokemin`;
CREATE TABLE `instance_pokemin` (
  `id_instance` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `niveau` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `experiencemax` int(11) NOT NULL,
  `pv` int(11) NOT NULL,
  `pvmax` int(11) NOT NULL,
  `mana` int(11) NOT NULL,
  `manamax` int(11) NOT NULL,
  `agilite` int(11) NOT NULL,
  `chance` int(11) NOT NULL,
  `endurance` int(11) NOT NULL,
  `esprit` int(11) NOT NULL,
  `puissance` int(11) NOT NULL,
  `intelligence` int(11) NOT NULL,
  `sauvage` tinyint(1) NOT NULL,
  `actif` tinyint(1) DEFAULT NULL,
  `id_pokemin` bigint(20) unsigned NOT NULL,
  `id_dresseur` bigint(20) unsigned DEFAULT NULL,
  `id_personnage` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_instance`),
  KEY `id_pokemin` (`id_pokemin`),
  KEY `id_dresseur` (`id_dresseur`),
  KEY `id_personnage` (`id_personnage`),
  CONSTRAINT `instance_pokemin_ibfk_1` FOREIGN KEY (`id_pokemin`) REFERENCES `pokemin` (`id_pokemin`),
  CONSTRAINT `instance_pokemin_ibfk_2` FOREIGN KEY (`id_dresseur`) REFERENCES `dresseur` (`id_dresseur`),
  CONSTRAINT `instance_pokemin_ibfk_3` FOREIGN KEY (`id_personnage`) REFERENCES `personnage` (`id_personnage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `objet`;
CREATE TABLE `objet` (
  `id_objet` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `effet` varchar(1024) DEFAULT NULL,
  `degat` int(11) DEFAULT NULL,
  `valeur` int(11) NOT NULL,
  `valeur_vente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_objet`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `personnage`;
CREATE TABLE `personnage` (
  `id_personnage` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `pokegrolard` int(11) NOT NULL,
  `id_pouvoir` bigint(20) unsigned DEFAULT NULL,
  `id_compte` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_personnage`),
  KEY `id_pouvoir` (`id_pouvoir`),
  KEY `id_compte` (`id_compte`),
  CONSTRAINT `personnage_ibfk_1` FOREIGN KEY (`id_pouvoir`) REFERENCES `pouvoir` (`id_pouvoir`),
  CONSTRAINT `personnage_ibfk_2` FOREIGN KEY (`id_compte`) REFERENCES `compte` (`id_compte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pokemin`;
CREATE TABLE `pokemin` (
  `id_pokemin` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `cri` varchar(256) NOT NULL,
  `evolution1` int(11) DEFAULT NULL,
  `niveau_evolution1` smallint(6) DEFAULT NULL,
  `evolution2` int(11) DEFAULT NULL,
  `niveau_evolution2` smallint(6) DEFAULT NULL,
  `taux_apparition` int(11) DEFAULT NULL,
  `taux_capture` int(11) DEFAULT NULL,
  `id_don` bigint(20) unsigned DEFAULT NULL,
  `id_type` bigint(20) unsigned NOT NULL,
  `id_type2` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_pokemin`),
  UNIQUE KEY `nom` (`nom`),
  KEY `id_don` (`id_don`),
  KEY `id_type2` (`id_type2`),
  KEY `id_type` (`id_type`),
  CONSTRAINT `pokemin_ibfk_1` FOREIGN KEY (`id_don`) REFERENCES `don` (`id_don`),
  CONSTRAINT `pokemin_ibfk_2` FOREIGN KEY (`id_type2`) REFERENCES `type` (`id_type`),
  CONSTRAINT `pokemin_ibfk_3` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pokemin` (`id_pokemin`, `nom`, `description`, `cri`, `evolution1`, `niveau_evolution1`, `evolution2`, `niveau_evolution2`, `taux_apparition`, `taux_capture`, `id_don`, `id_type`, `id_type2`) VALUES
(1,	'Ferchau',	'Petite créature disposant d\'une fine couche d\'ecaille dur comme de l\'acier, capable de projeter un liquide fait d\'alliage en fusion.',	'Métalique',	NULL,	15,	NULL,	35,	NULL,	NULL,	NULL,	1,	NULL),
(2,	'Acrame',	'Evolution de Ferchau, peut produire plus de liquide que ce dernier , attention il ne faut pas le toucher car sinon \'Acrame !',	'Métalique et agréable',	NULL,	35,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL);

DROP TABLE IF EXISTS `pouvoir`;
CREATE TABLE `pouvoir` (
  `id_pouvoir` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `effet` varchar(1024) DEFAULT NULL,
  `degat` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pouvoir`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id_role` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `role` (`id_role`, `nom`) VALUES
(3,	'administrateur'),
(2,	'createur'),
(1,	'utilisateur');

DROP TABLE IF EXISTS `sac`;
CREATE TABLE `sac` (
  `id_sac` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quantite` smallint(6) NOT NULL,
  `id_dresseur` bigint(20) unsigned DEFAULT NULL,
  `id_personnage` bigint(20) unsigned DEFAULT NULL,
  `id_objet` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_sac`),
  KEY `id_dresseur` (`id_dresseur`),
  KEY `id_personnage` (`id_personnage`),
  KEY `id_objet` (`id_objet`),
  CONSTRAINT `sac_ibfk_1` FOREIGN KEY (`id_dresseur`) REFERENCES `dresseur` (`id_dresseur`),
  CONSTRAINT `sac_ibfk_2` FOREIGN KEY (`id_personnage`) REFERENCES `personnage` (`id_personnage`),
  CONSTRAINT `sac_ibfk_3` FOREIGN KEY (`id_objet`) REFERENCES `objet` (`id_objet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id_type` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_type`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `type` (`id_type`, `nom`) VALUES
(1,	'feu');

-- 2024-11-26 11:25:29