SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `sysclass_enterprise`.`mod_classes` 
ADD COLUMN `type` ENUM('class','test') NOT NULL DEFAULT 'class' AFTER `active`;

CREATE TABLE IF NOT EXISTS `sysclass_enterprise`.`mod_classes_progress` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `class_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `factor` DECIMAL(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC),
  INDEX `class_id` (`class_id` ASC),
  CONSTRAINT `mod_classes_progress_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `sysclass_enterprise`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `mod_classes_progress_ibfk_2`
    FOREIGN KEY (`class_id`)
    REFERENCES `sysclass_enterprise`.`mod_classes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `sysclass_enterprise`.`mod_courses_progress` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `course_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `factor` DECIMAL(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC),
  INDEX `course_id` (`course_id` ASC),
  CONSTRAINT `mod_courses_progress_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `sysclass_enterprise`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `mod_courses_progress_ibfk_2`
    FOREIGN KEY (`course_id`)
    REFERENCES `sysclass_enterprise`.`mod_courses` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `sysclass_enterprise`.`mod_news` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `timestamp` INT(10) UNSIGNED NULL DEFAULT '0',
  `expire` INT(10) UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `user_id` (`user_id` ASC),
  CONSTRAINT `mod_news_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `sysclass_enterprise`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `sysclass_enterprise`.`mod_tests_execution` 
CHANGE COLUMN `completed` `completed` INT(10) NOT NULL DEFAULT '0' AFTER `pending`;

CREATE TABLE IF NOT EXISTS `sysclass_enterprise`.`settings` (
  `name` VARCHAR(100) NOT NULL,
  `value` TEXT NOT NULL,
  `datatype` VARCHAR(45) NULL DEFAULT 'string',
  `description` TEXT NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `sysclass_enterprise`.`user_avatar` (
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `file_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  INDEX `file_id` (`file_id` ASC),
  CONSTRAINT `user_avatar_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `sysclass_enterprise`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `user_avatar_ibfk_2`
    FOREIGN KEY (`file_id`)
    REFERENCES `sysclass_enterprise`.`mod_dropbox` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `sysclass_enterprise`.`user_times` 
ENGINE = InnoDB ,
DROP COLUMN `entity_id`,
DROP COLUMN `entity`,
DROP COLUMN `courses_ID`,
DROP COLUMN `lessons_ID`,
DROP COLUMN `time`,
DROP COLUMN `timestamp_now`,
DROP COLUMN `users_LOGIN`,
DROP COLUMN `session_expired`,
DROP COLUMN `session_timestamp`,
ADD COLUMN `user_id` MEDIUMINT(8) UNSIGNED NOT NULL AFTER `session_id`,
ADD COLUMN `started` INT(10) UNSIGNED NOT NULL AFTER `user_id`,
ADD COLUMN `ping` INT(10) UNSIGNED NOT NULL AFTER `started`,
ADD COLUMN `expired` TINYINT(1) NOT NULL DEFAULT '0' AFTER `ping`,
ADD INDEX `session_id` (`session_id` ASC),
ADD INDEX `user_id` (`user_id` ASC),
DROP INDEX `entity_INDEX` ,
DROP INDEX `users_LOGIN_INDEX` ;

ALTER TABLE `sysclass_enterprise`.`users` 
DROP COLUMN `need_mod_init`,
DROP COLUMN `dashboard_positions`,
DROP COLUMN `archive`,
DROP COLUMN `balance`,
DROP COLUMN `short_description`,
DROP COLUMN `status`,
DROP COLUMN `additional_accounts`,
DROP COLUMN `avatar`,
DROP COLUMN `timestamp`,
DROP COLUMN `languages_NAME`,
CHANGE COLUMN `group_id` `group_id` INT(11) NOT NULL DEFAULT '0' AFTER `surname`,
CHANGE COLUMN `language_id` `language_id` INT(11) NOT NULL DEFAULT '0' AFTER `group_id`,
CHANGE COLUMN `can_be_instructor` `can_be_instructor` TINYINT(1) NOT NULL DEFAULT '0' AFTER `language_id`,
CHANGE COLUMN `can_be_coordinator` `can_be_coordinator` TINYINT(1) NOT NULL DEFAULT '0' AFTER `can_be_instructor`,
CHANGE COLUMN `viewed_license` `viewed_license` TINYINT(1) NULL DEFAULT '0' AFTER `can_be_coordinator`,
CHANGE COLUMN `autologin` `autologin` CHAR(32) NULL DEFAULT NULL AFTER `viewed_license`,
CHANGE COLUMN `user_type` `user_type` VARCHAR(50) NOT NULL DEFAULT 'student' AFTER `active`,
CHANGE COLUMN `dashboard_id` `dashboard_id` VARCHAR(25) NOT NULL DEFAULT 'default' AFTER `user_type`,
CHANGE COLUMN `timezone` `timezone` VARCHAR(100) NULL DEFAULT '' AFTER `dashboard_id`,
CHANGE COLUMN `password` `password` VARCHAR(100) NOT NULL ,
ADD COLUMN `backend` VARCHAR(45) NOT NULL DEFAULT 'sysclass' AFTER `password`,
ADD COLUMN `locked` TINYINT(1) NOT NULL DEFAULT '0' AFTER `backend`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`),
ADD UNIQUE INDEX `login` (`login` ASC),
DROP INDEX `id` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_surveys` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_projects` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_lessons` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_done_surveys` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_courses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_content` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_classes` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`users_to_chatrooms` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`user_profile` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`tokens` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`themes` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`tests_to_questions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`tests` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`surveys` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`survey_questions_done` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`service_direct_link_hash` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`sent_notifications` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`search_keywords` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`search_invertedindex` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`scorm_data` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`rules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`questions_to_surveys` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`questions_to_skills` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`questions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`projects` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`profile_comments` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`periods` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`notifications` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`news` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`modules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_youtube` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xwebtutoria_avaliation` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xwebtutoria` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xuser_user_tags` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xuser_responsible` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xuser` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xskill_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xskill_lessons2skills` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xskill_course2skills` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xskill_content2skills` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xskill` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xprojects_topics` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xprojects_groups_to_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xprojects_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xprojects` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_to_send_list_item` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_to_send_list` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_sent_invoices_log` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_price_rules_tags` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_price_rules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_paid_items` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_negociation_modules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_manual_transactions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_lesson_negociation` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_lesson_modality_prices` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_lesson_modality` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_invoices_to_paid` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_invoices` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_course_negociation` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_course_modality_prices` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_course_modality` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_cielo_transactions_to_invoices` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_cielo_transactions_statuses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_cielo_transactions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_cielo_card_tokens` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_boleto_transactions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_boleto_ocorrencias` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_boleto_liquidacao` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xpay_boleto_bancos` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xies_to_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xentify_scopes` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xentify_scope_tags` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xentify` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xenrollment_statuses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xenrollment_receiver_counters` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xenrollment_documents_status` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xenrollment` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xdocuments_types` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xdocuments_to_courses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xdocuments_status` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xdocuments` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcourse_lesson_class_series` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcourse_lesson_class_calendar_series` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcourse_lesson_class_calendar` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcontent_schedule_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcontent_schedule_itens` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcontent_schedule_contents` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcontent_schedule` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcms_pages` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_xcms_blocks` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_settings` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_publish` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_progress` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_items` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_autosave` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_workbook_answers` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_rss_provider` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_rss_feeds` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_quick_mails_recipients_list` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_quick_mails_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_polos` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_pagamento_invoices_status` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_links` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_language_tokens` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_journal_rules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_journal_entries` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_ies_to_polos` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_ies` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_ranges` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_objects` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_groups_order` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_gradebook_grades` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_faq` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_chat_config` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_chat` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_certificates_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_certificates` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_cep_logradouros_base` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_cep_logradouros` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_blogs_users` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_blogs_comments` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_blogs_articles` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_blogs` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_banners` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`module_BBB` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`mod_lessons_files` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lessons_to_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lessons_to_courses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lessons_timeline_topics_data` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lessons_timeline_topics` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lessons` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`lesson_conditions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`languages` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`glossary` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`files` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_users_to_polls` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_topics` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_poll` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_personal_messages` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_messages` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_forums` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_folders` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`f_configuration` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`events` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`event_notifications` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`done_tests` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`done_questions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`directions` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`courses_to_groups` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`courses` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`content` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`completed_tests` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`comments` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`classes_to_content` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`classes_schedules` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`classes` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`chatrooms` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`chatmessages` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`calendar` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`cache` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`c_users_link` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`bookmarks` ;

DROP TABLE IF EXISTS `sysclass_enterprise`.`benchmark` ;

ALTER TABLE `sysclass_enterprise`.`user_times` 
ADD CONSTRAINT `user_times_ibfk_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `sysclass_enterprise`.`users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
