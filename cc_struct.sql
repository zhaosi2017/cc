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
  `unactive_nickname` varchar(50) NOT NULL DEFAULT '*' COMMENT '被叫昵称',
  `unactive_account` varchar(100) NOT NULL COMMENT '被叫账号',
  `active_nickname` varchar(50) NOT NULL DEFAULT '*' COMMENT '主叫昵称',
  `call_by_same_times` int(10) unsigned NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `contact_number` varchar(64) NOT NULL DEFAULT '',
  `unactive_contact_number` char(15) NOT NULL COMMENT '被叫电话',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `record_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '记录状态(1:正常, 2:黑名单, 3:垃圾桶)',
  `call_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `address` varchar(100)   DEFAULT NULL COMMENT '登录地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manager_login_logs`
--

DROP TABLE IF EXISTS `manager_login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manager_login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1成功,2密码错误,3验证错误,4账号错误',
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_ip` char(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `address` varchar(100)   DEFAULT NULL COMMENT '登录地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `create_id` int(10) unsigned NOT NULL DEFAULT '0',
  `update_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_at` int(11) DEFAULT '0',
  `update_at` int(11) DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `telegram_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `telegram_country_code` int(10) unsigned DEFAULT NULL,
  `telegram_name` varchar(64) NOT NULL DEFAULT '',
  `potato_number` varchar(64) NOT NULL DEFAULT '',
  `potato_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `potato_country_code` int(10) unsigned DEFAULT NULL,
  `potato_name` varchar(64) NOT NULL DEFAULT '',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_ip` varchar(64) NOT NULL DEFAULT '',
  `whitelist_switch` tinyint(1) NOT NULL DEFAULT '0',
  `language` VARCHAR(40) NOT NULL DEFAULT 'zh-CN',
  `step` TINYINT(1) NOT NULL DEFAULT '0',
   `amount` decimal(14,4) NOT NULL DEFAULT '0.0000' COMMENT '账户余额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `white_list`
--

DROP TABLE IF EXISTS `white_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `white_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `white_uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-07 16:44:03

CREATE TABLE `user_phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_phone_sort` int(11) NOT NULL DEFAULT '1' COMMENT '号码在用户下的顺序',
  `phone_country_code` char(8) NOT NULL DEFAULT '+86' COMMENT '电话号码的国际编码',
  `reg_time` int(11) NOT NULL DEFAULT '0' COMMENT '绑定时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `user_phone_number` char(16) NOT NULL DEFAULT '' COMMENT '电话号码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `black_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `black_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `black_uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE user_gent_contact (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  contact_country_code char(32) NOT NULL DEFAULT '86' COMMENT '国际编码',
  contact_phone_number char(32) NOT NULL DEFAULT '0' COMMENT '电话号码',
  contact_nickname char(64) NOT NULL DEFAULT '' COMMENT '联系人昵称',
  reg_time int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  update_time int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  contact_sort int(11) NOT NULL DEFAULT '1' COMMENT '紧急联系人的优先顺序  数字大优先级高',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `telegram_maps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `latitude` decimal(17,14) NOT NULL DEFAULT '0.00000000000000',
  `longitude` decimal(17,14) NOT NULL DEFAULT '0.00000000000000',
  `title` char(64) NOT NULL DEFAULT '''''',
  `description` text NOT NULL,
  `addr` char(255) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `potato_map` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `latitude` decimal(17,14) NOT NULL DEFAULT '0.00000000000000',
  `longitude` decimal(17,14) NOT NULL DEFAULT '0.00000000000000',
  `title` char(64) NOT NULL DEFAULT '''''',
  `description` text NOT NULL,
  `address` char(255) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/**   用户资金变动明细表*/
CREATE TABLE `final_change_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `change_type` int(11) NOT NULL COMMENT '帐变类型',
  `amount` decimal(14,4) NOT NULL DEFAULT '0.0000' COMMENT '帐变金额',
  `time` int(11) NOT NULL COMMENT '帐变时间',
  `user_id` int(11) NOT NULL COMMENT '帐变发生人',
  `comment` char(255) DEFAULT '' COMMENT '说明',
  `before` decimal(14,4) NOT NULL DEFAULT '0.0000' COMMENT '帐变之前金额',
  `after` decimal(14,4) NOT NULL DEFAULT '0.0000' COMMENT '帐变之后金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

／** 充值接口日志表 *／
CREATE TABLE `final_mutual_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `interface_name` char(16) DEFAULT '' COMMENT '接口名',
  `data` text COMMENT '交互数据',
  `time` int(11) NOT NULL COMMENT '发生时间',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '交互类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**
  号码池
 */
CREATE TABLE `call_number` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL COMMENT '电话号码',
  `status` int(11) NOT NULL COMMENT '可使用状态',
  `time` int(11) NOT NULL COMMENT '录入时间',
  `comment` char(255) DEFAULT NULL COMMENT '说明',
  `rent_status` int(11) NOT NULL DEFAULT '0' COMMENT '可租状态',
  `begin_time` int(11) NOT NULL COMMENT '启用时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `price` decimal(14,4) NOT NULL DEFAULT '0.0000' COMMENT '租金／每天',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**
  用户号码池
 */

CREATE TABLE `user_number` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL COMMENT '电话号码',
  `time` int(11) NOT NULL COMMENT '记录时间',
  `end_time` int(11) NOT NULL COMMENT '到期时间',
  `begin_time` int(11) NOT NULL COMMENT '起租时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;