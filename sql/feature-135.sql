ALTER TABLE `mod_tests_execution` ADD COLUMN `user_score` DECIMAL(15,4) NULL DEFAULT NULL AFTER `answers`;
ALTER TABLE `mod_tests_execution` ADD COLUMN `user_points` INT(11) NULL DEFAULT NULL AFTER `user_score`;

DROP TABLE IF EXISTS `mod_grades`;
CREATE TABLE `mod_grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `grades` text NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;
