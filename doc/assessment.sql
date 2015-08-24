-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-08-23 08:19:56
-- 服务器版本： 5.7.7-rc
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assessment`
--

-- --------------------------------------------------------

--
-- 表的结构 `ca_account`
--

CREATE TABLE IF NOT EXISTS `ca_account` (
  `account_id` int(11) NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `card_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为来访者，9为咨询师',
  `date` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户手机号／密码表';

--
-- 转存表中的数据 `ca_account`
--

INSERT INTO `ca_account` (`account_id`, `phone`, `password`, `card_id`, `type`, `date`) VALUES
(1, '18333333333', '111111', 1, 1, 0),
(2, '18333333334', '111111', 0, 1, 1440227695),
(3, '18333333335', '111111', 0, 1, 1440229432),
(4, '18333333332', '111111', 0, 1, 1440230893),
(5, '18333334444', '111111', 0, 1, 1440231728),
(6, '18333333311', '111111', 0, 1, 1440247698),
(7, '18344444444', '111111', 0, 1, 1440253261),
(8, '18333338888', '111111', 0, 1, 1440261151),
(9, '18444444444', '111111', 0, 1, 1440309864),
(10, '15093412311', '111111', 0, 1, 1440313276),
(11, '18444314131', '111111', 0, 1, 1440313431),
(12, '18111111111', '111111', 0, 1, 1440313545),
(13, '18111111118', '111111', 0, 1, 1440313671),
(14, '18444444442', '000000', 0, 1, 1440313762),
(15, '18333333330', '333333', 0, 9, 1440315160);

-- --------------------------------------------------------

--
-- 表的结构 `ca_admin`
--

CREATE TABLE IF NOT EXISTS `ca_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '管理员类型，用来分配权限,1为超级管理员，2为普通管理员'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员';

-- --------------------------------------------------------

--
-- 表的结构 `ca_comment`
--

CREATE TABLE IF NOT EXISTS `ca_comment` (
  `coment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `attidude_score` int(11) NOT NULL COMMENT '服务态度评分(1-5)',
  `professional_score` int(11) NOT NULL COMMENT '专业程度评分(1-5)',
  `content` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评论';

-- --------------------------------------------------------

--
-- 表的结构 `ca_tcard`
--

CREATE TABLE IF NOT EXISTS `ca_tcard` (
  `tcard_id` int(11) NOT NULL,
  `number` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师会员卡，以9开头';

--
-- 转存表中的数据 `ca_tcard`
--

INSERT INTO `ca_tcard` (`tcard_id`, `number`, `password`) VALUES
(1, '999999', '999999');

-- --------------------------------------------------------

--
-- 表的结构 `ca_teacherinfo`
--

CREATE TABLE IF NOT EXISTS `ca_teacherinfo` (
  `teacherinfo_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '咨询师图片',
  `gender` tinyint(1) NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `servide_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '服务类型 [1，2，3，4，5，6]高中，大学，工作未满1年，1-3年，3-5年，5年以上',
  `free_time` tinyint(2) NOT NULL COMMENT '空闲时间，两位整数，星期+时间段。12 周一，第二个时段。时段：9:00-10:30-12:00;2:30-4:00-5:30;7:00-8:30-10:00',
  `introduction` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '咨询师简介',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '居住城市',
  `certificate` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '心理咨询师，生涯规划师［00］0为否1为是'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师信息';

-- --------------------------------------------------------

--
-- 表的结构 `ca_ucard`
--

CREATE TABLE IF NOT EXISTS `ca_ucard` (
  `ucard_id` int(11) NOT NULL,
  `number` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者会员卡，以1开头';

--
-- 转存表中的数据 `ca_ucard`
--

INSERT INTO `ca_ucard` (`ucard_id`, `number`, `password`) VALUES
(1, '111111', '111111'),
(2, '122222', '111111');

-- --------------------------------------------------------

--
-- 表的结构 `ca_userinfo`
--

CREATE TABLE IF NOT EXISTS `ca_userinfo` (
  `userinfo_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) NOT NULL COMMENT '1男2女0未知',
  `status` tinyint(1) NOT NULL COMMENT '1高中 2大学,3工作未满1年,4工作1-3年,5工作3-5年,6工作5年以上',
  `school` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '专业（2）',
  `student_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '艺体生，少数名族考生，是否国家专项计划，是否军校或国防生（1） ［0000］0表示否，1表示是',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '居住城市'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者信息';

--
-- 转存表中的数据 `ca_userinfo`
--

INSERT INTO `ca_userinfo` (`userinfo_id`, `account_id`, `name`, `email`, `gender`, `status`, `school`, `college`, `student_type`, `city`) VALUES
(1, 9, 'ewqr', 'eqrw@qq.com', 2, 2, 'daf', 'wqer', '1111', '重庆'),
(2, 14, 'jianghang', 'jiaghang@gmaol.com', 1, 1, 'nangao', NULL, '1100', '上海');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ca_account`
--
ALTER TABLE `ca_account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `ca_admin`
--
ALTER TABLE `ca_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ca_comment`
--
ALTER TABLE `ca_comment`
  ADD PRIMARY KEY (`coment_id`);

--
-- Indexes for table `ca_tcard`
--
ALTER TABLE `ca_tcard`
  ADD PRIMARY KEY (`tcard_id`);

--
-- Indexes for table `ca_teacherinfo`
--
ALTER TABLE `ca_teacherinfo`
  ADD PRIMARY KEY (`teacherinfo_id`);

--
-- Indexes for table `ca_ucard`
--
ALTER TABLE `ca_ucard`
  ADD PRIMARY KEY (`ucard_id`);

--
-- Indexes for table `ca_userinfo`
--
ALTER TABLE `ca_userinfo`
  ADD PRIMARY KEY (`userinfo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ca_account`
--
ALTER TABLE `ca_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ca_admin`
--
ALTER TABLE `ca_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_comment`
--
ALTER TABLE `ca_comment`
  MODIFY `coment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_tcard`
--
ALTER TABLE `ca_tcard`
  MODIFY `tcard_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ca_teacherinfo`
--
ALTER TABLE `ca_teacherinfo`
  MODIFY `teacherinfo_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_ucard`
--
ALTER TABLE `ca_ucard`
  MODIFY `ucard_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ca_userinfo`
--
ALTER TABLE `ca_userinfo`
  MODIFY `userinfo_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
