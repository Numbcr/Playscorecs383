-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: cs383_playscore
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-game_image_28','s:74:\"https://media.rawg.io/media/games/511/5118aff5091cb3efec399c808f8c598f.jpg\";',1764212365),('laravel-cache-game_image_9767','s:74:\"https://media.rawg.io/media/games/4cf/4cfc6b7f1850590a4634b08bfab308ab.jpg\";',1764212349);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `game_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rawg_id` int NOT NULL,
  `game_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`game_id`),
  KEY `games_admin_id_foreign` (`admin_id`),
  CONSTRAINT `games_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (2,3498,'Grand Theft Auto V',95,'For me, Grand Theft Auto V’s extraordinary scope is summed up in two favourite moments. One is from a mid-game mission in which I flew a plane into another plane, fought the crew, hijacked the thing, and then parachuted out and watched it crash into the sea to escape death at the hands of incoming military fighter jets. Another time, whilst driving around in an off-road buggy, I got distracted by something that looked like a path up one of the San Andreas mountains. Turns out it was a path, and I spent 15 minutes following to the summit, where I nearly ran over a group of hikers. “Typical!” one of them yelled at me, as if he nearly gets run over by a rogue ATV on top of a mountain every time he goes on a hike.\n\nI could go on like this for ages. GTA V has an abundance of such moments, big and small, that make San Andreas – the city of Los Santos and its surrounding areas – feel like a living world where anything can happen. It both gives you tremendous freedom to explore an astonishingly well-realised world and tells a story that’s gripping, thrilling, and darkly comic. It is a leap forward in narrative sophistication for the series, and there’s no mechanical element of the gameplay that hasn’t been improved over Grand Theft Auto IV. It’s immediately noticeable that the cover system is more reliable and the auto-aim less touchy. The cars handle less like their tires are made of butter and stick better to the road, though their exaggerated handling still leaves plenty of room for spectacular wipeouts. And at long last, Rockstar has finally slain one of its most persistent demons, mission checkpointing, ensuring that you never have to do a long, tedious drive six times when you repeatedly fail a mission ever again.',1,'2025-11-26 22:35:51','2025-11-26 22:35:51'),(3,3328,'The Witcher 3: Wild Hunt',99,'Unlike its predecessor, The Witcher 3: Wild Hunt doesn\'t exactly come screaming off the starting line. Compared to The Witcher 2, where you\'re immediately plunged headlong into a sexy story of intrigue and betrayal, this main quest can seem mundane, even perfunctory at times. But each time I stepped off the well-beaten path to blaze my own trail, it turned into a wild, open, exhilarating fantasy roleplaying experience, rife with opportunities to make use of its excellent combat. Even after over 100 hours with The Witcher 3, it still tempts me to press on – there’s so much more I want to learn, and hunt.\n\n\nThe Witcher 3 is as dense and deep as the other two games in the series in terms of RPG mechanics, and the overwhelmingly massive open-world environment has at once made that depth more intimidating, and in the long run, more rewarding. It’s difficult to express just how huge and open this world is: verdant, rolling fields liberally dotted with swaying foliage of every shape and size fill the space between loosely connected, ramshackle townships where people struggle to scrape by. A full day/night cycle and dynamic weather pull it all together, cementing The Witcher 3’s landscape as one of the most authentic-feeling open worlds I’ve ever seen. A handy minimap points you where you want to go, which might seem like a crutch, but honestly, without it, I’d have been hopelessly lost. That a world this size still feels so purposeful, and full of things to do is quite an achievement.\n\nThe one caveat on all that though, is the technical performance on both the Xbox One and PS4 versions. 30 frames per second was sometimes too much to ask, transitions between The Witcher 3’s two main maps are just a bit too long, and minor glitches do pop up from time to time. None of it ever impacted gameplay in any meaningful way, though it did compromise the beauty of the experience ever so slightly. Thankfully, PC players can expect a lot more. On a GTX 980, Witcher 3 ran at 60 frames per second at all times on ultra settings.\n\nThis new open-world map obviously has ramifications for the structure of the story, and though there are flashes of greatness, the main story is ultimately the least fulfilling part of The Witcher 3. You might call it another case of The Elder Scrolls Syndrome. Our tale begins as a multi-continent search for Geralt’s long-lost lover Yennifer, and Ciri, his surrogate daughter. My single biggest issue though, is that it never becomes much more: the overly long main story is essentially just Geralt running errands for people in exchange for information on Ciri’s whereabouts. It effectively maintains focus and momentum, but it feels more like a wild goose chase than an intriguing mystery to unravel, like the one we got in Assassins of Kings.\n\nThanks to lots of excellent dialogue and voice acting there is some emotional payoff along the way, but it’s mixed in with too much padding in the form of meaningless fetch quests and collectathons. Every time I felt like I was on the verge of an interesting revelation, I’d have to suddenly stop to escort a goat, or search for a lost, narcoleptic dwarf. Heck, even Geralt can barely hide his frustration with the constant parade of menial tasks at times.\n\nIt’s also worth noting that though you will get along fine without playing the first two games in the series, without the context provided by the Witcher novels, Ciri is more or less a complete stranger until the last quarter of the journey, which made it difficult to care about finding her as much as The Witcher 3 expected me to – especially given the slew of intriguing characters who are relegated to supportive background roles.',1,'2025-11-26 22:38:18','2025-11-26 22:38:18'),(4,9767,'Hollow Knight',100,'It’s not hard to get lost in the deep, subterranean world of Hollow Knight – and I mean that in more ways than one. The expansive catacombs of Hallownest have countless paths to explore and secrets to find. But more than that, it’s rich with lore, history, and purpose that drew me into a 2D Metroidvania kingdom I wanted to uncover every inch of.\n\nThe deeper I went into Hollow Knight, the more I was surprised at just how much content and freedom it has to offer. I could wander in basically any direction and find bosses to fight, upgrades to collect, and secrets to uncover. But what’s truly captivating about the exploring this long-dead kingdom is its atmosphere. Art, music, color tone, sound, and a million other little details combine to give each area of the map a distinct sense of place, and those areas jigsaw together in a way that feels intentional and alive.\n\nWorld Wide Web\nThere are far more of these distinct biomes than I ever expected to discover, and the edges of each one blend together with the next in ways that help them make sense in the world. For example, walls on the border of the Fungal Wastes, even impassable ones in other areas, will be dotted with its telltale mushrooms. The lush environment of an area called Greenpath feels bustling and humid, a stark difference to the cold, dark caves of the Forgotten Crossroads. The bubble-filled region of Fog Canyon isn’t technically underwater, but the muffled filter over all of its audio goes hand-in-hand with jellyfish enemies and a brighter blue tone.\n\n\nScreens - Hollow Knight\n\n\nView 35 Images\n\n\n\nHallownest’s capital city, the City of Tears, is a metropolis in a huge cave where it’s always raining. But it wasn’t until 10 hours after I first discovered this place that I stumbled across the Blue Lake, a massive body of serene water positioned just above the underground city. Hollow Knight doesn’t shove this connection in your face, it just lets you explore its world and piece together the story for yourself as you sit down and enjoy a moment of quiet.',1,'2025-11-26 22:40:57','2025-11-26 22:40:57'),(5,28,'Red Dead Redemption 2',99,'It’s an huge, outstanding piece of art – worth the hard-earned cash as well as countless hours that are needed to get to know at least some of the attractions of Rockstar’s rendition of Wild West. For those who put story first – the biggest virtue of Red Dead Redemption 2 is that it sets the stage for RDR 1 and does it wonderfully.',1,'2025-11-26 22:42:34','2025-11-26 22:42:34');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_11_26_create_games_table',1),(5,'2025_11_26_180950_add_is_admin_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('dJJGqm5OqrkYayGQS4t2j2U6ssTYVfS7YnALCT0S',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVVpRczRtVTh5WHI4Y3FPaHdKV2ZpRFQyaDROQnozRTFaZHJzampBcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvc2Vzc2lvbi1jaGVjayI7czo1OiJyb3V0ZSI7Tjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1764210350);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Abdulaziz','Admin@gmail.com',NULL,'$2y$12$LbDDgXWDNZkQHfWFqDkdBObZ7e0X1eo9H0bMqdUHsvo2nK9SLPfz2',NULL,'2025-11-26 15:10:33','2025-11-26 15:10:33',1),(2,'Admin','admin@test.com',NULL,'$2y$12$laEFmt4Aul3oMVEo.IuJ6OJmob6c6ZSi2qsPvICPHLU7HG3Yjf3du',NULL,'2025-11-26 15:19:25','2025-11-26 15:19:25',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 13:16:16
