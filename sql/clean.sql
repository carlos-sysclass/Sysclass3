-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sysclass_clean
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_resources`
--

LOCK TABLES `acl_resources` WRITE;
/*!40000 ALTER TABLE `acl_resources` DISABLE KEYS */;
INSERT INTO `acl_resources` VALUES (1,'Roles','View',''),(2,'Roles','Create',''),(3,'Roles','Edit',''),(4,'Advertising','View',''),(5,'Areas','View',''),(6,'Calendar','View',''),(7,'Classes','View',''),(8,'Courses','View',''),(9,'Grades','View',''),(10,'Groups','View',''),(11,'Institution','View',''),(12,'Lessons','View',''),(13,'Questions','View',''),(14,'Tests','View',''),(15,'Translate','View',''),(16,'Users','View',''),(17,'Advertising','Create',''),(18,'Areas','Create',''),(19,'Calendar','Create',''),(20,'Classes','Create',''),(21,'Courses','Create',''),(22,'Grades','Create',''),(23,'Groups','Create',''),(24,'Institution','Create',''),(25,'Lessons','Create',''),(26,'Questions','Create',''),(27,'Tests','Create',''),(28,'Translate','Create',''),(29,'Users','Create',''),(30,'Advertising','Edit',''),(31,'Areas','Edit',''),(32,'Calendar','Edit',''),(33,'Classes','Edit',''),(34,'Courses','Edit',''),(35,'Grades','Edit',''),(36,'Groups','Edit',''),(37,'Institution','Edit',''),(38,'Lessons','Edit',''),(39,'Questions','Edit',''),(40,'Tests','Edit',''),(41,'Translate','Edit',''),(42,'Users','Edit',''),(43,'Calendar','Manage',''),(44,'Users','Change Password',''),(45,'Users','Delete',NULL),(46,'Dropbox','Edit',NULL),(47,'Permission','View',NULL),(48,'Questions','Delete',NULL),(49,'Enroll','View',NULL),(50,'Enroll','Delete',NULL),(51,'Enroll','Create',NULL),(52,'Settings','Manage',NULL),(53,'Roadmap','View',NULL),(54,'Dropbox','Delete',NULL),(55,'Translate','Delete',NULL),(56,'Chat','View',NULL),(59,'Chat','Delete',NULL),(60,'Chat','Assign',NULL),(61,'Chat','Receive',NULL),(62,'Enroll','Edit',NULL),(63,'Classes','Delete',NULL),(64,'Courses','Delete',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_roles`
--

LOCK TABLES `acl_roles` WRITE;
/*!40000 ALTER TABLE `acl_roles` DISABLE KEYS */;
INSERT INTO `acl_roles` VALUES (1,'Administrator',NULL,1,0,0),(2,'Student',NULL,1,0,0);
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
INSERT INTO `acl_roles_to_groups` VALUES (2,2);
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
INSERT INTO `acl_roles_to_resources` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(2,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64);
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Administrators',NULL,1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary'),(2,'Students',NULL,1,0,NULL,'0',NULL,0,0,'',0,0,0,0,'group','primary');
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
  `view_type` enum('serial','carrousel') NOT NULL DEFAULT 'serial',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_advertising`
--

LOCK TABLES `mod_advertising` WRITE;
/*!40000 ALTER TABLE `mod_advertising` DISABLE KEYS */;
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
  `title` varchar(150) NOT NULL,
  `info` text,
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `advertising_id` (`advertising_id`),
  CONSTRAINT `mod_advertising_content_ibfk_1` FOREIGN KEY (`advertising_id`) REFERENCES `mod_advertising` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_advertising_content`
--

LOCK TABLES `mod_advertising_content` WRITE;
/*!40000 ALTER TABLE `mod_advertising_content` DISABLE KEYS */;
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
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `coordinator_id` int(11) DEFAULT NULL,
  `info` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_areas`
--

LOCK TABLES `mod_areas` WRITE;
/*!40000 ALTER TABLE `mod_areas` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
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
  `started` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  CONSTRAINT `mod_chat_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
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
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `ies_id` mediumint(8) NOT NULL DEFAULT '0',
  `area_id` mediumint(8) unsigned DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `info` text,
  `course_id` mediumint(8) unsigned DEFAULT NULL,
  `instructor_id` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `type` enum('class','test') NOT NULL DEFAULT 'class',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `mod_classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `mod_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_classes`
--

LOCK TABLES `mod_classes` WRITE;
/*!40000 ALTER TABLE `mod_classes` DISABLE KEYS */;
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
-- Table structure for table `mod_courses`
--

DROP TABLE IF EXISTS `mod_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_courses` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `area_id` mediumint(8) DEFAULT '0',
  `ies_id` mediumint(8) DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `archive` int(10) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT NULL,
  `start_date` int(10) unsigned DEFAULT NULL,
  `end_date` int(10) unsigned DEFAULT NULL,
  `options` text,
  `metadata` text,
  `description` text,
  `info` text,
  `price` float DEFAULT '0',
  `enable_registration` tinyint(1) NOT NULL DEFAULT '1',
  `price_registration` float NOT NULL DEFAULT '0',
  `enable_presencial` tinyint(1) DEFAULT '1',
  `price_presencial` float DEFAULT '0',
  `enable_web` tinyint(1) DEFAULT '1',
  `price_web` float DEFAULT '0',
  `show_catalog` tinyint(1) NOT NULL DEFAULT '1',
  `publish` tinyint(1) DEFAULT '1',
  `directions_ID` mediumint(8) unsigned DEFAULT NULL,
  `reset` tinyint(1) NOT NULL DEFAULT '0',
  `certificate_expiration` int(10) unsigned DEFAULT NULL,
  `max_users` int(10) unsigned DEFAULT NULL,
  `rules` text,
  `terms` text,
  `instance_source` mediumint(8) unsigned DEFAULT '0',
  `supervisor_LOGIN` varchar(100) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'pt-br',
  `has_grouping` tinyint(1) NOT NULL DEFAULT '0',
  `has_student_selection` tinyint(1) NOT NULL DEFAULT '0',
  `has_periods` tinyint(1) NOT NULL DEFAULT '0',
  `coordinator_id` text,
  `duration_units` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `duration_type` varchar(45) NOT NULL DEFAULT 'year',
  `price_total` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `price_step_units` mediumint(8) unsigned NOT NULL DEFAULT '10',
  `price_step_type` varchar(45) NOT NULL DEFAULT 'month',
  PRIMARY KEY (`id`),
  KEY `instance_source` (`instance_source`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_courses`
--

LOCK TABLES `mod_courses` WRITE;
/*!40000 ALTER TABLE `mod_courses` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=685 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_dropbox`
--

LOCK TABLES `mod_dropbox` WRITE;
/*!40000 ALTER TABLE `mod_dropbox` DISABLE KEYS */;
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
  `start_date` int(10) unsigned NOT NULL,
  `end_date` int(10) unsigned NOT NULL,
  `identifier` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `admittance_type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `active` smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll`
--

LOCK TABLES `mod_enroll` WRITE;
/*!40000 ALTER TABLE `mod_enroll` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_course_to_users`
--

LOCK TABLES `mod_enroll_course_to_users` WRITE;
/*!40000 ALTER TABLE `mod_enroll_course_to_users` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_courses`
--

LOCK TABLES `mod_enroll_courses` WRITE;
/*!40000 ALTER TABLE `mod_enroll_courses` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_fields`
--

LOCK TABLES `mod_enroll_fields` WRITE;
/*!40000 ALTER TABLE `mod_enroll_fields` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_enroll_fields_options`
--

LOCK TABLES `mod_enroll_fields_options` WRITE;
/*!40000 ALTER TABLE `mod_enroll_fields_options` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_grades`
--

LOCK TABLES `mod_grades` WRITE;
/*!40000 ALTER TABLE `mod_grades` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_grades_ranges`
--

LOCK TABLES `mod_grades_ranges` WRITE;
/*!40000 ALTER TABLE `mod_grades_ranges` DISABLE KEYS */;
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
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `name` varchar(250) NOT NULL,
  `formal_name` varchar(250) NOT NULL,
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
INSERT INTO `mod_institution` VALUES (1,'4','Default Institution','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'BR',NULL,1,NULL,NULL,NULL);
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
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `class_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `info` text,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `type` enum('lesson','test') NOT NULL DEFAULT 'lesson',
  `has_text_content` tinyint(1) NOT NULL DEFAULT '1',
  `text_content` text,
  `text_content_language_id` int(11) DEFAULT '1',
  `has_video_content` tinyint(1) DEFAULT '1',
  `subtitle_content_language_id` int(11) DEFAULT '1',
  `instructor_id` text,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `mod_lessons_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `mod_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons`
--

LOCK TABLES `mod_lessons` WRITE;
/*!40000 ALTER TABLE `mod_lessons` DISABLE KEYS */;
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
  `language_code` varchar(10) NOT NULL DEFAULT 'en',
  `position` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `mod_lessons_content_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `mod_lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mod_lessons_content_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `mod_lessons_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_lessons_content`
--

LOCK TABLES `mod_lessons_content` WRITE;
/*!40000 ALTER TABLE `mod_lessons_content` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `mod_lessons_content_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_lessons_content_progress`
--

DROP TABLE IF EXISTS `mod_lessons_content_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_lessons_content_progress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `content_id` mediumint(8) unsigned NOT NULL,
  `factor` decimal(4,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
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
  `vencimento` timestamp NULL DEFAULT NULL,
  `valor` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_questions`
--

LOCK TABLES `mod_questions` WRITE;
/*!40000 ALTER TABLE `mod_questions` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_courses_periods`
--

LOCK TABLES `mod_roadmap_courses_periods` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_courses_periods` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_roadmap_courses_to_classes`
--

LOCK TABLES `mod_roadmap_courses_to_classes` WRITE;
/*!40000 ALTER TABLE `mod_roadmap_courses_to_classes` DISABLE KEYS */;
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
  `country_code` varchar(10) NOT NULL,
  `permission_access_mode` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `name` varchar(50) NOT NULL,
  `local_name` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `rtl` tinyint(1) NOT NULL DEFAULT '0',
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
INSERT INTO `mod_translate` VALUES (1,'en','US','4','English','English',1,0),(2,'pt','BR','4','Portuguese','Português',1,0),(3,'es','ES','4','Spanish','Español',1,0),(4,'ko','KR','4','Korean','한국의',0,0),(5,'it','IT','4','Italian','Italiano',1,0),(6,'fr','FR','4','French','Français',1,0),(7,'el','GR','4','Greek','Greek',0,0),(8,'zh-CHS','CN','4','Chinese','Chinese',0,1),(9,'ar','SA','4','Arabic','Arabic',0,1),(10,'ru','RU','4','Russian','Russia',0,0),(11,'th','TH','4','Thailand','Siamese',0,0);
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
INSERT INTO `mod_translate_tokens` VALUES ('en','#','#',0,1456342038),('en','% Complete','% Complete',0,1456343955),('en','A problem ocurred when tried to save you data. Please try again.','A problem ocurred when tried to save you data. Please try again.',0,1456341208),('en','Actions','Actions',NULL,1456342039),('en','Active','Active',NULL,1456342038),('en','Add Language','Add Language',NULL,1456344025),('en','Add/Change file','Add/Change file',0,1456343954),('en','Address Book','Address Book',0,1456343954),('en','Address Line 1','Address Line 1',NULL,1456343954),('en','Address Line 2','Address Line 2',NULL,1456343954),('en','Administation','Administation',0,1456341203),('en','Advertising','Advertising',NULL,1456343720),('en','Allow anonimous users to create an account','Allow anonimous users to create an account',0,1456344464),('en','Allow user to be a class/lesson instructor','Allow user to be a class/lesson instructor',0,1456343783),('en','Allow user to be a course coordinator','Allow user to be a course coordinator',0,1456343783),('en','Are you sure?','Are you sure?',0,1456341204),('en','Back','Back',0,1456340941),('en','Behavior','Behavior',0,1456343783),('en','Can be Coordinator?','Can be Coordinator?',0,1456343783),('en','Can be Instructor?','Can be Instructor?',0,1456343783),('en','City','City',NULL,1456343955),('en','Class Role','Class Role',0,1456342039),('en','Classes','Classes',0,1456342037),('en','Click to access','Click to access',0,1456340938),('en','Close','Close',0,1456342037),('en','Code','Code',0,1456344025),('en','Communication','Communication',0,1456341203),('en','Config and edit user info','Config and edit user info',0,1456343782),('en','Confirm','Confirm',0,1456341204),('en','Content','Content',0,1456341203),('en','Country','Country',0,1456343954),('en','Course Role','Course Role',0,1456342038),('en','Courses','Courses',0,1456342037),('en','Create Role','Create Role',0,1456342037),('en','Crop Image','Crop Image',0,1456343953),('en','Departments','Departments',NULL,1456343720),('en','Description','Description',0,1456342038),('en','Edit Organization','Edit Organization',0,1456343953),('en','Edit User','Edit User',0,1456343782),('en','Edit your Organization','Edit your Organization',0,1456343953),('en','Email','Email',NULL,1456340940),('en','Enable Facebook Login?','Enable Facebook Login?',0,1456344463),('en','Enable Forgot Form','Enable Forgot Form',0,1456344463),('en','Enable Google+ Login?','Enable Google+ Login?',0,1456344463),('en','Enable LinkedIn Login?','Enable LinkedIn Login?',0,1456344464),('en','Enable Sign Up?','Enable Sign Up?',0,1456344464),('en','Enable the \'forgot password\' option to be showed to the user on login screen','Enable the \'forgot password\' option to be showed to the user on login screen',0,1456344463),('en','Enable the user to access the system through Facebook','Enable the user to access the system through Facebook',0,1456344463),('en','Enable the user to access the system through Google Plus','Enable the user to access the system through Google Plus',0,1456344464),('en','Enable the user to access the system through LinkedIn','Enable the user to access the system through LinkedIn',0,1456344464),('en','Enabled','Enabled',0,1456342038),('en','English Name','English Name',0,1456344025),('en','Enrolled Classes','Enrolled Classes',0,1456343782),('en','Enrolled Courses','Enrolled Courses',0,1456343782),('en','Enrollment','Enrollment',NULL,1456343720),('en','Enter your e-mail address below to reset your password.','Enter your e-mail address below to reset your password.',0,1456340940),('en','Entities using these roles','Entities using these roles',0,1456342037),('en','Events','Events',NULL,1456343720),('en','Facebook','Facebook',0,1456343955),('en','Forget your password?','Forget your password?',0,1456340939),('en','Full Name','Full Name',0,1456343954),('en','Full Screen','Full Screen',0,1456341204),('en','General','General',0,1456343782),('en','Grades','Grades',NULL,1456343720),('en','Group','Group',0,1456342037),('en','Group Included.','Group Included.',0,1456344235),('en','Groups','Groups',NULL,1456343720),('en','Help','Help',0,1456341204),('en','Home','Home',0,1456342036),('en','I confirm that I have read and accept the above terms','I confirm that I have read and accept the above terms',0,1456341204),('en','Language','Language',0,1456343783),('en','Languages','Languages',NULL,1456343720),('en','Lessons','Lessons',NULL,1456343720),('en','License Viewed','License Viewed',0,1456343729),('en','Loading','Loading',0,1456341205),('en','Local Name','Local Name',0,1456344025),('en','Lock Screen','Lock Screen',0,1456341204),('en','Lock System','Lock System',0,1456344463),('en','Lock System, preventing ordinary users to access (like a explicit maintenance mode)','Lock System, preventing ordinary users to access (like a explicit maintenance mode)',0,1456344463),('en','Log Out','Log Out',0,1456341204),('en','Log in details','Log in details',0,1456343783),('en','Login','Login',NULL,1456340938),('en','Login & Signup','Login & Signup',0,1456344463),('en','Login to your account','Login to your account',0,1456340937),('en','Logo','Logo',0,1456343954),('en','Manage Role Permissions','Manage Role Permissions',0,1456342037),('en','Manage role Users and Groups','Manage role Users and Groups',0,1456342038),('en','Manage your Roles','Manage your Roles',0,1456342036),('en','Manage your users','Manage your users',0,1456343728),('en','Menu','Menu',0,1456341204),('en','My Profile','My Profile',0,1456341204),('en','NO','NO',0,1456343519),('en','Name','Name',NULL,1456342038),('en','New Password','New Password',0,1456343783),('en','New Role','New Role',0,1456342036),('en','New User','New User',0,1456343729),('en','No','No',0,1456341204),('en','No file(s) found. Drag a file over this window or click below to add','No file(s) found. Drag a file over this window or click below to add',0,1456343955),('en','Not Classified','Not Classified',0,1456341204),('en','OFF','OFF',NULL,1456342037),('en','ON','ON',NULL,1456342037),('en','Observations','Observations',0,1456343954),('en','Organization','Organization',NULL,1456343720),('en','Organizations','Organizations',0,1456343953),('en','Password','Password',NULL,1456340938),('en','Payments','Payments',0,1456341203),('en','Pending aproval','Pending aproval',0,1456343729),('en','Phone Number','Phone Number',NULL,1456343954),('en','Please Select','Please Select',0,1456342038),('en','Primary Group','Primary Group',NULL,1456343783),('en','Primary Language','Primary Language',0,1456343783),('en','Public Signed Users must be approved?','Public Signed Users must be approved?',0,1456344464),('en','Put your observations here...','Put your observations here...',0,1456343954),('en','Questions','Questions',NULL,1456343720),('en','RTL','RTL',0,1456344025),('en','Re-type New Password','Re-type New Password',0,1456343783),('en','Remember Me','Remember Me',0,1456340938),('en','Review translation','Review translation',0,1456344025),('en','Role added to resource.','Role added to resource.',0,1456343584),('en','Roles','Roles',0,1456341904),('en','Save Changes','Save Changes',0,1456342037),('en','Search for Group or User','Search for Group or User',0,1456342038),('en','Search for new Course','Search for new Course',0,1456343783),('en','Select a group','Select a group',0,1456343783),('en','Settings','Settings',NULL,1456343720),('en','Sign Up Aprroval?','Sign Up Aprroval?',0,1456344464),('en','Social Info','Social Info',0,1456343954),('en','State','State',NULL,1456343955),('en','Submit','Submit',0,1456340941),('en','Surname','Surname',0,1456343729),('en','System Settings','System Settings',0,1456344093),('en','System Subtitle','System Subtitle',NULL,1456344463),('en','System Title','System Title',NULL,1456344463),('en','System-wide Title','System-wide Title',0,1456344463),('en','System-wide subtitle','System-wide subtitle',0,1456344463),('en','Tests','Tests',NULL,1456343720),('en','Type','Type',0,1456342038),('en','updated.','updated.',0,1456343929),('en','User / Group','User / Group',0,1456342038),('en','Username and password are incorrect. Please make sure you typed correctly.','Username and password are incorrect. Please make sure you typed correctly.',0,1456340937),('en','Users','Users',0,1456341204),('en','View','View',0,1456344024),('en','View and Edit system configuration','View and Edit system configuration',0,1456344094),('en','View system languages','View system languages',0,1456344024),('en','Website','Website',0,1456343955),('en','YES','YES',0,1456343519),('en','Yes','Yes',0,1456341204),('en','You agreed within the license. Thanks for using Sysclass','You agreed within the license. Thanks for using Sysclass',0,1456341678),('en','You have been logout sucessfully. Thanks for using Sysclass.','You have been logout sucessfully. Thanks for using Sysclass.',0,1456343752),('en','Your session appers to be expired. Please provide your credentials.','Your session appers to be expired. Please provide your credentials.',0,1456340942),('en','Zipcode','Zipcode',NULL,1456343954);
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
INSERT INTO `settings` VALUES ('default_auth_backend','sysclass','General','Default Backend','string','Default Authentication Backend',0),('enable_facebook_login','0','Login & Signup','Enable Facebook Login?','bool','Enable the user to access the system through Facebook',1),('enable_forgot_form','0','Login & Signup','Enable Forgot Form','bool','Enable the \'forgot password\' option to be showed to the user on login screen',1),('enable_googleplus_login','0','Login & Signup','Enable Google+ Login?','bool','Enable the user to access the system through Google Plus',1),('enable_linkedin_login','0','Login & Signup','Enable LinkedIn Login?','bool','Enable the user to access the system through LinkedIn',1),('locked_down','0','General','Lock System','bool','Lock System, preventing ordinary users to access (like a explicit maintenance mode)',1),('maintenance_mode','0','General','Maintenance Mode','bool','Lock System, preventing ALL users to access (except system administrators)',0),('signup_enable','1','Login & Signup','Enable Sign Up?','bool','Allow anonimous users to create an account',1),('signup_group_default','2','Login & Signup','Default User Group ','int','Default Group Id for public signup users',0),('signup_must_approve','1','Login & Signup','Sign Up Aprroval?','bool','Public Signed Users must be approved?',1),('site_subtitle','Online Education','General','System Subtitle','string','System-wide subtitle',1),('site_title','Sysclass','General','System Title','string','System-wide Title',1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_api_tokens`
--

LOCK TABLES `user_api_tokens` WRITE;
/*!40000 ALTER TABLE `user_api_tokens` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `user_avatar` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_times`
--

LOCK TABLES `user_times` WRITE;
/*!40000 ALTER TABLE `user_times` DISABLE KEYS */;
INSERT INTO `user_times` VALUES (1,'hnvvoitvd9ap3gflvqeoedad76',1,1456341203,1456343677,1,NULL),(2,'qnnk7725iopp74nidehlmh1lc6',1,1456343764,1456343918,1,NULL),(3,'4409mk1j9fbpt4q90akcng8687',1,1456343939,1456345252,0,NULL);
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
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2a$08$vcXUqu8MXztvvxR07HXGieJEvORAfiIfeQMMXpc/r4uxwKV2xM/Ym','sysclass',0,'postmaster@sysclass.com','Administrator','User',1,NULL,'',NULL,0,0,1,NULL,1,'student','administrator',NULL,0,0,'2016-02-24 19:08:52',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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

-- Dump completed on 2016-02-24 17:21:59
