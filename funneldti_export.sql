/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.15-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: funneldti
-- ------------------------------------------------------
-- Server version	10.11.15-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_02_10_061006_create_project_contents_table',1),
(5,'2026_02_12_014804_add_year_range_to_project_contents_table',2),
(6,'2026_02_13_010847_create_inquiries_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `project_contents`
--

DROP TABLE IF EXISTS `project_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_number` int(11) NOT NULL,
  `section_title` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `year_range` varchar(255) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `source` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_contents`
--

LOCK TABLES `project_contents` WRITE;
/*!40000 ALTER TABLE `project_contents` DISABLE KEYS */;
INSERT INTO `project_contents` VALUES
(1,0,'Global Settings','metadata','2024-2025','{\"site_title\":\"Western Visayas: Investment and Economic Profile\",\"browser_tab_title\":\"Western Visayas Region 6 Profile\",\"logo_text\":\"DTI Region 6\"}',NULL,'2026-02-18 17:53:32','2026-02-18 17:53:32'),
(2,1,'Title Page','hero','2024-2025','{\"title\":\"Why Invest in\\nWestern Visayas?\",\"subtitle\":\"DEPARTMENT OF TRADE AND INDUSTRY REGION 6\",\"logo\":\"dti-logo.png\",\"highlight_stats\":[{\"label\":\"GRDP GROWTH (2024)\",\"value\":\"4.3%\"},{\"label\":\"GROWING POPULATION\",\"value\":\"4.9M\"}],\"modal_details\":{\"Why Invest in Visayas Logistics Cluster?\":{\"title\":\"Why Invest in Visayas Logistics Cluster?\",\"Points\":[\"Abundant in Natural Resources\",\"Agricultural Potential\",\"Collaborative Environment\",\"Competitive Human Capital\",\"Decongestion of other Areas within PH\",\"Generally Peaceful and Orderly\",\"High Demand for Logistics\",\"High Potential for Economic Growth\",\"Increasing Population\",\"Lack of Logistics Infrastructure\",\"Mitigation of Trade & Manufacturing Paralysis\",\"Presence of Logistics Infrastructure\",\"Strategic Location\",\"Sufficient Power Supply\"]}}}','Supra Regional Consultations, RDC VI, NEDA VI, PSA VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(3,2,'Regional Overview','stats_grid','2024-2025','{\"description\":\"Western Visayas or Region VI is located at the center of the Philippine archipelago and lies between two large bodies of water, the Sibuyan Sea and the Visayan Sea.\",\"notable_info\":\"Last June 13, 2024, President Bongbong Marcos signed the Republic Act No. 12000 to established the Negros Island Region (NIR).\",\"stats\":[{\"label\":\"Land Area\",\"value\":\"20,794 sq. km.\"},{\"label\":\"Population (2024)\",\"value\":\"4,861,911\"},{\"label\":\"Density (2024)\",\"value\":\"370 \\/ km2\"},{\"label\":\"Coastal\\/Landlocked\",\"value\":\"Coastal\"}],\"modal_details\":{\"Composition\":{\"Provinces\":\"Aklan, Antique, Capiz, Guimaras, & Iloilo\",\"Cities\":\"3\",\"Municipalities\":\"98\",\"Barangays\":\"3,209\",\"Congressional Districts\":\"10\"},\"Map Labels\":\"Sibuyan Sea, Visayan Sea, BORACAY, AKLAN, KALIBO, CAPIZ, ROXAS CITY, ANTIQUE, ILOILO, ILOILO CITY, SAN JOSE DE BUENAVISTA, GUIMARAS.\"}}','Philippine Statistics Authority, Census of Population 2024','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(4,3,'Partner Firms Marquee','marquee','2024-2025','{\"items\":[\"CONCENTRIX\",\"TELEPERFORMANCE\",\"TRANSCOM\",\"IQOR\",\"REED ELSEVIER\",\"TELUS\",\"WNS\",\"ASURION\",\"SUTHERLAND\"]}',NULL,'2026-02-18 17:53:32','2026-02-18 17:53:32'),
(5,4,'2024 Gross Regional Domestic Product','chart','2024-2025','{\"title\":\"GRDP Growth Rates by Region (2023-2024, %)\",\"categories\":[\"CV (VII)\",\"Caraga (XIII)\",\"CL (III)\",\"Davao (XI)\",\"EV (VIII)\",\"NorMin (X)\",\"NIR\",\"NCR\",\"CALABARZON\",\"SOCCSKSARGEN\",\"CV (II)\",\"Ilocos\",\"Bicol\",\"CAR\",\"MIMAROPA\",\"WV (VI)\",\"Zamboanga\",\"BARMM\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[7.3,6.9,6.5,6.3,6.2,6,5.9,5.59,5.56,5.5,5.3,4.94,4.92,4.8,4.4,4.3,4.2,2.7]}],\"modal_text\":\"In 2024, Central Visayas was the fastest growing region (7.3%). Western Visayas grew by 4.3%.\",\"notable_info\":\"In 2024, Central Visayas was the fastest growing region in the country with 7.3 percent growth.\"}','https://psa.gov.ph/system/files/pad/2024%20GRDP%20Publication.pdf','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(6,5,'Industry Share to GDP','stats_grid','2024-2025','{\"description\":\"The economy of Western Visayas grew by 4.3 percent in 2024, slower than the 6.8 percent growth in 2023. The Western Visayas economy was valued at PhP 641.76 billion (2.9% of the country\'s GDP) at constant 2018 prices.\",\"stats\":[{\"label\":\"2024 Growth\",\"value\":\"4.3%\"},{\"label\":\"Economy Value\",\"value\":\"PhP 641.76 B\"},{\"label\":\"Share to National GDP\",\"value\":\"2.9%\"}]}','PSA GRDP Publication 2024','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(7,6,'Per Capita GDP Growth','chart','2024-2025','{\"title\":\"Per Capita GDP Growth Rate by Region (2023-2024, %)\",\"categories\":[\"PH\",\"NCR\",\"CAR\",\"I\",\"II\",\"III\",\"IVA\",\"MIMAROPA\",\"V\",\"VI (WV)\",\"NIR\",\"VII\",\"VIII\",\"IX\",\"X\",\"XI\",\"XII\",\"XIII\",\"BARMM\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[4.8,5,3.63,4.7,4.6,5.6,4.3,3.64,4,3.62,5.5,6.2,5.4,3.2,5.1,5.3,4.4,5.8,1]}]}','PSA GRDP Publication 2024','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(8,7,'Growth Rates by Industry (2023-2024)','chart','2024-2025','{\"title\":\"Industry Growth Rates (%)\",\"categories\":[\"Professional & Business Services\",\"Electricity, Steam, Water\",\"Human Health & Social Work\",\"Accommodation & Food\",\"Transportation & Storage\",\"Financial & Insurance\",\"Other Services\",\"Wholesale & Retail Trade\",\"Information & Communication\",\"Real Estate\",\"Public Administration\",\"Construction\",\"Mining & Quarrying\",\"Education\",\"Manufacturing\",\"Agriculture, Forestry, Fishing\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[13.7,13.52,13.49,10.4,8.6,8,7.6,7.3,6.8,5.3,3.6,3.53,3.48,3.4,2.6,-7.3]}],\"modal_text\":\"Top growth: Professional services (13.7%). Decline: Agriculture (-7.3%).\"}','PSA GRDP Publication 2024','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(9,9,'The 12 Economic Drivers','grid','2024-2025','{\"items\":[{\"name\":\"AGRICULTURE\",\"details\":\"Agencies: DA, DOST, NIA, PCA, PhilFIDA, PhilMEC, SUCs, LGUs\"},{\"name\":\"MARINE & FISHERIES\",\"details\":\"Agencies: BFAR, DOST, SUCs, LGUs\"},{\"name\":\"MSMEs & LARGE MANUFACTURING\",\"details\":\"Agencies: DTI, DOST, LGUs\"},{\"name\":\"IT\\/BPO\\/BPMS\",\"details\":\"Agencies: DTI, DICT and Private Companies\"},{\"name\":\"WHOLESALE AND RETAIL\",\"details\":\"Agencies: DTI, LGUs\"},{\"name\":\"TOURISM\",\"details\":\"Agencies: DOT, DTI, LGUs\"},{\"name\":\"PROPERTY DEVELOPMENT\",\"details\":\"Agencies: DHSUD, LGUs\"},{\"name\":\"CONSTRUCTION\",\"details\":\"Agencies: DPWH, LGUs\"},{\"name\":\"HOUSING\",\"details\":\"Agencies: DepEd, CHED, DOH, DSWD, LGUs, NHA\"},{\"name\":\"FINANCIAL INSTITUTIONS\",\"details\":\"Agencies: BSP\"},{\"name\":\"PORT OPERATIONS\",\"details\":\"Agencies: PPA, CAAP, LGUs, Marina\"},{\"name\":\"TRANSPORTATION\",\"details\":\"Agencies: LTFRB, LGUs\"}]}','NEDA Region VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(10,10,'DTI Business Name Registration','stats_grid','2024-2025','{\"description\":\"Business Name Registration in Western Visayas (2022 - September 4, 2025). Total: 245,236.\",\"stats\":[{\"label\":\"2022 Total\",\"value\":\"56,135\"},{\"label\":\"2024 Total\",\"value\":\"71,289\"},{\"label\":\"2025 (Partial)\",\"value\":\"52,187\"},{\"label\":\"Total Transactions\",\"value\":\"245,236\"}],\"modal_details\":{\"Transaction Method\":{\"Online\":\"173,060 (70.57%)\",\"Hybrid\":\"45,188 (18.43%)\",\"Walkin\":\"26,988 (11%)\"},\"Gender Distribution\":{\"Women\":\"155,723 (63.5%)\",\"Men\":\"89,513 (36.5%)\"},\"Territorial Scope\":{\"Barangay\":\"162,943\",\"City\\/Prov\":\"53,490\",\"Regional\":\"19,096\"}}}','https://bnrs.dti.gov.ph/resources/bn-statistics','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(11,11,'Establishments in Operation (2021-2023)','chart','2024-2025','{\"title\":\"Number of Establishments by Province\",\"categories\":[\"Aklan\",\"Antique\",\"Capiz\",\"Guimaras\",\"Iloilo (inc City)\",\"Negros Occ (inc Bacolod)\"],\"series\":[{\"name\":\"2021\",\"data\":[6399,4304,7958,1407,23230,30417]},{\"name\":\"2022\",\"data\":[6737,4380,8220,1487,24148,30776]},{\"name\":\"2023\",\"data\":[8907,5719,9533,1890,26395,33200]}]}','PSA Region 6 Special Release - Reference No. 2025-SR18','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(12,12,'Establishment Size Distribution (2023)','grid','2024-2025','{\"items\":[{\"name\":\"Large (223)\",\"details\":\"Bacolod City (35.9%), Iloilo City (27.4%), Negros Occ (13.9%), Iloilo (8.1%), Aklan (6.3%), Capiz (5.8%), Antique (2.2%), Guimaras (0.4%)\"},{\"name\":\"Medium (239)\",\"details\":\"Bacolod City (27.6%), Negros Occ (22.2%), Iloilo City (20.5%), Aklan (13.8%), Iloilo (9.6%), Capiz (4.2%), Antique (1.7%), Guimaras (0.4%)\"},{\"name\":\"Small (6,791)\",\"details\":\"Bacolod City (24.0%), Iloilo City (18.7%), Negros Occ (17.3%), Iloilo (14.7%), Aklan (12.3%), Capiz (7.6%), Antique (4.0%), Guimaras (1.4%)\"},{\"name\":\"Micro (78,391)\",\"details\":\"Negros Occ (24.2%), Iloilo (21.2%), Bacolod (14.3%), Capiz (11.5%), Aklan (10.2%), Iloilo City (9.4%), Antique (6.9%), Guimaras (2.3%)\"}]}','PSA Region 6 Special Release','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(13,13,'Total Employment (2021-2023)','chart','2024-2025','{\"title\":\"Employment by Province\",\"categories\":[\"Aklan\",\"Antique\",\"Capiz\",\"Guimaras\",\"Iloilo (inc City)\",\"Negros Occ (inc Bacolod)\"],\"series\":[{\"name\":\"2021\",\"data\":[30841,20256,32406,4828,146410,209600]},{\"name\":\"2022\",\"data\":[32996,19851,34791,5201,150969,207238]},{\"name\":\"2023\",\"data\":[51452,25451,42683,6951,165833,237824]}]}','PSA Region 6 Special Release','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(14,14,'Employment Distribution by Size (2023)','grid','2024-2025','{\"description\":\"Distribution of total employment across establishment sizes per province.\",\"items\":[{\"name\":\"Large (124,511)\",\"details\":\"Bacolod (45.5%), Iloilo City (24.4%), Negros Occ (10.1%), Iloilo (8.7%), Capiz (3.8%), Antique (3.9%), Aklan (3.3%), Guimaras (0.3%)\"},{\"name\":\"Medium (32,546)\",\"details\":\"Bacolod (27.8%), Negros Occ (21.7%), Iloilo City (20.8%), Aklan (14.1%), Iloilo (9.4%), Capiz (4.0%), Antique (1.7%), Guimaras (0.4%)\"},{\"name\":\"Small (146,564)\",\"details\":\"Bacolod (25.0%), Negros Occ (19.0%), Iloilo City (18.7%), Iloilo (12.9%), Aklan (12.4%), Capiz (7.5%), Antique (3.4%), Guimaras (1.2%)\"},{\"name\":\"Micro (226,573)\",\"details\":\"Negros Occ (23.4%), Iloilo (20.1%), Bacolod (15.5%), Capiz (11.3%), Aklan (10.8%), Iloilo City (10.1%), Antique (6.7%), Guimaras (2.1%)\"}]}','PSA Region 6 Special Release','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(15,16,'Higher Education Institutions (HEIs)','stats_grid','2024-2025','{\"stats\":[{\"label\":\"Total HEIs\",\"value\":\"102\"},{\"label\":\"Graduates\",\"value\":\"20,391\"},{\"label\":\"Public (SUCs\\/LUCs)\",\"value\":\"53\"},{\"label\":\"Private\",\"value\":\"49\"}],\"modal_details\":{\"Breakdown by Location\":{\"Iloilo City\":\"29 (Public: 3, Private: 26)\",\"Iloilo\":\"27 (Public: 23, Private: 4)\",\"Capiz\":\"17 (Public: 9, Private: 8)\",\"Aklan\":\"16 (Public: 9, Private: 7)\",\"Antique\":\"9 (Public: 6, Private: 3)\",\"Guimaras\":\"4 (Public: 3, Private: 1)\"}}}','CHED - Statistical Bulletin 2024-2025','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(16,17,'HEI Distribution by Discipline','chart','2024-2025','{\"title\":\"Institutional Type by Discipline\",\"categories\":[\"Education Science\",\"Business Admin\",\"Engineering & Tech\",\"IT-Related\",\"Agriculture\\/Forestry\",\"Medical & Allied\",\"Social Sciences\",\"Service Trades\",\"Natural Science\",\"Humanities\",\"Maritime\",\"Mathematics\",\"Mass Comm\",\"Religion\",\"Architecture\",\"Fine Arts\",\"Law\",\"Home Economics\",\"Other\"],\"series\":[{\"name\":\"Public\",\"data\":[345,126,152,60,93,8,14,13,19,15,2,11,8,0,5,2,1,4,20]},{\"name\":\"Private\",\"data\":[156,160,41,42,2,58,20,19,12,13,20,0,3,9,2,5,5,0,20]}]}','CHED - Statistical Bulletin','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(17,19,'Transportation Infrastructure','grid','2024-2025','{\"items\":[{\"name\":\"9 Airports\",\"details\":\"6 CAAP-operated, 3 Private (Sipalay, Sicogon, Semirara).\",\"modal_details\":{\"Map Points\":[{\"label\":\"Iloilo International Airport\",\"lat\":10.83,\"lng\":122.54},{\"label\":\"Bacolod-Silay Airport\",\"lat\":10.77,\"lng\":123.01},{\"label\":\"Kalibo International Airport\",\"lat\":11.69,\"lng\":122.38},{\"label\":\"Roxas Airport\",\"lat\":11.6,\"lng\":122.75},{\"label\":\"Antique Airport\",\"lat\":10.74,\"lng\":121.93},{\"label\":\"Godofredo P. Ramos Airport (Caticlan)\",\"lat\":11.92,\"lng\":121.95},{\"label\":\"Sipalay Airport\",\"lat\":9.78,\"lng\":122.46},{\"label\":\"Sicogon Airport\",\"lat\":11.45,\"lng\":123.25},{\"label\":\"Semirara Airport\",\"lat\":12.05,\"lng\":121.37}]}},{\"name\":\"152 Ports\",\"details\":\"49 Fishing, 69 Private Commercial, 23 Public, 11 Feeder.\",\"modal_details\":{\"Map Points\":[{\"label\":\"Iloilo Commercial Port Complex\",\"lat\":10.7,\"lng\":122.57},{\"label\":\"Port of Dumangas\",\"lat\":10.81,\"lng\":122.71},{\"label\":\"Port of Estancia\",\"lat\":11.45,\"lng\":123.15},{\"label\":\"Port of Culasi (Roxas)\",\"lat\":11.61,\"lng\":122.72},{\"label\":\"Port of Caticlan\",\"lat\":11.93,\"lng\":121.95},{\"label\":\"Port of San Jose (Antique)\",\"lat\":10.74,\"lng\":121.93},{\"label\":\"Jordan Wharf (Guimaras)\",\"lat\":10.66,\"lng\":122.58},{\"label\":\"Bacolod Real Estate Development Corp. (BREDCO) Port\",\"lat\":10.67,\"lng\":122.94},{\"label\":\"Banago Port (Bacolod)\",\"lat\":10.69,\"lng\":122.95},{\"label\":\"Pulupandan Port\",\"lat\":10.52,\"lng\":122.79}]}}]}','CAAP / Wikipedia / WV RSET','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(18,20,'Telecommunications','stats_grid','2024-2025','{\"stats\":[{\"label\":\"Cell Towers\",\"value\":\"1,027\"},{\"label\":\"Wi-Fi Hotspots\",\"value\":\"293\"},{\"label\":\"Fiber-optic\",\"value\":\"20\"}]}','DICT Region VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(19,22,'Operating PEZA Sites','stats_grid','2024-2025','{\"stats\":[{\"label\":\"Total\",\"value\":\"23\"},{\"label\":\"Bacolod City\",\"value\":\"12\"},{\"label\":\"Iloilo City\",\"value\":\"6\"},{\"label\":\"Negros Occ\",\"value\":\"3\"}],\"modal_details\":{\"Others\":\"Aklan: 1, Capiz: 1\"}}','PEZA (Feb 2023)','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(20,23,'Logistics Investment Opportunities','list','2024-2025','{\"items\":[\"Seaport, Airport, Railway\",\"Warehouse, Cold Storage, Trucking Facility\",\"Agri Terminal, Food Terminal, Bagsakan Center\",\"Processing Plant, Packaging Plant\",\"ICT Infrastructure, Economic Zone\",\"Roads and Bridges\"]}','VIZ Logistics Cluster','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(21,24,'Why Invest in Visayas Logistics?','list','2024-2025','{\"items\":[\"Abundant Natural Resources & Agricultural Potential\",\"Strategic Location & Collaborative Environment\",\"Competitive Human Capital\",\"High Demand for Logistics & Economic Growth Potential\",\"Presence of Infrastructure & Sufficient Power Supply\",\"Generally Peaceful and Orderly\"]}','VIZ Logistics Cluster','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(22,25,'Priority Industries by Province','grid','2024-2025','{\"items\":[{\"name\":\"ILOILO\",\"details\":\"Tourism, Processed Food, IT-BPM\"},{\"name\":\"GUIMARAS\",\"details\":\"Fruits (Mangoes), Nuts (Cashews)\"},{\"name\":\"ANTIQUE\",\"details\":\"Bamboo, Processed Food (Kalamay)\"},{\"name\":\"AKLAN\",\"details\":\"Wearables (Pi\\u00f1a), Tourism (Boracay), Processed Food\"},{\"name\":\"CAPIZ\",\"details\":\"Aquamarine (Seafood), IT-BPM\"},{\"name\":\"NEGROS OCC\",\"details\":\"Sugar, Wearables, IT-BPM, Processed Food\"}]}','DTI Western Visayas','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(23,26,'DTI 6 Priority Industries','grid','2024-2025','{\"items\":[{\"name\":\"Coffee\",\"details\":\"9,914 ha Area Planted, 2,090 MT Production\"},{\"name\":\"Cacao\",\"details\":\"1,048 ha Farm Area, 21,988 kg Avg Production\"},{\"name\":\"Processed Fruits & Nuts\",\"details\":\"Mango, Banana, Pineapple, Peanut, Papaya, Calamansi\"},{\"name\":\"Coconut\",\"details\":\"Food (VCO, Vinegar) & Non-Food (Lumber, Copra) Products\"},{\"name\":\"Bamboo\",\"details\":\"25,535 ha Planted, 9 SSFs, 5 Anchor Firms\"},{\"name\":\"Wearables & Homestyle\",\"details\":\"Pi\\u00f1a, Abaca, Raffia (Aklan, Iloilo)\"},{\"name\":\"IT-BPM\",\"details\":\"200+ Companies, 50 Assisted Startups\"}]}','DTI Region VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(24,27,'Bamboo Industry Statistics','grid','2024-2025','{\"description\":\"Major industry sector. 9 SSFs, 5 Anchor Firms, 25,535.85 ha planted (as of Sept 2022).\",\"items\":[{\"name\":\"Yearly Area Planted (Ha.)\",\"details\":\"2013: 74, 2014: 125, 2015: 274.35, 2017: 4063.5, 2018: 1068, 2019: 50, 2020: 12847, 2021: 4714, 2022: 1320\"}]}','DTI Region VI - Annual Report','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(25,28,'Cacao Industry Cluster','stats_grid','2024-2025','{\"stats\":[{\"label\":\"Total Farm Area\",\"value\":\"1,048.48 ha\"},{\"label\":\"Area Planted\",\"value\":\"251 ha\"},{\"label\":\"Plants (Seedlings)\",\"value\":\"188,169\"},{\"label\":\"Bearing Trees\",\"value\":\"94,158\"},{\"label\":\"Avg Production\\/Yr\",\"value\":\"21,988 kg\"},{\"label\":\"Farmers\\/Orgs\",\"value\":\"230\"}]}','DTI Region VI - CoCa data','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(26,29,'Coffee Industry Cluster','stats_grid','2024-2025','{\"stats\":[{\"label\":\"Area Planted\",\"value\":\"9,914.32 ha\"},{\"label\":\"Green Beans Prod\",\"value\":\"2,089.84 MT\"},{\"label\":\"Dried Cherries Prod\",\"value\":\"4,178.68 MT\"},{\"label\":\"Bearing Trees\",\"value\":\"5,879,656\"},{\"label\":\"Avg Yield\",\"value\":\"0.42 MT\\/HA\"},{\"label\":\"Robusta Yield\",\"value\":\"3,376.26 MT\"}],\"modal_details\":{\"Anchor Firms\":[\"Sugar Valley Coffee (Negros Occ)\",\"Coffee Culture Roastery (Negros Occ)\",\"Kape Iloilo\"]}}','DTI Region VI - CoCa data','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(27,30,'Coconut Farmers & Industry Plan','grid','2024-2025','{\"items\":[{\"name\":\"Food Products\",\"details\":\"31 Registrants. Includes: Coco Vinegar (5), Cooking Oil (1), VCO (7), Fresh Coconut (5), Palm Oil (2), Whole nut (2).\"},{\"name\":\"Non-Food Products\",\"details\":\"387 Registrants. Includes: Coconut Lumber (317), Copra Trader (55), Charcoal (1), Coir (2).\"},{\"name\":\"Processors\",\"details\":\"3 Oil Millers\"}]}','PCA Matrix of Registrants (Jan-July 2025)','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(28,31,'Processed Fruits & Nuts Statistics','grid','2024-2025','{\"description\":\"Priority Commodities: Mango, Pineapple, Papaya, Peanut, Banana, Calamansi, Dragon Fruit, Cashew.\",\"items\":[{\"name\":\"Mango\",\"details\":\"179,346 MT Production, 11 Processors\"},{\"name\":\"Banana\",\"details\":\"757,725 MT Production, 163,209 Ha Area, 90 Processors\"},{\"name\":\"Pili Nuts\",\"details\":\"33 MT Production, 271 Ha Area, 1 Processor\"},{\"name\":\"Peanuts\",\"details\":\"7,388 MT Production, 9,224 Ha Area, 37 Processors\"},{\"name\":\"Papaya\",\"details\":\"15,263 MT Production, 3,402 Ha Area, 7 Processors\"},{\"name\":\"Calamansi\",\"details\":\"31 Processors\"}]}','National Processed Fruits and Nuts Roadmap','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(29,32,'Wearables & Homestyle Raw Materials','grid','2024-2025','{\"items\":[{\"name\":\"Aklan\",\"details\":\"Pi\\u00f1a, Abaca, Raffia, Nito, Clay, Bariw, Buri Ramie\"},{\"name\":\"Antique\",\"details\":\"Abaca, Buri, Bamboo, Coco Coir, Semi Precious Stones\"},{\"name\":\"Capiz\",\"details\":\"Bamboo, Shells, Abaca, Agsam Vine, Clay\"},{\"name\":\"Guimaras\",\"details\":\"Pandan, Twined Pi\\u00f1a, Coco Shells, Nito, Coco Coir\"},{\"name\":\"Iloilo\",\"details\":\"Abaca, Bamboo, Clay, Cotton, Shells\"},{\"name\":\"Negros Occ\",\"details\":\"Bamboo, Clay, Coco Shells, Silk, Pandan, Water Lily\"}]}','DTI Region VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(30,33,'IT-BPM Industry Cluster','list','2024-2025','{\"items\":[\"200+ leading companies (Concentrix, Teleperformance, Transcom, iQor)\",\"Goal: Premier location for IT-BPM locators and startups\",\"Programs: Online Slingshot Region 6, Moonshot TNK\",\"50 Assisted Startups (2021-2024)\"]}','DTI Region VI','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(31,99,'Invest Now','cta','2024-2025','{\"title\":\"Ready to Invest\\nin Western Visayas?\",\"description\":\"Contact the Department of Trade and Industry Region 6 for assistance, inquiries, and investment facilitation.\",\"action_text\":\"Contact DTI Region 6\"}',NULL,'2026-02-18 17:53:32','2026-02-18 17:53:32'),
(32,35,'Industry Recovery & Growth Strategies','list','2024-2025','{\"items\":[\"Inclusive and Resilient Tourism Development\",\"Digital Transformation and MSME Empowerment\",\"Creative and Service Sector Promotion\",\"Regional Industrialization and Innovation\",\"Workforce Upskilling and Technology Adoption\"]}','DTI Western Visayas','2026-02-18 17:53:32','2026-02-18 17:53:32'),
(33,0,'Global Settings','metadata','2026-2027','{\"site_title\":\"Western Visayas: Investment and Economic Profile\",\"browser_tab_title\":\"Western Visayas Region 6 Profile\",\"logo_text\":\"DTI Region 6\"}',NULL,'2026-02-18 21:35:18','2026-02-18 21:35:18'),
(34,1,'Title Page','hero','2026-2027','{\"title\":\"Why Invest in\\nWestern Visayas?\",\"subtitle\":\"DEPARTMENT OF TRADE AND INDUSTRY REGION 6\",\"logo\":\"dti-logo.png\",\"highlight_stats\":[{\"label\":\"GRDP GROWTH (2024)\",\"value\":\"40.3%\"},{\"label\":\"GROWING POPULATION\",\"value\":\"4.9M\"}],\"modal_details\":{\"Why Invest in Visayas Logistics Cluster?\":{\"title\":\"Why Invest in Visayas Logistics Cluster?\",\"Points\":[\"Abundant in Natural Resources\",\"Agricultural Potential\",\"Collaborative Environment\",\"Competitive Human Capital\",\"Decongestion of other Areas within PH\",\"Generally Peaceful and Orderly\",\"High Demand for Logistics\",\"High Potential for Economic Growth\",\"Increasing Population\",\"Lack of Logistics Infrastructure\",\"Mitigation of Trade & Manufacturing Paralysis\",\"Presence of Logistics Infrastructure\",\"Strategic Location\",\"Sufficient Power Supply\"]}}}','Supra Regional Consultations, RDC VI, NEDA VI, PSA VI','2026-02-18 21:35:18','2026-02-18 21:35:35'),
(35,2,'Regional Overview','stats_grid','2026-2027','{\"description\":\"Western Visayas or Region VI is located at the center of the Philippine archipelago and lies between two large bodies of water, the Sibuyan Sea and the Visayan Sea.\",\"notable_info\":\"Last June 13, 2024, President Bongbong Marcos signed the Republic Act No. 12000 to established the Negros Island Region (NIR).\",\"stats\":[{\"label\":\"Land Area\",\"value\":\"20,794 sq. km.\"},{\"label\":\"Population (2024)\",\"value\":\"4,861,911\"},{\"label\":\"Density (2024)\",\"value\":\"370 \\/ km2\"},{\"label\":\"Coastal\\/Landlocked\",\"value\":\"Coastal\"}],\"modal_details\":{\"Composition\":{\"Provinces\":\"Aklan, Antique, Capiz, Guimaras, & Iloilo\",\"Cities\":\"3\",\"Municipalities\":\"98\",\"Barangays\":\"3,209\",\"Congressional Districts\":\"10\"},\"Map Labels\":\"Sibuyan Sea, Visayan Sea, BORACAY, AKLAN, KALIBO, CAPIZ, ROXAS CITY, ANTIQUE, ILOILO, ILOILO CITY, SAN JOSE DE BUENAVISTA, GUIMARAS.\"}}','Philippine Statistics Authority, Census of Population 2024','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(36,3,'Partner Firms Marquee','marquee','2026-2027','{\"items\":[\"CONCENTRIX\",\"TELEPERFORMANCE\",\"TRANSCOM\",\"IQOR\",\"REED ELSEVIER\",\"TELUS\",\"WNS\",\"ASURION\",\"SUTHERLAND\"]}',NULL,'2026-02-18 21:35:18','2026-02-18 21:35:18'),
(37,4,'2024 Gross Regional Domestic Product','chart','2026-2027','{\"title\":\"GRDP Growth Rates by Region (2023-2024, %)\",\"categories\":[\"CV (VII)\",\"Caraga (XIII)\",\"CL (III)\",\"Davao (XI)\",\"EV (VIII)\",\"NorMin (X)\",\"NIR\",\"NCR\",\"CALABARZON\",\"SOCCSKSARGEN\",\"CV (II)\",\"Ilocos\",\"Bicol\",\"CAR\",\"MIMAROPA\",\"WV (VI)\",\"Zamboanga\",\"BARMM\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[7.3,6.9,6.5,6.3,6.2,6,5.9,5.59,5.56,5.5,5.3,4.94,4.92,4.8,4.4,4.3,4.2,2.7]}],\"modal_text\":\"In 2024, Central Visayas was the fastest growing region (7.3%). Western Visayas grew by 4.3%.\",\"notable_info\":\"In 2024, Central Visayas was the fastest growing region in the country with 7.3 percent growth.\"}','https://psa.gov.ph/system/files/pad/2024%20GRDP%20Publication.pdf','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(38,5,'Industry Share to GDP','stats_grid','2026-2027','{\"description\":\"The economy of Western Visayas grew by 4.3 percent in 2024, slower than the 6.8 percent growth in 2023. The Western Visayas economy was valued at PhP 641.76 billion (2.9% of the country\'s GDP) at constant 2018 prices.\",\"stats\":[{\"label\":\"2024 Growth\",\"value\":\"4.3%\"},{\"label\":\"Economy Value\",\"value\":\"PhP 641.76 B\"},{\"label\":\"Share to National GDP\",\"value\":\"2.9%\"}]}','PSA GRDP Publication 2024','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(39,6,'Per Capita GDP Growth','chart','2026-2027','{\"title\":\"Per Capita GDP Growth Rate by Region (2023-2024, %)\",\"categories\":[\"PH\",\"NCR\",\"CAR\",\"I\",\"II\",\"III\",\"IVA\",\"MIMAROPA\",\"V\",\"VI (WV)\",\"NIR\",\"VII\",\"VIII\",\"IX\",\"X\",\"XI\",\"XII\",\"XIII\",\"BARMM\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[4.8,5,3.63,4.7,4.6,5.6,4.3,3.64,4,3.62,5.5,6.2,5.4,3.2,5.1,5.3,4.4,5.8,1]}]}','PSA GRDP Publication 2024','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(40,7,'Growth Rates by Industry (2023-2024)','chart','2026-2027','{\"title\":\"Industry Growth Rates (%)\",\"categories\":[\"Professional & Business Services\",\"Electricity, Steam, Water\",\"Human Health & Social Work\",\"Accommodation & Food\",\"Transportation & Storage\",\"Financial & Insurance\",\"Other Services\",\"Wholesale & Retail Trade\",\"Information & Communication\",\"Real Estate\",\"Public Administration\",\"Construction\",\"Mining & Quarrying\",\"Education\",\"Manufacturing\",\"Agriculture, Forestry, Fishing\"],\"series\":[{\"name\":\"Growth Rate %\",\"data\":[13.7,13.52,13.49,10.4,8.6,8,7.6,7.3,6.8,5.3,3.6,3.53,3.48,3.4,2.6,-7.3]}],\"modal_text\":\"Top growth: Professional services (13.7%). Decline: Agriculture (-7.3%).\"}','PSA GRDP Publication 2024','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(41,9,'The 12 Economic Drivers','grid','2026-2027','{\"items\":[{\"name\":\"AGRICULTURE\",\"details\":\"Agencies: DA, DOST, NIA, PCA, PhilFIDA, PhilMEC, SUCs, LGUs\"},{\"name\":\"MARINE & FISHERIES\",\"details\":\"Agencies: BFAR, DOST, SUCs, LGUs\"},{\"name\":\"MSMEs & LARGE MANUFACTURING\",\"details\":\"Agencies: DTI, DOST, LGUs\"},{\"name\":\"IT\\/BPO\\/BPMS\",\"details\":\"Agencies: DTI, DICT and Private Companies\"},{\"name\":\"WHOLESALE AND RETAIL\",\"details\":\"Agencies: DTI, LGUs\"},{\"name\":\"TOURISM\",\"details\":\"Agencies: DOT, DTI, LGUs\"},{\"name\":\"PROPERTY DEVELOPMENT\",\"details\":\"Agencies: DHSUD, LGUs\"},{\"name\":\"CONSTRUCTION\",\"details\":\"Agencies: DPWH, LGUs\"},{\"name\":\"HOUSING\",\"details\":\"Agencies: DepEd, CHED, DOH, DSWD, LGUs, NHA\"},{\"name\":\"FINANCIAL INSTITUTIONS\",\"details\":\"Agencies: BSP\"},{\"name\":\"PORT OPERATIONS\",\"details\":\"Agencies: PPA, CAAP, LGUs, Marina\"},{\"name\":\"TRANSPORTATION\",\"details\":\"Agencies: LTFRB, LGUs\"}]}','NEDA Region VI','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(42,10,'DTI Business Name Registration','stats_grid','2026-2027','{\"description\":\"Business Name Registration in Western Visayas (2022 - September 4, 2025). Total: 245,236.\",\"stats\":[{\"label\":\"2022 Total\",\"value\":\"56,135\"},{\"label\":\"2024 Total\",\"value\":\"71,289\"},{\"label\":\"2025 (Partial)\",\"value\":\"52,187\"},{\"label\":\"Total Transactions\",\"value\":\"245,236\"}],\"modal_details\":{\"Transaction Method\":{\"Online\":\"173,060 (70.57%)\",\"Hybrid\":\"45,188 (18.43%)\",\"Walkin\":\"26,988 (11%)\"},\"Gender Distribution\":{\"Women\":\"155,723 (63.5%)\",\"Men\":\"89,513 (36.5%)\"},\"Territorial Scope\":{\"Barangay\":\"162,943\",\"City\\/Prov\":\"53,490\",\"Regional\":\"19,096\"}}}','https://bnrs.dti.gov.ph/resources/bn-statistics','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(43,11,'Establishments in Operation (2021-2023)','chart','2026-2027','{\"title\":\"Number of Establishments by Province\",\"categories\":[\"Aklan\",\"Antique\",\"Capiz\",\"Guimaras\",\"Iloilo (inc City)\",\"Negros Occ (inc Bacolod)\"],\"series\":[{\"name\":\"2021\",\"data\":[6399,4304,7958,1407,23230,30417]},{\"name\":\"2022\",\"data\":[6737,4380,8220,1487,24148,30776]},{\"name\":\"2023\",\"data\":[8907,5719,9533,1890,26395,33200]}]}','PSA Region 6 Special Release - Reference No. 2025-SR18','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(44,12,'Establishment Size Distribution (2023)','grid','2026-2027','{\"items\":[{\"name\":\"Large (223)\",\"details\":\"Bacolod City (35.9%), Iloilo City (27.4%), Negros Occ (13.9%), Iloilo (8.1%), Aklan (6.3%), Capiz (5.8%), Antique (2.2%), Guimaras (0.4%)\"},{\"name\":\"Medium (239)\",\"details\":\"Bacolod City (27.6%), Negros Occ (22.2%), Iloilo City (20.5%), Aklan (13.8%), Iloilo (9.6%), Capiz (4.2%), Antique (1.7%), Guimaras (0.4%)\"},{\"name\":\"Small (6,791)\",\"details\":\"Bacolod City (24.0%), Iloilo City (18.7%), Negros Occ (17.3%), Iloilo (14.7%), Aklan (12.3%), Capiz (7.6%), Antique (4.0%), Guimaras (1.4%)\"},{\"name\":\"Micro (78,391)\",\"details\":\"Negros Occ (24.2%), Iloilo (21.2%), Bacolod (14.3%), Capiz (11.5%), Aklan (10.2%), Iloilo City (9.4%), Antique (6.9%), Guimaras (2.3%)\"}]}','PSA Region 6 Special Release','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(45,13,'Total Employment (2021-2023)','chart','2026-2027','{\"title\":\"Employment by Province\",\"categories\":[\"Aklan\",\"Antique\",\"Capiz\",\"Guimaras\",\"Iloilo (inc City)\",\"Negros Occ (inc Bacolod)\"],\"series\":[{\"name\":\"2021\",\"data\":[30841,20256,32406,4828,146410,209600]},{\"name\":\"2022\",\"data\":[32996,19851,34791,5201,150969,207238]},{\"name\":\"2023\",\"data\":[51452,25451,42683,6951,165833,237824]}]}','PSA Region 6 Special Release','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(46,14,'Employment Distribution by Size (2023)','grid','2026-2027','{\"description\":\"Distribution of total employment across establishment sizes per province.\",\"items\":[{\"name\":\"Large (124,511)\",\"details\":\"Bacolod (45.5%), Iloilo City (24.4%), Negros Occ (10.1%), Iloilo (8.7%), Capiz (3.8%), Antique (3.9%), Aklan (3.3%), Guimaras (0.3%)\"},{\"name\":\"Medium (32,546)\",\"details\":\"Bacolod (27.8%), Negros Occ (21.7%), Iloilo City (20.8%), Aklan (14.1%), Iloilo (9.4%), Capiz (4.0%), Antique (1.7%), Guimaras (0.4%)\"},{\"name\":\"Small (146,564)\",\"details\":\"Bacolod (25.0%), Negros Occ (19.0%), Iloilo City (18.7%), Iloilo (12.9%), Aklan (12.4%), Capiz (7.5%), Antique (3.4%), Guimaras (1.2%)\"},{\"name\":\"Micro (226,573)\",\"details\":\"Negros Occ (23.4%), Iloilo (20.1%), Bacolod (15.5%), Capiz (11.3%), Aklan (10.8%), Iloilo City (10.1%), Antique (6.7%), Guimaras (2.1%)\"}]}','PSA Region 6 Special Release','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(47,16,'Higher Education Institutions (HEIs)','stats_grid','2026-2027','{\"stats\":[{\"label\":\"Total HEIs\",\"value\":\"102\"},{\"label\":\"Graduates\",\"value\":\"20,391\"},{\"label\":\"Public (SUCs\\/LUCs)\",\"value\":\"53\"},{\"label\":\"Private\",\"value\":\"49\"}],\"modal_details\":{\"Breakdown by Location\":{\"Iloilo City\":\"29 (Public: 3, Private: 26)\",\"Iloilo\":\"27 (Public: 23, Private: 4)\",\"Capiz\":\"17 (Public: 9, Private: 8)\",\"Aklan\":\"16 (Public: 9, Private: 7)\",\"Antique\":\"9 (Public: 6, Private: 3)\",\"Guimaras\":\"4 (Public: 3, Private: 1)\"}}}','CHED - Statistical Bulletin 2024-2025','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(48,17,'HEI Distribution by Discipline','chart','2026-2027','{\"title\":\"Institutional Type by Discipline\",\"categories\":[\"Education Science\",\"Business Admin\",\"Engineering & Tech\",\"IT-Related\",\"Agriculture\\/Forestry\",\"Medical & Allied\",\"Social Sciences\",\"Service Trades\",\"Natural Science\",\"Humanities\",\"Maritime\",\"Mathematics\",\"Mass Comm\",\"Religion\",\"Architecture\",\"Fine Arts\",\"Law\",\"Home Economics\",\"Other\"],\"series\":[{\"name\":\"Public\",\"data\":[345,126,152,60,93,8,14,13,19,15,2,11,8,0,5,2,1,4,20]},{\"name\":\"Private\",\"data\":[156,160,41,42,2,58,20,19,12,13,20,0,3,9,2,5,5,0,20]}]}','CHED - Statistical Bulletin','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(49,19,'Transportation Infrastructure','grid','2026-2027','{\"items\":[{\"name\":\"9 Airports\",\"details\":\"6 CAAP-operated, 3 Private (Sipalay, Sicogon, Semirara).\",\"modal_details\":{\"Map Points\":[{\"label\":\"Iloilo International Airport\",\"lat\":10.83,\"lng\":122.54},{\"label\":\"Bacolod-Silay Airport\",\"lat\":10.77,\"lng\":123.01},{\"label\":\"Kalibo International Airport\",\"lat\":11.69,\"lng\":122.38},{\"label\":\"Roxas Airport\",\"lat\":11.6,\"lng\":122.75},{\"label\":\"Antique Airport\",\"lat\":10.74,\"lng\":121.93},{\"label\":\"Godofredo P. Ramos Airport (Caticlan)\",\"lat\":11.92,\"lng\":121.95},{\"label\":\"Sipalay Airport\",\"lat\":9.78,\"lng\":122.46},{\"label\":\"Sicogon Airport\",\"lat\":11.45,\"lng\":123.25},{\"label\":\"Semirara Airport\",\"lat\":12.05,\"lng\":121.37}]}},{\"name\":\"152 Ports\",\"details\":\"49 Fishing, 69 Private Commercial, 23 Public, 11 Feeder.\",\"modal_details\":{\"Map Points\":[{\"label\":\"Iloilo Commercial Port Complex\",\"lat\":10.7,\"lng\":122.57},{\"label\":\"Port of Dumangas\",\"lat\":10.81,\"lng\":122.71},{\"label\":\"Port of Estancia\",\"lat\":11.45,\"lng\":123.15},{\"label\":\"Port of Culasi (Roxas)\",\"lat\":11.61,\"lng\":122.72},{\"label\":\"Port of Caticlan\",\"lat\":11.93,\"lng\":121.95},{\"label\":\"Port of San Jose (Antique)\",\"lat\":10.74,\"lng\":121.93},{\"label\":\"Jordan Wharf (Guimaras)\",\"lat\":10.66,\"lng\":122.58},{\"label\":\"Bacolod Real Estate Development Corp. (BREDCO) Port\",\"lat\":10.67,\"lng\":122.94},{\"label\":\"Banago Port (Bacolod)\",\"lat\":10.69,\"lng\":122.95},{\"label\":\"Pulupandan Port\",\"lat\":10.52,\"lng\":122.79}]}}]}','CAAP / Wikipedia / WV RSET','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(50,20,'Telecommunications','stats_grid','2026-2027','{\"stats\":[{\"label\":\"Cell Towers\",\"value\":\"1,027\"},{\"label\":\"Wi-Fi Hotspots\",\"value\":\"293\"},{\"label\":\"Fiber-optic\",\"value\":\"20\"}]}','DICT Region VI','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(51,22,'Operating PEZA Sites','stats_grid','2026-2027','{\"stats\":[{\"label\":\"Total\",\"value\":\"23\"},{\"label\":\"Bacolod City\",\"value\":\"12\"},{\"label\":\"Iloilo City\",\"value\":\"6\"},{\"label\":\"Negros Occ\",\"value\":\"3\"}],\"modal_details\":{\"Others\":\"Aklan: 1, Capiz: 1\"}}','PEZA (Feb 2023)','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(52,23,'Logistics Investment Opportunities','list','2026-2027','{\"items\":[\"Seaport, Airport, Railway\",\"Warehouse, Cold Storage, Trucking Facility\",\"Agri Terminal, Food Terminal, Bagsakan Center\",\"Processing Plant, Packaging Plant\",\"ICT Infrastructure, Economic Zone\",\"Roads and Bridges\"]}','VIZ Logistics Cluster','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(53,24,'Why Invest in Visayas Logistics?','list','2026-2027','{\"items\":[\"Abundant Natural Resources & Agricultural Potential\",\"Strategic Location & Collaborative Environment\",\"Competitive Human Capital\",\"High Demand for Logistics & Economic Growth Potential\",\"Presence of Infrastructure & Sufficient Power Supply\",\"Generally Peaceful and Orderly\"]}','VIZ Logistics Cluster','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(54,25,'Priority Industries by Province','grid','2026-2027','{\"items\":[{\"name\":\"ILOILO\",\"details\":\"Tourism, Processed Food, IT-BPM\"},{\"name\":\"GUIMARAS\",\"details\":\"Fruits (Mangoes), Nuts (Cashews)\"},{\"name\":\"ANTIQUE\",\"details\":\"Bamboo, Processed Food (Kalamay)\"},{\"name\":\"AKLAN\",\"details\":\"Wearables (Pi\\u00f1a), Tourism (Boracay), Processed Food\"},{\"name\":\"CAPIZ\",\"details\":\"Aquamarine (Seafood), IT-BPM\"},{\"name\":\"NEGROS OCC\",\"details\":\"Sugar, Wearables, IT-BPM, Processed Food\"}]}','DTI Western Visayas','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(55,26,'DTI 6 Priority Industries','grid','2026-2027','{\"items\":[{\"name\":\"Coffee\",\"details\":\"9,914 ha Area Planted, 2,090 MT Production\"},{\"name\":\"Cacao\",\"details\":\"1,048 ha Farm Area, 21,988 kg Avg Production\"},{\"name\":\"Processed Fruits & Nuts\",\"details\":\"Mango, Banana, Pineapple, Peanut, Papaya, Calamansi\"},{\"name\":\"Coconut\",\"details\":\"Food (VCO, Vinegar) & Non-Food (Lumber, Copra) Products\"},{\"name\":\"Bamboo\",\"details\":\"25,535 ha Planted, 9 SSFs, 5 Anchor Firms\"},{\"name\":\"Wearables & Homestyle\",\"details\":\"Pi\\u00f1a, Abaca, Raffia (Aklan, Iloilo)\"},{\"name\":\"IT-BPM\",\"details\":\"200+ Companies, 50 Assisted Startups\"}]}','DTI Region VI','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(56,27,'Bamboo Industry Statistics','grid','2026-2027','{\"description\":\"Major industry sector. 9 SSFs, 5 Anchor Firms, 25,535.85 ha planted (as of Sept 2022).\",\"items\":[{\"name\":\"Yearly Area Planted (Ha.)\",\"details\":\"2013: 74, 2014: 125, 2015: 274.35, 2017: 4063.5, 2018: 1068, 2019: 50, 2020: 12847, 2021: 4714, 2022: 1320\"}]}','DTI Region VI - Annual Report','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(57,28,'Cacao Industry Cluster','stats_grid','2026-2027','{\"stats\":[{\"label\":\"Total Farm Area\",\"value\":\"1,048.48 ha\"},{\"label\":\"Area Planted\",\"value\":\"251 ha\"},{\"label\":\"Plants (Seedlings)\",\"value\":\"188,169\"},{\"label\":\"Bearing Trees\",\"value\":\"94,158\"},{\"label\":\"Avg Production\\/Yr\",\"value\":\"21,988 kg\"},{\"label\":\"Farmers\\/Orgs\",\"value\":\"230\"}]}','DTI Region VI - CoCa data','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(58,29,'Coffee Industry Cluster','stats_grid','2026-2027','{\"stats\":[{\"label\":\"Area Planted\",\"value\":\"9,914.32 ha\"},{\"label\":\"Green Beans Prod\",\"value\":\"2,089.84 MT\"},{\"label\":\"Dried Cherries Prod\",\"value\":\"4,178.68 MT\"},{\"label\":\"Bearing Trees\",\"value\":\"5,879,656\"},{\"label\":\"Avg Yield\",\"value\":\"0.42 MT\\/HA\"},{\"label\":\"Robusta Yield\",\"value\":\"3,376.26 MT\"}],\"modal_details\":{\"Anchor Firms\":[\"Sugar Valley Coffee (Negros Occ)\",\"Coffee Culture Roastery (Negros Occ)\",\"Kape Iloilo\"]}}','DTI Region VI - CoCa data','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(59,30,'Coconut Farmers & Industry Plan','grid','2026-2027','{\"items\":[{\"name\":\"Food Products\",\"details\":\"31 Registrants. Includes: Coco Vinegar (5), Cooking Oil (1), VCO (7), Fresh Coconut (5), Palm Oil (2), Whole nut (2).\"},{\"name\":\"Non-Food Products\",\"details\":\"387 Registrants. Includes: Coconut Lumber (317), Copra Trader (55), Charcoal (1), Coir (2).\"},{\"name\":\"Processors\",\"details\":\"3 Oil Millers\"}]}','PCA Matrix of Registrants (Jan-July 2025)','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(60,31,'Processed Fruits & Nuts Statistics','grid','2026-2027','{\"description\":\"Priority Commodities: Mango, Pineapple, Papaya, Peanut, Banana, Calamansi, Dragon Fruit, Cashew.\",\"items\":[{\"name\":\"Mango\",\"details\":\"179,346 MT Production, 11 Processors\"},{\"name\":\"Banana\",\"details\":\"757,725 MT Production, 163,209 Ha Area, 90 Processors\"},{\"name\":\"Pili Nuts\",\"details\":\"33 MT Production, 271 Ha Area, 1 Processor\"},{\"name\":\"Peanuts\",\"details\":\"7,388 MT Production, 9,224 Ha Area, 37 Processors\"},{\"name\":\"Papaya\",\"details\":\"15,263 MT Production, 3,402 Ha Area, 7 Processors\"},{\"name\":\"Calamansi\",\"details\":\"31 Processors\"}]}','National Processed Fruits and Nuts Roadmap','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(61,32,'Wearables & Homestyle Raw Materials','grid','2026-2027','{\"items\":[{\"name\":\"Aklan\",\"details\":\"Pi\\u00f1a, Abaca, Raffia, Nito, Clay, Bariw, Buri Ramie\"},{\"name\":\"Antique\",\"details\":\"Abaca, Buri, Bamboo, Coco Coir, Semi Precious Stones\"},{\"name\":\"Capiz\",\"details\":\"Bamboo, Shells, Abaca, Agsam Vine, Clay\"},{\"name\":\"Guimaras\",\"details\":\"Pandan, Twined Pi\\u00f1a, Coco Shells, Nito, Coco Coir\"},{\"name\":\"Iloilo\",\"details\":\"Abaca, Bamboo, Clay, Cotton, Shells\"},{\"name\":\"Negros Occ\",\"details\":\"Bamboo, Clay, Coco Shells, Silk, Pandan, Water Lily\"}]}','DTI Region VI','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(62,33,'IT-BPM Industry Cluster','list','2026-2027','{\"items\":[\"200+ leading companies (Concentrix, Teleperformance, Transcom, iQor)\",\"Goal: Premier location for IT-BPM locators and startups\",\"Programs: Online Slingshot Region 6, Moonshot TNK\",\"50 Assisted Startups (2021-2024)\"]}','DTI Region VI','2026-02-18 21:35:18','2026-02-18 21:35:18'),
(63,99,'Invest Now','cta','2026-2027','{\"title\":\"Ready to Invest\\nin Western Visayas?\",\"description\":\"Contact the Department of Trade and Industry Region 6 for assistance, inquiries, and investment facilitation.\",\"action_text\":\"Contact DTI Region 6\"}',NULL,'2026-02-18 21:35:18','2026-02-18 21:35:18'),
(64,35,'Industry Recovery & Growth Strategies','list','2026-2027','{\"items\":[\"Inclusive and Resilient Tourism Development\",\"Digital Transformation and MSME Empowerment\",\"Creative and Service Sector Promotion\",\"Regional Industrialization and Innovation\",\"Workforce Upskilling and Technology Adoption\"]}','DTI Western Visayas','2026-02-18 21:35:18','2026-02-18 21:35:18');
/*!40000 ALTER TABLE `project_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES
('14O4TNGk0glpgcU89weuIKtwczfYzc1orTRuRsst',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmpGdkVPM2p1eTlUcjlGdktiaUtJMDNGdnVtMWx6Z3lmcEtVNFNJcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771210348),
('1jVvhuqcbpZwQ7EjuMzUTSqZUHiRm5aEVnZLejFa',NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFZNRUdYdUp4MU1vVkg1YlJyNlRqb1dhNXIxTDBDMm0yTmhpSjQyTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771491503),
('9cbYeUyZd1r14WO3XzjQHNPpN8SGfA6QLG84Y3De',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczlqanIzMmp0dlJnc1hQMTFZbkxpN2JQWUNIZlpMYlBTdlJsbXFXNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9fQ==',1771470713),
('cSgaemUDhBND4UvHVHwKod4uzMZJhzDCT95z1MXB',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidVMzY0ZuVjFYcmhlR1liWUdVTnFObHNpc005dFpaQWdnMkE5bDJlNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb3dubG9hZC1wcm9maWxlLzIwMjQtMjAyNSI7czo1OiJyb3V0ZSI7Tjt9fQ==',1771405934),
('GX3XjJGdn2M68Xb57Rs66ngVIzjqm0s5RauXBjv6',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWQwMDBXeXNVdFd0WTMwcGk2Z2hiTnhLTldhZzRxRGJ2STJDRUJJVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/eWVhcj0yMDI0LTIwMjUiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771213943),
('M3ttakTa6Qp74SHVTw5pnuLX1U6v2d62ARDEJ2Kw',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:147.0) Gecko/20100101 Firefox/147.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3ZZaW41Z3NuMWZ3Z1o4RHJyZHZpckh2UlVOQzFVdk4zcEw5cUg0diI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/eWVhcj0yMDI2LTIwMjciO3M6NToicm91dGUiO047fX0=',1771479393),
('T1NQKaUjtHnQ0us4P4bmtmPpYOQz0ppOnAaAu5ZS',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkgxTzBFcGlpbldoamEyOHQxQzFLZjhzemJrOG85RWEwcUJrekRTUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771395284);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin User','admin@example.com',NULL,'$2y$12$/QZWwsLj56.5Cl4tMvcGGuSsZ7yW93OeHyxvmUfKxsnVIE1yBtCpW',NULL,'2026-02-17 21:24:06','2026-02-18 21:34:10');
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

-- Dump completed on 2026-02-25  7:37:55
