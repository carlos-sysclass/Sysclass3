DROP TABLE IF EXISTS `mod_institution`;
CREATE TABLE `mod_institution` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `name` varchar(250) NOT NULL,
  `formal_name` varchar(250) NOT NULL,
  `contact` varchar(250) NOT NULL,
  `observations` text,
  `zip` varchar(15) NOT NULL,
  `address` varchar(150) NOT NULL,
  `number` varchar(15) NOT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(20) NOT NULL,
  `country_code` varchar(3) NOT NULL DEFAULT 'BR',
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `logo_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`logo_id`) REFERENCES mod_dropbox (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

INSERT INTO `mod_institution` (`id`,`permission_access_mode`,`name`,`formal_name`,`contact`,`observations`,`zip`,`address`,`number`,`address2`,`city`,`state`,`country_code`,`phone`,`active`,`website`,`facebook`,`logo_id`) VALUES (1,'4','Wiseflex','Wiseflex','','','','eeeee','','','Dallas','Texas','US','',1,'http://lucent.institute','lucentinstitute',286);
