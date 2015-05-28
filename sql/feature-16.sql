CREATE TABLE `mod_lessons_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `info` text,
  `order` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

ALTER TABLE `sysclass_demo`.`mod_lessons_content` 
CHANGE COLUMN `type` `content_type` VARCHAR(20) NOT NULL ,
CHANGE COLUMN `order` `position` INT(11) NOT NULL ;

DROP TABLE IF EXISTS `mod_lessons_files`;
CREATE TABLE `mod_lessons_files` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `upload_type` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` varchar(20) NOT NULL,
  `size` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mod_lessons_content_files`;
CREATE TABLE `mod_lessons_content_files` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`, `file_id`),
  FOREIGN KEY (`content_id`) REFERENCES mod_lessons_content (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (`file_id`) REFERENCES mod_dropbox (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8;
