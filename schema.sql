-- MySQL dump 10.13  Distrib 5.5.20, for osx10.6 (i386)
--
-- Host: localhost    Database: onlyin
-- ------------------------------------------------------
-- Server version	5.5.23

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subins`
--

DROP TABLE IF EXISTS `subins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subins` (
  `subin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`subin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subins`
--

LOCK TABLES `subins` WRITE;
/*!40000 ALTER TABLE `subins` DISABLE KEYS */;
INSERT INTO `subins` VALUES (1,'Acapulco','acapulco'),(2,'Aguascalientes','aguascalientes'),(3,'Austin','austin'),(4,'Calgary','calgary'),(5,'Charlotte','charlotte'),(6,'Chicago','chicago'),(7,'Chihuahua','chihuahua'),(8,'Ciudad Juárez','ciudad-juarez'),(9,'Columbus','columbus'),(10,'Culiacán','culiacan'),(11,'Dallas','dallas'),(12,'Detroit','detroit'),(13,'Ecatepec de Morelos','ecatepec-de-morelos'),(14,'Edmonton','edmonton'),(15,'El Paso','el-paso'),(16,'Fort Worth','fort-worth'),(17,'Guadalajara','guadalajara'),(18,'Guadalupe','guadalupe'),(19,'Guatemala City','guatemala-city'),(20,'Havana','havana'),(21,'Hermosillo','hermosillo'),(22,'Houston','houston'),(23,'Indianapolis','indianapolis'),(24,'Jacksonville','jacksonville'),(25,'Kingston','kingston'),(26,'León','leon'),(27,'Los Angeles','los-angeles'),(28,'Memphis','memphis'),(29,'Mérida','mérida'),(30,'Mexicali','mexicali'),(31,'Mexico City','mexico-city'),(32,'Mississauga','mississauga'),(33,'Monterrey','monterrey'),(34,'Montréal','montréal'),(35,'Naucalpan','naucalpan'),(36,'New York City','new-york-city'),(37,'Nezahualcóyotl','nezahualcoyotl'),(38,'Ottawa','ottawa'),(39,'Philadelphia','philadelphia'),(40,'Phoenix','phoenix'),(41,'Port-au-Prince','port-au-prince'),(42,'Puebla','puebla'),(43,'Saltillo','saltillo'),(44,'San Antonio','san-antonio'),(45,'San Diego','san-diego'),(46,'San Francisco','san-francisco'),(47,'San Jose','san-jose'),(48,'San Luis Potosí','san-luis-potosi'),(49,'Santiago','santiago'),(50,'Santo Domingo','santo-domingo'),(51,'Tegucigalpa','tegucigalpa'),(52,'Tijuana','tijuana'),(53,'Tlalnepantla de Baz','tlalnepantla-de-baz'),(54,'Toronto','toronto'),(55,'Winnipeg','winnipeg'),(56,'Zapopan','zapopan');
/*!40000 ALTER TABLE `subins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `stamp` int(11) NOT NULL,
  `last_login_at` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-22 22:01:53
