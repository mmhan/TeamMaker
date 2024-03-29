-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2011 at 03:10 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `teammaker`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=77 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 152),
(2, 1, NULL, NULL, 'Pages', 2, 15),
(3, 2, NULL, NULL, 'display', 3, 4),
(4, 2, NULL, NULL, 'add', 5, 6),
(5, 2, NULL, NULL, 'edit', 7, 8),
(6, 2, NULL, NULL, 'index', 9, 10),
(7, 2, NULL, NULL, 'view', 11, 12),
(8, 2, NULL, NULL, 'delete', 13, 14),
(9, 1, NULL, NULL, 'Groups', 16, 37),
(10, 9, NULL, NULL, 'admin_index', 17, 18),
(11, 9, NULL, NULL, 'admin_view', 19, 20),
(12, 9, NULL, NULL, 'admin_add', 21, 22),
(13, 9, NULL, NULL, 'admin_edit', 23, 24),
(14, 9, NULL, NULL, 'admin_delete', 25, 26),
(15, 9, NULL, NULL, 'add', 27, 28),
(16, 9, NULL, NULL, 'edit', 29, 30),
(17, 9, NULL, NULL, 'index', 31, 32),
(18, 9, NULL, NULL, 'view', 33, 34),
(19, 9, NULL, NULL, 'delete', 35, 36),
(20, 1, NULL, NULL, 'Projects', 38, 79),
(21, 20, NULL, NULL, 'admin_index', 39, 40),
(22, 20, NULL, NULL, 'admin_view', 41, 42),
(23, 20, NULL, NULL, 'admin_add', 43, 44),
(24, 20, NULL, NULL, 'admin_edit', 45, 46),
(25, 20, NULL, NULL, 'admin_delete', 47, 48),
(26, 20, NULL, NULL, 'add', 49, 50),
(27, 20, NULL, NULL, 'edit', 51, 52),
(28, 20, NULL, NULL, 'index', 53, 54),
(29, 20, NULL, NULL, 'view', 55, 56),
(30, 20, NULL, NULL, 'delete', 57, 58),
(31, 1, NULL, NULL, 'Sitemaps', 80, 93),
(32, 31, NULL, NULL, 'sitemap', 81, 82),
(33, 31, NULL, NULL, 'add', 83, 84),
(34, 31, NULL, NULL, 'edit', 85, 86),
(35, 31, NULL, NULL, 'index', 87, 88),
(36, 31, NULL, NULL, 'view', 89, 90),
(37, 31, NULL, NULL, 'delete', 91, 92),
(38, 1, NULL, NULL, 'Users', 94, 123),
(39, 38, NULL, NULL, 'login', 95, 96),
(40, 38, NULL, NULL, 'logout', 97, 98),
(41, 38, NULL, NULL, 'admin_login', 99, 100),
(42, 38, NULL, NULL, 'init_db', 101, 102),
(43, 38, NULL, NULL, 'build_acl', 103, 104),
(44, 38, NULL, NULL, 'admin_index', 105, 106),
(45, 38, NULL, NULL, 'admin_add', 107, 108),
(46, 38, NULL, NULL, 'admin_edit', 109, 110),
(47, 38, NULL, NULL, 'admin_delete', 111, 112),
(48, 38, NULL, NULL, 'add', 113, 114),
(49, 38, NULL, NULL, 'edit', 115, 116),
(50, 38, NULL, NULL, 'index', 117, 118),
(51, 38, NULL, NULL, 'view', 119, 120),
(52, 38, NULL, NULL, 'delete', 121, 122),
(53, 20, NULL, NULL, 'admin_dashboard', 59, 60),
(54, 20, NULL, NULL, 'admin_settings', 61, 62),
(55, 20, NULL, NULL, 'admin_add_users', 63, 64),
(56, 20, NULL, NULL, 'admin_add_members', 65, 66),
(57, 20, NULL, NULL, 'admin_add_members_status', 67, 68),
(58, 1, NULL, NULL, 'MembersSkills', 124, 137),
(59, 58, NULL, NULL, 'enter_data', 125, 126),
(60, 58, NULL, NULL, 'add', 127, 128),
(61, 58, NULL, NULL, 'edit', 129, 130),
(62, 58, NULL, NULL, 'index', 131, 132),
(63, 58, NULL, NULL, 'view', 133, 134),
(64, 58, NULL, NULL, 'delete', 135, 136),
(65, 20, NULL, NULL, 'cron', 69, 70),
(66, 20, NULL, NULL, 'admin_members', 71, 72),
(67, 20, NULL, NULL, 'admin_import_file', 73, 74),
(68, 20, NULL, NULL, 'admin_skills', 75, 76),
(69, 20, NULL, NULL, 'admin_launch', 77, 78),
(70, 1, NULL, NULL, 'Teams', 138, 151),
(71, 70, NULL, NULL, 'admin_make', 139, 140),
(72, 70, NULL, NULL, 'add', 141, 142),
(73, 70, NULL, NULL, 'edit', 143, 144),
(74, 70, NULL, NULL, 'index', 145, 146),
(75, 70, NULL, NULL, 'view', 147, 148),
(76, 70, NULL, NULL, 'delete', 149, 150);

-- --------------------------------------------------------

--
-- Table structure for table `admins_projects`
--

DROP TABLE IF EXISTS `admins_projects`;
CREATE TABLE IF NOT EXISTS `admins_projects` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user_id` int(16) NOT NULL,
  `project_id` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admins_projects`
--


-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=14 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Group', 1, NULL, 1, 6),
(2, 1, 'User', 1, NULL, 2, 3),
(3, NULL, 'Group', 2, NULL, 7, 12),
(6, 3, 'User', 4, NULL, 8, 9),
(7, NULL, 'Group', 3, NULL, 13, 16),
(8, 7, 'User', 5, NULL, 14, 15),
(10, 1, 'User', 7, NULL, 4, 5),
(13, 3, 'User', 10, NULL, 10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `_read` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `_update` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `_delete` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=7 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(2, 3, 1, '1', '1', '1', '1'),
(3, 7, 1, '-1', '-1', '-1', '-1'),
(4, 7, 49, '1', '1', '1', '1'),
(5, 7, 28, '1', '1', '1', '1'),
(6, 7, 59, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Super Admins', '2011-10-19 20:36:21', '2011-10-19 20:36:21'),
(2, 'Admins', '2011-10-19 20:36:21', '2011-10-19 20:36:21'),
(3, 'Team Members', '2011-10-19 20:36:21', '2011-10-19 20:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `members_projects`
--

DROP TABLE IF EXISTS `members_projects`;
CREATE TABLE IF NOT EXISTS `members_projects` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user_id` int(16) NOT NULL,
  `project_id` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `members_projects`
--


-- --------------------------------------------------------

--
-- Table structure for table `members_skills`
--

DROP TABLE IF EXISTS `members_skills`;
CREATE TABLE IF NOT EXISTS `members_skills` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `skill_id` int(32) NOT NULL,
  `user_id` int(32) DEFAULT NULL,
  `skill_value` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `members_skills`
--


-- --------------------------------------------------------

--
-- Table structure for table `members_teams`
--

DROP TABLE IF EXISTS `members_teams`;
CREATE TABLE IF NOT EXISTS `members_teams` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(16) unsigned NOT NULL,
  `user_id` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `members_teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `collection_end` datetime NOT NULL,
  `feedback_end` datetime NOT NULL,
  `description` text NOT NULL,
  `rules` blob,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `projects`
--


-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `project_id` int(32) NOT NULL,
  `name` varchar(160) DEFAULT NULL,
  `type` tinyint(8) DEFAULT NULL,
  `range` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `skills`
--


-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(16) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `project_id` int(16) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `size` int(8) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `uploads`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `given_id` varchar(32) CHARACTER SET latin1 NOT NULL,
  `name` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(125) CHARACTER SET latin1 NOT NULL,
  `password` varchar(40) CHARACTER SET latin1 NOT NULL,
  `group_id` int(11) NOT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `GivenID` (`given_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=14 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `given_id`, `name`, `email`, `password`, `group_id`, `last_login_time`, `created`, `modified`) VALUES
(1, '123', 'Super Admin', 'su@example.com', '6c46adf5a02d03471d5173ecfa6b7db309d2b708', 1, '2011-11-27 20:53:17', '2011-10-19 20:36:26', '2011-11-27 20:53:17'),
(4, '333', 'Test Admin', 'admin@example.com', '6c46adf5a02d03471d5173ecfa6b7db309d2b708', 2, NULL, '2011-10-19 20:36:26', '2011-11-26 20:25:23'),
(5, '122', 'Member', 'member@example.com', '6c46adf5a02d03471d5173ecfa6b7db309d2b708', 3, NULL, '2011-10-19 20:36:26', '2011-10-19 20:36:26'),
(7, '1234', 'Mr. Soong', 'soongwengchew@gmail.com', '62f37d34f4d62e6776066d30b7694a14a640b47c', 1, NULL, '2011-10-28 13:12:36', '2011-11-05 08:54:33');
