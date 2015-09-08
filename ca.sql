-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-09-08 07:43:03
-- 服务器版本： 5.7.7-rc
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ca`
--

-- --------------------------------------------------------

--
-- 表的结构 `ca_account`
--

CREATE TABLE IF NOT EXISTS `ca_account` (
  `account_id` int(11) NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL COMMENT '1为来访者，2为咨询师',
  `date` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户手机号／密码表';

--
-- 转存表中的数据 `ca_account`
--

INSERT INTO `ca_account` (`account_id`, `phone`, `password`, `card_id`, `type`, `date`) VALUES
(41, '18777774440', '47246349', 0, 9, 1441084155),
(40, '13777777777', '99454630', 0, 9, 1441084036),
(39, '18344444444', '98818874', 0, 9, 1441083768),
(38, '18444444444', '31692946', 0, 1, 1441083129),
(37, '18700001111', '55767563', 0, 9, 1441073747),
(36, '18555555555', '71634770', 0, 1, 1441073711),
(35, '18777774444', '54644650', 0, 9, 1441041862),
(34, '18999999999', NULL, 0, 9, 1441041770),
(33, '18000000000', '01453626', 0, 9, 1441041668),
(32, '18777777777', '29125355', 0, 1, 1441039589);

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
-- 表的结构 `ca_card`
--

CREATE TABLE IF NOT EXISTS `ca_card` (
  `card_id` int(11) NOT NULL,
  `card` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1user，2teacher'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ca_card`
--

INSERT INTO `ca_card` (`card_id`, `card`, `password`, `type`) VALUES
(1, '111', '111', 1);

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
-- 表的结构 `ca_teacher`
--

CREATE TABLE IF NOT EXISTS `ca_teacher` (
  `teacher_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '咨询师个人头像',
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '高中,大学,工作[以英文逗号隔开]',
  `free_time` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '空闲时间，两位整数，星期+时间段。12 周一，第二个时段。时段：9:00-10:30-12:00;2:30-4:00-5:30;7:00-8:30-10:00',
  `introduction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '咨询师简介',
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '居住城市',
  `certificate_a` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '心理咨询师',
  `certificate_b` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '生涯规划师',
  `time_a` int(11) DEFAULT NULL COMMENT '数字1到10,11表示10年以上',
  `time_b` int(11) DEFAULT NULL COMMENT '数字1到10,11表示10年以上'
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师信息';

--
-- 转存表中的数据 `ca_teacher`
--

INSERT INTO `ca_teacher` (`teacher_id`, `account_id`, `name`, `avatar`, `gender`, `email`, `service_type`, `free_time`, `introduction`, `city`, `certificate_a`, `certificate_b`, `time_a`, `time_b`) VALUES
(13, 39, 'hjg', 'teacher/20150901/55e5326e9eb6e.jpeg', 1, 'efgw@qq.com', '高中', NULL, '<p>饿fhirh.fk23<img src="/CareerAssessment/ueditor/php/upload/image/20150901/1441083949594913.jpeg" title="1441083949594913.jpeg" alt="5744444547.jpeg"/></p>', '北京', 'teacher/20150901/55e5326e9f5a2.jpeg', NULL, 1, 0),
(12, 37, 'wrqe', 'teacher/20150901/55e48debead1b.jpeg\n', 1, 'wreq@qq.com', '大学', NULL, '<p>请编辑您的个人简介...</p>', '衢州', 'teacher/20150901/55e50a7ba5a9d.jpeg', NULL, 2, 0),
(10, 34, 'fa', 'teacher/20150901/55e48debead1b.jpeg', NULL, NULL, NULL, NULL, 'ewrwerqweee', NULL, NULL, NULL, 1, 0),
(11, 35, 'noqwr', 'teacher/20150901/55e48debead1b.jpeg', 1, 'qwer@qq.com', '高中,大学', NULL, '<p>请编辑您的个人简介...qwrq</p><p>ewqrqwer</p><p>23rwerq<img alt="photo-1438480478735-3234e63615bb.jpeg" src="/CareerAssessment/ueditor/php/upload/image/20150901/1441041897106604.jpeg" title="1441041897106604.jpeg"/></p>', '杭州', 'teacher/20150901/55e48debeb745.jpg', 'teacher/20150901/55e48debeb9d9.jpeg', 5, 0),
(9, 33, 'name', 'teacher/20150901/55e48debead1b.jpeg\n', 1, 'aawe@qq.com', '工作', NULL, '<p>请编辑您的个人简介...</p>', NULL, NULL, NULL, 4, 0),
(14, 40, '东方人', 'teacher/20150901/55e532da9085e.jpeg', 1, 'rweg@qq.com', '高中,大学,工作', NULL, '<p>请编辑您的个人简介...</p>', '北京', NULL, NULL, 5, 0),
(15, 41, '估计', 'teacher/20150901/55e533768f702.jpeg', 1, 'wqf@ww.com', '', NULL, '<p>请编辑您的个人简介...</p>', '北京', NULL, NULL, 11, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ca_user`
--

CREATE TABLE IF NOT EXISTS `ca_user` (
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL COMMENT '1男2女0未知',
  `status` tinyint(1) DEFAULT NULL COMMENT '1高中 2大学,3工作未满1年,4工作1-3年,5工作3-5年,6工作5年以上',
  `school` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '专业（2）',
  `student_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '艺体生，少数名族考生，是否国家专项计划，是否军校或国防生（1） ［0000］0表示否，1表示是',
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '居住城市'
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者信息';

--
-- 转存表中的数据 `ca_user`
--

INSERT INTO `ca_user` (`user_id`, `account_id`, `name`, `email`, `gender`, `status`, `school`, `college`, `student_type`, `city`) VALUES
(12, 38, 'eee', 'gg@aa.com', 1, 6, NULL, NULL, NULL, '北京'),
(11, 36, 'eqwr', 'ewqr@qq.com', 1, 1, 'ewer', NULL, '0010', '广州'),
(10, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Indexes for table `ca_card`
--
ALTER TABLE `ca_card`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `ca_comment`
--
ALTER TABLE `ca_comment`
  ADD PRIMARY KEY (`coment_id`);

--
-- Indexes for table `ca_teacher`
--
ALTER TABLE `ca_teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `ca_user`
--
ALTER TABLE `ca_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ca_account`
--
ALTER TABLE `ca_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `ca_admin`
--
ALTER TABLE `ca_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_card`
--
ALTER TABLE `ca_card`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ca_comment`
--
ALTER TABLE `ca_comment`
  MODIFY `coment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_teacher`
--
ALTER TABLE `ca_teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ca_user`
--
ALTER TABLE `ca_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
