-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: iati_aims_db
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.12.10.1

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
-- Table structure for table `ActivityDateType`
--

DROP TABLE IF EXISTS `ActivityDateType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ActivityDateType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ActivityScope`
--

DROP TABLE IF EXISTS `ActivityScope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ActivityScope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(50) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ActivityStatus`
--

DROP TABLE IF EXISTS `ActivityStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ActivityStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AdministrativeAreaCode(First-level)`
--

DROP TABLE IF EXISTS `AdministrativeAreaCode(First-level)`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AdministrativeAreaCode(First-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) DEFAULT NULL,
  `Country` varchar(40) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AdministrativeAreaCode(Second-level)`
--

DROP TABLE IF EXISTS `AdministrativeAreaCode(Second-level)`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AdministrativeAreaCode(Second-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) DEFAULT NULL,
  `Country` varchar(40) DEFAULT NULL,
  `First-Level Administrative Area` varchar(50) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AidType`
--

DROP TABLE IF EXISTS `AidType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AidType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `CategoryCode` char(1) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AidTypeCategory`
--

DROP TABLE IF EXISTS `AidTypeCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AidTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BudgetIdentifier`
--

DROP TABLE IF EXISTS `BudgetIdentifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BudgetIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Sector` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BudgetIdentifierVocabulary`
--

DROP TABLE IF EXISTS `BudgetIdentifierVocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BudgetIdentifierVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BudgetType`
--

DROP TABLE IF EXISTS `BudgetType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BudgetType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ChangeLog`
--

DROP TABLE IF EXISTS `ChangeLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChangeLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `value` text NOT NULL,
  `updated_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `updated_datetime` (`updated_datetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CodeLists`
--

DROP TABLE IF EXISTS `CodeLists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CodeLists` (
  `codelist_id` int(11) NOT NULL AUTO_INCREMENT,
  `codelist` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `used_in` text NOT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`codelist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CollaborationType`
--

DROP TABLE IF EXISTS `CollaborationType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CollaborationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ConditionType`
--

DROP TABLE IF EXISTS `ConditionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ConditionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contact`
--

DROP TABLE IF EXISTS `Contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Currency`
--

DROP TABLE IF EXISTS `Currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DescriptionType`
--

DROP TABLE IF EXISTS `DescriptionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DescriptionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DisbursementChannel`
--

DROP TABLE IF EXISTS `DisbursementChannel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DisbursementChannel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DocumentCategory`
--

DROP TABLE IF EXISTS `DocumentCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DocumentCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CategoryCode` char(1) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DocumentCategoryCategory`
--

DROP TABLE IF EXISTS `DocumentCategoryCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DocumentCategoryCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FileFormat`
--

DROP TABLE IF EXISTS `FileFormat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FileFormat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FinanceType`
--

DROP TABLE IF EXISTS `FinanceType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FinanceType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FinanceTypeCategory`
--

DROP TABLE IF EXISTS `FinanceTypeCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FinanceTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FlowType`
--

DROP TABLE IF EXISTS `FlowType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FlowType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GazetteerAgency`
--

DROP TABLE IF EXISTS `GazetteerAgency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GazetteerAgency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GeographicalPrecision`
--

DROP TABLE IF EXISTS `GeographicalPrecision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GeographicalPrecision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Help`
--

DROP TABLE IF EXISTS `Help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Help-local`
--

DROP TABLE IF EXISTS `Help-local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Help-local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `IndicatorMeasure`
--

DROP TABLE IF EXISTS `IndicatorMeasure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IndicatorMeasure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Lang`
--

DROP TABLE IF EXISTS `Lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Lang` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Language`
--

DROP TABLE IF EXISTS `Language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LocationType`
--

DROP TABLE IF EXISTS `LocationType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LocationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationIdentifier`
--

DROP TABLE IF EXISTS `OrganisationIdentifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationIdentifierBilateral`
--

DROP TABLE IF EXISTS `OrganisationIdentifierBilateral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationIdentifierBilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationIdentifierIngo`
--

DROP TABLE IF EXISTS `OrganisationIdentifierIngo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationIdentifierIngo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationIdentifierMultilateral`
--

DROP TABLE IF EXISTS `OrganisationIdentifierMultilateral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationIdentifierMultilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationRole`
--

DROP TABLE IF EXISTS `OrganisationRole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationRole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OrganisationType`
--

DROP TABLE IF EXISTS `OrganisationType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganisationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PercisionCode`
--

DROP TABLE IF EXISTS `PercisionCode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PercisionCode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(11) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Description` text NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PolicyMarker`
--

DROP TABLE IF EXISTS `PolicyMarker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PolicyMarker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PolicySignificance`
--

DROP TABLE IF EXISTS `PolicySignificance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PolicySignificance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Privilege`
--

DROP TABLE IF EXISTS `Privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Privilege` (
  `privilege_id` int(10) NOT NULL AUTO_INCREMENT,
  `resource` text NOT NULL,
  `owner_id` int(10) NOT NULL,
  PRIMARY KEY (`privilege_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PublisherType`
--

DROP TABLE IF EXISTS `PublisherType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PublisherType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Region`
--

DROP TABLE IF EXISTS `Region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RegionVocabulary`
--

DROP TABLE IF EXISTS `RegionVocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RegionVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RelatedActivityType`
--

DROP TABLE IF EXISTS `RelatedActivityType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RelatedActivityType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ResultType`
--

DROP TABLE IF EXISTS `ResultType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResultType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Sector`
--

DROP TABLE IF EXISTS `Sector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SectorCategory`
--

DROP TABLE IF EXISTS `SectorCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SectorCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `ParentCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TiedStatus`
--

DROP TABLE IF EXISTS `TiedStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TiedStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TransactionType`
--

DROP TABLE IF EXISTS `TransactionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TransactionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VerificationStatus`
--

DROP TABLE IF EXISTS `VerificationStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VerificationStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Vocabulary`
--

DROP TABLE IF EXISTS `Vocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `address` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `uniqid` varchar(35) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `file_name` varchar(255) DEFAULT NULL,
  `display_in_footer` tinyint(1) DEFAULT '0',
  `url` text,
  `simplified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activity_hash`
--

DROP TABLE IF EXISTS `activity_hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `hash` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `default_field_groups`
--

DROP TABLE IF EXISTS `default_field_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_field_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `default_field_values`
--

DROP TABLE IF EXISTS `default_field_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_field_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `default_user_field`
--

DROP TABLE IF EXISTS `default_user_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_user_field` (
  `default_user_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`default_user_field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `displayed_subelements`
--

DROP TABLE IF EXISTS `displayed_subelements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `displayed_subelements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elements` text NOT NULL,
  `org_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activities`
--

DROP TABLE IF EXISTS `iati_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@generated_datetime` varchar(25) NOT NULL,
  `@version` varchar(10) NOT NULL,
  `unqid` varchar(256) NOT NULL,
  `user_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activity`
--

DROP TABLE IF EXISTS `iati_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(5) NOT NULL,
  `@default_currency` varchar(5) NOT NULL,
  `@hierarchy` int(3) NOT NULL,
  `@last_updated_datetime` varchar(25) NOT NULL,
  `@linked_data_uri` varchar(255) DEFAULT NULL,
  `activities_id` int(10) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activity_date`
--

DROP TABLE IF EXISTS `iati_activity_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activity_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(10) DEFAULT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activity_scope`
--

DROP TABLE IF EXISTS `iati_activity_scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activity_scope` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activity_status`
--

DROP TABLE IF EXISTS `iati_activity_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activity_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_activity_website`
--

DROP TABLE IF EXISTS `iati_activity_website`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_activity_website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_aid_type`
--

DROP TABLE IF EXISTS `iati_aid_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_budget`
--

DROP TABLE IF EXISTS `iati_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_budget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_budget/period_end`
--

DROP TABLE IF EXISTS `iati_budget/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_budget/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_budget/period_start`
--

DROP TABLE IF EXISTS `iati_budget/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_budget/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_budget/value`
--

DROP TABLE IF EXISTS `iati_budget/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_budget/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(20) NOT NULL,
  `@currency` varchar(20) DEFAULT NULL,
  `text` text NOT NULL,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_capital_spend`
--

DROP TABLE IF EXISTS `iati_capital_spend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_capital_spend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_collaboration_type`
--

DROP TABLE IF EXISTS `iati_collaboration_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_collaboration_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_conditions`
--

DROP TABLE IF EXISTS `iati_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_conditions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@attached` varchar(3) NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_conditions/condition`
--

DROP TABLE IF EXISTS `iati_conditions/condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_conditions/condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@type` int(11) DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `text` text,
  `conditions_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info`
--

DROP TABLE IF EXISTS `iati_contact_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `activity_id` int(10) NOT NULL,
  `@type` text NOT NULL,
  `@xml_lang` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/email`
--

DROP TABLE IF EXISTS `iati_contact_info/email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/email` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/job_title`
--

DROP TABLE IF EXISTS `iati_contact_info/job_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/job_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(25) NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/mailing_address`
--

DROP TABLE IF EXISTS `iati_contact_info/mailing_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/mailing_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/organisation`
--

DROP TABLE IF EXISTS `iati_contact_info/organisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/organisation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/person_name`
--

DROP TABLE IF EXISTS `iati_contact_info/person_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/person_name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/telephone`
--

DROP TABLE IF EXISTS `iati_contact_info/telephone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/telephone` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_contact_info/website`
--

DROP TABLE IF EXISTS `iati_contact_info/website`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_contact_info/website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_country_budget_items`
--

DROP TABLE IF EXISTS `iati_country_budget_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_country_budget_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(25) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_country_budget_items/budget_item`
--

DROP TABLE IF EXISTS `iati_country_budget_items/budget_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_country_budget_items/budget_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(50) NOT NULL,
  `@percentage` varchar(25) NOT NULL,
  `country_budget_items_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_country_budget_items/budget_item/description`
--

DROP TABLE IF EXISTS `iati_country_budget_items/budget_item/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_country_budget_items/budget_item/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `budget_item_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_default_aid_type`
--

DROP TABLE IF EXISTS `iati_default_aid_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_default_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_default_finance_type`
--

DROP TABLE IF EXISTS `iati_default_finance_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_default_finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_default_flow_type`
--

DROP TABLE IF EXISTS `iati_default_flow_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_default_flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_default_tied_status`
--

DROP TABLE IF EXISTS `iati_default_tied_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_default_tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_description`
--

DROP TABLE IF EXISTS `iati_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_document_link`
--

DROP TABLE IF EXISTS `iati_document_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_document_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@url` text NOT NULL,
  `@format` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_document_link/category`
--

DROP TABLE IF EXISTS `iati_document_link/category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_document_link/category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_document_link/language`
--

DROP TABLE IF EXISTS `iati_document_link/language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_document_link/language` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_document_link/title`
--

DROP TABLE IF EXISTS `iati_document_link/title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_document_link/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_gazetteer_entry`
--

DROP TABLE IF EXISTS `iati_gazetteer_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_gazetteer_entry` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_identifier`
--

DROP TABLE IF EXISTS `iati_identifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `activity_identifier` varchar(255) NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_indicator/actual`
--

DROP TABLE IF EXISTS `iati_indicator/actual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_indicator/baseline`
--

DROP TABLE IF EXISTS `iati_indicator/baseline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_indicator/description`
--

DROP TABLE IF EXISTS `iati_indicator/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_indicator/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_indicator/target`
--

DROP TABLE IF EXISTS `iati_indicator/target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_indicator/title`
--

DROP TABLE IF EXISTS `iati_indicator/title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_indicator/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_legacy_data`
--

DROP TABLE IF EXISTS `iati_legacy_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_legacy_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@name` varchar(255) NOT NULL,
  `@value` varchar(255) NOT NULL,
  `@iati_equivalent` text,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location`
--

DROP TABLE IF EXISTS `iati_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/administrative`
--

DROP TABLE IF EXISTS `iati_location/administrative`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/administrative` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@country` varchar(25) DEFAULT NULL,
  `@adm1` varchar(25) DEFAULT NULL,
  `@adm2` varchar(25) DEFAULT NULL,
  `text` text,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/coordinates`
--

DROP TABLE IF EXISTS `iati_location/coordinates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/coordinates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@latitude` varchar(25) NOT NULL,
  `@longitude` varchar(25) NOT NULL,
  `@precision` varchar(25) DEFAULT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/description`
--

DROP TABLE IF EXISTS `iati_location/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/gazetteer_entry`
--

DROP TABLE IF EXISTS `iati_location/gazetteer_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/gazetteer_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@gazetteer_ref` int(11) NOT NULL,
  `text` varchar(50) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/location_type`
--

DROP TABLE IF EXISTS `iati_location/location_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/location_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_location/name`
--

DROP TABLE IF EXISTS `iati_location/name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_location/name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation`
--

DROP TABLE IF EXISTS `iati_organisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `@last_updated_datetime` varchar(25) NOT NULL,
  `@default_currency` varchar(25) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/document_link`
--

DROP TABLE IF EXISTS `iati_organisation/document_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/document_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@url` text,
  `@format` varchar(25) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/document_link/category`
--

DROP TABLE IF EXISTS `iati_organisation/document_link/category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/document_link/category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/document_link/language`
--

DROP TABLE IF EXISTS `iati_organisation/document_link/language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/document_link/language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(25) DEFAULT NULL,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/document_link/title`
--

DROP TABLE IF EXISTS `iati_organisation/document_link/title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/document_link/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/identifier`
--

DROP TABLE IF EXISTS `iati_organisation/identifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/name`
--

DROP TABLE IF EXISTS `iati_organisation/name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_country_budget`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_country_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_country_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_end`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_country_budget/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_country_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_start`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_country_budget/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_country_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_country_budget/recipient_country`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_country_budget/recipient_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_country_budget/recipient_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_country_budget/value`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_country_budget/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_country_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_org_budget`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_org_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_org_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_end`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_org_budget/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_org_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_start`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_org_budget/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_org_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_org_budget/recipient_org`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_org_budget/recipient_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_org_budget/recipient_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/recipient_org_budget/value`
--

DROP TABLE IF EXISTS `iati_organisation/recipient_org_budget/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/recipient_org_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/reporting_org`
--

DROP TABLE IF EXISTS `iati_organisation/reporting_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) NOT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/total_budget`
--

DROP TABLE IF EXISTS `iati_organisation/total_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/total_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/total_budget/period_end`
--

DROP TABLE IF EXISTS `iati_organisation/total_budget/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/total_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/total_budget/period_start`
--

DROP TABLE IF EXISTS `iati_organisation/total_budget/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/total_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_organisation/total_budget/value`
--

DROP TABLE IF EXISTS `iati_organisation/total_budget/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_organisation/total_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_other_identifier`
--

DROP TABLE IF EXISTS `iati_other_identifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_other_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@owner_ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@owner_name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_participating_org`
--

DROP TABLE IF EXISTS `iati_participating_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_participating_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@role` varchar(25) NOT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_planned_disbursement`
--

DROP TABLE IF EXISTS `iati_planned_disbursement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_planned_disbursement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@updated` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_planned_disbursement/period_end`
--

DROP TABLE IF EXISTS `iati_planned_disbursement/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_planned_disbursement/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_planned_disbursement/period_start`
--

DROP TABLE IF EXISTS `iati_planned_disbursement/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_planned_disbursement/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_planned_disbursement/value`
--

DROP TABLE IF EXISTS `iati_planned_disbursement/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_planned_disbursement/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(25) NOT NULL,
  `@currency` varchar(25) DEFAULT NULL,
  `text` text NOT NULL,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_policy_marker`
--

DROP TABLE IF EXISTS `iati_policy_marker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_policy_marker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@significance` varchar(10) DEFAULT NULL,
  `@vocabulary` varchar(25) DEFAULT NULL,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_recipient_country`
--

DROP TABLE IF EXISTS `iati_recipient_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_recipient_country` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@percentage` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_recipient_region`
--

DROP TABLE IF EXISTS `iati_recipient_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_recipient_region` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@vocabulary` varchar(25) NOT NULL,
  `@percentage` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_related_activity`
--

DROP TABLE IF EXISTS `iati_related_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_related_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) NOT NULL,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_reporting_org`
--

DROP TABLE IF EXISTS `iati_reporting_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result`
--

DROP TABLE IF EXISTS `iati_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) NOT NULL,
  `@aggregation_status` varchar(5) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/description`
--

DROP TABLE IF EXISTS `iati_result/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `text` text NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator`
--

DROP TABLE IF EXISTS `iati_result/indicator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@measure` int(11) NOT NULL,
  `@ascending` varchar(5) NOT NULL DEFAULT 'true',
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/actual`
--

DROP TABLE IF EXISTS `iati_result/indicator/actual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/baseline`
--

DROP TABLE IF EXISTS `iati_result/indicator/baseline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/baseline/comment`
--

DROP TABLE IF EXISTS `iati_result/indicator/baseline/comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/baseline/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `baseline_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/description`
--

DROP TABLE IF EXISTS `iati_result/indicator/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period`
--

DROP TABLE IF EXISTS `iati_result/indicator/period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/actual`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/actual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/actual/comment`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/actual/comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/actual/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `actual_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/period-end`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/period-end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/period-end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/period-start`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/period-start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/period-start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/target`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/period/target/comment`
--

DROP TABLE IF EXISTS `iati_result/indicator/period/target/comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/period/target/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `target_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/target`
--

DROP TABLE IF EXISTS `iati_result/indicator/target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/indicator/title`
--

DROP TABLE IF EXISTS `iati_result/indicator/title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/indicator/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_result/title`
--

DROP TABLE IF EXISTS `iati_result/title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_result/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_sector`
--

DROP TABLE IF EXISTS `iati_sector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_sector` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(255) DEFAULT NULL,
  `@code` varchar(255) DEFAULT NULL,
  `@percentage` varchar(255) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_title`
--

DROP TABLE IF EXISTS `iati_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction`
--

DROP TABLE IF EXISTS `iati_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/aid_type`
--

DROP TABLE IF EXISTS `iati_transaction/aid_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/description`
--

DROP TABLE IF EXISTS `iati_transaction/description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/disbursement_channel`
--

DROP TABLE IF EXISTS `iati_transaction/disbursement_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/disbursement_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `text` text,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/distribution_channel`
--

DROP TABLE IF EXISTS `iati_transaction/distribution_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/distribution_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/finance_type`
--

DROP TABLE IF EXISTS `iati_transaction/finance_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(20) NOT NULL,
  `text` text,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/flow_type`
--

DROP TABLE IF EXISTS `iati_transaction/flow_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/provider_org`
--

DROP TABLE IF EXISTS `iati_transaction/provider_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/provider_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `@provider_activity_id` text,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/receiver_org`
--

DROP TABLE IF EXISTS `iati_transaction/receiver_org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/receiver_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `@receiver_activity_id` text,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/tied_status`
--

DROP TABLE IF EXISTS `iati_transaction/tied_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/transaction_date`
--

DROP TABLE IF EXISTS `iati_transaction/transaction_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/transaction_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) NOT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/transaction_type`
--

DROP TABLE IF EXISTS `iati_transaction/transaction_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/transaction_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iati_transaction/value`
--

DROP TABLE IF EXISTS `iati_transaction/value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iati_transaction/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation/annual_planning_budget`
--

DROP TABLE IF EXISTS `organisation/annual_planning_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation/annual_planning_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation/annual_planning_budget/period_end`
--

DROP TABLE IF EXISTS `organisation/annual_planning_budget/period_end`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation/annual_planning_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation/annual_planning_budget/period_start`
--

DROP TABLE IF EXISTS `organisation/annual_planning_budget/period_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation/annual_planning_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation/annual_planning_budget/period_start/test`
--

DROP TABLE IF EXISTS `organisation/annual_planning_budget/period_start/test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation/annual_planning_budget/period_start/test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `period_start_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation/name`
--

DROP TABLE IF EXISTS `organisation/name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `xml_lang` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_hash`
--

DROP TABLE IF EXISTS `organisation_hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  `hash` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_published`
--

DROP TABLE IF EXISTS `organisation_published`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_published` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publishing_org_id` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 NOT NULL,
  `data_updated_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pushed_to_registry` int(11) NOT NULL DEFAULT '0',
  `organisation_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_registry_published_data`
--

DROP TABLE IF EXISTS `organisation_registry_published_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `middle_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `published`
--

DROP TABLE IF EXISTS `published`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `published` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publishing_org_id` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `activity_count` int(11) DEFAULT NULL,
  `data_updated_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pushed_to_registry` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registry_info`
--

DROP TABLE IF EXISTS `registry_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registry_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `publishing_type` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `update_registry` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registry_published_data`
--

DROP TABLE IF EXISTS `registry_published_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reset`
--

DROP TABLE IF EXISTS `reset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `value` varchar(50) NOT NULL,
  `reset_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `role` varchar(15) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `support`
--

DROP TABLE IF EXISTS `support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `role_id` int(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `account_id` int(10) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_documents`
--

DROP TABLE IF EXISTS `user_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `uploaded_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_permission`
--

DROP TABLE IF EXISTS `user_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_register`
--

DROP TABLE IF EXISTS `user_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) NOT NULL,
  `org_address` text NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `publisher_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-18 13:19:34
