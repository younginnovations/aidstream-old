-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 07, 2014 at 08:13 PM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `address` text NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `uniqid` varchar(35) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `file_name` varchar(255) DEFAULT NULL,
  `display_in_footer` tinyint(1) DEFAULT '1',
  `url` text,
  `simplified` int(11) NOT NULL DEFAULT '0',
  `disqus_comments` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=317 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityDateType`
--

CREATE TABLE IF NOT EXISTS `ActivityDateType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityScope`
--

CREATE TABLE IF NOT EXISTS `ActivityScope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(50) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityStatus`
--

CREATE TABLE IF NOT EXISTS `ActivityStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_hash`
--

CREATE TABLE IF NOT EXISTS `activity_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `hash` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1189 ;

-- --------------------------------------------------------

--
-- Table structure for table `AdministrativeAreaCode(First-level)`
--

CREATE TABLE IF NOT EXISTS `AdministrativeAreaCode(First-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) DEFAULT NULL,
  `Country` varchar(40) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AdministrativeAreaCode(Second-level)`
--

CREATE TABLE IF NOT EXISTS `AdministrativeAreaCode(Second-level)` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) DEFAULT NULL,
  `Country` varchar(40) DEFAULT NULL,
  `First-Level Administrative Area` varchar(50) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AidType`
--

CREATE TABLE IF NOT EXISTS `AidType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `CategoryCode` char(1) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `AidTypeCategory`
--

CREATE TABLE IF NOT EXISTS `AidTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetIdentifier`
--

CREATE TABLE IF NOT EXISTS `BudgetIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Sector` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetIdentifierVocabulary`
--

CREATE TABLE IF NOT EXISTS `BudgetIdentifierVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `BudgetType`
--

CREATE TABLE IF NOT EXISTS `BudgetType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `ChangeLog`
--

CREATE TABLE IF NOT EXISTS `ChangeLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `value` text NOT NULL,
  `updated_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `updated_datetime` (`updated_datetime`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CodeLists`
--

CREATE TABLE IF NOT EXISTS `CodeLists` (
  `codelist_id` int(11) NOT NULL AUTO_INCREMENT,
  `codelist` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `used_in` text NOT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`codelist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `CollaborationType`
--

CREATE TABLE IF NOT EXISTS `CollaborationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `ConditionType`
--

CREATE TABLE IF NOT EXISTS `ConditionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE IF NOT EXISTS `Contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=576 ;

-- --------------------------------------------------------

--
-- Table structure for table `Country`
--

CREATE TABLE IF NOT EXISTS `Country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=651 ;

-- --------------------------------------------------------

--
-- Table structure for table `Currency`
--

CREATE TABLE IF NOT EXISTS `Currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=499 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_field_groups`
--

CREATE TABLE IF NOT EXISTS `default_field_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=317 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_field_values`
--

CREATE TABLE IF NOT EXISTS `default_field_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=317 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_user_field`
--

CREATE TABLE IF NOT EXISTS `default_user_field` (
  `default_user_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`default_user_field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DescriptionType`
--

CREATE TABLE IF NOT EXISTS `DescriptionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `DisbursementChannel`
--

CREATE TABLE IF NOT EXISTS `DisbursementChannel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `displayed_subelements`
--

CREATE TABLE IF NOT EXISTS `displayed_subelements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elements` text NOT NULL,
  `org_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentCategory`
--

CREATE TABLE IF NOT EXISTS `DocumentCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(3) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CategoryCode` char(1) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentCategoryCategory`
--

CREATE TABLE IF NOT EXISTS `DocumentCategoryCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(1) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `FileFormat`
--

CREATE TABLE IF NOT EXISTS `FileFormat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `FinanceType`
--

CREATE TABLE IF NOT EXISTS `FinanceType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Table structure for table `FinanceTypeCategory`
--

CREATE TABLE IF NOT EXISTS `FinanceTypeCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `FlowType`
--

CREATE TABLE IF NOT EXISTS `FlowType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `GazetteerAgency`
--

CREATE TABLE IF NOT EXISTS `GazetteerAgency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `GeographicalPrecision`
--

CREATE TABLE IF NOT EXISTS `GeographicalPrecision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `Help`
--

CREATE TABLE IF NOT EXISTS `Help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=332 ;

-- --------------------------------------------------------

--
-- Table structure for table `Help-local`
--

CREATE TABLE IF NOT EXISTS `Help-local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activities`
--

CREATE TABLE IF NOT EXISTS `iati_activities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@generated_datetime` varchar(25) NOT NULL,
  `@version` varchar(10) NOT NULL,
  `unqid` varchar(256) NOT NULL,
  `user_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=316 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity`
--

CREATE TABLE IF NOT EXISTS `iati_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(5) NOT NULL,
  `@default_currency` varchar(5) NOT NULL,
  `@hierarchy` int(3) NOT NULL,
  `@last_updated_datetime` varchar(25) NOT NULL,
  `@linked_data_uri` varchar(255) DEFAULT NULL,
  `activities_id` int(10) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1201 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_date`
--

CREATE TABLE IF NOT EXISTS `iati_activity_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(10) DEFAULT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1762 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_scope`
--

CREATE TABLE IF NOT EXISTS `iati_activity_scope` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_status`
--

CREATE TABLE IF NOT EXISTS `iati_activity_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=991 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_activity_website`
--

CREATE TABLE IF NOT EXISTS `iati_activity_website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=233 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_aid_type`
--

CREATE TABLE IF NOT EXISTS `iati_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget`
--

CREATE TABLE IF NOT EXISTS `iati_budget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1256 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/period_end`
--

CREATE TABLE IF NOT EXISTS `iati_budget/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1256 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/period_start`
--

CREATE TABLE IF NOT EXISTS `iati_budget/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1256 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_budget/value`
--

CREATE TABLE IF NOT EXISTS `iati_budget/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(20) NOT NULL,
  `@currency` varchar(20) DEFAULT NULL,
  `text` text NOT NULL,
  `budget_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1256 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_capital_spend`
--

CREATE TABLE IF NOT EXISTS `iati_capital_spend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_collaboration_type`
--

CREATE TABLE IF NOT EXISTS `iati_collaboration_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=391 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_conditions`
--

CREATE TABLE IF NOT EXISTS `iati_conditions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@attached` varchar(3) NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_conditions/condition`
--

CREATE TABLE IF NOT EXISTS `iati_conditions/condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@type` int(11) DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `text` text,
  `conditions_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `activity_id` int(10) NOT NULL,
  `@type` text NOT NULL,
  `@xml_lang` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=574 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/email`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/email` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=534 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/job_title`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/job_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(25) NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/mailing_address`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/mailing_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=461 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/organisation`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/organisation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=542 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/person_name`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/person_name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=419 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/telephone`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/telephone` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=379 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_contact_info/website`
--

CREATE TABLE IF NOT EXISTS `iati_contact_info/website` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `contact_info_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items`
--

CREATE TABLE IF NOT EXISTS `iati_country_budget_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(25) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items/budget_item`
--

CREATE TABLE IF NOT EXISTS `iati_country_budget_items/budget_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(50) NOT NULL,
  `@percentage` varchar(25) NOT NULL,
  `country_budget_items_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_country_budget_items/budget_item/description`
--

CREATE TABLE IF NOT EXISTS `iati_country_budget_items/budget_item/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `budget_item_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_aid_type`
--

CREATE TABLE IF NOT EXISTS `iati_default_aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=773 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_finance_type`
--

CREATE TABLE IF NOT EXISTS `iati_default_finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=382 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_flow_type`
--

CREATE TABLE IF NOT EXISTS `iati_default_flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=736 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_default_tied_status`
--

CREATE TABLE IF NOT EXISTS `iati_default_tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(10) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=369 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_description`
--

CREATE TABLE IF NOT EXISTS `iati_description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1101 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link`
--

CREATE TABLE IF NOT EXISTS `iati_document_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@url` text NOT NULL,
  `@format` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=881 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/category`
--

CREATE TABLE IF NOT EXISTS `iati_document_link/category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=948 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/language`
--

CREATE TABLE IF NOT EXISTS `iati_document_link/language` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=576 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_document_link/title`
--

CREATE TABLE IF NOT EXISTS `iati_document_link/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text,
  `document_link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=952 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_gazetteer_entry`
--

CREATE TABLE IF NOT EXISTS `iati_gazetteer_entry` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_identifier`
--

CREATE TABLE IF NOT EXISTS `iati_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `activity_identifier` varchar(255) NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1201 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/actual`
--

CREATE TABLE IF NOT EXISTS `iati_indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/baseline`
--

CREATE TABLE IF NOT EXISTS `iati_indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/description`
--

CREATE TABLE IF NOT EXISTS `iati_indicator/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/target`
--

CREATE TABLE IF NOT EXISTS `iati_indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(25) NOT NULL,
  `@value` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_indicator/title`
--

CREATE TABLE IF NOT EXISTS `iati_indicator/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_legacy_data`
--

CREATE TABLE IF NOT EXISTS `iati_legacy_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@name` varchar(255) NOT NULL,
  `@value` varchar(255) NOT NULL,
  `@iati_equivalent` text,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location`
--

CREATE TABLE IF NOT EXISTS `iati_location` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@percentage` varchar(25) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/administrative`
--

CREATE TABLE IF NOT EXISTS `iati_location/administrative` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@country` varchar(25) DEFAULT NULL,
  `@adm1` varchar(25) DEFAULT NULL,
  `@adm2` varchar(25) DEFAULT NULL,
  `text` text,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/coordinates`
--

CREATE TABLE IF NOT EXISTS `iati_location/coordinates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@latitude` varchar(25) NOT NULL,
  `@longitude` varchar(25) NOT NULL,
  `@precision` varchar(25) DEFAULT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/description`
--

CREATE TABLE IF NOT EXISTS `iati_location/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/gazetteer_entry`
--

CREATE TABLE IF NOT EXISTS `iati_location/gazetteer_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@gazetteer_ref` int(11) NOT NULL,
  `text` varchar(50) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/location_type`
--

CREATE TABLE IF NOT EXISTS `iati_location/location_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_location/name`
--

CREATE TABLE IF NOT EXISTS `iati_location/name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=159 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation`
--

CREATE TABLE IF NOT EXISTS `iati_organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `@last_updated_datetime` varchar(25) NOT NULL,
  `@default_currency` varchar(25) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/document_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@url` text,
  `@format` varchar(25) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/category`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/document_link/category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/language`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/document_link/language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(25) DEFAULT NULL,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/document_link/title`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/document_link/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `document_link_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/identifier`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/name`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_country_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_end`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_country_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/period_start`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_country_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/recipient_country`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_country_budget/recipient_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_country_budget/value`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_country_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_country_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_org_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_end`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_org_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/period_start`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_org_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/recipient_org`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_org_budget/recipient_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/recipient_org_budget/value`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/recipient_org_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `recipient_org_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/reporting_org`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) NOT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `organisation_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/total_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/period_end`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/total_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/period_start`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/total_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_organisation/total_budget/value`
--

CREATE TABLE IF NOT EXISTS `iati_organisation/total_budget/value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `total_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_other_identifier`
--

CREATE TABLE IF NOT EXISTS `iati_other_identifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@owner_ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@owner_name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_participating_org`
--

CREATE TABLE IF NOT EXISTS `iati_participating_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@role` varchar(25) NOT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text CHARACTER SET utf8,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2779 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement`
--

CREATE TABLE IF NOT EXISTS `iati_planned_disbursement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@updated` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/period_end`
--

CREATE TABLE IF NOT EXISTS `iati_planned_disbursement/period_end` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/period_start`
--

CREATE TABLE IF NOT EXISTS `iati_planned_disbursement/period_start` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_planned_disbursement/value`
--

CREATE TABLE IF NOT EXISTS `iati_planned_disbursement/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value_date` varchar(25) NOT NULL,
  `@currency` varchar(25) DEFAULT NULL,
  `text` text NOT NULL,
  `planned_disbursement_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_policy_marker`
--

CREATE TABLE IF NOT EXISTS `iati_policy_marker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@significance` varchar(10) DEFAULT NULL,
  `@vocabulary` varchar(25) DEFAULT NULL,
  `@code` varchar(10) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_recipient_country`
--

CREATE TABLE IF NOT EXISTS `iati_recipient_country` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@percentage` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1093 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_recipient_region`
--

CREATE TABLE IF NOT EXISTS `iati_recipient_region` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@vocabulary` varchar(25) NOT NULL,
  `@percentage` varchar(25) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=454 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_related_activity`
--

CREATE TABLE IF NOT EXISTS `iati_related_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) NOT NULL,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_reporting_org`
--

CREATE TABLE IF NOT EXISTS `iati_reporting_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `@type` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) NOT NULL,
  `text` text NOT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1201 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result`
--

CREATE TABLE IF NOT EXISTS `iati_result` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@type` varchar(25) NOT NULL,
  `@aggregation_status` varchar(5) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/description`
--

CREATE TABLE IF NOT EXISTS `iati_result/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `text` text NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@measure` int(11) NOT NULL,
  `@ascending` varchar(5) NOT NULL DEFAULT 'true',
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/actual`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/baseline`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/baseline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/baseline/comment`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/baseline/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `baseline_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/description`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/actual`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/actual` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/actual/comment`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/actual/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `actual_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/period-end`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/period-end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/period-start`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/period-start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) DEFAULT NULL,
  `text` text,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/target`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@value` varchar(20) NOT NULL,
  `period_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/period/target/comment`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/period/target/comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `target_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/target`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/target` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@year` varchar(20) NOT NULL,
  `@value` varchar(20) NOT NULL,
  `text` text,
  `indicator_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/indicator/title`
--

CREATE TABLE IF NOT EXISTS `iati_result/indicator/title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `@xml_lang` int(11) DEFAULT NULL,
  `indicator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_result/title`
--

CREATE TABLE IF NOT EXISTS `iati_result/title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text NOT NULL,
  `result_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_sector`
--

CREATE TABLE IF NOT EXISTS `iati_sector` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@vocabulary` varchar(255) DEFAULT NULL,
  `@code` varchar(255) DEFAULT NULL,
  `@percentage` varchar(255) DEFAULT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1385 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_title`
--

CREATE TABLE IF NOT EXISTS `iati_title` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1103 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction`
--

CREATE TABLE IF NOT EXISTS `iati_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(25) DEFAULT NULL,
  `activity_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6183 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/aid_type`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/aid_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2580 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/description`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3405 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/disbursement_channel`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/disbursement_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `text` text,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1726 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/distribution_channel`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/distribution_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `text` text NOT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/finance_type`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(20) NOT NULL,
  `text` text,
  `@xml_lang` varchar(20) DEFAULT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1904 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/flow_type`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/flow_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2688 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/provider_org`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/provider_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `@provider_activity_id` text,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4426 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/receiver_org`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/receiver_org` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@ref` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `@receiver_activity_id` text,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4692 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/tied_status`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/tied_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) NOT NULL,
  `@xml_lang` varchar(3) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1651 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/transaction_date`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/transaction_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@iso_date` varchar(25) NOT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5872 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/transaction_type`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/transaction_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@code` varchar(25) DEFAULT NULL,
  `@type` varchar(25) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6214 ;

-- --------------------------------------------------------

--
-- Table structure for table `iati_transaction/value`
--

CREATE TABLE IF NOT EXISTS `iati_transaction/value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `@currency` varchar(25) DEFAULT NULL,
  `@value_date` varchar(25) DEFAULT NULL,
  `text` text,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6215 ;

-- --------------------------------------------------------

--
-- Table structure for table `IndicatorMeasure`
--

CREATE TABLE IF NOT EXISTS `IndicatorMeasure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Lang`
--

CREATE TABLE IF NOT EXISTS `Lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Lang` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Language`
--

CREATE TABLE IF NOT EXISTS `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(2) DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=544 ;

-- --------------------------------------------------------

--
-- Table structure for table `LocationType`
--

CREATE TABLE IF NOT EXISTS `LocationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget`
--

CREATE TABLE IF NOT EXISTS `organisation/annual_planning_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_end`
--

CREATE TABLE IF NOT EXISTS `organisation/annual_planning_budget/period_end` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_start`
--

CREATE TABLE IF NOT EXISTS `organisation/annual_planning_budget/period_start` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `annual_planning_budget_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/annual_planning_budget/period_start/test`
--

CREATE TABLE IF NOT EXISTS `organisation/annual_planning_budget/period_start/test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `period_start_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation/name`
--

CREATE TABLE IF NOT EXISTS `organisation/name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `xml_lang` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifier`
--

CREATE TABLE IF NOT EXISTS `OrganisationIdentifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierBilateral`
--

CREATE TABLE IF NOT EXISTS `OrganisationIdentifierBilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierIngo`
--

CREATE TABLE IF NOT EXISTS `OrganisationIdentifierIngo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationIdentifierMultilateral`
--

CREATE TABLE IF NOT EXISTS `OrganisationIdentifierMultilateral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `Abbreviation` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationRole`
--

CREATE TABLE IF NOT EXISTS `OrganisationRole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `OrganisationType`
--

CREATE TABLE IF NOT EXISTS `OrganisationType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_hash`
--

CREATE TABLE IF NOT EXISTS `organisation_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_id` int(11) NOT NULL,
  `hash` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_published`
--

CREATE TABLE IF NOT EXISTS `organisation_published` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publishing_org_id` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 NOT NULL,
  `data_updated_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pushed_to_registry` int(11) NOT NULL DEFAULT '0',
  `organisation_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_registry_published_data`
--

CREATE TABLE IF NOT EXISTS `organisation_registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=257 ;

-- --------------------------------------------------------

--
-- Table structure for table `PercisionCode`
--

CREATE TABLE IF NOT EXISTS `PercisionCode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(11) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Description` text NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `PolicyMarker`
--

CREATE TABLE IF NOT EXISTS `PolicyMarker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `PolicySignificance`
--

CREATE TABLE IF NOT EXISTS `PolicySignificance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `Privilege`
--

CREATE TABLE IF NOT EXISTS `Privilege` (
  `privilege_id` int(10) NOT NULL AUTO_INCREMENT,
  `resource` text NOT NULL,
  `owner_id` int(10) NOT NULL,
  PRIMARY KEY (`privilege_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `middle_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=455 ;

-- --------------------------------------------------------

--
-- Table structure for table `published`
--

CREATE TABLE IF NOT EXISTS `published` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=393 ;

-- --------------------------------------------------------

--
-- Table structure for table `PublisherType`
--

CREATE TABLE IF NOT EXISTS `PublisherType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `Region`
--

CREATE TABLE IF NOT EXISTS `Region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `RegionVocabulary`
--

CREATE TABLE IF NOT EXISTS `RegionVocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(25) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `registry_info`
--

CREATE TABLE IF NOT EXISTS `registry_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_id` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `publishing_type` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `update_registry` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=249 ;

-- --------------------------------------------------------

--
-- Table structure for table `registry_published_data`
--

CREATE TABLE IF NOT EXISTS `registry_published_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_org_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=329 ;

-- --------------------------------------------------------

--
-- Table structure for table `RelatedActivityType`
--

CREATE TABLE IF NOT EXISTS `RelatedActivityType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `reset`
--

CREATE TABLE IF NOT EXISTS `reset` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `value` varchar(50) NOT NULL,
  `reset_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `ResultType`
--

CREATE TABLE IF NOT EXISTS `ResultType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `role` varchar(15) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Sector`
--

CREATE TABLE IF NOT EXISTS `Sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `CategoryCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=583 ;

-- --------------------------------------------------------

--
-- Table structure for table `SectorCategory`
--

CREATE TABLE IF NOT EXISTS `SectorCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `ParentCode` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Table structure for table `TiedStatus`
--

CREATE TABLE IF NOT EXISTS `TiedStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `TransactionType`
--

CREATE TABLE IF NOT EXISTS `TransactionType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` text,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `role_id` int(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `account_id` int(10) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=456 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE IF NOT EXISTS `user_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `uploaded_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_register`
--

CREATE TABLE IF NOT EXISTS `user_register` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `VerificationStatus`
--

CREATE TABLE IF NOT EXISTS `VerificationStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Vocabulary`
--

CREATE TABLE IF NOT EXISTS `Vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
