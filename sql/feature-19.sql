DROP TABLE IF EXISTS `mod_lessons_content_questions`;
DROP TABLE IF EXISTS `mod_questions`;
DROP TABLE IF EXISTS `mod_questions_types`;
DROP TABLE IF EXISTS `mod_questions_difficulties`;

CREATE TABLE `mod_questions_difficulties` (   
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`name` character varying(150) NOT NULL,   
	PRIMARY KEY(`id`) 
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

INSERT INTO `mod_questions_difficulties` (`id`,`name`) VALUES (NULL, 'Easy');
INSERT INTO `mod_questions_difficulties` (`id`,`name`) VALUES (NULL, 'Normal');
INSERT INTO `mod_questions_difficulties` (`id`,`name`) VALUES (NULL, 'Hard');
INSERT INTO `mod_questions_difficulties` (`id`,`name`) VALUES (NULL, 'Very Hard');


CREATE TABLE `mod_questions_types` (
	`id` character varying(20) NOT NULL,
	`name` character varying(150) NOT NULL,
	PRIMARY KEY(`id`) 
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('combine', 'Combine');
INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('free_text', 'Free Text');
INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('true_or_false', 'True Or False');
INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('simple_choice', 'Simple Choice');
INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('multiple_choice', 'Multiple Choice');
INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('fill_blanks', 'Fill in the blanks');

CREATE TABLE `mod_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` character varying(150) NOT NULL,
  `question` text NOT NULL,
  `area_id` mediumint(8) unsigned NOT NULL,
  `difficulty_id` mediumint(8) unsigned NOT NULL,
  `type_id` character varying(20) NOT NULL,
  `options` text,
  `answer` text,
  `explanation` text,
  `answers_explanation` text,
  `estimate` int(10) unsigned DEFAULT NULL,
  `settings` text,
  `active`TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  /* FOREIGN KEY (`area_id`) REFERENCES `mod_areas` ON UPDATE CASCADE ON DELETE RESTRICT */
  FOREIGN KEY (`difficulty_id`) REFERENCES `mod_questions_difficulties` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (`type_id`) REFERENCES `mod_questions_types` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDb DEFAULT CHARSET=utf8;


CREATE TABLE `mod_lessons_content_questions` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`question_id`),
  FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question_id`) REFERENCES `mod_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* --------------------------------------------------------------------------------------- */
ALTER TABLE `mod_lessons` ADD COLUMN `type` ENUM('lesson', 'test') NOT NULL DEFAULT 'lesson' AFTER `active`;

DROP TABLE IF EXISTS `mod_tests_to_questions`;
CREATE TABLE `mod_tests_to_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE (`lesson_id`,`question_id`),
  FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question_id`) REFERENCES `mod_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
