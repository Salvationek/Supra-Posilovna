-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `supraposilovna`;
CREATE DATABASE `supraposilovna` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci */;
USE `supraposilovna`;

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_czech_ci NOT NULL COMMENT 'Index role',
  `user_id` int(11) unsigned NOT NULL COMMENT 'UID Uživatele',
  `created_at` int(11) DEFAULT NULL COMMENT 'Datum přiřazení',
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_ibfk_1_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL COMMENT 'Index role/oprávnění',
  `type` int(11) NOT NULL COMMENT 'Typ záznamu',
  `description` text COLLATE utf8_czech_ci COMMENT 'Název role/oprávnění',
  `rule_name` varchar(64) COLLATE utf8_czech_ci DEFAULT NULL,
  `data` text COLLATE utf8_czech_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='YII - tabulka rolí';

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
  ('admin',	1,	'Administrátor',	NULL,	NULL,	NULL,	NULL),
  ('uzivatel',	1,	'Uživatel',	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_czech_ci NOT NULL COMMENT 'Vlastník oprávnění',
  `child` varchar(64) COLLATE utf8_czech_ci NOT NULL COMMENT 'Vlastněné oprávnění',
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Dědičnost rolí.';


DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `data` text COLLATE utf8_czech_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation` (
  `date` date NOT NULL COMMENT 'datum rezervace',
  `quarter` int(11) NOT NULL COMMENT 'počet čtvrthodin od začátku dne',
  `riid` int(10) unsigned NOT NULL COMMENT 'předmět rezervace',
  `uid` int(11) unsigned NOT NULL COMMENT 'identifikátor uživatele',
  `note` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'poznámka k rezervaci',
  PRIMARY KEY (`date`,`quarter`,`riid`),
  KEY `riid` (`riid`),
  KEY `uid` (`uid`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`riid`) REFERENCES `reservation_item` (`riid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `reservation_item`;
CREATE TABLE `reservation_item` (
  `riid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`riid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `reservation_item` (`riid`, `description`) VALUES
  (1,	'posilovna'),
  (2,	'sauna'),
  (3,	'spinning'),
  (4,	'bazén');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikátor uživatele',
  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'uživatelské jméno',
  `email` varchar(1023) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'emailová adresa',
  `auth_key` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'autorizační klíč',
  `password` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'heslo',
  `active` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'aktivní',
  `access_token` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'přístupový token',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `user` (`uid`, `username`, `email`, `auth_key`, `password`, `active`, `access_token`) VALUES
  (1,	'supraadmin',	'',	NULL,	'$2y$13$7igGliuKwcgTxFskTVYZaexMEtHSTS3Ve/cfUozt4V5P1xEFgwdP2',	1,	NULL);

-- 2018-01-13 21:48:16