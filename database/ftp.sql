-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 12 月 08 日 21:00
-- 服务器版本: 5.1.49
-- PHP 版本: 5.3.3-1ubuntu9.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ftpsearch`
--
CREATE DATABASE `ftpsearch` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ftpsearch`;

-- --------------------------------------------------------

--
-- 表的结构 `cat`
--

CREATE TABLE IF NOT EXISTS `cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` varchar(50) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `ipid` int(11) DEFAULT NULL,
  `acctime` mediumtext,
  PRIMARY KEY (`id`),
  KEY `fk_cat_ftpinfo` (`ipid`),
  KEY `fk_cat_cat1` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(50) DEFAULT NULL,
  `postfix` varchar(10) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `ipid` int(11) DEFAULT NULL,
  `acctime` mediumtext,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_files_ftpinfo1` (`ipid`),
  KEY `fk_files_cat1` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `files`
--


-- --------------------------------------------------------

--
-- 表的结构 `ftpinfo`
--

CREATE TABLE IF NOT EXISTS `ftpinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` varchar(15) DEFAULT NULL,
  `port` int(11) DEFAULT '21',
  `user` varchar(45) DEFAULT 'anonymous',
  `pw` varchar(45) DEFAULT NULL,
  `acc` tinyint(1) NOT NULL DEFAULT '0',
  `indb` tinyint(1) DEFAULT '0',
  `info` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='记录ftp站点相关信息' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ftpinfo`
--


--
-- 限制导出的表
--

--
-- 限制表 `cat`
--
ALTER TABLE `cat`
  ADD CONSTRAINT `fk_cat_cat1` FOREIGN KEY (`pid`) REFERENCES `cat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cat_ftpinfo` FOREIGN KEY (`ipid`) REFERENCES `ftpinfo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_cat1` FOREIGN KEY (`pid`) REFERENCES `cat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_files_ftpinfo1` FOREIGN KEY (`ipid`) REFERENCES `ftpinfo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

