<?php
/* 

This file is part of TREAM - Open Source Portfolio Management Software for External Asset Advisors.

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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

 * This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2017 zukunft.com AG, Zurich
 * @link http://tream.biz
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License

*/

//include 'batch/tream_db_adapter.php';
 
class Tream extends P4A
{
	/**
	 * @var P4A_Menu
	 */
	public $menu = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $formulas = null;
		
	public function __construct()
	{
		parent::__construct();
		$this->setTitle("TREAM - tream.biz");

                // set the default user types as a fallback
		$_SESSION['user_type'] = 'view only';
		$_SESSION['mandate_filter'] = "all";
                
		// Menu
		$this->build("p4a_menu", "menu");
		$this->menu_for_all_users(); // user specific menu entries are created with the login mask

		// Data sources
		$this->data_source_admin();            // user and access control
		$this->data_source_setting();          // general settings such as countries
		$this->data_source_mandates();         // defines how the client wants his money to be managed
		$this->data_source_portfolios();       // 
		$this->data_source_securities();
		$this->data_source_security_select();
		$this->data_source_security_special(); // special securies such as currencies
		$this->data_source_security_support();
		$this->data_source_security_fields();
		$this->data_source_trades();
		$this->data_source_risk();
		$this->data_source_monitoring();
		$this->data_source_recon();
		$this->data_source_messages();
		$this->data_source_persons();
		$this->data_source_kyc();
		$this->data_source_crm();

		// $this->openMask("open_today"); moved to login mask

		$this->openMask("P4A_Login_Mask");
		$this->active_mask->implement('onLogin', $this, 'login');
		$this->active_mask->username->setTooltip("your username");
		$this->active_mask->password->setTooltip("Type db here");
		$this->loginInfo();
		
	}

	public function menu_for_all_users()
	{
		$this->menu->addItem("open_today")
			//->setAccessKey("o")
			->setLabel("Today")
			->setFontColor("DarkGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("events")
			//->setAccessKey("e")
			->setLabel("To Do")
			->setFontColor("DarkGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("contact_menu", "Persons")
			->setFontColor("DarkGreen");
		$this->menu->items->contact_menu->addItem("contacts")
			->setLabel("Client contacts")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("actions")
			->setLabel("Client contact notes")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("contact_members")
			->setLabel("Client contact persons")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("persons")
			->setLabel("Persons")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("contact_numbers")
			->setLabel("Contact numbers")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("addresses")
			->setLabel("Addresses")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("address_links")
			->setLabel("Address links")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("documents")
			->setLabel("Documents")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("links")
			->setLabel("Links to Mandates")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("accounts")
			->setLabel("Mandates")
			//->setAccessKey("n")
			->setFontColor("YellowGreen")
			->implement("onclick", $this, "menuClick");
                $this->menu->items->accounts->addItem("account_persons")
			->setLabel("Mandate persons")
                        ->implement("onclick", $this, "menuClick");

		$this->menu->addItem("portfolios")
			//->setAccessKey("p")
			->setFontColor("YellowGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("portfolio_monitor")
			->setLabel("Analysis")
			->setFontColor("DarkGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("trade_menu", "Trades")
			->setFontColor("DarkGreen");
		$this->menu->items->trade_menu->addItem("trades")
			//->setAccessKey("t")
			->setLabel("All trades")
			->implement("onclick", $this, "menuClick");
                $this->menu->items->trade_menu->addItem("trade_equities")
			->setLabel("Equities")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->trade_menu->addItem("trade_bonds")
			->setLabel("Bonds")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->trade_menu->addItem("trade_funds")
			->setLabel("Funds")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->trade_menu->addItem("trade_fx")
			->setLabel("FX")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->trade_menu->addItem("trade_fx_swap")
			->setLabel("FX Swap")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->trade_menu->addItem("trade_cash")
			->setLabel("Cash")
			->implement("onclick", $this, "menuClick");
                $this->menu->items->trade_menu->addItem("trade_payments")
                        ->implement("onclick", $this, "menuClick");
/*
		$this->menu->addItem("link_masks", "links");
		$this->menu->items->link_masks->addItem("address_number_links")
			->implement("onclick", $this, "menuClick");
*/
		$this->menu->addItem("security_tables", "Securities");
		$this->menu->items->security_tables->addItem("securities_equity")
			->setLabel("Equities")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("currencies")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("currency_pairs")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("securities_other")
			->setLabel("Other")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("securities")
			->setLabel("All")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_underlyings")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_link_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_quote_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_issuers")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_payments")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_exchanges")
			->implement("onclick", $this, "menuClick");
	}
	
	public function menu_risk()
	{
                $this->menu->addItem("risk_menu", "Risk")
                    ->setFontColor("YellowGreen");
                $this->menu->items->risk_menu->addItem("account_mandates")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("portfolio_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("portfolio_security_fixings")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_items")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_item_values")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_targets")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_exceptions")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("exposure_references")
                        ->implement("onclick", $this, "menuClick"); 
                $this->menu->items->risk_menu->addItem("security_triggers")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("security_exposures")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->risk_menu->addItem("security_exposure_stati")
                        ->implement("onclick", $this, "menuClick");
	}
		
	public function menu_settings()
	{
                $this->menu->addItem("support_tables", "Settings");
                $this->menu->items->support_tables->addItem("countries")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("banks")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("account_types")
			->setLabel("Mandate types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("account_person_types")
			->setLabel("Mandate relationships")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("document_categories")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("document_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("person_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("address_link_types")
			->setLabel("Address types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("contact_categories")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("contact_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("contact_member_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("contact_number_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("trade_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("trade_payment_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->support_tables->addItem("trade_confirmation_types")
                        ->implement("onclick", $this, "menuClick");
		$this->menu->items->support_tables->addItem("security_payment_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->support_tables->addItem("security_amount_types")
			->implement("onclick", $this, "menuClick");
	}
		
	public function menu_system()
	{
                $this->menu->addItem("system_tables", "System");
                $this->menu->items->system_tables->addItem("contact_stati")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("trade_stati")
                        ->implement("onclick", $this, "menuClick");
		$this->menu->items->system_tables->addItem("price_feed_types")
			->implement("onclick", $this, "menuClick"); 
		$this->menu->items->system_tables->addItem("security_field_values")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->system_tables->addItem("security_fields")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->system_tables->addItem("security_field_source_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->system_tables->addItem("security_trigger_types")
			->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("messages")
                        ->implement("onclick", $this, "menuClick"); 
                $this->menu->items->system_tables->addItem("message_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("values")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("value_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("value_stati")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("event_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("event_stati")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->system_tables->addItem("contract_types")
                        ->implement("onclick", $this, "menuClick");
	}

	public function menu_reconciliation()
	{
                $this->menu->addItem("reconciliation_tables", "Reconciliation");
                $this->menu->items->reconciliation_tables->addItem("Recon_files")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->reconciliation_tables->addItem("Recon_steps")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->reconciliation_tables->addItem("Recon_file_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->reconciliation_tables->addItem("Recon_step_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->reconciliation_tables->addItem("Recon_value_types")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->reconciliation_tables->addItem("trade_type_bank_codes")
                        ->implement("onclick", $this, "menuClick");
	}

	public function menu_admin()
	{
                $this->menu->addItem("administration", "Administration");
                $this->menu->items->administration->addItem("Users")
                        ->setLabel("TREAM Users")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->administration->addItem("User_groups")
                        ->setLabel("Teams")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->administration->addItem("User_assigns")
                        ->setLabel("Team Members")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->administration->addItem("Portfolio_rights")
                        ->setLabel("Portfolio access")
                        ->implement("onclick", $this, "menuClick");
                $this->menu->items->administration->addItem("User_rights")
                        ->setLabel("Rename portfolio rights")
                        ->implement("onclick", $this, "menuClick"); 
                $this->menu->items->administration->addItem("User_types")
                        ->setLabel("Rename system rights")
                        ->implement("onclick", $this, "menuClick");
	}

	public function data_source_admin()
	{
		$this->build("p4a_db_source", "user_types")
			->setTable("log_user_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "user_type_select")
			->setTable("v_user_types")
			->setPK("user_type_id")
			->addOrder("user_type_id","DESC")
			->load();

                // list of all users including the system users
		$this->build("p4a_db_source", "users_all")
			->setTable("log_users")
			->addJoinLeft("log_user_types", "log_users.user_type_id  = log_user_types.user_type_id",
					  array('type_name'=>'type'))
			->addOrder("username")
			->load();

                // list of the "real" users excluding the system users
		$this->build("p4a_db_source", "users")
			->setTable("log_users")
			->addJoinLeft("log_user_types", "log_users.user_type_id  = log_user_types.user_type_id",
					  array('type_name'=>'type','log_user_types.code_id'=>'type_id')) 
			->setWhere("log_user_types.code_id <> 'root' and log_user_types.code_id <> 'system'") 
			->addOrder("username")
			->load();

		$this->build("p4a_db_source", "user_select")
			->setTable("v_log_users")
			->setPK("user_id")
			->addOrder("username")
			->load();

		$this->build("p4a_db_source", "user_groups")
			->setTable("log_user_groups")
			->addOrder("group_name")
			->load();

		$this->build("p4a_db_source", "user_group_select")
			->setTable("v_log_user_groups")
			->setPK("user_group_id")
			->addOrder("group_name")
			->load();

		$this->build("p4a_db_source", "user_assigns")
			->setTable("log_user_assigns")
			->addJoinLeft("log_users", "log_user_assigns.user_id  = log_users.user_id",
					  array('username'=>'user'))
			->addJoinLeft("log_user_groups", "log_user_assigns.user_group_id  = log_user_groups.user_group_id",
					  array('group_name'=>'group'))
			->addOrder("user_id")
			->load();

		$this->build("p4a_db_source", "user_rights")
			->setTable("log_user_rights")
			->addOrder("right_name")
			->load();

		$this->build("p4a_db_source", "user_right_select")
			->setTable("v_log_user_rights")
			->setPK("user_right_id")
			->addOrder("user_right_id","DESC")
			->load();

		$this->build("p4a_db_source", "portfolio_rights")
			->setTable("portfolio_rights")
			->addJoinLeft("portfolios", "portfolio_rights.portfolio_id  = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addJoinLeft("log_users", "portfolio_rights.user_id  = log_users.user_id",
					  array('username'=>'user'))
			->addJoinLeft("log_user_groups", "portfolio_rights.user_group_id  = log_user_groups.user_group_id",
					  array('group_name'=>'group'))
			->addJoinLeft("log_user_rights", "portfolio_rights.user_right_id  = log_user_rights.user_right_id",
					  array('right_name'=>'right'))
			->addOrder("portfolio_id")
			->load();

	}

	public function data_source_setting()
	{
		$this->build("p4a_db_source", "countries")
			->setTable("countries")
			->addOrder("name")
			->load();

		$this->build("p4a_db_source", "select_countries")
			->setTable("v_countries")
			->addOrder("name")
			->setPK("country_id")
			->load();

		$this->build("p4a_db_source", "select_countries2")
			->setTable("v_countries")
			->addOrder("name")
			->setPK("country_id")
			->load();

		$this->build("p4a_db_source", "banks")
			->setTable("banks")
			->addOrder("bank_name")
			->load();

		$this->build("p4a_db_source", "select_banks")
			->setTable("v_banks")
			->addOrder("bank_name")
			->setPK("bank_id")
			->load();

		// view for later use of bill creation
		$this->build("p4a_db_source", "v_bill")
			->setTable("v_bill")
			->addOrder("account_id")
			->load();
	}

	public function data_source_mandates()
	{
		// the status of the relationship to the client e.g. prospect, advisorary, discretionary, retired
		$this->build("p4a_db_source", "account_types")
			->setTable("account_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_account_types")
			->setTable("v_account_types")
			->addOrder("description")
			->setPK("account_type_id")
			->load();

		// to define the asset allocation strategy for a group of portfolios
		$this->build("p4a_db_source", "accounts")
			->setTable("accounts")
			->addOrder("account_name")
			->addJoinLeft("v_persons", "accounts.person_id  = v_persons.person_id",
					  array('display_name'=>'client'))
			->addJoinLeft("currencies", "accounts.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("banks", "accounts.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->setPageLimit(30)
			->setPK("account_id")
			->load();

		$this->build("p4a_db_source", "select_accounts")
			->setTable("v_accounts")
			->addOrder("account_select_name")
			->setPK("account_id")
			->load();

		// list of asset allocation strategies offered to clients
		$this->build("p4a_db_source", "account_mandates")
			->setTable("account_mandates")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_account_mandates")
			->setTable("v_account_mandates")
			->addOrder("description")
			->setPK("account_mandat_id")
			->load();

		// the relations of several persons to an mandate
		$this->build("p4a_db_source", "account_persons")
			->setTable("account_persons")
			->addJoinLeft("v_accounts", "account_persons.account_id = v_accounts.account_id",
					  array('account_select_name'=>'account'))
			->addJoinLeft("v_persons", "account_persons.person_id = v_persons.person_id",
					  array('display_name'=>'person'))
			->addJoinLeft("account_person_types", "account_persons.account_person_type_id = account_person_types.account_person_type_id",
					  array('type_name'=>'type'))
			->addOrder("account_id")
			->load();

		// list of possible relationship
		$this->build("p4a_db_source", "account_person_types")
			->setTable("account_person_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "account_person_type_select")
			->setTable("v_account_person_types")
			->addOrder("type_name")
			->setPK("account_person_type_id")
			->load();

	}

	public function data_source_portfolios()
	{
		// Main portfolio management tables
		$this->build("p4a_db_source", "portfolios")
			->setTable("portfolios")
			->addOrder("portfolio_name")
			->addJoinLeft("currencies", "portfolios.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("banks", "portfolios.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->load();

		$this->build("p4a_db_source", "select_portfolios")
			->setTable("v_portfolios")
			->addOrder("portfolio_select_name")
			//->addOrder("portfolio_number")
			->setWhere("v_portfolios.inactive <> 1") 
			->setPK("portfolio_id")
			->load();

                // the portfolio type defines the asset allocation settings for one portfolio
		$this->build("p4a_db_source", "portfolio_types")
			->setTable("portfolio_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_portfolio_types")
			->setTable("v_portfolio_types")
			->addOrder("type_name")
			->setPK("portfolio_type_id")
			->load();

		// the portfolio position includ the portfolio name
                $this->build("p4a_db_source", "v_portfolio_pos_named")
			->setTable("v_portfolio_pos_named")
			->addOrder("portfolio")
			->setPageLimit(30)
			->setPK("portfolio_id")
			->load();
			
	}

	public function data_source_securities()
	{
		$this->build("p4a_db_source", "securities")
			->setTable("securities")
			->addOrder("name")
			->addJoinLeft("currencies", "securities.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("security_types", "securities.security_type_id  = security_types.security_type_id",
					  array('description'=>'type'))
			->addJoinLeft("security_quote_types", "securities.security_quote_type_id  = security_quote_types.security_quote_type_id",
					  array('type_name'=>'quote_type')) 
			->setPageLimit(30)
			->load();

		// to select an underlying for a derivative
		$this->build("p4a_db_source", "underlyings")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPageLimit(30)
			->setPK("security_id")
			->load();
			
		$this->build("p4a_db_source", "security_link_types")
			->setTable("security_link_types")
			->addOrder("type_name")
			->setPageLimit(30)
			->setPK("security_link_type_id")
			->load();

		$this->build("p4a_db_source", "security_link_types_select")
			->setTable("v_security_link_types")
			->addOrder("type_name")
			->setPageLimit(30)
			->setPK("security_link_type_id")
			->load();

                $this->build("p4a_db_source", "security_underlyings")
			->setTable("security_underlyings")
			->addJoinLeft("securities", "security_underlyings.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("v_underlyings", "security_underlyings.underlying_id  = v_underlyings.underlying_id",
					  array('name'=>'underlying'))
			->addJoinLeft("v_security_link_types", "security_underlyings.security_link_type_id  = v_security_link_types.security_link_type_id",
					  array('type_name'=>'type'))
			->addOrder("security_id")
			->load();

	}

	public function data_source_security_select()
	{
		$this->build("p4a_db_source", "select_securities")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "select_securities_2")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "select_securities_3")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "security_cash")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'cash'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_bond")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'bond'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_equity")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'equity'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_fund")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'fund'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_ETF")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'ETF'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_metal")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'metal'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_structi")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'structi'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_option")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'option'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_future")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'future'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();


		$this->build("p4a_db_source", "security_alternative")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'alternative'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "security_FX")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'FX'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "security_FX_swap")
			->setTable("v_securities")
			->setWhere("v_securities.type_code_id = 'FX_swap'") 
			->addOrder("select_name")
			->setPK("security_id")
			->load();

        }

	public function data_source_security_special()
	{
		$this->build("p4a_db_source", "currencies")
			->setTable("currencies")
			->addOrder("symbol")
			->load();

		$this->build("p4a_db_source", "settle_currencies")
			->setTable("currencies")
			->addOrder("symbol")
			->load();

		$this->build("p4a_db_source", "select_currencies")
			->setTable("v_currencies")
			->addOrder("symbol")
			->setPK("currency_id")
			->load();

		$this->build("p4a_db_source", "select_settle_currencies")
			->setTable("v_currencies")
			->addOrder("symbol")
			->setPK("currency_id")
			->load();

		$this->build("p4a_db_source", "currency_pairs")
			->setTable("currency_pairs")
			->addOrder("description")
			->addJoinLeft("currencies", "currency_pairs.currency1_id  = currencies.currency_id",
					  array('symbol'=>'from_fx')) 
			->addJoinLeft("v_currencies_2", "currency_pairs.currency2_id  = v_currencies_2.currency_id",
					  array('symbol'=>'to_fx')) 
			->load();

		$this->build("p4a_db_source", "select_currency_pairs")
			->setTable("v_currency_pairs")
			->addOrder("sort_name")
			->setPK("currency_pair_id")
			->load();

	}

	public function data_source_security_support()
	{
		$this->build("p4a_db_source", "security_types")
			->setTable("security_types")
			->addOrder("description")
			->addJoinLeft("security_quote_types", "security_types.security_quote_type_id  = security_quote_types.security_quote_type_id",
					  array('type_name'=>'quote_type')) 
			->setPK("security_type_id")
			->load();

		$this->build("p4a_db_source", "select_security_types")
			->setTable("v_security_types")
			->addOrder("description")
			->setPK("security_type_id")
			->load();

		$this->build("p4a_db_source", "security_quote_types")
			->setTable("security_quote_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_quote_types")
			->setTable("v_security_quote_types")
			->addOrder("type_name")
			->setPK("security_quote_type_id")
			->load();

		$this->build("p4a_db_source", "security_exchanges")
			->setTable("security_exchanges")
			->addOrder("exchange_name")
			->load();

		$this->build("p4a_db_source", "select_security_exchanges")
			->setTable("v_security_exchanges")
			->addOrder("exchange_name")
			->setPK("security_exchange_id")
			->load();

                $this->build("p4a_db_source", "security_payments")
			->setTable("security_payments")
			->addJoinLeft("securities", "security_payments.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addOrder("ex_date")
			->load();

		$this->build("p4a_db_source", "security_payment_types")
			->setTable("security_payment_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_payment_types")
			->setTable("v_security_payment_types")
			->addOrder("type_name")
			->setPK("security_payment_type_id")
			->load();

		$this->build("p4a_db_source", "security_amount_types")
			->setTable("security_amount_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_amount_types")
			->setTable("v_security_amount_types")
			->addOrder("type_name")
			->setPK("security_amount_type_id")
			->load();

		$this->build("p4a_db_source", "security_issuers")
			->setTable("security_issuers")
			->addOrder("issuer_name")
			->load();

		$this->build("p4a_db_source", "security_issuer_select")
			->setTable("v_security_issuers")
			->setPK("security_issuer_id")
			->addOrder("issuer_name")
			->load();

		$this->build("p4a_db_source", "security_price_feed_types")
			->setTable("security_price_feed_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_price_feed_types")
			->setTable("v_security_price_feed_types")
			->addOrder("type_name")
			->setPK("feed_type_id")
			->load(); 

	}

        // user defined information fields related to securities for generic extension of TREAM
	public function data_source_security_fields()
	{
		$this->build("p4a_db_source", "security_fields")
			->setTable("security_fields")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_security_fields")
			->setTable("v_security_fields")
			->addOrder("description")
			->setPK("security_field_id")
			->load();

		$this->build("p4a_db_source", "security_field_values")
			->setTable("security_field_values")
			->addJoinLeft("securities", "security_field_values.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("security_fields", "security_field_values.security_field_id  = security_fields.security_field_id",
					  array('description'=>'field'))
			->addOrder("security_id")
			->load();
			
		$this->build("p4a_db_source", "security_field_source_types")
			->setTable("security_field_source_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_field_source_types")
			->setTable("v_security_field_source_types")
			->addOrder("type_name")
			->setPK("security_field_source_type_id")
			->load();

		$this->build("p4a_db_source", "value_types")
			->setTable("value_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "values")
			->setTable("values")
			->addJoinLeft("accounts", "values.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("value_stati", "values.value_status_id  = value_stati.value_status_id",
					  array('status_text'=>'status'))
			->addJoinLeft("value_types", "values.value_type_id  = value_types.value_type_id",
					  array('description'=>'type'))
			->addOrder("value_date")
			->load();

		$this->build("p4a_db_source", "value_stati")
			->setTable("value_stati")
			->addOrder("status_text")
			->load();

	}

	public function data_source_trades()
	{
		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoinLeft("accounts", "trades.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("persons", "trades.internal_person_id  = persons.person_id",
					  array('lastname'=>'internal_person'))
			->addJoinLeft("securities", "trades.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->load();
			
		$this->build("p4a_db_source", "trade_stati")
			->setTable("trade_stati")
			->addOrder("trade_status_id")
			->load();

		$this->build("p4a_db_source", "trade_type_bank_codes")
			->setTable("trade_type_bank_codes")
			->addJoinLeft("banks", "trade_type_bank_codes.bank_id  = banks.bank_id",
					  array('bank_name'=>'bank'))
			->addJoinLeft("trade_types", "trade_type_bank_codes.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_trade_type_bank_codes")
			->setTable("v_trade_type_bank_codes")
			->addOrder("type_name")
			->setPK("trade_type_bank_code_id")
			->load();

		$this->build("p4a_db_source", "trade_types")
			->setTable("trade_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_trade_types")
			->setTable("v_trade_types")
			->addOrder("description")
			->setPK("trade_type_id")
			->load();

		$this->build("p4a_db_source", "select_trade_types_fx")
			->setTable("v_trade_types_fx")
			->addOrder("description")
			->setPK("trade_type_id")
			->load();

		$this->build("p4a_db_source", "select_trade_types_fx_swap")
			->setTable("v_trade_types_fx_swap")
			->addOrder("description")
			->setPK("trade_type_id")
			->load();

		$this->build("p4a_db_source", "select_trade_types_cash")
			->setTable("v_trade_types_cash")
			->addOrder("description")
			->setPK("trade_type_id")
			->load();

		$this->build("p4a_db_source", "trade_payments")
			->setTable("trade_payments")
			->addJoinLeft("currencies", "trade_payments.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("trade_payment_types", "trade_payments.trade_payment_type_id  = trade_payment_types.trade_payment_type_id",
					  array('type_name'=>'type'))
			->addOrder("trade_id")
			->load();

		$this->build("p4a_db_source", "trade_payment_types")
			->setTable("trade_payment_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_trade_payment_types")
			->setTable("v_trade_payment_types")
			->addOrder("type_name")
			->setPK("trade_payment_type_id")
			->load();

		$this->build("p4a_db_source", "trade_confirmation_types")
			->setTable("trade_confirmation_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_trade_confirmation_types")
			->setTable("v_trade_confirmation_types")
			->addOrder("type_name")
			->setPK("trade_confirmation_type_id")
			->load();

		$this->build("p4a_db_source", "select_trade_stati")
			->setTable("v_trade_stati")
			->addOrder("trade_status_id")
			->setPK("trade_status_id")
			->load();

		$this->build("p4a_db_source", "trade_select")
			->setTable("v_trade_select")
			->setPK("trade_id")
			->addOrder("trade_key")
			->load();

	}

	public function data_source_risk()
	{
		$this->build("p4a_db_source", "select_asset_class")
			->setTable("v_exposure_asset_classes")
			->addOrder("description")
			->setPK("exposure_item_id")
			->load();

		$this->build("p4a_db_source", "security_exposure_stati")
			->setTable("security_exposure_stati")
			->addOrder("status_text")
			->load();

		$this->build("p4a_db_source", "security_exposure_stati_select")
			->setTable("v_security_exposure_stati")
			->addOrder("status_text")
			->setPK("security_exposure_status_id")
			->load();

		$this->build("p4a_db_source", "exposure_types")
			->setTable("exposure_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_exposure_types")
			->setTable("v_exposure_types")
			->addOrder("type_name")
			->setPK("exposure_type_id")
			->load();

		$this->build("p4a_db_source", "exposure_items")
			->setTable("exposure_items")
			->addJoinLeft("currencies", "exposure_items.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("exposure_types", "exposure_items.exposure_type_id  = exposure_types.exposure_type_id",
					  array('type_name'=>'type'))
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_exposure_items")
			->setTable("v_exposure_items")
			->addOrder("description")
			->setPK("exposure_item_id")
			->load();

		$this->build("p4a_db_source", "exposure_item_values")
			->setTable("exposure_item_values")
			->addJoinLeft("currencies", "exposure_item_values.ref_currency_id  = currencies.currency_id",
					  array('symbol'=>'ref_fx'))
			->addJoinLeft("exposure_items", "exposure_item_values.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'item'))
			->addJoinLeft("securities", "exposure_item_values.ref_security_id  = securities.security_id",
					  array('name'=>'ref_security'))
			->addOrder("description")
			->load();

		// used to add self reference so that a tree can be created
		$this->build("p4a_db_source", "exposure_item_select")
			->setTable("v_exposure_items")
			->addOrder("description")
			->setPK("exposure_item_id")
			->load();

		$this->build("p4a_db_source", "exposure_targets")
			->setTable("exposure_targets")
			->addJoinLeft("exposure_items", "exposure_targets.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'exposure'))
			->addJoinLeft("account_mandates", "exposure_targets.account_mandat_id  = account_mandates.account_mandat_id",
					  array('description'=>'mandat'))
			->addJoinLeft("currencies", "exposure_targets.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addOrder("account_mandat_id")
			->load();

		$this->build("p4a_db_source", "exposure_references")
			->setTable("exposure_references")
			->addJoinLeft("securities", "exposure_references.sec_ref_neutral_id  = securities.security_id",
					  array('name'=>'neutral_reference'))
			->addJoinLeft("account_mandates", "exposure_references.account_mandat_id  = account_mandates.account_mandat_id",
					  array('description'=>'mandat'))
			->addJoinLeft("currencies", "exposure_references.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addOrder("account_mandat_id")
			->load();

		$this->build("p4a_db_source", "security_exposures")
			->setTable("security_exposures")
			->addJoinLeft("securities", "security_exposures.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("exposure_items", "security_exposures.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'exposure'))
			->addOrder("exposure_item_id")
			->load();

		$this->build("p4a_db_source", "exposure_exceptions")
			->setTable("exposure_exceptions")
			->addJoinLeft("exposure_items", "exposure_exceptions.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'exposure'))
			->addJoinLeft("portfolios", "exposure_exceptions.portfolio_id  = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addOrder("portfolio_id")
			->load();

	}

	public function data_source_monitoring()
	{
		$this->build("p4a_db_source", "portfolio_security_fixings")
			->setTable("portfolio_security_fixings")
			->addJoinLeft("securities", "portfolio_security_fixings.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("portfolios", "portfolio_security_fixings.portfolio_id  = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addOrder("security_id")
			->load();

		$this->build("p4a_db_source", "security_triggers")
			->setTable("security_triggers")
			->addJoinLeft("securities", "security_triggers.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("security_trigger_types", "security_triggers.trigger_type_id  = security_trigger_types.trigger_type_id",
					  array('type_name'=>'type'))
			->addJoinLeft("security_trigger_stati", "security_triggers.trigger_status_id  = security_trigger_stati.trigger_status_id",
					  array('status_text'=>'status'))
			->addOrder("start")
			->load();

		$this->build("p4a_db_source", "security_trigger_types")
			->setTable("security_trigger_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_trigger_types")
			->setTable("v_security_trigger_types")
			->addOrder("type_name")
			->setPK("trigger_type_id")
			->load();

		$this->build("p4a_db_source", "security_trigger_stati")
			->setTable("security_trigger_stati")
			->addOrder("trigger_status_id")
			->load();

		$this->build("p4a_db_source", "select_security_trigger_stati")
			->setTable("v_security_trigger_stati")
			->addOrder("trigger_status_id")
			->setPK("trigger_status_id")
			->load();

	}

	public function data_source_recon()
	{
		$this->build("p4a_db_source", "recon_files")
			->setTable("recon_files")
			->addOrder("file_name")
			->load();

		$this->build("p4a_db_source", "recon_step_select")
			->setTable("v_recon_step_select")
			->setPK("recon_step_id")
			->addOrder("recon_select_name")
			->load();

		$this->build("p4a_db_source", "recon_file_select")
			->setTable("v_recon_files")
			->setPK("recon_file_id")
			->addOrder("file_name")
			->load();

		$this->build("p4a_db_source", "recon_file_types")
			->setTable("recon_file_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_file_type_select")
			->setTable("v_recon_file_types")
			->setPK("recon_file_type_id")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_step_types")
			->setTable("recon_step_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_step_type_select")
			->setTable("v_recon_step_types")
			->setPK("recon_step_type_id")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_value_types")
			->setTable("recon_value_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_value_type_select")
			->setTable("v_recon_value_types")
			->setPK("recon_value_type_id")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "recon_steps")
			->setTable("recon_steps")
			->addOrder("recon_step_id")
			->addJoinLeft("recon_files", "recon_steps.recon_file_id = recon_files.recon_file_id",
					  array('file_name'=>'file'))  
			->load();

	}

	public function data_source_messages()
	{
		$this->build("p4a_db_source", "messages")
			->setTable("messages")
			->addOrder("subject")
			->load();

		$this->build("p4a_db_source", "message_types")
			->setTable("message_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_message_types")
			->setTable("v_message_types")
			->addOrder("type_name")
			->setPK("message_type_id")
			->load();

		$this->build("p4a_db_source", "events")
			->setTable("events")
			->addOrder("event_date")
			->load();

		$this->build("p4a_db_source", "event_types")
			->setTable("event_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_event_types")
			->setTable("v_event_types")
			->addOrder("type_name")
			->setPK("event_type_id")
			->load();

		$this->build("p4a_db_source", "event_stati")
			->setTable("event_stati")
			->addOrder("event_status_id")
			->load();

		$this->build("p4a_db_source", "select_event_stati")
			->setTable("v_event_stati")
			->addOrder("event_status_id")
			->setPK("event_status_id")
			->load();

	}

	public function data_source_persons()
	{
		// Main crm tables
		$this->build("p4a_db_source", "persons")
			->setTable("persons")
			->addOrder("lastname")
			->addJoinLeft("person_types", "persons.person_type_id  = person_types.person_type_id",
					  array('description'=>'type'))
			->setPageLimit(30)
			->load();

		$this->build("p4a_db_source", "select_persons")
			->setTable("v_persons")
			->addOrder("select_name")
			->setPK("person_id")
			->load();

		$this->build("p4a_db_source", "internal_persons")
			->setTable("v_persons")
			->addOrder("select_name")
			->setWhere("v_persons.internal = 1") 
			->setPK("person_id")
			->load();

		$this->build("p4a_db_source", "person_types")
			->setTable("person_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_person_types")
			->setTable("v_person_types")
			->addOrder("description")
			->setPK("person_type_id")
			->load();

		$this->build("p4a_db_source", "contacts")
			->setTable("contacts")
			->addOrder("description")
			->addJoinLeft("contact_types", "contact_types.contact_type_id = contacts.contact_type_id",
					  array('type_name'=>'type'))
			->addJoinLeft("contact_categories", "contact_categories.contact_category_id = contacts.contact_category_id",
					  array('category_name'=>'category'))
			->addJoinLeft("persons", "contacts.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->addJoinLeft("accounts", "contacts.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->load();

		$this->build("p4a_db_source", "select_contacts")
			->setTable("v_contacts")
			->addOrder("contact_select")
			->setPK("contact_id")
			->load();

		$this->build("p4a_db_source", "contact_members")
			->setTable("contact_members")
			->addOrder("contact_id")
			->addJoinLeft("contacts", "contact_members.contact_id = contacts.contact_id",
					  array('description'=>'contact'))
			->addJoinLeft("persons", "contact_members.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->addJoinLeft("contact_member_types", "contact_members.contact_member_type_id = contact_member_types.contact_member_type_id",
					  array('member_type'=>'type'))
			->load();

		$this->build("p4a_db_source", "actions")
			->setTable("actions")
			->addOrder("deadline")
			->addJoinLeft("action_stati", "actions.action_status_id  = action_stati.action_status_id",
					  array('status_text'=>'status'))
			->addJoinLeft("contacts", "actions.create_contact_id = contacts.contact_id",
					  array('description'=>'contact'))
			->load();

                // also used to track the status of client meetings
		$this->build("p4a_db_source", "action_stati")
			->setTable("action_stati")
			->addOrder("status_text")
			->load();

		$this->build("p4a_db_source", "select_action_stati")
			->setTable("v_action_stati")
			->addOrder("status_text")
			->setPK("action_status_id")
			->load();

		$this->build("p4a_db_source", "contact_numbers")
			->setTable("contact_numbers")
			->addOrder("contact_number_id")
			->addJoinLeft("contact_number_types", "contact_numbers.contact_number_type_id = contact_number_types.contact_number_type_id",
					  array('type_name'=>'number_type'))
			->addJoinLeft("persons", "contact_numbers.person_id = persons.person_id",
					  array('lastname'=>'person_name'))
			->addJoinLeft("addresses", "contact_numbers.address_id = addresses.address_id",
					  array('city'=>'address'))
			->load();

                // to seperate the phases of a client relationship and for faster finding
		$this->build("p4a_db_source", "contact_categories")
			->setTable("contact_categories")
			->addOrder("category_name")
			->load();

		$this->build("p4a_db_source", "select_contact_categories")
			->setTable("v_contact_categories")
			->addOrder("category_name")
			->setPK("contact_category_id")
			->load();

		$this->build("p4a_db_source", "addresses")
			->setTable("addresses")
			->addOrder("description")
			->addJoinLeft("countries", "addresses.country_id = countries.country_id",
					  array('name'=>'country'))
			->load();

		$this->build("p4a_db_source", "select_addresses")
			->setTable("v_addresses")
			->addOrder("select_address")
			->setPK("address_id")
			->load();

		$this->build("p4a_db_source", "address_links")
			->setTable("address_links")
			->addOrder("address_id")
			->addJoinLeft("address_link_types", "address_links.address_link_type_id = address_link_types.address_link_type_id",
					  array('type_name'=>'type'))
			->addJoinLeft("addresses", "address_links.address_id = addresses.address_id",
					  array('description'=>'address'))
			->addJoinLeft("persons", "address_links.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->load();

	}

	public function data_source_kyc()
	{
		$this->build("p4a_db_source", "documents")
			->setTable("documents")
			->addOrder("account_id")
			->addJoinLeft("document_types", "document_types.document_type_id = documents.document_type_id",
					  array('type_name'=>'type'))
			->addJoinLeft("persons", "documents.internal_person_id = persons.person_id",
					  array('lastname'=>'internal_person'))
			->addJoinLeft("accounts", "documents.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("banks", "documents.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->load();
			
		$this->build("p4a_db_source", "document_types")
			->setTable("document_types")
			->addOrder("type_name")
			->addJoinLeft("document_categories", "document_categories.document_category_id = document_types.document_category_id",
					  array('category_name'=>'category'))
			->load();

		$this->build("p4a_db_source", "select_document_types")
			->setTable("v_document_types")
			->addOrder("type_name")
			->setPK("document_type_id")
			->load();

		// Category tables
		$this->build("p4a_db_source", "document_categories")
			->setTable("document_categories")
			->addOrder("category_name")
			->load();

		$this->build("p4a_db_source", "select_document_categories")
			->setTable("v_document_categories")
			->addOrder("category_name")
			->setPK("document_category_id")
			->load();

		$this->build("p4a_db_source", "contract_types")
			->setTable("contract_types")
			->addOrder("type_name")
			->load();

	}

	public function data_source_crm()
	{
		$this->build("p4a_db_source", "contact_member_types")
			->setTable("contact_member_types")
			->addOrder("contact_member_type_id")
			->load();

		$this->build("p4a_db_source", "select_contact_member_types")
			->setTable("v_contact_member_types")
			->addOrder("contact_member_type_id")
			->setPK("contact_member_type_id")
			->load();

		$this->build("p4a_db_source", "address_link_types")
			->setTable("address_link_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_address_link_types")
			->setTable("v_address_link_types")
			->addOrder("type_name")
			->setPK("address_link_type_id")
			->load();

		$this->build("p4a_db_source", "contact_number_types")
			->setTable("contact_number_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_contact_number_types")
			->setTable("v_contact_number_types")
			->addOrder("type_name")
			->setPK("contact_number_type_id")
			->load();

		$this->build("p4a_db_source", "contact_types")
			->setTable("contact_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_contact_types")
			->setTable("v_contact_types")
			->addOrder("type_name")
			->setPK("contact_type_id")
			->load();

	}
	
	public function menuClick()
	{
		$this->openMask($this->active_object->getName());
	}
	
	public function openLanguagesPopup()
	{
		$this->openPopup('change_language');
	}
	
	public function login()
	{
		$username = $this->active_mask->username->getNewValue();
		$password = $this->active_mask->password->getNewValue();
		
		$login_status = 0;
		
		// the default admin users; remove in case of a live installation
		if ($username == "timon" and $password == md5("xxx")) {
			$login_status = 1;
		}
		if ($username == "heang" and $password == md5("xxx")) {
			$login_status = 1;
		}
		
		// the default guest user; remove in case of a live installation
		if ($username == "guest" and $password == md5("tream")) {
			$login_status = 1;
		}
		
		if ($login_status == 1) {
			//$this->messageInfo("Login successful");

			$_SESSION['log_user'] = $username;
			//$GLOBALS['log_user'] = $username; // not GLOBAL to avoiv intererences
			$log_user = $username;

                        // set the system user rights
                        if ($username == "timon" or $username == "heang") {
                                $tream_user_type = "admin";
                                $_SESSION['user_type'] = "admin";
                        } 
			
			$this->messageInfo("Login successful as ".$_SESSION['log_user']." (".$_SESSION['user_type'].")");

                        // show the user type depending menus
			if ($_SESSION['user_type'] == 'root' 
                        or $_SESSION['user_type'] == 'admin' 
                        or $_SESSION['user_type'] == 'power_user'
                        or $_SESSION['user_type'] == 'risk') {
                                $this->menu_risk();
                                $this->menu_settings();
                                $this->menu_system();
                        }

                        if ($_SESSION['user_type'] == 'root' 
                        or $_SESSION['user_type'] == 'admin' 
                        or $_SESSION['user_type'] == 'power_user') { 
                                $this->menu_reconciliation();
                        } 

                        // the records of these table may have a link to the program code
                        // this is one reason why not all users should be able to change records in these tables

                        if ($_SESSION['user_type'] == 'root' 
                        or $_SESSION['user_type'] == 'admin') { 
                                $this->menu_admin();
        		} 

			
			// display the default mandate filter
/*			$_SESSION['mandate_filter'] = "all";
			$this->menu->addItem("mandate_filter")
				->setLabel("Mandate ".$_SESSION['mandate_filter'])
//				->setAlign("Right")
				->setFontColor("DarkOrchid");

                        $this->menu->addItem("change_language")
                                ->implement("onclick", $this, "openLanguagesPopup");
*/
			// display the user logged in
			$this->menu->addItem("loguser")
				->setLabel("- ".$_SESSION['log_user']." (".$_SESSION['user_type'].")")
//				->setAlign("Right")
				->setFontColor("Grey");
				
			//$this->menu->refresh;	
			//$this->display("menu", $p4a->menu);

			$this->openMask("open_today");

		} else {
			$this->messageError("Login failed");
			$this->loginInfo();
		}
	}
	
	protected function loginInfo()
	{
		$this->messageInfo('To login type:<br />username: guest<br />password: tream', 'info');
	}

}
