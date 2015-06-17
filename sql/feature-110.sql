DROP TABLE IF EXISTS `mod_roadmap_courses_grouping`;
CREATE TABLE `mod_roadmap_courses_grouping` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned,
  /* `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4', */
  `name` varchar(250) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` datetime NULL DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
  /* FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) */
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

/* ------------------------------------------------ */
ALTER TABLE `mod_courses` ADD COLUMN `has_grouping` TINYINT(1) NOT NULL DEFAULT 0 AFTER `currency`;
ALTER TABLE `mod_courses` ADD COLUMN `has_student_selection` TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_grouping`;
ALTER TABLE `mod_courses` ADD COLUMN `has_periods` TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_student_selection`;



DROP TABLE IF EXISTS `mod_roadmap_courses_to_classes`;
CREATE TABLE `mod_roadmap_courses_to_classes` (
  `id` mediumint(8) unsigned unsigned NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned unsigned NOT NULL,
  `class_id` mediumint(8) unsigned unsigned NOT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY (`course_id`,`class_id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS mod_roadmap_courses_seasons;
CREATE TABLE `mod_roadmap_courses_periods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `max_classes` int(8) DEFAULT '-1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mod_roadmap_classes_to_seasons`;
CREATE TABLE `mod_roadmap_classes_to_periods` (
  `period_id` mediumint(8) unsigned NOT NULL,
  `class_id` mediumint(8) unsigned NOT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`period_id`,`class_id`),
  FOREIGN KEY (`period_id`) REFERENCES mod_roadmap_courses_periods (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mod_lessons`;
CREATE TABLE `mod_lessons` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `class_id` mediumint(8) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `info` text,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `has_text_content` tinyint(1) NOT NULL DEFAULT '1',
  `text_content` text,
  `text_content_language_id` int(11) DEFAULT '1',
  `has_video_content` tinyint(1) DEFAULT '1',
  `subtitle_content_language_id` int(11) DEFAULT '1',
  `instructor_id` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`class_id`) REFERENCES mod_classes (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

DROP TABLE `mod_lessons_content_questions`;
DROP TABLE `mod_lessons_content_files`;
DROP TABLE `mod_lessons_content`;
CREATE TABLE `mod_lessons_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned DEFAULT NULL,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `info` text,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY (`parent_id`),
  FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

CREATE TABLE `mod_lessons_content_files` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`file_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `mod_lessons_content_files_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_lessons_content_questions` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`question_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `mod_lessons_content_questions_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `mod_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `mod_classes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `ies_id` mediumint(8) NOT NULL DEFAULT '0',
  `area_id` mediumint(8) unsigned DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `info` text,
  `course_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

GET CREATE SCRIPTS FROM COURSES TO CONTENT
