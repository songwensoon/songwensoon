/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : zendyun

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2014-04-15 22:17:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zendyun_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `zendyun_admin_user`;
CREATE TABLE `zendyun_admin_user` (
  `uid` int(3) NOT NULL AUTO_INCREMENT,
  `m_id` int(2) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `domain` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of zendyun_admin_user
-- ----------------------------
INSERT INTO `zendyun_admin_user` VALUES ('1', '1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '超级管理员', '0');
