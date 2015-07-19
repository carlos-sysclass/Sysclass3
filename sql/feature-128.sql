DROP TABLE IF EXISTS `mod_tests_execution`;
CREATE TABLE `mod_tests_execution` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`test_id` mediumint(8) unsigned NOT NULL,
    `try_index` smallint(4) unsigned NOT NULL DEFAULT 1,
	`start_timestamp` int(10) NOT NULL DEFAULT 0,
    `paused` tinyint(1) NOT NULL DEFAULT 0,
    `pending` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`test_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

