-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2014 at 10:29 PM
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

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `person_id`, `currency_id`, `bank_id`, `account_type_id`, `account_mandat_id`, `start_mandat`, `start_fee`, `fee_tp`, `fee_finder`, `fee_bank`, `fee_performance`, `end_finders`, `inactive`, `discount_bank`) VALUES
(36, 'Benificial Owner 1', NULL, 13, 7, 3, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- Dumping data for table `account_mandates`
--

INSERT INTO `account_mandates` (`account_mandat_id`, `description`) VALUES
(12, 'Discretionary Growth');

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`account_type_id`, `description`) VALUES
(3, 'Prospect');

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`bank_id`, `bank_name`) VALUES
(7, 'JB');

--
-- Dumping data for table `contact_types`
--

INSERT INTO `contact_types` (`contact_type_id`, `type_name`) VALUES
(7, 'telefon call');

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency_id`, `symbol`, `decimals`, `comment`) VALUES
(13, 'CHF', 2, NULL);

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_type_id`, `description`, `account_id`, `portfolio_id`, `security_id`, `description_unique`, `solution1_sql`, `event_status_id`, `event_date`, `created`, `updated`, `closed`, `solution1_description`, `solution2_sql`, `solution2_description`, `solution_selected`, `comment`) VALUES
(177, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- Dumping data for table `event_stati`
--

INSERT INTO `event_stati` (`event_status_id`, `status_text`, `code_id`) VALUES
(5, 'created', 'event_status_create'),
(6, 'in progress', 'event_status_working'),
(7, 'done', 'event_status_done'),
(8, 'closed', 'event_status_closed');

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`event_type_id`, `type_name`, `user_type_id`, `comment`, `code_id`) VALUES
(6, 'Trade missing', NULL, NULL, 'event_type_trade_missing'),
(7, 'SQL Error', NULL, NULL, 'event_type_sql_error'),
(8, 'System Event', NULL, NULL, 'event_type_system_event');

--
-- Dumping data for table `exposure_items`
--

INSERT INTO `exposure_items` (`exposure_item_id`, `exposure_type_id`, `order_nbr`, `description`, `currency_id`, `is_part_of`, `security_type_id`, `hist_volatility`, `implied_volatility`, `expected_volatility`, `hist_return`, `expected_return`, `market_return`) VALUES
(57, 8, NULL, 'EuropeStoxx600', NULL, NULL, NULL, 25, NULL, NULL, 4.8, NULL, NULL),
(58, 8, NULL, 'US Equities', NULL, NULL, NULL, 22, NULL, NULL, 4.8, NULL, NULL),
(59, 8, NULL, 'EU Bonds', NULL, NULL, NULL, 5, NULL, NULL, 6, NULL, NULL),
(63, 8, NULL, 'EU Cash', NULL, NULL, NULL, 0.2, NULL, NULL, 4, NULL, NULL),
(64, 8, NULL, 'World Equities', NULL, NULL, NULL, 18, NULL, NULL, 4.7, NULL, NULL),
(65, 8, NULL, 'UK Equities', NULL, NULL, NULL, 17, NULL, NULL, 5, NULL, NULL),
(66, 8, NULL, 'Asian Equities', NULL, NULL, NULL, 60, NULL, NULL, 4.9, NULL, NULL),
(67, 8, NULL, 'Commodities', NULL, NULL, NULL, 22, NULL, NULL, 1, NULL, NULL),
(68, 8, NULL, 'LatAM Equities', NULL, NULL, NULL, 48, NULL, NULL, 13, NULL, NULL);

--
-- Dumping data for table `exposure_targets`
--

INSERT INTO `exposure_targets` (`exposure_target_id`, `exposure_item_id`, `target`, `limit_up`, `limit_down`, `account_mandat_id`, `currency_id`, `comment`) VALUES
(1, 58, 10, 15, 5, 12, 13, NULL);

--
-- Dumping data for table `exposure_types`
--

INSERT INTO `exposure_types` (`exposure_type_id`, `type_name`) VALUES
(8, 'AssetClasses_EUR');

--
-- Dumping data for table `log_data`
--

INSERT INTO `log_data` (`table_id`, `table_name`, `row_id`, `field_name`, `log_time`, `user_id`, `user_name`, `old_value`, `new_value`) VALUES
(0, 'persons', 29, 'firstname', '2014-07-03 16:01:49', 0, NULL, 'Hans', 'Hans Uli'),
(0, 'exposure_types', 8, 'type_name', '2014-07-04 07:38:36', 0, NULL, NULL, 'AssetClasses'),
(0, 'exposure_items', 57, 'exposure_type_id', '2014-07-04 07:42:59', 0, NULL, NULL, '8'),
(0, 'exposure_items', 57, 'description', '2014-07-04 07:42:59', 0, NULL, NULL, 'EuropeStoxx600'),
(0, 'exposure_items', 57, 'hist_volatility', '2014-07-04 07:42:59', 0, NULL, NULL, '25'),
(0, 'exposure_items', 57, 'hist_return', '2014-07-04 07:42:59', 0, NULL, NULL, '4.5'),
(0, 'exposure_items', 58, 'exposure_type_id', '2014-07-04 08:00:24', 0, NULL, NULL, '8'),
(0, 'exposure_items', 58, 'description', '2014-07-04 08:00:24', 0, NULL, NULL, 'US Equities'),
(0, 'exposure_items', 58, 'hist_volatility', '2014-07-04 08:00:24', 0, NULL, NULL, '22'),
(0, 'exposure_items', 58, 'hist_return', '2014-07-04 08:00:24', 0, NULL, NULL, '4.8'),
(0, 'exposure_items', 59, 'exposure_type_id', '2014-07-04 08:01:18', 0, NULL, NULL, '8'),
(0, 'exposure_items', 59, 'description', '2014-07-04 08:01:18', 0, NULL, NULL, 'EU Bonds'),
(0, 'exposure_items', 59, 'hist_volatility', '2014-07-04 08:01:18', 0, NULL, NULL, '5'),
(0, 'exposure_items', 59, 'hist_return', '2014-07-04 08:01:18', 0, NULL, NULL, '2'),
(0, 'exposure_items', 60, 'exposure_type_id', '2014-07-04 08:01:55', 0, NULL, NULL, '8'),
(0, 'exposure_items', 60, 'description', '2014-07-04 08:01:55', 0, NULL, NULL, 'fff'),
(0, 'exposure_items', 61, 'description', '2014-07-04 08:03:14', 0, NULL, NULL, 'ghgfhgf'),
(0, 'exposure_items', 62, 'exposure_type_id', '2014-07-04 08:03:25', 0, NULL, NULL, '8'),
(0, 'exposure_items', 62, 'description', '2014-07-04 08:03:25', 0, NULL, NULL, 'hgfhgf'),
(0, 'exposure_items', 63, 'exposure_type_id', '2014-07-04 08:04:43', 0, NULL, NULL, '8'),
(0, 'exposure_items', 63, 'description', '2014-07-04 08:04:43', 0, NULL, NULL, 'EU Cash'),
(0, 'exposure_items', 63, 'hist_volatility', '2014-07-04 08:04:43', 0, NULL, NULL, '0.2'),
(0, 'exposure_items', 63, 'hist_return', '2014-07-04 08:04:43', 0, NULL, NULL, '4'),
(0, 'exposure_items', 64, 'exposure_type_id', '2014-07-04 08:05:44', 0, NULL, NULL, '8'),
(0, 'exposure_items', 64, 'description', '2014-07-04 08:05:44', 0, NULL, NULL, 'World Equities'),
(0, 'exposure_items', 64, 'hist_volatility', '2014-07-04 08:05:44', 0, NULL, NULL, '18'),
(0, 'exposure_items', 64, 'hist_return', '2014-07-04 08:05:44', 0, NULL, NULL, '4.7'),
(0, 'exposure_items', 65, 'exposure_type_id', '2014-07-04 08:06:39', 0, NULL, NULL, '8'),
(0, 'exposure_items', 65, 'description', '2014-07-04 08:06:39', 0, NULL, NULL, 'UK Equities'),
(0, 'exposure_items', 65, 'hist_volatility', '2014-07-04 08:06:39', 0, NULL, NULL, '17'),
(0, 'exposure_items', 65, 'hist_return', '2014-07-04 08:06:39', 0, NULL, NULL, '5'),
(0, 'exposure_items', 66, 'exposure_type_id', '2014-07-04 08:07:19', 0, NULL, NULL, '8'),
(0, 'exposure_items', 66, 'description', '2014-07-04 08:07:19', 0, NULL, NULL, 'Asian Equities'),
(0, 'exposure_items', 66, 'hist_volatility', '2014-07-04 08:07:19', 0, NULL, NULL, '42'),
(0, 'exposure_items', 66, 'hist_return', '2014-07-04 08:07:19', 0, NULL, NULL, '4.9'),
(0, 'exposure_items', 67, 'exposure_type_id', '2014-07-04 08:12:11', 0, NULL, NULL, '8'),
(0, 'exposure_items', 67, 'description', '2014-07-04 08:12:11', 0, NULL, NULL, 'Commodities'),
(0, 'exposure_items', 67, 'hist_volatility', '2014-07-04 08:12:11', 0, NULL, NULL, '22'),
(0, 'exposure_items', 67, 'hist_return', '2014-07-04 08:12:11', 0, NULL, NULL, '1'),
(0, 'exposure_items', 68, 'exposure_type_id', '2014-07-04 08:13:27', 0, NULL, NULL, '8'),
(0, 'exposure_items', 68, 'description', '2014-07-04 08:13:27', 0, NULL, NULL, 'LatAM Equities'),
(0, 'exposure_items', 68, 'hist_volatility', '2014-07-04 08:13:27', 0, NULL, NULL, '49'),
(0, 'exposure_items', 68, 'hist_return', '2014-07-04 08:13:27', 0, NULL, NULL, '13'),
(0, 'exposure_items', 57, 'hist_return', '2014-07-04 08:14:30', 0, NULL, '4.5', '4.8'),
(0, 'exposure_items', 59, 'hist_return', '2014-07-04 08:15:04', 0, NULL, '2', '6'),
(0, 'exposure_types', 8, 'type_name', '2014-07-04 08:31:18', 0, NULL, 'AssetClasses', 'AssetClasses_EUR'),
(0, 'exposure_items', 66, 'hist_volatility', '2014-07-07 09:43:00', 0, NULL, '42', '45'),
(0, 'exposure_items', 66, 'hist_volatility', '2014-07-07 10:32:48', 0, NULL, '45', '120'),
(0, 'exposure_items', 66, 'hist_volatility', '2014-07-07 11:29:29', 0, NULL, '120', '60'),
(0, 'exposure_items', 68, 'hist_volatility', '2014-07-07 12:12:36', 0, NULL, '49', '48'),
(0, 'accounts', 37, 'account_name', '2014-07-11 11:41:25', 0, NULL, NULL, 'NewAcc'),
(0, 'accounts', 37, 'inactive', '2014-07-11 11:41:25', 0, NULL, NULL, '0'),
(0, 'accounts', 38, 'account_name', '2014-07-11 11:45:24', 0, NULL, NULL, 'Muster Account'),
(0, 'accounts', 38, 'inactive', '2014-07-11 11:45:24', 0, NULL, NULL, '0'),
(0, 'persons', 31, 'lastname', '2014-07-11 11:45:54', 0, NULL, NULL, 'Test'),
(0, 'account_types', 3, 'description', '2014-07-11 11:50:34', 0, NULL, NULL, 'Discretionary Growth'),
(0, 'account_mandates', 12, 'description', '2014-07-11 11:55:21', 0, NULL, NULL, 'Discretionary Growth'),
(0, 'account_types', 3, 'description', '2014-07-11 11:55:35', 0, NULL, 'Discretionary Growth', 'Prospect'),
(0, 'currencies', 13, 'symbol', '2014-07-11 11:57:46', 0, NULL, NULL, 'CHF'),
(0, 'currencies', 13, 'decimals', '2014-07-11 11:57:46', 0, NULL, NULL, '2'),
(0, 'accounts', 39, 'account_name', '2014-07-11 11:57:55', 0, NULL, NULL, 'test'),
(0, 'accounts', 39, 'currency_id', '2014-07-11 11:57:55', 0, NULL, NULL, '13'),
(0, 'accounts', 39, 'account_type_id', '2014-07-11 11:57:55', 0, NULL, NULL, '3'),
(0, 'accounts', 39, 'account_mandat_id', '2014-07-11 11:57:55', 0, NULL, NULL, '12'),
(0, 'accounts', 39, 'inactive', '2014-07-11 11:57:55', 0, NULL, NULL, '0'),
(0, 'accounts', 38, 'person_id', '2014-07-11 11:58:31', 0, NULL, NULL, '30'),
(0, 'banks', 7, 'bank_name', '2014-07-11 12:11:21', 0, NULL, NULL, 'JB'),
(0, 'accounts', 36, 'currency_id', '2014-07-11 12:14:23', 0, NULL, NULL, '13'),
(0, 'accounts', 36, 'bank_id', '2014-07-11 12:14:23', 0, NULL, NULL, '7'),
(0, 'accounts', 36, 'account_type_id', '2014-07-11 12:14:23', 0, NULL, NULL, '3'),
(0, 'accounts', 36, 'account_mandat_id', '2014-07-11 12:14:23', 0, NULL, NULL, '12'),
(0, 'accounts', 36, 'account_name', '2014-07-11 12:28:51', 0, NULL, 'Demo Account', 'Benificial Owner 1'),
(0, 'portfolios', 25, 'account_id', '2014-07-11 12:29:39', 0, NULL, NULL, '36'),
(0, 'portfolios', 25, 'portfolio_name', '2014-07-11 12:29:39', 0, NULL, NULL, 'Aggresive Growth'),
(0, 'portfolios', 25, 'currency_id', '2014-07-11 12:29:39', 0, NULL, NULL, '13'),
(0, 'portfolios', 25, 'bank_id', '2014-07-11 12:29:39', 0, NULL, NULL, '7'),
(0, 'portfolios', 25, 'inactive', '2014-07-11 12:29:39', 0, NULL, NULL, '0'),
(0, 'portfolios', 25, 'portfolio_name', '2014-07-11 12:29:46', 0, NULL, 'Aggresive Growth', 'Aggressive Growth'),
(0, 'persons', 31, 'lastname', '2014-07-11 12:34:23', 0, NULL, 'Test', 'Advisor'),
(0, 'persons', 31, 'firstname', '2014-07-11 12:34:23', 0, NULL, NULL, 'Albert'),
(0, 'person_types', 7, 'description', '2014-07-11 12:36:25', 0, NULL, NULL, 'intern'),
(0, 'person_types', 8, 'description', '2014-07-11 12:36:35', 0, NULL, NULL, 'Prospect'),
(0, 'contact_types', 7, 'type_name', '2014-07-11 12:37:56', 0, NULL, NULL, 'telefon call'),
(0, 'securities', 479, 'name', '2014-07-11 12:38:44', 0, NULL, NULL, 'ABB'),
(0, 'securities', 479, 'currency_id', '2014-07-11 12:38:53', 0, NULL, NULL, '13'),
(0, 'trade_types', 23, 'description', '2014-07-11 12:41:01', 0, NULL, NULL, 'buy 2 market'),
(0, 'trade_types', 23, 'factor', '2014-07-11 12:41:01', 0, NULL, NULL, '1'),
(0, 'trade_types', 23, 'description', '2014-07-11 12:41:39', 0, NULL, 'buy 2 market', 'buy @ market'),
(0, 'trade_stati', 9, 'status_text', '2014-07-11 12:43:04', 0, NULL, NULL, 'new'),
(0, 'trade_stati', 10, 'status_text', '2014-07-11 12:43:10', 0, NULL, NULL, 'placed'),
(0, 'trade_stati', 11, 'status_text', '2014-07-11 12:43:24', 0, NULL, NULL, 'executed'),
(0, 'trades', 952, 'account_id', '2014-07-11 12:44:13', 0, NULL, NULL, '36'),
(0, 'trades', 952, 'internal_person_id', '2014-07-11 12:44:13', 0, NULL, NULL, '31'),
(0, 'trades', 952, 'security_id', '2014-07-11 12:44:13', 0, NULL, NULL, '479'),
(0, 'trades', 952, 'currency_id', '2014-07-11 12:44:13', 0, NULL, NULL, '13'),
(0, 'trades', 952, 'rational', '2014-07-11 12:44:13', 0, NULL, NULL, 'test'),
(0, 'trades', 952, 'trade_type_id', '2014-07-11 12:44:13', 0, NULL, NULL, '23'),
(0, 'trades', 952, 'portfolio_id', '2014-07-11 12:44:13', 0, NULL, NULL, '25'),
(0, 'trades', 952, 'trade_status_id', '2014-07-11 12:44:13', 0, NULL, NULL, '10'),
(0, 'trades', 952, 'checked', '2014-07-11 12:44:13', 0, NULL, NULL, '0'),
(0, 'trades', 952, 'settlement_currency_id', '2014-07-11 12:44:13', 0, NULL, NULL, '13'),
(0, 'trades', 952, 'contact_type_id', '2014-07-11 12:44:13', 0, NULL, NULL, '7'),
(0, 'securities', 480, 'name', '2014-10-21 20:18:47', 0, NULL, NULL, 'Credit Suisse'),
(0, 'securities', 480, 'symbol_market_map', '2014-10-21 20:18:47', 0, NULL, NULL, 'CSGN.SW'),
(0, 'exposure_targets', 1, 'exposure_item_id', '2014-10-25 19:49:40', 0, NULL, NULL, '58'),
(0, 'exposure_targets', 1, 'target', '2014-10-25 19:49:40', 0, NULL, NULL, '10'),
(0, 'exposure_targets', 1, 'limit_up', '2014-10-25 19:49:40', 0, NULL, NULL, '15'),
(0, 'exposure_targets', 1, 'limit_down', '2014-10-25 19:49:40', 0, NULL, NULL, '5'),
(0, 'exposure_targets', 1, 'account_mandat_id', '2014-10-25 19:49:40', 0, NULL, NULL, '12'),
(0, 'exposure_targets', 1, 'currency_id', '2014-10-25 19:49:40', 0, NULL, NULL, '13'),
(0, 'trades', 952, 'price', '2014-10-25 19:59:29', 0, NULL, NULL, '23'),
(0, 'trades', 952, 'size', '2014-10-25 19:59:29', 0, NULL, NULL, '100'),
(0, 'trades', 952, 'trade_status_id', '2014-10-25 19:59:29', 0, NULL, '10', '11'),
(0, 'securities', 479, 'last_price', '2014-10-25 20:00:03', 0, NULL, NULL, '23'),
(0, 'securities', 479, 'bid', '2014-10-25 20:00:03', 0, NULL, NULL, '22'),
(0, 'securities', 479, 'ask', '2014-10-25 20:00:03', 0, NULL, NULL, '24');

--
-- Dumping data for table `log_users`
--

INSERT INTO `log_users` (`user_id`, `username`, `password`, `code_id`) VALUES
(0, 'system', NULL, 'user_system'),
(1, 'demo', NULL, '');

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`person_id`, `lastname`, `firstname`, `date_of_birth`, `person_type_id`, `display_name`, `comment`, `code_id`) VALUES
(29, 'Muster', 'Hans Uli', NULL, NULL, NULL, NULL, NULL),
(30, 'Mustermann', 'Erika', NULL, NULL, NULL, NULL, NULL),
(31, 'Advisor', 'Albert', NULL, NULL, NULL, NULL, NULL);

--
-- Dumping data for table `person_types`
--

INSERT INTO `person_types` (`person_type_id`, `description`) VALUES
(7, 'intern'),
(8, 'Prospect');

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`portfolio_id`, `account_id`, `portfolio_name`, `currency_id`, `bank_id`, `bank_portfolio_id`, `inactive`) VALUES
(25, 36, 'Aggressive Growth', 13, 7, NULL, 0);

--
-- Dumping data for table `securities`
--

INSERT INTO `securities` (`security_id`, `security_issuer_id`, `name`, `ISIN`, `last_price`, `bid`, `ask`, `currency_id`, `security_type_id`, `currency_pair_id`, `bsi_id`, `symbol_market_map`, `price_feed_type_id`, `valor`, `security_quote_type_id`, `security_exposure_status_id`) VALUES
(479, NULL, 'ABB', NULL, 23, 22, 24, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(480, NULL, 'Credit Suisse', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSGN.SW', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `trades`
--

INSERT INTO `trades` (`trade_id`, `account_id`, `creation_time`, `internal_person_id`, `security_id`, `currency_id`, `price`, `size`, `rational`, `settlement_date`, `trade_type_id`, `trade_date`, `premium`, `fees`, `portfolio_id`, `trade_status_id`, `checked`, `comment`, `bank_ref_id`, `counterparty_ref_id`, `valid_until`, `fx_rate`, `premium_settlement_currency`, `settlement_currency_id`, `fees_internal`, `fees_bank`, `fees_extern`, `contact_type_id`, `date_placed`, `date_client`, `related_trade_id`) VALUES
(952, 36, '2014-07-11 12:44:13', 31, 479, 13, 23, 100, 'test', NULL, 23, NULL, NULL, NULL, 25, 11, 0, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, NULL, 7, NULL, NULL, NULL);

--
-- Dumping data for table `trade_stati`
--

INSERT INTO `trade_stati` (`trade_status_id`, `status_text`) VALUES
(9, 'new'),
(10, 'placed'),
(11, 'executed');

--
-- Dumping data for table `trade_types`
--

INSERT INTO `trade_types` (`trade_type_id`, `description`, `factor`, `comment`) VALUES
(23, 'buy @ market', 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
