ALTER TABLE `sysclass_demo`.`mod_translate_tokens` 
CHANGE COLUMN `language_code` `language_code` VARCHAR(10) NOT NULL ;
ALTER TABLE `sysclass_demo`.`mod_translate_tokens` 
CHANGE COLUMN `token` `token` VARCHAR(970) CHARACTER SET 'latin1' COLLATE 'latin1_bin' NOT NULL ;

ALTER TABLE `sysclass_demo`.`mod_lessons` 
ADD COLUMN `has_text_content` TINYINT(1) NOT NULL DEFAULT 1 AFTER `active`,
ADD COLUMN `text_content` TEXT NULL AFTER `has_text_content`,
ADD COLUMN `text_context_language_id` INT(11) NULL DEFAULT 1 AFTER `text_content`,
ADD COLUMN `has_video_content` TINYINT(1) NULL DEFAULT 1 AFTER `text_context_language_id`;

ALTER TABLE `sysclass_demo`.`mod_lessons` 
CHANGE COLUMN `text_context_language_id` `text_content_language_id` INT(11) NULL DEFAULT '1' ;
