ALTER TABLE `sysclass_itaipu`.`mod_institution` 
CHANGE COLUMN `zip` `postal_code` VARCHAR(15) NULL DEFAULT NULL ,
CHANGE COLUMN `number` `street_number` VARCHAR(15) NULL DEFAULT NULL ;
