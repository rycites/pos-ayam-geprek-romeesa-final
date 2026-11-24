-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Nov 2026 pada 13.41
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ayam_geprek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_sambal` int(11) DEFAULT NULL,
  `nama_sambal` varchar(100) DEFAULT NULL,
  `level_pedas` tinyint(4) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_menu`, `id_sambal`, `nama_sambal`, `level_pedas`, `jumlah`, `subtotal`, `status`) VALUES
(1, 1, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(2, 1, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(3, 1, 6, NULL, NULL, NULL, 1, 5000, 'selesai'),
(4, 2, 3, NULL, NULL, NULL, 1, 15000, 'selesai'),
(5, 2, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(6, 2, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(7, 3, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(8, 3, 3, NULL, NULL, NULL, 2, 30000, 'selesai'),
(9, 4, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(10, 4, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(11, 4, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(12, 5, 9, NULL, NULL, NULL, 1, 20000, 'selesai'),
(13, 6, 9, NULL, NULL, NULL, 1, 20000, 'selesai'),
(14, 11, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(15, 11, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(16, 11, 7, NULL, NULL, NULL, 1, 3000, 'selesai'),
(17, 12, 9, NULL, NULL, NULL, 1, 20000, 'selesai'),
(18, 12, 7, NULL, NULL, NULL, 1, 3000, 'selesai'),
(19, 12, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(20, 13, 9, NULL, NULL, NULL, 1, 20000, 'selesai'),
(21, 14, 6, NULL, NULL, NULL, 2, 6000, 'selesai'),
(22, 14, 4, NULL, NULL, NULL, 2, 10000, 'selesai'),
(23, 14, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(24, 15, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(25, 15, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(26, 15, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(27, 15, 9, NULL, NULL, NULL, 1, 20000, 'selesai'),
(28, 16, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(29, 16, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(30, 17, 9, NULL, NULL, NULL, 2, 40000, 'selesai'),
(31, 17, 4, NULL, NULL, NULL, 2, 10000, 'selesai'),
(32, 18, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(33, 19, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(34, 20, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(35, 21, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(36, 22, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(37, 22, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(38, 22, 6, NULL, NULL, NULL, 1, 3000, 'selesai'),
(39, 22, 7, NULL, NULL, NULL, 1, 3000, 'selesai'),
(40, 22, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(41, 22, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(42, 23, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(43, 23, 6, NULL, NULL, NULL, 1, 3000, 'selesai'),
(44, 24, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(45, 24, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(46, 24, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(47, 25, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(48, 25, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(49, 26, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(50, 27, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(51, 27, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(52, 28, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(53, 28, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(54, 29, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(55, 30, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(56, 30, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(57, 31, 5, NULL, NULL, NULL, 2, 12000, 'selesai'),
(58, 32, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(59, 32, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(60, 32, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(61, 33, 6, NULL, NULL, NULL, 1, 3000, 'selesai'),
(62, 33, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(63, 33, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(64, 34, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(65, 34, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(66, 35, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(67, 35, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(68, 35, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(69, 36, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(70, 36, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(71, 37, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(72, 38, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(73, 38, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(74, 39, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(75, 39, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(76, 40, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(77, 40, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(78, 41, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(79, 42, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(80, 43, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(81, 43, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(82, 43, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(83, 44, 1, NULL, NULL, NULL, 4, 72000, 'selesai'),
(84, 45, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(85, 45, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(86, 46, 1, NULL, NULL, NULL, 3, 54000, 'selesai'),
(87, 47, 1, NULL, NULL, NULL, 3, 54000, 'selesai'),
(88, 48, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(89, 48, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(90, 48, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(91, 49, 1, NULL, NULL, NULL, 2, 36000, 'menunggu_kasir'),
(92, 50, 1, NULL, NULL, NULL, 3, 54000, 'selesai'),
(93, 51, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(94, 52, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(95, 52, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(96, 53, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(97, 53, 7, NULL, NULL, NULL, 1, 3000, 'selesai'),
(98, 53, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(99, 54, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(100, 55, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(101, 56, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(102, 57, 1, NULL, NULL, NULL, 3, 54000, 'selesai'),
(103, 58, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(104, 58, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(105, 58, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(106, 59, 1, NULL, NULL, NULL, 3, 54000, 'selesai'),
(107, 60, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(108, 61, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(109, 61, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(110, 62, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(111, 62, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(112, 62, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(113, 63, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(114, 63, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(115, 64, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(116, 65, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(117, 65, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(118, 66, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(119, 67, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(120, 67, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(121, 68, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(122, 69, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(123, 70, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(124, 71, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(125, 72, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(126, 73, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(127, 73, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(128, 74, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(129, 75, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(130, 76, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(131, 77, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(132, 77, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(133, 78, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(134, 79, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(135, 80, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(136, 81, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(137, 82, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(138, 83, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(139, 84, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(140, 85, 2, NULL, NULL, NULL, 2, 34000, 'selesai'),
(141, 86, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(142, 86, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(143, 87, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(144, 88, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(145, 88, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(146, 89, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(147, 89, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(148, 90, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(149, 91, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(150, 92, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(151, 93, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(152, 94, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(153, 95, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(154, 96, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(155, 97, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(156, 97, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(157, 98, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(158, 98, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(159, 98, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(160, 99, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(161, 100, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(162, 101, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(163, 101, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(164, 102, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(165, 102, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(166, 103, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(167, 103, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(168, 103, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(169, 104, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(170, 105, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(171, 106, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(172, 107, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(173, 108, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(174, 109, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(175, 110, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(176, 111, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(177, 112, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(178, 113, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(179, 114, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(180, 115, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(181, 116, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(182, 116, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(183, 116, 10, NULL, NULL, NULL, 1, 30000, 'selesai'),
(184, 117, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(185, 117, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(186, 117, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(187, 118, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(188, 118, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(189, 119, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(190, 119, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(191, 119, 6, NULL, NULL, NULL, 1, 3000, 'selesai'),
(192, 121, 1, NULL, NULL, NULL, 2, 36000, 'selesai'),
(193, 121, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(194, 121, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(195, 122, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(196, 123, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(197, 124, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(198, 125, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(199, 126, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(200, 127, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(201, 128, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(202, 129, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(203, 130, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(204, 131, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(205, 132, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(206, 133, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(207, 134, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(208, 135, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(209, 136, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(210, 137, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(211, 138, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(212, 139, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(213, 139, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(214, 139, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(215, 140, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(216, 141, 2, NULL, NULL, NULL, 1, 17000, 'selesai'),
(217, 142, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(218, 143, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(219, 144, 4, NULL, NULL, NULL, 1, 5000, 'selesai'),
(220, 145, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(221, 146, 1, NULL, NULL, NULL, 1, 18000, 'selesai'),
(222, 147, 5, NULL, NULL, NULL, 1, 6000, 'selesai'),
(223, 148, 14, NULL, NULL, NULL, 1, 14000, 'selesai'),
(224, 148, 15, NULL, NULL, 5, 1, 12000, 'selesai'),
(225, 149, 15, 4, 'Lado Setan', 6, 1, 12000, 'selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `kategori` enum('makanan','minuman','topping') NOT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `pakai_sambal` tinyint(1) NOT NULL DEFAULT 0,
  `pakai_level` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`, `status`, `pakai_sambal`, `pakai_level`, `created_at`, `foto`) VALUES
(1, 'Ayam Geprek Paket Nasi (Dada)', 18000, 'makanan', 'habis', 0, 0, '2026-10-04 15:39:09', '06c2e87b4a9c1f1ccf44acfb43eafec0.jpeg'),
(2, 'Ayam Geprek Paket Nasi (Paha)', 17000, 'makanan', 'habis', 0, 0, '2026-10-04 15:39:09', '781cdcd6f83dfcae9adff0d47f7d8fdb.jpg'),
(4, 'Es Teh Manis', 5000, 'minuman', 'habis', 0, 0, '2026-10-04 15:39:09', 'ba18a15b11efa2992782b379932401e9.jpg'),
(5, 'Es Jeruk', 6000, 'minuman', 'habis', 0, 0, '2026-10-04 15:39:09', '4063feff1ee4672c0621f8782a47a499.jpg'),
(6, 'Mie', 3000, 'topping', 'habis', 0, 0, '2026-10-04 15:39:09', 'cdada0d1f79b9a314456fac88a3852ff.jpg'),
(7, 'Bakso', 3000, 'topping', 'habis', 0, 0, '2026-10-04 18:51:29', '5aabe26dae4addb0f1d5fea020140ef7.jpg'),
(10, 'Paket Komplit Ayam Geprek', 30000, 'makanan', 'habis', 0, 0, '2026-10-05 15:41:33', '6bdeb87f1a3d7d2360fee42b49c1a793.jpg'),
(13, 'ayam penyet', 20000, 'makanan', 'habis', 0, 0, '2026-10-10 16:50:47', 'd1afdf520ee3d14afa7b94737c91d52e.jpg'),
(14, 'Paket Komplit ( Ayam + Nasi + Esteh )', 14000, 'makanan', 'tersedia', 1, 0, '2026-11-23 14:14:37', '755fa11d5cd018b46e0d22a495457a3a.jpeg'),
(15, 'Mie Padeh Tapakiak', 12000, 'makanan', 'tersedia', 1, 1, '2026-11-23 14:19:48', 'd278391dc190c1c85325a0ea8386b380.jpg'),
(16, 'Ayam  Geprek+ Nasi', 11000, 'makanan', 'tersedia', 1, 0, '2026-11-23 14:24:22', '0cfc2fa32a2c8c308aeb2f33a5067d20.jpg'),
(17, 'Ayam Geprek', 9000, 'makanan', 'tersedia', 1, 0, '2026-11-23 14:30:33', '6bcd4f4d7810e0d4dd2f106be3b7a152.jpg'),
(18, 'Esteh', 5000, 'minuman', 'tersedia', 0, 0, '2026-11-24 13:37:47', '3c905394dfb549e5b946cd1cc127c915.jpg'),
(19, 'Jus Jeruk', 7000, 'minuman', 'tersedia', 0, 0, '2026-11-24 13:41:59', '3dae36334bc45db1f5615b4e238ed76d.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sambal`
--

CREATE TABLE `sambal` (
  `id` int(11) NOT NULL,
  `nama_sambal` varchar(100) NOT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sambal`
--

INSERT INTO `sambal` (`id`, `nama_sambal`, `status`, `created_at`) VALUES
(1, 'Lado Merah', 'tersedia', '2026-11-23 18:02:32'),
(2, 'Lado Hijau', 'tersedia', '2026-11-23 18:02:42'),
(3, 'Sambal Uji Sudah Diubah', 'habis', '2026-11-24 07:50:34'),
(4, 'Lado Setan', 'tersedia', '2026-11-24 13:23:15'),
(5, 'lado', 'habis', '2026-11-24 13:58:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_meja`
--

CREATE TABLE `tbl_meja` (
  `id_meja` int(11) NOT NULL,
  `nomor_meja` varchar(50) NOT NULL,
  `kapasitas` int(11) DEFAULT 4,
  `status_meja` enum('Kosong','Terisi','Reserved') DEFAULT 'Kosong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_meja`
--

INSERT INTO `tbl_meja` (`id_meja`, `nomor_meja`, `kapasitas`, `status_meja`) VALUES
(1, 'Meja 1', 4, 'Kosong'),
(2, 'Meja 2', 4, 'Kosong'),
(3, 'Meja 3', 4, 'Kosong'),
(4, 'Meja 4', 4, 'Kosong'),
(5, 'Meja 5', 4, 'Kosong'),
(6, 'Meja 6', 4, 'Kosong'),
(7, 'Meja 7', 4, 'Kosong'),
(8, 'Meja 8', 4, 'Kosong'),
(9, 'Meja 9', 4, 'Kosong'),
(10, 'Meja 10', 4, 'Kosong');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `uang_bayar` int(11) NOT NULL,
  `uang_kembalian` int(11) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `nomor_meja` varchar(100) DEFAULT NULL,
  `jenis_pesanan` varchar(20) DEFAULT 'Dine-in',
  `status_bayar` varchar(20) DEFAULT 'Lunas',
  `metode_pembayaran` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `no_faktur`, `tanggal`, `total_bayar`, `uang_bayar`, `uang_kembalian`, `id_kasir`, `nomor_meja`, `jenis_pesanan`, `status_bayar`, `metode_pembayaran`) VALUES
(1, 'INV-1791111587', '2026-10-04 12:59:47', 29000, 100000, 71000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(2, 'INV-1791111606', '2026-10-04 13:00:06', 38000, 100000, 62000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(3, 'INV-1791113057', '2026-10-04 13:24:17', 47000, 50000, 3000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(4, 'INV-1791114789', '2026-10-04 13:53:09', 40000, 50000, 10000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(5, 'INV-1791180983', '2026-10-05 08:16:23', 20000, 50000, 30000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(6, 'INV-1791181023', '2026-10-05 08:17:03', 20000, 20000, 0, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(7, 'QR-20261005-217', '2026-10-05 08:59:19', 26000, 26000, 0, 0, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Pending'),
(8, 'QR-20261005-728', '2026-10-05 09:00:37', 26000, 26000, 0, 0, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Pending'),
(9, 'QR-20261005-537', '2026-10-05 09:00:40', 26000, 26000, 0, 0, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Pending'),
(10, 'QR-20261005-441', '2026-10-05 09:00:57', 26000, 26000, 0, 0, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Pending'),
(11, 'QR-20261005-770', '2026-10-05 09:02:17', 26000, 26000, 0, 0, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Pending'),
(12, 'QR-20261005-581', '2026-10-05 09:05:32', 28000, 28000, 0, 0, 'Meja 1 (umar)', 'Dine-in', 'Lunas', 'Pending'),
(13, 'QR-20261005-851', '2026-10-05 09:10:45', 20000, 20000, 0, 0, 'Meja 1 (udin)', 'Dine-in', 'Lunas', 'Pending'),
(14, 'INV-1791184388', '2026-10-05 09:13:08', 52000, 100000, 48000, 2, NULL, 'Dine-in', 'Lunas', 'Pending'),
(15, 'QR-20261005-453', '2026-10-05 10:10:23', 60000, 100000, 40000, 2, 'Meja 3 (joni)', 'Dine-in', 'Lunas', 'Cash'),
(16, 'INV-1791188160', '2026-10-05 10:16:00', 41000, 41000, 0, 2, 'Meja 8', 'Dine-in', 'Lunas', 'QRIS'),
(17, 'INV-1791188188', '2026-10-05 10:16:28', 50000, 50000, 0, 2, 'Meja 3', 'Dine-in', 'Lunas', 'Cash'),
(18, 'INV-1791188209', '2026-10-05 10:16:49', 34000, 50000, 16000, 2, 'Takeaway', 'Takeaway', 'Lunas', 'Cash'),
(19, 'INV-1791188228', '2026-10-05 10:17:08', 36000, 36000, 0, 2, 'Takeaway', 'Takeaway', 'Lunas', 'QRIS'),
(20, 'INV-1791188687', '2026-10-05 10:24:47', 36000, 36000, 0, 2, 'Takeaway (badar)', 'Takeaway', 'Lunas', 'QRIS'),
(21, 'INV-1791188712', '2026-10-05 10:25:12', 34000, 34000, 0, 2, 'Meja 6 (padil)', 'Dine-in', 'Lunas', 'QRIS'),
(22, 'INV-1791189870', '2026-10-05 10:44:30', 52000, 52000, 0, 2, 'Takeaway (Badaruddin)', 'Takeaway', 'Lunas', 'QRIS'),
(23, 'QR-20261005-578', '2026-10-05 10:44:56', 21000, 50000, 29000, 2, 'Meja 3 (Fadil)', 'Dine-in', 'Lunas', 'Cash'),
(24, 'QR-20261006-500', '2026-10-06 03:37:05', 41000, 41000, 0, 2, 'Meja 3 (badar)', 'Dine-in', 'Lunas', 'QRIS'),
(25, 'INV-1791250765', '2026-10-06 03:39:25', 41000, 50000, 9000, 2, 'Takeaway (badar)', 'Takeaway', 'Lunas', 'Cash'),
(26, 'QR-20261007-441', '2026-10-07 06:30:48', 34000, 50000, 16000, 2, 'Meja 1 (badar)', 'Dine-in', 'Lunas', 'Cash'),
(27, 'QR-20261007-479', '2026-10-07 06:36:55', 48000, 50000, 2000, 2, 'Meja 1 (Badar)', 'Dine-in', 'Lunas', 'Cash'),
(28, 'QR-20261007-611', '2026-10-07 06:42:43', 48000, 50000, 2000, 2, 'Meja 1 (Udin)', 'Dine-in', 'Lunas', 'Cash'),
(29, 'QR-20261007-421', '2026-10-07 06:43:14', 34000, 50000, 16000, 2, 'Meja 1 (Ujang)', 'Dine-in', 'Lunas', 'Cash'),
(30, 'QR-20261007-526', '2026-10-07 06:47:57', 42000, 50000, 8000, 2, 'Meja 1 (Fajar)', 'Dine-in', 'Lunas', 'Cash'),
(31, 'QR-20261007-223', '2026-10-07 06:48:21', 12000, 50000, 38000, 2, 'Meja 1 (Alpi)', 'Dine-in', 'Lunas', 'Cash'),
(32, 'QR-20261007-578', '2026-10-07 07:12:07', 65000, 65000, 0, 0, 'Meja 1 (Badaruddin)', 'Dine-in', 'Lunas', 'QRIS'),
(33, 'QR-20261007-132', '2026-10-07 07:12:26', 51000, 100000, 49000, 2, 'Meja 1 (joni)', 'Dine-in', 'Lunas', 'Cash'),
(34, 'QR-20261007-708', '2026-10-07 08:58:49', 35000, 50000, 15000, 2, 'Meja 1 (a)', 'Dine-in', 'Lunas', 'Cash'),
(35, 'QR-20261007-674', '2026-10-07 09:00:55', 40000, 40000, 0, 0, 'Meja 1 (udin)', 'Dine-in', 'Lunas', 'QRIS'),
(36, 'QR-20261007-286', '2026-10-07 09:04:01', 35000, 35000, 0, 0, 'Meja 1 (umar)', 'Dine-in', 'Lunas', 'QRIS'),
(37, 'QR-20261007-843', '2026-10-07 09:35:27', 34000, 50000, 16000, 2, 'Meja 1 (padil)', 'Dine-in', 'Lunas', 'Cash'),
(38, 'QR-20261007-235', '2026-10-07 09:37:49', 35000, 50000, 15000, 2, 'Meja 1 (hadi)', 'Dine-in', 'Lunas', 'Cash'),
(39, 'QR-20261007-830', '2026-10-07 09:48:29', 24000, 24000, 0, 0, 'Meja 1 (Udin)', 'Dine-in', 'Lunas', 'QRIS'),
(40, 'QR-20261007-734', '2026-10-07 09:55:27', 23000, 23000, 0, 0, 'Meja 1 (Guru)', 'Dine-in', 'Lunas', 'QRIS'),
(41, 'QR-20261007-954', '2026-10-07 09:56:31', 36000, 36000, 0, 0, 'Meja 1 (Ujang)', 'Dine-in', 'Lunas', 'QRIS'),
(42, 'INV-1791359865', '2026-10-07 09:57:45', 36000, 36000, 0, 2, 'Takeaway (badar)', 'Takeaway', 'Lunas', 'QRIS'),
(43, 'QR-20261007-530', '2026-10-07 09:59:52', 41000, 41000, 0, 0, 'Meja 1 (Andi)', 'Dine-in', 'Lunas', 'QRIS'),
(44, 'QR-20261007-600', '2026-10-07 10:01:40', 72000, 72000, 0, 0, 'Meja 1 (Udin)', 'Dine-in', 'Lunas', 'QRIS'),
(45, 'TRX-20261007-775', '2026-10-07 10:03:52', 35000, 35000, 0, 0, '1', 'dine_in', 'lunas', 'QRIS'),
(46, 'TRX-20261007-514', '2026-10-07 10:04:17', 54000, 100000, 46000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(47, 'TRX-20261007-463', '2026-10-07 10:04:52', 54000, 100000, 46000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(48, 'TRX-20261007-892', '2026-10-07 10:06:09', 40000, 50000, 10000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(49, 'TRX-20261007-811', '2026-10-07 10:08:05', 36000, 50000, 14000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(50, 'TRX-20261007-554', '2026-10-07 10:11:18', 54000, 100000, 46000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(51, 'TRX-20261007-245', '2026-10-07 10:11:30', 36000, 36000, 0, 0, '1', 'dine_in', 'lunas', 'QRIS'),
(52, 'TRX-20261007-614', '2026-10-07 10:12:56', 52000, 100000, 48000, 2, '1', 'dine_in', 'Lunas', 'Cash'),
(53, 'TRX-20261007-826', '2026-10-07 10:18:22', 51000, 100000, 49000, 2, 'Meja 1 (Udin)', 'dine_in', 'Lunas', 'Cash'),
(54, 'TRX-20261007-981', '2026-10-07 10:18:52', 36000, 100000, 64000, 2, 'Meja 1 (Urid)', 'dine_in', 'Lunas', 'Cash'),
(55, 'TRX-20261007-456', '2026-10-07 10:21:18', 36000, 100000, 64000, 2, 'Meja 1 (Yanto)', 'dine_in', 'Lunas', 'Cash'),
(56, 'TRX-20261007-898', '2026-10-07 10:24:02', 18000, 100000, 82000, 2, 'Meja 1 (Wudi)', 'dine_in', 'Lunas', 'Cash'),
(57, 'TRX-20261007-270', '2026-10-07 10:25:09', 54000, 100000, 46000, 2, 'Meja 2 (Haris)', 'dine_in', 'Lunas', 'Cash'),
(58, 'TRX-20261007-442', '2026-10-07 10:35:11', 59000, 100000, 41000, 2, 'Meja 1 (Budi)', 'dine_in', 'Lunas', 'Cash'),
(59, 'TRX-20261007-147', '2026-10-07 10:35:33', 54000, 54000, 0, 0, 'Meja 1 (Wudi)', 'dine_in', 'lunas', 'QRIS'),
(60, 'TRX-20261007-211', '2026-10-07 10:41:12', 6000, 10000, 4000, 2, 'Meja 1 (Kutu)', 'dine_in', 'Lunas', 'Cash'),
(61, 'TRX-20261007-877', '2026-10-07 10:41:30', 23000, 23000, 0, 0, 'Meja 1 (Badar)', 'dine_in', 'lunas', 'QRIS'),
(62, 'TRX-20261007-765', '2026-10-07 10:43:52', 53000, 53000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(63, 'TRX-20261008-392', '2026-10-08 02:49:17', 52000, 52000, 0, 0, 'Meja 1 (Akbat)', 'dine_in', 'lunas', 'QRIS'),
(64, 'TRX-20261010-781', '2026-10-10 08:17:58', 17000, 17000, 0, 0, 'Meja 3 (ari)', 'dine_in', 'lunas', 'QRIS'),
(65, 'TRX-20261010-821', '2026-10-10 08:18:52', 24000, 24000, 0, 0, 'Meja 3 (budi)', 'dine_in', 'lunas', 'QRIS'),
(66, 'TRX-20261010-889', '2026-10-10 08:23:14', 36000, 36000, 0, 0, 'Meja 3 (ali)', 'dine_in', 'lunas', 'QRIS'),
(67, 'TRX-20261010-693', '2026-10-10 08:25:36', 35000, 35000, 0, 0, 'Meja 3 (Badaruddin)', 'dine_in', 'lunas', 'QRIS'),
(68, 'TRX-20261010-381', '2026-10-10 08:26:09', 36000, 50000, 14000, 2, 'Meja 3 (padil)', 'dine_in', 'Lunas', 'Cash'),
(69, 'INV-1791613851', '2026-10-10 08:30:51', 18000, 50000, 32000, 2, 'Takeaway (badar)', 'Takeaway', 'Lunas', 'Cash'),
(70, 'INV-1791613876', '2026-10-10 08:31:16', 36000, 36000, 0, 2, 'Takeaway (ali)', 'Takeaway', 'Lunas', 'QRIS'),
(71, 'TRX-20261010-820', '2026-10-10 08:49:23', 36000, 50000, 14000, 2, 'Meja 3 (joni)', 'dine_in', 'Lunas', 'Cash'),
(72, 'TRX-20261010-566', '2026-10-10 08:52:00', 17000, 17000, 0, 0, 'Meja 3 (joni)', 'dine_in', 'lunas', 'QRIS'),
(73, 'TRX-20261010-526', '2026-10-10 08:55:06', 35000, 35000, 0, 0, 'Meja 3 (umar)', 'dine_in', 'lunas', 'QRIS'),
(74, 'TRX-20261010-964', '2026-10-10 08:56:57', 36000, 50000, 14000, 2, 'Meja 3 (andi)', 'dine_in', 'Lunas', 'Cash'),
(75, 'TRX-20261010-894', '2026-10-10 08:59:23', 34000, 50000, 16000, 2, 'Meja 3 (padil)', 'dine_in', 'Lunas', 'Cash'),
(76, 'TRX-20261010-273', '2026-10-10 09:11:21', 36000, 36000, 0, 0, 'Meja 1 (badar)', 'dine_in', 'lunas', 'QRIS'),
(77, 'TRX-20261010-526', '2026-10-10 09:11:54', 22000, 50000, 28000, 2, 'Meja 3 (umar)', 'dine_in', 'Lunas', 'Cash'),
(78, 'TRX-20261010-268', '2026-10-10 09:12:56', 17000, 50000, 33000, 2, 'Meja 1 (padil)', 'dine_in', 'Lunas', 'Cash'),
(79, 'TRX-20261010-911', '2026-10-10 09:16:09', 36000, 50000, 14000, 2, 'Meja 1 (Badaruddin)', 'dine_in', 'Lunas', 'Cash'),
(80, 'TRX-20261010-805', '2026-10-10 09:17:18', 36000, 50000, 14000, 2, 'Meja 1 (umar)', 'dine_in', 'Lunas', 'Cash'),
(81, 'TRX-20261010-537', '2026-10-10 09:17:38', 17000, 50000, 33000, 2, 'Meja 1 (joni)', 'dine_in', 'Lunas', 'Cash'),
(82, 'TRX-20261010-212', '2026-10-10 09:18:12', 36000, 36000, 0, 0, 'Meja 1 (badar)', 'dine_in', 'lunas', 'QRIS'),
(83, 'TRX-20261010-720', '2026-10-10 10:38:10', 18000, 18000, 0, 0, 'Meja 1 (badar)', 'dine_in', 'lunas', 'QRIS'),
(84, 'TRX-20261010-480', '2026-10-10 10:40:55', 17000, 17000, 0, 0, 'Meja 1 (padil)', 'dine_in', 'lunas', 'QRIS'),
(85, 'TRX-20261010-868', '2026-10-10 10:48:11', 34000, 34000, 0, 0, 'Meja 1 (andi)', 'dine_in', 'lunas', 'QRIS'),
(86, 'TRX-20261010-746', '2026-10-10 10:49:45', 22000, 22000, 0, 0, 'Meja 1 (budi)', 'dine_in', 'lunas', 'QRIS'),
(87, 'TRX-20261010-774', '2026-10-10 10:54:21', 36000, 36000, 0, 0, 'Meja 1 (hadi)', 'dine_in', 'lunas', 'QRIS'),
(88, 'TRX-20261010-814', '2026-10-10 10:59:16', 35000, 35000, 0, 0, 'Meja 1 (budi)', 'dine_in', 'lunas', 'QRIS'),
(89, 'TRX-20261010-963', '2026-10-10 11:00:11', 35000, 35000, 0, 0, 'Meja 1 (andi)', 'dine_in', 'lunas', 'QRIS'),
(90, 'TRX-20261010-908', '2026-10-10 11:05:58', 36000, 36000, 0, 0, 'Meja 1 (badar)', 'dine_in', 'lunas', 'QRIS'),
(91, 'TRX-20261010-610', '2026-10-10 11:07:23', 5000, 5000, 0, 0, 'Meja 1 (padil)', 'dine_in', 'lunas', 'QRIS'),
(92, 'TRX-20261010-441', '2026-10-10 11:11:52', 17000, 17000, 0, 0, 'Meja 1 (badar)', 'dine_in', 'lunas', 'QRIS'),
(93, 'INV-1791624219', '2026-10-10 11:23:39', 36000, 36000, 0, 2, 'Takeaway (badar)', 'Takeaway', 'Lunas', 'QRIS'),
(94, 'TRX-20261010-707', '2026-10-10 11:28:13', 18000, 50000, 32000, 2, 'Meja 1 (andi)', 'dine_in', 'Lunas', 'Cash'),
(95, 'TRX-20261010-718', '2026-10-10 11:55:05', 6000, 10000, 4000, 2, 'Meja 1 (padil)', 'dine_in', 'Lunas', 'Cash'),
(96, 'TRX-20261010-366', '2026-10-10 12:00:40', 36000, 50000, 14000, 2, 'Meja 1 (kuda)', 'dine_in', 'Lunas', 'Cash'),
(97, 'TRX-20261014-368', '2026-10-14 13:36:10', 23000, 23000, 0, 0, 'Meja 1 (budi)', 'dine_in', 'lunas', 'QRIS'),
(98, 'TRX-20261016-937', '2026-10-16 11:53:49', 40000, 40000, 0, 0, 'Meja 1 (Jarjit)', 'dine_in', 'lunas', 'QRIS'),
(99, 'TRX-20261016-584', '2026-10-16 11:57:30', 30000, 30000, 0, 0, 'Meja 1 (Alpi)', 'dine_in', 'lunas', 'QRIS'),
(100, 'TRX-20261016-157', '2026-10-16 12:01:09', 36000, 36000, 0, 0, 'Meja 1 (Udin)', 'dine_in', 'lunas', 'QRIS'),
(101, 'INV-1792829374', '2026-10-24 10:09:34', 35000, 35000, 0, 2, 'Takeaway (ujang)', 'Takeaway', 'Lunas', 'QRIS'),
(102, 'TRX-20261024-296', '2026-10-24 11:56:20', 35000, 35000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(103, 'TRX-20261024-712', '2026-10-24 13:45:48', 40000, 40000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(104, 'TRX-20261024-852', '2026-10-24 18:00:53', 18000, 18000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(105, 'TRX-20261024-687', '2026-10-24 18:04:30', 18000, 50000, 32000, 2, 'Meja 1 (Budi)', 'dine_in', 'Lunas', 'Cash'),
(106, 'INV-1792858074', '2026-10-24 18:07:54', 18000, 50000, 32000, 2, 'Takeaway (Fadhil)', 'Takeaway', 'Lunas', 'Cash'),
(107, 'TRX-20261026-746', '2026-10-26 15:19:31', 18000, 18000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(108, 'TRX-20261026-849', '2026-10-26 15:20:22', 18000, 18000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(109, 'TRX-20261026-322', '2026-10-26 15:37:31', 18000, 18000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(110, 'TRX-20261026-277', '2026-10-26 16:24:46', 18000, 50000, 32000, 2, 'Meja 1 (budi)', 'dine_in', 'Lunas', 'Cash'),
(111, 'TRX-20261026-181', '2026-10-26 16:37:48', 18000, 50000, 32000, 2, 'Meja 1 (Budi)', 'dine_in', 'Lunas', 'Cash'),
(112, 'INV-1793029806', '2026-10-26 16:50:06', 18000, 50000, 32000, 2, 'Takeaway (Fadhil)', 'Takeaway', 'Lunas', 'Cash'),
(113, 'TRX-20261026-599', '2026-10-26 17:43:05', 18000, 20000, 2000, 2, 'Meja 1 (Budi)', 'dine_in', 'Lunas', 'Cash'),
(114, 'TRX-20261026-613', '2026-10-26 18:04:09', 18000, 18000, 0, 0, 'Meja 1 (Fadhil)', 'dine_in', 'lunas', 'QRIS'),
(115, 'TRX-20261108-243', '2026-11-08 13:45:03', 18000, 18000, 0, 0, 'Meja 2 (Budi)', 'dine_in', 'lunas', 'QRIS'),
(116, 'TRX-20261122-283', '2026-11-22 12:02:19', 53000, 53000, 0, 0, 'Meja 3 (Judika)', 'dine_in', 'lunas', 'QRIS'),
(117, 'TRX-20261122-917', '2026-11-22 12:07:59', 41000, 50000, 9000, 2, 'Meja 3 (Udin)', 'dine_in', 'Lunas', 'Cash'),
(118, 'TRX-20261122-517', '2026-11-22 13:03:23', 35000, 50000, 15000, 2, 'Meja 1 (judika)', 'dine_in', 'Lunas', 'Cash'),
(119, 'TRX-20261122-505', '2026-11-22 13:10:24', 26000, 26000, 0, 2, 'Meja 1 (siti)', 'dine_in', 'Lunas', 'QRIS'),
(120, 'TRX-20261122-983', '2026-11-22 13:25:25', 58000, 58000, 0, 2, 'Meja 1 (ari)', 'dine_in', 'Lunas', 'QRIS'),
(121, 'TRX-20261122-983', '2026-11-22 13:26:59', 58000, 58000, 0, 2, 'Meja 1 (ari)', 'dine_in', 'Lunas', 'QRIS'),
(122, 'TRX-20261122-508', '2026-11-22 13:32:49', 17000, 20000, 3000, 2, 'Meja 1 (badar)', 'dine_in', 'Lunas', 'Cash'),
(123, 'TRX-20261122-315', '2026-11-22 13:33:32', 18000, 18000, 0, 2, 'Meja 2 (ujang)', 'dine_in', 'Lunas', 'QRIS'),
(124, 'TRX-20261122-175', '2026-11-22 13:39:56', 5000, 10000, 5000, 2, 'Meja 1 (fazil)', 'dine_in', 'Lunas', 'Cash'),
(125, 'TRX-20261122-320', '2026-11-22 13:40:11', 18000, 18000, 0, 2, 'Meja 1 (anton)', 'dine_in', 'Lunas', 'QRIS'),
(126, 'TRX-20261122-616', '2026-11-22 13:46:08', 6000, 10000, 4000, 2, 'Meja 2 (yanto)', 'dine_in', 'Lunas', 'Cash'),
(127, 'TRX-20261122-756', '2026-11-22 13:46:23', 5000, 5000, 0, 2, 'Meja 2 (fauzan)', 'dine_in', 'Lunas', 'QRIS'),
(128, 'TRX-20261122-232', '2026-11-22 13:51:28', 18000, 20000, 2000, 2, 'Meja 1 (ujang)', 'dine_in', 'Lunas', 'Cash'),
(129, 'TRX-20261122-489', '2026-11-22 13:54:02', 18000, 20000, 2000, 2, 'Meja 1 (ujang)', 'dine_in', 'Lunas', 'Cash'),
(130, 'TRX-20261122-692', '2026-11-22 13:54:17', 5000, 5000, 0, 2, 'Meja 1 (bakri)', 'dine_in', 'Lunas', 'QRIS'),
(131, 'TRX-20261122-126', '2026-11-22 13:56:10', 18000, 18000, 0, 2, 'Meja 1 (doni)', 'dine_in', 'Lunas', 'Cash'),
(132, 'TRX-20261122-644', '2026-11-22 14:22:53', 18000, 18000, 0, 2, 'Meja 1 (budi)', 'dine_in', 'Lunas', 'Cash'),
(133, 'TRX-20261122-161', '2026-11-22 14:23:05', 18000, 18000, 0, 2, 'Meja 1 (bdui)', 'dine_in', 'Lunas', 'QRIS'),
(134, 'TRX-20261122-663', '2026-11-22 14:23:47', 18000, 18000, 0, 2, 'Meja 1 (budi)', 'dine_in', 'Lunas', 'Cash'),
(135, 'TRX-20261122-788', '2026-11-22 14:25:19', 18000, 18000, 0, 2, 'Meja 1 (budi)', 'dine_in', 'Lunas', 'Cash'),
(136, 'TRX-20261122-234', '2026-11-22 14:28:00', 18000, 50000, 32000, 2, 'Meja 1 (udin)', 'dine_in', 'Lunas', 'Cash'),
(137, 'TRX-20261122-299', '2026-11-22 14:30:53', 17000, 17000, 0, 2, 'Meja 1 (yanto)', 'dine_in', 'Lunas', 'QRIS'),
(138, 'TRX-20261123-779', '2026-11-23 01:52:25', 5000, 5000, 0, 2, 'Meja 1 (mang ujang)', 'dine_in', 'lunas', 'QRIS'),
(139, 'TRX-20261123-968', '2026-11-23 02:00:36', 40000, 40000, 0, 2, 'Meja 1 (Bujang)', 'dine_in', 'lunas', 'QRIS'),
(140, 'TRX-20261123-388', '2026-11-23 02:05:49', 18000, 18000, 0, 2, 'Meja 2 (Badar)', 'dine_in', 'lunas', 'QRIS'),
(141, 'TRX-20261123-576', '2026-11-23 05:27:06', 17000, 17000, 0, 2, 'Meja 1 (Mak dil)', 'dine_in', 'lunas', 'QRIS'),
(142, 'TRX-20261123-964', '2026-11-23 05:29:42', 5000, 5000, 0, 2, 'Meja 2 (Edo)', 'dine_in', 'lunas', 'QRIS'),
(143, 'TRX-20261123-399', '2026-11-23 05:45:22', 18000, 18000, 0, 2, 'Meja 1 (mang ujang)', 'dine_in', 'lunas', 'QRIS'),
(144, 'TRX-20261123-914', '2026-11-23 05:51:57', 5000, 10000, 5000, 2, 'Meja 1 (udn)', 'dine_in', 'lunas', 'Cash'),
(145, 'TRX-20261123-613', '2026-11-23 05:52:25', 6000, 6000, 0, 2, 'Meja 2 (umr)', 'dine_in', 'lunas', 'QRIS'),
(146, 'INV-20261123075950-148', '2026-11-23 07:59:50', 18000, 18000, 0, 2, 'Takeaway (umar)', 'Takeaway', 'lunas', 'QRIS'),
(147, 'INV-20261123080022-401', '2026-11-23 08:00:22', 6000, 10000, 4000, 2, 'Takeaway (udi)', 'Takeaway', 'lunas', 'Cash'),
(148, 'TRX-20261124-997', '2026-11-24 07:19:21', 26000, 26000, 0, 2, 'Meja 1 (budi)', 'dine_in', 'lunas', 'QRIS'),
(149, 'TRX-20261124-533', '2026-11-24 07:34:40', 12000, 15000, 3000, 2, 'Meja 1 (ujang)', 'dine_in', 'lunas', 'Cash');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','kasir','dapur') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Pemilik ', 'owner', '$2y$10$ovjz0..IrnF6u7L0BbdCR.ZMyDP4el1CBiSz5ob.5dBNmRF6KFIIq', 'owner', '2026-10-04 15:37:52'),
(2, 'Mbak Rina', 'kasir', '$2y$10$RfChWk266drgUbRvh3qaO.yo1A6ClFJDptVWAEUpk5H8Gg95114Ry', 'kasir', '2026-10-04 15:37:52'),
(3, 'Mas Koki ', 'dapur', '$2y$10$KfgZIYjn6CGVUg5RCd0x6uZ4VXLgT2qJehBVnsRXGZqdEOBuryLLK', 'dapur', '2026-10-04 15:37:52');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sambal`
--
ALTER TABLE `sambal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_meja`
--
ALTER TABLE `tbl_meja`
  ADD PRIMARY KEY (`id_meja`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `sambal`
--
ALTER TABLE `sambal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_meja`
--
ALTER TABLE `tbl_meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
