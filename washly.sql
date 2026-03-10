-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 03:02 PM
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
-- Database: `washly`
--

-- --------------------------------------------------------

--
-- Table structure for table `addon_options`
--

CREATE TABLE `addon_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` enum('detergent','softener','service') NOT NULL DEFAULT 'service',
  `unit_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `code`, `name`, `image_path`, `category`, `unit_price`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(30, 'fold_premium', 'พับผ้าพรีเมียมแยกประเภท', 'addons/mBSGGOVWR5sMA8id9p2gW47jtEz6aEUJVyREThYE.jpg', 'service', 8.00, 1, 0, '2026-03-05 06:01:02', '2026-03-05 06:07:33'),
(31, 'detergent_30', 'บรีสเอกเซล ซิกเนเจอร์ชมพู สูตรน้ำ 30 มล', 'addons/nDE5gwSZ5XGnMQKMmnHOIKzMVwh9KIWzjyOUKDtH.jpg', 'detergent', 5.00, 1, 0, '2026-03-05 08:49:49', '2026-03-05 08:49:49'),
(32, 'detergent_hygiene_detergent_35', 'Hygiene Detergent ผลิตภัณฑ์ซักผ้าชนิดน้ำ ขนาด 35 มล.', 'addons/CazCCkcHnvKuPWhlj7gkgO35J15IJEWPEubzUx84.jpg', 'detergent', 5.00, 1, 0, '2026-03-05 08:56:51', '2026-03-05 08:56:51'),
(33, 'softener_20', 'ดาวน์นี่ น้ำยาปรับผ้านุ่ม มิสทีค 20 มล.', 'addons/mIyWmHeQIC5f14h6UUE8ZCWRJ362Wa64EjsKzWgD.jpg', 'softener', 5.00, 1, 0, '2026-03-05 09:17:44', '2026-03-05 09:17:44'),
(34, 'softener_20_1', 'ไฮยีน สูตรเข้มข้น กลิ่นมอนิ่งเฟรช ฟ้า 20 มล.', 'addons/Nsq6UriyIpzO70aSjqi01FUXeanNYvqCwKraxJWK.webp', 'softener', 5.00, 1, 0, '2026-03-05 09:18:02', '2026-03-05 09:18:02');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"8f61b7f3-67a3-4f68-99eb-8d0eaa8856a9\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:131:\\\"ออเดอร์ ORD-20260309-9755 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"2519712d-926f-4b22-98fd-07e0499234e6\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:7:\\\"message\\\";s:131:\\\"ออเดอร์ ORD-20260309-9755 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773057679,\"delay\":null}', 0, NULL, 1773057679, 1773057679),
(2, 'default', '{\"uuid\":\"1b2e5c2c-59d4-47ee-8fed-7c16583dcdfb\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-9755\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"7a13f810-7e94-474b-99a6-39d3073ffee8\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:7:\\\"message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-9755\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773057679,\"delay\":null}', 0, NULL, 1773057679, 1773057679),
(3, 'default', '{\"uuid\":\"d5d1846f-d9b5-4394-8a3d-13efa5e24aea\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"5a65fa04-e3c3-455f-afbf-0c1f8f2cc429\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773057696,\"delay\":null}', 0, NULL, 1773057696, 1773057696),
(4, 'default', '{\"uuid\":\"b6362f94-b0fc-4581-b9e9-cb202fe8f327\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:107:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"กำลังซัก\\/อบ\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"28aa27a2-0a1d-44be-a180-e502d100d330\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:107:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"กำลังซัก\\/อบ\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058279,\"delay\":null}', 0, NULL, 1773058279, 1773058279),
(5, 'default', '{\"uuid\":\"39c21088-332e-4f56-ba3e-aa60a5502f59\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"กำลังนำส่ง\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"31917549-40c7-40ec-8b50-e519cfcd70d0\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"กำลังนำส่ง\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058823,\"delay\":null}', 0, NULL, 1773058823, 1773058823),
(6, 'default', '{\"uuid\":\"037ed476-61f7-472f-8b25-515c08452efc\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:33:\\\"ส่งสลิปแล้ว\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:155:\\\"ระบบรับสลิปของออเดอร์ ORD-20260309-9755 แล้ว กำลังรอแอดมินตรวจสอบ\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"f89d1f06-24b7-4991-82a2-143ed5469530\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:33:\\\"ส่งสลิปแล้ว\\\";s:7:\\\"message\\\";s:155:\\\"ระบบรับสลิปของออเดอร์ ORD-20260309-9755 แล้ว กำลังรอแอดมินตรวจสอบ\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058849,\"delay\":null}', 0, NULL, 1773058849, 1773058849),
(7, 'default', '{\"uuid\":\"ecba2319-c412-4ebb-b111-246901accc4b\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:57:\\\"มีสลิปใหม่รอตรวจสอบ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:103:\\\"ออเดอร์ ORD-20260309-9755 อัปโหลดสลิปเข้ามาแล้ว\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"warning\\\";s:2:\\\"id\\\";s:36:\\\"548a8981-5c55-4cc7-a311-3d91993988cf\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:57:\\\"มีสลิปใหม่รอตรวจสอบ\\\";s:7:\\\"message\\\";s:103:\\\"ออเดอร์ ORD-20260309-9755 อัปโหลดสลิปเข้ามาแล้ว\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"warning\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058849,\"delay\":null}', 0, NULL, 1773058849, 1773058849),
(8, 'default', '{\"uuid\":\"c9eaa78f-0da8-4fc1-beb8-c8701b2bf6ae\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:63:\\\"ยืนยันการชำระเงินแล้ว\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:133:\\\"ออเดอร์ ORD-20260309-9755 ผ่านการตรวจสอบสลิปเรียบร้อยแล้ว\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"3fd10943-fdc3-4518-96dc-4cd25678a7dc\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:63:\\\"ยืนยันการชำระเงินแล้ว\\\";s:7:\\\"message\\\";s:133:\\\"ออเดอร์ ORD-20260309-9755 ผ่านการตรวจสอบสลิปเรียบร้อยแล้ว\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058858,\"delay\":null}', 0, NULL, 1773058858, 1773058858),
(9, 'default', '{\"uuid\":\"65f8d38f-f40a-4395-89ce-68522ec46a5f\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:103:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"9b2f0861-2725-432b-b5e4-223c6473cf8f\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:103:\\\"ออเดอร์ ORD-20260309-9755 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773058884,\"delay\":null}', 0, NULL, 1773058884, 1773058884),
(10, 'default', '{\"uuid\":\"9975b53d-4f6c-4a9f-aaff-9d5c37e5f6f1\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:145:\\\"ออเดอร์ ORD-20260309-3042 ถูกสร้างเรียบร้อยแล้ว (เงินสดปลายทาง)\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"2d05cfa9-796d-4ae2-86dc-61fb074b7477\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:7:\\\"message\\\";s:145:\\\"ออเดอร์ ORD-20260309-3042 ถูกสร้างเรียบร้อยแล้ว (เงินสดปลายทาง)\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059115,\"delay\":null}', 0, NULL, 1773059115, 1773059115),
(11, 'default', '{\"uuid\":\"a0c62d67-548c-4517-a09b-a6ba75156052\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-3042\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"67c557cf-2c21-47bd-92ef-7e59fd7adc3a\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:7:\\\"message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-3042\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059115,\"delay\":null}', 0, NULL, 1773059115, 1773059115),
(12, 'default', '{\"uuid\":\"069a743e-f812-45de-a9a8-98294025f3c3\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"36304f43-aafd-41c1-a655-4a46315efd8f\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059126,\"delay\":null}', 0, NULL, 1773059126, 1773059126),
(13, 'default', '{\"uuid\":\"b57b8009-3f78-4115-a987-41261e8dd628\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:107:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"กำลังซัก\\/อบ\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"d680f00d-3534-45eb-9138-7cb5f9e99f7a\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:107:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"กำลังซัก\\/อบ\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059146,\"delay\":null}', 0, NULL, 1773059146, 1773059146),
(14, 'default', '{\"uuid\":\"0eeb8096-b7e2-4bb7-ac9d-a11e9463489c\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"กำลังนำส่ง\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"14bf25fc-8f7f-42c8-8ce0-3dcae26531ee\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"กำลังนำส่ง\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059773,\"delay\":null}', 0, NULL, 1773059773, 1773059773),
(15, 'default', '{\"uuid\":\"80cb4674-0865-4992-bae5-101e74a93ab0\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:66:\\\"ยืนยันการรับเงินสดแล้ว\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:145:\\\"ออเดอร์ ORD-20260309-3042 ถูกยืนยันว่าเก็บเงินสดเรียบร้อยแล้ว\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"374df777-ed8c-48de-a2e1-08ee1a012434\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:66:\\\"ยืนยันการรับเงินสดแล้ว\\\";s:7:\\\"message\\\";s:145:\\\"ออเดอร์ ORD-20260309-3042 ถูกยืนยันว่าเก็บเงินสดเรียบร้อยแล้ว\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059788,\"delay\":null}', 0, NULL, 1773059788, 1773059788),
(16, 'default', '{\"uuid\":\"48ec88e2-22c8-49ab-a5fd-efea23c3aa03\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:103:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"0941aa9b-7e03-44ce-b35c-6cb4eb4f84c1\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:103:\\\"ออเดอร์ ORD-20260309-3042 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059792,\"delay\":null}', 0, NULL, 1773059792, 1773059792),
(17, 'default', '{\"uuid\":\"f004b88e-fe82-445d-96b0-d37f5d729c7b\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:94:\\\"ออเดอร์ ORD-20260309-7181 เปลี่ยนเป็น \\\"ยกเลิก\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"25beee92-cee5-49b3-ad8e-4445289ccc4d\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:94:\\\"ออเดอร์ ORD-20260309-7181 เปลี่ยนเป็น \\\"ยกเลิก\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059866,\"delay\":null}', 0, NULL, 1773059866, 1773059866),
(18, 'default', '{\"uuid\":\"a1167628-7a9f-44f7-b165-d038e864e1d2\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:131:\\\"ออเดอร์ ORD-20260309-1815 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"3cdc0647-43c4-4746-9551-308c19bd3774\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:7:\\\"message\\\";s:131:\\\"ออเดอร์ ORD-20260309-1815 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059925,\"delay\":null}', 0, NULL, 1773059925, 1773059925),
(19, 'default', '{\"uuid\":\"744ba5ef-589a-4169-8113-5dad1551e586\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-1815\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"0d327ce9-663b-422f-b13b-42c4fb543882\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:7:\\\"message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-1815\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059925,\"delay\":null}', 0, NULL, 1773059925, 1773059925),
(20, 'default', '{\"uuid\":\"6bd7ce92-0639-438d-8d6f-fd4b95972bb9\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-1815 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"a93b76eb-dbe7-455a-8594-a26886d75d13\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-1815 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773059946,\"delay\":null}', 0, NULL, 1773059946, 1773059946),
(21, 'default', '{\"uuid\":\"a0f85228-40c5-4d71-a978-d7f5b45b0e1b\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:131:\\\"ออเดอร์ ORD-20260309-2490 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"17977c38-7292-4e2a-a622-2203a2a3db59\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:7:\\\"message\\\";s:131:\\\"ออเดอร์ ORD-20260309-2490 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060594,\"delay\":null}', 0, NULL, 1773060594, 1773060594);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(22, 'default', '{\"uuid\":\"1e3e1774-5844-4ada-9ca4-7d88c91d4c29\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-2490\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"5e06649e-42eb-475b-ac42-ad9e334cdc93\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:7:\\\"message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-2490\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060594,\"delay\":null}', 0, NULL, 1773060594, 1773060594),
(23, 'default', '{\"uuid\":\"fe0e1985-34a3-4c89-9369-07112740c816\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:106:\\\"ออเดอร์ ORD-20260309-2490 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"61f656f0-d078-4e65-8ec4-739d12bad0ac\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:106:\\\"ออเดอร์ ORD-20260309-2490 เปลี่ยนเป็น \\\"รับผ้าแล้ว\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060667,\"delay\":null}', 0, NULL, 1773060667, 1773060667),
(24, 'default', '{\"uuid\":\"521c49f4-c50a-4027-ac5a-2967ad2816b3\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:33:\\\"ส่งสลิปแล้ว\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:155:\\\"ระบบรับสลิปของออเดอร์ ORD-20260309-2490 แล้ว กำลังรอแอดมินตรวจสอบ\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"2d83a471-e9ba-497e-ade0-4eeae31053a0\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:33:\\\"ส่งสลิปแล้ว\\\";s:7:\\\"message\\\";s:155:\\\"ระบบรับสลิปของออเดอร์ ORD-20260309-2490 แล้ว กำลังรอแอดมินตรวจสอบ\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060702,\"delay\":null}', 0, NULL, 1773060702, 1773060702),
(25, 'default', '{\"uuid\":\"472b2f38-ab26-4ad1-8d8c-51c564a5bbd8\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:57:\\\"มีสลิปใหม่รอตรวจสอบ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:103:\\\"ออเดอร์ ORD-20260309-2490 อัปโหลดสลิปเข้ามาแล้ว\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"warning\\\";s:2:\\\"id\\\";s:36:\\\"7a7ec0fb-3fbb-4409-9d90-884c742f4231\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:57:\\\"มีสลิปใหม่รอตรวจสอบ\\\";s:7:\\\"message\\\";s:103:\\\"ออเดอร์ ORD-20260309-2490 อัปโหลดสลิปเข้ามาแล้ว\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"warning\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060702,\"delay\":null}', 0, NULL, 1773060702, 1773060702),
(26, 'default', '{\"uuid\":\"22995b1b-f2b4-48f7-b0c7-e6bfa7a4de0e\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:63:\\\"ยืนยันการชำระเงินแล้ว\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:133:\\\"ออเดอร์ ORD-20260309-2490 ผ่านการตรวจสอบสลิปเรียบร้อยแล้ว\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"a30272ae-688f-43af-9135-7a1db8a7842f\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:63:\\\"ยืนยันการชำระเงินแล้ว\\\";s:7:\\\"message\\\";s:133:\\\"ออเดอร์ ORD-20260309-2490 ผ่านการตรวจสอบสลิปเรียบร้อยแล้ว\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060739,\"delay\":null}', 0, NULL, 1773060739, 1773060739),
(27, 'default', '{\"uuid\":\"41ffa1ab-fb90-4452-a93e-9be9bf86583a\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:103:\\\"ออเดอร์ ORD-20260309-2490 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"f2c68526-ed82-4290-9778-6b3babb3ff50\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:54:\\\"อัปเดตสถานะออเดอร์\\\";s:7:\\\"message\\\";s:103:\\\"ออเดอร์ ORD-20260309-2490 เปลี่ยนเป็น \\\"เสร็จสิ้น\\\"\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773060752,\"delay\":null}', 0, NULL, 1773060752, 1773060752),
(28, 'default', '{\"uuid\":\"44265362-eaf6-4dbc-95e3-8ccb560e13dc\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:131:\\\"ออเดอร์ ORD-20260309-8520 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:7:\\\"success\\\";s:2:\\\"id\\\";s:36:\\\"46fcba3a-f3ec-4b3b-bab3-05c52e61ac22\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:36:\\\"จองคิวสำเร็จ\\\";s:7:\\\"message\\\";s:131:\\\"ออเดอร์ ORD-20260309-8520 ถูกสร้างเรียบร้อยแล้ว (โอน\\/สแกน QR)\\\";s:3:\\\"url\\\";s:37:\\\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\\\";s:5:\\\"level\\\";s:7:\\\"success\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773071733,\"delay\":null}', 0, NULL, 1773071733, 1773071733),
(29, 'default', '{\"uuid\":\"4278113f-44e9-4812-9377-4de1b1eb0309\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\SystemNotification\\\":5:{s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:45:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-8520\\\";s:41:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:43:\\\"\\u0000App\\\\Notifications\\\\SystemNotification\\u0000level\\\";s:4:\\\"info\\\";s:2:\\\"id\\\";s:36:\\\"a2a9319e-57a0-4120-9df1-d8ae45f5556d\\\";}s:4:\\\"data\\\";a:4:{s:5:\\\"title\\\";s:39:\\\"มีออเดอร์ใหม่\\\";s:7:\\\"message\\\";s:126:\\\"ลูกค้า หมิง วู๋อวิ่ง จองคิวใหม่หมายเลข ORD-20260309-8520\\\";s:3:\\\"url\\\";s:34:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\\";s:5:\\\"level\\\";s:4:\\\"info\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1773071733,\"delay\":null}', 0, NULL, 1773071733, 1773071733);

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
(4, '2026_03_02_115348_create_packages_table', 1),
(5, '2026_03_02_115350_create_time_slots_table', 1),
(6, '2026_03_02_115351_create_order_logs_table', 1),
(7, '2026_03_02_115351_create_orders_table', 1),
(8, '2026_03_03_053619_add_payment_slip_to_orders_table', 2),
(9, '2026_03_03_060810_update_payment_status_in_orders_table', 3),
(10, '2026_03_03_120000_create_notifications_table', 4),
(11, '2026_03_05_140000_add_booking_options_and_payment_method_to_orders_table', 5),
(12, '2026_03_05_143000_create_addon_options_table', 6),
(13, '2026_03_05_143100_add_default_detergent_code_to_packages_table', 6),
(14, '2026_03_05_170000_add_softener_category_to_addon_options_table', 7),
(15, '2026_03_05_173000_add_image_path_to_addon_options_table', 8),
(16, '2026_03_05_183000_add_use_customer_softener_to_orders_table', 9),
(17, '2026_03_08_160452_add_line_columns_to_users_table', 10),
(18, '2026_03_09_210000_add_staff_role_to_users_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0894481d-4f9a-4762-be27-2376a0715807', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e0b\\u0e31\\u0e01\\/\\u0e2d\\u0e1a\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-03 11:11:43', '2026-03-03 11:11:40', '2026-03-03 11:11:43'),
('0941aa9b-7e03-44ce-b35c-6cb4eb4f84c1', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e40\\u0e2a\\u0e23\\u0e47\\u0e08\\u0e2a\\u0e34\\u0e49\\u0e19\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 12:36:32', '2026-03-09 12:39:16'),
('0be54399-d81c-4c3a-a745-05904e7e930a', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 Aum \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260303-6155\",\"url\":\"https:\\/\\/hempy-julianna-superaerially.ngrok-free.dev\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 15:55:31', '2026-03-03 14:54:10', '2026-03-09 15:55:31'),
('0d327ce9-663b-422f-b13b-42c4fb543882', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-1815\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 15:55:31', '2026-03-09 12:38:45', '2026-03-09 15:55:31'),
('14bf25fc-8f7f-42c8-8ce0-3dcae26531ee', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e19\\u0e33\\u0e2a\\u0e48\\u0e07\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:36:23', '2026-03-09 12:36:13', '2026-03-09 12:36:23'),
('17977c38-7292-4e2a-a622-2203a2a3db59', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e42\\u0e2d\\u0e19\\/\\u0e2a\\u0e41\\u0e01\\u0e19 QR)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', NULL, '2026-03-09 12:49:54', '2026-03-09 12:49:54'),
('2519712d-926f-4b22-98fd-07e0499234e6', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e42\\u0e2d\\u0e19\\/\\u0e2a\\u0e41\\u0e01\\u0e19 QR)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 12:01:19', '2026-03-09 12:39:16'),
('25beee92-cee5-49b3-ad8e-4445289ccc4d', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-7181 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e22\\u0e01\\u0e40\\u0e25\\u0e34\\u0e01\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 12:37:46', '2026-03-09 12:39:16'),
('28aa27a2-0a1d-44be-a180-e502d100d330', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e0b\\u0e31\\u0e01\\/\\u0e2d\\u0e1a\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:11:37', '2026-03-09 12:11:19', '2026-03-09 12:11:37'),
('2d05cfa9-796d-4ae2-86dc-61fb074b7477', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e40\\u0e07\\u0e34\\u0e19\\u0e2a\\u0e14\\u0e1b\\u0e25\\u0e32\\u0e22\\u0e17\\u0e32\\u0e07)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 12:25:15', '2026-03-09 12:39:16'),
('2d83a471-e9ba-497e-ade0-4eeae31053a0', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2a\\u0e48\\u0e07\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e23\\u0e30\\u0e1a\\u0e1a\\u0e23\\u0e31\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e02\\u0e2d\\u0e07\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e41\\u0e25\\u0e49\\u0e27 \\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e23\\u0e2d\\u0e41\\u0e2d\\u0e14\\u0e21\\u0e34\\u0e19\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', NULL, '2026-03-09 12:51:42', '2026-03-09 12:51:42'),
('31917549-40c7-40ec-8b50-e519cfcd70d0', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e19\\u0e33\\u0e2a\\u0e48\\u0e07\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:20:37', '2026-03-09 12:20:23', '2026-03-09 12:20:37'),
('3610dca1-ae51-43c8-a092-c06ed6ba793d', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e22\\u0e37\\u0e19\\u0e22\\u0e31\\u0e19\\u0e01\\u0e32\\u0e23\\u0e0a\\u0e33\\u0e23\\u0e30\\u0e40\\u0e07\\u0e34\\u0e19\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e1c\\u0e48\\u0e32\\u0e19\\u0e01\\u0e32\\u0e23\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-05 09:36:33', '2026-03-03 11:28:38', '2026-03-05 09:36:33'),
('36304f43-aafd-41c1-a655-4a46315efd8f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 12:25:26', '2026-03-09 12:39:16'),
('374df777-ed8c-48de-a2e1-08ee1a012434', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e22\\u0e37\\u0e19\\u0e22\\u0e31\\u0e19\\u0e01\\u0e32\\u0e23\\u0e23\\u0e31\\u0e1a\\u0e40\\u0e07\\u0e34\\u0e19\\u0e2a\\u0e14\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e16\\u0e39\\u0e01\\u0e22\\u0e37\\u0e19\\u0e22\\u0e31\\u0e19\\u0e27\\u0e48\\u0e32\\u0e40\\u0e01\\u0e47\\u0e1a\\u0e40\\u0e07\\u0e34\\u0e19\\u0e2a\\u0e14\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 12:36:28', '2026-03-09 12:39:16'),
('3cdc0647-43c4-4746-9551-308c19bd3774', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-1815 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e42\\u0e2d\\u0e19\\/\\u0e2a\\u0e41\\u0e01\\u0e19 QR)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 12:38:45', '2026-03-09 12:39:16'),
('3fd10943-fdc3-4518-96dc-4cd25678a7dc', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e22\\u0e37\\u0e19\\u0e22\\u0e31\\u0e19\\u0e01\\u0e32\\u0e23\\u0e0a\\u0e33\\u0e23\\u0e30\\u0e40\\u0e07\\u0e34\\u0e19\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e1c\\u0e48\\u0e32\\u0e19\\u0e01\\u0e32\\u0e23\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 12:20:58', '2026-03-09 12:39:16'),
('46fcba3a-f3ec-4b3b-bab3-05c52e61ac22', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-8520 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e42\\u0e2d\\u0e19\\/\\u0e2a\\u0e41\\u0e01\\u0e19 QR)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', NULL, '2026-03-09 15:55:33', '2026-03-09 15:55:33'),
('4adf48f8-aa89-4f73-909b-4794d605f9de', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2a\\u0e48\\u0e07\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e23\\u0e30\\u0e1a\\u0e1a\\u0e23\\u0e31\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e02\\u0e2d\\u0e07\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e41\\u0e25\\u0e49\\u0e27 \\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e23\\u0e2d\\u0e41\\u0e2d\\u0e14\\u0e21\\u0e34\\u0e19\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-03 11:28:22', '2026-03-03 11:28:17', '2026-03-03 11:28:22'),
('548a8981-5c55-4cc7-a311-3d91993988cf', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e23\\u0e2d\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e2d\\u0e31\\u0e1b\\u0e42\\u0e2b\\u0e25\\u0e14\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e02\\u0e49\\u0e32\\u0e21\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"warning\"}', '2026-03-09 15:55:31', '2026-03-09 12:20:49', '2026-03-09 15:55:31'),
('5a65fa04-e3c3-455f-afbf-0c1f8f2cc429', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:01:44', '2026-03-09 12:01:36', '2026-03-09 12:01:44'),
('5e06649e-42eb-475b-ac42-ad9e334cdc93', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-2490\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 12:50:11', '2026-03-09 12:49:54', '2026-03-09 12:50:11'),
('6079fa8a-43d8-40a2-8588-5efc6e9c5a2f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e23\\u0e2d\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e2d\\u0e31\\u0e1b\\u0e42\\u0e2b\\u0e25\\u0e14\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e02\\u0e49\\u0e32\\u0e21\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"warning\"}', '2026-03-03 11:28:32', '2026-03-03 11:28:17', '2026-03-03 11:28:32'),
('61f656f0-d078-4e65-8ec4-739d12bad0ac', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', NULL, '2026-03-09 12:51:07', '2026-03-09 12:51:07'),
('67c557cf-2c21-47bd-92ef-7e59fd7adc3a', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-3042\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 15:55:31', '2026-03-09 12:25:15', '2026-03-09 15:55:31'),
('7a13f810-7e94-474b-99a6-39d3073ffee8', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-9755\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 12:01:29', '2026-03-09 12:01:19', '2026-03-09 12:01:29'),
('7a7ec0fb-3fbb-4409-9d90-884c742f4231', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e23\\u0e2d\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e2d\\u0e31\\u0e1b\\u0e42\\u0e2b\\u0e25\\u0e14\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e02\\u0e49\\u0e32\\u0e21\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"warning\"}', '2026-03-09 15:55:31', '2026-03-09 12:51:42', '2026-03-09 15:55:31'),
('7fa3318a-9a3a-46e4-bf10-0c468073df2c', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 5, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-6155 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"https:\\/\\/hempy-julianna-superaerially.ngrok-free.dev\\/customer\\/orders\",\"level\":\"success\"}', NULL, '2026-03-03 14:54:10', '2026-03-03 14:54:10'),
('82ecd449-9941-4068-83c4-e606e10b29f1', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e19\\u0e33\\u0e2a\\u0e48\\u0e07\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', NULL, '2026-03-03 11:27:49', '2026-03-03 11:27:49'),
('9480abce-49c2-4948-a9d3-b25a6458b784', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 Aum \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260303-3538\",\"url\":\"https:\\/\\/hempy-julianna-superaerially.ngrok-free.dev\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-03 14:30:28', '2026-03-03 14:03:52', '2026-03-03 14:30:28'),
('9b2f0861-2725-432b-b5e4-223c6473cf8f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e40\\u0e2a\\u0e23\\u0e47\\u0e08\\u0e2a\\u0e34\\u0e49\\u0e19\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:21:30', '2026-03-09 12:21:24', '2026-03-09 12:21:30'),
('a2a9319e-57a0-4120-9df1-d8ae45f5556d', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-8520\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 15:55:47', '2026-03-09 15:55:33', '2026-03-09 15:55:47'),
('a30272ae-688f-43af-9135-7a1db8a7842f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e22\\u0e37\\u0e19\\u0e22\\u0e31\\u0e19\\u0e01\\u0e32\\u0e23\\u0e0a\\u0e33\\u0e23\\u0e30\\u0e40\\u0e07\\u0e34\\u0e19\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e1c\\u0e48\\u0e32\\u0e19\\u0e01\\u0e32\\u0e23\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:52:28', '2026-03-09 12:52:19', '2026-03-09 12:52:28'),
('a93b76eb-dbe7-455a-8594-a26886d75d13', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-1815 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 12:39:06', '2026-03-09 12:39:16'),
('bd1d3b42-3d81-423c-9664-851fc87f6e2a', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-7181 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e0b\\u0e31\\u0e01\\/\\u0e2d\\u0e1a\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 09:26:12', '2026-03-09 12:39:16'),
('c8e0effa-9c35-456d-8442-076a159f61e4', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-7181 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e41\\u0e25\\u0e49\\u0e27\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 09:26:05', '2026-03-09 12:39:16'),
('ccf0046f-b813-47f9-92c8-283a9b757406', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-7181 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e19\\u0e33\\u0e2a\\u0e48\\u0e07\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 10:40:51', '2026-03-09 12:39:16'),
('cf924889-6ba5-481c-993d-e461fbb88b43', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-7181 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27 (\\u0e42\\u0e2d\\u0e19\\/\\u0e2a\\u0e41\\u0e01\\u0e19 QR)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"success\"}', '2026-03-09 12:39:16', '2026-03-09 09:23:58', '2026-03-09 12:39:16'),
('d680f00d-3534-45eb-9138-7cb5f9e99f7a', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-3042 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e0b\\u0e31\\u0e01\\/\\u0e2d\\u0e1a\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:25:59', '2026-03-09 12:25:46', '2026-03-09 12:25:59'),
('de0ca200-c8b4-45e7-a115-e6a75d7d55d9', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-9487 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e40\\u0e2a\\u0e23\\u0e47\\u0e08\\u0e2a\\u0e34\\u0e49\\u0e19\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-05 09:36:31', '2026-03-03 11:28:47', '2026-03-05 09:36:31'),
('e241d609-9191-495c-a835-ce73a65db971', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 4, '{\"title\":\"\\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e2a\\u0e33\\u0e40\\u0e23\\u0e47\\u0e08\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260303-3538 \\u0e16\\u0e39\\u0e01\\u0e2a\\u0e23\\u0e49\\u0e32\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e1a\\u0e23\\u0e49\\u0e2d\\u0e22\\u0e41\\u0e25\\u0e49\\u0e27\",\"url\":\"https:\\/\\/hempy-julianna-superaerially.ngrok-free.dev\\/customer\\/orders\",\"level\":\"success\"}', NULL, '2026-03-03 14:03:52', '2026-03-03 14:03:52'),
('ed11f23b-065e-4127-8d7f-2ecd8a8d0a82', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e21\\u0e35\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\\u0e43\\u0e2b\\u0e21\\u0e48\",\"message\":\"\\u0e25\\u0e39\\u0e01\\u0e04\\u0e49\\u0e32 \\u0e2b\\u0e21\\u0e34\\u0e07 \\u0e27\\u0e39\\u0e4b\\u0e2d\\u0e27\\u0e34\\u0e48\\u0e07 \\u0e08\\u0e2d\\u0e07\\u0e04\\u0e34\\u0e27\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e2b\\u0e21\\u0e32\\u0e22\\u0e40\\u0e25\\u0e02 ORD-20260309-7181\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\",\"level\":\"info\"}', '2026-03-09 15:55:31', '2026-03-09 09:23:58', '2026-03-09 15:55:31'),
('f2c68526-ed82-4290-9778-6b3babb3ff50', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c\",\"message\":\"\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-2490 \\u0e40\\u0e1b\\u0e25\\u0e35\\u0e48\\u0e22\\u0e19\\u0e40\\u0e1b\\u0e47\\u0e19 \\\"\\u0e40\\u0e2a\\u0e23\\u0e47\\u0e08\\u0e2a\\u0e34\\u0e49\\u0e19\\\"\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', NULL, '2026-03-09 12:52:32', '2026-03-09 12:52:32'),
('f89d1f06-24b7-4991-82a2-143ed5469530', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 6, '{\"title\":\"\\u0e2a\\u0e48\\u0e07\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e41\\u0e25\\u0e49\\u0e27\",\"message\":\"\\u0e23\\u0e30\\u0e1a\\u0e1a\\u0e23\\u0e31\\u0e1a\\u0e2a\\u0e25\\u0e34\\u0e1b\\u0e02\\u0e2d\\u0e07\\u0e2d\\u0e2d\\u0e40\\u0e14\\u0e2d\\u0e23\\u0e4c ORD-20260309-9755 \\u0e41\\u0e25\\u0e49\\u0e27 \\u0e01\\u0e33\\u0e25\\u0e31\\u0e07\\u0e23\\u0e2d\\u0e41\\u0e2d\\u0e14\\u0e21\\u0e34\\u0e19\\u0e15\\u0e23\\u0e27\\u0e08\\u0e2a\\u0e2d\\u0e1a\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"level\":\"info\"}', '2026-03-09 12:39:16', '2026-03-09 12:20:49', '2026-03-09 12:39:16');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `time_slot_id` bigint(20) UNSIGNED NOT NULL,
  `pickup_address` text NOT NULL,
  `pickup_latitude` varchar(255) DEFAULT NULL,
  `pickup_longitude` varchar(255) DEFAULT NULL,
  `pickup_map_link` varchar(255) DEFAULT NULL,
  `use_customer_detergent` tinyint(1) NOT NULL DEFAULT 0,
  `use_customer_softener` tinyint(1) NOT NULL DEFAULT 0,
  `wash_temp` enum('เย็น','อุ่น','ร้อน') DEFAULT NULL,
  `dry_temp` enum('อุ่น','ปานกลาง','ร้อน') DEFAULT NULL,
  `status` enum('pending','picked_up','processing','delivering','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','pending_cash','reviewing','paid') DEFAULT 'unpaid',
  `payment_method` enum('transfer','cash') NOT NULL DEFAULT 'transfer',
  `subtotal` decimal(8,2) NOT NULL DEFAULT 0.00,
  `addon_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `selected_addons` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_addons`)),
  `payment_slip` varchar(255) DEFAULT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `package_id`, `time_slot_id`, `pickup_address`, `pickup_latitude`, `pickup_longitude`, `pickup_map_link`, `use_customer_detergent`, `use_customer_softener`, `wash_temp`, `dry_temp`, `status`, `payment_status`, `payment_method`, `subtotal`, `addon_total`, `selected_addons`, `payment_slip`, `total_price`, `created_at`, `updated_at`) VALUES
(3, 'ORD-20260303-9487', 3, 1, 2, '346, ตำบลระแหง, เทศบาลตำบลระแหง, อำเภอลาดหลุมแก้ว, จังหวัดปทุมธานี, 12140, ประเทศไทย', NULL, NULL, NULL, 0, 0, 'เย็น', 'อุ่น', 'completed', 'paid', 'transfer', 0.00, 0.00, NULL, 'slips/IFlP8LbrtMq1Yn9zQvAfSZqfO4zgTrNhnlCMsVnF.jpg', 120.00, '2026-03-03 10:57:52', '2026-03-03 11:28:47'),
(4, 'ORD-20260303-3538', 4, 1, 1, 'ปทุม', NULL, NULL, NULL, 0, 0, 'เย็น', 'อุ่น', 'pending', 'unpaid', 'transfer', 0.00, 0.00, NULL, NULL, 120.00, '2026-03-03 14:03:52', '2026-03-03 14:03:52'),
(5, 'ORD-20260303-6155', 5, 1, 1, 'ปทุม', NULL, NULL, NULL, 0, 0, 'อุ่น', 'อุ่น', 'pending', 'unpaid', 'transfer', 0.00, 0.00, NULL, NULL, 120.00, '2026-03-03 14:54:10', '2026-03-03 14:54:10'),
(6, 'ORD-20260309-7181', 6, 1, 2, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://www.google.com/maps?q=14.0201,100.5235', 0, 0, 'อุ่น', 'ปานกลาง', 'cancelled', 'unpaid', 'transfer', 120.00, 18.00, '[{\"code\":\"detergent_hygiene_detergent_35\",\"name\":\"Hygiene Detergent \\u0e1c\\u0e25\\u0e34\\u0e15\\u0e20\\u0e31\\u0e13\\u0e11\\u0e4c\\u0e0b\\u0e31\\u0e01\\u0e1c\\u0e49\\u0e32\\u0e0a\\u0e19\\u0e34\\u0e14\\u0e19\\u0e49\\u0e33 \\u0e02\\u0e19\\u0e32\\u0e14 35 \\u0e21\\u0e25.\",\"category\":\"detergent\",\"unit_price\":5,\"qty\":1,\"line_total\":5},{\"code\":\"softener_20_1\",\"name\":\"\\u0e44\\u0e2e\\u0e22\\u0e35\\u0e19 \\u0e2a\\u0e39\\u0e15\\u0e23\\u0e40\\u0e02\\u0e49\\u0e21\\u0e02\\u0e49\\u0e19 \\u0e01\\u0e25\\u0e34\\u0e48\\u0e19\\u0e21\\u0e2d\\u0e19\\u0e34\\u0e48\\u0e07\\u0e40\\u0e1f\\u0e23\\u0e0a \\u0e1f\\u0e49\\u0e32 20 \\u0e21\\u0e25.\",\"category\":\"softener\",\"unit_price\":5,\"qty\":1,\"line_total\":5},{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', NULL, 138.00, '2026-03-09 09:23:56', '2026-03-09 12:37:46'),
(7, 'ORD-20260309-9755', 6, 1, 1, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://www.google.com/maps?q=14.0201,100.5235', 1, 1, 'อุ่น', 'อุ่น', 'completed', 'paid', 'transfer', 120.00, 8.00, '[{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', 'slips/KP1QeiXAeJNgSk2kBvGtzF3ECOtIhsqnGhZ7S4T2.jpg', 128.00, '2026-03-09 12:01:19', '2026-03-09 12:21:24'),
(8, 'ORD-20260309-3042', 6, 2, 2, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://www.google.com/maps?q=14.0201,100.5235', 1, 1, 'อุ่น', 'อุ่น', 'completed', 'paid', 'cash', 160.00, 8.00, '[{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', NULL, 168.00, '2026-03-09 12:25:14', '2026-03-09 12:36:32'),
(9, 'ORD-20260309-1815', 6, 1, 2, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://www.google.com/maps?q=14.0201,100.5235', 1, 1, 'อุ่น', 'อุ่น', 'picked_up', 'unpaid', 'transfer', 120.00, 8.00, '[{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', NULL, 128.00, '2026-03-09 12:38:45', '2026-03-09 12:39:06'),
(10, 'ORD-20260309-2490', 6, 1, 1, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://maps.google.com/?q=14.0201,100.5235', 1, 0, 'อุ่น', 'อุ่น', 'completed', 'paid', 'transfer', 120.00, 13.00, '[{\"code\":\"softener_20\",\"name\":\"\\u0e14\\u0e32\\u0e27\\u0e19\\u0e4c\\u0e19\\u0e35\\u0e48 \\u0e19\\u0e49\\u0e33\\u0e22\\u0e32\\u0e1b\\u0e23\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e19\\u0e38\\u0e48\\u0e21 \\u0e21\\u0e34\\u0e2a\\u0e17\\u0e35\\u0e04 20 \\u0e21\\u0e25.\",\"category\":\"softener\",\"unit_price\":5,\"qty\":1,\"line_total\":5},{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', 'slips/7MtDxeXeV1ovQaD8Zt8VZY3nU30pP2BBaW9WRf0d.png', 133.00, '2026-03-09 12:49:54', '2026-03-09 12:52:32'),
(11, 'ORD-20260309-8520', 6, 1, 2, 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://maps.google.com/?q=14.0201,100.5235', 1, 1, 'อุ่น', 'อุ่น', 'pending', 'unpaid', 'transfer', 120.00, 8.00, '[{\"code\":\"fold_premium\",\"name\":\"\\u0e1e\\u0e31\\u0e1a\\u0e1c\\u0e49\\u0e32\\u0e1e\\u0e23\\u0e35\\u0e40\\u0e21\\u0e35\\u0e22\\u0e21\\u0e41\\u0e22\\u0e01\\u0e1b\\u0e23\\u0e30\\u0e40\\u0e20\\u0e17\",\"category\":\"service\",\"unit_price\":8,\"qty\":1,\"line_total\":8}]', NULL, 128.00, '2026-03-09 15:55:33', '2026-03-09 15:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_logs`
--

CREATE TABLE `order_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_logs`
--

INSERT INTO `order_logs` (`id`, `order_id`, `user_id`, `old_status`, `new_status`, `created_at`, `updated_at`) VALUES
(10, 3, 1, 'pending', 'picked_up', '2026-03-03 11:01:17', '2026-03-03 11:01:17'),
(11, 3, 1, 'picked_up', 'processing', '2026-03-03 11:11:39', '2026-03-03 11:11:39'),
(12, 3, 1, 'processing', 'delivering', '2026-03-03 11:27:49', '2026-03-03 11:27:49'),
(13, 3, 1, 'delivering', 'completed', '2026-03-03 11:28:47', '2026-03-03 11:28:47'),
(14, 6, 1, 'pending', 'picked_up', '2026-03-09 09:26:05', '2026-03-09 09:26:05'),
(15, 6, 1, 'picked_up', 'processing', '2026-03-09 09:26:12', '2026-03-09 09:26:12'),
(16, 6, 1, 'processing', 'delivering', '2026-03-09 10:40:50', '2026-03-09 10:40:50'),
(17, 7, 1, 'pending', 'picked_up', '2026-03-09 12:01:36', '2026-03-09 12:01:36'),
(18, 7, 1, 'picked_up', 'processing', '2026-03-09 12:11:19', '2026-03-09 12:11:19'),
(19, 7, 1, 'processing', 'delivering', '2026-03-09 12:20:23', '2026-03-09 12:20:23'),
(20, 7, 1, 'delivering', 'completed', '2026-03-09 12:21:24', '2026-03-09 12:21:24'),
(21, 8, 1, 'pending', 'picked_up', '2026-03-09 12:25:26', '2026-03-09 12:25:26'),
(22, 8, 1, 'picked_up', 'processing', '2026-03-09 12:25:46', '2026-03-09 12:25:46'),
(23, 8, 1, 'processing', 'delivering', '2026-03-09 12:36:13', '2026-03-09 12:36:13'),
(24, 8, 1, 'delivering', 'completed', '2026-03-09 12:36:32', '2026-03-09 12:36:32'),
(25, 6, 1, 'delivering', 'cancelled', '2026-03-09 12:37:46', '2026-03-09 12:37:46'),
(26, 9, 1, 'pending', 'picked_up', '2026-03-09 12:39:06', '2026-03-09 12:39:06'),
(27, 10, 1, 'pending', 'picked_up', '2026-03-09 12:51:07', '2026-03-09 12:51:07'),
(28, 10, 1, 'picked_up', 'completed', '2026-03-09 12:52:32', '2026-03-09 12:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `description` text DEFAULT NULL,
  `default_detergent_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `description`, `default_detergent_code`, `created_at`, `updated_at`) VALUES
(1, 'ซัก-อบ-พับ (ไม่เกิน 5 กก.)', 120.00, 'เหมาะสำหรับผ้า 1 ตะกร้าเล็ก', NULL, '2026-03-02 12:07:49', '2026-03-05 06:00:25'),
(2, 'ซัก-อบ-พับ (ไม่เกิน 10 กก.)', 160.00, 'เหมาะสำหรับผ้า 1-2 สัปดาห์', NULL, '2026-03-02 12:07:49', '2026-03-05 06:00:25');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
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
('1PbvQz5pk3O5Ay4qH8ZWOyikN2rV8yhDVbMFfUB1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQW5BR2pTam1sdHl4cXhGOVJTR0h6ck5rb2dmNTZ6TGNSQ3l6UElMNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7czoxNzoiYXBpLm5vdGlmaWNhdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1773060753),
('6D9qnzCRn6UUGKP6FHeUB2HNdcq1ln672BvOJC2O', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTG9NbnRsVkRTRjJ3ZDBJWUJjaXpLUklGWllkTDgzN3hIRUJxMHdCZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7czoxNzoiYXBpLm5vdGlmaWNhdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1773071748),
('7kJatMAHxLh3Z4oRDa1TdT9f6tw6CGZSLYEsVENU', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMmRhQlpINnB0cW9lSmwwZ2hVU1pDT1lhcnp6VGRlWTE1cTByVGNtZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucyI7czo1OiJyb3V0ZSI7czoxNzoiYXBpLm5vdGlmaWNhdGlvbnMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1773072474);

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `round_name` varchar(255) NOT NULL,
  `max_quota` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `round_name`, `max_quota`, `created_at`, `updated_at`) VALUES
(1, 'รอบเช้า (08:00 - 12:00)', 10, '2026-03-02 12:07:54', '2026-03-02 12:07:54'),
(2, 'รอบบ่าย (13:00 - 17:00)', 10, '2026-03-02 12:07:55', '2026-03-02 12:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `line_id` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `map_link` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `line_id`, `fullname`, `username`, `avatar`, `password`, `phone`, `address`, `latitude`, `longitude`, `map_link`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'อิมอั้ม', 'aimaum', NULL, '$2y$12$eKltOCXzuiavadv86EWa3OOFaJIvIxvKxGL82Odg0Ctf3EWDOByoS', '0800000000', 'ร้านรับส่งผ้า', NULL, NULL, NULL, 'admin', NULL, '2026-03-02 11:08:53', '2026-03-09 10:02:56'),
(3, NULL, 'ลูกค้า คนแรก', 'customer', NULL, '$2y$12$6JKmQrcPsTaPQ0lsveGSH.6ac9K6l/JlS3K6Oh5anxPILAu5r3gJ.', '0123456789', '346, ตำบลระแหง, เทศบาลตำบลระแหง, อำเภอลาดหลุมแก้ว, จังหวัดปทุมธานี, 12140, ประเทศไทย', NULL, NULL, NULL, 'customer', NULL, '2026-03-03 09:05:49', '2026-03-03 09:05:49'),
(4, NULL, 'Aum', 'Aa', NULL, '$2y$12$n1PeHJnn4vXokhrB4HfJDeOIrZyyVcmG3XGalJO8F2MiykxYODmCS', '0886606200', 'ปทุม', NULL, NULL, NULL, 'customer', NULL, '2026-03-03 13:52:24', '2026-03-03 13:52:24'),
(5, NULL, 'Aum', 'Aum', NULL, '$2y$12$ikP/FBlRnjVubvOKID9YPup.sxPK/wzfrWnUqTKPUqgGi/2/ibpUK', '0886606200', 'ปทุม', NULL, NULL, NULL, 'customer', NULL, '2026-03-03 14:48:17', '2026-03-03 14:48:17'),
(6, NULL, 'หมิง วู๋อวิ่ง', 'mingming', NULL, '$2y$12$VSp57ITWbBZRDEJOrTPQFObkCmU.yeDO0EWRU8M8xVJfSrD.4QSrS', '0963571559', 'โรงพยาบาลปทุมธานี, ถนนปทุมธานี-ลาดหลุมแก้ว, ตำบลบางปรอก, เทศบาลเมืองปทุมธานี, องค์การบริหารส่วนตำบลบ้างฉาง, อำเภอเมืองปทุมธานี, จังหวัดปทุมธานี, 12160, ประเทศไทย', '14.0201', '100.5235', 'https://maps.google.com/?q=14.0201,100.5235', 'customer', NULL, '2026-03-09 08:38:47', '2026-03-09 12:49:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `addon_options_code_unique` (`code`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_package_id_foreign` (`package_id`),
  ADD KEY `orders_time_slot_id_foreign` (`time_slot_id`);

--
-- Indexes for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_logs_order_id_foreign` (`order_id`),
  ADD KEY `order_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_line_id_unique` (`line_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addon_options`
--
ALTER TABLE `addon_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_logs`
--
ALTER TABLE `order_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `orders_time_slot_id_foreign` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD CONSTRAINT `order_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
