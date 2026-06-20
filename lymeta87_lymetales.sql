-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2026 at 07:43 AM
-- Server version: 10.11.17-MariaDB-cll-lve-log
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lymeta87_lymetales`
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_special`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Fant', 'fant', NULL, 0, 1, '2026-06-14 09:30:13', '2026-06-14 09:30:33'),
(4, 'Dekle', 'dekle', NULL, 0, 1, '2026-06-14 09:30:51', '2026-06-14 09:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `description`, `expiry_date`, `usage_limit`, `used_count`, `status`, `created_at`, `updated_at`) VALUES
(5, 'URSKA-IQWTKN', 'percent', 10.00, '20 $ popusta za nove stranke pri prvem nakupu.', '2026-10-31', 100, 0, 1, '2026-06-18 00:12:39', '2026-06-18 00:13:10'),
(6, 'URSKA-VFB7M3', 'percent', 15.00, '50 $ popusta v okviru poletne promocije.', '2026-08-31', 500, 0, 1, '2026-06-18 00:14:25', '2026-06-18 00:14:25'),
(7, 'URSKA-EZ1VZA', 'free_shipping', 0.00, 'Free delivery available', '2026-10-20', 1000, 0, 1, '2026-06-18 00:16:11', '2026-06-18 00:16:11');

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(7, 'Katere podatke potrebujem za personalizacijo knjige?', 'Preprosto dodajte njihovo ime, izberite lik, ki je videti natanko tako kot oni, in napišite prisrčno posvetilo. Prilagoditev popolnoma edinstvene pravljične knjige je zdaj lažja kot kdaj koli prej.', '2026-06-17 23:49:27', '2026-06-17 23:49:27'),
(8, 'Kako dobim kodo za popust?', 'Na naše novice se lahko naročite tako, da na dnu strani vnesete svoj e-poštni naslov in prejmete 10 % popust na prvo naročilo.', '2026-06-17 23:50:12', '2026-06-17 23:50:12'),
(9, 'Kakšen je vaš dobavni rok?', 'Naša izdelava traja 1–2 delovna dneva. Čas dostave se giblje od 2 do 5 delovnih dni za standardno dostavo, odvisno od vaše lokacije.', '2026-06-17 23:51:11', '2026-06-17 23:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `footer_items`
--

CREATE TABLE `footer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `footer_section_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_items`
--

INSERT INTO `footer_items` (`id`, `footer_section_id`, `label`, `url`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Naše knjige', '/books', 0, '2026-06-14 08:46:26', '2026-06-16 21:39:02'),
(2, 1, 'Darilne kartice', '/books?site_category_id=4', 1, '2026-06-14 08:46:26', '2026-06-16 21:40:35'),
(3, 1, 'Novi prihodi', '/books?site_category_id=7', 2, '2026-06-14 08:46:26', '2026-06-16 21:41:06'),
(4, 2, 'Center za pomoč', '/help', 0, '2026-06-14 08:46:26', '2026-06-17 23:53:00'),
(5, 2, 'Pišite nam', '/contact', 1, '2026-06-14 08:46:26', '2026-06-17 23:53:19'),
(6, 3, 'Naša zgodba', '/our-story', 0, '2026-06-14 08:46:26', '2026-06-17 23:55:25'),
(7, 3, 'Politika zasebnosti', '/privacy-policy', 1, '2026-06-14 08:46:26', '2026-06-17 23:55:48'),
(8, 3, 'Pogoji uporabe', '/terms', 2, '2026-06-14 08:46:26', '2026-06-17 23:56:00'),
(12, 3, 'Our Blogs', '/blogs', 3, '2026-06-20 01:59:44', '2026-06-20 01:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `footer_sections`
--

CREATE TABLE `footer_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_sections`
--

INSERT INTO `footer_sections` (`id`, `title`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Trgovina', 1, '2026-06-14 08:46:26', '2026-06-17 23:55:11'),
(2, 'Podpora', 2, '2026-06-14 08:46:26', '2026-06-17 23:54:41'),
(3, 'Podjetje', 3, '2026-06-14 08:46:26', '2026-06-17 23:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE `gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gifts`
--

INSERT INTO `gifts` (`id`, `title`, `short_description`, `price`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'Medvedek za Objem', 'Mehek in prikupen plišasti medvedek, popoln za rojstne dneve, posebne priložnosti ali kot srčno darilo za vaše najdražje.', 29.99, 'uploads/gifts/1781748343_6a335277510a2.jpg', '2026-06-18 00:05:43', '2026-06-18 00:05:43'),
(2, 'Romantična Vrtnica', 'Elegantna vrtnica, ki izraža ljubezen in naklonjenost. Čudovito darilo za posebne trenutke in praznovanja.', 19.99, 'uploads/gifts/1781748377_6a3352995ef9e.jpg', '2026-06-18 00:06:17', '2026-06-18 00:06:17'),
(3, 'Darilna Košara Sladkih Presenečenj', 'Bogata košara z izbranimi sladkarijami in dobrotami, primerna za praznike, rojstne dneve ali zahvalo.', 49.99, 'uploads/gifts/1781748408_6a3352b861d57.jpg', '2026-06-18 00:06:48', '2026-06-18 00:06:48'),
(4, 'Dišeča Sveča Premium', 'Razkošna dišeča sveča, ki ustvari prijetno in sproščujoče vzdušje v vsakem prostoru.', 24.99, 'uploads/gifts/1781748442_6a3352daadfd8.jpg', '2026-06-18 00:07:22', '2026-06-18 00:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gift_cards`
--

INSERT INTO `gift_cards` (`id`, `title`, `subtitle`, `image_path`, `link`, `created_at`, `updated_at`) VALUES
(4, 'Novi začetki', 'Pozdravite malčka na svet z zgodbo, ustvarjeno samo zanj.', 'uploads/home/1781746632_image.png', NULL, '2026-06-17 23:37:12', '2026-06-17 23:37:12'),
(5, 'Skupno odraščanje', 'Praznujte mejnike, dogodivščine in veselje do učenja.', 'uploads/home/1781746701_image2.png', NULL, '2026-06-17 23:38:21', '2026-06-17 23:38:21'),
(6, 'Trenutki ljubezni', 'Darilo, ki sporoča, da ste ljubljeni – od starih staršev, staršev in družine.', 'uploads/home/1781746756_image3.png', NULL, '2026-06-17 23:39:17', '2026-06-17 23:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `gift_givers`
--

CREATE TABLE `gift_givers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subtitle` varchar(255) NOT NULL DEFAULT 'BECOME A',
  `title` varchar(255) NOT NULL DEFAULT 'Legendary gift-giver',
  `step_1_image` varchar(255) DEFAULT NULL,
  `step_1_text` varchar(255) NOT NULL DEFAULT 'Fill in a few bits of info',
  `step_2_image` varchar(255) DEFAULT NULL,
  `step_2_text` varchar(255) NOT NULL DEFAULT 'Preview personalisation in real time',
  `step_3_image` varchar(255) DEFAULT NULL,
  `step_3_text` varchar(255) NOT NULL DEFAULT 'Deliver smiles of joy to your favourite child',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gift_givers`
--

INSERT INTO `gift_givers` (`id`, `subtitle`, `title`, `step_1_image`, `step_1_text`, `step_2_image`, `step_2_text`, `step_3_image`, `step_3_text`, `created_at`, `updated_at`) VALUES
(1, 'Postanite a', 'Legendarni darovalec daril', 'uploads/home/step1_1781747305_photo_2026-06-16_14-44-16.jpg', 'Izpolnite nekaj podatkov', 'uploads/home/step2_1781747305_photo_2026-06-16_14-44-20.jpg', 'Predogled personalizacije v realnem času', 'uploads/home/step3_1781747305_photo_2026-06-16_14-44-23.jpg', 'Podarite nasmehe sreče svojemu najljubšemu otroku', '2026-06-14 08:46:26', '2026-06-17 23:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `hero_sections`
--

CREATE TABLE `hero_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `button_one_text` varchar(255) DEFAULT NULL,
  `button_one_link` varchar(255) DEFAULT NULL,
  `button_two_text` varchar(255) DEFAULT NULL,
  `button_two_link` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_sections`
--

INSERT INTO `hero_sections` (`id`, `title`, `subtitle`, `button_one_text`, `button_one_link`, `button_two_text`, `button_two_link`, `image_path`, `created_at`, `updated_at`) VALUES
(4, 'Velikonočni spomini', NULL, 'Prilagodi zdaj', NULL, 'Nakupujte vse knjige', NULL, 'uploads/home/1781746277_banner1.png', '2026-06-17 23:31:17', '2026-06-17 23:31:17'),
(5, 'Poletne zgodbe', NULL, 'Prilagodi zdaj', NULL, 'Nakupujte vse knjige', NULL, 'uploads/home/1781746333_banner3.jpg', '2026-06-17 23:32:13', '2026-06-17 23:32:13'),
(7, 'Družinski zakladi', NULL, 'Prilagodi zdaj', NULL, 'Nakupujte vse knjige', NULL, 'uploads/home/1781746443_banner5.png', '2026-06-17 23:34:03', '2026-06-17 23:34:03'),
(8, 'Zlati trenutki', NULL, 'Prilagodi zdaj', NULL, 'Nakupujte vse knjige', NULL, 'uploads/home/1781746492_banner4.jpg', '2026-06-17 23:34:52', '2026-06-17 23:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `home_features`
--

CREATE TABLE `home_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_features`
--

INSERT INTO `home_features` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(8, 'Vrednote, ki trajajo večno', 'Naše zgodbe navdihujejo prijaznost, pogum in ljubezen ter ustvarjajo dragocene spomine, ki ostanejo za vse življenje.', '2026-06-17 23:35:43', '2026-06-17 23:35:43'),
(9, 'Ustvarjeno z ljubeznijo in skrbnostjo', 'Vsak izdelek je skrbno izdelan in pripravljen z veliko pozornosti, da prinese veselje ob vsakem naročilu.', '2026-06-17 23:35:59', '2026-06-17 23:35:59'),
(10, 'Čarobni trenutki za vsakogar', 'Posebna darila ustvarjajo nepozabne trenutke sreče, topline in povezanosti med vašimi najdražjimi.', '2026-06-17 23:36:15', '2026-06-17 23:36:15'),
(11, 'Kakovost, ki ji lahko zaupate', 'Zavezani smo vrhunski kakovosti in premišljeni izdelavi, da boste z vsakim nakupom popolnoma zadovoljni.', '2026-06-17 23:36:29', '2026-06-17 23:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `home_promos`
--

CREATE TABLE `home_promos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `button_text` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_promos`
--

INSERT INTO `home_promos` (`id`, `title`, `description`, `button_text`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'Skrbno narejeno za ljudi, ki jih imate najraje', 'Od imen do odtenkov kože, barv oblačil do iskrenih posvetil – vse je mogoče prilagoditi, da ustvarite edinstveno darilo zanje.', 'Nakupujte zgodbo', 'uploads/home/promo_1781746798_about.webp', '2026-06-14 08:46:26', '2026-06-17 23:39:58');

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
(4, '2026_04_17_214505_create_personal_access_tokens_table', 1),
(5, '2026_05_21_000001_create_categories_table', 1),
(6, '2026_05_21_000002_create_coupons_table', 1),
(7, '2026_05_21_000003_create_products_table', 1),
(8, '2026_05_24_222532_create_product_reviews_table', 1),
(9, '2026_05_25_000001_add_image_gallery_to_products_table', 1),
(10, '2026_05_25_000002_create_product_images_table', 1),
(11, '2026_05_25_000003_update_product_images_add_sort_order', 1),
(12, '2026_05_26_000000_add_special_section_to_products_table', 1),
(13, '2026_05_26_000001_add_title_to_product_reviews_table', 1),
(14, '2026_05_27_000001_create_subcategories_table', 1),
(15, '2026_06_01_000001_create_orders_table', 1),
(16, '2026_06_03_120300_create_product_category_images_table', 1),
(17, '2026_06_03_153000_add_name_text_to_products_table', 1),
(18, '2026_06_05_000000_create_settings_table', 1),
(19, '2026_06_07_000001_add_parent_id_to_subcategories_table', 1),
(20, '2026_06_08_000001_create_product_customization_steps_table', 1),
(21, '2026_06_08_000002_create_product_customization_options_table', 1),
(22, '2026_06_08_000003_create_product_customization_substeps_table', 1),
(23, '2026_06_08_000004_create_product_customization_suboptions_table', 1),
(24, '2026_06_10_000001_add_order_payment_status_to_orders_table', 1),
(25, '2026_06_10_100001_create_site_categories_table', 1),
(26, '2026_06_10_100002_add_featured_image_id_to_products_table_new', 1),
(27, '2026_06_10_100003_add_site_category_to_products_table', 1),
(28, '2026_06_13_000001_add_is_special_to_site_categories_table', 1),
(29, '2026_06_15_000000_create_pages_table', 1),
(30, '2026_06_15_000001_create_contact_messages_table', 1),
(31, '2026_06_16_000000_create_hero_sections_table', 1),
(32, '2026_06_16_000001_create_gift_cards_table', 1),
(33, '2026_06_16_000002_create_faqs_table', 1),
(34, '2026_06_17_000001_add_option_type_to_product_category_images_table', 1),
(35, '2026_06_18_000001_add_domain_to_products_table', 1),
(36, '2026_06_19_000001_add_type_to_customization_tables', 1),
(37, '2026_06_20_000000_create_gifts_table', 1),
(38, '2026_06_21_000000_create_offers_table', 1),
(39, '2026_06_22_000000_add_short_description_to_offers_table', 1),
(40, '2026_06_23_000000_create_new_home_content_tables', 1),
(41, '2026_06_23_000001_add_slug_to_subcategories_table', 1),
(42, '2026_06_24_000000_add_type_to_products_table', 2),
(43, '2026_06_24_000001_create_product_book_images_table', 3),
(44, '2026_06_25_000000_create_product_book_images_table', 4),
(45, '2026_06_25_000001_add_is_featured_to_site_categories_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `min_quantity` int(11) NOT NULL DEFAULT 2,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 20.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `short_description`, `min_quantity`, `discount_percentage`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Kupi Več, Prihrani Več', 'Ob nakupu 3 ali več izdelkov prejmete 10 % popust na celotno naročilo.', 2, 10.00, 0, '2026-06-18 00:10:08', '2026-06-18 00:10:39'),
(3, 'Družinski Paket', 'Kupite 5 ali več izdelkov in izkoristite ugoden 15 % popust.', 4, 15.00, 0, '2026-06-18 00:10:39', '2026-06-18 00:11:16'),
(4, 'Velik Nakup, Velik Popust', 'Za naročila s 7 ali več izdelki prejmete 20 % popust.', 6, 20.00, 1, '2026-06-18 00:11:16', '2026-06-18 00:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(12) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `email` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fast_production_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `status`, `order_status`, `payment_status`, `email`, `full_name`, `address`, `city`, `postal_code`, `country`, `phone`, `items`, `subtotal`, `shipping_fee`, `fast_production_fee`, `discount`, `coupon_code`, `total`, `payment_method`, `stripe_payment_intent_id`, `created_at`, `updated_at`) VALUES
(1, 'LYM-Z8LIOEZB', NULL, 'pending', 'paid', 'walox@mailinator.com', 'Oleg Villarreal', 'In in ut obcaecati s', 'Est amet deleniti', 'Voluptatem odit reru', 'Velit quos et id cu', '01725044522', '[{\"product_id\":4,\"title\":\"Bo\\u017ei\\u010dkov pomo\\u010dnik\",\"image\":\"https:\\/\\/admin.lymetales.com\\/storage\\/products\\/gal_6a2ed72d90c6b6.00247571_p0M8L.png\",\"unit_price\":715,\"quantity\":1,\"line_total\":715,\"type\":\"product\",\"description\":\"Naj tvoja mala iskrica v o\\u010deh nikoli ne izgine. Naj bo tvoj svet vedno tako pisan kot ga vidi\\u0161 sedaj in naj ti ta knjigica obudi lepe spomine.\"}]', 715.00, 5.95, 0.00, 0.00, NULL, 720.95, 'stripe', 'pi_3TjW4tRuXg37YlQA1CFL6nGi', '2026-06-18 00:52:21', '2026-06-18 00:54:00'),
(2, 'LYM-3WEL1RJW', NULL, 'pending', 'paid', 'jan@gmail.com', 'Jan de Vries', 'Hoofdstraat 1', 'Amsterdam', '1011 AB', 'Netherlands', '+31612345678', '[{\"product_id\":9,\"title\":\"\\u010cude\\u017eni zaj\\u010dek\",\"image\":\"https:\\/\\/admin.lymetales.com\\/storage\\/products\\/gal_6a2f5db513d9a5.47646565_fPHYV.jpeg\",\"unit_price\":19.989999999999998436805981327779591083526611328125,\"quantity\":2,\"line_total\":39.97999999999999687361196265555918216705322265625,\"type\":\"product\",\"description\":\"Prvi \\u0161olski dan je lahko poln vznemirjenja in skrbi. V tej \\u010dudoviti zgodbi tvoj otrok pomaga medvedku premagati strah pred \\u0161olo, spoznati nove so\\u0161olce in odkriti, kako zabavno je u\\u010denje.\"}]', 39.98, 5.95, 0.00, 0.00, NULL, 45.93, 'stripe', 'pi_3TjZWqRuXg37YlQA16MPmThp', '2026-06-18 04:31:28', '2026-06-18 04:38:09'),
(4, 'LYM-GV9UMKSP', NULL, 'pending', 'paid', 'cuke@mailinator.com', 'Desirae Baldwin', 'Soluta aut iure nequ', 'Autem consequat Dol', 'Rem in harum ex mini', 'Ut minim quia veniam', '01234567', '[{\"product_id\":7,\"title\":\"Skrivnostni podvodni svet\",\"image\":\"https:\\/\\/admin.lymetales.com\\/storage\\/products\\/gal_6a2f629d115497.44981874_XTH8P.jpg\",\"unit_price\":22,\"quantity\":1,\"line_total\":22,\"type\":\"product\",\"description\":\"Potopi se globoko v ocean, spoznaj govore\\u010de ribice, modre kite in odkrij skriti potopljeni zaklad. Knjiga spodbuja otro\\u0161ko radovednost in ljubezen do narave ter oceanov.\"},{\"product_id\":3,\"title\":\"Darilna Ko\\u0161ara Sladkih Presene\\u010denj\",\"image\":\"https:\\/\\/admin.lymetales.com\\/uploads\\/gifts\\/1781748408_6a3352b861d57.jpg\",\"unit_price\":49.99000000000000198951966012828052043914794921875,\"quantity\":1,\"line_total\":49.99000000000000198951966012828052043914794921875,\"type\":\"gift\",\"description\":\"Bogata ko\\u0161ara z izbranimi sladkarijami in dobrotami, primerna za praznike, rojstne dneve ali zahvalo.\"}]', 71.99, 5.95, 0.00, 0.00, NULL, 77.94, 'stripe', 'pi_3TjZbYRuXg37YlQA12A3eR8r', '2026-06-18 04:38:51', '2026-06-18 04:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Our Story', 'our-story', '{\"hero\":{\"badge\":\"NA\\u0160A ZGODBA\",\"title\":\"Zgodbe, ki ostanejo za vedno.\",\"subtitle\":\"Verjamemo, da si vsak otrok zaslu\\u017ei biti junak svojega vesolja.\",\"button_text\":\"Oglejte si na\\u0161e knjige\",\"button_url\":\"\\/books\",\"image_url\":\"uploads\\/pages\\/1781500269_6a2f896d178dc_banner1.png\"},\"mission\":{\"title\":\"Verjamemo, da si vsak otrok zaslu\\u017ei biti junak svojega vesolja.\",\"paragraphs\":[\"<p>Lymetales se je rodil iz preproste ideje: najbolj \\u010darobne zgodbe so tiste, v katerih je va\\u0161 otrok zvezda. Ustvarjamo popolnoma osebne<\\/p>\",\"<p>Vsaka knjiga je narejena, v katerih je va\\u0161 otrok zvezda. Ustvarjamo popolnoma osebne<\\/p>\"]},\"quality_section\":{\"items\":[{\"badge\":\"<p>Prazen prostor na knji\\u017eni polici<\\/p>\",\"title\":\"<p>Prazen prostor na knji\\u017eni polici<\\/p>\",\"paragraphs\":[],\"image_url\":\"uploads\\/pages\\/1781501666_6a2f8ee2bdea5_IMG-5512-jpg-1-800x800.jpg\"},{\"badge\":\"<p>Brezkompromisna kakovost<\\/p>\",\"title\":\"<p>Brezkompromisna kakovost<\\/p>\",\"paragraphs\":[\"<p>Od tiska do vezave, sodelujemo z vrhunskimi izdelki<\\/p>\"],\"image_url\":\"uploads\\/pages\\/1781501436_6a2f8dfc05328_IMG-5512-jpg-1-800x800.jpg\"}]},\"steps\":{\"title\":\"Ustvarjanje \\u010darovnije v 3 korakih\",\"items\":[{\"step\":\"1\",\"title\":\"Prilagodite svojo knjigo\",\"description\":\"Vnesite ime svojega otroka, izberite njegov lik in izberite jezik zgodbe.\"},{\"step\":\"2\",\"title\":\"Tiskamo in ve\\u017eemo\",\"description\":\"Na\\u0161a produkcijska ekipa izdela va\\u0161o edinstveno knjigo z vrhunskimi materiali.\"},{\"step\":\"3\",\"title\":\"Dostavljeno na va\\u0161a vrata\",\"description\":\"Va\\u0161a personalizirana knjiga prispe lepo zapakirana in pripravljena za darilo.\"}]},\"difference\":{\"title\":\"Razlika v limetalih\",\"items\":[{\"title\":\"Oblikovanje, osredoto\\u010deno na otroka\",\"description\":\"Vsaka ilustracija in zgodba sta zasnovana tako, da va\\u0161ega otroka postavita v sredi\\u0161\\u010de pustolov\\u0161\\u010dine.\"},{\"title\":\"Takoj\\u0161nja darila\",\"description\":\"Naro\\u010dite \\u0161e danes in prejmite svojo knjigo v le nekaj delovnih dneh \\u2013 idealno za darilo v zadnjem trenutku.\"},{\"title\":\"Prava mala \\u010darovnija\",\"description\":\"Opazujte, kako se otroku zasvetijo o\\u010di, ko se vidi kot junak lastne zgodbe.\"},{\"title\":\"Zgrajeno za dolgo \\u017eivljenjsko dobo\",\"description\":\"Na\\u0161e knjige so narejene zato, da jih hranimo, ponovno beremo in prena\\u0161amo iz roda v rod kot dragocen dru\\u017einski spomin.\"}]},\"stats\":{\"number\":\"1 milijon+\",\"label\":\"Personalizirane zgodbe, ki prina\\u0161ajo \\u010darovnijo dru\\u017einam po vsem svetu.\",\"quote\":\"\\\"Preverjen rezultat, vsaka knjiga Lymetales je bila narejena ravno za mal\\u010dka.\\\"\"},\"gallery\":{\"title\":\"Iz na\\u0161ega studia, v njihove roke.\",\"images\":[\"uploads\\/pages\\/1781498579_6a2f82d3c81ab_about.webp\",\"uploads\\/pages\\/1781498579_6a2f82d3c836c_promotion2.webp\",\"uploads\\/pages\\/1781498579_6a2f82d3c8535_promotion1.webp\",\"uploads\\/pages\\/1781503084_6a2f946cd6bcf_IMG-5512-jpg-1-800x800.jpg\"]},\"cta\":{\"title\":\"Ustvarite nekaj, \\u010desar ne bodo nikoli pozabili.\",\"description\":\"Za\\u010dnite svojo osebno zgodbo \\u0161e danes.\",\"button_text\":\"Za\\u010dni zdaj\",\"button_url\":\"\\/personalised-gifts\",\"image_url\":\"uploads\\/pages\\/1781498579_6a2f82d3c88db_about.webp\"}}', 'Our Story - Lymetales', 'Learn about the origins of Lymetales and our mission to create personalised stories for children.', 1, '2026-06-14 08:46:26', '2026-06-18 02:31:11'),
(2, 'Privacy Policy', 'privacy-policy', '{\"header\":{\"badge\":\"PRAVNO\",\"title\":\"Pravilnik o zasebnosti\",\"last_updated\":\"April 2025\"},\"sections\":[{\"title\":\"\",\"body\":\"<p><strong>1. Kdo smo<\\/strong><\\/p><p>Lymetales je podjetje za personalizirane otro\\u0161ke knjige, ki ga upravlja Lymetales HQ, Inc. \\u010ce nas lahko kontaktirate, smo na tej strani objavili vse informacije, ki so na voljo za va\\u0161e osebne podatke. Lahko nas kontaktirate tudi na hello@lymetales.com.<\\/p><p><strong>2. Katere podatke zbiramo<\\/strong><\\/p><p>Zbiramo podatke, ki nam jih posredujete neposredno, va\\u0161e ime, e-po\\u0161tni naslov, naslov za dostavo, telefonsko \\u0161tevilko in podatke za personalizacijo (kot so ime otroka in izbrani liki). Zbiramo tudi podatke o pla\\u010dilu, vendar jih na\\u0161i ponudniki pla\\u010dilnih storitev varno obdelujejo in nikoli niso shranjeni na na\\u0161ih stre\\u017enikih.<\\/p><p>3. Kako uporabljamo va\\u0161e <strong>podatke<\\/strong><\\/p><p>Va\\u0161e podatke uporabljamo za obdelavo in izpolnjevanje va\\u0161ih naro\\u010dil, po\\u0161iljanje potrditev naro\\u010dil in posodobitev o po\\u0161iljanju, odgovarjanje na va\\u0161e zahteve za podporo ter izbolj\\u0161anje na\\u0161ih izdelkov in storitev. Z va\\u0161im soglasjem vam lahko po\\u0161iljamo tudi tr\\u017eenjska e-po\\u0161tna sporo\\u010dila o novih knjigah in promocijah.<\\/p><p>4. Podatki za personalizacijo<\\/p><p>Podatki, ki jih vnesete za personalizacijo knjig (kot so ime otroka, starost in zna\\u010dajske lastnosti), se uporabljajo izklju\\u010dno za izdelavo va\\u0161ega naro\\u010dila. Teh podatkov ne delimo s tretjimi osebami za namene tr\\u017eenja in jih izbri\\u0161emo iz aktivnih sistemov v 30 dneh po zaklju\\u010dku naro\\u010dila.<\\/p><p>5. Pi\\u0161kotki in sledenje<\\/p><p>Za delovanje na\\u0161ega spletnega mesta uporabljamo bistvene pi\\u0161kotke, za razumevanje, kako obiskovalci uporabljajo na\\u0161e spletno mesto, pa tudi neobvezne analiti\\u010dne pi\\u0161kotke (prek orodij, kot je Google Analytics). Nastavitve pi\\u0161kotkov lahko kadar koli upravljate prek na\\u0161e pasice za pi\\u0161kotke ali nastavitev brskalnika.<\\/p><p>6. Deljenje va\\u0161ih podatkov<\\/p><p>Va\\u0161e podatke delimo le z zaupanja vrednimi ponudniki storitev, ki nam pomagajo pri poslovanju \\u2013 vklju\\u010dno z na\\u0161imi partnerji za tiskanje in izpolnjevanje naro\\u010dil, ponudniki pla\\u010dil (Stripe, PayPal) in dostavnimi slu\\u017ebami. Ti partnerji so pogodbeno zavezani k varovanju va\\u0161ih podatkov in njihovi uporabi le za storitve, ki nam jih zagotavljajo.<\\/p><p>7. Hramba podatkov<\\/p><p>Podatke o va\\u0161em naro\\u010dilu in ra\\u010dunu hranimo do 5 let za pravne in ra\\u010dunovodske namene. Predhodni izbris lahko zahtevate tako, da nas kontaktirate.<\\/p><p>8. Va\\u0161e pravice<\\/p><p>Odvisno od va\\u0161e lokacije imate morda pravico do dostopa do svojih osebnih podatkov, njihovega popravka, brisanja ali izvoza ter pravico do ugovora ali omejitve dolo\\u010dene obdelave. Za uveljavljanje katere koli od teh pravic nas kontaktirajte na hello@lymetales.com in odgovorili vam bomo v 30 dneh.<\\/p><p>9. Zasebnost otrok<\\/p><p>Na\\u0161e spletno mesto je namenjeno odraslim, ki kupujejo od otrok. Zavestno ne zbiramo osebnih podatkov neposredno od otrok, mlaj\\u0161ih od 13 let. Podrobnosti o osebnih podatkih o otroku (kot je njegovo ime) posreduje odrasla oseba, ki kupuje, in se uporabljajo izklju\\u010dno za izdelavo naro\\u010denega izdelka.<\\/p><p>10. Varnost<\\/p><p>Za za\\u0161\\u010dito podatkov med prenosom uporabljamo industrijske standarde \\u0161ifriranja (TLS\\/SSL) in ustrezne tehni\\u010dne in organizacijske ukrepe za za\\u0161\\u010dito va\\u0161ih podatkov. V malo verjetnem primeru kr\\u0161itve podatkov, ki bi vplivala na va\\u0161e pravice, bomo o tem obvestili vas in pristojne organe, kot to zahteva zakonodaja.<\\/p><p>11. Spremembe te politike<\\/p><p>To politiko zasebnosti lahko ob\\u010dasno posodobimo. Ko bomo to storili, bomo na vrhu strani navedli datum \\u00bbZadnja posodobitev\\u00ab. O pomembnih spremembah vas bomo obvestili po e-po\\u0161ti ali prek obvestila na na\\u0161i spletni strani.<\\/p><p>12. Kontakt<\\/p><p>Za vsa vpra\\u0161anja ali zahteve v zvezi z zasebnostjo nas kontaktirajte na hello@lymetales.com. Z veseljem vam bomo pomagali in si prizadevali odgovoriti v enem delovnem dnevu.<\\/p>\"}]}', 'Privacy Policy - Lymetales', 'Read how Lymetales handles your personal data and privacy.', 1, '2026-06-14 08:46:26', '2026-06-18 02:32:32'),
(3, 'Terms of Service', 'terms-and-conditions', '{\"header\":{\"badge\":\"Pravno\",\"title\":\"Pogoji storitve\",\"last_updated\":\"April 2025\"},\"sections\":[{\"title\":\"\",\"body\":\"<p><strong>1. Dobrodo\\u0161li<\\/strong><\\/p><p>Z uporabo Lymetales in\\/ali oddajo naro\\u010dila se strinjate s temi pogoji. Namenjeni so temu, da je nakupovanje pri nas preprosto in po\\u0161teno.<\\/p><p><strong>2. Naro\\u010dila<\\/strong><\\/p><p>Vsa naro\\u010dila so odvisna od razpolo\\u017eljivosti in potrditve. Pridr\\u017eujemo si pravico, da zavrnemo ali prekli\\u010demo katero koli naro\\u010dilo, na primer, \\u010de je zahteva za personalizacijo nezakonita ali <strong>vsebuje \\u017ealjivo vsebino<\\/strong>.<\\/p><p>3. Personalizacija<\\/p><p>Odgovorni ste za to\\u010dnost podatkov o personalizaciji (imena, \\u010drkovanje, zna\\u010dajske lastnosti). Prosimo, da natan\\u010dno pregledate svoj predogled \\u2013 ko se za\\u010dne proizvodnja (v 2 urah po naro\\u010dilu), sprememb ni ve\\u010d mogo\\u010de.<\\/p><p>4. Cene in pla\\u010dilo<\\/p><p>Cene so prikazane v evrih in vklju\\u010dujejo veljavni DDV. Pla\\u010dilo se izvede ob zaklju\\u010dku nakupa prek Stripe, PayPal, Apple Pay ali Google Pay. Va\\u0161e naro\\u010dilo je potrjeno, ko je pla\\u010dilo uspe\\u0161no.<\\/p><p>5. Dostava<\\/p><p>Prizadevamo si, da odpremo v 1\\u20132 delovnih dneh. Dobavni roki so odvisni od va\\u0161ega namembnega kraja in izbranega prevoznika.<\\/p><p><strong>6. Vra\\u010dila<\\/strong><\\/p><p>Ker je vsaka knjiga edinstveno personalizirana, ne moremo sprejeti vra\\u010dil zaradi spremembe mnenja. \\u010ce je va\\u0161e naro\\u010dilo po\\u0161kodovano ali vsebuje tiskarsko napako, ki smo jo povzro\\u010dili mi, nas kontaktirajte v 14 dneh za brezpla\\u010dno zamenjavo.<\\/p><p>7. Intelektualna lastnina<\\/p><p>Vse zgodbe, ilustracije in dizajni so last podjetja Lymetales ali na\\u0161ih partnerjev. Na\\u0161e vsebine ne smete reproducirati, preprodajati ali distribuirati brez pisnega dovoljenja.<\\/p><p>8. Omejitev odgovornosti<\\/p><p>V najve\\u010djem obsegu, ki ga dovoljuje zakonodaja, Lymetales ne odgovarja za posredno ali posledi\\u010dno \\u0161kodo, ki izhaja iz va\\u0161e uporabe spletnega mesta ali na\\u0161ih izdelkov.<\\/p><p>9. Veljavna zakonodaja<\\/p><p>Te pogoje urejajo zakoni Portugalske. Vsi spori bodo re\\u0161eni na sodi\\u0161\\u010dih v Lizboni, brez poseganja v obvezne pravice do varstva potro\\u0161nikov.<\\/p><p>10. Kontakt<\\/p><p>Imate vpra\\u0161anja? Pi\\u0161ite na hello@lymetales.com. Z veseljem vam bomo pomagali in obi\\u010dajno odgovorimo v enem delovnem dnevu.<\\/p>\"}]}', 'Terms of Service - Lymetales', 'Read the terms and conditions for using Lymetales services.', 1, '2026-06-14 08:46:26', '2026-06-18 02:33:48'),
(4, 'Questions & Answers', 'faq', '{\"header\":{\"badge\":\"POGOSTA VPRA\\u0160ANJA\",\"title\":\"Vpra\\u0161anja, odgovori\",\"subtitle\":\"Vse, kar morate vedeti o ustvarjanju, naro\\u010danju in podarjanju knjige Lymetales.\"},\"categories\":[{\"name\":\"Personalizacija\",\"questions\":[{\"question\":\"Kako deluje personalizacija?\",\"answer\":\"<p>Med postopkom naro\\u010danja vnesite ime svojega otroka in izberite njegov lik. Na\\u0161 sistem vplete te podrobnosti v vsako stran zgodbe.<\\/p>\"},{\"question\":\"Ali lahko po naro\\u010dilu uredim svojo personalizacijo?\",\"answer\":\"<p>Spremembe so mo\\u017ene v roku 2 ur od oddaje naro\\u010dila. Po tem se za\\u010dne proizvodnja in spremembe niso ve\\u010d mogo\\u010de.<\\/p>\"},{\"question\":\"Je zgodba prepisana z imenom mojega otroka?\",\"answer\":\"<p>Ja! Otrokovo ime je vtkano v pripoved, ne le na naslovnico. Vsaka omemba junaka v zgodbi uporablja ime, ki ga navedete.<\\/p>\"}]},{\"name\":\"Dostava in po\\u0161iljanje\",\"questions\":[{\"question\":\"How long does it take?\",\"answer\":\"<p>Izdelamo in odpo\\u0161ljemo v 1\\u20132 delovnih dneh. Dostava nato traja 2\\u20135 delovnih dni za Evropo in 5\\u201310 dni za preostali svet.<\\/p>\"},{\"question\":\"Ali po\\u0161iljate mednarodno?\",\"answer\":\"<p>Da! Dostavljamo po vsem svetu. Stro\\u0161ki in \\u010dasi po\\u0161iljanja se razlikujejo glede na destinacijo in so prikazani ob zaklju\\u010dku nakupa.<\\/p>\"},{\"question\":\"Je dostava brezpla\\u010dna?\",\"answer\":\"<p>Za naro\\u010dila nad 60 \\u20ac ponujamo brezpla\\u010dno po\\u0161tnino. Sicer se stro\\u0161ki po\\u0161tnine izra\\u010dunajo ob zaklju\\u010dku nakupa glede na va\\u0161o lokacijo.<\\/p>\"}]},{\"name\":\"Returns & quality\",\"questions\":[{\"question\":\"Vra\\u010dila in kakovost\",\"answer\":\"<p>Ker je vsaka knjiga izdelana unikatno, ne moremo sprejeti vra\\u010dil zaradi spremembe mnenja. \\u010ce pride do napake ali tiskarske napake, jo bomo brezpla\\u010dno zamenjali \\u2013 samo kontaktirajte nas v 14 dneh.<\\/p>\"},{\"question\":\"Kaj pa, \\u010de pride do tipkarske napake ali napake?\",\"answer\":\"<p>\\u010ce je tipkarska napaka nastala po na\\u0161i krivdi (npr. ime je bilo natisnjeno druga\\u010de kot vi), vam bomo brezpla\\u010dno poslali nadomestno besedilo. \\u010ce tipkarska napaka izvira iz podatkov, ki ste jih posredovali, vam lahko ponudimo ponatis s popustom.<\\/p>\"}]},{\"name\":\"Darila in boni\",\"questions\":[{\"question\":\"Ali ponujate darilne bone?\",\"answer\":\"<p>Da! Na na\\u0161i spletni strani lahko kupite digitalne darilne kartice v razli\\u010dnih zneskih. Dostavljene so takoj po e-po\\u0161ti.<\\/p>\"},{\"question\":\"Ali lahko vklju\\u010dim darilno sporo\\u010dilo?\",\"answer\":\"<p>Seveda. Med blagajno lahko dodate osebno darilno sporo\\u010dilo, ki bo natisnjeno na kartici, ki bo prilo\\u017eena va\\u0161emu naro\\u010dilu.<\\/p>\"}]}],\"cta\":{\"title\":\"\\u0160e vedno imate vpra\\u0161anja?\",\"subtitle\":\"Na\\u0161a ekipa obi\\u010dajno odgovori v nekaj urah.\",\"button_text\":\"KONTAKTIRAJTE NAS\",\"button_url\":\"\\/contact\"}}', 'FAQ - Lymetales', 'Everything you need to know about creating, ordering, and gifting a Lymetales book.', 1, '2026-06-14 08:46:26', '2026-06-18 02:39:41'),
(5, 'Contact With Us', 'contact-us', '{\"header\":{\"badge\":\"RADI BI SLI\\u0160ALI VA\\u0160E MNENJE\",\"title\":\"Kontaktirajte nas\",\"subtitle\":\"Imate vpra\\u0161anja o knjigi, naro\\u010dilu ali zahtevi po meri? Na\\u0161a mala ekipa prebere vsako sporo\\u010dilo.\"},\"contact_info\":[{\"type\":\"email\",\"label\":\"E-PO\\u0160TA\",\"value\":\"hello@lymetales.com\",\"note\":\"Odgovorimo v 24 urah\",\"icon\":\"email\"},{\"type\":\"chat\",\"label\":\"KLEPET V \\u017dIVO\",\"value\":\"Na voljo v aplikaciji\",\"note\":\"pon\\u2013pet, 9\\u201318 po srednjeevropskem \\u010dasu\",\"icon\":\"chat\"},{\"type\":\"production\",\"label\":\"PRODUKCIJA\",\"value\":\"1\\u20132 delovna dneva\",\"note\":\"Nato poslano po vsem svetu\",\"icon\":\"clock\"}],\"form\":{\"title\":\"Po\\u0161ljite nam sporo\\u010dilo\",\"subtitle\":\"Na vsako sporo\\u010dilo odgovorimo \\u2013 obi\\u010dajno v enem delovnem dnevu.\",\"submit_button_text\":\"Po\\u0161lji sporo\\u010dilo\",\"privacy_note\":\"Z oddajo se strinjate z na\\u0161o politiko zasebnosti.\"}}', 'Contact Us - Lymetales', 'Get in touch with the Lymetales team.', 1, '2026-06-14 08:46:26', '2026-06-18 02:42:26');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `name_text` varchar(255) DEFAULT NULL,
  `name_font_family` varchar(255) DEFAULT 'PetitCochon',
  `name_top` varchar(255) DEFAULT '2%',
  `name_color` varchar(255) DEFAULT '#e591ae',
  `name_font_size` varchar(255) DEFAULT '88px',
  `name_right` varchar(255) DEFAULT '50%',
  `price` decimal(10,2) NOT NULL,
  `pages` int(11) DEFAULT NULL,
  `age_range` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `characters` varchar(255) DEFAULT NULL,
  `cover_type` varchar(255) DEFAULT NULL,
  `print_type` varchar(255) DEFAULT NULL,
  `paper_type` varchar(255) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 5.0,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `is_bestseller` tinyint(1) NOT NULL DEFAULT 0,
  `is_recommended` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `featured_image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `title`, `slug`, `description`, `name_text`, `name_font_family`, `name_top`, `name_color`, `name_font_size`, `name_right`, `price`, `pages`, `age_range`, `size`, `characters`, `cover_type`, `print_type`, `paper_type`, `rating`, `reviews_count`, `is_bestseller`, `is_recommended`, `status`, `type`, `domain`, `featured_image_id`, `site_category_id`, `site_subcategory_id`, `created_at`, `updated_at`) VALUES
(4, NULL, NULL, 'Božičkov pomočnik', 'bozickov-pomocnik', 'Naj tvoja mala iskrica v očeh nikoli ne izgine. Naj bo tvoj svet vedno tako pisan kot ga vidiš sedaj in naj ti ta knjigica obudi lepe spomine.', NULL, 'PetitCochon', '2%', '#e591ae', '88px', '50%', 715.00, 75, '3–5 let', '21 cm x 29,7 cm', 'Prilagodljiv', 'Premium Hardcover', 'visoko', 'visoko', 3.0, 26, 1, 1, 1, 'newborn', 'domain1', 18, 6, NULL, '2026-06-14 09:49:44', '2026-06-18 00:28:57'),
(6, NULL, NULL, 'Izgubljeni samorog', 'izgubljeni-samorog', 'V čarobnem gozdu se je izgubil mali samorog. Pridruži se čudoviti pustolovščini, kjer tvoja deklica pomaga samorogu najti pot domov in pri tem odkriva pomen prijaznosti in medsebojne pomoči.', NULL, 'PetitCochon', '2%', '#e591ae', '88px', '50%', 26.50, 32, '3–7 let', '21 cm x 29,7 cm', 'Prilagodljiv', 'Premium Hardcover', 'Visokokakovostni tisk', 'Sijajni papir', 4.8, 89, 1, 1, 1, 'kids', 'domain1', 60, 4, NULL, '2026-06-14 19:46:24', '2026-06-18 01:20:42'),
(7, NULL, NULL, 'Skrivnostni podvodni svet', 'skrivnostni-podvodni-svet', 'Potopi se globoko v ocean, spoznaj govoreče ribice, modre kite in odkrij skriti potopljeni zaklad. Knjiga spodbuja otroško radovednost in ljubezen do narave ter oceanov.', NULL, 'PetitCochon', '2%', '#e591ae', '88px', '50%', 22.00, 24, '2–6 let', '26 cm x 26 cm', 'Prilagodljiv', 'Premium Hardcover', 'Eko-prijazne barve', 'Debel risalni papir', 5.0, 45, 1, 1, 1, 'adult', 'domain1', 53, 4, NULL, '2026-06-14 19:46:24', '2026-06-18 01:07:03'),
(9, NULL, NULL, 'Čudežni zajček', 'cudezni-zajcek', 'Prvi šolski dan je lahko poln vznemirjenja in skrbi. V tej čudoviti zgodbi tvoj otrok pomaga medvedku premagati strah pred šolo, spoznati nove sošolce in odkriti, kako zabavno je učenje.', NULL, 'PetitCochon', '2%', '#e591ae', '88px', '50%', 19.99, 30, '5–8 let', '21 cm x 21 cm', 'Prilagodljiv', 'Premium Hardcover', 'Eko-prijazne barve', 'Premium mat papir', 4.9, 120, 1, 1, 1, 'kids', 'domain1', 36, 6, NULL, '2026-06-14 19:46:24', '2026-06-15 22:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `product_book_images`
--

CREATE TABLE `product_book_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_book_images`
--

INSERT INTO `product_book_images` (`id`, `product_id`, `image_path`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 4, '/storage/products/book_6a310642e2ab16.10391745_7AVie.jpg', 0, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(2, 4, '/storage/products/book_6a310642e37017.93170080_4pQYL.webp', 1, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(3, 4, '/storage/products/book_6a310642e3b8f3.81170554_FGKG0.webp', 2, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(8, 9, '/storage/products/book_6a335cededb273.13259901_kzca8.png', 0, '2026-06-18 00:50:21', '2026-06-18 00:50:21'),
(9, 9, '/storage/products/book_6a335cedf15604.71022662_A6YYk.png', 1, '2026-06-18 00:50:21', '2026-06-18 00:50:21'),
(10, 9, '/storage/products/book_6a335cedf28ab9.08727773_Zudqn.png', 2, '2026-06-18 00:50:21', '2026-06-18 00:50:21'),
(11, 9, '/storage/products/book_6a335cedf40478.62768100_oDhMr.png', 3, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(12, 9, '/storage/products/book_6a335cee00d7f8.80768788_gDXTM.png', 4, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(13, 9, '/storage/products/book_6a335cee01d629.37975627_XZOom.png', 5, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(14, 9, '/storage/products/book_6a335cee0495e5.33319482_WS6tY.png', 6, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(15, 9, '/storage/products/book_6a335cee063182.73685182_2KCNQ.png', 7, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(16, 7, '/storage/products/book_6a335d4744ba19.69828800_HDRD2.png', 0, '2026-06-18 00:51:51', '2026-06-18 00:51:51'),
(17, 7, '/storage/products/book_6a3360d724d696.50422245_kpnS3.png', 1, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(18, 7, '/storage/products/book_6a3360d727fd21.58944669_Wg8t3.png', 2, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(19, 7, '/storage/products/book_6a3360d72a38e5.86236748_wuBOJ.png', 3, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(20, 7, '/storage/products/book_6a3360d72b3057.69522632_u64Vv.png', 4, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(21, 7, '/storage/products/book_6a3360d72c0da8.66336543_S9SVW.png', 5, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(22, 7, '/storage/products/book_6a3360d72ce323.43313355_kdOla.png', 6, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(23, 6, '/storage/products/book_6a3363771d96f2.34799829_UXipE.png', 0, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(24, 6, '/storage/products/book_6a3363771ec599.84202141_346Av.png', 1, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(25, 6, '/storage/products/book_6a33637720a942.86619729_ycsiy.png', 2, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(26, 6, '/storage/products/book_6a336377218905.90935093_Z8Ev9.png', 3, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(27, 4, '/storage/products/book_6a33a8ecf18a90.29700306_iLIIp.png', 3, '2026-06-18 06:14:36', '2026-06-18 06:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_category_images`
--

CREATE TABLE `product_category_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `option_type` varchar(255) NOT NULL DEFAULT 'box',
  `option_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_customization_options`
--

CREATE TABLE `product_customization_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `step_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('dropdown','box','color') NOT NULL DEFAULT 'dropdown',
  `color_value` varchar(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_customization_options`
--

INSERT INTO `product_customization_options` (`id`, `step_id`, `name`, `type`, `color_value`, `image_path`, `is_default`, `sort_order`, `created_at`, `updated_at`) VALUES
(28, 18, 'Fant', 'dropdown', NULL, '/storage/products/copt_6a2f5db514f6c8.19890487_xrNuL.png', 0, 0, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(29, 18, 'Dekle', 'dropdown', NULL, '/storage/products/copt_6a2f5db51661d5.79786699_RnfGr.png', 0, 1, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(30, 19, 'Dekle', 'dropdown', NULL, '/storage/products/copt_6a3360d72f4852.36172175_uOfTi.png', 0, 0, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(31, 19, 'Fant', 'dropdown', NULL, '/storage/products/copt_6a3360d7394ea0.85068310_8hEOE.png', 0, 1, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(34, 21, 'Dekle', 'dropdown', NULL, '/storage/products/copt_6a3363772e7e82.40155655_t6juc.png', 0, 0, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(35, 21, 'Fant', 'dropdown', NULL, '/storage/products/copt_6a3363773d81a4.33593431_dJO34.png', 0, 1, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(36, 22, 'Dekle', 'dropdown', NULL, '/storage/products/copt_6a2ed72d926359.47772855_XtdQp.png', 0, 0, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(37, 22, 'Fant', 'dropdown', NULL, '/storage/products/copt_6a2ed72d93b4c4.56929290_rFFEE.png', 0, 1, '2026-06-18 06:14:37', '2026-06-18 06:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_customization_steps`
--

CREATE TABLE `product_customization_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('dropdown','box','color') NOT NULL DEFAULT 'dropdown',
  `color_value` varchar(20) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_customization_steps`
--

INSERT INTO `product_customization_steps` (`id`, `product_id`, `name`, `type`, `color_value`, `sort_order`, `created_at`, `updated_at`) VALUES
(18, 9, 'Spol', 'dropdown', NULL, 0, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(19, 7, 'Spol', 'dropdown', NULL, 0, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(21, 6, 'Spol', 'dropdown', NULL, 0, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(22, 4, 'Spol', 'box', NULL, 0, '2026-06-18 06:14:37', '2026-06-18 06:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_customization_suboptions`
--

CREATE TABLE `product_customization_suboptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `substep_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('dropdown','box','color') NOT NULL DEFAULT 'dropdown',
  `color_value` varchar(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_customization_suboptions`
--

INSERT INTO `product_customization_suboptions` (`id`, `substep_id`, `name`, `type`, `color_value`, `image_path`, `is_default`, `sort_order`, `created_at`, `updated_at`) VALUES
(7, 9, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 4, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(8, 9, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 5, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(9, 9, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 6, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(13, 11, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 3, '2026-06-14 10:43:29', '2026-06-14 10:43:29'),
(14, 11, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 4, '2026-06-14 10:43:29', '2026-06-14 10:43:29'),
(15, 11, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 5, '2026-06-14 10:43:29', '2026-06-14 10:43:29'),
(19, 13, '#b34b1b', 'color', '#b34b1b', '/storage/products/csub_6a2f5db516c0e3.16795230_zdW3z.png', 0, 3, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(20, 13, '#e2c975', 'color', '#e2c975', '/storage/products/csub_6a2f5db5170d09.91531799_AQYbP.png', 0, 4, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(21, 13, '#160f0c', 'color', '#160f0c', '/storage/products/csub_6a2f5db5174f61.61186345_ysuDQ.png', 0, 5, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(25, 15, '#b34b1b', 'color', '#b34b1b', '/storage/products/csub_6a2f5db516c0e3.16795230_zdW3z.png', 0, 3, '2026-06-14 20:05:02', '2026-06-14 20:05:02'),
(26, 15, '#e2c975', 'color', '#e2c975', '/storage/products/csub_6a2f5db5170d09.91531799_AQYbP.png', 0, 4, '2026-06-14 20:05:02', '2026-06-14 20:05:02'),
(27, 15, '#160f0c', 'color', '#160f0c', '/storage/products/csub_6a2f5db5174f61.61186345_ysuDQ.png', 0, 5, '2026-06-14 20:05:02', '2026-06-14 20:05:02'),
(28, 16, '#221a17', 'color', '#221a17', '/storage/products/csub_6a2f5db5158681.16641302_kJFwg.png', 0, 0, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(29, 16, '#e1c774', 'color', '#e1c774', '/storage/products/csub_6a2f5db515e015.83528678_3TBS6.png', 0, 1, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(30, 16, '#b34c1c', 'color', '#b34c1c', '/storage/products/csub_6a2f5db5161cc2.68356559_GFXEC.png', 0, 2, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(31, 17, '#b34b1b', 'color', '#b34b1b', '/storage/products/csub_6a2f5db516c0e3.16795230_zdW3z.png', 0, 3, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(32, 17, '#e2c975', 'color', '#e2c975', '/storage/products/csub_6a2f5db5170d09.91531799_AQYbP.png', 0, 4, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(33, 17, '#160f0c', 'color', '#160f0c', '/storage/products/csub_6a2f5db5174f61.61186345_ysuDQ.png', 0, 5, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(37, 19, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 3, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(38, 19, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 4, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(39, 19, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 5, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(43, 21, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 3, '2026-06-16 19:57:10', '2026-06-16 19:57:10'),
(44, 21, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 4, '2026-06-16 19:57:10', '2026-06-16 19:57:10'),
(45, 21, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 5, '2026-06-16 19:57:10', '2026-06-16 19:57:10'),
(46, 22, '#e2c875', 'color', '#e2c875', '/storage/products/csub_6a2ed72d92bb89.04362601_MVyfo.png', 0, 0, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(47, 22, '#b34b1a', 'color', '#b34b1a', '/storage/products/csub_6a2ed72d9312d6.33094222_9QLck.png', 0, 1, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(48, 22, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d936048.07033585_B8nAH.png', 0, 2, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(49, 23, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 3, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(50, 23, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 4, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(51, 23, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 5, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(52, 24, '#e2c875', 'color', '#e2c875', '/storage/products/csub_6a2ed72d92bb89.04362601_MVyfo.png', 0, 0, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(53, 24, '#b34b1a', 'color', '#b34b1a', '/storage/products/csub_6a2ed72d9312d6.33094222_9QLck.png', 0, 1, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(54, 24, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d936048.07033585_B8nAH.png', 0, 2, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(55, 25, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3357a2e4cbb2.66571253_yX5Ty.png', 0, 6, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(56, 25, '#713028', 'color', '#713028', '/storage/products/csub_6a3357a2e61615.11778698_RsN2d.png', 0, 7, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(57, 25, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3357a2e6bc02.87054081_uBt3q.png', 0, 8, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(58, 26, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 3, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(59, 26, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 4, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(60, 26, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 5, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(61, 27, '#667eb7', 'color', '#667eb7', NULL, 0, 13, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(62, 27, '#713028', 'color', '#713028', NULL, 0, 14, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(63, 27, '#8abb7f', 'color', '#8abb7f', NULL, 0, 15, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(64, 28, '#e2c875', 'color', '#e2c875', '/storage/products/csub_6a2ed72d92bb89.04362601_MVyfo.png', 0, 0, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(65, 28, '#b34b1a', 'color', '#b34b1a', '/storage/products/csub_6a2ed72d9312d6.33094222_9QLck.png', 0, 1, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(66, 28, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d936048.07033585_B8nAH.png', 0, 2, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(67, 29, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3357a2e4cbb2.66571253_yX5Ty.png', 0, 3, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(68, 29, '#713028', 'color', '#713028', '/storage/products/csub_6a3357a2e61615.11778698_RsN2d.png', 0, 4, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(69, 29, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3357a2e6bc02.87054081_uBt3q.png', 0, 5, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(70, 30, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 6, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(71, 30, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 7, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(72, 30, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 8, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(73, 31, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3357e9cb7135.23772939_B2zZt.png', 0, 9, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(74, 31, '#713028', 'color', '#713028', '/storage/products/csub_6a3357e9cbf0c8.69404446_B7NaV.png', 0, 10, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(75, 31, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3357e9cc54c3.83552299_AuDI2.png', 0, 11, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(76, 32, '#221a17', 'color', '#221a17', '/storage/products/csub_6a2f5db5158681.16641302_kJFwg.png', 0, 0, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(77, 32, '#e1c774', 'color', '#e1c774', '/storage/products/csub_6a2f5db515e015.83528678_3TBS6.png', 0, 1, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(78, 32, '#b34c1c', 'color', '#b34c1c', '/storage/products/csub_6a2f5db5161cc2.68356559_GFXEC.png', 0, 2, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(79, 33, '#667eb7', 'color', '#667eb7', NULL, 0, 9, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(80, 33, '#713028', 'color', '#713028', NULL, 0, 10, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(81, 33, '#8abb7f', 'color', '#8abb7f', NULL, 0, 11, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(82, 34, '#b34b1b', 'color', '#b34b1b', '/storage/products/csub_6a2f5db516c0e3.16795230_zdW3z.png', 0, 3, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(83, 34, '#e2c975', 'color', '#e2c975', '/storage/products/csub_6a2f5db5170d09.91531799_AQYbP.png', 0, 4, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(84, 34, '#160f0c', 'color', '#160f0c', '/storage/products/csub_6a2f5db5174f61.61186345_ysuDQ.png', 0, 5, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(85, 35, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a335cee0bd533.96542023_lkilZ.png', 0, 12, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(86, 35, '#773830', 'color', '#773830', '/storage/products/csub_6a335cee0cea69.87645732_E1pHk.png', 0, 13, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(87, 35, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a335cee0d5688.28349365_47AEF.png', 0, 14, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(88, 36, '#e2c976', 'color', '#e2c976', '/storage/products/csub_6a3360d7329103.02134752_FYAJ4.png', 0, 0, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(89, 36, '#b44d1c', 'color', '#b44d1c', '/storage/products/csub_6a3360d73445f3.37815957_iPj9j.png', 0, 1, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(90, 36, '#160f0d', 'color', '#160f0d', '/storage/products/csub_6a3360d7359039.31155796_pMEDL.png', 0, 2, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(91, 37, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3360d736e659.92049934_jERjf.png', 0, 3, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(92, 37, '#713028', 'color', '#713028', '/storage/products/csub_6a3360d737ad87.37698595_kLyd7.png', 0, 4, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(93, 37, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3360d7386d38.92989062_cZQep.png', 0, 5, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(94, 38, '#b44e1d', 'color', '#b44e1d', '/storage/products/csub_6a3360d73bb868.74218957_TmdJ4.png', 0, 10, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(95, 38, '#211b17', 'color', '#211b17', '/storage/products/csub_6a3360d73cfbe1.83647402_TUXVW.png', 0, 11, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(96, 38, '#e1c774', 'color', '#e1c774', '/storage/products/csub_6a3360d73e2454.28639558_20IJn.png', 0, 12, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(97, 39, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3360d73f4dd9.46398051_tQBpI.png', 0, 13, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(98, 39, '#713028', 'color', '#713028', '/storage/products/csub_6a3360d7401ac6.24538076_S3hlO.png', 0, 14, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(99, 39, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3360d740bf56.72695552_fHIqG.png', 0, 15, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(100, 40, '#e3c672', 'color', '#e3c672', '/storage/products/csub_6a33637732c6d1.44492611_8N7Fa.png', 0, 0, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(101, 40, '#b34d1c', 'color', '#b34d1c', '/storage/products/csub_6a33637736cd48.77746859_vJSZo.png', 0, 1, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(102, 40, '#170f0c', 'color', '#170f0c', '/storage/products/csub_6a33637738bee5.63961778_ZKD7h.png', 0, 2, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(103, 41, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773a0f93.31465170_m3t1A.png', 0, 3, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(104, 41, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773b5063.91789618_zU2kS.png', 0, 4, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(105, 41, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773c7a14.43092445_RhQgl.png', 0, 5, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(106, 42, '#b44d1c', 'color', '#b44d1c', '/storage/products/csub_6a336377410a79.84212436_qwkWB.png', 0, 6, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(107, 42, '#211a17', 'color', '#211a17', '/storage/products/csub_6a336377433d17.14791471_4i6Wl.png', 0, 7, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(108, 42, '#e1c773', 'color', '#e1c773', '/storage/products/csub_6a33637744b953.50427692_sLTcP.png', 0, 8, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(109, 43, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a336377462371.51268004_qIcZ1.png', 0, 10, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(110, 43, '#713028', 'color', '#713028', '/storage/products/csub_6a336377476c55.91153966_P7umk.png', 0, 11, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(111, 43, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a336377483527.03985474_ujPCG.png', 0, 12, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(112, 44, '#e3c672', 'color', '#e3c672', '/storage/products/csub_6a33637732c6d1.44492611_8N7Fa.png', 0, 0, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(113, 44, '#b34d1c', 'color', '#b34d1c', '/storage/products/csub_6a33637736cd48.77746859_vJSZo.png', 0, 1, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(114, 44, '#170f0c', 'color', '#170f0c', '/storage/products/csub_6a33637738bee5.63961778_ZKD7h.png', 0, 2, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(115, 45, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773a0f93.31465170_m3t1A.png', 0, 3, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(116, 45, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773b5063.91789618_zU2kS.png', 0, 4, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(117, 45, '#000000', 'color', '#000000', '/storage/products/csub_6a3363773c7a14.43092445_RhQgl.png', 0, 5, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(118, 46, '#b44d1c', 'color', '#b44d1c', '/storage/products/csub_6a336377410a79.84212436_qwkWB.png', 0, 6, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(119, 46, '#211a17', 'color', '#211a17', '/storage/products/csub_6a336377433d17.14791471_4i6Wl.png', 0, 7, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(120, 46, '#e1c773', 'color', '#e1c773', '/storage/products/csub_6a33637744b953.50427692_sLTcP.png', 0, 8, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(121, 47, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a336377462371.51268004_qIcZ1.png', 0, 9, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(122, 47, '#713028', 'color', '#713028', '/storage/products/csub_6a336377476c55.91153966_P7umk.png', 0, 10, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(123, 47, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a336377483527.03985474_ujPCG.png', 0, 11, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(124, 48, '#b34c1b', 'color', '#b34c1b', '/storage/products/csub_6a33a8ed06ee94.07206594_YGoHH.png', 0, 0, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(125, 48, '#b34b1a', 'color', '#b34b1a', '/storage/products/csub_6a2ed72d9312d6.33094222_9QLck.png', 0, 1, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(126, 48, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d936048.07033585_B8nAH.png', 0, 2, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(127, 49, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3357a2e4cbb2.66571253_yX5Ty.png', 0, 3, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(128, 49, '#713028', 'color', '#713028', '/storage/products/csub_6a3357a2e61615.11778698_RsN2d.png', 0, 4, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(129, 49, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3357a2e6bc02.87054081_uBt3q.png', 0, 5, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(130, 50, '#b34e1e', 'color', '#b34e1e', '/storage/products/csub_6a2ed72d941362.16225770_pMEJt.png', 0, 6, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(131, 50, '#000000', 'color', '#000000', '/storage/products/csub_6a2ed72d9450c5.98517905_ot0jj.png', 0, 7, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(132, 50, '#e2c672', 'color', '#e2c672', '/storage/products/csub_6a2ed72d948a07.86125690_7AMja.png', 0, 8, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(133, 51, '#667eb7', 'color', '#667eb7', '/storage/products/csub_6a3357e9cb7135.23772939_B2zZt.png', 0, 9, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(134, 51, '#713028', 'color', '#713028', '/storage/products/csub_6a3357e9cbf0c8.69404446_B7NaV.png', 0, 10, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(135, 51, '#8abb7f', 'color', '#8abb7f', '/storage/products/csub_6a3357e9cc54c3.83552299_AuDI2.png', 0, 11, '2026-06-18 06:14:37', '2026-06-18 06:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_customization_substeps`
--

CREATE TABLE `product_customization_substeps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('dropdown','box','color') NOT NULL DEFAULT 'dropdown',
  `color_value` varchar(20) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_customization_substeps`
--

INSERT INTO `product_customization_substeps` (`id`, `option_id`, `name`, `type`, `color_value`, `sort_order`, `created_at`, `updated_at`) VALUES
(9, 9, 'Lasje', 'color', NULL, 1, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(11, 11, 'Lasje', 'color', NULL, 1, '2026-06-14 10:43:29', '2026-06-14 10:43:29'),
(13, 13, 'Lasje', 'color', NULL, 1, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(15, 15, 'Lasje', 'color', NULL, 1, '2026-06-14 20:05:02', '2026-06-14 20:05:02'),
(16, 16, 'Lasje', 'color', NULL, 0, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(17, 17, 'Lasje', 'color', NULL, 1, '2026-06-15 22:51:18', '2026-06-15 22:51:18'),
(19, 19, 'Lasje', 'color', NULL, 1, '2026-06-16 02:16:02', '2026-06-16 02:16:02'),
(21, 21, 'Lasje', 'color', NULL, 1, '2026-06-16 19:57:10', '2026-06-16 19:57:10'),
(22, 22, 'Lasje', 'color', NULL, 0, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(23, 23, 'Lasje', 'color', NULL, 1, '2026-06-16 19:57:25', '2026-06-16 19:57:25'),
(24, 24, 'Lasje', 'color', NULL, 0, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(25, 24, 'oko', 'color', NULL, 2, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(26, 25, 'Lasje', 'color', NULL, 1, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(27, 25, 'oko', 'color', NULL, 3, '2026-06-18 00:27:46', '2026-06-18 00:27:46'),
(28, 26, 'Lasje', 'color', NULL, 0, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(29, 26, 'oko', 'color', NULL, 1, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(30, 27, 'Lasje', 'color', NULL, 2, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(31, 27, 'oko', 'color', NULL, 3, '2026-06-18 00:28:57', '2026-06-18 00:28:57'),
(32, 28, 'Lasje', 'color', NULL, 0, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(33, 28, 'oko', 'color', NULL, 2, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(34, 29, 'Lasje', 'color', NULL, 1, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(35, 29, 'oko', 'color', NULL, 3, '2026-06-18 00:50:22', '2026-06-18 00:50:22'),
(36, 30, 'Lasje', 'color', NULL, 0, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(37, 30, 'oko', 'color', NULL, 1, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(38, 31, 'Lasje', 'color', NULL, 2, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(39, 31, 'oko', 'color', NULL, 3, '2026-06-18 01:07:03', '2026-06-18 01:07:03'),
(40, 32, 'Lasje', 'color', NULL, 0, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(41, 32, 'oko', 'color', NULL, 1, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(42, 33, 'Lasje', 'color', NULL, 2, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(43, 33, 'oko', 'color', NULL, 3, '2026-06-18 01:18:15', '2026-06-18 01:18:15'),
(44, 34, 'Lasje', 'color', NULL, 0, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(45, 34, 'oko', 'color', NULL, 1, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(46, 35, 'Lasje', 'color', NULL, 2, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(47, 35, 'oko', 'color', NULL, 3, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(48, 36, 'Lasje', 'color', NULL, 0, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(49, 36, 'oko', 'color', NULL, 1, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(50, 37, 'Lasje', 'color', NULL, 2, '2026-06-18 06:14:37', '2026-06-18 06:14:37'),
(51, 37, 'oko', 'color', NULL, 3, '2026-06-18 06:14:37', '2026-06-18 06:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_main`, `sort_order`, `created_at`, `updated_at`) VALUES
(7, 4, '/storage/products/img_6a2ed72d8f5347.10476265_1p6TV.png', 1, 0, '2026-06-14 09:49:44', '2026-06-14 10:30:37'),
(15, 4, '/storage/products/gal_6a2ed72d900a25.48410383_bCfZT.png', 0, 1, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(16, 4, '/storage/products/gal_6a2ed72d906a47.59624735_J5RUg.png', 0, 2, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(17, 4, '/storage/products/gal_6a2ed72d909a33.43151762_10PZC.jpeg', 0, 3, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(18, 4, '/storage/products/gal_6a2ed72d90c6b6.00247571_p0M8L.png', 0, 4, '2026-06-14 10:30:37', '2026-06-14 10:30:37'),
(31, 9, '/storage/products/img_6a2f5db5121411.33966791_WsRS3.png', 1, 0, '2026-06-14 19:46:24', '2026-06-14 20:04:37'),
(34, 9, '/storage/products/gal_6a2f5db5132a26.04041477_PNTMK.png', 0, 1, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(35, 9, '/storage/products/gal_6a2f5db5138229.18933460_kJXpL.png', 0, 2, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(36, 9, '/storage/products/gal_6a2f5db513d9a5.47646565_fPHYV.jpeg', 0, 3, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(37, 9, '/storage/products/gal_6a2f5db5141db4.56911502_138gF.png', 0, 4, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(38, 5, 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=400', 1, 0, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(39, 5, 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?auto=format&fit=crop&q=80&w=400', 0, 1, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(40, 5, 'https://images.unsplash.com/photo-1518364538800-6bcb3f25da49?auto=format&fit=crop&q=80&w=400', 0, 2, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(41, 6, '/storage/products/img_6a33637722f523.21235220_C0g41.png', 1, 0, '2026-06-14 20:18:44', '2026-06-18 01:18:15'),
(44, 7, '/storage/products/img_6a2f629d10df49.42965710_JFkqq.png', 1, 0, '2026-06-14 20:18:44', '2026-06-14 20:25:33'),
(47, 11, 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&q=80&w=400', 1, 0, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(48, 11, 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?auto=format&fit=crop&q=80&w=400', 0, 1, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(49, 11, 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&q=80&w=400', 0, 2, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(50, 12, 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=400', 1, 0, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(51, 12, 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=400', 0, 1, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(52, 12, 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&q=80&w=400', 0, 2, '2026-06-14 20:18:44', '2026-06-14 20:18:44'),
(53, 7, '/storage/products/gal_6a2f629d115497.44981874_XTH8P.jpg', 0, 1, '2026-06-14 20:25:33', '2026-06-14 20:25:33'),
(54, 7, '/storage/products/gal_6a2f629d11b680.82800198_nHDmY.png', 0, 2, '2026-06-14 20:25:33', '2026-06-14 20:25:33'),
(55, 7, '/storage/products/gal_6a2f629d120141.06228052_MZqHF.jpeg', 0, 3, '2026-06-14 20:25:33', '2026-06-14 20:25:33'),
(56, 7, '/storage/products/gal_6a2f629d1244a9.13472314_d7y3b.png', 0, 4, '2026-06-14 20:25:33', '2026-06-14 20:25:33'),
(57, 6, '/storage/products/gal_6a33640a4b6979.56875805_7VibE.png', 0, 1, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(58, 6, '/storage/products/gal_6a33640a554132.91485996_on8oB.png', 0, 2, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(59, 6, '/storage/products/gal_6a33640a5fe8f2.90704075_gaV1q.jpeg', 0, 3, '2026-06-18 01:20:42', '2026-06-18 01:20:42'),
(60, 6, '/storage/products/gal_6a33640a64f390.99651642_uK5Cy.png', 0, 4, '2026-06-18 01:20:42', '2026-06-18 01:20:42');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `reviewer_email` varchar(255) NOT NULL,
  `reviewer_location` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `reviewer_name`, `title`, `reviewer_email`, `reviewer_location`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(4, 4, 'John 2', 'Great product!', 'john@example.com', 'Dhaka', 5.0, 'Really loved this product.', 1, '2026-06-16 01:23:27', '2026-06-16 01:23:27'),
(5, 7, 'John 2', 'Great product!', 'john@example.com', 'Dhaka', 5.0, 'Really loved this product.', 1, '2026-06-16 01:23:32', '2026-06-16 01:23:32'),
(6, 9, 'John 2', 'Great product!', 'john@example.com', 'Dhaka', 5.0, 'Really loved this product.', 1, '2026-06-16 01:23:37', '2026-06-16 01:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_special_sections`
--

CREATE TABLE `product_special_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_special_sections`
--

INSERT INTO `product_special_sections` (`id`, `product_id`, `subtitle`, `title`, `description`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 4, 'Izvrševanje Ex cupidatat', 'Naj tvoja mala iskrica', 'Naj tvoja mala iskrica v očeh nikoli ne izgine. Naj bo tvoj svet vedno tako pisan kot ga vidiš sedaj in naj ti ta knjigica obudi lepe spomine.', '/storage/products/special_6a2ed72d8d3634.04639342_j7qxh.png', 0, '2026-06-14 09:49:44', '2026-06-14 10:43:29'),
(2, 9, 'Prijateljstvo, ki ga je težko najti in ni samoumevno. Čarobna zgodba, ki bo očarala tako malčke kot starše.', 'Kakšna je zgodba?', 'Prijateljstvo, ki ga je težko najti in ni samoumevno. Čarobna zgodba, ki bo navdušila tako malčke kot starše. Malčki se na poti odraščanja soočajo z neštetimi izzivi, eden izmed njih je iskanje prijateljev. Pa je vsako prijateljstvo pravo ali ga je treba prepoznati? Personalizirana knjiga bo malčka in njegovega zajčka popeljala na pravo pustolovščino v gozd in družbo gozdnih živali. V iskanju novih prijateljev bo prišel do najlepšega, čudežnega odkritja. Čudovite in barvite ilustracije zgodbi dodajo le piko na i, zaradi njih pa se bo malček lahko popolnoma vživel v njegov lik.', '/storage/products/special_6a2f5db510a713.13197447_v04bZ.jpg', 0, '2026-06-14 20:04:37', '2026-06-14 20:04:37'),
(3, 7, 'Prijateljstvu, ki ga je težko najti in ni samoumevno. Čarobna zgodba, ki bo prevzela tako malčke kot starše.', 'Knjiga o pravem prijateljstvu', 'Prijateljstvu, ki ga je težko najti in ni samoumevno. Čarobna zgodba, ki bo prevzela tako malčke kot starše. Malčki se na poti odraščanja srečujejo z nešteto izzivi, eden od njih je tudi poiskati svoje prijatelje. Pa je vsako prijateljstvo tisto pravi ali ga je potrebno prepoznati? Personalizirana knjiga bo malčka in njegovega zajčka popeljala na pravo dogodivščino v gozd in družbo gozdnih živali.', '/storage/products/special_6a2f629d1032d4.34265503_MEHFp.jpg', 0, '2026-06-14 20:25:33', '2026-06-14 20:25:33'),
(4, 6, 'V čarobnem gozdu se je izgubil mali samorog.', 'Pridruži se čudoviti pustolovščini', 'se je izgubil mali samorog. Pridruži se čudoviti pustolovščini, kjer tvoja deklica pomaga samorogu najti pot domov in pri tem odkriva pomen prijaznosti in medsebojne pomoči.', '/storage/products/special_6a33637719c439.15545001_lkmT1.webp', 0, '2026-06-18 01:18:15', '2026-06-18 01:18:15');

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
('00eXNH2scq9yTHINUBroIljM1fsgEg0ZHvrVxfEO', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXd4RDZUTkpTZ1pUZGNpUGdxRk5Nc0ZHQ1RsbG5DcDJVd25lQXdobiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927715),
('0fyFubsLFOl7iSD0KTIV55eIs22g3x132mJsabX4', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFhlUExkV05HRXB2OFlmM3V2a056YWh0OXg3c2hPZHVJVmlxWHRGSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781928033),
('0karFJKlp6kSjMdUMnzuoLoeKQEGgfvMUSJZerUs', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiblRSR25QdzA4S0FDZ0E1WjBOTlhGOFZ2cnA0anZyNzl6QjZFWmJXZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781927220),
('1QBjec883xhE1jlqpDmSODAfC5d5oviHVnxkJP26', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXRDTUtHSlFFMzd5V3hrV1A2ekVvTktHazJBcEVMeTAxUkk4TzVqdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927215),
('22PAdb7Q6HtriMCFSdLNUSSieiIgxmuJjaoFIv6D', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3VIQW5LelN0SklQRVU0MlVyZXdUbXpheldDeXRzUzZocFN3WTQxQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927998),
('4ikfGthnSaaWT2De4OoLBWaJ5ntb3K34KvXVm6gi', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2RGVFo5eDNqNkNPNW5jMlZoRmVMOXgxdlNFVE45dXpxeHU2UllwUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927725),
('6MvgFdOtDluXXgD5raLaE4gaxUyYig3osdKsMqoL', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicm9qR1paQkFLU2NGMThzUDVuRzNOSjFBVkx5dUZ2emZjUjVQUWhHSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927726),
('6YYp7RhaWuKp8XOiI899OsD1qVb9cGDSlYrcmvDG', NULL, '137.59.180.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGthR3h6dlhHbnRIT2N0V1RoT1pGUW53aHlJMUtuN0xjMm5RTlV6eiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MDoiaHR0cHM6Ly9hZG1pbi5seW1ldGFsZXMuY29tL2FkbWluL2NvbnRhY3QtbWVzc2FnZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1MDoiaHR0cHM6Ly9hZG1pbi5seW1ldGFsZXMuY29tL2FkbWluL2NvbnRhY3QtbWVzc2FnZXMiO3M6NToicm91dGUiO3M6Mjg6ImFkbWluLmNvbnRhY3QtbWVzc2FnZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927923),
('6ZjBmU1TnAQs3oUAldTbqtKmnoMtPTgdSnsqUgcX', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicG1NN0FUTFNzOWg2MUtLSEwzVFZScThOa1IzT3VhQ2hrNHAxYUJVQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('9OW08dWj526nkmh5TN9TFvZ55SD389R2cFvIFVDk', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzRUdkZSU1l1eTVpSjQzWlF6NzR4MGZWbER2VmowbzI1OGh2OURQUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927998),
('9xVDF75vmxzbrbWIyT3uUvIRcyLUqZZzJW0k493G', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWZraTVYck5JVHBmT3dVWUlVTVBOWXZ1c1Y4UE9IRWdQUERYT054cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy83IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927228),
('APxfpBnKjI0dEiY6ySGAt0m012Ihcjei1kkevwVt', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHRrQk1VU1dnWDJlYzZhMTVvaVIzcnNzdWM1Z2pSM2lhaXlGZFBzSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927166),
('BaWySK5uyozttCxQkHZR7cZzzfoqMHS2b6BlDOXE', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHlvTHRsaHUzZVg4N1hhTFU0VGkxZVNuUW12Wjl0ZVpzNE5za0F6cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927215),
('BGK4CM3OkuSC8LMDtqooFmRz4XdcYAkkaa3QD1HT', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzdOVURkaTBMVFNJQ29OWVNEb0F6d1pDdHl5b0N6TWFtc2plTU5VQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927227),
('BTYWkWmsRzyfFsrVFulTZxe8Elpc6uRejfPETtmX', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid2NLcThOSE1wM29Zc0E0WjRGcnJCNnl5aUVCcHNNeTB3RHBSSjBVMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927165),
('bySJy6zulsKKy8IYN5ofAQPavdFaSLNATZam6m2t', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjdJWTRpeE93ajcydGxURjRjTzNGdURtQ21za0VKQnJKT0wwVFQ3RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781928033),
('dfHqBMEDBgWz2FYG66ZH1IkLxxKH9V6FGy4txVsc', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmdXUFB0V0FaVHpoQzEwNlk4bUdSeUFYY0NsalJDeWdyWktNcld3dCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927255),
('dfvXhfzyUcuXaSz9T0fsrNuB2JsBHGWUxw6dwFZO', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUpQVzljRXhwNkh1cVJScFJqWE1FSDdHNzNYOG9ma1BEanpDMzVFMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927714),
('dvy3lGJcny82xZCW9LOxMd8b8FzRmSO5CNPuURlb', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibnR0UkJpRnl6MzdQdnFtZ0I4N2NuNk9SSHduVGpjUW5RcVZ5YndCayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927719),
('dZJhR4e8Qal6pEqj7PQZKYZwBOUfogsKDpgRQ8dq', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWJ0YVpiWW1yeGJvQ0F1WVpxZXJLRGMyVVpPR2ZZMU5FblYwV3NKWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781928034),
('ED1czlvjBdkclpcIzE5dFMicX1JpxeyHSolY3l2L', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkw0NFJGSDllWWdKR1RmaUYzTVlQSUxzdlo5TlF6S0E0S2J3dDdNZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('eJIcx2uTXQnsR0DS6Puk65rZB8luTeLkHMB3dg4I', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicW1RQjRhQVA3aExnUUVUQndOZEZ2SmdMd05oNXFLNVNIOXE4a2lOdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927215),
('eZMWVdst5K8mftkeIP6ybxoJBmwkKq67CpAIU5Bx', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjNZVWJlVEdhUEV1SWVvV21pcWV5YjBpS3ZIVkNpNnk1aFBRZHl3WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927237),
('fV9CvVOcLAb0gxHDFNsmcHStSTNN7N9BiddLLIYH', NULL, '212.44.101.117', 'node', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibzYwU2NvWHh6TFVzNXRqc01FQ00yRXFRQ3ptS3B3MTVxVFMzUWNVWiI7czo5OiJzaG9wX2NhcnQiO2E6MTp7aTo3O2E6ODp7czoxMDoicHJvZHVjdF9pZCI7aTo3O3M6NToidGl0bGUiO3M6MjU6IlNrcml2bm9zdG5pIHBvZHZvZG5pIHN2ZXQiO3M6NToiaW1hZ2UiO3M6ODI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9zdG9yYWdlL3Byb2R1Y3RzL2dhbF82YTJmNjI5ZDExNTQ5Ny40NDk4MTg3NF9YVEg4UC5qcGciO3M6MTA6InVuaXRfcHJpY2UiO2Q6MjI7czo4OiJxdWFudGl0eSI7aToxO3M6MTA6ImxpbmVfdG90YWwiO2Q6MjI7czoxNToicGVyc29uYWxpc2F0aW9uIjtOO3M6NDoidHlwZSI7czo3OiJwcm9kdWN0Ijt9fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ5OiJodHRwczovL2FkbWluLmx5bWV0YWxlcy5jb20vYXBpL3Nob3AvaG9tZS1jb250ZW50IjtzOjU6InJvdXRlIjtOO319', 1781929699),
('G1Emw0deUqCXnfTrpAaG5gWjIohXGUvaNEekO73N', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHoyT2N4WU5adFQ1Y25WRG5TVDc5dWhONUVBQ3kzQ2gzeWhIZDhsdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927215),
('GbAnDaZxipjFsm38FwDHreRHTuIvmAFOvlf2m88V', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREN6d3g0Z1NqazFDRUxKSURwSWtvUEQ0TVBNUlU3THNDUWxFWUJ2ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781928033),
('gFgTlqxYSfoFNt2jUd6lT6CJqT3OcDjb3NBjPgI2', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWZtNkxGUTY2Z0xPS0VKa3JJUkExMXB3eE5zQkNuUUxFeUVsNlhiVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927222),
('GtSpAkUYTNYzcgqbPDAIHYngJlbACR1v0KcCDlwp', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVBnUjFZd0dwbjJHU1ByV3E1NXZqazJoR1cxaWs1UnZ3WXNuVUJjeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927170),
('h4T0wcxZaIuUMaBOMkIoeN1ZFtMTFNeiCeZhzAWC', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXJpaVJsSlcxR3MzOGtDQ25OcUpmWEp0b3VoWElmdVdFTkgzRzhEayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927256),
('Icv0gRV3oWjMUZmGWgA7o3JGY4PAh57L4HMo34ez', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiejlSRzh3WkI4cTJGeVN3dUpyQnhyNDcwTk5kMVhQZ3pocFUyS2lNQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy83IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927222),
('iQWVUZolEUPuxgZgcuCx6qCwv0q0GzjMgUZqhTWp', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibHNRRG1LYVZjUzRkUVlXeU9PTHI5UTF4WU1SQW1wWmUzVHA0cGtIWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927215),
('it6h1LXsxcuYbJc39Ibt8ZFT4VrgzULeMWQ2HMR9', NULL, '137.59.180.81', 'PostmanRuntime/7.54.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicWdUVzY4WG1md1lGN1FseWFHdHpFVTVheWNPcWZmYkdrZVdiZThCRSI7czo5OiJzaG9wX2NhcnQiO2E6MTp7aTo5O2E6ODp7czoxMDoicHJvZHVjdF9pZCI7aTo5O3M6NToidGl0bGUiO3M6MTc6IsSMdWRlxb5uaSB6YWrEjWVrIjtzOjU6ImltYWdlIjtzOjgzOiJodHRwczovL2FkbWluLmx5bWV0YWxlcy5jb20vc3RvcmFnZS9wcm9kdWN0cy9nYWxfNmEyZjVkYjUxM2Q5YTUuNDc2NDY1NjVfZlBIWVYuanBlZyI7czoxMDoidW5pdF9wcmljZSI7ZDoxOS45ODk5OTk5OTk5OTk5OTg0MzY4MDU5ODEzMjc3Nzk1OTEwODM1MjY2MTEzMjgxMjU7czo4OiJxdWFudGl0eSI7aToyO3M6MTA6ImxpbmVfdG90YWwiO2Q6MzkuOTc5OTk5OTk5OTk5OTk2ODczNjExOTYyNjU1NTU5MTgyMTY3MDUzMjIyNjU2MjU7czoxNToicGVyc29uYWxpc2F0aW9uIjtOO3M6NDoidHlwZSI7czo3OiJwcm9kdWN0Ijt9fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ5OiJodHRwczovL2FkbWluLmx5bWV0YWxlcy5jb20vYXBpL3Nob3AvaG9tZS1jb250ZW50IjtzOjU6InJvdXRlIjtOO319', 1781927860),
('iYEDMY6b4BPQlq0anoZsuZzRw7sRAWo4iU2Yn7Yz', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFcyUFZLdFVJMFduc21sdEdheHZWMmdQRWYyQXJVZWxCaE1XTlVXciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927228),
('knmzShUqAGqh0PEHa5BDunrNeiqOKyrPTAHNZbC5', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1FLTWhrSWsxSDNoU2NGTFZMS3BHbkRkdEpaY3dIVGdzcWZ5VVh2NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927999),
('l26amKbG0TeYMSN0OVSTIfhSTdEAxXkFp3oEhFBe', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE4ybE9oN0w0UVhMSlRrSzUyd1NtSVlVaHgwcUVLRDlqYUNrWUhTaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927714),
('lOW5uk1yGiHCg9k4b41BjinSawNlMOeqSq3pDj8Z', NULL, '137.59.180.81', 'node', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidWp5bVpndEJuUkxIeVJFaVRmWmxjWTJhZGxJM0lLMmM1aWhYb1VQSCI7czo5OiJzaG9wX2NhcnQiO2E6Mzp7czo2OiJnaWZ0XzMiO2E6Nzp7czoxMDoicHJvZHVjdF9pZCI7aTozO3M6NToidGl0bGUiO3M6MzY6IkRhcmlsbmEgS2/FoWFyYSBTbGFka2loIFByZXNlbmXEjWVuaiI7czo1OiJpbWFnZSI7czo0MjoidXBsb2Fkcy9naWZ0cy8xNzgxNzQ4NDA4XzZhMzM1MmI4NjFkNTcuanBnIjtzOjEwOiJ1bml0X3ByaWNlIjtkOjQ5Ljk5MDAwMDAwMDAwMDAwMTk4OTUxOTY2MDEyODI4MDUyMDQzOTE0Nzk0OTIxODc1O3M6ODoicXVhbnRpdHkiO2k6MTtzOjEwOiJsaW5lX3RvdGFsIjtkOjQ5Ljk5MDAwMDAwMDAwMDAwMTk4OTUxOTY2MDEyODI4MDUyMDQzOTE0Nzk0OTIxODc1O3M6NDoidHlwZSI7czo0OiJnaWZ0Ijt9aTo3O2E6ODp7czoxMDoicHJvZHVjdF9pZCI7aTo3O3M6NToidGl0bGUiO3M6MjU6IlNrcml2bm9zdG5pIHBvZHZvZG5pIHN2ZXQiO3M6NToiaW1hZ2UiO3M6ODI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9zdG9yYWdlL3Byb2R1Y3RzL2dhbF82YTJmNjI5ZDExNTQ5Ny40NDk4MTg3NF9YVEg4UC5qcGciO3M6MTA6InVuaXRfcHJpY2UiO2Q6MjI7czo4OiJxdWFudGl0eSI7aToxO3M6MTA6ImxpbmVfdG90YWwiO2Q6MjI7czoxNToicGVyc29uYWxpc2F0aW9uIjthOjQ6e3M6MTA6ImNoaWxkX25hbWUiO3M6NDoiZHRmZyI7czoxMDoiZGVkaWNhdGlvbiI7TjtzOjEzOiJwcmV2aWV3X2ltYWdlIjtzOjY1OiIvc3RvcmFnZS9wZXJzb25hbGlzYXRpb25zL3ByZXZfNmEzNWZiMGI0OGJmMTAuNjcxNjU1MDlfRzAwVndiLnBuZyI7czo2OiJmaWVsZHMiO2E6Mzp7czo0OiJzcG9sIjtzOjU6IkRla2xlIjtzOjU6Imxhc2plIjtzOjc6IiNlMmM5NzYiO3M6Mzoib2tvIjtzOjc6IiM2NjdlYjciO319czo0OiJ0eXBlIjtzOjc6InByb2R1Y3QiO31pOjk7YTo4OntzOjEwOiJwcm9kdWN0X2lkIjtpOjk7czo1OiJ0aXRsZSI7czoxNzoixIx1ZGXFvm5pIHphasSNZWsiO3M6NToiaW1hZ2UiO3M6ODM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9zdG9yYWdlL3Byb2R1Y3RzL2dhbF82YTJmNWRiNTEzZDlhNS40NzY0NjU2NV9mUEhZVi5qcGVnIjtzOjEwOiJ1bml0X3ByaWNlIjtkOjE5Ljk4OTk5OTk5OTk5OTk5ODQzNjgwNTk4MTMyNzc3OTU5MTA4MzUyNjYxMTMyODEyNTtzOjg6InF1YW50aXR5IjtpOjE7czoxMDoibGluZV90b3RhbCI7ZDoxOS45ODk5OTk5OTk5OTk5OTg0MzY4MDU5ODEzMjc3Nzk1OTEwODM1MjY2MTEzMjgxMjU7czoxNToicGVyc29uYWxpc2F0aW9uIjthOjQ6e3M6MTA6ImNoaWxkX25hbWUiO3M6ODoibSxtLGJtLG0iO3M6MTA6ImRlZGljYXRpb24iO047czoxMzoicHJldmlld19pbWFnZSI7czo2NToiL3N0b3JhZ2UvcGVyc29uYWxpc2F0aW9ucy9wcmV2XzZhMzVmYzY1NTFmMDI4LjUxNDA3MDcyX2UwTVhGUC5wbmciO3M6NjoiZmllbGRzIjthOjM6e3M6NDoic3BvbCI7czo0OiJGYW50IjtzOjU6Imxhc2plIjtzOjc6IiMyMjFhMTciO3M6Mzoib2tvIjtzOjc6IiM2NjdlYjciO319czo0OiJ0eXBlIjtzOjc6InByb2R1Y3QiO319czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fX0=', 1781933440),
('lPFJk8ovp87cRaDFrFRYVxUagruGk26S06MY5bho', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNk1WRnJKaHZlOEhsd0RrT3NVN09LTHpWaHNLUkdueVN0aGtWS044aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy83IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927215),
('Moa4hyVzX4ngR1Q22nENzyULr8qGjgbcYg4k0BHh', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVVUa0VzMGtxMG82Q2Q4NVJua1BnQ0ZaaWF3TG96RDlNVlVrR0Y3YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927998),
('oANsCMpfiqnVt1TZbXwm1xGHvSyFQqAZ6MmIYhDl', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUxqTUFLZWtBaTBvT1VHd0JZYkVUczF4RXhpNlRBM0drcTYyV1ZHTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('p0vK7J6KDxW1PhR6ecxQNdsTtg65L2qgCiTPa56T', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjNob21UTFVad3hWb2RvaTY1cmowU2JDTkpLYXpFMmd4bEVaNXluaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927714),
('p4hVHnLE1kOAyMIwNxzYJO3npxRC5S97F9mv9LWK', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmt3U3o0aU40OGRPajJWZk5aVWR2b29nMXoxeGJSalpXbXY2YjZ1diI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927998),
('P9Pxwyj8yxh8srn6SgQrKvKI20sG6qXZYI6Y3dAR', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjc5bWFNTTdISG13cm50amlZcGRCNUg0bmViRE9pQVpzNDdjalFIQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927725),
('pSNGtBu8dWUyed3wW7gDsJWXHtYYHEB27QzyCHbn', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibWpBckZGRG5DWmtEUTVWMlluOXQ1RzNqNzRON2haVTRuYUxzZkM0bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927732),
('pwd3TDxQ6Bhq6MCj9FKvWYx07YAtK0KcWun89Yz6', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjJ4NVh1YWN4UXFKNE5YV25malJkejFIVzhPUkFOTWlackg1VzNkRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927253),
('Q7bs8xixDfBcylGFPj2BNOIEXJd827XsDP77OX79', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXNvT3BFZzFrQlFaS3k4WFRsdEJhVEMwVlJlSHhoM1Voc1NKb2h6cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy83IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927170),
('qkPYLaV0sxWv01WBUbc3qSiHy6NpyDsAd5Q7Quef', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEZHUjNQdXpWQmM2TUZ1VTlyWHR2V0gxa3NpWlRtMmhjZlJURGlvdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927724),
('qxA8blIbQ89r6nAOY58VbwxOpCPd61gesxPunVDm', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXlHcGd1MnlGZ1J5TGQwVmxiR0J2RDlEckRoaWdybzRsdVFlemNWbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927222),
('rUk28HLbzaLWBB4igca0vnYkwevLg41HUW0Hnnm8', 1, '137.59.180.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMHlqUHFGU3ZNZjJIRXNRSk51R2NaWkMxQ3ZYOUJQYWI3OHRibGZ2QiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwczovL2FkbWluLmx5bWV0YWxlcy5jb20vYWRtaW4vcGFnZXMvNS9lZGl0IjtzOjU6InJvdXRlIjtzOjE2OiJhZG1pbi5wYWdlcy5lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781928159),
('RVOFOmxOWGrbQAD6HAYrtjXgEApBCXD1vsV8myOG', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMU9sQTZqcDVBMHNROWkzc1ZFT0NEVURHcDFoTzhodzlPWlY2YnZqcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927220),
('Sgi6Q9rl350LfXzJmFPkFS51bVsJTD7cHOeQG0hC', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicFhIMTg0THM1bk5tV2Q0U1FETGlDWDV2TnN3dlZ5c2VoSVljZTNDVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('SQ5A8rOPQYaJpAj70GMkgHHRhqr6m7PCLBi0gcZl', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaU1NYjZxNVZYMUVBb2Y2eFNtN0xwQVlUMnlaV2tkQlVWY0VzcWZHSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927725),
('T9sIc2jbKiJu1wf2ahIeuZ5R0LLYUco2MmDqwJES', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVno1ang0RUJ1SnpYVW9PU2lQY1BEVTF1amQwWjM3VWZoRW1CdEZ6dSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927235),
('Tt4IvaIJvsidESpBqI4dTV6PBz4VXtbjgCzBcGfd', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWxNS1RMY0dVY0lJTWxuNFdZNnA3cnB6cXJRWlBNcjB1SVBxc003RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927719),
('uUe8ebF2AuU6TyOxiko1QRq1FFuvC4kN6adyK5v1', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNENZRkxEdXE0d3ZPQnN4NjhjTTJRZk9UOHgzSjQzTzFLZnVZREZNeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9mYXFzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927998),
('veO2PHey1l0XDa1poClzHzYCiRuMKmR4b9t2DAcf', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0VoeVpCVjQwUHlIU1IyTENwRVdVdHBCeHdoeGxLYmExRkRoWkgxaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('vOxJbrJdnFfrzvLuvdD3L31LVRnQQMOmwI1ZW3Vo', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSEhjNGR1QW9qSXRQVHVva0Q2VWFrUjJCT3Y0RkU3UFUwc3p2T2t5eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927165),
('W9ejkkyuETzrhRCYF1eO8HaPsybPkqHu4jErZyHy', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3lkRUpIaEI3S2UwcUdIalZGbkZhQzV5b2lFRDdZMWNtQmZEM1ZKVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927732),
('wV1h6g6sIH1q7CcHeeHraPQHcp9DvGudwEQpYjK7', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic25YcTI0QUtaV0lXMkR2S3p5UzUwbmxZNXQ5eWMzZTJkN1YyUzRaayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9jYXJ0IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781927724),
('xlnGq3RypLxjDDN0jdbtutTxaG7ehSxap6F43vhk', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSG5JWW1IUTFWc1h0RUdCeTVJeXpLTkFvZTBhYVlmRWZwdURrNUlkWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927165),
('XyO7lJ0zCM7zuNxDMuEEMkLo1Tn1FMEsai0bVdEb', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicXFTeVVJaFNIMEFaTU5mN0NJMHNtaFJPR2NjMkhvcVFTc1d2eEFEQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9zaXRlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781928033),
('Z6uLxehqpZwDp6LajpIlFlyFd1nvL4g7dFGVKK2C', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDRnTmE5SmZjRGRvd0hZWFhCcjBxdVdwWFJhUWxMbFVNcnN4SnhkRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927728),
('zgVahqd24cW3t4Cy3UIsn6SFEzjlsWuWuNFbZEk5', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEtmeGUxMlpWck95bEt4YUIwdEFZU3FnUFBEYkRlQzBUbXp4U2pOSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9vZmZlcnMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927223),
('zPWesDSpjRGliOhWOmf4wZnwFhIfwPDqmik5mpfj', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3dicjBRaXhKZmNwNzJpSkFydHBVMkdTbFpKZ3VEN3dLV1NxVnJiMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781927255),
('ZXxGV70rYjHbsYgNPrck377Q9MgqQlEj8PgMLcFo', NULL, '212.44.101.117', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFo2V2pjdklDdXJnTkJNTm1UVXk1MWlRWnpwODVSNWtpMWIxTUJYZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vYWRtaW4ubHltZXRhbGVzLmNvbS9hcGkvc2hvcC9ob21lLWNvbnRlbnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781928033);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'newsletter_title', 'Pridobite 10 % popusta na prvo naročilo', '2026-06-14 08:46:26', '2026-06-16 23:41:33'),
(2, 'newsletter_description', 'Pridružite se naši skupnosti in ustvarite čarobne trenutke za otroke, ki jih imate radi.', '2026-06-14 08:46:26', '2026-06-16 23:41:33'),
(3, 'footer_brand_description', 'Ustvarjanje osebnih zgodb, ki slavijo čarobnost otroštva in družinske vezi.', '2026-06-14 08:46:26', '2026-06-17 23:59:43'),
(4, 'footer_logo_path', 'uploads/home/footer_logo_1781747983.webp', '2026-06-14 08:46:26', '2026-06-17 23:59:43'),
(5, 'footer_copyright', 'Vse pravice pridržane.', '2026-06-14 08:46:26', '2026-06-17 23:59:43'),
(6, 'social_instagram', 'https://www.dyxasadaniqic.ca', '2026-06-14 08:46:26', '2026-06-14 09:58:36'),
(7, 'social_tiktok', 'https://www.milujozyqujivut.co', '2026-06-14 08:46:26', '2026-06-14 09:58:36'),
(8, 'social_facebook', 'https://www.tyn.co.uk', '2026-06-14 08:46:26', '2026-06-14 09:58:36'),
(9, 'social_media_links', '[{\"label\":\"Instagram\",\"url\":\"https:\\/\\/www.instagram.com\\/#\"},{\"label\":\"TikTok\",\"url\":\"https:\\/\\/www.tiktok.com\\/#\"},{\"label\":\"Facebook\",\"url\":\"https:\\/\\/www.facebook.com\\/#\"},{\"label\":\"X\",\"url\":\"https:\\/\\/x.com\\/#\"}]', '2026-06-14 21:48:36', '2026-06-17 23:59:43');

-- --------------------------------------------------------

--
-- Table structure for table `site_categories`
--

CREATE TABLE `site_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_categories`
--

INSERT INTO `site_categories` (`id`, `name`, `slug`, `description`, `is_special`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Personalizirana darila', 'personalizirana-darila', NULL, 0, 0, 1, '2026-06-14 09:24:02', '2026-06-14 10:21:50'),
(5, 'Priložnosti', 'priloznosti', NULL, 0, 0, 1, '2026-06-14 10:22:00', '2026-06-14 10:22:00'),
(6, 'NAZAJ V ŠOLO', 'nazaj-v-solo', NULL, 1, 1, 1, '2026-06-14 10:22:10', '2026-06-17 00:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `site_subcategories`
--

CREATE TABLE `site_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_subcategories`
--

INSERT INTO `site_subcategories` (`id`, `site_category_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(10, 4, 'NOVOROJENČKI', 'Knjige za novorojenčki', 1, '2026-06-14 09:24:02', '2026-06-14 20:18:44'),
(11, 4, 'OTROCI', 'Knjige za otroci', 1, '2026-06-14 09:24:02', '2026-06-14 20:18:44'),
(12, 4, 'ODRASLI', 'Knjige za odrasli', 1, '2026-06-14 09:24:02', '2026-06-14 20:18:44'),
(13, 5, 'NOVOROJENČKI', 'Knjige za novorojenčki', 1, '2026-06-14 10:22:00', '2026-06-14 20:18:44'),
(14, 5, 'OTROCI', 'Knjige za otroci', 1, '2026-06-14 10:22:00', '2026-06-14 20:18:44'),
(15, 5, 'ODRASLI', 'Knjige za odrasli', 1, '2026-06-14 10:22:00', '2026-06-14 20:18:44'),
(16, 6, 'NOVOROJENČKI', 'Knjige za novorojenčki', 1, '2026-06-14 10:22:10', '2026-06-14 20:18:44'),
(17, 6, 'OTROCI', 'Knjige za otroci', 1, '2026-06-14 10:22:10', '2026-06-14 20:18:44'),
(18, 6, 'ODRASLI', 'Knjige za odrasli', 1, '2026-06-14 10:22:10', '2026-06-14 20:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `parent_id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 4, NULL, 'Lasje', NULL, NULL, 1, '2026-06-14 09:31:52', '2026-06-14 09:35:35'),
(6, 3, NULL, 'Lasje', NULL, NULL, 1, '2026-06-14 09:38:13', '2026-06-14 09:39:13'),
(7, 4, NULL, 'oko', NULL, NULL, 1, '2026-06-18 00:19:05', '2026-06-18 00:19:05'),
(8, 3, NULL, 'oko', NULL, NULL, 1, '2026-06-18 00:19:21', '2026-06-18 00:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`, `updated_at`) VALUES
(1, 'user@example.com', '2026-06-16 23:53:44', '2026-06-16 23:53:44'),
(2, 'sejan.softvence@gmail.com', '2026-06-18 00:13:46', '2026-06-18 00:13:46'),
(3, 'sejan@gmail.com', '2026-06-18 00:15:28', '2026-06-18 00:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `email_verified_at`, `password`, `status`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@example.com', '1234567890', NULL, '$2y$12$ct4dFfgIgxjpeJJFxCoPVOiYcueJrDa.2.ewXgVDyFPDxgExW8KmK', 'approved', 'admin', NULL, '2026-06-14 08:46:26', '2026-06-14 08:46:26');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_items`
--
ALTER TABLE `footer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `footer_items_footer_section_id_foreign` (`footer_section_id`);

--
-- Indexes for table `footer_sections`
--
ALTER TABLE `footer_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_givers`
--
ALTER TABLE `gift_givers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_sections`
--
ALTER TABLE `hero_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_features`
--
ALTER TABLE `home_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_promos`
--
ALTER TABLE `home_promos`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_stripe_payment_intent_id_index` (`stripe_payment_intent_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_subcategory_id_index` (`subcategory_id`);

--
-- Indexes for table `product_book_images`
--
ALTER TABLE `product_book_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_book_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_category_images`
--
ALTER TABLE `product_category_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_category_images_product_id_foreign` (`product_id`),
  ADD KEY `product_category_images_category_id_foreign` (`category_id`),
  ADD KEY `product_category_images_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `product_customization_options`
--
ALTER TABLE `product_customization_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_customization_options_step_id_foreign` (`step_id`);

--
-- Indexes for table `product_customization_steps`
--
ALTER TABLE `product_customization_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_customization_steps_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_customization_suboptions`
--
ALTER TABLE `product_customization_suboptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_customization_suboptions_substep_id_foreign` (`substep_id`);

--
-- Indexes for table `product_customization_substeps`
--
ALTER TABLE `product_customization_substeps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_customization_substeps_option_id_foreign` (`option_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_product_id_is_approved_index` (`product_id`,`is_approved`);

--
-- Indexes for table `product_special_sections`
--
ALTER TABLE `product_special_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_special_sections_product_id_index` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `site_categories`
--
ALTER TABLE `site_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_categories_slug_unique` (`slug`);

--
-- Indexes for table `site_subcategories`
--
ALTER TABLE `site_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_subcategories_site_category_id_foreign` (`site_category_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_index` (`category_id`),
  ADD KEY `subcategories_parent_id_index` (`parent_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `footer_items`
--
ALTER TABLE `footer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `footer_sections`
--
ALTER TABLE `footer_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gift_givers`
--
ALTER TABLE `gift_givers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hero_sections`
--
ALTER TABLE `hero_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `home_features`
--
ALTER TABLE `home_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `home_promos`
--
ALTER TABLE `home_promos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_book_images`
--
ALTER TABLE `product_book_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_category_images`
--
ALTER TABLE `product_category_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_customization_options`
--
ALTER TABLE `product_customization_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product_customization_steps`
--
ALTER TABLE `product_customization_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_customization_suboptions`
--
ALTER TABLE `product_customization_suboptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `product_customization_substeps`
--
ALTER TABLE `product_customization_substeps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_special_sections`
--
ALTER TABLE `product_special_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `site_categories`
--
ALTER TABLE `site_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `site_subcategories`
--
ALTER TABLE `site_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `footer_items`
--
ALTER TABLE `footer_items`
  ADD CONSTRAINT `footer_items_footer_section_id_foreign` FOREIGN KEY (`footer_section_id`) REFERENCES `footer_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_book_images`
--
ALTER TABLE `product_book_images`
  ADD CONSTRAINT `product_book_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_category_images`
--
ALTER TABLE `product_category_images`
  ADD CONSTRAINT `product_category_images_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_images_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_customization_options`
--
ALTER TABLE `product_customization_options`
  ADD CONSTRAINT `product_customization_options_step_id_foreign` FOREIGN KEY (`step_id`) REFERENCES `product_customization_steps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_customization_steps`
--
ALTER TABLE `product_customization_steps`
  ADD CONSTRAINT `product_customization_steps_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_customization_suboptions`
--
ALTER TABLE `product_customization_suboptions`
  ADD CONSTRAINT `product_customization_suboptions_substep_id_foreign` FOREIGN KEY (`substep_id`) REFERENCES `product_customization_substeps` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
