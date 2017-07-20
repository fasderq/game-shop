-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: gameshop
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.17.04.1

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
-- Table structure for table `aksessuare`
--

DROP TABLE IF EXISTS `aksessuare`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aksessuare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aksessuare`
--

LOCK TABLES `aksessuare` WRITE;
/*!40000 ALTER TABLE `aksessuare` DISABLE KEYS */;
INSERT INTO `aksessuare` VALUES (1,'A1'),(2,'A2'),(4,'S1'),(5,'S2');
/*!40000 ALTER TABLE `aksessuare` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `console_name_id_name` int(11) NOT NULL,
  PRIMARY KEY (`id_category`,`console_name_id_name`),
  KEY `fk_category_console_name_idx` (`console_name_id_name`),
  CONSTRAINT `fk_category_console_name` FOREIGN KEY (`console_name_id_name`) REFERENCES `console_name` (`id_name`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `console`
--

DROP TABLE IF EXISTS `console`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `console`
--

LOCK TABLES `console` WRITE;
/*!40000 ALTER TABLE `console` DISABLE KEYS */;
INSERT INTO `console` VALUES (1,'к',1,34),(2,'PS4',1,200),(3,'g',NULL,44),(4,'g',NULL,44),(5,'gg',NULL,4455),(6,'345',NULL,45),(15,'Плойка',1,500);
/*!40000 ALTER TABLE `console` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `console_accessory`
--

DROP TABLE IF EXISTS `console_accessory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_accessory` (
  `console_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  PRIMARY KEY (`console_id`,`accessory_id`),
  KEY `accessory_console_ref` (`accessory_id`),
  KEY `console_id` (`console_id`),
  CONSTRAINT `accessory_console_ref` FOREIGN KEY (`accessory_id`) REFERENCES `aksessuare` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `console_ accessory_ref` FOREIGN KEY (`console_id`) REFERENCES `console` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `console_accessory`
--

LOCK TABLES `console_accessory` WRITE;
/*!40000 ALTER TABLE `console_accessory` DISABLE KEYS */;
INSERT INTO `console_accessory` VALUES (1,1),(2,1),(1,2),(2,2);
/*!40000 ALTER TABLE `console_accessory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `console_name`
--

DROP TABLE IF EXISTS `console_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_name` (
  `id_name` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `console_name`
--

LOCK TABLES `console_name` WRITE;
/*!40000 ALTER TABLE `console_name` DISABLE KEYS */;
INSERT INTO `console_name` VALUES (1,'Плойка'),(2,'XBOX one'),(3,'XBOX 360');
/*!40000 ALTER TABLE `console_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `console_specification`
--

DROP TABLE IF EXISTS `console_specification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `console_specification` (
  `console_id` int(11) NOT NULL,
  `specification_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`console_id`,`specification_id`),
  KEY `specification_idx` (`specification_id`),
  KEY `console_idx` (`console_id`),
  CONSTRAINT `console_specification_ref` FOREIGN KEY (`console_id`) REFERENCES `console` (`id`),
  CONSTRAINT `specification_console_ref` FOREIGN KEY (`specification_id`) REFERENCES `specification` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=urf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `console_specification`
--

LOCK TABLES `console_specification` WRITE;
/*!40000 ALTER TABLE `console_specification` DISABLE KEYS */;
/*!40000 ALTER TABLE `console_specification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `content` longtext,
  `keywords` text,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_parent_ref` (`parent_id`),
  CONSTRAINT `page_parent_ref` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,'12','Титл',1,'Контент','ыфыва',1,1),(2,'1','Титл',2,'Контент','3',1,2);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties` (
  `id_properties` int(11) NOT NULL AUTO_INCREMENT,
  `properties` varchar(255) DEFAULT NULL,
  `category_id_category` int(11) NOT NULL,
  `category_console_name_id_name` int(11) NOT NULL,
  PRIMARY KEY (`id_properties`,`category_id_category`,`category_console_name_id_name`),
  KEY `fk_properties_category1_idx` (`category_id_category`,`category_console_name_id_name`),
  CONSTRAINT `fk_properties_category1` FOREIGN KEY (`category_id_category`, `category_console_name_id_name`) REFERENCES `category` (`id_category`, `console_name_id_name`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specification`
--

DROP TABLE IF EXISTS `specification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specification`
--

LOCK TABLES `specification` WRITE;
/*!40000 ALTER TABLE `specification` DISABLE KEYS */;
/*!40000 ALTER TABLE `specification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'root@localhost','Admin','123456',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-20 11:58:57
