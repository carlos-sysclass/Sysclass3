/* 	ALTER TABLE `mod_lessons` ADD COLUMN `progress` DECIMAL(4,3) NOT NULL DEFAULT 0 AFTER `type`;
	ALTER TABLE `mod_lessons_content` ADD COLUMN `progress` DECIMAL(4,3) NOT NULL DEFAULT 0 AFTER `info`;
*/
DROP TABLE `mod_lessons_content_progress`;
ALTER TABLE `mod_lessons_content` DROP COLUMN `progress`
ALTER TABLE `mod_lessons` DROP COLUMN `progress`
CREATE TABLE `mod_lessons_content_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `content_id` mediumint(8) unsigned NOT NULL,
  `factor` DECIMAL(4,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `mod_lessons_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `factor` DECIMAL(4,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;