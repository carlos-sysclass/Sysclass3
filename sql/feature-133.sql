ALTER TABLE `mod_lessons` ADD COLUMN `progress` DECIMAL(4,3) NOT NULL DEFAULT 0 AFTER `type`;
ALTER TABLE `mod_lessons_content` ADD COLUMN `progress` DECIMAL(4,3) NOT NULL DEFAULT 0 AFTER `info`;
