/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : localhost:3306
 Source Schema         : yophp

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 20/01/2020 15:27:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `age` tinyint(4) DEFAULT '0',
  `sex` tinyint(1) DEFAULT '0',
  `update_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test
-- ----------------------------
BEGIN;
INSERT INTO `test` VALUES (2, 'mike', 18, 1, '2020-01-16 18:30:55', '2020-01-19 18:30:58');
INSERT INTO `test` VALUES (3, 'tom', 19, 1, '2020-01-02 20:08:22', '2020-01-19 20:08:25');
INSERT INTO `test` VALUES (4, 'jacky3', 20, 2, '2020-01-19 20:22:13', '2020-01-19 20:22:13');
INSERT INTO `test` VALUES (5, 'lose104', 16, 1, '2020-01-20 15:00:10', '2020-01-19 20:22:50');
INSERT INTO `test` VALUES (6, 'jacky13', 17, 2, '2020-01-19 20:27:40', '2020-01-19 20:27:40');
INSERT INTO `test` VALUES (7, 'jacky80', 21, 2, '2020-01-19 20:28:35', '2020-01-19 20:28:35');
INSERT INTO `test` VALUES (8, 'jacky83', 21, 2, '2020-01-19 20:28:41', '2020-01-19 20:28:41');
INSERT INTO `test` VALUES (9, 'jacky12', 21, 2, '2020-01-19 20:38:25', '2020-01-19 20:38:25');
INSERT INTO `test` VALUES (10, 'jacky8', 22, 2, '2020-01-19 20:38:36', '2020-01-19 20:38:36');
INSERT INTO `test` VALUES (11, 'jacky91', 22, 2, '2020-01-19 20:39:08', '2020-01-19 20:39:08');
INSERT INTO `test` VALUES (12, 'jacky60', 21, 1, '2020-01-19 20:39:26', '2020-01-19 20:39:26');
INSERT INTO `test` VALUES (13, 'jacky13', 18, 2, '2020-01-19 20:41:21', '2020-01-19 20:41:21');
INSERT INTO `test` VALUES (14, 'jacky9', 21, 2, '2020-01-19 20:41:42', '2020-01-19 20:41:42');
INSERT INTO `test` VALUES (15, 'jacky54', 16, 1, '2020-01-19 20:42:28', '2020-01-19 20:42:28');
INSERT INTO `test` VALUES (16, 'jacky2', 16, 1, '2020-01-19 22:34:29', '2020-01-19 22:34:29');
INSERT INTO `test` VALUES (17, 'jacky37', 16, 2, '2020-01-19 22:51:48', '2020-01-19 22:51:48');
INSERT INTO `test` VALUES (18, 'jacky43', 19, 2, '2020-01-20 11:38:52', '2020-01-20 11:38:52');
INSERT INTO `test` VALUES (19, 'jacky42', 18, 1, '2020-01-20 11:46:28', '2020-01-20 11:46:28');
INSERT INTO `test` VALUES (20, 'jacky69', 18, 1, '2020-01-20 11:48:41', '2020-01-20 11:48:41');
INSERT INTO `test` VALUES (21, 'jacky96', 21, 2, '2020-01-20 11:48:55', '2020-01-20 11:48:55');
INSERT INTO `test` VALUES (22, 'jacky33', 17, 2, '2020-01-20 11:49:26', '2020-01-20 11:49:26');
INSERT INTO `test` VALUES (23, 'jacky74', 16, 1, '2020-01-20 11:49:34', '2020-01-20 11:49:34');
INSERT INTO `test` VALUES (24, 'jacky20', 20, 2, '2020-01-20 11:50:53', '2020-01-20 11:50:53');
INSERT INTO `test` VALUES (25, 'jacky54', 18, 2, '2020-01-20 11:51:28', '2020-01-20 11:51:28');
INSERT INTO `test` VALUES (26, 'jacky11', 21, 2, '2020-01-20 11:51:43', '2020-01-20 11:51:43');
INSERT INTO `test` VALUES (27, 'jacky81', 19, 2, '2020-01-20 11:51:57', '2020-01-20 11:51:57');
INSERT INTO `test` VALUES (29, 'jacky69', 20, 2, '2020-01-20 11:52:59', '2020-01-20 11:52:59');
INSERT INTO `test` VALUES (30, 'jacky1', 21, 2, '2020-01-20 11:53:55', '2020-01-20 11:53:55');
INSERT INTO `test` VALUES (31, 'jacky70', 17, 1, '2020-01-20 11:54:27', '2020-01-20 11:54:27');
INSERT INTO `test` VALUES (32, 'jacky31', 22, 1, '2020-01-20 11:54:54', '2020-01-20 11:54:54');
INSERT INTO `test` VALUES (33, 'jacky47', 17, 2, '2020-01-20 11:55:14', '2020-01-20 11:55:14');
INSERT INTO `test` VALUES (34, 'jacky97', 17, 2, '2020-01-20 11:55:34', '2020-01-20 11:55:34');
INSERT INTO `test` VALUES (35, 'jacky62', 22, 1, '2020-01-20 11:57:38', '2020-01-20 11:57:38');
INSERT INTO `test` VALUES (36, 'jacky84', 21, 1, '2020-01-20 11:58:00', '2020-01-20 11:58:00');
INSERT INTO `test` VALUES (37, 'jacky82', 21, 2, '2020-01-20 11:58:35', '2020-01-20 11:58:35');
INSERT INTO `test` VALUES (38, 'jacky39', 20, 2, '2020-01-20 11:58:57', '2020-01-20 11:58:57');
INSERT INTO `test` VALUES (39, 'jacky40', 18, 1, '2020-01-20 11:59:14', '2020-01-20 11:59:14');
INSERT INTO `test` VALUES (40, 'jacky6', 19, 1, '2020-01-20 11:59:18', '2020-01-20 11:59:18');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
