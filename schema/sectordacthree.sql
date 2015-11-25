SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aidstream`
--
-- --------------------------------------------------------
--
-- Table structure for table `ActivityDateType`
--

CREATE TABLE IF NOT EXISTS `SectorDacThree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

LOCK TABLES `SectorDacThree` WRITE;
/*!40000 ALTER TABLE `SectorDacThree` DISABLE KEYS */;

--
-- Dumping data for table `SectorDacThree`
--

INSERT INTO `SectorDacThree` (`id`, `Code`, `Name`, `lang_id`)
VALUES
	(1,111,'Education, level unspecified',1),
	(2,112,'Basic education',1),
	(3,113,'Secondary education',1),
	(4,114,'Post-secondary education',1),
	(5,121,'Health, general',1),
	(6,122,'Basic health',1),
	(7,130,'Population policies/Programmes and reproductive health',1),
	(8,140,'Water and sanitation',1),
	(9,151,'Government and civil society, general',1),
	(10,152,'Conflict prevention and resolution, peace and security',1),
	(11,160,'Other social infrastructure and services',1),
	(12,210,'Transport and storage',1),
	(13,220,'Communication',1),
	(14,230,'Energy generation and supply',1),
	(15,240,'Banking and financial services',1),
	(16,250,'Business and other services',1),
	(17,311,'Agriculture',1),
	(18,312,'Forestry',1),
	(19,313,'Fishing',1),
	(20,321,'Industry',1),
	(21,322,'Mineral resources and mining',1),
	(22,323,'Construction',1),
	(23,331,'Trade policy and regulations and trade-related adjustment',1),
	(24,332,'Tourism',1),
	(25,410,'General environmental protection',1),
	(26,430,'Other multisector',1),
	(27,510,'General budget support',1),
	(28,520,'Developmental food aid/Food security assistance',1),
	(29,530,'Other commodity assistance',1),
	(30,600,'Action relating to debt',1),
	(31,720,'Emergency Response',1),
	(32,730,'Reconstruction relief and rehabilitation',1),
	(33,740,'Disaster prevention and preparedness',1),
	(34,910,'Administrative costs of donors',1),
	(35,920,'Support to non-governmental organisations (NGOs)',1),
	(36,930,'Refugees in donor countries',1),
	(37,998,'Unallocated/ Unspecified',1);

/*!40000 ALTER TABLE `SectorDacThree` ENABLE KEYS */;
UNLOCK TABLES;
