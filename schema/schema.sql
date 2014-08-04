-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 27, 2014 at 11:20 AM
-- Server version: 5.5.37-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aidstream`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `twitter` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `uniqid` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_in_footer` tinyint(1) DEFAULT '1',
  `url` mediumtext COLLATE utf8_unicode_ci,
  `simplified` int(11) NOT NULL DEFAULT '0',
  `disqus_comments` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityDateType`
--

CREATE TABLE `ActivityDateType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityScope`
--

CREATE TABLE `ActivityScope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityStatus`
--

CREATE TABLE `ActivityStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_hash`
--

CREATE TABLE `activity_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `hash` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1221 ;

-- --------------------------------------------------------

--
-- Table structure for table `AdministrativeAreaCode(First-level)`
--

CREATE TABLE `AdministrativeAreaCode(First-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AdministrativeAreaCode(Second-level)`
--

CREATE TABLE `AdministrativeAreaCode(Second-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `First-Level Administrative Area` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AidType`
--

CREATE TABLE `AidType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `CategoryCode` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `AidTypeCategory`
--

CREATE TABLE `AidTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetIdentifier`
--

CREATE TABLE `BudgetIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Sector` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=154 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetIdentifierVocabulary`
--

CREATE TABLE `BudgetIdentifierVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetType`
--

CREATE TABLE `BudgetType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `ChangeLog`
--

CREATE TABLE `ChangeLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `updated_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `updated_datetime` (`updated_datetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CodeLists`
--

CREATE TABLE `CodeLists` (
  `codelist_id` int(11) NOT NULL AUTO_INCREMENT,
  `codelist` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `used_in` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`codelist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `CollaborationType`
--

CREATE TABLE `CollaborationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `ConditionType`
--

CREATE TABLE `ConditionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE `Contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=576 ;

-- --------------------------------------------------------

--
-- Table structure for table `Country`
--

CREATE TABLE `Country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=651 ;

-- --------------------------------------------------------

--
-- Table structure for table `Currency`
--

CREATE TABLE `Currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=499 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_field_groups`
--

CREATE TABLE `default_field_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_field_values`
--

CREATE TABLE `default_field_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_user_field`
--

CREATE TABLE `default_user_field` (
  `default_user_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `object` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`default_user_field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DescriptionType`
--

CREATE TABLE `DescriptionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `DisbursementChannel`
--

CREATE TABLE `DisbursementChannel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `displayed_subelements`
--

CREATE TABLE `displayed_subelements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elements` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentCategory`
--

CREATE TABLE `DocumentCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CategoryCode` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentCategoryCategory`
--

CREATE TABLE `DocumentCategoryCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `FileFormat`
--

CREATE TABLE `FileFormat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `FinanceType`
--

CREATE TABLE `FinanceType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Table structure for table `FinanceTypeCategory`
--

CREATE TABLE `FinanceTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `FlowType`
--

CREATE TABLE `FlowType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `GazetteerAgency`
--

CREATE TABLE `GazetteerAgency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `GeographicalPrecision`
--

CREATE TABLE `GeographicalPrecision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Table structure for table `Help`
--

CREATE TABLE `Help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=332 ;

-- --------------------------------------------------------

--
-- Table structure for table `Help-local`
--

CREATE TABLE `Help-local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activities`
--

CREATE TABLE `iati_activities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@generated_datetime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@version` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `unqid` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=335 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity`
--

CREATE TABLE `iati_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `@default_currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `@hierarchy` int(3) NOT NULL,
  `@last_updated_datetime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@linked_data_uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activities_id` int(10) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1233 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_date`
--

CREATE TABLE `iati_activity_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1805 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_scope`
--

CREATE TABLE `iati_activity_scope` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_status`
--

CREATE TABLE `iati_activity_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1015 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_website`
--

CREATE TABLE `iati_activity_website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=237 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_aid_type`
--

CREATE TABLE `iati_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget`
--

CREATE TABLE `iati_budget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1295 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/period_end`
--

CREATE TABLE `iati_budget/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1295 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/period_start`
--

CREATE TABLE `iati_budget/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1295 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/value`
--

CREATE TABLE `iati_budget/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `@currency` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1295 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_capital_spend`
--

CREATE TABLE `iati_capital_spend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_collaboration_type`
--

CREATE TABLE `iati_collaboration_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=400 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_conditions`
--

CREATE TABLE `iati_conditions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@attached` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_conditions/condition`
--

CREATE TABLE `iati_conditions/condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@type` int(11) DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `conditions_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info`
--

CREATE TABLE `iati_contact_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `activity_id` int(10) NOT NULL,
  `@type` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=587 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/email`
--

CREATE TABLE `iati_contact_info/email` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=547 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/job_title`
--

CREATE TABLE `iati_contact_info/job_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=97 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/mailing_address`
--

CREATE TABLE `iati_contact_info/mailing_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=468 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/organisation`
--

CREATE TABLE `iati_contact_info/organisation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=554 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/person_name`
--

CREATE TABLE `iati_contact_info/person_name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=428 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/telephone`
--

CREATE TABLE `iati_contact_info/telephone` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=385 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/website`
--

CREATE TABLE `iati_contact_info/website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items`
--

CREATE TABLE `iati_country_budget_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items/budget_item`
--

CREATE TABLE `iati_country_budget_items/budget_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `@percentage` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `country_budget_items_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items/budget_item/description`
--

CREATE TABLE `iati_country_budget_items/budget_item/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `budget_item_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_aid_type`
--

CREATE TABLE `iati_default_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=784 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_finance_type`
--

CREATE TABLE `iati_default_finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=388 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_flow_type`
--

CREATE TABLE `iati_default_flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=748 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_tied_status`
--

CREATE TABLE `iati_default_tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=375 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_description`
--

CREATE TABLE `iati_description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1132 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link`
--

CREATE TABLE `iati_document_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@url` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `@format` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=898 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/category`
--

CREATE TABLE `iati_document_link/category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=968 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/language`
--

CREATE TABLE `iati_document_link/language` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=594 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/title`
--

CREATE TABLE `iati_document_link/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=971 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_gazetteer_entry`
--

CREATE TABLE `iati_gazetteer_entry` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_identifier`
--

CREATE TABLE `iati_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1233 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/actual`
--

CREATE TABLE `iati_indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/baseline`
--

CREATE TABLE `iati_indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/description`
--

CREATE TABLE `iati_indicator/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/target`
--

CREATE TABLE `iati_indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/title`
--

CREATE TABLE `iati_indicator/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_legacy_data`
--

CREATE TABLE `iati_legacy_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@iati_equivalent` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location`
--

CREATE TABLE `iati_location` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/administrative`
--

CREATE TABLE `iati_location/administrative` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@country` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@adm1` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@adm2` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/coordinates`
--

CREATE TABLE `iati_location/coordinates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@latitude` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@longitude` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@precision` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=70 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/description`
--

CREATE TABLE `iati_location/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/gazetteer_entry`
--

CREATE TABLE `iati_location/gazetteer_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@gazetteer_ref` int(11) NOT NULL,
  `text` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/location_type`
--

CREATE TABLE `iati_location/location_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=114 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/name`
--

CREATE TABLE `iati_location/name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=169 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation`
--

CREATE TABLE `iati_organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@last_updated_datetime` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@default_currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link`
--

CREATE TABLE `iati_organisation/document_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@url` mediumtext COLLATE utf8_unicode_ci,
  `@format` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/category`
--

CREATE TABLE `iati_organisation/document_link/category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/language`
--

CREATE TABLE `iati_organisation/document_link/language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/title`
--

CREATE TABLE `iati_organisation/document_link/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/identifier`
--

CREATE TABLE `iati_organisation/identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/name`
--

CREATE TABLE `iati_organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget`
--

CREATE TABLE `iati_organisation/recipient_country_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_end`
--

CREATE TABLE `iati_organisation/recipient_country_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_start`
--

CREATE TABLE `iati_organisation/recipient_country_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/recipient_country`
--

CREATE TABLE `iati_organisation/recipient_country_budget/recipient_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/value`
--

CREATE TABLE `iati_organisation/recipient_country_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@value_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget`
--

CREATE TABLE `iati_organisation/recipient_org_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_end`
--

CREATE TABLE `iati_organisation/recipient_org_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_start`
--

CREATE TABLE `iati_organisation/recipient_org_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/recipient_org`
--

CREATE TABLE `iati_organisation/recipient_org_budget/recipient_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/value`
--

CREATE TABLE `iati_organisation/recipient_org_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@value_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/reporting_org`
--

CREATE TABLE `iati_organisation/reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget`
--

CREATE TABLE `iati_organisation/total_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/period_end`
--

CREATE TABLE `iati_organisation/total_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/period_start`
--

CREATE TABLE `iati_organisation/total_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/value`
--

CREATE TABLE `iati_organisation/total_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@value_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_other_identifier`
--

CREATE TABLE `iati_other_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@owner_ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@owner_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_participating_org`
--

CREATE TABLE `iati_participating_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@role` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2848 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement`
--

CREATE TABLE `iati_planned_disbursement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@updated` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/period_end`
--

CREATE TABLE `iati_planned_disbursement/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/period_start`
--

CREATE TABLE `iati_planned_disbursement/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/value`
--

CREATE TABLE `iati_planned_disbursement/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_policy_marker`
--

CREATE TABLE `iati_policy_marker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@significance` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@vocabulary` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_recipient_country`
--

CREATE TABLE `iati_recipient_country` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@percentage` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1130 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_recipient_region`
--

CREATE TABLE `iati_recipient_region` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@vocabulary` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@percentage` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=465 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_related_activity`
--

CREATE TABLE `iati_related_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_reporting_org`
--

CREATE TABLE `iati_reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1233 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result`
--

CREATE TABLE `iati_result` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@aggregation_status` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/description`
--

CREATE TABLE `iati_result/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator`
--

CREATE TABLE `iati_result/indicator` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@measure` int(11) NOT NULL,
  `@ascending` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true',
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/actual`
--

CREATE TABLE `iati_result/indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/baseline`
--

CREATE TABLE `iati_result/indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/baseline/comment`
--

CREATE TABLE `iati_result/indicator/baseline/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `baseline_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/description`
--

CREATE TABLE `iati_result/indicator/description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period`
--

CREATE TABLE `iati_result/indicator/period` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/actual`
--

CREATE TABLE `iati_result/indicator/period/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/actual/comment`
--

CREATE TABLE `iati_result/indicator/period/actual/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `actual_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/period-end`
--

CREATE TABLE `iati_result/indicator/period/period-end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/period-start`
--

CREATE TABLE `iati_result/indicator/period/period-start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/target`
--

CREATE TABLE `iati_result/indicator/period/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/target/comment`
--

CREATE TABLE `iati_result/indicator/period/target/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `target_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/target`
--

CREATE TABLE `iati_result/indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `@value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/title`
--

CREATE TABLE `iati_result/indicator/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/title`
--

CREATE TABLE `iati_result/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_sector`
--

CREATE TABLE `iati_sector` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@percentage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1408 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_title`
--

CREATE TABLE `iati_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1128 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction`
--

CREATE TABLE `iati_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7816 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/aid_type`
--

CREATE TABLE `iati_transaction/aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2690 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/description`
--

CREATE TABLE `iati_transaction/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4528 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/disbursement_channel`
--

CREATE TABLE `iati_transaction/disbursement_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `@xml_lang` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1830 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/distribution_channel`
--

CREATE TABLE `iati_transaction/distribution_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/finance_type`
--

CREATE TABLE `iati_transaction/finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `@xml_lang` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2007 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/flow_type`
--

CREATE TABLE `iati_transaction/flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2807 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/provider_org`
--

CREATE TABLE `iati_transaction/provider_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@provider_activity_id` mediumtext COLLATE utf8_unicode_ci,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5490 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/receiver_org`
--

CREATE TABLE `iati_transaction/receiver_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@receiver_activity_id` mediumtext COLLATE utf8_unicode_ci,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5685 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/tied_status`
--

CREATE TABLE `iati_transaction/tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `@xml_lang` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1762 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/transaction_date`
--

CREATE TABLE `iati_transaction/transaction_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7499 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/transaction_type`
--

CREATE TABLE `iati_transaction/transaction_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7847 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/value`
--

CREATE TABLE `iati_transaction/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `@value_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7848 ;

-- --------------------------------------------------------

--
-- Table structure for table `IndicatorMeasure`
--

CREATE TABLE `IndicatorMeasure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Lang`
--

CREATE TABLE `Lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Lang` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Language`
--

CREATE TABLE `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=544 ;

-- --------------------------------------------------------

--
-- Table structure for table `LocationType`
--

CREATE TABLE `LocationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget`
--

CREATE TABLE `organisation/annual_planning_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_end`
--

CREATE TABLE `organisation/annual_planning_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_start`
--

CREATE TABLE `organisation/annual_planning_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_start/test`
--

CREATE TABLE `organisation/annual_planning_budget/period_start/test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period_start_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/name`
--

CREATE TABLE `organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `xml_lang` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifier`
--

CREATE TABLE `OrganisationIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierBilateral`
--

CREATE TABLE `OrganisationIdentifierBilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierIngo`
--

CREATE TABLE `OrganisationIdentifierIngo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierMultilateral`
--

CREATE TABLE `OrganisationIdentifierMultilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationRole`
--

CREATE TABLE `OrganisationRole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationType`
--

CREATE TABLE `OrganisationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_hash`
--

CREATE TABLE `organisation_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  `hash` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_published`
--

CREATE TABLE `organisation_published` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publishing_org_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data_updated_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pushed_to_registry` int(11) NOT NULL DEFAULT '0',
  `organisation_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=131 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_registry_published_data`
--

CREATE TABLE `organisation_registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `response` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=262 ;

-- --------------------------------------------------------

--
-- Table structure for table `PercisionCode`
--

CREATE TABLE `PercisionCode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `PolicyMarker`
--

CREATE TABLE `PolicyMarker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `PolicySignificance`
--

CREATE TABLE `PolicySignificance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `Privilege`
--

CREATE TABLE `Privilege` (
  `privilege_id` int(10) NOT NULL AUTO_INCREMENT,
  `resource` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(10) NOT NULL,
  PRIMARY KEY (`privilege_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=142 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=490 ;

-- --------------------------------------------------------

--
-- Table structure for table `published`
--

CREATE TABLE `published` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publishing_org_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_count` int(11) DEFAULT NULL,
  `data_updated_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pushed_to_registry` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=406 ;

-- --------------------------------------------------------

--
-- Table structure for table `PublisherType`
--

CREATE TABLE `PublisherType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `Region`
--

CREATE TABLE `Region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `RegionVocabulary`
--

CREATE TABLE `RegionVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `registry_info`
--

CREATE TABLE `registry_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `publishing_type` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `update_registry` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=263 ;

-- --------------------------------------------------------

--
-- Table structure for table `registry_published_data`
--

CREATE TABLE `registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `response` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=333 ;

-- --------------------------------------------------------

--
-- Table structure for table `RelatedActivityType`
--

CREATE TABLE `RelatedActivityType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `reset`
--

CREATE TABLE `reset` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `reset_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Table structure for table `ResultType`
--

CREATE TABLE `ResultType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `role` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Sector`
--

CREATE TABLE `Sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=583 ;

-- --------------------------------------------------------

--
-- Table structure for table `SectorCategory`
--

CREATE TABLE `SectorCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `ParentCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `query` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=174 ;

-- --------------------------------------------------------

--
-- Table structure for table `TiedStatus`
--

CREATE TABLE `TiedStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `TransactionType`
--

CREATE TABLE `TransactionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8_unicode_ci,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(10) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(10) NOT NULL,
  `status` int(2) NOT NULL,
  `help_state` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=491 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activity_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `uploaded_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=138 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_register`
--

CREATE TABLE `user_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `VerificationStatus`
--

CREATE TABLE `VerificationStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Vocabulary`
--

CREATE TABLE `Vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
