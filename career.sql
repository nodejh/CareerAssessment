-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-10-28 10:04:41
-- 服务器版本： 5.7.7-rc
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `career`
--

-- --------------------------------------------------------

--
-- 表的结构 `ca_account`
--

CREATE TABLE IF NOT EXISTS `ca_account` (
  `account_id` int(11) NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1为来访者，2为咨询师',
  `register_time` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户手机号／密码表';

--
-- 转存表中的数据 `ca_account`
--

INSERT INTO `ca_account` (`account_id`, `phone`, `password`, `card_id`, `type`, `register_time`) VALUES
(1, NULL, '111111', 0, 1, 1444157859),
(2, NULL, '111111', 1, 1, 1444159623),
(3, NULL, '222222', 2, 1, 1444187855),
(4, '18333333333', '111111', 0, 2, 0),
(5, '18328999999', '1', NULL, 1, 1444659922),
(6, '18999999999', '1', NULL, 2, 1444660623),
(7, '18333330001', '1', NULL, 1, 1444660995),
(8, '18900001001', '1', NULL, 1, 1444661382),
(9, '18900001009', '1', NULL, 2, 1444661612),
(10, '18400001119', '111111', NULL, 1, 1444665487),
(11, '18400001111', '111111', NULL, 2, 1444673425),
(12, '18888888888', '1', NULL, 1, 1444691920),
(13, '18222220000', '1', NULL, 1, 1444710249),
(14, '18400001117', '1', NULL, 2, 1445273638),
(15, '13999999999', '1', NULL, 1, 1445299461),
(16, '18400001113', '1', NULL, 2, 1445315020);

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
-- 表的结构 `ca_appoint`
--

CREATE TABLE IF NOT EXISTS `ca_appoint` (
  `appoint_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'user 的 account_id',
  `teacher_id` int(11) NOT NULL COMMENT 'teacher 的 account_id',
  `user_select_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户预约的咨询师日期',
  `teacher_confirm_date` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '咨询师确认的日期',
  `user_confirm_date` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户确认的预约日期',
  `save_time` int(11) NOT NULL COMMENT '预约操作时间',
  `teacher_confirm_time` int(11) DEFAULT NULL COMMENT '咨询师确认时间',
  `user_confirm_time` int(11) DEFAULT NULL COMMENT '用户确认操作的时间',
  `finish_time` int(11) DEFAULT NULL COMMENT '完成咨询的时间',
  `status` int(11) NOT NULL COMMENT '0待咨询师确认，1待用户确认，2预约成功，3咨询完成，4来访者放弃，5咨询师放弃'
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ca_appoint`
--

INSERT INTO `ca_appoint` (`appoint_id`, `user_id`, `teacher_id`, `user_select_date`, `teacher_confirm_date`, `user_confirm_date`, `save_time`, `teacher_confirm_time`, `user_confirm_time`, `finish_time`, `status`) VALUES
(2, 2, 11, 'e-2015-10-14', NULL, NULL, 1444680439, 1444695905, NULL, 1444696137, 0),
(4, 2, 11, 'a-2015-10-16', NULL, NULL, 1444686073, 1444695974, NULL, 1444696041, 2),
(5, 12, 11, 'b-2015-10-14', NULL, NULL, 1444691925, 1444696216, NULL, 1444696279, 2),
(7, 13, 11, 'c-2015-10-14', NULL, NULL, 1444710403, 1444710868, NULL, NULL, 1),
(6, 2, 11, 'f-2015-10-15', NULL, NULL, 1444696120, 1444696404, NULL, NULL, 1),
(8, 13, 11, 'd-2015-10-15', NULL, NULL, 1444711909, 1444712200, NULL, NULL, 1),
(9, 2, 14, 'f-2015-10-20,f-2015-10-21,f-2015-10-22', 'f-2015-10-20,f-2015-10-21', 'f-2015-10-20,f-2015-10-21', 1445279943, 1445296287, NULL, 1445300891, 3),
(10, 15, 14, 'e-2015-10-23,e-2015-10-24,e-2015-10-25', 'e-2015-10-23,e-2015-10-24,e-2015-10-25', 'e-2015-10-23,e-2015-10-24,e-2015-10-25', 1445299494, 1445306777, NULL, 1445306798, 3),
(11, 2, 16, 'c-2015-10-22,c-2015-10-23,c-2015-10-24', 'c-2015-10-22,c-2015-10-23', 'c-2015-10-22,c-2015-10-23', 1445316460, 1445316598, NULL, 1445317312, 3),
(12, 2, 14, 'b-2015-10-21,b-2015-10-22', NULL, NULL, 1445317059, NULL, NULL, NULL, 0),
(13, 2, 14, 'c-2015-10-21,c-2015-10-22,c-2015-10-23', NULL, NULL, 1445317096, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ca_card`
--

CREATE TABLE IF NOT EXISTS `ca_card` (
  `card_id` int(11) NOT NULL,
  `card` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1user，2teacher'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ca_card`
--

INSERT INTO `ca_card` (`card_id`, `card`, `password`, `type`) VALUES
(1, '111111', '111111', 1),
(2, '222222', '222222', 2);

-- --------------------------------------------------------

--
-- 表的结构 `ca_	certificate`
--

CREATE TABLE IF NOT EXISTS `ca_	certificate` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ca_	certificate`
--

INSERT INTO `ca_	certificate` (`id`, `name`) VALUES
(1, '职业生涯规划师'),
(2, '心理咨询师');

-- --------------------------------------------------------

--
-- 表的结构 `ca_comment`
--

CREATE TABLE IF NOT EXISTS `ca_comment` (
  `comment_id` int(11) NOT NULL,
  `appoint_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `attitude_score` int(11) NOT NULL COMMENT '服务态度评分(1-5)',
  `professional_score` int(11) NOT NULL COMMENT '专业程度评分(1-5)',
  `content` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评论';

--
-- 转存表中的数据 `ca_comment`
--

INSERT INTO `ca_comment` (`comment_id`, `appoint_id`, `user_id`, `teacher_id`, `attitude_score`, `professional_score`, `content`, `time`) VALUES
(1, 0, 2, 14, 5, 4, NULL, 0),
(2, 0, 2, 14, 4, 5, NULL, 0),
(3, 0, 2, 14, 4, 5, NULL, 0),
(4, 0, 2, 14, 4, 5, NULL, 0),
(5, 0, 2, 14, 0, 0, NULL, 0),
(6, 0, 2, 14, 5, 4, NULL, 0),
(7, 0, 15, 14, 4, 5, NULL, 0),
(8, 0, 2, 14, 5, 4, NULL, 1445307976),
(9, 0, 2, 14, 5, 4, NULL, 1445307981),
(10, 0, 2, 14, 2, 3, '1111', 1445308009),
(11, 0, 2, 16, 5, 5, 'lllll', 1445318060),
(12, 0, 2, 16, 4, 5, '888888', 1445318081);

-- --------------------------------------------------------

--
-- 表的结构 `ca_option`
--

CREATE TABLE IF NOT EXISTS `ca_option` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='网站的一些设置选项';

--
-- 转存表中的数据 `ca_option`
--

INSERT INTO `ca_option` (`id`, `name`, `value`, `description`) VALUES
(1, 'time_per_day', '2', '每天最多预约次数'),
(2, 'number_per_appoint', '3', '每次预约最多能选择的时间段'),
(3, 'number_per_month', '8', '每个月最多预约次数');

-- --------------------------------------------------------

--
-- 表的结构 `ca_teacher`
--

CREATE TABLE IF NOT EXISTS `ca_teacher` (
  `teacher_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '咨询师个人头像',
  `gender` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '居住城市',
  `service_type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '小学，初中，高中,大学及以上,工作',
  `free_time` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '空闲时间，两位整数，星期+时间段。12 周一，第二个时段。时段：9:00-10:30-12:00;2:30-4:00-5:30;7:00-8:30-10:00',
  `introduction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '咨询师简介',
  `certificate` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `recommendation` int(11) DEFAULT NULL COMMENT '推荐等级，优先显示在页面',
  `attitude_score` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `professional_score` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师信息';

--
-- 转存表中的数据 `ca_teacher`
--

INSERT INTO `ca_teacher` (`teacher_id`, `account_id`, `name`, `avatar`, `gender`, `email`, `city`, `service_type`, `free_time`, `introduction`, `certificate`, `time`, `recommendation`, `attitude_score`, `professional_score`) VALUES
(3, 3, '1233', 'avatar/2015-10-13/561bf72dbba68.jpg', '', '111@qq.com', '上海', '小学,初中,大学及以上', NULL, 'ewqr玩儿', NULL, NULL, NULL, NULL, NULL),
(4, 11, 'fweq', 'avatar/2015-10-13/561c0365694da.jpg', '女', '234@qq.com', '北京', '小学,初中,高中,大学及以上,工作', 'a-2015-10-15,b-2015-10-15,c-2015-10-15,c-2015-10-16,d-2015-10-15,d-2015-10-16,d-2015-10-17', 'adsfq', NULL, NULL, NULL, NULL, NULL),
(5, 14, '咨询师', 'avatar/2015-10-20/56252db348e4f.jpg', '男', '111@qq.com', '杭州', '小学,初中,高中,大学及以上,工作', 'a-2015-10-20,a-2015-10-21,a-2015-10-22,a-2015-10-23,a-2015-10-24,a-2015-10-25,b-2015-10-20,b-2015-10-21,b-2015-10-22,b-2015-10-23,b-2015-10-24,b-2015-10-25,c-2015-10-20,c-2015-10-21,c-2015-10-22,c-2015-10-23,c-2015-10-24,c-2015-10-25,d-2015-10-20,d-2015-10-21,d-2015-10-22,d-2015-10-23,d-2015-10-24,d-2015-10-25,e-2015-10-20,e-2015-10-21,e-2015-10-22,e-2015-10-23,e-2015-10-24,e-2015-10-25,f-2015-10-20,f-2015-10-21,f-2015-10-22,f-2015-10-23,f-2015-10-24,f-2015-10-25', NULL, NULL, NULL, NULL, '3.8', '3.9'),
(6, 16, 'qqqqq', 'avatar/2015-10-20/5625c5900ef74.jpg', '男', '111@qq.com', NULL, NULL, 'a-2015-10-20,a-2015-10-21,a-2015-10-22,a-2015-10-23,a-2015-10-24,a-2015-10-25,b-2015-10-20,b-2015-10-22,b-2015-10-23,b-2015-10-24,b-2015-10-25,c-2015-10-20,c-2015-10-22,c-2015-10-23,c-2015-10-24,c-2015-10-25,d-2015-10-20,d-2015-10-21,d-2015-10-22,d-2015-10-23,d-2015-10-24,d-2015-10-25,e-2015-10-20,e-2015-10-21,e-2015-10-22,e-2015-10-23,e-2015-10-24,e-2015-10-25,f-2015-10-20,f-2015-10-21,f-2015-10-22,f-2015-10-23,f-2015-10-24,f-2015-10-25', NULL, NULL, NULL, NULL, '4.5', '5');

-- --------------------------------------------------------

--
-- 表的结构 `ca_user`
--

CREATE TABLE IF NOT EXISTS `ca_user` (
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '工作或学习状态。小学初中高中大学及以上，工作未满1年，1至3年，3至5年，3年以上',
  `school` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '专业（2）',
  `student_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '艺体生，少数名族考生，是否国家专项计划，(军校)国防生',
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '居住城市'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者信息';

--
-- 转存表中的数据 `ca_user`
--

INSERT INTO `ca_user` (`user_id`, `account_id`, `name`, `email`, `gender`, `status`, `school`, `college`, `student_type`, `city`) VALUES
(1, 2, 'rwt', 'iii@11.com', '男', '大学及以上', '1', 'e', NULL, '上海'),
(2, 3, 'dfgwege', NULL, '男', '小学', '', NULL, NULL, NULL),
(3, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 13, 'dsfawqe', 'dash@qq.com', '男', '小学', 'weqrw', NULL, NULL, '北京'),
(8, 15, 'laifangzhe', NULL, '男', '小学', '', NULL, NULL, NULL);

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
-- Indexes for table `ca_appoint`
--
ALTER TABLE `ca_appoint`
  ADD PRIMARY KEY (`appoint_id`);

--
-- Indexes for table `ca_card`
--
ALTER TABLE `ca_card`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `ca_	certificate`
--
ALTER TABLE `ca_	certificate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ca_comment`
--
ALTER TABLE `ca_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `ca_option`
--
ALTER TABLE `ca_option`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `ca_admin`
--
ALTER TABLE `ca_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ca_appoint`
--
ALTER TABLE `ca_appoint`
  MODIFY `appoint_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `ca_card`
--
ALTER TABLE `ca_card`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ca_	certificate`
--
ALTER TABLE `ca_	certificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ca_comment`
--
ALTER TABLE `ca_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `ca_option`
--
ALTER TABLE `ca_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ca_teacher`
--
ALTER TABLE `ca_teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ca_user`
--
ALTER TABLE `ca_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
