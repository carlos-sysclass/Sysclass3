ALTER TABLE `mod_tests_to_questions` 
ADD COLUMN `points` SMALLINT(4) NOT NULL DEFAULT 1 AFTER `position`,
ADD COLUMN `weight` SMALLINT(4) NOT NULL DEFAULT 1 AFTER `points`;

DROP TABLE IF EXISTS `mod_tests`;
CREATE TABLE `mod_tests` (
	`id` mediumint(8) unsigned NOT NULL,
	`time_limit`  smallint(4) NOT NULL DEFAULT '0',
	`allow_pause`  tinyint(1) NOT NULL DEFAULT '0',
	`test_repetition` smallint(4) NOT NULL DEFAULT '1',
	`show_question_weight`  tinyint(1) NOT NULL DEFAULT '0',
	`show_question_difficulty`  tinyint(1) NOT NULL DEFAULT '0',
	`show_question_type`  tinyint(1) NOT NULL DEFAULT '1',
	`show_one_by_one`  tinyint(1) NOT NULL DEFAULT '0',
	`can_navigate_through`  tinyint(1) NOT NULL DEFAULT '0',
	`show_correct_answers`  tinyint(1) NOT NULL DEFAULT '0',
	`randomize_questions` tinyint(1) NOT NULL DEFAULT '0',
	`randomize_answers` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	FOREIGN KEY (`id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;