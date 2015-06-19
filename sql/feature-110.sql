DROP TABLE IF EXISTS `mod_roadmap_classes_to_periods`;
DROP TABLE IF EXISTS `mod_roadmap_courses_periods`;

CREATE TABLE `mod_roadmap_courses_periods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `max_classes` int(8) DEFAULT '-1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_roadmap_classes_to_periods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` mediumint(8) unsigned NOT NULL,
  `class_id` mediumint(8) unsigned NOT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`period_id`,`class_id`),
  FOREIGN KEY (`period_id`) REFERENCES `mod_roadmap_courses_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
