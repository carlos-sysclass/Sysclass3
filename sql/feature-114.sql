CREATE TABLE `mod_dropbox` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `upload_type` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` varchar(20) NOT NULL,
  `size` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

ALTER TABLE `mod_institution`  ADD COLUMN `logo` VARCHAR(255) NULL DEFAULT NULL AFTER `facebook`;
ALTER TABLE `mod_institution` CHANGE COLUMN `logo` `logo` MEDIUMINT(8) NULL DEFAULT NULL ;


