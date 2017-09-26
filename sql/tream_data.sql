/*

TREAM setup data - table rows that should be created with the installation

Contains all records with a code_id, means where program code is linked to a special table rows.
And contains also some common used table rows for an easy start.


This file is part of TREAM - Portfolio Management Software.

TREAM is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

TREAM is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with TREAM. If not, see <http://www.gnu.org/licenses/gpl.html>.

To contact the authors write to: 
Timon Zielonka <timon@zukunft.com>

Copyright (c) 2013-2017 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz

*/ 

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
-- `log_user_types` - the roles of user in TREAM
--

INSERT INTO `log_user_types` (`user_type_id`, `type_name`, `comment`, `code_id`) VALUES
(1, 'root', 'this user can do everything, but this role should be used only in case of an emergency', 'root'),
(2, 'system batch', 'this user is used by the TREAM batch jobs and should not be assigned to a real person', 'system'),
(3, 'power admin', 'this user can almost everything and can be used for a single user setup without administrator', 'power_admin'),
(4, 'power user', 'this user can almost everything and can be used for a single user setup but with an administrator', 'power_user'),
(5, 'administrator', 'can change settings and setup new users, but cannot enter trades or any other business relevant data', 'admin'),
(6, 'client advisor', 'this user can see and manage only his clients and not change settings that may have an influence on other users or the asset allocation monitoring', 'user'),
(7, 'portfolio manager', 'the risk manager can set the asset allocation limits and enter trades based on the strategie, but cannot change client related data', 'risk'),
(8, 'read only', 'can just see to some portfolios', 'read_only');

--
-- `log_user_rights` - the rights of a user on a single mandate or portfolio
--

INSERT INTO `log_user_rights` (`user_right_id`, `right_name`, `comment`, `code_id`) VALUES
(1, 'system batch', 'this user is used by the tream batch jobs and cannot be assigned to a real person', 'system'),
(2, 'full control', 'can also archive portfolios', 'full'),
(3, 'full trading', 'same as client advisor, but can also delete trades and book trades outside the asset allocation limits', 'power_client_advisor'),
(4, 'client advisor', 'this user can see and add trades that are within the asset allocation limits. To delete trades this user has to book a storno trade.', 'client_advisor'),
(5, 'portfolio manager', 'the portfolio manager can set the asset allocation limits which the client advisor cannot', 'manager'),
(6, 'add trades', 'this user can only add trades, but not see the portfolio nor delete trades.', 'add_trades'),
(7, 'asset allocation', 'can only confirm trades suggested due to the asset allocation; can also see the positions', 'confirm'),
(8, 'read only', 'can just see to some portfolios', 'read_only');

--
-- `person_types` - the predefind system person types and some sample types
--

INSERT INTO `person_types` (`person_type_id`, `description`, `internal`, `comment`) VALUES
(1, 'intern', 1, 'persons that can have contact to the clients that can be linked to a TREAM user'),
(2, 'Prospect', NULL, ''),
(3, 'Clients', NULL, ''),
(4, 'Partners', NULL, '');

--
-- `persons` - some sample persons
--

INSERT INTO `persons` (`person_id`, `display_name`, `lastname`, `firstname`, `person_type_id`, `date_of_birth`, `comment`) VALUES
(1, 'Heang', 'Lor', 'Heang', 1, NULL, NULL),
(2, 'Timon', 'Zielonka', 'Timon', 1, NULL, NULL),
(3, 'Linus', 'Zielonka', 'Linus', 3, NULL, NULL);

--
-- `log_users` - the predefind system users for complete logging and some sample users
--

INSERT INTO `log_users` (`user_id`, `username`, `password`, `code_id`, `user_type_id`, `internal_person_id`, `comment`) VALUES
(1, 'system', NULL, 'system', 1, NULL, NULL),
(2, 'batch', NULL, 'batch', 2, NULL, NULL),
(3, 'reconciliation', NULL, 'reconciliation', 2, NULL, NULL),
(4, 'Heang', NULL, NULL, 6, 1, NULL),
(5, 'Timon', NULL, NULL, 7, 2, NULL);


--
-- `account_types` - some sample mandate types
--

INSERT INTO `account_types` (`account_type_id`, `description`, `comment`) VALUES
(1, 'Prospect', NULL),
(2, 'Discretionary', NULL),
(3, 'Advisorary', NULL),
(4, 'Retired', NULL);

--
-- `account_mandates` - some sample asset allocation types
--

INSERT INTO `account_mandates` (`account_mandat_id`, `description`, `comment`) VALUES
(1, 'Discretionary Conservative', NULL),
(2, 'Discretionary Growth', NULL),
(3, 'Advisory Growth', NULL);

--
-- `account_person_types` - the possible relationships between persons and a mandate
--

INSERT INTO `account_person_types` (`account_person_type_id`, `type_name`, `code_id`, `comment`) VALUES
(1, 'Beneficial Owner', 'owner', NULL),
(2, 'Advisor', 'advisor', NULL);

--
-- `banks` - some sample banks
--

INSERT INTO `banks` (`bank_id`, `bank_name`) VALUES
(1, 'JB'),
(2, 'UBS');

--
-- `currencies` - some sample currencies
--

INSERT INTO `currencies` (`currency_id`, `symbol`, `decimals`, `decimals_trading`, `comment`) VALUES
(1, 'CHF', 2, 4, NULL),
(2, 'EUR', 2, 4, NULL),
(3, 'USD', 2, 4, NULL),
(4, 'JPY', 0, 2, NULL),
(5, 'GBP', 2, 4, NULL),
(6, 'NOK', 2, 4, NULL);

--
-- `accounts` - some sample mandates
--

INSERT INTO `accounts` (`account_id`, `account_name`, `person_id`, `currency_id`, `bank_id`, `account_type_id`, `account_mandat_id`, `start_mandat`, `start_fee`, `fee_tp`, `fee_finder`, `fee_bank`, `fee_performance`, `end_finders`, `inactive`, `discount_bank`, `person_id_encoded`) VALUES
(1, 'Linus', NULL, 1, 1, 2, 2, NULL, NULL, 0.5, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(2, 'Heang Privat', 1, 1, 1, 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(3, 'Heang Company Hong Kong', 1, 3, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);

--
-- `account_persons` - some relationships to a mandate
--

INSERT INTO `account_persons` (`account_person_id`, `account_id`, `person_id`, `account_person_type_id`, `comment`) VALUES
(1, 1, 3, 1, NULL);

--
-- `contact_categories` - sample phases of the client relationship to group the client contacts
--

INSERT INTO `contact_categories` (`contact_category_id`, `category_name`, `comment`) VALUES
(1, 'Acquisition', NULL),
(2, 'Risk Profiling', NULL),
(3, 'Retention', NULL);

--
-- `contact_types` - samples for the different types of contacts with the client
--

INSERT INTO `contact_types` (`contact_type_id`, `type_name`, `comment`) VALUES
(1, 'telefon call', NULL),
(2, 'personal meeting', NULL),
(3, 'email', NULL);

--
-- `contact_member_types` - list of roles that persons can have in a meeting
--

INSERT INTO `contact_member_types` (`contact_member_type_id`, `member_type`, `comment`) VALUES
(1, 'Client Advisor', NULL),
(2, 'Portfolio Manager', NULL),
(3, 'Lawyer', NULL);

--
-- `document_categories` - e.g. to seperate client from bank related documents
--

INSERT INTO `document_categories` (`document_category_id`, `category_name`, `comment`) VALUES
(1, 'Legal contracts with clients', NULL),
(2, 'Bank statements', NULL);

--
-- `document_types` - some samples
--

INSERT INTO `document_types` (`document_type_id`, `type_name`, `document_category_id`, `comment`) VALUES
(1, 'Proposal', 1, NULL),
(2, 'Contract', 1, NULL),
(3, 'Trade statement', 2, NULL),
(4, 'Contract with Bank', 2, NULL);

--
-- `security_quote_types` to define the trade factor and the margin calculation
--

INSERT INTO `security_quote_types` (`security_quote_type_id`, `type_name`, `quantity_factor`, `margin`) VALUES
(1, 'per unit', 1, NULL),
(2, 'percent', 1, NULL),
(3, 'ounces', 1, NULL),
(4, '1 contract', 1, NULL),
(5, '100 contracts', 1, NULL),
(6, '100 contracts margin allowed', 1, 1);

--
-- `security_types` mainly to use in different forms/masks for the trade handling
--

INSERT INTO `security_types` (`security_type_id`, `description`, `security_quote_type_id`, `code_id`) VALUES
(1, 'Cash', 1, 'cash'),
(2, 'Fixed Income', 2, 'bond'),
(3, 'Equity', 1, 'equity'),
(4, 'Fund', 1, 'fund'),
(5, 'ETF', 1, 'ETF'),
(6, 'Metal', 3, 'metal'),
(7, 'Structured Product', 1, 'structi'),
(8, 'Option', 5, 'option'),
(9, 'Future', 6, 'future'),
(10, 'Alternative Investment', 1, 'alternative'),
(11, 'FX', 1, 'FX'),
(12, 'FX Swap', 4, 'FX_swap');

--
-- `exposure_types` - the root of the different asset allocation trees
--                    or in other words the different perspectives on the assets
--

INSERT INTO `exposure_types` (`exposure_type_id`, `type_name`, `description`, `comment`, `code_id`) VALUES
(1, 'Asset Class', NULL, NULL, 'asset_class'),
(2, 'Currencies', NULL, NULL, 'ccy');
(2, 'Sector', NULL, NULL, NULL);

--
-- `exposure_items` - the nodes of the asset allocation trees
--                    the first layor of the asset class item is the real asset class
--                    the lower layors of the asset class items are the packaging types
--

INSERT INTO `exposure_items` (`exposure_item_id`, `exposure_type_id`, `order_nbr`, `description`, `currency_id`, `is_part_of`, `part_weight`, `security_type_id`, `comment`) VALUES
(1, 1, 1, 'Cash', NULL, NULL, NULL, NULL, NULL),
(2, 1, 2, 'Fixed Income', NULL, NULL, NULL, NULL, NULL),
(3, 1, 3, 'Equities', NULL, NULL, NULL, NULL, NULL),
(4, 1, 4, 'Structures', NULL, NULL, NULL, NULL, 'Structured Products can have a seperate garator that the underlying asset'),
(5, 1, 5, 'Metals', NULL, NULL, NULL, NULL, NULL),
(6, 1, 6, 'Commodities', NULL, NULL, NULL, NULL, NULL),
(7, 1, 7, 'Real estate', NULL, NULL, NULL, NULL, NULL),
(8, 1, 8, 'others', NULL, NULL, NULL, NULL, NULL),
(9, 1, 11, 'Cash prime ccy', NULL, 1, NULL, NULL, NULL),
(10, 1, 12, 'Cash secondary ccy', NULL, 1, NULL, NULL, NULL),
(11, 1, 13, 'Cash blockchain', NULL, 1, NULL, NULL, NULL),
(12, 1, 14, 'Cash Fund', NULL, 1, NULL, NULL, NULL),
(13, 1, 15, 'Cash ETF', NULL, 1, NULL, NULL, NULL),
(14, 1, 16, 'Cash Option', NULL, 1, NULL, NULL, NULL),
(15, 1, 21, 'Bonds direct', NULL, 2, NULL, NULL, NULL),
(16, 1, 22, 'Bonds investment grade', NULL, 2, NULL, NULL, NULL),
(17, 1, 23, 'Bonds lower ratings', NULL, 2, NULL, NULL, NULL),
(18, 1, 24, 'Junk Bonds', NULL, 2, NULL, NULL, NULL),
(19, 1, 25, 'Bond Fund', NULL, 2, NULL, NULL, NULL),
(20, 1, 26, 'Bond ETF', NULL, 2, NULL, NULL, NULL),
(21, 1, 31, 'Equity direct', NULL, 3, NULL, NULL, NULL),
(22, 1, 32, 'Equity ADR/GDR', NULL, 3, NULL, NULL, NULL),
(23, 1, 33, 'Equity Fund active', NULL, 3, NULL, NULL, NULL),
(24, 1, 34, 'Equity ETF active', NULL, 3, NULL, NULL, NULL),
(25, 1, 35, 'Equity Index/Basket/Passive Fund', NULL, 3, NULL, NULL, NULL),
(26, 1, 36, 'Equity Index/Basket ETF', NULL, 3, NULL, NULL, NULL),
(27, 1, 37, 'Equity Future', NULL, 3, NULL, NULL, NULL),
(28, 1, 38, 'Equity Option', NULL, 3, NULL, NULL, 'Options with secured issuer'),
(29, 1, 39, 'Equity Warrant', NULL, 3, NULL, NULL, 'Options with unsecured issuer'),
(30, 1, 40, 'Equity Subscription Right', NULL, 3, NULL, NULL, NULL),
(31, 1, 41, 'Equity OTC', NULL, 3, NULL, NULL, NULL),
(32, 1, 51, 'Structures protected', NULL, 4, NULL, NULL, NULL),
(33, 1, 52, 'Structures income', NULL, 4, NULL, NULL, NULL),
(34, 1, 53, 'Structures performance', NULL, 4, NULL, NULL, NULL),
(35, 1, 54, 'Structures leverage', NULL, 4, NULL, NULL, NULL),
(36, 1, 61, 'Precious Metals', NULL, 5, NULL, NULL, NULL),
(37, 1, 62, 'Commodity Metals', NULL, 5, NULL, NULL, NULL),
(38, 1, 71, 'Hard commodities', NULL, 6, NULL, NULL, NULL),
(39, 1, 72, 'Soft commodities', NULL, 6, NULL, NULL, NULL),
(40, 1, 81, 'Real estate direct', NULL, 7, NULL, NULL, NULL),
(41, 1, 82, 'Real estate fund', NULL, 7, NULL, NULL, NULL),
(42, 1, 83, 'Real estate ETF', NULL, 7, NULL, NULL, NULL),
(43, 1, 91, 'Hedge Funds', NULL, 8, NULL, NULL, NULL),
(44, 2, 1, 'USD', 3, 11, NULL, 6, NULL),
(45, 2, 2, 'EUR', 2, 11, NULL, 6, NULL),
(46, 2, 3, 'CHF', 1, 11, NULL, 6, NULL),
(47, 2, 4, 'GBP', 5, 11, NULL, 6, NULL),
(48, 2, 5, 'JPY', 4, 13, NULL, 6, NULL),
(49, 2, 6, 'NOK', 6, 13, NULL, 6, NULL),
(50, 2, 999, 'other currencies', NULL, 11, NULL, NULL, NULL);

--
-- `action_stati` - predefined row to allow the system to track the client contacts
--

INSERT INTO `action_stati` (`action_status_id`, `status_text`, `code_id`, `comment`) VALUES
(1, 'planned', 'planned', NULL),
(2, 'pending tasks', 'pending', NULL),
(3, 'done', 'done', NULL);

--
-- `event_stati` - list of status possible for a system created event
--

INSERT INTO `event_stati` (`event_status_id`, `status_text`, `code_id`) VALUES
(1, 'created', 'event_status_create'),
(2, 'in progress', 'event_status_working'),
(3, 'done', 'event_status_done'),
(4, 'closed', 'event_status_closed');

--
-- `event_types` - list of event type that can be created by the system
--

INSERT INTO `event_types` (`event_type_id`, `type_name`, `user_type_id`, `comment`, `code_id`) VALUES
(1, 'Trade missing', NULL, NULL, 'event_type_trade_missing'),
(2, 'SQL Error', NULL, NULL, 'event_type_sql_error'),
(3, 'System Event', NULL, NULL, 'event_type_system_event');

--
-- `events` -  sample of an event (maybe not needed)
--

INSERT INTO `events` (`event_id`, `event_type_id`, `description`, `account_id`, `portfolio_id`, `security_id`, `description_unique`, `solution1_sql`, `event_status_id`, `event_date`, `created`, `updated`, `closed`, `solution1_description`, `solution2_sql`, `solution2_description`, `solution_selected`, `comment`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- `exposure_item_values` - sample values for the efficient frontier calculation
--

INSERT INTO `exposure_item_values` (`exposure_item_value_id`, `exposure_item_id`, `ref_currency_id`, `ref_security_id`, `hist_volatility`, `implied_volatility`, `expected_volatility`, `hist_return`, `market_return`, `expected_return`, `comment`) VALUES
(1, 1, 1, NULL, 11, 12, 13, 6, 7, 8, NULL);

--
-- `exposure_targets` - sample for an exposure target
--

INSERT INTO `exposure_targets` (`exposure_target_id`, `exposure_item_id`, `target`, `limit_up`, `limit_down`, `account_mandat_id`, `currency_id`, `comment`, `optimized`) VALUES
(1, 1, 45, 50, 40, 1, 1, NULL, 45);

--
-- `portfolios` - some sample portfolios
--

INSERT INTO `portfolios` (`portfolio_id`, `account_id`, `portfolio_number`, `portfolio_name`, `currency_id`, `bank_id`, `bank_portfolio_id`, `inactive`, `monitoring`, `confirm_to_bank`, `confirm_to_client`, `monitoring_security_limit`, `portfolio_type_id`, `is_part_of`, `IBAN`, `domicile`, `nationality`) VALUES
(1, 36, 1, 'Linus Portfolio', 13, 7, NULL, 0, 0, 1, 0, NULL, 1, NULL, NULL, NULL, NULL),
(2, 36, 3, 'Linus EUR Growth', 14, 7, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),

--
-- `securities` - some sample securities
--

INSERT INTO `securities` (`security_id`, `security_issuer_id`, `name`, `ISIN`, `last_price`, `bid`, `ask`, `currency_id`, `security_type_id`, `currency_pair_id`, `bsi_id`, `symbol_market_map`, `price_feed_type_id`, `valor`, `security_quote_type_id`, `security_exposure_status_id`) VALUES
(1, NULL, 'ABB', NULL, 23, 22, 24, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'Credit Suisse', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSGN.SW', NULL, NULL, NULL, NULL);

--
-- `trade_types` samples
--

INSERT INTO `trade_types` (`trade_type_id`, `description`, `factor`, `comment`) VALUES
(1, 'buy @ market', 1, NULL),
(2, 'sell @ market', 1, NULL);

--
-- `trade_stati` samples
--

INSERT INTO `trade_stati` (`trade_status_id`, `status_text`) VALUES
(1, 'new'),
(2, 'placed'),
(3, 'executed');

--
-- `trades` trades
--

INSERT INTO `trades` (`trade_id`, `account_id`, `creation_time`, `internal_person_id`, `security_id`, `currency_id`, `price`, `size`, `rational`, `settlement_date`, `trade_type_id`, `trade_date`, `premium`, `fees`, `portfolio_id`, `trade_status_id`, `checked`, `comment`, `bank_ref_id`, `counterparty_ref_id`, `valid_until`, `fx_rate`, `premium_settlement_currency`, `settlement_currency_id`, `fees_internal`, `fees_bank`, `fees_extern`, `contact_type_id`, `date_placed`, `date_client`, `related_trade_id`) VALUES
(1, 1, '2014-07-11 12:44:13', 3, 2, 1, 23, 100, 'test', NULL, 1, NULL, NULL, NULL, 1, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
