/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50711
 Source Host           : localhost
 Source Database       : chat

 Target Server Type    : MySQL
 Target Server Version : 50711
 File Encoding         : utf-8

 Date: 09/27/2016 19:21:10 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `ct_swarm`
-- ----------------------------
DROP TABLE IF EXISTS `ct_swarm`;
CREATE TABLE `ct_swarm` (
  `swarm_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '群ID',
  `swarm_name` varchar(250) DEFAULT NULL COMMENT '群名称',
  `user_id` int(11) DEFAULT NULL COMMENT '创建此群的用户ID',
  `avatar` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`swarm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ct_swarm`
-- ----------------------------
BEGIN;
INSERT INTO `ct_swarm` VALUES ('1', 'PHP技术群', '3', 'http://img0.imgtn.bdimg.com/it/u=1499900825,876847483&fm=21&gp=0.jpg');
COMMIT;

-- ----------------------------
--  Table structure for `ct_swarm_user`
-- ----------------------------
DROP TABLE IF EXISTS `ct_swarm_user`;
CREATE TABLE `ct_swarm_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `swarm_id` int(11) DEFAULT NULL COMMENT '群ID',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ct_swarm_user`
-- ----------------------------
BEGIN;
INSERT INTO `ct_swarm_user` VALUES ('4', '1', '5'), ('42', '1', '4');
COMMIT;

-- ----------------------------
--  Table structure for `ct_user`
-- ----------------------------
DROP TABLE IF EXISTS `ct_user`;
CREATE TABLE `ct_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(250) NOT NULL DEFAULT '' COMMENT '用户名',
  `remark_name` varchar(250) NOT NULL DEFAULT '',
  `password` varchar(250) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `sign` varchar(250) NOT NULL DEFAULT '' COMMENT '个性签名',
  `avatar` text COMMENT '用户头像',
  `sex` tinyint(1) DEFAULT NULL,
  `age` char(5) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ct_user`
-- ----------------------------
BEGIN;
INSERT INTO `ct_user` VALUES ('5', 'a2', 'a2', '202cb962ac59075b964b07152d234b70', '0', '你很懒，没有写签名123', 'http://pic11.nipic.com/20101119/3320946_221711832717_2.jpg', null, null, null), ('4', 'a1', 'a1', '202cb962ac59075b964b07152d234b70', '0', '没个性！111', 'http://pic.4j4j.cn/upload/pic/20130909/681ebf9d64.jpg', null, null, null), ('3', 'huguijian', '小胡胡', '202cb962ac59075b964b07152d234b70', '0', '没钱说话不硬气', 'http://i2.cqnews.net/car/attachement/jpg/site82/20120817/5404a6b61e3c1197fb211d.jpg', null, null, null), ('6', 'a3', 'a3', '202cb962ac59075b964b07152d234b70', '0', '哈哈', null, null, null, null), ('7', 'a5', '', '202cb962ac59075b964b07152d234b70', '0', '', null, null, null, null), ('8', 'haha', 'haha', '202cb962ac59075b964b07152d234b70', '0', '哈哈', 'http://img2.imgtn.bdimg.com/it/u=2403579423,941932607&fm=21&gp=0.jpg', null, null, null), ('9', 'diao', 'diao', '202cb962ac59075b964b07152d234b70', '0', '', 'http://e.hiphotos.baidu.com/image/h%3D200/sign=e592c9a3bb1bb0519024b4280678da77/d6ca7bcb0a46f21ff2ec9bd2fe246b600d33ae53.jpg', null, null, null);
COMMIT;

-- ----------------------------
--  Table structure for `ct_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `ct_user_group`;
CREATE TABLE `ct_user_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ct_user_group`
-- ----------------------------
BEGIN;
INSERT INTO `ct_user_group` VALUES ('32', '3', '哈哈'), ('25', '5', '测试'), ('29', '4', '哈哈'), ('30', '3', '我的好友'), ('31', '3', '游戏'), ('33', '4', '千千'), ('34', '6', '我的好友'), ('35', '9', '我的好友');
COMMIT;

-- ----------------------------
--  Table structure for `ct_user_tree`
-- ----------------------------
DROP TABLE IF EXISTS `ct_user_tree`;
CREATE TABLE `ct_user_tree` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `group_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ct_user_tree`
-- ----------------------------
BEGIN;
INSERT INTO `ct_user_tree` VALUES ('93', '4', '3', '29', null), ('94', '3', '4', '30', null), ('95', '3', '9', '31', null), ('96', '9', '3', '35', null);
COMMIT;



SET FOREIGN_KEY_CHECKS = 1;
