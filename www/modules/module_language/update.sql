CREATE TABLE `sysclass_root`.`module_language_tokens` (
`language` VARCHAR( 30 ) NOT NULL ,
`token` VARCHAR( 300 ) NOT NULL ,
`translated` TEXT NOT NULL ,
PRIMARY KEY ( `language` , `token` )
) ENGINE = MYISAM ;

INSERT INTO `sysclass_root`.`module_language_tokens` (`language`, `token`, `translated`) VALUES ('portuguese', '__LANGUAGE_NAME', 'Portuguese');
INSERT INTO `sysclass_root`.`module_language_tokens` (`language`, `token`, `translated`) VALUES ('english', '__LANGUAGE_NAME', 'English');
INSERT INTO `sysclass_root`.`module_language_tokens` (`language`, `token`, `translated`) VALUES ('english2', '__LANGUAGE_NAME', 'English2');


