CREATE DATABASE  IF NOT EXISTS `sysclass_demo` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sysclass_demo`;
-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: sysclass_demo
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

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
) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_payment_transacao`
--

LOCK TABLES `mod_payment_transacao` WRITE;
/*!40000 ALTER TABLE `mod_payment_transacao` DISABLE KEYS */;
INSERT INTO `mod_payment_transacao` VALUES (433,3,'{\"TOKEN\":\"EC-9ER04775AB9890835\",\"TIMESTAMP\":\"2016-01-25T17:09:04Z\",\"CORRELATIONID\":\"bf68f38b94238\",\"ACK\":\"Success\",\"VERSION\":\"108.0\",\"BUILD\":\"18308778\"}','EC-9ER04775AB9890835','checked'),(434,3,'{\"TOKEN\":\"EC-7XF61087544454432\",\"TIMESTAMP\":\"2016-01-25T17:32:09Z\",\"CORRELATIONID\":\"558cb548fee6\",\"ACK\":\"Success\",\"VERSION\":\"108.0\",\"BUILD\":\"18308778\"}','EC-7XF61087544454432','cancel');
/*!40000 ALTER TABLE `mod_payment_transacao` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-25 16:37:02
