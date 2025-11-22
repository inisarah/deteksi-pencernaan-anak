-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 03:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gangguanpencernaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `cf` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aturan`
--

INSERT INTO `aturan` (`id_aturan`, `id_penyakit`, `id_gejala`, `cf`) VALUES
(1, 1, 1, 0.8),
(2, 2, 2, 0.9),
(3, 3, 3, 0.8),
(4, 1, 4, 0.7),
(5, 1, 5, 0.7);

-- --------------------------------------------------------

--
-- Table structure for table `bobotcf`
--

CREATE TABLE `bobotcf` (
  `id_bobot` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bobotcf`
--

INSERT INTO `bobotcf` (`id_bobot`, `keterangan`, `bobot`) VALUES
(1, 'Tidak', 0),
(2, 'Tidak Tahu', 0.2),
(3, 'Sedikit Yakin', 0.4),
(4, 'Cukup Yakin', 0.6),
(5, 'Yakin', 0.8),
(6, 'Sangat Yakin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` int(11) NOT NULL,
  `gejala` varchar(150) NOT NULL,
  `cf` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`id_gejala`, `gejala`, `cf`) VALUES
(1, 'Apakah Frekuensi buang air besar meningkat?', 0.8),
(2, 'Apakah Kotoran berair atau encer?', 1),
(3, 'Apakah Perut kembung?', 0.8),
(4, 'Apakah Nyeri perut atau kram?', 0.6),
(5, 'Apakah Mual atau muntah?', 0.6),
(6, 'Apakah Demam ringan hingga sedang?', 0.6),
(7, 'Apakah Lemah atau lesu?', 0.8),
(8, 'Apakah Pusing?', 0.6),
(9, 'Apakah Kehilangan nafsu makan?', 0.8),
(10, 'Apakah Rasa tidak nyaman di perut?', 0.6),
(11, 'Apakah Feses dengan darah atau lendir?', 0.8),
(12, 'Apakah Sakit kepala?', 0.8),
(13, 'Apakah Perubahan warna tinja?', 0.8),
(14, 'Apakah Kesulitan atau rasa sakit saat buang air besar?', 0.8),
(15, 'Apakah Frekuensi buang air besar yang jarang?', 1),
(16, 'Apakah Feses keras ?', 0.8),
(17, 'Apakah Kelelahan?', 0.6),
(18, 'Apakah Rasa tidak selesai setelah buang air besar?', 0.8),
(19, 'Apakah Sakit perut yang hilang setelah buang air besar?', 0.8),
(20, 'Apakah Perasaan tidak nyaman di bagian bawah perut?', 0.6),
(21, 'Apakah Nyeri atau rasa terbakar di perut bagian atas?', 0.8),
(22, 'Apakah Muntah darah atau kotoran seperti kopi', 0.8),
(23, 'Apakah Perut terasa berat atau penuh', 0.6),
(24, 'Apakah Gangguan pencernaan setelah makan berat', 0.8),
(25, 'Apakah Penurunan berat badan?', 0.8);

-- --------------------------------------------------------

--
-- Table structure for table `penyakit`
--

CREATE TABLE `penyakit` (
  `id_penyakit` int(11) NOT NULL,
  `penyakit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penyakit`
--

INSERT INTO `penyakit` (`id_penyakit`, `penyakit`) VALUES
(1, 'Diare'),
(2, 'Sembelit'),
(3, 'Gastritis');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_pasien` varchar(100) NOT NULL,
  `waktu_konsultasi` text NOT NULL,
  `kode_gejala` text NOT NULL,
  `gejala` text NOT NULL,
  `hasil_diagnosa` varchar(255) NOT NULL,
  `nama_penyakit` varchar(255) NOT NULL,
  `solusi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id`, `id_user`, `nama_pasien`, `waktu_konsultasi`, `kode_gejala`, `gejala`, `hasil_diagnosa`, `nama_penyakit`, `solusi`) VALUES
(124, 20, 'Salma', '05-01-2025 17:49:53', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '97.9%', 'Diare', 'Orang tua dapat memberikan cairan seperti oralit atau air putih setiap 10-15 menit untuk mencegah dehidrasi. Hindari minuman manis atau berkafein. Berikan makanan ringan seperti nasi putih, kentang rebus, atau roti, dan hindari makanan pedas, berlemak, atau susu. Pastikan  mencuci tangan setelah anak buang air besar'),
(125, 20, 'Salma', '05-01-2025 17:50:02', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '97.9%', 'Diare', 'Orang tua dapat memberikan cairan seperti oralit atau air putih setiap 10-15 menit untuk mencegah dehidrasi. Hindari minuman manis atau berkafein. Berikan makanan ringan seperti nasi putih, kentang rebus, atau roti, dan hindari makanan pedas, berlemak, atau susu. Pastikan  mencuci tangan setelah anak buang air besar'),
(126, 20, 'Salma', '05-01-2025 17:51:30', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '97.9%', 'Diare', 'Orang tua dapat memberikan cairan seperti oralit atau air putih setiap 10-15 menit untuk mencegah dehidrasi. Hindari minuman manis atau berkafein. Berikan makanan ringan seperti nasi putih, kentang rebus, atau roti, dan hindari makanan pedas, berlemak, atau susu. Pastikan  mencuci tangan setelah anak buang air besar'),
(127, 20, 'Salma', '05-01-2025 17:53:46', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '{\"1\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"2\":{\"keterangan\":\"Yakin\",\"cf_user\":\"0.8\",\"cf_pakar\":\"1\"},\"3\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"},\"6\":{\"keterangan\":\"Sedikit Yakin\",\"cf_user\":\"0.4\",\"cf_pakar\":\"0.6\"},\"9\":{\"keterangan\":\"Cukup Yakin\",\"cf_user\":\"0.6\",\"cf_pakar\":\"0.8\"}}', '97.9%', 'Diare', 'Orang tua dapat memberikan cairan seperti oralit atau air putih setiap 10-15 menit untuk mencegah dehidrasi. Hindari minuman manis atau berkafein. Berikan makanan ringan seperti nasi putih, kentang rebus, atau roti, dan hindari makanan pedas, berlemak, atau susu. Pastikan  mencuci tangan setelah anak buang air besar');

-- --------------------------------------------------------

--
-- Table structure for table `relasi`
--

CREATE TABLE `relasi` (
  `id_relasi` int(11) NOT NULL,
  `id_gejala` int(11) DEFAULT NULL,
  `id_penyakit` int(11) DEFAULT NULL,
  `cf` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `relasi`
--

INSERT INTO `relasi` (`id_relasi`, `id_gejala`, `id_penyakit`, `cf`) VALUES
(91, 1, 1, 0.8),
(92, 2, 1, 0.9),
(93, 3, 1, 0.8),
(94, 3, 2, 0.8),
(95, 3, 3, 0.8),
(96, 4, 1, 0.7),
(97, 4, 2, 0.7),
(98, 4, 3, 0.7),
(99, 5, 1, 0.7),
(100, 5, 2, 0.7),
(101, 5, 3, 0.7),
(102, 6, 1, 0.7),
(103, 6, 3, 0.7),
(104, 7, 1, 0.8),
(105, 7, 3, 0.8),
(106, 8, 1, 0.5),
(107, 9, 1, 0.6),
(108, 9, 2, 0.6),
(109, 9, 3, 0.6),
(110, 10, 1, 0.6),
(111, 10, 2, 0.6),
(112, 10, 3, 0.6),
(113, 11, 1, 0.8),
(114, 12, 1, 0.5),
(115, 12, 3, 0.5),
(116, 13, 1, 0.8),
(117, 14, 2, 0.8),
(118, 15, 2, 0.9),
(119, 16, 2, 0.8),
(120, 17, 1, 0.6),
(121, 17, 2, 0.6),
(122, 18, 2, 0.8),
(123, 19, 2, 0.8),
(124, 20, 2, 0.7),
(125, 21, 3, 0.9),
(126, 22, 3, 0.8),
(127, 23, 3, 0.6),
(128, 24, 3, 0.8),
(129, 25, 3, 0.7);

-- --------------------------------------------------------

--
-- Table structure for table `solusi`
--

CREATE TABLE `solusi` (
  `id_solusi` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL,
  `solusi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `solusi`
--

INSERT INTO `solusi` (`id_solusi`, `id_penyakit`, `solusi`) VALUES
(4, 1, 'Orang tua dapat memberikan cairan seperti oralit atau air putih setiap 10-15 menit untuk mencegah dehidrasi. Hindari minuman manis atau berkafein. Berikan makanan ringan seperti nasi putih, kentang rebus, atau roti, dan hindari makanan pedas, berlemak, atau susu. Pastikan  mencuci tangan setelah anak buang air besar'),
(5, 2, 'Orang tua dapat melakukan beberapa langkah seperti memastikan anak cukup minum air putih untuk mencegah dehidrasi, karena kurang cairan bisa memperburuk sembelit. Berikan makanan tinggi serat seperti buah-buahan (apel, pisang, pepaya), sayuran (wortel, bayam), dan biji-bijian. Ajak anak bergerak atau bermain aktif agar gerakan ususnya lancar'),
(6, 3, 'Pastikan anak menghindari makanan atau minuman yang dapat mengiritasi lambung, seperti makanan pedas, asam, berlemak, atau minuman berkafein. Berikan makanan yang mudah dicerna dan lebih lembut, seperti nasi putih, kentang rebus, atau sup kaldu. Pastikan anak makan dalam porsi kecil tetapi sering, untuk mengurangi beban pada lambung. Selain itu, pastikan anak cukup istirahat dan hindari stres');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `jk_anak` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `role`, `nama`, `nama_anak`, `jk_anak`, `email`, `alamat`, `tgl_lahir`, `password`) VALUES
(10, 0, 'admin', '', 'Laki - laki', 'novrezaramadhan11@gmail.com', 'Padang', '2003-11-17', '$2y$10$ASS50col3niwOOku4Zkky.HpmF18hiPWL9pi2DnE8CS7jTDSD4ufe'),
(20, 1, 'Reza', 'Salma', 'Perempuan', 'novrezaramadhan11@gmail.com', 'Padang', '2023-05-15', '$2y$10$kXAXwXPYn56aCU2ogtVy3uFqCEjZLZ2Snxw8MSzTRTWg3qzQI7Q42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`);

--
-- Indexes for table `bobotcf`
--
ALTER TABLE `bobotcf`
  ADD PRIMARY KEY (`id_bobot`);

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relasi`
--
ALTER TABLE `relasi`
  ADD PRIMARY KEY (`id_relasi`),
  ADD KEY `id_gejala` (`id_gejala`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indexes for table `solusi`
--
ALTER TABLE `solusi`
  ADD PRIMARY KEY (`id_solusi`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bobotcf`
--
ALTER TABLE `bobotcf`
  MODIFY `id_bobot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `penyakit`
--
ALTER TABLE `penyakit`
  MODIFY `id_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `relasi`
--
ALTER TABLE `relasi`
  MODIFY `id_relasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `solusi`
--
ALTER TABLE `solusi`
  MODIFY `id_solusi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `relasi`
--
ALTER TABLE `relasi`
  ADD CONSTRAINT `fk_relasi_gejala` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_relasi_penyakit` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`) ON DELETE CASCADE;

--
-- Constraints for table `solusi`
--
ALTER TABLE `solusi`
  ADD CONSTRAINT `fk_solusi_penyakit` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`) ON DELETE CASCADE,
  ADD CONSTRAINT `solusi_ibfk_1` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
