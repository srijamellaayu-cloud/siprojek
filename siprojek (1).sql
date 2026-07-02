-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2026 at 04:33 AM
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
-- Database: `siprojek`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deal`
--

CREATE TABLE `deal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_proyek` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `mitra` varchar(255) NOT NULL,
  `biaya_penawaran` int(11) DEFAULT NULL,
  `durasi_proyek` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deal_tasks`
--

CREATE TABLE `deal_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penawaran_id` bigint(20) UNSIGNED NOT NULL,
  `nama_tugas` varchar(255) NOT NULL,
  `anggota` text DEFAULT NULL,
  `tanggal_tugas` date NOT NULL,
  `durasi` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'On Progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bank_penagihan` varchar(255) DEFAULT NULL,
  `dokumen_invoice` varchar(255) DEFAULT NULL,
  `dokumen_faktur_pajak` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deal_tasks`
--

INSERT INTO `deal_tasks` (`id`, `penawaran_id`, `nama_tugas`, `anggota`, `tanggal_tugas`, `durasi`, `deskripsi`, `status`, `created_at`, `updated_at`, `bank_penagihan`, `dokumen_invoice`, `dokumen_faktur_pajak`) VALUES
(32, 44, 'Surat Kerja Sama', '[\"Anita Saputri Halawa\"]', '2026-06-25', NULL, 'Surat Kerja Sama', 'Done', '2026-06-23 15:18:12', '2026-06-23 15:18:31', NULL, NULL, NULL),
(33, 44, 'Invoice Penagihan', NULL, '2026-06-25', NULL, NULL, 'Done', '2026-06-23 15:18:31', '2026-06-23 15:19:35', 'BCA', 'dokumen_invoice/Dokumen Invoice Sertifikasi Listrik.pdf', 'dokumen_faktur_pajak/Dokumen Faktur Pajak Sertifikasi Listrik.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_26_164354_create_projects_table', 1),
(5, '2025_09_25_044933_create_penawaran_table', 1),
(6, '2025_10_13_001819_create_deal_table', 1),
(7, '2026_04_17_000001_create_deal_tasks_table', 2),
(8, '2026_04_21_add_catatan_penolakan_to_penawaran', 3),
(9, '2026_05_12_000002_alter_anggota_column_in_deal_tasks_table', 4),
(10, '2026_06_04_000001_add_nomor_surat_to_penawaran_table', 4),
(11, '2026_06_04_200645_add_contract_fields_to_penawaran_table', 5),
(12, '2026_06_04_203249_add_project_dates_to_penawaran_table', 6),
(13, '2026_06_09_000001_change_biaya_penawaran_to_big_integer_in_penawaran_table', 7),
(14, '2026_06_09_000002_add_role_to_users_table', 8),
(15, '2026_06_18_000001_add_invoice_fields_to_deal_tasks_table', 9),
(16, '2026_06_18_000002_add_faktur_pajak_to_deal_tasks_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('sri22ti@mahasiswa.pcr.ac.id', '$2y$12$4BaKmeXP0/3LWicYtLBrqe/F4e.vc4PQrysZr9/hm3OHWVduntROC', '2026-06-14 06:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `penawaran`
--

CREATE TABLE `penawaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_proyek` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `nomor_kontrak` varchar(255) DEFAULT NULL,
  `mitra` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Menunggu Persetujuan',
  `biaya_penawaran` bigint(20) DEFAULT NULL,
  `durasi_proyek` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `dokumen_kontrak` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penawaran`
--

INSERT INTO `penawaran` (`id`, `nama_proyek`, `tanggal`, `tanggal_mulai`, `tanggal_selesai`, `nomor_surat`, `nomor_kontrak`, `mitra`, `status`, `biaya_penawaran`, `durasi_proyek`, `deskripsi`, `catatan_penolakan`, `dokumen`, `dokumen_kontrak`, `created_at`, `updated_at`) VALUES
(39, 'Program Magang Kerja PHR WK Rokan', '2025-02-01', NULL, NULL, NULL, NULL, 'Pertamina Hulu Rokan', 'Menunggu Persetujuan', 12000000000, '2 Tahun', 'Program Magang Kerja PHR WK Rokan', NULL, 'dokumen/tes (6).pdf', NULL, '2026-06-08 14:11:43', '2026-06-08 14:14:18'),
(42, 'Pengadaan Jasa Manage Service Sewa Perangkat Dan infrastruktur CCTV Include Instalasi di Diskominfo Kab Siak', '2025-04-14', NULL, NULL, NULL, NULL, 'PT Indosat Tbk', 'Menunggu Persetujuan', 500000000, '1 Tahun', 'Pengadaan Jasa Manage Service Sewa Perangkat Dan infrastruktur CCTV Include Instalasi di Diskominfo Kab Siak', NULL, 'dokumen/tes (9).pdf', NULL, '2026-06-08 14:16:37', '2026-06-08 14:16:37'),
(44, 'Sertifikasi Listrik', '2025-09-19', NULL, NULL, NULL, NULL, 'PT Lisan Nusantara Satu', 'Disetujui', 2000000, '1 Bulan', 'Sertifikasi Listrik', NULL, 'dokumen/tes (11).pdf', NULL, '2026-06-08 14:17:50', '2026-06-08 14:24:02'),
(45, 'Jasa -Jasa Pembuatan dan Pengembangan Perangkat Lunak (Software) Teknologi Virtual Reality & Mixed Reality PT Pertamina Hulu Rokan', '2025-11-05', NULL, NULL, NULL, NULL, 'Pertamina Hulu Rokan', 'Menunggu Persetujuan', 1000000000, '1 Tahun', 'Jasa -Jasa Pembuatan dan Pengembangan Perangkat Lunak (Software) Teknologi Virtual Reality & Mixed Reality PT Pertamina Hulu Rokan', NULL, 'dokumen/tes (12).pdf', NULL, '2026-06-08 14:18:20', '2026-06-08 14:18:20'),
(46, 'Jasa Bandwidth Internet dan Manage Service', '2026-01-02', NULL, NULL, NULL, NULL, 'Disnakertrans Prov. Riau', 'Menunggu Persetujuan', 100000000, '1 Tahun', 'Jasa Bandwidth Internet dan Manage Service', NULL, 'dokumen/tes (13).pdf', NULL, '2026-06-08 14:19:00', '2026-06-08 14:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4FLV65EIuzJBETXNegC9UmxNEp2YdXFCsAGpacPs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUFVhVzh3U1R2SElCRTJVOEpud3U0Mmc5Z2FWVVRhbXcxVUpDc3RYTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FwaS9kZWFkbGluZS1ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779413426),
('a42yIBTZJNWU7K7mP8yI47tP4UkpgZZJ7ZnY5xoK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzlESnc3VGgxZ1JRb0x0TzlHamdhZEhOYTdNRmpTUVJ1Z1RFYm12dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779379539),
('GSWCxA33O4wQ6bGXd4DaXsOXQ6d5N5gMsCvsbPYx', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWVdQTkJYamx1R000MGoxYnB3cTRkTHZMS0cwUmp4S2lPQk1NSnhkbSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2RlYWRsaW5lLW5vdGlmaWNhdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1779422175),
('i5XyomCIqGgcATMOfpGJ9BGd6LowFsmsSYUbzHkC', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSlZSYUlwY3ZYS3h1SnFEeUN1VGk0cHNiYjhDV090ZTJsUGUyRHliTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2RlYWRsaW5lLW5vdGlmaWNhdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1779381238),
('u1rXo2mvSsaes3gA6iZHLSliOxQpqwD3OszM5g5t', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXRob1A4ZDh3ZmFlV0VKV2pGVk00TTRvYlVTMFI0OXZXemxxR2UxaiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FwaS9kZWFkbGluZS1ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvZGVhZGxpbmUtbm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779469469),
('xeqDDmsdVLlnwLHzfcNBdE05wBluVaQC6NOs8HfK', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY1JDT0dVR3RyVGxRcjdpMXpsZVZBRW1OR1AzQUZ4NWQ4a1JlRWxJQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvZGVhZGxpbmUtbm90aWZpY2F0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1780334967);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'administrasi',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(10, 'Administrasi', 'administrasi@gmail.com', NULL, '$2y$12$EwHHxAkdnJm9O9N.PuX7h.BK1FhHGZCQSaXbF0dQe4TDAFwE4jWZq', 'administrasi', NULL, '2026-06-23 15:11:36', '2026-06-23 15:11:36'),
(11, 'Keuangan', 'keuangan@gmail.com', NULL, '$2y$12$RIv5j1JCBv7O1..zxwB9mOLLsURJkNkNScOfEpoJEnQ0acArlQPsG', 'keuangan', NULL, '2026-06-23 15:12:48', '2026-06-23 15:12:48'),
(12, 'Eksekutif', 'eksekutif@gmail.com', NULL, '$2y$12$NIzAp/fnSBke4OWivJd8Le8UjuRnkCU2TPBTcQkjzOvzk0PG/bZ8K', 'eksekutif', NULL, '2026-06-23 15:14:15', '2026-06-23 15:14:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `deal`
--
ALTER TABLE `deal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_tasks`
--
ALTER TABLE `deal_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_tasks_penawaran_id_foreign` (`penawaran_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penawaran`
--
ALTER TABLE `penawaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deal`
--
ALTER TABLE `deal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deal_tasks`
--
ALTER TABLE `deal_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `penawaran`
--
ALTER TABLE `penawaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deal_tasks`
--
ALTER TABLE `deal_tasks`
  ADD CONSTRAINT `deal_tasks_penawaran_id_foreign` FOREIGN KEY (`penawaran_id`) REFERENCES `penawaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
