/*
 Navicat Premium Data Transfer

 Source Server         : nguyenduckien
 Source Server Type    : MySQL
 Source Server Version : 100427
 Source Host           : localhost:3306
 Source Schema         : nro

 Target Server Type    : MySQL
 Target Server Version : 100427
 File Encoding         : 65001

 Date: 31/07/2023 16:33:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for adminpanel
-- ----------------------------
DROP TABLE IF EXISTS `adminpanel`;
CREATE TABLE `adminpanel`  (
  `domain` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `logo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `trangthai` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `android` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `iphone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `windows` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `java` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of adminpanel
-- ----------------------------
INSERT INTO `adminpanel` VALUES ('https://nrotwitch.me/', '../image/logo.png', 'hoatdong', 'NroTwitch.apk', 'NroTwitch.ipa', 'NroTwitch.rar', 'NroTwitch.jar');

SET FOREIGN_KEY_CHECKS = 1;
