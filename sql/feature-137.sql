DROP TABLE IF EXISTS `mod_advertising_content_files`;
DROP TABLE IF EXISTS `mod_advertising_content`;
DROP TABLE IF EXISTS `mod_advertising`;

CREATE TABLE `mod_advertising` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`placement` character varying(50) NOT NULL,
	`view_type` enum('serial', 'carrousel') NOT NULL DEFAULT 'serial',
	`active` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*
CREATE TABLE `mod_advertising_banners` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `advertising_id` mediumint(8) unsigned NOT NULL,
	`file_id` mediumint(8) unsigned NOT NULL,
	`position` int(11) DEFAULT NULL,
	`active` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	FOREIGN KEY (`advertising_id`) REFERENCES `mod_advertising` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
INSERT INTO `mod_advertising`(`id`,`placement`,`view_type`,`active`) VALUES (NULL, 'ads.leftbar.banner', 'serial', 1);
INSERT INTO `mod_advertising`(`id`,`placement`,`view_type`,`active`) VALUES (NULL, 'ads.rightbar.banner', 'serial', 1);

CREATE TABLE `mod_advertising_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `advertising_id` mediumint(8) unsigned NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `info` text,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`advertising_id`) REFERENCES `mod_advertising` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_advertising_content_files` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`file_id`),
  KEY `file_id` (`file_id`),
  FOREIGN KEY (`content_id`) REFERENCES `mod_advertising_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
