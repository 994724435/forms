/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : devtest

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-12-10 21:38:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for p_log
-- ----------------------------
DROP TABLE IF EXISTS `p_log`;
CREATE TABLE `p_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `create_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p_log
-- ----------------------------
INSERT INTO `p_log` VALUES ('1', '1888', '李海龙', '2017-06-21 23:09:09');

-- ----------------------------
-- Table structure for p_user
-- ----------------------------
DROP TABLE IF EXISTS `p_user`;
CREATE TABLE `p_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `birthday` varchar(64) DEFAULT NULL,
  `number` varchar(12) DEFAULT NULL,
  `addymd` date DEFAULT NULL,
  `dadtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p_user
-- ----------------------------
INSERT INTO `p_user` VALUES ('1', '李六', '13885805888', '2017-12-04', '14', '2017-12-10', '2017-12-10 21:06:31');
