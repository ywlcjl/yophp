/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80044 (8.0.44)
 Source Host           : localhost:3306
 Source Schema         : yophp

 Target Server Type    : MySQL
 Target Server Version : 80044 (8.0.44)
 File Encoding         : 65001

 Date: 21/11/2025 20:51:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `age` tinyint NULL DEFAULT 0,
  `sex` tinyint(1) NULL DEFAULT 0,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'ray753', 17, 1, '2025-11-21 20:46:15', '2025-11-21 20:46:15');
INSERT INTO `user` VALUES (2, 'rose366', 18, 2, '2025-11-21 20:46:20', '2025-11-21 20:46:20');
INSERT INTO `user` VALUES (3, 'lose1479', 26, 2, '2025-11-21 20:51:02', '2025-11-21 20:46:22');
INSERT INTO `user` VALUES (4, 'jack637', 17, 2, '2025-11-21 20:46:26', '2025-11-21 20:46:26');
INSERT INTO `user` VALUES (5, 'ray227', 30, 1, '2025-11-21 20:46:27', '2025-11-21 20:46:27');
INSERT INTO `user` VALUES (6, 'ray279', 19, 2, '2025-11-21 20:46:27', '2025-11-21 20:46:27');
INSERT INTO `user` VALUES (7, 'rick104', 20, 2, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `user` VALUES (8, 'jack619', 29, 2, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `user` VALUES (9, 'ray348', 19, 1, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `user` VALUES (10, 'rick492', 19, 2, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `user` VALUES (11, 'tom834', 19, 2, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `user` VALUES (12, 'zero148', 23, 1, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `user` VALUES (13, 'rose444', 24, 2, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `user` VALUES (14, 'zero767', 24, 2, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `user` VALUES (15, 'bong660', 30, 1, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `user` VALUES (16, 'mike245', 24, 2, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `user` VALUES (17, 'rick491', 22, 2, '2025-11-21 20:46:30', '2025-11-21 20:46:30');
INSERT INTO `user` VALUES (18, 'tom265', 17, 1, '2025-11-21 20:46:30', '2025-11-21 20:46:30');
INSERT INTO `user` VALUES (19, 'saw161', 25, 1, '2025-11-21 20:46:30', '2025-11-21 20:46:30');
INSERT INTO `user` VALUES (20, 'tom165', 27, 2, '2025-11-21 20:46:30', '2025-11-21 20:46:30');
INSERT INTO `user` VALUES (21, 'bong169', 30, 1, '2025-11-21 20:50:58', '2025-11-21 20:50:58');
INSERT INTO `user` VALUES (22, 'saw676', 17, 1, '2025-11-21 20:50:59', '2025-11-21 20:50:59');
INSERT INTO `user` VALUES (23, 'zero312', 21, 1, '2025-11-21 20:51:00', '2025-11-21 20:51:00');
INSERT INTO `user` VALUES (24, 'bong146', 26, 1, '2025-11-21 20:51:00', '2025-11-21 20:51:00');
INSERT INTO `user` VALUES (25, 'rose617', 23, 1, '2025-11-21 20:51:00', '2025-11-21 20:51:00');
INSERT INTO `user` VALUES (26, 'jack868', 24, 1, '2025-11-21 20:51:01', '2025-11-21 20:51:01');
INSERT INTO `user` VALUES (27, 'viter118', 24, 2, '2025-11-21 20:51:01', '2025-11-21 20:51:01');
INSERT INTO `user` VALUES (28, 'mike687', 16, 1, '2025-11-21 20:51:02', '2025-11-21 20:51:02');
INSERT INTO `user` VALUES (29, 'zero481', 21, 2, '2025-11-21 20:51:02', '2025-11-21 20:51:02');
INSERT INTO `user` VALUES (30, 'mike342', 27, 2, '2025-11-21 20:51:02', '2025-11-21 20:51:02');

SET FOREIGN_KEY_CHECKS = 1;
