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

