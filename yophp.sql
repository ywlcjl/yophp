/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80044
 Source Host           : localhost:3306
 Source Schema         : yophp

 Target Server Type    : MySQL
 Target Server Version : 80044
 File Encoding         : 65001

 Date: 21/12/2025 16:07:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for example
-- ----------------------------
DROP TABLE IF EXISTS `example`;
CREATE TABLE `example`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `desc_txt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` tinyint NULL DEFAULT 0,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of example
-- ----------------------------
INSERT INTO `example` VALUES (1, 'ray753ffl', '1700', 0, '2025-12-21 15:35:00', '2025-11-21 20:46:15');
INSERT INTO `example` VALUES (2, 'rose366', '18', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:20');
INSERT INTO `example` VALUES (3, 'lose1499', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:22');
INSERT INTO `example` VALUES (4, 'jack637', '17', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:26');
INSERT INTO `example` VALUES (5, 'ray227', '30', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:27');
INSERT INTO `example` VALUES (6, 'ray279', '19', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:27');
INSERT INTO `example` VALUES (7, 'rick104', '202', 2, '2025-12-08 21:18:49', '2025-11-21 20:46:28');
INSERT INTO `example` VALUES (8, 'jack619', '29', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:28');
INSERT INTO `example` VALUES (9, 'ray348', '19', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:28');
INSERT INTO `example` VALUES (10, 'rick492', '19', 0, '2025-11-21 20:46:28', '2025-11-21 20:46:28');
INSERT INTO `example` VALUES (11, 'tom834', '19', 1, '2025-12-08 21:29:36', '2025-11-21 20:46:28');
INSERT INTO `example` VALUES (12, 'zero148', '23', 1, '2025-12-08 21:29:35', '2025-11-21 20:46:29');
INSERT INTO `example` VALUES (13, 'rose444', '24', 0, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `example` VALUES (14, 'zero767', '24', 0, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `example` VALUES (15, 'bong660', '30', 0, '2025-11-21 20:46:29', '2025-11-21 20:46:29');
INSERT INTO `example` VALUES (16, 'mike245', '24', 0, '2025-12-08 17:10:39', '2025-11-21 20:46:29');
INSERT INTO `example` VALUES (17, 'rick491', '22', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:30');
INSERT INTO `example` VALUES (18, 'tom265', '17', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:30');
INSERT INTO `example` VALUES (19, 'saw161', '25', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:30');
INSERT INTO `example` VALUES (20, 'tom165', '27', 1, '2025-12-08 13:31:59', '2025-11-21 20:46:30');
INSERT INTO `example` VALUES (21, 'bong169', '30', 1, '2025-12-08 13:31:59', '2025-11-21 20:50:58');
INSERT INTO `example` VALUES (22, 'saw676', '17', 1, '2025-12-08 13:31:59', '2025-11-21 20:50:59');
INSERT INTO `example` VALUES (23, 'zero312', '21', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:00');
INSERT INTO `example` VALUES (24, 'bong146', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:00');
INSERT INTO `example` VALUES (25, 'rose617', '23', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:00');
INSERT INTO `example` VALUES (26, 'jack868', '24', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:01');
INSERT INTO `example` VALUES (27, 'viter118', '24', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:01');
INSERT INTO `example` VALUES (28, 'mike687', '16', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:02');
INSERT INTO `example` VALUES (29, 'zero481', '21', 0, '2025-12-08 13:32:13', '2025-11-21 20:51:02');
INSERT INTO `example` VALUES (30, 'mike342', '27', 1, '2025-12-08 13:31:59', '2025-11-21 20:51:02');
INSERT INTO `example` VALUES (31, 'tom522', '23', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:08');
INSERT INTO `example` VALUES (32, 'viter816', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:10');
INSERT INTO `example` VALUES (33, 'tom323', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:10');
INSERT INTO `example` VALUES (34, 'jack727', '16', 0, '2025-12-08 13:32:14', '2025-11-21 20:52:10');
INSERT INTO `example` VALUES (35, 'rose505', '22', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:11');
INSERT INTO `example` VALUES (36, 'saw799', '21', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:11');
INSERT INTO `example` VALUES (37, 'tom519', '21', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:11');
INSERT INTO `example` VALUES (38, 'mike517', '23', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:11');
INSERT INTO `example` VALUES (39, 'mike213', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:12');
INSERT INTO `example` VALUES (40, 'bong917', '17', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:12');
INSERT INTO `example` VALUES (41, 'saw445', '25', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:13');
INSERT INTO `example` VALUES (42, 'jack689', '20', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:13');
INSERT INTO `example` VALUES (43, 'saw175', '21', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:14');
INSERT INTO `example` VALUES (44, 'viter182', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:14');
INSERT INTO `example` VALUES (45, 'ray752', '20', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:14');
INSERT INTO `example` VALUES (46, 'saw637', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:14');
INSERT INTO `example` VALUES (47, 'tom684', '18', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:14');
INSERT INTO `example` VALUES (48, 'jack495', '26', 1, '2025-12-08 13:31:59', '2025-11-21 20:52:15');
INSERT INTO `example` VALUES (49, 'mike695', '4545', 0, '2025-12-08 21:33:29', '2025-11-21 20:52:15');
INSERT INTO `example` VALUES (50, 'mic763', '456', 2, '2025-12-21 16:06:57', '2025-12-21 15:43:07');

SET FOREIGN_KEY_CHECKS = 1;
