-- MySQL dump 10.13  Distrib 5.7.16, for osx10.12 (x86_64)
--
-- Host: localhost    Database: callcenter
-- ------------------------------------------------------
-- Server version	5.7.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ç”¨æˆ·ç¼–å·',
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色|权限',
  `type` int(11) unsigned NOT NULL COMMENT '类型:1角色(岗位),2权限',
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('1',1,'角色编号-1',NULL,NULL,1493278121,1493278121);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `call_record`
--

DROP TABLE IF EXISTS `call_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_record` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `active_call_uid` int(10) unsigned NOT NULL DEFAULT '0',
 `unactive_call_uid` int(10) unsigned NOT NULL DEFAULT '0',
 `active_account` varchar(100) NOT NULL COMMENT '主叫账号',
 `unactive_account` varchar(100) NOT NULL COMMENT '被叫账号',
 `active_nickname` varchar(50) NOT NULL DEFAULT '*' COMMENT '主叫昵称',
 `unactive_nickname` varchar(50) NOT NULL DEFAULT '*' COMMENT '被叫昵称',
 `call_by_same_times` int(10) unsigned NOT NULL DEFAULT '0',
 `type` int(10) unsigned NOT NULL DEFAULT '0',
 `contact_number` varchar(64) NOT NULL DEFAULT '',
 `unactive_contact_number` char(15) NOT NULL COMMENT '被叫电话',
 `status` int(10) unsigned NOT NULL DEFAULT '0',
 `record_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '记录状态(1:正常, 2:黑名单, 3:垃圾桶)',
 `call_time` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call_record`
--

LOCK TABLES `call_record` WRITE;
/*!40000 ALTER TABLE `call_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `call_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1成功,2密码错误,3验证错误,4账号错误',
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_ip` char(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `unlock_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unlock_uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (77,0,4,'2017-04-13 14:35:17','::1','2017-04-13 14:35:17',0),(78,0,4,'2017-04-13 14:41:09','::1','2017-04-13 14:41:09',0),(79,1,2,'2017-04-13 14:41:14','::1','2017-04-13 14:41:15',0),(80,1,2,'2017-04-13 14:46:38','::1','2017-04-13 14:46:39',0),(81,1,2,'2017-04-15 11:04:26','::1','2017-04-15 11:04:27',0),(82,0,4,'2017-04-17 11:33:47','::1','2017-04-17 11:33:47',0),(83,0,4,'2017-04-17 11:34:59','::1','2017-04-17 11:34:59',0),(84,0,4,'2017-04-17 13:11:18','::1','2017-04-17 13:11:18',0),(85,0,4,'2017-04-17 13:32:28','::1','2017-04-17 13:32:28',0),(86,0,4,'2017-04-17 13:34:09','::1','2017-04-17 13:34:09',0),(87,0,4,'2017-04-19 11:09:37','::1','2017-04-19 11:09:37',0),(88,1,2,'2017-04-20 14:53:42','::1','2017-04-20 14:53:42',0),(89,1,2,'2017-04-20 14:53:56','::1','2017-04-20 14:53:57',0),(90,1,2,'2017-04-20 14:54:13','::1','2017-04-20 14:54:14',0),(91,1,2,'2017-04-20 14:54:44','::1','2017-04-20 14:54:45',0),(92,1,2,'2017-04-20 14:57:07','::1','2017-04-20 14:57:07',0),(93,1,2,'2017-04-23 15:35:52','::1','2017-04-23 15:35:53',0),(94,1,2,'2017-04-23 15:36:01','::1','2017-04-23 15:36:01',0),(95,1,2,'2017-04-23 15:36:57','::1','2017-04-23 15:36:57',0),(96,1,2,'2017-04-23 15:37:04','::1','2017-04-23 15:37:05',0),(97,1,2,'2017-04-23 15:37:14','::1','2017-04-23 15:37:15',0),(98,1,2,'2017-04-26 14:32:16','::1','2017-04-26 14:32:16',0),(99,1,2,'2017-04-26 14:32:26','::1','2017-04-26 14:32:27',0),(100,1,2,'2017-04-26 14:32:42','::1','2017-04-26 14:32:43',0),(101,1,2,'2017-04-26 14:32:58','::1','2017-04-26 14:32:59',0),(102,1,2,'2017-04-26 14:33:16','::1','2017-04-26 14:33:17',0),(103,1,2,'2017-04-26 14:34:41','::1','2017-04-26 14:34:42',0),(104,1,2,'2017-04-26 14:36:36','::1','2017-04-26 14:36:36',0),(105,1,2,'2017-04-26 14:36:42','::1','2017-04-26 14:36:43',0),(106,1,2,'2017-04-26 14:38:18','::1','2017-04-26 14:38:18',0),(107,1,2,'2017-04-26 14:38:26','::1','2017-04-26 14:38:27',0),(108,1,2,'2017-04-27 16:06:35','::1','2017-04-27 16:06:36',0),(109,1,2,'2017-04-27 16:15:35','::1','2017-04-27 16:15:35',0),(110,1,2,'2017-04-27 16:15:54','::1','2017-04-27 16:15:55',0),(111,1,2,'2017-04-27 16:21:42','::1','2017-04-27 16:21:42',0),(112,1,2,'2017-04-27 16:27:36','::1','2017-04-27 16:27:37',0),(113,1,2,'2017-04-27 16:27:36','::1','2017-04-27 16:27:37',0),(114,1,2,'2017-04-27 16:39:39','::1','2017-04-27 16:39:40',0),(115,1,2,'2017-04-27 16:40:03','::1','2017-04-27 16:40:04',0);
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manager`
--

DROP TABLE IF EXISTS `manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(64) DEFAULT NULL,
  `password` varchar(64) NOT NULL DEFAULT '',
  `account` text,
  `nickname` text,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` text,
  `login_ip` varchar(64) NOT NULL DEFAULT '',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0',
  `update_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_at` int(11) DEFAULT '0',
  `update_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manager`
--

LOCK TABLES `manager` WRITE;
/*!40000 ALTER TABLE `manager` DISABLE KEYS */;
/*!40000 ALTER TABLE `manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `remark` text,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0',
  `update_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_at` int(11) DEFAULT '0',
  `update_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'admin','所有权限',0,0,1493278121,1493278121);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `account` text,
  `nickname` text,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `calling_times` int(10) unsigned NOT NULL DEFAULT '0',
  `called_times` int(10) unsigned NOT NULL DEFAULT '0',
  `limit_times` int(10) unsigned NOT NULL DEFAULT '0',
  `un_call_number` int(10) unsigned NOT NULL DEFAULT '0',
  `un_call_by_same_number` int(10) unsigned NOT NULL DEFAULT '0',
  `long_time` int(10) unsigned NOT NULL DEFAULT '0',
  `country_code` int(10) unsigned DEFAULT NULL,
  `phone_number` varchar(64) NOT NULL DEFAULT '',
  `urgent_contact_number_one` varchar(64) NOT NULL DEFAULT '',
  `urgent_contact_one_country_code` int(10) unsigned DEFAULT NULL,
  `urgent_contact_number_two` int(10) unsigned NOT NULL DEFAULT '0',
  `urgent_contact_two_country_code` int(10) unsigned DEFAULT NULL,
  `urgent_contact_person_one` text,
  `urgent_contact_person_two` text,
  `telegram_number` varchar(64) NOT NULL DEFAULT '',
  `telegram_country_code` int(10) unsigned DEFAULT NULL,
  `potato_number` varchar(64) NOT NULL DEFAULT '',
  `potato_country_code` int(10) unsigned DEFAULT NULL,
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'IR9Cf5uW-my7T4p08G15QPlZ-SNCuxkw','$2y$13$eA9jlQeu3hMTcZCyfiIB6OdHO5/hHuW86unYQ76n7prIQijpOpKOW','CpqqKFEa3gCx1rDLfucLYjYwYjZiNTRiNDMxYmY2ZWM0NGIwMTc1ZTZhZTRlZGI1OGMzMzlhZmY0MWEyNDBiN2YxOTIzYTdkMmMwNDZkN2ORJPrhTN9rAbVjR6rjWr/11mQoH294+UAHOmZ/wNSVVKLR9ScQnsR/JwYLwFjv5Pg=',NULL,0,0,0,NULL,'','',NULL,0,NULL,NULL,NULL,'',NULL,'',NULL,1493287004,0);
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

-- Dump completed on 2017-04-27 17:03:58
