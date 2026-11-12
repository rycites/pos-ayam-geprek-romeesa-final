-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Okt 2026 pada 07.41
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
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_menu`, `jumlah`, `subtotal`, `status`) VALUES
(1, 1, 1, 1, 18000, 'selesai'),
(2, 1, 5, 1, 6000, 'selesai'),
(3, 1, 6, 1, 5000, 'selesai'),
(4, 2, 3, 1, 15000, 'selesai'),
(5, 2, 2, 1, 17000, 'selesai'),
(6, 2, 5, 1, 6000, 'selesai'),
(7, 3, 2, 1, 17000, 'selesai'),
(8, 3, 3, 2, 30000, 'selesai'),
(9, 4, 1, 1, 18000, 'pending'),
(10, 4, 2, 1, 17000, 'pending'),
(11, 4, 4, 1, 5000, 'selesai');

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
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`, `status`, `created_at`) VALUES
(1, 'Ayam Geprek Paket Nasi (Dada)', 18000, 'makanan', 'tersedia', '2026-10-04 15:39:09'),
(2, 'Ayam Geprek Paket Nasi (Paha)', 17000, 'makanan', 'tersedia', '2026-10-04 15:39:09'),
(4, 'Es Teh Manis', 5000, 'minuman', 'tersedia', '2026-10-04 15:39:09'),
(5, 'Es Jeruk', 6000, 'minuman', 'tersedia', '2026-10-04 15:39:09'),
(6, 'Mie', 3000, 'topping', 'tersedia', '2026-10-04 15:39:09'),
(7, 'Bakso', 3000, 'topping', 'tersedia', '2026-10-04 18:51:29');

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
  `id_kasir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `no_faktur`, `tanggal`, `total_bayar`, `uang_bayar`, `uang_kembalian`, `id_kasir`) VALUES
(1, 'INV-1791111587', '2026-10-04 12:59:47', 29000, 100000, 71000, 2),
(2, 'INV-1791111606', '2026-10-04 13:00:06', 38000, 100000, 62000, 2),
(3, 'INV-1791113057', '2026-10-04 13:24:17', 47000, 50000, 3000, 2),
(4, 'INV-1791114789', '2026-10-04 13:53:09', 40000, 50000, 10000, 2);

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
(1, 'Pemilik Warung', 'owner', 'e10adc3949ba59abbe56e057f20f883e', 'owner', '2026-10-04 15:37:52'),
(2, 'Mbak Kasir', 'kasir', 'e10adc3949ba59abbe56e057f20f883e', 'kasir', '2026-10-04 15:37:52'),
(3, 'Mas Koki Dapur', 'dapur', 'e10adc3949ba59abbe56e057f20f883e', 'dapur', '2026-10-04 15:37:52');

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
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
