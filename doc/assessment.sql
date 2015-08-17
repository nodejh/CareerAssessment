-- Host: localhost
-- Generation Time: 2015-08-03 18:20:45
-- 服务器版本： 5.7.7-rc


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `assessment`
--

-- --------------------------------------------------------

--
-- 表的结构 `Admin`
--

CREATE TABLE IF NOT EXISTS `Admin` (
  `admin_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `admin_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyINT(1) NOT NULL COMMENT '管理员类型，用来分配权限,1为超级管理员，2为普通管理员'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员';

-- --------------------------------------------------------

--
-- 表的结构 `Card`
--

CREATE TABLE IF NOT EXISTS `Card` (
  `card_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyINT(1) NOT NULL COMMENT '会员卡类型。1可以做两个测评，可以看到简单结果，不能预约咨询师；2可以做四个测评，能预约'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员卡';

-- --------------------------------------------------------

--
-- 表的结构 `Comment`
--

CREATE TABLE IF NOT EXISTS `Comment` (
  `coment_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) NOT NULL,
  `teacher_id` INT(11)  NOT NULL,
  `attidude_score` INT(11) NOT NULL COMMENT '服务态度评分(1-5)',
  `professional_score` INT(11) NOT NULL COMMENT '专业程度评分(1-5)',
  `content` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评论';

-- --------------------------------------------------------

--
-- 表的结构 `Teacher`
--

CREATE TABLE IF NOT EXISTS `Teacher` (
  `teacher_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` INT(200) NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '咨询师图片',
  `gender` tinyINT(1) NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NULL,
  `type` VARCHAR(15) NOT NULL COMMENT '[1,2,3,4,5,6] 多选，用逗号隔开',
  `free_time` TINYINT(2) NOT NULL COMMENT '空闲时间，两位整数，星期+时间段。12 周一，第二个时段。时段：9:00-10:30-12:00;2:30-4:00-5:30;7:00-8:30-10:00',
  `introduction` varchar(255) NOT NULL COMMENT '咨询师简介'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师';

-- --------------------------------------------------------

--
-- 表的结构 `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NULL,
  `gender` tinyINT(1) NOT NULL,
  `study_status` TINYINT(1) NOT NULL COMMENT '1高中 2大学及以上,3工作未满1年,4工作1-3年,5工作3-5年,6工作5年以上',
  `school_name` VARCHAR(200) COLLATE utf8_unicode_ci NOT NULL,
  `college` VARCHAR(200) COLLATE utf8_unicode_ci NULL COMMENT '专业（2）',
  `senior_high_status` INT(4) NULL COMMENT '艺体生，少数名族考生，是否国家专项计划，是否军校或国防生（1） ［0000］0表示否，1表示是',
  `card_id` INT(11) NOT NULL,
  `type` TINYINT(1) NOT NULL COMMENT '1可以做两个测评，可以看到简单结果，不能预约咨询师；2可以做四个测评，能预约'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者';