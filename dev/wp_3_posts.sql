-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2014 at 02:21 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wordpress`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_3_posts`
--

CREATE TABLE IF NOT EXISTS `wp_3_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `wp_3_posts`
--

INSERT INTO `wp_3_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2014-08-18 13:12:12', '2014-08-18 18:12:12', 'Welcome to <a href="http://wordpress.loc/">test Sites</a>. This is your first post. Edit or delete it, then start blogging!', 'Hello world!', '', 'publish', 'open', 'open', '', 'hello-world', '', '', '2014-08-18 13:12:12', '2014-08-18 18:12:12', '', 0, 'http://wordpress.loc/asm/?p=1', 0, 'post', '', 1),
(2, 1, '2014-08-18 13:12:12', '2014-08-18 18:12:12', 'This is an example page. It''s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:\n\n<blockquote>Hi there! I''m a bike messenger by day, aspiring actor by night, and this is my blog. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin'' caught in the rain.)</blockquote>\n\n...or something like this:\n\n<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>\n\nAs a new WordPress user, you should go to <a href="http://wordpress.loc/asm/wp-admin/">your dashboard</a> to delete this page and create new pages for your content. Have fun!', 'Sample Page', '', 'publish', 'open', 'open', '', 'sample-page', '', '', '2014-08-18 13:12:12', '2014-08-18 18:12:12', '', 0, 'http://wordpress.loc/asm/?page_id=2', 0, 'page', '', 0),
(3, 1, '2014-08-18 18:12:20', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'open', '', '', '', '', '2014-08-18 18:12:20', '0000-00-00 00:00:00', '', 0, 'http://wordpress.loc/asm/?p=3', 0, 'post', '', 0),
(4, 1, '2014-08-18 18:12:30', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'open', '', '', '', '', '2014-08-18 18:12:30', '0000-00-00 00:00:00', '', 0, 'http://wordpress.loc/asm/?page_id=4', 0, 'page', '', 0),
(5, 1, '2014-08-18 18:25:14', '2014-08-18 18:25:14', '', 'Under Sample', '', 'publish', 'open', 'open', '', 'under-sample', '', '', '2014-08-18 18:25:14', '2014-08-18 18:25:14', '', 2, 'http://wordpress.loc/asm/?page_id=5', 0, 'page', '', 0),
(6, 1, '2014-08-18 18:25:14', '2014-08-18 18:25:14', '', 'Under Sample', '', 'inherit', 'open', 'open', '', '5-revision-v1', '', '', '2014-08-18 18:25:14', '2014-08-18 18:25:14', '', 5, 'http://wordpress.loc/asm/2014/08/18/5-revision-v1/', 0, 'revision', '', 0),
(7, 1, '2014-08-18 18:25:39', '2014-08-18 18:25:39', '', 'Also under sample', '', 'publish', 'open', 'open', '', 'also-under-sample', '', '', '2014-08-18 18:25:39', '2014-08-18 18:25:39', '', 2, 'http://wordpress.loc/asm/?page_id=7', 0, 'page', '', 0),
(8, 1, '2014-08-18 18:25:39', '2014-08-18 18:25:39', '', 'Also under sample', '', 'inherit', 'open', 'open', '', '7-revision-v1', '', '', '2014-08-18 18:25:39', '2014-08-18 18:25:39', '', 7, 'http://wordpress.loc/asm/2014/08/18/7-revision-v1/', 0, 'revision', '', 0),
(9, 1, '2014-08-18 18:25:54', '2014-08-18 18:25:54', '', 'under sample the third', '', 'publish', 'open', 'open', '', 'under-sample-the-third', '', '', '2014-08-18 18:25:54', '2014-08-18 18:25:54', '', 2, 'http://wordpress.loc/asm/?page_id=9', 0, 'page', '', 0),
(10, 1, '2014-08-18 18:25:54', '2014-08-18 18:25:54', '', 'under sample the third', '', 'inherit', 'open', 'open', '', '9-revision-v1', '', '', '2014-08-18 18:25:54', '2014-08-18 18:25:54', '', 9, 'http://wordpress.loc/asm/2014/08/18/9-revision-v1/', 0, 'revision', '', 0),
(11, 1, '2014-08-18 18:26:05', '2014-08-18 18:26:05', '', 'under third', '', 'publish', 'open', 'open', '', 'under-third', '', '', '2014-08-18 18:26:05', '2014-08-18 18:26:05', '', 9, 'http://wordpress.loc/asm/?page_id=11', 0, 'page', '', 0),
(12, 1, '2014-08-18 18:26:05', '2014-08-18 18:26:05', '', 'under third', '', 'inherit', 'open', 'open', '', '11-revision-v1', '', '', '2014-08-18 18:26:05', '2014-08-18 18:26:05', '', 11, 'http://wordpress.loc/asm/2014/08/18/11-revision-v1/', 0, 'revision', '', 0),
(13, 1, '2014-08-18 18:26:15', '2014-08-18 18:26:15', '', 'under under', '', 'publish', 'open', 'open', '', 'under-under', '', '', '2014-08-18 18:26:15', '2014-08-18 18:26:15', '', 5, 'http://wordpress.loc/asm/?page_id=13', 0, 'page', '', 0),
(14, 1, '2014-08-18 18:26:15', '2014-08-18 18:26:15', '', 'under under', '', 'inherit', 'open', 'open', '', '13-revision-v1', '', '', '2014-08-18 18:26:15', '2014-08-18 18:26:15', '', 13, 'http://wordpress.loc/asm/2014/08/18/13-revision-v1/', 0, 'revision', '', 0),
(15, 1, '2014-08-18 18:26:24', '2014-08-18 18:26:24', '', 'under also', '', 'publish', 'open', 'open', '', 'under-also', '', '', '2014-08-18 18:26:24', '2014-08-18 18:26:24', '', 7, 'http://wordpress.loc/asm/?page_id=15', 0, 'page', '', 0),
(16, 1, '2014-08-18 18:26:24', '2014-08-18 18:26:24', '', 'under also', '', 'inherit', 'open', 'open', '', '15-revision-v1', '', '', '2014-08-18 18:26:24', '2014-08-18 18:26:24', '', 15, 'http://wordpress.loc/asm/2014/08/18/15-revision-v1/', 0, 'revision', '', 0),
(17, 1, '2014-08-18 18:26:42', '2014-08-18 18:26:42', '', 'fourth under under', '', 'publish', 'open', 'open', '', 'fourth-under-under', '', '', '2014-08-18 18:26:42', '2014-08-18 18:26:42', '', 13, 'http://wordpress.loc/asm/?page_id=17', 0, 'page', '', 0),
(18, 1, '2014-08-18 18:26:42', '2014-08-18 18:26:42', '', 'fourth under under', '', 'inherit', 'open', 'open', '', '17-revision-v1', '', '', '2014-08-18 18:26:42', '2014-08-18 18:26:42', '', 17, 'http://wordpress.loc/asm/2014/08/18/17-revision-v1/', 0, 'revision', '', 0),
(19, 1, '2014-08-18 18:26:58', '2014-08-18 18:26:58', '', 'fifth under under', '', 'publish', 'open', 'open', '', 'fifth-under-under', '', '', '2014-08-18 18:26:58', '2014-08-18 18:26:58', '', 17, 'http://wordpress.loc/asm/?page_id=19', 0, 'page', '', 0),
(20, 1, '2014-08-18 18:26:58', '2014-08-18 18:26:58', '', 'fifth under under', '', 'inherit', 'open', 'open', '', '19-revision-v1', '', '', '2014-08-18 18:26:58', '2014-08-18 18:26:58', '', 19, 'http://wordpress.loc/asm/2014/08/18/19-revision-v1/', 0, 'revision', '', 0),
(21, 1, '2014-08-18 18:27:11', '2014-08-18 18:27:11', '', 'sixth under under', '', 'publish', 'open', 'open', '', 'sixth-under-under', '', '', '2014-08-18 18:27:11', '2014-08-18 18:27:11', '', 19, 'http://wordpress.loc/asm/?page_id=21', 0, 'page', '', 0),
(22, 1, '2014-08-18 18:27:11', '2014-08-18 18:27:11', '', 'sixth under under', '', 'inherit', 'open', 'open', '', '21-revision-v1', '', '', '2014-08-18 18:27:11', '2014-08-18 18:27:11', '', 21, 'http://wordpress.loc/asm/2014/08/18/21-revision-v1/', 0, 'revision', '', 0),
(23, 1, '2014-08-18 18:27:32', '2014-08-18 18:27:32', '', 'fourth under also', '', 'publish', 'open', 'open', '', 'fourth-under-also', '', '', '2014-08-18 18:27:32', '2014-08-18 18:27:32', '', 15, 'http://wordpress.loc/asm/?page_id=23', 0, 'page', '', 0),
(24, 1, '2014-08-18 18:27:32', '2014-08-18 18:27:32', '', 'fourth under also', '', 'inherit', 'open', 'open', '', '23-revision-v1', '', '', '2014-08-18 18:27:32', '2014-08-18 18:27:32', '', 23, 'http://wordpress.loc/asm/2014/08/18/23-revision-v1/', 0, 'revision', '', 0),
(25, 1, '2014-08-18 19:17:05', '2014-08-18 19:17:05', '', 'Another under also ', '', 'publish', 'open', 'open', '', 'another-under-also', '', '', '2014-08-18 19:17:05', '2014-08-18 19:17:05', '', 7, 'http://wordpress.loc/asm/?page_id=25', 0, 'page', '', 0),
(26, 1, '2014-08-18 19:17:05', '2014-08-18 19:17:05', '', 'Another under also ', '', 'inherit', 'open', 'open', '', '25-revision-v1', '', '', '2014-08-18 19:17:05', '2014-08-18 19:17:05', '', 25, 'http://wordpress.loc/asm/2014/08/18/25-revision-v1/', 0, 'revision', '', 0),
(27, 1, '2014-08-18 19:17:28', '2014-08-18 19:17:28', '', 'another fourth under under', '', 'publish', 'open', 'open', '', 'another-fourth-under-under', '', '', '2014-08-18 19:17:28', '2014-08-18 19:17:28', '', 13, 'http://wordpress.loc/asm/?page_id=27', 0, 'page', '', 0),
(28, 1, '2014-08-18 19:17:28', '2014-08-18 19:17:28', '', 'another fourth under under', '', 'inherit', 'open', 'open', '', '27-revision-v1', '', '', '2014-08-18 19:17:28', '2014-08-18 19:17:28', '', 27, 'http://wordpress.loc/asm/2014/08/18/27-revision-v1/', 0, 'revision', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
