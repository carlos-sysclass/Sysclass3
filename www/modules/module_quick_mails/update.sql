ALTER TABLE module_quick_mails_scope DROP PRIMARY KEY;
ALTER TABLE `module_quick_mails_scope` ADD `codigo` BIGINT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY ( `codigo` );
UPDATE `sysclass_root`.`module_xentify_scopes` SET `active` = '0' WHERE `module_xentify_scopes`.`id` =11;
UPDATE `sysclass_root`.`module_xentify_scopes` SET `active` = '0' WHERE `module_xentify_scopes`.`id` =12;
UPDATE `sysclass_root`.`module_xentify_scopes` SET `active` = '0' WHERE `module_xentify_scopes`.`id` =14;
ALTER TABLE `module_quick_mails_recipients` ADD `link` VARCHAR( 200 ) NULL;