-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: u824589774_bikgrup
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `title_tr` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `excerpt_tr` text DEFAULT NULL,
  `excerpt_en` text DEFAULT NULL,
  `content_tr` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'BİKGRUP Basın & Medya',
  `read_time` varchar(30) DEFAULT '4 dk',
  `views` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (2,'surdurulebilir-tarim-ve-gelecegin-gida-guvenligi','Sürdürülebilir Tarım: Doğaya Saygılı Üretim ve Biyolojik Mücadele','Sustainable Agriculture: Ecological Farming & Biological Pest Control','Toprağın verimliliğini nesiller boyu korumak adına kimyasal kullanımını minimize eden biyolojik mücadele yöntemlerimiz.','Minimizing chemical intervention through innovative biological pest control to nurture soil fertility for generations to come.','<p>Modern tarımda sürdürülebilirlik bir tercih değil, geleceğe borcumuzdur. BİK GRUP anlaşmalı çiftçi ağımızda ve kendi bahçelerimizde zararlılarla mücadelede yararlı böcekler ve feromon tuzakları kullanılmaktadır. Bu sayede sıfır kalıntı hedefimize %100 oranında ulaşıyoruz.</p>','<p>Sustainability in modern agriculture is an indispensable commitment to our future. In our orchards and partner grower networks, beneficial insects and pheromone traps are integrated into pest management, achieving zero residue targets.</p>','blog_agriculture_hero.jpg','BİKGRUP Basın & Medya','5 dk',4,1,'2026-10-30 10:01:17'),(3,'turk-meyve-ve-sebzesinin-avrupadaki-yukselisi','Türk Narenciyesi ve Taze Meyvesinin Avrupa Pazarındaki Yükselişi','The Ascendance of Turkish Citrus and Fresh Produce Across European Markets','Almanya, İngiltere ve İskandinav ülkelerinde Türk narenciyesine artan talep ve kalite sertifikasyonlarımızın getirdiği avantajlar.','Surging demand for Turkish citrus across Germany, the UK, and Scandinavia backed by our rigorous certifications.','<p>Avrupa Birliği standartlarında taze meyve ithalatı, en yüksek hijyen ve izlenebilirlik kriterlerini zorunlu kılmaktadır. BRCGS Food, IFS ve GlobalG.A.P. belgelerimiz ile BİK GRUP, Avrupa\'nın önde gelen süpermarket zincirlerinin doğrudan onaylı tedarikçisidir.</p>','<p>Fresh fruit import into European markets demands strict compliance with supreme hygiene and traceability benchmarks. With our GlobalG.A.P., IFS, and BRCGS certifications, BİKGRUP serves as an accredited direct supplier to leading retail chains.</p>','citrus_showcase.jpg','BİKGRUP Basın & Medya','3 dk',11,1,'2026-10-30 10:01:17'),(4,'test-blog-crud','Test Blog Başlık','Test Blog Title','Özet','','İçerik','','facility_automation.jpg','BİKGRUP','3 dk',2,1,'2026-10-30 13:45:11');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `name_tr` varchar(100) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `description_tr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `badge_color` varchar(20) DEFAULT '#15803d',
  `icon_svg` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'narenciye','Narenciye','Citrus','Akdeniz güneşinin en kaliteli portakal, limon, mandalina ve greyfurtları. Yüksek C vitamini, doğal sululuk ve ferahlatıcı lezzet.','The finest oranges, lemons, mandarins, and grapefruits kissed by Mediterranean sunshine.','#ea580c',NULL,'category_narenciye.png',2,1,'2026-10-30 10:01:17'),(2,'meyveler','Meyveler','Fresh Fruits','Akdeniz ve Ege\'nin bereketli bahçelerinden dalından sofranıza taze elma, karpuz, incir, çilek ve üzüm çeşitleri.','Fresh apples, watermelons, figs, strawberries, and grapes picked at peak sweetness.','#dc2626',NULL,'category_meyveler.png',3,1,'2026-10-30 10:01:17'),(3,'sebzeler','Sebzeler','Fresh Vegetables','Verimli tarım arazilerinde kontrollü tarım prensipleriyle yetiştirilen taze domates, biber, patlıcan ve çıtır salatalıklar.','Crispy cucumbers, premium tomatoes, peppers, and eggplants grown with precision farming.','#15803d',NULL,'category_sebzeler.png',4,1,'2026-10-30 10:01:17'),(4,'hububat','Hububat','Grains & Cereals','Bereketli Çukurova ve İç Anadolu ovalarından yüksek kaliteli buğday, mısır, ayçekirdeği ve soya fasulyesi.','High-grade wheat, corn, sunflower seeds, and soybeans sustainably harvested from fertile lands.','#15803d',NULL,NULL,1,1,'2026-11-04 06:32:44'),(5,'sivi-gubre','Sıvı Gübre','Liquid Fertilizers','Tarımsal üretimde verim ve kaliteyi artıran, bitkinin ihtiyaç duyduğu makro ve mikro besin elementlerini hızlıca sağlayan yüksek kaliteli sıvı gübre çeşitleri.','High-quality liquid fertilizer formulations designed to boost agricultural yield and provide essential nutrients directly and efficiently to crops.','#0284c7',NULL,NULL,5,1,'2026-11-12 10:30:00'),(6,'kati-gubre','Katı Gübre','Solid Fertilizers','Toprağın organik yapısını zenginleştiren, dengeli ve uzun süreli besleme sağlayan taban ve üst katı gübre çözümleri.','High-performance solid fertilizers and soil conditioners ensuring balanced, long-lasting plant nutrition and enriched soil structure.','#16a34a',NULL,NULL,6,1,'2026-11-12 10:30:00');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_items`
--

DROP TABLE IF EXISTS `gallery_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `title_tr` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT '',
  `category` varchar(100) DEFAULT 'genel',
  `image_path` varchar(255) DEFAULT '',
  `video_url` varchar(500) DEFAULT '',
  `video_file` varchar(255) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_items`
--

LOCK TABLES `gallery_items` WRITE;
/*!40000 ALTER TABLE `gallery_items` DISABLE KEYS */;
INSERT INTO `gallery_items` VALUES (8,'image','İnci Fresh İhracat Kalite Limon Kasalama','İnci Fresh Export Quality Lemon Cracking','paketleme','bikgrup-inci-fresh-ihracat-limon-kasalari-01.webp','','',1,1,'2026-11-04 11:31:20'),(9,'image','Özel İhracat Sandıklarında Taze Limon Paletleri','Fresh Lemon Pallets in Special Export Crates','paketleme','bikgrup-yesil-ihracat-sandigi-limon-paletleri-02.webp','','',2,1,'2026-11-04 11:31:22'),(10,'image','Modern Paketleme Tesisi ve İstifleme Sahası','Modern Packaging Facility and Stacking Area','tesisler','bikgrup-modern-paketleme-tesisi-istif-alani-03.webp','','',3,1,'2026-11-04 11:31:23'),(11,'image','Dalından Taze Yapraklı Mandalina İhracat Kasası','Export Crate of Tangerine with Fresh Leaves','urunler','bikgrup-yaprakli-taze-mandalina-ihracat-kasasi-04.webp','','',4,1,'2026-11-04 11:31:24'),(12,'image','Seçkin Mandalina Kasaları ve Sevkiyat Düzeni','Selected Tangerine Crates and Shipping Order','paketleme','bikgrup-seckin-mandalina-kasalari-sevkiyat-05.webp','','',5,1,'2026-11-04 11:31:25'),(13,'image','Birinci Sınıf Sarı Limon Kasalama ve Düzeni','First Class Yellow Lemon Crating and Layout','urunler','bikgrup-sari-limon-kasalama-ve-duzen-06.webp','','',6,1,'2026-11-04 11:31:26'),(14,'image','Tesis İçi Narenciye Kasaları ve Paletleme','On-Site Citrus Crates and Palletizing','tesisler','bikgrup-tesis-ici-narenciye-kasalari-paletleme-07.webp','','',7,1,'2026-11-04 11:31:27'),(15,'image','İhracat Standartlarında Taze Portakal Kasaları','Fresh Orange Crates at Export Standards','urunler','bikgrup-ihracat-portakal-kasalari-secim-08.webp','','',8,1,'2026-11-04 11:31:28'),(16,'image','Akdeniz Bahçelerinden Taze Mandalina Paketleme','Packaging of Fresh Tangerines from Mediterranean Gardens','paketleme','bikgrup-akdeniz-mandalina-kasalari-paketleme-09.webp','','',9,1,'2026-11-04 11:31:30'),(17,'image','Hasat Sonrası Birinci Sınıf Taze Mandalina','First Class Fresh Tangerine After Harvest','hasat','bikgrup-dalindan-taze-hasat-mandalina-10.webp','','',10,1,'2026-11-04 11:31:31'),(18,'image','Ahşap İhracat Kasasında Özel Mandalina Düzeni','Special Arrangement of Tangerines in Wooden Export Case','paketleme','bikgrup-ahsap-ihracat-kasasinda-mandalina-11.webp','','',11,1,'2026-11-04 11:31:32'),(19,'image','Özel Kasalarda İhracata Hazır Taze Mandalina','Fresh Tangerines Ready for Export in Special Crates','urunler','bikgrup-kirmizi-kasalarda-taze-mandalina-12.webp','','',12,1,'2026-11-04 11:31:34'),(20,'image','Avrupa Standartlarında İhracat Domates Paketleme','Export Tomato Packaging in European Standards','paketleme','bikgrup-avrupa-standartlarinda-domates-paketleme-13.webp','','',13,1,'2026-11-04 11:31:35'),(21,'image','Seçkin Salkım ve Sofralık Domates Kasaları','Seçkin Cluster and Table Tomato Crates','urunler','bikgrup-salkim-ve-sofralik-domates-kasalari-14.webp','','',14,1,'2026-11-04 11:31:37'),(22,'image','Narenciye Paketleme Hattı ve İstifleme Sahası','Citrus Packaging Line and Stacking Area','tesisler','bikgrup-narenciye-paketleme-hatti-istifleme-15.webp','','',15,1,'2026-11-04 11:31:38'),(23,'image','Ahşap Kasalama ile Doğal Tazelik Koruması','Natural Freshness Preservation with Wooden Crating','paketleme','bikgrup-ahsap-kasa-ihracatlik-mandalina-16.webp','','',16,1,'2026-11-04 11:31:38'),(24,'image','Mavi İhracat Kasalarında Özenle Seçilmiş Narenciye','Carefully Selected Citrus Fruits in Blue Export Crates','urunler','bikgrup-mavi-ihracat-kasasinda-narenciye-17.webp','','',17,1,'2026-11-04 11:31:40'),(25,'image','İhracat İçin Hazırlanan Taze Sebze Kasaları','Fresh Vegetable Crates Prepared for Export','paketleme','bikgrup-ihracat-icin-hazirlanan-taze-sebzeler-18.webp','','',18,1,'2026-11-04 11:31:41'),(26,'image','Uluslararası Taşıma Uyumlu Ahşap Kasa Dizilimi','Wooden Case Arrangement Compatible with International Transportation','lojistik','bikgrup-ahsap-kasa-meyve-tasima-duzeni-19.webp','','',19,1,'2026-11-04 11:31:43'),(27,'image','Siyah Kasalarda Kalibre Edilmiş Seçkin Mandalina','Elite Tangerine Calibrated in Black Cases','paketleme','bikgrup-siyah-kasalarda-kalibre-mandalina-20.webp','','',20,1,'2026-11-04 11:31:44'),(28,'image','Tesis İçi Kasa İstifleme ve Kalite Kontrol','On-site Case Stacking and Quality Control','tesisler','bikgrup-tesis-istif-alani-kasalama-21.webp','','',21,1,'2026-11-04 11:31:45'),(29,'image','Yüksek Kalite Standartlarında Narenciye Kasaları','Citrus Crates with High Quality Standards','urunler','bikgrup-yuksek-kalite-narenciye-kasalari-22.webp','','',22,1,'2026-11-04 11:31:46'),(30,'image','Ahşap Kasada Parlak ve Doğal Akdeniz Mandalinası','Bright and Natural Mediterranean Tangerine in Wooden Case','hasat','bikgrup-ahsap-kasada-parlak-mandalinalar-23.webp','','',23,1,'2026-11-04 11:31:47'),(31,'image','Uluslararası Lojistik İçin Paletlenmiş Kasalar','Palletized Crates for International Logistics','lojistik','bikgrup-uluslararasi-lojistik-kasa-paletleri-24.webp','','',24,1,'2026-11-04 11:31:49'),(32,'image','Mavi İhracat Kasalarında Kalibre Portakal Düzeni','Caliber Orange Arrangement in Blue Export Crates','urunler','bikgrup-mavi-ihracat-kasasinda-portakal-25.webp','','',25,1,'2026-11-04 11:31:49'),(33,'image','Taze Hasat Edilmiş Doğal Yapraklı Mandalina','Freshly Harvested Natural Leaf Tangerine','hasat','bikgrup-taze-hasat-yaprakli-mandalina-26.webp','','',26,1,'2026-11-04 11:31:51'),(34,'image','Soğuk Hava Deposunda Düzenli Kasa İstifleri','Neat Stacks of Crates in Cold Storage','lojistik','bikgrup-soguk-hava-deposu-kasa-istifleri-27.webp','','',27,1,'2026-11-04 11:31:52'),(35,'image','Ahşap Kasada Dalından Yeni Toplanmış Mandalina','Freshly Picked Tangerines in a Wooden Crate','hasat','bikgrup-ahsap-kasada-dalindan-taze-mandalina-28.webp','','',28,1,'2026-11-04 11:31:54'),(36,'image','Seçkin Narenciye Kasalama ve Ambalaj Tesisi','Seçkin Citrus Crating and Packaging Facility','paketleme','bikgrup-seckin-narenciye-kasalama-tesisi-29.webp','','',29,1,'2026-11-04 11:31:55'),(37,'image','Kırmızı İhracat Kasalarında Olgunlaşmış Mandalina','Ripe Tangerines in Red Export Crates','urunler','bikgrup-kirmizi-kasalarda-turuncu-mandalina-30.webp','','',30,1,'2026-11-04 11:31:56'),(38,'video','Otomasyonlu Limon Boylama ve Taşıma Hattı','Automated Lemon Sorting and Transport Line','tesisler','bikgrup-inci-fresh-ihracat-limon-kasalari-01.webp','','bikgrup-limon-boylama-ve-tasima-hatti-video-01.mp4',31,1,'2026-11-04 11:31:57'),(39,'video','İnci Fresh İhracat Limon Paletleme Süreci','İnci Fresh Export Lemon Palletizing Process','paketleme','bikgrup-yesil-ihracat-sandigi-limon-paletleri-02.webp','','bikgrup-inci-fresh-limon-paletleme-video-02.mp4',32,1,'2026-11-04 11:31:58'),(40,'video','Yüksek Kapasiteli Tesis İçi Kasa İstifleme','High Capacity In-Plant Case Stacking','tesisler','bikgrup-modern-paketleme-tesisi-istif-alani-03.webp','','bikgrup-tesis-ici-narenciye-istifleme-video-03.mp4',33,1,'2026-11-04 11:31:59'),(41,'video','Narenciye Kalite Kontrol ve Seçme Bandı','Citrus Quality Control and Selection Band','tesisler','bikgrup-sari-limon-kasalama-ve-duzen-06.webp','','bikgrup-sarartma-ve-kalite-secim-video-04.mp4',34,1,'2026-11-04 11:32:00'),(42,'video','Taze Mandalina Kasalama ve Paketleme Operasyonu','Fresh Tangerine Crating and Packaging Operation','paketleme','bikgrup-yaprakli-taze-mandalina-ihracat-kasasi-04.webp','','bikgrup-mandalina-kasalama-operasyonu-video-05.mp4',35,1,'2026-11-04 11:32:01'),(43,'video','Uluslararası Sevkiyat Öncesi Palet Hazırlığı','Pallet Preparation Before International Shipment','lojistik','bikgrup-tesis-ici-narenciye-kasalari-paletleme-07.webp','','bikgrup-ihracat-palet-hazirligi-video-06.mp4',36,1,'2026-11-04 11:32:02'),(44,'video','Yüksek Hızlı Boylama ve Sınıflandırma Hattı','High Speed ​​Grading and Grading Line','tesisler','bikgrup-ihracat-portakal-kasalari-secim-08.webp','','bikgrup-hizli-boylama-ve-siniflandirma-video-07.mp4',37,1,'2026-11-04 11:32:03'),(45,'video','Kasalı Narenciye Taşıma ve Konveyör Akışı','Crate Citrus Transportation and Conveyor Flow','tesisler','bikgrup-seckin-mandalina-kasalari-sevkiyat-05.webp','','bikgrup-kasali-meyve-tasima-duzeni-video-08.mp4',38,1,'2026-11-04 11:32:04'),(46,'video','Kalibre Edilmiş Limonların İhracat Kasalarına Dizilimi','Arranging Calibrated Lemons in Export Crates','paketleme','bikgrup-akdeniz-mandalina-kasalari-paketleme-09.webp','','bikgrup-kalibre-limon-kasalama-video-09.mp4',39,1,'2026-11-04 11:32:05'),(47,'video','Soğuk Zincir Lojistik ve İhracat Yükleme Sahası','Cold Chain Logistics and Export Loading Area','lojistik','bikgrup-uluslararasi-lojistik-kasa-paletleri-24.webp','','bikgrup-soguk-zincir-lojistik-sevkiyati-video-10.mp4',40,1,'2026-11-04 11:32:06'),(48,'video','BİK GRUP Genel Tesis Akışı ve Hasat Yolculuğu','BİK GRUP General Facility Flow and Harvest Journey','tanitim','bikgrup-taze-hasat-yaprakli-mandalina-26.webp','','bikgrup-genel-tesis-ve-hasat-yolculugu-video-11.mp4',41,1,'2026-11-04 11:32:08');
/*!40000 ALTER TABLE `gallery_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(50) NOT NULL COMMENT 'home, about, contact',
  `section_key` varchar(80) NOT NULL,
  `title_tr` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `subtitle_tr` varchar(255) DEFAULT NULL,
  `subtitle_en` varchar(255) DEFAULT NULL,
  `body_tr` longtext DEFAULT NULL,
  `body_en` longtext DEFAULT NULL,
  `extra_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_json`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` VALUES (3,'about','about_story','Tarihçemiz','Our History','BİK GRUP | Topraktan Geleceğe Güvenle.','BİK GROUP | From Soil to the Future with Confidence.','Temelleri 2018 yılında atılan BİK GRUP; üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında faaliyet gösteren bir şirketler topluluğudur.\n\nBünyesindeki Köylü Tarım, Zeliha Sultan Çiftliği, İnci Fresh ve Lara Citrus markalarıyla; narenciye, meyve ve sebze alanında üretim, paketleme ve küresel ihracat süreçlerini başarıyla yönetmektedir.','BİK GRUP, whose foundations were laid in 2018; It is a group of companies operating in different areas of agriculture, from production to supply, from trade to marketing.\n\nWith its brands Köylü Tarım, Zeliha Sultan Çiftliği, İnci Fresh and Lara Citrus; It successfully manages the production, packaging and global export processes in the field of citrus fruits, fruits and vegetables.','{\"founded_year\":\"2018\",\"export_ratio\":\"85%\",\"certifications\":[\"GlobalG.A.P.\",\"BRCGS Food\",\"IFS Food\",\"ISO 22000\",\"Sedex\"]}','2026-11-04 09:15:59'),(4,'about','mission_vision','Misyonumuz & Vizyonumuz','Our Mission & Vision','Köklerimiz Toprakta, Hedefimiz Dünyada.','Our roots are in the soil, our goal is in the world.','','','{\"mission_title\":\"Misyonumuz\",\"mission_desc\":\"Toprağın sunduğu zenginlikleri çevreye duyarlı, modern ve sürdürülebilir tarım teknikleriyle işleyerek güvenilir, sağlıklı ve yüksek kaliteli tarımsal ürünleri dünya pazarlarına ulaştırmak.\",\"vision_title\":\"Vizyonumuz\",\"vision_desc\":\"Tarım ve gıda sektöründe inovatif, izlenebilir ve entegre çözümlerle küresel ölçekte öncü, güvenilir ve tercih edilen bir marka olmak.\"}','2026-11-04 09:16:01'),(5,'home','home_hero','Köklerimiz Toprakta, Hedefimiz Dünyada.','Our roots are in the soil, our goal is in the world.','BİK GRUP','BİK GROUP','Temelleri 2018 yılında atılan BİK GRUP, üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında güvenle faaliyet göstermektedir.','BİK GRUP, whose foundations were laid in 2018, operates safely in different areas of agriculture, from production to supply, from trade to marketing.','{\"bg_image\":\"nature_hero_bg.jpg\",\"card1_title\":\"Üretim\",\"card1_desc\":\"Kendi çiftliklerimizde modern tarım teknikleriyle yüksek kaliteli narenciye ve tahıl üretimi.\",\"card1_btn\":\"KEŞFET\",\"card1_link\":\"/kurumsal\",\"card2_title\":\"Tedarik & Ticaret\",\"card2_desc\":\"Üreticiden sanayiye ve pazara uzanan güçlü hammadde tedariği ve güvenilir lojistik ağı.\",\"card2_btn\":\"İNCELE\",\"card2_link\":\"/kurumsal\",\"card3_title\":\"Global İhracat\",\"card3_desc\":\"Toprağın bereketini uluslararası kalite standartlarıyla dünya pazarlarına ulaştıran vizyon.\",\"card3_btn\":\"İNCELE\",\"card3_link\":\"/urunler\",\"hero_btn_text\":\"Kurumsal Keşfet\",\"hero_btn_link\":\"/kurumsal\"}','2026-11-04 09:16:03'),(6,'home','home_featured','Öne Çıkan Ürünlerimiz','Our Featured Products','Taze Mahsuller','Fresh Produce','Dünya pazarlarında en çok talep gören yüksek standartlı sofralık ürünlerimiz.','Our high standard tableware products are the most demanded in the world markets.','{\"btn_text\":\"Tüm Ürünleri İncele\",\"btn_link\":\"/urunler\",\"quote_btn_text\":\"Teklif Al\"}','2026-11-04 09:16:04'),(7,'home','home_map','Akdeniz\'den Dünyaya Ulaşan Kesintisiz İhracat Ağı','Uninterrupted Export Network Reaching the World from the Mediterranean','KÜRESEL İHRACAT & GÜVENİLİR LOJİSTİK','GLOBAL EXPORT & RELIABLE LOGISTICS','Türkiye\'nin en bereketli bahçelerinden 4 kıtada 30\'u aşkın ülkenin sofrasına taze lezzet köprüsü kuruyoruz.','We deliver it to European, Russian, Middle Eastern and Asian markets without breaking the cold chain, preserving its freshness and nutritional value.','{\"capsule1_title\":\"30+ Ülkeye İhracat\",\"capsule1_sub\":\"Avrupa, Rusya & İskandinavya\",\"capsule2_title\":\"150.000+ Ton Kapasite\",\"capsule2_sub\":\"Modern Soğuk Hava Depoları\",\"capsule3_title\":\"%100 Taze & İzlenebilir\",\"capsule3_sub\":\"GlobalG.A.P. & BRCGS Sertifikalı\"}','2026-11-04 09:40:50'),(8,'home','home_brands','Grup Markalarımız','Our Group Brands','GRUP ŞİRKETLERİMİZ & MARKALARIMIZ','OUR GROUP COMPANIES & BRANDS','Tarımsal üretim, paketleme ve ihracatta uzmanlaşmış grup şirketlerimizle tek çatı altında eksiksiz tedarik gücü.','Complete supply power under one roof with our group companies specialized in agricultural production, packaging and export.','{\"brand1_name\":\"KÖYLÜ TARIM\",\"brand1_desc\":\"Tarımsal ham madde tedariği ve güvenilir küresel ticaret.\",\"brand1_image\":\"fresh_vegetables.jpg\",\"brand1_btn\":\"İNCELE\",\"brand1_link\":\"/markalar/koylu-tarim\",\"brand2_name\":\"ZELİHA SULTAN\",\"brand2_desc\":\"Bereketli topraklarda sürdürülebilir üretim ve yetiştiricilik.\",\"brand2_image\":\"blog_agriculture_hero.jpg\",\"brand2_btn\":\"İNCELE\",\"brand2_link\":\"/markalar/zeliha-sultan\",\"brand3_name\":\"İNCİ FRESH\",\"brand3_desc\":\"Uluslararası standartlarda soğuk zincir yaş meyve ve sebze ihracatı.\",\"brand3_image\":\"fresh_fruits.jpg\",\"brand3_btn\":\"İNCELE\",\"brand3_link\":\"/markalar/inci-fresh\",\"brand4_name\":\"LARA CITRUS\",\"brand4_desc\":\"Akdeniz bahçelerinden dünya sofralarına seçkin narenciye.\",\"brand4_image\":\"citrus_showcase.jpg\",\"brand4_btn\":\"İNCELE\",\"brand4_link\":\"/markalar/lara-citrus\"}','2026-11-04 09:16:09'),(9,'home','home_facility','Sıradan bir işleme tesisi değiliz; otomasyon, izlenebilirlik ve kalite kontrol süreçlerimizle ürünlerimizi daima güvence altında tutuyoruz.','We are not an ordinary processing facility; We always keep our products safe with our automation, traceability and quality control processes.','Tek Dünya, Tek Pazar','One World, One Market','Modern optik boylama, otomatik paketleme ve hassas sıcaklık kontrollü atmosferik depolarımızla dünya standartlarında hizmet veriyoruz.','We provide world-class service with our modern optical sorting, automatic packaging and precise temperature-controlled atmospheric warehouses.','{\"btn_text\":\"Bize Ulaşın\",\"btn_link\":\"/iletisim\"}','2026-11-04 09:16:12'),(10,'home','home_blog','Blog & Haberler','Blog & News','Sektörel Haberler','Sectoral News','Tarımsal üretim trendleri, ihracat dinamikleri ve tarladan sofraya yenilikçi gelişmeler.','Agricultural production trends, export dynamics and innovative developments from farm to table.','{\"btn_text\":\"Tüm Makaleler\",\"btn_link\":\"/blog\"}','2026-11-04 09:16:13'),(11,'footer','footer_main','BİK GRUP','BİK GROUP','Topraktan Geleceğe Güvenle...','From Soil to the Future with Confidence...','Temelleri 2018 yılında atılan BİK GRUP; Köylü Tarım, Zeliha Sultan Çiftliği, İnci Fresh ve Lara Citrus markalarıyla tarımsal üretim ve ticarette güvenin simgesidir.','BİK GRUP, whose foundations were laid in 2018; Köylü Tarım is the symbol of trust in agricultural production and trade with its brands Zeliha Sultan Çiftliği, İnci Fresh and Lara Citrus.','{\"quick_links_title\":\"Hızlı Bağlantılar\",\"products_title\":\"Ürünlerimiz\",\"policies_title\":\"Politikalar\",\"contact_title\":\"İletişim & Lokasyon\",\"copyright_text\":\"Tüm hakları saklıdır.\"}','2026-11-04 09:16:14'),(12,'about','about_hero','Köklerimiz Toprakta, Hedefimiz Dünyada.','Our roots are in the soil, our goal is in the world.','BİK GRUP | Topraktan Geleceğe Güvenle.','BİK GROUP | From Soil to the Future with Confidence.','Temelleri 2018 yılında atılan BİK GRUP; üretimden tedariğe, ticaretten pazarlamaya kadar tarımın her aşamasında sürdürülebilir kalite anlayışıyla faaliyet göstermektedir.','BİK GRUP, whose foundations were laid in 2018; It operates with a sustainable quality approach at every stage of agriculture, from production to supply, from trade to marketing.','{\"bg_image\":\"facility_automation.jpg\",\"btn_text\":\"Tarihçemiz\",\"btn_link\":\"#tarihce\"}','2026-11-04 09:16:15'),(13,'products','products_hero','Tarladan Dünyaya Taze Lezzetler','Fresh Flavors from the Field to the World','Akdeniz\'in en taze narenciye, meyve ve sebzelerini özenle seçiyor, uluslararası kalite ve soğuk zincir güvencesiyle dünya sofralarına ulaştırıyoruz.','We carefully select the freshest citrus fruits, fruits and vegetables of the Mediterranean and deliver them to the world\'s tables with international quality and cold chain assurance.','','','{\"bg_image\":\"nature_hero_bg.jpg\",\"quote_btn_text\":\"Teklif Al\",\"all_products_text\":\"Tüm Ürünler\"}','2026-11-04 09:16:18'),(14,'blog','blog_page_hero','Tarımsal Gelişmeler & Sektörel Haberler','Agricultural Developments & Sectoral News','BİK GRUP | Sürdürülebilir Tarım ve İhracat Dünyası','BİK GROUP | Sustainable Agriculture and Export World','Dünya tarım pazarlarındaki yenilikler, ihracat analizleri ve modern tarım teknolojileri üzerine en güncel makalelerimiz.','Our latest articles on innovations in world agricultural markets, export analyzes and modern agricultural technologies.','{\"bg_image\":\"blog_hero_new.jpg\"}','2026-11-04 09:16:21'),(15,'contact','contact_hero','Bizimle İletişime Geçin.','Contact Us','BİK GRUP | İletişim & Global Ofisler','BİK GROUP | Contact & Global Offices','Ürün talepleri, ihracat fiyatlandırmaları ve toptan sevkiyatlar için uzman kadromuz size 7/24 destek vermeye hazırdır.','Our expert staff is ready to support you 24/7 for product requests, export pricing and wholesale shipments.','{\"bg_image\":\"citrus_showcase.jpg\",\"working_hours\":\"Pazartesi - Cuma: 08:30 - 18:00\",\"weekend_hours\":\"Cumartesi: 09:00 - 14:00\"}','2026-11-04 09:16:23'),(16,'policy','policy_privacy','Gizlilik Politikası','Privacy Policy','Kişisel Verilerin Korunması ve Gizlilik Prensipleri','Personal Data Protection and Privacy Principles','BİK GRUP olarak (bundan böyle \"Şirket\" olarak anılacaktır), web sitemizi ziyaret eden kullanıcılarımızın ve iş ortaklarımızın kişisel bilgilerinin gizliliğini korumayı en temel kurumsal sorumluluklarımızdan biri kabul etmekteyiz.\n\n1. Toplanan Bilgiler\nWeb sitemiz üzerinden paylaştığınız iletişim formu ve teklif talep bilgileri (ad, soyad, e-posta, telefon ve mesaj içeriği) yalnızca size hizmet sunmak, taleplerinizi karşılamak ve ihracat/satış süreçleri hakkında bilgilendirme sağlamak amacıyla toplanmaktadır.\n\n2. Bilgilerin Güvenliği ve Paylaşımı\nToplanan kişisel bilgileriniz hiçbir surette üçüncü taraflara ticari amaçlarla satılmaz, kiralanmaz veya yetkisiz kişi ve kurumlarla paylaşılmaz. Yasal zorunluluklar saklı kalmak kaydıyla, verileriniz yüksek güvenlikli sunucularda korunmaktadır.\n\n3. İletişim\nGizlilik politikamızla ilgili soru, görüş ve talepleriniz için destek@bikgrup.com e-posta adresi üzerinden şirket yetkililerimizle iletişime geçebilirsiniz.','As BİK GRUP, we attach utmost importance to the security and privacy of the personal data of our users who visit our website.','[]','2026-11-04 11:00:36'),(17,'policy','policy_kvkk','6698 Sayılı KVKK Uyarınca Aydınlatma Metni','KVKK Clarification Text','6698 Sayılı Kişisel Verilerin Korunması Kanunu Kapsamında Bilgilendirme','Information within the Scope of Personal Data Protection Law No. 6698','BİK GRUP Tarım ve İhracat olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu (\"KVKK\") uyarınca, veri sorumlusu sıfatıyla elde ettiğimiz kişisel verilerinizin işlenmesinde ve muhafazasında kanuni ilkelere ve kurallara riayet etmekteyiz.\n\n1. Kişisel Verilerin İşlenme Amacı\nKişisel verileriniz; tarımsal üretim, paketleme, soğuk hava ve ihracat faaliyetlerimizin yürütülmesi, müşteri ilişkilerinin yönetimi, bilgi ve teklif taleplerinin cevaplandırılması amaçlarıyla KVKK 5. ve 6. maddelerine uygun olarak işlenmektedir.\n\n2. İlgili Kişinin Hakları (Madde 11)\nKVKK 11. maddesi uyarınca veri sahipleri; kişisel verilerinin işlenip işlenmediğini öğrenme, işlenmişse bilgi talep etme, işlenme amacına uygun kullanılıp kullanılmadığını öğrenme ve verilerin silinmesini veya düzeltilmesini talep etme haklarına sahiptir.\n\n3. Başvuru ve Bilgi Edinme\nKVKK kapsamındaki haklarınıza ilişkin taleplerinizi yazılı olarak veya şirketimize daha önce bildirilen ve sistemimizde kayıtlı bulunan e-posta adresiniz üzerinden info@bikgrup.com adresine iletebilirsiniz.','BİK GRUP acts as the data controller in accordance with the Personal Data Protection Law No. 6698 (\"KVKK\").','[]','2026-11-04 11:00:36'),(18,'policy','policy_cookie','Çerez (Cookie) Politikası','Cookie Policy','Çerezler (Cookies) ve Web Analitiği Kullanımı','Use of Cookies and Web Analytics','Web sitemizde, ziyaretçilerimize daha iyi bir kullanıcı deneyimi sunabilmek, site performansını analiz etmek ve tercihleri hatırlayabilmek amacıyla çerezler (cookies) kullanılmaktadır.\n\n1. Kullanılan Çerez Türleri\n- Zorunlu Çerezler: Web sitesinin temel fonksiyonlarının doğru çalışması için gereklidir.\n- Performans ve Analitik Çerezleri: Sayfa ziyaretleri ve trafik kaynaklarını anonim olarak ölçerek site deneyimini iyileştirmemize yardımcı olur.\n- İşlevsel Çerezler: Dil seçimi gibi kişiselleştirilmiş tercihlerinizi hatırlar.\n\n2. Çerez Tercihlerinin Yönetimi\nTarayıcınızın ayarlarını değiştirerek çerezleri dilediğiniz zaman engelleyebilir veya silebilirsiniz. Ancak çerezlerin devre dışı bırakılması durumunda web sitesinin bazı özellikleri beklenen şekilde çalışmayabilir.','Cookies are used to make our website work properly and improve your experience.','[]','2026-11-04 11:00:36'),(35,'about','about_certs','Uluslararası Kalite & Gıda Güvenliği Sertifikalarımız',NULL,'BELGELER & AKREDİTASYON',NULL,'Uluslararası gıda güvenliği, izlenebilirlik ve sürdürülebilir tarım standartlarına tam uyumla belgelendirilmiş üretim tesislerimiz.',NULL,'{\"cert1_name\":\"GlobalG.A.P. IFA\",\"cert1_title\":\"GLOBALG.A.P.\",\"cert1_stamp\":\"✓ VERIFIED\",\"cert1_standard\":\"İyi Tarım Uygulamaları ve Uluslararası Çiftlik Güvenliği Standardı\",\"cert1_status\":\"🛡️ Akredite İhracat Sertifikalı\",\"cert2_name\":\"BRCGS Food Safety\",\"cert2_title\":\"BRCGS FOOD\",\"cert2_stamp\":\"GRADE AA\",\"cert2_standard\":\"Küresel Gıda Güvenliği Girişimi (GFSI) Onaylı Paketleme & Hijyen\",\"cert2_status\":\"🛡️ A Sınıfı Tesis Akreditasyonu\",\"cert3_name\":\"IFS Food Standard\",\"cert3_title\":\"IFS FOOD v7\",\"cert3_stamp\":\"HIGHER LEVEL\",\"cert3_standard\":\"Avrupa Perakende Birliği Yüksek Seviye Kalite ve İzlenebilirlik\",\"cert3_status\":\"🛡️ Süpermarket Onaylı Tedarikçi\",\"cert4_name\":\"ISO 22000:2018\",\"cert4_title\":\"ISO 22000\",\"cert4_stamp\":\"ISO 22000:2018\",\"cert4_standard\":\"Tarladan Tüketime Gıda Güvenliği Yönetim Sistemi Standardı\",\"cert4_status\":\"🛡️ Entegre Kalite Yönetimi\"}','2026-11-04 09:46:47'),(36,'home','home_references','BİK GRUP Güvencesiyle Dünya Standartlarında Ticaret','Export Assured by BİK GRUP','KÜRESEL İŞ ORTAKLARI & GÜVENİLİR REFERANSLAR','GLOBAL TRADE NETWORK & REFERENCES','Tarımsal üretim ve ihracatta Türkiye\'nin ve dünyanın lider gıda, yem ve sanayi kuruluşlarıyla güçlü iş birliği.','Strong business partnerships with leading food, feed, and industrial enterprises in agricultural production and export.','{\"items\":[{\"id\":1,\"name\":\"Beşler Yem\",\"image\":\"references\\/besler-yem.webp\",\"link\":\"#\"},{\"id\":2,\"name\":\"Elita Gıda\",\"image\":\"references\\/elita-gida.webp\",\"link\":\"#\"},{\"id\":3,\"name\":\"Erpiliç\",\"image\":\"references\\/erpilinc.webp\",\"link\":\"#\"},{\"id\":4,\"name\":\"Mersin Yem\",\"image\":\"references\\/mersin-yem.webp\",\"link\":\"#\"},{\"id\":5,\"name\":\"Migros\",\"image\":\"references\\/migros.webp\",\"link\":\"#\"},{\"id\":6,\"name\":\"Omnia Nişasta\",\"image\":\"references\\/omnia.webp\",\"link\":\"#\"},{\"id\":7,\"name\":\"Ova Un\",\"image\":\"references\\/ova-un.webp\",\"link\":\"#\"},{\"id\":8,\"name\":\"Proyem\",\"image\":\"references\\/proyem.webp\",\"link\":\"#\"},{\"id\":9,\"name\":\"Semolina\",\"image\":\"references\\/semolina.webp\",\"link\":\"#\"},{\"id\":10,\"name\":\"Sunar Mısır\",\"image\":\"references\\/sunar-misir.webp\",\"link\":\"#\"},{\"id\":11,\"name\":\"Sunar Un\",\"image\":\"references\\/sunar-un.webp\",\"link\":\"#\"},{\"id\":12,\"name\":\"Tarım Kredi Kooperatifleri\",\"image\":\"references\\/tarim-kredi.webp\",\"link\":\"#\"},{\"id\":13,\"name\":\"Tat Nişasta\",\"image\":\"references\\/tat-nisasta.webp\",\"link\":\"#\"},{\"id\":14,\"name\":\"Tekbaş Un\",\"image\":\"references\\/tekbas-un.webp\",\"link\":\"#\"},{\"id\":15,\"name\":\"Tezkim Tarımsal Kimya\",\"image\":\"references\\/tezkim.webp\",\"link\":\"#\"},{\"id\":16,\"name\":\"Timagro\",\"image\":\"references\\/timagro.webp\",\"link\":\"#\"}]}','2026-11-04 12:29:00');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `name_tr` varchar(120) NOT NULL,
  `name_en` varchar(120) NOT NULL,
  `short_desc_tr` text DEFAULT NULL,
  `short_desc_en` text DEFAULT NULL,
  `content_tr` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `origin_tr` varchar(100) DEFAULT 'Akdeniz & Ege / Türkiye',
  `origin_en` varchar(100) DEFAULT 'Mediterranean & Aegean / Turkey',
  `harvest_season_tr` varchar(100) DEFAULT 'Ekim - Mayıs',
  `harvest_season_en` varchar(100) DEFAULT 'October - May',
  `packaging_tr` varchar(150) DEFAULT 'Teleskopik Koli, Plastik Kasa, IFCO Kasa',
  `packaging_en` varchar(150) DEFAULT 'Telescopic Carton, Plastic Crate, IFCO Box',
  `extra_json` longtext DEFAULT NULL,
  `temperature` varchar(50) DEFAULT '+4°C / +8°C',
  `is_featured` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,1,'star-ruby-greyfurt','Greyfurt','Grapefruit','Derin kırmızı-pembe iç rengi ve yoğun aromasıyla gastronomik sunumlara en uygun zarif seçim.','Deep red-pink inner color with intense aroma, an elegant choice for gastronomy.','Star Ruby, ince kabuk yapısı ve çekirdeksiz dolgun meyve etiyle tüm dünyada en çok tercih edilen narenciye türlerinden biridir.','Star Ruby is worldwide acclaimed for its thin peel, seedless juicy flesh, and rich antioxidant properties.','product_star_ruby.png','Akdeniz & Ege / Türkiye','Mediterranean & Aegean / Turkey','Ekim - Mayıs','October - May','Teleskopik Koli, Plastik Kasa, IFCO Kasa','Telescopic Carton, Plastic Crate, IFCO Box','{\"char_et_rengi\":\"Koyu Kırmızı\",\"char_cekirdek\":\"1 - 2 Çekirdek\",\"char_kabuk_rengi\":\"Pembemsi Turuncu, Kırmızı Yanaklı\",\"char_dokusu\":\"Yumuşak Bol Sulu\",\"char_sekil\":\"Yuvarlak, Hafif Basık\",\"char_tadi\":\"Tatlı ve Aromatik\",\"nutrition_calorie\":\"42 kcal \\/ 100g\",\"nutrition_brix\":\"10.5° Brix\",\"nutrition_acidity\":\"1.1% Sitrik Asit\",\"badge1\":\"C Vitamini Deposu\",\"badge2\":\"Yüksek Doğal Su & Lif\",\"badge3\":\"Zengin Antioksidan\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +8°C',1,1,10,'2026-10-30 10:01:17'),(4,1,'washington-portakal','Portakal','Orange','Geniş gövdesi, göbekli yapısı ve çekirdeksiz tatlı meyve etiyle Akdeniz\'in en sevilen portakalı.','Distinct navel structure, seedless juicy flesh, and unmistakable natural aroma.','Washington Navel, kalın ve kolay soyulan kabuğu, aromatik zenginliği ile taze sofralık tüketimin bir numarasıdır.','Washington Navel is renowned for its easy-peeling rind, aromatic richness, and supreme sweetness.','product_orange.png','Akdeniz & Ege / Türkiye','Mediterranean & Aegean / Turkey','Kasım - Haziran','November - June','Teleskopik Koli, Plastik Kasa, IFCO Kasa','Telescopic Carton, Plastic Crate, IFCO Box','{\"char_et_rengi\":\"Turuncu\",\"char_cekirdek\":\"1 - 2 Çekirdek\",\"char_kabuk_rengi\":\"Koyu Turuncu\",\"char_dokusu\":\"Gevrek, Bol Sulu\",\"char_sekil\":\"Yuvarlağa Yakın\",\"char_tadi\":\"Tatlı, Aromatik\",\"nutrition_calorie\":\"47 kcal \\/ 100g\",\"nutrition_brix\":\"11.5° Brix\",\"nutrition_acidity\":\"0.8% Sitrik Asit\",\"badge1\":\"C Vitamini Deposu\",\"badge2\":\"Yüksek Doğal Su & Lif\",\"badge3\":\"Zengin Antioksidan\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +8°C',1,1,12,'2026-10-30 10:01:17'),(5,1,'mayer-limon','Limon','Lemon','İnce parlak kabuğu, yüksek meyve suyu verimi ve hafif tatlımsı narin aromasıyla lüks narenciye.','Thin glossy peel, exceptional juice yield, and delicately sweet floral citrus aroma.','Mayer Limon, zengin esansiyel yağları ve bol suyu ile dünya mutfaklarının vazgeçilmezidir.','Meyer lemon is highly prized in culinary arts for its fragrant aroma and rich juice extraction.','product_lemon.png','Akdeniz & Ege / Türkiye','Mediterranean & Aegean / Turkey','Ağustos - Mart','August - March','Teleskopik Koli, Plastik Kasa, IFCO Kasa','Telescopic Carton, Plastic Crate, IFCO Box','{\"char_et_rengi\":\"Yeşilimsi Sarı\",\"char_cekirdek\":\"Az Çekirdekli\",\"char_kabuk_rengi\":\"Koyu Sarı (Orta - İnce)\",\"char_dokusu\":\"Bol Sulu\",\"char_sekil\":\"Silindirik, Sap Tarafından Kısa Boyunlu\",\"char_tadi\":\"Canlandırıcı Asidik \\/ Ekşi\",\"nutrition_calorie\":\"29 kcal \\/ 100g\",\"nutrition_brix\":\"8.2° Brix\",\"nutrition_acidity\":\"5.0% Sitrik Asit\",\"badge1\":\"Yüksek Asit & C Vitamini\",\"badge2\":\"İnce Kabuk Kolay Sıkım\",\"badge3\":\"Doğal Koruyucu Aromatik\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +8°C',1,1,13,'2026-10-30 10:01:17'),(6,2,'akdeniz-karpuzu','Karpuz','Watermelon','Güneşle olgunlaşan, çıtır gevrek dokulu ve yüksek şeker oranına sahip tarladan taze karpuz.','Sun-ripened, crisp crunchy flesh with exceptional natural sweetness.','Verimli Çukurova ve Antalya ovalarında yetiştirilen karpuzlarımız, özel darbeye dayanıklı kasalama ile sevk edilir.','Harvested carefully to protect freshness and shipped in heavy-duty refrigerated crates.','product_watermelon.png','Akdeniz & Ege / Türkiye','Mediterranean & Aegean / Turkey','Mayıs - Ağustos','May - August','Teleskopik Koli, Plastik Kasa, IFCO Kasa','Telescopic Carton, Plastic Crate, IFCO Box','{\"char_et_rengi\":\"Canlı Yakut Kırmızısı\",\"char_cekirdek\":\"Küçük Çekirdekli / Çekirdeksiz\",\"char_kabuk_rengi\":\"Çizgili Koyu Yeşil\",\"char_dokusu\":\"Çıtır, Gevrek ve Ekstra Sulu\",\"char_sekil\":\"Yuvarlak / Oval Form\",\"char_tadi\":\"Yüksek Şekerli & Ferahlatıcı\",\"nutrition_calorie\":\"30 kcal / 100g\",\"nutrition_brix\":\"12.0° Brix\",\"nutrition_acidity\":\"0.2% Sitrik Asit\",\"badge1\":\"Likopen Deposu\",\"badge2\":\"%92 Doğal Su Oranı\",\"badge3\":\"Yaz Ferahlığı & Tazelik\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +8°C',1,1,14,'2026-10-30 10:01:17'),(7,3,'salkim-domates','Domates','Tomato','Dalında kızaran, yoğun aromalı ve mükemmel kalibreli sera & açık alan salkım domatesleri.','Ripened on the vine, deeply aromatic with consistent sizing and vibrant red color.','İleri teknoloji topraksız tarım tesislerimizde biyolojik mücadele yöntemleriyle ilaçsız yetiştirilir.','Grown in high-tech hydroponic facilities with sustainable biological pest management.','product_tomato.png','Akdeniz & Ege / Türkiye','Mediterranean & Aegean / Turkey','Tüm Yıl Boyunca','All Year Round','Teleskopik Koli, Plastik Kasa, IFCO Kasa','Telescopic Carton, Plastic Crate, IFCO Box','{\"char_et_rengi\":\"Dolgun Kırmızı Et\",\"char_cekirdek\":\"Doğal Çekirdekli\",\"char_kabuk_rengi\":\"Parlak Canlı Kırmızı\",\"char_dokusu\":\"Sert, Dayanıklı ve Etli\",\"char_sekil\":\"Düzgün Küresel Salkım\",\"char_tadi\":\"Yoğun Domates Aroması\",\"nutrition_calorie\":\"18 kcal / 100g\",\"nutrition_brix\":\"5.5° Brix\",\"nutrition_acidity\":\"0.5% Sitrik Asit\",\"badge1\":\"Yüksek Likopen & C Vitamini\",\"badge2\":\"Uzun Raf Ömrü\",\"badge3\":\"Salkım Tazeliği\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +8°C',1,1,15,'2026-10-30 10:01:17'),(9,4,'bugday','Buğday','Wheat','Yüksek proteinli, kaliteli ekmeklik ve makarnalık sert durum buğdayı.','High-protein, superior-grade durum and milling wheat for global markets.','Akdeniz ve İç Anadolu nun bereketli ovalarında kontrollü tarım standartlarıyla yetiştirilen buğdaylarımız; yüksek gluten, ideal hektolitre ve düşük nem oranlarıyla un, makarna ve gıda sanayisinin birinci sınıf tercihidir.','Sustainably cultivated in fertile Mediterranean and Anatolian plains, our wheat provides high gluten, optimal hectoliter weight, and superior milling quality.','product_wheat.png','Çukurova & İç Anadolu / Türkiye','Cukurova & Central Anatolia / Turkey','Haziran - Ağustos','June - August','Dökme, Big-Bag (1000 kg), Çuval (50 kg)','Bulk, Big-Bag (1000 kg), Sacks (50 kg)','{\"char_et_rengi\":\"Amber Sarısı Camsı Dane\",\"char_cekirdek\":\"Sert Endosperm & Yüksek Protein\",\"char_kabuk_rengi\":\"İnce Altın Kepek\",\"char_dokusu\":\"Yüksek İrmik & Un Verimi\",\"char_sekil\":\"İri Dolgun Tane\",\"char_tadi\":\"Doğal Tahıl Lezzeti\",\"varieties\":[{\"name\":\"Sert Durum Makarnalık Buğday\",\"months\":\"Haziran - Ağustos\",\"desc\":\"Yüksek camsılık, zengin sarı pigment ve üstün protein değeriyle makarna sanayisinin lideri.\",\"char_note\":\"Amber Sarısı Camsı Dane, Yüksek İrmik Verimi\"},{\"name\":\"Kırmızı Sert Ekmeklik Buğday\",\"months\":\"Haziran - Ağustos\",\"desc\":\"Yüksek gluten indeksi, mükemmel un randımanı ve üstün kabarma kabiliyeti.\",\"char_note\":\"Kırmızımsı Kahve Tane, Mükemmel Un Randımanı\"},{\"name\":\"Beyaz Sert Buğday\",\"months\":\"Haziran - Ağustos\",\"desc\":\"Açık renkli un ve bisküvilik sanayi için homojen hektolitre ağırlığı.\",\"char_note\":\"Berrak Beyaz Dane, Yüksek Un Beyazlığı\"}]}','Kuru & Havalandırmalı Depo',1,1,1,'2026-11-04 06:34:26'),(10,4,'misir','Mısır','Corn (Maize)','Altın sarısı daneli, düşük nem oranına sahip yüksek enerjili sarı mısır.','Golden grain, low-moisture high-energy feed and food grade corn.','Çukurova’nın güneşinde olgunlaşan sarı daneli mısırlarımız; yüksek nişasta ve enerji içeriği, düşük kırık oranı ve ideal nem kalibrasyonuyla yem sanayisi ve gıda üretiminde güvenle tercih edilmektedir.','Sun-ripened in Cukurova, our yellow grain corn delivers high starch and energy content with international quality grading.','product_corn.png','Çukurova / Türkiye','Cukurova / Turkey','Ağustos - Ekim','August - October','Dökme, Big-Bag (1000 kg), Çuval (50 kg)','Bulk, Big-Bag (1000 kg), Sacks (50 kg)','{\"char_et_rengi\":\"Parlak Altın Sarısı\",\"char_cekirdek\":\"Geniş Embriyo & Yüksek Enerji\",\"char_kabuk_rengi\":\"Pürüzsüz Sarı Parlak Kabuk\",\"char_dokusu\":\"Sert & Dolgun Tane\",\"char_sekil\":\"At Dişi & Küremsi Kalibre\",\"char_tadi\":\"Doğal Yüksek Nişasta & Aroma\",\"varieties\":[{\"name\":\"Sarı At Dişi Tane Mısır\",\"months\":\"Ağustos - Ekim\",\"desc\":\"Yüksek nişasta ve metabolik enerji içeriği, düşük kırık ve ideal nem kalibrasyonu.\",\"char_note\":\"Parlak Altın Sarısı, Sert ve Dolgun Tane\"},{\"name\":\"Sert Flint Mısır\",\"months\":\"Ağustos - Ekim\",\"desc\":\"Kusursuz tane sertliği, uzun depolama ömrü ve gıda sanayisi için ideal un kalitesi.\",\"char_note\":\"Koyu Turuncumsu Sarı, Camsı Kırılma Dirençli\"}]}','Kuru & Serin Depo (Max %14 Nem)',1,1,2,'2026-11-04 06:34:26'),(11,4,'aycekirdegi','Ayçekirdeği','Sunflower Seeds','Yüksek yağ ve dolgun protein oranına sahip çerezlik ve yağlık ayçekirdeği.','High-oil content and large-caliber confectionery sunflower seeds.','Uluslararası standartlarda elenen ve boylanan ayçekirdeklerimiz; yüksek yağ randımanı, dolgun iç kalibresi ve parlak kabuk yapısıyla hem yağ sanayinde hem de kuruyemiş sektöründe ihracat kalitesindedir.','Carefully graded sunflower seeds offering high oil yield, uniform sizing, and optimal moisture standards.','product_sunflower.png','Trakya & Çukurova / Türkiye','Thrace & Cukurova / Turkey','Temmuz - Eylül','July - September','Polipropilen Çuval (25 kg / 50 kg), Big-Bag','PP Bags (25 kg / 50 kg), Big-Bag','{\"char_et_rengi\":\"Krem Beyazı / Açık Sarı İç\",\"char_cekirdek\":\"%50+ Dolgun İç Randımanı\",\"char_kabuk_rengi\":\"Alacalı Çizgili / Tam Siyah\",\"char_dokusu\":\"Gevrek ve Yağlı\",\"char_sekil\":\"Uzun İri Kalibre Form\",\"char_tadi\":\"Lezzetli ve Zengin Yağ Aroması\",\"varieties\":[{\"name\":\"Dakota Alacalı Çerezlik\",\"months\":\"Temmuz - Eylül\",\"desc\":\"İri boy kalibre, yüksek doluluk oranı ve kolay kavurma randımanıyla sofralık lider.\",\"char_note\":\"Krem Beyazı Dolgun İç, Gevrek ve Yağlı\"},{\"name\":\"Siyah Yağlık Ayçekirdeği\",\"months\":\"Temmuz - Eylül\",\"desc\":\"Yüksek yağ randımanı (%45+), ince kabuk yapısı ve üstün bitkisel yağ kalitesi.\",\"char_note\":\"Açık Sarı Yağlı İç, Tam Siyah İnce Kabuk\"}]}','Kuru & Havalandırmalı Depo',1,1,3,'2026-11-04 06:34:26'),(12,4,'soya-fasulyesi','Soya Fasulyesi','Soybeans','Yüksek bitkisel proteinli, kontrollü tarımla üretilen sarı soya fasulyesi.','Non-GMO certified, high plant-protein premium yellow soybeans.','Çukurova nın verimli arazilerinde ikinci ürün ve ana ürün olarak yetiştirilen soya fasulyelerimiz; yüksek protein yüzdesi ve zengin yağ asidi profiliyle yem ve bitkisel yağ sektörünün stratejik hammaddesidir.','Grown in prime agricultural regions, our soybeans provide superior protein concentrations and oil yields.','product_soybean.png','Çukurova / Türkiye','Cukurova / Turkey','Ağustos - Ekim','August - October','Dökme, Big-Bag (1000 kg), Çuval (50 kg)','Bulk, Big-Bag (1000 kg), Sacks (50 kg)','{\"char_et_rengi\":\"Açık Altın Sarısı\",\"char_cekirdek\":\"%36+ Yüksek Ham Protein\",\"char_kabuk_rengi\":\"Pürüzsüz Açık Sarı Kabuk\",\"char_dokusu\":\"Sert, Homojen Boylanmış Tane\",\"char_sekil\":\"Düzgün Yuvarlak Küre Kalibre\",\"char_tadi\":\"Doğal Bitkisel Protein & Yağ\",\"varieties\":[{\"name\":\"Birinci Ürün Sarı Soya\",\"months\":\"Ağustos - Ekim\",\"desc\":\"Non-GMO kontrollü tarım, zengin bitkisel protein ve ideal yağ asidi dengesi.\",\"char_note\":\"Açık Sarı Embriyo, %36+ Ham Protein\"},{\"name\":\"İkinci Ürün Yüksek Protein Soya\",\"months\":\"Eylül - Ekim\",\"desc\":\"Çukurova’da hasat edilen, yüksek protein ve üstün yağ randımanı sağlayan taze mahsul.\",\"char_note\":\"Altın Sarısı Dolgun, %19+ Doğal Yağ\"}]}','Kuru & Serin Depo (Max %13 Nem)',1,1,4,'2026-11-04 06:34:26'),(13,1,'mandalina','Mandalina','Mandarin','Akdeniz’in verimli topraklarında yetiştirilen, kolay soyulan ince kabuklu, bol sulu ve aromatik taze mandalina çeşitlerimiz.','Fresh, juicy and highly aromatic mandarin varieties grown in the fertile soils of the Mediterranean with easy peel skin.','BİK GRUP olarak Çukurova ve Akdeniz bölgesindeki modern bahçelerimizde Tango, W. Murcott, Satsuma, Nova ve Primasol başta olmak üzere dünya standartlarında mandalina üretimi ve ihracatı gerçekleştiriyoruz.','As BIK GRUP, we produce and export world-class mandarin varieties including Tango, W. Murcott, Satsuma, Nova, and Primasol from our modern groves in the Mediterranean basin.','product_orange.png','Akdeniz & Çukurova / Türkiye','Mediterranean & Cukurova / Turkey','Eylül - Mart','September - March','Teleskopik Koli, Plastik Kasa, IFCO Kasa, File','Telescopic Carton, Plastic Crate, IFCO Box, Net Bag','{\"char_et_rengi\":\"Koyu Turuncu\",\"char_cekirdek\":\"Çekirdeksiz\",\"char_kabuk_rengi\":\"Turuncu, Sarı\",\"char_dokusu\":\"Bol Sulu, Etli\",\"char_sekil\":\"Yassı Yuvarlak\",\"char_tadi\":\"Tatlı, Çok Lezzetli\",\"nutrition_calorie\":\"53 kcal \\/ 100g\",\"nutrition_brix\":\"11.8° Brix\",\"nutrition_acidity\":\"0.9% Sitrik Asit\",\"badge1\":\"C Vitamini Deposu\",\"badge2\":\"Kolay Soyulur & Sulu\",\"badge3\":\"Aromatik Taze Mahsul\",\"box1_title\":\"Karton Kutu\",\"box1_weight\":\"7 Kg\",\"box1_dim\":\"600 x 400 x 120\",\"box2_title\":\"Karton Kutu\",\"box2_weight\":\"15 Kg\",\"box2_dim\":\"600 x 400 x 160\",\"box3_title\":\"Karton Kutu Derin\",\"box3_weight\":\"15 Kg\",\"box3_dim\":\"600 x 400 x 180\",\"box4_title\":\"Karton Kutu – Teleskopik\",\"box4_weight\":\"15 Kg\",\"box4_dim\":\"600 x 400 x 180\",\"box5_title\":\"File Ambalaj\",\"box5_weight\":\"1.5 Kg - 2 Kg\",\"box5_dim\":\"Perakende Market Rafı\"}','+4°C / +6°C',1,1,11,'2026-11-04 12:56:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quote_requests`
--

DROP TABLE IF EXISTS `quote_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quote_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_category` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `quote_amount` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quote_requests`
--

LOCK TABLES `quote_requests` WRITE;
/*!40000 ALTER TABLE `quote_requests` DISABLE KEYS */;
INSERT INTO `quote_requests` VALUES (1,'Star Ruby Greyfurt','20 Ton','Ahmet Yılmaz','05331112233','ahmet@firma.com','FOB Mersin teslimat istiyoruz.','pending',NULL,NULL,'2026-10-30 11:17:44','2026-10-30 11:17:44'),(5,'Washington Portakal','25 Ton (İhracat Kalite)','Ahmet Yılmaz','+90 532 111 22 33','ahmet@ornekithalat.com','Mersin liman teslim FOB fiyatı talep ediyoruz.','replied','','','2026-11-04 12:15:05','2026-11-04 12:23:43');
/*!40000 ALTER TABLE `quote_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'general' COMMENT 'theme, general, contact, social, seo',
  `label_tr` varchar(150) NOT NULL,
  `label_en` varchar(150) DEFAULT NULL,
  `input_type` varchar(30) DEFAULT 'text' COMMENT 'text, textarea, color, image, number',
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'theme_primary','#15803d','theme','Ana Renk (Canlı Yeşil)','Primary Color','color'),(2,'theme_primary_hover','#166534','theme','Ana Renk Koyu / Hover','Primary Hover Color','color'),(3,'theme_secondary','#ea580c','theme','İkincil Renk (Narenciye Turuncusu)','Secondary Color (Citrus Orange)','color'),(4,'theme_secondary_hover','#c2410c','theme','İkincil Renk Hover','Secondary Hover Color','color'),(5,'theme_accent','#059669','theme','Vurgu Rengi (Zümrüt)','Accent Color','color'),(6,'theme_bg','#fcfdfd','theme','Genel Sayfa Arka Plan Rengi','Page Background Color','color'),(7,'theme_card_bg','#ffffff','theme','Kart Arka Plan Rengi','Card Background Color','color'),(8,'theme_text','#0f172a','theme','Ana Yazı Rengi (Koyu)','Primary Text Color','color'),(9,'theme_text_muted','#64748b','theme','İkincil Yazı Rengi (Soluk Gri)','Muted Text Color','color'),(10,'theme_navbar_bg','rgba(255, 255, 255, 0.94)','theme','Navbar Cam Efekti Rengi','Navbar Glass Color','text'),(11,'theme_border_radius','20px','theme','Bileşen Köşe Yuvarlaklığı','Border Radius','text'),(12,'site_name','BİK GRUP','general','Firma Adı','Company Name','text'),(13,'site_logo_text','BİK GRUP','general','Logo Metni','Logo Text','text'),(14,'site_tagline_tr','Köklerimiz Toprakta, Hedefimiz Dünyada.','general','Slogan (TR)','Tagline (TR)','text'),(15,'site_tagline_en','Köklerimiz Toprakta, Hedefimiz Dünyada.','general','Slogan (EN)','Tagline (EN)','text'),(16,'site_phone','05337989901','contact','Telefon Numarası','Phone Number','text'),(17,'site_email','destek@bikgrup.com','contact','E-Posta Adresi','Email Address','text'),(18,'site_whatsapp','05337989901','contact','WhatsApp Hattı','WhatsApp Number','text'),(19,'site_address_tr','Sirkenli, ANA YOL ÜZERİ, 01900 Karataş/Adana','contact','Adres (TR)','Address (TR)','textarea'),(20,'site_address_en','Sirkenli, ANA YOL ÜZERİ, 01900 Karataş/Adana','contact','Adres (EN)','Address (EN)','textarea'),(21,'social_instagram','https://instagram.com/bikgrup','social','Instagram Profili','Instagram URL','text'),(22,'social_linkedin','https://linkedin.com/company/bikgrup','social','LinkedIn Profili','LinkedIn URL','text'),(23,'site_logo','logo_1793787030_f696cc.webp','general','Site Logosu',NULL,'image'),(24,'site_favicon','favicon_1793787375_769ef3.webp','general','Site Favicon (Tarayıcı İkonu)',NULL,'image'),(25,'site_address_map_link','https://maps.app.goo.gl/KxejLkF6GA7i7LsBA','general','',NULL,'text'),(47,'social_youtube','','social','',NULL,'text'),(58,'social_facebook','','general','',NULL,'text'),(61,'social_x','','general','',NULL,'text'),(96,'quote_notification_email','destek@bikgrup.com','quotes','Teklif Bildirim E-Postasý',NULL,'text'),(97,'smtp_host','smtp.hostinger.com','mail','',NULL,'text'),(98,'smtp_port','465','mail','',NULL,'text'),(99,'smtp_secure','ssl','mail','',NULL,'text'),(100,'smtp_user','destek@bikgrup.com','mail','',NULL,'text');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `is_immutable` tinyint(1) DEFAULT 0 COMMENT '1 ise Süper Admin dokunulmazdır, silinemez ve rolü değiştirilemez',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Süleyman Özer','suleymannozer0@gmail.com','$2y$10$scxq0WiJrGeSfFVGOqWeRu2dVHv398t5ZG33ggCm6mam/alfS7GIu','superadmin',1,'2026-11-12 10:30:00','2026-10-30 10:01:17','2026-11-12 10:30:00'),(4,'Bik Grup','bikgruppanel@gmail.com','$2y$10$Gdr.6/XNOw2EdgDNWGKhbup5iALNxbPwGVyx1m7YD8SBt9jqqbcmO','admin',0,'2026-10-30 17:06:31','2026-10-30 14:06:03','2026-10-30 14:06:31'),(6,'İbrahim Köylü','ibrahimkylpanel@gmail.com','$2y$10$GVvO2JCamQ4xejRsEEcck.10KH/VhhLHNiYQSLEROjcUYkJi2jJZm','admin',0,NULL,'2026-11-12 10:30:00','2026-11-12 10:30:00');
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

-- Dump completed on 2026-11-04 18:04:33
