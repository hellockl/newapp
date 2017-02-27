/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : newapp

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2017-02-27 18:01:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for n_admin
-- ----------------------------
DROP TABLE IF EXISTS `n_admin`;
CREATE TABLE `n_admin` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `role_ids` varchar(100) NOT NULL DEFAULT '0' COMMENT '用户角色',
  `name` varchar(50) NOT NULL COMMENT '用户姓名',
  `password` char(32) NOT NULL COMMENT '用户密码',
  `sex` enum('男','女') NOT NULL DEFAULT '男' COMMENT '性别',
  `email` varchar(50) NOT NULL COMMENT '用户邮箱',
  `telephone` varchar(20) NOT NULL COMMENT '用户手机号码',
  `residence` varchar(1000) NOT NULL COMMENT '户籍',
  `address` varchar(100) NOT NULL COMMENT '用户联系地址',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最后一次登录时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户注册时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态 1:正常，2:停用',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of n_admin
-- ----------------------------

-- ----------------------------
-- Table structure for n_admin_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `n_admin_auth_group`;
CREATE TABLE `n_admin_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '' COMMENT '组别名称',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_manage` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1需要验证权限 2 不需要验证权限',
  `rules` char(200) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id， 多个规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of n_admin_auth_group
-- ----------------------------
INSERT INTO `n_admin_auth_group` VALUES ('27', '超级管理员', '1', '1', '2,36,38,40,42,37,39,41,43,44,14,21,24,25,26,27,22,28,29,30,31,23,32,33,34,35,45,47,48,49,51');
INSERT INTO `n_admin_auth_group` VALUES ('28', '编辑', '1', '1', '14,23,32,33');

-- ----------------------------
-- Table structure for n_admin_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `n_admin_auth_group_access`;
CREATE TABLE `n_admin_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of n_admin_auth_group_access
-- ----------------------------
INSERT INTO `n_admin_auth_group_access` VALUES ('15', '27');
INSERT INTO `n_admin_auth_group_access` VALUES ('16', '28');

-- ----------------------------
-- Table structure for n_admin_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `n_admin_auth_rule`;
CREATE TABLE `n_admin_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT '' COMMENT '图标',
  `menu_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一标识Controller/action',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `pid` tinyint(5) NOT NULL DEFAULT '0' COMMENT '菜单ID ',
  `is_menu` tinyint(1) DEFAULT '1' COMMENT '1:是主菜单 2 否',
  `is_race_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:是 2:不是',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of n_admin_auth_rule
-- ----------------------------
INSERT INTO `n_admin_auth_rule` VALUES ('2', '', '', '基本管理', '0', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('4', '&amp;#xe613;', 'User/index', '用户管理', '2', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('7', 'asdasd', 'asd/asdasd', '三级菜单', '4', '2', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('14', '', '', '权限管理', '0', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('9', 'qwe', 'qwe/qweq', 'qwe', '2', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('10', 'dfgdf', 'dfgdfg/dfgdfg', 'gdfg', '2', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('11', 'ghjg', 'ghjghj/ghjghjghj', 'hjghj', '0', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('12', 'sa', 'as/as', 'as', '0', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('13', '权限管理', '阿萨打算的/阿萨打算的', '阿斯顿', '0', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('15', '阿斯顿', 'constrolls/constrolls', '名称吗', '4', '2', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('16', '按时大大', '大阿斯顿阿斯顿/阿斯顿阿斯顿按时', '阿萨打算的', '2', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('17', '阿斯顿', '阿斯顿阿斯顿asd/阿萨打算的', '阿萨打算的asd', '16', '2', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('18', '阿斯顿', '阿萨打算的/阿萨打算的', '阿斯顿a', '16', '2', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('19', '', '', '阿萨打算的', '0', '1', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('20', 'asd', 'User/addUser', '添加用户', '4', '2', '1', '1', '2', '');
INSERT INTO `n_admin_auth_rule` VALUES ('21', '&amp;#xe624;', 'Menu/index', '菜单管理', '14', '1', '2', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('22', '&amp;#xe612;', 'AuthGroup/authGroupList', '角色管理', '14', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('23', '&amp;#xe613;', 'User/index', '用户管理', '14', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('24', '13', 'Menu/addMenu', '添加菜单', '21', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('25', '213', 'Menu/editMenu', '编辑菜单', '21', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('26', '435', 'Menu/deleteMenu', '删除菜单', '21', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('27', '13', 'Menu/viewOpt', '查看三级菜单', '21', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('28', '123', 'AuthGroup/addGroup', '添加角色', '22', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('29', '35', 'AuthGroup/editGroup', '编辑角色', '22', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('30', '345', 'AuthGroup/deleteGroup', '删除角色', '22', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('31', 'asd', 'AuthGroup/ruleGroup', '分配权限', '22', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('32', '13', 'User/addUser', '添加用户', '23', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('33', '324', 'User/editUser', '编辑用户', '23', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('34', '435', 'User/deleterUser', '删除用户', '23', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('35', '234', 'AuthGroup/giveRole', '分配角色', '23', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('36', '&amp;#xe634;', 'Banner/index', 'Banner管理', '2', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('37', '&amp;#xe62a;', 'News/Index', '资讯管理', '2', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('38', 'a', 'Banner/addBanner', '上传Banner', '36', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('39', '&amp;#xe61f;', 'News/addNews', '新增资讯', '37', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('40', '2', 'Banner/editBanner', '编缉菜单', '36', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('41', 'asdf', 'News/uploadImgForContent', '富文本图片上传', '37', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('42', '3', 'Banner/upload', '上传', '36', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('43', 'dd', 'News/editNews', '编辑资讯', '37', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('44', 'dd', 'News/deleteNews', '删除资讯', '37', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('45', '', '', '会员系统', '0', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('48', '1', 'Users/addUsers', '添加会员', '47', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('47', '&amp;#xe613;', 'Users/index', '会员管理', '45', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('49', '&amp;#xe62d', 'Help/index', '帮助管理', '45', '1', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('50', '11', 'Users/checkUsers', '审核', '47', '2', '1', '1', '1', '');
INSERT INTO `n_admin_auth_rule` VALUES ('51', '1', 'Help/matching', '匹配用户', '49', '2', '1', '1', '1', '');

-- ----------------------------
-- Table structure for n_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `n_admin_user`;
CREATE TABLE `n_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '数据编号',
  `user_name` varchar(20) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `lastlogin_time` int(11) unsigned DEFAULT NULL COMMENT '最后一次登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许用户登录(1是  2否)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='后台用户表';

-- ----------------------------
-- Records of n_admin_user
-- ----------------------------
INSERT INTO `n_admin_user` VALUES ('11', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1480572245', '2');
INSERT INTO `n_admin_user` VALUES ('15', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1488175468', '1');
INSERT INTO `n_admin_user` VALUES ('16', 'test', '098f6bcd4621d373cade4e832627b4f6', '1480667348', '1');
INSERT INTO `n_admin_user` VALUES ('17', 'wuyawnen', '90b18287d7aab11bb2caee3e0c39fd08', '1480668214', '1');

-- ----------------------------
-- Table structure for n_banner
-- ----------------------------
DROP TABLE IF EXISTS `n_banner`;
CREATE TABLE `n_banner` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `banner_name` varchar(255) DEFAULT NULL,
  `banner_img` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `type` tinyint(2) DEFAULT '1' COMMENT 'banner类型',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态',
  `create_time` int(12) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='banner表';

-- ----------------------------
-- Records of n_banner
-- ----------------------------
INSERT INTO `n_banner` VALUES ('1', '111111', '/Public/upload/2017-02-27/58b39e7335048.jpg', '1', '1', '1487662749');
INSERT INTO `n_banner` VALUES ('2', '111', '/Public/upload/2017-02-21/58abf8330e982.png', '1', '1', '1487665204');
INSERT INTO `n_banner` VALUES ('3', '12121', '/Public/upload/2017-02-21/58abf8ce3b620.png', '1', '1', '1487665367');
INSERT INTO `n_banner` VALUES ('4', '111111', '/Public/upload/2017-02-21/58ac05ac6d0d7.png', '1', '1', '1487668654');
INSERT INTO `n_banner` VALUES ('5', '1212121', '/Public/upload/2017-02-21/58ac0869caac8.png', '1', '1', '1487669355');

-- ----------------------------
-- Table structure for n_gethelp
-- ----------------------------
DROP TABLE IF EXISTS `n_gethelp`;
CREATE TABLE `n_gethelp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `amount` varchar(255) NOT NULL COMMENT '获得帮助金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='获得帮助表';

-- ----------------------------
-- Records of n_gethelp
-- ----------------------------

-- ----------------------------
-- Table structure for n_givehelp
-- ----------------------------
DROP TABLE IF EXISTS `n_givehelp`;
CREATE TABLE `n_givehelp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `amount` varchar(255) NOT NULL COMMENT '提供帮助金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(3) NOT NULL COMMENT '状态（0：待匹配；1：匹配完成，未付款；2：已打款；3：确定已打款，已完成）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='提供帮助表';

-- ----------------------------
-- Records of n_givehelp
-- ----------------------------
INSERT INTO `n_givehelp` VALUES ('1', '1', '2000', '1340000123', '0');
INSERT INTO `n_givehelp` VALUES ('2', '1', '2000', '1354446646', '0');

-- ----------------------------
-- Table structure for n_news
-- ----------------------------
DROP TABLE IF EXISTS `n_news`;
CREATE TABLE `n_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL COMMENT '类型 扩展字段',
  `title` varchar(256) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `smeta` text COMMENT '缩略图',
  `hits` int(11) DEFAULT NULL COMMENT '阅读数 点击数',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态1默认2删除',
  `author` varchar(128) DEFAULT NULL COMMENT '操作人',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of n_news
-- ----------------------------
INSERT INTO `n_news` VALUES ('1', null, '1ddd', '', '/Public/upload/newscontent/58ac007e8f414.jpg', '0', '1', 'admin', '1487667052', '1487740461');

-- ----------------------------
-- Table structure for n_recommend
-- ----------------------------
DROP TABLE IF EXISTS `n_recommend`;
CREATE TABLE `n_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recommend_id` int(11) NOT NULL COMMENT '推荐人ID',
  `phone` varchar(13) NOT NULL COMMENT '手机号',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐人表';

-- ----------------------------
-- Records of n_recommend
-- ----------------------------

-- ----------------------------
-- Table structure for n_users
-- ----------------------------
DROP TABLE IF EXISTS `n_users`;
CREATE TABLE `n_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `name` varchar(255) DEFAULT NULL COMMENT '用户真实姓名',
  `idcard` varchar(16) DEFAULT NULL COMMENT '身份证号',
  `idcard_imga` varchar(255) DEFAULT NULL COMMENT '身份证正面照',
  `idcard_imgb` varchar(255) DEFAULT NULL COMMENT '身份证正面照',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态（0：待激活；1：已激活,未审核;2：已激活,已审核；3：已禁用）',
  `phone` varchar(13) DEFAULT NULL COMMENT '手机号',
  `father_id` int(11) DEFAULT '0' COMMENT '上一级ID',
  `grand_id` int(11) DEFAULT '0' COMMENT '上上一级ID',
  `password` varchar(32) NOT NULL COMMENT '账户密码',
  `amount_password` varchar(32) NOT NULL COMMENT '资金密码',
  `create_time` datetime DEFAULT NULL COMMENT '注册时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='users';

-- ----------------------------
-- Records of n_users
-- ----------------------------
INSERT INTO `n_users` VALUES ('1', 'test', '曹操', '3604281878113132', null, null, '1', '18888888888', '0', '0', 'e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e', '2017-02-14 14:11:28');
INSERT INTO `n_users` VALUES ('2', 'ssssssss', 'ddd', null, null, null, '1', '18888888888', '0', '0', '96e79218965eb72c92a549dd5a330112', '96e79218965eb72c92a549dd5a330112', '2017-02-15 10:24:08');
