DROP TABLE IF EXISTS `mod_certificate`;
CREATE TABLE `mod_certificate` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `entity_id` text NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'course',
  `vars` text NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sysclass_itaipu`.`mod_notification_to_users` 
ADD COLUMN `unique_id` VARCHAR(100) NOT NULL AFTER `timestamp`;
