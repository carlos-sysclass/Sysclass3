CREATE TABLE `mod_enroll_course_to_users` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `token` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `course_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `status_id` smallint(4) NOT NULL DEFAULT '1',
  `tag` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

