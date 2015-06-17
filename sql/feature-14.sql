DROP TABLE IF EXISTS `mod_lessons_files`;

ALTER TABLE `mod_lessons` ADD COLUMN `position` INT(11) NULL AFTER `info`;

/*-------------------------------------------------------*/

ALTER TABLE `mod_lessons_content` ADD COLUMN `parent_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `mod_lessons_content` CHANGE COLUMN `position` `position` INT(11) NULL DEFAULT NULL;

/* ALTER TABLE `mod_lessons_content` CHANGE COLUMN `parent_id` `parent_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL; */

ALTER TABLE `mod_lessons_content` ADD INDEX `fk_mod_lessons_content_parent_id_idx` (`parent_id` ASC);
ALTER TABLE `mod_lessons_content` ADD CONSTRAINT `fk_mod_lessons_content_parent_id`
  FOREIGN KEY (`parent_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `mod_lessons_content` ADD COLUMN `language_code` varchar(10) NOT NULL DEFAULT 'en' AFTER `info`;

ALTER TABLE `mod_dropbox` ADD COLUMN `filename` VARCHAR(250) NOT NULL DEFAULT '' AFTER `name`;

UPDATE `mod_dropbox` SET filename = `name`;
