-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th3 08, 2024 lúc 10:46 AM
-- Phiên bản máy phục vụ: 5.7.34
-- Phiên bản PHP: 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `demo`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `thoi_vang` int(255) NOT NULL DEFAULT '0',
  `reward` longtext,
  `vnd` int(255) DEFAULT '0',
  `tongnap` int(11) NOT NULL DEFAULT '0',
  `admin` int(11) NOT NULL DEFAULT '0',
  `mkc2` varchar(100) NOT NULL DEFAULT '0',
  `gioithieu` int(11) NOT NULL DEFAULT '0',
  `recaf` int(11) DEFAULT NULL,
  `vnd_reward` varchar(255) DEFAULT NULL,
  `gmail` varchar(100) DEFAULT NULL,
  `tichdiem` int(11) DEFAULT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `recovery_code` int(11) DEFAULT NULL,
  `diemdanh` int(11) NOT NULL DEFAULT '0',
  `code` int(11) NOT NULL DEFAULT '0',
  `xacminh` int(11) NOT NULL DEFAULT '0',
  `thoigian_xacminh` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
