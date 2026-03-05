-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 05 Mar 2026 pada 13.05
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
-- Database: `simrs_workorder`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('image','pdf') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` int(10) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chats`
--

INSERT INTO `chats` (`id`, `work_order_id`, `created_at`, `updated_at`) VALUES
(29, 71, '2026-03-05 03:20:55', '2026-03-05 03:20:55'),
(30, 72, '2026-03-05 03:25:37', '2026-03-05 03:25:37'),
(31, 73, '2026-03-05 03:27:46', '2026-03-05 03:27:46'),
(32, 74, '2026-03-05 03:31:31', '2026-03-05 03:31:31'),
(33, 75, '2026-03-05 03:33:10', '2026-03-05 03:33:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(86, 33, 2, 'p', 1, '2026-03-05 10:35:09'),
(87, 33, 2, 'p', 1, '2026-03-05 10:35:19'),
(88, 32, 2, 'p', 1, '2026-03-05 10:35:41'),
(89, 30, 2, 'p', 1, '2026-03-05 10:36:10'),
(90, 30, 2, 'p', 1, '2026-03-05 11:50:22'),
(91, 30, 2, 'halo', 1, '2026-03-05 11:50:41'),
(92, 30, 1, 'ada apa', 1, '2026-03-05 11:51:05'),
(93, 30, 1, 'ada apa', 1, '2026-03-05 11:51:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(60, 'default', '{\"uuid\":\"7e874194-6eb4-4d97-910a-895873a01d0b\",\"displayName\":\"App\\\\Mail\\\\TechnicianAssignedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\TechnicianAssignedMail\\\":3:{s:9:\\\"workOrder\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:28;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"rizalfadeli12345@gmail.com\\r\\n\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1772380878,\"delay\":null}', 0, NULL, 1772380878, 1772380878),
(61, 'default', '{\"uuid\":\"2380e710-ba50-4492-8965-17d8eb0f4c3d\",\"displayName\":\"App\\\\Mail\\\\TechnicianAssignedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\TechnicianAssignedMail\\\":3:{s:9:\\\"workOrder\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:64;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"rizalfadeli12345@gmail.com\\r\\n\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1772431597,\"delay\":null}', 0, NULL, 1772431597, 1772431597),
(62, 'default', '{\"uuid\":\"0a2cef93-7864-4266-99da-ba2c4587bf75\",\"displayName\":\"App\\\\Mail\\\\TechnicianAssignedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\TechnicianAssignedMail\\\":3:{s:9:\\\"workOrder\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:71;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"rizalfadeli12345@gmail.com\\r\\n\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1772707289,\"delay\":null}', 0, NULL, 1772707289, 1772707289),
(63, 'default', '{\"uuid\":\"507c3025-52b6-418c-97ed-d1b643fb4940\",\"displayName\":\"App\\\\Mail\\\\TechnicianAssignedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\TechnicianAssignedMail\\\":3:{s:9:\\\"workOrder\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:75;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:26:\\\"rizalfadeli12345@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1772711301,\"delay\":null}', 0, NULL, 1772711301, 1772711301),
(64, 'default', '{\"uuid\":\"84043280-1e33-45fa-bb3f-f6278f13359c\",\"displayName\":\"App\\\\Mail\\\\TechnicianAssignedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\TechnicianAssignedMail\\\":3:{s:9:\\\"workOrder\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:74;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"rizalfadeli12345@gmail.com\\r\\n\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1772711538,\"delay\":null}', 0, NULL, 1772711538, 1772711538);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_19_121601_create_roles_table', 1),
(5, '2026_02_19_121657_add_role_to_user_table', 1),
(6, '2026_02_19_121727_create_technicians_table', 1),
(7, '2026_02_19_121759_create_work_orders_table', 1),
(8, '2026_02_19_121830_create_attachments_table', 1),
(9, '2026_02_19_121852_create_status_log_table', 1),
(10, '2026_02_19_121917_create_chats_table', 1),
(11, '2026_02_24_164713_add_email_and_berita_acara_to_work_orders_table', 2),
(12, '2026_02_25_020837_create_progress_logs_table', 3),
(13, '2026_02_25_021923_add_berita_acara_to_work_orders', 4),
(14, '2026_02_26_025554_add_pelapor_fields_to_work_orders_table', 5),
(15, '2026_02_26_040132_add_email_to_technicians_table', 6),
(16, '2026_02_26_042604_add_kategori_to_work_orders_table', 7),
(17, '2026_02_27_161956_add_ttd_admin_to_work_orders_table', 8),
(18, '2026_03_01_162709_change_email_to_whatsapp_in_work_orders_table', 9),
(19, '2026_03_01_163416_make_user_id_nullable_in_work_orders_table', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `progress_logs`
--

CREATE TABLE `progress_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin Rumah Sakit', '2026-02-19 09:04:31', '2026-02-19 09:04:31'),
(2, 'user', 'Pengguna / Pelapor', '2026-02-19 09:04:31', '2026-02-19 09:04:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('HfO1Yc21hjvSVq82xN9asBH0zgoCI2pB1FyPGdPs', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNTdrbmZoUnVrTkVQc2puamh1eElpYm84TGdmTG50N3IwdUZzdHQ3TSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3VucmVhZC1jb3VudCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vdW5yZWFkLWNvdW50IjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi51bnJlYWQtY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1772711657),
('J2pmYwpugRMnJCSbq38qUtA7Bpjji0HP1fwJ9VZI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZEg1NFo1R2pjTjFPalBrMmhoWGdCQ2theXUwT3NGemVCakl5RWJOeiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3VucmVhZC1jb3VudCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1772711434),
('MRHeyVmhWcJPx2W64hJvuASUQyn40xYDuBBXxyLm', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) workorder_rsfix/1.0.0 Chrome/144.0.7559.111 Electron/40.2.1 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMTFabjJRTkE0YVZxbElzbEw4dW9tNDJ2WXJhY2p4RlRRaUJ1MktVZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3VzZXIvd29yay1vcmRlcnMvNjkvY2hhdCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdXNlci91bnJlYWQtY291bnQiO3M6NToicm91dGUiO3M6MTc6InVzZXIudW5yZWFkLWNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1772711653);

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_logs`
--

CREATE TABLE `status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `changed_by` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `status_logs`
--

INSERT INTO `status_logs` (`id`, `work_order_id`, `changed_by`, `old_status`, `new_status`, `note`, `created_at`) VALUES
(34, 67, 1, 'submitted', 'completed', NULL, '2026-03-02 05:59:13'),
(35, 66, 1, 'submitted', 'completed', NULL, '2026-03-02 05:59:26'),
(36, 65, 1, 'submitted', 'completed', NULL, '2026-03-02 05:59:46'),
(37, 64, 1, 'submitted', 'broken_total', NULL, '2026-03-02 06:00:00'),
(38, 63, 1, 'submitted', 'completed', NULL, '2026-03-02 06:00:19'),
(39, 68, 1, 'submitted', 'completed', NULL, '2026-03-02 06:02:53'),
(40, 73, 1, 'submitted', 'completed', 'jangan dirusakin lagi', '2026-03-05 10:41:00'),
(41, 71, 1, 'submitted', 'in_progress', NULL, '2026-03-05 10:41:42'),
(42, 71, 1, 'in_progress', 'completed', NULL, '2026-03-05 10:41:50'),
(43, 74, 1, 'submitted', 'completed', NULL, '2026-03-05 11:52:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `technicians`
--

CREATE TABLE `technicians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `technicians`
--

INSERT INTO `technicians` (`id`, `name`, `email`, `phone`, `specialty`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Agus Santoso', 'rizalfadeli12345@gmail.com', '081234567890', 'Listrik & Panel', 1, '2026-02-19 09:04:32', '2026-02-19 09:04:32'),
(2, 'Dedi Kurniawan', 'rizalfadeli12345@gmail.com\r\n', '082345678901', 'Mekanik & HVAC', 1, '2026-02-19 09:04:32', '2026-02-19 09:04:32'),
(3, 'Eko Prasetyo', NULL, '083456789012', 'IT & Jaringan', 1, '2026-02-19 09:04:32', '2026-02-19 09:04:32'),
(4, 'Fajar Nugroho', NULL, '084567890123', 'Alat Medis', 1, '2026-02-19 09:04:32', '2026-02-19 09:04:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `unit`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Rumah Sakit', 'admin@rssimrs.id', 'Manajemen', NULL, '$2y$12$xs5wHJ4Ds3lm/DON7psSd.aWlYot/PSnvLw6JpDXpTYOctc9v.CTe', 'hAW8N2trguYnAuaM5DKDiRyq3AZGQsnlwdw36hvkURUDdl14vacPi0hJkooJ', '2026-02-19 09:04:31', '2026-02-19 09:04:31'),
(2, 2, 'Perawat Budi', 'budi@rssimrs.id', 'Bangsal Bedah Lt. 2', NULL, '$2y$12$lWg8Ehdx99PwJ8i4aGkeN.thmH4iC6UKJexAsiV9eNw70jqUM6uyK', NULL, '2026-02-19 09:04:32', '2026-02-19 09:04:32'),
(3, 2, 'Riana', 'riana@rssimrs.com', 'Poli Gigi', NULL, '$2y$12$RdhZkE9FD5BrFFV/fDVh5eEN5MnqiX8p3FX.Ki0YHnZ3i1Ui7MGX.', 'jVW8zuX3tzhNMrUNL9RhFoDRBVf5kQWPUnIwfrrbd2Yrx0HpRFTrUYLci59L', '2026-02-20 19:37:42', '2026-02-20 19:37:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `tanda_tangan` varchar(255) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `status` enum('submitted','in_progress','completed','broken_total','delete') NOT NULL DEFAULT 'submitted',
  `technician_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estimated_days` tinyint(3) UNSIGNED DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `ttd_admin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `berita_acara_file` varchar(255) DEFAULT NULL,
  `berita_acara_generated_at` timestamp NULL DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `work_orders`
--

INSERT INTO `work_orders` (`id`, `code`, `user_id`, `item_name`, `location`, `description`, `nama_pelapor`, `whatsapp`, `tanda_tangan`, `berita_acara`, `priority`, `status`, `technician_id`, `estimated_days`, `admin_notes`, `ttd_admin`, `created_at`, `updated_at`, `berita_acara_file`, `berita_acara_generated_at`, `kategori`) VALUES
(63, 'WO-REZSMK', 1, 'Monitor', 'igd', 'Komputer di ruang IGD tidak bisa menyala, indikator lampu power menyala sebentar kemudian mati.', 'ahmad rizal', '6285608981265', 'signatures/sig_1772430950_CKxxM.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-01 22:55:50', '2026-03-01 23:00:19', NULL, NULL, NULL),
(64, 'WO-UHE9KS', 1, 'Monitor', 'alamanda', 'Printer laser di poli anak sering macet kertas, muncul error “Paper Jam”.', 'ahmad rizal', '6285608981265', 'signatures/sig_1772431008_QEowN.png', NULL, 'medium', 'broken_total', 2, NULL, NULL, NULL, '2026-03-01 22:56:48', '2026-03-01 23:06:37', NULL, NULL, NULL),
(65, 'WO-QIFULI', 1, 'printer', 'igd', 'Keyboard di ruang radiologi tidak merespons beberapa tombol, dugaan ada kerusakan switch.', 'RIZAL', '6285608981265', 'signatures/sig_1772431039_RUVSS.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-01 22:57:19', '2026-03-01 22:59:46', NULL, NULL, NULL),
(66, 'WO-NNID9C', 1, 'PRINTER', 'alamanda', 'Keyboard di ruang radiologi tidak merespons beberapa tombol, dugaan ada kerusakan switch.', 'admin', '6285608981265', 'signatures/sig_1772431083_fh5tu.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-01 22:58:03', '2026-03-01 22:59:26', NULL, NULL, NULL),
(67, 'WO-R7XBU3', 1, 'wifi', 'igd', 'UPS di ruang UGD mengeluarkan bunyi alarm dan tidak menstabilkan daya listrik.', 'rjl', '6285608981265', 'signatures/sig_1772431122_r9cGs.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-01 22:58:42', '2026-03-01 22:59:13', NULL, NULL, NULL),
(68, 'WO-HOTPJ6', 1, 'printer', 'alamanda', 'Keyboard di ruang radiologi tidak merespons beberapa tombol, dugaan ada kerusakan switch.', 'RIZAL', '6285608981265', 'signatures/sig_1772431343_il5UM.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-01 23:02:23', '2026-03-01 23:02:53', NULL, NULL, 'jaringan'),
(71, 'WO-20260305-0002', 2, 'wifi', 'wijaya kusuma', 'tidak ada internet', 'rijal f', '6285608981265', 'tanda_tangan/69a9590799671.png', NULL, 'low', 'completed', 2, NULL, NULL, NULL, '2026-03-05 03:20:55', '2026-03-05 03:41:50', NULL, NULL, 'jaringan'),
(72, 'WO-20260305-0003', 2, 'komputer', 'IGD', 'monitor berkedip', 'rizal', '6285608981265', 'tanda_tangan/69a95a2191965.png', NULL, 'medium', 'submitted', NULL, NULL, NULL, NULL, '2026-03-05 03:25:37', '2026-03-05 03:25:37', NULL, NULL, 'hardware'),
(73, 'WO-20260305-0004', 2, 'Monitor', 'alamanda', 'monitor kedip kedip', 'krisna', '6285608981265', 'tanda_tangan/69a95aa1e499f.png', NULL, 'medium', 'completed', NULL, NULL, NULL, NULL, '2026-03-05 03:27:45', '2026-03-05 03:41:00', NULL, NULL, 'hardware'),
(74, 'WO-20260305-0005', 2, 'wifi', 'alamanda', 'ddddddddddddddddddd', 'jjjjjjjjjjjj', '6285608981265', 'tanda_tangan/69a95b83212df.png', NULL, 'medium', 'completed', 2, NULL, NULL, 'signatures/ttd_admin_WO-20260305-0005_1772711554.png', '2026-03-05 03:31:31', '2026-03-05 04:52:35', 'berita_acara/BA_WO-20260305-0005.pdf', '2026-03-05 04:52:35', 'jaringan'),
(75, 'WO-20260305-0006', 2, 'wifi', 'IGD', 'ddddddddddddddddddddddd', 'samuel', '6285608981265', 'tanda_tangan/69a95be68df9f.png', NULL, 'high', 'submitted', 1, NULL, NULL, NULL, '2026-03-05 03:33:10', '2026-03-05 04:48:21', NULL, NULL, 'hardware');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_work_order_id_foreign` (`work_order_id`),
  ADD KEY `attachments_uploaded_by_foreign` (`uploaded_by`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chats_work_order_id_unique` (`work_order_id`);

--
-- Indeks untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_sender_id_foreign` (`sender_id`),
  ADD KEY `chat_messages_chat_id_created_at_index` (`chat_id`,`created_at`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `progress_logs`
--
ALTER TABLE `progress_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_logs_work_order_id_foreign` (`work_order_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `status_logs`
--
ALTER TABLE `status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_logs_work_order_id_foreign` (`work_order_id`),
  ADD KEY `status_logs_changed_by_foreign` (`changed_by`);

--
-- Indeks untuk tabel `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `technicians_email_unique` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_code_unique` (`code`),
  ADD KEY `work_orders_user_id_foreign` (`user_id`),
  ADD KEY `work_orders_technician_id_foreign` (`technician_id`),
  ADD KEY `work_orders_priority_status_created_at_index` (`priority`,`status`,`created_at`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `progress_logs`
--
ALTER TABLE `progress_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `status_logs`
--
ALTER TABLE `status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attachments_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `progress_logs`
--
ALTER TABLE `progress_logs`
  ADD CONSTRAINT `progress_logs_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `status_logs`
--
ALTER TABLE `status_logs`
  ADD CONSTRAINT `status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `status_logs_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Ketidakleluasaan untuk tabel `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
