-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2014 at 10:30 PM
-- Server version: 5.5.40
-- PHP Version: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tream`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(200) NOT NULL,
  `person_id` int(11) DEFAULT '0',
  `currency_id` int(11) DEFAULT '0',
  `bank_id` int(11) DEFAULT '0',
  `account_type_id` int(11) DEFAULT NULL,
  `account_mandat_id` int(11) DEFAULT '0',
  `start_mandat` date DEFAULT NULL,
  `start_fee` date DEFAULT NULL,
  `fee_tp` double DEFAULT NULL,
  `fee_finder` double DEFAULT NULL,
  `fee_bank` double DEFAULT NULL,
  `fee_performance` double DEFAULT NULL,
  `end_finders` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `discount_bank` double DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  KEY `person_id` (`person_id`),
  KEY `currency_id` (`currency_id`),
  KEY `bank_id` (`bank_id`),
  KEY `account_type_id` (`account_type_id`),
  KEY `account_mandat_id` (`account_mandat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_mandates`
--

CREATE TABLE IF NOT EXISTS `account_mandates` (
  `account_mandat_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`account_mandat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_persons`
--

CREATE TABLE IF NOT EXISTS `account_persons` (
  `account_person_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `account_person_type_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`account_person_id`),
  KEY `account_id` (`account_id`),
  KEY `person_id` (`person_id`),
  KEY `account_person_type_id` (`account_person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_person_types`
--

CREATE TABLE IF NOT EXISTS `account_person_types` (
  `account_person_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`account_person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE IF NOT EXISTS `account_types` (
  `account_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`account_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `person_id` int(11) DEFAULT NULL,
  `comment` text,
  `deadline` datetime DEFAULT '0000-00-00 00:00:00',
  `create_contact_id` int(11) DEFAULT NULL,
  `description` text,
  `action_status_id` int(11) DEFAULT NULL,
  `security_id` int(11) NOT NULL,
  `details` mediumtext,
  PRIMARY KEY (`action_id`),
  KEY `action_type_id` (`action_type_id`),
  KEY `person_id` (`person_id`),
  KEY `create_contact_id` (`create_contact_id`),
  KEY `action_status_id` (`action_status_id`),
  KEY `security_id` (`security_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `action_stati`
--

CREATE TABLE IF NOT EXISTS `action_stati` (
  `action_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  PRIMARY KEY (`action_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `action_types`
--

CREATE TABLE IF NOT EXISTS `action_types` (
  `action_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`action_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(300) DEFAULT NULL,
  `city_code` varchar(100) DEFAULT NULL,
  `city` varchar(300) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `address_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  KEY `address_type_id` (`address_type_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `address_links`
--

CREATE TABLE IF NOT EXISTS `address_links` (
  `address_link_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `firm_id` int(11) DEFAULT NULL,
  `address_link_type_id` int(11) NOT NULL,
  PRIMARY KEY (`address_link_id`),
  KEY `address_id` (`address_id`),
  KEY `person_id` (`person_id`),
  KEY `firm_id` (`firm_id`),
  KEY `address_link_type_id` (`address_link_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `address_link_types`
--

CREATE TABLE IF NOT EXISTS `address_link_types` (
  `address_link_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) NOT NULL,
  PRIMARY KEY (`address_link_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(200) NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bill_periods`
--

CREATE TABLE IF NOT EXISTS `bill_periods` (
  `bill_period_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  PRIMARY KEY (`bill_period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contact_type_id` int(11) DEFAULT NULL,
  `description` text,
  `contact_category_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `main_action` text,
  `details` mediumtext,
  `action_status_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `contact_type_id` (`contact_type_id`),
  KEY `contact_category_id` (`contact_category_id`),
  KEY `account_id` (`account_id`),
  KEY `portfolio_id` (`portfolio_id`),
  KEY `person_id` (`person_id`),
  KEY `action_status_id` (`action_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_categories`
--

CREATE TABLE IF NOT EXISTS `contact_categories` (
  `contact_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(300) NOT NULL,
  PRIMARY KEY (`contact_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_members`
--

CREATE TABLE IF NOT EXISTS `contact_members` (
  `contact_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `contact_member_type_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `comment` text,
  PRIMARY KEY (`contact_member_id`),
  KEY `person_id` (`person_id`),
  KEY `contact_member_type_id` (`contact_member_type_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_member_types`
--

CREATE TABLE IF NOT EXISTS `contact_member_types` (
  `contact_member_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_type` varchar(200) NOT NULL,
  PRIMARY KEY (`contact_member_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_notes`
--

CREATE TABLE IF NOT EXISTS `contact_notes` (
  `contact_note_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`contact_note_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_numbers`
--

CREATE TABLE IF NOT EXISTS `contact_numbers` (
  `contact_number_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_number` varchar(100) NOT NULL,
  `contact_number_type_id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`contact_number_id`),
  KEY `contact_number_type_id` (`contact_number_type_id`),
  KEY `person_id` (`person_id`),
  KEY `address_id` (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_number_types`
--

CREATE TABLE IF NOT EXISTS `contact_number_types` (
  `contact_number_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) NOT NULL,
  PRIMARY KEY (`contact_number_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_topics`
--

CREATE TABLE IF NOT EXISTS `contact_topics` (
  `contact_topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_type_id` int(11) NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`contact_topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE IF NOT EXISTS `contact_types` (
  `contact_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`contact_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE IF NOT EXISTS `contract` (
  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(300) NOT NULL,
  `contract_type_id` int(11) NOT NULL,
  PRIMARY KEY (`contract_id`),
  KEY `contract_type_id` (`contract_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contract_types`
--

CREATE TABLE IF NOT EXISTS `contract_types` (
  `contract_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`contract_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(20) NOT NULL,
  `decimals` int(11) DEFAULT '2' COMMENT 'dicimals used when displaying a value in this currency',
  `decimals_trading` int(11) DEFAULT '4' COMMENT 'number of decimals normally used for fx trading (4 for USD)',
  `comment` text,
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currency_pairs`
--

CREATE TABLE IF NOT EXISTS `currency_pairs` (
  `currency_pair_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency1_id` int(11) DEFAULT NULL,
  `currency2_id` int(11) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `fx_rate` double DEFAULT NULL COMMENT 'field with the last fx rate for fast calculation',
  `decimals` int(11) DEFAULT NULL,
  `symbol_market_map` varchar(200) DEFAULT NULL,
  `symbol_yahoo` varchar(200) DEFAULT NULL,
  `hist_volatility` double DEFAULT NULL,
  `implied_volatility` double DEFAULT NULL,
  `expected_volatility` double DEFAULT NULL,
  `hist_return` double DEFAULT NULL,
  `market_return` double DEFAULT NULL,
  `expected_return` double DEFAULT NULL,
  `comment` text,
  `price_feed_type_id` int(11) DEFAULT '0',
  PRIMARY KEY (`currency_pair_id`),
  KEY `currency1_id` (`currency1_id`),
  KEY `currency2_id` (`currency2_id`),
  KEY `price_feed_type_id` (`price_feed_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type_id` int(11) DEFAULT NULL,
  `internal_person_id` int(11) DEFAULT NULL,
  `scanned_document` text,
  `date_to_account` datetime DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `date_bank_sign` datetime DEFAULT NULL,
  `date_account_sign` datetime DEFAULT NULL,
  `date_received` datetime DEFAULT NULL,
  `date_internal_sign` datetime DEFAULT NULL,
  `archive` varchar(200) DEFAULT NULL,
  `keywords` text,
  `comment` text,
  PRIMARY KEY (`document_id`),
  KEY `document_type_id` (`document_type_id`),
  KEY `tp_person_id` (`internal_person_id`),
  KEY `account_id` (`account_id`),
  KEY `bank_id` (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE IF NOT EXISTS `document_categories` (
  `document_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) NOT NULL,
  PRIMARY KEY (`document_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE IF NOT EXISTS `document_types` (
  `document_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `document_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`document_type_id`),
  KEY `document_category_id` (`document_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(11) DEFAULT NULL,
  `description` text,
  `account_id` int(11) DEFAULT NULL COMMENT 'used if related to an account',
  `portfolio_id` int(11) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `description_unique` varchar(200) DEFAULT NULL COMMENT 'an shaort description to avoid "double" entries',
  `solution1_sql` text COMMENT 'a sql commant for the  a suggested solution',
  `event_status_id` int(11) DEFAULT NULL,
  `event_date` timestamp NULL DEFAULT NULL,
  `persons_informed` text COMMENT 'ids of the persons that have been informed about the creation of this event',
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `closed` timestamp NULL DEFAULT NULL,
  `solution1_description` text,
  `solution2_sql` text,
  `solution2_description` text,
  `solution_selected` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `description_unique` (`description_unique`),
  KEY `event_type_id` (`event_type_id`),
  KEY `account_id` (`account_id`),
  KEY `portfolio_id` (`portfolio_id`),
  KEY `security_id` (`security_id`),
  KEY `event_status_id` (`event_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_stati`
--

CREATE TABLE IF NOT EXISTS `event_stati` (
  `event_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  `code_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`event_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `event_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `user_type_id` int(11) DEFAULT NULL COMMENT 'only show the event to users of this type',
  `comment` text,
  `code_id` varchar(50) DEFAULT NULL,
  `push_message` tinyint(1) DEFAULT NULL COMMENT 'send out a message like an email if a event of this type is created; on the person can be defined how the person wants to be informed',
  PRIMARY KEY (`event_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_exceptions`
--

CREATE TABLE IF NOT EXISTS `exposure_exceptions` (
  `exposure_exception_id` int(11) NOT NULL AUTO_INCREMENT,
  `portfolio_id` int(11) NOT NULL,
  `exposure_item_id` int(11) NOT NULL,
  `target` float DEFAULT NULL,
  `limit_up` float DEFAULT NULL,
  `limit_down` float DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`exposure_exception_id`),
  KEY `portfolio_id` (`portfolio_id`),
  KEY `exposure_item_id` (`exposure_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_items`
--

CREATE TABLE IF NOT EXISTS `exposure_items` (
  `exposure_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `exposure_type_id` int(11) DEFAULT NULL,
  `order_nbr` int(11) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `currency_id` int(11) DEFAULT NULL COMMENT 'used only for currencies to link to the cash position',
  `is_part_of` int(11) DEFAULT NULL COMMENT 'self reference to create a tree',
  `part_weight` double DEFAULT NULL COMMENT 'weight used for aggregation the single exposures',
  `security_type_id` int(11) DEFAULT NULL COMMENT 'only used for Asset class exposure types to set the default expore item',
  `comment` text,
  PRIMARY KEY (`exposure_item_id`),
  KEY `exposure_type_id` (`exposure_type_id`),
  KEY `currency_id` (`currency_id`),
  KEY `security_type_id` (`security_type_id`),
  KEY `is_part_of` (`is_part_of`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_item_values`
--

DROP TABLE IF EXISTS `exposure_item_values`;
CREATE TABLE IF NOT EXISTS `exposure_item_values` (
  `exposure_item_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `exposure_item_id` int(11) NOT NULL,
  `ref_currency_id` int(11) NOT NULL,
  `ref_security_id` int(11) DEFAULT NULL,
  `hist_volatility` double DEFAULT NULL COMMENT 'automatically calculated based on market prices',
  `implied_volatility` double DEFAULT NULL COMMENT 'automatically calculated based on option prices',
  `expected_volatility` double DEFAULT NULL COMMENT 'can be set manually to overwrite the implied volatity',
  `hist_return` double DEFAULT NULL,
  `market_return` double DEFAULT NULL COMMENT 'if a market return exists; e.g. for bond markets the swap curve',
  `expected_return` double DEFAULT NULL COMMENT 'can be set to overwrite the market return', 
  `comment` text,
  PRIMARY KEY (`exposure_item_value_id`),
  KEY `exposure_item_id` (`exposure_item_id`),
  KEY `ref_currency_id` (`ref_currency_id`),
  KEY `ref_security_id` (`ref_security_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_item_value_correlations`
--

DROP TABLE IF EXISTS `exposure_item_value_correlations`;
CREATE TABLE IF NOT EXISTS `exposure_item_value_correlations` (
  `exposure_item_value_correlation_id` int(11) NOT NULL,
  `exposure_item_value1_id` int(11) NOT NULL,
  `exposure_item_value2_id` int(11) NOT NULL,
  `correlation` double DEFAULT NULL,
  PRIMARY KEY (`exposure_item_value_correlation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_targets`
--

CREATE TABLE IF NOT EXISTS `exposure_targets` (
  `exposure_target_id` int(11) NOT NULL AUTO_INCREMENT,
  `exposure_item_id` int(11) DEFAULT NULL,
  `target` float DEFAULT NULL,
  `limit_up` float DEFAULT NULL,
  `limit_down` float DEFAULT NULL,
  `account_mandat_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `comment` text,
  `optimized` double DEFAULT NULL COMMENT 'updated by the optimizer',
  PRIMARY KEY (`exposure_target_id`),
  KEY `exposure_item_id` (`exposure_item_id`),
  KEY `account_mandat_id` (`account_mandat_id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_target_values`
--

DROP TABLE IF EXISTS `exposure_target_values`;
CREATE TABLE IF NOT EXISTS `exposure_target_values` (
  `exposure_target_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `exposure_target_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `calc_value` double DEFAULT NULL,
  PRIMARY KEY (`exposure_target_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exposure_types`
--

CREATE TABLE IF NOT EXISTS `exposure_types` (
  `exposure_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `description` text,
  `comment` text,
  PRIMARY KEY (`exposure_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_data`
--

CREATE TABLE IF NOT EXISTS `log_data` (
  `table_id` int(11) NOT NULL,
  `table_name` varchar(200) NOT NULL,
  `row_id` int(11) NOT NULL,
  `field_name` varchar(200) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `old_value` text,
  `new_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_tables`
--

CREATE TABLE IF NOT EXISTS `log_tables` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_users`
--

CREATE TABLE IF NOT EXISTS `log_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `code_id` varchar(50) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_user_assigns`
--

CREATE TABLE IF NOT EXISTS `log_user_assigns` (
  `user_assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`user_assign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_user_groups`
--

CREATE TABLE IF NOT EXISTS `log_user_groups` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(300) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_user_rights`
--

CREATE TABLE IF NOT EXISTS `log_user_rights` (
  `user_right_id` int(11) NOT NULL AUTO_INCREMENT,
  `right_name` varchar(200) DEFAULT NULL,
  `comment` text,
  `code_id` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`user_right_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_user_types`
--

CREATE TABLE IF NOT EXISTS `log_user_types` (
  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  `code_id` varchar(100) DEFAULT NULL COMMENT 'link to php code',
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  `body` text,
  `message_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `message_type_id` (`message_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_types`
--

DROP TABLE IF EXISTS `message_types`;
CREATE TABLE IF NOT EXISTS `message_types` (
  `message_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`message_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

CREATE TABLE IF NOT EXISTS `parameters` (
  `parameter_id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter_type_id` int(11) NOT NULL,
  `date_parameter` date NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`parameter_id`),
  KEY `parameter_type_id` (`parameter_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `parameter_types`
--

CREATE TABLE IF NOT EXISTS `parameter_types` (
  `parameter_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`parameter_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE IF NOT EXISTS `persons` (
  `person_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `lastname` varchar(300) DEFAULT NULL,
  `firstname` varchar(300) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `person_type_id` int(11) DEFAULT NULL,
  `display_name` varchar(400) DEFAULT NULL,
  `comment` text,
  `code_id` varchar(50) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `person_type_id` (`person_type_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `person_types`
--

CREATE TABLE IF NOT EXISTS `person_types` (
  `person_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `internal` tinyint(1) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE IF NOT EXISTS `portfolios` (
  `portfolio_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `portfolio_number` int(11) DEFAULT NULL,
  `portfolio_name` varchar(200) DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_portfolio_id` varchar(200) DEFAULT NULL COMMENT 'the depot ID at the bank',
  `inactive` tinyint(1) DEFAULT NULL,
  `monitoring` tinyint(1) DEFAULT NULL,
  `confirm_to_bank` tinyint(1) DEFAULT NULL,
  `confirm_to_client` tinyint(1) DEFAULT NULL,
  `monitoring_security_limit` double DEFAULT NULL COMMENT 'if one security of this portfolio moves more than x percent a message is send to the portfolio manager',
  `portfolio_type_id` int(11) DEFAULT NULL,
  `is_part_of` int(11) DEFAULT NULL,
  `IBAN` varchar(100) DEFAULT NULL,
  `domicile` int(11) DEFAULT NULL,
  `nationality` int(11) DEFAULT NULL,
  PRIMARY KEY (`portfolio_id`),
  KEY `account_id` (`account_id`),
  KEY `currency_id` (`currency_id`),
  KEY `bank_id` (`bank_id`),
  KEY `portfolio_type_id` (`portfolio_type_id`),
  KEY `is_part_of` (`is_part_of`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_rights`
--

DROP TABLE IF EXISTS `portfolio_rights`;
CREATE TABLE IF NOT EXISTS `portfolio_rights` (
  `portfolio_right_id` int(11) NOT NULL AUTO_INCREMENT,
  `portfolio_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `user_right_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`portfolio_right_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_security_fixings`
--

DROP TABLE IF EXISTS `portfolio_security_fixings`;
CREATE TABLE IF NOT EXISTS `portfolio_security_fixings` (
  `portfolio_security_fixing_id` int(11) NOT NULL AUTO_INCREMENT,
  `portfolio_id` int(11) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `fixed_price` double DEFAULT NULL,
  `fixing_date` timestamp NULL DEFAULT NULL COMMENT 'maybe used for not sending a warning too often or not too less',
  `limit_overwrite` double DEFAULT NULL COMMENT 'overwrites the portfolio limit setting for this security',
  `comment` text,
  PRIMARY KEY (`portfolio_security_fixing_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `portfolio_security_fixings`
--

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_types`
--

DROP TABLE IF EXISTS `portfolio_types`;
CREATE TABLE IF NOT EXISTS `portfolio_types` (
  `portfolio_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`portfolio_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `portfolio_types`
--

-- --------------------------------------------------------

--
-- Table structure for table `recon_files`
--

CREATE TABLE IF NOT EXISTS `recon_files` (
  `recon_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(200) DEFAULT NULL,
  `file_path` varchar(400) DEFAULT NULL,
  `max_messages` int(11) DEFAULT NULL,
  `comment` text,
  `recon_file_type_id` int(11) DEFAULT NULL,
  `back_days` int(11) DEFAULT NULL COMMENT 'number of lookback days; used mainly for daily created files',
  `fixed_field_positions` text,
  `fixed_field_names` text,
  `last_run` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`recon_file_id`),
  KEY `recon_file_type_id` (`recon_file_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recon_file_entries`
--

CREATE TABLE IF NOT EXISTS `recon_file_entries` (
  `recon_file_entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_path` varchar(400) DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `open_events` int(11) DEFAULT NULL,
  PRIMARY KEY (`recon_file_entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recon_file_types`
--

CREATE TABLE IF NOT EXISTS `recon_file_types` (
  `recon_file_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  `code_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`recon_file_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recon_steps`
--

CREATE TABLE IF NOT EXISTS `recon_steps` (
  `recon_step_id` int(11) NOT NULL AUTO_INCREMENT,
  `recon_file_id` int(11) DEFAULT NULL,
  `order_nbr` int(11) DEFAULT NULL,
  `filter` varchar(200) DEFAULT NULL,
  `recon_step_type_id` int(11) DEFAULT NULL,
  `source_field_name` varchar(200) DEFAULT NULL,
  `source_id_field` varchar(200) DEFAULT NULL,
  `dest_table` varchar(200) DEFAULT NULL,
  `dest_field` varchar(200) DEFAULT NULL,
  `dest_id_field` varchar(200) DEFAULT NULL,
  `comment` text,
  `recon_value_type_id` int(11) DEFAULT NULL,
  `event_description_unique` varchar(200) DEFAULT NULL,
  `event_description` text,
  `solution1_text` text,
  `solution1_sql` text,
  `solution2_text` text,
  `solution2_sql` text,
  `stop_line_on_err` tinyint(1) DEFAULT NULL COMMENT 'stop the reconciliation for this line in case the step has a error',
  `err_step_for_step` int(11) DEFAULT NULL COMMENT 'run this step only if the related step was not succesful; e.g. select the trade by size and price only if not unique matching was possible',
  PRIMARY KEY (`recon_step_id`),
  KEY `order_nbr` (`order_nbr`),
  KEY `recon_file_id` (`recon_file_id`),
  KEY `recon_step_type_id` (`recon_step_type_id`),
  KEY `recon_value_type_id` (`recon_value_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recon_step_types`
--

CREATE TABLE IF NOT EXISTS `recon_step_types` (
  `recon_step_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`recon_step_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recon_value_types`
--

CREATE TABLE IF NOT EXISTS `recon_value_types` (
  `recon_value_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`recon_value_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Linked to program code' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `risk_profiles`
--

CREATE TABLE IF NOT EXISTS `risk_profiles` (
  `risk_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(200) NOT NULL,
  PRIMARY KEY (`risk_profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `securities`
--

CREATE TABLE IF NOT EXISTS `securities` (
  `security_id` int(11) NOT NULL AUTO_INCREMENT,
  `security_issuer_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `ISIN` varchar(20) DEFAULT NULL,
  `last_price` float DEFAULT NULL,
  `bid` float DEFAULT NULL,
  `ask` float DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `hist_update_time` datetime DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `security_type_id` int(11) DEFAULT NULL,
  `currency_pair_id` int(11) DEFAULT NULL,
  `bsi_id` int(11) DEFAULT NULL,
  `symbol_bloomberg` varchar(200) DEFAULT NULL,
  `symbol_reuters` varchar(200) DEFAULT NULL,
  `symbol_market_map` varchar(200) DEFAULT NULL,
  `symbol_yahoo` varchar(200) DEFAULT NULL,
  `price_feed_type_id` int(11) DEFAULT NULL,
  `valor` varchar(15) DEFAULT NULL COMMENT 'the swiss valor',
  `security_quote_type_id` int(11) DEFAULT NULL,
  `security_exposure_status_id` int(11) DEFAULT NULL,
  `monitoring_security_limit` double DEFAULT NULL,
  `archiv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`security_id`),
  KEY `currency_id` (`currency_id`),
  KEY `security_type_id` (`security_type_id`),
  KEY `currency_pair_id` (`currency_pair_id`),
  KEY `price_feed_type_id` (`price_feed_type_id`),
  KEY `security_quote_type_id` (`security_quote_type_id`),
  KEY `security_exposure_status_id` (`security_exposure_status_id`),
  KEY `security_issuer_id` (`security_issuer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_amount_types`
--

DROP TABLE IF EXISTS `security_amount_types`;
CREATE TABLE IF NOT EXISTS `security_amount_types` (
  `security_amount_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_amount_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_daily_prices`
--

DROP TABLE IF EXISTS `security_daily_prices`;
CREATE TABLE IF NOT EXISTS `security_daily_prices` (
  `security_id` int(11) NOT NULL,
  `quote_date` date NOT NULL,
  `open` double DEFAULT NULL,
  `high` double DEFAULT NULL,
  `low` double DEFAULT NULL,
  `close` double DEFAULT NULL,
  UNIQUE KEY `security_id` (`security_id`,`quote_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `security_exposures`
--

CREATE TABLE IF NOT EXISTS `security_exposures` (
  `security_exposure_id` int(11) NOT NULL AUTO_INCREMENT,
  `security_id` int(11) NOT NULL,
  `exposure_item_id` int(11) NOT NULL,
  `exposure_in_pct` float NOT NULL,
  `comment` text,
  `security_exposure_status_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`security_exposure_id`),
  KEY `security_exposure_status_id` (`security_exposure_status_id`),
  KEY `security_exposure_status_id_2` (`security_exposure_status_id`),
  KEY `security_id` (`security_id`),
  KEY `exposure_item_id` (`exposure_item_id`),
  KEY `security_exposure_status_id_3` (`security_exposure_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_exposure_stati`
--

CREATE TABLE IF NOT EXISTS `security_exposure_stati` (
  `security_exposure_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  PRIMARY KEY (`security_exposure_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_fields`
--

CREATE TABLE IF NOT EXISTS `security_fields` (
  `security_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `security_field_source_type_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_field_source_types`
--

DROP TABLE IF EXISTS `security_field_source_types`;
CREATE TABLE IF NOT EXISTS `security_field_source_types` (
  `security_field_source_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_field_source_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_field_values`
--

CREATE TABLE IF NOT EXISTS `security_field_values` (
  `security_field_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `security_id` int(11) NOT NULL,
  `security_field_id` int(11) NOT NULL,
  `sec_value` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_field_value_id`),
  KEY `security_id` (`security_id`),
  KEY `security_field_id` (`security_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_issuers`
--

CREATE TABLE IF NOT EXISTS `security_issuers` (
  `security_issuer_id` int(11) NOT NULL AUTO_INCREMENT,
  `issuer_name` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_issuer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_payments`
--

DROP TABLE IF EXISTS `security_payments`;
CREATE TABLE IF NOT EXISTS `security_payments` (
  `security_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `security_id` int(11) DEFAULT NULL,
  `record_date` datetime DEFAULT NULL,
  `ex_date` datetime DEFAULT NULL,
  `valuta_date` datetime DEFAULT NULL,
  `security_payment_type_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `amount_type_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_payment_id`),
  KEY `security_id` (`security_id`),
  KEY `security_payment_type_id` (`security_payment_type_id`),
  KEY `amount_type_id` (`amount_type_id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_payment_types`
--

DROP TABLE IF EXISTS `security_payment_types`;
CREATE TABLE IF NOT EXISTS `security_payment_types` (
  `security_payment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_payment_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_prices`
--

CREATE TABLE IF NOT EXISTS `security_prices` (
  `security_id` int(11) NOT NULL,
  `quote_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bid` float DEFAULT NULL,
  `ask` float DEFAULT NULL,
  `last` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `security_price_feed_types`
--

CREATE TABLE IF NOT EXISTS `security_price_feed_types` (
  `feed_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`feed_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_quote_types`
--

CREATE TABLE IF NOT EXISTS `security_quote_types` (
  `security_quote_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `quantity_factor` double DEFAULT '1' COMMENT 'multiply the trade size with this factor to get the correct premium',
  PRIMARY KEY (`security_quote_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_triggers`
--

CREATE TABLE IF NOT EXISTS `security_triggers` (
  `security_trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_type_id` int(11) DEFAULT NULL,
  `trigger_value` double DEFAULT NULL,
  `comment` text,
  `start` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NULL DEFAULT NULL,
  `trigger_status_id` int(11) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`security_trigger_id`),
  KEY `trigger_type_id` (`trigger_type_id`),
  KEY `trigger_status_id` (`trigger_status_id`),
  KEY `security_id` (`security_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_trigger_stati`
--

CREATE TABLE IF NOT EXISTS `security_trigger_stati` (
  `trigger_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`trigger_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_trigger_types`
--

CREATE TABLE IF NOT EXISTS `security_trigger_types` (
  `trigger_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `code_id` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`trigger_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_types`
--

CREATE TABLE IF NOT EXISTS `security_types` (
  `security_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `security_quote_type_id` int(11) DEFAULT NULL,
  `code_id` varchar(100) DEFAULT NULL COMMENT 'field to link predefined records to the code: cannot be changed by the users',
  PRIMARY KEY (`security_type_id`),
  KEY `security_quote_type_id` (`security_quote_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_underlyings`
--

CREATE TABLE IF NOT EXISTS `security_underlyings` (
  `security_underlying_id` int(11) NOT NULL AUTO_INCREMENT,
  `security_id` int(11) NOT NULL,
  `underlying_id` int(11) NOT NULL,
  `weight` double DEFAULT NULL,
  `delta` double DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`security_underlying_id`),
  KEY `security_id` (`security_id`),
  KEY `underlying_id` (`underlying_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `systems`
--

CREATE TABLE IF NOT EXISTS `systems` (
  `system_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_heartbeat` datetime DEFAULT NULL,
  `system_name` varchar(200) NOT NULL,
  `code_id` varchar(200) NOT NULL,
  PRIMARY KEY (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE IF NOT EXISTS `trades` (
  `trade_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `internal_person_id` int(11) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `size` double DEFAULT NULL,
  `rational` text,
  `settlement_date` date DEFAULT NULL,
  `trade_type_id` int(11) DEFAULT NULL,
  `trade_date` datetime DEFAULT NULL,
  `premium` double DEFAULT NULL,
  `fees` double DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL,
  `trade_status_id` int(11) DEFAULT '0',
  `checked` tinyint(1) DEFAULT NULL,
  `comment` text,
  `bank_ref_id` varchar(100) DEFAULT NULL,
  `counterparty_ref_id` varchar(100) DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL COMMENT 'date and time until this order is valid',
  `fx_rate` double DEFAULT NULL COMMENT 'the fx rate used for the trade to convert from the premium currency to the portfolio ref currency',
  `premium_settlement_currency` double DEFAULT NULL COMMENT 'the trade premium in portfolio currency, does not change even if the portfolio currency changes',
  `settlement_currency_id` int(11) DEFAULT NULL COMMENT 'the portfolio currency at the time the trade was done',
  `fees_internal` double DEFAULT NULL,
  `fees_bank` double DEFAULT NULL,
  `fees_extern` double DEFAULT NULL,
  `contact_type_id` int(11) DEFAULT NULL COMMENT 'fill only if no seperate client contact is saved',
  `date_placed` timestamp NULL DEFAULT NULL COMMENT 'time when the order has been placed at the markets',
  `date_client` timestamp NULL DEFAULT NULL COMMENT 'time when the decition to by was done or when the client has given the order, usually equal to creation time',
  `related_trade_id` int(11) DEFAULT NULL COMMENT 'to book several partial executions of one order',
  `confirmation_time` timestamp NULL DEFAULT NULL,
  `trade_confirmation_type_id` int(11) DEFAULT NULL,
  `scanned_bank_confirmation` text,
  `confirmation_time_bank` timestamp NULL DEFAULT NULL,
  `confirmation_time_client` timestamp NULL DEFAULT NULL,
  `bank_text_ins` text COMMENT 'comment given by the bank for the security booking',
  `bank_text_cash` text COMMENT 'bank comment for the cash transaction',
  `premium_netto` double DEFAULT NULL COMMENT 'fixed premium as saved by the user; can be used for example to by futures: just enter the premium to hedge and the price and the system can calculate the number of contracts',
  `premium_sett` double DEFAULT NULL COMMENT 'brutto premium in settlement currency',
  `premium_sett_netto` double DEFAULT NULL COMMENT 'netto premium in settlement currency',
  `fee_text` text COMMENT 'a description of the fees as send by the bank',
  `trade_confirmation_person` varchar(300) DEFAULT NULL,
  `bo_status` int(11) DEFAULT NULL COMMENT 'used at the moment to subpress profolio message after entering or changing a trade trade',
  PRIMARY KEY (`trade_id`),
  KEY `account_id` (`account_id`),
  KEY `tp_person_id` (`internal_person_id`),
  KEY `security_id` (`security_id`),
  KEY `currency_id` (`currency_id`),
  KEY `trade_type_id` (`trade_type_id`),
  KEY `portfolio_id` (`portfolio_id`),
  KEY `trade_status_id` (`trade_status_id`),
  KEY `settlement_currency_id` (`settlement_currency_id`),
  KEY `related_trade_id` (`related_trade_id`),
  KEY `contact_type_id` (`contact_type_id`),
  KEY `trade_confirmation_type_id` (`trade_confirmation_type_id`),
  KEY `counterparty_ref_id` (`counterparty_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_confirmation_types`
--

DROP TABLE IF EXISTS `trade_confirmation_types`;
CREATE TABLE IF NOT EXISTS `trade_confirmation_types` (
  `trade_confirmation_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`trade_confirmation_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_payments`
--

DROP TABLE IF EXISTS `trade_payments`;
CREATE TABLE IF NOT EXISTS `trade_payments` (
  `trade_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `percent` double DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `trade_payment_type_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`trade_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_payment_types`
--

DROP TABLE IF EXISTS `trade_payment_types`;
CREATE TABLE IF NOT EXISTS `trade_payment_types` (
  `trade_payment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`trade_payment_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_stati`
--

CREATE TABLE IF NOT EXISTS `trade_stati` (
  `trade_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  `use_for_position` tinyint(1) DEFAULT NULL,
  `use_for_simulation` tinyint(1) DEFAULT NULL,
  `use_for_reconciliation` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`trade_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_types`
--

CREATE TABLE IF NOT EXISTS `trade_types` (
  `trade_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `factor` double DEFAULT NULL,
  `use_fx` tinyint(1) DEFAULT NULL,
  `use_cash` tinyint(1) DEFAULT NULL,
  `use_fx_swap` tinyint(1) DEFAULT NULL,
  `do_not_use_size` tinyint(1) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`trade_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `trade_type_bank_codes`
--

DROP TABLE IF EXISTS `trade_type_bank_codes`;
CREATE TABLE IF NOT EXISTS `trade_type_bank_codes` (
  `trade_type_bank_code_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) DEFAULT NULL,
  `trade_type_id` int(11) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `description` text,
  `comment` text,
  PRIMARY KEY (`trade_type_bank_code_id`),
  KEY `trade_type_id` (`trade_type_id`),
  KEY `bank_id` (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `values`
--

CREATE TABLE IF NOT EXISTS `values` (
  `value_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `value_status_id` int(11) DEFAULT '0' COMMENT 'e.g. Fixed Value agreed with the client',
  `value_type_id` int(11) DEFAULT '0' COMMENT 'Asset under Management',
  `val_number` double DEFAULT NULL COMMENT 'the value itself',
  `value_date` datetime DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `security_id` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`value_id`),
  KEY `account_id` (`account_id`),
  KEY `value_status_id` (`value_status_id`),
  KEY `value_type_id` (`value_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_stati`
--

CREATE TABLE IF NOT EXISTS `value_stati` (
  `value_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_text` varchar(200) NOT NULL,
  PRIMARY KEY (`value_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_types`
--

CREATE TABLE IF NOT EXISTS `value_types` (
  `value_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`value_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_accounts`
--
CREATE TABLE IF NOT EXISTS `v_accounts` (
`account_id` int(11)
,`account_select_name` text
,`account_name` varchar(20)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_account_mandates`
--
CREATE TABLE IF NOT EXISTS `v_account_mandates` (
`account_mandat_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_account_person_types`
--
CREATE TABLE IF NOT EXISTS `v_account_person_types` (
`account_person_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_account_types`
--
CREATE TABLE IF NOT EXISTS `v_account_types` (
`account_type_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_action_stati`
--
CREATE TABLE IF NOT EXISTS `v_action_stati` (
`action_status_id` int(11)
,`status_text` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_addresses`
--
CREATE TABLE IF NOT EXISTS `v_addresses` (
`address_id` int(11)
,`select_address` text
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_address_link_types`
--
CREATE TABLE IF NOT EXISTS `v_address_link_types` (
`address_link_type_id` int(11)
,`type_name` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_banks`
--
CREATE TABLE IF NOT EXISTS `v_banks` (
`bank_id` int(11)
,`bank_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_bill`
--
CREATE TABLE IF NOT EXISTS `v_bill` (
`account_id` int(11)
,`account_name` varchar(200)
,`aum` double
,`fee_tp` double
,`start` date
,`end` date
,`days` int(7)
,`fees` double(19,2)
,`mwst` double(19,2)
,`total` double(19,2)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_bill_calc`
--
CREATE TABLE IF NOT EXISTS `v_bill_calc` (
`account_id` int(11)
,`account_name` varchar(200)
,`aum` double
,`fee_tp` double
,`start` date
,`end` date
,`days` int(7)
,`fees` double(19,2)
,`mwst` double(19,2)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_bill_data`
--
CREATE TABLE IF NOT EXISTS `v_bill_data` (
`account_id` int(11)
,`account_name` varchar(200)
,`aum` double
,`fee_tp` double
,`start` date
,`end` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contacts`
--
CREATE TABLE IF NOT EXISTS `v_contacts` (
`contact_id` int(11)
,`contact_select` mediumtext
,`account_name` varchar(20)
,`contact_time` timestamp
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_categories`
--
CREATE TABLE IF NOT EXISTS `v_contact_categories` (
`contact_category_id` int(11)
,`category_name` varchar(300)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_member_types`
--
CREATE TABLE IF NOT EXISTS `v_contact_member_types` (
`contact_member_type_id` int(11)
,`member_type` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_number_types`
--
CREATE TABLE IF NOT EXISTS `v_contact_number_types` (
`contact_number_type_id` int(11)
,`type_name` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_report`
--
CREATE TABLE IF NOT EXISTS `v_contact_report` (
`type` varchar(200)
,`person` varchar(300)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_report_actions`
--
CREATE TABLE IF NOT EXISTS `v_contact_report_actions` (
`contact_id` int(11)
,`description` text
,`deadline` datetime
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_report_head`
--
CREATE TABLE IF NOT EXISTS `v_contact_report_head` (
`contact_id` int(11)
,`headline` text
,`datum` timestamp
,`zeit` timestamp
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_report_members`
--
CREATE TABLE IF NOT EXISTS `v_contact_report_members` (
`contact_id` int(11)
,`type` varchar(200)
,`person` varchar(300)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_report_notes`
--
CREATE TABLE IF NOT EXISTS `v_contact_report_notes` (
`contact_notes` int(11)
,`description` text
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contact_types`
--
CREATE TABLE IF NOT EXISTS `v_contact_types` (
`contact_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_contract_types`
--
CREATE TABLE IF NOT EXISTS `v_contract_types` (
`contract_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_countries`
--
CREATE TABLE IF NOT EXISTS `v_countries` (
`country_id` int(11)
,`name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_currencies`
--
CREATE TABLE IF NOT EXISTS `v_currencies` (
`currency_id` int(11)
,`symbol` varchar(20)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_currencies_2`
--
CREATE TABLE IF NOT EXISTS `v_currencies_2` (
`currency_id` int(11)
,`symbol` varchar(20)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_currency_pairs`
--
CREATE TABLE IF NOT EXISTS `v_currency_pairs` (
`currency_pair_id` int(11)
,`select_name` varchar(200)
,`sort_name` varchar(41)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_currency_price_feed`
--
CREATE TABLE IF NOT EXISTS `v_currency_price_feed` (
`currency_pair_id` int(11)
,`currency1_id` int(11)
,`currency2_id` int(11)
,`factor` double
,`description` varchar(200)
,`fx_rate` double
,`decimals` int(11)
,`symbol_market_map` varchar(200)
,`symbol_yahoo` varchar(200)
,`comment` text
,`price_feed_type_id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_document_categories`
--
CREATE TABLE IF NOT EXISTS `v_document_categories` (
`document_category_id` int(11)
,`category_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_document_types`
--
CREATE TABLE IF NOT EXISTS `v_document_types` (
`document_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_events_open`
--
CREATE TABLE IF NOT EXISTS `v_events_open` (
`event_id` int(11)
,`event_type_id` int(11)
,`description` text
,`account_id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_event_stati`
--
CREATE TABLE IF NOT EXISTS `v_event_stati` (
`event_status_id` int(11)
,`status_text` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_event_types`
--
CREATE TABLE IF NOT EXISTS `v_event_types` (
`event_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_exposure_items`
--
CREATE TABLE IF NOT EXISTS `v_exposure_items` (
`exposure_item_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_exposure_target_for_portfolios`
--
DROP VIEW IF EXISTS `v_exposure_target_for_portfolios`;
CREATE TABLE IF NOT EXISTS `v_exposure_target_for_portfolios` (
`exposure_target_id` int(11)
,`exposure_item_id` int(11)
,`portfolio_id` int(11)
,`neutral` float
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_exposure_target_values`
--
DROP VIEW IF EXISTS `v_exposure_target_values`;
CREATE TABLE IF NOT EXISTS `v_exposure_target_values` (
`exposure_target_value_id` int(11)
,`exposure_target_id` int(11)
,`portfolio_id` int(11)
,`portfolio_name` varchar(200)
,`item_name` varchar(200)
,`calc_value` double(18,1)
,`optimized` double(18,1)
,`diff` double(18,1)
,`neutral` double(18,1)
,`limit_up` double(18,1)
,`limit_down` double(18,1)
,`exception` double(18,1)
,`diff_neutral` double(18,1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_exposure_target_with_exceptions`
--
DROP VIEW IF EXISTS `v_exposure_target_with_exceptions`;
CREATE TABLE IF NOT EXISTS `v_exposure_target_with_exceptions` (
`exposure_target_id` int(11)
,`exposure_item_id` int(11)
,`portfolio_id` int(11)
,`neutral` float
,`exception` float
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_exposure_types`
--
CREATE TABLE IF NOT EXISTS `v_exposure_types` (
`exposure_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_log_users`
--
DROP VIEW IF EXISTS `v_log_users`;
CREATE TABLE IF NOT EXISTS `v_log_users` (
`user_id` int(11)
,`username` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_log_user_groups`
--
DROP VIEW IF EXISTS `v_log_user_groups`;
CREATE TABLE IF NOT EXISTS `v_log_user_groups` (
`user_group_id` int(11)
,`group_name` varchar(300)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_log_user_rights`
--
DROP VIEW IF EXISTS `v_log_user_rights`;
CREATE TABLE IF NOT EXISTS `v_log_user_rights` (
`user_right_id` int(11)
,`right_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_log_user_types`
--
DROP VIEW IF EXISTS `v_log_user_types`;
CREATE TABLE IF NOT EXISTS `v_log_user_types` (
`user_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_message_types`
--
DROP VIEW IF EXISTS `v_message_types`;
CREATE TABLE IF NOT EXISTS `v_message_types` (
`message_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_persons`
--
CREATE TABLE IF NOT EXISTS `v_persons` (
`person_id` int(11)
,`select_name` text
,`display_name` varchar(501)
,`contact_number` varchar(100)
,`person_type_id` int(11)
,`internal` tinyint(4)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_person_types`
--
CREATE TABLE IF NOT EXISTS `v_person_types` (
`person_type_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolios`
--
CREATE TABLE IF NOT EXISTS `v_portfolios` (
`portfolio_id` int(11)
,`portfolio_select_name` varchar(422)
,`account_name` varchar(20)
,`inactive` tinyint(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_allocation`
--
CREATE TABLE IF NOT EXISTS `v_portfolio_allocation` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`asset_class` varchar(200)
,`position` double
,`trade_curr` varchar(20)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`ref_decimals` int(11)
,`bid` float
,`ask` float
,`last` float
,`open_value` double
,`buy_price` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_pct` double
,`pnl_market` double
,`pnl_fx` double
,`aum` double
,`aum_pct` double(18,1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_persons`
--
DROP VIEW IF EXISTS `v_portfolio_persons`;
CREATE TABLE IF NOT EXISTS `v_portfolio_persons` (
`portfolio_id` int(11)
,`account_id` int(11)
,`account_name` varchar(200)
,`portfolio_function` varchar(200)
,`portfolio_function_code_id` varchar(200)
,`person_name` text
,`contact_number` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pnl`
--
CREATE TABLE IF NOT EXISTS `v_portfolio_pnl` (
`portfolio_id` int(11)
,`aum` double
,`pnl` double
,`update_time` datetime
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos`
--
CREATE TABLE IF NOT EXISTS `v_portfolio_pos` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`currency_id` int(11)
,`asset_class` varchar(200)
,`position` double
,`trade_curr` varchar(20)
,`sec_curr` varchar(20)
,`security_issuer_id` int(11)
,`decimals` int(11)
,`ref_decimals` int(11)
,`bid` float
,`ask` float
,`last` float
,`open_value` double
,`buy_price` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_pct` double
,`pnl_market` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos_calc`
--
CREATE TABLE IF NOT EXISTS `v_portfolio_pos_calc` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`security_name` varchar(200)
,`asset_class` varchar(200)
,`sec_curr` varchar(20)
,`security_issuer_id` int(11)
,`trade_curr` varchar(20)
,`decimals` int(11)
,`ref_decimals` int(11)
,`position` double
,`bid` double
,`ask` double
,`last` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos_closed`
--
CREATE TABLE IF NOT EXISTS `v_portfolio_pos_closed` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`currency_id` int(11)
,`asset_class` varchar(200)
,`position` double
,`trade_curr` varchar(20)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`ref_decimals` int(11)
,`size_long` double
,`premium_long` double
,`avg_buy` double
,`size_short` double
,`premium_short` double
,`avg_sell` double
,`pnl_total` double
,`bid` double
,`ask` double
,`last` double
,`open_value` double
,`buy_price` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_pct` double
,`pnl_market` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos_derivate`
--
DROP VIEW IF EXISTS `v_portfolio_pos_derivate`;
CREATE TABLE IF NOT EXISTS `v_portfolio_pos_derivate` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_type_id` int(11)
,`security_name` varchar(200)
,`currency_id` int(11)
,`asset_class` varchar(200)
,`position` double
,`trade_curr` varchar(20)
,`sec_curr` varchar(20)
,`security_issuer_id` int(11)
,`decimals` int(11)
,`ref_decimals` int(11)
,`bid` float
,`ask` float
,`last` float
,`open_value` double
,`buy_price` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_pct` double
,`pnl_market` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos_get`
--
DROP VIEW IF EXISTS `v_portfolio_pos_get`;
CREATE TABLE IF NOT EXISTS `v_portfolio_pos_get` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`position` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_pos_get_prices`
--
DROP VIEW IF EXISTS `v_portfolio_pos_get_prices`;
CREATE TABLE IF NOT EXISTS `v_portfolio_pos_get_prices` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`position` double
,`bid` float
,`ask` float
,`last_price` float
,`update_time` datetime
,`used_price` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_portfolio_types`
--
DROP VIEW IF EXISTS `v_portfolio_types`;
CREATE TABLE IF NOT EXISTS `v_portfolio_types` (
`portfolio_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_position_pnl`
--
CREATE TABLE IF NOT EXISTS `v_position_pnl` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`currency_id` int(11)
,`trade_date` datetime
,`price` double
,`price_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`bid` float
,`ask` float
,`last_price` double
,`sell_price` double
,`update_time` datetime
,`pos_value` double
,`pos_value_ref` double
,`pos_value_ref_open` double
,`pos_value_last` double
,`pos_value_last_ref` double
,`pos_value_last_ref_open` double
,`pnl` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_last` double
,`decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recon_files`
--
CREATE TABLE IF NOT EXISTS `v_recon_files` (
`recon_file_id` int(11)
,`file_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recon_file_types`
--
CREATE TABLE IF NOT EXISTS `v_recon_file_types` (
`recon_file_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recon_step_select`
--
CREATE TABLE IF NOT EXISTS `v_recon_step_select` (
`recon_step_id` int(11)
,`recon_select_name` varchar(417)
,`file_name` varchar(200)
,`order_nbr` bigint(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recon_step_types`
--
CREATE TABLE IF NOT EXISTS `v_recon_step_types` (
`recon_step_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recon_value_types`
--
CREATE TABLE IF NOT EXISTS `v_recon_value_types` (
`recon_value_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_securities`
--
CREATE TABLE IF NOT EXISTS `v_securities` (
`security_id` int(11)
,`select_name` varchar(223)
,`name` varchar(200)
,`ISIN` varchar(20)
,`last_price` float
,`bid` float
,`ask` float
,`currency_id` int(11)
,`curr` varchar(20)
,`security_type_id` int(11)
,`currency_pair_id` int(11)
,`bsi_id` int(11)
,`symbol_market_map` varchar(200)
,`price_feed_type_id` int(11)
,`valor` varchar(15)
,`security_quote_type_id` int(11)
,`security_issuer_id` int(11)
,`type` varchar(200)
,`quantity_factor` double
,`quote_type` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_securities_mm_symbol_request`
--
CREATE TABLE IF NOT EXISTS `v_securities_mm_symbol_request` (
`ISIN` varchar(20)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_amount_types`
--
DROP VIEW IF EXISTS `v_security_amount_types`;
CREATE TABLE IF NOT EXISTS `v_security_amount_types` (
`security_amount_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_exposure_stati`
--
CREATE TABLE IF NOT EXISTS `v_security_exposure_stati` (
`security_exposure_status_id` int(11)
,`status_text` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_fields`
--
CREATE TABLE IF NOT EXISTS `v_security_fields` (
`security_field_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_field_source_types`
--
DROP VIEW IF EXISTS `v_security_field_source_types`;
CREATE TABLE IF NOT EXISTS `v_security_field_source_types` (
`security_field_source_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_issuers`
--
CREATE TABLE IF NOT EXISTS `v_security_issuers` (
`security_issuer_id` int(11)
,`issuer_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_payment_types`
--
DROP VIEW IF EXISTS `v_security_payment_types`;
CREATE TABLE IF NOT EXISTS `v_security_payment_types` (
`security_payment_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_pnl`
--
CREATE TABLE IF NOT EXISTS `v_security_pnl` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`security_name` varchar(200)
,`asset_class` varchar(200)
,`sec_curr` varchar(20)
,`security_issuer_id` int(11)
,`trade_curr` varchar(20)
,`decimals` int(11)
,`ref_decimals` int(11)
,`position` double
,`bid` double
,`ask` double
,`last` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_pos`
--
CREATE TABLE IF NOT EXISTS `v_security_pos` (
`security_id` int(11)
,`security_name` varchar(200)
,`ISIN` varchar(20)
,`currency_id` int(11)
,`position` double
,`trade_curr` varchar(20)
,`sec_curr` varchar(20)
,`security_issuer_id` int(11)
,`decimals` int(11)
,`ref_decimals` int(11)
,`bid` double
,`ask` double
,`last` double
,`open_value` double
,`buy_price` double
,`pos_value` double
,`pos_value_ref` double
,`pnl` double
,`pnl_last` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_pct` double
,`pnl_market` double
,`pnl_fx` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_price_feed`
--
CREATE TABLE IF NOT EXISTS `v_security_price_feed` (
`security_id` int(11)
,`name` varchar(200)
,`ISIN` varchar(20)
,`last_price` float
,`currency_id` int(11)
,`security_type_id` int(11)
,`currency_pair_id` int(11)
,`bsi_id` int(11)
,`symbol_market_map` varchar(200)
,`price_feed_type_id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_price_feed_types`
--
CREATE TABLE IF NOT EXISTS `v_security_price_feed_types` (
`feed_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_quote_types`
--
CREATE TABLE IF NOT EXISTS `v_security_quote_types` (
`security_quote_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_triggers_open`
--
DROP VIEW IF EXISTS `v_security_triggers_open`;
CREATE TABLE IF NOT EXISTS `v_security_triggers_open` (
`security_trigger_id` int(11)
,`trigger_type_id` int(11)
,`type_name` varchar(200)
,`type_code_id` varchar(200)
,`trigger_status_id` int(11)
,`status_text` varchar(200)
,`status_code_id` varchar(200)
,`trigger_value` double
,`security_id` int(11)
,`portfolio_id` int(11)
,`sec_name` varchar(200)
,`ISIN` varchar(20)
,`bid` float
,`ask` float
,`last_price` float
,`symbol_mm` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_trigger_stati`
--
CREATE TABLE IF NOT EXISTS `v_security_trigger_stati` (
`trigger_status_id` int(11)
,`status_text` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_trigger_types`
--
CREATE TABLE IF NOT EXISTS `v_security_trigger_types` (
`trigger_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_security_types`
--
CREATE TABLE IF NOT EXISTS `v_security_types` (
`security_type_id` int(11)
,`description` varchar(200)
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`quote_type` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_test_price_link`
--
DROP VIEW IF EXISTS `v_test_price_link`;
CREATE TABLE IF NOT EXISTS `v_test_price_link` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`bid` float
,`ask` float
,`last` float
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trades_confirm_to_bank`
--
DROP VIEW IF EXISTS `v_trades_confirm_to_bank`;
CREATE TABLE IF NOT EXISTS `v_trades_confirm_to_bank` (
`portfolio_id` int(11)
,`portfolio_name` varchar(200)
,`trade_id` int(11)
,`trade_type` varchar(200)
,`size` double
,`security_name` varchar(200)
,`price` double
,`valid_until` timestamp
,`creation_time` timestamp
,`confirmation_time_bank` timestamp
,`person_name` varchar(501)
,`portfolio_manager` varchar(501)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trades_confirm_to_client`
--
DROP VIEW IF EXISTS `v_trades_confirm_to_client`;
CREATE TABLE IF NOT EXISTS `v_trades_confirm_to_client` (
`portfolio_id` int(11)
,`portfolio_name` varchar(200)
,`trade_id` int(11)
,`trade_type` varchar(200)
,`size` double
,`security_name` varchar(200)
,`price` double
,`valid_until` timestamp
,`creation_time` timestamp
,`confirmation_time_client` timestamp
,`person_name` varchar(12)
,`portfolio_manager` varchar(501)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trades_fx`
--
DROP VIEW IF EXISTS `v_trades_fx`;
CREATE TABLE IF NOT EXISTS `v_trades_fx` (
`trade_id` int(11)
,`portfolio_id` int(11)
,`trade_date` datetime
,`valid_until` timestamp
,`confirmation_time` timestamp
,`trade_confirmation_type_id` int(11)
,`trade_confirmation_person` varchar(300)
,`internal_person_id` int(11)
,`contact_type_id` int(11)
,`currency_id` int(11)
,`fx_rate` double
,`settlement_currency_id` int(11)
,`trade_type_id` int(11)
,`trade_status_id` int(11)
,`size` double
,`premium_sett` double
,`premium_netto` double
,`premium_sett_netto` double
,`rational` text
,`settlement_date` date
,`creation_time` timestamp
,`bank_ref_id` varchar(100)
,`counterparty_ref_id` varchar(100)
,`bank_text_ins` text
,`checked` tinyint(1)
,`related_trade_id` int(11)
,`scanned_bank_confirmation` text
,`bo_status` int(11)
,`comment` text
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_confirmation_types`
--
DROP VIEW IF EXISTS `v_trade_confirmation_types`;
CREATE TABLE IF NOT EXISTS `v_trade_confirmation_types` (
`trade_confirmation_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_fifo_sum_size`
--
CREATE TABLE IF NOT EXISTS `v_trade_fifo_sum_size` (
`portfolio_id` int(11)
,`security_id` int(11)
,`trade_date` datetime
,`size_sum` double
,`calc_premium_sum` double
,`premium_sum` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_fifo_sum_size_test`
--
DROP VIEW IF EXISTS `v_trade_fifo_sum_size_test`;
CREATE TABLE IF NOT EXISTS `v_trade_fifo_sum_size_test` (
`portfolio_id` int(11)
,`security_id` int(11)
,`trade_date` datetime
,`size_sum` double
,`calc_premium_sum` double
,`premium_sum` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_fifo_type`
--
CREATE TABLE IF NOT EXISTS `v_trade_fifo_type` (
`portfolio_id` int(11)
,`security_id` int(11)
,`trade_date` datetime
,`size` double
,`size_sum` double
,`calc_premium_sum` double
,`premium_sum` double
,`trade_type` varchar(8)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_fifo_upl`
--
CREATE TABLE IF NOT EXISTS `v_trade_fifo_upl` (
`portfolio_id` int(11)
,`security_id` int(11)
,`trade_date` datetime
,`size` double
,`size_sum` double
,`calc_premium_sum` double
,`premium_sum` double
,`trade_type` varchar(8)
,`upl_in_pct` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_payment_types`
--
DROP VIEW IF EXISTS `v_trade_payment_types`;
CREATE TABLE IF NOT EXISTS `v_trade_payment_types` (
`trade_payment_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_pnl`
--
CREATE TABLE IF NOT EXISTS `v_trade_pnl` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`security_type_id` int(11)
,`asset_class` varchar(200)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`price_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`bid` float
,`ask` float
,`last_price` double
,`sell_price` double
,`update_time` datetime
,`pos_value` double
,`pos_value_ref` double
,`pos_value_ref_open` double
,`pos_value_last` double
,`pos_value_last_ref` double
,`pos_value_last_ref_open` double
,`pnl` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_last` double
,`pnl_fx` double
,`pnl_fx_pct` double
,`decimals` int(11)
,`ref_decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_pnl_calc`
--
CREATE TABLE IF NOT EXISTS `v_trade_pnl_calc` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`security_type_id` int(11)
,`asset_class` varchar(200)
,`asset_code` varchar(100)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`price_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`bid` float
,`ask` float
,`last_price` float
,`sell_price` double
,`update_time` datetime
,`pos_value` double
,`pos_value_ref` double
,`pos_value_ref_open` double
,`pos_value_last` double
,`pos_value_last_ref` double
,`pos_value_last_ref_open` double
,`pnl` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_last` double
,`decimals` int(11)
,`ref_decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_pnl_calc_cash`
--
CREATE TABLE IF NOT EXISTS `v_trade_pnl_calc_cash` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(20)
,`security_type_id` int(11)
,`asset_class` varchar(200)
,`asset_code` varchar(100)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`price_ref` float
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`bid` float
,`ask` float
,`last_price` double
,`sell_price` double
,`update_time` datetime
,`pos_value` double
,`pos_value_ref` double
,`pos_value_ref_open` double
,`pos_value_last` double
,`pos_value_last_ref` double
,`pos_value_last_ref_open` double
,`pnl` double
,`pnl_ref` double
,`pnl_ref_open` double
,`pnl_last` double
,`decimals` int(11)
,`ref_decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_pnl_get_prices`
--
CREATE TABLE IF NOT EXISTS `v_trade_pnl_get_prices` (
`portfolio_id` int(11)
,`security_id` int(11)
,`security_name` varchar(200)
,`security_type_id` int(11)
,`asset_class` varchar(200)
,`asset_code` varchar(100)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`price_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`bid` float
,`ask` float
,`last_price` float
,`sell_price` double
,`update_time` datetime
,`ref_currency_id` int(11)
,`decimals` int(11)
,`ref_decimals` int(11)
,`fx_rate` double
,`fx_rate_open` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`calc_premium` double
,`premium` double
,`fx_rate_open` double
,`ref_currency_id` int(11)
,`decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_fifo`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_fifo` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`trade_date` datetime
,`price` double
,`size` double
,`size_sum` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`calc_premium` double
,`premium` double
,`fx_rate_open` double
,`ref_currency_id` int(11)
,`decimals` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_long_short`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_long_short` (
`portfolio_id` int(11)
,`security_id` int(11)
,`size_long` double
,`premium_long` double
,`size_short` double
,`premium_short` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_ref`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_ref` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`trade_date` datetime
,`price` double
,`price_ref` double
,`price_trade_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_ref` double
,`calc_premium_trade_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`ref_currency_id` int(11)
,`ref_decimals` int(11)
,`fx_rate` double
,`fx_rate_open` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_ref_get`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_ref_get` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`trade_date` datetime
,`price` double
,`size` double
,`size_sum` double
,`calc_premium` double
,`premium` double
,`ref_currency_id` int(11)
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`trade_fx_rate` double
,`fx_rate_open` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_ref_get_fx`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_ref_get_fx` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`trade_date` datetime
,`price` double
,`price_trade_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_trade_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`ref_currency_id` int(11)
,`ref_decimals` int(11)
,`trade_fx_rate` double
,`fx_rate_open` double
,`fx_rate` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_premium_ref_get_sec`
--
CREATE TABLE IF NOT EXISTS `v_trade_premium_ref_get_sec` (
`portfolio_id` int(11)
,`security_id` int(11)
,`currency_id` int(11)
,`trade_curr` varchar(20)
,`sec_currency_id` int(11)
,`security_issuer_id` int(11)
,`sec_curr` varchar(20)
,`decimals` int(11)
,`trade_date` datetime
,`price` double
,`price_trade_ref` double
,`price_ref_open` double
,`size` double
,`security_quote_type_id` int(11)
,`quantity_factor` double
,`calc_premium` double
,`calc_premium_trade_ref` double
,`calc_premium_ref_open` double
,`premium` double
,`ref_currency_id` int(11)
,`ref_decimals` int(11)
,`trade_fx_rate` double
,`fx_rate_open` double
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_security`
--
DROP VIEW IF EXISTS `v_trade_security`;
CREATE TABLE IF NOT EXISTS `v_trade_security` (
`trade_id` int(11)
,`security_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_select`
--
CREATE TABLE IF NOT EXISTS `v_trade_select` (
`trade_id` int(11)
,`trade_key` varchar(444)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_stati`
--
CREATE TABLE IF NOT EXISTS `v_trade_stati` (
`trade_status_id` int(11)
,`status_text` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_types`
--
CREATE TABLE IF NOT EXISTS `v_trade_types` (
`trade_type_id` int(11)
,`description` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade_type_bank_codes`
--
DROP VIEW IF EXISTS `v_trade_type_bank_codes`;
CREATE TABLE IF NOT EXISTS `v_trade_type_bank_codes` (
`trade_type_bank_code_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_underlyings`
--
CREATE TABLE IF NOT EXISTS `v_underlyings` (
`underlying_id` int(11)
,`name` varchar(200)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_types`
--
CREATE TABLE IF NOT EXISTS `v_user_types` (
`user_type_id` int(11)
,`type_name` varchar(200)
);
-- --------------------------------------------------------

--
-- Structure for view `v_accounts`
--
DROP TABLE IF EXISTS `v_accounts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_accounts` AS select NULL AS `account_id`,' not set' AS `account_select_name`,' not set' AS `account_name` union select `accounts`.`account_id` AS `account_id`,concat_ws(' ',`accounts`.`account_name`,`account_mandates`.`description`,`banks`.`bank_name`,`currencies`.`symbol`) AS `account_select_name`,cast(`accounts`.`account_name` as unsigned) AS `account_name` from (((`accounts` join `account_mandates`) join `currencies`) join `banks`) where ((`accounts`.`account_mandat_id` = `account_mandates`.`account_mandat_id`) and (`accounts`.`currency_id` = `currencies`.`currency_id`) and (`accounts`.`bank_id` = `banks`.`bank_id`)) order by cast(`account_name` as unsigned);

-- --------------------------------------------------------

--
-- Structure for view `v_account_mandates`
--
DROP TABLE IF EXISTS `v_account_mandates`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_account_mandates` AS select NULL AS `account_mandat_id`,' not set' AS `description` union select `account_mandates`.`account_mandat_id` AS `account_mandat_id`,`account_mandates`.`description` AS `description` from `account_mandates`;

-- --------------------------------------------------------

--
-- Structure for view `v_account_person_types`
--
DROP TABLE IF EXISTS `v_account_person_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_account_person_types` AS select NULL AS `account_person_type_id`,' not set' AS `type_name` union select `account_person_types`.`account_person_type_id` AS `account_person_type_id`,`account_person_types`.`type_name` AS `type_name` from `account_person_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_account_types`
--
DROP TABLE IF EXISTS `v_account_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_account_types` AS select NULL AS `account_type_id`,' not set' AS `description` union select `account_types`.`account_type_id` AS `account_type_id`,`account_types`.`description` AS `description` from `account_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_action_stati`
--
DROP TABLE IF EXISTS `v_action_stati`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_action_stati` AS select NULL AS `action_status_id`,' not set' AS `status_text` union select `action_stati`.`action_status_id` AS `action_status_id`,`action_stati`.`status_text` AS `status_text` from `action_stati`;

-- --------------------------------------------------------

--
-- Structure for view `v_addresses`
--
DROP TABLE IF EXISTS `v_addresses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_addresses` AS select NULL AS `address_id`,' not set' AS `select_address` union select `addresses`.`address_id` AS `address_id`,concat_ws(', ',`addresses`.`street`,`addresses`.`city_code`,`addresses`.`city`) AS `select_address` from `addresses`;

-- --------------------------------------------------------

--
-- Structure for view `v_address_link_types`
--
DROP TABLE IF EXISTS `v_address_link_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_address_link_types` AS select NULL AS `address_link_type_id`,' not set' AS `type_name` union select `address_link_types`.`address_link_type_id` AS `address_link_type_id`,`address_link_types`.`type_name` AS `type_name` from `address_link_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_banks`
--
DROP TABLE IF EXISTS `v_banks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_banks` AS select NULL AS `bank_id`,' not set' AS `bank_name` union select `banks`.`bank_id` AS `bank_id`,`banks`.`bank_name` AS `bank_name` from `banks`;

-- --------------------------------------------------------

--
-- Structure for view `v_bill`
--
DROP TABLE IF EXISTS `v_bill`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bill` AS select `v_bill_calc`.`account_id` AS `account_id`,`v_bill_calc`.`account_name` AS `account_name`,`v_bill_calc`.`aum` AS `aum`,`v_bill_calc`.`fee_tp` AS `fee_tp`,`v_bill_calc`.`start` AS `start`,`v_bill_calc`.`end` AS `end`,`v_bill_calc`.`days` AS `days`,`v_bill_calc`.`fees` AS `fees`,`v_bill_calc`.`mwst` AS `mwst`,(`v_bill_calc`.`fees` + `v_bill_calc`.`mwst`) AS `total` from `v_bill_calc`;

-- --------------------------------------------------------

--
-- Structure for view `v_bill_calc`
--
DROP TABLE IF EXISTS `v_bill_calc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bill_calc` AS select `d`.`account_id` AS `account_id`,`d`.`account_name` AS `account_name`,`d`.`aum` AS `aum`,`d`.`fee_tp` AS `fee_tp`,`d`.`start` AS `start`,`d`.`end` AS `end`,(to_days(`d`.`end`) - to_days(`d`.`start`)) AS `days`,round((((`d`.`aum` * `d`.`fee_tp`) / 100) * ((to_days(`d`.`end`) - to_days(`d`.`start`)) / 360)),2) AS `fees`,round((0.08 * (((`d`.`aum` * `d`.`fee_tp`) / 100) * ((to_days(`d`.`end`) - to_days(`d`.`start`)) / 360))),2) AS `mwst` from `v_bill_data` `d`;

-- --------------------------------------------------------

--
-- Structure for view `v_bill_data`
--
DROP TABLE IF EXISTS `v_bill_data`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bill_data` AS select `a`.`account_id` AS `account_id`,`a`.`account_name` AS `account_name`,`v`.`val_number` AS `aum`,`a`.`fee_tp` AS `fee_tp`,if((`ps`.`date_parameter` > `a`.`start_fee`),`ps`.`date_parameter`,`a`.`start_fee`) AS `start`,`pe`.`date_parameter` AS `end` from (((`values` `v` join `accounts` `a`) join `parameters` `ps`) join `parameters` `pe`) where ((`v`.`value_type_id` = 2) and (`v`.`account_id` = `a`.`account_id`) and (`ps`.`parameter_type_id` = 2) and (`pe`.`parameter_type_id` = 1));

-- --------------------------------------------------------

--
-- Structure for view `v_contacts`
--
DROP TABLE IF EXISTS `v_contacts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contacts` AS select NULL AS `contact_id`,' not set' AS `contact_select`,' not set' AS `account_name`,NULL AS `contact_time` union select `contacts`.`contact_id` AS `contact_id`,concat_ws(' ','A',`accounts`.`account_name`,date_format(`contacts`.`start`,'%d.%m.%Y'),`contact_types`.`type_name`,`contacts`.`description`) AS `contact_select`,cast(`accounts`.`account_name` as unsigned) AS `account_name`,`contacts`.`start` AS `contact_time` from ((`contacts` join `accounts`) join `contact_types`) where ((`contacts`.`account_id` = `accounts`.`account_id`) and (`contacts`.`contact_type_id` = `contact_types`.`contact_type_id`)) order by cast(`account_name` as unsigned),`contact_time`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_categories`
--
DROP TABLE IF EXISTS `v_contact_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_categories` AS select NULL AS `contact_category_id`,' not set' AS `category_name` union select `contact_categories`.`contact_category_id` AS `contact_category_id`,`contact_categories`.`category_name` AS `category_name` from `contact_categories`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_member_types`
--
DROP TABLE IF EXISTS `v_contact_member_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_member_types` AS select NULL AS `contact_member_type_id`,' not set' AS `member_type` union select `contact_member_types`.`contact_member_type_id` AS `contact_member_type_id`,`contact_member_types`.`member_type` AS `member_type` from `contact_member_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_number_types`
--
DROP TABLE IF EXISTS `v_contact_number_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_number_types` AS select NULL AS `contact_number_type_id`,' not set' AS `type_name` union select `contact_number_types`.`contact_number_type_id` AS `contact_number_type_id`,`contact_number_types`.`type_name` AS `type_name` from `contact_number_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_report`
--
DROP TABLE IF EXISTS `v_contact_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_report` AS select `contact_member_types`.`member_type` AS `type`,`persons`.`lastname` AS `person` from ((`contact_members` join `persons`) join `contact_member_types`) where ((`contact_members`.`person_id` = `persons`.`person_id`) and (`contact_member_types`.`contact_member_type_id` = `contact_members`.`contact_member_type_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_contact_report_actions`
--
DROP TABLE IF EXISTS `v_contact_report_actions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_report_actions` AS select `actions`.`create_contact_id` AS `contact_id`,`actions`.`description` AS `description`,`actions`.`deadline` AS `deadline` from `actions`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_report_head`
--
DROP TABLE IF EXISTS `v_contact_report_head`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_report_head` AS select `contacts`.`contact_id` AS `contact_id`,`contacts`.`main_action` AS `headline`,`contacts`.`start` AS `datum`,`contacts`.`start` AS `zeit` from `contacts`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_report_members`
--
DROP TABLE IF EXISTS `v_contact_report_members`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_report_members` AS select `contact_members`.`contact_id` AS `contact_id`,`contact_member_types`.`member_type` AS `type`,`persons`.`lastname` AS `person` from ((`contact_members` join `persons`) join `contact_member_types`) where ((`contact_members`.`person_id` = `persons`.`person_id`) and (`contact_member_types`.`contact_member_type_id` = `contact_members`.`contact_member_type_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_contact_report_notes`
--
DROP TABLE IF EXISTS `v_contact_report_notes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_report_notes` AS select `contact_notes`.`contact_id` AS `contact_notes`,`contact_notes`.`description` AS `description` from `contact_notes`;

-- --------------------------------------------------------

--
-- Structure for view `v_contact_types`
--
DROP TABLE IF EXISTS `v_contact_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contact_types` AS select NULL AS `contact_type_id`,' not set' AS `type_name` union select `contact_types`.`contact_type_id` AS `contact_type_id`,`contact_types`.`type_name` AS `type_name` from `contact_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_contract_types`
--
DROP TABLE IF EXISTS `v_contract_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contract_types` AS select NULL AS `contract_type_id`,' not set' AS `type_name` union select `contract_types`.`contract_type_id` AS `contract_type_id`,`contract_types`.`type_name` AS `type_name` from `contract_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_countries`
--
DROP TABLE IF EXISTS `v_countries`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_countries` AS select NULL AS `country_id`,' not set' AS `name` union select `countries`.`country_id` AS `country_id`,`countries`.`name` AS `name` from `countries`;

-- --------------------------------------------------------

--
-- Structure for view `v_currencies`
--
DROP TABLE IF EXISTS `v_currencies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_currencies` AS select NULL AS `currency_id`,' not set' AS `symbol` union select `currencies`.`currency_id` AS `currency_id`,`currencies`.`symbol` AS `symbol` from `currencies`;

-- --------------------------------------------------------

--
-- Structure for view `v_currencies_2`
--
DROP TABLE IF EXISTS `v_currencies_2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_currencies_2` AS select NULL AS `currency_id`,' not set' AS `symbol` union select `currencies`.`currency_id` AS `currency_id`,`currencies`.`symbol` AS `symbol` from `currencies`;

-- --------------------------------------------------------

--
-- Structure for view `v_currency_pairs`
--
DROP TABLE IF EXISTS `v_currency_pairs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_currency_pairs` AS select NULL AS `currency_pair_id`,' not set' AS `select_name`,' not set' AS `sort_name` union select `currency_pairs`.`currency_pair_id` AS `currency_pair_id`,if((`currency_pairs`.`description` <> ''),`currency_pairs`.`description`,concat_ws('/',`v_currencies`.`symbol`,`v_currencies_2`.`symbol`)) AS `select_name`,concat_ws('/',`v_currencies`.`symbol`,`v_currencies_2`.`symbol`) AS `sort_name` from ((`currency_pairs` join `v_currencies`) join `v_currencies_2`) where ((`currency_pairs`.`currency1_id` = `v_currencies`.`currency_id`) and (`currency_pairs`.`currency2_id` = `v_currencies_2`.`currency_id`)) order by `sort_name`;

-- --------------------------------------------------------

--
-- Structure for view `v_currency_price_feed`
--
DROP TABLE IF EXISTS `v_currency_price_feed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_currency_price_feed` AS select `currency_pairs`.`currency_pair_id` AS `currency_pair_id`,`currency_pairs`.`currency1_id` AS `currency1_id`,`currency_pairs`.`currency2_id` AS `currency2_id`,`currency_pairs`.`factor` AS `factor`,`currency_pairs`.`description` AS `description`,`currency_pairs`.`fx_rate` AS `fx_rate`,`currency_pairs`.`decimals` AS `decimals`,`currency_pairs`.`symbol_market_map` AS `symbol_market_map`,`currency_pairs`.`symbol_yahoo` AS `symbol_yahoo`,`currency_pairs`.`comment` AS `comment`,`currency_pairs`.`price_feed_type_id` AS `price_feed_type_id` from `currency_pairs` where (`currency_pairs`.`price_feed_type_id` > 0);

-- --------------------------------------------------------

--
-- Structure for view `v_document_categories`
--
DROP TABLE IF EXISTS `v_document_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_document_categories` AS select NULL AS `document_category_id`,' not set' AS `category_name` union select `document_categories`.`document_category_id` AS `document_category_id`,`document_categories`.`category_name` AS `category_name` from `document_categories`;

-- --------------------------------------------------------

--
-- Structure for view `v_document_types`
--
DROP TABLE IF EXISTS `v_document_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_document_types` AS select NULL AS `document_type_id`,' not set' AS `type_name` union select `document_types`.`document_type_id` AS `document_type_id`,`document_types`.`type_name` AS `type_name` from `document_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_events_open`
--
DROP TABLE IF EXISTS `v_events_open`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_events_open` AS select `events`.`event_id` AS `event_id`,`events`.`event_type_id` AS `event_type_id`,`events`.`description` AS `description`,`events`.`account_id` AS `account_id` from `events`;

-- --------------------------------------------------------

--
-- Structure for view `v_event_stati`
--
DROP TABLE IF EXISTS `v_event_stati`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_event_stati` AS select NULL AS `event_status_id`,' not set' AS `status_text` union select `event_stati`.`event_status_id` AS `event_status_id`,`event_stati`.`status_text` AS `status_text` from `event_stati`;

-- --------------------------------------------------------

--
-- Structure for view `v_event_types`
--
DROP TABLE IF EXISTS `v_event_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_event_types` AS select NULL AS `event_type_id`,' not set' AS `type_name` union select `event_types`.`event_type_id` AS `event_type_id`,`event_types`.`type_name` AS `type_name` from `event_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_exposure_items`
--
DROP TABLE IF EXISTS `v_exposure_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_exposure_items` AS select NULL AS `exposure_item_id`,' not set' AS `description` union select `exposure_items`.`exposure_item_id` AS `exposure_item_id`,`exposure_items`.`description` AS `description` from `exposure_items`;

-- --------------------------------------------------------

--
-- Structure for view `v_exposure_target_for_portfolios`
--
DROP TABLE IF EXISTS `v_exposure_target_for_portfolios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_exposure_target_for_portfolios` AS select `exposure_targets`.`exposure_target_id` AS `exposure_target_id`,`exposure_items`.`exposure_item_id` AS `exposure_item_id`,`portfolios`.`portfolio_id` AS `portfolio_id`,`exposure_targets`.`target` AS `neutral` from (((`portfolios` join `accounts`) join `exposure_targets`) join `exposure_items`) where ((`portfolios`.`account_id` = `accounts`.`account_id`) and (`accounts`.`account_mandat_id` = `exposure_targets`.`account_mandat_id`) and (`exposure_targets`.`exposure_item_id` = `exposure_items`.`exposure_item_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_exposure_target_values`
--
DROP TABLE IF EXISTS `v_exposure_target_values`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_exposure_target_values` AS select `exposure_target_values`.`exposure_target_value_id` AS `exposure_target_value_id`,`exposure_target_values`.`exposure_target_id` AS `exposure_target_id`,`exposure_target_values`.`portfolio_id` AS `portfolio_id`,`portfolios`.`portfolio_name` AS `portfolio_name`,`exposure_items`.`description` AS `item_name`,round(`exposure_target_values`.`calc_value`,1) AS `calc_value`,round(`exposure_targets`.`optimized`,1) AS `optimized`,round((`exposure_targets`.`optimized` - `exposure_target_values`.`calc_value`),1) AS `diff`,round(`exposure_targets`.`target`,1) AS `neutral`,round(`exposure_targets`.`limit_up`,1) AS `limit_up`,round(`exposure_targets`.`limit_down`,1) AS `limit_down`,round(`v_exposure_target_with_exceptions`.`exception`,1) AS `exception`,round((if((`v_exposure_target_with_exceptions`.`exception` > 0),`v_exposure_target_with_exceptions`.`exception`,`exposure_targets`.`target`) - `exposure_target_values`.`calc_value`),1) AS `diff_neutral` from ((((`exposure_target_values` join `exposure_targets`) join `exposure_items`) join `portfolios`) join `v_exposure_target_with_exceptions`) where ((`exposure_target_values`.`exposure_target_id` = `exposure_targets`.`exposure_target_id`) and (`exposure_targets`.`exposure_item_id` = `exposure_items`.`exposure_item_id`) and (`exposure_target_values`.`portfolio_id` = `portfolios`.`portfolio_id`) and (`portfolios`.`portfolio_id` = `v_exposure_target_with_exceptions`.`portfolio_id`) and (`exposure_items`.`exposure_item_id` = `v_exposure_target_with_exceptions`.`exposure_item_id`)) group by `exposure_target_values`.`exposure_target_value_id`,`exposure_target_values`.`portfolio_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_exposure_target_with_exceptions`
--
DROP TABLE IF EXISTS `v_exposure_target_with_exceptions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_exposure_target_with_exceptions` AS select `v_exposure_target_for_portfolios`.`exposure_target_id` AS `exposure_target_id`,`v_exposure_target_for_portfolios`.`exposure_item_id` AS `exposure_item_id`,`v_exposure_target_for_portfolios`.`portfolio_id` AS `portfolio_id`,`v_exposure_target_for_portfolios`.`neutral` AS `neutral`,`exposure_exceptions`.`target` AS `exception` from (`v_exposure_target_for_portfolios` left join `exposure_exceptions` on(((`v_exposure_target_for_portfolios`.`exposure_item_id` = `exposure_exceptions`.`exposure_item_id`) and (`v_exposure_target_for_portfolios`.`portfolio_id` = `exposure_exceptions`.`portfolio_id`))));

-- --------------------------------------------------------

--
-- Structure for view `v_exposure_types`
--
DROP TABLE IF EXISTS `v_exposure_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_exposure_types` AS select NULL AS `exposure_type_id`,' not set' AS `type_name` union select `exposure_types`.`exposure_type_id` AS `exposure_type_id`,`exposure_types`.`type_name` AS `type_name` from `exposure_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_log_users`
--
DROP TABLE IF EXISTS `v_log_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_users` AS select NULL AS `user_id`,' not set' AS `username` union select `log_users`.`user_id` AS `user_id`,`log_users`.`username` AS `username` from `log_users`;

-- --------------------------------------------------------

--
-- Structure for view `v_log_user_groups`
--
DROP TABLE IF EXISTS `v_log_user_groups`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_user_groups` AS select NULL AS `user_group_id`,' not set' AS `group_name` union select `log_user_groups`.`user_group_id` AS `user_group_id`,`log_user_groups`.`group_name` AS `group_name` from `log_user_groups`;

-- --------------------------------------------------------

--
-- Structure for view `v_log_user_rights`
--
DROP TABLE IF EXISTS `v_log_user_rights`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_user_rights` AS select NULL AS `user_right_id`,' not set' AS `right_name` union select `log_user_rights`.`user_right_id` AS `user_right_id`,`log_user_rights`.`right_name` AS `right_name` from `log_user_rights`;

-- --------------------------------------------------------

--
-- Structure for view `v_log_user_types`
--
DROP TABLE IF EXISTS `v_log_user_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_user_types` AS select NULL AS `user_type_id`,' not set' AS `type_name` union select `log_user_types`.`user_type_id` AS `user_type_id`,`log_user_types`.`type_name` AS `type_name` from `log_user_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_message_types`
--
DROP TABLE IF EXISTS `v_message_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_message_types` AS select NULL AS `message_type_id`,' not set' AS `type_name` union select `message_types`.`message_type_id` AS `message_type_id`,`message_types`.`type_name` AS `type_name` from `message_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_persons`
--
DROP TABLE IF EXISTS `v_persons`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_persons` AS select NULL AS `person_id`,' not set' AS `select_name`,' not set' AS `display_name`,'' AS `contact_number`,NULL AS `person_type_id`,NULL AS `internal` union select `persons`.`person_id` AS `person_id`,if((`persons`.`display_name` <> ''),`persons`.`display_name`,concat_ws(' ',`persons`.`firstname`,`persons`.`lastname`)) AS `select_name`,if((`persons`.`display_name` <> ''),`persons`.`display_name`,concat_ws(' ',`persons`.`title`,`persons`.`lastname`)) AS `display_name`,`contact_numbers`.`contact_number` AS `contact_number`,`persons`.`person_type_id` AS `person_type_id`,`person_types`.`internal` AS `internal` from ((`persons` left join `person_types` on((`persons`.`person_type_id` = `person_types`.`person_type_id`))) left join `contact_numbers` on(((`persons`.`person_id` = `contact_numbers`.`person_id`) and (`contact_numbers`.`contact_number_type_id` = 3)))) order by cast(`select_name` as unsigned);

-- --------------------------------------------------------

--
-- Structure for view `v_person_types`
--
DROP TABLE IF EXISTS `v_person_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_person_types` AS select NULL AS `person_type_id`,' not set' AS `description` union select `person_types`.`person_type_id` AS `person_type_id`,`person_types`.`description` AS `description` from `person_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_portfolios`
--
DROP TABLE IF EXISTS `v_portfolios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolios` AS select NULL AS `portfolio_id`,' not set' AS `portfolio_select_name`,' not set' AS `account_name` union select `portfolios`.`portfolio_id` AS `portfolio_id`,concat_ws(' ',`portfolios`.`portfolio_name`,`banks`.`bank_name`,`currencies`.`symbol`) AS `portfolio_select_name`,cast(`accounts`.`account_name` as unsigned) AS `account_name` from (((`portfolios` join `accounts`) join `currencies`) join `banks`) where ((`portfolios`.`account_id` = `accounts`.`account_id`) and (`portfolios`.`currency_id` = `currencies`.`currency_id`) and (`portfolios`.`bank_id` = `banks`.`bank_id`)) order by cast(`account_name` as unsigned);

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_allocation`
--
DROP TABLE IF EXISTS `v_portfolio_allocation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_allocation` AS select `v_portfolio_pos`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos`.`security_id` AS `security_id`,`v_portfolio_pos`.`security_name` AS `security_name`,`v_portfolio_pos`.`asset_class` AS `asset_class`,`v_portfolio_pos`.`position` AS `position`,`v_portfolio_pos`.`trade_curr` AS `trade_curr`,`v_portfolio_pos`.`sec_curr` AS `sec_curr`,`v_portfolio_pos`.`decimals` AS `decimals`,`v_portfolio_pos`.`ref_decimals` AS `ref_decimals`,`v_portfolio_pos`.`bid` AS `bid`,`v_portfolio_pos`.`ask` AS `ask`,`v_portfolio_pos`.`last` AS `last`,`v_portfolio_pos`.`open_value` AS `open_value`,`v_portfolio_pos`.`buy_price` AS `buy_price`,`v_portfolio_pos`.`pos_value` AS `pos_value`,`v_portfolio_pos`.`pos_value_ref` AS `pos_value_ref`,`v_portfolio_pos`.`pnl` AS `pnl`,`v_portfolio_pos`.`pnl_last` AS `pnl_last`,`v_portfolio_pos`.`pnl_ref` AS `pnl_ref`,`v_portfolio_pos`.`pnl_ref_open` AS `pnl_ref_open`,`v_portfolio_pos`.`pnl_pct` AS `pnl_pct`,`v_portfolio_pos`.`pnl_market` AS `pnl_market`,`v_portfolio_pos`.`pnl_fx` AS `pnl_fx`,`v_portfolio_pnl`.`aum` AS `aum`,round(((`v_portfolio_pos`.`pos_value_ref` / `v_portfolio_pnl`.`aum`) * 100),1) AS `aum_pct` from (`v_portfolio_pos` join `v_portfolio_pnl`) where (`v_portfolio_pos`.`portfolio_id` = `v_portfolio_pnl`.`portfolio_id`);

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_persons`
--
DROP TABLE IF EXISTS `v_portfolio_persons`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_persons` AS select `portfolios`.`portfolio_id` AS `portfolio_id`,`accounts`.`account_id` AS `account_id`,`accounts`.`account_name` AS `account_name`,`account_person_types`.`type_name` AS `portfolio_function`,`account_person_types`.`code_id` AS `portfolio_function_code_id`,`v_persons`.`select_name` AS `person_name`,`contact_numbers`.`contact_number` AS `contact_number` from (((((`portfolios` join `accounts`) join `account_persons`) join `account_person_types`) join `v_persons`) join `contact_numbers`) where ((`portfolios`.`account_id` = `accounts`.`account_id`) and (`accounts`.`account_id` = `account_persons`.`account_id`) and (`account_persons`.`account_person_type_id` = `account_person_types`.`account_person_type_id`) and (`account_persons`.`person_id` = `v_persons`.`person_id`) and (`v_persons`.`person_id` = `contact_numbers`.`person_id`) and (`contact_numbers`.`contact_number_type_id` = 3));

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pnl`
--
DROP TABLE IF EXISTS `v_portfolio_pnl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pnl` AS select `v_trade_pnl`.`portfolio_id` AS `portfolio_id`,round(sum(`v_trade_pnl`.`pos_value_ref`),`v_trade_pnl`.`ref_decimals`) AS `aum`,round(sum(`v_trade_pnl`.`pnl_ref`),`v_trade_pnl`.`ref_decimals`) AS `pnl`,min(`v_trade_pnl`.`update_time`) AS `update_time` from `v_trade_pnl` group by `v_trade_pnl`.`portfolio_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos`
--
DROP TABLE IF EXISTS `v_portfolio_pos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos` AS select `v_portfolio_pos_calc`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos_calc`.`security_id` AS `security_id`,`v_portfolio_pos_calc`.`security_name` AS `security_name`,`v_portfolio_pos_calc`.`currency_id` AS `currency_id`,`v_portfolio_pos_calc`.`asset_class` AS `asset_class`,`v_portfolio_pos_calc`.`position` AS `position`,`v_portfolio_pos_calc`.`trade_curr` AS `trade_curr`,`v_portfolio_pos_calc`.`sec_curr` AS `sec_curr`,`v_portfolio_pos_calc`.`security_issuer_id` AS `security_issuer_id`,`v_portfolio_pos_calc`.`decimals` AS `decimals`,`v_portfolio_pos_calc`.`ref_decimals` AS `ref_decimals`,`v_portfolio_pos_get_prices`.`bid` AS `bid`,`v_portfolio_pos_get_prices`.`ask` AS `ask`,`v_portfolio_pos_get_prices`.`last_price` AS `last`,round(`v_portfolio_pos_calc`.`calc_premium_ref_open`,`v_portfolio_pos_calc`.`ref_decimals`) AS `open_value`,round(((`v_portfolio_pos_calc`.`calc_premium` / `v_portfolio_pos_calc`.`position`) * -(1)),`v_portfolio_pos_calc`.`ref_decimals`) AS `buy_price`,round(`v_portfolio_pos_calc`.`pos_value`,`v_portfolio_pos_calc`.`decimals`) AS `pos_value`,round(`v_portfolio_pos_calc`.`pos_value_ref`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pos_value_ref`,round(`v_portfolio_pos_calc`.`pnl`,`v_portfolio_pos_calc`.`decimals`) AS `pnl`,round(`v_portfolio_pos_calc`.`pnl_last`,`v_portfolio_pos_calc`.`decimals`) AS `pnl_last`,round(`v_portfolio_pos_calc`.`pnl_ref`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_ref`,round(`v_portfolio_pos_calc`.`pnl_ref_open`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_ref_open`,round(((`v_portfolio_pos_calc`.`pnl_ref_open` / `v_portfolio_pos_calc`.`calc_premium_ref_open`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_pct`,round(((`v_portfolio_pos_calc`.`pnl_ref` / `v_portfolio_pos_calc`.`calc_premium_ref`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_market`,round((((`v_portfolio_pos_calc`.`pnl_ref_open` - `v_portfolio_pos_calc`.`pnl_ref`) / `v_portfolio_pos_calc`.`calc_premium_ref_open`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_fx` from (`v_portfolio_pos_calc` join `v_portfolio_pos_get_prices`) where ((`v_portfolio_pos_calc`.`position` <> 0) and (`v_portfolio_pos_calc`.`portfolio_id` = `v_portfolio_pos_get_prices`.`portfolio_id`) and (`v_portfolio_pos_calc`.`security_id` = `v_portfolio_pos_get_prices`.`security_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_named`
--
DROP TABLE IF EXISTS `v_portfolio_pos_named`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_named` AS 
select 
  `v_portfolios`.`portfolio_select_name`    AS `portfolio`,
  `v_portfolio_pos`.`position`              AS `position`,
  `v_portfolio_pos`.`decimals`              AS `decimals`,
  `v_portfolio_pos`.`ref_decimals`          AS `ref_decimals`,
  `v_portfolio_pos`.`security_name`         AS `security`,
  `v_portfolio_pos`.`portfolio_id`          AS `portfolio_id`,
  `v_portfolio_pos`.`security_id`           AS `security_id`,
  `v_portfolio_pos`.`currency_id`           AS `currency_id`,
  `v_portfolio_pos`.`asset_class`           AS `asset_class`,
  `v_portfolio_pos`.`trade_curr`            AS `trade_curr`,
  `v_portfolio_pos`.`sec_curr`              AS `sec_curr`,
  `v_portfolio_pos`.`security_issuer_id`    AS `security_issuer_id`, 
  `v_portfolio_pos`.`bid`                   AS `bid`,
  `v_portfolio_pos`.`ask`                   AS `ask`,
  `v_portfolio_pos`.`last`                  AS `last`,
  `v_portfolio_pos`.`open_value`            AS `open_value`,
  `v_portfolio_pos`.`buy_price`             AS `buy_price`,
  `v_portfolio_pos`.`pos_value`             AS `pos_value`,
  `v_portfolio_pos`.`pos_value_ref`         AS `pos_value_ref`,
  `v_portfolio_pos`.`pnl`                   AS `pnl`,
  `v_portfolio_pos`.`pnl_last`              AS `pnl_last`,
  `v_portfolio_pos`.`pnl_ref`               AS `pnl_ref`, 
  `v_portfolio_pos`.`pnl_ref_open`          AS `pnl_ref_open`, 
  `v_portfolio_pos`.`pnl_pct`               AS `pnl_pct`,
  `v_portfolio_pos`.`pnl_market`            AS `pnl_market`,
  `v_portfolio_pos`.`pnl_fx`                AS `pnl_fx` 
from 
  `v_portfolio_pos`, `v_portfolios`
where 
  `v_portfolio_pos`.`portfolio_id` = `v_portfolios`.`portfolio_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_calc`
--
DROP TABLE IF EXISTS `v_portfolio_pos_calc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_calc` AS select `v_trade_pnl`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl`.`security_id` AS `security_id`,`v_trade_pnl`.`currency_id` AS `currency_id`,`v_trade_pnl`.`security_name` AS `security_name`,`v_trade_pnl`.`asset_class` AS `asset_class`,`v_trade_pnl`.`sec_curr` AS `sec_curr`,`v_trade_pnl`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl`.`trade_curr` AS `trade_curr`,`v_trade_pnl`.`decimals` AS `decimals`,`v_trade_pnl`.`ref_decimals` AS `ref_decimals`,sum(`v_trade_pnl`.`size`) AS `position`,avg(`v_trade_pnl`.`bid`) AS `bid`,avg(`v_trade_pnl`.`ask`) AS `ask`,avg(`v_trade_pnl`.`last_price`) AS `last`,sum(`v_trade_pnl`.`calc_premium`) AS `calc_premium`,sum(`v_trade_pnl`.`calc_premium_ref`) AS `calc_premium_ref`,sum(`v_trade_pnl`.`calc_premium_ref_open`) AS `calc_premium_ref_open`,sum(`v_trade_pnl`.`pos_value`) AS `pos_value`,sum(`v_trade_pnl`.`pos_value_ref`) AS `pos_value_ref`,sum(`v_trade_pnl`.`pnl`) AS `pnl`,sum(`v_trade_pnl`.`pnl_last`) AS `pnl_last`,sum(`v_trade_pnl`.`pnl_ref`) AS `pnl_ref`,sum(`v_trade_pnl`.`pnl_ref_open`) AS `pnl_ref_open`,sum(`v_trade_pnl`.`pnl_fx`) AS `pnl_fx` from `v_trade_pnl` group by `v_trade_pnl`.`portfolio_id`,`v_trade_pnl`.`security_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_closed`
--
DROP TABLE IF EXISTS `v_portfolio_pos_closed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_closed` AS select `v_portfolio_pos_calc`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos_calc`.`security_id` AS `security_id`,`v_portfolio_pos_calc`.`security_name` AS `security_name`,`v_portfolio_pos_calc`.`currency_id` AS `currency_id`,`v_portfolio_pos_calc`.`asset_class` AS `asset_class`,`v_portfolio_pos_calc`.`position` AS `position`,`v_portfolio_pos_calc`.`trade_curr` AS `trade_curr`,`v_portfolio_pos_calc`.`sec_curr` AS `sec_curr`,`v_portfolio_pos_calc`.`decimals` AS `decimals`,`v_portfolio_pos_calc`.`ref_decimals` AS `ref_decimals`,round(`v_trade_premium_long_short`.`size_long`,`v_portfolio_pos_calc`.`decimals`) AS `size_long`,round(`v_trade_premium_long_short`.`premium_long`,`v_portfolio_pos_calc`.`decimals`) AS `premium_long`,round(((`v_trade_premium_long_short`.`premium_long` / `v_trade_premium_long_short`.`size_long`) * -(1)),`v_portfolio_pos_calc`.`decimals`) AS `avg_buy`,round(`v_trade_premium_long_short`.`size_short`,`v_portfolio_pos_calc`.`decimals`) AS `size_short`,round(`v_trade_premium_long_short`.`premium_short`,`v_portfolio_pos_calc`.`decimals`) AS `premium_short`,round(((`v_trade_premium_long_short`.`premium_short` / `v_trade_premium_long_short`.`size_short`) * -(1)),`v_portfolio_pos_calc`.`decimals`) AS `avg_sell`,round((`v_trade_premium_long_short`.`premium_short` + `v_trade_premium_long_short`.`premium_long`),`v_portfolio_pos_calc`.`decimals`) AS `pnl_total`,round(`v_portfolio_pos_calc`.`bid`,`v_portfolio_pos_calc`.`decimals`) AS `bid`,round(`v_portfolio_pos_calc`.`ask`,`v_portfolio_pos_calc`.`decimals`) AS `ask`,round(`v_portfolio_pos_calc`.`last`,`v_portfolio_pos_calc`.`decimals`) AS `last`,round(`v_portfolio_pos_calc`.`calc_premium_ref_open`,`v_portfolio_pos_calc`.`ref_decimals`) AS `open_value`,round(((`v_portfolio_pos_calc`.`calc_premium` / `v_portfolio_pos_calc`.`position`) * -(1)),`v_portfolio_pos_calc`.`ref_decimals`) AS `buy_price`,round(`v_portfolio_pos_calc`.`pos_value`,`v_portfolio_pos_calc`.`decimals`) AS `pos_value`,round(`v_portfolio_pos_calc`.`pos_value_ref`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pos_value_ref`,round(`v_portfolio_pos_calc`.`pnl`,`v_portfolio_pos_calc`.`decimals`) AS `pnl`,round(`v_portfolio_pos_calc`.`pnl_last`,`v_portfolio_pos_calc`.`decimals`) AS `pnl_last`,round(`v_portfolio_pos_calc`.`pnl_ref`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_ref`,round(`v_portfolio_pos_calc`.`pnl_ref_open`,`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_ref_open`,round(((`v_portfolio_pos_calc`.`pnl_ref_open` / `v_portfolio_pos_calc`.`calc_premium_ref_open`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_pct`,round(((`v_portfolio_pos_calc`.`pnl_ref` / `v_portfolio_pos_calc`.`calc_premium_ref`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_market`,round((((`v_portfolio_pos_calc`.`pnl_ref_open` - `v_portfolio_pos_calc`.`pnl_ref`) / `v_portfolio_pos_calc`.`calc_premium_ref_open`) * -(100)),`v_portfolio_pos_calc`.`ref_decimals`) AS `pnl_fx` from (`v_portfolio_pos_calc` join `v_trade_premium_long_short`) where ((`v_portfolio_pos_calc`.`position` = 0) and (`v_portfolio_pos_calc`.`portfolio_id` = `v_trade_premium_long_short`.`portfolio_id`) and (`v_portfolio_pos_calc`.`security_id` = `v_trade_premium_long_short`.`security_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_derivate`
--
DROP TABLE IF EXISTS `v_portfolio_pos_derivate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_derivate` AS select `v_portfolio_pos`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos`.`security_id` AS `security_id`,`v_securities`.`security_type_id` AS `security_type_id`,`v_portfolio_pos`.`security_name` AS `security_name`,`v_portfolio_pos`.`currency_id` AS `currency_id`,`v_portfolio_pos`.`asset_class` AS `asset_class`,`v_portfolio_pos`.`position` AS `position`,`v_portfolio_pos`.`trade_curr` AS `trade_curr`,`v_portfolio_pos`.`sec_curr` AS `sec_curr`,`v_portfolio_pos`.`security_issuer_id` AS `security_issuer_id`,`v_portfolio_pos`.`decimals` AS `decimals`,`v_portfolio_pos`.`ref_decimals` AS `ref_decimals`,`v_portfolio_pos`.`bid` AS `bid`,`v_portfolio_pos`.`ask` AS `ask`,`v_portfolio_pos`.`last` AS `last`,`v_portfolio_pos`.`open_value` AS `open_value`,`v_portfolio_pos`.`buy_price` AS `buy_price`,`v_portfolio_pos`.`pos_value` AS `pos_value`,`v_portfolio_pos`.`pos_value_ref` AS `pos_value_ref`,`v_portfolio_pos`.`pnl` AS `pnl`,`v_portfolio_pos`.`pnl_last` AS `pnl_last`,`v_portfolio_pos`.`pnl_ref` AS `pnl_ref`,`v_portfolio_pos`.`pnl_ref_open` AS `pnl_ref_open`,`v_portfolio_pos`.`pnl_pct` AS `pnl_pct`,`v_portfolio_pos`.`pnl_market` AS `pnl_market`,`v_portfolio_pos`.`pnl_fx` AS `pnl_fx` from (`v_portfolio_pos` join `v_securities`) where ((`v_portfolio_pos`.`security_id` = `v_securities`.`security_id`) and ((`v_securities`.`security_type_id` = 5) or (`v_securities`.`security_type_id` = 6) or (`v_securities`.`security_type_id` = 18)));

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_get`
--
DROP TABLE IF EXISTS `v_portfolio_pos_get`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_get` AS select `trades`.`portfolio_id` AS `portfolio_id`,`trades`.`security_id` AS `security_id`,`trades`.`currency_id` AS `currency_id`,sum(`trades`.`size`) AS `position` from `trades` group by `trades`.`portfolio_id`,`trades`.`security_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_pos_get_prices`
--
DROP TABLE IF EXISTS `v_portfolio_pos_get_prices`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_pos_get_prices` AS select `v_portfolio_pos_get`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos_get`.`security_id` AS `security_id`,`v_portfolio_pos_get`.`currency_id` AS `currency_id`,`v_portfolio_pos_get`.`position` AS `position`,`securities`.`bid` AS `bid`,`securities`.`ask` AS `ask`,`securities`.`last_price` AS `last_price`,if(isnull(`securities`.`update_time`),str_to_date('2014-01-01 11:30:10','%Y-%m-%d %h:%i:%s'),`securities`.`update_time`) AS `update_time`,if((`v_portfolio_pos_get`.`position` > 0),if((`securities`.`bid` > 0),`securities`.`bid`,`securities`.`last_price`),if((`securities`.`ask` > 0),`securities`.`ask`,`securities`.`last_price`)) AS `used_price` from (`v_portfolio_pos_get` join `securities`) where (`v_portfolio_pos_get`.`security_id` = `securities`.`security_id`);

-- --------------------------------------------------------

--
-- Structure for view `v_portfolio_types`
--
DROP TABLE IF EXISTS `v_portfolio_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_portfolio_types` AS select NULL AS `portfolio_type_id`,' not set' AS `type_name` union select `portfolio_types`.`portfolio_type_id` AS `portfolio_type_id`,`portfolio_types`.`type_name` AS `type_name` from `portfolio_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_position_pnl`
--
DROP TABLE IF EXISTS `v_position_pnl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_position_pnl` AS select `v_trade_pnl`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl`.`security_id` AS `security_id`,`v_trade_pnl`.`security_name` AS `security_name`,`v_trade_pnl`.`currency_id` AS `currency_id`,max(`v_trade_pnl`.`trade_date`) AS `trade_date`,max(`v_trade_pnl`.`price`) AS `price`,max(`v_trade_pnl`.`price_ref`) AS `price_ref`,max(`v_trade_pnl`.`price_ref_open`) AS `price_ref_open`,sum(`v_trade_pnl`.`size`) AS `size`,max(`v_trade_pnl`.`security_quote_type_id`) AS `security_quote_type_id`,max(`v_trade_pnl`.`quantity_factor`) AS `quantity_factor`,sum(`v_trade_pnl`.`calc_premium`) AS `calc_premium`,sum(`v_trade_pnl`.`calc_premium_ref`) AS `calc_premium_ref`,sum(`v_trade_pnl`.`calc_premium_ref_open`) AS `calc_premium_ref_open`,sum(`v_trade_pnl`.`premium`) AS `premium`,max(`v_trade_pnl`.`bid`) AS `bid`,max(`v_trade_pnl`.`ask`) AS `ask`,max(`v_trade_pnl`.`last_price`) AS `last_price`,max(`v_trade_pnl`.`sell_price`) AS `sell_price`,if((sum(`v_trade_pnl`.`size`) = 0),now(),min(`v_trade_pnl`.`update_time`)) AS `update_time`,sum(`v_trade_pnl`.`pos_value`) AS `pos_value`,sum(`v_trade_pnl`.`pos_value_ref`) AS `pos_value_ref`,sum(`v_trade_pnl`.`pos_value_ref_open`) AS `pos_value_ref_open`,sum(`v_trade_pnl`.`pos_value_last`) AS `pos_value_last`,sum(`v_trade_pnl`.`pos_value_last_ref`) AS `pos_value_last_ref`,sum(`v_trade_pnl`.`pos_value_last_ref_open`) AS `pos_value_last_ref_open`,sum(`v_trade_pnl`.`pnl`) AS `pnl`,sum(`v_trade_pnl`.`pnl_ref`) AS `pnl_ref`,sum(`v_trade_pnl`.`pnl_ref_open`) AS `pnl_ref_open`,sum(`v_trade_pnl`.`pnl_last`) AS `pnl_last`,max(`v_trade_pnl`.`decimals`) AS `decimals` from `v_trade_pnl` group by `v_trade_pnl`.`portfolio_id`,`v_trade_pnl`.`security_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_recon_files`
--
DROP TABLE IF EXISTS `v_recon_files`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recon_files` AS select NULL AS `recon_file_id`,' not set' AS `file_name` union select `recon_files`.`recon_file_id` AS `recon_file_id`,`recon_files`.`file_name` AS `file_name` from `recon_files`;

-- --------------------------------------------------------

--
-- Structure for view `v_recon_file_types`
--
DROP TABLE IF EXISTS `v_recon_file_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recon_file_types` AS select NULL AS `recon_file_type_id`,' not set' AS `type_name` union select `recon_file_types`.`recon_file_type_id` AS `recon_file_type_id`,`recon_file_types`.`type_name` AS `type_name` from `recon_file_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_recon_step_select`
--
DROP TABLE IF EXISTS `v_recon_step_select`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recon_step_select` AS select NULL AS `recon_step_id`,' not set' AS `recon_select_name`,' not set' AS `file_name`,0 AS `order_nbr` union select `recon_steps`.`recon_step_id` AS `recon_step_id`,concat_ws(' ',`recon_files`.`file_name`,`recon_steps`.`order_nbr`,'(',`recon_steps`.`source_field_name`,')') AS `recon_select_name`,`recon_files`.`file_name` AS `file_name`,`recon_steps`.`order_nbr` AS `order_nbr` from (`recon_steps` join `recon_files`) where (`recon_steps`.`recon_file_id` = `recon_files`.`recon_file_id`) order by `file_name`,`order_nbr`;

-- --------------------------------------------------------

--
-- Structure for view `v_recon_step_types`
--
DROP TABLE IF EXISTS `v_recon_step_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recon_step_types` AS select NULL AS `recon_step_type_id`,' not set' AS `type_name` union select `recon_step_types`.`recon_step_type_id` AS `recon_step_type_id`,`recon_step_types`.`type_name` AS `type_name` from `recon_step_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_recon_value_types`
--
DROP TABLE IF EXISTS `v_recon_value_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recon_value_types` AS select NULL AS `recon_value_type_id`,' not set' AS `type_name` union select `recon_value_types`.`recon_value_type_id` AS `recon_value_type_id`,`recon_value_types`.`type_name` AS `type_name` from `recon_value_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_securities`
--
DROP TABLE IF EXISTS `v_securities`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_securities` AS select NULL AS `security_id`,' not set' AS `select_name`,' not set' AS `name`,'' AS `ISIN`,NULL AS `last_price`,NULL AS `bid`,NULL AS `ask`,NULL AS `currency_id`,'' AS `curr`,NULL AS `security_type_id`,NULL AS `currency_pair_id`,NULL AS `bsi_id`,NULL AS `symbol_market_map`,NULL AS `price_feed_type_id`,'' AS `valor`,NULL AS `security_quote_type_id`,NULL AS `security_issuer_id`,'' AS `type`,NULL AS `quantity_factor`,'' AS `quote_type` union select `securities`.`security_id` AS `security_id`,concat_ws(' - ',`securities`.`name`,`securities`.`ISIN`) AS `select_name`,`securities`.`name` AS `name`,`securities`.`ISIN` AS `ISIN`,`securities`.`last_price` AS `last_price`,`securities`.`bid` AS `bid`,`securities`.`ask` AS `ask`,`securities`.`currency_id` AS `currency_id`,`currencies`.`symbol` AS `curr`,`securities`.`security_type_id` AS `security_type_id`,`securities`.`currency_pair_id` AS `currency_pair_id`,`securities`.`bsi_id` AS `bsi_id`,`securities`.`symbol_market_map` AS `symbol_market_map`,`securities`.`price_feed_type_id` AS `price_feed_type_id`,`securities`.`valor` AS `valor`,`securities`.`security_quote_type_id` AS `security_quote_type_id`,`securities`.`security_issuer_id` AS `security_issuer_id`,`v_security_types`.`description` AS `type`,if((`security_quote_types`.`security_quote_type_id` > 0),`security_quote_types`.`quantity_factor`,`v_security_types`.`quantity_factor`) AS `quantity_factor`,if((`security_quote_types`.`security_quote_type_id` > 0),`security_quote_types`.`type_name`,`v_security_types`.`quote_type`) AS `quote_type` from (((`securities` left join `currencies` on((`securities`.`currency_id` = `currencies`.`currency_id`))) left join `security_quote_types` on((`securities`.`security_quote_type_id` = `security_quote_types`.`security_quote_type_id`))) left join `v_security_types` on((`securities`.`security_type_id` = `v_security_types`.`security_type_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_securities_mm_symbol_request`
--
DROP TABLE IF EXISTS `v_securities_mm_symbol_request`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_securities_mm_symbol_request` AS select `securities`.`ISIN` AS `ISIN` from `securities` where ((`securities`.`ISIN` <> '') and (`securities`.`ISIN` is not null) and ((`securities`.`symbol_market_map` = '') or (`securities`.`last_price` = 0) or isnull(`securities`.`last_price`)));

-- --------------------------------------------------------

--
-- Structure for view `v_security_amount_types`
--
DROP TABLE IF EXISTS `v_security_amount_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_amount_types` AS select NULL AS `security_amount_type_id`,' not set' AS `type_name` union select `security_amount_types`.`security_amount_type_id` AS `security_amount_type_id`,`security_amount_types`.`type_name` AS `type_name` from `security_amount_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_exposure_stati`
--
DROP TABLE IF EXISTS `v_security_exposure_stati`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_exposure_stati` AS select NULL AS `security_exposure_status_id`,' not set' AS `status_text` union select `security_exposure_stati`.`security_exposure_status_id` AS `security_exposure_status_id`,`security_exposure_stati`.`status_text` AS `status_text` from `security_exposure_stati`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_fields`
--
DROP TABLE IF EXISTS `v_security_fields`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_fields` AS select NULL AS `security_field_id`,' not set' AS `description` union select `security_fields`.`security_field_id` AS `security_field_id`,`security_fields`.`description` AS `description` from `security_fields`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_field_source_types`
--
DROP TABLE IF EXISTS `v_security_field_source_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_field_source_types` AS select NULL AS `security_field_source_type_id`,' not set' AS `type_name` union select `security_field_source_types`.`security_field_source_type_id` AS `security_field_source_type_id`,`security_field_source_types`.`type_name` AS `type_name` from `security_field_source_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_issuers`
--
DROP TABLE IF EXISTS `v_security_issuers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_issuers` AS select NULL AS `security_issuer_id`,' not set' AS `issuer_name` union select `security_issuers`.`security_issuer_id` AS `security_issuer_id`,`security_issuers`.`issuer_name` AS `issuer_name` from `security_issuers`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_payment_types`
--
DROP TABLE IF EXISTS `v_security_payment_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_payment_types` AS select NULL AS `security_payment_type_id`,' not set' AS `type_name` union select `security_payment_types`.`security_payment_type_id` AS `security_payment_type_id`,`security_payment_types`.`type_name` AS `type_name` from `security_payment_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_pnl`
--
DROP TABLE IF EXISTS `v_security_pnl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_pnl` AS select `v_trade_pnl`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl`.`security_id` AS `security_id`,`v_trade_pnl`.`currency_id` AS `currency_id`,`v_trade_pnl`.`security_name` AS `security_name`,`v_trade_pnl`.`asset_class` AS `asset_class`,`v_trade_pnl`.`sec_curr` AS `sec_curr`,`v_trade_pnl`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl`.`trade_curr` AS `trade_curr`,`v_trade_pnl`.`decimals` AS `decimals`,`v_trade_pnl`.`ref_decimals` AS `ref_decimals`,sum(`v_trade_pnl`.`size`) AS `position`,avg(`v_trade_pnl`.`bid`) AS `bid`,avg(`v_trade_pnl`.`ask`) AS `ask`,avg(`v_trade_pnl`.`last_price`) AS `last`,sum(`v_trade_pnl`.`calc_premium`) AS `calc_premium`,sum(`v_trade_pnl`.`calc_premium_ref`) AS `calc_premium_ref`,sum(`v_trade_pnl`.`calc_premium_ref_open`) AS `calc_premium_ref_open`,sum(`v_trade_pnl`.`pos_value`) AS `pos_value`,sum(`v_trade_pnl`.`pos_value_ref`) AS `pos_value_ref`,sum(`v_trade_pnl`.`pnl`) AS `pnl`,sum(`v_trade_pnl`.`pnl_last`) AS `pnl_last`,sum(`v_trade_pnl`.`pnl_ref`) AS `pnl_ref`,sum(`v_trade_pnl`.`pnl_ref_open`) AS `pnl_ref_open`,sum(`v_trade_pnl`.`pnl_fx`) AS `pnl_fx` from `v_trade_pnl` group by `v_trade_pnl`.`security_id`;


-- --------------------------------------------------------

--
-- Structure for view `v_security_price_feed`
--
DROP TABLE IF EXISTS `v_security_price_feed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_price_feed` AS select `securities`.`security_id` AS `security_id`,`securities`.`name` AS `name`,`securities`.`ISIN` AS `ISIN`,`securities`.`last_price` AS `last_price`,`securities`.`currency_id` AS `currency_id`,`securities`.`security_type_id` AS `security_type_id`,`securities`.`currency_pair_id` AS `currency_pair_id`,`securities`.`bsi_id` AS `bsi_id`,`securities`.`symbol_market_map` AS `symbol_market_map`,`securities`.`price_feed_type_id` AS `price_feed_type_id` from `securities`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_price_feed_types`
--
DROP TABLE IF EXISTS `v_security_price_feed_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_price_feed_types` AS select NULL AS `feed_type_id`,' not set' AS `type_name` union select `security_price_feed_types`.`feed_type_id` AS `feed_type_id`,`security_price_feed_types`.`type_name` AS `type_name` from `security_price_feed_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_quote_types`
--
DROP TABLE IF EXISTS `v_security_quote_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_quote_types` AS select NULL AS `security_quote_type_id`,' not set' AS `type_name` union select `security_quote_types`.`security_quote_type_id` AS `security_quote_type_id`,`security_quote_types`.`type_name` AS `type_name` from `security_quote_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_triggers_open`
--
DROP TABLE IF EXISTS `v_security_triggers_open`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_triggers_open` AS select `security_triggers`.`security_trigger_id` AS `security_trigger_id`,`security_triggers`.`trigger_type_id` AS `trigger_type_id`,`security_trigger_types`.`type_name` AS `type_name`,`security_trigger_types`.`code_id` AS `type_code_id`,`security_triggers`.`trigger_status_id` AS `trigger_status_id`,`security_trigger_stati`.`status_text` AS `status_text`,`security_trigger_stati`.`code_id` AS `status_code_id`,`security_triggers`.`trigger_value` AS `trigger_value`,`security_triggers`.`security_id` AS `security_id`,`security_triggers`.`portfolio_id` AS `portfolio_id`,`securities`.`name` AS `sec_name`,`securities`.`ISIN` AS `ISIN`,`securities`.`bid` AS `bid`,`securities`.`ask` AS `ask`,`securities`.`last_price` AS `last_price`,`securities`.`symbol_market_map` AS `symbol_mm` from (((`security_triggers` left join `securities` on((`security_triggers`.`security_id` = `securities`.`security_id`))) left join `security_trigger_types` on((`security_triggers`.`trigger_type_id` = `security_trigger_types`.`trigger_type_id`))) left join `security_trigger_stati` on((`security_triggers`.`trigger_status_id` = `security_trigger_stati`.`trigger_status_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_security_trigger_stati`
--
DROP TABLE IF EXISTS `v_security_trigger_stati`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_trigger_stati` AS select NULL AS `trigger_status_id`,' not set' AS `status_text` union select `security_trigger_stati`.`trigger_status_id` AS `trigger_status_id`,`security_trigger_stati`.`status_text` AS `status_text` from `security_trigger_stati`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_trigger_types`
--
DROP TABLE IF EXISTS `v_security_trigger_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_trigger_types` AS select NULL AS `trigger_type_id`,' not set' AS `type_name` union select `security_trigger_types`.`trigger_type_id` AS `trigger_type_id`,`security_trigger_types`.`type_name` AS `type_name` from `security_trigger_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_security_types`
--
DROP TABLE IF EXISTS `v_security_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_security_types` AS select NULL AS `security_type_id`,' not set' AS `description`,NULL AS `security_quote_type_id`,1 AS `quantity_factor`,'' AS `quote_type` union select `security_types`.`security_type_id` AS `security_type_id`,`security_types`.`description` AS `description`,`security_types`.`security_quote_type_id` AS `security_quote_type_id`,`security_quote_types`.`quantity_factor` AS `quantity_factor`,`security_quote_types`.`type_name` AS `quote_type` from (`security_types` left join `security_quote_types` on((`security_types`.`security_quote_type_id` = `security_quote_types`.`security_quote_type_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_test_price_link`
--
DROP TABLE IF EXISTS `v_test_price_link`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_test_price_link` AS select `v_portfolio_pos_calc`.`portfolio_id` AS `portfolio_id`,`v_portfolio_pos_calc`.`security_id` AS `security_id`,`v_portfolio_pos_calc`.`security_name` AS `security_name`,`v_portfolio_pos_get_prices`.`bid` AS `bid`,`v_portfolio_pos_get_prices`.`ask` AS `ask`,`v_portfolio_pos_get_prices`.`last_price` AS `last` from (`v_portfolio_pos_calc` join `v_portfolio_pos_get_prices`) where ((`v_portfolio_pos_calc`.`position` <> 0) and (`v_portfolio_pos_calc`.`portfolio_id` = `v_portfolio_pos_get_prices`.`portfolio_id`) and (`v_portfolio_pos_calc`.`security_id` = `v_portfolio_pos_get_prices`.`security_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_trades_confirm_to_bank`
--
DROP TABLE IF EXISTS `v_trades_confirm_to_bank`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trades_confirm_to_bank` AS select `p`.`portfolio_id` AS `portfolio_id`,`p`.`portfolio_name` AS `portfolio_name`,`t`.`trade_id` AS `trade_id`,`tt`.`description` AS `trade_type`,`t`.`size` AS `size`,`s`.`name` AS `security_name`,`t`.`price` AS `price`,`t`.`valid_until` AS `valid_until`,`t`.`creation_time` AS `creation_time`,`t`.`confirmation_time_bank` AS `confirmation_time_bank`,`pb`.`display_name` AS `person_name`,`pi`.`display_name` AS `portfolio_manager` from ((((((((((`trades` `t` join `trade_types` `tt`) join `portfolios` `p`) join `v_securities` `s`) join `accounts` `a`) join `account_persons` `apb`) join `account_person_types` `apbt`) join `v_persons` `pb`) join `account_persons` `api`) join `account_person_types` `apit`) join `v_persons` `pi`) where ((`t`.`portfolio_id` = `p`.`portfolio_id`) and (`t`.`trade_type_id` = `tt`.`trade_type_id`) and (`t`.`security_id` = `s`.`security_id`) and (`p`.`confirm_to_bank` = 1) and isnull(`t`.`confirmation_time_bank`) and (`p`.`account_id` = `a`.`account_id`) and (`a`.`account_id` = `apb`.`account_id`) and (`apb`.`account_person_type_id` = `apbt`.`account_person_type_id`) and (`apbt`.`code_id` = 'bank_rm') and (`apb`.`person_id` = `pb`.`person_id`) and (`a`.`account_id` = `api`.`account_id`) and (`api`.`account_person_type_id` = `apit`.`account_person_type_id`) and (`apit`.`code_id` = 'pm') and (`api`.`person_id` = `pi`.`person_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_trades_confirm_to_client`
--
DROP TABLE IF EXISTS `v_trades_confirm_to_client`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trades_confirm_to_client` AS select `p`.`portfolio_id` AS `portfolio_id`,`p`.`portfolio_name` AS `portfolio_name`,`t`.`trade_id` AS `trade_id`,`tt`.`description` AS `trade_type`,`t`.`size` AS `size`,`s`.`name` AS `security_name`,`t`.`price` AS `price`,`t`.`valid_until` AS `valid_until`,`t`.`creation_time` AS `creation_time`,`t`.`confirmation_time_client` AS `confirmation_time_client`,'Fritz Sample' AS `person_name`,`pi`.`display_name` AS `portfolio_manager` from ((((((((`trades` `t` join `trade_types` `tt`) join `portfolios` `p`) join `v_securities` `s`) join `accounts` `a`) join `v_persons` `pb`) join `account_persons` `api`) join `account_person_types` `apit`) join `v_persons` `pi`) where ((`t`.`portfolio_id` = `p`.`portfolio_id`) and (`t`.`trade_type_id` = `tt`.`trade_type_id`) and (`t`.`security_id` = `s`.`security_id`) and (`p`.`confirm_to_bank` = 1) and isnull(`t`.`confirmation_time_client`) and (`p`.`account_id` = `a`.`account_id`) and (`a`.`person_id` = `pb`.`person_id`) and (`a`.`account_id` = `api`.`account_id`) and (`api`.`account_person_type_id` = `apit`.`account_person_type_id`) and (`apit`.`code_id` = 'pm') and (`api`.`person_id` = `pi`.`person_id`));

-- --------------------------------------------------------

--
-- Structure for view `v_trades_fx`
--
DROP TABLE IF EXISTS `v_trades_fx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trades_fx` AS select `trades`.`trade_id` AS `trade_id`,`trades`.`portfolio_id` AS `portfolio_id`,`trades`.`trade_date` AS `trade_date`,`trades`.`valid_until` AS `valid_until`,`trades`.`confirmation_time` AS `confirmation_time`,`trades`.`trade_confirmation_type_id` AS `trade_confirmation_type_id`,`trades`.`trade_confirmation_person` AS `trade_confirmation_person`,`trades`.`internal_person_id` AS `internal_person_id`,`trades`.`contact_type_id` AS `contact_type_id`,`trades`.`currency_id` AS `currency_id`,`trades`.`fx_rate` AS `fx_rate`,`trades`.`settlement_currency_id` AS `settlement_currency_id`,`trades`.`trade_type_id` AS `trade_type_id`,`trades`.`trade_status_id` AS `trade_status_id`,`trades`.`size` AS `size`,`trades`.`premium_sett` AS `premium_sett`,`trades`.`premium_netto` AS `premium_netto`,`trades`.`premium_sett_netto` AS `premium_sett_netto`,`trades`.`rational` AS `rational`,`trades`.`settlement_date` AS `settlement_date`,`trades`.`creation_time` AS `creation_time`,`trades`.`bank_ref_id` AS `bank_ref_id`,`trades`.`counterparty_ref_id` AS `counterparty_ref_id`,`trades`.`bank_text_ins` AS `bank_text_ins`,`trades`.`checked` AS `checked`,`trades`.`related_trade_id` AS `related_trade_id`,`trades`.`scanned_bank_confirmation` AS `scanned_bank_confirmation`,`trades`.`bo_status` AS `bo_status`,`trades`.`comment` AS `comment` from ((`trades` join `v_securities`) join `security_types`) where ((`trades`.`security_id` = `v_securities`.`security_id`) and (`v_securities`.`security_type_id` = `security_types`.`security_type_id`) and (`security_types`.`code_id` = 'FX'));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_confirmation_types`
--
DROP TABLE IF EXISTS `v_trade_confirmation_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_confirmation_types` AS select NULL AS `trade_confirmation_type_id`,' not set' AS `type_name` union select `trade_confirmation_types`.`trade_confirmation_type_id` AS `trade_confirmation_type_id`,`trade_confirmation_types`.`type_name` AS `type_name` from `trade_confirmation_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_fifo_sum_size`
--
DROP TABLE IF EXISTS `v_trade_fifo_sum_size`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_fifo_sum_size` AS select `v_trade_premium`.`portfolio_id` AS `portfolio_id`,`v_trade_premium`.`security_id` AS `security_id`,max(`trades`.`trade_date`) AS `trade_date`,sum(`v_trade_premium`.`size`) AS `size_sum`,sum(`v_trade_premium`.`calc_premium`) AS `calc_premium_sum`,sum(`v_trade_premium`.`premium`) AS `premium_sum` from (`trades` join `v_trade_premium`) where ((`v_trade_premium`.`portfolio_id` = `trades`.`portfolio_id`) and (`v_trade_premium`.`security_id` = `trades`.`security_id`) and (`v_trade_premium`.`trade_date` < `trades`.`trade_date`)) group by `v_trade_premium`.`portfolio_id`,`v_trade_premium`.`security_id`,`trades`.`trade_date`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_fifo_sum_size_test`
--
DROP TABLE IF EXISTS `v_trade_fifo_sum_size_test`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_fifo_sum_size_test` AS select `v_trade_premium`.`portfolio_id` AS `portfolio_id`,`v_trade_premium`.`security_id` AS `security_id`,max(`trades`.`trade_date`) AS `trade_date`,sum(`v_trade_premium`.`size`) AS `size_sum`,sum(`v_trade_premium`.`calc_premium`) AS `calc_premium_sum`,sum(`v_trade_premium`.`premium`) AS `premium_sum` from (`trades` join `v_trade_premium`) where ((`v_trade_premium`.`portfolio_id` = `trades`.`portfolio_id`) and (`v_trade_premium`.`security_id` = `trades`.`security_id`) and (`v_trade_premium`.`trade_date` < `trades`.`trade_date`)) group by `v_trade_premium`.`portfolio_id`,`v_trade_premium`.`security_id`,`trades`.`trade_date`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_fifo_type`
--
DROP TABLE IF EXISTS `v_trade_fifo_type`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_fifo_type` AS select `v_trade_premium`.`portfolio_id` AS `portfolio_id`,`v_trade_premium`.`security_id` AS `security_id`,`v_trade_premium`.`trade_date` AS `trade_date`,`v_trade_premium`.`size` AS `size`,`v_trade_fifo_sum_size`.`size_sum` AS `size_sum`,`v_trade_fifo_sum_size`.`calc_premium_sum` AS `calc_premium_sum`,`v_trade_fifo_sum_size`.`premium_sum` AS `premium_sum`,if((isnull(`v_trade_fifo_sum_size`.`size_sum`) or (`v_trade_fifo_sum_size`.`size_sum` = 0)),'increase',if((((`v_trade_fifo_sum_size`.`size_sum` > 0) and (`v_trade_premium`.`size` > 0)) or ((`v_trade_fifo_sum_size`.`size_sum` < 0) and (`v_trade_premium`.`size` < 0))),'increase',if((abs(`v_trade_premium`.`size`) > abs(`v_trade_fifo_sum_size`.`size_sum`)),'reopen','reduce'))) AS `trade_type` from (`v_trade_premium` left join `v_trade_fifo_sum_size` on(((`v_trade_premium`.`portfolio_id` = `v_trade_fifo_sum_size`.`portfolio_id`) and (`v_trade_premium`.`security_id` = `v_trade_fifo_sum_size`.`security_id`) and (`v_trade_premium`.`trade_date` = `v_trade_fifo_sum_size`.`trade_date`))));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_fifo_upl`
--
DROP TABLE IF EXISTS `v_trade_fifo_upl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_fifo_upl` AS select `v_trade_fifo_type`.`portfolio_id` AS `portfolio_id`,`v_trade_fifo_type`.`security_id` AS `security_id`,`v_trade_fifo_type`.`trade_date` AS `trade_date`,`v_trade_fifo_type`.`size` AS `size`,`v_trade_fifo_type`.`size_sum` AS `size_sum`,`v_trade_fifo_type`.`calc_premium_sum` AS `calc_premium_sum`,`v_trade_fifo_type`.`premium_sum` AS `premium_sum`,`v_trade_fifo_type`.`trade_type` AS `trade_type`,if((`v_trade_fifo_type`.`trade_type` = 'increase'),1,if((`v_trade_fifo_type`.`trade_type` = 'reduce'),((1 - ((`v_trade_fifo_type`.`size_sum` + `v_trade_fifo_type`.`size`) / `v_trade_fifo_type`.`size_sum`)) * -(1)),abs(((`v_trade_fifo_type`.`size_sum` + `v_trade_fifo_type`.`size`) / `v_trade_fifo_type`.`size`)))) AS `upl_in_pct` from `v_trade_fifo_type`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_payment_types`
--
DROP TABLE IF EXISTS `v_trade_payment_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_payment_types` AS select NULL AS `trade_payment_type_id`,' not set' AS `type_name` union select `trade_payment_types`.`trade_payment_type_id` AS `trade_payment_type_id`,`trade_payment_types`.`type_name` AS `type_name` from `trade_payment_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_pnl`
--
DROP TABLE IF EXISTS `v_trade_pnl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_pnl` AS select `v_trade_pnl_calc`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl_calc`.`security_id` AS `security_id`,`v_trade_pnl_calc`.`security_name` AS `security_name`,`v_trade_pnl_calc`.`security_type_id` AS `security_type_id`,`v_trade_pnl_calc`.`asset_class` AS `asset_class`,`v_trade_pnl_calc`.`currency_id` AS `currency_id`,`v_trade_pnl_calc`.`trade_curr` AS `trade_curr`,`v_trade_pnl_calc`.`sec_currency_id` AS `sec_currency_id`,`v_trade_pnl_calc`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl_calc`.`sec_curr` AS `sec_curr`,`v_trade_pnl_calc`.`trade_date` AS `trade_date`,`v_trade_pnl_calc`.`price` AS `price`,`v_trade_pnl_calc`.`price_ref` AS `price_ref`,`v_trade_pnl_calc`.`price_ref_open` AS `price_ref_open`,`v_trade_pnl_calc`.`size` AS `size`,`v_trade_pnl_calc`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_pnl_calc`.`quantity_factor` AS `quantity_factor`,`v_trade_pnl_calc`.`calc_premium` AS `calc_premium`,`v_trade_pnl_calc`.`calc_premium_ref` AS `calc_premium_ref`,`v_trade_pnl_calc`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_pnl_calc`.`premium` AS `premium`,`v_trade_pnl_calc`.`bid` AS `bid`,`v_trade_pnl_calc`.`ask` AS `ask`,`v_trade_pnl_calc`.`last_price` AS `last_price`,`v_trade_pnl_calc`.`sell_price` AS `sell_price`,`v_trade_pnl_calc`.`update_time` AS `update_time`,`v_trade_pnl_calc`.`pos_value` AS `pos_value`,`v_trade_pnl_calc`.`pos_value_ref` AS `pos_value_ref`,`v_trade_pnl_calc`.`pos_value_ref_open` AS `pos_value_ref_open`,`v_trade_pnl_calc`.`pos_value_last` AS `pos_value_last`,`v_trade_pnl_calc`.`pos_value_last_ref` AS `pos_value_last_ref`,`v_trade_pnl_calc`.`pos_value_last_ref_open` AS `pos_value_last_ref_open`,`v_trade_pnl_calc`.`pnl` AS `pnl`,`v_trade_pnl_calc`.`pnl_ref` AS `pnl_ref`,`v_trade_pnl_calc`.`pnl_ref_open` AS `pnl_ref_open`,`v_trade_pnl_calc`.`pnl_last` AS `pnl_last`,(`v_trade_pnl_calc`.`pos_value` - `v_trade_pnl_calc`.`pos_value_ref`) AS `pnl_fx`,((`v_trade_pnl_calc`.`pos_value` - `v_trade_pnl_calc`.`pos_value_ref`) / `v_trade_pnl_calc`.`pos_value_ref`) AS `pnl_fx_pct`,`v_trade_pnl_calc`.`decimals` AS `decimals`,`v_trade_pnl_calc`.`ref_decimals` AS `ref_decimals` from `v_trade_pnl_calc` union select `v_trade_pnl_calc_cash`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl_calc_cash`.`security_id` AS `security_id`,`v_trade_pnl_calc_cash`.`security_name` AS `security_name`,`v_trade_pnl_calc_cash`.`security_type_id` AS `security_type_id`,`v_trade_pnl_calc_cash`.`asset_class` AS `asset_class`,`v_trade_pnl_calc_cash`.`currency_id` AS `currency_id`,`v_trade_pnl_calc_cash`.`trade_curr` AS `trade_curr`,`v_trade_pnl_calc_cash`.`sec_currency_id` AS `sec_currency_id`,`v_trade_pnl_calc_cash`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl_calc_cash`.`sec_curr` AS `sec_curr`,`v_trade_pnl_calc_cash`.`trade_date` AS `trade_date`,`v_trade_pnl_calc_cash`.`price` AS `price`,`v_trade_pnl_calc_cash`.`price_ref` AS `price_ref`,`v_trade_pnl_calc_cash`.`price_ref_open` AS `price_ref_open`,`v_trade_pnl_calc_cash`.`size` AS `size`,`v_trade_pnl_calc_cash`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_pnl_calc_cash`.`quantity_factor` AS `quantity_factor`,`v_trade_pnl_calc_cash`.`calc_premium` AS `calc_premium`,`v_trade_pnl_calc_cash`.`calc_premium_ref` AS `calc_premium_ref`,`v_trade_pnl_calc_cash`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_pnl_calc_cash`.`premium` AS `premium`,`v_trade_pnl_calc_cash`.`bid` AS `bid`,`v_trade_pnl_calc_cash`.`ask` AS `ask`,`v_trade_pnl_calc_cash`.`last_price` AS `last_price`,`v_trade_pnl_calc_cash`.`sell_price` AS `sell_price`,`v_trade_pnl_calc_cash`.`update_time` AS `update_time`,`v_trade_pnl_calc_cash`.`pos_value` AS `pos_value`,`v_trade_pnl_calc_cash`.`pos_value_ref` AS `pos_value_ref`,`v_trade_pnl_calc_cash`.`pos_value_ref_open` AS `pos_value_ref_open`,`v_trade_pnl_calc_cash`.`pos_value_last` AS `pos_value_last`,`v_trade_pnl_calc_cash`.`pos_value_last_ref` AS `pos_value_last_ref`,`v_trade_pnl_calc_cash`.`pos_value_last_ref_open` AS `pos_value_last_ref_open`,`v_trade_pnl_calc_cash`.`pnl` AS `pnl`,`v_trade_pnl_calc_cash`.`pnl_ref` AS `pnl_ref`,`v_trade_pnl_calc_cash`.`pnl_ref_open` AS `pnl_ref_open`,`v_trade_pnl_calc_cash`.`pnl_last` AS `pnl_last`,(`v_trade_pnl_calc_cash`.`pos_value` - `v_trade_pnl_calc_cash`.`pos_value_ref`) AS `pnl_fx`,((`v_trade_pnl_calc_cash`.`pos_value` - `v_trade_pnl_calc_cash`.`pos_value_ref`) / `v_trade_pnl_calc_cash`.`pos_value_ref`) AS `pnl_fx_pct`,`v_trade_pnl_calc_cash`.`decimals` AS `decimals`,`v_trade_pnl_calc_cash`.`ref_decimals` AS `ref_decimals` from `v_trade_pnl_calc_cash`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_pnl_calc`
--
DROP TABLE IF EXISTS `v_trade_pnl_calc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_pnl_calc` AS select `v_trade_pnl_get_prices`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl_get_prices`.`security_id` AS `security_id`,`v_trade_pnl_get_prices`.`security_name` AS `security_name`,`v_trade_pnl_get_prices`.`security_type_id` AS `security_type_id`,`v_trade_pnl_get_prices`.`asset_class` AS `asset_class`,`v_trade_pnl_get_prices`.`asset_code` AS `asset_code`,`v_trade_pnl_get_prices`.`currency_id` AS `currency_id`,`v_trade_pnl_get_prices`.`trade_curr` AS `trade_curr`,`v_trade_pnl_get_prices`.`sec_currency_id` AS `sec_currency_id`,`v_trade_pnl_get_prices`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl_get_prices`.`sec_curr` AS `sec_curr`,`v_trade_pnl_get_prices`.`trade_date` AS `trade_date`,`v_trade_pnl_get_prices`.`price` AS `price`,`v_trade_pnl_get_prices`.`price_ref` AS `price_ref`,`v_trade_pnl_get_prices`.`price_ref_open` AS `price_ref_open`,`v_trade_pnl_get_prices`.`size` AS `size`,`v_trade_pnl_get_prices`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_pnl_get_prices`.`quantity_factor` AS `quantity_factor`,`v_trade_pnl_get_prices`.`calc_premium` AS `calc_premium`,`v_trade_pnl_get_prices`.`calc_premium_ref` AS `calc_premium_ref`,`v_trade_pnl_get_prices`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_pnl_get_prices`.`premium` AS `premium`,`v_trade_pnl_get_prices`.`bid` AS `bid`,`v_trade_pnl_get_prices`.`ask` AS `ask`,`v_trade_pnl_get_prices`.`last_price` AS `last_price`,`v_trade_pnl_get_prices`.`sell_price` AS `sell_price`,`v_trade_pnl_get_prices`.`update_time` AS `update_time`,((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value`,(((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_ref`,(((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate_open`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_ref_open`,(`v_trade_pnl_get_prices`.`last_price` * `v_trade_pnl_get_prices`.`size`) AS `pos_value_last`,(((`v_trade_pnl_get_prices`.`last_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_last_ref`,(((`v_trade_pnl_get_prices`.`last_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate_open`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_last_ref_open`,(((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl`,((((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium_ref`) AS `pnl_ref`,((((`v_trade_pnl_get_prices`.`sell_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`fx_rate`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium_ref_open`) AS `pnl_ref_open`,(((`v_trade_pnl_get_prices`.`last_price` * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl_last`,`v_trade_pnl_get_prices`.`decimals` AS `decimals`,`v_trade_pnl_get_prices`.`ref_decimals` AS `ref_decimals` from `v_trade_pnl_get_prices` where (`v_trade_pnl_get_prices`.`asset_code` <> 'FX');

-- --------------------------------------------------------

--
-- Structure for view `v_trade_pnl_calc_cash`
--
DROP TABLE IF EXISTS `v_trade_pnl_calc_cash`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_pnl_calc_cash` AS select `v_trade_pnl_get_prices`.`portfolio_id` AS `portfolio_id`,`v_trade_pnl_get_prices`.`security_id` AS `security_id`,`v_trade_pnl_get_prices`.`sec_curr` AS `security_name`,`v_trade_pnl_get_prices`.`security_type_id` AS `security_type_id`,`v_trade_pnl_get_prices`.`asset_class` AS `asset_class`,`v_trade_pnl_get_prices`.`asset_code` AS `asset_code`,`v_trade_pnl_get_prices`.`currency_id` AS `currency_id`,`v_trade_pnl_get_prices`.`trade_curr` AS `trade_curr`,`v_trade_pnl_get_prices`.`sec_currency_id` AS `sec_currency_id`,`v_trade_pnl_get_prices`.`security_issuer_id` AS `security_issuer_id`,`v_trade_pnl_get_prices`.`sec_curr` AS `sec_curr`,`v_trade_pnl_get_prices`.`trade_date` AS `trade_date`,`v_trade_pnl_get_prices`.`price` AS `price`,`v_trade_pnl_get_prices`.`last_price` AS `price_ref`,`v_trade_pnl_get_prices`.`price_ref_open` AS `price_ref_open`,`v_trade_pnl_get_prices`.`size` AS `size`,`v_trade_pnl_get_prices`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_pnl_get_prices`.`quantity_factor` AS `quantity_factor`,`v_trade_pnl_get_prices`.`calc_premium` AS `calc_premium`,(`v_trade_pnl_get_prices`.`size` * `v_trade_pnl_get_prices`.`last_price`) AS `calc_premium_ref`,`v_trade_pnl_get_prices`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_pnl_get_prices`.`premium` AS `premium`,`v_trade_pnl_get_prices`.`update_time` AS `update_time`,`v_trade_pnl_get_prices`.`bid` AS `bid`,`v_trade_pnl_get_prices`.`ask` AS `ask`,if(isnull(`v_trade_pnl_get_prices`.`last_price`),1,`v_trade_pnl_get_prices`.`last_price`) AS `last_price`,if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) AS `sell_price`,((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value`,((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_ref`,((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_ref_open`,(if(isnull(`v_trade_pnl_get_prices`.`last_price`),1,`v_trade_pnl_get_prices`.`last_price`) * `v_trade_pnl_get_prices`.`size`) AS `pos_value_last`,((if(isnull(`v_trade_pnl_get_prices`.`last_price`),1,`v_trade_pnl_get_prices`.`last_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_last_ref`,((if(isnull(`v_trade_pnl_get_prices`.`last_price`),1,`v_trade_pnl_get_prices`.`last_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) AS `pos_value_last_ref_open`,(((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl`,(((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl_ref`,(((if(isnull(`v_trade_pnl_get_prices`.`sell_price`),1,`v_trade_pnl_get_prices`.`sell_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl_ref_open`,(((if(isnull(`v_trade_pnl_get_prices`.`last_price`),1,`v_trade_pnl_get_prices`.`last_price`) * `v_trade_pnl_get_prices`.`size`) * `v_trade_pnl_get_prices`.`quantity_factor`) + `v_trade_pnl_get_prices`.`calc_premium`) AS `pnl_last`,`v_trade_pnl_get_prices`.`decimals` AS `decimals`,`v_trade_pnl_get_prices`.`ref_decimals` AS `ref_decimals` from `v_trade_pnl_get_prices` where (`v_trade_pnl_get_prices`.`asset_code` = 'FX');

-- --------------------------------------------------------

--
-- Structure for view `v_trade_pnl_get_prices`
--
DROP TABLE IF EXISTS `v_trade_pnl_get_prices`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_pnl_get_prices` AS select `v_trade_premium_ref`.`portfolio_id` AS `portfolio_id`,`v_trade_premium_ref`.`security_id` AS `security_id`,`securities`.`name` AS `security_name`,`securities`.`security_type_id` AS `security_type_id`,`security_types`.`description` AS `asset_class`,if(isnull(`security_types`.`code_id`),'',`security_types`.`code_id`) AS `asset_code`,`v_trade_premium_ref`.`currency_id` AS `currency_id`,`v_trade_premium_ref`.`trade_curr` AS `trade_curr`,`v_trade_premium_ref`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium_ref`.`security_issuer_id` AS `security_issuer_id`,`v_trade_premium_ref`.`sec_curr` AS `sec_curr`,`v_trade_premium_ref`.`trade_date` AS `trade_date`,`v_trade_premium_ref`.`price` AS `price`,`v_trade_premium_ref`.`price_ref` AS `price_ref`,`v_trade_premium_ref`.`price_ref_open` AS `price_ref_open`,`v_trade_premium_ref`.`size` AS `size`,`v_trade_premium_ref`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium_ref`.`quantity_factor` AS `quantity_factor`,`v_trade_premium_ref`.`calc_premium` AS `calc_premium`,`v_trade_premium_ref`.`calc_premium_ref` AS `calc_premium_ref`,`v_trade_premium_ref`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_premium_ref`.`premium` AS `premium`,`v_portfolio_pos_get_prices`.`bid` AS `bid`,`v_portfolio_pos_get_prices`.`ask` AS `ask`,`v_portfolio_pos_get_prices`.`last_price` AS `last_price`,`v_portfolio_pos_get_prices`.`used_price` AS `sell_price`,if(isnull(`v_portfolio_pos_get_prices`.`update_time`),str_to_date('2014-01-01 11:30:10','%Y-%m-%d %h:%i:%s'),`v_portfolio_pos_get_prices`.`update_time`) AS `update_time`,`v_trade_premium_ref`.`ref_currency_id` AS `ref_currency_id`,`v_trade_premium_ref`.`decimals` AS `decimals`,`v_trade_premium_ref`.`ref_decimals` AS `ref_decimals`,`v_trade_premium_ref`.`fx_rate` AS `fx_rate`,`v_trade_premium_ref`.`fx_rate_open` AS `fx_rate_open` from (((`v_trade_premium_ref` left join `securities` on((`v_trade_premium_ref`.`security_id` = `securities`.`security_id`))) left join `security_types` on((`securities`.`security_type_id` = `security_types`.`security_type_id`))) left join `v_portfolio_pos_get_prices` on(((`v_trade_premium_ref`.`portfolio_id` = `v_portfolio_pos_get_prices`.`portfolio_id`) and (`v_trade_premium_ref`.`security_id` = `v_portfolio_pos_get_prices`.`security_id`))));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium`
--
DROP TABLE IF EXISTS `v_trade_premium`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium` AS 
select 
`trades`.`portfolio_id` AS `portfolio_id`,
`trades`.`security_id` AS `security_id`,
`trades`.`currency_id` AS `currency_id`,
`currencies`.`symbol` AS `trade_curr`,
`trades`.`trade_date` AS `trade_date`,
`trades`.`price` AS `price`,
(`trades`.`size` * `trade_types`.`factor`) AS `size`,
`v_securities`.`security_quote_type_id` AS `security_quote_type_id`,
`v_securities`.`quantity_factor` AS `quantity_factor`,
`v_securities`.`currency_id` AS `sec_currency_id`,
`v_securities`.`security_issuer_id` AS `security_issuer_id`,
(((`trades`.`price` * `trades`.`size` * `v_securities`.`quantity_factor`) * -(1)) * `trade_types`.`factor`) AS `calc_premium`,
`trades`.`premium` AS `premium`,
if(((`trades`.`currency_id` = `trades`.`settlement_currency_id`) 
 or (`trades`.`settlement_currency_id` = 0) 
 or isnull(`trades`.`settlement_currency_id`)),1,`trades`.`fx_rate`) AS `fx_rate_open`,
`portfolios`.`currency_id` AS `ref_currency_id`,
`currencies`.`decimals` AS `decimals` 
from (((((`trades` left join `trade_stati`  on((`trades`.`trade_status_id`            = `trade_stati`.`trade_status_id`))) 
                   left join `trade_types`  on((`trades`.`trade_type_id`              = `trade_types`.`trade_type_id`))) 
                   left join `v_securities` on((`trades`.`security_id`                = `v_securities`.`security_id`))) 
                   left join `portfolios`   on((`trades`.`portfolio_id`               = `portfolios`.`portfolio_id`))) 
                   left join `currencies`   on((`trades`.`currency_id`                = `currencies`.`currency_id`))) 
WHERE `trade_stati`.`use_for_position` = 1
  AND (`trade_types`.`do_not_use_size` is Null or `trade_types`.`do_not_use_size` = 0)
  AND `trades`.`security_id` is NOT Null;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_fifo`
--
DROP TABLE IF EXISTS `v_trade_premium_fifo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_fifo` AS select `v_trade_premium`.`portfolio_id` AS `portfolio_id`,`v_trade_premium`.`security_id` AS `security_id`,`v_trade_premium`.`currency_id` AS `currency_id`,`v_trade_premium`.`trade_curr` AS `trade_curr`,`v_trade_premium`.`trade_date` AS `trade_date`,`v_trade_premium`.`price` AS `price`,`v_trade_premium`.`size` AS `size`,`v_trade_fifo_upl`.`size_sum` AS `size_sum`,`v_trade_premium`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium`.`quantity_factor` AS `quantity_factor`,`v_trade_premium`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium`.`security_issuer_id` AS `security_issuer_id`,(if((`v_trade_fifo_upl`.`trade_type` = 'increase'),`v_trade_premium`.`calc_premium`,`v_trade_fifo_upl`.`calc_premium_sum`) * `v_trade_fifo_upl`.`upl_in_pct`) AS `calc_premium`,(if((`v_trade_fifo_upl`.`trade_type` = 'increase'),`v_trade_premium`.`premium`,`v_trade_fifo_upl`.`premium_sum`) * `v_trade_fifo_upl`.`upl_in_pct`) AS `premium`,`v_trade_premium`.`fx_rate_open` AS `fx_rate_open`,`v_trade_premium`.`ref_currency_id` AS `ref_currency_id`,`v_trade_premium`.`decimals` AS `decimals` from (`v_trade_premium` left join `v_trade_fifo_upl` on(((`v_trade_premium`.`portfolio_id` = `v_trade_fifo_upl`.`portfolio_id`) and (`v_trade_premium`.`security_id` = `v_trade_fifo_upl`.`security_id`) and (`v_trade_premium`.`trade_date` = `v_trade_fifo_upl`.`trade_date`))));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_long_short`
--
DROP TABLE IF EXISTS `v_trade_premium_long_short`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_long_short` AS select `v_trade_premium`.`portfolio_id` AS `portfolio_id`,`v_trade_premium`.`security_id` AS `security_id`,sum(if((`v_trade_premium`.`size` <= 0),0,`v_trade_premium`.`size`)) AS `size_long`,sum(if((`v_trade_premium`.`size` <= 0),0,`v_trade_premium`.`calc_premium`)) AS `premium_long`,sum(if((`v_trade_premium`.`size` >= 0),0,`v_trade_premium`.`size`)) AS `size_short`,sum(if((`v_trade_premium`.`size` >= 0),0,`v_trade_premium`.`calc_premium`)) AS `premium_short` from `v_trade_premium` group by `v_trade_premium`.`portfolio_id`,`v_trade_premium`.`security_id`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_ref`
--
DROP TABLE IF EXISTS `v_trade_premium_ref`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_ref` AS select `v_trade_premium_ref_get_fx`.`portfolio_id` AS `portfolio_id`,`v_trade_premium_ref_get_fx`.`security_id` AS `security_id`,`v_trade_premium_ref_get_fx`.`currency_id` AS `currency_id`,`v_trade_premium_ref_get_fx`.`trade_curr` AS `trade_curr`,`v_trade_premium_ref_get_fx`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium_ref_get_fx`.`security_issuer_id` AS `security_issuer_id`,`v_trade_premium_ref_get_fx`.`sec_curr` AS `sec_curr`,`v_trade_premium_ref_get_fx`.`decimals` AS `decimals`,`v_trade_premium_ref_get_fx`.`trade_date` AS `trade_date`,`v_trade_premium_ref_get_fx`.`price` AS `price`,(`v_trade_premium_ref_get_fx`.`price` * `v_trade_premium_ref_get_fx`.`fx_rate`) AS `price_ref`,`v_trade_premium_ref_get_fx`.`price_trade_ref` AS `price_trade_ref`,`v_trade_premium_ref_get_fx`.`price_ref_open` AS `price_ref_open`,`v_trade_premium_ref_get_fx`.`size` AS `size`,`v_trade_premium_ref_get_fx`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium_ref_get_fx`.`quantity_factor` AS `quantity_factor`,`v_trade_premium_ref_get_fx`.`calc_premium` AS `calc_premium`,(`v_trade_premium_ref_get_fx`.`calc_premium` * `v_trade_premium_ref_get_fx`.`fx_rate`) AS `calc_premium_ref`,`v_trade_premium_ref_get_fx`.`calc_premium_trade_ref` AS `calc_premium_trade_ref`,`v_trade_premium_ref_get_fx`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_premium_ref_get_fx`.`premium` AS `premium`,`v_trade_premium_ref_get_fx`.`ref_currency_id` AS `ref_currency_id`,`v_trade_premium_ref_get_fx`.`ref_decimals` AS `ref_decimals`,`v_trade_premium_ref_get_fx`.`fx_rate` AS `fx_rate`,`v_trade_premium_ref_get_fx`.`fx_rate_open` AS `fx_rate_open` from (`v_trade_premium_ref_get_fx` left join `currencies` on((`v_trade_premium_ref_get_fx`.`ref_currency_id` = `currencies`.`currency_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_ref_get`
--
DROP TABLE IF EXISTS `v_trade_premium_ref_get`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_ref_get` AS select `v_trade_premium_fifo`.`portfolio_id` AS `portfolio_id`,`v_trade_premium_fifo`.`security_id` AS `security_id`,`v_trade_premium_fifo`.`currency_id` AS `currency_id`,`v_trade_premium_fifo`.`trade_curr` AS `trade_curr`,`v_trade_premium_fifo`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium_fifo`.`security_issuer_id` AS `security_issuer_id`,`currencies`.`symbol` AS `sec_curr`,`v_trade_premium_fifo`.`decimals` AS `decimals`,`v_trade_premium_fifo`.`trade_date` AS `trade_date`,`v_trade_premium_fifo`.`price` AS `price`,`v_trade_premium_fifo`.`size` AS `size`,`v_trade_premium_fifo`.`size_sum` AS `size_sum`,`v_trade_premium_fifo`.`calc_premium` AS `calc_premium`,`v_trade_premium_fifo`.`premium` AS `premium`,`v_trade_premium_fifo`.`ref_currency_id` AS `ref_currency_id`,`v_trade_premium_fifo`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium_fifo`.`quantity_factor` AS `quantity_factor`,if((`v_trade_premium_fifo`.`currency_id` = `v_trade_premium_fifo`.`ref_currency_id`),1,(`currency_pairs`.`fx_rate` * `currency_pairs`.`factor`)) AS `trade_fx_rate`,if((`v_trade_premium_fifo`.`currency_id` = `v_trade_premium_fifo`.`ref_currency_id`),1,(`v_trade_premium_fifo`.`fx_rate_open` * `currency_pairs`.`factor`)) AS `fx_rate_open` from ((`v_trade_premium_fifo` left join `currency_pairs` on(((`v_trade_premium_fifo`.`currency_id` = `currency_pairs`.`currency1_id`) and (`v_trade_premium_fifo`.`ref_currency_id` = `currency_pairs`.`currency2_id`)))) left join `currencies` on((`v_trade_premium_fifo`.`sec_currency_id` = `currencies`.`currency_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_ref_get_fx`
--
DROP TABLE IF EXISTS `v_trade_premium_ref_get_fx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_ref_get_fx` AS select `v_trade_premium_ref_get_sec`.`portfolio_id` AS `portfolio_id`,`v_trade_premium_ref_get_sec`.`security_id` AS `security_id`,`v_trade_premium_ref_get_sec`.`currency_id` AS `currency_id`,`v_trade_premium_ref_get_sec`.`trade_curr` AS `trade_curr`,`v_trade_premium_ref_get_sec`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium_ref_get_sec`.`security_issuer_id` AS `security_issuer_id`,`v_trade_premium_ref_get_sec`.`sec_curr` AS `sec_curr`,`v_trade_premium_ref_get_sec`.`decimals` AS `decimals`,`v_trade_premium_ref_get_sec`.`trade_date` AS `trade_date`,`v_trade_premium_ref_get_sec`.`price` AS `price`,`v_trade_premium_ref_get_sec`.`price_trade_ref` AS `price_trade_ref`,`v_trade_premium_ref_get_sec`.`price_ref_open` AS `price_ref_open`,`v_trade_premium_ref_get_sec`.`size` AS `size`,`v_trade_premium_ref_get_sec`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium_ref_get_sec`.`quantity_factor` AS `quantity_factor`,`v_trade_premium_ref_get_sec`.`calc_premium` AS `calc_premium`,`v_trade_premium_ref_get_sec`.`calc_premium_trade_ref` AS `calc_premium_trade_ref`,`v_trade_premium_ref_get_sec`.`calc_premium_ref_open` AS `calc_premium_ref_open`,`v_trade_premium_ref_get_sec`.`premium` AS `premium`,`v_trade_premium_ref_get_sec`.`ref_currency_id` AS `ref_currency_id`,`v_trade_premium_ref_get_sec`.`ref_decimals` AS `ref_decimals`,`v_trade_premium_ref_get_sec`.`trade_fx_rate` AS `trade_fx_rate`,`v_trade_premium_ref_get_sec`.`fx_rate_open` AS `fx_rate_open`,if((`v_trade_premium_ref_get_sec`.`sec_currency_id` = `v_trade_premium_ref_get_sec`.`ref_currency_id`),1,(`currency_pairs`.`fx_rate` * `currency_pairs`.`factor`)) AS `fx_rate` from (`v_trade_premium_ref_get_sec` left join `currency_pairs` on(((`v_trade_premium_ref_get_sec`.`sec_currency_id` = `currency_pairs`.`currency1_id`) and (`v_trade_premium_ref_get_sec`.`ref_currency_id` = `currency_pairs`.`currency2_id`))));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_premium_ref_get_sec`
--
DROP TABLE IF EXISTS `v_trade_premium_ref_get_sec`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_premium_ref_get_sec` AS select `v_trade_premium_ref_get`.`portfolio_id` AS `portfolio_id`,`v_trade_premium_ref_get`.`security_id` AS `security_id`,`v_trade_premium_ref_get`.`currency_id` AS `currency_id`,`v_trade_premium_ref_get`.`trade_curr` AS `trade_curr`,`v_trade_premium_ref_get`.`sec_currency_id` AS `sec_currency_id`,`v_trade_premium_ref_get`.`security_issuer_id` AS `security_issuer_id`,`v_trade_premium_ref_get`.`sec_curr` AS `sec_curr`,`v_trade_premium_ref_get`.`decimals` AS `decimals`,`v_trade_premium_ref_get`.`trade_date` AS `trade_date`,`v_trade_premium_ref_get`.`price` AS `price`,(`v_trade_premium_ref_get`.`price` * `v_trade_premium_ref_get`.`trade_fx_rate`) AS `price_trade_ref`,(`v_trade_premium_ref_get`.`price` * `v_trade_premium_ref_get`.`fx_rate_open`) AS `price_ref_open`,`v_trade_premium_ref_get`.`size` AS `size`,`v_trade_premium_ref_get`.`security_quote_type_id` AS `security_quote_type_id`,`v_trade_premium_ref_get`.`quantity_factor` AS `quantity_factor`,`v_trade_premium_ref_get`.`calc_premium` AS `calc_premium`,(`v_trade_premium_ref_get`.`calc_premium` * `v_trade_premium_ref_get`.`trade_fx_rate`) AS `calc_premium_trade_ref`,(`v_trade_premium_ref_get`.`calc_premium` * `v_trade_premium_ref_get`.`fx_rate_open`) AS `calc_premium_ref_open`,`v_trade_premium_ref_get`.`premium` AS `premium`,`v_trade_premium_ref_get`.`ref_currency_id` AS `ref_currency_id`,`currencies`.`decimals` AS `ref_decimals`,`v_trade_premium_ref_get`.`trade_fx_rate` AS `trade_fx_rate`,`v_trade_premium_ref_get`.`fx_rate_open` AS `fx_rate_open` from (`v_trade_premium_ref_get` left join `currencies` on((`v_trade_premium_ref_get`.`ref_currency_id` = `currencies`.`currency_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_security`
--
DROP TABLE IF EXISTS `v_trade_security`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_security` AS select `trades`.`trade_id` AS `trade_id`,if((`security_types`.`code_id` = 'FX'),if((`v_currencies`.`currency_id` = `v_currencies_2`.`currency_id`),`v_currencies`.`symbol`,concat_ws('/',`v_currencies`.`symbol`,`v_currencies_2`.`symbol`)),`v_securities`.`name`) AS `security_name` from ((((`trades` left join `v_securities` on((`trades`.`security_id` = `v_securities`.`security_id`))) left join `security_types` on((`v_securities`.`security_type_id` = `security_types`.`security_type_id`))) left join `v_currencies` on((`trades`.`currency_id` = `v_currencies`.`currency_id`))) left join `v_currencies_2` on((`trades`.`settlement_currency_id` = `v_currencies_2`.`currency_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_select`
--
DROP TABLE IF EXISTS `v_trade_select`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_select` AS select NULL AS `trade_id`,' not set' AS `trade_key` union select `trades`.`trade_id` AS `trade_id`,concat_ws(' ',`portfolios`.`portfolio_name`,`trades`.`trade_date`,`trades`.`size`,`securities`.`name`) AS `trade_key` from (((`trades` left join `securities` on((`trades`.`security_id` = `securities`.`security_id`))) left join `portfolios` on((`trades`.`portfolio_id` = `portfolios`.`portfolio_id`))) left join `currencies` on((`trades`.`currency_id` = `currencies`.`currency_id`)));

-- --------------------------------------------------------

--
-- Structure for view `v_trade_stati`
--
DROP TABLE IF EXISTS `v_trade_stati`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_stati` AS select NULL AS `trade_status_id`,' not set' AS `status_text` union select `trade_stati`.`trade_status_id` AS `trade_status_id`,`trade_stati`.`status_text` AS `status_text` from `trade_stati`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_types`
--
DROP TABLE IF EXISTS `v_trade_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_types` AS select NULL AS `trade_type_id`,' not set' AS `description` union select `trade_types`.`trade_type_id` AS `trade_type_id`,`trade_types`.`description` AS `description` from `trade_types`;

-- --------------------------------------------------------

--
-- Structure for view `v_trade_type_bank_codes`
--
DROP TABLE IF EXISTS `v_trade_type_bank_codes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_trade_type_bank_codes` AS select NULL AS `trade_type_bank_code_id`,' not set' AS `type_name` union select `trade_type_bank_codes`.`trade_type_bank_code_id` AS `trade_type_bank_code_id`,`trade_type_bank_codes`.`type_name` AS `type_name` from `trade_type_bank_codes`;

-- --------------------------------------------------------

--
-- Structure for view `v_underlyings`
--
DROP TABLE IF EXISTS `v_underlyings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_underlyings` AS select `securities`.`security_id` AS `underlying_id`,`securities`.`name` AS `name` from `securities`;

-- --------------------------------------------------------

--
-- Structure for view `v_user_types`
--
DROP TABLE IF EXISTS `v_user_types`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_types` AS select NULL AS `user_type_id`,' not set' AS `type_name` union select `log_user_types`.`user_type_id` AS `user_type_id`,`log_user_types`.`type_name` AS `type_name` from `log_user_types`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`),
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `accounts_ibfk_3` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`bank_id`),
  ADD CONSTRAINT `accounts_ibfk_4` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`account_type_id`),
  ADD CONSTRAINT `accounts_ibfk_5` FOREIGN KEY (`account_mandat_id`) REFERENCES `account_mandates` (`account_mandat_id`);

--
-- Constraints for table `account_persons`
--
ALTER TABLE `account_persons`
  ADD CONSTRAINT `account_persons_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `account_persons_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`),
  ADD CONSTRAINT `account_persons_ibfk_3` FOREIGN KEY (`account_person_type_id`) REFERENCES `account_person_types` (`account_person_type_id`);

--
-- Constraints for table `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `actions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`),
  ADD CONSTRAINT `actions_ibfk_2` FOREIGN KEY (`create_contact_id`) REFERENCES `contacts` (`contact_id`),
  ADD CONSTRAINT `actions_ibfk_3` FOREIGN KEY (`action_status_id`) REFERENCES `action_stati` (`action_status_id`),
  ADD CONSTRAINT `actions_ibfk_4` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `actions_ibfk_5` FOREIGN KEY (`action_type_id`) REFERENCES `action_types` (`action_type_id`);

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`);

--
-- Constraints for table `address_links`
--
ALTER TABLE `address_links`
  ADD CONSTRAINT `address_links_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `address_links_ibfk_2` FOREIGN KEY (`address_link_type_id`) REFERENCES `address_link_types` (`address_link_type_id`),
  ADD CONSTRAINT `address_links_ibfk_3` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`);

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`contact_type_id`) REFERENCES `contact_types` (`contact_type_id`),
  ADD CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`contact_category_id`) REFERENCES `contact_categories` (`contact_category_id`),
  ADD CONSTRAINT `contacts_ibfk_3` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`portfolio_id`),
  ADD CONSTRAINT `contacts_ibfk_4` FOREIGN KEY (`action_status_id`) REFERENCES `action_stati` (`action_status_id`),
  ADD CONSTRAINT `contacts_ibfk_5` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `contacts_ibfk_6` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`);

--
-- Constraints for table `contact_members`
--
ALTER TABLE `contact_members`
  ADD CONSTRAINT `contact_members_ibfk_1` FOREIGN KEY (`contact_member_type_id`) REFERENCES `contact_member_types` (`contact_member_type_id`),
  ADD CONSTRAINT `contact_members_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`contact_id`),
  ADD CONSTRAINT `contact_members_ibfk_3` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`);

--
-- Constraints for table `contact_notes`
--
ALTER TABLE `contact_notes`
  ADD CONSTRAINT `contact_notes_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`contact_id`);

--
-- Constraints for table `contact_numbers`
--
ALTER TABLE `contact_numbers`
  ADD CONSTRAINT `contact_numbers_ibfk_1` FOREIGN KEY (`contact_number_type_id`) REFERENCES `contact_number_types` (`contact_number_type_id`),
  ADD CONSTRAINT `contact_numbers_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`),
  ADD CONSTRAINT `contact_numbers_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`);

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`contract_type_id`);

--
-- Constraints for table `currency_pairs`
--
ALTER TABLE `currency_pairs`
  ADD CONSTRAINT `currency_pairs_ibfk_1` FOREIGN KEY (`currency1_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `currency_pairs_ibfk_2` FOREIGN KEY (`currency2_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `currency_pairs_ibfk_3` FOREIGN KEY (`price_feed_type_id`) REFERENCES `security_price_feed_types` (`feed_type_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`document_type_id`),
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`bank_id`),
  ADD CONSTRAINT `documents_ibfk_4` FOREIGN KEY (`internal_person_id`) REFERENCES `persons` (`person_id`);

--
-- Constraints for table `document_types`
--
ALTER TABLE `document_types`
  ADD CONSTRAINT `document_types_ibfk_1` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`document_category_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`event_type_id`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`portfolio_id`),
  ADD CONSTRAINT `events_ibfk_4` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `events_ibfk_5` FOREIGN KEY (`event_status_id`) REFERENCES `event_stati` (`event_status_id`);

--
-- Constraints for table `exposure_exceptions`
--
ALTER TABLE `exposure_exceptions`
  ADD CONSTRAINT `exposure_exceptions_ibfk_1` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`portfolio_id`),
  ADD CONSTRAINT `exposure_exceptions_ibfk_2` FOREIGN KEY (`exposure_item_id`) REFERENCES `exposure_items` (`exposure_item_id`);

--
-- Constraints for table `exposure_items`
--
ALTER TABLE `exposure_items`
  ADD CONSTRAINT `exposure_items_ibfk_1` FOREIGN KEY (`exposure_type_id`) REFERENCES `exposure_types` (`exposure_type_id`),
  ADD CONSTRAINT `exposure_items_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `exposure_items_ibfk_3` FOREIGN KEY (`is_part_of`) REFERENCES `exposure_items` (`exposure_item_id`),
  ADD CONSTRAINT `exposure_items_ibfk_4` FOREIGN KEY (`security_type_id`) REFERENCES `security_types` (`security_type_id`);

--
-- Constraints for table `exposure_targets`
--
ALTER TABLE `exposure_targets`
  ADD CONSTRAINT `exposure_targets_ibfk_1` FOREIGN KEY (`exposure_item_id`) REFERENCES `exposure_items` (`exposure_item_id`),
  ADD CONSTRAINT `exposure_targets_ibfk_2` FOREIGN KEY (`account_mandat_id`) REFERENCES `account_mandates` (`account_mandat_id`),
  ADD CONSTRAINT `exposure_targets_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`);

--
-- Constraints for table `parameters`
--
ALTER TABLE `parameters`
  ADD CONSTRAINT `parameters_ibfk_1` FOREIGN KEY (`parameter_type_id`) REFERENCES `parameter_types` (`parameter_type_id`);

--
-- Constraints for table `persons`
--
ALTER TABLE `persons`
  ADD CONSTRAINT `persons_ibfk_1` FOREIGN KEY (`person_type_id`) REFERENCES `person_types` (`person_type_id`);

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `portfolios_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`bank_id`),
  ADD CONSTRAINT `portfolios_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `recon_files`
--
ALTER TABLE `recon_files`
  ADD CONSTRAINT `recon_files_ibfk_1` FOREIGN KEY (`recon_file_type_id`) REFERENCES `recon_file_types` (`recon_file_type_id`);

--
-- Constraints for table `recon_steps`
--
ALTER TABLE `recon_steps`
  ADD CONSTRAINT `recon_steps_ibfk_1` FOREIGN KEY (`recon_file_id`) REFERENCES `recon_files` (`recon_file_id`),
  ADD CONSTRAINT `recon_steps_ibfk_2` FOREIGN KEY (`recon_step_type_id`) REFERENCES `recon_step_types` (`recon_step_type_id`);

--
-- Constraints for table `securities`
--
ALTER TABLE `securities`
  ADD CONSTRAINT `securities_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `securities_ibfk_2` FOREIGN KEY (`currency_pair_id`) REFERENCES `currency_pairs` (`currency_pair_id`),
  ADD CONSTRAINT `securities_ibfk_3` FOREIGN KEY (`price_feed_type_id`) REFERENCES `security_price_feed_types` (`feed_type_id`),
  ADD CONSTRAINT `securities_ibfk_4` FOREIGN KEY (`security_quote_type_id`) REFERENCES `security_quote_types` (`security_quote_type_id`),
  ADD CONSTRAINT `securities_ibfk_5` FOREIGN KEY (`security_exposure_status_id`) REFERENCES `security_exposure_stati` (`security_exposure_status_id`),
  ADD CONSTRAINT `securities_ibfk_6` FOREIGN KEY (`security_type_id`) REFERENCES `security_types` (`security_type_id`),
  ADD CONSTRAINT `securities_ibfk_7` FOREIGN KEY (`security_issuer_id`) REFERENCES `security_issuers` (`security_issuer_id`);

--
-- Constraints for table `security_exposures`
--
ALTER TABLE `security_exposures`
  ADD CONSTRAINT `security_exposures_ibfk_1` FOREIGN KEY (`security_exposure_status_id`) REFERENCES `security_exposure_stati` (`security_exposure_status_id`),
  ADD CONSTRAINT `security_exposures_ibfk_2` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `security_exposures_ibfk_3` FOREIGN KEY (`exposure_item_id`) REFERENCES `exposure_items` (`exposure_item_id`);

--
-- Constraints for table `security_field_values`
--
ALTER TABLE `security_field_values`
  ADD CONSTRAINT `security_field_values_ibfk_1` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `security_field_values_ibfk_2` FOREIGN KEY (`security_field_id`) REFERENCES `security_fields` (`security_field_id`);

--
-- Constraints for table `security_triggers`
--
ALTER TABLE `security_triggers`
  ADD CONSTRAINT `security_triggers_ibfk_1` FOREIGN KEY (`trigger_status_id`) REFERENCES `security_trigger_stati` (`trigger_status_id`),
  ADD CONSTRAINT `security_triggers_ibfk_2` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `security_triggers_ibfk_3` FOREIGN KEY (`trigger_type_id`) REFERENCES `security_trigger_types` (`trigger_type_id`);

--
-- Constraints for table `security_types`
--
ALTER TABLE `security_types`
  ADD CONSTRAINT `security_types_ibfk_1` FOREIGN KEY (`security_quote_type_id`) REFERENCES `security_quote_types` (`security_quote_type_id`);

--
-- Constraints for table `security_underlyings`
--
ALTER TABLE `security_underlyings`
  ADD CONSTRAINT `security_underlyings_ibfk_1` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `security_underlyings_ibfk_2` FOREIGN KEY (`underlying_id`) REFERENCES `securities` (`security_id`);

--
-- Constraints for table `trades`
--
ALTER TABLE `trades`
  ADD CONSTRAINT `trades_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `trades_ibfk_10` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`portfolio_id`),
  ADD CONSTRAINT `trades_ibfk_11` FOREIGN KEY (`internal_person_id`) REFERENCES `persons` (`person_id`),
  ADD CONSTRAINT `trades_ibfk_2` FOREIGN KEY (`security_id`) REFERENCES `securities` (`security_id`),
  ADD CONSTRAINT `trades_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `trades_ibfk_4` FOREIGN KEY (`trade_type_id`) REFERENCES `trade_types` (`trade_type_id`),
  ADD CONSTRAINT `trades_ibfk_5` FOREIGN KEY (`trade_status_id`) REFERENCES `trade_stati` (`trade_status_id`),
  ADD CONSTRAINT `trades_ibfk_6` FOREIGN KEY (`settlement_currency_id`) REFERENCES `currencies` (`currency_id`),
  ADD CONSTRAINT `trades_ibfk_7` FOREIGN KEY (`contact_type_id`) REFERENCES `contact_types` (`contact_type_id`),
  ADD CONSTRAINT `trades_ibfk_8` FOREIGN KEY (`related_trade_id`) REFERENCES `trades` (`trade_id`);

--
-- Constraints for table `values`
--
ALTER TABLE `values`
  ADD CONSTRAINT `values_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `values_ibfk_2` FOREIGN KEY (`value_status_id`) REFERENCES `value_stati` (`value_status_id`),
  ADD CONSTRAINT `values_ibfk_3` FOREIGN KEY (`value_type_id`) REFERENCES `value_types` (`value_type_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
