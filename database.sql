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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Everything is an item, schools, PCs, RAM, etc.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_item`
--

LOCK TABLES `asd_item` WRITE;
/*!40000 ALTER TABLE `asd_item` DISABLE KEYS */;
INSERT INTO `asd_item` VALUES (1,'chernobyl');
INSERT INTO `asd_item` VALUES (3,'poli');
INSERT INTO `asd_item` VALUES (2,'table1');
/*!40000 ALTER TABLE `asd_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_location`
--

DROP TABLE IF EXISTS `asd_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_location` (
  `location_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_when` datetime DEFAULT NULL,
  `location_parent` int(10) unsigned DEFAULT NULL COMMENT 'NULL if primary, location_ID for log reasons',
  `container` int(10) unsigned NOT NULL COMMENT 'Shelve object_ID',
  `item` int(10) unsigned NOT NULL COMMENT 'Shelved object_ID',
  PRIMARY KEY (`location_ID`),
  KEY `part` (`container`),
  KEY `of` (`item`),
  KEY `parent` (`location_parent`),
  KEY `partof_when` (`location_when`),
  CONSTRAINT `asd_location_ibfk_1` FOREIGN KEY (`container`) REFERENCES `asd_item` (`item_ID`),
  CONSTRAINT `asd_location_ibfk_2` FOREIGN KEY (`item`) REFERENCES `asd_item` (`item_ID`) ON DELETE CASCADE,
  CONSTRAINT `asd_location_ibfk_3` FOREIGN KEY (`location_parent`) REFERENCES `asd_location` (`location_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Where is the item';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_location`
--

LOCK TABLES `asd_location` WRITE;
/*!40000 ALTER TABLE `asd_location` DISABLE KEYS */;
INSERT INTO `asd_location` VALUES (1,NULL,NULL,3,1);
/*!40000 ALTER TABLE `asd_location` ENABLE KEYS */;
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
  `property_parent` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`property_ID`),
  UNIQUE KEY `property_uid` (`property_uid`),
  KEY `property_parent` (`property_parent`),
  CONSTRAINT `asd_property_ibfk_1` FOREIGN KEY (`property_parent`) REFERENCES `asd_property` (`property_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_property`
--

LOCK TABLES `asd_property` WRITE;
/*!40000 ALTER TABLE `asd_property` DISABLE KEYS */;
INSERT INTO `asd_property` VALUES (1,'name','Name',NULL);
/*!40000 ALTER TABLE `asd_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_reference`
--

DROP TABLE IF EXISTS `asd_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_reference` (
  `item_ID` int(10) unsigned NOT NULL,
  `property_ID` int(10) unsigned NOT NULL,
  `reference_value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`item_ID`,`property_ID`),
  KEY `property_ID` (`property_ID`),
  CONSTRAINT `asd_reference_ibfk_1` FOREIGN KEY (`item_ID`) REFERENCES `asd_item` (`item_ID`) ON DELETE CASCADE,
  CONSTRAINT `asd_reference_ibfk_2` FOREIGN KEY (`property_ID`) REFERENCES `asd_property` (`property_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_reference`
--

LOCK TABLES `asd_reference` WRITE;
/*!40000 ALTER TABLE `asd_reference` DISABLE KEYS */;
INSERT INTO `asd_reference` VALUES (1,1,'Aula Chernobyl');
INSERT INTO `asd_reference` VALUES (3,1,'Politecnico di Torino');
/*!40000 ALTER TABLE `asd_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asd_user`
--

DROP TABLE IF EXISTS `asd_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_user` (
  `user_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username',
  `user_role` enum('admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Activation',
  `user_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'First name',
  `user_surname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Surname',
  `user_password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `user_uid` (`user_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asd_user`
--

LOCK TABLES `asd_user` WRITE;
/*!40000 ALTER TABLE `asd_user` DISABLE KEYS */;
INSERT INTO `asd_user` VALUES (1,'asdman','admin',1,'Asd','Man','!');
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

-- Dump completed on 2016-12-22  2:47:45
