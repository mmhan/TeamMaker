/*
SQLyog Community Edition- MySQL GUI v8.14 
MySQL - 5.0.45-community-nt : Database - teammaker
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `acos` */

DROP TABLE IF EXISTS `acos`;

CREATE TABLE `acos` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) character set latin1 default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) character set latin1 default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `acos` */

insert  into `acos`(`id`,`parent_id`,`model`,`foreign_key`,`alias`,`lft`,`rght`) values (1,NULL,NULL,NULL,'controllers',1,114),(2,1,NULL,NULL,'Pages',2,15),(3,2,NULL,NULL,'display',3,4),(4,2,NULL,NULL,'add',5,6),(5,2,NULL,NULL,'edit',7,8),(6,2,NULL,NULL,'index',9,10),(7,2,NULL,NULL,'view',11,12),(8,2,NULL,NULL,'delete',13,14),(9,1,NULL,NULL,'Groups',16,37),(10,9,NULL,NULL,'admin_index',17,18),(11,9,NULL,NULL,'admin_view',19,20),(12,9,NULL,NULL,'admin_add',21,22),(13,9,NULL,NULL,'admin_edit',23,24),(14,9,NULL,NULL,'admin_delete',25,26),(15,9,NULL,NULL,'add',27,28),(16,9,NULL,NULL,'edit',29,30),(17,9,NULL,NULL,'index',31,32),(18,9,NULL,NULL,'view',33,34),(19,9,NULL,NULL,'delete',35,36),(20,1,NULL,NULL,'Projects',38,69),(21,20,NULL,NULL,'admin_index',39,40),(22,20,NULL,NULL,'admin_view',41,42),(23,20,NULL,NULL,'admin_add',43,44),(24,20,NULL,NULL,'admin_edit',45,46),(25,20,NULL,NULL,'admin_delete',47,48),(26,20,NULL,NULL,'add',49,50),(27,20,NULL,NULL,'edit',51,52),(28,20,NULL,NULL,'index',53,54),(29,20,NULL,NULL,'view',55,56),(30,20,NULL,NULL,'delete',57,58),(31,1,NULL,NULL,'Sitemaps',70,83),(32,31,NULL,NULL,'sitemap',71,72),(33,31,NULL,NULL,'add',73,74),(34,31,NULL,NULL,'edit',75,76),(35,31,NULL,NULL,'index',77,78),(36,31,NULL,NULL,'view',79,80),(37,31,NULL,NULL,'delete',81,82),(38,1,NULL,NULL,'Users',84,113),(39,38,NULL,NULL,'login',85,86),(40,38,NULL,NULL,'logout',87,88),(41,38,NULL,NULL,'admin_login',89,90),(42,38,NULL,NULL,'init_db',91,92),(43,38,NULL,NULL,'build_acl',93,94),(44,38,NULL,NULL,'admin_index',95,96),(45,38,NULL,NULL,'admin_add',97,98),(46,38,NULL,NULL,'admin_edit',99,100),(47,38,NULL,NULL,'admin_delete',101,102),(48,38,NULL,NULL,'add',103,104),(49,38,NULL,NULL,'edit',105,106),(50,38,NULL,NULL,'index',107,108),(51,38,NULL,NULL,'view',109,110),(52,38,NULL,NULL,'delete',111,112),(53,20,NULL,NULL,'admin_dashboard',59,60),(54,20,NULL,NULL,'admin_settings',61,62),(55,20,NULL,NULL,'admin_add_users',63,64),(56,20,NULL,NULL,'admin_add_members',65,66),(57,20,NULL,NULL,'admin_add_members_status',67,68);

/*Table structure for table `admins_projects` */

DROP TABLE IF EXISTS `admins_projects`;

CREATE TABLE `admins_projects` (
  `id` int(16) NOT NULL auto_increment,
  `user_id` int(16) NOT NULL,
  `project_id` int(16) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `admins_projects` */

insert  into `admins_projects`(`id`,`user_id`,`project_id`) values (9,1,9),(10,1,10),(11,1,11),(12,1,12),(13,1,13),(14,1,14),(16,1,2),(17,1,3),(18,1,4),(19,1,5),(20,1,6),(21,1,7),(22,1,8),(23,1,1);

/*Table structure for table `aros` */

DROP TABLE IF EXISTS `aros`;

CREATE TABLE `aros` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) character set latin1 default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) character set latin1 default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `aros` */

insert  into `aros`(`id`,`parent_id`,`model`,`foreign_key`,`alias`,`lft`,`rght`) values (1,NULL,'Group',1,NULL,1,6),(2,1,'User',1,NULL,2,3),(3,NULL,'Group',2,NULL,7,12),(6,3,'User',4,NULL,8,9),(7,NULL,'Group',3,NULL,13,74),(8,7,'User',5,NULL,14,15),(9,7,'User',6,NULL,16,17),(10,1,'User',7,NULL,4,5),(13,3,'User',10,NULL,10,11),(12,7,'User',9,NULL,18,19),(14,NULL,'Member',11,NULL,75,76),(15,NULL,'Member',12,NULL,77,78),(16,7,'Member',13,NULL,20,21),(17,7,'Member',14,NULL,22,23),(18,7,'Member',15,NULL,24,25),(19,7,'Member',16,NULL,26,27),(20,7,'Member',17,NULL,28,29),(21,7,'Member',18,NULL,30,31),(22,7,'Member',19,NULL,32,33),(23,7,'Member',20,NULL,34,35),(24,7,'Member',21,NULL,36,37),(25,7,'Member',22,NULL,38,39),(26,7,'Member',23,NULL,40,41),(27,7,'Member',24,NULL,42,43),(28,7,'Member',25,NULL,44,45),(29,7,'Member',26,NULL,46,47),(30,7,'Member',27,NULL,48,49),(31,7,'Member',28,NULL,50,51),(32,7,'Member',8,NULL,52,53),(33,7,'Member',9,NULL,54,55),(34,7,'Member',8,NULL,56,57),(35,7,'Member',9,NULL,58,59),(36,7,'Member',8,NULL,60,61),(37,7,'Member',9,NULL,62,63),(38,7,'Member',8,NULL,64,65),(39,7,'Member',9,NULL,66,67),(40,7,'Member',8,NULL,68,69),(41,7,'Member',8,NULL,70,71),(42,7,'Member',9,NULL,72,73);

/*Table structure for table `aros_acos` */

DROP TABLE IF EXISTS `aros_acos`;

CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL auto_increment,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) character set latin1 NOT NULL default '0',
  `_read` varchar(2) character set latin1 NOT NULL default '0',
  `_update` varchar(2) character set latin1 NOT NULL default '0',
  `_delete` varchar(2) character set latin1 NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `aros_acos` */

insert  into `aros_acos`(`id`,`aro_id`,`aco_id`,`_create`,`_read`,`_update`,`_delete`) values (1,1,1,'1','1','1','1'),(2,3,1,'1','1','1','1');

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) character set latin1 NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `groups` */

insert  into `groups`(`id`,`name`,`created`,`modified`) values (1,'Super Admins','2011-10-19 20:36:21','2011-10-19 20:36:21'),(2,'Admins','2011-10-19 20:36:21','2011-10-19 20:36:21'),(3,'Team Members','2011-10-19 20:36:21','2011-10-19 20:36:21');

/*Table structure for table `members_projects` */

DROP TABLE IF EXISTS `members_projects`;

CREATE TABLE `members_projects` (
  `id` int(16) NOT NULL auto_increment,
  `user_id` int(16) NOT NULL,
  `project_id` int(16) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

/*Data for the table `members_projects` */

insert  into `members_projects`(`id`,`user_id`,`project_id`) values (173,8,1),(174,8,1),(175,8,1),(176,8,1),(177,8,1),(178,8,1),(179,8,1),(180,8,1),(181,8,1),(199,9,1),(200,9,1),(201,9,1),(202,9,1),(203,9,1),(204,9,1),(205,9,1),(206,9,1),(207,9,1),(208,9,1),(209,9,1),(210,9,1),(211,9,1),(212,9,1),(213,9,1),(214,9,1),(215,9,1),(216,9,1);

/*Table structure for table `members_skills` */

DROP TABLE IF EXISTS `members_skills`;

CREATE TABLE `members_skills` (
  `id` int(32) NOT NULL,
  `skill_id` int(32) NOT NULL,
  `user_id` int(32) default NULL,
  `skill_value` text NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `members_skills` */

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(16) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `collection_end` datetime NOT NULL,
  `feedback_end` datetime NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(3) NOT NULL default '1',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `projects` */

insert  into `projects`(`id`,`name`,`collection_end`,`feedback_end`,`description`,`status`,`created`,`modified`) values (1,'CSV #1','2011-11-23 00:00:00','2011-11-30 00:00:00','Bla bla bla',1,'2011-11-18 06:52:43','2011-11-18 06:52:43');

/*Table structure for table `skills` */

DROP TABLE IF EXISTS `skills`;

CREATE TABLE `skills` (
  `id` int(32) NOT NULL auto_increment,
  `project_id` int(32) NOT NULL,
  `name` varchar(160) default NULL,
  `type` tinyint(8) default NULL,
  `range` text,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `skills` */

/*Table structure for table `uploads` */

DROP TABLE IF EXISTS `uploads`;

CREATE TABLE `uploads` (
  `id` int(16) NOT NULL auto_increment,
  `project_id` int(16) NOT NULL,
  `name` varchar(150) default NULL,
  `size` int(8) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `uploads` */

insert  into `uploads`(`id`,`project_id`,`name`,`size`,`created`,`modified`) values (1,1,'865561b669c1a81aa12d6cd79cd2d340925bddd5',406,'2011-11-18 06:52:43','2011-11-18 06:52:43');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `given_id` varchar(32) character set latin1 NOT NULL,
  `name` varchar(150) character set latin1 default NULL,
  `email` varchar(125) character set latin1 NOT NULL,
  `password` varchar(40) character set latin1 NOT NULL,
  `group_id` int(11) NOT NULL,
  `last_login_time` datetime default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `GivenID` (`given_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `users` */

insert  into `users`(`id`,`given_id`,`name`,`email`,`password`,`group_id`,`last_login_time`,`created`,`modified`) values (1,'123','Super Admin','mmhan2u@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',1,'2011-11-18 14:25:44','2011-10-19 20:36:26','2011-11-18 14:25:44'),(5,'122','Member','mmhan2u+member@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',3,NULL,'2011-10-19 20:36:26','2011-10-19 20:36:26'),(4,'333','Test Reception','mmhan2u+admin@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',2,NULL,'2011-10-19 20:36:26','2011-10-19 20:36:26'),(6,'444','Test User','test@example.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',3,NULL,'2011-10-28 13:07:32','2011-11-05 08:52:27'),(7,'1234','Mr. Soong','soongwengchew@gmail.com','62f37d34f4d62e6776066d30b7694a14a640b47c',1,NULL,'2011-10-28 13:12:36','2011-11-05 08:54:33'),(8,'11111D','John Smith','11111D@sayhi.oib.com','',3,NULL,'2011-11-18 09:56:16','2011-11-18 09:56:16'),(9,'11112D','Alan Jr. Smith','11112D@sayhi.oib.com','',3,NULL,'2011-11-18 09:56:16','2011-11-18 09:56:16');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
