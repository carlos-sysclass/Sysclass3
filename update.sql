ALTER TABLE `users` ADD COLUMN `websocket_key` CHAR(64) NULL DEFAULT NULL AFTER `api_secret_key`;

ALTER TABLE `user_times` 
ADD COLUMN `websocket_token` VARCHAR(255) NULL DEFAULT NULL AFTER `expired`;

DROP TABLE IF EXISTS `mod_chat_queue`;
CREATE TABLE `mod_chat_queue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `websocket_token` varchar(255) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `requester_id` mediumint(8) unsigned NOT NULL,
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mod_chat_queue` 
ADD COLUMN `type` VARCHAR(30) NULL AFTER `websocket_token`;

ALTER TABLE `mod_chat_queue` RENAME TO  `mod_chat` ;

CREATE TABLE `mod_chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` mediumint(8) unsigned NOT NULL,
  `message` text,
  `user_id` mediumint(8) unsigned NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`chat_id`) REFERENCES `mod_chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
