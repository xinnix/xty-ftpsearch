-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: ftpsearch
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1

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
-- Table structure for table `cat`
--

DROP TABLE IF EXISTS `cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` varchar(50) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `ipid` int(11) DEFAULT NULL,
  `acctime` mediumtext,
  PRIMARY KEY (`id`),
  KEY `fk_cat_ftpinfo` (`ipid`),
  KEY `fk_cat_cat1` (`pid`),
  CONSTRAINT `fk_cat_cat1` FOREIGN KEY (`pid`) REFERENCES `cat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cat_ftpinfo` FOREIGN KEY (`ipid`) REFERENCES `ftpinfo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat`
--

LOCK TABLES `cat` WRITE;
/*!40000 ALTER TABLE `cat` DISABLE KEYS */;
INSERT INTO `cat` VALUES (31,NULL,NULL,1,NULL),(32,'',31,1,NULL),(33,'abc',31,1,NULL);
/*!40000 ALTER TABLE `cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(50) DEFAULT NULL,
  `postfix` varchar(10) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `ipid` int(11) DEFAULT NULL,
  `acctime` mediumtext,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_files_ftpinfo1` (`ipid`),
  KEY `fk_files_cat1` (`pid`),
  CONSTRAINT `fk_files_cat1` FOREIGN KEY (`pid`) REFERENCES `cat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_files_ftpinfo1` FOREIGN KEY (`ipid`) REFERENCES `ftpinfo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (1,'OReilly.Linux.in.a.Nutshell.5th.Edition.Jul.2005.c','chm',31,1,NULL,NULL),(2,'vim.pdf','pdf',33,1,NULL,NULL),(3,'xx','xx',33,1,NULL,NULL),(4,'ftpsearchsdesignandimplement.pdf','pdf',31,1,NULL,NULL),(5,'vim.pdf','pdf',31,1,NULL,NULL),(6,'xx','xx',31,1,NULL,NULL),(7,'æ´»ç€å°±ä¸ºæ”¹å˜ä¸–ç•Œ(å®Œæ•´ç‰ˆ).pdf','pdf',31,1,NULL,NULL);
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ftpinfo`
--

DROP TABLE IF EXISTS `ftpinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ftpinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` varchar(15) DEFAULT NULL,
  `port` int(11) DEFAULT '21',
  `user` varchar(45) DEFAULT 'anonymous',
  `pw` varchar(45) DEFAULT NULL,
  `acc` tinyint(1) NOT NULL DEFAULT '0',
  `indb` tinyint(1) DEFAULT '0',
  `info` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='记录ftp站点相关信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ftpinfo`
--

LOCK TABLES `ftpinfo` WRITE;
/*!40000 ALTER TABLE `ftpinfo` DISABLE KEYS */;
INSERT INTO `ftpinfo` VALUES (1,'127.0.0.1',21,'anonymous',NULL,1,1,'某测试的ftp');
/*!40000 ALTER TABLE `ftpinfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-09 14:48:13
