-- MySQL dump 10.13  Distrib 5.7.20, for macos10.12 (x86_64)
--
-- Host: localhost    Database: WT_Test
-- ------------------------------------------------------
-- Server version	5.7.20

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
-- Table structure for table `artist_designer`
--

DROP TABLE IF EXISTS `artist_designer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artist_designer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_designer` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist_designer`
--

LOCK TABLES `artist_designer` WRITE;
/*!40000 ALTER TABLE `artist_designer` DISABLE KEYS */;
INSERT INTO `artist_designer` VALUES (1,'sdaGsdgsdg',''),(2,'afdbh<dfbfd<b',''),(3,'<fbfbfdb<dfbbfd',''),(4,'hjas H sahjc',''),(5,'grebhwgrb2h3rg',''),(6,'harald den skelÃ¶gde',''),(7,'hararre barra',''),(8,'harald blÃ¥taqnd',''),(9,'ydrustyuugfyug',''),(10,'gv jjhv j ',''),(11,'dsgsdGSg',''),(12,'banksy',''),(13,'banksy',''),(14,'banksy',''),(15,'banksy',''),(16,'banksy',''),(17,'banksy',''),(18,'banksy',''),(19,'asFasfafs',''),(20,'rrrtttyy',''),(21,'rrrtttyyy',''),(22,'rqwerewrq',''),(23,'sadFdsfdf',''),(24,'fredde',''),(25,'dsGsdggsd',''),(26,'dsGsdggsd',''),(27,'dsaDaSDDSA',''),(28,'dsaDaSDD',''),(29,'harry',''),(30,'goran',''),(31,'gert fylking',''),(32,'safA',''),(33,'dwGdsgdsg',''),(34,'asCFA',''),(35,'eeqew',''),(36,'hokan',''),(37,'klas sune',''),(38,'somebody',''),(39,'klas sune',''),(40,'sadfadfad',''),(41,'adam aasma',''),(42,'danielBell',''),(43,'Benny',''),(44,'svalebo',''),(45,'','');
/*!40000 ALTER TABLE `artist_designer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (3,'Estonia'),(4,'Sweden'),(5,'Germany');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries_languages`
--

DROP TABLE IF EXISTS `countries_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries_languages` (
  `country_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`country_id`,`language_id`),
  KEY `FK_countries_languages_idx` (`language_id`),
  CONSTRAINT `FK_countries_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_languages_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries_languages`
--

LOCK TABLES `countries_languages` WRITE;
/*!40000 ALTER TABLE `countries_languages` DISABLE KEYS */;
INSERT INTO `countries_languages` VALUES (3,4),(4,5),(3,6),(3,7),(4,7),(5,8);
/*!40000 ALTER TABLE `countries_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES (1,'euro'),(2,'sek');
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `zipcode` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `country` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phonenumber` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formats`
--

DROP TABLE IF EXISTS `formats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formats`
--

LOCK TABLES `formats` WRITE;
/*!40000 ALTER TABLE `formats` DISABLE KEYS */;
INSERT INTO `formats` VALUES (3,'landscape','landscape'),(4,'portrait','portrait');
/*!40000 ALTER TABLE `formats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longblob NOT NULL,
  `mimetype` varchar(45) NOT NULL,
  `size` int(11) NOT NULL,
  `images_category_id` int(11) NOT NULL,
  `time_of_entry` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `FK_images_categories_idx` (`images_category_id`),
  CONSTRAINT `FK_images_categories` FOREIGN KEY (`images_category_id`) REFERENCES `images_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images_categories`
--

DROP TABLE IF EXISTS `images_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_categories`
--

LOCK TABLES `images_categories` WRITE;
/*!40000 ALTER TABLE `images_categories` DISABLE KEYS */;
INSERT INTO `images_categories` VALUES (4,'slider',NULL),(5,'product',NULL),(6,'productinterior',NULL),(7,'sectionsmall',NULL),(8,'sectionbig',NULL),(9,'sectionmobile',NULL);
/*!40000 ALTER TABLE `images_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_price`
--

DROP TABLE IF EXISTS `item_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_price` (
  `item_id` int(11) NOT NULL,
  `paper_price` decimal(5,2) DEFAULT NULL,
  `technique_price` decimal(5,2) DEFAULT NULL,
  `labour_price` decimal(5,2) DEFAULT NULL,
  `total_price` decimal(5,2) NOT NULL,
  PRIMARY KEY (`item_id`),
  CONSTRAINT `FK_item_prices_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_price`
--

LOCK TABLES `item_price` WRITE;
/*!40000 ALTER TABLE `item_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `print_technique_id` int(11) NOT NULL,
  `printer_id` int(11) NOT NULL,
  `date_of_entry` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_sizes_idx` (`size_id`),
  KEY `FK_print_techniques_idx` (`print_technique_id`),
  KEY `FK_materials_idx` (`material_id`),
  KEY `FK_items_printers_idx` (`printer_id`),
  CONSTRAINT `FK_items_printers` FOREIGN KEY (`printer_id`) REFERENCES `printers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_materials` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_print_techniques` FOREIGN KEY (`print_technique_id`) REFERENCES `print_techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_sizes` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (114,21,22,21,34,'2018-01-11 06:33:50'),(115,22,23,22,35,'2018-01-11 06:33:50'),(116,22,23,23,35,'2018-01-11 06:33:50'),(117,23,22,21,36,'2018-01-11 06:33:50'),(118,24,22,21,36,'2018-01-11 06:33:50'),(119,25,22,21,36,'2018-01-11 06:33:50');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (4,'Estonian'),(5,'Swedish'),(6,'Russian'),(7,'English'),(8,'German');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (22,'moahawk',''),(23,'fager','');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(5,2) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_currencies_idx` (`currency_id`),
  KEY `FK_countries_idx` (`country_id`),
  CONSTRAINT `FK_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_currencies` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `print_techniques`
--

DROP TABLE IF EXISTS `print_techniques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `print_techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technique` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `print_techniques`
--

LOCK TABLES `print_techniques` WRITE;
/*!40000 ALTER TABLE `print_techniques` DISABLE KEYS */;
INSERT INTO `print_techniques` VALUES (21,'digital',''),(22,'rally',''),(23,'reta','');
/*!40000 ALTER TABLE `print_techniques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `printers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `contact_name` varchar(45) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `added_by_user` int(11) DEFAULT NULL,
  `time_of_entry` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_printers_countries_idx` (`country_id`),
  KEY `FK_printers_users_idx` (`added_by_user`),
  CONSTRAINT `FK_printers_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_printers_users` FOREIGN KEY (`added_by_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printers`
--

LOCK TABLES `printers` WRITE;
/*!40000 ALTER TABLE `printers` DISABLE KEYS */;
INSERT INTO `printers` VALUES (31,'sunes','adam4837@hotmail.com','12345','adam',4,13,NULL),(32,'sunes','adam4837@hotmail.com','12345','adam',4,13,NULL),(33,'olle','','0','',3,13,NULL),(34,'olle','','0','',3,13,NULL),(35,'perra','','0','',3,13,NULL),(36,'','','0','',3,13,NULL);
/*!40000 ALTER TABLE `printers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privileges`
--

DROP TABLE IF EXISTS `privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `privileges` varchar(15) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privileges`
--

LOCK TABLES `privileges` WRITE;
/*!40000 ALTER TABLE `privileges` DISABLE KEYS */;
INSERT INTO `privileges` VALUES (3,'admin','Can do everything'),(4,'designer','Can design');
/*!40000 ALTER TABLE `privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_descriptions`
--

DROP TABLE IF EXISTS `product_descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_descriptions` (
  `language_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`language_id`,`product_id`),
  KEY `FK_languages_idx` (`language_id`),
  KEY `FK_products_idx` (`product_id`),
  CONSTRAINT `FK_pd_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pd_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_descriptions`
--

LOCK TABLES `product_descriptions` WRITE;
/*!40000 ALTER TABLE `product_descriptions` DISABLE KEYS */;
INSERT INTO `product_descriptions` VALUES (4,1,'Siin on tema Ãµpilane adam aasma ka teisel pidlil, sel pildil on olemas kahel variandil moahawkil pareile peale vÃµi Ã¼hes variant fageri paperil.','Daniel Eesti Bell'),(6,1,'ÐµÑÑ‚ÑŒ Ð°Ð´Ð½Ð¾Ð¹ Ð²Ð°Ñ€Ð¸Ð°Ð½Ñ‚ Ð² Ð¼Ð¾Ð°Ñ‡ÑˆÐºÐ¸Ð» ','ÑÑ‚Ð° Ð´Ð°Ð½Ð¸ÐµÐ» Ð±ÐµÐ»Ð»'),(6,2,'Ð±ÐµÐ½Ð½Ñ‹ ÐµÑÑ‚Ð¬ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ð¿Ð¾ Ñ€ÑƒÑÑÐºÐ¸','Ð‘ÐµÐ½Ð½Ñ‹ '),(7,1,'on the second photo you can see his apprentice adam aasma','this is daniel bell'),(7,4,'This boat exist only in english and on moahawk paper sizes 90X70','Boat');
/*!40000 ALTER TABLE `product_descriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_of_entry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `formats_id` int(11) DEFAULT NULL,
  `artist_designer_id` int(11) DEFAULT NULL,
  `added_by_user_id` int(11) DEFAULT NULL,
  `date_of_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_artist_designer_idx` (`artist_designer_id`),
  KEY `FK_users_idx` (`added_by_user_id`),
  KEY `FK_formats_idx` (`formats_id`),
  CONSTRAINT `FK_artist_designer` FOREIGN KEY (`artist_designer_id`) REFERENCES `artist_designer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_formats` FOREIGN KEY (`formats_id`) REFERENCES `formats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_users` FOREIGN KEY (`added_by_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'2018-04-17 00:38:49',3,NULL,13,'2018-04-17 00:38:49',13),(2,'2018-04-17 00:45:12',3,NULL,13,'2018-04-17 00:45:12',13),(3,'2018-04-17 00:47:24',3,NULL,13,'2018-04-17 00:47:24',13),(4,'2018-04-17 00:47:24',3,NULL,13,'2018-04-17 00:47:24',13),(5,'2018-04-19 06:21:33',3,NULL,13,'2018-04-19 06:21:33',13),(6,'2018-04-22 10:16:45',3,NULL,13,'2018-04-22 10:16:45',13),(7,'2018-04-22 10:16:50',3,NULL,13,'2018-04-22 10:16:50',13);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_images`
--

DROP TABLE IF EXISTS `products_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_images` (
  `product_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`image_id`),
  KEY `FK_IMAGE_idx` (`image_id`),
  KEY `FK_PRODUCT_idx` (`product_id`,`image_id`),
  CONSTRAINT `FK_IMAGE` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_images`
--

LOCK TABLES `products_images` WRITE;
/*!40000 ALTER TABLE `products_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_items`
--

DROP TABLE IF EXISTS `products_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_items` (
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`material_id`,`size_id`,`country_id`),
  KEY `FK_products_items_items_idx` (`material_id`),
  KEY `FK_product_items_sizes_idx` (`size_id`),
  KEY `FK_product_items_countries_idx` (`country_id`),
  CONSTRAINT `FK_product_items_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_product_items_materials` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_product_items_sizes` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_products_items_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_items`
--

LOCK TABLES `products_items` WRITE;
/*!40000 ALTER TABLE `products_items` DISABLE KEYS */;
INSERT INTO `products_items` VALUES (1,22,21,3),(1,22,23,3),(1,22,24,3),(4,22,24,3),(4,22,25,3),(1,23,22,3),(2,23,22,3);
/*!40000 ALTER TABLE `products_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_sections`
--

DROP TABLE IF EXISTS `products_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_sections` (
  `product_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`section_id`,`country_id`,`language_id`),
  KEY `FK_pcs_categories_idx` (`section_id`),
  KEY `FK_pc_country_idx` (`country_id`),
  KEY `FK_product_sections_language_id_idx` (`language_id`),
  CONSTRAINT `FK_pc_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_pc_sections` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_product_sections_language_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_products_sections_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_sections`
--

LOCK TABLES `products_sections` WRITE;
/*!40000 ALTER TABLE `products_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_descriptions`
--

DROP TABLE IF EXISTS `section_descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_descriptions` (
  `title` varchar(45) DEFAULT NULL,
  `sales_line_header` varchar(45) DEFAULT NULL,
  `sales_line_paragraph` varchar(45) DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `section_description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`language_id`,`section_id`),
  KEY `FK_section_description_languages_idx` (`language_id`),
  KEY `FK_section_descriptions_sections_idx` (`section_id`),
  CONSTRAINT `FK_section_description_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_section_descriptions_sections` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_descriptions`
--

LOCK TABLES `section_descriptions` WRITE;
/*!40000 ALTER TABLE `section_descriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_descriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desktop_big_pic_id` int(11) DEFAULT NULL,
  `desktop_small_pic_id` int(11) DEFAULT NULL,
  `mobile_pic_id` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_sections_images_idx` (`desktop_big_pic_id`),
  KEY `FK_desktop_small_sections_images_idx` (`desktop_small_pic_id`),
  KEY `FK_mobile_sections_images_idx` (`mobile_pic_id`),
  KEY `FK_sections_users_idx` (`created_by_user_id`),
  CONSTRAINT `FK_sections_images_big` FOREIGN KEY (`desktop_big_pic_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_sections_images_mobile` FOREIGN KEY (`mobile_pic_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_sections_images_small` FOREIGN KEY (`desktop_small_pic_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_sections_users` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sizes` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (21,'200x200',''),(22,'2222',''),(23,'70x50',''),(24,'90x70',''),(25,'110x90','');
/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider_description`
--

DROP TABLE IF EXISTS `slider_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider_description` (
  `id` int(11) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `salesline` varchar(45) DEFAULT NULL,
  `slider_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sliders_slider_description_idx` (`slider_id`),
  KEY `FK_countries_slider_description_idx` (`country_id`),
  KEY `FK_languages_slider_description_idx` (`language_id`),
  CONSTRAINT `FK_countries_slider_description` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_languages_slider_description` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_sliders_slider_description` FOREIGN KEY (`slider_id`) REFERENCES `sliders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider_description`
--

LOCK TABLES `slider_description` WRITE;
/*!40000 ALTER TABLE `slider_description` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desktop_image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by_user_id` int(11) NOT NULL,
  `mobile_image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_idx` (`product_id`),
  KEY `FK_user_idx` (`added_by_user_id`),
  KEY `FK_image_idx` (`desktop_image_id`),
  KEY `FK_mobile_image_slider_idx` (`mobile_image_id`),
  CONSTRAINT `FK_image_slider` FOREIGN KEY (`desktop_image_id`) REFERENCES `images` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mobile_image_slider` FOREIGN KEY (`mobile_image_id`) REFERENCES `images` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_product_slider` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_slider` FOREIGN KEY (`added_by_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_country`
--

DROP TABLE IF EXISTS `user_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_country` (
  `user_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`country_id`),
  KEY `FK_country_idx` (`country_id`),
  CONSTRAINT `FK_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_country`
--

LOCK TABLES `user_country` WRITE;
/*!40000 ALTER TABLE `user_country` DISABLE KEYS */;
INSERT INTO `user_country` VALUES (13,3),(16,3),(18,3),(13,4),(16,4),(18,4),(18,5),(19,5);
/*!40000 ALTER TABLE `user_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `passwordsalt` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `privileges` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName_UNIQUE` (`username`),
  KEY `FK_Privileges_idx` (`privileges`),
  CONSTRAINT `FK_Privileges` FOREIGN KEY (`privileges`) REFERENCES `privileges` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (13,'admin','5f67816293e5bf4d22542b270efeb43c','jvdnIJndavjnad','admin','admin',3),(14,'asd','cd379019bce53273640ca247f81459a0','5a3c67b5e47ad','ad','adfg',3),(16,'ghj','e6d71b082e240248bcbdf0a888512b4f','5a3c6875bd33e','ghj','ghj',3),(18,'adam','e21dcb49a69a575159ee0120b9bae8ed','5a3c6bf1b6f03','freddan','aronson',3),(19,'aaa','fcb21df88d36add48ae83ca2b13d15cb','5a3e6cfab279a','aaa','aaa',3);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-24 19:42:13
