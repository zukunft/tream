<?php
/* 

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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz

 * This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2015 zukunft.com AG, Zurich

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

		// Menu
		$this->build("p4a_menu", "menu");
		
		$this->menu->addItem("open_today")
			//->setAccessKey("o")
			->setLabel("Today")
			->setFontColor("ForestGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("events")
			//->setAccessKey("e")
			->setLabel("Events")
			->setFontColor("DarkGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("contact_menu", "Client activities")
			->setFontColor("DarkGreen");
		$this->menu->items->contact_menu->addItem("contacts")
			->setLabel("Save a client activity")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("actions")
			->setLabel("Add an activity to an existing client activity")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->contact_menu->addItem("contact_members")
			->setLabel("Add a person to a client contact")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("documents")
			//->setAccessKey("d")
			->setFontColor("YellowGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("trades")
			//->setAccessKey("t")
			->setFontColor("DarkGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("portfolios")
			//->setAccessKey("p")
			->setFontColor("YellowGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("accounts")
			//->setAccessKey("n")
			->setFontColor("YellowGreen")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("links")
			//->setAccessKey("l")
			->setFontColor("DarkOrchid")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("persons")
			//->setAccessKey("s")
			->setFontColor("Indigo")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("contact_numbers")
			//->setAccessKey("n")
			->setFontColor("Indigo")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("addresses")
			//->setAccessKey("d")
			->setFontColor("Indigo")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("address_links")
			//->setAccessKey("s")
			->setFontColor("Indigo")
			->implement("onclick", $this, "menuClick");
/*
		$this->menu->addItem("link_masks", "links");
		$this->menu->items->link_masks->addItem("address_number_links")
			->implement("onclick", $this, "menuClick");
*/
		$this->menu->addItem("security_tables", "Securities");
		$this->menu->items->security_tables->addItem("security_triggers")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("securities")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_underlyings")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("currencies")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("currency_pairs")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_field_values")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_fields")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_quote_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_field_source_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_trigger_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_price_feed_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_exposures")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_exposure_stati")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_issuers")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_payments")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_payment_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("security_amount_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("portfolio_types")
			->implement("onclick", $this, "menuClick");
		$this->menu->items->security_tables->addItem("portfolio_security_fixings")
			->implement("onclick", $this, "menuClick");

		if ($tream_user_type == 'root' 
		or $tream_user_type == 'admin' 
		or $tream_user_type == 'power_user'
		or $tream_user_type == 'risk') {
			$this->menu->addItem("support_tables", "Support Tables");
			$this->menu->items->support_tables->addItem("exposure_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("exposure_items")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("exposure_item_values")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("exposure_targets")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("exposure_exceptions")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("trade_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("trade_stati")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("trade_payments")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("trade_payment_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("trade_confirmation_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("event_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("event_stati")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("account_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("account_mandates")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("account_persons")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("account_person_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("person_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("address_link_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("countries")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("contact_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("contact_number_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("contact_categories")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("document_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("document_categories")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("messages")
				->implement("onclick", $this, "menuClick"); 
			$this->menu->items->support_tables->addItem("message_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("contract_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("banks")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("values")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("value_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->support_tables->addItem("value_stati")
				->implement("onclick", $this, "menuClick");
		}

		if ($tream_user_type == 'root' 
		or $tream_user_type == 'admin' 
		or $tream_user_type == 'power_user') {
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

		// the records of these table may have a link to the program code
		// this is one reason why not all users should be able to change records in these tables
		if ($tream_user_type == 'root' 
		or $tream_user_type == 'admin') {
			$this->menu->addItem("administration", "Administration");
			$this->menu->items->administration->addItem("Portfolio_rights")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->administration->addItem("Users")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->administration->addItem("User_groups")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->administration->addItem("User_assigns")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->administration->addItem("User_types")
				->implement("onclick", $this, "menuClick");
			$this->menu->items->administration->addItem("User_rights")
				->implement("onclick", $this, "menuClick"); 
		}

			
/*
		$this->menu->addItem("change_language")
			->implement("onclick", $this, "openLanguagesPopup");
*/
		// Data sources
		// Main portfolio management tables
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
			->setWhere("v_portfolios.inactive <> 1") 
			->setPK("portfolio_id")
			->load();

		$this->build("p4a_db_source", "portfolio_types")
			->setTable("portfolio_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_portfolio_types")
			->setTable("v_portfolio_types")
			->addOrder("type_name")
			->setPK("portfolio_type_id")
			->load();

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

		$this->build("p4a_db_source", "underlyings")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPageLimit(30)
			->setPK("security_id")
			->load();

		$this->build("p4a_db_source", "security_underlyings")
			->setTable("security_underlyings")
			->addJoinLeft("securities", "security_underlyings.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("v_underlyings", "security_underlyings.underlying_id  = v_underlyings.underlying_id",
					  array('name'=>'underlying'))
			->addOrder("security_id")
			->load();

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

		$this->build("p4a_db_source", "portfolio_security_fixings")
			->setTable("portfolio_security_fixings")
			->addJoinLeft("securities", "portfolio_security_fixings.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("portfolios", "portfolio_security_fixings.portfolio_id  = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addOrder("security_id")
			->load();

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

		// Select tables for main tables
		$this->build("p4a_db_source", "select_securities")
			->setTable("v_securities")
			->addOrder("select_name")
			->setPK("security_id")
			->load();

		// type 1 is partner and 6 is intern (should be fixed in the database
/*		$this->build("p4a_db_source", "tp_persons")
			->setTable("persons")
			->addOrder("display_name")
			->setWhere("persons.person_type_id = 1 or persons.person_type_id = 6") 
			->load(); */

		$this->build("p4a_db_source", "internal_persons")
			->setTable("v_persons")
			->addOrder("select_name")
/*			->setWhere("v_persons.person_type_id IS NULL or v_persons.person_type_id = 1 or v_persons.person_type_id = 6") better exclude some persons */
/*			->setWhere("v_persons.person_type_id = 1 or v_persons.person_type_id = 6 or v_persons.person_type_id = 7")  */
			->setWhere("v_persons.internal = 1") 
			->setPK("person_id")
			->load();

		// Type tables - portfolio management
		$this->build("p4a_db_source", "security_types")
			->setTable("security_types")
			->addOrder("description")
			->addJoinLeft("security_quote_types", "security_types.security_quote_type_id  = security_quote_types.security_quote_type_id",
					  array('type_name'=>'quote_type')) 
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

		$this->build("p4a_db_source", "security_field_source_types")
			->setTable("security_field_source_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_field_source_types")
			->setTable("v_security_field_source_types")
			->addOrder("type_name")
			->setPK("security_field_source_type_id")
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

		// Type tables - crm
		$this->build("p4a_db_source", "contract_types")
			->setTable("contract_types")
			->addOrder("type_name")
			->load();

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

		// Stati tables
		$this->build("p4a_db_source", "action_stati")
			->setTable("action_stati")
			->addOrder("status_text")
			->load();

		$this->build("p4a_db_source", "select_action_stati")
			->setTable("v_action_stati")
			->addOrder("status_text")
			->setPK("action_status_id")
			->load();

		// type select tables
		$this->build("p4a_db_source", "select_security_types")
			->setTable("v_security_types")
			->addOrder("description")
			->setPK("security_type_id")
			->load();

		$this->build("p4a_db_source", "account_types")
			->setTable("account_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_account_types")
			->setTable("v_account_types")
			->addOrder("description")
			->setPK("account_type_id")
			->load();

		$this->build("p4a_db_source", "account_mandates")
			->setTable("account_mandates")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_account_mandates")
			->setTable("v_account_mandates")
			->addOrder("description")
			->setPK("account_mandat_id")
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

		$this->build("p4a_db_source", "trade_stati")
			->setTable("trade_stati")
			->addOrder("status_text")
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

		$this->build("p4a_db_source", "security_exposure_stati")
			->setTable("security_exposure_stati")
			->addOrder("status_text")
			->load();

		$this->build("p4a_db_source", "security_exposure_stati_select")
			->setTable("v_security_exposure_stati")
			->addOrder("status_text")
			->setPK("security_exposure_status_id")
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

		$this->build("p4a_db_source", "trade_types")
			->setTable("trade_types")
			->addOrder("description")
			->load();

		$this->build("p4a_db_source", "select_trade_types")
			->setTable("v_trade_types")
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
			->addOrder("status_text")
			->setPK("trade_status_id")
			->load();

		$this->build("p4a_db_source", "trade_select")
			->setTable("v_trade_select")
			->setPK("trade_id")
			->addOrder("trade_key")
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

		$this->build("p4a_db_source", "users")
			->setTable("log_users")
			->addJoinLeft("log_user_types", "log_users.user_type_id  = log_user_types.user_type_id",
					  array('type_name'=>'type'))
			->addOrder("username")
			->load();

		$this->build("p4a_db_source", "user_select")
			->setTable("v_log_users")
			->setPK("user_id")
			->addOrder("username")
			->load();

		$this->build("p4a_db_source", "user_types")
			->setTable("log_user_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "user_group_select")
			->setTable("v_log_user_groups")
			->setPK("user_group_id")
			->addOrder("group_name")
			->load();

		$this->build("p4a_db_source", "user_groups")
			->setTable("log_user_groups")
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

		$this->build("p4a_db_source", "user_right_select")
			->setTable("v_log_user_rights")
			->setPK("user_right_id")
			->addOrder("right_name")
			->load();

		$this->build("p4a_db_source", "user_rights")
			->setTable("log_user_rights")
			->addOrder("right_name")
			->load();

		$this->build("p4a_db_source", "user_type_select")
			->setTable("v_user_types")
			->setPK("user_type_id")
			->addOrder("type_name")
			->load();

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

		$this->build("p4a_db_source", "exposure_types")
			->setTable("exposure_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_exposure_types")
			->setTable("v_exposure_types")
			->addOrder("type_name")
			->setPK("exposure_type_id")
			->load();

		$this->build("p4a_db_source", "account_persons")
			->setTable("account_persons")
			->addJoinLeft("accounts", "account_persons.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("persons", "account_persons.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->addJoinLeft("account_person_types", "account_persons.account_person_type_id = account_person_types.account_person_type_id",
					  array('type_name'=>'type'))
			->addOrder("account_id")
			->load();

		$this->build("p4a_db_source", "account_person_types")
			->setTable("account_person_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "account_person_type_select")
			->setTable("v_account_person_types")
			->addOrder("type_name")
			->setPK("account_person_type_id")
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

		$this->build("p4a_db_source", "security_price_feed_types")
			->setTable("security_price_feed_types")
			->addOrder("type_name")
			->load();

		$this->build("p4a_db_source", "select_security_price_feed_types")
			->setTable("v_security_price_feed_types")
			->addOrder("type_name")
			->setPK("feed_type_id")
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

		$this->build("p4a_db_source", "contact_categories")
			->setTable("contact_categories")
			->addOrder("category_name")
			->load();

		$this->build("p4a_db_source", "select_contact_categories")
			->setTable("v_contact_categories")
			->addOrder("category_name")
			->setPK("contact_category_id")
			->load();

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

		$this->build("p4a_db_source", "currencies")
			->setTable("currencies")
			->addOrder("symbol")
			->load();

		$this->build("p4a_db_source", "select_currencies")
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

		$this->build("p4a_db_source", "banks")
			->setTable("banks")
			->addOrder("bank_name")
			->load();

		$this->build("p4a_db_source", "select_banks")
			->setTable("v_banks")
			->addOrder("bank_name")
			->setPK("bank_id")
			->load();

		// views

		$this->build("p4a_db_source", "v_bill")
			->setTable("v_bill")
			->addOrder("account_id")
			->load();

		
			// Primary action
		//echo get_current_user();
		$this->openMask("open_today");
		/*
		$this->active_mask->implement('onLogin', $this, 'login');
		$this->active_mask->username->setTooltip("your username");
		$this->active_mask->password->setTooltip("Type db here");
		$this->loginInfo();
		*/
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
		
		if (($username == "timon" or $username == "thomas" or $username == "patrick") and $password == md5("db")) {
			$this->messageInfo("Login successful");
			$this->openMask("persons");
		} else {
			$this->messageError("Login failed");
			$this->loginInfo();
		}
	}
	
	protected function loginInfo()
	{
		$this->messageInfo('To login type:<br />username: p4a<br />password: p4a', 'info');
	}

}
