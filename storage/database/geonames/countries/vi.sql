-- MySQL dump 10.13  Distrib 5.6.35, for osx10.9 (x86_64)
--
-- Host: localhost    Database: careerexchange
-- ------------------------------------------------------
-- Server version	5.6.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `<<prefix>>subadmin1`
--

/*!40000 ALTER TABLE `<<prefix>>subadmin1` DISABLE KEYS */;
INSERT INTO `<<prefix>>subadmin1` VALUES (3758,'VI.010','VI','Saint Croix Island','Saint Croix Island',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (3759,'VI.020','VI','Saint John Island','Saint John Island',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (3760,'VI.030','VI','Saint Thomas Island','Saint Thomas Island',1);
/*!40000 ALTER TABLE `<<prefix>>subadmin1` ENABLE KEYS */;

--
-- Dumping data for table `<<prefix>>subadmin2`
--

/*!40000 ALTER TABLE `<<prefix>>subadmin2` DISABLE KEYS */;
INSERT INTO `<<prefix>>subadmin2` VALUES (41841,'VI.010.04600','VI','VI.010','Anna\'s Hope Village','Anna\'s Hope Village',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41842,'VI.010.20800','VI','VI.010','Christiansted','Christiansted',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41843,'VI.010.31600','VI','VI.010','East End','East End',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41844,'VI.010.62200','VI','VI.010','Northwest','Northwest',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41845,'VI.010.71200','VI','VI.010','Sion Farm','Sion Farm',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41846,'VI.010.73900','VI','VI.010','Southcentral','Southcentral',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41847,'VI.010.75700','VI','VI.010','Southwest','Southwest',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41848,'VI.020.15400','VI','VI.020','Central','Central',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41849,'VI.020.27100','VI','VI.020','Coral Bay','Coral Bay',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41850,'VI.020.28900','VI','VI.020','Cruz Bay','Cruz Bay',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41851,'VI.020.33400','VI','VI.020','East End','East End',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41852,'VI.030.17200','VI','VI.030','Charlotte Amalie','Charlotte Amalie',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41853,'VI.030.34300','VI','VI.030','East End','East End',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41854,'VI.030.61300','VI','VI.030','Northside','Northside',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41855,'VI.030.74800','VI','VI.030','Southside','Southside',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41856,'VI.030.78400','VI','VI.030','Tutu','Tutu',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41857,'VI.030.82000','VI','VI.030','Water Island','Water Island',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41858,'VI.030.82900','VI','VI.030','West End','West End',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41859,'VI.010.38800','VI','VI.010','Frederiksted','Frederiksted',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (41860,'VI.010.59500','VI','VI.010','Northcentral','Northcentral',1);
/*!40000 ALTER TABLE `<<prefix>>subadmin2` ENABLE KEYS */;

--
-- Dumping data for table `<<prefix>>cities`
--

/*!40000 ALTER TABLE `<<prefix>>cities` DISABLE KEYS */;
INSERT INTO `<<prefix>>cities` VALUES (4795467,'VI','Charlotte Amalie','Charlotte Amalie',18.3419,-64.9307,'P','PPLC','VI.030','VI.030.17200',20000,'America/St_Thomas',1,'2016-06-21 23:00:00','2016-06-21 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (4795577,'VI','Cruz Bay','Cruz Bay',18.3313,-64.7937,'P','PPLA','VI.020','VI.020.28900',2743,'America/St_Thomas',1,'2016-06-21 23:00:00','2016-06-21 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (4796512,'VI','Saint Croix','Saint Croix',17.7275,-64.747,'P','PPLA','VI.010','VI.010.73900',50601,'America/St_Thomas',1,'2016-06-21 23:00:00','2016-06-21 23:00:00');
/*!40000 ALTER TABLE `<<prefix>>cities` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
