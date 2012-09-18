ALTER TABLE `module_pagamento_invoices` ADD `invoices_sha_access` TEXT NULL AFTER `invoice_id`;
	
RENAME TABLE `module_schools` TO `module_ies` ;
	
INSERT INTO `module_pagto_boleto_ocorrencias` (`codigo` , `descricao`) VALUES ('02', 'ENTRADA CONFIRMADA');
INSERT INTO `module_pagto_boleto_ocorrencias` (`codigo` , `descricao`) VALUES ('09', 'BAIXA SIMPLES');
INSERT INTO `module_pagto_boleto_ocorrencias` (`codigo` , `descricao`) VALUES ('54', 'TARIFA MENSAL DE LIQUIDAÇÕES NA CARTEIRA');
	
ALTER TABLE `module_pagto_boleto_ocorrencias` CHANGE `codigo` `id` VARCHAR( 3 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `module_pagto_boleto_ocorrencias` CHANGE `descricao` `description` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 

ALTER TABLE `module_pagto_boleto_liquidacao` CHANGE `codigo` `id` VARCHAR( 3 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `module_pagto_boleto_liquidacao` CHANGE `descricao` `description` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

RENAME TABLE `module_pagto_boleto_ocorrencias` TO `module_pagamento_boleto_ocorrencias`;
RENAME TABLE `module_pagto_boleto_liquidacao` TO `module_pagamento_boleto_liquidacao`; 

ALTER TABLE `module_pagamento_invoices` ADD FULLTEXT (`invoice_id`);

CREATE TABLE IF NOT EXISTS `module_pagamento_boleto_invoices_return`(
  `payment_id` mediumint(8) NOT NULL,
  `parcela_index` int(11) NOT NULL DEFAULT '1',
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nosso_numero` text NOT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `ocorrencia_id` VARCHAR( 3 ) NULL,
  `liquidacao_id` VARCHAR( 3 ) NULL,
  `valor_titulo` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_abatimento` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_desconto` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_juros_multa` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_outros_creditos` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tag` text NOT NULL,
  PRIMARY KEY (`payment_id`,`parcela_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `module_pagamento_boleto_invoices_return` ADD `filename` VARCHAR( 200 ) NULL ;




CREATE TABLE IF NOT EXISTS `module_ies_to_polos` (
  `ies_id` mediumint(8) DEFAULT NULL,
  `polo_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`ies_id`, `polo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `module_ies_to_polos` (`ies_id`, `polo_id`) VALUES ('3', '3');
INSERT INTO `module_ies_to_polos` (`ies_id`, `polo_id`) VALUES ('2', '3');

INSERT INTO module_xuser (
id,
data_nascimento,
rg,
cpf,
cep,
endereco,
numero,
complemento,
bairro,
cidade,
uf,
telefone,
celular
) SELECT id,
data_nascimento,
rg,
cpf,
cep,
endereco,
numero,
complemento,
bairro,
cidade,
estado,
telefone,
celular
FROM c_user_details
ON DUPLICATE KEY UPDATE module_xuser.id=c_user_details.id;

ALTER TABLE `courses` ADD `enable_registration` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `price`;
ALTER TABLE `courses` ADD `price_registration` FLOAT NOT NULL DEFAULT '0' AFTER `enable_registration` ;

UPDATE `module_pagamento_types` SET `comments` = 'Pagamento em [_PAGAMENTO_PARCELAS] vezes de [_PAGAMENTO_MENSALIDADE], ao dia [_PAGAMENTO_VENCIMENTO] de cada mês, com desconto de [_PAGAMENTO_DESCONTO] para pagamento pontual.' WHERE `module_pagamento_types`.`payment_type_id` =1;
UPDATE `module_pagamento_types` SET `comments` = 'Pagamento em [_PAGAMENTO_PARCELAS] vezes de [_PAGAMENTO_MENSALIDADE], ao dia [_PAGAMENTO_VENCIMENTO] de cada mês, com desconto de [_PAGAMENTO_DESCONTO] para pagamento pontual.' WHERE `module_pagamento_types`.`payment_type_id` =2;

ALTER TABLE `courses` ADD `terms` TEXT AFTER `rules`;




CREATE TABLE IF NOT EXISTS `module_xenrollment` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `token` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `courses_id` mediumint(8) NOT NULL DEFAULT '0',
  `users_id` mediumint(8) NOT NULL DEFAULT '0',
  `payment_id` mediumint(8) NOT NULL DEFAULT '0',
  `status_id` smallint(4) NOT NULL DEFAULT '1',
  `tag` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DELETE FROM module_ies_to_polos;
INSERT module_ies_to_polos (ies_id, polo_id) SELECT ies.id, polo.id FROM `module_polos` polo, `module_ies` ies WHERE ies.id <> 1;

ALTER TABLE `module_xenrollment` ADD `ies_id` MEDIUMINT( 8 ) NOT NULL AFTER `token` ;
ALTER TABLE `module_xenrollment` CHANGE `ies_id` `ies_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '1';

ALTER TABLE `module_pagamento` ADD `enrollment_id` INT( 11 ) NULL DEFAULT NULL AFTER `payment_id`;
ALTER TABLE `module_pagamento` ADD `course_id` INT( 11 ) NULL DEFAULT NULL AFTER `enrollment_id`; 

UPDATE `courses` SET `enable_registration` = 1, `price_registration` = '440' WHERE `courses`.`id` = 20;
UPDATE `courses` SET `enable_registration` = 1, `price_registration` = '440' WHERE `courses`.`id` = 21;





CREATE TABLE IF NOT EXISTS `module_xpayment_ies_defaults` (
`ies_id` mediumint(8),
`vencimento` mediumint(8) NOT NULL default '5',
`desconto` decimal(15,4) NOT NULL default '5.0000',
`parcelas` mediumint(8) NOT NULL default '10',
`payment_type_id` mediumint(8) NOT NULL default '1',
`emitir_vencidos` tinyint(1) default '0',
PRIMARY KEY  (`ies_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `module_xpayment_ies_defaults` (`ies_id` ,`vencimento` ,`desconto` ,`parcelas` ,`payment_type_id` ,`emitir_vencidos`) VALUES (
'1', '5', '5.0000', '10', '1', '0');

INSERT INTO `module_xpayment_ies_defaults` (`ies_id` ,`vencimento` ,`desconto` ,`parcelas` ,`payment_type_id` ,`emitir_vencidos`) VALUES (
'2', '5', '5.0000', '18', '2', '0');
 
INSERT INTO `module_xpayment_ies_defaults` (`ies_id` ,`vencimento` ,`desconto` ,`parcelas` ,`payment_type_id` ,`emitir_vencidos`) VALUES (
'3', '5', '5.0000', '18', '2', '0');

CREATE TABLE IF NOT EXISTS `module_xenrollment_statuses` (
  `id` varchar(3) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `module_xenrollment_statuses` (`id` ,`descricao`) VALUES ('1', 'Iniciada');
INSERT INTO `module_xenrollment_statuses` (`id` ,`descricao`) VALUES ('2', 'Registrada');
INSERT INTO `module_xenrollment_statuses` (`id` ,`descricao`) VALUES ('3', 'Erro');

ALTER TABLE `module_xenrollment_statuses` CHANGE `descricao` `name` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `module_xenrollment` ADD `data_registro` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`;

CREATE TABLE IF NOT EXISTS `module_xdocuments_types` (
  `type_id` mediumint(8) NOT NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO module_xdocuments_types (`type_id`, `description`) VALUES (1, 'check');
INSERT INTO module_xdocuments_types (`type_id`, `description`) VALUES (2, 'upload');

ALTER TABLE `module_xdocuments_types` CHANGE `type_id` `id` MEDIUMINT( 8 ) NOT NULL;
ALTER TABLE `module_xdocuments_types` CHANGE `description` `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

DROP TABLE IF EXISTS `module_xdocuments`;
CREATE TABLE IF NOT EXISTS `module_xdocuments` (
	`document_id` mediumint(8) NOT NULL,
	`data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`name`  VARCHAR(75) NULL,
	`description` VARCHAR(255) NULL,
	`type_id` smallint(4) default '1', -- "check" OR "upload"
	`required` tinyint(1) default '1',
	`user_responsible` tinyint(1) default '0',
	`user_authority` tinyint(1) default '0',
PRIMARY KEY  (`document_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `module_xdocuments` (`document_id`, `data_registro` ,`name` ,`description` ,
`type_id` ,`required` ,`user_responsible` ,`user_authority`) VALUES (
1, CURRENT_TIMESTAMP , 'Xerox RG', 'Fotocópia do RG ou documento equivalente', '1', '1', '1', '1');
INSERT INTO `module_xdocuments` (`document_id`, `data_registro` ,`name` ,`description` ,
`type_id` ,`required` ,`user_responsible` ,`user_authority`) VALUES (
2, CURRENT_TIMESTAMP , 'Xerox CPF', 'Fotocópia do CPF', '1', '1', '1', '1');
INSERT INTO `module_xdocuments` (`document_id`, `data_registro` ,`name` ,`description` ,
`type_id` ,`required` ,`user_responsible` ,`user_authority`) VALUES (
3, CURRENT_TIMESTAMP , 'Diploma de Graduação', 'Fotocópia autenticada frente e verso do diploma da Graduação', '1', '1', '1', '1');
INSERT INTO `module_xdocuments` (`document_id`, `data_registro` ,`name` ,`description` ,
`type_id` ,`required` ,`user_responsible` ,`user_authority`) VALUES (
4, CURRENT_TIMESTAMP , 'Fotocópia Histórico Escolar', 'Fotocópia autenticada do histórico escolar da Graduação', '1', '1', '1', '1');
INSERT INTO `module_xdocuments` (`document_id`, `data_registro` ,`name` ,`description` ,
`type_id` ,`required` ,`user_responsible` ,`user_authority`) VALUES (
5, CURRENT_TIMESTAMP , 'Comprovante de Endereço', 'Fotocópia do comprovante de endereço atualizado', '1', '1', '1', '1');

CREATE TABLE IF NOT EXISTS `module_xdocuments_to_courses` (
  `document_id` mediumint(8) DEFAULT NULL,
  `course_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`document_id`, `course_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO module_xdocuments_to_courses (course_id, document_id) 
SELECT courses.`id` ,doc.`document_id` FROM `module_xdocuments` doc, courses;



CREATE TABLE IF NOT EXISTS `module_xdocuments_status` (
  `id` mediumint(8) NOT NULL,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO module_xdocuments_status (`id`, `name`) VALUES (1, 'pendente');
INSERT INTO module_xdocuments_status (`id`, `name`) VALUES (2, 'entregue');
INSERT INTO module_xdocuments_status (`id`, `name`) VALUES (3, 'em revisão');

CREATE TABLE IF NOT EXISTS `module_xenrollment_documents_status` (
  `enrollment_id` mediumint(8) DEFAULT NULL,
  `document_id` mediumint(8) NOT NULL,
  `status_id` smallint(4) default '1', 
  PRIMARY KEY (`enrollment_id`, `document_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_xies_to_users` (
  `ies_id` mediumint(8) DEFAULT NULL,
  `user_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`ies_id`, `user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO module_xies_to_users (ies_id, user_id) VALUES (1, 1);
INSERT INTO module_xies_to_users (ies_id, user_id) VALUES (2, 1);
INSERT INTO module_xies_to_users (ies_id, user_id) VALUES (3, 1);


CREATE TABLE IF NOT EXISTS `module_xpayment_course_defaults` (
  `course_id` mediumint(8) NOT NULL DEFAULT '0',
  `vencimento` mediumint(8) NOT NULL DEFAULT '5',
  `desconto` decimal(15,4) NOT NULL DEFAULT '5.0000',
  `parcelas` mediumint(8) NOT NULL DEFAULT '10',
  `payment_type_id` mediumint(8) NOT NULL DEFAULT '1',
  `emitir_vencidos` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 2011-07-22
ALTER TABLE `user_types` ADD `extended_user_type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `basic_user_type`;

UPDATE `user_types` SET `extended_user_type` = 'coordenator' WHERE `user_types`.`id` = 2;
UPDATE `user_types` SET `extended_user_type` = 'director' WHERE `user_types`.`id` = 3;
UPDATE `user_types` SET `extended_user_type` = 'financier' WHERE `user_types`.`id` = 4;
UPDATE `user_types` SET `extended_user_type` = 'secretary' WHERE `user_types`.`id` = 5;

/*
SELECT users.*, (SELECT basic_user_type FROM user_types WHERE id = users.user_type) FROM `users` WHERE user_type IN (SELECT id FROM user_types);
*/
/*
UPDATE users SET user_types_ID = user_type WHERE user_type IN (SELECT id FROM user_types) AND (user_types_ID = 0 OR user_types_ID IS NULL)
UPDATE users SET user_type = (SELECT basic_user_type FROM user_types WHERE id = users.user_type) WHERE user_type IN (SELECT id FROM user_types);
*/

/*

*/

-- 2011-07-25
ALTER TABLE `users_to_classes` CHANGE `active` `active` TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `users_to_courses` ADD `classe_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `courses_ID`;

INSERT INTO `module_xpayment_course_defaults` (
	`course_id`, `vencimento`, `desconto`, `parcelas`, `payment_type_id`, `emitir_vencidos`
)
VALUES (
	'30', '5', '5.0000', '5', '1', '0'
);



/* - Migrar os dados da tablea users_to_classes para a tabela "users_to_courses"; */
UPDATE users_to_courses uc
SET classe_id = (SELECT classes_ID FROM users_to_classes WHERE users_ID = (SELECT id FROM users WHERE login = uc.users_LOGIN) AND uc.courses_ID = (SELECT courses_ID FROM classes WHERE id = users_to_classes.classes_ID) LIMIT 1)
WHERE classe_id = 0;

/* PEGAR MATRÍCULAS PELO SITE ULT */
SELECT enroll.data_registro, enroll.status_id, enroll_stat.name as status, CONCAT_WS(' ', users.name, users.surname) as nome, users.login, users.email, det.telefone, c.name as curso, ut.name as status, users.active as ativo
FROM users_to_courses uc 
LEFT JOIN users ON (uc.users_LOGIN = users.login) 
LEFT JOIN courses c ON (uc.courses_ID = c.id)
LEFT JOIN user_types ut ON (users.user_types_ID  = ut.id)
LEFT JOIN module_xuser det ON (users.id = det.id)
LEFT OUTER JOIN module_xenrollment enroll ON (users.id = enroll.users_id AND uc.courses_ID = enroll.courses_id)
LEFT OUTER JOIN module_xenrollment_statuses enroll_stat ON (enroll.status_id = enroll_stat.id)
WHERE 
    users.user_types_ID IN ( 6, 12, 10, 16 ) AND users.user_type = 'student' 
ORDER BY enroll.data_registro DESC;

ALTER TABLE `module_xdocuments_to_courses` ADD `required` TINYINT( 1 ) NOT NULL DEFAULT '1'


CREATE TABLE `module_xpayment_send_invoices_log` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`data_registro` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;

ALTER TABLE `module_xpayment_send_invoices_log` ADD `user_send_id` INT NOT NULL ;

CREATE TABLE `module_xpayment_send_invoices_log_item` (
  `send_invoice_id` bigint(20) NOT NULL,
  `payment_id` mediumint(8) NOT NULL,
  `parcela_index` int(11) NOT NULL,
  `vencimento` timestamp NULL DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  PRIMARY KEY (`send_invoice_id`,`payment_id`,`parcela_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `module_xpayment_send_invoices_log_item` ADD `send` TINYINT( 1 ) NOT NULL ;

--2011-08-05
CREATE TABLE IF NOT EXISTS `module_xpayment_types_to_xies` (
  `payment_type_id` mediumint(8) NOT NULL,
  ies_id mediumint(8) NOT NULL,
  PRIMARY KEY (`payment_type_id`, ies_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `module_xpayment_types_to_xies` (`payment_type_id` ,`ies_id`) VALUES ('1', '1');
INSERT INTO `module_xpayment_types_to_xies` (`payment_type_id` ,`ies_id`) VALUES ('2', '2');
INSERT INTO `module_xpayment_types_to_xies` (`payment_type_id` ,`ies_id`) VALUES ('2', '3');

INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('1', '678');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('1', '679');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('1', '680');

INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('2', '457');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('2', '563');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('2', '726');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('2', '680');

INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('3', '457');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('3', '563');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('3', '726');
INSERT INTO `module_xies_to_users` (`ies_id` ,`user_id`) VALUES ('3', '680');

--2011-08-07
CREATE TABLE IF NOT EXISTS `module_xcourse_lesson_class_series`(
  `id` mediumint(8) NOT NULL,
  `name` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
ALTER TABLE `module_xcourse_lesson_class_series` CHANGE `id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `module_xcourse_lesson_class_series` ADD `default_interval` INT NOT NULL;
ALTER TABLE `module_xcourse_lesson_class_series` CHANGE `name` `name` VARCHAR( 150 ) NOT NULL ;

INSERT INTO `module_xcourse_lesson_class_series` (`id`, `name`, `default_interval`) VALUES
(1, 'Período de Estudo', 30),
(2, 'Prova Online', 5),
(3, 'Período de Web Tutoria', 20),
(4, 'Entrega do Artigo', 20),
(5, 'Prova Presencial', 1),
(6, 'Prova Substitutiva', 1);

CREATE TABLE IF NOT EXISTS `module_xcourse_lesson_class_calendar`(
  `course_id` mediumint(8) NOT NULL,
  `lesson_id` mediumint(8) NOT NULL,
  `classe_id` mediumint(8) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_id`,`lesson_id`,`classe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_xcourse_lesson_class_calendar_series`(
  `course_id` mediumint(8) NOT NULL,
  `lesson_id` mediumint(8) NOT NULL,
  `classe_id` mediumint(8) NOT NULL,
  `serie_id` mediumint(8) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_id`,`lesson_id`,`classe_id`,`serie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--2011-08-15
CREATE TABLE IF NOT EXISTS `module_xenrollment_history` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `enrollment_id` mediumint(8) NOT NULL,
  `data_registro` timestamp NULL DEFAULT NULL,
  `status_id` mediumint(8) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--2011-08-17
DROP TABLE IF EXISTS `module_xcms_pages`;
CREATE TABLE IF NOT EXISTS `module_xcms_pages` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `positions` text,
  `rules` text,
--  `tempĺate_file` varchar(255) NOT NULL,
--  `fixed_data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `module_xcms_blocks`;
CREATE TABLE IF NOT EXISTS `module_xcms_blocks` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `tag` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `module_xcms_pages_to_blocks`;
CREATE TABLE IF NOT EXISTS `module_xcms_pages_to_blocks` (
  `page_id` mediumint(8) NOT NULL,
  `block_id` mediumint(8) NOT NULL,
  `tag` text,
  PRIMARY KEY (`page_id`, `block_id`)
) ENGINE=MyISAM;


TRUNCATE TABLE module_xcms_pages;
TRUNCATE TABLE module_xcms_blocks;
TRUNCATE TABLE module_xcms_pages_to_blocks;

INSERT INTO `module_xcms_pages` (`id` ,`name` ,`positions`) VALUES (NULL,  'Página Inicial Extensão', NULL);
INSERT INTO `module_xcms_pages` (`id` ,`name` ,`positions`) VALUES (NULL,  'Página Inicial Curso', NULL);

INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterCalendar', 'xcms', 'load_calendar', NULL);
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterNewsletter', 'xcms', 'load_newsletter', NULL);
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterSocialNetworks', 'social', 'load_social_network', NULL);
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterNews', 'xcms', 'load_news', NULL);
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterCourseProjects', 'xprojects', 'load_course_projects', NULL);
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterCourseGroups', 'xprojects', 'load_course_groups', NULL);

INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '1', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '2', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '3', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '4', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('2', '5', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('2', '6', NULL);


ALTER TABLE  `module_polos` ADD  `geo_lat` DOUBLE NOT NULL DEFAULT  '0' AFTER  `uf` ,
ADD  `geo_lng` DOUBLE NOT NULL DEFAULT  '0' AFTER  `geo_lat` ,
ADD  `geo_search` TEXT NULL AFTER  `geo_lng`


/*
id
title
data
deadline
creator_LOGIN
lessons_ID
auto_assign
metadata
*/
--2011-08-18


/*
CREATE TABLE IF NOT EXISTS `module_xprojects` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `scope_id` smallint(4) NOT NULL, -- link_to "module_xentify_scopes"
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO  `module_xprojects` (`id` ,`title` ,`description` ,`scope_id`) VALUES (NULL ,  'Projeto teste',  
'Teste de projeto... A descrição poderá ser alterada para cada um das instâncias de execução do projeto. Um grupo de alunos da mesma turma, ou da mesma instituição, ou do mesmo polo, etc...',  '6');

CREATE TABLE IF NOT EXISTS `module_xprojects_topics` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 1');
INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 2');
INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 3');

CREATE TABLE IF NOT EXISTS `module_xprojects_groups` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `topic_id` mediumint(8) NOT NULL,
  `description` text NULL,
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `module_xprojects_groups_to_users` (
  `user_id` mediumint(8) NOT NULL,
  `topic_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`user_id`, `topic_id`)
) ENGINE=MyISAM;
*/
-- 2011-08-19
CREATE TABLE IF NOT EXISTS `module_xwebtutoria` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `classe_id` mediumint(8) NOT NULL,
  `status_id` mediumint(8) NOT NULL,
  `avaliation_id` mediumint(8) NOT NULL,
  `body` varchar(100) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `module_xwebtutoria_status` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO  `module_xwebtutoria_status` (`id` ,`name`) VALUES ('1',  'Ativa');
INSERT INTO  `module_xwebtutoria_status` (`id` ,`name`) VALUES ('2',  'Publicada');
INSERT INTO  `module_xwebtutoria_status` (`id` ,`name`) VALUES ('3',  'Cancelada');
INSERT INTO  `module_xwebtutoria_status` (`id` ,`name`) VALUES ('4',  'Oculta');

CREATE TABLE IF NOT EXISTS `module_xwebtutoria_avaliation` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO  `module_xwebtutoria_avaliation` (`id` ,`name`) VALUES ('1',  'Ótima');
INSERT INTO  `module_xwebtutoria_avaliation` (`id` ,`name`) VALUES ('2',  'Boa');
INSERT INTO  `module_xwebtutoria_avaliation` (`id` ,`name`) VALUES ('3',  'Regular');
INSERT INTO  `module_xwebtutoria_avaliation` (`id` ,`name`) VALUES ('4',  'Ruim');
INSERT INTO  `module_xwebtutoria_avaliation` (`id` ,`name`) VALUES ('5',  'Péssima');



-- 2011-08-25
ALTER TABLE  `module_xcms_pages` ADD  `rules` text DEFAULT  '' AFTER  `positions`;

ALTER TABLE  `module_xcms_pages` ADD  `layout` ENUM(  'onecolumn',  'twocolumn-50-50',  'twocolumn-75-25',  'twocolumn-25-75',  'threecolumn-33-34-33' ) NOT NULL DEFAULT  'onecolumn' AFTER `name`;


-- 2011-08-30
DROP TABLE IF EXISTS `module_xenrollment_history`;
CREATE TABLE IF NOT EXISTS `module_xenrollment_history` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `enrollment_id` mediumint(8) NOT NULL,
  `status_id` mediumint(8) NOT NULL DEFAULT 0, -- IF USED, REFER TO STATUS CHANGE HISTORY
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

ALTER TABLE  `module_xenrollment` CHANGE  `data_registro`  `data_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
/*
CREATE TABLE IF NOT EXISTS `module_xentify_scopes` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `rules` text, -- @todo Define Projects Scope Rules
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por IES', '%s poderá ser compartilhado entre alunos da mesma IES', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por Polo', '%s poderá ser compartilhado entre alunos do mesmo polo', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por Curso', '%s poderá ser compartilhado entre alunos do mesmo curso', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por Turma', '%s poderá ser compartilhado entre alunos da mesma turma', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por Disciplina', '%s poderá ser compartilhado entre alunos do mesma disciplina', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por Disciplina e Turma', '%s poderá ser compartilhado somente entre alunos da mesma disciplina e mesma turma', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Individual', '%s não poderá ser compartilhado.', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por papel de usuário', '%s poderá ser compartilhado por papel de usuário.', '{}');
INSERT INTO module_xentify_scopes (id, name, description, rules) VALUES (NULL, 'Agrupado por tipo de usuário', '%s poderá ser compartilhado por tipo de usuário.', '{}');
*/
ALTER TABLE  `module_xcms_pages` ADD  `type` INT NOT NULL DEFAULT  '0' AFTER  `name`;

UPDATE  `maguser_root`.`module_xcms_pages` SET  `type` =  '1' WHERE  `module_xcms_pages`.`id` = 1;
UPDATE  `maguser_root`.`module_xcms_pages` SET  `type` =  '2' WHERE  `module_xcms_pages`.`id` = 2;

-- 2011-09-01
INSERT INTO `maguser_root`.`module_xenrollment_statuses` (`id` ,`name`) VALUES ('5', 'Cancelamento Solicitado');
INSERT INTO `maguser_root`.`module_xenrollment_statuses` (`id` ,`name`) VALUES ('6', 'Matriculado');

-- 2011-09-13
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterWebtutoriaLastItens', 'xwebtutoria', 'load_webtutoria_last_itens', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('2', '7', NULL);

INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterQuickContactList', 'quick_mails', 'load_quick_contact_list', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '8', NULL);

-- 2011-09-15
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterMainBillboard', 'billboard', 'load_main_billboard', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '9', NULL);

INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterRssFeedsList', 'rss', 'load_main_feeds_list', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '10', NULL);

INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterCourseUserActivity', 'xcourse', 'load_course_user_activity', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '11', NULL);


ALTER TABLE `module_billboard` ADD `data_registro` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL;

UPDATE `maguser_root`.`module_billboard` SET `data_registro` = TIMESTAMP( '2011-09-15 23:41:05' ) WHERE `module_billboard`.`lessons_ID` = -1;
ALTER TABLE `module_xcms_pages` CHANGE `layout` `layout` ENUM( 'onecolumn', 'twocolumn-50-50', 'twocolumn-75-25', 'twocolumn-25-75', 'twocolumn-66-33', 'twocolumn-33-66', 'threecolumn-33-34-33' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'onecolumn';



-- 2011-09-20
ALTER TABLE `module_billboard` ADD `user_type` varchar(50) NOT NULL DEFAULT 'student';


-- 2011-09-22
INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterAcademicCalendar', 'xcourse', 'load_academic_calendar', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '12', NULL);



ALTER TABLE `module_xuser` CHANGE `uf` `uf` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `module_xuser` CHANGE `cidade` `cidade` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `module_xuser` CHANGE `numero` `numero` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `module_xuser` CHANGE `cep` `cep` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `module_xuser` CHANGE `endereco` `endereco` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


-- 2011-09-28
ALTER TABLE `module_xuser_responsible` ADD `type` VARCHAR( 20 ) NOT NULL DEFAULT 'parents' AFTER `id`;
ALTER TABLE `module_xuser_responsible` DROP PRIMARY KEY;
ALTER TABLE `module_xuser_responsible` ADD PRIMARY KEY ( `id` , `type` );

-- 2011-09-29
CREATE TABLE IF NOT EXISTS `module_xpayment_user_ammount_types` (
  `user_id` mediumint(8) NOT NULL,
  `ammount_type` varchar(20) NOT NULL,
  `value` mediumint(8) NOT NULL,
  PRIMARY KEY (`user_id`, `ammount_type`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `module_xpayment_to_send_list` (
  `payment_id` mediumint(8) NOT NULL,
  `parcela_index` varchar(20) NOT NULL,
  PRIMARY KEY (`payment_id`, `parcela_index`)
) ENGINE=MyISAM;

--2011-10-03
ALTER TABLE `module_xpayment_to_send_list` DROP PRIMARY KEY;
ALTER TABLE `module_xpayment_to_send_list` ADD `ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `module_xpayment_to_send_list` CHANGE `ID` `id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `module_xpayment_to_send_list` ADD `data_envio` TIMESTAMP NOT NULL AFTER `id`;a 
ALTER TABLE `module_xpayment_to_send_list` CHANGE `data_envio` `data_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
RENAME TABLE `module_xpayment_to_send_list` TO `maguser_root`.`module_xpayment_to_send_list_item` ;
ALTER TABLE `module_xpayment_to_send_list_item` DROP `data_envio` ;
ALTER TABLE `module_xpayment_to_send_list_item` CHANGE `id` `send_id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT 

CREATE TABLE IF NOT EXISTS `module_xpayment_to_send_list` (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM;

ALTER TABLE `module_xpayment_to_send_list_item` CHANGE `send_id` `send_id` BIGINT( 20 ) NOT NULL ;
ALTER TABLE `module_xpayment_to_send_list_item` DROP PRIMARY KEY;
ALTER TABLE `module_xpayment_to_send_list_item` ADD PRIMARY KEY ( `send_id` , `payment_id` , `parcela_index` );


ALTER TABLE `module_pagamento` ADD `send_to` VARCHAR( 20 ) NOT NULL DEFAULT 'student' AFTER `data_inicio`;

-- 2011-10-04
ALTER TABLE `module_xuser_responsible` ADD `cep` VARCHAR( 15 ) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `endereco` varchar(150) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `numero` varchar(15) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `complemento` varchar(50) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `bairro` varchar(100) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `cidade` varchar(100) NULL DEFAULT NULL;
ALTER TABLE `module_xuser_responsible` ADD `uf` varchar(20) NULL DEFAULT NULL;



INSERT INTO `module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'magesterStudentGuidance', 'xcms', 'load_student_guidance', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '13', NULL);





----- SQL PARA ADAPTAR PARA O MODULO DE PAGAMENTO

/*
SELECT * FROM module_pagamento_invoices 
WHERE pago = 2  AND parcela_index = 1
ORDER BY pago DESC*/


/*
SELECT * FROM module_pagamento_invoices 
WHERE payment_id NOT IN (SELECT payment_id FROM module_pagamento)
*/
/*
SELECT * FROM module_pagamento_boleto_invoices_return ret
LEFT JOIN module_pagamento_invoices inv ON (inv.payment_id = ret.payment_id AND inv.parcela_index = ret.parcela_index);
*/




SELECT 
enr.id, enr.status_id, enr.courses_id, enr.users_id,

inv.payment_type_id, GROUP_CONCAT(pag2ies.ies_id ORDER BY pag2ies.ies_id ASC SEPARATOR ',') as ies_id, GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, inv.parcela_index, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas, inv.payment_id, inv.invoice_id AS nosso_numero, users.id as user_id, users.login, users.name, users.surname, inv.data_vencimento, inv.valor as valor_total
FROM module_pagamento_invoices inv 
JOIN module_pagamento pag ON (pag.payment_id = inv.payment_id) JOIN users ON (pag.user_id = users.id) 
JOIN module_xenrollment enr ON (inv.payment_id = enr.payment_id)
LEFT OUTER JOIN module_xpayment_types_to_xies pag2ies ON (pag.payment_type_id = pag2ies.payment_type_id) LEFT OUTER JOIN module_ies ies ON (pag2ies.ies_id = ies.id) 
WHERE pag2ies.ies_id IN (0,1) 
AND inv.pago = 0 
AND inv.data_vencimento > CURRENT_DATE
AND inv.data_vencimento < DATE_ADD(CURRENT_DATE, INTERVAL '10' DAY)
AND inv.data_vencimento <> '0000-00-00' 
AND inv.data_vencimento <> '0000-00-00' 
AND inv.parcela_index > 1 
-- AND enr.status_id = 4
GROUP BY inv.payment_id, inv.parcela_index 
ORDER BY enr.id, enr.status_id, enr.courses_id;



SELECT GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, CONCAT(users.name, ' ', users.surname) as aluno, users.login,  c.name as curso, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = ret.payment_id) as total_parcelas, ret.data_pagamento

FROM module_pagamento pag
JOIN module_xenrollment enr ON (pag.payment_id = enr.payment_id)
JOIN courses c ON (c.id = enr.courses_id)
LEFT join users ON (pag.user_id = users.id) 
LEFT join module_pagamento_invoices inv ON (inv.payment_id = pag.payment_id) 
LEFT OUTER join module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id) 
LEFT OUTER join module_ies ies ON (pag2ies.ies_id = ies.id) 

WHERE 
pag2ies.ies_id IN (0,1) 
AND pag2ies.ies_id = 1 
AND inv.parcela_index = 1 




GROUP BY ret.payment_id, ret.parcela_index 
ORDER BY ret.data_pagamento DESC, users.name ASC ;



/*
DELETE FROM module_xenrollment 
WHERE 
users_id = 0 
OR courses_id = 0;
*/

/*
SELECT 
enr.id, enr.users_id, inv.pago, enr.courses_id, enr.payment_id, enr.status_id,
inv.parcela_index, inv.invoice_id as nosso_numero, inv.data_vencimento, 


GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, CONCAT(users.name, ' ', users.surname) as aluno, users.login,  c.name as curso, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas

FROM module_pagamento pag
JOIN module_xenrollment enr ON (pag.payment_id = enr.payment_id)
JOIN courses c ON (c.id = enr.courses_id)
LEFT join users ON (pag.user_id = users.id) 
LEFT join module_pagamento_invoices inv ON (inv.payment_id = pag.payment_id) 
LEFT OUTER join module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id) 
LEFT OUTER join module_ies ies ON (pag2ies.ies_id = ies.id) 

WHERE 
pag2ies.ies_id IN (0,1) 
AND pag2ies.ies_id = 1 
AND inv.parcela_index = 1 
AND inv.pago <> 0
AND inv.pago <> 2

GROUP BY inv.payment_id, inv.parcela_index 
ORDER BY inv.pago DESC, inv.data_vencimento DESC, users.name ASC ;
*/


/*
SELECT 

GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, CONCAT(users.name, ' ', users.surname) as aluno, users.login,  c.name as curso, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas
FROM module_pagamento pag
JOIN module_xenrollment enr ON (pag.payment_id = enr.payment_id)
JOIN courses c ON (c.id = enr.courses_id)
LEFT join users ON (pag.user_id = users.id) 
LEFT join module_pagamento_invoices inv ON (inv.payment_id = pag.payment_id) 
LEFT OUTER join module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id) 
LEFT OUTER join module_ies ies ON (pag2ies.ies_id = ies.id) 

WHERE 
pag2ies.ies_id IN (0,1) 
AND pag2ies.ies_id = 1 
AND inv.parcela_index = 1 
AND inv.pago <> 0
AND inv.pago <> 2

GROUP BY inv.payment_id, inv.parcela_index 
ORDER BY inv.pago DESC, inv.data_vencimento DESC, users.name ASC ;
*/


/*
SELECT 
enr.id, enr.users_id, inv.pago, enr.courses_id, enr.payment_id, enr.status_id,
inv.parcela_index, inv.invoice_id as nosso_numero, inv.data_vencimento, 


GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, CONCAT(users.name, ' ', users.surname) as aluno, users.login,  c.name as curso, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas

FROM module_pagamento pag
JOIN module_xenrollment enr ON (pag.payment_id = enr.payment_id)
JOIN courses c ON (c.id = enr.courses_id)
LEFT join users ON (pag.user_id = users.id) 
LEFT join module_pagamento_invoices inv ON (inv.payment_id = pag.payment_id) 
LEFT OUTER join module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id) 
LEFT OUTER join module_ies ies ON (pag2ies.ies_id = ies.id) 

WHERE 
pag2ies.ies_id IN (0,1) 
AND pag2ies.ies_id = 1 
AND inv.parcela_index = 1 
AND inv.pago <> 0
AND inv.pago <> 2

GROUP BY inv.payment_id, inv.parcela_index 
ORDER BY inv.pago DESC, inv.data_vencimento DESC, users.name ASC ;
*/



SELECT 

GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies, CONCAT(users.name, ' ', users.surname) as aluno, users.login,  c.name as curso, (select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas
FROM module_pagamento pag
JOIN module_xenrollment enr ON (pag.payment_id = enr.payment_id)
JOIN courses c ON (c.id = enr.courses_id)
LEFT join users ON (pag.user_id = users.id) 
LEFT join module_pagamento_invoices inv ON (inv.payment_id = pag.payment_id) 
LEFT OUTER join module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id) 
LEFT OUTER join module_ies ies ON (pag2ies.ies_id = ies.id) 

WHERE 
pag2ies.ies_id IN (0,1) 
AND pag2ies.ies_id = 1 
AND inv.parcela_index = 1 
AND inv.pago <> 0
AND inv.pago <> 2

GROUP BY inv.payment_id, inv.parcela_index 
ORDER BY inv.pago DESC, inv.data_vencimento DESC, users.name ASC;





/* BOLETOS A PAGAR */
/*
SELECT pag.payment_id, c.id as course_id, u.id as user_id, CONCAT(u.name, ' ', u.surname) as aluno, u.login, c.name as curso, inv.invoice_id nosso_numero, 
inv.data_vencimento,
(SELECT status_id FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id),
CASE (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id)
WHEN 1 THEN 'Baixa Manual'
WHEN 2 THEN 'Baixa Automática'
END as tipo_pagto
FROM module_pagamento_invoices inv
LEFT JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)
LEFT JOIN users u ON (pag.user_id = u.id)
LEFT JOIN courses c ON (pag.course_id = c.id)
WHERE inv.data_vencimento > '2011-09-05'
AND inv.data_vencimento < '2011-09-30'
--AND parcela_index = 2
AND pag.payment_type_id = 1
AND (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id) <> 0
AND c.id NOT IN (26, 30)
AND u.active = 1
ORDER BY u.name
*/

SELECT pag.payment_id, c.id as course_id, u.id as user_id, u.active, CONCAT(u.name, ' ', u.surname) as aluno, u.login, c.name as curso, inv.invoice_id nosso_numero, inv.parcela_index, 
inv.data_vencimento,
(SELECT data_vencimento FROM module_pagamento_invoices WHERE parcela_index = (inv.parcela_index-1) AND payment_id = inv.payment_id),
CASE (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = (inv.parcela_index-1) AND payment_id = inv.payment_id)
WHEN 1 THEN 'Baixa Manual'
WHEN 2 THEN 'Baixa Automática'
END as tipo_pagto
FROM module_pagamento_invoices inv
LEFT JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)
LEFT JOIN users u ON (pag.user_id = u.id)
LEFT JOIN courses c ON (pag.course_id = c.id)
WHERE inv.data_vencimento > '2011-09-05'
AND inv.data_vencimento < '2011-09-30'
/* AND parcela_index = 2 */ 
/* AND pag.payment_type_id = 1 */
AND (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = (inv.parcela_index-1) AND payment_id = inv.payment_id) <> 0
/* AND c.id NOT IN (26, 30) */
AND pag.payment_id IN (40, 41, 42, 43, 44, 45, 46, 49, 50, 51, 52, 53, 54, 56, 57, 58, 60, 61)
AND inv.bloqueio = 0
AND u.active = 1
AND inv.pago = 0
ORDER BY pag.payment_id, u.name




- Email da Adriane
[ ok ]	- Disponibilizar acesso a fati e fajar 
[ ok ] 	- Escolhas dos temas do grupo fora do fórum
	- Domínio dos alunos para o projeto.
[ ok ]	- Está aparecendo a aula inaugural (no mural) em todos os cursos.
- Aluno que pagou e não baixou
	Verificar com o banco. linha digitável correta.
[ ok ] - Retirar período de web-tutoria
- Contato privado entre os grupos dos projetos
- Links importantes "Orientações ao Aluno"





--2011-10-17
CREATE TABLE IF NOT EXISTS `module_xprojects` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `scope_id` smallint(4) NOT NULL, -- link_to "module_xentify_scopes"
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;# MySQL returned an empty result set (i.e. zero rows).

/*
INSERT INTO  `module_xprojects` (`id` ,`title` ,`description` ,`scope_id`) VALUES (NULL ,  'Projeto teste',  
'Teste de projeto... A descrição poderá ser alterada para cada um das instâncias de execução do projeto. Um grupo de alunos da mesma turma, ou da mesma instituição, ou do mesmo polo, etc...',  '6');
*/
CREATE TABLE IF NOT EXISTS `module_xprojects_topics` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;# MySQL returned an empty result set (i.e. zero rows).

/*
INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 1');
INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 2');
INSERT INTO `module_xprojects_topics` (`id`, `project_id`, `title`) VALUES (NULL, '1', 'Tema 3');
*/
CREATE TABLE IF NOT EXISTS `module_xprojects_groups` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `topic_id` mediumint(8) NOT NULL,
  `description` text NULL,
  `page_id` mediumint(8) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;# MySQL returned an empty result set (i.e. zero rows).


CREATE TABLE IF NOT EXISTS `module_xprojects_groups_to_users` (
  `user_id` mediumint(8) NOT NULL,
  `topic_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`user_id`, `topic_id`)
) ENGINE=MyISAM;# MySQL returned an empty result set (i.e. zero rows).



INSERT INTO `module_xcms_pages` (`id` ,`name` ,`type` ,`layout` ,`positions` ,`rules`) VALUES (
'3', 'Página Inicial Curso / Projeto', '2', 'onecolumn', NULL , NULL);

INSERT INTO `maguser_root`.`module_xcms_blocks` (
`id` ,
`name` ,
`module` ,
`action` ,
`tag`
)
VALUES (
NULL , 'magesterCmsTextBlock', 'xcms', 'load_cms_text', NULL
);
UPDATE `maguser_root`.`module_xcms_blocks` SET `name` = 'magesterProjectsGroupsLanding',
`module` = 'xprojects',
`action` = 'load_groups_landing' WHERE `module_xcms_blocks`.`id` =14;


INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('3', '14', NULL);




ALTER TABLE `f_forums` ADD `group_topic_id` INT NOT NULL AFTER `lessons_ID` ;
ALTER TABLE `f_forums` CHANGE `group_topic_id` `group_topic_id` INT( 11 ) NOT NULL DEFAULT '0';








INSERT INTO `module_xprojects` (`id`, `title`, `description`, `scope_id`, `page_id`) VALUES (NULL, 'Projeto Curso Engenharia de Software', '', '0', NULL);

INSERT INTO `module_xprojects_topics` (`id` ,`project_id` ,`title` ,`page_id`)VALUES (
NULL , '1', 'Sistema de Locadora de Veículo', NULL);
INSERT INTO `module_xprojects_topics` (`id` ,`project_id` ,`title` ,`page_id`)VALUES (
NULL , '1', 'Sistema de Biblioteca', NULL);
INSERT INTO `module_xprojects_topics` (`id` ,`project_id` ,`title` ,`page_id`)VALUES (
NULL , '1', 'Sistema de Controle de Ordem de Serviços (Oficina Mecânica)', NULL);
INSERT INTO `module_xprojects_topics` (`id` ,`project_id` ,`title` ,`page_id`)VALUES (
NULL , '1', 'Sistema  Acadêmico', NULL);
INSERT INTO `module_xprojects_topics` (`id` ,`project_id` ,`title` ,`page_id`)VALUES (
NULL , '1', 'Sistema de Consultório Médico', NULL);

INSERT INTO `module_xprojects_groups` (`id` ,`topic_id` ,`description` ,`page_id`)VALUES (NULL , '1', NULL , 3);
INSERT INTO `module_xprojects_groups` (`id` ,`topic_id` ,`description` ,`page_id`)VALUES (NULL , '2', NULL , 3);
INSERT INTO `module_xprojects_groups` (`id` ,`topic_id` ,`description` ,`page_id`)VALUES (NULL , '3', NULL , 3);
INSERT INTO `module_xprojects_groups` (`id` ,`topic_id` ,`description` ,`page_id`)VALUES (NULL , '4', NULL , 3);
INSERT INTO `module_xprojects_groups` (`id` ,`topic_id` ,`description` ,`page_id`)VALUES (NULL , '5', NULL , 3);


UPDATE `module_xcms_blocks` SET `module` = 'xcourse' WHERE `module_xcms_blocks`.`id` = 13;



UPDATE `module_billboard` SET `data` = '<script type="text/javascript" src="/jwplayer/jwplayer.js"></script> <h2>Aula Inaugural</h2> <div id="video_container">Carregando </div> <script type="text/javascript"> jwplayer("video_container").setup({ flashplayer: "/jwplayer/player.swf", file: "/public_data/pos/engenharia/aula_magna.f4v", height: 400, width: ''100%'' }); </script> ' WHERE `module_billboard`.`lessons_ID` = -10;

INSERT INTO `module_billboard` (
`lessons_ID` ,
`course_id` ,
`data` ,
`data_registro` ,
`user_type`
)
VALUES (
'-20', '20', '<script type="text/javascript" src="/jwplayer/jwplayer.js"></script> <h2>Aula Inaugural</h2> <div id="video_container">Carregando </div> <script type="text/javascript"> jwplayer("video_container").setup({ flashplayer: "/jwplayer/player.swf", file: "/public_data/pos/bioenergia/aula_magna.avi", height: 400, width: ''100%'' }); </script> ', '2011-10-17 13:46:47', 'student'
);




--2011-10-19
INSERT INTO `module_xcms_blocks` (`id` ,`name` ,`module` ,`action` ,`tag`)VALUES (NULL , 'magesterPaymentDueInvoicesBlock', 'xpayment', 'load_due_invoices', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id` ,`block_id` ,`tag`)VALUES ('1', '15', NULL);


-- 2011-10-24
INSERT INTO `module_xcms_blocks` (`id` ,`name` ,`module` ,`action` ,`tag`) VALUES (NULL , 'magesterProjectsGroupsMembers', 'xprojects', 'load_groups_members', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('3', '16', NULL);

INSERT INTO `module_xcms_blocks` (`id` ,`name` ,`module` ,`action` ,`tag`) VALUES (NULL , 'magesterProjectsGroupsForum', 'xprojects', 'load_groups_forum', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('3', '17', NULL);]

ALTER TABLE  `module_xprojects_groups` ADD  `tag` TEXT NOT NULL
UPDATE  `module_xprojects_groups` SET  `tag` = '[{"label":"Host","value":"grupo1.grupos.magester.net"},{"label":"Usuário","value":"grupo1@magester.net"},{"label":"Senha","value":"61xeuy7epn"}]' WHERE `module_xprojects_groups`.`id` =1;
UPDATE  `module_xprojects_groups` SET  `tag` = '[{"label":"host","value":"grupo2.grupos.magester.net"},{"label":"Usuário","value":"grupo2@magester.net"},{"label":"Senha","value":"2n48mokl46"}]' WHERE `module_xprojects_groups`.`id` =2;
UPDATE  `module_xprojects_groups` SET  `tag` = '[{"label":"Host","value":"grupo3.grupos.magester.net"},{"label":"Usuário","value":"grupo3@magester.net"},{"label":"Senha","value":"nmnkx9gg57"}]' WHERE `module_xprojects_groups`.`id` =3;
UPDATE  `module_xprojects_groups` SET  `tag` =  '[{"label":"Host","value":""},{"label":"Usuário","value":""},{"label":"Senha","value":""}]
' WHERE  `module_xprojects_groups`.`id` =4;
UPDATE  `module_xprojects_groups` SET  `tag` =  '[{"label":"Host","value":""},{"label":"Usuário","value":""},{"label":"Senha","value":""}]
' WHERE  `module_xprojects_groups`.`id` =5;
UPDATE  `module_xprojects_groups` SET  `tag` =  '[{"label":"Host","value":""},{"label":"Usuário","value":""},{"label":"Senha","value":""}]
' WHERE  `module_xprojects_groups`.`id` =-1;


UPDATE `module_xcms_pages` SET  `layout` =  'twocolumn-66-33' WHERE  `module_xcms_pages`.`id` =3;
UPDATE `module_xcms_pages` SET  `positions` =  '[["magesterProjectsGroupsLanding", "magesterProjectsGroupsForum"],["magesterProjectsGroupsMembers"]]' WHERE `module_xcms_pages`.`id` =3;

-- 2011-10-25
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1418',  '1');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1418',  '2');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1418',  '3');

INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1386',  '1');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1386',  '2');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1386',  '3');

INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1360',  '1');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1360',  '2');
INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('1360',  '3');

INSERT INTO  `module_xprojects_groups_to_users` (`user_id` ,`topic_id`) VALUES ('60',  '-1');

INSERT INTO `module_xcms_blocks` (`id` ,`name` ,`module` ,`action` ,`tag`) VALUES (NULL , 'magesterProjectsGroupsFileList', 'xprojects', 'load_groups_file_list', NULL);
INSERT INTO `module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('3', '18', NULL);

--2001-11-01
ALTER TABLE `module_billboard` ADD `scripts` TEXT NOT NULL AFTER `data` ;

--2011-11-09
ALTER TABLE  `module_xuser` ADD  `instituicao_formacao` VARCHAR( 100 ) NULL;
ALTER TABLE  `module_xuser` ADD  `empregabilidade` VARCHAR( 100 ) NULL;
ALTER TABLE  `module_xuser` ADD  `escolaridade` VARCHAR( 100 ) NULL;

-- 2011-11-10
CREATE TABLE IF NOT EXISTS `classes_to_content` (
  `classe_id` mediumint(8) unsigned NOT NULL,
  `content_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`classe_id`,`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--2011-11-28
CREATE TABLE IF NOT EXISTS `module_xcontent_schedule` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `xentify_scope_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL default 1,

  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO  `SysClass_root`.`module_xcms_blocks` (
`id` ,
`name` ,
`module` ,
`action` ,
`tag`
)
VALUES (
NULL ,  'magesterContentScheduleList',  'xcontent',  'load_content_schedule_list',  ''
);

INSERT INTO `SysClass_root`.`module_xcms_pages_to_blocks` (`page_id`, `block_id`, `tag`) VALUES ('1', '19', NULL);

-- 2011-11-30
/* FOR COURSE SKILL MANAGEMENT */
CREATE TABLE module_xskill (
	id mediumint(8) NOT NULL AUTO_INCREMENT,
	xentify_scope_id mediumint(8) unsigned NOT NULL DEFAULT 0,
	name VARCHAR (100) NOT NULL,
	description TEXT,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE module_xskill_content2skills (
	content_id mediumint(8) NOT NULL,
	skill_id mediumint(8) NOT NULL,
	`require` tinyint(1) NOT NULL DEFAULT 1,
	`provide` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (content_id, skill_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE module_xskill_course2skills (
	course_id mediumint(8) NOT NULL,
	skill_id mediumint(8) NOT NULL,
	`require` tinyint(1) NOT NULL DEFAULT 1,
	`provide` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (course_id, skill_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE module_xskill_lessons2skills (
	lesson_id mediumint(8) NOT NULL,
	skill_id mediumint(8) NOT NULL,
	`require` tinyint(1) NOT NULL DEFAULT 1,
	`provide` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (lesson_id, skill_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE module_xskill_users (
	user_id mediumint(8) NOT NULL,
	skill_id mediumint(8) NOT NULL,
	PRIMARY KEY (user_id, skill_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--2011-11-30
ALTER TABLE  `courses` ADD  `enable_professional` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `price_web`;
ALTER TABLE  `courses` ADD  `price_professional` float NULL DEFAULT 0 AFTER  `enable_professional`;
ALTER TABLE  `courses` ADD  `enable_vip` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `price_professional`;
ALTER TABLE  `courses` ADD  `price_vip` float NULL DEFAULT 0 AFTER  `enable_vip`;



--2011-12-05
INSERT INTO `module_xentify_scopes` (`id`, `name`, `description`, `rules`) VALUES (10, 'Agrupado por Polo e Turma', '%s poderá ser compartilhado somente entre alunos da mesma disciplina e mesmo polo', '{}');


ALTER TABLE  `module_xcontent_schedule` DROP PRIMARY KEY;
ALTER TABLE  `module_xcontent_schedule` ADD  `id` BIGINT NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY (  `id` );

CREATE TABLE IF NOT EXISTS `module_xcontent_schedule_itens` (
  `schedule_id` bigint(20) NOT NULL,
  `index` mediumint(8) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`schedule_id`, `index` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE  `module_xcontent_schedule` CHANGE  `xentify_id`  `xentify_id` TEXT NOT NULL;


CREATE TABLE IF NOT EXISTS `module_xcontent_schedule_users` (
  `schedule_id` bigint(20) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `index` mediumint(8) NOT NULL,
  PRIMARY KEY (`schedule_id`, `user_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/* Schedule Ref. */
/*
21 - Engenharia de Software - IBM
	91 - Modelagem de Negócio e Gerência de Requisitos de Software com Casos de Uso
		2067 - Avaliação Presencial	
			15 => 2011/1
	94 - Gerência de Projetos I (PMBOK)
		2066 - Avaliação Presencial
			15 => 2011/1
			31 => 2011/2
	90 - Processo de Desenvolvimento de Software (Fundamentos e Implementação do RUP)
		2058 - Avaliação Presencial
			15 => 2011/1
			31 => 2011/2

20 - Bioenergia
	64 - Geração e Caracterização de Materia-Prima I
		2061 - Avaliação Presencial
			14 => 2011/1
	65 - Geração e Caracterização de Materia-Prima II
		2075 - Avaliação Presencial
			14 => 2011/1
31 - Pós-Graduação com Gestão em ERP
	106 - Gestão por Processos com ERP SAP
		2076 - Avaliação Presencial
			30 => 2011/1
			37 => 2011/2
	107 - Processos de Contabilidade Gerencial e Financeira
		2077 - Avaliação Presencial
			30 => 2011/1
*/

INSERT INTO `module_xcontent_schedule` (`id`, `content_id`, `xentify_scope_id`, `xentify_id`, `start`, `end`, `block_html`, `active`) VALUES
(1, 2058, 10, '1;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(2, 2066, 10, '1;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(3, 2067, 10, '1;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(4, 2058, 10, '1;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(5, 2066, 10, '1;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(6, 2061, 10, '1;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(7, 2075, 10, '1;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(8, 2076, 10, '1;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(9, 2076, 10, '1;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(10, 2077, 10, '1;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(11, 2058, 10, '2;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(12, 2066, 10, '2;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(13, 2067, 10, '2;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(14, 2058, 10, '2;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(15, 2066, 10, '2;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(16, 2061, 10, '2;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(17, 2075, 10, '2;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(18, 2076, 10, '2;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(19, 2076, 10, '2;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(20, 2077, 10, '2;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(21, 2058, 10, '3;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(22, 2066, 10, '3;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(23, 2067, 10, '3;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(24, 2058, 10, '3;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(25, 2066, 10, '3;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(26, 2061, 10, '3;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(27, 2075, 10, '3;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(28, 2076, 10, '3;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(29, 2076, 10, '3;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(30, 2077, 10, '3;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(31, 2058, 10, '4;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(32, 2066, 10, '4;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(33, 2067, 10, '4;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(34, 2058, 10, '4;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(35, 2066, 10, '4;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(36, 2061, 10, '4;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(37, 2075, 10, '4;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(38, 2076, 10, '4;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(39, 2076, 10, '4;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(40, 2077, 10, '4;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(41, 2058, 10, '5;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(42, 2066, 10, '5;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(43, 2067, 10, '5;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(44, 2058, 10, '5;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(45, 2066, 10, '5;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(46, 2061, 10, '5;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(47, 2075, 10, '5;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(48, 2076, 10, '5;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(49, 2076, 10, '5;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(50, 2077, 10, '5;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(51, 2058, 10, '6;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(52, 2066, 10, '6;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(53, 2067, 10, '6;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(54, 2058, 10, '6;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(55, 2066, 10, '6;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(56, 2061, 10, '6;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(57, 2075, 10, '6;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(58, 2076, 10, '6;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(59, 2076, 10, '6;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(60, 2077, 10, '6;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(61, 2058, 10, '9;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(62, 2066, 10, '9;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(63, 2067, 10, '9;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(64, 2058, 10, '9;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(65, 2066, 10, '9;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(66, 2061, 10, '9;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(67, 2075, 10, '9;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(68, 2076, 10, '9;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(69, 2076, 10, '9;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(70, 2077, 10, '9;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(71, 2058, 10, '10;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(72, 2066, 10, '10;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(73, 2067, 10, '10;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(74, 2058, 10, '10;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(75, 2066, 10, '10;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(76, 2061, 10, '10;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(77, 2075, 10, '10;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(78, 2076, 10, '10;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(79, 2076, 10, '10;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(80, 2077, 10, '10;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(81, 2058, 10, '11;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(82, 2066, 10, '11;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(83, 2067, 10, '11;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(84, 2058, 10, '11;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(85, 2066, 10, '11;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(86, 2061, 10, '11;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(87, 2075, 10, '11;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(88, 2076, 10, '11;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(89, 2076, 10, '11;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(90, 2077, 10, '11;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(91, 2058, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(92, 2066, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(93, 2067, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(94, 2058, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(95, 2066, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(96, 2061, 10, '12;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(97, 2075, 10, '12;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(98, 2076, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(99, 2076, 10, '12;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(100, 2077, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(101, 2058, 10, '13;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(102, 2066, 10, '13;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(103, 2067, 10, '13;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(104, 2058, 10, '13;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(105, 2066, 10, '13;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(106, 2061, 10, '13;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(107, 2075, 10, '13;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(108, 2076, 10, '13;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(109, 2076, 10, '13;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(110, 2077, 10, '13;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(111, 2058, 10, '14;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(112, 2066, 10, '14;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(113, 2067, 10, '14;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(114, 2058, 10, '14;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(115, 2066, 10, '14;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(116, 2061, 10, '14;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(117, 2075, 10, '14;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(118, 2076, 10, '14;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(119, 2076, 10, '14;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(120, 2077, 10, '14;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(121, 2058, 10, '15;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(122, 2066, 10, '15;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(123, 2067, 10, '15;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(124, 2058, 10, '15;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(125, 2066, 10, '15;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(126, 2061, 10, '15;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(127, 2075, 10, '15;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(128, 2076, 10, '15;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(129, 2076, 10, '15;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(130, 2077, 10, '15;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(131, 2058, 10, '17;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(132, 2066, 10, '17;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(133, 2067, 10, '17;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(134, 2058, 10, '17;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(135, 2066, 10, '17;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(136, 2061, 10, '17;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(137, 2075, 10, '17;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(138, 2076, 10, '17;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(139, 2076, 10, '17;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(140, 2077, 10, '17;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(141, 2058, 10, '19;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(142, 2066, 10, '19;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(143, 2067, 10, '19;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(144, 2058, 10, '19;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(145, 2066, 10, '19;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(146, 2061, 10, '19;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(147, 2075, 10, '19;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(148, 2076, 10, '19;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(149, 2076, 10, '19;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(150, 2077, 10, '19;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(151, 2058, 10, '20;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(152, 2066, 10, '20;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(153, 2067, 10, '20;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(154, 2058, 10, '20;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(155, 2066, 10, '20;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(156, 2061, 10, '20;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(157, 2075, 10, '20;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(158, 2076, 10, '20;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(159, 2076, 10, '20;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(160, 2077, 10, '20;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES
(1, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(1, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(1, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(1, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(1, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(1, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(1, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(1, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(2, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(2, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(2, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(2, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(2, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(2, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(2, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(2, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(3, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(3, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(3, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(3, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(3, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(3, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(3, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(3, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(4, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(4, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(4, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(4, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(4, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(4, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(4, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(4, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(5, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(5, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(5, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(5, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(5, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(5, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(5, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(5, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(6, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(6, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(6, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(6, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(6, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(6, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(6, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(6, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(7, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(7, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(7, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(7, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(7, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(7, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(7, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(7, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(8, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(8, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(8, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(8, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(8, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(8, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(8, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(8, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(9, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(9, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(9, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(9, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(9, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(9, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(9, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(9, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(10, 1, '2011-12-12 08:00:00', '2011-12-12 12:00:00', 1),
(10, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(10, 3, '2011-12-13 08:00:00', '2011-12-13 12:00:00', 1),
(10, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(10, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(10, 6, '2011-12-14 08:00:00', '2011-12-14 12:00:00', 1),
(10, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(10, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1);



-- POLO 2
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(11, 1, '2011-12-12 14:00:00', '2011-12-12 17:00:00', 1),
(11, 2, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(11, 3, '2011-12-13 14:00:00', '2011-12-13 17:00:00', 1),
(11, 4, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(11, 5, '2011-12-14 14:00:00', '2011-12-14 17:00:00', 1),
(11, 6, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(11, 7, '2011-12-15 14:00:00', '2011-12-15 17:00:00', 1),
(11, 8, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(11, 9, '2011-12-16 14:00:00', '2011-12-16 17:00:00', 1),
(11, 10, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1),
(11, 11, '2011-12-17 09:00:00', '2011-12-17 12:00:00', 1),
(11, 12, '2011-12-17 14:00:00', '2011-12-16 17:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 12, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 13, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 14, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 15, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 16, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 17, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 18, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 19, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 20, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 11;

-- POLO 3
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(21, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(21, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(21, 3, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(21, 4, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(21, 5, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(21, 6, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(21, 7, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(21, 8, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(21, 9, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(21, 10, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(21, 11, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(21, 12, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(21, 13, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(21, 14, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(21, 15, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 22, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 23, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 24, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 25, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 26, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 27, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 28, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 29, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 30, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 21;


-- POLO 4
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(31, 1, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(31, 2, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(31, 3, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(31, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(31, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(31, 6, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(31, 7, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(31, 8, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(31, 9, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(31, 10, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(31, 11, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(31, 12, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1),
(31, 13, '2011-12-17 09:00:00', '2011-12-17 12:00:00', 1),
(31, 14, '2011-12-17 13:00:00', '2011-12-17 18:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 32, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 33, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 34, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 35, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 36, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 37, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 38, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 39, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 40, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 31;



-- POLO 5
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(41, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(41, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(41, 3, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(41, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(41, 5, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(41, 6, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(41, 7, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(41, 8, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(41, 9, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(41, 10, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1);


INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 42, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 43, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 44, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 45, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 46, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 47, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 48, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 49, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 50, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 41;




-- POLO 6
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(51, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(51, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(51, 3, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(51, 4, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(51, 5, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(51, 6, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(51, 7, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(51, 8, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(51, 9, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(51, 10, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(51, 11, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(51, 12, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(51, 13, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(51, 14, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(51, 15, '2011-12-17 12:00:00', '2011-12-17 12:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 52, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 53, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 54, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 55, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 56, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 57, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 58, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 59, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 60, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 51;




-- POLO 9
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(61, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(61, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(61, 3, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(61, 4, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(61, 5, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(61, 6, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(61, 7, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(61, 8, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(61, 9, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(61, 10, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(61, 11, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(61, 12, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),


INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 62, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 63, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 64, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 65, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 66, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 67, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 68, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 69, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 70, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 61;


-- POLO 10
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(71, 1, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(71, 2, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(71, 3, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(71, 4, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(71, 5, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(71, 6, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(71, 7, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(71, 8, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(71, 9, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(71, 10, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 72, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 73, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 74, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 75, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 76, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 77, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 78, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 79, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 80, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 71;

-- POLO 11
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(81, 1, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(81, 2, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(81, 3, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(81, 4, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(81, 5, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(81, 6, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(81, 7, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(81, 8, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(81, 9, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(81, 10, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1),
(81, 11, '2011-12-17 09:00:00', '2011-12-17 12:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 82, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 83, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 84, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 85, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 86, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 87, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 88, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 89, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 90, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 81;



-- POLO 13
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(91, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(91, 2, '2011-12-12 14:00:00', '2011-12-12 18:00:00', 1),
(91, 3, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(91, 4, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(91, 5, '2011-12-13 14:00:00', '2011-12-13 18:00:00', 1),
(91, 6, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(91, 7, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(91, 8, '2011-12-14 14:00:00', '2011-12-14 18:00:00', 1),
(91, 9, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(91, 10, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(91, 11, '2011-12-15 14:00:00', '2011-12-15 18:00:00', 1),
(91, 12, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(91, 13, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(91, 14, '2011-12-16 14:00:00', '2011-12-16 18:00:00', 1),
(91, 15, '2011-12-17 08:00:00', '2011-12-17 12:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 92, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 93, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 94, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 95, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 96, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 97, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 98, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 99, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 100, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 91;





-- POLO 14
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(101, 1, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(101, 2, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(101, 3, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(101, 4, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 102, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 103, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 104, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 105, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 106, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 107, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 108, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 109, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 110, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 101;



-- POLO 15
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(111, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(111, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(111, 3, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(111, 4, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(111, 5, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(111, 6, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(111, 7, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(111, 8, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(111, 9, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(111, 10, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(111, 11, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(111, 12, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(111, 13, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(111, 14, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(111, 15, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 112, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 113, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 114, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 115, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 116, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 117, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 118, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 119, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 120, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 111;



-- POLO 17
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(121, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(121, 2, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(121, 3, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(121, 4, '2011-12-17 13:00:00', '2011-12-17 18:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 122, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 123, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 124, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 125, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 126, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 127, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 128, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 129, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 130, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 121;


-- POLO 19
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(131, 1, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(131, 2, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(131, 3, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 132, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 133, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 134, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 135, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 136, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 137, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 138, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 139, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 140, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 131;


-- POLO 20
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(141, 1, '2011-12-12 09:00:00', '2011-12-12 12:00:00', 1),
(141, 2, '2011-12-12 13:00:00', '2011-12-12 18:00:00', 1),
(141, 3, '2011-12-12 19:00:00', '2011-12-12 22:00:00', 1),
(141, 4, '2011-12-13 09:00:00', '2011-12-13 12:00:00', 1),
(141, 5, '2011-12-13 13:00:00', '2011-12-13 18:00:00', 1),
(141, 6, '2011-12-13 19:00:00', '2011-12-13 22:00:00', 1),
(141, 7, '2011-12-14 09:00:00', '2011-12-14 12:00:00', 1),
(141, 8, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(141, 9, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(141, 10, '2011-12-15 09:00:00', '2011-12-15 12:00:00', 1),
(141, 11, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(141, 12, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(141, 13, '2011-12-16 09:00:00', '2011-12-16 12:00:00', 1),
(141, 14, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(141, 15, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1),
(141, 16, '2011-12-17 09:00:00', '2011-12-17 12:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 142, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 143, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 144, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 145, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 146, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 147, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 148, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 149, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 150, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 141;




-- 2011-12-07
UPDATE  `SysClass_root`.`user_types` SET  `extended_user_type` =  'polo' WHERE  `user_types`.`id` =11;
/*
CREATE TABLE IF NOT EXISTS `module_xcontent_authorize` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `xentify_scope_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL default 1,

  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/


INSERT INTO `module_xcontent_schedule` (`id`, `content_id`, `xentify_scope_id`, `xentify_id`, `start`, `end`, `block_html`, `active`) VALUES
(161, 2058, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(162, 2066, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(163, 2067, 10, '12;15', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(164, 2058, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(165, 2066, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(166, 2061, 10, '12;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(167, 2075, 10, '12;14', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(168, 2076, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(169, 2076, 10, '12;37', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1),
(170, 2077, 10, '12;31', '2011-12-05 00:00:00', '2011-12-17 23:59:59', '', 1);





INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) VALUES 
(161, 1, '2011-12-14 13:00:00', '2011-12-14 18:00:00', 1),
(161, 2, '2011-12-14 19:00:00', '2011-12-14 22:00:00', 1),
(161, 3, '2011-12-15 13:00:00', '2011-12-15 18:00:00', 1),
(161, 4, '2011-12-15 19:00:00', '2011-12-15 22:00:00', 1),
(161, 5, '2011-12-16 13:00:00', '2011-12-16 18:00:00', 1),
(161, 6, '2011-12-16 19:00:00', '2011-12-16 22:00:00', 1),
(161, 7, '2011-12-17 09:00:00', '2011-12-17 12:00:00', 1);

INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 162, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 163, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 164, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 165, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 166, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 167, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 168, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 169, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;
INSERT INTO `module_xcontent_schedule_itens` (`schedule_id`, `index`, `start`, `end`, `active`) 
SELECT 170, `index`, `start`, `end`, `active` FROM `module_xcontent_schedule_itens` WHERE schedule_id = 161;


CREATE TABLE IF NOT EXISTS `module_xpay_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` mediumint(8) NOT NULL,
  `parcela_index` int(11) NOT NULL DEFAULT '1',
  `payment_type_id` int(11) NOT NULL,
  `invoice_id` text NOT NULL,
  `invoices_sha_access` text,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` timestamp NULL DEFAULT NULL,
  `valor_desconto` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status_id` tinyint(1) NOT NULL DEFAULT '1',
  `bloqueio` tinyint(1) DEFAULT '0',
  `pago` tinyint(1) DEFAULT '0',
  `tag` text NOT NULL,
PRIMARY KEY (`id`),

  UNIQUE KEY (`payment_id`,`parcela_index`),
  FULLTEXT KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- 2012-01-24
DROP TABLE IF EXISTS `module_xpay_cielo_transactions`;
CREATE TABLE IF NOT EXISTS `module_xpay_cielo_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
`payment_id` mediumint(8) NOT NULL,
  `tid` varchar(100) NOT NULL,
	pedido_id varchar(255) NOT NULL,
	valor decimal(15,4) NOT NULL DEFAULT '0.0000',
	data timestamp NULL DEFAULT NULL,
	descricao varchar(255) NOT NULL,
	bandeira varchar(30) NOT NULL,
	produto varchar(20) NOT NULL,
	parcelas varchar(30) NOT NULL,
PRIMARY KEY (`id`),
  FULLTEXT KEY `tid_key` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 2012-01-24
DROP TABLE IF EXISTS `module_xpay_cielo_transactions_to_invoices`;
CREATE TABLE IF NOT EXISTS `module_xpay_cielo_transactions_to_invoices` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
`payment_id` mediumint(8) NOT NULL,
`parcela_index` mediumint(8) NOT NULL,
PRIMARY KEY (`transaction_id`, `payment_id`, `parcela_index`),
  FULLTEXT KEY `tid_key` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--2012-02-22
UPDATE `SysClass_root`.`module_xcms_blocks` SET `name` = 'SysClassAds', `module` = 'xcms', `action` = 'load_ads' WHERE `module_xcms_blocks`.`id` = 13;


CREATE TABLE `SysClass_root`.`module_xcontent_schedule_contents` (
`schedule_id` INT( 11 ) NOT NULL ,
`content_id` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `schedule_id` , `content_id` )
) ENGINE = MYISAM ;

INSERT INTO module_xcontent_schedule_contents (schedule_id, content_id)
SELECT id, content_id FROM `module_xcontent_schedule`;

ALTER TABLE `module_xcontent_schedule` DROP `content_id`;

ALTER TABLE `module_xcontent_schedule_contents` ADD `course_id` INT( 11 ) NOT NULL AFTER `schedule_id` ;
ALTER TABLE `module_xcontent_schedule_contents` DROP PRIMARY KEY ;

ALTER TABLE `module_xcontent_schedule_contents` ADD PRIMARY KEY ( `schedule_id` , `course_id` , `content_id` ) ;

UPDATE `module_xcontent_schedule_contents` 
SET course_id = (SELECT courses_ID FROM lessons_to_courses WHERE lessons_ID = (SELECT lessons_ID FROM content WHERE id = `module_xcontent_schedule_contents`.content_id));

-- 2012-03-06
UPDATE module_xcontent_schedule SET active = 0 WHERE id <= 170;




INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(172, 2061), (172, 2611), (172, 2075), (172, 2614), (172, 2153), (172, 2167), (172, 2076), (172, 2626), (172, 2077), (172, 2612), (172, 2395), (172, 2058), (172, 2619), (172, 2066), (172, 2620), (172, 2067), (172, 2621), (172, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(173, 2061), (173, 2611), (173, 2075), (173, 2614), (173, 2153), (173, 2167), (173, 2076), (173, 2626), (173, 2077), (173, 2612), (173, 2395), (173, 2058), (173, 2619), (173, 2066), (173, 2620), (173, 2067), (173, 2621), (173, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(174, 2061), (174, 2611), (174, 2075), (174, 2614), (174, 2153), (174, 2167), (174, 2076), (174, 2626), (174, 2077), (174, 2612), (174, 2395), (174, 2058), (174, 2619), (174, 2066), (174, 2620), (174, 2067), (174, 2621), (174, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(175, 2061), (175, 2611), (175, 2075), (175, 2614), (175, 2153), (175, 2167), (175, 2076), (175, 2626), (175, 2077), (175, 2612), (175, 2395), (175, 2058), (175, 2619), (175, 2066), (175, 2620), (175, 2067), (175, 2621), (175, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(176, 2061), (176, 2611), (176, 2075), (176, 2614), (176, 2153), (176, 2167), (176, 2076), (176, 2626), (176, 2077), (176, 2612), (176, 2395), (176, 2058), (176, 2619), (176, 2066), (176, 2620), (176, 2067), (176, 2621), (176, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(177, 2061), (177, 2611), (177, 2075), (177, 2614), (177, 2153), (177, 2167), (177, 2076), (177, 2626), (177, 2077), (177, 2612), (177, 2395), (177, 2058), (177, 2619), (177, 2066), (177, 2620), (177, 2067), (177, 2621), (177, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(178, 2061), (178, 2611), (178, 2075), (178, 2614), (178, 2153), (178, 2167), (178, 2076), (178, 2626), (178, 2077), (178, 2612), (178, 2395), (178, 2058), (178, 2619), (178, 2066), (178, 2620), (178, 2067), (178, 2621), (178, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(179, 2061), (179, 2611), (179, 2075), (179, 2614), (179, 2153), (179, 2167), (179, 2076), (179, 2626), (179, 2077), (179, 2612), (179, 2395), (179, 2058), (179, 2619), (179, 2066), (179, 2620), (179, 2067), (179, 2621), (179, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(180, 2061), (180, 2611), (180, 2075), (180, 2614), (180, 2153), (180, 2167), (180, 2076), (180, 2626), (180, 2077), (180, 2612), (180, 2395), (180, 2058), (180, 2619), (180, 2066), (180, 2620), (180, 2067), (180, 2621), (180, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(181, 2061), (181, 2611), (181, 2075), (181, 2614), (181, 2153), (181, 2167), (181, 2076), (181, 2626), (181, 2077), (181, 2612), (181, 2395), (181, 2058), (181, 2619), (181, 2066), (181, 2620), (181, 2067), (181, 2621), (181, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(182, 2061), (182, 2611), (182, 2075), (182, 2614), (182, 2153), (182, 2167), (182, 2076), (182, 2626), (182, 2077), (182, 2612), (182, 2395), (182, 2058), (182, 2619), (182, 2066), (182, 2620), (182, 2067), (182, 2621), (182, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(183, 2061), (183, 2611), (183, 2075), (183, 2614), (183, 2153), (183, 2167), (183, 2076), (183, 2626), (183, 2077), (183, 2612), (183, 2395), (183, 2058), (183, 2619), (183, 2066), (183, 2620), (183, 2067), (183, 2621), (183, 2068);
INSERT INTO module_xcontent_schedule_contents(schedule_id, content_id) VALUES
(184, 2061), (184, 2611), (184, 2075), (184, 2614), (184, 2153), (184, 2167), (184, 2076), (184, 2626), (184, 2077), (184, 2612), (184, 2395), (184, 2058), (184, 2619), (184, 2066), (184, 2620), (184, 2067), (184, 2621), (184, 2068);

ALTER TABLE `lessons` ADD `link` VARCHAR( 500 ) NULL DEFAULT NULL AFTER `originating_course`;
ALTER TABLE `lessons` CHANGE `link` `firstlink` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


INSERT INTO module_xcontent_schedule_contents(schedule_id, course_id, content_id) 
VALUES (172, 31, 2628), (173, 31, 2628), (174, 31, 2628), (175, 31, 2628), (176, 31, 2628), (177, 31, 2628), (178, 31, 2628), (179, 31, 2628), (180, 31, 2628), (181, 31, 2628), (182, 31, 2628), (183, 31, 2628), (184, 31, 2628);

--2012-03-21
ALTER TABLE `module_xuser` ADD `registro` VARCHAR( 50 ) NOT NULL AFTER `id` , ADD FULLTEXT (`registro`);


/* --2012-04-02 */
ALTER TABLE `users_to_courses` ADD `modality_id` MEDIUMINT( 8 ) NOT NULL AFTER `user_type`;

CREATE TABLE IF NOT EXISTS `module_xpay_course_modality_prices` (
  `modality_id` mediumint(8) NOT NULL,
`course_id` mediumint(8) NOT NULL,
`from_timestamp` int(10) NOT NULL,
`to_timestamp` int(10) NOT NULL,
`price` mediumint(8) NOT NULL,
PRIMARY KEY (`modality_id`, `course_id`, `from_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `module_xpay_course_negociation`;
CREATE TABLE IF NOT EXISTS `module_xpay_course_negociation` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` mediumint(8) NOT NULL,
	`course_id` mediumint(8) NOT NULL,
	`negociation_index` smallint(4) NOT NULL, 
	PRIMARY KEY (`id`),
	UNIQUE(`negociation_index`, `user_id`, `course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_xpay_course_modality` (
  `id` mediumint(8) NOT NULL,
  `name` mediumint(8) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `module_xpay_invoices` (
	`negociation_id` mediumint(8) NOT NULL,
	`invoice_index` mediumint(8) NOT NULL,
	`invoice_id` text NULL,
	`invoice_sha_access` text,
	`valor` smallint(4) NOT NULL, 
	`data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`data_vencimento` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`negociation_id`, `invoice_index`),
	FULLTEXT KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_xpay_invoices_to_paid` (
	`negociation_id` mediumint(8) NOT NULL,
	`invoice_index` mediumint(8) NOT NULL,
	`paid_id` mediumint(8) NOT NULL,
	PRIMARY KEY (`negociation_id`, `invoice_index`, `paid_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_xpay_paid_items` (
	`id` int(11) NOT NULL,
	`transaction_id` int(11) NOT NULL,
	`method_id` varchar(100) NOT NULL,
	`paid` float NOT NULL DEFAULT 0,
	`start_timestamp` int(10) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/* 2012-04-10 */
CREATE TABLE IF NOT EXISTS `module_xentify` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `module_xpay_price_rules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
-- SCOPE NO QUAL A REGRA É APLICADA
  `rule_xentify_scope_id` mediumint(8) unsigned NOT NULL,
-- VALORES DE SCOPE NO QUAL A REGRA É APLICADA, PODENDO SER ID DE TURMAS, DE CURSOS, POLOS, ETC...
  `rule_xentify_id` text NOT NULL,
-- ENTIDADE AO QUAL A REGRA SE REFERE, O PARA GLOBAL, AS REGRAS SÃO APLICADAS EM CASCATE, ORDENADA POR ESTE ID
  `entify_id` mediumint(8) unsigned NOT NULL,
-- ID DA ENTIDADE AO QUAL A REGRA SE REFERE, COMO O CÓDIGO DO CURSO, O CÓDIGO DO POLO, ETC...
  `entify_absolute_id` mediumint(8) unsigned NOT NULL,
  `order` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `module_xentify` (`id` ,`name`) VALUES ('0', 'Global');
UPDATE `module_xentify` SET `id` = 0;
INSERT INTO `module_xentify` (`id` ,`name`) VALUES ('1', 'IES');
INSERT INTO `module_xentify` (`id` ,`name`) VALUES ('2', 'Polo');
INSERT INTO `module_xentify` (`id` ,`name`) VALUES ('3', 'Curso');
INSERT INTO `module_xentify` (`id` ,`name`) VALUES ('4', 'Turma');



INSERT INTO `module_xentify_scopes` (`id` ,`name` ,`description` ,`rules` ,`active`) VALUES 
('0', 'Todos os usuários', '%s será compartilhado entre todos os alunos', '{}', '1');
UPDATE `module_xentify_scopes` SET `id` = '0' WHERE `module_xentify_scopes`.`id` =11;

INSERT INTO `module_xentify_scopes` (`id` ,`name` ,`description` ,`rules` ,`active`) VALUES 
('11', 'Todos os usuários Adimplentes', '%s será compartilhado entre todos os alunos adimplentes', '{}', '1');

INSERT INTO `module_xentify_scopes` (`id` ,`name` ,`description` ,`rules` ,`active`) VALUES 
('12', 'Todos os usuários Inadimplentes', '%s será compartilhado entre todos os alunos inadimplentes', '{}', '1');

INSERT INTO `module_xpay_price_rules` (`id`, `rule_xentify_scope_id`, `rule_xentify_id`, `entify_id`, `entify_absolute_id`, `order`, `active`) VALUES (1, 0, '0', 1, 1, 1, 1);


ALTER TABLE `module_xpay_price_rules` ADD `type_id` TINYINT( 1 ) NOT NULL DEFAULT '-1' COMMENT '-1 = desconto, 1 = acrescimo' AFTER `entify_absolute_id` ,
ADD `percentual` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT 'percentual ou absoluto' AFTER `type_id` ,
ADD `valor` FLOAT NOT NULL DEFAULT '0' COMMENT 'valor absoluto ou percentual (5% = 0.05)' AFTER `percentual`,
ADD `base_price_applied` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT '1 para aplicado no preço base, 0 para aplicar no valor do fluxo.' AFTER `valor`;
/*
CREATE TABLE IF NOT EXISTS `module_xpay_invoices_templates` (
	-- ENTIDADE AO QUAL A REGRA SE REFERE, O PARA GLOBAL, AS REGRAS SÃO APLICADAS EM CASCATE, ORDENADA POR ESTE ID
	`entify_id` mediumint(8) unsigned NOT NULL,
	-- ID DA ENTIDADE AO QUAL A REGRA SE REFERE, COMO O CÓDIGO DO CURSO, O CÓDIGO DO POLO, ETC...
	`entify_absolute_id` mediumint(8) unsigned NOT NULL,
	`invoice_index` mediumint(8) NOT NULL,
	`invoice_id` text NULL,
	`invoice_sha_access` text,
	`valor` smallint(4) NOT NULL, 
	`data_vencimento` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`negociation_id`, `invoice_index`),
	FULLTEXT KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `module_xpay_invoices_templates`;
*/
/*
CREATE TABLE IF NOT EXISTS `module_xpay_negociation_params` (
	`negociation_id` mediumint(8) NOT NULL,
	`parcelas` mediumint(8) NOT NULL,
	`registration_tax` float NOT NULL DEFAULT 0,
	`valor` smallint(4) NOT NULL, 
	`vencimento_1_parcela` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`negociation_id`, `invoice_index`),
	FULLTEXT KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/

-- 2012-04-19
/*
ALTER TABLE `f_forums` ADD `classe_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `lessons_ID`;
ALTER TABLE `news` ADD `classe_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `lessons_ID`;
*/
ALTER TABLE `module_xpay_course_negociation` ADD `active` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `module_xpay_course_negociation` ADD `timestamp` int(10) NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `module_xpay_course_negociation` ADD `registration_tax` float NOT NULL DEFAULT 0 AFTER `course_id`;
ALTER TABLE `module_xpay_course_negociation` ADD `parcelas` mediumint(8) NOT NULL AFTER `registration_tax`;
ALTER TABLE `module_xpay_course_negociation` ADD `vencimento_1_parcela` timestamp NULL DEFAULT NULL;

/* 2012-04-23 */
ALTER TABLE `module_xpay_price_rules` ADD `description` VARCHAR( 150 ) NOT NULL AFTER `id`;

ALTER TABLE `module_xpay_invoices` ADD `description` VARCHAR( 150 ) NOT NULL AFTER `invoice_index`;
ALTER TABLE `module_xpay_invoices` ADD `locked` TINYINT( 1 ) NOT NULL DEFAULT '0';

/* 2012-04-24 */
ALTER TABLE `module_pagamento` ADD `migrated` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `module_xpay_course_negociation` ADD `ref_payment_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0';


CREATE TABLE IF NOT EXISTS `module_xpay_boleto_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` mediumint(8) NOT NULL,	
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nosso_numero` text NOT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `ocorrencia_id` varchar(3) DEFAULT NULL,
  `liquidacao_id` varchar(3) DEFAULT NULL,
  `valor_titulo` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_abatimento` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_desconto` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_juros_multa` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_outros_creditos` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tag` text NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `module_xpay_paid_items` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `module_xpay_boleto_transactions` CHANGE `data_registro` `data_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;


CREATE TABLE IF NOT EXISTS `module_xpay_manual_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` mediumint(8) NOT NULL,	
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/* 2012-04-26 */
CREATE TABLE IF NOT EXISTS `module_xpay_price_rules_tags` (
  `rule_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` enum('is_full_paid','is_not_full_paid','is_overdue','is_not_overdue') NOT NULL,
  PRIMARY KEY (`rule_id`, `tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('1', 'is_not_full_paid');
INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('1', 'is_not_overdue');

INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('2', 'is_not_full_paid');
INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('2', 'is_overdue');

INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('3', 'is_not_full_paid');
INSERT INTO `module_xpay_price_rules_tags` (`rule_id` ,`tag`) VALUES ('3', 'is_overdue');

ALTER TABLE `module_xpay_price_rules` ADD `applied_on` ENUM( 'once', 'per_day', 'per_month' ) NOT NULL AFTER `base_price_applied`;

/* 2012-04-27 */
UPDATE `module_xpay_price_rules` SET `valor` = '0.05' WHERE `module_xpay_price_rules`.`id` =1;
INSERT INTO `SysClass_root`.`module_xpay_price_rules` (`id`, `description`, `rule_xentify_scope_id`, `rule_xentify_id`, `entify_id`, `entify_absolute_id`, `type_id`, `percentual`, `valor`, `base_price_applied`, `applied_on`, `order`, `active`) VALUES (NULL, '', '0', '0', '1', '1', '1', '1', '0.02', '1', 'once', '1', '1');
UPDATE `module_xpay_price_rules` SET `description` = 'Desconto de 5% para pagamento pontual' WHERE `module_xpay_price_rules`.`id` =1;
UPDATE `module_xpay_price_rules` SET `description` = 'Multa de 2% por atraso' WHERE `module_xpay_price_rules`.`id` =2;

ALTER TABLE `module_xpay_price_rules_tags` CHANGE `tag` `tag` ENUM( 'is_full_paid', 'is_not_full_paid', 'is_overdue', 'is_not_overdue', 'is_registration_tax', 'is_not_registration_tax' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

INSERT INTO `module_xpay_price_rules_tags` (`rule_id`, `tag`) VALUES ('1', 'is_not_registration_tax');


CREATE TABLE IF NOT EXISTS `module_xpay_to_send_list` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_xpay_to_send_list_item` (
  `send_id` bigint(20) NOT NULL,
  `negociation_id` mediumint(8) NOT NULL,
  `invoice_index` varchar(20) NOT NULL,
  PRIMARY KEY (`send_id`,`negociation_id`,`invoice_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* 2012-05-02 */
CREATE TABLE IF NOT EXISTS `module_xpay_sent_invoices_log` (
  `id` bigint(20) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `negociation_id` mediumint(8) NOT NULL,
  `invoice_index` int(11) NOT NULL,
  `vencimento` timestamp NULL DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE  `module_xpay_sent_invoices_log` CHANGE  `id`  `id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT;

/* 2012-05-03 */
ALTER TABLE `module_xpay_course_negociation` ADD `send_to` ENUM( 'student', 'parent', 'financial' ) NULL DEFAULT NULL AFTER  `negociation_index`;

ALTER TABLE `module_xuser_responsible` CHANGE `type` `type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'parent';

/* 2012-05-06 */
ALTER TABLE `module_xpay_price_rules_tags` CHANGE `tag` `tag` ENUM( 'is_full_paid', 'is_not_full_paid', 'is_overdue', 'is_not_overdue', 'is_registration_tax', 'is_not_registration_tax', 'is_custom', 'is_not_custom' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

CREATE TABLE IF NOT EXISTS `module_xuser_user_tags` (
  `user_id` mediumint(20) NOT NULL,
  `tag` enum('is_full_paid','is_not_full_paid','is_overdue','is_not_overdue','is_registration_tax','is_not_registration_tax','is_custom','is_not_custom') NOT NULL,
  PRIMARY KEY (`user_id`,`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `SysClass_root`.`module_xuser_user_tags` (`user_id`,`tag`) VALUES ('0', 'is_not_custom');


/* 2012-05-09 */
ALTER TABLE `module_xpay_boleto_transactions` ADD `valor_pago` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `valor_outros_creditos`;
ALTER TABLE `module_xpay_boleto_transactions` ADD `valor_tarifas` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `valor_outros_creditos`;


/* 2012-05-22 */
CREATE ALGORITHM=UNDEFINED VIEW `module_xpay_zzz_paid_items` AS select `cneg`.`id` AS `negociation_id`,`cneg`.`user_id` AS `user_id`,`cneg`.`course_id` AS `course_id`,`paid`.`id` AS `paid_id`,`paid`.`method_id` AS `method_id`,`c`.`name` AS `course_name`,`cl`.`name` AS `classe_name`,`bolt`.`nosso_numero` AS `nosso_numero`,`u`.`name` AS `name`,`u`.`surname` AS `surname`,`u`.`login` AS `login`,`inv`.`invoice_index` AS `invoice_index`,(select count(`module_xpay_invoices`.`negociation_id`) from `module_xpay_invoices` where (`module_xpay_invoices`.`negociation_id` = `cneg`.`id`)) AS `total_parcelas`,`inv`.`data_vencimento` AS `data_vencimento`,from_unixtime(`paid`.`start_timestamp`) AS `data_pagamento`,`inv`.`valor` AS `valor`,(`inv`.`valor` - `paid`.`paid`) AS `desconto`,`paid`.`paid` AS `paid` from (((((((((`module_xpay_paid_items` `paid` join `module_xpay_invoices_to_paid` `inv_paid` on((`inv_paid`.`paid_id` = `paid`.`id`))) join `module_xpay_invoices` `inv` on(((`inv_paid`.`negociation_id` = `inv`.`negociation_id`) and (`inv_paid`.`invoice_index` = `inv`.`invoice_index`)))) join `module_xpay_course_negociation` `cneg` on((`inv`.`negociation_id` = `cneg`.`id`))) left join `module_xpay_boleto_transactions` `bolt` on(((`paid`.`transaction_id` = `bolt`.`id`) and (`paid`.`method_id` = 'boleto')))) join `users` `u` on((`u`.`id` = `cneg`.`user_id`))) left join `module_xpay_manual_transactions` `manu` on(((`paid`.`transaction_id` = `bolt`.`id`) and (`paid`.`method_id` = 'manual')))) join `courses` `c` on((`c`.`id` = `cneg`.`course_id`))) join `users_to_courses` `uc` on(((`uc`.`users_LOGIN` = `u`.`login`) and (`uc`.`courses_ID` = `cneg`.`course_id`)))) left join `classes` `cl` on(((`uc`.`classe_id` = `cl`.`id`) and (`uc`.`courses_ID` = `cl`.`courses_ID`)))) order by `paid`.`id` desc;

/* 2012-05-06 */
CREATE TABLE `sysclass_root`.`user_last_access` (
`id` INT NOT NULL AUTO_INCREMENT ,
`lesson_ID` INT NOT NULL ,
`course_ID` INT NOT NULL ,
`user_ID` INT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;


/* 2012-06-14 */
ALTER TABLE `module_xpay_invoices_to_paid` ADD `full_value` FLOAT NULL DEFAULT NULL;

delimiter //

CREATE PROCEDURE teste()
BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE negociation_id, invoice_index, paid_id INT;
  DECLARE paid, valor FLOAT;
  DECLARE cur1 CURSOR FOR SELECT t1.negociation_id, t1.invoice_index, t1.paid_id, t1.paid, t1.valor FROM module_xpay_zzz_paid_items t1 WHERE t1.paid < t1.valor;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur1;

  read_loop: LOOP
    FETCH cur1 INTO negociation_id, invoice_index, paid_id, paid, valor;
    IF done THEN
      LEAVE read_loop;
    END IF;


    UPDATE module_xpay_invoices_to_paid t1 SET t1.full_value = valor WHERE t1.negociation_id = negociation_id AND t1.invoice_index = invoice_index AND t1.paid_id = paid_id;
  END LOOP;

  CLOSE cur1;
END//
delimiter ;
CALL teste;
DROP PROCEDURE teste;

/* 2012-06-29 */
ALTER TABLE `users_to_lessons` ADD `modality_id` MEDIUMINT( 8 ) NOT NULL AFTER `user_type`;

/* 2012-07-06 */
ALTER TABLE `module_xpay_course_negociation` CHANGE `is_simulation` `is_simulation` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = For imcomplete simulation (can be used again), 2=> for closed simulation (disonible for use)'

/* 2012-07-11 */
ALTER TABLE `module_xpay_course_negociation` ADD `lesson_id` INT NOT NULL DEFAULT '0' AFTER `course_id`;
ALTER TABLE `module_xpay_course_modality_prices` ADD `lesson_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `course_id`;

INSERT INTO `module_xpay_course_modality` (`id`, `name`) VALUES ('0', 'Indefinido');
ALTER TABLE `module_xpay_course_modality_prices` DROP PRIMARY KEY;
ALTER TABLE `module_xpay_course_modality_prices` ADD PRIMARY KEY ( `modality_id` , `course_id` , `lesson_id` , `from_timestamp` ) ;

/* 2012-07-25 */
-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 05:28 PM
-- Server version: 5.1.63-0ubuntu0.10.04.1
-- PHP Version: 5.3.2-1ubuntu4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sysclass_root`
--

-- --------------------------------------------------------

--
-- Structure for view `module_xpay_zzz_paid_items`
--

DROP VIEW module_xpay_zzz_paid_items;

CREATE ALGORITHM=UNDEFINED DEFINER=`sysclass`@`localhost` SQL SECURITY DEFINER VIEW `module_xpay_zzz_paid_items` AS select `cneg`.`id` AS `negociation_id`,`cneg`.`user_id` AS `user_id`,`cneg`.`course_id` AS `course_id`,`paid`.`id` AS `paid_id`,`paid`.`method_id` AS `method_id`,`c`.`name` AS `course_name`,`cl`.`name` AS `classe_name`,`bolt`.`nosso_numero` AS `nosso_numero`,`u`.`name` AS `name`,`u`.`surname` AS `surname`,`u`.`login` AS `login`,`inv`.`invoice_index` AS `invoice_index`,(select count(`module_xpay_invoices`.`negociation_id`) from `module_xpay_invoices` where (`module_xpay_invoices`.`negociation_id` = `cneg`.`id`)) AS `total_parcelas`,`inv`.`data_vencimento` AS `data_vencimento`,from_unixtime(`paid`.`start_timestamp`) AS `data_pagamento`,`inv`.`valor` AS `valor`,(`inv`.`valor` - `paid`.`paid`) AS `desconto`,`paid`.`paid` AS `paid` from (((((((((`module_xpay_paid_items` `paid` join `module_xpay_invoices_to_paid` `inv_paid` on((`inv_paid`.`paid_id` = `paid`.`id`))) join `module_xpay_invoices` `inv` on(((`inv_paid`.`negociation_id` = `inv`.`negociation_id`) and (`inv_paid`.`invoice_index` = `inv`.`invoice_index`)))) join `module_xpay_course_negociation` `cneg` on((`inv`.`negociation_id` = `cneg`.`id`))) left join `module_xpay_boleto_transactions` `bolt` on(((`paid`.`transaction_id` = `bolt`.`id`) and (`paid`.`method_id` = 'boleto')))) join `users` `u` on((`u`.`id` = `cneg`.`user_id`))) left join `module_xpay_manual_transactions` `manu` on(((`paid`.`transaction_id` = `bolt`.`id`) and (`paid`.`method_id` = 'manual')))) join `courses` `c` on((`c`.`id` = `cneg`.`course_id`))) join `users_to_courses` `uc` on(((`uc`.`users_LOGIN` = `u`.`login`) and (`uc`.`courses_ID` = `cneg`.`course_id`)))) left join `classes` `cl` on(((`uc`.`classe_id` = `cl`.`id`) and (`uc`.`courses_ID` = `cl`.`courses_ID`)))) order by `paid`.`id` desc;



/* 2012-07-26 */
INSERT INTO `sysclass_root`.`module_xentify_scopes` (`id`,`name`,`description`,`rules`,`active`) VALUES (
	NULL , 'Agrupado por grupo de usuários', '%s será compartilhado entre todos os alunos de um determinado grupo', NULL , '1'
);

/* @TODO: CADASTRAR GRUPO DE 10% DE DESCONTO, COM ID 4, OU MUDAR NESTE INSERT */
INSERT INTO `sysclass_root`.`module_xpay_price_rules` (`id`, `description`, `rule_xentify_scope_id`, `rule_xentify_id`, `entify_id`, `entify_absolute_id`, `type_id`, `percentual`, `valor`, `base_price_applied`, `applied_on`, `order`, `active`) VALUES (NULL, 'Desconto de 10% para pagamento pontual ', '13', '4', '1', '1', '-1', '1', '0.1', '1', 'once', '1', '1');

INSERT INTO `sysclass_root`.`module_xpay_price_rules_tags` (`rule_id`, `tag`) VALUES ('26', 'is_not_full_paid');
INSERT INTO `sysclass_root`.`module_xpay_price_rules_tags` (`rule_id`, `tag`) VALUES ('26', 'is_not_overdue');


INSERT INTO `sysclass_root`.`module_xpay_price_rules` (`id`, `description`, `rule_xentify_scope_id`, `rule_xentify_id`, `entify_id`, `entify_absolute_id`, `type_id`, `percentual`, `valor`, `base_price_applied`, `applied_on`, `order`, `active`) VALUES (NULL, 'Desconto de 30% para pagamento pontual ', '13', '5', '1', '1', '-1', '1', '0.3', '1', 'once', '1', '1');

INSERT INTO `sysclass_root`.`module_xpay_price_rules_tags` (`rule_id`, `tag`) VALUES ('27', 'is_not_full_paid');
INSERT INTO `sysclass_root`.`module_xpay_price_rules_tags` (`rule_id`, `tag`) VALUES ('27', 'is_not_overdue');



CREATE TABLE IF NOT EXISTS `module_xentify_scope_tags` (
	`xentify_scope_id` mediumint(8) UNSIGNED ,
	`xentify_id` varchar (100), 
	`tag` enum('is_full_paid','is_not_full_paid','is_overdue','is_not_overdue','is_registration_tax','is_not_registration_tax','is_custom','is_not_custom') NOT NULL,
  PRIMARY KEY (`xentify_scope_id`,`xentify_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* 2012-07-30 */
ALTER TABLE `module_xentify_scope_tags` DROP PRIMARY KEY;
ALTER TABLE `module_xentify_scope_tags` ADD PRIMARY KEY ( `xentify_scope_id` , `xentify_id` , `tag` ) ;

INSERT INTO `sysclass_root`.`module_xentify_scope_tags` (
`xentify_scope_id` ,
`xentify_id` ,
`tag`
)
VALUES (
'13', '4', 'is_custom'
);

INSERT INTO `sysclass_root`.`module_xentify_scope_tags` (
`xentify_scope_id` ,
`xentify_id` ,
`tag`
)
VALUES (
'13', '5', 'is_custom'
);

INSERT INTO `sysclass_root`.`module_xentify_scope_tags` (
`xentify_scope_id` ,
`xentify_id` ,
`tag`
)
VALUES (
'13', '6', 'is_custom'
);


/* 2012-08-08 */
ALTER TABLE `news` ADD `xscope_id` SMALLINT( 4 ) NOT NULL DEFAULT '0' AFTER `id` ;
ALTER TABLE `news` ADD `xentify_id` TEXT NULL AFTER `xscope_id`;
UPDATE `sysclass_root`.`module_xentify_scopes` SET `active` = '1' WHERE `module_xentify_scopes`.`id` =1;
ALTER TABLE `lessons` ADD `ies_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `id`;

/* 2012-08-10 */
// ESCOPO POR TIPO DE USUÁRIO
UPDATE `sysclass_root`.`module_xentify_scopes` SET `active` = '1' WHERE `module_xentify_scopes`.`id` =9;

INSERT INTO `module_xentify_scopes` (`id`, `name`, `description`, `rules`, `active`) VALUES
(NULL, 'Agrupado por IES/tipo de usuário', '%s poderá ser compartilhado entre usuários de mesmo tipo e mesma IES', '{}', 1);


INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Coordenador', '28x28/coordenador', '', '', NULL
);
INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Financeiro', '28x28/financeiro', '', '', NULL
);
INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Suporte Técnico', '28x28/suporte', '', '', NULL
);
INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Direção', '28x28/presidente', '', '', NULL
);
INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Secretaria', '28x28/secretaria', '', '', NULL
);
/*
INSERT INTO `sysclass_root`.`module_quick_mails_recipients` (`id` ,`title` ,`image` ,`xuser_type` ,`qm_type` ,`qm_group`) VALUES (
	NULL , 'Seu Polo', '28x28/house', '', '', NULL
);
*/

CREATE TABLE IF NOT EXISTS `module_quick_mails_scope` (
  `recipient_id` mediumint(8) NOT NULL,
  xscope_id mediumint(8) NOT NULL,
  xentify_id mediumint(8) NOT NULL,
  PRIMARY KEY (`recipient_id`, xscope_id, xentify_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `module_quick_mails_scope` CHANGE `xentify_id` `xentify_id` VARCHAR( 100 ) NOT NULL;

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(1, 15, '1;12'),
(1, 15, '1;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(2, 15, '1;12'),
(2, 15, '1;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(3, 15, '1;12'),
(3, 15, '1;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(4, 15, '1;12'),
(4, 15, '1;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(5, 15, '1;12'),
(5, 15, '1;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(11, 15, '2;16'),
(11, 15, '2;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(12, 15, '2;16'),
(12, 15, '2;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(13, 15, '2;16'),
(13, 15, '2;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(14, 15, '2;16'),
(14, 15, '2;student');

INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(15, 15, '2;16'),
(15, 15, '2;student');
/*
INSERT INTO `module_quick_mails_scope` (`recipient_id`, `xscope_id`, `xentify_id`) VALUES
(16, 15, '2;16'),
(16, 15, '2;student');



*/
CREATE TABLE IF NOT EXISTS `module_quick_mails_groups` (
  `id` mediumint(8) NOT NULL,
  name varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DELETE FROM `sysclass_root`.`module_quick_mails_recipients_list`;
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('1', '43');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('2', '678');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('3', '334');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('4', '49');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('5', '43');

INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('11', '1339');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('12', '1501');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('14', '1599');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('15', '899');

INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('1', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('2', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('3', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('4', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('5', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('6', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('7', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('8', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('9', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('10', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('11', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('12', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('13', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('14', '1');
INSERT INTO `sysclass_root`.`module_quick_mails_recipients_list` (`recipient_id`, `user_id`) VALUES ('15', '1');


/* 2012-08-16 */
ALTER TABLE `module_xcms_pages_to_blocks` ADD `xscope_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `block_id`;
ALTER TABLE `module_xcms_pages_to_blocks` ADD `xentify_id` TEXT NULL DEFAULT NULL AFTER `xscope_id`;

/* module_pagamento is ONLY FOR POS */
UPDATE `sysclass_root`.`module_xcms_pages_to_blocks` 
SET `xscope_id` = '1', `xentify_id` = '2' 
WHERE `module_xcms_pages_to_blocks`.`page_id` =1 AND `module_xcms_pages_to_blocks`.`block_id` =15;

INSERT INTO `sysclass_root`.`module_xcms_blocks` (`id`, `name`, `module`, `action`, `tag`) VALUES (NULL, 'SysclassXpayInvoices', 'xpay', 'load_invoices', NULL);

UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '1' WHERE `module_quick_mails_recipients`.`id` = 1;
UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '1' WHERE `module_quick_mails_recipients`.`id` = 2;
UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '1' WHERE `module_quick_mails_recipients`.`id` = 3;
UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '1' WHERE `module_quick_mails_recipients`.`id` = 4;
UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '1' WHERE `module_quick_mails_recipients`.`id` = 5;

UPDATE `sysclass_root`.`module_quick_mails_recipients` SET `group_id` = '2' WHERE `module_quick_mails_recipients`.`id` IN (11,12,13,14,15);

ALTER TABLE `module_quick_mails_groups` CHANGE `id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT;
INSERT INTO `sysclass_root`.`module_quick_mails_groups` (`id`, `name`) VALUES (NULL , 'Extensão');
INSERT INTO `sysclass_root`.`module_quick_mails_groups` (`id`, `name`) VALUES (NULL , 'Pós-Graduação');


CREATE TABLE IF NOT EXISTS `service_direct_link_hash` (
  `id` mediumint(8) NOT NULL,
  user_type varchar(50) NOT NULL,
  query varchar(255) NOT NULL,
  expires timestamp NULL DEFAULT NULL,
  hash varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `service_direct_link_hash` CHANGE `id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `service_direct_link_hash` ADD `user_login` VARCHAR( 100 ) NOT NULL AFTER `id`;

/* 2012-09-18 */
UPDATE `sysclass_root`.`module_xcms_pages_to_blocks` SET `xentify_id` = NULL 
WHERE `module_xcms_pages_to_blocks`.`page_id` =1 AND `module_xcms_pages_to_blocks`.`block_id` =22;

/* 2012-09-18 */

