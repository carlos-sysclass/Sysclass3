-- MySQL dump 10.13  Distrib 5.5.42, for Linux (x86_64)
--
-- Host: localhost    Database: sysclass_itaipu
-- ------------------------------------------------------
-- Server version	5.5.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl_resources`
--

DROP TABLE IF EXISTS `acl_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_resources` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ALTERNATIVE` (`group`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_resources`
--

LOCK TABLES `acl_resources` WRITE;
/*!40000 ALTER TABLE `acl_resources` DISABLE KEYS */;
INSERT INTO `acl_resources` VALUES (1,'Roles','View',''),(2,'Roles','Create',''),(3,'Roles','Edit',''),(4,'Advertising','View',''),(5,'Areas','View',''),(6,'Calendar','View',''),(7,'Classes','View',''),(8,'Courses','View',''),(9,'Grades','View',''),(10,'Groups','View',''),(11,'Institution','View',''),(12,'Lessons','View',''),(13,'Questions','View',''),(14,'Tests','View',''),(15,'Translate','View',''),(16,'Users','View',''),(17,'Advertising','Create',''),(18,'Areas','Create',''),(19,'Calendar','Create',''),(20,'Classes','Create',''),(21,'Courses','Create',''),(22,'Grades','Create',''),(23,'Groups','Create',''),(24,'Institution','Create',''),(25,'Lessons','Create',''),(26,'Questions','Create',''),(27,'Tests','Create',''),(28,'Translate','Create',''),(29,'Users','Create',''),(30,'Advertising','Edit',''),(31,'Areas','Edit',''),(32,'Calendar','Edit',''),(33,'Classes','Edit',''),(34,'Courses','Edit',''),(35,'Grades','Edit',''),(36,'Groups','Edit',''),(37,'Institution','Edit',''),(38,'Lessons','Edit',''),(39,'Questions','Edit',''),(40,'Tests','Edit',''),(41,'Translate','Edit',''),(42,'Users','Edit',''),(43,'Calendar','Manage',''),(44,'Users','Change Password',''),(45,'Users','Delete',NULL),(46,'Dropbox','Edit',NULL),(47,'Permission','View',NULL),(48,'Questions','Delete',NULL),(49,'Enroll','View',NULL),(50,'Enroll','Delete',NULL),(51,'Enroll','Create',NULL),(52,'Settings','Manage',NULL),(53,'Roadmap','View',NULL),(54,'Dropbox','Delete',NULL),(55,'Translate','Delete',NULL),(56,'Chat','View',NULL),(59,'Chat','Delete',NULL),(60,'Chat','Assign',NULL),(61,'Chat','Receive',NULL),(62,'Enroll','Edit',NULL),(63,'Classes','Delete',NULL),(64,'Courses','Delete',NULL),(65,'Tests','Delete',NULL);
/*!40000 ALTER TABLE `acl_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_roles`
--

DROP TABLE IF EXISTS `acl_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_roles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `in_course` tinyint(1) NOT NULL DEFAULT '0',
  `in_class` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_roles`
--

LOCK TABLES `acl_roles` WRITE;
/*!40000 ALTER TABLE `acl_roles` DISABLE KEYS */;
INSERT INTO `acl_roles` VALUES (1,'Administrator',NULL,1,0,0),(2,'Student',NULL,1,0,0),(3,'Teacher',NULL,1,0,0);
/*!40000 ALTER TABLE `acl_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_roles_to_groups`
--

DROP TABLE IF EXISTS `acl_roles_to_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_roles_to_groups` (
  `role_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `acl_roles_to_groups_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acl_roles_to_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_roles_to_groups`
--

LOCK TABLES `acl_roles_to_groups` WRITE;
/*!40000 ALTER TABLE `acl_roles_to_groups` DISABLE KEYS */;
INSERT INTO `acl_roles_to_groups` VALUES (1,1),(2,2),(3,3);
/*!40000 ALTER TABLE `acl_roles_to_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_roles_to_resources`
--

DROP TABLE IF EXISTS `acl_roles_to_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_roles_to_resources` (
  `role_id` mediumint(8) unsigned NOT NULL,
  `resource_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `acl_roles_to_resources_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acl_roles_to_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `acl_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_roles_to_resources`
--

LOCK TABLES `acl_roles_to_resources` WRITE;
/*!40000 ALTER TABLE `acl_roles_to_resources` DISABLE KEYS */;
INSERT INTO `acl_roles_to_resources` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(2,44),(1,45),(1,46),(2,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64);
/*!40000 ALTER TABLE `acl_roles_to_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_roles_to_users`
--

DROP TABLE IF EXISTS `acl_roles_to_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_roles_to_users` (
  `role_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `acl_roles_to_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acl_roles_to_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_roles_to_users`
--

LOCK TABLES `acl_roles_to_users` WRITE;
/*!40000 ALTER TABLE `acl_roles_to_users` DISABLE KEYS */;
INSERT INTO `acl_roles_to_users` VALUES (1,1);
/*!40000 ALTER TABLE `acl_roles_to_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `dynamic` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) unsigned DEFAULT NULL,
  `user_types_ID` varchar(50) DEFAULT '0',
  `languages_NAME` varchar(50) DEFAULT NULL,
  `users_active` tinyint(1) DEFAULT '0',
  `assign_profile_to_new` tinyint(1) DEFAULT '0',
  `unique_key` varchar(255) DEFAULT '',
  `is_default` tinyint(1) DEFAULT '0',
  `key_max_usage` mediumint(8) unsigned DEFAULT '0',
  `key_current_usage` mediumint(8) unsigned DEFAULT '0',
  `behaviour_allow_messages` tinyint(1) DEFAULT '0',
  `image` varchar(20) DEFAULT 'group',
  `image_type` varchar(20) DEFAULT 'primary',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Administrators',NULL,1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary'),(2,'Students','Students Group<br>',1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary'),(3,'Teachers','Teacher Group<br>',1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary'),(4,'Chat Coordinators','Chat Coordinators Group<br><br>',1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary'),(5,'Chat Technical Support','Chat Technical Support Group<br><br>',1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_advertising`
--

DROP TABLE IF EXISTS `mod_advertising`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_advertising` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `placement` varchar(50) NOT NULL,
  `view_type` varchar(20) NOT NULL DEFAULT 'serial',
  `banner_size` smallint(4) unsigned NOT NULL DEFAULT '0',
  `global_link` varchar(300) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_advertising`
--

LOCK TABLES `mod_advertising` WRITE;
/*!40000 ALTER TABLE `mod_advertising` DISABLE KEYS */;
INSERT INTO `mod_advertising` VALUES (1,'ads.leftbar.banner','serial',0,NULL,0),(2,'ads.rightbar.banner','serial',1,'http://sysclass.com.br/',1);
/*!40000 ALTER TABLE `mod_advertising` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_advertising_content`
--

DROP TABLE IF EXISTS `mod_advertising_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_advertising_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `advertising_id` mediumint(8) unsigned NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `info` text,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `advertising_id` (`advertising_id`),
  CONSTRAINT `mod_advertising_content_ibfk_1` FOREIGN KEY (`advertising_id`) REFERENCES `mod_advertising` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_advertising_content`
--

LOCK TABLES `mod_advertising_content` WRITE;
/*!40000 ALTER TABLE `mod_advertising_content` DISABLE KEYS */;
INSERT INTO `mod_advertising_content` VALUES (2,2,'file','','{\"preview\":{},\"id\":\"16\",\"owner_id\":\"3\",\"upload_type\":\"image\",\"filename\":\"banner_carro_legenda.jpg\",\"url\":\"http://itaipu.sysclass.com/files/image/banner_carro_legenda.jpg\",\"active\":\"1\",\"name\":\"banner_carro_legenda.jpg\",\"lastModified\":1462461371000,\"lastModifiedDate\":\"2016-05-05T15:16:11.000Z\",\"size\":28461,\"type\":\"image/jpeg\",\"crop\":{\"x\":0,\"y\":0,\"x2\":300,\"y2\":250,\"w\":300,\"h\":250}}','en',NULL,1);
/*!40000 ALTER TABLE `mod_advertising_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_advertising_content_files`
--

DROP TABLE IF EXISTS `mod_advertising_content_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_advertising_content_files` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`file_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `mod_advertising_content_files_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `mod_advertising_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_advertising_content_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_advertising_content_files`
--

LOCK TABLES `mod_advertising_content_files` WRITE;
/*!40000 ALTER TABLE `mod_advertising_content_files` DISABLE KEYS */;
INSERT INTO `mod_advertising_content_files` VALUES (2,16,1);
/*!40000 ALTER TABLE `mod_advertising_content_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_areas`
--

DROP TABLE IF EXISTS `mod_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_areas` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `permission_access_mode` char(1) NOT NULL DEFAULT '4',
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `coordinator_id` int(11) DEFAULT NULL,
  `info` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_areas`
--

LOCK TABLES `mod_areas` WRITE;
/*!40000 ALTER TABLE `mod_areas` DISABLE KEYS */;
INSERT INTO `mod_areas` VALUES (1,'4','Departamento de Compras','Cursos relacionados ao Departamento de Compras da Itaipu<br>',NULL,NULL,1);
/*!40000 ALTER TABLE `mod_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_calendar_events`
--

DROP TABLE IF EXISTS `mod_calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_calendar_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start` int(10) NOT NULL,
  `end` int(10) NOT NULL,
  `source_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_mod_calendar_events_1` (`source_id`),
  CONSTRAINT `fk_mod_calendar_events_1` FOREIGN KEY (`source_id`) REFERENCES `mod_calendar_sources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `mod_calendar_events_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_calendar_events`
--

LOCK TABLES `mod_calendar_events` WRITE;
/*!40000 ALTER TABLE `mod_calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_calendar_sources`
--

DROP TABLE IF EXISTS `mod_calendar_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_calendar_sources` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_calendar_sources`
--

LOCK TABLES `mod_calendar_sources` WRITE;
/*!40000 ALTER TABLE `mod_calendar_sources` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_calendar_sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_chat`
--

DROP TABLE IF EXISTS `mod_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_chat` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `websocket_token` varchar(255) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `requester_id` mediumint(8) unsigned NOT NULL,
  `receiver_id` mediumint(8) unsigned DEFAULT NULL,
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  CONSTRAINT `mod_chat_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_chat`
--

LOCK TABLES `mod_chat` WRITE;
/*!40000 ALTER TABLE `mod_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_chat_messages`
--

DROP TABLE IF EXISTS `mod_chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` mediumint(8) unsigned NOT NULL,
  `message` text,
  `user_id` mediumint(8) unsigned NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `mod_chat_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `mod_chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_chat_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_chat_messages`
--

LOCK TABLES `mod_chat_messages` WRITE;
/*!40000 ALTER TABLE `mod_chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_classes`
--

DROP TABLE IF EXISTS `mod_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_classes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ies_id` mediumint(8) NOT NULL DEFAULT '0',
  `area_id` mediumint(8) unsigned DEFAULT '0',
  `course_id` mediumint(8) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'class',
  `name` varchar(150) NOT NULL,
  `description` text,
  `objectives` text,
  `goals` text,
  `professor_id` mediumint(8) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_classes`
--

LOCK TABLES `mod_classes` WRITE;
/*!40000 ALTER TABLE `mod_classes` DISABLE KEYS */;
INSERT INTO `mod_classes` VALUES (1,0,0,NULL,'class','1. Cadastrando-se como Fornecedor de itaipu',NULL,NULL,NULL,NULL,1),(2,0,0,NULL,'class','2. Código de Conduta para Fornecedores da Itaipu','Módulo 2 - Código de Conduta para Fornecedores da Itaipu<br><br>',NULL,NULL,NULL,1),(3,0,0,NULL,'class','3. Relacionamento com Fornecedores','Módulo 3 - Relacionamento com Fornecedores<br>',NULL,NULL,NULL,1),(4,0,0,NULL,'class','4. Compras Sustentáveis',NULL,NULL,NULL,NULL,1),(5,0,0,NULL,'class','5. Compras Sustentáveis',NULL,NULL,NULL,NULL,1),(6,0,0,NULL,'class','6. Equidade de Gênero',NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `mod_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_classes_progress`
--

DROP TABLE IF EXISTS `mod_classes_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_classes_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `class_id` mediumint(8) unsigned NOT NULL,
  `factor` decimal(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `mod_classes_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_classes_progress_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_classes_progress`
--

LOCK TABLES `mod_classes_progress` WRITE;
/*!40000 ALTER TABLE `mod_classes_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_classes_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_colors`
--

DROP TABLE IF EXISTS `mod_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_colors` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `rgb` varchar(25) NOT NULL,
  `info` varchar(75) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_colors`
--

LOCK TABLES `mod_colors` WRITE;
/*!40000 ALTER TABLE `mod_colors` DISABLE KEYS */;
INSERT INTO `mod_colors` VALUES (115,'#ffffff','white','#ffffff - white'),(116,'#e1e5ec','default','#e1e5ec - default'),(117,'#2f353b','dark','#2f353b - dark'),(118,'#3598dc','blue','#3598dc - blue'),(119,'#578ebe','blue-madison','#578ebe - blue-madison'),(120,'#2C3E50','blue-chambray','#2C3E50 - blue-chambray'),(121,'#22313F','blue-ebonyclay','#22313F - blue-ebonyclay'),(122,'#67809F','blue-hoki','#67809F - blue-hoki'),(123,'#4B77BE','blue-steel','#4B77BE - blue-steel'),(124,'#4c87b9','blue-soft','#4c87b9 - blue-soft'),(125,'#5e738b','blue-dark','#5e738b - blue-dark'),(126,'#5C9BD1','blue-sharp','#5C9BD1 - blue-sharp'),(127,'#32c5d2','green','#32c5d2 - green'),(128,'#1BBC9B','green-meadow','#1BBC9B - green-meadow'),(129,'#1BA39C','green-seagreen','#1BA39C - green-seagreen'),(130,'#36D7B7','green-turquoise','#36D7B7 - green-turquoise'),(131,'#44b6ae','green-haze','#44b6ae - green-haze'),(132,'#26C281','green-jungle','#26C281 - green-jungle'),(133,'#3faba4','green-soft','#3faba4 - green-soft'),(134,'#4DB3A2','green-dark','#4DB3A2 - green-dark'),(135,'#2ab4c0','green-sharp','#2ab4c0 - green-sharp'),(136,'#E5E5E5','grey','#E5E5E5 - grey'),(137,'#e9edef','grey-steel','#e9edef - grey-steel'),(138,'#fafafa','grey-cararra','#fafafa - grey-cararra'),(139,'#555555','grey-gallery','#555555 - grey-gallery'),(140,'#95A5A6','grey-cascade','#95A5A6 - grey-cascade'),(141,'#BFBFBF','grey-silver','#BFBFBF - grey-silver'),(142,'#ACB5C3','grey-salsa','#ACB5C3 - grey-salsa'),(143,'#bfcad1','grey-salt','#bfcad1 - grey-salt'),(144,'#525e64','grey-mint','#525e64 - grey-mint'),(145,'#e7505a','red','#e7505a - red'),(146,'#E08283','red-pink','#E08283 - red-pink'),(147,'#E26A6A','red-sunglo','#E26A6A - red-sunglo'),(148,'#e35b5a','red-intense','#e35b5a - red-intense'),(149,'#D91E18','red-thunderbird','#D91E18 - red-thunderbird'),(150,'#EF4836','red-flamingo','#EF4836 - red-flamingo'),(151,'#d05454','red-soft','#d05454 - red-soft'),(152,'#f36a5a','red-haze','#f36a5a - red-haze'),(153,'#e43a45','red-mint','#e43a45 - red-mint'),(154,'#c49f47','yellow','#c49f47 - yellow'),(155,'#E87E04','yellow-gold','#E87E04 - yellow-gold'),(156,'#f2784b','yellow-casablanca','#f2784b - yellow-casablanca'),(157,'#f3c200','yellow-crusta','#f3c200 - yellow-crusta'),(158,'#F7CA18','yellow-lemon','#F7CA18 - yellow-lemon'),(159,'#F4D03F','yellow-saffron','#F4D03F - yellow-saffron'),(160,'#c8d046','yellow-soft','#c8d046 - yellow-soft'),(161,'#c5bf66','yellow-haze','#c5bf66 - yellow-haze'),(162,'#c5b96b','yellow-mint','#c5b96b - yellow-mint'),(163,'#8E44AD','purple','#8E44AD - purple'),(164,'#8775a7','purple-plum','#8775a7 - purple-plum'),(165,'#BF55EC','purple-medium','#BF55EC - purple-medium'),(166,'#8E44AD','purple-studio','#8E44AD - purple-studio'),(167,'#9B59B6','purple-wisteria','#9B59B6 - purple-wisteria'),(168,'#9A12B3','purple-seance','#9A12B3 - purple-seance'),(169,'#8775a7','purple-intense','#8775a7 - purple-intense'),(170,'#796799','purple-sharp','#796799 - purple-sharp'),(171,'#8877a9','purple-soft','#8877a9 - purple-soft');
/*!40000 ALTER TABLE `mod_colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_courses`
--

DROP TABLE IF EXISTS `mod_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_courses` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` mediumint(8) DEFAULT '0',
  `coordinator_id` mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `objectives` text,
  `goals` text,
  `duration_units` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `duration_type` varchar(45) NOT NULL DEFAULT 'year',
  `price_total` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `price_step_units` mediumint(8) unsigned NOT NULL DEFAULT '10',
  `price_step_type` varchar(45) NOT NULL DEFAULT 'month',
  `archive` int(10) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `mod_coursescol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_courses`
--

LOCK TABLES `mod_courses` WRITE;
/*!40000 ALTER TABLE `mod_courses` DISABLE KEYS */;
INSERT INTO `mod_courses` VALUES (1,1,NULL,'Programa de Desenvolvimento de Fornecedores','Programa de treinamento para fornecedores novos e atuais.<br>',NULL,NULL,1,'year',0.00,10,'month',0,NULL,1,NULL);
/*!40000 ALTER TABLE `mod_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_courses_progress`
--

DROP TABLE IF EXISTS `mod_courses_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_courses_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `course_id` mediumint(8) unsigned NOT NULL,
  `factor` decimal(4,3) NOT NULL DEFAULT '0.000',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_courses_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_courses_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_courses_progress`
--

LOCK TABLES `mod_courses_progress` WRITE;
/*!40000 ALTER TABLE `mod_courses_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_courses_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_dropbox`
--

DROP TABLE IF EXISTS `mod_dropbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_dropbox` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` mediumint(8) unsigned DEFAULT NULL,
  `upload_type` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `filename` varchar(250) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL,
  `size` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `mod_dropbox_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_dropbox`
--

LOCK TABLES `mod_dropbox` WRITE;
/*!40000 ALTER TABLE `mod_dropbox` DISABLE KEYS */;
INSERT INTO `mod_dropbox` VALUES (1,3,'lesson','VACC - aula01.mp4','VACC - aula01.mp4','video/mp4',435778422,'http://itaipu.sysclass.com/files/lesson/VACC%20-%20aula01.mp4',1),(2,3,'lesson','VACC - aula01 (1).mp4','VACC - aula01 (1).mp4','video/mp4',50331648,'',1),(3,3,'lesson','VACC - aula01 (2).mp4','VACC - aula01 (2).mp4','video/mp4',27262976,'',1),(4,3,'lesson','VACC - aula01 (3).mp4','VACC - aula01 (3).mp4','video/mp4',10485760,'',1),(5,3,'lesson','Itaipu_Desfor_SAF-Take4.mp4','Itaipu_Desfor_SAF-Take4.mp4','video/mp4',141588009,'http://itaipu.sysclass.com/files/lesson/Itaipu_Desfor_SAF-Take4.mp4',1),(6,6,'image','carlos_olivera_small.jpg','carlos_olivera_small.jpg','image/jpeg',124518,'http://itaipu.sysclass.com/files/image/carlos_olivera_small.jpg',1),(7,6,'image','carlos_olivera_small (1).jpeg','carlos_olivera_small (1).jpeg','image/jpeg',5868,'http://itaipu.sysclass.com/files/image/carlos_olivera_small (1).jpeg',1),(8,6,'image','comptia.jpeg','comptia.jpeg','image/jpeg',12018,'http://itaipu.sysclass.com/files/image/comptia.jpeg',1),(9,6,'image','carlos_olivera_small (2).jpeg','carlos_olivera_small (2).jpeg','image/jpeg',5868,'http://itaipu.sysclass.com/files/image/carlos_olivera_small (2).jpeg',1),(10,1,'image','YourAvatar (6).jpeg','YourAvatar (6).jpeg','image/jpeg',5437,'https://itaipu.sysclass.com/files/image/YourAvatar (6).jpeg',1),(11,6,'image','carlos_olivera_small (3).jpeg','carlos_olivera_small (3).jpeg','image/jpeg',16790,'http://itaipu.sysclass.com/files/image/carlos_olivera_small (3).jpeg',1),(12,1,'image','YourAvatar (7).jpeg','YourAvatar (7).jpeg','image/jpeg',15469,'https://itaipu.sysclass.com/files/image/YourAvatar (7).jpeg',1),(13,6,'image','carlos_olivera_small (4).jpeg','carlos_olivera_small (4).jpeg','image/jpeg',16790,'http://itaipu.sysclass.com/files/image/carlos_olivera_small (4).jpeg',1),(14,3,'image','01---Assinatura-Preferencial (2).png','01---Assinatura-Preferencial (2).png','image/png',13093,'http://itaipu.sysclass.com/files/image/01---Assinatura-Preferencial%20%282%29.png',1),(15,1,'image','banner_quadrado (2).png','banner_quadrado (2).png','image/png',131301,'http://itaipu.sysclass.com/files/image/banner_quadrado (2).png',1),(16,3,'image','banner_carro_legenda.png','banner_carro_legenda.png','image/png',131301,'http://itaipu.sysclass.com/files/image/banner_carro_legenda.png',1),(17,3,'lesson','Ensaio Rosimeri.mp4','Ensaio Rosimeri.mp4','video/mp4',125635102,'http://itaipu.sysclass.com/files/lesson/Ensaio%20Rosimeri.mp4',1);
/*!40000 ALTER TABLE `mod_dropbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_enroll`
--

DROP TABLE IF EXISTS `mod_enroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_enroll` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `identifier` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `admittance_type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `active` smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll`
--

LOCK TABLES `mod_enroll` WRITE;
/*!40000 ALTER TABLE `mod_enroll` DISABLE KEYS */;
INSERT INTO `mod_enroll` VALUES (1,'Default','2016-06-01','0000-00-00','matricula','individual',0,1);
/*!40000 ALTER TABLE `mod_enroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_enroll_course_to_users`
--

DROP TABLE IF EXISTS `mod_enroll_course_to_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_enroll_course_to_users` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `token` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `enroll_id` mediumint(8) unsigned NOT NULL,
  `course_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `status_id` smallint(4) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tag` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `fk_mod_enroll_course_to_users_1_idx` (`enroll_id`),
  CONSTRAINT `fk_mod_enroll_course_to_users_1` FOREIGN KEY (`enroll_id`) REFERENCES `mod_enroll` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mod_enroll_course_to_users_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_course_to_users`
--

LOCK TABLES `mod_enroll_course_to_users` WRITE;
/*!40000 ALTER TABLE `mod_enroll_course_to_users` DISABLE KEYS */;
INSERT INTO `mod_enroll_course_to_users` VALUES (1,'2d020708-9168-4ac8-9700-513930',1,1,5,1,'2016-05-25 19:26:02',NULL),(8,'41d5cdab-1c75-4843-903a-e30cf0',1,1,6,1,'2016-05-30 16:07:13',NULL),(9,'c7762948-9011-48e1-883e-482333',1,1,7,1,'2016-05-30 16:09:55',NULL),(10,'831afef1-0335-4eb7-a708-0303ce',1,1,8,1,'2016-05-30 18:23:07',NULL),(11,'d226143f-9c98-4cb9-9a3f-db85c4',1,1,9,1,'2016-06-03 14:18:12',NULL),(12,'ca55cbb2-1ad3-49f1-8712-5b648e',1,1,3,1,'2016-06-07 21:56:47',NULL),(13,'57e8c6ae-4ab1-4392-80d6-71e709',1,1,10,1,'2016-06-09 18:17:48',NULL);
/*!40000 ALTER TABLE `mod_enroll_course_to_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_enroll_courses`
--

DROP TABLE IF EXISTS `mod_enroll_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_enroll_courses` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `enroll_id` mediumint(8) unsigned NOT NULL,
  `course_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enroll_id` (`enroll_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_enroll_courses_ibfk_1` FOREIGN KEY (`enroll_id`) REFERENCES `mod_enroll` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mod_enroll_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_courses`
--

LOCK TABLES `mod_enroll_courses` WRITE;
/*!40000 ALTER TABLE `mod_enroll_courses` DISABLE KEYS */;
INSERT INTO `mod_enroll_courses` VALUES (1,1,1);
/*!40000 ALTER TABLE `mod_enroll_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_enroll_fields`
--

DROP TABLE IF EXISTS `mod_enroll_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_enroll_fields` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `enroll_id` mediumint(8) unsigned NOT NULL,
  `field_id` mediumint(8) unsigned NOT NULL,
  `label` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `weight` smallint(4) unsigned NOT NULL DEFAULT '12',
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `required_time` smallint(4) NOT NULL DEFAULT '0',
  `position` smallint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enroll_id` (`enroll_id`),
  KEY `field_id` (`field_id`),
  CONSTRAINT `mod_enroll_fields_ibfk_1` FOREIGN KEY (`enroll_id`) REFERENCES `mod_enroll` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mod_enroll_fields_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `mod_fields` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_fields`
--

LOCK TABLES `mod_enroll_fields` WRITE;
/*!40000 ALTER TABLE `mod_enroll_fields` DISABLE KEYS */;
INSERT INTO `mod_enroll_fields` VALUES (4,1,2,'Nome',6,1,0,1),(6,1,1,'Email',12,1,0,3),(21,1,4,'CNPJ',6,0,0,13),(22,1,3,'Sobrenome',6,1,0,2),(24,1,6,'Como conheceu o programa?',12,1,0,14),(25,1,8,'É fornecedor da ITAIPU?',6,0,0,11),(26,1,9,'Nome da Empresa',12,0,0,12),(27,1,10,'Cep',6,1,0,5),(28,1,11,'País',6,1,0,4),(29,1,20,'Bairro',6,1,0,8),(30,1,19,'Número',6,1,0,7),(31,1,18,'Endereço',12,1,0,6),(32,1,21,'Cidade',6,1,0,10),(33,1,22,'Estado',6,1,0,9);
/*!40000 ALTER TABLE `mod_enroll_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_enroll_fields_options`
--

DROP TABLE IF EXISTS `mod_enroll_fields_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_enroll_fields_options` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `enroll_field_id` mediumint(8) unsigned NOT NULL,
  `label` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `position` smallint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `_idx` (`enroll_field_id`),
  CONSTRAINT `fk_enroll_fields_id` FOREIGN KEY (`enroll_field_id`) REFERENCES `mod_enroll_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_fields_options`
--

LOCK TABLES `mod_enroll_fields_options` WRITE;
/*!40000 ALTER TABLE `mod_enroll_fields_options` DISABLE KEYS */;
INSERT INTO `mod_enroll_fields_options` VALUES (1,24,'Google','google',0),(2,24,'Facebook','facebook',1),(3,24,'Folder','folder',2),(4,24,'Indicação','indicacao',3),(5,24,'Jornal','jornal',4),(6,24,'Televisão','televisao',5),(7,24,'Amigo que trabalha na Itaipu','amigo_na_itaipu',6),(8,24,'Empresa onde Trabalho','empresa_onde_trabalho',7),(9,24,'Email','email',8),(10,24,'Palestras','palestras',9),(11,24,'Outros','outros',10),(12,28,'Brasil','brasil',0),(13,28,'Paraguai','paraguai',1);
/*!40000 ALTER TABLE `mod_enroll_fields_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_fields`
--

DROP TABLE IF EXISTS `mod_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_fields` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `type_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `mod_fields_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `mod_fields_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_fields`
--

LOCK TABLES `mod_fields` WRITE;
/*!40000 ALTER TABLE `mod_fields` DISABLE KEYS */;
INSERT INTO `mod_fields` VALUES (1,'email',2,1),(2,'name',1,1),(3,'surname',1,1),(4,'cnpj',3,1),(5,'phone',4,1),(6,'how_did_you_know',5,1),(8,'is_supplier',6,1),(9,'supplier_name',1,1),(10,'postal_code',7,1),(11,'country',5,1),(18,'street',1,1),(19,'street_number',8,1),(20,'district',1,1),(21,'city',1,1),(22,'state',1,1);
/*!40000 ALTER TABLE `mod_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_fields_types`
--

DROP TABLE IF EXISTS `mod_fields_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_fields_types` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `helper_class` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_fields_types`
--

LOCK TABLES `mod_fields_types` WRITE;
/*!40000 ALTER TABLE `mod_fields_types` DISABLE KEYS */;
INSERT INTO `mod_fields_types` VALUES (1,'text',''),(2,'text','email'),(3,'text','cnpj'),(4,'text','phone_br'),(5,'select2',''),(6,'checkbox',''),(7,'text','zipcode_br'),(8,'text','integer');
/*!40000 ALTER TABLE `mod_fields_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_grades`
--

DROP TABLE IF EXISTS `mod_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_grades`
--

LOCK TABLES `mod_grades` WRITE;
/*!40000 ALTER TABLE `mod_grades` DISABLE KEYS */;
INSERT INTO `mod_grades` VALUES (1,'Zero a cem','Regra de cálculo para notas de 0 a 100.<br><br>Se a nota for maior que 60%, o aluno é aprovado.<br>',1);
/*!40000 ALTER TABLE `mod_grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_grades_ranges`
--

DROP TABLE IF EXISTS `mod_grades_ranges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_grades_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_id` int(11) NOT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `range_start` int(4) NOT NULL DEFAULT '0',
  `range_end` int(4) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `grade_id` (`grade_id`),
  CONSTRAINT `mod_grades_ranges_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `mod_grades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_grades_ranges`
--

LOCK TABLES `mod_grades_ranges` WRITE;
/*!40000 ALTER TABLE `mod_grades_ranges` DISABLE KEYS */;
INSERT INTO `mod_grades_ranges` VALUES (1,1,NULL,0,60),(2,1,NULL,61,100);
/*!40000 ALTER TABLE `mod_grades_ranges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_institution`
--

DROP TABLE IF EXISTS `mod_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_institution` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `permission_access_mode` char(1) NOT NULL DEFAULT '4',
  `name` varchar(250) NOT NULL,
  `contact` varchar(250) DEFAULT NULL,
  `observations` text,
  `zip` varchar(15) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `number` varchar(15) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country_code` varchar(3) NOT NULL DEFAULT 'BR',
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `logo_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logo_id` (`logo_id`),
  CONSTRAINT `mod_institution_ibfk_1` FOREIGN KEY (`logo_id`) REFERENCES `mod_dropbox` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_institution`
--

LOCK TABLES `mod_institution` WRITE;
/*!40000 ALTER TABLE `mod_institution` DISABLE KEYS */;
INSERT INTO `mod_institution` VALUES (1,'4','Itaipu Binacional',NULL,NULL,NULL,NULL,NULL,NULL,'Curitiba','Paraná','BR','(41) 3321-4411',1,'http://itaipu.gov.br','itaipu',14);
/*!40000 ALTER TABLE `mod_institution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons`
--

DROP TABLE IF EXISTS `mod_lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `permission_access_mode` char(1) NOT NULL DEFAULT '4',
  `class_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `info` text,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) NOT NULL DEFAULT 'lesson',
  `has_text_content` tinyint(1) NOT NULL DEFAULT '1',
  `text_content` text,
  `text_content_language_id` int(11) DEFAULT '1',
  `has_video_content` tinyint(1) DEFAULT '1',
  `subtitle_content_language_id` int(11) DEFAULT '1',
  `instructor_id` text,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `mod_lessons_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons`
--

LOCK TABLES `mod_lessons` WRITE;
/*!40000 ALTER TABLE `mod_lessons` DISABLE KEYS */;
INSERT INTO `mod_lessons` VALUES (2,'4',2,'Código de Conduta para Fornecedores da Itaipu',NULL,NULL,1,'lesson',1,NULL,1,1,1,NULL),(4,'4',3,'Aula 03 - SAF',NULL,NULL,1,'lesson',1,NULL,1,1,1,NULL),(5,'4',3,'Sistema de Avaliação de Fornecedores',NULL,NULL,1,'lesson',1,NULL,1,1,1,NULL),(6,'4',1,'Apresentação do Curso',NULL,NULL,1,'lesson',1,NULL,1,1,1,'\"3\"');
/*!40000 ALTER TABLE `mod_lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content`
--

DROP TABLE IF EXISTS `mod_lessons_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned DEFAULT NULL,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `info` text,
  `content` text,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `mod_lessons_content_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content`
--

LOCK TABLES `mod_lessons_content` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content` DISABLE KEYS */;
INSERT INTO `mod_lessons_content` VALUES (1,NULL,2,'file','','{\"id\":\"1\",\"owner_id\":\"3\",\"upload_type\":\"lesson\",\"name\":\"VACC - aula01.mp4\",\"filename\":\"VACC - aula01.mp4\",\"type\":\"video/mp4\",\"size\":\"435778422\",\"url\":\"http://itaipu.sysclass.com/files/lesson/VACC%20-%20aula01.mp4\",\"active\":\"1\"}','','en',NULL,1,0),(2,NULL,4,'file','','{\"id\":\"5\",\"owner_id\":\"3\",\"upload_type\":\"lesson\",\"name\":\"Itaipu_Desfor_SAF-Take4.mp4\",\"filename\":\"Itaipu_Desfor_SAF-Take4.mp4\",\"type\":\"video/mp4\",\"size\":\"141588009\",\"url\":\"http://itaipu.sysclass.com/files/lesson/Itaipu_Desfor_SAF-Take4.mp4\",\"active\":\"1\"}','','en',NULL,1,0),(3,NULL,5,'url','','','https://dl.dropboxusercontent.com/u/95443/desfor%20itaipu%20cursos/video%20aulas/03-Relacionamento%20com%20fornecedores/VARF%20-%20Sistema%20de%20Avalia%C3%A7%C3%A3o%20de%20Fornecedores_v2.mp4','en',NULL,1,0),(5,NULL,6,'file','','{\"id\":\"17\",\"owner_id\":\"3\",\"upload_type\":\"lesson\",\"name\":\"Ensaio Rosimeri.mp4\",\"filename\":\"Ensaio Rosimeri.mp4\",\"type\":\"video/mp4\",\"size\":\"125635102\",\"url\":\"http://itaipu.sysclass.com/files/lesson/Ensaio%20Rosimeri.mp4\",\"active\":\"1\"}','','en',NULL,1,0);
/*!40000 ALTER TABLE `mod_lessons_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content_files`
--

DROP TABLE IF EXISTS `mod_lessons_content_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content_files` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`file_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `mod_lessons_content_files_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content_files`
--

LOCK TABLES `mod_lessons_content_files` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content_files` DISABLE KEYS */;
INSERT INTO `mod_lessons_content_files` VALUES (1,1,1),(2,5,1),(5,17,1);
/*!40000 ALTER TABLE `mod_lessons_content_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content_progress`
--

DROP TABLE IF EXISTS `mod_lessons_content_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content_progress` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `content_id` mediumint(8) unsigned NOT NULL,
  `factor` decimal(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`user_id`,`content_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `mod_lessons_content_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_progress_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content_progress`
--

LOCK TABLES `mod_lessons_content_progress` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_lessons_content_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content_questions`
--

DROP TABLE IF EXISTS `mod_lessons_content_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content_questions` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`content_id`,`question_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `mod_lessons_content_questions_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `mod_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content_questions`
--

LOCK TABLES `mod_lessons_content_questions` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_lessons_content_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content_questions_answers`
--

DROP TABLE IF EXISTS `mod_lessons_content_questions_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content_questions_answers` (
  `content_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `answer` text,
  PRIMARY KEY (`content_id`,`question_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `mod_lessons_content_questions_answers_ibfk_1` FOREIGN KEY (`content_id`, `question_id`) REFERENCES `mod_lessons_content_questions` (`content_id`, `question_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_questions_answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content_questions_answers`
--

LOCK TABLES `mod_lessons_content_questions_answers` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content_questions_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_lessons_content_questions_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_progress`
--

DROP TABLE IF EXISTS `mod_lessons_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `factor` decimal(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `mod_lessons_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_progress`
--

LOCK TABLES `mod_lessons_progress` WRITE;
/*!40000 ALTER TABLE `mod_lessons_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_lessons_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_listeners`
--

DROP TABLE IF EXISTS `mod_listeners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_listeners` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_listeners`
--

LOCK TABLES `mod_listeners` WRITE;
/*!40000 ALTER TABLE `mod_listeners` DISABLE KEYS */;
INSERT INTO `mod_listeners` VALUES (1,'course','user-completed','certificate','make-avaliable',1),(2,'user','password-reset','users','start-password-request',1);
/*!40000 ALTER TABLE `mod_listeners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_messages`
--

DROP TABLE IF EXISTS `mod_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_messages` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `reply_to` mediumint(8) unsigned DEFAULT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `timestamp` int(10) unsigned DEFAULT '0',
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `mod_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_messages`
--

LOCK TABLES `mod_messages` WRITE;
/*!40000 ALTER TABLE `mod_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_messages_groups`
--

DROP TABLE IF EXISTS `mod_messages_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_messages_groups` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_messages_groups`
--

LOCK TABLES `mod_messages_groups` WRITE;
/*!40000 ALTER TABLE `mod_messages_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_messages_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_messages_to_groups`
--

DROP TABLE IF EXISTS `mod_messages_to_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_messages_to_groups` (
  `message_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`message_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `mod_messages_to_groups_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `mod_messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_messages_to_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_messages_to_groups`
--

LOCK TABLES `mod_messages_to_groups` WRITE;
/*!40000 ALTER TABLE `mod_messages_to_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_messages_to_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_news`
--

DROP TABLE IF EXISTS `mod_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `data` text,
  `timestamp` int(10) unsigned DEFAULT '0',
  `expire` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `mod_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_news`
--

LOCK TABLES `mod_news` WRITE;
/*!40000 ALTER TABLE `mod_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_notification_to_users`
--

DROP TABLE IF EXISTS `mod_notification_to_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_notification_to_users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `message` text NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'info',
  `link_text` varchar(100) DEFAULT NULL,
  `link_href` varchar(300) DEFAULT NULL,
  `stick` tinyint(1) NOT NULL DEFAULT '0',
  `viewed` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `mod_notification_to_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_notification_to_users`
--

LOCK TABLES `mod_notification_to_users` WRITE;
/*!40000 ALTER TABLE `mod_notification_to_users` DISABLE KEYS */;
INSERT INTO `mod_notification_to_users` VALUES (1,1,'You request a password reset.','activity','View','/module/users/view/',0,0,1465327924);
/*!40000 ALTER TABLE `mod_notification_to_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_payment`
--

DROP TABLE IF EXISTS `mod_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_payment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mod_payment_1_idx` (`user_id`),
  CONSTRAINT `fk_mod_payment_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_payment`
--

LOCK TABLES `mod_payment` WRITE;
/*!40000 ALTER TABLE `mod_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_payment_itens`
--

DROP TABLE IF EXISTS `mod_payment_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_payment_itens` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `payment_id` mediumint(8) DEFAULT NULL,
  `vencimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_payment_itens`
--

LOCK TABLES `mod_payment_itens` WRITE;
/*!40000 ALTER TABLE `mod_payment_itens` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_payment_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_payment_transacao`
--

DROP TABLE IF EXISTS `mod_payment_transacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_payment_transacao` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `payment_itens_id` mediumint(8) DEFAULT NULL,
  `descricao` text,
  `token` varchar(100) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mod_payment_transacao_1_idx` (`payment_itens_id`),
  CONSTRAINT `fk_mod_payment_transacao_1` FOREIGN KEY (`payment_itens_id`) REFERENCES `mod_payment_itens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_payment_transacao`
--

LOCK TABLES `mod_payment_transacao` WRITE;
/*!40000 ALTER TABLE `mod_payment_transacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_payment_transacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_permission_conditions`
--

DROP TABLE IF EXISTS `mod_permission_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_permission_conditions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `condition_id` varchar(50) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_permission_conditions`
--

LOCK TABLES `mod_permission_conditions` WRITE;
/*!40000 ALTER TABLE `mod_permission_conditions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_permission_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_permission_entities`
--

DROP TABLE IF EXISTS `mod_permission_entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_permission_entities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `entity_id` varchar(50) NOT NULL,
  `condition_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_permission_entities`
--

LOCK TABLES `mod_permission_entities` WRITE;
/*!40000 ALTER TABLE `mod_permission_entities` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_permission_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_questions`
--

DROP TABLE IF EXISTS `mod_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `question` text NOT NULL,
  `area_id` mediumint(8) unsigned NOT NULL,
  `difficulty_id` mediumint(8) unsigned NOT NULL,
  `type_id` varchar(20) NOT NULL,
  `options` text,
  `answer` text,
  `explanation` text,
  `answers_explanation` text,
  `estimate` int(10) unsigned DEFAULT NULL,
  `settings` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `difficulty_id` (`difficulty_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `mod_questions_ibfk_1` FOREIGN KEY (`difficulty_id`) REFERENCES `mod_questions_difficulties` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mod_questions_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `mod_questions_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_questions`
--

LOCK TABLES `mod_questions` WRITE;
/*!40000 ALTER TABLE `mod_questions` DISABLE KEYS */;
INSERT INTO `mod_questions` VALUES (1,'One choice question','<blockquote><blockquote><h1><i>What is the </i><i>correct </i><u>c</u><b><u>hoic</u></b><u>e</u><b>?</b></h1></blockquote></blockquote>',1,1,'simple_choice','[{\"index\":0,\"answer\":true,\"choice\":\"Choice A\"},{\"index\":1,\"choice\":\"Choice B\",\"answer\":false},{\"index\":2,\"choice\":\"Choice C\",\"answer\":false}]',NULL,NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `mod_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_questions_difficulties`
--

DROP TABLE IF EXISTS `mod_questions_difficulties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_questions_difficulties` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_questions_difficulties`
--

LOCK TABLES `mod_questions_difficulties` WRITE;
/*!40000 ALTER TABLE `mod_questions_difficulties` DISABLE KEYS */;
INSERT INTO `mod_questions_difficulties` VALUES (1,'Easy'),(2,'Normal'),(3,'Hard'),(4,'Very Hard');
/*!40000 ALTER TABLE `mod_questions_difficulties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_questions_types`
--

DROP TABLE IF EXISTS `mod_questions_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_questions_types` (
  `id` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_questions_types`
--

LOCK TABLES `mod_questions_types` WRITE;
/*!40000 ALTER TABLE `mod_questions_types` DISABLE KEYS */;
INSERT INTO `mod_questions_types` VALUES ('free_text','Free Text'),('multiple_choice','Multiple Choice'),('simple_choice','Simple Choice'),('true_or_false','True Or False');
/*!40000 ALTER TABLE `mod_questions_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_roadmap_classes_to_periods`
--

DROP TABLE IF EXISTS `mod_roadmap_classes_to_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_roadmap_classes_to_periods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` mediumint(8) unsigned NOT NULL,
  `class_id` mediumint(8) unsigned NOT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `period_id` (`period_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `mod_roadmap_classes_to_periods_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `mod_roadmap_courses_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_roadmap_classes_to_periods_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_classes_to_periods`
--

LOCK TABLES `mod_roadmap_classes_to_periods` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_classes_to_periods` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_roadmap_classes_to_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_roadmap_courses_grouping`
--

DROP TABLE IF EXISTS `mod_roadmap_courses_grouping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_roadmap_courses_grouping` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_roadmap_courses_grouping_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_courses_grouping`
--

LOCK TABLES `mod_roadmap_courses_grouping` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_courses_grouping` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_roadmap_courses_grouping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_roadmap_courses_periods`
--

DROP TABLE IF EXISTS `mod_roadmap_courses_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_roadmap_courses_periods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `max_classes` int(8) DEFAULT '-1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_roadmap_courses_periods_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_courses_periods`
--

LOCK TABLES `mod_roadmap_courses_periods` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_courses_periods` DISABLE KEYS */;
INSERT INTO `mod_roadmap_courses_periods` VALUES (1,1,'Desfor',NULL,-1,1);
/*!40000 ALTER TABLE `mod_roadmap_courses_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_roadmap_courses_to_classes`
--

DROP TABLE IF EXISTS `mod_roadmap_courses_to_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_roadmap_courses_to_classes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(8) unsigned NOT NULL,
  `class_id` mediumint(8) unsigned NOT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `mod_roadmap_courses_to_classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_roadmap_courses_to_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_courses_to_classes`
--

LOCK TABLES `mod_roadmap_courses_to_classes` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_courses_to_classes` DISABLE KEYS */;
INSERT INTO `mod_roadmap_courses_to_classes` VALUES (1,1,1,NULL,NULL,NULL,0),(2,1,2,NULL,NULL,NULL,1),(3,1,3,NULL,NULL,NULL,1),(4,1,4,NULL,NULL,NULL,1),(5,1,5,NULL,NULL,NULL,0),(6,1,6,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `mod_roadmap_courses_to_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_tests`
--

DROP TABLE IF EXISTS `mod_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_tests` (
  `id` mediumint(8) unsigned NOT NULL,
  `grade_id` mediumint(8) unsigned DEFAULT NULL,
  `time_limit` smallint(4) NOT NULL DEFAULT '0',
  `allow_pause` tinyint(1) NOT NULL DEFAULT '0',
  `test_repetition` smallint(4) NOT NULL DEFAULT '1',
  `show_question_weight` tinyint(1) NOT NULL DEFAULT '0',
  `show_question_difficulty` tinyint(1) NOT NULL DEFAULT '0',
  `show_question_type` tinyint(1) NOT NULL DEFAULT '1',
  `show_one_by_one` tinyint(1) NOT NULL DEFAULT '0',
  `can_navigate_through` tinyint(1) NOT NULL DEFAULT '0',
  `show_correct_answers` tinyint(1) NOT NULL DEFAULT '0',
  `randomize_questions` tinyint(1) NOT NULL DEFAULT '0',
  `randomize_answers` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `mod_tests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_tests`
--

LOCK TABLES `mod_tests` WRITE;
/*!40000 ALTER TABLE `mod_tests` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_tests_execution`
--

DROP TABLE IF EXISTS `mod_tests_execution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_tests_execution` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `test_id` mediumint(8) unsigned NOT NULL,
  `try_index` smallint(4) unsigned NOT NULL DEFAULT '1',
  `start_timestamp` int(10) NOT NULL DEFAULT '0',
  `paused` tinyint(1) NOT NULL DEFAULT '0',
  `pending` tinyint(1) NOT NULL DEFAULT '1',
  `completed` int(10) NOT NULL DEFAULT '0',
  `answers` text,
  `user_score` decimal(15,4) DEFAULT NULL,
  `user_points` int(11) DEFAULT NULL,
  `user_grade` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  CONSTRAINT `mod_tests_execution_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_tests_execution`
--

LOCK TABLES `mod_tests_execution` WRITE;
/*!40000 ALTER TABLE `mod_tests_execution` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_tests_execution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_tests_to_questions`
--

DROP TABLE IF EXISTS `mod_tests_to_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_tests_to_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `points` smallint(4) NOT NULL DEFAULT '1',
  `weight` smallint(4) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_id` (`lesson_id`,`question_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `mod_tests_to_questions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_tests_to_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `mod_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_tests_to_questions`
--

LOCK TABLES `mod_tests_to_questions` WRITE;
/*!40000 ALTER TABLE `mod_tests_to_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_tests_to_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_translate`
--

DROP TABLE IF EXISTS `mod_translate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `js_code` varchar(10) DEFAULT NULL,
  `country_code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `local_name` varchar(50) NOT NULL,
  `rtl` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `local_name` (`local_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_translate`
--

LOCK TABLES `mod_translate` WRITE;
/*!40000 ALTER TABLE `mod_translate` DISABLE KEYS */;
INSERT INTO `mod_translate` VALUES (1,'en','en','US','English','English',0,1),(2,'pt','pt-br','BR','Portuguese','Português',0,1),(3,'es','es','ES','Spanish','Español',0,1),(4,'ko','ko','KR','Korean','한국의',0,0),(5,'it','it','IT','Italian','Italiano',0,1),(6,'fr','fr','FR','French','Français',0,1),(7,'el','el','GR','Greek','Greek',0,0),(8,'zh-CHS','zh-cn','CN','Chinese','Chinese',1,0),(9,'ar','ar','SA','Arabic','Arabic',1,0),(10,'ru','ru','RU','Russian','Russia',0,0),(11,'th','th','TH','Thailand','Siamese',0,0);
/*!40000 ALTER TABLE `mod_translate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_translate_tokens`
--

DROP TABLE IF EXISTS `mod_translate_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_translate_tokens` (
  `language_code` varchar(10) NOT NULL,
  `token` varchar(757) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `text` varchar(980) NOT NULL,
  `edited` tinyint(1) unsigned DEFAULT '0',
  `timestamp` int(10) DEFAULT NULL,
  PRIMARY KEY (`language_code`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_translate_tokens`
--

LOCK TABLES `mod_translate_tokens` WRITE;
/*!40000 ALTER TABLE `mod_translate_tokens` DISABLE KEYS */;
INSERT INTO `mod_translate_tokens` VALUES ('en','#','#',0,1456342038),('en','# Questions','# Questions',NULL,1456346843),('en','% Complete','% Complete',0,1456343955),('en','A problem ocurred when tried to save you data. Please try again.','A problem ocurred when tried to save you data. Please try again.',0,1456341208),('en','About You','About You',0,1456346856),('en','Account','Account',0,1456346856),('en','Action if the maximum is reached','Action if the maximum is reached',0,1464101110),('en','Actions','Actions',NULL,1456342039),('en','Active','Active',NULL,1456342038),('en','Add Exercises','Add Exercises',0,1463670130),('en','Add Field','Add Field',0,1464101134),('en','Add File','Add File',0,1463670130),('en','Add HTML','Add HTML',0,1465322795),('en','Add Language','Add Language',NULL,1456344025),('en','Add Subtitles','Add Subtitles',0,1463670130),('en','Add Text','Add Text',0,1463670130),('en','Add Url','Add Url',0,1463670130),('en','Add a Video Poster','Add a Video Poster',0,1463670130),('en','Add a new Course Grouping','Add a new Course Grouping',0,1463669309),('en','Add a new Question','Add a new Question',0,1463670130),('en','Add/Change file','Add/Change file',0,1456343954),('en','Address','Address',0,1464031501),('en','Address Book','Address Book',0,1456343954),('en','Address Line 1','Address Line 1',NULL,1456343954),('en','Address Line 2','Address Line 2',NULL,1456343954),('en','Administation','Administation',0,1456341203),('en','Administrator','Administrator',0,1463669094),('en','Admittance Type','Admittance Type',0,1463669111),('en','Advertising','Advertising',NULL,1456343720),('en','All','All',0,1456346843),('en','Allow anonimous users to create an account','Allow anonimous users to create an account',0,1456344464),('en','Allow pause the test?','Allow pause the test?',0,1464965714),('en','Allow the user to select the the classes order','Allow the user to select the the classes order',0,1463669309),('en','Allow user to be a class/lesson instructor','Allow user to be a class/lesson instructor',0,1456343783),('en','Allow user to be a course coordinator','Allow user to be a course coordinator',0,1456343783),('en','Announcements','Announcements',0,1456346843),('en','Are you sure?','Are you sure?',0,1456341204),('en','Assign to Another User','Assign to Another User',NULL,1463669094),('en','Assign to me','Assign to me',NULL,1463669094),('en','Attach Files','Attach Files',0,1456346843),('en','Attendee','Attendee',0,1456346843),('en','Attendence','Attendence',NULL,1456346843),('en','Attributions','Attributions',0,1463669094),('en','Author','Author',0,1456346843),('en','Automatic translate to','Automatic translate to',0,1463670130),('en','Back','Back',0,1456340941),('en','Banner Size','Banner Size',0,1465322795),('en','Banners','Banners',0,1465322795),('en','Behavior','Behavior',0,1456343783),('en','Bibliography','Bibliography',NULL,1456346843),('en','Birthday','Birthday',0,1456346856),('en','Block Admittance','Block Admittance',0,1464101110),('en','Block user input only in the current question','Block user input only in the current question',0,1464965714),('en','Book','Book',0,1456346843),('en','Books:','Books:',0,1456346843),('en','Calendar','Calendar',0,1456346843),('en','Campus:','Campus:',0,1456346843),('en','Can be Coordinator?','Can be Coordinator?',0,1456343783),('en','Can be Instructor?','Can be Instructor?',0,1456343783),('en','Can messages be sent to this group?','Can messages be sent to this group?',0,1464032383),('en','Can navigate through the test?','Can navigate through the test?',0,1464965714),('en','Cancel','Cancel',0,1456346843),('en','Certificates','Certificates',0,1463669094),('en','Change Password','Change Password',0,1456346856),('en','Change file','Change file',0,1463669094),('en','Chat','Chat',0,1464030895),('en','Chat not avaliable','Chat not avaliable',0,1456346843),('en','Choice','Choice',0,1463670130),('en','Choose language','Choose language',NULL,1463670130),('en','City','City',NULL,1456343955),('en','Class','Class',0,1463670130),('en','Class Role','Class Role',0,1456342039),('en','Classes','Classes',0,1456342037),('en','Classes Disponible','Classes Disponible',0,1463669309),('en','Click','Click',0,1465327495),('en','Click here to move choice','Click here to move choice',0,1463670130),('en','Click here to move content','Click here to move content',NULL,1463670130),('en','Click to access','Click to access',0,1456340938),('en','Close','Close',0,1456342037),('en','Closed','Closed',0,1456346843),('en','Code','Code',0,1456344025),('en','Collapse All','Collapse All',0,1463670130),('en','Collection sorted successfully','Collection sorted successfully',0,1464709458),('en','Communication','Communication',0,1456341203),('en','Completed','Completed',NULL,1456346843),('en','Config and edit group info','Config and edit group info',0,1464032403),('en','Config and edit the grade','Config and edit the grade',0,1464965942),('en','Config and edit user info','Config and edit user info',0,1456343782),('en','Confirm','Confirm',0,1456341204),('en','Content','Content',0,1456341203),('en','Content Editor','Content Editor',0,1463670130),('en','Coordinator','Coordinator',NULL,1463669140),('en','Copy','Copy',0,1463670130),('en','Copy content from another lesson','Copy content from another lesson',0,1463670130),('en','Country','Country',0,1456343954),('en','Course','Course',NULL,1456346843),('en','Course Duration','Course Duration',0,1463669197),('en','Course Format','Course Format',0,1464101110),('en','Course Grouping','Course Grouping',NULL,1463669309),('en','Course Objectives','Course Objectives',0,1464623775),('en','Course Periods','Course Periods',0,1463669309),('en','Course Prices','Course Prices',0,1463669197),('en','Course Role','Course Role',0,1456342038),('en','Courses','Courses',0,1456342037),('en','Create Attribution','Create Attribution',0,1464031584),('en','Create Block','Create Block',0,1463669309),('en','Create Class','Create Class',0,1463669309),('en','Create Enrollment Guidelines','Create Enrollment Guidelines',0,1464101109),('en','Create Lesson','Create Lesson',0,1463669813),('en','Create Period','Create Period',0,1464101110),('en','Create Question','Create Question',0,1463670130),('en','Create Role','Create Role',0,1456342037),('en','Create Test','Create Test',0,1463669813),('en','Create a new Departament','Create a new Departament',0,1463669140),('en','Create a new Question','Create a new Question',0,1463670130),('en','Create a new User','Create a new User',0,1456346680),('en','Create a new grade','Create a new grade',0,1464965793),('en','Create a new lessons','Create a new lessons',0,1464031631),('en','Create a new program','Create a new program',0,1463669196),('en','Create a new question','Create a new question',0,1464970371),('en','Create a new test','Create a new test',0,1464965714),('en','Create an account','Create an account',0,1456345772),('en','Created with success','Created with success',0,1456346735),('en','Credit Hours:','Credit Hours:',0,1456346843),('en','Crop Image','Crop Image',0,1456343953),('en','Current Password','Current Password',0,1456346856),('en','Curriculum','Curriculum',0,1464639579),('en','Date','Date',0,1456346843),('en','Departaments','Departaments',0,1456347058),('en','Department','Department',NULL,1463669197),('en','Departments','Departments',NULL,1456343720),('en','Description','Description',0,1456342038),('en','Details','Details',0,1456346843),('en','Difficulty','Difficulty',NULL,1463670130),('en','District','District',NULL,1464031501),('en','Division/Portifolio:','Division/Portifolio:',0,1456346843),('en','Do it again!','Do it again!',0,1456346843),('en','Do now!','Do now!',NULL,1456346843),('en','Do you really want to remove this conversation?','Do you really want to remove this conversation?',0,1463669094),('en','Docs In Box','Docs In Box',0,1456346843),('en','Docs Pending','Docs Pending',0,1456346843),('en','Don\'t have an account?','Don\'t have an account?',0,1456345772),('en','Done','Done',0,1456346843),('en','Download','Download',NULL,1456346843),('en','Drag to reposition item','Drag to reposition item',0,1463669309),('en','Drop Box','Drop Box',NULL,1456346843),('en','Dropbox','Dropbox',0,1456346843),('en','During this course you will...','During this course you will...',0,1456346843),('en','Dynamic','Dynamic',0,1464101110),('en','Edit Advertising','Edit Advertising',0,1465322795),('en','Edit Department','Edit Department',0,1463669178),('en','Edit Enrollment Guideline','Edit Enrollment Guideline',0,1464101134),('en','Edit Enrollment Guidelines','Edit Enrollment Guidelines',0,1464101134),('en','Edit Grade Rule','Edit Grade Rule',0,1464965942),('en','Edit Group','Edit Group',0,1464032403),('en','Edit Organization','Edit Organization',0,1456343953),('en','Edit Program','Edit Program',0,1463669309),('en','Edit Question','Edit Question',0,1464970570),('en','Edit User','Edit User',0,1456343782),('en','Edit a Departament','Edit a Departament',0,1463669178),('en','Edit a advertising item','Edit a advertising item',0,1465322795),('en','Edit your Organization','Edit your Organization',0,1456343953),('en','Edit your class info','Edit your class info',0,1463669813),('en','Edit your lesson info','Edit your lesson info',0,1463670129),('en','Edit your program info','Edit your program info',0,1463669309),('en','Edit your questions','Edit your questions',0,1464970569),('en','Email','Email',NULL,1456340940),('en','Email Address','Email Address',NULL,1456346856),('en','Email:','Email:',0,1456346843),('en','Emails','Emails',NULL,1456346843),('en','Enable Course Groupings','Enable Course Groupings',0,1463669309),('en','Enable Course Periods','Enable Course Periods',0,1463669309),('en','Enable Facebook Login?','Enable Facebook Login?',0,1456344463),('en','Enable Forgot Form','Enable Forgot Form',0,1456344463),('en','Enable Google+ Login?','Enable Google+ Login?',0,1456344463),('en','Enable LinkedIn Login?','Enable LinkedIn Login?',0,1456344464),('en','Enable Sign Up?','Enable Sign Up?',0,1456344464),('en','Enable Student Selection?','Enable Student Selection?',0,1463669309),('en','Enable the \'forgot password\' option to be showed to the user on login screen','Enable the \'forgot password\' option to be showed to the user on login screen',0,1456344463),('en','Enable the user to access the system through Facebook','Enable the user to access the system through Facebook',0,1456344463),('en','Enable the user to access the system through Google Plus','Enable the user to access the system through Google Plus',0,1456344464),('en','Enable the user to access the system through LinkedIn','Enable the user to access the system through LinkedIn',0,1456344464),('en','Enabled','Enabled',0,1456342038),('en','End Date','End Date',0,1463669111),('en','English Name','English Name',0,1456344025),('en','Enroll in another group','Enroll in another group',0,1464101110),('en','Enrolled Classes','Enrolled Classes',0,1456343782),('en','Enrolled Courses','Enrolled Courses',0,1456343782),('en','Enrollment','Enrollment',NULL,1456343720),('en','Enrollment Dates','Enrollment Dates',0,1464101110),('en','Enter your e-mail address below to reset your password.','Enter your e-mail address below to reset your password.',0,1456340940),('en','Entities using these attributions','Entities using these attributions',0,1464031584),('en','Entities using these roles','Entities using these roles',0,1456342037),('en','Environment','Environment',0,1463669094),('en','Especiy the start and final date for this rule be avaliable. If you don\'t specify the final date, its duration will be underterminate.','Especiy the start and final date for this rule be avaliable. If you don\'t specify the final date, its duration will be underterminate.',0,1464101110),('en','Este e-mail é gerada automaticamente e respondê-lo não é necessário.','Este e-mail é gerada automaticamente e respondê-lo não é necessário.',NULL,1464631434),('en','Event Creation','Event Creation',0,1456346843),('en','Event Details','Event Details',0,1456346843),('en','Event Source','Event Source',0,1456346843),('en','Event Type','Event Type',0,1456346843),('en','Events','Events',NULL,1456343720),('en','Exams:','Exams:',0,1456346843),('en','Exercises','Exercises',NULL,1456346843),('en','Expand / Collpase','Expand / Collpase',0,1463669309),('en','Expand All','Expand All',0,1463670130),('en','FALSE','FALSE',0,1456346843),('en','Facebook','Facebook',0,1456343955),('en','Fax:','Fax:',0,1456346843),('en','Field Name','Field Name',0,1464101134),('en','Fields','Fields',0,1464101134),('en','Filter Options','Filter Options',0,1456346843),('en','Finish Date','Finish Date',0,1464101110),('en','First Name','First Name',0,1456346856),('en','Fixed','Fixed',0,1464101110),('en','Forget your password?','Forget your password?',0,1456340939),('en','Forgot your password?','Forgot your password?',0,1465327495),('en','Friday','Friday',0,1464101110),('en','From','From',0,1464101110),('en','Full Name','Full Name',0,1456343954),('en','Full Screen','Full Screen',0,1456341204),('en','General','General',0,1456343782),('en','Global Link','Global Link',0,1465322795),('en','Goals','Goals',0,1464032481),('en','Grade','Grade',0,1456346843),('en','Grade Ranges','Grade Ranges',0,1464965793),('en','Grade Rule','Grade Rule',0,1464965714),('en','Grades','Grades',NULL,1456343720),('en','Group','Group',0,1456342037),('en','Group Behaviour','Group Behaviour',0,1464032382),('en','Group Included with success','Group Included with success',0,1456344235),('en','Group name template','Group name template',0,1464101110),('en','Group-Based','Group-Based',0,1464101110),('en','Grouping End Date','Grouping End Date',0,1464101134),('en','Grouping Name template','Grouping Name template',0,1464101110),('en','Grouping Options','Grouping Options',0,1464101110),('en','Grouping Start Date','Grouping Start Date',0,1464101134),('en','Grouping name','Grouping name',0,1464101134),('en','Groups','Groups',NULL,1456343720),('en','Help','Help',0,1456341204),('en','Here you can select the avaliable courses on this enroll package.','Here you can select the avaliable courses on this enroll package.',0,1464101134),('en','Home','Home',0,1456342036),('en','How many times the user can have the test?','How many times the user can have the test?',0,1464965714),('en','I confirm that I have read and accept the above terms','I confirm that I have read and accept the above terms',0,1456341204),('en','If clicking the URL above does not work, copy and paste the URL into a browser window.','If clicking the URL above does not work, copy and paste the URL into a browser window.',0,1465327921),('en','Import Lesson','Import Lesson',0,1463669813),('en','Individual','Individual',0,1464101110),('en','Info','Info',0,1456346843),('en','Insert a url','Insert a url',0,1463670130),('en','Insert your own URL, from youtube, s3, etc...','Insert your own URL, from youtube, s3, etc...',0,1463670130),('en','Installments','Installments',0,1463669197),('en','Instructor','Instructor',0,1456346843),('en','Instructors','Instructors',0,1456346843),('en','Interval Rules','Interval Rules',0,1464101110),('en','It allows the user to navigate through the test\'s questions','It allows the user to navigate through the test\'s questions',0,1464965714),('en','Knowledge Area','Knowledge Area',NULL,1465322795),('en','Label','Label',0,1464101135),('en','Label Name','Label Name',0,1464101135),('en','Language','Language',0,1456343783),('en','Languages','Languages',NULL,1456343720),('en','Last Name','Last Name',0,1456346856),('en','Left Side','Left Side',0,1465322794),('en','Lembre-se que a equipe do SysClass jamais solicita sua senha por e-mail. Esteja alerta para e-mails que solicitam informações sobre sua conta.','Lembre-se que a equipe do SysClass jamais solicita sua senha por e-mail. Esteja alerta para e-mails que solicitam informações sobre sua conta.',0,1465327921),('en','Lesson','Lesson',0,1463670129),('en','Lesson Exercises','Lesson Exercises',0,1456346843),('en','Lesson content created with success','Lesson content created with success',0,1463694811),('en','Lesson content removed with success','Lesson content removed with success',0,1464031758),('en','Lesson content updated with success','Lesson content updated with success',0,1465336874),('en','Lesson created with success','Lesson created with success',0,1463669836),('en','Lesson removed with success','Lesson removed with success',0,1464033528),('en','Lesson updated with success','Lesson updated with success',0,1463753738),('en','Lessons','Lessons',NULL,1456343720),('en','License Viewed','License Viewed',0,1456343729),('en','Loading','Loading',0,1456341205),('en','Local Name','Local Name',0,1456344025),('en','Local Time','Local Time',0,1456346843),('en','Lock Screen','Lock Screen',0,1456341204),('en','Lock System','Lock System',0,1456344463),('en','Lock System, preventing ordinary users to access (like a explicit maintenance mode)','Lock System, preventing ordinary users to access (like a explicit maintenance mode)',0,1456344463),('en','Log Out','Log Out',0,1456341204),('en','Log in details','Log in details',0,1456343783),('en','Login','Login',NULL,1456340938),('en','Login & Signup','Login & Signup',0,1456344463),('en','Login already exists','Login already exists',0,1456346681),('en','Login can be used','Login can be used',0,1456346681),('en','Login to your account','Login to your account',0,1456340937),('en','Logo','Logo',0,1456343954),('en','Manage Attributions Permissions','Manage Attributions Permissions',0,1464031584),('en','Manage Role Permissions','Manage Role Permissions',0,1456342037),('en','Manage attributions Users and Groups','Manage attributions Users and Groups',0,1464031584),('en','Manage enrolled users','Manage enrolled users',0,1464963500),('en','Manage role Users and Groups','Manage role Users and Groups',0,1456342038),('en','Manage the way student get into the system','Manage the way student get into the system',0,1463669110),('en','Manage your Attributions','Manage your Attributions',0,1464031584),('en','Manage your Departaments','Manage your Departaments',0,1456347058),('en','Manage your Lessons','Manage your Lessons',0,1463751972),('en','Manage your Programs','Manage your Programs',0,1463669192),('en','Manage your Question Database','Manage your Question Database',0,1464970340),('en','Manage your Roles','Manage your Roles',0,1456342036),('en','Manage your courses','Manage your courses',0,1464728637),('en','Manage your grades','Manage your grades',0,1464965786),('en','Manage your groups','Manage your groups',0,1464032378),('en','Manage your in page advertisings','Manage your in page advertisings',0,1464641730),('en','Manage your tests and exams','Manage your tests and exams',0,1464965494),('en','Manage your users','Manage your users',0,1456343728),('en','Manage yours Classes','Manage yours Classes',0,1463669871),('en','Mark as correct!','Mark as correct!',0,1465324096),('en','Materials','Materials',0,1456346843),('en','Maximum','Maximum',0,1464623775),('en','Maximum students','Maximum students',NULL,1464101110),('en','Me','Me',0,1456346843),('en','Menu','Menu',0,1456341204),('en','Message Body','Message Body',NULL,1456346843),('en','Monday','Monday',0,1464101110),('en','Month','Month',0,1464101110),('en','Month(s)','Month(s)',NULL,1463669197),('en','More','More',0,1456346843),('en','More Info','More Info',0,1464032481),('en','My Profile','My Profile',0,1456341204),('en','NO','NO',0,1456343519),('en','Name','Name',NULL,1456342038),('en','Needs Help!?','Needs Help!?',0,1464965793),('en','New Attribution','New Attribution',0,1464031584),('en','New Choice','New Choice',NULL,1463670130),('en','New Class','New Class',0,1463669813),('en','New Course','New Course',0,1463669192),('en','New Department','New Department',0,1456347058),('en','New Enrollment Guideline','New Enrollment Guideline',0,1463669110),('en','New Grade Rule','New Grade Rule',0,1464965787),('en','New Group','New Group',0,1464032378),('en','New Lesson','New Lesson',0,1463670129),('en','New Password','New Password',0,1456343783),('en','New Program','New Program',0,1463669196),('en','New Question','New Question',0,1464970340),('en','New Role','New Role',0,1456342036),('en','New Rule','New Rule',0,1464965793),('en','New Test','New Test',0,1464965494),('en','New Tests','New Tests',NULL,1456346843),('en','New User','New User',0,1456343729),('en','Next Class','Next Class',0,1456346843),('en','Next Course','Next Course',NULL,1456346843),('en','Next Lesson','Next Lesson',0,1456346843),('en','No','No',0,1456341204),('en','No Instructors defined','No Instructors defined',0,1463669309),('en','No file(s) found. Drag a file over this window or click below to add','No file(s) found. Drag a file over this window or click below to add',0,1456343955),('en','None','None',NULL,1456346843),('en','Not Classified','Not Classified',0,1456341204),('en','Number of Classes:','Number of Classes:',0,1456346843),('en','Nós recebemos seu pedido de inscrição e apenas precisamos confirmar o seu e-mail. Por favor, clique sobre a url abaixo para ativar sua conta','Nós recebemos seu pedido de inscrição e apenas precisamos confirmar o seu e-mail. Por favor, clique sobre a url abaixo para ativar sua conta',NULL,1464631434),('en','O código irá expirar dentro de seis horas. Após isso, você precisará reenviar o que pedido de redefinição de senha.','O código irá expirar dentro de seis horas. Após isso, você precisará reenviar o que pedido de redefinição de senha.',0,1465327921),('en','OFF','OFF',NULL,1456342037),('en','ON','ON',NULL,1456342037),('en','Objectives','Objectives',0,1464623775),('en','Objetives','Objetives',0,1464032481),('en','Obrigado pelo seu cadastro!','Obrigado pelo seu cadastro!',NULL,1464631434),('en','Observations','Observations',0,1456343954),('en','Office:','Office:',0,1456346843),('en','Offline','Offline',0,1464032481),('en','Online','Online',0,1464032481),('en','Open','Open',0,1456346843),('en','Open Period','Open Period',0,1464101110),('en','Open a Ticket','Open a Ticket',0,1456346843),('en','Open ticket(s)','Open ticket(s)',0,1456346843),('en','Ops! Sorry, any data found!','Ops! Sorry, any data found!',0,1456346843),('en','Ops! There\'s any content for this lesson','Ops! There\'s any content for this lesson',0,1456346843),('en','Ops! There\'s any courses registered for this course','Ops! There\'s any courses registered for this course',0,1456346843),('en','Ops! There\'s any exercises registered for this course','Ops! There\'s any exercises registered for this course',0,1464032481),('en','Ops! There\'s any info registered for this program','Ops! There\'s any info registered for this program',0,1456346843),('en','Ops! There\'s any materials registered for this course','Ops! There\'s any materials registered for this course',NULL,1456346843),('en','Ops! There\'s no data registered for this course','Ops! There\'s no data registered for this course',0,1456346843),('en','Options','Options',NULL,1456346843),('en','Organization','Organization',NULL,1456343720),('en','Organizations','Organizations',0,1456343953),('en','Original Language','Original Language',0,1463670130),('en','Overview','Overview',0,1456346856),('en','Papers:','Papers:',0,1456346843),('en','Password','Password',NULL,1456340938),('en','Password updated with success!','Password updated with success!',0,1464638735),('en','Password updated with success! Please enter you login details below.','Password updated with success! Please enter you login details below.',0,1465327997),('en','Payments','Payments',0,1456341203),('en','Pending','Pending',0,1456346843),('en','Pending aproval','Pending aproval',0,1456343729),('en','Period','Period',0,1464623775),('en','Period name','Period name',0,1463669309),('en','Personal info','Personal info',0,1456346856),('en','Phone Number','Phone Number',NULL,1456343954),('en','Placement','Placement',0,1464641730),('en','Please Select','Please Select',0,1456342038),('en','Please pick another username','Please pick another username',0,1456346681),('en','Please select the size','Please select the size',0,1463669094),('en','Please type login and password below','Please type login and password below',0,1464638712),('en','Position:','Position:',0,1456346843),('en','Prerequisite(s):','Prerequisite(s):',0,1456346843),('en','Previous Class','Previous Class',0,1456346843),('en','Previous Course','Previous Course',NULL,1456346843),('en','Previous Lesson','Previous Lesson',0,1456346843),('en','Price','Price',0,1463669197),('en','Primary Group','Primary Group',NULL,1456343783),('en','Primary Language','Primary Language',0,1456343783),('en','Privacity Settings','Privacity Settings',0,1456346856),('en','Professor Files','Professor Files',0,1456346843),('en','Profile Picture','Profile Picture',0,1456346856),('en','Program','Program',NULL,1456346843),('en','Programs','Programs',NULL,1456346843),('en','Progress','Progress',NULL,1456346843),('en','Provides multiple roadmaps based on course enrollment dates','Provides multiple roadmaps based on course enrollment dates',NULL,1463669309),('en','Public Signed Users must be approved?','Public Signed Users must be approved?',0,1456344464),('en','Put your content here','Put your content here',0,1463670130),('en','Put your description here...','Put your description here...',0,1456346843),('en','Put your observations here...','Put your observations here...',0,1456343954),('en','Put your question here...','Put your question here...',0,1463670130),('en','Question','Question',NULL,1456346843),('en','Question Type','Question Type',0,1463670130),('en','Questions','Questions',NULL,1456343720),('en','Questions Database','Questions Database',NULL,1463670130),('en','RTL','RTL',0,1456344025),('en','Randomize all alternatives from simple and multiple choice questions','Randomize all alternatives from simple and multiple choice questions',0,1464965714),('en','Randomize the order of questions?','Randomize the order of questions?',0,1464965714),('en','Re-type New Password','Re-type New Password',0,1456343783),('en','Record cannot be created because it already exists','Record cannot be created because it already exists',0,1464728698),('en','Remember Me','Remember Me',0,1456340938),('en','Removed with success','Removed with success',0,1464121502),('en','Repetition Limit','Repetition Limit',0,1456346843),('en','Required','Required',0,1456346843),('en','Required Equipment:','Required Equipment:',0,1456346843),('en','Reset my Pass','Reset my Pass',0,1464638712),('en','Review translation','Review translation',0,1456344025),('en','Right Side','Right Side',0,1465322794),('en','Road Map','Road Map',0,1456346843),('en','Roadmap for Course Grouping: ','Roadmap for Course Grouping: ',0,1463669309),('en','Role added to resource with success','Role added to resource with success',0,1456343584),('en','Roles','Roles',0,1456341904),('en','Saturday','Saturday',0,1464101110),('en','Save','Save',0,1463669309),('en','Save Changes','Save Changes',0,1456342037),('en','Save and Add another Lesson','Save and Add another Lesson',0,1463670130),('en','Saving','Saving',NULL,1463670130),('en','School Unit:','School Unit:',0,1456346843),('en','Se a link acima não estiver funcionando, copie e cola a url acima em seu navegador.','Se a link acima não estiver funcionando, copie e cola a url acima em seu navegador.',NULL,1464631434),('en','Se você ainda está tendo dificuldade para recuperar sua senha, favor entrar em contato conosco pelo site: ','Se você ainda está tendo dificuldade para recuperar sua senha, favor entrar em contato conosco pelo site: ',0,1465327921),('en','Search Lessons','Search Lessons',0,1456346843),('en','Search for Group or User','Search for Group or User',0,1456342038),('en','Search for Users','Search for Users',0,1463669094),('en','Search for a course','Search for a course',0,1464101134),('en','Search for new Course','Search for new Course',0,1456343783),('en','See your profile info, change your password and more.','See your profile info, change your password and more.',0,1456346856),('en','Segue abaixo o link para de redefinição de sua senha.','Segue abaixo o link para de redefinição de sua senha.',0,1465327921),('en','Select','Select',0,1463669094),('en','Select Class','Select Class',0,1463670130),('en','Select Grade Rule','Select Grade Rule',0,1464965714),('en','Select User','Select User',0,1463669094),('en','Select a Period','Select a Period',NULL,1463669197),('en','Select a action','Select a action',NULL,1464101110),('en','Select a course','Select a course',0,1456346843),('en','Select a group','Select a group',0,1456343783),('en','Select...','Select...',NULL,1456346856),('en','Send','Send',NULL,1456346843),('en','Send Message','Send Message',0,1456346843),('en','Send To','Send To',NULL,1456346843),('en','Send to the waiting list','Send to the waiting list',0,1464101110),('en','Set Resolution','Set Resolution',NULL,1463669094),('en','Set logo image','Set logo image',0,1464964839),('en','Settings','Settings',NULL,1456343720),('en','Show correct answers?','Show correct answers?',0,1464965714),('en','Show question Difficulty?','Show question Difficulty?',0,1464965714),('en','Show question type?','Show question type?',0,1464965714),('en','Show question weight?','Show question weight?',0,1464965714),('en','Show questions one by one?','Show questions one by one?',0,1464965714),('en','Show the questions in a randomized order','Show the questions in a randomized order',0,1464965714),('en','Show the user the difficulty of the question during the test','Show the user the difficulty of the question during the test',0,1464965714),('en','Show the user the type of the question during the test','Show the user the type of the question during the test',0,1464965714),('en','Show the user the weight of the question during the test','Show the user the weight of the question during the test',0,1464965714),('en','Shows the correct answer after user response. This feature will block the question after the user response.','Shows the correct answer after user response. This feature will block the question after the user response.',0,1464965714),('en','Shuffle questions alternatives?','Shuffle questions alternatives?',0,1464965714),('en','Sign Up Aprroval?','Sign Up Aprroval?',0,1456344464),('en','Sizes:','Sizes:',0,1463669094),('en','Social Info','Social Info',0,1456343954),('en','Sorry! Any data has been registered for this class yet.','Sorry! Any data has been registered for this class yet.',0,1456346843),('en','Stand By','Stand By',0,1456346843),('en','Start Chat','Start Chat',0,1456346843),('en','Start Date','Start Date',0,1463669111),('en','State','State',NULL,1456343955),('en','Status','Status',0,1456346843),('en','Street','Street',0,1464031501),('en','Street Number','Street Number',NULL,1464031501),('en','Student','Student',0,1463669094),('en','Student Limit','Student Limit',0,1464101110),('en','Students','Students',0,1463669309),('en','Students Goals','Students Goals',0,1464623775),('en','Subject','Subject',NULL,1456346843),('en','Submit','Submit',0,1456340941),('en','Suggested','Suggested',0,1456346843),('en','Summary','Summary',0,1464639579),('en','Sunday','Sunday',0,1464101110),('en','Support Requests','Support Requests',0,1463669094),('en','Surname','Surname',0,1456343729),('en','System Settings','System Settings',0,1456344093),('en','System Subtitle','System Subtitle',NULL,1456344463),('en','System Title','System Title',NULL,1456344463),('en','System-wide Title','System-wide Title',0,1456344463),('en','System-wide subtitle','System-wide subtitle',0,1456344463),('en','TRUE','TRUE',0,1456346843),('en','Talk about you..','Talk about you..',0,1456346856),('en','Talk to us','Talk to us',0,1456346843),('en','Telephone:','Telephone:',0,1456346843),('en','Test Info','Test Info',0,1456346843),('en','Test Overview','Test Overview',0,1456346843),('en','Test Repetition Times','Test Repetition Times',0,1464965714),('en','Test Settings','Test Settings',0,1464965714),('en','Tests','Tests',NULL,1456343720),('en','Tests:','Tests:',0,1456346843),('en','Thank you for joining %s!','Thank you for joining %s!',0,1465327921),('en','The answer is...','The answer is...',0,1456346843),('en','The goals to be achieved by the students','The goals to be achieved by the students',0,1464623775),('en','The objective to be achieved by this program','The objective to be achieved by this program',0,1464623775),('en','The passwords doesn\'t match!','The passwords doesn\'t match!',0,1464638712),('en','The system can\'t authenticate you using the current methods. Please came back in a while.','The system can\'t authenticate you using the current methods. Please came back in a while.',0,1458836526),('en','The system can\'t found the provided e-mail address.','The system can\'t found the provided e-mail address.',0,1465327511),('en','The system can\'t locate this account. Please use the form below.','The system can\'t locate this account. Please use the form below.',0,1458836521),('en','This filed will be used to create ','This filed will be used to create ',0,1464101110),('en','This ia a system generated email and reply is not required.','This ia a system generated email and reply is not required.',0,1465327921),('en','This is correct!','This is correct!',0,1463670130),('en','Thursday','Thursday',0,1464101110),('en','Time Limit','Time Limit',0,1464965714),('en','Time limit','Time limit',0,1456346843),('en','Time limit in minutes?','Time limit in minutes?',0,1464965714),('en','Times done','Times done',0,1456346843),('en','Timezone','Timezone',0,1456346856),('en','Title','Title',NULL,1463670130),('en','To','To',0,1464101110),('en','Toogle Active','Toogle Active',NULL,1463669309),('en','Total Lessons','Total Lessons',0,1463669309),('en','Total Price','Total Price',0,1464623775),('en','Total Questions','Total Questions',0,1456346843),('en','Total time in minutes available for the test execution. Leave 0 (zero) for unlimited time.','Total time in minutes available for the test execution. Leave 0 (zero) for unlimited time.',0,1464965714),('en','Translate','Translate',0,1463670130),('en','Tuesday','Tuesday',0,1464101110),('en','Type','Type',0,1456342038),('en','Type your message here...','Type your message here...',0,1456346843),('en','Type your question, and where you need to insert a blank, enter the following sequence','Type your question, and where you need to insert a blank, enter the following sequence',0,1463670130),('en','URL for Business Card:','URL for Business Card:',0,1456346843),('en','Unit','Unit',0,1456346843),('en','Units','Units',NULL,1456346843),('en','Units Completed','Units Completed',0,1464032481),('en','Updated with success','Updated with success',0,1456343929),('en','Url','Url',NULL,1464118556),('en','User / Group','User / Group',0,1456342038),('en','User Included with success','User Included with success',0,1456347011),('en','User Since','User Since',0,1463669309),('en','User Type','User Type',0,1463669309),('en','User added to course with success','User added to course with success',0,1463669341),('en','User added to group with success','User added to group with success',0,1464032412),('en','User removed with success','User removed with success',0,1456347019),('en','Username and password are incorrect. Please make sure you typed correctly.','Username and password are incorrect. Please make sure you typed correctly.',0,1456340937),('en','Users','Users',0,1456341204),('en','Users Groups','Users Groups',0,1464032378),('en','Video','Video',0,1456346843),('en','View','View',0,1456344024),('en','View Map','View Map',0,1456346843),('en','View Type','View Type',0,1464641730),('en','View and Edit system configuration','View and Edit system configuration',0,1456344094),('en','View system languages','View system languages',0,1456344024),('en','Viewed','Viewed',NULL,1456346843),('en','We\'ll send you an e-mail containing a link to reset your password. Please check your inbox.','We\'ll send you an e-mail containing a link to reset your password. Please check your inbox.',0,1465327636),('en','Website','Website',0,1456343955),('en','Wednesday','Wednesday',0,1464101110),('en','Week','Week',0,1464101110),('en','Week(s)','Week(s)',NULL,1463669197),('en','YES','YES',0,1456343519),('en','Year','Year',0,1464101110),('en','Year(s)','Year(s)',NULL,1463669197),('en','Yes','Yes',0,1456341204),('en','You agreed within the license. Thanks for using Sysclass','You agreed within the license. Thanks for using Sysclass',0,1456341678),('en','You can crop your picture, if you wish','You can crop your picture, if you wish',0,1463669094),('en','You can define the criterias for user admitance. This information is used to make course grouping control, and the calculate the course desired roadmap.','You can define the criterias for user admitance. This information is used to make course grouping control, and the calculate the course desired roadmap.',0,1464101110),('en','You can select a customized rule to show yours students grades in your prefered way. If you do not choose, the grades will be showed in the [0-100] standard','You can select a customized rule to show yours students grades in your prefered way. If you do not choose, the grades will be showed in the [0-100] standard',0,1464965714),('en','You can select one of two ways to define the grouping creation. A fixed way, when you manualy dwefined the dates for admittance, or the dynamic way, when you set the parameters for the system automatically create the grouping.','You can select one of two ways to define the grouping creation. A fixed way, when you manualy dwefined the dates for admittance, or the dynamic way, when you set the parameters for the system automatically create the grouping.',0,1464101110),('en','You can set the maximum number of students allowed to enter in a grouping. If you don\'t want to limit, set this field to \'0\'.','You can set the maximum number of students allowed to enter in a grouping. If you don\'t want to limit, set this field to \'0\'.',0,1464101110),('en','You have been logout sucessfully. Thanks for using Sysclass.','You have been logout sucessfully. Thanks for using Sysclass.',0,1456343752),('en','You\'re in:','You\'re in:',NULL,1456346843),('en','Your Courses','Your Courses',NULL,1456346856),('en','Your Files','Your Files',0,1456346843),('en','Your Location','Your Location',0,1456346856),('en','Your Profile','Your Profile',0,1456346856),('en','Your Support Requests','Your Support Requests',0,1463669094),('en','Your curriculum overview','Your curriculum overview',0,1464639579),('en','Your session appers to be expired. Please provide your credentials.','Your session appers to be expired. Please provide your credentials.',0,1456340942),('en','Zipcode','Zipcode',NULL,1456343954),('en','active','active',0,1465322795),('en','classes selected','classes selected',0,1456346843),('en','completed','completed',NULL,1456346843),('en','email is required','email is required',0,1464728511),('en','end_date is required','end_date is required',0,1464101128),('en','enroll_id is required','enroll_id is required',0,1464099876),('en','files','files',NULL,1456346843),('en','here','here',0,1465327495),('en','points','points',0,1456346843),('en','to reset your password','to reset your password',0,1465327495),('en','total classes','total classes',0,1456346843),('es','Back','Atrás',0,1457606649),('es','Click to access','Haga clic en acceso',0,1457606647),('es','Create an account','Crear una cuenta',0,1457606648),('es','Don\'t have an account?','¿No tienes una cuenta?',0,1457606647),('es','Email','Correo electrónico',NULL,1457606649),('es','Enter your e-mail address below to reset your password.','Introduzca su dirección de correo electrónico para restablecer tu contraseña.',0,1457606648),('es','Forget your password?','¿Olvidó su contraseña?',0,1457606648),('es','Login','Inicio de sesión',NULL,1457606646),('es','Login to your account','Entra a tu cuenta',0,1457606645),('es','Password','Contraseña',NULL,1457606646),('es','Remember Me','Acuérdate de mí',0,1457606646),('es','Submit','Enviar',0,1457606650),('es','The system can\'t authenticate you using the current methods. Please came back in a while.','El sistema no puede autenticar usando los métodos actuales. Por favor, volvió en un rato.',0,1457606700),('es','Your session appers to be expired. Please provide your credentials.','Su período de sesiones aparece para ser vencido. Por favor proporcione sus credenciales.',0,1457606656),('pt','#','#',NULL,1456346929),('pt','# Questions','# Perguntas',NULL,1456346930),('pt','% Complete','% Completa',0,1456346886),('pt','A problem ocurred when tried to save you data. Please try again.','Um problema ocorreu quando tentei te salvar dados. Por favor, tente novamente.',0,1464640173),('pt','About You','Sobre você',0,1456346882),('pt','Account','Conta',0,1456346875),('pt','Action if the maximum is reached','Ação, se o máximo for alcançado',0,1464790124),('pt','Actions','Ações',NULL,1456347071),('pt','Active','Ativo',0,1456347070),('pt','Add Field','Adicionar campo',0,1464790127),('pt','Add File','Adicionar arquivo',0,1465308067),('pt','Add HTML','Adicionar HTML',0,1465308067),('pt','Add Language','Adicionar idioma',NULL,1456347068),('pt','Add a new Course Grouping','Adicionar um novo agrupamento de curso',0,1465245661),('pt','Add/Change file','Adicionar/alterar arquivo',0,1456346883),('pt','Address','Endereço',0,1464639593),('pt','Address Book','Livro de endereços',0,1456411665),('pt','Address Line 1','Linha de endereço 1',NULL,1456411670),('pt','Address Line 2','Linha de endereço 2',NULL,1456411671),('pt','Administation','Administation',0,1456346871),('pt','Administrator','Administrador',0,1456347054),('pt','Admittance Type','Tipo de admissão',0,1464790106),('pt','Advertising','Publicidade',0,1456347053),('pt','All','Todos os',0,1456346948),('pt','Allow anonimous users to create an account','Permitir que anônimos usuários criar uma conta',0,1456411043),('pt','Allow the user to select the the classes order','Permitir que o usuário selecione a ordem de classes',0,1465245659),('pt','Announcements','Anúncios',0,1456346891),('pt','Are you sure?','Tem certeza?',NULL,1456346888),('pt','Assign to Another User','Atribuir a outro usuário',NULL,1463668926),('pt','Assign to me','Atribuir a mim',NULL,1463668925),('pt','Attach Files','Anexar arquivos',0,1456346901),('pt','Attendee','Participante',0,1456346946),('pt','Attendence','Atendimento',NULL,1456346915),('pt','Attributions','Atribuições',NULL,1463668921),('pt','Author','Autor',0,1456346923),('pt','Back','Voltar',0,1456345938),('pt','Banner Size','Tamanho do banner',0,1465308065),('pt','Banners','Bandeiras',0,1465308064),('pt','Bibliography','Bibliografia',NULL,1456346915),('pt','Birthday','Aniversário',0,1456346880),('pt','Block Admittance','Admissão de bloco',0,1464790125),('pt','Book','Livro',0,1456346922),('pt','Books:','Livros:',0,1456346934),('pt','Calendar','Calendário',0,1456346891),('pt','Campus:','Campus:',0,1456346936),('pt','Can messages be sent to this group?','As mensagens podem ser enviadas a este grupo?',0,1464640092),('pt','Cancel','Cancelar',0,1456346895),('pt','Certificates','Certificados',0,1463669005),('pt','Change Password','Alterar senha',0,1456346877),('pt','Change file','Alteração de arquivo',0,1463669007),('pt','Chat','Bate-papo',0,1464639593),('pt','Chat not avaliable','Bate-papo não disponível',0,1456346946),('pt','City','Cidade',NULL,1456411671),('pt','Class','Classe',0,1464895990),('pt','Classes','Classes',0,1456346902),('pt','Classes Disponible','Disponible de classes',0,1465245661),('pt','Click','Clique em',0,1465482290),('pt','Click here to move content','Clique aqui para mover conteúdo',NULL,1465308069),('pt','Click to access','Clique para acesso',0,1456345935),('pt','Close','Fechar',NULL,1456346952),('pt','Closed','Fechado',0,1456346919),('pt','Code','Código',0,1456347069),('pt','Collapse All','Recolher tudo',0,1465308068),('pt','Communication','Comunicação',0,1456346871),('pt','Completed','Concluído',NULL,1456346916),('pt','Config and edit group info','Config e editar informação de grupo',0,1464640091),('pt','Confirm','Confirmar',NULL,1464632615),('pt','Content','Conteúdo',0,1456346870),('pt','Coordinator','Coordenador',0,1464632649),('pt','Country','País',0,1456347069),('pt','Course','Curso',NULL,1456346925),('pt','Course Duration','Duração do curso',0,1465245651),('pt','Course Format','Formato do curso',0,1464790114),('pt','Course Grouping','Agrupamento de curso',0,1464790111),('pt','Course Objectives','Objetivo do curso',0,1465245657),('pt','Course Periods','Períodos de curso',0,1465245662),('pt','Course Prices','Preços do curso',0,1465245653),('pt','Courses','Cursos',NULL,1456346910),('pt','Create Attribution','Criar a atribuição',0,1463668952),('pt','Create Block','Criar bloco',0,1465245661),('pt','Create Class','Criar a classe',0,1465245662),('pt','Create Period','Criar o período',NULL,1464790127),('pt','Create an account','Criar uma conta',0,1456345936),('pt','Created with success','Criado com sucesso',0,1465308097),('pt','Credit Hours:','Horas de crédito:',0,1456346932),('pt','Crop Image','Cortar imagem',0,1456346873),('pt','Current Password','Senha atual',0,1456346883),('pt','Curriculum','Currículo',0,1464639593),('pt','Date','Data',0,1456346950),('pt','Department','Departamento',NULL,1465245656),('pt','Departments','Departamentos',0,1456347051),('pt','Description','Descrição',NULL,1456346949),('pt','Destination','Destino',0,1456347077),('pt','Details','Detalhes',0,1456346940),('pt','District','Distrito',NULL,1464639595),('pt','Division/Portifolio:','Divisão/Portifolio:',0,1456346935),('pt','Do it again!','Faz de novo!',0,1456346942),('pt','Do now!','Faça agora!',NULL,1456346942),('pt','Do you really want to remove this conversation?','Você realmente quer remover esta conversa?',0,1463668925),('pt','Docs In Box','Documentos na caixa',0,1456346945),('pt','Docs Pending','Documentos pendentes',0,1456346945),('pt','Don\'t have an account?','Não tem uma conta?',0,1456345935),('pt','Done','Feito',0,1456346941),('pt','Download','Baixar',NULL,1456346943),('pt','Drag to reposition item','Arraste para reposicionar o item',0,1464790127),('pt','Drop Box','Arquivos',1,1456347173),('pt','Dropbox','Dropbox',0,1456346913),('pt','During this course you will...','Durante este curso você será...',0,1456346931),('pt','Dynamic','Dinâmica',0,1464790117),('pt','Edit Advertising','Editar publicidade',0,1465308063),('pt','Edit Enrollment Guideline','Editar inscrição diretriz',0,1464790110),('pt','Edit Enrollment Guidelines','Editar inscrição orientações',0,1464790110),('pt','Edit Group','Editar grupo',0,1464640091),('pt','Edit Organization','Editar a organização',0,1456411665),('pt','Edit Program','Editar o programa',0,1465245651),('pt','Edit Token','Editar o Token',0,1456347078),('pt','Edit a advertising item','Editar um item de publicidade',0,1465308063),('pt','Edit your Organization','Editar a sua organização',0,1456411664),('pt','Edit your program info','Editar sua informação de programa',0,1465245650),('pt','Email','Email',NULL,1456345937),('pt','Email Address','Endereço de e-mail',NULL,1456346880),('pt','Email:','Email:',0,1456346937),('pt','Emails','E-mails',0,1456346868),('pt','Enable Course Groupings','Habilitar agrupamentos de curso',0,1465245660),('pt','Enable Course Periods','Habilitar os períodos do curso',0,1465245660),('pt','Enable Facebook Login?','Habilitar o Login do Facebook?',0,1456411037),('pt','Enable Forgot Form','Esqueceu de habilitar formulário',0,1456411038),('pt','Enable Google+ Login?','Habilitar o Google + Login?',0,1456411039),('pt','Enable LinkedIn Login?','Habilitar o LinkedIn Login?',0,1456411041),('pt','Enable Sign Up?','Ativar o sinal acima?',0,1456411042),('pt','Enable Student Selection?','Habilitar a seleção de estudante?',0,1465245659),('pt','Enable the \'forgot password\' option to be showed to the user on login screen','Habilite a opção \'esqueci a senha\' para ser mostrado ao usuário na tela de login',0,1456411039),('pt','Enable the user to access the system through Facebook','Permitir que o usuário acessar o sistema através do Facebook',0,1456411038),('pt','Enable the user to access the system through Google Plus','Permitir que o usuário acessar o sistema através do Google Plus',0,1456411040),('pt','Enable the user to access the system through LinkedIn','Permitir que o usuário acessar o sistema através do LinkedIn',0,1456411042),('pt','Enabled','Habilitado',0,1463668953),('pt','End Date','Data de término',0,1464790106),('pt','English Name','Nome em inglês',0,1456347069),('pt','Enroll in another group','Inscrever-se no outro grupo',0,1464790125),('pt','Enrollment','Inscrição',0,1456347053),('pt','Enrollment Dates','Datas de inscrição',0,1464790116),('pt','Enter your e-mail address below to reset your password.','Digite seu endereço de e-mail abaixo para redefinir sua senha.',0,1456345936),('pt','Entities using these attributions','Usando estas atribuições de entidades',0,1463668952),('pt','Environment','Meio ambiente',0,1456347054),('pt','Especiy the start and final date for this rule be avaliable. If you don\'t specify the final date, its duration will be underterminate.','Especiy o início e a data final para esta regra ser disponível. Se você não especificar a data final, sua duração será underterminate.',0,1464790114),('pt','Event Creation','Criação do evento',0,1456346949),('pt','Event Details','Detalhes do evento',0,1456346949),('pt','Event Source','Origem do evento',0,1456346948),('pt','Event Type','Tipo de evento',0,1456346950),('pt','Events','Eventos',0,1456346865),('pt','Exams:','Exames:',0,1456346933),('pt','Exercises','Exercícios',NULL,1456346928),('pt','Expand / Collpase','Expandir / Collpase',0,1464790127),('pt','Expand All','Expandir todos os',0,1465308067),('pt','FALSE','FALSO',0,1456346897),('pt','Facebook','Facebook',0,1456411673),('pt','Fax:','Fax:',0,1456346937),('pt','Field Name','Nome do campo',0,1464790128),('pt','Fields','Campos',0,1464790112),('pt','Filter Options','Opções de filtro',0,1456346947),('pt','Finish Date','Data de término',0,1464790114),('pt','First Name','Primeiro nome',0,1456346878),('pt','Fixed','Fixo',0,1464790117),('pt','Forget your password?','Esqueceu sua senha?',NULL,1465506285),('pt','Forgot your password?','Esqueceu sua senha?',0,1465482290),('pt','Friday','Sexta-feira',0,1464790122),('pt','From','De',0,1464790129),('pt','Full Name','Nome completo',0,1456411666),('pt','Full Screen','Tela cheia',0,1456346874),('pt','General','Geral',0,1456411032),('pt','Global Link','Global Link',0,1465308066),('pt','Goals','Objetivos',NULL,1465506294),('pt','Grade','Grau',0,1456346918),('pt','Grades','Notas',0,1456347051),('pt','Group','Grupo',0,1463668953),('pt','Group Behaviour','Comportamento do grupo',0,1464640092),('pt','Group name template','Modelo de nome de grupo',0,1464790118),('pt','Group-Based','Com base em grupo',0,1464790115),('pt','Grouping End Date','Data de término do agrupamento',0,1464790112),('pt','Grouping Name template','Modelo do nome do agrupamento',0,1464790118),('pt','Grouping Options','Opções de agrupamento',0,1464790112),('pt','Grouping Start Date','Data de início do agrupamento',0,1464790112),('pt','Grouping name','Nome do agrupamento',0,1464790111),('pt','Groups','Grupos de',0,1456347052),('pt','Help','Ajuda',NULL,1456346876),('pt','Here you can select the avaliable courses on this enroll package.','Aqui você pode selecionar os cursos disponíveis neste pacote de registrar.',0,1464790126),('pt','Home','Casa',0,1456346873),('pt','Individual','Indivíduo',0,1464790115),('pt','Info','Informação',0,1456346913),('pt','Installments','Parcelas',NULL,1465245654),('pt','Instructor','Instrutor',0,1456346913),('pt','Instructors','Instrutores',0,1456346893),('pt','Interval Rules','Regras de intervalo',0,1464790119),('pt','Knowledge Area','Área de conhecimento',NULL,1465308066),('pt','Label','Rótulo',0,1464790129),('pt','Label Name','Nome da etiqueta',0,1464790128),('pt','Language','Língua',0,1456346881),('pt','Languages','Idiomas',0,1456347050),('pt','Last Name','Último nome',0,1456346879),('pt','Left Side','Lado esquerdo',0,1465308062),('pt','Lesson Exercises','Exercícios da lição',0,1456346894),('pt','Lessons','Lições',0,1456346902),('pt','License Viewed','Licença vista',0,1464639738),('pt','Loading','A carregar',0,1456346890),('pt','Local Name','Nome local',0,1456347070),('pt','Local Time','Hora local',0,1456346947),('pt','Lock Screen','Tela do fechamento',0,1456346874),('pt','Lock System','Sistema de bloqueio',0,1456411033),('pt','Lock System, preventing ordinary users to access (like a explicit maintenance mode)','Sistema de fechamento, impedindo que os usuários comuns de acesso (como um modo de manutenção explícita)',0,1456411034),('pt','Log Out','Efetuar logout',0,1456346875),('pt','Login','Login',NULL,1456345933),('pt','Login & Signup','Login & Signup',0,1456411032),('pt','Login to your account','Inicie sessão na sua conta',0,1456345933),('pt','Logo','Logotipo',0,1456411668),('pt','Manage Attributions Permissions','Gerenciar permissões de atribuições',0,1463668953),('pt','Manage attributions Users and Groups','Gerenciar atribuições usuários e grupos',0,1463668954),('pt','Manage enrolled users','Gerenciar usuários inscritos',0,1464790405),('pt','Manage the way student get into the system','Gerenciar o aluno de maneira entrar no sistema',0,1464790104),('pt','Manage your Attributions','Gerenciar suas atribuições',0,1463668951),('pt','Manage your Programs','Gerenciar seus programas',0,1465245638),('pt','Manage your groups','Gerenciar seus grupos',0,1463668989),('pt','Manage your in page advertisings','Gerenciar sua página em propagandas',0,1456347219),('pt','Manage your tests and exams','Gerenciar os testes e exames',0,1464895989),('pt','Manage your users','Gerenciar seus usuários',0,1464639737),('pt','Materials','Materiais',0,1456346927),('pt','Maximum','Máximo',0,1465245652),('pt','Maximum students','Máximos alunos',NULL,1464790124),('pt','Me','Me',0,1456346951),('pt','Menu','Menu de',0,1456346872),('pt','Message Body','Corpo da mensagem',NULL,1456346900),('pt','Monday','Segunda-feira',0,1464790121),('pt','Month','Mês',0,1464790119),('pt','Month(s)','Mês (es)',NULL,1465245656),('pt','More','Mais',0,1456346915),('pt','More Info','Mais informação',0,1464632649),('pt','My Profile','Meu perfil',0,1456346874),('pt','NO','NÃO',NULL,1456411044),('pt','Name','Nome',0,1456346878),('pt','New Attribution','Nova atribuição',0,1463668951),('pt','New Course','Novo curso',0,1465245639),('pt','New Enrollment Guideline','Nova diretriz de inscrição',0,1464790105),('pt','New Group','Novo grupo',0,1463668990),('pt','New Password','Nova senha',0,1456346884),('pt','New Test','Novo teste',0,1464895990),('pt','New Tests','Novos testes',0,1456346870),('pt','New User','Novo usuário',0,1456346873),('pt','Next Class','Próxima aula',0,1456346912),('pt','Next Course','Próximo curso',NULL,1456346909),('pt','Next Lesson','Próxima lição',0,1456346926),('pt','No','Não',NULL,1456346890),('pt','No Instructors defined','Não há instrutores definidos',0,1464790130),('pt','No file(s) found. Drag a file over this window or click below to add','Nenhum arquivo encontrado. Arraste um arquivo sobre esta janela ou clique abaixo para adicionar',0,1456346886),('pt','None','Nenhum',NULL,1456346935),('pt','Not Classified','Não classificados',0,1456346872),('pt','Number of Classes:','Número de Classes:',0,1456346932),('pt','OFF','FORA',0,1456411666),('pt','ON','DIANTE',0,1456411666),('pt','Objectives','Objectivos',0,1465245657),('pt','Objetives','Objetivos',0,1464632650),('pt','Observations','Observações',0,1456411667),('pt','Office:','Escritório:',0,1456346936),('pt','Offline','Off-line',0,1464632651),('pt','Online','On-line',0,1464632651),('pt','Open','Aberto',0,1456346919),('pt','Open Period','Período aberto',0,1464790113),('pt','Open a Ticket','Abra um Ticket',0,1456346944),('pt','Open ticket(s)','Bilhete (s) aberto',0,1456346945),('pt','Ops! Sorry, any data found!','Ops! Desculpe, todos os dados!',0,1456346944),('pt','Ops! There\'s any content for this lesson','Ops! Não há qualquer conteúdo para esta lição',0,1456346940),('pt','Ops! There\'s any courses registered for this course','Ops! Existe algum curso registrado para este curso',0,1456346931),('pt','Ops! There\'s any exercises registered for this course','Ops! Não há qualquer exercícios registrados para este curso',0,1464632650),('pt','Ops! There\'s any info registered for this program','Ops! Não há qualquer informação registrada para este programa',0,1456346910),('pt','Ops! There\'s any materials registered for this course','Ops! Não há qualquer material registrado para este curso',NULL,1456346941),('pt','Ops! There\'s no data registered for this course','Ops! Não há dados registrados para este curso',0,1456346939),('pt','Options','Opções',NULL,1456346930),('pt','Organization','Organização',0,1456347050),('pt','Organizations','Organizações',0,1456411664),('pt','Overview','Visão geral',0,1456346875),('pt','Papers:','Documentos:',0,1456346933),('pt','Password','Senha',NULL,1456345934),('pt','Password updated with success!','Senha atualizada com sucesso!',0,1464632646),('pt','Payments','Pagamentos',0,1456346869),('pt','Pending','Pendente',0,1456346941),('pt','Pending aproval','Aprovação pendente',0,1464639738),('pt','Period','Período',0,1465245655),('pt','Period name','Nome do período',0,1465245663),('pt','Personal info','Informação pessoal',0,1456346876),('pt','Phone Number','Número de telefone',NULL,1456411668),('pt','Placement','Colocação',0,1456347219),('pt','Please Select','Por favor selecione',NULL,1463668922),('pt','Please select the size','Por favor, selecione o tamanho',0,1463669007),('pt','Please type login and password below','Por favor digite o login e senha abaixo',0,1464632614),('pt','Position:','Posição:',0,1456346935),('pt','Prerequisite(s):','Prerequisite(s):',0,1456346931),('pt','Previous Class','Classe anterior',0,1456346912),('pt','Previous Course','Curso anterior',NULL,1456346909),('pt','Previous Lesson','Lição anterior',0,1456346926),('pt','Price','Preço',0,1465245654),('pt','Privacity Settings','Configurações de privacidade',0,1456346878),('pt','Professor Files','Arquivos do professor',0,1456346920),('pt','Profile Picture','Foto do perfil',0,1456346877),('pt','Program','Programa',NULL,1456346911),('pt','Programs','Programas',NULL,1456346905),('pt','Progress','Progresso',0,1456346866),('pt','Provides multiple roadmaps based on course enrollment dates','Fornece vários roteiros com base nas datas de inscrição do curso',NULL,1465245660),('pt','Public Signed Users must be approved?','Público assinado usuários deve ser aprovados?',0,1456411045),('pt','Put your content here','Colocar o seu conteúdo aqui',0,1465308069),('pt','Put your description here...','Colocar sua descrição aqui...',0,1456346949),('pt','Put your observations here...','Coloque suas observações aqui...',0,1456411667),('pt','Question','Pergunta',NULL,1456346898),('pt','Questions','Perguntas',0,1456347051),('pt','RTL','RTL',0,1456347070),('pt','Re-type New Password','Re-digite a nova senha',0,1456346884),('pt','Remember Me','Lembra de mim',0,1456345934),('pt','Repetition Limit','Limite de repetição',0,1456346893),('pt','Required','Necessário',0,1456346923),('pt','Required Equipment:','Equipamento necessário:',0,1456346934),('pt','Reset my Pass','Redefinir minha passagem',0,1464632616),('pt','Review translated terms','Rever os termos traduzidos',0,1456347076),('pt','Review translation','Revisão de tradução',0,1456347067),('pt','Right Side','Lado direito',0,1465308063),('pt','Road Map','Mapa de estrada',0,1456346910),('pt','Roadmap for Course Grouping: ','Roteiro para Agrupamento de curso:',0,1465245662),('pt','Role added to resource with success','Função adicionada ao recurso com sucesso',0,1464640140),('pt','Roles','Papéis',0,1456347052),('pt','Saturday','Sábado',0,1464790122),('pt','Save','Salvar',0,1456347079),('pt','Save Changes','Salvar as alterações',0,1456346951),('pt','Saving','Economia',0,1465308068),('pt','School Unit:','Unidade de escola:',0,1456346936),('pt','Search Lessons','Lições de pesquisa',0,1456346927),('pt','Search for Group or User','Busca por grupo ou usuário',0,1463668954),('pt','Search for Users','Busca de usuários',0,1463668922),('pt','Search for a course','Busca de um curso',0,1464790126),('pt','See your profile info, change your password and more.','Ver suas informações de perfil, alterar sua senha e muito mais.',0,1456346865),('pt','Select','Selecione',0,1463668923),('pt','Select User','Selecione o usuário',0,1463668921),('pt','Select a Period','Selecione um período',0,1464790119),('pt','Select a action','Selecione uma ação',NULL,1464790125),('pt','Select a course','Selecione um curso',0,1456346902),('pt','Select...','Selecione...',NULL,1456346882),('pt','Send','Enviar',NULL,1465506294),('pt','Send Message','Enviar mensagem',0,1456346898),('pt','Send To','Enviar para',NULL,1456346899),('pt','Send to the waiting list','Enviar para a lista de espera',0,1464790126),('pt','Set Resolution','Resolução conjunto',NULL,1463668926),('pt','Settings','Configurações',0,1456347050),('pt','Sign Up Aprroval?','Cadastre-se Aprroval?',0,1456411044),('pt','Sizes:','Tamanhos:',0,1463669006),('pt','Social Info','Informação social',0,1456411665),('pt','Sorry! Any data has been registered for this class yet.','Sinto muito! Quaisquer dados tem sido registrados para esta classe.',0,1456346924),('pt','Source','Fonte',0,1456347077),('pt','Stand By','Estar a postos',0,1456346919),('pt','Start Chat','Iniciar bate-papo',0,1456346947),('pt','Start Date','Data de início',0,1464790106),('pt','State','Estado',NULL,1456411672),('pt','Status','Estatuto',0,1456346930),('pt','Street','Rua',0,1464639594),('pt','Street Number','Número de rua',NULL,1464639595),('pt','Student','Estudante',0,1456347054),('pt','Student Limit','Limite de estudante',0,1464790123),('pt','Students Goals','Objetivos de alunos',0,1465245658),('pt','Subject','Assunto',NULL,1456346900),('pt','Submit','Enviar',0,1456345938),('pt','Suggested','Sugeriu',0,1456346924),('pt','Summary','Resumo',0,1464639596),('pt','Sunday','Domingo',0,1464790120),('pt','Support Requests','Solicitações de suporte',0,1463668923),('pt','Surname','Sobrenome',0,1456346879),('pt','System Settings','Configurações do sistema',0,1456411031),('pt','System Subtitle','Sistema de legendas',NULL,1456411035),('pt','System Title','Título de sistema',NULL,1456411036),('pt','System settings saved with success!','Configurações de sistema salvadas com sucesso!',0,1465327485),('pt','System-wide Title','Título de todo o sistema',0,1456411036),('pt','System-wide subtitle','Subtítulo de todo o sistema',0,1456411035),('pt','TRUE','VERDADE',0,1456346896),('pt','Talk about you..','Falar sobre você...',0,1456346883),('pt','Talk to us','Fale conosco',0,1456346891),('pt','Telephone:','Telefone:',0,1456346937),('pt','Term','Termo',0,1456347078),('pt','Test Info','Informações do teste',0,1456346892),('pt','Test Overview','Visão geral do teste',0,1456346892),('pt','Tests','Testes de',NULL,1456346916),('pt','Tests:','Testes:',0,1456346933),('pt','The answer is...','A resposta é...',0,1456346896),('pt','The goals to be achieved by the students','As metas a serem alcançadas pelos alunos',0,1465245658),('pt','The objective to be achieved by this program','O objetivo a ser alcançado por este programa',0,1465245657),('pt','The passwords doesn\'t match!','As senhas não combinam!',0,1464632615),('pt','The system can\'t authenticate you using the current methods. Please came back in a while.','O sistema não pode autenticá-lo usando os métodos atuais. Por favor, voltou em um tempo.',0,1459457478),('pt','The system can\'t locate this account. Please use the form below.','O sistema não pode localizar essa conta. Por favor, utilize o formulário abaixo.',0,1459459363),('pt','This filed will be used to create ','Isso arquivado será usado para criar',0,1464790118),('pt','This username is not avaliable. Please select another one.','Este nome de usuário não está disponível. Por favor selecione outro.',0,1464642806),('pt','Thursday','Quinta-feira',0,1464790122),('pt','Time limit','Limite de tempo',0,1456346893),('pt','Times done','Vezes feito',0,1456346917),('pt','Timezone','Fuso horário',0,1456346882),('pt','To','Para',0,1464790129),('pt','Toogle Active','Toogle ativo',0,1464790130),('pt','Total Lessons','Total de aulas',0,1464790130),('pt','Total Price','Preço total',0,1465245653),('pt','Total Questions','Total de perguntas',0,1456346892),('pt','Translation saved!','Tradução salvou!',0,1456347109),('pt','Translations','Traduções',0,1456347076),('pt','Tuesday','Terça-feira',0,1464790121),('pt','Type','Tipo',NULL,1456346928),('pt','Type your message here...','Digite sua mensagem aqui...',0,1456346951),('pt','URL for Business Card:','URL para cartão de visita:',0,1456346938),('pt','Unit','Unidade',0,1456346925),('pt','Units','Unidades',NULL,1456346913),('pt','Units Completed','Unidades concluídas',0,1464632649),('pt','Updated with success','Atualizado com sucesso',0,1456409612),('pt','Url','URL',NULL,1464790113),('pt','User / Group','Usuário / grupo',0,1463668954),('pt','User Since','Usuário desde',0,1464640093),('pt','User Type','Tipo de usuário',0,1464640092),('pt','Username and password are incorrect. Please make sure you typed correctly.','Nome de usuário e senha estão incorretos. Verifique se que você digitou corretamente.',0,1456346747),('pt','Users','Usuários',0,1456346871),('pt','Users Groups','Grupos de usuários',0,1463668990),('pt','Video','Vídeo',0,1456346927),('pt','View','Exibir',1,1456347109),('pt','View Map','Ver mapa',0,1456346944),('pt','View Translations','Exibir Traduções',0,1456347076),('pt','View Type','Tipo de exibição',0,1456347220),('pt','View and Edit system configuration','Exibir e editar a configuração do sistema',0,1456411031),('pt','View system languages','Idiomas do sistema vista',0,1456347067),('pt','Viewed','Visualizaram',NULL,1456346929),('pt','Website','Web site',0,1456411672),('pt','Wednesday','Quarta-feira',0,1464790121),('pt','Week','Semana',0,1464790119),('pt','Week(s)','Semana (s)',NULL,1465245655),('pt','YES','SIM',NULL,1456411044),('pt','Year','Ano',0,1464790120),('pt','Year(s)','Ano (s)',NULL,1465245656),('pt','Yes','Sim',NULL,1456346889),('pt','You can crop your picture, if you wish','Você pode recortar sua foto, se desejar',0,1463669006),('pt','You can define the criterias for user admitance. This information is used to make course grouping control, and the calculate the course desired roadmap.','Você pode definir os critérios para a entrada do usuário. Esta informação é usada para fazer curso agrupamento de controle e a calcular o roteiro do curso desejado.',0,1464790115),('pt','You can select one of two ways to define the grouping creation. A fixed way, when you manualy dwefined the dates for admittance, or the dynamic way, when you set the parameters for the system automatically create the grouping.','Você pode selecionar uma das duas maneiras de definir a criação do agrupamento. Uma forma fixa, quando você dwefined manualmente as datas para a admissão, ou de forma dinâmica, quando você define os parâmetros para o sistema automaticamente cria o agrupamento.',0,1464790116),('pt','You can set the maximum number of students allowed to enter in a grouping. If you don\'t want to limit, set this field to \'0\'.','Você pode definir o número máximo de alunos permitido entrar em um agrupamento. Se você não quiser limitar, conjunto este campo para \'0\'.',0,1464790123),('pt','You have been logout sucessfully. Thanks for using Sysclass.','Você tem sido bem sucedido de logout. Obrigado por usar o Sysclass.',0,1456346743),('pt','You\'re in:','Você está em:',NULL,1456346925),('pt','Your Courses','Seus cursos',NULL,1456346878),('pt','Your Files','Seus arquivos',0,1456346921),('pt','Your Location','Sua localização',0,1456346884),('pt','Your Profile','Seu perfil',0,1456346865),('pt','Your Support Requests','Seus pedidos de suporte',0,1463668923),('pt','Your curriculum overview','Visão geral de seu currículo',0,1464639596),('pt','Your session appers to be expired. Please provide your credentials.','Sua sessão appers expirem. Por favor fornece suas credenciais.',0,1456345941),('pt','Zipcode','ZipCode',NULL,1456411669),('pt','active','ativo',0,1465308065),('pt','classes selected','classes selecionadas',0,1456346943),('pt','completed','concluído',NULL,1456346905),('pt','files','arquivos',NULL,1456346921),('pt','here','aqui',0,1465482291),('pt','points','pontos',0,1456346939),('pt','to reset your password','para redefinir sua senha',0,1465482291),('pt','total classes','classes totais',0,1456346943);
/*!40000 ALTER TABLE `mod_translate_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_tutoria`
--

DROP TABLE IF EXISTS `mod_tutoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_tutoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_timestamp` int(10) unsigned NOT NULL,
  `answer_timestamp` int(10) unsigned DEFAULT NULL,
  `lessons_ID` int(11) NOT NULL,
  `unit_ID` int(11) DEFAULT NULL,
  `title` varchar(300) NOT NULL,
  `question_user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer_user_id` int(11) DEFAULT NULL,
  `answer` text,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_user_id` (`question_user_id`),
  KEY `awnser_user_id` (`answer_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_tutoria`
--

LOCK TABLES `mod_tutoria` WRITE;
/*!40000 ALTER TABLE `mod_tutoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_tutoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `group` varchar(45) DEFAULT NULL,
  `label` varchar(45) NOT NULL,
  `datatype` varchar(45) DEFAULT 'string',
  `description` text NOT NULL,
  `changeable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('chat_role_coordinator','4','Chat','Academic Chat Coordinator','int','Group responsible for academic coordinator chat',0),('chat_role_technical_support','5','Chat','Technical Support Group','int','Group responsible for technical support chat',0),('default_auth_backend','sysclass','General','Default Backend','string','Default Authentication Backend',0),('enable_facebook_login','0','Login & Signup','Enable Facebook Login?','bool','Enable the user to access the system through Facebook',1),('enable_forgot_form','1','Login & Signup','Enable Forgot Form','bool','Enable the \'forgot password\' option to be showed to the user on login screen',1),('enable_googleplus_login','0','Login & Signup','Enable Google+ Login?','bool','Enable the user to access the system through Google Plus',1),('enable_linkedin_login','0','Login & Signup','Enable LinkedIn Login?','bool','Enable the user to access the system through LinkedIn',1),('locked_down','0','General','Lock System','bool','Lock System, preventing ordinary users to access (like a explicit maintenance mode)',1),('maintenance_mode','0','General','Maintenance Mode','bool','Lock System, preventing ALL users to access (except system administrators)',0),('signup_enable','1','Login & Signup','Enable Sign Up?','bool','Allow anonimous users to create an account',1),('signup_group_default','2','Login & Signup','Default User Group ','int','Default Group Id for public signup users',0),('signup_must_approve','1','Login & Signup','Sign Up Aprroval?','bool','Public Signed Users must be approved?',1),('site_subtitle','Online Education','General','System Subtitle','string','System-wide subtitle',1),('site_title','Sysclass','General','System Title','string','System-wide Title',1);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_api_tokens`
--

DROP TABLE IF EXISTS `user_api_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_api_tokens` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_api_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_api_tokens`
--

LOCK TABLES `user_api_tokens` WRITE;
/*!40000 ALTER TABLE `user_api_tokens` DISABLE KEYS */;
INSERT INTO `user_api_tokens` VALUES (1,'f1231875caaa54885dc1ed95fd9353c41c1db85a19f3342e470f7a8a15fd993a',1,1464118681,1464118682,0),(2,'0e5794d1ae413d2ca8b499cef1bcf1bcb0dca7bb345c5f0bd6410fe4fa57b792',1,1464119054,1464119054,0),(3,'8729a0dd2494993c0ecbf9baac5a531b4f7fb61c0e347efb265df7a5b497a957',1,1464119069,1464119069,0),(4,'3c97e1d6ef8266ed3ce186f26b04d6d605541efd3d0055f757c70066e910137a',1,1464119077,1464119077,0),(5,'6fcb5e71851147c5b731db74233504f11f3402fa1d7a73948cabdd05da49b9d6',1,1464119098,1464119098,0),(6,'f489b79dc40cd5f5f31a2300510e7456aad37033bb95cd0d99763d8fc23ebfac',1,1464192605,1464192605,0),(7,'30b59d29c46dbc5bcb5890ea35cb4ed7b4541bd6bac8854b7e13ed97ac60053a',1,1464192623,1464192623,0),(8,'bf279da66a97861767ff2696138c84d82c913f4a2915809e741d21c23e00afe7',1,1464192623,1464192623,0),(9,'81c33a06ab1b79feaa6b6f19cda5b1ce9663ed2cd334c2d7d5a9debb26d4743f',1,1464192676,1464192676,0),(10,'2663e5091d423cdf770550f7d994d0a8b3a833c0b22c54872ac7119733b667ad',1,1464192682,1464192682,0),(11,'baa8f07cfcabba8a04fe839376b7adeff1f543c7ec491ae229bd78c927f9512a',1,1464200711,1464200711,0),(12,'f248811420b4ad3b423a022802103daabad3f867ffa6956223ca1e04e9bacbda',1,1464204362,1464204362,0),(13,'c5dee5cf6ecc0cc47a7de21e2c03374fb94fb6aa4d86e448cbc10239bae1e568',1,1464624305,1464624305,0),(14,'79471b6692b662bcf3f6cde8f1e3b49e80f669e020ba12ba893fa0a5a3b2a5a1',1,1464624323,1464624323,0),(15,'bf6c41ff8139aae9f4869772483d48885c67a1eaf61fffd9d0fbbc9c86f304aa',1,1464624378,1464624378,0),(16,'00a4439538b1d974a1584ddd282e514cdc77f3b0005e590dde3da161ee58d70d',1,1464624433,1464624433,0),(17,'8baca79851bfec18647d22b0080c8715974a7b41b11c6069fe9b3b41946185fa',1,1464624523,1464624523,0),(18,'2854a2bcb4593bb068c1c565ddd924c745e68b0422e035919fb803ad8e7d3e56',1,1464624530,1464624530,0),(19,'be58c4d0352227616d242ecaf98786d2b9fa4508436666a92736fceeba640fd1',1,1464624595,1464624595,0),(20,'8cfe38841a678a03287e711d7badf7d3c39139bffca3340ffec4b882e1834ef3',1,1464630843,1464630844,0),(21,'1b15514a5b0c2f879fc236db0301c9c4185de3102b47ef342fb07386d693d97a',1,1464630873,1464630873,0),(22,'2933c3bde831cab8707231d61d86c9faed1f4cd7cc113f4e387b71269e62e432',1,1464630897,1464630897,0),(23,'6c3345821d5d1a16cee66b3ecb44967415ed53d71ad48dfada7fe3731f5b0c5a',1,1464631356,1464631356,0),(24,'07d385d07feb09f7104bbced678bd413ff781f4db97ed06feb2634b2cd2d1b6f',1,1464631657,1464631657,0),(25,'e6ceab438d4a59f1759ea408b7ddc52327915a7e0e9f696641f821e3356a1979',1,1464632075,1464632075,0),(26,'e8e9f28f56ca8eb201669730157f93936cc4bfe98dd47781d2d568a0a3ae7ea4',1,1464632587,1464632587,0),(27,'806c2f20179fc214252dd03858e0d92b682b611da43db794a0a3dedd6c12e8e4',1,1464633957,1464633957,0),(28,'50163aea6117dd01969052f81f3fa4850f7b061b77ed049de50207ce52fde53b',1,1464707738,1464707738,0),(29,'871e1c0104e84b1b33c908a87eefc13585be84cdabf7529886d3d7a5c9fa1570',1,1464707741,1464707741,0),(30,'d3f3cab582bc54687f4e002012ba26a015654fd5afc8fac2b9b4517540b03f91',1,1464707833,1464707833,0),(31,'d2aad2d06c79abeefed62356aee8374ba3e57996088fb4eb7a98783ccdb17b4c',1,1464707889,1464707889,0),(32,'cb6c4af715c746f6c1164c7f7219f9c04b97e6f6d6fddf5737f6f8a856b3c657',1,1464709514,1464709514,0),(33,'417dcc7d81e65598c081f2055d5bdb6fcedb18d14b337326461fc10897f302c5',1,1464709575,1464709575,0),(34,'f64fdae0e346b93a086ad0bb8977829ae342fd708156c0a74cb1ea10f3ca1cc4',1,1464963422,1464963422,0),(35,'300bea8f15f806e57746c695484cfd144e6eeffb08ffa6f1adc370ec71c5bb1d',1,1464963492,1464963492,0),(36,'3064085923facaf7d8ed1496404af1d53df24f8a649a3c064716f3fd214b2f1c',1,1464963520,1464963520,0),(37,'6de660e146da3432741c407d60e66c41a2538d78b16992bd962ac1eb46b3149f',1,1465494717,1465494717,0),(38,'b617007fcf82be5d8cbef3a596359b56a46ac902348a4b0fc4ee4bc78a695cae',1,1465494745,1465494745,0),(39,'c9483fe2a24b188f1ab05dd3553322bca5fa384bdb620b6122cd95e9ed9ef9b9',1,1465495870,1465495871,0),(40,'28d7c26bd9c6a5398121b314aeb142dd0fc6f52017d94022c285712788fd156c',1,1465495915,1465495915,0),(41,'7d7d27e03a3e654614a44c2af5dd4cdfb55c14dfba674534d3d9cee4e1dc3e2a',1,1465495932,1465495933,0),(42,'b1d0e7f5eb1614baccc7a9ca209b33bb7715415b832b551925d863b8b8c8174c',1,1465496268,1465496268,0),(43,'576e69a522007374b4c0657eead96021ce3809bda253809c758d49db7713e925',1,1465496273,1465496274,0),(44,'53ec31fc60046c324a29f3ca2b2497e655e0939e90b50ed71ee968848dcebc12',1,1465496277,1465496277,0),(45,'31c15d1b51adc68f95f250bcc15dbf54feb8e9e7a78ca1231af49a8756d59416',1,1465496410,1465496410,0);
/*!40000 ALTER TABLE `user_api_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_avatar`
--

DROP TABLE IF EXISTS `user_avatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_avatar` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `file_id` mediumint(8) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `user_avatar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_avatar_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `mod_dropbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_avatar`
--

LOCK TABLES `user_avatar` WRITE;
/*!40000 ALTER TABLE `user_avatar` DISABLE KEYS */;
INSERT INTO `user_avatar` VALUES (1,12,1),(6,13,1);
/*!40000 ALTER TABLE `user_avatar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_curriculum`
--

DROP TABLE IF EXISTS `user_curriculum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_curriculum` (
  `id` mediumint(8) unsigned NOT NULL,
  `summary` text,
  PRIMARY KEY (`id`),
  CONSTRAINT `user_curriculum_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_curriculum`
--

LOCK TABLES `user_curriculum` WRITE;
/*!40000 ALTER TABLE `user_curriculum` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_curriculum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_settings` (
  `user_id` bigint(20) NOT NULL,
  `item` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`user_id`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
INSERT INTO `user_settings` VALUES (3,'class_id','3'),(3,'course_id','1'),(3,'lesson_id','5'),(5,'class_id','2'),(5,'course_id','1'),(5,'lesson_id','2'),(6,'class_id','3'),(6,'course_id','1'),(6,'lesson_id','4'),(9,'class_id','2'),(9,'course_id','1'),(9,'lesson_id','2'),(10,'class_id','1'),(10,'course_id','1'),(10,'lesson_id','6');
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_times`
--

DROP TABLE IF EXISTS `user_times`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_times` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `websocket_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_times_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_times`
--

LOCK TABLES `user_times` WRITE;
/*!40000 ALTER TABLE `user_times` DISABLE KEYS */;
INSERT INTO `user_times` VALUES (1,'hnvvoitvd9ap3gflvqeoedad76',1,1456341203,1456343677,1,NULL),(2,'qnnk7725iopp74nidehlmh1lc6',1,1456343764,1456343918,1,NULL),(3,'4409mk1j9fbpt4q90akcng8687',1,1456343939,1456345252,1,NULL),(4,'aimgq19lnstr9quimhkqijeok7',1,1456345950,1456346673,1,NULL),(5,'ldkddq70numevpmj0t8v4p52s2',1,1456346787,1456347000,1,NULL),(6,'ag8c8mkq2107dm8upsb4s4ni91',3,1456346828,1456347713,1,NULL),(7,'s5qnk0dmrp72egafq0gcdf1385',3,1456404443,1456412161,1,NULL),(8,'1d8f4sj4r373kjvca04m9at203',3,1463668918,1463694724,1,NULL),(9,'r0nor3cek5b2evajfd6mevufq2',3,1463751955,1464033522,1,'96950496157436dd839e33789494391'),(10,'2te78sqev8ubc5hgcbme55oi72',1,1464031492,1464031492,1,NULL),(11,'v7p5cre2a7p1ie3as9fd3o8gb2',1,1464031552,1464032735,1,'19045180757435dea5bd03659021132'),(12,'eaqvjde4dmsejc3s8kuechq055',1,1464099754,1464100283,1,NULL),(13,'auvrjs24e0ie2hvb98sem3cob6',1,1464100307,1464100307,1,NULL),(14,'7s51n4jnhfb4bi5db0cvlqbfo2',4,1464100336,1464100336,1,NULL),(16,'2kp682ee9tsjksl4omgkjr2re4',1,1464100461,1464100461,1,NULL),(18,'imss2b5dpalethjtmikcisg5j5',4,1464100591,1464100854,1,NULL),(19,'voskromvtga2he7bbppdipmgp4',4,1464100880,1464100880,1,NULL),(20,'tuubq2dg877p665nk5jj1i8li1',1,1464100926,1464121766,1,'2083560095744b9d2bb888347320022'),(21,'7ud7r2mr9nr5tn18u2j9696ok7',3,1464119330,1464120534,1,'7782556635744bc2cac45e121918628'),(22,'t7s2qj8oipupq117eq9gejeg97',4,1464197739,1464197739,1,NULL),(23,'bcl9fgnj1j8kjmdphpfeffpic2',4,1464487744,1464487744,1,NULL),(24,'nlmllqj4g071a9raq320a3tor2',4,1464623252,1464624139,1,NULL),(26,'3elcc430n6cvc79kef2lc096e2',4,1464624161,1464624161,1,NULL),(28,'ooqqnnatci141k0eiacs77rig1',1,1464630864,1464639834,1,NULL),(30,'f7icbi0nhkq61htoc3ftbekv60',3,1464633071,1464641996,1,'1395237279574ca9d3a1b0a774417438'),(31,'cufgqssfobjg422q5amiqkkon4',6,1464638712,1464639726,1,NULL),(33,'ibc9t5cbohhhckefp5t3kih9d0',4,1464639747,1464662527,1,'867300531574cfa01a7eb1714731850'),(34,'gm8153udj5ikaba8c4etl3h2o5',6,1464639790,1464639790,1,NULL),(35,'07nc9atslv5mgjttt51150ijn2',6,1464639849,1464640039,1,NULL),(36,'uehu763e0vi5eu8ruv1ea4e5j5',6,1464639922,1464640470,1,NULL),(37,'3herbeikhbm7nn30btl1ve84u2',1,1464640052,1464641050,1,'1883263090574cbb6ab0eb9709457171'),(39,'vk9neu61vqsbhmk4olf41ed0c5',3,1464708516,1464708516,1,NULL),(40,'jsc63j9m7jocm5j45lq81343e5',3,1464708624,1464729117,0,NULL),(41,'cqnn2i3ad5o5u6lg7hr48u2m15',4,1464723159,1464723159,0,NULL),(42,'decjcqisdofi2srkpk473f1fs4',1,1464790024,1464795888,1,NULL),(43,'acddc6i8toutls3c9mikddje01',3,1464796309,1464812589,0,NULL),(44,'g0647qq4m4adegkrst348tv300',1,1464885191,1464885191,1,NULL),(45,'vhgm7f7nkovsm796utd8fhg7d0',1,1464895526,1464895989,1,NULL),(46,'fpf6k9ldp7vcshfsh4t5bv0j40',3,1464961252,1464970568,0,NULL),(53,'0egm9ftutigaehm6uhkffm78a1',4,1465074199,1465074199,0,NULL),(54,'v4es42scpp9981bvkkpi0qia83',1,1465243141,1465245638,1,NULL),(55,'n40kivofkpos2d9jd3ahaiej71',1,1465307847,1465327365,1,NULL),(56,'kor42aimcgd7h8diaunjaldqk5',3,1465322689,1465337063,0,'1580028682575744fe5a0be562980867'),(57,'972s1fv4uvumsf1h2a2rre6je5',1,1465327389,1465327484,1,'151075204157571f84dbf65271765027'),(58,'nvpnkmcn4hftn5hgvdognlrq91',1,1465327584,1465327584,1,'418195314575720002a7a1731936458'),(59,'ijeil17l78m7h88hkv4mi83pc6',1,1465327997,1465327997,0,'51484726557573e79e1576839393464'),(60,'27hk3fmtn147mnb6k0o4otvng4',3,1465494254,1465506223,0,NULL),(61,'b5f77jt9jf5i69t50m323vguq7',10,1465504611,1465504611,1,NULL),(62,'gpunlltjui27cos0603ilpu2a4',10,1465504668,1465506282,1,NULL),(63,'r6tk9ril8go9tmgq51ho3ihus0',10,1465506292,1465506292,0,NULL);
/*!40000 ALTER TABLE `user_times` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `basic_user_type` varchar(50) NOT NULL,
  `extended_user_type` varchar(50) NOT NULL,
  `core_access` text,
  `modules_access` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `backend` varchar(45) NOT NULL DEFAULT 'sysclass',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(150) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT '0',
  `birthday` date DEFAULT NULL,
  `timezone` varchar(100) DEFAULT '',
  `short_description` text,
  `can_be_instructor` tinyint(1) NOT NULL DEFAULT '0',
  `can_be_coordinator` tinyint(1) NOT NULL DEFAULT '0',
  `viewed_license` tinyint(1) DEFAULT '0',
  `autologin` char(32) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `user_type` varchar(50) NOT NULL DEFAULT 'student',
  `dashboard_id` varchar(25) NOT NULL DEFAULT 'default',
  `comments` text,
  `pending` tinyint(1) NOT NULL DEFAULT '0',
  `user_types_ID` mediumint(8) DEFAULT '0',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `api_secret_key` char(64) DEFAULT NULL,
  `websocket_key` char(64) DEFAULT NULL,
  `reset_hash` char(64) DEFAULT NULL,
  `cnpj` varchar(150) DEFAULT NULL,
  `phone` varchar(150) DEFAULT NULL,
  `how_did_you_know` varchar(150) DEFAULT NULL,
  `is_supplier` varchar(150) DEFAULT NULL,
  `supplier_name` varchar(150) DEFAULT NULL,
  `postal_code` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `street` varchar(150) DEFAULT NULL,
  `street_number` varchar(150) DEFAULT NULL,
  `district` varchar(150) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `active` (`active`),
  KEY `email` (`email`),
  KEY `name` (`name`),
  KEY `surname` (`surname`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2a$08$k5yCW8G2isTcKf5CkYjoQuoLzm3IB08ooIBPMMqJg1IJsJPeq4Ck.','sysclass',0,'postmaster@sysclass.com','Administrator','User',2,NULL,'',NULL,0,0,1,NULL,1,'student','student',NULL,0,0,'2016-02-24 19:08:52','$2a$08$5115jOPDM49JPdL0Etz7neznB4idBCbhgXXGM0QCbsekfZfnphM4C','$2a$08$Rng8Ygjh2pCWr3Ut49sHFOjH72Q/pFJevyYaCevxEo.CFTJ/vC/7m',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'acir','$2a$08$Rng8Ygjh2pCWr3Ut49sHFOjH72Q/pFJevyYaCevxEo.CFTJ/vC/7m','sysclass',0,'acir@americas.com.br','Acir','Mandello',1,'1985-12-06','America/Sao_Paulo',NULL,0,0,1,NULL,1,'student','student',NULL,0,0,'2016-02-24 20:45:35',NULL,'$2a$08$f7Ukff6Frz1gIwex5LLQtONjbsSO.R6yvxPKOCXzsA/q1PwnP0ury',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'carlos.oliveira','$2a$08$8cz9UT6nG9HGl5xxmnizTuHSdxlgsrZ3yAKejyVJYwevZi66Lr6Lu','sysclass',0,'carlos@wiseflex.com','Carlos','Oliveira',1,NULL,'',NULL,0,0,1,NULL,1,'student','student',NULL,0,0,'2016-05-24 14:24:28',NULL,'$2a$08$p7HKlFj39KhvhR67puNWOO58mClNwXCibOuCe5/I9YV0L/7JiEefu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'carlos.oliveira572','$2a$08$IdByWH8hB1krwvEKdMD1p.hPDBrK3yG2jHzzFpEZ.3fiJBbpJfO4e','sysclass',0,'carlos@wiseflex.com','Carlos','OLIVEIRA',2,'2016-05-09','',NULL,0,0,1,NULL,1,'student','student',NULL,0,0,'2016-05-30 16:07:13',NULL,'$2a$08$6NxpJ6MqJTY3lBRCfSMShuMroDbb40uXSjbkhVkD4IdVlucC.V3pe',NULL,'',NULL,NULL,'','WiseFlex Knowledge Systems LLC.','75000-000','brasil','Rua Visconde de Nacar','1505','sao luiz ','Garland','RIO'),(10,'japones.federal','$2a$08$dLnemPy2zNHIDV5xfzogvePsDDwkoWdXSKh87ve7Hpgct534O.Fqu','sysclass',0,'acir@ult.com.br','Japones','da Federal',0,NULL,'',NULL,0,0,1,NULL,1,'student','student',NULL,0,0,'2016-06-09 18:17:48',NULL,'$2a$08$F31nhyWmBFWJHYQ9IsJZpOpGoeTW5QdhgfCOAcFzlEWx2p3Wbdggi',NULL,'10.811.409/0001-41',NULL,NULL,'','Grupo Americas','80410-201','brasil','Rua Visconde de Nacar','1505','Centro','Curitiba','PR');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_password_request`
--

DROP TABLE IF EXISTS `users_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_password_request` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `reset_hash` varchar(100) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `valid_until` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `users_password_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_password_request`
--

LOCK TABLES `users_password_request` WRITE;
/*!40000 ALTER TABLE `users_password_request` DISABLE KEYS */;
INSERT INTO `users_password_request` VALUES (1,'baa825397ee058ec',1,'2016-06-08 01:27:16',0);
/*!40000 ALTER TABLE `users_password_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_to_groups`
--

DROP TABLE IF EXISTS `users_to_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_to_groups` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `users_to_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_to_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_to_groups`
--

LOCK TABLES `users_to_groups` WRITE;
/*!40000 ALTER TABLE `users_to_groups` DISABLE KEYS */;
INSERT INTO `users_to_groups` VALUES (3,1),(4,1),(1,2),(3,2),(4,2),(6,2),(10,2),(1,3),(3,3);
/*!40000 ALTER TABLE `users_to_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-10 18:37:51
