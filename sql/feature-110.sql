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
