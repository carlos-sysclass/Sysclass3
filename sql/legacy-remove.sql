CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `settings` ADD COLUMN `datatype` VARCHAR(45) NULL DEFAULT 'string' AFTER `value`;

ALTER TABLE `users` CHANGE COLUMN `password` `password` VARCHAR(100) NOT NULL,
ADD COLUMN `backend` VARCHAR(45) NOT NULL DEFAULT 'sysclass' AFTER `password`;

ALTER TABLE `users` ADD COLUMN `locked` tinyint(1) NOT NULL DEFAULT 0 AFTER `backend`;

UPDATE `users` SET backend = 'sysclass';

DROP TABLE IF EXISTS user_times;
CREATE TABLE `user_times` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY (`session_id`),
  FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8;


/*
DROP TABLE IF EXISTS `user_to_roles`;
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_to_roles` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `context` varchar(50) NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`, role_id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sysclass_demo`.`user_to_roles` (`user_id`, `role_id`) VALUES ('1', 'system_administrator')
*/


