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
INSERT INTO `<<prefix>>subadmin1` VALUES (2451,'NP.FR','NP','Far Western','Far Western',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (2452,'NP.MR','NP','Mid Western','Mid Western',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (2453,'NP.CR','NP','Central Region','Central Region',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (2454,'NP.ER','NP','Eastern Region','Eastern Region',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (2455,'NP.WR','NP','Western Region','Western Region',1);
INSERT INTO `<<prefix>>subadmin1` VALUES (2456,'NP.00','NP','','Patheka',1);
/*!40000 ALTER TABLE `<<prefix>>subadmin1` ENABLE KEYS */;

--
-- Dumping data for table `<<prefix>>subadmin2`
--

/*!40000 ALTER TABLE `<<prefix>>subadmin2` DISABLE KEYS */;
INSERT INTO `<<prefix>>subadmin2` VALUES (27116,'NP.FR.14','NP','NP.FR','Setī Zone','Seti Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27117,'NP.ER.13','NP','NP.ER','Sagarmāthā Zone','Sagarmatha Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27118,'NP.MR.12','NP','NP.MR','Rāptī Zone','Rapti Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27119,'NP.CR.11','NP','NP.CR','Nārāyanī Zone','Narayani Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27120,'NP.ER.10','NP','NP.ER','Mechī Zone','Mechi Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27121,'NP.FR.09','NP','NP.FR','Mahākālī Zone','Mahakali Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27122,'NP.WR.08','NP','NP.WR','Lumbinī Zone','Lumbini Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27123,'NP.ER.07','NP','NP.ER','Kosī Zone','Kosi Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27124,'NP.MR.06','NP','NP.MR','Karnālī Zone','Karnali Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27125,'NP.CR.05','NP','NP.CR','Janakpur Zone','Janakpur Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27126,'NP.WR.04','NP','NP.WR','Gandakī Zone','Gandaki Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27127,'NP.WR.03','NP','NP.WR','Dhawalāgiri Zone','Dhawalagiri Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27128,'NP.MR.02','NP','NP.MR','Bherī Zone','Bheri Zone',1);
INSERT INTO `<<prefix>>subadmin2` VALUES (27129,'NP.CR.01','NP','NP.CR','Bāgmatī Zone','Bagmati Zone',1);
/*!40000 ALTER TABLE `<<prefix>>subadmin2` ENABLE KEYS */;

--
-- Dumping data for table `<<prefix>>cities`
--

/*!40000 ALTER TABLE `<<prefix>>cities` DISABLE KEYS */;
INSERT INTO `<<prefix>>cities` VALUES (1282616,'NP','Wāling','Waling',27.9833,83.7667,'P','PPL','NP.WR',NULL,21867,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282635,'NP','Tulsīpur','Tulsipur',28.131,82.2973,'P','PPL','NP.MR',NULL,39058,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282666,'NP','Tīkāpur','Tikapur',28.5,81.1333,'P','PPL','NP.FR',NULL,44758,'Asia/Kathmandu',1,'2013-05-21 23:00:00','2013-05-21 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282714,'NP','Tānsen','Tansen',27.8673,83.5467,'P','PPL','NP.WR',NULL,23693,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282770,'NP','Sirāhā','Siraha',26.6541,86.2087,'P','PPL','NP.ER',NULL,24657,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282884,'NP','Rājbirāj','Rajbiraj',26.5333,86.75,'P','PPL','NP.ER',NULL,33061,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282898,'NP','Pokhara','Pokhara',28.2669,83.9685,'P','PPLA','NP.WR','NP.WR.04',200000,'Asia/Kathmandu',1,'2011-03-09 23:00:00','2011-03-09 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282931,'NP','Pātan','Patan',27.6766,85.3142,'P','PPL','NP.CR','NP.CR.01',183310,'Asia/Kathmandu',1,'2013-05-04 23:00:00','2013-05-04 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1282950,'NP','Panauti̇̄','Panauti',27.5845,85.5148,'P','PPL','NP.CR',NULL,27602,'Asia/Kathmandu',1,'2015-06-07 23:00:00','2015-06-07 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283082,'NP','Malangwa','Malangwa',26.8568,85.5581,'P','PPL','NP.CR',NULL,20284,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283095,'NP','Mahendranagar','Mahendranagar',28.9167,80.3333,'P','PPL','NP.FR',NULL,88381,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283119,'NP','Lobujya','Lobujya',27.95,86.8167,'P','PPL','NP.ER',NULL,8767,'Asia/Kathmandu',1,'2013-05-21 23:00:00','2013-05-21 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283161,'NP','Lahān','Lahan',26.7296,86.4951,'P','PPL','NP.ER',NULL,31495,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283190,'NP','Kirtipur','Kirtipur',27.6787,85.2775,'P','PPL','NP.CR',NULL,44632,'Asia/Kathmandu',1,'2015-05-05 23:00:00','2015-05-05 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283217,'NP','Khāndbāri','Khandbari',27.3747,87.2039,'P','PPL','NP.ER',NULL,22903,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283240,'NP','Kathmandu','Kathmandu',27.7017,85.3206,'P','PPLC','NP.CR','NP.CR.01',1442271,'Asia/Kathmandu',1,'2015-03-19 23:00:00','2015-03-19 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283285,'NP','Jumla','Jumla',29.2747,82.1838,'P','PPL','NP.MR','NP.MR.06',9073,'Asia/Kathmandu',1,'2013-09-20 23:00:00','2013-09-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283318,'NP','Janakpur','Janakpur',26.7183,85.9065,'P','PPL','NP.CR',NULL,93767,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283323,'NP','Jaleswar','Jaleswar',26.6471,85.8008,'P','PPL','NP.CR',NULL,23573,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283329,'NP','Ithari','Ithari',26.6667,87.2833,'P','PPL','NP.ER',NULL,47984,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283333,'NP','Ilām','Ilam',26.9094,87.9282,'P','PPLA3','NP.ER','NP.ER.10',17491,'Asia/Kathmandu',1,'2011-07-30 23:00:00','2011-07-30 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283339,'NP','Hetauda','Hetauda',27.4284,85.0322,'P','PPL','NP.CR','NP.CR.11',84775,'Asia/Kathmandu',1,'2015-05-06 23:00:00','2015-05-06 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283368,'NP','Gulariyā','Gulariya',28.2333,81.3333,'P','PPL','NP.MR',NULL,53107,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283401,'NP','Gaur','Gaur',26.7645,85.2784,'P','PPL','NP.CR',NULL,27325,'Asia/Kathmandu',1,'2016-09-08 23:00:00','2016-09-08 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283460,'NP','Dharān Bāzār','Dharan Bazar',26.8125,87.2835,'P','PPL','NP.ER',NULL,108600,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283465,'NP','Dhankutā','Dhankuta',26.9833,87.3333,'P','PPLA','NP.ER',NULL,22084,'Asia/Kathmandu',1,'2011-03-09 23:00:00','2011-03-09 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283467,'NP','Dhangarhi','Dhangarhi',28.7079,80.5961,'P','PPL','NP.FR',NULL,92294,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283484,'NP','Dārchulā','Darchula',29.8412,80.5287,'P','PPL','NP.WR',NULL,18317,'Asia/Kathmandu',1,'2013-11-03 23:00:00','2013-11-03 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283496,'NP','Dailekh','Dailekh',28.8443,81.7101,'P','PPLA3','NP.MR','NP.MR.02',20908,'Asia/Kathmandu',1,'2011-07-30 23:00:00','2011-07-30 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283499,'NP','Dadeldhurā','Dadeldhura',29.2984,80.5806,'P','PPL','NP.FR',NULL,19014,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283562,'NP','Butwāl','Butwal',27.7006,83.4484,'P','PPL','NP.WR',NULL,91733,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283581,'NP','Bīrganj','Birganj',27.0104,84.8773,'P','PPL','NP.CR',NULL,133238,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283582,'NP','Biratnagar','Biratnagar',26.455,87.2701,'P','PPL','NP.ER','NP.ER.07',182324,'Asia/Kathmandu',1,'2016-04-11 23:00:00','2016-04-11 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283613,'NP','Bharatpur','Bharatpur',27.6833,84.4333,'P','PPL','NP.CR','NP.CR.11',107157,'Asia/Kathmandu',1,'2013-11-09 23:00:00','2013-11-09 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283621,'NP','Siddharthanagar','Siddharthanagar',27.5,83.45,'P','PPLA3','NP.WR','NP.WR.08',63367,'Asia/Kathmandu',1,'2016-11-02 23:00:00','2016-11-02 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283628,'NP','Bhadrapur','Bhadrapur',26.544,88.0944,'P','PPL','NP.ER',NULL,19523,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283679,'NP','Banepā','Banepa',27.6325,85.5219,'P','PPL','NP.CR',NULL,17153,'Asia/Kathmandu',1,'2015-06-07 23:00:00','2015-06-07 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (1283711,'NP','Bāglung','Baglung',28.2719,83.5898,'P','PPL','NP.WR',NULL,23296,'Asia/Kathmandu',1,'2013-05-20 23:00:00','2013-05-20 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (6254842,'NP','Besisahar','Besisahar',28.2342,82.4128,'P','PPL','NP.00',NULL,5427,'Asia/Kathmandu',1,'2014-01-14 23:00:00','2014-01-14 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (6254843,'NP','Birendranagar','Birendranagar',28.6019,81.6339,'P','PPLA','NP.MR',NULL,31381,'Asia/Kathmandu',1,'2011-03-09 23:00:00','2011-03-09 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (6254845,'NP','Dipayal','Dipayal',29.2608,80.94,'P','PPLA','NP.FR',NULL,23416,'Asia/Kathmandu',1,'2013-08-04 23:00:00','2013-08-04 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (6941099,'NP','Nepalgunj','Nepalgunj',28.05,81.6167,'P','PPL','NP.MR','NP.MR.02',64400,'Asia/Kathmandu',1,'2015-04-27 23:00:00','2015-04-27 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (8199102,'NP','kankrabari Dovan','kankrabari Dovan',27.6288,85.4593,'P','PPL','NP.CR','NP.CR.01',10000,'Asia/Kathmandu',1,'2012-02-11 23:00:00','2012-02-11 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (8504556,'NP','Hari Bdr Tamang House','Hari Bdr Tamang House',27.6289,85.4589,'P','PPL','NP.CR','NP.CR.01',10000,'Asia/Kathmandu',1,'2013-03-11 23:00:00','2013-03-11 23:00:00');
INSERT INTO `<<prefix>>cities` VALUES (11154397,'NP','Bhattarai Danda','Bhattarai Danda',27.8833,83.9333,'P','PPL','NP.WR','NP.WR.04',5510,'Asia/Kathmandu',1,'2017-05-04 23:00:00','2017-05-04 23:00:00');
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
