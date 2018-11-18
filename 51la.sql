# Host: localhost  (Version: 5.5.53)
# Date: 2018-11-13 21:00:26
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ct_domain"
#

DROP TABLE IF EXISTS `ct_domain`;
CREATE TABLE `ct_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '0启用 1停用',
  `ip` int(11) DEFAULT '0',
  `pv` int(11) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

#
# Data for table "ct_domain"
#

INSERT INTO `ct_domain` VALUES (1,'10','www.baidu.com',0,0,0,'2018-11-12 19:51:22','285e62da70b63989fd898d4c89bf77f5');

#
# Structure for table "ct_domain_click"
#

DROP TABLE IF EXISTS `ct_domain_click`;
CREATE TABLE `ct_domain_click` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) DEFAULT NULL,
  `from_url` varchar(255) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `useragent` varchar(1024) DEFAULT NULL,
  `referrer` varchar(512) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='访问记录';

#
# Data for table "ct_domain_click"
#


#
# Structure for table "ct_domain_click_tmp"
#

DROP TABLE IF EXISTS `ct_domain_click_tmp`;
CREATE TABLE `ct_domain_click_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) DEFAULT NULL,
  `from_url` varchar(255) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `useragent` varchar(1024) DEFAULT NULL,
  `referrer` varchar(512) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Data for table "ct_domain_click_tmp"
#


#
# Structure for table "ct_domain_day"
#

DROP TABLE IF EXISTS `ct_domain_day`;
CREATE TABLE `ct_domain_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checkid` varchar(20) DEFAULT NULL COMMENT '201811121 使用日期加did组成字段，防重',
  `did` int(11) DEFAULT NULL COMMENT '域名id',
  `day` date DEFAULT NULL COMMENT '统计日期',
  `ip` int(11) DEFAULT NULL,
  `pv` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checkid` (`checkid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Data for table "ct_domain_day"
#


#
# Structure for table "ct_user"
#

DROP TABLE IF EXISTS `ct_user`;
CREATE TABLE `ct_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `userpass` varchar(32) NOT NULL DEFAULT '',
  `status` int(11) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

#
# Data for table "ct_user"
#

INSERT INTO `ct_user` VALUES (8,'homewwwroot@gmail.co','14e1b600b1fd579f47433b88e8d85291',0,'2018-11-11 21:03:41'),(9,'homewwwroot@gmail.com','14e1b600b1fd579f47433b88e8d85291',0,'2018-11-11 21:07:39'),(10,'meieiem','5ee27e6cfab98d4ea4842b803ca30208',0,'2018-11-12 10:38:44');
