-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 27 Mar 2024 pada 08.28
-- Versi server: 8.0.36-cll-lve
-- Versi PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvpspbuc_slv`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`mvpspbuc`@`localhost` PROCEDURE `UpdateDeviceStatus` (IN `new_device_key` INT, IN `new_voltage` INT)   BEGIN
    DECLARE last_timestamp TIMESTAMP;
    DECLARE max_voltage_threshold INT;

    -- Mengambil waktu terakhir sensor mengirimkan data
    SELECT MAX(timestamp) INTO last_timestamp
    FROM log_sensor
    WHERE device_key = new_device_key;

    -- Mengambil nilai max_voltage_threshold dari tabel device
    SELECT max_voltage INTO max_voltage_threshold
    FROM device
    WHERE device_key = new_device_key;

    -- Memperbarui status perangkat berdasarkan waktu terakhir dan kondisi max_voltage
    UPDATE device
    SET status = CASE
                    WHEN last_timestamp IS NULL OR last_timestamp < NOW() - INTERVAL 5 MINUTE THEN 'offline'
                    WHEN new_voltage > max_voltage_threshold THEN 'warning'
                    WHEN new_voltage <= max_voltage_threshold THEN 'online'
                    ELSE 'offline'
                END
    WHERE device_key = new_device_key;

    -- Memperbarui status perangkat lainnya jika tidak ada data selama 5 menit
    UPDATE device
    SET status = 'offline'
    WHERE device_key <> new_device_key
      AND device_key NOT IN (SELECT DISTINCT device_key FROM log_sensor WHERE timestamp >= NOW() - INTERVAL 5 MINUTE);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `chat_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `contacts`
--

INSERT INTO `contacts` (`id`, `nama`, `chat_id`, `created_at`, `updated_at`) VALUES
(18, 'KALIMANTAN - MONITORING TEGANGAN FCC', '-4134613353', '2024-02-17 09:11:50', '2024-03-13 02:20:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `device`
--

CREATE TABLE `device` (
  `id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `snslv` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('online','offline','warning') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'offline',
  `witel` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `network` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe_dispenser` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_dispenser` int DEFAULT NULL,
  `dispenser_integrasi` int DEFAULT NULL,
  `dispenser_tidak_terintegrasi` int DEFAULT NULL,
  `lock_voltage` float DEFAULT NULL,
  `min_voltage` float DEFAULT NULL,
  `max_voltage` float DEFAULT NULL,
  `device_key` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telegram` enum('0','1') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `device`
--

INSERT INTO `device` (`id`, `nama`, `site`, `snslv`, `status`, `witel`, `network`, `tipe_dispenser`, `total_dispenser`, `dispenser_integrasi`, `dispenser_tidak_terintegrasi`, `lock_voltage`, `min_voltage`, `max_voltage`, `device_key`, `telegram`, `created_at`, `updated_at`) VALUES
(5, '63707002', '63707002', 'SLVKSL300010', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 3, 3, 0, 5.5, 1, 10, '9w84DzABdn', '0', '2024-02-29 09:56:53', '2024-03-22 08:26:52'),
(6, '6470705', '6470705', 'SLVKSL300009', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 4, 4, 0, 4.5, 1, 11, 'CSuEOfaHLb', '0', '2024-03-01 03:15:51', '2024-03-22 08:26:52'),
(7, '6470607', '6470607', 'SLVKSL300003', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 5, 4, 1, 5, 1, 10, 'dRI0uAXFo3', '0', '2024-03-01 04:53:53', '2024-03-22 08:26:52'),
(9, '6472116', '6472116', 'SLVKSL300005', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 4, 2, 2, 7, 1, 10, 'NF3lpqMa0a', '0', '2024-03-07 03:52:22', '2024-03-22 07:59:11'),
(10, '6472114', '6472114', 'SLVKSL300007', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 3, 3, 0, 7, 1, 10, 'VNN45f6aFD', '0', '2024-03-07 08:29:18', '2024-03-22 08:26:52'),
(11, '6470804', '6470804', 'SLVKSL300006', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 3, 3, 3, 6, 1, 10, 'D8vUoY4sfj', '0', '2024-03-09 01:41:32', '2024-03-22 08:26:52'),
(12, '6470805', '6470805', 'SLVKSL300016', 'offline', 'Banjarmasin', 'Astinet', 'Prime', 4, 3, 1, 6, 1, 10, '3NceJKF9ht', '0', '2024-03-09 01:42:18', '2024-03-22 07:59:11'),
(14, 'Test SLV 2024', 'Test SLV 2024', 'SLVKSL3000100', 'offline', 'Banjarmasin', 'Astinet', 'Tatsuno', 10, 6, 4, 1, 0, 1, 'CV3fkOfW0x', '1', '2024-03-12 06:45:50', '2024-03-22 07:59:12'),
(15, 'TESTING SLV 2', 'TESTING SLV 2', 'SLVKSL3000101', 'offline', 'Banjarmasin', 'ASTINET', 'Tatsuno', 6, 4, 2, 5, 1, 10, 'PwNKP6uvay', '1', '2024-03-21 06:53:49', '2024-03-21 06:53:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_sensor`
--

CREATE TABLE `log_sensor` (
  `id` int NOT NULL,
  `voltage` float DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_notification_timestamp` timestamp NULL DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `device_key` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_led` enum('0','1') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `report_broadcast`
--

CREATE TABLE `report_broadcast` (
  `id` int NOT NULL,
  `device_key` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `status_voltage` enum('high','normal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `broadcast_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `broadcast_total` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `report_broadcast`
--

INSERT INTO `report_broadcast` (`id`, `device_key`, `status_voltage`, `broadcast_at`, `broadcast_total`) VALUES
(1, 'CV3fkOfW0x', 'high', '2024-03-20 06:44:41', 1),
(2, 'CV3fkOfW0x', 'high', '2024-03-20 06:49:43', 1),
(3, 'D8vUoY4sfj', 'high', '2024-03-20 14:04:29', 1),
(4, 'D8vUoY4sfj', 'normal', '2024-03-20 14:09:39', 1),
(5, 'CSuEOfaHLb', 'high', '2024-03-20 14:10:16', 1),
(6, 'dRI0uAXFo3', 'high', '2024-03-20 14:13:47', 1),
(7, 'CSuEOfaHLb', 'normal', '2024-03-20 14:15:24', 1),
(8, 'CSuEOfaHLb', 'high', '2024-03-20 14:17:58', 1),
(9, 'dRI0uAXFo3', 'normal', '2024-03-20 14:18:52', 1),
(10, 'CSuEOfaHLb', 'normal', '2024-03-20 14:23:04', 1),
(11, 'D8vUoY4sfj', 'high', '2024-03-20 14:24:10', 1),
(12, 'D8vUoY4sfj', 'normal', '2024-03-20 14:29:14', 1),
(13, 'D8vUoY4sfj', 'high', '2024-03-20 14:30:30', 1),
(14, 'D8vUoY4sfj', 'normal', '2024-03-20 14:35:32', 1),
(15, 'D8vUoY4sfj', 'high', '2024-03-20 14:47:14', 1),
(16, 'D8vUoY4sfj', 'normal', '2024-03-20 14:52:30', 1),
(17, 'CSuEOfaHLb', 'high', '2024-03-20 15:06:38', 1),
(18, 'D8vUoY4sfj', 'high', '2024-03-20 15:08:29', 1),
(19, '3NceJKF9ht', 'high', '2024-03-20 15:11:31', 1),
(20, 'CSuEOfaHLb', 'normal', '2024-03-20 15:11:42', 1),
(21, 'D8vUoY4sfj', 'normal', '2024-03-20 15:13:32', 1),
(22, 'CSuEOfaHLb', 'high', '2024-03-20 15:20:19', 1),
(23, 'CSuEOfaHLb', 'normal', '2024-03-20 15:25:22', 1),
(24, 'D8vUoY4sfj', 'high', '2024-03-20 15:38:01', 1),
(25, 'CSuEOfaHLb', 'high', '2024-03-20 15:41:47', 1),
(26, 'D8vUoY4sfj', 'normal', '2024-03-20 15:43:04', 1),
(27, 'CSuEOfaHLb', 'normal', '2024-03-20 15:46:50', 1),
(28, 'CSuEOfaHLb', 'high', '2024-03-20 15:51:33', 1),
(29, 'CSuEOfaHLb', 'normal', '2024-03-20 15:56:37', 1),
(30, 'CSuEOfaHLb', 'high', '2024-03-20 16:03:43', 1),
(31, 'CSuEOfaHLb', 'normal', '2024-03-20 16:08:50', 1),
(32, 'dRI0uAXFo3', 'high', '2024-03-20 16:11:00', 1),
(33, 'D8vUoY4sfj', 'high', '2024-03-20 16:12:44', 1),
(34, 'dRI0uAXFo3', 'normal', '2024-03-20 16:16:11', 1),
(35, 'D8vUoY4sfj', 'normal', '2024-03-20 16:17:48', 1),
(36, 'D8vUoY4sfj', 'high', '2024-03-20 16:28:02', 1),
(37, 'D8vUoY4sfj', 'normal', '2024-03-20 16:33:16', 1),
(38, '3NceJKF9ht', 'high', '2024-03-20 16:39:15', 1),
(39, 'D8vUoY4sfj', 'high', '2024-03-20 16:44:27', 1),
(40, 'D8vUoY4sfj', 'normal', '2024-03-20 16:49:30', 1),
(41, '3NceJKF9ht', 'high', '2024-03-20 17:06:56', 1),
(42, 'D8vUoY4sfj', 'high', '2024-03-20 17:13:14', 1),
(43, 'D8vUoY4sfj', 'normal', '2024-03-20 17:18:17', 1),
(44, 'CSuEOfaHLb', 'high', '2024-03-20 17:20:17', 1),
(45, 'CSuEOfaHLb', 'normal', '2024-03-20 17:25:21', 1),
(46, 'D8vUoY4sfj', 'high', '2024-03-20 17:25:27', 1),
(47, '3NceJKF9ht', 'normal', '2024-03-20 17:27:00', 1),
(48, 'D8vUoY4sfj', 'normal', '2024-03-20 17:30:30', 1),
(49, 'CSuEOfaHLb', 'high', '2024-03-20 17:30:51', 1),
(50, 'D8vUoY4sfj', 'high', '2024-03-20 17:35:25', 1),
(51, 'CSuEOfaHLb', 'normal', '2024-03-20 17:35:57', 1),
(52, 'VNN45f6aFD', 'high', '2024-03-20 17:38:47', 1),
(53, 'D8vUoY4sfj', 'normal', '2024-03-20 17:40:28', 1),
(54, 'VNN45f6aFD', 'normal', '2024-03-20 17:43:52', 1),
(55, 'CSuEOfaHLb', 'high', '2024-03-20 17:52:17', 1),
(56, 'D8vUoY4sfj', 'high', '2024-03-20 17:52:41', 1),
(57, 'VNN45f6aFD', 'high', '2024-03-20 17:55:18', 1),
(58, 'CSuEOfaHLb', 'normal', '2024-03-20 17:57:24', 1),
(59, 'D8vUoY4sfj', 'normal', '2024-03-20 17:57:44', 1),
(60, 'VNN45f6aFD', 'normal', '2024-03-20 18:00:22', 1),
(61, '3NceJKF9ht', 'high', '2024-03-20 18:03:40', 1),
(62, 'D8vUoY4sfj', 'high', '2024-03-20 18:15:38', 1),
(63, 'D8vUoY4sfj', 'normal', '2024-03-20 18:20:40', 1),
(64, 'D8vUoY4sfj', 'high', '2024-03-20 18:29:46', 1),
(65, 'D8vUoY4sfj', 'normal', '2024-03-20 18:34:58', 1),
(66, 'dRI0uAXFo3', 'high', '2024-03-20 18:36:09', 1),
(67, 'CSuEOfaHLb', 'high', '2024-03-20 18:39:05', 1),
(68, 'dRI0uAXFo3', 'normal', '2024-03-20 18:41:13', 1),
(69, 'CSuEOfaHLb', 'normal', '2024-03-20 18:44:08', 1),
(70, 'D8vUoY4sfj', 'high', '2024-03-20 18:46:29', 1),
(71, 'dRI0uAXFo3', 'high', '2024-03-20 18:46:43', 1),
(72, 'D8vUoY4sfj', 'normal', '2024-03-20 18:51:30', 1),
(73, 'dRI0uAXFo3', 'normal', '2024-03-20 18:51:52', 1),
(74, 'D8vUoY4sfj', 'high', '2024-03-20 18:52:45', 1),
(75, 'dRI0uAXFo3', 'high', '2024-03-20 19:10:47', 1),
(76, 'dRI0uAXFo3', 'normal', '2024-03-20 19:15:49', 1),
(77, 'D8vUoY4sfj', 'normal', '2024-03-20 19:34:07', 1),
(78, 'CSuEOfaHLb', 'high', '2024-03-20 19:57:12', 1),
(79, 'CSuEOfaHLb', 'normal', '2024-03-20 20:02:44', 1),
(80, 'CSuEOfaHLb', 'high', '2024-03-20 20:34:15', 1),
(81, 'CSuEOfaHLb', 'normal', '2024-03-20 20:39:18', 1),
(82, '3NceJKF9ht', 'normal', '2024-03-20 20:48:41', 1),
(83, 'CSuEOfaHLb', 'high', '2024-03-20 21:46:17', 1),
(84, 'CSuEOfaHLb', 'normal', '2024-03-20 21:51:24', 1),
(85, 'VNN45f6aFD', 'high', '2024-03-20 21:54:53', 1),
(86, 'VNN45f6aFD', 'normal', '2024-03-20 21:59:55', 1),
(87, 'CSuEOfaHLb', 'high', '2024-03-20 22:18:37', 1),
(88, 'CSuEOfaHLb', 'normal', '2024-03-20 22:23:43', 1),
(89, 'D8vUoY4sfj', 'high', '2024-03-20 22:41:58', 1),
(90, 'D8vUoY4sfj', 'normal', '2024-03-20 22:47:00', 1),
(91, 'CSuEOfaHLb', 'high', '2024-03-20 23:29:53', 1),
(92, 'CSuEOfaHLb', 'normal', '2024-03-20 23:34:58', 1),
(93, 'D8vUoY4sfj', 'high', '2024-03-20 23:52:26', 1),
(94, 'D8vUoY4sfj', 'normal', '2024-03-20 23:57:28', 1),
(95, 'D8vUoY4sfj', 'high', '2024-03-21 00:01:59', 1),
(96, 'CSuEOfaHLb', 'high', '2024-03-21 00:05:34', 1),
(97, 'D8vUoY4sfj', 'normal', '2024-03-21 00:07:01', 1),
(98, 'CSuEOfaHLb', 'normal', '2024-03-21 00:10:43', 1),
(99, '9w84DzABdn', 'high', '2024-03-21 00:15:20', 1),
(100, 'CSuEOfaHLb', 'high', '2024-03-21 00:15:49', 1),
(101, '9w84DzABdn', 'normal', '2024-03-21 00:20:30', 1),
(102, 'CSuEOfaHLb', 'normal', '2024-03-21 00:20:54', 1),
(103, 'CSuEOfaHLb', 'high', '2024-03-21 00:29:54', 1),
(104, '3NceJKF9ht', 'high', '2024-03-21 00:35:00', 1),
(105, 'CSuEOfaHLb', 'normal', '2024-03-21 00:35:02', 1),
(106, 'D8vUoY4sfj', 'high', '2024-03-21 00:40:48', 1),
(107, '9w84DzABdn', 'high', '2024-03-21 00:42:52', 1),
(108, 'D8vUoY4sfj', 'normal', '2024-03-21 00:45:54', 1),
(109, '9w84DzABdn', 'normal', '2024-03-21 00:47:53', 1),
(110, 'D8vUoY4sfj', 'high', '2024-03-21 00:52:42', 1),
(111, 'D8vUoY4sfj', 'normal', '2024-03-21 00:57:44', 1),
(112, '9w84DzABdn', 'high', '2024-03-21 01:14:20', 1),
(113, 'D8vUoY4sfj', 'high', '2024-03-21 01:17:44', 1),
(114, '9w84DzABdn', 'normal', '2024-03-21 01:19:22', 1),
(115, 'CSuEOfaHLb', 'high', '2024-03-21 01:21:43', 1),
(116, 'D8vUoY4sfj', 'normal', '2024-03-21 01:22:46', 1),
(117, 'CSuEOfaHLb', 'normal', '2024-03-21 01:26:52', 1),
(118, 'D8vUoY4sfj', 'high', '2024-03-21 01:34:07', 1),
(119, 'D8vUoY4sfj', 'normal', '2024-03-21 01:39:09', 1),
(120, 'CSuEOfaHLb', 'high', '2024-03-21 01:45:07', 1),
(121, '9w84DzABdn', 'high', '2024-03-21 01:49:16', 1),
(122, 'CSuEOfaHLb', 'normal', '2024-03-21 01:50:15', 1),
(123, '9w84DzABdn', 'normal', '2024-03-21 01:54:20', 1),
(124, 'D8vUoY4sfj', 'high', '2024-03-21 01:56:05', 1),
(125, 'D8vUoY4sfj', 'normal', '2024-03-21 02:01:07', 1),
(126, '9w84DzABdn', 'high', '2024-03-21 02:02:42', 1),
(127, '9w84DzABdn', 'normal', '2024-03-21 02:07:45', 1),
(128, 'CSuEOfaHLb', 'high', '2024-03-21 02:39:38', 1),
(129, 'CSuEOfaHLb', 'normal', '2024-03-21 02:44:45', 1),
(130, 'CSuEOfaHLb', 'high', '2024-03-21 02:48:05', 1),
(131, 'D8vUoY4sfj', 'high', '2024-03-21 02:51:55', 1),
(132, 'CSuEOfaHLb', 'normal', '2024-03-21 02:53:08', 1),
(133, 'D8vUoY4sfj', 'normal', '2024-03-21 02:56:57', 1),
(134, 'D8vUoY4sfj', 'high', '2024-03-21 03:04:54', 1),
(135, 'D8vUoY4sfj', 'normal', '2024-03-21 03:09:56', 1),
(136, 'CSuEOfaHLb', 'high', '2024-03-21 03:19:31', 1),
(137, 'CSuEOfaHLb', 'normal', '2024-03-21 03:24:43', 1),
(138, 'D8vUoY4sfj', 'high', '2024-03-21 03:42:16', 1),
(139, 'D8vUoY4sfj', 'normal', '2024-03-21 03:47:18', 1),
(140, '9w84DzABdn', 'high', '2024-03-21 03:47:58', 1),
(141, '9w84DzABdn', 'normal', '2024-03-21 03:53:06', 1),
(142, 'D8vUoY4sfj', 'high', '2024-03-21 03:59:22', 1),
(143, 'D8vUoY4sfj', 'normal', '2024-03-21 04:04:24', 1),
(144, 'VNN45f6aFD', 'high', '2024-03-21 04:13:42', 1),
(145, 'VNN45f6aFD', 'normal', '2024-03-21 04:18:46', 1),
(146, '9w84DzABdn', 'high', '2024-03-21 04:33:50', 1),
(147, '9w84DzABdn', 'normal', '2024-03-21 04:38:53', 1),
(148, 'CSuEOfaHLb', 'high', '2024-03-21 04:50:26', 1),
(149, 'CSuEOfaHLb', 'normal', '2024-03-21 04:55:28', 1),
(150, '9w84DzABdn', 'high', '2024-03-21 05:05:24', 1),
(151, 'CSuEOfaHLb', 'high', '2024-03-21 05:08:09', 1),
(152, '9w84DzABdn', 'normal', '2024-03-21 05:10:32', 1),
(153, 'CSuEOfaHLb', 'normal', '2024-03-21 05:13:11', 1),
(154, 'CSuEOfaHLb', 'high', '2024-03-21 05:17:26', 1),
(155, 'CSuEOfaHLb', 'normal', '2024-03-21 05:22:31', 1),
(156, 'CSuEOfaHLb', 'high', '2024-03-21 05:27:33', 1),
(157, 'D8vUoY4sfj', 'high', '2024-03-21 05:29:38', 1),
(158, 'CSuEOfaHLb', 'normal', '2024-03-21 05:32:36', 1),
(159, '9w84DzABdn', 'high', '2024-03-21 05:34:16', 1),
(160, 'D8vUoY4sfj', 'normal', '2024-03-21 05:34:40', 1),
(161, '9w84DzABdn', 'normal', '2024-03-21 05:39:18', 1),
(162, '9w84DzABdn', 'high', '2024-03-21 05:44:29', 1),
(163, 'D8vUoY4sfj', 'high', '2024-03-21 05:46:53', 1),
(164, '9w84DzABdn', 'normal', '2024-03-21 05:49:31', 1),
(165, 'D8vUoY4sfj', 'normal', '2024-03-21 05:52:04', 1),
(166, 'CSuEOfaHLb', 'high', '2024-03-21 05:59:57', 1),
(167, 'CSuEOfaHLb', 'normal', '2024-03-21 06:05:18', 1),
(168, 'D8vUoY4sfj', 'high', '2024-03-21 06:20:47', 1),
(169, 'VNN45f6aFD', 'high', '2024-03-21 06:24:31', 1),
(170, 'D8vUoY4sfj', 'normal', '2024-03-21 06:25:49', 1),
(171, 'VNN45f6aFD', 'normal', '2024-03-21 06:29:33', 1),
(172, 'D8vUoY4sfj', 'high', '2024-03-21 06:57:34', 1),
(173, 'D8vUoY4sfj', 'normal', '2024-03-21 07:02:40', 1),
(174, 'NF3lpqMa0a', 'high', '2024-03-21 07:09:08', 1),
(175, 'CSuEOfaHLb', 'high', '2024-03-21 07:32:26', 1),
(176, 'CSuEOfaHLb', 'high', '2024-03-21 07:37:28', 1),
(177, 'CSuEOfaHLb', 'normal', '2024-03-21 07:42:30', 1),
(178, 'D8vUoY4sfj', 'high', '2024-03-21 08:17:40', 1),
(179, 'D8vUoY4sfj', 'normal', '2024-03-21 08:22:42', 1),
(180, 'CSuEOfaHLb', 'high', '2024-03-21 08:27:11', 1),
(181, 'CSuEOfaHLb', 'normal', '2024-03-21 08:32:14', 1),
(182, 'D8vUoY4sfj', 'high', '2024-03-21 09:05:31', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `level`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', NULL, '$2y$12$OeOeO02uahU9YLHC3oZpv.RTfBPEiz0HDWSBBF3kDEsoddgjxZjyS', NULL, 'admin', '2024-01-24 06:40:30', '2024-01-24 06:40:30'),
(6, 'TA_KALIMANTAN1', 'TA_KALIMANTAN1', 'bamss0007@gmail.com', NULL, '$2y$12$NwZtSzvsFxjxFJds34T2vuDaV94e5BoYEu78XDTYldTompNBb25Se', NULL, 'user', '2024-02-27 01:49:41', '2024-03-19 01:52:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_sensor`
--
ALTER TABLE `log_sensor`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `report_broadcast`
--
ALTER TABLE `report_broadcast`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `device`
--
ALTER TABLE `device`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `log_sensor`
--
ALTER TABLE `log_sensor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `report_broadcast`
--
ALTER TABLE `report_broadcast`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
