DROP TABLE IF EXISTS `mod_advertising_banners`;
DROP TABLE IF EXISTS `mod_advertising`;

CREATE TABLE `mod_advertising` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`placement` character varying(50) NOT NULL,
	`view_type` enum('serial', 'carrousel') NOT NULL DEFAULT 'serial',
	`active` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

