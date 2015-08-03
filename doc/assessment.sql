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
  `admin_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `admin_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '管理员类型，用来分配权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员';

-- --------------------------------------------------------

--
-- 表的结构 `Card`
--

CREATE TABLE IF NOT EXISTS `Card` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '会员卡类型'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员卡';

-- --------------------------------------------------------

--
-- 表的结构 `Comment`
--

CREATE TABLE IF NOT EXISTS `Comment` (
  `coment_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `parent_id` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `content` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评论';

-- --------------------------------------------------------

--
-- 表的结构 `Teacher`
--

CREATE TABLE IF NOT EXISTS `Teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` int(200) NOT NULL,
  `picture` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '咨询师图片',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='咨询师';

-- --------------------------------------------------------

--
-- 表的结构 `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `subject` tinyint(2) NOT NULL,
  `senior_high` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `university` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='来访者';

