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

 Date: 31/07/2023 13:14:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for momo_trans
-- ----------------------------
DROP TABLE IF EXISTS `momo_trans`;
CREATE TABLE `momo_trans`  (
  `ID` int NOT NULL,
  `tranId` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `io` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `partnerId` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int NOT NULL,
  `partnerName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `amount` int NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `millisecond` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
