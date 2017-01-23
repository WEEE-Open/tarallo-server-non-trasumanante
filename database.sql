-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: localhost
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-0+deb8u1

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
-- Table structure for table `asd_item`
--

DROP TABLE IF EXISTS `asd_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_item` (
  `item_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_uid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'WEEE custom identifier',
  PRIMARY KEY (`item_ID`),
  UNIQUE KEY `item_uid` (`item_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Everything is an item, schools, PCs, RAM, etc.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_item`
--

LOCK TABLES `asd_item` WRITE;
/*!40000 ALTER TABLE `asd_item` DISABLE KEYS */;
INSERT INTO `asd_item` VALUES (5,'apple-graphite-1-17'),(1,'chernobyl'),(3,'poli'),(4,'rosario-home'),(2,'table1');
/*!40000 ALTER TABLE `asd_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_itemmap`
--

DROP TABLE IF EXISTS `asd_itemmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_itemmap` (
  `itemmap_contained` int(10) unsigned NOT NULL COMMENT 'Item shelved',
  `itemmap_container` int(10) unsigned NOT NULL COMMENT 'Item operating as a shelve',
  `itemmap_creation_user` int(10) unsigned NOT NULL COMMENT 'Logged-in user who performed operation',
  `itemmap_creation_date` datetime NOT NULL COMMENT 'Usually NOW()',
  `itemmap_formal_user` int(10) unsigned NOT NULL COMMENT 'Who commissioned this location',
  `itemmap_formal_date` datetime NOT NULL COMMENT 'Formal date in which the location can be established',
  PRIMARY KEY (`itemmap_contained`,`itemmap_container`),
  KEY `location_creation_user` (`itemmap_creation_user`),
  KEY `location_formal_user` (`itemmap_formal_user`),
  KEY `itemmap_subject` (`itemmap_contained`),
  KEY `itemmap_creation_date` (`itemmap_creation_date`),
  KEY `itemmap_formal_date` (`itemmap_formal_date`),
  KEY `itemmap_container` (`itemmap_container`),
  CONSTRAINT `asd_itemmap_ibfk_2` FOREIGN KEY (`itemmap_container`) REFERENCES `asd_item` (`item_ID`),
  CONSTRAINT `asd_itemmap_ibfk_3` FOREIGN KEY (`itemmap_creation_user`) REFERENCES `asd_user` (`user_ID`),
  CONSTRAINT `asd_itemmap_ibfk_4` FOREIGN KEY (`itemmap_formal_user`) REFERENCES `asd_user` (`user_ID`),
  CONSTRAINT `asd_itemmap_ibfk_5` FOREIGN KEY (`itemmap_contained`) REFERENCES `asd_item` (`item_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Where is the item';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_itemmap`
--

LOCK TABLES `asd_itemmap` WRITE;
/*!40000 ALTER TABLE `asd_itemmap` DISABLE KEYS */;
INSERT INTO `asd_itemmap` VALUES (1,3,1,'2016-12-26 00:00:00',1,'2016-12-26 00:00:00'),(2,1,1,'2017-01-21 02:00:34',1,'2017-01-21 02:00:34'),(5,4,2,'2017-01-23 00:47:51',3,'2017-01-23 00:47:51');
/*!40000 ALTER TABLE `asd_itemmap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_itemmaplog`
--

DROP TABLE IF EXISTS `asd_itemmaplog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_itemmaplog` (
  `itemmaplog_ID` int(10) unsigned NOT NULL,
  `itemmaplog_contained` int(10) unsigned NOT NULL,
  `itemmaplog_container` int(10) unsigned NOT NULL,
  `itemmaplog_date` int(11) NOT NULL,
  PRIMARY KEY (`itemmaplog_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_itemmaplog`
--

LOCK TABLES `asd_itemmaplog` WRITE;
/*!40000 ALTER TABLE `asd_itemmaplog` DISABLE KEYS */;
/*!40000 ALTER TABLE `asd_itemmaplog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_property`
--

DROP TABLE IF EXISTS `asd_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_property` (
  `property_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `property_uid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `property_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`property_ID`),
  UNIQUE KEY `property_uid` (`property_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sort of categories';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_property`
--

LOCK TABLES `asd_property` WRITE;
/*!40000 ALTER TABLE `asd_property` DISABLE KEYS */;
INSERT INTO `asd_property` VALUES (1,'name','nome'),(2,'capacity','capacità'),(3,'ram','RAM'),(5,'note','note/descrizione');
/*!40000 ALTER TABLE `asd_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_propertymap`
--

DROP TABLE IF EXISTS `asd_propertymap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_propertymap` (
  `propertymap_parent` int(10) unsigned NOT NULL,
  `propertymap_child` int(10) unsigned NOT NULL,
  PRIMARY KEY (`propertymap_parent`,`propertymap_child`),
  KEY `propertymap_child` (`propertymap_child`),
  CONSTRAINT `asd_propertymap_ibfk_1` FOREIGN KEY (`propertymap_parent`) REFERENCES `asd_property` (`property_ID`),
  CONSTRAINT `asd_propertymap_ibfk_2` FOREIGN KEY (`propertymap_child`) REFERENCES `asd_property` (`property_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Property tree';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_propertymap`
--

LOCK TABLES `asd_propertymap` WRITE;
/*!40000 ALTER TABLE `asd_propertymap` DISABLE KEYS */;
INSERT INTO `asd_propertymap` VALUES (3,2);
/*!40000 ALTER TABLE `asd_propertymap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_spec`
--

DROP TABLE IF EXISTS `asd_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_spec` (
  `spec_value` text COLLATE utf8mb4_unicode_ci COMMENT 'Property value',
  `spec_creation_user` int(10) unsigned NOT NULL COMMENT 'User who created this entry',
  `spec_creation_date` datetime NOT NULL COMMENT 'Creation date',
  `spec_lastedit_user` int(10) unsigned NOT NULL,
  `spec_lastedit_date` datetime NOT NULL,
  `item_ID` int(10) unsigned NOT NULL COMMENT 'Subject item',
  `property_ID` int(10) unsigned NOT NULL COMMENT 'Property applied',
  PRIMARY KEY (`item_ID`,`property_ID`),
  KEY `spec_creation_user` (`spec_creation_user`),
  KEY `spec_creation_date` (`spec_creation_date`),
  KEY `property_ID` (`property_ID`),
  KEY `spec_lastedit_user` (`spec_lastedit_user`),
  KEY `spec_lastedit_date` (`spec_lastedit_date`),
  CONSTRAINT `asd_spec_ibfk_3` FOREIGN KEY (`spec_creation_user`) REFERENCES `asd_user` (`user_ID`),
  CONSTRAINT `asd_spec_ibfk_4` FOREIGN KEY (`item_ID`) REFERENCES `asd_item` (`item_ID`) ON DELETE CASCADE,
  CONSTRAINT `asd_spec_ibfk_5` FOREIGN KEY (`property_ID`) REFERENCES `asd_property` (`property_ID`),
  CONSTRAINT `asd_spec_ibfk_6` FOREIGN KEY (`spec_lastedit_user`) REFERENCES `asd_user` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Specification: Item with a property and an associated value';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_spec`
--

LOCK TABLES `asd_spec` WRITE;
/*!40000 ALTER TABLE `asd_spec` DISABLE KEYS */;
INSERT INTO `asd_spec` VALUES ('Laboratorio Chernobyl',1,'2016-12-20 00:00:00',1,'2017-01-23 01:14:54',1,1),('Politecnico di Torino',1,'2016-12-26 16:28:12',1,'2016-12-27 12:33:31',3,1),('È il laboratorio.\n\nasd',1,'2017-01-23 00:28:04',1,'2017-01-23 01:18:46',3,5);
/*!40000 ALTER TABLE `asd_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_user`
--

DROP TABLE IF EXISTS `asd_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_user` (
  `user_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique username',
  `user_role` enum('admin','donator') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Permissions are inherited from every role',
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Activation',
  `user_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Privacy reasons',
  `user_name` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'First name',
  `user_surname` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Surname',
  `user_displayname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `user_uid` (`user_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Users and organizations';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_user`
--

LOCK TABLES `asd_user` WRITE;
/*!40000 ALTER TABLE `asd_user` DISABLE KEYS */;
INSERT INTO `asd_user` VALUES (1,'asdman','admin',1,1,'WEEE Open',NULL,'WEEE Open',NULL),(2,'rosario.antoci','admin',0,1,'Rosario','Antoci','','!'),(3,'boh','donator',0,1,'Come si chiama?',NULL,NULL,'!');
/*!40000 ALTER TABLE `asd_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-23  1:31:14
