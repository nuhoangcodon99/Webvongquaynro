-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 11, 2024 lúc 07:12 AM
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
-- Cơ sở dữ liệu: `zenkai`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ban` smallint(6) NOT NULL DEFAULT '0',
  `point_post` int(11) NOT NULL DEFAULT '0',
  `last_post` int(11) NOT NULL DEFAULT '0',
  `role` int(11) NOT NULL DEFAULT '-1',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_time_login` timestamp NOT NULL DEFAULT '2002-05-07 07:00:00',
  `last_time_logout` timestamp NOT NULL DEFAULT '2002-05-07 07:00:00',
  `ip_address` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `thoi_vang` int(11) NOT NULL DEFAULT '0',
  `server_login` int(11) NOT NULL DEFAULT '-1',
  `bd_player` double DEFAULT '1',
  `is_gift_box` tinyint(1) DEFAULT '0',
  `gift_time` varchar(255) DEFAULT '0',
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
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `create_time`, `update_time`, `ban`, `point_post`, `last_post`, `role`, `is_admin`, `last_time_login`, `last_time_logout`, `ip_address`, `active`, `thoi_vang`, `server_login`, `bd_player`, `is_gift_box`, `gift_time`, `reward`, `vnd`, `tongnap`, `admin`, `mkc2`, `gioithieu`, `recaf`, `vnd_reward`, `gmail`, `tichdiem`, `sodienthoai`, `recovery_code`, `diemdanh`, `code`, `xacminh`, `thoigian_xacminh`) VALUES
(1, 'admin', 'admin', '2024-01-10 06:27:31', '2024-01-10 06:27:31', 0, 0, 0, -1, 0, '2002-05-07 07:00:00', '2002-05-07 07:00:00', '127.0.0.1', 1, 0, -1, 1, 0, '0', NULL, 9999999, 20000000, 1, 'adminori', 0, NULL, '', '', 0, NULL, NULL, 0, 0, 0, 0),
(2, 'rosegoku', 'rosegoku', '2024-01-10 11:52:11', '2024-01-10 11:52:11', 0, 0, 0, -1, 0, '2002-05-07 07:00:00', '2002-05-07 07:00:00', '127.0.0.1', 1, 0, -1, 1, 0, '0', NULL, 0, 0, 0, '0', 0, NULL, NULL, '', NULL, NULL, NULL, 0, 0, 0, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
