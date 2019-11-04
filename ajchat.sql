-- phpMyAdmin SQL Dump
-- version 2.6.4-pl4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 19, 2006 at 02:43 PM
-- Server version: 5.0.16
-- PHP Version: 5.0.0
-- 
-- Database: `ajchat`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ac_rooms`
-- 

CREATE TABLE `ac_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomname` varchar(50) NOT NULL default '',
  `updated` double NOT NULL default '0',
  `lines` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `roomname` (`roomname`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ac_rooms`
-- 

INSERT INTO `ac_rooms` VALUES (1, 'technology', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `ac_users`
-- 

CREATE TABLE `ac_users` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `maxlines` tinyint(3) NOT NULL,
  `dateformat` tinyint(1) NOT NULL,
  `timezone` float NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ac_users`
-- 

INSERT INTO `ac_users` VALUES (1, 'guest', '0', '', 0, 0, 0);
