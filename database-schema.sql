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
-- Table structure for table `asd_itemmap`
--

DROP TABLE IF EXISTS `asd_itemmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_itemmap` (
  `itemmap_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemmap_subject` int(10) unsigned NOT NULL COMMENT 'Item shelved',
  `itemmap_container` int(10) unsigned NOT NULL COMMENT 'Item operating as a shelve',
  `itemmap_creation_user` int(10) unsigned NOT NULL COMMENT 'Logged-in user who performed operation',
  `itemmap_creation_date` datetime NOT NULL COMMENT 'Usually NOW()',
  `itemmap_formal_user` int(10) unsigned NOT NULL COMMENT 'Who commissioned this location',
  `itemmap_formal_date` datetime NOT NULL COMMENT 'Formal date in which the location can be established',
  `itemmap_parent` int(10) unsigned DEFAULT NULL COMMENT 'NULL if primary, location_ID for log reasons',
  PRIMARY KEY (`itemmap_ID`),
  KEY `location_creation_user` (`itemmap_creation_user`),
  KEY `location_formal_user` (`itemmap_formal_user`),
  KEY `itemmap_subject` (`itemmap_subject`),
  KEY `itemmap_creation_date` (`itemmap_creation_date`),
  KEY `itemmap_formal_date` (`itemmap_formal_date`),
  KEY `itemmap_parent` (`itemmap_parent`),
  KEY `itemmap_container` (`itemmap_container`),
  CONSTRAINT `asd_itemmap_ibfk_1` FOREIGN KEY (`itemmap_subject`) REFERENCES `asd_item` (`item_ID`) ON DELETE CASCADE,
  CONSTRAINT `asd_itemmap_ibfk_2` FOREIGN KEY (`itemmap_container`) REFERENCES `asd_item` (`item_ID`),
  CONSTRAINT `asd_itemmap_ibfk_3` FOREIGN KEY (`itemmap_creation_user`) REFERENCES `asd_user` (`user_ID`),
  CONSTRAINT `asd_itemmap_ibfk_4` FOREIGN KEY (`itemmap_formal_user`) REFERENCES `asd_user` (`user_ID`),
  CONSTRAINT `asd_itemmap_ibfk_5` FOREIGN KEY (`itemmap_parent`) REFERENCES `asd_itemmap` (`itemmap_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Where is the item';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sort of categories';
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `asd_user`
--

DROP TABLE IF EXISTS `asd_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asd_user` (
  `user_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique username',
  `user_role` enum('admin') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Permissions are inherited from every role',
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Activation',
  `user_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Privacy reasons',
  `user_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'First name',
  `user_surname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Surname',
  `user_password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `user_uid` (`user_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Users and organizations';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-26 16:38:23
