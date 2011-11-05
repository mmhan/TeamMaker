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
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `acos` */

/*Table structure for table `admins_projects` */

DROP TABLE IF EXISTS `admins_projects`;

CREATE TABLE `admins_projects` (
  `id` int(16) NOT NULL auto_increment,
  `user_id` int(16) NOT NULL,
  `project_id` int(16) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `admins_projects` */

/*Table structure for table `aros` */

DROP TABLE IF EXISTS `aros`;

CREATE TABLE `aros` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `aros` */

insert  into `aros`(`id`,`parent_id`,`model`,`foreign_key`,`alias`,`lft`,`rght`) values (1,NULL,'Group',1,NULL,1,6),(2,1,'User',1,NULL,2,3),(3,NULL,'Group',2,NULL,7,10),(6,3,'User',4,NULL,8,9),(7,NULL,'Group',3,NULL,11,16),(8,7,'User',5,NULL,12,13),(9,7,'User',6,NULL,14,15),(10,1,'User',7,NULL,4,5);

/*Table structure for table `aros_acos` */

DROP TABLE IF EXISTS `aros_acos`;

CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL auto_increment,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL default '0',
  `_read` varchar(2) NOT NULL default '0',
  `_update` varchar(2) NOT NULL default '0',
  `_delete` varchar(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `aros_acos` */

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `groups` */

insert  into `groups`(`id`,`name`,`created`,`modified`) values (1,'Super Admins','2011-10-19 20:36:21','2011-10-19 20:36:21'),(2,'Admins','2011-10-19 20:36:21','2011-10-19 20:36:21'),(3,'Team Members','2011-10-19 20:36:21','2011-10-19 20:36:21');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `projects` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(150) default NULL,
  `email` varchar(125) NOT NULL,
  `password` varchar(40) NOT NULL,
  `group_id` int(11) NOT NULL,
  `last_login_time` datetime default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`group_id`,`last_login_time`,`created`,`modified`) values (1,'Super Admin','mmhan2u@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',1,'2011-11-05 09:20:22','2011-10-19 20:36:26','2011-11-05 09:20:22'),(5,'Member','mmhan2u+member@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',3,NULL,'2011-10-19 20:36:26','2011-10-19 20:36:26'),(4,'Test Reception','mmhan2u+admin@gmail.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',2,NULL,'2011-10-19 20:36:26','2011-10-19 20:36:26'),(6,'Test User','test@example.com','6c46adf5a02d03471d5173ecfa6b7db309d2b708',3,NULL,'2011-10-28 13:07:32','2011-11-05 08:52:27'),(7,'Mr. Soong','soongwengchew@gmail.com','62f37d34f4d62e6776066d30b7694a14a640b47c',1,NULL,'2011-10-28 13:12:36','2011-11-05 08:54:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
