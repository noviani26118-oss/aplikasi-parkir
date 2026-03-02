-- Database: ukk_parkir

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET SESSION sql_require_primary_key = 0;
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------

--
-- Table structure for table `tabel_users`
--

CREATE TABLE `tabel_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_users`
--

INSERT INTO `tabel_users` (`id_user`, `nama_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', '2024-02-02 00:00:00'),
(2, 'Petugas Parkir', 'petugas', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'petugas', '2024-02-02 00:00:00'),
(3, 'Pemilik', 'owner', '72122ce96bfec66e2396d2e25225d70a', 'owner', '2024-02-02 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kendaraan`
--

CREATE TABLE `tabel_kendaraan` (
  `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kendaraan` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kendaraan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tabel_kendaraan` (`id_kendaraan`, `nama_kendaraan`) VALUES
(1, 'Motor'),
(2, 'Mobil'),
(3, 'Truk');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_area_parkir`
--

CREATE TABLE `tabel_area_parkir` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `nama_area` varchar(50) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `terisi` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tabel_area_parkir` (`id_area`, `nama_area`, `kapasitas`, `terisi`) VALUES
(1, 'Lantai 1 (Motor)', 100, 0),
(2, 'Lantai 2 (Mobil)', 50, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tarif`
--

CREATE TABLE `tabel_tarif` (
  `id_tarif` int(11) NOT NULL AUTO_INCREMENT,
  `id_kendaraan` int(11) NOT NULL,
  `tarif_per_jam` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_tarif`),
  KEY `id_kendaraan` (`id_kendaraan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tabel_tarif` (`id_tarif`, `id_kendaraan`, `tarif_per_jam`) VALUES
(1, 1, 2000),
(2, 2, 5000),
(3, 3, 8000);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_transaksi`
--

CREATE TABLE `tabel_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(20) NOT NULL,
  `id_kendaraan` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `plat_nomor` varchar(15) NOT NULL,
  `jam_masuk` datetime NOT NULL,
  `jam_keluar` datetime DEFAULT NULL,
  `lama_parkir` int(11) DEFAULT NULL COMMENT 'dalam jam',
  `total_bayar` int(11) DEFAULT NULL,
  `status` enum('masuk','keluar') NOT NULL DEFAULT 'masuk',
  `tanggal_transaksi` date NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`id_transaksi`),
  KEY `id_kendaraan` (`id_kendaraan`),
  KEY `id_area` (`id_area`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_log_aktivitas`
--

CREATE TABLE `tabel_log_aktivitas` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `aktivitas` text NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `tabel_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tabel_kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tabel_area_parkir`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tabel_tarif`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tabel_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tabel_log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `tabel_tarif`
  ADD CONSTRAINT `tabel_tarif_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `tabel_kendaraan` (`id_kendaraan`) ON DELETE CASCADE;

ALTER TABLE `tabel_transaksi`
  ADD CONSTRAINT `tabel_transaksi_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `tabel_kendaraan` (`id_kendaraan`),
  ADD CONSTRAINT `tabel_transaksi_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `tabel_area_parkir` (`id_area`),
  ADD CONSTRAINT `tabel_transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tabel_users` (`id_user`);

ALTER TABLE `tabel_log_aktivitas`
  ADD CONSTRAINT `tabel_log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tabel_users` (`id_user`) ON DELETE CASCADE;

COMMIT;
