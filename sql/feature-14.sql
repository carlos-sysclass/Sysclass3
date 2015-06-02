DROP TABLE IF EXISTS `mod_lessons_files`;

ALTER TABLE `mod_lessons` ADD COLUMN `position` INT(11) NULL AFTER `info`;
