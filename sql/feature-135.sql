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

ALTER TABLE `mod_tests` ADD COLUMN `grade_id` MEDIUMINT(8) unsigned DEFAULT NULL AFTER `id`;

ALTER TABLE `mod_tests_execution` ADD COLUMN `user_grade` INT(11) NULL DEFAULT NULL AFTER `user_points`;
ALTER TABLE `mod_tests_execution` CHANGE COLUMN `user_grade` `user_grade` VARCHAR(100) NULL DEFAULT NULL;
