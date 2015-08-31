-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net

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
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency_id`, `symbol`, `decimals`, `decimals_trading`, `comment`) VALUES
(1, 'CHF', 2, 4, NULL),
(2, 'EUR', 2, 4, NULL),
(3, 'USD', 2, 4, NULL),
(4, 'JPY', 2, 2, NULL),
(5, 'GPB', 2, 4, NULL),
(6, 'NOK', 2, 4, NULL);

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`bank_id`, `bank_name`) VALUES
(1, 'JB');

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`account_type_id`, `description`) VALUES
(1, 'Prospect');

--
-- Dumping data for table `account_mandates`
--

INSERT INTO `account_mandates` (`account_mandat_id`, `description`) VALUES
(1, 'Discretionary Growth');

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `person_id`, `currency_id`, `bank_id`, `account_type_id`, `account_mandat_id`, `start_mandat`, `start_fee`, `fee_tp`, `fee_finder`, `fee_bank`, `fee_performance`, `end_finders`, `inactive`, `discount_bank`) VALUES
(1, 'Benificial Owner 1', NULL, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- Dumping data for table `contact_types`
--

INSERT INTO `contact_types` (`contact_type_id`, `type_name`) VALUES
(1, 'telefon call');


--
-- Dumping data for table `event_stati`
--

INSERT INTO `event_stati` (`event_status_id`, `status_text`, `code_id`) VALUES
(1, 'created', 'event_status_create'),
(2, 'in progress', 'event_status_working'),
(3, 'done', 'event_status_done'),
(4, 'closed', 'event_status_closed');

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`event_type_id`, `type_name`, `user_type_id`, `comment`, `code_id`) VALUES
(1, 'Trade missing', NULL, NULL, 'event_type_trade_missing'),
(2, 'SQL Error', NULL, NULL, 'event_type_sql_error'),
(3, 'System Event', NULL, NULL, 'event_type_system_event');

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_type_id`, `description`, `account_id`, `portfolio_id`, `security_id`, `description_unique`, `solution1_sql`, `event_status_id`, `event_date`, `created`, `updated`, `closed`, `solution1_description`, `solution2_sql`, `solution2_description`, `solution_selected`, `comment`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- Dumping data for table `exposure_types`
--

INSERT INTO `exposure_types` (`exposure_type_id`, `type_name`, `description`, `comment`) VALUES
(1, 'Asset Class', NULL, NULL),
(2, 'Currencies', NULL, NULL);

--
-- Dumping data for table `security_types`
--

INSERT INTO `security_types` (`security_type_id`, `description`, `security_quote_type_id`, `code_id`) VALUES
(1, 'Bonds', NULL, NULL),
(2, 'Equities', NULL, NULL),
(3, 'ETF Equities', NULL, NULL),
(4, 'Precious metal', NULL, NULL),
(5, 'Fund', NULL, NULL),
(6, 'Cash', NULL, 'FX'),
(7, 'Structured Product', NULL, NULL);

--
-- Dumping data for table `exposure_items`
--

INSERT INTO `exposure_items` (`exposure_item_id`, `exposure_type_id`, `order_nbr`, `description`, `currency_id`, `is_part_of`, `part_weight`, `security_type_id`, `comment`) VALUES
(1, 1, NULL, 'Equities', NULL, NULL, NULL, NULL, NULL),
(2, 1, NULL, 'Equities direct', NULL, 1, NULL, 2, NULL),
(3, 1, NULL, 'ADR GDR', NULL, 2, NULL, NULL, NULL),
(4, 1, NULL, 'Equities Indirect', NULL, 1, NULL, NULL, NULL),
(5, 1, NULL, 'Equities ETF', NULL, 4, NULL, 3, NULL),
(6, 1, NULL, 'Subscription Right', NULL, 4, NULL, NULL, NULL),
(7, 1, NULL, 'Fixed Income', NULL, NULL, NULL, NULL, NULL),
(8, 1, NULL, 'Bonds direct', NULL, 7, NULL, 1, NULL),
(9, 1, NULL, 'Bonds indirect', NULL, 7, NULL, 1, NULL),
(10, 1, NULL, 'Bonds funds', NULL, 9, NULL, 1, NULL),
(11, 1, NULL, 'Cash', NULL, NULL, NULL, NULL, NULL),
(12, 2, NULL, 'USD', 3, 11, NULL, 6, NULL),
(13, 2, NULL, 'EUR', 2, 11, NULL, 6, NULL),
(14, 2, NULL, 'CHF', 1, 11, NULL, 6, NULL),
(15, 2, NULL, 'GDB', 5, 11, NULL, 6, NULL),
(16, 2, NULL, 'other currrencies', NULL, 11, NULL, NULL, NULL),
(17, 2, NULL, 'JPY', 4, 13, NULL, 6, NULL),
(18, 2, NULL, 'NOK', 6, 13, NULL, 6, NULL),
(19, 1, NULL, 'Alternative Investment', NULL, NULL, NULL, NULL, NULL),
(20, 1, NULL, 'Metals', NULL, 19, NULL, 4, NULL),
(21, 1, NULL, 'Commodities', NULL, 19, NULL, NULL, NULL),
(22, 1, NULL, 'Hedge Funds', NULL, 19, NULL, 5, NULL);

--
-- Dumping data for table `exposure_item_values`
--

INSERT INTO `exposure_item_values` (`exposure_item_value_id`, `exposure_item_id`, `ref_currency_id`, `ref_security_id`, `hist_volatility`, `implied_volatility`, `expected_volatility`, `hist_return`, `market_return`, `expected_return`, `comment`) VALUES
(1, 1, 1, NULL, 11, 12, 13, 6, 7, 8, NULL);

--
-- Dumping data for table `exposure_targets`
--

INSERT INTO `exposure_targets` (`exposure_target_id`, `exposure_item_id`, `target`, `limit_up`, `limit_down`, `account_mandat_id`, `currency_id`, `comment`, `optimized`) VALUES
(1, 1, 45, 50, 40, 1, 1, NULL, 45);

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
(1, 'Muster', 'Hans Uli', NULL, NULL, NULL, NULL, NULL),
(2, 'Mustermann', 'Erika', NULL, NULL, NULL, NULL, NULL),
(3, 'Advisor', 'Albert', NULL, NULL, NULL, NULL, NULL);

--
-- Dumping data for table `person_types`
--

INSERT INTO `person_types` (`person_type_id`, `description`) VALUES
(1, 'intern'),
(2, 'Prospect');

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`portfolio_id`, `account_id`, `portfolio_name`, `currency_id`, `bank_id`, `bank_portfolio_id`, `inactive`) VALUES
(1, 1, 'Aggressive Growth', 1, 1, NULL, 0);

--
-- Dumping data for table `securities`
--

INSERT INTO `securities` (`security_id`, `security_issuer_id`, `name`, `ISIN`, `last_price`, `bid`, `ask`, `currency_id`, `security_type_id`, `currency_pair_id`, `bsi_id`, `symbol_market_map`, `price_feed_type_id`, `valor`, `security_quote_type_id`, `security_exposure_status_id`) VALUES
(1, NULL, 'ABB', NULL, 23, 22, 24, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'Credit Suisse', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSGN.SW', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `trade_types`
--

INSERT INTO `trade_types` (`trade_type_id`, `description`, `factor`, `comment`) VALUES
(1, 'buy @ market', 1, NULL),
(2, 'sell @ market', 1, NULL);

--
-- Dumping data for table `trade_stati`
--

INSERT INTO `trade_stati` (`trade_status_id`, `status_text`) VALUES
(1, 'new'),
(2, 'placed'),
(3, 'executed');

--
-- Dumping data for table `trades`
--

INSERT INTO `trades` (`trade_id`, `account_id`, `creation_time`, `internal_person_id`, `security_id`, `currency_id`, `price`, `size`, `rational`, `settlement_date`, `trade_type_id`, `trade_date`, `premium`, `fees`, `portfolio_id`, `trade_status_id`, `checked`, `comment`, `bank_ref_id`, `counterparty_ref_id`, `valid_until`, `fx_rate`, `premium_settlement_currency`, `settlement_currency_id`, `fees_internal`, `fees_bank`, `fees_extern`, `contact_type_id`, `date_placed`, `date_client`, `related_trade_id`) VALUES
(1, 1, '2014-07-11 12:44:13', 3, 2, 1, 23, 100, 'test', NULL, 1, NULL, NULL, NULL, 1, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
