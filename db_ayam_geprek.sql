-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Okt 2026 pada 06.57
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
(9, 4, 1, 1, 18000, 'selesai'),
(10, 4, 2, 1, 17000, 'selesai'),
(11, 4, 4, 1, 5000, 'selesai'),
(12, 5, 9, 1, 20000, 'selesai'),
(13, 6, 9, 1, 20000, 'selesai'),
(14, 11, 1, 1, 18000, 'selesai'),
(15, 11, 4, 1, 5000, 'selesai'),
(16, 11, 7, 1, 3000, 'selesai'),
(17, 12, 9, 1, 20000, 'selesai'),
(18, 12, 7, 1, 3000, 'selesai'),
(19, 12, 4, 1, 5000, 'selesai'),
(20, 13, 9, 1, 20000, 'selesai'),
(21, 14, 6, 2, 6000, 'selesai'),
(22, 14, 4, 2, 10000, 'selesai'),
(23, 14, 1, 2, 36000, 'selesai'),
(24, 15, 1, 1, 18000, 'selesai'),
(25, 15, 2, 1, 17000, 'selesai'),
(26, 15, 4, 1, 5000, 'selesai'),
(27, 15, 9, 1, 20000, 'selesai'),
(28, 16, 1, 2, 36000, 'selesai'),
(29, 16, 4, 1, 5000, 'selesai'),
(30, 17, 9, 2, 40000, 'selesai'),
(31, 17, 4, 2, 10000, 'selesai'),
(32, 18, 2, 2, 34000, 'selesai'),
(33, 19, 1, 2, 36000, 'selesai'),
(34, 20, 1, 2, 36000, 'selesai'),
(35, 21, 2, 2, 34000, 'selesai'),
(36, 22, 1, 1, 18000, 'selesai'),
(37, 22, 2, 1, 17000, 'selesai'),
(38, 22, 6, 1, 3000, 'selesai'),
(39, 22, 7, 1, 3000, 'selesai'),
(40, 22, 4, 1, 5000, 'selesai'),
(41, 22, 5, 1, 6000, 'selesai'),
(42, 23, 1, 1, 18000, 'selesai'),
(43, 23, 6, 1, 3000, 'selesai'),
(44, 24, 1, 1, 18000, 'selesai'),
(45, 24, 2, 1, 17000, 'selesai'),
(46, 24, 5, 1, 6000, 'selesai'),
(47, 25, 1, 2, 36000, 'selesai'),
(48, 25, 4, 1, 5000, 'selesai'),
(49, 26, 2, 2, 34000, 'selesai'),
(50, 27, 1, 1, 18000, 'selesai'),
(51, 27, 10, 1, 30000, 'selesai'),
(52, 28, 10, 1, 30000, 'selesai'),
(53, 28, 1, 1, 18000, 'selesai'),
(54, 29, 2, 2, 34000, 'selesai'),
(55, 30, 1, 2, 36000, 'pending'),
(56, 30, 5, 1, 6000, 'pending'),
(57, 31, 5, 2, 12000, 'pending');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`, `status`, `created_at`, `foto`) VALUES
(1, 'Ayam Geprek Paket Nasi (Dada)', 18000, 'makanan', 'tersedia', '2026-10-04 15:39:09', '06c2e87b4a9c1f1ccf44acfb43eafec0.jpeg'),
(2, 'Ayam Geprek Paket Nasi (Paha)', 17000, 'makanan', 'tersedia', '2026-10-04 15:39:09', '781cdcd6f83dfcae9adff0d47f7d8fdb.jpg'),
(4, 'Es Teh Manis', 5000, 'minuman', 'tersedia', '2026-10-04 15:39:09', 'ba18a15b11efa2992782b379932401e9.jpg'),
(5, 'Es Jeruk', 6000, 'minuman', 'tersedia', '2026-10-04 15:39:09', '4063feff1ee4672c0621f8782a47a499.jpg'),
(6, 'Mie', 3000, 'topping', 'tersedia', '2026-10-04 15:39:09', 'cdada0d1f79b9a314456fac88a3852ff.jpg'),
(7, 'Bakso', 3000, 'topping', 'tersedia', '2026-10-04 18:51:29', '5aabe26dae4addb0f1d5fea020140ef7.jpg'),
(10, 'Paket Komplit Ayam Geprek', 30000, 'makanan', 'tersedia', '2026-10-05 15:41:33', '6bdeb87f1a3d7d2360fee42b49c1a793.jpg');

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
(26, 'QR-20261007-441', '2026-10-07 06:30:48', 34000, 0, 0, 0, 'Meja 1 (badar)', 'Dine-in', 'Pending', 'Cash'),
(27, 'QR-20261007-479', '2026-10-07 06:36:55', 48000, 0, 0, 0, 'Meja 1 (Badar)', 'Dine-in', 'Pending', 'Cash'),
(28, 'QR-20261007-611', '2026-10-07 06:42:43', 48000, 0, 0, 0, 'Meja 1 (Udin)', 'Dine-in', 'Pending', 'QRIS'),
(29, 'QR-20261007-421', '2026-10-07 06:43:14', 34000, 0, 0, 0, 'Meja 1 (Ujang)', 'Dine-in', 'Pending', 'Cash'),
(30, 'QR-20261007-526', '2026-10-07 06:47:57', 42000, 0, 0, 0, 'Meja 1 (Fajar)', 'Dine-in', 'Pending', 'QRIS'),
(31, 'QR-20261007-223', '2026-10-07 06:48:21', 12000, 0, 0, 0, 'Meja 1 (Alpi)', 'Dine-in', 'Pending', 'Cash');

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
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
