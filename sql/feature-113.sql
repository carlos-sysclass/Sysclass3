ALTER TABLE `sysclass_demo`.`mod_translate_tokens` 
CHANGE COLUMN `language_code` `language_code` VARCHAR(10) NOT NULL ;
ALTER TABLE `sysclass_demo`.`mod_translate_tokens` 
CHANGE COLUMN `token` `token` VARCHAR(970) CHARACTER SET 'latin1' COLLATE 'latin1_bin' NOT NULL ;
